<?php
/**
 * Tags Validation Lib Class
 * This is the class for all the API Validations related to Tags API like Detail page
 * @date    2015-08-27
 * @author  Ankur Gupta
 * @todo    none
*/

class TagsValidationLib
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
     * @date 2015-08-28
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
     * @desc API to check Validations while getting the detail page of a Tag
     * @param response object of the response class. This is required in case there is an error. We will then create error response from this class.
     * @param input array. This will contain the values of the Parameters (UserId, contentType, start, count, sorting)
     * @return true in case no error is found
     * @return false in case any error is found. Also, we will set the response object with the error message
     * @date 2015-08-27
     * @author Ankur Gupta
     */
    function validateGetTagDetailPage($response, $input){
        $paramArray = array(
                            'userId'=>array(
                                'value'=>$input['userId'],
                                'minLength'=>'1',
                                'maxLength'=>'11',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
                            'tagId'=>array(
                                'value'=>$input['tagId'],
                                'minLength'=>'1',
                                'maxLength'=>'10',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
                            'contentType'=>array(
                                'value'=>$input['contentType'],
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
                                'maxLength'=>'10',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
                            'sorting'=>array(
                                'value'=>$input['sorting'],
                                'minLength'=>'1',
                                'maxLength'=>'100',
                                'validation'=>'validateStr',
                                'mandatory'=>true
                            )
                        );
        $noErrorFound = $this->validationLibObj->checkValidations($paramArray);
        return $this->returnResponse($response, $noErrorFound);
    }


}



