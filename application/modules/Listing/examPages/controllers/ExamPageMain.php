<?php

class ExamPageMain extends MX_Controller {

    private $examRepository;
    private $userStatus;
	
	function _init() {
  		$this->load->builder('ExamBuilder','examPages');
      $examBuilder          = new ExamBuilder();
      $this->examRepository = $examBuilder->getExamRepository();
      if($this->userStatus == ""){
          $this->userStatus = $this->checkUserValidation();
      }
      $this->examPageLib = $this->load->library('examPages/ExamPageLib');
    }

    function getExamPageByNewURl(){
      $urlString   = explode('-exam', $_SERVER[REQUEST_URI]);
      $examName    = end(explode('/', $urlString[0]));
      $sectionName = ltrim($urlString[1],'-');
      $sectionName = explode('?',$sectionName);
      $sectionName = $sectionName[0];
      $this->getExamPage($examName, $sectionName);
    }

    /**
     * Render exam homepage
     * @param  examName and sectionName from url
     **/
    function getExamPage($param, $sectionName){
      
      if($param !='' && $param == 'gmat'){
          $abroadUrl = SHIKSHA_STUDYABROAD_HOME.'/exams/gmat';
          header("Location: $abroadUrl",TRUE,301);exit;
      }
      
    	if(!empty($sectionName) && !is_numeric($sectionName)){
    		$param = $param.'@'.$sectionName;
    	}
    	// initialize resources
      $this->_init();
      //validate exam url's
      $response    = $this->examPageLib->examUrlRequest($param, $isAmp);
      $examId      = $response['examId'];
      $sectionKeyName = $response['sectionName'];

        $examBasicObj = $this->examRepository->find($examId);
        if(empty($examBasicObj)){
          show_404();
            exit(0);
        }
      $primaryGroup = $examBasicObj->getPrimaryGroup();
      $primaryGroupId = $primaryGroup['id'];
      if(empty($primaryGroupId)){
        show_404();exit(0);
      }

      $course = (isset($_GET['course']) && $_GET['course']>0)?$this->input->get('course'):0;
      if($course>0 && is_numeric($course)){
          $groupId = $course;
        }else{
          $groupId = $primaryGroupId;
      }

      $displayData = array(); 
      $displayData['examBasicObj'] = $examBasicObj;     
      $displayData['examId'] = $examId;
      $displayData['groupId'] = $groupId;
      $displayData['pageName'] = 'examPage';
      $displayData['examName'] = htmlentities($examBasicObj->getName());
      $displayData['examFullName'] = htmlentities($examBasicObj->getFullName());
      $displayData['isRootUrl'] = $examBasicObj->isRootUrl();
        $displayData['validateuser'] = $this->userStatus;
        $displayData['userId'] = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
        $displayData['sectionName'] = $sectionName;
        $displayData['primaryGroupId'] = $primaryGroupId;     
      $displayData['course'] = $course; 
      $displayData['currentUrl'] = $examBasicObj->getUrl();
      $displayData['seoSection'] =  $sectionKeyName; 
      $displayData['showAllUpdate'] = (isset($_GET['showAllUpdate']) && $_GET['showAllUpdate'])?$this->input->get('showAllUpdate'):false;   
      $displayData['actionType'] = !empty($_GET['actionType'])?$this->input->get('actionType'):'';
      $displayData['fromwhere']  = !empty($_GET['fromwhere'])?$this->input->get('fromwhere'):'';
      $displayData['fileNo']     = !empty($_GET['fileNo'])?$this->input->get('fileNo'):'';
        if($displayData['userId'] > 0)
        {
   $displayData['GA_userLevel'] = 'Logged In';
        }
        else{
   $displayData['GA_userLevel'] = 'Non-Logged In';
       }
    $this->load->config('examPages/examPageConfig');
  $displayData['trackingKeyList'] = $this->config->item('pcTrackingKeys'); 
      $displayData['instituteAccptLimit'] = D_instituteAcceptingLimit;
      $displayData['similarExamLimit'] = D_similarExamsLimit;
      $displayData['updatesLimit'] = D_updatesCount;

        //ask now
        $displayData['qtrackingPageKeyId'] = $displayData['trackingKeyList']['ask_question'];  
        $displayData['responseAction']     = 'exam_ask_question';
        $displayData['entityId']           = $examId;
        $displayData['tagEntityType']     = 'Exams';
        $displayData['suggestorPageName']  = "all_tags";
        
      $displayData['redirectOriginalUrl'] = $examBasicObj->getUrl();
      $displayData['currentUrl'] = $examBasicObj->getUrl();
      if($course>0){
          $displayData['currentUrlWithParams'] = $displayData['currentUrl'].'?course='.$course;
      }else{
          $displayData['currentUrlWithParams'] = $displayData['currentUrl'];
      }
      $this->examPageLib->prepareExamPageData($displayData);
      // breadcrumb
      $displayData['examBreadCrumb'] = $this->examPageLib->prepareBreadCrumb($this->examRepository, $examBasicObj, $examId, $primaryGroupId, $sectionKeyName);
      $this->load->helper('examPages/exam');  

  //Check if this is MBA Exam. If yes, we will show a banner on it.
        $groupObj = $this->examRepository->findGroup($groupId);
  $displayData['isMBAExam'] = 'false';
  $displayData['isBTechExam'] = false;

        if(!empty($groupObj) && is_object($groupObj)){
            $mapping  = $groupObj->getEntitiesMappedToGroup();
            $data['courseIds']        = $mapping['course'];
            $displayData['examOtherAttribute'] = $mapping['otherAttribute'];
      foreach ($data['courseIds'] as $courseId){
    if($courseId == '101'){
      $displayData['isMBAExam'] = true;
    }
                else if($courseId == '10'){
                        $displayData['isBTechExam'] = true;
                }
      }
        }
        
            $this->benchmark->mark('dfp_data_start');
            $dfpObj   = $this->load->library('common/DFPLib');
            $dpfParam = array('groupObj'=>$groupObj,'parentPage'=>'DFP_ExamPage','pageType'=>$sectionKeyName,'examId'=>$examId,'groupId'=>$groupId,'conductedBy'=>$displayData['conductedBy']);
            $displayData['dfpData']  = $dfpObj->getDFPData($this->userStatus, $dpfParam);
            $this->benchmark->mark('dfp_data_end');

    $displayData['websiteTourContentMapping'] = Modules::run('common/WebsiteTour/getContentMapping','cta','desktop');
  //End check for MBA Exam

        if($examId == '327'){
                $displayData['catMainExam'] = true;
        }
  else if($examId == '6244'){
                $displayData['jeeMainExam'] = true;
        }
      $displayData['anaWidget'] =  Modules::run('examPages/ExamPageMain/prepareAnAWidget',$examId, $examBasicObj->getName(),'data');  
      $this->load->view("examPages/newExam/examHomePage",$displayData);
    }

  // this function will 301 redirect from old exam list page to new exam list page
  public function redirectExamList($param)
	{
		$examRedirect = $this->load->library('examPages/ExamRedirect');
		$examRedirect->examList($param);
	}
	
	/*
    Desc   : list of all exam pages based on stream, stubstream and popular course
    @Param : $param1, $param2 (this will be stream_id, substream_id or courseid)
    @uthor : akhter
    @type  : no return
    */
	function getAllExamList($param1, $param2){

		if(empty($param1)){
			show_404();
		}

		$NUMBER_OF_POPULAR_COURSES = 4;
    $this->load->helper('examPages/exam');  
		$examLibObj = $this->load->library('examPages/ExamPageLib');
		$urlLibObj  = $this->load->library('common/UrlLib',array('stream_id'=>$param1,'substream_id'=>$param2));
    		$examRequest        = $this->load->library('examPages/ExamPageRequest');
    		$pageType   = 'hierarchy';
		$beaconTrackData = array(
			'pageIdentifier' => 'examPageMain',
			'pageEntityId'   => 0,
			'extraData'      => array(
				'countryId' => 2,
        'childPageIdentifier' => 'allExamPage'
			)
		);
		if(strpos($_SERVER['REQUEST_URI'],'pc')){
			$pageType    = 'course';
			$course      = $urlLibObj->getPopularCourse(array($param1));
        	$urlParam    = '/'.$urlLibObj->formateTitle($course['name']).'/exams-pc-'.$course['id'];
        	$data        = array($course['id']);
        	$inputName   = $course['name'];
        	$breadCrumbParam = array('page'=>'examPage','popularCourseName'=>$inputName);
        	$beaconTrackData['extraData']['baseCourseId'] = $course['id'];
            $dpfParam = array('parentPage'=>'DFP_AllExamPage','pageType'=>'popularCourse','baseCourse'=>$course['id']);
		}else if(strpos($_SERVER['REQUEST_URI'],'st')){
			$urlParam    = $urlLibObj->getHierarchy().'/exams-st-'.$param1;
			$data        = $examLibObj->getHierarchyId($param1, 'none', 'none', true);
			$nameString  = $urlLibObj->getStreamSubstreamName(array('stream_id'=>$param1));
			$inputName   = ($nameString['streamName'] === "Sarkari Exams")?"Sarkari":$nameString['streamName'];
			$breadCrumbParam = array('page'=>'examPage','streamName'=>$inputName);
			$beaconTrackData['extraData']['hierarchy'] = array(
					'streamId' => $param1,
					'substreamId' => 0,
					'specializationId' => 0,
				);
            $dpfParam = array('parentPage'=>'DFP_AllExamPage','pageType'=>'stream','stream_id'=> $param1);
		}else if(strpos($_SERVER['REQUEST_URI'],'sb')){
			$urlParam    = $urlLibObj->getHierarchy().'/exams-sb-'.$param1.'-'.$param2;
			$data        = $examLibObj->getHierarchyId($param1, $param2, 'none', true);
			$nameString  = $urlLibObj->getStreamSubstreamName(array('stream_id'=>$param1,'substream_id'=>$param2));
			$inputName   = $nameString['subStreamName'];
			$seoText     = $inputName.' ('.$nameString['streamName'].')';
			$breadCrumbParam = array('page'=>'examPage','streamName'=>$nameString['streamName'],'stream_id'=>$param1,'subStreamName'=>$inputName);
			$beaconTrackData['extraData']['hierarchy'] = array(
					'streamId' => $param1,
					'substreamId' => $param2,
					'specializationId' => 0,
				);
      $dpfParam = array('parentPage'=>'DFP_AllExamPage','pageType'=>'subStream','stream_id'=> $param1,'substream_id'=>$param2);
		}
		$canonicalURL = SHIKSHA_HOME.$urlParam;
		$currentPageUrl = current_url();
		if ( $canonicalURL != $currentPageUrl ) { 
			redirect($canonicalURL, 'location', 301);
		}

		if(!empty($data) && $pageType == 'hierarchy'){
			$result = $examLibObj->getExamList($data,$pageType);
		}else if(!empty($data) && $pageType == 'course'){
			$result = $examLibObj->getExamList($data,$pageType);
		}
    
    //add pagination
    $queryParam    = 'pageNo';
    $pageNo        = $this->input->get('pageNo',true);
    $pageSize      = 20;
    $totalResults  = count($result);
    $totalPages    = ceil($totalResults / $pageSize);
    if(($pageNo && !preg_match("/^[0-9]$/", $pageNo)) || ($pageNo !='' && $pageNo==0)){
      redirect($canonicalURL, 'location', 301);
    }else if($pageNo && $pageNo>$totalPages){
      redirect($canonicalURL, 'location', 302);
    }
    $currentPageNo = (is_numeric($pageNo) && $pageNo>0) ? $pageNo : 1;
    $start         = ($currentPageNo - 1) * $pageSize;
    $result        = array_slice($result, $start, $pageSize,true);

    $prevUrl = ($currentPageNo && $currentPageNo>=2) ? (($currentPageNo==2) ? $currentPageUrl : $currentPageUrl.'?'.$queryParam.'='.($currentPageNo-1) ) : '';
    $nextUrl = ($currentPageNo && $currentPageNo<$totalPages) ? $currentPageUrl.'?'.$queryParam.'='.($currentPageNo+1) : '';

		$examIds = array();
		foreach ($result as $examIdKey => $exampageIdValue) {
			array_push($examIds, $result[$examIdKey]['examId']);
		}
		$examId = implode(",", $examIds);
    $sectionsToShowOnAllExam = array('syllabus','admitcard','answerkey','results','samplepapers','applicationform');
    $this->load->builder('ExamBuilder','examPages');
    $examBuilder          = new ExamBuilder();
    $this->examRepository = $examBuilder->getExamRepository();
    $examObj = $this->examRepository->findMultiple($examIds);
    foreach ($result as $key => $resVal) {
      $sectionArr = $this->examRepository->findContent($resVal['groupId'], array('sectionname'));
      $sectionMappingArr[$key] = $sectionArr['sectionname'];
      $groupIdArr[] = $resVal['groupId'];
    }  
   
    $guideDownloaded = $examLibObj->checkActionPerformedOnGroupForAllExamPage($groupIdArr,'allExamGuide_');

    foreach ($sectionMappingArr as $key => $sec) {
      foreach ($sec as $skey => $sval) {
        if(in_array($sval, $sectionsToShowOnAllExam)) {
          $finalSectionMappingArr[$key][] = $sval;
        }
      }
    }
    $this->load->config('examPages/examPageConfig');
    $sectionNames = $this->config->item('sectionNamesMapping'); 
		/*$examPageModel  = $this->load->model('examPages/exampagemodel');
		$examPageResult = $examPageModel->getExamSectionDescription($examId);
		$finalResult = $examLibObj->mergeData($result, $examPageResult);*/

		$finalResult = $result;

		$exampageIds = array();

    $examPageModel  = $this->load->model('examPages/exampagemodel');
    $exampageIdsmapping = $examPageModel->getPrimaryExamPageIdsForExams($examIds);

    foreach ($exampageIdsmapping as $key => $value) {
        array_push($exampageIds, $value);
        if(array_key_exists($key, $finalResult))
        {
          $finalResult[$key]['exampageId'] = $value;
        }
    }
    

		/*foreach ($finalResult as $exampageIdKey => $exampageIdValue) {
			array_push($exampageIds, $finalResult[$exampageIdKey]['exampageId']);
		}*/

		$exampageIdsToFindDate = implode(",", $exampageIds);
		$exampageIds   = $examPageModel->getDates($exampageIdsToFindDate);

		foreach ($finalResult as $oneResult => $oneValue) {
			$finalResult[ $oneResult ]['examName'] .= " ";
			$count = 0; // This would not let the number of dates selected exceed 2
			foreach($exampageIds as $oneExamPageIdKey => $oneExamPageIdValue) {
				if( $oneExamPageIdValue['exampageId'] == $oneValue['exampageId']  && $count < 2){
					$finalResult[ $oneResult ]['dates'][$count] = array('startDate' => $oneExamPageIdValue['startDate'], 'description' => $oneExamPageIdValue['description'], 'endDate' => $oneExamPageIdValue['endDate']);
					unset($exampageIds[$oneExamPageIdKey]); // Remove the elements which have been used up
					$count++;
				}
			}
    }

    foreach ($finalResult as $k => $fullVal) {
      if(count($fullVal['dates']) < 2 || !array_key_exists('dates', $fullVal)){
        $noDatesPageIds[] =  $fullVal['exampageId'];
      }
    }
    $prevExampageIds   = $examPageModel->getDates(implode(',', $noDatesPageIds), '', 'importantdates', 'endDate');

    foreach ($finalResult as $finResult => $finValue) {
    $finResult[ $oneResult ]['examName'] .= " ";
    $prevCount = count($finValue['dates']); 
    foreach($prevExampageIds as $prevExamPageIdKey => $prevExamPageIdValue) {
      if( $prevExamPageIdValue['exampageId'] == $finValue['exampageId']  && $prevCount < 2){
        $finalResult[ $finResult ]['dates'][$prevCount] = array('startDate' => $prevExamPageIdValue['startDate'], 'description' => $prevExamPageIdValue['description'], 'endDate' => $prevExamPageIdValue['endDate']);
        unset($prevExampageIds[$prevExamPageIdKey]); // Remove the elements which have been used up
        $prevCount++;
      }
    }
    }

    $finalResult = $examLibObj->reArrangeDateFormat($finalResult);
    $isNoIndex = (count($finalResult)<3) ? true : false;
    if($currentPageNo>1){
      $otherExams   = $finalResult;
    }else{
      $popularExams = array_slice($finalResult, 0, $NUMBER_OF_POPULAR_COURSES);
      $otherExams   = array_slice($finalResult, $NUMBER_OF_POPULAR_COURSES);  
    }
		
    if($this->userStatus == ""){
      $this->userStatus = $this->checkUserValidation();
    }
    if(isMobileRequest()){
      $trackingKeyForGuide = 1447;
    }else{
      $trackingKeyForGuide = 1445;
    }  
    $validateuser = $this->userStatus;
    $userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
    $seoText  = !empty($seoText) ? $seoText : $inputName;  
		$viewData = array(
			'seoText'     => $seoText,
			'isNoIndex'   => $isNoIndex,
			'courseName'   => $inputName,
			'popularExams' => $popularExams,
			'otherExams'   => $otherExams,
      'finalSectionMappingArr' => $finalSectionMappingArr,
      'sectionNames' => $sectionNames,
      'examRequestObj' => $examRequest,
      'sectionsToShowOnAllExam' => $sectionsToShowOnAllExam,
			'breadcrumbHtml'  => $urlLibObj->getBreadCrumb($breadCrumbParam),
			'canonicalURL' => $canonicalURL,
			'beaconTrackData' => $beaconTrackData,
      'trackingKeyForGuide' => $trackingKeyForGuide,
      'validateuser' => $validateuser,
			'userStatus' => $validateuser,
      'guideDownloaded' => $guideDownloaded,
      'userId' => $userId,
      'boomr_pageid' => "ALLEXAMPAGE",
      'currentPageNo'=> $currentPageNo,
      'pageSize'=>$pageSize,
      'currentPageUrl'=>$currentPageUrl,
      'totalItems'=>$totalResults,
      'prevURL'=>$prevUrl,
      'nextURL'=>$nextUrl,
      'queryParam'=>$queryParam
		);

    $this->benchmark->mark('dfp_data_start');
    $dfpObj   = $this->load->library('common/DFPLib');
    $viewData['dfpData']  = $dfpObj->getDFPData($this->userStatus, $dpfParam);
    $this->benchmark->mark('dfp_data_end');
		$mmpType = 'newmmpexam';
	 	$isLoggedIn = ($viewData['validateuser'] == 'false') ? false : true;
		$this->load->library('customizedmmp/customizemmp_lib');
		$customizedMMPLib = new customizemmp_lib();
		//View for new MMP common/newMMPForm
		//$viewData['mmpData'] = $customizedMMPLib->seoMMPLayerFromOrganicTraffic($mmpType, $isLoggedIn);
		$gtmArray = $examLibObj->getGTMArray($beaconTrackData, $examId);
		$viewData['gtmParams'] = $gtmArray;
    $viewData['product'] = 'allExams';

                $viewData['isBTechExam'] = 'false';
                if(isset($course['id']) && $course['id'] == '10'){
                        $viewData['isBTechExam'] = 'true';
                }
		$this->load->view('examList', $viewData);
	}

	// desc : redirect 301 only for old mba exam page url like (mba/exam/cat) to new exam url
	function redirectMBA301($examName, $subCategory, $sectionName){
		$examRedirect = $this->load->library('examPages/ExamRedirect');
		$examRedirect->redirectMBAExam($examName, $subCategory, $sectionName);
	}

	// desc : redirect 301 for old other exam page like (jee-mains-exampage) to new url
	function redirectOther301($param){
		$examRedirect = $this->load->library('examPages/ExamRedirect');
		$examRedirect->redirectOtherExam($param);
	}

	function updateTableColumnContentForHttp(){
   	 	ini_set('memory_limit','-1');
        set_time_limit(0);
   	 	$contentObj = $this->load->library('common/httpContent');
   	 	//exam page related tables
   	 	$tableName = 'exampage_college';
   	 	$primaryColumnName = 'id';
   	 	$contentColumnName = 'tile_link';
   	 	$status = array('live', 'draft');
   	 	$contentObj->findHttpInContent($tableName, $primaryColumnName, $contentColumnName, $status);

   	 	$tableName = 'exampage_home';
   	 	$primaryColumnName = 'id';
   	 	$contentColumnName = 'description';
   	 	$status = array('live', 'draft');
   	 	$contentObj->findHttpInContent($tableName, $primaryColumnName, $contentColumnName, $status);

   	 	$tableName = 'exampage_interview';
   	 	$primaryColumnName = 'id';
   	 	$contentColumnName = 'interview';
   	 	$status = array('live', 'draft');
   	 	$contentObj->findHttpInContent($tableName, $primaryColumnName, $contentColumnName, $status);

   	 	$tableName = 'exampage_result';
   	 	$primaryColumnName = 'id';
   	 	$contentColumnName = 'exam_analysis';
   	 	$status = array('live', 'draft');
   	 	$contentObj->findHttpInContent($tableName, $primaryColumnName, $contentColumnName, $status);

   	 	$tableName = 'exampage_result';
   	 	$primaryColumnName = 'id';
   	 	$contentColumnName = 'exam_reaction';
   	 	$status = array('live', 'draft');
   	 	$contentObj->findHttpInContent($tableName, $primaryColumnName, $contentColumnName, $status);

   	 	$tableName = 'exampage_syllabus';
   	 	$primaryColumnName = 'id';
   	 	$contentColumnName = 'syllabus_content';
   	 	$status = array('live', 'draft');
   	 	$contentObj->findHttpInContent($tableName, $primaryColumnName, $contentColumnName, $status);

   	 	$tableName = 'exampage_section_description';
   	 	$primaryColumnName = 'id';
   	 	$contentColumnName = 'section_description';
   	 	$status = array('live', 'draft');
   	 	$contentObj->findHttpInContent($tableName, $primaryColumnName, $contentColumnName, $status);

   	 	$tableName = 'exampage_syllabus';
   	 	$primaryColumnName = 'id';
   	 	$contentColumnName = 'syllabus_content';
   	 	$status = array('live', 'draft');
   	 	$contentObj->findHttpInContent($tableName, $primaryColumnName, $contentColumnName, $status);

   	 	//blog related tables
   	 	$tableName = 'blogTable';
   	 	$primaryColumnName = 'blogId';
   	 	$contentColumnName = 'summary';
   	 	$status = array('live', 'draft');
   	 	$contentObj->findHttpInContent($tableName, $primaryColumnName, $contentColumnName, $status);
		
		$tableName = 'blogDescriptions';
   	 	$primaryColumnName = 'descriptionId';
   	 	$contentColumnName = 'description';
   	 	$status = array();
   	 	$contentObj->findHttpInContent($tableName, $primaryColumnName, $contentColumnName, $status);

   	 	$tableName = 'blogQnA';
   	 	$primaryColumnName = 'id';
   	 	$contentColumnName = 'question';
   	 	$status = array('live', 'draft');
   	 	$contentObj->findHttpInContent($tableName, $primaryColumnName, $contentColumnName, $status);

   	 	$tableName = 'blogQnA';
   	 	$primaryColumnName = 'id';
   	 	$contentColumnName = 'answer';
   	 	$status = array('live', 'draft');
   	 	$contentObj->findHttpInContent($tableName, $primaryColumnName, $contentColumnName, $status);

   	 	$tableName = 'blogSlideShow';
   	 	$primaryColumnName = 'id';
   	 	$contentColumnName = 'description';
   	 	$status = array('live', 'draft');
   	 	$contentObj->findHttpInContent($tableName, $primaryColumnName, $contentColumnName, $status);

   	 	//career related tables
   	 	$tableName = 'CP_CareerPageValueTable';
   	 	$primaryColumnName = 'id';
   	 	$contentColumnName = 'value';
   	 	$status = array('live', 'draft');
   	 	$contentObj->findHttpInContent($tableName, $primaryColumnName, $contentColumnName, $status);

   	 	$tableName = 'CP_CareerPathStepsTable';
   	 	$primaryColumnName = 'id';
   	 	$contentColumnName = 'stepDescription';
   	 	$status = array('live', 'draft');
   	 	$contentObj->findHttpInContent($tableName, $primaryColumnName, $contentColumnName, $status);

   }

    function getAllAnnouncements(){
    	$examId = (isset($_POST['examId']) && $_POST['examId']>0)?$this->input->post('examId'):0;
    	$groupId = (isset($_POST['groupId']) && $_POST['groupId']>0)?$this->input->post('groupId'):0;

    	$examModel = $this->load->model('examPages/exampagemodel');
    	$displayData['updates'] = $examModel->getUpdateDetails($examId, $groupId);
    	$displayData['updateClass'] = 'update-layer-list';
    	$html = $this->load->view('/examPages/newExam/widgets/announcementList',$displayData,true);
        echo json_encode(array('html' => $html));
    }

    function getFeaturedCollege($examId, $groupId){
        $examPageLib = $this->load->library('examPages/ExamPageLib');
        $displayData['info'] = $examPageLib->getFeaturedCollegeData($examId, $groupId);
	if(empty($displayData['info'])){
                return '';
        }
	shuffle($displayData['info']);
        $this->load->view('/examPages/newExam/widgets/featuredCollege',$displayData);
    }
	
    function getContentDeliveryData($examId, $groupId){
    	$examPageLib = $this->load->library('examPages/ExamPageLib');
        $displayData['info'] = $examPageLib->getContentDeliveryData($examId, $groupId);
	if(empty($displayData['info'])){
                return '';
        }
        $this->load->view('/examPages/newExam/widgets/contentDelivery',$displayData);
    }

    function getSimilarExamsLayer()
    {
    	$examId = !empty($_POST['examId']) ? $this->input->post('examId') : '';
    	$groupId = !empty($_POST['groupId']) ? $this->input->post('groupId') : '';

    	if(empty($examId) || empty($groupId))
    		return;
  		
      $examPageLib = $this->load->library('examPages/ExamPageLib');		
  		$showExamList = array();
  		$showExamList['similarExams'] = $examPageLib->getSimilarExamWidget($examId,$groupId,10,true);
  		$showExamList['examId'] = $examId;
  		$showExamList['groupId'] = $groupId;
  		$result = $this->load->view('examPages/newExam/similarExamData',$showExamList,true);
  		echo json_encode(array('result'=> $result));
    }

    /**
     * purpose: To get article widget data
     * @param  examId, examName
     */
    function prepareArticleWidget($examId = 0, $examName = '' , $returnOutputOnly=false)
    {
        $examPageLib = $this->load->library('examPages/ExamPageLib');
        $displayData['examName'] = $examName;

        if(!empty($examId) && $examId!=0 && is_numeric($examId)){
                //Get articles mapped to Exam
                $examPageModel = $this->load->model('examPages/exampagemodel');
                $blogIdsArr = $examPageModel->getAllBlogIdsMappedToExamId($examId);

                //Get blogIds
                if(!empty($blogIdsArr)){
                        $blogIdsCsv = $examPageLib->getBlogIdsCsv($blogIdsArr);
                }

                //Get details of Articles mapped to Exam
                if($blogIdsCsv != ''){
                        $displayData['examPageArticles']        = $examPageLib->getExamArticles('articles',$blogIdsCsv,10,0);
                        $displayData['examPageArticles']        = $displayData['examPageArticles']['articles'];
                }
		if($returnOutputOnly){
			return $displayData;
		}

                //Load the view file
                $this->load->view("examPages/newExam/relatedArticle",$displayData);
        }

    }

    /**
     * purpose: To get ANA widget data
     * @param  examId, examName
     */
    function prepareAnAWidget($examId = 0, $examName = '', $type='view')
    {
	$this->load->helper(array('image','string'));
        $examPageLib = $this->load->library('examPages/ExamPageLib');

        if(!empty($examId) && $examId!=0 && is_numeric($examId)){
        
            $userId       = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
            $displayData = $examPageLib->getAnAWidgetData($examId, $userId);
            $displayData['loggedInUser'] = $userId;
	    $displayData['examName'] = $examName;
            $displayData['listing_type'] = 'ExamPage';

            //Load the view file
            if(isset($displayData['questionsDetail']) && count($displayData['questionsDetail'])>1){
              if($type=='data'){
                return $displayData;
              }
            	$this->load->view('nationalInstitute/InstitutePage/ANAWidget', $displayData);
            }
        }

    }
	
	// Exampage Apply Online CTA
  function getApplyOnline(){
    $examId  = $this->input->post('examId');
    $groupId = $this->input->post('groupId');
    $trackingKey = $this->input->post('tracking_key');
    $examPageLib = $this->load->library('examPages/ExamPageLib');
    $applyOnlineData = $examPageLib->getApplyOnline($examId, $groupId);
    $seoURL = ($applyOnlineData['of_external_url'])?$applyOnlineData['of_external_url']:$applyOnlineData['of_seo_url'];
    if(!empty($seoURL)){
        $seoURL = $seoURL.'?tracking_keyid='.$trackingKey;
        $html = 'Last Date to Apply  <strong class="f14__clr3 fnt__sb">'.date('M d, Y',strtotime($applyOnlineData['of_last_date'])).'</strong> <a href="javascript:void(0);" data-url="'.$seoURL.'" id="eApplyBtn" class="f14__clrb__b" ga-attr="APPLY_NOW">Apply Now</a>';
        echo $html;  
    }
  }

    function storeClickCount($tableName='exampage_featured_cd_links'){
        if(isset($_REQUEST['cdClickCount']) && $_REQUEST['cdClickCount']>0){
		$id = $_REQUEST['cdClickCount'];
        }else{
		return 0;
	}
        $this->exammodel = $this->load->model('examPages/exammodel');
        $res= $this->exammodel->storeClickCount($id, $tableName);
        return $res;
    }

}

