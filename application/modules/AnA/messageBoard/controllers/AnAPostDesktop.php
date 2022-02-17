<?php

class AnAPostDesktop extends MX_Controller {

    private function _init(){
        $this->dbLibObj = DbLibCommon::getInstance('AnA');
        if($this->userStatus == ""){
            $this->userStatus = $this->checkUserValidation();
        }
    }

    public function postingIntermediatePageTagsData(){
        $requestHeader = ($_SERVER['HTTP_ORIGIN'] != null) ? $_SERVER['HTTP_ORIGIN'] : SHIKSHA_HOME;
        header("Access-Control-Allow-Origin: ".$requestHeader);
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
        header('P3P: CP="CAO PSA OUR"'); // Makes IE to support cookies
        
        //commenting below line because actual desktop flow is breaking
//        header("Content-Type: application/json; charset=utf-8");
        $this->_init();
        $userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;

        $entityType = isset($_POST['entityType'])?$this->input->post('entityType'):"question";
        $entityText = isset($_POST['entityText'])?$this->input->post('entityText'):"";
        $editEntityId = isset($_POST['editEntityId'])?$this->input->post('editEntityId'):"";
        $action = isset($_POST['action'])?$this->input->post('action'):"";
        $entityDesc = isset($_POST['entityDesc'])?$this->input->post('entityDesc'):"";
        $trackingPageKeyId = isset($_POST['trackingPageKeyId'])?$this->input->post('trackingPageKeyId'):'';


        $this->load->config('messageBoard/MessageBoardInternalConfig');
        $moderatorUserIds = $this->config->item('moderatorUserIds');
        $isModeratorEditing = false;

        if(in_array($userId, $moderatorUserIds) && $action == 'editing' && $entityType == 'question')
        {
            $isModeratorEditing = true;
        }

        $APIClient = $this->load->library("APIClient");        
        $APIClient->setUserId($userId);
        $APIClient->setVisitorId(getVisitorId());
        $APIClient->setRequestType("post");
        $APIClient->setRequestData(array("entityType"=>$entityType, "entityText" => $entityText,"editEntityId"=>$editEntityId,'action'=>$action,"description" => $entityDesc,'isModeratorEditing' => $isModeratorEditing));
        $jsonDecodedData =$APIClient->getAPIData("AnAPost/postingIntermediatePage/");

        $displayData['tagsData'] = array();

        if(isset($jsonDecodedData['tags'])){
            $displayData['tagsData'] = $jsonDecodedData['tags'];    
        }
        $displayData['entityType'] = $entityType;
        $displayData['GA_userLevel'] = $userId > 0 ? 'Logged In': 'Non-Logged In';
        //_p($displayData);
        echo $this->load->view('messageBoard/desktopNew/widgets/tagsIntermediate',$displayData);
    }

    /**
    * Function called to fetch the similar question for Add Question  for Intermediate Page
    * API called = AnA/getSimilarQuestions/
    */
    public function postingIntermediatePageSimilarQuestionsData(){
        $requestHeader = ($_SERVER['HTTP_ORIGIN'] != null) ? $_SERVER['HTTP_ORIGIN'] : SHIKSHA_HOME;
        header("Access-Control-Allow-Origin: ".$requestHeader);
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
        header('P3P: CP="CAO PSA OUR"'); // Makes IE to support cookies
        //commenting below line because actual desktop flow is breaking
        //header("Content-Type: application/json; charset=utf-8");

        
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
        $displayData['GA_userLevel'] = $userId > 0 ? 'Logged In':'Non-Logged In';
        echo $this->load->view('messageBoard/desktopNew/widgets/qPostingSimilarQues',$displayData);
    }                                                                                                                                                                                                            

    public function postingQuesDisc(){
        if(!verifyCSRF()) { return false; }
        $this->_init();
        $postEntityType = $this->input->post('entityType', true);
        $postEntityText = $this->input->post('entityText', true);
        $postEntityDesc = $this->input->post('entityDesc', true);
        $postEditEntityId   = $this->input->post('editEntityId', true);
        $postTagsData   = $this->input->post('tagsData', true);
        $postTrackingPageKeyId  = $this->input->post('trackingPageKeyId', true);
        $postListingTypeId  = $this->input->post('listingTypeId', true);
        $postListingType    = $this->input->post('listingType', true);
        $postCourseId       = $this->input->post('courseId', true);
        $postEntityId       = $this->input->post('entityId', true);
        $postTagsEntityType = $this->input->post('tagEntityType', true);

        $entityType = isset($postEntityType)?$postEntityType:"question";
        $entityText = isset($postEntityText)?$postEntityText:"";
        $entityDesc = isset($postEntityDesc)?$postEntityDesc:"";
        $editEntityId = isset($postEditEntityId)?$postEditEntityId:0;
        $tagsData = isset($postTagsData)?$postTagsData:"";
        $trackingPageKeyId = isset($postTrackingPageKeyId)?$postTrackingPageKeyId:'';
        $listingTypeId = isset($postListingTypeId)?$postListingTypeId:0;
        $listingType = isset($postListingType)?$postListingType:"";
        $courseId = isset($postCourseId)?$postCourseId:0;
        $entityId = isset($postEntityId)?$postEntityId:0;
        $tagEntityType = isset($postTagsEntityType)?$postTagsEntityType:"";
        $userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;

        // Ajax Authentication 
        if($userId == 0){
            echo SHIKSHA_ASK_HOME;
            return;
        }

        $this->load->config('messageBoard/MessageBoardInternalConfig');

        $moderatorUserIds = $this->config->item('moderatorUserIds');
        $isModeratorEditing = false;

        if(in_array($userId, $moderatorUserIds) && (!empty($editEntityId)) && $entityType == 'question')
        {
            $isModeratorEditing = true;
        }

        $action = isset($_POST['action'])?$this->input->post('action'):"";
        $requestIP = $_SERVER['REMOTE_ADDR'];
        $APIClient = $this->load->library("APIClient");        
        $APIClient->setUserId($userId);
        $APIClient->setVisitorId(getVisitorId());
        $APIClient->setRequestType("post");

        if($listingTypeId > 0 || $courseId > 0){
            $postData = array("title"=>$entityText, "entityType" => $entityType, "tags" => $tagsData,"requestIP" => $requestIP, "editEntityId" => $editEntityId,"listingTypeId"=>$listingTypeId,"listingType"=>$listingType,"courseId"=>$courseId,'action'=>$action,"trackingPageKeyId" => $trackingPageKeyId,'isModeratorEditing' => $isModeratorEditing);
           
        }else{
             $postData = array("title"=>$entityText, "entityType" => $entityType, "tags" => $tagsData,"requestIP" => $requestIP, "editEntityId" => $editEntityId,'action'=>$action,"trackingPageKeyId" => $trackingPageKeyId,'entityId'=>$entityId,'tagEntityType'=>$tagEntityType,'isModeratorEditing' => $isModeratorEditing);
            
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

    public function fetchAddMoreLayer(){

        $data['type'] = $this->input->post('entityType', true);
        
        echo $this->load->view('desktopNew/widgets/addMoreTagsLayer',$data);
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
        $displayData['responseMessage'] = $jsonDecodedData['responseMessage'];
        echo json_encode($displayData);
        
    }

    public function deleteEntityFromAnA()
    {
        if(!verifyCSRF()) { return false; }
        
        $this->_init();
        //Get the Input variables from Post/Get
        $userId       = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
        $entityId     = empty($_POST['entityId'])?'':$this->input->post('entityId');
        $threadId     = empty($_POST['threadId'])?'':$this->input->post('threadId');
        $entityType   = empty($_POST['entityType'])?'':$this->input->post('entityType');

        //Add Delete Entity Log
        $currentTime = date('Y-m-d H:i:s');
        $referrer = $_SERVER["HTTP_REFERER"];
        error_log("\r\nAnswer Deleted:::: Function = AnAPostDesktop/deleteEntityFromAnA, AnswerId = $entityId, Time = $currentTime, Deleted By = $userId, Referrer = $referrer", 3, "/tmp/deleteAnswer.log");

        $APIClient = $this->load->library("APIClient");        
        $APIClient->setUserId($userId);
        $APIClient->setVisitorId(getVisitorId());
        $APIClient->setRequestType("post");
        $APIClient->setRequestData(array("msgId"=>$entityId,"threadId"=>$threadId,"entityType"=>$entityType));
        $jsonDecodedData =$APIClient->getAPIData("AnAPost/deleteEntityFromCMS/");
        $displayData['responseMessage'] = $jsonDecodedData['responseMessage'];
        echo json_encode($displayData);
    }

    public function postAnswer(){
        if(!verifyCSRF()) { return false; }
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
            $displayData['answerEdited'] = $jsonDecodedData['editedTxt'];
            $displayData['answerShareUrl'] = $answerShareUrl.'?referenceEntityId='.$answerId;
        }
        $displayData['responseMessage'] = $jsonDecodedData['responseMessage'];

        echo json_encode($displayData);

    }

    public function postComment(){
        if(!verifyCSRF()) { return false; }
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
            $displayData['commentEdited'] = $jsonDecodedData['editedTxt'];
            $displayData['commentShareUrl'] = $commentShareUrl.'?referenceEntityId='.$editEntityId;
        }else{
            $displayData['commentShareUrl'] = $commentShareUrl.'?referenceEntityId='.$jsonDecodedData['commentId'];
        }
        $displayData['parentId'] = $parentId;
        $displayData['responseMessage'] = $jsonDecodedData['responseMessage'];

        echo json_encode($displayData);

    }      

    //below function is used for fetching report abuse layer
    function getReportAbuseLayer()
    {
        $data['msgId'] = empty($_POST['msgId'])?0:$this->input->post('msgId');
        $data['threadId'] = empty($_POST['threadId'])?0:$this->input->post('threadId');
        $data['entityType'] = empty($_POST['entityType'])?0:$this->input->post('entityType');
        $data['trackingPageKeyId'] = empty($_POST['trackingPageKeyId'])?'':$this->input->post('trackingPageKeyId');
        echo $this->load->view('desktopNew/reportAbuseLayer',$data);
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
            $temp = explode('-', $value);
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

    /*To move ana questions to listing or listing question to AnA*/
    function moveQuestionToAnAOrListings(){
	return;
        $this->_init();
        $threadId = isset($_POST['threadId'])?$this->input->post('threadId'):'0';
        $questionMoveToIns = isset($_POST['questionMoveToIns'])?$this->input->post('questionMoveToIns'):'off';
        $quesOwner = isset($_POST['quesOwner'])?$this->input->post('quesOwner'):'0';
        $requestIP = S_REMOTE_ADDR;

        if($threadId > 0){
            $isPaid = 'false';
            $this->load->library('messageboardconfig');
            $this->load->model('messageBoard/QnAModel');
            $dbHandle = $this->_loadDatabaseHandle('write');
            if($questionMoveToIns=='on'){
                    $mcourseId = isset($_POST['courseId'])?$this->input->post('courseId'):'0';

                    $this->load->builder('nationalCourse/CourseBuilder');
                    $CourseBuilder = new CourseBuilder();
                    $courseRepository = $CourseBuilder->getCourseRepository();
                    $courseObj = $courseRepository->find($mcourseId);
                    $isPaid =$courseObj->isPaid();
                    $minstId=$courseObj->getInstituteId();
                   
                    if($minstId>0){
                        $data =array('listingTypeId'=>$minstId,'listingType'=>'institute', 'requestIP'=>$requestIP);
                        $dbHandle->where(array('threadId' => $threadId ));
                        $dbHandle->update($this->messageboardconfig->messageTable,$data);
                        
                        $data =array('description'=>'');
                        $dbHandle->where(array('threadId' => $threadId ));
                        $dbHandle->update($this->messageboardconfig->messageDiscussion,$data);

                        $this->QnAModel->moveQuestionToInstitute($threadId,$mcourseId,$minstId,$isPaid);
                        if($isPaid == '1'){
                            $userDetails = $this->QnAModel->getUserDetailsPostedQues($threadId);
                            $addReqInfo['displayName'] = $userDetails['dName'];
                            $addReqInfo['contact_cell'] = $userDetails['mobile'];
                            $addReqInfo['contact_email'] = $userDetails['email'];
                            $addReqInfo['userId'] = $userDetails['userId'];
                            //$addReqInfoVars['userInfo'] = $userDetails['userId'];
                            $addReqInfo['listing_type_id'] = $mcourseId;
                            $addReqInfo['listing_type'] = 'course';
                            $addReqInfo['widget'] = '';
                            /*$addReqInfo['preferred_city'] = 
                            $addReqInfo['preferred_locality'] = */
                            $trackingKeyId = $userDetails['tracking_keyid'];

                            Modules::run('MultipleApply/MultipleApply/addLeadForMoveQuestionToListing',$quesOwner,$addReqInfo,'Asked_Question_On_Listing',$trackingKeyId);
                        }
                        $this->QnAModel->sendMailToCampusReps($mcourseId,$minstId,$threadId); 
                        $toastMsg = 'Question moved to the course: '.$mcourseId.' successfully.';
                    }else{
                        $toastMsg = 'CourseId is not valid.';
                    }               
                                                     
            }else{
                    $data =array('listingTypeId'=>'0','listingType'=>'NULL', 'requestIP'=>$requestIP);
                    $dbHandle->where(array('threadId' => $threadId ));
                    $dbHandle->update($this->messageboardconfig->messageTable,$data);           
                    $this->QnAModel->moveQuestionToAnA($threadId);
                    $toastMsg = 'Question moved to Ask & Answer successfully.';
               
            }
        }
         echo json_encode($toastMsg);
    }

    function removeCollegeFromQuestion(){
        $msgId = isset($_POST['msgId'])?$this->input->post('msgId'):'0';
        if(empty($msgId) || !is_numeric($msgId)){
            echo 0;
        }
        $this->_init();
        $this->load->library(array('v1/AnACommonLib'));
        $this->anaCommonLib = new AnACommonLib();  
        $this->anamodel = $this->load->model('messageBoard/AnAModel');

        $userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
        $this->anaCommonLib->trackEditOperation($msgId, "question", $userId);
        $this->anamodel->removeListingTypeFromQuestion($msgId);

        $result = $this->anamodel->getCollegeTagFromQuestion($msgId);
        foreach ($result as $key => $value) {
                $tagIds[]  = $value['tag_id'];    
                $tagType[] = $value['tag_type'];
        }
        foreach ($tagType as $key => $type) {
                if(strpos($type,'_parent') == false){
                    $parentTagType[] = $type.'_parent';
                }
        }
        $parentTagArr = $this->anamodel->getCollegeParentTagFromQuestion($tagIds);

        $result   = $this->anamodel->removeCollegeTag($msgId, $tagIds, $tagType, $parentTagArr, $parentTagType);
        if($result>0){
            Modules::run('search/Indexer/addToQueue', $msgId, "question");  
        }
        echo 1;
    }

}

?>
