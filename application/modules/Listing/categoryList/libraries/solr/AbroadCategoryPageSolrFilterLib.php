<?php

class AbroadCategoryPageSolrFilterLib
{

    public function formatFilterResults($facets,$locationRepository,$categoryRepository)
    {
        //_p($facets);
        $facetFields = $facets['facet_fields'];
        //_p($facets['facet_queries']);
        //_p($facetFields);//die;
        $filterNames = array('fees','location','exams','subcategory','more option');
        $filters = array();
        $userAppliedfilters = array();
        foreach ($filterNames as $facetName) {
                switch ($facetName) {
                    case 'exams':
                        $this->_formatExamFilter($facetFields,$filters,$userAppliedfilters);
                        break;
                    
                    case 'fees':
                    	$this->_formatFeeFilter($facets,$filters,$userAppliedfilters);
                        break;
                    
                    case 'location':
                        $this->_formatLocationFilter($facetFields,$locationRepository,$filters,$userAppliedfilters);    
                        break;

                    case 'subcategory':
                            $this->_formatCategoryFilter($facetFields,$categoryRepository,$filters,$userAppliedfilters);
                        break;

                    case 'more option':
		                   $this->_formatMoreOption($facets,$facetFields,$filters,$userAppliedfilters); 
                        break;    
            }
        }
        $final = array('filters'=>$filters,'userAppliedfilters'=>$userAppliedfilters);
        return $final;
    }

    private function _formatExamFilter($facetFields,&$filters,&$userAppliedfilters){
        $exams = array_keys($facetFields['saCourseEligibilityExamsIdMap_parent']);
        foreach ($exams as $key => $value) {
            $valueArr = explode(':', $value);
            $filters['exams'][$valueArr[1]] = $valueArr[0];
            $scores = array_keys($facetFields['sa'.$valueArr[0].'ExamScore']);
            //ignore in case of IELTS else get integer value only
            if($valueArr[1]!=2){
                $scores = array_map('intval', $scores);
            }
            arsort($scores);
            $filters['examsScore'][$valueArr[1]] = $scores;
        }

        //user apllied examscore filter
        $exams = array_keys($facetFields['saCourseEligibilityExamsIdMap']);
        foreach ($exams as $key => $value) {
            $valueArr = explode(':', $value);
            $userAppliedfilters['exams'][$valueArr[1]] = $valueArr[0];
            $scores = array_keys($facetFields['sa'.$valueArr[0].'ExamScore']);
            //ignore in case of IELTS else get integer value only
            if($valueArr[1]!=2){
                $scores = array_map('intval', $scores);
            }
            arsort($scores);
            $userAppliedfilters['examsScore'][$valueArr[1]] = $scores;
        }
        //_p($filters['exams']);
        //_p($userAppliedfilters['exams']);
    }

    private function _formatFeeFilter($facets,&$filters,&$userAppliedfilters){
        //_p($GLOBALS['CP_ABROAD_FEES_RANGE']['ABROAD_RS_RANGE_IN_LACS']);die;
        $feeFilters = $feeParentFilters=array();
        foreach ($GLOBALS['CP_ABROAD_FEES_RANGE']['ABROAD_RS_RANGE_IN_LACS'] as $key => $value) 
        {
           if($facets['facet_queries']['parentfee'.$key]>0){
                $feeParentFilters[$key] = $value;   
           } 
        
            if($facets['facet_queries']['fee'.$key]>0){
                $feeFilters[$key]       = $value;
            }
        }
        $filters['fee'] = $feeParentFilters;
        $userAppliedfilters['fee'] = $feeFilters;
    }

    private function _formatLocationFilter($facetFields,$locationRepository,&$filters,&$userAppliedfilters){
        //Parent city filter
        $cities = array_keys($facetFields['saUnivCityId_parent']);
        if(count($cities)>0){
            $cityObj = $locationRepository->findMultipleCities($cities);
            $cityFilter = array();
            foreach ($cityObj as $key => $obj) {
               // get cities for filter which dont have state id 
               if($obj->getStateId()== 1){
                $cityFilter[$obj->getId()] = $obj->getName();
                }
            }
            $filters['city'] = $cityFilter;
        }

        //user apllied city filter
        $cities = array_keys($facetFields['saUnivCityId']);
        if(count($cities)>0){
            $cityObj = $locationRepository->findMultipleCities($cities);
            $cityFilter = array();
            foreach ($cityObj as $key => $obj) {
               // get cities for filter which dont have state 
               if($obj->getStateId()== 1){  
                $cityFilter[$obj->getId()] = $obj->getName();
                }
            }
            $userAppliedfilters['city'] = $cityFilter;
        }
        
        //Parent state filter
        $states = array_keys($facetFields['saUnivStateId_parent']);
        if(count($states)>0){
            $statesObj = $locationRepository->findMultipleStates($states);
            $stateFilter = array();
            foreach ($statesObj as $key => $obj) {
               $stateFilter[$obj->getId()] = $obj->getName();
            }
            $filters['state'] = $stateFilter;
        }

        $states = array_keys($facetFields['saUnivStateId']);
        if(count($states)>0){
            $statesObj = $locationRepository->findMultipleStates($states);
            $stateFilter = array();
            foreach ($statesObj as $key => $obj) {
               $stateFilter[$obj->getId()] = $obj->getName();
            }
            $userAppliedfilters['state'] = $stateFilter;
        }

        //Parent country filter
        $countries = array_keys($facetFields['saUnivCountryId_parent']);
        if(count($countries)>0){
            $countriesObj = $locationRepository->getAbroadCountryByIds($countries);
            $countryFilter = array();
            foreach ($countriesObj as $key => $obj) {
               $countryFilter[$obj->getId()] = $obj->getName();
            }
            $filters['country'] = $countryFilter;
        }

        $countries = array_keys($facetFields['saUnivCountryId']);
        if(count($countries)>0){
            $countriesObj = $locationRepository->getAbroadCountryByIds($countries);
            $countryFilter = array();
            foreach ($countriesObj as $key => $obj) {
               $countryFilter[$obj->getId()] = $obj->getName();
            }
            $userAppliedfilters['country'] = $countryFilter;
        }
    }

    private function _formatCategoryFilter($facetFields,$categoryRepository,&$filters,&$userAppliedfilters){
        $categories = array_keys($facetFields['saCourseSubcategoryId_parent']);
        if(count($categories)>0){
            $categoriesObj = $categoryRepository->findMultiple($categories);
            $categoriesFilter = array();
            foreach ($categoriesObj as $key => $obj) {
               $categoriesFilter[$obj->getId()] = $obj->getName();
            }
            $filters['coursecategory'] = $categoriesFilter;
        }

        $categories = array_keys($facetFields['saCourseSubcategoryId']);
        if(count($categories)>0){
            $categoriesObj = $categoryRepository->findMultiple($categories);
            $categoriesFilter = array();
            foreach ($categoriesObj as $key => $obj) {
               $categoriesFilter[$obj->getId()] = $obj->getName();
            }
            $userAppliedfilters['coursecategory'] = $categoriesFilter;
        }
    }

    private function _formatMoreOption($facets,$facetFields,&$filters,&$userAppliedfilters){
        if($facetFields['saUnivAccommodationAvailable_parent']['Yes']>0)
        {
            $moreOptionParent['WTH_ACMDTN'] = $GLOBALS['MORE_OPTIONS']['WTH_ACMDTN'];
        }
        if($facetFields['saUnivAccommodationAvailable']['Yes']>0)
        {
            $moreOption['WTH_ACMDTN'] = $GLOBALS['MORE_OPTIONS']['WTH_ACMDTN'];
        }
        if($facetFields['saCourseScholarship_parent']['Yes']>0)
        {
            $moreOptionParent['OFR_SCHLSHP'] = $GLOBALS['MORE_OPTIONS']['OFR_SCHLSHP'];
        }
        if($facetFields['saCourseScholarship']['Yes']>0)
        {
            $moreOption['OFR_SCHLSHP'] = $GLOBALS['MORE_OPTIONS']['OFR_SCHLSHP'];
        }
        if($facetFields['saUnivType_parent']['public']>0)
        {
            $moreOptionParent['PUB_FUND'] = $GLOBALS['MORE_OPTIONS']['PUB_FUND'];
        }
        if($facetFields['saUnivType']['public']>0)
        {
            $moreOption['PUB_FUND'] = $GLOBALS['MORE_OPTIONS']['PUB_FUND'];
        }

        if($facets['facet_queries']['diplomaFlag']>0){
            $moreOption['DEGREE_COURSE'] = $GLOBALS['MORE_OPTIONS']['DEGREE_COURSE'];   
        }
        if($facets['facet_queries']['diplomaFlagParent']>0){
            global $levelFilter;
            $levelFilter = true;
            $moreOptionParent['DEGREE_COURSE'] = $GLOBALS['MORE_OPTIONS']['DEGREE_COURSE'];   
        }

        $filters['moreoptions'] = $moreOptionParent;
        $userAppliedfilters['moreoptions'] =  $moreOption;
    }

}
?>    