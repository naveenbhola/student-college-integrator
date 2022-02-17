<?php
include_once('UserSearchCriteriaEvaluator.php');

class ProfileBased implements UserSearchCriteriaEvaluator
{

	private $CI;
	
	function __construct()
	{
		$this->CI = & get_instance();
	}
	
	public function evaluate($criteriaSet)
	{
		$userSet = array();
		$this->inputArray = $criteriaSet;
		$users = $this->formSubmit();

		if($criteriaSet['countFlag']){
			return $users;
		}

		foreach($users as $userId){
			$userSet[$userId] = TRUE;
		}
		return $userSet;
	}

	private function getSourcesOfFundingForStudyAbroad(&$postArray) {
		$sources = array('UserFundsOwn' => 'Own', 'UserFundsBank' => 'Bank Loan', 'UserFundsNone' => 'Others');
		foreach ($sources as $source => $sourceLabel) {
			if (isset($this->inputArray[$source]) && !empty($this->inputArray[$source])) {
				$postArray[$source] = $this->inputArray[$source];
			}
		}
	}
	
	private function getDesiredCoursesForStudyAbroad(&$postArray) {
		$categoryIds = array();
		global $studyAbroadAllDesiredCourses;
		$popularCourseIds = $studyAbroadAllDesiredCourses;
		//$popularCourseIds = array('1508','1509','1510');
		if (is_array($this->inputArray['search_category_id'])) {
			foreach ($this->inputArray['search_category_id'] as $temp=>$id) {
				$categoryIds[$temp] = $id;
				if(in_array($id,$popularCourseIds)){
					$postArray['DesiredCourseId'][] = $id;
				}
			}
		}
		
		$desiredCourseLevels = $this->inputArray['desiredCourseLevel'];
		if (empty($desiredCourseLevels)) {
			$desiredCourseLevels = array('UG', 'PG', 'PhD');
		}
		
		$this->CI->load->library('LDB_Client');
		$ldbClientObj = new LDB_Client();
		
		foreach ($desiredCourseLevels as $desiredCourseLevel) {
			if (is_array($categoryIds)) {
				foreach ($categoryIds as $index => $categoryId) {
					if($categoryId !== '0' && !in_array($categoryId,$popularCourseIds)){
						$desiredCourseDetails = array_pop(json_decode($ldbClientObj->getCourseForCriteria($appId, $categoryId, 'abroad', $desiredCourseLevel), true));
						$postArray['DesiredCourseId'][] = $desiredCourseDetails['SpecializationId'];
					}
				}
			}
		}
	}

	private function getExamDataForStudyAbroad(&$postArray) {
		$exams = array('TOEFL', 'IELTS', 'GMAT', 'GRE', 'SAT', 'PTE', 'CAEL', 'MELAB', 'CAE');
		foreach ($exams as $examName) {
			if (isset($this->inputArray['exam_' . $examName])) {
				$postArray['ExamScore'][$examName]['min'] = (is_numeric($this->inputArray[$examName . '_min_score'])) ? $this->inputArray[$examName . '_min_score'] : "";
				$postArray['ExamScore'][$examName]['max'] = (is_numeric($this->inputArray[$examName . '_max_score'])) ? $this->inputArray[$examName . '_max_score'] : "";
			}
		}
	}

	private function remove_array_empty_values($array, $remove_null_number = false) {
		$new_array = array();
		
		$null_exceptions = array();
		
		foreach ($array as $key => $value) {
			$value = trim($value);
			
			if ($remove_null_number) {
				$null_exceptions[] = '0';
			}
			
			if (!in_array($value, $null_exceptions) && $value != "") {
				$new_array[] = $value;
			}
		}
		return $new_array;
	}

	public function formSubmit() {
		
		if ($this->inputArray['country'] == 'abroad') {
			
			$response = $this->getUsersDataForAbroad();

		} else {		

			$response = $this->getUsersDataForNational();

		}
		
		return $response;
	}

	private function getUsersDataForNational() {

		$postArray = array(); $finalResponse = array();
		$targetDB = $this->inputArray['targetDB'];

		if($targetDB == 'custom') {

			$criteriaNos = $this->inputArray['criteriaNos'];
			$allFieldsData = json_decode($this->inputArray['allFieldsData']);
			$allsubStreamSpecializationMapping = json_decode($this->inputArray['subStreamSpecializationMapping']);
			$allungroupedSpecializations = json_decode($this->inputArray['ungroupedSpecializations']);

			if(!empty($criteriaNos)) {

				$count = 0;$allCriteriasCount = array();
				foreach($criteriaNos as $criteriaNo) {

					$postArray = array(); $fieldsData = array();$substream = '';$specialization = '';$courses = '';$mode = '';$city = '';$locality = '';$responseCity = '';

					$stream = $this->inputArray['stream_'.$criteriaNo];
					if(!empty($stream) && $stream > 0) {
						$postArray['stream'] = $stream;
					}

					if($allFieldsData->$criteriaNo != '') {
						$fieldsData = $allFieldsData->$criteriaNo;
						$courses = $fieldsData->courses;
						$mode = $fieldsData->mode;
						$city = $fieldsData->city;
						$locality = $fieldsData->locality;
						$responseCity = $fieldsData->responseCity;
					}		

					$subStreamSpecializationMapping = (array)$allsubStreamSpecializationMapping->$criteriaNo;
					$ungroupedSpecializations = $allungroupedSpecializations->$criteriaNo;
					
					if(is_array($subStreamSpecializationMapping) && count($subStreamSpecializationMapping) > 0) {
						$postArray['subStreamSpecializationMapping'] = $subStreamSpecializationMapping;
					}

					if(is_array($ungroupedSpecializations) && count($ungroupedSpecializations) > 0) {
						$postArray['ungroupedSpecializations'] = $ungroupedSpecializations;						
					}

					if(is_array($courses) && count($courses) > 0) {
						$postArray['courseId'] = array_unique($courses);
					}

					if(is_array($mode) && count($mode) > 0) {
						$attributeIds = array();

						$this->CI->load->library('listingBase/BaseAttributeLibrary');
        				$baseAttributeLibrary = new BaseAttributeLibrary();
        				$attributeIds = $baseAttributeLibrary->getAttributeIdByValueId($mode);

						$postArray['attributeIds'] = $attributeIds;
						$postArray['attributeValues'] = $mode;
					}

					$cityLocalityArray = array();
					if(is_array($city) && count($city) > 0) {
						$cityLocalityArray['CurrentCities'] = $city;
					}

					if($locality != '') {
						$cityLocalityArray['currentLocalities'] = $locality;
					}

					if(is_array($responseCity) && count($responseCity) > 0) {
						$postArray['responseCities'] = $responseCity;
					}

					$this->CI->load->library('userSearchCriteria/UserSearchCriteria');
					$searchCriteria = new UserSearchCriteria();
					$formattedCityLocalityArray = $searchCriteria->formatCityLocalities($cityLocalityArray);

					$postArray['CurrentCities'] = $formattedCityLocalityArray['CurrentCities'];
					$postArray['currentLocalities'] = $formattedCityLocalityArray['currentLocalities'];

					$exams = $this->inputArray['exams_'.$criteriaNo];
					if(is_array($exams) && count($exams) > 0) {
						// foreach($exams as $examId) {								
						// 	$examArray[$examId] = array();								
						// }
						$postArray['exams'] = $exams;
					}
													
					$minExp = $this->inputArray['minExp_'.$criteriaNo];
					if ($minExp != '' && is_numeric($minExp)) {
						$postArray['MinExp'] = $minExp;
					}
				
					$maxExp = $this->inputArray['maxExp_'.$criteriaNo];
					if ($maxExp != '' && is_numeric($maxExp)) {
						$postArray['MaxExp'] = $maxExp;
					}

					$searchArray = $this->setCommonFields($postArray);
					$response = $this->runSearch($searchArray);

					if($response != '' && is_int($response['totalRows']) && $response['totalRows'] >= 0) {
						if($this->inputArray['countFlag']) {
							if($this->inputArray['needEachCriteriaCount'] === true) {
								$allCriteriasCount[$criteriaNo] = $response['totalRows'];
							} else {
								$count += $response['totalRows'];
							}			
						} else {
							$finalResponse = array_merge($finalResponse, $response['userIds']);
						}			
					}
				}
			}
		} else {
			
			$searchArray = $this->setCommonFields($postArray);
			$response = $this->runSearch($searchArray);
			if($this->inputArray['countFlag']) {
				$count = $response['totalRows'];
			} else {
				$finalResponse = $response['userIds'];
			}

		}

		unset($postArray);

		if($this->inputArray['countFlag']) {   //returning only count based on check

			if($this->inputArray['needEachCriteriaCount'] == 'true') {
				return $allCriteriasCount;
			} else {
				return $count;				
			}

		} else {
	        unset($response);

	 		return $finalResponse;

 		}
	}

	private function getUsersDataForAbroad() {

		$postArray = array();
		$postArray['ExtraFlag'] = 'studyabroad';
		$this->getDesiredCoursesForStudyAbroad($postArray);
		$this->getExamDataForStudyAbroad($postArray);
		
		if (is_array($this->inputArray['course_specialization'])) {
			foreach($this->inputArray['course_specialization'] as $index=>$specializationId){
				if($specializationId !== '' && $specializationId !== '-1')
					$postArray['abroadSpecializations'][] = $specializationId;
			}
		}

		if (isset($this->inputArray['passport'])) {
			$postArray['passport'] = $this->inputArray['passport'];
		}

		$postArray['countFlag'] = $this->inputArray['countFlag'];

		if ($this->inputArray['gradStartYear'] != "") {
			$postArray['GraduationCompletedFrom'] = $this->inputArray['gradStartYear'] . "-01-01 00:00:00";
		}
		
		if ($this->inputArray['gradEndYear'] != "") {
			$result = strtotime("{$this->inputArray['gradEndYear']}-12-01");
			$result = strtotime('-1 second', strtotime('+1 month', $result));
			$finaldate = $result . " 23:59:59";
			$finaldate = date("Y-m-d H:i:s", $finaldate);
			$postArray['GraduationCompletedTo'] = $finaldate;
		}
		
		if ($this->inputArray['XIIStartYear'] != "") {
			$postArray['XIICompletedFrom'] = $this->inputArray['XIIStartYear'] . "-01-01 00:00:00";
		}
		
		if ($this->inputArray['XIIEndYear'] != "") {
			$result = strtotime("{$this->inputArray['XIIEndYear']}-12-01");
			$result = strtotime('-1 second', strtotime('+1 month', $result));
			$finaldate = $result . " 23:59:59";
			$finaldate = date("Y-m-d H:i:s", $finaldate);
			$postArray['XIICompletedTo'] = $finaldate;
		}
		
		if (isset($this->inputArray['CurLocArr'])) {
			//$postArray['CurrentLocation'] = $this->remove_array_empty_values($this->inputArray['CurLocArr']);
			$this->CI->load->builder('LocationBuilder','location');
			$locationBuilder = new \LocationBuilder;
			$locationRepository = $locationBuilder->getLocationRepository();
			
			$cityObjs = $locationRepository->findMultipleCities($this->inputArray['CurLocArr']);
			foreach($cityObjs as $cityId => $cityObj) {
				if($cityObj->isVirtualCity()) {
					$citiesByVitualCity = $locationRepository->getCitiesByVirtualCity($cityId);
					foreach($citiesByVitualCity as $city) {
						$this->inputArray['CurLocArr'][] = $city->getId();
					}
				}
			}
			unset($cityObjs);
			unset($locationRepository);

			$postArray['CurrentLocation'] = array_unique($this->inputArray['CurLocArr']);
			if (isset($this->inputArray['hiddenCurrentCity'])) {
				$postArray['currentLocation'] = implode(', ', $this->inputArray['hiddenCurrentCity']);
			}
		}
		
		if (isset($this->inputArray['prefLocClause'])) {
			if ($this->inputArray['prefLocClause'] == 'or') {
				$postArray['LocationAndOr'] = 1;
			}
		}
		
		if (isset($this->inputArray['prefLocArr'])) {
			$postArray['PreferredLocation'] = $this->remove_array_empty_values($this->inputArray['prefLocArr']);
		}
		
		if (isset($this->inputArray['currLocArr'])) {
			$postArray['CurrentLocality'] = $this->remove_array_empty_values($this->inputArray['currLocArr']);
			
			$prefLocalityNumBlocks = intval($this->inputArray['prefLocalityNumBlocks']);
			if($prefLocalityNumBlocks) {
				$postArray['Locality'] = array();
				for($i=1;$i<=$prefLocalityNumBlocks;$i++) {
					$postArray['Locality'] = array_merge($postArray['Locality'],$this->remove_array_empty_values($this->inputArray['LocalityArr_'.$i]));
				}
			}
		}
		
		$prefLocalityNumBlocks = $this->inputArray['prefLocalityNumBlocks'];
		
		$loc=array();
		for($i=1;$i<=$prefLocalityNumBlocks;$i++){
			$loc1[$i-1] = $this->inputArray['LocalityArr_'.$i];
		}
		
		for($i=1;$i<=$prefLocalityNumBlocks;$i++){
			$curr1[$i-1] = $this->inputArray['currLocArr_'.$i];
		}
		$postArray['currentLocalities'] = $loc1;
		
		$current_City = array();
		$i=0;
		foreach($curr1 as $k=>$v){
			foreach($v as $curCityCode=>$currCities){
				$current_City[$i] = json_decode(base64_decode($currCities));
				$current_City[$i] = $current_City[$i]->cityId;
				$i++;
			}
		}
		
		$localities="";
		
		$virtualCityArray = array();
		$checkVirtualCity = array();
		foreach($current_City as $key=>$val){
			if($val){
				$checkVirtualCity[] = $val;
			}
		}
		if(!empty($checkVirtualCity)){
		    
			$this->CI->load->builder('LocationBuilder','location');
			$locationBuilder = new \LocationBuilder;
			$locationRepository = $locationBuilder->getLocationRepository();
			
			$cityObjs = $locationRepository->findMultipleCities($checkVirtualCity);
			foreach($cityObjs as $cityId => $cityObj) {
				if($cityObj->isVirtualCity()) {
					$citiesByVitualCity = $locationRepository->getCitiesByVirtualCity($cityId);
					foreach($citiesByVitualCity as $city) {
						$virtualCityArray[] = $city->getId();
					}
				}
			}
		}
		
		unset($cityObjs);
		unset($locationRepository);
		
		$current_City = array_merge($current_City, $virtualCityArray);
		
		$checkArray = array_filter($current_City);
		if(!empty($checkArray)){
			$postArray['CurrentCities'] = $current_City;
		}
		
		if (isset($this->inputArray['timefilter']['type']) && $this->inputArray['timefilter']['type'] != "all") {
			if ($this->inputArray['timefilter']['type'] == "interval" && !empty($this->inputArray['timefilter']['interval']) && is_numeric($this->inputArray['timefilter']['interval'])) {
				$interval = intval($this->inputArray['timefilter']['interval']);
				$fromtime = time() - ($interval * 24 * 60 * 60);
				$postArray['NewDateFilter'] = date('Y-m-d', $fromtime);
				$postArray['DateFilterFrom'] = date('Y-m-d', $fromtime);
				$postArray['DateFilterTo'] = date('Y-m-d').' 23:59:59';

			}
			else if ($this->inputArray['timefilter']['type'] == "range") {
				if (!empty($this->inputArray['timefilter']['from'])) {
					list($day, $month, $year) = split('/', $this->inputArray['timefilter']['from']);
					$postArray['DateFilterFrom'] = $year.'-'.$month.'-'.$day;
				}
				if (!empty($this->inputArray['timefilter']['to'])) {
					list($day, $month, $year) = split('/', $this->inputArray['timefilter']['to']);
					$postArray['DateFilterTo'] = $year.'-'.$month.'-'.$day." 23:59:59";
				}
			}
		}
		
		//$postArray['Unsubscribe'] = '0'; //commented, now email pref-5 will be used
		$postArray['mobileVerified']         = '0';
		$postArray['includeActiveUsers']     = $this->inputArray['includeActiveUsers'];
		$postArray['includeCounselingUsers'] = $this->inputArray['includeCounselingUsers'];
		$postArray['totalMailsToBeSent']     = $this->inputArray['totalMailsToBeSent'];
		
		$activateSolrSearch = true;
		if($activateSolrSearch){
			$resultResponse = modules::run('enterprise/shikshaDB/searchLDBSolr',$postArray, 0, -1, '',TRUE);

		} else{
			$postArray['isMMM'] = 'yes';
			$resultResponse = modules::run('LDB/LDB_Server/searchLDB',$postArray, '', 0, '', '');
		}
		

		if($this->inputArray['countFlag']){
			return $resultResponse['totalRows'];				//returning only count based on check
		}

		unset($postArray);

        $finalResult = array();

        foreach ($resultResponse['userIds'] as $key) {            
                $finalResult[] = $key;
        }
        
        unset($resultResponse);

        $resultResponse['userIds'] = $finalResult;
        //Part : ends	
 
 		return $resultResponse['userIds'];
	}

	private function setCommonFields($postArray) {

		$timeRange = $this->inputArray['timeRange'];
		$targetDB = $this->inputArray['targetDB'];
		if (isset($timeRange)) {

			if($timeRange == "all" && $targetDB == 'all') {

				$postArray['DateFilterFrom'] = date('Y-m-d',strtotime('-2 years')).' 00:00:00';				
				$postArray['DateFilterTo'] = date('Y-m-d').' 23:59:59';

			} else if ($timeRange == "interval") {

				$timeRangeIntervalDays = $this->inputArray['timeRangeIntervalDays'];
 				if((!empty($timeRangeIntervalDays)) && (is_numeric($this->inputArray['timeRangeIntervalDays']))) {
				
					$timeRangeIntervalDays = intval($timeRangeIntervalDays);
					$fromtime = time() - ($timeRangeIntervalDays * 24 * 60 * 60);
					$postArray['NewDateFilter'] = date('Y-m-d', $fromtime);
					$postArray['DateFilterFrom'] = date('Y-m-d', $fromtime);
					$postArray['DateFilterTo'] = date('Y-m-d').' 23:59:59';

 				}

			} else if ($timeRange == "duration") {

				$timeRangeDurationFrom= $this->inputArray['timeRangeDurationFrom'];
				if (!empty($timeRangeDurationFrom)) {
					list($day, $month, $year) = split('/', $timeRangeDurationFrom);
					$postArray['DateFilterFrom'] = $year.'-'.$month.'-'.$day;
				}
				$timeRangeDurationTo= $this->inputArray['timeRangeDurationTo'];
				if (!empty($timeRangeDurationTo)) {
					list($day, $month, $year) = split('/', $timeRangeDurationTo);
					$postArray['DateFilterTo'] = $year.'-'.$month.'-'.$day." 23:59:59";
				} else {
					$postArray['DateFilterTo'] = date('Y-m-d').' 23:59:59';
				}

			}
		}
		
		//$postArray['Unsubscribe'] = '0'; //commented, now email pref-5 will be used
		$postArray['mobileVerified'] = '0';
		$postArray['includeActiveUsers'] = $this->inputArray['includeActiveUsers'];
		$postArray['totalMailsToBeSent'] = $this->inputArray['totalMailsToBeSent'];
		$postArray['countFlag'] = $this->inputArray['countFlag'];
		$postArray['ExtraFlag'] = $this->inputArray['ExtraFlag'];
		$postArray['responseField'] = $this->inputArray['responseField'];

		return $postArray;

	}

	private function runSearch($postArray) {

		$resultResponse = modules::run('enterprise/enterpriseSearch/searchLDBSolr',$postArray, 0, -1, '',TRUE);		
		return $resultResponse;

	}

}
