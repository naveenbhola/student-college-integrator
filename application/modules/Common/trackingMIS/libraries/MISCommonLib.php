<?php
require_once('vendor/autoload.php');
class MISCommonLib {
    private $CI;
    public static $TRAFFICDATA_PAGEVIEWS, $TRAFFICDATA_SESSIONS, $RESPONSES_DATA,$REGISTRATION_DATA,$clientCon;
    
    public function __construct(){
        $this->CI = & get_instance();
        $this->usergroupAllowed = array("shikshaTracking");
        $this->abroadCommonLib = $this->CI->load->library('listingPosting/AbroadCommonLib');
        $this->clientCon = $this->_getSearchServerConnection();
        MISCommonLib::$TRAFFICDATA_PAGEVIEWS = PAGEVIEW_INDEX_NAME;
        MISCommonLib::$TRAFFICDATA_SESSIONS  = SESSION_INDEX_NAME;

        MISCommonLib::$REGISTRATION_DATA  = array(
            'indexName' => 'mis_registrations',
            'type' => 'registration'
        );
        $this->colorCodes = array("#FDDB6D",
            "#80DAEB",
            "#FF8243",
            "#BAB86C",
            "#17806D",
            "#C8385A",
            "#71BC78",
            "#7366BD",
            "#FC2847",
            "#0000FF",
            "#8A2BE2",
            "#A52A2A",
            "#DEB887",
            "#5F9EA0",
            "#7FFF00",
            "#D2691E",
            "#FF7F50",
            "#6495ED",
            "#DC143C",
            "#00FFFF",
            "#00008B",
            "#008B8B",
            "#B8860B",
            "#A9A9A9",
            "#006400",
            "#BDB76B",
            "#8B008B",
            "#556B2F",
            "#FF8C00",
            "#414A4C",
            "#FFA089",
            "#95918C",
            "#FDD9B5",
            "#B2EC5D",
            "#E6A8D7",
            "#F780A1",
            "#9F8170",
            "#FF9BAA",
            "#FD7C6E",
            "#FF1DCE",
            "#FDFC74",
            "#77DDE7",
            "#FAE7B5",
            "#A5694F",
            "#3BB08F",
            "#1F75FE",
            "#199EBD",
            "#EA7E5D",
            "#926EAE",
            "#FF7F49",
            "#CDA4DE",
            "#FFBD88",
            "#ADADD6",
            "#FF43A4",
            "#1CD3A2",
            "#76FF7A",
            "#FF48D0",
            "#FDD7E4",
            "#F0E891",
            "#FF7538",
            "#158078",
            "#C364C5",
            "#FF496C",
            "#9D81BA",
            "#EFDBC5",
            "#DEAA88",
            "#BC5D58",
            "#CD4A4A",
            "#FAA76C",
            "#FFA343",
            "#DBD7D2",
            "#B0B7C6",
            "#C5D0E6",
            "#CB4154",
            "#F664AF",
            "#FFBCD9",
            "#45CEA2",
            "#FC89AC",
            "#1DACD6",
            "#EF98AA",
            "#ECEABE",
            "#1CA9C9",
            "#DD9475",
            "#CD9575",
            "#E3256B",
            "#FFA474",
            "#8F509D",
            "#FC74FD",
            "#FDFC74",
            "#9FE2BF",
            "#2B6CC4",
            "#FF1DCE",
            "#A8E4A0",
            "#1974D2",
            "#E7C697",
            "#87A96B",
            "#CC6666",
            "#F75394",
            "#30BA8F",
            "#FB7EFD",
            "#6DAE81",
            "#1DF914",
            "#EE204D",
            "#FCD975",
            "#8E4585",
            "#7442C8",
            "#D68A59",
            "#979AAA",
            "#FFFF99",
            "#FDBCB4",
            "#7851A9",
            "#FFB653",
            "#5D76CB",
            "#DE5D83",
            "#1FCECB",
            "#C5E384",
            "#9ACEEB",
            "#FCB4D5",
            "#CDC5C2",
            "#8A795D",
            "#FC6C85",
            "#FD5E53",
            "#6E5160",
            "#FF5349",
            "#DD4492",
            "#C0448F",
            "#FFAACC",
            "#1A4876",
            "#CA3767",
            "#B4674D",
            "#FCE883",
            "#EDEDED",
            "#A2ADD0",
            "#FFCFAB",
            "#EFCDB8",
            "#FF6E4A",
            "#1CAC78");
    }

    public function _getSearchServerConnection($metric = '', $ESVersion = '') {
        $ESConnectionLib = $this->CI->load->library('trackingMIS/elasticSearch/ESConnectionLib');
        if($metric == "response" || $metric == "RMC" || $ESVersion == 'ES5'){
            $this->clientCon = $ESConnectionLib->getESServerConnectionWithCredentials();
        }else{
            $this->clientCon = $ESConnectionLib->getShikshaESServerConnection();
        }
        return $this->clientCon;
        /*return;
        $this->clientParams = array();
        if(true || ENVIRONMENT == 'production' || ENVIRONMENT == 'beta') {
           
            //$this->clientParams['hosts'] = array('10.10.16.72');
            $this->clientParams['hosts'] = array('10.10.16.101');
            MISCommonLib::$RESPONSES_DATA  = array(
                'indexName' => 'mis_responses',
                'type' => 'response'
            );
        }
        else {
            $this->clientParams['hosts'] = array('172.16.3.111');
            MISCommonLib::$RESPONSES_DATA  = array(
                'indexName' => 'mis_responses',
                'type' => 'response'
            );
        }
        return new Elasticsearch\Client($this->clientParams);*/
    }

    /**
     * Arrange data to be shown on the data grid. The input data contains the elements : PageName, TrafficSource, DeviceName, ResponseCount, Percentage as class properties in any order. The change that this method performs is that it orders the class members in the same order as stated.
     *
     * @param array $gridData The input data
     * @param string $doSort Whether to sort the input data
     */
    public static function arrangeTableData(&$gridData, $doSort = 'false')
    {
        if($doSort){
            arsort($gridData);
        }
        foreach($gridData as $oneKey => $oneData){
            $oneRowForTable = new stdClass();
            $oneRowForTable->PageName = $oneData->PageName;
            $oneRowForTable->TrafficSource = $oneData->TrafficSource;
            $oneRowForTable->DeviceName = $oneData->DeviceName;
            $oneRowForTable->ResponseCount = $oneData->ResponseCount;
            if(isset($oneData->Percentage)){
                $oneRowForTable->Percentage = $oneData->Percentage;
            }

            $gridData[$oneKey] = $oneRowForTable;
        }
    }

    public function getPageName($pageName){
        $pageNameArray = array(

            // Study Abroad Pages
            '404Page'               => '404 Page',
            'articlePage'           => 'Article Page',
            'applyContentPage'      => 'Apply Content',
            'applyHomePage'         => 'Shikha Apply Home',
            'allCountryHome'        => 'Country Home',
            'allcountryHome'        => 'Country Home',
            'countryHome'           => 'Country Home',
            'countryHomePage'       => 'Country Home',
            'categoryPage'          => 'Category Page',
            'courseRankingPage'     => 'Course Ranking',
            'coursePage'            => 'Course Page',
            'countryPage'           => 'Country Page',
            'consultantPage'        => 'Consultant Page',
            'compareCoursesPage'    => 'Compare Courses',
            'deptPage'              => 'Department Page',
            'departmentPage'        => 'Department Page',
            'examPage'              => 'Exam Page',
            'guidePage'             => 'Guide Page',
            'homePage'              => 'Home Page',
            'marketingPage'         => 'Marketing Page',
            'recommendationPage'    => 'Reco Page',
            'rmcSuccessPage'        => 'RMC Success',
            'rmcRegistrationPage'   => 'RMC Registration Page',
            'savedCoursesPage'      => 'Saved Courses Page',
            'savedCoursePage'       => 'Saved Courses Page',
            'shortlistPage'         => 'Saved Courses Page',
            'shortlistTab'          => 'Shortlist Tab',
            'savedCoursesTab'       => 'Saved Courses tab',
            'searchPage'            => 'Search Page',
            'studentCall'           => 'Student Call',
            'stagePage'             => 'Stage Page',
            'universityPage'        => 'University Page',
            'universityRankingPage' => 'University Ranking',

            // Domestic Pages
            'comparePage'           => 'Compare College Page',
            'courseDetailsPage'     => 'Course Listing Page',
            'instituteListingPage'  => 'Institute Listing Page',
            'courseHomePage'        => 'Course Home Page',
            'rankingPage'           => 'Ranking Page',
            //'shortlistPage'         => 'Shortlist Page',
            'rankPredictor'         => 'Rank Predictor',
            'eventCalendar'         => 'Exam Calendar Page',
            'collegeReviewPage'     => 'College Review Page',
            'campusRepresentative'  => 'Campus Connect Page',
            'collegePredictorPage'  => 'College Predictor Page',
            'collegePredictor'      => 'College Predictor',
            'mentorshipPage'        => 'Campus Mentorship Page',
            'qnaPage'               => 'Ask n Answer Page',
            'careerCompasPage'      => 'Career Compass Page',
            'allCoursesPage'        => 'All Courses Page',
            'searchV2Page'          => 'Search Page',
            'questionDetailPage'    => 'Question Detail Page',
            'articleDetailPage'     => 'Article Detail Page',
            //'homePage'              => 'Home Page',
            'MMP'                   => 'Marketing Page',
            //'examPage'              => 'Exam Page',
            'careerHomePage'        => 'Career Home Page',
            'careerDetailPage'      => 'Career Detail Page',
            'careerOpportunities'   => 'Career Opportunities',
            'careerCounselling'     => 'Career Counselling',
            'iimPredictorInput'     => 'IIM Predictor Input',
            'cafeBuzzPage'          => 'Cafe Buzz Page',
            //'articlePage'           => 'Article Home Page',
            'collegeReviewRatingForm' => 'College Review Rating Form',
            'onlineFormDashboard'   => 'OnlineForm Dashboard',
            'collegeReviewIntermediatePage' => 'College Review Intermediate Page',
            'userDetailPage'        => 'User Detail Page',
            'discussionDetailPage'  => 'Discussion Detail Page',
            'examLandingPage'       => 'ExamLanding Page',
            //'campusRepresentative'  => 'Campus Representative Page',
            'discussionPage'        => 'Discussion Home Page',
            'iimPredictorOutput'    => 'IIM Predictor Output',
            'searchResultPage'      => 'Search Result Page',
            'campusRepresentativeIntermediatePage' => 'Campus Connect Intermediate Page',
            'coursePageQuestionPosting' => 'Course Page Question Posting',
            'courseFaqHomePage'     => 'Course Faq Home Page',
            'announcementPage'      => 'Announcement Home Page',
            'campusAmbassadorForm'  => 'Campus Ambassador Form',
            'topSearchPage'         => 'Top Search Page',
            'onlineApplicationForm' => 'Online Application Form',
            //'coursePage'            => 'Course Page',
            //'searchPage'            => 'Search Page',
            'announcementDetailPage' => 'Announcement Detail Page',
            'shortlistedColleges'    => 'Shortlisted Colleges Page',
            'myQnaPage'             => 'My QnA Page',
            'browsePage2'           => 'Browse Page2',
            'browsePage3'           => 'Browse Page3',
            'courseFaqPage'         => 'Course Faq Page',
            'mentorshipForm'        => 'Mentorship Form',
            'browsePage'            => 'Browse Page',
            'loginPage'             => 'Login Page',
        );
        if($pageNameArray[$pageName]){
            return $pageNameArray[$pageName];
        }else{
            return $this->convertToWords($pageName);
            //return inputString.replace(/([a-z])(_){0,}([A-Z])/g, '$1 $3');
        }
        //return $pageNameArray[$pageName]?$pageNameArray[$pageName]:$pageName;
    }

    public function getPageNameForDomestic($pageName){
        $pageNames = array(
                'category'                    => 'categoryPage',
                'home'                        => 'homePage',
                'courselisting'               => 'courseDetailsPage',
                'coursehome'                  => 'courseHomePage',
                'institute'                   => 'instituteListingPage',
                'shortlist'                   => 'shortlistPage',
                'search'                      => 'searchPage',
                'exam'                        => 'examPage',
                'browse'                      => 'browsePage',
                'top_search'                  => 'topSearchPage',
                'ranking'                     => 'rankingPage',
                'college_review_home'         => 'collegeReviewPage',
                'college_review_intermediate' => 'collegeReviewIntermediatePage',
                'campus_rep_home'             => 'campusRepresentative',
                'campus_rep_intermediate'     => 'campusRepresentativeIntermediatePage',
                'compare_colleges'            => 'comparePage',
                'exam_calendar'               => 'eventCalendar',
                'application_form'            => 'onlineFormDashboard',
                'career_home'                 => 'careerHomePage',
                'career_detail'               => 'careerDetailPage',
                'career_counselling'          => 'careerCounselling',
                'career_opportunities'        => 'careerOpportunities',
                'career_compass'              => 'careerCompasPage',
                'article_home'                => 'articlePage',
                'article_detail'              => 'articleDetailPage',
                'qna'                         => 'qnaPage',
                'cafe_buzz'                   => 'cafeBuzzPage',
                'rank_predictor'              => 'rankPredictor',
                'college_predictor'           => 'collegePredictor',
                'mentorship'                  => 'mentorshipPage',
                'question_details'            => 'questionDetailPage',
                'discussionDetail'            => 'discussionDetailPage', // added  by Nithish Reddy
                'iim_predictor'               => 'iimPredictorInput',
                'category_abroad'             => 'categoryPage',
                'search_result'               => 'searchResultPage',
                'allCoursePage'               => 'allCoursesPage',
                'all' => 'all'
        );
        return $pageNames[$pageName];
    }

    public function getPageNameForAbroad($pageName){
        $abroadPageNames = array(
            "article_detail" => "articlePage",
            "category" => "categoryPage",
            "institute" => "universityPage",
            "courselisting" => "coursePage",
            /*
            "courseranking" => "courseRankingPage",
            "universityRanking" => "universityRankingPage",
            "guidePage",
            "homePage",
            "searchPage",
            "countryPage",
            "savedCoursePage",
            "savedCoursesPage",
            "applyContentPage",
            "savedCoursesTab",
            "consultantPage",
            "recommendationPage",
            "departmentPage",
            "stagePage",
            "examPage",
            "countryHomePage",*/
        );

        return $abroadPageNames[$pageName];
    }

    function getDataForUTMTraffic($dateRange,$sourceApplication,$utmCurrentFilter,$teamName)
    {
        //_p($dateRange);_p($sourceApplication);_p($utmCurrentFilter);die;
        $params = array();
        $params['index'] = SESSION_INDEX_NAME;
        $params['type'] = 'session';
        $params['body']['size'] = 0;
        $startDateFilter = array();
        $startDateFilter['range']['startTime']['gte'] = $dateRange['startDate'].'T00:00:00';
        $endDateFilter = array();
        $endDateFilter['range']['startTime']['lte'] = $dateRange['endDate'].'T23:59:59';

        $dataCheckFilter =array();
        $dataCheckFilter['exists']['field'] = 'landingPageDoc.pageIdentifier';

        if($sourceApplication){
            if($sourceApplication == 'mobile'){
                $params['body']['query']['bool']['filter']['bool']['must'][]['term']['landingPageDoc.isMobile'] = "yes";
            }if($sourceApplication == 'desktop'){
                $params['body']['query']['bool']['filter']['bool']['must'][]['term']['landingPageDoc.isMobile'] = "no";
            }
        }
        if($teamName){
            if($teamName == 'abroad'){
                $params['body']['query']['bool']['filter']['bool']['must'][]['term']['landingPageDoc.isStudyAbroad'] = "yes";
            }if($teamName == 'national'){
                $params['body']['query']['bool']['filter']['bool']['must'][]['term']['landingPageDoc.isStudyAbroad'] = "no";
            }
        }

        $params['body']['query']['bool']['filter']['bool']['must'][] = $startDateFilter;
        $params['body']['query']['bool']['filter']['bool']['must'][] = $endDateFilter;
        $params['body']['query']['bool']['filter']['bool']['must'][] = $dataCheckFilter;

        $params['body']['aggs']['checkColoum']['filter']['exists']['field'] = 'landingPageDoc.pageIdentifier';

        $utmFilter['UTMWise']['terms']['field']= $utmCurrentFilter;
        $utmFilter['UTMWise']['terms']['size']= 0;

        $params['body']['aggs']['checkColoum']['aggs'] = $utmFilter;

        //_p(json_encode($params));die;
        $search = $this->clientCon->search($params);
        $UTMTrafficData = $search['aggregations']['checkColoum']['UTMWise']['buckets'];
        $total = $search['hits']['total'];
        
        //**** if we have total counts greater than 20 than we take only top 20 
        //_p($UTMTrafficData);die;
        if(count($UTMTrafficData)>20){
            $UTMTrafficData = array_slice($UTMTrafficData, 0, 20,'true');
        }
        //_p($UTMTrafficData);die;
        foreach ($UTMTrafficData as $key => $value) {
            if($value['key']!=''){
                $UTMTraffic[$value['key']] = $value['doc_count'];
            }
        }
        //_p($UTMTraffic);die;
        $UTMTrafficData = $this->prepareDataForDonutChartForUTMTraffic($UTMTraffic,$this->colorCodes,$total);
        //_p($UTMTrafficData);die;

        return $UTMTrafficData;   
    }

    function prepareDataForDonutChartForUTMTraffic($donutChartData,$colorArray,$total){
        $i = 0; 
        foreach ($donutChartData as $key => $value) {
            $donutChartArray[$i]['value'] = intval($value);
            $donutChartArray[$i]['label'] = $key;
            $donutChartArray[$i]['color'] = $colorArray[$i];
            $splitName = strlen($key) > 17 ? substr($key, 0, 14) . ' ...' : $key;
            $donutChartIndexData=$donutChartIndexData. 
                            '<tr>'.
                                '<td  class="width_60_percent_important">'.
                                    '<p  title="'.$key.'" class="white_space_normal_overwrite"><i class="fa fa-square " style="color: '.$donutChartArray[$i]['color'].'"></i>'.$splitName.'</p>'.
                                '</td>'.
                                '<td >'.number_format((($value*100)/$total), 2, '.', '').'</td>'.
                                '<td >'.number_format($value).'</td>'.
                            '</tr>';                            
            $i++;
        }
        $total = '<h4>Total Count :    '.($total?$total:0).'</h4>';
        $donutChart = array($donutChartArray,$donutChartIndexData,$total);
        return $donutChart;
    }

    public function prepareDataForTrafficSourceFilter($barGraphData,$dateRange,$sourceApplication='',$filters=array(),$source=''){
        // prepare data for traffic source filter 
        //1. show filter value in a single row
        // 2. data in form of bar graph
        // if all row total is less than total, show row with display name as other and remaining count
        $trafficSourceData = $barGraphData[0];
        $total = $barGraphData[1];
        //1. show filter value in a single row
        $i=0;
        arsort($trafficSourceData);
        //_p($trafficSourceData);die;
        foreach ($trafficSourceData as $key => $value) {
            if(($key == 'Other')||($key == '')){
                    continue;
            }
            $trafficSourceArray[$i++] = $key;
            $lis = $lis . 
                    '<li role="presentation"  >'.
                        '<a href="javascript:void(0)" id="'.$key.'" role="tab" data-toggle="tab" aria-expanded="true">'.ucfirst($key).
                        '</a>'.          
                    '</li>';
        }
        //_p($trafficSourceArray);die;
        $prioritySourceArray= array('paid','mailer','social','direct','seo');
        foreach ($prioritySourceArray as $key => $value) {
            if(in_array($value, $trafficSourceArray)){
                $defaultView = $value;
                break;
            }
        }
        $barGraph['lis'] = $lis;
        $barGraph['defaultView'] = $defaultView;
        //_p($defaultView);die;
        // 2. data in form of bar graph
        //$defaultView = 'mailer';
        $barGraph['barGraphData'] = $this->prepareDataForBarGraphForTrafficSource($dateRange,$sourceApplication,$defaultView,$filters,$source);
        return $barGraph;
    }   

    public function prepareDataForBarGraphForTrafficSource($dateRange,$sourceApplication,$defaultView,$filters=array(),$source='')
    {
        $params = array();
        $params['index'] = SESSION_INDEX_NAME;
        $params['type'] = 'session';
        $params['body']['size'] = 0;
        $startDateFilter = array();
        $startDateFilter['range']['startTime']['gte'] = $dateRange['startDate'].'T00:00:00';
        $endDateFilter = array();
        $endDateFilter['range']['startTime']['lte'] = $dateRange['endDate'].'T23:59:59';

        $sourceApplication = strtolower($sourceApplication);
        if($sourceApplication){
            if($sourceApplication == 'mobile'){
                $params['body']['query']['bool']['filter']['bool']['must'][]['term']['isMobile'] = "yes";
            }if($sourceApplication == 'desktop'){
                $params['body']['query']['bool']['filter']['bool']['must'][]['term']['isMobile'] = "no";
            }
        }

        if($source){
            $params['body']['query']['bool']['filter']['bool']['must'][]['term']['isStudyAbroad'] = ($source=='abroad')?'yes':'no';
        }

        if($source == 'abroad' && $filters['pageName'] !=''){
            $pageName = $filters['pageName'];
            $pageType = $filters['pageType'];

            if($pageName == 'rankingPage'){
                if($pageType == 0){
                    $courserankingFilter['bool']['should'][]['term']['landingPageDoc.pageIdentifier'] = 'courseRankingPage';
                    $courserankingFilter['bool']['should'][]['term']['landingPageDoc.pageIdentifier'] ='universityRankingPage';
                    $params['body']['query']['bool']['filter']['bool']['must'][] = $courserankingFilter;
                    
                }else if($pageType == 1){
                    $params['body']['query']['bool']['filter']['bool']['must'][]['term']['landingPageDoc.pageIdentifier'] = 'universityRankingPage';        
                }else if($pageType == 2){
                    $params['body']['query']['bool']['filter']['bool']['must'][]['term']['landingPageDoc.pageIdentifier'] = 'courseRankingPage';    
                }    
            }else{
                $params['body']['query']['bool']['filter']['bool']['must'][]['term']['landingPageDoc.pageIdentifier'] = $pageName;    
            }
        }

        // top filters
        if($filters['categoryId'] !=0 ){
            $ldbCourseIdsArray = $this->abroadCommonLib->getAbroadMainLDBCourses();
            foreach ($ldbCourseIdsArray as $key => $value) {
                $ldbCourseIds[]= $value['SpecializationId'];
            }
            if(in_array($filters['categoryId'],$ldbCourseIds)){
                $params['body']['query']['bool']['filter']['bool']['must'][]['term']['landingPageDoc.LDBCourseId'] = $filters['categoryId'];
            }else{
                $params['body']['query']['bool']['filter']['bool']['must'][]['term']['landingPageDoc.categoryId'] = $filters['categoryId'];
                if($filters['courseLevel']!= ''){
                    if($filters['courseLevel']!= '0'){
                        $params['body']['query']['bool']['filter']['bool']['must'][]['term']['landingPageDoc.courseLevel'] = $filters['courseLevel'];
                    }
                }
            }
        }else{  
            if($filters['courseLevel']!= ''){
                if($filters['courseLevel']!= '0'){
                    $params['body']['query']['bool']['filter']['bool']['must'][]['term']['landingPageDoc.courseLevel'] = $filters['courseLevel'];
                }
            }
        }


        if(is_array($filters['country']) ){
            if(sizeof($filters['country']) == 1){
                    $params['body']['query']['bool']['filter']['bool']['must'][]['term']['landingPageDoc.countryId'] = $filters['country'][0];
            }else{

                foreach ($filters['country'] as $key => $value) {
                    $countries[] = $value;
                }
                $params['body']['query']['bool']['filter']['bool']['must'][]['bool']['should'][]['terms']['landingPageDoc.countryId'] = $countries;  
            }    
        }else{
            if($filters['country'] !=0 ){
                $params['body']['query']['bool']['filter']['bool']['must'][]['term']['landingPageDoc.countryId'] = $filters['country'];    
            }
        }

        $params['body']['query']['bool']['filter']['bool']['must'][] = $startDateFilter;
        $params['body']['query']['bool']['filter']['bool']['must'][] = $endDateFilter;
        $params['body']['query']['bool']['filter']['bool']['must'][]['term']['source'] = $defaultView;

        $trafficTrends = function($filter){
            if ($filter['aspect'] == 'users') {
                $trafficAspect = array(
                    'pivot' => array(
                        'cardinality' => array(
                            'field' => 'visitorId'
                        )
                    )
                );
                return $trafficAspect;
            } else if ($filter['aspect'] == 'pageviews') {
                $trafficAspect = array(
                    'pivot' => array(
                        'sum' => array(
                            'field' => 'pageviews'
                        )
                    )
                );
                return $trafficAspect;
            }
        };
        //_p(json_encode($params));die;
        // now for diff utms change aggs
        //1. For UTM Source
        $UTMFilter['UTMSource']['terms']['field']= 'utm_source';
        $UTMFilter['UTMSource']['terms']['size']= 0;
        if($filters['aspect'] != ''){
            if($filters['aspect'] != 'sessions'){
                $UTMFilter['UTMSource']['aggs'] =$trafficTrends($filters);    
            }
        }   
        $params['body']['aggs'] = $UTMFilter;
        $search = $this->clientCon->search($params);
        $UTMSourceData = $search['aggregations']['UTMSource']['buckets'];
        if($filters['aspect'] == 'users') {
            $params['body']['aggs'] = array(
                    'userCount' => array(
                            'cardinality' => array(
                                    'field'=>'visitorId'
                                )
                        )
                );
            $search = $this->clientCon->search($params);
            $UTMSourceDataCount = $search['aggregations']['userCount']['value'];
        }else{
            $UTMSourceDataCount = $search['hits']['total'];
        }
        
        $UTMSourceData = $this->prepareDataForBarGraphForTraffic($UTMSourceData,$UTMSourceDataCount,$filters['aspect'],0);    
        
        //2. For UTM Campaign
        $UTMFilter =array();
        $UTMFilter['UTMMedium']['terms']['field']= 'utm_medium';
        $UTMFilter['UTMMedium']['terms']['size']= 0;
        if($filters['aspect'] != ''){
            if($filters['aspect'] != 'sessions'){
                $UTMFilter['UTMMedium']['aggs'] =$trafficTrends($filters);
            }
        }
        $params['body']['aggs'] = $UTMFilter;
        $search = $this->clientCon->search($params);
        $UTMMediumData = $search['aggregations']['UTMMedium']['buckets'];
        if($filters['aspect'] == 'users') {
            $params['body']['aggs'] = array(
                    'userCount' => array(
                            'cardinality' => array(
                                    'field'=>'visitorId'
                                )
                        )
                );
            $search = $this->clientCon->search($params);
            $UTMMediumDataCount = $search['aggregations']['userCount']['value'];
        }else{
            $UTMMediumDataCount = $search['hits']['total'];
        }
        $UTMMediumData = $this->prepareDataForBarGraphForTraffic($UTMMediumData,$UTMMediumDataCount,$filters['aspect'],0);    
        
        
        //_p(json_encode($params));die;

        //3. For UTM Medium
        $UTMFilter =array();
        $UTMFilter['UTMCampaign']['terms']['field']= 'utm_campaign';
        $UTMFilter['UTMCampaign']['terms']['size']= 0;
        if($filters['aspect'] != ''){
            if($filters['aspect'] != 'sessions'){
                $UTMFilter['UTMCampaign']['aggs'] =$trafficTrends($filters);
            }
        }
        
        $params['body']['aggs'] = $UTMFilter;
        $search = $this->clientCon->search($params);
        $UTMCampaignData = $search['aggregations']['UTMCampaign']['buckets'];                
        if($filters['aspect'] == 'users') {
            $params['body']['aggs'] = array(
                    'userCount' => array(
                            'cardinality' => array(
                                    'field'=>'visitorId'
                                )
                        )
                );
            $search = $this->clientCon->search($params);
            $UTMCampaignDataCount = $search['aggregations']['userCount']['value'];
        }else{
            $UTMCampaignDataCount = $search['hits']['total'];
        }
        
        $UTMCampaignData = $this->prepareDataForBarGraphForTraffic($UTMCampaignData,$UTMCampaignDataCount,$filters['aspect'],1);
        
        
        $inputArray = array(
                            'utmSource' =>$UTMSourceData,
                            'utmCampaign' => $UTMCampaignData,
                            'utmMedium' => $UTMMediumData
                            );
        $barGraphData = $this->prepareTrafficSourceBarGraphForTraffic($inputArray);
        //_p(json_encode($params));die;

        return $data = $barGraphData;
    }
    
    function prepareBarGraphForUTMDataForTraffic($number,$heading,$data){
        if($data['count'] >=10){
            $height = 320;    
        }else{
            $height = $data['count']*36;
        }
        $class = "col-md-6 col-sm-6 col-xs-12";
        $result = '<div  id="BGraph'.$number.'" >'.
            '<div class="x_panel tile " style="padding-right:5px !important">'.
                '<div class="x_title " >'.
                    '<div  class= "pieHeadingSmallSA" style="width: 100%">'.
                        '<table style="width:100%">'.
                                    '<tr>'.
                                        '<td colspan=2>'.$heading.'</td>'.
                                        '<td style="text-align: left; width: 70px">'.'     '.'</td>'.
                                        '<td  class="w_right showGrowth" style="text-align: left;width:10%;display:none;font-size:12px">'.'MOM'.'</td>'.
                                        '<td  class="w_right showGrowth" style="text-align: left;width:10%;display:none;font-size:12px">'.'YOY'.'</td>'.
                                    '</tr>'.
                                '</table>'.
                    '</div>'.
                    '<div class="clearfix"></div>'.
                '</div>'.
                '<div class="loader_small_overlay" id="barGraphHorizental_'.$number.'" style="display:none"><img src="'.SHIKSHA_HOME.'/public/images/trackingMIS/mis-loading-small.gif"></div>'.
                '<div class="x_content overflow_BR" id="barGraph'.$number.'" style="height: '.$height.'px;padding-right:0px !important;padding-left:0px !important">'.$data['barGraph'].
                '</div>'.
            '</div>'.
        '</div>';
        return $result;
    }

    function prepareTrafficSourceBarGraphForTraffic($barGraph){
        //_p($barGraph);die;
        if(! $barGraph['utmSource'] && !$barGraph['utmCampaign'] && !$barGraph['utmMedium']){
            return null;
        }

        $utmSource = $this->prepareBarGraphForUTMDataForTraffic(1,'UTM Source',$barGraph['utmSource']);
        $utmCampaign = $this->prepareBarGraphForUTMDataForTraffic(2,'UTM Campaign',$barGraph['utmCampaign']);
        $utmMedium = $this->prepareBarGraphForUTMDataForTraffic(3,'UTM Medium',$barGraph['utmMedium']);
        $l1 = $barGraph['utmSource']['count'];
        $l3 = $barGraph['utmCampaign']['count'];
        $l2 = $barGraph['utmMedium']['count'];

        if($l1 > $l2){
            $maxL = 1;
        }else{
            $maxL = 2;
        }
        //_p($l1);_p($l2);_p($maxL);die;
        $dataValue = '<div class="col-md-6 col-sm-6 col-xs-12">'.$utmSource.'</div>'.
                     '<div class="col-md-6 col-sm-6 col-xs-12">'.$utmMedium.'</div>'.
                     '<div class="col-md-12 col-sm-12 col-xs-12">'.$utmCampaign.'</div>';
                
        //_p($dataValue);die;
        return $dataValue;
    }

    public function prepareDataForBarGraphForTrafficAspect($UTMData,$UTMDataCount,$aspect)
    {  
        //_p($UTMData);_p($UTMDataCount);die;
        //_p($flag);_p($showGrowth);die;
        foreach ($UTMData as $key => $value) {
            if($value['key'] ==''){
                unset($UTMData[$key]);
            }else{
                $UTMArray[$value['key']] = $value['pivot']['value'];
                $count = $count + $value['doc_count'];
            }  
        }
        if($UTMDataCount > $count){
            $diff = $UTMDataCount -$count;
            $UTMArray['Others'] = $diff;
        }
        
        arsort($UTMArray);
        $maxValue = 0;
        $i=0;
        foreach ($UTMArray as $key => $value) { 
            if($i==0){
                $maxValue = $value;
            }else{
                break;   
            }
            $i++;
        }
        $avg = number_format((($maxValue)/100), 2,'.','');
        //_p($avg);die;
        
        $leftWidth = 40;
        $centerWidth  = 30;
        $countWidth =   30;
        $pageNameWidth = 26;
            
        $barGraph = '<table style="width: 100%;">';
        foreach ($UTMArray as $key => $value) {   
            $normalizeValue = number_format(($value/$avg), 0, '.', '');
            $actualValue = number_format(($value));
            $title = ucfirst($key);
            $fieldName = limitTextLength($title,$pageNameWidth);
            $span = '<span title="'.htmlentities($title).'">'.htmlentities($fieldName).'</span>';
            $percantageValue = number_format((($value*100)/$UTMDataCount), 2,'.','');
            
            $field ='<td class="BGHeading_fontSize" style="width:'.$countWidth.'% !important">&nbsp&nbsp'.$actualValue.' ( '.$percantageValue.'%)'.
                    '</td>';    
            

            $barGraph = $barGraph.
            '<tr class="widget_summary">'.
                '<td class="w_left" style="width:'.$leftWidth.'% !important">'.
                   $span.
                '</td>'.
                '<td class="w_center " style="width:'.$centerWidth.'% !important">'.
                    '<div class="progress" style="margin-bottom:10px !important" >'.
                        '<div  title = "'.$actualValue.'" class="progress-bar bg-green" role="progressbar" style="width:'.$normalizeValue.'%'.'" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100">'.
                            '<span class="sr-only"  ></span>'.
                        '</div>'.
                    '</div>'.
                '</td>'.$field.
                '<div class="clearfix"></div>';   
        }
        
        $barGraph = $barGraph.'</table>';
        //die;_p($barGraph);die;
        return $barGraph;
    }

    public function prepareDataForBarGraphForTraffic($UTMData,$UTMDataCount,$aspect='sessions',$sizeFlag=0)
    {  
        //_p($UTMData);_p($UTMDataCount);die;
        if($aspect =='users'){
            foreach ($UTMData as $key => $value) {
                if($value['key'] ==''){
                    unset($UTMData[$key]);
                }else{
                    $UTMArray[$value['key']] = $value['pivot']['value'];
                    $count = $count + $value['doc_count'];    
                }  
            }
            if($UTMDataCount > $count){
                $diff = $UTMDataCount -$count;
                $UTMArray['Others'] = $diff;
            }
        }else{
            foreach ($UTMData as $key => $value) {
                if($value['key'] ==''){
                    unset($UTMData[$key]);
                }else{
                    $UTMArray[$value['key']] = $value['doc_count'];
                    $count = $count + $value['doc_count'];    
                }  
            }
            
            if($UTMDataCount > $count){
                $diff = $UTMDataCount -$count;
                $UTMArray['Others'] = $diff;
            }
        }

        arsort($UTMArray);
        $maxValue = 0;
        $i=0;
        foreach ($UTMArray as $key => $value) { 
            if($i==0){
                $maxValue = $value;
            }else{
                break;   
            }
            $i++;
        }
        $avg = number_format((($maxValue)/100), 2,'.','');
        //_p($avg);die;
        
        
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
        foreach ($UTMArray as $key => $value) {   
            $normalizeValue = number_format(($value/$avg), 0, '.', '');
            $actualValue = number_format(($value));
            $title = ucfirst($key);
            $fieldName = limitTextLength($title,$pageNameWidth);
            $span = '<span title="'.htmlentities($title).'">'.htmlentities($fieldName).'</span>';
            $percantageValue = number_format((($value*100)/$UTMDataCount), 2,'.','');
            
            $field ='<td class="BGHeading_fontSize" style="width:'.$countWidth.'% !important">&nbsp&nbsp'.$actualValue.' ( '.$percantageValue.'%)'.
                    '</td>';    
            

            $barGraph = $barGraph.
            '<tr class="widget_summary">'.
                '<td class="w_left" style="width:'.$leftWidth.'% !important">'.
                   $span.
                '</td>'.
                '<td class="w_center " style="width:'.$centerWidth.'% !important">'.
                    '<div class="progress" style="margin-bottom:10px !important" >'.
                        '<div  title = "'.$actualValue.'" class="progress-bar bg-green" role="progressbar" style="width:'.$normalizeValue.'%'.'" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100">'.
                            '<span class="sr-only"  ></span>'.
                        '</div>'.
                    '</div>'.
                '</td>'.$field.
                '<div class="clearfix"></div>';   
        }
        
        $barGraph = $barGraph.'</table>';
        //die;_p($barGraph);die;
        //return $barGraph;
        return  array('barGraph'=>$barGraph,
                    'count' => count($UTMArray));   
    }

    function prepareElasticQueryForShikshaTraffic($dateRange,$sourceApplication){
        $params = array();
        $params['index'] = SESSION_INDEX_NAME;
        $params['type'] = 'session';
        $params['body']['size'] = 0;
        $startDateFilter = array();
        $startDateFilter['range']['startTime']['gte'] = $dateRange['startDate'].'T00:00:00';
        $endDateFilter = array();
        $endDateFilter['range']['startTime']['lte'] = $dateRange['endDate'].'T23:59:59';
        
        $params['body']['query']['bool']['filter']['bool']['must'][] = $startDateFilter;
        $params['body']['query']['bool']['filter']['bool']['must'][] = $endDateFilter;

        if($sourceApplication){
            if($sourceApplication == 'Mobile'){
                $params['body']['query']['bool']['filter']['bool']['must'][]['term']['isMobile'] = "yes";
            }if($sourceApplication == 'Desktop'){
                $params['body']['query']['bool']['filter']['bool']['must'][]['term']['isMobile'] = "no";
            }
        }
        return $params;
    }

    function prepareDataForLineChartForShikshaTraffic($dateWiseData,$dateRange,$view,$actualCount,$flagValue=0, $aspect='sessions')
    {
        $gendate = new DateTime();
        $startYear = date('Y', strtotime($dateRange['startDate']));
        $endYear = date('Y', strtotime($dateRange['endDate']));

        if($view == 'day'){
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
        }else if($view == 'week'){
            if($startYear == $endYear)
            {
                // creating week array
                $swn = date('W', strtotime($dateRange['startDate']));
                $ewn = date('W', strtotime($dateRange['endDate'])); 
                $lineArray = array();
                $lineArray[$dateRange['startDate']] = 0;//$lineChartData[$swn];
                if($swn > $ewn){
                    $swn =0;
                }
                for ($i=$swn; $i <= $ewn ; $i++) { 
                    $gendate->setISODate($startYear,$i,1); //year , week num , day
                    $df = $gendate->format('Y-m-d');
                    $lineArray[$df] = 0;   
                }
                
                // for managing startDate traffic data
                $gendate->setISODate($startYear,$swn,1); //year , week num , day
                $df = $gendate->format('Y-m-d');
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
                // for managing startDate traffic data
                $gendate->setISODate($startYear,$swn,1); //year , week num , day
                $df = $gendate->format('Y-m-d');
                //_p($lineArray);die;
            }    
        }else if($view == 'month'){   
            if($startYear == $endYear){
                $smn = date('m', strtotime($dateRange['startDate']));
                $emn = date('m', strtotime($dateRange['endDate']));
                $lineArray = array();
                $df = $startYear.'-'.$smn.'-01';
                $lineArray[$df] = 0;
                $lineArray[$dateRange['startDate']] = 0;       
                for ($i=$smn+1; $i <= $emn ; $i++){
                    $i = intval($i);
                    if($i <= 9){
                        $i = '0'.$i;
                        $df = $startYear.'-'.$i.'-01';
                    }else{
                        $df = $startYear.'-'.$i.'-01';    
                    }
                    $lineArray[$df] = 0;       
                }
                //_p($lineArray);
                $df = $startYear.'-'.$smn.'-01';
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
                        $j = intval($j);
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
                    //$pieChartDataOne[$value['trafficSource']]+= $value['visitCount'];
                    $pieChartDataTwo[$value['siteSource']]+= $value['visitCount'];
                    $page = $value['pageWise'];
                    $page = $this->MISCommonLib->getPageName($page);
                    $pieChartDataThree[$page]+=$value['visitCount'];               
                }
                $df = $startYear.'-'.$smn.'-01';
                //_p($lineArray);_p($pieChartDataOne);_p($pieChartDataTwo);die;
            }
        }
		if($flagValue == 1){
            foreach ($dateWiseData as $key => $value)    // gbd : group by date
            {
                $lineArray[$key] = $value;
            }
        }else{
       		$total = 0; 
        	foreach ($dateWiseData as $key => $value)    // gbd : group by date
            {
				if($aspect == 'sessions'){
	                $total += $value['doc_count'];
    	            $lineArray[date("Y-m-d", strtotime($value['key_as_string']))] = $value['doc_count'];
				} else if($aspect == 'users'){
                    $total += $value['usersCount']['value'];
                    $lineArray[date("Y-m-d", strtotime($value['key_as_string']))] = $value['usersCount']['value'];
                }else if($aspect == 'pageviews'){
                    $total += $value['pageviews']['value'];
                    $lineArray[date("Y-m-d", strtotime($value['key_as_string']))] = $value['pageviews']['value'];
                }else{
                    $total += $value['doc_count'];
                    $lineArray[date("Y-m-d", strtotime($value['key_as_string']))] = $value['doc_count'];
                }
            }
        }
        /*
            $resData[] = array(
                            "visitDate" => date("Y-m-d", strtotime($dateArray['key_as_string'])),
                            "visitCount" => $sourceApplicationArray['doc_count']
                            );
                   
            if($tempTotal > $tempTotalData){
                $resData[] = array(
                                "visitDate" => date("Y-m-d", strtotime($dateArray['key_as_string'])),
                                "sourceApplication" => "Other",
                                "visitCount" => $tempTotal - $tempTotalData
                                );
            }*/
        //_p($actualCount);_p($total);die;
        
        if($view != 'day'){
            if(($lineArray[$dateRange['startDate']] == 0) && ($dateRange['startDate'] != $df)){
                $lineArray[$dateRange['startDate']] = $lineArray[$df];
                unset($lineArray[$df]);
            }
        }
        //_p($lineArray);die;
        return $this->prepareDataForLineChart($lineArray);
    }

    function prepareDonutChartData($params,$filter,$colorCodes,$flag =0, $aspect='sessions'){
        $pageWiseFilter = array(
            'pivot' => array(
                'terms' => array(
                    'field' => $filter,
                    'size' => 0
                )
            )
        );
        // we use terms rather than cardinality  in count distinct value, but it will take more time than expected.

        if ($aspect == 'users') {
            $pageWiseFilter['pivot']['aggs'] = array(
                'usersCount' => array(
                    'cardinality' => array(
                        'field' => 'visitorId'
                    )
                )
            );
        } else if ($aspect == 'pageviews') {
            $pageWiseFilter['pivot']['aggs'] = array(
                'pageviews' => array(
                    'sum' => array(
                        'field' => 'pageviews'
                    )
                )
            );
        }

        $params['body']['aggs'] = $pageWiseFilter;
        if($filter == 'source'){
            //_p(json_encode($params));die;
        }
        //_p(json_encode($params));die;
        $search = $this->clientCon->search($params);
        $result = $search['aggregations']['pivot']['buckets'];
        $actualCount = $search['hits']['total'];
        if ($aspect == 'users') {
            unset($params['body']['aggs']);
            $params['body']['aggs'] = array(
                'userCountData'=> array(
                    'cardinality'=>array(
                        'field'=>"visitorId"                        
                    )
                )
            );
            $search = $this->clientCon->search($params);
            //_p(json_encode($params));die;
            $actualCount = $search['aggregations']['userCountData']['value'];
        } else if ($aspect == 'pageviews') {
            unset($params['body']['aggs']);
            $search = $this->clientCon->search($params);
            $actualCount = $search['hits']['total'];
        }
        
        $count = 0;
        foreach ($result as $key => $value) {
            $sourceApplicationArray = array('yes','no');
            if(in_array($value['key'], $sourceApplicationArray)){
                $temp = ($value['key']=='no')?"Desktop":"Mobile";
            }else{
                if(($filter == 'landingPageDoc.pageIdentifier')||($filter == 'pageIdentifier')){
                    $temp = $this->getPageName($value['key']);
                }else{
                    $temp =$value['key'];    
                }    
            }

            if($aspect == 'users'){
                $resultArray[$temp] = $value['usersCount']['value'];
                $count += $value['usersCount']['value'];
            } else if($aspect == 'sessions') {
                if($temp==''){
                    $resultArray['Other'] = $value['doc_count'];
                }else{
                    $resultArray[$temp] = $value['doc_count'];    
                }
                $count += $value['doc_count'];
            } else if($aspect == 'pageviews') {
                $resultArray[$temp] = $value['pageviews']['value'];
                $count += $value['pageviews']['value'];
            }
        }

        if($aspect != 'users') {
            if($actualCount > $count){
                $resultArray['Other'] = $actualCount - $count;
            }
        }
        if($flag == 1){
            return array($resultArray,$actualCount);
        }else{
            return $this->prepareDataForDonutChart($resultArray,$colorCodes,$actualCount);    
        }        
    }

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
        foreach ($donutChartData as $key => $value) {
            $donutChartArray[$i]['value'] = intval($value);
            $donutChartArray[$i]['label'] = $key;
            $donutChartArray[$i]['color'] = $colorArray[$i];
            $splitName = strlen($key) > 16 ? substr($key, 0, 12) . ' ...' : $key;
            $donutChartIndexData=$donutChartIndexData. 
                            '<tr>'.
                                '<td class="width_60_percent_important">'.
                                    '<p title="'.$key.'" class="white_space_normal_overwrite"><i class="fa fa-square " style="color: '.$donutChartArray[$i]['color'].'"></i>'.$splitName.'</p>'.
                                '</td>'.
                                '<td >'.number_format((($value*100)/$total), 2, '.', '').'</td>'.
                                '<td >'.number_format($value).'</td>'.
                            '</tr>';                            
            $i++;
        }
        $total = '<h4>Total Count :    '.($total?number_format($total):0).'</h4>';
        $donutChart = array($donutChartArray,$donutChartIndexData,$total);
        return $donutChart;
    }

    /**
     * Works on a descending order of dates and inserts zero values so that on a graph ALL dates as stated in the $dates are covered.
     *
     * @param array $trends An array of stdClass objects containing Date and ScalarValue as the elements
     * @param array $dates An array of dates with startDate and endDate as the index (case sensitive)
     * @param int $viewType An identifier which identifies the grouping to be used on the line chart
     *
     * @return array A list of elements with zero values wherever there was no value in the resultset
     */
    public function insertZeroValues($trends, $dates, $viewType = 1)
    {
        $increment = 0;
        switch ($viewType) {
            case 1:
            case 'day':
                $increment = ' +1 day';
                break;
            case 2:
            case 'week':
                $increment = ' +1 week';
                break;
            case 3:
            case 'month':
                $increment = ' +1 month';
                break;
            default:
                $increment = ' +1 day'; // Find some better fallback
                break;
        }

        $initialDate = $trends[count($trends)-1]->Date; // Get the starting date from the fetched data
        if(strtotime($initialDate) > strtotime($dates['startDate'])){ // adjust the starting date
            $decrement = str_replace("+", "-", $increment);
            for ($starting = date('Y-m-d', strtotime($initialDate . $decrement)); strtotime($starting) >= strtotime($dates['startDate']); $starting = date('Y-m-d', strtotime($starting . $decrement))) {
                $firstDateValue = new stdClass();
                $firstDateValue->Date = $starting;
                $firstDateValue->ScalarValue = 0;
                $trends[] = $firstDateValue;
            }
        }
        $initialDate = $trends[count($trends)-1]->Date;

        foreach ($trends as $oneIndex => $oneDateResponse) {
            $trends[ strtotime($oneDateResponse->Date) ] = $oneDateResponse->ScalarValue;
            unset($trends[ $oneIndex ]);
        }

        for ($starting = $initialDate; strtotime($starting) <=strtotime($dates['endDate']); $starting = date('Y-m-d', strtotime($starting . $increment))) {
            if (
                !isset($trends[ strtotime($starting) ]) &&
                $initialDate == $dates['startDate']
            ) {
                $trends[ strtotime($starting) ] = 0;
            }
        }
        ksort($trends);

        $startDate = array_keys($trends);
        $startDate = date('Y-m-d', $startDate[0]);

        for ($starting = date('Y-m-d', strtotime($startDate)); strtotime($starting) < strtotime($dates['endDate']); $starting = date('Y-m-d', strtotime($starting . $increment))) {

            if(!isset($trends[strtotime($starting)])){
                $trends[strtotime($starting)] = 0;
            }
        }

        // if the first date in trends is still ahead of the startdate, add a new interval
        if(strtotime($initialDate) > strtotime($dates['startDate'])){
            $trends[strtotime($dates['startDate'])] = 0;
        }

        ksort($trends);

        $index = 0;
        foreach ($trends as $key => $value) {
            $oneResponse              = new stdClass();
            $oneResponse->Date        = date('Y-m-d', $key);
            $oneResponse->ScalarValue = $value;
            unset($trends[ $key ]);
            $trends[ $index ] = $oneResponse;
            $index++;
        }

        $finalDate = $trends[$index-1]->Date; // Get the final date from the processed data
        for ($starting = date('Y-m-d', strtotime($finalDate . $increment)); strtotime($starting) < strtotime($dates['endDate']); $starting = date('Y-m-d', strtotime($starting . $increment))) {
            $lastDateValue = new stdClass();
            $lastDateValue->Date = $starting;
            $lastDateValue->ScalarValue = 0;
            $trends[$index] = $lastDateValue;
            $index++;
        }

        // Shift the starting date to match the startDate
        if(strtotime($trends[0]->Date) < strtotime($dates['startDate'])){
            $trends[0]->Date = $dates['startDate'];
        }

        return $trends;
    }

    /**
     * Get grouping interval to be used while querying the datastore
     *
     * @param array $dateRange The startdate and the enddate
     *
     * @return string The grouping interval identifier to be used
     *
     * TODO Migrate to the new method MISCommonLib::decideView($dateRange, $view, $dataStore='db')
     */
    public function getPeriodForGrouping($dateRange, $viewType='day')
    {
        $allowedViewTypes = array(
            'day',
            'week',
            'month'
        );

        if(!in_array($viewType, $allowedViewTypes)){
            $viewType = 'day';
        }
        $dateDifference = (strtotime($dateRange['endDate']) - strtotime($dateRange['startDate'])) / (60 * 60 * 24);
        $period         = 'day';
        if ($dateDifference > 60 && $dateDifference <= 180) {
            $period = 'week';
        } else if ($dateDifference > 180) {
            $period = 'month';
        }

        if(
            ($viewType == 'day' && $period == 'week') ||
            ($viewType == 'week' && $period == 'month') ||
            ($viewType == 'day' && $period == 'month')
        ){
            return $period;
        } else if (
            ($viewType == 'month' && $period == 'week') ||
            ($viewType == 'week' && $period == 'day') ||
            ($viewType == 'month' && $period == 'day')
        ){
            return $viewType;
        }

        return $period;
    }


    public static function getView($view){
        switch($view){
            case 1:
                return 'day';

            case 2:
                return 'week';

            case 3:
                return 'month';

            default:
                return 'day';
        }
    }

    /**
     * Decide the type of view to be used to query the datastore based on the following coding:
     *
     * <table>
     * <tr>
     *  <td>1</td><td>Day</td>
     * </tr>
     * <tr>
     *  <td>2</td><td>Week</td>
     * </tr>
     * <tr>
     *  <td>3</td><td>Month</td>
     * </tr>
     * </table>
     *
     * @param array $dateRange An array containing the startDate and the endDate as received from the controller
     * @param int    $viewType The type of view passed from the URL. Please see table above
     * @param string $dataStore The type of data store to be queried. ES seems unused : can this param be dropped?
     *
     * @return string The type of view to be used to query the data store
     */
    public function decideView($dateRange, $viewType=1, $dataStore='db')
    {
        $allowedViewTypes = array(
            'es' => array(
                'day',
                'week',
                'month'
            ),
            'db' => array(
                "1", "2", "3"
            )
        );

        $dateDifference = (strtotime($dateRange['endDate']) - strtotime($dateRange['startDate'])) / (60 * 60 * 24);
        $period         = 0; // day is 1 hence period is the index of the allowedViewTypes array i.e. 0
        if ($dateDifference > 60 && $dateDifference <= 180) {
            $period = 1; // week is 2 hence period would be 1
        } else if ($dateDifference > 180) {
            $period = 2; // month is 3 hence period would be 2
        }

        if(
            ($viewType == 1 && $period == 1) ||
            ($viewType == 2 && $period == 2) ||
            ($viewType == 1 && $period == 2)
        ){
            return $allowedViewTypes[$dataStore][$period];
        } else if (
            ($viewType == 3 && $period == 1) ||
            ($viewType == 2 && $period == 0) ||
            ($viewType == 3 && $period == 0)
        ){
            return $allowedViewTypes[$dataStore][$viewType-1];
        }

        return $allowedViewTypes[$dataStore][$period];
    }

    /**
     * Get YYYY-MM-DD formatted date for a date provided by elasticsearch
     *
     * @param string $elasticDate A date provided by some elasticsearch query result
     *
     * @return string The date in the desired format
     */
    public function extractDate($elasticDate){
        $properDate = explode('T', $elasticDate);
        return $properDate[0];
    }

    /**
     * Make camel cased or underscored word into separate words
     *
     * @param string $inputWord The camel cased or underscored word
     *
     * @return string The separated words
     */
    public function convertToWords($inputWord){
        return ucfirst(preg_replace('/([a-z])(_){0,}([A-Z])/', '$1 $3', $inputWord));
    }


    /**
     * Get a guaranteed value for a variable identified by a type.
     *
     * @param string $type     The variable identifier
     * @param string $variable The variable itself
     *
     * @return string The default value if blank value is passed
     */
    public function getDefault($type, $variable)
    {

        switch ($type) {
            case 'startDate':
            case 'compareStartDate':
                if ($variable == '')
                    $variable = date('Y-m-d', strtotime('-29 days'));
                break;
            case 'endDate':
            case 'compareEndDate':
                if ($variable == '')
                    $variable = date('Y-m-d');
                break;
            case 'pageName':
                if ($variable == '')
                    $variable = 'category';
                break;
            case 'category':
                if ($variable == '')
                    $variable = '3';
                break;
            case 'device':
            case 'type':
                if ($variable == '')
                    $variable = 'all';
                break;
            case 'pivot':
                if($variable == '')
                    $variable = 'pageview';
                break;
        }

        return $variable;
    }

    /**
     * Change date formats (This method just changes the string representation.)
     *
     * @param string $inputDate   The input date
     * @param string $inputFormat The format in which the date was input
     *
     * @return string A YYYY-MM-DD representation
     */
    public function getFormattedDate($inputDate, $inputFormat = 'MM/DD/YYYY')
    {
        switch ($inputFormat) {
            case 'MM/DD/YYYY':
                $replacementPattern = '$3-$2-$1';
                break;
            case 'DD/MM/YYYY':
                $replacementPattern = '$3-$1-$2';
                break;
            default:
                $replacementPattern = '$3-$2-$1';
                break;

        }

        return preg_replace('/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/', $replacementPattern, $inputDate);
    }

    public function responseTiles($inputRequest, $team = 'global'){
        // Total Responses

        $this->prepareQuery($inputRequest, $team);
        $result = $this->clientCon->search($this->elasticQuery);
        $totalResponses = $result['hits']['total'];
        $saModel = $this->CI->load->model('trackingMIS/samismodel');
        if($inputRequest->isRMC=="yes"){
                
            $universityIdsForSelectedDuration =  $saModel->getRMCUniversityIdsForSelectedDuration((array)$inputRequest);
            
            $universityIdsArray = array_map(function($a){
                return $a['universityId'];
            }, $universityIdsForSelectedDuration);
            if(count($universityIdsArray)){
                $courseIdsForSelectedDuration = $saModel->getRMCCourseIds($universityIdsArray,(array)$inputRequest);    
            }else{
                $courseIdsForSelectedDuration = 0;
            }     
            $universityIdsCount = count($universityIdsForSelectedDuration);   
            $courseIdsCount = count($courseIdsForSelectedDuration);
            //-------------------------------
        }else{

            // Paid Responses
            $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][] = array(
                'term' => array(
                    'responseType' => 'paid'
                )
            );
            $result = $this->clientCon->search($this->elasticQuery);
            $paidResponses = $result['hits']['total'];

            // RMC Response Count
            $this->prepareQuery($inputRequest, $team);
            $this->elasticQuery['body']['query']['bool']['filter']['bool']['must_not'][] = array(
                'term' => array(
                    'RMCResponseType' => '' // we just need the paid and the free RMCs
                )
            );
            $this->elasticQuery['body']['aggs'] = array(
                'rmcResponses' => array(
                    'terms' => array(
                        'field' => 'RMCResponseType'
                    )
                )
            );
            $result = $this->clientCon->search($this->elasticQuery);
            $buckets = $result['aggregations']['rmcResponses']['buckets'];
            $rmcResponsesCount = $buckets[0]['doc_count'] + $buckets[1]['doc_count'];

           // Paid courses count
            $paidCoursesCount = 0;
            if($team == 'abroad' || $team == 'studyabroad') {
                $responseType = ($inputRequest->pivotType != 'all' ? $inputRequest->pivotType : 'paid');
                $paidCoursesCount = $saModel->getCourseIdsForSelectedDuration(GOLD_SL_LISTINGS_BASE_PRODUCT_ID,(array)$inputRequest,$responseType);
                $paidCoursesCount = count($paidCoursesCount);
            } else{
                $listingsModel = $this->CI->load->model('trackingMIS/nationalListings/listings_model');
                $paidCoursesCount = $listingsModel->getPaidCoursesCount($inputRequest, $team);
            }
        }
        // Find all users
        $this->prepareQuery($inputRequest, $team);
        $this->elasticQuery['body']['aggs'] = array(
            'allUsers' => array(
                'cardinality' => array(
                    'field' => 'userId'
                )
            )
        );

        $result = $this->clientCon->search($this->elasticQuery);
        $allUsers = array();
        $allUsers = $result['aggregations']['allUsers']['value'];
        // Unique Users Count
        $uniqueUsersCount = $allUsers;

		/*
	        $team = 'global'; // No need to use team filter

	        $this->elasticQuery = array(
            'index' => MISCommonLib::$RESPONSES_DATA['indexName'],
            'type'  => MISCommonLib::$RESPONSES_DATA['type'],
            'body'  => array(
                'size' => 0
            )
        );

        $this->elasticQuery['body']['aggs'] = array(
            'allUsers' => array(
                'terms' => array(
                    'field' => 'userId',
                    'size'  => 0
                )
            )

        );
        $startDateFilter = array(
            'range' => array(
                'responseDate' => array(
                    'lt' => $inputRequest->startDate. 'T00:00:00'
                ),
            )
        );
        $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][] = $startDateFilter;

        $result = $this->clientCon->search($this->elasticQuery);
        $oldUsers = array();
        foreach($result['aggregations']['allUsers']['buckets'] as $oneOldUser) {
            $oldUsers[] = $oneOldUser['key'];
        }

        if(count($oldUsers)>0){
            $firstTimeUsers = array_diff($allUsers, $oldUsers); // Find which all $allUsers are not in $oldUsers    
        }else{
            $firstTimeUsers = array_unique($allUsers);
        }*/
        
        if($inputRequest->isRMC == "yes"){
            $universityIdsCount = count($universityIdsForSelectedDuration);   
            $courseIdsCount = count($courseIdsForSelectedDuration);
            return array(
                'totalResponseCount' => number_format($totalResponses),
                'respondentRatio' => number_format($totalResponses / $uniqueUsersCount, 2),
                'totalUniversities' => number_format($universityIdsCount),
                'totalCourses' => number_format($courseIdsCount),
                'totalUserCount' => number_format($uniqueUsersCount),
                //'firstTimeUserCount' => number_format(count($firstTimeUsers))
            );
        }else{
            return array(
                'totalResponseCount' => number_format($totalResponses),
                'respondentRatio'    => number_format($totalResponses / $uniqueUsersCount, 2),
                'paidResponseCount'  => number_format($paidResponses),
                'rmcResponseCount'   => number_format($rmcResponsesCount),
                'paidCoursesCount'   => number_format($paidCoursesCount),
                //'firstTimeUserCount' => number_format(count($firstTimeUsers))
            );
        }
    }

    public function responseTrends($inputRequest, $team='global'){

        $this->prepareQuery($inputRequest, $team);

        $this->elasticQuery['body']['aggs'] = array(
            'responseTrends' => array(
                'date_histogram' => array(
                    'field' => 'responseDate',
                    'interval' => MISCommonLib::getView($inputRequest->viewType),
                    "order" => array(
                        "_key" => "desc"
                    )
                )
            )
        );

        $queryResult = $this->clientCon->search($this->elasticQuery);

        $trends = array();

        foreach($queryResult['aggregations']['responseTrends']['buckets'] as $oneResult){

            $oneTrend = new stdClass();
            $oneTrend->Date = $this->extractDate($oneResult['key_as_string']);
            $oneTrend->ScalarValue = $oneResult['doc_count'];

            $trends[] = $oneTrend;

        }
        return $trends;
    }

    public function responseSplit($inputRequest, $team = 'global')
    {
        $this->prepareQuery($inputRequest, $team);
        $getSplitAspect = function($splitAspect){

            switch($splitAspect){
                case 'device':
                    return 'sourceApplication';
                case 'page':
                    return 'pageIdentifier';
                case 'widget':
                    return 'keyName';
                case 'pivotType':
                    return 'responseType';
                case 'session':
                    return 'trafficSource';
                case 'rmcResponseType':
                    return 'RMCResponseType';
                case 'utmCampaign':
                case 'utmMedium':
                case 'utmSource':
                    return $splitAspect;
                default:
                    return 'page';

            }
        };

        $splitAspect = $getSplitAspect($inputRequest->splitAspect);

        if ($inputRequest->splitAspect == 'rmcResponseType') {

            $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][] = array(
                'terms' => array(
                    'RMCResponseType' => array('paid', 'free') // we just need the paid and the free RMCs
                )
            );
        }


        $this->elasticQuery['body']['aggs'] = array(
            $inputRequest->splitAspect => array(
                'terms' => array(
                    'field' => $splitAspect,
                    'size' => 0
                )
            )
        );

        if($inputRequest->sessionTypeSelector != ''){
            $sessionTypeFilter = array(
                'term' => array(
                    'trafficSource' => $inputRequest->sessionTypeSelector
                )
            );
            $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][] = $sessionTypeFilter;
        }

        $responseSplit = $this->clientCon->search($this->elasticQuery);
        $splits = array();

        $totalCount = 0;

        foreach($responseSplit['aggregations'][$inputRequest->splitAspect]['buckets'] as $oneSplit){
            $oneSplitData = new stdClass();
            $oneSplitData->PivotName = $oneSplit['key'];
            $oneSplitData->ScalarValue = $oneSplit['doc_count'];
            $totalCount += $oneSplit['doc_count'];
            $splits[] = $oneSplitData;
        }

        foreach($splits as $index => $oneSplit){
            $splits[$index]->Percentage = number_format($oneSplit->ScalarValue / $totalCount * 100, 2);
        }

        return $splits;
    }

    public function responseTable($inputRequest, $team = 'global'){
        $this->prepareQuery($inputRequest, $team);

    if($inputRequest->isRMC == 'yes' && $inputRequest->pageName != ''){
        $this->elasticQuery['body']['aggs'] = array(
                'keyName' => array(
                    'terms' => array(
                        'field' => 'keyName',
                        'size' => 0,
                        'order' => array(
                            '_count' => 'desc'
                        )
                    ),
                    'aggs'  => array(
                        'Widget' => array(
                            'terms' => array(
                                'field' => 'widget',
                                'size' => 0,
                            ),
                            'aggs'  => array(
                        'sourceApplicationWise' => array(
                            'terms' => array(
                                'field' => 'sourceApplication',
                                'size' => 0,
                            )
                        )
                    )
                        )
                    )
                )
            );
    }else{
        $this->elasticQuery['body']['aggs'] = array(
                'pageWise' => array(
                    'terms' => array(
                        'field' => 'pageIdentifier',
                        'size' => 0,
                        'order' => array(
                            '_count' => 'desc'
                        )
                    ),
                    'aggs'  => array(
                        'sourceApplicationWise' => array(
                            'terms' => array(
                                'field' => 'sourceApplication',
                                'size' => 0,
                            )
                        )
                    )
                )
            );
    }
        

        $tableData = $this->clientCon->search($this->elasticQuery);
        $gridData = array();
        $totalData = 0;

        if($inputRequest->isRMC == 'yes' && $inputRequest->pageName != ''){
            foreach($tableData['aggregations']['keyName']['buckets'] as $oneRowInResult){
                $widgetWiseData = $oneRowInResult['Widget']['buckets'];
                foreach($widgetWiseData as $oneWidgetData){
                    $widgetWiseData = $oneWidgetData['sourceApplicationWise']['buckets'];
                    foreach($widgetWiseData as $oneDeviceData){
                        $oneRowForTable = new stdClass();
                        $oneRowForTable->ResponseCount = $oneDeviceData['doc_count'];
                        $oneRowForTable->KeyName = $oneRowInResult['key'];
                        $oneRowForTable->WidgetName = $oneWidgetData['key'];
                        $oneRowForTable->DeviceName = $oneDeviceData['key'];
                        $totalData += $oneDeviceData['doc_count'];
                        $gridData[]  = $oneRowForTable;
                }
            }
            }
            arsort($gridData);
            $sortedGridData = array();

            foreach($gridData as $key => $oneRowData){
                $oneSortedData = new stdClass();
                $oneSortedData->KeyName = $oneRowData->KeyName;
                $oneSortedData->WidgetName = $oneRowData->WidgetName;
                $oneSortedData->DeviceName = $oneRowData->DeviceName;
                $oneSortedData->ResponseCount = $oneRowData->ResponseCount;
                $oneSortedData->Percentage = number_format($oneRowData->ResponseCount/$totalData * 100, 2);
                $sortedGridData[] = $oneSortedData;
                unset($gridData[$key]);
            }
        }else{
            foreach($tableData['aggregations']['pageWise']['buckets'] as $oneRowInResult){
                $sourceApplicationWiseData = $oneRowInResult['sourceApplicationWise']['buckets'];
                foreach($sourceApplicationWiseData as $oneDeviceData){
                    $oneRowForTable = new stdClass();
                    $oneRowForTable->ResponseCount = $oneDeviceData['doc_count'];
                    $oneRowForTable->PageName = $oneRowInResult['key'];
                    $oneRowForTable->DeviceName = $oneDeviceData['key'];
                    $totalData += $oneDeviceData['doc_count'];
                    $gridData[]  = $oneRowForTable;
                }
            }
            arsort($gridData);
            $sortedGridData = array();

            foreach($gridData as $key => $oneRowData){
                $oneSortedData = new stdClass();
                $oneSortedData->PageName = $oneRowData->PageName;
                $oneSortedData->DeviceName = $oneRowData->DeviceName;
                $oneSortedData->ResponseCount = $oneRowData->ResponseCount;
                $oneSortedData->Percentage = number_format($oneRowData->ResponseCount/$totalData * 100, 2);
                $sortedGridData[] = $oneSortedData;
                unset($gridData[$key]);
            }
        }
            
        return $sortedGridData;
    }
    //registrationTiles

    public function MISTopTiles($inputRequest){
        switch ($inputRequest['metric']) {
            case 'traffic':
                return $this->_trafficTiles($inputRequest);
                break;

            case 'engagement':
                return $this->_engagementTiles($inputRequest);
                break;

            case 'registration':
                return $this->_registrationTiles($inputRequest);
                break;

            case 'response':
            case 'RMC':
                return $this->_responseTiles($inputRequest);
                break;

            case 'home':
                if($inputRequest['pageName'] == 'searchPage' && $inputRequest['misSource'] == 'STUDY ABROAD'){
                    return $this->_studyAbroadSearchTiles($inputRequest);    
                }
                break;
            case 'exam_upload' :
                if($inputRequest['misSource'] == 'STUDY ABROAD'){
                    return $this->_studyAbroadExamUploadTiles($inputRequest);
                }
                break;

            case 'sassistant':
                return $this->_sassistantTiles($inputRequest);
                break;
            
            default:
                # code...
                break;
        }
    }

    private function _studyAbroadSearchTiles($inputRequest)
    {
        $topTiles = array(
            'totalSearch' => 0,
            'searchPerRespondent' => 0,
            'totalFilterApplied' => 0,
            'totalSortingApplied' => 0,
            'totalInteraction' => 0,
            'historySearchPercentage' => 0
            );
        $this->overviewModel = $this->CI->load->model('overview_model');
        $result = $this->overviewModel->getTrackingIdsForAbroadSearch($inputRequest);
        if($result){
            foreach ($result as $key => $value) {
                $trackingIds[] = $value['id'];
            }
            $trackingIds = array_unique($trackingIds);
            $result = $this->overviewModel->getSearchCountForSearch($inputRequest,$trackingIds);
            if($result){
                // historyTracking :: '0 : Recent Search Not shown, 1 : Recent Search Shown, 2 : Searched from history'
                $historyWidgetShownCount = 0;
                $searchFromHistoryCount = 0;
                foreach ($result as $key => $value) {
                    $topTiles['totalSearch'] += $value['count'];
                    if (($value['historyTracking'] == 1) || ($value['historyTracking'] == 2)) {
                        $historyWidgetShownCount += $value['count'];
                        if($value['historyTracking'] == 2)
                        {
                            $searchFromHistoryCount += $value['count'];
                        }
                    }
                }
                $topTiles['historySearchPercentage'] = ($historyWidgetShownCount != 0)?number_format((($searchFromHistoryCount*100)/$historyWidgetShownCount), 2, '.', ''):0.00;
                $sessionCount = $this->overviewModel->getDistinctSessionCountForSearch($inputRequest,$trackingIds);
                $topTiles['searchPerRespondent'] = number_format((($topTiles['totalSearch'])/$sessionCount), 2, '.', '');
                $topTiles['totalFilterApplied'] = $this->overviewModel->getAppliedFilterForSearch($inputRequest,$trackingIds);
                $topTiles['totalSortingApplied'] = $this->overviewModel->getSortingAppliedForSearch($inputRequest,$trackingIds);
                $topTiles['totalInteraction'] = $this->overviewModel->getPageInteractionForSearch($inputRequest,$trackingIds);
            }
        }
        return $topTiles;
    }



    public function MISTrands($inputRequest){
        switch ($inputRequest['metric']) {
            case 'traffic':
                return $this->_trafficTrands($inputRequest);
                break;

            case 'engagement':
                return $this->_engagementTrands($inputRequest);
                break;

            case 'registration':
                return $this->_registrationTrands($inputRequest);
                break;

            case 'response':
            case 'RMC':
                return $this->_responseTrands($inputRequest);
                break;

            case 'home':
                if($inputRequest['pageName'] == 'searchPage' && $inputRequest['misSource'] == 'STUDY ABROAD'){
                    return $this->_studyAbroadSearchTrands($inputRequest);
                }
                break;

            case 'exam_upload' :
                if($inputRequest['misSource'] == 'STUDY ABROAD'){
                    return $this->_studyAbroadExamUploadTrands($inputRequest);
                }
                break;

            case 'sassistant':
                return $this->_sassistantTrends($inputRequest);
                break;
            
            default:
                # code...
                break;
        }
    }

    private function _studyAbroadSearchTrands($inputRequest){
        //_p($inputRequest);die;
        $resultset = array();
        $this->overviewModel = $this->CI->load->model('overview_model');
        $result = $this->overviewModel->getTrackingIdsForAbroadSearch($inputRequest);
        if($result){
            foreach ($result as $key => $value) {
                $trackingIds[] = $value['id'];
            }
            $trackingIds = array_unique($trackingIds);

            $fieldName = $inputRequest['lineChart'];
            $result = $this->overviewModel->getStudyAbroadSearchTrands($inputRequest,$trackingIds,$fieldName);
            //_p($result);die;
            return $result;
        }
        return $resultset;
    }



    public function prepareDataForLineChartForDBData($responsesData,$filterArray){
        $startYear = date('Y', strtotime($filterArray['dateRange']['startDate']));
        $endYear = date('Y', strtotime($filterArray['dateRange']['endDate']));
        $gendate = new DateTime();

        if($filterArray['view'] == 1){
            $sDate=date_create($filterArray['dateRange']['startDate']);
            $eDate=date_create($filterArray['dateRange']['endDate']);
            $diff = date_diff($sDate,$eDate);
            $dateDiff = $diff->format("%a");
            $lineArray=array();
            $tempDate =$filterArray['dateRange']['startDate'];
            for($i=0;$i<=$dateDiff;$i++){
                $lineArray[$tempDate] =0;
                $tempDate = date('Y-m-d', strtotime($tempDate . ' +1 day'));
            }                
            foreach ($responsesData as  $value) {
                    $lineArray[$value['date']] += $value['count'];
            }
        }
        else if($filterArray['view'] == 2){
            if($startYear == $endYear){
                // creating week array
                $startWeekNo = intval(date('W', strtotime($filterArray['dateRange']['startDate'])));
                $endWeekNo = intval(date('W', strtotime($filterArray['dateRange']['endDate'])));
                $lineArray = array();
                if($startWeekNo > $endWeekNo){
                    $startWeekNo = 0;
                }

                foreach ($responsesData as  $value) {
                    $lineChartData[$value['week']] += $value['count'];
                }
                //_p($lineChartData);
                $lineArray[$filterArray['dateRange']['startDate']] = $lineChartData[$startWeekNo]?$lineChartData[$startWeekNo]:0;
                for ($i=$startWeekNo+1; $i <= $endWeekNo ; $i++) { 
                    $gendate->setISODate($startYear,$i,1); //year , week num , day
                    $df = $gendate->format('Y-m-d');
                    $lineArray[$df] = 0;   
                }
                
                foreach ($lineChartData as $key => $value) {
                    if($key == $startWeekNo ){
                        continue;
                    }         
                    $gendate->setISODate($startYear,$key,1); //year , week num , day
                    $lineArray[$gendate->format('Y-m-d')] = $value;   
                }
                //_p($lineArray);die;
            }else{
                $startWeekNo = date('W', strtotime($filterArray['dateRange']['startDate']));
                $endWeekNo =date('W', strtotime($startYear."-12-31"));
                if($endWeekNo == 1){
                    $endWeekNo = date('W', strtotime($startYear."-12-24"));
                }
                $startWeekNo1 = 1;
                $endWeekNo1 =date('W', strtotime($filterArray['dateRange']['endDate']));
                $gendate->setISODate($startYear,$endWeekNo,7); //year , week num , day
                $tempDate = $gendate->format('Y-m-d');
                if($tempDate >= $filterArray['dateRange']['endDate'])
                {
                    $startWeekNo1 =0;
                    $endWeekNo1 =-1;
                }
               $lineArray = array();
               foreach ($responsesData as  $value) {
                    if(($value['week']) > $endWeekNo)
                    {
                        $lineChartData[1] += $value['count'];
                    }else{
                        $lineChartData[($value['week'])] += $value['count'];
                    }
                }
               $lineArray[$filterArray['dateRange']['startDate']] = $lineChartData[$startWeekNo]?$lineChartData[$startWeekNo]:0;
               for ($i=$startWeekNo+1; $i <= $endWeekNo ; $i++) { 
                    $gendate->setISODate($startYear,$i,1); //year , week num , day
                    $df = $gendate->format('Y-m-d');
                    $lineArray[$df] = $lineChartData[$i]?$lineChartData[$i]:0;
                }
                for ($i=$startWeekNo1; $i <= $endWeekNo1 ; $i++) { 
                    $gendate->setISODate($endYear,$i,1); //year , week num , day
                    $df = $gendate->format('Y-m-d');
                    $lineArray[$df] = $lineChartData[$i]?$lineChartData[$i]:0;   
                }
            }    
        }
        else if($filterArray['view'] == 3){
            if($startYear == $endYear){
                $startMonthNo = date('m', strtotime($filterArray['dateRange']['startDate']));
                $endMonthNo = date('m', strtotime($filterArray['dateRange']['endDate']));
                $lineArray = array();
                foreach ($responsesData as  $value) {
                    if($value['month'] <=9)
                    {
                        $lineChartData['0'.$value["month"]] += $value['count'];
                    }else{
                        $lineChartData[$value['month']] += $value['count'];    
                    }
                }
                if($lineChartData[$startMonthNo])
                {
                    $lineArray[$filterArray['dateRange']['startDate']] = $lineChartData[$startMonthNo];    
                }else{
                    $lineArray[$filterArray['dateRange']['startDate']] = 0;    
                }
                
                for ($i=$startMonthNo+1; $i <= $endMonthNo ; $i++) {
                    if($i <= 9){
                        $i =intval($i);
                        $i = '0'.$i;
                        $df = $startYear.'-'.$i.'-01';
                    }else{
                        $df = $startYear.'-'.$i.'-01';    
                    }
                    if($lineChartData[$i]){
                        $lineArray[$df] = $lineChartData[$i];    
                    }else{
                        $lineArray[$df] = 0;   
                    }    
                }
            }else{
                $startMonthNo = intval(date('m', strtotime($filterArray['dateRange']['startDate'])));
                $endMonthNo1 =intval(date('m', strtotime($filterArray['dateRange']['endDate'])));
                $lineArray = array();
                $lineArray[$filterArray['dateRange']['startDate']] = 0;
                $startFlag = false;
                $endFlag = false;
                for($i=$startYear; $i<=$endYear;$i++){
                    if($i == $startYear){
                        $startFlag = true;
                        $sm =$startMonthNo;    
                    }else{
                        $sm =1;
                    }

                    if($i == $endYear){
                        $endFlag = true;
                        $em = $endMonthNo1;
                    }else{
                        $em =12;
                    }
                    
                    for($j=$sm;$j<=$em;$j++)
                    {
                        if($startFlag){
                            $startFlag =false;
                            continue;
                        }
//                        if($endFlag && $j == $em)
//                        {
//                            break;
//                        }
                        if($j <= 9)
                        {
                            $j = intval($j);
                            $daten = $i.'-0'.$j.'-01';

                        }else{
                            $daten = $i.'-'.$j.'-01';
                        }
                        $lineArray[$daten] = 0;
                    }
                }
//                $lineArray[$filterArray['dateRange']['endDate']] = 0;
                $countYearMonthWise = array();
                foreach ($responsesData as  $value) {
                    $yearMonth = date('Ym',strtotime($value['date']));
                    if(!isset($countYearMonthWise[$yearMonth]))
                    {
                        $countYearMonthWise[$yearMonth] = $value['count'];
                    }
                    else
                    {
                        $countYearMonthWise[$yearMonth] += $value['count'];
                    }
                }
            }
        }

        foreach ($lineArray as $key => $value) {
            $yearMonth = date('Ym',strtotime($key));
            $value = isset($countYearMonthWise[$yearMonth])?$countYearMonthWise[$yearMonth]:$value;
            $finalLineArray[] = array($key,$value);
        }
        //_p($finalLineArray);
        return $finalLineArray;
    }

    public function MISSplits($inputRequest){
        switch ($inputRequest['metric']) {
            case 'traffic':
                return $this->_trafficSplits($inputRequest);
                break;

            case 'engagement':
                return $this->_engagementSplits($inputRequest);
                break;

            case 'registration':
                return $this->_registrationSplits($inputRequest);
                break;

            case 'response':
            case 'RMC':
                return $this->_responseSplits($inputRequest);
                break;

            case 'home':
                if($inputRequest['pageName'] == 'searchPage' && $inputRequest['misSource'] == 'STUDY ABROAD'){
                    return $this->_studyAbroadSearchHomeSplit($inputRequest);
                }
                break;

            case 'exam_upload' :
                if($inputRequest['misSource'] == 'STUDY ABROAD'){
                    return $this->_studyAbroadExamUploadSplit($inputRequest);
                }
                break;

            case 'sassistant':
                return $this->_sassistantSplit($inputRequest);
                break;


            default:
                # code...
                break;
        }
    }

    private function _studyAbroadSearchHomeSplit($inputRequest){
        //_p($inputRequest);die;
        $this->overviewModel = $this->CI->load->model('overview_model');
        $result = $this->overviewModel->getTrackingIdsForAbroadSearch($inputRequest);
        if($result){
            foreach ($result as $key => $value) {
                $trackingIds[] = $value['id'];
            }
            $trackingIds = array_unique($trackingIds);

            $fieldName = $inputRequest['donutChart']['fieldName'];
            if($fieldName == 'clickSource'){
                $result = $this->overviewModel->getPageInterectionWiseCount($inputRequest,$trackingIds);
                //_p($result);die;
                if($result){                    
                    foreach ($result as $key => $value) {
                        $resultset[$value['clickSource']] = $value['count'];
                    }
                    unset($result);
                }

            }else{
                $result = $this->overviewModel->getSearchSplitwiseCount($inputRequest,$trackingIds,$fieldName);
                if($result){
                    //_p($result);die;
                    switch ($fieldName) {
                        case 'sourceApplication':
                        case 'searchType':
                        case 'searchResultType':
                        case 'searchEntity':
                            foreach ($result as $key => $value) {
                                $resultset[$value['field']] = $value['count'];
                            }
                            break;

                        case 'trackingKeyId':
                            $trackingIds = array();
                            foreach ($result as $key => $value) {
                                $trackingIds[] = $value['field'];
                            }

                            $trackingIds = array_unique($trackingIds);
                            $trackingDrtails = $this->overviewModel->getPageName($trackingIds);
                            foreach ($trackingDrtails as $key => $value) {
                                $trackingIdToPageName[$value['id']] = $value['page'];
                            }
                            $actualCount = 0;
                            foreach ($result as $key => $value) {
                                $pageName = $this->getPageName($trackingIdToPageName[$value['field']]);
                                $resultset[$pageName] += $value['count'];
                            }
                            break;

                        case 'visitorSessionId':
                            $sessionIds = array();
                            foreach ($result as $key => $value) {
                                $sessionIds[] = $value['field'];
                            }

                            if($sessionIds && count($sessionIds) > 0){
                                $sessionIdsCount = count($sessionIds);
                            }
                            
                            $params = array();
                            $params['index'] = SESSION_INDEX_NAME;
                            $params['type'] = 'session';
                            $params['body']['size'] = $sessionIdsCount;
                            $params['body']['query']['bool']['filter']['bool']['must'][]['terms']['sessionId'] = $sessionIds;
                            //_p(json_encode($params));die;
                            $search = $this->clientCon->search($params);
                            
                            break;                        
                        
                        default:
                            # code...
                            break;
                    }
                }
            }
                
            if($resultset && count($resultset) > 0){
                arsort($resultset);
                $result = array();
                foreach ($resultset as $key => $value) {
                    $result[] = array(
                        'name' => $key,
                        'value' => $value
                        );
                }
            }
        }
        return $result;
    }

    private function _studyAbroadExamUploadTiles($inputRequest)
    {
        $topTiles = array(
            'totalUpload' => 0,
            'totalUser' => 0,
            'firstTimeUser' => 0
        );
        $this->samismodel = $this->CI->load->model('samismodel');
        $result = $this->samismodel->getTrackingIdsForAbroadExamUpload($inputRequest);
        if($result){
            foreach ($result as $key => $value) {
                $trackingIds[] = $value['id'];
            }
            $trackingIds = array_unique($trackingIds);
            $result = $this->samismodel->getCountForExamUpload($inputRequest,$trackingIds);
            if($result){
                $topTiles['totalUpload'] = $result[0]['count'];
                $distinctUserArray = $this->samismodel->getDistinctUserCountForSelectedDuration($trackingIds,$inputRequest,array(),array(),'exam_upload');
                $distinctUserArray = array_map(function($a){
                    return $a['userId'];
                }, $distinctUserArray);
                $topTiles['totalUser'] = count($distinctUserArray);
                $repeatUserArray = $this->samismodel->getResponsesByFirstTimeUser($distinctUserArray,$inputRequest,'exam_upload');
                $repeatUserArray = array_map(function($a){
                    return $a['userId'];
                }, $repeatUserArray);
                $repeatUserCount = count($repeatUserArray);
                $topTiles['firstTimeUser'] = $topTiles['totalUser']-$repeatUserCount;
            }
        }
        return $topTiles;
    }

    private function _studyAbroadExamUploadTrands($inputRequest){
        //_p($inputRequest);die;
        $resultset = array();
        $this->samismodel = $this->CI->load->model('samismodel');
        $result = $this->samismodel->getTrackingIdsForAbroadExamUpload($inputRequest);
        if($result){
            foreach ($result as $key => $value) {
                $trackingIds[] = $value['id'];
            }
            $trackingIds = array_unique($trackingIds);

            $fieldName = $inputRequest['lineChart'];
            $result = $this->samismodel->getStudyAbroadExamUploadTrands($inputRequest,$trackingIds,$fieldName);
            //_p($result);die;
            return $result;
        }
        return $resultset;
    }

    private function _studyAbroadExamUploadSplit($inputRequest){
        $resultset = array();
        $fieldName = $inputRequest['donutChart']['fieldName'];
        $this->samismodel = $this->CI->load->model('samismodel');
        $resultset = $this->samismodel->getCountForAbroadExamUploadFieldWise($inputRequest,$fieldName);
        $result = array();
        if($resultset && count($resultset) > 0){
            arsort($resultset);
            foreach ($resultset as $key => $value) {
                $result[] = array(
                    'name' => $value['fieldName'],
                    'value' => intval($value['count'])
                );
            }
        }
        return $result;
    }

    private function _engagementTiles($inputRequest){
        $this->elasticQuery = array();
        $elasticQueryLib = $this->CI->load->library("trackingMIS/elasticQueryLib");
        $ESQuery = $elasticQueryLib->prepareElasticSearchQuery($inputRequest,'session');
        $this->elasticQuery = $ESQuery['elasticQuery'];
        unset($ESQuery);
        $aggregation = array(
            'totalDuration' => array('sum' => array('field' => 'duration')),
            'pageviews' => array('sum' => array('field' => 'pageviews')),
            'bounce' => array('sum' => array('field' => 'bounce')),
        );
        
        $this->elasticQuery['body']['aggs'] = $aggregation;
        //error_log("ELASTICSEARCHQUERY : ENGAGEMENT Tiles 1:  ".json_encode($this->elasticQuery)."          ");
        //_p(json_encode($this->elasticQuery));die;
        $result = $this->clientCon->search($this->elasticQuery);
        //_p($result);die;

        $totalSession = $result['hits']['total'];
        $totalDuration = $result['aggregations']['totalDuration']['value'];
        $totalBounceSession = $result['aggregations']['bounce']['value'];
        $bounceSessions = number_format((($totalBounceSession*100)/$totalSession), 2, '.', '');
        $totalPageviews = $result['aggregations']['pageviews']['value'];
        $pagesPerSessions = number_format((($totalPageviews)/$totalSession), 2, '.', '');
        $avgSessionDuration = number_format((($totalDuration)/(60*$totalSession)), 2, '.', '');
        $hourFormat = explode('.',number_format((($totalDuration)/($totalSession)), 2, '.', ''));
        $avgSessionDuration =date('i:s', mktime(0, 0, $hourFormat[0]));

        // For pageviews count
        $this->elasticQuery = array();
        $ESQuery = $elasticQueryLib->prepareElasticSearchQuery($inputRequest,'pageview');
        $this->elasticQuery = $ESQuery['elasticQuery'];
        unset($ESQuery);
        //error_log("ELASTICSEARCHQUERY : ENGAGEMENT Tiles 2 :   ".json_encode($this->elasticQuery)."          ");
        $result = $this->clientCon->search($this->elasticQuery);
        $totalPageviews = $result['hits']['total'];
        if($inputRequest['pageName']){
            $this->_prepareQueryForExitPage($inputRequest);
            $ESQuery = $elasticQueryLib->prepareElasticSearchQuery($inputRequest,'session','exitPage');
            $this->elasticQuery = $ESQuery['elasticQuery'];
            unset($ESQuery);

            //_p(json_encode($this->elasticQuery));die;
            //error_log("ELASTICSEARCHQUERY : ENGAGEMENT Tiles 3 :   ".json_encode($this->elasticQuery)."          ");
            $result = $this->clientCon->search($this->elasticQuery);
            $exitPageCount = $result['hits']['total'];

            $this->elasticQuery = array();
            $ESQuery = $elasticQueryLib->prepareElasticSearchQuery($inputRequest,'pageview');
            $this->elasticQuery = $ESQuery['elasticQuery'];
            unset($ESQuery);
            //error_log("ELASTICSEARCHQUERY : ENGAGEMENT Tiles 4 :   ".json_encode($this->elasticQuery)."          ");
            $result = $this->clientCon->search($this->elasticQuery);
            $pageCount = $result['hits']['total'];
            $exitRate = number_format((($exitPageCount*100)/($pageCount)), 2, '.', '');
            $result = array(
                'pageViews' => $totalPageviews,
                'pgPerSess' => $pagesPerSessions,
                'avgSessDuration' => $avgSessionDuration,
                'bounceRate' => $bounceSessions,
                'exitRate' => $exitRate,
                'totalSessions' => $totalSession
            );
        }else{
            $result = array(
                'pageViews' => $totalPageviews,
                'pgPerSess' => $pagesPerSessions,
                'avgSessDuration' => $avgSessionDuration,
                'bounceRate' => $bounceSessions,
                'totalSessions' => $totalSession
            );
        }

            
        return $result;
    }

    private function _prepareQueryForExitPage($inputRequest){

        $this->elasticQuery = array(
            'index' => SESSION_INDEX_NAME,
            'type'  => 'session',
            'body'  => array(
                'size' => 0
            )
        );

        //Date Range Filter
        if($inputRequest['dateRange']['startDate'] != ''){
            $startDateFilter = array(
                'range' => array(
                    'exitPage.visitTime' => array(
                        'gte' => $inputRequest['dateRange']['startDate'] . 'T00:00:00',
                        'lte' => $inputRequest['dateRange']['endDate'] . 'T23:59:59'
                    ),
                )
            );
            $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][] = $startDateFilter;
        }

        // team filter
        if ($inputRequest['misSource'] != 'SHIKSHA') {
            if ($inputRequest['misSource'] == 'STUDY ABROAD') {
                $teamFilter = 'yes';
            } else if ($inputRequest['misSource'] == 'DOMESTIC') {
                $teamFilter = 'no';
            }
            $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['term']['exitPage.isStudyAbroad'] = $teamFilter;
        }
        // Page Filter
        $pivot = 'exitPage.';
        if (strlen($inputRequest['pageName']) > 0 && strcasecmp($inputRequest['pageName'], 'all') != 0) {
            if ($inputRequest['misSource'] == 'STUDY ABROAD') {
                if ($inputRequest['pageName'] == 'rankingPage') {
                        if ($inputRequest['pageType'] == 1) {
                            $pageNameFilter = array(
                                'term' => array(
                                    $pivot.'pageIdentifier' => 'universityRankingPage'
                                )
                            );
                        } else if ($inputRequest['pageType'] == 2) {
                            $pageNameFilter = array(
                                'term' => array(
                                    $pivot.'pageIdentifier' => 'courseRankingPage'
                                )
                            );
                        } else {
                            $pageNameFilter = array(
                                'terms' => array(
                                    $pivot.'pageIdentifier' => array(
                                        'courseRankingPage',
                                        'universityRankingPage'
                                    )
                                )
                            );
                        }                
                    }else{
                        $pageNameFilter = array(
                            'term' => array(
                                $pivot.'pageIdentifier' => $inputRequest['pageName']
                            )
                        );
                    }
            }else{
                $pageNameFilter = array(
                            'term' => array(
                                $pivot.'pageIdentifier' => $inputRequest['pageName']
                            )
                        );
            }
            $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][] = $pageNameFilter;
        }

        if($inputRequest['misSource'] == 'STUDY ABROAD') {
            if($inputRequest['categoryId'] >0){
                $this->abroadCommonLib = $this->CI->load->library('listingPosting/AbroadCommonLib');
                $ldbCourseIdsArray = $this->abroadCommonLib->getAbroadMainLDBCourses();
                foreach ($ldbCourseIdsArray as $key => $value) {
                    $ldbCourseIds[]= $value['SpecializationId'];
                }
                if(in_array($inputRequest['categoryId'],$ldbCourseIds)){
                    $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['term'][$pivot.'LDBCourseId'] = $inputRequest['categoryId'];
                }else{
                    $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['term'][$pivot.'categoryId'] = $inputRequest['categoryId'];
                    if(!empty($inputRequest['courseLevel']) && $inputRequest['courseLevel'] != '0'){
                        $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['term'][$pivot.'courseLevel'] = $inputRequest['courseLevel'];
                    }
                }
            }else if(!empty($inputRequest['courseLevel']) && $inputRequest['courseLevel'] != '0'){
                $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['term'][$pivot.'courseLevel'] = $inputRequest['courseLevel'];
            }
            if(!empty($inputRequest['country']) && $inputRequest['country'] != '0'){
                $this->elasticQuery['body']['query']['bool']['filter']['bool']['should'][]['term'][$pivot.'countryId'] = $inputRequest['country'];
            }
        }else if ($inputRequest['misSource'] == 'DOMESTIC'){
            if ($inputRequest['pageName'] != 'eventCalendar' && $inputRequest['pageName'] != 'articlePage' ) {
                if($inputRequest['categoryId'] >0){
                    $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['term'][$pivot.'categoryId'] = $inputRequest['categoryId'];
                }
            }
                
            if (intval($inputRequest['subCategory']) > 0){
                $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['term'][$pivot.'subCategoryId'] = $inputRequest['subCategory'];
            }
        }

        $this->_prepareCommonFilterForElasticQuery($inputRequest,'exitPage');
    }

    private function _trafficTiles($inputRequest){
        //traffic query
        $elasticQueryLib = $this->CI->load->library("trackingMIS/elasticQueryLib");
        $result = $elasticQueryLib->prepareElasticSearchQuery($inputRequest,'session');
        $this->elasticQuery = $result['elasticQuery'];

        $this->elasticQuery['body']['aggs'] = array(
            'userCount' => array(
                'cardinality' => array(
                    'field' => 'visitorId'
                )
            ),
        );
        //error_log("ELASTICSEARCHQUERY : TRAFFIC Top Tiles :   ".json_encode($this->elasticQuery)."                     ");
        //_p(json_encode($this->elasticQuery));die;
        $searchResult = $this->clientCon->search($this->elasticQuery); // Users and sessions count
        //var_dump($result['hasNestedQuery']);die;
        //_p($this->elasticQuery);die;
        //_p($this->elasticQuery['body']['query']['bool']['filter'][0]['bool']['must']);die;
        if($result['hasNestedQuery'] == 1){
            $this->elasticQuery['body']['query']['bool']['filter'][1]['bool']['must'][]['term']['sessionNumber'] = 1;
        }else{
            $this->elasticQuery['body']['query']['bool']['filter'][0]['bool']['must'][]['term']['sessionNumber'] = 1;
        }
        //_p($this->elasticQuery);die;
        
        unset($this->elasticQuery['body']['aggs']);
        //error_log("ELASTICSEARCHQUERY : TRAFFIC Top Tiles  1 :   ".json_encode($this->elasticQuery)."                     ");
        $newSessions = $this->clientCon->search($this->elasticQuery);

        $this->elasticQuery = array();
        //pageview query
        $elasticQueryLib = $this->CI->load->library("trackingMIS/elasticQueryLib");
        $result = $elasticQueryLib->prepareElasticSearchQuery($inputRequest,'pageview');
        $this->elasticQuery = $result['elasticQuery'];        
        //_p(json_encode($this->elasticQuery));die;
        //error_log("ELASTICSEARCHQUERY : TRAFFIC Top Tiles 2 :   ".json_encode($this->elasticQuery)."                     ");
        $pageviews = $this->clientCon->search($this->elasticQuery);

        $result = array(
            'visitorId' =>($searchResult['aggregations']['userCount']['value']),
            'sessions' => ($searchResult['hits']['total']),
            'pageviews' =>($pageviews['hits']['total']),
            'sessionNumber' => number_format($newSessions['hits']['total'] / $searchResult['hits']['total'] * 100, 2, '.', '' )
        );
        return $result;
    }

    private function _engagementSplits($inputRequest){
        switch ($inputRequest['aspect']){
            case 'pageviews':
                if($inputRequest['donutChart']['fieldName'] == 'userId'){
                    $result = $this->_prepareUserWiseChartForEngagement($inputRequest);
                }else{
                    $inputRequest['aspect'] = 'pageViews';
                    $result = $this->_trafficSplits($inputRequest);                    
                }
                return $result;
                
                break;

            case 'pgpersess':
            case 'avgsessdur':
            case 'bounce':
                if($inputRequest['donutChart']['fieldName'] == 'userId'){
                    $result = $this->_prepareUserWiseChartForEngagement($inputRequest);
                }else{
                    $this->elasticQuery = array();
                    $elasticQueryLib = $this->CI->load->library("trackingMIS/elasticQueryLib");
                    $result = $elasticQueryLib->prepareElasticSearchQuery($inputRequest,'session');
                    $this->elasticQuery = $result['elasticQuery'];
                    unset($result);
                    if($inputRequest['aspect'] == 'avgsessdur'){
                        $fieldNameForAggs = 'duration';
                    }else if($inputRequest['aspect'] == 'bounce'){
                        $fieldNameForAggs = 'bounce';
                    }else if($inputRequest['aspect'] == 'pgpersess'){
                        $fieldNameForAggs = 'pageviews';
                    }

                    $this->elasticQuery['body']['aggs']['sumPivot']['sum']['field']=$fieldNameForAggs;
                    //error_log("ELASTICSEARCHQUERY : ENGAGEMENT Split 1:   ".json_encode($this->elasticQuery)."          ");
                    //_p(json_encode($this->elasticQuery));die;
                    $queryResult = $this->clientCon->search($this->elasticQuery);
                    unset($this->elasticQuery['body']['aggs']);
                    $actualSessionCount = $queryResult['hits']['total'];
                    $actualPivotCount = $queryResult['aggregations']['sumPivot']['value'];

                    $pivotWiseFilter = $this->_addAggerateToQuery($inputRequest['donutChart']['fieldName'],$inputRequest['view'],'fieldAgg');            
                    $pivotWiseFilter['aggPivot']['aggs'] = $this->_addAggerateToQuery($fieldNameForAggs,'','sumAgg');

                    $this->elasticQuery['body']['aggs'] = $pivotWiseFilter;
                    //_p(json_encode($this->elasticQuery));die;
                    //error_log("ELASTICSEARCHQUERY : ENGAGEMENT Split 2:   ".json_encode($this->elasticQuery)."          ");
                    $search = $this->clientCon->search($this->elasticQuery);
                    $resultData = $search['aggregations'][ 'aggPivot' ]['buckets'];
                    $sourceApplicationArray = array('yes','no');
                    $flag =0;
                    $totalSessionCount=0;
                    foreach ($resultData as $key => $value) {
                        if(!$value['key']){
                            continue;
                        }
                        $totalSessionCount += $value['doc_count'];
                        $totalPivotCount += $value['countPivot']['value'];

                        if(in_array($value['key'], $sourceApplicationArray)){
                            $temp = ($value['key']=='no')?"Desktop":"Mobile";
                        }else{
                            if($inputRequest['donutChart']['fieldName'] == 'landingPageDoc.pageIdentifier'){
                                $temp = $this->getPageName($value['key']);
                                if(!$temp){
                                    $temp =$value['key'];
                                }
                            }else{
                                $temp =$value['key'];    
                            }    
                        }
                        //_p($value['countPivot']['value']/$value['doc_count']);                        

                        if($value['countPivot']['value']/$value['doc_count'] > 0){
                            $flag =1;
                        }else{
                            continue;
                        }

                        
                        if($inputRequest['aspect'] == 'bounce'){
                            $resultset[$temp] = number_format((($value['countPivot']['value']*100)/$value['doc_count']), 2, '.', '');
                        }else if($inputRequest['aspect'] == 'avgsessdur'){
                            //$resultset[$temp] = number_format((($value['countPivot']['value'])/($value['doc_count']*60)), 2, '.', '');
                            $hourFormat = explode('.',number_format((($value['countPivot']['value'])/($value['doc_count'])), 2, '.', ''));
                            $resultset[$temp] =date('i:s', mktime(0, 0, $hourFormat[0]));
                        }if($inputRequest['aspect'] == 'pgpersess'){
                            $resultset[$temp] = number_format((($value['countPivot']['value'])/($value['doc_count'])), 2, '.', '');
                        }                        
                    }
            
                    if(($actualSessionCount != $totalSessionCount) || ($actualPivotCount != $totalPivotCount)){
                        $diffSessionCount = $actualSessionCount - $totalSessionCount;
                        if($diffSessionCount >0){
                            if(floatval(($actualPivotCount - $totalPivotCount)/$diffSessionCount) > 0){
                                if($inputRequest['aspect'] == 'bounce'){
                                    $resultset['others'] = number_format((($actualPivotCount - $totalPivotCount)*100)/$diffSessionCount, 2, '.', '');
                                }else if($inputRequest['aspect'] == 'avgsessdur'){
                                    //$resultset['others'] = number_format((($actualPivotCount - $totalPivotCount))/($diffSessionCount*60), 2, '.', '');
                                    $hourFormat = explode('.',number_format((($actualPivotCount - $totalPivotCount)/($diffSessionCount)), 2, '.', ''));
                                    $resultset['others'] =date('i:s', mktime(0, 0, $hourFormat[0]));
                                }if($inputRequest['aspect'] == 'pgpersess'){
                                    $resultset['others'] = number_format((($actualPivotCount - $totalPivotCount))/$diffSessionCount, 2, '.', '');
                                }
                            }
                                                              
                        }  
                    }
                    //_p($resultset);die;
                    arsort($resultset);
                    foreach ($resultset as $key => $value) {
                        $result[] = array(
                                'name' => ucfirst($key),
                                'value' => $value
                            );
                    } 
                    if($flag ==0){
                        $result ='';
                    }                   
                }
                //_p($result);die;                       
                break;

            case 'exit':
                if($inputRequest['donutChart']['fieldName'] == 'userId'){
                    $result = $this->_prepareUserWiseChartForEngagement($inputRequest);
                }else{
                    //pageviews query
                    $params = array();
                    $this->elasticQuery = array();
                    $elasticQueryLib = $this->CI->load->library("trackingMIS/elasticQueryLib");
                    $result = $elasticQueryLib->prepareElasticSearchQuery($inputRequest,'pageview');
                    $this->elasticQuery = $result['elasticQuery'];
                    unset($result);
                    $params = $this->elasticQuery;
                    $pivotWiseFilter = $this->_addAggerateToQuery($inputRequest['donutChart']['fieldName'],'','fieldAgg');
                    $params['body']['aggs'] = $pivotWiseFilter;
                    //_p(json_encode($params));die;
                    //error_log("ELASTICSEARCHQUERY : ENGAGEMENT SPLIT :   ".$inputRequest['donutChart']['fieldName']."  ".json_encode($params)."          ");
                    $search = $this->clientCon->search($params);
                    $actualCountPageviews = $search['hits']['total'];
                    $devicewiseData = $search['aggregations']['aggPivot']['buckets'];
                    $sourceApplicationForPageViews = array();
                    $totalPageviews = 0;
                    foreach ($devicewiseData as $key => $value) {
                        $sourceApplicationForPageViews[$value['key']] = $value['doc_count'];
                        $totalPageviews++;
                    }
                    if($inputRequest['donutChart']['fieldName'] != 'isMobile'){
                        if($actualCountPageviews > $totalPageviews){
                            $sourceApplicationForPageViews['Other'] = $actualCountPageviews - $totalPageviews;
                        }
                    }                        

                    //exit session query
                    $this->elasticQuery = array();
                    //$this->_prepareQueryForExitPage($inputRequest);
                    $result = $elasticQueryLib->prepareElasticSearchQuery($inputRequest,'session','exitPage');
                    $this->elasticQuery = $result['elasticQuery'];
                    unset($result);
                    $pivotWiseFilter = $this->_addAggerateToQuery('exitPage.'.$inputRequest['donutChart']['fieldName'],'','fieldAgg');
                    $this->elasticQuery['body']['aggs'] = $pivotWiseFilter;
                    //error_log("ELASTICSEARCHQUERY : ENGAGEMENT SPLIT :   ".$inputRequest['donutChart']['fieldName']."  ".json_encode($this->elasticQuery)."          ");
                    $search = $this->clientCon->search($this->elasticQuery);
                    $actualCountExit = $search['hits']['total'];
                    $devicewiseData = $search['aggregations']['aggPivot']['buckets'];
                    $sourceApplicationForExit = array();
                    $totalExitCount = 0;
                    foreach ($devicewiseData as $key => $value) {
                        $sourceApplicationForExit[$value['key']] = $value['doc_count'];
                        $totalExitCount++;
                    }
                    if($inputRequest['donutChart']['fieldName'] != 'isMobile'){
                        if($actualCountExit > $totalExitCount){
                            $sourceApplicationForExit['Other'] = $actualCountExit - $totalExitCount;
                        }
                    }
                    //_p($sourceApplicationForExit);die;
                    $sourceApplication = array();
                    $sourceApplicationArray = array('yes','no');
                    $flag =0;
                    $resultset =array();
                    foreach ($sourceApplicationForExit as $key => $value) {
                        if(!$key){
                            continue;
                        }
                        if(in_array($key, $sourceApplicationArray)){
                            $temp = ($key=='no')?"Desktop":"Mobile";
                        }else{
                            if($inputRequest['donutChart']['fieldName'] == 'landingPageDoc.pageIdentifier'){
                                $temp = $this->getPageName($key);
                                if(!$temp){
                                    $temp =$key;
                                }
                            }else{
                                $temp =$key;
                            }    
                        }
                        $resultset[$temp] = number_format((($value*100)/$sourceApplicationForPageViews[$key]), 2, '.', '');
                        if(intval($resultset[$temp]) > 0){
                            $flag =1;
                        }
                    }
                    //_p($sourceApplication);die;
                    //_p($resultset);die;
                    arsort($resultset);
                    foreach ($resultset as $key => $value) {
                        $result[] = array(
                                'name' => ucfirst($key),
                                'value' => $value
                            );
                    } 

                    if($flag ==0){
                        $result ='';
                    }                    
                }
                break;
            
            default:
                # code...
                break;
        }
        return $result;
    }

    private function _engagementTrands($inputRequest){
        //_p($inputRequest['aspect']);die;
        switch ($inputRequest['aspect']){

            case 'pageviews':
                $inputRequest['aspect'] = 'pageViews';
                $result = $this->_trafficTrands($inputRequest);
                break;

            case 'pgpersess':
            case 'avgsessdur':
            case 'bounce':
                $this->elasticQuery = array();
                $elasticQueryLib = $this->CI->load->library("trackingMIS/elasticQueryLib");
                $result = $elasticQueryLib->prepareElasticSearchQuery($inputRequest,'session');
                $this->elasticQuery = $result['elasticQuery'];
                unset($result);
                
                $dateWiseFilter = $this->_addAggerateToQuery('startTime',$inputRequest['view'],'time');
                if($inputRequest['aspect'] == 'avgsessdur'){
                    $fieldNameForAggs = 'duration';
                }else if($inputRequest['aspect'] == 'bounce'){
                    $fieldNameForAggs = 'bounce';
                }else if($inputRequest['aspect'] == 'pgpersess'){
                    $fieldNameForAggs = 'pageviews';
                }
                //$fieldNameForAggs = ($inputRequest['aspect'] == 'avgsessdur')?'duration':'bounce';
                $dateWiseFilter['dateWise']['aggs'] = $this->_addAggerateToQuery($fieldNameForAggs,'','sumAgg');
                $this->elasticQuery['body']['aggs'] = $dateWiseFilter;
                //error_log("ELASTICSEARCHQUERY : ENGAGEMENT TRANDS :   ".json_encode($this->elasticQuery)."          ");
                $search = $this->clientCon->search($this->elasticQuery);
                $resultData = $search['aggregations'][ 'dateWise' ]['buckets'];
                $flag =0;
                foreach ($resultData as $key => $value) {
                    if($value['countPivot']['value'] >0){
                        $flag =1;
                    }
                    if($inputRequest['aspect'] == 'avgsessdur'){
                        $result[date("Y-m-d", strtotime($value['key_as_string']))] = number_format((($value['countPivot']['value'])/($value['doc_count']*60)), 2, '.', '');
                    }else if($inputRequest['aspect'] == 'bounce'){
                        $result[date("Y-m-d", strtotime($value['key_as_string']))] = number_format((($value['countPivot']['value']*100)/$value['doc_count']), 2, '.', '');
                    }else if($inputRequest['aspect'] == 'pgpersess'){
                        $result[date("Y-m-d", strtotime($value['key_as_string']))] = number_format((($value['countPivot']['value'])/($value['doc_count'])), 2, '.', '');
                    }                    
                }
                if($flag ==0){
                    $result =array();
                }
                break;

            case 'exit':
                //pageviews query
                $params = array();
                $this->elasticQuery = array();
                $elasticQueryLib = $this->CI->load->library("trackingMIS/elasticQueryLib");
                $result = $elasticQueryLib->prepareElasticSearchQuery($inputRequest,'pageview');
                $this->elasticQuery = $result['elasticQuery'];
                unset($result);
                
                $params = $this->elasticQuery;
                $dateWiseFilter = $this->_addAggerateToQuery('visitTime',$inputRequest['view'],'time');
                $params['body']['aggs'] = $dateWiseFilter;
                //error_log("ELASTICSEARCHQUERY : ENGAGEMENT TRANDS :   ".json_encode($params)."          ");
                $search = $this->clientCon->search($params);
                $dateWiseData = $search['aggregations']['dateWise']['buckets'];
                $lineArrayForExit = array();
                foreach ($dateWiseData as $key => $value) {
                    $lineArrayForPageViews[date("Y-m-d", strtotime($value['key_as_string']))] = $value['doc_count'];
                }
                //_p($lineArrayForPageViews);

                //exit session query
                $this->elasticQuery = array();
                $result = $elasticQueryLib->prepareElasticSearchQuery($inputRequest,'session','exitPage');
                $this->elasticQuery = $result['elasticQuery'];
                unset($result);
                $dateWiseFilter = $this->_addAggerateToQuery('exitPage.visitTime',$inputRequest['view'],'time');
                $this->elasticQuery['body']['aggs'] = $dateWiseFilter;
                //error_log("ELASTICSEARCHQUERY : ENGAGEMENT TRANDS :   ".json_encode($this->elasticQuery)."          ");
                $search = $this->clientCon->search($this->elasticQuery);
                $dateWiseData = $search['aggregations']['dateWise']['buckets'];
                $lineArrayForExit = array();
                foreach ($dateWiseData as $key => $value) {
                    $lineArrayForExit[date("Y-m-d", strtotime($value['key_as_string']))] = $value['doc_count'];
                }
                //_p($lineArrayForExit);die;
                $result = array();
                $flag =0;
                foreach ($lineArrayForExit as $key => $value) {
                    $result[$key] = number_format((($value*100)/$lineArrayForPageViews[$key]), 2, '.', '');
                    if(intval($result[$key]) >0){
                        $flag =1;
                    }
                }
                if($flag ==0){
                    $result =array();
                }
                break;
            
            default:
                # code...
                break;
        }
        return $result;
    }

    private function _addAggerateToQuery($field,$view='1',$aggAspect='time', $useTimeZone = 'yes'){
        switch ($aggAspect) {
            case 'time':
                $dateWiseFilter = array(
                    'dateWise' => array(
                        'date_histogram' => array(
                            'interval' => MISCommonLib::getView($view),
                            'field'    => $field,
                            'order' => array(
                                "_key" => "desc"
                            )
                        ),
                    )
                );
                if($useTimeZone == 'yes'){
                    $dateWiseFilter['dateWise']['date_histogram']['time_zone'] = ELASTIC_TIMEZONE;
                }
                return $dateWiseFilter;
                break;

            case 'sumAgg':
                $countPivotFilter = array(
                    'countPivot' => array(
                        'sum' => array(
                            'field' => $field,
                        )
                    )
                );
                return $countPivotFilter;

            case 'fieldAgg':
                $fieldAggFilter = array(
                    'aggPivot' => array(
                        'terms' => array(
                            'field'    => $field,
                            'size' => ELASTIC_AGGS_SIZE,
                            'order' => array(
                                "_count" => "desc"
                            )
                        ),
                    )
                );
                return $fieldAggFilter;
                break;

            default:
                # code...
                break;
        }
    }

    private function _trafficTrands($inputRequest){
        //_p($inputRequest);die;
        if($inputRequest['aspect'] == 'pageViews'){
            $this->elasticQuery = array();
            $elasticQueryLib = $this->CI->load->library("trackingMIS/elasticQueryLib");
            $result = $elasticQueryLib->prepareElasticSearchQuery($inputRequest,'pageview');
            $this->elasticQuery = $result['elasticQuery'];
            
            $dateWiseFilter = $this->_addAggerateToQuery('visitTime',$inputRequest['view'],'time');
        }else{
            $this->elasticQuery = array();
            $elasticQueryLib = $this->CI->load->library("trackingMIS/elasticQueryLib");
            $result = $elasticQueryLib->prepareElasticSearchQuery($inputRequest,'session');
            $this->elasticQuery = $result['elasticQuery'];
            $dateWiseFilter = $this->_addAggerateToQuery('startTime',$inputRequest['view'],'time');

            if($inputRequest['aspect'] == 'Users'){
                $dateWiseFilter['dateWise']['aggs'] = array(
                    'users' => array(
                        'cardinality' => array(
                            'field' => 'visitorId'
                        )
                    )
                );
            }
        }

        $this->elasticQuery['body']['aggs'] = $dateWiseFilter;
        //echo json_encode($this->elasticQuery);die;
        //error_log("ELASTICSEARCHQUERY : TRAFFIC Trands :   ".json_encode($this->elasticQuery)."                   ");
        $search = $this->clientCon->search($this->elasticQuery);
        $result = $search['aggregations'][ 'dateWise' ]['buckets'];
        
        foreach($result as $key => $value){
            if($inputRequest['aspect'] == 'Users'){
                $result[date("Y-m-d", strtotime($value['key_as_string']))] = $value['users']['value'];
            } else {
                $result[date("Y-m-d", strtotime($value['key_as_string']))] = $value['doc_count'];
            }
            unset($result[$key]); // Keep on unsetting the values which we do not need
        }

        //_p($result);die;
        return $result;
    }

    private function _prepareUserWiseChartForEngagement($inputRequest){
        $this->elasticQuery = array();
        $elasticQueryLib = $this->CI->load->library("trackingMIS/elasticQueryLib");
        $result = $elasticQueryLib->prepareElasticSearchQuery($inputRequest,'pageview');
        $this->elasticQuery = $result['elasticQuery'];
        unset($result);
                
        switch ($inputRequest['aspect']){
            case 'pageviews':
                $this->elasticQuery['body']['aggs']['users']['filter']['term']['userId'] = 0;
                //error_log("ELASTICSEARCHQUERY : ENGAGEMENT Split :   ".json_encode($this->elasticQuery)."          ");
                //_p(json_encode($this->elasticQuery));die;
                $queryResult = $this->clientCon->search($this->elasticQuery);

                $totalUsers = $queryResult['hits']['total'];
                $nonLoggedInUsers = $queryResult['aggregations']['users']['doc_count'];
                $loggedInUsers = $totalUsers - $nonLoggedInUsers;
                $userwise = array();
                $userwise['Loggedin'] = $loggedInUsers;
                $userwise['Non Loggedin']  = $nonLoggedInUsers;
                arsort($userwise);
                foreach ($userwise as $key => $value) {
                    $result[] = array(
                        'name' => $key,
                        'value' => $value
                        );
                }
                break;

            case 'pgpersess':
            case 'avgsessdur':
            case 'bounce':
                $this->elasticQuery = array();
                $result = $elasticQueryLib->prepareElasticSearchQuery($inputRequest,'session');
                $this->elasticQuery = $result['elasticQuery'];
                unset($result);

                if($inputRequest['aspect'] == 'avgsessdur'){
                    $fieldNameForAggs = 'duration';
                }else if($inputRequest['aspect'] == 'bounce'){
                    $fieldNameForAggs = 'bounce';
                }else if($inputRequest['aspect'] == 'pgpersess'){
                    $fieldNameForAggs = 'pageviews';
                }
                $this->elasticQuery['body']['aggs']['sumPivot']['sum']['field']=$fieldNameForAggs;
                //_p(json_encode($this->elasticQuery));die;
                //error_log("ELASTICSEARCHQUERY : Split :   ".json_encode($this->elasticQuery)."          ");
                $queryResult = $this->clientCon->search($this->elasticQuery);
                unset($this->elasticQuery['body']['aggs']);
                $actualSessionCount = $queryResult['hits']['total'];
                $actualPivotCount = $queryResult['aggregations']['sumPivot']['value'];

                $pivotWiseFilter['users']['filter']['term']['userId'] = 0;
                $pivotWiseFilter['users']['aggs'] = $this->_addAggerateToQuery($fieldNameForAggs,'','sumAgg');
                $this->elasticQuery['body']['aggs'] = $pivotWiseFilter;                
                //_p(json_encode($this->elasticQuery));die;
                //error_log("ELASTICSEARCHQUERY : Split :   ".json_encode($this->elasticQuery)."          ");
                $queryResult = $this->clientCon->search($this->elasticQuery);
                
                $nonLoggedInSessions = $queryResult['aggregations']['users']['doc_count'];
                $nonLoggedInPivotCount = $queryResult['aggregations']['users']['countPivot']['value'];

                $loggedInSessions = $actualSessionCount - $nonLoggedInSessions;
                $loggedInPivotCount = $actualPivotCount - $nonLoggedInPivotCount;
                $userwise = array();


                if($inputRequest['aspect'] == 'pgpersess'){
                    $userwise['Loggedin'] = number_format((($loggedInPivotCount)/$loggedInSessions), 2, '.', '');
                    $userwise['Non Loggedin']  = number_format((($nonLoggedInPivotCount)/$nonLoggedInSessions), 2, '.', '');
                }else if($inputRequest['aspect'] == 'bounce'){
                    $userwise['Loggedin'] = number_format((($loggedInPivotCount*100)/$loggedInSessions), 2, '.', '');
                    $userwise['Non Loggedin']  = number_format((($nonLoggedInPivotCount*100)/$nonLoggedInSessions), 2, '.', '');
                }else if($inputRequest['aspect'] == 'avgsessdur'){
                    $hourFormat = explode('.',number_format((($loggedInPivotCount)/($loggedInSessions)), 2, '.', ''));
                    $resultset =date('i:s', mktime(0, 0, $hourFormat[0]));
                    $userwise['Loggedin'] = $resultset;

                    $hourFormat = explode('.',number_format((($nonLoggedInPivotCount)/($nonLoggedInSessions)), 2, '.', ''));
                    $resultset =date('i:s', mktime(0, 0, $hourFormat[0]));
                    $userwise['Non Loggedin']  = $resultset;
                }
                arsort($userwise);
                $flag =0;
                foreach ($userwise as $key => $value) {
                    if(intval($value) >0){
                        $flag =1;
                    }
                    $result[] = array(
                        'name' => $key,
                        'value' => $value
                        );
                } 
                if($flag == 0){
                    $result = '';
                }
                
                break;

            case 'exit':
                    //pageviews query
                    $params = array();
                    $this->elasticQuery = array();
                    $result = $elasticQueryLib->prepareElasticSearchQuery($inputRequest,'pageview');
                    $this->elasticQuery = $result['elasticQuery'];
                    unset($result);                    
                    $params = $this->elasticQuery;
                    $pivotWiseFilter['userWise']['filter']['term']['userId'] =0;
                    $params['body']['aggs'] = $pivotWiseFilter;
                    //_p(json_encode($params));
                    //error_log("ELASTICSEARCHQUERY : ENGAGEMENT USER Split :   ".json_encode($params)."          ");
                    $search = $this->clientCon->search($params);
                    $totalUsers = $search['hits']['total'];
                    $totalNonLoggUsersForExit = $search['aggregations']['userWise']['doc_count'];
                    $totalLoggInUsersForExit = $totalUsers - $totalNonLoggUsersForExit;                        

                    //exit session query
                    $this->elasticQuery = array();
                    //$this->_prepareQueryForExitPage($inputRequest);
                    $result = $elasticQueryLib->prepareElasticSearchQuery($inputRequest,'session','exitPage');
                    $this->elasticQuery = $result['elasticQuery'];
                    unset($result);
                    $pivotWiseFilter = array();
                    $pivotWiseFilter['userWise']['filter']['term']['exitPage.userId'] =0;
                    $this->elasticQuery['body']['aggs'] = $pivotWiseFilter;
                    //_p(json_encode($this->elasticQuery));die;
                    //error_log("ELASTICSEARCHQUERY : ENGAGEMENT USER Split :   ".json_encode($this->elasticQuery)."          ");
                    $search = $this->clientCon->search($this->elasticQuery);
                    $totalUsers = $search['hits']['total'];
                    $totalNonLoggUsersForPageWiews = $search['aggregations']['userWise']['doc_count'];
                    $totalLoggInUsersPageWiews = $totalUsers - $totalNonLoggUsersForPageWiews;

                    $userwise = array();
                    $userwise['Loggedin'] = number_format((($totalLoggInUsersPageWiews*100)/$totalLoggInUsersForExit), 2, '.', '');
                    $userwise['Non Loggedin']  = number_format((($totalNonLoggUsersForPageWiews*100)/$totalNonLoggUsersForExit), 2, '.', '');
                    arsort($userwise);
                    $flag =0;
                    foreach ($userwise as $key => $value) {
                        if(intval($value) >0){
                            $flag =1;
                        }
                        $result[] = array(
                            'name' => $key,
                            'value' => $value
                            );
                    } 
                    if($flag == 0){
                        $result = '';
                    }
                    //_p($sourceApplicationForExit);die;
                break;
            
            default:
                # code...
                break;
        }
        return $result;
    }

    private function _trafficSplits($inputRequest){
        //_p($inputRequest);die;
        if($inputRequest['aspect'] == 'pageViews'){
            $this->elasticQuery = array();
            $elasticQueryLib = $this->CI->load->library("trackingMIS/elasticQueryLib");
            $ESQuery = $elasticQueryLib->prepareElasticSearchQuery($inputRequest,'pageview');
            $this->elasticQuery = $ESQuery['elasticQuery'];

            $inputRequest['donutChart']['fieldName'] = str_replace('landingPageDoc.pageIdentifier', 'pageIdentifier',$inputRequest['donutChart']['fieldName']);
            $pivotFilter = array(
                'pivot' => array(
                    'terms' => array(
                        'field' => $inputRequest['donutChart']['fieldName'],
                        'size' => ELASTIC_AGGS_SIZE
                    )
                )
            );
            $this->elasticQuery['body']['aggs'] = $pivotFilter;
            //error_log("ELASTICSEARCHQUERY : TRAFFIC ".$inputRequest['donutChart']['fieldName']." Split  :  ".json_encode($this->elasticQuery)."                     "); 
            $queryResult = $this->clientCon->search($this->elasticQuery);
            $sourceApplicationArray = array('yes','no');
            $actualCount = $queryResult['hits']['total'];
            //_p($actualCount);
            $totalCount = 0;
            foreach($queryResult['aggregations']['pivot']['buckets'] as $key => $value){
                if(!$value['key']){
                    continue;
                }
                if(in_array($value['key'], $sourceApplicationArray)){
                    $temp = ($value['key']=='no')?"Desktop":"Mobile";
                }else{
                    if($inputRequest['donutChart']['fieldName'] == 'pageIdentifier'){
                        $temp = $this->getPageName($value['key']);
                        if(!$temp){
                            $temp =$value['key'];
                        }
                    }else{
                        $temp =$value['key'];    
                    }    
                }
                $totalCount += $value['doc_count'];
                $resultset[$temp] += $value['doc_count'];                
            }
        }else{
            $this->elasticQuery = array();
            $elasticQueryLib = $this->CI->load->library("trackingMIS/elasticQueryLib");
            $ESQuery = $elasticQueryLib->prepareElasticSearchQuery($inputRequest,'session');
            $this->elasticQuery = $ESQuery['elasticQuery'];

            $pivotFilter = array(
                'pivot' => array(
                    'terms' => array(
                        'field' => $inputRequest['donutChart']['fieldName'],
                        'size' => ELASTIC_AGGS_SIZE
                    )
                )
            );
            if($inputRequest['aspect'] == 'Users') {
                $pivotFilter['pivot']['aggs'] = array(
                    'usersCount' => array(
                        'cardinality' => array(
                            'field' => 'visitorId'
                        )
                    )
                );
            }
            $this->elasticQuery['body']['aggs'] = $pivotFilter;
            //error_log("ELASTICSEARCHQUERY : TRAFFIC ".$inputRequest['donutChart']['fieldName']." Split  :  ".json_encode($this->elasticQuery)."                     ");
            
            $queryResult = $this->clientCon->search($this->elasticQuery);
            $actualCount = $queryResult['hits']['total'];
            $totalCount = 0;
            $sourceApplicationArray = array('yes','no');
            foreach($queryResult['aggregations']['pivot']['buckets'] as $key => $value){
                if(!$value['key']){
                    continue;
                }
                if(in_array($value['key'], $sourceApplicationArray)){
                    $temp = ($value['key']=='no')?"Desktop":"Mobile";
                }else{
                    if($inputRequest['donutChart']['fieldName'] == 'landingPageDoc.pageIdentifier'){
                        $temp = $this->getPageName($value['key']);   
                    }else{
                        $temp =$value['key'];    
                    }    
                }
                
                if($inputRequest['aspect'] == 'Users'){
                    $resultset[$temp] += $value['usersCount']['value'];                    
                }else{
                    $resultset[$temp] += $value['doc_count'];                    
                    $totalCount += $value['doc_count'];
                }
            }
        }
        
        if($inputRequest['aspect'] != 'Users') {
            if($actualCount > $totalCount){
                $resultset['Other'] = $actualCount - $totalCount;
            }
        }
        
        arsort($resultset);
        foreach ($resultset as $key => $value) {
            $result[] = array(
                'name' => $key,
                'value' => intval($value)
                );
        }
        
        return $result;
    }

    private function _addAggerateToESQuery($aggerateFilter,$fieldName,$view=1, $useTimeZone = 'yes', $addMissingValueCount = "no"){
        $resultset = array();
        switch ($aggerateFilter) {
            case 'lineChart':
                $pivotAggeration = array(
                    'pivot' => array(
                        'date_histogram' => array(
                            'field' => $fieldName,
                            'interval' => MISCommonLib::getView($view),
                            "order" => array(
                               "_key" => "desc"
                            )
                        )
                    )
                );
                if($useTimeZone == 'yes'){
                    $pivotAggeration['pivot']['date_histogram']['time_zone'] = ELASTIC_TIMEZONE;
                }
                $this->elasticQuery['body']['aggs'] = $pivotAggeration;
                //_p(json_encode($this->elasticQuery));die;
                $queryResult = $this->clientCon->search($this->elasticQuery);
                //_p($queryResult);die;
                foreach($queryResult['aggregations']['pivot']['buckets'] as $key => $value){
                    $resultset[date("Y-m-d", strtotime($value['key_as_string']))] = $value['doc_count'];
                }
                break;

            case 'split':
                $pivotAggeration = array(
                    'pivot' => array(
                        'terms' => array(
                            'field' => $fieldName,
                            'size' => ELASTIC_AGGS_SIZE,
                            "order" => array(
                               "_count" => "desc"
                            )
                        )
                    )
                );
                $this->elasticQuery['body']['aggs'] = $pivotAggeration;
                //error_log("ELASTICSEARCHQUERY : RESPONSE Split :   ".json_encode($this->elasticQuery)."          ");
                //_p(json_encode($this->elasticQuery));die;
                $queryResult = $this->clientCon->search($this->elasticQuery);
                //_p($queryResult);die;
                $total = 0;
                foreach($queryResult['aggregations']['pivot']['buckets'] as $key => $value){
                    $total += $value['doc_count'];
                    if($fieldName == 'pageIdentifier' || $fieldName == 'page'){
                        $resultset[] = array(
                            'name' => $this->getPageName($value['key']),
                            'value' => $value['doc_count']
                        );
                    }else{
                        $resultset[] = array(
                            'name' => $value['key'],
                            'value' => $value['doc_count']
                        );
                    }
                }

                if($addMissingValueCount == 'yes'){
                    if($queryResult['hits']['total'] != $total){
                        $resultset[] = array(
                            'name' => "Other",
                            'value' => ($queryResult['hits']['total'] - $total)
                        );
                    }
                    usort($resultset, function($a,$b){
                        return($a['value'] >=$b['value']?-1:1);
                    });
                }

                break;
            
            case 'dataTableAggs':
                $resultset = array(
                    $fieldName => array(
                        'terms' => array(
                            'field' => $fieldName,
                            'size' => ELASTIC_AGGS_SIZE,
                        )
                    )
                );
                break;
        }
        //_p($resultset);die;
        return $resultset;
    }

    private function _prepareQueryForPageviews($inputRequest){
        $this->elasticQuery = array(
            'index' => PAGEVIEW_INDEX_NAME,
            'type' => 'pageview',
            'body' => array(
                'size' => 0
            )
        );

        //Date Range Filter
        if($inputRequest['dateRange']['startDate'] != ''){
            $startDateFilter = array(
                'range' => array(
                    'visitTime' => array(
                        'gte' => $inputRequest['dateRange']['startDate'] . 'T00:00:00',
                        'lte' => $inputRequest['dateRange']['endDate'] . 'T23:59:59'
                    ),
                )
            );
            $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][] = $startDateFilter;
        }
        
        // team filter
        if ($inputRequest['misSource'] != 'SHIKSHA') {
            if ($inputRequest['misSource'] == 'STUDY ABROAD') {
                $teamFilter = 'yes';
            } else if ($inputRequest['misSource'] == 'DOMESTIC') {
                $teamFilter = 'no';
            }
            $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['term']['isStudyAbroad'] = $teamFilter;
        }        

        // Page Filter
        if (strlen($inputRequest['pageName']) > 0 && strcasecmp($inputRequest['pageName'], 'all') != 0) {
            if ($inputRequest['misSource'] == 'STUDY ABROAD') {
                if ($inputRequest['pageName'] == 'rankingPage') {
                        if ($inputRequest['pageType'] == 1) {
                            $pageNameFilter = array(
                                'term' => array(
                                    'pageIdentifier' => 'universityRankingPage'
                                )
                            );
                        } else if ($inputRequest['pageType'] == 2) {
                            $pageNameFilter = array(
                                'term' => array(
                                    'pageIdentifier' => 'courseRankingPage'
                                )
                            );
                        } else {
                            $pageNameFilter = array(
                                'terms' => array(
                                    'pageIdentifier' => array(
                                        'courseRankingPage',
                                        'universityRankingPage'
                                    )
                                )
                            );
                        }                
                    }else{
                        $pageNameFilter = array(
                            'term' => array(
                                'pageIdentifier' => $inputRequest['pageName']
                            )
                        );
                    }
            }else{
                $pageNameFilter = array(
                            'term' => array(
                                'pageIdentifier' => $inputRequest['pageName']
                            )
                        );
            }
            $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][] = $pageNameFilter;
        }

        // catogory/subcategory/courseLevel
        if($inputRequest['misSource'] == 'STUDY ABROAD') {
            if($inputRequest['categoryId'] >0){
                $this->abroadCommonLib = $this->CI->load->library('listingPosting/AbroadCommonLib');
                $ldbCourseIdsArray = $this->abroadCommonLib->getAbroadMainLDBCourses();
                foreach ($ldbCourseIdsArray as $key => $value) {
                    $ldbCourseIds[]= $value['SpecializationId'];
                }
                if(in_array($inputRequest['categoryId'],$ldbCourseIds)){
                    $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['term']['LDBCourseId'] = $inputRequest['categoryId'];
                }else{
                    $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['term']['categoryId'] = $inputRequest['categoryId'];
                    if($inputRequest['courseLevel'] != '0'){
                        $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['term']['courseLevel'] = $inputRequest['courseLevel'];
                    }
                }
            }else if(!empty($inputRequest['courseLevel']) && $inputRequest['courseLevel'] != '0'){
                $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['term']['courseLevel'] = $inputRequest['courseLevel'];
            }
            if(!empty($inputRequest['country']) && $inputRequest['country'] != '0'){
                $this->elasticQuery['body']['query']['bool']['filter']['bool']['should'][]['term']['countryId'] = $inputRequest['country'];
            }
        }else if ($inputRequest['misSource'] == 'DOMESTIC'){
            $excludedPageArray= array('homePage','instituteListingPage','eventCalendar','qnaPage');
            if (!in_array($inputRequest['pageName'], $excludedPageArray)){
                if($inputRequest['pageName'] == 'eventCalendar' || $inputRequest['pageName'] == 'articlePage') {
                    if (intval($inputRequest['subCategory']) > 0) {
                        $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][] = array(
                            'term' => array(
                                'subCategoryId' => $inputRequest['subCategory']
                            )
                        );
                    }
                }else if($inputRequest['pageName'] == 'articleDetailPage') {
                    if(intval($inputRequest['categoryId']) > 0 ){
                        $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][] = array(
                            'term' => array(
                                'categoryId' => doubleval($inputRequest['categoryId'])
                            )
                        );
                    }
                }else {
                    if(intval($inputRequest['categoryId']) > 0 ){
                        $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][] = array(
                            'term' => array(
                                'categoryId' => doubleval($inputRequest['categoryId'])
                            )
                        );
                    }
                    if (intval($inputRequest['subCategory']) != 0) {
                        $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][] = array(
                            'term' => array(
                                'subCategoryId' => $inputRequest['subCategory']
                            )
                        );
                    }
                }
            }
            else {
                if(intval($inputRequest['categoryId']) > 0 ){
                    $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][] = array(
                        'term' => array(
                            'categoryId' => doubleval($inputRequest['categoryId'])
                        )
                    );
                }
                if (intval($inputRequest['subCategory']) > 0) {
                    $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][] = array(
                        'term' => array(
                            'subCategoryId' => $inputRequest['subCategory']
                        )
                    );
                }
            }
        }

        // adding common filter
        $this->_prepareCommonFilterForElasticQuery($inputRequest);
        //_p($this->elasticQuery);die;
    }

    private function _prepareQueryForTraffic($inputRequest){
        $this->elasticQuery = array(
            'index' => SESSION_INDEX_NAME,
            'type'  => 'session',
            'body'  => array(
                'size' => 0
            )
        );

        //Date Range Filter
        if($inputRequest['dateRange']['startDate'] != ''){
            $startDateFilter = array(
                'range' => array(
                    'startTime' => array(
                        'gte' => $inputRequest['dateRange']['startDate'] . 'T00:00:00',
                        'lte' => $inputRequest['dateRange']['endDate'] . 'T23:59:59'
                    ),
                )
            );
            $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][] = $startDateFilter;
        }

        // team filter
        if ($inputRequest['misSource'] != 'SHIKSHA') {
            if ($inputRequest['misSource'] == 'STUDY ABROAD') {
                $teamFilter = 'yes';
            } else if ($inputRequest['misSource'] == 'DOMESTIC') {
                $teamFilter = 'no';
            }
            $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['term']['isStudyAbroad'] = $teamFilter;
        }        

        // Page Filter
        $pivot = 'landingPageDoc.';
        if (strlen($inputRequest['pageName']) > 0 && strcasecmp($inputRequest['pageName'], 'all') != 0) {
            if ($inputRequest['misSource'] == 'STUDY ABROAD') {
                if ($inputRequest['pageName'] == 'rankingPage') {
                        if ($inputRequest['pageType'] == 1) {
                            $pageNameFilter = array(
                                'term' => array(
                                    $pivot.'pageIdentifier' => 'universityRankingPage'
                                )
                            );
                        } else if ($inputRequest['pageType'] == 2) {
                            $pageNameFilter = array(
                                'term' => array(
                                    $pivot.'pageIdentifier' => 'courseRankingPage'
                                )
                            );
                        } else {
                            $pageNameFilter = array(
                                'terms' => array(
                                    $pivot.'pageIdentifier' => array(
                                        'courseRankingPage',
                                        'universityRankingPage'
                                    )
                                )
                            );
                        }                
                    }else{
                        $pageNameFilter = array(
                            'term' => array(
                                $pivot.'pageIdentifier' => $inputRequest['pageName']
                            )
                        );
                    }
            }else{
                $pageNameFilter = array(
                            'term' => array(
                                $pivot.'pageIdentifier' => $inputRequest['pageName']
                            )
                        );
            }
            $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][] = $pageNameFilter;
        }

        if($inputRequest['misSource'] == 'STUDY ABROAD') {
            if($inputRequest['categoryId'] >0){
                $this->abroadCommonLib = $this->CI->load->library('listingPosting/AbroadCommonLib');
                $ldbCourseIdsArray = $this->abroadCommonLib->getAbroadMainLDBCourses();
                foreach ($ldbCourseIdsArray as $key => $value) {
                    $ldbCourseIds[]= $value['SpecializationId'];
                }
                if(in_array($inputRequest['categoryId'],$ldbCourseIds)){
                    $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['term']['landingPageDoc.LDBCourseId'] = $inputRequest['categoryId'];
                }else{
                    $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['term']['landingPageDoc.categoryId'] = $inputRequest['categoryId'];
                    if($inputRequest['courseLevel'] != '0'){
                        $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['term']['landingPageDoc.courseLevel'] = $inputRequest['courseLevel'];
                    }
                }
            }else if(!empty($inputRequest['courseLevel']) &&  $inputRequest['courseLevel'] != '0'){
                $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['term']['landingPageDoc.courseLevel'] = $inputRequest['courseLevel'];
            }
            if(!empty($inputRequest['country']) && $inputRequest['country'] != '0'){
                $this->elasticQuery['body']['query']['bool']['filter']['bool']['should'][]['term']['landingPageDoc.countryId'] = $inputRequest['country'];
            }
        }else if ($inputRequest['misSource'] == 'DOMESTIC'){
            if ($inputRequest['pageName'] != 'eventCalendar' && $inputRequest['pageName'] != 'articlePage' ) {
                if($inputRequest['categoryId'] >0){
                    $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['term']['landingPageDoc.categoryId'] = $inputRequest['categoryId'];
                }
            }
                
            if (intval($inputRequest['subCategory']) > 0){
                $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['term']['landingPageDoc.subCategoryId'] = $inputRequest['subCategory'];
            }
        }

        // adding user filter
        $this->_prepareCommonFilterForElasticQuery($inputRequest);
        //_p($this->elasticQuery);die;
    }

    private function _prepareCommonFilterForElasticQuery($inputRequest,$pivot=''){
        // adding user filter
        $this->_addUserFilterToElasticQuery($inputRequest,$pivot);

        //Source Application
        $this->_addSourceApplicationFilterToElasticQuery($inputRequest,$pivot);
        //_p($this->elasticQuery);die;
    }

    private function _addSourceApplicationFilterToElasticQuery($inputRequest,$pivot=''){
        if (strcasecmp($inputRequest['sourceApplication'], 'all') != 0) {
            if (
                strcasecmp($inputRequest['sourceApplication'], 'desktop') == 0 ||
                strcasecmp($inputRequest['sourceApplication'], 'mobile') == 0
            ) {
                $fieldName = (empty($pivot))?'isMobile':$pivot.'.'.'isMobile';
                $deviceFilter = array(
                    'term' => array(
                        $fieldName => ($inputRequest['sourceApplication'] == 'desktop') ? 'no' : 'yes'
                    )
                );
                $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][] = $deviceFilter;
            }
        }
    }

    private function _addUserFilterToElasticQuery($inputRequest,$pivot=''){
        //echo 'dddww';_p($this->elasticQuery);die;
        if(!empty($inputRequest['userFilter'])){
            $fieldName = (empty($pivot))?'userId':$pivot.'.'.'userId';
            if($inputRequest['userFilter'] == 'loggedIn'){
                $userFilter = array(
                    'term'  =>  array(
                        $fieldName    => 0
                        )
                    );
                $this->elasticQuery['body']['query']['bool']['filter']['bool']['must_not'][] = $userFilter;
            }else if($inputRequest['userFilter'] == 'nonLoggedIn'){
                $userFilter = array(
                    'term'  =>  array(
                        $fieldName    => 0
                        )
                    );
                $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][] = $userFilter;
            }
        }
        //_p($this->elasticQuery);die;
    }

    // MIS shiksha assistant top tiles
    public function _sassistantTiles($inputRequest){
        //_p($inputRequest['topTiles']);
        // prepare Default Query
        $this->_getSearchServerConnection('','ES5');
        $this->elasticQuery = array();
        $elasticQueryLib = $this->CI->load->library("trackingMIS/elasticQueryLib");
        $this->elasticQuery = $elasticQueryLib->prepareQueryForAssistantESIndex($inputRequest);
        $this->elasticQuery['body']['aggs']['totalSessions']['cardinality']['field'] = 'sessionId';
        //_p(json_encode($this->elasticQuery));die;
        $result = $this->clientCon->search($this->elasticQuery);
        //_p($result);die;
        $totalSessions = $result['aggregations']['totalSessions']['value'];
        $totalConversations = $result['hits']['total'];
        $resultArray = array(
            'totalConversations' => $totalConversations,
            'conversationPerSessions' => number_format((($totalConversations)/$totalSessions), 2, '.', '')
        );
        return $resultArray;
    }

    // MIS Registrations
    public function _registrationTiles($inputRequest){
        // prepare Default Query
        $this->elasticQuery = array();
        $elasticQueryLib = $this->CI->load->library("trackingMIS/elasticQueryLib");
        $this->elasticQuery = $elasticQueryLib->prepareQueryForRegistration($inputRequest);
        //_p(json_encode($this->elasticQuery));die;
        $resultArray = array();

        foreach ($inputRequest['topTiles'] as $key ) {
            $tempESQuery = $this->elasticQuery;
            switch ($key) {
                case 'totalRegistration':
                    $result = $this->clientCon->search($this->elasticQuery);
                    $resultArray[$key] = ($result['hits']['total']);
                    break;

                case 'mmpRegistration':
                    $tempESQuery['body']['query']['bool']['filter']['bool']['must'][]['term']['widget'] = 'marketingPageForm';
                    $result = $this->clientCon->search($tempESQuery);
                    $resultArray[$key] = ($result['hits']['total']);
                    break;

                case 'responseRegistration':
                    $responseRegistration = array('response','Course shortlist','downloadBrochure','send Contat Detail','send Contact Detail');
                    $tempESQuery['body']['query']['bool']['filter']['bool']['must'][]['terms']['conversionType'] = $responseRegistration;
                    $result = $this->clientCon->search($tempESQuery);
                    $resultArray[$key] = ($result['hits']['total']);
                    break;

                case 'signUpRegistration':
                    $topSignup = array('topsignupwidget','findBestCollegesForYourself','registerFree');
                    $tempESQuery['body']['query']['bool']['filter']['bool']['must'][]['terms']['widget'] = $topSignup;
                    $tempESQuery['body']['query']['bool']['filter']['bool']['must'][]['term']['sourceApplication'] = 'Desktop';
                    $result = $this->clientCon->search($tempESQuery);
                    $resultArray[$key] = ($result['hits']['total']);
                    break;

                case 'hamburgerRegistration':
                    $topSignup = array('topsignupwidget','findBestCollegesForYourself','registerButton');
                    $tempESQuery['body']['query']['bool']['filter']['bool']['must'][]['terms']['widget'] = $topSignup;
                    $tempESQuery['body']['query']['bool']['filter']['bool']['must'][]['term']['sourceApplication'] = 'Mobile';
                    $result = $this->clientCon->search($tempESQuery);
                    $resultArray[$key] = ($result['hits']['total']);
                    break;

                case 'guideRegistration':
                    $tempESQuery['body']['query']['bool']['filter']['bool']['must'][]['term']['conversionType'] = 'downloadGuide';
                    $result = $this->clientCon->search($tempESQuery);
                    $resultArray[$key] = ($result['hits']['total']);
                    break;
                
            }
        }
        return $resultArray;
    }

    // MIS Responses
    private function _responseTiles($inputRequest){
        //_p($inputRequest);die;
        $this->_getSearchServerConnection($inputRequest['metric']);
        $response = array();
        $this->elasticQuery = array();
        $elasticQueryLib = $this->CI->load->library("trackingMIS/elasticQueryLib");

        if($inputRequest['misSource'] == 'STUDY ABROAD' || $inputRequest['misSource'] == 'SHIKSHA') {
            $trackingIds = $this->getTrackingKeys($inputRequest,array('keyName' => 'rateMyChance'));
        }
        $baseQuery = $elasticQueryLib->prepareQueryForResponse($inputRequest,array("trackingIds" => $trackingIds));
        $this->elasticQuery = $baseQuery;
        //_p(json_encode($this->elasticQuery));
        $this->elasticQuery['body']['aggs']['uniqueUsersCount']['cardinality']['field'] = 'user_id';
        //_p(json_encode($this->elasticQuery));die;
        error_log("ELASTICSEARCHQUERY : RESPONSE Top tiles 1 :   ".json_encode($this->elasticQuery)."          ");
        $result = $this->clientCon->search($this->elasticQuery);
        $uniqueUsersCount = $result['aggregations']['uniqueUsersCount']['value'];
        $response['totalResponse'] = $result['hits']['total'];
        $response['responsePerRespondent'] = number_format($result['hits']['total'] / $uniqueUsersCount, 2);

        if($inputRequest['misSource'] == 'DOMESTIC'){
            $this->elasticQuery = $baseQuery;
            $this->elasticQuery['body']['aggs']['uniqueSessionsCount']['cardinality']['field'] = 'visitor_session_id';
            $result = $this->clientCon->search($this->elasticQuery);
            $uniqueSessionsCount = $result['aggregations']['uniqueSessionsCount']['value'];
            $response['totalResponse'] = $result['hits']['total'];
            $response['responsePerSessions'] = number_format($result['hits']['total'] / $uniqueSessionsCount, 2);
        }

        //_p($response);die;

        if($inputRequest['metric'] != "RMC" && !($inputRequest['misSource'] == 'STUDY ABROAD' && $inputRequest['pageName'] != "")){
            // paid responses
            $this->elasticQuery = $baseQuery;
            $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][] = array(
                'term' => array('is_response_paid' => '1')
            );
            error_log("ELASTICSEARCHQUERY : RESPONSE Top tiles 2 :   ".json_encode($this->elasticQuery)."          ");
            $result = $this->clientCon->search($this->elasticQuery);
            $response['paidResponse'] = $result['hits']['total'];
        }


        if(($inputRequest['misSource'] == 'STUDY ABROAD' || $inputRequest['misSource'] == 'SHIKSHA') && $inputRequest['metric'] == "response" && $inputRequest['pageName'] == "") {
            $response['rmcResponse'] = 0;
            if(count($trackingIds) >0){
                $this->elasticQuery = $baseQuery;
                $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][] = array(
                    'terms' => array('tracking_id' => $trackingIds)
                );
                error_log("ELASTICSEARCHQUERY : RESPONSE Top tiles 3 :   ".json_encode($this->elasticQuery)."          ");
                $result = $this->clientCon->search($this->elasticQuery);
                $response['rmcResponse'] = $result['hits']['total'];
            }
        }

        $saModel = $this->CI->load->model('trackingMIS/samismodel');

        // paidCourse
        if($inputRequest['metric'] == "response"){
            if($inputRequest['misSource'] == 'STUDY ABROAD' && $inputRequest['pageName'] == ""){
                $inputRequest['startDate'] = $inputRequest['dateRange']['startDate'];
                $inputRequest['endDate'] = $inputRequest['dateRange']['endDate'];
                $responseType = (($inputRequest['responseSubscriptionType'] != 'all' && isset($inputRequest['responseSubscriptionType'])) ? $inputRequest['responseSubscriptionType'] : 'paid');                

                $paidCoursesCount = $saModel->getCourseIdsForSelectedDuration(GOLD_SL_LISTINGS_BASE_PRODUCT_ID,$inputRequest,$responseType);
                $paidCoursesCount = count($paidCoursesCount);
                $response['paidCourse'] = $paidCoursesCount;
                unset($inputRequest['endDate']);
                unset($inputRequest['startDate']);
            }
        }

        if($inputRequest['misSource'] == 'STUDY ABROAD') {
            $inputRequest['startDate'] = $inputRequest['dateRange']['startDate'];
            $inputRequest['endDate'] = $inputRequest['dateRange']['endDate'];
            if($inputRequest['metric'] == "RMC"){
                $universityIdsForSelectedDuration =  $saModel->getRMCUniversityIdsForSelectedDuration($inputRequest);
                
                $universityIdsArray = array_map(function($a){
                    return $a['universityId'];
                }, $universityIdsForSelectedDuration);
                if(count($universityIdsArray)){
                    $courseIdsForSelectedDuration = $saModel->getRMCCourseIds($universityIdsArray,$inputRequest);    
                }else{
                    $courseIdsForSelectedDuration = 0;
                }     
                $universityIdsCount = count($universityIdsForSelectedDuration);   
                $courseIdsCount = count($courseIdsForSelectedDuration);
                $response['totalUniv'] = $universityIdsCount;
                $response['totalCourse'] = $courseIdsCount;
                $response['totalUsers'] = $uniqueUsersCount;
            }else if($inputRequest['metric'] == "response"){
                if($inputRequest['pageName'] != ""){
                    $samismodel = $this->CI->load->model('samismodel');
                    $filterArray = array(
                        "startDate" => $inputRequest['dateRange']['startDate'],
                        "endDate" => $inputRequest['dateRange']['endDate'],
                        "country" =>$inputRequest['country'],
                        "category" => $inputRequest['category'],
                        "courseLevel" => $inputRequest['courseLevel']
                    );
                    $courseIdsForSelectedDuration = $samismodel->getCourseIdsForSelectedDuration(GOLD_SL_LISTINGS_BASE_PRODUCT_ID,$filterArray,$inputRequest['responseType']);
                    $courseIdsArray = array_map(function($a){
                        return $a['courseId'];
                    }, $courseIdsForSelectedDuration);
                    $courseIdsCount= count($courseIdsArray);
                    $universityIdsCount =0;
                    if(count($courseIdsArray) >0){
                        $universityIds = $samismodel->getUniversityIds($courseIdsArray); 
                        $universityIdsCount = count($universityIds);     
                    }
                    $response['totalCourse'] = count($courseIdsArray);
                    $response['totalUniv'] = $universityIdsCount;
                }
            }
            unset($inputRequest['endDate']);
            unset($inputRequest['startDate']);
        } else if($inputRequest['misSource'] == 'DOMESTIC'){
            $listingsModel = $this->CI->load->model('trackingMIS/nationalListings/listings_model');
            $paidCoursesCount = $listingsModel->getPaidCoursesCount($inputRequest, $team);
        }else if($inputRequest['misSource'] == 'SHIKSHA'){

        }

        //_p($response);die;
        return $response;
    }


    public function getTrackingKeys($inputRequest, $extraData = array()){
        $OverviewModel = $this->CI->load->model('overview_model');
        $result = $OverviewModel->getTrackingKeys('','',$extraData);
        $trackingIds = array();
        foreach ($result as $key => $trackingData) {
            $trackingIds[] = $trackingData->id;
        }
        return $trackingIds;
    }
    
    private function _responseTrands($inputRequest){
        //_p($inputRequest);die;
        $this->_getSearchServerConnection($inputRequest['metric']);
        $response = array();
        $this->elasticQuery = array();        
        $elasticQueryLib = $this->CI->load->library("trackingMIS/elasticQueryLib");
        $this->elasticQuery = $elasticQueryLib->prepareQueryForResponse($inputRequest);
        //_p(json_encode($this->elasticQuery));die;
        error_log("ELASTICSEARCHQUERY : RESPONSE Trands :   ".json_encode($this->elasticQuery)."          ");
        $result = $this->_addAggerateToESQuery('lineChart',$inputRequest['lineChart'],$inputRequest['view'],'no');
        //_p($result);die;
        return $result;
    }

    
    private function _sassistantTrends($inputRequest){
        //_p($inputRequest);
        $response = array();
        $this->elasticQuery = array();        
        $elasticQueryLib = $this->CI->load->library("trackingMIS/elasticQueryLib");


        if($inputRequest['lineChart'] == "sessionDurationWithAssistant" || $inputRequest['lineChart'] == "sessionDurationWithoutAssistant" || $inputRequest['lineChart'] == "sessionCountWithAssistant" || $inputRequest['lineChart'] == "sessionCountWithoutAssistant" || $inputRequest['lineChart'] == "pagesCountWithAssistant" || $inputRequest['lineChart'] == "pagesCountWithoutAssistant"){
            $ESQuery = $elasticQueryLib->prepareElasticSearchQuery($inputRequest,'session');
            $this->elasticQuery = $ESQuery['elasticQuery'];
        }else if($inputRequest['lineChart'] == "pvFromAssistant"){
            $inputRequest['assistantFilter'] = "assistantPageviews";
            $ESQuery = $elasticQueryLib->prepareElasticSearchQuery($inputRequest,'pageview');
            $this->elasticQuery = $ESQuery['elasticQuery'];
        }else{
            $this->_getSearchServerConnection("","ES5");
            $this->elasticQuery = $elasticQueryLib->prepareQueryForAssistantESIndex($inputRequest);
        }
        
        if($inputRequest['lineChart'] == "conversations" || $inputRequest['lineChart'] == "queriesVsAnsQueries" || $inputRequest['lineChart'] == "answeredQueries"){
            $result = $this->_addAggerateToESQuery('lineChart','queryTime',$inputRequest['view'],'no');
        }else if($inputRequest['lineChart'] == "conversationPerSession"){
            $view = $this->getView($inputRequest['view']);
            $this->elasticQuery['body']['aggs']['conversations'] = array(
                'date_histogram' => array(
                    'field' => 'queryTime',
                    'interval' => $view,
                    'order' => array(
                        '_key' => 'desc'
                    )
                ),
                'aggs' => array(
                    'uniqueSession' => array(
                        'cardinality' => array(
                            'field' => 'sessionId'
                        )
                    )
                )
            );
            $response = $this->clientCon->search($this->elasticQuery);
            //_p($result);die;
            $result = array();
            foreach ($response['aggregations']['conversations']['buckets'] as $key => $value) {
                $result[date("Y-m-d", strtotime($value['key_as_string']))] = number_format((($value['doc_count'])/$value['uniqueSession']['value']), 2, '.', '');
            }
        }else if($inputRequest['lineChart'] == "sessionDurationWithAssistant" || $inputRequest['lineChart'] == "sessionDurationWithoutAssistant"){
            $view = $this->getView($inputRequest['view']);
            $this->elasticQuery['body']['aggs']['conversations'] = array(
                'date_histogram' => array(
                    'field' => 'startTime',
                    'interval' => $view,
                    'time_zone' => ELASTIC_TIMEZONE,
                    'order' => array(
                        '_key' => 'desc'
                    )
                ),
                'aggs' => array(
                    'sessionDuration' => array(
                        'sum' => array(
                            'field' => 'duration'
                        )
                    )
                )
            );

            $response = $this->clientCon->search($this->elasticQuery);
            $result = array();
            foreach ($response['aggregations']['conversations']['buckets'] as $key => $value) {
                $result[date("Y-m-d", strtotime($value['key_as_string']))] = number_format(($value['sessionDuration']['value']/($value['doc_count']*60)), 2, '.', '');
            }
            //_p($result);die;
        }else if($inputRequest['lineChart'] == "sessionCountWithAssistant" || $inputRequest['lineChart'] == "sessionCountWithoutAssistant"){
            $result = $this->_addAggerateToESQuery('lineChart','startTime',$inputRequest['view']);
        }else if($inputRequest['lineChart'] == "pagesCountWithAssistant" || $inputRequest['lineChart'] == "pagesCountWithoutAssistant"){

            $view = $this->getView($inputRequest['view']);
            $this->elasticQuery['body']['aggs']['conversations'] = array(
                'date_histogram' => array(
                    'field' => 'startTime',
                    'interval' => $view,
                    'time_zone' => ELASTIC_TIMEZONE,
                    'order' => array(
                        '_key' => 'desc'
                    )
                ),
                'aggs' => array(
                    'totalPageviews' => array(
                        'sum' => array(
                            'field' => 'pageviews'
                        )
                    )
                )
            );
            $response = $this->clientCon->search($this->elasticQuery);
            $result = array();
            //_p($response);die;
            foreach ($response['aggregations']['conversations']['buckets'] as $key => $value) {
                $result[date("Y-m-d", strtotime($value['key_as_string']))] = number_format(($value['totalPageviews']['value']/($value['doc_count'])), 2, '.', '');
            }
        }else if($inputRequest['lineChart'] == "queriesVsAnsQueriesPerSessions" || $inputRequest['lineChart'] == "answeredPerSessions"){
            $view = $this->getView($inputRequest['view']);
            //_p($this->elasticQuery);die;
            $this->elasticQuery['body']['aggs']['conversations'] = array(
                'date_histogram' => array(
                    'field' => 'queryTime',
                    'interval' => $view,
                    'order' => array(
                        '_key' => 'desc'
                    )
                ),
                'aggs' => array(
                    'uniqueSession' => array(
                        'cardinality' => array(
                            'field' => 'sessionId'
                        )
                    )
                )
            );
            $response = $this->clientCon->search($this->elasticQuery);
            $result = array();
            foreach ($response['aggregations']['conversations']['buckets'] as $key => $value) {
                $result[date("Y-m-d", strtotime($value['key_as_string']))] = number_format((($value['doc_count']*1000)/$value['uniqueSession']['value']), 0, '.', '');
            }
        }else if($inputRequest['lineChart'] == "pvFromAssistant"){
            $result = $this->_addAggerateToESQuery('lineChart','visitTime',$inputRequest['view']);
        }

        return $result;
    }

    private function _registrationTrands($inputRequest){
        //_p($inputRequest);die;
        $this->elasticQuery = array();
        $elasticQueryLib = $this->CI->load->library("trackingMIS/elasticQueryLib");
        $this->elasticQuery = $elasticQueryLib->prepareQueryForRegistration($inputRequest);
        $result = $this->_addAggerateToESQuery('lineChart',$inputRequest['lineChart'],$inputRequest['view'],"no");
        return $result;
    }

    private function _registrationSplits($inputRequest){
        $this->elasticQuery = array();
        $elasticQueryLib = $this->CI->load->library("trackingMIS/elasticQueryLib");
        $this->elasticQuery = $elasticQueryLib->prepareQueryForRegistration($inputRequest);
        
        if($inputRequest['donutChart']['fieldName'] == "sourceApplication" && $inputRequest['misSource'] == "DOMESTIC"){
            $result = $this->_addAggerateToESQuery('split',"sourceApplicationType",1,'no','yes');
        }else{
            $result = $this->_addAggerateToESQuery('split',$inputRequest['donutChart']['fieldName'],1,'no','yes');
        }
        //_p(json_encode($this->elasticQuery));die;

        return $result;
    }

    private function _sassistantSplit($inputRequest){
        //_p($inputRequest);die;
        $this->_getSearchServerConnection('','ES5');
        $response = array();
        $this->elasticQuery = array();
        $elasticQueryLib = $this->CI->load->library("trackingMIS/elasticQueryLib");
        $this->elasticQuery = $elasticQueryLib->prepareQueryForAssistantESIndex($inputRequest);
        if($inputRequest['donutChart'] == "quickReplyTopQueries" || $inputRequest['donutChart'] == "userTopQueries"){
            $this->elasticQuery['body']['aggs']['topUserQuery']['terms'] = array(
                'field' => 'userQuery.raw',
                'size' => 50
            );
            $result = $this->clientCon->search($this->elasticQuery);
            foreach ($result['aggregations']['topUserQuery']['buckets'] as $key => $value) {
                $response[] = array('name' => $value['key'],'value' => $value['doc_count']);
            }
        }else if($inputRequest['donutChart'] == "topIntentAnswered" || $inputRequest['donutChart'] == "topIntentUnAnswered"){
            $this->elasticQuery['body']['aggs']['nestedQueryPath'] = array(
                'nested' => array('path' => 'apiResponseData'),
                'aggs' => array('topIntent' => array('terms' => array('field' => 'apiResponseData.data.predictedTripletsList.attribute','size'=>20)))
                
            );
            //_p(json_encode($this->elasticQuery));die;
            $result = $this->clientCon->search($this->elasticQuery);
            //_p($result);die;
            foreach ($result['aggregations']['nestedQueryPath']['topIntent']['buckets'] as $key => $value) {
                $response[] = array('name' => $value['key'],'value' => $value['doc_count']);
            }
        }else if($inputRequest['donutChart'] == "opinionFactual"){
            $this->elasticQuery['body']['aggs']['nestedQueryPath'] = array(
                'nested' => array('path' => 'apiResponseData'),
                'aggs' => array('topIntent' => array('terms' => array('field' => 'apiResponseData.data.predictedTripletsList.opinionFactual','size'=>3)))
                
            );
            //_p(json_encode($this->elasticQuery));die;
            $result = $this->clientCon->search($this->elasticQuery);
            $total = $result['hits']['total'];
            foreach ($result['aggregations']['nestedQueryPath']['topIntent']['buckets'] as $key => $value) {
                if($value['key'] == ""){
                    $name = "other";
                }else{
                    $name = $value['key'];
                }
                $percentageData = number_format(($value['doc_count']*100/$total), 2, '.', '');
                $response[] = array('name' => $name,'value' => $value['doc_count']." (".$percentageData.")");
            }
        }else if($inputRequest['donutChart'] == "questionByAttribute"){
            $this->elasticQuery['body']['aggs']['nestedQueryPath'] = array(
                'nested' => array('path' => 'apiResponseData'),
                'aggs' => array('topIntent' => array('terms' => array('field' => 'apiResponseData.data.predictedTripletsList.attribute','size'=>20)))
                
            );
            //_p(json_encode($this->elasticQuery));die;
            $result = $this->clientCon->search($this->elasticQuery);
            //_p($result);die;
            foreach ($result['aggregations']['nestedQueryPath']['topIntent']['buckets'] as $key => $value) {
                $response[] = array('name' => $value['key'],'value' => $value['doc_count']);
            }
        }
        //_p($this->elasticQuery);die;
        return $response;
        //_p($response);die;
    }

    private function _responseSplits($inputRequest){
        //_p($inputRequest);die;
        $this->_getSearchServerConnection($inputRequest['metric']);
        $response = array();
        $this->elasticQuery = array();
        $elasticQueryLib = $this->CI->load->library("trackingMIS/elasticQueryLib");
        $this->elasticQuery = $elasticQueryLib->prepareQueryForResponse($inputRequest);
        //_p(json_encode($this->elasticQuery));die;
        //error_log("ELASTICSEARCHQUERY : RESPONSE Split :   ".json_encode($this->elasticQuery)."          ");
        if($inputRequest['donutChart']['fieldName'] == "device" && $inputRequest['misSource'] == "DOMESTIC"){
            $result = $this->_addAggerateToESQuery('split',"deviceType",1,'no','yes');
        }else{
            $result = $this->_addAggerateToESQuery('split',$inputRequest['donutChart']['fieldName'],1,'no','yes');
        }
        if($inputRequest['donutChart']['fieldName'] == 'is_response_paid'){
            foreach ($result as $key => $value) {
                if($value['name'] == '1'){
                    $result[$key]['name'] = 'Paid';
                }else{
                    $result[$key]['name'] = 'Free';
                }
            }
        }
        return $result;
    }

    public function MISSessionSplit($inputRequest){
        switch ($inputRequest['metric']) {
            case 'traffic':
                return $this->_trafficSessionSplit($inputRequest);
                break;

            case 'engagement':
                return $this->_engagementSessionSplit($inputRequest);
                break;

            case 'registration':
                return $this->_registrationSessionSplit($inputRequest);
                break;

            case 'response':
            case 'RMC':
                return $this->_responseSessionSplit($inputRequest);
                break;
            
            default:
                # code...
                break;
        }
    }

    private function _engagementSessionSplit($inputRequest){
        switch ($inputRequest['aspect']){
            case 'pageviews':
                $inputRequest['aspect'] = 'pageViews';
                $inputRequest['metric'] = 'traffic';
                $result = $this->_trafficSessionSplit($inputRequest);
                break;

            case 'pgpersess':   
            case 'avgsessdur':
            case 'bounce':
                if($inputRequest['aspect'] == 'avgsessdur'){
                    $fieldNameForAggs = 'duration';
                }else if($inputRequest['aspect'] == 'bounce'){
                    $fieldNameForAggs = 'bounce';
                }else if($inputRequest['aspect'] == 'pgpersess'){
                    $fieldNameForAggs = 'pageviews';
                }
                
                $this->elasticQuery = array();
                $elasticQueryLib = $this->CI->load->library("trackingMIS/elasticQueryLib");
                $result = $elasticQueryLib->prepareElasticSearchQuery($inputRequest,'session');
                $this->elasticQuery = $result['elasticQuery'];
                unset($result);

                $dateWiseFilter = $this->_addAggerateToQuery('source','','fieldAgg');
                $dateWiseFilter['aggPivot']['aggs'] = $this->_addAggerateToQuery($fieldNameForAggs,'','sumAgg');
                $this->elasticQuery['body']['aggs'] = $dateWiseFilter;
                //_p(json_encode($this->elasticQuery));die;
                //error_log("ELASTICSEARCHQUERY : ENGAGEMENT Session Split :   ".json_encode($this->elasticQuery)."          ");
                $search = $this->clientCon->search($this->elasticQuery);
                $resultData = $search['aggregations'][ 'aggPivot' ]['buckets'];
                $resultArray = $this->_getDefaultView($resultData,$inputRequest['aspect']);
                if($resultArray['defaultView']){
                    $splitInformation =$this->preprareUTMDataForMIS($inputRequest,$resultArray['defaultView']);
                    $result = array(
                        'session' => $resultArray['defaultView'],
                        'splitForBarGraph' => array(
                            'barGraphOptions' => $resultArray['trafficSourceArray'],
                            'splitInformation' => $splitInformation
                        )
                    );
                }else{
                    $result = array(
                        'session' => $resultArray['defaultView'],
                        'splitForBarGraph' => array(
                            'barGraphOptions' => null,
                            'splitInformation' => $splitInformation
                        )
                    );
                }
                break;

            case 'exit':
                //pageviews query
                $params = array();
                $this->elasticQuery = array();
                $elasticQueryLib = $this->CI->load->library("trackingMIS/elasticQueryLib");
                $result = $elasticQueryLib->prepareElasticSearchQuery($inputRequest,'pageview');
                $this->elasticQuery = $result['elasticQuery'];
                unset($result);

                $params = $this->elasticQuery;
                $pivotWiseFilter = $this->_addAggerateToQuery('source','','fieldAgg');
                $params['body']['aggs'] = $pivotWiseFilter;
                //_p(json_encode($params));
                //error_log("ELASTICSEARCHQUERY : ENGAGEMENT Session Split :   ".json_encode($params)."          ");
                $trafficSourceDataForPageviews = $this->clientCon->search($params);
                $trafficSourceDataForPageviews = $trafficSourceDataForPageviews['aggregations']['aggPivot']['buckets'];
                foreach ($trafficSourceDataForPageviews as $key => $value) {
                    $trafficSourceArrayForPageviews[$value['key']] = $value['doc_count'];
                }
                //_p($trafficSourceArrayForPageviews);

                //exit session query
                $this->elasticQuery = array();
                //$this->_prepareQueryForExitPage($inputRequest);
                $result = $elasticQueryLib->prepareElasticSearchQuery($inputRequest,'session','exitPage');
                $this->elasticQuery = $result['elasticQuery'];
                unset($result);

                $pivotWiseFilter = $this->_addAggerateToQuery('exitPage.source','','fieldAgg');
                $this->elasticQuery['body']['aggs'] = $pivotWiseFilter;
                //_p(json_encode($this->elasticQuery));die;
                //error_log("ELASTICSEARCHQUERY : ENGAGEMENT Session Split :   ".json_encode($this->elasticQuery)."          ");
                $trafficSourceData = $this->clientCon->search($this->elasticQuery);
                $trafficSourceData = $trafficSourceData['aggregations']['aggPivot']['buckets'];
                foreach ($trafficSourceData as $key => $value) {                
                    $trafficSourceArray[$value['key']] = $value['doc_count'];
                }
                //_p($trafficSourceArray);die;
                    
                foreach ($trafficSourceArray as $key => $value) {
                    if(!$key){
                        continue;
                    }
                    $exitRateForTrafficSource[$key] = number_format((($value*100)/$trafficSourceArrayForPageviews[$key]), 2, '.', '');
                }
                arsort($exitRateForTrafficSource);
                $resultArray =$this->_getDefaultViewForExitRate($exitRateForTrafficSource);
                if($resultArray['defaultView']){
                    $splitInformation =$this->preprareUTMDataForMIS($inputRequest,$resultArray['defaultView']);
                    $result = array(
                        'session' => $resultArray['defaultView'],
                        'splitForBarGraph' => array(
                            'barGraphOptions' => $resultArray['trafficSourceArray'],
                            'splitInformation' => $splitInformation
                        )
                    );
                }else{
                    $result = array(
                        'session' => $resultArray['defaultView'],
                        'splitForBarGraph' => array(
                            'barGraphOptions' => null,
                            'splitInformation' => $splitInformation
                        )
                    );
                }
                break;
            default:
                break;
        }
        //echo 'f'; _p($result);die;
        return $result;
    }

    private function _getDefaultViewForExitRate($resultData){
        $flag =0;
        foreach ($resultData as $key => $value) {
            if(!$key){
                continue;
            }
            if(intval($value)>0){
                $flag =1;
            }else{
                continue;
            }
        }
        if($flag ==0){
            return array('defaultView' => '','trafficSourceArray' =>'');
        }
        foreach ($resultData as $key => $value) {
            $trafficSourceArray[] = $key;  
        }
        if($trafficSourceArray){
            $defaultView = 'paid';
            $prioritySourceArray= array('paid','mailer','social','direct','seo','notsure');
            foreach ($prioritySourceArray as $key => $value) {
                if(in_array($value, $trafficSourceArray)){
                    $defaultView = $value;
                    break;
                }
            }
        }
        return array('defaultView' => $defaultView,'trafficSourceArray' =>$trafficSourceArray);
    }

    private function _getDefaultView($resultData,$aspect){
        $flag =0;
        foreach ($resultData as $key => $value) {
            if(!$value['key']){
                continue;
            }
            if($value['countPivot']['value'] >0){
                $flag =1;
            }
            if(intval($value['countPivot']['value']) <= 0){
                continue;
            }
            if($aspect == 'bounce'){
                $result[$value['key']] = number_format((($value['countPivot']['value']*100)/($value['doc_count'])), 2, '.', '');
            }else if($aspect == 'avgsessdur'){
                $result[$value['key']] = number_format((($value['countPivot']['value'])/($value['doc_count']*60)), 2, '.', '');
            }else if($aspect == 'pgpersess'){
                $result[$value['key']] = number_format((($value['countPivot']['value'])/($value['doc_count'])), 2, '.', '');
            }
        }
        if($flag ==0){
            return array('defaultView' => '','trafficSourceArray' =>'');
        }
        arsort($result);
        foreach ($result as $key => $value) {
            $trafficSourceArray[] = $key;  
        }
        if($trafficSourceArray){
            $defaultView = 'paid';
            $prioritySourceArray= array('paid','mailer','social','direct','seo','notsure');
            foreach ($prioritySourceArray as $key => $value) {
                if(in_array($value, $trafficSourceArray)){
                    $defaultView = $value;
                    break;
                }
            }
        }
        return array('defaultView' => $defaultView,'trafficSourceArray' =>$trafficSourceArray);
    }

    private function _trafficSessionSplit($inputRequest){
        //_p($inputRequest);die;
        if($inputRequest['aspect'] == 'pageViews'){
            $this->elasticQuery = array();
            $elasticQueryLib = $this->CI->load->library("trackingMIS/elasticQueryLib");
            $result = $elasticQueryLib->prepareElasticSearchQuery($inputRequest,'pageview');
            $this->elasticQuery = $result['elasticQuery'];

            $pivotFilter = array(
                'pivot' => array(
                    'terms' => array(
                        'field' => 'source',
                        'size' => ELASTIC_AGGS_SIZE
                    )
                )
            );
            $this->elasticQuery['body']['aggs'] = $pivotFilter;
            //_p(json_encode($this->elasticQuery));die;
            //error_log("ELASTICSEARCHQUERY : TRAFFIC SessionSplit :   ".json_encode($this->elasticQuery)."                     ");
            $queryResult = $this->clientCon->search($this->elasticQuery);
            //_p($queryResult['aggregations']['pivot']['buckets']);die;
            $sourceApplicationArray = array('yes','no');
            $actualCount = $queryResult['hits']['total'];
            $totalCount = 0;
            foreach($queryResult['aggregations']['pivot']['buckets'] as $key => $value){
                $totalCount += $value['doc_count'];
                if(in_array($value['key'], $sourceApplicationArray)){
                    $temp = ($value['key']=='no')?"Desktop":"Mobile";
                }else{
                    if(($inputRequest['donutChart']['fieldName'] == 'landingPageDoc.pageIdentifier')||($inputRequest['donutChart']['fieldName'] == 'pageIdentifier')){
                        $temp = $this->getPageName($value['key']);
                    }else{
                        $temp =$value['key'];    
                    }    
                }
                
                $resultset[] = array(
                    'name' => $temp,
                    'value' => $value['doc_count']
                );            
            }
        }else{
            $this->elasticQuery = array();
            $elasticQueryLib = $this->CI->load->library("trackingMIS/elasticQueryLib");
            $result = $elasticQueryLib->prepareElasticSearchQuery($inputRequest,'session');
            $this->elasticQuery = $result['elasticQuery'];
            
            $pivotFilter = array(
                'pivot' => array(
                    'terms' => array(
                        'field' => 'source',
                        'size' => ELASTIC_AGGS_SIZE
                    )
                )
            );
            if($inputRequest['aspect'] == 'Users') {
                $pivotFilter['pivot']['aggs'] = array(
                    'usersCount' => array(
                        'cardinality' => array(
                            'field' => 'visitorId'
                        )
                    )
                );
            }
            $this->elasticQuery['body']['aggs'] = $pivotFilter;
            //error_log("ELASTICSEARCHQUERY : TRAFFIC SessionSplit :   ".json_encode($this->elasticQuery)."                     ");
            //_p(json_encode($this->elasticQuery));die;
            $queryResult = $this->clientCon->search($this->elasticQuery);
            $actualCount = $queryResult['hits']['total'];
            $totalCount = 0;
            $sourceApplicationArray = array('yes','no');
            foreach($queryResult['aggregations']['pivot']['buckets'] as $key => $value){
                if(in_array($value['key'], $sourceApplicationArray)){
                    $temp = ($value['key']=='no')?"Desktop":"Mobile";
                }else{
                    if(($inputRequest['donutChart']['fieldName'] == 'landingPageDoc.pageIdentifier')||($inputRequest['donutChart']['fieldName'] == 'pageIdentifier')){
                        $temp = $this->getPageName($value['key']);
                    }else{
                        $temp =$value['key'];    
                    }    
                }
                
                if($inputRequest['aspect'] == 'Users'){
                    $resultset[] = array(
                        'name' => $temp,
                        'value' => $value['usersCount']['value']
                    );
                }else{
                    $resultset[] = array(
                        'name' => $temp,
                        'value' => $value['doc_count']
                    );
                    $totalCount += $value['doc_count'];
                }
            }
        }
        //_p($resultset);die;
        if($inputRequest['aspect'] != 'Users') {
            if($actualCount > $totalCount){
                $resultset[] = array('name' => 'Other','value'=> $actualCount - $count);
            }
        }
        
        foreach ($resultset as $key => $value) {
            if(($value['name'] == 'Other')||($value['name'] == '')){
                continue;
            }
            $trafficSourceArray[] = $value['name'];
        }
        
        if($trafficSourceArray){
            $defaultView = 'paid';
            $prioritySourceArray= array('paid','mailer','social','direct','seo','notsure');
            foreach ($prioritySourceArray as $key => $value) {
                if(in_array($value, $trafficSourceArray)){
                    $defaultView = $value;
                    break;
                }
            }
            $splitInformation =$this->preprareUTMDataForMIS($inputRequest,$defaultView);    
        }
        
        //_p($splitInformation);die;
        //_p($defaultView);die;
        $result = array(
                    'session' => $defaultView,
                    'splitForBarGraph' => array(
                        'barGraphOptions' => $trafficSourceArray,
                        'splitInformation' => $splitInformation
                    )
                );
        //_p($result);die;
        return $result;
    }

    private function _registrationSessionSplit($inputRequest){
        $this->elasticQuery = array();
        $elasticQueryLib = $this->CI->load->library("trackingMIS/elasticQueryLib");
        $this->elasticQuery = $elasticQueryLib->prepareQueryForRegistration($inputRequest);
        //_p(json_encode($this->elasticQuery));die;

        $result = $this->_addAggerateToESQuery('split','trafficSource');
        foreach ($result as $key => $value) {
            if($value['name'] == 'Other'){
                continue;
            }
            $trafficSourceArray[] = $value['name'];
        }

        $defaultView = 'paid';
        $prioritySourceArray= array('paid','mailer','social','direct','seo','notsure');
        foreach ($prioritySourceArray as $key => $value) {
            if(in_array($value, $trafficSourceArray)){
                $defaultView = $value;
                break;
            }
        }
        
        //_p($inputRequest['diffChartData']);die;
        $splitInformation =$this->preprareUTMDataForMIS($inputRequest,$defaultView);
        //_p($splitInformation);die;
        //_p($defaultView);die;
        $result = array(
                    'session' => $defaultView,
                    'splitForBarGraph' => array(
                        'barGraphOptions' => $trafficSourceArray,
                        'splitInformation' => $splitInformation
                    )
                );
        //_p($result);die;
        return $result;
    }

    private function _responseSessionSplit($inputRequest){
        $this->_getSearchServerConnection($inputRequest['metric']);
        //_p($inputRequest);die;
        $this->elasticQuery = array();
        $elasticQueryLib = $this->CI->load->library("trackingMIS/elasticQueryLib");
        $this->elasticQuery = $elasticQueryLib->prepareQueryForResponse($inputRequest);
        error_log("ELASTICSEARCHQUERY : RESPONSE session split :   ".json_encode($this->elasticQuery)."          ");
        $result = $this->_addAggerateToESQuery('split','response_source');
        foreach ($result as $key => $value) {
            if($value['name'] == 'Other'){
                continue;
            }
            $trafficSourceArray[] = $value['name'];
        }
        //_p($trafficSourceArray);die;
        $defaultView = 'paid';
        $prioritySourceArray= array('paid','mailer','social','direct','seo','notsure');
        foreach ($prioritySourceArray as $key => $value) {
            if(in_array($value, $trafficSourceArray)){
                $defaultView = $value;
                break;
            }
        }
        
        //_p($inputRequest['diffChartData']);die;
        $splitInformation =$this->preprareUTMDataForMIS($inputRequest,$defaultView);
        //_p($splitInformation);die;
        //_p($defaultView);die;
        $result = array(
                    'session' => $defaultView,
                    'splitForBarGraph' => array(
                        'barGraphOptions' => $trafficSourceArray,
                        'splitInformation' => $splitInformation
                    )
                );
        //_p($result);die;
        return $result;
    }

    public function preprareUTMDataForMIS($inputRequest,$defaultView='paid'){
        switch ($inputRequest['metric']) {
            case 'registration':
                return $this->_prepareUTMFilterForRegistration($inputRequest,$defaultView);
                break;

            case 'response':
            case 'RMC':
                return $this->_prepareUTMFilterForResponse($inputRequest,$defaultView);
                break;

            case 'engagement':
                switch ($inputRequest['aspect']) {
                    case 'pgpersess':
                    case 'bounce':
                    case 'avgsessdur':
                        if($inputRequest['aspect'] == 'avgsessdur'){
                            $fieldNameForAggs = 'duration';
                        }else if($inputRequest['aspect'] == 'bounce'){
                            $fieldNameForAggs = 'bounce';
                        }else if($inputRequest['aspect'] == 'pgpersess'){
                            $fieldNameForAggs = 'pageviews';
                        }

                        $this->elasticQuery = array();
                        $elasticQueryLib = $this->CI->load->library("trackingMIS/elasticQueryLib");
                        $inputRequest['utmSourceFilter'] = $defaultView;
                        $result = $elasticQueryLib->prepareElasticSearchQuery($inputRequest,'session');
                        unset($inputRequest['utmSourceFilter']);
                        $this->elasticQuery = $result['elasticQuery'];
                        unset($result);
                        $this->elasticQuery['body']['aggs']['sumPivot']['sum']['field']=$fieldNameForAggs;
                        //error_log("ELASTICSEARCHQUERY : UTM Split :   ".json_encode($this->elasticQuery)."                     ");
                        //_p(json_encode($this->elasticQuery));die;
                        $queryResult = $this->clientCon->search($this->elasticQuery);
                        unset($this->elasticQuery['body']['aggs']);
                        $actualSessionCount = $queryResult['hits']['total'];
                        $actualPivotCount = $queryResult['aggregations']['sumPivot']['value'];
                        //_p($actualSessionCount);_p($actualPivotCount);
                        foreach ($inputRequest['tabbedGraph'] as $data => $fieldArray) {
                            $resultset = array();
                            $result = array();
                            $pivotFilter = $this->_addAggerateToQuery($fieldArray['fieldName'],'','fieldAgg');
                            $pivotFilter['aggPivot']['aggs'] = $this->_addAggerateToQuery($fieldNameForAggs,'','sumAgg');
                            $this->elasticQuery['body']['aggs'] = $pivotFilter;
                            //error_log("ELASTICSEARCHQUERY : UTM Split  :   ".$fieldNameForAggs."  :  ".json_encode($this->elasticQuery)."                     ");
                            //_p(json_encode($this->elasticQuery));die;
                            $queryResult = $this->clientCon->search($this->elasticQuery);
                            $totalSessionCount =0;
                            $totalPivotCount =0;
                            //_p($queryResult['aggregations']['aggPivot']['buckets']);
                            foreach($queryResult['aggregations']['aggPivot']['buckets'] as $key => $value){
                                if(!$value['key']){
                                    continue;
                                }                                                                
                                if($inputRequest['aspect'] == 'bounce'){
                                    $resultset[$value['key']] = number_format((($value['countPivot']['value']*100)/$value['doc_count']), 2, '.', '');
                                }else if($inputRequest['aspect'] == 'avgsessdur'){
                                    $resultset[$value['key']] = number_format((($value['countPivot']['value'])/($value['doc_count']*60)), 2, '.', '');
                                }if($inputRequest['aspect'] == 'pgpersess'){
                                    $resultset[$value['key']] = number_format((($value['countPivot']['value'])/($value['doc_count'])), 2, '.', '');
                                }
                                
                                $totalSessionCount += $value['doc_count'];
                                $totalPivotCount += $value['countPivot']['value'];                                
                            }
                            if(($actualSessionCount != $totalSessionCount) || ($actualPivotCount != $totalPivotCount)){
                                $diffSessionCount = $actualSessionCount - $totalSessionCount;
                                if($diffSessionCount >0){
                                    if($inputRequest['aspect'] == 'bounce'){
                                        $resultset['others'] = number_format((($actualPivotCount - $totalPivotCount)*100)/$diffSessionCount, 2, '.', '');
                                    }else if($inputRequest['aspect'] == 'avgsessdur'){
                                        $resultset['others'] = number_format((($actualPivotCount - $totalPivotCount))/($diffSessionCount*60), 2, '.', '');
                                    }if($inputRequest['aspect'] == 'pgpersess'){
                                        $resultset['others'] = number_format((($actualPivotCount - $totalPivotCount))/$diffSessionCount, 2, '.', '');
                                    }
                                      
                                }  
                            }
                            arsort($resultset);
                            $flag =0;
                            foreach ($resultset as $key => $value) {
                                if(intval($value) >0){
                                    $flag =1;
                                }
                                $result[] = array(
                                        'name' => $key,
                                        'value' => $value
                                    );
                            }
                            if($flag == 0){
                                $result ='';
                            }
                            $splitInformation[$fieldArray['fieldName']] = $result;
                        }
                        return $splitInformation;

                        break;

                    case 'exit':
                        //pageviews query
                        $params = array();
                        $this->elasticQuery = array();
                        $elasticQueryLib = $this->CI->load->library("trackingMIS/elasticQueryLib");
                        $inputRequest['utmSourceFilter'] = $defaultView;
                        $result = $elasticQueryLib->prepareElasticSearchQuery($inputRequest,'pageview');
                        unset($inputRequest['utmSourceFilter']);
                        $this->elasticQuery = $result['elasticQuery'];
                        unset($result);
                        $params = $this->elasticQuery;
                        //_p(json_encode($this->elasticQuery));
                        //error_log("ELASTICSEARCHQUERY : ENGAGEMENT Session Split :   ".json_encode($params)."          ");
                        $queryResult = $this->clientCon->search($params);
                        $actualPageviewCount = $queryResult['hits']['total'];
                        //_p($actualPageviewCount);

                        //exit session query
                        $this->elasticQuery = array();
                        //$this->_prepareQueryForExitPage($inputRequest);
                        $elasticQueryLib = $this->CI->load->library("trackingMIS/elasticQueryLib");
                        $inputRequest['utmSourceFilter'] = $defaultView;
                        $result = $elasticQueryLib->prepareElasticSearchQuery($inputRequest,'session','exitPage');
                        unset($inputRequest['utmSourceFilter']);
                        $this->elasticQuery = $result['elasticQuery'];
                        unset($result);
                        //_p(json_encode($this->elasticQuery));die;
                        //error_log("ELASTICSEARCHQUERY : ENGAGEMENT Session Split :   ".json_encode($this->elasticQuery)."          ");
                        $queryResult = $this->clientCon->search($this->elasticQuery);
                        $actualSessionCount = $queryResult['hits']['total'];
                        //_p($actualSessionCount);die;
                        
                        $actualSessionCount = $queryResult['hits']['total'];
                        $actualPivotCount = $queryResult['aggregations']['sumPivot']['value'];
                        //_p($inputRequest['tabbedGraph']);die;
                        foreach ($inputRequest['tabbedGraph'] as $data => $fieldArray) {
                            $resultset = array();
                            $result = array();
                            $pageviewsUTMWise = array();
                            $exitSessionUTMWise = array();
                            //pageviews query
                            $pivotFilter = $this->_addAggerateToQuery($fieldArray['fieldName'],'','fieldAgg');
                            $params['body']['aggs'] = $pivotFilter;
                            //error_log("ELASTICSEARCHQUERY : ENGAGEMENT Session Split :   ".json_encode($params)."          ");
                            $queryResult = $this->clientCon->search($params);
                            $queryResult = $queryResult['aggregations']['aggPivot']['buckets'];
                            $totalPageviewsCount = 0;
                            foreach ($queryResult as $key => $value) {
                                if(!$value['key']){
                                    continue;
                                }
                                $pageviewsUTMWise[$value['key']] += $value['doc_count'];
                                $totalPageviewsCount += $value['doc_count'];
                            }
                            //_p($pageviewsUTMWise);

                            //exit session query
                            $pivotFilter = $this->_addAggerateToQuery('exitPage.'.$fieldArray['fieldName'],'','fieldAgg');
                            $this->elasticQuery['body']['aggs'] = $pivotFilter;
                            //error_log("ELASTICSEARCHQUERY : ENGAGEMENT Session Split :   ".json_encode($this->elasticQuery)."          ");                                                                
                            $queryResult = $this->clientCon->search($this->elasticQuery);
                            $queryResult = $queryResult['aggregations']['aggPivot']['buckets'];
                            foreach ($queryResult as $key => $value) {
                                if(!$value['key']){
                                    continue;
                                }
                                $exitSessionUTMWise[$value['key']] += $value['doc_count'];
                                $totalExitSessionCount += $value['doc_count'];
                            }
                            foreach($exitSessionUTMWise as $key => $value){
                                $resultset[$key] = number_format((($value*100)/$pageviewsUTMWise[$key]), 2, '.', '');                                                                                            
                            }

                            //_p($totalSessionCount);_p($totalPivotCount);die;
                            if(($actualSessionCount != $totalExitSessionCount) || ($actualPageviewCount != $totalPageviewsCount)){
                                $diffPageviewCount = $actualPageviewCount - $totalPageviewsCount;
                                if($diffPageviewCount >0){                                
                                    $resultset['others'] = number_format((($actualSessionCount - $totalExitSessionCount)*100)/$diffPageviewCount, 2, '.', '');                                                                          
                                }  
                            }
                            //_p($resultset);die;
                            arsort($resultset);
                            $flag =0;
                            foreach ($resultset as $key => $value) {
                                if(intval($value) >0){
                                    $flag =1;
                                }
                                $result[] = array(
                                        'name' => $key,
                                        'value' => $value
                                    );
                            }
                            if($flag == 0){
                                $result ='';
                            }
                            $splitInformation[$fieldArray['fieldName']] = $result;
                        }
                        return $splitInformation;                        
                        break;
                    
                    default:
                        # code...
                        break;
                }
                break;

            case 'traffic':
                if($inputRequest['aspect'] == 'pageViews'){
                    $this->elasticQuery = array();
                    $elasticQueryLib = $this->CI->load->library("trackingMIS/elasticQueryLib");
                    $inputRequest['utmSourceFilter'] = $defaultView;
                    $result = $elasticQueryLib->prepareElasticSearchQuery($inputRequest,'pageview');
                    $this->elasticQuery = $result['elasticQuery'];
                    unset($inputRequest['utmSourceFilter']);
                    
                    foreach ($inputRequest['tabbedGraph'] as $data => $fieldArray) {
                        //_p($fieldArray);
                        $result = array();
                        $resultset = array();
                        $pivotFilter = array(
                            'pivot' => array(
                                'terms' => array(
                                    'field' => $fieldArray['fieldName'],
                                    'size' => ELASTIC_AGGS_SIZE
                                )
                            )
                        );                        
                        $this->elasticQuery['body']['aggs'] = $pivotFilter;
                        //_p(json_encode($this->elasticQuery));die;
                        //error_log("ELASTICSEARCHQUERY : TRAFFIC ".$fieldArray['fieldName']." Split :   ".json_encode($this->elasticQuery)."                     ");
                        $queryResult = $this->clientCon->search($this->elasticQuery);
                        //_p($queryResult);die;
                        $actualCount = $queryResult['hits']['total'];
                        $totalCount = 0;
                        $sourceApplicationArray = array('yes','no');
                        foreach($queryResult['aggregations']['pivot']['buckets'] as $key => $value){
                            if(!$value['key']){
                                continue;
                            }
                            
                            $temp =$value['key'];    
                            if($inputRequest['aspect'] == 'Users'){
                                $resultset[$temp] = $value['usersCount']['value'];                                
                            }else{
                                $resultset[$temp] = $value['doc_count'];
                                $totalCount += $value['doc_count'];
                            }
                        }
                        
                        if($inputRequest['aspect'] != 'Users') {
                            if($actualCount > $totalCount){
                                $resultset['Other'] = $actualCount - $totalCount;
                            }
                        }
                        arsort($resultset);
                        foreach ($resultset as $key => $value) {
                            $result[] = array(
                                    'name' => $key,
                                    'value' => $value
                                );
                        }
                        $splitInformation[$fieldArray['fieldName']] = $result;
                        //_p($splitInformation);
                    }
                }else{
                    $this->elasticQuery = array();
                    $elasticQueryLib = $this->CI->load->library("trackingMIS/elasticQueryLib");
                    $inputRequest['utmSourceFilter'] = $defaultView;
                    $result = $elasticQueryLib->prepareElasticSearchQuery($inputRequest,'session');
                    $this->elasticQuery = $result['elasticQuery'];
                    unset($inputRequest['utmSourceFilter']);
                    
                    foreach ($inputRequest['tabbedGraph'] as $data => $fieldArray) {
                        $resultset = array();
                        $result =array();
                        $pivotFilter = array(
                            'pivot' => array(
                                'terms' => array(
                                    'field' => $fieldArray['fieldName'],
                                    'size' => ELASTIC_AGGS_SIZE
                                )
                            )
                        );
                        if($inputRequest['aspect'] == 'Users') {
                            $pivotFilter['pivot']['aggs'] = array(
                                'usersCount' => array(
                                    'cardinality' => array(
                                        'field' => 'visitorId'
                                    )
                                )
                            );
                        }
                        $this->elasticQuery['body']['aggs'] = $pivotFilter;
                        //error_log("ELASTICSEARCHQUERY : TRAFFIC ".$fieldArray['fieldName']." Split :   ".json_encode($this->elasticQuery)."                     ");
                        //_p(json_encode($this->elasticQuery));die;
                        $queryResult = $this->clientCon->search($this->elasticQuery);
                        //_p($queryResult);die;
                        if($inputRequest['aspect'] == 'Users'){
                            unset($this->elasticQuery['body']['aggs']);
                            $this->elasticQuery['body']['aggs'] = array(
                                'usersCount' => array(
                                    'cardinality' => array(
                                        'field' => 'visitorId'
                                    )
                                )
                            );
                            //_p(json_encode($this->elasticQuery));die;
                            $querySet = $this->clientCon->search($this->elasticQuery);
                            //error_log("ELASTICSEARCHQUERY : TRAFFIC Inside ".$fieldArray['fieldName']." Split :   ".json_encode($this->elasticQuery)."                     ");
                            //_p($queryResult);
                            $actualCount = $querySet['aggregations']['usersCount']['value'];
                            //_p($actualCount);die;

                        }else{
                            $actualCount = $queryResult['hits']['total'];
                        }
                        
                        $totalCount = 0;
                        $sourceApplicationArray = array('yes','no');
                        foreach($queryResult['aggregations']['pivot']['buckets'] as $key => $value){
                            if(!$value['key']){
                                continue;
                            }

                            $temp =$value['key'];    
                            if($inputRequest['aspect'] == 'Users'){
                                $resultset[$temp] = $value['usersCount']['value'];
                                $totalCount += $value['usersCount']['value'];
                            }else{
                                $resultset[$temp] = $value['doc_count'];
                                $totalCount += $value['doc_count'];
                            }
                        }                                            
                        //_p($totalCount);_p($actualCount);die;
                        if($actualCount > $totalCount){
                            $resultset['Other'] = $actualCount - $totalCount;
                        }
                        arsort($resultset);
                        foreach ($resultset as $key => $value) {
                            $result[] = array(
                                    'name' => htmlentities($key),
                                    'value' => $value
                                );
                        }
                        //_p($result);die;
                        $splitInformation[$fieldArray['fieldName']] = $result;                                                
                    }
                }
                //_p($splitInformation);die;
                return $splitInformation;
                break;
            
            default:
                # code...
                break;
        }
    }

    private function _prepareUTMFilterForResponse($inputRequest,$defaultView='paid'){
        $this->_getSearchServerConnection($inputRequest['metric']);
        $this->elasticQuery = array();
        $elasticQueryLib = $this->CI->load->library("trackingMIS/elasticQueryLib");
        $inputRequest['utmSourceFilter'] = $defaultView;
        $this->elasticQuery = $elasticQueryLib->prepareQueryForResponse($inputRequest);
        unset($inputRequest['utmSourceFilter']);        
        //_p(json_encode($this->elasticQuery));die;  
        //error_log("ELASTICSEARCHQUERY : RESPONSE UTM :   ".json_encode($this->elasticQuery)."          ");
        foreach ($inputRequest['tabbedGraph'] as $key => $value) {
            $splitInformation[$value['fieldName']] = $this->_addAggerateToESQuery('split',$value['fieldName'],1,'no','yes');
        }
        //_p($splitInformation);die;
        return $splitInformation;
    }

    private function _prepareUTMFilterForRegistration($inputRequest,$defaultView='paid'){
        $this->elasticQuery = array();
        $elasticQueryLib = $this->CI->load->library("trackingMIS/elasticQueryLib");
        $inputRequest['utmSourceFilter'] = $defaultView;
        $this->elasticQuery = $elasticQueryLib->prepareQueryForRegistration($inputRequest);
        unset($inputRequest['utmSourceFilter']);
        foreach ($inputRequest['tabbedGraph'] as $key => $value) {
            $splitInformation[$value['fieldName']] = $this->_addAggerateToESQuery('split',$value['fieldName'],1,'no','yes');
        }
        return $splitInformation;
    }

    public function insertZeroValuesForLineChart($inputArray, $dateRange, $view){
        //_p($view);_p($dateRange);_p($inputArray);die;
        $lineArray =array();
        $increment = 0;
        switch ($view) {
              case 1:
              case 'day':
                    $startDate = $dateRange['startDate'];     
                    $increment = ' +1 day';
                    break;

              case 2:
              case 'week':
                    $dotw = date('w', strtotime($dateRange['startDate']));
                    if($dotw >1){
                        $dotw -=1;
                    }else if($dotw ==0){
                        $dotw += 6;
                    }else{
                        $dotw =0;
                    }
                    $start = strtotime($dateRange['startDate']) - ($dotw * 24*60*60);
                    $startDate = date("Y-m-d",$start);
                    $increment = ' +1 week';
                    break;

              case 3:
              case 'month':
                    $gendate = new DateTime();
                    $startYear = date('Y', strtotime($dateRange['startDate']));
                    $monthNo = date('m', strtotime($dateRange['startDate']));
                    $startDate = $startYear.'-'.$monthNo.'-'.'01';
                    $increment = ' +1 month';
                    break;

              default:
                  $increment = ' +1 day'; // Find some better fallback
                  break;
        }
        $lineArray[$dateRange['startDate']] = 0;
        for($starting = $startDate; strtotime($starting) <=strtotime($dateRange['endDate']); $starting = date('Y-m-d', strtotime($starting . $increment))){
            $valueCount = $inputArray[$starting]?$inputArray[$starting]:0;
            $lineArray[$starting] = $valueCount;
        }
        if($startDate != $dateRange['startDate']){
              $lineArray[$dateRange['startDate']] = $lineArray[$startDate];//$dateRange['startDate']] = $lineArray[$startDate];
              unset($lineArray[$startDate]);
        }
        foreach ($lineArray as $key => $value) {
            $lineChartFinal[] = array($key,$value);
        }
        return $lineChartFinal;
    }

    public function MISTable($inputRequest){
        switch ($inputRequest['metric']) {
            case 'traffic':
                return $this->_trafficTable($inputRequest);
                break;

            case 'engagement':
                return $this->_engagementTable($inputRequest);
                break;

            case 'registration':
                return $this->_registrationTable($inputRequest);
                break;

            case 'response':
            case 'RMC':
                return $this->_responseTable($inputRequest);
                break;

            case 'exam_upload':
                if($inputRequest['misSource'] == 'STUDY ABROAD'){
                    return $this->_studyAbroadExamUploadTable($inputRequest);
                }
                break;
            
            default:
                # code...
                break;
        }
    }

    private  function _studyAbroadExamUploadTable($inputRequest)
    {
        $this->samismodel = $this->CI->load->model('samismodel');
        $resultset = $this->samismodel->getCountForAbroadExamUploadFieldWise($inputRequest,'sourceWidget');
        $resultantData = array();
        if(!empty($resultset))
        {
            $totalCount = 0;
            foreach ($resultset as $data)
            {
                $totalCount += $data['count'];
            }
            reset($resultantData);
            foreach ($resultset as $data)
            {
                $resultantData[] = array(
                    'widget' => $data['widget'],
                    'sourceApplication' =>$data['source'],
                    'count' => $data['count'],
                    'percentage'=>number_format($data['count']/$totalCount * 100, 2)
                );
            }

        }
        return $resultantData;
    }

    private function _engagementTable($inputRequest){
        switch ($inputRequest['aspect']){
            case 'pageviews':
                $inputRequest['aspect'] = 'pageViews';
                $inputRequest['metric'] = 'traffic';
                $result = $this->_trafficTable($inputRequest);
                foreach ($result as $key => $value) {
                    unset($result[$key]['percentage']);
                }
                
                return $result;
                //_p($result);die;
                break;

            case 'pgpersess':   
            case 'avgsessdur':
            case 'bounce':
                if(!($inputRequest['misSource'] == 'STUDY ABROAD' && $inputRequest['pageName'])){
                    if($inputRequest['aspect'] == 'avgsessdur'){
                        $fieldNameForAggs = 'duration';
                    }else if($inputRequest['aspect'] == 'bounce'){
                        $fieldNameForAggs = 'bounce';
                    }else if($inputRequest['aspect'] == 'pgpersess'){
                        $fieldNameForAggs = 'pageviews';
                    }
                    
                    $this->elasticQuery = array();
                    $elasticQueryLib = $this->CI->load->library("trackingMIS/elasticQueryLib");
                    $result = $elasticQueryLib->prepareElasticSearchQuery($inputRequest,'session');
                    $this->elasticQuery = $result['elasticQuery'];
                    unset($result);

                    $previousField;
                    $data = &$this->elasticQuery['body'];
                    //_p($inputRequest['dataTable']);die;
                    foreach ($inputRequest['dataTable'][0] as $key => $value) {
                        $value['field'] = str_replace('pageIdentifier', 'landingPageDoc.pageIdentifier',$value['field']);
                        if($previousField){
                            $data[$previousField]['aggs'] = $this->_addAggerateToESQuery('dataTableAggs',$value['field']);
                            $data = &$data[$previousField]['aggs'];
                            $previousField = $value['field'];
                        }else{
                            $data['aggs'] = $this->_addAggerateToESQuery('dataTableAggs',$value['field']);
                            $data = &$data['aggs'];
                            $previousField = $value['field'];
                        }
                    }
                    
                    $data[$previousField]['aggs'] = $this->_addAggerateToQuery($fieldNameForAggs,'','sumAgg');
                    //error_log("ELASTICSEARCHQUERY : ENGAGEMENT Data table :   ".json_encode($this->elasticQuery)."                     ");
                    $search = $this->clientCon->search($this->elasticQuery);
                    $result = $search['aggregations']['landingPageDoc.pageIdentifier']['buckets'];
                    unset($this->elasticQuery['body']['aggs']);
                    $this->elasticQuery['body']['aggs'] = $this->_addAggerateToQuery($fieldNameForAggs,'','sumAgg');
                    //_p(json_encode($this->elasticQuery));die;
                    //error_log("ELASTICSEARCHQUERY : ENGAGEMENT Data table :   ".json_encode($this->elasticQuery)."                     ");
                    $search = $this->clientCon->search($this->elasticQuery);
                    $actualCountData = $search['aggregations']['countPivot']['value'];
                    return $this->_prepareDataForDataTableForEngagement($result,$inputRequest['pageName'],$inputRequest['aspect'],$actualCountData);
                }else{
                    return null;
                }
                break;

            case 'exit':
                if($inputRequest['misSource'] == 'DOMESTIC'){
                    //pageviews query
                    $params = array();
                    $this->elasticQuery = array();
                    $elasticQueryLib = $this->CI->load->library("trackingMIS/elasticQueryLib");
                    $result = $elasticQueryLib->prepareElasticSearchQuery($inputRequest,'pageview');
                    $this->elasticQuery = $result['elasticQuery'];
                    unset($result);

                    $previousField;
                    $data = &$this->elasticQuery['body'];
                    foreach ($inputRequest['dataTable'][0] as $key => $value) {
                        //$value['field'] = str_replace('pageIdentifier', 'landingPageDoc.pageIdentifier',$value['field']);
                        if($previousField){
                            $data[$previousField]['aggs'] = $this->_addAggerateToESQuery('dataTableAggs',$value['field']);
                            $data = &$data[$previousField]['aggs'];
                            $previousField = $value['field'];
                        }else{
                            $data['aggs'] = $this->_addAggerateToESQuery('dataTableAggs',$value['field']);
                            $data = &$data['aggs'];
                            $previousField = $value['field'];
                        }
                    }
                    $params = $this->elasticQuery;
                    unset($this->elasticQuery);
                    //error_log("ELASTICSEARCHQUERY : ENGAGEMENT Data table :   ".json_encode($params)."                     ");
                    $queryResult = $this->clientCon->search($params);
                    $result = $queryResult['aggregations']['pageIdentifier']['buckets'];
                    $countRow =0;
                    foreach ($result as $key => $pageArray){
                        $sourceWise = $pageArray['source']['buckets'];
                        foreach ($sourceWise as $key => $sourceArray) 
                        {
                            $siteSource = $sourceArray['isMobile']['buckets'];
                            foreach ($siteSource as $keyOne => $siteSourceArray) {
                                $trafficCount = $siteSourceArray['doc_count'];
                                if($trafficCount <= 0){
                                    continue;
                                }
                                $countRow++;
                                if($countRow > 200){
                                    break;
                                }
                                $page = $pageArray['key'];
                                $page = $this->getPageName($page);                        
                                $siteSource = ($siteSourceArray['key']=='no')?"Desktop":"Mobile";
                                $resultSetForPageviews[$page][$sourceArray['key']][ucfirst($siteSource)] = $trafficCount;                            
                            }
                        }
                    }
                    //_p($resultSet);die;

                    //exit session query
                    $this->elasticQuery = array();
                    $result = $elasticQueryLib->prepareElasticSearchQuery($inputRequest,'session','exitPage');
                    $this->elasticQuery = $result['elasticQuery'];
                    unset($result);

                    $previousField='';
                    $data = &$this->elasticQuery['body'];
                    foreach ($inputRequest['dataTable'][0] as $key => $value){
                        $value['field'] = 'exitPage.'.$value['field'];
                        if($previousField){
                            $data[$previousField]['aggs'] = $this->_addAggerateToESQuery('dataTableAggs',$value['field']);
                            $data = &$data[$previousField]['aggs'];
                            $previousField = $value['field'];
                        }else{
                            $data['aggs'] = $this->_addAggerateToESQuery('dataTableAggs',$value['field']);
                            $data = &$data['aggs'];
                            $previousField = $value['field'];
                        }
                    }
                    //error_log("ELASTICSEARCHQUERY : ENGAGEMENT Data table :   ".json_encode($this->elasticQuery)."                     ");
                    $queryResult = $this->clientCon->search($this->elasticQuery);
                    $result = $queryResult['aggregations']['exitPage.pageIdentifier']['buckets'];
                    $countRow =0;
                    foreach ($result as $key => $pageArray){
                        $sourceWise = $pageArray['exitPage.source']['buckets'];
                        foreach ($sourceWise as $key => $sourceArray) 
                        {
                            $siteSource = $sourceArray['exitPage.isMobile']['buckets'];
                            foreach ($siteSource as $keyOne => $siteSourceArray) {
                                $trafficCount = $siteSourceArray['doc_count'];
                                if($trafficCount <= 0){
                                    continue;
                                }
                                $countRow++;
                                if($countRow > 200){
                                    break;
                                }
                                $page = $pageArray['key'];
                                $page = $this->getPageName($page);
                                $siteSource = ($siteSourceArray['key']=='no')?"Desktop":"Mobile";
                                $resultSetForExitSession[$page][$sourceArray['key']][ucfirst($siteSource)] = $trafficCount;                            
                            }
                        }
                    }
                    //_p($resultSetForExitSession);die;
                    foreach ($resultSetForExitSession as $page => $trafficSourceArray) {
                        foreach ($trafficSourceArray as $trafficSource => $sourceApplicationArray) {
                            foreach ($sourceApplicationArray as $sourceApplication => $count) {                        
                                $pageviewCount = $resultSetForPageviews[$page][$trafficSource][$sourceApplication];
                                if($pageviewCount > 0){
                                    $resultDataSet[] = array(
                                        'count' => number_format((($count*100)/$pageviewCount), 2, '.', ''),
                                        'pageName' => $page,
                                        'trafficSource' => $trafficSource,
                                        'sourceApplication' => $sourceApplication
                                    );                        
                                }
                            }
                        }
                    }
                    arsort($resultDataSet);
                    foreach ($resultDataSet as $key => $value) {
                        $dataTableData[] = array(
                                    'pageName' => $value['pageName'],
                                    'trafficSource' => ucfirst($value['trafficSource']),
                                    'sourceApplication' => $value['sourceApplication'],
                                    'count' => $value['count']                        
                                    );
                        unset($resultDataSet[$key]);
                    }
                }else{
                    $dataTableData = '';
                }
                    return $dataTableData;
                break;
        }
    }

    private function _trafficTable($inputRequest){
        //_p($inputRequest);die;
        if($inputRequest['aspect'] == 'pageViews'){
            $this->elasticQuery = array();
            $elasticQueryLib = $this->CI->load->library("trackingMIS/elasticQueryLib");
            $result = $elasticQueryLib->prepareElasticSearchQuery($inputRequest,'pageview');
            $this->elasticQuery = $result['elasticQuery'];

            if($inputRequest['pageName']){
                if($inputRequest['misSource'] == 'DOMESTIC'){                    
                    $previousField;
                    $data = &$this->elasticQuery['body'];
                    //_p($inputRequest['dataTable'][0]);die;
                    foreach ($inputRequest['dataTable'][0] as $key => $value) {                            
                        $inputRequest['dataTable'][0][$key]['field'] = str_replace('landingPageDoc.pageIdentifier', 'pageIdentifier', $value['field']);
                    }

                    foreach ($inputRequest['dataTable'][0] as $key => $value) {
                        if($previousField){
                            $data[$previousField]['aggs'] = $this->_addAggerateToESQuery('dataTableAggs',$value['field']);
                            $data = &$data[$previousField]['aggs'];
                            $previousField = $value['field'];
                        }else{
                            $data['aggs'] = $this->_addAggerateToESQuery('dataTableAggs',$value['field']);
                            $data = &$data['aggs'];
                            $previousField = $value['field'];
                        }
                    }
                    //_p(json_encode($this->elasticQuery));die;
                    //error_log("ELASTICSEARCHQUERY : TRAFFIC DataTable :   ".json_encode($this->elasticQuery)."                     ");
                    $search = $this->clientCon->search($this->elasticQuery);
                    $actualCountData = $search['hits']['total'];
                    $result = $search['aggregations']['pageIdentifier']['buckets'];
                    return $this->_prepareDataForDataTableForTraffic($result,$inputRequest['pageName'],$inputRequest['aspect'],$actualCountData); 
                }else{
                    return '';
                }
            }else{
                $previousField;
                $data = &$this->elasticQuery['body'];
                //_p($inputRequest['dataTable'][0]);die;
                foreach ($inputRequest['dataTable'][0] as $key => $value) {                            
                    $inputRequest['dataTable'][0][$key]['field'] = str_replace('landingPageDoc.pageIdentifier', 'pageIdentifier', $value['field']);                    
                }

                foreach ($inputRequest['dataTable'][0] as $key => $value) {
                    if($previousField){
                        $data[$previousField]['aggs'] = $this->_addAggerateToESQuery('dataTableAggs',$value['field']);
                        $data = &$data[$previousField]['aggs'];
                        $previousField = $value['field'];
                    }else{
                        $data['aggs'] = $this->_addAggerateToESQuery('dataTableAggs',$value['field']);
                        $data = &$data['aggs'];
                        $previousField = $value['field'];
                    }
                }
                //_p(json_encode($this->elasticQuery));die;
                //error_log("ELASTICSEARCHQUERY : TRAFFIC DataTable :   ".json_encode($this->elasticQuery)."             ");
                $search = $this->clientCon->search($this->elasticQuery);
                $actualCountData = $search['hits']['total'];
                $result = $search['aggregations']['pageIdentifier']['buckets'];
                return $this->_prepareDataForDataTableForTraffic($result,$inputRequest['pageName'],$inputRequest['aspect'],$actualCountData); 
            }
        }else{
            if($inputRequest['pageName']){
                if($inputRequest['misSource'] == 'STUDY ABROAD'){
                    $pageArray = array('homePage','shortlistPage','searchPage','stagePage','recommendationPage','compareCoursesPage');
                    if(!(in_array($pageName, $pageArray)))
                    {
                        $this->elasticQuery = array();
                        $elasticQueryLib = $this->CI->load->library("trackingMIS/elasticQueryLib");
                        $result = $elasticQueryLib->prepareElasticSearchQuery($inputRequest,'session');
                        $this->elasticQuery = $result['elasticQuery'];
                        
                        $previousField;
                        $data = &$this->elasticQuery['body'];

                        foreach ($inputRequest['dataTable'][0] as $key => $value) {                            
                            if($inputRequest['pageName'] == 'categoryPage'){
                                $inputRequest['dataTable'][0][$key]['field'] = str_replace('landingPageDoc.pageEntityId', 'landingPageDoc.pageURL', $value['field']);
                            }
                        }

                        foreach ($inputRequest['dataTable'][0] as $key => $value) {
                            if($previousField){
                                $data[$previousField]['aggs'] = $this->_addAggerateToESQuery('dataTableAggs',$value['field']);
                                $data = &$data[$previousField]['aggs'];
                                $previousField = $value['field'];
                            }else{
                                $data['aggs'] = $this->_addAggerateToESQuery('dataTableAggs',$value['field']);
                                $data = &$data['aggs'];
                                $previousField = $value['field'];
                            }
                        }

                        if($inputRequest['aspect'] == 'Users') {
                            $data[$previousField]['aggs'] = array(
                                'usersCount' => array(
                                    'cardinality' => array(
                                        'field' => 'visitorId'
                                    )
                                )
                            );
                        }
                        //_p(json_encode($this->elasticQuery));die;
                        //error_log("ELASTICSEARCHQUERY : TRAFFIC DataTable :   ".json_encode($this->elasticQuery)."                     ");
                        $search = $this->clientCon->search($this->elasticQuery);
                        if($inputRequest['pageName'] == 'categoryPage'){
                            $result = $search['aggregations']['landingPageDoc.pageURL']['buckets'];
                        }else{
                            $result = $search['aggregations']['landingPageDoc.pageEntityId']['buckets'];
                        }
                        if($inputRequest['aspect'] == 'Users') {
                            unset($this->elasticQuery['body']['aggs']);
                            $this->elasticQuery['body']['aggs'] = array(
                                'usersCount' => array(
                                    'cardinality' => array(
                                        'field' => 'visitorId'
                                    )
                                )
                            );
                            $search = $this->clientCon->search($this->elasticQuery);
                            $actualCountData = $search['aggregations']['usersCount']['value'];
                        }else{
                            $actualCountData = $search['hits']['total'];
                        }
                        return $this->_prepareDataForDataTableForTraffic($result,$inputRequest['pageName'],$inputRequest['aspect'],$actualCountData);
                    }else{
                        return '';
                    }
                }else{
                        $this->elasticQuery = array();
                        $elasticQueryLib = $this->CI->load->library("trackingMIS/elasticQueryLib");
                        $result = $elasticQueryLib->prepareElasticSearchQuery($inputRequest,'session');
                        $this->elasticQuery = $result['elasticQuery'];
                        unset($result);
                        $previousField;
                        $data = &$this->elasticQuery['body'];                        
                        //_p($inputRequest['dataTable'][0]);die;
                        foreach ($inputRequest['dataTable'][0] as $key => $value) {
                            if($previousField){
                                $data[$previousField]['aggs'] = $this->_addAggerateToESQuery('dataTableAggs',$value['field']);
                                $data = &$data[$previousField]['aggs'];
                                $previousField = $value['field'];
                            }else{
                                $data['aggs'] = $this->_addAggerateToESQuery('dataTableAggs',$value['field']);
                                $data = &$data['aggs'];
                                $previousField = $value['field'];
                            }
                        }

                        if($inputRequest['aspect'] == 'Users') {
                            $data[$previousField]['aggs'] = array(
                                'usersCount' => array(
                                    'cardinality' => array(
                                        'field' => 'visitorId'
                                    )
                                )
                            );
                        }
                        //_p(json_encode($this->elasticQuery));die;
                        //error_log("ELASTICSEARCHQUERY : TRAFFIC DataTable :   ".json_encode($this->elasticQuery)."                     ");
                        $search = $this->clientCon->search($this->elasticQuery);
                        if($inputRequest['pageName'] == 'categoryPage'){
                            $result = $search['aggregations']['landingPageDoc.pageURL']['buckets'];
                        }else{
                            $result = $search['aggregations']['landingPageDoc.pageEntityId']['buckets'];
                        }
                        if($inputRequest['aspect'] == 'Users') {
                            unset($this->elasticQuery['body']['aggs']);
                            $this->elasticQuery['body']['aggs'] = array(
                                'usersCount' => array(
                                    'cardinality' => array(
                                        'field' => 'visitorId'
                                    )
                                )
                            );
                            //error_log("ELASTICSEARCHQUERY : TRAFFIC DataTable Inside:   ".json_encode($this->elasticQuery)."                     ");
                            $search = $this->clientCon->search($this->elasticQuery);
                            $actualCountData = $search['aggregations']['usersCount']['value'];
                        }else{
                            $actualCountData = $search['hits']['total'];
                        }
                        
                        return $this->_prepareDataForDataTableForTraffic($result,$inputRequest['pageName'],$inputRequest['aspect'],$actualCountData);
                }
            }else{
                $this->elasticQuery = array();
                $elasticQueryLib = $this->CI->load->library("trackingMIS/elasticQueryLib");
                $result = $elasticQueryLib->prepareElasticSearchQuery($inputRequest,'session');
                $this->elasticQuery = $result['elasticQuery'];
                
                $previousField;
                $data = &$this->elasticQuery['body'];
                foreach ($inputRequest['dataTable'][0] as $key => $value) {
                    if($previousField){
                        $data[$previousField]['aggs'] = $this->_addAggerateToESQuery('dataTableAggs',$value['field']);
                        $data = &$data[$previousField]['aggs'];
                        $previousField = $value['field'];
                    }else{
                        $data['aggs'] = $this->_addAggerateToESQuery('dataTableAggs',$value['field']);
                        $data = &$data['aggs'];
                        $previousField = $value['field'];
                    }
                }

                if($inputRequest['aspect'] == 'Users') {
                    $data[$previousField]['aggs'] = array(
                        'usersCount' => array(
                            'cardinality' => array(
                                'field' => 'visitorId'
                            )
                        )
                    );
                }

                //error_log("ELASTICSEARCHQUERY : TRAFFIC DataTable :   ".json_encode($this->elasticQuery)."                     ");
                $search = $this->clientCon->search($this->elasticQuery);
                $result = $search['aggregations']['landingPageDoc.pageIdentifier']['buckets'];
                if($inputRequest['aspect'] == 'Users') {
                    unset($this->elasticQuery['body']['aggs']);
                    $this->elasticQuery['body']['aggs'] = array(
                        'usersCount' => array(
                            'cardinality' => array(
                                'field' => 'visitorId'
                            )
                        )
                    );

                    //error_log("ELASTICSEARCHQUERY : TRAFFIC Inside DataTable :   ".json_encode($this->elasticQuery)."                     ");
                    $search = $this->clientCon->search($this->elasticQuery);
                    $actualCountData = $search['aggregations']['usersCount']['value'];
                    //_p($actualCountData);die;
                }else{
                    $actualCountData = $search['hits']['total'];
                }
                return $this->_prepareDataForDataTableForTraffic($result,$inputRequest['pageName'],$inputRequest['aspect'],$actualCountData);
            }
        }
    }

    private function _prepareDataForDataTableForEngagement($result,$pageName,$aspect,$actualCountData){
        $countRow =0;
        foreach ($result as $key => $pageArray){
            $sourceWise = $pageArray['source']['buckets'];
            foreach ($sourceWise as $key => $sourceArray) 
            {
                $siteSource = $sourceArray['isMobile']['buckets'];
                foreach ($siteSource as $keyOne => $siteSourceArray) {
                    $countRow++;
                    if($countRow > 200){
                        break;
                    }
                    $page = $pageArray['key'];
                    $page = $this->getPageName($page);

                    $trafficCount = $siteSourceArray['usersCount']['value'];
                    
                    if($aspect == 'avgsessdur'){
                        $hourFormat = explode('.',number_format((($siteSourceArray['countPivot']['value'])/($siteSourceArray['doc_count'])), 2, '.', ''));
                        $trafficCount =date('i:s', mktime(0, 0, $hourFormat[0]));
                        //$trafficCount = number_format((($siteSourceArray['countPivot']['value'])/($siteSourceArray['doc_count']*60)), 2, '.', '');
                    }else if($aspect == 'bounce'){
                        $trafficCount = number_format((($siteSourceArray['countPivot']['value']*100)/$siteSourceArray['doc_count']), 2, '.', '');
                    }else if($aspect == 'pgpersess'){
                        $trafficCount = number_format((($siteSourceArray['countPivot']['value'])/($siteSourceArray['doc_count'])), 2, '.', '');
                    }

                    $resultSet[] = array(
                        'count' => $trafficCount,
                        'pageName' => $page,
                        'trafficSource' => $sourceArray['key'],
                        'sourceApplication' => ($siteSourceArray['key']=='no')?"Desktop":"Mobile"
                        );                        
                }
            }
        }            
        arsort($resultSet);
        foreach ($resultSet as $key => $value) {
            $dataTableData[] = array(
                        'pageName' => $value['pageName'],
                        'trafficSource' => ucfirst($value['trafficSource']),
                        'sourceApplication' => $value['sourceApplication'],
                        'count' => $value['count']                        
                        );
            unset($resultSet[$key]);
        }
        return $dataTableData;
        //_p($dataTableData);die;
    }

    private function _prepareDataForDataTableForTraffic($result,$pageName,$aspect,$actualCountData)
    {
        if($pageName)
        {
            $countRow =0;
            foreach ($result as $key => $course){
                $sourceWise = $course['source']['buckets'];
                foreach ($sourceWise as $key => $sourceArray) 
                {
                    $siteSource = $sourceArray['isMobile']['buckets'];
                    foreach ($siteSource as $keyOne => $siteSourceArray) {
                        $countRow++;
                        if($countRow > 200){
                            break;
                        }
                        if($aspect != "Users"){
                            $trafficCount = $siteSourceArray['doc_count'];
                        }else{
                            $trafficCount = $siteSourceArray['usersCount']['value'];
                        }
                        if($pageName == 'categoryPage'){                            
                            $page = str_replace(SHIKSHA_STUDYABROAD_HOME.'/','',$course['key']);
                            $page = str_replace(SHIKSHA_HOME.'/','',$course['key']);
                            //$page = str_replace('http://studyabroad.shiksha.com'.'/','',$course['key']);                            
                            if($pageName == $page){
                                $page = $this->getPageName($page);
                            }
                            $pageURL[$page] = $course['key'];

                            $resultSet[] = array(
                                'count' => $trafficCount,
                                'pageName' => '<a href="'.$pageURL[$page].'"  target="_blank">'.$page.'</a>',
                                'trafficSource' => $sourceArray['key'],
                                'sourceApplication' => ($siteSourceArray['key']=='no')?"Desktop":"Mobile"
                                );                        
                        }else{
                            $page = $this->getPageName($course['key']);
                            $resultSet[] = array(
                                'count' => $trafficCount,
                                'pageName' => $page,
                                'trafficSource' => $sourceArray['key'],
                                'sourceApplication' => ($siteSourceArray['key']=='no')?"Desktop":"Mobile"
                            );                            
                        }
                    }
                }
            }
            //_p(count($resultSet));die;
            arsort($resultSet);
            foreach ($resultSet as $key => $value) {
                $dataTableData[] = array(
                            'pageName' => $value['pageName'],
                            'trafficSource' => ucfirst($value['trafficSource']),
                            'sourceApplication' => $value['sourceApplication'],
                            'count' => number_format($value['count']),
                            'percentage' => number_format($value['count']/$actualCountData * 100, 2)
                            );
                unset($resultSet[$key]);
            }
            //_p($resultSet);die;
        }else if($pageName==''){
            $countRow =0;
            foreach ($result as $key => $pageArray){  
                //$total += $pageArray['doc_count'];
                $sourceWise = $pageArray['source']['buckets'];
                foreach ($sourceWise as $key => $sourceArray) 
                {
                    $siteSource = $sourceArray['isMobile']['buckets'];
                    foreach ($siteSource as $keyOne => $siteSourceArray) {
                        $countRow++;
                        if($countRow > 200){
                            break;
                        }
                        $page = $pageArray['key'];
                        $page = $this->getPageName($page);
                        if($aspect != "Users"){
                            $trafficCount = $siteSourceArray['doc_count'];
                        }else{
                            $trafficCount = $siteSourceArray['usersCount']['value'];
                        }
                        $resultSet[] = array(
                            'count' => $trafficCount,
                            'pageName' => $page,
                            'trafficSource' => $sourceArray['key'],
                            'sourceApplication' => ($siteSourceArray['key']=='no')?"Desktop":"Mobile"
                            );                        
                    }
                }
            }            
            arsort($resultSet);
            foreach ($resultSet as $key => $value) {
                $dataTableData[] = array(
                            'pageName' => $value['pageName'],
                            'trafficSource' => ucfirst($value['trafficSource']),
                            'sourceApplication' => $value['sourceApplication'],
                            'count' => number_format($value['count']),
                            'percentage' => number_format($value['count']/$actualCountData * 100, 2)
                            );
                unset($resultSet[$key]);
            }
            //_p($dataTableData);die;
        }
        //_p($dataTableData);die;
        return $dataTableData;
    }

    private function _responseTable($inputRequest){
        $this->_getSearchServerConnection($inputRequest['metric']);
        //_p($inputRequest);die;
        $this->elasticQuery = array();
        $elasticQueryLib = $this->CI->load->library("trackingMIS/elasticQueryLib");
        $this->elasticQuery = $elasticQueryLib->prepareQueryForResponse($inputRequest);

        //_p($inputRequest['dataTable']);
        $previousField;
        $data = &$this->elasticQuery['body'];
        foreach ($inputRequest['dataTable'][0] as $key => $value) {
            if($previousField){
                $data[$previousField]['aggs'] = $this->_addAggerateToESQuery('dataTableAggs',$value['field']);
                $data = &$data[$previousField]['aggs'];
                $previousField = $value['field'];
            }else{
                $data['aggs'] = $this->_addAggerateToESQuery('dataTableAggs',$value['field']);
                $data = &$data['aggs'];
                $previousField = $value['field'];
            }
        }
        //_p(json_encode($this->elasticQuery));die;
        error_log("ELASTICSEARCHQUERY : RESPONSE Data Table :   ".json_encode($this->elasticQuery)."          ");
        $queryResult = $this->clientCon->search($this->elasticQuery);
        //_p($queryResult);die;
        $totalCount = $queryResult['hits']['total'];
        if($inputRequest['pageName']){
            $result = $queryResult['aggregations']['source']['buckets'];
            foreach ($result as $keyName => $keyNameArray) {
                foreach ($keyNameArray['widget']['buckets'] as $widget => $widgetArray) {
                    foreach ($widgetArray['device']['buckets'] as $key => $sourceApplicationArray) {
                        $resultSet[] = array(
                            'count' => $sourceApplicationArray['doc_count'],
                            'keyName' => $keyNameArray['key'],
                            'widget' => $widgetArray['key'],
                            'sourceApplication' => $sourceApplicationArray['key'],
                            'percentage' => number_format($sourceApplicationArray['doc_count']/$totalCount * 100, 2)
                        );
                    }
                }
            }
            arsort($resultSet);
            foreach ($resultSet as $key => $value) {
                $dataTableData[] = array(
                            'keyName' => $value['keyName'],
                            'widget' => $value['widget'],
                            'sourceApplication' => $value['sourceApplication'],
                            'count' => $value['count'],
                            'percentage' => $value['percentage']
                            );
                unset($resultSet[$key]);
            }
        }else{
            $result = $queryResult['aggregations']['page']['buckets'];
            foreach ($result as $key => $pageIdentifierArray) {
                $pageIdentifier = $pageIdentifierArray['device']['buckets'];
                foreach ($pageIdentifier as $sourceApplication => $sourceApplicationArray) {
                    $resultSet[] = array(
                        'count' => $sourceApplicationArray['doc_count'],
                        'pageName' => $this->getPageName($pageIdentifierArray['key']),
                        'sourceApplication' => $sourceApplicationArray['key'],
                        'percentage' => number_format($sourceApplicationArray['doc_count']/$totalCount * 100, 2)
                    );
                }
            }
            arsort($resultSet);
            foreach ($resultSet as $key => $value) {
                $dataTableData[] = array(
                            'pageName' => $value['pageName'],
                            'sourceApplication' => $value['sourceApplication'],
                            'count' => $value['count'],
                            'percentage' => $value['percentage']
                            );
                unset($resultSet[$key]);
            }
        }
        
        //_p($dataTableData);die;
        return $dataTableData;
    }

    private function _registrationTable($inputRequest){
        $this->elasticQuery = array();
        $elasticQueryLib = $this->CI->load->library("trackingMIS/elasticQueryLib");
        $this->elasticQuery = $elasticQueryLib->prepareQueryForRegistration($inputRequest);
        //_p(json_encode($this->elasticQuery));die;
        //_p(json_encode($this->elasticQuery));die;
        //_p($inputRequest['diffChartData']);die;
        $previousField;
        $data = &$this->elasticQuery['body'];
        foreach ($inputRequest['dataTable'][0] as $key => $value) {
            if($previousField){
                $data[$previousField]['aggs'] = $this->_addAggerateToESQuery('dataTableAggs',$value['field']);
                $data = &$data[$previousField]['aggs'];
                $previousField = $value['field'];
            }else{
                $data['aggs'] = $this->_addAggerateToESQuery('dataTableAggs',$value['field']);
                $data = &$data['aggs'];
                $previousField = $value['field'];
            }
        }
        $queryResult = $this->clientCon->search($this->elasticQuery);
        $totalCount = $queryResult['hits']['total'];
        if($inputRequest['pageName']){
            $result = $queryResult['aggregations']['keyName']['buckets'];
            foreach ($result as $key => $pageIdentifierArray) {
                $pageIdentifier = $pageIdentifierArray['widget']['buckets'];
                foreach ($pageIdentifier as $trafficSource => $trafficSourceArray) {
                    $trafficSource = $trafficSourceArray['sourceApplication']['buckets'];
                    foreach ($trafficSource as $sourceApplication => $sourceApplicationArray) {
                        $resultSet[] = array(
                            'count' => $sourceApplicationArray['doc_count'],
                            'keyName' => $pageIdentifierArray['key'],
                            'widget' => $trafficSourceArray['key'],
                            'sourceApplication' => $sourceApplicationArray['key'],
                            'percentage' => number_format($sourceApplicationArray['doc_count']/$totalCount * 100, 2)
                            );
                    }
                }
            }
            arsort($resultSet);
            foreach ($resultSet as $key => $value) {
                $dataTableData[] = array(
                            'keyName' => $value['keyName'],
                            'widget' => $value['widget'],
                            'sourceApplication' => $value['sourceApplication'],
                            'count' => $value['count'],
                            'percentage' => $value['percentage']
                            );
                unset($resultSet[$key]);
            }
        }else{
            $result = $queryResult['aggregations']['pageIdentifier']['buckets'];    
            foreach ($result as $key => $pageIdentifierArray) {
                $pageIdentifier = $pageIdentifierArray['trafficSource']['buckets'];
                foreach ($pageIdentifier as $trafficSource => $trafficSourceArray) {
                    $trafficSource = $trafficSourceArray['sourceApplication']['buckets'];
                    foreach ($trafficSource as $sourceApplication => $sourceApplicationArray) {
                        $resultSet[] = array(
                            'count' => $sourceApplicationArray['doc_count'],
                            'pageName' => $this->getPageName($pageIdentifierArray['key']),
                            'trafficSource' => $trafficSourceArray['key'],
                            'sourceApplication' => $sourceApplicationArray['key'],
                            'percentage' => number_format($sourceApplicationArray['doc_count']/$totalCount * 100, 2)
                            );
                    }
                }
            }
            arsort($resultSet);
            foreach ($resultSet as $key => $value) {
                $dataTableData[] = array(
                            'pageName' => $value['pageName'],
                            'trafficSource' => $value['trafficSource'],
                            'sourceApplication' => $value['sourceApplication'],
                            'count' => $value['count'],
                            'percentage' => $value['percentage']
                            );
                unset($resultSet[$key]);
            }
        }
        
        return $dataTableData;
    }

    //currently not used
    public function prepareDataTableData($inputArray,$inputFilter,$x){
        _p($inputArray);_p($inputFilter);die;
        echo '---------------------';
        $i=0;
        foreach ($inputArray as $key => $value) {
            if(is_array($value[$inputFilter[$x]])){
                $temp = $value[$inputFilter[$x]]['buckets'];
                $x++;
                $resultSet[$key] = $this->prepareDataTableData($temp,$inputFilter,$x);
                return 0;
            }
            
        }
    }

    private function _prepareQueryForRegistration($inputRequest)
    {
        $this->elasticQuery = array(
            'index' => REGISTRATION_INDEX_NAME,
            'type'  => 'registration',
            'body'  => array(
                'size' => 0
            )
        );

        if($inputRequest['dateRange']['startDate'] != ''){
            $startDateFilter = array(
                'range' => array(
                    'registrationDate' => array(
                        'gte' => $inputRequest['dateRange']['startDate'] . 'T00:00:00'
                    ),
                )
            );
            $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][] = $startDateFilter;
        }

        if($inputRequest['dateRange']['endDate'] != ''){
            $endDateFilter = array(
                'range' => array(
                    'registrationDate' => array(
                        'lte' => $inputRequest['dateRange']['endDate'] . 'T23:59:59'
                    ),
                )
            );
            $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][] = $endDateFilter;
        }

        //Source Application
        if (strcasecmp($inputRequest['sourceApplication'], 'all') != 0) {
            if (
                strcasecmp($inputRequest['sourceApplication'], 'desktop') == 0 ||
                strcasecmp($inputRequest['sourceApplication'], 'mobile') == 0
            ) {
                $deviceFilter = array(
                    'term' => array(
                        'sourceApplication' => ucfirst(strtolower($inputRequest['sourceApplication']))
                    )
                );

                $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][] = $deviceFilter;
            }
        }
        // team filter
        if ($inputRequest['misSource'] != 'SHIKSHA') {
            $teamFilter = array(
                'term' => array(
                    'site' => 'Study Abroad'
                )
            );
            if ($inputRequest['misSource'] == 'STUDY ABROAD') {
                $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][] = $teamFilter;
            } else if ($inputRequest['misSource'] == 'DOMESTIC') {
                $this->elasticQuery['body']['query']['bool']['filter']['bool']['must_not'][] = $teamFilter;
            }
        }

        if ($inputRequest['misSource'] == 'STUDY ABROAD') {
            if($inputRequest['categoryId'] >0){
                $this->abroadCommonLib = $this->CI->load->library('listingPosting/AbroadCommonLib');
                $ldbCourseIdsArray = $this->abroadCommonLib->getAbroadMainLDBCourses();
                foreach ($ldbCourseIdsArray as $key => $value) {
                    $ldbCourseIds[]= $value['SpecializationId'];
                }
                if(in_array($inputRequest['categoryId'],$ldbCourseIds)){
                    $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['term']['desiredCourse'] = $inputRequest['categoryId'];
                }else{
                    $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['term']['categoryId'] = $inputRequest['categoryId'];
                    if($inputRequest['courseLevel'] != '0'){
                        $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['term']['courseLevel'] = $inputRequest['courseLevel'];
                    }
                }
            }else if(!empty($inputRequest['courseLevel']) && $inputRequest['courseLevel'] != '0'){
                $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['term']['courseLevel'] = $inputRequest['courseLevel'];
            }

            if(!empty($inputRequest['country']) && $inputRequest['country'] != '0'){
                $this->elasticQuery['body']['query']['bool']['filter']['bool']['should'][]['term']['prefCountry1'] = $inputRequest['country'];
                $this->elasticQuery['body']['query']['bool']['filter']['bool']['should'][]['term']['prefCountry2'] = $inputRequest['country'];
                $this->elasticQuery['body']['query']['bool']['filter']['bool']['should'][]['term']['prefCountry3'] = $inputRequest['country'];
            }

            // abroad exam filter
            if(in_array($inputRequest['abroadExam'], array('yes','no','booked'))){
                $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['term']['exam'] = $inputRequest['abroadExam'];
            }
        } else if ($inputRequest['misSource'] == 'DOMESTIC'){
           if($inputRequest['categoryId'] >0){
                if($inputRequest['categoryId'] == 14){
                    $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['term']['userType'] = 'testPrep';
                    if($inputRequest['mainExam'] >0 ){
                        if($inputRequest['subExam'] >0 ){
                            $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['term']['blogId'] = $inputRequest['subExam'];
                        }else{
                            $this->CI->load->model('overview_model');
                            $this->OverviewModel  = new overview_model();
                            $blogIds = $this->OverviewModel->getAllSubExamId($inputRequest['mainExam']);
                            $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['terms']['blogId'] = $blogIds;
                        }
                    }
                }else{
                    $this->elasticQuery['body']['query']['bool']['filter']['bool']['must_not'][]['term']['userType'] = 'testPrep';
                    $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['term']['categoryId'] = $inputRequest['categoryId'];
                    if($inputRequest['subCategory'] >0){
                        $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['term']['subCategoryId'] = $inputRequest['subCategory'];
                    }
                }      
            }
        }

        if (strlen($inputRequest['pageName']) > 0 && strcasecmp($inputRequest['pageName'], 'all') != 0) {
            if ($inputRequest['misSource'] == 'STUDY ABROAD') {
                if ($inputRequest['pageName'] == 'rankingPage') {
                        if ($inputRequest['pageType'] == 1) {
                            $pageNameFilter = array(
                                'term' => array(
                                    'pageIdentifier' => 'universityRankingPage'
                                )
                            );
                        } else if ($inputRequest['pageType'] == 2) {
                            $pageNameFilter = array(
                                'term' => array(
                                    'pageIdentifier' => 'courseRankingPage'
                                )
                            );
                        } else {
                            $pageNameFilter = array(
                                'terms' => array(
                                    'pageIdentifier' => array(
                                        'courseRankingPage',
                                        'universityRankingPage'
                                    )
                                )
                            );
                        }
                    } else if ($inputRequest['pageName'] == 'categoryPage'){
                        $pageNameFilter = array(
                            'terms' => array(
                                'pageIdentifier' => array(
                                    'categoryPage',
                                    'savedCoursesTab'
                                )
                            )
                        );
                    }else{
                        $pageNameFilter = array(
                            'term' => array(
                                'pageIdentifier' => $inputRequest['pageName']
                            )
                        );
                    }
            }else{
                $pageNameFilter = array(
                            'term' => array(
                                'pageIdentifier' => $inputRequest['pageName']
                            )
                        );
            }
            $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][] = $pageNameFilter;
        }

        if ($inputRequest['shikshaPages']!= "0" && $inputRequest['shikshaPages']!= null){
            $pageNameFilter = array(
                'term' => array(
                    'pageIdentifier' => $inputRequest['shikshaPages']
                )
            );
            $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][] = $pageNameFilter;
        }
    }

    private function _prepareQueryForResponse($inputRequest)
    {
        $this->elasticQuery = array(
            'index' => SHIKSHA_RESPONSE_INDEX_NAME,
            'type'  => 'response',
            'body'  => array(
                'size' => 0
            )
        );

        if($inputRequest['dateRange']['startDate'] != ''){
            $startDateFilter = array(
                'range' => array(
                    'response_time' => array(
                        'gte' => $inputRequest['dateRange']['startDate'] . 'T00:00:00'
                    ),
                )
            );
            $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][] = $startDateFilter;
        }

        if($inputRequest['dateRange']['endDate'] != ''){
            $endDateFilter = array(
                'range' => array(
                    'response_time' => array(
                        'lte' => $inputRequest['dateRange']['endDate'] . 'T23:59:59'
                    ),
                )
            );
            $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][] = $endDateFilter;
        }

        //Source Application
        if (strcasecmp($inputRequest['sourceApplication'], 'all') != 0) {
            if (
                strcasecmp($inputRequest['sourceApplication'], 'desktop') == 0 ||
                strcasecmp($inputRequest['sourceApplication'], 'mobile') == 0
            ) {
                $deviceFilter = array(
                    'term' => array(
                        'sourceApplication' => ucfirst(strtolower($inputRequest['sourceApplication']))
                    )
                );

                $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][] = $deviceFilter;
            }
        }
        // team filter
        if ($inputRequest['misSource'] != 'SHIKSHA') {
            $teamFilter = array(
                'term' => array(
                    'site' => 'Study Abroad'
                )
            );
            if ($inputRequest['misSource'] == 'STUDY ABROAD') {
                $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][] = $teamFilter;
            } else if ($inputRequest['misSource'] == 'DOMESTIC') {
                $this->elasticQuery['body']['query']['bool']['filter']['bool']['must_not'][] = $teamFilter;
            }
        }

        if ($inputRequest['misSource'] == 'STUDY ABROAD') {
            if($inputRequest['categoryId'] >0){
                $this->abroadCommonLib = $this->CI->load->library('listingPosting/AbroadCommonLib');
                $ldbCourseIdsArray = $this->abroadCommonLib->getAbroadMainLDBCourses();
                foreach ($ldbCourseIdsArray as $key => $value) {
                    $ldbCourseIds[]= $value['SpecializationId'];
                }
                if(in_array($inputRequest['categoryId'],$ldbCourseIds)){
                    $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['term']['desiredCourse'] = $inputRequest['categoryId'];
                }else{
                    $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['term']['categoryId'] = $inputRequest['categoryId'];
                    if($inputRequest['courseLevel'] != '0'){
                        $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['term']['courseLevel'] = $inputRequest['courseLevel'];
                    }
                }
            }else if(!empty($inputRequest['courseLevel']) && $inputRequest['courseLevel'] != '0'){
                $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['term']['courseLevel'] = $inputRequest['courseLevel'];
            }

            if(!empty($inputRequest['country']) && $inputRequest['country'] != '0'){
                $this->elasticQuery['body']['query']['bool']['filter']['bool']['should'][]['term']['prefCountry1'] = $inputRequest['country'];
                $this->elasticQuery['body']['query']['bool']['filter']['bool']['should'][]['term']['prefCountry2'] = $inputRequest['country'];
                $this->elasticQuery['body']['query']['bool']['filter']['bool']['should'][]['term']['prefCountry3'] = $inputRequest['country'];
            }

            // abroad exam filter
            if(in_array($inputRequest['abroadExam'], array('yes','no','booked'))){
                $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['term']['exam'] = $inputRequest['abroadExam'];
            }
        } else if ($inputRequest['misSource'] == 'DOMESTIC'){
           if($inputRequest['categoryId'] >0){
                if($inputRequest['categoryId'] == 14){
                    $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['term']['userType'] = 'testPrep';
                    if($inputRequest['mainExam'] >0 ){
                        if($inputRequest['subExam'] >0 ){
                            $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['term']['blogId'] = $inputRequest['subExam'];
                        }else{
                            $this->CI->load->model('overview_model');
                            $this->OverviewModel  = new overview_model();
                            $blogIds = $this->OverviewModel->getAllSubExamId($inputRequest['mainExam']);
                            $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['terms']['blogId'] = $blogIds;
                        }
                    }
                }else{
                    $this->elasticQuery['body']['query']['bool']['filter']['bool']['must_not'][]['term']['userType'] = 'testPrep';
                    $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['term']['categoryId'] = $inputRequest['categoryId'];
                    if($inputRequest['subCategory'] >0){
                        $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['term']['subCategoryId'] = $inputRequest['subCategory'];
                    }
                }      
            }
        }

        if (strlen($inputRequest['pageName']) > 0 && strcasecmp($inputRequest['pageName'], 'all') != 0) {
            if ($inputRequest['misSource'] == 'STUDY ABROAD') {
                if ($inputRequest['pageName'] == 'rankingPage') {
                        if ($inputRequest['pageType'] == 1) {
                            $pageNameFilter = array(
                                'term' => array(
                                    'pageIdentifier' => 'universityRankingPage'
                                )
                            );
                        } else if ($inputRequest['pageType'] == 2) {
                            $pageNameFilter = array(
                                'term' => array(
                                    'pageIdentifier' => 'courseRankingPage'
                                )
                            );
                        } else {
                            $pageNameFilter = array(
                                'terms' => array(
                                    'pageIdentifier' => array(
                                        'courseRankingPage',
                                        'universityRankingPage'
                                    )
                                )
                            );
                        }
                    } else if ($inputRequest['pageName'] == 'categoryPage'){
                        $pageNameFilter = array(
                            'terms' => array(
                                'pageIdentifier' => array(
                                    'categoryPage',
                                    'savedCoursesTab'
                                )
                            )
                        );
                    }else{
                        $pageNameFilter = array(
                            'term' => array(
                                'pageIdentifier' => $inputRequest['pageName']
                            )
                        );
                    }
            }else{
                $pageNameFilter = array(
                            'term' => array(
                                'pageIdentifier' => $inputRequest['pageName']
                            )
                        );
            }
            $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][] = $pageNameFilter;
        }

        if ($inputRequest['shikshaPages']!= "0"){
            $pageNameFilter = array(
                'term' => array(
                    'pageIdentifier' => $inputRequest['shikshaPages']
                )
            );
            $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][] = $pageNameFilter;
        }
    }

    /**
     * Prepare an elasticsearch query to be used in various response generation logics
     *
     * @param stdClass $inputRequest An object containing the user input (typically via URL parameters / post data)
     * @param string $team The team identifier - global / shiksha or domestic / national or abroad / studyabroad
     */
    private function prepareQuery($inputRequest, $team)
    {
        $this->elasticQuery = array(
            'index' => MISCommonLib::$RESPONSES_DATA['indexName'],
            'type'  => MISCommonLib::$RESPONSES_DATA['type'],
            'body'  => array(
                'size' => 0
            )
        );

        if($inputRequest->startDate != ''){
            $startDateFilter = array(
                'range' => array(
                    'responseDate' => array(
                        'gte' => $inputRequest->startDate . 'T00:00:00'
                    ),
                )
            );
            $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][] = $startDateFilter;
        }

        if($inputRequest->endDate != ''){
            $endDateFilter = array(
                'range' => array(
                    'responseDate' => array(
                        'lte' => $inputRequest->endDate . 'T23:59:59'
                    ),
                )
            );
            $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][] = $endDateFilter;
        }


	$specialCategoryIds = array();
        $ldbCourseIdsArray = $this->abroadCommonLib->getAbroadMainLDBCourses();
	$specialCategoryIds = array_map(function($a){ return $a['SpecializationId']; },$ldbCourseIdsArray);
        if (intval($inputRequest->category) > 0) {
            $fieldName          = 'categoryId';
            if (in_array($inputRequest->category, $specialCategoryIds)) {
                $fieldName = 'ldbCourseId';
            }
            $categoryFilter                                                              = array(
                'term' => array(
                    $fieldName => $inputRequest->category
                )
            );
            $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][] = $categoryFilter;
        }

        if (intval($inputRequest->subcategoryId) > 0) {
            $subcategoryFilter                                                     = array(
                'term' => array(
                    'subCategoryId' => $inputRequest->subcategoryId
                )
            );
            $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][] = $subcategoryFilter;
        }

        if (intval($inputRequest->country) > 0) {
            $countryFilter = array(
                'term' => array(
                    'countryId' => $inputRequest->country
                )
            );
            $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][] = $countryFilter;
        }

        if ( !in_array($inputRequest->category, $specialCategoryIds) &&
            intval($inputRequest->category) >= 0 &&
            ($team == 'abroad' || $team == 'studyabroad')
        ) {
            if (($inputRequest->courseLevel != '') && ($inputRequest->courseLevel != '0')) {
                $courseLevelFilter                                                           = array(
                    'term' => array(
                        'courseLevel' => $inputRequest->courseLevel
                    )
                );
                $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][] = $courseLevelFilter;
            }
        }

        if (strlen($inputRequest->pageName) > 0 && strcasecmp($inputRequest->pageName, 'all') != 0) {


            if ($team == 'abroad' || $team == 'studyabroad') {

                if ($inputRequest->pageName == 'rankingPage') {
                    if ($inputRequest->pageType == 1) {
                        $pageNameFilter = array(
                            'term' => array(
                                'pageIdentifier' => 'universityRankingPage'
                            )
                        );
                    } else if ($inputRequest->pageType == 2) {
                        $pageNameFilter = array(
                            'term' => array(
                                'pageIdentifier' => 'courseRankingPage'
                            )
                        );
                    } else {
                        $pageNameFilter = array(
                            'terms' => array(
                                'pageIdentifier' => array(
                                    'courseRankingPage',
                                    'universityRankingPage'
                                )
                            )
                        );
                    }
                } else if ($inputRequest->pageName == 'categoryPage'){
                    $pageNameFilter = array(
                        'terms' => array(
                            'pageIdentifier' => array(
                                'categoryPage',
                                'savedCoursesTab'
                            )
                        )
                    );
                }else{
                    $pageNameFilter = array(
                        'term' => array(
                            'pageIdentifier' => $inputRequest->pageName
                        )
                    );
                }
            } else {
                $pageNameFilter = array(
                    'term' => array(
                        'pageIdentifier' => $this->getPageNameForDomestic($inputRequest->pageName)
                    )
                );
            }

            $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][] = $pageNameFilter;
        }

        if (strlen($inputRequest->pivotType) > 0 && strcasecmp($inputRequest->pivotType, 'all') != 0) {

            if($inputRequest->isRMC != "yes"){
                $pivotTypeFilter                                                       = array(
                'term' => array(
                    'responseType' => $inputRequest->pivotType
                )
            );
            $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][] = $pivotTypeFilter;    
            }
            
        }

        if (strlen($inputRequest->sourceApplication) > 0 && strcasecmp($inputRequest->sourceApplication, 'all') != 0) {
            if (
                strcasecmp($inputRequest->sourceApplication, 'desktop') == 0 ||
                strcasecmp($inputRequest->sourceApplication, 'mobile') == 0
            ) {
                $deviceFilter = array(
                    'term' => array(
                        'sourceApplication' => ucfirst(strtolower($inputRequest->sourceApplication))
                    )
                );

                $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][] = $deviceFilter;
            }
        }

        $team = strtolower($team);
        if ($team != 'global' && $team != 'shiksha') {

            $teamFilter = array(
                'term' => array(
                    'site' => 'Study Abroad'
                )
            );
            if ($team == 'abroad' || $team == 'studyabroad') {
                $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][] = $teamFilter;
            } else if ($team == 'domestic' || $team == 'national') {
                $this->elasticQuery['body']['query']['bool']['filter']['bool']['must_not'][] = $teamFilter;
            }
        }

        if($inputRequest->isRMC == 'yes'){
            $teamFilter = array(
                'terms' => array(
                    'RMCResponseType' => array('paid','free')
                )
            );
            $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][] = $teamFilter;
            
        }
    }

    /**
     * This method acts as the pageview counterpart of the method prepareTrafficQuery
     * @param        $dateRange
     * @param string $pageName
     * @param array  $extraData
     *
     * @return array
     */
    public function preparePageviewQuery($dateRange, $pageName='', $extraData=array())
    {
        $elasticQuery = array(
            'index' => PAGEVIEW_INDEX_NAME,
            'type' => 'pageview',
            'body' => array(
                'size' => 0
            )
        );

        $startDate = $dateRange['startDate'].'T00:00:00';
        $endDate = $dateRange['endDate'].'T23:59:59';

        $categoryId = $extraData['National']['category'];
        $subcategoryIds = explode(",", $extraData['National']['subcategory']);
        $deviceType = isset($extraData['National']['deviceType']) && $extraData['National']['deviceType'] != '' ? $extraData['National']['deviceType']: 'all';

        if(strtolower($pageName) != 'all'){
            // Decide if the category and subcategory filter has to be applied based on the page name
            if ($pageName != 'home' && $pageName != 'institute' && $pageName != 'exam_calendar' && $pageName != 'qna') {
                if ($pageName == 'exam_calendar' || $pageName == 'article_home') {
                    if (intval($extraData['National']['subcategory']) != 0) {
                        $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = array(
                            'term' => array(
                                'subCategoryId' => $subcategoryIds
                            )
                        );
                    }
                } else if ($pageName == 'article_detail') {
                    $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = array(
                        'term' => array(
                            'categoryId' => doubleval($categoryId)
                        )
                    );

                }else {
                    if(intval($categoryId) != 0 ){
                        $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = array(
                            'term' => array(
                                'categoryId' => doubleval($categoryId)
                            )
                        );
                    }
                    if (intval($extraData['National']['subcategory']) != 0) {
                        $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = array(
                            'term' => array(
                                'subCategoryId' => $subcategoryIds
                            )
                        );
                    }
                }
            }
        } else {
            if(intval($categoryId) != 0 ){
                $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = array(
                    'term' => array(
                        'categoryId' => doubleval($categoryId)
                    )
                );
            }
            if (intval($extraData['National']['subcategory']) != 0) {
                $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = array(
                    'term' => array(
                        'subCategoryId' => $subcategoryIds
                    )
                );
            }
        }

        $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = array(
            'term' => array(
                'isStudyAbroad' => 'no'
            )
        );
        $sourceApplicationFilter = array();
        if($deviceType == 'desktop'){

            $sourceApplicationFilter = array(
                'term' => array(
                    'isMobile' => 'no'
                )
            );
            $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = $sourceApplicationFilter;
        } else if ($deviceType == 'mobile'){
            $sourceApplicationFilter = array(
                'term' => array(
                    'isMobile' => 'yes'
                )
            );
            $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = $sourceApplicationFilter;
        }
        if(strtolower($pageName) != 'all'){
            $pageNameValue = $this->getPageNameForDomestic($pageName);
            if($pageNameValue != ''){
                $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = array(
                    'term' => array('pageIdentifier' => $pageNameValue)
                );
            }
        }

        $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = array(
            'range' => array(
                'visitTime' => array(
                    'gte' => $startDate,
                    'lte' => $endDate
                ),
            )
        );

        if (isset($extraData['National']['trafficSource'])) {
            $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = array(
                'term' => array(
                    'source' => $extraData['National']['trafficSource']
                )
            );
        }

        $view = $this->decideView($dateRange, $extraData['National']['view'], 'es');

        $dateWiseGrouping = array(
            'dateWise' => array(
                'date_histogram' => array(
                    'interval' => $view,
                    'field'    => 'visitTime',
                    'order' => array(
                        "_key" => "desc"
                    )
                ),
            )
        );

        $elasticQuery['body']['aggs'] = $dateWiseGrouping;
        return $elasticQuery;
    }

    /**
     * This method will act as the replacement of the existing method trafficDataLib::_prepareNationalFilters
     *
     * Can we use this instead of the method trafficDataLib::_prepareNationalFilters? Currently this is being used in trafficDataLib::getTrafficTiles
     * @param array  $dateRange
     * @param string $pageName
     * @param array  $extraData
     *
     * @return array
     */
    public function prepareTrafficQuery($dateRange, $pageName='', $extraData=array())
    {
        $elasticQuery = array(
            'index' => SESSION_INDEX_NAME,
            'type' => 'session',
            'body' => array(
                'size' => 0
            )
        );

        $startDate = $dateRange['startDate'].'T00:00:00';
        $endDate = $dateRange['endDate'].'T23:59:59';

        $categoryId = $extraData['National']['category'];
        $subcategoryIds = explode(",", $extraData['National']['subcategory']);
        $deviceType = isset($extraData['National']['deviceType']) && $extraData['National']['deviceType'] != '' ? $extraData['National']['deviceType']: 'all';

        $categoryIdTerm = 'landingPageDoc.categoryId';
        $subCategoryIdTerm = 'landingPageDoc.subCategoryId';
        $pageNameTerm = 'landingPageDoc.pageIdentifier';
        $isStudyAbroadTerm = 'isStudyAbroad';
        $isMobileTerm = 'isMobile';

        if( $extraData['National']['pivot'] == 'exit') {
            $categoryIdTerm = 'exitPage.categoryId';
            $subCategoryIdTerm = 'exitPage.subCategoryId';
            $pageNameTerm = 'exitPage.pageIdentifier';
            $isStudyAbroadTerm = 'exitPage.isStudyAbroad';
            $isMobileTerm = 'exitPage.isMobile';
        }
        if(strtolower($pageName) != 'all'){
            // Decide if the category and subcategory filter has to be applied based on the page name
            if ($pageName != 'home' && $pageName != 'institute' && $pageName != 'exam_calendar' && $pageName != 'qna') {
                if ($pageName == 'exam_calendar' || $pageName == 'article_home') {
                    if (intval($extraData['National']['subcategory']) != 0) {
                        $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = array(
                            'term' => array(
                                $subCategoryIdTerm => $subcategoryIds
                            )
                        );
                    }
                } else if ($pageName == 'article_detail') {
                    $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = array(
                        'term' => array(
                            $categoryIdTerm => doubleval($categoryId))
                    );

                }else {
                    if($categoryId != 0 ){
                        $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = array(
                            'term' => array(
                                $categoryIdTerm => doubleval($categoryId)
                            )
                        );
                    }
                    if ($extraData['National']['subcategory'] != 0) {
                        $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = array(
                            'term' => array(
                                $subCategoryIdTerm => $subcategoryIds
                            )
                        );
                    }
                }
            }
        } else {
            if($categoryId != 0 ){
                $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = array(
                    'term' => array(
                        $categoryIdTerm => doubleval($categoryId)
                    )
                );
            }
            if ($extraData['National']['subcategory'] != 0) {
                $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = array(
                    'term' => array(
                        $subCategoryIdTerm => $subcategoryIds
                    )
                );
            }
        }

        $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = array(
            'term' => array(
                $isStudyAbroadTerm => 'no')
        );
        $sourceApplicationFilter = array();
        if($deviceType == 'desktop'){

            $sourceApplicationFilter = array(
                'term' => array(
                    $isMobileTerm => 'no'
                )
            );
            $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = $sourceApplicationFilter;
        } else if ($deviceType == 'mobile'){
            $sourceApplicationFilter = array(
                'term' => array(
                    $isMobileTerm => 'yes'
                )
            );
            $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = $sourceApplicationFilter;
        }
        if(strtolower($pageName) != 'all'){
            $pageNameValue = $this->getPageNameForDomestic($pageName);
            if($pageNameValue != ''){
                $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = array(
                    'term' => array($pageNameTerm => $pageNameValue)
                );
            }
        }

        $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = array(
            'range' => array(
                'startTime' => array(
                    'gte' => $startDate,
                    'lte' => $endDate
                ),
            )
        );

        if (isset($extraData['National']['trafficSource'])) {
            $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = array(
                'term' => array(
                    'source' => $extraData['National']['trafficSource']
                )
            );
        }

        $view = $this->decideView($dateRange, $extraData['National']['view'], 'es');

        $dateWiseGrouping = array(
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

        $elasticQuery['body']['aggs'] = $dateWiseGrouping;

        return $elasticQuery;
    }


    public function getResponseStatsData($dateRange, $team='global')
    {

        $this->initiateModel();
        if($team == 'abroad'){
            $this->dbHandle->select('acpd.category_id,acpd.sub_category_id,acpd.city_id,acpd.university_id,acpd.country_id,`tlt`.`listing_type_id`, count(distinct tlt.id) as count'); //table
            $this->dbHandle->join('abroadCategoryPageData acpd','acpd.course_id = tlt.listing_type_id','inner'); // table
            //$this->dbHandle->where('acpd.status = ','live'); // table
        }else if($source == 'national'){
            $this->dbHandle->select('cpd.category_id as subCategoryId,cpd.city_id,cpd.institute_id,`tlt`.`listing_type_id`, count(distinct tlt.id) as count');
            $this->dbHandle->join('categoryPageData cpd','cpd.course_id = tlt.listing_type_id','inner'); // table
            //$this->dbHandle->where('cpd.status = ','live'); // table
        }

        $this->dbHandle->from('tempLMSTable tlt');
        $this->dbHandle->where('tlt.listing_type','course');
        $this->dbHandle->where_in('tlt.tracking_keyid',$trackingIdsArray);
        $this->dbHandle->where('tlt.submit_date >=',$dateRange['startDate'].' 00:00:00');
        $this->dbHandle->where('tlt.submit_date <=',$dateRange['endDate'].' 23:59:59');

        $this->dbHandle->group_by('tlt.listing_type_id');  // table
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        return $result;
    }

    public function get($something, $basisInputRequest, $forTeam){
        $COUNT = 10;

        $this->prepareQuery($basisInputRequest, $forTeam);
        switch($something){
            case 'categories':
                $this->elasticQuery['body']['query']['bool']['filter']['bool']['must_not'][] = array(
                    'term' => array(
                        'categoryId' => 0
                    )
                );
                $aggs = array(
                    $something => array(
                        'terms' => array(
                            'field' => 'categoryId',
                            'size' => $COUNT,
                            'order' => array(
                                '_count' => 'desc'
                            )
                        )
                    )
                );
                break;
            case 'subcategories':
                $this->elasticQuery['body']['query']['bool']['filter']['bool']['must_not'][] = array(
                    'term' => array(
                        'subCategoryId' => 0
                    )
                );
                $aggs = array(
                    $something => array(
                        'terms' => array(
                            'field' => 'subCategoryId',
                            'size' => $COUNT,
                            'order' => array(
                                '_count' => 'desc'
                            )
                        )
                    )
                );
                break;
            case 'cities':
                $this->elasticQuery['body']['query']['bool']['filter']['bool']['must_not'][] = array(
                    'term' => array(
                        'cityId' => 0
                    )
                );
                $aggs = array(
                    $something => array(
                        'terms' => array(
                            'field' => 'cityId',
                            'size' => $COUNT,
                            'order' => array(
                                '_count' => 'desc'
                            )
                        )
                    )
                );
                break;
            case 'institutes':
                $this->elasticQuery['body']['query']['bool']['filter']['bool']['must_not'][] = array(
                    'term' => array(
                        'listingTypeId' => 0
                    )
                );
                $aggs = array(
                    $something => array(
                        'terms' => array(
                            'field' => 'listingTypeId',
                            'size' => $COUNT,
                            'order' => array(
                                '_count' => 'desc'
                            )
                        )
                    )
                );
                break;
            case 'pages':
                $aggs = array(
                    $something => array(
                        'terms' => array(
                            'field' => 'pageIdentifier',
                            'size' => $COUNT,
                            'order' => array(
                                '_count' => 'desc'
                            )
                        )
                    )
                );
                break;
            case 'countries':
                $this->elasticQuery['body']['query']['bool']['filter']['bool']['must_not'][] = array(
                    'term' => array(
                        'countryId' => 0
                    )
                );
                $aggs = array(
                    $something => array(
                        'terms' => array(
                            'field' => 'countryId',
                            'size' => $COUNT,
                            'order' => array(
                                '_count' => 'desc'
                            )
                        )
                    )
                );
                break;
        }

        $this->elasticQuery['body']['aggs'] = $aggs;

        $result = $this->clientCon->search($this->elasticQuery);
        return $result['aggregations'][$something]['buckets'];
    }

    function getTopRegistrationStats($inputRequest){
        //_p($inputRequest);die;
        $responses = array();
        $result = $this->getTopStatsDataForRegistration($inputRequest);
        if($inputRequest['showGrowth'] == true){
            // mom data
            $dateRange = $inputRequest['dateRange'];
            $inputRequest['dateRange']['startDate'] = date('Y-m-d', strtotime($dateRange['startDate'].' -30 days'));
            $inputRequest['dateRange']['endDate'] = date('Y-m-d', strtotime($dateRange['endDate'].' -30 days'));
            $momData = $this->getTopStatsDataForRegistration($inputRequest);
            
            // yoy data
            $inputRequest['dateRange']['startDate'] = date('Y-m-d', strtotime($dateRange['startDate'].' -1 year'));
            $inputRequest['dateRange']['endDate'] = date('Y-m-d', strtotime($dateRange['endDate'].' -1 year'));
            $yoyData = $this->getTopStatsDataForRegistration($inputRequest);

            foreach ($result as $key => $count) {
                $momGrowth[$key] = isset($momData[$key])?number_format(((($count - $momData[$key])*100)/$momData[$key]), 0):100;
                $yoyGrowth[$key] = isset($yoyData[$key])?number_format(((($count - $yoyData[$key])*100)/$yoyData[$key]), 0):100;
            }
        }
        //_p($result);_p($momGrowth);_p($yoyGrowth);die;
        switch ($inputRequest['aspect']) {
            case 'topPages':
                foreach ($result as $key => $count) {
                    $responses[] = array(
                        'ID'          => $key,
                        'PivotName'   => $key,
                        'ScalarValue' => $count,
                        'DeltaMOM'    => $momGrowth[$key],
                        'DeltaYOY'    => $yoyGrowth[$key]
                    );
                }
                //_p($responses);die;
                break;
            
            case 'topCategories':
                $this->CI->load->builder('CategoryBuilder','categoryList');
                $categoryBuilder = new CategoryBuilder;
                $this->categoryRepository = $categoryBuilder->getCategoryRepository();
                $categoryObject = $this->categoryRepository->findMultiple(array_keys($result));
                foreach ($result as $key => $count) {
                    $responses[] = array(
                        'ID'          => $key,
                        'PivotName'   => (isset($categoryObject[$key]))?$categoryObject[$key]->getName():"",
                        'ScalarValue' => $count,
                        'DeltaMOM'    => $momGrowth[$key],
                        'DeltaYOY'    => $yoyGrowth[$key]
                    );
                }
                break;

            case 'topSubcategories':
                $this->CI->load->builder('CategoryBuilder','categoryList');
                $categoryBuilder = new CategoryBuilder;
                $this->categoryRepository = $categoryBuilder->getCategoryRepository();
                $categoryObject = $this->categoryRepository->findMultiple(array_keys($result));
                foreach ($result as $key => $count) {
                    $responses[] = array(
                        'ID'          => $key,
                        'PivotName'   => (isset($categoryObject[$key]))?$categoryObject[$key]->getName():"Unknown",
                        'ScalarValue' => $count,
                        'DeltaMOM'    => $momGrowth[$key],
                        'DeltaYOY'    => $yoyGrowth[$key]
                    );
                }
                
                break;

            case 'topCountries':
                $this->CI->load->builder('LocationBuilder','location');
                $locationBuilder    = new LocationBuilder();
                $this->locationRepository = $locationBuilder->getLocationRepository();

                foreach ($result as $key => $count) {
                    $countryObj = $this->locationRepository->findCountry($key);
                    $responses[] = array(
                        'ID'          => $key,
                        'PivotName'   => (is_object($countryObj))?$countryObj->getName():"Unknown",
                        'ScalarValue' => $count,
                        'DeltaMOM'    => $momGrowth[$key],
                        'DeltaYOY'    => $yoyGrowth[$key]
                    );
                }
                break;

            case 'topCities':
                $this->CI->load->builder('LocationBuilder','location');
                $locationBuilder    = new LocationBuilder();
                $this->locationRepository = $locationBuilder->getLocationRepository();

                $cityObj = $this->locationRepository->findMultipleCities(array_keys($result));

                foreach ($result as $key => $count) {
                    $responses[] = array(
                        'ID'          => $key,
                        'PivotName'   => (isset($cityObj[$key]))?$cityObj[$key]->getName():"Unknown",
                        'ScalarValue' => $count,
                        'DeltaMOM'    => $momGrowth[$key],
                        'DeltaYOY'    => $yoyGrowth[$key]
                    );
                }
                break;

            case 'topStreams':
                $this->CI->load->builder('ListingBaseBuilder', 'listingBase');
                $listingBaseBuilder   = new ListingBaseBuilder();
                $streamRepository     = $listingBaseBuilder->getStreamRepository();

                $streamObj = $streamRepository->findMultiple(array_keys($result));
                //_p($streamObj);die;
                foreach ($result as $key => $count) {
                    $responses[] = array(
                        'ID'          => $key,
                        'PivotName'   => (isset($streamObj[$key]))?$streamObj[$key]->getName():"Unknown",
                        'ScalarValue' => $count,
                        'DeltaMOM'    => $momGrowth[$key],
                        'DeltaYOY'    => $yoyGrowth[$key]
                    );
                }
                break;

            case 'topDesiredCountries':
                $this->CI->load->builder('LocationBuilder','location');
                $locationBuilder    = new LocationBuilder();
                $this->locationRepository = $locationBuilder->getLocationRepository();

                foreach ($result as $key => $count) {
                    $countryObj = $this->locationRepository->findCountry($key);
                    $responses[] = array(
                        'ID'          => $key,
                        'PivotName'   => (is_object($countryObj))?$countryObj->getName():"Unknown",
                        'ScalarValue' => $count,
                        'DeltaMOM'    => $momGrowth[$key],
                        'DeltaYOY'    => $yoyGrowth[$key]
                    );
                }
                break;

            default:
                return array();
                break;
        }
        //_p($responses);die;
        echo json_encode($responses);
    }

    function getTopResponseStats($inputRequest){
        //_p($inputRequest);die;
        $responses = array();
        $result = $this->getTopStatsData($inputRequest);
        if($inputRequest['showGrowth'] == true){
            // mom data
            $dateRange = $inputRequest['dateRange'];
            $inputRequest['dateRange']['startDate'] = date('Y-m-d', strtotime($dateRange['startDate'].' -30 days'));
            $inputRequest['dateRange']['endDate'] = date('Y-m-d', strtotime($dateRange['endDate'].' -30 days'));
            $momData = $this->getTopStatsData($inputRequest);
            
            // yoy data
            $inputRequest['dateRange']['startDate'] = date('Y-m-d', strtotime($dateRange['startDate'].' -1 year'));
            $inputRequest['dateRange']['endDate'] = date('Y-m-d', strtotime($dateRange['endDate'].' -1 year'));
            $yoyData = $this->getTopStatsData($inputRequest);

            foreach ($result as $key => $count) {
                $momGrowth[$key] = isset($momData[$key])?number_format(((($count - $momData[$key])*100)/$momData[$key]), 0):100;
                $yoyGrowth[$key] = isset($yoyData[$key])?number_format(((($count - $yoyData[$key])*100)/$yoyData[$key]), 0):100;
            }
        }
        //_p($result);_p($momGrowth);_p($yoyGrowth);die;
        switch ($inputRequest['aspect']) {
            case 'topPages':
                foreach ($result as $key => $count) {
                    $responses[] = array(
                        'ID'          => $key,
                        'PivotName'   => $key,
                        'ScalarValue' => $count,
                        'DeltaMOM'    => $momGrowth[$key],
                        'DeltaYOY'    => $yoyGrowth[$key]
                    );
                }
                //_p($responses);die;
                break;
            
            case 'topCategories':
                $this->CI->load->builder('CategoryBuilder','categoryList');
                $categoryBuilder = new CategoryBuilder;
                $this->categoryRepository = $categoryBuilder->getCategoryRepository();
                $categoryObject = $this->categoryRepository->findMultiple(array_keys($result));
                foreach ($result as $key => $count) {
                    $responses[] = array(
                        'ID'          => $key,
                        'PivotName'   => (isset($categoryObject[$key]))?$categoryObject[$key]->getName():"",
                        'ScalarValue' => $count,
                        'DeltaMOM'    => $momGrowth[$key],
                        'DeltaYOY'    => $yoyGrowth[$key]
                    );
                }
                break;

            case 'topSubcategories':
                $this->CI->load->builder('CategoryBuilder','categoryList');
                $categoryBuilder = new CategoryBuilder;
                $this->categoryRepository = $categoryBuilder->getCategoryRepository();
                $categoryObject = $this->categoryRepository->findMultiple(array_keys($result));
                foreach ($result as $key => $count) {
                    $responses[] = array(
                        'ID'          => $key,
                        'PivotName'   => (isset($categoryObject[$key]))?$categoryObject[$key]->getName():"Unknown",
                        'ScalarValue' => $count,
                        'DeltaMOM'    => $momGrowth[$key],
                        'DeltaYOY'    => $yoyGrowth[$key]
                    );
                }
                
                break;

            case 'topListings':
                if($inputRequest['misSource'] == "STUDY ABROAD"){
                    $this->CI->load->builder('listing/ListingBuilder');
                    $listingBuilder = new ListingBuilder();
                    $this->universityRepo = $listingBuilder->getUniversityRepository();
                    $universityObj = $this->universityRepo->findMultiple(array_keys($result));

                    foreach ($result as $key => $count) {
                        $responses[] = array(
                            'ID'          => $key,
                            'PivotName'   => (isset($universityObj[$key]))?$universityObj[$key]->getName():"Unknown",
                            'CountryName' => (isset($universityObj[$key]))?$universityObj[$key]->getLocation()->getCountry()->getName():"Unknown",
                            'URL' => (isset($universityObj[$key]))?$universityObj[$key]->getURL():"Unknown",
                            'ScalarValue' => $count,
                            'DeltaMOM'    => $momGrowth[$key],
                            'DeltaYOY'    => $yoyGrowth[$key]
                        );
                    }
                }else if($inputRequest['misSource'] == "DOMESTIC"){
                    //institute_id
                    $this->CI->load->builder("nationalInstitute/InstituteBuilder");
                    $instituteBuilder = new InstituteBuilder();
                    $instituteRepository = $instituteBuilder->getInstituteRepository();
                    $listingObj = $instituteRepository->findMultiple(array_keys($result));
                    foreach ($result as $key => $count) {
                        $responses[] = array(
                            'ID'          => $key,
                            'PivotName'   => (isset($listingObj[$key]))?$listingObj[$key]->getName():"Unknown",
                            'CountryName' => "India",
                            'URL' => (isset($listingObj[$key]))?$listingObj[$key]->getURL():"Unknown",
                            'ScalarValue' => $count,
                            'DeltaMOM'    => $momGrowth[$key],
                            'DeltaYOY'    => $yoyGrowth[$key]
                        );
                    }
                }
                    
                break;

            case 'topCountries':
                $this->CI->load->builder('LocationBuilder','location');
                $locationBuilder    = new LocationBuilder();
                $this->locationRepository = $locationBuilder->getLocationRepository();

                foreach ($result as $key => $count) {
                    $countryObj = $this->locationRepository->findCountry($key);
                    $responses[] = array(
                        'ID'          => $key,
                        'PivotName'   => (is_object($countryObj))?$countryObj->getName():"Unknown",
                        'ScalarValue' => $count,
                        'DeltaMOM'    => $momGrowth[$key],
                        'DeltaYOY'    => $yoyGrowth[$key]
                    );
                }
                break;

            case 'topCities':
                $this->CI->load->builder('LocationBuilder','location');
                $locationBuilder    = new LocationBuilder();
                $this->locationRepository = $locationBuilder->getLocationRepository();

                $cityObj = $this->locationRepository->findMultipleCities(array_keys($result));

                foreach ($result as $key => $count) {
                    $responses[] = array(
                        'ID'          => $key,
                        'PivotName'   => (isset($cityObj[$key]))?$cityObj[$key]->getName():"Unknown",
                        'ScalarValue' => $count,
                        'DeltaMOM'    => $momGrowth[$key],
                        'DeltaYOY'    => $yoyGrowth[$key]
                    );
                }
                break;

            case 'topStreams':
                $this->CI->load->builder('ListingBaseBuilder', 'listingBase');
                $listingBaseBuilder   = new ListingBaseBuilder();
                $streamRepository     = $listingBaseBuilder->getStreamRepository();

                $streamObj = $streamRepository->findMultiple(array_keys($result));
                //_p($streamObj);die;
                foreach ($result as $key => $count) {
                    $responses[] = array(
                        'ID'          => $key,
                        'PivotName'   => (isset($streamObj[$key]))?$streamObj[$key]->getName():"Unknown",
                        'ScalarValue' => $count,
                        'DeltaMOM'    => $momGrowth[$key],
                        'DeltaYOY'    => $yoyGrowth[$key]
                    );
                }
                break;

            default:
                return array();
                break;
        }
        //_p($responses);die;
        echo json_encode($responses);
    }

    function getTopTrafficStats($inputRequest){
        //_p($inputRequest);die;
        $responses = array();
        $result = $this->getTopStatsDataForTraffic($inputRequest);
        //_p($result);die;
        if($inputRequest['showGrowth'] == true){
            // mom data
            $dateRange = $inputRequest['dateRange'];
            $inputRequest['dateRange']['startDate'] = date('Y-m-d', strtotime($dateRange['startDate'].' -30 days'));
            $inputRequest['dateRange']['endDate'] = date('Y-m-d', strtotime($dateRange['endDate'].' -30 days'));
            $momData = $this->getTopStatsDataForTraffic($inputRequest);
            
            // yoy data
            $inputRequest['dateRange']['startDate'] = date('Y-m-d', strtotime($dateRange['startDate'].' -1 year'));
            $inputRequest['dateRange']['endDate'] = date('Y-m-d', strtotime($dateRange['endDate'].' -1 year'));
            $yoyData = $this->getTopStatsDataForTraffic($inputRequest);

            foreach ($result as $key => $count) {
                $momGrowth[$key] = isset($momData[$key])?number_format(((($count - $momData[$key])*100)/$momData[$key]), 0):100;
                $yoyGrowth[$key] = isset($yoyData[$key])?number_format(((($count - $yoyData[$key])*100)/$yoyData[$key]), 0):100;
            }
        }

        //_p($result);_p($momGrowth);_p($yoyGrowth);die;
        switch ($inputRequest['aspect']) {
            case 'topPages':
                foreach ($result as $key => $count) {
                    $responses[] = array(
                        'ID'          => $key,
                        'PivotName'   => $key,
                        'ScalarValue' => $count,
                        'DeltaMOM'    => $momGrowth[$key],
                        'DeltaYOY'    => $yoyGrowth[$key]
                    );
                }
                break;
            
            case 'topCategories':
                $this->CI->load->builder('CategoryBuilder','categoryList');
                $categoryBuilder = new CategoryBuilder;
                $this->categoryRepository = $categoryBuilder->getCategoryRepository();
                $categoryObject = $this->categoryRepository->findMultiple(array_keys($result));
                foreach ($result as $key => $count) {
                    $responses[] = array(
                        'ID'          => $key,
                        'PivotName'   => (isset($categoryObject[$key]))?$categoryObject[$key]->getName():"",
                        'ScalarValue' => $count,
                        'DeltaMOM'    => $momGrowth[$key],
                        'DeltaYOY'    => $yoyGrowth[$key]
                    );
                }
                break;

            case 'topSubcategories':
                $this->CI->load->builder('CategoryBuilder','categoryList');
                $categoryBuilder = new CategoryBuilder;
                $this->categoryRepository = $categoryBuilder->getCategoryRepository();
                $categoryObject = $this->categoryRepository->findMultiple(array_keys($result));
                foreach ($result as $key => $count) {
                    $responses[] = array(
                        'ID'          => $key,
                        'PivotName'   => (isset($categoryObject[$key]))?$categoryObject[$key]->getName():"Unknown",
                        'ScalarValue' => $count,
                        'DeltaMOM'    => $momGrowth[$key],
                        'DeltaYOY'    => $yoyGrowth[$key]
                    );
                }
                
                break;

            case 'topCountries':
                foreach ($result as $key => $count) {
                    $responses[] = array(
                        'ID'          => $key,
                        'PivotName'   => $key,
                        'ScalarValue' => $count,
                        'DeltaMOM'    => $momGrowth[$key],
                        'DeltaYOY'    => $yoyGrowth[$key]
                    );
                }
                break;

            case 'topCities':
                foreach ($result as $key => $count) {
                    $responses[] = array(
                        'ID'          => $key,
                        'PivotName'   => $key,
                        'ScalarValue' => $count,
                        'DeltaMOM'    => $momGrowth[$key],
                        'DeltaYOY'    => $yoyGrowth[$key]
                    );
                }
                break;
                break;

            case 'topStreams':
                $this->CI->load->builder('ListingBaseBuilder', 'listingBase');
                $listingBaseBuilder   = new ListingBaseBuilder();
                $streamRepository     = $listingBaseBuilder->getStreamRepository();

                $streamObj = $streamRepository->findMultiple(array_keys($result));
                //_p($streamObj);die;
                foreach ($result as $key => $count) {
                    $responses[] = array(
                        'ID'          => $key,
                        'PivotName'   => (isset($streamObj[$key]))?$streamObj[$key]->getName():"Unknown",
                        'ScalarValue' => $count,
                        'DeltaMOM'    => $momGrowth[$key],
                        'DeltaYOY'    => $yoyGrowth[$key]
                    );
                }
                break;

            default:
                return array();
                break;
        }
        //_p($responses);die;
        echo json_encode($responses);
    }

    function prepareOverviewToptiles($inputRequest){
        $elasticQueryLib = $this->CI->load->library("trackingMIS/elasticQueryLib");
        $this->_getSearchServerConnection($inputRequest['dataFor']);
        //_p($inputRequest);die;

        // for registration count
        if($inputRequest['dataFor'] == "registration"){
            $this->elasticQuery = $elasticQueryLib->prepareQueryForRegistration($inputRequest);
            $result = $this->clientCon->search($this->elasticQuery);
            return json_encode(number_format($result['hits']['total']));
        }

        if($inputRequest['dataFor'] == "traffic"){
            $response = array();
            $commonElasticQuery = $elasticQueryLib->prepareElasticSearchQuery($inputRequest,'session');

            // unique users
            $this->elasticQuery = $commonElasticQuery['elasticQuery'];
            $this->elasticQuery['body']['aggs'] = array(
                'usersCount' => array(
                    'cardinality' => array(
                        'field' => 'visitorId'
                    )
                )
            );

            $result = $this->clientCon->search($this->elasticQuery);
            $response['users'] = number_format($result['aggregations']['usersCount']['value']);

            // total sessions
            $response['sessions'] = number_format($result['hits']['total']);

            //avg session
            $this->elasticQuery = $commonElasticQuery['elasticQuery'];
            $aggregation = array(
                'totalDuration' => array('sum' => array('field' => 'duration')),
                'pageviews' => array('sum' => array('field' => 'pageviews'))
            );
            
            $this->elasticQuery['body']['aggs'] = $aggregation;
            $result = $this->clientCon->search($this->elasticQuery);
            $totalSession = $result['hits']['total'];
            $totalDuration = $result['aggregations']['totalDuration']['value'];

            $avgSessionDuration = number_format((($totalDuration)/(60*$totalSession)), 2, '.', '');
            $hourFormat = explode('.',number_format((($totalDuration)/($totalSession)), 2, '.', ''));
            $response['avgsessdur'] = date('i:s', mktime(0, 0, $hourFormat[0]));

            // total pageviews
            $commonElasticQuery = $elasticQueryLib->prepareElasticSearchQuery($inputRequest,'pageview');
            $this->elasticQuery = $commonElasticQuery['elasticQuery'];
            $result = $this->clientCon->search($this->elasticQuery);
            $response['pageviews'] = number_format($result['hits']['total']);

            return json_encode($response);
        }
    }

    function prepareOverviewDonutChart($inputRequest){
        $response = array();
        if($inputRequest['metric'] == "response"){
              $response = $this->prepareOverviewDonutChartForResponse($inputRequest);      
        }else if($inputRequest['metric'] == "registration"){
              $response = $this->prepareOverviewDonutChartForRegistration($inputRequest);
        }else if($inputRequest['metric'] == "traffic"){
              $response = $this->prepareOverviewDonutChartForTraffic($inputRequest);
        }
       
        return json_encode($response);
    }

    function prepareOverviewDonutChartForTraffic($inputRequest){
        //_p($inputRequest);die;
        $response = array();
        $elasticQueryLib = $this->CI->load->library("trackingMIS/elasticQueryLib");
        $ESQuery = $elasticQueryLib->prepareElasticSearchQuery($inputRequest,'session');
        $this->elasticQuery = $ESQuery['elasticQuery'];

        $fieldName = $inputRequest['aspect'];
        if($inputRequest['aspect'] == "trafficSource"){
            $fieldName = 'source';
        }else if($inputRequest['aspect'] == "trafficDevice"){
            $fieldName = 'isMobile';
        }
        
        $result = $this->_addAggerateToESQuery("split",$fieldName,1, 'no',"yes");
        
        $totalCount = 0;
        foreach ($result as $key => $value) {
            $totalCount += $value['value'];
        }

        foreach ($result as $key => $value) {
            $response[$inputRequest['aspect']][] = array(
                'Percentage' => number_format(($value['value']*100)/$totalCount, 2),
                'PivotName' => $value['name'],
                'ScalarValue' => $value['value']
            );
        }

        return $response;
    }

    function prepareOverviewDonutChartForRegistration($inputRequest){
        //_p($inputRequest);die;
        $response = array();
        $elasticQueryLib = $this->CI->load->library("trackingMIS/elasticQueryLib");

        $fieldName = $inputRequest['aspect'];
        if($inputRequest['aspect'] == "registrationDevice"){
            $fieldName = 'sourceApplication';
        }else if($inputRequest['aspect'] == "paidFree"){
            $fieldName = 'source';
        }else if($inputRequest['aspect'] == "registrationSource"){
            $fieldName = 'trafficSource';
        }

        $this->elasticQuery = $elasticQueryLib->prepareQueryForRegistration($inputRequest);
        $result = $this->_addAggerateToESQuery("split",$fieldName,1, 'no',"yes");
        
        $totalCount = 0;
        foreach ($result as $key => $value) {
            $totalCount += $value['value'];
        }

        foreach ($result as $key => $value) {
            $response[$inputRequest['aspect']][] = array(
                'Percentage' => number_format(($value['value']*100)/$totalCount, 2),
                'PivotName' => $value['name'],
                'ScalarValue' => $value['value']
            );
        }

        return $response;
    }

    function prepareOverviewDonutChartForResponse($inputRequest){
        //_p($inputRequest);die;
        $response = array();
        $elasticQueryLib = $this->CI->load->library("trackingMIS/elasticQueryLib");
        $this->_getSearchServerConnection($inputRequest['metric']);

        $fieldName = $inputRequest['aspect'];
        if($inputRequest['aspect'] == "rmcResponseType"){
            $inputRequest['metric'] = "RMC";
            $fieldName = 'is_response_paid';
        }else if($inputRequest['aspect'] == "pivotType"){
            $fieldName = 'is_response_paid';
        }else if($inputRequest['aspect'] == "session"){
            $fieldName = 'response_source';
        }

        $this->elasticQuery = $elasticQueryLib->prepareQueryForResponse($inputRequest);
        //_p(json_encode($this->elasticQuery));die;
        $result = $this->_addAggerateToESQuery("split",$fieldName,1, 'no',"yes");
        $totalCount = 0;
        foreach ($result as $key => $value) {
            $totalCount += $value['value'];
            if($fieldName == "is_response_paid"){
                if($value['name'] == '1'){
                    $result[$key]['name'] = 'Paid';
                }else{
                    $result[$key]['name'] = 'Free';
                }
            }
        }

        foreach ($result as $key => $value) {
            $response[$inputRequest['aspect']][] = array(
                'Percentage' => number_format(($value['value']*100)/$totalCount, 2),
                'PivotName' => $value['name'],
                'ScalarValue' => $value['value']
            );
        }
        return $response;
    }

    function getTopStatsData($inputRequest){
        $elasticQueryLib = $this->CI->load->library("trackingMIS/elasticQueryLib");
        $this->elasticQuery = $elasticQueryLib->prepareQueryForResponse($inputRequest);
        //_p(json_encode($this->elasticQuery));die;
        switch ($inputRequest['aspect']) {
            case 'topPages':
                $elasticDocField = "page";
                break;
            
            case 'topCategories':
                $elasticDocField = "response_category_id";
                break;

            case 'topSubcategories':
                $elasticDocField = "response_sub_category_id";
                break;

            case 'topListings':
                if($inputRequest['misSource'] == "STUDY ABROAD"){
                    $elasticDocField = "response_university_id";    
                }else if($inputRequest['misSource'] == "DOMESTIC"){
                    $elasticDocField = "institute_id";
                }
                break;

            case 'topCountries':
                $elasticDocField = "response_country_id";
                break;

            case 'topCities':
                $elasticDocField = "response_city_id";
                break;

            case 'topStreams':
                $elasticDocField = "response_stream_id";
                break;

            default:
                return array();
                break;
        }
        if($elasticDocField != ""){
            $this->elasticQuery['body']['aggs']['top_aspect']['terms'] = array(
                'field' => $elasticDocField,
                'size' => $inputRequest['topNResult'],
                "order" => array("_count" => "desc")
            );
        }

        $this->_getSearchServerConnection($inputRequest['metric']);
        $result = $this->clientCon->search($this->elasticQuery);
        //_p($result['aggregations']['top_aspect']['buckets']);die;
        $response = array();
        foreach ($result['aggregations']['top_aspect']['buckets'] as $key => $data) {
            $response[$data['key']] = $data['doc_count'];
        }
        return $response;
        //_p(json_encode($this->elasticQuery));die;
    }

    function getTopStatsDataForRegistration($inputRequest){
        $elasticQueryLib = $this->CI->load->library("trackingMIS/elasticQueryLib");
        $this->elasticQuery = $elasticQueryLib->prepareQueryForRegistration($inputRequest);
        //echo json_encode($this->elasticQuery);die;
        switch ($inputRequest['aspect']) {
            case 'topPages':
                $elasticDocField = "pageIdentifier";
                break;
            
            case 'topCategories':
                $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][] =array("range" => array("categoryId" => array("gt"=>1)));
                $elasticDocField = "categoryId";
                break;

            case 'topSubcategories':
                $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'][] =array("range" => array("subCategoryId" => array("gt"=>1)));
                $elasticDocField = "subCategoryId";
                break;

            case 'topCountries':
                $elasticDocField = "countryId";
                break;

            case 'topCities':
                $elasticDocField = "cityId";
                break;

            case 'topStreams':
                $elasticDocField = "stream";
                break;

            case 'topDesiredCountries':
                $elasticDocField = "prefCountry";
                break;

            default:
                return array();
                break;
        }
        if($elasticDocField != ""){
            if($elasticDocField == "prefCountry"){
                $this->elasticQuery['body']['aggs']['top_aspect']['terms'] = array(
                    'field' => $elasticDocField."1",
                    'size' => ELASTIC_AGGS_SIZE,
                    "order" => array("_count" => "desc")
                );

                $topDesiredCountries1 = $this->clientCon->search($this->elasticQuery);
                //_p($topDesiredCountries1);die;
                $this->elasticQuery['body']['aggs']['top_aspect']['terms']['field'] = $elasticDocField."2";
                $topDesiredCountries2 = $this->clientCon->search($this->elasticQuery);

                $this->elasticQuery['body']['aggs']['top_aspect']['terms']['field'] = $elasticDocField."3";
                $topDesiredCountries3 = $this->clientCon->search($this->elasticQuery);
                
                $topDesiredCountries = array();
                foreach ($topDesiredCountries1['aggregations']['top_aspect']['buckets'] as $key => $value) {
                    $topDesiredCountries[$value['key']] = $value['doc_count'];
                }
                foreach ($topDesiredCountries2['aggregations']['top_aspect']['buckets'] as $key => $value) {
                    $topDesiredCountries[$value['key']] += $value['doc_count'];   
                }
                foreach ($topDesiredCountries3['aggregations']['top_aspect']['buckets'] as $key => $value) {
                    $topDesiredCountries[$value['key']] += $value['doc_count'];
                }

                arsort($topDesiredCountries);
                $i = 0;
                $topTenDesiredCountries = array();
                foreach ($topDesiredCountries as $key => $value) {
                    $response[$key] = $value;
                    if($i++ == 9)
                        break;
                }
            }else{
                $this->elasticQuery['body']['aggs']['top_aspect']['terms'] = array(
                    'field' => $elasticDocField,
                    'size' => $inputRequest['topNResult'],
                    "order" => array("_count" => "desc")
                );

                //echo json_encode($this->elasticQuery);die;
                $result = $this->clientCon->search($this->elasticQuery);
                $response = array();
                foreach ($result['aggregations']['top_aspect']['buckets'] as $key => $data) {
                    $response[$data['key']] = $data['doc_count'];
                }
            }
        }
        return $response;
        //_p(json_encode($this->elasticQuery));die;
    }

    function getTopStatsDataForTraffic($inputRequest){
        $elasticQueryLib = $this->CI->load->library("trackingMIS/elasticQueryLib");
        $ESQuery = $elasticQueryLib->prepareElasticSearchQuery($inputRequest,'session');
        $this->elasticQuery = $ESQuery['elasticQuery'];
        //echo json_encode($this->elasticQuery);die;
        switch ($inputRequest['aspect']) {
            case 'topPages':
                $elasticDocField = "landingPageDoc.pageIdentifier";
                break;
            
            case 'topCategories':
                if(!($result['hasNestedQuery'] == 1)){
                    $this->elasticQuery['body']['query']['bool']['filter'][0]['bool']['must'][] =array("range" => array("landingPageDoc.categoryId" => array("gt"=>1)));
                }
                
                $elasticDocField = "landingPageDoc.categoryId";
                break;

            case 'topSubcategories':
                if(!($result['hasNestedQuery'] == 1)){
                    $this->elasticQuery['body']['query']['bool']['filter'][0]['bool']['must'][] =array("range" => array("landingPageDoc.subCategoryId" => array("gt"=>1)));
                }
                $elasticDocField = "landingPageDoc.subCategoryId";
                break;

            case 'topCountries':
                $elasticDocField = "geocountry";
                break;

            case 'topCities':
                $elasticDocField = "geocity";
                break;

            case 'topStreams':
                $elasticDocField = "stream";
                break;

            default:
                return array();
                break;
        }
        if($elasticDocField != ""){
            if($elasticDocField == "stream"){
                $this->elasticQuery['body']['aggs'] = array(
                    "landingPageDoc.hierarchy" => array(
                        "nested" => array(
                            "path" => "landingPageDoc.hierarchy"
                        ),
                        "aggs" => array(
                            "top_aspect" => array(
                                "terms" => array(
                                    "field" => "landingPageDoc.hierarchy.streamId",
                                    "size" => $inputRequest['topNResult']+1,
                                    "order" => array("_count" => "desc")
                                )
                            )
                        )
                    )
                );

                $result = $this->clientCon->search($this->elasticQuery);
                $response = array();
                $bucketCount = 0;
                foreach ($result['aggregations']['landingPageDoc.hierarchy']['top_aspect']['buckets'] as $key => $data) {
                    if($bucketCount == 10){
                        break;
                    }
                    if($data['key'] == 0){
                        continue;
                    }
                    $bucketCount ++;
                    $response[$data['key']] = $data['doc_count'];
                }
            }else{
                $this->elasticQuery['body']['aggs']['top_aspect']['terms'] = array(
                    'field' => $elasticDocField,
                    'size' => $inputRequest['topNResult'],
                    "order" => array("_count" => "desc")
                );

                //echo json_encode($this->elasticQuery);die;
                $result = $this->clientCon->search($this->elasticQuery);
                $response = array();
                foreach ($result['aggregations']['top_aspect']['buckets'] as $key => $data) {
                    $response[$data['key']] = $data['doc_count'];
                }
            }
        }
        //_p($response);die;
        return $response;
    }

    public function getCourseListingsByClient($clientId){
        if(!is_numeric($clientId)){
            return array();
        }
        $overviewModel = $this->CI->load->model('overview_model');
        $result = $overviewModel->getCourseListingsByClient($clientId);
        $packtypes = array();  // 0(Free),1(GOLD_SL_LISTINGS_BASE_PRODUCT_ID),7(BRONZE_LISTINGS_BASE_PRODUCT_ID),375(GOLD_ML_LISTINGS_BASE_PRODUCT_ID)
        $packtypes[0] = "Free";
        $packtypes[1] = "Paid";
        $packtypes[7] = "Paid";
        $packtypes[375] = "Paid";

        $courseListings = array();
        /*_p($result);
        usort($result,function($c1,$c2){
            return (strcasecmp($c1['listing_title'],$c2['listing_title']));
        });
        _p($result);die;*/
        //_p($result);die;
        foreach ($result as $key => $listingDetails) {
            $courseListings[$listingDetails['listing_title']] = array(
                                                                    "id" =>$listingDetails['listing_type_id'],
                                                                    "title" => $listingDetails['listing_title']." ( Id : ".$listingDetails['listing_type_id']." , Current Subscription : ".$packtypes[$listingDetails['pack_type']]."(".$listingDetails['pack_type']."))"
                                                                );
        }
/*        _p($courseListings);die;


        foreach ($result as $key => $listingDetails) {
            $courseListings[$listingDetails['listing_type_id']] = $listingDetails['listing_title']." ( Id : ".$listingDetails['listing_type_id']." , Current Subscription : ".$packtypes[$listingDetails['pack_type']]."(".$listingDetails['pack_type']."))";
        }*/
        //_p($courseListings);die;
        return $courseListings;
    }

    public function getUserDetails($clientList){
        $userDetails = array();
        if(is_array($clientList) && count($clientList)>0 ){
            $overviewModel = $this->CI->load->model('overview_model');
            $result = $overviewModel->getUserDetails($clientList);
            foreach ($result as $key => $value) {
                $userDetails[$value['userId']] = array("email" => $value['email'], "displayName" => $value['displayname']);
            }
            //_p($result);die;
        }
        return $userDetails;
    }

    public function getPageGroupPageList($pageGroupList, $site){
        $groupPageList = array();
        if(is_array($pageGroupList)){
            $overviewModel = $this->CI->load->model('overview_model');
            $result = $overviewModel->getPageGroupPageList($pageGroupList, $site);
            //_p($result);die;
            foreach ($result as $key => $pageDetails) {
                $groupPageList[$pageDetails['pageGroup']][$pageDetails['page']] = $this->convertToWords($pageDetails['page']);
            }
            //_p($groupPageList);die;
        }
        return $groupPageList;
    }

    public function getShikshaPageGroupsList($team){
        $pageGroupList = array();
        $overviewModel = $this->CI->load->model('overview_model');
        $result = $overviewModel->getShikshaPageGroupsList($team);
        foreach ($result as $key => $trackingDetails) {
            $pageGroupList[$trackingDetails['pageGroup']] = $this->convertToWords($trackingDetails['pageGroup']);
        }
        //_p($pageGroupList);die;
        return $pageGroupList;
    }

    public function getPageIdentifiersList($team){
        $pageList = array();
        $overviewModel = $this->CI->load->model('overview_model');
        $result = $overviewModel->getPageIdentifiersList($team);
        //_p($result);die;
        foreach ($result as $key => $trackingDetails) {
            $pageList[$trackingDetails['page']] = $this->convertToWords($trackingDetails['page']);
        }
        //_p($pageList);die;
        return $pageList;
    }
	
    private function _getSassistantShownCount($clientCon){
        $elasticQuery = array(
            'index' => "shiksha_abtest",
            'type'  => 'abtest',
            'body'  => array(
                'size' => 0
            )
        );
        $elasticQuery['body']['aggs']['Data']['terms'] = array(
            "field" => "abVarient.keyword",
            "size" => 1000000
        );
        //_p(json_encode($elasticQuery));die;
        $result = $clientCon->search($elasticQuery);
        //$total = $result['hits']['total'];
        $shownCount = 0;
        foreach ($result['aggregations']['Data']['buckets'] as $key => $value) {
            if($value['key'] == 2){
                $shownCount = $value['doc_count'];
            }
            
        }
        return $shownCount;
    }

    private function _getSassistantUsageCount($clientCon){
        $elasticQuery = array(
            'index' => SHIKSHA_ASSISTANT_INDEX_NAME_REALTIME_SEARCH,
            'type'  => 'chat',
            'body'  => array(
                'size' => 0
            )
        );
        //$mustFilters[] = array("range" => array("queryTime" => array("gte"=> "2019-05-03T16:00:00")));
        $elasticQuery['body']['query']['bool']['filter']['bool']['must']['range']['queryTime']['gte'] = "2019-05-03T14:47:00";
        $elasticQuery['body']['aggs']['Data']['cardinality']['field'] = 'sessionId' ;
        //_p(json_encode($elasticQuery));die;
        $result = $clientCon->search($elasticQuery);
        return $result['aggregations']['Data']['value'];
    }

    function checkConversionHistoryBySessionId($type,$data){
        //_p($type);_p($data);die;
        $chatConversations = array();
        //_p($sessionId);die;
        $ESConnectionLib = $this->CI->load->library('trackingMIS/elasticSearch/ESConnectionLib');
        $clientCon = $ESConnectionLib->getESServerConnectionWithCredentials();


        if($type == "hours"){
            //$assistantShown = $this->_getSassistantShownCount($clientCon);
            //_p($assistantShown);

            //$assistantUsagesCount = $this->_getSassistantUsageCount($clientCon);
            //_p($assistantUsagesCount);die;
        }
        

        $elasticQuery = array(
            'index' => SHIKSHA_ASSISTANT_INDEX_NAME_REALTIME_SEARCH,
            'type'  => 'chat',
            'body'  => array(
                'size' => 500
            )
        );
        $mustFilters = array();

        if($type == "session" && $data != ""){
            $mustFilters[] = array("term" => array("sessionId" => $data));
        }else if($type == "hours"){

            if($data != ""){
                $startDate =str_replace(" ", "T", date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s")."-".$data." hours")));
                $mustFilters[] = array("range" => array("queryTime" => array("gte"=> $startDate)));
            }
        }else{
            $mustFilters[] = array("range" => array("queryTime" => array("gte"=> "2019-05-03T16:00:00")));
        }
        if(count($mustFilters) >0){
            $elasticQuery['body']['query']['bool']['filter']['bool']['must'][] = $mustFilters;        
        }

        $elasticQuery['body']['sort']['queryTime']['order'] = 'asc';
        if($type == "hours"){
            $elasticQuery['body']['sort']['queryTime']['order'] = 'desc';    
        }
        
        
        $result = $clientCon->search($elasticQuery);
        $chatConversations = array();
        //$chatConversations['assistantShown'] = $assistantShown;
        //$chatConversations['assistantUsagesCount'] = $assistantUsagesCount;
        if($result['hits']['total'] >0){
            if($type == "hours"){
                foreach ($result['hits']['hits'] as $key => $value) {
                    if(isset($chatConversations['history'][$value['_source']['sessionId']])){
                        $chatConversations['history'][$value['_source']['sessionId']]['count'] ++;
                        $chatConversations['history'][$value['_source']['sessionId']]['userQuery'] = $value['_source']['userQuery'];
                        $chatConversations['history'][$value['_source']['sessionId']]['queryType'] = $value['_source']['queryType'];
                        $chatConversations['history'][$value['_source']['sessionId']]['queryTime'] = str_replace("T", " ", $value['_source']['queryTime']);
                    }else{
                        $chatConversations['history'][$value['_source']['sessionId']] = array(
                            'userQuery' => $value['_source']['userQuery'],
                            'queryType' => $value['_source']['queryType'],
                            'queryTime' => str_replace("T", " ", $value['_source']['queryTime']),
                            'count' => 1
                        );
                    }
                }
            }else{
                foreach ($result['hits']['hits'] as $key => $value) {                    
                    $responsesCount = count($value['_source']['apiResponseData']['data']['responses'])>0?count($value['_source']['apiResponseData']['data']['responses']):0;
                    if($value['_source']['apiResponseData']['data']['promptResponse']['promptType'] == "disambiguation"){
                        $responsesCount .= " (disambiguation)";
                    }
                    $chatConversations['history'][] = array(
                        'userQuery' => $value['_source']['userQuery'],
                        'queryType' => $value['_source']['queryType'],
                        'opinionFactual' => $value['_source']['apiResponseData']['data']['predictedTripletsList'][0]['opinionFactual'],
                        'attribute' => $value['_source']['apiResponseData']['data']['predictedTripletsList'][0]['attribute'],
                        'responsesCount' => $responsesCount,
                        'queryTime' => str_replace("T", " ", $value['_source']['queryTime']),
                    );
                }
            }
                
        }
        //_p($chatConversations);die;
        return $chatConversations;
    }

    public function getUserHistoryByUsedId($userId){
        $pageviewsCount = 0;
        $fieldList = array();
        $returnData = array();
        $ESConnectionLib = $this->CI->load->library('trackingMIS/elasticSearch/ESConnectionLib');
        $timeout = 20; // in seconds
        $clientCon = $ESConnectionLib->getShikshaESServerConnection(true,$timeout);
        $elasticQuery = array(
            'index' => "shiksha_pageviews_m2018*,shiksha_pageviews_m2019*",
            'type'  => 'pageview',
            'body'  => array(
                'size' => 1000000,
                '_source' => array("sessionId", "visitorId")
            )
        );

         //$mustFilters[] = array("range" => array("queryTime" => array("gte"=> "2019-05-03T16:00:00")));
        $elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['range']['visitTimeIST']['gte'] = "2018-01-01T00:00:00";
        $elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['term']['userId'] = $userId;
        //_p(json_encode($elasticQuery));die;

        try{
            $result = $clientCon->search($elasticQuery);
        }
        catch(Exception $e){
            $returnData = array("error" => 1, "message" => "Query taking longer than ".$timeout." seconds to process this request.");
            return $returnData;
        }
        //$result = $clientCon->search($elasticQuery);
        $firstQueryTime = $result['took'];
        if($result['hits']['total'] >0 ){
            $sessions = array();
            $visitots = array();
            $result = $result['hits']['hits'];
            foreach ($result as $key => $value) {
                $sessions[$value['_source']['sessionId']] = $value['_source']['sessionId'];
                $visitots[$value['_source']['visitorId']] = $value['_source']['visitorId'];
            }
            unset($result);
            $sessions = array_keys($sessions);
            $visitots = array_keys($visitots);
            //_p($sessions);_p($visitots);

            $elasticQuery = array();
            $elasticQuery = array(
                'index' => "shiksha_pageviews_m2018*,shiksha_pageviews_m2019*",
                'type'  => 'pageview',
                'body'  => array(
                    'size' => 100,
                    '_source' => array("sessionId", "visitorId", "pageURL", "pageIdentifier","pageEntityId", "isStudyAbroad","isMobile","visitTimeIST", "source","childPageIdentifier")
                )
            );

            $elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['range']['visitTimeIST']['gte'] = "2018-01-01T00:00:00";
            //$elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['range']['visitTimeIST']['gte'] = "2018-03-15T00:00:00";
            //$elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['range']['visitTimeIST']['gte'] = "2018-03-20T00:00:00";
            //$elasticQuery['body']['query']['bool']['filter']['bool']['should'][]['terms']['sessionId']= $sessions;
            $elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['terms']['visitorId'] = $visitots;
            //$elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['term']['userId'] = $userId;
            $elasticQuery['body']['sort']['visitTimeIST']['order'] = "asc";
            //_p(($elasticQuery));die;
            try{
                $result = $clientCon->search($elasticQuery);
            }
            catch(Exception $e){
                $returnData = array("error" => 1, "message" => "Query taking longer than ".$timeout." seconds to process this request.");
                return $returnData;
            }
            //$result = $clientCon->search($elasticQuery);
            //_p($result);die;
            $secondQueryTime = $result['took'];
            $esResposne = $result['hits']['hits'];

            //_p($totalData);die;
            $userHistory = array();
            $tempData = array();
            foreach ($esResposne as $key => $value) {
                $doc = $value['_source'];
                //_p($doc['visitTimeIST']);die;
                $doc['visitTimeIST'] = str_replace("T", " ", $doc['visitTimeIST']);
                $tempData[] = $doc;
            }
            unset($esResposne);

            /*usort($tempData, function($a,$b){
                return(strtotime($a['visitTimeIST']) <=strtotime($b['visitTimeIST'])?-1:1);
            });*/

            
            foreach ($tempData as $key => $doc) {
                $pageviewsCount++;                
                $userHistory[$doc['visitorId']][$doc['sessionId']][] = array(
                    "S. No." => $pageviewsCount, 
                    "Sess No" => $sessionNo,
                    "pageURL" => $doc["pageURL"],
                    "Page Group" => $this->convertToWords($doc["pageIdentifier"]),
                    "Entity Id" => $doc["pageEntityId"],
                    "source <br> device <br> site" => $doc["source"]."<br>".(($doc["isMobile"] == "no")?"Desktop":"Mobile")."<br>".(($doc["isStudyAbroad"] == "no")?"Domestic": "Study Abroad"),
                    "Child Page" => $this->convertToWords($doc["childPageIdentifier"]),
                    "Visit Time" => str_replace("T", " ", $doc["visitTimeIST"])
                );
            }

            $sessionNo = 1;
            foreach ($userHistory as $visitorId => $sessions) { 
                foreach ($sessions as $sessionId => $pageviews) {
                    foreach ($pageviews as $index => $pageDetails) {
                        $userHistory[$visitorId][$sessionId][$index]['Sess No'] = $sessionNo;
                    }
                    $sessionNo++;
                }
            }
            //_p($userHistory);die;

            $fieldList = array(
                array("field"=>"S. No.","width" => "5%"),
                array("field"=>"Sess No","width" => "5%"),
                array("field"=>"pageURL","width" => "46%"),
                array("field"=>"Page Group","width" => "10%"),
                array("field"=>"Entity Id","width" => "7%"),
                array("field"=>"source <br> device <br> site","width" => "8%"),
                array("field"=>"Child Page","width" => "9%"),
                array("field"=>"Visit Time","width" => "9%"),
            );
        }
        $returnData = array(
            "firstQueryTimeTaken(ms)" => $firstQueryTime,
            "secondQueryTimeTaken(ms)" => $secondQueryTime,
            "pageviewsCount" => $pageviewsCount,
            "userHistory" => $userHistory,
            "fieldList" => $fieldList
        );
        
        return $returnData;
    }  

    public function getUserDetailsByEmail($email = ""){
        if(empty($email)){
            return;
        }

        $overviewModel = $this->CI->load->model('overview_model');
        $result = $overviewModel->getUserDetailsByEmail($email);
        $userDetails = $result[0];
        return $userDetails;
    }  
}
    
