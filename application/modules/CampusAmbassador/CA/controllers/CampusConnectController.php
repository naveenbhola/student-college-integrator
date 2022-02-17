
<?php
/*

   Copyright 2015-16 Info Edge India Ltd

   $Author: Akhter (UGC Team)

   $Id: Campus Connect Controller

 */

class CampusConnectController extends MX_Controller
{
		private $noOfInst = 9;
        function init($library=array('ajax'),$helper=array('url','image','shikshautility','utility_helper', 'cahomepage')){
		if(is_array(  $helper)){
			$this->load->helper($helper);
		}
		if(is_array($library)){
			$this->load->library($library);
		}
		if(($this->userStatus == ""))
			$this->userStatus = $this->checkUserValidation();

        $this->load->helper('coursepages/course_page');
		
		$this->load->model('campusconnectmodel');
		$this->campusconnect = new CampusConnectModel();

		$this->campusConnectBaseLib = $this->load->library('ccBaseLib');

	}
	/**
	 *
	 * Show Campus Connect Homapage
	 *
	 * @param    None
	 * @return   View with the Homepage
	 *
	 */
	function campusConnectHomepage($programId){
		$this->init();

		$programId = !empty($programId)? $programId : 1;

		$programIdMappingData = $this->campusConnectBaseLib->getProgramIdMappingDetails($programId);
		$programIdMappingData = $programIdMappingData[$programId];
		if(empty($programIdMappingData)){
			show_404();
		}

		$this->campusConnectBaseLib->redirectionRule($programIdMappingData['url']);
		$displayData = array();  
		$displayData['validateuser'] = $this->userStatus;
		$displayData['userId'] = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		
        $displayData['programId'] = $programId;
        $displayData['setAutoSuggestorOption'] = array('streamId'=>$programIdMappingData['stream_id'],
        	                                           'substreamId'=>$programIdMappingData['substream_id'],
        	                                           'baseCourseId'=>$programIdMappingData['base_course_id']
        	                                          );
        
		//Get SEO Details
		//_p($programIdMappingData);die;
        $displayData['m_meta_title'] = $programIdMappingData['title'].' Campus Connect';
        $displayData['m_meta_description'] = 'Talk to '.$programIdMappingData['title'].' college current students online. Ask your questions about courses, eligibility, fees, placements and more to select the right college';
        $displayData['canonicalURL'] = $programIdMappingData['url'];

        //prepare breadcrumb html
        $breadcrumbOptions = array('generatorType' 	=> 'CampusConnectHomePage',
									'options' 		=> array(
										'mappingData' =>$programIdMappingData,
										'mappingnameArray'	=>	$programIdMappingData));
		$BreadCrumbGenerator = $this->load->library('common/breadcrumb/BreadcrumbGenerator', $breadcrumbOptions);
		$displayData['breadcrumbHtml'] = $BreadCrumbGenerator->prepareBreadcrumbHtml();
        //below line is used to store the required infromation in beacon varaible for tracking purpose
        $displayData['beaconTrackData'] = $this->campusConnectBaseLib->prepareBeaconTrackData($programIdMappingData,$programId);
        //below line is used for conversion tracking purpose
        $displayData['subscribeTrackingPageKeyID']=200;

        $this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_CampusConnect','entity_id'=>$programId,'stream_id'=>$programIdMappingData['stream_id']);
        $displayData['dfpData']  = $dfpObj->getDFPData($displayData['validateuser'], $dpfParam);
        $this->benchmark->mark('dfp_data_end');

		$this->load->view('campus_connect/campusConnectHomepage',$displayData);
	}
	
	/**
	 * Show Campus Connect Intermediate Page
	 * @param    
	 * @return 
	 * NOTE- Intermediatepage of Campus connect has beed droped, so redirect (301) old url to college question page
	 **/
	function campusConnectIntermediatepage($url,$instituteId){
		if(!(isset($instituteId)) || !is_numeric($instituteId))
		{
			show_404();exit();
		}
		$interMediateUrl = $this->getCampusIntermediateUrl($instituteId);
		$url = $interMediateUrl['url'];
		header("Location: $url",TRUE,301);
	}
	
	function redirect301(){
		$url = SHIKSHA_HOME.'/mba/resources/campus-connect-program-1'; // new url
		$currentUrl = $_SERVER['SCRIPT_URI'];
		if($url !='' && $url != $currentUrl){
			if($_SERVER['QUERY_STRING'] !='' && $_SERVER['QUERY_STRING'] != NULL){
				$url = $url."?".$_SERVER['QUERY_STRING'];
			}
			if('http://'.$_SERVER['HTTP_HOST']==SHIKSHA_MANAGEMENT_HOME){
				if( (strpos($url, "http") === false) || (strpos($url, "http") != 0) || (strpos($url, SHIKSHA_HOME) === 0) || (strpos($url,SHIKSHA_ASK_HOME_URL) === 0) || (strpos($url,SHIKSHA_STUDYABROAD_HOME) === 0)  || (strpos($url,ENTERPRISE_HOME) === 0) ){
					header("Location: $url",TRUE,301);
				}
				else{
				    header("Location: ".SHIKSHA_HOME,TRUE,301);
				}
        	}else{
				if( (strpos($url, "http") === false) || (strpos($url, "http") != 0) || (strpos($url, SHIKSHA_HOME) === 0) || (strpos($url,SHIKSHA_ASK_HOME_URL) === 0) || (strpos($url,SHIKSHA_STUDYABROAD_HOME) === 0 || (strpos($url,ENTERPRISE_HOME) === 0) ) ){
					header("Location: $url",TRUE,301);
				}
				else{
				    header("Location: ".SHIKSHA_HOME,TRUE,301);
				}
			}
			exit;
		}
	}

	/**
	 *
	 * Show Campus Connect Top Ranked and Featured College widget
	 *
	 * @param    
	 * @return 
	 *
	 */
	
	
	function topRankedAndFeaturedCollegeWidget($programId=1){
		
		$this->init();
	
		$instituteIds = array();
		if($this->input->is_ajax_request()){ 
 		    $programId = isset($_POST['programId'])?$this->input->post('programId'):1; 
 	    }
		$widgetType = isset($_POST['widgetType'])?$this->input->post('widgetType'):'topRanked';

        $instCourseData  =  $this->campusConnectBaseLib->getInstAndCourseOfProgram($programId); 

        foreach($instCourseData as $key=>$val){ 
                        $instituteIds[] = $key; 
        } 
        $instituteIds = array_unique($instituteIds); 
        $noOfTopInst = 9; 
        $courseIdStr = ''; 
         
        $this->rankingCommonLib = $this->load->library('rankingV2/RankingCommonLibv2'); 
		if($widgetType == 'topRanked'){

			$displayData['trackingPageKeyId']=196;
            $instRankBySource = $this->rankingCommonLib->getInstituteRankBySource($instituteIds, $noOfTopInst); 
        
            foreach ($instRankBySource as $instId => $source) { 
                    $sortedInstArr[] = $instId; 
            } 

			// set data for Top Question list
			$setDataForTopQuestions = prepareDataForTopQuestion($instRankBySource, $instCourseData); 
			$displayData['dataForTopQuestion'] = $setDataForTopQuestions;

			$courseIdStr = getCrsIdStringOfTopRankedInst($instRankBySource, $instCourseData);
			$allQues = $this->campusconnect->getQuestionsForTopRankedColleges($courseIdStr); 
            $topRankedQuestions = get24QuestionsForTopRankedColleges($allQues, $sortedInstArr); 
            $topRankedQuestions = json_decode($topRankedQuestions, true); 

            // load the institute builder
		    $this->load->builder("nationalCourse/CourseBuilder");
		    $courseBuilder = new CourseBuilder();
		    $displayData['courseRepo'] = $courseBuilder->getCourseRepository();

            $displayData['quesData'] = $topRankedQuestions['finalQuestions']; 
            $displayData['finalQuesId'] = $topRankedQuestions['finalQuesId']; 
            $answerData = $this->campusconnect->getTopRankedRecentAnswers(implode(',', $topRankedQuestions['finalQuesId'])); 

			$answerData = get24AnswersForTopRankedColleges($answerData, $displayData['finalQuesId']);
			$displayData['answerData'] = $answerData;
		}else{
			$displayData['trackingPageKeyId']=197;
			$instRankBySource = $this->campusConnectBaseLib->getAllPaidInstituteId($instituteIds, $noOfTopInst);
            foreach ($instRankBySource as $instId => $source) { 
                            $sortedInstArr[] = $instId; 
            } 
		}

        $finalResult = $this->campusConnectBaseLib->prepareFinalResultForTopFeaturedCollege($sortedInstArr, $instRankBySource, $instCourseData);
		$displayData['result'] = $finalResult;

		$displayData['quesWidgetType'] = $widgetType;

		if($this->input->is_ajax_request()){			
			$this->load->view('campus_connect/showRankedAndFeaturedColleges',$displayData);
		}else{
			$this->load->view('campus_connect/topRankAndFeaturedCollegeWidget',$displayData);
		}
			
	}
	
	/**
	 *
	 * Load Campus Connect Top Ranked College's questions
	 *
	 * @param    
	 * @return 
	 *
	 */
	function getQuestionsForTopRankedIntitutes()
	{
		if($this->input->is_ajax_request()){
			$instData = (isset($_POST['instData']) && $_POST['instData']!='')?$this->input->post('instData'):'';
			if($instData=='')
				exit;
			$getTopRankedInstituteIdsWithCA = json_decode($instData, true);
			
			$this->init();
			$courseIds = array();
			foreach($getTopRankedInstituteIdsWithCA as $val)
			{
				$i++;
				$courseIds[] = $val['courseIdStr'];
				$instituteIds[] = $val['instituteId'];
			}
			$courseIds = array_unique(explode(',',implode(',', $courseIds)));
			$courseIdsStr = implode(',',$courseIds);
			
			$allQues = $this->campusconnect->getQuestionsForTopRankedColleges($courseIdsStr);

			$qnaData = get24QuestionsForTopRankedColleges($allQues, $instituteIds);
			$qnaData = json_decode($qnaData, true);
			$displayData['quesData'] = $qnaData['finalQuestions'];
			$displayData['finalQuesId'] = $qnaData['finalQuesId'];

			// load the institute builder
		    $this->load->builder("nationalCourse/CourseBuilder");
		    $courseBuilder = new CourseBuilder();
		    $displayData['courseRepo'] = $courseBuilder->getCourseRepository();
		 
			$answerData = array();
			if(!empty($qnaData['finalQuesId']))
			{
				$answerData = $this->campusconnect->getTopRankedRecentAnswers(implode(',', $qnaData['finalQuesId']));
			}
			$answerData = get24AnswersForTopRankedColleges($answerData, $displayData['finalQuesId']);
			$displayData['answerData'] = $answerData;
			$displayData['quesWidgetType'] = 'topRanked';

			$this->load->view('campus_connect/topQuestionWidget', $displayData);
		}
	}
	
	/**
	 *
	 * Load Campus Connect Top Ranked College's questions
	 *
	 * @param    
	 * @return 
	 *
	 */
	function getQuestionsWithFeaturedAnswer()
	{
		$this->init();
		$programId = isset($_POST['programId'])?$this->input->post('programId'):1;
		$allFeaturedAns = $this->campusConnectBaseLib->prepareDataForFeaturedQuestion($programId); 
		$ansId = array();
		foreach($allFeaturedAns as $val)
		{
			$ansId[] = $val['threadId'];
			$courseList[$val['threadId']] = $val['courseId'];
		}
		
		if(!empty($ansId))
		{
			$allQues = $this->campusconnect->getQuestionsWithFeaturedAnswer(implode(',', $ansId));
		}

		foreach ($allQues as $key => $value) {
			if($courseList[$value['msgId']]){
				$value['courseId'] = $courseList[$value['msgId']];
				$allQuesList[] = $value;
			}
		}
	
		$displayData['quesData'] = $allQuesList;
		
		$answerData = get24AnswersForQuestionsWithFeaturedAnswer($allFeaturedAns, $allQuesList);
		$displayData['answerData'] = $answerData;
		$displayData['quesWidgetType'] = 'featured';
	
		// load the institute builder
	    $this->load->builder("nationalCourse/CourseBuilder");
	    $courseBuilder = new CourseBuilder();
	    $displayData['courseRepo'] = $courseBuilder->getCourseRepository();

		$this->load->view('campus_connect/topQuestionWidget', $displayData);
	}
	
	/**
	 *function desc : get intermediate page url and used details in meta tags
	 *@author : akhter
	**/
	function getCampusIntermediateUrl($instituteId,$courseHomePageId=1)
	{	
		if($this->input->is_ajax_request())
		{
			$instituteId =  $this->input->post('instituteId');
			$listingType =  ($this->input->post('listingType')) ? $this->input->post('listingType') : 'institute';
			$action      = (isset($_POST['action']) && $_POST['action']!='')?$this->input->post('action'):'';
			if($action == 'CCHomeSearchTracking')
			{
				$search_string  = $this->input->post('search_string');
				$search_clicked = $this->input->post('search_clicked');
				if(isset($_COOKIE['ci_mobile']) && $_COOKIE['ci_mobile'] == 'mobile'){
					$product = 'CampusConnect_Mobile';
					$module  = 'CampusConnectHomepage_Mobile';
				}else{
					$product = 'CampusConnect';
					$module  = 'CampusConnectHomepage';
				}
				$trackingData = array('product'     => $product,
						   'module'         => $module,
						   'search_string'  => $search_string,
						   'search_clicked' => $search_clicked,
						   'listingType'    => $listingType,
						   'listingTypeId'  => $instituteId,
						   'creationDate'   => date('Y-m-d H:i:s'));
				if($search_clicked != ''){
					modules::run('common/autoSuggestorSearchTracking/campusConnectSearchTracking', $trackingData);
				}
			}
		}
		if(isset($instituteId) && $instituteId>0){
			// go to college question page instead of intermediate page, all intermediate will be 301
			$Id = $instituteId;
            $type = 'all_content_pages';
            $optionalArgs['typeOfListing'] = ($listingType) ? $listingType : 'institute'; 
            $optionalArgs['typeOfPage'] = 'questions';
            $data['url'] = getSeoUrl($Id,$type,$title="",$optionalArgs,$flagDbhit='NA',$creationDate='');
		}
		if($this->input->is_ajax_request()){ echo json_encode($data); }else{ return $data; }
	}
	
	
	function mostViewedAndTrendingCollegeQuestions(){
		if($this->input->is_ajax_request()){
			$this->init();
			$programId = isset($_POST['programId'])?$this->input->post('programId'):1;
			$widgetType = isset($_POST['widgetType'])?$this->input->post('widgetType'):'mostViewed';

			$instituteData = $this->campusConnectBaseLib->getInstituteDataForMostViewedAndTrandingWidget($programId,$widgetType,$this->noOfInst,false);
			if($instituteData['finalInstitueDetails']){
				$displayData['instituteData'] = $instituteData['finalInstitueDetails'];
				if($widgetType == 'mostViewed'){
					$orderBy = 'viewCount';
				}else{
					$orderBy = 'creationDate';
				}
				$quesData = array();
				if(!empty($instituteData['courseIdsStr'])){
					$quesData = $this->campusconnect->getQuestionsForBottomCollegeCard($instituteData['courseIdsStr'], $orderBy);
				}
				$quesData = get24QuestionsForMostViewsColleges($quesData, $instituteData['instituteIds']);
				$quesData = json_decode($quesData, true);
				$displayData['quesData'] = $quesData['finalQuestions'];
				$displayData['finalQuesId'] = $quesData['finalQuesId'];
				$answerData = array();
				if(!empty($quesData['finalQuestions'])){
					$answerData = $this->campusconnect->getMostViewedAnswers(implode(',', $quesData['finalQuesId']));
				}
				$answerData = get24AnswersForMostViewsColleges($answerData, $displayData['finalQuesId']);
				$displayData['answerData'] = $answerData;
				
				if($widgetType == 'mostViewed'){
					$displayData['quesWidgetType'] = 'mostViewQuestion';	
				}else{
					$displayData['quesWidgetType'] = 'trendingQuestion';
				}
			}
			$this->load->view('campus_connect/mostViewedQuestionWidget', $displayData);
		}
	}

	
	/**
	 *
	 * Show Campus Connect Most viewed and Trending College widget
	 *
	 * @param    
	 * @return 
	 *
	 */
	
	
	function mostViewedAndTrendingCollegeWidget($programId=1){
		$this->init();
		if($this->input->is_ajax_request()){
			$programId = isset($_POST['programId'])?$this->input->post('programId'):1;
		}
		$widgetType = isset($_POST['widgetType'])?$this->input->post('widgetType'):'mostViewed';
		$instituteData = $this->campusConnectBaseLib->getInstituteDataForMostViewedAndTrandingWidget($programId,$widgetType,$this->noOfInst,true);
		$displayData['quesData'] = array();
		if($instituteData){
			if(!$this->input->is_ajax_request()){
				//code for most viewed question widget - start
				$orderBy = 'viewCount';
				$courseIds = $instituteData['courseIdsStr'];
				if(!empty($courseIds)){
					$quesData = $this->campusconnect->getQuestionsForBottomCollegeCard($courseIds, $orderBy);
				}
				$instituteIds = $instituteData['instituteIds'];
				$quesData = get24QuestionsForMostViewsColleges($quesData, $instituteIds);
				$quesData = json_decode($quesData, true);
				$displayData['quesData'] = $quesData['finalQuestions'];
				$displayData['finalQuesId'] = $quesData['finalQuesId'];
			
				$answerData = $this->campusconnect->getMostViewedAnswers(implode(',', $quesData['finalQuesId']));
				$answerData = get24AnswersForMostViewsColleges($answerData, $displayData['finalQuesId']);
				$displayData['answerData'] = $answerData;
				$displayData['quesWidgetType'] = 'mostViewQuestion';
				//code for most viewed question widget - end
			}
		}
			
		$displayData['instituteData'] = $instituteData['finalInstitueDetails'];
		$displayData['widgetType'] = $widgetType;
		//below line is used for conversion tracking purpose
		if($widgetType=='mostViewed'){
			$displayData['trackingPageKeyId']=198;
		}
		elseif ($widgetType=='trending') {
			$displayData['trackingPageKeyId']=199;
		}
		if($this->input->is_ajax_request()){
			$this->load->view('campus_connect/showMostViewedAndTendingCollege',$displayData);
		}else{
			$this->load->view('campus_connect/mostViewedAndTrendingCollegeWidget',$displayData);
		}
	}

function CampusConnectQuestionsTab()
	{
		
		$this->init();
		$this->load->model('campusconnectmodel');
		$this->ccmodel = new CampusConnectModel();
		$displayData = array();
		$orderOfQuestion = $this->input->post('orderOfQuestion');
		$instId = $this->input->post('instId');
		if(!is_numeric($instId) || $instId<=0){
			return;
		}
		$courseIds = $this->ccmodel->getCourseIdforInstitute($instId);
		$courseIdArray = array();
		foreach($courseIds as $courseId=>$key){
			$courseIdArray[] = $key['courseIdStr'];
		}
		//Get UserId of all CR's of the Institute
		$courseIds = implode(",",$courseIdArray);
		$userIds = $this->ccmodel->getUserIdforInstitute($courseIds);
		foreach($userIds as $value){
			$finaluserIds[] = $value['userId'];
		}
		$result = $this->ccmodel->getLatestAndPopularQuestions($orderOfQuestion,$finaluserIds,$courseIds);
		$display['displayData'] = $result;
		$display['order']=$orderOfQuestion;
 		$this->load->view('campus_connect/questionTab',$display);
	
	}
	
	
	function checkInstituteNaukriData($instituteIds)
	{
         $this->load->model('listing/institutemodel');
         $course_model = $this->load->model('listing/coursemodel');

         if(empty($instituteIds))
             return array();

         $institutemodel     = new institutemodel;
         $data             = array();
         $salaryDataResults     = 
$institutemodel->getNaukriSalaryData($instituteIds, 'multiple');

         $instituteWiseNaukriData = array();

         // get the naukri salary data
         foreach($salaryDataResults as $naukriDataRow)
         {
             if($naukriDataRow['exp_bucket'] == '2-5')
  $instituteWiseNaukriData[$naukriDataRow['institute_id']] = $naukriDataRow;

             $totalEmployees[$naukriDataRow['institute_id']] += 
$naukriDataRow['tot_emp'];
         }

         // unset the naukri data for institutes whose employee count is less than 30
         foreach($totalEmployees as $instituteId => $employeeCount)
         {
             if($employeeCount < 30)
                 unset($instituteWiseNaukriData[$instituteId]);
         }

         return $instituteWiseNaukriData;
     }

     function campusRepMapping()
      {
        
		$this->load->model('campusconnectmodel');
		$this->campusconnect = new CampusConnectModel();
	
		$allCACourseArr = $this->campusconnect->getAllCoursesWithCAForMigration();
	    $numOfCACourses = array();
	    $existBeUser = array();$existMbaUser = array();
            $this->load->model('CA/cadiscussionmodel');
            $this->CADiscussionModel = new CADiscussionModel();
	    
	    $resultData = $this->CADiscussionModel->_checkCategoryOnCourseForMigration($allCACourseArr);
	    _p($resultData);die;
		foreach ($resultData as $key => &$value) {
		   	if($value == 23){
		   		$value = 1;
		   	}else if($value == 56){
		   		$value = 2;
		   	}
		   	else if($value == 28){
		   		$value = 9;
		   	}else if($value == 69 || $value == 70){
		   		$value = 6;
		   	}else if($value == 84){
		   		$value = 7;
		   	}else if($value == 20){
		   		$value = 8;
		   	}else if($value == 33){
		   		$value = 10;
		   	}
		   else if($value == 18){
		   		$value = 8;
		   	}
		   	else {}
		}
		foreach ($resultData as $key => $value) {
	   		$status = $this->campusconnect->updateProgramId($key, $value);
		}
		if($status == 1){
			_p('Script run successfully');
		}else {
			_p('Script failed');
		}
     }	
}?>
