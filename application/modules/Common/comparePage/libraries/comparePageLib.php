<?php 
class comparePageLib{
	public function __construct(){
		$this->CI = &get_instance();
	}
	public function processInput($courseIds, $maxCompares, $device = 'desktop'){
		$courseIds = $this->CI->security->xss_clean($courseIds);
		$courseIdArr = array();
		$isStaticPage = false;
		if($courseIds != ''){
			$courseIdArr = explode('-', $courseIds);
			if(count($courseIdArr) > 5 && $device == 'desktop' || count($courseIdArr) > 3 && $device == 'mobile'){
				$isStaticPage = true;
			}
			$courseIdArr = array_filter($courseIdArr, array($this, 'keepOnlyNumbers'));
			$courseIdArr = array_slice($courseIdArr, 0, $maxCompares);
			$courseIdArr2 = array_unique($courseIdArr);
			// remove duplicate courseId form url
			if(count($courseIdArr)>1 && (count($courseIdArr2) != count($courseIdArr))){
				$url = SHIKSHA_HOME.'/resources/college-comparison-'.implode('-',$courseIdArr2);
				redirect($url, 'location');exit;
			}
		}
		return array('courseIdArr' => $courseIdArr2, 'isStaticPage' => $isStaticPage);
	}

	private function keepOnlyNumbers($val){
		return is_numeric($val) && $val > 0 ? $val : false;
	}

	public function getCourseObjectsData(&$displayData){
		$this->CI->load->builder("nationalCourse/CourseBuilder");
		$builder = new CourseBuilder();
		$repo = $builder->getCourseRepository();
		
		$courseObjs = $repo->findMultiple($displayData['courseIdArr'], array('eligibility', 'location', 'academic', 'facility'));
		$courseObjs_setOrder = array();
		foreach ($displayData['courseIdArr'] as $courseId) {
			$tempCourseId = 0;
			if(is_object($courseObjs[$courseId])){
				$tempCourseId = $courseObjs[$courseId]->getId();
				$displayData['courseNameArr'][$courseId] = $courseObjs[$courseId]->getName();
			}
			if(empty($tempCourseId)){
				show_404();
				exit();
			}
			$courseObjs_setOrder[$courseId] = $courseObjs[$courseId];
		}
		$courseObjs = $courseObjs_setOrder;
		unset($courseObjs_setOrder);
		$displayData['courseObjs'] = $courseObjs;
		$displayData['currentCourseCount'] = count($courseObjs);
		$instIdArr = $instNameArr = array();
		foreach ($courseObjs as $crsId => $crsObj) {
			$displayData['hier'][$crsId] = $crsObj->getCourseTypeInformation();
			$displayData['instIdArr'][$crsId]   = $crsObj->getInstituteId();
			$displayData['instNameArr'][$crsId] = $crsObj->getInstituteName();
		}
	}

	public function getInstituteObjectsData(&$displayData){
		$this->CI->load->builder("nationalInstitute/InstituteBuilder");
	    $instituteBuilder = new InstituteBuilder();
	    $instituteRepo = $instituteBuilder->getInstituteRepository();
	    //$displayData['instituteObjs'] = $instituteRepo->findMultiple($displayData['instIdArr'], array('facility'));
            $instIds = array();
            foreach ($displayData['instIdArr'] as $key => $value){
                $instIds[] = $value;
            }
            if(!empty($instIds)){
                $displayData['instituteObjs'] = $instituteRepo->findMultiple($instIds, array('facility'));
            }

	    foreach ($displayData['instituteObjs'] as $instId => $instObj) {
	    	$displayData['instTypeArr'][$instId] = $instObj->getListingType();
	    }
	}

	public function getCourseAttributesData(&$displayData){
		$courseAttributeData = array();
		foreach ($displayData['courseIdArr'] as $key => $courseId) {
			$courseTypeInfo = $displayData['courseObjs'][$courseId]->getCourseTypeInformation();
			$courseTypeInfo = $courseTypeInfo['entry_course'];
			//get only the primary hierarchy
			foreach ($courseTypeInfo->getHierarchies() as $key => $value) {
				if($value['primary_hierarchy'] == 1){
					$courseAttributeData[$courseId] = array(
										'stream_id' => $value['stream_id'],
										'substream_id' => $value['substream_id'],
										'specialization_id' => $value['specialization_id']
										);
					break;
				}
			}
			$courseAttributeData[$courseId]['base_course_id'] = $courseTypeInfo->getBaseCourse();
		}
		$displayData['courseAttributeData'] = $courseAttributeData;
	}

    public function getDropdownCourseList(&$displayData){
    	$isFirstCourseId = true;
    	$courseLevelId = 0;
    	$firstCourseId = $displayData['courseIdArr'][0];
    	$this->CI->load->library('nationalInstitute/InstituteDetailLib');
    	$instituteDetailLib = new InstituteDetailLib();
    	$this->CI->load->library('nationalCourse/CourseDetailLib');
    	$courseDetailLib = new CourseDetailLib();
    	$this->CI->load->builder("nationalInstitute/InstituteBuilder");
	    $instituteBuilder = new InstituteBuilder();
	    $instituteRepo = $instituteBuilder->getInstituteRepository();
    	foreach ($displayData['courseIdArr'] as $courseId) {
    		$courseList[$courseId] = array();
    		if($isFirstCourseId){
    			$courseTypeInfo = $displayData['courseObjs'][$courseId]->getCourseTypeInformation();
    			$courseLevelObj = $courseTypeInfo['entry_course']->getCourseLevel();
    			if(!empty($courseLevelObj)){
    				$courseLevelId  = $courseLevelObj->getId();	
    			}
    			$courseList[$courseId] = $instituteDetailLib->getInstituteCourseIds($displayData['instIdArr'][$courseId], $displayData['instTypeArr'][$displayData['instIdArr'][$courseId]]);
    			$courseList[$courseId] = array_unique($courseList[$courseId]['courseIds']);
    		}else{
    			$coursesOnLevel = $courseDetailLib->getCoursesForInstitutesByLevel($displayData['instIdArr'][$courseId], $displayData['instTypeArr'][$displayData['instIdArr'][$courseId]], $courseLevelId);
    			if(empty($coursesOnLevel)){
    				$courseList[$courseId] = $instituteDetailLib->getInstituteCourseIds($displayData['instIdArr'][$courseId], $displayData['instTypeArr'][$displayData['instIdArr'][$courseId]]);
    				$courseList[$courseId] = $courseList[$courseId]['courseIds'];
    			}else{
    				foreach ($coursesOnLevel as $key => $value) {
    					$courseList[$courseId][] = $value['course_id'];
    				}
    			}

    			$courseList[$courseId] = array_unique($courseList[$courseId]);
    			
    		}
    		$instituteWithCourses = $instituteRepo->findWithCourses(array($displayData['instIdArr'][$courseId] => $courseList[$courseId]), array('basic'), array('basic'), array('setContactDetails' => false));
    		$instituteWithCoursesData[$courseId] = $instituteWithCourses;
    		$isFirstCourseId = false;
    	}
    	$displayData['instituteWithCoursesData'] = $instituteWithCoursesData;
    }

	public function getComparisionData(&$displayData){
		$this->CI->load->config('comparePage/compareConfig');
		$compareAttributes = $this->CI->config->item('compareAttributes');
		foreach ($compareAttributes as $attr => $label) {
			switch ($attr) {
				case 'academicUnit':
					$this->CI->benchmark->mark('libA');
					if($displayData['showAcademicUnitSection']){
						$this->getAcademicUnitCompareData($displayData);
					}
					$this->CI->benchmark->mark('libB');
					break;

				case 'rank':
					$this->CI->benchmark->mark('libC');
					$this->getInstituteRankCompareData($displayData);
					$this->CI->benchmark->mark('libD');
					break;

				case 'recognition':
					$this->CI->benchmark->mark('libE');
					$this->getCourseRecognitionCompareData($displayData);
					$this->CI->benchmark->mark('libF');
					break;

				case 'courseStatus':
					$this->CI->benchmark->mark('libG');
					$this->getCourseStatusCompareData($displayData);
					$this->CI->benchmark->mark('libH');
					break;

				case 'accreditation':
					$this->CI->benchmark->mark('libI');
					$this->getAccreditationCompareData($displayData);
					$this->CI->benchmark->mark('libJ');
					break;

				case 'alumniSalary':
					$this->CI->benchmark->mark('libK');
					$this->getAlumniSalaryCompareData($displayData);
					$this->CI->benchmark->mark('libL');
					break;

				case 'eligibility':
					$this->CI->benchmark->mark('libM');
					$this->getCourseEligibilityCompareData($displayData);
					$this->CI->benchmark->mark('libN');
					break;

				case 'courseFee':
					$this->CI->benchmark->mark('libO');
					$this->getCourseFeeCompareData($displayData);
					$this->CI->benchmark->mark('libP');
					break;

				case 'courseSeats':
					$this->CI->benchmark->mark('libQ');
					$this->getCourseSeatsCompareData($displayData);
					$this->CI->benchmark->mark('libR');
					break;

				case 'courseDuration':
					$this->CI->benchmark->mark('libS');
					$this->getCourseDurationCompareData($displayData);
					$this->CI->benchmark->mark('libT');
					break;

				case 'facilities':
					$this->CI->benchmark->mark('libU');
					$this->getInstFacilityCompareData($displayData);
					$this->CI->benchmark->mark('libV');
					break;

				case 'userInterest':
					$this->CI->benchmark->mark('libW');
					$this->courseShortlistWidget($displayData);
					$this->CI->benchmark->mark('libX');
					$this->askQuestionWidget($displayData);
					$this->CI->benchmark->mark('libY');
					break;
			}
		}
		error_log('::ComparePageLib::AB::'.$this->CI->benchmark->elapsed_time('libA', 'libB'));
		error_log('::ComparePageLib::CD::'.$this->CI->benchmark->elapsed_time('libC', 'libD'));
		error_log('::ComparePageLib::EF::'.$this->CI->benchmark->elapsed_time('libE', 'libF'));
		error_log('::ComparePageLib::GH::'.$this->CI->benchmark->elapsed_time('libG', 'libH'));
		error_log('::ComparePageLib::IJ::'.$this->CI->benchmark->elapsed_time('libI', 'libJ'));
		error_log('::ComparePageLib::KL::'.$this->CI->benchmark->elapsed_time('libK', 'libL'));
		error_log('::ComparePageLib::MN::'.$this->CI->benchmark->elapsed_time('libM', 'libN'));
		error_log('::ComparePageLib::OP::'.$this->CI->benchmark->elapsed_time('libO', 'libP'));
		error_log('::ComparePageLib::QR::'.$this->CI->benchmark->elapsed_time('libQ', 'libR'));
		error_log('::ComparePageLib::ST::'.$this->CI->benchmark->elapsed_time('libS', 'libT'));
		error_log('::ComparePageLib::UV::'.$this->CI->benchmark->elapsed_time('libU', 'libV'));
		error_log('::ComparePageLib::WX::'.$this->CI->benchmark->elapsed_time('libW', 'libX'));
		error_log('::ComparePageLib::XY::'.$this->CI->benchmark->elapsed_time('libX', 'libY'));
	}

	private function getAcademicUnitCompareData(&$displayData){
	    $institutesToShow = array();
	    $headerInstitutes = array();
	    foreach ($displayData['academicUnitRawData'] as $courseId => $instRawData) {
	    	$headerInstitutes[$courseId] = $instRawData['userSelectedInstitute'];
	    	$institutesToShow[$courseId] = $instRawData['primaryInstitute'];
	    }
	    $institutesToShowUnique = array_unique($institutesToShow);
	    $headerInstitutes = array_diff($headerInstitutes, $displayData['instIdArr']);
	    $instObjs = array();
	    if(!empty($headerInstitutes)){
	    	$this->CI->load->builder("nationalInstitute/InstituteBuilder");
		    $instituteBuilder = new InstituteBuilder();
		    $instituteRepo = $instituteBuilder->getInstituteRepository();
	    	$instObjs = $instituteRepo->findMultiple($headerInstitutes, array('basic'));
	    }
	    $displayData['instituteObjs'] = $displayData['instituteObjs'] + $instObjs;
	    $displayData['compareData']['academicUnit'] = array();
	    foreach ($institutesToShow as $courseId => $academicUnitId) {
	    	$displayData['compareData']['academicUnit'][$courseId] = $displayData['instituteObjs'][$academicUnitId]->getName();
	    }
	}

	private function getInstituteRankCompareData(&$displayData){
		$this->CI->load->library('rankingV2/RankingCommonLibv2');
		$RankingCommonLibv2 = new RankingCommonLibv2();
		$rankData = $RankingCommonLibv2->getCourseRankBySource($displayData['courseIdArr'], true);
		// get ranking page url
		$rankingPageIds = array();
		if(is_array($rankData) && count($rankData) > 0){
			foreach ($rankData as $courseId => $rankDetails) {
				foreach ($rankDetails as $rankSource => $sourceDetails) {
					$rankingPageIds[$courseId] = $sourceDetails['rankingPageId'];
				}
			}
		}
		$this->CI->load->builder('RankingPageBuilder', RANKING_PAGE_MODULE);
		$RankingURLManager  = RankingPageBuilder::getURLManager();
		$rankingPageUrl = array();
		foreach ($rankingPageIds as $courseId => $rankingPageId) {
			$courseCityId = $displayData['courseObjs'][$courseId]->getMainLocation();
			if($courseCityId){
				$courseCityId = $courseCityId->getCityId();
				$pageIdentifier = $rankingPageId."-2-0-".$courseCityId."-0";
				$RankingPageRequest = $RankingURLManager->getRankingPageRequest($pageIdentifier);
				$rankingPageUrl[$courseId][$rankingPageId] = SHIKSHA_HOME.'/'.$RankingURLManager->buildURL($RankingPageRequest);
			}
		}
		foreach ($displayData['courseIdArr'] as $key => $courseId) {
			if(empty($rankData[$courseId])){
				$rankData[$courseId] = array();
			}
		}
		$formatedRankData = $this->_formatRankingData($rankData);
		$rankDataWithUrl = array(
			'rankData' 	=> $formatedRankData,
			'rankingPageUrl'		=> $rankingPageUrl
			);
		$displayData['compareData']['ranks'] = $rankDataWithUrl;
	}

	private function _formatRankingData($rankData){
		$rankDataFormated = array();
		$sourceArray = array();
		$courseIdSourceMap = array();
		$courseIds = array();
		foreach($rankData as $courseId => $rankDetails) {
			foreach($rankDetails as $sourceName => $sourceDetails) {
				$sourceArray[$sourceName] = 0;
				$courseIdSourceMap[$courseId][] = $sourceName;
			}
			$courseIds[] = $courseId;
		}
		$sourceArray = array_keys($sourceArray);
		foreach($sourceArray as $key => $sourceName) {
			foreach($courseIds as $courseId) {
				if(in_array($sourceName, $courseIdSourceMap[$courseId])) {
					$rankDataFormated[$courseId][$sourceName] = $rankData[$courseId][$sourceName];
				}else{
					$rankDataFormated[$courseId][$sourceName] = array(
						'rankingPageId' => 0,
						'rank' => 'NA'
						);
				}
			}
		}
		
		return $rankDataFormated;
		
	}

	private function getCourseRecognitionCompareData(&$displayData){
		$hasNoCompareData = true;
		foreach ($displayData['courseIdArr'] as $key => $courseId) {
			if($displayData['courseObjs'][$courseId]->getRecognition()){
				$recognitions = $displayData['courseObjs'][$courseId]->getRecognition();
				foreach ($recognitions as $recognition) {
					$displayData['compareData']['recognition'][$courseId][] = $recognition->getName();
				}
				$hasNoCompareData = false;
			}else{
				$displayData['compareData']['recognition'][$courseId] = array();
			}
		}
		if($hasNoCompareData){
			unset($displayData['compareData']['recognition']);
		}
	}

	private function getCourseStatusCompareData(&$displayData){
		$this->CI->load->library('nationalCourse/CourseDetailLib');
	    $this->lib1 = new CourseDetailLib;
	    $courseStatus = $this->lib1->getCourseStatus($displayData['courseIdArr'], $displayData['courseObjs']);
	    $displayData['compareData']['courseStatus'] = array();
	    foreach ($courseStatus as $courseId => $courseSts) {
	    	if(!empty($courseSts['courseStatusDisplay'])){
	    		$displayData['compareData']['courseStatus'][$courseId] = $courseSts['courseStatusDisplay'];
	    	}
	    }
	}

	private function getAccreditationCompareData(&$displayData){
		foreach ($displayData['courseIdArr'] as $key => $courseId) {
			$instId = $displayData['instIdArr'][$courseId];
			$accreditation = $displayData['instituteObjs'][$instId]->getAccreditation();
			$nbaAccr = $displayData['courseObjs'][$courseId]->isNBAAccredited();
			if($nbaAccr === true){
				$displayData['compareData']['accreditation'][$courseId][] = 'NBA Accredited';
			}
			if(!empty($accreditation)){
				$displayData['compareData']['accreditation'][$courseId][] = 'NAAC Accredited (Grade '.$accreditation.')';
			}
		}
	}

	private function getAlumniSalaryCompareData(&$displayData){
		$hasNoAlumniSalaryData = true;
		foreach ($displayData['courseIdArr'] as $key => $courseId) {
		  $naukriDataIns[$courseId] = array('institute'=>$displayData['courseObjs'][$courseId]->getInstituteId());
		}

		$salaryData = $this->createBarChart($naukriDataIns, false);

		foreach ($displayData['courseIdArr'] as $key => $courseId) {
			if(!empty($salaryData['data'][$courseId])){
				$displayData['compareData']['alumniSalary'][$courseId] = $salaryData['data'][$courseId];
				$hasNoAlumniSalaryData = false;
			}else{
				$displayData['compareData']['alumniSalary'][$courseId] = array();
			}
		}
		if($hasNoAlumniSalaryData){
			unset($displayData['compareData']['alumniSalary']);
		}
	}

	private function getCourseEligibilityCompareData(&$displayData){
		$hasNoCompareData = true;
		foreach ($displayData['courseIdArr'] as $key => $courseId) {
			$genEligibilty = $displayData['courseObjs'][$courseId]->getEligibility(array('general'));
			if(!empty($genEligibilty)){
				$displayData['compareData']['eligibility'][$courseId] = $genEligibilty['general'];
				$hasNoCompareData = false;
			}else{
				$displayData['compareData']['eligibility'][$courseId] = array();
			}
		}
		if($hasNoCompareData){
			unset($displayData['compareData']['eligibility']);
		}
		$displayData['compareData']['eligibility'] = $this->_formatEligibiltyData($displayData['compareData']['eligibility']);
	}

	private function _formatEligibiltyData($courseWiseEligibilities){
		$finalEligData = array();
		foreach ($courseWiseEligibilities as $courseId => $eligibilities) {
			if(!empty($eligibilities)){
				foreach ($eligibilities as $key => $eligibility) {
					switch ($eligibility->getUnit()) {
						case 'percentage':
							$finalEligData[$courseId][] = array('examName' => $eligibility->getExamName(), 'examCutOff' => $eligibility->getValue(), 'unit' => '%');
							break;

						case 'percentile':
							$finalEligData[$courseId][] = array('examName' => $eligibility->getExamName(), 'examCutOff' => $eligibility->getValue(), 'unit' => '%ile');
							break;

						case 'rank':
							$finalEligData[$courseId][] = array('examName' => $eligibility->getExamName(), 'examCutOff' => $eligibility->getValue(), 'unit' => 'Rank');
							break;
						
						default:
							$finalEligData[$courseId][] = array('examName' => $eligibility->getExamName(), 'examCutOff' => $eligibility->getValue(), 'unit' => 'Mark');
							break;
					}
				}
			}else{
				$finalEligData[$courseId] = array();
			}
		}
		return $finalEligData;
	}

	private function getCourseFeeCompareData(&$displayData){
		$hasNoCompareData = true;
		foreach ($displayData['courseIdArr'] as $key => $courseId) {
			if($displayData['courseObjs'][$courseId]->getFees()){
				$displayData['compareData']['courseFee'][$courseId] = $displayData['courseObjs'][$courseId]->getFees();
				$hasNoCompareData = false;
			}else{
				$displayData['compareData']['courseFee'][$courseId] = array();
			}
		}
		if($hasNoCompareData){
			unset($displayData['compareData']['courseFee']);
		}
	}

	private function getCourseSeatsCompareData(&$displayData){
		foreach ($displayData['courseIdArr'] as $key => $courseId) {
			$totalSeats = $displayData['courseObjs'][$courseId]->getTotalSeats();
			if(!empty($totalSeats)){
				$displayData['compareData']['courseSeats'][$courseId] = $totalSeats;
			}
		}
	}

	private function getCourseDurationCompareData(&$displayData){
		foreach ($displayData['courseIdArr'] as $key => $courseId) {
			$duration = $displayData['courseObjs'][$courseId]->getDuration();
			if(!empty($duration)){
				$displayData['compareData']['courseDuration'][$courseId] = $duration;
			}
		}
	}

	private function getInstFacilityCompareData(&$displayData){
		$hasNoCompareData = true;
		$this->CI->load->config('comparePage/compareConfig');
		$displayData['compareFacilities'] = $compareFacilities = $this->CI->config->item('compareFacilities');
		$displayData['compareExtraFacilities'] = $compareExtraFacilities = $this->CI->config->item('compareExtraFacilities');
	    $compareFacilitiesIDs = array_keys($compareFacilities);
	    $compareExtraFacilitiesIDs = array_keys($compareExtraFacilities);
	    $collegeHostelFacilities = array();
	    foreach ($displayData['courseIdArr'] as $key => $courseId) {
	    	
			$instId = $displayData['instIdArr'][$courseId];
			$facilities = $displayData['instituteObjs'][$instId]->getFacilities();
			if(!empty($facilities)){
				$hasNoCompareData = false;
				$collegeFacilities = array();
				$collegeFacilitiesFinalList = array();
				$collegeExtraFacilitiesFinalList = array();
				foreach ($facilities as $key => $facility) {
					if($facility->getFacilityId() == OTHERS_FACILITY_ID){
						$childFacilities = $facility->getChildFacilities();
						foreach ($childFacilities as $childFacility) {
							if($childFacility->getFacilityStatus()){
								$collegeFacilities[$courseId][$childFacility->getFacilityId()] = 1;
							}else{
								$collegeFacilities[$courseId][$childFacility->getFacilityId()] = 0;
							}
						}
					}else if($facility->getFacilityId() == HOSTEL_FACILITY_ID){
						if($facility->getFacilityStatus()){
							$collegeFacilities[$courseId][$facility->getFacilityId()] = 1;

							$childFacilities = $facility->getChildFacilities();
							foreach ($childFacilities as $childFacility) {
								if($childFacility->getFacilityStatus()){
									$collegeHostelFacilities[$courseId][$childFacility->getFacilityName()] = 1;
								}else{
									$collegeHostelFacilities[$courseId][$childFacility->getFacilityName()] = 0;
								}
							}
						}else{
							$collegeFacilities[$courseId][$facility->getFacilityId()] = 0;
						}
					}else{
						if($facility->getFacilityStatus()){
							$collegeFacilities[$courseId][$facility->getFacilityId()] = 1;
						}else{
							$collegeFacilities[$courseId][$facility->getFacilityId()] = 0;
						}
					}
				}
				foreach ($compareFacilitiesIDs as $id) {
					if(isset($collegeFacilities[$courseId][$id]) && $collegeFacilities[$courseId][$id] == 1){
						$collegeFacilitiesFinalList[] = array($compareFacilities[$id], 'yes', $id);
					}else if(isset($collegeFacilities[$courseId][$id]) && $collegeFacilities[$courseId][$id] == 0){
						$collegeFacilitiesFinalList[] = array($compareFacilities[$id], 'no', $id);
					}else{
						$collegeFacilitiesFinalList[] = array($compareFacilities[$id], 'noInfo', $id);
					}
				}
				foreach ($compareExtraFacilitiesIDs as $id2) {
					if(isset($collegeFacilities[$courseId][$id2]) && $collegeFacilities[$courseId][$id2] == 1){
						$collegeExtraFacilitiesFinalList[] = array($compareExtraFacilities[$id2], 'yes', $id2);
					}else if(isset($collegeFacilities[$courseId][$id2]) && $collegeFacilities[$courseId][$id2] == 0){
						$collegeExtraFacilitiesFinalList[] = array($compareExtraFacilities[$id2], 'no', $id2);
					}else{
						$collegeExtraFacilitiesFinalList[] = array($compareExtraFacilities[$id2], 'noInfo', $id2);
					}
				}
				$displayData['compareData']['facilities']['collegeFacilitiesFinalList'][$courseId] = $collegeFacilitiesFinalList;
				if(!empty($collegeExtraFacilitiesFinalList)){
					$displayData['compareData']['facilities']['collegeExtraFacilitiesFinalList'][$courseId] = $collegeExtraFacilitiesFinalList;
				}
			}else{
				$displayData['compareData']['facilities']['collegeFacilitiesFinalList'][$courseId] = array();
			}
		}
		if($hasNoCompareData){
			unset($displayData['compareData']['facilities']);
		}
		$extraFacilityData = array();
		$collegeFacilityData = array();
		$collegeFacilitiesFinalList = $displayData['compareData']['facilities']['collegeFacilitiesFinalList'];
		for ($i=0; $i < count($compareFacilities); $i++) {
			foreach ($displayData['courseIdArr'] as $courseId) {
				if($collegeFacilitiesFinalList[$courseId][$i][1] == 'yes'){
					$collegeFacilityData[$collegeFacilitiesFinalList[$courseId][$i][0]][$courseId] = 1;
				}else if($collegeFacilitiesFinalList[$courseId][$i][1] == 'no'){
					$collegeFacilityData[$collegeFacilitiesFinalList[$courseId][$i][0]][$courseId] = 0;
				}else if(isset($collegeFacilitiesFinalList[$courseId][$i][0])){
					$collegeFacilityData[$collegeFacilitiesFinalList[$courseId][$i][0]][$courseId] = -1;
				}
			}
		}
		$temp = array();
		foreach ($collegeFacilityData as $facilityName => $facilitySts) {
			$unsetVar = false;
			foreach ($facilitySts as $cid => $sts) {
				if($sts != '-1'){
					$unsetVar = true;
					break;
				}
			}
			if($unsetVar){
				$temp[$facilityName] = $facilitySts;
			}
		}
		$displayData['compareData']['facilities']['collegeFacilitiesFinalList'] = $temp;

		$collegeExtraFacilitiesFinalList = $displayData['compareData']['facilities']['collegeExtraFacilitiesFinalList'];
		for ($i=0; $i < count($compareExtraFacilities); $i++) { 
			foreach ($displayData['courseIdArr'] as $courseId) {
				if($collegeExtraFacilitiesFinalList[$courseId][$i][1] == 'yes'){
					$extraFacilityData[$collegeExtraFacilitiesFinalList[$courseId][$i][0]][$courseId] = 1;
				}else if(isset($collegeExtraFacilitiesFinalList[$courseId][$i][0])){
					$extraFacilityData[$collegeExtraFacilitiesFinalList[$courseId][$i][0]][$courseId] = 0;
				}
			}
		}
		$temp = array();
		foreach ($extraFacilityData as $facilityName => $facilitySts) {
			$unsetVar = false;
			foreach ($facilitySts as $cid => $sts) {
				if($sts != '0'){
					$unsetVar = true;
					break;
				}
			}
			if($unsetVar){
				$temp[$facilityName] = $facilitySts;
			}
		}
		$displayData['compareData']['facilities']['collegeExtraFacilitiesFinalList'] = $temp;
		$displayData['collegeHostelFacilities'] = $this->_formatHostelFacilities($collegeHostelFacilities);
	}

	private function _formatHostelFacilities($collegeHostelFacilities){
		$filterCollegeHostelFacilities = array();
		foreach ($collegeHostelFacilities as $courseId => $value) {
			foreach ($value as $key => $val) {
				if($val != '0'){
					$filterCollegeHostelFacilities[$courseId][] = $key;
				}
			}
		}
		return $filterCollegeHostelFacilities;
	}

	private function courseShortlistWidget(&$displayData){}

	private function askQuestionWidget(&$displayData){}

	public function getPopularCoursesForComparision($baseEntityArray = array(),$source='desktop'){
        $displayData = array();
        if(is_array($baseEntityArray) && count($baseEntityArray)){
        	$this->CI->load->model('comparePage/comparepagemodel');
			$this->comparePageModel = new comparepagemodel;
            foreach ($baseEntityArray as $key => $baseEntities){
                if(is_array($baseEntities) && count($baseEntities)){
                    $baseEntityArrayForCourses = array(
                        'stream_id'         => intval($baseEntities['stream_id'])?$baseEntities['stream_id']:0,
                        'substream_id'      => intval($baseEntities['substream_id'])?$baseEntities['substream_id']:0,
                        'specialization_id' => intval($baseEntities['specialization_id'])?$baseEntities['specialization_id']:0,
                        'base_course_id'    => intval($baseEntities['base_course_id'])?$baseEntities['base_course_id']:0,
                    );
                    $popularList=$this->comparePageModel->comparisionOfPopularCourses($baseEntityArrayForCourses,$source);
                    if(is_array($popularList) && count($popularList)){
                        $displayData = array();
                        $displayData['baseEntityName'] = $this->getBaseEntityName($baseEntityArrayForCourses);          
                        $displayData['popularList']=$popularList;
                        return $displayData;
                        break;
                    }
                }
            }
        }
    }

    public function getBaseEntityName($baseEntities){
        $this->CI->load->builder('ListingBaseBuilder', 'listingBase');
        $listingBaseBuilder   = new ListingBaseBuilder();
        $getBaseEntityName = false;
        $baseEntityName = '';
        if(intval($baseEntities['base_course_id']) && $baseEntities['base_course_id'] > 0){ 
            $basecourseRepository = $listingBaseBuilder->getBaseCourseRepository();
            $baseCourseObj = $basecourseRepository->find($baseEntities['base_course_id']);
            if($baseCourseObj->getIsPopular() == 1){
                if($baseCourseObj->getAlias() || $baseCourseObj->getName()){
                    $baseEntityName = $baseCourseObj->getAlias() ? $baseCourseObj->getAlias() : $baseCourseObj->getName();
                    $getBaseEntityName = true;
                }
            }
        }

        if(!$getBaseEntityName && intval($baseEntities['substream_id']) && $baseEntities['substream_id'] > 0){
            $subStreamRepository     = $listingBaseBuilder->getSubstreamRepository();
            $subStreamObj = $subStreamRepository->find($baseEntities['substream_id']);
            if($subStreamObj->getAlias() || $subStreamObj->getName()){
                $baseEntityName = $subStreamObj->getAlias() ? $subStreamObj->getAlias() : $subStreamObj->getName();
                $getBaseEntityName = true;
            }
        }

        if(!$getBaseEntityName && intval($baseEntities['stream_id']) && $baseEntities['stream_id'] > 0){
            $streamRepository     = $listingBaseBuilder->getStreamRepository();
            $streamObj = $streamRepository->find($baseEntities['stream_id']);
            if($streamObj->getAlias() || $streamObj->getName()){
                $baseEntityName = $streamObj->getAlias() ? $streamObj->getAlias() : $streamObj->getName();
                $getBaseEntityName = true;
            }
        }
        return $baseEntityName;
    }

    function getCourseListOnLevel($firstSelectedCourseId, $instituteId, $listingType){
    	//1- get course level like UG/PG
		$this->CI->load->builder("nationalCourse/CourseBuilder");
        $builder = new CourseBuilder();
        $courseRepo         = $builder->getCourseRepository();
		$course 			= $courseRepo->find($firstSelectedCourseId);
		
		$courseTypeObj   	= $course->getCourseTypeInformation();
		$courseLeveObj  	= $courseTypeObj['entry_course']->getCourseLevel();
		
		if(!empty($courseLeveObj)){
			$courseLevelId  = $courseLeveObj->getId();
		}
		//2- get all course of institute based on first course level
		$this->CI->load->library("nationalCourse/CourseDetailLib");
		$lib = new CourseDetailLib(); 
		return $lib->getCoursesForInstitutesByLevel($instituteId, $listingType, $courseLevelId);
    }

      public function getAlsoViewedCourses($courseIdArr,$count='5'){
                $instituteIdArr = array();
		$courseIds = array();
                $instituteObjects = '';
                $showRecommendation = false;
		$instituteToCourseMapping = array();
		$cityName = array();
                $this->CI->load->library('recommendation/alsoviewed');
                $instituteCourseIdsArray = $this->CI->alsoviewed->getAlsoViewedCourses($courseIdArr, $count, array());
                foreach($instituteCourseIdsArray as $arr){
                       $instituteIdArr[] = $arr['instituteId'];
		       $instituteToCourseMapping[$arr['courseId']] = $arr['instituteId'];
		       $courseIds[] =  $arr['courseId'];
                }
                if(!empty($instituteIdArr)){
			$this->CI->load->builder("InstituteBuilder", "nationalInstitute");
		        $instituteBuilder   = new InstituteBuilder();
			//_p($instituteBuilder); exit();
		        $instituteRepo      = $instituteBuilder->getInstituteRepository();
                        $instituteObjects   = $instituteRepo->findMultiple($instituteIdArr);
			
			
                        if($instituteObjects){
                                $showRecommendation = true;
                        }

			$this->CI->load->builder("nationalCourse/CourseBuilder");
	                $courseBuilder = new CourseBuilder();
        	        $courseRepo = $courseBuilder->getCourseRepository();
			$courseObjects = $courseRepo->findMultiple($courseIds);
			foreach($courseObjects as $courseObj){
				if($courseObj->getId()!='' && $courseObj->getMainLocation()!=''){
					$courseInfo[$instituteToCourseMapping[$courseObj->getId()]]['cityName'] = $courseObj->getMainLocation()->getCityName();					  $courseInfo[$instituteToCourseMapping[$courseObj->getId()]]['courseId'] = $courseObj->getId();
				}
			}
                }
                $data['showRecommendation'] = $showRecommendation;
                $data['instituteObjects']   = $instituteObjects;
				$data['courseInfo']         = $courseInfo;
                return $data;
    }

    public function getCampusRepsForCompareTool(&$displayData){		
		$this->CI->load->model('CA/cadiscussionmodel');
		$this->cadiscussionmodel = new CADiscussionModel();
		$campusRepArray = array();
		foreach($displayData['courseIdArr'] as $courseId){
			$instituteId = $displayData['courseObjs'][$courseId]->getInstituteId();
			//$url = Modules::run('CA/CADiscussions/getCourseUrl', $courseId);
			$url = $displayData['courseObjs'][$courseId]->getURL();
			$campusRepArray['courses'][]   = $courseId;
			$campusRepArray['institute'][] = $instituteId;
			$campusRepArray['courseUrl'][] = $url;
			$campusRepArray[] = $this->cadiscussionmodel->getCampusRepInfoForCourse(array($courseId), "course" ,$instituteId,1);
		}
		return $campusRepArray;
	}

	public function processAcademicUnitCookie(&$displayData){
		$cookieVal = $_COOKIE['cmpAcadUnit'];
		$instAndCourseArr = array();
		if(!empty($cookieVal)){
			$instAndCourseArr = explode('|', $cookieVal);
			foreach ($instAndCourseArr as $instAndCourse) {
				$temp = explode('-', $instAndCourse);
				$temp[0] = trim($temp[0]);
				$temp[1] = trim($temp[1]);
				if(in_array($temp[1], $displayData['courseIdArr'])){
					$instituteToShow[$temp[1]]['userSelectedInstitute'] = $temp[0];
					$instituteToShow[$temp[1]]['primaryInstitute'] = $displayData['courseObjs'][$temp[1]]->getInstituteId();
				}
			}
		}
		$newCookie = '';
		$newCookieArr = array();
		$displayData['showAcademicUnitSection'] = false;
		foreach ($displayData['courseIdArr'] as $courseId) {
			if(empty($instituteToShow[$courseId])){
				$instituteToShow[$courseId]['userSelectedInstitute'] = $instituteToShow[$courseId]['primaryInstitute'] = $displayData['instIdArr'][$courseId];
			}
			if($instituteToShow[$courseId]['userSelectedInstitute'] != $instituteToShow[$courseId]['primaryInstitute']){
				$displayData['showAcademicUnitSection'] = true;
			}
			$newCookieArr[] = $instituteToShow[$courseId]['userSelectedInstitute'].'-'.$courseId;
		}
		if(!empty($newCookieArr)){
			if($displayData['showAcademicUnitSection']){
				$displayData['academicUnitRawData'] = $instituteToShow;
			}
			$displayData['academicUnitCookieData'] = $instituteToShow;
			$newCookie = implode('|', $newCookieArr);
			$this->resetAcademicUnitCookie($newCookie);
		}
	}

	private function resetAcademicUnitCookie($value){
		setcookie('cmpAcadUnit', $value, (time() + 24*60*60), '/', COOKIEDOMAIN);
	}

	public function removeAcademicUnitCookie(){
		setcookie('cmpAcadUnit', '', (time() - 1), '/', COOKIEDOMAIN);
	}

	function createBarChart($naukriDataIns, $loadView = true){
        	// added by virender
			// desciption : manage naurki data based on course subcategory allow only 23 (full time mba)
			if(count($naukriDataIns)>0){
				$this->naukridatagraph = $this->CI->load->library('mNaukriTool5/NaukriDataGraph');
				$salaryDataResults = $this->naukridatagraph->prepareNaukriDataGraph($naukriDataIns);
			}
			$this->CI->load->model('nationalInstitute/institutedetailsmodel');
			$institutedetailsmodel = new institutedetailsmodel;
			$data = array();

			$avgSalaryDataResults = $institutedetailsmodel->getAverageNaukriSalaryData('2-5');
			$total_employees = 0;
			$noDataFound = true;
			$bucketForExp = array();
			foreach($salaryDataResults as $key=>$value) {
                foreach($value as $k=>$salaryData){
                    $bucketForExp[$key]['exp'][]    = $salaryData['exp_bucket'];
                    $bucketForExp[$key]['instName']   = $salaryData['institute_name'];
                    $bucketForExp[$key]['instId']   = $salaryData['instId'];
                }
            }
			
			foreach($salaryDataResults as $key=>$value) { 
                if(!in_array('2-5',$bucketForExp[$key]['exp'])){
                    $NoOfEmployees_bucket2 = $salaryData['tot_emp'];
                    $data[$key]['Exp_Bucket'] = '2-5';
                    $data[$key]['AvgCTC'] = 0;
                    $data[$key]['totalAvg'] = $avgSalaryDataResults;
                    $data[$key]['institute_name'] = $bucketForExp[$key]['instName'];
                    $data[$key]['instId'] = $bucketForExp[$key]['instId'];
                }
			    foreach($value as $k=>$salaryData){
					$total_employees = $total_employees + $salaryData['tot_emp'];
					if($salaryData['exp_bucket'] == '2-5') {
					    $NoOfEmployees_bucket2 = $salaryData['tot_emp'];
					    $data[$key]['Exp_Bucket'] = $salaryData['exp_bucket'];
					    $data[$key]['AvgCTC'] = $salaryData['ctc50'];
					    $data[$key]['totalAvg'] = $avgSalaryDataResults;
					    $data[$key]['institute_name'] = $salaryData['institute_name'];
					    $data[$key]['instId'] = $salaryData['instId'];
					    $noDataFound = false;
					    break;
					}
					if($salaryData['exp_bucket'] ==''){
					    $NoOfEmployees_bucket2 = $salaryData['tot_emp'];
					    $data[$key]['Exp_Bucket'] = '2-5';
					    $data[$key]['AvgCTC'] = 0;
					    $data[$key]['totalAvg'] = $avgSalaryDataResults;
					    $data[$key]['institute_name'] = $salaryData['institute_name'];
					    $data[$key]['instId'] = $salaryData['instId'];
					}
			    }
			}

			$response = array();
			$response['data'] = $this->naukridatagraph->manageCourseIndex($naukriDataIns, $data); // return final naukri graph data
			if($loadView){
				$this->load->view('naukriData',$response);//will be using in future
            }else{
            	return $response;
            }
    }

    function migrateOrDeletePopularCourseComparisionMappingForCourse($oldCourse, $newCourse, $dbHandle){
        if(empty($oldCourse)){
            return 'courseId can\'t be blank,not able to Migrate / Delete Popular Course Comparision Mapping.';
        }

        if($oldCourse <=0){
            return 'courseId is not valid,not able to Migrate / Delete Popular Course Comparision Mapping.';
        }

        $this->comparePageModel = $this->CI->load->model('comparePage/comparepagemodel');
        $result = $this->comparePageModel->checkIfPopularCourseComparisionMappingExist($oldCourse, $dbHandle);
        if($result == true){
        	if(empty($newCourse)){
	            $response = $this->comparePageModel->deletePopularCourseComparisionMappingForCourse($oldCourse, $dbHandle);
	        }else{
	        	if($newCourse <=0){
	            	return 'new course is not valid.Not able to Migrate / Delete Popular Course Comparision Mapping.';
	            }else{
	            	// load course obj for new courses.
	            	$this->CI->load->builder("nationalCourse/CourseBuilder");
				    $courseBuilder = new CourseBuilder();
				    $courseRepo = $courseBuilder->getCourseRepository();
				    $courseObj = $courseRepo->find($newCourse);

				    if(!empty($courseObj)){
				    	$courseId = $courseObj->getId();	
				    }
				    
				    if(empty($courseId)){
				    	return 'course object is blank for courseId = '.$newCourse.'while migrating Popular Course Comparision Mapping';
				    }else{
				    	$newCourseDetails = array(
				    		'courseName' => $courseObj->getName(),
				    		'courseCityName' => $courseObj->getMainLocation()->getCityName()
				    		);
				    	$response = $this->comparePageModel->migratePopularCourseComparisionMappingForCourse($oldCourse,$newCourse,$newCourseDetails, $dbHandle);
				    }
	            }
	        }

	        if($response){
	            return 'Popular Course Comparision Mapping is migrated / deleted.';
	        }else{
	            return 'Popular Course Comparision is not migrated / deleted.';
	        }
        }else{
        	return 'Popular course comparision migration not applicable.';
        }
    }

    // get compared data by user
    function getComparedData($fromWhere){
    	$courseIdBucket = array();
    	$cmpDataStr     = empty($fromWhere) ? $_COOKIE['compare-global-data'] : $_COOKIE['mob-compare-global-data'];
		if(!empty($cmpDataStr)){
			$cookieArr = explode('|',$cmpDataStr);
			for($i = 0; $i<count($cookieArr); $i++){
				if($cookieArr[$i] != ''){ 
		            $strArr      = explode('::',$cookieArr[$i]); //course::tracking_key::instituteId
		            if(is_numeric($strArr[0]) && !empty($strArr[0])){
		            	$courseIdBucket[$strArr[0]]    = array('trackeyId'=>$strArr[1],'instituteId'=>$strArr[2]);
		            }
		        }
			}
		}
		return $courseIdBucket;
    }

    function prepareBeaconTrackData($pageIdentifier,$courseIds,$courseObjs){
    	if(!empty($courseIds)){
			$mainCourse = $courseIds[0];
			$mainCourseObj = $courseObjs[$mainCourse];
		}
    	$onlineFormUtilityLib = $this->CI->load->library('Online/OnlineFormUtilityLib');
		$beaconTrackData = $onlineFormUtilityLib->prepareBeaconTrackDataForCourse($pageIdentifier,$mainCourse,$mainCourseObj);
		if(!empty($courseIds)){
			$index = 1;
			foreach ($courseObjs as $courseId => $courseObj) {
				$beaconTrackData['extraData']['compareInstituteID'.$index] = $courseObj->getInstituteId();
				$beaconTrackData['extraData']['compareInstituteName'.$index] = $courseObj->getInstituteName();
				$beaconTrackData['extraData']['compareCourseID'.$index] = $courseObj->getId();
				$index ++;
			}
		}
		return $beaconTrackData;
    }

    function prepareInstArrayForCompareGTM($beaconTrackData){
    	$instArr = array($beaconTrackData['extraData']['compareInstituteID1'],$beaconTrackData['extraData']['compareInstituteID2'],$beaconTrackData['extraData']['compareInstituteID3'],$beaconTrackData['extraData']['compareInstituteID4']);
    	return array_filter($instArr);
    }

    	function getGTMArray($beaconTrackData , $instForGTM){
		foreach ($beaconTrackData['extraData']['hierarchy'] as $key => $value) {
			if($value['streamId'] != ''){
				$stream[] = $value['streamId'];	
			}
			if($value['substreamId'] != ''){
				$substream[] = $value['substreamId'];	
			}
			if($value['specializationId'] != ''){
				$specialization[] = $value['specializationId'];	
			}
		}
		if(is_array($beaconTrackData['extraData']['baseCourseId'])){
			$baseCourseId = implode(',', $beaconTrackData['extraData']['baseCourseId']);
		}else{
			$baseCourseId = $beaconTrackData['extraData']['baseCourseId'];			
		}
		if(is_array($beaconTrackData['extraData']['cityId'])){	
			$cityIds = implode(',', $beaconTrackData['extraData']['cityId']);	
		}else{
			$cityIds = $beaconTrackData['extraData']['cityId'];			
		}
		if(is_array($beaconTrackData['extraData']['stateId'])){	
			$stateIds = implode(',', $beaconTrackData['extraData']['stateId']);	
		}else{
			$stateIds = $beaconTrackData['extraData']['stateId'];			
		}
	//	_p($beaconTrackData);die;
        $gtmParams = array(
        "pageType" => $beaconTrackData['pageIdentifier'],
     	"stream"=>implode(',',array_unique($stream)),
	 	"substream"=>implode(',',array_unique($substream)),
	 	"specialization"=>implode(',',array_unique($specialization)),
	 	"instituteId"=>implode(',',$instForGTM),
	 	"baseCourseId"=>$baseCourseId,
	 	"cityId" => $cityIds,
        "stateId" => $stateIds,
        "countryId"=> 2
        );
        if($userStatus!='false' && $userStatus[0]['experience']!==""){
            $displayData['gtmParams']['workExperience'] = $userStatus[0]['experience'];
	    }
        
        return $gtmParams;
	}

}
