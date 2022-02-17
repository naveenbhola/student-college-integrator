<?php 
class userProfilePageLib{
    private $CI;
    private $usermodel;
    private $locationBuilder;
    function __construct(){
        $this->CI = &get_instance();
    }


    private function _init(){
    	$this->usermodel = $this->CI->load->model('user/usermodel');
    }

    public function getUserProfilePageURL($profileDisplayName, $urlType = 'viewPage', $validate = true, $rawFlag = false){
        $validateFlag = false;
        if($validate){
            $result = $this->validateProfilePage($profileDisplayName);
            if($result !== false){
                $validateFlag = true;
            }
        }else{
            $validateFlag = true;
        }
    	$returnArr = array();
    	$returnArr['url'] = '';
    	if($validateFlag){
    		$returnArr['url'] = SHIKSHA_STUDYABROAD_HOME.'/users/'.$profileDisplayName;
    		if($urlType == 'editPage'){
    			$returnArr['url'] .= '/edit';
    		}
        }
        if($rawFlag === false){
            return json_encode($returnArr);
        }
        else{
            return $returnArr;
        }
    }

    public function validateProfilePage($profileDisplayName){
    	$this->usermodel = $this->CI->load->model('user/usermodel');
    	$result = $this->usermodel->getUserDataByDisplayname($profileDisplayName);
    	if(empty($result)){
    		return false;
    	}
    	return $result;
    }

    //Function to fetch user profile related details
    public function viewUserProfile(&$displayData)
    {
        unset($displayData['validateuser']);
        unset($displayData['profileUserData']);
        // subs data
        if($displayData['selfProfile'] === true){
            $displayData['subscrSetting'] = $this->CI->security->xss_clean($_REQUEST['unscr']);
            $this->CI->load->config('user/unsubscribeConfig');
            $displayData['mailerCategory'] = $this->CI->config->item('mailerCategory');
            // user subs data
            $userProfileLib = $this->CI->load->library('userProfile/UserProfileLib');
            $displayData['userUnsubsData'] = $userProfileLib->getUserUnsubscribeMapping($displayData['profileUserId']);
            //_p($displayData['userUnsubsData']);
        }
        
        $user 	= $this->usermodel->getUserById($displayData['profileUserId']);
        $pref = $user->getPreference();
        $displayData['isAbroad'] =false;
        $displayData['masterCourse'] = false;
        if(is_object($pref)){
            $displayData['isAbroad'] = ($pref->getExtraFlag() == 'studyabroad')?true:false;
            if($displayData['isAbroad']===true){
                $displayData['desiredCourse'] = $pref->getDesiredCourse();
                $displayData['abroadSpecialization'] = $pref->getAbroadSpecialization();
                $this->_categorySpecializationName($displayData);
            }
            $timeOfStart = $pref->getTimeOfStart();
            $displayData['timeOfStart'] = ($timeOfStart == '0000-00-00 00:00:00'?NULL:date_format($timeOfStart,'Y'));
        }else{
            $displayData['desiredCourse'] = null;
            $displayData['abroadSpecialization'] = null;
        }
        if(!$displayData['isAbroad'])
        {
            redirect($this->redirectURLToNationalProfilePage($displayData['profileUserId']));
        }
        $displayData['userProfileUrl'] = SHIKSHA_STUDYABROAD_HOME.'/users/'.$displayData['profileDisplayName'].'/';
        //location builder load
        $this->CI->load->builder('LocationBuilder','location');
        $this->locationBuilder = new \LocationBuilder;

        $this->_userBasicInfo($displayData,$user);
        $this->_userEducationInfo($displayData,$user);
        $userLocationPreferences = $user->getLocationPreferences();
        $displayData['destinationCountry'] = array();
        foreach($userLocationPreferences as $location) {
            $countryId = $location->getCountryId();
            if($countryId > 2) {
                $displayData['destinationCountry'][$countryId] = 1;
            }
        }
        $this->_userDestinationCountriesOrdered($displayData);
        //check user is admitted or not
        $displayData['admittedUniversityCountyStr'] = $this->userAdmittedInfo($displayData['profileUserId']);
//        _p($displayData);die;
    }


    public  function _categorySpecializationName(&$displayData)
    {
        //fetch Master LDB courses
        $abroadCommonLib = $this->CI->load->library('listingPosting/AbroadCommonLib');
        $masterCourseArr = $abroadCommonLib->getAbroadMainLDBCourses();
        $masterCourseArr = array_map(function ($a){return $a['SpecializationId'];},$masterCourseArr);

        if(!empty($displayData['desiredCourse']))
        {
            //Fetch Category id and course level through LDB builder and repository
            $this->CI->load->builder('LDBCourseBuilder','LDB');
            $ldbBuilder = new LDBCourseBuilder();
            $ldbRepo = $ldbBuilder->getLDBCourseRepository();
            unset($ldbBuilder);
            $ldbCourse = $ldbRepo->find($displayData['desiredCourse']);
            unset($ldbRepo);
            $displayData['courseLevel'] = $ldbCourse->getCourseName();
            $displayData['courseLevel1'] = $ldbCourse->getCourseLevel1();
            $catId = $ldbCourse->getCategoryId();
            $displayData['catName'] = $ldbCourse->getCourseName();
            unset($ldbCourse);
            //Fetch Category Name for non LDB master courses and specialization
            $this->CI->load->builder('CategoryBuilder','categoryList');
            $catBuilder = new CategoryBuilder();
            $catRepo = $catBuilder->getCategoryRepository();
            unset($catBuilder);
            if(!in_array($displayData['desiredCourse'],$masterCourseArr))
            {
                $catObj = $catRepo->find($catId);
                $displayData['catName'] = $catObj->getName();
                unset($catObj);
                $displayData['courseLevel'] = $this->_getCurrentLevelByCourseLevel($displayData['courseLevel']);
            }
            else
            {
                $displayData['masterCourse'] = true;
            }
            if(!empty($displayData['abroadSpecialization']))
            {
                $catObj = $catRepo->find($displayData['abroadSpecialization']);
                $displayData['specializationName'] = $catObj->getName();
            }
            else
            {
                $displayData['specializationName'] = null;
            }
        }
        else
        {
            $displayData['catName'] = null;
            $displayData['specializationName'] = null;
        }
    }

    private function _userBasicInfo(&$displayData,$user)
    {
        $displayData['avtar'] = $user->getAvatarImageURL();
        // true flag has been set to show only gender neutral default images in all cases as per discussion.
        $displayData['userAvtar'] = $this->prependDomainToUserImageUrl($displayData['avtar']);
        $displayData['userName'] = $user->getFirstName().' '.$user->getLastName();
        if($displayData['selfProfile'])
        {
            $displayData['userMobile'] = $user->getMobile();
            $displayData['userEmail'] = $user->getEmail();
            $displayData['userCountry'] = $user->getCountry();
            $displayData['isdCode'] = $user->getISDCode();
            if($displayData['userCountry'] == 2)
            {
                //get residence info
                $displayData['cityFlag'] = true;
                $city = $user->getCity();
                if($city > 0) {
                    $locationRepository = $this->locationBuilder->getLocationRepository();
                    $cityObj = $locationRepository->findCity($city);
                    $displayData['cityOrCountryName'] = $cityObj->getName();
                }
            }
            elseif($displayData['userCountry'] > 2)
            {
                $displayData['cityFlag'] = false;
                $locationRepository = $this->locationBuilder->getLocationRepository();
                $countryObj = $locationRepository->findCountry($displayData['userCountry']);
                $displayData['cityOrCountryName'] = $countryObj->getName();
            }
        }
    }

    private function _userEducationInfo(&$displayData,$user)
    {
        $userEducation = $user->getEducation();
        //get user education info
        $displayData['exams'] = array();
        foreach($userEducation as $education) {
            $level = $education->getLevel();
            $marks = $education->getMarks();
            $name = $education->getName();
            if($level == 'Competitive exam') {
                $displayData['exams'][$name] = $marks;
            }
            $userAdditionalInfo = $user->getUserAdditionalInfo();
            if($education->getName() == '10'){
                if(is_object($userAdditionalInfo)){
                    $displayData['educationInfoProfile']['UG']['CurrentClass'] = $userAdditionalInfo->getCurrentClass();
                    $displayData['educationInfoProfile']['UG']['CurrentSchoolName'] = $userAdditionalInfo->getCurrentSchool();
                }
                $displayData['educationInfoProfile']['UG']['EduName'] = '10';
                $displayData['educationInfoProfile']['UG']['tenthBoard'] = $education->getBoard();
                $displayData['educationInfoProfile']['UG']['CurrentSubjects'] = $education->getSubjects();
                $displayData['educationInfoProfile']['UG']['tenthMarks'] = $education->getMarks();
                switch ($displayData['educationInfoProfile']['UG']['tenthBoard'])
                {
                    case 'CBSE':
                        {
                            global $Reverse_CBSE_Grade_Mapping;
                            $displayData['educationInfoProfile']['UG']['marksHeading'] = 'CGPA';
                            $displayData['educationInfoProfile']['UG']['tenthMarks'] = $Reverse_CBSE_Grade_Mapping[$displayData['educationInfoProfile']['UG']['tenthMarks']];
                            break;
                        }
                    case 'ICSE':
                        {
                            global $ICSE_Grade_Mapping;
                            $Reverse_ICSE_Grade_Mapping = array_flip($ICSE_Grade_Mapping);
                            $displayData['educationInfoProfile']['UG']['marksHeading'] = 'Percentage';
                            $displayData['educationInfoProfile']['UG']['tenthMarks'] = $Reverse_ICSE_Grade_Mapping[$displayData['educationInfoProfile']['UG']['tenthMarks']];
                            break;
                        }
                    case 'IGCSE':
                        {
                            global $Reverse_IGCSE_Grade_Mapping;
                            $displayData['educationInfoProfile']['UG']['marksHeading'] = 'Grades';
                            $displayData['educationInfoProfile']['UG']['tenthMarks'] = $Reverse_IGCSE_Grade_Mapping[$displayData['educationInfoProfile']['UG']['tenthMarks']];
                            break;
                        }
                    case 'IBMYP':
                        {
                            global $IBMYP_Grade_Mapping;
                            $Reverse_IBMYP_Grade_Mapping = array_flip($IBMYP_Grade_Mapping);
                            $displayData['educationInfoProfile']['UG']['marksHeading'] = 'Marks';
                            $displayData['educationInfoProfile']['UG']['tenthMarks'] = $Reverse_IBMYP_Grade_Mapping[$displayData['educationInfoProfile']['UG']['tenthMarks']];
                            break;
                        }
                    case 'NIOS':
                        {
                            global $NIOS_Grade_Mapping;
                            $Reverse_NIOS_Grade_Mapping = array_flip($NIOS_Grade_Mapping);
                            $displayData['educationInfoProfile']['UG']['marksHeading'] = 'Percentage';
                            $displayData['educationInfoProfile']['UG']['tenthMarks'] = $Reverse_NIOS_Grade_Mapping[$displayData['educationInfoProfile']['UG']['tenthMarks']];
                            break;
                        }
                }
            }else if($education->getName() == 'Graduation'){
                global $workExperience;
//                $displayData['PG']['graduationCompletionYear'] = $year;
                $displayData['educationInfoProfile']['PG']['workExperience'] = $user->getExperience();
                $displayData['educationInfoProfile']['PG']['workExperience'] = $workExperience[$displayData['educationInfoProfile']['PG']['workExperience']];
                $displayData['educationInfoProfile']['PG']['marks'] = $marks;
                $displayData['educationInfoProfile']['PG']['stream'] = $education->getSubjects();
            }
        }
        if(!empty($displayData['exams']))
            $displayData['exams'] = $this->_getExamsOrdered($displayData['exams']);
    }

    private function _getExamsOrdered($exams){
        $usersExams = array();
        $this->CI->load->config('applyHome/counselorReviewConfig');
        global $examsPriorityOrder;
        foreach($examsPriorityOrder as $exam){
            if(isset($exams[$exam]))
                $usersExams[] = array('name'=>$exam,'marks'=>$exams[$exam]);
        }
        return  $usersExams;
    }

    public function userAdmittedInfo($userId)
    {
        if(is_numeric($userId) && !is_array($userId)){
            $userId = array($userId);
        }
        $this->applyHomePageModel = $this->CI->load->model('applyHome/applyhomepagemodel');
        $userAdmittedCourses = $this->applyHomePageModel->getUserAdmittedCourses($userId);
        $courses = array_values($userAdmittedCourses);
        $userAdmittedCourseList = array();
        $universityCountyStr = '';
        if(!empty($courses)){
            $this->CI->load->builder('ListingBuilder','listing');
            $listingBuilder = new ListingBuilder;
            $abroadCourseRepository = $listingBuilder->getAbroadCourseRepository();
            $courseObjs = $abroadCourseRepository->findMultiple($courses);
            foreach($userAdmittedCourses as $user => $courseId){
                if(!($courseObjs[$courseId] instanceof AbroadCourse))
                {
                    continue;
                }
                $universityName = $courseObjs[$courseId]->getUniversityName();
                $countryName = $courseObjs[$courseId]->getCountryName();
                $universityCountyStr = '';
                if(!empty($universityName)){
                    $universityCountyStr .='Admitted to '.$universityName;
                }
                if(!empty($universityCountyStr) && !empty($countryName)){
                    $universityCountyStr .= ', '.$countryName;
                }
                $universityCountyStr = formatArticleTitle($universityCountyStr,50);
                $userAdmittedCourseList[$user] = $universityCountyStr;
            }
            if(count($userId) == 1) // single user case
            {
                return $universityCountyStr;
            }else{
                return $userAdmittedCourseList;
            }
        }
    }

    public function prependDomainToUserImageUrl($imageUrl,$flagDefault=false){
        if($flagDefault || empty($imageUrl) || strpos($imageUrl,'/public/images/photoNotAvailable.gif') ){
            return IMGURL_SECURE.'/public/images/studyAbroadCounsellorPage/profileDefaultNew1.jpg';
        }
        if(strpos($imageUrl,'public') && strpos($imageUrl,'gif')){//for avatar img default image would
            return IMGURL_SECURE.'/public/images/studyAbroadCounsellorPage/profileDefaultNew1.jpg';
        }
        if(strpos($imageUrl,'public') && strpos($imageUrl,'profileDefault')){
            $imageUrl = str_replace('/public/images/', '/public/images/studyAbroadCounsellorPage/', $imageUrl);
            $imageUrl = str_replace('.gif', '.jpg', $imageUrl);
            return IMGURL_SECURE.$imageUrl;
        }else return MEDIAHOSTURL.$imageUrl;
    }
    /* 
     * in comment sections images are to be shown in small size, avtar images are allowed.
     */
    public function prependDomainToUserImageUrlInComments($imageUrl, $flagDefault=false){
        if($flagDefault || empty($imageUrl) || strpos($imageUrl,'/public/images/photoNotAvailable.gif') ){
            return IMGURL_SECURE.'/public/images/studyAbroadCounsellorPage/profileDefaultNew1.jpg';
        }else if(strpos($imageUrl,'public') !== false){
            return IMGURL_SECURE.$imageUrl;
        }else {
            return MEDIAHOSTURL.$imageUrl;
        }
    }

    private function _getCurrentLevelByCourseLevel($courseLevel){
        if(strpos($courseLevel, 'Bachelors') !== false){
            $currentLevel = 'Bachelors';
        }else if(strpos($courseLevel, 'Masters') !== false){
            $currentLevel = 'Masters';
        }else if(strpos($courseLevel, 'PhD') !== false){
            $currentLevel = 'PhD';
        }
        return $currentLevel;
    }

    private function _userDestinationCountriesOrdered(&$displayData)
    {
        global $studyAbroadPopularCountries;
        $orderedCountry = array();
        foreach ($studyAbroadPopularCountries as $id => $name)
        {
            if(isset($displayData['destinationCountry'][$id]))
            {
                $orderedCountry[] = $name;
                unset($displayData['destinationCountry'][$id]);
            }
        }
        $tempCountryArray = array();
        if(!empty($displayData['destinationCountry']))
        {
            reset($displayData['destinationCountry']);
            $locationRepository  = $this->locationBuilder->getLocationRepository();
            foreach ($displayData['destinationCountry'] as $id => $val)
            {
                $location = $locationRepository->findCountry($id);
                $tempCountryArray[] = $location->getName();
            }
            if(count($tempCountryArray) > 1)
            {
                asort($tempCountryArray);
            }
        }
        $displayData['destinationCountry'] = array_merge($orderedCountry,$tempCountryArray);
    }

    public function setMISTrackingDetails(&$displayData, $profilePageType){
    	$displayData['beaconTrackData']['pageEntityId'] = $displayData['profileUserId'];
    	$displayData['beaconTrackData']['extraData']    = null;
    	if($profilePageType == 'editProfile'){
        	$displayData['beaconTrackData']['pageIdentifier'] = 'editUserProfilePage';
        }else if($profilePageType == 'viewProfile'){
        	$displayData['beaconTrackData']['pageIdentifier'] = 'viewUserProfilePage';
        }
    }

    public function setSEODetails(&$displayData, $profilePageType){
    	$displayData['seoDetails']['url'] = getCurrentPageURLWithoutQueryParams();
    	$profileUserName = ucwords($displayData['profileUserData']['firstname'].' '.$displayData['profileUserData']['lastname']);
    	if($profilePageType == 'editProfile'){
    		$displayData['seoDetails']['seoTitle'] = 'Edit - '.$profileUserName."'s Profile Information | Shiksha";
    		$displayData['seoDetails']['seoDescription'] = $profileUserName.' - edit your personal information, educational information, display name, and other account options related to your Shiksha account.';
    	}else if($profilePageType == 'viewProfile'){
    		$displayData['seoDetails']['seoTitle'] = $profileUserName."'s Profile Information | Shiksha";
    		$displayData['seoDetails']['seoDescription'] = "View ".$profileUserName."'s profile information and activities on Shiksha.com. Join this study abroad education & career community to connect with career experts, counselors, and students online.";
    	}
    }

    public function redirectURLToNationalProfilePage($user_id){

        if($user_id>0){
            $url = SHIKSHA_HOME.'/userprofile/'.$user_id;
        }else{
            $url = SHIKSHA_HOME;
        }

        return $url;
    }
	
	public function prepareDataToPrefillForm(& $displayData, $validateUser)
	{
		$this->_init();
		
		if($user = $this->usermodel->getUserById($displayData['profileUserId']))
        {
			$displayData['profileUserData']['passport'] = $user->getPassport();
			$displayData['profileUserData']['userCity'] = $user->getCity();
            // object for tUserPref data
            $pref = $user->getPreference();
			if(is_object($pref)){
				$desiredCourse = $pref->getDesiredCourse();
				$displayData['profileUserData']['desiredCourse'] = $desiredCourse;
				$displayData['profileUserData']['abroadSpecialization'] =  $pref->getAbroadSpecialization();
				if($pref->getExtraFlag() != 'studyabroad')
				{
					redirect($this->redirectURLToNationalProfilePage($displayData['profileUserId']));
				} 
			}else{
				$desiredCourse = null;
			}
			
			if(!empty($desiredCourse)){
				$signupLib = $this->CI->load->library('studyAbroadCommon/AbroadSignupLib');
				$displayData['profileUserData']['currentLevel'] = $signupLib->getCurrentLevelByLDBCourseId($desiredCourse);    
			}
			// countries
            $loc = $user->getLocationPreferences();
			$displayData['profileUserData']['userPreferredDestinations'] = array();
            foreach($loc  as $location) {
				$countryId = $location->getCountryId();
				if($countryId > 2)
				{
					array_push($displayData['profileUserData']['userPreferredDestinations'], $countryId);
				}
			}
            
            //* Get Fields value for SA Shiksha Apply* //
            $userAdditionalInfo = $user->getUserAdditionalInfo();
            if($userEducation = $user->getEducation()){
                foreach($userEducation as $education) {
                    if($education->getName() == '10'){
                        if(is_object($userAdditionalInfo)){
							$displayData['profileUserData']['CurrentClass'] = $userAdditionalInfo->getCurrentClass();
							$displayData['profileUserData']['CurrentSchoolName'] = $userAdditionalInfo->getCurrentSchool();
                        }
                        $displayData['profileUserData']['EduName'] = '10';
                        $displayData['profileUserData']['tenthBoard'] = $education->getBoard();
                        $displayData['profileUserData']['tenthMarks'] = $education->getMarks();
                    }else if($education->getName() == 'Graduation'){
                        $displayData['profileUserData']['EduName'] = 'Graduation';
                        $displayData['profileUserData']['workExperience'] = $user->getExperience();
                        $displayData['profileUserData']['graduationStream'] = $education->getSubjects();
                        $displayData['profileUserData']['graduationPercentage'] = $education->getMarks();
                    }   
                }
            }
			//var_dump($user->getExperience());die;
        }
		//_p($displayData['profileUserData']);die;
	}
}
