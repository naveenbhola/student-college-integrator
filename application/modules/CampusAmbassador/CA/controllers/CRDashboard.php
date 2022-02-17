<?php
class CRDashboard extends MX_Controller
{
	var $userStatus = '';
	var $isMentor = false;
	var $menteeList = array();
	var $showChatTab;
        function init($library=array('ajax','Mywallet'),$helper=array('url','image','shikshautility','utility_helper','cr')){
		if(is_array($helper)){
			$this->load->helper($helper);
		}
		if(is_array($library)){
			$this->load->library($library);
		}
		$this->load->model('mentormodel');
		if(($this->userStatus == ""))
		{
			$this->userStatus = $this->checkUserValidation();
			if($this->userStatus != 'false')
			{
				$mentor = $this->mentormodel->checkUserIfMentor(array($this->userStatus[0]['userid']));
				if($mentor[$this->userStatus[0]['userid']] != false && is_array($mentor[$this->userStatus[0]['userid']]) && $mentor[$this->userStatus[0]['userid']][0] != '')
				{
					$this->isMentor = true;
					$this->menteeList = $this->mentormodel->getAllMentees($this->userStatus[0]['userid']);
				}
				else
				{
					$this->isMentor = false;
					$this->menteeList = array();
				}
			}
		}
		$this->load->model('crdashboardmodel');
		$this->showChatTab = true;
	}

	/****************************
	Purpose: Function is use to show the login page for mapping interface.
	*****************************/
	function showLoginPage(){
	    $this->init();
	    $appId = 12;
	    $data['validateuser'] = $this->userStatus;
	    $data['userId'] = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
	    $this->load->view('messageBoard/anaInternal/showLoginForm', $data);
	}
	
	function authCheck()
	{
	   $userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
	   $profile = array();
	   if($userId>0){
		$profile = $this->crdashboardmodel->checkValidUser($userId);
	   }
	   if(($userId ==0 && count($profile) ==0) || ($userId !=0 && count($profile) ==0)){
		$this->showLoginPage();
		return false;
	   }
	   else{
		return true;
	   }
	}
	
	/*
	* Function to display the Campus reps unanswered tab 
	*/
	
	function getCRUnansweredTab()
	{
		$this->init();
		
		if($this->authCheck())
		{	
			if($this->input->is_ajax_request()){
			$page_number = filter_var($this->input->post('page'), FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
			}
			$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
			
			$data['isCAEng'] = ($this->isMentor) ? 'Engineering' : '';
			
			$this->load->builder("nationalCourse/CourseBuilder");
			$courseBuilder = new CourseBuilder();
			$courseRepository = $courseBuilder->getCourseRepository();
			$data = $this->getUnansweredQuestions($userId, $page_number);
			if(!empty($data) && is_array($data['result'])){
                                $data['totalQuestion'] = $data['result']['total'];
                        }
			$data['courseRepository'] = $courseRepository;
			$data['validateuser'] = $this->userStatus;
			$data['pageName'] = 'questionPage';
			$data['item_per_page'] = 10;
			if($this->input->is_ajax_request()){
			     echo $this->load->view('dashboard/questionPage',$data);
			}else{
			     $this->load->view('dashboard/unansweredMainPage',$data);	
			}
		}
		
	}

	function getUnansweredQuestions($userId , $page_number){

		$this->benchmark->mark('code_start1');
		$this->init();
		$crData = $this->crdashboardmodel->getMainCourseIdInstituteIdUserIdOfCR($userId);
		$this->benchmark->mark('code_end1');
		error_log("==getUnansweredQuestions==first==".$this->benchmark->elapsed_time('code_start1', 'code_end1'));

                $this->benchmark->mark('code_start2');
                $this->intitutedetaillib = $this->load->library("nationalInstitute/InstituteDetailLib");
                $instituteCourseHierarchy = $this->intitutedetaillib->getAllCoursesForInstitutes($crData['instituteId']);
		$this->benchmark->mark('code_end2');
		error_log("==getUnansweredQuestions==second==".$this->benchmark->elapsed_time('code_start2', 'code_end2'));

                $courseIds  = $instituteCourseHierarchy['instituteWiseCourses'][$crData['instituteId']];
		if(empty($courseIds)){
                        return '';
                }
		$this->benchmark->mark('code_start3');
                $data['result'] = $this->crdashboardmodel->getUnansweredQuestions($userId, $courseIds , $crData['instituteId'], $page_number);
		$this->benchmark->mark('code_end3');
                error_log("==getUnansweredQuestions==third==".$this->benchmark->elapsed_time('code_start3', 'code_end3'));
		$data['crData'] = $crData;
		return $data;
	}
        
	function getCRApprovedAnswers($page_number=0,$item_per_page,$status1,$status2,$status3)
	{
		  $this->init();
		  $userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;          
		  $coursesId = $this->crdashboardmodel->getAllCourseIdFromCR($userId);
		  $data['totalQuestion']=$this->crdashboardmodel->getTotalApprovedAnswer($userId,$coursesId,$status1,$status2,$status3);
		  $data['result']=$this->crdashboardmodel->getApprovedAnswersFromDb($userId,$coursesId,$page_number,$item_per_page,$status1,$status2,$status3);
		  return $data;
	}
	
	function getAnswersComment()
	{
	     $this->init();
	     if($this->authCheck())
	     {	
			if($this->input->is_ajax_request()){
			$page_number = filter_var($this->input->post('page'), FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
			}		
			$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
			$this->load->builder("nationalCourse/CourseBuilder");
			$courseBuilder = new CourseBuilder();
			$courseRepository = $courseBuilder->getCourseRepository();
			$data['courseRepository'] = $courseRepository;
			
			$data['isCAEng'] = ($this->isMentor) ? 'Engineering' : '';
			
                        $status1='draft';
                        $status2='approved';
                        $status3='';
                        $item_per_page=10;
			$result = $this->getCRApprovedAnswers($page_number,$item_per_page,$status1,$status2,$status3);
			$AnswerComment = array();
			foreach((object)$result['result'] as $results)
			{
			   $AnswerComment[] = $results->answerId;
			   
			}
			$answerId=implode(',',$AnswerComment);
			$data['result']=$result;
			$data['validateuser'] = $this->userStatus;
			$data['pageName'] = 'answerPage';
			$data['item_per_page'] = $item_per_page;
		   
			$data['totalQuestion'] = $result['totalQuestion'][0]->totalAnswer;
			
			$data['AnswerComment'] = $this->crdashboardmodel->getCRAnswerComment($answerId);
			
			if($this->input->is_ajax_request())
			{
				echo $this->load->view('dashboard/approvedAnswersTab',$data);
		        }else{
				$this->load->view('dashboard/answeredMainPage',$data);	
			}
	     }
	}
        
        function getDisapprovedAnswer()
        {
     		redirect('/CA/CRDashboard/getCRUnansweredTab');
          $this->init();
          if($this->authCheck())
	     {	
			if($this->input->is_ajax_request()){
			$page_number = filter_var($this->input->post('page'), FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
			}
			
			$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
			$this->load->builder("nationalCourse/CourseBuilder");
			$courseBuilder = new CourseBuilder();
			$courseRepository = $courseBuilder->getCourseRepository();
		
			$data['courseRepository'] = $courseRepository;
			
			$data['isCAEng'] = ($this->isMentor) ? 'Engineering' : '';
			
                        $status1='';
                        $status2='';
                        $status3='disapproved';
                        $item_per_page=10;
			$result = $this->getCRApprovedAnswers($page_number,$item_per_page,$status1,$status2,$status3);
			$AnswerComment = array();
			foreach((object)$result['result'] as $results)
			{
			   $AnswerComment[] = $results->answerId;
			   
			}
			$answerId=implode(',',$AnswerComment);
			$data['result']=$result;
			$data['validateuser'] = $this->userStatus;
			$data['pageName'] = 'disapprovedAnswerPage';
			$data['item_per_page'] = 10;	   
			$data['totalQuestion'] = $result['totalQuestion'][0]->totalAnswer;
			$data['AnswerComment'] = $this->crdashboardmodel->getCRAnswerComment($answerId);
			
			if($this->input->is_ajax_request())
			{
				echo $this->load->view('dashboard/disapprovedAnswerTab',$data);
		        }else{
				$this->load->view('dashboard/disapprovedAnsweredMainPage',$data);	
			}
	     
               }
          
        }

	function getPageTabWidget($pageName,$totalUnansweredQuestions='',$coursesId='')
	{
		$this->init();
		$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		if(($totalUnansweredQuestions=='' && $coursesId=='') || ($totalUnansweredQuestions!='' && $coursesId=='') || ($totalUnansweredQuestions=='' && $coursesId!='')){
                        $unansweredQData = $this->getUnansweredQuestions($userId);
                        $data['totalQuestionRows'] = $unansweredQData['result']['total'];
			$coursesId = $unansweredQData['crData']['courseId'];
		}
     		$data['totalApprovedAnswer'] = $this->crdashboardmodel->getTotalApprovedAnswer($userId,$coursesId,'draft','approved','');
		
		// make default landing page
                $data['totalDisapprovedAnswer'] = $this->crdashboardmodel->getTotalApprovedAnswer($userId,$coursesId,'','','disapproved');
		
		if($data['totalQuestionRows'] == 0 && $data['totalDisapprovedAnswer'][0]->totalAnswer != 0 && $data['totalApprovedAnswer'][0]->totalAnswer != 0 && $pageName == 'questionPage')
		{     
			redirect('/CA/CRDashboard/getDisapprovedAnswer');
		}elseif($data['totalQuestionRows'] == 0 && $data['totalDisapprovedAnswer'][0]->totalAnswer == 0 && $data['totalApprovedAnswer'][0]->totalAnswer != 0 && $pageName == 'questionPage'){
			
			redirect('/CA/CRDashboard/getAnswersComment');
		}elseif($data['totalQuestionRows'] == 0 && $data['totalDisapprovedAnswer'][0]->totalAnswer != 0 && $data['totalApprovedAnswer'][0]->totalAnswer == 0 && $pageName == 'questionPage'){
			
			redirect('/CA/CRDashboard/getDisapprovedAnswer');
		}elseif($data['totalQuestionRows'] != 0 && $data['totalDisapprovedAnswer'][0]->totalAnswer == 0 && $data['totalApprovedAnswer'][0]->totalAnswer != 0 && $pageName == 'disapprovedAnswerPage'){
			
			redirect('/CA/CRDashboard/getCRUnansweredTab');
		}elseif($data['totalQuestionRows'] == 0 && $data['totalDisapprovedAnswer'][0]->totalAnswer == 0 && $data['totalApprovedAnswer'][0]->totalAnswer != 0 && $pageName == 'disapprovedAnswerPage'){

			redirect('/CA/CRDashboard/getAnswersComment');
		}elseif($data['totalQuestionRows'] != 0 && $data['totalDisapprovedAnswer'][0]->totalAnswer == 0 && $data['totalApprovedAnswer'][0]->totalAnswer == 0 && $pageName == 'disapprovedAnswerPage'){
			
			redirect('/CA/CRDashboard/getCRUnansweredTab');
		}elseif($data['totalQuestionRows'] != 0 && $data['totalDisapprovedAnswer'][0]->totalAnswer != 0 && $data['totalApprovedAnswer'][0]->totalAnswer == 0 && $pageName == 'answerPage'){
			
			redirect('/CA/CRDashboard/getCRUnansweredTab');
		}elseif($data['totalQuestionRows'] == 0 && $data['totalDisapprovedAnswer'][0]->totalAnswer != 0 && $data['totalApprovedAnswer'][0]->totalAnswer == 0 && $pageName == 'answerPage'){
			
			redirect('/CA/CRDashboard/getDisapprovedAnswer');
		}elseif($data['totalQuestionRows'] != 0 && $data['totalDisapprovedAnswer'][0]->totalAnswer == 0 && $data['totalApprovedAnswer'][0]->totalAnswer == 0 && $pageName == 'answerPage'){
			
			redirect('/CA/CRDashboard/getCRUnansweredTab');
		}elseif($data['totalQuestionRows'] == 0 && $data['totalDisapprovedAnswer'][0]->totalAnswer == 0 && $data['totalApprovedAnswer'][0]->totalAnswer == 0 && $pageName == 'myTask'){
			//redirect('/CA/CRDashboard/myTaskTab');
		}
		$this->load->view('dashboard/pageTabWidget',$data);
		
	}
	
	function getProfile()
	{
		$this->init();
		
		$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$data['getInfo'] = $this->crdashboardmodel->getCRProfileInfo($userId);
		$data['getTotalDig'] = $this->crdashboardmodel->getTotalDigAnser($userId);
		$this->load->builder("nationalCourse/CourseBuilder");
		$courseBuilder = new CourseBuilder();
		$courseRepository = $courseBuilder->getCourseRepository();
		$course = $courseRepository->find($data['getInfo'][0]->mainCourseId);
		if(is_object($course))
		{
		       $data['courseName'] = html_escape($course->getName());
		       $data['instituteName'] = html_escape($course->getInstituteName());	
		}
		$totalTime = $this->crdashboardmodel->getAverageAnswerTime($userId);
		$data['totalTime'] = $this->makeAverageAnswerTime($totalTime);	
		$this->load->view('dashboard/profileWidget',$data);	
	}
	
	function makeAverageAnswerTime($time){
		$timeDiff = $time;
		if($timeDiff<0){
			return "0 Sec";
		}elseif($timeDiff>0 && $timeDiff<=30){
			return "$timeDiff secs";
		}elseif($timeDiff>30 && $timeDiff<60){
			return "$timeDiff secs";
		}elseif($timeDiff>60 && $timeDiff<(60*4)){
			return floor(($timeDiff/60))." mins";
		}elseif($timeDiff>(60*4) && $timeDiff<(60*60)){
			$displayTime=floor($timeDiff/60);
			return "$displayTime mins";
		}elseif($timeDiff>(60*60) && $timeDiff<(24*60*60)){
			$displayTime=floor($timeDiff/(60*60));
			if($displayTime==1){
				return "$displayTime hour";
			}else{
				return "$displayTime hours";
			}
		}elseif($timeDiff>(24*60*60) && $timeDiff<(24*7*60*60)){
			$displayTime=floor($timeDiff/(60*60*24));
			if($displayTime==1){
				return "$displayTime day";
			}else{
				return "$displayTime days";
			}	
		}elseif($timeDiff>(24*7*60*60) && $timeDiff<(24*60*60*31)){
			$displayTime=floor($timeDiff/(60*60*24*7));
			if($displayTime==1){
				return "$displayTime week";
			}else{
				return "$displayTime weeks";
			}
		}elseif($timeDiff>(24*60*60*31) && $timeDiff<(24*60*60*366)){
			$displayTime=floor($timeDiff/(60*60*24*30));
				if($displayTime==1){
				return "$displayTime month";
			}else{
				return "$displayTime months";
			}
		}elseif($timeDiff>(24*60*60*366*10)){
			return $time;
		}elseif($timeDiff>(24*60*60*366)){
			$displayTime=floor($timeDiff/(60*60*24*366));
			if($displayTime==1){
				return "$displayTime year";
			}else{
				return "$displayTime years";
			}
		}
	}
	
	function myTaskTab($taskType='open',$taskId=''){
		$this->init();
		
		if($this->authCheck())
		{
			$data['pageName'] = 'myTask';
			$data['validateuser'] = $this->userStatus;
			$data['userId'] = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
			
			// added by akhter, get task list by user category
			$crProg = $this->crdashboardmodel->getCrProgram($data['userId']); 
			
			$programId = $crProg[0]['programId'];
			$tasks = $this->crdashboardmodel->getListOfTasks($taskType,$data['userId'],$programId);
			$formattedData = formatTaskData($tasks, $taskId, $taskType);
			$data['status'] = checkIfTaskIdExits($taskId , $tasks, $taskType);
			if($data['status'] == 'false'){
				header('location:'.SHIKSHA_HOME.'/CA/CRDashboard/myTaskTab/'.$taskType);
			}
			$data['tasks'] = $formattedData;
			$data['taskType'] = $taskType;
			$data['uploadedTasks'] = $this->crdashboardmodel->getUploadedTasks($data['userId'],$formattedData['taskInfo'][$taskType]['defaultId']);
			$this->load->view('dashboard/myTask',$data);
		}
	}
	
	function endTask(){
		$this->init();
		$taskId = $this->input->post('taskId');
		$userId = $this->input->post('userId');
		$this->crdashboardmodel->endTask($taskId,$userId);
		echo 'done';
	}
	
	function uploadMedia($index=0) {
	    $this->init();
	    $appId = 1;
	    if(isset($_FILES['userApplicationfile']['name'][$index]) && $_FILES['userApplicationfile']['name'][$index]!=''){

		  $type = $_FILES['userApplicationfile']['type'][$index];
		  $size = $_FILES['userApplicationfile']['size'][$index];
		   
		  if($size>5242880)
		  {
		      echo 'error::::Please upload a file of max 5 MB only::::BrowserHidden';
		      exit;
		  }
		  else{
			//error_log("uploadMedia ====".print_r($size,true));
			$fileName = explode('.',$_FILES['userApplicationfile']['name'][$index]);
			$fileNameToBeAdded = $fileName[0];
			$fileCaption= $fileNameToBeAdded;
			$fileExtension = $fileName[count($fileName) - 1];
			$fileCaption .= $fileExtension == '' ? '' : '.'. $fileExtension;
  
			$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;;
  
			$this->load->library('upload_client');
			$uploadClient = new Upload_client();
  
			$fileType = explode('/', str_replace('"', '', $_FILES['userApplicationfile']['type'][$index]));
			$mediaDataType = '';
			$type = $_FILES['userApplicationfile']['type'][$index];
			if($type== "image/gif" || $type== "image/jpeg"|| $type=="image/jpg" || $type== "image/png"){
				$mediaDataType	= "image";
			}
			
			if($type== "application/msword" || $type== "application/vnd.ms-powerpoint"|| $type=="application/pdf" || $type== "application/vnd.ms-excel" || $type == "text/plain" || $type == "application/postscript" || $type=="application/vnd.openxmlformats-officedocument.wordprocessingml.document" || $type == "application/vnd.openxmlformats-officedocument.wordprocessingml.document"  || $type=="application/vnd.openxmlformats-officedocument.presentationml.presentation" || $type == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"){
				$mediaDataType	= 'pdf';
			}
			if($mediaDataType==''){
				echo 'error::::Only pdf, ppt, pptx, doc, docx, xls, xlsx, txt files are allowed::::BrowserHidden';
				exit;
			}
		      //$FILES = $_FILES;
			$FILES = array();
			$FILES['userApplicationfile']['type'][0] = $_FILES['userApplicationfile']['type'][$index];
			$FILES['userApplicationfile']['name'][0] = $_FILES['userApplicationfile']['name'][$index];
			$FILES['userApplicationfile']['tmp_name'][0] = $_FILES['userApplicationfile']['tmp_name'][$index];
			$FILES['userApplicationfile']['error'][0] = $_FILES['userApplicationfile']['error'][$index];
			$FILES['userApplicationfile']['size'][0] = $_FILES['userApplicationfile']['size'][$index];

	//error_log(" uploadMedia ====".print_r( $_FILES['userApplicationfile']['type'][$index],true));die;
		      //Before uploading the file, check the Size and type of file. Only if they are valid, will we proceed with the uploading
			$upload_forms = $uploadClient->uploadFile($appId,$mediaDataType,$FILES,array($fileCaption),$userId, 'uploadFromCRDashboard','userApplicationfile');
			//error_log(" uploadMedia ====*****".print_r( $_POST,true));
			if(is_array($upload_forms)) {
			    $displayData['fileId'] = $upload_forms[0]['mediaid'];
			    $displayData['fileName'] = $fileCaption;
			    $displayData['mediaType'] = $mediaDataType;
			    $displayData['fileUrl'] = $upload_forms[0]['imageurl'];
			    $userId = $this->input->post('userId');
			    $taskId = $this->input->post('taskId');
			    $task_uploaded_id = $this->crdashboardmodel->uploadTask($userId, $taskId, $_FILES['userApplicationfile']['name'][$index], $displayData['fileUrl']);
			    echo "noerror::::".$_FILES['userApplicationfile']['name'][$index].'::::'.$task_uploaded_id.'::::BrowserHidden';
			} else {
			    echo $displayData['error'] = "error::::".$upload_forms.'::::BrowserHidden';exit;
			}
		  }
	    }else{
		echo $displayData['error'] = 'error::::Please select a file to upload::::BrowserHidden';
	    }
	    //return $displayData;
	}
	
	function removeUploadedTask(){
		$this->init();
		$task_uploaded_id = $this->input->post('task_uploaded_id');
		$this->crdashboardmodel->removeUploadedTask($task_uploaded_id);
		echo $task_uploaded_id;
	}
	
	function addLink(){
		$this->init();
		$userId = $this->input->post('AL_userId');
		$taskId = $this->input->post('AL_taskId');
		$name = $this->input->post('postlink');
		$url = $this->input->post('postlink');
		//$urlobj=parse_url($url);
		//$name=$urlobj['host'];
		if (preg_match('@^(https?://)?([a-z0-9_-]+\.)*([a-z0-9_-]+)\.[a-z]{2,6}(/.*)?$@i',$url,$match)) {
			//$name = $match[3];
		}
		$task_uploaded_id = $this->crdashboardmodel->uploadTask($userId, $taskId, $name, $url);
		echo "noerror::::".$name."::::".$task_uploaded_id.'::::postlink';
	}

	function getMyWallet()
	{
		header("Location:".CAMPUS_REP_DASHBOARD_URL,TRUE,301);
        exit;
		$this->init();
		
		if($this->authCheck())
		{
			$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
			$coursesId = $this->crdashboardmodel->getAllCourseIdFromCR($userId);
			$data['getTotalQ'] = $this->crdashboardmodel->getUnansweredQuestionsFromDb($userId,$coursesId);
			$data['totalUnans'] = $data['getTotalQ']['total'];
			$data['validateuser'] = $this->userStatus;
			$data['pageName'] = 'walletPage';
			$data['isCAEng'] = ($this->isMentor) ? 'Engineering' : '';
			$money = Array();
			foreach((object)$data['getTotalQ']['result'] as $result)
			{
				$timeDiff = (strtotime(date('Y-m-d H:i:s')) - strtotime($result->creationDate));
				$total = getCREarning($timeDiff,$data['isCAEng']);
				$money[] = $total['money'];
			}
			$data['potentialEarn'] = array_sum($money);
		
			$paid = $this->mywallet->getPaidAmountByUser($userId);
			$data['totaPaid'] = $paid[0]->paid;
			
			$data['totalCRQuestion'] =  $this->crdashboardmodel->getTotalQuestion($userId,$coursesId);

			$data['earning'] = $this->mywallet->getCurrentEarningByUser($userId);
			
			$data['task'] = $this->mywallet->getCreatedTaskByUser($userId);

			$this->load->view('dashboard/walletMainPage',$data);
		}
	}
	
	function getNotificationWidget($notifactionData)
	{  	
		$this->init();
		$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		if($notifactionData['unansweredQCount']==''){
			$data = $this->getUnansweredQuestions($userId);
			$data['totalQuestions'] = $data['result']['total'];
		}else{
			$data['totalQuestions'] = $notifactionData['unansweredQCount'];
		}
		$openTotalTask = $this->crdashboardmodel->getOpenTasks($userId,$crCat[0]['category_id']);
		$uniqueTasks = getUniqeTasks($openTotalTask);
		$data['openTotalTask'] = $uniqueTasks;
		$this->load->view('dashboard/notificationWidget',$data);
	}
	
	function paidCR()
	{
		if($this->input->is_ajax_request()){
		$this->init();	
		$userCRId = $this->input->post('userId');
		$paidAmount = $this->input->post('paidAmount');
		$chequeNumber = $this->input->post('chequeNumber');
		$resp = $this->mywallet->paidCRCheque($userCRId,$paidAmount,$chequeNumber);
		echo $resp;
		//Send mail alert to CA
		if($resp>0)
			$this->sendPaymentAlertMailerToCR($userCRId, $paidAmount);
		}
	}

	  
   function checkAtleastOneTaskSubmitted(){
	$this->init();
    	$userId = $this->input->post('userId');
	$taskId = $this->input->post('taskId');
	echo $this->crdashboardmodel->checkAtleastOneTaskSubmitted($userId,$taskId);
   }
   
	/*
	Function to send a mail alert to
	a CA when a cheque payment is made
	to him from the backend.
	
	Author : Virender
	*/
	function sendPaymentAlertMailerToCR($userId, $paidAmount=0)
	{
		$contentArr = array();
		$attachment = array();
		$this->load->model('camodel');
		$this->CAModel = new CAModel();
		$result = $this->CAModel->getAllCADetails($userId);
		if(is_array($result[0]['ca']) && $paidAmount>0)
		{
			$userEmail = $result[0]['ca']['email'];
			$contentArr['crName'] = ucwords($result[0]['ca']['firstname'].' '.$result[0]['ca']['lastname']);
			$contentArr['paidAmount'] = $paidAmount;
			$this->load->library('mailerClient');
			$MailerClient = new MailerClient();
			Modules::run('systemMailer/SystemMailer/CRPayoutMail',$userEmail, $contentArr, $attachment);
		}
	}
	
	function myChatTab()
	{
		redirect('/CA/CRDashboard/getCRUnansweredTab');
	}
	
	function acceptMenteeChatRequest()
	{
		$this->init();
		if($this->authCheck()){
			$slotId   = (isset($_POST['slotId']) && $_POST['slotId'] != '')?$this->input->post('slotId'):0;
			$menteeId = (isset($_POST['menteeId']) && $_POST['menteeId'] != '')?$this->input->post('menteeId'):0;
			$slotTime = (isset($_POST['slotTime']) && $_POST['slotTime'] != '')?$this->input->post('slotTime'):'';
			$userId   = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
			if($userId > 0 && $this->isMentor && $slotId > 0){
				$menteeList = $this->menteeList;
				$menteeIdArr = array();
				foreach($menteeList as $mentee)
				{
					if($menteeId != $mentee['menteeId'])
						$menteeIdArr[] = $mentee['menteeId'];
				}
				if(!empty($menteeIdArr))
				{
					$menteeIds = implode(',', $menteeIdArr);
					$this->mentormodel->declineOtherRequestedSlots($slotTime, $menteeIds);
				}
				$this->mentormodel->updateChatSlotStatus('booked', $slotId);
				$schedule = array();
				$schedule['slotId']           = $slotId;
				$schedule['mentorId']         = $userId;
				$schedule['menteeId']         = $menteeId;
				$schedule['scheduleStatus']   = 'accepted';
				$schedule['cancelledBy']      = 0;
				$schedule['createdDate']      = date('Y-m-d H:i:s');
				$schedule['modificationDate'] = date('Y-m-d H:i:s');
				$this->mentormodel->addNewChatSchedule($schedule);
				
				$menteeDetails = $this->mentormodel->getUserDetails($menteeId);
				$contentArr['menteeDetails'] = $menteeDetails;
				$contentArr['slotTime'] = date('j F, h:i A',strtotime($slotTime)).' - '.date('h:i A',strtotime($slotTime)+1800);
				$contentArr['status'] = 'accepted';
				
				Modules::run('systemMailer/SystemMailer/acceptDeclineChatRequestByMentor', $menteeDetails[0]['email'], $contentArr);
				//send mail to internal team - added by Virender Singh for UGC-3018 - start
				$this->load->library('MentorMailers');
				$mentorMailer = new MentorMailers;
				$mailerData = array();
				$mailerData['mailSubject'] = 'Mentorship chat notification - Chat scheduled by mentor';
				$mailerData['slotTimeStr'] = date('j F Y, h:i A',strtotime($slotTime)).' - '.date('h:i A',strtotime($slotTime)+1800);
				$mailerData['actionTaken'] = 'Scheduled';
				$mailerData['mentorName']  = ucwords($this->userStatus[0]['firstname'].' '.$this->userStatus[0]['lastname']);
				$mentorEmail = explode('|', $this->userStatus[0]['cookiestr']);
				$mailerData['mentorEmail'] = $mentorEmail[0];
				$mailerData['menteeName']  = ucwords($menteeDetails[0]['firstname'].' '.$menteeDetails[0]['lastname']);
				
				$mailerData['menteeEmail'] = $menteeDetails[0]['email'];
				$mentorMailer->mentorshipProgramInternalTeamMailers('chatSchedulingActionMailer', $mailerData);
				//send mail to internal team - added by Virender Singh for UGC-3018 - end
				echo 'success';die;
			}
		}
		echo 'error';
	}
	
	function cancelScheduledChat()
	{
		$this->init();
		if($this->authCheck()){
			$slotId       = (isset($_POST['slotId']) && $_POST['slotId'] != '')?$this->input->post('slotId'):0;
			$scheduleId   = (isset($_POST['scheduleId']) && $_POST['scheduleId'] != '')?$this->input->post('scheduleId'):0;
			$userId       = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
			$menteeId   = (isset($_POST['menteeId']) && $_POST['menteeId'] != '')?$this->input->post('menteeId'):'';
			$timeSlotStr  = (isset($_POST['timeSlotStr']) && $_POST['timeSlotStr'] != '')?$this->input->post('timeSlotStr'):'';
			
			if($userId > 0 && $this->isMentor && $slotId > 0){
				$this->mentormodel->updateChatSlotStatus('decline', $slotId);
				$this->mentormodel->updateChatScheduleStatus('cancelled', $userId, $scheduleId);
				
				$menteeDetails = $this->mentormodel->getUserDetails($menteeId);
				$contentArr['menteeDetails'] = $menteeDetails;
				$contentArr['timeSlotStr'] = $timeSlotStr;
				$urlOfLandingPage = SHIKSHA_HOME."/user/MyShiksha/index";
				$contentArr['urlOfLandingPage'] = $urlOfLandingPage;
				
				Modules::run('systemMailer/SystemMailer/chatSessionCancelledByMentor', $menteeDetails[0]['email'], $contentArr);
				
				//send mail to internal team - added by Virender Singh for UGC-3018 - start
				$this->load->library('MentorMailers');
				$mentorMailer = new MentorMailers;
				$mailerData = array();
				$mailerData['mailSubject'] = 'Mentorship chat notification - Chat cancelled by mentor';
				$mailerData['slotTimeStr'] = $timeSlotStr;
				$mailerData['actionTaken'] = 'Cancelled';
				$mailerData['mentorName']  = ucwords($this->userStatus[0]['firstname'].' '.$this->userStatus[0]['lastname']);
				$mentorEmail = explode('|', $this->userStatus[0]['cookiestr']);
				$mailerData['mentorEmail'] = $mentorEmail[0];
				$mailerData['menteeName']  = ucwords($menteeDetails[0]['firstname'].' '.$menteeDetails[0]['lastname']);
				
				$mailerData['menteeEmail'] = $menteeDetails[0]['email'];
				$mentorMailer->mentorshipProgramInternalTeamMailers('chatSchedulingActionMailer', $mailerData);
				//send mail to internal team - added by Virender Singh for UGC-3018 - end
				
				echo 'success';die;
			}
		}
		echo 'error';
	}
	
	function addMentorChatForModeration()
	{
		$this->init();
		if($this->authCheck()){
			if($this->input->is_ajax_request()){
				$slotId     = (isset($_POST['slotId']) && $_POST['slotId'] != '')?$this->input->post('slotId'):0;
				$scheduleId = (isset($_POST['scheduleId']) && $_POST['scheduleId'] != '')?$this->input->post('scheduleId'):0;
				$chatTxt    = (isset($_POST['chatTxt']) && $_POST['chatTxt'] != '')?$this->input->post('chatTxt'):'';
				$mentorId   = (isset($_POST['mentorId']) && $_POST['mentorId'] != '')?$this->input->post('mentorId'):0;
				$menteeId   = (isset($_POST['menteeId']) && $_POST['menteeId'] != '')?$this->input->post('menteeId'):0;
				$userId     = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:-1;
				if($this->isMentor && $userId == $mentorId && $slotId > 0 && $scheduleId > 0 && $menteeId > 0){
					$this->load->library('MentorMailers');
					$mentorMailer = new MentorMailers;
					$this->mentormodel->updateChatSlotStatus('expired', $slotId);
					$this->mentormodel->updateChatScheduleStatus('completed', 0, $scheduleId);
					$data = array();
					$data['slotId']      = $slotId;
					$data['scheduleId']  = $scheduleId;
					$data['mentorId']    = $userId;
					$data['menteeId']    = $menteeId;
					$data['chatText']    = base64_encode($chatTxt);
					$data['chatStatus']  = 'draft';
					$data['createdDate'] = date('Y-m-d H:i:s');
					$this->mentormodel->addMentorshipChat($data);
					$menteeDetails = $this->mentormodel->getUserDetails($menteeId);
					$mentorDetails = $this->mentormodel->getUserDetails($mentorId);
					$slotDetails = $this->mentormodel->getSlotDetailsById($slotId);
		
					$contentArr['slotTime'] = date('j F, h:i A ',strtotime($slotDetails[0]['slotTime'])).' - '.date('h:i A',strtotime($slotDetails[0]['slotTime'])+1800);
					$contentArr['menteeDetails'] = $menteeDetails;
					
					$urlOfLandingPage = SHIKSHA_HOME."/user/MyShiksha/index";
					$contentArr['urlOfLandingPage'] = $urlOfLandingPage;
					
					$this->load->library('mailerClient');
					$MailerClient = new MailerClient();
					
					$InternalLandingPage = SHIKSHA_HOME.'/CAEnterprise/CampusAmbassadorEnterprise/getChatModeration';
					 $CMSLoginUrl = $MailerClient->generateAutoLoginLink(1,'cmsadmin@shiksha.com',$InternalLandingPage);
					
					
					Modules::run('systemMailer/SystemMailer/chatCompletionMailToMentee', $menteeDetails[0]['email'], $contentArr);
					$mentorMailer->sendChatCompletionUpdatetoInternalTeam($menteeDetails[0]['firstname'],$menteeDetails[0]['lastname'],$mentorDetails[0]['firstname'],$mentorDetails[0]['lastname'],$contentArr['slotTime'],$CMSLoginUrl);
					
					
					echo 'success';die;
				}
			}
		}
		echo 'error';
	}
	
	function editMentorChatForModeration()
	{
		$this->init();
		if($this->authCheck()){
			if($this->input->is_ajax_request()){
				$chatTxt  = (isset($_POST['chatTxt']) && $_POST['chatTxt'] != '')?$this->input->post('chatTxt'):'';
				$mentorId = (isset($_POST['mentorId']) && $_POST['mentorId'] != '')?$this->input->post('mentorId'):0;
				$logId    = (isset($_POST['logId']) && $_POST['logId'] != '')?$this->input->post('logId'):0;
				$userId   = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:-1;
				if($this->isMentor && $userId == $mentorId && $logId > 0)
				{
					$this->mentormodel->updateChatHistoryText(base64_encode($chatTxt), $logId);
					echo 'success';die;
				}
			}
		}
		echo 'error';
	}
}
?>
