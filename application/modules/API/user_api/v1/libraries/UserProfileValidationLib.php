<?php
/**
 * User Profile Validation Lib Class
 * This is the class for all the API Validations related to User Profile
 * @date    2015-10-13
 * @author  Ankit Bansal
 * @todo    none
*/

class UserProfileValidationLib
{
    private $CI;
    private $validationLibObj;

    function __construct() {
        $this->CI = &get_instance();
        $this->CI->load->library('common_api/APIValidationLib');
	   $this->validationLibObj = new APIValidationLib();
    }

    function validateEducationDetails($response, $input){

     
        $paramArray = array(
                            'EducationBackground'=>array(
                                'value'=>$input['EducationBackground'],
                                'mandatoryArray'=>true
                            ),
                        );
        $noErrorFound = $this->validationLibObj->checkValidations($paramArray);
        
        if($noErrorFound === true){
            $arr = $input['EducationBackground'];
            $allowedLevelArray = array("xth","xiith","bachelors","masters","masters");
            $tempArr = array_intersect($arr, $allowedLevelArray);

            if(count($tempArr) != count($arr)){
                $noErrorFound = array(
                                    array(
                                        'field' => 'EducationBackground',
                                        'errorMessage' => 'Invalid Value for Course Level'
                                        )
                                    );
            }

        }
    return $this->returnResponse($response, $noErrorFound);
    }


    /**
     * @desc Commonly used Function to create the Response object in case any error is found in any of the Validation API 
     * @param response object of the response class. This is required in case there is an error. We will then create error response from this class.
     * @param noErrorFound string which will be true in case no error is found. If any error is found, this will contain the error message
     * @return true in case no error is found
     * @return false in case any error is found. Also, we will set the response object with the error message
     * @date 2015-10-14
     * @author Ankit Bansal
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
     * @desc API to check Validations on UserBasicData API. In this we check all the required parameters and also the type, minLength, manLength of each Param
     * @param response object of the response class. This is required in case there is an error. We will then create error response from this class.
     * @param input array. This will contain the values of the Parameters (email and password)
     * @return true in case no error is found
     * @return false in case any error is found. Also, we will set the response object with the error message
     * @date 2015-10-12
     * @author Romil Goel
     */   
    function validateUserBasicDetails($response, $input){
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


    /**
     * @desc API to check Validations on UserBasicData API. In this we check all the required parameters and also the type, minLength, manLength of each Param
     * @param response object of the response class. This is required in case there is an error. We will then create error response from this class.
     * @param input array. This will contain the values of the Parameters (email and password)
     * @return true in case no error is found
     * @return false in case any error is found. Also, we will set the response object with the error message
     * @date 2015-10-14
     * @author Ankit Bansal
     */   
    function validateUserPersonalDetails($response, $input){
        
        $paramArray = array();
        if(array_key_exists('mobile', $input)){

            if($input['isdCode'] == "91-2"){
                $paramArray['mobile'] = array(
                                        'value'=>$input['mobile'],
                                        'minLength'=>'10',
                                        'maxLength'=>'10',
                                        'validation'=>'validateMobileInteger',
                                        'mandatory'=>true
                                    );    
            }
            else {
                $paramArray['mobile'] = array(
                                        'value'=>$input['mobile'],
                                        'minLength'=>'6',
                                        'maxLength'=>'20',
                                        'validation'=>'validateInteger',
                                        'mandatory'=>true
                                    );       
            }            
        }


        if(array_key_exists('residenceCityLocality', $input)){
            $paramArray['residenceCityLocality'] = array(
                                        'value'=>$input['residenceCityLocality'],
                                        'minLength'=>'1',
                                        'maxLength'=>'10',
                                        'validation'=>'validateInteger',
                                        'mandatory'=>true
                                    );
        }

        if(array_key_exists('dob', $input)){
            $paramArray['dob'] = array(
                                        'value'=>$input['dob'],
                                        'minLength'=>'10',
                                        'maxLength'=>'10',
                                        'validation'=>'validateDate',
                                        'mandatory'=>true
                                    );
        }

        if(array_key_exists('bio', $input)){
            $paramArray['bio'] = array(
                                        'value'=>$input['bio'],
                                        'minLength'=>'1',
                                        'maxLength'=>'500',
                                        'validation'=>'validateStr',
                                        'mandatory'=>true
                                    );
        }

        if(array_key_exists('aboutMe', $input)){
            $paramArray['aboutMe'] = array(
                                        'value'=>$input['aboutMe'],
                                        'minLength'=>'5',
                                        'maxLength'=>'45',
                                        'validation'=>'validateStr',
                                        'mandatory'=>true
                                    );
        }


        if(array_key_exists('facebookId', $input)){
            $paramArray['facebookId'] = array(
                                        'value'=>$input['facebookId'],
                                        'minLength'=>'1',
                                        'maxLength'=>'40',
                                        'validation'=>'validateFacebookURL',
                                        'mandatory'=>true
                                    );
        }
        if(array_key_exists('twitterId', $input)){
            $paramArray['twitterId'] = array(
                                        'value'=>$input['twitterId'],
                                        'minLength'=>'1',
                                        'maxLength'=>'40',
                                        'validation'=>'validateTwitterURL',
                                        'mandatory'=>true
                                    );
        }
        if(array_key_exists('linkedinId', $input)){
            $paramArray['linkedinId'] = array(
                                        'value'=>$input['linkedinId'],
                                        'minLength'=>'1',
                                        'maxLength'=>'40',
                                        'validation'=>'validateLinkedInURL',
                                        'mandatory'=>true
                                    );
        }
        if(array_key_exists('youtubeId', $input)){
            $paramArray['youtubeId'] = array(
                                        'value'=>$input['youtubeId'],
                                        'minLength'=>'1',
                                        'maxLength'=>'40',
                                        'validation'=>'validateYoutubeURL',
                                        'mandatory'=>true
                                    );
        }
        if(array_key_exists('firstName', $input)){
            $paramArray['firstName'] = array(
                                        'value'=>$input['firstName'],
                                        'minLength'=>'1',
                                        'maxLength'=>'50',
                                        'validation'=>'validateDisplayName',
                                        'mandatory'=>true
                                    );
        }
        if(array_key_exists('lastName', $input)){
            $paramArray['lastName'] = array(
                                        'value'=>$input['lastName'],
                                        'minLength'=>'1',
                                        'maxLength'=>'50',
                                        'validation'=>'validateDisplayName',
                                        'mandatory'=>true
                                    );
        }
                            
        $noErrorFound = $this->validationLibObj->checkValidations($paramArray);

        // age
        if($input['dob']){
            $age = strtotime(date("Y-m-d")) - strtotime($input['dob']);
            $age = $age/(60*60*24*365);
            $dateDiff     = strtotime($input['dob']) - strtotime(date("Y-m-d"));
            $errorOccured = 0;
            if($age <= 12){
                $errorOccured = 1;
                $errorMsg = "Age should be minimum 12 years";
            }
            if($dateDiff > 0){
                $errorOccured = 1;
                $errorMsg = "Invalid Date of Birth";
            }

            if($errorOccured == 1){
                $noErrorFound = array(
                        array(
                            'field' => "dob", 
                            'errorMessage' => $errorMsg
                        )
                );
            }
        }

        return $this->returnResponse($response, $noErrorFound);
    }


    /**
     * @desc API to check Validations on UserBasicData API. In this we check all the required parameters and also the type, minLength, manLength of each Param
     * @param response object of the response class. This is required in case there is an error. We will then create error response from this class.
     * @param input array. This will contain the values of the Parameters (userId, profileUserId, start, count)
     * @return true in case no error is found
     * @return false in case any error is found. Also, we will set the response object with the error message
     * @date 2015-10-14
     * @author Ankur Gupta
     */
    function validateUserProfilePage($response, $input){
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
                                'maxLength'=>'11',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
                            'count'=>array(
                                'value'=>$input['count'],
                                'minLength'=>'1',
                                'maxLength'=>'11',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
                            'userId'=>array(
                                'value'=>$input['profileUserId'],
                                'minLength'=>'1',
                                'maxLength'=>'11',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            )
                    );
        $noErrorFound = $this->validationLibObj->checkValidations($paramArray);
        return $this->returnResponse($response, $noErrorFound);
    }


     /*
     * @date 2015-10-14
     * @author Ankit Bansal
     */
    function validateUserLevelDetails($response, $input){
        
        $paramArray = array(
                            'EducationBackground'=>array(
                                'value'=>$input['EducationBackground'],
                                'mandatoryArray'=>true
                            ),
        
                    );
        $noErrorFound = $this->validationLibObj->checkValidations($paramArray);
        
        if($noErrorFound ===  true){


            $this->CI->load->config('UserProfileBuilderConfig');
            $configData = $this->CI->config->item('profileBuilderData');            
            $allowedLevelValues = array_keys($configData['userProfileCustomCourseLevel']);
           
            if(count(array_intersect($input['EducationBackground'],$allowedLevelValues)) != count($input['EducationBackground']))  {
                $noErrorFound = array(
                    array(
                        'field' => 'EducationBackground', 
                        'errorMessage' => 'Invalid EducationBackground'
                    )
                );
            } else {
                $noErrorFound = true;
            }
            
        }
        return $this->returnResponse($response, $noErrorFound);
    }

    /**
     * @desc API to check Validations on UserBasicData API. In this we check all the required parameters and also the type, minLength, manLength of each Param
     * @param response object of the response class. This is required in case there is an error. We will then create error response from this class.
     * @param input array. This will contain the values of the Parameters (userId, profileUserId, start, count)
     * @return true in case no error is found
     * @return false in case any error is found. Also, we will set the response object with the error message
     * @date 2015-10-14
     * @author Ankur Gupta
     */
    function validateUserProfileContentPage($response, $input){
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
                                'maxLength'=>'11',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
                            'count'=>array(
                                'value'=>$input['count'],
                                'minLength'=>'1',
                                'maxLength'=>'11',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
                            'userId'=>array(
                                'value'=>$input['profileUserId'],
                                'minLength'=>'1',
                                'maxLength'=>'11',
                                'validation'=>'validateInteger',
                                'mandatory'=>true
                            ),
                            'category'=>array(
                                'value'=>$input['category'],
                                'minLength'=>'1',
                                'maxLength'=>'30',
                                'validation'=>'validateStr',
                                'mandatory'=>true
                            )
                    );
        $noErrorFound = $this->validationLibObj->checkValidations($paramArray);
        return $this->returnResponse($response, $noErrorFound);
    }

    public function validateFollowFieldsForProfile($response, $input){

        $paramArray = array(
                            'entityType'=>array(
                                'value'=>$input['entityType'],
                                'minLength'=>'1',
                                'maxLength'=>'11',
                                'validation'=>'validateStr',
                                'mandatory'=>true
                            ),
                            'followType'=>array(
                                'value'=>$input['followType'],
                                'minLength'=>'1',
                                'maxLength'=>'30',
                                'validation'=>'validateStr',
                                'mandatory'=>true
                            ),
                            'status'=>array(
                                'value'=>$input['status'],
                                'minLength'=>'1',
                                'maxLength'=>'11',
                                'validation'=>'validateStr',
                                'mandatory'=>true
                            )
                    );
        $noErrorFound = $this->validationLibObj->checkValidations($paramArray);
        if($noErrorFound === true){
            if($input['status'] != "follow" && $input['status'] != "unfollow"){

                $noErrorFound = array(
                    array(
                        'field' => 'status', 
                        'errorMessage' => 'Invalid status'
                    )                    
                );
                return $this->returnResponse($response, $noErrorFound);
            }

            if($input['entityType'] != "tag"){

                $noErrorFound = array(
                    array(
                        'field' => 'entityType', 
                        'errorMessage' => 'Invalid entityType'
                    )                    
                );
                return $this->returnResponse($response, $noErrorFound);
            }

            if(!trim($input['entityIds'])){

                $noErrorFound = array(
                    array(
                        'field' => 'entityIds', 
                        'errorMessage' => 'Invalid entityIds'
                    )                    
                );
                return $this->returnResponse($response, $noErrorFound);
            }

            $this->CI->load->config('UserProfileBuilderConfig');
            $configData = $this->CI->config->item('profileBuilderData');            
            $allowedFollowType = $configData['allowedFollowType'];

            if(!in_array($input['followType'], $allowedFollowType)){
                $noErrorFound = array(
                    array(
                        'field' => 'followType', 
                        'errorMessage' => 'Invalid followType'
                    )                    
                );
                return $this->returnResponse($response, $noErrorFound);   
            }


        }
        return $this->returnResponse($response, $noErrorFound);
    }


     public function validateUserPrivacyBasicDetails($response, $input){

        $paramArray = array(
                            'field'=>array(
                                'value'=>$input['field'],
                                'minLength'=>'1',
                                'maxLength'=>'30',
                                'validation'=>'validateStr',
                                'mandatory'=>true
                            ),
                            'isPrivate'=>array(
                                'value'=>$input['isPrivate'],
                                'minLength'=>'1',
                                'maxLength'=>'5',
                                'validation'=>'validateStr',
                                'mandatory'=>true
                            ),
                            
                    );
        $noErrorFound = $this->validationLibObj->checkValidations($paramArray);
        if($noErrorFound === true){
           
            if($input['isPrivate'] != "YES" && $input['isPrivate'] != "NO"){

                $noErrorFound = array(
                    array(
                        'field' => 'isPrivate', 
                        'errorMessage' => 'Invalid isPrivate'
                    )                    
                );
                return $this->returnResponse($response, $noErrorFound);
            }

            $this->CI->load->config('UserProfileBuilderConfig');
            $configData = $this->CI->config->item('profileBuilderData');            
            $allowedPrivacyFieldArray = $configData['allowedPrivacyFieldArray'];

            if(!in_array($input['field'], $allowedPrivacyFieldArray)){
                $noErrorFound = array(
                    array(
                        'field' => 'field', 
                        'errorMessage' => 'Invalid privacy field'
                    )                    
                );
                return $this->returnResponse($response, $noErrorFound);   
            }


        }
        return $this->returnResponse($response, $noErrorFound);
    }


    public function validateTotalExpericeYears($response,$input){
        $paramArray = array(
                            'workExperience'=>array(
                                'value'=>$input['workExperience'],
                                'minLength'=>'1',
                                'maxLength'=>'3',
                                'mandatory'=>true
                            )
                    );

        $noErrorFound = $this->validationLibObj->checkValidations($paramArray);

        if($noErrorFound === true){
            $workExperienceLib = new \registration\libraries\FieldValueSources\WorkExperience;
            $allowedWorkExpData = array_keys($workExperienceLib->getValues());    
            if(!in_array($input['workExperience'], $allowedWorkExpData)){
                $noErrorFound = array(
                    array(
                        'field' => 'workExperience', 
                        'errorMessage' => 'Invalid workExperience'
                    )                    
                );
                return $this->returnResponse($response, $noErrorFound);   
            }
            
        }
        return $this->returnResponse($response, $noErrorFound);
    }

    public function validateWorkExpAddDetails($response,$input){
        

        $paramArray = array();

        $flag = false;
        if(array_key_exists('employer', $input)){
            $flag = true;
            $paramArray['employer'] = array(
                                            'value'=>$input['employer'],
                                            'mandatoryArray'=>true
                                            );            
        }

        $paramArray['workExp'] = array(
                                        'value'=>$input['workExp'],
                                        'mandatoryArray'=>true
                                        );            



        $noErrorFound = $this->validationLibObj->checkValidations($paramArray);

        if($noErrorFound === true && $flag){

            if(count($input['employer']) == 1){
                if($input['currentJob'][0] != "YES" && $input['currentJob'][0] != "NO"){
                    $noErrorFound = array(
                        array(
                            'field' => 'currentJob', 
                            'errorMessage' => 'Invalid isCurrentJob'
                        )                    
                    );
                    return $this->returnResponse($response, $noErrorFound);   
                }
            }
            else if(count($input['employer']) > 1) {

                // CHECK FOR ONLY ONE CURRENT JOB & ONLY YES/NO VALUES IN CURRENT JOB

                $counts = array_count_values($input['currentJob']);

                if(array_key_exists('YES', $counts) && $counts['YES'] > 1){
                    $noErrorFound = array(
                        array(
                            'field' => 'currentJob', 
                            'errorMessage' => 'Multiple Current Jobs'
                        )                    
                    );
                    return $this->returnResponse($response, $noErrorFound);   
                }
                else if(count($counts) > 2){
                    $noErrorFound = array(
                        array(
                            'field' => 'currentJob', 
                            'errorMessage' => 'Invalid Current Jobs'
                        )                    
                    );
                    return $this->returnResponse($response, $noErrorFound);   
                } else {

                    $yesCnt = 0;
                    $noCnt = 0;
                    if(array_key_exists('YES', $counts)){
                        $yesCnt = $counts['YES'];
                    }
                    if(array_key_exists('NO', $counts)){
                        $noCnt = $counts['NO'];
                    }
                    if(( $yesCnt + $noCnt ) != count($input['currentJob'])){
                        $noErrorFound = array(
                            array(
                                'field' => 'currentJob', 
                                'errorMessage' => 'Invalid Current Jobs'
                            )                    
                        );
                        return $this->returnResponse($response, $noErrorFound);   
                    }


                }
                
            }   


        }
            
        return $this->returnResponse($response, $noErrorFound);
    }


   
}


