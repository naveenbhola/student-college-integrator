<?php

class AbroadSearchCrons extends MX_Controller {
    
    public function buildStaticSearchUrl(){ 
         $locationModel = $this->load->model('location/locationmodel');  
        
         $stateData     = $locationModel->getAllAbroadState();
         $formatStateData = array();
         foreach ($stateData as $key => $value) {
             $formatStateData[$value['state_id']]  = $value;
         }
         unset($stateData);

         $countryData   = $locationModel->getAbroadCountries();
         $formatCountryData = array();
         foreach ($countryData as $key => $value) {
             $formatCountryData[$value['countryId']]  = $value;
         }
         unset($countryData);

        $citiesData  = $locationModel->getCities();
        $formatCitiesData = array();
        foreach ($citiesData as $key => $locObj) {
            if($locObj['countryId'] !== "-1" && $locObj['countryId'] !== "2"){
                $formatCitiesData[$locObj['city_id']] = $locObj;
            } 
        }
        unset($citiesData);
              
        
        $this->load->library('SASearch/AutoSuggestorSolrRequestGenerator');
        $autoSuggestorSolrRequestGenerator = new AutoSuggestorSolrRequestGenerator;
        $curlLib                           = $this->load->library('common/CustomCurl');
        
        $solrUrl                           = $autoSuggestorSolrRequestGenerator->getUniversityCountBasedOnLocation();
        $urlComp                           = explode('?', $solrUrl);
        $curlLib->setIsRequestToSolr(1);

        $customCurlObject                  = $curlLib->curl($urlComp[0], $urlComp[1]);
        $solrContent                       = unserialize($customCurlObject->getResult());
        $facetData                         = $solrContent['facet_counts']['facet_fields'];
        $citiesData                        = $facetData['saUnivCityId'];
        $statesData                        = $facetData['saUnivStateId'];

        $cityTableData  = array();
        foreach ($citiesData as $cityId => $count) {
            //excluding those cities which have same name as state name in a country
            //Bug id https://infoedge.atlassian.net/browse/SA-4162
            if($count > 0 && !in_array($cityId, array(11269,11189,11187,12187,11186,11399,11423,11202,11364,11431,11432,11902,11921,11935,12005,12186))){
                $prepareData               = array();
                $prepareData['city_id']    = $cityId;
                $prepareData['count']      = $count;
                $prepareData['url']        = $this->prepareStaticSearchUrl($cityId,$formatCountryData,$formatCitiesData,$formatStateData,'city');
                $prepareData['status']     = 'live';
                $prepareData['created_on'] =  date('Y-m-d H:i:s');
                $cityTableData[]           = $prepareData;                
            }
        }

        $searchModel = $this->load->model('SASearch/sasearchmodel'); 


        $stateTableData = array();
        foreach ($statesData as $stateId => $count) {
            if($count > 0){
                $prepareData               = array();
                $prepareData['state_id']   = $stateId;
                $prepareData['count']      = $count;
                $prepareData['url']        = $this->prepareStaticSearchUrl($stateId,$formatCountryData,$formatCitiesData,$formatStateData,'state');
                $prepareData['status']     = 'live';
                $prepareData['created_on'] =  date('Y-m-d H:i:s');
                $stateTableData[]          = $prepareData;    
            }            
        }
        if(!empty($cityTableData) && !empty($stateTableData)){
            $searchModel->saveSearchStaticUrls($cityTableData,$stateTableData);            
        }        
    }

    function prepareStaticSearchUrl($locId,$allCountryData,$allCitiesData,$allStateData,$type){
        if($type == 'city'){
            $cityObj     = $allCitiesData[$locId];            
            $cityName    = $cityObj['city_name'];
            $countryName = $allCountryData[$cityObj['countryId']]['name'];            
            $uri         = '/'.seo_url($countryName,'-',100,true)."/"."universities-in-".seo_url($cityName,'-',100,true);
            return $uri;
        }else if($type == 'state'){
            $stateObj = $allStateData[$locId];            
            $stateName = $stateObj['state_name'];
            $countryName = $allCountryData[$stateObj['countryId']]['name'];            
            $uri         = '/'.seo_url($countryName,'-',100,true)."/"."universities-in-".seo_url($stateName,'-',100,true);
            return $uri;
        }
    }
}