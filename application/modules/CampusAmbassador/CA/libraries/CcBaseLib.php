<?php

/**
 * 
 * @author Bharat
 *
 */
class CcBaseLib
{

		function __construct()
		{
				$this->CI = & get_instance();
				$this->CI->load->model('CA/campusconnectmodel');
				$this->campusconnect = new CampusConnectModel();
		}		

		function getCCProgramMapping($programIds = array(), $getAllData = 0){
			if(!is_array($programIds)){
				$programIds = array($programIds);
			}
			if($getAllData == 1){
			   $data = $this->campusconnect->getCCProgramMapping('',$getAllData);
			   $filterData = $this->getProgramDataOnProgramIdIndex($data);
		       return $filterData;
			}
			if(!empty($programIds) && is_array($programIds)){
				$filterData = array();
				$data = $this->campusconnect->getCCProgramMapping($programIds);
      			$filterData = $this->getProgramDataOnProgramIdIndex($data);
		    	return $filterData;
			}
	    	return $filterData;
		}
		
		function getInstAndCourseOfProgram($programId) {
			$data = $this->campusconnect->getInstAndCrsIdForProgramId($programId);
	    	foreach ($data as $key => $value) {
	    		$result[$value['instituteId']]['CA_Count']=$value['CA_Count'];
	    		$result[$value['instituteId']]['courseIdStr']=$value['courseIdStr'];
	    	}
	    	return $result;	
	    }

	function getInstituteDetails($instArr){
        $this->CI->load->builder("nationalInstitute/InstituteBuilder");
    	$instituteBuilder = new InstituteBuilder();
    	$instituteRepo = $instituteBuilder->getInstituteRepository();	    	
	    if(!empty($instArr) || is_array($instArr)){
	    	$res = $instituteRepo->findMultiple($instArr,array('basic','media'));
	        foreach ($res as $key => $value) {
	        	$result[$key]['name'] = $res[$key]->getName(); 
	        	$headerImage = $res[$key]->getHeaderImage();
	        	$headerImageUrl = "";
	        	if($headerImage)
	            	$headerImageUrl = $headerImage->getUrl();
	        	$imageVariant = "";
	        	if($headerImageUrl)
	            	$imageVariant = getImageVariant($headerImageUrl, 7);
	        	$result[$key]['imgUrl'] = $imageVariant; 
				$result[$key]['instUrl'] = $res[$key]->getUrl(); 
				if($res[$key]->getMainLocation()){
					$locality = $res[$key]->getMainLocation()->getLocalityName();
					$cityName = $res[$key]->getMainLocation()->getCityName();                            
				}
				$result[$key]['locality'] = $locality;
	            $result[$key]['cityName'] = $cityName;
	            $result[$key]['listingType'] = $res[$key]->getType();
	        }
	    }	  
	    return $result;
	}

	public function processInstRankData($instRankBySource){
		$currentYear = date('Y');
		$filterYearArray = array();
		$filterYearArray[] = $currentYear;
		for ($i=1; $i<=5  ; $i++) {
			$filterYearArray[] = $currentYear + $i;
			$filterYearArray[] = $currentYear - $i;
		}
		foreach ($instRankBySource as $instituteId => $instRankDetails){
			if(count($instRankDetails)> 2){
				arsort($instRankDetails);
				$instRankDetails = array_slice($instRankDetails,0,2);
			}
			foreach ($instRankDetails as $source => $rank) {
				$rankTemp = $rank;
				unset($instRankDetails[$source]);
				$instRankDetails[$this->checkForYearInSource($source,$filterYearArray)] = $rank;
			}
			$instRankBySource[$instituteId] = $instRankDetails;
		}
		return $instRankBySource;
	}

	public function checkForYearInSource($source,$filterYearArray){
		foreach ($filterYearArray as $year){
			if (strpos($source, (string)$year) !== false) {
				$source = str_replace($year, '', $source);
				$source = rtrim($source,' ');
				break;
			}
		}
		return $source;
	}

	public function getInstituteDataForMostViewedAndTrandingWidget($programId,$widgetType,$noOfTopInst,$rankingDataRequired = false){
		$instituteDetails = $this->getInstAndCourseOfProgram($programId);
		if(!$instituteDetails){
			return array();
		}
		$courseIdStr = '';
		foreach ($instituteDetails as $instituteId => $instituteData) {
			$courseIdStr = $instituteData['courseIdStr'].','.$courseIdStr;
		}
		$courseIdStr = rtrim($courseIdStr,',');
		$instituteIds = array_keys($instituteDetails);
		$courseIds = explode(',',$courseIdStr);
		$instituteIdsArray = $this->campusconnect->getInstIdsMappedToQues($widgetType,$courseIds,$noOfTopInst,$programId);
		if(!$instituteIdsArray){
			return array();
		}
		$finalInstitueIds = array();
		$finalInstitueDetails = array();
		foreach($instituteIdsArray as $instituteIdArray){
			$finalInstitueIds[] = $instituteIdArray['instituteId'];
			$finalInstitueDetails[$instituteIdArray['instituteId']] =$instituteDetails[$instituteIdArray['instituteId']];
		}
		$finalInstitueIds = array_unique($finalInstitueIds);
		if($rankingDataRequired == true){
			$this->rankingCommonLib = $this->CI->load->library('rankingV2/RankingCommonLibv2');
			$programMappingArray = $this->getCCProgramMapping($programId);
			$programMappingArray = $programMappingArray[$programId];
			$programMapping[$programMappingArray['entityType']] = $programMappingArray['entityId'];
			$instRankBySource = $this->rankingCommonLib->getInstituteRankBySource($finalInstitueIds, $noOfTopInst, $programMapping);
			$instRankBySource = $this->processInstRankData($instRankBySource);
		}
		$courseIds = '';
		$institutebasicDetails = $this->getInstituteDetails($finalInstitueIds);
		foreach ($finalInstitueDetails as $instituteId => $instituteDetails) {
			if($rankingDataRequired == true){
				$temp = $instituteDetails;
				$finalInstitueDetails[$instituteId] = $institutebasicDetails[$instituteId];
				$finalInstitueDetails[$instituteId]['CA_Count'] = $temp['CA_Count'];
				if($instRankBySource[$instituteId]){
					$finalInstitueDetails[$instituteId]['rank'] = $instRankBySource[$instituteId];	
				}
			}else{
				$finalInstitueDetails[$instituteId] = $institutebasicDetails[$instituteId];
			}
			$courseIds = $instituteDetails['courseIdStr'].','.$courseIds;
		}
		$courseIds = rtrim($courseIds,',');
		$courseIds = ltrim($courseIds,',');
		return array(
			'finalInstitueDetails' => $finalInstitueDetails,
			'instituteIds' => array_keys($finalInstitueDetails),
			'courseIdsStr' => $courseIds
		);
	}

	function prepareFinalData($instituteData,$rankData){
		foreach ($instituteData as $instituteId => $value) {
			if(array_key_exists($instituteId,$rankData)){
				$instituteData[$instituteId]['rank'] = $rankData[$instituteId]['rank'];
				$instituteData[$instituteId]['CA_Count'] = $rankData[$instituteId]['CA_Count'];
			}
		}
		return $instituteData;
	}

	// return institute having rank only
	function getAllPaidInstituteId($instituteIds, $noOfTopInst){
		$caPaidInstitutes = $this->campusconnect->getAllPaidInstitutesHavingCA($instituteIds);
		foreach($caPaidInstitutes as $capaidInstitute){
			$instituteIdArr[] = $capaidInstitute['instituteId'];
		}

		$this->rankingCommonLib = $this->CI->load->library('rankingV2/RankingCommonLibv2');
		$instRankBySource = $this->rankingCommonLib->getInstituteRankBySource($instituteIdArr, $noOfTopInst);
		return $instRankBySource;
	}

	/////
	// Desc :  Migrate cr course from old to new course if course is deleted
	// Param : $oldCourse, $newCourse, $dbHandle
	// Return Type : string
	// @uther : akhter
	/////
	function updateCRCourse($oldCourse, $newCourse, $newInstituteId, $dbHandle)
	{
		if(empty($oldCourse)){
			return 'courseId can\'t be blank, not able to Migrate / Delete CR Mapping.';
		}
		$this->CI->load->library('CA/CADiscussionHelper');
		$this->caDiscussionHelper =  new CADiscussionHelper();
		$result = $this->caDiscussionHelper->checkIfCampusRepExistsForCourse(array($oldCourse));
		if(!($result[$oldCourse]) == 'true'){
			return 'CR is not exist for this course : '.$oldCourse;
		}

		if(empty($newCourse)){
			// when course is deleted.
			//update status as deleted for these course ids in (CA_OtherCourseDetails,CA_MainCourseMappingTable)
			$CRMigrationResponse = 0;
			$ids = $this->campusconnect->getIdsFromMainCRTable($oldCourse,$dbHandle);
			if(is_array($ids) && count($ids) > 0){
				$CRMigrationResponse = 1;
				$cadIds = array();
				foreach ($ids as $key => $value) {
					$caIds[] = $value['caId'];
				}
				$response = $this->campusconnect->updateStatusForDeletedCourseInProfileTable($caIds,$dbHandle);
				if($response !=1){
					return 'CR Mapping is not migrated / deleted.';
					//return 'Query is not executed successfully for CR Deletion';
				}
				$ids = $this->campusconnect->getOtherCrIds($oldCourse,$dbHandle);
				if(is_array($ids) && count($ids) > 0){
					$otherCRIds = array();
					foreach ($ids as $key => $value) {
						$otherCRIds[] = $value['id'];
					}
					$response = $this->campusconnect->updateStatusForDeletedCourseInOtherForIds($otherCRIds,$dbHandle);
					if($response !=1){
						return 'CR Mapping is not migrated / deleted.';
						//return 'Query is not executed successfully for CR Deletion';
					}
				}
				$response =  $this->campusconnect->updateStatusForDeletedCourseInMain($oldCourse,$dbHandle);
				if($response !=1){
					return 'CR Mapping is not migrated / deleted.';
					//return 'Query is not executed successfully for CR Deletion';
				}
			}
			if($CRMigrationResponse == 0){
				// check in other table.
				$result = $this->campusconnect->checkIfCRExistInOtherTable($oldCourse,$dbHandle);
				if($result == true){
					$response = $this->campusconnect->updateStatusForDeletedCourseInOther($oldCourse,$dbHandle);
					if($response !=1){
						return 'CR Mapping is not migrated / deleted.';
						//return 'Query is not executed successfully for CR Deletion';
					}
				}else{
					return 'CR Mapping not applicable.';
				}
			}
			return 'CR Mapping is migrated / deleted.';
		}

		// when course is migrated.
		// load the course builder
	    $this->CI->load->builder("nationalCourse/CourseBuilder");
	    $courseBuilder = new CourseBuilder();
	    $courseRepo = $courseBuilder->getCourseRepository();
	    $courseObj = $courseRepo->findMultiple(array($newCourse),array('location'));
	    if(empty($courseObj[$newCourse])){
	    	return 'course object is blank for courseId = '.$newCourse.' for CR migration.';
	    }

	    if(!empty($courseObj[$newCourse])){
	    	//get newcourse Institute Id
	    	// step 1-get all location of new course
	    	$newCourseData[$newCourse]['locationList']     = array_keys($courseObj[$newCourse]->getLocations());
	    	$courseTypeObj                                 = $courseObj[$newCourse]->getCourseTypeInformation();
	    	// step 2- get hierarchy and basecourse for entery level
	    	if(!empty($courseTypeObj['entry_course'])){
	    		$newCourseData[$newCourse]['entery']['baseCourseId'] = $courseTypeObj['entry_course']->getBaseCourse();
    			$newCourseData[$newCourse]['entery']['hierarchies']  = $courseTypeObj['entry_course']->getHierarchies();	
	    	}
	    	// step 3- get hierarchy and basecourse for exit level
    		if(!empty($courseTypeObj['exit_course'])){
    			$newCourseData[$newCourse]['exit']['baseCourseId']   = $courseTypeObj['exit_course']->getBaseCourse();
    			$newCourseData[$newCourse]['exit']['hierarchies']    = $courseTypeObj['exit_course']->getHierarchies();	
    		}
	    }

		//step 4- find old course in CA_MainCourseMappingTable
		$CRMigrationResponse = 0;
		$resultData = $this->campusconnect->findCourseInMain($oldCourse, true, $dbHandle);
		if(!empty($resultData)){
			if(empty($newInstituteId)){
				return 'instituteId can\'t be blank, not able to Migrate / Delete CR Mapping.';
			}
			$updateIdStr = $this->getIdForUpdateCourse($resultData, $newCourseData, $newCourse);
			if($updateIdStr){
				$CRMigrationResponse = 1;
			}
			$response = $this->campusconnect->updateCRCourseInMain($updateIdStr, $newCourse, $dbHandle, $newInstituteId);
			if($response !=1){
				return 'CR Mapping is not migrated / deleted.';
				//return 'Query is not executed successfully for CR Deletion';
			}
		}
		
		//step 5- find old course in CA_OtherCourseDetails if not found in main table of CR
		$mappinCA = $this->campusconnect->findCourseInOther($oldCourse, $dbHandle); // get id for CA_MainCourseMappingTable
		$mappinCAId = $this->getIdFromOther($mappinCA);
		$resultData = $this->campusconnect->findCourseInMain(implode(',',array_keys($mappinCAId)), false, $dbHandle); // get main course from other course
		if(!empty($resultData)){
			$updateIdStr = $this->getIdForUpdateCourse($resultData, $newCourseData, $newCourse);
			$updateIdArr = explode(',', $updateIdStr);
			foreach ($updateIdArr as $key => $value) {
				if($mappinCAId[$value] !=''){
					$idArr[] = 	$mappinCAId[$value];
				}
			}
			$updateIdStr = implode(',',$idArr);
			//step 10- update old course in main course table / other course table of CR		
			if($updateIdStr){
				$CRMigrationResponse = 1;
			}
			$response = $this->campusconnect->updateCRCourseInOther($updateIdStr, $newCourse, $dbHandle);
			if($response !=1){
				return 'CR Mapping is not migrated / deleted.';
				//return 'Query is not executed successfully for CR Deletion';
			}
		}

		if($updateIdStr ==1){
			return 'CR Mapping is migrated / deleted.';	
		}else{
			return 'CR Mapping not applicable.';
		}
		
	}

	function getIdFromOther($value){
		$data = array();
		if(count($value)>0){
			foreach ($value as $key => $value) {
				$data[$value['mappingCAId']] = $value['otherId'];
			}
		}
		return $data;	
	}

	function getIdForUpdateCourse($resultData, $newCourseData, $newCourse){
		if(count($resultData)>0){
			//step 6- find mapping from CR programId
			$programId = $resultData[0]['programId'];

			$programMappingArray = $this->getCCProgramMapping($programId);
			$programMappingArray = $programMappingArray[$programId];
			$programMapping[$programMappingArray['entityType']] = $programMappingArray['entityId'];

			if($programMapping['stream_id']){
				$oldCourseData['hierarchies'] = array('stream_id'=>$programMapping['stream_id']);
			}
			if($programMapping['substream_id']){
				$oldCourseData['hierarchies'] = array('substream_id'=>$programMapping['substream_id']);
			}
			if($programMapping['base_course_id']){
				$oldCourseData['baseCourseId'] = $programMapping['base_course_id'];
			}
			
			foreach ($resultData as $key => $row) {
				//step 7- locationId should be same of old course and new course
				if(in_array($row['locationId'],$newCourseData[$newCourse]['locationList'])){
					//step 8- match baseCourseId 
					if(($oldCourseData['baseCourseId'] !='' && $newCourseData[$newCourse]['entery']['baseCourseId'] !='') && ($oldCourseData['baseCourseId'] == $newCourseData[$newCourse]['entery']['baseCourseId'])){
						$updateId[] = $row['id'];
						continue;
					}
					//step 9- match hierarchies
					if(count($newCourseData[$newCourse]['entery']['hierarchies'])>0){
						foreach ($newCourseData[$newCourse]['entery']['hierarchies'] as $key => $value) {
							if($value['stream_id'] == $oldCourseData['hierarchies']['stream_id']){
								$updateId[] = $row['id'];
								continue;
							}
							if($value['substream_id'] == $oldCourseData['hierarchies']['substream_id']){
								$updateId[] = $row['id'];
								continue;
							}	
						}
					}

					if(($oldCourseData['baseCourseId'] !='' && $newCourseData[$newCourse]['exit']['baseCourseId'] !='') && ($oldCourseData['baseCourseId'] == $newCourseData[$newCourse]['exit']['baseCourseId'])){
						$updateId[] = $row['id'];
						continue;
					}

					if(count($newCourseData[$newCourse]['exit']['hierarchies'])>0){
						foreach ($newCourseData[$newCourse]['exit']['hierarchies'] as $key => $value) {
							if($value['stream_id'] == $oldCourseData['hierarchies']['stream_id']){
								$updateId[] = $row['id'];
								continue;
							}
							if($value['substream_id'] == $oldCourseData['hierarchies']['substream_id']){
								$updateId[] = $row['id'];
								continue;
							}
						}
					}
				}
			}
			return implode(',',array_unique(explode(',',implode(',', $updateId))));
		}
	}

	function prepareFinalResultForTopFeaturedCollege($sortedInstArr, $instRankBySource, $instCourseData){
		$instDetail =  $this->getInstituteDetails($sortedInstArr); 
		$instRankBySource = $this->processInstRankData($instRankBySource);
        $instituteRankData = prepareRankAndCaData($instRankBySource, $instCourseData);
        $finalResult = $this->prepareFinalData($instDetail,$instituteRankData);
        return $finalResult;
	}

	function prepareDataForFeaturedQuestion($programId){
		$instCourseData  =  $this->getInstAndCourseOfProgram($programId); 
     	foreach($instCourseData as $key=>$val){ 
            $instituteIds[] = $key; 
        } 
        $instituteIds = array_unique($instituteIds); 
		$instRankBySource = $this->getAllPaidInstituteId($instituteIds, 9);
        $courseIdStr = getCrsIdStringOfTopRankedInst($instRankBySource, $instCourseData);
		return $this->campusconnect->getAllFeaturedAnswers($courseIdStr);
	}

	// this function is used to map CR with other courses
	function filterCourseOnProgramId($courseIdsArr, $programId){
		
		if(empty($courseIdsArr)){
			return;
		}

		$programMappingArray = $this->getCCProgramMapping($programId);
		$programMappingArray = $programMappingArray[$programId];
		$programMapping[$programMappingArray['entityType']] = $programMappingArray['entityId'];
		
		if($programMapping['stream_id']){
			$mainCourseData['hierarchies'] = array('stream_id'=>$programMapping['stream_id']);
		}
		if($programMapping['substream_id']){
			$mainCourseData['hierarchies'] = array('substream_id'=>$programMapping['substream_id']);
		}
		if($programMapping['base_course_id']){
			$mainCourseData['baseCourseId'] = $programMapping['base_course_id'];
		}

		// load the course builder
	    $this->CI->load->builder("nationalCourse/CourseBuilder");
	    $courseBuilder = new CourseBuilder();
	    $courseRepo = $courseBuilder->getCourseRepository();
	    $courseObj  = $courseRepo->findMultiple($courseIdsArr);

		if(empty($courseObj)){
	    	return 'course object is blank';
	    }

	    foreach ($courseIdsArr as $index => $courseId) {
	    	 if(!empty($courseObj[$courseId])){
		    	// step 1-get course information 
		    	$courseTypeObj = $courseObj[$courseId]->getCourseTypeInformation();
		    	// step 2- get hierarchy and basecourse for entery level
		    	if(!empty($courseTypeObj['entry_course'])){

		    		$courseData[$courseId]['entery']['baseCourseId'] = $courseTypeObj['entry_course']->getBaseCourse();
		    		
		    		if(($mainCourseData['baseCourseId'] !='' && $courseData[$courseId]['entery']['baseCourseId'] !='') && ($mainCourseData['baseCourseId'] == $courseData[$courseId]['entery']['baseCourseId'])){
						$finalCourseObj[$courseId] = $courseObj[$courseId];
						continue;
					}

	    			$courseData[$courseId]['entery']['hierarchies']  = $courseTypeObj['entry_course']->getHierarchies();	

	    			//step 3- match hierarchies
					if(count($courseData[$courseId]['entery']['hierarchies'])>0){
						foreach ($courseData[$courseId]['entery']['hierarchies'] as $key => $value) {
							if($value['stream_id'] == $mainCourseData['hierarchies']['stream_id']){
								$finalCourseObj[$courseId] = $courseObj[$courseId];
								continue;
							}
							if($value['substream_id'] == $mainCourseData['hierarchies']['substream_id']){
								$finalCourseObj[$courseId] = $courseObj[$courseId];
								continue;
							}	
						}
					}

		    	}// end entry course

		    	// step 4- get hierarchy and basecourse for exit level
	    		if(!empty($courseTypeObj['exit_course'])){
	    			$courseData[$courseId]['exit']['baseCourseId']   = $courseTypeObj['exit_course']->getBaseCourse();

	    			if(($mainCourseData['baseCourseId'] !='' && $courseData[$courseId]['exit']['baseCourseId'] !='') && ($mainCourseData['baseCourseId'] == $courseData[$courseId]['exit']['baseCourseId'])){
						$finalCourseObj[$courseId] = $courseObj[$courseId];
						continue;
					}

	    			$courseData[$courseId]['exit']['hierarchies']    = $courseTypeObj['exit_course']->getHierarchies();	

	    			if(count($courseData[$courseId]['exit']['hierarchies'])>0){
						foreach ($courseData[$courseId]['exit']['hierarchies'] as $key => $value) {
							if($value['stream_id'] == $mainCourseData['hierarchies']['stream_id']){
								$finalCourseObj[$courseId] = $courseObj[$courseId];
								continue;
							}
							if($value['substream_id'] == $mainCourseData['hierarchies']['substream_id']){
								$finalCourseObj[$courseId] = $courseObj[$courseId];
								continue;
							}	
						}
					}
	    		}// end exit course
	    	}
	    }
	    return $finalCourseObj;
	}

	//When an Institute is migrated or deleted, this API updates the same instituteId in CA Profile Table, Main Course Mapping Table and Other Courses Table. 
	function updateCRInstId($oldInstId, $newInstId, $locChanged = 0){
		if(empty($oldInstId)){
			return 'Inst can\'t be blank,not able to Migrate / Delete CR Mapping.';
		}
		if(!is_array($oldInstId) || !(count($oldInstId) > 0)){
			return 'Inst can\'t be blank,not able to Migrate / Delete CR Mapping.';
		}

		$result = $this->campusconnect->checkIfCRMappingExistForInstituteInMain($oldInstId);
		if($result['status'] == true){
			if(empty($newInstId) || $locChanged == 1){
				// when an institute is deleted.
				//update status as deleted for these inst ids in (CA_OtherCourseDetails,CA_MainCourseMappingTable)
				$response =  $this->campusconnect->updateStatusForDeletedInstInMain($oldInstId,$dbHandle);
				if($response ==1){
					$response = $this->campusconnect->updateStatusForDeletedInstInOther($oldInstId,$dbHandle);
					if($response ==1){
						return 'CR Mapping is migrated / deleted for .'.$result['instituteIds'];
					}else{
						return 'CP Mapping is not migrated / deleted.';
					}
				}else{
					return 'CP Mapping is not migrated / deleted.';
				}
			}

			$response = $this->campusconnect->updateInstIdOfCR($oldInstId, $newInstId, $dbHandle);
			if($response ==1){
				return 'CR Mapping is migrated / deleted.';
			}else{
				return 'CP Mapping is not migrated / deleted.';
			}
		}else{
			return 'CR migration not applicable.';
		}
	}


	function getProgramIdMappingDetails($programIds = ''){
		if(!(($programIds == 'all') || (intval($programIds)> 0) || (is_array($programIds) && count($programIds) >0))){
			return false;
		}
		//$this->ccmodel = $this->CI->load->model('CA/campusconnectmodel');
		$getAllData = 0;
		if($programIds == 'all'){
			$getAllData = 1;	
		}
		$result = $this->getCCProgramMapping($programIds, $getAllData);
		if(!is_array($result)){
			return false;
		}

		if(is_array($result) && count($result) <=0){
			return false;
		}

		$mappingArray = array();
		$streamIds = $substreamIds = $baseCourseIds = array();
		foreach ($result as $programId => $value) {
			switch ($value['entityType']) {
				case 'stream_id':
				$streamIds[$value['entityId']] = 0;
					break;

				case 'substream_id':
				$substreamIds[$value['entityId']] = 0;
					break;

				case 'base_course_id':
				$baseCourseIds[$value['entityId']] = 0;
					break;
			}
			$mappingArray[$programId] = array(
				'entityType' => $value['entityType'],
				'entityId' => $value['entityId'],
				);
		}
		//_p($baseCourseIds);_p($streamIds);_p($substreamIds);		_p($mappingArray);die;

		$this->CI->load->builder('ListingBaseBuilder', 'listingBase');
		$listingBaseBuilder   = new ListingBaseBuilder();

		// base course details
		$baseCourseIds = array_keys($baseCourseIds);
		if(count($baseCourseIds) > 0){
			$basecourseRepository = $listingBaseBuilder->getBaseCourseRepository();
			$baseCourseObjs = $basecourseRepository->findMultiple($baseCourseIds);
			$baseCourseDetails = array();
			foreach ($baseCourseObjs as $baseCourseId => $baseCourseObj) {
				$baseCourseName = $baseCourseObj->getAlias() ? $baseCourseObj->getAlias() :$baseCourseObj->getName();
				$baseCourseDetails[$baseCourseId] = array(
					'title' => $baseCourseName,
					'base_course_id' =>$baseCourseId,
					'base_course_name' => $baseCourseName,
					'url' => strtolower(seo_url($baseCourseName, "-", 30))
				);
			}
		}
		//_p($baseCourseDetails);die;

		unset($baseCourseObjs);

		// stream details
		$streamIds = array_keys($streamIds);
		if(count($streamIds) > 0){
			$streamRepository     = $listingBaseBuilder->getStreamRepository();
			$stremObjs = $streamRepository->findMultiple($streamIds);
			$streamDetails = array();
			foreach ($stremObjs as $streamId => $streamObj) {
				$streamName = $streamObj->getAlias() ? $streamObj->getAlias() :$streamObj->getName();
				$streamDetails[$streamId] = array(
							'title' => $streamName,
							'stream_id' =>$streamId,
							'stream_name' => $streamName,
							'url' => strtolower(seo_url($streamName, "-", 30))
							);
			}
			unset($stremObjs);
		}
		//_p($streamDetails);die;

		// substream details
		$substreamIds = array_keys($substreamIds);
		if(count($substreamIds) > 0){
			$subStremRepoObj = $listingBaseBuilder->getSubstreamRepository();
			$subStremObjs = $subStremRepoObj->findMultiple($substreamIds);
			$substreamParentIds = array();
			$substreamDetails = array();
			foreach ($subStremObjs as $substreamId => $subStremObj) {
				$substreamParentIds[$subStremObj->getPrimaryStreamId()] = 0;
				$substreamName = $subStremObj->getAlias() ? $subStremObj->getAlias() :$subStremObj->getName();
				$substreamDetails[$substreamId] = array(
					'title' => $substreamName,
					'substream_id' => $substreamId,
					'substream_name' => $substreamName,
					'parent_stream_id' =>$subStremObj->getPrimaryStreamId(),
				);
			}
			$substreamParentIds = array_keys($substreamParentIds);
			$streamRepository     = $listingBaseBuilder->getStreamRepository();
			$stremObjs = $streamRepository->findMultiple($substreamParentIds);
			$substreamParentDetails = array();
			foreach ($stremObjs as $streamId => $stremObj) {
				$substreamParentDetails[$streamId] = $stremObj->getAlias() ? $stremObj->getAlias() :$stremObj->getName();
			}
			unset($stremObjs);

			foreach ($substreamDetails as $substreamId => $substreamDetail) {
				$substreamDetails[$substreamId]['parent_stream_name'] = $substreamParentDetails[$substreamDetail['parent_stream_id']];
				$substreamDetails[$substreamId]['url'] = strtolower(seo_url($substreamParentDetails[$substreamDetail['parent_stream_id']], "-", 30)).'/'.strtolower(seo_url($substreamDetail['substream_name'], "-", 30));
			}
		}
		
		foreach ($mappingArray as $programId => $entityDetails) {
			if($entityDetails['entityType'] == 'base_course_id'){
				$url = $baseCourseDetails[$entityDetails['entityId']]['url'];
				$mappingArray[$programId] = $baseCourseDetails[$entityDetails['entityId']];
			}else if($entityDetails['entityType'] == 'substream_id'){
				$url = $substreamDetails[$entityDetails['entityId']]['url'];
				$mappingArray[$programId] = $substreamDetails[$entityDetails['entityId']];
			}else if($entityDetails['entityType'] == 'stream_id'){
				$url = $streamDetails[$entityDetails['entityId']]['url'];
				$mappingArray[$programId] = $streamDetails[$entityDetails['entityId']];
			}
			$mappingArray[$programId]['url'] = SHIKSHA_HOME.'/'.$url.'/resources/campus-connect-program-'.$programId;

		}
		//_p($mappingArray);die;
		return $mappingArray;
	}

	function redirectionRule($url){
		$currentUrl = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
		if($currentUrl != $url){
			header("Location: $url",TRUE,301);
		}
	}
	
	function getProgramDataOnProgramIdIndex($data){
		if(!empty($data)){
					foreach ($data as $key => $value) {
						if($value['entityType'] == 'stream'){
							$value['entityType'] = 'stream_id';
						}else if($value['entityType'] == 'substream'){
							$value['entityType'] = 'substream_id';
						}else {
							$value['entityType'] = 'base_course_id';
						}
						$filterData[$value['programId']]['entityType']=$value['entityType'];
						$filterData[$value['programId']]['entityId']=$value['entityId'];

					}
				}

		return $filterData;
	}

	function prepareBeaconTrackData($programIdMappingData,$programId){
		$beaconTrackData = array(
            'pageIdentifier' => 'campusRepresentative',
            'pageEntityId'   => $programId, // No Page entity id for this one
        );
		$hierarchy = array();
		if(isset($programIdMappingData['stream_id'])){
			$beaconTrackData['extraData']['hierarchy'] = array(
				'streamId' => $programIdMappingData['stream_id'],
		        'substreamId' => 0,
		        'specializationId' => 0
				);
        }elseif(isset($programIdMappingData['substream_id'])){
        	$beaconTrackData['extraData']['hierarchy'] = array(
				'streamId' => $programIdMappingData['parent_stream_id'],
		        'substreamId' => $programIdMappingData['substream_id'],
		        'specializationId' => 0
				);
        }elseif(isset($programIdMappingData['base_course_id'])){
        	$beaconTrackData['extraData']['baseCourseId'] = $programIdMappingData['base_course_id'];	
        }
        $beaconTrackData['extraData']['countryId'] = 2;
		return $beaconTrackData;
	}
}
?>
