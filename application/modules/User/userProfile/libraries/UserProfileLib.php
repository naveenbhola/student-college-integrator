<?php 

class UserProfileLib{
	private $userprofilemodel;
	private $profileCTAMapping = array(
										'Questions Asked' => array('Ask Your First Question','/questions'),
										'Answers' => array('Answer Questions Now','/unanswers'),
										'Following' => array('Follow People Now',''),
										'Discussion Comments' => array('Participate In Discussion','/discussions'),
										'Tags Followed' => array('Follow Tags Now','/tags')
										);
	private $examScoreTypeConfig = array('percentile','percentage','score','rank');

	function __construct(){
		$this->CI = & get_instance();

	}

	private function _init(){
		$this->CI->load->model('userProfile/userprofilemodeldesktop');
		$this->userprofilemodel = new userprofilemodeldesktop();
	}

	/*
	*	function to return user profile details based on profile type - publicProfile,myProfile
	*/
	public function getUserProfileDetails($userId,$profileType = 'publicProfile', $groupId='all'){
		$this->_init();
		$masterPrivateFieldIds = array('Email','Mobile','DateOfBirth','StudentEmail');
		
		if(empty($userId)){
			return array('error' => "User Id is null");
		}

		$entity = $this->getEntityList($groupId);

		foreach ($entity as $groupId => $entityName) {
			$userDetails[$groupId] = $this->getUserDetailByGroupId($userId,$profileType, $groupId,$entityName,$masterPrivateFieldIds);
		}

		if($userDetails['userPreference']['ExtraFlag'] == 'studyabroad') {
			if(!empty($userDetails['Competitive exam']['Name'])) {
				$examName = "IELTS";
				if(in_array($examName, $userDetails['Competitive exam']['Name'])) {
					$updatedExamDetails = $this->userprofilemodel->fetchExamDetailsFromDB($userId);
					if(!empty($updatedExamDetails)) {
						$userDetails['Competitive exam'] = $updatedExamDetails;
					}
				}
			}
		}

		return $userDetails;
	}

	private function getUserDetailByGroupId($userId,$profileType = 'publicProfile', $groupId,$entityName,$masterPrivateFieldIds){

			if ($profileType == 'myProfile'){
				$userProfileFields = $this->userprofilemodel->getUserMyProfile($userId,$groupId);
			} else{
				$userProfileFields = $this->userprofilemodel->getUserPublicProfile($userId,$groupId,$masterPrivateFieldIds);
			}
			
			$this->usermodel = $this->CI->load->model('user/usermodel');
			$user = $this->usermodel->getUserById($userId);
                        if(!is_object($user)) {
				return;
                        }

			if($groupId == 'personalInfo'){
				$entitityObjects= $user;
			} else{
				$entitityObjects= $user->$entityName();
			}

			if(!is_object($entitityObjects)){
				return;
			}

			unset($user);

			if($entityName == 'getEducation'){
				foreach ($entitityObjects as $entitityObject){
					if($entitityObject->getLevel() == $groupId){
						$entity[] = $entitityObject;
						
						$entityFound = true;					//user details exist for this level
						
						 if($groupId != 'Competitive exam' && $groupId != '10' ){
							break;
						} 
					}	
				}	

				if(!$entityFound){								//if user details doesn;t exist, exit processing
					return;
				}
			}

			if($entityName == 'getUserWorkExp'){

				$workExpNumber = substr($groupId, -1);

				if($groupId == 'workExp10'){
					$workExpNumber =10;
				}

				$workExpCount = 1;
				foreach ($entitityObjects as $entitityObject) {
					if($workExpCount == $workExpNumber){
						$entity[]= $entitityObject;
						$entityFound = true;					//user details exist for this level
						break;
					}
					$workExpCount++;
				}

				if(!$entityFound){								//if user details doesn;t exist, exit processing
					return;
				}
			}

			if($entityName == 'locationPreferences'){
				foreach ($entitityObjects as $entitityObject){
						$entity[] = $entitityObject;
						
						$entityFound = true;					//user details exist for this level
				}	

				if(!$entityFound){								//if user details doesn;t exist, exit processing
					return;
				}
			}

			if(!empty($entity) && $groupId != 'Competitive exam' && $groupId != '10'){
				$entityObj = $entity[0];
			} else if(!empty($entity) && ($groupId == 'Competitive exam' || $groupId == 'locationPreferences' || $groupId == '10')){
				$entityObj = $entity;
			}else {
				$entityObj = $entitityObjects;
			}

			unset($entityArray);
			unset($entitityObjects);
			unset($entity);

			foreach ($userProfileFields as $key => $value) {
				$value= str_replace($groupId,"",$value['fieldId']);
				$functionName = 'get'.$value;

				if($groupId != 'Competitive exam' && $groupId != 'locationPreferences' && $groupId != '10'){
					$userDetails[$value] = $entityObj->$functionName();	

					if($value == 'DesiredCourse' && empty($userDetails[$value])){
						$userDetails['showNationalPref'] = 'yes';
					}	
				} else{		
					foreach ($entityObj as $key) {
					
						$userDetails[$value][] = $key->$functionName();
					}
				}

				if($groupId == '10' && $value != 'Subjects'){
					$userDetails[$value] = $userDetails[$value][0];
				}

				if($groupId == 'PHD' && $value == 'CourseSpecialization' && empty($userDetails[$value])){
					$userDetails[$value] = $entityObj->getSpecialization();
				}
			}
		
		return $userDetails;
	}

	private function getEntityList($groupId){

		$groupIdEntityMapping = array('personalInfo' => 'user',
										'10'=>'getEducation','12'=>'getEducation','UG'=>'getEducation','PG'=>'getEducation',
										'PHD'=>'getEducation','Competitive exam'=>'getEducation',
										
										'workExp1' => 'getUserWorkExp','workExp2' => 'getUserWorkExp','workExp3' => 'getUserWorkExp',
										'workExp4' => 'getUserWorkExp','workExp5' => 'getUserWorkExp',
										'workExp6' => 'getUserWorkExp','workExp7' => 'getUserWorkExp','workExp8' => 'getUserWorkExp',
										'workExp9' => 'getUserWorkExp','workExp10' => 'getUserWorkExp',
										
										'socialInfo'=>'getSocialProfile','additionalInfo'=>'getUserAdditionalInfo',

										'userPreference' => 'getPreference', 'locationPreferences' =>'getLocationPreferences'
									);

		$entity = array();

		if($groupId == 'all'){
			foreach ($groupIdEntityMapping as $groupId=> $entityName) {
				$entity[$groupId] = $entityName;
			}
		} else{
			$entity[$groupId] = $groupIdEntityMapping[$groupId];
		}

		return 	$entity;
	}

	public function getUserFlagDetails($userId){
		$this->_init();
		$flagDetails = $this->userprofilemodel->getUserFlagInfo($userId);
		return $flagDetails[0];
	}

	public function setUserFieldPrivate($userId,$fieldIds=array()){
		$this->_init();

		return $this->userprofilemodel->setUserFieldPrivate($userId,$fieldIds);

	}

	public function setUserFieldPublic($userId,$fieldIds=array()){
		$this->_init();

		return $this->userprofilemodel->setUserFieldPublic($userId,$fieldIds);
	}	

	public function getUserPointsLevel($userId){
		$this->_init();
		
		return $this->userprofilemodel->getUserPointsLevel($userId);
	}

	public function getUserLocationDetails($city, $locality){
		$this->_init();

		$data = array();
		if($locality > 0){
			$data = $this->userprofilemodel->getUserCityLocaityData($locality);
		}else{
			$data = $this->userprofilemodel->getUserCityData($city);
		}
		
		return $data[0];
	}

	public function getCountryName($countryId){
		$this->_init();
		return $this->userprofilemodel->getCountryName($countryId);
	}

	public function getUserPrivateDetails($userId){
		$this->_init();

		$userPrivacyData = $this->userprofilemodel->getUserPrivateFields($userId);

		return $userPrivacyData;
	}

	private function fetchExamDetailsFromDB($userId) {		
		$this->_init();

		$examDetails = $this->userprofilemodel->fetchExamDetailsFromDB($userId);

		return $examDetails;
	}

	/**
	* Function to get user level data from userpointvaluebymodule table
	* @param : userId
	* @return : user level data (levelId, points and level name)
	*/
	public function getUserLevelData($userId){
		
		$this->_init();
		
		return $this->userprofilemodel->getUserLevelData($userId);
	}
	

	public function getUserProfielStats($userId, $start = 0, $count = 10){
		$APIClient = $this->CI->load->library("APIClient");     
	       
        $APIClient->setRequestType("get");
		
		$activityStats = $APIClient->getAPIData("UserProfile/getUserActivitiesAndStats/".$userId."/".$start	."/".$count);

		return $activityStats;
	}

	public function formatUserStatsWithCTA($activityStats,$publicProfile = false){
		$ctaData = array();

		if(!$publicProfile){
			$ctaData = $this->profileCTAMapping;
		}
		
		if(empty($activityStats) || !is_array($activityStats)){
			return array();
		}
		
		$keyCounter =0;
		foreach ($activityStats as $key => $stat) {
			if($stat['value'] ==0 && array_key_exists($stat['lable'],$ctaData) && $stat['lable'] != 'Following' ){
				$newActivityStats[$keyCounter]['id'] = $stat['id'];
				$newActivityStats[$keyCounter]['lable'] = $ctaData[$stat['lable']][0];
				$newActivityStats[$keyCounter]['value'] = 0;
				$newActivityStats[$keyCounter]['isCTA'] = true;
				$newActivityStats[$keyCounter]['redirectionUrl'] = ($ctaData[$stat['lable']][1]) ? SHIKSHA_HOME.$ctaData[$stat['lable']][1] : '';
				$keyCounter++;
			} else if ($stat['value'] != 0){
				$newActivityStats[$keyCounter]['id'] = $stat['id'];
				$newActivityStats[$keyCounter]['lable'] = $stat['lable'];
				$newActivityStats[$keyCounter]['value'] = $stat['value'];
				$keyCounter++;
			}
		}

		unset($ctaData);

		return $newActivityStats;
	}

	public function followUnfollowAction($data){

		$this->_init();
		if(empty($data)){
			return 'Failure';
		}

		$APIClient = $this->CI->load->library("APIClient");     
        $APIClient->setRequestType("post");
		$APIClient->setRequestData($data);
	    $APIClient->setUserId($data['userId']);
		$followEntityData = $APIClient->getAPIData("Universal/followEntity");
		if($followEntityData['responseMessage'] == 'Success'){
			$followEntityData['followingCount'] = $this->userprofilemodel->getCountOfUsersIamFollowing($data['userId']);
		}
		return $followEntityData;
	}

	public function deleteCommentAnswerAction($data){

		if(empty($data)){
			return 'Failure';
		}

		$APIClient = $this->CI->load->library("APIClient");     
        $APIClient->setRequestType("post");
		$APIClient->setRequestData($data);
	    $APIClient->setUserId($data['userId']);
		$deleteEntityData = $APIClient->getAPIData("AnAPost/shortlistEntity");
		
		return $deleteEntityData['responseMessage'];
	}

	public function showDetailedTuples($apiForANA,$userId,$entityType,$entityCategory='',$start=0, $count=10,$loggedInUserId){

		$APIClient = $this->CI->load->library("APIClient");
		$APIClient->setRequestType("get");

		$APIClient->setUserId($loggedInUserId);

		if($entityCategory == 'answerLater' || $entityCategory == 'questionsAsked' || $entityCategory == 'questionsFollowed' || $entityCategory == 'answerUpvotedQuestions' || $entityCategory == 'HQAnswerQuestions' || $entityCategory == 'answers'){
			$EntityData = $APIClient->getAPIData($apiForANA."/".$userId."/".$entityCategory."/".$start."/".$count);
			return $EntityData['questions'];

		}else if($entityCategory == 'commentLater' || $entityCategory == 'discussionsPosted' || $entityCategory == 'comments' || $entityCategory == 'discussionsFollowed' || $entityCategory == 'commentUpvotedDiscussions' || $entityCategory == 'HQCommentDiscussions'){
			$EntityData = $APIClient->getAPIData($apiForANA."/".$userId."/".$entityCategory."/".$start."/".$count);
			return $EntityData['discussions'];

		}else if($entityCategory == 'Followers'){
			$EntityData = $APIClient->getAPIData($apiForANA."/".$userId."/".$start."/".$count);
			return $EntityData['users'];

		}else if($entityCategory == 'Following'){	
			$EntityData = $APIClient->getAPIData($apiForANA."/".$userId."/".$start."/".$count);
			return $EntityData['users'];

		}else if($entityCategory == 'Tags Followed'){
			$EntityData = $APIClient->getAPIData($apiForANA."/".$userId."/".$start."/".$count);
			return $EntityData['tags'];

		}
	}

	//function to get all privacy details of user
	public function getUserPrivacyDetails($userId){
			
		$this->_init();
		$userPrivateData = $this->getUserPrivateDetails($userId);

		$masterPrivacyData = $this->userprofilemodel->getMasterPrivacyFields($userId);

		$data = array();
		$userPrivacyDetails = array();

		foreach ($userPrivateData as $key => $value) {
			$data[] = $value['fieldId'];
			if($value['fieldId'] == 'activitystats'){
				$userPrivacyDetails['activitystats'] = 'private';
			}

			if($value['fieldId'] == 'expertise'){
				$userPrivacyDetails['expertise'] = 'private';
			}
		}
		
		foreach ($masterPrivacyData as $key => $value) {
			if(in_array($value['fieldId'], $data)){
				$userPrivacyDetails[$value['fieldId']] = 'private';
			} else{
				$userPrivacyDetails[$value['fieldId']] = 'public';
			}
		}

		$userPrivacyDetails['Email'] = 'private';
		$userPrivacyDetails['Mobile'] = 'private';
		$userPrivacyDetails['StudentEmail'] = 'private';
		if(!$userPrivacyDetails['activitystats']){
			$userPrivacyDetails['activitystats'] = 'public';
		}
		if(!$userPrivacyDetails['expertise']){
			$userPrivacyDetails['expertise'] = 'public';
		}

		return $userPrivacyDetails;

	}

	public function getURLForTupple($id, $type, $title = ""){
		$this->CI->load->helper('url_helper');
		
		$url = getSeoUrl($id, $type, $title); 
		return $url;
	}

	public function getFlagIfIAmFollowing($entityId, $loggedInUserId){
		$this->_init();
		$getFollowingDetail = $this->userprofilemodel->getFlagIfIAmFollowing($entityId, $loggedInUserId);
		return $getFollowingDetail;
	}

	public function redirectionUrl($display_name, $urlType = 'viewPage'){

		$user_profile_lib = $this->CI->load->library('userProfilePage/userProfilePageLib');
		
		$redirect_url = $user_profile_lib->getUserProfilePageURL($display_name, $urlType);
		$redirect_url = json_decode($redirect_url,true);

		$redirect_url = $redirect_url['url'];
		if($redirect_url == ''){
			$redirect_url = SHIKSHA_HOME;
		}

		return $redirect_url;
	}

	public function userUnsubscribeMapping($userId,$unsubscribeStatus,$unsubscribeCatId){		
		$this->_init();
		$this->userprofilemodel->userUnsubscribeMapping($userId,$unsubscribeStatus,$unsubscribeCatId);
		global $unsubscribe_category;

		$this->CI->load->config('response/responseConfig');
		$unsubscribe_category = $this->CI->config->item('unsubscribe_category');

		if(in_array($unsubscribeCatId,$unsubscribe_category)){
			$user_response_lib = $this->CI->load->library('response/userResponseIndexingLib');
			$extraData         = "{'personalInfo:true'}";
			$user_response_lib->insertInIndexingQueue($userId, $extraData);
		}
		$usermodel = $this->CI->load->model('user/usermodel');
		$usermodel->addUserToIndexingQueue($userId);
		return true;
	}

	public function getUserUnsubscribeMapping($userId){
		$this->_init();
		$data = $this->userprofilemodel->getUserUnsubscribeMapping($userId);
		$unsubscribeCategories = array();
		foreach ($data as $key => $value) {
			$unsubscribeCategories[$value['unsubscribe_category']] = 1;
		}
		return $unsubscribeCategories;
	}

	public function saveExamProfileData($userId, $userExamDetails){

		if($userId<=0){
			return 'invalid userId';
		}


		foreach ($userExamDetails as $index => $examDetail) {

			$validationFlag = $this->_validateExamProfileData($examDetail['examName'], $examDetail['examGroupId'] , $examDetail['examScoreType'], $examDetail['examScore']);

			if(!$validationFlag){
				unset($userExamDetails[$index]);
			}else{

				if($examDetail['examScore']<=0){
					$exam_with_score_group[] = $examDetail['examGroupId'];
				}else{
					$all_exams[] = $examDetail['examGroupId'];
				}
			}

			$group_id_index_map[$examDetail['examGroupId']] = $index;
		}

		
		if(count($userExamDetails)<1){
			return false;
		}

		$this->_init();


		if(count($exam_with_score_group)>0){
			$exam_with_score =	$this->userprofilemodel->checkValidExamProfile($exam_with_score_group, $userId);
		}
	

		foreach ($exam_with_score as $exam) {
			$temp_check_array[] = $exam['examGroupId'];
		}

		foreach ($exam_with_score_group as $key => $group_id) {

			if(!in_array($group_id, $temp_check_array)){
				$all_exams[] = $group_id;
			}else{
				unset($userExamDetails[$group_id_index_map[$group_id]]);
			}
		}

		if(count($all_exams)<1){
			return;
		}
		
		$this->userprofilemodel->deleteUserExamProfile($userId, $all_exams);
			
		return $this->userprofilemodel->saveUserExamProfile($userId, $userExamDetails);
    }

    private function _validateExamProfileData($examName, $examGroupId, $examScoreType, $examScore){

    	if($examName ==''){
			return false;
		}

    	if($examGroupId<1 || $examGroupId=='')	{
    		return false;
    	}

    	if($examScoreType!='' && !in_array($examScoreType, $this->examScoreTypeConfig)){
    		return false;	
    	}

    	if($examScoreType !='' && !is_numeric($examScore)){
    		return false;
    	}

    	if($examScoreType =='' && $examScore>0){
    		return false;
    	}


		if($examScoreType != 'score' && (int)$examScore<0 ){
    		return false;
    	} 

    	if($examScoreType == 'rank' && (int)$examScore == 0 ){
    		return false;
    	}    	

    	if( ($examScoreType == 'percentile' || $examScoreType =='percentage') && (int)$examScore>100 ){
    		return false;
    	}


    	return true;
    }
}

?>
