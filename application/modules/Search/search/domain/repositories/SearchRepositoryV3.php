<?php

class SearchRepositoryV3 extends EntityRepository{
	
	private $instituteCount;
	private $courseCount;
	private $instituteData;
	private $filters;


	public function __construct($request){
		$this->CI = & get_instance();
		$this->request                 = $request;
		$this->solrClient = $this->CI->load->library('search/Solr/SolrClient');

		$this->CI->load->builder("nationalInstitute/InstituteBuilder");
        $instituteBuilder = new InstituteBuilder();
        $this->instituteRepository = $instituteBuilder->getInstituteRepository();

        $this->CI->load->config("nationalCategoryList/nationalConfig");
        $this->field_alias = $this->CI->config->item('FIELD_ALIAS');

        $this->solrRequestGenerator = $this->CI->load->library('search/Solr/SolrRequestGenerator');
	}

	public function getRawSearchData($params = array()){
		$keyword = $this->request->getSearchKeyword();
		// unset($this->request->CI);
		if(empty($keyword) && $params["forExamPage"] == false) {
			return false;
		}
		$solrRequestData['keyword'] = $keyword;
		$solrRequestData['oldKeyword'] = $this->request->getOldKeyword();

		$solrRequestData['additionalFacetsToFetch'] = $this->request->getAdditionalFacetsToFetch();

		//get filters from QER
		$solrRequestData['qerFilters'] = $this->request->getQERFilters();

		if($this->request->getRequestFrom() == 'filterBucket' && isMobileRequest()){
			$solrRequestData['getFiltersOnly'] = true;
		}
		
		//get user applied filters
		$solrRequestData['filters'] = $this->request->getAppliedFilters();
		if($this->request->isStreamClosedSearch()) {
            $solrRequestData['facetCriterion']['type'] = 'stream';
            $solrRequestData['facetCriterion']['id'] = reset($this->request->getStream());
        }
        else if($this->request->isSubStreamClosedSearch()) {
            $solrRequestData['facetCriterion']['type'] = 'substream';
            $solrRequestData['facetCriterion']['id'] = reset($this->request->getSubStream());
        }
        else if($this->request->isBaseCourseClosedSearch()) {
            $solrRequestData['facetCriterion']['type'] = 'base_course';
            $solrRequestData['facetCriterion']['id'] = reset($this->request->getBaseCourse());
        }
		$solrRequestData['requestType'] = 'search_result';
		
		$this->_combineUserAndQERFilters($solrRequestData);
		$solrRequestData['userAppliedFilters'] = $solrRequestData['filters'];

		//get page details
		if($params["forExamPage"]){
			$solrRequestData['pageLimit'] = $params["limit"];
			$solrRequestData['filters']['stream'][] = $params["streamId"];
			$solrRequestData['filters']['exam'][]	= $params["examId"];			
		}
		else{
			$solrRequestData['pageLimit'] = $this->request->getPageLimit();
		}
		
		$solrRequestData['pageNum'] = $this->request->getCurrentPageNum();
		$solrRequestData['requestFrom'] = $this->request->getRequestFrom();
		
		$solrRequestData['getInstitutesAndCourses'] = $this->request->getInstitutesFlag();
		$solrRequestData['getFilters'] = $this->request->getFiltersFlag();

		$solrRequestData['isAutosuggestorClosedSearch'] = $this->request->isAutosuggestorClosedSearch();
		$solrRequestData['sort_by'] = "relevance";
		
		$solrRequestData['getParentFilters'] = $this->request->getParentFiltersFlag();

		if(DO_SEARCHPAGE_TRACKING){
			$trackingSearchId = $this->request->getTrackingSearchId();
			$trackingFilterId = $this->request->getTrackingFilterId();
			if(!empty($trackingSearchId)){
				$solrRequestData['trackingSearchId'] = $trackingSearchId;
			}
			if(!empty($trackingFilterId)){
				$solrRequestData['trackingFilterId'] = $trackingFilterId;
			}
		}
		$solrRequestData['isOpenToClose'] = $this->request->getTwoStepClosedSearch();

		//get search data from solr
		if($params["forExamPage"] == false){
			$solrResults = $this->solrClient->getFiltersAndInstitutes($solrRequestData);	
		}
		//for exam page
		else{
			$solrResults = $this->solrClient->getFiltersAndInstitutes($solrRequestData, true);
			$data['instCourseMapping'] = $solrResults['instituteIdCourseIdMap'];
			$data['totalCount'] = $solrResults['numOfInstitutes'];
			return $data;
		}
        if($solrResults['numOfInstitutes'] == 0) {
          //  _p('ZERO RESULT');
        }

        //set result count in description
        // $this->request->setCountInDescription($solrResults['numOfInstitutes']);

        //get details of institutes to show on tuple
        $data['institutes'] = $this->getInstituteData($solrResults['instituteIdCourseIdMap']);
        
        //get details to help render view easily
        $data['totalInstituteCount'] = $solrResults['numOfInstitutes'];
        $data['filters'] = $solrResults['filters'];
        $data['fieldAlias'] = $solrResults['fieldAlias'];
        $data['selectedFilters'] = $solrResults['selectedFilters'];
        if(isMobileRequest()){
        	$selectedStream = (array)$this->request->getStream();
        	if(!empty($selectedStream)){
        		$data['selectedFilters']['stream'][$selectedStream[0]] = 'abc';
        	}
        }
        $data['relevantResults'] = $solrResults['relevantResults'];
        $data['appliedFilters'] = $solrRequestData['userAppliedFilters'];
        if(!empty($data['relevantResults'])){
        	$this->request->setRelevantResults($data['relevantResults']);	
        }
        unset($solrResults);
        return $data;
	}


	private function _combineUserAndQERFilters(& $solrRequestData) {
		$categoryFiltersByUser = $this->CI->input->get('uaf',true);
		$filterListOnPage = $this->solrRequestGenerator->getFacetList($solrRequestData);
		$this->filters = array('institute', 'stream', 'substream', 'specialization', 'base_course', 'popular_group', 'cert_prov', 'exams', 'locality', 'city', 'state', 'fees', 'course_level', 'credential', 'education_type', 'delivery_method', 'course_type', 'ownership', 'accreditation', 'cr_exists', 'min_course_review');
		foreach ($this->filters as $key => $filterName) {
				$filterValues = array();
		 	    switch($filterName) {
		 	    	case 'stream':
	 	    		case 'popular_group':	 	    			
	 	    		case 'certificate_provider':		 	    		
	 	    		case 'state':	 	    			
	 	    		case 'city':		 	    		
	 	    		case 'locality':	 	    			
	 	    		case 'course_level':	 	    			
	 	    		case 'course_type':
	 	    		case 'institute':
	 	    		case 'base_course':
	 	    		case 'credential':
	 	    		case 'course_type':
	 	    			$uafFilterName = $filterName;
	 	    			if(in_array($filterName, array('city', 'state', 'locality'))) {
	 	    				$uafFilterName = 'location';
	 	    			}
	 	    			if(is_array($solrRequestData['filters'][$filterName]) && count($solrRequestData['filters'][$filterName]) > 0) {
	                        $filterValues = $solrRequestData['filters'][$filterName];
	                    }
	                    else if(is_array($solrRequestData['qerFilters'][$filterName]) && count($solrRequestData['qerFilters'][$filterName]) > 0 && !in_array($uafFilterName, $categoryFiltersByUser)) {
	                        $filterValues = $solrRequestData['qerFilters'][$filterName];
	                    }
	                    
	                    $solrRequestData['filters'][$filterName] = $filterValues;
	 	    			break;

		 	    	case 'specialization':
		 	    		if(is_array($solrRequestData['filters'][$filterName]) && count($solrRequestData['filters'][$filterName]) > 0) {
	                        $filterValues = $solrRequestData['filters'][$filterName];
	                    }
	                    else if(is_array($solrRequestData['qerFilters'][$filterName]) && count($solrRequestData['qerFilters'][$filterName]) > 0 && !in_array('specializations', $categoryFiltersByUser) && !in_array('sub_spec', $categoryFiltersByUser)) {
	                        $filterValues = $solrRequestData['qerFilters'][$filterName];
	                    }
	                    
		 	    		if(!in_array('sub_spec', $filterListOnPage)) {
		 	    			$solrRequestData['filters'][$filterName] = $filterValues;
		 	    		} else {
		 	    			foreach ($filterValues as $key => $value) {
		 	    				$filterValues[$key] = $this->field_alias['substream'].'_any::'.$this->field_alias['specialization']."_".$value;
		 	    			}
		 	    			if(empty($solrRequestData['filters']['sub_spec'])) {
			 	    			$solrRequestData['filters']['sub_spec'] = $filterValues;
			 	    		} else {
			 	    			$solrRequestData['filters']['sub_spec'] = array_merge($solrRequestData['filters']['sub_spec'], $filterValues);
			 	    		}
		 	    			unset($solrRequestData['filters']['specialization']);
		 	    		}
	                    break;

	                case 'substream':
	                	//if multiple substreams are detected by qer & stream is on the page, sub_spec filter will be shown
	                	if(is_array($solrRequestData['filters'][$filterName]) && count($solrRequestData['filters'][$filterName]) > 0) {
	                        $filterValues = $solrRequestData['filters'][$filterName];
	                    }
	                    else if(is_array($solrRequestData['qerFilters'][$filterName]) && count($solrRequestData['qerFilters'][$filterName]) > 0 && !in_array('sub_spec', $categoryFiltersByUser)) {
	                        $filterValues = $solrRequestData['qerFilters'][$filterName];
	                    }

		 	    		if(!in_array('sub_spec', $filterListOnPage)) {
		 	    			$solrRequestData['filters'][$filterName] = $filterValues;
		 	    		} else {
		 	    			foreach ($filterValues as $key => $value) {
		 	    				$filterValues[$key] = $this->field_alias['substream']."_".$value;
		 	    			}
		 	    			if(empty($solrRequestData['filters']['sub_spec'])) {
			 	    			$solrRequestData['filters']['sub_spec'] = $filterValues;
			 	    		} else {
			 	    			$solrRequestData['filters']['sub_spec'] = array_merge($solrRequestData['filters']['sub_spec'], $filterValues);
			 	    		}
		 	    			unset($solrRequestData['filters']['substream']);
		 	    		}
	                	break;

	                case 'delivery_method':	 	    			
	 	    		case 'education_type':
	 	    			//ASSUMPTION --- et & dm will always come together. They will never come as separate filters
	 	    			if(is_array($solrRequestData['filters'][$filterName]) && count($solrRequestData['filters'][$filterName]) > 0) {
	                        $filterValues = $solrRequestData['filters'][$filterName];
	                    }
	                    else if(is_array($solrRequestData['qerFilters'][$filterName]) && count($solrRequestData['qerFilters'][$filterName]) > 0 && !in_array('et_dm', $categoryFiltersByUser)) {
	                        $filterValues = $solrRequestData['qerFilters'][$filterName];
	                    }
	                    $addFullTimeInFilters = 0;
	                    foreach ($filterValues as $key => $value) {
	                    	if($filterName == 'education_type') {
	                    		if($value == FULL_TIME_MODE) {
	 	    						$filterValues[$key] = $this->field_alias['education_type']."_".$value;
	                    		} else {
	                    			//part time
	                    			$filterValues[$key] = $this->field_alias['education_type']."_".PART_TIME_MODE.'::'.$this->field_alias['delivery_method'].'_any';
	                    		}
	                    	}
	                    	else {
	                    		if($value == CLASSROOM_MODE) {
	                    			// classroom
	                    			$addFullTimeInFilters = 1;
	                    		}
	                    		$filterValues[$key] = $this->field_alias['education_type']."_".PART_TIME_MODE.'::'.$this->field_alias['delivery_method'].'_'.$value;
	                    	}
	 	    			}
	 	    			if($addFullTimeInFilters) {
	 	    				$filterValues[] = $this->field_alias['education_type']."_".FULL_TIME_MODE;
	 	    			}
	 	    			
	 	    			if(!empty($solrRequestData['filters']['et_dm']) && !empty($filterValues)) {
	                    	$solrRequestData['filters']['et_dm'] = array_merge($solrRequestData['filters']['et_dm'], $filterValues);
	                    	unset($solrRequestData['filters'][$filterName]);
	 	    			} else if(!empty($filterValues)) {
	 	    				$solrRequestData['filters']['et_dm'] = $filterValues;
	 	    				unset($solrRequestData['filters'][$filterName]);
	 	    			}
	                    break;
		 	    }
		}
	}

	public function getInstituteData($instituteData) {
        $institutes                   = array();
        $instituteWithPopularCourses  = array();
        $instituteWithLoadMoreCourses = array();
        $instituteWithCourseCount     = array();
        
        foreach ($instituteData as $instituteId => $courses) {
            $courseCount = count($courses);
            $instituteWithCourseCount[$instituteId] = $courseCount;
            foreach ($courses as $key => $value) {
                if($key == 0) {
                    $instituteWithPopularCourses[$instituteId][] = $value;          
                    $popularCourses[] = $value;                     
                }else{
                    $instituteWithLoadMoreCourses[$instituteId][] = $value;                 
                }
            }
        }
        
        //lookup with respect to performance
        $institutes['instituteData']                = $this->instituteRepository->findWithCourses($instituteWithPopularCourses, array('basic', 'facility','media','location'), array('basic' , 'location','eligibility'));
        $institutes['popularCourses']               = $popularCourses;
        $institutes['instituteLoadMoreCourses']     = $instituteWithLoadMoreCourses;
        $institutes['instituteCourseCount']         = $instituteWithCourseCount;
        $institutes['instituteCountInCurrentPage']  = count($instituteWithPopularCourses);

        return $institutes;
    }
}