<?php
/**
 * AnA Post Class
 * This is the class for all the APIs related to AnA Posting Like Posting Question/Answer, Editing, Deleting
 * @date    2015-08-08
 * @author  Ankur Gupta
 * @todo    none
*/

class AnAPost extends APIParent {

        private $validationObj;
        private $anaCommonLib;

        function __construct() {
                parent::__construct();
                $this->load->library(array('AnAValidationLib', 'AnACommonLib','message_board_client'));
                $this->validationObj = new AnAValidationLib();
                $this->anaCommonLib = new AnACommonLib();
                $this->msgBoardClient = new Message_board_client();
        }

        /**
         * @desc API to Add/Edit an answer to a question
         * @param POST param topicId which is the question id
         * @param POST param answerText which will be the answer text
         * @param POST param requestIP which will be the IP Address of the device
         * @param POST param action which will be action (add/edit)
         * @param POST param answerId which will be the answerId in case of Edit.
         * @return JSON string with HTTP Code 200 and Success/Failure message
         * @date 2015-08-05
         * @author Ankur Gupta
         */
        function postAnswer(){
        
            //step 1:Fetch the Input from GET/POST
            $Validate = $this->getUserDetails();
            $userId = isset($Validate['userId'])?$Validate['userId']:'';
            
            //We have 4 entities in the System namely, Question/Discussion, Answer, Comment, Reply.
            //Here, Question -> Answer -> Comment
            //And, Discussion -> Comment -> Reply
            //In case of Answer, threadId = parentId = questionId and mainAnswerId = 0
            //In case of Comment, threadId = questionId and parentId = answerId and mainAnswerId = answerId
            //In case of Reply, threadId = questionId and parentId = answerId and mainAnswerId = commentId
            $topicId = $this->input->post('topicId');
            $answerText = $this->input->post('answerText');
            $requestIP = isset($_POST['requestIP'])?$this->input->post('requestIP'):'';
            $action = isset($_POST['action'])?$this->input->post('action'):'add';
            $answerId = isset($_POST['answerId'])?$this->input->post('answerId'):0;
            $pageName = isset($_POST['currentPage'])?$this->input->post('currentPage'):'homePage';
            $tracking_key_id = isset($_POST['trackingPageKeyId'])?$this->input->post('trackingPageKeyId'):'';
            
			if(empty($tracking_key_id))
			{

            	if($pageName == 'homePage'){
                $tracking_key_id = '561';
           		 }else if($pageName == 'questionDetailPage'){
                	$tracking_key_id = '563';
	            }else if($pageName == 'unansweredTabTagDetailPage'){
    	            $tracking_key_id = '564';
    	        }else if($pageName == 'questionTabTagDetailPage'){
    	            $tracking_key_id = '562';
    	        }else if($pageName == 'unansweredTab'){
    	            $tracking_key_id = '631';
    	        }
			}            
            //step 2:validate all the fields
	    $this->benchmark->mark('validate_answer_start');
            if(! $this->validationObj->validatePostAnswer($this->response, array('userId'=>$userId, 'topicId'=>$topicId, 'answerText'=>$answerText,'action'=>$action,'answerId'=>$answerId,'requestIP'=>$requestIP))){
                    return;
            }
            $this->benchmark->mark('validate_answer_end');
            //Step 3: Fetch the Data from DB + Logic
            //A. Check if user has already given this answer in the Past 7 Days
            $proceedAhead = true;
            $this->load->model('QnAModel');
	    $this->benchmark->mark('check_same_answer_start');
            $response = $this->QnAModel->checkForSameAnswerInPreviousSevenDays($userId,trim($answerText),$topicId);
	    $this->benchmark->mark('check_same_answer_end');
            if($response>0){    //If user has already Posted a similar answer in the Past 7 Days
                    $returnValue = 'SAMEANS';
                    $proceedAhead = false;
            }
        
            //B. Check for any Spam in the Entity. If none found, Post the Answer
            if(Modules::run('messageBoard/MsgBoard/spamCheck', $answerText, $requestIP) == "true"){
                $returnValue = 1001001;
            }
            else if($proceedAhead){   //Start Posting the entries
                if($action == "edit"){
			$this->benchmark->mark('track_edit_start');
			$this->anaCommonLib->trackEditOperation($answerId, 'answer', $userId);
			$this->benchmark->mark('track_edit_end');
                        $msgDetailsArray = array();
                        $msgDetailsArray['msgId'] = $answerId;
                        $msgDetailsArray['msgTxt'] = $answerText;
                        $msgDetailsArray['requestIP'] = $requestIP;
                        $msgDetailsArray['threadId'] = $topicId;
			$this->benchmark->mark('edit_answer_start');
                        $returnValue = $this->msgBoardClient->editMsgDetails($appId,$msgDetailsArray,$userId);
			$this->benchmark->mark('edit_answer_end');
                        $returnValue = $returnValue['Result'];
			$this->benchmark->mark('get_ra_start');
            $editDetails = $this->AnAModel->getReportAbuseEntityData($answerId,'answer');
            $answerOwnerId = $this->AnAModel->getOwnerIdOfAnswer($answerId);
            if(!empty($answerOwnerId) && $answerOwnerId > 0 && ($answerOwnerId == $userId)){
                $this->AnAModel->updateEditStatusOfAnswer($answerId);
            }
			$this->benchmark->mark('get_ra_end');
                        $editedMsg = $editDetails[0]['entityTitle'];
                        
                }else{
			$this->benchmark->mark('post_answer_start');
                        $returnValue = $this->msgBoardClient->postReply($appId,$userId,$answerText,$topicId,$topicId,$requestIP,$fromOthers = "user",$displayName = "",$mainAnsId = "0",$tracking_key_id);
			$this->benchmark->mark('post_answer_end');
                }
            }
        
            //C. Now, check the returned value
            if($returnValue == "1001001"){  //Spam Entity
                $this->response->setStatusCode(STATUS_CODE_FAILURE);
                $this->response->setResponseMsg("Invalid Answer.");                        
            }
            else if(is_numeric($returnValue)){  //Answer Successfully Posted
                $this->response->setResponseMsg("Answer posted successfully."); 
                $this->response->setBody(array('answerId'=>$returnValue));
		$this->benchmark->mark('index_question_auto_sugestor_start');
                //Modules::run('indexer/NationalIndexer/indexQuestionForAutosuggestor', $topicId);
		$this->benchmark->mark('index_question_auto_sugestor_end');
            }else if($action == "edit" && $returnValue == 'Edited'){
                $this->response->setResponseMsg("Answer edited successfully."); 
                $this->response->setBody(array('editedTxt'=>$editedMsg));
           }else if($returnValue != 'Edited'){   //Some Error
                switch($returnValue){
                    case 'SUQ': $errorMessage = "You cannot answer your own question.";
                                break;
                    case 'MTOA': $errorMessage = "You cannot answer more than once on the same question.";
                                break;
                    case 'NOREP': $errorMessage = "We have blocked some of your app privileges due to unauthorized activities performed by you in the past. Email moderator@shiksha.com to know more.";
                                break;
                    case 'OUCE': $errorMessage = "You cannot edit other user's answer";
                                break;
                    case 'ARAE': $errorMessage = "You cannot edit an already rated answer.";
                                break;
                    case 'SABA': $errorMessage = "You cannot edit an answer rated as Best answer.";
                                break;
                    case 'ARPAE': $errorMessage = "You cannot edit an answer which has comments.";
                                break;
                    case 'SAMEANS': $errorMessage = "You have already given a similar answer.";
                                break;
                    default: $errorMessage = "Some error occured. Please try again.";
                                break;                        
                }
                $this->response->setStatusCode(STATUS_CODE_FAILURE);
                $this->response->setResponseMsg($errorMessage);                            
            }
            
            
            //Step 4: Return the Response
            $this->response->output();    
        }

        /**
         * @desc API to Add a comment to a question/discussion
         * @param POST param topicId which is the question id
         * @param POST param comment Text which will be the comment text
         * @param POST param requestIP which will be the IP Address of the device
         * @param POST param type which will be question/discussion
         * @param POST param parentId which will be the answerId in case of question comment and discussion id in case of discussion comment
         * @return JSON string with HTTP Code 200 and Success/Failure message
         * @date 2015-08-05
         * @author Ankur Gupta
         */
        function postComment(){
        
            //step 1:Fetch the Input from GET/POST
            $Validate = $this->getUserDetails();
            $userId = isset($Validate['userId'])?$Validate['userId']:'';
            
            //We have 4 entities in the System namely, Question/Discussion, Answer, Comment, Reply.
            //Here, Question -> Answer -> Comment
            //And, Discussion -> Comment -> Reply
            //In case of Question Comment, threadId = topicId and parentId = answerId
            //In case of Discussion Comment, threadId = topicId = discussionId and parentId = discussionId
            //In case of Discussion Reply, threadId = topicId = discussionId and parentId = commentId
            $topicId = $this->input->post('topicId');
            $parentId = $this->input->post('parentId');
            $commentText = $this->input->post('commentText');
	        $requestIP = isset($_POST['requestIP'])?$this->input->post('requestIP'):'';
	        $type = isset($_POST['type']) ? $_POST['type']:'question';
	        $editEntityId = isset($_POST['editEntityId']) ? $this->input->post('editEntityId') : 0;
            $pageName = isset($_POST['currentPage']) ? $this->input->post('currentPage') : 'homePage';

            //below line is used for conversion tracking keyid 
            $tracking_key_id = isset($_POST['trackingPageKeyId'])?$this->input->post('trackingPageKeyId'):'';
            
            $entityType = 'comment';	
            if($type == "discussion" && $topicId != $parentId){
                $entityType = 'reply';
            }
            
            if(empty($tracking_key_id))
            {
                if($type == 'question'){
                    if($pageName == 'questionDetailPage'){
                            $tracking_key_id = '565';
                    }else if($pageName == 'answerViewMorePage'){
                            $tracking_key_id = '629';
                    }else if($pageName == 'answerCommentPage'){
                            $tracking_key_id = '637';
                    }
                }else if($type == 'discussion'){
                    if($entityType == 'comment'){
                            if($pageName == 'homePage'){
                                    $tracking_key_id = '567';
                            }else if($pageName == 'discussionTabTagDetailPage'){
                                    $tracking_key_id = '568';
                            }else if($pageName == 'discussionDetailPage'){
                                    $tracking_key_id = '569';
                            }else if($pageName == 'discussionTab'){
                                    $tracking_key_id = '570';
                            }else if($pageName == 'questionTabTagDetailPage')
                                    $tracking_key_id = '794';

                    }else if($entityType =='reply'){
                            if($pageName == 'discussionDetailPage'){
                                 $tracking_key_id = '571';   
                            }else if($pageName == 'commentViewMorePage'){
                                 $tracking_key_id = '630';   
                            }else if($pageName == 'commentReplyPage'){
                                 $tracking_key_id = '636';   
                            }
                    }
                }    
            }
            
            
            //step 2:validate all the fields
	    if($type == "discussion" && $topicId != $parentId){
                if(! $this->validationObj->validatePostReply($this->response, array('userId'=>$userId, 'topicId'=>$topicId, 'commentText'=>$commentText,'type'=>$type,'parentId'=>$parentId,'requestIP'=>$requestIP,'editEntityId'=>$editEntityId))){
                        return;
                }
	    }
	    else{
	        if(! $this->validationObj->validatePostComment($this->response, array('userId'=>$userId, 'topicId'=>$topicId, 'commentText'=>$commentText,'type'=>$type,'parentId'=>$parentId,'requestIP'=>$requestIP,'editEntityId'=>$editEntityId))){
        		return;
            	}
	    }
        
            //Step 3: Fetch the Data from DB + Logic
            //A. Check for any Spam in the Entity. If none found, Post the Comment
            if(Modules::run('messageBoard/MsgBoard/spamCheck', $commentText, $requestIP) == "true"){
                $returnValue = 1001001;;
            }
            else{   //Start Posting the entries

		if($editEntityId > 0){
			$entityType = 'comment';	
			if($type == "discussion" && $topicId != $parentId){
				$entityType = 'reply';
			}
			$this->anaCommonLib->trackEditOperation($editEntityId, $entityType, $userId);
		        $this->load->model('messageBoard/AnAModel');
		        $returnValue = $this->AnAModel->editCommentReply(array('msgTxt'=>$commentText,'msgId'=>$editEntityId,'requestIP'=>$requestIP));
                        $editDetails = $this->AnAModel->getReportAbuseEntityData($editEntityId,'comment');
                        $editedMsg = $editDetails[0]['entityTitle'];
		}
		else{
			if($type == "discussion"){
				//Fetch the Main Answer Id of the Discussion before Posting Comment
				$this->load->model('messageBoard/AnAModel');
				$mainAnsId = $this->AnAModel->getDiscussionMainAnsId($topicId);
			
				if($topicId == $parentId){	//In case of Discussion Comment, the ParentId will be same as mainAnsId
					$parentId = $mainAnsId;
				}
			
				//Now, Post the comment/reply
				$returnValue = $this->msgBoardClient->postReply($appId = 12,$userId,$commentText,$topicId,$parentId,$requestIP,$fromOthers = "discussion",$displayName = "",$mainAnsId,$tracking_key_id);
			}
			else{
				$returnValue = $this->msgBoardClient->postReply($appId = 12,$userId,$commentText,$topicId,$parentId,$requestIP,$fromOthers = "user",$displayName = "",$parentId,$tracking_key_id);
			}
		}
            }
        
            //C. Now, check the returned value
            if($returnValue == "1001001"){  //Spam Entity
                $this->response->setStatusCode(STATUS_CODE_FAILURE);
                $this->response->setResponseMsg("Invalid Comment.");                        
            }
            else if(is_numeric($returnValue)){  //Comment Successfully Posted
                $this->response->setResponseMsg(ucfirst($entityType)." posted successfully."); 
                $this->response->setBody(array('commentId'=>$returnValue));
            }
            else if($editEntityId > 0 && $returnValue){  //Comment Successfully Edited
                $this->response->setResponseMsg(ucfirst($entityType)." edited successfully."); 
                $this->response->setBody(array('message'=>'Comment Edited','editedTxt'=>$editedMsg));
            }
            else{   //Some Error
                switch($returnValue){
                    case 'NOREP': $errorMessage = "We have blocked some of your app privileges due to unauthorized activities performed by you in the past. Email moderator@shiksha.com to know more.";
                                break;
                    default: $errorMessage = "Some error occured. Please try again.";
                                break;                        
                }
                $this->response->setStatusCode(STATUS_CODE_FAILURE);
                $this->response->setResponseMsg($errorMessage);                            
            }
            
            //Add notification of APP in redis
                /*if($this->response->getStatusCode() == 0 && $editEntityId>0 && $type=='discussion' && $entityType=='comment'){
                        //$moderatorUserId = $this->AnAModel->checkIfUserisPowerUser($userId);
                        //if($user_id == $moderatorUserId){
                                $this->appNotification = $this->load->library('Notifications/NotificationContributionLib');
                                $this->appNotification->addNotificationForAppInRedis('edit_comment',$topicId,'discussion',$userId,'comment',$editEntityId);         
                        //}
                        
                }*/
            
            //Step 4: Return the Response
            $this->response->output();    
        }

        
        /**
         * @desc API to Close a question
         * @param POST param questionId which is the question id
         * @return JSON string with HTTP Code 200 and Success/Failure message
         * @date 2015-08-05
         * @author Ankur Gupta
         */
        function closeQuestion(){
        
            //step 1:Fetch the Input from GET/POST
            $Validate = $this->getUserDetails();
            $userId = isset($Validate['userId'])?$Validate['userId']:'';
            $questionId = $this->input->post('questionId');        
            
            //step 2:validate all the fields
            if(! $this->validationObj->validateCloseQuestion($this->response, array('userId'=>$userId, 'questionId'=>$questionId))){
                    return;
            }
        
            //Step 3: Send the Input to Backend and make the changes
            $resultOfClose = $this->msgBoardClient->closeDiscussion($appId = 12,$questionId,$userId);
            $closeDiscussion = $resultOfClose['Result'];
            
        
            //Step 4: Return the Response
            $this->response->setResponseMsg("Question closed successfully."); 
            $this->response->output();    
        }
	
        /**
         * @desc API to Share a question/discussion/answer
         * @param POST param entityId which is the entity id
         * @param POST param entityType which is the entity type
         * @param POST param destination which is the third-party site on which the content is shared
         * @return JSON string with HTTP Code 200 and Success/Failure message
         * @date 2015-08-05
         * @author Ankur Gupta
         */
        function shareEntity(){
        
            //step 1:Fetch the Input from GET/POST
            $Validate = $this->getUserDetails();
            $userId = isset($Validate['userId'])?$Validate['userId']:'';
            $entityId = $this->input->post('entityId');
	    $entityType = $this->input->post('entityType');
	    $destination = $this->input->post('destination');
            
            //step 2:validate all the fields
            if(! $this->validationObj->validateShareEntity($this->response, array('userId'=>$userId, 'entityId'=>$entityId, 'entityType'=>$entityType, 'destination'=>$destination))){
                    return;
            }
        
            //Step 3: Send the Input to Backend and make the changes
            $this->load->model('messageBoard/AnAModel');
            $result = $this->AnAModel->shareEntity($userId, $entityId, $entityType, $destination);
        
            //Step 4: Return the Response
            $this->response->output();    
        }
        
        
        /**
         * @desc API to set rating for an entity
         * @param POST param entityId which is the entity id
         * @param POST param digVal which is the value to represent digup/digdown
         * @param POST param pageType which is the product to represent the module
         * @return JSON string with HTTP Code 200 and Success/Failure message
         * @date 2015-08-06
         * @author Yamini Bisht
         */
        
        function setEntityRating(){
                
                //step 1:Fetch the Input from GET/POST
                $Validate = $this->getUserDetails();
                $userId = isset($Validate['userId'])?$Validate['userId']:'';
                $appId = 12;
                $entityId = (isset($_POST['entityId'])) ? $this->input->post('entityId') : '';
                $entityType = (isset($_POST['entityType'])) ? $this->input->post('entityType') : '';
                $digVal = (isset($_POST['digVal'])) ? $this->input->post('digVal') : '';
                $pageType = (isset($_POST['pageType']) && $_POST['pageType']!='') ? $this->input->post('pageType') : 'qna';
                $pageName = (isset($_POST['currentPage'])) ? $this->input->post('currentPage') : 'homePage';
				$tracking_key_id = isset($_POST['trackingPageKeyId'])?$this->input->post('trackingPageKeyId'):'';
                $isLoginFlow = isset($_POST['isLoginFlow'])?$this->input->post('isLoginFlow'):FALSE;
                if($isLoginFlow == 'true'){
                	$isLoginFlow = TRUE;
                }
                
		 if($entityType == "answer"){
                    $fromWhere = 'question';

                }
                else if($entityType == 'comment'){
                    $fromWhere = 'discussion';
                }


                if(empty($tracking_key_id))
                {
                if($entityType == 'answer'){
                        $fromWhere = 'question';
                        
                        if($pageName == 'homePage'){
                                if($digVal == '1'){
                                     $tracking_key_id = '572'; 
                                }else{
                                     $tracking_key_id = '575';   
                                }
                                        
                        }else if($pageName == 'questionTabTagDetailPage'){
                                if($digVal == '1'){
                                     $tracking_key_id = '573'; 
                                }else{
                                     $tracking_key_id = '576';   
                                }
                                
                        }else if($pageName == 'questionDetailPage'){
                                if($digVal == '1'){
                                     $tracking_key_id = '574'; 
                                }else{
                                     $tracking_key_id = '577';   
                                }
                                
                        }else if($pageName == 'answerViewMorePage'){
                                if($digVal == '1'){
                                     $tracking_key_id = '632'; 
                                }else{
                                     $tracking_key_id = '633';   
                                }
                                
                        }
                }else if($entityType == 'comment'){
                        $fromWhere = 'discussion';
                        
                        if($pageName == 'homePage'){
                                if($digVal == '1'){
                                     $tracking_key_id = '578'; 
                                }else{
                                     $tracking_key_id = '593';   
                                }
                                        
                        }else if($pageName == 'discussionTabTagDetailPage'){
                                if($digVal == '1'){
                                     $tracking_key_id = '597'; 
                                }else{
                                     $tracking_key_id = '598';   
                                }
                                
                        }else if($pageName == 'discussionDetailPage'){
                                if($digVal == '1'){
                                     $tracking_key_id = '580'; 
                                }else{
                                     $tracking_key_id = '595';   
                                }
                                
                        }else if($pageName == 'discussionTab'){
                                if($digVal == '1'){
                                     $tracking_key_id = '579'; 
                                }else{
                                     $tracking_key_id = '594';   
                                }
                                
                        }else if($pageName == 'commentViewMorePage'){
                                if($digVal == '1'){
                                     $tracking_key_id = '634'; 
                                }else{
                                     $tracking_key_id = '635';   
                                }
                        }else if($pageName == 'questionTabTagDetailPage')
                                {
                                    if($digVal == '1')    
                                    {
                                        $tracking_key_id = '795';
                                    }
                                    else
                                    {
                                        $tracking_key_id = '796';
                                    }
                                }

                        }
                }
                
                
                //Step 2:Validate all the fields
                if( ! $this->validationObj->validateRatingDataForAnA($this->response,  array('userId'=>$userId,
												'entityId'=>$entityId,
											       'digVal'=>$digVal,
                                                                                               'pageType'=>$pageType,
                                                                                               'entityType'=>$entityType)) ){
                    return;
                }

                //Step 3: 
                
                //a.check the digValue
                
                if($digVal != 0 && $digVal!=1){
                        
                        $this->response->setStatusCode(STATUS_CODE_FAILURE);
                        $this->response->setResponseMsg('dig value is not correct');
                        
                }else{
                
                        $digResult     = $this->msgBoardClient->updateDigVal($appId,$userId,$entityId,$digVal,$pageType,$fromWhere,$tracking_key_id,$isLoginFlow);
                        $resultOfDig   = $digResult['Result'];
                        $threadOwnerId = $digResult['threadOwnerId'];
                        
                
                        //b. Now, check the returned value
                        if($resultOfDig == 'success'){

                                // MAB-1492 : show rating layer if thread owner has upvoted an answer in his/her thread
                                if($threadOwnerId == $userId && $digVal==1 && $entityType == "answer"){

                                    $smartRatingLayer = array();
                                    $smartRatingLayer['showRatingLayer'] = true;
                                    $smartRatingLayer['ratingLayerDaysInterval'] = $this->config->item('smartRatingLayerDaysInterval');
                                    $smartRatingLayer['smartRatingLayerText'] = $this->config->item('smartRatingLayerText');

                                    $this->response->addBodyParam("smartRatingLayer", $smartRatingLayer);
                                }
                                
                                $confirmMsg = "Thanks for rating this ".$entityType;
                                $this->response->setStatusCode(STATUS_CODE_SUCCESS);
                                $this->response->setResponseMsg($confirmMsg);
                                
                                       
                        }else if($resultOfDig != ' '){   //Some Error
                                switch($resultOfDig){
                                    case 'SUCE': $errorMessage = "You can not rate your own ".$entityType.'.';
                                                break;
                                    case 'na': $errorMessage = "You have already rated this ".$entityType.'.';
                                                break;
                                    case 'NOREP': $errorMessage = "We have blocked some of your app privileges due to unauthorized activities performed by you in the past. Email moderator@shiksha.com to know more.";
                                                break;
                                    default: $errorMessage = "Some error occured.Unable to rate this ".$entityType.'.';
                                                break;
                        	}
                                $this->response->setStatusCode(STATUS_CODE_FAILURE);
                                $this->response->setResponseMsg($errorMessage);
                        }
        	}
                 
                //Step 4: Return the Response
		$this->response->output();
                
                
        }
        
        
             /**
         * @desc API to delete an entity
         * @param POST param msgId which is the entity id
         * @param POST param threadId which is the question id
         * @param POST param userId which is the entity owner id
         * @param POST param entityType which represnt question/answer/comment
         * @return JSON string with HTTP Code 200 and Success/Failure message
         * @date 2015-08-06
         * @author Yamini Bisht
         */
        
        function deleteEntityFromCMS(){
                
                //step 1:Fetch the Input from GET/POST
                $this->load->model('messageBoard/AnAModel');
                
                $Validate = $this->getUserDetails();
                $userId = isset($Validate['userId'])?$Validate['userId']:'';
                $msgId = (isset($_POST['msgId'])) ? $this->input->post('msgId') : '';
                $threadId = (isset($_POST['threadId'])) ? $this->input->post('threadId') : '';
                $entityType = (isset($_POST['entityType'])) ? $this->input->post('entityType') : '';
                $ownerUserId = $this->AnAModel->getOwnerIdOfEntity($msgId);
                
                //Step 2:Validate all the fields
                if( ! $this->validationObj->validateDeleteEntityParams($this->response,  array('userId'=>$userId,
												'msgId'=>$msgId,
											       'threadId'=>$threadId,
                                                                                               'ownerUserId'=>$ownerUserId,
                                                                                               'entityType'=>$entityType)) ){
                    return;
                }

                //check if user is an entity owner/moderator/cms-admin
                $moderatorUserId = $this->AnAModel->checkIfUserisPowerUser($userId);
                if($userId != $ownerUserId && $userId != $moderatorUserId && $userId != '11'){
                        $this->response->setResponseMsg("Unsuccessful");
                        $this->response->setStatusCode(STATUS_CODE_FAILURE);
                        $this->response->output();
                    return;
                }

                //Step 3: Update the Data In DB + Logic        
                $appId = 12;
                
                if($entityType!= 'question'){
                        
                        $deleteFromCMS = 0;
                        
                        if($entityType == 'discussion'){
                                
                                $msgId = $this->AnAModel->getDiscussionMainAnsId($msgId);
                                
                        }
                        $resultOfDelete = $this->msgBoardClient->deleteCommentFromCMS($appId,$msgId,$threadId,$ownerUserId);
                        $deleteFromCMS = $resultOfDelete['Result'];
                     	//Indexing calls will be made frmo Backend 
			/*  
                        modules::run('search/Indexer/addToQueue', $threadId, 'discussion', 'delete');
                        modules::run('search/Indexer/addToQueue', $threadId, 'discussion', 'index');

                        if($entityType == 'answer'){
			    $this->benchmark->mark('index_question_auto_sugestor_start');
                            Modules::run('indexer/NationalIndexer/indexQuestionForAutosuggestor', $threadId);
			    $this->benchmark->mark('index_question_auto_sugestor_end');
                        }
			*/
	
                        $result = array('userValidate' => $userId,
                                        'result' =>$deleteFromCMS,
                                        'msgId' =>$msgId);
                        
                        $this->response->setBody($result);
                        
                        
                }else{
                
                        $response = $this->msgBoardClient->deleteTopicFromCMS($appId,$msgId,$status='deleted');
			/*
                        if($entityType == 'question'){
			    $this->benchmark->mark('index_question_auto_sugestor_start');
                            Modules::run('indexer/NationalIndexer/indexQuestionForAutosuggestor', $msgId);
			    $this->benchmark->mark('index_question_auto_sugestor_end');
                        }
			*/
                        
                        $result = array('userValidate' => $userId,
                                        'result' =>$response,
                                        'msgId' =>$msgId);
                        
                        $this->response->setBody($result);
											       
                }
                
                
                //Step 4: Return the Response
                $this->response->setResponseMsg(ucfirst($entityType)." deleted successfully.");
		$this->response->output();
                
        }
        
        function getReportAbuseFormData(){
                
                //step 1:Fetch the Input from GET/POST
                $appId = 1;
                $module = "QuestionAnswer";
                
                $Validate = $this->getUserDetails();
                $userId = isset($Validate['userId'])?$Validate['userId']:'';
                
                 //Step 2:Validate all the fields
                if( ! $this->validationObj->validateReportAbuseFormData($this->response,  array('userId'=>$userId
                                                                                               )) ){
                    return;
                }
                
                //Step 2: Fetch the Data from DB + Logic
               
                $Result['reportAbuseReasonList'] = $this->msgBoardClient->getReportAbuseForm($appId,$module);
                
                $this->response->setBody($Result);
                
                //Step 3: Return the Response
		$this->response->output();
                
        }
        
        function setReportAbuseReason(){
                
                //step 1:Fetch the Input from GET/POST
                
                $this->load->model('messageBoard/AnAModel');
                $this->load->helper('shikshautility');
                
                $Validate = $this->getUserDetails();
                $userId = isset($Validate['userId'])?$Validate['userId']:'';
                
                $msgId = $this->input->post('msgId');
                $ownerId = $this->AnAModel->getOwnerIdOfEntity($msgId);
                $threadId = $this->input->post('threadId');
                $reasonIdList = $this->input->post('chosenReasonList');
                $reasonIdText = (isset($_POST['chosenReasonText'])) ? $this->input->post('chosenReasonText') : '';
                $entityTypeShown = (isset($_POST['entityTypeReportAbuse'])) ? $this->input->post('entityTypeReportAbuse') : 'question';
                $otherReasonText = (isset($_POST['otherReasonText'])) ? $this->input->post('otherReasonText') : '';
                $pageName = (isset($_POST['currentPage'])) ? $this->input->post('currentPage') : 'homePage';

                //below line is used for conversion tracking keyid 
                $tracking_key_id = isset($_POST['trackingPageKeyId'])?$this->input->post('trackingPageKeyId'):'';

                $entityName = getAbuseEntityName($entityTypeShown);
                
                if($entityTypeShown == 'question' && $pageName == 'questionDetailPage'){
                        $tracking_key_id = '581';
                }else if($entityTypeShown == 'answer' && $pageName == 'questionDetailPage'){
                        $tracking_key_id = '582';
                }else if($entityTypeShown == 'comment' && $pageName == 'answerCommentPage'){
                        $tracking_key_id = '583';
                }else if($entityTypeShown == 'discussion' && $pageName == 'discussionDetailPage'){
                        $tracking_key_id = '584';
                }else if($entityTypeShown == 'discussion Comment' && $pageName == 'discussionDetailPage'){
                        $tracking_key_id = '585';
                }else if($entityTypeShown == 'discussion Reply' && $pageName == 'commentReplyPage'){
                        $tracking_key_id = '586';
                }
                
                $reasonTextArray = json_decode($reasonIdText,true);
                
                $reasonTextString = '';
                foreach($reasonTextArray['chosenReasonText'] as $chosenReasonText){
                        
                        $reasonTextString .= ($reasonTextString=='')?'<b>'.$chosenReasonText['Title'].'</b> - '.$chosenReasonText['Content']:':<b>'.$chosenReasonText['Title'].'</b> - '.$chosenReasonText['Content'];
                }
                
                                                          
                
                if($this->input->post('abuseReason') == 6)
                {
                        $otherReason = $this->input->post('chosenReasonText');
                }
                $appId = 12;
                $resultOfAbuse = 0;
                
                 //Step 2:Validate all the fields
                if( ! $this->validationObj->validateReportAbuseReasonData($this->response,  array('userId'=>$userId,
												'msgId'=>$msgId,
												'threadId'=>$threadId,
                                                                                               'chosenReasonList'=>$reasonIdList,
                                                                                               'chosenReasonText'=>$reasonIdText,
                                                                                               'entityTypeReportAbuse'=>$entityTypeShown,
                                                                                               'otherReasonText'=>$otherReasonText
                                                                                               )) ){
                    return;
                }
                
                //Step 3: Send the Input to Backend and make the changes                
		$proceedAhead = true;

                //Check if RI is above 0. Only in case of Yes, we will proceed further
                $res = json_decode($this->msgBoardClient->getUserReputationPoints($appId,$userId));
                if($res[0]->reputationPoints<=0 && $res[0]->reputationPoints!='9999999'){
                        $errorMessage = "We have blocked some of your app privileges due to unauthorized activities performed by you in the past. Email moderator@shiksha.com to know more.";
                        $this->response->setStatusCode(STATUS_CODE_FAILURE);
                        $this->response->setResponseMsg($errorMessage);
			$proceedAhead = false;
                }

		//Get the points for abuse based on user level
		if($proceedAhead){
                $userLevel = $this->msgBoardClient->getUserLevel($appId,$userId,"AnA");
                if(is_array($userLevel))
                {
                        $this->load->helper('messageBoard/abuse');
                        $abuseRating = getAbusePointsFromLevelId($userLevel[0]['levelId']);
                        $userLevel = ($userLevel[0]['Level']!='')?$userLevel[0]['Level']:'Beginner-Level 1';
                        $entityType = $entityTypeShown;
                        
                        if($userLevel == 'Beginner-Level 1' && $abuseRating == 0){
                              $abuseRating = 1;
                        }
                            
                        $resultOfAbuse = $this->msgBoardClient->setAbuseRecord($appId,$userId,$userLevel,$abuseRating,$msgId,$reasonIdList,$entityType,$threadId,$otherReasonText,$tracking_key_id);
                            
                        if(!(is_array($resultOfAbuse))){
                            $results = 0;
                        }
                        else
                        {
                                $results = $resultOfAbuse[0]['results'];
            
                                //If abuse flag is set then set the entity as deleted
                                if($resultOfAbuse[0]['delete'] == 1 && $entityType == 'question'){
                                      $deleteResult = $this->msgBoardClient->deleteTopicFromCMS($appId,$msgId,'abused');
                                      $this->appNotification = $this->load->library('Notifications/NotificationContributionLib');
                                        $this->load->model('AnAModel');
                                        $reportAbuseEntityData = $this->AnAModel->getReportAbuseEntityData($msgId);
                                        $reportAbuseUserData = $this->AnAModel->getReportAbuseUserData($msgId);
                      
                                        foreach($reportAbuseUserData as $key=>$val){ 
                                             $reportAbuser[] = $val['userId'];
                                             $reportAbuseDate[] = $val['creationDate'];   
                                        }
                                        
                                        if($reportAbuseEntityData[0]['fromOthers'] == 'user'){
                                                $threadType = 'question';
                                                $reportAbuseThreadData = $this->AnAModel->getReportAbuseThreadData($threadId);
                                        }else if($reportAbuseEntityData[0]['fromOthers'] == 'discussion'){
                                                $threadType = $reportAbuseEntityData[0]['fromOthers'];
                                                $reportAbuseThreadData = $this->AnAModel->getReportAbuseThreadData($threadId+1);
                                        }
                                                          
                                         $this->appNotification->addAutoDeleteNotificationToRedis($entityName,$reportAbuseEntityData[0]['entityTitle'],$threadType,$reportAbuser,$reportAbuseDate,$ownerId,$reportAbuseThreadData[0]['threadTitle']);
                                         
                                         //Add the entry in Redis for Personalized Homepage
                                         if(strtolower($entityTypeShown) == 'question' || strtolower($entityTypeShown) == 'discussion'){
                                              $this->load->library('common/personalization/UserInteractionCacheStorageLibrary');
                                              $this->userinteractioncachestoragelibrary->deleteEntity($ownerId,$threadId,$threadType);
                                         }
                                }
                                else if($resultOfAbuse[0]['delete'] == 1){
                                      $deleteResult = $this->msgBoardClient->deleteCommentFromCMS($appId,$msgId,$threadId,$ownerId,'abused');
                                      $this->appNotification = $this->load->library('Notifications/NotificationContributionLib');
                                        $this->load->model('AnAModel');
                                        $reportAbuseEntityData = $this->AnAModel->getReportAbuseEntityData($msgId);
                                        $reportAbuseUserData = $this->AnAModel->getReportAbuseUserData($msgId);
                      
                                        foreach($reportAbuseUserData as $key=>$val){ 
                                             $reportAbuser[] = $val['userId'];
                                             $reportAbuseDate[] = $val['creationDate'];   
                                        }
                                        
                                        if($reportAbuseEntityData[0]['fromOthers'] == 'user'){
                                                $threadType = 'question';
                                                $reportAbuseThreadData = $this->AnAModel->getReportAbuseThreadData($threadId);
                                        }else if($reportAbuseEntityData[0]['fromOthers'] == 'discussion'){
                                                $threadType = $reportAbuseEntityData[0]['fromOthers'];
                                                $reportAbuseThreadData = $this->AnAModel->getReportAbuseThreadData($threadId+1);
                                        }
                                      
                                        $this->appNotification->addAutoDeleteNotificationToRedis($entityName,$reportAbuseEntityData[0]['entityTitle'],$threadType,$reportAbuser,$reportAbuseDate,$ownerId,$reportAbuseThreadData[0]['threadTitle']);
                                        
                                        //Add the entry in Redis for Personalized Homepage
                                         if(strtolower($entityTypeShown) == 'question' || strtolower($entityTypeShown) == 'discussion'){
                                              $this->load->library('common/personalization/UserInteractionCacheStorageLibrary');
                                              $this->userinteractioncachestoragelibrary->deleteEntity($ownerId,$threadId,$threadType);
                                         }else if(strtolower($entityTypeShown) == 'answer' || $entityTypeShown == 'discussion Comment' || $entityTypeShown == 'discussionComment'){
                                              $this->load->library('common/personalization/UserInteractionCacheStorageLibrary');
                                              $this->userinteractioncachestoragelibrary->deleteEntity($ownerId,$threadId,$threadType,$entityTypeShown);	
                                         }
                                }
                                
                                //Send the mail to the users for abuse and/or deleted
                                global $isWebAPICall;
                                if($results=='1' && $isWebAPICall==1)
                                {
                                  if($resultOfAbuse[0]['delete'] == 1)
                                   modules::run('messageBoard/MsgBoard/sendAbuseMail',$ownerId,$userId,$msgId,$entityType,$reasonTextString,true);
                                  else
                                    modules::run('messageBoard/MsgBoard/sendAbuseMail',$ownerId,$userId,$msgId,$entityType,$reasonTextString,false);
                                }
            
                        }
                          
                          
                        if($results != '1'){
                            //$entityType = strtolower(preg_replace('/(?<!\ )[A-Z]/', ' $0', $entityType));
                                if($entityType == 'discussionComment')
                                {
                                    $entityType = 'comment';
                                }
                                elseif($entityType == 'discussionReply')
                                {
                                    $entityType = 'reply';
                                }
                                switch($results){
                                        case 'SUCE': $errorMessage = "You can not report abuse your own ".$entityType.'.';
                                              break;
                                }
                                $this->response->setStatusCode(STATUS_CODE_FAILURE);
                                $this->response->setResponseMsg($errorMessage);
                        }
                        else{                      
                                $result = array('userValidate' => $userId,
                                                    'result' =>$results,
                                                );
                                
                                $this->response->setResponseMsg("Your response has been recorded. Thank you for your efforts in keeping shiksha clean");
                                $this->response->setBody($result);
                        }
                }
                else{
                        $errorMessage = "User could not be retrived";
                        $this->response->setStatusCode(STATUS_CODE_FAILURE);
                        $this->response->setResponseMsg($errorMessage);                        
                }
		}
                //Step 4: Return the Response
		$this->response->output();        
        }


        /**
         * @desc API to Shortlist a question/discussion. By shortlist we mean, answer later a question OR comment later a discussion
         * @param POST param entityId which is the entity id
         * @param POST param entityType which is the entity type
         * @return JSON string with HTTP Code 200 and Success/Failure message
         * @date 2015-08-14
         * @author Ankur Gupta
         */
        function shortlistEntity(){

            //step 1:Fetch the Input from GET/POST
            $Validate = $this->getUserDetails();
            $userId = isset($Validate['userId'])?$Validate['userId']:'';
            $entityId = $this->input->post('entityId');
            $entityType = $this->input->post('entityType');
            //below line is used for MIS Tracking for answer Later/comment Later
            $trackingPageKeyId = isset($_POST['trackingPageKeyId'])?$this->input->post('trackingPageKeyId'):'';
            $currentPage = isset($_POST['currentPage'])?$this->input->post('currentPage'):'';
            $currentWidget = isset($_POST['currentWidget'])?$this->input->post('currentWidget'):'';
	        $status = isset($_POST['status'])?$this->input->post('status'):'live';

            //below if condtion will execute when calling comes from mobile App
            if(empty($trackingPageKeyId))
            {
                if($entityType == 'question')
                    $trackingPageKeyId = 790; //answer Later From questionDetailPage
                elseif($entityType == 'discussion')
                    $trackingPageKeyId = 791; //comment Later from discussionDetailPage
            }

            //step 2:validate all the fields
            if(! $this->validationObj->validateShortlistEntity($this->response, array('userId'=>$userId, 'entityId'=>$entityId, 'entityType'=>$entityType, 'status'=>$status))){
                    return;
            }

            //Step 3: Send the Input to Backend and make the changes
            $this->load->model('messageBoard/AnAModel');
            $result = $this->AnAModel->shortlistEntity($userId, $entityId, $entityType, $status,$trackingPageKeyId);

            //Step 4: Return the Response
            $this->response->output();
        }

        /**
         * @desc API to fetch the details on the Intermediate Page of Question/Discussion posting. we will have to return the suggested Tags and Simiilar questions
         * @param POST param entityText which is the Title of the Question/Discussion
         * @param POST param entityType which is the entity type. This can be question/discussion
         * @param POST param editEntityId - entityId to be edited. Else, it will be unset
         * @return JSON object with HTTP Code 200 and Suggested Tags + Similar questions
         * @date 2015-08-20
         * @author Ankur Gupta
         */
	function postingIntermediatePage(){
            //step 1:Fetch the Input from GET/POST
            $Validate = $this->getUserDetails();
            $userId = isset($Validate['userId'])?$Validate['userId']:'';
            $entityText = $this->input->post('entityText');
            $entityType = isset($_POST['entityType']) ? $this->input->post('entityType') : 'question';
	    $editEntityId = isset($_POST['editEntityId']) ? $this->input->post('editEntityId') : '0';
	    $action = isset($_POST['action']) ? $this->input->post('action') : 'editEntity';
	    $description = isset($_POST['description']) ? $this->input->post('description') : '';
        $isModeratorEditing = isset($_POST['isModeratorEditing']) ? $this->input->post('isModeratorEditing') : false;

            if($entityType != 'question')
            {
                $isModeratorEditing = false;
            }

            //step 2:validate all the fields
            if(! $this->validationObj->validateEntityType($this->response, $entityType)){
                    return;
            }
            if(! $this->validationObj->validateIntermediatePage($this->response, array('userId'=>$userId, 'entityText'=>$entityText, 'entityType'=>$entityType,'editEntityId'=>$editEntityId, 'description'=>$description))){
                    return;
            }

            //Step 3: Send the Input to Backend and make the changes
	    $finalArray = array();

	    //A. Get the Tags for the Question/Discussion text
            $this->load->library('Tagging/TaggingLib');
            $this->TaggingLib = new TaggingLib();
            $data[] = $entityText;
	    $this->benchmark->mark('get_tag_suggestions_start');
	    if($action != "editTag"){	//In case of Edit Tag, we do not need to findd any suggestion. Simply, return all the Tags found in DB
	            $suggestions = $this->TaggingLib->showTagSuggestions($data,'normal',$isModeratorEditing);
	    }
	    $this->benchmark->mark('get_tag_suggestions_end');

	    //B. Now, for each of these Tag suggestions, fetch the Tag details and also handle the conflict cases (https://infoedge.atlassian.net/browse/MAB-110)
	    $this->benchmark->mark('get_tag_details_start');
            if(is_array($suggestions)){
                $suggestionsDetails = $this->TaggingLib->getTagDetails($suggestions,$isModeratorEditing);
		if(isset($suggestionsDetails['tags']) && is_array($suggestionsDetails['tags']) && count($suggestionsDetails['tags'])>0){
			$finalArray['tags'] = $suggestionsDetails['tags'];
		}
            }
	    $this->benchmark->mark('get_tag_details_start');

	    //C. Get the Similar Questions for this Text only while Posting New Question
	    if($entityType == 'question' && $editEntityId=="0"){
		//$finalArray['similarQuestions'] = $this->anaCommonLib->getSimilarQuestions($entityText, $count = 6);
	    }

            //D. In case of Edit Question, we need to show the Manual Tags also which the user had attached with the Question/Discussion
	    //But, in case of Edit Tags, return all the Tags found in DB
	    $this->benchmark->mark('get_tag_array_start');
	    if($editEntityId > 0 && $action != "editTag"){

                $classificationArray = array('manual');
                if($isModeratorEditing)
                {
                    $classificationArray = array('manual','manual_parent');
                }

            	$manualTags = $this->TaggingLib->getTagsByClassification($editEntityId, $classificationArray);
            	if(isset($finalArray['tags'])){
                    	$finalArray['tags'] = array_merge($finalArray['tags'],$manualTags);
			$finalArray['tags'] = $this->anaCommonLib->setUniqueTags($finalArray['tags']);
            	}
            	else{
                    	$finalArray['tags'] = $manualTags;
            	}
	    }
	    else if($editEntityId > 0 && $action == "editTag"){
		$finalArray['tags'] = $this->TaggingLib->getAllTagsOfEntity($editEntityId, $entityType);
	    }
	    $this->benchmark->mark('get_tag_array_end');

	    $this->response->setBody($finalArray);

            //Step 4: Return the Response
            $this->response->output();
	}           


        /**
         * @desc API to Post a Question/Discussion
         * @param POST param title which is the Title of the Question/Discussion
         * @param POST param description which is the entity description
         * @param POST param tags - Object of tags which needs to be associated with the Entity
         * @param POST param entityType - Can be question/discussion
         * @param POST param editEntityId - entityId to be edited. Else, it will be unset
         * @return JSON object with HTTP Code 200 and entityId which is posted
         * @date 2015-08-20
         * @author Ankur Gupta
         */
	function postEntity(){
            //step 1:Fetch the Input from GET/POST
            $extraParam = array();
            $Validate = $this->getUserDetails();
            $userId = isset($Validate['userId'])?$Validate['userId']:'';
            $title = $this->input->post('title');
            $description = isset($_POST['description']) ? $this->input->post('description') : '';
            $tags = isset($_POST['tags']) ? $this->input->post('tags') : '';
            $requestIP = isset($_POST['requestIP']) ? $this->input->post('requestIP') : '';
            $entityType = isset($_POST['entityType']) ? $this->input->post('entityType') : 'question';
	        $editEntityId = isset($_POST['editEntityId']) ? $this->input->post('editEntityId') : '0';
	        $action = isset($_POST['action']) ? $this->input->post('action') : 'editEntity';
            $pageName = isset($_POST['currentPage']) ? $this->input->post('currentPage') : 'homePage';
            $widget = isset($_POST['currentWidget']) ? $this->input->post('currentWidget') : 'topWidget';
            $tracking_key_id = isset($_POST['trackingPageKeyId'])?$this->input->post('trackingPageKeyId'):'';
            $listingTypeId = isset($_POST['listingTypeId'])?$this->input->post('listingTypeId'):NULL;
            $listingType = isset($_POST['listingType'])?$this->input->post('listingType'):NULL;
            $courseId = isset($_POST['courseId'])?$this->input->post('courseId'):NULL;
            $entityId = isset($_POST['entityId'])?$this->input->post('entityId'):0;
            $tagEntityType = isset($_POST['tagEntityType'])?$this->input->post('tagEntityType'):"";
            $isModeratorEditing = isset($_POST['isModeratorEditing']) ? $this->input->post('isModeratorEditing') : false;

            if($entityType != 'question')
            {
                $isModeratorEditing = false;
            }

            if(!empty($entityId) && $entityId>0 && !empty($tagEntityType)){
                $extraParam = array('entityId'=>$entityId,'entityType'=>$tagEntityType);
            }

            if(empty($tracking_key_id))
            {
                if($entityType == 'question'){
                if($pageName == 'homePage' && $widget == 'topWidget'){
                        $tracking_key_id = '555';
                }else if($pageName == 'homePage' && $widget == 'topLowerWidget'){
                        $tracking_key_id = '617';
                }else if($pageName == 'tagDetailPage' && $widget == 'topWidget'){
                        $tracking_key_id = '556';
                }else if($pageName == 'questionDetailPage' && $widget == 'topWidget'){
                        $tracking_key_id = '557';
                }else if($pageName == 'discussionDetailPage' && $widget == 'topWidget'){
                        $tracking_key_id = '558';
                }else if($pageName == 'discussionTab' && $widget == 'topWidget'){
                        $tracking_key_id = '559';
                }else if($pageName == 'unansweredTab' && $widget == 'topWidget'){
                        $tracking_key_id = '560';
                }else if($pageName == 'unansweredTab' && $widget == 'topLowerWidget'){
                        $tracking_key_id = '621';
                }
                }else if($entityType == 'discussion'){
                    if($pageName == 'discussionTab' && $widget == 'topLowerWidget'){
                            $tracking_key_id = '566';
                    }

                }
            }


            //step 2:validate all the fields
            if(! $this->validationObj->validateEntityType($this->response, $entityType)){
                    return;
            }
            if(! $this->validationObj->validatePostEntity($this->response, array('userId'=>$userId, 'title'=>$title, 'description'=>$description, 'entityType'=>$entityType, 'requestIP'=>$requestIP, 'editEntityId'=>$editEntityId, 'tags'=>$tags))){
                    return;
            }

            //Step 3: Send the Input to Backend and Post the Entity
	    $proceedAhead = true;
	    $result = array();

	    //Fetch the Tags that needs to be attached to the Entity.
	    //For this, the following things will be done:
	    //For all the tags marked Objective/Background/Manual: Find their parents at 1 Level up (except for the conflicted ones)
	    //If any of the Manual tag is conflicted, ignore its Parents
            $this->load->library('Tagging/TaggingLib');
            $this->TaggingLib = new TaggingLib();
            $redisLib = PredisLibrary::getInstance();    	
	    $tagsArray = json_decode($tags,true);
	    if(isset($tagsArray['tags']) && is_array($tagsArray['tags']) && count($tagsArray['tags'])>0){
		if($action != 'editTag' && !$isModeratorEditing){	//In case of EditTag feature, we simply have to store all the Tags sent to the API
			$finalTagArray = $this->TaggingLib->findTagsToBeAttached($tagsArray['tags']);
		}
		else{
			$finalTagArray = $tagsArray['tags'];
		}
	    }
	    //A. Fetch the Category & Country of the Question on the basis of Tags
	    $selectedCategoryCsv = $this->anaCommonLib->getCategoryFromTags($finalTagArray);;
	    $countryCSV = $this->anaCommonLib->getCountryFromTags($finalTagArray);
	    //B. Spam Check
        if(ENVIRONMENT == "production"){
            if(Modules::run('messageBoard/MsgBoard/spamCheck', $title, $requestIP) == "true" || Modules::run('messageBoard/MsgBoard/spamCheck', $description, $requestIP) == "true") {
                $returnValue['ThreadID'] = 1001001;
                $proceedAhead = false;
            }
        }
	    
	    //C. Call the Post Question function
            if($proceedAhead){   //Start Posting the entries
		$fromOthers = ($entityType=="question")?"user":$entityType;
        
        $source="mobileApp";
        global $isWebAPICall;
        if(isset($isWebAPICall) && $isWebAPICall){
            $source = "mobilesiteapicall";
        }

		if($editEntityId == "0"){
	                $returnValue = $this->msgBoardClient->addTopic($appId = 12,$userId,$title,$selectedCategoryCsv,$requestIP,$fromOthers,$listingTypeId,$listingType,$toBeIndex = "1",$displayname = "",$countryCSV,$description,$courseId,$tracking_key_id,$source);
                    
                    if($listingTypeId>0 && $courseId>0){
                        $this->load->library(array('Alerts_client','listing_client'));
                        $this->load->model('QnAModel');
                        $creationTime= date('Y-m-d H:i:s');
                        $threadId = isset($returnValue['ThreadID'])?$returnValue['ThreadID']:0;

                        $alertClient = new Alerts_client();
                        $ListingClientObj = new Listing_client();
                        $alertName = 'comment-'.$questionText;
                        $alertResult = $alertClient->createWidgetAlert($appId,$userId,8,'byComment',$threadId,$alertName);
                        $resultOfCreation['alertResult'] = $alertResult;

                        //After the Question for the Listing is added in the DB, then also add this in the Related questions table.
                        $this->load->builder('nationalCourse/CourseBuilder');
                        $builder = new CourseBuilder();
                        $courseRepository = $builder->getCourseRepository();
                        $courseObj = $courseRepository->find($courseId);
                        $primaryParentId = $courseObj->getInstituteId();

                        $isPaid = $courseObj->isPaid();
                        $includeInSitemap  = 1;
                        if($isPaid == 1) {
                            $campusRepData = array();
                            $this->cadiscussionmodel = $this->load->model('CA/cadiscussionmodel');
                            $campusRepData = $this->cadiscussionmodel->getCampusReps($courseId, $instituteId);
                            if(empty($campusRepData['data'])) {
                                $includeInSitemap = 0;
                            }
                        }

                        if(empty($returnValue['isDuplicate'])){
                            $this->QnAModel->makeResponseOfQuestionAsker($userId,$threadId,$creationTime,$courseId,$listingTypeId,0);
                            $ListingClientObj->updateListingQuestions($appId,$listingTypeId,$threadId,'One');
                        }
                        
                        $source = ($_COOKIE['ci_mobile'] !='') ? 'mobile' : 'desktop';
                        $tracking = $this->QnAModel->insertAnAMobileTracking($userId,'Question',$source);

                    }

                        $this->response->setResponseMsg(ucfirst($entityType)." posted successfully.");
		}
		else{
			$this->anaCommonLib->trackEditOperation($editEntityId, $entityType, $userId);
			$returnValue = $this->msgBoardClient->updateCafePost($appId = 12,$editEntityId,$userId,$title,$selectedCategoryCsv,$requestIP,$fromOthers,$listingTypeId,$listingType,$toBeIndex = "1",$displayname = "",$countryCSV,$description,$questionMoveToIns='off' ,$courseId,$instId='0',$isPaid='false',$questionMoveToCafe='off',$source);
                        $this->response->setResponseMsg(ucfirst($entityType)." edited successfully.");
		}
            }
	    //D. Check the returned array. If it is fine, only then proceed with the result
	    if(isset($returnValue['ThreadID']) && is_numeric($returnValue['ThreadID'])){
		$result["entityId"] = $returnValue['ThreadID'];
	    }
	    else if(isset($returnValue['Result']) && $editEntityId!="0"){	//In case of Edit Entity
		$result["message"] = $returnValue['Result'];
	    }

	    //E. Now, map the Tags with the Question/Discussion just posted
	    if( isset($result["entityId"]) ){
            if(isset($primaryParentId) && $primaryParentId >0 && $listingTypeId != $primaryParentId){
                $extralistingArray = array($primaryParentId);
                //send mailer to CR's of an institute
                $courseId = ($courseId>0)?$courseId:0;
                $qNaModel = $this->load->model('QnAModel');
                $qNaModel->sendMailToCampusReps($courseId,$primaryParentId,$result["entityId"],false);
            }else{
                $extralistingArray = array();
            }
		//Precaution: In case this is a duplicate question, we will remove all its previous tags.
		$this->TaggingLib->deleteTagsWithContentToDB($result["entityId"]);
	       	$this->TaggingLib->insertTagsWithContentToDB($finalTagArray,$result["entityId"],$entityType,$action="threadpost",$editType="editEntity",$listingTypeId,$extralistingArray, $extraParam);
            $redisLib->addMembersToHash('questionPostedLastNMins',array($result["entityId"] => 'post'));
	    }
       
	    else if( $editEntityId!="0" ){	//In case of Edit
		//First, remove all the Attached Tags
		$this->TaggingLib->deleteTagsWithContentToDB($editEntityId);		
	        $redisLib->addMembersToHash('questionPostedLastNMins',array($editEntityId => 'edit'));

	        //update solr data: Not required anymore
		$this->benchmark->mark('index_question_auto_sugestor_start');
        	//Modules::run('indexer/NationalIndexer/indexQuestionForAutosuggestor', $editEntityId);
		$this->benchmark->mark('index_question_auto_sugestor_end');

		//Then, add the new tags
		if( is_array($finalTagArray) && count($finalTagArray)>0 ){
			$this->TaggingLib->insertTagsWithContentToDB($finalTagArray,$editEntityId,$entityType,"updatetag",$editType=$action);		
		}
		else{	//In case of no new tag added.
			$this->TaggingLib->insertTagsWithContentToDB(array(),$editEntityId,$entityType,"updatetag",$editType=$action);
		}
	    }

            if($returnValue == 'NOREPD' || $returnValue == 'NOREPQ'){
		        $errorMessage = "We have blocked some of your app privileges due to unauthorized activities performed by you in the past. Email moderator@shiksha.com to know more.";
                $this->response->setStatusCode(STATUS_CODE_FAILURE);
                $this->response->setResponseMsg($errorMessage);
	    }
           
	    $this->response->setBody($result);

            //Step 4: Return the Response
            $this->response->output();
	}


       /**
         * @desc API to revert rating for an entity
         * @param POST param entityId which is the entity id
         * @return JSON string with HTTP Code 200 and Success/Failure message
         * @date 2015-12-12
         * @author Ankur Gupta
         */

        function deleteEntityRating(){

                //step 1:Fetch the Input from GET/POST
                $Validate = $this->getUserDetails();
                $userId = isset($Validate['userId'])?$Validate['userId']:'';
                $appId = 12;
                $entityId = (isset($_POST['entityId'])) ? $this->input->post('entityId') : '';
                $entityType = (isset($_POST['entityType'])) ? $this->input->post('entityType') : '';
				$digVal = (isset($_POST['digVal'])) ? $this->input->post('digVal') : '1';

                //Step 2:Validate all the fields
                if( ! $this->validationObj->validateRatingDataForAnA($this->response,  array('userId'=>$userId,
                                                                                                'entityId'=>$entityId,
                                                                                               'digVal'=>$digVal,
                                                                                               'pageType'=>'qna',
                                                                                               'entityType'=>$entityType)) ){
                    return;
                }

                //Step 3:
            	$this->load->model('QnAModel');
	        	$digResult = $this->QnAModel->deleteDigVal($appId,$userId,$entityId, $digVal);
                $resultOfDig = $digResult['Result'];

                //b. Now, check the returned value
                if($resultOfDig == 'success'){

                        $confirmMsg = "Your rating has been removed.";
                        $this->response->setStatusCode(STATUS_CODE_SUCCESS);
                        $this->response->setResponseMsg($confirmMsg);

                }else if($resultOfDig != ' '){   //Some Error

                        switch($resultOfDig){
                            case 'NF': $errorMessage = "You have not given any rating to this ".$entityType.".";
                                        break;
                            default: $errorMessage = "Some error occured. Unable to remove your rating.";
                                        break;
                        }
                        $this->response->setStatusCode(STATUS_CODE_FAILURE);
                        $this->response->setResponseMsg($errorMessage);

                }

                //Step 4: Return the Response
                $this->response->output();
                
        }
        function saveQdpFeedback()
        {
                $Validate = $this->getUserDetails();
                $userId = isset($Validate['userId'])?$Validate['userId']:'';
                $rating = (isset($_POST['rating'])) ? $this->input->post('rating') : '';        
                $feedbackMessage = (isset($_POST['feedbackMessage'])) ? $this->input->post('feedbackMessage') : '';
                $questionId = (isset($_POST['questionId'])) ? $this->input->post('questionId') : '';
                $lastAnswerId = (isset($_POST['lastAnswerId'])) ? $this->input->post('lastAnswerId') : '';

                $numberOfAnswers = (isset($_POST['numberOfAnswers'])) ? $this->input->post('numberOfAnswers') : '';
                if( ! $this->validationObj->validateRatingDataForQdp($this->response,  array('userId'=>$userId,
                                                                                                'rating'=>$rating,
                                                                                               'feedbackMessage'=>$feedbackMessage,
                                                                                               'questionId'=>$questionId,
                                                                                               'lastAnswerId'=>$lastAnswerId,
                                                                                               'numberOfAnswers'=>$numberOfAnswers)) ){
                    return;
                }
                $this->load->model('messageBoard/AnAModel');
                if($lastAnswerId=="0")
                {
                    $lastAnswerDetails = $this->AnAModel->getAnswerDetails($questionId,$userId,0,1,'latest');
                    $lastAnswerId = $lastAnswerDetails['childDetails'][0]['msgId'];
                }
                $result = $this->AnAModel->insertFeedback($rating,$userId,$feedbackMessage,$questionId,$lastAnswerId,$numberOfAnswers);  

                if($result)
                {
                        $confirmMsg = "Thank your for the feedback";
                        $this->response->setStatusCode(STATUS_CODE_SUCCESS);
                        $this->response->setResponseMsg($confirmMsg);
                }
                else
                {
                    $errorMessage = "Something went wrong!!";
                    $this->response->setStatusCode(STATUS_CODE_FAILURE);
                    $this->response->setResponseMsg($errorMessage);
                }
                $this->response->output();
    }
    function updateFeedbackLayerShownCount()        
    {
                $Validate = $this->getUserDetails();
                $userId = isset($Validate['userId'])?$Validate['userId']:'';
                $questionId = (isset($_POST['questionId'])) ? $this->input->post('questionId') : '';
                if( ! $this->validationObj->validateFeedbackCountUpdateData($this->response,  array('userId'=>$userId,
                                                                                               'questionId'=>$questionId)) ){
                    return;
                }
                $this->load->model('messageBoard/AnAModel');
                $result = $this->AnAModel->updateFeedbackLayerCount($userId,$questionId);
                if($result)
                {
                    $msg = "Done";
                    $this->response->setStatusCode(STATUS_CODE_SUCCESS);
                    $this->response->setResponseMsg($msg);
                }
                else
                {
                    $errorMessage = "Something went wrong!!";
                    $this->response->setStatusCode(STATUS_CODE_FAILURE);
                    $this->response->setResponseMsg($errorMessage);
                }
                $this->response->output();
    }
}