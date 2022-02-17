<?php
/**
 * Populator class file for UserFlags entity
 */ 
namespace user\libraries\DataPopulators;

/**
 * Populator class for UserFlags entity
 */ 
class UserFlags extends AbstractPopulator
{
    /**
     *LDB courses to be excluded while checking for isLDBUser
     *
     * @var array 
     */ 
    public static $parentLDBCourses = array(1,35,36,37,38,39,40,41,42,43,44,45,382);
    
    /**
     * Constructor
     *
     * @param string $mode create|update
     */
    function __construct($mode = 'create')
    {
        parent::__construct($mode);
    }
    
    /**
     * Populate data into UserFlags entity
     *
     * @param object $userFlags \user\Entities\UserFlags
     * @param array $data Data to be populated in
     */
    public function populate(\user\Entities\UserFlags $userFlags,$data = array())
    {
        $this->setData($data);
        
        if($this->canSet('pendingVerification')) {
            $userFlags->setPendingVerification($this->getValue('pendingVerification',1));    
        }
        if($this->canSet('hardBounce')) {
            $userFlags->setHardBounce($this->getValue('hardBounce',0)); 
        }
        if($this->canSet('unsubscribe')) {
            $userFlags->setUnsubscribe($this->getValue('unsubscribe',0));   
        }
        if($this->canSet('ownershipChallenged')) {
            $userFlags->setOwnershipChallenged($this->getValue('ownershipChallenged',0));  
        }
        if($this->canSet('softBounce')) {
            $userFlags->setSoftBounce($this->getValue('softBounce',0));  
        }
        if($this->canSet('abused')) {
            $userFlags->setAbused($this->getValue('abused',0));   
        }
        if($this->canSet('mobileVerified')) {
            $userFlags->setMobileVerified($this->getValue('mobileVerified',0)); 
        }
        if($this->canSet('emailSentCount')) {
            $userFlags->setEmailSentCount($this->getValue('emailSentCount',0));   
        }    
        if($this->canSet('emailVerified')) {
            $userFlags->setEmailVerified($this->getValue('emailVerified',0));  
        }
        
        if($this->canSet('isMR')) {
            if($data['isMR'] === 'YES'){
                $userFlags->setIsMR('YES');
            }
            else {
                $userFlags->setIsMR(NULL);
            }
        }
        else {
            $userFlags->setIsMR(NULL);
        }
        
        if($this->canSet('mobile')) {
            
            /**
             * Check if user is NDNC
             */
            $isNDNC = 'NO';
            if($data['mobile']) {
                $this->CI->load->library('ndnc_lib');
                $result = $this->CI->ndnc_lib->ndnc_mobile_check($data['mobile']);
                
                if($result == 'true') {
                    $isNDNC = 'YES';
                }
            }
            
            $userFlags->setIsNDNC($isNDNC);
        }
        
        if($this->canSet('desiredCourse') || $this->canSet('fieldOfInterest') || $this->canSet('stream')) {
            /**
             * Check if user is LDB
             */ 
            $isLDBUser = 'NO';
            if(!empty($data['isLDBUser'])) {
                $isLDBUser = $data['isLDBUser'];
            }
            $desiredCourse = intval($data['desiredCourse']);
            $educationInterest = intval($data['fieldOfInterest']);
            $desiredGraduationLevel = $data['desiredGraduationLevel'];
            $studyAbroad = $data['isStudyAbroad'];
            
            // set flag true for national in case of mmp only
            $mmpFormId = $data['mmpFormId'];
            
            // Avoid flag to be set true if course is paid
            $isPaid = $data['isPaid'];
            // Avoid flag to be set true if contactByConsultant is no
            $contactByConsultant = $data['contactByConsultant'];

            if(!empty($data['isdCode']) && $data['isdCode'] != INDIA_ISD_CODE){
                $isLDBUser = 'NO';
                $userFlags->setIsLDBUser($isLDBUser);
            }else if(!empty($data['stream'])){
                $isLDBUser = 'YES';
                if($isPaid){
                    $isLDBUser = 'NO';
                    if(!empty($data['isLDBUser'])){
                        $isLDBUser = $data['isLDBUser'];
                    }
                } 
                
                $userFlags->setIsLDBUser($isLDBUser);
            }else if($studyAbroad != 'yes' && $isLDBUser == 'YES') {
                // do nothing
            } else if($studyAbroad == 'yes'){
                /*if(($desiredCourse && !in_array($desiredCourse,self::$parentLDBCourses) && $contactByConsultant !== 'no' && !$isPaid) || ($studyAbroad == 'yes' && $educationInterest && $desiredGraduationLevel && $contactByConsultant !== 'no' && !$isPaid)) {
                    if($data['context']=='mobile' && $data['registrationStep']=='1'){
                        error_log('TESTTT   1');
                        $isLDBUser = 'NO';
                    }else{
                        error_log('TESTTT   2');
                        $isLDBUser = 'YES';
                    }
                }
                else if($contactByConsultant == 'no' && !$isPaid) {
                    error_log('TESTTT   3');
                    $isLDBUser = 'NO';
                }*/
                $isLDBUser = $data['SAUserLDBFlag'];
                $userFlags->setIsLDBUser($isLDBUser);
            }
        }
        
        if($this->data['name'] || $this->data['email'] || $this->data['mobile']) {
            if($this->_isTestUser()) {
                $userFlags->setIsTestUser('YES');    
            }
            else {
                $userFlags->setIsTestUser('NO');    
            }
        }
    }
    
    /**
     * Function to check if the user is Test User
     *
     * @return boolean
     */
    private function _isTestUser()
    {
        require FCPATH.'globalconfig/testUserConfig.php';
        
        if($name = $this->data['name']) {
            $filter = '/^testuser[ ]*[0-9]+$/i';
            if(preg_match($filter,$name)) {
                return TRUE;
            }
        }
        
        if($email = $this->data['email']) {
            $emailDomain = end(explode('@',$email));
            if(is_array($testUserDomainsForEmail) && in_array($emailDomain,$testUserDomainsForEmail)) {
                return TRUE;
            }
        }
        
        if($mobile = $this->data['mobile']) {
            if(is_array($testUserMobileNumbers) && in_array($mobile,$testUserMobileNumbers)) {
                return TRUE;
            }    
        }
        
        return FALSE;
    }
}