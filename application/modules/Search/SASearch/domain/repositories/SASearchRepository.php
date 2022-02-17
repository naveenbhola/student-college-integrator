<?php

class SASearchRepository extends EntityRepository{

	public function __construct($request, $autoSuggestorSolrClient){
		$this->request                 = $request;

		$this->autoSuggestorSolrClient = $autoSuggestorSolrClient;
	}


	public function getRawSearchData(){
		//get search keyword
		$finalData = array();
		$keyword = $this->request->getSearchKeyword();
		if(empty($keyword)) {
			return false;
		}
		$solrRequestData['keyword'] = $keyword;

		//get filters from QER
		$solrRequestData['qerFilters']   = $this->request->getQERFilters();
		$solrRequestData['sortingParam'] = $this->request->getSortingParam();
		//get page details
		$solrRequestData['pageLimit'] = SA_SEARCH_PAGE_LIMIT;
		$solrRequestData['pageNum'] = $this->request->getCurrentPageNum();
		$solrRequestData['requestFrom'] = $this->request->getRequestFrom();
		$solrRequestData['filterUpdateCallFlag'] = $this->request->getFilterUpdateCallFlag();
		$solrRequestData['filterWithDataFlag'] = $this->request->getFilterWithDataFlag();

		if($this->request->getTextSearchFlag()==1)
		{
			$solrRequestData['textSearchFlag'] = $this->request->getTextSearchFlag();
			$solrRequestData['remainingKeyword'] = $this->request->getRemainingKeyword();
		}	
		 //_p($solrRequestData['filterUpdateCallFlag']);
		$pageData = array();
		//get search data from solr
		$searchPageType = $this->_getSearchPageType($solrRequestData['qerFilters']);
        $solrResult = $this->autoSuggestorSolrClient->getFiltersAndInstitutes($solrRequestData,$searchPageType);
		if($solrResult == -1)
		{
			$solrResult = array();
			$pageData['solrOutage'] = 1;
		}
		// _p($solrResult);die;
		foreach($solrResult['grouped']['saUnivId']['groups'] as $data){
			$univId = $data['groupValue'];
			foreach($data['doclist']['docs'] as $cdata){
				$univData[$univId][$cdata['a']] = $cdata;
			}
		}
		$pageData['totalResultCount'] = $solrResult['grouped']['saUnivId']['ngroups'];
		$pageData['currentPageNum']   = $solrRequestData['pageNum'];
		$pageData['searchPageType']		  = $searchPageType;
		$pageData['appliedFilter']	  = $solrRequestData['qerFilters'];
		// check if case of level with university:
		if(isMobileRequest() === false && $pageData['searchPageType'] == "course" && count($solrRequestData['qerFilters']['level'])>0 && count($solrRequestData['qerFilters']['universities'])>0)
		{
			$pageData['appliedFilter']['originalState'] = reset($solrRequestData['qerFilters']['level']);
		}
		$pageData['resultsLimit'] = $solrRequestData['pageLimit'];
		// _p($univData);die;

		$finalData['pageData'] = $pageData;
		$finalData['univData'] = $univData;
		if($solrRequestData['filterUpdateCallFlag']==1){
                $allFilters=$this->_prepareFiltersFromSolrResult($solrResult['facet_counts']['facet_fields'],$searchPageType,$keyword,$solrRequestData);
				$finalData['filters'] =$allFilters[0];
                $finalData['filters_parent']=$allFilters[1];
        }else{
        		$finalData['filters'] = $this->_prepareExamFilterForSorter($solrResult['facet_counts']['facet_fields']);
        }        
                $finalData['sortParam'] = $solrRequestData['sortingParam'];
		return $finalData;


	}

	private function _prepareExamFilterForSorter($facets,$solrRequestData)
	{
		$searchLoggingObject=  StudyAbroadLoggingLib::getLoggerInstance('filterPreparation', $keyword);
		$ci = &get_instance();
		$this->extractionLib = $ci->load->library("SASearch/FilterExtractionLib");
		unset($ci);
		$filters = array();
		$filters['exam'] = $this->extractionLib->extractExamFilterValues($facets,$this->request);
		return $filters;
	}

	private function _prepareFiltersFromSolrResult($facets,$searchPageType,$keyword,$solrRequestData){
		$ci = &get_instance();
		$this->extractionLib = $ci->load->library("SASearch/FilterExtractionLib");
		unset($ci);
		$filters = array();
        $filters_parent=array();
		switch($searchPageType){
			case 'course':
				$filters_parent = array();
				$filters_parent = $this->_prepareCourseFiltersFromSolrResult($facets,$solrRequestData,true);
				$filters = $this->_prepareCourseFiltersFromSolrResult($facets,$solrRequestData);
				// to set parent & current filters diff and enabling / disabling .
				$this->_prepareDynamicFilters($filters, $filters_parent);
				//_p($filters);die;
				break;
			case 'university':
				$filters = $this->_prepareUniversityFiltersFromSolrResult($facets);
				break;
			default:
		}
		return array($filters,$filters_parent);
	}
	/*
	 * to set parent & current filters diff and enabling / disabling .
	 */
	private function _prepareDynamicFilters(&$filters, $filters_parent)
	{
		if(count($filters_parent)==0)
		{
			$filters_parent = $filters;
		}
		// for each parent filter (full set)...
		foreach($filters_parent as $filterKey=>$filterValues)
		{	
			switch($filterKey)
			{
				case "fees": 				$filters[$filterKey."_parent"] = $this->_checkDisabledStatusForFees($filterValues,$filters[$filterKey]);break;
				case "exam": 				$filters[$filterKey."_parent"] = $this->_checkDisabledStatusForExam($filterValues,$filters[$filterKey]);break;
				case "course": 				$filters[$filterKey."_parent"] = $this->_checkDisabledStatusForCourse($filterValues,$filters[$filterKey]);break;
				case "duration": 			$filters[$filterKey."_parent"] = $this->_checkDisabledStatusForDuration($filterValues,$filters[$filterKey]);break;
				case "courseLevel": 		$filters[$filterKey."_parent"] = $this->_checkDisabledStatusForCourseLevel($filterValues,$filters[$filterKey]);break;
				case "deadline": 			$filters[$filterKey."_parent"] = $this->_checkDisabledStatusForDeadline($filterValues,$filters[$filterKey]);break;
				case "scholarship": 		$filters[$filterKey."_parent"] = $this->_checkDisabledStatusForScholarship($filterValues,$filters[$filterKey]);break;
				case "location": 			$filters[$filterKey."_parent"] = $this->_checkDisabledStatusForLocation($filterValues,$filters[$filterKey]);break;
				case "rmc": 				$filters[$filterKey."_parent"] = $this->_checkDisabledStatusForRmc($filterValues,$filters[$filterKey]);break;
				case "sop": 				$filters[$filterKey."_parent"] = $this->_checkDisabledStatusForSop($filterValues,$filters[$filterKey]);break;
				case "lor": 				$filters[$filterKey."_parent"] = $this->_checkDisabledStatusForLor($filterValues,$filters[$filterKey]);break;
				case "12thCutoff": 			$filters[$filterKey."_parent"] = $this->_checkDisabledStatusFor12thCutoff($filterValues,$filters[$filterKey]);break;
				case "UGCutoff": 			$filters[$filterKey."_parent"] = $this->_checkDisabledStatusForUGCutoff($filterValues,$filters[$filterKey]);break;
				case "WorkExperience": 		$filters[$filterKey."_parent"] = $this->_checkDisabledStatusForWorkExperience($filterValues,$filters[$filterKey]);break;
			}            
		}
		//_p($filters);
	}
	private function _checkDisabledStatusForFees($filterParent,$filterCurrent){

		$filterParent = $filterCurrent;
		return $filterParent;
	}
	private function _checkDisabledStatusForExam($filterParent,$filterCurrent){
		$filterCurrentMatchSet = array_map(function($a){return $a['name'];},$filterCurrent);
		foreach($filterParent as $key=>$value)
		{
			$filterParent[$key]['disabled'] = false;
			// if current value doesnt appear in current filter ...
			if(in_array($value['name'],$filterCurrentMatchSet) ===false)
			{
				// ... set disabled
				$filterParent[$key]['disabled'] = true;
				$filterParent[$key]['count'] = 0;
			}else{
				$filterParent[$key]['scores'] = $filterCurrent[$key]['scores'];
				$filterParent[$key]['count'] = $filterCurrent[$key]['count'];
			}
		}
		usort($filterParent,function($a,$b){return ($a['count']<$b['count']);});
		return $filterParent;
	}
	private function _checkDisabledStatusForCourse($filterParent,$filterCurrent){
		$filterParent = $filterCurrent;
		return $filterParent;
	}
	private function _checkDisabledStatusForDuration($filterParent,$filterCurrent){
		
		$filterParent = $filterCurrent;
		return $filterParent;
	}
	private function _checkDisabledStatusForCourseLevel($filterParent,$filterCurrent)
	{
		$filterCurrentMatchSet = array_map(function($a){return $a['level'];},$filterCurrent);
		//_p($filterCurrentMatchSet);
		foreach($filterParent as $key=>$value)
		{
			$filterParent[$key]['disabled'] = false;
			// if current value doesnt appear in current filter ...
			if(in_array($value['level'],$filterCurrentMatchSet) ===false)
			{
				// ... set disabled
				$filterParent[$key]['disabled'] = true;
				$filterParent[$key]['count'] = 0;
			}else{
				$filterParent[$key]['count'] = $filterCurrent[$key]['count'];
			}
		}
		usort($filterParent,function($a,$b){return ($a['count']<$b['count']);});
		return $filterParent;
	}
	private function _checkDisabledStatusForDeadline($filterParent,$filterCurrent){
		$filterParent2 = $filterParent['seasons'];
		$filterCurrent2 = $filterCurrent['seasons'];
                $filterCurrentMatchSet = array_map(function($a){return $a;},$filterCurrent2);
         	foreach($filterParent2 as $key=>$value)
		{
			$filterParent2[$key]['disabled'] = false;
			// if current value doesnt appear in current filter ...
                        if(!isset($filterCurrentMatchSet[$key])){
                                $filterParent2[$key]['disabled'] = true;
				$filterParent2[$key]['count'] = 0;
                        }else{
                            $filterParent2[$key]['count'] = $filterCurrentMatchSet[$key]['count'];
                        }
		}
		usort($filterParent2,function($a,$b){return ($a['count']<$b['count']);});
		$filterParent['seasons'] = $filterParent2;
		$filterParent['dates'] 	 = $filterCurrent['dates'];
		return $filterParent;
	}
	private function _checkDisabledStatusForScholarship($filterParent,$filterCurrent){
		
		if(array_key_exists('Yes',$filterParent)){
			$filterParent['Yes_disabled'] = false;
			if(!array_key_exists('Yes',$filterCurrent))
			{
				$filterParent['Yes_disabled'] = true;
				$filterParent['Yes'] = 0;
			}
			else{
				$filterParent['Yes'] = $filterCurrent['Yes'];
			}
		}
		if(array_key_exists('No',$filterParent)){
			$filterParent['No_disabled'] = false;
			if(!array_key_exists('No',$filterCurrent))
			{
				$filterParent['No_disabled'] = true;
				$filterParent['No'] = 0;
			}
			else{
				$filterParent['No'] = $filterCurrent['No'];
			}
		}
		return $filterParent;
	}
	private function _checkDisabledStatusForLocation($filterParent,$filterCurrent){
		$filterParent = $filterCurrent;
		return $filterParent;
	}
	private function _checkDisabledStatusForRmc($filterParent,$filterCurrent){
		$filterParent = $filterCurrent;
		return $filterParent;
	}
	private function _checkDisabledStatusForSop($filterParent,$filterCurrent){
		if(!is_numeric($filterCurrent)){
			return 0 ;
		}
		$filterParent = $filterCurrent;
		return $filterParent;
	}
	private function _checkDisabledStatusForLor($filterParent,$filterCurrent){
		if(!is_numeric($filterCurrent)){
			return 0 ;
		}
		$filterParent = $filterCurrent;
		return $filterParent;
	}
	private function _checkDisabledStatusFor12thCutoff($filterParent,$filterCurrent){
		
		$filterParent = $filterCurrent;
		return $filterParent;
	}
	private function _checkDisabledStatusForUGCutoff($filterParent,$filterCurrent){
		
		$filterParent = $filterCurrent;
		return $filterParent;
	}
	private function _checkDisabledStatusForWorkExperience($filterParent,$filterCurrent){
		
		$filterParent = $filterCurrent;
		return $filterParent;
	}
	private function _prepareCourseFiltersFromSolrResult($facets,$solrRequestData,$filterUpdateCallFlag = false){
		$filters = array();
		$filters['fees'] = $this->extractionLib->extractFeesFilterValues($facets,$this->request,$filterUpdateCallFlag);
		$filters['exam'] = $this->extractionLib->extractExamFilterValues($facets,$this->request,$filterUpdateCallFlag);
		$filters['course'] = $this->extractionLib->extractCourseFilterValues($facets,$this->request,$solrRequestData,$filterUpdateCallFlag);
		$filters['duration'] = $this->extractionLib->extractDurationFilterValues($facets,$this->request,$filterUpdateCallFlag);
		$filters['courseLevel'] = $this->extractionLib->extractCourseLevelFilterValues($facets,$this->request,$filterUpdateCallFlag);
		$filters['deadline'] = $this->extractionLib->extractDeadlineFilterValues($facets,$this->request,$filterUpdateCallFlag);
		$filters['scholarship'] = $this->extractionLib->extractScholarshipFilterValues($facets,$this->request,$filterUpdateCallFlag);
		$filters['location'] = $this->extractionLib->extractLocationFilterValues($facets,$this->request,$solrRequestData,$filterUpdateCallFlag);
		$filters['rmc'] = $this->extractionLib->extractRMCFilterValues($facets,$this->request,$filterUpdateCallFlag);
		$filters['sop'] = $this->extractionLib->extractSOPFilterValues($facets,$this->request,$filterUpdateCallFlag);
		$filters['lor'] = $this->extractionLib->extractLORFilterValues($facets,$this->request,$filterUpdateCallFlag);
		$filters['12thCutoff'] = $this->extractionLib->extract12thCutoffFilterValues($facets,$this->request,$filterUpdateCallFlag);
		$filters['UGCutoff'] = $this->extractionLib->extractUgCutoffFilterValues($facets,$this->request,$filterUpdateCallFlag);
		$filters['WorkExperience'] = $this->extractionLib->extractWorkExperienceFilterValues($facets,$this->request,$filterUpdateCallFlag);
		$filters['originalState']= $this->extractionLib->computeOriginalFilterState($solrRequestData,  $this->request,$facets);
                return $filters;
	}
 
	private function _prepareUniversityFiltersFromSolrResult($facets){
		$filters = array();
		$filters['type'] = $this->extractionLib->extractTypeFilterValues($facets,$this->request);
		$filters['type2'] = $this->extractionLib->extractType2FilterValues($facets,$this->request);
		$filters['livingexpenses'] = $this->extractionLib->extractLivingExpensesFilterValues($facets,$this->request);
		$filters['location'] = $this->extractionLib->extractLocationFilterValues($facets,$this->request);
		$filters['desiredcourses'] = $this->extractionLib->extractDesiredCoursesFilterValues($facets,$this->request);
		$filters['course'] = $this->extractionLib->extractCourseFilterValues($facets,$this->request);
		$filters['scholarship'] = $this->extractionLib->extractScholarshipFilterValues($facets,$this->request);
		$filters['accommodation'] = $this->extractionLib->extractAccommodationFilterValues($facets,$this->request);
		return $filters;
	}

	private function _getSearchPageType($qerData){
		$searchPageType = 'course';
		if($this->_checkForCourseSearchPageType($qerData)||
			 $this->request->getFilterUpdateCallFlag() || 
			 $this->request->getFilterWithDataFlag())
		{
			$searchPageType = 'course';
		}else if($this->_checkForUniversitySearchPageType($qerData)){
			$searchPageType='university';
		}

		return $searchPageType;
	}

	private function _checkForCourseSearchPageType($qerData){
		$type = false;
		if(!isset($qerData['universities']) && 
		   	(isset($qerData['level']) || 
			isset($qerData['specialization']) || 
			isset($qerData['specializationIds']) ||
			isset($qerData['categoryIds']) ||
			isset($qerData['subCategoryIds']) ||
			isset($qerData['desiredCourse']) 
			)
		   )
			{
				
				$type  = true;

			}
			return $type;
	}

	private function _checkForUniversitySearchPageType($qerData){
		$type = false;
		if(isset($qerData['universities']) || 
		   isset($qerData['institute']) || 
		   isset($qerData['city']) ||
		   isset($qerData['state']) ||
		   isset($qerData['country']) ||
		   isset($qerData['continent']) ||
		   isset($qerData['textSearchFlag'])
		   )
			{
				
				$type  = true;

			}
			return $type;
	}	
}
