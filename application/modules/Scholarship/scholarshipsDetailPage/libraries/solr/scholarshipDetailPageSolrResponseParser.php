<?php

class scholarshipDetailPageSolrResponseParser
{
    //Priority order mentioned in SA-3385
    private $countryPriorityList;
    private $categoryPriorityList;

    function __construct(){
        $this->CI = &get_instance();
        $this->CI->load->builder('location/LocationBuilder');
        $locationBuilder = new LocationBuilder();
        $this->locationRepo = $locationBuilder->getLocationRepository();

        $this->CI->load->builder('CategoryBuilder','categoryList');
        $categoryBuilder = new CategoryBuilder;
        $this->categoryRepository = $categoryBuilder->getCategoryRepository();

        $this->CI->load->config('scholarshipsDetailPage/scholarshipConfig');
        $this->countryPriorityList  = $this->CI->config->item('countryPriorityOrder');
        $this->categoryPriorityList = $this->CI->config->item('categoryPriorityOrder');
    }

    public function formatSimilarScholarshipsSolrResponse($solrResponse, $widgetFilterData){
        $returnArray = array();
        if($solrResponse['responseHeader']['status']!=0){
            $returnArray['status'] = 'failed';
            return $returnArray;
        }
        $returnArray['scholarships'] = array();
        foreach($solrResponse['response']['docs'] as &$scholarshipData){
            $this->_formatCountryData($scholarshipData, $widgetFilterData);

            if($scholarshipData['saScholarshipCategoryId'][0] == 'all'){
                $scholarshipData['saScholarshipCategoryId'] = array_keys($this->categoryPriorityList);
            }
            $this->_formatCategoryData($scholarshipData);

            $returnArray['scholarships'][] = $scholarshipData;
        }
        return $returnArray;
    }

    private function _formatCountryData(&$scholarshipData, $widgetFilterData){
        $countryFoundFlag = false;
        // Removed for SA-3434
        // foreach ($this->countryPriorityList as $cntryId => $cntryName) {
        //     if(in_array($cntryId, $scholarshipData['saScholarshipCountryId'])){
        //         $scholarshipData['countryStr'] = $cntryName;
        //         $countryFoundFlag = true;
        //         break;
        //     }
        // }
        if(!$countryFoundFlag){
            if($scholarshipData['saScholarshipCountryId'][0] == 1){
                $countries = $this->locationRepo->getAllCountries();
                unset($countries['All']);
                $scholarshipData['countryStr'] = 'USA +'.(count($countries)-1).' more';
            }else{
                $countryObj = $this->locationRepo->findCountry($scholarshipData['saScholarshipCountryId'][0]);
                $scholarshipData['countryStr'] = $countryObj->getName();
            }
        }
        if(count($scholarshipData['saScholarshipCountryId']) > 1){
            $scholarshipData['countryStr'] .= ' +'.(count($scholarshipData['saScholarshipCountryId'])-1).' more';
        }
    }

    private function _formatCategoryData(&$scholarshipData){
        $categoryCount = 0;
        foreach ($this->categoryPriorityList as $catId => $catName) {
            if(in_array($catId, $scholarshipData['saScholarshipCategoryId'])){
                if($categoryCount <= 3){
                    $categoryCount++;
                    $scholarshipData['categoryStr'][$catId] = $catName;
                }
                if($categoryCount == 3){
                    break;
                }
            }
        }
        if($categoryCount < 3 && count($scholarshipData['saScholarshipCategoryId']) > $categoryCount){
            $extraCategories  = 3 - $categoryCount;
            $extraCategoryIds = array(); $i=0;
            while(count($extraCategoryIds)!=$extraCategories){
                if(!in_array($scholarshipData['saScholarshipCategoryId'][$i], array_keys($scholarshipData['categoryStr']))){
                    $extraCategoryIds[] = $scholarshipData['saScholarshipCategoryId'][$i];
                }
                $i++;
            }
            if(count($extraCategoryIds)>0){
                $catObjs = $this->categoryRepository->findMultiple($extraCategoryIds);
                foreach ($catObjs as $catObj) {
                   $scholarshipData['categoryStr'][$catObj->getId()] = $catObj->getName();
                }
            }
        }
        if(count($scholarshipData['saScholarshipCategoryId']) > 3){
            $scholarshipData['categoryStr'] = (implode(', ', $scholarshipData['categoryStr'])).' +'.(count($scholarshipData['saScholarshipCategoryId'])-3).' more';
        }else{
            $scholarshipData['categoryStr'] = implode(', ', $scholarshipData['categoryStr']);
        }
    }
}