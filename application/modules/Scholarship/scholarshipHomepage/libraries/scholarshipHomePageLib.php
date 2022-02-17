<?php

class scholarshipHomePageLib{

    private $CI;
    private $pageUrl;
    private $scholarshipStatisticsKey = 'shpScholarshipStatisticsKey';
    function __construct(){
        $this->CI = &get_instance();
        $this->pageUrl = 'scholarships';
        $this->CI->load->model('scholarshipHomepage/scholarshiphomepagemodel');
        $this->scholarshiphomepagemodel = new scholarshiphomepagemodel();
        $this->CI->load->config('scholarshipCategoryPage/scholarshipCategoryPageConfig');
        $this->CI->load->builder('LocationBuilder','location');
        $this->locationBuilder = new LocationBuilder;
        $this->locationRepository = $this->locationBuilder->getLocationRepository();  
        $this->CI->load->builder('scholarshipsDetailPage/scholarshipBuilder');
        $this->scholarshipBuilder        = new scholarshipBuilder();
        $this->scholarshipRepository     = $this->scholarshipBuilder->getScholarshipRepository();
        $this->scholarshipHomePageCache = $this->CI->load->library('scholarshipHomepage/cache/scholarshipHomePageCache'); 
    }

    public function _validateUrl(){
        $currentUrl = getCurrentPageURLWithoutQueryParams();
        $currentUrl = str_replace(SHIKSHA_STUDYABROAD_HOME, '', $currentUrl);
        $currentUrl = trim($currentUrl,'/');
        if($currentUrl != $this->pageUrl){
            redirect(SHIKSHA_STUDYABROAD_HOME.'/'.$this->pageUrl, 'location');
            die;
        }
    }
    
    public function getSEODetails(&$displayData){
        $displayData['seoDetails'] = array();
        $displayData['seoDetails']['url']            = SHIKSHA_STUDYABROAD_HOME.'/'.$this->pageUrl;
        $displayData['seoDetails']['seoTitle']       = 'Study Abroad Scholarships -'.
                                                        $displayData['fsTotalScholarshipCount'] .' International Scholarships for Indian Students';
        $displayData['seoDetails']['seoDescription'] = ' Detailed info on '. $displayData['fsTotalScholarshipCount'].' scholarships to study abroad for '.count($displayData['scholarshipCountryCategoryPageURL']).' countries. Get eligibility, amount, courses & Know how Indian students can apply for these scholarships to study abroad only at Studyabroad.shiksha.com.';
    }

    public function getMISTrackingDetails(&$displayData){
        $displayData['beaconTrackData'] = array(
                                                'pageIdentifier' => 'scholarshipHomepage',
                                                'pageEntityId'   => 0,
                                                'extraData'      => null
                                            );
    }

    public function getCountryMappingWithId(&$displayData, $countryIds = array()){
        if(count($countryIds)==0) {
            global $scholarshipCategoryPageCountries;
            $countryIds = $scholarshipCategoryPageCountries;
        }
         
        foreach ($countryIds as $countryId) {
            $countryObj = $this->locationRepository->findCountry($countryId);
            $displayData['countryMapping'][$countryId] = $countryObj->getName();
        }
        asort($displayData['countryMapping']);   
    }

    public function getScholarshipStatsByCountry(&$displayData, $isMobile, $countryIds = array()){
        $this->CI->load->builder('scholarshipCategoryPageBuilder','scholarshipCategoryPage');
        
        $storeInCache = false;
        if(count($countryIds)==0) {
            global $scholarshipCategoryPageCountries;
            $countryIds = $scholarshipCategoryPageCountries;
            $storeInCache = true;
        }
        $scholarshipCategoryPageBuilder = new scholarshipCategoryPageBuilder;
        foreach ($countryIds as $countryId) {
            $requestData = array();
            $requestData['pageNumber'] = $pageNumber;
            $requestData['countryId'] = $countryId;
            $requestData['type'] = 'country';
            $requestData['countryName'] = $displayData['countryMapping'][$countryId];
            $scholarshipCategoryPageRepository = $scholarshipCategoryPageBuilder->getScholarshipCategoryPageRepository($requestData);
            $request = $scholarshipCategoryPageRepository->getScholarshipCategoryPageRequest();
            $displayData['scholarshipCountryCategoryPageURL'][$countryId] = $request->getPaginatedUrl();
        }
        //all countries
        $allCountryId = -1;
        $countryIds[] = $allCountryId;
        $scholarshipStatistics = array();
        $scholarshipStatistics = $this->getScholarshipStatsByCountryFromSQL($countryIds,$displayData, $storeInCache);
        $allCountryScholarshipCount = $scholarshipStatistics['data'][$allCountryId]['totalScholarships'];
        $allCountryScholarshipAmount = $scholarshipStatistics['data'][$allCountryId]['totalAmount'];
        unset($scholarshipStatistics['data'][$allCountryId]);
        $displayData['fsTotalScholarshipAmount'] = getLongIndianDisplableAmount($displayData['fsTotalScholarshipAmount']);

        foreach ($scholarshipStatistics['data'] as $key => $value) {
            $scholarshipStatistics['data'][$value['countryId']]['totalScholarships'] = $scholarshipStatistics['data'][$value['countryId']]['totalScholarships'] + $allCountryScholarshipCount;
            $scholarshipStatistics['data'][$value['countryId']]['totalAmount'] = $scholarshipStatistics['data'][$value['countryId']]['totalAmount'] + $allCountryScholarshipAmount;
            if($scholarshipStatistics['data'][$value['countryId']]['totalAmount']!=0){
                $scholarshipStatistics['data'][$value['countryId']]['totalAmount'] = getLongIndianDisplableAmount($scholarshipStatistics['data'][$value['countryId']]['totalAmount'])." of Scholarships";
            }
        }

        foreach ($displayData['countryMapping'] as $key => $value) {
            if(!$scholarshipStatistics['data'][$key] && $allCountryScholarshipAmount!=0 && $allCountryScholarshipCount!=0){
                $scholarshipStatistics['data'][$key]['totalAmount'] = getLongIndianDisplableAmount($allCountryScholarshipAmount)." of Scholarships";
                $scholarshipStatistics['data'][$key]['totalScholarships'] = $allCountryScholarshipCount;
                $scholarshipStatistics['data'][$key]['countryId'] = $key;
            }
        }

        foreach ($scholarshipStatistics['data'] as $key => $value) {
            if($value['totalAmount']===0){
                unset($scholarshipStatistics['data'][$value['countryId']]);
            }
        }

        usort($scholarshipStatistics['data'], function ($item1, $item2) {
            if ($item1['totalScholarships'] == $item2['totalScholarships']) return $item1['countryId']>$item2['countryId'];
            return $item1['totalScholarships'] < $item2['totalScholarships'] ? 1 : -1;
        });
        $displayData['scholarshipStatistics'] = $scholarshipStatistics['data'];
        if($isMobile){
            $displayData['scholarshipStatistics'] = array_slice($displayData['scholarshipStatistics'], 0, 6);
        }
    } 

    private function getScholarshipStatsByCountryFromSQL(&$scholarshipCategoryPageCountries,&$displayData, $storeInCache = true){
        $scholarshipStatistics = $this->scholarshipHomePageCache->getSHPCountryWidgetData($this->scholarshipStatisticsKey);
        if(!empty($scholarshipStatistics) && count($scholarshipStatistics)>0 && !empty($scholarshipStatistics['data']) && count($scholarshipStatistics['data'])>0){
            $scholarshipStatistics['data'] = array_intersect_key( $scholarshipStatistics['data'], array_flip( $scholarshipCategoryPageCountries)); 
            
            $displayData['fsTotalScholarshipAmount'] = $scholarshipStatistics['fsTotalScholarshipAmount'];
            $displayData['fsTotalScholarshipCount'] = $scholarshipStatistics['fsTotalScholarshipCount'];
            return $scholarshipStatistics;
        }
        $scholarshipsWithCountry = $this->scholarshiphomepagemodel->getScholarshipsByCountry($scholarshipCategoryPageCountries);
        $scholarshipIds = array_unique(array_map(function($element){return $element['scholarshipId'];}, $scholarshipsWithCountry));
        $displayData['fsTotalScholarshipCount'] = count($scholarshipIds);
        // load exchange rates from lib
        $this->CI->load->library('listing/cache/AbroadListingCache');
	$abroadListingCacheLib  = new AbroadListingCache();
	$exchangeRate 			= $abroadListingCacheLib->getCurrencyConversionFactor(NULL,NULL,true);
        $scholarshipDetails = array();
        $scholarshipDetails = $this->scholarshiphomepagemodel->getScholarshipsAmountAndAwards($scholarshipIds,$exchangeRate);

        $scholarshipStatistics = array();
        foreach ($scholarshipsWithCountry as $key => $value) {
            $scholarshipStatistics['data'][$value['countryId']]['totalAmount'] = $scholarshipStatistics['data'][$value['countryId']]['totalAmount'] + $scholarshipDetails[$value['scholarshipId']]['totalAmount'];
            $scholarshipStatistics['data'][$value['countryId']]['totalScholarships'] = $scholarshipStatistics['data'][$value['countryId']]['totalScholarships'] + 1;
            $scholarshipStatistics['data'][$value['countryId']]['countryId'] = $value['countryId'];
            $displayData['fsTotalScholarshipAmount'] = $displayData['fsTotalScholarshipAmount']+$scholarshipDetails[$value['scholarshipId']]['totalAmount'];
        }
        $scholarshipStatistics['fsTotalScholarshipAmount'] = $displayData['fsTotalScholarshipAmount'];
        $scholarshipStatistics['fsTotalScholarshipCount'] = $displayData['fsTotalScholarshipCount'];
        
        if($storeInCache==true) {
            $this->scholarshipHomePageCache->setSHPCountryWidgetData($this->scholarshipStatisticsKey,$scholarshipStatistics);
        }
        
        return $scholarshipStatistics;
    } 

    public function abroadCategoriesListForFindScholarshipWidget(&$displayData){
        $this->abroadCommonLib  = $this->CI->load->library('listingPosting/AbroadCommonLib');
        $displayData['abroadCategories'] = $this->abroadCommonLib->getAbroadCategories();
        asort($displayData['abroadCategories'], function($a,$b) {
          return $a['name']>$b['name'];
        });
    }

    public function getPopularScholarshipFromSolr($scholarshipCount,$additionalFilters=array())
    {
        // get popular scholarship data from solr
        $this->scholarshipHomePageSolrRequestGenerator  = $this->CI->load->library('scholarshipHomepage/solr/scholarshipHomePageSolrRequestGenerator');
        $this->scholarshipHomePageSolrResponseParser  = $this->CI->load->library('scholarshipHomepage/solr/scholarshipHomePageSolrResponseParser');
        $solrRequestUrl = $this->scholarshipHomePageSolrRequestGenerator->getPopularScholarshipsRequestUrl($scholarshipCount,$additionalFilters);
        $solrClient = $this->CI->load->library("SASearch/AutoSuggestorSolrClient");
        $response = $solrClient->getCategoryPageResults($solrRequestUrl,'scholarshipCategoryPage');
        $response = $this->scholarshipHomePageSolrResponseParser->parsePopularScholarshipSolrResponse($response);
        return $response;
    }
    
    public function getPopularScholarship(&$displayData,$isMobile){
        $scholarshipCount = ($isMobile)?10:15;
        $response = $this->getPopularScholarshipFromSolr($scholarshipCount);
        $countries = array();
        foreach ($response['countries'] as $countryId) {
            if($countryId>0){
                $countryObj = $this->locationRepository->findCountry($countryId);
                $countries[$countryId] = $countryObj->getName();
            }
        }
        $sections = array('basic'=>array('scholarshipId','seoUrl'));
        $response['allScholarships'] = array_filter($response['allScholarships']);
        $scholarshipObj = $this->scholarshipRepository->findMultiple($response['allScholarships'],$sections);
        global $studyAbroadPopularCountries;
        $allCountriesCount = count($this->locationRepository->getAllCountries());
        $scholarshipURLObj = array();
        foreach ($scholarshipObj as $key => $value) {
            $scholarshipURLObj[$value->getId()] = $value->getUrl();
        }
        foreach ($response['scholarships'] as $key => $value) {
           if($response['scholarships'][$key]['saScholarshipType']=='both'){
                $response['scholarships'][$key]['saScholarshipType'] = 'Need and Merit';
           }
           $response['scholarships'][$key]['seoUrl'] = $scholarshipURLObj[$value['saScholarshipId']];
           if($response['scholarships'][$key]['saScholarshipCategory']=='internal'){
                $response['scholarships'][$key]['saScholarshipUniversity'] = formatArticleTitle(htmlentities($response['scholarships'][$key]['saScholarshipUnivName'].' in '.$countries[$value['saScholarshipCountryId'][0]]),50);
           }else{
                if($value['saScholarshipCountryId'][0]==1){
                    $response['scholarships'][$key]['countryCount'] = $allCountriesCount;
                    $response['scholarships'][$key]['saScholarshipUniversity'] = "All universities in ".implode(', ',array_slice($studyAbroadPopularCountries,0,2));
                }else{
                    $response['scholarships'][$key]['saScholarshipUniversity'] = 'All universities in '.$countries[$value['saScholarshipCountryId'][0]];
                    if($value['countryCount']>1){
                        $response['scholarships'][$key]['saScholarshipUniversity'] .= ', '.$countries[$value['saScholarshipCountryId'][1]];
                    }
                }
           }
        }
        $displayData['popularScholarships'] = $response['scholarships'];
    }

    /*
     * function that gets scholarship related articles & guides
     */
    public function getScholarshipRelatedContent(& $displayData)
    {
        // get scholarship related contentIds
        $contentIds = $this->scholarshiphomepagemodel->getContentIdsWithScholarshipTags();
        if(count($contentIds)>0)
        {
            // get popular scholarship content details
            $displayData['popularContent'] = $this->scholarshiphomepagemodel->getSortedScholarshipContent('popularity',$contentIds,5);
            // get recent scholarship content details
            $displayData['recentContent'] = $this->scholarshiphomepagemodel->getSortedScholarshipContent('recency',$contentIds,7);
        }
    }
}
