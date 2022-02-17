<?php

class RankingEnterprise extends ShikshaMobileWebSite_Controller {
	
	private $rankingCommonLib;
	private $rankingEnterpriseLib;
	private $validStatus;
	private $tabId = 57;
	
	public function __construct(){
		$this->load->library(array('Category_list_client', 'register_client'));
		$this->load->builder('RankingPageBuilder', 'ranking');
		$this->rankingEnterpriseLib = RankingPageBuilder::getRankingPageEnterpriseLib();
		$this->rankingCommonLib 	= RankingPageBuilder::getRankingPageCommonLib();
		$this->rankingURLManager  	= RankingPageBuilder::getURLManager();
		$this->load->helper("shikshautility");
		$this->config->load("ranking_config");
		$this->validStatus = array("all", "live", "disable", "draft");
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
		if(isset($_REQUEST['status']) && in_array($_REQUEST['status'], $this->validStatus)){
			$selectedStatus = $_REQUEST['status'];
			if($_REQUEST['status'] != "all"){
				$params['status'] = array($_REQUEST['status']);
			}
		}
		$params['limit']  	= 10;
		if(isset($_REQUEST['offset']) && is_numeric($_REQUEST['offset'])){
			$params['offset']  	= $_REQUEST['offset'];
		} else {
			$params['offset']  	= 0;
		}
		$rankingPageResults 			= $this->rankingEnterpriseLib->getRankingPagesWithPageData($params);
		$rankingPages 					= $rankingPageResults['results'];
		$totalRankingPages 				= $rankingPageResults['totalrows'];
		$rankingPageDataCountDetails 	= $rankingPageResults['page_data_count'];
		
		if($totalRankingPages != -1){
			$paginationHTML = doPagination($totalRankingPages,"/ranking/RankingEnterprise/index/?offset=@start@&status=$selectedStatus", $params['offset'], $params['limit'], 5);
		}
		
		$parentCategories 			 = $this->rankingCommonLib->getCategories();
		$specializationsByCategoryId = $this->rankingCommonLib->getSpecializationDetails($parentCategories);
		
		$cmsPageArr['category_details'] 	  = $parentCategories;
		$cmsPageArr['specialization_details'] = $specializationsByCategoryId;
		$cmsPageArr['ranking_pages'] 		  = $rankingPages;
		$cmsPageArr['pagination']			  = $paginationHTML;
		$cmsPageArr['selected_status']        = $selectedStatus;
		$cmsPageArr['page_data_count']        = $rankingPageDataCountDetails;
		$this->load->view('ranking/ranking_enterprise/list_ranking_page', $cmsPageArr);
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
		
		$urlParams = array();
		$urlParams['category_id'] 		= $categoryId;
		$urlParams['subcategory_id'] 	= $subCategoryId;
		$urlParams['specialization_id'] = $specializationId;
		$parentCategories 				= $this->rankingCommonLib->getCategories();
		
		$specializationsByCategoryId 	= $this->rankingCommonLib->getSpecializationDetails($parentCategories);
		$courseSuggestions = $this->rankingEnterpriseLib->loadInstituteSuggestions($categoryId, $subCategoryId, $specializationId);
		$invalidCourseIds = array();
		$updatedCourseSuggestions = array();
		foreach($courseSuggestions as $suggestion){
			$courseDetails = $suggestion['course_details'];
			if(empty($courseDetails)){
				$tempRankPageId = $suggestion['ranking_page_id'];
				$tempCourseId = $suggestion['course_id'];
				if(!array_key_exists($tempRankPageId, $invalidCourseIds)){
					$invalidCourseIds[$tempRankPageId] = array();
				}
				$invalidCourseIds[$tempRankPageId][] = $tempCourseId;
			} else {
				$updatedCourseSuggestions[] = $suggestion;
			}
		}
		$courseSuggestions = $updatedCourseSuggestions;
		foreach($invalidCourseIds as $rankingPageId => $courseIdList){
			foreach($courseIdList as $courseId){
				$this->deleteCourseFromRankingPageData($rankingPageId, $courseId, false, false);
			}
		}
		$maxCoursesInRankingPageAllowed = $this->config->item('MAXIMUM_COURSES_IN_RANKING_PAGE');
		$cmsPageArr['url_params']			  = $urlParams;
		$cmsPageArr['category_details'] 	  = $parentCategories;
		$cmsPageArr['specialization_details'] = $specializationsByCategoryId;
		$cmsPageArr['course_suggestions']  	  = $courseSuggestions;
		$cmsPageArr['maxCoursesInRankingPageAllowed']  	 = $maxCoursesInRankingPageAllowed;
		
		$this->load->view('ranking/ranking_enterprise/create_ranking_page', $cmsPageArr);
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
		$this->load->view('ranking/ranking_enterprise/ranking_page_actions', $cmsPageArr);
	}
	
	public function edit($rankingPageId = NULL){
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
			$rankingPageData = $this->rankingEnterpriseLib->getRankingPageData($rankingPageId);
		}
		$invalidCourseIds = array();
		$updatedRankingPageData = array();
		foreach($rankingPageData as $pageData){
			$courseDetails = $pageData['course_details'];
			if(empty($courseDetails)){
				$invalidCourseIds[] = $pageData['course_id'];
			} else {
				$updatedRankingPageData[] = $pageData;
			}
		}
		foreach($invalidCourseIds as $courseId){
			$this->deleteCourseFromRankingPageData($rankingPageId, $courseId, false, false);
		}
		
		$rankingPageData = $updatedRankingPageData;
		$cmsPageArr['ranking_page_data'] = $rankingPageData;
		
		$parentCategories 			 = $this->rankingCommonLib->getCategories();
		$specializationsByCategoryId = $this->rankingCommonLib->getSpecializationDetails($parentCategories);
		$maxCoursesInRankingPageAllowed = $this->config->item('MAXIMUM_COURSES_IN_RANKING_PAGE');
		
		$cmsPageArr['category_details'] 	  = $parentCategories;
		$cmsPageArr['specialization_details'] = $specializationsByCategoryId;
		$cmsPageArr['maxCoursesInRankingPageAllowed'] = $maxCoursesInRankingPageAllowed;
		$this->load->view('ranking/ranking_enterprise/edit_ranking_page', $cmsPageArr);
	}
	
	public function createRankingPage(){
		$paramArray['category_id']       = (isset($_REQUEST['category_id']) && $_REQUEST['category_id'] != '' && is_numeric($_REQUEST['category_id'])) ? $_REQUEST['category_id'] : 0;
		$paramArray['subcategory_id']    = (isset($_REQUEST['subcategory_id']) && $_REQUEST['subcategory_id'] != '' && is_numeric($_REQUEST['subcategory_id'])) ? $_REQUEST['subcategory_id'] : 0;
		$paramArray['specialization_id'] = (isset($_REQUEST['specialization_id']) && $_REQUEST['specialization_id'] != '' && is_numeric($_REQUEST['specialization_id'])) ? $_REQUEST['specialization_id'] : 0;
		$paramArray['ranking_page_text'] = (isset($_REQUEST['ranking_page_text']) && $_REQUEST['ranking_page_text'] != '') ? $_REQUEST['ranking_page_text'] : "";
		
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
			$return['success'] = "true";
		}
		if($ajaxCall){
			echo json_encode($return);
		} else {
			return $return;
		}
	}
	
	public function fetchInstituteCourses($instituteId = NULL){
		$resultSet = array();
		if(!empty($instituteId)){
			$resultSet = $this->rankingCommonLib->getInstituteCourses($instituteId);
		}
		echo json_encode($resultSet);
	}
	
	public function saveRankingCourseDetails($rankPageId, $instituteId, $courseId, $rank, $courseAltText){
		$returnData = array("success" => "false", "error_type" => array("INVALID_INPUT_PARAMS"));
		if(!empty($rankPageId) && !empty($instituteId) && !empty($courseId) && !empty($rank)  && !empty($courseAltText)){
			$courseAltText = rawurldecode($courseAltText);
			$courseAltText = str_replace("!%%!", "/", $courseAltText);
			$courseAltText = strip_tags($courseAltText);
			$returnData = $this->rankingEnterpriseLib->saveRankingCourseDetails($rankPageId, $instituteId, $courseId, $rank, $courseAltText);
		}
		echo json_encode($returnData);
	}
	
	/*public function updateRankingPageDetails(){
		$paramString 	= $_REQUEST['postparams'];
		$categoryParams = $_REQUEST['categoryparams'];
		if(!empty($paramString)){
			$returnData = $this->rankingEnterpriseLib->updateRankingPageDetails($paramString, $categoryParams);
		}
		echo json_encode($returnData);
	}*/
	
	public function getURLOperationMessages(){
		$params = array();
		if(!empty($_REQUEST['op'])){
			switch($_REQUEST['op']){
				case 'status':
					$params['op']	  = 'status';
					$params['status'] = $_REQUEST['status'];
					$params['id'] 	  = $_REQUEST['id'];
					break;
				case 'create':
					$params['op']	  = 'create';
					$params['id'] 	  = $_REQUEST['id'];
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
		$rankingPageMetaDetails = $this->rankingEnterpriseLib->getRankingPageMetaDetails($rankingPageId);
		$cmsPageArr['ranking_page'] = array();
		if(count($rankingPages) > 0){
			$cmsPageArr['ranking_page'] = $rankingPages[0];
		}
		$multiplePassesAllowed = false;
		if(!empty($_REQUEST['multiple_passes_allowed']) && $_REQUEST['multiple_passes_allowed'] == "true"){
			$multiplePassesAllowed = true;
		}
		$cmsPageArr['multiplePassesAllowed'] = $multiplePassesAllowed;
		$cmsPageArr['ranking_page_meta_details'] = $rankingPageMetaDetails;
		$this->load->view('ranking/ranking_enterprise/manage_meta_details', $cmsPageArr);
	}
	
	public function change_meta_details() {
		$ranking_page_id 			= trim($this->input->post('page_id'));
		$ranking_page_title 		= trim($this->input->post('page_title'));
		$ranking_page_description 	= trim($this->input->post('page_description'));
		$ranking_page_title_exam 		= trim($this->input->post('page_title_exam'));
		$ranking_page_description_exam 	= trim($this->input->post('page_description_exam'));
		$multiple_passes_allowed 	= trim($this->input->post('multiple_passes_allowed'));
		$updateMetaDetailsMultipleTimes = false;
		if($multiple_passes_allowed == "true"){
			$updateMetaDetailsMultipleTimes = true;
		}
		$return = array();
		if(empty($ranking_page_id) || empty($ranking_page_title) && empty($ranking_page_description)){
			$return['success'] 	 = "false";
			$return['error_msg'] = "Invalid input parameters.";
		}
		$status = $this->rankingEnterpriseLib->changeRankingPageMetaDetails($ranking_page_id, $ranking_page_title, $ranking_page_description, $ranking_page_title_exam, $ranking_page_description_exam, $updateMetaDetailsMultipleTimes);
		if($status){
			$return['success'] 	 = "true";
		} else {
			$return['success'] 	 	= "false";
			$return['error_msg'] 	= "DB operation failed. Please note meta details updataion is only one time operation.";
		}
		echo json_encode($return);
	}
}
