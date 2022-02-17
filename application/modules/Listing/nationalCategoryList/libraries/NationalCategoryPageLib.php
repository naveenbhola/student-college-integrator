<?php

class NationalCategoryPageLib {
	function __construct() {
		$this->CI =& get_instance();
		
		//load model
		$this->categoryPageSEOModel = $this->CI->load->model('nationalCategoryList/categorypageseomodel');
		$this->categoryProductModel = $this->CI->load->model('nationalCategoryList/categoryproductmodel');
		$this->categoryPageModel = $this->CI->load->model('nationalCategoryList/categorypagemodel');

		$this->CI->load->config("nationalCategoryList/nationalConfig");
		$this->field_alias = $this->CI->config->item('FIELD_ALIAS');
		$this->CI->load->helper('listingCommon/listingcommon');

		$this->CI->load->builder('ListingBaseBuilder', 'listingBase');
		$this->listingBaseBuilder    = new ListingBaseBuilder();
		$this->baseCourseRepository = $this->listingBaseBuilder->getBaseCourseRepository();

		$this->CI->load->builder('LocationBuilder','location');
        $this->locationBuilder = new LocationBuilder();
		$this->locationRepository = $this->locationBuilder->getLocationRepository();

		$this->CI->load->builder('SearchBuilder', 'search');
    	$this->searchCommon = SearchBuilder::getSearchCommon();

    	$this->solrClient = $this->CI->load->library('search/Solr/SolrClient');
    	$this->nationalCategoryListCache = $this->CI->load->library('nationalCategoryList/cache/NationalCategoryListCache');

		$this->statesCityMapping = array('128'=>'74', '129'=>'10210', '130'=>'1980', '131'=>'172', '134'=>'63', '135'=>'72', '345'=>'127');
		$this->statesToIgnore = array('128', '129', '130', '131', '134', '135', '345');
	}

	/**
	 * Returns URL strictly with parameters sent in the arguments. If any parameter is not set, it will not be considered
	 * in creating the URL.
	 *
	 * Set the parameters with the keys as - <br/>
	 * <ul><li>$categoryData['stream_id']</li>
	 * <li>$categoryData['substream_id']</li>
	 * <li>$categoryData['specialization_id']</li>
	 * <li>$categoryData['base_course_id']</li>
	 * <li>$categoryData['education_type']</li>
	 * <li>$categoryData['delivery_method']</li>
	 * <li>$categoryData['credential']</li>
	 * <li>$categoryData['exam_id']</li>
	 * <li>$categoryData['city_id']</li>
	 * <li>$categoryData['state_id']</li></ul>
	 *
	 * @param array $categoryData Combination corresponding to which URL is to be obtained
	 * In case of virtual city, state id needs to be -1
	 *
	 * @return string|void URL in case of match | void otherwise
	 */
    function getUrlByParams($categoryData, $filterData) {
    	if(empty($categoryData)) {
	    	return;
	    }
	    if(!isset($categoryData['stream_id']) || empty($categoryData['stream_id'])) {
			$categoryData['stream_id'] = 0;
		}
		if(!isset($categoryData['substream_id']) || empty($categoryData['substream_id'])) {
			$categoryData['substream_id'] = 0;
		}
		if(!isset($categoryData['specialization_id']) || empty($categoryData['specialization_id'])) {
			$categoryData['specialization_id'] = 0;
		}
		if(!isset($categoryData['base_course_id']) || empty($categoryData['base_course_id'])) {
			$categoryData['base_course_id'] = 0;
		}
		if(!isset($categoryData['education_type']) || empty($categoryData['education_type'])) {
			$categoryData['education_type'] = 0;
		}
		if(!isset($categoryData['delivery_method']) || empty($categoryData['delivery_method'])) {
			$categoryData['delivery_method'] = 0;
		}
		if(!isset($categoryData['credential']) || empty($categoryData['credential'])) {
			$categoryData['credential'] = 0;
		}
		if(!isset($categoryData['exam_id']) || empty($categoryData['exam_id'])) {
			$categoryData['exam_id'] = 0;
		}
		if((!isset($categoryData['state_id']) || empty($categoryData['state_id'])) && (!isset($categoryData['city_id']) || empty($categoryData['city_id']))) {
			$categoryData['state_id'] = 1;
		}
		if(!isset($categoryData['city_id']) || empty($categoryData['city_id'])) {
			$categoryData['city_id'] = 1;
		}
		if(!isset($categoryData['min_result_count'])) {
			$categoryData['min_result_count'] = 1;
		}
		
		if(!empty($categoryData['stream_id']) || !empty($categoryData['base_course_id'])) {
    		$url = $this->categoryPageSEOModel->getUrlByParams($categoryData);
    	}

    	if(!empty($url['url'])) {
    		$queryParams = '';
    		if(!empty($filterData)) {
    			$queryParams = $this->getFilterQueryParams($filterData);
    			if(!empty($queryParams))
    				$url = SHIKSHA_HOME.'/'.$url['url'].'?'.$queryParams;
    		}else{
    			$url = SHIKSHA_HOME.'/'.$url['url'];
    		}
    		
    		return $url;
    	}
    	return;
    }

    function getAllIndiaPageByStreamId($streamId){
    	if(empty($streamId)) {
	    	return;
	    }
	    $data = $this->categoryPageSEOModel->getAllIndiaPageByStreamId($streamId);
	    if(!empty($data)){
	    	$data['url'] = addingDomainNameToUrl(array('url' => $data['url'],'domainName' => SHIKSHA_HOME));
	    	$returnData = $data;
	    }
	    return $returnData;
    }

    /**
	 * Returns URL strictly with parameters sent in the arguments. If any parameter is not set, it will not be considered
	 * in creating the URL. Works for multiple parameters at once.
	 *
	 * Set the parameters with the keys as - <br/>
	 * $categoryData[0]['stream_id']
	 * $categoryData[0]['substream_id']
	 * $categoryData[0]['specialization_id']
	 * $categoryData[0]['base_course_id']
	 * $categoryData[0]['education_type']
	 * $categoryData[0]['delivery_method']
	 * $categoryData[0]['credential']
	 * $categoryData[0]['exam_id']
	 * $categoryData[0]['city_id']
	 * $categoryData[0]['state_id']
	 *
	 * Input - array $categoryDataArr Combinations corresponding to which URL is to be obtained
	 *
	 * Output - Array of URLs in case of match | null otherwise
	 */
    function getUrlByMultipleParams($categoryDataArr) {
    	$finalResultData = array();

    	if(empty($categoryDataArr)) {
	    	return;
	    }

	    foreach ($categoryDataArr as $key => $categoryData) {
	    	if(empty($categoryData)) {
		    	return;
		    }

		    if(!isset($categoryData['stream_id']) || empty($categoryData['stream_id'])) {
				$categoryData['stream_id'] = 0;
			}
			if(!isset($categoryData['substream_id']) || empty($categoryData['substream_id'])) {
				$categoryData['substream_id'] = 0;
			}
			if(!isset($categoryData['specialization_id']) || empty($categoryData['specialization_id'])) {
				$categoryData['specialization_id'] = 0;
			}
			if(!isset($categoryData['base_course_id']) || empty($categoryData['base_course_id'])) {
				$categoryData['base_course_id'] = 0;
			}
			if(!isset($categoryData['education_type']) || empty($categoryData['education_type'])) {
				$categoryData['education_type'] = 0;
			}
			if(!isset($categoryData['delivery_method']) || empty($categoryData['delivery_method'])) {
				$categoryData['delivery_method'] = 0;
			}
			if(!isset($categoryData['credential']) || empty($categoryData['credential'])) {
				$categoryData['credential'] = 0;
			}
			if(!isset($categoryData['exam_id']) || empty($categoryData['exam_id'])) {
				$categoryData['exam_id'] = 0;
			}
			if((!isset($categoryData['state_id']) || empty($categoryData['state_id'])) && (!isset($categoryData['city_id']) || empty($categoryData['city_id']))) {
				$categoryData['state_id'] = 1;
			}
			if(!isset($categoryData['city_id']) || empty($categoryData['city_id'])) {
				$categoryData['city_id'] = 1;
			}

			$dataKey = $this->_generateKeyForCatBasedOnParams($categoryData);
			$finalResultData[$dataKey] = array();
			if(!empty($categoryData['filterData'])){
				$finalResultData[$dataKey]['filterData'] = $categoryData['filterData'];
			}
			if(!isset($categoryData['min_result_count'])) {
				$categoryData['min_result_count'] = 1;
			}
			
			if(!empty($categoryData['stream_id']) || !empty($categoryData['base_course_id'])) {
	    		$categoryDataModel[] = $categoryData;
	    	}
	    }
	    
	    $urls = $this->categoryPageSEOModel->getUrlByMultipleParams($categoryDataModel);
	    // _p($urls);die;
	    
	    foreach ($urls as $key => $url) {
	    	if(!empty($url['url'])) {

	    		$urls[$key]['url'] = '/'.$url['url'];
	    		$dataKey = $this->_generateKeyForCatBasedOnParams($url);
	    		
	    		if(!empty($finalResultData[$dataKey]['filterData'])){
	    			$queryParams = $this->getFilterQueryParams($finalResultData[$dataKey]['filterData']);
	    			if(!empty($queryParams)){
	    				$urls[$key]['url'] .= '?'.$queryParams;
	    			}
	    		}
	    		$finalResultData[$dataKey] = $urls[$key];
	    	}
	    }
	    $finalResultData = array_map(function($a){
	    	if($a['filterData']){
	    		unset($a['filterData']);
	    	}
	    	return $a;
	    },$finalResultData);

    	return $finalResultData;
    }


    function getMultipleUrlByMultipleParams($categoryData,$exclusionList=array(),$limit) {
    	$finalResultData = array();

    	if(empty($categoryData)) {
	    	return;
	    }

		$urls = $this->categoryPageSEOModel->getUrlByCustomParams($categoryData,$exclusionList,$limit);
		
		if(!empty($categoryData['filterData'])){
			$queryParams = $this->getFilterQueryParams($categoryData['filterData']);
			if(!empty($queryParams)){
				foreach ($urls as $key => $url) {
			        if(!empty($url['url'])) {
				 		$urls[$key]['url'] = SHIKSHA_HOME.'/'.$url['url'].'?'.$queryParams;
				    }
				}
			}
		}
		else{
			foreach ($urls as $key => $url) {
		        if(!empty($url['url'])) {
			 		$urls[$key]['url'] = SHIKSHA_HOME.'/'.$url['url'];
			    }
			}
		}

		return $urls;
    }

    /*
     * Set parameter with the keys as -
     * $filterData['stream']
     * $filterData['substream']
     * $filterData['specialization']
     * $filterData['base_course']
     * $filterData['education_type']
     * $filterData['delivery_method']
     * $filterData['credential']
     * $filterData['exam']
     * $filterData['city']
     * $filterData['state']
     *
     * Description - 
     * The function will return query parameters used as filters on the page.
     */
    public function getFilterQueryParams($filterData) {
    	foreach ($filterData as $entity => $filters) {
    		$entityAlias = $this->field_alias[$entity];
    		if(!empty($entityAlias)) {
    			if(!is_array($filters)) {
		            $filters = array($filters);
		        }
    			foreach ($filters as $key => $value) {
    				if(!empty($value)) {
    					$queryParams[] = $entityAlias."[]=".$value;
    				}
    			}
    		}
    	}
    	$string = implode('&', $queryParams);
    	
    	return $string;
    }

	public function redirectOldCategoryPages($params) {
		//hit db to get new url
		if(DEBUGGER) {
			_p('Old Params -');
			_p($params);
		}
		if($params['category_id'] == -1) {
			show_404();
			return;
		}

		//get old to new mapping
		$recatParams = $this->categoryPageSEOModel->getRecatParamsForOldCategory($params);
		
		if(!empty($params['exam'])) {
			$this->examMainLib = $this->CI->load->library("examPages/ExamMainLib");
			$examDetails = $this->examMainLib->getExamDetailsByName($params['exam']);
			$params['exam'] = $examDetails[$params['exam']]['examId'];
		}
		$recatParams['exam_id'] = $params['exam'];
		$recatParams['city_id'] = $params['city_id'];
		if((!isset($params['city_id']) || empty($params['city_id']) || $params['city_id'] == 1) && isset($params['state_id']) && $params['state_id'] > 0 ) {
			$recatParams['state_id'] = $params['state_id'];

			if(in_array($params['state_id'], $this->statesToIgnore)) {
				$recatParams['city_id'] = $this->statesCityMapping[$params['state_id']];
				unset($recatParams['state_id']);
			}
		}
		
		$recatParams['min_result_count'] = 0;
		if(DEBUGGER) {
			_p('New Params -');
			_p($recatParams);
		}

		//get new url
		if(!empty($recatParams['stream_id']) || !empty($recatParams['base_course_id'])) {
			if(!empty($recatParams['base_course_id'])) {
				$baseCourseObj = $this->baseCourseRepository->find($recatParams['base_course_id']);
				$isBaseCoursePopular = $baseCourseObj->getIsPopular();
				if($isBaseCoursePopular) {
					$recatParams['stream_id'] = 0;
					$recatParams['substream_id'] = 0;
					$recatParams['credential'] = 0;
					if(DEBUGGER) {
						_p('Popular course. Removing stream & substream -');
						_p($recatParams);
					}
				}
			}
			
			$redirectUrl = $this->getUrlByParams($recatParams);
		}
		
		if(!empty($redirectUrl)) {
			redirect($redirectUrl, 'location', 301);
		} else {
			//LF-6403
			if(!empty($recatParams['stream_id'])) {
				//Check for education type
				if(empty($recatParams['education_type'])) {
					$recatParams['education_type'] = FULL_TIME_MODE;
					$redirectUrl = $this->getUrlByParams($recatParams);
					unset($recatParams['education_type']);

					if(DEBUGGER) {
						_p('Added education type');
						_p($recatParams);
					}
				} else if($recatParams['education_type'] == FULL_TIME_MODE) {
					//$education_type_org = $recatParams['education_type'];
					unset($recatParams['education_type']);
					$redirectUrl = $this->getUrlByParams($recatParams);
					
					$recatParams['education_type'] = FULL_TIME_MODE;

					if(DEBUGGER) {
						_p('Removed education type');
						_p($recatParams);
					}
				}
				
				$filterData = array();
				if(empty($redirectUrl)) {
					$filterData['specialization'] = $recatParams['specialization_id'];
					unset($recatParams['specialization_id']);

					$filterData['education_type'] = $recatParams['education_type'];
					unset($recatParams['education_type']);
					
					$filterData['delivery_method'] = $recatParams['delivery_method'];
					unset($recatParams['delivery_method']);
					
					$filterData['credential'] = $recatParams['credential'];
					unset($recatParams['credential']);
					
					$filterData['exam'] = $recatParams['exam_id'];
					unset($recatParams['exam_id']);
					
					$redirectUrl = $this->getUrlByParams($recatParams, $filterData);
				}

				if(empty($redirectUrl)) {
					$filterData['base_course'] = $recatParams['base_course_id'];
					unset($recatParams['base_course_id']);
					
					$redirectUrl = $this->getUrlByParams($recatParams, $filterData);
				}
			}
			elseif(!empty($recatParams['base_course_id']) && $isBaseCoursePopular) { //LF-6346
				if(empty($recatParams['education_type'])) {
					$recatParams['education_type'] = FULL_TIME_MODE;
					if(DEBUGGER) {
						_p('Adding full time');
						_p($recatParams);
					}
					$redirectUrl = $this->getUrlByParams($recatParams);
					$recatParams['education_type'] = 0;
				}

				if(empty($redirectUrl) && !empty($recatParams['exam_id'])) {
					$filterData['exam'] = $recatParams['exam_id'];
					unset($recatParams['exam_id']);
					
					if(DEBUGGER) {
						_p('Removing exam from original URL');
						_p($recatParams);
					}
					$redirectUrl = $this->getUrlByParams($recatParams, $filterData);

					if(empty($redirectUrl) && empty($recatParams['education_type'])) {
						$recatParams['education_type'] = FULL_TIME_MODE;
						if(DEBUGGER) {
							_p('Adding full time after removing exam');
							_p($recatParams);
						}
						$redirectUrl = $this->getUrlByParams($recatParams, $filterData);
					}
				}
			}
			
			if(!empty($redirectUrl)) {
				if(DEBUGGER) {
					_p('Cutting down params');
					_p($recatParams);
				}
				redirect($redirectUrl, 'location', 301);
			} else {
				//show zrp
				if(DEBUGGER) {
					_p('ENTRY NOT FOUND IN category_page_seo TABLE');
				}
				show_404();
			}
		}
	}

	public function getCategoryPageMainSponsoredInstitutes($criteria) {
		$result = $this->categoryProductModel->getCategoryPageMainSponsoredInstitutes($criteria);
		foreach ($result as $key => $value) {
			if($value['product_type'] == 'category_sponsor') {
				$sponsoredInstitutes['cs'][] = $value['institute_id'];
				unset($result[$key]);
			}
		}
		foreach ($result as $key => $value) {
			if($value['product_type'] == 'main' && !in_array($value['institute_id'], $sponsoredInstitutes['cs'])) {
				$sponsoredInstitutes['main'][] = $value['institute_id'];
				unset($result[$key]);
			}
		}
		return $sponsoredInstitutes;
	}

	/**
	* Function to fetch the category Page Data from category_page_seo table
	*
	* @param $scriptUrl {string} URL for which data needs to be fetched
	*
	*/
	public function getCategoryPageDataByUrl($scriptUrl=null){
		if(empty($scriptUrl)) {
			return array();
		}

		// Generate Hash(hash function defined in listingcommon_helper)
		$scriptUrlHash = murmurhash3_int($scriptUrl);

		// Fetch Data from DB based on Hash
		$data = $this->categoryPageSEOModel->getHashUrlParsedParams($scriptUrlHash);
	
		$count = count($data);
		// If count of result > 1, then check on the basis of url else return the result
        if($count > 1){
            foreach ($data as $key => $value) {
                if($value['url'] == $scriptUrl){
                    $data = $value;
                    break;
                }
            }
        }else if($count == 1){
            $data = reset($data);
        }

        return $data;
	}

	function _generateKeyForCatBasedOnParams($categoryData){
		if(empty($categoryData)){
			return "";
		}
		$keyData = array();
		$keyData[] = $stream_id 				= $categoryData['stream_id'];
		$keyData[] = $substream_id		    = $categoryData['substream_id'];
		$keyData[] = $specialization_id 	= $categoryData['specialization_id'];
		$keyData[] = $base_course_id 		= $categoryData['base_course_id'];
		$keyData[] = $education_type 		= $categoryData['education_type'];
		$keyData[] = $delivery_method 		= $categoryData['delivery_method'];
		$keyData[] = $credential 			= $categoryData['credential'];
		$keyData[] = $exam_id 				= $categoryData['exam_id'];
		$keyData[] = $city_id 				= $categoryData['city_id'];
		$keyData[] = $state_id 				= $categoryData['state_id'];

		$key = implode("_", $keyData);
		return $key;
	}

	function getMultipleUrlByParams($categoryData) {
		if(empty($categoryData)) {
	    	return;
	    }
		
		$urls = $this->categoryPageSEOModel->getMultipleUrlByParams($categoryData);

    	foreach ($urls as $key => $value) {
    		$urls[$key]['url'] = SHIKSHA_HOME.'/'.$value['url'];
    	}

    	return $urls;
	}

	function getCTPsByInstituteOrCourse($instituteIds, $courseIds) {
		if(!empty($instituteIds) || !empty($courseIds)) {
			$virtualCititesMapping = $this->getVirtualCityMapping();
			$criteriaArray = $this->categoryPageModel->getCTPCriteriaByInstituteOrCourse($instituteIds, $courseIds, $virtualCititesMapping);
		}
		foreach ($criteriaArray as $instituteId => $value) {
			$result[$instituteId] = $this->categoryPageSEOModel->getURLByMultipleCriteria($value);
		}
		//_p($result); die;
		foreach ($result as $instituteId => $urls) {
			foreach ($urls as $key => $url) {
				$finalList[$url] = $url;
			}
		}
		//_p($finalList); die;
		return $finalList;
	}

	function getVirtualCityMapping() {
		$virtualCities = array_keys($this->searchCommon->getVirtualCityMappingForSearch());
		foreach ($virtualCities as $key => $cityId) {
			$citites = $this->locationRepository->getCitiesByVirtualCity($cityId);
			foreach ($citites as $key => $cityObj) {
				$virtualCititesMapping[$cityObj->getId()] = $cityId;
			}
		}

		return $virtualCititesMapping;
	}

	/*
	 * 
	 */
	function fetchClientCoursesForCriteria($criteria) {
		$cacheKeys = $this->createCacheKeyForClientCoursesCriteria($criteria);
		// _p($cacheKeys);

		//fetch from cache, returns array [cachKey] = [list of course ids]
		$cachedData = $this->nationalCategoryListCache->getClientCoursesCache($cacheKeys);
		// _p("Data from cache");
		// _p($cachedData);
		// _p("===========================DONE===========================");

		foreach ($cachedData as $index => $value) {
			if(empty($value)) {
				if(!empty($criteria[$index])) {
					$remainingCriteria[$index] = $criteria[$index];
				}
			}
			else {
				$courseIds[$index] = json_decode($value);
				if($value == -1) {
					unset($courseIds[$index]);
				}
			}
		}
		//_p($remainingCriteria);

		$remainingCourseIds = $this->getClientCoursesForCriteriaFromSolr($remainingCriteria, $cacheKeys);
		foreach ($remainingCourseIds as $index => $value) {
			$courseIds[$index] = $value;
		}

		return $courseIds;
	}

	/*
	 * Key = [stream]_[substream]_[specialization]_[base_course]_[education_type]_[delivery_method]_[credential]_[exam]_[city]_[state]
	 */
	function createCacheKeyForClientCoursesCriteria($criteria) {
		foreach ($criteria as $key => $row) {
			if(empty($row['stream'])){
				$criteria[$key]['stream'] = 0;
			}
			if(empty($row['substream'])){
				$criteria[$key]['substream'] = 0;
			}
			if(empty($row['specialization'])){
				$criteria[$key]['specialization'] = 0;
			}
			if(empty($row['base_course'])){
				$criteria[$key]['base_course'] = 0;
			}
			if(empty($row['education_type'])){
				$criteria[$key]['education_type'] = 0;
			}
			if(empty($row['delivery_method'])){
				$criteria[$key]['delivery_method'] = 0;
			}
			if(empty($row['credential'])){
				$criteria[$key]['credential'] = 0;
			}
			if(empty($row['exam'])){
				$criteria[$key]['exam'] = 0;
			}
			if(empty($row['city'])){
				$criteria[$key]['city'] = 1;
			}
			if(empty($row['state'])){
				$criteria[$key]['state'] = 1;
			}
			$cacheKeys[$key] = $criteria[$key]['stream'].'_'.$criteria[$key]['substream'].'_'.$criteria[$key]['specialization'].'_'.$criteria[$key]['base_course'].'_'.$criteria[$key]['education_type'].'_'.$criteria[$key]['delivery_method'].'_'.$criteria[$key]['credential'].'_'.$criteria[$key]['exam'].'_'.$criteria[$key]['city'].'_'.$criteria[$key]['state'];
		}

		return $cacheKeys;
	}

	function getClientCoursesForCriteriaFromSolr($criteria, $cacheKeys) {
		foreach ($criteria as $key1 => $row) {
			$solrRequestData = array();
			foreach ($row as $key2 => $value) {
				if(!empty($value)) {
					$solrRequestData['filters'][$key2][] = $value;
				}
			}
			
			$solrResults = $this->solrClient->getClientCoursesBasedOnCriteria($solrRequestData);
			$courseIds[$key1] = $solrResults;

			if(empty($solrResults)) {
				$solrResults = -1;
			}

			//store in cache
			$this->nationalCategoryListCache->storeClientCoursesCache($cacheKeys[$key1], json_encode($solrResults));
		}

		return $courseIds;
	}
}
