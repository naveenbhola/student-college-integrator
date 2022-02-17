<?php
/**
 * Purpose : Controller for all CMS related functionalities of Exam Pages(National)
 * Author 	  : Romil Goel
 * Creation Date : 19-09-2014
 *
 */
class ExamPagesCMS extends MX_Controller
{
    private function _init()
    {
        $this->validateuser = $this->checkUserValidation();
        $this->cmsUserInfo = $this->cmsUserValidation();
        $this->load->config("examPages/examPageConfig");
        //$this->categoryOfEnabledExamPages = $this->config->item('categoryOfEnabledExamPages');
        
        $this->examPostingLib   = $this->load->library('examPages/ExamPostingLib');
        $this->examPageRequest  = $this->load->library('examPages/examPageRequest');

        $this->userModel    = $this->load->model('user/usermodel');

        $this->examCache = $this->load->library('examPages/cache/ExamCache');
        
        if($this->cmsUserInfo['usergroup']!='cms'){
            header("location:/enterprise/Enterprise/disallowedAccess");
            exit();
        }
    }
    
    private function _initExamPageForm() {
        // prepare the header components
        $headerComponents = array(
                        'css' => array('headerCms', 'footer','common_new', 'exampages_cms'),
                        'js' => array('user','common','header','studyAbroadCMS','CalendarPopup','domesticExamContent'),
                        'jsFooter' =>'',
                        'title' => "",
                        'product' => 'CMSExam',
                        'displayname' => (isset($this->validateuser[0]['displayname']) ? $this->validateuser[0]['displayname'] : ""),
                        'isOldTinyMceNotRequired' => 1
		);

        // tab to be selected
        $this->cmsUserInfo['prodId'] =   EXAM_PAGES_TAB_ID;
        
        // render the view
        echo $this->load->view('enterprise/headerCMS', $headerComponents,true);
        echo $this->load->view('common/calendardiv');
        echo $this->load->view('enterprise/cmsTabs', $this->cmsUserInfo, true);
    }
    
    /** 
    * Purpose : Method to open add CMS Exam Page form 
    * Params  : $examId . $groupId optional
    */
    function addEditExamContent($examId,$groupId)
    {
        // initialize
        $this->_init();
        $this->_initExamPageForm();
        $displayData['activePage'] = 'examPageList';
        $displayData['actionType'] = "add";
        //check examId,groupId mapping exist
        if(!empty($examId) || !empty($groupId))
        {
            $cmsmodel = $this->load->model('examcmsmodel');
            $groupName = $cmsmodel->getGroupNameByExamGroup($examId,$groupId);    
            if(empty($groupName))
            {
                _p('No mapping between examId and groupId');
                echo $this->load->view('common/footerNew', true);
                return;
            }
            $displayData['actionType'] = "edit";
            $displayData['examId'] = $examId;
            $displayData['groupId'] = $groupId;
            $displayData['groupName'] = $groupName;
        }
        

        $displayData['formName']   = "addExamPage";
        //$displayData['categories'] = array_keys($this->categoryOfEnabledExamPages); // change for LF-2688
        $this->load->library("examPages/ExamMainLib");
        $this->ExamMainLib = new ExamMainLib();
        $displayData['examList'] = $this->ExamMainLib->getExamsList($filter);
        echo $this->load->view('examPages/cms/addEditExamPage', $displayData);
    }

    function manageExamPageSeo($examId){
        $this->_init();
        $this->_initExamPageForm();
        $displayData['activePage'] = 'exam_seo';
        $displayData['actionType'] = "add";

        if(!empty($examId)){
            $displayData['actionType'] = "edit";
        }
        
        $displayData['formName']   = "addEditExamPageSeo";
        $this->load->library("examPages/ExamMainLib");
        $this->ExamMainLib = new ExamMainLib();
        $displayData['examList'] = $this->ExamMainLib->getExamsList($filter);
        $displayData['sectionList_'] = $this->config->item('sectionNamesMapping');

        echo $this->load->view('examPages/cms/addEditExamPageSeo', $displayData);
    }

    function saveExamPageSeoFormData(){
        $this->_init();
        $postData['examId'] = $this->input->post('examId');
        $postData['sectionName'] = $this->input->post('sectionName');
        $postData['metaTitle'] = $this->input->post('metaTitle');
        $postData['metaDescription'] = $this->input->post('metaDescription');
        $postData['h1Tag'] = $this->input->post('h1Tag');

        // insert into html cache purging queue
        $arr = array("cache_type" => "htmlpage", "entity_type" => "exampage", "entity_id" => $postData['examId'], "cache_key_identifier" => "");
        $shikshamodel = $this->load->model("common/shikshamodel");
        $shikshamodel->insertCachePurgingQueue($arr);

        $cmsmodel = $this->load->model('examcmsmodel');
        $cmsmodel->saveExamPageSeoFormData($postData);
        echo json_encode(array('data' => 'Data Added successfully','status' => 'success'));
    }

    function getExamPageSeo(){
        $this->_init();

        $examId = !empty($_POST['examId']) ? $this->input->post('examId') : '';
        $sectionName = !empty($_POST['sectionName']) ? $this->input->post('sectionName') : '';

        if(empty($examId) || empty($sectionName))
        {
            echo json_encode(array('data' => array('error' => array('msg' => 'Unable to Fetch content'))));
            return;
        }

        $examLib = $this->load->library('examPages/ExamMainLib');
        $examData = $examLib->getExamDataByExamIds(array($examId));

        $seoData = $this->examPostingLib->getExamPageSeo($examId, $sectionName);
        $seoData['url'] = $examData[$examId]['url'];

        echo json_encode(array('data' => $seoData));
    }
    
    function editExamPageForm($examPageId) {
        // initialize
        $this->_init();
        $this->_initExamPageForm();
        
        $displayData['actionType'] = 'edit';
        
        //get data
        $examPageData = $this->examPostingLib->getExamPageData($examPageId);
        if(empty($examPageData['basic'])){
		    //show_404();
            _p('<h1>      Invalid Page Id</h1>');
            echo $this->load->view('common/footerNew', true);
            return;
		}
        $displayData['formName']   = "addExamPage";
        $displayData['examPageData'] = $examPageData;
        //$displayData['categories'] = array_keys($this->categoryOfEnabledExamPages); // change for LF-2688
        //$displayData['otherExams'] = $this->getExamListByCategory($examPageData['basic']['category_name']);
        $this->load->library("examPages/ExamMainLib");
        $this->ExamMainLib = new ExamMainLib();
        $filter['exampageId'] = 0;
        $displayData['examList'] = $this->ExamMainLib->getExamsList($filter);

        $displayData['beforeEditExamName'] = $this->examPostingLib->getExamNameById($examPageId,'live');
        
        //get last modified by -> user details
        $userData = $this->userModel->getUserById($examPageData['basic']['last_modified_by']);
		$displayData['examPageData']['basic']['last_modified_by_name'] = $userData->getDisplayName();
        $userData = $this->userModel->getUserById($examPageData['basic']['created_by']);
        $displayData['examPageData']['basic']['created_by_name'] = $userData->getDisplayName();
        $displayData['metaDetails'] = $this->examPostingLib->getMetaDetailsFromDB($examPageId);
        if(empty($displayData['metaDetails'])){
            $displayData['metaDetails'] = $this->examPostingLib->getMetaDetailsDefault($examPageId);
        }        
        $this->load->view('examPages/cms/addEditExamPage', $displayData);
    }
    
    function getExamListByCategory($categoryName ='') {
        $returnExams = true;
        if(empty($categoryName)){
            $returnExams = false;
            $categoryName =  $this->input->post('categoryName',true);
        }
        $this->listingCache = $this->load->library('listing/cache/ListingCache');
        $exam_list = $this->listingCache->getExamsList();
		if( TRUE || empty($exam_list)) {   // Refresh cache of exam list in case of CMS user post any new exam page.
            $this->categoryClient = $this->load->library('category_list_client');
            $exam_list = $this->categoryClient->getTestPrepCoursesList(1);
			$this->listingCache->storeExamsList($exam_list);
		}
        $examListByCategory = $this->_prepareExamList($exam_list);
        $this->examPostingLib = $this->load->library('examPages/ExamPostingLib');
        $ignoreExamList = $this->examPostingLib->getAlreadyAddedExams();

        $redirectExams = $this->listingCache->getRedirectExamsList();
        if(empty($redirectExams)){
            $redirectExams = $this->examPostingLib->getRedirectExams();
        }
        $ExamListOptionHtml = "";$exams = array();
        foreach($examListByCategory[$categoryName] as $exam)
        {
            if($exam == 'No Exam Required' || in_array($exam, $ignoreExamList[$categoryName]) || !empty($redirectExams[strtolower($exam)])) {
                continue;
            }
            $exams[] = $exam;
        	$ExamListOptionHtml .= '<option value="'.$exam.'">'.$exam.'</option>';
        }
        if(empty($ExamListOptionHtml)) {
        	$ExamListOptionHtml = "NO EXAM FOUND";
        }
        if($returnExams){
            return $exams;
        }
        else{
            echo  $ExamListOptionHtml;            
        }
    }
    
    private function _prepareExamList($exam_list = array()) {
		$final_exam_list = array();
		if(count($exam_list) >0) {
			foreach ($exam_list as $list) {
				foreach ($list as $list_child) {
					$final_exam_list[$list_child['acronym']][] = $list_child['child']['acronym'];
				}
			}
		}
        
        //Entry for GMAT in MBA
		if(!empty($final_exam_list['MBA'])){
			$final_exam_list['MBA'][] = "GMAT";
		}

	      /** Engineering Exams deleted or having wrong mapping ****/
		if(!empty($final_exam_list['Engineering'])){
			$final_exam_list['Engineering'][] = "WBJEE";
			$final_exam_list['Engineering'][] = "RPET";
			$final_exam_list['Engineering'][] = "KIITEE";
	       }
        
		return $final_exam_list;
	}

    /**
     * save exam form data
     * author: aman varshney
     * @return 
     */
    function saveExamPageFormData(){
        // initialize
        $this->_init();

        $data           = $this->postDataForExampage();
        $data['userId'] = $this->cmsUserInfo['userid'];

        // Check lock cms editor info
        $ExamPageCache = $this->load->library('examPages/cache/ExamPageCache');
        $result = $ExamPageCache->getCMSUserLockingInfo($data['examId']);
        if(!empty($result) && !empty($result['userId']) && $result['userId'] != $data['userId']){
            $response = array('data' => array('err' => array('msg' => 'This Exam has been locked by '.$result['userName'])));
            echo json_encode($response);exit;
        }

        // validating exam info field is empty
        foreach($data['homepage'] as $val){
            if($val['label']=='Summary' && $val['type'] =='Fixed'){
                $checkValue = strip_tags($val['wikiData']);
                if(empty($checkValue))
                {
                    show_404();    
                }
            }
        }   

        // validating exampageId is numeric and category and examname is empty
        if(!is_numeric($data['examPageId']) && empty($data['examName']) && empty($data['userComments'])) {
            show_404();
        }

        // checking the action case
        if($data['examPageId'] == 0){
            $data['action']    = 'add';
            $data["created"]   = date('Y-m-d H:i:s');
            $data["updated"]   = date('Y-m-d H:i:s');
            $data["createdBy"] = $data["userId"];
            $data["updatedBy"] = $data["userId"];
        }else{
            $data['action']    = 'edit';
            $data["created"]   = $data["creationDate"]; //old date
            $data["updated"]   = date('Y-m-d H:i:s');
            $data["updatedBy"] = $data["userId"];
            $data['createdBy'] = $data['createdBy'];
        }
        
        $examPageId = $this->examPostingLib->addEditExamData($data);

        //below lines are used for inserting exampageids into rabbitmq to generate AMP html
        if($data['status'] == 'live')
        {
            $wikiSections = array('answerkey','slotbooking','importantdates','summary','eligibility','process','examcenters','examanalysis','studentreaction','contactinfo','pattern','syllabus','admitcard','cutoff','counselling','applicationform','samplepaperswiki','results','preptips');
            $cmsmodel = $this->load->model('examcmsmodel');
            $tempexamPageId = array();
            $multipleGroupIds = array();
            foreach ($data['saveToMultiple'] as $secKey => $groupValue) {
                if(in_array($secKey, $wikiSections))
                {
                    foreach ($groupValue as $gkey => $groupId) {
                        if(!empty($groupId))
                        {
                            $tempexamPageId[] = $cmsmodel->getPageIdBasedOnExamGroupId($groupId);
                            $multipleGroupIds[] = $groupId;
                        }
                    }
                }
            }

            $this->load->builder('ExamBuilder','examPages');
            $examBuilder          = new ExamBuilder();
            $examRepository = $examBuilder->getExamRepository();


            $tempexamPageId = array_unique($tempexamPageId);
            modules::run('common/GlobalShiksha/insertIntoAmpRabbitMQueue',$examPageId,$tempexamPageId, 'exampage');
            $this->examCache->deleteCache($data['groupId'],ExamContentKey);
            $examRepository->disableCaching();
            $examRepository->findContent($data['groupId'],'all',false,false,true);

            $this->examCache->deleteCache($data['examId'],ExamBaiscKey);
            $examRepository->disableCaching();
            $examRepository->findMultiple(array($data['examId']),false,true);


            // insert into html cache purging queue
            $arr = array("cache_type" => "htmlpage", "entity_type" => "exampage", "entity_id" => $data['examId'], "cache_key_identifier" => "");
            $shikshamodel = $this->load->model("common/shikshamodel");
            $shikshamodel->insertCachePurgingQueue($arr);

            foreach ($multipleGroupIds as $grkey => $grvalue) {
                if(!empty($grvalue))
                {
                    $this->examCache->deleteCache($grvalue,ExamContentKey);    
                    $examRepository->disableCaching();
                    $examRepository->findContent($grvalue,'all',false,false,true);
                }
            }   
        }
        
        $this->unlockEditorInfo($data['examId']);

        if($examPageId > 0)
        {
            $response = array('data' => array('msg' => 'Exam Content Add Successfully'));
        }
        else
        {
            $response = array('data' => array('err' => array('msg' => 'Some problem has been occured')));
        }
        echo json_encode($response);
        exit;
    }

    public function saveExamPageContentData(){
        $this->_init();
        $data           = $this->postDataForExampage();
        $data['userId'] = $this->cmsUserInfo['userid'];

        // Check lock cms editor info
        $ExamPageCache = $this->load->library('examPages/cache/ExamPageCache');
        $result = $ExamPageCache->getCMSUserLockingInfo($data['examId']);
        if(!empty($result) && !empty($result['userId']) && $result['userId'] != $data['userId']){
            $response = array('data' => array('err' => array('msg' => 'This Exam has been locked by '.$result['userName'])));
            echo json_encode($response);exit;
        }

        // validating exampageId is numeric and category and examname is empty
        if(!is_numeric($data['examPageId']) && empty($data['examName'])) {
            show_404();
        }

        // checking the action case
        if($data['examPageId'] == 0){
            $data['action']    = 'add';
            $data["created"]   = date('Y-m-d H:i:s');
            $data["updated"]   = date('Y-m-d H:i:s');
            $data["createdBy"] = $data["userId"];
            $data["updatedBy"] = $data["userId"];
        }else{
            $data['action']    = 'edit';
            $data["created"]   = $data["creationDate"]; //old date
            $data["updated"]   = date('Y-m-d H:i:s');
            $data["updatedBy"] = $data["userId"];
            $data['createdBy'] = $data['createdBy'];
        }

        $result = $this->examPostingLib->saveExamPageContentData($data);

        /**$this->unlockEditorInfo($data['examId']);*/
        echo $result;

    }




    /**
     * get post data array
     * author: aman varshney
     * @return array
     */
    function postDataForExampage(){
        $formFieldInfo = json_decode($this->input->post("data",true),true);
        return $formFieldInfo;
    }
    
    /**
     * @Desc: To View Exam Page Order Interface for backend
     * @author: Ankit Garg
     * @created: 2015-02-18
     */
    function orderExamPages() {
        // initialize
        $this->_init();
        $this->_initExamPageForm();
        
        $displayData['formName']   = "SortExams";

        $this->load->builder('listingBase/ListingBaseBuilder');
        $ListingBaseBuilder = new ListingBaseBuilder();
        $hierarchyRepo = $ListingBaseBuilder->getHierarchyRepository();
        $displayData['streams'] = $hierarchyRepo->getAllStreams();
        $this->basecourseRepo = $ListingBaseBuilder->getBaseCourseRepository();
        $displayData['popularCourses'] =     $this->basecourseRepo->getAllPopularCourses('array');
        $displayData['activePage'] = 'examList';
        $this->load->view('examPages/cms/orderExamPages', $displayData);
    }
    
    /**
     * @Desc: To get Exam Names for a specific category
     * @author: Ankit Garg
     * @created: 2015-02-18
     */
    function getExamNamesByCategory() {
        $categoryName =  $this->input->post('categoryName',true);
        $this->examPostingLib = $this->load->library('examPages/ExamPostingLib');
        $displayData = array();
        $displayData['examList'] = $this->examPostingLib->getExamsByCategoryName($categoryName);
        $this->load->view('examPages/cms/examNamesSelectBox', $displayData);
    }

    function getExamListByHierarachy(){
        if($this->input->is_ajax_request()){
            $courseId = $this->input->post('courseId',true)?$this->input->post('courseId',true):0;
            $streamId = $this->input->post('streamId',true)?$this->input->post('streamId',true):0;
            $subStreamId = $this->input->post('subStreamId',true)?$this->input->post('subStreamId',true):0;
            //_p($courseId);_p($streamId);_p($subStreamId);die;
            if($courseId > 0 && $streamId > 0){
                echo "Please select either hierarchy or popular course.";
                exit(0);
            }

            if($courseId == 0 && $streamId == 0){
                echo "Please select a hierarchy or popular course.";
                exit(0);
            }
            $displayData = array();
            $this->load->builder('ListingBaseBuilder', 'listingBase');
            $listingBaseBuilder   = new ListingBaseBuilder();
            $this->examPostingLib = $this->load->library('examPages/ExamPostingLib');

            if($courseId > 0){
                $hierarchyIds = array($courseId);
                $entityType = 'course';
            }else{
                $hierarchyReco     = $listingBaseBuilder->getHierarchyRepository();
                $hierarchyIds = $hierarchyReco->getHierarchyIdByBaseEntities($streamId, $subStreamId, '', 'array');
                $entityType = '';
            }

            if(is_array($hierarchyIds) && count($hierarchyIds) > 0){
                $result = $this->examPostingLib->getExamIdsByHierarchy($hierarchyIds,$entityType);
                if($result && count($result)){
                    $examIds = array();
                    foreach ($result as $key => $value) {
                        $examIds[] = $value['examId'];
                    }
                    // get exam details from exampage_master
                    $result = $this->examPostingLib->getExamNameByExamId($examIds);
                    if($result && count($result)){
                        foreach ($result as $key => $value) {
                            $examDetails[$value['id']] = array(
                                'exam_name' => $value['name'],
                                'is_featured' => false,
                                'exam_order' => -1
                                );
                        }
                        $examIds = array_keys($examDetails);
                        $result = $this->examPostingLib->getExamOrderForInputHierarchy($examIds,$courseId,$streamId,$subStreamId);
                        if($result){
                            foreach ($result as $key => $value) {
                                if($examDetails[$value['examId']]){
                                    $examDetails[$value['examId']]['exam_order'] = $value['exam_order'];
                                    $examDetails[$value['examId']]['is_featured'] = $value['is_featured'];
                                }
                            }
                            uasort($examDetails, function($a, $b){
                                if($a["exam_order"] == -1 || $b["exam_order"] == -1){
                                    return $a["exam_order"]< $b["exam_order"];
                                }else{
                                    return $a["exam_order"]> $b["exam_order"];
                                }
                            });
                        }
                        $displayData['examList'] = $examDetails;
                    }
                }
            }
            $this->load->view('examPages/cms/examNamesSelectBox', $displayData);
        }
    }

    function getSubStreamListByStream(){
        $subStreams = array();
        if($this->input->is_ajax_request()){
            $streamId = $this->input->post('streamId',true);        
            if($streamId != '' && $streamId > 0){
                $this->load->builder('listingBase/ListingBaseBuilder');
                $listingBase = new ListingBaseBuilder();
                $hierarchyRepo = $listingBase->getHierarchyRepository();
                $result = $hierarchyRepo->getSubstreamSpecializationByStreamId($streamId, 1);
                if($result){
                    $result = $result[$streamId];
                    if(is_array($result['substreams']) && count($result['substreams']) >0  ){
                        foreach ($result['substreams'] as $key => $value) {
                            $subStreams[$value['id']] = $value['name'];
                        }
                    }
                }
            }
        }
        echo json_encode($subStreams);
    }
    
    /**
     * @Desc: To save Exam Page sort order in to the DB
     * @author: Ankit Garg
     * @created: 2015-02-18
     */
    function saveExamPageSortOrder() {
        if($this->input->is_ajax_request()){
            $this->examPostingLib = $this->load->library('examPages/ExamPostingLib');
            $response = $this->examPostingLib->updateExamSortOrder($this->input->post('examData',true));
            $this->load->library('cacheLib');
            $this->cacheLib = new cacheLib();
            if($response) {
                $this->cacheLib->clearCacheForKey('featuredExams_GNB');
                $ExamPageLib = $this->load->library('examPages/ExamPageLib');
                //refreshing cache
                if($ExamPageLib->updateHierarchiesWithExamNamesCache()) {
                    echo "1";
                }
                else {
                    echo "Data saved but unable to refresh cache";
                }
            }
            else {
                echo "Unable to save data";
            }
        }
    }

    /**
        below function is used for upload application form from exam content cms
    */
    function uploadApplicationForm()
    {
        $response['data'] = array('error' => array('msg' => 'Unable to upload file due to incorrect data'));
        if($_FILES['uploads']) {
            $response = array();
            error_log('data'.print_r($_FILES['uploads'],true));

            $response = $this->prepareUploadData($_FILES['uploads']);

            error_log('data'.print_r($response,true));

            $finalResponse = $response;

            if(!is_array($response) && $response != ""){
                $finalResponse = array();
                $finalResponse['file_url'] = addingDomainNameToUrl(array('url' => $response,'domainName' => MEDIA_SERVER));
                $finalResponse['file_relative_url'] = $response;
                $finalResponse['file_name'] = $_FILES['uploads']['name'][0];
                $finalResponse['file_size'] = $_FILES['uploads']['size'][0];
            }
            // if(!is_array($response)) {
                $response = array('data' => $finalResponse);
            // }
        }
        echo json_encode($response);
    }

    function uploadSamplePapers(){
        $this->load->config('examPages/examPageConfig');
        $this->uploadErrorCodes = $this->config->item('errorCodes');
        $this->errorCodesMsg = $this->config->item('errorCodesMsg');
        $response = array();
        $errors = array();
        if($_FILES['uploads']) {
            $uploadArrData = $this->_verifyUploadData($_FILES['uploads'], $errors);
            if(!empty($uploadArrData)){
                $this->_initUploadClient();
                $upload_array = $this->UploadClient->uploadFile($appId, 'pdf', array('exam_application' => $uploadArrData), array(), "-1", "exam", 'exam_application');
                if($upload_array['status'] == 1){
                    unset($upload_array['status']);
                    unset($upload_array['max']);
                    $finalResponse = array();
                    foreach ($upload_array as $key => $value) {
                        if(!is_array($value['imageurl']) && $value['imageurl'] != ''){
                            $finalResponse[$key]['file_relative_url'] = $value['imageurl'];
                            $finalResponse[$key]['file_url']          = addingDomainNameToUrl(array('url' => $value['imageurl'],'domainName' => MEDIA_SERVER));
                            $finalResponse[$key]['file_name'] = $value['filename'];
                        }
                    }
                    
                    $response = array('data' => $finalResponse);
                }else{
                    $response['genericErrorMsg'] = 'Unable to upload files due to incorrect data.';
                }
            }else{
                if($errors['isOverallSizeLimitReached'] == true){
                    $response['genericErrorMsg'] = 'Can not upload more than 100 MB at once.';
                }else{
                    $response['genericErrorMsg'] = 'Unable to upload files. Files are invalid.';
                }
            }
            unset($errors['isOverallSizeLimitReached']);
            if(count($errors) > 0){
                $response['error'] = $errors;
                $response['errorCodeMsgs'] = $this->errorCodesMsg;
                $response['errorMsg'] = 'Unable to upload some files due to incorrect data.';
            }
            echo json_encode($response);
        }
    }

    /*private function _verifyFileTypes($typeArr, &$errors){
        $allowedTypes = array('application/pdf', 'application/msword');
        foreach ($typeArr as $key => $value) {
            if(!in_array($value, $allowedTypes)){
                $errors['type'][$key] = 'UPLOAD_ERR_UNSUPPORTED_FILE_FORMAT';
            }
        }
    }*/
    private function _verifyFileNames($nameArr, &$errors){
        foreach ($nameArr as $key => $value) {
            if(empty($value)){
                $errors['name'][$key] = array('UPLOAD_ERR_NO_FILE_NAME', $value);
            }
        }
    }
    private function _verifyFileExtensions($nameArr, &$errors){
        $allowedFiles = array('pdf', 'doc', 'docx');
        foreach ($nameArr as $key => $value) {
            $nameParts = explode('.', $value);
            $partsCount = count($nameParts);
            if(!in_array($nameParts[$partsCount-1], $allowedFiles)){
                $errors['name'][$key] = array('UPLOAD_ERR_UNSUPPORTED_FILE', $value);
            }
        }
    }
    private function _verifyTmpLocations($nameArr, $tmpArr, &$errors){
        foreach ($tmpArr as $key => $value) {
            if(empty($value)){
                $errors['tmp_name'][$key] = array('UPLOAD_ERR_TMP_FAILED', $nameArr[$key]);
            }
        }
    }
    private function _verifyErrorCodes($nameArr, $errCodeArr, &$errors){
        foreach ($errCodeArr as $key => $value) {
            if($value != 0){
                $errors['error'][$key] = array($this->uploadErrorCodes[$value], $nameArr[$key]);
            }
        }
    }
    private function _verifyFileSizes($nameArr, $sizeArr, &$errors){
        $maxSize = 25 * 1024 * 1024;
        $sizeSum = 0;
        foreach ($sizeArr as $key => $value) {
            if($value > $maxSize){
                $sizeSum += $value;
                $errors['size'][$key] = array('UPLOAD_ERR_MAX_SIZE', $nameArr[$key]);
            }
        }
        $errors['isOverallSizeLimitReached'] = $sizeSum > (100 * 1024 * 1024);
    }

    private function _verifyUploadData($uploadArrData, &$errors){
        if(!empty($uploadArrData['tmp_name']) && count($uploadArrData['tmp_name']) > 0){
            $this->_verifyFileNames($uploadArrData['name'], $errors);
            //$this->_verifyFileTypes($uploadArrData['type'], $errors);
            $this->_verifyFileExtensions($uploadArrData['name'], $errors);
            $this->_verifyTmpLocations($uploadArrData['name'], $uploadArrData['tmp_name'], $errors);
            $this->_verifyErrorCodes($uploadArrData['name'], $uploadArrData['error'], $errors);
            $this->_verifyFileSizes($uploadArrData['name'], $uploadArrData['size'], $errors);
            if(!empty($errors)){
                foreach ($errors as $type => $errorSet) {
                    foreach ($errorSet as $key => $errorValue) {
                        unset($uploadArrData['name'][$key]);
                        unset($uploadArrData['type'][$key]);
                        unset($uploadArrData['tmp_name'][$key]);
                        unset($uploadArrData['error'][$key]);
                        unset($uploadArrData['size'][$key]);
                    }
                }
            }
            if($errors['isOverallSizeLimitReached'] == true){
                return array();
            }
            if(empty($uploadArrData['tmp_name'])){
                return array();
            }
            return $uploadArrData;
        }
    }

    /*below funciton is used for uploading files from exam content*/
    function prepareUploadData($uploadArrData)
    {

        // check if files has been uploaded
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
            if(!in_array($type, array('pdf','doc'))) {
                $return_response_array['error']['msg'] = "Only document of type .pdf and .doc allowed";
                return $return_response_array;
            }
            //_p($uploadArrData); die;
            // all well, upload now
            $upload_array = $this->UploadClient->uploadFile($appId,'pdf',array('exam_application' => $uploadArrData),array(),"-1","exam",'exam_application');
            // var_dump($upload_array); 
            // check the response from upload library
            if(is_array($upload_array) && $upload_array['status'] == 1) {
                $return_response_array = $upload_array[0]['imageurl'];
            }
            else {
                if($upload_array == 'Size limit of 25 Mb exceeded') {
                    $upload_array = "Please upload a file less than 25 MB in size"; 
                }
                $return_response_array['error']['msg'] = $upload_array;
            }
            return $return_response_array;
        }
        else {
            return "";
        }
    
    }
    private function _initUploadClient() {
        if(!$this->UploadClient) {
            $this->load->library('upload_client');
            $this->UploadClient = new Upload_client();
        }
        // return $this->UploadClient;
    }

    /*below function is used for get exam content based on groupId and examId combination*/
    function getExamContentBasedOnGroup()
    {
        $this->_init();
        $examId = !empty($_POST['examId']) ? $this->input->post('examId') : '';
        $groupId = !empty($_POST['groupId']) ? $this->input->post('groupId') : '';
        $examName = !empty($_POST['examName']) ? $this->input->post('examName') : '';
        $actionType = !empty($_POST['actionType']) ? $this->input->post('actionType') : '';

        if(empty($examId) || empty($groupId) || empty($actionType))
        {
            echo json_encode(array('data' => array('error' => array('msg' => 'Unable to Fetch content'))));
            return;
        }

        // lock cms editor info
        $ExamPageCache = $this->load->library('examPages/cache/ExamPageCache');
        $result = $ExamPageCache->getCMSUserLockingInfo($examId);
        if(!empty($result) && !empty($result['userId']) && $result['userId'] != $this->validateuser[0]['userid']){
            echo json_encode(array('data' => array('error' => array('msg' => 'This Exam has been locked by '.$result['userName']))));
            return;       
        }else{
            $cacheData['userId']   = $this->validateuser[0]['userid'];
            $cacheData['userName'] = $this->validateuser[0]['displayname'];
            $ExamPageCache->setCMSUserLockingInfo($examId, $cacheData);
            unset($cacheData);    
        }
        
        $examPageData = $this->examPostingLib->getExamContentDataBasedOnPageId($examId,$groupId);
        if(empty($examPageData))
        {
            $displayData['actionType'] = $actionType;
            $displayData['examPageId'] = 0;  
            $displayData['sectionOrder'] = $this->config->item('page_order');
        }
        else
        {
            $displayData   = $examPageData;
            $displayData['actionType'] = 'edit';
            if($displayData['status'] == 'live')
            {
                $displayData['groupsLiveList'] = $this->examPostingLib->getGroupsUnderExam($examId);
                unset($displayData['groupsLiveList'][$groupId]);
            }
        }

        $displayData['formName']   = "addExamPage";
        $displayData['examId'] = $examId;
        $displayData['groupId'] = $groupId;
        $displayData['numberOfSections'] = 16;
        $this->load->config('eventCategory');
        $displayData['events'] = $this->config->item("events");
        $examPageLib = $this->load->library('examPages/ExamPageLib');
        $examPageUrl = $examPageLib->getExamPageUrl($examName,'',$groupId);
        $html = $this->load->view('examPages/cms/examPageForm',$displayData,true);
        echo json_encode(array('data' => array('html' => $html,'examPageUrl' => $examPageUrl)));
    }
    /*function getExamContentDataBasedOnPageId($examId,$groupId)
    {
        if(empty($examId) || empty($groupId))
        {
            return;
        }
        $cmsmodel = $this->load->model('examcmsmodel');
        return $cmsmodel->getPageIdBasedOnExamGroupId($examId,$groupId);
    }*/

    function migrateExamPageContent($examId = 0){
        $cmsmodel = $this->load->model('examcontentmigrationmodel');
        $cmsmodel->migrateExamPageContent($examId);
    }

    function createAmpContentBasedOnPageId($examPageId)
    {
       if(empty($examPageId))
            return;
        $examPostingLib   = $this->load->library('examPages/ExamPostingLib');
        $examPostingLib->createAmpContentBasedOnPageId($examPageId);
    }
    function deleteAmpCache()
    {
        $pageId = !empty($_POST['page_id']) ? $_POST['page_id'] : '';
        $cmsmodel = $this->load->model('examcmsmodel');
        $examGroupIdArray = $cmsmodel->getExamGroupIdBasecOnPageId($pageId);

        $groupId = $examGroupIdArray['groupId'];
        $examId = $examGroupIdArray['exam_id'];

        if(empty($groupId) || empty($examId))
            return;
        $examCache = $this->load->library('examPages/cache/ExamCache');
        $examCache->deleteCache($groupId,ExamAMPContentKey);
        $this->updateExamPageOnGoogleCDNcacheAMP($examId,$groupId);
    }

    /**
    * @param $examId = examId of Exam Page
    * @param $groupId = groupId of Exam Page
    * @param $isRemove = false means update exampage on google cdn cache.
                         true  means remove exampage on google cdn cache.
    */
    function updateExamPageOnGoogleCDNcacheAMP($examId,$groupId,$isRemove = false)
    {
        if(empty($examId) || empty($groupId))
            return;

        $this->load->builder("examPages/ExamBuilder");

        $examBuilder = new ExamBuilder();
        $examRepo = $examBuilder->getExamRepository();
        $examBasicObj = $examRepo->find($examId);
        if(!empty($examBasicObj))
        {
            $ampURL = $examBasicObj->getAmpURL();
            $primaryGroup = $examBasicObj->getPrimaryGroup();
            $primaryGroupId = $primaryGroup['id'];

            if(!empty($primaryGroupId) && $primaryGroupId != $groupId)
            {
                $ampURL .= '?course='.$groupId;
            }
            if(!empty($ampURL))
            {
                updateGoogleCDNcacheForAMP($ampURL,$isRemove);
                error_log('updated Google CDN Cache For Exam ='.$examId.' and GroupId ='.$groupId);
            }   
        }
    }
    function getExamGroupDetails()
    {
        $this->_init();
        $examId = (isset($_POST['examId']) && $_POST['examId']>0)?$this->input->post('examId'):'0';
        $containerId = 'group ';
      
        if($examId > 0){
            $cmsmodel = $this->load->model('examcmsmodel');
             $groupList = $cmsmodel->getAllGroupDetails($examId);
        }else{
            return;
        }
        echo json_encode($groupList);   
    }

    function unlockEditorInfo($examId){
        if(!empty($_GET['examId'])){
            $this->_init();
        }
        $examId = !empty($_GET['examId']) ? $this->input->get('examId') : $examId;
        $examId = !empty($_POST['examId']) ? $this->input->post('examId') : $examId;
        if(empty($examId) || !is_numeric($examId)){
            return;
        }
        $ExamPageCache = $this->load->library('examPages/cache/ExamPageCache');
        $ExamPageCache->deleteCMSUserLockingInfo($examId);
    }

}
