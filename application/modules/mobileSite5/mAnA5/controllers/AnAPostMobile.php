<?php
class AnAPostMobile extends ShikshaMobileWebSite_Controller
{
	public function __construct()
	{

	}
	private function _init()
	{
		 if($this->userStatus == ""){
	        $this->userStatus = $this->checkUserValidation();
        }
	}
	function getReportAbuseLayer()
	{
		$data['msgId'] = empty($_POST['msgId'])?0:$this->input->post('msgId');
		$data['threadId'] = empty($_POST['threadId'])?0:$this->input->post('threadId');
		$data['entityType'] = empty($_POST['entityType'])?0:$this->input->post('entityType');
        $data['trackingPageKeyId'] = empty($_POST['trackingPageKeyId'])?'':$this->input->post('trackingPageKeyId');
		echo $this->load->view('mobile/reportAbuseForm',$data);
	}
	function sendReportAbuseReason()
	{
		$this->_init();

        //Get the Input variables from Post/Get

        $userId       = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;

		$msgId = empty($_POST['msgId'])?0:$this->input->post('msgId');
		$threadId = empty($_POST['threadId'])?0:$this->input->post('threadId');
		$entityType = empty($_POST['entityType'])?0:$this->input->post('entityType');
		$reportAbuseId = empty($_POST['reportAbuseId'])?0:$this->input->post('reportAbuseId');
		$reportAbuseText = empty($_POST['reportAbuseText'])?'':$this->input->post('reportAbuseText');
		$otherReasonText = empty($_POST['otherReasonText'])?'':$this->input->post('otherReasonText');
        $trackingPageKeyId = empty($_POST['trackingPageKeyId'])?'':$this->input->post('trackingPageKeyId');
        
		$reportAbuseId = implode(',', $reportAbuseId);
        $i = 0;
        foreach ($reportAbuseText as $key => $value) {
            $temp = explode('||', $value);
            $chosenReasonText[$i++] = array('Title' => $temp[0],'Content'=>$temp[1]);
        }

        $reportAbuseTextJSON['chosenReasonText'] = $chosenReasonText;

		//$reportAbuseText = json_encode(implode(',', $reportAbuseText));

		//Make a CURL call to fetch the data
        $APIClient = $this->load->library("APIClient");        
        $APIClient->setUserId($userId);
        $APIClient->setVisitorId(getVisitorId());
        $APIClient->setRequestType("post");
        $APIClient->setRequestData(array("msgId"=>$msgId, "threadId" => $threadId ,"entityTypeReportAbuse" => $entityType,"chosenReasonList" => $reportAbuseId,"chosenReasonText" => json_encode($reportAbuseTextJSON),"otherReasonText" => $otherReasonText,"trackingPageKeyId"=>$trackingPageKeyId));
        $jsonDecodedData =$APIClient->getAPIData("AnAPost/setReportAbuseReason");
        $displayMsg = $jsonDecodedData['responseMessage'];
        echo json_encode($displayMsg);
	}

	function postAnswer(){
		$this->_init();

        //Get the Input variables from Post/Get

        $userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;

		$topicId = $this->input->post('topicId');
        $answerText = $this->input->post('answerText');
        $requestIP = S_REMOTE_ADDR;
        $action = isset($_POST['action'])?$this->input->post('action'):'add';
        $answerId = isset($_POST['answerId'])?$this->input->post('answerId'):0;
        $trackingPageKeyId = isset($_POST['trackingPageKeyId'])?$this->input->post('trackingPageKeyId'):'';
        $answerShareUrl = getSeoUrl($topicId,'question',$answerText);
		//Make a CURL call to fetch the data
        $APIClient = $this->load->library("APIClient");        
        $APIClient->setUserId($userId);
        $APIClient->setVisitorId(getVisitorId());
        $APIClient->setRequestType("post");
        $APIClient->setRequestData(array("topicId"=>$topicId, "answerText" => $answerText ,"requestIP" => $requestIP,"action" => $action,"answerId" => $answerId,"trackingPageKeyId"=>$trackingPageKeyId));
        $jsonDecodedData =$APIClient->getAPIData("AnAPost/postAnswer");

        if($action == 'add'){
            $displayData['answerShareUrl'] = $answerShareUrl.'?referenceEntityId='.$jsonDecodedData['answerId'];
        }else{
            $displayData['answerShareUrl'] = $answerShareUrl;
        }
        $displayData['responseMessage'] = $jsonDecodedData['responseMessage'];

        //Modules::run('indexer/NationalIndexer/indexQuestionForAutosuggestor', $topicId);
        echo json_encode($displayData);
	}

    /**
    * Function called to fetch the tags for Edit / Add Question and Discussion and in case of Edit Tags for Intermediate Page
    * API called = AnAPost/postingIntermediatePage
    */
	public function postingIntermediatePageTagsData(){
		$this->_init();
	    $userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;

	    $entityType = isset($_POST['entityType'])?$this->input->post('entityType'):"question";
	    $entityText = isset($_POST['entityText'])?$this->input->post('entityText'):"";
	    $editEntityId = isset($_POST['editEntityId'])?$this->input->post('editEntityId'):"";
	    $action = isset($_POST['action'])?$this->input->post('action'):"";
	    $entityDesc = isset($_POST['entityDesc'])?$this->input->post('entityDesc'):"";
        $trackingPageKeyId = isset($_POST['trackingPageKeyId'])?$this->input->post('trackingPageKeyId'):'';

		$APIClient = $this->load->library("APIClient");        
        $APIClient->setUserId($userId);
        $APIClient->setVisitorId(getVisitorId());
        $APIClient->setRequestType("post");
        $APIClient->setRequestData(array("entityType"=>$entityType, "entityText" => $entityText,"editEntityId"=>$editEntityId,'action'=>$action,"description" => $entityDesc));
        $jsonDecodedData =$APIClient->getAPIData("AnAPost/postingIntermediatePage/");

        $displayData['tagsData'] = array();

        if(isset($jsonDecodedData['tags'])){
            $displayData['tagsData'] = $jsonDecodedData['tags'];    
        }
        $displayData['entityType'] = $entityType;
		$displayData['trackingPageKeyId'] = $trackingPageKeyId;
        echo $this->load->view('mAnA5/mobile/tagsIntermediatePage',$displayData);
	}

    /**
    * Function called to fetch the similar question for Add Question  for Intermediate Page
    * API called = AnA/getSimilarQuestions/
    */
	public function postingIntermediatePageSimilarQuestionsData(){
        
        if(!verifyCSRF()) { return false; }

		$this->_init();
	    $userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;

		$entityText = isset($_POST['entityText'])?$this->input->post('entityText'):"";

		$APIClient = $this->load->library("APIClient");        
        $APIClient->setUserId($userId);
        $APIClient->setVisitorId(getVisitorId());
        $APIClient->setRequestData(array("entityText" => $entityText));
        $APIClient->setRequestType("post");
        $url = "AnA/getSimilarQuestions/";
     
        $jsonDecodedData =$APIClient->getAPIData($url);

        $displayData['similarQuestionsData'] = array();

        if(isset($jsonDecodedData['similarQuestions'])){
            $displayData['similarQuestionsData'] = $jsonDecodedData['similarQuestions'];    
        }
    	foreach ($displayData['similarQuestionsData'] as $key => $value) {
    		$displayData['similarQuestionsData'][$key]['url'] = getSeoUrl($value['questionId'],"question",$value['questionTitle'], array(), "NA", $value['creationDate']);
    	}
        echo $this->load->view('mAnA5/mobile/similarQuestionsIntermediatePage',$displayData);
	}

    /**
    * Function called when Edit / Add Question and Discussion and in case of Edit Tags for Posting
    * API called = AnAPost/postEntity/
    */
	public function postingQuesDisc(){
        if(!verifyCSRF()) { return false; }
		$this->_init();
    	$entityType = isset($_POST['entityType'])?$this->input->post('entityType'):"question";
	    $entityText = isset($_POST['entityText'])?$this->input->post('entityText'):"";
	    $entityDesc = isset($_POST['entityDesc'])?$this->input->post('entityDesc'):"";
	    $editEntityId = isset($_POST['editEntityId'])?$this->input->post('editEntityId'):0;
	    $tagsData = isset($_POST['tagsData'])?$this->input->post('tagsData'):"";
        $trackingPageKeyId = isset($_POST['trackingPageKeyId'])?$this->input->post('trackingPageKeyId'):'';
        $listingTypeId = isset($_POST['listingTypeId'])?$this->input->post('listingTypeId'):0;
        $listingType = isset($_POST['listingType'])?$this->input->post('listingType'):"";
        $courseId = isset($_POST['courseId'])?$this->input->post('courseId'):0;
        $tagEntityId = !empty($_POST['tagEntityId']) ? $this->input->post('tagEntityId') : 0;
        $tagEntityType = !empty($_POST['tagEntityType']) ? $this->input->post('tagEntityType') : 0;

	    $userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
        $action = isset($_POST['action'])?$this->input->post('action'):"";
	    $requestIP = $_SERVER['REMOTE_ADDR'];
	    $APIClient = $this->load->library("APIClient");        
        $APIClient->setUserId($userId);
        $APIClient->setVisitorId(getVisitorId());
        $APIClient->setRequestType("post");

        if($listingTypeId > 0 || $courseId > 0){
            $postData = array("title"=>$entityText, "entityType" => $entityType, "tags" => $tagsData,"requestIP" => $requestIP, "editEntityId" => $editEntityId,"listingTypeId"=>$listingTypeId,"listingType"=>$listingType,"courseId"=>$courseId,'action'=>$action,"trackingPageKeyId" => $trackingPageKeyId);
           
        }else{
             $postData =  array("title"=>$entityText, "entityType" => $entityType, "tags" => $tagsData,"requestIP" => $requestIP, "editEntityId" => $editEntityId,'action'=>$action,"trackingPageKeyId" => $trackingPageKeyId);
            
        }

        if($tagEntityId > 0 && is_numeric($tagEntityId) && !empty($tagEntityType))
        {
            $postData = array_merge($postData, array('entityId' => $tagEntityId,'tagEntityType' => $tagEntityType));
        }

        if(trim($entityDesc) != ""){
        	$postData['description'] = $entityDesc;
        }
        
        $APIClient->setRequestData($postData);

        $url = "AnAPost/postEntity/";
        $jsonDecodedData =$APIClient->getAPIData($url);

        // make an entry into alert_user_preference table(only during thread creation)
        if($jsonDecodedData['entityId'] && !$editEntityId){

            $this->load->library(array("Alerts_client"));
            $alertClient = new Alerts_client();
            $alertName = 'comment-'.$entityText;
            try{
                $alertResult = $alertClient->createWidgetAlert(12,$userId,8,'byComment',$jsonDecodedData['entityId'],$alertName);
                if(isset($alertResult['alert_id']) && !is_numeric($alertResult['alert_id'])){
                    error_log("Exception Occurred during setting alert pref of thread".$jsonDecodedData['entityId']." and ".$userId);
                }
            }catch(Exception $e){
                error_log("Exception Occurred during setting alert pref of thread".$jsonDecodedData['entityId']." and ".$userId);
            }
        }

        if(!$editEntityId){
        	$editEntityId = $jsonDecodedData['entityId'];
        }
        $seoUrl = getSeoUrl($editEntityId,$entityType,$entityText);
        echo $seoUrl;

	}

	public function closeQuestion()
	{
		$this->_init();

        //Get the Input variables from Post/Get

        $userId       = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;

		$questionId = empty($_POST['entityId'])?'':$this->input->post('entityId');
		$APIClient = $this->load->library("APIClient");        
        $APIClient->setUserId($userId);
        $APIClient->setVisitorId(getVisitorId());
        $APIClient->setRequestType("post");
        $APIClient->setRequestData(array("questionId"=>$questionId));
        $jsonDecodedData =$APIClient->getAPIData("AnAPost/closeQuestion/");
        $displayMsg = $jsonDecodedData['responseMessage'];
        echo json_encode($displayMsg);
        
        
	}
	public function deleteEntityFromQnA()
	{
		$this->_init();
		//Get the Input variables from Post/Get

        $userId       = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;

        $entityId	  = empty($_POST['entityId'])?'':$this->input->post('entityId');
        $threadId 	  = empty($_POST['threadId'])?'':$this->input->post('threadId');
        $entityType   = empty($_POST['entityType'])?'':$this->input->post('entityType');

        //Add Delete Entity Log
        $currentTime = date('Y-m-d H:i:s');
        $referrer = $_SERVER["HTTP_REFERER"];
        error_log("\r\nAnswer Deleted:::: Function = AnAPostMobile/deleteEntityFromQnA, AnswerId = $entityId, Time = $currentTime, Deleted By = $userId, Referrer = $referrer", 3, "/tmp/deleteAnswer.log");

        $APIClient = $this->load->library("APIClient");        
        $APIClient->setUserId($userId);
        $APIClient->setVisitorId(getVisitorId());
        $APIClient->setRequestType("post");
        $APIClient->setRequestData(array("msgId"=>$entityId,"threadId"=>$threadId,"entityType"=>$entityType));
        $jsonDecodedData =$APIClient->getAPIData("AnAPost/deleteEntityFromCMS/");
        $displayMsg = $jsonDecodedData['responseMessage'];
        echo json_encode($displayMsg);
	}

	public function postComment(){
		$this->_init();

        //Get the Input variables from Post/Get

        $userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;

		$topicId = $this->input->post('topicId');
		$parentId = $this->input->post('parentId');
        $commentText = $this->input->post('commentText');
        $requestIP = S_REMOTE_ADDR;
        $type = isset($_POST['type']) ? $_POST['type']:'question';
        $editEntityId = isset($_POST['editEntityId'])?$this->input->post('editEntityId'):'0';
        $trackingPageKeyId = isset($_POST['trackingPageKeyId'])?$this->input->post('trackingPageKeyId'):'';

        $commentShareUrl = getSeoUrl($topicId,$type,$commentText);
		//Make a CURL call to fetch the data
        $APIClient = $this->load->library("APIClient");        
        $APIClient->setUserId($userId);
        $APIClient->setVisitorId(getVisitorId());
        $APIClient->setRequestType("post");
        $APIClient->setRequestData(array("topicId"=>$topicId, "parentId" => $parentId ,"commentText"=>$commentText,"requestIP" => $requestIP,"type" => $type,"editEntityId" => $editEntityId,"trackingPageKeyId"=>$trackingPageKeyId));
        $jsonDecodedData =$APIClient->getAPIData("AnAPost/postComment");

        if($editEntityId>0){
            $displayData['commentShareUrl'] = $commentShareUrl;
        }else{
            $displayData['commentShareUrl'] = $commentShareUrl.'?referenceEntityId='.$jsonDecodedData['commentId'];
        }
        $displayDat['parentId'] = $parentId;
        $displayData['responseMessage'] = $jsonDecodedData['responseMessage'];

        echo json_encode($displayData);

	}

    public function anaPosting($layer="layer1"){
        if($layer == "layer1"){
            $this->load->view('mAnA5/mobile/questionpostingLayer');
        }
        else if($layer == "layer2"){
            $this->load->view('mAnA5/mobile/questionpostingLayerTagSuggestions'); 
        }
        else if($layer == "layer3"){
            $this->load->view('mAnA5/mobile/addMoreTagsPosting');
        }
    }
}

?>
