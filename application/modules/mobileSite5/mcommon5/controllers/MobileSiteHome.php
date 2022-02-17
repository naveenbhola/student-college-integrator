<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MobileSiteHome extends ShikshaMobileWebSite_Controller
{
	function __construct()
	{
		parent::__construct();
	}

	function init(){
		$this->load->config('mcommon5/mobi_config');
		$this->load->model('homepagemodel');
	}

	function renderHomePage()
	{
		$this->init();
		$data = array();
		
		//added by akhter
		//$resetPage variable is using to reset page caching and rewrite html of middle panel and also using in js as variable
		$data['resetPage'] = (isset($_GET['resetPage']) && ($_GET['resetPage'] == '1')) ? true : false;
		$data['validateuser'] = $this->logged_in_user_array; //empty array in logged out case
		$data['userPrefData'] = array();
		if(!empty($data['validateuser']))
		{	
			$result = Modules::run('registration/RegistrationForms/getLoggedInUserDetails',false);
			$data['userPrefData'][$result['stream']['value']]  = $result['baseCourses']['value'];
		}
		
		//$data['activelink'] = 'home';
		$data['boomr_pageid'] = "home";
		$data['m_canonical_url'] = SHIKSHA_HOME;
		$data['dns_prefetch']=getDNSPrefetchLinks('MOBILE');
		//for tracking purpose
		$data['trackForPages'] = true;
		//below line is used for storing information in beacon variable for tracking purpose
		$data['beaconTrackData'] = array(
			'pageIdentifier' => 'homePage',
			'pageEntityId'   => 0,
			'extraData'      => array(
				'countryId'	=> 2
			)
		);

		$this->load->helper('mobile_html5');
		$data['categoryMap'] = $this->config->item('categoryMap');
		$data['hierarchyMap']   = $this->config->item('hierarchyMap');
		$data['tabsOnHomepage'] = $this->config->item('tabsOnHomepage');
		$tabCookie = (isset($_COOKIE['hpTab']) && $_COOKIE['hpTab']!='' && in_array($_COOKIE['hpTab'], array_keys($data['tabsOnHomepage']))) ? $_COOKIE['hpTab'] : '';
		$data['tabSelected'] = getSelectedTabForUser($data['userPrefData'], $tabCookie, $data['tabsOnHomepage']);
		if ($tabCookie == '') {
			setcookie('hpTab', $data['tabSelected'], time() + 30*24*60*60, '/', COOKIEDOMAIN);
		}
		$data['remainingTabs'] = getRemainingTabsDataForHomepage($data['tabsOnHomepage'], $data['tabSelected']);
		$data['noJqueryMobile'] = true;
		$data['predBannerStream'] = 'btech';
		$this->_loadView('india', $data);
	}

	function getCategoryPageUrlForLocation($data, $returnType=false, $fromAMP='')
	{
		$location    = $data['location'];
		$source      = !empty($data['source']) ? $data['source'] : 'homepage';
		$filters = array();
		if(empty($data)){
			$data['stream_id']    = $this->input->post('userStream');
			$data['substream_id'] = $this->input->post('userSubStream');
			$data['specialization_id'] = $this->input->post('userSpecId');
			$data['base_course_id']    = $this->input->post('userBaseCourse');
			$edu_type                  = $this->input->post('userEduType');
			$location                  = $this->input->post('location');
			if(empty($location)){
				$location = 'city_1';
			}
			$source = $this->input->post('source');
			if(empty($source)){
				$source = 'homepage';
			}
		}

		$data['education_type'] = !empty($data['education_type']) ? $data['education_type'] : $edu_type;

		// If stream + baseCourse selected then, will redirect to stream + location category page with apply filter on base_course
		if(empty($data['education_type']) && !empty($data['base_course_id'])){
			$filters['base_course'] = $data['base_course_id'];
			unset($data['base_course_id'],$data['education_type']);
		}

		$data['country_id'] = 2;

		$loc = explode('_', $location);
		if($loc[0] == 'city'){
			$data['city_id'] = $loc[1];
		}else if($loc[0] == 'state'){
			$data['state_id'] = $loc[1];
		}

		if(!empty($data['specialization_id'])){	
			$filters['specialization'] = $data['specialization_id'];
			unset($data['specialization_id']);
		}
		unset($data['source'], $data['location']);

		$obj = $this->load->library('nationalCategoryList/NationalCategoryPageLib');
		$url = $obj->getUrlByParams($data,$filters);
		
		if($fromAMP != 'hamburger' && (!in_array($data['stream_id'],array(DESIGN_STREAM,IT_SOFTWARE_STREAM,ANIMATION_STREAM))) ){
			setcookie('selectedLocationType', $loc[0], time() + (30*24*60*60) ,'/',COOKIEDOMAIN);
			setcookie('selectedLocation', $loc[1], time() + (30*24*60*60) ,'/',COOKIEDOMAIN);	
		}
		if($returnType){
			return $url;
		}else{
			echo json_encode($url);
		}
	}

	function getLocationOnStream(){

		$streamId    = $this->input->post('streamId');
		$subStreamId = $this->input->post('subStreamId');
		$specId      = $this->input->post('specId');
		$baseCourse  = $this->input->post('baseCourse');
		$source      = $this->input->post('source');
		$location    = $this->input->post('locationStr');
		$edu_type    = $this->input->post('edu_type');
		
		static $roundNumber = 1;
		
		$this->load->library('search/Solr/AutoSuggestorSolrClient');
        $autoSuggestorSolrClient = new AutoSuggestorSolrClient;
        $data['getLocations'] = 1;

        if($location!='' && $roundNumber == 1){
        	unset($data['getLocations']);
        	$loc = explode('_', $location);
        	if($loc[0] == 'state'){
        		$data['filters']['state'] = array($loc[1]);			
        	}
        	if($loc[0] == 'city'){
        		$data['filters']['city'] = array($loc[1]);			
        	}
        }
        
        if(!empty($streamId)) {
        	$data['filters']['stream'] = array($streamId);
        } 
        if(!empty($subStreamId)){
        	$data['filters']['substream'] = array($subStreamId);			
        }
        if(!empty($specId)){
        	$data['filters']['specialization'] = array($specId);	
        }
        if(!empty($baseCourse)){
        	$data['filters']['base_course'] = array($baseCourse);
        }
        
        $solrFilterResults = $autoSuggestorSolrClient->getAdvancedFilterOnEntitySelection($data);

        if(!empty($location) && $roundNumber == 1){
			// get all locations if zero results on that location
			if(empty($solrFilterResults['numberOfResults'])){
				$roundNumber++;
				$this->getLocationOnStream();
				return;
			}else{
				// get category page url
				$data = array();
				$data['stream_id'] = $streamId;
				$data['substream_id'] = $subStreamId;
				$data['specialization_id']   = $specId;
				$data['base_course_id'] = $baseCourse; 
				$data['location'] = $location;
				$data['source'] = $source;
				$data['education_type'] = $edu_type;
				$solrFilterResults['categoryPageUrl'] = $this->getCategoryPageUrlForLocation($data,true);
			}
		}
        echo json_encode($solrFilterResults);
	}

	function getListDataForHomepageLayers($streamName = 'Engineering'){
	
		if($this->input->is_ajax_request()){
			$listType = isset($_POST['listType']) && $_POST['listType']!='' ? $this->input->post('listType') : 'exmLyr';
			$streamName = isset($_POST['streamName']) && $_POST['streamName']!='' ? $this->input->post('streamName') : 'Engineering';	
			switch ($listType) {
				case 'exmLyr':
					//exam list based on category
					$examCat = isset($_POST['examCat']) && $_POST['examCat']!='' ? $this->input->post('examCat') : 'mba';
					echo $this->getExamListForLayer($examCat);
					break;
				case 'cpLyr':
					//college predictor list
					echo $this->getCollegePredictorListForLayer($streamName);
					break;
				case 'rpLyr':
					//rank predictor list
					echo $this->getRankPredictorListForLayer();
					break;			
				default:
					echo json_encode(array());
					break;
			}
		}
	}

	private function getExamListForLayer($examCat){
		$NUMBER_OF_POPULAR_COURSES = 4;
		$this->ExamPageCache = $this->load->library('examPages/cache/ExamPageCache');
		$examDetails = $this->ExamPageCache->getHierarchiesWithExamNames();
		switch($examCat){
			case 'mba':
				$examDetails =  $examDetails['course'][MANAGEMENT_COURSE];
				break;
			case 'engineering': //ENGINEERING_COURSE
				$examDetails =  $examDetails['course'][ENGINEERING_COURSE];
				break;

			case 'design':
			case 'law':
				$this->load->builder('ListingBaseBuilder', 'listingBase');
	        	$this->ListingBaseBuilder    = new ListingBaseBuilder();
	        	$this->hierarchyRepo = $this->ListingBaseBuilder->getHierarchyRepository();
	        	$streamId = ($examCat == 'design') ? DESIGN_STREAM:LAW_STREAM;
	        	$hierarchyId = $this->hierarchyRepo->getHierarchyIdByBaseEntities($streamId,'none','none');
	        	$hierarchyId = $hierarchyId[0];
				$examDetails = $examDetails['hierarchy'][$hierarchyId];
				break;
		}
		$examData = array();
		foreach ($examDetails as $examId => $examDetail) {
			$examData[] = array(
				'id' => $examId,
				'name' => $examDetail['examName'] ,
				'url' => $examDetail['url']
				);
		}
		$popularExams = array_slice($examData, 0, $NUMBER_OF_POPULAR_COURSES);
		$otherExams   = array_slice($examData, $NUMBER_OF_POPULAR_COURSES);
		return json_encode(array('popularList'=>$popularExams, 'otherList'=>$otherExams));
		//_p($examData);die;
		// -------------------------------------------------------------------------------------
		/*
			$NUMBER_OF_POPULAR_COURSES = 4;
			$examPageModel = $this->load->model('examPages/exampagemodel');
			$result = $examPageModel->getListOfExams($examCat);
			$this->load->library('examPages/ExamPageRequest');
			foreach ($result as &$value) {
				$examPageRequest = new ExamPageRequest($value['examName']);
				$examUrl = $examPageRequest->getUrl();
				$value['name'] = $value['examName'];
				$value['id'] = $value['exampageId'];
				$value['url'] = $examUrl['url'];
				unset($value['examDescription']);
				unset($value['examName']);
				unset($value['exampageId']);
			}
			$popularExams = array_slice($result, 0, $NUMBER_OF_POPULAR_COURSES);
			$otherExams   = array_slice($result, $NUMBER_OF_POPULAR_COURSES);
			return json_encode(array('popularList'=>$popularExams, 'otherList'=>$otherExams));
		*/
	}

	private function getCollegePredictorListForLayer($streamName){
		$this->load->config('CP/CollegePredictorConfig',TRUE);
		$settings = $this->config->item('settings','CollegePredictorConfig');
		$id = 1;
		$popularCP = array();
		$otherCP = array();
		$cPredictor = $settings['CPEXAMS'];
		ksort($cPredictor);
		$collegepredictorlibrary = $this->load->library('CP/CollegePredictorLibrary');
		foreach ($cPredictor as $cp => $val) {
			if($collegepredictorlibrary->isValidPredictorForStream($cp, $streamName)) {
				if(!in_array($cp, $settings['CPPOPULAR'])) {
					$otherCP[] = array('id'=>$id, 'name'=>strtoupper(substr($val['shortName'],0,-4)), 'url'=>$val['directoryName'].'/'.$val['collegeUrl']);
				}else{
					$popularCP[] = array('id'=>$id, 'name'=>strtoupper(substr($val['shortName'],0,-4)), 'url'=>$val['directoryName'].'/'.$val['collegeUrl']);
				}
			}
			$id++;
		}
		return json_encode(array('popularList'=>$popularCP, 'otherList'=>$otherCP));
	}

	private function getRankPredictorListForLayer(){
		$this->load->config('RP/RankPredictorConfig',TRUE);
		$examList = $this->config->item('settings','RankPredictorConfig');
		$examList = $examList['RPEXAMS'];
		ksort($examList);
		$data = array();
		$id = 1;
		foreach ($examList as $rp => $val) {
			//$data[] = array('id'=>$id, 'name'=>strtoupper(substr($val['name'],0,-4)), 'url'=>SHIKSHA_HOME.'/'.$val['url']);
			$data[] = array('id'=>$id, 'name'=>strtoupper(substr($val['name'],0,-4)), 'url'=>RANK_PREDICTOR_BASE_URL.'/'.$val['url']);
			$id++;
		}
		return json_encode(array('popularList'=>$data, 'otherList'=>array()));
	}
	
	/*
	Function to fetch HTML for Sub-category Div on Homepage
	*/
	function showSubCategoriesHTML($id="3")
	{
		$this->init();
		$data = array();
		$data['key'] = $id;
		$this->load->library('category_list_client');
		$data['HomePageData'] = $this->category_list_client->getTabsContentByCategory();
		$data['HomePageType'] = 'india';
		echo $this->load->view('subcategoryLayer',$data);
	}

	/*
	Function to fetch HTML for Sub-category Div on Homepage
	*/
	function showLocationHTML()
	{
		$this->load->builder('LocationBuilder','location');
		$locationBuilder = new LocationBuilder;
		$locationRepository = $locationBuilder->getLocationRepository();
		$cityList = $locationRepository->getCitiesByMultipleTiers(array(1,2,3),2);
		$data['cityList'] = $cityList;
		$data['locationRepository'] = $locationRepository;
		echo $this->load->view('locationLayer',$data);
	}
	
	/*
	Function to fetch HTML for Country Div on Homepage
	*/
	function showCountryHTML()
	{
		$this->load->library('categoryList/CategoryPageRequest');
		$this->load->library('category_list_client');
		
		$request_object = new CategoryPageRequest();
		$this->load->builder('LocationBuilder','location');
		$locationBuilder = new LocationBuilder;
		$locationRepository = $locationBuilder->getLocationRepository();
		$data['countries1'] = $locationRepository->getCountriesByRegion(1);
		$data['countries2'] = $locationRepository->getCountriesByRegion(2);
		$data['countries3'] = $locationRepository->getCountriesByRegion(3);
		$data['countries4'] = $locationRepository->getCountriesByRegion(4);

		echo $this->load->view('countryLayer',$data);
	}
	
	/*
	This function will be called from Browse Institute flow. This will find the Category page URL and redirect user to correct page
	*/
	function browseInstitute(){
		//Fetch the Post variables
		$catId = $this->input->post('categoryIdSelected');
		$subCatId = $this->input->post('subcategoryIdSelected');	
		$locationId = $this->input->post('locationIdSelected');
		$isStudyAbroad = $this->input->post('isStudyAbroad');
		$countryId = $this->input->post('countryIdSelected');
		$regionId = $this->input->post('regionIdSelected');
		$locationTypeSelected = $this->input->post('locationTypeSelected');
		$type = isset($_POST['type'])?$this->input->post('type'):'';

		//Fetch the Category page URL
		$this->load->library('categoryList/categoryPageRequest');
		$URLRequest = new CategoryPageRequest;
		if($isStudyAbroad == "0"){
			if($locationTypeSelected=='city'){
				$URLRequest->setData(array('categoryId' => $catId,'subCategoryId'=>$subCatId,'cityId'=>$locationId));
			}
			if($locationTypeSelected=='state'){
				$URLRequest->setData(array('categoryId' => $catId,'subCategoryId'=>$subCatId,'stateId'=>$locationId));
			}
		}
		else{
			$URLRequest->setData(array('categoryId' => $catId,'regionId'=>$regionId,'countryId'=>$countryId));
		}
		
		$categoryPageURL = $URLRequest->getURL();
		if($type=='ajax'){
			echo $categoryPageURL;
		}else{
		//Redirect
                header("Location: $categoryPageURL");
                exit;		
		}
	}

	/*
	 This function will check whether the City found using Geo-location is available in our DB or not
	*/
	function checkForCityName($cityFound){
		$this->load->builder('LocationBuilder','location');
		$locationBuilder = new LocationBuilder;
		$locationRepository = $locationBuilder->getLocationRepository();
		$cityList = $locationRepository->getCitiesByMultipleTiers(array(1,2,3),2);
		$this->load->model('location/locationmodel');
		foreach($cityList[1] as $city){
			if($city->getName() == $cityFound){
				$result = $this->locationmodel->getVirtualCityId($city->getId());
                                if(!empty($result)){
                                        echo $result['city_id'].'#'.$result['city_name'];

                                }else{
                                        echo $city->getId().'#'.$cityFound;
                                }
				return;
			}
		}
		foreach($cityList[2] as $city){
			if($city->getName() == $cityFound){
				$result = $this->locationmodel->getVirtualCityId($city->getId());
				if(!empty($result)){
					echo $result['city_id'].'#'.$result['city_name'];
					
				}else{
					echo $city->getId().'#'.$cityFound;
				}
				return;
			}
		}
		foreach($cityList[3] as $city){
			if($city->getName() == $cityFound){
				$result = $this->locationmodel->getVirtualCityId($city->getId());
                                if(!empty($result)){
                                        echo $result['city_id'].'#'.$result['city_name'];

                                }else{
                                        echo $city->getId().'#'.$cityFound;
                                }
				return;
			}
		}
		echo 0;
	}
	
	function _loadView($type='india',$data)
	{
		if(!empty($_REQUEST['mailerId']) && !empty($_REQUEST['mailId']) && !empty($_REQUEST['mailReportSpam'])) {
			$this->load->view('mailerReportSpam');
		}
		else if(!empty($_REQUEST['encodedMail']) && !empty($_REQUEST['mailerUnsubscribe'])) {
			$objmailerClient = $this->load->library('mailer/MailerClient');
			$result = $objmailerClient->autoLogin(1,$_REQUEST['encodedMail']);
			setcookie('user',$result,0,'/',COOKIEDOMAIN);			
			$redirectUrl = SHIKSHA_HOME."/userprofile/edit?unscr=5";
			$redirectUrl = Modules::run('mailer/Mailer/processRedirectUrl', $redirectUrl,$result,'');
			$redirectUrl = $redirectUrl['redirectUrl'];
			header( "Location: $redirectUrl" );
			exit();
		}
		else {
			//$data['HomePageType'] = 'india';
			storeTempUserData("countriesArray","2,");
			$data['autoSuggestorPageName'] = 'homepage_mobile';
			$data['pageName'] = 'home';
			$data['pageType'] = 'homepage';

			$homepagemiddlepanelcache = "HomePageRedesignCache/mobileMiddlepanel.html";
			$this->load->config('CP/CollegePredictorConfig',TRUE);
	        $collegepredictorlibrary = $this->load->library('CP/CollegePredictorLibrary');
	        $settings = $this->config->item('settings','CollegePredictorConfig');
	        $data['designPredictorUrl']['url'] = $collegepredictorlibrary->generateCollegePredictorUrl($settings['CPEXAMS']['NIFT']);
	        $data['designPredictorUrl']['info'] = $settings['CPEXAMS']['NIFT'];

			$this->load->view('homepage',$data);
		}
	}

	function Unsubscribe($encodedMail)
	{
		$this->load->library('mailer/MailerClient');
		$objmailerClient = new MailerClient;
		$objmailerClient->unsubscribe($this->appId,$encodedMail);
		//for tracking purpose
		$data['trackingpageIdentifier']='unsubscribePage';
		$data['trackingcountryId']=2;
		//below line is used for storing information in beacon variable for tracking purpose
		$this->tracking=$this->load->library('common/trackingpages');
		$this->tracking->_pagetracking($data);
		$this->load->view('mailerUnsubscribeResponse',$data);
	}

	function recordMailerReportSpam($mailerId,$mailId)
	{
		$validity = $this->checkUserValidation();
		if(!empty($validity[0]['userid'])) {
			$this->load->model('mailer/mailermodel');
			$this->mailermodel->recordMailerReportSpam($validity[0]['userid'],$mailerId,$mailId);
			$this->load->view('mailerReportSpamResponse');
		}
		else {
			header("Location: ".SHIKSHA_HOME);
			exit();
		}
	}

	function populateExams(){
			$categorypagemodel     = $this->load->model('categoryList/categorypagemodel');
			$input = array('categoryId' => 3,'subCategoryId' => 23);
			$output = $categorypagemodel->getExamNameForCatgoryIdAndSubcatID($input);
			$data['exams'] = $output;
			echo $this->load->view('examSelectionLayer',$data);
	}
	 /*
        Function to fetch HTML for Sub-category Div on Homepage
        */
        function showLocationHTMLForExams()
        {
        return;
		$examSelected = base64_decode($this->input->post('examSelected'));
		$arrayOfExams  = json_decode($examSelected,true);                
		$categorypagemodel     = $this->load->model('categoryList/categorypagemodel');
		$data = $categorypagemodel->getLocationFromExam($arrayOfExams['exams'],'city');
		$data['cityList'] = $data['city'];
                echo $this->load->view('locationLayerForExams',$data);
        }

	function checkLocationExists(){
		$currentLocation = $_COOKIE['currentGeoLocation'];
		$examSelected = base64_decode($this->input->post('examSelected'));
		$arrayOfExams  = json_decode($examSelected,true);
		$categorypagemodel     = $this->load->model('categoryList/categorypagemodel');
		$data = $categorypagemodel->getLocationFromExam($arrayOfExams['exams'],'city');
		$data['cityList'] = $data['city'];
		$returnStr = 'NORESULT';		
		foreach($data['cityList'] as $key=>$value){
			for($i=0;$i<count($value);$i++){
				if(base64_encode($value[$i]->getName())==base64_encode($currentLocation)){
					if($value[$i]->getStateId()=='-1'){
						$returnStr =  $currentLocation.':'.$value[$i]->getId().':1';
					}else{				
						$returnStr =  $currentLocation.':'.$value[$i]->getId().':'.$value[$i]->getStateId();
					}
				}
			}
		}
		echo $returnStr; 
	}
	
	function mobile_track_autosuggestor(){
		//Get Keyword, suggestion, pagename from Post
		$requestArr['keywordText'] = $this->input->post('keyword');
		$requestArr['pageName'] = $this->input->post('searchPageName');
		$requestArr['suggestionText'] = $this->input->post('suggestionText');
		$requestArr['userId'] = $this->input->post('userId');
		$this->load->model('ShikshaModel');
		$this->ShikshaModel->autoSuggestorTracking($requestArr);
		return true;		
	}
	
	function createArticleHTML($subCat=23){
		$appId = 1;
		$orderBy = ' lastModifiedDate desc ';
		$startOffset = 0;
		$countOffset = 5;
		$criteria['subcat'] = $subCat;
		$criteria['blogType'] = 'popular';
                $this->load->library('blog_client');
				$blog_client = new Blog_client();
                $articlesList = $blog_client->getArticlesForCriteria($appId, $criteria, $orderBy, $startOffset, $countOffset);
                if(is_array($articlesList)){
                        $displayData = $articlesList[0]['results'];
                }
		$displayData['articleURL'] = SHIKSHA_HOME."/blogs/shikshaBlog/showArticlesList";
		$displayData['subcat'] = $subCat;
		$this->load->view('articleWidget',$displayData);		
	}
	

	function createPopularComparisonHTML($subCat=23){
		$this->load->library('compareInstitute/ComparisonLibrary');
                $comparisonLibraryObj = new comparisonLibrary();
                $comparisonList = $comparisonLibraryObj->getPopularComparisonForHomepage($subCat);
                if(is_array($comparisonList)){
                        $displayData['comparisonList'] = $comparisonList;
                }
		$displayData['comparisonURL'] = SHIKSHA_HOME."/compare-colleges";
		$displayData['subcat'] = $subCat;
		$this->load->view('comparisonWidget',$displayData);				
	}
	
	function mobileRankingWidgetBackend(){
		$this->init();
		$cmsUserInfo = Modules::run('enterprise/Enterprise/cmsUserValidation');
		$userid = $cmsUserInfo['userid'];
		$usergroup = $cmsUserInfo['usergroup'];
		$thisUrl = $cmsUserInfo['thisUrl'];
		$validity = $cmsUserInfo['validity'];
		$flagMedia = 1;
		
		$appId = 12;	
		$cmsPageArr = array();
		$cmsPageArr['prodId'] = 804;
		$cmsPageArr['validateuser'] = $validity;
		$cmsPageArr['headerTabs'] =  $cmsUserInfo['headerTabs'];
		$cmsPageArr['myProducts'] = $cmsUserInfo['myProducts'];
		$loggedInUserId = isset($userid)?$userid:0;
		$this->load->model('rankingWidgetBackend_Model');
		$cmsPageArr['mobileRankingDataArray'] = $this->rankingWidgetBackend_Model->getDataForMobileRankingWidget();
		$this->load->view('rankingWidgetBackendView',$cmsPageArr);
		
	}
	
	
	function mobileRankingWidgetData(){
		$this->load->model('rankingWidgetBackend_Model');
		// update status of previous enteries
		$res = $this->rankingWidgetBackend_Model->rankingWidgetUpdateStatus();
		for($i=0;$i<count($_POST['courseTitle']);$i++)
		{
			$courseName = ($_POST['courseName'][$i] !='') ? $_POST['courseName'][$i] : '';
			$courseTitle = $_POST['courseTitle'][$i];
			$courseDescription = $_POST['courseDescription'][$i];
			$courseLink = ($_POST['courseLink'][$i] !='') ? $_POST['courseLink'][$i] : '';
		        $results = $this->rankingWidgetBackend_Model->rankingWidgetMobileHomeModel($courseName,$courseTitle,$courseDescription,$courseLink);
		}
		
		if($results>0)
		{
			setcookie('showSuccessMessageOnSave','yes', time() + 3600 ,'/',COOKIEDOMAIN);
			redirect(SHIKSHA_HOME.'/mcommon5/MobileSiteHome/mobileRankingWidgetBackend');
		}
		
	}
	
	function loadRankingWidget(){
                $preview = (isset($_GET['preview']) && $_GET['preview'] == 1)?true:false;
                $this->load->library('Common/cacheLib');
                $this->cacheLib = new cacheLib();
                $ckey = 'MobileHomepageRankingConfig';
                if($preview || $this->cacheLib->get($ckey)=='ERROR_READING_CACHE'){
                    $this->load->model('rankingWidgetBackend_Model');
                    $data['rankingDataArray'] = $this->rankingWidgetBackend_Model->getDataForMobileRankingWidget();
                    if(!$preview)
                    {
                            $this->cacheLib->store($ckey, $data['rankingDataArray'] , 30*24*3600);
                    }
                }
                else{
                    $data['rankingDataArray'] = $this->cacheLib->get($ckey);
                }
                $this->load->view('rankingWidget',$data);		
	}
	
	function clearRankingCache(){
                $this->load->library('Common/cacheLib');
                $this->cacheLib = new cacheLib();
                $ckey = 'MobileHomepageRankingConfig';
		$this->cacheLib->clearCacheForKey($ckey);
	}	

        function checkPerformance($type="india")
        {
                $data = array();
                $data['HomePageData'] = $this->getTabsContentByCategory;
                $data['activelink'] = 'home';
                $data['boomr_pageid'] = "home";
                $data['m_canonical_url'] = SHIKSHA_HOME;
                $data['dns_prefetch']=getDNSPrefetchLinks('MOBILE');
                //$popularCoursesArray = $this->config->item('popularCourses');

                /* load config from db - start */
                $popularCoursesArray = array();
                $preview = (isset($_GET['preview']) && $_GET['preview'] == 1)?true:false;
                $this->load->library('Common/cacheLib');
                $this->cacheLib = new cacheLib();
                $ckey = 'MobileHomepagePopularCourseConfig';
                if($preview || $this->cacheLib->get($ckey)=='ERROR_READING_CACHE'){
                        $this->load->model('enterprise/mobilehomepagemodel');
                        $mobileHomepageModel = new mobilehomepagemodel();
                        $popularCourses = $mobileHomepageModel->getPopularCourseList(5);

                        $popularCourseIdsArray = array();
                        foreach($popularCourses as $course)
                        {
                            $popularCourseIdsArray[] = $course['id'];
                        }
                        $popularCourseIds = implode(',', $popularCourseIdsArray);
                        $rightSideMainLinksArray        = $mobileHomepageModel->getRightSideMainLinks($popularCourseIdsArray);
                        $rightSideStudentToolLinksArray = $mobileHomepageModel->getStudentToolLinks($popularCourseIdsArray, $limits['toolLinks']['max']);
                        $popularExamsArray              = $mobileHomepageModel->getPopularExamList($popularCourseIdsArray, $limits['popularExams']['max']);

                        foreach($rightSideMainLinksArray as $key => $val)
                        {
                                $rightSideMainLinks[$val['popularCourseId']][] = $val;
                        }
                        foreach($rightSideStudentToolLinksArray as $key => $val)
                        {
                                $rightSideStudentToolLinks[$val['popularCourseId']][] = $val;
                        }
                        foreach($popularExamsArray as $key => $val)
                        {
                                $popularExams[$val['popularCourseId']][] = $val;
                        }

                        foreach($popularCourses as $key => $course)
                        {
                                $popularCoursesData = array();
                                $popularCoursesData['name'] = $course['name'];
                                $popularCoursesData['subcatId'] = $course['subcatId'];
                                $popularCoursesData['parentId'] = $course['parentId'];
                                $popularCoursesData['bgcolor'] = $course['bgcolor'];

                                foreach($rightSideMainLinks[$course['popularCourseId']] as $k => $links)
                                {
                                        $popularCoursesData['rightSideMainLinks'][] = array('name'=>$links['name'], 'URL'=>$links['URL'], 'type'=>$links['type']);
                                }
                                foreach($rightSideStudentToolLinks[$course['popularCourseId']] as $k => $links)
                                {
                                        $popularCoursesData['rightSideStudentToolLinks'][] = array('name'=>$links['name'], 'URL'=>$links['URL'], 'type'=>$links['type']);
                                }
                                foreach($popularExams[$course['popularCourseId']] as $k => $links)
                                {
                                        $popularCoursesData['exams'][] = array('name'=>$links['name'], 'params'=>$links['params'], 'examName'=>$links['examName']);
                                }
                                $popularCoursesArray[] = $popularCoursesData;
                        }
                        if(!$preview)
                        {
                                $this->cacheLib->store($ckey, $popularCoursesArray, 30*24*3600);
                        }
                }else{
                        $popularCoursesArray = ($this->cacheLib->get($ckey) !='' && is_array($this->cacheLib->get($ckey))) ? $this->cacheLib->get($ckey) : array();
                }
                /* load config from db - end */
                $data['popularCoursesArray'] = $popularCoursesArray;

                $popularComparisonArray = $this->config->item('popularComparisonWidget');
                $data['popularComparisonArray'] = $popularComparisonArray;
                $this->load->config("examPages/examPageConfig");
                $this->load->builder('ExamBuilder','examPages');

                $this->load->config('RP/RankPredictorConfig',TRUE);
                $rankPredictorSetting = $this->config->item('settings','RankPredictorConfig');
                $data['rankPredictorArray'] = $rankPredictorSetting['RPEXAMS'];

                $this->load->config('CP/CollegePredictorConfig',TRUE);
                $settings = $this->config->item('settings','CollegePredictorConfig');
                $data['collegePredictorArray'] = $settings['CPEXAMS'];
                $data['loadSmallJQuery'] = true;
                $this->_loadView($type,$data);
        }

  /**
    * function : getTabsData
    * param: no
    * desc : prepare html for all tabs except selected tab, other tab based on category and subcategory
    * type : html
    * added by akhter
    **/ 
	function getTabsData(){
		$this->load->library('Common/cacheLib');
		$this->cacheLib = new cacheLib();
		$cacheHtmlKey = '_otherKey';
		$resetPage = $this->input->post('resetPage');

		if($this->cacheLib->get($cacheHtmlKey) == 'ERROR_READING_CACHE' || $resetPage){
			$hamLib = $this->load->library('mcommon5/HamburgerLib');
			$displayData['result'] = $hamLib->getTabsContentByStream();
			$this->setConfigData($displayData['result']);
			//_p($displayData['result']);die;
			$this->cacheLib->store($cacheHtmlKey,$displayData['result'], 1*24*3600);
		}else{
			$displayData['result'] = $this->cacheLib->get($cacheHtmlKey);
		}
		$htm = $this->load->view('homepageWidgets/otherTab',$displayData,true);
		$htmList['other'] = sanitize_output($htm);
		$finalHtmlFor[] = 'other';
		$htmList['htmlTab'] = $finalHtmlFor;
		$finalHtml = json_encode($htmList);
    	echo $finalHtml;
	}

	private function setConfigData(&$streamData){
		$this->load->config('mcommon5/mobi_config');
		$streamList = $this->config->item('streamList');
		foreach ($streamList as $key => $categoryList) {
			$list = array();
			$streamId = $categoryList['streamId'];
			if(in_array($streamId,array(MASS_COMMUNICATION_MEDIA,HUMANITIES_SOCIAL_SCIENCES,SCIENCE,ACCOUNTING_AND_COMMERCE))){
				$streamData[$streamId]['collegeCutoffPageLink'] = 1;
				$streamData[$streamId]['collegeCutoffData']['name'] = 'Delhi University Cut-Offs';
			}
			$list['streamName'] = $categoryList['streamName'];
			$list['class']   = $categoryList['class'];
			$list['liClass'] = $categoryList['liClass'];
			$streamData[$streamId]['config'] = $list;
		}
	}

	/**
    * function : getSubCatListOnCategory
    * param: categoryList
    * desc : create list of subcategory based on category
    * reutrn type : array list of subcate based on category
    * added by akhter
    **/
	function getSubCatListOnCategory($categoryList){
			$this->load->builder('CategoryBuilder','categoryList');
			$categoryBuilder = new CategoryBuilder;
			$categoryRepository = $categoryBuilder->getCategoryRepository();
			foreach ($categoryList as $key => $categoryList) {

					$categoryid = $categoryList['catId'];

					if(!is_numeric($categoryid) || $categoryid <=0){
				    	continue;
				    }

					$subCatObj = $categoryRepository->getSubCategories($categoryid);

					if(is_array($subCatObj)){

						$removeSubCatList = (count($categoryList['removeSubCat'])>0) ? $categoryList['removeSubCat'] : array();
						$list[$categoryid]['catName'] = $categoryList['catName'];
						$list[$categoryid]['class']   = $categoryList['class'];
						$list[$categoryid]['liClass']   = $categoryList['liClass'];

						foreach ($subCatObj as $obj) {
							if(!in_array($obj->getId(), $removeSubCatList)){
								$list[$categoryid]['subcatList'][$obj->getId()] = $obj->getName();
							}
						}
						natcasesort($list[$categoryid]['subcatList']);
					}
			}
			return $list;
	}

	function getFeaturedCollegeBanners(){
		$this->load->library('Common/cacheLib');
		$this->cacheLib = new cacheLib();
		$cacheHtmlKey = '_ftrClgBnr';
		$resetPage = $this->input->post('resetPage');
		if($this->cacheLib->get($cacheHtmlKey) == 'ERROR_READING_CACHE' || $resetPage){
			$hpmodel = $this->load->model('home/homepagemodel');
			$featuredCollegeData = $hpmodel->getFeaturedCollegeBanners(true,'HomePageMobile');
			$totalBanners = count($featuredCollegeData['paid']) + count($featuredCollegeData['free']);
			if($totalBanners > 1){
				$this->load->helper('home/homepage');
				$displayData['featuredCollegesData'] = generateTargetUrl($featuredCollegeData);
				$htm = $this->load->view('homepageWidgets/featuredCollegesWidgetInner',$displayData,true);
				$data['view'] = sanitize_output($htm);
			}else{
				$data['empty'] = 1;
			}	
			$this->cacheLib->store($cacheHtmlKey,$data, 1*24*3600);
		}else{
			$data = $this->cacheLib->get($cacheHtmlKey);
		}
		echo json_encode($data);
	}

	// desc   : This is used to read PBT data from dashboardconfig.php and insert in to OF_DashBoardConfig table
    // NOTICE : run only one time
  	// @uthor : akhter
	function addPBT(){
		$this->load->library('dashboardconfig');
		$pbtArr = DashboardConfig::$institutes_autorization_details_array;
		foreach ($pbtArr as $courseId => $value) {
				if(is_numeric($courseId) && $courseId > 0 && $value['external'] == 'yes'){
						$data[] = array( 
										'seoTitle'      =>$value['seo_title'],
										'seoDescription'=>$value['seo_description'],
										'seoUrl'        =>$value['seo_url'],
										'altImageHeader'=>$value['alt_image_header'],
										'instituteId'   =>$value['instituteId'],
										'courseId'      =>$courseId,
										'externalUrl'   =>'yes'
										);
			}
		}
		if(count($data)>0){
			$this->load->model('homepagemodel','myModelObj');
			$res = $this->myModelObj->insertPBT($data);
			if($res){
				echo 'Data has been successfully inserted.';
			}
		}
	}

	function jsLog(){
		error_log(print_r($_POST,true),3,'/tmp/jsLog');
	}

	// prepare DFP banner data for PWA homePage/CLP/CTP only
	function getDFPData(){
		$parentPage = $this->input->post('parentPage');
		$pageType   = $this->input->post('pageType'); // child page
		$entity_id  = $this->input->post('entity_id');
		$cityId     = $this->input->post('city');
		$stateId    = $this->input->post('state');
		$extraPrams = json_decode($this->input->post("extraPrams",true),true);
		
		$dpfParam = Array();
		if($parentPage == 'DFP_HomePage'){
			$dpfParam = array('parentPage'=>$parentPage,'pageType'=>$pageType);	
		}else if($parentPage == 'DFP_CourseDetailPage' && (!empty($entity_id) && preg_match('/^\d+$/',$entity_id))){
			$this->load->builder("nationalCourse/CourseBuilder");
	        $courseBuilder = new CourseBuilder();
	        $courseRepo = $courseBuilder->getCourseRepository();   
	        $courseObj  = $courseRepo->find($entity_id,'full');
			$dpfParam = array('courseObj'=>$courseObj,'city'=>$cityId,'state'=>$stateId,'parentPage'=>$parentPage);
		}else if($parentPage == 'DFP_CategoryPage' || $parentPage == 'DFP_ALL_COURSES_PAGE' || $parentPage == 'DFP_BIP' || $parentPage == 'DFP_SIP'){
			$dpfParam = array('parentPage'=>$parentPage,'entity_id'=>$entity_id,'baseCourse'=>$extraPrams['baseCourse'],'cityId'=>$extraPrams['city'],'educationType'=>$extraPrams['educationType'],'stateId'=>$extraPrams['state'],'stream_id'=>$extraPrams['streams'],'substream_id'=>$extraPrams['substreams'],'specialization_id'=>$extraPrams['specializations'],'deliveryMethod'=>$extraPrams['deliveryMethod'],'courseCredential'=>$extraPrams['credential']);
			if($parentPage == 'DFP_ALL_COURSES_PAGE' || $parentPage == 'DFP_BIP' || $parentPage == 'DFP_SIP'){
				$this->load->builder("nationalInstitute/InstituteBuilder");
				$instituteBuilder = new InstituteBuilder();
				$this->instituteRepo = $instituteBuilder->getInstituteRepository();
				if($entity_id){
					$instituteObj = $this->instituteRepo->find($entity_id,'full');	
				}
				$dpfParam['instituteObj'] = $instituteObj;
				$dpfParam['pageType'] = 'homepage';
			}
		}else if($parentPage == 'DFP_InstituteDetailPage' || $parentPage == 'DFP_UniversityListingPage'){
        	$this->load->builder("nationalInstitute/InstituteBuilder");
        	$instituteBuilder = new InstituteBuilder();
        	$this->instituteRepo = $instituteBuilder->getInstituteRepository();
        	if($entity_id){
        		$instituteObj = $this->instituteRepo->find($entity_id,'full');	
        	}
			$dpfParam = array('instituteObj'=> $instituteObj, 'parentPage'=>$parentPage,'entity_id'=>$entity_id,'cityId'=>$cityId,'stateId'=>$stateId,'pageType'=>$pageType,'stream_id'=>$extraPrams['streams'],'baseCourse'=>$extraPrams['baseCourse'],'substream_id'=>$extraPrams['substreams'],'specialization_id'=>$extraPrams['specializations']);
		}else if($parentPage == 'DFP_CourseHomePage'){
			$dpfParam = array('parentPage'=>$parentPage,'entity_id'=>$entity_id,'baseCourse'=>$extraPrams['baseCourse'],'educationType'=>$extraPrams['educationType'],'stream_id'=>$extraPrams['streams'],'substream_id'=>$extraPrams['substreams'],'specialization_id'=>$extraPrams['specializations'],'deliveryMethod'=>$extraPrams['deliveryMethod'],'courseCredential'=>$extraPrams['credential']);
		}else if ($parentPage == "DFP_RankingPage") {
			$dpfParam = array(
				'parentPage'=>'DFP_RankingPage',
				'entity_id'=>$entity_id,
				'stream_id'=>$extraPrams['streams'],
				'substream_id'=>$extraPrams['substreams'],
				'specialization_id'=>$extraPrams['specializations'],
				'baseCourse'=>$extraPrams['baseCourse'],
				'cityId'=>$extraPrams['city'],
				'stateId'=>$extraPrams['state'],
				'educationType'=>$extraPrams['educationType'],
				'deliveryMethod'=>$extraPrams['deliveryMethod'],
			);

		} else if($parentPage == "DFP_ExamPage") {
			$this->load->builder('ExamBuilder','examPages');
        	$examBuilder = new ExamBuilder();
        	$examRepository = $examBuilder->getExamRepository();
        	$groupObj = $examRepository->findGroup($extraPrams['groupId']);
        	
        	if(!empty($extraPrams['conductedBy']) && is_numeric($extraPrams['conductedBy'])){
        		$examPageLib = $this->load->library('examPages/ExamPageLib');
        		$conductedByArr = $examPageLib->getConductedBy($extraPrams['conductedBy']);
				$conductedBy = is_array($conductedByArr) ? $conductedByArr['conductedBy'] : '';	
        	}else{
        		$conductedBy = $extraPrams['conductedBy'];
        	}
        	
			$dpfParam = array(
				'parentPage'		=> $parentPage,
				'entity_id' 		=> $entity_id,
				'groupObj' 			=> $groupObj,
				'examId' 			=> $extraPrams['examId'],
				'groupId' 			=> $extraPrams['groupId'],
				'conductedBy' 		=> $conductedBy,
				'pageType' 			=> $extraPrams['pageType'],
				'stream_id' 		=> $extraPrams['stream_id'],
				'substream_id' 		=> $extraPrams['substream_id'],
				'specialization_id' => $extraPrams['specialization_id'],
				'baseCourse' 		=> $extraPrams['baseCourse'],
				'addMoreDfpSlot'    => $extraPrams['addMoreDfpSlot']
			);			
		} else {
            $dpfParam = array(
                'parentPage' => $parentPage
            );
        }
	
		$dfpObj   = $this->load->library('common/DFPLib');
		$userInfo = $this->checkUserValidation();
        $result   = $dfpObj->getDFPData($userInfo, $dpfParam);

		$requestHeader = ($_SERVER['HTTP_ORIGIN'] != null) ? $_SERVER['HTTP_ORIGIN'] : SHIKSHA_HOME;
        header("Access-Control-Allow-Origin: ".$requestHeader);
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
        header('P3P: CP="CAO PSA OUR"'); // Makes IE to support cookies
        header("Content-Type: application/json; charset=utf-8");

        echo json_encode(array('data' => $result));exit();
	}
}
