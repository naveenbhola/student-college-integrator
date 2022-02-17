<?php 
class listingPageWidgetLib {
    
    private $CI = '';
    private $LDB_SEARCH_URL;
    private $MAX_PROFILES_COUNT;
	private $widgetType;
    
    function __construct(){
        $this->CI = &get_instance();
        $this->MAX_PROFILES_COUNT = 10;
        $this->LDB_SEARCH_URL = SOLR_LDB_SEARCH_SELECT_URL_BASE;
        $this->CI->load->builder('SearchBuilder','search');
        $this->searchServer = SearchBuilder::getSearchServer();
        $this->listingPageWidgetModel = $this->CI->load->model('listingPage/listingpagewidgetmodel');
        $this->userProfilePageLib = $this->CI->load->library('userProfilePage/userProfilePageLib');
        $this->CI->load->config('applyHome/counselorReviewConfig');
    }

    private function _init($listingTypeIdArr, $fromPage){
		if(empty($listingTypeIdArr)){
			return false;
		}
		$this->widgetType = $fromPage.'TypeWidget';
		$this->CI->load->builder('ListingBuilder','listing');
		$listingBuilder = new ListingBuilder;
		$this->abroadCourseRepository = $listingBuilder->getAbroadCourseRepository();
		return true;
	}

    public function getUserProfileWidgetData($listingTypeIdArr, $fromPage){
		$validateData = $this->_init($listingTypeIdArr, $fromPage);
		if($validateData == false){
			return false;
		}
		$userAdmittedCourseArr = array(); $courseData = array();
		$finalUsers = $this->getAdmittedUserListForTheWidget($listingTypeIdArr);

		if(count($finalUsers) == 0) // no users found
		{
			return array();
		}
		foreach ($finalUsers as $value) {
			$userAdmittedCourseArr[$value['userId']] = $value['courseId'];
		}
		
		//Get user data
		$admittedUserIdArr = array_keys($userAdmittedCourseArr);
		$url = $this->generateRequestURLForUserDetails($admittedUserIdArr);

		//echo $url;
		$userData = $this->initiateSolrCall($url);

		//_p($userData);die;
		//Extract and format user data
		$orderedUserData = array();
		foreach ($userData as $value) {
			$list = $value['doclist']['docs'];
			$orderedUserData[$value['groupValue']] = $list[0];
			$orderedUserData[$value['groupValue']]['displayData'] = json_decode($list[0]['displayData'],true);
			unset($orderedUserData[$value['groupValue']]['displayData']['PrefData']);
		}

		//Get admitted course data
        $listingData = $this->abroadCourseRepository->findMultiple(array_unique($userAdmittedCourseArr));
		foreach ($listingData as $courseId => $courseObj) {
			$courseData[$courseId]['courseName']     = $courseObj->getName();
			$courseData[$courseId]['universityName'] = $courseObj->getUniversityName();
		}
		$userProfileData = $this->formatUserDataForTheWidget($userAdmittedCourseArr, $orderedUserData, $courseData);
		return $userProfileData;
    }

    private function getAdmittedUserListForTheWidget($listingTypeIdArr){
    	$usersFound = 0; $finalUsers = array(); $courseIdsAlreadyChecked = array();
		//Step 1
		if($this->widgetType == 'coursePageTypeWidget'){
			$data = $this->listingPageWidgetModel->getEnrolledUsers($listingTypeIdArr, $this->MAX_PROFILES_COUNT);
			$courseIdsAlreadyChecked = array_merge($courseIdsAlreadyChecked, $listingTypeIdArr);
			$finalUsers = array_merge($finalUsers, $data);
			$usersFound = count($finalUsers);
		}
		//Step 2
		if($usersFound < $this->MAX_PROFILES_COUNT){
			$limit = $this->MAX_PROFILES_COUNT - $usersFound;
			$courseObj = $this->abroadCourseRepository->find($listingTypeIdArr[0]);
			if($this->widgetType == 'coursePageTypeWidget'){
				$univId = is_object($courseObj) ? $courseObj->getUniversityId() : 0;
				$courseLevel = is_object($courseObj) ? $courseObj->getCourseLevel1Value() : '';
				$allCourseLevel = $this->getAllCourseLevels($courseLevel);

				$filter = array();
				$filter['univId'] = $univId;
				$filter['courseLevels'] = $allCourseLevel;
				$otherCourses = $this->listingPageWidgetModel->getAllCoursesWithFilter($filter, $courseIdsAlreadyChecked);				
				$otherCourses = array_map(function($t){return $t['course_id'];}, $otherCourses);
				$data = $this->listingPageWidgetModel->getEnrolledUsers($otherCourses, $limit);
				$courseIdsAlreadyChecked = array_merge($courseIdsAlreadyChecked, $otherCourses);
			}else if($this->widgetType == 'universityPageTypeWidget'){
				$data = $this->listingPageWidgetModel->getEnrolledUsers($listingTypeIdArr, $limit);
				$courseIdsAlreadyChecked = array_merge($listingTypeIdArr, $otherCourses);
			}
			$finalUsers = array_merge($finalUsers, $data);
			$usersFound = count($finalUsers);
		}
		//Step 3
		if($usersFound < $this->MAX_PROFILES_COUNT){
			$limit = $this->MAX_PROFILES_COUNT - $usersFound;
			$courseCountryId = is_object($courseObj) ? $courseObj->getCountryId() : 0;
			if($this->widgetType == 'coursePageTypeWidget'){
				$courseLevel = is_object($courseObj) ? $courseObj->getCourseLevel1Value() : '';
				$allCourseLevel = $this->getAllCourseLevels($courseLevel);
			}else{
				$allCourseLevel = $this->getAllCourseLevels('all');
			}
			$filter = array();
			$filter['countryId'] = $courseCountryId;
			$filter['courseLevels'] = $allCourseLevel;

			$otherCourses = $this->listingPageWidgetModel->getAllCoursesWithFilter($filter, $courseIdsAlreadyChecked);
			$otherCourses = array_map(function($t){return $t['course_id'];}, $otherCourses);
			
			
			$excludeUserList = array_map(function($t){return $t['userId'];}, $finalUsers);
			$data = $this->listingPageWidgetModel->getEnrolledUsers($otherCourses, $limit,$excludeUserList);
			$finalUsers = array_merge($finalUsers, $data);
		}
		return $finalUsers;
    }

    private function formatUserDataForTheWidget($userAdmittedCourseArr, $orderedUserData, $courseData){
    	global $examsPriorityOrder; global $workExperience;
    	$admittedUserIdArr = array_keys($userAdmittedCourseArr);
    	foreach ($admittedUserIdArr as $userId) {
			$userEducationData = array();
			$value = $orderedUserData[$userId];
			$userProfileData[$value['userId']]['name'] = ucwords(strtolower(trim($value['firstname']).' '.trim($value['lastname'])));
			
			//user image
			$finalUrl = getUserProfileImageLink($value['displayData']['avtarimageurl']);
			$finalUrl = resizeImage($finalUrl, 'm');
			$finalUrl = getProfileImageAbsoluteUrl($finalUrl);
			$userProfileData[$value['userId']]['image'] = $finalUrl;

			//user admitted course data
			$userProfileData[$value['userId']]['admissionData'] = array(
				'courseName'=>$courseData[$userAdmittedCourseArr[$value['userId']]]['courseName'],
				'univName'=>$courseData[$userAdmittedCourseArr[$value['userId']]]['universityName']
				);

			//extract exam and education data

			// foreach ($value as $key => $val) {
			// 	if(strpos($key, '_educationLevel') !== false || strpos($key, '_educationMarks') !== false || strpos($key, '_educationMarksType') !== false){
			// 		$fieldName = explode('_', $key);
			// 		if(in_array($fieldName[0], array('10','graduation'))){
			// 			$userEducationData[$value['userId']]['allEducations'][$fieldName[0]][$fieldName[1]]=$val;
			// 		}else{
			// 			$userEducationData[$value['userId']]['allExams'][$fieldName[0]][$fieldName[1]]=$val;
			// 		}
					
			// 	}
			// }

			$latest10thId = 0; $latestGradId = 0;
			foreach ($value['displayData']['EducationData'] as $key => $val) {
				$key2 = strtolower($val['Name']);
				if(in_array($val['Name'], array('10','Graduation'))){
					if($val['Name'] == 'Graduation' && $val['id'] > $latestGradId){
						$latestGradId = $val['id'];
						$userEducationData[$value['userId']]['allEducations'][$key2]['educationLevel']=$val['Level'];
						$userEducationData[$value['userId']]['allEducations'][$key2]['educationMarks']=(int)$val['Marks'];
						$userEducationData[$value['userId']]['allEducations'][$key2]['educationMarksType']=$val['MarksType'];
					}
					if($val['Name'] == '10' && $val['id'] > $latest10thId){
						$latest10thId = $val['id'];
						$userEducationData[$value['userId']]['allEducations'][$key2]['educationLevel']='10';
						$userEducationData[$value['userId']]['allEducations'][$key2]['educationMarks']=(int)$val['Marks'];
						$userEducationData[$value['userId']]['allEducations'][$key2]['educationMarksType']=$val['MarksType'];
						$userEducationData[$value['userId']]['allEducations'][$key2]['board']=$val['board'];
					}
				}else{
					$userEducationData[$value['userId']]['allExams'][$key2]['educationLevel']=$val['Level'];
					$userEducationData[$value['userId']]['allExams'][$key2]['educationMarks']=(int)$val['Marks'];
					$userEducationData[$value['userId']]['allExams'][$key2]['educationMarksType']=$val['MarksType'];
				}
			}

			//format exams
			$userAllExams = array_keys($userEducationData[$value['userId']]['allExams']);
			if(!empty($userAllExams)){
				foreach ($examsPriorityOrder as $exam) {
					if(in_array(strtolower($exam), $userAllExams)){
						$userProfileData[$value['userId']]['exam'] = $userEducationData[$value['userId']]['allExams'][strtolower($exam)];
						$userProfileData[$value['userId']]['exam']['examName'] = $exam;
						break;
					}
				}
			}

			//format education
			if(isset($userEducationData[$value['userId']]['allEducations']['10'])){
				$userProfileData[$value['userId']]['education'] = $userEducationData[$value['userId']]['allEducations']['10'];
				//$userProfileData[$value['userId']]['education']['educationLevel'] = '10';
				//$userProfileData[$value['userId']]['education']['board'] = $value['board'];
				$this->mapBoardAndMarksWithGradeMapping($userProfileData[$value['userId']]['education']);
			}
			if(isset($userEducationData[$value['userId']]['allEducations']['graduation'])){
				$userProfileData[$value['userId']]['education'] = $userEducationData[$value['userId']]['allEducations']['graduation'];
				$userProfileData[$value['userId']]['education']['workex'] = $value['workex'];

				if($value['workex'] == '-1' || $value['workex'] === NULL){
					$userProfileData[$value['userId']]['education']['workex'] = 'None';
				}else{
					$userProfileData[$value['userId']]['education']['workex'] = $workExperience[$value['workex']];
				}
			}
			
			//user profile page link
			$linkData = $this->userProfilePageLib->getUserProfilePageURL(trim($value['displayname']), 'viewPage', false);
			$userProfileData[$value['userId']]['profileLink'] = json_decode($linkData,true)['url'];
		}
		return $userProfileData;
    }

    public function mapBoardAndMarksWithGradeMapping(&$tenthData){
    	switch ($tenthData['board']) {
    		case 'CBSE':
                global $Reverse_CBSE_Grade_Mapping;
                $tenthData['educationMarksType'] = 'CGPA';
                $tenthData['educationMarks'] = $Reverse_CBSE_Grade_Mapping[$tenthData['educationMarks']];
                break;
            case 'ICSE':
                global $ICSE_Grade_Mapping;
                $Reverse_ICSE_Grade_Mapping = array_flip($ICSE_Grade_Mapping);
                $tenthData['educationMarksType'] = 'Percentage';
                $tenthData['educationMarks'] = $Reverse_ICSE_Grade_Mapping[$tenthData['educationMarks']];
                break;
            case 'IGCSE':
                global $Reverse_IGCSE_Grade_Mapping;
                $tenthData['educationMarksType'] = 'Grades';
                $tenthData['educationMarks'] = $Reverse_IGCSE_Grade_Mapping[$tenthData['educationMarks']];
                break;
            case 'IBMYP':
                global $IBMYP_Grade_Mapping;
                $Reverse_IBMYP_Grade_Mapping = array_flip($IBMYP_Grade_Mapping);
                $tenthData['educationMarksType'] = 'Marks';
                $tenthData['educationMarks'] = $Reverse_IBMYP_Grade_Mapping[$tenthData['educationMarks']];
                break;
            case 'NIOS':
                global $NIOS_Grade_Mapping;
                $Reverse_NIOS_Grade_Mapping = array_flip($NIOS_Grade_Mapping);
                $tenthData['educationMarksType'] = 'Percentage';
                $tenthData['educationMarks'] = $Reverse_NIOS_Grade_Mapping[$tenthData['educationMarks']];
                break;
    	}
    }

    public function initiateSolrCall($url){
        if($url != ''){
            $request_array = explode("?", $url); 
            $response = $this->searchServer->leadSearchCurl($request_array[0],$request_array[1]); 
            $response = unserialize($response);
            //return $response['response'];
            return $response['grouped']['userId']['groups'];
        }
        return false;
    }

    public function generateRequestURLForUserDetails($userIds){
		$request = $this->LDB_SEARCH_URL;
		$request .= '?q=*%3A*&wt=phps';
		$requestParams = array();
		$userIds = implode('%20', $userIds);
		
		$request .= "&fq=userId:(".$userIds.')';
		$request .= '&fq=DocType:user';
		$request .= '&group=true';
		$request .= '&group.field=userId';
        $request .= '&rows='.$this->MAX_PROFILES_COUNT;
        $request .= '&fl=displayname,userId,firstname,lastname,workex,user_image,displayData';
		return $request;
	}
    
    // public function getUserSchoolBoard($userIds){
    // 	$this->usermodel = $this->CI->load->model('user/usermodel');
    // 	$result = $this->usermodel->getSchoolBoardByUserId($userIds);
    // 	$boardData = array();
    // 	foreach ($result as $value) {
    // 		$boardData[$value['UserId']] = $value['board'];
    // 	}
    // 	return $boardData;
    // }

    private function getAllCourseLevels($level){
		if($level == 'all'){
			return array('Bachelors', 'Bachelors Certificate', 'Bachelors Diploma', 'Masters', 'Masters Certificate', 'Masters Diploma', 'PhD');
		}else if(strpos($level, 'Bachelors')!==false){
			return array('Bachelors', 'Bachelors Certificate', 'Bachelors Diploma');
		}else{
			return array('Masters', 'Masters Certificate', 'Masters Diploma', 'PhD');
		}
	}
}    
?>
