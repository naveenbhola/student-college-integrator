<?php
/**
 *qna rehash phase-2 part-2 start
 */

 /**
 *qna rehash phase-2 part-2 start
 */
class PowerUser extends MX_Controller {
    
    /**
     * Init Function for initialization
     */
    function init() {
        $this->load->helper(array('form', 'url','date','image','shikshaUtility'));
        $this->load->library(array('cacheLib','Poweruser_client','Acl_client'));
        $this->userStatus = $this->checkUserValidation();
    }
    
    /**
     * Function to get the Power User Data
     */
    function getPowerUserData() {
        $this->init();
        $appId = 12;
        $loggedInUserId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
        $moduleName = trim($this->input->post('moduleName'));
        $filter = trim($this->input->post('Filter'));
        $start=$this->input->post('startFrom');
        $rows=$this->input->post('countOffset');

        $userIdFieldData=$this->input->post('userIdFieldData');
        $userEmailFieldData=$this->input->post('userEmailFieldData');
        $userminReputationPointFieldData=$this->input->post('userminReputationPointFieldData');
        $usermaxReputationPointFieldData=$this->input->post('usermaxReputationPointFieldData');
        $searchTypeVal=$this->input->post('searchTypeVal');
        $reported=$this->input->post('reported');
        $filterVal=$this->input->post('filterVal');
        $parameterObj = array('powerUser' => array('offset'=>-1,'totalCount'=>0,'countOffset'=>5));
        $powerUser = new Poweruser_client();
        $resultPowerUser = $powerUser->getPowerUserInfo($appId,$loggedInUserId,$start,$rows,$moduleName,$filter,$userIdFieldData,$userEmailFieldData,$userminReputationPointFieldData,$usermaxReputationPointFieldData,$searchTypeVal,$filterVal);error_log("pranjultest==".print_r($resultPowerUser,true ));
        error_log("resultPowerUser== ".print_r($resultPowerUser,true));
        $totalResults = isset($resultPowerUser[0]['totalResults'])?$resultPowerUser[0]['totalResults']:0;
        $cmsPageArr['userInfo'] = isset($resultPowerUser[0]['results'])?$resultPowerUser[0]['results']:0;
        $parameterObj['powerUser']['offset'] = 0;
        if($totalResults)
            $parameterObj['powerUser']['totalCount'] = $totalResults;
        else
            $parameterObj['powerUser']['totalCount'] = 0;
        $Validate = $this->userStatus;
        $cmsPageArr['userminReputationPointFieldData'] = $userminReputationPointFieldData;
        $cmsPageArr['usermaxReputationPointFieldData'] = $usermaxReputationPointFieldData;
        $cmsPageArr['userEmailFieldData'] = $userEmailFieldData;
        $cmsPageArr['userIdFieldData'] = $userIdFieldData;
        $cmsPageArr['validateuser'] = $Validate;
        $cmsPageArr['parameterObj'] = json_encode($parameterObj);
        $cmsPageArr['filterSel'] = $filterVal;
        $cmsPageArr['moduleName'] = $moduleName;
        $cmsPageArr['startFrom'] = $start;
        $cmsPageArr['countOffset'] = $rows;
        $cmsPageArr['totalCount'] = $parameterObj['powerUser']['totalCount'];
        $cmsPageArr['searchTypeVal'] = $searchTypeVal;

        echo $totalResults."::".$this->load->view('enterprise/powerUserListing',$cmsPageArr);
    }
    
    /**
     * Function to set the Power user level
     */
    function setPowerUserLevel() {
        $this->init();
        $appId = 1;
        $userId = trim($this->input->post('userId'));
        $newLevelOfUser = trim($this->input->post('newLevelOfUser'));
        $email = trim($this->input->post('email'));
        $displayname = trim($this->input->post('displayname'));
        $powerUser = new Poweruser_client();
        $insertInfoIntoTable = $powerUser->insertPowerUserDetails($appId,$userId,$newLevelOfUser,$email, $displayname);
        echo $insertInfoIntoTable['result'];
    }
    
    /**
     * Function to delete the Power User Level
     */
    function deletePowerUserLevel() {
        $this->init();
        $appId = 1;
        $userId = trim($this->input->post('userId'));
        $powerUser = new Poweruser_client();
        $deleteLevelFromPowerUserTable = $powerUser->deletePowerUserLevel($appId,$userId);
        echo $deleteLevelFromPowerUserTable['result'];
    }

    /**
     * Function to insert data into questions discussion linking table
     */
    function insertIntoQuestionDiscussionLinkingTable() {
        $this->init();
        $appId = 1;
        $linkedQuestionId = trim($this->input->post('linkedQuestionId'));
        $mainQuestionId = trim($this->input->post('mainQuestionId'));
        //$userId = trim($this->input->post('userId'));
        $questionOwerId = trim($this->input->post('questionOwerId'));
        $entityType = trim($this->input->post('entityType'));
        $userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
        if($userId == 0){
        	echo "-1";
        	return;
        }
        $this->load->model('messageBoard/AnAModel');
        $linkQuestionDiscussionCount    = $this->AnAModel->getLinkedThreadsCount($entityId, $entityType, array('accepted'));
        if(($entityType == 'discussion' && $linkQuestionDiscussionCount >= 1) || ($entityType == 'question' && $linkQuestionDiscussionCount >= 10)){
        	echo '-2';
        	return;
        }
        $ifAlreadyMappingExist  = $this->AnAModel->checkIfLinkingThreadMappingExist($mainQuestionId, $linkedQuestionId, $entityType);
        if($ifAlreadyMappingExist === TRUE){
            echo '-3';
            return;
        }
        $powerUser = new Poweruser_client();
        $insertIntoQuestionDiscussionLinkingTable = $powerUser->insertIntoQuestionDiscussionLinkingTable($appId,$linkedQuestionId,$mainQuestionId,$userId,$questionOwerId,$entityType);
        echo "1";
        return;
    }
    
    /**
     * Function to change the linked question status
     */
    function changeLinkedQuestionStatus() {
        $this->init();
        $appId = 12;
        $loggedInUserId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
	/*$linkingQuestionId = trim($this->input->post('linkingQuestionId'));
	$mainQuestionId = trim($this->input->post('mainQuestionId'));
	$userId = trim($this->input->post('userId'));
	$entityType = trim($this->input->post('entityType'));*/
        $newstatus = trim($this->input->post('newstatus'));
        $msgId = trim($this->input->post('msgId'));
        $id = trim($this->input->post('id'));
        $linkedQuestionId = trim($this->input->post('linkedQuestionId'));
        $powerUser = new Poweruser_client();
        $changeLinkedQuestionStatus = $powerUser->changeLinkedQuestionStatus($appId,$id,$newstatus,$msgId,$linkedQuestionId);
        
        //Add notification of APP in redis
        if($newstatus == 'accepted'){
                $this->appNotification = $this->load->library('Notifications/NotificationContributionLib');
                $this->appNotification->addNotificationForAppInRedis('link_question',$msgId,'question',$loggedInUserId,'linked',$linkedQuestionId);
        }
                           
        echo $changeLinkedQuestionStatus['result'];
    }
    
    /**
     * Function to unlink the question
     */
    function unlinkedQuestion() {
        $this->init();
        $appId = 1;
        $linkedEntityId = trim($this->input->post('linkedEntityId'));
        $linkingEntityId = trim($this->input->post('linkingEntityId'));
        $powerUser = new Poweruser_client();
        $unlinkedQuestion = $powerUser->unlinkedQuestion($appId,$linkedEntityId,$linkingEntityId);
        echo $unlinkedQuestion['result'];

    }
    
    /**
     * Function to make sticky discussion Announcements
     */
    function makeStickyDiscussionAnnouncement() {
        $this->init();
        $appId = 1;
        $powerUserId= trim($this->input->post('powerUserId'));
        $stickyMsgId= trim($this->input->post('stickyMsgId'));
        $stickyThreadId= trim($this->input->post('stickyThreadId'));
        $categoryId= trim($this->input->post('categoryId'));
        $entityType= trim($this->input->post('entityType'));
        $status= trim($this->input->post('status'));
        $powerUser = new Poweruser_client();
        $linkedQuestion = $powerUser->makeStickyDiscussionAnnouncement($appId,$powerUserId,$stickyMsgId,$stickyThreadId,$categoryId,$entityType,$status);
        echo $linkedQuestion['result'];
    }

    /**
     * Function to change the linked discussion status
     */
   function changeLinkedDiscussionStatus() {
        $this->init();
        $appId = 12;
        $loggedInUserId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
	$newstatus = trim($this->input->post('newstatus'));
        $msgId = trim($this->input->post('msgId'));
	$stickyThreadId= $msgId-1;
        $id = trim($this->input->post('id'));
        $linkedDiscussionId = trim($this->input->post('linkedDiscussionId'));
        $powerUser = new Poweruser_client();
              //Add notification of APP in redis
        if($newstatus == 'accepted'){
                $this->appNotification = $this->load->library('Notifications/NotificationContributionLib');
                $this->appNotification->addNotificationForAppInRedis('link_discussion',$stickyThreadId,'discussion',$loggedInUserId,'linked',$linkedDiscussionId);
        }
        $changeLinkedDiscussionStatus = $powerUser->changeLinkedDiscussionStatus($appId,$id,$newstatus,$msgId,$linkedDiscussionId);
		//check if it is sticky also... if yes then remove it from sticky table because a discussion that has been linked will not be sticky

		$makeDiscussionUnsticky=$powerUser->makeDiscussionUnStickyifItIsLinked($appId,$newstatus,$stickyThreadId,$linkedDiscussionId);
                
  
        
		echo $changeLinkedDiscussionStatus['result'];
    }
    
    /**
     * Function to get the linked Discussion and questions
     */
    function getLinkedDiscussionAndQuestion(){
        $this->init();
        $appId = 12;
        $loggedInUserId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
        $moduleName = trim($this->input->post('moduleName'));
        $filterVal = trim($this->input->post('Filter'));
        $start=$this->input->post('startFrom');
        $rows=$this->input->post('countOffset');
        $parameterObj = array('powerUser' => array('offset'=>-1,'totalCount'=>0,'countOffset'=>5));
        $start=$this->input->post('startFrom');
        $rows=$this->input->post('countOffset');
        $type=$this->input->post('type');
	$userNameFieldData=$this->input->post('userNameFieldData');
	$userLevelFieldData=$this->input->post('userLevelFieldData');

        $powerUser = new Poweruser_client();
        if($type=='ques')
            $getLinkedQuestion = $powerUser->getLinkedQuestion($appId,$start,$rows,$filterVal,$userNameFieldData,$userLevelFieldData);
        else
            $getLinkedQuestion = $powerUser->getLinkedDiscussion($appId,$start,$rows,$filterVal,$userNameFieldData,$userLevelFieldData);
            
        $totalResults = isset($getLinkedQuestion[0]['totalResults'])?$getLinkedQuestion[0]['totalResults']:0;
        $cmsPageArr['userInfo1'] = isset($getLinkedQuestion[0]['results1'])?$getLinkedQuestion[0]['results1']:0;
        $cmsPageArr['userInfo2'] = isset($getLinkedQuestion[0]['results2'])?$getLinkedQuestion[0]['results2']:0;
        $cmsPageArr['userInfo3'] = isset($getLinkedQuestion[0]['results3'])?$getLinkedQuestion[0]['results3']:0;
        $parameterObj['powerUser']['offset'] = 0;
        if($totalResults)
            $parameterObj['powerUser']['totalCount'] = $totalResults;
        else
            $parameterObj['powerUser']['totalCount'] = 0;
        $cmsPageArr['parameterObj'] = json_encode($parameterObj);
        $cmsPageArr['filterSel'] = $filterVal;
        $cmsPageArr['moduleName'] = $moduleName;
        $cmsPageArr['startFrom'] = $start;
        $cmsPageArr['countOffset'] = $rows;
        $cmsPageArr['totalCount'] = $parameterObj['powerUser']['totalCount'];
	$cmsPageArr['userNameFieldData'] = $userNameFieldData;
	$cmsPageArr['userLevelFieldData'] = $userLevelFieldData;
    // $this->load->model('messageBoard/AnAModel');
    // $expertLevelsForFilter = $this->AnAModel->getExpertLevels('AnA');
    $this->load->helper('messageBoard/abuse');
    $expertLevelsForFilter = getExpertLevels();
    $cmsPageArr['expertLevelsForFilter'] = $expertLevelsForFilter;
        if($type=='ques')
            echo $totalResults."::".$this->load->view('enterprise/cms_linkedQuestions',$cmsPageArr);
        else
             echo $totalResults."::".$this->load->view('enterprise/cms_linkedDiscussions',$cmsPageArr);
    }
}
///qna rehash phase-2 part-2 end////
?>