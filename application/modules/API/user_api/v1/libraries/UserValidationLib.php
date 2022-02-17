<?php
/**
 * User Validation Lib Class
 * This is the class for all the API Validations related to User like Login, Register, Forgot Password, Rest Password
 * @date    2015-07-16
 * @author  Ankur Gupta
 * @todo    none
*/

class UserValidationLib
{
    private $CI;
    private $validationLibObj;

    function __construct() {
        $this->CI = &get_instance();
        $this->CI->load->library('common_api/APIValidationLib');
	$this->validationLibObj = new APIValidationLib();
    }

    /**
     * @desc API to check Validations on Login API. In this we check all the required parameters and also the type, minLength, manLength of each Param
     * @param response object of the response class. This is required in case there is an error. We will then create error response from this class.
     * @param input array. This will contain the values of the Parameters (email and password)
     * @return true in case no error is found
     * @return false in case any error is found. Also, we will set the response object with the error message
     * @date 2015-07-16
     * @author Ankur Gupta
     */   
    function validateLogin($response, $input){
        $paramArray = array(
                            'email'=>array(
                                'value'=>$input['email'],
                                'minLength'=>'7',
                                'maxLength'=>'125',
                                'validation'=>'validateEmail',
                                'mandatory'=>true
                            ),
                            'password'=>array(
                                'value'=>$input['password'],
                                'minLength'=>'6',
                                'maxLength'=>'25',
                                'validation'=>'validateStr',
                                'mandatory'=>true
                            )
                        );
        $noErrorFound = $this->validationLibObj->checkValidations($paramArray);
	return $this->returnResponse($response, $noErrorFound);
    }

    /**
     * @desc API to check Validations on Reset API. In this we check all the required parameters and also the type, minLength, manLength of each Param
     * @param response object of the response class. This is required in case there is an error. We will then create error response from this class.
     * @param input array. This will contain the values of the Parameters (email, password and uname)
     * @return true in case no error is found
     * @return false in case any error is found. Also, we will set the response object with the error message
     * @date 2015-07-16
     * @author Ankur Gupta
     */
    function validateResetPassword($response, $input){
        $paramArray = array(
                            'uname'=>array(
                                'value'=>$input['uname'],
                                'minLength'=>'1',
                                'maxLength'=>'50',
                                'validation'=>'validateStr',
                                'mandatory'=>true
                            ),
                            'email'=>array(
                                'value'=>$input['email'],
                                'minLength'=>'7',
                                'maxLength'=>'125',
                                'validation'=>'validateEmail',
                                'mandatory'=>true
                            ),
                            'password'=>array(
                                'value'=>$input['password'],
                                'minLength'=>'6',
                                'maxLength'=>'25',
                                'validation'=>'validateStr',
                                'mandatory'=>true
                            )
                        );
        $noErrorFound = $this->validationLibObj->checkValidations($paramArray);
        return $this->returnResponse($response, $noErrorFound);
    }

    /**
     * @desc API to check Validations on Forgot password API. In this we check all the required parameters and also the type, minLength, manLength of each Param
     * @param response object of the response class. This is required in case there is an error. We will then create error response from this class.
     * @param input array. This will contain the values of the Parameters (email)
     * @return true in case no error is found
     * @return false in case any error is found. Also, we will set the response object with the error message
     * @date 2015-07-16
     * @author Ankur Gupta
     */
    function validateForgotPassword($response, $input){
        $paramArray = array(
                            'email'=>array(
                                'value'=>$input['email'],
                                'minLength'=>'7',
                                'maxLength'=>'125',
                                'validation'=>'validateEmail',
                                'mandatory'=>true
                            )
                        );
        $noErrorFound = $this->validationLibObj->checkValidations($paramArray);
        return $this->returnResponse($response, $noErrorFound);
    }

    /**
     * @desc API to check Validations on Register API. In this we check all the required parameters and also the type, minLength, manLength of each Param
     * @param response object of the response class. This is required in case there is an error. We will then create error response from this class.
     * @param input array. This will contain the values of the Parameters (firstName, lastName, email, password)
     * @return true in case no error is found
     * @return false in case any error is found. Also, we will set the response object with the error message
     * @date 2015-07-16
     * @author Ankur Gupta
     */
    function validateRegister($response, $input){
        $paramArray = array(
                            'email'=>array(
                                'value'=>$input['email'],
                                'minLength'=>'7',
                                'maxLength'=>'125',
                                'validation'=>'validateEmail',
                                'mandatory'=>true
                            ),
                            'password'=>array(
                                'value'=>$input['password'],
                                'minLength'=>'6',
                                'maxLength'=>'25',
                                'validation'=>'validateStr',
                                'mandatory'=>true
                            ),
                            'first name'=>array(
                                'value'=>$input['firstName'],
                                'minLength'=>'1',
                                'maxLength'=>'50',
                                'validation'=>'validateDisplayName',
                                'mandatory'=>true
                            ),
                            'last name'=>array(
                                'value'=>$input['lastName'],
                                'minLength'=>'1',
                                'maxLength'=>'50',
                                'validation'=>'validateDisplayName',
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
    
    /**
     * @desc API to check Validations of user for intermediate page. In this we check all the required parameters and also the type, minLength, manLength of each Param
     * @param response object of the response class. This is required in case there is an error. We will then create error response from this class.
     * @param input array. This will contain the values of the Parameters (email)
     * @return true in case no error is found
     * @return false in case any error is found. Also, we will set the response object with the error message
     * @date 2015-07-21
     * @author Yamini Bisht
     */
    function validateUserProfileData($response, $input){
        $paramArray = array(
                            'userId'=>array(
                                'value'=>$input['userId'],
                                'minLength'=>'1',
                                'maxLength'=>'11',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
			    
			    'expertType'=>array(
                                'value'=>$input['expertType'],
                                'minLength'=>'0',
                                'maxLength'=>'50',
                                'validation'=>'validateStr',
                                'mandatory'=>false
                            ),
			    
			    'organisation name'=>array(
                                'value'=>$input['organisationName'],
                                'minLength'=>'0',
                                'maxLength'=>'500',
                                'validation'=>'validateStr',
                                'mandatory'=>false
                            )
                        );
        $noErrorFound = $this->validationLibObj->checkValidations($paramArray);
        return $this->returnResponse($response, $noErrorFound);
    }
    
     function validateFBLogin($response, $input){

        $paramArray = array(
                            'facebookAccessToken'=>array(
                                'value'=>$input['facebookAccessToken'],
                                'minLength'=>'1',
                                'maxLength'=>'1000',
                                'validation'=>'validateFBAccessToken',
                                'mandatory'=>true
                            ),
                            'email'=>array(
                                'value'=>$input['email'],
                                'minLength'=>'7',
                                'maxLength'=>'125',
                                'validation'=>'validateEmail',
                                'mandatory'=>true
                            ),
                            'first name'=>array(
                                'value'=>$input['firstName'],
                                'minLength'=>'1',
                                'maxLength'=>'50',
                                'validation'=>'validateDisplayName',
                                'mandatory'=>true
                            ),
                            'last name'=>array(
                                'value'=>$input['lastName'],
                                'minLength'=>'1',
                                'maxLength'=>'50',
                                'validation'=>'validateDisplayName',
                                'mandatory'=>true
                            ),
                            'facebookUserId'=>array(
                                'value'=>$input['facebookUserId'],
                                'minLength'=>'1',
                                'maxLength'=>'100',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            )
                        );
        $noErrorFound = $this->validationLibObj->checkValidations($paramArray);
        return $this->returnResponse($response, $noErrorFound);
    }
    
    function validateUserProfileBuilderData($response, $input){

        $paramArray = array(
	    
			    'userId'=>array(
                                'value'=>$input['userId'],
                                'minLength'=>'1',
                                'maxLength'=>'11',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
			    
                            'profileType'=>array(
                                'value'=>$input['profileType'],
                                'minLength'=>'1',
                                'maxLength'=>'20',
                                'validation'=>'validateStr',
                                'mandatory'=>true
                            ),
                            'courseInfoFilled'=>array(
                                'value'=>$input['courseInfoFilled'],
                                'minLength'=>'0',
                                'maxLength'=>'10',
                                'validation'=>'validateStr',
                                'mandatory'=>false
                            ),
                            'countryInfoFilled'=>array(
                                'value'=>$input['countryInfoFilled'],
                                'minLength'=>'0',
                                'maxLength'=>'10',
                                'validation'=>'validateStr',
                                'mandatory'=>false
                            ),
                            'courseLevelFilled'=>array(
                                'value'=>$input['courseLevelFilled'],
                                'minLength'=>'0',
                                'maxLength'=>'10',
                                'validation'=>'validateStr',
                                'mandatory'=>false
                            )
                            
                        );
        $noErrorFound = $this->validationLibObj->checkValidations($paramArray);
        return $this->returnResponse($response, $noErrorFound);
    }
    
    function validateProfileBuildeTagData($response, $input){

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
    
    function validateFeedBackFormData($response, $input){

        $paramArray = array('userId'=>array(
                                'value'=>$input['userId'],
                                'minLength'=>'1',
                                'maxLength'=>'11',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
                            'feedback text'=>array(
                                'value'=>$input['feedbackText'],
                                'minLength'=>'0',
                                'maxLength'=>'1500',
                                'validation'=>'validateStr',
                                'mandatory'=>false
                            ),
			    'usefulness'=>array(
                                'value'=>$input['usefulness'],
                                'minLength'=>'0',
                                'maxLength'=>'1',
                                'validation'=>'validateInteger',
                                'mandatory'=>false
                            ),
			    'easeOfUser'=>array(
                                'value'=>$input['easeOfUser'],
                                'minLength'=>'0',
                                'maxLength'=>'1',
                                'validation'=>'validateInteger',
                                'mandatory'=>false
                            ),
			    'lookAndFeel'=>array(
                                'value'=>$input['lookAndFeel'],
                                'minLength'=>'0',
                                'maxLength'=>'1',
                                'validation'=>'validateInteger',
                                'mandatory'=>false
                            )
                            
                        );
        $noErrorFound = $this->validationLibObj->checkValidations($paramArray);
        return $this->returnResponse($response, $noErrorFound);
    }

    function validateUserId($response, $input){
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

}


