<?php
/**
 * Unified context file
 */ 
namespace registration\libraries\Forms\Contexts;

/**
 * Unified context class
 */ 
class Unified extends AbstractContext
{
    /**
     * Constructor
     */ 
    function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Apply the context to field settings
     *
     * @param array $fieldSettings
     * @return array
     */ 
    public function apply($fieldSettings)
    {
        unset($fieldSettings['firstName']);
        unset($fieldSettings['lastName']);
        unset($fieldSettings['email']);
        unset($fieldSettings['securityCode']);
        
        $fieldSettings['age'] = array('type' => 'textbox','visible' => 'Yes','mandatory' => 'No');
        $fieldSettings['gender'] = array('type' => 'radio','visible' => 'Yes','mandatory' => 'No');
         
        /**
         * Logged-in user specific field settings
         */ 
        $loggedInUserData =  $this->CI->checkUserValidation();
        if(is_array($loggedInUserData) && is_array($loggedInUserData[0]) && intval($loggedInUserData[0]['userid'])) {
            $loggedInUserId = intval($loggedInUserData[0]['userid']);
           
            $this->CI->load->model('user/usermodel');
            $userModel = new \UserModel;
            if($user = $userModel->getUserById($loggedInUserId)) {
                
                /**
                 * If when plan to start already filled, unset it
                 */ 
                $pref = $user->getPreference();
                if($pref) {
                    $timeOfStart = $pref->getTimeOfStart();
                    if($timeOfStart && $timeOfStart != '0000-00-00 00:00:00') {
                        unset($fieldSettings['whenPlanToStart']);
                        unset($fieldSettings['whenPlanToGo']);
                    }
                }
                
                /**
                 * If exams already filled, unset it
                 */ 
                $userEducation = $user->getEducation();
                if($userEducation) {
                    foreach($userEducation as $education) {
                        if($education->getLevel() == 'Competitive exam') {
                            unset($fieldSettings['exams']);
                            break;
                        }
                    }
                }
                
                 /**
                 * If mobile no. already filled, unset it
                 */ 
                if($user->getMobile()) {
                    unset($fieldSettings['mobile']);
                }
            }
        }
        
        return $fieldSettings;
    }
}