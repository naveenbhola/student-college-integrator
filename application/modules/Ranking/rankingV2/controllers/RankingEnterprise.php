<?php

class RankingEnterprise extends MX_Controller {
	
	private $rankingCommonLib;
	private $rankingEnterpriseLib;
	private $validStatus;
    private $rankingModel;
	private $tabId = 205;
	
	public function __construct(){
		$this->load->library(array('Category_list_client', 'register_client'));
		$this->config->load("ranking_config");
		$this->load->builder('RankingPageBuilder', RANKING_PAGE_MODULE);
		$this->rankingEnterpriseLib = RankingPageBuilder::getRankingPageEnterpriseLib();
		$this->rankingCommonLib 	= RankingPageBuilder::getRankingPageCommonLib();
		$this->rankingURLManager  	= RankingPageBuilder::getURLManager();
		$this->load->helper("shikshautility");
		$this->validStatus = array("all", "live", "disable", "draft");
        $this->rankingModel	= RankingPageBuilder::getRankingModel();
	}
	
	public function index(){
		$cmsUserInfo = modules::run('enterprise/Enterprise/cmsUserValidation');
		if($cmsUserInfo['usergroup'] != 'cms'){
			header("location:/enterprise/Enterprise/disallowedAccess");
			exit();
		}
		$userid 	= $cmsUserInfo['userid'];
		$usergroup 	= $cmsUserInfo['usergroup'];
		$validity 	= $cmsUserInfo['validity'];
		$cmsPageArr = array();
		$cmsPageArr['userid'] 		= 	$userid;
		$cmsPageArr['validateuser'] = 	$validity;
		$cmsPageArr['headerTabs'] 	=  	$cmsUserInfo['headerTabs'];
		$cmsPageArr['prodId'] 		=   $this->tabId; //Tab ID
		$cmsPageArr['action']       =   'list';
		
		$messageParams = $this->getURLOperationMessages();
		$cmsPageArr['message_params'] = $messageParams;
		
		$params = array();
		$params['status'] = array("live", "draft", "disable");
		$selectedStatus = 'all';
		$stat = $this->input->get('status',true); 
		if(isset($stat) && in_array($stat, $this->validStatus)){
			$selectedStatus = $_REQUEST['status'];
			if($stat != "all"){
				$params['status'] = array($stat);
			}
		}
		$params['limit']  	= 10;

		$ofst = $this->input->get('offset',true);	
		if(isset($ofst) && is_numeric($ofst)){
			$params['offset']  	= $ofst;
		} else {
			$params['offset']  	= 0;
		}

		$rankingPageResults 			= $this->rankingEnterpriseLib->getRankingPagesWithPageData($params);
		$rankingPages 					= $rankingPageResults['results'];
		$totalRankingPages 				= $rankingPageResults['totalrows'];
		$rankingPageDataCountDetails 	= $rankingPageResults['page_data_count'];
		
		if($totalRankingPages != -1){
			$paginationHTML = doPagination($totalRankingPages,"/".RANKING_PAGE_MODULE."/RankingEnterprise/index/?offset=@start@&status=$selectedStatus", $params['offset'], $params['limit'], 5);
		}
		$cmsPageArr['ranking_pages'] 		  = $rankingPages;
		$cmsPageArr['pagination']			  = $paginationHTML;
		$cmsPageArr['selected_status']        = $selectedStatus;
		$cmsPageArr['page_data_count']        = $rankingPageDataCountDetails;
		$this->load->view(RANKING_PAGE_MODULE.'/ranking_enterprise/list_ranking_page', $cmsPageArr);
	}
	
	public function create($categoryId = NULL, $subCategoryId = NULL, $specializationId = NULL) {
		$cmsUserInfo = modules::run('enterprise/Enterprise/cmsUserValidation');
		if($cmsUserInfo['usergroup']!='cms'){
			header("location:/enterprise/Enterprise/disallowedAccess");
			exit();
		}
		$userid 	= $cmsUserInfo['userid'];
		$usergroup 	= $cmsUserInfo['usergroup'];
		$validity 	= $cmsUserInfo['validity'];
		$cmsPageArr = array();
		$cmsPageArr['userid'] 		= 	$userid;
		$cmsPageArr['validateuser'] = 	$validity;
		$cmsPageArr['headerTabs'] 	=  	$cmsUserInfo['headerTabs'];
		$cmsPageArr['prodId'] 		=   $this->tabId; //Tab ID
		$cmsPageArr['action']       =   'create';
		$sources						= $this->rankingCommonLib->getAllSources();
		
		$hierarchy = modules::run('listingBase/HierarchyAdmin/getHierarchyTree',1,'array');
		$popularCourses = modules::run('listingBase/BaseCourseAdmin/getPopularCourses','array');
		$popularCourses = array_map(function($ele){return array('id'=>$ele['base_course_id'],'name'=>$ele['name']);}, $popularCourses['data']);
		// $popularCourseIds = array_map(function($ele){return $ele['id'];},$popularCourses);
		$courseAttributes = modules::run('listingBase/BaseCourseAdmin/getCourseAttributes','array');
		$baseCourses = modules::run('listingBase/BaseCourseAdmin/getAllBasecourses','array');
		$baseCourses = array_map(function($ele){return array('id'=>$ele['base_course_id'],'name'=>$ele['name']);},$baseCourses['data']);
		$maxCoursesInRankingPageAllowed = $this->config->item('MAXIMUM_COURSES_IN_RANKING_PAGE');
		
		$cmsPageArr['hierarchy'] = $hierarchy;
		$cmsPageArr['baseCourses'] = $baseCourses;
		$cmsPageArr['popularCourses'] = $popularCourses;
		$cmsPageArr['courseAttributes'] = $courseAttributes;
		$cmsPageArr['maxCoursesInRankingPageAllowed'] = $maxCoursesInRankingPageAllowed;
		$cmsPageArr['sources']				  = $sources;
		
		$this->load->view(RANKING_PAGE_MODULE.'/ranking_enterprise/create_ranking_page', $cmsPageArr);
	}
	
	public function action($rankingPageId = NULL, $action = NULL){
		$cmsUserInfo = modules::run('enterprise/Enterprise/cmsUserValidation');
		if($cmsUserInfo['usergroup']!='cms'){
			header("location:/enterprise/Enterprise/disallowedAccess");
			exit();
		}
		$userid 	= $cmsUserInfo['userid'];
		$usergroup 	= $cmsUserInfo['usergroup'];
		$validity 	= $cmsUserInfo['validity'];
		$cmsPageArr = array();
		$cmsPageArr['userid'] 		= 	$userid;
		$cmsPageArr['validateuser'] = 	$validity;
		$cmsPageArr['headerTabs'] 	=  	$cmsUserInfo['headerTabs'];
		$cmsPageArr['prodId'] 		=   $this->tabId; //Tab ID
		$cmsPageArr['action']       =   $action;
		$cmsPageArr['rank_page_id'] =   $rankingPageId;
		
		$validActions = array("delete", "disable", "live");
		$params = array();
		$params['status'] = array("live", "draft", "disable", "delete");
		$params['id'] = $rankingPageId;
		$rankingPages = $this->rankingEnterpriseLib->getRankingPagesWithPageData($params);
		$cmsPageArr['ranking_page'] 	= array();
		$cmsPageArr['valid_actions'] 	= $validActions;
		if(count($rankingPages) > 0){
			$cmsPageArr['ranking_page'] = $rankingPages[0];
		}
		$this->load->view(RANKING_PAGE_MODULE.'/ranking_enterprise/ranking_page_actions', $cmsPageArr);
	}
	
	public function edit($rankingPageId = NULL, $defaultSourceId = NULL){
		$cmsUserInfo = modules::run('enterprise/Enterprise/cmsUserValidation');
		if($cmsUserInfo['usergroup']!='cms'){
			header("location:/enterprise/Enterprise/disallowedAccess");
			exit();
		}
		$userid 	= $cmsUserInfo['userid'];
		$usergroup 	= $cmsUserInfo['usergroup'];
		$validity 	= $cmsUserInfo['validity'];
		$cmsPageArr = array();
		$cmsPageArr['userid'] 		= 	$userid;
		$cmsPageArr['validateuser'] = 	$validity;
		$cmsPageArr['headerTabs'] 	=  	$cmsUserInfo['headerTabs'];
		$cmsPageArr['prodId'] 		=   $this->tabId; //Tab ID
		$cmsPageArr['action']       =   'edit';
		
		$messageParams = $this->getURLOperationMessages();
		$cmsPageArr['message_params'] = $messageParams;
		
		$params = array();
		$params['status'] = array("live", "draft", "disable");
		$params['id'] = $rankingPageId;
		$rankingPages = $this->rankingEnterpriseLib->getRankingPagesWithPageData($params);
		$cmsPageArr['ranking_page'] = array();
		if(count($rankingPages) > 0){
			$cmsPageArr['ranking_page'] = $rankingPages[0];
		}
		
		$rankingPageData = array();
		if(!empty($cmsPageArr['ranking_page'])){
			$rankingPageSourceData = $this->rankingEnterpriseLib->getRankingPageSourceData($rankingPageId, $cmsPageArr['ranking_page']['status']);
			foreach($rankingPageSourceData as $source) {
				$sourceIds[] = $source['source_id'];
			}
			
			$cmsPageArr['ranking_page_source_data'] = $rankingPageSourceData;

			$cmsPageArr['publisherList'] = $this->rankingEnterpriseLib->getRankingPagePublishersWithData($rankingPageId, $cmsPageArr['ranking_page']['status']);
			
			if($defaultSourceId == NULL) {
				$cmsPageArr['ranking_page_default_source_id'] = $rankingPageSourceData[0]['source_id'];
			} else {
				$cmsPageArr['ranking_page_default_source_id'] = $defaultSourceId;
			}
			$rankingPageData = modules::run(RANKING_PAGE_MODULE.'/RankingEnterprisev2/getRankingPageData',$rankingPageId);
		}

		$invalidCourseIds = array();
		$updatedRankingPageData = array();
		$hasAtleastOneCourse = 0;
		foreach($rankingPageData as $pageData){
			$courseDetails = $pageData['course_details'];
			if(empty($courseDetails)){
				$invalidCourseIds[] = $pageData['course_id'];
			} else {
				$updatedRankingPageData[] = $pageData;
				$hasAtleastOneCourse = 1;
			}
		}

		if($hasAtleastOneCourse) {
			foreach($invalidCourseIds as $courseId){
				$this->rankingModel->deleteCourseFromRankingPageData($rankingPageId, $courseId);
			}
		}

		$rankingPageData = $updatedRankingPageData;
		$cmsPageArr['ranking_page_data'] = $rankingPageData;
		$maxCoursesInRankingPageAllowed = $this->config->item('MAXIMUM_COURSES_IN_RANKING_PAGE');
		$cmsPageArr['prefillData'] = $this->_prepareEditPrefillData($cmsPageArr['ranking_page']);
		$cmsPageArr['maxCoursesInRankingPageAllowed'] = $maxCoursesInRankingPageAllowed;
		$rankingMetaData = $this->getRankingPageSeoData($rankingPageId,0,0,'array');
		$cmsPageArr['metaDetails'] = $rankingMetaData;
		$this->load->view(RANKING_PAGE_MODULE.'/ranking_enterprise/edit_ranking_page', $cmsPageArr);
	}
	
	public function createRankingPage(){
        //xss changes
        $category_id = $this->input->get('category_id',true);
        $subcategory_id = $this->input->get('subcategory_id',true);
        $specialization_id = $this->input->get('specialization_id',true);
        $ranking_page_text = $this->input->get('ranking_page_text',true);

		$paramArray['category_id']       = (isset($category_id) && $category_id != '' && is_numeric($category_id)) ? $category_id : 0;
		$paramArray['subcategory_id']    = (isset($subcategory_id) && $subcategory_id != '' && is_numeric($subcategory_id)) ? $subcategory_id : 0;
		$paramArray['specialization_id'] = (isset($specialization_id) && $specialization_id != '' && is_numeric($specialization_id)) ? $specialization_id : 0;
		$paramArray['ranking_page_text'] = (isset($ranking_page_text) && $ranking_page_text != '') ? $ranking_page_text : "";
		
		$returnResult = $this->rankingEnterpriseLib->createRankingPage($paramArray);
		echo json_encode($returnResult);
	}
	
	public function changeStatus($rankingPageId = NULL, $status = NULL){
		$result = false;
		if(!empty($rankingPageId) && !empty($status)){
			$result = $this->rankingEnterpriseLib->changeRankingPageStatus($rankingPageId, $status);
		}
		$return = array();
		$return['success'] = "false";
		if($result['success']){
			$this->rankingCommonLib->invalidateRankingPagesCache(array($rankingPageId));
			$this->rankingCommonLib->invalidateRankingObjectCache(array($rankingPageId));
			$return['success'] = "true";
		}
		$return['error_msg'] = $result['error_msg'];
		echo json_encode($return);
	}
	
	public function deleteCourseFromRankingPageData($rankingPageId = NULL, $courseId = NULL, $ajaxCall = true, $checkForAtLeastOneCourseAvailable = true){
		$result = false;
		if(!empty($rankingPageId) && !empty($courseId)){
			$result = $this->rankingEnterpriseLib->deleteCourseFromRankingPageData($rankingPageId, $courseId, $checkForAtLeastOneCourseAvailable);
		}
		$return = array();
		$return['success'] 		= "false";
		$return['error_msg'] 	= $result['error_msg'];
		if($result['success']){
			$this->rankingCommonLib->invalidateRankingObjectCache(array($rankingPageId));
			$this->rankingCommonLib->indexCourseForRanking(array($courseId));
			$return['success'] = "true";
		}
		if($ajaxCall){
			echo json_encode($return);
		} else {
			return $return;
		}
	}
	
	public function removeCourseFromSourceData($rankingPageId = NULL, $courseId = NULL, $sourceId = NULL){
		$result = false;
		if(!empty($rankingPageId) && !empty($courseId) && !empty($sourceId)){
			$result = $this->rankingEnterpriseLib->removeCourseFromSourceData($rankingPageId, $courseId, $sourceId);
		}
		$return = array();
		$return['success'] 		= "false";
		$return['error_msg'] 	= $result['error_msg'];
		if($result['success']){
			$this->rankingCommonLib->invalidateRankingObjectCache(array($rankingPageId));
			$this->rankingCommonLib->indexCourseForRanking(array($courseId));
			$return['success'] = "true";
		}
		echo json_encode($return);
	}
	
	public function fetchInstituteCourses($instituteId = NULL){
		$resultSet = array();
		if(!empty($instituteId)){
			$resultSet = $this->rankingCommonLib->getInstituteCourses($instituteId);
		}
		echo json_encode($resultSet);
	}
	
	public function saveRankingCourseDetails($rankPageId, $instituteId, $courseId, $rankScoreDetails, $courseAltText, $sourceId){
		$returnData = array("success" => "false", "error_type" => array("INVALID_INPUT_PARAMS"));
		if(!empty($rankPageId) && !empty($instituteId) && !empty($courseId) && !empty($courseAltText)){
			$courseAltText = rawurldecode($courseAltText);
			$courseAltText = str_replace("!%%!", "/", $courseAltText);
			$courseAltText = strip_tags($courseAltText);
			$returnData = $this->rankingEnterpriseLib->saveRankingCourseDetails($rankPageId, $instituteId, $courseId, $rankScoreDetails, $courseAltText, $sourceId);
			$this->rankingCommonLib->invalidateRankingObjectCache(array($rankPageId));
			$pageLiveFlag = $this->rankingCommonLib->checkIfRankingPageExists($rankPageId);
			if($pageLiveFlag){
				$this->rankingCommonLib->indexCourseForRanking(array($courseId));
			}
		}
		echo json_encode($returnData);
	}
	
	/*public function updateRankingPageDetails(){
		$paramString 	= $this->input->post('postparams',true);
		$categoryParams = $this->input->post('categoryparams',true);
		$rankingPageDisclaimer = $this->input->post('rankingpagedisclaimer',true);
		$sourceSelectedValues = $this->input->post('sourceselectedvalues',true);
		
		if(!empty($paramString)){
			$returnData = $this->rankingEnterpriseLib->updateRankingPageDetails($paramString, $categoryParams, $rankingPageDisclaimer, $sourceSelectedValues);
		}
		echo json_encode($returnData);
	}*/
	
	public function getURLOperationMessages(){
		$params = array();
		$op = $this->input->get('op',true);
		if(!empty($op)){
			switch($op){
				case 'status':
					$params['op']	  = 'status';
					$params['status'] = $this->input->get('status',true);
					$params['id'] 	  = $this->input->get('id',true);
					break;
				case 'create':
					$params['op']	  = 'create';
					$params['id'] 	  = $this->input->get('id',true);
					break;
				case 'institute_add':
					$params['op']	  = 'institute_add';
					break;
				case 'institute_delete':
					$params['op']	  = 'institute_delete';
					break;
				case 'ranking_page_updated':
					$params['op']	  = 'ranking_page_updated';
					break;
			}
		}
		return $params;
	}
	
	public function manage_meta_details($rankingPageId = NULL){
		$cmsUserInfo = modules::run('enterprise/Enterprise/cmsUserValidation');
		if($cmsUserInfo['usergroup']!='cms'){
			header("location:/enterprise/Enterprise/disallowedAccess");
			exit();
		}
		$userid 	= $cmsUserInfo['userid'];
		$usergroup 	= $cmsUserInfo['usergroup'];
		$validity 	= $cmsUserInfo['validity'];
		$cmsPageArr = array();
		$cmsPageArr['userid'] 		= 	$userid;
		$cmsPageArr['validateuser'] = 	$validity;
		$cmsPageArr['headerTabs'] 	=  	$cmsUserInfo['headerTabs'];
		$cmsPageArr['prodId'] 		=   $this->tabId; //Tab ID
		$cmsPageArr['action']       =   $action;
		$cmsPageArr['rank_page_id'] =   $rankingPageId;
		
		$messageParams = $this->getURLOperationMessages();
		$cmsPageArr['message_params'] = $messageParams;
		
		$params = array();
		$params['status'] = array("live", "draft", "disable", "delete");
		$params['id'] 	  = $rankingPageId;
		$rankingPages = $this->rankingEnterpriseLib->getRankingPagesWithPageData($params);
		$cmsPageArr['locationFilterData'] = modules::run('rankingV2/RankingMain/getRankingPageCatData',$rankingPageId);
		$cmsPageArr['locationFilterData'] = json_decode($cmsPageArr['locationFilterData'],true);
		$cmsPageArr['ranking_page'] = array();
		if(count($rankingPages) > 0){
			$cmsPageArr['ranking_page'] = $rankingPages[0];
		}
		$multiplePassesAllowed = false;
		$REQ_multiple_passes_allowed = $this->input->get('multiple_passes_allowed',true);
		if(!empty($REQ_multiple_passes_allowed) && $REQ_multiple_passes_allowed == "true"){
			$multiplePassesAllowed = true;
		}
		$cmsPageArr['multiplePassesAllowed'] = $multiplePassesAllowed;
		$this->load->view(RANKING_PAGE_MODULE.'/ranking_enterprise/manage_meta_details', $cmsPageArr);
	}
	
	public function change_meta_details() {
		$data = array();
		$data['rankingPageId'] 			= trim($this->input->post('page_id',true));
		$data['rankingPageTitle'] 		= trim($this->input->post('page_title',true));
		$data['rankingPageDescription'] 	= trim($this->input->post('page_description',true));
		$data['rankingPageLocation'] = trim($this->input->post('location_value',true));
		$data['rankingPageHeading'] 	= trim($this->input->post('page_heading',true));
		$data['rankingPageTitleExam'] 	= trim($this->input->post('page_title_exam',true));
		$data['disclaimer'] 	= trim($this->input->post('disclaimer',true));
		$data['rankingPageDescriptionExam'] 	= trim($this->input->post('page_description_exam',true));
		
		$return = array();
		if(empty($data['rankingPageId']) || empty($data['rankingPageLocation'])){
			$return['success'] 	 = "false";
			$return['error_msg'] = "Invalid input parameters.";
		}
		$status = $this->rankingEnterpriseLib->changeRankingPageMetaDetails($data);
		if($status){
			$return['success'] 	 = "true";
		} else {
			$return['success'] 	 	= "false";
			$return['error_msg'] 	= "DB operation failed. Please note meta details updataion is only one time operation.";
		}
		echo json_encode($return);
	}
	
	public function editSource($rankingPageId = NULL) {
		$cmsUserInfo = modules::run('enterprise/Enterprise/cmsUserValidation');
		if($cmsUserInfo['usergroup']!='cms'){
			header("location:/enterprise/Enterprise/disallowedAccess");
			exit();
		}
		$userid 	= $cmsUserInfo['userid'];
		$usergroup 	= $cmsUserInfo['usergroup'];
		$validity 	= $cmsUserInfo['validity'];
		$cmsPageArr = array();
		$cmsPageArr['userid'] 		= 	$userid;
		$cmsPageArr['validateuser'] = 	$validity;
		$cmsPageArr['headerTabs'] 	=  	$cmsUserInfo['headerTabs'];
		$cmsPageArr['prodId'] 		=   $this->tabId; //Tab ID
		$cmsPageArr['action']       =   'edit';
		
		$messageParams = $this->getURLOperationMessages();
		$cmsPageArr['message_params'] = $messageParams;
		
		$params = array();
		$params['status'] = array("live", "draft", "disable");
		$params['id'] = $rankingPageId;
		$rankingPages = $this->rankingEnterpriseLib->getRankingPagesWithPageData($params);
		$cmsPageArr['ranking_page'] = array();
		if(count($rankingPages) > 0){
			$cmsPageArr['ranking_page'] = $rankingPages[0];
		}
		
		//get all sources
		$sources = $this->rankingCommonLib->getAllSources();
		$cmsPageArr['sources'] = $sources;
		
		// get sources mapped to this ranking page
		if(!empty($cmsPageArr['ranking_page'])){
			$rankingPageSourceData = $this->rankingEnterpriseLib->getRankingPageSourceData($rankingPageId, $cmsPageArr['ranking_page']['status']);
		}
		foreach($rankingPageSourceData as $source) {
			$rankingPageSources[] = $source['source_id'];
		}
		$cmsPageArr['ranking_page_sources'] = $rankingPageSources;
		
		$this->load->view(RANKING_PAGE_MODULE.'/ranking_enterprise/ranking_page_edit_source', $cmsPageArr);
	}
    
	/*
	@name       : Ankit
	@description: to view table of all live sources
	*/
	function sourcesIndex() {
        $cmsUserInfo = modules::run('enterprise/Enterprise/cmsUserValidation');
        if($cmsUserInfo['usergroup']!='cms') {
				header("location:/enterprise/Enterprise/disallowedAccess");
				exit();
		}
		$sourceData       =       $this->rankingModel->getAllSources(array('live','disable'));

		$formatedSouceData = array();
		foreach($sourceData as $data)
    	{
    		$formatedSouceData[$data['publisher_id']]['publisher_id'] = $data['publisher_id'];
    		$formatedSouceData[$data['publisher_id']]['name'] = $data['name'];
    		$formatedSouceData[$data['publisher_id']]['publisher_data'][]=
    		array('year'=>$data['year'],'source_id'=> $data['source_id'],'status'=>$data['status']);
    	}
    	$cmsPageArr['sourceData']	=	$formatedSouceData;


		$cmsPageArr['userid'] 		= 	$userid;
		$cmsPageArr['validateuser']     = 	$cmsUserInfo['validity'];
		$cmsPageArr['headerTabs'] 	=  	$cmsUserInfo['headerTabs'];
		$cmsPageArr['prodId'] 		=       $this->tabId;    	

		$this->load->view(RANKING_PAGE_MODULE.'/ranking_enterprise/list_all_sources', $cmsPageArr);
	}
	
	/*
	@name       : Ankit
	@description: to view add/ edit sources details page
	@param      : $action      = specifies the type of action required 
				  $sourceId    = specifies source id
	*/
	function manageSources($action = 'add', $publisherId = null) {
        $cmsUserInfo = modules::run('enterprise/Enterprise/cmsUserValidation');
		if($cmsUserInfo['usergroup']!='cms'){
			header("location:/enterprise/Enterprise/disallowedAccess");
			exit();
		}
		$sourceNames = $this->rankingModel->getAllSources(array('live','disable'));

		$cmsPageArr = array();
		switch($action) {
			case 'edit': 
				$cmsPageArr['sourceData'] = $this->rankingModel->getPublisherData($publisherId, array('live','disable'));
				break;
		}

		$allSourceNames = array();
		foreach ($sourceNames as $row) {
			if(empty($publisherId) || (!empty($publisherId) && $row['publisher_id'] != $publisherId && !in_array($row['name'], $allSourceNames))) {
				$allSourceNames[] = $row['name'];
			}
		}

		$cmsPageArr['publisherId']			= 	$publisherId;
		$cmsPageArr['action']				=	$action;
		$cmsPageArr['startYear']			= 	2010;
		$cmsPageArr['endYear']				= 	date('Y');
		$cmsPageArr['userid']               = 	$userid;
		$cmsPageArr['validateuser']         = 	$cmsUserInfo['validity'];
		$cmsPageArr['headerTabs']           =  	$cmsUserInfo['headerTabs'];
		$cmsPageArr['prodId']               =   $this->tabId;
		$cmsPageArr['formName']             = 	'rankingSource';
		$cmsPageArr['sourceNames']          = 	$allSourceNames;
		$this->load->view(RANKING_PAGE_MODULE.'/ranking_enterprise/addSource', $cmsPageArr);
	}
    
	/*
	@name       : Ankit
	@description: saving/ editting/ updating source form data
	@param      : $action      = specifies the type of action required 
                      $sourceId    = specifies source id
                      $status      = specifies the new status to be updated 

	*/
	function saveSourceData($action, $publisherId, $status) {
		$cmsUserInfo = modules::run('enterprise/Enterprise/cmsUserValidation');
		if($cmsUserInfo['usergroup']!='cms'){
				header("location:/enterprise/Enterprise/disallowedAccess");
				exit();
		}

		$data['publisherId'] = $publisherId;
		$data['sourceName'] = $this->input->post('sourceName', true);
		$data['year'] = $this->input->post('year', true);
		$data['action'] = $action;
		$data['status'] = $status;
		
		$response = '0';
		switch($action) {
			case 'save':
				if(empty($data['year']) || empty($data['sourceName'])) {
					$response = '0';
					break;
				}
				$response = $this->rankingModel->insertSourceData($data);
				break;

			case 'edit':
				if(empty($data['sourceName'])) {
					$response = '0';
					break;
				}
				$response = $this->rankingModel->insertSourceData($data);
				$this->rankingcommonlib->updateCoursesForSource($publisherId, 'publisher');
				break;

			case 'update':
				$sourceId = $publisherId;
				$response = $this->rankingModel->updateSourceDataStatus($status, $sourceId);
				$this->rankingCommonLib->invalidateRankingObjectCache();
				if($status == 'live' || $status == 'disable'){
					$this->rankingcommonlib->updateCoursesForSource($sourceId, 'source');
				}
				break;
		}
		echo $response;
		exit;
	}
	
	public function updateSourceRankingPage(){
		$rankingPageId 			= $this->input->post('rankingPageId',true);
		$rankingPageStatus 		= $this->input->post('rankingPageStatus',true);		
		$sourceSelectedValues 	= $this->input->post('sourceSelectedvalues',true);
		
		if(!empty($sourceSelectedValues)) {
			$returnData = $this->rankingEnterpriseLib->updateSourceRankingPage($rankingPageId, $rankingPageStatus, $sourceSelectedValues);
		}
		echo json_encode($returnData);
	}

	/*
     * One time script to add ordering for ranking pages
     */
    function updateRankingPageOrderInDb() {
    	$this->rankingModel->updateRankingPageOrderInDb();
    	_p('DONE');
    }

    public function addRankingPage(){
    	$inputData = $this->_getRankingPagePostData();
    	$metaDetaits = $this->getRankingPageMetaData();
    	$res = $this->rankingEnterpriseLib->addRankingPage($inputData['mainTableData'],$inputData['sources'],$metaDetaits);
    	
    	echo json_encode($res);
    }

    /*
    	@Author : Rahul Bhatnagar
		This function gives a [id]=>[name] array of specializations of a popular course.
		The input can be either a single popular course id or an array of multiple ids. 
		Array checks are made internally within the repository.
		@params : $popularCourseIds : Single id (integer) or multiple ids (array).
    */
    public function getPopularCourseSpecializationDetails($popularCourseIds,$outputFormat = 'json'){
    	$this->load->builder('ListingBaseBuilder', 'listingBase');
    	$listingBaseBuilder = new ListingBaseBuilder();
    	$baseCourseRepository = $listingBaseBuilder->getBaseCourseRepository();
    	$data = $baseCourseRepository->getBaseEntityTreeByBaseCourseIds($popularCourseIds,'',1);
    	$result = array();
    	foreach($data as $streamId =>$streamData){
    		foreach($streamData['specializations'] as $streamSpecialization){
    			$result[$streamSpecialization['id']] = $streamSpecialization['name'];
    		}
    		foreach($streamData['substreams'] as $substream){
    			foreach($substream['specializations'] as $substreamSpecialization){
    				$result[$substreamSpecialization['id']] = $substreamSpecialization['name'];
    			}
    		}
    	}
    	if($outputFormat == 'array'){
    		return $result;
    	}
    	echo json_encode($result);
    }

    private function _prepareEditPrefillData($page){
    	$data = array();
    	$data['rankingPageText'] = $page['ranking_page_text'];
    	$data['disclaimer'] = $page['disclaimer'];
    	if($page['stream_id'] != 0){
    		$data['type'] = 'stream';
    		$hierarchy = modules::run('listingBase/HierarchyAdmin/getHierarchyTree',1,'array');
    		$data['stream'] = array('id'=>$page['stream_id'],'name'=>$hierarchy['data'][$page['stream_id']]['name']);
    		$data['substream'] = array('id'=>$page['substream_id'],'name'=>$hierarchy['data'][$page['stream_id']]['substreams'][$page['substream_id']]['name']);
    		$data['specialization'] = array('id'=>$page['specialization_id'],'name'=>$hierarchy['data'][$page['stream_id']]['substreams'][$page['substream_id']]['specializations'][$page['specialization_id']]['name']);
    		$baseCourses = modules::run('listingBase/BaseCourseAdmin/getAllBasecourses','array');
    		$data['baseCourse'] = array('id'=>$page['base_course_id'],'name'=>$baseCourses['data'][$page['base_course_id']]['name']);
    	}else if($page['base_course_id'] != 0){
    		$data['type'] = 'popularCourse';
    		$popularCourses = modules::run('listingBase/BaseCourseAdmin/getPopularCourses','array');
    		$data['popularCourse'] = array('id'=>$page['base_course_id'],'name'=>$popularCourses['data'][$page['base_course_id']]['name']);
    		$specializations = $this->getPopularCourseSpecializationDetails($page['base_course_id'],'array');
    		$data['specialization'] = array('id'=>$page['shiksha_specialization_id'],'name'=>$specializations[$page['shiksha_specialization_id']]);
    	}else{
    		return array();
    	}

    	$courseAttributes = modules::run('listingBase/BaseCourseAdmin/getCourseAttributes','array');
    	$data['deliveryMethod'] = array('id'=>$page['delivery_method'],'name'=>$courseAttributes['data']['Medium/Delivery Method'][$page['delivery_method']]);
    	$data['educationType'] = array('id'=>$page['education_type'],'name'=>$courseAttributes['data']['Education Type'][$page['education_type']]);
    	$data['credential'] = array('id'=>$page['credential'],'name'=>$courseAttributes['data']['Credential'][$page['credential']]);
    	return $data;
    }

    private function _getRankingPagePostData(){
    	$this->load->helper('security');
    	$streamId = (integer)$this->input->post('streamId');
    	$substreamId = (integer)$this->input->post('substreamId');
    	$specializationId = (integer)$this->input->post('specializationId');
    	$baseCourseId = (integer)$this->input->post('baseCourseId');
    	$sources = $this->input->post('sources');
    	$defaultPublisher = $this->input->post('defaultPublisher');
    	$tupleType = $this->input->post('tupleType');
    	$credentials = (integer)$this->input->post('credentials');
    	$educationType = (integer)$this->input->post('educationtype');
    	$deliveryMethod = (integer)$this->input->post('deliverymethod');
    	$rankingPageText = xss_clean($this->input->post('rankingPageText'));
    	$disclaimer = xss_clean($this->input->post('disclaimer'));
    	$status = xss_clean($this->input->post('status'));
    	$status = empty($status)?'draft':$status;
    	$created = $this->input->post('created');
    	$created = empty($created)?date('Y-m-d H:i:s'):$created;
    	$mainTableData = array(
    		'ranking_page_text' => $rankingPageText,
    		'default_publisher' => $defaultPublisher,
    		'tuple_type' => $tupleType,
    		'disclaimer' => $disclaimer,
    		'status' => $status,
    		'created' => $created,
    		'updated' => date('Y-m-d H:i:s'),
    		'stream_id' => $streamId,
    		'substream_id' => $substreamId,
    		'specialization_id' => $specializationId,
    		'education_type' => $educationType,
    		'delivery_method' => $deliveryMethod,
    		'credential' => $credentials,
    		'base_course_id' => $baseCourseId
		);

		return array('mainTableData' => $mainTableData,'sources'=>$sources);
    }


    private function getRankingPageMetaData(){
    	$h1 = $this->input->post('h1');
    	$breadcrumb =  $this->input->post('breadcrumb');
    	$description = $this->input->post('description');
    	$title =  $this->input->post('title');
    	$metaData = array(
    		'ranking_page_title' => $title,
    		'ranking_page_description' => $description,
    		'h1' => $h1,
    		'breadcrumb' => $breadcrumb
    	);
    	return $metaData;

    }

    public function editRankingPage($rankingPageId){
    	$data = $this->_getRankingPagePostData();
    	$metaDatails = $this->getRankingPageMetaData();
    	$mainTableData = $data['mainTableData'];
    	$this->rankingCommonLib->invalidateRankingObjectCache(array($rankingPageId));
		$res = $this->rankingEnterpriseLib->editRankingPage($rankingPageId,$mainTableData,$metaDatails);
		echo json_encode($res);
    }

    public function getRankingPageSeoData($rankingPageId =NULL,$cityId = 0 ,$stateId = 0, $format ='json'){
		if($rankingPageId==NULL) return;
		$this->load->library("rankingV2/RankingCommonLib");
		$data = $this->rankingcommonlib->getRankingPageMetaData($rankingPageId,$cityId,$stateId);
		if($format == 'json'){
			echo json_encode($data);
		}
		else{
			return $data;
		}
	}
	public function uploadExcelData($rankingPageId,$source){
			if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				
				if (isset($_FILES['files'])) {

					$file_tmp = $_FILES['files']['tmp_name'][0];
					$fileName = $file_tmp;
					$inputFileName = $fileName;
					
					$this->load->library('common/PHPExcel');
					$objPHPExcel 	= new PHPExcel();
					$objReader = PHPExcel_IOFactory::createReader('Excel2007');
					$objReader->setReadDataOnly(true);
					$objPHPExcel  = $objReader->load($inputFileName);
					$maxCoursesInRankingPageAllowed = $this->config->item('MAXIMUM_COURSES_IN_RANKING_PAGE');
					$objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
					$highestRow    = $objWorksheet->getHighestRow();
					
					if($highestRow -1 > $maxCoursesInRankingPageAllowed){
						http_response_code(404);
						return;
					}

					$userStatus = $this->checkUserValidation();	
					$userId = isset($userStatus[0]['userid'])?$userStatus[0]['userid']:0;

					$highestColumn = $objWorksheet->getHighestColumn();
					$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
					$data = array();
					$inputData=array();
					$inputData['ranking_page_id'] = $rankingPageId;
					$inputData['source'] = $source;
					$inputData['status'] = "draft";
					$inputData['created_by'] = $userId;

					for($row = 2; $row <= $highestRow; ++$row) {
						$isValidRow = true;
						for ($col = 0; $col < $highestColumnIndex; $col++) {
							$fieldName = $objWorksheet->getCellByColumnAndRow($col,1)->getValue();
							$value = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
							if(is_numeric($value) && $value > 0){
								$inputData[$fieldName] = trim($value);
							}else{
								$isValidRow = false;
							}
						}
						if($isValidRow){
							$data[]=$inputData;
						}
					}

					if(!empty($data)){
						$status = $this->rankingEnterpriseLib->uploadInstituteRankingData(array_values($data));
						if($status == false){
							http_response_code(500);
							return;	
						}
					} else {
						http_response_code(500);
							return;	
					}
					
				}	
			}
		}
}
