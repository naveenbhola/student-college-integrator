<?php

class studyAbroadHomepageLibrary  {
        private $CI;
        private $cacheLib;
        private $studyAbroadHomepageModel;
        private $useCache = true;
        private $cacheTimeLimit = 86400;
        
        function __construct(){
                $this->CI = &get_instance();
                $this->cacheLib = $this->CI->load->library('Common/cacheLib');
                $this->studyAbroadHomepageModel = $this->CI->load->model('studyAbroadHome/studyabroadhomepagemodel');
                $this->CI->load->builder('LocationBuilder','location');
                $locationBuilder = new LocationBuilder;
                $this->locationRepository = $locationBuilder->getLocationRepository();
                $this->CI->load->config('studyAbroadHome/studyAbroadHomePageConfig');
         }

        function getCountStats(){
                $key = "studyAbroadHomepageCoverageStat";
                if(($this->cacheLib->get($key)=='ERROR_READING_CACHE') || ($this->useCache == false) ){
                    $data = $this->_countStatsFromDB($key);
                }else{
                    $data = $this->cacheLib->get($key);
                    if(!isset($data['scholarshipCount']))
                        $data = $this->_countStatsFromDB($key);
                }
                return $data;
        }

        private function _countStatsFromDB($key)
        {
            $data = $this->studyAbroadHomepageModel->getCoverageStats();
            $this->CI->load->config('scholarshipCategoryPage/scholarshipCategoryPageConfig');
            global $scholarshipCategoryPageCountries;
            $scholarshipCategoryPageCountries[] = -1;
            $this->CI->load->model('scholarshipHomepage/scholarshiphomepagemodel');
            $scholarshiphomepagemodel = new scholarshiphomepagemodel();
            $scholarshipsWithCountry = $scholarshiphomepagemodel->getScholarshipsByCountry($scholarshipCategoryPageCountries);
            $scholarshipIds = array_unique(array_map(function($element){return $element['scholarshipId'];}, $scholarshipsWithCountry));
            $data['scholarshipCount'] = count($scholarshipIds);
            $this->cacheLib->store($key,$data,$this->cacheTimeLimit);
            return $data;
        }
        
        function getArticles(){
                $key = "studyAbroadHomepageArticlesData";
                if( ($this->cacheLib->get($key)=='ERROR_READING_CACHE') || ($this->useCache == false) ){
                    $data = $this->studyAbroadHomepageModel->getArticles();
                    $this->cacheLib->store($key,$data,$this->cacheTimeLimit);
                }else{
                    $data = $this->cacheLib->get($key);
                }
                return $data;                
        }
        
        function getMostViewedCoursesData(){
                $key = "studyAbroadHomepageMostViewedCourses";
                if( ($this->cacheLib->get($key)=='ERROR_READING_CACHE') || ($this->useCache == false) ){
                    global $countryArrayForViewed;
                    $data = $this->studyAbroadHomepageModel->getMostViewedCoursesData($countryArrayForViewed);
                    $this->cacheLib->store($key,$data,$this->cacheTimeLimit);
                }else{
                    $data = $this->cacheLib->get($key);
                }
                return $data;                                
        }

        function getCountryMapData($desiredCourses){
                $key = "studyAbroadHomepageMapData";
                if( ($this->cacheLib->get($key)=='ERROR_READING_CACHE') || ($this->useCache == false) ){
                    global $countryArrayForMap;
                    $data = $this->studyAbroadHomepageModel->getCountryMapData($countryArrayForMap,$desiredCourses);
                    $this->cacheLib->store($key,$data,$this->cacheTimeLimit);
                }else{
                    $data = $this->cacheLib->get($key);
                }
                return $data;                                                
        }

        function prepareTrackingData(&$displayData){
            $displayData['beaconTrackData'] = array(
                                                'pageIdentifier' => 'homePage',
                                                'pageEntityId' => '0',
                                                'extraData' => null
                                              );  
            $displayData['googleRemarketingParams'] = array(
                                                    "categoryId" => 0,
                                                    "subcategoryId" => 0,
                                                    "desiredCourseId" => 0,
                                                    "countryId" => 0,
                                                    "cityId" => 0
                                                );    
        }

        function populateAbroadCountries(&$displayData){
            $countries = $this->locationRepository->getAbroadCountries();
            usort($countries,function($c1,$c2){
                return (strcasecmp($c1->getName(),$c2->getName()));
            });
            $displayData['abroadCountries'] = $countries;
        }
}
?>
