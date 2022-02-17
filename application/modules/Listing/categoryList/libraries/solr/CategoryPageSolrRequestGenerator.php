<?php

class CategoryPageSolrRequestGenerator
{
    private $CI;
    private $solrServer;
    private $request;
    private $citiesSetInURL;
      
    function __construct()
    {
        $this->CI = & get_instance();
        
        $this->CI->load->builder('SearchBuilder','search');
        $this->solrServer = SearchBuilder::getSearchServer();
        
        $this->CI->load->library('search/Common/SearchCommon');
        $this->searchCommon = new SearchCommon;
    }
    
    public function generate(CategoryPageRequest $request, $applyFiltersFlag = 1)
    {
        $this->request = $request;
    	$solrBaseURL = $this->solrServer->getSolrURL('course','select');
        $sortingCriteria 	= $this->request->getSortingCriteria();
        
        /*
         * Get request attributes
         */ 
        $categoryId = (int) $request->getCategoryId();
        $subCategoryId = (int) $request->getSubCategoryId();
        $LDBCourseId = (int) $request->getLDBCourseId();
        $countryId = (int) $request->getCountryId();
        $stateId = (int) $request->getStateId();
        $cityId = (int) $request->getCityId();
        $zoneId = (int) $request->getZoneId();
        $localityId = (int) $request->getLocalityId();

//        if($request->isRequestForSingleCityPageHavingAllCityResult()) {
//        	$cityId = $stateId = 1;
//        }

        $urlComponents = array();
        $urlComponents[] = 'q=*:*';
        $urlComponents[] = 'wt=phps';
        
        //Filters to hide
        global $filtersToHideInSolrRequest;
        global $SOLR_DATA_FIELDS_ALIAS;
        $filterToSolrAttribMapping = array(
            'mode' => "course_type",
            'courseLevel' => "course_level_cp"	
        );
        
        foreach ($filtersToHideInSolrRequest as $filterType => $filterValues)
        {
            $urlComponentsForFilterToHide= array();	
            foreach($filterValues as $filterValue)
            {
                $urlComponentsForFilterToHide[] = $filterToSolrAttribMapping[$filterType].":\"".str_replace(" ","%20",$filterValue)."\"";
            }
            $urlComponents[] = 'fq=-('.implode('%20OR%20',$urlComponentsForFilterToHide).')';
        }
        
        //Filters to be applied
        $urlComponents[] = 'fq=facetype:course';
        
        //Category/subcategory/LDB course filters
        if($LDBCourseId > 1) {
            $urlComponents[] = 'fq=course_ldb_id:'.$LDBCourseId;
        }
        else if($subCategoryId > 1) {
            $urlComponents[] = 'fq=course_category_id_list:'.$subCategoryId;
        }
        else {
            $urlComponents[] = 'fq=course_category_id_list:'.$categoryId;
        }
        
        //Location filters
        $virtualCities      = $this->searchCommon->getVirtualCityMappingForSearch();
        $virtualCitiesIds   = array_keys($virtualCities);
        if($localityId) {
            $urlComponents[] = 'fq=course_locality_id:'.$localityId;
        }
        else if($zoneId) {
            $urlComponents[] = 'fq=course_zone_id:'.$zoneId;
        }
        else if($cityId && $cityId != 1) {
            $urlComponents[] = in_array($cityId,$virtualCitiesIds) ? 'fq=course_virtual_city_id:'.$cityId : 'fq=course_city_id:'.$cityId;
        }
        else if($stateId && $stateId != 1) {
            $urlComponents[] = 'fq=course_state_id:'.$stateId;
        }
        else if($countryId) {
            $urlComponents[] = 'fq=course_country_id:'.$countryId;
        }
         
        $urlComponents[] = 'fq=course_country_id:2';   // fetch document related to india only        
        
        //Applied filters
        if($request->isMultilocationPage()){
            $filters = $request->getAppliedFilters();
            $userPreferredLocation = $request->getUserPreferredLocationOrder();
            $selectedCities = array();
            $selectedStates = array();
            foreach($userPreferredLocation as $locationRow)
            {
                if($locationRow["location_type"] == 'city')
                    $selectedCities[] = $locationRow["location_id"];
                else if($locationRow["location_type"] == 'state')
                    $selectedStates[] = $locationRow["location_id"];
            }
            $filters['city']    = $selectedCities;
            $filters['state']   = $selectedStates;
            
            // for multilocation case we need to include city,states in filters(with tags) as well as in parent category page query(without tags)
            // to prevent the case of making the filters at india level
            $locationComponents = array();
            if(is_array($selectedCities) && count($selectedCities) > 0) {
                foreach($selectedCities as $city) {
                    $locationComponents[] = in_array($city,$virtualCitiesIds) ? 'course_virtual_city_id:'.$this->_escapeSolrQueryString($city): 'course_city_id:'.$this->_escapeSolrQueryString($city);
                };
            }
            
            if(is_array($selectedStates) && count($selectedStates) > 0) {
                foreach($selectedStates as $stateRowId) {
                    $locationComponents[] = 'course_state_id:'.$this->_escapeSolrQueryString($stateRowId);
                };
            }
	
            if(!empty($locationComponents))
                $urlComponents[] = 'fq=('.implode('%20OR%20',$locationComponents).')';
    
            $urlComponentsForAppliedFilters = $this->_getURLComponentsForAppliedFilters($filters);
            $urlComponents = array_merge($urlComponents,$urlComponentsForAppliedFilters);
        }
        else //if($applyFiltersFlag)
        {
            $appliedFilters = $request->getAppliedFilters();
            $urlComponentsForAppliedFilters = $this->_getURLComponentsForAppliedFilters($appliedFilters);
            $urlComponents = array_merge($urlComponents,$urlComponentsForAppliedFilters);
        }
        
        //Fields to be returned in data
        global $CP_SOLR_FL_LIST;
		$fieldsToFetch = $CP_SOLR_FL_LIST;
        
        if(isset($sortingCriteria['sortBy']) && $sortingCriteria['sortBy'] != "" && $sortingCriteria['sortBy'] != 'none') {
            global $CP_SOLR_FL_LIST_SORT;
            $fieldsToFetchForSorting = $CP_SOLR_FL_LIST_SORT;
            $fieldsToFetch = array_merge($fieldsToFetch, (array)$fieldsToFetchForSorting[$sortingCriteria['sortBy']]);
        }  else if($request->isMultilocationPage())	{
        	// performance change : city,state fl would be added to curl FL if required.
        	$usrLocPref = $request->getUserPreferredLocationOrder();
        	$usrLocPrefTypes = array_map(function($a){return $a['location_type'];},$usrLocPref);
        	$usrLocPrefTypes = array_unique($usrLocPrefTypes);
        	
        	global $CP_SOLR_FL_LIST_FOR_MULTILOCATION;
        	foreach($usrLocPrefTypes as $usrLocPrefType)
        	{
        		$fieldsToFetch = array_merge($fieldsToFetch,(array)$CP_SOLR_FL_LIST_FOR_MULTILOCATION[$usrLocPrefType]);
        	}
        }
        
        foreach($fieldsToFetch as $fields)
	{
	    if($SOLR_DATA_FIELDS_ALIAS[$fields])
		$fieldsToFetchWithAlias[] = $SOLR_DATA_FIELDS_ALIAS[$fields].":".$fields;
	    else
		$fieldsToFetchWithAlias[] = $fields;
	}

        $urlComponents[] 	= 'fl='.implode(',',$fieldsToFetchWithAlias);
        
        //$urlComponents[] = 'fl='.implode(',',$fieldsToFetch);//.",course_duration_value,course_duration_unit";
        $urlComponents[] = 'rows=1000000'; //All the rows
        
        $urlComponents[] = 'hl=off'; //Highlighting is off
        
            //Faceting
            //if(!$applyFiltersFlag)
        if(!$request->isAJAXCall())
        {
	    $taggedFilters = "{!ex=ffee,faima,fclasstime,floc,fcourselevel,fmode,fduration,fdpref,fcourseexam,fexam,freview}";
            $urlComponents[] = 'facet=true';
            $urlComponents[] = 'facet.mincount=1';
            $urlComponents[] = 'facet.limit=-1';
            $urlComponents[] = 'facet.field='.$taggedFilters.'course_level_cp';
            $urlComponents[] = 'facet.field='.$taggedFilters.'course_type_facet';
            $urlComponents[] = 'facet.field='.$taggedFilters.'course_location_cluster';
            $urlComponents[] = 'facet.field='.$taggedFilters.'course_RnR_valid_exams';//'facet.field=course_eligibility_required';
            $urlComponents[] = 'facet.field='.$taggedFilters.'course_eligibility_required_cp';
            $urlComponents[] = 'facet.field='.$taggedFilters.'course_degree_pref';
            $urlComponents[] = 'facet.field='.$taggedFilters.'course_class_timings';
            $urlComponents[] = 'facet.field='.$taggedFilters.'institute_aima_rating';
            $urlComponents[] = 'facet.field='.$taggedFilters.'course_normalised_fees';
            $urlComponents[] = 'facet.field='.$taggedFilters.'course_ldb_id';
            $urlComponents[] = 'facet.field='.$taggedFilters.'course_category_id_list';
            $urlComponents[] = 'facet.field='.$taggedFilters.'course_virtual_city_id';
        
            if(!in_array($request->getSubCategoryId(), array(23,56))) {
        	$urlComponents[] = 'facet.pivot='.$taggedFilters.'course_duration_value,course_duration_unit';
            }
        
        }
        
        $urlComponents[] = 'group=true';
        $urlComponents[] = 'group.field=institute_id';
        //$urlComponents[] = 'group.main=true';
        //$urlComponents[] = 'group.format=simple';
        $urlComponents[] = 'group.ngroups=true';
        $urlComponents[] = 'group.facet=true';
        $urlComponents[] = 'group.limit=100';
        $urlComponents[] = 'group.sort=course_order+asc';
        
        $solrRequest = $solrBaseURL.implode('&',$urlComponents);

        return $solrRequest;
    }
    

 	public function _makeURLComponentForExamWithScore($appliedFiltersExamFilter, $categoryId) {
		global $MBA_EXAM_CUTOFF_LIMITS;
        if(empty($categoryId)) {
            $categoryId = $this->request->getCategoryId();
        }
		
		foreach ( $appliedFiltersExamFilter as $examWithScore ) {
			$examComponents = explode ( '_', $examWithScore );
			$examName = $examComponents [0];
			$examScore = $examComponents [1];
			if ($categoryId == 3) {
				$examName = in_array ( $examName, array_keys ( $MBA_EXAM_CUTOFF_LIMITS ) ) ? $examName : 'defaultExam';
				$scoreType = $MBA_EXAM_CUTOFF_LIMITS [$examName] ['type'];
				if ($scoreType == "around") {
					if ($MBA_EXAM_CUTOFF_LIMITS [$examName] ['lower'] == $examScore) 					// in - CAT_54 out- course_exam_CAT : [* TO 54]
					{
						$examcURLComponents [] = 'course_exam_' . removeSpacesFromString($examComponents[0]). ":{0%20TO%20" . $MBA_EXAM_CUTOFF_LIMITS [$examName] ['lower'] . "]";
					} else if ($MBA_EXAM_CUTOFF_LIMITS [$examName] ['upper'] == $examScore) 					// in - CAT_95 out- course_exam_CAT : [95 TO *]
					{
						
						$upperLimit = $MBA_EXAM_CUTOFF_LIMITS [$examName] ['upper'];
						if($examName != "defaultExam") {
							$upperLimit = $upperLimit + 1;
						}
						$examcURLComponents [] = 'course_exam_' . removeSpacesFromString($examComponents[0]) . ":[" . $upperLimit . "%20TO%20*]";
					} else if ($MBA_EXAM_CUTOFF_LIMITS [$examName] ['upper'] > $examScore && $MBA_EXAM_CUTOFF_LIMITS [$examName] ['lower'] < $examScore) { // in - CAT_70 out- course_exam_CAT : [65 TO 75]
						$diff = $MBA_EXAM_CUTOFF_LIMITS [$examName] ['diff'];
						$examcURLComponents [] = 'course_exam_' . removeSpacesFromString($examComponents[0]) . ":[" . ($examScore - $diff) . "%20TO%20" . ($examScore + $diff) . "]";
					} else { // in - CAT_0 out- course_RnR_valid_exams : CAT
						$examcURLComponents [] = 'course_RnR_valid_exams:"' . $this->_escapeSolrQueryString ( $examComponents [0] ) . '"';
					}
				} else if ($scoreType == "range") {
					$range = explode ( "-", $examScore );
					if (count ( $range ) > 1) 					// in - CMAT_0_299 out- course_exam_CMAT:[0 TO 299]
					{
						$examcURLComponents [] = 'course_exam_' . removeSpacesFromString($examComponents[0]) . ":{" . $range [0] . "%20TO%20" . $range [1] . "]";
					} else if ($range [0] > 0) { // in - GMAT_650 out- course_exam_GMAT:[650 TO *]
						$examcURLComponents [] = 'course_exam_' . removeSpacesFromString($examComponents[0]) . ":[" . $range [0] . "%20TO%20*]";
					} else { // in - GMAT_0 out- course_RnR_valid_exams:GMAT
						$examcURLComponents [] = 'course_RnR_valid_exams:"' . $this->_escapeSolrQueryString ( $examComponents [0] ) . '"';
					}
				}
			} else if ($categoryId == 2) {
				$engineering_exams_with_score = $GLOBALS ['ENGINEERING_EXAMS_REQUIRED_SCORES'];
				$marksType = in_array ( $examName, $engineering_exams_with_score ) ? 'score' : 'rank';
				if ($examScore > 0 && $marksType == 'rank') {
					$examcURLComponents [] = 'course_exam_' . removeSpacesFromString($examComponents[0]) . ":[" . $examScore . "%20TO%20*]";
					$examcURLComponents [] = 'course_exam_' . removeSpacesFromString($examComponents[0]) . ":0";
						
				} else if ($examScore > 0 && $marksType == 'score') {
					$examcURLComponents [] = 'course_exam_' . removeSpacesFromString($examComponents[0]) . ":{0%20TO%20" . $examScore . "]";
					$examcURLComponents [] = 'course_exam_' . removeSpacesFromString($examComponents[0]) . ":0";
				} else {
					$examcURLComponents [] = 'course_RnR_valid_exams:"' . $this->_escapeSolrQueryString ( $examComponents [0] ) . '"';
				}
			}
		}
		return $examcURLComponents;
	}
    
    private function _getURLComponentsForAppliedFilters($appliedFilters)
    {
        
        $urlComponents = array();
        

        /**
         * Exam
         */
        if(is_array($appliedFilters['exams']) && count($appliedFilters['exams']) > 0) {
        	 
        	$examComponents = array();
        	foreach($appliedFilters['exams'] as $exams) {
                if(!empty($exams)) {
        		    $examComponents[] = 'course_eligibility_required_cp:"'.$this->_escapeSolrQueryString($exams).'"';
                }
        	};
        
        	//$examComponents = $this->_makeURLComponentForExamWithScore($appliedFilters['courseexams']);
            if(!empty($examComponents)) {
        	   $urlComponents[] = 'fq={!tag=fexam}('.implode('%20OR%20',$examComponents).')';
            }
        }
        
        /**
         * Course Exam
         */ 
        if(is_array($appliedFilters['courseexams']) && count($appliedFilters['courseexams']) > 0 && $appliedFilters['courseexams'][0] != -1) {
        	$examComponents = $this->_makeURLComponentForExamWithScore($appliedFilters['courseexams']);
            $urlComponents[] = 'fq={!tag=fcourseexam}('.implode('%20OR%20',$examComponents).')';
        }
        
        /**
         * Degree Preference
         */ 
        if(is_array($appliedFilters['degreePref']) && count($appliedFilters['degreePref']) > 0) {
            
            $degreePrefComponents = array();
            foreach($appliedFilters['degreePref'] as $degreePref) {
                $degreePrefComponents[] = 'course_degree_pref:'.$this->_escapeSolrQueryString($degreePref);
            };
            
            $urlComponents[] = 'fq={!tag=fdpref}('.implode('%20OR%20',$degreePrefComponents).')';
        }

        /**
         * Duration Filter
         */
        $durationComponents = array();
        if(is_array($appliedFilters['duration']) && count($appliedFilters['duration']) > 0) {
            foreach($appliedFilters['duration'] as $duration) {
                $duration = explode(" ",$duration);
                
                $anotherDurationUnit = $duration[1];
                if(substr($anotherDurationUnit,-1) == 's') {
                    $strlen = strlen($anotherDurationUnit);
                    $anotherDurationUnit = substr($duration[1], 0 ,$strlen-1);
                }
                
                $durationComponents[] = '(course_duration_value_int:'.$this->_escapeSolrQueryString($duration[0]).'%20AND%20course_duration_unit:'.$anotherDurationUnit.'*)';
            };
        }
        
        if(is_array($appliedFilters['durationRange']) && count($appliedFilters['durationRange']) > 0) {
           	$anotherDurationUnit = $appliedFilters['durationRange']['type'];
           	if(substr($anotherDurationUnit,-1) == 's') {
        		$strlen = strlen($anotherDurationUnit);
        		$anotherDurationUnit = substr($anotherDurationUnit, 0 ,$strlen-1);
        	}
        	
        	$durationDurationRange = explode('-',$appliedFilters['durationRange']['range']);
        	if(count($durationDurationRange) > 1)
        	{
        		$rangeComponent = '(course_duration_value_int:['.$durationDurationRange[0].'%20TO%20'.$durationDurationRange[count($durationDurationRange)-1].']';
        	} else {
        		$rangeComponent = '(course_duration_value_int:'.$durationDurationRange[0];
           	}
        	
        	$durationComponents[] = $rangeComponent.'%20AND%20course_duration_unit:'.$anotherDurationUnit.'*)';
        }
        
        if(!empty($durationComponents))
            $urlComponents[] = 'fq={!tag=fduration}('.implode('%20OR%20',$durationComponents).')';
        
        
        /**
         * Mode Filter
         */ 
        if(is_array($appliedFilters['mode']) && count($appliedFilters['mode']) > 0) {
            
            $modeComponents = array();
            foreach($appliedFilters['mode'] as $mode) {
                $modeComponents[] = 'course_type:"'.$this->_escapeSolrQueryString($mode).'"';
            };
            
            $urlComponents[] = 'fq={!tag=fmode}('.implode('%20OR%20',$modeComponents).')';
        }
        
        /**
         * Course Level Filter
         */ 
        if(is_array($appliedFilters['courseLevel']) && count($appliedFilters['courseLevel']) > 0) {
            
            $courseLevelComponents = array();
            foreach($appliedFilters['courseLevel'] as $courseLevel) {
                $courseLevelComponents[] = 'course_level_cp:"'.$this->_escapeSolrQueryString($courseLevel).'"';
            };
            
            $urlComponents[] = 'fq={!tag=fcourselevel}('.implode('%20OR%20',$courseLevelComponents).')';
        }
        
        
        // ------------------------  Start : Location Filters -------------------------
        /**
         * City Filter
         */
        $virtualCities      = $this->searchCommon->getVirtualCityMappingForSearch();
        $virtualCitiesIds   = array_keys($virtualCities);
        $locationComponents = array();
        if(is_array($appliedFilters['city']) && count($appliedFilters['city']) > 0) {
            
            foreach($appliedFilters['city'] as $city) {
                // if selected filter is a vitual city then check for corresponding field
                $locationComponents[] = in_array($city,$virtualCitiesIds) ? 'course_virtual_city_id:'.$this->_escapeSolrQueryString($city): 'course_city_id:'.$this->_escapeSolrQueryString($city);
            };
        }

        /**
         * Locality Filter
         */ 
        if(is_array($appliedFilters['locality']) && count($appliedFilters['locality']) > 0) {
            
            $localityArr = array();
            foreach($appliedFilters['locality'] as $locality) {
                $localityArr[] = $this->_escapeSolrQueryString($locality);
            };
            
            if($localityArr)
                $locationComponents[] = "course_locality_id:(".implode("%20",$localityArr).")";

        }
	
	 /**
         * State Filter
         */ 
	if(is_array($appliedFilters['state']) && count($appliedFilters['state']) > 0) {
            
            foreach($appliedFilters['state'] as $stateRowId) {
                $locationComponents[] = 'course_state_id:'.$this->_escapeSolrQueryString($stateRowId);
            };
        }
	
        if(!empty($locationComponents))
            $urlComponents[] = 'fq={!tag=floc}('.implode('%20OR%20',$locationComponents).')';
        
            
        
        // ------------------------  End : Location Filters -------------------------
        
        /**
         * Class Timings Filter
         */ 
        if(is_array($appliedFilters['classTimings']) && count($appliedFilters['classTimings']) > 0) {
            
            $classTimingsComponents = array();
            foreach($appliedFilters['classTimings'] as $classTimings) {
                $classTimingsComponents[] = 'course_class_timings:'.$this->_escapeSolrQueryString($classTimings);
            };
            
            $urlComponents[] = 'fq={!tag=fclasstime}('.implode('%20OR%20',$classTimingsComponents).')';
        }
        
        /**
         * AIMA Rating Filter
         */ 
        if(is_array($appliedFilters['AIMARating']) && count($appliedFilters['AIMARating']) > 0) {
            
            $AIMARatingComponents = array();
            foreach($appliedFilters['AIMARating'] as $AIMARating) {
                $AIMARatingComponents[] = 'institute_aima_rating:'.$this->_escapeSolrQueryString($AIMARating);
            };
            
            $urlComponents[] = 'fq={!tag=faima}('.implode('%20OR%20',$AIMARatingComponents).')';
        }

        /**
         * Fees Filter
         */ 
        if(is_array($appliedFilters['fees']) && count($appliedFilters['fees']) > 0) {
            
            $feesComponents = array();
            $feesVal = $appliedFilters['fees'][0];
            if($feesVal != "No Limit")
            {
                $originalRanges = $GLOBALS['CP_FEES_RANGE']['RS_RANGE_IN_LACS'];
                $keyOfFees = array_search($feesVal, $originalRanges);
                
                if($keyOfFees >= 0 && $keyOfFees)
                {
                    $range = "{0%20TO%20".$this->_escapeSolrQueryString($keyOfFees)."]";
                }
            }
            
            if($range)
                $urlComponents[] = 'fq={!tag=ffee}course_normalised_fees:'.$range;
        }
        
        /**
         * Last modified date Filter
         */
        if(isset($appliedFilters['lastmodifieddate']) && count($appliedFilters['lastmodifieddate']) > 0) {
            
            $lastmodifieddateComponents = array();
            $formattedDate = date("Y-m-d\T00:00:00\Z",strtotime($appliedFilters['lastmodifieddate']));
            $lastmodifieddateComponents[] = 'course_last_modified_date:['.$formattedDate.'%20TO%20*]';
            
            $urlComponents[] = 'fq=('.implode('%20OR%20',$lastmodifieddateComponents).')';
        }
        
        return $urlComponents;
    }
    
    public function _escapeSolrQueryString($inputQueryString)
    {
        $match = array('+', ' ');
        $replace = array('%2B', '%20');
        $outputQueryString = str_replace($match, $replace, $inputQueryString);
        
        return $outputQueryString;
    }
    
    public function generateQueryToGetAllLocations(CategoryPageRequest $request) {
        $solrBaseURL = $this->solrServer->getSolrURL('course','select');
        
        //Get request attributes
        $categoryId = (int) $request->getCategoryId();
        $subCategoryId = (int) $request->getSubCategoryId();
        $LDBCourseId = (int) $request->getLDBCourseId();
        
        $urlComponents = array();
        $urlComponents[] = 'q=*:*';
        $urlComponents[] = 'wt=phps';
        
        //Filters to be applied
        $urlComponents[] = 'fq=facetype:course';
        
        //Category/subcategory/LDB course filters
        if($LDBCourseId > 1) {
            $urlComponents[] = 'fq=course_ldb_id:'.$LDBCourseId;
        }
        else if($subCategoryId > 1) {
            $urlComponents[] = 'fq=course_category_id_list:'.$subCategoryId;
        }
        else {
            $urlComponents[] = 'fq=course_category_id_list:'.$categoryId;
        }
        
        $urlComponents[] = 'hl=off'; //Highlighting is off
        $urlComponents[] = 'rows=0'; //No data needed
        
        //Faceting
        $urlComponents[] = 'facet=true';
        $urlComponents[] = 'facet.mincount=1';
        $urlComponents[] = 'facet.limit=-1';
        $urlComponents[] = 'facet.field=course_location_cluster';
        $urlComponents[] = 'facet.field=course_virtual_city_id';
        
        if($subCategoryId == 23 || $subCategoryId == 56) { //For full time mba and be/btech subcat apply lastmodifed filter, show only lastmodified results after desc 2012
            $lastmodifieddateComponents = array();
            $formattedDate = date("Y-m-d\T00:00:00\Z",strtotime('2012-10-01'));
            $lastmodifieddateComponents[] = 'course_last_modified_date:['.$formattedDate.'%20TO%20*]';
            
            $urlComponents[] = 'fq=('.implode('%20OR%20',$lastmodifieddateComponents).')';
        }
        
        $solrRequest = $solrBaseURL.implode('&',$urlComponents);
        return $solrRequest;
    }
    
    /**
    * Purpose   : Method to generate the solr query for RnR category pages
    * Params    : 1. current category page request object
    * To Do     : none
    * Author    : Romil Goel
    */
    public function generateRnRSolrQuery(CategoryPageRequest $request)
    {
        $this->request      = $request;
    	$solrBaseURL        = $this->solrServer->getSolrURL('course','select');
        $sortingCriteria    = $this->request->getSortingCriteria();
        
        /*
         * Get request attributes
         */ 
        $categoryId     = (int) $request->getCategoryId();
        $subCategoryId  = (int) $request->getSubCategoryId();
        $LDBCourseId    = (int) $request->getLDBCourseId();
        $countryId      = (int) $request->getCountryId();
        
        $urlComponents  = array();
        $urlComponents[]= 'q=*:*';
        $urlComponents[]= 'wt=phps';
        
        //Filters to hide
        global $filtersToHideInSolrRequest;
        $filterToSolrAttribMapping = array(
            'mode'          => "course_type",
            'courseLevel'   => "course_level_cp"	
        );
        
	// get the condition for filterToHide
        foreach ($filtersToHideInSolrRequest as $filterType => $filterValues)
        {
            $urlComponentsForFilterToHide= array();	
            foreach($filterValues as $filterValue)
            {
                $urlComponentsForFilterToHide[] = $filterToSolrAttribMapping[$filterType].":\"".str_replace(" ","%20",$filterValue)."\"";
            }
            $urlComponents[] = 'fq=-('.implode('%20OR%20',$urlComponentsForFilterToHide).')';
        }
        
        $urlComponents[] = 'fq=facetype:course';
        
        //Category/subcategory filters
        if($subCategoryId > 1) {
            $urlComponents[] = 'fq=course_category_id_list:'.$subCategoryId;
        }
        else {
            $urlComponents[] = 'fq=course_category_id_list:'.$categoryId;
        }
        
        //Applied filters
		$appliedFilters = $request->getAppliedFilters();
        $urlComponentsForAppliedFilters = $this->_getURLComponentsForAppliedFiltersForRnR($request, $appliedFilters);
        $urlComponents = array_merge($urlComponents,$urlComponentsForAppliedFilters);
	
        //Fields to be returned in data
	global $SOLR_DATA_FIELDS_ALIAS;
        global $CP_SOLR_FL_LIST;
		$fieldsToFetch = $CP_SOLR_FL_LIST;
        
        if(isset($sortingCriteria['sortBy']) && $sortingCriteria['sortBy'] != "" && $sortingCriteria['sortBy'] != 'none') {
            global $CP_SOLR_FL_LIST_SORT;
            $fieldsToFetchForSorting = $CP_SOLR_FL_LIST_SORT;
            $fieldsToFetch = array_merge($fieldsToFetch, (array)$fieldsToFetchForSorting[$sortingCriteria['sortBy']]);
        }
	else if($request->isMultilocationPage())
	{	
		// performance change : city,state fl would be added to curl FL if required.
		$usrLocPref = $request->getUserPreferredLocationOrder(); 
	    $usrLocPrefTypes = array_map(function($a){return $a['location_type'];},$usrLocPref);
	    $usrLocPrefTypes = array_unique($usrLocPrefTypes);
		
	    global $CP_SOLR_FL_LIST_FOR_MULTILOCATION;
	    foreach($usrLocPrefTypes as $usrLocPrefType)
	    {
		$fieldsToFetch = array_merge($fieldsToFetch,(array)$CP_SOLR_FL_LIST_FOR_MULTILOCATION[$usrLocPrefType]);
	    }
		
	}
        
	foreach($fieldsToFetch as $fields)
	{
	    if($SOLR_DATA_FIELDS_ALIAS[$fields])
		$fieldsToFetchWithAlias[] = $SOLR_DATA_FIELDS_ALIAS[$fields].":".$fields;
	    else
		$fieldsToFetchWithAlias[] = $fields;
	}

        $urlComponents[] 	= 'fl='.implode(',',$fieldsToFetchWithAlias);//.",course_duration_value,course_duration_unit";
        $urlComponents[] 	= 'rows=1000000'; //All the rows
        $urlComponents[] 	= 'hl=off'; //Highlighting is off
        
	//Faceting
	$taggedFilters 		= "{!ex=fcourseexam,floc,ffee,fcourseldb}";
	//$feesPrefixQuery 	= "{!ex=ffee%20key=course_normalised_fees_current}";
	$statePrefixQuery 	= "{!ex=floc%20key=course_state_id_current}";
	$cityPrefixQuery 	= "{!ex=floc%20key=course_city_id_current}";
	$localityPrefixQuery 	= "{!ex=floc%20key=course_locality_id_current}";
	
	$virtualcityPrefixQuery = "{!ex=floc%20key=course_virtual_city_id_current}";
	$courseexamPrefixQuery 	= "{!ex=fcourseexam%20key=course_RnR_valid_exams_current}";
	$speciaPrefixQuery 	= "{!ex=fcourseldb%20key=course_ldb_id_current}";
    
	$urlComponents[] 	= 'facet=true';
	$urlComponents[] 	= 'facet.mincount=1';
	$urlComponents[] 	= 'facet.limit=-1';
	$urlComponents[] 	= 'facet.sort=true';
	
	$urlComponents[] 	= 'facet.field='.$taggedFilters.'course_location_cluster';
	$urlComponents[] 	= 'facet.field='.$statePrefixQuery.'course_state_id';
	$urlComponents[] 	= 'facet.field='.$cityPrefixQuery.'course_city_id';
	$urlComponents[] 	= 'facet.field='.$localityPrefixQuery.'course_locality_id';
	$urlComponents[] 	= 'facet.field='.$virtualcityPrefixQuery.'course_virtual_city_id';
	
	$urlComponents[] 	= 'facet.field='.$taggedFilters.'course_RnR_valid_exams';
	$urlComponents[] 	= 'facet.field='.$courseexamPrefixQuery.'course_RnR_valid_exams';
	//$urlComponents[] 	= 'facet.field='.$taggedFilters.'course_normalised_fees';
	//$urlComponents[] 	= 'facet.field='.$feesPrefixQuery.'course_normalised_fees';
	$urlComponents[] 	= 'facet.field='.$taggedFilters.'course_ldb_id';
	$urlComponents[] 	= 'facet.field='.$speciaPrefixQuery.'course_ldb_id';
	$urlComponents[] 	= 'facet.field='.$taggedFilters.'course_virtual_city_id';
	
	if(!in_array($request->getSubCategoryId(), array(23,56))) {
	    $urlComponents[] = 'facet.pivot='.$taggedFilters.'course_duration_value,course_duration_unit';
	}
        
                    // get fees facet in case of mobile site
                    if($_COOKIE['ci_mobile_js_support'] == 'yes' || $GLOBALS['flag_mobile_js_support_user_agent'] == 'yes'){
                            $urlComponents[] 	= 'facet.field='.$taggedFilters.'course_normalised_fees';
                    }
	
        $urlComponents[] = 'group=true';
        $urlComponents[] = 'group.field=institute_id';
        $urlComponents[] = 'group.ngroups=true';
        $urlComponents[] = 'group.facet=true';
        $urlComponents[] = 'group.limit=100';
        $urlComponents[] = 'group.sort=course_order+asc';
        $urlComponents[] = 'facet.query={!ex=ffee%20key=100000}course_normalised_fees:{0%20TO%20100000]&facet.query={!ex=ffee%20key=200000}course_normalised_fees:{0%20TO%20200000]&facet.query={!ex=ffee%20key=500000}course_normalised_fees:{0%20TO%20500000]&facet.query={!ex=ffee%20key=700000}course_normalised_fees:{0%20TO%20700000]&facet.query={!ex=ffee%20key=1000000}course_normalised_fees:{0%20TO%201000000]&facet.query={!ex=ffee%20key=NoLimit}course_normalised_fees:[*%20TO%20*]';
        $solrRequest = $solrBaseURL.implode('&',$urlComponents);
        
        return $solrRequest;
    }
    
    /**
    * Purpose   : Method to generate the query for different parameters of category page (like exams, city, fees)
    * Params    : 1. current category page request object
    *		  2. User Applied filter
    * Author    : Romil Goel
    */
    private function _getURLComponentsForAppliedFiltersForRnR($request, $appliedFilters)
    {
        $urlComponents = array();
        
        /**
         * Course Exam
         */
        $courseExamComponents   = $this->getFieldQueryForFilter($request, $appliedFilters, 'courseexams');
        $urlComponents          = array_merge($urlComponents, $courseExamComponents);
        

        // ------------------------  Start : Location Filters -------------------------
        $locationComponents = array();
        
        /**
         * Locality Filter
         */
        $localityComponents     = $this->getFieldQueryForFilter($request, $appliedFilters, 'locality');
        $locationComponents     = array_merge($locationComponents, $localityComponents);
        
        
        
        
        /**
         * City Filter
         */
        $cityComponents     = $this->getFieldQueryForFilter($request, $appliedFilters, 'city');
        $locationComponents = array_merge($locationComponents, $cityComponents);
      
         /**
         * State Filter
         */ 
	    $stateComponents        = $this->getFieldQueryForFilter($request, $appliedFilters, 'state');
        $locationComponents     = array_merge($locationComponents, $stateComponents);
	
        // for mobile RNR category pages we need not to get all the locations.
        if($_COOKIE['ci_mobile_js_support'] == 'yes' || $GLOBALS['flag_mobile_js_support_user_agent'] == 'yes'){
            if(!empty($locationComponents)){
                $urlComponents[] = 'fq=('.implode('%20OR%20',$locationComponents).')';
            }
        }
        else if(!empty($locationComponents))
            $urlComponents[] = 'fq={!tag=floc}('.implode('%20OR%20',$locationComponents).')';
            
        // ------------------------  End : Location Filters -------------------------
        
        /**
         * Fees Filter
         */
        $feesComponents   = $this->getFieldQueryForFilter($request, $appliedFilters, 'fees');
        $urlComponents    = array_merge($urlComponents, $feesComponents);

        /**
         * Specialization Filter
         */
        $specializationComponents   = $this->getFieldQueryForFilter($request, $appliedFilters, 'specialization');
        $urlComponents    = array_merge($urlComponents, $specializationComponents);

        /**
         * Last modified Filter
         */
        $lastmodifieddateComponents   = $this->getFieldQueryForFilter($request, $appliedFilters, 'lastmodifieddate');
        $urlComponents    = array_merge($urlComponents, $lastmodifieddateComponents);        
        
        return $urlComponents;
        
    }
    
    function getFieldQueryForFilter($request, $appliedFilters, $filterName)
    {
        $urlComponents      = array();
        $virtualCities      = $this->searchCommon->getVirtualCityMappingForSearch();
        $virtualCitiesIds   = array_keys($virtualCities);

        switch($filterName)
        {
            case 'courseexams' :
                    if(is_array($appliedFilters['courseexams']) && count($appliedFilters['courseexams']) > 0)
                    {
                        $examComponents = $this->_makeURLComponentForExamWithScore($appliedFilters['courseexams']);
                        $urlComponents[] = 'fq={!tag=fcourseexam}('.implode('%20OR%20',$examComponents).')';
                    }
                    else if(!is_array($appliedFilters['courseexams']) && $request->getExamName())
                    {
                        $urlComponents[] = 'fq={!tag=fcourseexam}(course_RnR_valid_exams:"'.$this->_escapeSolrQueryString($request->getExamName()).'")';
                    }
                    break;
            case 'city' :
                    if(is_array($appliedFilters['city']) && count($appliedFilters['city']) > 0) {
                        foreach($appliedFilters['city'] as $city) {
                            // if selected filter is a vitual city then check for corresponding field
                            if(!in_array($city,$this->citiesSetInURL)) {
                           	$urlComponents[] = in_array($city,$virtualCitiesIds) ? 'course_virtual_city_id:'.$this->_escapeSolrQueryString($city): 'course_city_id:'.$this->_escapeSolrQueryString($city);
                            }
                            };
                    }
                    else if( $request->getCityId() && $request->getCityId() > 1)
                    {      
                        if(!is_array($appliedFilters['city'])){
	                    $city = $request->getCityId();
    	                	if(!in_array($city,$this->citiesSetInURL)) {
                                                $urlComponents[] = in_array($request->getCityId(),$virtualCitiesIds) ? 'course_virtual_city_id:'.$this->_escapeSolrQueryString($request->getCityId()): 'course_city_id:'.$this->_escapeSolrQueryString($request->getCityId());
                                        }
                        }
                    }
                    break;
            case 'locality' :
                    if(is_array($appliedFilters['locality']) && count($appliedFilters['locality']) > 0) {
                    	
                    	$this->CI->load->builder('LocationBuilder','location');
                    	$locationBuilder = new LocationBuilder;
                    	$locationRepository = $locationBuilder->getLocationRepository();
                    	$localityObjs = $locationRepository->findMultipleLocalities($appliedFilters['locality']);
                    	
                    	foreach($localityObjs as $localityObj) {
                    		$localityCityMapping[$localityObj->getCityId()][] = $localityObj->getId();
                    	}
                    	$this->citiesSetInURL = array_keys($localityCityMapping);
                    	
                    	$localityArr = array();
                        
                        foreach($localityCityMapping as $cityId => $localities) {
                        	$urlComponents[] = "(course_city_id:".$cityId."%20AND%20course_locality_id:(".implode("%20",$localities)."))";
                        }
                  
                    }
                    else if( $request->getLocalityId())
                    {
                              if( !is_array($appliedFilters['city'])){
    	                	$this->CI->load->builder('LocationBuilder','location');
    	                	$locationBuilder = new LocationBuilder;
    	                	$locationRepository = $locationBuilder->getLocationRepository();
    	                	$localityObj = $locationRepository->findLocality($request->getLocalityId());
	                    	$this->citiesSetInURL [] = $localityObj->getCityId();
                    		$urlComponents[] = "(course_city_id:".$localityObj->getCityId()."%20AND%20course_locality_id:".$request->getLocalityId().")";
                              }
                    }
                    break;
            case 'state' :
                    if(is_array($appliedFilters['state']) && count($appliedFilters['state']) > 0) {
                        foreach($appliedFilters['state'] as $stateRowId) {
                            $urlComponents[] = 'course_state_id:'.$this->_escapeSolrQueryString($stateRowId);
                        };
                    }
                    else if( $request->getStateId() && $request->getStateId() > 1 && $request->isStatePage())
                    {
                        if(  ( !is_array($appliedFilters['state']) && (!is_array($appliedFilters['locality']) && !is_array($appliedFilters['city'])))){
                              $urlComponents[] = "course_state_id:".$request->getStateId();
                        }
		}                        
                    break;
            case 'fees' :
                    if(is_array($appliedFilters['fees']) && count($appliedFilters['fees']) > 0) {
                        $feesComponents = array();
                        $feesVal = $appliedFilters['fees'][0];
                        if($feesVal != "No Limit")
                        {
                            $originalRanges = $GLOBALS['CP_FEES_RANGE']['RS_RANGE_IN_LACS'];
                            $keyOfFees = array_search($feesVal, $originalRanges);
                            
                            if($keyOfFees >= 0)
                                $range = "{0%20TO%20".$this->_escapeSolrQueryString($keyOfFees)."]";
                        }
                        if($range)
                            $urlComponents[] = 'fq={!tag=ffee}(course_normalised_fees:'.$range.")";
                    }
                    else if(!is_array($appliedFilters['fees']) && $request->getFeesValue() && $request->getFeesValue() != -1)
                    {
                        $range              = "{0%20TO%20".$this->_escapeSolrQueryString($request->getFeesValue())."]";
                        $urlComponents[]    = 'fq={!tag=ffee}(course_normalised_fees:'.$range.")";
                    }
                    break;
            case 'specialization' :
		    $specializationComponents = array();
                    if(is_array($appliedFilters['specialization']) && count($appliedFilters['specialization']) > 0) {
                        foreach($appliedFilters['specialization'] as $specialization) {
                            if($specialization > 1 )
                                $specializationComponents[] = 'course_ldb_id:'.$specialization;
                        };
                    }
                    else if(!is_array($appliedFilters['specialization']) && $request->getLDBCourseId())
                    {
                            if($request->getLDBCourseId() > 1 )
                                $specializationComponents[] = 'course_ldb_id:'.$request->getLDBCourseId();
                    }
		    
		    if($specializationComponents)
			$urlComponents[] = 'fq={!tag=fcourseldb}('.implode('%20OR%20',$specializationComponents).')';
		    break;
	    case 'lastmodifieddate' :
		    if(isset($appliedFilters['lastmodifieddate']) && count($appliedFilters['lastmodifieddate']) > 0) {
			$lastmodifieddateComponents = array();
			$formattedDate = date("Y-m-d\T00:00:00\Z",strtotime($appliedFilters['lastmodifieddate']));
			$lastmodifieddateComponents[] = 'course_last_modified_date:['.$formattedDate.'%20TO%20*]';
			$urlComponents[] = 'fq=('.implode('%20OR%20',$lastmodifieddateComponents).')';
		    }
		
        }
        
        return $urlComponents;
    }
	
	function generateQueryForNonRNRCatPageResultCount($categoryPageData) {
		$solrBaseURL = $this->solrServer->getSolrURL('course','select');
		
		$categoryId = $categoryPageData['categoryId'];
		$subCategoryId = $categoryPageData['subCategoryId'];
		$LDBCourseId = $categoryPageData['LDBCourseId'];
		$localityId = $categoryPageData['localityId'];
		$cityId = $categoryPageData['cityId'];
		$stateId = $categoryPageData['stateId'];
		$countryId = $categoryPageData['countryId'];
		$zoneId = $categoryPageData['zoneId'];
		
        $urlComponents = array();
        $urlComponents[] = 'q=*:*';
        $urlComponents[] = 'wt=phps';
		
		//Filters to hide
        global $filtersToHideInSolrRequest;
        global $SOLR_DATA_FIELDS_ALIAS;
        $filterToSolrAttribMapping = array(
            'mode' => "course_type",
            'courseLevel' => "course_level_cp"	
        );
        
        foreach ($filtersToHideInSolrRequest as $filterType => $filterValues)
        {
            $urlComponentsForFilterToHide= array();	
            foreach($filterValues as $filterValue)
            {
                $urlComponentsForFilterToHide[] = $filterToSolrAttribMapping[$filterType].":\"".str_replace(" ","%20",$filterValue)."\"";
            }
            $urlComponents[] = 'fq=-('.implode('%20OR%20',$urlComponentsForFilterToHide).')';
        }
        
        //Filters to be applied
        $urlComponents[] = 'fq=facetype:course';
        
        //Category/subcategory/LDB course filters
        if($LDBCourseId > 1) {
            $urlComponents[] = 'fq=course_ldb_id:'.$LDBCourseId;
        }
        else if($subCategoryId > 1) {
            $urlComponents[] = 'fq=course_category_id_list:'.$subCategoryId;
        }
        else {
            $urlComponents[] = 'fq=course_category_id_list:'.$categoryId;
        }
        
        //Location filters
        $virtualCities      = $this->searchCommon->getVirtualCityMappingForSearch();
        $virtualCitiesIds   = array_keys($virtualCities);
        if($localityId) {
            $urlComponents[] = 'fq=course_locality_id:'.$localityId;
        }
        else if($zoneId) {
            $urlComponents[] = 'fq=course_zone_id:'.$zoneId;
        }
        else if($cityId && $cityId != 1) {
            $urlComponents[] = in_array($cityId,$virtualCitiesIds) ? 'fq=course_virtual_city_id:'.$cityId : 'fq=course_city_id:'.$cityId;
        }
        else if($stateId && $stateId != 1) {
            $urlComponents[] = 'fq=course_state_id:'.$stateId;
        }
        else if($countryId) {
            $urlComponents[] = 'fq=course_country_id:'.$countryId;
        }
         
        $urlComponents[] = 'fq=course_country_id:2';   // fetch document related to india only
		
		$urlComponents[] = 'fl=none';
		$urlComponents[] = 'rows=1000000'; //All the rows
        $urlComponents[] = 'hl=off';
		$urlComponents[] = 'group=true';
        $urlComponents[] = 'group.field=institute_id';
        $urlComponents[] = 'group.ngroups=true';
        $urlComponents[] = 'group.facet=true';
        $urlComponents[] = 'group.limit=100';
        
        $solrRequest = $solrBaseURL.implode('&',$urlComponents);
		
        return $solrRequest;
	}
	
	function generateQueryForRNRCatPageResultCount($categoryPageData, $filtersToHide, $request) {
		$this->request = $request;
		$solrBaseURL = $this->solrServer->getSolrURL('course','select');
		
		$categoryId = $categoryPageData['categoryId'];
		$subCategoryId = $categoryPageData['subCategoryId'];
		
        $urlComponents  = array();
        $urlComponents[]= 'q=*:*';
        $urlComponents[]= 'wt=phps';
        
        //Filters to hide
        $filterToSolrAttribMapping = array(
            'mode'          => "course_type",
            'courseLevel'   => "course_level_cp"	
        );
		
		// get the condition for filterToHide
        foreach ($filtersToHide as $filterType => $filterValues)
        {
            $urlComponentsForFilterToHide= array();	
            foreach($filterValues as $filterValue)
            {
                $urlComponentsForFilterToHide[] = $filterToSolrAttribMapping[$filterType].":\"".str_replace(" ","%20",$filterValue)."\"";
            }
            $urlComponents[] = 'fq=-('.implode('%20OR%20',$urlComponentsForFilterToHide).')';
        }
        
        $urlComponents[] = 'fq=facetype:course';
        
        //Category/subcategory filters
        if($subCategoryId > 1) {
            $urlComponents[] = 'fq=course_category_id_list:'.$subCategoryId;
        }
        else {
            $urlComponents[] = 'fq=course_category_id_list:'.$categoryId;
        }
		
		$urlComponents[] = 'fq=course_country_id:2';   // fetch document related to india only 
		
        $appliedFilters = $this->getAppliedFiltersForCatPageCount($categoryPageData);
        $urlComponentsForAppliedFilters = $this->_getURLComponentsForAppliedFiltersForRnR($request, $appliedFilters);
        $urlComponents = array_merge($urlComponents,$urlComponentsForAppliedFilters);		
		
		$urlComponents[] = 'fl=none';
		$urlComponents[] = 'rows=1000000'; //All the rows
        $urlComponents[] = 'hl=off'; //Highlighting is off
        $urlComponents[] = 'group=true';
        $urlComponents[] = 'group.field=institute_id';
        $urlComponents[] = 'group.ngroups=true';
        $urlComponents[] = 'group.facet=true';
        $urlComponents[] = 'group.limit=100';
        
		$solrRequest = $solrBaseURL.implode('&',$urlComponents);
		
		return $solrRequest;
	}
	
	private function getAppliedFiltersForCatPageCount($categoryPageData) {
		$filters = array();
		if($categoryPageData['zoneId'] != CP_DEFAULT_VAL_ZONE_ID) {
			$filters['zone'][] = $categoryPageData['zoneId'];
		} else {
			if($categoryPageData['localityId'] != CP_DEFAULT_VAL_LOCALITY_ID) {
				$filters['locality'][] = $categoryPageData['localityId'];
			} else {
				if($categoryPageData['cityId'] != CP_DEFAULT_VAL_CITY_ID) {
					$filters['city'][] = $categoryPageData['cityId'];
				} else {
					if($categoryPageData['stateId'] != CP_DEFAULT_VAL_STATE_ID) {
						$filters['state'][] = $categoryPageData['stateId'];
					}
				}
			}
		}
		if($categoryPageData['LDBCourseId']) {
			$filters['specialization'][] = $categoryPageData['LDBCourseId'];
		}
		if($categoryPageData['feesValue'] != 'none') {
			$feesRange = $GLOBALS['CP_FEES_RANGE']['RS_RANGE_IN_LACS'];
			$filters['fees'][] = $feesRange[$categoryPageData['feesValue']];
		}
		if($categoryPageData['examName'] != 'none') {
			$filters['courseexams'][] = $categoryPageData['examName'].'_0';
		}
		$filters['lastmodifieddate'] = '2012-10-01';
		
		return $filters;
	}
}
