<?php 
class ExamMainCMS extends MX_Controller
{
	private function _init()
    {
    	$this->validateuser = $this->checkUserValidation();
        $this->cmsUserInfo = $this->cmsUserValidation();
        $this->load->model("examPages/exammainmodel");
        if($this->cmsUserInfo['usergroup']!='cms'){
            header("location:/enterprise/Enterprise/disallowedAccess");
            exit();
        }

        $this->load->library('Tagging/TaggingCMSLib');
        $this->taggingCMSLib = new TaggingCMSLib();

        $this->examCache = $this->load->library('examPages/cache/ExamCache');
    }

    private function _initExamMainForm() {
        // prepare the header components
        $headerComponents = array(
                        'css' => array('headerCms', 'footer', 'exampages_cms', 'cmsForms'),
                        'js' => array('common','header'),
                        'jsFooter' => array('examMain', 'cmsForms'),
                        'title' => "",
                        'product' => 'CMSExam',
                        'displayname' => (isset($this->validateuser[0]['displayname']) ? $this->validateuser[0]['displayname'] : ""),
                        );
        // tab to be selected
        $this->cmsUserInfo['prodId'] = EXAM_PAGES_TAB_ID;
        
        // render the view
        echo $this->load->view('enterprise/headerCMS', $headerComponents, true);
        echo $this->load->view('enterprise/cmsTabs', $this->cmsUserInfo, true);
    }
    
    public function showMainExamList($examId)
    {
        // initialize
        $this->_init();
        $this->_initExamMainForm();
        $this->load->library('session');

        $displayData['formName']   = "addEditMainExamForm";
        if($examId != '' && $examId > 0){
        	$displayData['actionType'] = "edit";
        }else{
        	$displayData['actionType'] = "add";
        }
        $examList = array();
        $examMain = new exammainmodel();
        $where = '';
        $value = array();
        if($displayData['actionType'] == 'edit'){
        	$where   = ' and main.id = ? ';
        	$value[] = $examId;
			$examList = $examMain->getAllExamsByFilter($where, $value);
            $listingId = $examList[0]['conductedBy']; // inst/uni
            if(is_numeric($listingId) && $listingId>0 && !empty($listingId)){
                $this->load->builder("nationalInstitute/InstituteBuilder");
                $instituteBuilder = new InstituteBuilder();
                $instituteRepo = $instituteBuilder->getInstituteRepository();
                $instObj = $instituteRepo->find($listingId);    
                if(!empty($instObj) && is_object($instObj)){
                    $conductedByName = $instObj->getName(); // only use for display
                    $examList[0]['conductedByName'] = $conductedByName;
                }
            }
        	$displayData['examData'] = $examList;
        }else{
        	$examList = $examMain->getAllExamsByFilter();
        	$displayData['examList'] = $examList;
        }
       foreach($examList as $exam){
                $exams[$exam['examId']] = htmlentities($exam['examName']);
        }
        $displayData['examListForSuggestor'] = $exams;

        $displayData['suggestorPageName'] = 'CMS_ExamInstSuggestors';
        $this->load->builder('listingBase/ListingBaseBuilder');
        $ListingBaseBuilder = new ListingBaseBuilder();
        $hierarchyRepo = $ListingBaseBuilder->getHierarchyRepository();
        $displayData['streams'] = $hierarchyRepo->getAllStreams();

		$displayData['activePage'] = 'examList';        
        echo $this->load->view('examPages/cms/addEditMainExam', $displayData);
    }

    function manageGroups($groupId){  // showMainExamList
        // initialize
        $this->_init();
        $this->_initExamMainForm();
        $this->load->library('session');

        $displayData['formName']   = "addEditGroupForm";
        if(!empty($groupId) && is_numeric($groupId)){
            $displayData['actionType'] = "edit";
        }else{
            $displayData['actionType'] = "add";
        }

        $examMain = new exammainmodel();
        $AND = '';
        $value = array();
        if($displayData['actionType'] == 'edit'){
            $AND   = ' and grp.groupId = ? ';
            $value[] = $groupId;
            $displayData['examData'] = $examMain->getAllGroupsByFilter($AND, $value);
        }else{
            $displayData['examList'] = $examMain->getAllGroupsByFilter();
        }

        $displayData['suggestorPageName'] = 'CMS_suggestors';
        $displayData['activePage'] = 'examList';
        $where   = " and main.status = 'live'";
        $allExam = $examMain->getExamList($where);
        $displayData ['allExam'] = $allExam; // prepare exam drop-down
        echo $this->load->view('examPages/cms/manageGroup', $displayData);
    }

    function filterGroupByExam($examId){
        if(!is_numeric($examId) || empty($examId)){
            return;
        }
        $this->_init();
        $examMain = new exammainmodel();
        $AND   = ' and grp.examId = ? ';
        $value[] = $examId;
        $displayData['examList'] = $examMain->getAllGroupsByFilter($AND, $value);
        $displayData['groupPageIdList'] = $examMain->getPageContentCreatedOnGroup($examId);
        $html = $this->load->view('examPages/cms/groupHtml', $displayData, true);
        echo json_encode(array('html'=>$html));
    }

    // below function is used to manage exam only.
    function submitExamData(){
        // initialize
        $this->_init();
        $examMain = new exammainmodel();
        $this->load->library('session');

        $editExamId = (isset($_POST['editExamId']) && !empty($_POST['editExamId'])) ? $this->input->post('editExamId') : 0;
        //edit case
        $name = (isset($_POST['examName']) && $_POST['examName'] != '') ? $this->input->post('examName') : '';
        $examFullName = (isset($_POST['examFullName']) && $_POST['examFullName'] != '') ? $this->input->post('examFullName') : '';
        $conductedBy = (isset($_POST['inputConductedBy']) && $_POST['inputConductedBy'] != '') ? $this->input->post('inputConductedBy') : '';
        $searchConductedBy = (isset($_POST['searchConductedBy']) && $_POST['searchConductedBy'] != '') ? $this->input->post('searchConductedBy') : '';
        $rootURL = (isset($_POST['rootURL']) && $_POST['rootURL'] != '') ? $this->input->post('rootURL') : 'No';
        $excludeConductingBodyInUrl = (isset($_POST['excludeConductingBodyInUrl']) && $_POST['excludeConductingBodyInUrl'] == 'Yes') ? true : false;
        

        $oldName = $this->input->post('examOldName');

        $examMainData = array();
        $examMainData['name'] = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $name)));
        $examMainData['fullName'] = trim($examFullName);
        $examMainData['conductedBy'] = !empty($conductedBy) ? trim($conductedBy) : trim($searchConductedBy);
        $examMainData['isRootUrl'] = $rootURL;
	$examMainData['isConductingBody'] = (isset($_POST['excludeConductingBodyInUrl']) && $_POST['excludeConductingBodyInUrl'] == 'Yes') ? $this->input->post('excludeConductingBodyInUrl') : 'No';

        $examId = 0;
        $attrData = array();
        if($editExamId > 0 && !empty($name)){
            $actionType = 'edit';
            //TAGS CMS CALL
            $this->taggingCMSLib->addTagsPendingMappingAction("Exams",$editExamId,'Edit',array('newName'=>$name));
            //update data in exampage_main
            if(strtolower($name) != strtolower($oldName)){
                $examMain->addRedirectForOldToNewExam($editExamId, $oldName, $name);
            }
            $examMain->updateMainExam($examMainData, $editExamId);
            $examMain->updateExamGuide($editExamId,'exam');
            $examId = $editExamId;
        }else if(!empty($name)){
            //insert exam data in exampage_main
            $examMainData['status'] = 'live';
            //$examMainData['exampageId'] = 0;
            $examMainData['creationDate'] = date('Y-m-d H:i:s');
            $examId = $examMain->addMainExam($examMainData);
            $actionType = 'add';
        }

        //create/update URL's
        $this->createExamURL($examId, $excludeConductingBodyInUrl);

        $this->examCache->deleteCache($examId,ExamBaiscKey);

        Modules::run('examPages/ExamPagesCMS/updateExamPageOnGoogleCDNcacheAMP',$examId,'',false);

        // insert into html cache purging queue
        $arr = array("cache_type" => "htmlpage", "entity_type" => "exampage", "entity_id" => $examId, "cache_key_identifier" => "");
        $shikshamodel = $this->load->model("common/shikshamodel");
        $shikshamodel->insertCachePurgingQueue($arr);

        if($editExamId > 0){
            $this->session->set_flashdata('errorMsg', 'Exam has been successfully updated.');
            redirect('/examPages/ExamMainCMS/showMainExamList/', 'location', 301);
        }else{
            $this->session->set_flashdata('errorMsg', 'Exam has been successfully saved.');
            $this->taggingCMSLib->addTagsPendingMappingAction("Exams",$examId,'Add');
            redirect('/examPages/ExamMainCMS/showMainExamList', 'location', 301);
        }
    }

    function createExamURL($examId, $excludeConductingBody = false){
        $ExamPageLib = $this->load->library('examPages/ExamPageLib');
        $ExamPageLib->addExamUrl($examId, $excludeConductingBody);
    }

    public function submitExamGroupData(){
        $this->_init();
        $examMain    = new exammainmodel();
        $this->load->library('session');

        $editExamId = (isset($_POST['editExamId']) && !empty($_POST['editExamId'])) ? $this->input->post('editExamId') : 0;
        $editGroupId = (isset($_POST['editGroupId']) && !empty($_POST['editGroupId'])) ? $this->input->post('editGroupId') : 0;
        $exam        = (isset($_POST['examId']) && $_POST['examId'] != '') ? $this->input->post('examId') : 0;
        $groupName   = (isset($_POST['groupName']) && $_POST['groupName'] != '') ? $this->input->post('groupName') : '';
        $groupName   = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $groupName)));

        $primaryOrder = (isset($_POST['primaryOrder']) && $_POST['primaryOrder'] != '') ? $this->input->post('primaryOrder') : 0;
        $year  = (isset($_POST['groupYear']) && $_POST['groupYear'] != '') ? $this->input->post('groupYear') : '';

        $groupData = array();
        $attrData  = array();
        $examId    = trim($exam);
        $groupId   = $editGroupId;
        $groupData['groupName'] = $groupName;
        $groupData['examId']    = $examId;
        $groupData['isPrimary']    = trim($primaryOrder);
        $groupData['status']    = 'live';
        $groupData['lastModifiedDate'] = date('Y-m-d H:i:s');

        if($editGroupId > 0){ 
            $actionType = 'edit';
            //update exampage_groups table
            $examMain->updateExamGroup($groupData, $editGroupId);
            //mark attribute data as deleted
    	    $attrData['status'] = 'deleted';
    	    $examMain->updateMainExamAttributes($attrData, $editGroupId); 
            $examMain->updateExamGuide($editGroupId,'group'); 
        }else if(!empty($groupName) && $editGroupId == 0){  //insert exam data
            $actionType = 'add';
        	$groupId = $examMain->addExamGroup($groupData);
        }

        //update primary on exam
        $primaryCount = $this->checkPrimaryOnExam($examId);
        if($primaryCount>0 && $primaryOrder == 1){
            $examMain->updatePrimaryGroup($examId, $groupId);
        }

        if(!empty($groupId) && $groupId > 0){
        	//insert attrtibute data
    		$attrData  = array();
    		$stream    = $this->input->post('stream');
		  	$substream = $this->input->post('subStream');
		  	$spec      = $this->input->post('specialization');
		  	$primary   = $this->input->post('primary');
		  	$courses   = $this->input->post('course');
		  	$college = $this->input->post('college');
            $university = $this->input->post('university');
            $group = $this->input->post('group');
            $careers   = $this->input->post('careers');
		  	$othAttr   = $this->input->post('otherAttributes');

		  	$this->appendHierarchyData($examId, $attrData, $stream, $substream, $spec, $primary, $groupId);
		  	$this->appendAttributeData($examId, $attrData, $courses, $college, $university,$group, $careers, $othAttr, $year, $groupId);
		  	$examMain->addMainExamAttributes($attrData);

            if($editExamId > 0){
                // updated set status = deleted for examId in exampage_order table
                $examMain->updateExamOrderStatus($editExamId);
                foreach ($attrData as $key => $value) {
                    if(in_array($value['entityType'],array('hierarchy','primaryHierarchy'))){
                        $hierarchyIds[] = $value['entityId'];
                    }else if($value['entityType'] != 'course'){
                        unset($attrData[$key]);
                    }
                }
                $hierarchyIds = array_unique($hierarchyIds);
                if(count($hierarchyIds) > 0){
                    $this->load->builder('ListingBaseBuilder', 'listingBase');
                    $this->ListingBaseBuilder    = new ListingBaseBuilder();
                    $this->hierarchyRepo = $this->ListingBaseBuilder->getHierarchyRepository();
                    $hierarchyTostreamSubStreamMapping = $this->hierarchyRepo->getBaseEntitiesByHierarchyId($hierarchyIds,0,'array');
                    unset($hierarchyIds);
                    foreach ($hierarchyTostreamSubStreamMapping as $key => $value) {
                        if(!$value['substream_id'] || $value['substream_id'] ==''){
                            $hierarchyTostreamSubStreamMapping[$key]['substream_id'] = 0;
                        }
                        unset($hierarchyTostreamSubStreamMapping[$key]['specialization_id']);
                    }    
                }
                /*$newMapping['stream'] = array_map(function($ele){return $ele['stream_id'];},$hierarchyTostreamSubStreamMapping);
                $newMapping['substream'] = array_map(function($ele){return $ele['substream_id'];},$hierarchyTostreamSubStreamMapping);
                $newMapping['base_course'] = $courses[0];
                $examPostingLib = $this->load->library('examPages/ExamPostingLib');
                $examPostingLib->addExamToIndexLog('allexam',array('currentMapping'=>$currentMapping,'newMapping'=>$newMapping));*/

                $finalData = array();
                // insert new row for examId ordering
                $i =0;
                foreach ($attrData as $key => $value) {
                    if($value['entityType'] == 'course'){
                        $finalData[$i] = array(
                            'streamId' => 0,
                            'subStreamId' => 0,
                            'courseId' => $value['entityId']
                            );
                    }else{
                        $finalData[$i] = array(
                            'streamId' => $hierarchyTostreamSubStreamMapping[$value['entityId']]['stream_id'],
                            'subStreamId' => $hierarchyTostreamSubStreamMapping[$value['entityId']]['substream_id'],
                            'courseId' => 0
                            );
                    }
                    $finalData[$i]['examId'] = $value['examId'];
                    $finalData[$i]['exam_order'] = -1;
                    $finalData[$i]['is_featured'] = 0;
                    $finalData[$i]['status'] = 'live';
                    $i++;
                }
                $examMain->updateOrInsertExamOrderWithHierarchy($finalData);
                $ExamPageLib = $this->load->library('examPages/ExamPageLib');
                $ExamPageLib->updateHierarchiesWithExamNamesCache();
            }
        }
        // create/update exam url
        $this->createExamURL($examId);

        $this->examCache->deleteCache($groupId,GroupKey);
        $this->examCache->deleteCache($examId,ExamBaiscKey);

        $examPageCache = $this->load->library('examPages/cache/ExamPageCache');
        $examPageCache->deleteExamCache('HierarchiesWithExamNames_json');
        
        Modules::run('examPages/ExamPagesCMS/updateExamPageOnGoogleCDNcacheAMP',$examId,$groupId,false);

        // insert into html cache purging queue
        $arr = array("cache_type" => "htmlpage", "entity_type" => "exampage", "entity_id" => $examId, "cache_key_identifier" => "");
        $shikshamodel = $this->load->model("common/shikshamodel");
        $shikshamodel->insertCachePurgingQueue($arr);

        if($editExamId > 0){
            $this->session->set_flashdata('errorMsg', 'Group has been successfully updated.');
        	redirect('/examPages/ExamMainCMS/manageGroups/', 'location', 301);
        }else{
            $this->session->set_flashdata('errorMsg', 'Group has been successfully added.');
            $this->taggingCMSLib->addTagsPendingMappingAction("Exams",$examId,'Add');
        	redirect('/examPages/ExamMainCMS/manageGroups', 'location', 301);
        }
    }

    function checkPrimaryOnExam($examId){
        $this->_init();
        $examId = trim($examId);
        if(is_numeric($examId) && !empty($examId)){
            $examMain = new exammainmodel();
            $result = $examMain->checkPrimaryOnExam($examId);
            if($this->input->is_ajax_request()){
                echo $result;
            }else{
                return $result;
            }
        }
    }

    private function appendHierarchyData($examId, &$attrData, $stream, $substream, $spec, $primary, $groupId){
    	$hierArr = array();
    	foreach ($stream as $key => $value) {
	  		$primaryHierarchy = ($primary == $key) ? 'yes' : 'no';
	  		if(empty($substream[$key])) {
	  			$substream[$key] = 'none';
	  		}
	  		if(empty($spec[$key])) {
	  			$spec[$key] = 'none';
	  		}
	  		if(!empty($value) && $value > 0){
	  			$hierArr[] = array('streamId'=>$value, 'substreamId'=>$substream[$key], 'specializationId'=>$spec[$key],'primaryHierarchy'=>$primaryHierarchy);
	  		}
	  	}
	  	if(!empty($hierArr)){
	  		$this->load->builder('listingBase/ListingBaseBuilder');
			$listingBase   = new ListingBaseBuilder();
			$hierarchyRepo = $listingBase->getHierarchyRepository();
			$hierarchyIds  = $hierarchyRepo->getHierarchiesByMultipleBaseEntities($hierArr);	
	  	}
	  	foreach ($hierarchyIds as $key=>$value) {
	  		$rowData = array();
	  		$rowData['examId'] = $examId;
            $rowData['groupId'] = $groupId;
	  		$rowData['entityId'] = $value['hierarchy_id'];
			$rowData['status'] = 'live';
			$rowData['creationDate'] = date('Y-m-d H:i:s');
			$rowData['modificationDate'] = date('Y-m-d H:i:s');
			$value['substream_id'] = !empty($value['substream_id']) ? $value['substream_id'] : 'none';
			$value['specialization_id'] = !empty($value['specialization_id']) ? $value['specialization_id'] : 'none';
			foreach ($hierArr as $key => $v) {
				if(($v['streamId'] == $value['stream_id']) && ($v['substreamId'] == $value['substream_id']) && ($v['specializationId'] == $value['specialization_id'])){
					$primaryValue = $v['primaryHierarchy'];
				}
			}
			$rowData['entityType'] = ($primaryValue == 'yes') ? 'primaryHierarchy' : 'hierarchy';
			$attrData[] = $rowData;
	  	}
    }

    private function appendAttributeData($examId, &$attrData, $courses, $colleges, $universities, $groups, $careers, $otherAttributes, $year, $groupId){
        $attrArr = array('courses'=>'course', 'careers'=>'career','colleges'=>'college','universities'=>'university', 'groups'=>'group', 'otherAttributes'=>'otherAttribute', 'year'=>'year');
    	foreach ($attrArr as $attr => $type) {
    		foreach ($$attr as $value) {
    			if(!empty($value) && count($value) > 0){
    				foreach ($value as $attrId) {
    					$rowData = array();
                        if(!empty($attrId)){
    			    		$rowData['examId'] = $examId;
                            $rowData['groupId'] = $groupId;
    				  		$rowData['entityId'] = $attrId;
    						$rowData['entityType'] = $type;
    						$rowData['status'] = 'live';
    						$rowData['creationDate'] = date('Y-m-d H:i:s');
    						$rowData['modificationDate'] = date('Y-m-d H:i:s');
    						$attrData[] = $rowData;
                        }
    				}
				}
    		}
    	}
    }

    private function validateExamId($examId){}

    public function checkIfExamNameIsAlreadyExist($examName){
        // initialize
        $this->_init();
        $examMain = new exammainmodel();
        $examName = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $examName)));
        $result = $examMain->checkIfExamNameIsAlreadyExist($examName);
        if($result){
            echo json_encode($result[0]['id']);
        }else{
            echo json_encode(0);
        }
    }

    public function checkIfGroupNameIsAlreadyExist($examId, $groupName){
        $this->_init();
        $examId = trim($examId);
        $groupName = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $groupName)));
        if(is_numeric($examId) && !empty($examId) && !empty($groupName)){
            $examMain = new exammainmodel();
            $result = $examMain->checkIfGroupNameIsAlreadyExist($examId, $groupName);
            if($result){
                echo json_encode($result[0]['groupId']);
            }else{
                echo json_encode(0);
            }    
        }
    }

    function removeGroups($groupId, $examId){
        $this->_init();
        if(is_numeric($groupId) && !empty($groupId) && !empty($examId) && is_numeric($examId)){
            $examMain = new exammainmodel();
            $result = $examMain->removeGroups($groupId, $examId);
            if($result){
                $this->examCache->deleteCache($groupId,GroupKey);
                $this->examCache->deleteCache($examId,ExamBaiscKey);
                $this->examCache->deleteCache($groupId,ExamContentKey);
                $this->examCache->deleteCache($groupId,ExamAMPContentKey);
                
                $examPageCache = $this->load->library('examPages/cache/ExamPageCache');
                $examPageCache->deleteExamCache('HierarchiesWithExamNames_json');
                $this->load->library('session');
                $this->session->set_flashdata('errorMsg', 'Group has been successfully removed.');
                //below line is used for removing Examp Page AMP cache from Google CDN cache
                Modules::run('examPages/ExamPagesCMS/updateExamPageOnGoogleCDNcacheAMP',$examId,$groupId,true);

                // insert into html cache purging queue
                $arr = array("cache_type" => "htmlpage", "entity_type" => "exampage", "entity_id" => $examId, "cache_key_identifier" => "");
                $shikshamodel = $this->load->model("common/shikshamodel");
                $shikshamodel->insertCachePurgingQueue($arr);
        
                redirect('/examPages/ExamMainCMS/manageGroups', 'location');
            }
        }
    }

    function manageExamPageAnnouncements($action = 'add')
    {
        // initialize
        $this->_init();
        $this->_initExamMainForm();

        $displayData['activePage'] = 'examUpdates';
        $displayData['action'] = $action;

        $examList = array();
        $examMain = new exammainmodel();
        $examList = $examMain->getAllExamsByFilter('', array());
        $exams=array();

        foreach($examList as $exam){
                $exams[$exam['examId']] = htmlentities($exam['examName']);            
        }

        $displayData['examList'] = $exams;

        if(!empty($exams)){
                if($action == 'delete'){
                    $examId = (isset($_GET['examId']) && !empty($_GET['examId'])) ? $this->input->get('examId') : 0;
                    $displayData['examId'] = $examId;
                    $displayData = $this->getExamUpdatesList($displayData,$examId);
                }
                echo $this->load->view('examPages/cms/addAnnouncementExamPage', $displayData);
        }else{
            return;
        }  
    }

    function setExamUpdateDetails()
    {

        // initialize
        $this->_init();
        $examMain = new exammainmodel();
        
        $data['examId'] = (isset($_POST['examIdFieldVal']) && $_POST['examIdFieldVal']>0)?$this->input->post('examIdFieldVal'):'0';
        $data['groupIds'] = (isset($_POST['groupIds']) && $_POST['groupIds']>0)?$this->input->post('groupIds'):array();
        $examUpdate = (isset($_POST['examUpdate']) && !empty($_POST['examUpdate']))?$this->input->post('examUpdate'):array();
        $examUpdateUrl = (isset($_POST['examupdateurl']) && !empty($_POST['examupdateurl']))?$this->input->post('examupdateurl'):array();
        $data['isMailSent'] = (isset($_POST['isMailSent']) && !empty($_POST['isMailSent']))?$this->input->post('isMailSent'):'no';

        $data['examUpdate'] = json_decode($examUpdate);
        $data['examUpdateUrl'] = json_decode($examUpdateUrl);
        $response = 'fail';

        if($data['examId'] > 0 && !empty($data['groupIds']) && !empty($data['examUpdate'])){
                $result = $examMain->insertExamUpdateDetails($data);
            if(!empty($result['update_ids'])){
                if($result['isMailSent'] == 'yes'){
                   /* error_log(" Rabbit QUEUE nmasdbasdbh hj");
                    try {
                        $this->config->load('amqp');
                        $this->load->library("common/jobserver/JobManagerFactory");
                        $jobManager = JobManagerFactory::getClientInstance();

                        $rabbitQueue['logType'] = 'examUpdates';
                        $rabbitQueue['updateIds'] = json_encode($result['update_ids']);
                        $rabbitQueue['examId'] = $data['examId'];
                        error_log('Rabbit QUEUE'.print_r($data['groupIds'],true));
                        $rabbitQueue['groupIds'] = json_encode($data['groupIds']);
                        $jobManager->addBackgroundJob("examUpdates", $rabbitQueue);
                    }
                    catch(Exception $e){
                        error_log("JOBQException: ".$e->getMessage());
                    }*/

                    $this->sendUpdateMailerToUsers($result['update_ids'],$data['examId'],$data['groupIds']);
                }
                $response = 'success';               
            }            
        }
        echo $response;

    }

    function getExamGroupDetails()
    {
        $this->_init();
        $examId = (isset($_POST['examId']) && $_POST['examId']>0)?$this->input->post('examId'):'0';
        $containerId = 'group ';
      
        if($examId > 0){
            $examMain = new exammainmodel();
             $groupList = $examMain->getExamGroupDetails($examId);
        }else{
            return;
        }
        echo json_encode($groupList);   

    }

	function getExamsBasedOnStream(){
        $streamId = (isset($_POST['streamId']) && !empty($_POST['streamId'])) ? $this->input->post('streamId') : 0;
        $viewFlag = (isset($_POST['viewFlag']) && !empty($_POST['viewFlag'])) ? $this->input->post('viewFlag') : 'true';
        if($streamId != 0){
            $this->load->builder('listingBase/ListingBaseBuilder');
            $listingBase = new ListingBaseBuilder();
            $hierarchyRepo = $listingBase->getHierarchyRepository();
            $hierarchyIds = $hierarchyRepo->getHierarchyIdByBaseEntities($streamId,'any','any','array');
            if(!empty($hierarchyIds)){
                $hierarchyIds = implode(',',$hierarchyIds);
            }else{
                return;
            }
            $this->load->model("examPages/exammainmodel");
            $examMain = new exammainmodel();
            $examList = $examMain->getAllExamsByStreamFilter($hierarchyIds);

            $i = 0;
            foreach($examList as $exam){
                $examList[$i]['examName'] = htmlentities($exam['examName']);     
                $i++;       
            }

            $displayData['examList'] = $examList;
            if($viewFlag === 'true'){
                $html = $this->load->view('examPages/cms/showFilteredExams', $displayData,true);
                $html = sanitize_output($html);
                echo json_encode(array('html'=>$html)); 
            }else{
                echo json_encode($examList); 
            }
        }
    }

    function getExamDataBasedOnExamId(){
        $examId = (isset($_POST['examId']) && !empty($_POST['examId'])) ? $this->input->post('examId') : 0;
        if(empty($examId)){
            return;
        }
        $this->load->model("examPages/exammainmodel");
        $examMain = new exammainmodel();
        $where = 'and main.id = ?';
        $examList = $examMain->getAllExamsByFilter($where, $examId);
        $displayData['examList'] = $examList;
        $html = $this->load->view('examPages/cms/showFilteredExams', $displayData,true);
        $html = sanitize_output($html);
        echo json_encode(array('html'=>$html)); 
     }

    function getExamUpdatesList(&$displayData,$examId=0){

        $examId = (isset($_POST['examId']) && !empty($_POST['examId'])) ? $this->input->post('examId') : $examId;
        $start = (isset($_POST['page']) && !empty($_POST['page'])) ? $this->input->post('page') : 0;
        $ajaxCall = (isset($_POST['ajaxCall']) && !empty($_POST['ajaxCall'])) ? $this->input->post('ajaxCall') : false;
        $offset= 10;

        $this->load->model("examPages/exammainmodel");
        $examMain = new exammainmodel();
        $updateList = $examMain->getExamUpdatesData($examId);


        $i = 0;
        foreach($updateList['details'] as $id=>$val){
            $updateList['details'][$id]['groupCount'] = count($updateList['details'][$id]['groups']);
            $updateList['details'][$id]['fGroupName'] = $val['groups'][0];
            $updateList['details'][$id]['groupsNameString'] = implode(', ',$val['groups']);
            if($examId>0 && $i==0){
                $displayData['displayExam'] = addslashes($val['examName']);
            }
            $updateList['details'][$id]['examName'] = htmlentities($val['examName']);
            $i++;  
        }    

        foreach($updateList['details'] as $key=>$val){
            $uniqueUpdates[]  = $val;
        }

        $position = ($start * $offset);
        $displayData['totalUpdates'] = count($uniqueUpdates);
        $displayData['offset'] = $offset;
        $displayData['item_per_page'] = $offset;

        $updateDetails = array_slice($uniqueUpdates, $position, $offset);
        $displayData['updateDetails'] = $updateDetails;

        if($ajaxCall){            
            $html = $this->load->view('examPages/cms/updateListExam', $displayData,true);
            $html = sanitize_output($html);
            echo json_encode(array('html'=>$html));   
        }else{
            return $displayData;

        }
              
     }

    function deleteExamUpdates(){
        $updateId = (isset($_POST['updateId']) && !empty($_POST['updateId'])) ? $this->input->post('updateId') : 0;
        if(empty($updateId)){
            return;
        }
        $this->load->model("examPages/exammainmodel");
        $examMain = new exammainmodel();
        $deleteStatus = $examMain->deleteExamUpdates($updateId);
        
        if($deleteStatus){
            $response = 'Update deleted successfully.';
        }else{
            $response = 'Something went wrong';
        }

        echo $response;
     }

     function manageExamPageFeaturedContent($contentType, $mode='add', $editExam){
        // initialize
        $this->_init();

        // prepare the header components
        $headerComponents = array(
                        'css' => array('headerCms', 'footer', 'exampages_cms', 'cmsForms'),
                        'js' => array('common','header','CalendarPopup'),
                        'jsFooter' => array('examMain', 'cmsForms'),
                        'title' => "",
                        'product' => 'CMSExam',
                        'displayname' => (isset($this->validateuser[0]['displayname']) ? $this->validateuser[0]['displayname'] : ""),
                        );
        // tab to be selected
        $this->cmsUserInfo['prodId'] = EXAM_PAGES_TAB_ID;
        
        // render the view
        echo $this->load->view('enterprise/headerCMS', $headerComponents, true);
        echo $this->load->view('enterprise/cmsTabs', $this->cmsUserInfo, true);

        $examList = array();
        $examMain = new exammainmodel();
        $examList = $examMain->getAllExamsByFilter('', array());
        $exams=array();

        foreach($examList as $exam){
                $exams[$exam['examId']] = htmlentities($exam['examName']);            
        }

        $displayData['examList'] = $exams;
        $displayData['contentType'] = $contentType;

        //Edit featured Content
        $idsToExclude = (isset($_GET['selfIds']) && !empty($_GET['selfIds'])) ? $this->input->get('selfIds') : '';
        if($mode == 'edit' && !empty($editExam)) {
            $this->ExamLib = $this->load->library('examPages/ExamMainLib');

            if($contentType == 'institute'){
                $featuredEditData = $examMain->getFeaturedInstData(array('idsToExclude'=>$idsToExclude));
            }elseif($contentType == 'featuredExam'){
                $featuredEditData = $examMain->getFeaturedExamData(array('idsToExclude'=>$idsToExclude));
            }
            
            $displayData['mode'] = $mode;
            $displayData['idsToExclude'] = $idsToExclude;
            $displayData['featuredEditData'] = $featuredEditData[$editExam];
        }

        $examId = (isset($_GET['examId']) && !empty($_GET['examId'])) ? $this->input->get('examId') : 0;
        $displayData['examId'] = $examId;
        $displayData = $this->getAllFeaturedContent($displayData,$examId);

        if($contentType == 'institute'){
            $displayData['activePage'] = 'featured_institute';         
            $displayData['heading'] = 'Universities-Colleges';
            $displayData['suggestorPageName'] = 'instSuggestorsExamCMS';
        }elseif($contentType == 'featuredExam'){
            $displayData['activePage'] = 'featured_exam';       
            $displayData['heading'] = 'Exams';
        }

        $this->load->view('examPages/cms/manageFeaturedContent', $displayData);
     }

    function getAllFeaturedContent(&$displayData,$examId=0){
        //load ExamMainLib library
        $this->ExamLib = $this->load->library('examPages/ExamMainLib');
        if($displayData['contentType'] == 'institute'){
            $featuredContentDetails = $this->ExamLib->formatFeaturedContentData(array('examId' => $examId));
        }elseif($displayData['contentType'] == 'featuredExam'){
            $featuredContentDetails = $this->ExamLib->formatFeaturedExamData(array('examId' => $examId));
        }
        $displayData['featuredData'] = $featuredContentDetails;
        return $displayData;

     }

    function setFeaturedContentData()
    {
        // initialize
        $this->_init();
        $examMain = new exammainmodel();
        
        $data['examId'] = (isset($_POST['examIdFieldVal']) && $_POST['examIdFieldVal']>0)?$this->input->post('examIdFieldVal'):'0';
        $data['groupIds'] = (isset($_POST['groupIds']) && $_POST['groupIds']>0)?$this->input->post('groupIds'):array();
        $data['dest_parent_id'] = (isset($_POST['dest_parent_id']) && !empty($_POST['dest_parent_id']))?$this->input->post('dest_parent_id'):'0';
        $data['dest_id'] = (isset($_POST['dest_id']) && !empty($_POST['dest_id']))?$this->input->post('dest_id'):'0';
        $fromDate = (isset($_POST['fromDate']) && !empty($_POST['fromDate']))?$this->input->post('fromDate'):'';
        $toDate = (isset($_POST['toDate']) && !empty($_POST['toDate']))?$this->input->post('toDate'):'';
        $data['mode'] = (isset($_POST['mode']) && !empty($_POST['mode']))?$this->input->post('mode'):'add';
        $data['idsToExclude'] = (isset($_POST['idsToExclude']) && !empty($_POST['idsToExclude']))?$this->input->post('idsToExclude'):'';
        $contentType = (isset($_POST['contentType']) && !empty($_POST['contentType']))?$this->input->post('contentType'):'institute';
        $data['CTA_text'] = (isset($_POST['CTA_text']) && !empty($_POST['CTA_text']))?$this->input->post('CTA_text'):'';
                $data['redirection_url'] = (isset($_POST['redirection_url']) && !empty($_POST['redirection_url']))?$this->input->post('redirection_url'):'';
        $data['from_date'] = str_replace('/', '-', $fromDate);
        $formattedFromDate = date("Y-m-d", strtotime($from_date));

        $data['to_date'] = str_replace('/', '-', $toDate);
        $formattedToDate = date("Y-m-d", strtotime($toDate));

        if($data['examId'] > 0 && !empty($data['groupIds']) && $data['dest_parent_id']>0 && $data['dest_id']>0 && !empty($fromDate) && !empty($toDate)){
            if($contentType == 'institute'){
                $result = $examMain->insertFeaturedCollegeDetails($data);
                $entityType = 'college';
            }elseif($contentType == 'featuredExam'){
                $result = $examMain->insertFeaturedExamsDetails($data);
                $entityType = 'exam';
            } 

            if($result === '-1'){
                $response = 'This combination already exists for some of the group(s).';
            }else{
                $response = ($result)?'Featured '.$entityType.' posted successfully.':'Something went wrong.';
            }  
            
        }else{
            $response = 'Something went wrong.';
        }

        echo $response;

    }


    function getCourseList(){
        $this->_init();
        $listingId = (isset($_POST['listingId']) && $_POST['listingId']>0)?$this->input->post('listingId'):'0';
        if(!empty($listingId) && $listingId>0){
            $this->intitutedetaillib = $this->load->library("nationalInstitute/InstituteDetailLib");
            $courseList = $this->intitutedetaillib->getAllCoursesForInstitutes($listingId);
            if(!empty($courseList['courseIds'])){
                $courseIds = array();
                foreach($courseList['courseIds'] as $courseId){
                    $courseIds[] = $courseId;
                }
                $this->load->builder("nationalCourse/CourseBuilder");
                $courseBuilder = new CourseBuilder();
                $this->courseRepo = $courseBuilder->getCourseRepository();

                $coursesInfo = $this->courseRepo->findMultiple($courseList['courseIds']);
                foreach ($coursesInfo as $courseKey => $courseValue){
                    $courseId = $courseValue->getId();
                    $courseName = $courseValue->getName();
                    $instituteId = $courseValue->getInstituteId();
                    $this->load->builder("nationalInstitute/InstituteBuilder");
                    $instituteBuilder = new InstituteBuilder();
                    $this->instituteRepo = $instituteBuilder->getInstituteRepository(); 
                    $instituteObj = $this->instituteRepo->find($instituteId,array('basic'));    
                    $instituteName = $courseValue->getOfferedByShortName();
                    $instituteName = $instituteName ? $instituteName : $instituteObj->getShortName();
                    $instituteName = $instituteName ? $instituteName : $instituteObj->getName();
                    if($listingType == 'university'){
                        $courseName .= ", ".$instituteName;
                    }

                    $instituteCourses[] = array('course_id' => $courseId,'course_name' => htmlentities($courseName));
                } 
            }
        }else{
            return;
        }

        echo json_encode($instituteCourses);   
    }

    function deleteFeaturedContent(){
        $data=array();
        $data['dest_id'] = (isset($_POST['dest_id']) && !empty($_POST['dest_id'])) ? $this->input->post('dest_id') : 0;
        $data['orig_exam_id'] = (isset($_POST['orig_exam_id']) && !empty($_POST['orig_exam_id'])) ? $this->input->post('orig_exam_id') : 0;
        $data['startDate'] = (isset($_POST['startDate']) && !empty($_POST['startDate'])) ? $this->input->post('startDate') : '';
        $data['endDate'] = (isset($_POST['endDate']) && !empty($_POST['endDate'])) ? $this->input->post('endDate') : '';
        $data['contentType'] = (isset($_POST['contentType']) && !empty($_POST['contentType'])) ? $this->input->post('contentType') : 0;

        if(empty($data['dest_id']) || empty($data['orig_exam_id']) || empty($data['contentType']) || empty($data['endDate']) || empty($data['startDate'])){
            return;
        }

        $this->load->model("examPages/exammainmodel");
        $examMain = new exammainmodel();

        if($data['contentType'] == 'institute'){
            $data['tableName'] = 'exampage_featured_college';
            $data['dest_column_name'] = 'dest_course_id';
            $responseMsg = 'Featured university/college deleted successfully.';
        }elseif($data['contentType'] == 'featuredExam'){
            $data['tableName'] = 'exampage_featured_exam';
            $data['dest_column_name'] = 'dest_group_id';
            $responseMsg = 'Featured exam deleted successfully.';
        }else{

        }

        $deleteStatus = $examMain->deleteFeaturedContent($data);
        
        if($deleteStatus){
            $response = $responseMsg;
        }else{
            $response = 'Something went wrong';
        }

        echo $response;
     }

    function manageExamPageFeaturedCDLinks($mode='add',$editId){
        // initialize
        $this->_init();

        // prepare the header components
        $headerComponents = array(
                        'css' => array('headerCms', 'footer', 'exampages_cms', 'cmsForms'),
                        'js' => array('common','header','CalendarPopup'),
                        'jsFooter' => array('examMain', 'cmsForms'),
                        'title' => "",
                        'product' => 'CMSExam',
                        'displayname' => (isset($this->validateuser[0]['displayname']) ? $this->validateuser[0]['displayname'] : ""),
                        );
        // tab to be selected
        $this->cmsUserInfo['prodId'] = EXAM_PAGES_TAB_ID;
        
        // render the view
        echo $this->load->view('enterprise/headerCMS', $headerComponents, true);
        echo $this->load->view('enterprise/cmsTabs', $this->cmsUserInfo, true);

        $this->load->builder('listingBase/ListingBaseBuilder');
        $ListingBaseBuilder = new ListingBaseBuilder();
        $hierarchyRepo = $ListingBaseBuilder->getHierarchyRepository();
        $displayData['streams'] = $hierarchyRepo->getAllStreams();
        $displayData['activePage'] = 'featured_links';
        $displayData['heading'] = 'CD Links';

        $examList = array();
        $examMain = new exammainmodel();
        $examList = $examMain->getAllExamsByFilter('', array());
        $exams=array();

        foreach($examList as $exam){
                $exams[$exam['examId']] = htmlentities($exam['examName']);            
        }

        $displayData['examList'] = $exams;

        if($mode == 'edit' && !empty($editId)) {
            $featuredEditData = $examMain->getFeaturedCDLinks(array('link_id' => $editId));                     
            $displayData['edit_link_id'] = $editId;
            $displayData['featuredEditData'] = $featuredEditData['details'][$editId];
        }
        $displayData['mode'] = $mode;
        $examId = (isset($_GET['examId']) && !empty($_GET['examId'])) ? $this->input->get('examId') : 0;
        $displayData['examId'] = $examId;       
        $displayData = $this->getFeaturedCDLinksList($displayData,$examId); 

        $this->load->view('examPages/cms/manageFeaturedCDLinks', $displayData);
     }

     function getFeaturedCDLinksList(&$displayData,$examId=0){

        $examId = (isset($_POST['examId']) && !empty($_POST['examId'])) ? $this->input->post('examId') : $examId;
        $start = (isset($_POST['page']) && !empty($_POST['page'])) ? $this->input->post('page') : 0;
        $ajaxCall = (isset($_POST['ajaxCall']) && !empty($_POST['ajaxCall'])) ? $this->input->post('ajaxCall') : false;
        $eventEndDateOrder = (isset($_POST['eventEndDateOrder']) && !empty($_POST['eventEndDateOrder'])) ? $this->input->post('eventEndDateOrder') : '';
        $offset= 10;
        $this->load->model("examPages/exammainmodel");
        $examMain = new exammainmodel();
        $linksList = $examMain->getFeaturedCDLinks(array('examId'=>$examId, 'eventEndDateOrder'=>$eventEndDateOrder));

        foreach($linksList['details'] as $key=>$val){
            $uniqueLinksList[]  = $val;
        }

        $position = ($start * $offset);
        $displayData['totalLinks'] = count($uniqueLinksList);
        $displayData['item_per_page'] = $offset;

        $featuredData = array_slice($uniqueLinksList, $position, $offset);
        $displayData['featuredData'] = $featuredData;
        $displayData['displayExam'] = $linksList['displayExam'];

        if($ajaxCall){            
            $html = $this->load->view('examPages/cms/featuredCDLinksContentList', $displayData,true);
            $html = sanitize_output($html);
            echo json_encode(array('html'=>$html));   
        }else{
            return $displayData;

        }
              
     }

    function postFeaturedCDLinkData (){

        $examId = (isset($_POST['examIdFieldVal']) && !empty($_POST['examIdFieldVal']))?$this->input->post('examIdFieldVal'):'0';
        $groups = (isset($_POST['groupIdFieldVal']) && !empty($_POST['groupIdFieldVal']))?$this->input->post('groupIdFieldVal'):'';
        $streamId = (isset($_POST['streamIdFieldVal']) && !empty($_POST['streamIdFieldVal']))?$this->input->post('streamIdFieldVal'):'0';
        $campaign_name = (isset($_POST['campaign']) && !empty($_POST['campaign']))?$this->input->post('campaign'):'';
        $heading = (isset($_POST['heading']) && !empty($_POST['heading']))?$this->input->post('heading'):'';
        $body = (isset($_POST['body']) && !empty($_POST['body']))?$this->input->post('body'):'';
        $CTA_text = (isset($_POST['CTA_text']) && !empty($_POST['CTA_text']))?$this->input->post('CTA_text'):'';
        $redirect_url = (isset($_POST['redirect_url']) && !empty($_POST['redirect_url']))?$this->input->post('redirect_url'):'';
        $to_date = (isset($_POST['to_date_main']) && !empty($_POST['to_date_main']))?$this->input->post('to_date_main'):'';
        $from_date = (isset($_POST['from_date_main']) && !empty($_POST['from_date_main']))?$this->input->post('from_date_main'):'';
        $mode = (isset($_POST['mode']) && !empty($_POST['mode']))?$this->input->post('mode'):'';
        $editId = (isset($_POST['editId']) && !empty($_POST['editId']))?$this->input->post('editId'):0;

        $groupIds = explode(',',$groups);

        if($examId == 0 || empty($groups) || empty($campaign_name) || empty($heading) || empty($body) || empty($CTA_text) || empty($redirect_url) || empty($to_date) || empty($from_date) ){
            return;
        }

        $i = 0;
        foreach($campaign_name as $val){
            if(!empty($campaign_name[$i])){
                $data[$i]['campaign_name'] = $campaign_name[$i];
                $data[$i]['heading'] = $heading[$i];
                $data[$i]['body'] = $body[$i];
                $data[$i]['CTA_text'] = $CTA_text[$i];
                $data[$i]['redirection_url'] = $redirect_url[$i];
                $data[$i]['creation_date'] = date('Y-m-d H:i:s');
                $data[$i]['status'] = 'live';
                $fromDate = str_replace('/', '-', $from_date[$i]);
                $data[$i]['start_date'] = date("Y-m-d", strtotime($fromDate));
                $toDate = str_replace('/', '-', $to_date[$i]);
                $data[$i]['end_date'] = date("Y-m-d", strtotime($toDate));
                $i++;
            }        
        }

        $insertData['insertData'] = $data;
        $insertData['examId'] =  $examId;
        $insertData['groupIds'] = $groupIds;
        $insertData['streamId'] = $streamId;
        $insertData['editId'] = $editId;
        $insertData['mode'] = $mode;
        $this->load->model("examPages/exammainmodel");
        $examMain = new exammainmodel();
        $result = $examMain->insertExamCDLinks($insertData);

    }

    function deleteExamFeaturedLinks(){
        $link_id = (isset($_POST['id']) && !empty($_POST['id'])) ? $this->input->post('id') : 0;
        if($link_id == 0){
            return;
        }
        $this->load->model("examPages/exammainmodel");
        $examMain = new exammainmodel();
        $deleteStatus = $examMain->deleteExamFeaturedLinks($link_id,'deleted');
        
        if($deleteStatus){
            $response = 'Link deleted successfully.';
        }else{
            $response = 'Something went wrong';
        }

        echo $response;
     }

    function sendUpdateMailerToUsers($updateIds, $examId, $groupIds){
        /*$updateIds = !empty($_POST['updateIds']) ? json_decode($this->input->post('updateIds')) : $updateIds;
        $examId = !empty($_POST['examId']) ? $this->input->post('examId') : $examId;
        $groupIds = !empty($_POST['groupIds']) ? json_decode($this->input->post('groupIds')) : $groupIds;*/
        
        if(empty($updateIds) || empty($examId) || empty($groupIds)){
            return;
        }

        $this->load->builder('ExamBuilder','examPages');
        $examBuilder          = new ExamBuilder();
        $this->examRepository = $examBuilder->getExamRepository();
       
        $examBasicObj = $this->examRepository->find($examId, false);
        if(empty($examBasicObj)){
            return;
        }
        $primaryGroup = $examBasicObj->getPrimaryGroup();
        $primaryGroupId = $primaryGroup['id'];

        $examModel = $this->load->model('examPages/exampagemodel');
        $updatesData = $examModel->getUpdateDetails($examId,$groupIds,'',$updateIds);
        if($updatesData['updateList'][0]['isMailSent'] == 'yes'){
            $examPageLib = $this->load->library('examPages/ExamPageLib');
            $userList = $examPageLib->getResponsesForExam($groupIds);
        
            $mailerData['userDetails'] = $userList;

            foreach($userList as $user_id=>$details){
                $groupId = $details['groupId'];
                $examContentObj = $this->examRepository->findContent($groupId, 'all', false);
        
                if(empty($examContentObj)){
                    $examContentObj = $this->examRepository->findContent($primaryGroupId, 'all', false);
                    $groupId = $primaryGroupId;
                }

                if(empty($examContentObj)){
                    return;
                }

                $examSections = $examContentObj['sectionname'];
                $this->examRequest        = $this->load->library('examPages/ExamPageRequest');
                $this->load->config('examPages/examPageConfig.php');
                $sectionNameMappings = $this->config->item('sectionNamesMapping');
                $mailerData['examName'] = htmlentities($examBasicObj->getName());
                $examFullName = htmlentities($examBasicObj->getFullName());
                if($primaryGroupId == $groupId){
                    $query_params = '?showAllUpdate=true';
                    $params = '';
                }else{
                    $query_params = '?course='.$groupId.'&showAllUpdate=true';
                    $params = '?course='.$groupId;
                }

                $mailerData['update_url'] = $examBasicObj->getUrl().$query_params;                
                $mailerData['snippetPages'] = array();

                if(count($examSections)>1){
                    foreach ($examSections as $secKey => $secVal) {
                        if($secVal == 'homepage'){
                            $section_url = $examBasicObj->getUrl().$params;
                            $mailerData['snippetPages'][] = array('sectionName'=>'Home Page',
                                                              'sectionUrl'=>$section_url);
                        }else{
                            $this->examRequest->setExamName($mailerData['examName']);
                            $section_url = $this->examRequest->getUrl($secVal,true).$params;
                            $mailerData['snippetPages'][] = array('sectionName'=>$sectionNameMappings[$secVal],
                                                              'sectionUrl'=>$section_url);
                        }                
                    }
                }

                $groupObj  = $this->examRepository->findGroup($groupId);
                if(!empty($groupObj) && is_object($groupObj)){
                    $mapping   = $groupObj->getEntitiesMappedToGroup();
                    $groupYear = $mapping['year'][0];
                }

                if(!empty($groupYear)){
                        $groupYear  = ' '.$groupYear;
                }else{
                        $groupYear  = '';
                }

                if(!empty($examFullName)){
                        $examFullName  = ' ( '.$examFullName. ' )';
                }else{
                        $examFullName  = '';
                }

                $mailerData['examNameForMailer'] = $mailerData['examName'].$examFullName.$groupYear;
                $mailerData['userName'] = $details['displayName'];
                $mailerData['userId'] =$user_id;
                $mailerData['updatesData'] = $updatesData['updateList'];
                $mailerData['totalUpdates'] = $updatesData['totalUpdates'];
                $mailerData['entityId'] = $groupId;
                $mailerData['entityType'] = 'exam';
                Modules::run('systemMailer/SystemMailer/latestUpdateToUsersMail', $details['email'], $mailerData);
            }           
            
        }            
    }
    public function insertIntoAmpRabbitMQueue($examPageId,$tempexamPageId, $pageType = 'exampage')
    {
        if(empty($examPageId))
            return;
        try {
                $this->config->load('amqp');
                $this->load->library("common/jobserver/JobManagerFactory");
                $jobManager = JobManagerFactory::getClientInstance();

                $rabbitQueue['logType'] = $pageType;
                $rabbitQueue['pageId'] = $examPageId;
                $jobManager->addBackgroundJob("convertToAmp", $rabbitQueue);
                foreach ($tempexamPageId as $key => $tempId) {
                    if(!empty($tempId))
                    {
                        $rabbitQueue['logType'] = $pageType;
                        $rabbitQueue['pageId'] = $tempId;
                        $jobManager->addBackgroundJob("convertToAmp", $rabbitQueue);    
                    }
                }
            }
            catch(Exception $e){
                error_log("JOBQException: ".$e->getMessage());
            }
    }

}
