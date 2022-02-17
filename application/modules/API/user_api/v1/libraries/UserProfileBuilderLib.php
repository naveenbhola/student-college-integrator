<?php
class UserProfileBuilderLib {

    private $CI;
    private $validationLibObj;

    function __construct() {
        $this->CI = &get_instance();
        $this->CI->load->library('common_api/APIValidationLib');
        $this->validationLibObj = new APIValidationLib();
    }
    
    /**
         * @desc API to fetch data for user profile builder
         * @date 2015-08-20
         * @author Yamini Bisht
         */
    
    function getProfileBuilderInfo($userId){
        
        $this->userprofilebuildermodel = $this->CI->load->model('user/userprofilebuildermodel');

	if($userId > 0){
        	$userProfileType = $this->userprofilebuildermodel->getUserProfileType($userId);
	}
        
        $result['courseInfoFilled'] = false;
        $result['countryInfoFilled'] = false;
        
        if(!empty($userProfileType)){      
            $result['profileType'] = $userProfileType;
        }else{
            $result['profileType'] = 'consumer';
        }
        
        $questionComplete = 0;
        
        if($userId != ''){
            
            $courseInfo  = $this->userprofilebuildermodel->getUserTagInfo($userId,array('stream','stream_interest'));
            if(!empty($courseInfo)){
                $questionCompleted = $questionCompleted + 1;
                $result['courseInfoFilled'] = true;
            }
            
            $countryInfo  = $this->userprofilebuildermodel->getUserTagInfo($userId,array('country','countries_interest'));
            if(!empty($countryInfo)){
                $questionCompleted = $questionCompleted + 1;
                $result['countryInfoFilled'] = true;
            }        
            
            if($result['profileType'] == 'consumer'){
                
                $result['courseLevelFilled'] = false;
                $this->CI->load->config('v1/UserProfileBuilderConfig',TRUE);
                $this->profileBuilderArray = $this->CI->config->item('profileBuilderData','UserProfileBuilderConfig');
                $courseLevels = $this->profileBuilderArray['courseLevel'];
            
                    $courseLevelData  = $this->userprofilebuildermodel->getUserTagInfo($userId,array('course_level'));
                    
                    if(!empty($courseLevelData)){
                        $questionCompleted = $questionCompleted + 1;
                        $result['courseLevelFilled'] = true;
                    }   
                    
            }
            
            
            if($userProfileType == 'producer'){
                $totalQues = 2;
                
            }else{
                $totalQues = 3;
            }
            
            $quesLeft = $totalQues - $questionCompleted;
            
            if($totalQues == $questionCompleted){                
                return $result= NULL;
            }else{
                $result['message'] = ($quesLeft == 1) ? "Answer ".$quesLeft." simple question to see relevant questions and discussions!" : "Answer ".$quesLeft." simple questions to see relevant questions and discussions!";
            }
            
        }
        
        return $result;
        
    }
}
?>
