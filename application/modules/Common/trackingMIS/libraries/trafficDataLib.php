<?php
require_once('vendor/autoload.php');

class trafficDataLib {
    private $CI;
    private $clientCon;
    private $clientParams;
    //private static $TRAFFIC_PAGEVIEWS, $TRAFFIC_SESSIONS;

    public function __construct(){
        $this->CI = & get_instance();
        $this->usergroupAllowed = array("shikshaTracking");
        $this->MISCommonLib = $this->CI->load->library('trackingMIS/MISCommonLib');
        $this->clientCon = $this->MISCommonLib->_getSearchServerConnection();
        $this->abroadCommonLib = $this->CI->load->library('listingPosting/AbroadCommonLib');
    }

    function prepareAbroadFiltersForPageViews($extraData,$page,& $params)
    {   
        $params['body']['query']['filtered']['query']['bool']['must'][]['match']['isStudyAbroad'] = "yes";

        if($page){
            if($page == 'rankingPage'){
                if($extraData['pageType'] == 0){
                    $params['body']['query']['filtered']['query']['bool']['should'][]['match']['pageIdentifier'] = 'courseRankingPage';
                    $params['body']['query']['filtered']['query']['bool']['should'][]['match']['pageIdentifier'] = 'universityRankingPage';
                    $params['body']['query']['filtered']['query']['bool']['minimum_should_match'] = 1;    
                }else if($extraData['pageType'] == 1){
                    $params['body']['query']['filtered']['query']['bool']['must'][]['match']['pageIdentifier'] = 'universityRankingPage';        
                }else if($extraData['pageType'] == 2){
                    $params['body']['query']['filtered']['query']['bool']['must'][]['match']['pageIdentifier'] = 'courseRankingPage';    
                }    
            }else{
                $params['body']['query']['filtered']['query']['bool']['must'][]['match']['pageIdentifier'] = $page;    
            }
        }

        if(is_array($extraData['country']) ){
            if(sizeof( $extraData['country']) == 1){
                    $params['body']['query']['filtered']['query']['bool']['must'][]['match']['countryId'] = $extraData['country'][0];
            }else{
                foreach ($extraData['country'] as $key => $value) {
                    $params['body']['query']['filtered']['query']['bool']['should'][]['match']['countryId'] = $value;
                }
                $params['body']['query']['filtered']['query']['bool']['minimum_should_match'] = 1;    
            }    
        }else{
            if($extraData['country'] !=0 ){
                $params['body']['query']['filtered']['query']['bool']['must'][]['match']['countryId'] = $extraData['country'];    
            }
        }

        if($extraData['categoryId'] !=0 ){
            $ldbCourseIdsArray = $this->abroadCommonLib->getAbroadMainLDBCourses();
            foreach ($ldbCourseIdsArray as $key => $value) {
                $ldbCourseIds[]= $value['SpecializationId'];
            }

            if(in_array($extraData['categoryId'],$ldbCourseIds)){
                $params['body']['query']['filtered']['query']['bool']['must'][]['match']['LDBCourseId'] = $extraData['categoryId'];
            }else{
                $params['body']['query']['filtered']['query']['bool']['must'][]['match']['categoryId'] = $extraData['categoryId'];
                if($extraData['courseLevel']!= ''){
                    if($extraData['courseLevel']!= '0'){
                        $params['body']['query']['filtered']['query']['bool']['must'][]['match']['courseLevel'] = $extraData['courseLevel'];
                    }
                }
            }
        }else{  
            if($extraData['courseLevel']!= ''){
                if($extraData['courseLevel']!= '0'){
                    $params['body']['query']['filtered']['query']['bool']['must'][]['match']['courseLevel'] = $extraData['courseLevel'];
                }
            }
        }
    }

    private function _prepareAbroadFilters($extraData,$page,& $params)
    {   
        $params['body']['query']['filtered']['query']['bool']['must'][]['match']['isStudyAbroad'] = "yes";

        if($page){
            if($page == 'rankingPage'){
                if($extraData['pageType'] == 0){
                    $params['body']['query']['filtered']['query']['bool']['should'][]['match']['landingPageDoc.pageIdentifier'] = 'courseRankingPage';
                    $params['body']['query']['filtered']['query']['bool']['should'][]['match']['landingPageDoc.pageIdentifier'] = 'universityRankingPage';
                    $params['body']['query']['filtered']['query']['bool']['minimum_should_match'] = 1;    
                }else if($extraData['pageType'] == 1){
                    $params['body']['query']['filtered']['query']['bool']['must'][]['match']['landingPageDoc.pageIdentifier'] = 'universityRankingPage';        
                }else if($extraData['pageType'] == 2){
                    $params['body']['query']['filtered']['query']['bool']['must'][]['match']['landingPageDoc.pageIdentifier'] = 'courseRankingPage';    
                }    
            }else{
                $params['body']['query']['filtered']['query']['bool']['must'][]['match']['landingPageDoc.pageIdentifier'] = $page;    
            }
        }

        if(is_array($extraData['country']) ){
            if(sizeof( $extraData['country']) == 1){
                    $params['body']['query']['filtered']['query']['bool']['must'][]['match']['landingPageDoc.countryId'] = $extraData['country'][0];
            }else{
                foreach ($extraData['country'] as $key => $value) {
                    $params['body']['query']['filtered']['query']['bool']['should'][]['match']['landingPageDoc.countryId'] = $value;
                }
                $params['body']['query']['filtered']['query']['bool']['minimum_should_match'] = 1;    
            }    
        }else{
            if($extraData['country'] !=0 ){
                $params['body']['query']['filtered']['query']['bool']['must'][]['match']['landingPageDoc.countryId'] = $extraData['country'];    
            }
        }
        
        if($extraData['categoryId'] !=0 ){
            $ldbCourseIdsArray = $this->abroadCommonLib->getAbroadMainLDBCourses();
            foreach ($ldbCourseIdsArray as $key => $value) {
                $ldbCourseIds[]= $value['SpecializationId'];
            }

            if(in_array($extraData['categoryId'],$ldbCourseIds)){
                $params['body']['query']['filtered']['query']['bool']['must'][]['match']['landingPageDoc.LDBCourseId'] = $extraData['categoryId'];
            }else{
                $params['body']['query']['filtered']['query']['bool']['must'][]['match']['landingPageDoc.categoryId'] = $extraData['categoryId'];
                if($extraData['courseLevel']!= ''){
                    if($extraData['courseLevel']!= '0'){
                        $params['body']['query']['filtered']['query']['bool']['must'][]['match']['landingPageDoc.courseLevel'] = $extraData['courseLevel'];
                    }
                }
            }
        }else{  
            if($extraData['courseLevel']!= ''){
                if($extraData['courseLevel']!= '0'){
                    $params['body']['query']['filtered']['query']['bool']['must'][]['match']['landingPageDoc.courseLevel'] = $extraData['courseLevel'];
                }
            }
        }
    }

    // Used in case of traffic. Can we change this with some common method since it has been used in case of engagement (like exit rate) as well
    private function _prepareNationalFilters($extraData,$page,& $params)
    {
        $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['isStudyAbroad'] = "no";

        $subcategories = explode(",", $extraData['subcategory']);

        if ($extraData['deviceType'] != 'all') {
            $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['isMobile'] = ($extraData['deviceType'] == 'desktop') ? 'no' : 'yes';
        }

        $pageData                    = $this->MISCommonLib->getPageNameForDomestic($page);
        if (strtolower($pageData) != 'all') {
            $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['landingPageDoc.pageIdentifier'] = $pageData;
        }

        $invalidPages = array('home', 'institute', 'article_detail', 'qna');
        if ( !in_array($page, $invalidPages) && intval($extraData['category']) != '0') {
            if ($page == 'exam_calendar' || $page == 'article_home' ) {
                if (intval($extraData['subcategory']) != 0 ){
                    $params['body']['query']['filtered']['filter']['bool']['must'][]['terms']['landingPageDoc.subCategoryId'] = $subcategories;
                }
            } else {
                $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['landingPageDoc.categoryId'] = $extraData['category'];
                if (intval($extraData['subcategory']) != 0){
                    $params['body']['query']['filtered']['filter']['bool']['must'][]['terms']['landingPageDoc.subCategoryId'] = $subcategories;
                }
            }
        }
    }

    private function _prepareCDFilters($extraData,$dateRange)
    {
        $trafficSources = array(
            'paid' => array("paid"),
            'free' => array("mailer","seo","notsure","direct","social")
            );

        $isStudyAbroad = isset($extraData['CD']['isStudyAbroad'])?$extraData['CD']['isStudyAbroad']:'no';
        $deviceType = $extraData['CD']['deviceType'];
        $startDate = $dateRange['startDate'];
        $endDate = $dateRange['endDate'];
        $isMobile = '';

        $elasticQuery['index'] = MISCommonLib::$TRAFFICDATA_SESSIONS;
        $elasticQuery['type']  = 'session';
        $elasticQuery['body']['size'] = 0;
        $prefix = 'landingPageDoc.';

         if (isset($extraData['CD']['deviceType'])) {
                $deviceType = $extraData['CD']['deviceType'];
                $isMobile   = ($deviceType == 'desktop') ? 'no' : 'yes';
            }
            
        if (count($extraData['CD']['courseId']) > 0 || count($extraData['CD']['instituteId']) > 0) { 


            if(isset($extraData['CD']['isStudyAbroad'])) {
                $isStudyAbroad = $extraData['CD']['isStudyAbroad'];
            } else {
                $isStudyAbroad = 'no';
            }

            foreach ($extraData['CD']['instituteId'] as $instituteId) {
                $pageEntityId['term'][$prefix.'pageEntityId'] = intval($instituteId);
                $pageIdentifier = '';

                if ($isStudyAbroad == 'yes') {
                    $pageIdentifier['term'][$prefix.'pageIdentifier'] = $this->MISCommonLib->getPageNameForAbroad('institute');
                } else {

                    if ($deviceType == 'desktop') {
                        $pageIdentifier['term'][$prefix.'pageIdentifier'] = $this->MISCommonLib->getPageNameForDomestic('institute', $deviceType);
                    }else if($deviceType == 'mobile') 
                    {
                        $mobileData = array(
                            'term' => array(
                                $prefix.'pageIdentifier' => $this->MISCommonLib->getPageNameForDomestic('institute', 'mobile')
                            )
                        );

                        $mobile5Data = array(
                            'term' => array(
                                $prefix.'pageIdentifier' => $this->MISCommonLib->getPageNameForDomestic('allCoursePage', 'mobile')
                            )
                        );

                        $pageIdentifier = array(
                            'bool' => array(
                                'should' => array(
                                    $mobileData,
                                    $mobile5Data
                                )
                            )
                        );   
                    }
                    else {
                        $mobileData = array(
                            'term' => array(
                                $prefix.'pageIdentifier' => $this->MISCommonLib->getPageNameForDomestic('institute', 'mobile')
                            )
                        );

                        $desktopData = array(
                            'term' => array(
                                $prefix.'pageIdentifier' => $this->MISCommonLib->getPageNameForDomestic('institute', 'desktop')
                            )
                        );
                        $mobile5Data = array(
                            'term' => array(
                                $prefix.'pageIdentifier' => $this->MISCommonLib->getPageNameForDomestic('allCoursePage', 'mobile')
                            )
                        );

                        $pageIdentifier = array(
                            'bool' => array(
                                'should' => array(
                                    $mobileData,
                                    $desktopData,
                                    $mobile5Data
                                )
                            )
                        );
                    }
                }

                $elasticQuery['body']['query']['filtered']['filter']['bool']['should'][]['bool']['must'] = array(
                    $pageEntityId,
                    $pageIdentifier
                );
            }


            foreach ($extraData['CD']['courseId'] as $oneCourseId) {
                $pageEntityId['term'][$prefix.'pageEntityId'] = intval($oneCourseId);
                $pageIdentifier = '';

                if ($isStudyAbroad == 'yes') {
                    $pageIdentifier['term'][$prefix.'pageIdentifier'] = $this->MISCommonLib->getPageNameForAbroad('courselisting');
                } else {

                    if ($deviceType != '') {
                        $pageIdentifier['term'][$prefix.'pageIdentifier'] = $this->MISCommonLib->getPageNameForDomestic('courselisting', $deviceType);
                    } else {
                        $mobileData = array(
                            'term' => array(
                                $prefix.'pageIdentifier' => $this->MISCommonLib->getPageNameForDomestic('courselisting', 'mobile')
                            )
                        );

                        $desktopData = array(
                            'term' => array(
                                $prefix.'pageIdentifier' => $this->MISCommonLib->getPageNameForDomestic('courselisting', 'desktop')
                            )
                        );

                        $pageIdentifier = array(
                            'bool' => array(
                                'should' => array(
                                    $mobileData,
                                    $desktopData
                                )
                            )
                        );
                    }
                }

                $elasticQuery['body']['query']['filtered']['filter']['bool']['should'][]['bool']['must'] = array(
                    $pageEntityId,
                    $pageIdentifier
                );
            }
        } else if(count($extraData['CD']['articleId']) > 0) {

            foreach ($extraData['CD']['articleId'] as $oneArticleId) {
                $pageEntityId['term'][$prefix.'pageEntityId'] = intval($oneArticleId);
                $pageIdentifier = '';

                if ($isStudyAbroad == 'yes') {
                    $pageIdentifier['term'][$prefix.'pageIdentifier'] = $this->MISCommonLib->getPageNameForAbroad('article_detail');
                } else {
                    if ($deviceType != '') {
                        $pageIdentifier['term'][$prefix.'pageIdentifier'] = $this->MISCommonLib->getPageNameForDomestic('article_detail', $deviceType);
                    } else {
                        $mobileData = array(
                            'term' => array(
                                $prefix.'pageIdentifier' => $this->MISCommonLib->getPageNameForDomestic('article_detail', 'mobile')
                            )
                        );

                        $desktopData = array(
                            'term' => array(
                                $prefix.'pageIdentifier' => $this->MISCommonLib->getPageNameForDomestic('article_detail', 'desktop')
                            )
                        );

                        $pageIdentifier = array(
                            'bool' => array(
                                'should' => array(
                                    $mobileData,
                                    $desktopData
                                )
                            )
                        );
                    }
                }

                $elasticQuery['body']['query']['filtered']['filter']['bool']['should'][]['bool']['must'] = array(
                    $pageEntityId,
                    $pageIdentifier
                );
            }
        }
        else if(count($extraData['CD']['discussionId']) > 0)
        {
            //startd
            foreach ($extraData['CD']['discussionId'] as $oneDiscussionId) {
                $pageEntityId['term'][$prefix.'pageEntityId'] = intval($oneDiscussionId);
                $pageIdentifier = '';

                if ($isStudyAbroad == 'yes') {
                    $pageIdentifier['term'][$prefix.'pageIdentifier'] = $this->MISCommonLib->getPageNameForAbroad('discussionDetail');
                } else {
                    if ($deviceType != '') {
                        $pageIdentifier['term'][$prefix.'pageIdentifier'] = $this->MISCommonLib->getPageNameForDomestic('discussionDetail', $deviceType);
                    } else {
                        /*$mobileData = array(
                            'term' => array(
                                $prefix.'pageIdentifier' => strtolower($this->MISCommonLib->getPageNameForDomestic('discussionDetail', 'mobile'))
                            )
                        );*/

                        $desktopData = array(
                            'term' => array(
                                $prefix.'pageIdentifier' => $this->MISCommonLib->getPageNameForDomestic('discussionDetail', 'desktop')
                            )
                        );

                        $pageIdentifier = array(
                            'bool' => array(
                                'should' => array(
                                    //$mobileData,
                                    $desktopData
                                )
                            )
                        );
                    }
                }

                $elasticQuery['body']['query']['filtered']['filter']['bool']['should'][]['bool']['must'] = array(
                    $pageEntityId,
                    $pageIdentifier
                );
            }
            //ended
        }
        else if(count($extraData['CD']['subCategoryId']) >0 || count($extraData['CD']['authorId']) > 0)
        {
            //

            if($extraData['CD']['pageName'] == 'institute')
            {
                if ($isStudyAbroad == 'yes') {
                        $instituteData = array(
                            'terms' => array(
                                $prefix.'pageIdentifier' => array($this->MISCommonLib->getPageNameForAbroad('institute'),
                                    $this->MISCommonLib->getPageNameForAbroad('courselisting'))
                                )
                            );
                        $elasticQuery['body']['query']['filtered']['filter']['bool']['must'] = array(
                                      $instituteData
                                );
                } else {
                        if($deviceType == 'desktop')
                        {
                          
                          $instituteData = array(
                            'terms' => array(
                                $prefix.'pageIdentifier' => array($this->MISCommonLib->getPageNameForDomestic('institute','desktop'),
                                    $this->MISCommonLib->getPageNameForDomestic('courselisting','desktop'))
                                )
                            );
                          $elasticQuery['body']['query']['filtered']['filter']['bool']['must'] = array(
                                      $instituteData
                                );
                        }
                        else if($deviceType == 'mobile')
                        {
                            $instituteData = array(
                                'terms' => array(
                                    $prefix.'pageIdentifier' => array($this->MISCommonLib->getPageNameForDomestic('institute','mobile'),
                                    $this->MISCommonLib->getPageNameForDomestic('courselisting','mobile'),$this->MISCommonLib->getPageNameForDomestic('allCoursePage','mobile')) 
                                    )
                                );
                            $elasticQuery['body']['query']['filtered']['filter']['bool']['must'] = array(
                                            $instituteData
                                    );
                        }
                        else 
                        {
                            $instituteData = array(
                            'terms' => array(
                                $prefix.'pageIdentifier' => array($this->MISCommonLib->getPageNameForDomestic('institute','desktop'),
                                    $this->MISCommonLib->getPageNameForDomestic('courselisting','desktop'),$this->MISCommonLib->getPageNameForDomestic('institute','mobile'),$this->MISCommonLib->getPageNameForDomestic('courselisting','mobile'),$this->MISCommonLib->getPageNameForDomestic('allCoursePage','mobile'))
                                )
                            );   
                            $elasticQuery['body']['query']['filtered']['filter']['bool']['must'] = array(
                                            $instituteData
                                    );
                        }
                     }
                
            }
            else if($extraData['CD']['pageName'] == 'articlePage')
            {
                if ($isStudyAbroad == 'yes') {
                        $articleData = array(
                            'terms' => array(
                                $prefix.'pageIdentifier' => array($this->MISCommonLib->getPageNameForAbroad('article_detail'))
                                )
                            );
                        $elasticQuery['body']['query']['filtered']['filter']['bool']['must'] = array(
                                      $articleData
                                );
                }
                else
                {
                    if($deviceType == 'desktop')
                    {
                        $articleData =  array(
                            'terms' => array(
                                $prefix.'pageIdentifier' => array($this->MISCommonLib->getPageNameForDomestic('article_detail','desktop'))
                                )
                            );
                        $elasticQuery['body']['query']['filtered']['filter']['bool']['must'] = array(
                                      $articleData
                                );
                    }
                    else if($deviceType == 'mobile')
                    {
                        $articleData = array(
                            'terms' => array(
                                $prefix.'pageIdentifier' => array($this->MISCommonLib->getPageNameForDomestic('article_detail','mobile')
                                    )
                                )
                            );
                        $elasticQuery['body']['query']['filtered']['filter']['bool']['must'] = array(
                                      $articleData
                                );

                    }
                    else
                    {
                        $articleData = array(
                            'terms' => array(
                                $prefix.'pageIdentifier' => array($this->MISCommonLib->getPageNameForDomestic('article_detail','mobile'),$this->MISCommonLib->getPageNameForDomestic('article_detail','desktop')
                                    )
                                )
                            );
                        $elasticQuery['body']['query']['filtered']['filter']['bool']['must'] = array(
                                      $articleData
                                );

                    }

                }
            }
            else if($extraData['CD']['pageName'] == 'discussionPage')
            {
                $discussionData = array(
                            'terms' => array(
                                $prefix.'pageIdentifier' => array($this->MISCommonLib->getPageNameForDomestic('discussionDetail','desktop'))
                                )
                            );
                        $elasticQuery['body']['query']['filtered']['filter']['bool']['must'] = array(
                                      $discussionData
                                );
            }
            //
        }

        //---------------------------------------------------------------------------------------------
        $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['term'][$prefix.'isStudyAbroad'] = $isStudyAbroad;
         if ( ! empty($extraData['CD']['deviceType'])) {
            $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['term'][$prefix.'isMobile'] = $isMobile;
        }
        if(isset($extraData['CD']['subCategoryId'])){
            $subCategoryId = $extraData['CD']['subCategoryId'];
            foreach ($subCategoryId as $key => $value) {
                $subCategoryFilter['bool']['should'][]['term'][$prefix.'subCategoryId'] = $value;
            }
            $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = $subCategoryFilter;
        }
        if(isset($extraData['CD']['cityId']) || isset($extraData['CD']['stateId']))
        {
            $cityId =  $extraData['CD']['cityId'];
            foreach ($cityId as $key => $value) {
                $cityFilter['bool']['should'][]['term'][$prefix.'cityId'] =  $value;
            }
            $stateId =  $extraData['CD']['stateId'];
            foreach ($stateId as $key => $value) {
                $cityFilter['bool']['should'][]['term'][$prefix.'stateId'] =  $value;
            }
            $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = $cityFilter;
        }
        /*if(isset($extraData['CD']['stateId']))
        {
            $stateId =  $extraData['CD']['stateId'];
            foreach ($stateId as $key => $value) {
                $stateFilter['bool']['should'][]['term'][$prefix.'stateId'] =  $value;
            }
            $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = $stateFilter;
        }*/
        if(isset($extraData['CD']['countryId']))
        {
            $countryId = $extraData['CD']['countryId'];
            foreach ($countryId as $key => $value) {
                $countryFilter['bool']['should'][]['term'][$prefix.'countryId'] = $value;
            }
            $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = $countryFilter;
        }
        if(isset($extraData['CD']['authorId']))
        {
            $authorId = $extraData['CD']['authorId'];
            foreach ($authorId as $key => $value) {
                $authorFilter['bool']['should'][]['term'][$prefix.'authorId'] = $value;
            }
            $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = $authorFilter;
        }

         if( ! empty($extraData['CD']['trafficSource'])){
            $trafficSourceType = $extraData['CD']['trafficSource'];

            $trafficSourceWiseGrouping = array();
            foreach ($trafficSources[$trafficSourceType] as $oneTrafficSourceType => $oneTrafficSourceName) {
                $trafficSourceWiseGrouping['bool']['should'][]['term']['source'] = $oneTrafficSourceName;
            }

            $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = $trafficSourceWiseGrouping;

        }
        $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['range']['startTime']['lte'] = $dateRange['endDate'] . 'T23:59:59';    
        $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['range']['startTime']['gte'] = $dateRange['startDate']. 'T00:00:00';
            $view = $this->_getView($extraData['CD']['view']);
            $extraData['CD']['view'] = $view;
            $siteSourseFilter['siteSourse']['terms']['field'] = 'isMobile';
            $pageWiseFilter['siteSourse']['terms']['size']= 0;

            $sourceWiseFilter['sourseWise']['terms']['field']= 'source';
            $sourceWiseFilter['sourseWise']['aggs'] = $siteSourseFilter;

            $dateWiseFilter['dateWiseCount']['date_histogram']['interval'] =$extraData['CD']['view'];
            $dateWiseFilter['dateWiseCount']['date_histogram']['field'] = 'startTime';
            $dateWiseFilter['dateWiseCount']['aggs'] = $sourceWiseFilter; 
            $elasticQuery['body']['aggs'] = $dateWiseFilter;                     
             
        return $elasticQuery;
    }

    private function _prepareDataForBargraphForpgps($pageWise,$extraData)
    {
        arsort($pageWise);
        //_p($extraData['engagementType']);die;
        $maxValue = 0;
        $i=0;
        foreach ($pageWise as $key => $value) {
            if($i==0){
                $maxValue = $value;
            }else{
                break;   
            }    
            $i++;
        }
        $avg = number_format((($maxValue)/100), 2,'.','');
        /*_p($maxValue);_p($avg);
        _p($pageWise);die;*/
        foreach ($pageWise as $key => $value) {
           //_p($value);die;
            $valueWidth = number_format(($value/$avg), 0, '.', '');
        if($extraData['engagementType']== 'avgsessdur'){
            $field = '<div style="width: 100px"><small style="width:10px !important;">&nbsp '.$value.'</small>&nbsp;<small style="width:10px !important;font-size: 15px;">Mins</small></div>';
        }else if($extraData['engagementType']== 'pgpersess'){
            $field = '<div style="width: 100px"><small style="width:10px !important;">&nbsp '.$value.'</small>&nbsp;<small style="width:10px !important;font-size: 15px;">Pages</small></div>';
        }else if($extraData['engagementType']== 'exit' || $extraData['engagementType']== 'bounce'){
            $field = '<div style="width: 100px"><small style="width:10px !important;">&nbsp '.$value.'</small>&nbsp;<small style="width:10px !important;font-size: 15px;">%</small></div>';
        }else{
            $field ='<div style="width: 100px"><small style="width:10px !important;">&nbsp '.$value.'</small></div>';
        }
        //pgpersess
        //_p($key);
        $splitName = strlen($key) > 11 ? substr($key, 0, 10) . ' ...' : $key;
        $barGraph = $barGraph.
            '<div class="widget_summary">'.
                '<div class="w_left w_25">'.
                   '<span title= "'.$key.'" >'.$splitName.'</span>'.
                '</div>'.
                '<div class="w_center w_55">'.
                    '<div class="progress">'.
                        '<div  title = "'.$value.'" class="progress-bar bg-green" role="progressbar" style="width:'.$valueWidth.'%'.'" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100">'.
                            '<span class="sr-only"  ></span>'.
                        '</div>'.
                 '</div>'.
                '</div>'.
                '<div class="w_right ">'.
                    '<span >'.$field.'</span>'.
                '</div>'.
                '<div class="clearfix"></div>'.
            '</div>';
            //_p($barGraph);die;
        }
        //_p($barGraph);die;
        return $barGraph; 
    }

    private function _prepareDataForDataTableForBounce($prepareTableData)
    {
        //_p($prepareTableData);die;
        
        $dataTableHeading = " (Page-Source Application wise) ";
        //Traffic Data (Page – Source - Source Application wise) 
        //$coloumHeading = $this->getColoumHeading($pageName);
        $dataTable = '<thead>'.
                        '<tr class="headings">'.
                            '<th style="padding-left:20px">'.
                                '<input type="checkbox" class="tableflat">'.
                            '</th>'.
                            '<th style="padding-left:20px">'.'Page </th>'.
                            '<th style="padding-left:20px">Source Application </th>'.
                            '<th style="padding-left:20px;width:100px">Count </th>'.
                            '<th style="padding-left:20px;width:130px">Count (%) </th>'.
                        '</tr>'.
                    '</thead>'.
                    '<tbody>';
        $prepareDataForCSV[0]  = array('Page','Device','Count','Count (%)');
        $i=1;

        foreach ($prepareTableData as $page => $pageArray){
            foreach ($pageArray as $device => $value) {
                $dataTable = $dataTable.
                    '<tr class="even pointer">'.
                        '<td class="a-center ">'.
                            '<input type="checkbox" class="tableflat">'.
                        '</td>'.
                        '<td class=" ">'.$page.'</td>'.
                        '<td class=" ">'.$device.'</td>'.
                        '<td class=" ">'.$value['visitCount'].'</td>'.
                        '<td>'.$value['visitCount'].'</td>'.
                    '</tr>';
                $prepareDataForCSV[$i++] = array($page,$device,$value['visitCount'],$value['visitCount']);
            }    
        }

        $dataTable = $dataTable.'</tbody>';
        $DataForDataTable = array($dataTableHeading,$dataTable,$prepareDataForCSV);
        //_p($DataForDataTable);die;
        return $DataForDataTable;  
    }

    private function _getBounceData($totalData,$bounceData,$extraData,$isComparision)
    {

        $totalSessions = $totalData['dataForDifferentCharts']['lineChartData'][1];
        $totalSessionsForBounce = $bounceData['dataForDifferentCharts']['lineChartData'][1];
        for ($i=0; $i < sizeof($totalSessions); $i++) 
        { 
            $temp = $totalSessions[$i][1];
            $temp1 = $totalSessionsForBounce[$i][1];
            $lineChartDataForBounce[$i]=array($totalSessions[$i][0],number_format((($temp1*100)/$temp), 2, '.', ''));
        }

        $totalSessions ='';
        $totalSessionsForBounce ='';
        $totalSessions = $totalData['dataForDifferentCharts']['barGraph'][2];
        $totalSessionsForBounce = $bounceData['dataForDifferentCharts']['barGraph'][2];
        //_p($totalSessionsForBounce);die;//_p(sizeof($totalSessionsForBounce));_p(sizeof($totalSessions));die;
        foreach ($totalSessions as $key => $value) {
            $pageWise[$key] = number_format((($totalSessionsForBounce[$key]*100)/$value), 2, '.', '');
        }
        if(!$isComparision){
            $temp = $bounceData['dataForDifferentCharts']['dataForDataTable'];
            $a = array();
            foreach($totalData['dataForDifferentCharts']['dataForDataTable'] as $key=>$v){
                $mobile ='';
                $Desktop = '';
                //_p($extraData);die;
                if($extraData['studyAbroad']['engagementType'] == 'bounce'){
                    $mobile = number_format((($temp[$key]['Mobile']['visitCount']*100)/$v['Mobile']['visitCount']), 2, '.', '');
                    $desktop = number_format((($temp[$key]['Desktop']['visitCount']*100)/$v['Desktop']['visitCount']), 2, '.', '');
                }else{
                    $mobile = number_format((($temp[$key]['Mobile']['visitCount'])/$v['Mobile']['visitCount']), 2, '.', '');
                    $desktop = number_format((($temp[$key]['Desktop']['visitCount'])/$v['Desktop']['visitCount']), 2, '.', '');
                }
                $a[$key] = array(
                                'Desktop' => array(
                                                'visitCount' => $desktop
                                                    ),
                                'Mobile' => array(
                                            'visitCount' => $mobile
                                                )
                                );
            }
            $datatable = $this->_prepareDataForDataTableForBounce($a);
        }else{
            $pagewiseBR = $this->_prepareDataForBargraphForpgps($pageWise,$extraData['studyAbroad']);
        }
        $totalSessions ='';
        $totalSessionsForBounce ='';
        //_p($lineChartDataForBounce);die;
        //_p($totalSessions);_p($totalSessionsForBounce);_p(sizeof($totalSessionsForBounce));die;
        $totalSessions = $totalData['dataForDifferentCharts']['barGraph'][1];
        $totalSessionsForBounce = $bounceData['dataForDifferentCharts']['barGraph'][1];
        $devicewise['Desktop'] = number_format((($totalSessionsForBounce['Desktop']*100)/$totalSessions['Desktop']), 2, '.', '');
        $devicewise['Mobile']  = number_format((($totalSessionsForBounce['Mobile']*100)/$totalSessions['Mobile']), 2, '.', '');
        $devicewiseBG = $this->_prepareDataForBargraphForpgps($devicewise,$extraData['studyAbroad']);
        //_p($devicewiseBG);die;

        $t = $totalSessions['Desktop'] + $totalSessions['Mobile'];
        $t1 = $totalSessionsForBounce['Desktop'] + $totalSessionsForBounce['Mobile'];
        $totalSessions = $totalData['dataForDifferentCharts']['barGraph'][0];
        $totalSessionsForBounce = $bounceData['dataForDifferentCharts']['barGraph'][0];
        $userwise['Loggedin'] = number_format((($totalSessionsForBounce['Loggedin']*100)/$totalSessions['Loggedin']), 2, '.', '');
        $userwise['Non Loggedin']  = number_format((($totalSessionsForBounce['Non Loggedin']*100)/$totalSessions['Non Loggedin']), 2, '.', '');
        //_p($userwise);die;
        $userwiseBR = $this->_prepareDataForBargraphForpgps($userwise,$extraData['studyAbroad']);
        if(!$isComparision){
            $data['dataForDifferentCharts'] = array(
                                            'lineChartData'  => array('lineChartArray',$lineChartDataForBounce),
                                            'barGraphData' => array(
                                                            'deviceWise' => $devicewiseBG,
                                                            'userWise' => $userwiseBR,
                                                            ),
                                            'dataForDataTable' => $datatable
                                                );  

        }else{
            $data['dataForDifferentCharts'] = array(
                                            'lineChartData'  => array('lineChartArray',$lineChartDataForBounce),
                                            'barGraphData' => array(
                                                            'deviceWise' => $devicewiseBG,
                                                            'userWise' => $userwiseBR,
                                                            'pageWise' => $pagewiseBR
                                                            )
                                                );   
        }
        $data['type'] ='bounce';

        $bounceRate =number_format((($t1*100)/$t), 2, '.', '');
        //$data['topTiles'] = array(1,$bounceRate,0,0,0);
        $data['total'] = $bounceRate;
        //_p($data);die;
        return $data;
    }

    private function _getTopTilesDataForEng($dateRange,$extraData,$pageName)
    {
        $elasticQuery = array();
        $elasticQuery['index'] = MISCommonLib::$TRAFFICDATA_SESSIONS;
        $elasticQuery['type']  = 'session';
        $elasticQuery['body']['size'] = 0;
        $prefix = 'landingPageDoc.';
        $isStudyAbroad = 'yes';
        $categoryId    = isset($extraData['studyAbroad']['categoryId']) ? $extraData['studyAbroad']['categoryId'] : 0;
        $courseLevel   = isset($extraData['studyAbroad']['courseLevel']) ? $extraData['studyAbroad']['courseLevel'] : 0;
        $countryId     = isset($extraData['studyAbroad']['country']) ? $extraData['studyAbroad']['country'] : 0;
        $valuesToMatch = array();
        $startDate = $dateRange['startDate'];
        $endDate = $dateRange['endDate'];
        $ldbCourseIdsArray = $this->abroadCommonLib->getAbroadMainLDBCourses();
        foreach ($ldbCourseIdsArray as $key => $value) {
            $specialCategories[]= $value['SpecializationId'];
        }

        $pageType = isset($extraData['studyAbroad']['pageType']) ? $extraData['studyAbroad']['pageType'] : 0;
        if ($categoryId != 0) {
            if (array_search($categoryId, $specialCategories) !== false) {
                $valuesToMatch[][$prefix.'LDBCourseId'] = $categoryId;
            } else {
                $valuesToMatch[][$prefix.'categoryId'] = $categoryId;
                if ($courseLevel != '0') {
                    $valuesToMatch[][$prefix.'courseLevel'] = $courseLevel;
                }
            }
        } else {
            if ($courseLevel != '0') {
                $valuesToMatch[][$prefix.'courseLevel'] = $courseLevel;
            }
        }
        if ($countryId != 0) {
            $valuesToMatch[][$prefix.'countryId'] = $countryId;
        }
        $valuesToMatch[]['isStudyAbroad'] = $isStudyAbroad;
        if($extraData['studyAbroad']['bounce'] == 'yes'){
            $valuesToMatch[]['bounce'] = 1;    
        }
        
        foreach ($valuesToMatch as $value) {
            $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['term'] = $value;
        }

        if($pageName)
        {
            if ($pageName == 'rankingPage') {
                if ($pageType == 1) {
                    $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['term'][$prefix.'pageIdentifier'] = 'universityRankingPage';
                } else if ($pageType == 2) {
                    $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['term'][$prefix.'pageIdentifier'] = 'courseRankingPage';
                }else{
                    $elasticQuery['body']['query']['filtered']['filter']['bool']['should'][]['term'][$prefix.'pageIdentifier'] = 'courseRankingPage';
                    $elasticQuery['body']['query']['filtered']['filter']['bool']['should'][]['term'][$prefix.'pageIdentifier'] = 'universityRankingPage';
                    //$elasticQuery['body']['query']['filtered']['filter']['bool'][]['minimum_should_match'] = 1;
                }
            } else {
                $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['term'][$prefix.'pageIdentifier'] = $pageName;
            }
        }
        
        $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['range']['startTime']['gte'] = $startDate . 'T00:00:00';
        $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['range']['startTime']['lt'] =  $endDate . 'T00:00:00';

        $aggregation = array(
            'totalDuration' => array('sum' => array('field' => 'duration')),
            'pageviews' => array('sum' => array('field' => 'pageviews')),
            'bounce' => array('sum' => array('field' => 'bounce')),

        );
        
        $elasticQuery['body']['aggs'] = $aggregation;
        //_p(json_encode($elasticQuery));die;

        $result = $this->clientCon->search($elasticQuery);
        //_p($result);die;

        $totalSession = $result['hits']['total'];
        $totalPageviews = $result['aggregations']['pageviews']['value'];
        $totalDuration = $result['aggregations']['totalDuration']['value'];
        $totalBounceSession = $result['aggregations']['bounce']['value'];
        //_p($totalPageviews);_p($totalDuration);die;
        $bounceSessions = number_format((($totalBounceSession*100)/$totalSession), 2, '.', '');
        $pagesPerSessions = number_format((($totalPageviews)/$totalSession), 2, '.', '');
        $avgSessionDuration = number_format((($totalDuration)/(60*$totalSession)), 2, '.', '');

        $hourFormat = explode('.',number_format((($totalDuration)/($totalSession)), 2, '.', ''));
        $avgSessionDuration =date('i:s', mktime(0, 0, $hourFormat[0]));
        //---------------------------------
        //-----------------------------------
        if($pageName)
        {
            $elasticQuery = '';
            $elasticQuery['index'] = MISCommonLib::$TRAFFICDATA_SESSIONS;
            $elasticQuery['type']  = 'session';
            $elasticQuery['body']['size'] = 0;
            //$prefix = '';
            $prefix = 'exitPage.';

            $isStudyAbroad = 'yes';
            $categoryId    = isset($extraData['studyAbroad']['categoryId']) ? $extraData['studyAbroad']['categoryId'] : 0;
            $courseLevel   = isset($extraData['studyAbroad']['courseLevel']) ? $extraData['studyAbroad']['courseLevel'] : 0;
            $countryId     = isset($extraData['studyAbroad']['country']) ? $extraData['studyAbroad']['country'] : 0;
            $valuesToMatch = array();
            $startDate = $dateRange['startDate'];
            $endDate = $dateRange['endDate'];
            $ldbCourseIdsArray = $this->abroadCommonLib->getAbroadMainLDBCourses();
            foreach ($ldbCourseIdsArray as $key => $value) {
                $specialCategories[]= $value['SpecializationId'];
            }

            $pageType          = isset($extraData['studyAbroad']['pageType']) ? $extraData['studyAbroad']['pageType'] : 0;

            if ($categoryId != 0) {
                if (array_search($categoryId, $specialCategories) !== false) {
                    $valuesToMatch[][$prefix.'LDBCourseId'] = $categoryId;
                } else {
                    $valuesToMatch[][$prefix.'categoryId'] = $categoryId;
                    if ($courseLevel != '0') {
                        $valuesToMatch[][$prefix.'courseLevel'] = $courseLevel;
                    }
                }
            } else {
                if ($courseLevel != '0') {
                    $valuesToMatch[][$prefix.'courseLevel'] = $courseLevel;
                }
            }
            if ($countryId != 0) {
                $valuesToMatch[][$prefix.'countryId'] = $countryId;
            }
            $valuesToMatch[][$prefix.'isStudyAbroad'] = $isStudyAbroad;
            if($extraData['studyAbroad']['bounce'] == 'yes')
            {
                $valuesToMatch[]['bounce'] = 1;    
            }
            
            foreach ($valuesToMatch as $value) {
                $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['term'] = $value;
            }

            if($pageName)
            {
                if ($pageName == 'rankingPage') {
                    if ($pageType == 1) {
                        $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['term'][$prefix.'pageIdentifier'] = 'universityRankingPage';
                    } else if ($pageType == 2) {
                        $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['term'][$prefix.'pageIdentifier'] = 'courseRankingPage';
                    }else{
                        $elasticQuery['body']['query']['filtered']['filter']['bool']['should'][]['term'][$prefix.'pageIdentifier'] = 'courseRankingPage';
                        $elasticQuery['body']['query']['filtered']['filter']['bool']['should'][]['term'][$prefix.'pageIdentifier'] = 'universityRankingPage';
                        //$elasticQuery['body']['query']['filtered']['filter']['bool']['minimum_should_match'] = 1;
                    }
                } else {
                    $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['term'][$prefix.'pageIdentifier'] = $pageName;
                }
            }
            
            $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['range']['startTime']['gte'] = $startDate . 'T00:00:00';
            $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['range']['startTime']['lt'] =  $endDate . 'T00:00:00';

            //_p(json_encode($elasticQuery));die;

            $result = $this->clientCon->search($elasticQuery);
            $exitSession = $result['hits']['total'];
        }    
        // ========================page views====================
        {

                $isStudyAbroad = 'yes';
                $elasticQuery = '';                    

                $elasticQuery['index'] = MISCommonLib::$TRAFFICDATA_PAGEVIEWS;
                $elasticQuery['type']  = 'pageview';
                $startDate             = $dateRange['startDate'];
                $endDate               = $dateRange['endDate'];

                $elasticQuery['body']['size'] = 0;                   
                    $categoryId    = isset($extraData['studyAbroad']['categoryId']) ? $extraData['studyAbroad']['categoryId'] : 0;
                    $courseLevel   = isset($extraData['studyAbroad']['courseLevel']) ? $extraData['studyAbroad']['courseLevel'] : 0;
                    $countryId     = isset($extraData['studyAbroad']['country']) ? $extraData['studyAbroad']['country'] : 0;
                    $valuesToMatch = array();
                    $ldbCourseIdsArray = $this->abroadCommonLib->getAbroadMainLDBCourses();
                    foreach ($ldbCourseIdsArray as $key => $value) {
                        $specialCategories[]= $value['SpecializationId'];
                    }

                    $pageType          = isset($extraData['studyAbroad']['pageType']) ? $extraData['studyAbroad']['pageType'] : 0;

                    if ($categoryId != 0) {
                        if (array_search($categoryId, $specialCategories) !== false) {
                            $valuesToMatch[]['LDBCourseId'] = $categoryId;
                        } else {
                            $valuesToMatch[]['categoryId'] = $categoryId;
                            if ($courseLevel != '0') {
                                $valuesToMatch[]['courseLevel'] = $courseLevel;
                            }
                        }
                    }else{
                        if ($courseLevel != '0' ) {
                            $valuesToMatch[]['courseLevel'] = $courseLevel;
                        }
                    }
                    if ($countryId != 0) {
                        $valuesToMatch[]['countryId'] = $countryId;
                    }
                    $valuesToMatch[]['isStudyAbroad'] = 'yes';

                    foreach ($valuesToMatch as $value) {
                        $elasticQuery['body']['query']['filtered']['query']['bool']['must'][]['match'] = $value;
                    }

                    if($pageName){
                        if ($pageName == 'rankingPage') {
                            if ($pageType == 1) {
                                $elasticQuery['body']['query']['filtered']['query']['bool']['must'][]['match']['pageIdentifier'] = 'universityRankingPage';
                            } else if ($pageType == 2) {
                                $elasticQuery['body']['query']['filtered']['query']['bool']['must'][]['match']['pageIdentifier'] = 'courseRankingPage';
                            }else{
                                $elasticQuery['body']['query']['filtered']['query']['bool']['should'][]['match']['pageIdentifier'] = 'courseRankingPage';
                                $elasticQuery['body']['query']['filtered']['query']['bool']['should'][]['match']['pageIdentifier'] = 'universityRankingPage';
                                $elasticQuery['body']['query']['filtered']['query']['bool']['minimum_should_match'] = 1;
                            }
                        } else {
                            $elasticQuery['body']['query']['filtered']['query']['bool']['must'][]['match']['pageIdentifier'] = $pageName;
                        }    
                    }
                    
                    $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['range']['visitTime']['gte'] = $startDate . 'T00:00:00';
                    $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['range']['visitTime']['lt'] =  $endDate . 'T00:00:00';   
                        
                    //_p(json_encode($elasticQuery));die;
                    $result = $this->clientCon->search($elasticQuery);
                    $totalPageviews = $result['hits']['total'];
        }
        //========================================================
        if($pageName)
        {
            $exitRate = number_format((($exitSession*100)/$totalPageviews), 2, '.', '');
            $topTiles = array($totalPageviews,$pagesPerSessions,$avgSessionDuration,$exitRate,$bounceSessions);
        }
        else
        {
            $topTiles = array($totalPageviews,$pagesPerSessions,$avgSessionDuration,$bounceSessions);
        }

        //_p($totalSession);_p($totalPageviews);_p($totalDuration);_p($totalBounceSession);_p($exitSession);_p($totalPageviews);_p($exitRate);die; 
        return $topTiles;
        //-----------------------------------------------
    }

    function prepareDonutChartDataForEngForPageViews($params,$filter,$colorCodes,$flag =0){

        $pivotFilter['pivot']['terms']['field']= $filter;
        $pivotFilter['pivot']['terms']['size']= 0;
        $params['body']['aggs'] = $pivotFilter;
        //_p(json_encode($params));die;
        $search = $this->clientCon->search($params);
        $actualCount = $search['hits']['total'];
        $result = $search['aggregations']['pivot']['buckets'];
        $count = 0;
        foreach ($result as $key => $value) {

            if($filter == 'pageIdentifier'){
                $page = $this->MISCommonLib->getPageName($value['key']);
                $resultArray[$page] = $value['doc_count'];
            }else{
                $sourceApplicationArray = array('yes','no');
                if(in_array($value['key'], $sourceApplicationArray)){
                    $temp = ($value['key']=='no')?"Desktop":"Mobile";
                }else{
                    $temp =$value['key'];
                }
                $resultArray[$temp] = $value['doc_count'];
            }
               
            $count += $value['doc_count'];
        }
        if($actualCount > $count){
            $resultArray['Other'] = $actualCount - $count;
        }
        //_p($resultArray);die;
        if($flag == 1){
            return array($resultArray,$actualCount);
        }else{
            //return $this->prepareDataForDonutChart($resultArray,$colorCodes,$actualCount);    
        }          
    }

    function prepareDonutChartDataForEng($params,$filter,$colorCodes,$flag =0,$engFilter){
        if($engFilter == 'pgpersess'){
            $temp = 'pageviews';
        }else if($engFilter == 'avgsessdur'){
            $temp = 'duration';
        }else if($engFilter == 'bounce'){
            $temp = 'bounce';
        }

        $pageViewsAggs['pageViewsAggs']['sum']['field'] = $temp;
        $pivotFilter['pivot']['terms']['field']= $filter;
        $pivotFilter['pivot']['terms']['size']= 0;
        $pivotFilter['pivot']['aggs'] = $pageViewsAggs;
        $params['body']['aggs'] = $pivotFilter;
        //_p(json_encode($params));die;
        $search = $this->clientCon->search($params);
        $actualCount = $search['hits']['total'];
        $result = $search['aggregations']['pivot']['buckets'];
        $count = 0;
        foreach ($result as $key => $value) {
            if($engFilter == 'pgpersess'){
                if($filter == 'landingPageDoc.pageIdentifier'){
                    $page = $this->MISCommonLib->getPageName($value['key']);
                    $resultArray[$page] = number_format((($value['pageViewsAggs']['value'])/$value['doc_count']), 2, '.', '');
                }else{
                    $sourceApplicationArray = array('yes','no');
                    if(in_array($value['key'], $sourceApplicationArray)){
                        $temp = ($value['key']=='no')?"Desktop":"Mobile";
                    }else{
                        $temp =$value['key'];
                    }
                    $resultArray[$temp] = number_format((($value['pageViewsAggs']['value'])/$value['doc_count']), 2, '.', '');    
                }
            }else if($engFilter == 'avgsessdur'){
                if($filter == 'landingPageDoc.pageIdentifier'){
                    $page = $this->MISCommonLib->getPageName($value['key']);
                    $resultArray[$page] = number_format((($value['pageViewsAggs']['value'])/($value['doc_count']*60)), 2, '.', '');
                }else{
                    $sourceApplicationArray = array('yes','no');
                    if(in_array($value['key'], $sourceApplicationArray)){
                        $temp = ($value['key']=='no')?"Desktop":"Mobile";
                    }else{
                        $temp =$value['key'];
                    }
                    $resultArray[$temp] = number_format((($value['pageViewsAggs']['value'])/($value['doc_count']*60)), 2, '.', '');    
                }
            }else if($engFilter == 'bounce'){
                if($filter == 'landingPageDoc.pageIdentifier'){
                    $page = $this->MISCommonLib->getPageName($value['key']);
                    $resultArray[$page] = number_format((($value['pageViewsAggs']['value']*100)/($value['doc_count'])), 2, '.', '');
                }else{
                    $sourceApplicationArray = array('yes','no');
                    if(in_array($value['key'], $sourceApplicationArray)){
                        $temp = ($value['key']=='no')?"Desktop":"Mobile";
                    }else{
                        $temp =$value['key'];
                    }
                    $resultArray[$temp] = number_format((($value['pageViewsAggs']['value']*100)/($value['doc_count'])), 2, '.', '');    
                }
            }       
            //$count += $value['doc_count'];
        }
        /*if($actualCount > $count){
            $resultArray['Other'] = $actualCount - $count;
        }*/
        //_p($resultArray);die;
        if($flag == 1){
            return array($resultArray,$actualCount);
        }else{
            //return $this->prepareDataForDonutChart($resultArray,$colorCodes,$actualCount);    
        }  
    }

    function getEngagementData($dateRange,$extraData,$pageName,$isDataTable=1,$isComparision=0,$colorCodes)
    {
        $extraData['studyAbroad']['categoryId'] = intval($extraData['studyAbroad']['categoryId']);
            if(is_array($extraData['country']) ){
            if(sizeof( $extraData['country']) == 1){
                    $extraData['country'][0] = intval($extraData['country'][0]);
            }else{
                foreach ($extraData['country'] as $key => $value) {
                    $extraData['country'][$key] = intval($value);
                }
            }    
            }else{
                if($extraData['country'] !=0 ){
                    $extraData['country'] = intval($extraData['country']);
                }
            }
        $tempDate = $dateRange['endDate'];
        $dateRange['endDate'] = date("Y-m-d",strtotime($dateRange['endDate']."+1 days"));

        if($pageName){
            $pageName = $this->getPageArrayForTraffic($pageName);
        }
        $topTiles = $this->_getTopTilesDataForEng($dateRange,$extraData,$pageName);
        $this->engagementLib = $this->CI->load->library('trackingMIS/engagementLib');
        $engagementData = $this->engagementLib->getPageMetricData($dateRange, $pageName, $extraData);
        //_p($engagementData['pageViews']['query']);die;
        $dateRange['endDate'] =$tempDate;
        switch ($extraData['studyAbroad']['engagementType']) {
                    case 'exit':

                        $exitQuery = $engagementData['exit']['exitSession']['query'];
                        $pageViewsQuery = $engagementData['exit']['pageviews']['query'];
                        unset($pageViewsQuery['body']['aggs']);
                        //_p(json_encode($pageViewsQuery));die;

                        // For Exit Page Data
                        $extraData['studyAbroad']['view'] = $this->_getView($extraData['studyAbroad']['view']);
                        $dateWiseFilter['dateWiseCount']['date_histogram']['interval'] =$extraData['studyAbroad']['view'];
                        $dateWiseFilter['dateWiseCount']['date_histogram']['field'] = 'startTime';
                        $exitQuery['body']['aggs'] = $dateWiseFilter;
                        //_p(json_encode($exitQuery));die;
                        $search = $this->clientCon->search($exitQuery);
                        $actualCount = $search['hits']['total'];
                        $dateWiseData = $search['aggregations']['dateWiseCount']['buckets'];
                        $lineArrayForExit = array();
                        foreach ($dateWiseData as $key => $value) {
                            $lineArrayForExit[date("Y-m-d", strtotime($value['key_as_string']))] = $value['doc_count'];
                        }
                        //_p($lineArrayForExit);die;

                        // For Page Views Data
                        $extraData['studyAbroad']['view'] = $this->_getView($extraData['studyAbroad']['view']);
                        $dateWiseFilter['dateWiseCount']['date_histogram']['interval'] =$extraData['studyAbroad']['view'];
                        $dateWiseFilter['dateWiseCount']['date_histogram']['field'] = 'visitTime';
                        $pageViewsQuery['body']['aggs'] = $dateWiseFilter;
                        //_p(json_encode($pageViewsQuery));die;
                        $search = $this->clientCon->search($pageViewsQuery);
                        $actualCount = $search['hits']['total'];
                        $dateWiseData = $search['aggregations']['dateWiseCount']['buckets'];
                        $lineArrayForPageViews = array();
                        foreach ($dateWiseData as $key => $value) {
                            $lineArrayForPageViews[date("Y-m-d", strtotime($value['key_as_string']))] = $value['doc_count'];
                        }
                        //_p($lineArrayForPageViews);die;
                        $lineArray = array();
                        foreach ($lineArrayForExit as $key => $value) {
                            $lineArray[$key] = number_format((($value*100)/$lineArrayForPageViews[$key]), 2, '.', '');
                        }
                        //_p($lineArray);die;
                        $lineChartData= $this->MISCommonLib->prepareDataForLineChartForShikshaTraffic($lineArray,$dateRange,$extraData['studyAbroad']['view'],$actualCount,1);
                        $pageData['dataForDifferentCharts']['lineChartData'] = $lineChartData;
                        //_p($lineChartData);die;

                        // for Source Application Data
                        unset($exitQuery['body']['aggs']);
                        unset($pageViewsQuery['body']['aggs']);
                            // For Exit Page 
                            $pivotFilter = array();
                            $pivotFilter['pivot']['terms']['field']= 'exitPage.isMobile';
                            $pivotFilter['pivot']['terms']['size']= 0;
                            $exitQuery['body']['aggs'] = $pivotFilter;
                            //_p(json_encode($exitQuery));die;
                            $search = $this->clientCon->search($exitQuery);
                            $actualCount = $search['hits']['total'];
                            $devicewiseData = $search['aggregations']['pivot']['buckets'];
                            $sourceApplicationForExit = array();
                            foreach ($devicewiseData as $key => $value) {
                                $sourceApplicationForExit[($value['key']=='no')?'Desktop':'Mobile'] = $value['doc_count'];
                            }
                            //_p($sourceApplication);die;

                            // For Page Views
                            $pivotFilter = array();
                            $pivotFilter['pivot']['terms']['field']= 'isMobile';
                            $pivotFilter['pivot']['terms']['size']= 0;
                            $pageViewsQuery['body']['aggs'] = $pivotFilter;
                            //_p(json_encode($pageViewsQuery));die;
                            $search = $this->clientCon->search($pageViewsQuery);
                            $actualCount = $search['hits']['total'];
                            $devicewiseData = $search['aggregations']['pivot']['buckets'];
                            $sourceApplicationForPageViews = array();
                            foreach ($devicewiseData as $key => $value) {
                                $sourceApplicationForPageViews[($value['key']=='no')?'Desktop':'Mobile'] = $value['doc_count'];
                            }
                            //_p($sourceApplicationForPageViews);die;
                            $sourceApplication = array();
                            foreach ($sourceApplicationForExit as $key => $value) {
                                $sourceApplication[$key] = number_format((($value*100)/$sourceApplicationForPageViews[$key]), 2, '.', '');
                            }
                            //_p($sourceApplication);die;
    
                        $donutChartDataBySourceApplication = $this->_prepareDataForBargraphForpgps($sourceApplication,$extraData['studyAbroad']);
                        //_p($donutChartDataBySourceApplication);die;
                        $pageData['dataForDifferentCharts']['barGraphData']['deviceWise'] = $donutChartDataBySourceApplication;

                        //===============================================================================
                        // For User Wise
                            unset($exitQuery['body']['aggs']);
                            unset($pageViewsQuery['body']['aggs']);
                            $exitQuery['body']['aggs']['userWise']['filter']['term']['exitPage.userId'] =0;
                            //_p(json_encode($exitQuery));die;
                            $search = $this->clientCon->search($exitQuery);
                            $totalUsers = $search['hits']['total'];
                            $totalNonLoggUsersForExit = $search['aggregations']['userWise']['doc_count'];
                            $totalLoggInUsersForExit = $totalUsers - $totalNonLoggUsersForExit;
                            //_p($totalUsers);_p($totalNonLoggUsers);_p($totalLoggInUsers);die;

                            // For pageviews
                            $pageViewsQuery['body']['aggs']['userWise']['filter']['term']['userId'] =0;
                            //_p(json_encode($pageViewsQuery));die;
                            $search = $this->clientCon->search($pageViewsQuery);
                            $totalUsers = $search['hits']['total'];
                            $totalNonLoggUsersForPageWiews = $search['aggregations']['userWise']['doc_count'];
                            $totalLoggInUsersPageWiews = $totalUsers - $totalNonLoggUsersForPageWiews;

                            $userwise = array();
                            $userwise['Loggedin'] = number_format((($totalLoggInUsersForExit*100)/$totalLoggInUsersPageWiews), 2, '.', '');
                            $userwise['Non Loggedin']  = number_format((($totalNonLoggUsersForExit*100)/$totalNonLoggUsersForPageWiews), 2, '.', '');
                            //_p($userwise);die;
                            $userwiseBR = $this->_prepareDataForBargraphForpgps($userwise,$extraData['studyAbroad']);
                            $pageData['dataForDifferentCharts']['barGraphData']['userWise'] = $userwiseBR;
                            unset($params['body']['aggs']['users']);
                        //===============================================================================


                        /*    
                        $exitData = $this->_prepareDataForDifferentChartsForBounce($engagementData['exit']['exitSession']['result'],$pageName,$dateRange,$extraData['studyAbroad'],$isComparision,$colorCodes);
                        $totalData = $this->_prepareDataForDifferentChartsForBounce($engagementData['exit']['pageviews']['result'],$pageName,$dateRange,$extraData['studyAbroad'],$isComparision,$colorCodes);
                        //_p($totalData);_p($bounceData);die;
                        $pageData = $this->_getBounceData($totalData,$exitData,$extraData);*/
                        //$pageData['topTiles'] = array(1,0,$pageData['total'],0,0);
                        //-------------------------------------------------------------------------------------------------
                        if(!$isComparision){
                            $trafficSourceData = $this->_getTrafficSourceDataForExit($engagementData['exit']['exitSession']['query'],$engagementData['exit']['pageviews']['query']);
                            //_p($trafficSourceData);die;
                            $trafficSourceBarGraph =$this->_prepareDataForBargraphForpgps($trafficSourceData['donutChart']['data'],$extraData['studyAbroad']);

                            $pageData['dataForDifferentCharts']['barGraphData']['trafficSource'] = $trafficSourceBarGraph;
                            unset($trafficSourceData['donutChart']);
                            $pageData['dataForDifferentCharts']['barGraphDataForExit'] = $trafficSourceData['barGraphData'];
                            //_p($data['dataForDifferentCharts']['barGraphDataForBounce']);die;
                            $pageData['dataForDifferentCharts']['trafficSourceFilterData'] = $trafficSourceData['lis'];
                            $pageData['dataForDifferentCharts']['defaultView'] = $trafficSourceData['defaultView'];
                        }else{
                            //_p(json_encode($engagementData['exit']['pageviews']['query']));die;
                            $params = $engagementData['exit']['exitSession']['query'];
                            $trafficSourceAggeration['trafficSource']['terms']['field']= 'exitPage.source';
                            $trafficSourceAggeration['trafficSource']['terms']['size']= 0;
                            $params['body']['aggs'] = $trafficSourceAggeration;
                            //_p(json_encode($params));die;
                            $trafficSourceData = $this->clientCon->search($params);
                            $trafficSourceData = $trafficSourceData['aggregations']['trafficSource']['buckets'];
                            foreach ($trafficSourceData as $key => $value) {                
                                $trafficSourceArray[$value['key']] = $value['doc_count'];
                            }

                            $params='';
                            $params = $engagementData['exit']['pageviews']['query'];
                            $trafficSourceAggeration['trafficSource']['terms']['field']= 'source';
                            $trafficSourceAggeration['trafficSource']['terms']['size']= 0;
                            $params['body']['aggs'] = $trafficSourceAggeration;
                            //_p(json_encode($params));die;
                            $trafficSourceData = $this->clientCon->search($params);
                            $trafficSourceData = $trafficSourceData['aggregations']['trafficSource']['buckets'];
                            foreach ($trafficSourceData as $key => $value) {                
                                $trafficSourceArrayForpv[$value['key']] = $value['doc_count'];
                            }

                            foreach ($trafficSourceArray as $key => $value) {                
                                $trafficSourceArrayForExitRate[$key] = number_format((($value*100)/$trafficSourceArrayForpv[$key]), 2, '.', '');
                            }
                            
                            //_p($pageData['dataForDifferentCharts']['donutChartData']);die;
                            $pageData['dataForDifferentCharts']['barGraphData']['trafficSource'] = $this->_prepareDataForBargraphForpgps($trafficSourceArrayForExitRate,$extraData['studyAbroad']); 
                        }
                        //-------------------------------------------------------------------------------------------------
                        $pageData['topTiles'] = $topTiles;
                        $pageData['engagementType'] = 'exit';

                        return $pageData;
                        break;
                    
                    case 'pageviews':
                        $engagementData['type'] ='pageviews'; 
                        //_p(json_encode($engagementData['pageViews']['query']));die;
                        $params = array();
                        $params = $engagementData['pageViews']['query'];
                        
                        unset($params['body']['aggs']);
                        //_p(json_encode($params));die;
                        // We have query, now we apply diff aggs
                        // 1. For Line Chart
                        $extraData['studyAbroad']['view'] = $this->_getView($extraData['studyAbroad']['view']);
                        $dateWiseFilter['dateWiseCount']['date_histogram']['interval'] =$extraData['studyAbroad']['view'];
                        $dateWiseFilter['dateWiseCount']['date_histogram']['field'] = 'visitTime';
                        $params['body']['aggs'] = $dateWiseFilter;
                        //_p(json_encode($params));die;
                        $search = $this->clientCon->search($params);
                        $actualCount = $search['hits']['total'];
                        $dateWiseData = $search['aggregations']['dateWiseCount']['buckets'];
                        foreach ($dateWiseData as $key => $value) {
                            $lineArray[date("Y-m-d", strtotime($value['key_as_string']))] = $value['doc_count'];
                        }
                        //_p($lineArray);die;
                        $lineChartData= $this->MISCommonLib->prepareDataForLineChartForShikshaTraffic($lineArray,$dateRange,$extraData['studyAbroad']['view'],$actualCount,1);
                        $pageData['dataForDifferentCharts']['lineChartData'] = $lineChartData;
                        //_p($lineChartData);die;

                        // for Source Application Data
                        unset($params['body']['aggs']);
                        $donutChartDataBySourceApplication = $this->prepareDonutChartDataForEngForPageViews($params,'isMobile',$colorCodes,1);
                        $donutChartDataBySourceApplication = $this->prepareDataForDonutChart($donutChartDataBySourceApplication[0],$colorCodes,$donutChartDataBySourceApplication[1]);
                        $data['dataForDifferentCharts']['donutChartData']['sourceApplication'] = $donutChartDataBySourceApplication;
                        //_p($donutChartDataBySourceApplication);die;

                        // for logged in and non logged in users
                        $params['body']['aggs']['users']['filter']['term']['userId'] = 0;
                        //_p(json_encode($params));die;
                        $search = $this->clientCon->search($params);
                        $totalUsers = $search['hits']['total'];
                        $nonLoggedInUsers = $search['aggregations']['users']['doc_count'];
                        
                        $userwise = array();
                        $userwise['Loggedin'] = $totalUsers - $nonLoggedInUsers;
                        $userwise['Non Loggedin']  = $nonLoggedInUsers;
                        //_p($userwise);die;
                        $userwiseBR = $this->prepareDataForDonutChart($userwise,$colorCodes,$totalUsers);
                        $data['dataForDifferentCharts']['donutChartData']['userwise'] = $userwiseBR;
                        unset($params['body']['aggs']);

                        if(!$pageName){
                            if($isComparision == 1){
                                // For Page wise Data
                                $donutChartDataByPageWise = $this->prepareDonutChartDataForEngForPageViews($params,'pageIdentifier',$colorCodes,1,'bounce');
                                $donutChartDataByPageWise = $this->prepareDataForDonutChart($donutChartDataByPageWise[0],$colorCodes,$donutChartDataByPageWise[1]);
                                $data['dataForDifferentCharts']['donutChartData']['pageWise'] = $donutChartDataByPageWise;
                            }else{
                                // For Data Table
                                unset($params['body']['aggs']);

                                $siteSourceAggs['siteSource']['terms']['field'] =  "isMobile";
                                $siteSourceAggs['siteSource']['terms']['size'] =  0;

                                $sourceAggs['source']['terms']['field'] =  "source";
                                $sourceAggs['source']['terms']['size'] =  0;
                                $sourceAggs['source']['aggs'] = $siteSourceAggs;

                                $pageWiseAggs['pageWise']['terms']['field'] =  "pageIdentifier";
                                $pageWiseAggs['pageWise']['terms']['size'] =  0;
                                $pageWiseAggs['pageWise']['aggs'] = $sourceAggs;
                                $params['body']['aggs'] = $pageWiseAggs;
                                //_p(json_encode($params));die;
                                $search = $this->clientCon->search($params);
                                $result = $search['aggregations']['pageWise']['buckets'];
                                //_p($result);die;
                                $dataArray = array();
                                foreach ($result as $key => $pageArray){  
                                    $source = $pageArray['source']['buckets'];
                                    foreach ($source as $key => $sourceArray) {  
                                        $siteSource = $sourceArray['siteSource']['buckets'];
                                        foreach ($siteSource as $key => $siteSourceArray) {
                                            $page = $pageArray['key']; 
                                            $page = $this->MISCommonLib->getPageName($page);
                                            $dataArray[$page][$sourceArray['key']][($siteSourceArray['key']=='yes')?'Mobile':'Desktop'] = $siteSourceArray['doc_count'];
                                        }
                                    }
                                }
                                //_p($dataArray);die;
                                $dataTable = $this->_prepareDataForDataTableForpgpsForData($dataArray,$extraData);
                                //_p($dataTable);die;
                                $pageData['dataForDifferentCharts']['dataForDataTable'] = $dataTable;
                            }
                        }

                        //===============================
                        //==================================
                        //$pageData = $this->_prepareDataForDifferentChartsForEngagement($engagementData,$pageName,$dateRange,$extraData['studyAbroad'],$isComparision,$colorCodes);
                        //_p($isComparision);die;
                        if(!$isComparision){
                            // for traffic source data
                            $trafficSourceData = $this->_getTrafficSourceData($engagementData['pageViews']['query']);
                            $trafficSourceDonutChart = $this->prepareDataForDonutChart($trafficSourceData['donutChart']['data'],$colorCodes,$trafficSourceData['donutChart']['count']);    
                            $data['dataForDifferentCharts']['donutChartData']['trafficSource'] = $trafficSourceDonutChart;
                            unset($trafficSourceData['donutChart']);
                            $pageData['dataForDifferentCharts']['barGraphDataForPageViews'] = $trafficSourceData['barGraphData'];
                            //_p($trafficSourceData);die;
                            $pageData['dataForDifferentCharts']['trafficSourceFilterData'] = $trafficSourceData['lis'];
                            $pageData['dataForDifferentCharts']['defaultView'] = $trafficSourceData['defaultView'];
                        }else{
                            $params = $engagementData['pageViews']['query'];
                            $trafficSourceAggeration['trafficSource']['terms']['field']= 'source';
                            $trafficSourceAggeration['trafficSource']['terms']['size']= 0;
                            $params['body']['aggs'] = $trafficSourceAggeration;
                            $trafficSourceData = $this->clientCon->search($params);
                            $trafficSourceData = $trafficSourceData['aggregations']['trafficSource']['buckets'];
                            foreach ($trafficSourceData as $key => $value) {
                                $trafficSourceArray[$value['key']] = $value['doc_count'];
                                $totalTrafficSourceCouont+=$value['doc_count'];
                            }
                            
                            $trafficSourceDonutChart = $this->prepareDataForDonutChart($trafficSourceArray,$colorCodes,$totalTrafficSourceCouont);
                       
                            
                            $data['dataForDifferentCharts']['donutChartData']['trafficSource'] = $trafficSourceDonutChart;
                        }

                        if($pageName){
                            if($isComparision == 0){
                                $pageData['dataForDifferentCharts']['donutChartData'] = array($data['dataForDifferentCharts']['donutChartData']['sourceApplication'],$data['dataForDifferentCharts']['donutChartData']['trafficSource'],$data['dataForDifferentCharts']['donutChartData']['userwise']);
                            }else{
                                $pageData['dataForDifferentCharts']['donutChartData'] = array($data['dataForDifferentCharts']['donutChartData']['sourceApplication'],$data['dataForDifferentCharts']['donutChartData']['trafficSource'],$data['dataForDifferentCharts']['donutChartData']['userwise']);
                            }
                        }else{
                            if($isComparision == 0){
                                $pageData['dataForDifferentCharts']['donutChartData'] = array($data['dataForDifferentCharts']['donutChartData']['sourceApplication'],$data['dataForDifferentCharts']['donutChartData']['trafficSource'],$data['dataForDifferentCharts']['donutChartData']['userwise']);
                            }else{
                                $pageData['dataForDifferentCharts']['donutChartData'] = array($data['dataForDifferentCharts']['donutChartData']['sourceApplication'],$data['dataForDifferentCharts']['donutChartData']['trafficSource'],$data['dataForDifferentCharts']['donutChartData']['userwise'],$data['dataForDifferentCharts']['donutChartData']['pageWise']);
                            }
                        }

                        $pageData['topTiles'] = $topTiles;
                        return $pageData;
                        break;

                    case 'bounce':

                        $params = array();
                        $params = $engagementData['bounce']['totalSession']['query'];
                        unset($params['body']['aggs']);
                        //_p(json_encode($params));die;

                        // We have query, now we apply diff aggs
                        // 1. For Line Chart
                        $pageViewsAggsFilter['pageViewsAggs']['sum']['field'] = 'bounce';
                        $extraData['studyAbroad']['view'] = $this->_getView($extraData['studyAbroad']['view']);
                        $dateWiseFilter['dateWiseCount']['date_histogram']['interval'] =$extraData['studyAbroad']['view'];
                        $dateWiseFilter['dateWiseCount']['aggs'] = $pageViewsAggsFilter;
                        $dateWiseFilter['dateWiseCount']['date_histogram']['field'] = 'startTime';
                        $params['body']['aggs'] = $dateWiseFilter;
                        //_p(json_encode($params));die;
                        $search = $this->clientCon->search($params);
                        $actualCount = $search['hits']['total'];
                        $dateWiseData = $search['aggregations']['dateWiseCount']['buckets'];
                        foreach ($dateWiseData as $key => $value) {
                            $lineArray[date("Y-m-d", strtotime($value['key_as_string']))] = number_format((($value['pageViewsAggs']['value']*100)/$value['doc_count']), 2, '.', '');
                        }
                        $lineChartData= $this->MISCommonLib->prepareDataForLineChartForShikshaTraffic($lineArray,$dateRange,$extraData['studyAbroad']['view'],$actualCount,1);
                        $data['dataForDifferentCharts']['lineChartData'] = $lineChartData;
                        //_p($lineChartData);die;

                        // for Source Application Data
                        $donutChartDataBySourceApplication = $this->prepareDonutChartDataForEng($params,'isMobile',$colorCodes,1,'bounce');
                        $donutChartDataBySourceApplication = $this->_prepareDataForBargraphForpgps($donutChartDataBySourceApplication[0],$extraData['studyAbroad']);
                        $data['dataForDifferentCharts']['barGraphData']['deviceWise'] = $donutChartDataBySourceApplication;
                        //_p($donutChartDataBySourceApplication);die;

                        // for logged in and non logged in users
                        $pivotFilter['userPageViewCount']['sum']['field'] = 'bounce';
                        $params['body']['aggs'] = $pivotFilter;
                        $params['body']['aggs']['users']['filter']['term']['userId'] =0;
                        $params['body']['aggs']['users']['aggs']['pageViewsCount']['sum']['field'] = 'bounce';
                        $search = $this->clientCon->search($params);
                        $totalPageViews = $search['aggregations']['userPageViewCount']['value'];
                        $totalSessions = $search['hits']['total'];

                        $totalPageViewsForNonLogg = $search['aggregations']['users']['pageViewsCount']['value'];
                        $totalSessionsForNonLogg = $search['aggregations']['users']['doc_count'];

                        $totalPageViewsForLogg = $totalPageViews - $totalPageViewsForNonLogg;
                        $totalSessionsForLogg = $totalSessions - $totalSessionsForNonLogg;

                        $userwise = array();
                        $userwise['Loggedin'] = number_format((($totalPageViewsForLogg*100)/$totalSessionsForLogg), 2, '.', '');
                        $userwise['Non Loggedin']  = number_format((($totalPageViewsForNonLogg*100)/$totalSessionsForNonLogg), 2, '.', '');
                        //_p($userwise);die;
                        $userwiseBR = $this->_prepareDataForBargraphForpgps($userwise,$extraData['studyAbroad']);
                        $data['dataForDifferentCharts']['barGraphData']['userWise'] = $userwiseBR;
                        unset($params['body']['aggs']['users']);

                        if(!$pageName){
                            if($isComparision == 1){
                                // For Page wise Data
                                $donutChartDataByPageWise = $this->prepareDonutChartDataForEng($params,'landingPageDoc.pageIdentifier',$colorCodes,1,'bounce');
                                $donutChartDataByPageWise = $this->_prepareDataForBargraphForpgps($donutChartDataByPageWise[0],$extraData);
                                $data['dataForDifferentCharts']['barGraphData']['pageWise'] = $donutChartDataByPageWise;
                            }else{
                                // For Data Table
                                unset($params['body']['aggs']['checkColoum']['aggs']);

                                $pageViewsSum['pageViewAggs']['sum']['field'] = 'bounce';

                                $siteSourceAggs['siteSource']['terms']['field'] =  "isMobile";
                                $siteSourceAggs['siteSource']['terms']['size'] =  0;
                                $siteSourceAggs['siteSource']['aggs'] = $pageViewsSum;

                                $sourceAggs['source']['terms']['field'] =  "source";
                                $sourceAggs['source']['terms']['size'] =  0;
                                $sourceAggs['source']['aggs'] = $siteSourceAggs;

                                $pageWiseAggs['pageWise']['terms']['field'] =  "landingPageDoc.pageIdentifier";
                                $pageWiseAggs['pageWise']['terms']['size'] =  0;
                                $pageWiseAggs['pageWise']['aggs'] = $sourceAggs;

                                $params['body']['aggs'] = $pageWiseAggs;

                                //_p(json_encode($params));die;
                                $search = $this->clientCon->search($params);
                                $result = $search['aggregations']['pageWise']['buckets'];
                                //_p($result);die;
                                $dataArray = array();
                                foreach ($result as $key => $pageArray){  
                                    $source = $pageArray['source']['buckets'];
                                    foreach ($source as $key => $sourceArray) {  
                                        $siteSource = $sourceArray['siteSource']['buckets'];
                                        foreach ($siteSource as $key => $siteSourceArray) {
                                            //_p($siteSourceArray['pageViewAggs']['value']);die;
                                            $page = $pageArray['key']; 
                                            $page = $this->MISCommonLib->getPageName($page);
                                            $dataArray[$page][$sourceArray['key']][($siteSourceArray['key']=='yes')?'Mobile':'Desktop'] = number_format((($siteSourceArray['pageViewAggs']['value']*100)/$siteSourceArray['doc_count']), 2, '.', '');
                                        }
                                    }
                                }
                                //_p($dataArray);die;
                                $dataTable = $this->_prepareDataForDataTableForpgpsForData($dataArray,$extraData);
                                $data['dataForDifferentCharts']['dataForDataTable'] = $dataTable;
                            }
                        }

                        //_p(json_encode($engagementData['bounce']['bounceSession']['query']));die;
                        //$totalData = $this->_prepareDataForDifferentChartsForBounce($engagementData['bounce']['totalSession']['result'],$pageName,$dateRange,$extraData['studyAbroad'],$isComparision,$colorCodes);

                        //$bounceData = $this->_prepareDataForDifferentChartsForBounce($engagementData['bounce']['bounceSession']['result'],$pageName,$dateRange,$extraData['studyAbroad'],$isComparision,$colorCodes);
                        //$pageData = $this->_getBounceData($totalData,$bounceData,$extraData,$isComparision);
                        //--------------------------------------------------------------------------------------------------
                        if(!$isComparision){
                            //_p(json_encode($engagementData['bounce']['totalSession']['query']));die;
                            $trafficSourceData = $this->_getTrafficSourceDataForBounce($engagementData['bounce']['totalSession']['query']);

                            $trafficSourceBarGraph =$this->_prepareDataForBargraphForpgps($trafficSourceData['donutChart']['data'],$extraData['studyAbroad']);

                            $data['dataForDifferentCharts']['barGraphData']['trafficSource'] = $trafficSourceBarGraph;
                            unset($trafficSourceData['donutChart']);
                            $data['dataForDifferentCharts']['barGraphDataForBounce'] = $trafficSourceData['barGraphData'];
                            //_p($data['dataForDifferentCharts']['barGraphDataForBounce']);die;
                            $data['dataForDifferentCharts']['trafficSourceFilterData'] = $trafficSourceData['lis'];
                            $data['dataForDifferentCharts']['defaultView'] = $trafficSourceData['defaultView'];
                        }else{
                            $params = array();
                            $params = $engagementData['bounce']['totalSession']['query'];
                            unset($params['body']['aggs']);

                            $trafficSourceAggeration['trafficSource']['terms']['field']= 'source';
                            $trafficSourceAggeration['trafficSource']['terms']['size']= 0;
                            $trafficSourceAggeration['trafficSource']['aggs']['pageViewsAggs']['sum']['field']="bounce";
                            $params['body']['aggs'] = $trafficSourceAggeration;
                            //_p(json_encode($params));die;
                            $trafficSourceData = $this->clientCon->search($params);
                            $trafficSourceData = $trafficSourceData['aggregations']['trafficSource']['buckets'];
                            foreach ($trafficSourceData as $key => $value) {                
                                $trafficSourceArray[$value['key']] = number_format((($value['pageViewsAggs']['value']*100)/$value['doc_count']), 2, '.', '');
                            }
                            //_p($pageData['dataForDifferentCharts']['donutChartData']);die;
                            $data['dataForDifferentCharts']['barGraphData']['trafficSource'] = $this->_prepareDataForBargraphForpgps($trafficSourceArray,$extraData['studyAbroad']); 
                        }
                        //--------------------------------------------------------------------------------------------------
                        $data['engagementType'] = 'bounce';
                        $data['topTiles'] = $topTiles;

                        return $data;
                        break;

                    case 'pgpersess':
                        //_p(json_encode($engagementData['query']));die;
                        $params = array();
                        $params = $engagementData['query'];
                        //$query['aggs'] = '';
                        //_p(json_encode($query));
                        unset($params['body']['aggs']);
                        //_p(json_encode($params));die;

                        // We have query, now we apply diff aggs
                        // 1. For Line Chart
                        $pageViewsAggsFilter['pageViewsAggs']['sum']['field'] = 'pageviews';
                        $extraData['studyAbroad']['view'] = $this->_getView($extraData['studyAbroad']['view']);
                        $dateWiseFilter['dateWiseCount']['date_histogram']['interval'] =$extraData['studyAbroad']['view'];
                        $dateWiseFilter['dateWiseCount']['aggs'] = $pageViewsAggsFilter;
                        $dateWiseFilter['dateWiseCount']['date_histogram']['field'] = 'startTime';
                        $params['body']['aggs'] = $dateWiseFilter;
                        //_p(json_encode($params));die;
                        $search = $this->clientCon->search($params);
                        $actualCount = $search['hits']['total'];
                        $dateWiseData = $search['aggregations']['dateWiseCount']['buckets'];
                        foreach ($dateWiseData as $key => $value) {
                            $lineArray[date("Y-m-d", strtotime($value['key_as_string']))] = number_format((($value['pageViewsAggs']['value'])/$value['doc_count']), 2, '.', '');
                        }
                        $lineChartData= $this->MISCommonLib->prepareDataForLineChartForShikshaTraffic($lineArray,$dateRange,$extraData['studyAbroad']['view'],$actualCount,1);
                        $data['dataForDifferentCharts']['lineChartData'] = $lineChartData;
                        //_p($lineChartData);die;

                        // for Source Application Data
                        $donutChartDataBySourceApplication = $this->prepareDonutChartDataForEng($params,'isMobile',$colorCodes,1,'pgpersess');
                        $donutChartDataBySourceApplication = $this->_prepareDataForBargraphForpgps($donutChartDataBySourceApplication[0],$extraData['studyAbroad']);
                        $data['dataForDifferentCharts']['barGraphData']['deviceWise'] = $donutChartDataBySourceApplication;
                        //_p($donutChartDataBySourceApplication);die;

                        // for logged in and non logged in users
                        $pivotFilter['userPageViewCount']['sum']['field'] = 'pageviews';
                        $params['body']['aggs'] = $pivotFilter;
                        $params['body']['aggs']['users']['filter']['term']['userId'] =0;
                        $params['body']['aggs']['users']['aggs']['pageViewsCount']['sum']['field'] = 'pageviews';
                        //_p(json_encode($params));die;
                        $search = $this->clientCon->search($params);
                        $totalPageViews = $search['aggregations']['userPageViewCount']['value'];
                        $totalSessions = $search['hits']['total'];

                        $totalPageViewsForNonLogg = $search['aggregations']['users']['pageViewsCount']['value'];
                        $totalSessionsForNonLogg = $search['aggregations']['users']['doc_count'];

                        $totalPageViewsForLogg = $totalPageViews - $totalPageViewsForNonLogg;
                        $totalSessionsForLogg = $totalSessions - $totalSessionsForNonLogg;

                        $userwise = array();
                        $userwise['Loggedin'] = number_format((($totalPageViewsForLogg)/$totalSessionsForLogg), 2, '.', '');
                        $userwise['Non Loggedin']  = number_format((($totalPageViewsForNonLogg)/$totalSessionsForNonLogg), 2, '.', '');
                        //_p($userwise);die;
                        $userwiseBR = $this->_prepareDataForBargraphForpgps($userwise,$extraData['studyAbroad']);
                        $data['dataForDifferentCharts']['barGraphData']['userWise'] = $userwiseBR;
                        unset($params['body']['aggs']['users']);
                        if(!$pageName){
                            if($isComparision == 1){
                                // For Page wise Data
                                $donutChartDataByPageWise = $this->prepareDonutChartDataForEng($params,'landingPageDoc.pageIdentifier',$colorCodes,1,'pgpersess');
                                $donutChartDataByPageWise = $this->_prepareDataForBargraphForpgps($donutChartDataByPageWise[0],$extraData);
                                $data['dataForDifferentCharts']['barGraphData']['pageWise'] = $donutChartDataByPageWise;
                            }else{
                                // For Data Table
                                unset($params['body']['aggs']);

                                $pageViewsSum['pageViewAggs']['sum']['field'] = 'pageviews';

                                $siteSourceAggs['siteSource']['terms']['field'] =  "isMobile";
                                $siteSourceAggs['siteSource']['terms']['size'] =  0;
                                $siteSourceAggs['siteSource']['aggs'] = $pageViewsSum;

                                $sourceAggs['source']['terms']['field'] =  "source";
                                $sourceAggs['source']['terms']['size'] =  0;
                                $sourceAggs['source']['aggs'] = $siteSourceAggs;

                                $pageWiseAggs['pageWise']['terms']['field'] =  "landingPageDoc.pageIdentifier";
                                $pageWiseAggs['pageWise']['terms']['size'] =  0;
                                $pageWiseAggs['pageWise']['aggs'] = $sourceAggs;

                                $params['body']['aggs'] = $pageWiseAggs;

                                //_p(json_encode($params));die;
                                $search = $this->clientCon->search($params);
                                $result = $search['aggregations']['pageWise']['buckets'];
                                //_p($result);die;
                                $dataArray = array();
                                foreach ($result as $key => $pageArray){  
                                    $source = $pageArray['source']['buckets'];
                                    foreach ($source as $key => $sourceArray) {  
                                        $siteSource = $sourceArray['siteSource']['buckets'];
                                        foreach ($siteSource as $key => $siteSourceArray) {
                                            //_p($siteSourceArray['pageViewAggs']['value']);die;
                                            $page = $pageArray['key']; 
                                            $page = $this->MISCommonLib->getPageName($page);
                                            $dataArray[$page][$sourceArray['key']][($siteSourceArray['key']=='yes')?'Mobile':'Desktop'] = number_format((($siteSourceArray['pageViewAggs']['value'])/$siteSourceArray['doc_count']), 2, '.', '');
                                        }
                                    }
                                }

                                $dataTable = $this->_prepareDataForDataTableForpgpsForData($dataArray,$extraData);
                                $data['dataForDifferentCharts']['dataForDataTable'] = $dataTable;
                            }
                        }

                        if(!$isComparision){
                            $trafficSourceData = $this->_getTrafficSourceDataForpgpersess($engagementData['query']);

                            $trafficSourceBarGraph =$this->_prepareDataForBargraphForpgps($trafficSourceData['donutChart']['data'],$extraData['studyAbroad']);

                            $data['dataForDifferentCharts']['barGraphData']['trafficSource'] = $trafficSourceBarGraph;
                            unset($trafficSourceData['donutChart']);
                            $data['dataForDifferentCharts']['barGraphDataForPgpersess'] = $trafficSourceData['barGraphData'];
                            //_p($trafficSourceData);die;
                            $data['dataForDifferentCharts']['trafficSourceFilterData'] = $trafficSourceData['lis'];
                            $data['dataForDifferentCharts']['defaultView'] = $trafficSourceData['defaultView'];
                        }else{
                            
                            $params = $engagementData['query'];
                            unset($params['body']['aggs']);

                            $trafficSourceAggeration['trafficSource']['terms']['field']= 'source';
                            $trafficSourceAggeration['trafficSource']['terms']['size']= 0;
                            $trafficSourceAggeration['trafficSource']['aggs']['pageViewsAggs']['sum']['field']="pageviews";
                            $params['body']['aggs'] = $trafficSourceAggeration;
                            $trafficSourceData = $this->clientCon->search($params);
                            $trafficSourceData = $trafficSourceData['aggregations']['trafficSource']['buckets'];
                            foreach ($trafficSourceData as $key => $value) {                
                                $trafficSourceArray[$value['key']] = number_format((($value['pageViewsAggs']['value'])/$value['doc_count']), 2, '.', '');
                            }
                            $data['dataForDifferentCharts']['barGraphData']['trafficSource'] = $this->_prepareDataForBargraphForpgps($trafficSourceArray,$extraData['studyAbroad']); 
                        }
                        $data['engagementType'] = 'pgpersess';
                        $data['topTiles'] = $topTiles;
                        return $data;
                        break;

                    case 'avgsessdur':
                        //_p(json_encode($engagementData['query']));die;
                        $params = array();
                        $params = $engagementData['query'];
                        unset($params['body']['aggs']);
                        //_p(json_encode($params));die;

                        // We have query, now we apply diff aggs
                        // 1. For Line Chart
                        $pageViewsAggsFilter['pageViewsAggs']['sum']['field'] = 'duration';
                        $extraData['studyAbroad']['view'] = $this->_getView($extraData['studyAbroad']['view']);
                        $dateWiseFilter['dateWiseCount']['date_histogram']['interval'] =$extraData['studyAbroad']['view'];
                        $dateWiseFilter['dateWiseCount']['aggs'] = $pageViewsAggsFilter;
                        $dateWiseFilter['dateWiseCount']['date_histogram']['field'] = 'startTime';
                        $params['body']['aggs'] = $dateWiseFilter;
                        //_p(json_encode($params));die;
                        $search = $this->clientCon->search($params);
                        $actualCount = $search['hits']['total'];
                        $dateWiseData = $search['aggregations']['dateWiseCount']['buckets'];
                        foreach ($dateWiseData as $key => $value) {
                            $lineArray[date("Y-m-d", strtotime($value['key_as_string']))] = number_format((($value['pageViewsAggs']['value'])/($value['doc_count']*60)), 2, '.', '');
                        }
                        $lineChartData= $this->MISCommonLib->prepareDataForLineChartForShikshaTraffic($lineArray,$dateRange,$extraData['studyAbroad']['view'],$actualCount,1);
                        $data['dataForDifferentCharts']['lineChartData'] = $lineChartData;
                        //_p($data['dataForDifferentCharts']['lineChartData']);die;

                        // for Source Application Data
                        $donutChartDataBySourceApplication = $this->prepareDonutChartDataForEng($params,'isMobile',$colorCodes,1,'avgsessdur');
                        $donutChartDataBySourceApplication = $this->_prepareDataForBargraphForpgps($donutChartDataBySourceApplication[0],$extraData['studyAbroad']);
                        $data['dataForDifferentCharts']['barGraphData']['deviceWise'] = $donutChartDataBySourceApplication;
                        //_p($donutChartDataBySourceApplication);die;


                        // for logged in and non logged in users
                        $pivotFilter['userPageViewCount']['sum']['field'] = 'duration';
                        $params['body']['aggs'] = $pivotFilter;
                        $params['body']['aggs']['users']['filter']['term']['userId'] =0;
                        $params['body']['aggs']['users']['aggs']['pageViewsCount']['sum']['field'] = 'duration';
                        //_p(json_encode($params));die;
                        $search = $this->clientCon->search($params);
                        $totalPageViews = $search['aggregations']['userPageViewCount']['value'];
                        $totalSessions = $search['hits']['total'];

                        $totalPageViewsForNonLogg = $search['aggregations']['users']['pageViewsCount']['value'];
                        $totalSessionsForNonLogg = $search['aggregations']['users']['doc_count'];

                        $totalPageViewsForLogg = $totalPageViews - $totalPageViewsForNonLogg;
                        $totalSessionsForLogg = $totalSessions - $totalSessionsForNonLogg;

                        $userwise = array();
                        $userwise['Loggedin'] = number_format((($totalPageViewsForLogg)/($totalSessionsForLogg*60)), 2, '.', '');
                        $userwise['Non Loggedin']  = number_format((($totalPageViewsForNonLogg)/($totalSessionsForNonLogg*60)), 2, '.', '');
                        //_p($userwise);die;
                        $userwiseBR = $this->_prepareDataForBargraphForpgps($userwise,$extraData['studyAbroad']);
                        $data['dataForDifferentCharts']['barGraphData']['userWise'] = $userwiseBR;
                        unset($params['body']['aggs']['users']);

                        if(!$pageName){
                            if($isComparision == 1){
                                // For Page wise Data
                                $donutChartDataByPageWise = $this->prepareDonutChartDataForEng($params,'landingPageDoc.pageIdentifier',$colorCodes,1,'avgsessdur');
                                $donutChartDataByPageWise = $this->_prepareDataForBargraphForpgps($donutChartDataByPageWise[0],$extraData);
                                $data['dataForDifferentCharts']['barGraphData']['pageWise'] = $donutChartDataByPageWise;
                            }else{
                                // For Data Table
                                unset($params['body']['aggs']);

                                $pageViewsSum['pageViewAggs']['sum']['field'] = 'duration';

                                $siteSourceAggs['siteSource']['terms']['field'] =  "isMobile";
                                $siteSourceAggs['siteSource']['terms']['size'] =  0;
                                $siteSourceAggs['siteSource']['aggs'] = $pageViewsSum;

                                $sourceAggs['source']['terms']['field'] =  "source";
                                $sourceAggs['source']['terms']['size'] =  0;
                                $sourceAggs['source']['aggs'] = $siteSourceAggs;

                                $pageWiseAggs['pageWise']['terms']['field'] =  "landingPageDoc.pageIdentifier";
                                $pageWiseAggs['pageWise']['terms']['size'] =  0;
                                $pageWiseAggs['pageWise']['aggs'] = $sourceAggs;

                                $params['body']['aggs'] = $pageWiseAggs;

                                //_p(json_encode($params));die;
                                $search = $this->clientCon->search($params);
                                $result = $search['aggregations']['pageWise']['buckets'];
                                //_p($result);die;
                                $dataArray = array();
                                foreach ($result as $key => $pageArray){  
                                    $source = $pageArray['source']['buckets'];
                                    foreach ($source as $key => $sourceArray) {  
                                        $siteSource = $sourceArray['siteSource']['buckets'];
                                        foreach ($siteSource as $key => $siteSourceArray) {
                                            //_p($siteSourceArray['pageViewAggs']['value']);die;
                                            $page = $pageArray['key']; 
                                            $page = $this->MISCommonLib->getPageName($page);
                                            $dataArray[$page][$sourceArray['key']][($siteSourceArray['key']=='yes')?'Mobile':'Desktop'] = number_format((($siteSourceArray['pageViewAggs']['value'])/($siteSourceArray['doc_count']*60)), 2, '.', '');
                                        }
                                    }
                                }

                                $dataTable = $this->_prepareDataForDataTableForpgpsForData($dataArray,$extraData);
                                $data['dataForDifferentCharts']['dataForDataTable'] = $dataTable;
                            }
                        }

                        //$data = $this->_prepareDataForDifferentChartsForpgpersess($engagementData['result'],$pageName,$dateRange,$extraData['studyAbroad'],$isComparision);
                        if(!$isComparision){
                            $trafficSourceData = $this->_getTrafficSourceDataForAvgsessdr($engagementData['query']);

                            $trafficSourceBarGraph =$this->_prepareDataForBargraphForpgps($trafficSourceData['donutChart']['data'],$extraData['studyAbroad']);

                            $data['dataForDifferentCharts']['barGraphData']['trafficSource'] = $trafficSourceBarGraph;
                            unset($trafficSourceData['donutChart']);
                            $data['dataForDifferentCharts']['barGraphDataForPgpersess'] = $trafficSourceData['barGraphData'];
                            //_p($trafficSourceData);die;
                            $data['dataForDifferentCharts']['trafficSourceFilterData'] = $trafficSourceData['lis'];
                            $data['dataForDifferentCharts']['defaultView'] = $trafficSourceData['defaultView'];
                        }else{
                            $params = $engagementData['query'];
                            unset($params['body']['aggs']);
                            //_p(json_encode($params));die;

                            $trafficSourceAggeration['trafficSource']['terms']['field']= 'source';
                            $trafficSourceAggeration['trafficSource']['terms']['size']= 0;
                            $trafficSourceAggeration['trafficSource']['aggs']['pageViewsAggs']['sum']['field']="duration";
                            $params['body']['aggs'] = $trafficSourceAggeration;
                            //_p(json_encode($params));die;
                            $trafficSourceData = $this->clientCon->search($params);
                            $trafficSourceData = $trafficSourceData['aggregations']['trafficSource']['buckets'];
                            foreach ($trafficSourceData as $key => $value) {                
                                $trafficSourceArray[$value['key']] = number_format((($value['pageViewsAggs']['value'])/($value['doc_count']*60)), 2, '.', '');
                            }

                            //$data['dataForDifferentCharts']['barGraphData']['pageWise']
                            $data['dataForDifferentCharts']['barGraphData']['trafficSource'] = $this->_prepareDataForBargraphForpgps($trafficSourceArray,$extraData['studyAbroad']); 
                        }
                        $data['engagementType'] = 'avgsessdur';
                        $data['topTiles'] = $topTiles;
                        return $data;
                        break;
        }   
    }

    private function _prepareDataForDataTableForpgpsForData($prepareTableData)
    {
        //_p($prepareTableData);die;
        
        $dataTableHeading = " (Page- Source - Source Application wise) ";
        //Traffic Data (Page – Source - Source Application wise) 
        //$coloumHeading = $this->getColoumHeading($pageName);
        $dataTable = '<thead>'.
                        '<tr class="headings">'.
                            '<th style="padding-left:20px">'.
                                '<input type="checkbox" class="tableflat">'.
                            '</th>'.
                            '<th style="padding-left:20px">'.'Page </th>'.
                            '<th style="padding-left:20px">Source </th>'.
                            '<th style="padding-left:20px">Source Application </th>'.
                            '<th style="padding-left:20px;width:100px">Count </th>'.
                        '</tr>'.
                    '</thead>'.
                    '<tbody>';
        $prepareDataForCSV[0]  = array('Page','Source','Device','Count');

        foreach ($prepareTableData as $page => $pageArray){
            foreach ($pageArray as $source => $sourceArray) {
                foreach ($sourceArray as $siteSource => $siteSourceArray){
                    $dataTable = $dataTable.
                        '<tr class="even pointer">'.
                            '<td class="a-center ">'.
                                '<input type="checkbox" class="tableflat">'.
                            '</td>'.
                            '<td class=" ">'.$page.'</td>'.
                            '<td class=" ">'.$source.'</td>'.
                            '<td class=" ">'.$siteSource.'</td>'.
                            '<td class=" ">'.$siteSourceArray.'</td>'.
                        '</tr>';
                    $prepareDataForCSV[] = array($page,$source,$siteSource,$siteSourceArray);
                }
            }    
        }

        $dataTable = $dataTable.'</tbody>';
        $DataForDataTable = array($dataTableHeading,$dataTable,$prepareDataForCSV);
        //_p($DataForDataTable);die;
        return $DataForDataTable;  
    }

    private function _getTrafficSourceDataForExit($queryForExitPages,$queryForPageViews,$flag=0){
        $paramsForExitPages = $queryForExitPages;
        $paramsForPageViews = $queryForPageViews;
        unset($paramsForPageViews['body']['aggs']);
        //_p(json_encode($paramsForPageViews));die;
        // for traffic source aggeration
        if($flag ==0){
            $trafficSourceAggeration = array();
            $trafficSourceAggeration['trafficSource']['terms']['field']= 'exitPage.source';
            $trafficSourceAggeration['trafficSource']['terms']['size']= 0;
            $paramsForExitPages['body']['aggs'] = $trafficSourceAggeration;
            //_p(json_encode($paramsForExitPages));die;
            $trafficSourceData = $this->clientCon->search($paramsForExitPages);
            $trafficSourceData = $trafficSourceData['aggregations']['trafficSource']['buckets'];
            foreach ($trafficSourceData as $key => $value) {                
                $trafficSourceArray[$value['key']] = $value['doc_count'];
            }
            $trafficSourceData = $trafficSourceArray;

            // for pageviews
            $trafficSourceAggeration = array();
            $trafficSourceAggeration['trafficSource']['terms']['field']= 'source';
            $trafficSourceAggeration['trafficSource']['terms']['size']= 0;
            $paramsForPageViews['body']['aggs'] = $trafficSourceAggeration;
            //_p(json_encode($paramsForPageViews));die;
            $trafficSourceDataForPageviews = $this->clientCon->search($paramsForPageViews);
            $trafficSourceDataForPageviews = $trafficSourceDataForPageviews['aggregations']['trafficSource']['buckets'];
            foreach ($trafficSourceDataForPageviews as $key => $value) {
                $trafficSourceArrayForPageviews[$value['key']] = $value['doc_count'];
            }
            $trafficSourceDataForPageviews = $trafficSourceArrayForPageviews;
            //_p($trafficSourceDataForPageviews);die;
            foreach ($trafficSourceData as $key => $value) {
                $exitRateForTrafficSource[$key] = number_format((($value*100)/$trafficSourceDataForPageviews[$key]), 2, '.', '');
            }
            $temp = $trafficSourceData;
            //_p($exitRateForTrafficSource);die;
            //-----------------------------
            $trafficSourceData = $exitRateForTrafficSource;
            arsort($trafficSourceData);
            //_p($trafficSourceData);die;

            foreach ($trafficSourceData as $key => $value) {
                $trafficCount += $value;
                $trafficSourceArray[] = $key;
                $lis = $lis . 
                        '<li role="presentation"  >'.
                            '<a href="javascript:void(0)" id="'.$key.'" role="tab" data-toggle="tab" aria-expanded="true">'.ucfirst($key).
                            '</a>'.          
                        '</li>';
            }
            //_p($trafficSourceArray);die;
            $defaultView = 'paid';
            $prioritySourceArray= array('paid','mailer','social','direct','seo');
            foreach ($prioritySourceArray as $key => $value) {
                if(in_array($value, $trafficSourceArray)){
                    $defaultView = $value;
                    break;
                }
            }
            $barGraph['lis'] = $lis;
            $barGraph['defaultView'] = $defaultView;
            //$barGraph['barGraphData']
            $paramsForExitPages ='';
            $paramsForExitPages = $queryForExitPages;
            $paramsForExitPages['body']['query']['filtered']['filter']['bool']['must'][]['term']['exitPage.source'] = $defaultView;

            $paramsForPageViews ='';
            $paramsForPageViews = $queryForPageViews;
            $paramsForPageViews['body']['query']['filtered']['filter']['bool']['must'][]['term']['source'] = $defaultView;
        }

        //_p(json_encode($params));die;
        $exitPageForDefaultSource =$temp[$defaultView];
        $pageviewsForDefault = $trafficSourceDataForPageviews[$defaultView];
        //_p($exitPageForDefaultSource);_p($pageviewsForDefault);die;

        // now for diff utms change aggs
        $extraData['engagementType'] = 'exit';
        //==============================================================================================================
        //1. For UTM Source
        $UTMFilter['UTMSource']['terms']['field']= 'exitPage.utm_source';
        $UTMFilter['UTMSource']['terms']['size']= 0;
        $paramsForExitPages['body']['aggs'] = $UTMFilter;
        //_p(json_encode($paramsForExitPages));die;
        $search = $this->clientCon->search($paramsForExitPages);
        $UTMSourceData = $search['aggregations']['UTMSource']['buckets'];
        $utmExitPages='';
        $utmPageviews='';
        foreach ($UTMSourceData as $key => $value) {
            $utmExitPages += $value['doc_count'];
            $UTMSourceDataArray[$value['key']] = $value['doc_count'];
        }
        if($exitPageForDefaultSource != $utmExitPages){
            $diffSessionCount = $exitPageForDefaultSource - $utmExitPages;
            $UTMSourceDataArray['other'] = $diffSessionCount;
        }

        $UTMFilter ='';
        $UTMFilter['UTMSource']['terms']['field']= 'utm_source';
        $UTMFilter['UTMSource']['terms']['size']= 0;
        $paramsForPageViews['body']['aggs'] = $UTMFilter;
        //_p(json_encode($paramsForPageViews));die;
        $search = $this->clientCon->search($paramsForPageViews);
        $UTMSourceData = $search['aggregations']['UTMSource']['buckets'];
        foreach ($UTMSourceData as $key => $value) {
            $utmPageviews += $value['doc_count'];
            $UTMSourceDataArrayForpv[$value['key']] = $value['doc_count'];
        }
        if($pageviewsForDefault != $utmPageviews){
            $diffSessionCount = $pageviewsForDefault - $utmPageviews;
            $UTMSourceDataArrayForpv['other'] = $diffSessionCount;
        }

        foreach ($UTMSourceDataArray as $key => $value) {
            $UTMSourceDataArrayForExitRate[$key] = number_format((($value*100)/$UTMSourceDataArrayForpv[$key]), 2, '.', '');
        }

        $UTMSourceData = $this->_prepareDataForBargraphForpgpsForUTM($UTMSourceDataArrayForExitRate,$extraData,0);
        //--------------------------------------------------------------------------------------------------------------
        //2. For UTM Campaign
        $UTMFilter =array();
        $UTMFilter['UTMMedium']['terms']['field']= 'exitPage.utm_medium';
        $UTMFilter['UTMMedium']['terms']['size']= 0;
        $paramsForExitPages['body']['aggs'] = $UTMFilter;
        //_p(json_encode($paramsForExitPages));die;
        $search = $this->clientCon->search($paramsForExitPages);
        $UTMMediumData = $search['aggregations']['UTMMedium']['buckets'];
        $utmExitPages='';
        $utmPageviews='';
        foreach ($UTMMediumData as $key => $value) {
            $utmExitPages += $value['doc_count'];
            $UTMMediumDataArray[$value['key']] = $value['doc_count'];
        }
        if($exitPageForDefaultSource != $utmExitPages){
            $diffSessionCount = $exitPageForDefaultSource - $utmExitPages;
            $UTMMediumDataArray['other'] = $diffSessionCount;
        }
        
        $UTMFilter ='';
        $UTMFilter['UTMMedium']['terms']['field']= 'utm_medium';
        $UTMFilter['UTMMedium']['terms']['size']= 0;
        $paramsForPageViews['body']['aggs'] = $UTMFilter;
        //_p(json_encode($paramsForPageViews));die;
        $search = $this->clientCon->search($paramsForPageViews);
        $UTMMediumData = $search['aggregations']['UTMMedium']['buckets'];
        foreach ($UTMMediumData as $key => $value) {
            $utmPageviews += $value['doc_count'];
            $UTMMediumDataArrayForpv[$value['key']] = $value['doc_count'];
        }
        if($pageviewsForDefault != $utmPageviews){
            $diffSessionCount = $pageviewsForDefault - $utmPageviews;
            $UTMMediumDataArrayForpv['other'] = $diffSessionCount;
        }
        foreach ($UTMMediumDataArray as $key => $value) {
            $UTMMediumDataArrayForExitRate[$key] = number_format((($value*100)/$UTMMediumDataArrayForpv[$key]), 2, '.', '');
        }

        $UTMMediumData = $this->_prepareDataForBargraphForpgpsForUTM($UTMMediumDataArrayForExitRate,$extraData,0);
        //_p(json_encode($params));die;

        //3. For UTM Medium
        $UTMFilter =array();
        $UTMFilter['UTMCampaign']['terms']['field']= 'exitPage.utm_campaign';
        $UTMFilter['UTMCampaign']['terms']['size']= 0;
        $paramsForExitPages['body']['aggs'] = $UTMFilter;
        //_p(json_encode($paramsForExitPages));die;
        $search = $this->clientCon->search($paramsForExitPages);
        $UTMCampaignData = $search['aggregations']['UTMCampaign']['buckets'];
        $utmExitPages='';
        $utmPageviews='';
        foreach ($UTMCampaignData as $key => $value) {
            $utmExitPages += $value['doc_count'];
            $UTMCampaignDataArray[$value['key']] = $value['doc_count'];
        }
        if($exitPageForDefaultSource != $utmExitPages){
            $diffSessionCount = $exitPageForDefaultSource - $utmExitPages;
            $UTMCampaignDataArray['other'] = $diffSessionCount;
        }
        
        $UTMFilter ='';
        $UTMFilter['UTMCampaign']['terms']['field']= 'utm_campaign';
        $UTMFilter['UTMCampaign']['terms']['size']= 0;
        $paramsForPageViews['body']['aggs'] = $UTMFilter;
        //_p(json_encode($paramsForPageViews));die;
        $search = $this->clientCon->search($paramsForPageViews);
        $UTMCampaignData = $search['aggregations']['UTMCampaign']['buckets'];
        foreach ($UTMCampaignData as $key => $value) {
            $utmPageviews += $value['doc_count'];
            $UTMCampaignDataArrayForpv[$value['key']] = $value['doc_count'];
        }
        if($pageviewsForDefault != $utmPageviews){
            $diffSessionCount = $pageviewsForDefault - $utmPageviews;
            $UTMCampaignDataArrayForpv['other'] = $diffSessionCount;
        }

        foreach ($UTMCampaignDataArray as $key => $value) {
            $UTMCampaignDataArrayForExitRate[$key] = number_format((($value*100)/$UTMCampaignDataArrayForpv[$key]), 2, '.', '');
        }

        $UTMCampaignData = $this->_prepareDataForBargraphForpgpsForUTM($UTMCampaignDataArrayForExitRate,$extraData,1);

        //_p(json_encode($params));die;

        if($flag ==0){
            $inputArray = array(
                            'utmSource' =>$UTMSourceData,
                            'utmMedium' => $UTMMediumData,
                            'utmCampaign' => $UTMCampaignData
                            );
            $barGraph['barGraphData'] = $this->MISCommonLib->prepareTrafficSourceBarGraphForTraffic($inputArray);
            //$barGraph['barGraphData'] = array($UTMSourceData,$UTMCampaignData,$UTMMediumData);
            $barGraph['donutChart']['data'] = $trafficSourceData;
            $barGraph['donutChart']['count'] = $trafficCount;    
        }else{
            $inputArray = array(
                            'utmSource' =>$UTMSourceData,
                            'utmMedium' => $UTMMediumData,
                            'utmCampaign' => $UTMCampaignData
                            );
            $barGraph = $this->MISCommonLib->prepareTrafficSourceBarGraphForTraffic($inputArray);
            //$barGraph = array($UTMSourceData,$UTMCampaignData,$UTMMediumData);
        }
        
        return $barGraph;
    }

    private function _getTrafficSourceDataForBounce($query,$flag=0){

        $params = $query;
        unset($params['body']['aggs']);
        //_p(json_encode($params));die;
        // for traffic source aggeration
        if($flag ==0){

            $trafficSourceAggeration['trafficSource']['terms']['field']= 'source';
            $trafficSourceAggeration['trafficSource']['terms']['size']= 0;
            $trafficSourceAggeration['trafficSource']['aggs']['pageViewsAggs']['sum']['field']="bounce";
            $params['body']['aggs'] = $trafficSourceAggeration;
            //_p(json_encode($params));die;

            $trafficSourceData = $this->clientCon->search($params);
            $trafficSourceData = $trafficSourceData['aggregations']['trafficSource']['buckets'];
            foreach ($trafficSourceData as $key => $value) {               
                if($value['key']==''){
                    $trafficSourceArray['Other'] = number_format((($value['pageViewsAggs']['value']*100)/$value['doc_count']), 2, '.', '');
                }else{
                    $trafficSourceArray[$value['key']] = number_format((($value['pageViewsAggs']['value']*100)/$value['doc_count']), 2, '.', '');
                }
                
            }

            //_p($bounceRateForTrafficSource);die;
            //-----------------------------
            $trafficSourceData = $trafficSourceArray;
            arsort($trafficSourceData);
            //_p($trafficSourceData);die;

            foreach ($trafficSourceData as $key => $value) {
                $trafficCount += $value;
                if($key =='Other'){
                    continue;
                }
                $trafficSourceArray[] = $key;
                $lis = $lis . 
                        '<li role="presentation"  >'.
                            '<a href="javascript:void(0)" id="'.$key.'" role="tab" data-toggle="tab" aria-expanded="true">'.ucfirst($key).
                            '</a>'.          
                        '</li>';
            }
            //_p($trafficSourceArray);die;
            $defaultView = 'paid';
            $prioritySourceArray= array('paid','mailer','social','direct','seo');
            foreach ($prioritySourceArray as $key => $value) {
                if(in_array($value, $trafficSourceArray)){
                    $defaultView = $value;
                    break;
                }
            }
            $barGraph['lis'] = $lis;
            $barGraph['defaultView'] = $defaultView;
            //$barGraph['barGraphData']
            $params ='';
            $params = $query;
            $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['source'] = $defaultView;
        }

        $params['body']['aggs'] ='';
        $params['body']['aggs']['pageViewsAggs']['sum']['field']="bounce";
        $search = $this->clientCon->search($params);
        //_p(json_encode($params));die;
        $totalSessions = '';
        $totalSessions = $search['hits']['total'];

        $totalPageviews = '';
        $totalPageviews = $search['aggregations']['pageViewsAggs']['value'];
        
        unset($params['body']['aggs']);

        // now for diff utms change aggs
        $extraData['engagementType'] = 'bounce';
        
        //1. For UTM Source
        $UTMFilter['UTMSource']['terms']['field']= 'utm_source';
        $UTMFilter['UTMSource']['terms']['size']= 0;
        $UTMFilter['UTMSource']['aggs']['pageViewsAggs']['sum']['field']="bounce";
        $params['body']['aggs'] = $UTMFilter;
        //_p(json_encode($params));die;
        $search = $this->clientCon->search($params);
        $UTMSourceData = $search['aggregations']['UTMSource']['buckets'];

        $utmPageviews ='';
        $utmSessions = '';
        foreach ($UTMSourceData as $key => $value) {
            $utmPageviews += $value['pageViewsAggs']['value'];
            $utmSessions += $value['doc_count'];
            $UTMSourceDataArray[$value['key']] = number_format((($value['pageViewsAggs']['value']*100)/$value['doc_count']), 2, '.', '');
        }
        //_p($totalPageviews);_p($totalSessions);die;
        if(($utmPageviews != $totalPageviews) || ($utmSessions != $totalSessions)){
            $diffSessionCount = $totalSessions - $utmSessions;
            if($diffSessionCount >0){
                $UTMSourceDataArray['others'] = number_format((($totalPageviews - $utmPageviews)*100)/$diffSessionCount, 2, '.', '');  
            }  
        }
        $UTMSourceData = $UTMSourceDataArray;
        //_p($UTMSourceData);die;
        $UTMSourceData = $this->_prepareDataForBargraphForpgpsForUTM($UTMSourceData,$extraData,0);
        //$this->MISCommonLib->prepareDataForBarGraphForTraffic($UTMSourceData,$UTMSourceDataCount);
        //_p(json_encode($params));die;
        //--------------------------------------------------------------------------------------------------------------
        //2. For UTM Campaign
        $UTMFilter =array();
        $UTMFilter['UTMMedium']['terms']['field']= 'utm_medium';
        $UTMFilter['UTMMedium']['terms']['size']= 0;
        $UTMFilter['UTMMedium']['aggs']['pageViewsAggs']['sum']['field']="bounce";
        $params['body']['aggs'] = $UTMFilter;
    
        $search = $this->clientCon->search($params);
        $UTMMediumData = $search['aggregations']['UTMMedium']['buckets'];
        $utmPageviews ='';
        $utmSessions = '';
        foreach ($UTMMediumData as $key => $value) {
            $utmPageviews += $value['pageViewsAggs']['value'];
            $utmSessions += $value['doc_count'];
            $UTMMediumDataArray[$value['key']] = number_format((($value['pageViewsAggs']['value']*100)/$value['doc_count']), 2, '.', '');
        }
        if(($utmPageviews != $totalPageviews) || ($utmSessions != $totalSessions)){
            $diffSessionCount = $totalSessions - $utmSessions;
            if($diffSessionCount >0){
                $UTMMediumDataArray['others'] = number_format((($totalPageviews - $utmPageviews)*100)/$diffSessionCount, 2, '.', '');  
            }  
        }
        $UTMMediumData = $UTMMediumDataArray;
        $UTMMediumData = $this->_prepareDataForBargraphForpgpsForUTM($UTMMediumData,$extraData,0);//$this->MISCommonLib->prepareDataForBarGraphForTraffic($UTMMediumData,$UTMMediumDataCount);
        //_p(json_encode($params));die;

        //3. For UTM Medium
        $UTMFilter =array();
        $UTMFilter['UTMCampaign']['terms']['field']= 'utm_campaign';
        $UTMFilter['UTMCampaign']['terms']['size']= 0;
        $UTMFilter['UTMCampaign']['aggs']['pageViewsAggs']['sum']['field']="bounce";
        $params['body']['aggs'] = $UTMFilter;
        //_p(json_encode($params));die;
        $search = $this->clientCon->search($params);
        $UTMCampaignData = $search['aggregations']['UTMCampaign']['buckets'];
        $utmPageviews ='';
        $utmSessions = '';
        foreach ($UTMCampaignData as $key => $value) {
            $utmPageviews += $value['pageViewsAggs']['value'];
            $utmSessions += $value['doc_count'];
            $UTMCampaignDataArray[$value['key']] = number_format((($value['pageViewsAggs']['value']*100)/$value['doc_count']), 2, '.', '');
        }
        if(($utmPageviews != $totalPageviews) || ($utmSessions != $totalSessions)){
            $diffSessionCount = $totalSessions - $utmSessions;
            if($diffSessionCount >0){
                $UTMCampaignDataArray['others'] = number_format((($totalPageviews - $utmPageviews)*100)/$diffSessionCount, 2, '.', '');  
            }  
        }

        $UTMCampaignData = $UTMCampaignDataArray;
        $UTMCampaignData = $this->_prepareDataForBargraphForpgpsForUTM($UTMCampaignData,$extraData,1);//$this->MISCommonLib->prepareDataForBarGraphForTraffic($UTMCampaignData,$UTMCampaignDataCount);
        //_p(json_encode($params));die;

        if($flag ==0){
            $inputArray = array(
                            'utmSource' =>$UTMSourceData,
                            'utmMedium' => $UTMMediumData,
                            'utmCampaign' => $UTMCampaignData
                            );
            $barGraph['barGraphData'] = $this->MISCommonLib->prepareTrafficSourceBarGraphForTraffic($inputArray);
            //$barGraph['barGraphData'] = array($UTMSourceData,$UTMCampaignData,$UTMMediumData);
            $barGraph['donutChart']['data'] = $trafficSourceData;
            $barGraph['donutChart']['count'] = $trafficCount;    
        }else{
            $inputArray = array(
                            'utmSource' =>$UTMSourceData,
                            'utmMedium' => $UTMMediumData,
                            'utmCampaign' => $UTMCampaignData
                            );
            $barGraph = $this->MISCommonLib->prepareTrafficSourceBarGraphForTraffic($inputArray);
            //$barGraph = array($UTMSourceData,$UTMCampaignData,$UTMMediumData);
        }
        
        return $barGraph;
    }

    private function _prepareDataForBargraphForpgpsForUTM($pageWise,$extraData,$sizeFlag=0)
    {
        arsort($pageWise);
                $maxValue = 0;
        $i=0;
        foreach ($pageWise as $key => $value) {
            if($i==0){
                $maxValue = $value;
            }else{
                break;   
            }    
            $i++;
        }
        $avg = number_format((($maxValue)/100), 2,'.','');
        /*$avg = intval($avg);
        if($avg <= 0){
            $avg =1;
        }*/
        //_p($extraData['engagementType']);die;
        //_p($extraData);die;
        if($sizeFlag==0){
            $leftWidth = 40;
            $centerWidth  = 30;
            $countWidth =   30;
            $pageNameWidth = 26;
        }else{
            $leftWidth = 55;
            $centerWidth  = 30;
            $countWidth =   15;
            $pageNameWidth = 70;
        }
        $barGraph = '<table style="width: 100%;">';
        foreach ($pageWise as $key => $value) {
           //_p($value);die;
            /*_p($value);_p($avg);
            $valueWidth = $value/$avg;
            _p($valueWidth);*/
            $valueWidth = $value/$avg;
        if($extraData['engagementType']== 'avgsessdur'){
            /*$field = '<td style="width: 100px"><small style="width:10px !important;">&nbsp '.$value.'</small>&nbsp;<small style="width:10px !important;font-size: 15px;">Mins</small></div>';*/
            $field ='<td class="BGHeading_fontSize" style="width:'.$countWidth.'% !important">&nbsp&nbsp'.$value.'  Mins</td>';
        }else if($extraData['engagementType']== 'pgpersess'){
            /*$field = '<div style="width: 100px"><small style="width:10px !important;">&nbsp '.$value.'</small>&nbsp;<small style="width:10px !important;font-size: 15px;">Pages</small></div>';*/
            $field ='<td class="BGHeading_fontSize" style="width:'.$countWidth.'% !important">&nbsp&nbsp'.$value.'  Pages</td>';
        }else if($extraData['engagementType']== 'exit' || $extraData['engagementType']== 'bounce'){
            /*$field = '<div style="width: 100px"><small style="width:10px !important;">&nbsp '.$value.'</small>&nbsp;<small style="width:10px !important;font-size: 15px;">%</small></div>';*/
            $field ='<td class="BGHeading_fontSize" style="width:'.$countWidth.'% !important">&nbsp&nbsp'.$value.'  %</td>';
        }else{
            /*$field ='<div style="width: 100px"><small style="width:10px !important;">&nbsp '.$value.'</small></div>';*/
            $field ='<td class="BGHeading_fontSize" style="width:'.$countWidth.'% !important">&nbsp&nbsp'.$value.'  </td>';
        }
        //pgpersess
        //_p($key);
        
        //limitTextLength($title,$pageNameWidth);
        $splitName = limitTextLength($key,$pageNameWidth);
        //strlen($key) > 11 ? substr($key, 0, 10) . ' ...' : $key;

        $barGraph = $barGraph.
            '<tr class="widget_summary">'.
                '<td class="w_left" style="width:'.$leftWidth.'% !important">'.
                    '<span title="'.htmlentities($key).'">'.htmlentities($splitName).'</span>'.
                '</td>'.
                '<td class="w_center " style="width:'.$centerWidth.'% !important">'.
                    '<div class="progress" style="margin-bottom:10px !important" >'.
                        '<div  title = "'.$value.'" class="progress-bar bg-green" role="progressbar" style="width:'.$valueWidth.'%'.'" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100">'.
                            '<span class="sr-only"  ></span>'.
                        '</div>'.
                    '</div>'.
                '</td>'.$field.
                '<div class="clearfix"></div>';
            //_p($barGraph);die;
        }
        $barGraph = $barGraph.'</table>';
        //_p($barGraph);die;
        return    array('barGraph'=>$barGraph,
                'count' => count($pageWise));     
    }

    function prepareDataForTrafficSourceForAjaxCallForExit($dateRange,$defaultView,$filters,$count,$pageName,$pageType){
        
        $params = array();
        $params['index'] = MISCommonLib::$TRAFFICDATA_SESSIONS;
        $params['type'] = 'session';
        $params['body']['size'] = 0;
        $startDateFilter = array();
        $startDateFilter['range']['startTime']['gte'] = $dateRange['startDate'].'T00:00:00';
        $endDateFilter = array();
        $endDateFilter['range']['startTime']['lte'] = $dateRange['endDate'].'T23:59:59';

        if($filters['categoryId'] !=0 ){
            $ldbCourseIdsArray = $this->abroadCommonLib->getAbroadMainLDBCourses();
            foreach ($ldbCourseIdsArray as $key => $value) {
                $ldbCourseIds[]= $value['SpecializationId'];
            }

            if(in_array($filters['categoryId'],$ldbCourseIds)){
                $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['exitPage.LDBCourseId'] = $filters['categoryId'];
            }else{
                $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['exitPage.categoryId'] = $filters['categoryId'];
                if($filters['courseLevel']!= ''){
                    if($filters['courseLevel']!= '0'){
                        $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['exitPage.courseLevel'] = $filters['courseLevel'];
                    }
                }
            }
        }else{  
            if($filters['courseLevel']!= ''){
                if($filters['courseLevel']!= '0'){
                    $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['exitPage.courseLevel'] = $filters['courseLevel'];
                }
            }
        }

        if(intval($filters['country']) !=0 ){
           $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['exitPage.countryId'] = intval($filters['country']);    
        }
        if($pageName != 'Study Abroad'){
            if ($pageName == 'rankingPage') {
                if ($pageType == 1) {
                    $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['exitPage.pageIdentifier'] = 'universityRankingPage';
                } else if ($pageType == 2) {
                    $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['exitPage.pageIdentifier'] = 'courseRankingPage';
                }else{
                    $params['body']['query']['filtered']['filter']['bool']['should'][]['term']['exitPage.pageIdentifier'] = 'courseRankingPage';
                    $params['body']['query']['filtered']['filter']['bool']['should'][]['term']['exitPage.pageIdentifier'] = 'universityRankingPage';
                }
            } else {
                $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['exitPage.pageIdentifier'] = $pageName;
            }    
        }
        
        $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['exitPage.isStudyAbroad'] = 'yes';
        $params['body']['query']['filtered']['filter']['bool']['must'][] = $startDateFilter;
        $params['body']['query']['filtered']['filter']['bool']['must'][] = $endDateFilter;
        $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['exitPage.source'] = $defaultView;
        
        $exitPageQuery = $params;

        //====================================================
        $params = array();
        $params ='';
        $params['index'] = MISCommonLib::$TRAFFICDATA_PAGEVIEWS;
        $params['type'] = 'pageview';
        $params['body']['size'] = 0;
        $startDateFilter = array();
        $startDateFilter['range']['visitTime']['gte'] = $dateRange['startDate'].'T00:00:00';
        $endDateFilter = array();
        $endDateFilter['range']['visitTime']['lte'] = $dateRange['endDate'].'T23:59:59';

        if($filters['categoryId'] !=0 ){
            $ldbCourseIdsArray = $this->abroadCommonLib->getAbroadMainLDBCourses();
            foreach ($ldbCourseIdsArray as $key => $value) {
                $ldbCourseIds[]= $value['SpecializationId'];
            }

            if(in_array($filters['categoryId'],$ldbCourseIds)){
                $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['LDBCourseId'] = $filters['categoryId'];
            }else{
                $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['categoryId'] = $filters['categoryId'];
                if($filters['courseLevel']!= ''){
                    if($filters['courseLevel']!= '0'){
                        $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['courseLevel'] = $filters['courseLevel'];
                    }
                }
            }
        }else{  
            if($filters['courseLevel']!= ''){
                if($filters['courseLevel']!= '0'){
                    $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['courseLevel'] = $filters['courseLevel'];
                }
            }
        }

        if(intval($filters['country']) !=0 ){
           $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['countryId'] = intval($filters['country']);    
        }
        if($pageName != 'Study Abroad'){
            if ($pageName == 'rankingPage') {
                if ($pageType == 1) {
                    $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['pageIdentifier'] = 'universityRankingPage';
                } else if ($pageType == 2) {
                    $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['pageIdentifier'] = 'courseRankingPage';
                }else{
                    $params['body']['query']['filtered']['filter']['bool']['should'][]['term']['pageIdentifier'] = 'courseRankingPage';
                    $params['body']['query']['filtered']['filter']['bool']['should'][]['term']['pageIdentifier'] = 'universityRankingPage';
                }
            } else {
                $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['pageIdentifier'] = $pageName;
            }    
        }
        
        $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['isStudyAbroad'] = 'yes';
        $params['body']['query']['filtered']['filter']['bool']['must'][] = $startDateFilter;
        $params['body']['query']['filtered']['filter']['bool']['must'][] = $endDateFilter;
        $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['source'] = $defaultView;
        //_p(json_encode($exitPageQuery));_p('-------------');_p(json_encode($params));die;
        return $this->_getTrafficSourceDataForExit($exitPageQuery,$params,1);        
    }

    function prepareDataForTrafficSourceForAjaxCallForBounce($dateRange,$defaultView,$filters,$count,$pageName,$pageType){
        $params = array();
        $params['index'] = MISCommonLib::$TRAFFICDATA_SESSIONS;
        $params['type'] = 'session';
        $params['body']['size'] = 0;
        $startDateFilter = array();
        $startDateFilter['range']['startTime']['gte'] = $dateRange['startDate'].'T00:00:00';
        $endDateFilter = array();
        $endDateFilter['range']['startTime']['lte'] = $dateRange['endDate'].'T23:59:59';

        if($filters['categoryId'] !=0 ){
            $ldbCourseIdsArray = $this->abroadCommonLib->getAbroadMainLDBCourses();
            foreach ($ldbCourseIdsArray as $key => $value) {
                $ldbCourseIds[]= $value['SpecializationId'];
            }

            if(in_array($filters['categoryId'],$ldbCourseIds)){
                $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['landingPageDoc.LDBCourseId'] = $filters['categoryId'];
            }else{
                $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['landingPageDoc.categoryId'] = $filters['categoryId'];
                if($filters['courseLevel']!= ''){
                    if($filters['courseLevel']!= '0'){
                        $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['landingPageDoc.courseLevel'] = $filters['courseLevel'];
                    }
                }
            }
        }else{  
            if($filters['courseLevel']!= ''){
                if($filters['courseLevel']!= '0'){
                    $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['landingPageDoc.courseLevel'] = $filters['courseLevel'];
                }
            }
        }

        if(intval($filters['country']) !=0 ){
           $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['landingPageDoc.countryId'] = intval($filters['country']);    
        }
        if($pageName != 'Study Abroad'){
            if ($pageName == 'rankingPage') {
                if ($pageType == 1) {
                    $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['landingPageDoc.pageIdentifier'] = 'universityRankingPage';
                } else if ($pageType == 2) {
                    $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['landingPageDoc.pageIdentifier'] = 'courseRankingPage';
                }else{
                    $params['body']['query']['filtered']['filter']['bool']['should'][]['term']['landingPageDoc.pageIdentifier'] = 'courseRankingPage';
                    $params['body']['query']['filtered']['filter']['bool']['should'][]['term']['landingPageDoc.pageIdentifier'] = 'universityRankingPage';
                }
            } else {
                $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['landingPageDoc.pageIdentifier'] = $pageName;
            }    
        }

        $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['isStudyAbroad'] = 'yes';
        $params['body']['query']['filtered']['filter']['bool']['must'][] = $startDateFilter;
        $params['body']['query']['filtered']['filter']['bool']['must'][] = $endDateFilter;
        $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['source'] = $defaultView;
        //_p(json_encode($params));die;
        return $this->_getTrafficSourceDataForBounce($params,1);
    }

    function prepareDataForTrafficSourceForAjaxCallForAvgsessdur($dateRange,$defaultView,$filters,$count,$pageName,$pageType){

        $params = array();
        $params['index'] = MISCommonLib::$TRAFFICDATA_SESSIONS;
        $params['type'] = 'session';
        $params['body']['size'] = 0;
        $startDateFilter = array();
        $startDateFilter['range']['startTime']['gte'] = $dateRange['startDate'].'T00:00:00';
        $endDateFilter = array();
        $endDateFilter['range']['startTime']['lte'] = $dateRange['endDate'].'T23:59:59';

        if($filters['categoryId'] !=0 ){
            $ldbCourseIdsArray = $this->abroadCommonLib->getAbroadMainLDBCourses();
            foreach ($ldbCourseIdsArray as $key => $value) {
                $ldbCourseIds[]= $value['SpecializationId'];
            }

            if(in_array($filters['categoryId'],$ldbCourseIds)){
                $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['landingPageDoc.LDBCourseId'] = $filters['categoryId'];
            }else{
                $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['landingPageDoc.categoryId'] = $filters['categoryId'];
                if($filters['courseLevel']!= ''){
                    if($filters['courseLevel']!= '0'){
                        $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['landingPageDoc.courseLevel'] = $filters['courseLevel'];
                    }
                }
            }
        }else{  
            if($filters['courseLevel']!= ''){
                if($filters['courseLevel']!= '0'){
                    $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['landingPageDoc.courseLevel'] = $filters['courseLevel'];
                }
            }
        }

        if(intval($filters['country']) !=0 ){
           $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['landingPageDoc.countryId'] = intval($filters['country']);    
        }
        if($pageName != 'Study Abroad'){
            if ($pageName == 'rankingPage') {
                if ($pageType == 1) {
                    $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['landingPageDoc.pageIdentifier'] = 'universityRankingPage';
                } else if ($pageType == 2) {
                    $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['landingPageDoc.pageIdentifier'] = 'courseRankingPage';
                }else{
                    $params['body']['query']['filtered']['filter']['bool']['should'][]['term']['landingPageDoc.pageIdentifier'] = 'courseRankingPage';
                    $params['body']['query']['filtered']['filter']['bool']['should'][]['term']['landingPageDoc.pageIdentifier'] = 'universityRankingPage';
                }
            } else {
                $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['landingPageDoc.pageIdentifier'] = $pageName;
            }    
        }
        
        $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['isStudyAbroad'] = 'yes';
        $params['body']['query']['filtered']['filter']['bool']['must'][] = $startDateFilter;
        $params['body']['query']['filtered']['filter']['bool']['must'][] = $endDateFilter;
        $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['source'] = $defaultView;
        //_p(json_encode($params));die;
        return $this->_getTrafficSourceDataForAvgsessdr($params,1);
    }

    function prepareDataForTrafficSourceForAjaxCallForPgpersess($dateRange,$defaultView,$filters,$count,$pageName,$pageType){

        $params = array();
        $params['index'] = MISCommonLib::$TRAFFICDATA_SESSIONS;
        $params['type'] = 'session';
        $params['body']['size'] = 0;
        $startDateFilter = array();
        $startDateFilter['range']['startTime']['gte'] = $dateRange['startDate'].'T00:00:00';
        $endDateFilter = array();
        $endDateFilter['range']['startTime']['lte'] = $dateRange['endDate'].'T23:59:59';
        
        if($filters['categoryId'] !=0 ){
            $ldbCourseIdsArray = $this->abroadCommonLib->getAbroadMainLDBCourses();
            foreach ($ldbCourseIdsArray as $key => $value) {
                $ldbCourseIds[]= $value['SpecializationId'];
            }

            if(in_array($filters['categoryId'],$ldbCourseIds)){
                $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['landingPageDoc.LDBCourseId'] = $filters['categoryId'];
            }else{
                $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['landingPageDoc.categoryId'] = $filters['categoryId'];
                if($filters['courseLevel']!= ''){
                    if($filters['courseLevel']!= '0'){
                        $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['landingPageDoc.courseLevel'] = $filters['courseLevel'];
                    }
                }
            }
        }else{  
            if($filters['courseLevel']!= ''){
                if($filters['courseLevel']!= '0'){
                    $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['landingPageDoc.courseLevel'] = $filters['courseLevel'];
                }
            }
        }

        if(intval($filters['country']) !=0 ){
           $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['landingPageDoc.countryId'] = intval($filters['country']);    
        }
        if($pageName != 'Study Abroad'){
            if ($pageName == 'rankingPage') {
                if ($pageType == 1) {
                    $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['landingPageDoc.pageIdentifier'] = 'universityRankingPage';
                } else if ($pageType == 2) {
                    $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['landingPageDoc.pageIdentifier'] = 'courseRankingPage';
                }else{
                    $params['body']['query']['filtered']['filter']['bool']['should'][]['term']['landingPageDoc.pageIdentifier'] = 'courseRankingPage';
                    $params['body']['query']['filtered']['filter']['bool']['should'][]['term']['landingPageDoc.pageIdentifier'] = 'universityRankingPage';
                }
            } else {
                $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['landingPageDoc.pageIdentifier'] = $pageName;
            }    
        }
        
        $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['isStudyAbroad'] = 'yes';
        
        $params['body']['query']['filtered']['filter']['bool']['must'][] = $startDateFilter;
        $params['body']['query']['filtered']['filter']['bool']['must'][] = $endDateFilter;
        $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['source'] = $defaultView;
        //_p(json_encode($params));die;
        return $this->_getTrafficSourceDataForpgpersess($params,1);
    }

    function prepareDataForTrafficSourceForAjaxCallForPageviews($dateRange,$defaultView,$filters,$count,$pageName,$pageType=''){
        $params = array();
        $params['index'] = MISCommonLib::$TRAFFICDATA_PAGEVIEWS;
        $params['type'] = 'pageview';
        $params['body']['size'] = 0;
        $startDateFilter = array();
        $startDateFilter['range']['visitTime']['gte'] = $dateRange['startDate'].'T00:00:00';
        $endDateFilter = array();
        $endDateFilter['range']['visitTime']['lte'] = $dateRange['endDate'].'T23:59:59';


        if($filters['categoryId'] !=0 ){
            $ldbCourseIdsArray = $this->abroadCommonLib->getAbroadMainLDBCourses();
            foreach ($ldbCourseIdsArray as $key => $value) {
                $ldbCourseIds[]= $value['SpecializationId'];
            }

            if(in_array($filters['categoryId'],$ldbCourseIds)){
                $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['LDBCourseId'] = $filters['categoryId'];
            }else{
                $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['categoryId'] = $filters['categoryId'];
                if($filters['courseLevel']!= ''){
                    if($filters['courseLevel']!= '0'){
                        $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['courseLevel'] = $filters['courseLevel'];
                    }
                }
            }
        }else{  
            if($filters['courseLevel']!= ''){
                if($filters['courseLevel']!= '0'){
                    $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['courseLevel'] = $filters['courseLevel'];
                }
            }
        }

        if(intval($filters['country']) !=0 ){
           $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['countryId'] = intval($filters['country']);    
        }

        if(is_array($filters['country']) ){
            if(sizeof( $filters['country']) == 1){
                    $params['body']['query']['filtered']['query']['bool']['must'][]['match']['landingPageDoc.countryId'] = $filters['country'][0];
            }else{
                foreach ($filters['country'] as $key => $value) {
                    $params['body']['query']['filtered']['query']['bool']['should'][]['match']['landingPageDoc.countryId'] = $value;
                }
                $params['body']['query']['filtered']['query']['bool']['minimum_should_match'] = 1;    
            }    
        }else{
            if($filters['country'] !=0 ){
                $params['body']['query']['filtered']['query']['bool']['must'][]['match']['landingPageDoc.countryId'] = $filters['country'];    
            }
        }

        if($pageName != ''){
            if($pageName != 'Study Abroad'){
                if ($pageName == 'rankingPage') {
                    if ($pageType == 1) {
                        $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['pageIdentifier'] = 'universityRankingPage';
                    } else if ($pageType == 2) {
                        $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['pageIdentifier'] = 'courseRankingPage';
                    }else{
                        $params['body']['query']['filtered']['filter']['bool']['should'][]['term']['pageIdentifier'] = 'courseRankingPage';
                        $params['body']['query']['filtered']['filter']['bool']['should'][]['term']['pageIdentifier'] = 'universityRankingPage';
                    }
                } else {
                    $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['pageIdentifier'] = $pageName;
                }    
            }
        }
        
        $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['isStudyAbroad'] = 'yes';
        $params['body']['query']['filtered']['filter']['bool']['must'][] = $startDateFilter;
        $params['body']['query']['filtered']['filter']['bool']['must'][] = $endDateFilter;
        $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['source'] = $defaultView;
        //_p(json_encode($params));die;
        return $this->_getTrafficSourceData($params,1);
    }

    private function _getTrafficSourceDataForAvgsessdr($query,$flag=0){
        $params = $query;
        unset($params['body']['aggs']);
        //_p(json_encode($params));die;
        if($flag ==0){
            $trafficSourceAggeration['trafficSource']['terms']['field']= 'source';
            $trafficSourceAggeration['trafficSource']['terms']['size']= 0;
            $trafficSourceAggeration['trafficSource']['aggs']['pageViewsAggs']['sum']['field']="duration";
            $params['body']['aggs'] = $trafficSourceAggeration;
            //_p(json_encode($params));die;
            $trafficSourceData = $this->clientCon->search($params);
            $trafficSourceData = $trafficSourceData['aggregations']['trafficSource']['buckets'];

            foreach ($trafficSourceData as $key => $value) {                
                if($value['key']==''){
                    $trafficSourceArray['Other'] = number_format((($value['pageViewsAggs']['value'])/($value['doc_count']*60)), 2, '.', '');
                }else{
                    $trafficSourceArray[$value['key']] = number_format((($value['pageViewsAggs']['value'])/($value['doc_count']*60)), 2, '.', '');
                }
            }
            $trafficSourceData = $trafficSourceArray;
            //_p($trafficSourceData);die;
            //-----------------------------
            arsort($trafficSourceData);
            //_p($trafficSourceData);die;
            foreach ($trafficSourceData as $key => $value) {
                $trafficCount += $value;
                if($key =='Other'){
                    continue;
                }
                $trafficSourceArray[] = $key;
                $lis = $lis . 
                        '<li role="presentation"  >'.
                            '<a href="javascript:void(0)" id="'.$key.'" role="tab" data-toggle="tab" aria-expanded="true">'.ucfirst($key).
                            '</a>'.          
                        '</li>';
            }
            //_p($trafficSourceArray);die;
            $defaultView = 'paid';
            $prioritySourceArray= array('paid','mailer','social','direct','seo');
            foreach ($prioritySourceArray as $key => $value) {
                if(in_array($value, $trafficSourceArray)){
                    $defaultView = $value;
                    break;
                }
            }
            $barGraph['lis'] = $lis;
            $barGraph['defaultView'] = $defaultView;
            //$barGraph['barGraphData']
            $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['source'] = $defaultView;
        }
        
        $params['body']['aggs'] ='';
        $params['body']['aggs']['pageViewsAggs']['sum']['field']="duration";
        $search = $this->clientCon->search($params);        
        //_p(json_encode($params));die;

        $totalPageviews = '';
        $totalPageviews = $search['aggregations']['pageViewsAggs']['value'];

        $totalSessions = '';
        $totalSessions = $search['hits']['total'];
        

        unset($params['body']['aggs']);
        // now for diff utms change aggs
        $extraData['engagementType'] = 'avgsessdur';
        //1. For UTM Source
        $UTMFilter['UTMSource']['terms']['field']= 'utm_source';
        $UTMFilter['UTMSource']['terms']['size']= 0;
        $UTMFilter['UTMSource']['aggs']['pageViewsAggs']['sum']['field']="duration";
        $params['body']['aggs'] = $UTMFilter;
        //_p(json_encode($params));die;
        $search = $this->clientCon->search($params);
        $UTMSourceData = $search['aggregations']['UTMSource']['buckets'];

        $utmPageviews ='';
        $utmSessions = '';
        foreach ($UTMSourceData as $key => $value) {
            $utmPageviews += $value['pageViewsAggs']['value'];
            $utmSessions += $value['doc_count'];
            $UTMSourceDataArray[$value['key']] = number_format((($value['pageViewsAggs']['value'])/($value['doc_count']*60)), 2, '.', '');
        }
        
        if(($utmPageviews != $totalPageviews) || ($utmSessions != $totalSessions)){
            $diffSessionCount = $totalSessions - $utmSessions;
            if($diffSessionCount >0){
                $UTMSourceDataArray['others'] = number_format(($totalPageviews - $utmPageviews)/($diffSessionCount*60), 2, '.', '');  
            }  
        }
        $UTMSourceData = $UTMSourceDataArray;
        //_p($UTMSourceData);die;
        $UTMSourceData = $this->_prepareDataForBargraphForpgpsForUTM($UTMSourceData,$extraData,0);
        //$this->MISCommonLib->prepareDataForBarGraphForTraffic($UTMSourceData,$UTMSourceDataCount);
        //_p(json_encode($params));die;
        //--------------------------------------------------------------------------------------------------------------
        //2. For UTM Campaign
        $UTMFilter =array();
        $UTMFilter['UTMMedium']['terms']['field']= 'utm_medium';
        $UTMFilter['UTMMedium']['terms']['size']= 0;
        $UTMFilter['UTMMedium']['aggs']['pageViewsAggs']['sum']['field']="duration";
        $params['body']['aggs'] = $UTMFilter;
        $search = $this->clientCon->search($params);
        $UTMMediumData = $search['aggregations']['UTMMedium']['buckets'];
        $utmPageviews ='';
        $utmSessions = '';
        foreach ($UTMMediumData as $key => $value) {
            $utmPageviews += $value['pageViewsAggs']['value'];
            $utmSessions += $value['doc_count'];

            $UTMMediumDataArray[$value['key']] = number_format((($value['pageViewsAggs']['value'])/($value['doc_count']*60)), 2, '.', '');
        }
        if(($utmPageviews != $totalPageviews) || ($utmSessions != $totalSessions)){
            $diffSessionCount = $totalSessions - $utmSessions;
            if($diffSessionCount >0){
                $UTMMediumDataArray['others'] = number_format(($totalPageviews - $utmPageviews)/($diffSessionCount*60), 2, '.', '');  
            }  
        }
        $UTMMediumData = $UTMMediumDataArray;
        $UTMMediumData = $this->_prepareDataForBargraphForpgpsForUTM($UTMMediumData,$extraData,0);//$this->MISCommonLib->prepareDataForBarGraphForTraffic($UTMMediumData,$UTMMediumDataCount);
        //_p(json_encode($params));die;

        //3. For UTM Medium
        $UTMFilter =array();
        $UTMFilter['UTMCampaign']['terms']['field']= 'utm_campaign';
        $UTMFilter['UTMCampaign']['terms']['size']= 0;
        $UTMFilter['UTMCampaign']['aggs']['pageViewsAggs']['sum']['field']="duration";
        $params['body']['aggs'] = $UTMFilter;

        $search = $this->clientCon->search($params);
        $UTMCampaignData = $search['aggregations']['UTMCampaign']['buckets'];
        $utmPageviews ='';
        $utmSessions = '';
        foreach ($UTMCampaignData as $key => $value) {
            $utmPageviews += $value['pageViewsAggs']['value'];
            $utmSessions += $value['doc_count'];
            $UTMCampaignDataArray[$value['key']] = number_format((($value['pageViewsAggs']['value'])/($value['doc_count']*60)), 2, '.', '');
        }
        if(($utmPageviews != $totalPageviews) || ($utmSessions != $totalSessions)){
            $diffSessionCount = $totalSessions - $utmSessions;
            if($diffSessionCount >0){
                $UTMCampaignDataArray['others'] = number_format(($totalPageviews - $utmPageviews)/($diffSessionCount*60), 2, '.', '');  
            }  
        }

        $UTMCampaignData = $UTMCampaignDataArray;
        $UTMCampaignData = $this->_prepareDataForBargraphForpgpsForUTM($UTMCampaignData,$extraData,1);//$this->MISCommonLib->prepareDataForBarGraphForTraffic($UTMCampaignData,$UTMCampaignDataCount);
        //_p(json_encode($params));die;

        if($flag ==0){
            $inputArray = array(
                            'utmSource' =>$UTMSourceData,
                            'utmMedium' => $UTMMediumData,
                            'utmCampaign' => $UTMCampaignData
                            );
            $barGraph['barGraphData'] = $this->MISCommonLib->prepareTrafficSourceBarGraphForTraffic($inputArray);
            //$barGraph['barGraphData'] = array($UTMSourceData,$UTMCampaignData,$UTMMediumData);
            $barGraph['donutChart']['data'] = $trafficSourceData;
            $barGraph['donutChart']['count'] = $trafficCount;    
        }else{
            $inputArray = array(
                            'utmSource' =>$UTMSourceData,
                            'utmMedium' => $UTMMediumData,
                            'utmCampaign' => $UTMCampaignData
                            );
            $barGraph = $this->MISCommonLib->prepareTrafficSourceBarGraphForTraffic($inputArray);
            //$barGraph = array($UTMSourceData,$UTMCampaignData,$UTMMediumData);
        }
        
        return $barGraph;
    }

    private function _getTrafficSourceDataForpgpersess($query,$flag=0){
        $params = $query;
        unset($params['body']['aggs']);
        //_p(json_encode($params));die;
        // for traffic source aggeration
        if($flag ==0){
            $trafficSourceAggeration['trafficSource']['terms']['field']= 'source';
            $trafficSourceAggeration['trafficSource']['terms']['size']= 0;
            $trafficSourceAggeration['trafficSource']['aggs']['pageViewsAggs']['sum']['field']="pageviews";

            $params['body']['aggs'] = $trafficSourceAggeration;
            //_p(json_encode($params));die;
            $trafficSourceData = $this->clientCon->search($params);
            $trafficSourceData = $trafficSourceData['aggregations']['trafficSource']['buckets'];
            foreach ($trafficSourceData as $key => $value) {                
                if($value['key']==''){
                    $trafficSourceArray['Other'] = number_format((($value['pageViewsAggs']['value'])/$value['doc_count']), 2, '.', '');
                }else{
                    $trafficSourceArray[$value['key']] = number_format((($value['pageViewsAggs']['value'])/$value['doc_count']), 2, '.', '');
                }
            }
            $trafficSourceData = $trafficSourceArray;
            //_p($trafficSourceData);die;
            //-----------------------------
            arsort($trafficSourceData);
            //_p($trafficSourceData);die;
            foreach ($trafficSourceData as $key => $value) {
                $trafficCount += $value;
                if($key =='Other'){
                    continue;
                }
                $trafficSourceArray[] = $key;
                $lis = $lis . 
                        '<li role="presentation"  >'.
                            '<a href="javascript:void(0)" id="'.$key.'" role="tab" data-toggle="tab" aria-expanded="true">'.ucfirst($key).
                            '</a>'.          
                        '</li>';
            }
            //_p($trafficSourceArray);die;
            $defaultView = 'paid';
            $prioritySourceArray= array('paid','mailer','social','direct','seo');
            foreach ($prioritySourceArray as $key => $value) {
                if(in_array($value, $trafficSourceArray)){
                    $defaultView = $value;
                    break;
                }
            }
            $barGraph['lis'] = $lis;
            $barGraph['defaultView'] = $defaultView;
            //$barGraph['barGraphData']
            $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['source'] = $defaultView;
        }
        
        $params['body']['aggs'] ='';
        $params['body']['aggs']['pageViewsAggs']['sum']['field']="pageviews";
        $search = $this->clientCon->search($params);
        //_p(json_encode($params));die;

        $totalPageviews = '';
        $totalPageviews = $search['aggregations']['pageViewsAggs']['value'];

        $totalSessions = '';
        $totalSessions = $search['hits']['total'];
        
        unset($params['body']['aggs']);
    
        // now for diff utms change aggs
        $extraData['engagementType'] = 'pgpersess';
        //1. For UTM Source
        $UTMFilter['UTMSource']['terms']['field']= 'utm_source';
        $UTMFilter['UTMSource']['terms']['size']= 0;
        $UTMFilter['UTMSource']['aggs']['pageViewsAggs']['sum']['field']="pageviews";
        $params['body']['aggs'] = $UTMFilter;
        //_p(json_encode($params));die;
        $search = $this->clientCon->search($params);
        $UTMSourceData = $search['aggregations']['UTMSource']['buckets'];

        $utmPageviews ='';
        $utmSessions = '';
        foreach ($UTMSourceData as $key => $value) {
            $utmPageviews += $value['pageViewsAggs']['value'];
            $utmSessions += $value['doc_count'];
            $UTMSourceDataArray[$value['key']] = number_format((($value['pageViewsAggs']['value'])/$value['doc_count']), 2, '.', '');
        }
        
        if(($utmPageviews != $totalPageviews) || ($utmSessions != $totalSessions)){
            $diffSessionCount = $totalSessions - $utmSessions;
            if($diffSessionCount >0){
                $UTMSourceDataArray['others'] = number_format(($totalPageviews - $utmPageviews)/$diffSessionCount, 2, '.', '');  
            }  
        }
        $UTMSourceData = $UTMSourceDataArray;
        //_p($UTMSourceData);die;
        $UTMSourceData = $this->_prepareDataForBargraphForpgpsForUTM($UTMSourceData,$extraData,0);//$this->MISCommonLib->prepareDataForBarGraphForTraffic($UTMSourceData,$UTMSourceDataCount);
        //_p(json_encode($params));die;
        //--------------------------------------------------------------------------------------------------------------
        //2. For UTM Campaign
        $UTMFilter =array();
        $UTMFilter['UTMMedium']['terms']['field']= 'utm_medium';
        $UTMFilter['UTMMedium']['terms']['size']= 0;
        $UTMFilter['UTMMedium']['aggs']['pageViewsAggs']['sum']['field']="pageviews";
        $params['body']['aggs'] = $UTMFilter;
        //_p(json_encode($params));die;
        $search = $this->clientCon->search($params);
        $UTMMediumData = $search['aggregations']['UTMMedium']['buckets'];
        $utmPageviews ='';
        $utmSessions = '';
        foreach ($UTMMediumData as $key => $value) {
            $utmPageviews += $value['pageViewsAggs']['value'];
            $utmSessions += $value['doc_count'];

            $UTMMediumDataArray[$value['key']] = number_format((($value['pageViewsAggs']['value'])/$value['doc_count']), 2, '.', '');
        }
        if(($utmPageviews != $totalPageviews) || ($utmSessions != $totalSessions)){
            $diffSessionCount = $totalSessions - $utmSessions;
            if($diffSessionCount >0){
                $UTMMediumDataArray['others'] = number_format(($totalPageviews - $utmPageviews)/$diffSessionCount, 2, '.', '');  
            }  
        }
        $UTMMediumData = $UTMMediumDataArray;
        $UTMMediumData = $this->_prepareDataForBargraphForpgpsForUTM($UTMMediumData,$extraData,0);//$this->MISCommonLib->prepareDataForBarGraphForTraffic($UTMMediumData,$UTMMediumDataCount);
        //_p(json_encode($params));die;

        //3. For UTM Medium
        $UTMFilter =array();
        $UTMFilter['UTMCampaign']['terms']['field']= 'utm_campaign';
        $UTMFilter['UTMCampaign']['terms']['size']= 0;
        $UTMFilter['UTMCampaign']['aggs']['pageViewsAggs']['sum']['field']="pageviews";
        $params['body']['aggs'] = $UTMFilter;
        //_p(json_encode($params));die;
        $search = $this->clientCon->search($params);
        $UTMCampaignData = $search['aggregations']['UTMCampaign']['buckets'];
        $utmPageviews ='';
        $utmSessions = '';
        foreach ($UTMCampaignData as $key => $value) {
            $utmPageviews += $value['pageViewsAggs']['value'];
            $utmSessions += $value['doc_count'];
            $UTMCampaignDataArray[$value['key']] = number_format((($value['pageViewsAggs']['value'])/$value['doc_count']), 2, '.', '');
        }
        if(($utmPageviews != $totalPageviews) || ($utmSessions != $totalSessions)){
            $diffSessionCount = $totalSessions - $utmSessions;
            if($diffSessionCount >0){
                $UTMCampaignDataArray['others'] = number_format(($totalPageviews - $utmPageviews)/$diffSessionCount, 2, '.', '');  
            }  
        }

        $UTMCampaignData = $UTMCampaignDataArray;
        $UTMCampaignData = $this->_prepareDataForBargraphForpgpsForUTM($UTMCampaignData,$extraData,1);//$this->MISCommonLib->prepareDataForBarGraphForTraffic($UTMCampaignData,$UTMCampaignDataCount);
        //_p(json_encode($params));die;

        if($flag ==0){
            $inputArray = array(
                            'utmSource' =>$UTMSourceData,
                            'utmMedium' => $UTMMediumData,
                            'utmCampaign' => $UTMCampaignData
                            );
            $barGraph['barGraphData'] = $this->MISCommonLib->prepareTrafficSourceBarGraphForTraffic($inputArray);
            //$barGraph['barGraphData'] = array($UTMSourceData,$UTMCampaignData,$UTMMediumData);
            $barGraph['donutChart']['data'] = $trafficSourceData;
            $barGraph['donutChart']['count'] = $trafficCount;    
        }else{
            $inputArray = array(
                            'utmSource' =>$UTMSourceData,
                            'utmMedium' => $UTMMediumData,
                            'utmCampaign' => $UTMCampaignData
                            );
            $barGraph = $this->MISCommonLib->prepareTrafficSourceBarGraphForTraffic($inputArray);
            //$barGraph = array($UTMSourceData,$UTMCampaignData,$UTMMediumData);
        }
        
        return $barGraph;
    }

    private function _getTrafficSourceData($query,$flag=0){

        $params = $query;
        //_p(json_encode($params));die;
        // for traffic source aggeration
        if($flag ==0){
            $trafficSourceAggeration['trafficSource']['terms']['field']= 'source';
            $trafficSourceAggeration['trafficSource']['terms']['size']= 0;
            $params['body']['aggs'] = $trafficSourceAggeration;
            $trafficSourceData = $this->clientCon->search($params);
            $trafficSourceData = $trafficSourceData['aggregations']['trafficSource']['buckets'];
            foreach ($trafficSourceData as $key => $value) {
                $trafficSourceArray[$value['key']] = $value['doc_count'];
            }
            $trafficSourceData = $trafficSourceArray;
            //_p($trafficSourceData);die;
            //-----------------------------
            arsort($trafficSourceData);
            //_p($trafficSourceData);die;
            foreach ($trafficSourceData as $key => $value) {
                $trafficCount += $value;
                $trafficSourceArray[] = $key;
                $lis = $lis . 
                        '<li role="presentation"  >'.
                            '<a href="javascript:void(0)" id="'.$key.'" role="tab" data-toggle="tab" aria-expanded="true">'.ucfirst($key).
                            '</a>'.          
                        '</li>';
            }
            //_p($trafficSourceArray);die;
            $defaultView = 'paid';
            $prioritySourceArray= array('paid','mailer','social','direct','seo');
            foreach ($prioritySourceArray as $key => $value) {
                if(in_array($value, $trafficSourceArray)){
                    $defaultView = $value;
                    break;
                }
            }
            $barGraph['lis'] = $lis;
            $barGraph['defaultView'] = $defaultView;
            //$barGraph['barGraphData']

            $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['source'] = $defaultView;    
        }
        
        // now for diff utms change aggs
        //1. For UTM Source
        $UTMFilter['UTMSource']['terms']['field']= 'utm_source';
        $UTMFilter['UTMSource']['terms']['size']= 0;
        $params['body']['aggs'] = $UTMFilter;
        //_p(json_encode($params));die;
        $search = $this->clientCon->search($params);
        //_p($search);die;
        $UTMSourceData = $search['aggregations']['UTMSource']['buckets'];
        
        $UTMSourceDataCount = $search['hits']['total'];
        $UTMSourceData = $this->MISCommonLib->prepareDataForBarGraphForTraffic($UTMSourceData,$UTMSourceDataCount,'',0);
        //_p(json_encode($params));die;

        //2. For UTM Campaign
        $UTMFilter =array();
        $UTMFilter['UTMMedium']['terms']['field']= 'utm_medium';
        $UTMFilter['UTMMedium']['terms']['size']= 0;
        $params['body']['aggs'] = $UTMFilter;
        $search = $this->clientCon->search($params);
        $UTMMediumData = $search['aggregations']['UTMMedium']['buckets'];
        $UTMMediumDataCount = $search['hits']['total'];
        $UTMMediumData = $this->MISCommonLib->prepareDataForBarGraphForTraffic($UTMMediumData,$UTMMediumDataCount,'',0);
        //_p(json_encode($params));die;

        //3. For UTM Medium
        $UTMFilter =array();
        $UTMFilter['UTMCampaign']['terms']['field']= 'utm_campaign';
        $UTMFilter['UTMCampaign']['terms']['size']= 0;
        $params['body']['aggs'] = $UTMFilter;
        $search = $this->clientCon->search($params);
        $UTMCampaignData = $search['aggregations']['UTMCampaign']['buckets'];                
        $UTMCampaignDataCount = $search['hits']['total'];
        $UTMCampaignData = $this->MISCommonLib->prepareDataForBarGraphForTraffic($UTMCampaignData,$UTMCampaignDataCount,'',1);
        //_p(json_encode($params));die;

        
        if($flag ==0){
            $inputArray = array(
                            'utmSource' =>$UTMSourceData,
                            'utmMedium' => $UTMMediumData,
                            'utmCampaign' => $UTMCampaignData
                            );
            $barGraph['barGraphData'] = $this->MISCommonLib->prepareTrafficSourceBarGraphForTraffic($inputArray);
            //$barGraph['barGraphData'] = array($UTMSourceData,$UTMCampaignData,$UTMMediumData);
            $barGraph['donutChart']['data'] = $trafficSourceData;
            $barGraph['donutChart']['count'] = $trafficCount;    
        }else{
            $inputArray = array(
                            'utmSource' =>$UTMSourceData,
                            'utmMedium' => $UTMMediumData,
                            'utmCampaign' => $UTMCampaignData
                            );
            $barGraph = $this->MISCommonLib->prepareTrafficSourceBarGraphForTraffic($inputArray);
        }
        return $barGraph;
    }

    private function _prepareDataForDifferentChartsForpgpersess($engagementData,$pageName,$dateRange,$extraData,$isComparision)
    {
        $data = $engagementData['aggregations']['dateWise']['buckets'];
        $i=0;
        $total = 0;
        $mobilePages =0;
        $desktopPages=0;
        if($pageName){
            foreach ($data as $key => $gbd)    // gbd : group by date
            {
                $total += $gbd['doc_count']; 
                $deviceWise = $gbd['siteSourse']['buckets'];
                foreach ($deviceWise as $keyOne => $gbss)   //gbss: group by site source
                {
                        $resData[$i++] = array(
                                            "visitDate" => date("Y-m-d", strtotime($gbd['key_as_string'])),
                                            "siteSource" => ($gbss['key']=='no')?"Desktop":"Mobile",
                                            "visitCount" => $gbss['doc_count'],
                                            "pageviewCount" => $gbss['pageViewsAggs']['value']
                                            );
                        if($gbss['key']=='no')
                        {
                            $mobilePage += $gbss['pageViewsAggs']['value'];
                        }else{
                            $desktopPages  += $gbss['pageViewsAggs']['value'];
                        }
                }
                
            }
        }else{
            foreach ($data as $key => $gbd) // gbd : group by date
            {
                $total += $gbd['doc_count'];
                $deviceWise = $gbd['siteSourse']['buckets'];
                foreach ($deviceWise as $keyOne => $gbss) //gbss: group by site source
                {
                    $pagewise = $gbss['pageWise']['buckets'];
                    foreach ($pagewise as $keyTwo => $gbp) //gbp: group by page
                    {
                        $resData[$i++] = array(
                                            "visitDate" => date("Y-m-d", strtotime($gbd['key_as_string'])),
                                            "siteSource" => ($gbss['key']=='no')?"Desktop":"Mobile",
                                            "pageWise"  => $gbp['key'],
                                            "visitCount" => $gbp['doc_count'],
                                            "pageviewCount" => $gbp['pageViewsAggs']['value']
                                            );
                        if($gbss['key']=='no')
                        {
                            $mobilePage += $gbp['pageViewsAggs']['value'];
                        }else{
                            $desktopPages  += $gbp['pageViewsAggs']['value'];
                        }
                    }
                }
            }
        }
        //_p($resData);die;    
        $gendate = new DateTime();
        $startYear = date('Y', strtotime($dateRange['startDate']));
        $endYear = date('Y', strtotime($dateRange['endDate']));

        if($extraData['view'] == 1){
            $sDate=date_create($dateRange['startDate']);
            $eDate=date_create($dateRange['endDate']);
            $diff = date_diff($sDate,$eDate);
            $dateDiff = $diff->format("%a");
            $lineArray=array();
            $lineArraypw =array();
            $tempDate =$dateRange['startDate'];
            for($i=0;$i<=$dateDiff;$i++){
                $lineArray[$tempDate] =0;
                $lineArraypw[$tempDate] =0;
                $tempDate = date('Y-m-d', strtotime($tempDate . ' +1 day'));
            } 

            foreach ($resData as  $value) {
                    $lineArray[$value['visitDate']] += $value['visitCount'];
                    $lineArraypw[$value['visitDate']] += $value['pageviewCount'];
                    $pieChartDataTwo[$value['siteSource']]+= $value['visitCount'];
                    if($pageName=='')
                    {
                        $page = $value['pageWise'];
                        $page = $this->MISCommonLib->getPageName($page);
                        if($isComparision)
                        {
                            //$pieChartDataThree[$page]+=$value['visitCount'];
                        }else
                        {
                            $prepareTableData[$page][$value['siteSource']]['visitCount']+=$value['visitCount'];
                            $prepareTableDataOne[$page][$value['siteSource']]['visitCount']+=$value['pageviewCount'];
                        }
                        $pieChartDataThree[$page]+=$value['visitCount'];
                        $pieChartDataFour[$page]+=$value['pageviewCount'];
                    }     
            }
        }else if($extraData['view'] == 2){   
            if($startYear == $endYear)
            {
                // creating week array
                $swn = date('W', strtotime($dateRange['startDate']));
                $ewn = date('W', strtotime($dateRange['endDate'])); 
                $lineArray = array();
                $lineArray[$dateRange['startDate']] = 0;//$lineChartData[$swn];

                for ($i=$swn; $i <= $ewn ; $i++) { 
                    $gendate->setISODate($startYear,$i,1); //year , week num , day
                    $df = $gendate->format('Y-m-d');
                    $lineArray[$df] = 0;   
                }
                //_p($lineArray);
                foreach ($resData as $key =>  $value) {
                    $lineArray[$value['visitDate']] += $value['visitCount'];
                    $lineArraypw[$value['visitDate']] += $value['pageviewCount'];
                    $pieChartDataOne[$value['source']]+= $value['visitCount'];
                    $pieChartDataTwo[$value['siteSource']]+= $value['visitCount'];
                    if($pageName=='')
                    {
                        $page = $value['pageWise'];
                        $page = $this->MISCommonLib->getPageName($page);
                        if($isComparision)
                        {
                            //$pieChartDataThree[$page]+=$value['visitCount'];
                        }else
                        {
                            $prepareTableData[$page][$value['siteSource']]['visitCount']+=$value['visitCount'];
                            $prepareTableDataOne[$page][$value['siteSource']]['visitCount']+=$value['pageviewCount'];
                        }
                        $pieChartDataThree[$page]+=$value['visitCount'];
                        $pieChartDataFour[$page]+=$value['pageviewCount'];
                    }
                }
                //_p($lineArray);die;
                // for managing startDate traffic data
                $gendate->setISODate($startYear,$swn,1); //year , week num , day
                $df = $gendate->format('Y-m-d');
                if(($lineArray[$dateRange['startDate']] ==0) && ($dateRange['startDate'] != $df)){
                    $lineArray[$dateRange['startDate']] = $lineArray[$df];
                    unset($lineArray[$df]);
                } 
                //_p($lineArray);_p($pieChartDataOne);_p($pieChartDataTwo);die;
            }
            else
            {
                $swn = date('W', strtotime($dateRange['startDate']));
                $ewn =date('W', strtotime($startYear."-12-31"));
                if($ewn == 1){
                    $ewn = date('W', strtotime($startYear."-12-24"));
                }
                $swn1 = 1;
                $ewn1 =date('W', strtotime($dateRange['endDate']));
                $gendate->setISODate($startYear,$ewn,7); //year , week num , day
                $tempDate = $gendate->format('Y-m-d');
                if($tempDate >= $dateRange['endDate']){
                    $swn1 =0;
                    $ewn1 =-1;
                }
                //_p($swn);_p($ewn); _p($swn1);_p($ewn1);
                $lineArray = array();
                $lineArray[$dateRange['startDate']] = 0;//$lineChartData[$swn];
                for ($i=$swn; $i <= $ewn ; $i++) { 
                    $gendate->setISODate($startYear,$i,1); //year , week num , day
                    $df = $gendate->format('Y-m-d');
                    $lineArray[$df] = 0;
                }
                for ($i=$swn1; $i <= $ewn1 ; $i++) { 
                    $gendate->setISODate($endYear,$i,1); //year , week num , day
                    $df = $gendate->format('Y-m-d');
                    $lineArray[$df] = 0;   
                }
                //_p($lineArray);
                foreach ($resData as $key =>  $value) {
                    $lineArray[$value['visitDate']] += $value['visitCount'];  
                    $lineArraypw[$value['visitDate']] += $value['pageviewCount'];
                    $pieChartDataOne[$value['siteSource']]+= $value['visitCount'];
                    $pieChartDataTwo[$value['type']]+= $value['visitCount'];
                    if($pageName=='')
                    {
                        $page = $value['pageWise'];
                        $page = $this->MISCommonLib->getPageName($page);
                        if($isComparision)
                        {
                            //$pieChartDataThree[$page]+=$value['visitCount'];
                        }else
                        {
                            $prepareTableData[$page][$value['siteSource']]['visitCount']+=$value['visitCount'];
                            $prepareTableDataOne[$page][$value['siteSource']]['visitCount']+=$value['pageviewCount'];
                        }
                        $pieChartDataThree[$page]+=$value['visitCount'];
                        $pieChartDataFour[$page]+=$value['pageviewCount'];
                    }
            
                }
                //_p($lineArray);
                // for managing startDate traffic data
                $gendate->setISODate($startYear,$swn,1); //year , week num , day
                $df = $gendate->format('Y-m-d');
                if(($lineArray[$dateRange['startDate']] ==0) && ($dateRange['startDate'] != $df)){
                    $lineArray[$dateRange['startDate']] = $lineArray[$df];
                    unset($lineArray[$df]);
                } 
                //_p($lineArray);die;
            }    
        }else if($extraData['view'] == 3){   
            if($startYear == $endYear){
                $smn = date('m', strtotime($dateRange['startDate']));
                $emn = date('m', strtotime($dateRange['endDate']));
                $lineArray = array();
                $df = $startYear.'-'.$smn.'-01';
                $lineArray[$df] = 0;
                $lineArray[$dateRange['startDate']] = 0;       
                for ($i=$smn+1; $i <= $emn ; $i++){
                    if($i <= 9){
                        $i = '0'.$i;
                        $df = $startYear.'-'.$i.'-01';
                    }else{
                        $df = $startYear.'-'.$i.'-01';    
                    }
                    $lineArray[$df] = 0;       
                }
                //_p($lineArray);
                foreach ($resData as  $value) {
                    $lineArray[$value['visitDate']] += $value['visitCount']; 
                    $lineArraypw[$value['visitDate']] += $value['pageviewCount'];
                    $pieChartDataOne[$value['source']]+= $value['visitCount'];
                    $pieChartDataTwo[$value['siteSource']]+= $value['visitCount'];
                    if($pageName=='')
                    {
                        $page = $value['pageWise'];
                        $page = $this->MISCommonLib->getPageName($page);
                        if($isComparision)
                        {
                            //$pieChartDataThree[$page]+=$value['visitCount'];
                        }else
                        {
                            $prepareTableData[$page][$value['siteSource']]['visitCount']+=$value['visitCount'];
                            $prepareTableDataOne[$page][$value['siteSource']]['visitCount']+=$value['pageviewCount'];
                        }
                        $pieChartDataThree[$page]+=$value['visitCount'];
                        $pieChartDataFour[$page]+=$value['pageviewCount'];
                    }
                }
                //_p($pieChartDataThree);die;
                $df = $startYear.'-'.$smn.'-01';
                if(($lineArray[$dateRange['startDate']] == 0) && ($dateRange['startDate'] != $df)){
                    $lineArray[$dateRange['startDate']] = $lineArray[$df];
                    unset($lineArray[$df]);
                }
            }else{
                $smn = date('m', strtotime($dateRange['startDate']));
                $emn = 12;
                $smn1 = 1;
                $emn1 =date('m', strtotime($dateRange['endDate']));
                //_p($smn);_p($emn);_p($smn1);_p($emn1);
                $lineArray = array();
                $lineArray[$dateRange['startDate']] = 0;
                $daten = $dateRange['startDate'];
                $mnp =0;
                $mnn =0;
                $y = date('Y', strtotime($resData[0]['visitDate']));
                $flag = 0;
                $sd='';
                for($i=$startYear; $i<=$endYear;$i++)
                {
                    
                    if($i == $startYear){
                        $sm =$smn;    
                    }else{
                        $sm =1;
                    }

                    if($i == $endYear){
                        $em = $emn1;
                    }else{
                        $em =12;
                    }
                    
                    for($j=$sm;$j<=$em;$j++)
                    {
                        if($j <= 9)
                        {
                            $daten = $i.'-0'.$j.'-01';
                        }else{
                            $daten = $i.'-'.$j.'-01';
                        }  
                        if($flag == 0)
                        {
                            $sd=$daten;
                            $flag=1;
                        }
                        $lineArray[$daten] = 0;
                    }
                }
                //_p($lineArray);
                foreach ($resData as  $value) {
                    $lineArray[$value['visitDate']] += $value['visitCount']; 
                    $lineArraypw[$value['visitDate']] += $value['pageviewCount'];
                    $pieChartDataOne[$value['source']]+= $value['visitCount'];
                    $pieChartDataTwo[$value['siteSource']]+= $value['visitCount'];
                    if($pageName=='')
                    {
                        $page = $value['pageWise'];
                        $page = $this->MISCommonLib->getPageName($page);
                        if($isComparision)
                        {
                            //$pieChartDataThree[$page]+=$value['visitCount'];
                        }else
                        {
                            $prepareTableData[$page][$value['siteSource']]['visitCount']+=$value['visitCount'];
                            $prepareTableDataOne[$page][$value['siteSource']]['visitCount']+=$value['pageviewCount'];
                        }
                        $pieChartDataThree[$page]+=$value['visitCount'];
                        $pieChartDataFour[$page]+=$value['pageviewCount'];
                    }
                }
                $df = $startYear.'-'.$smn.'-01';
                if(($lineArray[$dateRange['startDate']] == 0) && ($dateRange['startDate'] != $df)){
                    $lineArray[$dateRange['startDate']] = $lineArray[$df];
                    unset($lineArray[$df]);
                }
                //_p($lineArray);_p($pieChartDataOne);_p($pieChartDataTwo);die;
            }
        } 

        /*
            _p($lineArray);
            _p('--------p1-------------------');
            _p($pieChartDataOne);
            _p('----------p2-----------------');
            _p($pieChartDataTwo);
            _p('----------p3-----------------');
            _p($pieChartDataThree);
            _p('------p4---------------------');
            _p($pieChartDataFour);
            _p('---------------------------');
            _p($prepareTableData);
            _p('---------------------------');
            _p($prepareTableDataOne);
            _p('-------------------------');
            _p($total);
            die;
        */
         
        foreach ($lineArray as $key => $value) {
            if($extraData['engagementType'] == 'pgpersess'){
                $lineChartDataForpps[$key] =  number_format((($lineArraypw[$key])/($value)), 2, '.', '');
            }else{
                $lineChartDataForpps[$key] =  number_format((($lineArraypw[$key])/(60*$value)), 2, '.', '');
            }
        }
        //_p($lineChartDataForpps);die;
        
        $lineChartArray = $this->prepareDataForLineChart($lineChartDataForpps);

        if($extraData['engagementType'] == 'pgpersess'){
            $deviceWise = array(
                        'Mobile' => number_format((($mobilePage)/$pieChartDataTwo['Mobile']), 2, '.', ''),
                        'Desktop' => number_format((($desktopPages)/$pieChartDataTwo['Desktop']), 2, '.', '')
            );
        }else{
            $deviceWise = array(
                        'Mobile' => number_format((($mobilePage)/(60*$pieChartDataTwo['Mobile'])), 2, '.', ''),
                        'Desktop' => number_format((($desktopPages)/(60*$pieChartDataTwo['Desktop'])), 2, '.', '')
            );
        }
        
        //_p($deviceWise);die;
        $temp = $engagementData['aggregations']['users'];
        if($extraData['engagementType'] == 'pgpersess'){
            $userWise['Non Loggedin'] =  number_format((($temp['userPageview']['value'])/$temp['doc_count']), 2, '.', '');
        }else{
            $userWise['Non Loggedin'] =  number_format((($temp['userPageview']['value'])/(60*$temp['doc_count'])), 2, '.', '');
        }
        
        //_p($engagementData['aggregations']['users']);die;
        $totalpps = $mobilePage + $desktopPages;
        $desktoppps = $totalpps - $temp['userPageview']['value'];
        $desktopview = $total - $temp['doc_count'];
        if($extraData['engagementType'] == 'pgpersess'){
            $userWise['Loggedin'] =  number_format((($desktoppps)/$desktopview), 2, '.', '');
        }else{
            $userWise['Loggedin'] =  number_format((($desktoppps)/(60*$desktopview)), 2, '.', '');
        }
        //_p($userWise);die;

        if($pageName=='' && !$isComparision){
            $pieChartDataThree[$value['pageWise']]+=$value['visitCount'];
            $donutChartDataByPage   = $this->prepareDataForDonutChart($pieChartDataThree,$colorCodes,$total);
        }else if($pageName=='' && $isComparision){
            foreach ($pieChartDataFour as $key => $value) {
                //_p($value);die;
                if($extraData['engagementType'] == 'pgpersess'){
                    $pageWise[$key] = number_format((($value)/$pieChartDataThree[$key]), 2, '.', '');
                }else{
                    $pageWise[$key] = number_format((($value)/(60*$pieChartDataThree[$key])), 2, '.', '');
                }
            }
        }

        $engData['dataForDifferentCharts']['lineChartData'] = $lineChartArray;

        if($extraData['engagementType'] == 'pgpersess'){
            $engData['pgps'] =  number_format((($totalpps)/($total)), 2, '.', '');
        }else{
            $engData['pgps'] =  number_format((($totalpps)/(60*$total)), 2, '.', '');   
        }

        $deviceWiseBR = $this->_prepareDataForBargraphForpgps($deviceWise,$extraData);
        $userWiseBR = $this->_prepareDataForBargraphForpgps($userWise,$extraData);
        //_p($isComparision);die;
        if(!$isComparision){
        $a = array();
        foreach($prepareTableData as $key=>$v){
            $mobile ='';
            $Desktop = '';
            if($extraData['engagementType']  == 'avgsessdur'){
                $mobile = number_format((($prepareTableDataOne[$key]['Mobile']['visitCount'])/(60*$v['Mobile']['visitCount'])), 2, '.', '');
                $desktop = number_format((($prepareTableDataOne[$key]['Desktop']['visitCount'])/(60*$v['Desktop']['visitCount'])), 2, '.', '');
            }else{
                $mobile = number_format((($prepareTableDataOne[$key]['Mobile']['visitCount'])/$v['Mobile']['visitCount']), 2, '.', '');
                $desktop = number_format((($prepareTableDataOne[$key]['Desktop']['visitCount'])/$v['Desktop']['visitCount']), 2, '.', '');    
                }

                
                $a[$key] = array(
                                'Desktop' => array(
                                                'visitCount' => $desktop
                                                    ),
                                'Mobile' => array(
                                            'visitCount' => $mobile
                                                )
                                );
            }
            $datatable = $this->_prepareDataForDataTableForBounce($a);
        }else{
            $pagewiseBR = $this->_prepareDataForBargraphForpgps($pageWise,$extraData);
        }
        
        if($pageName){
            $engData['dataForDifferentCharts']['barGraphData'] = array(
                                                            'deviceWise' => $deviceWiseBR,
                                                            'userWise' => $userWiseBR
                                                            );//array($deviceWise,$userWise); 
        }else if($pageName =='' && !$isComparision){
            $engData['dataForDifferentCharts']['barGraphData'] = array(
                                                            'deviceWise' => $deviceWiseBR,
                                                            'userWise' => $userWiseBR,
                                                            );
            $engData['dataForDifferentCharts']['dataForDataTable'] = $datatable;
        }else if($pageName =='' && $isComparision){
            $engData['dataForDifferentCharts']['barGraphData'] = array(
                                                            'deviceWise' => $deviceWiseBR,
                                                            'userWise' => $userWiseBR,
                                                            'pageWise' => $pagewiseBR
                                                            ); 
        }

        //_p($engagementData['DonutChartData']);die;
        //_p($engData['dataForDifferentCharts']['barGraphData']);die;
        //_p($engData['dataForDifferentCharts']['barGraphData']);die;
        return $engData;
    }

    private function _prepareDataForDataTableForpgps($pageWise,$extraData)
    {

        if($extraData['engagementType'] == 'avgsessdur'){
            $dataTableHeading = "Avg Session Duration";
        }else if($extraData['engagementType'] == 'bounce'){
            $dataTableHeading = "Bounce Rate";
        }else{
            $dataTableHeading = "Pages/Sessions";    
        }
    
        //$coloumHeading = $this->getColoumHeading($pageName);
        $dataTable = '<thead>'.
                        '<tr class="headings">'.
                            '<th style="padding-left:20px">'.
                                '<input type="checkbox" class="tableflat">'.
                            '</th>'.
                            '<th style="padding-left:20px">'.'Page </th>'.
                            '<th style="padding-left:20px">'.$dataTableHeading.'</th>'.
                        '</tr>'.
                    '</thead>'.
                    '<tbody>';
        $prepareDataForCSV[0]  = array('Page',$dataTableHeading);
        $i=1;
        foreach ($pageWise as $key => $value) {
            $dataTable = $dataTable.
                    '<tr class="even pointer">'.
                        '<td class="a-center ">'.
                            '<input type="checkbox" class="tableflat">'.
                        '</td>'.
                        '<td class=" ">'.$key.'</td>'.
                        '<td class=" ">'.$value.'</td>'.
                    '</tr>';
                $prepareDataForCSV[$i++] = array($key,$value);
        }
        $dataTable = $dataTable.'</tbody>';
        $DataForDataTable = array($dataTableHeading,$dataTable,$prepareDataForCSV);
        //_p($DataForDataTable);die;
        return $DataForDataTable;     
    }

    private function _prepareDataForDifferentChartsForBounce($engagementData,$pageName,$dateRange,$extraData,$isComparision,$colorCodes)
    {
        $data = $engagementData['aggregations']['dateWise']['buckets'];
        $nonLoggedInUsers  = $engagementData['aggregations']['users']['doc_count'];
        $totalUsers= $engagementData['hits']['total'];
        $loggedInUsers = $totalUsers - $nonLoggedInUsers;
        $pieChartDataOne = array(
                            'Loggedin' => $loggedInUsers,
                            'Non Loggedin' => $nonLoggedInUsers
                            );

        $i=0;
        $total = 0;
        if($pageName){
            foreach ($data as $key => $gbd)    // gbd : group by date
            {
                $total += $gbd['doc_count']; 
                $deviceWise = $gbd['siteSourse']['buckets'];
                foreach ($deviceWise as $keyOne => $gbss)   //gbss: group by site source
                {
                        $resData[$i++] = array(
                                            "visitDate" => date("Y-m-d", strtotime($gbd['key_as_string'])),
                                            "siteSource" => ($gbss['key']=='no')?"Desktop":"Mobile",
                                            "visitCount" => $gbss['doc_count']
                                            );
                }
                
            }
        }else{
            foreach ($data as $key => $gbd) // gbd : group by date
            {
                $total += $gbd['doc_count'];
                $deviceWise = $gbd['siteSourse']['buckets'];
                foreach ($deviceWise as $keyOne => $gbss) //gbss: group by site source
                {
                    $pagewise = $gbss['pageWise']['buckets'];
                    foreach ($pagewise as $keyTwo => $gbp) //gbp: group by page
                    {
                        $resData[$i++] = array(
                                            "visitDate" => date("Y-m-d", strtotime($gbd['key_as_string'])),
                                            "siteSource" => ($gbss['key']=='no')?"Desktop":"Mobile",
                                            "pageWise"  => $gbp['key'],
                                            "visitCount" => $gbp['doc_count']
                                            );
                    }
                }
            }
        }
        
        $gendate = new DateTime();
        $startYear = date('Y', strtotime($dateRange['startDate']));
        $endYear = date('Y', strtotime($dateRange['endDate']));

        if($extraData['view'] == 1){
            $sDate=date_create($dateRange['startDate']);
            $eDate=date_create($dateRange['endDate']);
            $diff = date_diff($sDate,$eDate);
            $dateDiff = $diff->format("%a");
            $lineArray=array();
            $tempDate =$dateRange['startDate'];
            for($i=0;$i<=$dateDiff;$i++){
                $lineArray[$tempDate] =0;
                $tempDate = date('Y-m-d', strtotime($tempDate . ' +1 day'));
            } 

            foreach ($resData as  $value) {
                    $lineArray[$value['visitDate']] += $value['visitCount'];    
                    $pieChartDataTwo[$value['siteSource']]+= $value['visitCount'];
                    if($pageName=='')
                    {
                        $page = $value['pageWise'];
                        if($page =='savedcoursespage' || $page =='savedcoursepage')
                        {
                            $page = 'savedcoursespage';
                        }
                        $page = $this->MISCommonLib->getPageName($page);
                        if($isComparision)
                        {
                            //$pieChartDataThree[$page]+=$value['visitCount'];
                        }else
                        {
                            $prepareTableData[$page][$value['siteSource']]['visitCount']+=$value['visitCount'];
                        }
                        $pieChartDataThree[$page]+=$value['visitCount'];
                    }     
            }
        }else if($extraData['view'] == 2){   
            if($startYear == $endYear)
            {
                // creating week array
                $swn = date('W', strtotime($dateRange['startDate']));
                $ewn = date('W', strtotime($dateRange['endDate'])); 
                $lineArray = array();
                $lineArray[$dateRange['startDate']] = 0;//$lineChartData[$swn];

                for ($i=$swn; $i <= $ewn ; $i++) { 
                    $gendate->setISODate($startYear,$i,1); //year , week num , day
                    $df = $gendate->format('Y-m-d');
                    $lineArray[$df] = 0;   
                }
                //_p($lineArray);
                foreach ($resData as $key =>  $value) {
                    $lineArray[$value['visitDate']] += $value['visitCount'];
                    //$pieChartDataOne[$value['source']]+= $value['visitCount'];
                    $pieChartDataTwo[$value['siteSource']]+= $value['visitCount'];
                    if($pageName=='')
                    {
                        $page = $value['pageWise'];
                        if($page =='savedcoursespage' || $page =='savedcoursepage')
                        {
                            $page = 'savedcoursespage';
                        }
                        $page = $this->MISCommonLib->getPageName($page);
                        if($isComparision)
                        {
                            //$pieChartDataThree[$page]+=$value['visitCount'];
                        }else
                        {
                            $prepareTableData[$page][$value['siteSource']]['visitCount']+=$value['visitCount'];
                        }
                        $pieChartDataThree[$page]+=$value['visitCount'];
                    } 
                }
                //_p($lineArray);die;
                // for managing startDate traffic data
                $gendate->setISODate($startYear,$swn,1); //year , week num , day
                $df = $gendate->format('Y-m-d');
                if(($lineArray[$dateRange['startDate']] ==0) && ($dateRange['startDate'] != $df)){
                    $lineArray[$dateRange['startDate']] = $lineArray[$df];
                    unset($lineArray[$df]);
                } 
                //_p($lineArray);_p($pieChartDataOne);_p($pieChartDataTwo);die;
            }
            else
            {
                $swn = date('W', strtotime($dateRange['startDate']));
                $ewn =date('W', strtotime($startYear."-12-31"));
                if($ewn == 1){
                    $ewn = date('W', strtotime($startYear."-12-24"));
                }
                $swn1 = 1;
                $ewn1 =date('W', strtotime($dateRange['endDate']));
                $gendate->setISODate($startYear,$ewn,7); //year , week num , day
                $tempDate = $gendate->format('Y-m-d');
                if($tempDate >= $dateRange['endDate']){
                    $swn1 =0;
                    $ewn1 =-1;
                }
                //_p($swn);_p($ewn); _p($swn1);_p($ewn1);
                $lineArray = array();
                $lineArray[$dateRange['startDate']] = 0;//$lineChartData[$swn];
                for ($i=$swn; $i <= $ewn ; $i++) { 
                    $gendate->setISODate($startYear,$i,1); //year , week num , day
                    $df = $gendate->format('Y-m-d');
                    $lineArray[$df] = 0;
                }
                for ($i=$swn1; $i <= $ewn1 ; $i++) { 
                    $gendate->setISODate($endYear,$i,1); //year , week num , day
                    $df = $gendate->format('Y-m-d');
                    $lineArray[$df] = 0;   
                }
                //_p($lineArray);
                foreach ($resData as $key =>  $value) {
                    $lineArray[$value['visitDate']] += $value['visitCount'];  
                    $pieChartDataOne[$value['siteSource']]+= $value['visitCount'];
                    //$pieChartDataTwo[$value['type']]+= $value['visitCount'];
                    if($pageName=='')
                    {
                        $page = $value['pageWise'];
                        if($page =='savedcoursespage' || $page =='savedcoursepage')
                        {
                            $page = 'savedcoursespage';
                        }
                        $page = $this->MISCommonLib->getPageName($page);
                        if($isComparision)
                        {
                            //$pieChartDataThree[$page]+=$value['visitCount'];
                        }else
                        {
                            $prepareTableData[$page][$value['siteSource']]['visitCount']+=$value['visitCount'];
                        }
                        $pieChartDataThree[$page]+=$value['visitCount'];
                    } 
            
                }
                //_p($lineArray);
                // for managing startDate traffic data
                $gendate->setISODate($startYear,$swn,1); //year , week num , day
                $df = $gendate->format('Y-m-d');
                if(($lineArray[$dateRange['startDate']] ==0) && ($dateRange['startDate'] != $df)){
                    $lineArray[$dateRange['startDate']] = $lineArray[$df];
                    unset($lineArray[$df]);
                } 
                //_p($lineArray);die;
            }    
        }else if($extraData['view'] == 3){   
            if($startYear == $endYear){
                $smn = date('m', strtotime($dateRange['startDate']));
                $emn = date('m', strtotime($dateRange['endDate']));
                $lineArray = array();
                $df = $startYear.'-'.$smn.'-01';
                $lineArray[$df] = 0;
                $lineArray[$dateRange['startDate']] = 0;       
                for ($i=$smn+1; $i <= $emn ; $i++){
                    if($i <= 9){
                        $i = '0'.$i;
                        $df = $startYear.'-'.$i.'-01';
                    }else{
                        $df = $startYear.'-'.$i.'-01';    
                    }
                    $lineArray[$df] = 0;       
                }
                //_p($lineArray);
                foreach ($resData as  $value) {
                    $lineArray[$value['visitDate']] += $value['visitCount']; 
                    //$pieChartDataOne[$value['source']]+= $value['visitCount'];
                    $pieChartDataTwo[$value['siteSource']]+= $value['visitCount'];
                    if($pageName=='')
                    {
                        $page = $value['pageWise'];
                        if($page =='savedcoursespage' || $page =='savedcoursepage')
                        {
                            $page = 'savedcoursespage';
                        }
                        $page = $this->MISCommonLib->getPageName($page);
                        if($isComparision)
                        {
                            //$pieChartDataThree[$page]+=$value['visitCount'];
                        }else
                        {
                            $prepareTableData[$page][$value['siteSource']]['visitCount']+=$value['visitCount'];
                        }
                        $pieChartDataThree[$page]+=$value['visitCount'];
                    } 
                }
                //_p($pieChartDataThree);die;
                $df = $startYear.'-'.$smn.'-01';
                if(($lineArray[$dateRange['startDate']] == 0) && ($dateRange['startDate'] != $df)){
                    $lineArray[$dateRange['startDate']] = $lineArray[$df];
                    unset($lineArray[$df]);
                }
            }else{
                $smn = date('m', strtotime($dateRange['startDate']));
                $emn = 12;
                $smn1 = 1;
                $emn1 =date('m', strtotime($dateRange['endDate']));
                //_p($smn);_p($emn);_p($smn1);_p($emn1);
                $lineArray = array();
                $lineArray[$dateRange['startDate']] = 0;
                $daten = $dateRange['startDate'];
                $mnp =0;
                $mnn =0;
                $y = date('Y', strtotime($resData[0]['visitDate']));
                $flag = 0;
                $sd='';
                for($i=$startYear; $i<=$endYear;$i++)
                {
                    
                    if($i == $startYear){
                        $sm =$smn;    
                    }else{
                        $sm =1;
                    }

                    if($i == $endYear){
                        $em = $emn1;
                    }else{
                        $em =12;
                    }
                    
                    for($j=$sm;$j<=$em;$j++)
                    {
                        if($j <= 9)
                        {
                            $daten = $i.'-0'.$j.'-01';
                        }else{
                            $daten = $i.'-'.$j.'-01';
                        }  
                        if($flag == 0)
                        {
                            $sd=$daten;
                            $flag=1;
                        }
                        $lineArray[$daten] = 0;
                    }
                }
                //_p($lineArray);
                foreach ($resData as  $value) {
                    $lineArray[$value['visitDate']] += $value['visitCount']; 
                    //$pieChartDataOne[$value['source']]+= $value['visitCount'];
                    $pieChartDataTwo[$value['siteSource']]+= $value['visitCount'];
                    if($pageName=='')
                    {
                        $page = $value['pageWise'];
                        if($page =='savedcoursespage' || $page =='savedcoursepage')
                        {
                            $page = 'savedcoursespage';
                        }
                        $page = $this->MISCommonLib->getPageName($page);
                        if($isComparision)
                        {
                            //$pieChartDataThree[$page]+=$value['visitCount'];
                        }else
                        {
                            $prepareTableData[$page][$value['siteSource']]['visitCount']+=$value['visitCount'];
                        }
                        $pieChartDataThree[$page]+=$value['visitCount'];
                    } 
                }
                $df = $startYear.'-'.$smn.'-01';
                if(($lineArray[$dateRange['startDate']] == 0) && ($dateRange['startDate'] != $df)){
                    $lineArray[$dateRange['startDate']] = $lineArray[$df];
                    unset($lineArray[$df]);
                }
                //_p($lineArray);_p($pieChartDataOne);_p($pieChartDataTwo);die;
            }
        } 

        /*
            _p($lineArray);
            _p('---------------------------');
            _p($pieChartDataOne);
            _p('---------------------------');
            _p($pieChartDataTwo);
            _p('---------------------------');
            _p($pieChartDataThree);
            _p('---------------------------');
            _p($prepareTableData);
            _p('---------------------------');
            _p($total);
            die;
        */
         
        //$lineChartArray[$i++] = array(date("Y-m-d", strtotime($value['key_as_string'])),$value['doc_count']);
        $lineChartArray = $this->prepareDataForLineChart($lineArray);
        
        if($pageName=='' && $isComparision){
            //$pieChartDataThree[$value['pageWise']]+=$value['visitCount'];
            $donutChartDataByPage   = $this->prepareDataForDonutChart($pieChartDataThree,$colorCodes,$total);
        }else if($pageName=='' && !$isComparision){
            //_p($prepareTableData);die;
            //$engData['dataForDifferentCharts']['DataForDataTable'] = $this->_prepareDataForDataTableForEngagement($prepareTableData,$pageName,$total);
        }

        $engData['dataForDifferentCharts']['lineChartData'] = $lineChartArray;

        if($pageName){
            $engData['dataForDifferentCharts']['dataForDataTable'] = $prepareTableData;
            $engData['dataForDifferentCharts']['barGraph'] =array($pieChartDataOne,$pieChartDataTwo); 
        }else if($pageName =='' && $isComparision){
            $engData['dataForDifferentCharts']['barGraph'] =array($pieChartDataOne,$pieChartDataTwo,$pieChartDataThree);     
        }else if($pageName =='' && !$isComparision){
            $engData['dataForDifferentCharts']['dataForDataTable'] = $prepareTableData;
            $engData['dataForDifferentCharts']['barGraph'] =array($pieChartDataOne,$pieChartDataTwo); 
        }
        
        //_p($engagementData['DonutChartData']);die;
        //_p($engData);die;
        return $engData;
    }

    private function _prepareDataForDifferentChartsForEngagement($engagementData,$pageName,$dateRange,$extraData,$isComparision,$colorCodes)
    {
        $data = $engagementData['pageViews']['result']['aggregations']['dateWise']['buckets'];
        //_p($data);die;
        $i=0;
        $total = 0;
        if($pageName){
            foreach ($data as $key => $gbd)    // gbd : group by date
            {
                $total += $gbd['doc_count']; 
                $deviceWise = $gbd['siteSourse']['buckets'];
                foreach ($deviceWise as $keyOne => $gbss)   //gbss: group by site source
                {
                        $resData[$i++] = array(
                                            "visitDate" => date("Y-m-d", strtotime($gbd['key_as_string'])),
                                            "siteSource" => ($gbss['key']=='no')?"Desktop":"Mobile",
                                            "visitCount" => $gbss['doc_count']
                                            );
                }     
            }
        }else{
            foreach ($data as $key => $gbd) // gbd : group by date
            {
                $total += $gbd['doc_count'];
                $deviceWise = $gbd['siteSourse']['buckets'];
                foreach ($deviceWise as $keyOne => $gbss) //gbss: group by site source
                {
                    $pagewise = $gbss['pageWise']['buckets'];
                    foreach ($pagewise as $keyTwo => $gbp) //gbp: group by page
                    {
                        $resData[$i++] = array(
                                            "visitDate" => date("Y-m-d", strtotime($gbd['key_as_string'])),
                                            "siteSource" => ($gbss['key']=='no')?"Desktop":"Mobile",
                                            "pageWise"  => $gbp['key'],
                                            "visitCount" => $gbp['doc_count']
                                            );
                    }
                }
            }
        }
        //_p($resData);die;    
        $gendate = new DateTime();
        $startYear = date('Y', strtotime($dateRange['startDate']));
        $endYear = date('Y', strtotime($dateRange['endDate']));

        if($extraData['view'] == 1){
            $sDate=date_create($dateRange['startDate']);
            $eDate=date_create($dateRange['endDate']);
            $diff = date_diff($sDate,$eDate);
            $dateDiff = $diff->format("%a");
            $lineArray=array();
            $tempDate =$dateRange['startDate'];
            for($i=0;$i<=$dateDiff;$i++){
                $lineArray[$tempDate] =0;
                $tempDate = date('Y-m-d', strtotime($tempDate . ' +1 day'));
            } 

            foreach ($resData as  $value) {
                    $lineArray[$value['visitDate']] += $value['visitCount'];    
                    $pieChartDataTwo[$value['siteSource']]+= $value['visitCount'];
                    if($pageName=='')
                    {
                        $page = $value['pageWise'];
                        $page = $this->MISCommonLib->getPageName($page);
                        if($isComparision)
                        {
                            $pieChartDataThree[$page]+=$value['visitCount'];
                        }else
                        {
                            $prepareTableData[$page][$value['siteSource']]['visitCount']+=$value['visitCount'];
                        }  
                    }     
            }
        }else if($extraData['view'] == 2){   
            if($startYear == $endYear)
            {
                // creating week array
                $swn = date('W', strtotime($dateRange['startDate']));
                $ewn = date('W', strtotime($dateRange['endDate'])); 
                $lineArray = array();
                $lineArray[$dateRange['startDate']] = 0;//$lineChartData[$swn];

                for ($i=$swn; $i <= $ewn ; $i++) { 
                    $gendate->setISODate($startYear,$i,1); //year , week num , day
                    $df = $gendate->format('Y-m-d');
                    $lineArray[$df] = 0;   
                }
                //_p($lineArray);
                foreach ($resData as $key =>  $value) {
                    $lineArray[$value['visitDate']] += $value['visitCount'];
                    //$pieChartDataOne[$value['source']]+= $value['visitCount'];
                    $pieChartDataTwo[$value['siteSource']]+= $value['visitCount'];
                    if($pageName==''){
                        $page = $value['pageWise'];
                        $page = $this->MISCommonLib->getPageName($page);
                        //$pieChartDataThree[$page]+=$value['visitCount'];
                        if($isComparision)
                        {
                            $pieChartDataThree[$page]+=$value['visitCount'];
                        }else
                        {
                            $prepareTableData[$page][$value['siteSource']]['visitCount']+=$value['visitCount'];
                        }
                    }
                }
                //_p($lineArray);die;
                // for managing startDate traffic data
                $gendate->setISODate($startYear,$swn,1); //year , week num , day
                $df = $gendate->format('Y-m-d');
                if(($lineArray[$dateRange['startDate']] ==0) && ($dateRange['startDate'] != $df)){
                    $lineArray[$dateRange['startDate']] = $lineArray[$df];
                    unset($lineArray[$df]);
                } 
                //_p($lineArray);_p($pieChartDataOne);_p($pieChartDataTwo);die;
            }
            else
            {
                $swn = date('W', strtotime($dateRange['startDate']));
                $ewn =date('W', strtotime($startYear."-12-31"));
                if($ewn == 1){
                    $ewn = date('W', strtotime($startYear."-12-24"));
                }
                $swn1 = 1;
                $ewn1 =date('W', strtotime($dateRange['endDate']));
                $gendate->setISODate($startYear,$ewn,7); //year , week num , day
                $tempDate = $gendate->format('Y-m-d');
                if($tempDate >= $dateRange['endDate']){
                    $swn1 =0;
                    $ewn1 =-1;
                }
                //_p($swn);_p($ewn); _p($swn1);_p($ewn1);
                $lineArray = array();
                $lineArray[$dateRange['startDate']] = 0;//$lineChartData[$swn];
                for ($i=$swn; $i <= $ewn ; $i++) { 
                    $gendate->setISODate($startYear,$i,1); //year , week num , day
                    $df = $gendate->format('Y-m-d');
                    $lineArray[$df] = 0;
                }
                for ($i=$swn1; $i <= $ewn1 ; $i++) { 
                    $gendate->setISODate($endYear,$i,1); //year , week num , day
                    $df = $gendate->format('Y-m-d');
                    $lineArray[$df] = 0;   
                }
                //_p($lineArray);
                foreach ($resData as $key =>  $value) {
                    $lineArray[$value['visitDate']] += $value['visitCount'];  
                    $pieChartDataOne[$value['siteSource']]+= $value['visitCount'];
                    $pieChartDataTwo[$value['type']]+= $value['visitCount'];
                    if($pageName=='')
                    {
                        $page = $value['pageWise'];
                        $page = $this->MISCommonLib->getPageName($page);
                        if($isComparision)
                        {
                            $pieChartDataThree[$page]+=$value['visitCount'];
                        }else
                        {
                            $prepareTableData[$page][$value['siteSource']]['visitCount']+=$value['visitCount'];
                        }
                    }
            
                }
                //_p($lineArray);
                // for managing startDate traffic data
                $gendate->setISODate($startYear,$swn,1); //year , week num , day
                $df = $gendate->format('Y-m-d');
                if(($lineArray[$dateRange['startDate']] ==0) && ($dateRange['startDate'] != $df)){
                    $lineArray[$dateRange['startDate']] = $lineArray[$df];
                    unset($lineArray[$df]);
                } 
                //_p($lineArray);die;
            }    
        }else if($extraData['view'] == 3){   
            if($startYear == $endYear){
                $smn = date('m', strtotime($dateRange['startDate']));
                $emn = date('m', strtotime($dateRange['endDate']));
                $lineArray = array();
                $df = $startYear.'-'.$smn.'-01';
                $lineArray[$df] = 0;
                $lineArray[$dateRange['startDate']] = 0;       
                for ($i=$smn+1; $i <= $emn ; $i++){
                    if($i <= 9){
                        $i = '0'.$i;
                        $df = $startYear.'-'.$i.'-01';
                    }else{
                        $df = $startYear.'-'.$i.'-01';    
                    }
                    $lineArray[$df] = 0;       
                }
                //_p($lineArray);
                foreach ($resData as  $value) {
                    $lineArray[$value['visitDate']] += $value['visitCount']; 
                    //$pieChartDataOne[$value['source']]+= $value['visitCount'];
                    $pieChartDataTwo[$value['siteSource']]+= $value['visitCount'];
                    if($pageName=='')
                    {
                        $page = $value['pageWise'];
                        $page = $this->MISCommonLib->getPageName($page);
                        if($isComparision)
                        {
                            $pieChartDataThree[$page]+=$value['visitCount'];
                        }else
                        {
                            $prepareTableData[$page][$value['siteSource']]['visitCount']+=$value['visitCount'];
                        }
                    }
                }
                //_p($pieChartDataThree);die;
                $df = $startYear.'-'.$smn.'-01';
                if(($lineArray[$dateRange['startDate']] == 0) && ($dateRange['startDate'] != $df)){
                    $lineArray[$dateRange['startDate']] = $lineArray[$df];
                    unset($lineArray[$df]);
                }
            }else{
                $smn = date('m', strtotime($dateRange['startDate']));
                $emn = 12;
                $smn1 = 1;
                $emn1 =date('m', strtotime($dateRange['endDate']));
                //_p($smn);_p($emn);_p($smn1);_p($emn1);
                $lineArray = array();
                $lineArray[$dateRange['startDate']] = 0;
                $daten = $dateRange['startDate'];
                $mnp =0;
                $mnn =0;
                $y = date('Y', strtotime($resData[0]['visitDate']));
                $flag = 0;
                $sd='';
                for($i=$startYear; $i<=$endYear;$i++)
                {
                    
                    if($i == $startYear){
                        $sm =$smn;    
                    }else{
                        $sm =1;
                    }

                    if($i == $endYear){
                        $em = $emn1;
                    }else{
                        $em =12;
                    }
                    
                    for($j=$sm;$j<=$em;$j++)
                    {
                        if($j <= 9)
                        {
                            $daten = $i.'-0'.$j.'-01';
                        }else{
                            $daten = $i.'-'.$j.'-01';
                        }  
                        if($flag == 0)
                        {
                            $sd=$daten;
                            $flag=1;
                        }
                        $lineArray[$daten] = 0;
                    }
                }
                //_p($lineArray);
                foreach ($resData as  $value) {
                    $lineArray[$value['visitDate']] += $value['visitCount']; 
                    //$pieChartDataOne[$value['source']]+= $value['visitCount'];
                    $pieChartDataTwo[$value['siteSource']]+= $value['visitCount'];
                    if($pageName=='')
                    {
                        $page = $value['pageWise'];
                        $page = $this->MISCommonLib->getPageName($page);
                        if($isComparision)
                        {
                            $pieChartDataThree[$page]+=$value['visitCount'];
                        }else
                        {
                            $prepareTableData[$page][$value['siteSource']]['visitCount']+=$value['visitCount'];
                        } 
                    }
                }
                $df = $startYear.'-'.$smn.'-01';
                if(($lineArray[$dateRange['startDate']] == 0) && ($dateRange['startDate'] != $df)){
                    $lineArray[$dateRange['startDate']] = $lineArray[$df];
                    unset($lineArray[$df]);
                }
                //_p($lineArray);_p($pieChartDataOne);_p($pieChartDataTwo);die;
            }
        } 

        //_p($engagementData['pageViews']['aggregations']['users']);die;
        $pieChartDataOne['Loggedin'] = $total - $engagementData['pageViews']['result']['aggregations']['users']['doc_count'];
        $pieChartDataOne['Non Loggedin'] = $engagementData['pageViews']['result']['aggregations']['users']['doc_count'];

        /*
            _p($lineArray);
            _p('---------------------------');
            _p($pieChartDataOne);
            _p('---------------------------');
            _p($pieChartDataTwo);
            _p('---------------------------');
            _p($pieChartDataThree);
            _p('---------------------------');
            _p($prepareTableData);
            _p('---------------------------');
            _p($total);
            die;
        */
        //$lineChartArray[$i++] = array(date("Y-m-d", strtotime($value['key_as_string'])),$value['doc_count']);
        $lineChartArray = $this->prepareDataForLineChart($lineArray);
        $engData['dataForDifferentCharts']['lineChartData'] = $lineChartArray;


        $donutChartDataByUsers = $this->prepareDataForDonutChart($pieChartDataOne,$colorCodes,$total);
        $donutChartDataByDevice = $this->prepareDataForDonutChart($pieChartDataTwo,$colorCodes,$total);

        if($pageName=='' && $isComparision){
            //$pieChartDataThree[$value['pageWise']]+=$value['visitCount'];
            $donutChartDataByPage   = $this->prepareDataForDonutChart($pieChartDataThree,$colorCodes,$total);
        }else if($pageName=='' && !$isComparision){
            $engData['dataForDifferentCharts']['dataForDataTable'] = $this->_prepareDataForDataTableForEngagement($prepareTableData,$pageName,$total);
        }

        
        if($pageName){
            $engData['dataForDifferentCharts']['donutChartData'] =array($donutChartDataByDevice,$donutChartDataByUsers); 
        }else if($pageName =='' && $isComparision){
            $engData['dataForDifferentCharts']['donutChartData'] =array($donutChartDataByDevice,$donutChartDataByUsers,$donutChartDataByPage);     
        }else if($pageName =='' && !$isComparision){
            $engData['dataForDifferentCharts']['donutChartData'] =array($donutChartDataByDevice,$donutChartDataByUsers); 
        }

        $engData['total'] = $total;
        return $engData;
    }

    function _prepareDataForDataTableForEngagement($prepareTableData,$pageName,$total)
    {
        //_p($prepareTableData);die;
        if($pageName)
        {
            foreach ($count as $key => $course){  
                $total += $course['doc_count'];
                $sourceWise = $course['sourseWise']['buckets'];
                foreach ($sourceWise as $key => $source) 
                {
                    $deviceWise = $source['siteSourse']['buckets'];
                    foreach ($deviceWise as $keyOne => $device) {
                        $page = $course['key'];
                        if($page =='savedcoursespage' || $page =='savedcoursepage'){
                            $page = 'savedcoursespage';
                        }
                        $prepareTableData[$page][$source['key']][($device['key']=='no')?"Desktop":"Mobile"]['visitCount']+=$device['doc_count'];                       
                    }
                }
            }

            $dataTableHeading = " (".$coloumHeading."-Source-Source Application wise) ";
            $coloumHeading = $this->getColoumHeading($pageName);
            $dataTable = '<thead>'.
                            '<tr class="headings">'.
                                '<th style="padding-left:20px">'.
                                    '<input type="checkbox" class="tableflat">'.
                                '</th>'.
                                '<th style="padding-left:20px">'.$coloumHeading.' </th>'.
                                '<th style="padding-left:20px">Source </th>'.
                                '<th style="padding-left:20px">Source Application </th>'.
                                '<th style="padding-left:20px;width:100px">Visit Count </th>'.
                                '<th style="padding-left:20px;width:130px">Visit (%) </th>'.
                            '</tr>'.
                        '</thead>'.
                        '<tbody>';
            $prepareDataForCSV[0]  = array($coloumHeading,'Source','Device','Visit Count','Visit (%)');
            $i=1;
            foreach ($prepareTableData as $course => $courseArray){
                foreach ($courseArray as $source => $sourceArray) {
                    foreach ($sourceArray as $device => $value) {
                        $dataTable = $dataTable.
                            '<tr class="even pointer">'.
                                '<td class="a-center ">'.
                                    '<input type="checkbox" class="tableflat">'.
                                '</td>'.
                                '<td class=" ">'.$course.'</td>'.
                                '<td class=" ">'.$source.'</td>'.
                                '<td class=" ">'.$device.'</td>'.
                                '<td class=" ">'.number_format($value['visitCount']).'</td>'.
                                '<td>'.number_format((($value['visitCount']*100)/$total), 2, '.', '').'</td>'.
                            '</tr>';
                        $prepareDataForCSV[$i++] = array($course,$source,$device,$value['visitCount'],number_format((($value['visitCount']*100)/$total), 2, '.', ''));
                    }    
                }
            }
            $dataTable = $dataTable.'</tbody>';
            $DataForDataTable = array($dataTableHeading,$dataTable,$prepareDataForCSV);
            return $DataForDataTable;    
        }else if($pageName=='')
        {            
            $dataTableHeading = " (Page-Source Application wise) ";
            //$coloumHeading = $this->getColoumHeading($pageName);
            $dataTable = '<thead>'.
                            '<tr class="headings">'.
                                '<th style="padding-left:20px">'.
                                    '<input type="checkbox" class="tableflat">'.
                                '</th>'.
                                '<th style="padding-left:20px">'.'Page </th>'.
                                '<th style="padding-left:20px">Source Application </th>'.
                                '<th style="padding-left:20px;width:100px">Count </th>'.
                                '<th style="padding-left:20px;width:130px">Count (%) </th>'.
                            '</tr>'.
                        '</thead>'.
                        '<tbody>';
            $prepareDataForCSV[0]  = array('Page','Device','Count','Count (%)');
            $i=1;

            foreach ($prepareTableData as $page => $pageArray){
                foreach ($pageArray as $device => $value) {
                    $page = $this->MISCommonLib->getPageName($page);
                    $dataTable = $dataTable.
                        '<tr class="even pointer">'.
                            '<td class="a-center ">'.
                                '<input type="checkbox" class="tableflat">'.
                            '</td>'.
                            '<td class=" ">'.$page.'</td>'.
                            '<td class=" ">'.$device.'</td>'.
                            '<td class=" ">'.number_format($value['visitCount']).'</td>'.
                            '<td>'.number_format((($value['visitCount']*100)/$total), 2, '.', '').'</td>'.
                        '</tr>';
                    $prepareDataForCSV[$i++] = array($page,$device,$value['visitCount'],number_format((($value['visitCount']*100)/$total), 2, '.', ''));
                }        
            }

            $dataTable = $dataTable.'</tbody>';
            $DataForDataTable = array($dataTableHeading,$dataTable,$prepareDataForCSV);
            return $DataForDataTable;
        }    
    }

    /*private function prepareDataForDifferentCharts($responsesData,$pageName,$dateRange,$extraData,$isComparision,$colorCodes)
    {
        $i=0;
        $total = 0;
        if($pageName){
            foreach ($responsesData as $key => $gbd)    // gbd : group by date
            {
                $total += $gbd['doc_count'];
                
                $deviceWise = $gbd['siteSourse']['buckets'];
                foreach ($deviceWise as $keyOne => $gbss)   //gbss: group by site source
                {
                        $resData[$i++] = array(
                                            "visitDate" => date("Y-m-d", strtotime($gbd['key_as_string'])),
                                            "siteSource" => ($gbss['key']=='no')?"Desktop":"Mobile",
                                            "visitCount" => $gbss['doc_count']
                                            );
                }
            }
        }else{
            foreach ($responsesData as $key => $gbd)    // gbd : group by date
            {
                $total += $gbd['doc_count'];
                
                $deviceWise = $gbd['siteSourse']['buckets'];
                foreach ($deviceWise as $keyOne => $gbss)   //gbss: group by site source
                {
                    $pageWise = $gbss['pageWise']['buckets'];
                    foreach ($pageWise as $keyTwo => $gbp)  //gbp: group by page
                    {
                        $resData[$i++] = array(
                                        "visitDate" => date("Y-m-d", strtotime($gbd['key_as_string'])),
                                        "siteSource" => ($gbss['key']=='no')?"Desktop":"Mobile",
                                        "pageWise"  => $gbp['key'],
                                        "visitCount" => $gbp['doc_count']
                                        );
                    }
                }
            }
        }
        //_p($resData);die;
        $gendate = new DateTime();
        $startYear = date('Y', strtotime($dateRange['startDate']));
        $endYear = date('Y', strtotime($dateRange['endDate']));

        if($extraData['view'] == 'day'){
            $sDate=date_create($dateRange['startDate']);
            $eDate=date_create($dateRange['endDate']);
            $diff = date_diff($sDate,$eDate);
            $dateDiff = $diff->format("%a");
            $lineArray=array();
            $tempDate =$dateRange['startDate'];
            for($i=0;$i<=$dateDiff;$i++){
                $lineArray[$tempDate] =0;
                $tempDate = date('Y-m-d', strtotime($tempDate . ' +1 day'));
            }                
            foreach ($resData as  $value) {
                    $lineArray[$value['visitDate']] += $value['visitCount'];    
                    $pieChartDataTwo[$value['siteSource']]+= $value['visitCount'];
                    if($pageName==''){
                        $page = $value['pageWise'];
                        $page = $this->MISCommonLib->getPageName($page);
                        $pieChartDataThree[$page]+=$value['visitCount'];
                    }
            }
        }else if($extraData['view'] == 'week'){   
            if($startYear == $endYear)
            {
                // creating week array
                $swn = date('W', strtotime($dateRange['startDate']));
                $ewn = date('W', strtotime($dateRange['endDate'])); 
                $lineArray = array();
                $lineArray[$dateRange['startDate']] = 0;//$lineChartData[$swn];

                for ($i=$swn; $i <= $ewn ; $i++) { 
                    $gendate->setISODate($startYear,$i,1); //year , week num , day
                    $df = $gendate->format('Y-m-d');
                    $lineArray[$df] = 0;   
                }
                //_p($lineArray);
                foreach ($resData as $key =>  $value) {
                    $lineArray[$value['visitDate']] += $value['visitCount'];
                    $pieChartDataTwo[$value['siteSource']]+= $value['visitCount'];
                    if($pageName==''){
                        $page = $value['pageWise'];
                        $page = $this->MISCommonLib->getPageName($page);
                        $pieChartDataThree[$page]+=$value['visitCount'];
                    }
                }
                //_p($lineArray);die;
                // for managing startDate traffic data
                $gendate->setISODate($startYear,$swn,1); //year , week num , day
                $df = $gendate->format('Y-m-d');
                if(($lineArray[$dateRange['startDate']] ==0) && ($dateRange['startDate'] != $df)){
                    $lineArray[$dateRange['startDate']] = $lineArray[$df];
                    unset($lineArray[$df]);
                } 
                //_p($lineArray);_p($pieChartDataOne);_p($pieChartDataTwo);die;
            }
            else
            {
                $swn = date('W', strtotime($dateRange['startDate']));
                $ewn =date('W', strtotime($startYear."-12-31"));
                if($ewn == 1){
                    $ewn = date('W', strtotime($startYear."-12-24"));
                }
                $swn1 = 1;
                $ewn1 =date('W', strtotime($dateRange['endDate']));
                $gendate->setISODate($startYear,$ewn,7); //year , week num , day
                $tempDate = $gendate->format('Y-m-d');
                if($tempDate >= $dateRange['endDate']){
                    $swn1 =0;
                    $ewn1 =-1;
                }
                //_p($swn);_p($ewn); _p($swn1);_p($ewn1);
                $lineArray = array();
                $lineArray[$dateRange['startDate']] = 0;//$lineChartData[$swn];
                for ($i=$swn; $i <= $ewn ; $i++) { 
                    $gendate->setISODate($startYear,$i,1); //year , week num , day
                    $df = $gendate->format('Y-m-d');
                    $lineArray[$df] = 0;
                }
                for ($i=$swn1; $i <= $ewn1 ; $i++) { 
                    $gendate->setISODate($endYear,$i,1); //year , week num , day
                    $df = $gendate->format('Y-m-d');
                    $lineArray[$df] = 0;   
                }
                //_p($lineArray);
                foreach ($resData as $key =>  $value) {

                    $lineArray[$value['visitDate']] += $value['visitCount'];    
                    
                    $pieChartDataTwo[$value['siteSource']]+= $value['visitCount'];

                    if($pageName==''){
                        $page = $value['pageWise'];
                        
                        $page = $this->MISCommonLib->getPageName($page);
                        $pieChartDataThree[$page]+=$value['visitCount'];
                    }
            
                }
                //_p($lineArray);
                // for managing startDate traffic data
                $gendate->setISODate($startYear,$swn,1); //year , week num , day
                $df = $gendate->format('Y-m-d');
                if(($lineArray[$dateRange['startDate']] ==0) && ($dateRange['startDate'] != $df)){
                    $lineArray[$dateRange['startDate']] = $lineArray[$df];
                    unset($lineArray[$df]);
                } 
                //_p($lineArray);die;
            }    
        }else if($extraData['view'] == 'month'){   
            if($startYear == $endYear){
                $smn = date('m', strtotime($dateRange['startDate']));
                $emn = date('m', strtotime($dateRange['endDate']));
                $lineArray = array();
                $df = $startYear.'-'.$smn.'-01';
                $lineArray[$df] = 0;
                $lineArray[$dateRange['startDate']] = 0;       
                for ($i=$smn+1; $i <= $emn ; $i++){
                    if($i <= 9){
                        $i = '0'.$i;
                        $df = $startYear.'-'.$i.'-01';
                    }else{
                        $df = $startYear.'-'.$i.'-01';    
                    }
                    $lineArray[$df] = 0;       
                }
                //_p($lineArray);
                foreach ($resData as  $value) {
                    $lineArray[$value['visitDate']] += $value['visitCount']; 
                    $pieChartDataTwo[$value['siteSource']]+= $value['visitCount'];
                    if($pageName==''){
                        $page = $value['pageWise'];
                        
                        $page = $this->MISCommonLib->getPageName($page);
                        $pieChartDataThree[$page]+=$value['visitCount'];
                    }
                }
                //_p($pieChartDataThree);die;
                $df = $startYear.'-'.$smn.'-01';
                if(($lineArray[$dateRange['startDate']] == 0) && ($dateRange['startDate'] != $df)){
                    $lineArray[$dateRange['startDate']] = $lineArray[$df];
                    unset($lineArray[$df]);
                }
            }else{
                $smn = date('m', strtotime($dateRange['startDate']));
                $emn = 12;
                $smn1 = 1;
                $emn1 =date('m', strtotime($dateRange['endDate']));
                //_p($smn);_p($emn);_p($smn1);_p($emn1);
                $lineArray = array();
                $lineArray[$dateRange['startDate']] = 0;
                $daten = $dateRange['startDate'];
                $mnp =0;
                $mnn =0;
                $y = date('Y', strtotime($resData[0]['visitDate']));
                $flag = 0;
                $sd='';
                for($i=$startYear; $i<=$endYear;$i++)
                {
                    
                    if($i == $startYear){
                        $sm =$smn;    
                    }else{
                        $sm =1;
                    }

                    if($i == $endYear){
                        $em = $emn1;
                    }else{
                        $em =12;
                    }
                    
                    for($j=$sm;$j<=$em;$j++)
                    {
                        if($j <= 9)
                        {
                            $daten = $i.'-0'.$j.'-01';
                        }else{
                            $daten = $i.'-'.$j.'-01';
                        }  
                        if($flag == 0)
                        {
                            $sd=$daten;
                            $flag=1;
                        }
                        $lineArray[$daten] = 0;
                    }
                }
                //_p($lineArray);
                foreach ($resData as  $value) {
                    $lineArray[$value['visitDate']] += $value['visitCount']; 
                    $pieChartDataTwo[$value['siteSource']]+= $value['visitCount'];
                    if($pageName==''){
                        $page = $value['pageWise'];
                        
                        $page = $this->MISCommonLib->getPageName($page);
                        $pieChartDataThree[$page]+=$value['visitCount'];
                    }
                }
                $df = $startYear.'-'.$smn.'-01';
                if(($lineArray[$dateRange['startDate']] == 0) && ($dateRange['startDate'] != $df)){
                    $lineArray[$dateRange['startDate']] = $lineArray[$df];
                    unset($lineArray[$df]);
                }
                //_p($lineArray);_p($pieChartDataOne);_p($pieChartDataTwo);die;
            }
        }
        //$lineChartArray[$i++] = array(date("Y-m-d", strtotime($value['key_as_string'])),$value['doc_count']);
        $lineChartArray = $this->prepareDataForLineChart($lineArray);
        $donutChartDataByDevice = $this->prepareDataForDonutChart($pieChartDataTwo,$colorCodes,$total);
        if($pageName=='' && $isComparision){
            $pieChartDataThree[$value['pageWise']]+=$value['visitCount'];
        }
        $donutChartDataByPage   = $this->prepareDataForDonutChart($pieChartDataThree,$colorCodes,$total);

        $trafficData['lineChartData'] = $lineChartArray;
        if($pageName){
            $trafficData['donutChartData'] =array($donutChartDataByDevice,''); 
        }else if($pageName =='' && $isComparision){
            $trafficData['donutChartData'] =array($donutChartDataByDevice,'',$donutChartDataByPage);     
        }else{
            $trafficData['donutChartData'] =array($donutChartDataByDevice,''); 
            $trafficData['barGraphData'] = array($pieChartDataOne,$total);
        }
        //_p($trafficData['DonutChartData']);die;
        return $trafficData;
    }*/

    function prepareDataForLineChart($lineChartData)
    {
        $i=0;
        foreach ($lineChartData as $date => $count) {
            $lineChartArray[$i++] = array($date,$count);   
        }
        $lineChartData = array('lineChartArray', $lineChartArray);
        return $lineChartData;
    }

    function prepareDataForDonutChart($donutChartData,$colorArray,$total)
    {   
        arsort($donutChartData);
        $i = 0;
        //_p($total);  
        //_p($donutChartData);die;
        foreach ($donutChartData as $key => $value) {
            $value = intval($value);
            $donutChartArray[$i]['value'] = $value;
            $donutChartArray[$i]['label'] = $key;
            $donutChartArray[$i]['color'] = $colorArray[$i];
            $splitName = strlen($key) > 18 ? substr($key, 0, 14) . ' ...' : $key;
            $donutChartIndexData=$donutChartIndexData. 
                            '<tr>'.
                                '<td class="width_60_percent_important" >'.
                                    '<p style="font-size:15px" title="'.$key.' : '.number_format($value).'"><i class="fa fa-square " style="color: '.$donutChartArray[$i]['color'].'">'.'</i>'.'&nbsp'.$splitName.''.'</p>'.
                                    
                                '</td>'.
                                '<td style="width:15%;text-align:center">'.number_format((($value*100)/$total), 2, '.', '').'</td>'.
                                '<td style="width:25%;text-align:center">'.$value.'</td>'.
                            '</tr>';                            
            $i++;
        }
        $donutChartData = array($donutChartArray,$donutChartIndexData,$total);
        return $donutChartData;
    }

    function getColoumHeading($pageName)
    {
        switch ($pageName) {
            case 'coursePage' :
                return 'Course Id';
                break;

        case 'countryHomePage' :
                return 'Country Id';
                break;

        case 'universityPage' :
                return 'University Id';
                break;

        case 'guidePage' :
                return 'Guide Id';
                break;

        case 'homePage' :
                return '';
                break;
        
        case 'categoryPage' :
                return 'Page Url';
                break;
        
        case 'articlePage' :
                return 'Article Id';
                break;
        
        case 'shortlistPage' :
                return '';
                break;
        
        case 'courseRankingPage' :
                return 'courseRanking Id';
                break;
        
        case 'universityRankingPage' :
                return 'universityRanking Id';
                break;
        
        case 'examPage' :
                return 'exam Id';
                break;

        case 'searchPage' :
                return '';
                break;

        case 'departmentPage' :
                return 'Department Id';
                break;

        case 'consultantPage' :
                return 'Consultant Id';
                break;

        case 'stagePage' :
                return 'Stage Id';
                break;
        
        case 'countryPage' :
                return 'Country Id';
                break;
        
        case 'applyContentPage' :
                return 'applyContent Id';
                break;
        
        default: 
            return 'Course Id';
                break;
            }
    }

    function prepareDataForDataTableForTraffic($count,$pageName,$aspect)
    {
        if($pageName)
        {
            foreach ($count as $key => $course){  
                $total += $course['doc_count'];
                $sourceWise = $course['sourceWise']['buckets'];
                foreach ($sourceWise as $key => $sourceArray) 
                {
                    $siteSource = $sourceArray['siteSource']['buckets'];
                    foreach ($siteSource as $keyOne => $siteSourceArray) {
                        if($aspect == "sessions"){
                            $trafficCount = $siteSourceArray['doc_count'];
                        }else{
                            $trafficCount = $siteSourceArray['usersCount']['value'];
                        }
                        if($pageName == 'categoryPage'){
                            $page = str_replace(SHIKSHA_STUDYABROAD_HOME.'/','',$course['key']);
                            //$page = str_replace('http://studyabroad.shiksha.com'.'/','',$course['key']);
                            $pageURL[$page] = $course['key'];
                            //http://studyabroad.shiksha.com
                            $prepareTableData[$page][$sourceArray['key']][($siteSourceArray['key']=='no')?"Desktop":"Mobile"]['visitCount']+=$trafficCount;
                        }else{
                            $page = $course['key'];
                            if($page =='savedcoursespage' || $page =='savedcoursepage'){
                                $page = 'savedcoursespage';
                            }
                            $prepareTableData[$page][$sourceArray['key']][($siteSourceArray['key']=='no')?"Desktop":"Mobile"]['visitCount']+=$trafficCount;
                        }
                    }
                }
            }
            $dataTableHeading = " (Page-Source-Source Application wise)";
            $coloumHeading = $this->getColoumHeading($pageName);
            $dataTable = '<thead>'.
                            '<tr class="headings">'.
                                '<th style="padding-left:20px">'.
                                    '<input type="checkbox" class="tableflat">'.
                                '</th>'.
                                '<th style="padding-left:20px">'.$coloumHeading.' </th>'.
                                '<th style="padding-left:20px">Source </th>'.
                                '<th style="padding-left:20px">Source Application </th>'.
                                '<th style="padding-left:20px;width:100px">Visit Count </th>'.
                                '<th style="padding-left:20px;width:130px">Visit (%) </th>'.
                            '</tr>'.
                        '</thead>'.
                        '<tbody>';
            $prepareDataForCSV[0]  = array($coloumHeading,'Source','Device','Visit Count','Visit (%)');
            $i=1;
            foreach ($prepareTableData as $course => $courseArray){
                foreach ($courseArray as $source => $sourceArray) {
                    foreach ($sourceArray as $device => $value) {
                        if($pageName == 'categoryPage'){
                            $pageUrl = '<a href="'.$pageURL[$course].'"  target="_blank">'.$course.'</a>';
                        }else{
                            $pageUrl = $course;
                        }
                        $dataTable = $dataTable.
                            '<tr class="even pointer">'.
                                '<td class="a-center ">'.
                                    '<input type="checkbox" class="tableflat">'.
                                '</td>'.
                                '<td class=" ">'.$pageUrl.'</td>'.
                                '<td class=" ">'.$source.'</td>'.
                                '<td class=" ">'.$device.'</td>'.
                                '<td class=" ">'.number_format($value['visitCount']).'</td>'.
                                '<td>'.number_format((($value['visitCount']*100)/$total), 2, '.', '').'</td>'.
                            '</tr>';
                        $prepareDataForCSV[$i++] = array($course,$source,$device,$value['visitCount'],number_format((($value['visitCount']*100)/$total), 2, '.', ''));
                    }    
                }
            }
            $dataTable = $dataTable.'</tbody>';
            $DataForDataTable = array($dataTableHeading,$dataTable,$prepareDataForCSV);
            return $DataForDataTable;    
        }else if($pageName=='')
        {
            foreach ($count as $key => $pageArray){  
                $total += $pageArray['doc_count'];
                $sourceWise = $pageArray['sourceWise']['buckets'];
                foreach ($sourceWise as $key => $sourceArray) 
                {
                    $siteSource = $sourceArray['siteSource']['buckets'];
                    foreach ($siteSource as $keyOne => $siteSourceArray) {
                        $page = $pageArray['key'];
                        $page = $this->MISCommonLib->getPageName($page);
                        if($aspect == "sessions"){
                            $trafficCount = $siteSourceArray['doc_count'];
                        }else{
                            $trafficCount = $siteSourceArray['usersCount']['value'];
                        }
                        $prepareTableData[$page][$sourceArray['key']][($siteSourceArray['key']=='no')?"Desktop":"Mobile"]['visitCount']+=$trafficCount;
                    }
                }
            }
            //_p($prepareTableData);die;

            $dataTableHeading = " (Page-Source-Source Application wise)";
            //$coloumHeading = $this->getColoumHeading($pageName);
            $dataTable = '<thead>'.
                            '<tr class="headings">'.
                                '<th style="padding-left:20px">'.
                                    '<input type="checkbox" class="tableflat">'.
                                '</th>'.
                                '<th style="padding-left:20px">'.'Page </th>'.
                                '<th style="padding-left:20px">Source </th>'.
                                '<th style="padding-left:20px">Source Application </th>'.
                                '<th style="padding-left:20px;width:100px">Visit Count </th>'.
                                '<th style="padding-left:20px;width:130px">Visit (%) </th>'.
                            '</tr>'.
                        '</thead>'.
                        '<tbody>';
            $prepareDataForCSV[0]  = array('Page','Source','Device','Visit Count','Visit (%)');
            $i=1;
            foreach ($prepareTableData as $course => $courseArray){
                foreach ($courseArray as $source => $sourceArray) {
                    foreach ($sourceArray as $device => $value) {
                        //_p($value['visitCount']);
                        //$value['visitCount'] = number_format($value['visitCount']);
                        $dataTable = $dataTable.
                            '<tr class="even pointer">'.
                                '<td class="a-center ">'.
                                    '<input type="checkbox" class="tableflat">'.
                                '</td>'.
                                '<td class=" ">'.$course.'</td>'.
                                '<td class=" ">'.$source.'</td>'.
                                '<td class=" ">'.$device.'</td>'.
                                '<td class=" ">'.number_format($value['visitCount']).'</td>'.
                                '<td>'.number_format((($value['visitCount']*100)/$total), 2, '.', '').'</td>'.
                            '</tr>';
                        $prepareDataForCSV[$i++] = array($course,$source,$device,$value['visitCount'],number_format((($value['visitCount']*100)/$total), 2, '.', ''));
                    }    
                }
            }
            $dataTable = $dataTable.'</tbody>';
            $DataForDataTable = array($dataTableHeading,$dataTable,$prepareDataForCSV);
            return $DataForDataTable;
        }     
    }

    private function _getView($view)
    {
        switch($view)
        {
            case 1:
                return 'day';

            case 2:
                return 'week';

            case 3:
                return 'month';

            default :
                return 'day';
        }
    }

    function getPageArrayForTraffic($pageName)
    {
        switch ($pageName) {
            case 'deptPage' :
                $page = 'departmentPage';
                break;
            default :
                $page = $pageName;
                break;
        }
        return $page;
    } 
    /*
        Input :
            1. date range filter
            2. page name
            3. view
            4. filter:
                a. category
                b. country (for categoryPage, country may have array)
                c. course level

        Output:
            1. Top Tiles:
                a. Users
                b. Sessions
                c. Page Views
                d. % new sessions
            2. line chart
            3. donut chart:
                a. source application
                b. traffic source
                c. page (in case of comparision)
            4. bar graph:
                a. utm source
                b. utm campaign
                c. utm medium
            5. Data Table
    */


    function addAspectToTraffic($aspect){
        if ($aspect == 'users') {
            $dateWiseFilter = array(
                'usersCount' => array(
                    'cardinality' => array(
                        'field' => 'visitorId'
                    )
                )
            );
        } else if ($aspect == 'pageviews') {
            $dateWiseFilter = array(
                'pageviews' => array(
                    'sum' => array(
                        'field' => 'pageviews'
                    )
                )
            );
        }
        return $dateWiseFilter;
    }

    function prepareDataForTrafficPageviews($dateRange,$extraData,$pageName='',$isDataTable=1,$isComparision=0,$colorCodes){
        $extraData['studyAbroad']['categoryId'] = intval($extraData['studyAbroad']['categoryId']);
        if(is_array($extraData['country']) ){
        if(sizeof( $extraData['country']) == 1){
                $extraData['country'][0] = intval($extraData['country'][0]);
        }else{
            foreach ($extraData['country'] as $key => $value) {
                $extraData['country'][$key] = intval($value);
            }
        }    
        }else{
            if($extraData['country'] !=0 ){
                $extraData['country'] = intval($extraData['country']);
            }
        }

        $params = array();
        $params['index'] = MISCommonLib::$TRAFFICDATA_PAGEVIEWS;
        $params['type'] = 'pageview';
        $params['body']['size'] = 0;

        $startDateFilter = array();
        $startDateFilter['range']['visitTime']['gte'] = $dateRange['startDate'].'T00:00:00';
        $endDateFilter = array();
        $endDateFilter['range']['visitTime']['lte'] = $dateRange['endDate'].'T23:59:59';
        if($pageName){
            $pageName = $this->getPageArrayForTraffic($pageName);
        }
        $this->_prepareAbroadFiltersForTrafficPageviews($extraData['studyAbroad'],$pageName,$params);
        //_p(json_encode($params));die;
        $view = $this->_getView($extraData['studyAbroad']['view']);
        $extraData['studyAbroad']['view'] = $view;   
        $pageFilter = $extraData['studyAbroad'];
        $params['body']['query']['filtered']['filter']['bool']['must'][] = $startDateFilter;
        $params['body']['query']['filtered']['filter']['bool']['must'][] = $endDateFilter;
        //_p(json_encode($params));die;

        // We have query, now we apply diff aggs
            // 1. For Line Chart
            $dateWiseFilter['dateWiseCount']['date_histogram']['interval'] =$extraData['studyAbroad']['view'];
            $dateWiseFilter['dateWiseCount']['date_histogram']['field'] = 'visitTime';
            $params['body']['aggs'] = $dateWiseFilter;
            //_p(json_encode($params));die;
            $search = $this->clientCon->search($params);
            $actualCount = $search['hits']['total'];
            $dateWiseData = $search['aggregations']['dateWiseCount']['buckets'];
            //_p($dateWiseData);die;
            $lineChartData= $this->MISCommonLib->prepareDataForLineChartForShikshaTraffic($dateWiseData,$dateRange,$view,$actualCount,0,$aspect);
            //_p($lineChartData);die;
            $trafficData['dataForDifferentCharts']['lineChartData'] = $lineChartData;

            // for Source Application Data
            $donutChartDataBySourceApplication = $this->MISCommonLib->prepareDonutChartData($params,'isMobile',$colorCodes,1);
            $donutChartDataBySourceApplication = $this->prepareDataForDonutChart($donutChartDataBySourceApplication[0],$colorCodes,$donutChartDataBySourceApplication[1]);
            

            // for Page Wise Data
            if($isComparision && !$pageName){
                $donutChartDataByPage = $this->MISCommonLib->prepareDonutChartData($params,'pageIdentifier',$colorCodes,1);
                //_p($donutChartDataByPage);die;
                $donutChartDataByPage = $this->prepareDataForDonutChart($donutChartDataByPage[0],$colorCodes,$donutChartDataByPage[1]);
            }
            if(!$isComparision ){
                unset($params['body']['aggs']);
                $trafficSourceData = $this->_getTrafficSourceData($params);
                $donutChartDataBySource = $this->prepareDataForDonutChart($trafficSourceData['donutChart']['data'],$colorCodes,$trafficSourceData['donutChart']['count']);    
                $trafficData['dataForDifferentCharts']['barGraphDataForTraffic'] = $trafficSourceData['barGraphData'];
                //_p($trafficSourceData);die;
                $trafficData['dataForDifferentCharts']['trafficSourceFilterData'] = $trafficSourceData['lis'];
                $trafficData['dataForDifferentCharts']['defaultView'] = $trafficSourceData['defaultView'];
                //-----------------------------------
            }else{
                // for Traffic Source  Data
                $donutChartDataBySource = $this->MISCommonLib->prepareDonutChartData($params,'source',$colorCodes,1);
                $donutChartDataBySource = $this->prepareDataForDonutChart($donutChartDataBySource[0],$colorCodes,$donutChartDataBySource[1]);

            }
            
            if($extraData['studyAbroad'])
            {       
                if(!$isDataTable){
                    $pageArray = array('homePage','shortlistPage','searchPage','stagePage','recommendationPage','compareCoursesPage');
                    if(!(in_array($pageName, $pageArray)))
                    {
                        if($pageName)
                        {
                            $trafficData['dataForDifferentCharts']['dataForDataTable'] ='';

                            /*unset($params['body']['aggs']);
                            //---------------------
                            $siteSourseFilter=array();
                            $sourceWiseFilter =array();
                            $pageWiseFilter=array();

                            $siteSourseFilter['siteSource']['terms']['field'] = 'isMobile';
                            $siteSourseFilter['siteSource']['terms']['size'] = 0;
                            
                            $sourceWiseFilter['source']['terms']['field']= 'source';
                            $sourceWiseFilter['source']['terms']['size'] = 0;
                            $sourceWiseFilter['source']['aggs'] = $siteSourseFilter;

                            if($pageName == 'categoryPage'){
                                $pageWiseFilter['pageWise']['terms']['field']= 'pageURL';
                            }else{
                                $pageWiseFilter['pageWise']['terms']['field']= 'pageEntityId';
                            }

                            $pageWiseFilter['pageWise']['aggs']= $sourceWiseFilter;
                            $params['body']['aggs'] = $pageWiseFilter;
                            //_p(json_encode($params));die;
                            $search = $this->clientCon->search($params);
                            $result = $search['aggregations']['pageWise']['buckets'];
                            $totalPageViewsCount =0;
                            
                            foreach ($result as $key => $pageArray){  
                                $source = $pageArray['source']['buckets'];
                                foreach ($source as $key => $sourceArray) {  
                                    $siteSource = $sourceArray['siteSource']['buckets'];
                                    foreach ($siteSource as $key => $siteSourceArray) {
                                        if($pageName == 'categoryPage'){
                                            $page = str_replace(SHIKSHA_STUDYABROAD_HOME.'/','',$result['key']);
                                            $pageURL[$page] = $result['key'];
                                            $prepareTableData[$page][$sourceArray['key']][($siteSource['key']=='no')?"Desktop":"Mobile"]['visitCount']+=$trafficCount;
                                        }else{
                                            $page = $pageArray['key'];
                                        }

                                        $totalPageViewsCount += $siteSourceArray['doc_count'];
                                        $dataArray[$page][$sourceArray['key']][($siteSourceArray['key']=='yes')?'Mobile':'Desktop'] = $siteSourceArray['doc_count'];
                                    }
                                }
                            }
                            $trafficData['dataForDifferentCharts']['dataForDataTable'] = $this->_prepareDataForDataTableForPageViewsForTraffic($dataArray,$totalPageViewsCount);   
                        */
                        }else if($pageName == ''){    
                            $extraData = $extraData['studyAbroad'];
                            unset($params['body']['aggs']);
                            $siteSourceAggs['siteSource']['terms']['field'] =  "isMobile";
                            $siteSourceAggs['siteSource']['terms']['size'] =  0;

                            $sourceAggs['source']['terms']['field'] =  "source";
                            $sourceAggs['source']['terms']['size'] =  0;
                            $sourceAggs['source']['aggs'] = $siteSourceAggs;

                            $pageWiseAggs['pageWise']['terms']['field'] =  "pageIdentifier";
                            $pageWiseAggs['pageWise']['terms']['size'] =  0;
                            $pageWiseAggs['pageWise']['aggs'] = $sourceAggs;
                            $params['body']['aggs'] = $pageWiseAggs;
                            //_p(json_encode($params));die;
                        
                            $search = $this->clientCon->search($params);
                            $result = $search['aggregations']['pageWise']['buckets'];

                            $totalPageViewsCount =0;
                            foreach ($result as $key => $pageArray){  
                                $source = $pageArray['source']['buckets'];
                                foreach ($source as $key => $sourceArray) {  
                                    $siteSource = $sourceArray['siteSource']['buckets'];
                                    foreach ($siteSource as $key => $siteSourceArray) {
                                        $totalPageViewsCount += $siteSourceArray['doc_count'];
                                        $page = $pageArray['key']; 
                                        $page = $this->MISCommonLib->getPageName($page);
                                        $dataArray[$page][$sourceArray['key']][($siteSourceArray['key']=='yes')?'Mobile':'Desktop'] = $siteSourceArray['doc_count'];
                                    }
                                }
                            }
                            //_p($dataArray);die;
                        $trafficData['dataForDifferentCharts']['dataForDataTable'] = $this->_prepareDataForDataTableForPageViewsForTraffic($dataArray,$totalPageViewsCount);
                        }
                    }
                } 
            }
            if($isComparision && !$pageName){
                $trafficData['dataForDifferentCharts']['donutChartData'] =array($donutChartDataBySourceApplication,$donutChartDataBySource,$donutChartDataByPage);
            }else{
                $trafficData['dataForDifferentCharts']['donutChartData'] =array($donutChartDataBySourceApplication,$donutChartDataBySource);    
            }
            //_p($trafficData['dataForDifferentCharts']['donutChartData']);die;

            // Prepare Top Tiles
            //prepare data for top tiles
                //$trafficData['DonutChartData'] =array($DonutChartDataByDevice,$DonutChartDataBySource); 
                // 4. pageviews
                unset($param['body']['aggs']);
                $search = $this->clientCon->search($params);
                $pageviews = $search['hits']['total'];

            // Prepare Query For Traffic
            $params =array();
            $extraData['studyAbroad']['categoryId'] = intval($extraData['studyAbroad']['categoryId']);
            if(is_array($extraData['country']) ){
            if(sizeof( $extraData['country']) == 1){
                    $extraData['country'][0] = intval($extraData['country'][0]);
            }else{
                foreach ($extraData['country'] as $key => $value) {
                    $extraData['country'][$key] = intval($value);
                }
            }
    
            }else{
                if($extraData['country'] !=0 ){
                    $extraData['country'] = intval($extraData['country']);
                }
            }
            $startDateFilter = array();
            $startDateFilter['range']['startTime']['gte'] = $dateRange['startDate'].'T00:00:00';
            $endDateFilter = array();
            $endDateFilter['range']['startTime']['lte'] = $dateRange['endDate'].'T23:59:59';
            if($pageName){
                $pageName = $this->getPageArrayForTraffic($pageName);
            }

            $view = $this->_getView($extraData['studyAbroad']['view']);
            $extraData['studyAbroad']['view'] = $view;   
            $pageFilter = $extraData['studyAbroad'];
            $params['body']['query']['filtered']['filter']['bool']['must'][] = $startDateFilter;
            $params['body']['query']['filtered']['filter']['bool']['must'][] = $endDateFilter;
            
            $this->_prepareAbroadFilters($extraData,$pageName,$params);
            // 1. total 
            $session =0;
            unset($params['body']['aggs']);
            $search = $this->clientCon->search($params);
            $session = $search['hits']['total'];

            // 2. unique users
            unset($params['body']['aggs']);
            $params['body']['aggs']['users']['cardinality']['field'] = 'visitorId';
            $search = $this->clientCon->search($params);
            $visitors = $search['aggregations']['users']['value'];

            // 3. new session count
            unset($params['body']['aggs']);
            $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['sessionNumber'] =1;
            $search = $this->clientCon->search($params);
            $count = $search['hits']['total'];
            $perNewSession = number_format(($count*100)/$session, 2, '.', '');

                

            $trafficData['topTiles'] = array($visitors,$session,$pageviews,$perNewSession);
            
            return $trafficData;
    }
    private function _prepareDataForDataTableForPageViewsForTraffic($prepareTableData,$total)
    {
        //_p($prepareTableData);die;
        
        $dataTableHeading = " (Page- Source - Source Application wise) ";
        //Traffic Data (Page – Source - Source Application wise) 
        //$coloumHeading = $this->getColoumHeading($pageName);
        $dataTable = '<thead>'.
                        '<tr class="headings">'.
                            '<th style="padding-left:20px">'.
                                '<input type="checkbox" class="tableflat">'.
                            '</th>'.
                            '<th style="padding-left:20px">'.'Page </th>'.
                            '<th style="padding-left:20px">Source </th>'.
                            '<th style="padding-left:20px">Source Application </th>'.
                            '<th style="padding-left:20px;width:100px">Visit Count  </th>'.
                            '<th style="padding-left:20px;width:100px">Visit (%) </th>'.
                        '</tr>'.
                    '</thead>'.
                    '<tbody>';
        $prepareDataForCSV[0]  = array('Page','Source','Device','Visit Count','Visit (%)');

        foreach ($prepareTableData as $page => $pageArray){
            foreach ($pageArray as $source => $sourceArray) {
                foreach ($sourceArray as $siteSource => $siteSourceArray){
                    $dataTable = $dataTable.
                        '<tr class="even pointer">'.
                            '<td class="a-center ">'.
                                '<input type="checkbox" class="tableflat">'.
                            '</td>'.
                            '<td class=" ">'.$page.'</td>'.
                            '<td class=" ">'.$source.'</td>'.
                            '<td class=" ">'.$siteSource.'</td>'.
                            '<td class=" ">'.$siteSourceArray.'</td>'.
                            '<td>'.number_format((($siteSourceArray*100)/$total), 2, '.', '').'</td>'.
                        '</tr>';
                    $prepareDataForCSV[] = array($page,$source,$siteSource,$siteSourceArray,number_format((($siteSourceArray*100)/$total), 2, '.', ''));
                }
            }    
        }
        //_p($prepareDataForCSV);die;
        $dataTable = $dataTable.'</tbody>';
        $DataForDataTable = array($dataTableHeading,$dataTable,$prepareDataForCSV);
        //_p($DataForDataTable);die;
        return $DataForDataTable;  
    }

    private function _prepareAbroadFiltersForTrafficPageviews($extraData,$page,& $params)
    {   
        $params['body']['query']['filtered']['query']['bool']['must'][]['match']['isStudyAbroad'] = "yes";

        if($page){
            if($page == 'rankingPage'){
                if($extraData['pageType'] == 0){
                    $params['body']['query']['filtered']['query']['bool']['should'][]['match']['pageIdentifier'] = 'courseRankingPage';
                    $params['body']['query']['filtered']['query']['bool']['should'][]['match']['pageIdentifier'] = 'universityRankingPage';
                    $params['body']['query']['filtered']['query']['bool']['minimum_should_match'] = 1;    
                }else if($extraData['pageType'] == 1){
                    $params['body']['query']['filtered']['query']['bool']['must'][]['match']['pageIdentifier'] = 'universityRankingPage';        
                }else if($extraData['pageType'] == 2){
                    $params['body']['query']['filtered']['query']['bool']['must'][]['match']['pageIdentifier'] = 'courseRankingPage';    
                }    
            }else{
                $params['body']['query']['filtered']['query']['bool']['must'][]['match']['pageIdentifier'] = $page;    
            }
        }

        if(is_array($extraData['country']) ){
            if(sizeof( $extraData['country']) == 1){
                    $params['body']['query']['filtered']['query']['bool']['must'][]['match']['countryId'] = $extraData['country'][0];
            }else{
                foreach ($extraData['country'] as $key => $value) {
                    $params['body']['query']['filtered']['query']['bool']['should'][]['match']['countryId'] = $value;
                }
                $params['body']['query']['filtered']['query']['bool']['minimum_should_match'] = 1;    
            }    
        }else{
            if($extraData['country'] !=0 ){
                $params['body']['query']['filtered']['query']['bool']['must'][]['match']['countryId'] = $extraData['country'];    
            }
        }
        
        if($extraData['categoryId'] !=0 ){
            $ldbCourseIdsArray = $this->abroadCommonLib->getAbroadMainLDBCourses();
            foreach ($ldbCourseIdsArray as $key => $value) {
                $ldbCourseIds[]= $value['SpecializationId'];
            }

            if(in_array($extraData['categoryId'],$ldbCourseIds)){
                $params['body']['query']['filtered']['query']['bool']['must'][]['match']['LDBCourseId'] = $extraData['categoryId'];
            }else{
                $params['body']['query']['filtered']['query']['bool']['must'][]['match']['categoryId'] = $extraData['categoryId'];
                if($extraData['courseLevel']!= ''){
                    if($extraData['courseLevel']!= '0'){
                        $params['body']['query']['filtered']['query']['bool']['must'][]['match']['courseLevel'] = $extraData['courseLevel'];
                    }
                }
            }
        }else{  
            if($extraData['courseLevel']!= ''){
                if($extraData['courseLevel']!= '0'){
                    $params['body']['query']['filtered']['query']['bool']['must'][]['match']['courseLevel'] = $extraData['courseLevel'];
                }
            }
        }
    }

    function getTotalVisitForPage($dateRange,$extraData,$pageName='',$isDataTable=1,$isComparision=0,$colorCodes)
    {
        $view = 'day';
    	$params = array();
        $params['index'] = MISCommonLib::$TRAFFICDATA_SESSIONS;
        $params['type'] = 'session';
        $params['body']['size'] = 0;
        
        $dataCheckFilter =array();
        $dataCheckFilter['exists']['field'] = 'landingPageDoc';
   
        if($extraData['CD'])
        {
          if(empty($extraData['CD']['instituteId']) && empty($extraData['CD']['courseId']) && empty($extraData['CD']['articleId']) && empty($extraData['CD']['discussionId']) && empty($extraData['CD']['subCategoryId']) && empty($extraData['CD']['authorId']))
          {
                $resData = array();     
          }
          else
          { 
            $elasticQuery= $this->_prepareCDFilters($extraData,$dateRange);
            $search = $this->clientCon->search($elasticQuery);
            $responsesData = $search['aggregations']['dateWiseCount']['buckets'];

            //$responsesData,$pageName,$dateRange,$pageFilter);
           foreach ($responsesData as $key => $gbd)    // gbd : group by date
            {
                $total += $gbd['doc_count'];
                $sourceWise = $gbd['sourseWise']['buckets'];
                foreach ($sourceWise as $key => $gbs)   // gbs: group by source
                {
                    $deviceWise = $gbs['siteSourse']['buckets'];
                    foreach ($deviceWise as $keyOne => $gbss)   //gbss: group by site source
                    {
                        $resData[$i++] = array(
                                            "responseDate" => date("Y-m-d", strtotime($gbd['key_as_string'])),
                                            "sourceWise"    => $gbs['key'],
                                            "siteSource" => ($gbss['key']=='no')?"Desktop":"Mobile",
                                            "responsescount" => $gbss['doc_count']
                                            );
                    }
                }
            }
            }
            return($resData);
        }  
        else if($extraData['National'])
        {
            $startDateFilter = array();
            $startDateFilter['range']['startTime']['gte'] = $dateRange['startDate'].'T00:00:00';
            $endDateFilter = array();
            $endDateFilter['range']['startTime']['lte'] = $dateRange['endDate'].'T23:59:59';
            $this->_prepareNationalFilters($extraData['National'],$pageName,$params);

            $params['body']['query']['filtered']['filter']['bool']['must'][] = $startDateFilter;
            $params['body']['query']['filtered']['filter']['bool']['must'][] = $endDateFilter;

            $deviceWiseGrouping = array(
                'deviceWise' => array(
                    'terms' => array(
                        'field' => 'isMobile',
                        'size' => 0,
                        'order' => array(
                            '_count' => 'desc'
                        )
                    )
                )
            );


            $pivot = $extraData['National']['pivot'];

            if ($pivot == 'user') {
                $deviceWiseGrouping['deviceWise']['aggs'] = array(
                    $pivot => array(
                        'cardinality' => array(
                            'field' => 'visitorId',
                        )
                    )
                );
            }

            $sourceWiseGrouping = array(
                'sourceWise' => array(
                    'terms' => array(
                        'field' => 'source',
                        'size' => 0,
                        'order' => array(
                            '_count' => 'desc'
                        )
                    ),
                    'aggs' => $deviceWiseGrouping
                )
            );

            $params['body']['aggs'] = array(
                'pageWise' => array(
                    'terms' => array(
                        'field' => 'landingPageDoc.pageIdentifier',
                        'size' => 0,
                        'order' => array(
                            '_count' => 'desc'
                        )
                    ),
                    'aggs' => $sourceWiseGrouping
                )
            );

            $search = $this->clientCon->search($params);

            $totalCount = $search['hits']['total'];

            $pageWiseData = $search['aggregations']['pageWise']['buckets'];
            foreach($pageWiseData as $oneRowInResult){
                $pageName = ucfirst(preg_replace('/([a-z])(_){0,}([A-Z])/', '$1 $3', $oneRowInResult['key'])); // Make camel cased word into separate words;
                foreach ($oneRowInResult['sourceWise']['buckets'] as $oneResult) {
                    $trafficSourceName = ucfirst($oneResult['key']);

                    $sourceApplicationWiseData = $oneResult['deviceWise']['buckets'];
                    foreach ($sourceApplicationWiseData as $oneDeviceData) {
                        $oneRowForTable                = new stdClass();
                        if($pivot == 'user' ){
                            $oneRowForTable->ResponseCount = $oneDeviceData['user']['value'];
                        } else {
                            $oneRowForTable->ResponseCount = $oneDeviceData['doc_count'];
                        }
                        $oneRowForTable->PageName      = $pageName;
                        $oneRowForTable->TrafficSource = $trafficSourceName;
                        $oneRowForTable->DeviceName    = $oneDeviceData['key'] == 'yes' ? 'Mobile' : 'Desktop';
                        $oneRowForTable->Percentage = number_format($oneRowForTable->ResponseCount * 100 / $totalCount, 2);
                        $gridData[]                    = $oneRowForTable;
                    }
                }
            }

            $this->MISCommonLib->arrangeTableData($gridData, true);
            return array_values($gridData);
        }else if($extraData['studyAbroad'])
        {   
            $extraData['studyAbroad']['categoryId'] = intval($extraData['studyAbroad']['categoryId']);
            if(is_array($extraData['country']) ){
            if(sizeof( $extraData['country']) == 1){
                    $extraData['country'][0] = intval($extraData['country'][0]);
            }else{
                foreach ($extraData['country'] as $key => $value) {
                    $extraData['country'][$key] = intval($value);
                }
            }    
            }else{
                if($extraData['country'] !=0 ){
                    $extraData['country'] = intval($extraData['country']);
                }
            }

            $startDateFilter = array();
            $startDateFilter['range']['startTime']['gte'] = $dateRange['startDate'].'T00:00:00';
            $endDateFilter = array();
            $endDateFilter['range']['startTime']['lte'] = $dateRange['endDate'].'T23:59:59';
            if($pageName){
                $pageName = $this->getPageArrayForTraffic($pageName);
            }
            $this->_prepareAbroadFilters($extraData['studyAbroad'],$pageName,$params);

            $view = $this->_getView($extraData['studyAbroad']['view']);
            $extraData['studyAbroad']['view'] = $view;   
            $pageFilter = $extraData['studyAbroad'];
            $params['body']['query']['filtered']['filter']['bool']['must'][] = $startDateFilter;
            $params['body']['query']['filtered']['filter']['bool']['must'][] = $endDateFilter;
            
            //_p(json_encode($params));die;

            // We have query, now we apply diff aggs
            // 1. For Line Chart
            $dateWiseFilter['dateWiseCount']['date_histogram']['interval'] =$extraData['studyAbroad']['view'];
            $dateWiseFilter['dateWiseCount']['date_histogram']['field'] = 'startTime';
            
            $aspect = $extraData['studyAbroad']['aspect'];
            if($aspect == "Sessions"){
                $aspect = "sessions";
                $extraData['studyAbroad']['aspect'] = "sessions";
            }else if($aspect == "Users"){
                $aspect = "users";
                $extraData['studyAbroad']['aspect'] = "users";
            }else if($aspect == "Page Views"){
                $aspect = "pageviews";
                $extraData['studyAbroad']['aspect'] = "pageviews";
            }
            if($aspect != "sessions"){
                //_p(json_encode($this->addAspectToTraffic($aspect)));die;
                $dateWiseFilter['dateWiseCount']['aggs'] = $this->addAspectToTraffic($aspect);    
            }

            
            $params['body']['aggs'] = $dateWiseFilter;
            $search = $this->clientCon->search($params);
            $actualCount = $search['hits']['total'];
            $dateWiseData = $search['aggregations']['dateWiseCount']['buckets'];
            $lineChartData= $this->MISCommonLib->prepareDataForLineChartForShikshaTraffic($dateWiseData,$dateRange,$view,$actualCount,0,$aspect);
            //_p($lineChartData);die;
            // for Source Application Data
            $donutChartDataBySourceApplication = $this->MISCommonLib->prepareDonutChartData($params,'isMobile',$colorCodes,1,$aspect);
            $donutChartDataBySourceApplication = $this->prepareDataForDonutChart($donutChartDataBySourceApplication[0],$colorCodes,$donutChartDataBySourceApplication[1]);

            // for Traffic Source Data
            $donutChartDataBySource = $this->MISCommonLib->prepareDonutChartData($params,'source',$colorCodes,1,$aspect);
            $donutChartDataBySource = $this->prepareDataForDonutChart($donutChartDataBySource[0],$colorCodes,$donutChartDataBySource[1]);
            // for Page Wise Data

            if($isComparision && !$pageName){
                $donutChartDataByPage = $this->MISCommonLib->prepareDonutChartData($params,'landingPageDoc.pageIdentifier',$colorCodes,1,$aspect);
                $donutChartDataByPage = $this->prepareDataForDonutChart($donutChartDataByPage[0],$colorCodes,$donutChartDataByPage[1]);
            }

            /*
                //_p($responsesData);die;
                //$trafficDataForDiffChart= $this->prepareDataForDifferentCharts($responsesData,$pageName,$dateRange,$pageFilter,$isComparision,$colorCodes);
                //_p($trafficDataForDiffChart);die;
                // for traffic source filter 
                /*$sourceWiseFilter['sourseWise']['terms']['field']= 'source';
                $pageWiseFilter['sourseWise']['terms']['size']= 0;
                $params['body']['aggs']['checkColoum']['aggs'] = $sourceWiseFilter;
                //_p(json_encode($params));
                $search = $this->clientCon->search($params);
                $trafficSourceArrayData = $search['aggregations']['checkColoum']['sourseWise']['buckets'];
                foreach ($trafficSourceArrayData as $key => $value) {
                    $trafficSourceUTMArray[$value['key']] = $value['doc_count'];
                    $totalTrafficUTMCount += $value['doc_count'];
                }

                //$trafficDataForDiffChart['donutChartData'][1] = $this->prepareDataForDonutChart($trafficSourceUTMArray,$colorCodes,$totalTrafficUTMCount);
            */
            if(!$isComparision && $donutChartDataBySource[2]){
                foreach ($donutChartDataBySource[0] as $key => $value) {
                    if($value['label'] != 'Other'){
                        $trafficSourceArray[$value['label']] = $value['value'];
                        $trafficSourceCount += $value['value'];    
                    }
                }

                $temp = array($trafficSourceArray,$trafficSourceCount);
                $extraData['studyAbroad']['pageName'] = $pageName;
                $trafficDataForDiffChart['barGraphData'] = $this->MISCommonLib->prepareDataForTrafficSourceFilter($temp,$dateRange,'',$extraData['studyAbroad'],'abroad');
                $trafficData['dataForDifferentCharts']['barGraphDataForTraffic'] =$trafficDataForDiffChart['barGraphData']['barGraphData'];
                $trafficData['dataForDifferentCharts']['trafficSourceFilterData'] =$trafficDataForDiffChart['barGraphData']['lis'];
                $trafficData['dataForDifferentCharts']['defaultView'] =$trafficDataForDiffChart['barGraphData']['defaultView'];
            }

            $trafficData['dataForDifferentCharts']['lineChartData'] = $lineChartData;
            if($isComparision && !$pageName){
                $trafficData['dataForDifferentCharts']['donutChartData'] =array($donutChartDataBySourceApplication,$donutChartDataBySource,$donutChartDataByPage);
            }else{
                $trafficData['dataForDifferentCharts']['donutChartData'] =array($donutChartDataBySourceApplication,$donutChartDataBySource);    
            }
    
            //$trafficData['dataForDifferentCharts']['campaignwiseData'] =$campaignwiseData;
            //only for Study Abroad
            if($extraData['studyAbroad'])
            {   
                //prepare data for top tiles
                //$trafficData['DonutChartData'] =array($DonutChartDataByDevice,$DonutChartDataBySource); 
                // 1. total 
                $session =0;
                unset($params['body']['aggs']);
                $search = $this->clientCon->search($params);
                $session = $search['hits']['total'];

                // 2. unique users
                unset($params['body']['aggs']);
                $params['body']['aggs']['users']['cardinality']['field'] = 'visitorId';
                $search = $this->clientCon->search($params);
                $visitors = $search['aggregations']['users']['value'];

                // 3. new session count
                unset($params['body']['aggs']);
                $params['body']['query']['filtered']['filter']['bool']['must'][]['term']['sessionNumber'] =1;
                $search = $this->clientCon->search($params);
                $count = $search['hits']['total'];
                $perNewSession = number_format(($count*100)/$session, 2, '.', '');

                // 4. pageviews
                $params = array();
                $params['index'] = MISCommonLib::$TRAFFICDATA_PAGEVIEWS;
                $params['type'] = 'pageview';
                $params['body']['size'] = 0;

                $startDateFilter = array();
                $startDateFilter['range']['visitTime']['gte'] = $dateRange['startDate'].'T00:00:00';
                $endDateFilter = array();
                $endDateFilter['range']['visitTime']['lte'] = $dateRange['endDate'].'T23:59:59';

                $filter = $this->prepareAbroadFiltersForPageViews($extraData['studyAbroad'],$pageName,$params);
                $params['body']['query']['filtered']['filter']['bool']['must'][] = $startDateFilter;
                $params['body']['query']['filtered']['filter']['bool']['must'][] = $endDateFilter;
                $search = $this->clientCon->search($params);
                $pageviews = $search['hits']['total'];

                $trafficData['topTiles'] = array($visitors,$session,$pageviews,$perNewSession);
                //--------------------
                if(!$isDataTable){
                    $pageArray = array('homePage','shortlistPage','searchPage','stagePage','recommendationPage','compareCoursesPage');
                    if(!(in_array($pageName, $pageArray)))
                    {
                        if($pageName)
                        {
                            $extraData = $extraData['studyAbroad'];
                            $params = array();
                            $params['index'] = MISCommonLib::$TRAFFICDATA_SESSIONS;
                            $params['type'] = 'session';
                            $params['body']['size'] = 0;

                            $startDateFilter = array();
                            $startDateFilter['range']['startTime']['gte'] = $dateRange['startDate'].'T00:00:00';
                            $endDateFilter = array();
                            $endDateFilter['range']['startTime']['lte'] = $dateRange['endDate'].'T23:59:59';
                            $params['body']['query']['filtered']['query']['bool']['must'][]['match']['isStudyAbroad'] = "yes";

                            if($pageName == 'rankingPage'){
                                if($extraData['pageType'] == 0){
                                    $params['body']['query']['filtered']['query']['bool']['should'][]['match']['landingPageDoc.pageIdentifier'] = 'courseRankingPage';
                                    $params['body']['query']['filtered']['query']['bool']['should'][]['match']['landingPageDoc.pageIdentifier'] = 'universityRankingPage';
                                    $params['body']['query']['filtered']['query']['bool']['minimum_should_match'] = 1;    
                                }else if($extraData['pageType'] == 1){
                                    $params['body']['query']['filtered']['query']['bool']['must'][]['match']['landingPageDoc.pageIdentifier'] = 'universityRankingPage';        
                                }else if($extraData['pageType'] == 2){
                                    $params['body']['query']['filtered']['query']['bool']['must'][]['match']['landingPageDoc.pageIdentifier'] = 'courseRankingPage';    
                                }    
                            }else{
                                $params['body']['query']['filtered']['query']['bool']['must'][]['match']['landingPageDoc.pageIdentifier'] = $pageName;    
                            }

                            if(is_array($extraData['country']) ){
                                if(sizeof( $extraData['country']) == 1){
                                        $params['body']['query']['filtered']['query']['bool']['must'][]['match']['landingPageDoc.countryId'] = $extraData['country'][0];
                                }else{
                                    foreach ($extraData['country'] as $key => $value) {
                                        $params['body']['query']['filtered']['query']['bool']['should'][]['match']['landingPageDoc.countryId'] = $value;
                                    }
                                    $params['body']['query']['filtered']['query']['bool']['minimum_should_match'] = 1;    
                                }    
                            }else{
                                if($extraData['country'] !=0 ){
                                    $params['body']['query']['filtered']['query']['bool']['must'][]['match']['landingPageDoc.countryId'] = $extraData['country'];    
                                }
                            }
                            
                            if($extraData['category'] !=0 ){
                                $ldbCourseIdsArray = $this->abroadCommonLib->getAbroadMainLDBCourses();
                                foreach ($ldbCourseIdsArray as $key => $value) {
                                    $ldbCouseIds[]= $value['SpecializationId'];
                                }

                                if(in_array($extraData['category'],$ldbCouseIds)){
                                    $params['body']['query']['filtered']['query']['bool']['must'][]['match']['landingPageDoc.LDBCourseId'] = $extraData['category'];
                                }else{
                                    $params['body']['query']['filtered']['query']['bool']['must'][]['match']['landingPageDoc.categoryId'] = $extraData['category'];
                                    if($extraData['courseLevel']!= ''){
                                        if($extraData['courseLevel']!= '0'){
                                            $params['body']['query']['filtered']['query']['bool']['must'][]['match']['landingPageDoc.courseLevel'] = $extraData['courseLevel'];
                                        }
                                    }
                                }
                            }else{  
                                if($extraData['courseLevel']!= ''){
                                    if($extraData['courseLevel']!= '0'){
                                        $params['body']['query']['filtered']['query']['bool']['must'][]['match']['landingPageDoc.courseLevel'] = $extraData['courseLevel'];
                                    }
                                }
                            }

                            $params['body']['query']['filtered']['filter']['bool']['must'][] = $startDateFilter;
                            $params['body']['query']['filtered']['filter']['bool']['must'][] = $endDateFilter;
                            //---------------------
                            $siteSourseFilter=array();
                            $sourceWiseFilter =array();
                            $pageWiseFilter=array();

                            $siteSourseFilter['siteSource']['terms']['field'] = 'isMobile';
                            $siteSourseFilter['siteSource']['terms']['size'] = 0;
                            if($aspect != "sessions"){
                                $siteSourseFilter['siteSource']['aggs'] = $this->addAspectToTraffic($aspect);    
                            }
                            $sourceWiseFilter['sourceWise']['terms']['field']= 'source';
                            $sourceWiseFilter['sourceWise']['terms']['size'] = 0;
                            $sourceWiseFilter['sourceWise']['aggs'] = $siteSourseFilter;

                            $pageWiseFilter['pageWise']['terms']['field']= 'landingPageDoc.pageIdentifier';

                            if($pageName == 'categoryPage'){
                                $pageWiseFilter['pageWise']['terms']['field']= 'landingPageDoc.pageURL';
                            }else{
                                $pageWiseFilter['pageWise']['terms']['field']= 'landingPageDoc.pageEntityId';
                            }

                            $pageWiseFilter['pageWise']['aggs']= $sourceWiseFilter;
                            $params['body']['aggs'] = $pageWiseFilter;
                            $search = $this->clientCon->search($params);

                            $count = $search['aggregations']['pageWise']['buckets'];//$search['aggregations']['courseId']['buckets'];
                            $trafficData['dataForDifferentCharts']['dataForDataTable'] = $this->prepareDataForDataTableForTraffic($count,$pageName,$aspect);   
                        }else if($pageName == ''){
                            
                            $extraData = $extraData['studyAbroad'];
                            $params = array();
                            $params['index'] = MISCommonLib::$TRAFFICDATA_SESSIONS;
                            $params['type'] = 'session';
                            $params['body']['size'] = 0;

                            $startDateFilter = array();
                            $startDateFilter['range']['startTime']['gte'] = $dateRange['startDate'].'T00:00:00';
                            $endDateFilter = array();
                            $endDateFilter['range']['startTime']['lte'] = $dateRange['endDate'].'T23:59:59';
                            $params['body']['query']['filtered']['query']['bool']['must'][]['match']['isStudyAbroad'] = "yes";

                            if(is_array($extraData['country']) ){
                                if(sizeof( $extraData['country']) == 1){
                                        $params['body']['query']['filtered']['query']['bool']['must'][]['match']['landingPageDoc.countryId'] = $extraData['country'][0];
                                }else{
                                    foreach ($extraData['country'] as $key => $value) {
                                        $params['body']['query']['filtered']['query']['bool']['should'][]['match']['landingPageDoc.countryId'] = $value;
                                    }
                                    $params['body']['query']['filtered']['query']['bool']['minimum_should_match'] = 1;    
                                }    
                            }else{
                                if($extraData['country'] !=0 ){
                                    $params['body']['query']['filtered']['query']['bool']['must'][]['match']['landingPageDoc.countryId'] = $extraData['country'];    
                                }
                            }
                            
                            if($extraData['categoryId'] !=0 ){
                                $ldbCourseIdsArray = $this->abroadCommonLib->getAbroadMainLDBCourses();
                                foreach ($ldbCourseIdsArray as $key => $value) {
                                    $ldbCouseIds[]= $value['SpecializationId'];
                                }

                                if(in_array($extraData['categoryId'],$ldbCouseIds)){
                                    $params['body']['query']['filtered']['query']['bool']['must'][]['match']['landingPageDoc.LDBCourseId'] = $extraData['categoryId'];
                                }else{
                                    $params['body']['query']['filtered']['query']['bool']['must'][]['match']['landingPageDoc.categoryId'] = $extraData['categoryId'];
                                    if($extraData['courseLevel']!= ''){
                                        if($extraData['courseLevel']!= '0'){
                                            $params['body']['query']['filtered']['query']['bool']['must'][]['match']['landingPageDoc.courseLevel'] = $extraData['courseLevel'];
                                        }
                                    }
                                }
                            }else{  
                                if($extraData['courseLevel']!= ''){
                                    if($extraData['courseLevel']!= '0'){
                                        $params['body']['query']['filtered']['query']['bool']['must'][]['match']['landingPageDoc.courseLevel'] = $extraData['courseLevel'];
                                    }
                                }
                            }

                            $params['body']['query']['filtered']['filter']['bool']['must'][] = $startDateFilter;
                            $params['body']['query']['filtered']['filter']['bool']['must'][] = $endDateFilter;
                            
                            //----------------aggs-----
        
                            $siteSourseFilter = array();
                            $siteSourseFilter['siteSource']['terms']['field'] = 'isMobile';
                            $siteSourseFilter['siteSource']['terms']['size'] = 0;
                            if($aspect != "sessions"){
                                $siteSourseFilter['siteSource']['aggs'] = $this->addAspectToTraffic($aspect);    
                            }

                            $sourceWiseFilter = array();
                            $sourceWiseFilter['sourceWise']['terms']['field']= 'source';
                            $sourceWiseFilter['sourceWise']['aggs'] = $siteSourseFilter;

                            $pageWiseFilter = array();
                            $pageWiseFilter['pageWise']['terms']['field']= 'landingPageDoc.pageIdentifier';
                            $pageWiseFilter['pageWise']['terms']['size']= 0;
                            $pageWiseFilter['pageWise']['aggs'] = $sourceWiseFilter;

                            $params['body']['aggs'] = $pageWiseFilter;
                            $search = $this->clientCon->search($params);

                            $count = $search['aggregations']['pageWise']['buckets'];
                            $trafficData['dataForDifferentCharts']['dataForDataTable'] = $this->prepareDataForDataTableForTraffic($count,$pageName,$aspect);
                        }
                    }
                } 
                return $trafficData;
            }
        }
        //_p($trafficData);die;
        return $trafficData;   
    }

    private function _getPaidTraffic($queryForPaidTraffic){
        //_p(json_encode($queryForPaidTraffic));die;
        $queryForPaidTraffic['body']['query']['filtered']['query']['bool']['must'][]['match']['source'] = "paid";
        $queryForPaidTraffic['body']['aggs']['checkColoum']['filter']['exists']['field'] = 'landingPageDoc.pageIdentifier';
        $campaignWiseFilter['campaignWise']['terms']['field']= 'utm_campaign';
        $campaignWiseFilter['campaignWise']['terms']['size']= 0;
        $queryForPaidTraffic['body']['aggs']['checkColoum']['aggs'] = $campaignWiseFilter;
        //_p(json_encode($queryForPaidTraffic));die;
        $search = $this->clientCon->search($queryForPaidTraffic);
        $campaignwiseData = $search['aggregations']['checkColoum']['campaignWise']['buckets'];
        return $campaignwiseData;
    }

    function getTrafficDataForPieCharts($dateRange,$extraData)
    {
        $elasticQuery= $this->_prepareCDFilters($extraData,$dateRange);
        //_p(json_encode($elasticQuery));
        unset($elasticQuery['body']['aggs']);
        $siteSourceFilter['siteSource']['terms']['field'] = 'isMobile';
        $pageWiseFilter['siteSource']['terms']['size']= 0;

        $sourceWiseFilter['sourceWise']['terms']['field']= 'source';
        $sourceWiseFilter['sourceWise']['aggs'] = $siteSourceFilter;
        $elasticQuery['body']['aggs'] = $sourceWiseFilter;

        $search = $this->clientCon->search($elasticQuery);
        $sourceWise = $search['aggregations']['sourceWise']['buckets'];
        //$responsesData,$pageName,$dateRange,$pageFilter);
        $result = array();
        $i = 0;
        foreach ($sourceWise as $sourceKey => $sourceValue)    // gbd : group by date
        {
            $total += $gbd['doc_count'];
            $siteWise = $sourceValue['siteSource']['buckets'];
            foreach ($siteWise as $siteKey => $siteValue) {
                $result[$i++] = array(
                    "sourceWise" => $sourceValue['key'],
                    'siteSource' => ($siteValue['key'] == 'no')?"Desktop":"Mobile",
                    'responsescount' => $siteValue['doc_count']
                    );
            }
        }    
        return $result;
    }
    function getTrafficDataForCustomerDelivery()
    {   
    }

    function getUniqueUserCountForCustomerDelivery($dateRange,$extraData)
    {
        $elasticQuery = $this->_prepareCDFilters($extraData,$dateRange);
        unset($elasticQuery['body']['aggs']);
        $elasticQuery['body']['aggs']['users']['cardinality']['field'] = 'visitorId';
        $search = $this->clientCon->search($elasticQuery);
        return $search['aggregations']['users']['value'];
    }

    // Function to get traffic top tiles
    public function getTrafficTiles($dateRange, $pageName='', $extraData=array()){

        $elasticQuery = $this->MISCommonLib->prepareTrafficQuery($dateRange, $pageName, $extraData);
//        $elasticQuery['type'] = 'session';
//        $elasticQuery['body']['size'] = 0;

//        $this->_prepareNationalFilters($extraData['National'], $pageName, $elasticQuery);

        $elasticQuery['body']['aggs'] = array(
            'userCount' => array(
                'cardinality' => array(
                    'field' => 'visitorId'
                )
            ),
        );
        $searchResult = $this->clientCon->search($elasticQuery); // Users and sessions count

        $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['term']['sessionNumber'] = 1;
        unset($elasticQuery['body']['aggs']);
        $newSessions = $this->clientCon->search($elasticQuery);

        $pageviewQuery = $this->MISCommonLib->preparePageviewQuery($dateRange, $pageName, $extraData);
        $pageviews = $this->clientCon->search($pageviewQuery);

        $result = array(
            'userCount' => number_format($searchResult['aggregations']['userCount']['value']),
            'sessionCount' => number_format($searchResult['hits']['total']),
            'pageviewCount' => number_format($pageviews['hits']['total']),
            'newsessionPercent' => number_format($newSessions['hits']['total'] / $searchResult['hits']['total'] * 100, 2, '.', '' )
        );

        return $result;
    }

    /**
     *
     * TODO Delete
     * @param        $dateRange
     * @param string $pageName
     * @param array  $extraData
     *
     * @return mixed
     */
    private function getPageViewQuery($dateRange, $pageName = '', $extraData = array())
    {
        $categoryId = 0;
        $subcategoryId = 0;
        $isMobile = 'no';
        $isStudyAbroad = 'yes';
        $deviceType = 'all';

        if( count($extraData) > 0 ){
            $categoryId = $extraData['category'];
            $subcategoryId = $extraData['subcategory'];
            $deviceType = strlen( $extraData['deviceType'] ) > 0 ? $extraData['deviceType'] : 'all';
            $nationalData['deviceType'] = $deviceType;
            if($nationalData['deviceType'] != 'all'){
                if( $nationalData['deviceType'] == 'mobile'  ) {
                    $isMobile = 'yes';
                    $deviceType  = 'mobile';
                } else {
                    $deviceType = 'desktop';
                }
            }

            $isStudyAbroad = 'no';
        }

        $startDate = $dateRange['startDate'];
        $endDate = $dateRange['endDate'];

        $elasticQuery['index'] = MISCommonLib::$TRAFFICDATA_PAGEVIEWS;
        $elasticQuery['type']  = 'pageview';

        $elasticQuery['body']['size'] = 0;
        $elasticQuery['body']['query'] = array();

        if($pageName != 'all'){

            $pageData = $this->MISCommonLib->getPageNameForDomestic($pageName);
            $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = array(
                'term' => array(
                    'pageIdentifier' => $pageData
                )
            );
        }

        if( $deviceType != 'all' ) {
            $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = array(
                'term' => array(
                    'isMobile' => $isMobile
                )
            );
        }

        $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = array(
            'term' => array(
                'isStudyAbroad' => $isStudyAbroad
            )
        );

        $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['range'] = array(
            'visitTime' => array(
                'lte'  => $endDate.'T23:59:59',
                'gte' => $startDate.'T00:00:00'
            )
        );
        if(strtolower($pageName) != 'all'){
            if($pageName != 'home' && $pageName !='institute' && $pageName != 'exam_calendar' && $categoryId != 'all' && $pageName != 'qna'){ // Dont consider category and subcategory if any of this fails
                if ($pageName == 'exam_calendar' || $pageName == 'article_home'){ // Consider only the subcategory
                    if($subcategoryId){
                        $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['term']['subCategoryId'] = intval($subcategoryId);
                    }
                } else if ($pageName == 'article_detail') { // Consider only the category
                    if($categoryId){
                        $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['term']['categoryId'] = intval($categoryId);
                    }
                } else { // Consider the normal (i.e.) other cases other than the ones mentioned above
                    $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['term']['categoryId'] = intval($categoryId);
                    if($subcategoryId){
                        $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['term']['subCategoryId'] = intval($subcategoryId);
                    }
                }
            }
        }else{
            if($categoryId != 'all'){
                $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['term']['categoryId'] = intval($categoryId);    
            }
            if($subcategoryId){
                $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['term']['subCategoryId'] = intval($subcategoryId);
            }   
        }

        $elasticQuery['body']['sort']['visitTime']['order'] = 'asc';
        return $elasticQuery;
    }


    /**
     * Get the trends to be shown on a line chart in the format:
     * [{Date, ScalarValue},{Date2, ScalarValue2}...].
     * Provides the trends for users or sessions (by default sessions)
     *
     * @param array $dateRange The startDate and the endDate
     * @param array $extraData Information (such as deviceType, category, subcategory, view, pivot) to be passed on to this method.
     * @param string $pageName The pagename for which the trend is to be calculated
     *
     * @return array A list of stdClass objects in the format stated.
     */
    public function getTrafficTrends($dateRange, $extraData, $pageName = '')
    {
        $params                 = array();
        $params['index']        = MISCommonLib::$TRAFFICDATA_SESSIONS;
        $params['type']         = 'session';
        $params['body']['size'] = 0;

        if ($extraData['National']) {
            $startDateFilter                              = array();
            $startDateFilter['range']['startTime']['gte'] = $dateRange['startDate'] . 'T00:00:00';
            $endDateFilter                                = array();
            $endDateFilter['range']['startTime']['lte']   = $dateRange['endDate'] . 'T23:59:59';
            $this->_prepareNationalFilters($extraData['National'], $pageName, $params);

            $params['body']['query']['filtered']['filter']['bool']['must'][] = $startDateFilter;
            $params['body']['query']['filtered']['filter']['bool']['must'][] = $endDateFilter;

            $view = $this->MISCommonLib->decideView($dateRange, $extraData['National']['view'], 'es');

            $dateWiseFilter = array(
                'dateWise' => array(
                    'date_histogram' => array(
                        'interval' => $view,
                        'field'    => 'startTime',
                        'order' => array(
                            "_key" => "desc"
                        )
                    ),
                )
            );

            if ($extraData['National']['pivot'] == 'user') {
                $dateWiseFilter['dateWise']['aggs'] = array(
                    'users' => array(
                        'cardinality' => array(
                            'field' => 'visitorId'
                        )
                    )
                );
            }
            $params['body']['aggs'] = $dateWiseFilter;

            $search = $this->clientCon->search($params);

            $result = $search['aggregations'][ 'dateWise' ]['buckets'];
            $resultsToPass = array();

            foreach($result as $index => $oneResult){
                $oneDateResult = new stdClass();
                $oneDateResult->Date = $this->MISCommonLib->extractDate($oneResult['key_as_string']);
                if($extraData['National']['pivot'] == 'user'){
                    $oneDateResult->ScalarValue = $oneResult['users']['value'];
                } else {
                    $oneDateResult->ScalarValue = $oneResult['doc_count'];
                }
                $resultsToPass[] = $oneDateResult;
                unset($result[$index]); // Keep on unsetting the values which we do not need
            }

            if(count($resultsToPass) <= 0){
                return $resultsToPass;
            }

            $resultsToPass = $this->MISCommonLib->insertZeroValues($resultsToPass, $dateRange, $view);
            return $resultsToPass;
        }
    }


    public function getTrafficSplit($dateRange, $extraData, $pageName = '', $splitAspect, $trafficSourceName = ''){
        $params                 = array();

        if ($extraData['National']) {

            $params = $this->MISCommonLib->prepareTrafficQuery($dateRange, $pageName, $extraData);

            if($trafficSourceName != ''){
                $params['body']['query']['filtered']['filter']['bool']['must'][] = array(
                    'term' => array(
                        'source' => $trafficSourceName
                    )
                );
            }

            $getSplitAggregation = function($splitAspect){

                switch($splitAspect){
                    case 'device':
                        $splitParameter = 'isMobile';
                        break;
                    case 'page':
                        $splitParameter = 'landingPageDoc.pageIdentifier';
                        break;
                    case 'widget':
                        $splitParameter = 'keyName';
                        break;
                    case 'pivotType':
                        $splitParameter = 'responseType';
                        break;
                    case 'session':
                        $splitParameter = 'source';
                        break;
                    case 'rmcResponseType':
                        $splitParameter = 'RMCResponseType';
                        break;
                    case 'utmCampaign':
                        $splitParameter = 'utm_campaign';
                        break;
                    case 'utmMedium':
                        $splitParameter = 'utm_medium';
                        break;
                    case 'utmSource':
                        $splitParameter = 'utm_source';
                        break;
                    default:
                        $splitParameter = 'page';
                        break;

                }

                return $splitParameter;
            };

            $splitParameter = $getSplitAggregation($splitAspect);
            $params['body']['aggs'] = array(
                $splitAspect => array(
                    'terms' => array(
                        'field' => $splitParameter,
                        'size' => 0
                    )
                )
            );


            $pivot = $extraData['National']['pivot'];
            if ($pivot == 'user') {
                $params['body']['aggs'][$splitAspect]['aggs'] = array(
                    $pivot => array(
                        'cardinality' => array(
                            'field' => 'visitorId',
                        )
                    )
                );
            }
            $search = $this->clientCon->search($params);
            $result = $search['aggregations'][ $splitAspect ]['buckets'];
            $total = $search['hits']['total'];
            $totalCount = 0;

            $split = array();

            foreach($result as $index => $oneResult){
                $oneSplit = new stdClass();
                if($pivot == 'user'){
                    $oneSplit->ScalarValue = $oneResult[$pivot]['value'];
                } else {
                    $oneSplit->ScalarValue = $oneResult['doc_count'];
                    $totalCount += $oneSplit->ScalarValue;
                }
                if($splitAspect == 'device'){
                    $oneSplit->PivotName = $oneResult['key'] == 'yes' ? 'Mobile' : 'Desktop';
                } else {
                    $oneSplit->PivotName = $oneResult['key'] != '' ? ucfirst(htmlentities($oneResult['key'])) : 'Other';
                }
                $totalCount += $oneSplit->ScalarValue;
                $split[] = $oneSplit;
                unset($result[$index]); // Keep on unsetting the values which we do not need
            }

            $matchCountsFor = array(
                'utmSource',
                'utmMedium',
                'utmCampaign',
            );

            if ($extraData['National']['pivot'] == 'user' && in_array($splitAspect, $matchCountsFor)) { // Adjust the counts here too.
                $total = 0;
                $cardinalityAdjustment = $search['aggregations'][$splitAspect]['buckets'];
                foreach($cardinalityAdjustment as $oneCardinality){
                    $total += $oneCardinality['user']['value'];
                }
            }

            if($extraData['National']['pivot'] == 'session'){
                if($total > $totalCount ||
                    (in_array($splitAspect, $matchCountsFor) && $total == 0)
                ){
                    $otherSplit = new stdClass();
                    $otherSplit->ScalarValue = $total - $totalCount;
					$otherSplit->PivotName = "Other";
                    $split[] = $otherSplit;
                }
            }

            arsort($split);
            foreach($split as $key => $oneSplit){
                $split[$key]->Percentage = number_format($oneSplit->ScalarValue * 100 / $total, 2);
            }
            return array_values($split);
        }

    }
}
