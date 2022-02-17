<?php

class scholarshipCategoryPageURLParserLib{
    private $CI;
    function __construct(){
        $this->CI = &get_instance();
        $this->CI->load->config('scholarshipCategoryPage/scholarshipCategoryPageConfig');
    }
    public function parseAndValidateUrl($pageName,$pageIdentifier){
        $pageName = strtolower($pageName);
        $pageIdentifierArray    = explode('-',$pageIdentifier);
        $pageIdentifier         = $pageIdentifierArray[0];
        $pageNumber             = $pageIdentifierArray[1]>1?$pageIdentifierArray[1]:1;
        $requestData = array();
        $requestData['pageNumber'] = $pageNumber;
        if($this->CI->input->post('isAjaxCall',true)==1){ // case of filter application 
            $requestData['isAjaxCall'] = true;
        }
		if($this->CI->input->post('filterAJAX',true)==1){ // case of filter generation on page load
            $requestData['isFilterAjaxCall'] = true;
        }
        $userEnteredUrl = str_replace(SHIKSHA_STUDYABROAD_HOME,'',getCurrentPageURLWithoutQueryParams());
        switch ($pageIdentifier) {
            case 'cp':
                $this->_parseUrlForCountryCategoryPage($pageName,$requestData,$userEnteredUrl);
                break;
            case 'courses':
                $this->_parseUrlForCourseCategoryPage($pageName,$requestData,$userEnteredUrl);
                break;
        }
        $requestData['requestCounter'] = $this->CI->input->post('requestCounter',true);
        if($this->CI->input->post('countFlag',true)==1){
            $requestData['countFlag'] = true;
        }
        return $requestData;
    }
    
    private function _parseUrlForCountryCategoryPage($pageName,&$requestData,$userEnteredUrl){
        $countryHomeLib = $this->CI->load->library('countryHome/CountryHomeLib');
		$country        = $countryHomeLib->getCountry($pageName);
        if($country==false || $country->getName()=='All'){
            show_404_abroad();
        }
        
        global $scholarshipCategoryPageCountries;
        if(!in_array($country->getId(),$scholarshipCategoryPageCountries)){
            show_404_abroad();
        }
        $requestData['countryId'] = $country->getId();
        $requestData['type'] = 'country';
        $requestData['countryName'] = $country->getName();
        $cleanQueryStr = $this->_getFiltersFromQueryString($requestData);
        $correctBaseUrl     = '/scholarships/'.seo_url_lowercase($country->getName()).'-cp';
        if($requestData['pageNumber']>1){
            $correctBaseUrl = $correctBaseUrl.'-'.$requestData['pageNumber'];
        }
        if($_SERVER['QUERY_STRING']!=''){
            $correctUrl = $correctBaseUrl.($cleanQueryStr ==""?"":"?".$cleanQueryStr);
            $userEnteredUrl = $userEnteredUrl."?".$_SERVER['QUERY_STRING'];
        }else{
            $correctUrl = $correctBaseUrl;
        }
        
        if($correctUrl!=$userEnteredUrl){
            redirect(SHIKSHA_STUDYABROAD_HOME.$correctUrl,301);
        }
        $requestData['url'] = $correctBaseUrl;
        $requestData['queryString'] = $cleanQueryStr;
    }
    private function _parseUrlForCourseCategoryPage($pageName,&$requestData,$userEnteredUrl){
        switch ($pageName) {
            case 'masters':
            case 'bachelors':
                $requestData['level'] = $pageName;
                break;
            default:
                show_404_abroad();
                break;
        }
        $requestData['type'] = 'courseLevel';
		$cleanQueryStr = $this->_getFiltersFromQueryString($requestData);
        $correctBaseUrl = '/scholarships/'.seo_url_lowercase($pageName).'-courses';
        if($requestData['pageNumber']>1){
            $correctBaseUrl = $correctBaseUrl.'-'.$requestData['pageNumber'];
        }
        if($_SERVER['QUERY_STRING']!=''){
            $correctUrl = $correctBaseUrl.($cleanQueryStr ==""?"":"?".$cleanQueryStr);
            $userEnteredUrl = $userEnteredUrl."?".$_SERVER['QUERY_STRING'];
        }else{
            $correctUrl = $correctBaseUrl;
        }
        if($correctUrl!=$userEnteredUrl){
            redirect(SHIKSHA_STUDYABROAD_HOME.$correctUrl,301);
        }
        $requestData['url'] = $correctBaseUrl;
        $requestData['queryString'] = $cleanQueryStr;
    }
	/*
	 * translates field alias from url query string to Solr field names
	 */
    private function _getFiltersFromQueryString(& $requestData)
	{
		// grab the query string components
		$queryStrComponents = array();
		parse_str($_SERVER['QUERY_STRING'], $queryStrComponents);
		$cleanURLComponents = array();
		// list of all alias to solr field name mapping
		global $queryStringAliasToSolrFieldMapping;
		$appliedFilterURLComponents = array();
		foreach($queryStrComponents as $alias=>$fieldValue)
		{
			if($alias == 'so'){
				global $scholarshipSortingCriteriaToSolrFieldMapping;
				if(isset($scholarshipSortingCriteriaToSolrFieldMapping[$fieldValue])){
					$cleanURLComponents[$alias] = $fieldValue;
					$requestData['sortCriteria'] = $fieldValue;
				}
				continue;
			}else{
				$fieldName = $queryStringAliasToSolrFieldMapping[$alias];
			}
			//echo "<br>".$alias.":".$fieldName;
			// reject country filter on country X all levels page & level on all countries X level page
			if(($fieldName == "saScholarshipCountryId" && $requestData['type'] == "country")||
			   ($fieldName == "saScholarshipCourseLevel" && $requestData['type'] == "courseLevel"))
			{
				continue;
			}
			if($fieldValue != '' && !is_null($fieldValue))
			{
				$processedFieldValue = $this->_processFilterFromQueryString($fieldName, $fieldValue);
				$appliedFilterURLComponents[$fieldName] = $processedFieldValue['processedFilter'];
				$cleanURLComponents[$alias] = $processedFieldValue['cleanFieldValueStr'];
			}
		}
                $this->_validateApplicabilityFilter($cleanURLComponents);
		$requestData['appliedFilters'] = $appliedFilterURLComponents;
		// re-add profiling	if available
		if(!is_null($queryStrComponents['profiling']) && $queryStrComponents['profiling'] == 1){
			$cleanURLComponents['profiling'] = $queryStrComponents['profiling'];
		}
		return urldecode(http_build_query($cleanURLComponents));
	}
        private function _validateApplicabilityFilter(&$cleanURLComponents){
            if(!empty($cleanURLComponents['su'])){
                $scholarshipCategoryFilter = array_filter(explode(',',$cleanURLComponents['sc']));
                
                $key = array_search('internal',$scholarshipCategoryFilter);
                if($key!=false){
                    unset($scholarshipCategoryFilter[$key]);
                }
                
                if(!empty($scholarshipCategoryFilter)){
                    $cleanURLComponents['sc'] = implode(',', $scholarshipCategoryFilter);
                }else{
                    unset($cleanURLComponents['sc']);
                }
            }
        }
	/*
	 * 
	 */
	private function _processFilterFromQueryString($fieldName, $fieldValue)
	{
		$cleanFieldValueStr = $this->CI->security->xss_clean($fieldValue);
		switch($fieldName)
		{
			// numeric ids
			case 'saScholarshipCountryId':
			case 'saScholarshipStudentNationality':
			case 'saScholarshipCategoryId':
			case 'saScholarshipSpecialRestriction':
			case 'saScholarshipUnivId':
			case 'saScholarshipIntakeYear':
				$processedFilter = explode(',',$cleanFieldValueStr);
				$processedFilter = array_values(array_unique(array_filter($processedFilter,
                                                                                        function($a){
                                                                                               if(is_numeric($a)){
                                                                                                       return $a;
                                                                                               }
                                                                                       })));
				$cleanFieldValueStr = implode(',',$processedFilter);				
					break;
			// text
			case 'saScholarshipCourseLevel':
			case 'saScholarshipCategory':
			case 'saScholarshipType':
				$processedFilter = explode(',',$cleanFieldValueStr);
				$processedFilter = array_values(array_unique(array_filter($processedFilter)));
				$cleanFieldValueStr = implode(',',$processedFilter);
					break;
			// sliders
			case 'saScholarshipApplicationEndDate':
				$this->_processQueryStringForDeadline($cleanFieldValueStr, $processedFilter);
					break;
			case 'saScholarshipAmount':
				$this->_processQueryStringForAmount($cleanFieldValueStr, $processedFilter);
					break;
			case 'saScholarshipAwardsCount': 
				$this->_processQueryStringForAwards($cleanFieldValueStr, $processedFilter);
					break;
			default:
				$processedFilter = array();
				$cleanFieldValueStr = '';
				break;
		}
		return array('processedFilter'=>$processedFilter,
					 'cleanFieldValueStr'=>$cleanFieldValueStr);
	}
	private function _processQueryStringForDeadline(&$cleanFieldValueStr, &$processedFilter)
	{
		$processedFilter = explode('_',$cleanFieldValueStr);
		$processedFilter = array_values((array_filter($processedFilter)));
		$cleanFieldValueStr = implode('_',$processedFilter);
		$startDate = date_create_from_format("m-Y",$processedFilter[0]);
		$endDate = date_create_from_format("m-Y",$processedFilter[1]);
		if(($startDate === false)||($endDate === false))
		{
			$processedFilter = array();
			$cleanFieldValueStr = '';
		}else{
			$diffObj = date_diff($startDate,$endDate);
			if($diffObj->invert == 1)
			{
				$processedFilter = array();
				$cleanFieldValueStr = '';
			}else{
				$processedFilter[0] = date_format($startDate,"Y-m")."-01T00:00:00Z";
				if(!function_exists(cal_days_in_month)){
					$endDay = date('t', mktime(0, 0, 0, $endDate->format("m"), 1, $endDate->format("Y")));
				}
				else{
					$endDay = cal_days_in_month(CAL_GREGORIAN, $endDate->format("m"),$endDate->format("Y"));
				}

				$processedFilter[1] = $endDate->format("Y")."-".$endDate->format("m")."-".$endDay."T23:59:59Z";
			}
		}
	}
	
	private function _processQueryStringForAmount(&$cleanFieldValueStr, &$processedFilter)
	{
		$processedFilter = explode('-',$cleanFieldValueStr);
		$processedFilter = array_values((array_map(function($a){
														if(is_numeric($a)){
															return $a;
														}},$processedFilter)));
		if(is_null($processedFilter[0])||is_null($processedFilter[1])||
		   ($processedFilter[0] > $processedFilter[1]) ||
		   abs($processedFilter[0]-$processedFilter[1])< 50000)
		{
			$processedFilter = array();
			$cleanFieldValueStr = '';
		}else{
			$cleanFieldValueStr = implode('-',$processedFilter);
		}
	}
	
	private function _processQueryStringForAwards(&$cleanFieldValueStr, &$processedFilter)
	{
		$processedFilter = explode('-',$cleanFieldValueStr);
		$processedFilter = array_values((array_map(function($a){
														if(is_numeric($a)){
															return $a;
														}},$processedFilter)));
		if(is_null($processedFilter[0])||is_null($processedFilter[1])||
		   ($processedFilter[0] > $processedFilter[1])||
		   ($processedFilter[0] == 0 && $processedFilter[1] == 0)
		   )
		{
			$processedFilter = array();
			$cleanFieldValueStr = '';
		}else{
			$cleanFieldValueStr = implode('-',$processedFilter);
		}
	}
}
