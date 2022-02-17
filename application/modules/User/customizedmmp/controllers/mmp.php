<?php

/*
 customizemmp  FrontEnd controller
*/

class mmp extends MX_Controller {
	
	private $_ci;
	public $customizedMMPTabId = "101";
	private $MarketingPageClient;
	private $MMP_SANDBOX_DIR;
	private $MMP_LIVE_DIR;
		
	public function init(){
		$this->_ci = & get_instance();
		ini_set('upload_max_filesize','100M');
		ini_set('max_execution_time', '1800');
		
		// MMP SANDBOX PATH
		$this->MMP_SANDBOX_DIR = FCPATH . 'public/mmp/sandbox';
		// MMP LIVE PATH
		$this->MMP_LIVE_DIR = FCPATH . 'public/mmp/';
		
		$this->load->library(array('Enterprise_client', 'customizemmp_lib', 'MultipleMarketingPageClient', 'LDB_Client','category_list_client'));
		$this->load->helper(array('form', 'url','date','html'));
		$this->MarketingPageClient = MultipleMarketingPageClient::getInstance();
		$this->userid = '';
		
	}
	
	public function index(){
		$this->showCustomizedMMP();
	}

	public function templateForm($page_id)
	{
		$cmp_model = $this->load->model('customizedmmp/customizemmp_model');
		if($page_id>0) {
			$mmp_details = $cmp_model->getMMPDetails($page_id);
			if(is_array($mmp_details) && $mmp_details['status'] !='live') {
				show_404();
			}
		}else{
			header("Location: ".SHIKSHA_HOME);
			exit();
		}
		
		$validity = $this->checkUserValidation();
		if(($validity == "false" )||($validity == "")) {
			$logged = "No";
		} else {
			$logged = "Yes";
			//$redirectURL = Modules::run('multipleMarketingPage/Marketing/getUserRedirectionURL',$page_id,1);
			//header("Location: ".$redirectURL);
			//exit();
		}
		
		if(isMobileRequest()) {
			$mmpIndexFilePath = 'public/mmp/'.$page_id.'/mobile/index.html';	
		}
		else {
			$mmpIndexFilePath = 'public/mmp/'.$page_id.'/index.html';
		}
		
        $fileContents = file_get_contents($mmpIndexFilePath);
		if(strpos($fileContents,'@MMPHEADER') === FALSE) {
			$this->templateFormOld($page_id);
		}
		else {
			$this->templateFormNew($page_id);
		}
	}
	
	
	public function templateFormOld($page_id){
		
        $this->init();
        $this->load->library("mmp_template_uploader");
        $mmp_template_uploader_lib = new mmp_template_uploader();
        $formHTML = $this->getCustomizedMMPFormHTMLByID($page_id,1);
		
        $mmpIndexFilePath = 'public/mmp/'.$page_id.'/index.html';
        $fileContents = file_get_contents($mmpIndexFilePath);
        if($fileContents != false){
            $updatedFileContent = str_replace('@MMPFORM', $formHTML, $fileContents);
            //file_put_contents($mmpIndexFilePath, $updatedFileContent, LOCK_EX);
        }
        $dom = new DOMDocument;
        $dom->loadHTML($updatedFileContent);
        $images = $dom->getElementsByTagName('img');
        foreach($images as $image) {
            $origImg = $image->getAttribute('src');
            if(substr($origImg,0,7) == 'images/')
            $image->setAttribute('src', '/public/mmp/'.$page_id.'/'.$origImg);
        }
        $links = $dom->getElementsByTagName('link');
        foreach($links as $link){
            $origCss =  $link->getAttribute('href');
            if(substr($origCss,0,4) == 'css/' ){
                $link->setAttribute('href', '/public/mmp/'.$page_id.'/'. $origCss);
            }
        }
        $scripts = $dom->getElementsByTagName('script');
        foreach($scripts as $script){
            $origScript =  $script->getAttribute('src');
            if(substr($origScript,0,3) == 'js/' ){
                $script->setAttribute('src', '/public/mmp/'.$page_id.'/'. $origScript);
            }
        }
        echo $dom->saveHTML();
        exit();
    }
	
    public function templateFormNew($page_id)
	{
        $this->init();
        $this->load->library("mmp_template_uploader");
        $mmp_template_uploader_lib = new mmp_template_uploader();
		
		$templatizedMMP = Modules::run('registration/Forms/templatizedMMP', $page_id);
	
		$templatePath = 'public/mmp/'.$page_id;
		if(isMobileRequest()) {
			$templatePath = 'public/mmp/'.$page_id.'/mobile';
			/**
			 * Doesn't have a mobile version
			 */ 
			if(!file_exists($templatePath.'/index.html')) {
				$templatePath = 'public/mmp/'.$page_id;
			}
		}
		else {
			/**
			 * Only has a mobile version
			 */ 
			if(!file_exists($templatePath.'/index.html')) {
				$templatePath = 'public/mmp/'.$page_id.'/mobile';
			}
		}
		
        $mmpIndexFilePath = $templatePath.'/index.html';
        $fileContents = file_get_contents($mmpIndexFilePath);
        if($fileContents != false){
			foreach($templatizedMMP as $dataElement => $dataContent) {
				$fileContents = str_replace($dataElement, $dataContent, $fileContents);
			}
        }
		
        $dom = new DOMDocument;
        $dom->loadHTML($fileContents);
        $images = $dom->getElementsByTagName('img');
        foreach($images as $image) {
            $origImg = $image->getAttribute('src');
            if(substr($origImg,0,7) == 'images/')
            $image->setAttribute('src', '/'.$templatePath.'/'.$origImg);
        }
        $links = $dom->getElementsByTagName('link');
        foreach($links as $link){
            $origCss =  $link->getAttribute('href');
            if(substr($origCss,0,4) == 'css/' ){
                $link->setAttribute('href', '/'.$templatePath.'/'.$origCss);
            }
        }
        $scripts = $dom->getElementsByTagName('script');
        foreach($scripts as $script){
            $origScript =  $script->getAttribute('src');
            if(substr($origScript,0,3) == 'js/' ){
                $script->setAttribute('src', '/'.$templatePath.'/'.$origScript);
            }
        }
        echo $dom->saveHTML();
        exit();
    }

    public function templateFormSandbox($page_id){
        $this->init();
        $this->load->library("mmp_template_uploader");
        $mmp_template_uploader_lib = new mmp_template_uploader();
        $formHTML = $this->getCustomizedMMPFormHTMLByID($page_id,1);
        $mmpIndexFilePath = 'public/mmp/sandbox/'.$page_id.'/index.html';
        $fileContents = file_get_contents($mmpIndexFilePath);
        if($fileContents != false){
            $updatedFileContent = str_replace('@MMPFORM', $formHTML, $fileContents);
        //    file_put_contents($mmpIndexFilePath, $updatedFileContent, LOCK_EX);
        }
        $dom = new DOMDocument;
        $dom->loadHTML($updatedFileContent);
        $images = $dom->getElementsByTagName('img');
        foreach($images as $image) {
            $origImg = $image->getAttribute('src');
            if(substr($origImg,0,7) == 'images/')
            $image->setAttribute('src', '/public/mmp/sandbox/'.$page_id.'/'.$origImg);
        }
        $links = $dom->getElementsByTagName('link');
        foreach($links as $link){
            $origCss =  $link->getAttribute('href');
            if(substr($origCss,0,4) == 'css/' ){
                $link->setAttribute('href', '/public/mmp/sandbox/'.$page_id.'/'. $origCss);
            }
        }
        $scripts = $dom->getElementsByTagName('script');
        foreach($scripts as $script){
            $origScript =  $script->getAttribute('src');
            if(substr($origScript,0,3) == 'js/' ){
                $script->setAttribute('src', '/public/mmp/sandbox/'.$page_id.'/'. $origScript);
            }
        }
        echo $dom->saveHTML();
        exit();
    }

	public function showCustomizedMMP($pageId, $status_text, $extraInfo = NULL) {
		$this->init();
		$customizedMMPLib = new customizemmp_lib();
		$returnResult = $customizedMMPLib->listCustomizedMMP();
		$data = array();
        $data['normalForms'] = $customizedMMPLib->getNormalForms();
		$data['marketing_details'] = $returnResult;
		$data['headerTabs'] =$this->getHeaderTabs();
		$data['prodId'] = $this->customizedMMPTabId;
		$data['validateuser'] = $this->userStatus;
		$tempFormTypes = $this->getMMPFormTypes();
		$formData = array();
		foreach($tempFormTypes as $key=>$val){
			$formData[$val['form_id']] = $val;
		}
		$mmpURL = array();
		foreach($returnResult['mmp_details'] as $key=>$value){
			$urls = $this->getMMPUrl($value->page_id);
			$mmpURL[$value->page_id]['sandbox_url'] = $urls['sandbox_url'];
			$mmpURL[$value->page_id]['live_url'] = $urls['live_url'];
			$liveFolderExist = $this->isLiveFileExist($value->page_id);
			$mmpURL[$value->page_id]['live_mmp_exist'] = $liveFolderExist;
		}
		$data['form_types'] = $formData;
		$data['mmp_urls'] = $mmpURL;
		$data['mmp_custom_params'] = $this->getCustomMMPParams($pageId, $status_text, $extraInfo);
		$data['currentPageId'] = $pageId;
		$this->load->view("customizedMMPMainPage", $data);
	}
  
	public function isLiveFileExist($mmp_id = NULL){
		$returnValue = false;
		if($mmp_id != NULL){
			$this->load->library("mmp_template_uploader");
			$mmp_template_uploader_lib = new mmp_template_uploader();
			$path = $this->MMP_LIVE_DIR . $mmp_id;
			$returnRes = $mmp_template_uploader_lib->scanDirectory($path);
			if(!empty($returnRes['success']) && $returnRes['success'] == true){
				$returnValue = true;
			} else {
				$returnValue = false;
			}
		}
		return $returnValue;
	}
	
	public function redirectToCustomizedMMP($params = array()) {
		$this->init();
		$data = array();
		$data['headerTabs'] = $this->getHeaderTabs();
		$data['validateuser'] = $this->userStatus;
		$data['mmp_custom_params'] = $params;
		$this->load->view("customizedMMPMainPage", $data);
	}
	
	public function marketingPageDetailsById($page_id){
		$this->init();
		$customizedMMPLib = new customizemmp_lib();
		$returnResult = $customizedMMPLib->marketingPageDetailsById($page_id);
		return $returnResult;
	}
	
	public function createMMPPage() {
		$this->init();
		$mmp_name = trim($this->input->post('mmp_name'));
		$mmp_destination_url = trim($this->input->post('mmp_destination_url'));
		$action_type = trim($this->input->post('action_type'));
		$page_id = trim($this->input->post('page_id'));
		$form_type_id = trim($this->input->post('form_type'));
		
		#todo: valid logic should be in a separate function
		# rewrite function
		if($mmp_name > 0) {
			$customizedMMPLib = new customizemmp_lib();
			$params = array();
			$params['page_name'] = $mmp_name;
			$params['destination_url'] = $mmp_destination_url;
			$params['page_id'] = $mmp_name;
			$params['action'] = $action_type;
			$params['form_type_id'] = $form_type_id;
			$latestPageId = $customizedMMPLib->createMMPPage($params);
			if($action_type == "insert"){
				header('Location: '. SHIKSHA_HOME .'/customizedmmp/mmp/showCustomizedMMP/'.$latestPageId."/mmp_create_successfully/");
				exit();
			} else if($action_type == "update"){
				header('Location: '. SHIKSHA_HOME .'/customizedmmp/mmp/showCustomizedMMP/'.$latestPageId."/mmp_edited_successfully/");
				exit();
			}
		} else {
			//$params = array();
			//$params['error'] = true;
			//$params['error_header'] = "Oops form validation failed";
			//$params['field_values']['mmp_name'] = $mmp_name;
			//$params['field_values']['mmp_destination_url'] = $mmp_destination_url;
			//if(strlen($mmp_name) <= 0){
			//	$params['error_text']['mmp_name'] = "mmp name is left blank";
			//}
			if($action_type == "insert"){
				header('Location: '. SHIKSHA_HOME .'/customizedmmp/mmp/showCustomizedMMP/-1/mmp_creation_failed/');
				exit();	
			} else if($action_type == "update"){
				header('Location: '. SHIKSHA_HOME .'/customizedmmp/mmp/editMMPPageDetails/'.$page_id.'/mmp_edit_failed/');
				exit();	
			}
		}
	}
	
	private function getHeaderTabs () {
		$this->userStatus = $this->checkUserValidation();
		if(($this->userStatus == "false" )||($this->userStatus == "")) {
			header('location:/enterprise/Enterprise/loginEnterprise');
			exit();
		}
		if(is_array($this->userStatus) && $this->userStatus['0']['usergroup']!='cms') {
			header("location:/enterprise/Enterprise/disallowedAccess");
			exit();
		}
		$entObj = new Enterprise_client();
		$headerTabs = $entObj->getHeaderTabs(1,$this->userStatus[0]['usergroup'],$this->userStatus[0]['userid']);
		$headerTabs['0']['selectedTab'] = $this->customizedMMPTabId;
		return $headerTabs;
	}
	function ary_diff( $ary_1, $ary_2 ) {
	  // compare the value of 2 array
	  // get differences that in ary_1 but not in ary_2
	  // get difference that in ary_2 but not in ary_1
	  // return the unique difference between value of 2 array
	  $diff = array();
	  
	  // get differences that in ary_1 but not in ary_2
	  foreach ( $ary_1 as $v1 ) {
	    $flag = 0;
	    foreach ( $ary_2 as $v2 ) {
	      $flag |= ( $v1 == $v2 );
	      if ( $flag ) break;
	    }
	    if ( !$flag ) array_push( $diff, $v1 );
	  }
	  
	  // get difference that in ary_2 but not in ary_1
	  foreach ( $ary_2 as $v2 ) {
	    $flag = 0;
	    foreach ( $ary_1 as $v1 ) {
	      $flag |= ( $v1 == $v2 );
	      if ( $flag ) break;
	    }
	    if ( !$flag && !in_array( $v2, $diff ) ) array_push( $diff, $v2 );
	  }
	  
	  return $diff;
	}
	public function addRemoveMMPageCourses($page_id,$pagetype) {
		$this->init();
		$data['headerTabs'] = $this->getHeaderTabs();
		$data['prodId'] = 101;
		$data['page_id'] = $page_id;
		$data['validateuser'] = $this->userStatus;
		$page_details = $this->marketingPageDetailsById($page_id);
		if($page_details['mmp_type'] != "customized"){
			header('Location: '. SHIKSHA_HOME .'/customizedmmp/mmp/showCustomizedMMP/'.$page_id."/mmp_type_incorrect/");
			exit();
		}
		$data['page_url'] = $page_details['page_url'];
		$data['count_courses'] = $page_details['count_courses'];
		if(empty($pagetype)) {
			$pagetype = $page_details['page_type'];	
		}
		
		$data['original_page_type'] = $page_details['page_type'];
		$data['page_type'] = $pagetype;
		if($pagetype=='indianpage') {
			$ldbObj = new LDB_Client();
			$data['courses_list']= json_decode($this->MarketingPageClient->getCourseSpecializationForAllCategoryIdGroup($page_id),true);
			$data['mba_courses_all'] = json_decode($ldbObj->sgetCourseList($appId,3),true);
			$distance_course = json_decode($ldbObj->sgetSpecializationListByParentId($appId,24),true);
			$data['mba_courses_all'] = array_merge($data['mba_courses_all'],$distance_course);
			$saved_courses_list = json_decode($this->MarketingPageClient->getCourselistForApage($page_id,'category'),true);
			$data['saved_courses_list'] =$saved_courses_list['courses_list'];
			$data['course_ids'] = str_replace(',',' ',$saved_courses_list['course_id']);
			$data['management_courseids'] = trim(($saved_courses_list['management_courseids']));
			$data['saved_management_courses'] =
			$this->MarketingPageClient->getManagementCourses($data['management_courseids']);
			$new_value = $this->ary_diff($data['mba_courses_all'],$data['saved_management_courses']);
			
			//echo "<pre>";print_r($new_value);echo "</pre>";
			
			$data['mba_courses'] = $new_value; //$data['mba_courses_all'];
			$view_template = 'addremovemmpagecourses';
		} else if($pagetype=='abroadpage') {
			// load data for courses list
			$data['courses_list'] = json_decode($this->MarketingPageClient->getStudyAbroadCoursesListForApage(1,$page_id,$pagetype,'complete_list'),true);
			$categories = array();
			foreach($data['courses_list']['courses_list'] as $category) {
				$categories[$category[0]['categoryID'][0]] = $category[0]['categoryName'][0];
			}
			asort($categories);
			$data['courses_list']= $categories;
			$data['saved_courses_lists']= json_decode($this->MarketingPageClient->getStudyAbroadCoursesListForApage(1,$page_id,$pagetype,'saved_list'),true);
			$data['course_ids'] = $data['saved_courses_lists']['course_id'];
			$data['course_ids'] = str_replace(',',' ',$data['course_ids']);
			$savedcatgeories = array();
			foreach($data['saved_courses_lists']['courses_list'] as $category) {
				$savedcatgeories[$category[0]['categoryID'][0]] = $category[0]['categoryName'][0];
			}
			asort($savedcatgeories);
			$data['saved_courses_lists']= $savedcatgeories;
			$view_template = 'addremovemmpageabroadcourses';
		} else if($pagetype=='testpreppage') {
			//$categoryClient = new Category_list_client();
			$data['courses_list'] = json_decode($this->MarketingPageClient->getTestPrepCoursesListForApage(1,$page_id,$pagetype,'complete_list'),true);
			$data['courses_list'] = $data['courses_list']['courses_list'];
			$data['saved_courses_lists']= json_decode($this->MarketingPageClient->getTestPrepCoursesListForApage(1,$page_id,$pagetype,'saved_list'),true);
			$data['saved_courses_list'] = $data['saved_courses_lists']['courses_list'];
			$data['course_ids'] = $data['saved_courses_lists']['course_id'];
			$data['course_ids'] = str_replace(',',' ',$data['course_ids']);
			$view_template = 'addremovemmpagetestprepcourses';
		}
		$this->load->view($view_template,$data);
	}
	
	public function saveMMPageCourses($page_id,$page_type) {
		$this->init();
		$this->getHeaderTabs();
		$data['page_id'] = $page_id;
		$courses_ids = trim($this->input->post('courses_ids'));
		$page_details= $this->marketingPageDetailsById($page_id);
		if(empty($page_type)) {
			$page_type = $page_details['page_type'];
		}
		if($page_type=='indianpage') {
			$management_courses_ids = trim($this->input->post('management_courses_ids'));
			$courses_ids = $courses_ids.'-'.$management_courses_ids;
		}
		$this->MarketingPageClient->saveMMPageCourses($page_id,$courses_ids,$page_type);
		$page_details_temp = $this->marketingPageDetailsById($page_id);
		$coursesLeft = 0;
		if(!empty($page_details_temp['count_courses'])){
			$coursesLeft = $page_details_temp['count_courses'];
		}
		if($coursesLeft > 0){
			header('Location: '. SHIKSHA_HOME .'/customizedmmp/mmp/showCustomizedMMP/'.$page_id."/mmp_courses_added/".$coursesLeft."/");
		} else {
			header('Location: '. SHIKSHA_HOME .'/customizedmmp/mmp/showCustomizedMMP/'.$page_id."/mmp_courses_deleted/".$coursesLeft."/");
		}
		exit(0);
	}
	
	public function editMMPPageDetails($pageId, $status_text, $extraInfo) {
		$this->init();
		$data = array();
		$data['headerTabs'] =$this->getHeaderTabs();
		$data['prodId'] = $this->customizedMMPTabId;
		$data['validateuser'] = $this->userStatus;
		$data['currentPageId'] = $pageId;
		$page_details = $this->marketingPageDetailsById($pageId);
		$data['form_types'] = $this->getMMPFormTypes();
		$data['page_details'] = $page_details;
		if($data['page_details']['mmp_type'] != "customized"){
			header('Location: '. SHIKSHA_HOME .'/customizedmmp/mmp/showCustomizedMMP/'.$pageId."/mmp_type_incorrect/");
			exit();
		}
		$data['mmp_custom_params'] = $this->getCustomMMPParams($pageId, $status_text, $extraInfo);
		$this->load->view("editCustomizeMMPPageDetails", $data);
	}
	
	public function getMMPFormTypes(){
		$this->init();
		$customizedMMPLib = new customizemmp_lib();
		$returnResult = $customizedMMPLib->getMMPFormTypes();
		return $returnResult;
	}
	
	public function showUploadTemplateForm($pageId, $status_text, $extraInfo){
		$this->init();
		$data = array();
		$data['headerTabs'] = $this->getHeaderTabs();
		$data['prodId'] = $this->customizedMMPTabId;
		$data['validateuser'] = $this->userStatus;
		$data['currentPageId'] = $pageId;
		$page_details = $this->marketingPageDetailsById($pageId);
		$data['page_details'] = $page_details;
		$data['mmp_custom_params'] = $this->getCustomMMPParams($pageId, $status_text, $extraInfo);
		if($data['page_details']['mmp_type'] != "customized"){
			header('Location: '. SHIKSHA_HOME .'/customizedmmp/mmp/showCustomizedMMP/'.$pageId."/mmp_type_incorrect/");
			exit();
		}
		$this->load->view("uploadMMPTemplate", $data);
	}
	
	public function makeMMPLive($pageId, $status_text, $extraInfo){
		$this->init();
		$data = array();
		$data['headerTabs'] = $this->getHeaderTabs();
		$data['prodId'] = $this->customizedMMPTabId;
		$data['validateuser'] = $this->userStatus;
		$data['currentPageId'] = $pageId;
		$page_details = $this->marketingPageDetailsById($pageId);
		$data['page_details'] = $page_details;
		$data['mmp_custom_params'] = $this->getCustomMMPParams($pageId, $status_text, $extraInfo);
		if($data['page_details']['mmp_type'] != "customized"){
			header('Location: '. SHIKSHA_HOME .'/customizedmmp/mmp/showCustomizedMMP/'.$pageId."/mmp_type_incorrect/");
			exit();
		} else if($data['page_details']['status'] == "created"){
			header('Location: '. SHIKSHA_HOME .'/customizedmmp/mmp/showCustomizedMMP/'.$pageId."/mmp_status_incorrect/");
			exit();
		}
		$mmp_url = $this->getMMPUrl($pageId);
		$sandbox_preview_link = $mmp_url['sandbox_url'];
		$data['sandbox_preview_link'] = $sandbox_preview_link;
		$this->load->view("makeMMPLive", $data);
	}
	
	public function moveMMPFromSandboxToLive() {
		$this->init();
		$this->load->library("mmp_template_uploader");
		$mmp_template_uploader_lib = new mmp_template_uploader();
		$page_id = trim($this->input->post('page_id'));
		$page_details = $this->marketingPageDetailsById($page_id);
		if(!empty($page_details['mmp_type']) && $page_details['mmp_type'] == "customized" && ( $page_details['status'] == "development" || $page_details['status'] == "live")){
			$returnData = $mmp_template_uploader_lib->moveMMPFromDevToLive($page_id);
		} else if(!empty($page_details['mmp_type']) && $page_details['mmp_type'] == "customized" && $page_details['status'] == "created"){
			$returnData['error'] = true;
			$returnData['error_text']['mmp_status'] = "The mmp is not in valid state";
		} else {
			$returnData['error'] = true;
			$returnData['error_text']['mmp_type'] = "The mmp is not of customized type";
		}
		$data = array();
		$data['headerTabs'] = $this->getHeaderTabs();
		$data['prodId'] = $this->customizedMMPTabId;
		$data['validateuser'] = $this->userStatus;
		$data['currentPageId'] = $page_id;
		if(!empty($returnData['success']) && $returnData['success'] == true){
			$data['mmp_custom_params'] = $this->getCustomMMPParams($page_id, "make_mmp_live_success", $returnData);	
		} else {
			$data['mmp_custom_params'] = $this->getCustomMMPParams($page_id, "make_mmp_live_failed", $returnData);	
		}
		$updateStatusSuccessfully = false;
		if($returnData['success'] == true) {
			$customizedMMPLib = new customizemmp_lib();
			$updateStatusSuccessfully = $customizedMMPLib->updateMMPStatus($page_id, 'live');
		}
		$page_details = $this->marketingPageDetailsById($page_id);
		$data['page_details'] = $page_details;
		
		$mmp_url = $this->getMMPUrl($page_id);
		$sandbox_preview_link = $mmp_url['sandbox_url'];
		$data['sandbox_preview_link'] = $sandbox_preview_link;
		
		$this->load->view("makeMMPLive", $data);
	}
	
	public function uploadMMPTemplate($pageid) {
		$this->init();
		$this->load->library("mmp_template_uploader");
		$mmp_template_uploader_lib = new mmp_template_uploader();
		$config = array();
		$page_id = trim($this->input->post('page_id'));
		$config['upload_path'] = $this->MMP_SANDBOX_DIR;
		$config['allowed_types'] = 'zip';
		$config['overwrite'] = true;
		$page_details = $this->marketingPageDetailsById($page_id);
		$totalCoursesSelectedMMP = $page_details['count_courses'];
		
		if(!empty($page_details['mmp_type']) && $page_details['mmp_type'] == "customized"){ // if mmp page is not of customized type throw error
			$this->load->library('upload', $config);
			$returnData = array();
			if (!$this->upload->do_upload('template_file')) {
				$returnData['file_upload'] = $this->upload->display_errors();
			} else {
				// mmp template upload successful
				$data = array('upload_data' => $this->upload->data());
				$mmpFilename = $data['upload_data']['raw_name']; //101
				$mmpZipFilePath = $data['upload_data']['full_path']; // /var/www/html/shiksha/public/mmp/sandbox/101.zip
				$mmpExtractedPath = $data['upload_data']['file_path']; // /var/www/html/shiksha/public/mmp/sandbox/
				if((string)$page_id != $mmpFilename) {
					$returnData['file_name'] = "Uploaded template file name is not matched with page id";
					$mmp_template_uploader_lib->removeExistingDirectoryOrFile($mmpZipFilePath);
				} else {
					if(!$mmp_template_uploader_lib->checkTemplateName($mmpFilename)){
						$mmp_template_uploader_lib->removeExistingDirectoryOrFile($mmpZipFilePath);
						$returnData['file_name'] = "Uploaded template file name is not proper";
					} else {
						// copy the existing folder with same name as old 
						$mmp_template_uploader_lib->copyDirectoryRecursive($mmpExtractedPath.$mmpFilename, $mmpExtractedPath.$mmpFilename.'-old');
						// check if everything with zip uploaded is fine
						$returnInfo = $mmp_template_uploader_lib->checkMMPZipFormat($mmpFilename, $mmpZipFilePath, $mmpExtractedPath);
						if(array_key_exists('success', $returnInfo)) {
						//	// get form HTML for mmp
						//	$formHTML = $this->getCustomizedMMPFormHTMLByID($page_id);
						//	$mmpIndexFilePath = $returnInfo['index_file_path'];
						//    // replace placeholder by actual form
						//	$placeHolderReplaced = $mmp_template_uploader_lib->replacePlaceHolderByMMPForm($mmpIndexFilePath, $formHTML);
						//	if(!$placeHolderReplaced) {
						//		// place holder couldn't replaced successfully 
						//		$returnData['placeholder_replace'] = "Error occurred while replacing placeholder in html file";
						//		//delete uploaded zip file
						//		$mmp_template_uploader_lib->removeExistingDirectoryOrFile($mmpZipFilePath);
						//		//delete new folder created for mmp
						//		$extractedMMPFolderPath = $mmpExtractedPath . $mmpFilename;
						//		$mmp_template_uploader_lib->removeExistingDirectoryOrFile($extractedMMPFolderPath);
						//		//rename orlder version to latest, as new mmp upload process failed
						//		rename($mmpExtractedPath . $mmpFilename .'-old', $mmpExtractedPath . $mmpFilename);
						//	} else {
						//		//place holder successfully replaced by form html, now delete older version of mmp in sandbox
						//		$mmp_template_uploader_lib->removeExistingDirectoryOrFile($mmpExtractedPath . $mmpFilename .'-old');
						//	}
						} else {
							//some problem with uploaded zip file
							if(!empty($returnInfo['folder_to_be_deleted'])){
								foreach($returnInfo['folder_to_be_deleted'] as $folderName){
									if(!empty($folderName)){
										$folder_to_be_deleted_path = $this->MMP_SANDBOX_DIR . "/". $folderName;
										$mmp_template_uploader_lib->removeExistingDirectoryOrFile($folder_to_be_deleted_path);
									}
								}
							}
							//delete the zip file just uploaded
							$mmp_template_uploader_lib->removeExistingDirectoryOrFile($mmpZipFilePath);
							$extractedMMPFolderPath = $mmpExtractedPath . $mmpFilename;
							// delete the folder created for new zip file
							$mmp_template_uploader_lib->removeExistingDirectoryOrFile($extractedMMPFolderPath);
							// replace old folder as latest mmp as the upload action failed
							rename($mmpExtractedPath . $mmpFilename .'-old', $mmpExtractedPath . $mmpFilename);
							$returnData = $returnInfo;
						}
					}
				}
			}
		} else {
			$returnData['error'] = true;
			$returnData['error_text']['mmp_type'] = "The mmp is not of customized type";
		}
		if(!empty($returnData)){
			$returnData['error'] = true;
		} else {
			$returnData['success'] = true;
		}
		
		$updateStatusSuccessfully = false;
		if($returnData['success'] == true) {
			$customizedMMPLib = new customizemmp_lib();
			$updateStatusSuccessfully = $customizedMMPLib->updateMMPStatus($page_id, 'development');
		}
		
		$data = array();
		$data['headerTabs'] = $this->getHeaderTabs();
		$data['prodId'] = $this->customizedMMPTabId;
		$data['validateuser'] = $this->userStatus;
		$data['currentPageId'] = $page_id;
		$page_details = $this->marketingPageDetailsById($page_id);
		$data['page_details'] = $page_details;
		$data['mmp_custom_params'] = $returnData;
		$mmp_url = $this->getMMPUrl($page_id);
		$data['preview_link'] = $mmp_url['sandbox_url'];
		$this->load->view("uploadMMPTemplate", $data);
	}
	
	public function makeMMPDisable($pageId, $status_text, $extraInfo) {
		$this->init();
		$data = array();
		$data['headerTabs'] = $this->getHeaderTabs();
		$data['prodId'] = $this->customizedMMPTabId;
		$data['validateuser'] = $this->userStatus;
		$data['currentPageId'] = $pageId;
		$page_details = $this->marketingPageDetailsById($pageId);
		$data['page_details'] = $page_details;
		$data['mmp_custom_params'] = $this->getCustomMMPParams($pageId, $status_text, $extraInfo);
		if($data['page_details']['mmp_type'] != "customized"){
			header('Location: '. SHIKSHA_HOME .'/customizedmmp/mmp/showCustomizedMMP/'.$pageId."/mmp_type_incorrect/");
			exit();
		} else if($data['page_details']['status'] != "live"){
			header('Location: '. SHIKSHA_HOME .'/customizedmmp/mmp/showCustomizedMMP/'.$pageId."/mmp_status_incorrect/");
			exit();
		}
		$mmp_url = $this->getMMPUrl($pageId);
		$live_preview_link = $mmp_url['live_url'];
		$data['live_preview_link'] = $live_preview_link;
		$this->load->view("makeMMPDisable", $data);
	}
	
	public function disableLiveMMP(){
		$this->init();
		$this->load->library("mmp_template_uploader");
		$mmp_template_uploader_lib = new mmp_template_uploader();
		$page_id = trim($this->input->post('page_id'));
		$page_details = $this->marketingPageDetailsById($page_id);
		
		if(!empty($page_details['mmp_type']) && $page_details['mmp_type'] == "customized" && ( $page_details['status'] == "development" || $page_details['status'] == "live")){
			$returnData = $mmp_template_uploader_lib->disableLiveMMP($page_id);
		} else if(!empty($page_details['mmp_type']) && $page_details['mmp_type'] == "customized" && $page_details['status'] == "created"){
			$returnData['error'] = true;
			$returnData['error_text']['mmp_status'] = "The mmp is not in valid state";
		} else {
			$returnData['error'] = true;
			$returnData['error_text']['mmp_type'] = "The mmp is not of customized type";
		}
		$data = array();
		$data['headerTabs'] = $this->getHeaderTabs();
		$data['prodId'] = $this->customizedMMPTabId;
		$data['validateuser'] = $this->userStatus;
		$data['currentPageId'] = $page_id;
		if(!empty($returnData['success']) && $returnData['success'] == true){
			$data['mmp_custom_params'] = $this->getCustomMMPParams($page_id, "mmp_disable_success", $returnData);
		} else {
			$data['mmp_custom_params'] = $this->getCustomMMPParams($page_id, "mmp_disable_failed", $returnData);
		}
		$updateStatusSuccessfully = false;
		if($returnData['success']) {
			$customizedMMPLib = new customizemmp_lib();
			$updateStatusSuccessfully = $customizedMMPLib->updateMMPStatus($page_id, 'history');
		}
		$page_details = $this->marketingPageDetailsById($page_id);
		$data['page_details'] = $page_details;
		
		$mmp_url = $this->getMMPUrl($page_id);
		$live_preview_link = $mmp_url['live_url'];
		$data['live_preview_link'] = $live_preview_link;
		
		$this->load->view("makeMMPDisable", $data);
	}
	
	public function editMMPIndexFile($pageId, $fileType = 'sandbox') {
		$this->init();
		
		$data = array();
		$data['headerTabs'] = $this->getHeaderTabs();
		$data['prodId'] = $this->customizedMMPTabId;
		$data['validateuser'] = $this->userStatus;
		$data['currentPageId'] = $pageId;
		$page_details = $this->marketingPageDetailsById($pageId);
		$data['page_details'] = $page_details;
		if($data['page_details']['mmp_type'] != "customized"){
			header('Location: '. SHIKSHA_HOME .'/customizedmmp/mmp/showCustomizedMMP/'.$pageId."/mmp_type_incorrect/");
			exit();
		} else if($data['page_details']['status'] != "live" && $data['page_details']['status'] != "development"){
			header('Location: '. SHIKSHA_HOME .'/customizedmmp/mmp/showCustomizedMMP/'.$pageId."/mmp_status_incorrect/");
			exit();
		}
		
		$mmp_url = $this->getMMPUrl($pageId);
		$data['live_mmp_url'] = $mmp_url['live_url'];
		$data['sandbox_mmp_url'] = $mmp_url['sandbox_url'];
		$data['file_type'] = $fileType;
		
		$filePath = $this->MMP_SANDBOX_DIR . "/" . $pageId . "/index.html";
		if($fileType == "live"){
			$filePath = $this->MMP_LIVE_DIR . $pageId . "/index.html";
		}
		$this->load->library("mmp_template_uploader");
		$mmp_template_uploader_lib = new mmp_template_uploader();
		$returnData = $mmp_template_uploader_lib->readFileContent($filePath);
		if(!empty($returnData['success']) && $returnData['success'] == true){
			$data['file_content'] = utf8_encode($returnData['file_content']);
		} else {
			$data['file_content'] = false;
			$data['mmp_custom_params'] = $this->getCustomMMPParams($pageId, "index_file_read_failed", $returnData);
		}
		$this->load->view("editMMPIndexFile", $data);
	}
	
	public function postMMPIndexFile() {
		$this->init();
		$data = array();
		$data['headerTabs'] = $this->getHeaderTabs();
		$data['prodId'] = $this->customizedMMPTabId;
		$data['validateuser'] = $this->userStatus;
		
		$page_id = trim($this->input->post('page_id'));
		$file_type = trim($this->input->post('file_type'));
		
		if(!empty($this->input->raw_request_variable['file_content'])){
			$file_contents = utf8_encode($this->input->raw_request_variable['file_content']);	
		} else {
			$post_data = explode("&", file_get_contents('php://input'));
			$result = array();
			foreach( $post_data as $post_datum) {
				$pair = explode("=", $post_datum );
				$result[urldecode($pair[0])] = urldecode(($pair[1]));
			}
			$file_contents = utf8_encode($result['file_content']);
		}
		
		$filePath = $this->MMP_SANDBOX_DIR . "/" . $page_id . "/index.html";
		if($file_type == "live"){
			$filePath = $this->MMP_LIVE_DIR . $page_id . "/index.html";
		}
		
		$page_details = $this->marketingPageDetailsById($page_id);
		if(!empty($page_details['mmp_type']) && $page_details['mmp_type'] == "customized" && ( $page_details['status'] == "development" || $page_details['status'] == "live")) {
			$this->load->library("mmp_template_uploader");
			$mmp_template_uploader_lib = new mmp_template_uploader();
			$copyStatus = copy($filePath, $filePath.'.backup-'.date("d.m.y:H.m.s"));
			if($copyStatus){
				$returnData = $mmp_template_uploader_lib->writeFileContent($filePath, $file_contents);
				if(!empty($returnData['success']) && $returnData['success'] == true){
					$returnData = $this->getCustomMMPParams($page_id, "index_file_write_success", $returnData);
					$file_read_data = $mmp_template_uploader_lib->readFileContent($filePath);
					if(!empty($file_read_data['file_content'])){
						$file_contents = $file_read_data['file_content'];
					}
				} else {
					$returnData = $this->getCustomMMPParams($page_id, "index_file_write_failed", $returnData);
				}	
			} else {
				$returnData['error'] = true;
				$returnData['error_text']['index_backup_file'] = "Failed to create backup of index file";
			}
		} else if(!empty($page_details['mmp_type']) && $page_details['mmp_type'] == "customized" && $page_details['status'] == "created"){
			$returnData['error'] = true;
			$returnData['error_text']['mmp_status'] = "The mmp is not in valid state";
		} else {
			$returnData['error'] = true;
			$returnData['error_text']['mmp_type'] = "The mmp is not of customized type";
		}
		
		$data['mmp_custom_params'] = $returnData;
		$mmp_url = $this->getMMPUrl($page_id);
		$data['live_mmp_url'] = $mmp_url['live_url'];
		$data['sandbox_mmp_url'] = $mmp_url['sandbox_url'];
		$data['file_type'] = $file_type;
		$data['file_content'] = $file_contents;
		$data['file_content_updated'] = true;
		$data['currentPageId'] = $page_id;
		$this->load->view("editMMPIndexFile", $data);
	}
	
	public function listMMPFiles($page_id = NULL, $type = "sandbox") {
		$this->init();
		if($page_id == NULL) {
			header('Location: '. SHIKSHA_HOME .'/customizedmmp/mmp/showCustomizedMMP/'.$page_id."/mmp_id_incorrect/");
			exit();
		} else {
			$page_details = $this->marketingPageDetailsById($page_id);
			if(empty($page_details['mmp_type']) || $page_details['mmp_type'] != "customized" || ( $page_details['status'] != "development" && $page_details['status'] != "live") ) {
				header('Location: '. SHIKSHA_HOME .'/customizedmmp/mmp/showCustomizedMMP/'.$page_id."/mmp_type_incorrect/");
				exit();
			} else {
				$data = array();
				$data['headerTabs'] = $this->getHeaderTabs();
				$data['prodId'] = $this->customizedMMPTabId;
				$data['validateuser'] = $this->userStatus;
				$data['currentPageId'] = $page_id;
				$data['file_type'] = $type;
				
				$this->load->library("mmp_template_uploader");
				$mmp_template_uploader_lib = new mmp_template_uploader();
				$returnData = $mmp_template_uploader_lib->getMMPFilesList($page_id, $type);
				if(!empty($returnData['error'])) {
					$data['mmp_custom_params'] = $this->getCustomMMPParams($page_id, "mmp_dire_read_issue", $returnData);
				} else {
					$filesArrayNew = array();
					foreach($returnData as $key=>$value){
						$matchForArchive = strstr($key, 'archive');
						$matchForBackup = strstr($key, 'backup');
						$match = strstr($value, 'backup'); // check if index should not contain any backup file
						if(!$match && $key != "images" && $key != "js" && !$matchForBackup && !$matchForArchive){
							if(is_array($value)){ // if its not index file than check in array of files for backup file
								foreach($value as $tempK => $tempV){
									$matchForBackup = strstr($tempK, 'backup');
									if(!$matchForBackup){
										$ext = pathinfo($tempV, PATHINFO_EXTENSION);
										if($ext == 'html' || $ext == 'css'){
											$filesArrayNew[$key][$tempK] = $tempV;	
										}
									}
								}
							} else {
								$ext = pathinfo($value, PATHINFO_EXTENSION);
								if($ext == 'html' || $ext == 'css'){
									$filesArrayNew[$key] = $value;	
								}
							}
						}
					}
					$data['files_list'] = $filesArrayNew;
				}
				$this->load->view("listMMPFiles", $data);
			}
		}
	}
	
	public function editFile($page_id, $file_type, $dir_name, $file_name){
		$this->init();
		$mmpPath = $this->MMP_SANDBOX_DIR . "/" . $page_id . "/";
		if($file_type == "live"){
			$mmpPath = $this->MMP_LIVE_DIR . $page_id . "/";
		}
		$filePath = "";
		if($dir_name == "index"){
			$filePath = $mmpPath . $file_name;
		} else {
			$filePath = $mmpPath . $dir_name . "/" . $file_name;
		}
		
		if(!file_exists($filePath)){
			header('Location: '. SHIKSHA_HOME .'/customizedmmp/mmp/showCustomizedMMP/'.$page_id."/mmp_edit_file_incorrect/");
			exit();
		} else {
			$data['headerTabs'] = $this->getHeaderTabs();
			$data['prodId'] = $this->customizedMMPTabId;
			$data['validateuser'] = $this->userStatus;
			$data['currentPageId'] = $page_id;
			$data['file_type'] = $file_type;
			$data['file_name'] = $file_name;
			$data['dir_name'] = $dir_name;
			
			
			if($dir_name == "index"){
				$data['live_mmp_url'] = SHIKSHA_HOME . "/public/mmp/". $page_id . "/" . $file_name;
				$data['sandbox_mmp_url'] = SHIKSHA_HOME . "/public/mmp/sandbox/". $page_id . "/" . $file_name;	
			} else {
				$data['live_mmp_url'] = SHIKSHA_HOME . "/public/mmp/". $page_id . "/" . $dir_name . "/" . $file_name;
				$data['sandbox_mmp_url'] = SHIKSHA_HOME . "/public/mmp/sandbox/". $page_id . "/" . $dir_name . "/" . $file_name;
			}
			
			
			$this->load->library("mmp_template_uploader");
			$mmp_template_uploader_lib = new mmp_template_uploader();
			$returnData = $mmp_template_uploader_lib->readFileContent($filePath);
			if(!empty($returnData['success']) && $returnData['success'] == true){
				$data['file_content'] = utf8_decode($returnData['file_content']);
			} else {
				$data['file_content'] = false;
				$data['mmp_custom_params'] = $this->getCustomMMPParams($pageId, "index_file_read_failed", $returnData);
			}
			$this->load->view("editMMPIndexFile", $data);
		}
	}
	
	public function postFileContent() {
		$this->init();
		$data = array();
		$data['headerTabs'] = $this->getHeaderTabs();
		$data['prodId'] = $this->customizedMMPTabId;
		$data['validateuser'] = $this->userStatus;
		
		$page_id = trim($this->input->post('page_id'));
		$file_type = trim($this->input->post('file_type'));
		$file_name = trim($this->input->post('file_name'));
		$dir_name = trim($this->input->post('dir_name'));
		
		if(!empty($this->input->raw_request_variable['file_content'])){
			$file_contents = ($this->input->raw_request_variable['file_content']);	
		} else {
			$post_data = explode("&", file_get_contents('php://input'));
			$result = array();
			foreach( $post_data as $post_datum) {
				$pair = explode("=", $post_datum );
				$result[urldecode($pair[0])] = urldecode(($pair[1]));
			}
			$file_contents = ($result['file_content']);
		}
		
		$mmpPath = $this->MMP_SANDBOX_DIR . "/" . $page_id . "/";
		if($file_type == "live"){
			$mmpPath = $this->MMP_LIVE_DIR . $page_id . "/";
		}
		$filePath = "";
		if($dir_name == "index"){
			$filePath = $mmpPath . $file_name;
		} else {
			$filePath = $mmpPath . $dir_name . "/" . $file_name;
		}
		
		$page_details = $this->marketingPageDetailsById($page_id);
		if(!empty($page_details['mmp_type']) && $page_details['mmp_type'] == "customized" && ( $page_details['status'] == "development" || $page_details['status'] == "live")) {
			$this->load->library("mmp_template_uploader");
			$mmp_template_uploader_lib = new mmp_template_uploader();
			$copyStatus = copy($filePath, $filePath.'.backup-'.date("d.m.y:H.m.s"));
			if($copyStatus){
				$returnData = $mmp_template_uploader_lib->writeFileContent($filePath, $file_contents);
				if(!empty($returnData['success']) && $returnData['success'] == true){
					$returnData = $this->getCustomMMPParams($page_id, "file_edit_succes", $returnData);
					$file_read_data = $mmp_template_uploader_lib->readFileContent($filePath);
					if(!empty($file_read_data['file_content'])){
						$file_contents = utf8_decode($file_read_data['file_content']);
					}
				} else {
					$returnData = $this->getCustomMMPParams($page_id, "index_file_write_failed", $returnData);
				}	
			} else {
				$returnData['error'] = true;
				$returnData['error_text']['index_backup_file'] = "Failed to create backup of index file";
			}
		} else if(!empty($page_details['mmp_type']) && $page_details['mmp_type'] == "customized" && $page_details['status'] == "created"){
			$returnData['error'] = true;
			$returnData['error_text']['mmp_status'] = "The mmp is not in valid state";
		} else {
			$returnData['error'] = true;
			$returnData['error_text']['mmp_type'] = "The mmp is not of customized type";
		}
		
		if($dir_name == "index"){
				$data['live_mmp_url'] = SHIKSHA_HOME . "/public/mmp/". $page_id . "/" . $file_name;
				$data['sandbox_mmp_url'] = SHIKSHA_HOME . "/public/mmp/sandbox/". $page_id . "/" . $file_name;	
		} else {
			$data['live_mmp_url'] = SHIKSHA_HOME . "/public/mmp/". $page_id . "/" . $dir_name . "/" . $file_name;
			$data['sandbox_mmp_url'] = SHIKSHA_HOME . "/public/mmp/sandbox/". $page_id . "/" . $dir_name . "/" . $file_name;
		}
		$data['mmp_custom_params'] = $returnData;
		$data['file_type'] = $file_type;
		$data['file_content'] = $file_contents;
		$data['file_content_updated'] = true;
		$data['currentPageId'] = $page_id;
		$data['file_name'] = $file_name;
		$data['dir_name'] = $dir_name;
			
		$this->load->view("editMMPIndexFile", $data);
	}
	
	public function getCustomizedMMPFormHTMLByID($page_id,$includeDependencies = 0) {
		$this->init();
		//$formHTML = Modules::run('multipleMarketingPage/Marketing/customizedmmp', $page_id);
		$formHTML = Modules::run('registration/Forms/MMP', $page_id,$includeDependencies);
		return $formHTML;
	}
	
	public function getTestCustomizedMMPFormHTMLByID($page_id) {
		$this->init();
		//$formHTML = Modules::run('multipleMarketingPage/Marketing/customizedmmp', $page_id);
		$formHTML = Modules::run('registration/Forms/MMP', $page_id,$includeDependencies);
		echo $formHTML;
	}
	
	public function getMMPUrl($page_id){
		$this->init();
		$links = array();
		
		$links['sandbox_url'] = SHIKSHA_HOME . "/customizedmmp/mmp/templateFormSandbox/". $page_id;
		$links['live_url'] = SHIKSHA_HOME . "/customizedmmp/mmp/templateForm/". $page_id;
		
		//$links['sandbox_url'] = SHIKSHA_HOME . "/public/mmp/sandbox/". $page_id;
		//$links['live_url'] = SHIKSHA_HOME . "/public/mmp/". $page_id;
		
		return $links;
	}
	
	private function getCustomMMPParams($pageId, $status_text, $extraInfo) {
		$data = array();
		if(!empty($pageId) && !empty($status_text)) {
			$editCourseLink = SHIKSHA_HOME .'/customizedmmp/mmp/addRemoveMMPageCourses/'.$pageId."/";
			$editDetailsLink = SHIKSHA_HOME .'/customizedmmp/mmp/editMMPPageDetails/'.$pageId."/";
			$uploadZipLink = SHIKSHA_HOME .'/customizedmmp/mmp/showUploadTemplateForm/'.$pageId."/";
			$customizedMMPHome = SHIKSHA_HOME .'/customizedmmp/mmp/showCustomizedMMP/'.$pageId."/";
			
			$mmpUrls = $this->getMMPUrl($pageId);
			$liveMMPLink = $mmpUrls['live_url'];
			$sandboxMMPLink = $mmpUrls['sandbox_url'];
			
			$params = array();
			if($status_text == "mmp_create_successfully"){
				$params['success'] = true;
				$params['success_header'] = "New MMP created successfully!";
				//$params['success_text'][] = "<a href='".$editCourseLink."'>Pick courses</a> for created MMP";
				//$params['success_text'][] = "<a href='".$editDetailsLink."'>Edit MMP details</a>";
			} else if($status_text == "mmp_courses_added"){
				$params['success'] = true;
				$courseString = "courses";
				if(intval($extraInfo) == 1){
					$courseString = "course";
				}
				$params['success_header'] = $extraInfo ." " .$courseString." added successfully for MMP ID: ".$pageId." ";
				$params['success_text'][] = "<a href='".$uploadZipLink."'>Upload template zip</a> for MMP ID: ". $pageId;
				$params['success_text'][] = "<a href='".$editCourseLink."'>Change courses</a> for MMP ID: ". $pageId;
				$params['success_text'][] = "<a href='".$editDetailsLink."'>Edit MMP details</a>";
			} else if($status_text == "mmp_courses_deleted"){
				$params['success'] = true;
				$editCourseLink = SHIKSHA_HOME .'/customizedmmp/mmp/addRemoveMMPageCourses/'.$pageId."/";
				$params['success_header'] = "Zero(0) courses are associated with MMP ID: ".$pageId;
				//$params['success_text'][] = "<a href='".$editCourseLink."'>Pick courses</a> for MMP ID: ". $pageId;
				//$params['success_text'][] = "<a href='".$editDetailsLink."'>Edit MMP details</a>";
			} else if($status_text == 'mmp_creation_failed'){
				$params['error'] = true;
				$params['error_header'] = "No mmp was selected from dropdown";
				$params['error_text'][] = "Try again <a href='javascript:void(0);' onclick='showAddNewMMPForm();'>Add new template</a>";
			} else if($status_text == 'mmp_edit_failed'){
				$params['error'] = true;
				$params['error_header'] = "Server side form validation failed while editing MMP";
			} else if($status_text == "mmp_edited_successfully"){
				$params['success'] = true;
				$params['success_header'] = "MMP details for MMP ID: ".$pageId ." edited successfuly";
				//$params['success_text'][] = "<a href='".$editCourseLink."'>Pick courses</a> for MMP ID: ". $pageId;
				//$params['success_text'][] = "<a href='".$editDetailsLink."'>Edit MMP details</a>";
			} else if($status_text == "make_mmp_live_failed"){
				$params['error'] = true;
				$params['error_header'] = "Server side error occurred while making the sandbox version live";
				$params['error_text'] = $extraInfo['error_text'];
			} else if($status_text == "make_mmp_live_success"){
				$params['success'] = true;
				$params['success_header'] = "The sandbox version of MMP ID = ". $pageId." made live.";
				$params['success_text'][] = "<a href='".$liveMMPLink."'>Preview your live MMP here</a>";
				$params['success_text'][] = "<a href='".$customizedMMPHome."'>Go to customized MMP home</a>";
			} else if($status_text == "mmp_type_incorrect"){
				$params['error'] = true;
				$params['error_header'] = "The mentioned page was not a customizable mmp page, In this section only operations for customized mmp's are allowed.";
			} else if($status_text == "mmp_status_incorrect"){
				$params['error'] = true;
				$params['error_header'] = "The mentioned page was not in valid status";
			} else if($status_text == "mmp_disable_success"){
				$params['success'] = true;
				$params['success_header'] = "The mentioned MMP for pageid: ". $pageId ." has been successfully disabled";
				$params['success_text'][] = "<a href='".$customizedMMPHome."'>Go to customized MMP home</a>";
			} else if($status_text == "mmp_disable_failed"){
				$params['error'] = true;
				$params['error_header'] = "The disable operation for MMP pageid: ". $pageId ." has failed";
				$params['error_text'] = $extraInfo['error_text'];
			} else if($status_text == "index_file_read_failed"){
				$params['error'] = true;
				$params['error_header'] = "Server side error occurred while redaing index file.";
				$params['error_text'] = $extraInfo['error_text'];
			}  else if($status_text == "index_file_write_success"){
				$params['success'] = true;
				$params['success_header'] = "Index file successfully updated.";
				$params['success_text'][] = "<a href='".$customizedMMPHome."'>Go to customized MMP home</a>";
			}  else if($status_text == "index_file_write_failed"){
				$params['error'] = true;
				$params['error_header'] = "Server side error occurred while editing index file.";
				$params['error_text'] = $extraInfo['error_text'];
			}  
			
			$params['page_id'] = $pageId;
			$data = $params;	
		}
		return $data;
	}
	
	private function p($data) {
		echo "<pre>";
		print_r($data);
		echo "<pre>";
	}

	public function EditCustom($arg0, $arg1, $arg2){
		$this->init();
		$formid = $arg0;
		$customizedMMPLib = new customizemmp_lib();
        $pageType = $customizedMMPLib->getPageType($formid);
        if($pageType == 'testpreppage'){
            $testPrepCourses = $customizedMMPLib->getTestPrepCoursesForForm($formid);
            $returnResult = array();
            $counter = 0;
            foreach($testPrepCourses as $k=>$v){
                $acronym = \registration\builders\RegistrationBuilder::getCourseGroupForTestPrep($v['course_id']);
                $testPrepGroupDetails = $customizedMMPLib->getTestPrepGroupDetails($acronym);
                $returnResult[$counter]['coursename'] = $v['acronym'];
                $returnResult[$counter]['courseid'] = $v['course_id'];
                $returnResult[$counter]['groupid'] = $testPrepGroupDetails[0]['groupid'];
                $returnResult[$counter]['groupname'] = $testPrepGroupDetails[0]['groupname'];
                $returnResult[$counter]['acronym'] = $testPrepGroupDetails[0]['acronym'];
                $returnResult[$counter]['reach'] = 'national';
                $counter++;
            }
        }
        else{
            $returnResult = $customizedMMPLib->getCoursesForForm($formid);
        }
		
		$courseUnderCustomization = 0;
		if($arg1 == 'cid') {
			$courseUnderCustomization = $arg2;
		}
		else if($arg2 == 'course') {
			$courseUnderCustomization = $arg1;
		}
		else if(count($returnResult) == 1) {
			$courseUnderCustomization = $returnResult[0]['courseid'];
		}
	
		$id = $arg1;
		$type = $arg2;
        $customizedMMPLib = new customizemmp_lib();
		if($arg1 == 'abroadpage'){
			$customizations = $customizedMMPLib->getCustomizationsForForm($formid,0,'abroadpage');
		}
		else{
			$customizations = $customizedMMPLib->getCustomizationsForForm($formid,$id,$type);
		}
		$customizations = json_decode($customizations);
		$customRuleArr = array();
		$customActionArr = array();
		$counter = 0;
		foreach($customizations as $k=>$v){
				$data['editData'][$counter]['foreignid'] = $v->foreignid;
				$data['editData'][$counter]['name'] = $v->name;
				$data['editData'][$counter]['type'] = $v->type;
				
				$courseId = $courseGroupId = 0;
				if($v->type == 'group') {
					$courseGroupId = $v->foreignid;
				}
				else if($v->type == 'course') {
					$courseId = $v->foreignid;
				}
				if($v->type == 'abroadpage'){
					$form = \registration\builders\RegistrationBuilder::getRegistrationForm('MMP',array('mmpFormId' => $formid));
				}
				else{
					$form = \registration\builders\RegistrationBuilder::getRegistrationForm('MMP',array('mmpFormId' => $formid,'desiredCourseId' => $courseId,'courseGroupId' => $courseGroupId));
				}
				$fields = $form->getFields();
				foreach($fields as $field){
					if($field->getId() == "desiredCourse" && $v->type == 'abroadpage'){
						continue;
					}
					$data['fields'][$counter]['id'] = $field->getId();
					$data['fields'][$counter]['isVisible'] = $field->isVisible();
					$data['fields'][$counter]['isMandatory'] = $field->isMandatory();
					$data['fields'][$counter]['isCustom'] = $field->isCustom();
					if($field->getId() == 'preferredStudyLocality'){
						$data['fields'][$counter]['values'] = $field->getValues(array('cities'  => 'true'));
						$myField = $field;
					}
                    else if ($field->getId() == "exams"){
						if($courseUnderCustomization) {
							$examValues = $field->getValues(array('desiredCourse' => $courseUnderCustomization));
							if(count($examValues)) {
								foreach($examValues['featured'] as $examKey => $examVal) {
									$data['fields'][$counter]['values'][$examKey] = addslashes($examVal->getDisplayNameForUser());
								}
								foreach($examValues['others'] as $examKey => $examVal) {
									$data['fields'][$counter]['values'][$examKey] = addslashes($examVal->getDisplayNameForUser());
								}
							}
						}
					} else if($field->getId() == 'fieldOfInterest') {
						if(STUDY_ABROAD_NEW_REGISTRATION && $v->type == 'abroadpage') {
						
							$abroad_cat_values = $field->getValues(array('twoStep'=>true,'registerationDomain'=>'studyAbroad'));
							//_P($abroad_cat_values);
							$final_value = array();
							foreach($abroad_cat_values as $val) {
								$final_value[$val['id']] = $val['name']; 
							}
							$data['fields'][$counter]['values'] = $final_value;
						}
				       } else if($field->getId() == 'abroadDesiredCourse') {
						//echo $field->getLabel();
						$abroadDesiredCourse = array();
						//$field->setType('select');
						foreach($field->getValues() as $value) {
							$abroadDesiredCourse[$value['SpecializationId']] = $value['CourseName'];
						}
						$data['fields'][$counter]['values'] = $abroadDesiredCourse;				
					} else if($field->getId() == 'desiredGraduationLevel') {

						if(STUDY_ABROAD_NEW_REGISTRATION && $pageType == 'abroadpage') {
							$abroad_grad_level = $field->getValues(array('twoStep'=>true,'registerationDomain'=>'studyAbroad'));
							$abroad_grad_level_final  = array();
		                                        foreach($abroad_grad_level as $level) {
								if($level['CourseName'] !='Certificate/Diploma') {
									$abroad_grad_level_final[] = $level['CourseName'];
								}
							}
							$data['fields'][$counter]['values'] = $abroad_grad_level_final;
						}

					}
					else if ($field->getId() == "examsAbroad"){
						if(STUDY_ABROAD_NEW_REGISTRATION) {

							$exams_values = array();
							foreach($field->getValues(array('studyAbroad' => true)) as $val) {	
								$exams_values[] = $val['name'];
							}

							$data['fields'][$counter]['values'] = $exams_values; 		
					
						} else {
							$data['fields'][$counter]['values'] = $field->getValues(array('studyAbroad' => true));
						}
					}
					else{
						$values = $field->getValues();
						if($field->isCustom()) {
							if($field->getType() == 'select' || $field->getType() == 'multiple') {
								$valueMap = array();
								foreach($values as $key => $value) {
									$valueMap[] = $key."=".$value;
								}
								$values = implode('|',$valueMap);
							}
							else {
								$values = implode('|',$values);
							}
						}
						$data['fields'][$counter]['values'] = $values;
					}
					$data['fields'][$counter]['type'] =  $field->getType();
					$data['fields'][$counter]['label'] =  $field->getLabel();
					if(is_array($field->getPreSelectedValues()))
						$data['fields'][$counter]['preselected'] =  join('|',$field->getPreSelectedValues());
					else
      					$data['fields'][$counter]['preselected'] =  $field->getPreSelectedValues();
					$counter++;
			}
			
			include_once APPPATH."modules/User/customizedmmp/helpers/HtmlGenerator.php";
			$generator = new ValueGenerator();
			if($type == 'group')
	                	$fieldshtml = $generator->getDataForView($data['fields'],$returnResult,$arg1,'',$myField);
			if($type == 'course')
				$fieldshtml = $generator->getDataForView($data['fields'],$returnResult,'',$arg1,$myField);
			if($arg1 == 'abroadpage')
                                $fieldshtml = $generator->getDataForView($data['fields'],$returnResult,'NA','NA',$myField);
			$tempRuleArr = explode(',', $v->todo);
			$tempRuleArr2 = array();
			foreach($tempRuleArr as $tempRule){
				$tempRuleArr2[] = "'".$tempRule."'";
			}
			
			$customActionArr = join(',',$tempRuleArr2);
			if($customActionArr == "''")
				$customActionArr = '';

                        $tempRuleArr = explode(',', $v->condition);
                        $tempRuleArr2 = array();
                        foreach($tempRuleArr as $tempRule){
                                $tempRuleArr2[] = "'".$tempRule."'";
                        }
                        $customRuleArr = join(',',$tempRuleArr2);
			if($customRuleArr == "''")
				$customRuleArr = '';

                        $counter++;
                }
		if ($arg2 == 'group')
			$a1 = 'gid';
		if ($arg2 == 'course')
                        $a1 = 'cid';
		echo Modules::run('customizedmmp/mmp/customizePage',$formid, $a1, $arg1,$customRuleArr, $customActionArr,$fieldshtml,$data['fields']);
		die;
	}


	public function SubmitCmp(){
		$this->init();
		$beLabels = split(',', $this->input->post('labels'));
		$beDefault = split(',', $this->input->post('defaultValue'));
		$beType = split(',', $this->input->post('typeArr'));
		$beVisible = split(',', $this->input->post('visibleArr'));
		$beValues = explode('#', $this->input->post('valuesArr'));
		$bemandatory = split(',', $this->input->post('mandatoryArr'));
		$beIsCustom = split(',', $this->input->post('isCustomArr'));
		$beRules = split(',', $this->input->post('ruleSet'));
		$toDo = split(',', $this->input->post('toDoSet'));
		$beids = split(',', $this->input->post('ids'));
		$groupId = $this->input->post('gid');
		$courseId = $this->input->post('cid');
		$formId = $this->input->post('formid');
		$pageType = $this->input->post('pagetype');
		
		$fields = Array();
		$data = Array();
		$registrationForm = \registration\builders\RegistrationBuilder::getRegistrationForm('LDB');
		foreach($toDo as $k=>$v){
			$customRule = new \registration\libraries\CustomLogic\Rule;

			$toDoArr = split('#',$v);
			//print_r($toDoArr[0]);
			$activityArr  = explode('|',$toDoArr[0]);
			$labelArr = explode('|',$toDoArr[1]);
			$valArr =  explode('|',$toDoArr[2]);
			foreach($activityArr as $k1=>$v1){
				if($activityArr[$k1] == 'hide'){
					$action1 = new \registration\libraries\CustomLogic\Action(new \registration\libraries\RegistrationFormField($labelArr[$k1]),'visibility','no');
				}
				else{
					$action1 = new \registration\libraries\CustomLogic\Action(new \registration\libraries\RegistrationFormField($labelArr[$k1]),'visibility','yes');
				}
                $customRule->addAction($action1);
                if($valArr[$k1] != 'NA'){
                    $action2 = new \registration\libraries\CustomLogic\Action(new \registration\libraries\RegistrationFormField($labelArr[$k1]),'value',$valArr[$k1]);
                    $customRule->addAction($action2);
                }

			}


			$rulesArr = split('#',$beRules[$k]);
			if($rulesArr[1] != 'NA'){
				$rule1Arr = split('=',$rulesArr[0]);
				$rule2Arr = split('=',$rulesArr[1]);
				$condition1 = new \registration\libraries\CustomLogic\Condition(new \registration\libraries\RegistrationFormField($rule1Arr[0]),$rule1Arr[1]);
				$condition2 = new \registration\libraries\CustomLogic\Condition(new \registration\libraries\RegistrationFormField($rule2Arr[0]),$rule2Arr[1]);
				$customRule->addCondition($condition1);
				$customRule->addCondition($condition2);
				if($rulesArr[2] == 'and')
					$customRule->setLogic("AND");
				else
					$customRule->setLogic("OR");
			}
			else{
				$rule1Arr = split('=',$rulesArr[0]);
				$condition1 = new \registration\libraries\CustomLogic\Condition(new \registration\libraries\RegistrationFormField($rule1Arr[0]),$rule1Arr[1]);
				$customRule->addCondition($condition1);
			}

			$registrationForm->addCustomRule($customRule);
		}

		if(trim($_POST['ruleSet']) == ''){
			$ruleJson ='';
		}
		else
			$ruleJson = $registrationForm->getRuleJSON();
			
				
		for($i = 0; $i<count($beLabels); $i++){
			$fields[$i] = new \registration\libraries\RegistrationFormField;
			
			$fields[$i]->setPreSelectedValues($beDefault[$i]);
			$fields[$i]->setOrder($i);
			if($beVisible[$i] == '1')
				$fields[$i]->setVisible(true);
			else
				$fields[$i]->setVisible(false);
			if($bemandatory[$i] =='1')
				$fields[$i]->setMandatory(true);
			else
				$fields[$i]->setMandatory(false);
			if($beIsCustom[$i] == 'yes')
				$fields[$i]->setCustom(true);
			else
				$fields[$i]->setCustom(false);
				
				
			$fields[$i]->setType($beType[$i]);
			//if($beIsCustom[$i] == 'yes')
			
			$values = explode('|',$beValues[$i]);
            $valueMap = array();
            foreach($values as $value) {
                list($key,$val) = explode('=',$value);
                if($val || $val == '0') {
                    $valueMap[$key] = $val;
                }
                else {
                    $valueMap[] = $value;
                }
            }
			
			$fields[$i]->setValues($valueMap);
			//echo $beValues[$i]."<br />";
			$fields[$i]->setLabel($beLabels[$i]);
		    $fields[$i]->setId($beids[$i]);
            /*
			if($beIsCustom[$i] == 'yes') {
				$fields[$i]->setId('custom_'.strtolower(str_replace(' ','_',$beLabels[$i])));
			}
			else {
				$fields[$i]->setId($beids[$i]);
			}
            */
			
			$registrationForm->addField($fields[$i]);
		}
		
	    $fieldJson = $registrationForm->getFieldJSON();
        $customizedMMPLib = new customizemmp_lib();
		if($pageType == 'abroadpage'){
			$returnResult = $customizedMMPLib->addCustomization($formId, $fieldJson,$ruleJson,0, 'abroadpage',$_POST['ruleSet'], $_POST['toDoSet']);
		}
		elseif($groupId > 0){
		        $returnResult = $customizedMMPLib->addCustomization($formId, $fieldJson,$ruleJson,$groupId, 'group',$_POST['ruleSet'], $_POST['toDoSet']);
		}
		elseif ($courseId > 0){
			$returnResult = $customizedMMPLib->addCustomization($formId, $fieldJson,$ruleJson,$courseId,'course',$_POST['ruleSet'], $_POST['toDoSet']);
		}
		else{
			echo "No course or group chosen";
		}
		echo Modules::run('enterprise/MultipleMarketingPage/marketingPageDetails');
	}

	public function customizePage($arg0, $arg1, $arg2, $rules, $actions,$fieldshtml,$final_fields= array()){
		
        $this->init();
		$data = array();
		$data['headerTabs'] = $this->getHeaderTabs();
		$data['validateuser'] = $this->userStatus;
		$formid = $arg0;
		$customizedMMPLib = new customizemmp_lib();
                $data['rules'] = $rules;
                $data['actions'] = $actions;
                $data['formid'] = $formid;
		$mmpFormId = $formid;
        $pageType = $customizedMMPLib->getPageType($formid);
        if($pageType == 'testpreppage')
    		$customizations = $customizedMMPLib->getCustomizationsForForm($formid,'','testprep');
        else
            $customizations = $customizedMMPLib->getCustomizationsForForm($formid,'','');
		$customizations = json_decode($customizations);
		$customRuleArr = array();
		$customActionArr = array();
		$counter = 0;
		foreach($customizations as $k=>$v){
			$data['editData'][$counter]['foreignid'] = $v->foreignid;
			$data['editData'][$counter]['name'] = $v->name;
			$data['editData'][$counter]['type'] = $v->type;
			$counter++;
		}
		$groups = array();
		$groupCourses = array();
		$courses = "";
		$groupAcronyms = array();
        if($pageType == 'testpreppage'){
            $testPrepCourses = $customizedMMPLib->getTestPrepCoursesForForm($formid);
            $returnResult = array();
            $counter = 0;
            foreach($testPrepCourses as $k=>$v){
                $acronym = \registration\builders\RegistrationBuilder::getCourseGroupForTestPrep($v['course_id']);
                $testPrepGroupDetails = $customizedMMPLib->getTestPrepGroupDetails($acronym);
                $returnResult[$counter]['coursename'] = $v['acronym'];
                $returnResult[$counter]['courseid'] = $v['course_id'];
                $returnResult[$counter]['groupid'] = $testPrepGroupDetails[0]['groupid'];
                $returnResult[$counter]['groupname'] = $testPrepGroupDetails[0]['groupname'];
                $returnResult[$counter]['acronym'] = $testPrepGroupDetails[0]['acronym'];
                $returnResult[$counter]['reach'] = 'national';
                $counter++;
            }
        }
        else{
    		$returnResult = $customizedMMPLib->getCoursesForForm($formid);
        }
		
		$courseUnderCustomization = 0;
		if($arg1 == 'cid') {
			$courseUnderCustomization = $arg2;
		}
		else if(count($returnResult) == 1) {
			$courseUnderCustomization = $returnResult[0]['courseid'];
		}
		
		$data['courses'] = $returnResult;
        $data['numberOfCoursesOnForm'] = count($returnResult);
		foreach($returnResult as $k=>$v){
			$groups[$v['groupid']] = $v['groupname'];
			$groupCourses[$v['groupid']][] = $v['coursename'];
			$groupAcronyms[$v['groupid']] = $v['acronym'];
		}

        foreach($data['editData'] as $key=>$value){
            if($value['type'] == 'group'){
                if(count($groupCourses[$value['foreignid']]) == 0){
                    unset($data['editData'][$key]);
                }
            }
        }

		if($arg1 == 'gid') {
			$courses = $groupCourses[$arg2];
			$courses = join(',',$courses);
			$data['gid'] = $arg2;
			foreach($returnResult as $k=>$v){
				if($v['groupid'] == $arg2)
					$data['reach'] = $v['reach'];
            }
			$groupAcronym = $groupAcronyms[$arg2];
		}
		if($arg1 == 'cid'){
			$data['cid'] = $arg2;
			foreach($returnResult as $k=>$v){
				if($v['courseid'] == $arg2) {
					$data['reach'] = $v['reach'];
					$groupAcronym = $v['acronym'];
				}
			}
		}
		if(!isset($arg1) || $arg1 == ''){
			
		}
		$data['groups'] = $groups;
		$data['coursesNames'] = $courses;
		$pageType = $customizedMMPLib->getPageType($formid);
		if(!isset($fieldshtml)){
			if($pageType == 'abroadpage') {
				
                    if(STUDY_ABROAD_NEW_REGISTRATION) {
						$form = \registration\builders\RegistrationBuilder::getRegistrationForm('MMP',array('courseGroup' => 'studyAbroadRevampedMMP','mmpFormId' => $mmpFormId));
					} else {
						$form = \registration\builders\RegistrationBuilder::getRegistrationForm('MMP',array('courseGroup' => 'studyAbroad', 'mmpFormId' => $mmpFormId));
					}
					
			} else {
								
				if($courseUnderCustomization>0) {
					$form = \registration\builders\RegistrationBuilder::getRegistrationForm('MMP',array('desiredCourseId' => $courseUnderCustomization, 'mmpFormId' => $mmpFormId));
				}else if($arg1 == 'gid' && $arg2>0) {
					$form = \registration\builders\RegistrationBuilder::getRegistrationForm('MMP',array('courseGroupId' => $arg2, 'mmpFormId' => $mmpFormId));
				} else {
					$form = \registration\builders\RegistrationBuilder::getRegistrationForm('MMP',array('courseGroup' => $groupAcronym, 'mmpFormId' => $mmpFormId)); 	
				}
								
			}
				
			$fields = $form->getFields();
			$myField = '';
			$counter = 0;
			foreach($fields as $field){
				//echo $field->getId()."<br/>";
				if((in_array($field->getId(),array("desiredCourse","currentSchool", "currentClass", "tenthBoard", "tenthmarks", "graduationStream", "graduationMarks", "workExperience")) && $pageType == 'abroadpage') || (in_array($field->getId(),array("preferredStudyLocality","fieldOfInterest")) && $pageType != 'abroadpage')){					
					continue;
				}
								
				$data['fields'][$counter]['id'] = $field->getId();
				$data['fields'][$counter]['isVisible'] = $field->isVisible();
				$data['fields'][$counter]['isMandatory'] = $field->isMandatory();
				$data['fields'][$counter]['isCustom'] = $field->isCustom();
				if($field->getId() == 'specialization') {
					$specialization_values = $field->getValues(array('desiredCourse'=>$data['cid']));
					if(is_array($specialization_values) && count($specialization_values)>0) {
							$data['fields'][$counter]['values'] = $field->getValues(array('desiredCourse'=>$data['cid']));
					} else {
							continue;
					}
					
				} else if($field->getId() == 'residenceLocality') {					
					$data['fields'][$counter]['values'] = $field->getValues(array('cities'  => 'true'));
					//_P($data['fields'][$counter]['values']);
				}else if($field->getId() == 'preferredStudyLocality'){
					$data['fields'][$counter]['values'] = $field->getValues(array('cities'  => 'true'));
					$myField = $field;
				} else if($field->getId() == 'residenceCity') {
					    if($pageType == 'abroadpage') {
							$data['fields'][$counter]['values'] = $field->getValues();
						} else {
							$data['fields'][$counter]['values'] = $field->getValues(array('isNational'=>true));
						}
						//_P($data['fields'][$counter]['values']);
                } else if ($field->getId() == "exams"){
					if($courseUnderCustomization) {
						$examValues = $field->getValues(array('desiredCourse' => $courseUnderCustomization));
						if(count($examValues)) {
							foreach($examValues['featured'] as $examKey => $examVal) {
								$data['fields'][$counter]['values'][$examKey] = addslashes($examVal->getDisplayNameForUser());
							}
							foreach($examValues['others'] as $examKey => $examVal) {
								$data['fields'][$counter]['values'][$examKey] = addslashes($examVal->getDisplayNameForUser());
							}
						}
					}
                		} else if($field->getId() == 'fieldOfInterest') {
					if(STUDY_ABROAD_NEW_REGISTRATION && $pageType == 'abroadpage') {
						
						$abroad_cat_values = $field->getValues(array('twoStep'=>true,'registerationDomain'=>'studyAbroad'));
						//_P($abroad_cat_values);
						$final_value = array();
						foreach($abroad_cat_values as $val) {
							$final_value[$val['id']] = $val['name']; 
						}
						$data['fields'][$counter]['values'] = $final_value;
					}
				} else if($field->getId() == 'desiredGraduationLevel') {

					if(STUDY_ABROAD_NEW_REGISTRATION && $pageType == 'abroadpage') {
						$abroad_grad_level = $field->getValues(array('twoStep'=>true,'registerationDomain'=>'studyAbroad'));
						$abroad_grad_level_final  = array();
                                                foreach($abroad_grad_level as $level) {
							if($level['CourseName'] !='Certificate/Diploma') {
								$abroad_grad_level_final[] = $level['CourseName'];
							}
						}
						$data['fields'][$counter]['values'] = $abroad_grad_level_final;
					}

				}
				else if ($field->getId() == "examsAbroad"){					
					if(STUDY_ABROAD_NEW_REGISTRATION) {

						$exams_values = array();
						foreach($field->getValues(array('studyAbroad' => true)) as $val) {	
							$exams_values[] = $val['name'];
						}

						$data['fields'][$counter]['values'] = $exams_values; 		
					
					} else {

						$data['fields'][$counter]['values'] = $field->getValues(array('studyAbroad' => true));
					}
                                         
                		} else if($field->getId() == 'abroadSpecialization') {
					//echo $field->getLabel();
					//_P($field->getValues());
					$data['fields'][$counter]['values'] = $field->getValues();
				} else if($field->getId() == 'abroadDesiredCourse') {
					//echo $field->getLabel();
					$abroadDesiredCourse = array();
					//$field->setType('select');
					foreach($field->getValues() as $value) {
						$abroadDesiredCourse[$value['SpecializationId']] = $value['CourseName'];
					}
					$data['fields'][$counter]['values'] = $abroadDesiredCourse;				
				} else if($field->getId() == "desiredCourse") {
					 //echo "majqwaaaaaaaaaaaa";
					//_P($field->getValues(array('mmpFormId'=>$mmpFormId)));
					$data['fields'][$counter]['values'] = $field->getValues(array('mmpFormId'=>$mmpFormId));
				} else{
					$data['fields'][$counter]['values'] = $field->getValues();
				} 					
				
				$data['fields'][$counter]['type'] =  $field->getType();
				$data['fields'][$counter]['label'] =  $field->getLabel();
				$data['fields'][$counter]['preselected'] =  is_array($field->getPreSelectedValues()) ? implode('|',$field->getPreSelectedValues()) : $field->getPreSelectedValues();
				$counter++;
			}
			include_once APPPATH."modules/User/customizedmmp/helpers/HtmlGenerator.php";
			$generator = new ValueGenerator();
			$data['fieldshtml'] = $generator->getDataForView($data['fields'],$returnResult,$data['gid'],$data['cid'],$myField,$pageType);
		} else {
				$data['fieldshtml'] = $fieldshtml;
				$data['fields'] = $final_fields;
				
			}

		$data['pagetype'] = $pageType;
	//	print_r($fieldshtml);die;
		$this->load->view("cmpcustomized.php", $data);
	}

	public function saveNationalFormCustomization() {

		$fields = array('stream', 'subStreamSpecMap', 'baseCourses', 'mode');

		$customizationFields = array();
		foreach($fields as $field) {
			$newfield = $field;
			if($field == 'subStreamSpecMap') {
				$newfield = 'subStreamSpec';
			} else if($field == 'mode') {
				$newfield = 'educationType';
			}
			$fieldValue = '';
			$fieldValue = $this->input->post($field);
			$fieldValue = json_decode($fieldValue, true);

			if((!empty($fieldValue)) && ($fieldValue != '-1')) {
				$customizationFields[$newfield]['value'] = $fieldValue;
			}

			$customizationFields[$newfield]['hidden'] = ($this->input->post($field.'_hide') == 'on') ? '1':'0';
			$customizationFields[$newfield]['disabled'] = ($this->input->post($field.'_disable') == 'on') ? '1':'0';
		}
		
		$jsoncustomizationFields = '';
		if(!empty($customizationFields)) {
			$jsoncustomizationFields = json_encode($customizationFields);
		}
		$mmpPageId = $this->input->post('mmpPageId');
		$cmp_model = $this->load->model('customizedmmp/customizemmp_model');
		$cmp_model->saveCustomization($mmpPageId, $jsoncustomizationFields);

		header("Location: ".SHIKSHA_HOME."/enterprise/MultipleMarketingPage/marketingPageDetails");
		exit;

	}

}

?>
