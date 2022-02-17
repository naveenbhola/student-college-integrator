<?php
/**
 * Populator class file for User entity
 */ 
namespace user\libraries\DataPopulators;

/**
 * Populator class for User entity
 */ 
class User extends AbstractPopulator
{
    /**
     * @var array required fields for user creation
     */
    public static $requiredFields = array('firstName','lastName','email');
    
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
     * Populate data into User entity
     *
     * @param object $user \user\Entities\User
     * @param array $data Data to be populated in
     */ 
    public function populate(\user\Entities\User $user,$data = array())
    {
        $this->setData($data);
        
        if($this->isCreation()) {
            $this->checkRequiredFields(self::$requiredFields);
        }
        
        extract($data);
           
        if($this->canSet('email')) {
            
            /**
             * Check whether email address already exists
             */
            if($user->getEmail() != $email && $this->_emailExists($email)) {
                throw new \Exception('The email id '.$email.' already exists');   
            }
            $user->setEmail($email);
        }
        
        if($this->canSet('firstName') && !empty($data['firstName'])) {
            if($this->isUpdation()) {
                $user->setFirstName($firstName);
            }
            else {
                $user->setFirstName($firstName);
                
                $displayName = $this->_generateDisplayName($firstName);
                $user->setDisplayName($displayName);
            }
        }
        
        if($this->canSet('lastName') && !empty($data['lastName'])) {
            $user->setLastName($lastName);
        }
        
        if($this->canSet('mobile') && !empty($data['mobile'])) {
            $user->setMobile($mobile);
        }
        
        if($this->canSet('password')) {
            
            if($this->isUpdation()) {
                if($password) {
                    $user->setTextPassword($password);
                    $user->setEpassword( sha256($password) );
                }
            }
            else {
                if(!$password) {
                    $password = $this->_generateNewpassword();
                }
                
                $user->setTextPassword($password);  
                $user->setEpassword( sha256($password) );
            }
        }
        
        if($this->canSet('residenceCity') || $this->canSet('residenceCityLocality') && (!empty($data['residenceCity']) || !empty($data['residenceCityLocality']))) {
            if(!$residenceCity) {
                if($residenceCityLocality) {
                    $residenceCity = $residenceCityLocality;
                }else{
                    $residenceCity = NULL;
                }
            }
            $user->setCity($residenceCity);
        }
        
        if($this->canSet('locality') || $this->canSet('residenceLocality') && (!empty($data['locality']) || !empty($data['residenceLocality']))) {
            if(!$residenceLocality) {
                $residenceLocality = NULL;
            }
            $user->setLocality($residenceLocality);
        }
        else {
            $user->setLocality(NULL);
        }
        
        if($this->canSet('age')) {
            if(!$age) {
                $age = NULL;
            }
            $user->setAge($age);
        }
        
        if($this->canSet('gender')) {
            $user->setGender($gender);
        }
        
        if($data['examTaken'] == 'yes') {
            $user->setPassport('yes');
        }
        else if($this->canSet('passport')) {
            $user->setPassport($passport);
        }
        
        if($this->canSet('workExperience')) {
			if(isset($data['isRmcStudentprofile']) && $data['isRmcStudentprofile'] == 'yes' && empty($data['workExperience']) && $data['workExperience'] != '0' ) {
                $workExperience = '-1';
            }

            if(!empty($data['workExperience'])){
               $workExperience = $data['workExperience'];
            }
            $user->setExperience($workExperience);
        }
        
        if($this->canSet('privacySettings')) {
            if(in_array('viaEmail',$privacySettings)) {
                $user->setViaemail(1);
            }
            if(in_array('viaMobile',$privacySettings)) {
                $user->setViamobile(1);
            }
            if(in_array('newsletter',$privacySettings)) {
                $user->setNewsLetterEmail(1);
            }
        }

        if(isset($data['dob'])){
            if(empty($data['dob'])){
                $data['dob'] = '0000-00-00';
            }
            $user->setDateOfBirth(new \DateTime($data['dob']));
        }

        //function to find user country using ISD code
        if(isset($data['isdCode'])){
            $isdData = $data['isdCode'];
            $isdData = explode('-', $isdData);
            
            $isdCode = $isdData[0];
            $countryID = $isdData[1];

            $user->setISDCode($isdCode);
            $user->setCountry($countryID);
        }else if($data['context'] != 'unifiedProfile'){
            $user->setCountry(2);
        }
        
        if($this->isCreation()) {
            //$user->setAvtarImageURL('/public/images/photoNotAvailable.gif');
            $user->setUserCreationDate(new \DateTime());
            $user->setLastLoginTime(new \DateTime());
            
            if($data['usergroup'] == 'fbuser'){
                $userGroup = $data['usergroup'];
            }else if(!$userGroup) {
                $userGroup = 'user';
            }
            $user->setUserGroup($userGroup);
            
            if(!$signupFlag) {
                $signupFlag = 'user';
            }
            $user->setQuickSignupFlag($signupFlag);
            
            $user->setRandomKey($this->_generateRandonKey($email));
            
            $user->setPublishInstituteFollowing(1);
            $user->setPublishInstituteUpdates(1);
            $user->setPublishRequestEBrochure(1);
            $user->setPublishBestAnswerAndLevelActivity(0);
            $user->setPublishArticleFollowing(1);
            $user->setPublishQuestionOnFB(0);
            $user->setPublishAnswerOnFB(0);
            $user->setPublishDiscussionOnFB(0);
            $user->setPublishAnnouncementOnFB(0);
            $user->setProfession('');
            $user->setDateOfBirth(new \DateTime('0000-00-00'));
        }else{
            if(isset($data['avtarimageurl'])){
                $user->setAvatarImageURL($data['avtarimageurl']);
            }
        }
        
        $user->setLastModifiedOn(new \DateTime());
    }
    
    /**
     * Generate display name from first name
     * Append a 5-digit random no. to fist name and make sure its unique in dataabse
     *
     * @param string $name
     * @return string Generated display name
     */ 
    private function _generateDisplayName($name)
    {
        $displayNameAlreadyExists = TRUE;
        $name = sanitizeString($name);
        while($displayNameAlreadyExists) {
            $displayName = $name.rand(10001,99999);
            $users = $this->repository->findByDisplayName($displayName);
            if(count($users) == 0) {
                $displayNameAlreadyExists = FALSE;
            }
        }
        
        return $displayName;
    }
    
    /**
     * Check whether an email already exists
     *
     * @param string $email
     * @return bool
     */ 
    private function _emailExists($email)
    {
        $users = $this->repository->findByEmail($email);
        return count($users) == 0 ? FALSE : TRUE;
    }
    
    /**
     * Check whether a display name already exists
     *
     * @param string $displayName
     * @return bool
     */
    private function _displayNameExists($displayName)
    {
        $users = $this->repository->findByDisplayName($displayName);
        return count($users) == 0 ? FALSE : TRUE;
    }
    
    /**
     * Generate password for new user
     *
     * @return string
     */ 
    private function _generateNewpassword()
    {
        return 'Shiksha@'.rand(100001,999999);
    }
    
    /**
     * Generate a random key
     *
     * @param string $email
     * @return string
     */ 
    private function _generateRandonKey($email)
    {
        return md5($email .':'. date());
    }
}
