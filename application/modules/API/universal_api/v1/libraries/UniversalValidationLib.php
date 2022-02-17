<?php
/**
 * Universal Validation Lib Class
 * This is the class for all the API Validations related to Universal API like Follow
 * @date    2015-08-09
 * @author  Ankur Gupta
 * @todo    none
*/

class UniversalValidationLib
{
    private $CI;
    private $validationLibObj;

    function __construct() {
        $this->CI = &get_instance();
        $this->CI->load->library('common_api/APIValidationLib');
	$this->validationLibObj = new APIValidationLib();
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
    
    /**
     * @desc API to check Validations while Following/unfollowing Entity
     * @param response object of the response class. This is required in case there is an error. We will then create error response from this class.
     * @param input array. This will contain the values of the Parameters (UserId, entityId, entityType, action)
     * @return true in case no error is found
     * @return false in case any error is found. Also, we will set the response object with the error message
     * @date 2015-08-09
     * @author Ankur Gupta
     */
    function validateFollowEntity($response, $input){
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
                            'action'=>array(
                                'value'=>$input['action'],
                                'minLength'=>'1',
                                'maxLength'=>'100',
                                'validation'=>'validateStr',
                                'mandatory'=>true
                            )
                        );

        // prevent user to follow/unfollow herself/himself
        if(($input['userId'] == $input['entityId']) && $input['entityType'] == 'user'){
            $response->setResponseMsg("You cannot ".$input['action']." yourself.");
            $response->setStatusCode(STATUS_CODE_FAILURE);
            $response->output();
            return false;
        }

        $noErrorFound = $this->validationLibObj->checkValidations($paramArray);
        return $this->returnResponse($response, $noErrorFound);
    }

    /**
     * @desc API to check Validations for AUto Suggestor
     * @param response object of the response class. This is required in case there is an error. We will then create error response from this class.
     * @param input array. This will contain the values of the Parameters (UserId, text, type, count)
     * @return true in case no error is found
     * @return false in case any error is found. Also, we will set the response object with the error message
     * @date 2015-08-19
     * @author Ankur Gupta
     */
    function validateAutoSuggestor($response, $input){
        $paramArray = array(
                            'userId'=>array(
                                'value'=>$input['userId'],
                                'minLength'=>'1',
                                'maxLength'=>'11',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
                            'text'=>array(
                                'value'=>$input['text'],
                                'minLength'=>'1',
                                'maxLength'=>'1000',
                                'validation'=>'validateStr',
                                'mandatory'=>true
                            ),
                            'type'=>array(
                                'value'=>$input['type'],
                                'minLength'=>'1',
                                'maxLength'=>'100',
                                'validation'=>'validateStr',
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

}



