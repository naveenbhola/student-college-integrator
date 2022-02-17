<?php

class SearchPage{

	private $request;
	private $searchPageDataProcessor;
	private $instituteRepository;
	private $filters;

	function __construct(SearchPageRequest $request,
						 SearchPageRepository $searchPageRepo,
						 $instituteRepository,
						 $locationRepository,
						 $categoryRepository,
						 $LDBCourseRepository)
	{
		$this->request             = $request;
		$this->searchPageRepo      = $searchPageRepo;
		$this->instituteRepository = $instituteRepository;
		$this->locationRepository  = $locationRepository;
		$this->categoryRepository  = $categoryRepository;
		$this->LDBCourseRepository = $LDBCourseRepository;
		$this->CI = &get_instance();
		$this->CI->load->config("search/SearchPageConfig");
		$this->buildSearchData();
	}

	public function buildSearchData() {
		$this->searchPageRepo->getRawSearchData();
	}

	public function getRelevantResultsFlag(){
		return $this->searchPageRepo->getRelevantResultsFlag();
	}

	public function getOldKeyword(){
		return $this->searchPageRepo->getOldKeyword();
	}

	public function getInstituteCount() {
		return $this->searchPageRepo->getInstituteCount();
	}

	public function getCourseCount() {
		return $this->searchPageRepo->getCourseCount();
	}

	public function getDebugSortInfo() {
		return $this->searchPageRepo->getDebugSortInfo();
	}

	public function getInstitutes(){
		$institutes                   = array();
		$instituteData                = $this->searchPageRepo->getInstitutes();
		
		$instituteWithPopularCourses  = array();
		$instituteWithLoadMoreCourses = array();
		$instituteWithCourseCount     = array();

		foreach ($instituteData as $instituteId => $courses) {
			$courseCount = count($courses);
			$instituteWithCourseCount[$instituteId] = $courseCount;
			foreach ($courses as $key => $value) {
				if($key < 1){
					$instituteWithPopularCourses[$instituteId][] = $value;			
					$popularCourses[] = $value;						
				}else{
					$instituteWithLoadMoreCourses[$instituteId][] = $value;					
				}
			}
		}
		
		//lookup with respect to performance
		$institutes['instituteData']            	= $this->instituteRepository->findWithCourses($instituteWithPopularCourses);
		$institutes['popularCourses'] 				= $popularCourses;
		$institutes['instituteLoadMoreCourses'] 	= $instituteWithLoadMoreCourses;
		$institutes['instituteCourseCount']     	= $instituteWithCourseCount;
		$institutes['instituteCountInCurrentPage']	= count($instituteWithPopularCourses);
		return $institutes;
	}

	public function getFilters(){
		$this->filters = array();
		$filtersData   = $this->searchPageRepo->getFilters();
		$this->appliedFilters = $this->request->getAppliedFilters();
		$this->searchParamFieldAlias = $this->CI->config->item('SEARCH_PARAMS_FIELDS_ALIAS');
		foreach ($filtersData as $filter => $value) {
			switch ($filter) {
				case 'subcat':
					$time_start = microtime_float(); $start_memory = memory_get_usage();
					$this->filters['subCat']    	 = $this->prepareSubCatFilters($filtersData['subcat']);
					if(LOG_SEARCH_PERFORMANCE_DATA) error_log("Section: In SearchPage, Filter, Subcat | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME);
					break;

				case 'location':
					$time_start = microtime_float(); $start_memory = memory_get_usage();
					$this->filters['locations'] 	 = $this->prepareLocationFilters($filtersData['location']);
					if(LOG_SEARCH_PERFORMANCE_DATA) error_log("Section: In SearchPage, Filter, location | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME);
					break;

				case 'exams':
					$time_start = microtime_float(); $start_memory = memory_get_usage();
					$this->filters['exams']     	 = $this->prepareExamFilters($filtersData['exams']); //$filtersData['exams'];
					if(LOG_SEARCH_PERFORMANCE_DATA) error_log("Section: In SearchPage, Filter, exams | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME);
					break;

				case 'fees':
					$time_start = microtime_float(); $start_memory = memory_get_usage();
					$this->filters['fees']      	 = $this->prepareFeesFilters($filtersData['fees']);
					if(LOG_SEARCH_PERFORMANCE_DATA) error_log("Section: In SearchPage, Filter, fees | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME);
					break;

				case 'mode':
					$time_start = microtime_float(); $start_memory = memory_get_usage();
					$this->filters['mode']      	 = $this->prepareModeFilters($filtersData['mode']); //$filtersData['mode'];
					if(LOG_SEARCH_PERFORMANCE_DATA) error_log("Section: In SearchPage, Filter, mode | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME);
					break;

				case 'courseLevel':
					$time_start = microtime_float(); $start_memory = memory_get_usage();
					$this->filters['courseLevel']    = $this->prepareCourseLevelFilters($filtersData['courseLevel']); //$filtersData['courseLevel'];
					if(LOG_SEARCH_PERFORMANCE_DATA) error_log("Section: In SearchPage, Filter, courseLevel | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME);
					break;

				case 'ldbCourse':
				case 'specialization':
					$time_start = microtime_float(); $start_memory = memory_get_usage();
					$specializationResult = $this->prepareSpecializationFilters($filtersData['specialization'], $filtersData['ldbCourse']);
					if(!empty($specializationResult))
						$this->filters['specialization'] = $specializationResult;
					if(LOG_SEARCH_PERFORMANCE_DATA) error_log("Section: In SearchPage, Filter, specialization | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME);
					break;

				case 'degreePref':
					$time_start = microtime_float(); $start_memory = memory_get_usage();
					$this->filters['degreePref'] = $this->prepareDegreePrefFilters($filtersData['degreePref']);
					if(LOG_SEARCH_PERFORMANCE_DATA) error_log("Section: In SearchPage, Filter, degreePref | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME);
					break;

				case 'affiliation':
					$time_start = microtime_float(); $start_memory = memory_get_usage();
					$this->filters['affiliation'] = $this->prepareAffiliationFilters($filtersData['affiliation']);
					if(LOG_SEARCH_PERFORMANCE_DATA) error_log("Section: In SearchPage, Filter, affiliation | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME);
					break;

				case 'classTimings':
					$time_start = microtime_float(); $start_memory = memory_get_usage();
					$this->filters['classTimings'] = $this->prepareClassTimingsFilters($filtersData['classTimings']);
					if(LOG_SEARCH_PERFORMANCE_DATA) error_log("Section: In SearchPage, Filter, classTimings | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME);
					break;

				case 'facilities':
					$time_start = microtime_float(); $start_memory = memory_get_usage();
					$this->filters['facilities']  = $this->prepareFacilitiesFilters($filtersData['facilities']);
					if(LOG_SEARCH_PERFORMANCE_DATA) error_log("Section: In SearchPage, Filter, facilities | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME);
					break;

				default:
					# code...
					break;
			}
		}

		return $this->filters;
	}

	private function prepareSubCatFilters($data) {
		$qerFilters = $this->request->getQERFilters();
		if($qerFilters['customQer']) {
			foreach ($qerFilters['course'] as $ldbCourse) {
				$allowedSubCategory = $this->categoryRepository->getCategoryByLDBCourse($ldbCourse);
				$allowedSubCategories[$allowedSubCategory->getId()] = $allowedSubCategory;
				if(!in_array($allowedSubCategory->getId(), $allowedSubCategories)) {
					$allowedSubCategoryIds[] = $allowedSubCategory->getId();
				}
				if(!in_array($allowedSubCategory->getParentId(), $parentIds)) {
					$parentIds[] = $allowedSubCategory->getParentId();
				}
			}
		}

		if(!empty($parentIds)) {
			$catObjs = $this->categoryRepository->findMultiple($parentIds);
		}
		
		if(!empty($allowedSubCategories)) {
			$subCat = $allowedSubCategories + $catObjs;
		} else {
			if(!empty($data)) {
				$subCat = $this->categoryRepository->findMultiple(array_keys($data));
			}
		}
		
		//$subCat = $this->categoryRepository->findMultiple(array_keys($data));
		$subCatData = array();
		foreach($subCat as $val){
			if($val->getParentId() > 1){
				//getParentObj
				$parentObj  = $subCat[$val->getParentId()];
				$subCatData['cat_'.$val->getParentId()]['name']                                			 = $parentObj->getName();
				$subCatData['cat_'.$val->getParentId()]['subCategory']['subcat_'.$val->getId()]['id']    = $val->getId();
				$subCatData['cat_'.$val->getParentId()]['subCategory']['subcat_'.$val->getId()]['name']  = $val->getName();
				$subCatData['cat_'.$val->getParentId()]['subCategory']['subcat_'.$val->getId()]['count'] = $data[$val->getId()];
				if(!isset($subCatData['cat_'.$val->getParentId()]['totalSubcatResultCount'])) {
					$subCatData['cat_'.$val->getParentId()]['totalSubcatResultCount'] = 0;
				}

				$subCatData['cat_'.$val->getParentId()]['totalSubcatResultCount'] = $subCatData['cat_'.$val->getParentId()]['totalSubcatResultCount'] + $data[$val->getId()];
			}
		}
		
		uasort($subCatData, array('SearchPage','forBySubCatCount'));
		
		return $subCatData;
	}

	static function forBySubCatCount($a,$b){
		if($a['totalSubcatResultCount'] > $b['totalSubcatResultCount']){
			return -1;
		}else{
			return 1;
		}
	}

	static function compareByName($a, $b) {
  		return strcmp($a["name"], $b["name"]);
	}

	private function prepareModeFilters($data) {
		$courseModeList = array();
		$modeAlias = $this->searchParamFieldAlias['mode'];
		foreach ($data['current'] as $mode => $count) {
			$courseModeList[$modeAlias."_".$mode]['id'] = $mode;
			$courseModeList[$modeAlias."_".$mode]['name'] = $mode;
			$courseModeList[$modeAlias."_".$mode]['count'] = $count;
			$courseModeList[$modeAlias."_".$mode]['enabled'] = 1;
			$courseModeList[$modeAlias."_".$mode]['checked'] = 0;
			if(in_array($mode, $this->appliedFilters['mode'])) {
				$courseModeList[$modeAlias."_".$mode]['checked'] = 1;
			}
		}
		
		//get disabled course level
		if(empty($data['current'])) {
			$data['current'] = array();
		}
		$disCourseLevel = array_diff_key($data['parent'], $data['current']);
		foreach ($disCourseLevel as $mode => $count) {
			$courseModeList[$modeAlias."_".$mode]['id'] = $mode;
			$courseModeList[$modeAlias."_".$mode]['name'] = $mode;
			$courseModeList[$modeAlias."_".$mode]['count'] = 0;
			$courseModeList[$modeAlias."_".$mode]['enabled'] = 0;
			$courseModeList[$modeAlias."_".$mode]['checked'] = 0;
		}
		
		return $courseModeList;
	}

	private function prepareCourseLevelFilters($data) {
		$courseLevelList = array();
		$courseLevelAlias = $this->searchParamFieldAlias['courseLevel'];
		foreach ($data['current'] as $courseLevel => $count) {
			$courseLevelList[$courseLevelAlias."_".$courseLevel]['id'] = $courseLevel;
			$courseLevelList[$courseLevelAlias."_".$courseLevel]['name'] = $courseLevel;
			$courseLevelList[$courseLevelAlias."_".$courseLevel]['count'] = $count;
			$courseLevelList[$courseLevelAlias."_".$courseLevel]['enabled'] = 1;
			$courseLevelList[$courseLevelAlias."_".$courseLevel]['checked'] = 0;
			if(in_array($courseLevel, $this->appliedFilters['level'])) {
				$courseLevelList[$courseLevelAlias."_".$courseLevel]['checked'] = 1;
			}
		}
		
		//get disabled course level
		if(empty($data['current'])) {
			$data['current'] = array();
		}
		$disCourseLevel = array_diff_key($data['parent'], $data['current']);
		foreach ($disCourseLevel as $courseLevel => $count) {
			$courseLevelList[$courseLevelAlias."_".$courseLevel]['id'] = $courseLevel;
			$courseLevelList[$courseLevelAlias."_".$courseLevel]['name'] = $courseLevel;
			$courseLevelList[$courseLevelAlias."_".$courseLevel]['count'] = 0;
			$courseLevelList[$courseLevelAlias."_".$courseLevel]['enabled'] = 0;
			$courseLevelList[$courseLevelAlias."_".$courseLevel]['checked'] = 0;
		}
		
		return $courseLevelList;
	}

	private function prepareExamFilters($data) {
		$appliedExams = array();
		$examAlias = $this->searchParamFieldAlias['exams'];
		foreach ($this->appliedFilters['exams'] as $examWithScore) {
			if(!empty($examWithScore)) {
				$examInfo = explode('_', $examWithScore);
				$appliedExams[] = $examInfo[0];
			}
		}
		$examList = array();
		foreach ($data['current'] as $exam => $count) {
			$examList[$examAlias."_".$exam]['id'] = $exam;
			$examList[$examAlias."_".$exam]['name'] = $exam;
			$examList[$examAlias."_".$exam]['count'] = $count;
			$examList[$examAlias."_".$exam]['enabled'] = 1;
			$examList[$examAlias."_".$exam]['checked'] = 0;
			if(in_array($exam, $appliedExams)) {
				$examList[$examAlias."_".$exam]['checked'] = 1;
			}
		}
		
		//get disabled exams
		if(empty($data['current'])) {
			$data['current'] = array();
		}
		$disExam = array_diff_key($data['parent'], $data['current']);
		foreach ($disExam as $exam => $count) {
			$examList[$examAlias."_".$exam]['id'] = $exam;
			$examList[$examAlias."_".$exam]['name'] = $exam;
			$examList[$examAlias."_".$exam]['count'] = 0;
			$examList[$examAlias."_".$exam]['enabled'] = 0;
			$examList[$examAlias."_".$exam]['checked'] = 0;
		}
		
		return $examList;
	}

	private function prepareLocationFilters($data){
		$locations = array();
		$time_start = microtime_float(); $start_memory = memory_get_usage();
		$cityAlias = $this->searchParamFieldAlias['city'];
		$stateAlias = $this->searchParamFieldAlias['state'];

		//current cities and virtual cities
		if(array_key_exists('city', $data['current']) && array_key_exists('virtualCity', $data['current'])) {
			$dataWithCityAndVirtualCity['current'] = $data['current']['city'] + $data['current']['virtualCity'];
		}
		elseif (array_key_exists('city', $data['current'])) {
			$dataWithCityAndVirtualCity['current'] = $data['current']['city'];
		}
		elseif (array_key_exists('virtualCity', $data['current'])) {
			$dataWithCityAndVirtualCity['current'] = $data['current']['virtualCity'];
		}

		//parent cities and virtual cities
		if(array_key_exists('city', $data['parent']) && array_key_exists('virtualCity', $data['parent'])) {
			$dataWithCityAndVirtualCity['parent'] = $data['parent']['city'] + $data['parent']['virtualCity'];
		}
		elseif (array_key_exists('city', $data['parent'])) {
			$dataWithCityAndVirtualCity['parent'] = $data['parent']['city'];
		}
		elseif (array_key_exists('virtualCity', $data['parent'])) {
			$dataWithCityAndVirtualCity['parent'] = $data['parent']['virtualCity'];
		}

		$cityObjs = array();
		if(count(array_keys($dataWithCityAndVirtualCity['current'])) > 0){
			$cityTierObjList = $this->locationRepository->getCitiesByMultipleTiers(array(1), 2);
			
			$tier1Cities = array();
			foreach ($cityTierObjList[1] as $key => $cityObj) {
				$tier1Cities[] = $cityObj->getId();
			}

			$cities['current']['tier'] = array(); $cities['current']['nonTier'] = array();
			
			//special check for all india case in mobile
			if(isMobileRequest()) {
				$tier1Cities[] = 1;
				$dataWithCityAndVirtualCity['current']['All India:1'] = $this->getInstituteCount();
			}

			foreach($dataWithCityAndVirtualCity['current'] as $city => $count){
				$tempArr = explode(':', $city);
				$cityName = $tempArr[0];
				$cityId = $tempArr[1];
				if(in_array($cityId, $tier1Cities)) {
					$cities['current']['tier'][$cityAlias.'_'.$cityId]['id']    = $cityId;
					$cities['current']['tier'][$cityAlias.'_'.$cityId]['name']  = $cityName;
					$cities['current']['tier'][$cityAlias.'_'.$cityId]['count'] = $count;
					$cities['current']['tier'][$cityAlias.'_'.$cityId]['checked']  = 0;
					if(in_array($cityId, $this->appliedFilters['city'])) {
						$cities['current']['tier'][$cityAlias.'_'.$cityId]['checked']  = 1;
					}
				} else {
					$cities['current']['nonTier'][$cityAlias.'_'.$cityId]['id']    = $cityId;
					$cities['current']['nonTier'][$cityAlias.'_'.$cityId]['name']  = $cityName;
					$cities['current']['nonTier'][$cityAlias.'_'.$cityId]['count'] = $count;
					$cities['current']['nonTier'][$cityAlias.'_'.$cityId]['checked']  = 0;
					if(in_array($cityId, $this->appliedFilters['city'])) {
						$cities['current']['nonTier'][$cityAlias.'_'.$cityId]['checked']  = 1;
					}
				}
			}
			uasort($cities['current']['tier'], array('SearchPage','compareByName'));
			uasort($cities['current']['nonTier'], array('SearchPage','compareByName'));
		}
		
		//get disabled cities
		if(count(array_keys($dataWithCityAndVirtualCity['parent'])) > 0) {
			if(empty($dataWithCityAndVirtualCity['current'])) {
				$dataWithCityAndVirtualCity['current'] = array();
			}
			$dataWithCityAndVirtualCity['disabled'] = array_diff_key($dataWithCityAndVirtualCity['parent'], $dataWithCityAndVirtualCity['current']);
			
			$disabledCity = array();
			foreach($dataWithCityAndVirtualCity['disabled'] as $city => $count){
				$tempArr = explode(':', $city);
				$cityName = $tempArr[0];
				$cityId = $tempArr[1];
				$disabledCity[$cityAlias.'_'.$cityId]['id']    = $cityId;
				$disabledCity[$cityAlias.'_'.$cityId]['name']  = $cityName;
				$disabledCity[$cityAlias.'_'.$cityId]['count']  = 0;
				$disabledCity[$cityAlias.'_'.$cityId]['checked']  = 0;
			}
			uasort($disabledCity, array('SearchPage','compareByName'));
		}
		$locations['city'] = $cities['current']['tier'] + $cities['current']['nonTier'];
		
		if(LOG_SEARCH_PERFORMANCE_DATA) error_log("Section: In SearchPage, Filter, Location, City | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME);
		
		//enabled states
		$time_start = microtime_float(); $start_memory = memory_get_usage();
		if(count(array_keys($data['current']['state'])) > 0){
			foreach($data['current']['state'] as $state => $count){
				$tempArr = explode(':', $state);
				$stateName = $tempArr[0];
				$stateId = $tempArr[1];
				$locations['state'][$stateAlias.'_'.$stateId]['id']    = $stateId;
				$locations['state'][$stateAlias.'_'.$stateId]['name']  = $stateName;
				$locations['state'][$stateAlias.'_'.$stateId]['count'] = $count;
				$locations['state'][$stateAlias.'_'.$stateId]['checked']  = 0;
				if(in_array($stateId, $this->appliedFilters['state'])) {
					$locations['state'][$stateAlias.'_'.$stateId]['checked']  = 1;
				}
			}
			uasort($locations['state'], array('SearchPage','compareByName'));
		}
		
		//disabled states
		if(empty($data['current']['state'])) {
			$data['current']['state'] = array();
		}
		$disStates = array_diff_key($data['parent']['state'], $data['current']['state']);
		$disabledState = array();
		if(count(array_keys($disStates)) > 0){
			foreach($disStates as $state => $count){
				$tempArr = explode(':', $state);
				$stateName = $tempArr[0];
				$stateId = $tempArr[1];
				$disabledState[$stateAlias.'_'.$stateId]['id']    = $stateId;
				$disabledState[$stateAlias.'_'.$stateId]['name']  = $stateName;
				$disabledState[$stateAlias.'_'.$stateId]['count']  = 0;
				$disabledState[$stateAlias.'_'.$stateId]['checked'] = 0;
			}
			uasort($disabledState, array('SearchPage','compareByName'));
		}
		if(LOG_SEARCH_PERFORMANCE_DATA) error_log("Section: In SearchPage, Filter, Location, State | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME);
		
		if(!empty($data['parent'])) {
			$locations['disabled'] = $disabledCity + $disabledState;
		}
		
		//locality
		$time_start = microtime_float(); $start_memory = memory_get_usage();
		$localityObjs = array();
		foreach($data['current']['locality'] as $id => $localityCount) {
			$idArr = explode(':', $id);
			$cityId = $idArr[0];
			$localityId = $idArr[1];
			if(!empty($localityId) && !empty($cityId)) {
				$locations['locality']['cityWiseLocality'][$cityId][] = $localityId;
				$locations['locality']['localityCount'][$cityId][$localityId] = $localityCount;
			}
		}
		if(LOG_SEARCH_PERFORMANCE_DATA) error_log("Section: In SearchPage, Filter, Location, Locality | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME);
		
		return $locations;
	}

	private function prepareSpecializationFilters($spec, $ldbCourse) {
		$specializationList = array();
		$subCatId           = $this->request->getSubCategoryId();
		$specAlias 			= $this->searchParamFieldAlias['specialization'];
		
		$data = array();
		if(!empty($spec['current']) && !empty($ldbCourse['current'])) {
			$data = array_merge($spec['current'], $ldbCourse['current']);
		} elseif(!empty($spec['current'])) {
			$data = $spec['current'];
		} elseif(!empty($ldbCourse['current'])) {
			$data = $ldbCourse['current'];
		}
		arsort($data); //sort on the basis of count
		$subcatWiseLdbCourses = $this->LDBCourseRepository->getLDBCoursesForSubCategory($subCatId);
		foreach ($subcatWiseLdbCourses as $key => $value) {
			$subcatWiseLdbCourseIds[] = $value->getId();
		}
		
		foreach ($data as $specNameIdMap => $specCount) {
			$tempArr = explode(':', $specNameIdMap);
			$specName = $tempArr[0];
			$specId = $tempArr[1];
			if($specId > 0) {
				if(!empty($specId) && in_array($specId, $subcatWiseLdbCourseIds)) {
					$specializationList[$specAlias."_".$specId]['id']    = $specId;
					$specializationList[$specAlias."_".$specId]['name']  = $specName;
					$specializationList[$specAlias."_".$specId]['count'] = $specCount;
					$specializationList[$specAlias."_".$specId]['enabled'] = 1;
					$specializationList[$specAlias."_".$specId]['checked'] = 0;
					if(in_array($specId, $this->appliedFilters['course'])) {
						$specializationList[$specAlias."_".$specId]['checked'] = 1;
					}
				}
			}
		}

		//get disabled filters
		if(empty($spec['current'])) {
			$spec['current'] = array();
		}
		if(empty($ldbCourse['current'])) {
			$ldbCourse['current'] = array();
		}
		$disSpec = array_diff_key($spec['parent'], $spec['current']);
		$disLdbCourse = array_diff_key($ldbCourse['parent'], $ldbCourse['current']);
		$data = array();
		if(!empty($disSpec) && !empty($disLdbCourse)) {
			$data = array_merge($disSpec, $disLdbCourse);
		} elseif(!empty($disSpec)) {
			$data = $disSpec;
		} elseif(!empty($disLdbCourse)) {
			$data = $disLdbCourse;
		}

		foreach ($data as $specNameIdMap => $specCount) {
			$tempArr = explode(':', $specNameIdMap);
			$specName = $tempArr[0];
			$specId = $tempArr[1];
			if($specId > 0) {
				if(!empty($specId) && in_array($specId, $subcatWiseLdbCourseIds)) {
					$specializationList[$specAlias."_".$specId]['id']    = $specId;
					$specializationList[$specAlias."_".$specId]['name']  = $specName;
					$specializationList[$specAlias."_".$specId]['count'] = 0;
					$specializationList[$specAlias."_".$specId]['enabled'] = 0;
					$specializationList[$specAlias."_".$specId]['checked'] = 0;
				}
			}
		}
		
		return $specializationList;
	}

	private function prepareAffiliationFilters($data){
		$affiliationList = array();
		$affAlias 		 = $this->searchParamFieldAlias['affiliation'];
		foreach ($data['current'] as $affiliation => $count) {
			$affiliationList[$affAlias."_".$affiliation]['id'] = $affiliation;
			if($affiliation == 'Indian')
				$affiliationList[$affAlias."_".$affiliation]['name'] = 'Affiliated to Indian University';
			elseif($affiliation == 'Autonomous')
				$affiliationList[$affAlias."_".$affiliation]['name'] = 'Autonomous';
			elseif($affiliation == 'Deemed')
				$affiliationList[$affAlias."_".$affiliation]['name'] = 'Deemed University';
			elseif($affiliation == 'Foreign')
				$affiliationList[$affAlias."_".$affiliation]['name'] = 'Affiliated to Foreign University';

			$affiliationList[$affAlias."_".$affiliation]['count'] = $count;
			$affiliationList[$affAlias."_".$affiliation]['enabled'] = 1;
			$affiliationList[$affAlias."_".$affiliation]['checked'] = 0;
			if(in_array($affiliation, $this->appliedFilters['affiliation'])) {
				$affiliationList[$affAlias."_".$affiliation]['checked'] = 1;
			}
		}

		//get disabled affiliation
		if(empty($data['current'])) {
			$data['current'] = array();
		}
		$disAff = array_diff_key($data['parent'], $data['current']);
		foreach ($disAff as $affiliation => $count) {
			$affiliationList[$affAlias."_".$affiliation]['id'] = $affiliation;
			if($affiliation == 'Indian')
				$affiliationList[$affAlias."_".$affiliation]['name'] = 'Affiliated to Indian University';
			elseif($affiliation == 'Autonomous')
				$affiliationList[$affAlias."_".$affiliation]['name'] = 'Autonomous';
			elseif($affiliation == 'Deemed')
				$affiliationList[$affAlias."_".$affiliation]['name'] = 'Deemed University';
			elseif($affiliation == 'Foreign')
				$affiliationList[$affAlias."_".$affiliation]['name'] = 'Affiliated to Foreign University';

			$affiliationList[$affAlias."_".$affiliation]['count'] = 0;
			$affiliationList[$affAlias."_".$affiliation]['enabled'] = 0;
			$affiliationList[$affAlias."_".$affiliation]['checked'] = 0;
		}
		
		return $affiliationList;
	}

	private function prepareDegreePrefFilters($data){
		$degreePrefList = array();
		$degreePrefAlias = $this->searchParamFieldAlias['degreePref'];
		global $Degreee_Pref_Mapping;
		foreach ($data['current'] as $degreePref => $count) {
			$degreePrefList[$degreePrefAlias."_".$degreePref]['id'] = $Degreee_Pref_Mapping[$degreePref];
			$degreePrefList[$degreePrefAlias."_".$degreePref]['name'] = $Degreee_Pref_Mapping[$degreePref];
			$degreePrefList[$degreePrefAlias."_".$degreePref]['count'] = $count;
			$degreePrefList[$degreePrefAlias."_".$degreePref]['enabled'] = 1;
			$degreePrefList[$degreePrefAlias."_".$degreePref]['checked'] = 0;
			if(in_array($degreePref, $this->appliedFilters['degreePref'])) {
				$degreePrefList[$degreePrefAlias."_".$degreePref]['checked'] = 1;
			}
		}

		//get disabled degree pref
		if(empty($data['current'])) {
			$data['current'] = array();
		}
		$disDegPref = array_diff_key($data['parent'], $data['current']);
		foreach ($disDegPref as $degreePref => $count) {
			$degreePrefList[$degreePrefAlias."_".$degreePref]['id'] = $Degreee_Pref_Mapping[$degreePref];
			$degreePrefList[$degreePrefAlias."_".$degreePref]['name'] = $Degreee_Pref_Mapping[$degreePref];
			$degreePrefList[$degreePrefAlias."_".$degreePref]['count'] = 0;
			$degreePrefList[$degreePrefAlias."_".$degreePref]['enabled'] = 0;
			$degreePrefList[$degreePrefAlias."_".$degreePref]['checked'] = 0;
		}
		
		return $degreePrefList;
	}

	public function getSelectedFilters(){
		$selectedFilters = array();
		$appliedFilters = $this->request->getAppliedFilters();
		$count = array();
		foreach ($appliedFilters as $filters => $val) {
			switch ($filters) {
				case 'subcatId':
					if(empty($val))
						break;
					
					$subCatObj      = $this->categoryRepository->find($val);
					$selectedFilters['subCategory'] = $subCatObj->getName();
					break;
				case 'city':
					foreach ($val as $key => $cityId) {
						$cityIdWithAlias = $this->searchParamFieldAlias['city']."_".$cityId;
						if(isset($this->filters['locations']['city'][$cityIdWithAlias])){
							$selectedFilters['locations']['city'][$cityIdWithAlias]['id'] =  $cityId;
							// fetch data from global array
							$selectedFilters['locations']['city'][$cityIdWithAlias]['name'] =  $this->filters['locations']['city'][$cityIdWithAlias]['name'];
						}
					}
					$count['locations'] = $count['locations'] + count($val);
					break;
				case 'state':
					foreach ($val as $key => $stateId) {
						$stateIdWithAlias = $this->searchParamFieldAlias['state']."_".$stateId;
						if(isset($this->filters['locations']['state'][$stateIdWithAlias])){
							$selectedFilters['locations']['state'][$stateIdWithAlias]['id'] =  $stateId;
							// fetch data from global array
							$selectedFilters['locations']['state'][$stateIdWithAlias]['name'] =  $this->filters['locations']['state'][$stateIdWithAlias]['name'];
						}
					}
					$count['locations'] = $count['locations'] + count($val);
					break;
				case 'fees':
				 	global $SEARCH_FEES_RANGE;
				 	foreach ($val as $key => $value) {
				 		$feesAlias = $this->searchParamFieldAlias['fees']."_".$value;
				 		if(isset($val, $this->filters[$filters][$feesAlias]) && $this->filters[$filters][$feesAlias]['enabled']){
							$selectedFilters['fees'][$feesAlias]['id'] = $value;					
							$selectedFilters['fees'][$feesAlias]['name'] = $this->filters[$filters][$this->searchParamFieldAlias['fees']."_".$value]['name'];					
						}
				 	}
				 	$count['fees'] = count($val);
					break;
				case 'course':
					foreach ($val as $key => $specializationId) {
						$specWithAlias = $this->searchParamFieldAlias['specialization']."_".$specializationId;
						if(isset($this->filters['specialization'][$specWithAlias]) && $this->filters['specialization'][$specWithAlias]['enabled']){
							$selectedFilters['specialization'][$specWithAlias]['id'] = $specializationId;
							if(!empty($this->filters['specialization'][$specWithAlias]['name'])){
								$selectedFilters['specialization'][$specWithAlias]['name'] = $this->filters['specialization'][$specWithAlias]['name'];
							}
							else{
								$ldbObj = $this->LDBCourseRepository->find($specializationId);
								$selectedFilters['specialization'][$specWithAlias]['name'] = $ldbObj->getSpecialization();
							}
						}
					}
					$count['specialization'] = count($val);
					break;
				case 'degreePref':
					global $Degreee_Pref_Mapping;
					foreach($val as $key => $degreePrefName){
						$degreePrefWithAlias = $this->searchParamFieldAlias['degreePref']."_".$degreePrefName;
						if(array_key_exists($degreePrefWithAlias, $this->filters[$filters]) && $this->filters[$filters][$degreePrefWithAlias]['enabled']){
							$selectedFilters[$filters][$degreePrefWithAlias]['id'] = strtolower($Degreee_Pref_Mapping[$degreePrefName]);
							$selectedFilters[$filters][$degreePrefWithAlias]['name'] = $Degreee_Pref_Mapping[$degreePrefName];
						}
					}
					$count['degreePref'] = count($val);
					break;
				case 'facilities':
					foreach($val as $facilities){
						$facilitiesWithAlias = $this->searchParamFieldAlias['facilities']."_".$facilities;
						if(array_key_exists($facilitiesWithAlias, $this->filters[$filters]) && $this->filters[$filters][$facilitiesWithAlias]['enabled']){
							//$facilitiesSplit = explode(":", $facilities);
							$facilitiesKey = str_replace(":", "|", $facilities);
							$selectedFilters[$filters][$facilitiesWithAlias]['id'] = $this->filters[$filters][$facilitiesWithAlias]['id'];
							$selectedFilters[$filters][$facilitiesWithAlias]['name'] = $this->filters[$filters][$facilitiesWithAlias]['name'];
						}
					}
					$count['facilities'] = count($val);
					break;
				case 'level':
					$refineVal = array();
					foreach($val as $key =>$res){
						$levelWithAlias = $this->searchParamFieldAlias['courseLevel']."_".$res;
						if(array_key_exists($levelWithAlias, $this->filters['courseLevel']) && $this->filters['courseLevel'][$levelWithAlias]['enabled']){
							$refineVal[$levelWithAlias]['id'] = $res;
							$refineVal[$levelWithAlias]['name'] = $res;
						}
					}
					$selectedFilters['courseLevel'] = $refineVal;
					$count['courseLevel'] = count($val);
					break;
				case 'exams':
					$refineVal = array();
					foreach($val as $key =>$res){
						$examString = explode("_", $res);
						$examWithAlias = $this->searchParamFieldAlias['exams']."_".$examString[0];
						if(array_key_exists($examWithAlias, $this->filters[$filters]) && $this->filters[$filters][$examWithAlias]['enabled']){
							$refineVal[$examWithAlias]['id'] = $examString[0];
							$refineVal[$examWithAlias]['name'] = $examString[0];
						}
					}
					$selectedFilters[$filters] 		= $refineVal;
					$count['exams'] = count($val);
					break;
				case 'affiliation':
					$refineVal = array();
					foreach($val as $key =>$res){
						$affiliationWIthAlias = $this->searchParamFieldAlias['affiliation']."_".$res;
						if(array_key_exists($affiliationWIthAlias, $this->filters[$filters]) && $this->filters[$filters][$affiliationWIthAlias]['enabled']){
							$refineVal[$affiliationWIthAlias]['id'] = $this->filters[$filters][$affiliationWIthAlias]['name'];
							$refineVal[$affiliationWIthAlias]['name'] = $this->filters[$filters][$affiliationWIthAlias]['name'];
						}
					}
					$selectedFilters[$filters] 		= $refineVal;
					$count['affiliation'] = count($val);
					break;
				case 'mode':
					$refineVal = array();
					foreach($val as $key =>$res){
						$modeWithAlias = $this->searchParamFieldAlias['mode']."_".$res;
						if(array_key_exists($modeWithAlias, $this->filters[$filters]) && $this->filters[$filters][$modeWithAlias]['enabled']){
							$refineVal[$modeWithAlias]['id'] = $this->filters[$filters][$modeWithAlias]['id'];
							$refineVal[$modeWithAlias]['name'] = $this->filters[$filters][$modeWithAlias]['name'];
						}
					}
					$selectedFilters[$filters] 		= $refineVal;
					$count['mode'] = count($val);
					break;
				case 'classTimings':
					$refineVal = array();
					foreach($val as $key =>$res){
						$classWithAlias = $this->searchParamFieldAlias['classtimings']."_".$res;
						if(array_key_exists($classWithAlias, $this->filters[$filters]) && $this->filters[$filters][$classWithAlias]['enabled']){
							$refineVal[$classWithAlias]['id'] = $this->filters[$filters][$classWithAlias]['id'];
							$refineVal[$classWithAlias]['name'] = $this->filters[$filters][$classWithAlias]['name'];
						}
					}
					$selectedFilters[$filters] 		= $refineVal;
					$count['classTimings'] = count($val);
					break;
				default:
					$selectedFilters[$filters] = $val;
					break;
			}
		}
		
		return array('selectedFilters'=>$selectedFilters, 'count'=>$count);
	}

	private function prepareFeesFilters($data){
		$fees = array();
		$feesAlias = $this->searchParamFieldAlias['fees'];
		global $SEARCH_FEES_RANGE;
		$subCatId           = $this->request->getSubCategoryId();
		$range = $SEARCH_FEES_RANGE[$subCatId];

		foreach($data as $key=>$val){
			if($val > 0){
				$feeArr = explode('_', $key);
				$feeBucket = $range[$feeArr[0]]['placeholder'];
				$type = $feeArr[1];

				if(empty($type)) { //current filters(enabled)
					$fees[$feesAlias."_".$feeArr[0]]['enabled'] = 1;
					$fees[$feesAlias."_".$feeArr[0]]['id']    = $feeArr[0];
					$fees[$feesAlias."_".$feeArr[0]]['name'] =  $feeBucket;
					$fees[$feesAlias."_".$feeArr[0]]['count'] = $val;
					$fees[$feesAlias."_".$feeArr[0]]['checked'] =  0;
					if(in_array($key, $this->appliedFilters['fees'])) {
						$fees[$feesAlias."_".$feeArr[0]]['checked'] =  1;
					}
				}
				if($type == 'parent'){
					if(!in_array($feesAlias."_".$feeArr[0], array_keys($fees))) { //if present in enabled filters
						$fees[$feesAlias."_".$feeArr[0]]['enabled'] = 0;
						$fees[$feesAlias."_".$feeArr[0]]['checked'] = 0;
						$fees[$feesAlias."_".$feeArr[0]]['count'] = 0;
					}
					$fees[$feesAlias."_".$feeArr[0]]['id']    = $feeArr[0];
					$fees[$feesAlias."_".$feeArr[0]]['name'] =  $feeBucket;
				}
			}
		}
		
		return $fees;
	}

	private function prepareClassTimingsFilters($data){
		$classTimings = array();
		$classTimingsAlias = $this->searchParamFieldAlias['classtimings'];
		foreach($data['current'] as $key => $count){
			$classTimings[$classTimingsAlias."_".$key]['id'] = $key;
			if($key == 'morningClasses'){
				$classTimings[$classTimingsAlias."_".$key]['name'] = 'Morning Classes';
			}
			elseif($key == 'eveningClasses'){
				$classTimings[$classTimingsAlias."_".$key]['name'] = 'Evening Classes';
			}
			elseif($key == 'weekendClasses'){
				$classTimings[$classTimingsAlias."_".$key]['name'] = 'Weekend Classes';
			}
			$classTimings[$classTimingsAlias."_".$key]['count'] = $count;
			$classTimings[$classTimingsAlias."_".$key]['enabled'] = 1;
			$classTimings[$classTimingsAlias."_".$key]['checked'] = 0;
			if(in_array($key, $this->appliedFilters['classTimings'])) {
				$classTimings[$classTimingsAlias."_".$key]['checked'] = 1;
			}
		}

		//get disabled class timings
		if(empty($data['current'])) {
			$data['current'] = array();
		}
		$disClassTimings = array_diff_key($data['parent'], $data['current']);
		foreach($disClassTimings as $key => $count){
			$classTimings[$classTimingsAlias."_".$key]['id'] = $key;
			if($key == 'morningClasses'){
				$classTimings[$classTimingsAlias."_".$key]['name'] = 'Morning Classes';
			}
			elseif($key == 'eveningClasses'){
				$classTimings[$classTimingsAlias."_".$key]['name'] = 'Evening Classes';
			}
			elseif($key == 'weekendClasses'){
				$classTimings[$classTimingsAlias."_".$key]['name'] = 'Weekend Classes';
			}
			$classTimings[$classTimingsAlias."_".$key]['count'] = 0;
			$classTimings[$classTimingsAlias."_".$key]['enabled'] = 0;
			$classTimings[$classTimingsAlias."_".$key]['checked'] = 0;
		}
		
		return $classTimings;
	}

	private function prepareFacilitiesFilters($data) {
		global $FACILITY_ID_CSS_ICON_NAME_MAPPING;
		
		$facilities = array();
		$facilitiesAlias = $this->searchParamFieldAlias['facilities'];
		foreach($data['current'] as $key => $val){
			$facilitiesSplit = explode(":", $key);
			$facilityName    = $facilitiesSplit[0];
			$facilityId      = $facilitiesSplit[1];
			// check to hide certain facility
			if($FACILITY_ID_CSS_ICON_NAME_MAPPING[$facilityId] != '') {
				$facilities[$facilitiesAlias."_".$key]['id']    = $facilityId;
				$facilities[$facilitiesAlias."_".$key]['name']  = $facilityName;
				$facilities[$facilitiesAlias."_".$key]['count'] = $val;
				$facilities[$facilitiesAlias."_".$key]['enabled'] = 1;
				$facilities[$facilitiesAlias."_".$key]['checked'] = 0;
				if(in_array($key, $this->appliedFilters['facilities'])) {
					$facilities[$facilitiesAlias."_".$key]['checked'] = 1;
				}
			}
		}
		//get disabled facilities
		if(empty($data['current'])) {
			$data['current'] = array();
		}
		$disFacilities = array_diff_key($data['parent'], $data['current']);
		foreach($disFacilities as $key => $val){
			$facilitiesSplit = explode(":", $key);
			$facilityName    = $facilitiesSplit[0];
			$facilityId      = $facilitiesSplit[1];
			// check to hide certain facility
			if($FACILITY_ID_CSS_ICON_NAME_MAPPING[$facilityId] != '') {
				$facilities[$facilitiesAlias."_".$key]['id']    = $facilityId;
				$facilities[$facilitiesAlias."_".$key]['name']  = $facilityName;
				$facilities[$facilitiesAlias."_".$key]['count'] = 0;
				$facilities[$facilitiesAlias."_".$key]['enabled'] = 0;
				$facilities[$facilitiesAlias."_".$key]['checked'] = 0;
			}
		}

		return $facilities;
	}

	public function prepareBackToSearchUrlData(){
		$data 				= array();
		$subCatId           = $this->request->getSubCategoryId();
		$subCatObj          = $this->categoryRepository->find($subCatId);
		if(is_object($subCatObj))
			$subCatParentId          = $subCatObj->getParentId();
		$data['subCategoryName'] = $subCatObj->getName();
		if($subCatParentId){
			$catObj          = $this->categoryRepository->find($subCatParentId);
			if(is_object($catObj))
				$data['categoryName'] = $catObj->getName();
		}
		return $data;
	}

	public function getFilterBucketName() {
		$filterName = array();

		//specialization
		$subCatId = $this->request->getSubCategoryId();
		$nameMappingConfig = $this->CI->config->item('SPECIALIZATION_FILTERNAME_MAPPING');
	    $filterName['specialization'] = 'Course';
	    foreach($nameMappingConfig as $key => $arr){
	        if(in_array($subCatId, $arr)){
	            $filterName['specialization'] = $key;
	            break;
	        }
	    }
	    $filterName['subCat'] 		= 'Stream';
	    $filterName['exams'] 		= 'Exams Accepted';
	    $filterName['locations'] 	= 'Location';
	    $filterName['courseLevel'] 	= 'Course Level';
	    $filterName['classTimings'] = 'Class Timings';
	    $filterName['mode'] 		= 'Mode of Study';
	    $filterName['fees'] 		= 'Total Fees';
	    $filterName['affiliation'] 	= 'Course Status';
	    $filterName['degreePref'] 	= 'Recognized By';
	    $filterName['facilities'] 	= 'Facilities';
	    
	    return $filterName;
	}
}