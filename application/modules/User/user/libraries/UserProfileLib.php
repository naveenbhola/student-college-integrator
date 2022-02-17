<?php

class UserProfileLib{
	
	private function _init(){
		$this->CI = & get_instance();
	}

	public function formatUserReportData($userProfileData){
		$userData = array();
		$tempUserData = array();

		foreach($userProfileData['userPersonalInfo'] as $row=>$val){

			/*Check if any section doesn't exist */
			if(empty($userProfileData['userWorkEx'][$row])){
				$userProfileData['userWorkEx'][$row] = array('workExCompletion'=>'0 %');
			}

			if(empty($userProfileData['userEducation'][$row])){
				$userProfileData['userEducation'][$row] = array('educationCompletion'=>'0 %');
			}

			if(empty($userProfileData['userPreference'][$row])){
				$userProfileData['userPreference'][$row] = array('eduPrefCompletion'=>'0 %');
			}
			/*Array merging */
			$tempUserData = $val + $userProfileData['userWorkEx'][$row];
			$tempUserData = $tempUserData + $userProfileData['userPreference'][$row];

			$userData[$row] = $tempUserData + $userProfileData['userEducation'][$row];
			$userData[$row]['TotalCompletion'] = (intval($userData[$row]['personalInfoCompletion']) + intval($userData[$row]['workExCompletion']) + intval($userData[$row]['educationCompletion']) + intval($userData[$row]['eduPrefCompletion']))/4 ." %";
		}
			
		$userData = $this->_formatDataForReport($userData);

		return $userData;
	}

	private function _formatDataForReport($userData){
		$returnData = array();
		$index = 1;

		$reportHeader = $this->_getReportVariables('reportHeader');
		
		$reportKeys = $this->_getReportVariables('reportKeys');
		
		$returnData[0] = $reportHeader;

		foreach ($userData as $userId => $userData) {
			foreach ($reportKeys as $key => $value) {
				$returnData[$index][] = isset($userData[$value])? $userData[$value]:'';
			}
			$index++;
		}
		
		return $returnData;
	}

	private function _sectionCompletionCalculator($userData, $sectionName){
		$weightage = $this->_getReportVariables('weightage');

		$sum = 0;
		foreach($weightage[$sectionName] as $key => $value){
			
			if(!empty($userData[$key]) && !is_numeric($userData[$key])){
				$sum += $value;
			}

			if(is_numeric($userData[$key]) && $userData[$key] != -1){
				$sum += $value;
			}
		}
		return $sum." %";
	}

	private function _profileCompletionCalculator($userData){

		/*Handling default DOB */
		if($userData['dateofbirth'] == '0000-00-00'){
			unset($userData['dateofbirth']);
		}

		$userData['personalInfoCompletion'] = $this->_sectionCompletionCalculator($userData, 'personalInfo');
		$userData['workExCompletion'] = $this->_sectionCompletionCalculator($userData, 'workEx');
		$userData['educationCompletion'] = $this->_sectionCompletionCalculator($userData, 'educationData');
		
		if($userData['ExtraFlag'] != 'studyabroad'){
			$userData['eduPrefCompletion'] = $this->_sectionCompletionCalculator($userData, 'educationalPrefNational');
		}else{
			$userData['abroadEducationalDetails'] = 'TRUE';
			$userData['eduPrefCompletion'] = $this->_sectionCompletionCalculator($userData, 'educationalPrefAbroad');
		}
		$userData['TotalCompletion'] = ($userData['personalInfoCompletion'] + $userData['workExCompletion'] + $userData['educationCompletion'] + $userData['eduPrefCompletion'])/4 ." %";
		return $userData;
	}

	public function fuseArray($array1, $array2){
		if(empty($array1) && empty($array2)){
			return array();
		}

		$array1 = $array1 + $array2;
		foreach ($array1 as $key => $value) {
			if(empty($array2[$key])){
				$array2[$key] = array();
			}
			$array1[$key] = $array1[$key] + $array2[$key];
		}
		return $array1;
	}

	private function _getReportVariables($variableName){

		switch($variableName){
			case 'weightage':
				return array(
				'personalInfo' => array(
						'Photo' => 12.5,
						'firstname' => 12.5,
						'Mobile' => 12.5,
						'Email' => 12.5,
						'Country' => 12.5,
						'AboutMe' => 12.5,
						'Bio' => 12.5,
						'dateofbirth' => 12.5
					),
				'workEx'=> array(
					'TotalWorkEx' => 50,
					'employer'=>12.5,
					'Designation'=>12.5,
					'department'=>12.5,
					'currentJob'=>12.5
					),
				'educationData'=> array(
					'xthSchool'=>5,
					'tenthBoard'=>5,
					'tenthMarks'=>5,
					'xthCompletionYear'=>5,
					'xiithSchool'=>4,
					'xiiSpecialization'=>4,
					'xiiBoard'=>4,
					'xiiMarks'=>4,
					'xiiCompletionYear'=>4,
					'bachelorsCollege'=>4,
					'bachelorsDegree'=>4,
					'bachelorsUniv'=>2,
					// 'bachelorsStream'=>4,
					'bachelorsStream'=>2,
					'bachelorsMarks'=>4,
					'graduationCompletionYear'=>4,
					'mastersCollege'=>4,
					'mastersDegree'=>4,
					'mastersUniv'=>2,
					// 'mastersStream'=>4,
					'mastersStream'=>2,
					'mastersMarks'=>4,
					'mastersCompletionYear'=>4,
					'phdCollege'=>4,
					'phdDegree'=>4,
					'phdUniv'=>4,
					'phdStream'=>4,
					// 'phdSpec'=>2,
					// 'phdMarks'=>4,
					'phdCompletionYear'=>4
					),
				'educationalPrefNational' => array(
						'Interest'=> 40,
						'DesiredCourse'=>40,
						'exam' => 10,
						'examMarks' => 10
					),
				'educationalPrefAbroad' => array(
						// 'Interest'=> 10,
						'DesiredCourse'=>10,
						'planToGo' => 10,
						'DesiredCountries'=>10,
						'abroadEducationalDetails' => 20, //(exam/valid passport=> always exist)
						'fundingSource' => 10,
						'Budget' => 10,
						'extracurricular' => 10,
						'preferences' => 10,
						'specialConsiderations' => 10
					)
				);
			break;

			case 'fieldLevelMapping':
				return array(
				
				'10' => array(
						'instituteName' => 'xthSchool',
						'board' => 'tenthBoard',
						'Marks' => 'tenthMarks',
						'CourseCompletionDate' => 'xthCompletionYear'
					),
				'12' => array(
						'instituteName' => 'xiithSchool',
						'Specialization' => 'xiiSpecialization',
						'board' => 'xiiBoard',
						'Marks' => 'xiiMarks',
						'CourseCompletionDate' => 'xiiCompletionYear'
					),
				'UG' => array(
						'instituteName' => 'bachelorsCollege',
						'name' => 'bachelorsDegree',
						'board' => 'bachelorsUniv',
						'subjects' => 'bachelorsStream',
						'Specialization' => 'bachelorsSpec',
						'Marks' => 'bachelorsMarks',
						'CourseCompletionDate' => 'graduationCompletionYear'
					),
				'PG' => array(
						'instituteName' => 'mastersCollege',
						'name' => 'mastersDegree',
						'board' => 'mastersUniv',
						'subjects' => 'mastersStream',
						'Specialization' => 'mastersSpec',
						'Marks' => 'mastersMarks',
						'CourseCompletionDate' => 'mastersCompletionYear'
					),
				'PHD' => array(
						'instituteName' => 'phdCollege',
						'name' => 'phdDegree',
						'board' => 'phdUniv',
						'subjects' => 'phdStream',
						'Specialization' => 'phdSpec',
						'Marks' => 'phdMarks',
						'CourseCompletionDate' => 'phdCompletionYear'
					)
				);
			break;

			case 'reportHeader':
				return array('UserId', 'Email', 'ISD Code', 'Mobile', 'First Name', 'Last Name', 'Country', 'City', 'Locality','Date of birth', 'About Me', 'Bio', 'Education Interest', 'Desired Course',
								'Country of Interest', 'Photo', 'Total Work Experience', 'Employer Name', 'Designation', 'Function/Department', 'Is this your current job?', 'X School Name', 'X Board', 'X Marks', 
								'X Completion Year', 'XII School Name', 'XII Stream', 'XII Board', 'XII Marks', 'XII CompletionYear',
								'Bachelors College Name', 'Bachelors Degree / Diploma Name', 'Bachelors University Name', 'Bachelors Specialization' , 'Bachelors Marks', 'Bachelors Course Completion Year', 
								'Masters College Name', 'Masters Degree / Diploma Name', 'Masters University Name', 'Masters Specialization' , 'Masters Marks', 'Masters Course Completion Year', 
								'PHD College Name', 'PHD Degree / Diploma Name', 'PHD University Name', 'PHD Specialization' , 'PHD Marks', 'PHD Course Completion Year',
								'Source of Funding', 'Budget of Studies	', 'Extra Curricular Activities', 'Special Considerations', 'Preferences', 'Planning to Start', 'student Email Id' ,
								'Personal Info Section Completion ', 'WorkEx Section Completion', 'Education Background section Completion', 'Education Preferences Completion', 'Total Profile Completion'
						);

			break;

			case 'reportKeys':
				return array('UserId', 'Email', 'ISDCode', 'Mobile', 'firstname', 'lastname', 'Country', 'City', 'Locality','dateofbirth', 'AboutMe', 'Bio', 'Interest', 'DesiredCourse',
								'DesiredCountries', 'Photo', 'TotalWorkEx', 'employer', 'designation', 'department', 'currentJob', 'xthSchool', 'tenthBoard', 'tenthMarks', 
								'xthCompletionYear', 'xiithSchool', 'xiiSpecialization', 'xiiBoard', 'xiiMarks', 'xiiCompletionYear',
								'bachelorsCollege', 'bachelorsDegree', 'bachelorsUniv', 'bachelorsStream' , 'bachelorsMarks', 'graduationCompletionYear', 
								'mastersCollege', 'mastersDegree', 'mastersUniv', 'mastersStream' , 'mastersMarks', 'mastersCompletionYear', 
								'phdCollege', 'phdDegree', 'phdUniv', 'phdStream' , 'phdMarks', 'phdCompletionYear',
								'fundingSource', 'Budget', 'extracurricular', 'specialConsiderations', 'preferences', 'planToGo', 'studentEmail' ,
								'personalInfoCompletion', 'workExCompletion', 'educationCompletion', 'eduPrefCompletion', 'TotalCompletion'
							);
			break;
		}

	}

	public function theChunkinator($arrayToBeDivided, $chunkSize, $privateMethodName){
		if(count($arrayToBeDivided) < 1){
			return array();
		}	

		if(empty($chunkSize)){
			$chunkSize = 500;
		}

		$tempDataHolder = array();
		$returnArray = array();

		$rounds = ceil( count($arrayToBeDivided)/$chunkSize );
		for($i=0; $i<$rounds; $i++){
			$tempDataHolder = array_slice($arrayToBeDivided, ($i*$chunkSize), $chunkSize, true);
			$returnArray += $this->{$privateMethodName}($tempDataHolder);
		}

		return $returnArray;
	}

	private function _fetchUserPersonalInfo($userIds = array()){
		if(count($userIds) < 1){
			return array();
		}

		$this->_init();
		$userprofilemodel = $this->CI->load->model('userProfile/userprofilemodeldesktop');
		return $userprofilemodel->getuserPersonalInfo($userIds);
	}
	
	public function getuserPersonalInfo($userIds = array()){
		$data = array();
		$data = $this->theChunkinator($userIds, 500, '_fetchUserPersonalInfo');
		foreach($data as $key=>$val){
			if($val['dateofbirth'] == '0000-00-00'){
				unset($data[$key]['dateofbirth']);
			}

			$data[$key]['personalInfoCompletion'] = $this->_sectionCompletionCalculator($data[$key], 'personalInfo');
		} 
		return $data;
	}

	private function _fetchUserWorkExData($userIds = array()){
		if(count($userIds) < 1){
			return array();
		}

		$this->_init();
		$userprofilemodel = $this->CI->load->model('userProfile/userprofilemodeldesktop');
		return $userprofilemodel->getUserWorkExData($userIds);
	}	

	public function getUserWorkExData($userIds = array()){
		$data = array();
		$data = $this->theChunkinator($userIds, 500, '_fetchUserWorkExData');
		foreach($data as $key=>$val){
			$data[$key]['workExCompletion'] = $this->_sectionCompletionCalculator($data[$key], 'workEx');
		} 
		
		return $data;
	}

	private function _fetchUserEducationData($userIds){
		$this->_init();

		$fieldLevelMapping = $this->_getReportVariables('fieldLevelMapping');
		
		$userprofilemodel = $this->CI->load->model('userProfile/userprofilemodeldesktop');
		return $userprofilemodel->getUserEducationData($userIds, $fieldLevelMapping);

	}

	public function getUserEducationData($userIds = array()){
		$data = array();
		$data = $this->theChunkinator($userIds, 500, '_fetchUserEducationData');

		foreach($data as $key=>$val){
			$data[$key]['educationCompletion'] = $this->_sectionCompletionCalculator($data[$key], 'educationData');
		} 
		
		return $data;
	}

	private function _fetchUserPreferenceData($userIds){ 
		$this->_init();

		$data  = array();

		$userprofilemodel = $this->CI->load->model('userProfile/userprofilemodeldesktop');
		$extraFlag = $userprofilemodel->getTUserPrefExtraFlag($userIds);
		if(empty($extraFlag['national'])){
			$extraFlag['national'] = array();
		}

		if(empty($extraFlag['studyabroad'])){
			$extraFlag['studyabroad'] = array();
		}
		$otherUsers = array_merge($extraFlag['national'], $extraFlag['studyabroad']);

		$data = $this->fuseArray($userprofilemodel->getUsersNationalCourseAndInterest($otherUsers), $userprofilemodel->getUsersTestPrepInterest($extraFlag['testprep']));
		$data = $this->fuseArray($data, $userprofilemodel->getUserExamsDetails($userIds));
		$data = $this->fuseArray($data, $userprofilemodel->getAbroadUserDetails($extraFlag['studyabroad']));
		
		return $this->fuseArray($data, $userprofilemodel->getUserDesiredCountries($userIds));
	}

	public function getUserPreferenceData($userIds = array()){
		$data = array();
		$data = $this->theChunkinator($userIds, 500, '_fetchUserPreferenceData');
	
		foreach($data as $key=>$userData){
			if($userData['ExtraFlag'] != 'studyabroad'){
				$data[$key]['eduPrefCompletion'] = $this->_sectionCompletionCalculator($userData, 'educationalPrefNational');
			}else{
				$userData['abroadEducationalDetails'] = 'TRUE';
				$data[$key]['eduPrefCompletion'] = $this->_sectionCompletionCalculator($userData, 'educationalPrefAbroad');
			}
		} 
		
		return $data;
	}


}