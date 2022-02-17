<?php
/**
 * AnA Class
 * This is the class for all the APIs related to AnA Like Unanswered Tab, Question detail pages, Posting questions/answers/comments
 * @date    2015-07-27
 * @author  Ankur Gupta
 * @todo    none
*/

class AnA extends APIParent {

        private $validationObj;
        private $anaCommonLib;

        function __construct() {
                parent::__construct();
                $this->load->library(array('AnAValidationLib', 'AnACommonLib'));
                $this->validationObj = new AnAValidationLib();
                $this->anaCommonLib = new AnACommonLib();
        }

        /**
         * @desc API to get question and answer details with report abuse for question detail page. 
         * @param AuthChecksum containing the logged-in user details
         * @param start value with the Starting counter of Questions
         * @param count value with the number of answers to be fetched
         * @param sortOrder value which can be latest,oldest,upvoted
         * @param referenceAnswerId which be a entityId of a reference Answer
         * @return JSON string with HTTP Code 200 and question details,list of answers.
         * @return JSON string with HTTP Code 200 and Message Failure if User id is not valid
         * @date 2015-07-30
         * @author Yamini Bisht
         */
        
        
        function getQuestionDetailWithAnswers($questionId, $start = 0, $count = 5, $sortOrder='Upvotes', $referenceAnswerId = "0"){
                //step 1:Fetch the Input from GET/POST
                $Validate = $this->getUserDetails();
                $userId = isset($Validate['userId'])?$Validate['userId']:0;

                //step 2:validate all the fields
                if(! $this->validationObj->validateQuestionDetail($this->response, array('userId'=>$userId, 'start'=>$start, 'count'=>$count,'questionId'=>$questionId,'sortOrder'=>$sortOrder,'referenceAnswerId'=>$referenceAnswerId))){                        
                        return;
                }
                
                //Step 3: Fetch the Data from DB + Logic
              	$this->benchmark->mark('get_complete_details_start'); 
                $quesDetail =  $this->anaCommonLib->formatQuestionDetailPageData($userId,$questionId,$start,$count,$sortOrder,$referenceAnswerId);
		$this->benchmark->mark('get_complete_details_end');
                $moderatorUserId = $this->AnAModel->checkIfUserisPowerUser($userId);

                if(!empty($quesDetail['entityDetails'])){
                        $this->benchmark->mark('get_overflow_ques_start');  
                        $quesDetail['entityDetails']['overflowTabs'] = $this->getEntityOverflowTabList($userId,$questionId,'question',0,$quesDetail['entityDetails']['status'],$quesDetail['entityDetails']['hasUserReportedAbuse'],$quesDetail['entityDetails']['userId'],$moderatorUserId,$quesDetail['entityDetails']['hasUserAnswered']);
    			$this->benchmark->mark('get_overflow_ques_end');
                $this->benchmark->mark('get_overflow_ans_start');
                foreach($quesDetail['childDetails'] as $key=>$ansDetail){   
                        $quesDetail['childDetails'][$key]['overflowTabs'] = $this->getEntityOverflowTabList($userId,$ansDetail['msgId'],'answer',0,$quesDetail['entityDetails']['status'],$ansDetail['hasUserReportedAbuse'],$ansDetail['userId'],$moderatorUserId);
                
                }
			    $this->benchmark->mark('get_overflow_ans_end');
                        $quesDetail['rowsCount'] = $count;
                        
                        // set view count duration for question/answer tracking
                        $quesDetail['viewTrackParams'] = array("question" => QUESTION_VIEW_DURATION, "answer" => ANSWER_VIEW_DURATION, "trackDuration" => VIEWCOUNT_TRACKING_INTERVAL, "trackingPageType" => "threadDetailPage");
                        
                        $this->response->setBody($quesDetail);
                        
                }else{
                        $this->response->setStatusCode(STATUS_CODE_FAILURE);
                        $this->response->setResponseMsg("Question doesn't exist."); 
                        $this->response->output();
                        return;
                }
                $this->benchmark->mark('beacon_call_start');
                global $isMobileApp;
                global $isWebAPICall;
                if($isMobileApp && !$isWebAPICall){
                	modules::run('beacon/Beacon/index',rand(), '0003003', $questionId);
		}
		$this->benchmark->mark('beacon_call_end');
                
                //Step 4: Return the Response
                $this->response->output();
		                
        }
        
        /**
         * @desc API to get comment details with report abuse . 
         * @param AuthChecksum containing the logged-in user details
         * @param start value with the Starting counter of Questions
         * @param count value with the number of comments to be fetched
         * @param sortOrder value which can be latest,oldest,upvoted
         * @return JSON string with HTTP Code 200 and list of answers.
         * @return JSON string with HTTP Code 200 and Message Failure if User id is not valid
         * @date 2015-07-30
         * @author Yamini Bisht
         */
        
        function getCommentDetails($entityId, $start = 0, $count = 10, $sortOrder='latest',$referenceCommentId=0){
                
                //step 1:Fetch the Input from GET/POST
                $Validate = $this->getUserDetails();
                $userId = isset($Validate['userId'])?$Validate['userId']:0;
                
                //step 2:validate all the fields
                if(! $this->validationObj->validateCommentDetails($this->response, array('userId'=>$userId, 'start'=>$start, 'count'=>$count,'entityId'=>$entityId,'sortOrder'=>$sortOrder,'referenceCommentId'=>$referenceCommentId))){
                        
                        return;
                }
                
                //Step 3: Fetch the Data from DB + Logic
                
                $commentDetailData = $this->anaCommonLib->formatCommentDetailsPagination($entityId,$userId,$start,$count,$sortOrder,$referenceCommentId);
                $moderatorUserId = $this->AnAModel->checkIfUserisPowerUser($userId);
                
                if(!empty($commentDetailData['childDetails'])){
                        
                        foreach($commentDetailData['childDetails'] as $key=>$commentDetail){
                        
                                $commentDetailData['childDetails'][$key]['overflowTabs'] = $this->getEntityOverflowTabList($userId,$commentDetail['msgId'],'comment',0,$commentDetail['threadStatus'],$commentDetail['hasUserReportedAbuse'],$commentDetail['userId'],$moderatorUserId);
                        
                        }
                        $commentDetailData['rowsCount'] = $count;
                        
                         $this->response->setBody($commentDetailData);
                }else{
                        $this->response->setStatusCode(STATUS_CODE_FAILURE);
                        $this->response->setResponseMsg("Entity doesn't exist."); 
                        $this->response->output();
                        return;
                }
                
                //Step 4: Return the Response
                $this->response->output();
                
        }
        
        
         /**
         * @desc API to get discussion and comment details with report abuse for question detail page. 
         * @param AuthChecksum containing the logged-in user details
         * @param start value with the Starting counter of Questions
         * @param count value with the number of answers to be fetched
         * @param sortOrder value which can be latest,oldest,upvoted
         * @return JSON string with HTTP Code 200 and question details,list of answers.
         * @return JSON string with HTTP Code 200 and Message Failure if User id is not valid
         * @date 2015-07-31
         * @author Yamini Bisht
         */
        
        
        function getDiscussionDetailWithComments($discussionId, $start = 0, $count = 10,$sortOrder='latest',$referenceCommentId=0){
                
                //step 1:Fetch the Input from GET/POST
                $Validate = $this->getUserDetails();
                $userId = isset($Validate['userId'])?$Validate['userId']:0;

                //step 2:validate all the fields
                if(! $this->validationObj->validateDiscussionDetails($this->response, array('userId'=>$userId, 'start'=>$start, 'count'=>$count,'discussionId'=>$discussionId,'sortOrder'=>$sortOrder,'referenceCommentId'=>$referenceCommentId))){                        
                        return;
                }
                
                //Step 3: Fetch the Data from DB + Logic
                $this->load->model('messageBoard/AnAModel');
                $discussionDetailPageData = $this->anaCommonLib->formatDiscussionDetailPageData($discussionId,$userId,$start,$count,$sortOrder,$referenceCommentId);
                
                if(empty($discussionDetailPageData['childDetails'])){
                        $discussionDetailPageData['childDetails'] = NULL;
                }
                
                if(!empty($discussionDetailPageData['entityDetails'])){
                         
                         $moderatorUserId = $this->AnAModel->checkIfUserisPowerUser($userId);
                         $discussionDetailPageData['entityDetails']['overflowTabs'] = $this->getEntityOverflowTabList($userId,$discussionId,'discussion',$discussionDetailPageData['entityDetails']['isLinked'],$discussionDetailPageData['entityDetails']['status'],$discussionDetailPageData['entityDetails']['hasUserReportedAbuse'],$discussionDetailPageData['entityDetails']['userId'],$moderatorUserId);
                         
                         
                         foreach($discussionDetailPageData['childDetails'] as $key=>$commentDetail){
                        
                                $discussionDetailPageData['childDetails'][$key]['isLinked'] = $discussionDetailPageData['entityDetails']['isLinked'];
                                $discussionDetailPageData['childDetails'][$key]['overflowTabs'] = $this->getEntityOverflowTabList($userId,$commentDetail['msgId'],'comment',$discussionDetailPageData['entityDetails']['isLinked'],$discussionDetailPageData['entityDetails']['status'],$commentDetail['hasUserReportedAbuse'],$commentDetail['userId'],$moderatorUserId);
                        
                        }
                        $discussionDetailPageData['rowsCount'] = $count;
                         
                         $this->response->setBody($discussionDetailPageData);
                }else{
                        $this->response->setStatusCode(STATUS_CODE_FAILURE);
                        $this->response->setResponseMsg("Discussion doesn't exist."); 
                        $this->response->output();
                        return;
                }
                
                modules::run('beacon/Beacon/index',rand(), '0003003', $discussionId);
                
                //Step 4: Return the Response
                $this->response->output();
                
        }

        /**
         * @desc API to fetch Homepage News feed of the User
         * @param AuthChecksum containing the logged-in user details
         * @param start value with the Starting counter of Questions
         * @param count value with the number of questions to be fetched
         * @return JSON string with HTTP Code 200 and News feed items
         * @return JSON string with HTTP Code 200 and Message Failure if User id is not valid
         * @date 2015-08-07
         * @author Ankur Gupta
         */
	function getHomepageData($start = 0, $pageNumber = 0, $filter = 'home'){
		//step 1:Fetch the Input from GET/POST
        $Validate = $this->getUserDetails();
        $userId = isset($Validate['userId'])?$Validate['userId']:'';
        $visitorId = isset($Validate['visitorId'])?$Validate['visitorId']:'';

        //step 2:validate all the fields
        if(! $this->validationObj->validateHomepageTab($this->response, array('userId'=>$userId, 'start'=>$start, 'pagenumber'=>$pageNumber))){
        	return;
        }

        //Step 3: Fetch the Data from DB + Logic

        if($pageNumber == 0){
        	//A. Get User details
        	$returnArray['userDetails'] = $this->anaCommonLib->getUserDetails($userId);
        	$returnArray['canStartDiscussion'] = $returnArray['userDetails']['levelId'] >= 11 ? true : false;
        	
        	//F. check user profile Builder info
        	$this->load->library('UserProfileBuilderLib');
        	$this->profileBuilderObj = new UserProfileBuilderLib();
        	$returnArray['profileBuilderInfo'] = $this->profileBuilderObj->getProfileBuilderInfo($userId);
        }
		
        global $isWebAPICall;
        $totalResponsePerRequest = -1;
        if($isWebAPICall == 1 && $pageNumber == 0){
            if($start == 0){
                $totalResponsePerRequest = 3;
            }else{
                $totalResponsePerRequest = 7;
            }
        }
		//B. Get Homepage entities
		$homePageRelatedData				= $this->anaCommonLib->getHomepageData($userId, $start, $pageNumber, $filter, 0, $visitorId, $totalResponsePerRequest);
		$returnArray['homepage'] 			= $homePageRelatedData['homeFeed'];
		$returnArray['nextPaginationIndex'] = $homePageRelatedData['nextPaginationIndex'];
		
		if($homePageRelatedData['newHomeFeedItems'] > 5){
			$returnArray['moreStoriesCount']	= $homePageRelatedData['newHomeFeedItems'];
		}
		
		if($pageNumber == 1 && $filter == 'home'){
			//C. Get Tags Recomemndations : Commented below code as it will not go live in this release 26th Nov 2015
			$returnArray['tagsRecommendations'] = $this->anaCommonLib->getTagsRecommendations($userId);
			
			//E. Set some common parameters: Commented below code as it will not go live in this release 26th Nov 2015
			$returnArray['showTagsRecommendationsAtPostion'] = 1;
		}

		if($pageNumber == 2 && $filter == 'home'){
			//D. Get User Recommendations : Commented below code as it will not go live in this release 26th Nov 2015
			$returnArray['userRecommendations'] = $this->anaCommonLib->getUserRecommendations($userId);
			$returnArray['showUserRecommendationsAtPostion'] = 1;
		}

		// set view count duration for question/answer tracking
        $returnArray['viewTrackParams'] = array("question" => QUESTION_VIEW_DURATION, "answer" => ANSWER_VIEW_DURATION, "trackDuration" => VIEWCOUNT_TRACKING_INTERVAL, "trackingPageType" => "anaHomepage");

		$this->response->setBody($returnArray);

        // track home feed served
        $this->_trackAPI($returnArray, "HomefeedTrack");

        $noDataAvailableMsg = array("home" => "No Stories for you.", "discussion" => "No Discussions for you.", "unanswered" => "No Un-answered questions for you.");
        if(empty($returnArray['homepage'])){
            $this->response->setResponseMsg($noDataAvailableMsg[$filter]);
        }

        //Step 4: Return the Response
        $this->response->output();
	}                        


         /**
         * @desc API to get question/discussions which have been Shortlisted by user
         * @param AuthChecksum containing the logged-in user details
         * @param entityType which can be question/discussion/both
         * @return JSON string with HTTP Code 200 and Shortlisted Questions/Discussions
         * @return JSON string with HTTP Code 200 and Message Failure if User id is not valid
         * @date 2015-08-14
         * @author Ankur Gupta
         */
        function getShortlistedEntities($entityType = 'both', $start = 0, $count = 10){

                //step 1:Fetch the Input from GET/POST
                $Validate = $this->getUserDetails();
                $userId = isset($Validate['userId'])?$Validate['userId']:'';

                //step 2:validate all the fields
                if(! $this->validationObj->validateGetShortlistEntity($this->response, array('userId'=>$userId, 'entityType'=>$entityType, 'start'=>$start, 'count'=>$count))){
                        return;
                }

                //Step 3: Fetch the Data from DB + Logic
                $this->load->model('messageBoard/AnAModel');
                $shortlistData = $this->AnAModel->getShortlistedEntities($Validate['userId'],$entityType, $start, $count);

                if(!empty($shortlistData)){
                         $this->response->setBody($shortlistData);
                }else{
                        $this->response->setResponseMsg("No Shortlisted entity exist.");
                        $this->response->output();
                        return;
                }

                //Step 4: Return the Response
                $this->response->output();

        }
        
        /**
         * @desc API to get overFlow Tab list of an entity(delete/edit/close/reportAbuse)
         * @param AuthChecksum containing the logged-in user details
         * @param entityId which can be id of an entity
         * @param entityType which can be question/discussion/answer/comment/reply
         * @return JSON string with HTTP Code 200 and Shortlisted Questions/Discussions
         * @return JSON string with HTTP Code 200 and Message Failure if User id is not valid
         * @date 2015-08-14
         * @author Ankur Gupta
         */
        
        function getEntityOverflowTabList($userId,$entityId,$entityType,$isLinked=0,$status='live',$hasUserReportedAbuse,$ownerId,$moderatorUserId,$hasUserAlreadyAnswered){

            $userId = $userId ? $userId : 0;
            
            //step 1:validate all the fields
                if(! $this->validationObj->validateOverFlowTabData($this->response, array('userId'=>$userId, 'entityType'=>$entityType,'entityId'=>$entityId))){
                        
                        return;
                }
                
            //Step 2:fetch course,countries and level list
                
                $this->load->config('anaConfig',TRUE);
                
                if($status == 'closed' || $isLinked){
                         $this->EntityOverFlowTabs = $this->config->item('closedLinkedOverflowTabs','anaConfig');
                }else{
                         $this->EntityOverFlowTabs = $this->config->item('overflowTabs','anaConfig');
                }
                
                $this->load->model('messageBoard/AnAModel');
                
				if($userId>0){
					//$ownerId = $this->AnAModel->getAnswerOwnerUserId($entityId);
		            if(($entityType =='question' && $userId != $ownerId)|| $entityType =='discussion'){
		                    
		                   $isMarkedAnsLater = $this->AnAModel->checkIfAnswerLaterMarked($userId,$entityId,$entityType);
		                    
		            }
		            if($userId == $ownerId){
		                   $result = $this->EntityOverFlowTabs[$entityType]['owner'];
		                   
		            }else if($userId != $ownerId){
                        //$moderatorUserId = $this->AnAModel->checkIfUserisPowerUser($userId);
                        if(($userId == $moderatorUserId && $moderatorUserId != '') || ($userId == 11)){
                                $result = $this->EntityOverFlowTabs[$entityType]['moderator'];          
                        }else{
                            $result = $this->EntityOverFlowTabs[$entityType]['otherUser'];
                        }  

						if($hasUserReportedAbuse){
                            //remove report abuse option incase of already reported abuse
                             foreach($result as $key=>$val){
                                       if($val['id']==105 || $val['id']==109 || $val['id']==112 || $val['id']==116 || $val['id']==120){
                                               unset($result[$key]);
                                               $result=array_values($result);
                                       }
                               }
                        }
                	}  

		             //remove answer/comment later option from tab incase of already answered or already marked later
		            if(!empty($isMarkedAnsLater) || $hasUserAlreadyAnswered){
		                          foreach($result as $key=>$val){
		                            if($val['id']==117 || $val['id']==106){
		                                    unset($result[$key]);
		                                    $result=array_values($result);
		                            }
		                    }
		             }      
    
            }else{
                    $result = $this->EntityOverFlowTabs[$entityType]['otherUser'];
            }
        
                 return $result;
            
        }
        
        
        
        /**
         * @desc API to get reply details with report abuse . 
         * @param AuthChecksum containing the logged-in user details
         * @param start value with the Starting counter of Questions
         * @param count value with the number of comments to be fetched
         * @return JSON string with HTTP Code 200 and list of answers.
         * @return JSON string with HTTP Code 200 and Message Failure if User id is not valid
         * @date 2015-09-14
         * @author Yamini Bisht
         */
        
        function getReplyDetails($commentId, $start = 0, $count = 10,$isLinked = 0){
                //step 1:Fetch the Input from GET/POST
                $Validate = $this->getUserDetails();
                $userId = isset($Validate['userId'])?$Validate['userId']:0;
                
                //step 2:validate all the fields
                if(! $this->validationObj->validateReplyDetails($this->response, array('userId'=>$userId, 'start'=>$start, 'count'=>$count,'commentId'=>$commentId))){
                        
                        return;
                }
		$this->load->model('messageBoard/AnAModel');
                $moderatorUserId = $this->AnAModel->checkIfUserisPowerUser($userId);
                
                //Step 3: Fetch the Data from DB + Logic
                $replyDetailData = $this->anaCommonLib->formatReplyDetailsPagination($commentId,$userId,$start,$count);
                
                if(!empty($replyDetailData['childDetails'])){
                        
                        foreach($replyDetailData['childDetails'] as $key=>$replyDetail){
                                
                                        $replyDetailData['childDetails'][$key]['overflowTabs'] = $this->getEntityOverflowTabList($userId,$replyDetail['msgId'],'reply',$isLinked,'live',$replyDetail['hasUserReportedAbuse'],$replyDetail['userId'],$moderatorUserId);
                                
                        
                        }
                        $replyDetailData['rowsCount'] = $count;

                         $this->response->setBody($replyDetailData);
                }else{
                        $this->response->setStatusCode(STATUS_CODE_FAILURE);
                        $this->response->setResponseMsg("Entity doesn't exist."); 
                        $this->response->output();
                        return;
                }

                //Step 4: Return the Response
                $this->response->output();
        
        }

        function getUserProfileData(){

                //step 1:Fetch the Input from GET/POST
                $Validate = $this->getUserDetails();
                $userId = isset($Validate['userId'])?$Validate['userId']:'';

                if(empty($userId) || empty($Validate)){
                        $response = ResponseFactory::createResponse(ResponseFactory::RESPONSE_NOT_FOUND);
                        $response->setResponseMsg("User-id Missing");
                        $response->output();
                        exit(0);
                }
                $returnArray = array();

                $this->load->library('UserProfileBuilderLib');
                $this->profileBuilderObj = new UserProfileBuilderLib();
                $returnArray['profileBuilderInfo'] = $this->profileBuilderObj->getProfileBuilderInfo($userId);
                
                $this->response->setBody($returnArray);

                //Step 4: Return the Response
                $this->response->output();
        }

        function testHomeFeed($userId,$type = 'home',$start){
                if(!in_array($type, array('home','discussion','unanswered')))
                {
                        echo "not allowed";die;
                }
                $data = $this->anaCommonLib->getHomepageData($userId, $start, 100, $type);
                _p($data);
        }

        function getSimilarQuestions($entityText=""){
                //step 1:Fetch the Input from GET/POST
                $Validate = $this->getUserDetails();
                $userId = isset($Validate['userId'])?$Validate['userId']:'';
                
                if(isset($_POST['entityText'])){
                    $entityText = $this->input->post('entityText');
                }

                //step 2:validate all the fields
                if(! $this->validationObj->validateSimilarQuestions($this->response, array('userId'=>$userId, 'entityText'=>$entityText))){

                        return;
                }

                //Step 3: Fetch the Data from DB + Logic
                $finalArray = array();
                $finalArray['similarQuestions'] = $this->anaCommonLib->getSimilarQuestions($entityText, $count = 6);
                $this->response->setBody($finalArray);

                //Step 4: Return the Response
                $this->response->output();
        }

        /**
         * @desc API to fetch User details
         * @param AuthChecksum containing the logged-in user details
         * @return JSON string with HTTP Code 200 and News feed items
         * @return JSON string with HTTP Code 200 and Message Failure if User id is not valid
         * @date 2015-12-18
         * @author Ankur Gupta
         */
        function getUserBasicDetails(){
                //step 1:Fetch the Input from GET/POST
                $Validate = $this->getUserDetails();
                $userId = isset($Validate['userId'])?$Validate['userId']:'';
                //step 2:validate all the fields
                if(! $this->validationObj->validateHomepageTab($this->response, array('userId'=>$userId, 'start'=>0, 'pagenumber'=>1))){
                        return;
                }

                //Step 3: Fetch the Data from DB + Logic
                $returnArray = $this->anaCommonLib->getUserDetails($userId);

                $this->response->setBody($returnArray);

                //Step 4: Return the Response
                $this->response->output();
        }
        
         /**
         * @desc API to fetch list of users who have done an action like follow,upvote
         * @param AuthChecksum containing the logged-in user details
         * @return JSON string with HTTP Code 200
         * @return JSON string with HTTP Code 200 and Message Failure if entity id is not valid
         * @date 2016-01-07
         * @author Yamini Bisht
         */
        function getListOfUsersBasedOnAction($entityId,$start = 0,$count = 10,$actionType,$entityType='question'){
                
                //step 1:Fetch the Input from GET/POST
                $Validate = $this->getUserDetails();
                $userId = isset($Validate['userId'])?$Validate['userId']:'';
                //step 2:validate all the fields
                if(! $this->validationObj->validateListOfUsersBasedOnAction($this->response, array('userId'=>$userId, 'entityId'=>$entityId, 'start'=>$start,'count'=>$count,'actionType'=>$actionType,'entityType'=>$entityType))){
                        return;
                }
                //Step 3: Fetch the Data from DB + Logic
                $returnArray['users'] = $this->anaCommonLib->formatListOfUsersDetailActionBased($userId,$entityId,$start,$count,$actionType,$entityType);
                 $returnArray['rowsCount'] = $count;
                
                $this->response->setBody($returnArray);

                //Step 4: Return the Response
                $this->response->output();
        }

        function getAnAMostActiveUsers(){

                //step 1:Fetch the Input from GET/POST : none
                $Validate = $this->getUserDetails();
                $userId = isset($Validate['userId'])?$Validate['userId']:'';

                //step 2:validate all the fields : none
     
                //Step 3: Fetch the Data from DB + Logic
                $returnArray['mostActiveUsers'] = $this->anaCommonLib->getAnAMostActiveUsers($userId);
                
                $this->response->setBody($returnArray);

                //Step 4: Return the Response
                $this->response->output();   
        }

        function getThreadsMostActiveUser($threadId, $threadType){

            //step 1:Fetch the Input from GET/POST 
            $Validate = $this->getUserDetails();
            $userId = isset($Validate['userId'])?$Validate['userId']:'';

            //step 2:validate all the fields : none

            //Step 3: Fetch the Data from DB + Logic
            $returnArray['activeUsersData'] = array();
            $returnArray['tagDetails'] = array();
            
            $tagDetails = $this->anaCommonLib->getThreadTagForMostActiveUsers($threadId, $threadType);
            
            if($tagDetails){
                $this->load->library('v1/TagCommonLib');
                $tagsCommonLib = new TagCommonLib();
                $activeUsersData = $tagsCommonLib->getTopContributorsForTag($tagDetails['tagId'], $tagDetails['tagName'], $userId);

                $returnArray['activeUsersData'] = $activeUsersData;
                $returnArray['tagDetails'] = $tagDetails;
            }

            $this->response->setBody($returnArray);

            //Step 4: Return the Response
            $this->response->output();   
        }

        /**
         * @desc API to fetch ANA Homepage Widget of the User basis he is contributor/not
         * @param AuthChecksum containing the logged-in user details
         * @param start value with the Starting counter of Questions
         * @param count value with the number of questions to be fetched
         * @return JSON string with HTTP Code 200 and News feed items
         * @return JSON string with HTTP Code 200 and Message Failure if User id is not valid
         * @date 2019-07-22
         * @author Ankur Gupta
         */
        function getANAWidgetFeed($start = 0, $pageNumber = 0){

            //step 1:Fetch the Input from GET/POST
	        $Validate = $this->getUserDetails();
        	$userId = isset($Validate['userId'])?$Validate['userId']:'';
	        $visitorId = isset($Validate['visitorId'])?$Validate['visitorId']:'';

	        //step 2:validate all the fields
        	if(! $this->validationObj->validateHomepageTab($this->response, array('userId'=>$userId, 'start'=>$start, 'pagenumber'=>$pageNumber))){
                	return;
        	}

		    //Step 3: Check if user is contributor. If yes, get Home data, else get unanswered data
	        $this->load->model('messageBoard/AnAModel');
		    if($this->AnAModel->isUserContributor($userId)){
    			$returnArray['isContributor'] = "true";
	    		$filter = 'unanswered';
	   	    }
		    else{
		    	$returnArray['isContributor'] = "false";
			    $filter = 'home';
		    }

	        global $isWebAPICall;
        	$totalResponsePerRequest = -1;
	        if($isWebAPICall == 1 && $pageNumber == 0){
        	    if($start == 0){
                	$totalResponsePerRequest = 3;
	            }else{
        	        $totalResponsePerRequest = 7;
	            }
        	}

                //B. Get Homepage entities
                $homePageRelatedData                            = $this->anaCommonLib->getHomepageData($userId, $start, $pageNumber, $filter, 0, $visitorId, $totalResponsePerRequest);

		    if($returnArray['isContributor'] == "true"){
	                $returnArray['unanswered']          = $homePageRelatedData['homeFeed'];
	                $returnArray['answered']			= array();
	        	$returnArray['viewTrackParams'] 		= array("question" => QUESTION_VIEW_DURATION, "answer" => ANSWER_VIEW_DURATION, "trackDuration" => VIEWCOUNT_TRACKING_INTERVAL, "trackingPageType" => "anaWidget");
    		}
	    	else{
	                $returnArray['answered']            = $homePageRelatedData['homeFeed'];
	                $returnArray['unanswered']			= array();
	        	$returnArray['viewTrackParams'] 		= array("question" => QUESTION_VIEW_DURATION, "answer" => ANSWER_VIEW_DURATION, "trackDuration" => VIEWCOUNT_TRACKING_INTERVAL, "trackingPageType" => "anaWidget");
		    }
            $returnArray['unansweredLink'] = SHIKSHA_ASK_HOME."/unanswers";
                $this->response->setBody($returnArray);

	        $noDataAvailableMsg = array("home" => "No Stories for you.", "discussion" => "No Discussions for you.", "unanswered" => "No Un-answered questions for you.");
	        if(empty($returnArray['homepage'])){
        	    $this->response->setResponseMsg($noDataAvailableMsg[$filter]);
        	}

	        //Step 4: Return the Response
        	$this->response->output();
        }
        
}
