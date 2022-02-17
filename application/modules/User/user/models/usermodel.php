<?php
/**
 * User model class file
 */

 
/**
 * User model class
 */ 
class UserModel extends MY_Model
{
    /**
     * DB Handler
     * @var Object DB Handle
     */ 
    private $dbHandle;
    
    /**
     * Array of Object for \user\libraries\RegistrationObservers
     * @var array of objects \user\libraries\RegistrationObservers
     */ 
    private $registrationObservers;
    
    /**
     * Object for User repository \user\Repositories\UserRepository
     * @var object User repository \user\Repositories\UserRepository
     */
    private $repository;
	
    /**
     * Object for user repository \user\Repositories\UserRepository
     * @var object User repository \user\Repositories\UserRepository
     */
    private $writeRepository;
    
    /**
     * Constructor
     */ 
    function __construct()
    {
        parent::__construct('User');
        $this->repository = \user\Builders\UserBuilder::getUserRepository();
		$this->writeRepository = \user\Builders\UserBuilder::getUserRepository('write');
        

        //$cacheDriver = new \Doctrine\Common\Cache\XcacheCache();
        //$cacheDriver->deleteAll();
    }
    
    /**
     * Initiate the model
     *
     * @param string $operation
     */ 
    private function initiateModel($operation = 'read')
    {
		if($operation=='read'){
			$this->dbHandle = $this->getReadHandle();
		}
		else{
        	$this->dbHandle = $this->getWriteHandle();
		}
	}
    
    /**
     * Add a registration observer
     *
     * @param object $observer 
     */ 
    public function addRegistrationObserver(\user\libraries\RegistrationObservers\AbstractObserver $observer)
    {
        $this->registrationObservers[] = $observer;
    }

    /**
     * Notify registration observers after successful registration
     *
     * @param object $observer 
     */ 
    private function _notifyObservers($user,$data)
    {
        foreach($this->registrationObservers as $observer) {
            $observer->update($user,$data);
        }
    }
    
    /**
     * Create a new user using data provided
     *
     * @param array $data
     * @return object Newly created user \user\Entities\User
     */ 
    public function createUser($data)
    {
        $user = new \user\Entities\User;
        $this->_populateData($user,$data);   
        
        $this->writeRepository->store($user);
        $this->_notifyObservers($user,$data);
        return $user;
    }
    
    /**
     * Update a user with data provided
     *
     * @param array $data
     * @return object updated user \user\Entities\User
     */ 
    public function update($data)
    {
        $userId = $data['userId'];
        $user = $this->writeRepository->find($userId);
        
        if(!$user || !$user->getId()) {
            throw new Exception("User with id ".$userId." does not exist");
        }
        
        $userpoint = new \user\libraries\RegistrationObservers\UserPointUpdation;
        $userpoint->setUserOldData($user, $data);

        $this->_populateData($user,$data,'update');
        $this->writeRepository->store($user);

        $userpoint->update($user, $data);

		$this->updateUserData($userId);
        $this->_notifyObservers($user,$data);

        return $user;
    }
    

    private function _getLDBFlagForSAUser($user, $data, $mode){
        $isLDBUser = 'NO';
        if(!empty($data['isdCode']) && $data['isdCode'] != INDIA_ISD_CODE){
            $isLDBUser = 'NO';
        }else{
            if($mode == 'create'){   // new user
                if(empty($data['listingTypeIdForBrochure'])){  // registration case
                    $isLDBUser = "YES";
                }else{     // response case
                    $packType = $this->_getSAListingPackType($data);
                    if($packType == 1){
                        $isLDBUser = "NO";
                    }else{
                        $isLDBUser = "YES";
                    }
                }
            }else{  // user update flow
                $isLDBUser = $user->isLDB() ? "YES" : "NO";
                $preferences = $user->getPreference();
                if(is_object($preferences) && $preferences->getExtraFlag() == '' &&  $preferences->getEducationLevel() == ''){
                    // short user
                    if(empty($data['listingTypeIdForBrochure'])){  // registration case
                        $isLDBUser = "YES";
                    }else{     // response case
                        $packType = $this->_getSAListingPackType($data);
                        if($packType == 1){
                            $isLDBUser = "NO";
                        }else{
                            $isLDBUser = "YES";
                        }
                    }
                }
            }
        }
        return $isLDBUser;
    }

    private function _getSAListingPackType($data){
        $packType = 0;
        if(!empty($data['listingTypeIdForBrochure']) && $data['listingTypeIdForBrochure'] >0){
            $listingId = $data['listingTypeIdForBrochure'];
            if($data['listingTypeForBrochure'] == 'scholarship'){
                $this->load->builder('scholarshipsDetailPage/scholarshipBuilder');
                $scholarshipBuilder    = new scholarshipBuilder();
                $scholarshipRepository = $scholarshipBuilder->getScholarshipRepository();
                $sections = array('basic'=>array('scholarshipId','subscriptionType'));
                $scholarshipObj            = $scholarshipRepository->find($listingId,$sections);
                if(is_object($scholarshipObj) || !empty($scholarshipObj)){
                    $packType = $scholarshipObj->isPaid();
                }
            }else if($data['listingTypeForBrochure'] == 'course'){
                $this->load->builder('ListingBuilder','listing');
                $listingBuilder     = new ListingBuilder;
                $this->abroadCourseRepository           = $listingBuilder->getAbroadCourseRepository();
                $courseObj             = $this->abroadCourseRepository->find($listingId);
                if(is_object($courseObj) || !empty($courseObj)){
                    $packType = $courseObj->isPaid();
                }
            }
        }
        return $packType;
    }   

    /**
     * Populate data into user entity and its sub-entities
     *
     * @param object $user \user\Entities\User
     * @param array $data
     * @param string mode Whether creating new user or updating old user (create|update)
     */ 
    private function _populateData(\user\Entities\User $user,$data,$mode = 'create')
    {
        
        /*Logic for registration tracking and submit_date updatation logic */        
        $data['mode'] = $mode;
        $data['isCityChanged'] = false;
        $data['isCountryChanged'] = false;
        $data['isDesiredCourseChanged'] = false;
        $data['isPrefCountriesChanged'] = false;

        if($data['isStudyAbroad'] == "yes"){
            $data['SAUserLDBFlag'] = $this->_getLDBFlagForSAUser($user, $data, $mode);
        }

        if($mode == 'update'){

            if(!empty($data['residenceCity']) && $data['residenceCity'] != $user->getCity()){
                $data['isCityChanged'] = true;
            }

            if(!empty($data['residenceCityLocality']) && $data['residenceCityLocality'] != $user->getCity()){
                $data['isCityChanged'] = true;
            }

            if(!empty($data['isdCode'])){
                $isdData = $data['isdCode'];
                $isdData = explode('-', $isdData);
            
                $countryID = $isdData[1];
                if($countryID != $user->getCountry()){
                    $data['isCountryChanged'] = true;
                }
            }

            if(!empty($data['desiredCourse'])){
                $userPref = $user->getPreferences();
            }

        }else{
            $data['isDesiredCourseChanged'] = true;
        }

        $data['courseLevelData'] = $this->_getEducationLevelByBaseCourses($data['baseCourses']);

        /**
         * Populate user data
         */ 
        $populator = new \user\libraries\DataPopulators\User($mode);
        $populator->populate($user,$data);

        /**
         * Populate user Preferences
         */
        $populator = new \user\libraries\DataPopulators\UserPref($mode);
        
        $createNewPreference = TRUE;
        
        if($mode == 'update') {
            $preferences = $user->getPreferences();
            if(count($preferences) > 0 && $pref = $preferences[0]) {
                
                /*cases to identify change of desired cource */
                if($data['isTestPrep'] != 'yes' && !empty($data['desiredCourse']) && $data['desiredCourse'] != $pref->getDesiredCourse()){
                    $data['isDesiredCourseChanged'] = true;
                }else if($data['isStudyAbroad'] == 'yes'){

                    if($data['abroadSpecialization'] != $pref->getAbroadSpecialization()){
                        $data['isDesiredCourseChanged'] = true;
                    }else if(empty($data['desiredCourse']) && !empty($data['fieldOfInterest'])){
                        $desiredCourse = $this->_getDesiredCourseForStudyAbroad($data['fieldOfInterest'], $data['desiredGraduationLevel']);
                        if($desiredCourse != $pref->getDesiredCourse()){
                            $data['isDesiredCourseChanged'] = true;
                        }
                    }

                }

                $this->_updateUserImplicitEducationLevel($pref, $data);
                
                $populator->populate($pref,$data);
                $createNewPreference = FALSE;
			}
        }
	
		if($createNewPreference) {
            $pref = new \user\Entities\UserPref;
            $populator->populate($pref,$data);
            $pref->setUser($user);
            $user->addUserPref($pref);
        }
        
        /*A we have to update interest data only in registration and in profile update flows */
        if(!empty($data['stream']) && ($mode == 'create' || $data['isResponseForm'] != 'yes') ){
            $this->_populateUserpref($user, $data, $mode);
        }else if($data['isStudyAbroad'] == 'yes' && $mode == 'update'){
            /*National Explicit Interest */
            $this->_deleteOldUserPrefs($data['userId'], 'draft');

            /*National Implicit Interest */
            $this->load->model('response/responsemodel');
            $responseModel = new ResponseModel();
            $responseModel->updateResponseProfileStatusByUserId($data['userId']);
        }

        /**
         * Populate location Preferences
         */
        $populator = new \user\libraries\DataPopulators\UserLocationPref;

        /**
         * Preferred study locations
         * These consists of cities and states
         */ 
        if($data['preferredStudyLocation'] && is_array($data['preferredStudyLocation']) && count($data['preferredStudyLocation']) > 0) {
            
            $states = $data['preferredStudyLocation']['states'];
            $cities = $data['preferredStudyLocation']['cities'];
            
            if(is_array($states) && count($states)) {
                foreach($states as $stateId) {
                    if(intval($stateId) > 0) {
                        $locationPref = new \user\Entities\UserLocationPref;
                        $populator->populate($locationPref,array('stateId' => $stateId));
                        $locationPref->setUser($user);
                        $locationPref->setPreference($pref);
                        $pref->addUserLocationPref($locationPref);
                    }    
                }
            }
            
            if(is_array($cities) && count($cities)) {
                foreach($cities as $cityId) {
                    if(intval($cityId) > 0) {
                        $locationPref = new \user\Entities\UserLocationPref;
                        $populator->populate($locationPref,array('cityId' => $cityId));
                        $locationPref->setUser($user);
                        $locationPref->setPreference($pref);
                        $pref->addUserLocationPref($locationPref);
                    }    
                }
            }
        }
        
		/**
         * For localities 'Anywhere', we'll save the city id
         */
		if($data['preferredStudyLocalityCity'] && is_array($data['preferredStudyLocalityCity']) && count($data['preferredStudyLocalityCity']) > 0) {
            for($i=0;$i<count($data['preferredStudyLocalityCity']);$i++) {
				$preferredStudyLocalityCityId = $data['preferredStudyLocalityCity'][$i];
				$preferredStudyLocalityId = $data['preferredStudyLocality'][$i];
                if(intval($preferredStudyLocalityCityId) > 0 && $preferredStudyLocalityId == '-1') {
                    $locationPref = new \user\Entities\UserLocationPref;
                    $populator->populate($locationPref,array('cityId' => $preferredStudyLocalityCityId));
                    $locationPref->setUser($user);
                    $locationPref->setPreference($pref);
                    $pref->addUserLocationPref($locationPref);
                }
            }
        }
		
        /**
         * Populate preferred localities
         */ 
        if($data['preferredStudyLocality'] && is_array($data['preferredStudyLocality']) && count($data['preferredStudyLocality']) > 0) {
            foreach($data['preferredStudyLocality'] as $localityId) {
                if(intval($localityId) > 0) {
                    $locationPref = new \user\Entities\UserLocationPref;
                    $populator->populate($locationPref,array('localityId' => $localityId));
                    $locationPref->setUser($user);
                    $locationPref->setPreference($pref);
                    $pref->addUserLocationPref($locationPref);
                }
            }
        }

        /**
         * Populate preferred countries
         */ 
        if($data['destinationCountry'] && is_array($data['destinationCountry']) && count($data['destinationCountry']) > 0) {

            $data['isPrefCountriesChanged'] = $this->_isPrefCountriesChanged($user, $data['destinationCountry']);

			$this->deleteUserAbroaddestinationCountry($data['userId']);
            foreach($data['destinationCountry'] as $countryId) {
                if(intval($countryId) > 0) {
                    $locationPref = new \user\Entities\UserLocationPref;
                    $populator->populate($locationPref,array('countryId' => $countryId));
                    $locationPref->setUser($user);
                    $locationPref->setPreference($pref);
                    $pref->addUserLocationPref($locationPref);
                }
            }
        }
        
        /**
         * Populate education preferences
         */
    	$makeUserEducationEntry = true;

        $checkUserEducation = ($data['isStudyAbroad'] == 'yes' && (empty($data['exams']) || empty($data['examTaken']) || ($data['examTaken'] == 'no'||$data['examTaken'] == 'bookedExamDate') ) 
                                && !isset($data['tenthmarks']) && !isset($data['graduationStream']));

    	if($checkUserEducation){
    	    if(isset($data['examTobeDeleted'])){
                $examToBeDeleted = json_decode($data['examTobeDeleted']);
            
                if(count($examToBeDeleted)>0) {
                    $this->_backupUserExams($data['userId'], $examToBeDeleted);
                    $this->deleteUserExams($data['userId'],$examToBeDeleted);
                }  
            }
            $makeUserEducationEntry = false;
    	}
    	if($makeUserEducationEntry) {
            $educationLevels = \user\libraries\DataPopulators\UserEducation::getEducationLevelsInData($data);
    	    $userEducation = $user->getEducation();
    	    
    	    $registeredLevels = array();
    	    foreach($userEducation as $education) {
        		if($education->getLevel() == 'UG') {
        		    $registeredLevels['graduation'] = $education;
        		}
        		else if($education->getLevel() == '12') {
        		    $registeredLevels['xii'] = $education;
        		}
        		else if($education->getLevel() == 'Competitive exam') {
        		    $registeredLevels['exam-'.strtoupper($education->getName())] = $education;
        		}

                if($education->getLevel() == 'UG' && isset($data['tenthmarks'])){
                    $levelsToBeDeleted[] = 'UG';
                }
                if($education->getLevel() == 'PG' && isset($data['graduationStream'])){
                    $levelsToBeDeleted[] = 'PG';
                }
                if($education->getLevel() == '12' && isset($data['12thBoard'])){
                    $levelsToBeDeleted[] = '12';
                }
                
                // Level to be deleted from tUserEducation table for unified profile
                if($education->getLevel() == 'PHD' && in_array('phd', $data['EducationBackground'])){
                    $levelsToBeDeleted[] = 'phd';
                }

                if($education->getLevel() == '10' && in_array('xth', $data['EducationBackground'])){
                    $levelsToBeDeleted[] = '10';
                }

                if($education->getLevel() == '12' && in_array('xiith', $data['EducationBackground'])){
                    $levelsToBeDeleted[] = '12';
                }

                if($education->getLevel() == 'UG' && in_array('bachelors', $data['EducationBackground'])){
                    $levelsToBeDeleted[] = 'UG';
                }

                if($education->getLevel() == 'PG' && in_array('masters', $data['EducationBackground'])){
                    $levelsToBeDeleted[] = 'PG';
                }
    	    }
                
            if($data['isStudyAbroad'] != 'yes' && $data['isUnifiedProfile'] != 'yes'){
                if($data['graduationCompletionYear'] > 0) {
                    $educationLevels[] = 'graduation';
                }
                else if($data['xiiYear'] > 0) {
                    $educationLevels[] = 'xii';
                }
            } else if($data['isStudyAbroad'] == 'yes' && $data['registrationSource'] == 'MARKETING_FORM' && $mode == 'update') {

                if($data['courseLevel'] == 'UG' && isset($data['tenthmarks'])){
                    $levelPGkey = array_search('PG', $levelsToBeDeleted);
                    if($levelPGkey >= 0) {
                        unset($levelsToBeDeleted[$levelPGkey]);
                    }
                    $levelsToBeDeleted[] = '10';
                } else if(($data['courseLevel'] == 'PG' || $data['courseLevel'] == 'PhD') && isset($data['graduationStream'])){             
                    $levelPGkey = array_search('PG', $levelsToBeDeleted);
                    if($levelPGkey >= 0) {
                        unset($levelsToBeDeleted[$levelPGkey]);
                    }
                    $levelsToBeDeleted[] = 'PG';
                }

                if($data['courseLevel'] == 'UG') {
                    $levelkey = (string)(array_search('PG', $educationLevels));
                    
                    if($levelkey != '' && $levelkey >= '0') {
                        unset($educationLevels[$levelkey]);
                        unset($levelkey);
                    }
                } else if($data['courseLevel'] == 'PG' || $data['courseLevel'] == 'PhD') {
                    $levelkey = (string)(array_search('xthWithoutSub', $educationLevels));
                    
                    if($levelkey != '' && $levelkey >= '0') {
                        unset($educationLevels[$levelkey]);
                        unset($levelkey);
                    }
                }
                
            }
    	    
    	    $deletedLevels = array_diff(array_keys($registeredLevels), $educationLevels);
    	    foreach($deletedLevels as $key => $level) {
        		if(stripos($level, 'exam') !== false) {
        		    $level = explode("-", $level);
        		    $exam = $level[1];
        		    if(!array_key_exists($exam.'_score', $data)) {
        			unset($deletedLevels[$key]);
        		    }
        		    else {
        			$deletedLevels[$key] = $exam;
        		    }
        		}
        		else {
        		    unset($deletedLevels[$key]);
        		}
    	    }

            // If user delete any from study abroad accounts and settings page, then delete those exams from backend
            if(isset($data['examTobeDeleted'])){
                $examToBeDeleted = json_decode($data['examTobeDeleted']);
            
                if(count($examToBeDeleted)>0) {

                $oldExams = array();
                $deleteExams = array();

                foreach ($examToBeDeleted as $key => $value) {
                    $oldExams[] = "exam-".$value;
                }

                $deleteExams = array_diff($oldExams, $educationLevels);
                 foreach($deleteExams as $key => $level) {
                    if(stripos($level, 'exam') !== false) {
                    $level = explode("-", $level);
                    $exam = $level[1];
                    $deleteExams[$key] = $exam;
                    }else {
                        unset($deletedLevels[$key]);
                    }
                 }
                    $deletedLevels = array_merge($deletedLevels, $deleteExams);
                }
            }
            
    	    if(count($deletedLevels)) {
                    $this->_backupUserExams($data['userId'], $deletedLevels);
    		        $this->deleteUserExams($data['userId'],$deletedLevels);
    	    }


            if(!empty($levelsToBeDeleted) && count($levelsToBeDeleted)){
                $this->_backupUserEducation($data['userId'], $levelsToBeDeleted);
                $this->deleteUserEducation($data['userId'],$levelsToBeDeleted);
            }

    	    if(is_array($educationLevels) && count($educationLevels) > 0) {
        		$populator = new \user\libraries\DataPopulators\UserEducation;
        		foreach($educationLevels as $educationLevel) {
        		    if($mode == 'create' || empty($registeredLevels[$educationLevel])) {
        				$education = new \user\Entities\UserEducation;

                        $populator->populate($education,$data,array('level' => $educationLevel,'examGroupId'=>$data["examGroupIds"]));
                        
        				$education->setUser($user);
        				$user->addUserEducation($education);
        		    }
        		    else if($mode == 'update') {
                        if(stripos($educationLevel, 'exam') !== false) {
                            $level = explode("-", $educationLevel);
                            $exam = $level[1];
                            $this->_backupUserExams($data['userId'], array($exam));
                        }else{
                            $this->_backupUserExams($data['userId'], array($educationLevel));
                        }
        			    $populator->populate($registeredLevels[$educationLevel],$data,array('level' => $educationLevel,'examGroupId'=>$data["examGroupIds"]));
        		    }
        		}
    	    }
        }
        
        /**
         * Populate specialization preferences
         */
        if($data['specialization'] > 0) {
			$specializationPref = new \user\Entities\UserSpecializationPref;
			$populator = new \user\libraries\DataPopulators\UserSpecializationPref;
			$populator->populate($specializationPref,array('specializationId' => $data['specialization']));
			if($mode == 'create') {
				$specializationPref->setUser($user);
				$specializationPref->setPreference($pref);
				$pref->addUserSpecializationPref($specializationPref);
				$user->addUserSpecializationPref($specializationPref);
			} else if ($mode == 'update') {
				$specialization = $pref->getSpecializationPreferences();
				$specialization_details = $specialization[0];
				if(empty($specialization_details)) {
					$specializationPref->setUser($user);
					$specializationPref->setPreference($pref);
					$pref->addUserSpecializationPref($specializationPref);
					$user->addUserSpecializationPref($specializationPref);					
				} else {
					$populator->populate($specialization_details,array('specializationId' => $data['specialization']));
				}
				
			}
        } else {
			if ($mode == 'update') {
				$this->deleteUserSpecialization($data['userId']);
			}			
		}
        
        /**
         * Populate test prep specialization preferences
         */
        
        if($data['isTestPrep'] == 'yes' && $data['desiredCourse']) {
            $specializationPref = new \user\Entities\UserTestPrepSpecializationPref;
            $populator = new \user\libraries\DataPopulators\UserTestPrepSpecializationPref;
            
            /* Fetch previous stored blog Id */
            $previousData = $pref->getTestPrepSpecializationPreferences();
            foreach($previousData as $key=>$value){
                $objPreviousData = $value;
            }
            if(is_object($objPreviousData) && !empty($data['desiredCourse']) && $data['desiredCourse'] != $objPreviousData->getSpecializationId()){
                $data['isDesiredCourseChanged'] = true;
            }

            $populator->populate($specializationPref,array('specializationId' => $data['desiredCourse']));
            $specializationPref->setPreference($pref);
        
            if($mode == 'update') {
                $previousData = $pref->getTestPrepSpecializationPreferences();
                foreach($previousData as $key=>$value){
                    $objPreviousData = $value;
                }
                
                if(empty($objPreviousData)) {
                    $data['isDesiredCourseChanged'] = true;
                    $pref->addUserTestPrepSpecializationPref($specializationPref);
                }
                else {
                    $populator->populate($objPreviousData,array('specializationId' => $data['desiredCourse']));
                    $pref->updateUserTestPrepSpecializationPref(array($objPreviousData));
                    
        		}
            }else{
        		$pref->addUserTestPrepSpecializationPref($specializationPref);
            }
        }
        
        /**
         * Populate user flags
         */
        $populator = new \user\libraries\DataPopulators\UserFlags($mode);
        
        // Avoid flag isMR to be set null if course is paid
        if(!$data['mmpFormId'] && $data['isStudyAbroad'] != 'yes'){

            if($data['course']){
                $this->load->builder('ListingBuilder','listing');
                $listingBuilder = new ListingBuilder;
                $courseRepository = $listingBuilder->getCourseRepository();
                $courseObj = $courseRepository->find($data['course']);
                if(is_object($courseObj)){
                    $data['isMR'] = $courseObj->isPaid()?null:$data['isMR'];
                    $data['isPaid'] = $courseObj->isPaid();
                }
            }else if($data['clientCourse'] && $data['listing_type'] == 'course'){
                $this->load->builder("nationalCourse/CourseBuilder");
                $builder   = new CourseBuilder();
                $repo      = $builder->getCourseRepository();
                $courseObj = $repo->find($data['clientCourse'], array('basic'));
                if(is_object($courseObj)){
                    $data['isMR'] = $courseObj->isPaid()?null:$data['isMR'];
                    $data['isPaid'] = $courseObj->isPaid();
                }
            }else if($data['clientCourse'] && $data['listing_type'] == 'exam'){
                $this->load->library('response/responseAllocationLib');
                $responseLib    = new responseAllocationLib();
                $data['isPaid'] = $responseLib->getMatchedSubscriptions($data['clientCourse'], array($data['residenceCityLocality']), true);
                
            }
        }

        if($mode == 'update') {
            $flags = $user->getFlags();
			$data['isLDBUser'] = $user->isLDB();
            $populator->populate($flags,$data);
        }
        else {
            $flags = new \user\Entities\UserFlags;
            $populator->populate($flags,$data);
            $flags->setUser($user);
            $user->setFlags($flags);
        }
        
        if($mode == 'create') {
            /*
             * Populate user points
             */ 
            $pointSystem = new \user\Entities\UserPointSystem;
            $populator = new \user\libraries\DataPopulators\UserPointSystem;
            $populator->populate($pointSystem,$data);
            $pointSystem->setUser($user);
            $user->setPointSystem($pointSystem);
            
            /*
             * Populate registration source
             */
            if($data['registrationSource']) {
                $registrationSource = new \user\Entities\UserRegistrationSource;
                $populator = new \user\libraries\DataPopulators\UserRegistrationSource;
                $populator->populate($registrationSource,$data);
                $registrationSource->setUser($user);
                $user->setRegistrationSource($registrationSource);
            }
            
            /*
             * Populate my shiksha page sata
             */
            $populator = new \user\libraries\DataPopulators\UserMyPageComponent;
            
            foreach(\user\libraries\DataPopulators\UserMyPageComponent::$components as $componentData) {
                $myPageComponent = new \user\Entities\UserMyPageComponent;
                $populator->populate($myPageComponent,$componentData);
                $myPageComponent->setUser($user);
                $user->addUserMyPageComponent($myPageComponent);
            }
        }

        $this->_populateAppliedData($user, $data, $mode);
        $this->_populateWorkExData($user, $data);

        /* Data for the registration tracking should be populated at the last */
        if($data['isDesiredCourseChanged'] || $data['isCityChanged'] || $data['isCountryChanged'] || $data['isPrefCountriesChanged']){
            $this->_populateTrackingData($user, $data, $mode);
        }
    }

     /*
      * Populate registration tracking data
      */
    private function _populateTrackingData(\user\Entities\User $user, $data = array(), $mode = 'create'){
        if($mode == 'create'){
            $data['isNewReg'] = 'yes';
        }else{
            if(empty($data['residenceCity']) && empty($data['residenceCityLocality'])){
                $data['residenceCity'] = $user->getCity();
            }

            if(empty($data['isdCode'])){
                $ISDcode = $user->getISDCode();
                $country = $user->getCountry();
                $data['isdCode'] = $ISDcode.'-'.$country;
            }
            
            if(empty($data['desiredCourse'])){
                $userprefObj = $user->getPreference();
                $data['desiredCourse'] = $userprefObj->getDesiredCourse();
                $userTestPref = $userprefObj->getTestPrepSpecializationPreferences();
                if($data['desiredCourse'] == 0 && is_object($userTestPref[0])){

                    $data['desiredCourse'] = $userTestPref[0]->getSpecializationId();
                    $data['isTestPrep'] = 'yes';
                }else{
                    if($userprefObj->getExtraFlag() == 'studyabroad'){
                        $data['isStudyAbroad'] = 'yes';
                    }
                }
            }
            
            if(empty($data['destinationCountry'])){
                $locationPref = $user->getLocationPreferences();
                foreach ($locationPref as $locationObj) {
                    $data['destinationCountry'][] = $locationObj->getCountryId();
                }
            }
        }
        
        if($data['isTestPrep'] == 'yes' && !empty($data['desiredCourse'])){
            $testPrepData = $this->getTestPrepSubCat($data['desiredCourse']);
            $data['subCatId'] = $testPrepData['boardId'];
            $data['fieldOfInterest'] = $testPrepData['parentId'];
        }else{
            if($data['isStudyAbroad'] == 'yes'){
                 $data['subCatId'] = empty($data['abroadSpecialization'])? '0':$data['abroadSpecialization'];
            }else{
                $data['subCatId'] = $this->getSubCategoryIdFromLDBCourseId($data['desiredCourse']);
            }
            if(empty($data['fieldOfInterest'])){
                $data['fieldOfInterest'] = $this->getCategoryIdFromLDBCourseId($data['desiredCourse']);
            }
        }

        //if($data['fieldOfInterest'] == 1508 || $data['fieldOfInterest'] == 1509 || $data['fieldOfInterest'] == 1510){
        global $studyAbroadAllDesiredCourses;
        if(in_array($data['fieldOfInterest'], $studyAbroadAllDesiredCourses)) {
            $data['fieldOfInterest'] = 1;
        }
        
        if($this->getSessionSourceType() == 'paid'){
            $data['source'] = 'paid';
        }else{
            $data['source'] = 'free';
        }

        $registrationTracking = new \user\Entities\RegistrationTracking;
        $populator = new \user\libraries\DataPopulators\RegistrationTracking;
        
        $populator->populate($registrationTracking,$data);
        $registrationTracking->setUser($user);
        $user->addRegistrationTracking($registrationTracking);

    }

    /*
     * Populate user Work Experience
     */
    private function _populateWorkExData(\user\Entities\User $user, $data = array()){

        if($data['workExp']){
            $index = 0;
            $oldUserWorkExp = $user->getUserWorkExp();
            
            // Edit case
            if($oldUserWorkExp && !isset($data['isNewWorkExp'])){
                $array_id = array();
                foreach($oldUserWorkExp as $UserWork){
                    $array_id[] = $UserWork->getId();
                }
                $this->deleteUserWorkExp($array_id, $user->getId());
            }else if(in_array('YES', $data['currentJob'])){
                $this->updateCurrentJobStatusToNO($user->getId());
            }

            $flag = (isset($data['employer']) || isset($data['designation']) || isset($data['department']) || isset($data['startDate']) || isset($data['endDate']) || isset($data['currentJob']) );
            if(!$flag){
                return;
            }
            
            foreach($data['workExp'] as $workExp){

               if(empty($data['employer'][$index])){
                    $index++;
                    continue;
               }

                $workExpDetails = array();
                $workExpDetails['employer'] = isset($data['employer'][$index])? $data['employer'][$index]: NULL;
                $workExpDetails['designation'] = isset($data['designation'][$index])? $data['designation'][$index]: NULL; 
                $workExpDetails['department'] = isset($data['department'][$index])? $data['department'][$index]: NULL;
                $workExpDetails['startDate'] = isset($data['startDate'][$index])? $data['startDate'][$index]: '0000-00-00';
                $workExpDetails['endDate'] = isset($data['endDate'][$index])? $data['endDate'][$index]: '0000-00-00';
                $workExpDetails['currentJob'] = isset($data['currentJob'][$index])? $data['currentJob'][$index]: 'NO';
                
                $UserWorkExp = new \user\Entities\UserWorkExp;
                $populator = new \user\libraries\DataPopulators\UserWorkExp;
                
                $populator->populate($UserWorkExp,$workExpDetails);
                $UserWorkExp->setUser($user);
                $user->setUserWorkExp($UserWorkExp);
                $index++;
            }
        }   
    }

    private function _populateAppliedData(\user\Entities\User $user, $data = array(), $mode = 'create'){
        
        /*
         * Populate user social profile
         */

        $isSocialProfileDataAvail = (
                                        isset($data['twitterId']) || isset($data['facebookId']) || isset($data['linkedinId']) 
                                        || isset($data['personalURL']) || isset($data['youtubeId'])
                                    );

        if($isSocialProfileDataAvail){
            if($user->getSocialProfile()){
                $this->deleteUserSocailProfile($user->getSocialProfile()->getId());
            }

            $UserSocialProfile = new \user\Entities\UserSocialProfile;

            $populator = new \user\libraries\DataPopulators\UserSocialProfile;

            $populator->populate($UserSocialProfile,$data);
            $UserSocialProfile->setUser($user);
            $user->setSocialProfile($UserSocialProfile);
        }

        /*
         * Populate user Additional Info
         */

        $userAdditionalInfo = (
                                isset($data['gradUniversity']) || isset($data['gradCollege']) || 
                                isset($data['extracurricular']) || isset($data['specialConsiderations']) ||
                                isset($data['preferences']) || isset($data['aboutMe']) || isset($data['bio']) || 
                                isset($data['currentClass']) || isset($data['currentSchool']) || 
                                isset($data['examTaken']) || isset($data['employmentStatus']) || isset($data['maritalStatus'])
                                    //this examtaken check is because of new value bookeddate
                            );

        if($userAdditionalInfo){

          //  if($user->getUserAdditionalInfo()){
                // $this->deleteUserAdditionalInfo($user->getUserAdditionalInfo()->getId());
           // }
            
            $populator = new \user\libraries\DataPopulators\UserAdditionalInfo;
            $UserAdditionalInfo = $user->getUserAdditionalInfo();
            if($mode == 'update' && gettype($UserAdditionalInfo) == 'object') {
                $populator->populate($UserAdditionalInfo,$data);
            }else {
                $UserAdditionalInfo = new \user\Entities\UserAdditionalInfo;
                $populator->populate($UserAdditionalInfo,$data);
                $UserAdditionalInfo->setUser($user);
                $user->setUserAdditionalInfo($UserAdditionalInfo);
            }
        }


        if($data['appliedCourseId'] && (isset($data['isRmcStudentprofile']) && $data['isRmcStudentprofile'] == 'yes')){

            // Delete previous records of the user
            if($data['userId'] > 0){
                 $this->deleteAppliedData($data['userId'], $data['appliedCourseId']);
            }
           
            $index = 0;
            foreach($data['appliedCourseId'] as $appliedCourseId){
                $appliedData = array();

                $appliedData['appliedCourseId'] = $data['appliedCourseId'][$index];
                $appliedData['appliedCourseName'] = $data['appliedCourseName'][$index];
                $appliedData['courseCategory'] = $data['courseCategory'][$index];
                $appliedData['courseSubCategory'] = $data['courseSubCategory'][$index];
                $appliedData['LDBCourseId'] = $data['LDBCourseId'][$index];
                $appliedData['universityName'] = $data['universityName'][$index];
                $appliedData['scholarshipReceived'] = $data['scholarshipReceived'][$index];
                $appliedData['scholarshipDetails'] = $data['scholarshipDetails'][$index];
                $appliedData['applicationAccepted'] = $data['applicationAccepted'][$index];
                $appliedData['AdmissionTaken'] = $data['AdmissionTaken'][$index];
                $appliedData['timeOfAdmission'] = $data['timeOfAdmission'][$index];
                $appliedData['reasonsForRejection'] = $data['reasonsForRejection'][$index];

                $UserCourseApplied = new \user\Entities\UserCourseApplied;
                $populator = new \user\libraries\DataPopulators\UserCourseApplied;
                $populator->populate($UserCourseApplied,$appliedData);
                $UserCourseApplied->setUser($user);
                $user->setCourseApplied($UserCourseApplied);

                $index ++;
            }
        }
    }

    private function _filterDummyCourses($baseCourses){
        $this->load->library('registration/RegistrationLib');
        $registrationLib = new RegistrationLib();
        
        return $registrationLib->filterDummyBaseCourses($baseCourses);
    }

    private function _getEducationLevelByBaseCourses($baseCourses){
        if(empty($baseCourses)){
            return array();
        }

        $this->load->builder('listingBase/ListingBaseBuilder');
        $listingBase = new \ListingBaseBuilder();

        $BaseCourseRepository = $listingBase->getBaseCourseRepository();

        $baseCoursesObject = $BaseCourseRepository->findMultiple($baseCourses);

        $this->load->library('listingBase/BaseAttributeLibrary');
        $BaseAttributeLibrary = new \BaseAttributeLibrary(); 
        $attributeMapping = $BaseAttributeLibrary->getValuesForAttributeByName(array('Course Level'));

        $returnData = array();
        $returnData['educationLevel'] = 'none';
        foreach ($baseCoursesObject as $key => $value) {
            $returnData['courseLevel'][$key] = $attributeMapping['Course Level'][$value->getLevel()];
            if($returnData['courseLevel'][$key] != 'none'){
                $returnData['educationLevel'] = $returnData['courseLevel'][$key];
                $returnData['courseLevelId']  = $value->getLevel();
            }
        }
        
        return $returnData;
    }

    /* 1) Create User interest according to the selected substreams and base courses.
     * 2) Max number of possible interests can be N+1 where N is the substream.
     * 3) Then distribute the base courses and specializations among made interests.
     */

    /*
     * Populates user data into the user preference populators
     * @Params: $user: User entity Object
     *          $data: Values received from the frontend form
     */
    private function _populateUserpref(\user\Entities\User $user, $data = array(), $mode='create'){

        if($mode == 'update' && !empty($data['userId'])){
            $this->_deleteOldUserPrefs($data['userId'], 'history');
        }

        $this->load->library('listingBase/BaseAttributeLibrary');
        $baseAttributeLibrary = new BaseAttributeLibrary();

        $educationType = $baseAttributeLibrary->getAttributeIdByValueId($data['educationType']);

        /* Substream Specialization */
        $data['subStreamSpecMapping'] = json_decode($data['subStreamSpecMapping'], true);

        $baseCourses = $this->_filterDummyCourses($data['baseCourses']);

        /* Creating user interest according to the input base courses and specialization */
        $userInterestData = $this->_getAllPossibleInterest($data['stream'], $data['subStream'], $baseCourses['baseCourses'], $data['subStreamSpecMapping']);

        foreach($userInterestData[$data['stream']] as $subStream=>$courses){

            /* API to populate data into UserInterest.php */

            if($subStream == 'ungrouped'){
                $selectedSpecUnderThisSubstream = $data['subStreamSpecMapping']['ungrouped'];
                $mappedbaseCourses = $userInterestData['mappedbaseCourses'];
            }else{
                $selectedSpecUnderThisSubstream = $data['subStreamSpecMapping'][$subStream];
                $mappedbaseCourses = array();
            }
            if(empty($data['level'])){
                $data['level'] = $data['courseLevelData']['courseLevelId'];
            }
            
            /* Setting values for tUserCourseSpecialization table */
            $courseSpecCombinations = $this->getCourseSpecCombinations($data['stream'], $subStream, $selectedSpecUnderThisSubstream, $courses, $data['subStream'], $mappedbaseCourses, $data['selectedSubStreams'], $data['level'], $data['credential'], $baseCourses['dummyCourses']);
            
			if($courseSpecCombinations['isDummyCourse']){
                $courseLevel = $baseAttributeLibrary->getValueNameByValueId(array($data['level']));
                foreach ($courseSpecCombinations['dummyCourseId'] as $dummyCourseId) {
                    $data['courseLevelData']['courseLevel'][$dummyCourseId] = $courseLevel[$data['level']];
                }
                unset($courseSpecCombinations['isDummyCourse']);
                unset($courseSpecCombinations['dummyCourseId']);
            }
            
			$courseSpecCombinations = $this->_addDumyCoursesInCombination($courseSpecCombinations, $baseCourses['dummyCourses'], $selectedSpecUnderThisSubstream, $subStream, count($userInterestData[$data['stream']]));

            /* Cases to avoid dummy courses entry for dummy interest */
            if($subStream == 'ungrouped' && empty($courseSpecCombinations)){
                continue;
            }

            //if($subStream == 'ungrouped' && empty($data['subStreamSpecMapping']['ungrouped'])){
              //  continue;
            //}

            $userInterest = $this->_populateUserInterest($user, $data['stream'], $subStream);
            foreach($courseSpecCombinations as $key=>$values){
                $userCourseSpecialization = new \user\Entities\UserCourseSpecialization;
                $populator = new \user\libraries\DataPopulators\UserCourseSpecialization;
                // if(empty($values['baseCourse'])){
                //     mail('naveen.bhola@shiksha.com,ajay.sharma@shiksha.com,mansi.gupta@shiksha.com,karundeep.gill@shiksha.com,mohd.alimkhan@shiksha.com','Base Course missing while creating user interest at '.date('Y-m-d H:i:s'), 'From page: '.$_SERVER['HTTP_REFERER'].'<br/>'.print_r($data, true));
                // }
                // if(empty($data['courseLevelData']['courseLevel'])){
                //     mail('naveen.bhola@shiksha.com,ajay.sharma@shiksha.com,mansi.gupta@shiksha.com,karundeep.gill@shiksha.com,mohd.alimkhan@shiksha.com','Course Level missing while creating user interest at '.date('Y-m-d H:i:s'), 'From page: '.$_SERVER['HTTP_REFERER'].'<br/>'.print_r($data, true));
                // }
                $populator->populate($userCourseSpecialization, $values, $data['courseLevelData']['courseLevel']);
                $userCourseSpecialization->setUserInterest($userInterest);
                $userInterest->addUserCourseSpecialization($userCourseSpecialization);
            }

            /* Setting values fot tUserAttributes */
            foreach($educationType as $attributeValue=>$attributeKey){
                $userAttributes = new \user\Entities\UserAttributes;
                $populator = new \user\libraries\DataPopulators\UserAttributes;
                // if(empty($attributeKey) || empty($attributeValue)){
                //     mail('naveen.bhola@shiksha.com,ajay.sharma@shiksha.com,mansi.gupta@shiksha.com,karundeep.gill@shiksha.com,mohd.alimkhan@shiksha.com','Mode missing while creating user interest at '.date('Y-m-d H:i:s'), 'From page: '.$_SERVER['HTTP_REFERER'].'<br/>'.print_r($data, true));
                // }
                $populator->populate($userAttributes, $attributeKey, $attributeValue);
                $userAttributes->setUserInterest($userInterest);
                $userInterest->addUserAttributes($userAttributes);
            }
        }
    }

    /* API to populate data into UserInterest.php */
    private function _populateUserInterest(\user\Entities\User $user, $stream, $subStream){
        
        $populatorData =array();
        $populatorData['stream'] = $stream;
        if($subStream == 'unMappedCourses'){
            $populatorData['subStream'] = NULL;
        }else{
            $populatorData['subStream'] = $subStream;
        }

        $userInterest = new \user\Entities\UserInterest;
        $populator = new \user\libraries\DataPopulators\UserInterest;

        $populator->populate($userInterest, $populatorData);
        $userInterest->setUser($user);
        $user->addUserInterest($userInterest);

        return $userInterest;
    }

    private function _addDumyCoursesInCombination($courseSpecCombinations, $dummyCourses, $selectedSpecs, $subStream, $interestCount){

        if(empty($dummyCourses)){
            return $courseSpecCombinations;
        }

        /* 1. check if mapping is empty but specialization is there then consider the provided specializations
         * 2. check if courseSpecCombinations and selectedSpecs is empty, then just return dummy courses mapped to null specizalization
         * 3. else take specializations from the mapping
         */

        if(empty($courseSpecCombinations) && !empty($selectedSpecs)){
            $specializations = array();
            foreach($selectedSpecs as $key=>$specId){
                $specializations[$specId] = 1;
            }
        }else if(empty($courseSpecCombinations) && empty($selectedSpecs)){
            if($interestCount > 0 && $subStream == 'ungrouped'){
                return array();
            }

            $returnData = array();
            foreach($dummyCourses as $key=>$course){
                $returnData[] = array('baseCourse'=>$course);
            }
            return $returnData;
       }else if(!empty($subStream) && empty($selectedSpecs) && $subStream != 'ungrouped'){
           // if($interestCount > 0){
           //      return array();
           //  }           
           $specializations = array();
           $specializations[] = 1;

        }else{
            $specializations = array();
            foreach($courseSpecCombinations as $key=>$specId){
                if(empty($specId['specialization'])){
                    continue;
                }
                $specializations[$specId['specialization']] = 1;
            }
        }   
        
        /* Making data in the desired format */
        $index = count($courseSpecCombinations);
        foreach($specializations as $specId=>$const){
            foreach ($dummyCourses as $key => $courseId) {
                $courseSpecCombinations[$index]['baseCourse'] = $courseId;
                if(empty($specId)){
                   // $courseSpecCombinations[$index]['specialization'] = NULL;
                }else{
                    $courseSpecCombinations[$index]['specialization'] = $specId;
                }
                $index++;
            }
        }

        return $courseSpecCombinations;
    }

    /* 
    * Function to create valid combinations of basecourses and specializations
    * @Params: $substream: As getCourseSpecCombinations is getting call in a loop of all possible substreams, so this substream is the index substream of the loop
    *           $selectedSubStreams: Actually selected substream by the user
    */
    public function getCourseSpecCombinations($streamId, $substream, $specializations = array(), $baseCourses = array(), $masterSubStreams=array(), $mappedbaseCourses=array(), $selectedSubStreams=array(), $requiredLevel, $requiredCredential, $dummyCourses=array() ){
        $data = array();
        
        $this->load->builder('listingBase/ListingBaseBuilder');
        $listingBase = new ListingBaseBuilder();
        $baseCourseRepo = $listingBase->getBaseCourseRepository();

        /* Case where we have only base courses without any specializations */
        $index = 0;
        if(empty($specializations)){
            foreach($baseCourses as $key=>$courseValue){
                if(!empty($mappedbaseCourses[$courseValue])){
                    continue;
                }

                $data[$index]['baseCourse'] = $courseValue;
                $data[$index]['specialization'] = NULL;
                $index++;
            }

            /* Case where we have no base course or no dummy course in an interest : mapping dummy course(s) of same level or credential in that case */
            if(empty($baseCourses) && empty($dummyCourses)){
                $dummyBaseCourses = array();
                $dummyBaseCourses = $this->_getDummyBaseCourseForHierarchy($requiredLevel,$requiredCredential);

                foreach ($dummyBaseCourses as $dummyBaseCourse) {
                    
                    $data[$index]['baseCourse']     = $dummyBaseCourse;
                    $data[$index]['specialization'] = NULL;
                    $data['dummyCourseId'][]        = $dummyBaseCourse;
                    $index++;

                }
                $data['isDummyCourse']      = true;
            }

        }else{
            /* No need to pass dummy substream value for checking valid combinations */
            if($substream == 'ungrouped'){
                unset($substream);
            }

            /*Getting the mapped courses and specializations pairs */
            $specCourseMapping = $baseCourseRepo->getValidCombinationsOfCourseAndSpec($streamId, $substream, $baseCourses, $specializations, $masterSubStreams, $selectedSubStreams);
            
            /*Making data in the desired format */
            foreach($specCourseMapping as $key=>$mapping){
                /* No need to map base courses with NULL which are already mapped to a specialization */
                if(empty($mapping['specialization_id']) && !empty($mappedbaseCourses[$mapping['baseCourse']])){
                    continue;
                }
                if(!empty($baseCourses)){
                    if(!empty($mapping['baseCourse'])){
                        $data[$index]['baseCourse'] = $mapping['baseCourse'];
                        $data[$index]['specialization'] = $mapping['specialization_id'];
                        $index++;
                        continue;
                    } else {
                        if(!empty($substream) && !empty($mapping['specialization_id']) && empty($mapping['baseCourse'])){
                            $hasBaseCourse = false;
                            foreach ($specCourseMapping as $specKey => $value) {
                                if(!empty($value['baseCourse'])){
                                    $hasBaseCourse = true;
                                    break;
                                }
                            }
                            if($hasBaseCourse){
                                $data[$index]['baseCourse'] = $mapping['baseCourse'];
                                $data[$index]['specialization'] = $mapping['specialization_id'];
                                $index++;
                                continue;
                            }
                        }
                    }
                }
                if(empty($dummyCourses)){ 
                    /* Case where we have no base course or no dummy course in an interest : mapping dummy course(s) of same level or credential in that case */

                    $dummyBaseCourses = array();
                    $dummyBaseCourses = $this->_getDummyBaseCourseForHierarchy($requiredLevel,$requiredCredential);

                    foreach ($dummyBaseCourses as $dummyBaseCourse) {
                        
                        $data[$index]['baseCourse']     = $dummyBaseCourse;
                        $data['dummyCourseId'][]        = $dummyBaseCourse;
                        $data[$index]['specialization'] = $mapping['specialization_id'];
                        $index++;

                    }
                    $data['isDummyCourse']      = true;

                }
            }
        }

        return $data;
    }

    private function _getDummyBaseCourseForHierarchy($requiredLevel,$requiredCredential) {

        $this->load->builder('listingBase/ListingBaseBuilder');
        $listingBase = new ListingBaseBuilder();
        $baseCourseRepo = $listingBase->getBaseCourseRepository();
        $dummyCourses = $baseCourseRepo->getAllDummyBaseCourses();
        $returnArray = array();

        foreach ($dummyCourses as $key => $dummyCourseData) {

            if($dummyCourseData['base_course_id'] == 143){ 
                continue; //skipping mapping of 'None' dummy course in case of Certificate level/credential
            }

            if($dummyCourseData['level'] == $requiredLevel && $dummyCourseData['credential'] == $requiredCredential){

                $returnArray[] = $dummyCourseData['base_course_id'];
                
            }else if($dummyCourseData['level'] == $requiredLevel && empty($requiredCredential)){
                
                $returnArray[] = $dummyCourseData['base_course_id'];
                
            }

        }

        return $returnArray;

    }

    private function _getAllPossibleInterest($stream, $substream, $baseCourses, $subStreamSpecMapping ){
        $interestData = array();

        if(empty($substream)){
            $groupingData = array();
            $groupingData[$stream]['unMappedCourses'] = $baseCourses;
            return $groupingData;
        }else{
            $this->load->builder('listingBase/ListingBaseBuilder');
            $listingBase = new ListingBaseBuilder();
            $baseCourseRepo = $listingBase->getBaseCourseRepository();

            /*Now getting substreams mapped with submitted base courses and stream */
            $courseMappings = $baseCourseRepo->filterCoursesMappedToStreamAndCourse($stream, $baseCourses);

            /*Splitting data in the required format */
            return $this->_splitingUserInterest($stream, $substream, $courseMappings, $subStreamSpecMapping);
        }
    }

    private function _splitingUserInterest($stream, $substreams, $courseMappings = array(), $subStreamSpecMapping){
        $subStreamskeys = array();

        /*removing duplicate values */
        foreach($substreams as $key=>$value){
            $subStreamskeys[$value] = 1;
        }

        /*Making mapped substream and course data in the required format, Also removing duplicate values */
        $baseCourses = array();
        $baseCourseMappedSubStream = array();
        foreach($courseMappings as $key=>$value){
            if(!empty($subStreamskeys[$value['substream_id']])){
                $baseCourseMappedSubStream[$value['substream_id']][$value['baseCourse']] = $value['baseCourse'];
                $baseCourses[$value['baseCourse']] = $value['baseCourse'];
            }else{
                $baseCourseMappedSubStream['unMappedCourses'][$value['baseCourse']] = $value['baseCourse'];
            }

        }

        /* if flow is specialization, then there will never be a case where courses will be mapped to stream directly when no unmapped specialization is selected by the user */
        $flow = $this->input->post('flow');
        if($flow == 'specialization' && empty($subStreamSpecMapping['ungrouped'])){
            unset($baseCourseMappedSubStream['unMappedCourses']);
        }

        /*checking for the case where no course is selected for substream selected by the user */
        $groupingData = array();
        foreach($substreams as $key=>$subStream){
            if(!empty($baseCourseMappedSubStream[$subStream])){
                $groupingData[$stream][$subStream] = $baseCourseMappedSubStream[$subStream];
            }else{
                $groupingData[$stream][$subStream] = array();
            }
        }

        /*fitting unmapped courses with the stream */
        if(!empty($baseCourseMappedSubStream['unMappedCourses'])){
            foreach ($baseCourseMappedSubStream['unMappedCourses'] as $key => $value) {
                $groupingData[$stream]['ungrouped'][] = $value;
            }
        }

        $groupingData['mappedbaseCourses'] = $baseCourses;

        return $groupingData;
    }

    /**
     * Get user by email
     *
     * @param string $email
     * @return object \user\Entities\User
     */ 
    public function getUserByEmail($email)
    {
        $users = $this->repository->findByEmail($email);
        if(count($users) > 0) {
            return $users[0];
        }
        else {
            return FALSE;
        }
    }
    
    /**
     * Get user by userId
     *
     * @param string $userId
     * @return object \user\Entities\User
     */
    public function getUserById($userId, $flagForWriteHandle = false)
    {
		if($flagForWriteHandle === true)
		{
			$user = $this->writeRepository->find($userId);
		}
		else
		{
			$user = $this->repository->find($userId);
		}
        if(!$user || !$user->getId()) {
            return FALSE;
        }
        else {
            return $user;
        }
    }
    
    /**
     * Save custom MMP data
     *
     * @param array $data
     */ 
    public function saveMMPCustomData($data)
    {
        $this->initiateModel('write');
        foreach($data as $MMPCustomData) {
            $this->dbHandle->insert('cmp_customdata',$MMPCustomData);
        }
    }
	
	public function trackUserLogin($userId)
	{
		/**
		 * Excluding user ids used in QA monitoring scripts
		 */
        if($userId != 1030 && $userId != 1031) {
			if(!getTempUserData('loginTracked')) {
				$this->_trackUserActivity($userId,'Login');
				storeTempUserData('loginTracked','1');
				deleteTempUserData('logoutTracked');
			}
		}
	}
	
	public function trackUserLogout($userId)
	{
		/**
		 * Excluding user ids used in QA monitoring scripts
		 */
        if($userId != 1030 && $userId != 1031) {
            if(!getTempUserData('logoutTracked')) {
                $this->_trackUserActivity($userId,'Logout');
                storeTempUserData('logoutTracked','1');
                deleteTempUserData('loginTracked');
            }            
		}
	}
	
	private function _trackUserActivity($userId,$activity)
	{
		// add value for additonal column 
		$ci = & get_instance();
		$ci->load->library('session');
		
		$sessionId = $ci->session->userdata('session_id');		
		
		if(!empty($_SERVER['HTTP_REFERER'])) {
				$login_page_url = $_SERVER['HTTP_REFERER'];
		} else {
			    $login_page_url = $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
		}
		
		global $s_remote_addr;
		$this->initiateModel('write');
		$data = array(
			'userId' => $userId,
			'activityTime' => date('Y-m-d H:i:s'),
			'activity' => $activity,
			'ipAddress' => $s_remote_addr,
			'sessionId'=> $sessionId,
			'login_page_url'=> $login_page_url 
		);
		$this->dbHandle->insert('tuserLoginTracking',$data);
		
		/**
		 * Update last login time
		 */
		if($activity == 'Login') {
			$sql = "UPDATE tuser SET lastlogintime = '".date('Y-m-d H:i:s')."' WHERE userid = ?";
			$this->dbHandle->query($sql, array($userId));
			
			$sql = "UPDATE user_session_info SET user_id = ? WHERE session_id = ?";
			$this->dbHandle->query($sql, array($userId, sessionId()));
			
			$this->addUserToIndexingQueue($userId);
		}
	}
	
	/**
	 * Function to get user id corresponding to given email
	 *
	 * @param $email email id for which user id has to be found
	 * @param $encoded flag to indicate if the email is encoded
	 * @return $row['userid'] user id corresponding to given email
	 */
	public function getUserIdByEmail($email, $encoded = False, $dbhandle_type= 'read')
	{
		$this->initiateModel($dbhandle_type);
		if($encoded) {
			$sql = "SELECT userid FROM tuser WHERE email = DECODE(UNHEX(?),'ShikSha')";
		}
		else {
			$sql = "SELECT userid FROM tuser WHERE email = ?";
		}
		$query = $this->dbHandle->query($sql,$email);
		$row = $query->row_array();
		return $row['userid'];
	}
	
	/**
	 * Function to get user group for given user ids
	 *
	 * @param array $userIds list of user ids
	 * @return array $result mapping of user id and user group
	 */
	public function getUserGroupForListOfUserIds($userIds)
	{
		$this->initiateModel();
        if(empty($userIds)){
            return false;
        }

		$sql = "SELECT userid,usergroup FROM tuser WHERE userid in (?)";
		$query = $this->dbHandle->query($sql,array($userIds));
		$result  = array();
		
		foreach($query->result_array() as $row)
		{
			$result[$row['userid']] = $row['usergroup'];
		}
		return $result;
	}

	/**
	 * Function to get basic information of the user
	 *
	 * @param array $userIdsArray list of user ids
	 * @return array $userInfoArray basic info for the given user ids
	 */
	public function getUsersBasicInfo($userIdsArray)
	{
		$this->initiateModel();
		$userInfoArray = array();
        if(in_array('HTMLCollection', $userIdsArray) || empty($userIdsArray) || !is_array($userIdsArray)) {
            $listingDbErrorData = array('referer'       => $_SERVER['HTTP_REFERER'],
                                        'userIdArray'   => $userIdsArray,
                                        'cookieData'   => $_COOKIE,
                                        'postData'      => $_POST);
            error_log("Listings db error logging data-----".print_r($listingDbErrorData, true));

            return false;
        }

        $tempUserIdArray = array();
        
        foreach ($userIdsArray as $userid) {
            if(intval($userid) >1){
                $tempUserIdArray[] = $userid;
            } else{
                $listingDbErrorData = array('referer'       => $_SERVER['HTTP_REFERER'],
                                        'userIdArray'   => $userIdsArray,
                                        'cookieData'   => $_COOKIE,
                                        'postData'      => $_POST);

                $listingDbErrorData = json_encode($listingDbErrorData);

                $fp = fopen('/tmp/listingDbError','a');
                fwrite($fp,$listingDbErrorData);
                fclose($fp);
            }
        }


        $userIdsArray = $tempUserIdArray; 
        if(count($userIdsArray) == 0 || empty($userIdsArray) ){
            return false;
        }

        $sql = "SELECT `userid`, `displayname`, `email`, `isdCode`, `mobile`,`firstname`,`lastname`,`ePassword` as password FROM `tuser` WHERE `userid` in (?)";
		$query = $this->dbHandle->query($sql,array($userIdsArray));
		foreach ($query->result() as $rowTemp) {
			$userInfoArray[$rowTemp->userid]['userid'] = $rowTemp->userid;
			$userInfoArray[$rowTemp->userid]['displayname'] = $rowTemp->displayname;
			$userInfoArray[$rowTemp->userid]['email'] = $rowTemp->email;
            $userInfoArray[$rowTemp->userid]['isdCode'] = $rowTemp->isdCode;
			$userInfoArray[$rowTemp->userid]['mobile'] = $rowTemp->mobile;
			$userInfoArray[$rowTemp->userid]['firstname'] = $rowTemp->firstname;
			$userInfoArray[$rowTemp->userid]['lastname'] = $rowTemp->lastname;
			$userInfoArray[$rowTemp->userid]['password'] = $rowTemp->password;
		}
		return $userInfoArray;
	}
	

    public function getUserInfoForMMMCSV($userIdsArray){
        $this->initiateModel();
        $userInfoArray = array();

        foreach ($userIdsArray as $userid) {
            if(intval($userid) >1){
                $tempUserIdArray[] = $userid;
            } 
        }

        $userIdsArray = $tempUserIdArray; 
        unset($tempUserIdArray);

        if(count($userIdsArray) == 0 || empty($userIdsArray) ){
            return false;
        }

        $sql = "SELECT  `userid`,`email`, `firstname`,`lastname`,`mobile` FROM `tuser` WHERE `userid` in (?)";
        $query = $this->dbHandle->query($sql,array($userIdsArray));
        
        foreach ($query->result() as $rowTemp) {
            $userInfoArray[$rowTemp->userid]['email'] = $rowTemp->email;
            $userInfoArray[$rowTemp->userid]['firstname'] = $rowTemp->firstname;
            $userInfoArray[$rowTemp->userid]['lastname'] = $rowTemp->lastname;
            $userInfoArray[$rowTemp->userid]['mobile'] = $rowTemp->mobile;
        }
        
        return $userInfoArray;
    }

	/**
	 * Function to get encoded email
	 *
	 * @param $email email id to encode
	 * @return $row['encodedEmail'] encoded email id
	 */
	public function getEncodedEmail($email)
	{
		$this->initiateModel();
		$sql = 'SELECT hex(encode(?,"ShikSha")) as encodedEmail';
		$query = $this->dbHandle->query($sql,array($email));
		$row = $query->row_array();
		return $row['encodedEmail'];
	}
	
	/**
	 * Function to get decoded email
	 *
	 * @param $email encoded email id
	 * @return $row['decodedEmail'] decoded email id
	 */
	public function getDecodedEmail($email)
	{
		$this->initiateModel();
		$sql = 'SELECT DECODE(UNHEX(?),"ShikSha") as decodedEmail';
		$query = $this->dbHandle->query($sql,array($email));
		$row = $query->row_array();
		return $row['decodedEmail'];
	}
	
	/**
	 * Function to add user education
	 *
	 * @param $userId userid of user whose education is to be added
	 * @param $name name of user whose education is to be added
	 * @param $score score of user whose education is to be added
	 * @param $scoreType score type of user whose education is to be added
	 */
	public function addUserEducation($userId,$name,$score,$scoreType)
	{
		$this->initiateModel('write');
		
		$sql = "SELECT id FROM tUserEducation WHERE UserId = ? AND Name = ?";
		$query = $this->dbHandle->query($sql,array($userId,$name));
		$row = $query->row_array();
		
		if($row['id']) {
			$data = array(
				'Marks' => $score,
				'SubmitDate' => date('Y-m-d H:i:s')
			);
			if($scoreType) {
				$data['MarksType'] = $scoreType;
			}
			$this->dbHandle->where('id',$row['id']);
			$this->dbHandle->update('tUserEducation',$data);
		}
		else {
			$data = array(
				'UserId' => $userId,
				'Name' => $name,
				'Level' => 'Competitive exam',
				'Marks' => $score,
				'Country' => 2,
				'SubmitDate' => date('Y-m-d H:i:s'),
				'Status' => 'live'
			);
			if($scoreType) {
				$data['MarksType'] = $scoreType;
			}
			$this->dbHandle->insert('tUserEducation',$data);
		}
	}

	/**
	 * Function to get Mobile Registered users
	 *
	 * @param array $userids
	 * @return array mobile registered users
	 */
	public function getMobileRegisteredUsers($userids){
		$this->initiateModel('read');
        $userIdsArray = explode(',', $userids);
        
		$sql = "SELECT userid FROM tusersourceInfo WHERE userid in (?) and referer ='mobile' or referer like '%MobileUser%' and type='registration'";
		$query = $this->dbHandle->query($sql,array($userIdsArray));
		$result = $query->result_array();
		$finalArr = array();
		if(is_array($result) && count($result) > 0) {
			foreach($result as $row) {
				$finalArr[] = $row['userid'];
			}
			return $finalArr;
		}else{
			return 	$finalArr;
		}
	}
	
	/**
	 * Function to check Unique Random Code
	 *
	 * @param $uniqueCode
	 * @return $uniqueCode
	 */
	function checkAvailability($uniqueCode){
		$this->initiateModel('read');
		$sql = "SELECT 1 FROM recomendationMailToUserMappingForSMS where uniqueCode=?";
		$query = $this->dbHandle->query($sql, array($uniqueCode));
		$result = $query->row_array();
		if(empty($result)){
			return $uniqueCode;
		}else{
			return FALSE;
		}
	}
	
        /**
	 * Function to generate Unique Random Code
	 *
	 * @return $uniqueNum unique random code
	 */
	private function _generateUniqueNumber(){
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';
                for ($i = 0; $i < 5; $i++) {
			$randomString .= $characters[mt_rand(0, strlen($characters) - 1)];
		}
		while(!$uniqueNum){
		    $uniqueNum = $this->checkAvailability($randomString);        
		}
		return $uniqueNum;
	}

	/**
	 * Function to Store Sms Recomendation Inforamtion
	 *
	 * @param array $data user data
	 * @param $uniqueNum unique number
	 */
	public function storeSmsRecomendationInfo($data,$uniqueNum){
		$this->initiateModel('write');
		$subQuery = '';
		foreach($data as $key=>$value){
			$uniqueNum = $this->_generateUniqueNumber();
			$subQuery .= "('',".$this->dbHandle->escape($uniqueNum).",".$this->dbHandle->escape($value['userid']).",".$this->dbHandle->escape($value['mailid']).",".$this->dbHandle->escape($value['toemail']).",".$this->dbHandle->escape($value['createdtime']).") ,";
		}
		$subQuery  = rtrim($subQuery,',');
		$this->initiateModel('write');
		$sql = "insert into recomendationMailToUserMappingForSMS (`id` , `uniqueCode`,`userid` , `mailid` ,`email`, `creationtime`) values $subQuery";
		$this->dbHandle->query($sql);
	}

	/**
	 * Function to get Recomendation Mail Data for SMS short url
	 *
	 * @return array $finalArr user data
	 */
	public function getDataForShortUrl(){
		$this->initiateModel('read');
		//$sql = "SELECT tu.mobile, rms.* FROM recomendationMailToUserMappingForSMS rms left join smsQueue sq on (rms.userid=sq.user_id) 
		//	left join tuser tu on (tu.userId = rms.userid)
		//	where user_id is NULL";
		$sql = "SELECT tu.mobile, tu.firstname, rms.* FROM recomendationMailToUserMappingForSMS rms
			left join tuser tu on (tu.userId = rms.userid)
			where smsStatus='new'";
		$query = $this->dbHandle->query($sql);
		$result = $query->result_array();
		if(is_array($result) && count($result) > 0) {
			foreach($result as $row) {
				$finalArr[] = $row;
			}
			return $finalArr;
		}else{
			return FALSE;
		}
	}

	/**
	 * Function to get Recomendation Data when user click on short url
	 *
	 * @param $uniqueCode unique code
	 * @return array $result Data for SMS recommendation
	 */
	public function getDataForSMSRecomendation($uniqueCode){
		$this->initiateModel('read');
		$sql = "SELECT * FROM recomendationMailToUserMappingForSMS where uniqueCode=?";
		$query = $this->dbHandle->query($sql,array($uniqueCode));
		$result = array();
		$result = $query->row_array();
		if(is_array($result) && count($result) > 0) {
			return $result;
		}else{
			return $result;
		}
	}
	
	/**
	 * Function to update status to old in recomendationMailToUserMappingForSMS table for SMS
	 *
	 * @param $uniqueCode unique code
	 */
	public function updateStatusForSMS($uniqueCode){
		$this->initiateModel('write');
		$sql = "update  recomendationMailToUserMappingForSMS set smsStatus='old' where uniqueCode=?";
		$query = $this->dbHandle->query($sql,array($uniqueCode));
	}
	
	/**
	 * Function to store tracking sms recommendation data
	 *
	 * @param array $data tracking sms recommendation data
	 */
	public function storeTrackingSMSRecomendationData($data){
		$this->initiateModel('write');
		$sql = "insert into recommendationSMSTracking set mailId=?, userId=?,clickedDateTime = now()";
		$query = $this->dbHandle->query($sql,array($data['mailid'],$data['userid']));
	}
	
	/**
	 * Function to get user preference by id
	 *
	 * @param array $userIds
	 * @return array $userPref user preference
	 */
	public function getUserPrefById($userIds)
	{
		$this->initiateModel('read');
        $sql = "SELECT UserId, ExtraFlag,DesiredCourse from tUserPref where UserId in (?)";
		$query = $this->dbHandle->query($sql,array($userIds));
		$userPref = array();
		foreach ($query->result() as $rowTemp) {
		    //if($rowTemp->ExtraFlag == NULL)
			//$rowTemp->ExtraFlag = "national";
		    $userPref[$rowTemp->UserId]['ExtraFlag'] = $rowTemp->ExtraFlag;
            $userPref[$rowTemp->UserId]['DesiredCourse'] = $rowTemp->DesiredCourse;
		}
		return $userPref;
	} 

        /**
	 * Function to get LDB user details
	 *
	 * @param $user_id user id to get LDB user details
	 * @return array $response_array LDB user details
	 */
	public function getLDBuserDetails($user_id) {
	    $response_array['isLDBUser'] = 'NO';
	    $response_array['userData'] = array();
	    
	    if(empty($user_id)) {
		    return $response_array;
	    }
	    
	    $response_array['userId'] = $user_id;
	    
	    $this->initiateModel('read');
	    
	    $sql = "SELECT isLDBUser FROM tuserflag WHERE UserId = ?";
	    $query = $this->dbHandle->query($sql, $user_id);
	    $row = $query->row_array();
	    $isLDBUser = $row['isLDBUser'];
	    
	    
		$sql = "SELECT * FROM tUserPref WHERE UserId = ?";
		$query = $this->dbHandle->query($sql, $user_id);
		$row = $query->row_array();
		$pref_id = $row["PrefId"];
		$extra_flag = $row["ExtraFlag"];
		$desired_course = $row['DesiredCourse'];
		
        if(($desired_course > 0 && $extra_flag == 'studyabroad') || $isLDBUser == 'YES') {
                
    		if(!empty($desired_course) || $extra_flag == 'testprep') {
    		    if($desired_course != 0) {
        			$queryCmdx = "select tCourseSpecializationMapping.CourseName, categoryBoardTable.name as CategoryName from tCourseSpecializationMapping left join categoryBoardTable on boardId=CategoryId where SpecializationId=?";
                    $query = $this->dbHandle->query($queryCmdx,array($desired_course));
    		    }
    		    else if($extra_flag == 'testprep') {
        			$queryCmdx = "select blogTable.blogid, acronym as CourseName from blogTable,tUserPref_testprep_mapping where tUserPref_testprep_mapping.blogid = blogTable.blogid AND blogTable.status = 'live' AND tUserPref_testprep_mapping.prefid=?";
                    $query = $this->dbHandle->query($queryCmdx,array($pref_id));
    		    }
    		    
    		    $row = $query->row_array();
    		    
    		    $userData = array();
    		    if(empty($extra_flag)) {
    			    $extra_flag = 'national';
    		    }
    		    
    		    if($extra_flag == 'testprep') {
    			    $desired_course = $row['blogid'];
    		    }
    		    
    		    $desiredCourseName = '';
    		    if($extra_flag == 'testprep' || $extra_flag == 'national') {
    			    $desiredCourseName = $row['CourseName'];
    		    }
    		    else {
        			if($row['CategoryName'] == 'All') {
        			    $desiredCourseName = $row['CourseName'];
        			}
        			else {
        			    $desiredCourseName = $row['CourseName'].' in '.$row['CategoryName'];
        			}
    		    }
    		    
    		    $userData['desiredCourseType'] = $extra_flag;
    		    $userData['desiredCourse'] = $desired_course;
    		    $userData['desiredCourseName'] = $desiredCourseName;
    		    
    		    $response_array['isLDBUser'] = 'YES';
    		    $response_array['userData']= $userData;
    		}
        }
	    
	    return $response_array;
	}    

        /**
	 * Function to log LDB user preference
	 *
	 * @param array $data user data
	 * @param $user_id user id
	 */
	public function logLDBuserPref($data,$user_id) {
	    if(empty($data) || empty($user_id)) {
		return 0;
	    }
	    
	    $this->initiateModel('write');
	    $data_to_insert = array(
		    'userid'=>$user_id,
		    'pref_data'=>$data
	    );
	    
	    $this->dbHandle->insert('tuser_pref_log',$data_to_insert);
	    return $this->dbHandle->insert_id();
	}

	/**
	 * Function to update tuser_pref_log
	 *
	 * @param $insertId id in tuser_pref_log
	 * @param $text alert layer text
	 */
	public function updateLDBuserPrefLog($insertId, $text) {
	    if(empty($insertId) || empty($text) || $text == '' || $insertId == 0) {
		    return false;
	    }
	    
	    $this->initiateModel('write');
	    $sql = "UPDATE tuser_pref_log SET alert_layer_text = ? WHERE id = ?";
	    $this->dbHandle->query($sql, array(base64_decode($text),$insertId));
	}
	
	/**
	 * Function to get user's preferred country
	 *
	 * @param $emailId email id of the user
	 * @return $countryId preferred country id
	 */
	public function getUserPreferredCountryByEmail($emailId) {
	    $countryId = null;
	    
	    $this->initiateModel('read');
	    $sql = "SELECT DISTINCT tUserLocationPref.CountryId FROM tUserLocationPref, tuser WHERE tuser.email = DECODE(UNHEX(?),'ShikSha') AND tuser.userid = tUserLocationPref.UserId";
	    $query = $this->dbHandle->query($sql,array($emailId));
	    
	    if($query->num_rows() > 0) {
		    $countryId = $query->row()->CountryId;
	    }
	    
	    return $countryId;
	}
	
	/**
	 * Function to get course group
	 *
	 * @param $userId user id
	 * @return $courseGroup course group of the user
	 */
	public function getCourseGroupByUserId($userId) {
	    if(empty($userId) || $userId < 1) {
		return array();
	    }
	    
	    $this->initiateModel('read');
	    $sql = "SELECT DISTINCT groupid, groupname, acronym FROM cmp_coursegroupmapping, tUserPref WHERE tUserPref.UserId = ? AND tUserPref.DesiredCourse = cmp_coursegroupmapping.courseid";
	    $query = $this->dbHandle->query($sql, array($userId));
	    
	    $courseGroup = array();
	    if($query->num_rows() > 0) {
		$courseGroup['groupid'] = $query->row()->groupid;
		$courseGroup['groupname'] = $query->row()->groupname;
		$courseGroup['acronym'] = $query->row()->acronym;
	    }
	    
	    return $courseGroup;
	}
	
	/**
	 * Function to get desired course for study abroad
	 *
	 * @param $courseId course id
	 * @return array $desiredCourse category and course name
	 */
	public function getDesiredCourseForStudyAbroad($courseId) {
	    if(empty($courseId) || $courseId < 1) {
		return array();
	    }
	    
	    $this->initiateModel('read');
	    $sql = "SELECT CourseName, CategoryId FROM tCourseSpecializationMapping WHERE SpecializationId = ?";
	    $query = $this->dbHandle->query($sql, array($courseId));
	    
	    $desiredCourse = array();
	    if($query->num_rows() > 0) {
		$desiredCourse['fieldOfInterest'] = $query->row()->CategoryId;
		$desiredCourse['desiredGraduationLevel'] = $query->row()->CourseName;
	    }
	    
	    return $desiredCourse;
	}
	
	/**
	 * Update tuserdata de-normalized table
	 * Should be called when
	 * 1. New user is created
	 * 2. User info is updated
	 *
	 * @param $userId user id
	 */ 
	function updateUserData($userId)
	{
        error_log("User_Profile_ in in model function  : ".print_r((memory_get_peak_usage()/(1024*1024)),true));
		$this->initiateModel('write');		 
		$userId = intval($userId);

		$sql = "SELECT a.userid,a.usercreationDate,a.usergroup,
				b.hardbounce,b.softbounce,b.unsubscribe,b.ownershipchallenged,b.abused,b.isLDBUser,
				d.DesiredCourse,d.ExtraFlag,
				e.categoryID as subcategoryId,
				f.categoryID
				FROM `tuser` a
				LEFT JOIN tuserflag b ON b.userid = a.userid
				LEFT JOIN tUserPref d ON d.UserId = a.userid
				LEFT JOIN LDBCoursesToSubcategoryMapping e ON e.ldbCourseID = d.DesiredCourse
				LEFT JOIN tCourseSpecializationMapping f ON f.SpecializationID = d.DesiredCourse
				WHERE a.userid = ?";
		
		$query = $this->dbHandle->query($sql,array($userId));
		$tUserData = $query->row_array();
		
		$tUserData['countryId'] = 2;
		error_log("User_Profile_ in in model function after select query  : ".print_r((memory_get_peak_usage()/(1024*1024)),true));
		if($tUserData['ExtraFlag'] == 'testprep') {
			$sql = "SELECT a.userid, b.blogId, c.boardId, d.parentId
					FROM  tUserPref a
					INNER JOIN tUserPref_testprep_mapping b ON b.prefid = a.PrefId
					INNER JOIN blogTable c ON c.blogid = b.blogid
					INNER JOIN categoryBoardTable d ON d.boardId = c.boardId
					WHERE a.UserId = ? AND c.status = 'live' ";
			
			$query = $this->dbHandle->query($sql,array($userId));
			$tUserTestPrepData = $query->row_array();
			
			$tUserData['DesiredCourse'] = $tUserTestPrepData['blogId'];
			$tUserData['subcategoryId'] = $tUserTestPrepData['boardId'];
			$tUserData['categoryID'] = $tUserTestPrepData['parentId'];
		}
		else if($tUserData['ExtraFlag'] == 'studyabroad') {
			$tUserData['countryId'] = 0;
		}
		
		$sql = "SELECT userid FROM tuserdata WHERE userid = ?";
		$query = $this->dbHandle->query($sql,array($userId));
		$currentUserData = $query->row_array();
        error_log("User_Profile_ in in model function after select query from tuserdata  : ".print_r((memory_get_peak_usage()/(1024*1024)),true));
		
		if($currentUserData['userid']) {
			$this->dbHandle->where('userid',$userId);
			$this->dbHandle->update('tuserdata',$tUserData);
		}
		else {
			$this->dbHandle->insert('tuserdata',$tUserData);
		}
		
		/**
		 * Index user data
		 */
		$this->addUserToIndexingQueue($userId);
	}
	
	/**
	 * Function to add user to indexing queue
	 *
	 * @param $userId user id
	 */
	public function addUserToIndexingQueue($userId)
	{
		$this->initiateModel('write');
		
		$data = array(
			'userId' => $userId,
			'queueTime' => date('Y-m-d H:i:s'),
			'status' => 'queued'
		);
		
		$this->dbHandle->insert('tuserIndexingQueue',$data);
	}
	
	/**
	 * Function to get maximum id from indexing queue
	 *
	 * @return $row['maxId'] maximum id
	 */
	public function getMaxIdFromIndexingQueue()
	{
		$this->initiateModel('read');
		
		$sql = "SELECT MAX(id) as maxId FROM tuserIndexingQueue WHERE status = 'queued'";
		$query = $this->dbHandle->query($sql);
		$row = $query->row_array();
		
		return $row['maxId'];
	}
	
	/**
	 * Function to get users queued for indexing
	 *
	 * @param $maxIdInQueue maximum id in queue
	 * @return array $users users queued for indexing
	 */
	public function getUsersQueuedForIndexing($maxIdInQueue)
	{
		$this->initiateModel('read');
		
		$sql = "SELECT DISTINCT userId FROM tuserIndexingQueue WHERE status = 'queued' AND id <= ?";
		$query = $this->dbHandle->query($sql,array($maxIdInQueue));
		$results = $query->result_array();

		$users = array();
		foreach($results as $result) {
			$users[] = $result['userId'];
		}
		
		return $users;
	}

    public function getUsersQueuedForIndexingByStartDate($startDate='')
    {
        if($startDate ==''){
            return;
        }
        $this->initiateModel('read');
        $sql = "SELECT DISTINCT userId FROM tuserIndexingQueue WHERE queueTime >= ?";
        
        $query = $this->dbHandle->query($sql,array($startDate));
        $results = $query->result_array();

        $users = array();
        foreach($results as $result) {
            $users[] = $result['userId'];
        }
        
        return $users;
    }  

    /**
     * Function to get delta users queued for indexing
     *
     * @param $maxIdInQueue maximum id in queue
     * @return array $users users queued for indexing
     */
    public function getDeltaUsersQueuedForIndexing($lastIndexedId, $maxIdInQueue)
    {
        $this->initiateModel('read');
        
        $sql = "SELECT DISTINCT userId FROM tuserIndexingQueue WHERE  id > ? AND id <= ?";
        $query = $this->dbHandle->query($sql,array($lastIndexedId, $maxIdInQueue));
        $results = $query->result_array();

        $users = array();
        foreach($results as $result) {
            $users[] = $result['userId'];
        }
        
        return $users;
    }
	
	/**
	 * Function to set users indexed
	 *
	 * @param array $userIds users to set indexed
	 * @param $maxIdInQueue maximum id in queue
	 */
	public function setUsersIndexed($userIds,$maxIdInQueue)
	{
		$this->initiateModel('write');

        $sql =  "UPDATE tuserIndexingQueue ".
				"SET status = 'processed', processTime = ? ".
				"WHERE userId IN (?) AND status = 'queued' AND id <= ?";
				
		$query = $this->dbHandle->query($sql,array(date('Y-m-d H:i:s'),$userIds,$maxIdInQueue));
        
	}
	
	/**
	 * Function to get abroad short registration data
	 *
	 * @param $userId user to get data for
	 * @return $userData abroad short registration data
	 */
	public function getAbroadShortRegistrationData($userId) {
	    $this->initiateModel('read');
	    
	    $sql = "SELECT tuser.email,
			   tuser.firstname,
			   tuser.lastname,
               tuser.isdCode,
			   tuser.mobile,
			   tuser.passport,
               tuser.usercreationDate,
			   tuser.avtarimageurl,
			   tUserEducation.Name,
			   tUserEducation.Marks,
			   tUserPref.TimeOfStart,
			   tUserAdditionalInfo.bookedExamDate
		    FROM tuser
		    LEFT JOIN tUserPref
			   ON tuser.userid = tUserPref.UserId
		    LEFT JOIN tUserEducation
			   ON tuser.userid = tUserEducation.UserId AND
			      tUserEducation.Level = 'Competitive exam'
			LEFT JOIN tUserAdditionalInfo
			   ON tuser.userid = tUserAdditionalInfo.userId
		    WHERE tuser.userid = ?";
	    
	    $query = $this->dbHandle->query($sql, array($userId));
	    
	    $userData = array();	    
	    if($query->num_rows() > 0) {
		$row = $query->row_array();
		
		$userData['email'] = $row['email'];
		$userData['firstName'] = $row['firstname'];
		$userData['lastName'] = $row['lastname'];
        $userData['isdCode'] = $row['isdCode'];
		$userData['mobile'] = $row['mobile'];
        $userData['creationDate'] = $row['usercreationDate'];
		$userData['avtarimageurl'] = $row['avtarimageurl'];
		
		if(!empty($row['passport'])) {
		    $userData['passport'] = $row['passport'];
		}
		
		if(!empty($row['TimeOfStart'])) {
		    $userData['whenPlanToGo'] = $row['TimeOfStart'];
		}
		
		if(!empty($row['Name'])) {
		    foreach($query->result_array() as $row) {
				$userData['examsAbroad'][$row['Name']] = $row['Marks'];
				}
			}
	    }
		if(!empty($row['bookedExamDate']))
		{
			$userData['bookedExamDate'] = $row['bookedExamDate'];
		}
	    return $userData;
	}
	
	/**
	 * Function to get users having pre registration views
	 *
	 * @return array $userIds users having pre registration views
	 */
	public function getUsersHavingPreRegistrationViews() {
	    $this->initiateModel('read');
	    
	    $sql = "SELECT userId FROM tuserflag WHERE hasPreRegistrationViews = 'YES'";
	    
	    $query = $this->dbHandle->query($sql);
	    
	    $userIds = array();
	    if($query->num_rows() > 0) {
		foreach($query->result_array() as $row) {
		    $userIds[] = $row['userId'];
		}
	    }
	    
	    return $userIds;
	}

    public function getAbroadUsersHavingPreRegistrationViews() {
        $this->initiateModel('read');
        
        $sql = "SELECT tf.userId FROM tuserflag tf INNER JOIN tUserPref tp ON (tf.userid = tp.UserId) WHERE tf.hasPreRegistrationViews = 'YES' AND tp.ExtraFlag='studyabroad'";
        
        $query = $this->dbHandle->query($sql);
        
        $userIds = array();
        if($query->num_rows() > 0) {
        foreach($query->result_array() as $row) {
            $userIds[] = $row['userId'];
        }
        }
        
        return $userIds;
    }
	
	/**
	 * Function to get pre registration course views
	 *
	 * @param array $userIds user ids
	 * @return array $preRegistrationCourseViews pre registration course views for users
	 */
	public function getPreRegistrationCourseViews($userIds) {
	    $this->initiateModel('read');
	    
	    $preRegistrationCourseViews = array();
	    
	    if(count($userIds)) {
    		$sql = "SELECT DISTINCT tuser.userid, listing_track.course_id
    			FROM listing_track
    			INNER JOIN user_session_info ON listing_track.user_session_id = user_session_info.id
    			INNER JOIN tuser ON user_session_info.user_id  = tuser.userid
    			INNER JOIN course_details ON listing_track.course_id = course_details.course_id
    			WHERE tuser.userid IN (?)
    			AND listing_track.visit_time < tuser.usercreationDate
    			AND listing_track.is_institute = 0
    			AND course_details.status = 'live'
    			ORDER BY listing_track.visit_time DESC";
    		
    		$query = $this->dbHandle->query($sql,array($userIds));
    		
    		if($query->num_rows() > 0) {
    		    foreach($query->result_array() as $row) {
    			$preRegistrationCourseViews[$row['userid']][] = $row['course_id'];
    		    }
    		}
	    }
	    
	    return $preRegistrationCourseViews;
	}
	
	/**
	 * Function to set pre registration views flag in tuserflag
	 *
	 * @param array $userIds user ids to set the flag for
	 */
	public function updateHasPreRegistrationViews($userIds) {
	    $this->initiateModel('write');

	    if(count($userIds)) {
    		$sql = "UPDATE tuserflag SET hasPreRegistrationViews = 'NO' WHERE userId IN (?)";
    		
    		$this->dbHandle->query($sql,array($userIds));
	    }
	}
	
	/**
	 * Function to update mobile verified flag for user
	 *
	 * @param $userid user id
	 * @param $mobileVerified value of the flag mobileVerified to be updated
	 */
	public function updateMobileVerifiedFlagforUser($userid,$mobileVerified) {
		
		$this->initiateModel('write');
		
		if($userid>0) {
			$sql = "UPDATE tuserflag SET mobileverified = ? WHERE userId= ?";
			$this->dbHandle->query($sql,array($mobileVerified,$userid));
		}
	}
	
	/**
	 * Function to get user's shortlisted courses
	 *
	 * @return array $userShortlistedCourses shortlisted courses for user
	 */
	public function getUserShortlistedCourses() {
	    $this->initiateModel('read');
	    
	    $userShortlistedCourses = array();
	    
	    $shortlistedBefore = date("Y-m-d H:i:s", strtotime("-30 minutes"));
	    
	    $sql = "SELECT DISTINCT userShortlistedCourses.userId, userShortlistedCourses.courseId, userShortlistedCourses.pageType, userShortlistedCourses.tracking_keyid 
		    FROM userShortlistedCourses
		    INNER JOIN course_details ON userShortlistedCourses.courseId = course_details.course_id
		    WHERE userShortlistedCourses.isResponseConverted = 0
		    AND userShortlistedCourses.shortListTime <= ?
		    AND userShortlistedCourses.userId > 0
		    AND userShortlistedCourses.scope  = 'abroad'
		    AND userShortlistedCourses.status = 'live'
		    AND course_details.status = 'live'
		    ORDER BY userShortlistedCourses.shortListTime ASC";
	    
	    $query = $this->dbHandle->query($sql,array($shortlistedBefore));
	    
	    if($query->num_rows() > 0) {
    		foreach($query->result_array() as $row) {
    		    $userShortlistedCourses[$row['userId']][$row['courseId']] = array('pageType' => $row['pageType'], 'tracking_keyid' => $row['tracking_keyid'] ); //$row['pageType'];
    		}
	    }
	    
	    return $userShortlistedCourses;
	}

    public function getUserAllShortlistedCourses() {
        $this->initiateModel('read');
        
        $userShortlistedCourses = array();
        
        $shortlistedBefore = date("Y-m-d H:i:s", strtotime("-30 minutes"));
        
        $sql = "SELECT DISTINCT userShortlistedCourses.userId, userShortlistedCourses.courseId, userShortlistedCourses.pageType, userShortlistedCourses.scope,userShortlistedCourses.tracking_keyid, userShortlistedCourses.visitorSessionid
            FROM userShortlistedCourses
            INNER JOIN course_details ON userShortlistedCourses.courseId = course_details.course_id
            WHERE userShortlistedCourses.isResponseConverted = 0
            AND userShortlistedCourses.shortListTime <= ?
            AND userShortlistedCourses.userId > 0
            AND userShortlistedCourses.status = 'live'
            AND userShortlistedCourses.scope = 'abroad'
            AND course_details.status = 'live'
            ORDER BY userShortlistedCourses.shortListTime ASC";
        
        $query = $this->dbHandle->query($sql,array($shortlistedBefore))->result_array();
        return $query;
        
        if($query->num_rows() > 0) {
            foreach($query->result_array() as $row) {
                $userShortlistedCourses[$row['userId']][$row['courseId']][] = $row['pageType'];
                $userShortlistedCourses[$row['userId']][$row['courseId']][] = $row['scope'];
            }
        }
        
        return $userShortlistedCourses;
    }

        /**
	 * Function to get shortlisted courses for mobile users
	 *
	 * @return array $userShortlistedCourses shortlisted courses for mobile user
	 */
	public function getMobileUserShortlistedCourses() {
            $this->initiateModel('read');

            $userShortlistedCourses = array();

            $shortlistedBefore = date("Y-m-d H:i:s", strtotime("-30 minutes"));

            $sql = "SELECT DISTINCT userShortlistedCourses.userId, userShortlistedCourses.courseId
                    FROM userShortlistedCourses
                    INNER JOIN course_details ON userShortlistedCourses.courseId = course_details.course_id
                    WHERE userShortlistedCourses.isResponseConverted = 0
                    AND userShortlistedCourses.shortListTime <= ?
                    AND userShortlistedCourses.userId > 0
                    AND userShortlistedCourses.pageType IN ('mobileCategoryPage','mobileCourseDetailPage','mobileRankingPage','mobileComparePage','mobileCollegePredictorPage','MOB_CareerCompass_Shortlist')
                    AND userShortlistedCourses.status = 'live'
                    AND course_details.status = 'live'
                    ORDER BY userShortlistedCourses.shortListTime ASC";

            $query = $this->dbHandle->query($sql,array($shortlistedBefore));

            if($query->num_rows() > 0) {
                $i = 0;
                foreach($query->result_array() as $row) {
                    $userShortlistedCourses[$row['userId']][] = $row['courseId'];
                }
            }

            return $userShortlistedCourses;
        }
	
	/**
	 * Function to get shortlisted courses for national user
	 *
	 * @return array $userShortlistedCourses shortlisted courses for national user
	 */
	public function getNationalUserShortlistedCourses() {
		$this->initiateModel('read');
		$userShortlistedCourses = array();
		$shortlistedBefore = date("Y-m-d H:i:s", strtotime("-30 minutes"));
		$sql = "SELECT DISTINCT userShortlistedCourses.userId, userShortlistedCourses.courseId, userShortlistedCourses.pageType ".
				"FROM userShortlistedCourses ".
				"INNER JOIN course_details ON userShortlistedCourses.courseId = course_details.course_id AND course_details.status = 'live' ".
				"WHERE userShortlistedCourses.isResponseConverted = 0 ".
				"AND userShortlistedCourses.shortListTime <= ? ".
				"AND userShortlistedCourses.userId > 0 ".
				"AND (userShortlistedCourses.pageType LIKE 'ND_%' OR userShortlistedCourses.pageType LIKE 'NM_%') ".
				"AND userShortlistedCourses.status = 'live' ".
				"ORDER BY userShortlistedCourses.shortListTime ASC";
		
		$query = $this->dbHandle->query($sql,array($shortlistedBefore));
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$userShortlistedCourses[$row['userId']][$row['courseId']] = $row['pageType'];
			}
		}
		return $userShortlistedCourses;
	}
	
	/**
	 * Function to update shorlisted responses
	 *
	 * @param $userId user id of the response
	 * @param $courseId course on which response was made
	 */
	public function updateShortlistedResponses($userId, $courseId) {
	    $this->initiateModel('write');
	    
	    if($userId > 0 && $courseId > 0) {
		$sql = "UPDATE userShortlistedCourses SET isResponseConverted = 1 WHERE userId = ? AND courseId = ?";
		
		$this->dbHandle->query($sql, array($userId, $courseId));
	    }
	}
	
	/**
	 * Function to get user details for two step registration users
	 *
	 * @param $user_id two step registration user
	 * @return array $response_array user details
	 */
        public function getUserDetailsForTwoStepRegistration($user_id) {
            $response_array['isLDBUser'] = 'NO';
            $response_array['DesiredCourse'] = 0;

            if(empty($user_id)) {
                return $response_array;
            }

            $response_array['userId'] = $user_id;

            $this->initiateModel('read');

            $sql = "SELECT isLDBUser FROM tuserflag WHERE UserId = ?";
            $query = $this->dbHandle->query($sql, $user_id);
            $row = $query->row_array();
            $isLDBUser = $row['isLDBUser'];
	    
            $sql = "SELECT * FROM tUserPref WHERE UserId = ?";
            $query = $this->dbHandle->query($sql, $user_id);
            $row = $query->row_array();
            $DesiredCourse = $row['DesiredCourse'];
	    $extraFlag = $row['ExtraFlag'];

	    if(($DesiredCourse > 0 && $extraFlag == 'studyabroad') || $isLDBUser == 'YES') {
		$response_array['isLDBUser'] = 'YES';
	    }
	    
	    $response_array['CategoryId'] = 0;
	    if($extraFlag=='testprep'){
		$sql = "select blogid from tUserPref tup join tUserPref_testprep_mapping tuptm on (tup.PrefId = tuptm.prefid) where tup.USerId=?";
		$query = $this->dbHandle->query($sql, $user_id);
		$row = $query->row_array();
		$DesiredCourse = $row['blogid'];
		
		$sql = "select boardId from categoryBoardTable where flag=?";
		$query = $this->dbHandle->query($sql,$extraFlag);
		$row = $query->row_array();
		$response_array['CategoryId'] = $row['boardId'];
	    }
	    
            if(!empty($DesiredCourse)){
                $response_array['DesiredCourse'] = $DesiredCourse;
            }
            return $response_array;
        }
	
	/**
	 * Function to check if the user is response
	 *
	 * @param $userId user to check if it is user
	 * @return $clientCourseId course on which user is response
	 */
	function checkUserIsResponse($userId){
	    $this->initiateModel('read');
	    $sql = "select courseId from latestUserResponseData where userId = ?";
	    $query = $this->dbHandle->query($sql, $userId);
	    $row = $query->row_array();
	    $clientCourseId = $row['courseId'];
	    if(empty($clientCourseId)){
                $clientCourseId = 0;
            }
	    return $clientCourseId;
    }

	/**
	 * Function to get desired course and field of interest based on course id
	 *
	 * @param $clientCourseId course id
	 * @return array $response_array desired course and field of interest for course id
	 */
	function getDesiredCourseIdAndFieldOfInterest($clientCourseId){	
	    $this->initiateModel('read');
            $sql = "select LDBCourseID from clientCourseToLDBCourseMapping where clientCourseID=? and status='live' limit 1 ";
	    $query = $this->dbHandle->query($sql, $clientCourseId);
	    $row = $query->row_array();
	    $response_array['ldbCourseId'] = $row['LDBCourseID'];
            $sql = "select ParentId,CategoryId, SpecializationName  from tCourseSpecializationMapping where SpecializationId=?  and status='live'";
	    $query = $this->dbHandle->query($sql, $response_array['ldbCourseId']);
	    $row = $query->row_array();
	    $response_array['CategoryId'] = $row['CategoryId'];
        $response_array['ldbCourseId'] = ($row['SpecializationName']=='All' || $row['SpecializationName']=='ALL') ? $response_array['ldbCourseId'] : $row['ParentId'];
	    
	    return $response_array;
	}

	/**
	 * Function to check if user is a LDB user
	 *
	 * @param $userId user to check for
	 * @return $isLDBUser flag isLDBUser from tuserflag
	 */
	public function checkIfLDBUser($userId)
	{
		$this->initiateModel();
		$isLDBUser = 'NO';
		$sql = "SELECT isLDBUser FROM  tuserflag WHERE userId=?";
		error_log("userid====SELECT isLDBUser FROM  tuserflag WHERE userId='".$userId."'");
		$query = $this->dbHandle->query($sql, $userId);
		$row = $query->row_array();
		if(!empty($row['isLDBUser']))
		    $isLDBUser = $row['isLDBUser'];
		return $isLDBUser;
	}
	
	/**
	 * Function to get field of interest based on course id
	 *
	 * @param $ldbCourseId LDB course id
	 * @return array $response_array field of interest(category) for LDB course id
	 */
	function getFieldForInterest($ldbCourseId){
	    $this->initiateModel('read');
	    $response_array['ldbCourseId'] = $ldbCourseId;
	    $sql = "select ParentId,CategoryId, SpecializationName  from tCourseSpecializationMapping where SpecializationId=?  and status='live'";
	    $query = $this->dbHandle->query($sql, $response_array['ldbCourseId']);
	    $row = $query->row_array();	
	    $response_array['CategoryId'] = $row['CategoryId'];
	    $response_array['ldbCourseId'] = ($row['SpecializationName']=='All' || $row['SpecializationName']=='ALL') ? $response_array['ldbCourseId'] : $row['ParentId'];
	    return $response_array;
	}
	
	/**
	 * Function to delete user exams from tUserEducation
	 *
	 * @param $userId user id
	 * @param $exams exam names corresponding to the user which has to be deleted
	 */
	public function deleteUserExams($userId, $exams) {
	    $this->initiateModel('write');
	    $sql = "DELETE FROM tUserEducation
		    WHERE UserId = ?
		    AND Level = 'Competitive exam'
		    AND Name IN (?)";
	    $query = $this->dbHandle->query($sql, array($userId,$exams));
	}

    /**
     * Function to update currentJob status of tUserWorkExp to NO
     *
     * @param $userId user id
     */
    public function updateCurrentJobStatusToNO($userId){
        if(empty($userId) || $userId < 1){
            return false;
        }

        $this->initiateModel('write');
        $sql = "UPDATE tUserWorkExp
                SET currentJob = 'NO'
                WHERE UserId = ?";
        $query = $this->dbHandle->query($sql, array($userId));
    }

    /**
     * Function to delete user work exp from tUserWorkExp
     *
     * @param $ids => array having id's of tUserWorkExp
     */

    private function deleteUserWorkExp($ids, $userId){
        if(count($ids) < 1 || $userId < 1 || empty($userId)){
            return false;
        }

        $this->initiateModel('write');
        $sql = "DELETE FROM tUserWorkExp
            WHERE userId = ?
            AND id IN (?)";
        $query = $this->dbHandle->query($sql, array($userId,$ids));
    }

    /**
     * Function to delete user additional info from tUserAdditionalInfo
     *
     * @param $id table id
     */
    public function deleteUserAdditionalInfo($id) {
        if($id > 0){

            $this->initiateModel('write');
            $sql = "DELETE FROM tUserAdditionalInfo
                WHERE id = ?";
            $query = $this->dbHandle->query($sql, array($id));
        }
    }

    /**
     * Function to delete user social profile info from userSocialProfile
     *
     * @param $id table id
     */
    public function deleteUserSocailProfile($id) {
        if($id > 0){

            $this->initiateModel('write');
            $sql = "DELETE FROM userSocialProfile
                WHERE id = ?";
            $query = $this->dbHandle->query($sql, array($id));
        }
    }



    /**
     * Function to delete user SA shiksha Apply fields from tUserEducation
     *
     * @param $userId user id
     * @param $levels level names corresponding to the user which has to be deleted
     */
    public function deleteUserEducation($userId, $levels) {
        if(empty($userId) || !isset($userId)){
            return ;
        }

        $this->initiateModel('write');
        $sql = "DELETE FROM tUserEducation
                WHERE UserId = ?
                AND Level IN (?)";

        $query = $this->dbHandle->query($sql, array($userId, $levels));
    }

    /**
     * Function to mark old education preferences of the user to history/draft
     *
     * @param $userId user id
     * @param $status => 'history' or 'draft'
     */
    public function _deleteOldUserPrefs($userId, $status='history'){
        if(empty($userId)){
            return false;
        }

        $this->initiateModel('write');
        $sql = 'SELECT interestId FROM tUserInterest WHERE userId=? AND status !="history"';
        $result = $this->dbHandle->query($sql, array($userId))->result_array();

        $interestIds = array();
        foreach ($result as $key => $value) {
            $interestIds[]  = $value['interestId'];
        }
        
        if(empty($interestIds) || empty($interestIds[0])){
            return true;
        }

        $sql = 'UPDATE tUserInterest SET status=? WHERE interestId IN (?)';
        $this->dbHandle->query($sql, array($status,$interestIds));

        $sql = 'UPDATE tUserCourseSpecialization SET status=? WHERE interestId IN (?)';
        $this->dbHandle->query($sql, array($status,$interestIds));

        $sql = 'UPDATE tUserAttributes SET status=? WHERE interestId IN (?)';
        $this->dbHandle->query($sql, array($status,$interestIds));

        return true;
    }

        /**
     * Function to delete user details from tUserCourseApplied
     *
     * @param $userId user id
     * @param $clientCourseId course Ids
     */
    public function deleteAppliedData($userId, $clientCourseId) {
        if(empty($userId) || !isset($userId)){
            return ;
        }

        $this->initiateModel('write');
        $sql = "DELETE FROM tUserCourseApplied
                WHERE UserId = ?
                AND courseId IN (?)";
        $query = $this->dbHandle->query($sql, array($userId,$clientCourseId));
    }
       /**
	 * Function to backup User Exams in tUserEducationTracking
	 *
	 * @param $userId user id
	 * @param $exams exam names corresponding to the user id
	 */  
	public function _backupUserExams($userId, $exams) {
		$this->initiateModel('write');
		$sql = "Insert into tUserEducationTracking (id,UserId,Name,InstituteId,Level,Marks,MarksType,CourseCompletionDate,CourseSpecialization,OngoingCompletedFlag,City,Country,SubmitDate,Status,board,subjects,instituteName,Specialization,examGroupId)"
				. "select * from tUserEducation "
				. "where UserId = ? "
				. "and Name IN (?)";
		$query = $this->dbHandle->query($sql,array($userId,$exams));
	}

    /**
     * Function to backup User Exams in tUserEducationTracking
     *
     * @param $userId user id
     * @param $levels level names corresponding to the user id
     */  
    private function _backupUserEducation($userId, $levels) {
        $this->initiateModel('write');
        $sql = "Insert into tUserEducationTracking  (id,UserId,Name,InstituteId,Level,Marks,MarksType,CourseCompletionDate,CourseSpecialization,OngoingCompletedFlag,City,Country,SubmitDate,Status,board,subjects,instituteName,Specialization,examGroupId)"
                . "select * from tUserEducation "
                . "where UserId = ? "
                . "and Level IN (?)";
        
        $query = $this->dbHandle->query($sql,array($userId,$levels));
    }
	
	/**
	 *Function to delete user's entry from tUserSpecializationPref
	 *
	 *@param $userId user whose entry is to be deleted
	 */
	public function deleteUserSpecialization($userId) {
	    $this->initiateModel('write');
	    $sql = "DELETE FROM  tUserSpecializationPref WHERE UserId = ?";
	    $query = $this->dbHandle->query($sql,array($userId));
	}
        
	/**
	 *Function to delete abroad user's destination country
	 *
	 *@param $userId user whose destination country is to be deleted
	 */
	public function deleteUserAbroaddestinationCountry($userId) {
	    $this->initiateModel('write');
	    $sql = "DELETE FROM tUserLocationPref WHERE UserId = ? and CountryId != 2";
	    $query = $this->dbHandle->query($sql,array($userId));
	}
	
	public function UserDataTracking($userId, $UserData) {
		$this->initiateModel('write');
		$sql = "Insert into  tUserUpdationTracking (UserId, UserData, SubmitDate) values (?, ?, ?)";
        $query = $this->dbHandle->query($sql,array($userId, $UserData, date('Y-m-d H:i:s')));
	}

	/**
	 * Function to get national clients
	 *
	 * @return $row national client details
	 */
	public function getNationalClient($offsetIds =null){
	    $this->initiateModel('read');
       
        $cond="";
        if(!empty($offsetIds)){
            $cond = " and a.username > ".$this->dbHandle->escape($offsetIds);
        }
        $sql=   "SELECT distinct a.username as userid FROM listings_main a JOIN enterpriseUserDetails b ON a.username = b.userId 
                JOIN institute_location_table c on a.listing_type_id = c.institute_id 
                where a.status in('live','deleted') and c.country_id = 2 and a.listing_type='institute' $cond order by a.username ASC";

        error_log('#### '.$sql);
        $query = $this->dbHandle->query($sql);
        $row = $query->result_array(); 
        return $row;
    }
	/**
	 * Function to get user pref
	 *
	 * @param $user_id user id
	 */
	public function getUserPrefInfoByUserid($user_id)
    {
        $this->initiateModel('read');
		$sql = "SELECT * from tUserPref where UserId = ?";
		$result = $this->dbHandle->query($sql, array($user_id))->row_array();
		return $result;
    }
    
   /**
	 * Function to update mobile number, when it is changed from OTP verification(for returning user)
	 *
	 *@param $email email id to update mobile for
	 *@param $newMobile new mobile number
	 */
    
    public function updateMobileFromOTPChange($email, $newMobile){
        if(!empty($email) && !empty($newMobile) && is_string($email)){
            $this->initiateModel('write');
        	$insertParams = array($newMobile, $email);
        	$sql = "UPDATE tuser set mobile = ? WHERE email = ?";
        	$query = $this->dbHandle->query($sql, $insertParams);
        }
    }

    public function updateMobileByUserId($userId, $newMobile){
        if(empty($userId) || empty($newMobile)){
            return;
        }
        $this->initiateModel('write');
        $sql = "UPDATE tuser set mobile = ? WHERE userid = ?";
        $query = $this->dbHandle->query($sql, array($newMobile, $userId));

    }

    function getUserDesiredSubCategory($userId) {
        if(!empty($userId)) {
            $this->initiateModel('read');
            $sql = "SELECT subCategoryId FROM tuserdata WHERE UserId = ?";
            $result = $this->dbHandle->query($sql, array($userId))->row_array();
            return $result;
        }
    }

    /*
    * Function to first name and last name from tuser
    * @params: $userId 
    */
    public function getNameByUserId($userId){
        $this->initiateModel();
        $sql = "select userid , firstname, lastname from tuser where userid in (?)";
        if(!is_array($userId)){
            $userId = array($userId);
        }
        return $this->dbHandle->query($sql,array($userId))->result_array();
    }


    /**
     * Function to get basic information of the user
     *
     * @param array $emailIds, Array list of email ids
     * @return array $userInfoArray basic info for the given user ids
     */

    public function getUsersBasicInfoByEmail($emailIds, $handle = "read"){
        $this->initiateModel($handle);
        $userInfoArray = array();

        $sql = "SELECT `userid`, `displayname`,`isdCode`, `email`, `mobile`,`firstname`,`lastname`,ePassword as password,`city` FROM `tuser` WHERE `email` in (?)";
        $query = $this->dbHandle->query($sql,array($emailIds));       
        foreach ($query->result() as $rowTemp) {
            $userInfoArray[$rowTemp->userid]['userid']      = $rowTemp->userid;
            $userInfoArray[$rowTemp->userid]['displayname'] = $rowTemp->displayname;
            $userInfoArray[$rowTemp->userid]['email']       = $rowTemp->email;
            $userInfoArray[$rowTemp->userid]['isdCode']     = $rowTemp->isdCode;
            $userInfoArray[$rowTemp->userid]['mobile']      = $rowTemp->mobile;
            $userInfoArray[$rowTemp->userid]['firstname']   = $rowTemp->firstname;
            $userInfoArray[$rowTemp->userid]['lastname']    = $rowTemp->lastname;
            $userInfoArray[$rowTemp->userid]['password']    = $rowTemp->password;
            $userInfoArray[$rowTemp->userid]['city']        = $rowTemp->city;
        }
        return $userInfoArray;
    }

    public function getMBARegistrationCount($fromDate, $toDate){

        $this->initiateModel('read');
        $start_date = date("Y-m-d", strtotime($fromDate));
        $end_date = date("Y-m-d", strtotime($toDate));
        $sql = "  SELECT count(*) as reg_count from ((SELECT tup.UserId
                    FROM tUserPref tup, tuser tu
                    WHERE tup.DesiredCourse 
                    IN ( 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 713, 714, 715, 716, 718, 719, 1307, 1310, 1311, 1312 ) 
                    and tu.userid=tup.UserId
                    AND DATE(tu.usercreationDate) >= ? 
                    AND DATE(tu.usercreationDate) <= ?)
                    Union
                    (SELECT DISTINCT t.userId
                    FROM tempLMSTable t
                    INNER JOIN clientCourseToLDBCourseMapping cpd ON cpd.clientCourseID = t.listing_type_id
                    INNER JOIN tuser tu ON tu.userid = t.userId
                    WHERE DATE(tu.usercreationDate) >= ? 
                    AND DATE(tu.usercreationDate) <= ? 
                    AND t.listing_type = 'course'
                    AND cpd.status = 'live'
                    AND cpd.LDBCourseID
                    IN ( 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 713, 714, 715, 716, 718, 719, 1307, 1310, 1311, 1312 ))) as x";

        $data = $this->dbHandle->query($sql, array($start_date, $end_date, $start_date, $end_date))->row_array();

        return $data['reg_count'];
    }

    public function getReponseData($fromDate, $toDate){

        $this->initiateModel('read');
        $start_date = date("Y-m-d", strtotime($fromDate));
        $end_date = date("Y-m-d", strtotime($toDate));
        $sql = "SELECT count(*) as reponse_count FROM (SELECT DISTINCT t.*
                FROM `tempLMSTable` t
                INNER JOIN clientCourseToLDBCourseMapping cpd ON cpd.clientCourseID = t.listing_type_id
                AND t.listing_subscription_type = 'paid'
                AND DATE(t.submit_date) >= ? 
                AND DATE(t.submit_date) <= ? 
                AND t.listing_type = 'course'
                AND cpd.status = 'live'
                AND cpd.LDBCourseID
                IN ( 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 713, 714, 715, 716, 718, 719, 1307, 1310, 1311, 1312 )) as x";
// _p($sql);
        $data = $this->dbHandle->query($sql, array($start_date, $end_date))->row_array();
        
        return $data['reponse_count'];
    }


    public function getLastResponseType($userId){
         $this->initiateModel('read');

         $sql ="select action from tempLMSTable where userid = ? order by id limit 1";
         $res =$this->dbHandle->query($sql,array($userId))->result_array();

         return $res[0]['action'];
    }
	
function getAbroadCourseDetails($clientCourseId){
        $this->initiateModel('read');
        $couseDetails = array();
        $sql = "select acpd.course_id, lm.listing_title, acpd.category_id, acpd.sub_category_id, acpd.ldb_course_id, un.name from listings_main lm
                left join abroadCategoryPageData acpd on (lm.listing_type_id = acpd.course_id)
                left join university un on (acpd.university_id = un.university_id)
                where acpd.course_id in (?) and acpd.status='live' and lm.status = 'live' and un.status='live'";

        $couseDetails = $this->dbHandle->query($sql,array($clientCourseId))->result_array();

        return $couseDetails;
    }

    public function insertAppUserProfileData($userId,$userProfileType,$expertName,$organisationName){
	
		$this->initiateModel('write');
		
		$sql = "insert into tuser_profileType (`id` , `userId`,`userProfileType`,`expertType`,`organisationName`, `creationtime`) values (NULL,?,?,?,?,now()) ON DUPLICATE KEY UPDATE creationtime = now()";
		
		$this->dbHandle->query($sql,array($userId,$userProfileType,$expertName,$organisationName));
	
    }
    
     public function showIntermediatePageOrNot($userId){
	
		$this->initiateModel('read');
		
		$res = array();
		$sql = "SELECT userId from `shiksha`.`tuser_profileType` where userId = ?";
		$res = $this->dbHandle->query($sql,array($userId))->result_array();
		
		return $res;
	
    }

	 /**
     * Get basic details of user by user-id
     *
     * @param user-id
     * @return array $userInfoArray basic info for the given user id
     */
    public function getUserBasicInfoById($userId)
    {
        $this->initiateModel('write');
        $userInfoArray = array();
        $sql = "SELECT tu.userid,tu.displayname,tu.email,tu.firstname,tu.lastname,tu.avtarimageurl,tuai.aboutMe,tu.usergroup FROM `tuser` tu LEFT JOIN `tUserAdditionalInfo` tuai ON (tuai.userId = tu.userid)   WHERE tu.`userid` = ?";
        $query = $this->dbHandle->query($sql,$userId);
        foreach ($query->result() as $rowTemp) {
            $userInfoArray['userid'] = $rowTemp->userid;
            $userInfoArray['email'] = $rowTemp->email;
            $userInfoArray['firstname'] = $rowTemp->firstname;
            $userInfoArray['lastname'] = $rowTemp->lastname;
            $userInfoArray['avtarimageurl'] = $rowTemp->avtarimageurl;
            $userInfoArray['displayname'] = $rowTemp->displayname;
            $userInfoArray['aboutMe'] = $rowTemp->aboutMe;
            $userInfoArray['usergroup'] = $rowTemp->usergroup;
        }
        return $userInfoArray;
    }
    public function getUserValidationInfoById($userId){
        $this->initiateModel('write');
        $this->dbHandle->select('userid,displayname,isdCode,email,mobile,firstname,lastname,ePassword as password');
        $this->dbHandle->from('tuser');
        $this->dbHandle->where('userid',$userId);
        $userData = $this->dbHandle->get()->result_array();
        return $userData;
    }
    
    public function updateUserProfileData($userId,$userProfileType,$expertName,$organisationName){
	
		$this->initiateModel('write');
		
		$sql = "UPDATE tuser_profileType SET userProfileType = ?, expertType=?, organisationName=?, creationtime = now()
			WHERE userId = ?";
		    
		$this->dbHandle->query($sql,array($userProfileType,$expertName,$organisationName,$userId));	
	
	
    }

        
    /**
     * Function to add users in the exclusion list from LDB
     *
     * @param data and userid
     * @return status
     */
    public function addUserToLDBExclusionList($userId, $exclusionType,$isdCode=''){
        if((!empty($userId)) && (!empty($exclusionType))) {
            $this->initiateModel('write');
            $submitTime = date('Y-m-d H:i:s');
            $status = "live";
            $this->markUserHistoryInLDBExclusionList($userId, $exclusionType,$isdCode);

            // insert new row
            $sql = "insert into LDBExclusionList (userId, exclusionType, submit_time, status) values (?, ?, ?, ?)";
            $this->dbHandle->query($sql,array($userId, $exclusionType, $submitTime, $status));

            $this->addUserToIndexingQueue($userId);
        }
    }

    public function markUserHistoryInLDBExclusionList($userId, $exclusionType="International User",$isdCode=''){
        // update all entry in exclusion table as history
        $this->initiateModel('write');
        $sql = "UPDATE LDBExclusionList set status ='history' where status ='live' and userId =? ";

        if($exclusionType != "International User"){
            if(!(!empty($isdCode) && $isdCode == INDIA_ISD_CODE)){
                $sql .= " and exclusionType != 'International User'";
            }
        }

        $this->dbHandle->query($sql,array($userId));
    }

    /*
     * Fnction to check is education level is filled from the front end form.
     * @params: $level => contains the education level (10, 12, UG ..)
     *          $data => POST Data
     */
    private function _userFieldConditionCheck($level='', $data){
        $flag = false;
        switch($level){
            case '10':
                $flag = ($data['tenthBoard'] || $data['xthSchool'] || $data['xthCompletionYear'] || is_array($data['CurrentSubjects']) );
            break;

            case '12':
                $flag = ($data['xiithSchool'] || $data['xiiMarks'] || $data['xiiSpecialization'] || $data['xiiYear'] );
            break;

            case 'UG':
                $flag = ($data['bachelorsDegree'] || $data['bachelorsUniv'] || $data['bachelorsMarks'] || $data['bachelorsStream'] || $data['bachelorsSpec'] || $data['graduationCompletionYear'] );
            break;

            case 'PG':
                $flag = ($data['mastersDegree'] || $data['mastersUniv'] || $data['mastersCollege'] || $data['mastersMarks'] || $data['mastersStream'] || $data['bachelorsSpec'] || $data['mastersCompletionYear'] );
            break;

            case 'PHD':
                $flag = ($data['phdDegree'] || $data['phdUniv'] || $data['phdCollege'] || $data['phdMarks'] || $data['phdStream'] || $data['phdSpec'] || $data['phdCompletionYear'] );
            break;

            case 'workExp':
                $flag = ($data['employer'] || $data['designation'] || $data['department'] || $data['startDate'] || $data['endDate'] || $data['currentJob'] );
            break;

            default:
                $flag = false;
            break;
        }

        unset($data);
        return $flag;
    }

    public function getUsersBasicInfoById($userIds)
    {
        if(empty($userIds))
            return array();


        $userIds = array_map('intval', $userIds); 

        $this->initiateModel();
        $userInfoArray = array();
        $sql = "SELECT userid,displayname,email,firstname,lastname,avtarimageurl,mobile,isdCode,city FROM `tuser` WHERE `userid` IN (?)";

        $query = $this->dbHandle->query($sql,array($userIds));
        foreach ($query->result() as $rowTemp) {
            $userid = $rowTemp->userid;
            $userInfoArray[$userid]['email']         = $rowTemp->email;
            $userInfoArray[$userid]['firstname']     = $rowTemp->firstname;
            $userInfoArray[$userid]['lastname']      = $rowTemp->lastname;
            $userInfoArray[$userid]['avtarimageurl'] = $rowTemp->avtarimageurl;
            $userInfoArray[$userid]['displayname']   = $rowTemp->displayname;
            $userInfoArray[$userid]['mobile']        = $rowTemp->mobile;
            $userInfoArray[$userid]['isdCode']       = $rowTemp->isdCode;
            $userInfoArray[$userid]['mobile']        = $rowTemp->mobile;
            $userInfoArray[$userid]['isdCode']       = $rowTemp->isdCode;
            $userInfoArray[$userid]['city']          = $rowTemp->city;
            
        }
        return $userInfoArray;
    }
    
    
     
    /**
	 * Function to send mail of new contributor to internal team 
	 *
	 * @param string $tagsString
	 */
        function sendMailForContributorToInternalTeam($userId){
            
	    $this->initiateModel('read');
	    
             $queryCmd = "select email,firstname,lastname,displayname,userid,mobile from tuser t1 where t1.userid=?";
		$queryRes = $this->dbHandle->query($queryCmd, array($userId));
		$row = $queryRes->row();
		$userName = $row->displayname;
		$fname = $row->firstname;
		$lname = $row->lastname;
		$userId = $row->userid;
		$email = $row->email;
		$mobileNo = $row->mobile;
		$fromMail = "noreply@shiksha.com";
		$subject = "A new expert has registered on App";
		$emails = array('mudit.pandey@shiksha.com', 'k.akhil@shiksha.com');
		$userProfileLink = SHIKSHA_HOME.'/getUserProfile/'.$userName;
		$content= '<p>Hi,</p>
		<p>Details of the new user who have registered with us as an expert:</p>
		<p>&nbsp;</p>
		<div>
		<table>
		<tr>
			<td>Name :</td>
			<td>'.$fname.' '.$lname.'</td>
		</tr>
		<tr>
			<td>EmailId :</td>
			<td>'.$email.'</td>
		</tr>
		<tr>
			<td>Mobile Number :</td>
			<td>'.$mobileNo.'</td>
		</tr>
		<tr>
			<td>User profile link :</td>
			<td><a href="'.$userProfileLink.'" target="_blank">'.$userProfileLink.'</td>
		</tr>
		<tr>
			<td>User Id :</td>
			<td>'.$userId.'</td>
		</tr>
		
		</table>
		</div>
		<p>&nbsp;</p>
		<p>Best wishes,</p>
		<p>Shiksha.com</p>';
		
		$this->load->library('alerts_client');
                $alertClient = new Alerts_client();
  
		for($i=0; $i<count($emails); $i++)
		{
			$alertClient->externalQueueAdd("12", ADMIN_EMAIL, $emails[$i], $subject, $content, "html", '');
		}
                
	}

    public function getUserBasicAndFlagDetails($userIds)
    {
        if(empty($userIds))
            return array();

        $this->initiateModel();
        $userDetailsArray = array();
        $sql = "SELECT tu.userid, tu.isdCode, tuf.isNDNC FROM tuser tu LEFT JOIN tuserflag tuf ON tu.userid = tuf.userid WHERE tu.userid IN (?)";

        $query = $this->dbHandle->query($sql,array($userIds));
        foreach ($query->result() as $rowTemp) {
            $userid = $rowTemp->userid;
            $userDetailsArray[$userid]['isdCode']         = $rowTemp->isdCode;
            $userDetailsArray[$userid]['isNDNC']     = $rowTemp->isNDNC;
            
        }
        return $userDetailsArray;
    }
    
    public function getUsersBasicInfoAndFlagDetails($userIds){
    	if(empty($userIds)){
    		return array();
    	}
    	
    	$usersDetailData = array();
    	$sql = " SELECT tu.userid,tu.displayname,tu.email,tu.firstname,tu.lastname,tu.avtarimageurl,tuf.abused FROM tuser tu"
    			." LEFT JOIN tuserflag tuf ON(tu.userid = tuf.userId)"
    			." WHERE tu.userid IN (?) ";
    	$this->initiateModel('read');
    	$resultSet = $this->dbHandle->query($sql,array($userIds))->result_array();
    	foreach ($resultSet as $data){
    		$usersDetailData[$data['userid']]['email']			= $data['email'];
    		$usersDetailData[$data['userid']]['firstname']		= $data['firstname'];
    		$usersDetailData[$data['userid']]['lastname']		= $data['lastname'];
    		$usersDetailData[$data['userid']]['avtarimageurl']	= $data['avtarimageurl'];
    		$usersDetailData[$data['userid']]['displayname']	= $data['displayname'];
    		$usersDetailData[$data['userid']]['abused']			= $data['abused'];
    	}
    	return $usersDetailData;
    }
    
    function insertUserFeedBack($userId,$feedbackText,$usefulness,$easeOfUser,$lookAndFeel)
    {
	$this->initiateModel('write');
	
	$queryCmd = "INSERT INTO appUserFeedBack(userId,feedbackText,usefulness,easeOfUser,lookAndFeel,creationTime,modificationTime) VALUES (?, ?, ?, ?, ?,now(),now()) ON DUPLICATE KEY UPDATE modificationTime = now()";
             
        $query = $this->dbHandle->query($queryCmd, array($userId,$feedbackText,$usefulness,$easeOfUser,$lookAndFeel));
	
    } 

    public function trackLogout($userId){
        $this->_trackUserActivity($userId,'Logout');
    }

	public function getUsersAdditionalInfo($userIds){

        if(empty($userIds))
            return array();

        $this->initiateModel();
        $userInfoArray = array();
        $sql = "SELECT userId,aboutMe,bookedExamDate FROM tUserAdditionalInfo WHERE userId IN (?)";

        $query = $this->dbHandle->query($sql,array($userIds));
        foreach ($query->result_array() as $rowTemp) {
            $userid = $rowTemp['userId'];
            $userInfoArray[$userid]         = $rowTemp;
        }
        return $userInfoArray;
    }

    public function trackPasswordChange($userId){

        if(empty($userId))
            return false;

        $this->initiateModel('write');
        $query = "SELECT id FROM passwordChangedTracking WHERE userId=?";
        $query = $this->dbHandle->query($query,array($userId));

        if($query->num_rows() > 0) {

            // update
            $dataArr = array("isLogOutNeeded" => 1, 
                             "modificationTime" => date('Y-m-d H:i:s'));
            $this->dbHandle->where('userId',$userId);
            $this->dbHandle->update("passwordChangedTracking", $dataArr);
        }
        else{

            // insert
            $dataArr = array("userId" => $userId,
                             "isLogOutNeeded" => 1,
                             "modificationTime" => date('Y-m-d H:i:s'));
            $this->dbHandle->insert("passwordChangedTracking", $dataArr);
        }
    }

    public function updateUserPref($userId, $desiredCourse, $ExtraFlag){ 
        if(empty($userId))
            return false;
        
        $this->initiateModel('write');
        $sql = "UPDATE tUserPref set DesiredCourse = ?, ExtraFlag=?, submitdate=now(), is_processed='no' WHERE userId = ?";
        $this->dbHandle->query($sql, array($desiredCourse, $ExtraFlag, $userId));
    }

    public function getUserPrefId($userId){ 
        if(empty($userId))
            return false;
        
         $this->initiateModel('read');
         $sql = "SELECT prefId from tUserPref WHERE userId = ?";
         $result = $this->dbHandle->query($sql, array($userId))->result_array();
         return $result[0]['prefId'];
    }   

    public function insertIntoTestPrepMapping($prefId, $examId){
        if(empty($prefId) || empty($examId))
            return false;

        $this->initiateModel('write');
        $sql = "INSERT INTO tUserPref_testprep_mapping (prefId, blogid) VALUES (?,?)";
        $this->dbHandle->query($sql, array($prefId, $examId));
    }

    public function getTestPrepMappingId($prefId){
        if(empty($prefId))
            return false;
        
         $this->initiateModel('read');
         $sql = "SELECT id from tUserPref_testprep_mapping WHERE prefId = ?";
         $result = $this->dbHandle->query($sql, array($prefId))->result_array();
         return $result[0]['id'];
    }

    public function updateTestPrepMapping($id, $blogId){
        if(empty($id) || empty($blogId))
            return false;

        $this->initiateModel('write');
        $sql = "UPDATE tUserPref_testprep_mapping SET blogId=?, updateTime=now() WHERE id=?";
        $this->dbHandle->query($sql, array($blogId, $id));
    }

    /*
     * Function to get category Id using LDB course Id
     * @params: $ldbCourseId : LDB course Id(Integer)
     * @output: category Id (Integer)
     */
    public function getCategoryIdFromLDBCourseId($ldbCourseId){
        if(empty($ldbCourseId) || !($ldbCourseId > 0) ){
            return;
        }

        $this->initiateModel('read');
        $sql = "SELECT CategoryId FROM tCourseSpecializationMapping WHERE SpecializationId=? AND status='live'";
        $result = $this->dbHandle->query($sql, array($ldbCourseId))->result_array();
        return $result[0]['CategoryId'];
    }

    /*
     * Function to get sub-category Id using LDB course Id
     * @params: $ldbCourseId : LDB course Id(Integer)
     * @output: Sub category Id (Integer)
     */
    public function getSubCategoryIdFromLDBCourseId($ldbCourseId){
         if(empty($ldbCourseId) || !($ldbCourseId > 0) ){
            return;
        }

        $this->initiateModel('read');
        $sql = 'SELECT categoryID as subCatId from LDBCoursesToSubcategoryMapping WHERE ldbCourseId = ? AND status="live";';
        $result = $this->dbHandle->query($sql, array($ldbCourseId))->result_array();
        return $result[0]['subCatId'];
    }

    public function getUserDetailsForRegistrationTracking($userId = 0){
        if(empty($userId)){
            return array();
        }
        
        $this->initiateModel('write');
        $sql = 'SELECT t.city, t.country, tulf.CountryId as prefCountry FROM tuser t 
                LEFT JOIN tUserLocationPref tulf ON (t.userid=tulf.UserId)
                WHERE t.userid=?';
        return $this->dbHandle->query($sql, array($userId))->result_array();
    }

    public function insertIntoRegistrationTracking($data){
        $this->initiateModel('write');
        $this->db->insert('shiksha.registrationTracking', $data); 
    }

    public function insertIntoMISTracking($data){
        $this->initiateModel('write');
        $this->db->insert('shiksha.MISTracking', $data); 
    }

    public function getTestPrepSubCat($blogId=0){
        if(empty($blogId)){
            return;
        }
        $this->initiateModel('read');
        $sql = 'SELECT a.boardId, b.parentId FROM blogTable a LEFT JOIN categoryBoardTable b ON (a.boardId=b.boardId) WHERE a.blogId=?';
        $result = $this->dbHandle->query($sql, array($blogId))->result_array();
        return $result[0];
    }

     /*
     * Function to get desired course of user using userid
     * @params: $userId : primary key of tuser(Integer)
     * @output: desired course Id (Integer)
     */
     public function getDesiredCourseByUserId($userId){
        if(empty($userId)){
            return;
        }
        $this->initiateModel('write');  /* 'write' mode to handle server lag cases */
        $sql="SELECT desiredCourse FROM tUserPref WHERE userId = ?";
        $result = $this->dbHandle->query($sql, array($userId))->result_array();
        return $result[0]['desiredCourse'];
     }

     /*
     * Function to get blog Id of user using userid(for test prep course)
     * @params: $prefid : primary key of tUserPref(Integer)
     * @output: blog Id (Integer)
     */
     public function getBlogIdByPrefId($prefId){
        if(empty($prefId)){
            return;
        }
        $this->initiateModel('write');
        $sql="SELECT blogid FROM tUserPref_testprep_mapping WHERE prefid = ? AND status='live'";
        $result = $this->dbHandle->query($sql, array($prefId))->result_array();
        return $result[0]['blogid'];
     }

    /*
     * Function to get source type from registrationTracking(for test prep course)
     * @params: $userId : primary key of tuser(Integer)
     */
    public function getLastSourceFromRegistrationTracking($userId){
         if(empty($userId)){
            return;
        }
        $this->initiateModel('write'); /* 'write' mode to handle server lag cases */
        $sql = 'SELECT source FROM registrationTracking WHERE userId=? ORDER BY id DESC LIMIT 1';
        $result = $this->dbHandle->query($sql, array($userId))->result_array();
        return $result[0]['source'];
    }
    
    function UpdateUserBounce($email,$userid) {
		
		if(empty($email)) {
				return false;
		}
				
		//update tuserflag		
		$this->initiateModel('write');
		if($userid>0) {
			$sql = "UPDATE tuserflag SET hardbounce='0' WHERE userid=?";
			$result = $this->dbHandle->query($sql, array($userid));
		}
		
		if(is_string($email)) {
		//update userbouncedata
			$sql = "DELETE from UserBounceData where email_id=?";
			$result = $this->dbHandle->query($sql, array($email));
		
			$db = $this->getWriteHandleByModule('Mailer');
			$db->query($sql, array($email));
        }
	}

    /* Function to compare 2 arrays 
     * @params: Two arrays which we want to compare
     * @return: TRUE:- if identicle, FALSE:- if not identicle
    */
    private function identical_arrays_checker( $arrayA , $arrayB ) { 
        sort( $arrayA ); 
        sort( $arrayB ); 
        return $arrayA == $arrayB; 
    } 

    /* 
     * Function to check user's preferred countries changed status
    */
    private function _isPrefCountriesChanged(\user\Entities\User $user, $destinationCountry){
        $userLocationPrefObj =$user->getLocationPreferences();
        if(empty($userLocationPrefObj)){
                return true;
        }else{
            $storedPrefCountries = array();
            foreach ($userLocationPrefObj as $key => $obj) {
                $storedPrefCountries[] = $obj->getCountryId();
            }
            if(!$this->identical_arrays_checker($storedPrefCountries, $destinationCountry)){
                return true;
            }
        }
        return false;
    }

	/**
     * Update users tags follow list whenever desired course is updated
     * @author Romil Goel <romil.goel@shiksha.com>
     * @date   2016-04-07
     * @param  [type]     $userId        [User Id]
     * @param  [type]     $desiredCourse [Desired course id]
     * @param  [type]     $entityType    [entity Type]
     */
    function updateUserTags($userId, $desiredCourse, $entityType){

        $universalmodel = $this->load->model('common/universalmodel');

        $type = 'desired_course';
        if($entityType == 'testprep'){
            $type = 'test_prep';              
        }
        if(($_COOKIE['ci_mobile'] == 'mobile') || ($GLOBALS['flag_mobile_user_agent'] == 'mobile'))
        {
            $implicitFollowTrackingPageKeyId = 772;
        }
        else{
            $implicitFollowTrackingPageKeyId = 773;
        }
        // follow the tag
        $userTags = $universalmodel->getTagsForEntity($desiredCourse, $type);
        foreach ($userTags as $tagId) {
            $universalmodel->followEntity($userId, $tagId, 'tag', 'follow','explicit',$implicitFollowTrackingPageKeyId); 
        }
    }

    /*
     * Function to get source of session(visitor session id) from session_tracking table 
     */
    public function getSessionSourceType(){
        $this->initiateModel('read');
        
        $sql="SELECT source FROM session_tracking WHERE sessionId=?";
        $result = $this->dbHandle->query($sql, getVisitorSessionId())->result_array();
        return $result[0]['source'];
    }

    /**
     * Get desired course value fr study abroad
     * It's calculated by a combination of field of interest (category) and desired graduation level (UG|PG)
     *
     * @return integer
     */ 
     private function _getDesiredCourseForStudyAbroad($categoryId, $desiredGraduationLevel)
     {
         $this->initiateModel('read');
        
        // STUDY_ABROAD_NEW_REGISTRATION;
        if(STUDY_ABROAD_NEW_REGISTRATION) {
            $sql =  "SELECT SpecializationId ".
            "FROM tCourseSpecializationMapping WHERE 1 ".
            "AND CategoryId = ? ".
            "AND CourseName = ?";

            $query = $this->dbHandle->query($sql,array($categoryId,$desiredGraduationLevel));
        }
        else {
            $sql =  "SELECT SpecializationId ".
            "FROM tCourseSpecializationMapping WHERE scope = 'abroad' ".
            "AND CategoryId = ? ".
            "AND CourseLevel1 = ? ".
            "AND CourseName = ?";

            $query = $this->dbHandle->query($sql,array($categoryId,$desiredGraduationLevel,$desiredGraduationLevel));
        }
        
        
        $result = $query->row_array();
        return $result['SpecializationId'];
    }

    /* 
     * Function to get exam details filled by user
     * @Params: $userIds => array having userids
     *          $examList => List of exam you want to check
     * @return: array having exam details
     */
    public function getUserSAExams($userIds = array(), $examList=array()){
        if(empty($userIds)){
            return array();
        }

        if(empty($examList) || !is_array($examList)){
            $examList = array("TOEFL","IELTS","PTE","GRE","GMAT","SAT","CAEL","MELAB","CAE");
        }

        $this->initiateModel('write'); //Due to lag problem
        $sql = 'SELECT UserId, Name, Marks, MarksType, SubmitDate FROM tUserEducation WHERE Level = "Competitive exam" AND Name IN (?) AND UserId IN (?)';
        return $this->dbHandle->query($sql,array($examList,$userIds))->result_array();
    }

    public function isSubStreamSpecMandatory($stream){
        if(empty($stream)){
            return;
        }

       $this->initiateModel('read');
        $sql = 'SELECT isSpecializationMand FROM registrationStreamFlow WHERE streamId=? AND status="live"';
        $result = $this->dbHandle->query($sql, array($stream))->result_array();
        return $result[0]['isSpecializationMand'];
    }

    public function getUserInterest($userId){
        if(empty($userId)){
            return array();
        }

         $this->initiateModel();
        $sql = 'SELECT interestId, streamId, subStreamId,time from tUserInterest where status="live" and userId = ?';
        return $this->dbHandle->query($sql,array($userId))->result_array();
    }

    public function getUserSpecilization($interestId){
        if(empty($interestId)){
            return array();
        }

        $this->initiateModel();
        $sql = 'SELECT specializationId, baseCourseId from tUserCourseSpecialization  where  status ="live" and interestId =?';

        return $this->dbHandle->query($sql,array($interestId))->result_array();
    }

    public function getUserAttributes($interestId){
        if(empty($interestId)){
            return array();
        }

        $this->initiateModel();
        $sql = 'SELECT attributeKey, attributeValue from tUserAttributes where  status ="live" and interestId = ?';

        return $this->dbHandle->query($sql,array($interestId))->result_array();
    }

	public function getUserFlagDetails($userId){
        if(empty($userId)){
            return array();
        }

        $this->initiateModel();
        $sql = 'SELECT isNDNC from tuserflag where userid = ?';
        return $this->dbHandle->query($sql,array($userId))->row_array();
    }

	public function getDataForResponseProfile($stream,$substream,$baseCourses,$subStreamSpecMapping){
        
        /*Creating user interest according to the input base courses and specialization */
        $userInterestData = $this->_getAllPossibleInterest($stream, $substream, $baseCourses, $subStreamSpecMapping);

        return $userInterestData;
    }

    public function getUserResponseProfile($userId){
        if(empty($userId)){
            return array();
        }

        $this->initiateModel();
        $sql = 'SELECT stream_id,substream_id,user_profile,que.listing_type, usrprf.listing_id, submit_date,que.action_type from user_response_profile usrprf,temp_response_queue que where usrprf.user_id = ? and que.id =queue_id and usrprf.status="live"';

        return $this->dbHandle->query($sql,array($userId))->result_array();
    }

    public function getUserDistinctResponseProfile($userId){
        if(empty($userId)){
            return array();
        }

        $this->initiateModel();
        $sql = 'SELECT stream_id,substream_id,count(*) as affinity from user_response_profile where user_id = ? and status="live" group by stream_id ,substream_id ';

        return $this->dbHandle->query($sql,array($userId))->result_array();
    }

    public function getUserLocationAffinity($userId){
        if(empty($userId)){
            return array();
        }

        $this->initiateModel();
        $sql = 'select cityId,affinity from userResponseLocationAffinity where userId =?';

        return $this->dbHandle->query($sql,array($userId))->result_array();
    }

    public function getUserResponseData($userId){
        if(empty($userId)){
            return array();
        }

        $this->initiateModel();

        $sql = 'SELECT  listing_type_id as courseId,submit_date AS responseDate FROM tempLMSTable WHERE userId =? and listing_type="course"';

        return $this->dbHandle->query($sql,array($userId))->result_array();
    }

    /**
    * Function for getting education level from tuserpref table
    */  
    public function getUserEducationLevel($userId){

        if(empty($userId)) {
            return;
        }

        $this->initiateModel('write');
        $sql = 'select educationLevel, ExtraFlag from tUserPref where UserId =?';

        $result = $this->dbHandle->query($sql,array($userId))->row_array();

        return $result;
    }

    /**
    * Function for updating education level in tuserpref table
    */  
    public function updateUserPrefData($userId, $updatedData){

        if(empty($userId) || empty($updatedData)) {
            return ;
        }

        $this->initiateModel('write');

        $sql = 'update tUserPref set '.implode(", ", $updatedData).' where UserId =?';
        $this->dbHandle->query($sql,array($userId));

        return true;
    }

    public function getUserCourseLevel($userId){
        if(empty($userId)){
            return;
        }
        $this->initiateModel();
        $query = $this->dbHandle->select('educationLevel')->where('UserId',$userId)->get('tUserPref')->row_array();
        return $query['educationLevel'];
    }

    public function excludeUserInMigration(){
        $this->initiateModel();

        $sql = 'select userId from tempLMSTable group by userId having count(*) >50';

        $result = $this->dbHandle->query($sql)->result_array();

        return $result;
    }

    private function _updateUserImplicitEducationLevel(\user\Entities\UserPref $userPref, $data = array()){
        if(!empty($data['stream']) && $data['mode'] == 'update' ){
            $oldLevel = $userPref->getEducationLevel();
            if($oldLevel != 'None' && $data['courseLevelData']['educationLevel'] != 'None' && $data['courseLevelData']['educationLevel'] != $oldLevel){
                /*National Implicit Interest */
                $this->load->model('response/responsemodel');
                $responseModel = new ResponseModel();
                $responseModel->updateResponseProfileStatusFromRegistration($data['userId']);
            }
        }
    }

    public function getDataFromLDBLeadContactedTracking(){
        $this->initiateModel();

        $sql = "select id, SubscriptionId from LDBLeadContactedTracking "
               ."where CreditConsumed = 150 and activity_flag in ('LDB_MR','SA_MR') and ClientId != 6500651";

        $result = $this->dbHandle->query($sql)->result_array();

        return $result;
    }

    public function updateSubscriptionLog($data){
        $this->initiateModel('write');

        $sql = "update SUMS.SubscriptionLog set NumberConsumed=125 where "
                ."ConsumedId=? and SubscriptionId=?";

        $this->dbHandle->query($sql,array($data['id'],$data['SubscriptionId']));
    }

    public function getSubscriptionProductMppingData($subId){
        $this->initiateModel();

        $sql = "select SubscriptionId, BaseProdRemainingQuantity from SUMS.Subscription_Product_Mapping "
                ."where SubscriptionId=? and BaseProductId=127 order by SubscrLastModifyTime desc limit 0,1";
        $resultSet = $this->dbHandle->query($sql,array($subId))->result_array();

        return $resultSet;
    }

    public function updateSubscriptionProductMapping($subId, $addition){
        $this->initiateModel('write');

        $sql = "update SUMS.Subscription_Product_Mapping set "
                ."BaseProdRemainingQuantity=BaseProdRemainingQuantity+ $addition where "
                ."SubscriptionId=? and BaseProductId=127 order by SubscrLastModifyTime desc limit 1";

        $this->dbHandle->query($sql,array($subId));
    }

    public function updateLDBLeadContactedTracking(){
        $this->initiateModel('write');

        $sql = "update LDBLeadContactedTracking set CreditConsumed=125 "
                ."where CreditConsumed = 150 and activity_flag in ('LDB_MR','SA_MR') and ClientId != 6500651";
    
        $this->dbHandle->query($sql);
    }

    public function getSAExamExceptionRanges(){
        $this->initiateModel('read');
        $this->dbHandle->select('examId, minScore, maxScore');
        $this->dbHandle->from('studyAbroadExamExceptionForLeads');
        $this->dbHandle->where('status','live');
        $result = $this->dbHandle->get()->result_array();
        return $result;
    }

    public function getSANonExamExceptionRules($userPreferredCountries, $courseLevel,$percentage){
        if(!is_array($userPreferredCountries) || count($userPreferredCountries) <=0 || empty($courseLevel) || empty($percentage)){
            return false;
        }
        $this->initiateModel('read');
        $this->dbHandle->select('id, country');
        $this->dbHandle->from('studyAbroadNonExamExceptionForLeads');
        $this->dbHandle->where('status','live');
        $this->dbHandle->where('courseLevel',$courseLevel);
        $this->dbHandle->where('minPercentage <=',$percentage);
        $this->dbHandle->where_in('country',$userPreferredCountries);
        $result = $this->dbHandle->get()->result_array();
        return $result;
    }

    public function checkIfUserAlreadyProcessed($userId){
        if(empty($userId) || $userId <=0){
            return false;
        }
        $this->initiateModel('read');
        $sql = "
                SELECT leadid FROM SALeadMatchingLog WHERE leadid = ?
                union 
                SELECT userId FROM SALeadAllocation WHERE userId = ?
                ";
        $resultSet = $this->dbHandle->query($sql,array($userId,$userId))->row_array();
        return $resultSet;
    }

    /**
     * Function to insert education details in tUserEducation
     *
     * @data $data data to be inserted in table
     */
    public function insertUserEducationDetails($data) {
        $this->initiateModel('write');
        $this->dbHandle->insert_batch('tUserEducation', $data);
    }

    /**
     * Function to insert education details in tUserEducation
     *
     * @data $data data to be inserted in table
     */
    public function updateUserAdditionalInfo($userId, $data) {
        $this->initiateModel('write');

        $this->dbHandle->where('userId',$userId);
        $this->dbHandle->update('tUserAdditionalInfo',$data);
    }

    public function checkAndUpdateIfStreamDigestSent($userId, $streamId) {
        $this->initiateModel('write');
        $this->dbHandle->select('id');
        $this->dbHandle->from('stream_digest_sent_mails');
        $this->dbHandle->where('streamId', $streamId);
        $this->dbHandle->where('userId', $userId);
        $result = $this->dbHandle->get()->result_array();
        if(empty($result)) {
            $data['streamId'] = $streamId;
            $data['userId']   = $userId;
            $this->dbHandle->insert('stream_digest_sent_mails', $data);
            return false;
        }
        else {
            return true;
        }
    }

	public function getUserInfoByAttribute($userId, $attributeName){
        if(empty($userId) || empty($attributeName)){
            return false;
        }
        $this->initiateModel('read');
        $this->dbHandle->select('id, attributeValue,status, addedOn');
        $this->dbHandle->from('userInfoTracking');
        $this->dbHandle->where('userId',$userId);
        $this->dbHandle->where('attributeName', $attributeName);
        $this->dbHandle->where('status', "live");
        $result = $this->dbHandle->get()->result_array();
        return $result;
    }

    public function insertRowInUserInfoTracking($row){
        if(!is_array($row) || count($row)<=0){
            return false;
        }

        $this->initiateModel('write');
        $this->dbHandle->insert('userInfoTracking',$row);
    }

    public function updateStatusInUserInfoTracking($userInfoTrackingId, $status){
        if(empty($userInfoTrackingId) || $userInfoTrackingId<=0){
            return false;
        }

        if(empty($status)){
            return false;
        }

        $data = array('status'=>$status);
        $this->initiateModel('write');
        $this->dbHandle->where('id',$userInfoTrackingId);
        $this->dbHandle->update('userInfoTracking',$data);
    }

    public function userWithUpdatedExamDetails($date, $attributeName){
        if(empty($date) || empty($attributeName)){
            return false;
        }

        $this->initiateModel('read');
        $this->dbHandle->select('userId, attributeValue, addedOn');
        $this->dbHandle->from('userInfoTracking');
        $this->dbHandle->where('status','live');
        $this->dbHandle->where('attributeName',$attributeName);
        $this->dbHandle->where('addedOn >=', $date);
        $result = $this->dbHandle->get()->result_array();
        return $result;
    }

    public function getUserExamsDetails($userIds){
        if(empty($userIds) || !is_array($userIds) || count($userIds) <=0){
            return array();
        }

        $this->initiateModel('read');
        $this->dbHandle->select('UserId, Name, Marks');
        $this->dbHandle->from('tUserEducation');
        $this->dbHandle->where('Level','Competitive exam');
        $this->dbHandle->where_in('userid',$userIds);
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();
        return $result;
    }

    public function getUserDataByDisplayname($displayname){
        if(empty($displayname)){
            return array();
        }
        $this->initiateModel('read');
        $this->dbHandle->select('userid, firstname, lastname');
        $this->dbHandle->from('tuser');
        $this->dbHandle->where('displayname',$displayname);
        $result = $this->dbHandle->get()->result_array();
        return $result[0];
    }

	public function getCountryIdFromIsdCode($isdCode){
        if($isdCode > 0){
            $this->initiateModel('read');
            $this->dbHandle->select('shiksha_countryId');
            $this->dbHandle->from('isdCodeCountryMapping');
            $this->dbHandle->where('status',"live");
            $this->dbHandle->where('isdCode',$isdCode);
            $result = $this->dbHandle->get()->result_array();
            return $result[0]['shiksha_countryId'];
        }else{
            return;
        }
    }

    public function getSchoolBoardByUserId($userIds){
        if(!empty($userIds) && is_array($userIds)){
            $this->initiateModel('read');
            $this->dbHandle->select('UserId, board');
            $this->dbHandle->from('tUserEducation');
            $this->dbHandle->where_in('UserId',$userIds);
            $this->dbHandle->where('Level',"10");
            $this->dbHandle->where('Status',"live");
            $result = $this->dbHandle->get()->result_array();
            return $result;
        }else{
            return array();
        }
    }

    public function updateTUserFlagData($userId, $tUserFlagData){
        $this->initiateModel('write');
        
        if($userId>0) {
            $this->dbHandle->where('userId',$userId);
            $this->dbHandle->update('tuserflag',$tUserFlagData);
        }
    }

    public function getEncodedEmailByUserId($userId)
    {
        if($userId >0){
            $this->initiateModel();
            $sql = 'SELECT hex(encode(email,"ShikSha")) as encodedEmail from tuser where userId= ?';
            $query = $this->dbHandle->query($sql,array($userId));
            $row = $query->row_array();
            return $row['encodedEmail'];
        }
        return;
    }

    public function updateProfileImageUrl($userId, $url){
        $this->initiateModel('write');        
        if($userId>0) {
            $imageData = array('avtarimageurl' => $url);
            $this->dbHandle->where('userId', $userId);
            $this->dbHandle->update('tuser', $imageData);
        }
    }

    public function updateUserUnsubscribeCategory($userId,$unsubscribeCategory,$isUnsubscribed){
        if($userId <= 0 || $unsubscribeCategory <= 0 || empty($isUnsubscribed)){
            return false;
        }

        $this->initiateModel('write');
        if($isUnsubscribed == 'no'){
            $data = array(
                'status'      => 'history',
                'modified_on' => date('Y-m-d H:i:s')
            );
            $this->dbHandle->where('user_id',$userId);
            $this->dbHandle->where('unsubscribe_category',$unsubscribeCategory);
            $this->dbHandle->update('user_unsubscribe_mapping',$data);
        }
    }

    public function getUserUnscrCategoryStatus($email,$unsubscribeCategory){
        if(empty($email) || $unsubscribeCategory <1){
            return false;
        }

        $this->initiateModel('read');
        $this->dbHandle->select('unscr.user_id');
        $this->dbHandle->from('tuser tu');
        $this->dbHandle->join('user_unsubscribe_mapping unscr','unscr.user_id = tu.userid','inner');
        $this->dbHandle->where('tu.email',$email);
        $this->dbHandle->where('unscr.unsubscribe_category',$unsubscribeCategory);
        $this->dbHandle->where('unscr.status','live');
        $result = $this->dbHandle->get()->result_array();
        return $result[0]['user_id'];
    }

    public function getUserEmailPreference($userIds){
        if(empty($userIds) || !is_array($userIds) || count($userIds) <=0){
            return array();
        }

        $this->initiateModel('read');
        $this->dbHandle->distinct();
        $this->dbHandle->select('user_id, unsubscribe_category ');
        $this->dbHandle->from('user_unsubscribe_mapping');
        $this->dbHandle->where('status','live');
        $this->dbHandle->where_in('user_id',$userIds);
        $result = $this->dbHandle->get()->result_array();
        return $result;
    }

    public function getUserDetailsByUserId($userIds) {

        if(empty($userIds) || !is_array($userIds) || count($userIds) <=0){
            return array();
        }

        $this->initiateModel('read');
        $query = "SELECT t.userid as user_id, t.email, t.isdCode, t.mobile, t.firstname, t.lastname, t.city as city_id, cc.city_name, cc.state_id, st.state_name from tuser t left join countryCityTable cc on t.city = cc.city_id left join stateTable st on cc.state_id = st.state_id where t.userid in (?)";
        $queryres = $this->dbHandle->query($query,array($userIds));
        foreach($queryres->result_array() as $row) {
            $result[$row['user_id']] = $row;
        }
        return $result;
    }
/*
    public function isUserHasProfile($userId) {

        if(!$userId){
            return false;
        }

        $this->initiateModel('read');
        $query = "select streamId,baseCourseId  from tUserInterest tu 
        join tUserCourseSpecialization tx on tu.interestId = tx.interestId 
        WHERE tu.userId = ? 
        and tu.status IN('live', 'draft')
        and tx.status IN ('live' , 'draft')";

        $queryres = $this->dbHandle->query($query,array($userId))->result_array();
        if(!empty($queryres)){
            return false;
        }
         return true;
    }*/

      public function isUserHasProfile($userId) {

        if(!$userId){
            return false;
        }

        $this->initiateModel('read');
        $query = "select streamId,baseCourseId  from tUserInterest tu 
        join tUserCourseSpecialization tx on tu.interestId = tx.interestId 
        WHERE tu.userId = ? 
        and tu.status IN('live', 'draft')
        and tx.status IN ('live' , 'draft')";

        $queryres = $this->dbHandle->query($query,array($userId))->result_array();

        if(!empty($queryres)){
            return false;
        }

        if(empty($queryres)){
            $query = "select * from user_response_profile where user_id = ? and status = ? limit 1"; 
            $queryres = $this->dbHandle->query($query,array($userId,"live"))->result_array();

            if(!empty($queryres)){
                return false;    
            }

        }

         return true;
    }


    /**
     * Function to get user id corresponding to given email
     *
     * @param $clientId client id for which user id has to be found
     * @param $encoded flag to indicate if the email is encoded
     * @return $row['userid'] user id corresponding to given email
     */
    public function getEmailIdByClientId($clientId)
    {   if($clientId < 1){
            return ;
         }
        $this->initiateModel('read');
        $sql = "SELECT email FROM tuser WHERE userid = ?";
        $query = $this->dbHandle->query($sql,array($clientId));
        $row = $query->row_array();
        return $row['email'];
    }

    public function getUserDetailById($userId)
    {   
        if(!$userId){
            return ;
         }
        $this->initiateModel('read');
        $sql = "SELECT * FROM tuser WHERE userid = ?";
        $query = $this->dbHandle->query($sql,array($userId));
        $row = $query->row_array();
        return $row;
    }

    public function getExamDataForUser($lastProcessedId,$batchSize){
        if($lastProcessedId !== "" && $batchSize !== ""){
        
            $this->initiateModel('read');
            $sql = "Select id,Name from tUserEducation where level = 'Competitive exam' and Status = 'live' and examGroupId IS NULL  and id > ? limit ?";
            $result = $this->dbHandle->query($sql,array($lastProcessedId,$batchSize))->result_array();
            return $result;
        }
        
    }


    public function updateGroupIdForUser($updateData){
        if(!empty($updateData)){
            $this->initiateModel('write');
            $this->dbHandle->update_batch('tUserEducation',$updateData,'id');
        }
    }

    public function getUserDataForExamAlerts($streams, $courseLevel, $criteria){
        if(empty($streams) || empty($courseLevel)){
            return false;
        }
        $this->initiateModel('read');
        $this->dbHandle->select('t.userid, i.streamId');
        $this->dbHandle->from('tuserdata t FORCE INDEX (usercreationDate)');
        //$this->dbHandle->from('tuserdata t');
        $this->dbHandle->join('tUserInterest i', 't.userid = i.userId', 'inner');
        $this->dbHandle->join('tUserCourseSpecialization c', 'i.interestId = c.interestId', 'inner');
        $this->dbHandle->where('i.status', 'live');
        $this->dbHandle->where('c.status', 'live');
        $this->dbHandle->where('t.hardbounce', '0');
        $this->dbHandle->where('t.softbounce', '0');
        $this->dbHandle->where('t.ownershipchallenged', '0');
        $this->dbHandle->where('t.abused', '0');
        $this->dbHandle->where('t.unsubscribe', '0');
        $this->dbHandle->where_in('i.streamId', $streams);
        $this->dbHandle->where_in('c.courseLevel', $courseLevel);
        if(!empty($criteria['start'])){
            $this->dbHandle->where('t.usercreationDate >=', $criteria['start']);
        }
        if(!empty($criteria['end'])){
            $this->dbHandle->where('t.usercreationDate <=', $criteria['end']);
        }
        $this->dbHandle->where_not_in('t.usergroup', array('sums','enterprise','cms','saAdmin','saCMS'));
        $this->dbHandle->group_by('c.interestId');

        $result = $this->dbHandle->get()->result_array();
        $streamWiseUsers = array();
        foreach ($result as $value) {
            $streamWiseUsers[$value['streamId']][] = $value['userid'];
        }
        unset($result);
        return $streamWiseUsers;
    }

    public function getAllHierarchies($streams) {
        if(empty($streams)){
            return false;
        }
        $this->initiateModel('read');
        $this->dbHandle->select('hierarchy_id, stream_id');
        $this->dbHandle->from('base_hierarchies');
        $this->dbHandle->where('status', 'live');
        $this->dbHandle->where_in('stream_id', $streams);
        return $this->dbHandle->get()->result_array();
    }

    public function filterUnsubscribedUserIds($userIds){
        if(empty($userIds)){
            return array();
        }
        $this->initiateModel('read');
        $this->dbHandle->select('user_id');
        $this->dbHandle->from('user_unsubscribe_mapping');
        $this->dbHandle->where('unsubscribe_category', '5');
        $this->dbHandle->where('status', 'live');
        $this->dbHandle->where_in('user_id', $userIds);
        $result = $this->dbHandle->get()->result_array();
        $unsubscribedUsers = array();
        foreach ($result as $value) {
            $unsubscribedUsers[] = $value['user_id'];
        }
        unset($result);
        return $unsubscribedUsers;
    }
}
