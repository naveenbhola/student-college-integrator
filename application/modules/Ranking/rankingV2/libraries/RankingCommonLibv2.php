<?php

class RankingCommonLibv2 {
	function __construct(){
		$this->CI = &get_instance();
		$this->latestSourceData = array();
	}

	public function getInstituteCourses($instituteId = NULL){
		$courses = array();
		if(!empty($instituteId)){
			$ci = &get_instance();
			$ci->load->builder('nationalInstitute/InstituteBuilder');
			unset($ci);
			$instituteBuilder = new InstituteBuilder();
			$this->instituteRepo = $instituteBuilder->getInstituteRepository();
    		$instituteObject = $this->instituteRepo->find($instituteId);
			if(!empty($instituteObject)){
				$instituteName 	= $instituteObject->getName();
				$instituteId 	= $instituteObject->getId();
				if(!empty($instituteName)){
					$instituteCourses = $this->instituteRepo->getCoursesOfInstitutes(array($instituteId));
					if(empty($instituteCourses)){
						return array();
					}
					$instituteCourses = reset($instituteCourses);
					$courses = $instituteCourses->getCourse();
					$courseIds = array_map(function($ele){return $ele->getId();}, $courses);
					if(count($courseIds) > 0){
						$courses = $this->getCourseRankingDetails($courseIds,$instituteObject);
					}
				}
			}
		}
		return $courses;
	}

	public function updateInstituteForCourseIds($courseIds,$instituteId){
		$ci = &get_instance();
		$ranking_model = $ci->load->model(RANKING_PAGE_MODULE.'/ranking_model');
		return $ranking_model->updateInstituteForCourseIds($courseIds,$instituteId);
	}

	public function updateInstituteId($oldId,$newId){
		$ci = &get_instance();
		$ranking_model = $ci->load->model(RANKING_PAGE_MODULE.'/ranking_model');
		return $ranking_model->updateInstituteId($oldId,$newId);
	}
    /*
     *  To mark courses deleted in ranking pages when courses are deleted.
     * @params : 
     *  $courseIds : An array of courseIds
     *  $userStatus: UserValidation array, only 'listingAdmin' usergroup can delete
     * @return :
     *  status : The status of the deletion operation. Possible statuses are 'Access Denied', 'Invalid Input', 'Failed', 'Success'.
     *  
     */
    public function markCoursesDeletedInRankingPages($courseIds,$userStatus){
        
        if(!is_array($courseIds)){
            return "Invalid Input";
        }
        if(count($courseIds)==0){
            return 'Success';
        }
        $ci = &get_instance();
        $ci->load->model(RANKING_PAGE_MODULE.'/ranking_model');
        $ranking_model          = new ranking_model();
        return $ranking_model->markCoursesDeletedInRankingPages($courseIds,$userStatus);
    }
	public function getCourseRankingDetails($courseIds,$instituteObject){
		if(empty($courseIds)){
			return array();
		}
		$ci = &get_instance();
		$ci->load->builder("nationalCourse/CourseBuilder");
		unset($ci);
		$builder = new CourseBuilder();
        $courseRepository = $builder->getCourseRepository();
        $courseObjects = $courseRepository->findMultiple($courseIds,array('course_type_information','location','fees','eligibility'));
        $courseData = array();
        foreach($courseObjects as $courseId => $course){
        	$id = $course->getId();
        	if(empty($id)){
        		continue;
        	}
        	$temp = array();
        	if(empty($instituteObject)){
        		$temp['institute_id'] = $course->getInstituteId();
        		$temp['institute_name'] = $course->getInstituteName();
	        	$temp['institute_url'] = '';//$instituteObject->getURL();

        	}else{
        		$temp['institute_id'] = $instituteObject->getId();
	        	$temp['institute_name'] = $instituteObject->getName();
	        	$temp['institute_url'] = $instituteObject->getURL();
        	}
        	
        	$temp['id'] = $course->getId();
        	$temp['name'] = $course->getName();
        	$temp['url'] = $course->getURL();
        	$locations = reset($course->getLocations());
        	if(is_object($locations)){
        		$temp['city'] = $locations->getCityName();
        		$temp['state'] = $locations->getStateName();	
        	}else{
        		$temp['city'] = "Undefined";
        		$temp['state'] = "Undefined";
        	}
        	
        	$eligibility = $course->getEligibility();
        	foreach($eligibility['general'] as $exam){
        		$temp['exams'][$exam->getExamName()] = array('name'=>$exam->getExamName(),'marks'=>$exam->getValue(),'marks_type'=>$exam->getUnit());
        	}
        	$fees = $course->getFees();
        	if(!empty($fees)){
        		$temp['fees_currency'] = $fees->getFeesUnitName();
        		$temp['fees_value'] = $fees->getFeesValue();
        	}else{
        		$temp['fees_value'] = 0;
        		$temp['fees_currency'] = 'INR';	
        	}
        	
        	$info = $course->getCourseTypeInformation();
        	$info = reset($info);
        	//$hierarchy = reset($info->getHierarchies());
            $hierarchy = $course->getPrimaryHierarchy();
        	$temp['stream'] = $hierarchy['stream_id'];
        	$temp['substream'] = $hierarchy['substream_id'];
        	$temp['specialization'] = $hierarchy['specialization_id'];
        	$temp['credential'] = $info->getCredential();
        	$temp['baseCourse'] = $info->getBaseCourse();
        	$temp['educationType'] = $course->getEducationType();
        	$temp['deliveryMethod'] = $course->getDeliveryMethod();
        	$courseData[$course->getId()] = $temp;
        }
		return $courseData;
	}

	public function getRankingPageData($rankingPageId = NULL){
		$rankingPageData = array();
		if(empty($rankingPageId)){
			return $rankingPageData;
		}
		$ci = &get_instance();
		$ci->load->model(RANKING_PAGE_MODULE.'/ranking_model');
		$rankingModel = new ranking_model();
		$params = array();
		$params['ranking_page_id'] = $rankingPageId;
		$rankingPageData = $rankingModel->getRankingPageData($params);

		if(!empty($rankingPageData)){
			foreach($rankingPageData as $key => $pageData){
				$courseIds[] = $pageData['course_id'];
				$rankingPageCourseIds[] = $pageData['id'];
			}
			
			//get course param details
			$courseParamData = $rankingModel->getRankingPageCourseParamData($rankingPageCourseIds);
            
			foreach($courseParamData as $key => $data) {
				$courseCustomParamData[$data['ranking_page_course_id']][$data['source_id']] = $data['rank'];
			}
            
			//get course data
			$courseDetails = $this->getCourseRankingDetails($courseIds);
            
			foreach($rankingPageData as $key => $pageData){
				$tempCourseId = $pageData['course_id'];
				if(array_key_exists($tempCourseId, $courseDetails)){
                    $rankingPageData[$key]['course_details'] = $courseDetails[$tempCourseId];
    				$tempPageCourseId = $pageData['id'];
    				$rankingPageData[$key]['course_param_details'] = array();
    				if(array_key_exists($tempPageCourseId, $courseCustomParamData)){
    					$rankingPageData[$key]['course_param_details'] = $courseCustomParamData[$tempPageCourseId];
    				}
                }
			}
		}
        
		return array_values($rankingPageData);
	}

	public function getCourseRankBySource($courseIds = array(),$isRankingPageIdRequired = false){
		if(!(is_array($courseIds) && count($courseIds) > 0)){
			return false;
		}
		$courseRankBySource = array();
		$this->CI = &get_instance();
		$this->CI->load->model('rankingV2/ranking_model');
		$this->rankingModel = new ranking_model();
		$courseRankBySourceDetails = $this->rankingModel->getCourseRankBySource($courseIds);
		if($courseRankBySourceDetails){
			$courseRankBySource = $this->_processCourseRankBySourceData($courseRankBySourceDetails , $isRankingPageIdRequired);
		}
		return $courseRankBySource;
	}

	public function getCoursesRankData($courseIds = array(), $excludeEnggSpecializationRanking = true, $maxRank){
		if(!(is_array($courseIds) && count($courseIds) > 0)){
			return false;
		}
		$courseRank = array();
		$sourceIds = array();
		$this->CI = &get_instance();
		$this->CI->load->model('rankingV2/ranking_model');
		$this->CI->load->config('rankingV2/rankingConfig');
		$rankingConfig = $this->CI->config->item('rankingConfig');
		$this->rankingModel = new ranking_model();
		$courseRankBySourceDetails = $this->rankingModel->getCoursesRankData($courseIds, $excludeEnggSpecializationRanking, $maxRank);
		
		$latestRanksCheck = array();
		$courseRankBySourceDetailsLatest = array();
		foreach ($courseRankBySourceDetails as $row) {
			if(empty($latestRanksCheck[$row['course_id']][$row['publisher_id']])){
				$latestRanksCheck[$row['course_id']][$row['publisher_id']] = 1;
				$courseRankBySourceDetailsLatest[] = $row;
			}
		}
		$courseRankBySourceDetails = $courseRankBySourceDetailsLatest;
		
		foreach ($courseRankBySourceDetails as $value) {
			$sourceIds[] = $value['source_id'];
		}
		$sourceIds = array_values(array_unique($sourceIds));
		$sourceData = $this->_getSourceDetails($sourceIds);

		$uniqueRankingIds = array();
        foreach ($courseRankBySourceDetails as $value) {
            $rankingId = $value['source_id']."_".$value['ranking_page_id'];
            if(!in_array($rankingId, $uniqueRankingIds) && !empty($value['rank'])) {
                $value['source_name'] = $sourceData[$value['source_id']];
                $courseRank[] = $value;
                $sourceIds[] = $value['source_id'];
                $uniqueRankingIds[] = $rankingId;
            }
        }
		//_p($courseRank[0]);die;
        if($courseRank[0]['publisher_id'] == $rankingConfig['NIRF_Publisher_Id'] && ($courseRank[0]['ranking_page_id'] == $rankingConfig['MBA_Ranking_Page_Id'] || $courseRank[0]['ranking_page_id'] == $rankingConfig['EXECUTIVE_MBA_Ranking_Page_Id']) &&  $courseRank[0]['rank'] > 50){
                 if( $courseRank[0]['rank'] > 50 &&  $courseRank[0]['rank'] < 76) {
                      $courseRank[0]['rank'] = "51-75";
                 }
                 else if( $courseRank[0]['rank'] > 75 &&  $courseRank[0]['rank'] < 101) {
                      $courseRank[0]['rank'] = "76-100";
                 }
        }
        //_p($courseRank);die;
        return $courseRank;
	}
	public function getRankingPageCourses($rankingPageObject){
                $courseIds = array();
                if(empty($rankingPageObject)){
                    return $courseIds;
                }
		$rankingPageDataArray = $rankingPageObject->getRankingPageData();
		
		foreach($rankingPageDataArray as $rankingPageData){
			$courseIds[] = $rankingPageData->getCourseId();
		}
		return $courseIds;
	}
	private function _processCourseRankBySourceData($courseRankBySourceDetails , $isRankingPageIdRequired = false){
		$courseRankBySource = array();
		if(is_array($courseRankBySourceDetails) && count($courseRankBySourceDetails) > 0){
			$rankingPageIds = array();
			foreach ($courseRankBySourceDetails as $key => $courseRankDetails) {
				$rankingPageIds[$courseRankDetails['ranking_page_id']] = 1;
			}
			$rankingPageIds = array_keys($rankingPageIds);
			$rankingPageToSourceMapping = $this->_getRankingPageToSourceMapping($rankingPageIds);
			if(is_array($rankingPageToSourceMapping) && count($rankingPageToSourceMapping) > 0){
				foreach ($courseRankBySourceDetails as $key => $courseRankDetail){
					if($rankingPageToSourceMapping[$courseRankDetail['ranking_page_id']][$courseRankDetail['source_id']]){
						$courseRankBySource[$courseRankDetail['course_id']][$courseRankDetail['source_id']] = array(
							'rank' => $courseRankDetail['rank']
						);
						if($isRankingPageIdRequired == true){
							$courseRankBySource[$courseRankDetail['course_id']][$courseRankDetail['source_id']]['rankingPageId'] = $courseRankDetail['ranking_page_id'];
						}
						$sourceIds[$courseRankDetail['source_id']] = 0;
					}
				}
				$sourceIds = array_keys($sourceIds);
				$sourceDetails = $this->_getSourceDetails($sourceIds);
				foreach ($courseRankBySource as $courseId => $courseRankDetails) {
					foreach ($courseRankDetails as $sourceId => $sourceToRankDetails) {
						if($sourceDetails[$sourceId]){
							if($isRankingPageIdRequired == false){
								$courseRankBySource[$courseId][$sourceDetails[$sourceId]] = $sourceToRankDetails['rank'];	
							}else{
								$courseRankBySource[$courseId][$sourceDetails[$sourceId]] = array(
								'rankingPageId' => $sourceToRankDetails['rankingPageId'],
								'rank'			=> $sourceToRankDetails['rank']
								);	
							}
						}
						unset($courseRankBySource[$courseId][$sourceId]);
					}
				}
			}
		}
		return $courseRankBySource;
	}

	public function getInstituteRankBySource($instituteIds,$count,$filters= array()){
		if(!(is_array($instituteIds) && count($instituteIds) > 0 && $count > 0)){
			return false;
		}

		$instituteRankBySource = array();
		$this->CI = &get_instance();
		$this->CI->load->model('rankingV2/ranking_model');
		$this->rankingModel = new ranking_model();

		if(is_array($filters) && count($filters)>0){
			$validFilters = array('stream_id','substream_id','base_course_id','education_type','delivery_method','credential','specialization_id');

			foreach($filters as $key => $value){
				if(!in_array($key, $validFilters)){
					unset($filters[$key]);
				}
			}

			foreach($validFilters as $param){
				if(empty($filter[$param]) || !intval($filter[$param])){
					if($param == 'education_type'){
						$hasEducationTypeFilter = false;
					}
					$filter[$param] = 0;
				}
			}
		}

		$instituteRankBySourceDetails = $this->rankingModel->getInstituteRankBySourceDetails($instituteIds,$filters);
		if(!(is_array($instituteRankBySourceDetails) && count($instituteRankBySourceDetails) > 0)){
			if($hasEducationTypeFilter == false){
				$filters['education_type'] = EDUCATION_TYPE;
				$instituteRankBySourceDetails = $this->rankingModel->getInstituteRankBySourceDetails($instituteIds,$filters);
			}
		}

		$instituteRankBySource = $this->_processInstituteRankBySourceData($instituteRankBySourceDetails);
		if(!(is_array($instituteRankBySource) && count($instituteRankBySource) > 0)){
			return array();
		}
		foreach ($instituteRankBySource as $instituteId => $instituteRank) {
			foreach ($instituteRank as $source => $rank) {
				$sourceIds[$source] =1;
			}
		}
		$sourceIds = array_keys($sourceIds);
		$latestUpdatedSourceId = $this->rankingModel->getLatestUpdatedSource($sourceIds);
		$latestUpdatedSourceId = $latestUpdatedSourceId[0]['source_id'];
				
		uasort($instituteRankBySource,function($a,$b)use($latestUpdatedSourceId){
			if(isset($a[$latestUpdatedSourceId]) && isset($b[$latestUpdatedSourceId])){
				if($a[$latestUpdatedSourceId] > $b[$latestUpdatedSourceId]){
					return 1;
				}
			}else if(!isset($a[$latestUpdatedSourceId]) && isset($b[$latestUpdatedSourceId])){
				return 1;
			}else if(isset($a[$latestUpdatedSourceId]) && !isset($b[$latestUpdatedSourceId])){
				return -1;
			}else{
				return 0;
			}		
		});

		$instituteRankBySource = array_slice($instituteRankBySource,0,$count,true);
		$sourceDetails = $this->_getSourceDetails($sourceIds);
		if($sourceDetails){
			foreach ($instituteRankBySource as $instituteId => $instituteRankDetails) {
				foreach ($instituteRankDetails as $sourceId => $rank) {
					if($sourceDetails[$sourceId]){
						$instituteRankBySource[$instituteId][$sourceDetails[$sourceId]] = $rank;
					}
					unset($instituteRankBySource[$instituteId][$sourceId]);
				}
			}
		}else{
			return array();
		}
		return $instituteRankBySource;
	}

	private function _processInstituteRankBySourceData($instituteRankBySourceDetails){
		$instituteRankBySource = array();
		if(is_array($instituteRankBySourceDetails) && count($instituteRankBySourceDetails) > 0){
			$rankingPageIds = array();
			foreach ($instituteRankBySourceDetails as $key => $instituteRankDetails) {
				$rankingPageIds[$instituteRankDetails['ranking_page_id']] = 1;
			}
			$rankingPageIds = array_keys($rankingPageIds);
			$rankingPageToSourceMapping = $this->_getRankingPageToSourceMapping($rankingPageIds);	
			if(is_array($rankingPageToSourceMapping) && count($rankingPageToSourceMapping) > 0){
				foreach ($instituteRankBySourceDetails as $key => $instituteRankDetail) {
					if($rankingPageToSourceMapping[$instituteRankDetail['ranking_page_id']][$instituteRankDetail['source_id']]){
						$instituteRankBySourceInfo[$instituteRankDetail['institute_id']][$instituteRankDetail['course_id']][$instituteRankDetail['source_id']] = array(
							'rank' => $instituteRankDetail['rank']
						);
						$sourceIds[$instituteRankDetail['source_id']] = 0;
					}
				}
				$sourceIds = array_keys($sourceIds);
				foreach ($instituteRankBySourceInfo as $instituteId => $instituteDetails) {
					foreach ($instituteDetails as $courseId => $courseDetails) {
						foreach ($courseDetails as $sourceId => $sourceToRankDetails) {
							if(isset($instituteRankBySource[$instituteId][$sourceId])){
								if($instituteRankBySource[$instituteId][$sourceId] > $sourceToRankDetails['rank']){
									$instituteRankBySource[$instituteId][$sourceId] = $sourceToRankDetails['rank'];
								}
							}else{
								$instituteRankBySource[$instituteId][$sourceId] = $sourceToRankDetails['rank'];
							}
						}
					}
				}
			}
		}
		return $instituteRankBySource;
	}

	private function _getSourceDetails($sourceIds){
		$sourceDetails = array();
		if(is_array($sourceIds) && count($sourceIds) > 0){
			$result = $this->rankingModel->getSourceDetails($sourceIds);
			if($result == false){
				return false;
			}else{
				foreach ($result as $key => $value) {
					$sourceDetails[$value['source_id']] = $value['name'];
				}
				return $sourceDetails;
			}
		}
	}

	private function _getRankingPageToSourceMapping($rankingPageIds){
		if(!(is_array($rankingPageIds) && count($rankingPageIds)>0)){
			return false;
		}
		$rankingPageToSourceMapping = array();
		$result = $this->rankingModel->getRankingPageToSourceMapping($rankingPageIds);
		if($result == false){
			return false;
		}else{
			foreach ($result as $key => $value) {
				$rankingPageToSourceMapping[$value['ranking_page_id']][$value['source_id']] = 1;
			}
			return $rankingPageToSourceMapping;
		}
	}

	public function getRankingDetailsByFilter($filters = array()){
		$validFilters = array('stream_id','substream_id','base_course_id','education_type','delivery_method','credential','specialization_id');
		foreach($filters as $key => $value){
			if(!in_array($key, $validFilters)){
				unset($filters[$key]);
			}
		}

		foreach($validFilters as $param){
			if(empty($filters[$param]) || !intval($filters[$param])){
				if($param == 'education_type'){
					$hasEducationTypeFilter = false;
				}
				$filters[$param] = 0;
			}
		}
		$this->CI = &get_instance();
		$this->CI->load->model(RANKING_PAGE_MODULE.'/ranking_model');
		$this->rankingModel = new ranking_model();
		$rankingData = $this->rankingModel->getRankingDetailsByFilter($filters);
		if(!(count($rankingData)>0)){
			if($hasEducationTypeFilter == false){
				$filters['education_type'] = EDUCATION_TYPE;
				$rankingData = $this->rankingModel->getRankingDetailsByFilter($filters);
			}
		}
		return $rankingData;
	}

	public function getNonZeroRankingPages($filters, $limit = false){
		$this->CI = &get_instance();
		$this->CI->load->model(RANKING_PAGE_MODULE.'/ranking_model');
		$this->rankingModel = new ranking_model();
		$rankingData = $this->rankingModel->getNonZeroRankingPages($filters);
		if(!(count($rankingData)>0)){
			if(!$filter['education_type']){
				$filter['education_type'] = EDUCATION_TYPE;
				$rankingData = $this->rankingModel->getNonZeroRankingPages($filters);
			}
		}
		return $rankingData;
	}

	public function getLatestRankingSourcesForCourses($courseList){
		$latestRankSourceData = array();
		// $courseList[] = 1645;
		// $courseList[] = 267848;
		// $courseList[] = 314107;
		// $courseList[] = 27310;
		if(!empty($courseList) && is_array($courseList)){
			$this->CI = &get_instance();
			$this->CI->load->model(RANKING_PAGE_MODULE.'/ranking_model');
			$this->rankingModel = new ranking_model();
			$rankingSourceIds = $this->rankingModel->getRankingSourcesForCourses($courseList);
			$latestRankData = $rankingPageIds = $latestSourceIds = $latestSourceIdNameMap = $sourceRankData = array();
			$finalData = array();
			foreach ($rankingSourceIds['source_id'] as $courseId => $rankSourceIds) {
				foreach ($rankSourceIds as $rankPageId => $publisherData) {
					if(empty($this->latestSourceData[$rankPageId]) || !is_array($this->latestSourceData[$rankPageId])){
						$this->latestSourceData[$rankPageId] = $this->rankingModel->getLatestSourceForRankingPage($rankPageId);
					}
					$finalData[$courseId]['rankingPageIds'][$rankPageId] = $rankPageId;
					foreach ($publisherData as $publisherId => $sourceData) {
						foreach ($sourceData as $rankInfo) {
							if(!isset($latestRankData[$courseId][$rankPageId][$publisherId]) || $latestRankData[$courseId][$rankPageId][$publisherId]['year'] < $rankInfo['year'])
							$latestRankData[$courseId][$rankPageId][$publisherId] = $rankInfo;
						}
					}
				}
			}
			foreach ($latestRankData as $course_id => $courseWiseRankData) {			
				foreach ($courseWiseRankData as $pageId => $latestRank) {
					foreach ($latestRank as $publisher_id => $rankData) {
						if(in_array($rankData['source_id'], $this->latestSourceData[$pageId])){
							//$finalData[$course_id]['latestSourceIds'][$rankData['source_id']] = $rankData['source_id'];
							$finalData[$course_id]['latestSourceIdNameMap'][$rankData['ranking_page_id']][$rankData['publisher_id']] = $rankData['publisher_name'].'_'.$rankData['year'].':'.$rankData['source_id'];
							//$finalData[$course_id]['sourceRankData'][$rankData['ranking_page_id']][$rankData['source_id']] = $rankData['rank'];
						}
					}
				}
			}
		}
		foreach ($latestRankData as $course_id => &$courseWiseRankData) {
			foreach ($courseWiseRankData as $pageId => &$latestRank) {
				if(count($finalData[$course_id]['latestSourceIdNameMap'][$pageId])== 1 && isset($finalData[$course_id]['latestSourceIdNameMap'][$pageId][RANKING_SHIKSHA_DEFAULT_PUBLISHER])){
					unset($finalData[$course_id]['latestSourceIdNameMap'][$pageId]);
					unset($finalData[$course_id]['rankingPageIds'][$pageId]);
					continue;
				}
				foreach ($latestRank as $publisher_id => $rankData) {
					if(in_array($rankData['source_id'], $this->latestSourceData[$pageId])){
						$finalData[$course_id]['latestSourceIds'][$rankData['source_id']] = $rankData['source_id'];
						$finalData[$course_id]['sourceRankData'][$rankData['ranking_page_id']][$rankData['source_id']] = $rankData['rank'];
					}
				}
			}
		}
		return $finalData;
	}

	private function getLatestRankingSourceIdMap(&$sourceRankPageData){
		$finalRankData = array();
		foreach ($sourceRankPageData as $sourceId => $value) {
			$finalRankData['source_id'][] = $sourceId;
			foreach ($value['pageId'] as $rpId) {
				$finalRankData['source_id_name_map']['source_name_id_map_'.$rpId][] = $value['publisher_name'][0].':'.$sourceId;
				$finalRankData['nl_course_ranking_page_id'][$rpId] = $rpId;
			}
		}
		return $finalRankData;
	}

	private function getLatestRankingSource(&$rankingSourceInfo, &$sourceRankPageData, $rankingPageId){
		$latestRankSource = array();
		foreach ($rankingSourceInfo as $key => $value) {
			if(!isset($latestRankSource[$value['publisher_name']]) || $latestRankSource[$value['publisher_name']]['year'] < $value['year']){
				$latestRankSource[$value['publisher_name']] = array('source_id'=>$value['source_id'], 'year'=>$value['year']);
				$sourceRankPageData[$value['source_id']]['pageId'][] = $rankingPageId;
				$sourceRankPageData[$value['source_id']]['publisher_name'][0] = $value['publisher_name'].'_'.$value['year'];
			}
		}
	}

	private function formatSourceWiseRanks(&$sourceWiseRanks, &$rankingPageCourseData, &$formattedData){
		foreach ($sourceWiseRanks as $key => $value) {
			$formattedData['source_'.$value['source_id'].'_'.$rankingPageCourseData[$value['ranking_page_course_id']]] = $value['rank'];
		}
	}
}
