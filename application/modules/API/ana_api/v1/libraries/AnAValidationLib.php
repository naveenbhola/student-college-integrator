<?php
/**
 * AnA Validation Lib Class
 * This is the class for all the API Validations related to AnA Like Unanswered Tab, Question detail pages, Posting questions/answers/comments
 * @date    2015-07-27
 * @author  Ankur Gupta
 * @todo    none
*/

class AnAValidationLib
{
    private $CI;
    private $validationLibObj;

    function __construct() {
        $this->CI = &get_instance();
        $this->CI->load->library('common_api/APIValidationLib');
	$this->validationLibObj = new APIValidationLib();
    }

    /**
     * @desc API to check Validations on Unanswered Page.
     * @param response object of the response class. This is required in case there is an error. We will then create error response from this class.
     * @param input array. This will contain the values of the Parameters (UserId, Start and Count)
     * @return true in case no error is found
     * @return false in case any error is found. Also, we will set the response object with the error message
     * @date 2015-07-27
     * @author Ankur Gupta
     */   
    function validateHomepageTab($response, $input){
        $paramArray = array(
                            'userId'=>array(
                                'value'=>$input['userId'],
                                'minLength'=>'1',
                                'maxLength'=>'11',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
                            'start'=>array(
                                'value'=>$input['start'],
                                'minLength'=>'1',
                                'maxLength'=>'10',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
                            'pagenumber'=>array(
                                'value'=>$input['pagenumber'],
                                'minLength'=>'1',
                                'maxLength'=>'3',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            )
                        );
        $noErrorFound = $this->validationLibObj->checkValidations($paramArray);
	return $this->returnResponse($response, $noErrorFound);
    }

    /**
     * @desc Commonly used Function to create the Response object in case any error is found in any of the Validation API 
     * @param response object of the response class. This is required in case there is an error. We will then create error response from this class.
     * @param noErrorFound string which will be true in case no error is found. If any error is found, this will contain the error message
     * @return true in case no error is found
     * @return false in case any error is found. Also, we will set the response object with the error message
     * @date 2015-07-16
     * @author Ankur Gupta
     */
    function returnResponse($response, $noErrorFound){
        if($noErrorFound !== true){
		$response->setFieldError($noErrorFound);
		$response->setResponseMsg("Unsuccessful");
		$response->setStatusCode(STATUS_CODE_FAILURE);
                $response->output();
                return false;
        }
	return true;
    }

    function returnResponseAnswer($response, $noErrorFound, $paramArray){
        if($noErrorFound !== true){
		error_log(print_r($paramArray,true), 3, '/tmp/answer-errors.log');
		error_log(print_r($noErrorFound,true), 3, '/tmp/answer-errors.log');
                $response->setFieldError($noErrorFound);
                $response->setResponseMsg("Unsuccessful");
                $response->setStatusCode(STATUS_CODE_FAILURE);
                $response->output();
                return false;
        }
	error_log(print_r($paramArray,true), 3, '/tmp/answer-all.log');
        return true;
    }
    
    
    /**
     * @desc API to check Validations on Unanswered Page.
     * @param response object of the response class. This is required in case there is an error. We will then create error response from this class.
     * @param input array. This will contain the values of the Parameters (UserId, Start and Count)
     * @return true in case no error is found
     * @return false in case any error is found. Also, we will set the response object with the error message
     * @date 2015-07-27
     * @author Ankur Gupta
     */   
    function validateQuestionDetail($response, $input){
        $paramArray = array(
                            'userId'=>array(
                                'value'=>$input['userId'],
                                'minLength'=>'1',
                                'maxLength'=>'11',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
                            'start'=>array(
                                'value'=>$input['start'],
                                'minLength'=>'1',
                                'maxLength'=>'10',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
                            'count'=>array(
                                'value'=>$input['count'],
                                'minLength'=>'1',
                                'maxLength'=>'3',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
			    'questionId'=>array(
                                'value'=>$input['questionId'],
                                'minLength'=>'1',
                                'maxLength'=>'10',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
			    'sortOrder'=>array(
                                'value'=>$input['sortOrder'],
                                'minLength'=>'1',
                                'maxLength'=>'10',
                                'validation'=>'validateStr',
                                'mandatory'=>true
                            ),
			    'referenceAnswerId'=>array(
                                'value'=>$input['referenceAnswerId'],
                                'minLength'=>'0',
                                'maxLength'=>'10',
                                'validation'=>'validateInteger',
                                'mandatory'=>false
                            )
			    
                        );
        $noErrorFound = $this->validationLibObj->checkValidations($paramArray);
	return $this->returnResponse($response, $noErrorFound);
    }
    
    
        function validateCommentDetails($response, $input){
        $paramArray = array(
                            'userId'=>array(
                                'value'=>$input['userId'],
                                'minLength'=>'1',
                                'maxLength'=>'11',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
                            'start'=>array(
                                'value'=>$input['start'],
                                'minLength'=>'1',
                                'maxLength'=>'10',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
                            'count'=>array(
                                'value'=>$input['count'],
                                'minLength'=>'1',
                                'maxLength'=>'3',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
			    'entityId'=>array(
                                'value'=>$input['entityId'],
                                'minLength'=>'1',
                                'maxLength'=>'10',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
			    'sortOrder'=>array(
                                'value'=>$input['sortOrder'],
                                'minLength'=>'1',
                                'maxLength'=>'10',
                                'validation'=>'validateStr',
                                'mandatory'=>true
                            ),
			    'referenceCommentId'=>array(
                                'value'=>$input['referenceCommentId'],
                                'minLength'=>'0',
                                'maxLength'=>'10',
                                'validation'=>'validateInteger',
                                'mandatory'=>false
                            )
                        );
        $noErrorFound = $this->validationLibObj->checkValidations($paramArray);
	return $this->returnResponse($response, $noErrorFound);
    }
    
    
        function validateDiscussionDetails($response, $input){
        $paramArray = array(
                            'userId'=>array(
                                'value'=>$input['userId'],
                                'minLength'=>'1',
                                'maxLength'=>'11',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
                            'start'=>array(
                                'value'=>$input['start'],
                                'minLength'=>'1',
                                'maxLength'=>'10',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
                            'count'=>array(
                                'value'=>$input['count'],
                                'minLength'=>'1',
                                'maxLength'=>'3',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
			    'discussionId'=>array(
                                'value'=>$input['discussionId'],
                                'minLength'=>'1',
                                'maxLength'=>'10',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
			    'sortOrder'=>array(
                                'value'=>$input['sortOrder'],
                                'minLength'=>'1',
                                'maxLength'=>'10',
                                'validation'=>'validateStr',
                                'mandatory'=>true
                            ),
			    'referenceCommentId'=>array(
                                'value'=>$input['referenceCommentId'],
                                'minLength'=>'0',
                                'maxLength'=>'10',
                                'validation'=>'validateInteger',
                                'mandatory'=>false
                            )
                        );
        $noErrorFound = $this->validationLibObj->checkValidations($paramArray);
	return $this->returnResponse($response, $noErrorFound);
    }

    /**
     * @desc API to check Validations while Adding/Editing Answer
     * @param response object of the response class. This is required in case there is an error. We will then create error response from this class.
     * @param input array. This will contain the values of the Parameters (UserId, parentId, text)
     * @return true in case no error is found
     * @return false in case any error is found. Also, we will set the response object with the error message
     * @date 2015-08-06
     * @author Ankur Gupta
     */
    function validatePostAnswer($response, $input){
        $paramArray = array(
                            'userId'=>array(
                                'value'=>$input['userId'],
                                'minLength'=>'1',
                                'maxLength'=>'11',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
                            'topicId'=>array(
                                'value'=>$input['topicId'],
                                'minLength'=>'1',
                                'maxLength'=>'10',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
                            'answer'=>array(
                                'value'=>$input['answerText'],
                                'minLength'=>'15',
                                'maxLength'=>'2500',
                                'validation'=>'validateStrForPosting',
                                'mandatory'=>true
                            ),
                            'action'=>array(
                                'value'=>$input['action'],
                                'minLength'=>'1',
                                'maxLength'=>'20',
                                'validation'=>'validateStr',
                                'mandatory'=>false
                            ),
                            'answerId'=>array(
                                'value'=>$input['answerId'],
                                'minLength'=>'1',
                                'maxLength'=>'10',
                                'validation'=>'validateInteger',
                                'mandatory'=>false
                            ),
                            'requestIP'=>array(
                                'value'=>$input['requestIP'],
                                'minLength'=>'0',
                                'maxLength'=>'50',
                                'validation'=>'validateStr',
                                'mandatory'=>false
                            )
                        );
        $noErrorFound = $this->validationLibObj->checkValidations($paramArray);
        return $this->returnResponseAnswer($response, $noErrorFound, $paramArray);
    }

    /**
     * @desc API to check Validations while Adding/Editing Answer
     * @param response object of the response class. This is required in case there is an error. We will then create error response from this class.
     * @param input array. This will contain the values of the Parameters (UserId, parentId, text)
     * @return true in case no error is found
     * @return false in case any error is found. Also, we will set the response object with the error message
     * @date 2015-08-06
     * @author Ankur Gupta
     */
    function validatePostComment($response, $input){
        $paramArray = array(
                            'userId'=>array(
                                'value'=>$input['userId'],
                                'minLength'=>'1',
                                'maxLength'=>'11',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
                            'topicId'=>array(
                                'value'=>$input['topicId'],
                                'minLength'=>'1',
                                'maxLength'=>'10',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
                            'comment'=>array(
                                'value'=>$input['commentText'],
                                'minLength'=>'3',
                                'maxLength'=>'2500',
                                'validation'=>'validateStrForPosting',
                                'mandatory'=>true
                            ),
                            'type'=>array(
                                'value'=>$input['type'],
                                'minLength'=>'1',
                                'maxLength'=>'20',
                                'validation'=>'validateStr',
                                'mandatory'=>false
                            ),
                            'parentId'=>array(
                                'value'=>$input['parentId'],
                                'minLength'=>'1',
                                'maxLength'=>'10',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
                            'editEntityId'=>array(
                                'value'=>$input['editEntityId'],
                                'minLength'=>'1',
                                'maxLength'=>'10',
                                'validation'=>'validateInteger',
                                'mandatory'=>false
                            ),
                            'requestIP'=>array(
                                'value'=>$input['requestIP'],
                                'minLength'=>'0',
                                'maxLength'=>'50',
                                'validation'=>'validateStr',
                                'mandatory'=>false
                            )
                        );
        $noErrorFound = $this->validationLibObj->checkValidations($paramArray);
        return $this->returnResponse($response, $noErrorFound);
    }
   
    /**
     * @desc API to check Validations while Adding/Editing Reply
     * @param response object of the response class. This is required in case there is an error. We will then create error response from this class.
     * @param input array. This will contain the values of the Parameters (UserId, parentId, text)
     * @return true in case no error is found
     * @return false in case any error is found. Also, we will set the response object with the error message
     * @date 2015-08-06
     * @author Ankur Gupta
     */
    function validatePostReply($response, $input){
        $paramArray = array(
                            'userId'=>array(
                                'value'=>$input['userId'],
                                'minLength'=>'1',
                                'maxLength'=>'11',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
                            'topicId'=>array(
                                'value'=>$input['topicId'],
                                'minLength'=>'1',
                                'maxLength'=>'10',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
                            'reply'=>array(
                                'value'=>$input['commentText'],
                                'minLength'=>'3',
                                'maxLength'=>'2500',
                                'validation'=>'validateStrForPosting',
                                'mandatory'=>true
                            ),
                            'type'=>array(
                                'value'=>$input['type'],
                                'minLength'=>'1',
                                'maxLength'=>'20',
                                'validation'=>'validateStr',
                                'mandatory'=>false
                            ),
                            'parentId'=>array(
                                'value'=>$input['parentId'],
                                'minLength'=>'1',
                                'maxLength'=>'10',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
                            'editEntityId'=>array(
                                'value'=>$input['editEntityId'],
                                'minLength'=>'1',
                                'maxLength'=>'10',
                                'validation'=>'validateInteger',
                                'mandatory'=>false
                            ),
                            'requestIP'=>array(
                                'value'=>$input['requestIP'],
                                'minLength'=>'0',
                                'maxLength'=>'50',
                                'validation'=>'validateStr',
                                'mandatory'=>false
                            )
                        );
        $noErrorFound = $this->validationLibObj->checkValidations($paramArray);
        return $this->returnResponse($response, $noErrorFound);
    }
 
    /**
     * @desc API to check Validations while Closing a question
     * @param response object of the response class. This is required in case there is an error. We will then create error response from this class.
     * @param input array. This will contain the values of the Parameters (UserId, questionId)
     * @return true in case no error is found
     * @return false in case any error is found. Also, we will set the response object with the error message
     * @date 2015-08-06
     * @author Ankur Gupta
     */
    function validateCloseQuestion($response, $input){
        $paramArray = array(
                            'userId'=>array(
                                'value'=>$input['userId'],
                                'minLength'=>'1',
                                'maxLength'=>'11',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
                            'questionId'=>array(
                                'value'=>$input['questionId'],
                                'minLength'=>'1',
                                'maxLength'=>'10',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            )
                        );
        $noErrorFound = $this->validationLibObj->checkValidations($paramArray);
        return $this->returnResponse($response, $noErrorFound);
    }

    /**
     * @desc API to check Validations while Sharing Entity
     * @param response object of the response class. This is required in case there is an error. We will then create error response from this class.
     * @param input array. This will contain the values of the Parameters (UserId, entityId, entityType, destination)
     * @return true in case no error is found
     * @return false in case any error is found. Also, we will set the response object with the error message
     * @date 2015-08-06
     * @author Ankur Gupta
     */
    function validateShareEntity($response, $input){
        $paramArray = array(
                            'userId'=>array(
                                'value'=>$input['userId'],
                                'minLength'=>'1',
                                'maxLength'=>'11',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
                            'entityId'=>array(
                                'value'=>$input['entityId'],
                                'minLength'=>'1',
                                'maxLength'=>'10',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
                            'entityType'=>array(
                                'value'=>$input['entityType'],
                                'minLength'=>'1',
                                'maxLength'=>'100',
                                'validation'=>'validateStr',
                                'mandatory'=>true
                            ),
                            'destination'=>array(
                                'value'=>$input['destination'],
                                'minLength'=>'1',
                                'maxLength'=>'100',
                                'validation'=>'validateStr',
                                'mandatory'=>true
                            )
                        );
        $noErrorFound = $this->validationLibObj->checkValidations($paramArray);
        return $this->returnResponse($response, $noErrorFound);
    }
    
    function validateRatingDataForAnA($response, $input){
        $paramArray = array(
                            'userId'=>array(
                                'value'=>$input['userId'],
                                'minLength'=>'1',
                                'maxLength'=>'11',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
                            'entityId'=>array(
                                'value'=>$input['entityId'],
                                'minLength'=>'1',
                                'maxLength'=>'10',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
                            'digVal'=>array(
                                'value'=>$input['digVal'],
                                'minLength'=>'1',
                                'maxLength'=>'3',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
			    'pageType'=>array(
                                'value'=>$input['pageType'],
                                'minLength'=>'0',
                                'maxLength'=>'20',
                                'validation'=>'validateStr',
                                'mandatory'=>false
                            ),
			    'entityType'=>array(
                                'value'=>$input['entityType'],
                                'minLength'=>'0',
                                'maxLength'=>'20',
                                'validation'=>'validateStr',
                                'mandatory'=>true
                            )
                        );
        $noErrorFound = $this->validationLibObj->checkValidations($paramArray);
	return $this->returnResponse($response, $noErrorFound);
    }
    
    function validateDeleteEntityParams($response, $input){
        $paramArray = array(
                            'userId'=>array(
                                'value'=>$input['userId'],
                                'minLength'=>'1',
                                'maxLength'=>'11',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
                            'msgId'=>array(
                                'value'=>$input['msgId'],
                                'minLength'=>'1',
                                'maxLength'=>'10',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
                            'threadId'=>array(
                                'value'=>$input['threadId'],
                                'minLength'=>'1',
                                'maxLength'=>'10',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
			    'ownerUserId'=>array(
                                'value'=>$input['ownerUserId'],
                                'minLength'=>'1',
                                'maxLength'=>'11',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
			    'entityType'=>array(
                                'value'=>$input['entityType'],
                                'minLength'=>'1',
                                'maxLength'=>'20',
                                'validation'=>'validateStr',
                                'mandatory'=>true
                            )
                        );
        $noErrorFound = $this->validationLibObj->checkValidations($paramArray);
	return $this->returnResponse($response, $noErrorFound);
    }
    
    
    function validateReportAbuseReasonData($response, $input){
        $paramArray = array(
                            'userId'=>array(
                                'value'=>$input['userId'],
                                'minLength'=>'1',
                                'maxLength'=>'11',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
                            'msgId'=>array(
                                'value'=>$input['msgId'],
                                'minLength'=>'1',
                                'maxLength'=>'10',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
                            'threadId'=>array(
                                'value'=>$input['threadId'],
                                'minLength'=>'1',
                                'maxLength'=>'10',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
			    'chosenReasonList'=>array(
                                'value'=>$input['chosenReasonList'],
                                'minLength'=>'1',
                                'maxLength'=>'50',
                                'validation'=>'validateStr',
                                'mandatory'=>true
                            ),
			    'chosenReasonText'=>array(
                                'value'=>$input['chosenReasonText'],
                                'minLength'=>'1',
                                'maxLength'=>'500',
                                'validation'=>'validateStr',
                                'mandatory'=>true
                            ),
			    'entityTypeReportAbuse'=>array(
                                'value'=>$input['entityTypeReportAbuse'],
                                'minLength'=>'1',
                                'maxLength'=>'20',
                                'validation'=>'validateStr',
                                'mandatory'=>true
                            ),
			    'otherReasonText'=>array(
                                'value'=>$input['otherReasonText'],
                                'minLength'=>'0',
                                'maxLength'=>'100',
                                'validation'=>'validateStr',
                                'mandatory'=>false
                            )
                        );
	
        $noErrorFound = $this->validationLibObj->checkValidations($paramArray);
	return $this->returnResponse($response, $noErrorFound);
    }


    /**
     * @desc API to check Validations while Shortlisting Entity
     * @param response object of the response class. This is required in case there is an error. We will then create error response from this class.
     * @param input array. This will contain the values of the Parameters (UserId, entityId, entityType)
     * @return true in case no error is found
     * @return false in case any error is found. Also, we will set the response object with the error message
     * @date 2015-08-14
     * @author Ankur Gupta
     */
    function validateShortlistEntity($response, $input){
        $paramArray = array(
                            'userId'=>array(
                                'value'=>$input['userId'],
                                'minLength'=>'1',
                                'maxLength'=>'11',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
                            'entityId'=>array(
                                'value'=>$input['entityId'],
                                'minLength'=>'1',
                                'maxLength'=>'10',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
                            'entityType'=>array(
                                'value'=>$input['entityType'],
                                'minLength'=>'1',
                                'maxLength'=>'100',
                                'validation'=>'validateStr',
                                'mandatory'=>true
                            ),
                            'status'=>array(
                                'value'=>$input['status'],
                                'minLength'=>'1',
                                'maxLength'=>'10',
                                'validation'=>'validateStr',
                                'mandatory'=>true
                            )
                        );
        $noErrorFound = $this->validationLibObj->checkValidations($paramArray);
        return $this->returnResponse($response, $noErrorFound);
     }

    /**
     * @desc API to check Validations while fetching Shortlisting Entity
     * @param response object of the response class. This is required in case there is an error. We will then create error response from this class.
     * @param input array. This will contain the values of the Parameters (UserId, entityType)
     * @return true in case no error is found
     * @return false in case any error is found. Also, we will set the response object with the error message
     * @date 2015-08-14
     * @author Ankur Gupta
     */
    function validateGetShortlistEntity($response, $input){
        $paramArray = array(
                            'userId'=>array(
                                'value'=>$input['userId'],
                                'minLength'=>'1',
                                'maxLength'=>'11',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
                            'entityType'=>array(
                                'value'=>$input['entityType'],
                                'minLength'=>'1',
                                'maxLength'=>'100',
                                'validation'=>'validateStr',
                                'mandatory'=>true
                            ),
                            'start'=>array(
                                'value'=>$input['start'],
                                'minLength'=>'1',
                                'maxLength'=>'10',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
                            'count'=>array(
                                'value'=>$input['count'],
                                'minLength'=>'1',
                                'maxLength'=>'3',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            )
                        );
        $noErrorFound = $this->validationLibObj->checkValidations($paramArray);
        return $this->returnResponse($response, $noErrorFound);
     }    

    /**
     * @desc API to check Validations while Posting Question/Discussion Intermediate Page
     * @param response object of the response class. This is required in case there is an error. We will then create error response from this class.
     * @param input array. This will contain the values of the Parameters (UserId, entityType)
     * @return true in case no error is found
     * @return false in case any error is found. Also, we will set the response object with the error message
     * @date 2015-08-20
     * @author Ankur Gupta
     */
    function validateIntermediatePage($response, $input){
	if($input['entityType']=="question"){
	        $paramArray = array(
                            'userId'=>array(
                                'value'=>$input['userId'],
                                'minLength'=>'1',
                                'maxLength'=>'11',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
                            'entityType'=>array(
                                'value'=>$input['entityType'],
                                'minLength'=>'1',
                                'maxLength'=>'100',
                                'validation'=>'validateStr',
                                'mandatory'=>true
                            ),
                            'question'=>array(
                                'value'=>$input['entityText'],
                                'minLength'=>'20',
                                'maxLength'=>'140',
                                'validation'=>'validateStrForPosting',
                                'mandatory'=>true
                            ),
                            'description'=>array(
                                'value'=>$input['description'],
                                'minLength'=>'0',
                                'maxLength'=>'300',
                                'validation'=>'validateStrForPosting',
                                'mandatory'=>false
                            ),
                            'editEntityId'=>array(
                                'value'=>$input['editEntityId'],
                                'minLength'=>'0',
                                'maxLength'=>'10',
                                'validation'=>'validateInteger',
                                'mandatory'=>false
                            )
                        );
	}
	else{
                $paramArray = array(
                            'userId'=>array(
                                'value'=>$input['userId'],
                                'minLength'=>'1',
                                'maxLength'=>'11',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
                            'entityType'=>array(
                                'value'=>$input['entityType'],
                                'minLength'=>'1',
                                'maxLength'=>'100',
                                'validation'=>'validateStr',
                                'mandatory'=>true
                            ),
                            'discussion'=>array(
                                'value'=>$input['entityText'],
                                'minLength'=>'20',
                                'maxLength'=>'100',
                                'validation'=>'validateStrForPosting',
                                'mandatory'=>true
                            ),
                            'description'=>array(
                                'value'=>$input['description'],
                                'minLength'=>'20',
                                'maxLength'=>'2500',
                                'validation'=>'validateStrForPosting',
                                'mandatory'=>true
                            ),
                            'editEntityId'=>array(
                                'value'=>$input['editEntityId'],
                                'minLength'=>'0',
                                'maxLength'=>'10',
                                'validation'=>'validateInteger',
                                'mandatory'=>false
                            )
                        );
	}
        $noErrorFound = $this->validationLibObj->checkValidations($paramArray);
        return $this->returnResponse($response, $noErrorFound);
     }

    /**
     * @desc API to check Validations while Posting Question/Discussion
     * @param response object of the response class. This is required in case there is an error. We will then create error response from this class.
     * @param input array. This will contain the values of the Parameters (UserId, title, description, tags)
     * @return true in case no error is found
     * @return false in case any error is found. Also, we will set the response object with the error message
     * @date 2015-08-20
     * @author Ankur Gupta
     */
    function validatePostEntity($response, $input){
	if($input['entityType']=='question'){
	        $paramArray = array(
                            'userId'=>array(
                                'value'=>$input['userId'],
                                'minLength'=>'1',
                                'maxLength'=>'11',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
                            'question'=>array(
                                'value'=>$input['title'],
                                'minLength'=>'20',
                                'maxLength'=>'140',
                                'validation'=>'validateStrForPosting',
                                'mandatory'=>true
                            ),
                            'description'=>array(
                                'value'=>$input['description'],
                                'minLength'=>'0',
                                'maxLength'=>'300',
                                'validation'=>'validateStrForPosting',
                                'mandatory'=>false
                            ),
                            'tags'=>array(
                                'value'=>$input['tags'],
                                'minLength'=>'0',
                                'maxLength'=>'10000',
                                'validation'=>'validateStr',
                                'mandatory'=>false
                            ),
                            'requestIP'=>array(
                                'value'=>$input['requestIP'],
                                'minLength'=>'0',
                                'maxLength'=>'50',
                                'validation'=>'validateStr',
                                'mandatory'=>false
                            ),
                            'editEntityId'=>array(
                                'value'=>$input['editEntityId'],
                                'minLength'=>'0',
                                'maxLength'=>'10',
                                'validation'=>'validateInteger',
                                'mandatory'=>false
                            )
                        );
	}
	else{
                $paramArray = array(
                            'userId'=>array(
                                'value'=>$input['userId'],
                                'minLength'=>'1',
                                'maxLength'=>'11',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
                            'discussion'=>array(
                                'value'=>$input['title'],
                                'minLength'=>'20',
                                'maxLength'=>'100',
                                'validation'=>'validateStrForPosting',
                                'mandatory'=>true
                            ),
                            'description'=>array(
                                'value'=>$input['description'],
                                'minLength'=>'20',
                                'maxLength'=>'2500',
                                'validation'=>'validateStrForPosting',
                                'mandatory'=>true
                            ),
                            'tags'=>array(
                                'value'=>$input['tags'],
                                'minLength'=>'0',
                                'maxLength'=>'10000',
                                'validation'=>'validateStr',
                                'mandatory'=>false
                            ),
                            'requestIP'=>array(
                                'value'=>$input['requestIP'],
                                'minLength'=>'0',
                                'maxLength'=>'50',
                                'validation'=>'validateStr',
                                'mandatory'=>false
                            ),
                            'editEntityId'=>array(
                                'value'=>$input['editEntityId'],
                                'minLength'=>'0',
                                'maxLength'=>'10',
                                'validation'=>'validateInteger',
                                'mandatory'=>false
                            )
                        );
	}
        $noErrorFound = $this->validationLibObj->checkValidations($paramArray);
        return $this->returnResponse($response, $noErrorFound);
    }


    function validateEntityType($response, $entityType){
        if($entityType == "question" || $entityType == "discussion"){
                return true;
        }
        else{
                $errorArray = array(
                                        array(
                                                'field' => 'entityType',
                                                'errorMessage' => 'Please enter valid entityType'
                                        )
                                    );
                return $this->returnResponse($response, $errorArray);
        }
    }
    
    function validateOverFlowTabData($response, $input){
        $paramArray = array(
                            'userId'=>array(
                                'value'=>$input['userId'],
                                'minLength'=>'1',
                                'maxLength'=>'11',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
                            'entityType'=>array(
                                'value'=>$input['entityType'],
                                'minLength'=>'1',
                                'maxLength'=>'20',
                                'validation'=>'validateStr',
                                'mandatory'=>true
                            ),
			    'entityId'=>array(
                                'value'=>$input['entityId'],
                                'minLength'=>'1',
                                'maxLength'=>'10',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            )
                        );
        $noErrorFound = $this->validationLibObj->checkValidations($paramArray);
	return $this->returnResponse($response, $noErrorFound);
    }
    
    
    function validateReplyDetails($response, $input){
        $paramArray = array(
                            'userId'=>array(
                                'value'=>$input['userId'],
                                'minLength'=>'1',
                                'maxLength'=>'11',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
                            'start'=>array(
                                'value'=>$input['start'],
                                'minLength'=>'1',
                                'maxLength'=>'10',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
                            'count'=>array(
                                'value'=>$input['count'],
                                'minLength'=>'1',
                                'maxLength'=>'3',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
			    'commentId'=>array(
                                'value'=>$input['commentId'],
                                'minLength'=>'1',
                                'maxLength'=>'10',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            )
                        );
        $noErrorFound = $this->validationLibObj->checkValidations($paramArray);
	return $this->returnResponse($response, $noErrorFound);
    }
    
    function validateReportAbuseFormData($response, $input){
        $paramArray = array(
                            'userId'=>array(
                                'value'=>$input['userId'],
                                'minLength'=>'1',
                                'maxLength'=>'11',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            )
                        );
	
        $noErrorFound = $this->validationLibObj->checkValidations($paramArray);
	return $this->returnResponse($response, $noErrorFound);
    }


    function validateSimilarQuestions($response, $input){
        $paramArray = array(
                            'userId'=>array(
                                'value'=>$input['userId'],
                                'minLength'=>'1',
                                'maxLength'=>'11',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
                            'entityText'=>array(
                                'value'=>$input['entityText'],
                                'minLength'=>'1',
                                'maxLength'=>'100',
                                'validation'=>'validateStr',
                                'mandatory'=>true
                            )
                        );
        $noErrorFound = $this->validationLibObj->checkValidations($paramArray);
        return $this->returnResponse($response, $noErrorFound);
    }
    
    
    function validateListOfUsersBasedOnAction($response, $input){
        $paramArray = array(
                            'userId'=>array(
                                'value'=>$input['userId'],
                                'minLength'=>'1',
                                'maxLength'=>'11',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
                            'entityId'=>array(
                                'value'=>$input['entityId'],
                                'minLength'=>'1',
                                'maxLength'=>'10',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
                            'start'=>array(
                                'value'=>$input['start'],
                                'minLength'=>'1',
                                'maxLength'=>'10',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
                            'count'=>array(
                                'value'=>$input['count'],
                                'minLength'=>'1',
                                'maxLength'=>'3',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
                            'actionType'=>array(
                                'value'=>$input['actionType'],
                                'minLength'=>'1',
                                'maxLength'=>'20',
                                'validation'=>'validateStr',
                                'mandatory'=>true
                            ),
                            'entityType'=>array(
                                'value'=>$input['entityType'],
                                'minLength'=>'1',
                                'maxLength'=>'20',
                                'validation'=>'validateStr',
                                'mandatory'=>true
                            )
                        );
        $noErrorFound = $this->validationLibObj->checkValidations($paramArray);
        return $this->returnResponse($response, $noErrorFound);
    }
    function validateRatingDataForQdp($response, $input){
        $paramArray = array(
                            'userId'=>array(
                                'value'=>$input['userId'],
                                'minLength'=>'1',
                                'maxLength'=>'11',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
                            'questionId'=>array(
                                'value'=>$input['questionId'],
                                'minLength'=>'1',
                                'maxLength'=>'10',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
                            'rating'=>array(
                                'value'=>$input['rating'],
                                'minLength'=>'1',
                                'maxLength'=>'2',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
                            'feedbackMessage'=>array(
                                'value'=>$input['feedbackMessage'],
                                'minLength'=>'0',
                                'maxLength'=>'1000',
                                'validation'=>'validateStr',
                                'mandatory'=>false
                            ),
                            'lastAnswerId'=>array(
                                'value'=>$input['lastAnswerId'],
                                'minLength'=>'1',
                                'maxLength'=>'10',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
                            'numberOfAnswers'=>array(
                                'value'=>$input['numberOfAnswers'],
                                'minLength'=>'1',
                                'maxLength'=>'10',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),

                        );
        $noErrorFound = $this->validationLibObj->checkValidations($paramArray);
    return $this->returnResponse($response, $noErrorFound);
    }
    function validateFeedbackCountUpdateData($response, $input){
        $paramArray = array(
                            'userId'=>array(
                                'value'=>$input['userId'],
                                'minLength'=>'1',
                                'maxLength'=>'11',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
                            'questionId'=>array(
                                'value'=>$input['questionId'],
                                'minLength'=>'1',
                                'maxLength'=>'10',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
                        );
        $noErrorFound = $this->validationLibObj->checkValidations($paramArray);
    return $this->returnResponse($response, $noErrorFound);
    }
}


