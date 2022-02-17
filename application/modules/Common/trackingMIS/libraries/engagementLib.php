<?php
require_once('vendor/autoload.php');

class engagementLib {
    private $clientCon;
    private $clientParams;
    //private static $TRAFFICDATA_PAGEVIEWS, $TRAFFICDATA_SESSIONS;

    public function __construct(){
        $this->CI = & get_instance();
        $this->usergroupAllowed = array("shikshaTracking");
        $this->MISCommonLib = $this->CI->load->library('trackingMIS/MISCommonLib');
        $this->clientCon = $this->MISCommonLib->_getSearchServerConnection();
        /*engagementLib::$TRAFFICDATA_PAGEVIEWS = 'trafficdata_pageviews_2';
        engagementLib::$TRAFFICDATA_SESSIONS  = 'trafficdata_sessions_3';*/
    }

    private function _getSearchServerConnection() {
    	$this->clientParams = array();
        //$this->clientParams['hosts'] = array('10.10.16.72');
        $this->clientParams['hosts'] = array(ELASTIC_SEARCH_HOST);
        //        $this->clientParams['hosts'] = array('172.16.3.108');
        $this->clientCon = new Elasticsearch\Client($this->clientParams);
    }

    public function getEngagementData($dateRange, $pageName='', $extraData = array(''))
    {

        $categoryId = 0;
        $subcategoryId = 0;
        $isMobile = 'no';
        $isStudyAbroad = 'yes';
        $deviceType = 'all';

        if( count($extraData) > 0 && array_key_exists('National', $extraData)){
            $categoryId = $extraData['National']['category'];
            $subcategoryId = $extraData['National']['subcategory'];
            $viewType = $extraData['National']['viewType'];


            $isStudyAbroad = 'no';
        }

        $startDate = $dateRange['startDate'];
        $endDate = $dateRange['endDate'];

        $elasticQuery['index'] = MISCommonLib::$TRAFFICDATA_PAGEVIEWS;
        $elasticQuery['type']  = 'pageview';

        $elasticQuery['body']['size'] = 0;
        $elasticQuery['body']['query'] = array();
        if(strtolower($pageName) !='all'){
            $pageData = $this->MISCommonLib->getPageNameForDomestic($pageName);
            if($pageData){
                $elasticQuery['body']['query']['filtered']['query']['bool']['should'][0]['match']['pageIdentifier'] = $pageData;
            }
        }
        if($extraData['National']['deviceType'] != 'all'){
            if( $extraData['National']['deviceType'] == 'mobile'  ) {
                $elasticQuery['body']['query']['filtered']['filter']['and'][]['term']['isMobile'] = 'yes';
                $deviceType  = 'mobile';
            } else {
                $elasticQuery['body']['query']['filtered']['filter']['and'][]['term']['isMobile'] = 'no';
                $deviceType = 'desktop';
            }
        }

        $elasticQuery['body']['query']['filtered']['filter']['and'][]['term'] = array(
            'isStudyAbroad' => $isStudyAbroad
        );
        $elasticQuery['body']['query']['filtered']['filter']['and'][]['range'] = array(
            'visitTime' => array(
                'lte'  => $endDate.'T23:59:59',
                'gte' => $startDate.'T00:00:00'
            )
        );
        
        if(strtolower($pageName) !='all'){
            if($pageName != 'home' && $pageName !='institute' && $pageName != 'exam_calendar' && $categoryId != 'all' && $pageName != 'qna'){ // Dont consider category and subcategory if any of this fails
                if ($pageName == 'exam_calendar' || $pageName == 'article_home'){ // Consider only the subcategory
                    if($subcategoryId){
                        $elasticQuery['body']['query']['filtered']['filter']['and'][]['term']['subCategoryId'] = intval($subcategoryId);
                    }
                } else if ($pageName == 'article_detail') { // Consider only the category
                    if($categoryId){
                        $elasticQuery['body']['query']['filtered']['filter']['and'][]['term']['categoryId'] = intval($categoryId);
                    }
                } else { // Consider the normal (i.e.) other cases other than the ones mentioned above
                    $elasticQuery['body']['query']['filtered']['filter']['and'][]['term']['categoryId'] = intval($categoryId);
                    if($subcategoryId){
                        $elasticQuery['body']['query']['filtered']['filter']['and'][]['term']['subCategoryId'] = intval($subcategoryId);
                    }
                }
            }
        }

        $trafficSourceWiseGrouping = array(
            'sourceWise' => array(
                'terms' => array(
                    'field' => 'source',
                    'size' => 0
                )
            )
        );

        $subcategoryWiseGrouping = array(
            'subcategoryWise' => array(
                'terms' => array(
                    'field' => 'subCategoryId',
                    'size' => 0
                ),
                'aggs' => $trafficSourceWiseGrouping
            )
        );

        $categoryWiseGrouping = array(
            'categoryWise' => array(
                'terms' => array(
                    'field' => 'categoryId',
                    'size' => 0
                ),
                'aggs' => $subcategoryWiseGrouping
            )
        );

        $dateHistogram = array(
            'field' => 'visitTime',
            'interval' => $this->MISCommonLib->getPeriodForGrouping($dateRange, $viewType)
        );

        if( $deviceType == 'all' ) {
            if($pageName == 'home' || $pageName == 'institute'){
                $elasticQuery['body']['aggs']['pageViews'] = array(
                    'terms' => array(
                        'field'    => 'isMobile',
                    ),
                    'aggs' => array(
                        'dateWise' => array(
                            'date_histogram' => $dateHistogram
                        )
                    ),
                );
            } else {
                $elasticQuery['body']['aggs']['pageViews'] = array(
                    'terms' => array(
                        'field'    => 'isMobile',
                    ),
                    'aggs' => array(
                        'dateWise' => array(
                            'date_histogram' => $dateHistogram,
                            'aggs' => $categoryWiseGrouping
                        )
                    ),
                );
            }


        } else {
            if($pageName == 'home' || $pageName == 'institute'){
                $elasticQuery['body']['aggs']['pageViews'] = array(
                    'date_histogram' => $dateHistogram,
                );
            } else {
                $elasticQuery['body']['aggs']['pageViews'] = array(
                    'date_histogram' => $dateHistogram,
                    'aggs' => $categoryWiseGrouping
                );
            }
        }

        $elasticQuery['body']['sort']['visitTime']['order'] = 'asc';

        $result = $this->clientCon->search($elasticQuery);

        if(isset($extraData['National']['mode']) && $extraData['National']['mode'] == 'tile')
            return $result['hits']['total'];
        return ($result['aggregations']['pageViews']['buckets']);
    }


    /**
     * Get a page identifier corresponding to a page name and a device name.
     * This is applicable for domestic only.
     * @see getAbroadPageName($pageName)
     *
     * @param string $pageName The page name
     * @param string $deviceName The device name
     *
     * @return string The pagename corresponding to the device
     */
    private function getPageName($pageName, $deviceName) // TODO: Move this code to a common library. Make it probably a static function
    {
        $pageNames = array(
            'desktop' => array(
                'home'                        => 'Desktop_Homepage',
                'courselisting'               => 'courseDetailsPage',
                'coursehome'                  => 'Desktop_Course_Homepage_National',
                'institute'                   => 'instituteListingPage',
                'shortlist'                   => 'Desktop_ShortlistPage_National',
                'category'                    => 'Desktop_CategoryPage_National',
                'search'                      => 'Desktop_SearchPage_National',
                'exam'                        => 'Desktop_ExamPage_National',
                'browse'                      => 'Desktop_BrowsePage_National',
                'top_search'                  => 'Desktop_TopSearchPage_National',
                'ranking'                     => 'Desktop_RankingPage_National',
                'college_review_home'         => 'CollegeReviewsHomePage',
                'college_review_intermediate' => 'CollegeReviewsIntermediatePage',
                'campus_rep_home'             => 'CampusRepresentativeHomePage',
                'campus_rep_intermediate'     => 'CampusRepresentativeIntermediatePage',
                'compare_colleges'            => 'CompareColleges',
                'exam_calendar'               => 'ExamCalendar',
                'application_form'            => 'ApplicationFormHomePage',
                'career_home'                 => 'CareerHomepage',
                'career_detail'               => 'CareerDetailPage',
                'career_counselling'          => 'CareerCounselling',
                'career_opportunities'        => 'CareerOpportunities',
                'career_compass'              => 'CareerCompass',
                'article_home'                => 'ArticleHomePage',
                'article_detail'              => 'articleDetailPage',
                'qna'                         => 'Q&A',
                'cafe_buzz'                   => 'CafeBuzz',
                'rank_predictor'              => 'RankPredictor',
                'college_predictor'           => 'CollegePredictor',
                'mentorship'                  => 'MentorshipHomePage',
                'question_details'            => 'QuestionDetailPage',
				'discussionDetail'            => 'discussionDetailPage', // added by Nithish Reddy
                'iim_predictor'               => 'IIMPredictor_Desktop_Input'
            ),
            'mobile'  => array(
                'home'                        => 'Mobile_HomePage',
                'courselisting'               => 'courseDetailsPage',
                'coursehome'                  => 'Mobile_Course_Homepage_National',
                'institute'                   => 'instituteListingPage',
                'shortlist'                   => 'Mobile_ShortlistPage_National',
                'category'                    => 'Mobile_CategoryPage',
                'category_abroad'             => 'Mobile_CategoryPage',
                'search'                      => 'Mobile_SearchPage_National',
                'exam'                        => 'Mobile_Exampage_National',
                'browse'                      => 'Mobile_BrowsePage_National',
                'top_search'                  => 'Mobile_TopSearchPage_National',
                'ranking'                     => 'Mobile_RankingPage_National',
                'college_review_home'         => 'Mobile_CollegeReviews',
                'college_review_intermediate' => 'Mobile_CollegeReviewsIntermediatePage',
                'campus_rep_home'             => 'CampusRepresentativeHomePage',
                'campus_rep_intermediate'     => 'CampusRepresentativeIntermediatePage',
                'compare_colleges'            => 'Mobile_CompareColleges',
                'exam_calendar'               => 'Mobile_ExamCalendar',
                'application_form'            => 'ApplicationFormHomePage',
                'career_home'                 => 'CareerHomepage',
                'career_detail'               => 'CareerDetailPage',
                'career_counselling'          => 'CareerCounselling',
                'career_opportunities'        => 'CareerOpportunities',
                'career_compass'              => 'Mobile_CareerCompass',
                'article_home'                => 'ArticleHomePage',
                'article_detail'              => 'articleDetailPage',
                'qna'                         => 'Q&A',
                'cafe_buzz'                   => 'CafeBuzz',
                'rank_predictor'              => 'Mobile_RankPredictor',
                'college_predictor'           => 'Mobile_CollegePredictor',
                'mentorship'                  => 'Mobile_MentorshipHomePage',
                'question_details'            => 'QuestionDetailPage',
                'search_result'               => 'Mobile_SearchResultPage_National',
                'discussionDetail'            => 'discussionDetailPage', // added  by Nithish Reddy
				'allCoursePage'       		  =>  'allCoursesPage',
                'iim_predictor'               => 'IIMPredictor_Desktop_Input'
            )
        );

        return $pageNames[$deviceName][$pageName];
    }

    private function getAbroadPageName($pageName){
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

    function getLoggedinUsers($dateRange, $pageName, $extraData)
    {
        $isMobile      = 'no';
        $isStudyAbroad = 'yes';
        $deviceType    = 'all';

        $elasticQuery['index'] = MISCommonLib::$TRAFFICDATA_PAGEVIEWS;
        $elasticQuery['type']  = 'pageview';
        $startDate             = $dateRange['startDate'];
        $endDate               = $dateRange['endDate'];

        $elasticQuery['body']['size'] = 0;

        if (count($extraData) > 0) {
            $categoryId    = '';
            $pageType      = 0;
            $courseLevel   = '';
            $countryId     = 0;

            if (array_key_exists('studyAbroad', $extraData)) 
            {
                $categoryId    = isset($extraData['studyAbroad']['category']) ? $extraData['StudyAbroad']['category'] : 0;
                $courseLevel   = isset($extraData['studyAbroad']['courseLevel']) ? $extraData['StudyAbroad']['courseLevel'] : 0;
                $countryId     = isset($extraData['studyAbroad']['countryId']) ? $extraData['StudyAbroad']['countryId'] : 0;
                $valuesToMatch = array();

                $this->abroadCommonLib = $this->CI->load->library('listingPosting/AbroadCommonLib');
                $ldbCourseIdsArray = $this->abroadCommonLib->getAbroadMainLDBCourses();
                foreach ($ldbCourseIdsArray as $key => $value) {
                    $specialCategories[]= $value['SpecializationId'];
                }   
                $pageType          = isset($extraData['StudyAbroad']['pageType']) ? $extraData['StudyAbroad']['pageType'] : 0;
                if ($categoryId != 0) {
                    if (array_search($categoryId, $specialCategories) !== false) {
                        $valuesToMatch[]['LDBCourseId'] = $categoryId;
                    } else {
                        $valuesToMatch[]['categoryId'] = $categoryId;
                        if ($courseLevel != 0  || $courseLevel != '') {
                            $valuesToMatch[]['courseLevel'] = $courseLevel;
                        }
                    }
                }else{
                    if ($courseLevel != 0 || $courseLevel != '') {
                        $valuesToMatch[]['courseLevel'] = $courseLevel;
                    }
                }
                if ($countryId != '') {
                    $valuesToMatch[]['countryId'] = $countryId;
                }
                $valuesToMatch[]['isStudyAbroad'] = $isStudyAbroad;
                $valuesToMatch[]['userId'] =0;

                foreach ($valuesToMatch as $value) {
                    $elasticQuery['body']['query']['filtered']['query']['bool']['must'][]['match'] = $value;
                }

                if($pageName){
                    if ($pageName == 'rankingPage') {
                        if ($pageType == 1) {
                            $elasticQuery['body']['query']['filtered']['query']['bool']['must'][]['match']['pageIdentifier'] = 'universityRankingPage';
                        } else if ($pageType == 2) {
                            $elasticQuery['body']['query']['filtered']['query']['bool']['must'][]['match']['pageIdentifier'] = 'courseRankingPage';
                        }
                    } else {
                        $elasticQuery['body']['query']['filtered']['query']['bool']['must'][]['match']['pageIdentifier'] = $pageName;
                    }    
                }
                
                $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['range']['visitTime']['gte'] = $startDate . 'T00:00:00';
                $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['range']['visitTime']['lt'] =  $endDate . 'T00:00:00';

                
                //_p(json_encode($elasticQuery));die;
                $result = $this->clientCon->search($elasticQuery);
                
                //_p($result['hits']['total']);                die;
                return $result['hits']['total'];

            }
        }
    }

    public function getPageviewData($dateRange, $pageName = '', $extraData)
    {
        $isMobile      = 'no';
        $isStudyAbroad = isset($extraData['CD']['isStudyAbroad'])?$extraData['CD']['isStudyAbroad']:'no';
        $deviceType    = empty($extradata['CD']['deviceType'])?'':$extradata['CD']['deviceType'];

        $elasticQuery['index'] = MISCommonLib::$TRAFFICDATA_PAGEVIEWS;
        $elasticQuery['type']  = 'pageview';
        $startDate             = $dateRange['startDate'];
        $endDate               = $dateRange['endDate'];

        $elasticQuery['body']['size'] = 0;

        if (count($extraData) > 0) {
            $categoryId    = '';
            $pageType      = 0;
            $courseLevel   = '';
            $countryId     = 0;

            if (array_key_exists('studyAbroad', $extraData)) 
            {
                //_p($dateRange);_p($pageName);
                //_p($extraData);die;
                $categoryId    = isset($extraData['studyAbroad']['categoryId']) ? $extraData['studyAbroad']['categoryId'] : 0;
                $courseLevel   = isset($extraData['studyAbroad']['courseLevel']) ? $extraData['studyAbroad']['courseLevel'] : 0;
                $countryId     = isset($extraData['studyAbroad']['country']) ? $extraData['studyAbroad']['country'] : 0;
                $valuesToMatch = array();
                $this->abroadCommonLib = $this->CI->load->library('listingPosting/AbroadCommonLib');
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

                $pageWiseAggregation   = array(
                            'pageWise' => array(
                                'terms' => array('field' => 'pageIdentifier',"size"=>0),
                            )
                        );

                $deviceWiseAggregation = array(
                    'siteSourse' => array(
                        'terms' => array('field' => 'isMobile',"size"=>0),
                        'aggs'  => $pageWiseAggregation
                    )
                );
                $dateWiseAggregation   = array(
                    'dateWise' => array(
                        'date_histogram' => array(
                            'field'    => 'visitTime',
                            'interval' => $extraData['studyAbroad']['view']
                        ),
                        'aggs'           => $deviceWiseAggregation
                    )
                );
                
                $dateWiseAggregation['users'] = array("filter" => array("term" => array("userId" => 0)));      
                $elasticQuery['body']['aggs'] = $dateWiseAggregation;    
                
                //_p(json_encode($elasticQuery));die;
                $result = $this->clientCon->search($elasticQuery);

                // traffic source data
                /*$trafficSourceAggeration = array(
                    'siteSourse' => array(
                        'terms' => array('field' => 'source',"size"=>0),
                    )
                );
                $elasticQuery['body']['aggs'] = $trafficSourceAggeration;
                //_p(json_encode($elasticQuery));die;
                $trafficSource = $this->clientCon->search($elasticQuery);*/
                //_p($result);                die;

                return $result =array('result' => $result,
                                    'query' => $elasticQuery
                                    );

            } else if (array_key_exists('CD', $extraData)) {
                $flag = 0;

                $isStudyAbroad = isset($extraData['CD']['isStudyAbroad'])?$extraData['CD']['isStudyAbroad']:'no';
                $deviceType    = empty($extradata['CD']['deviceType'])?'':$extradata['CD']['deviceType'];
                if (isset($extraData['CD']['deviceType'])) {
                        $deviceType = $extraData['CD']['deviceType'];
                        $isMobile   = ($deviceType == 'desktop') ? 'no' : 'yes';
                    }
                     /*if(isset($extraData['CD']['isStudyAbroad'])) {
                        $isStudyAbroad = $extraData['CD']['isStudyAbroad'];
                    } else {
                        $isStudyAbroad = 'no';
                    }*/

                if (count($extraData['CD']['courseId']) > 0 || count($extraData['CD']['instituteId']) > 0) {
                    $flag = 1;

                    foreach ($extraData['CD']['instituteId'] as $instituteId) {
                        $pageEntityId['term']['pageEntityId'] = intval($instituteId);
                        $pageIdentifier = '';

                        if ($isStudyAbroad == 'yes') {
                            $pageIdentifier['term']['pageIdentifier'] = $this->getAbroadPageName('institute');
                        } else {

                            if ($deviceType == 'desktop') {
                                $pageIdentifier['term']['pageIdentifier'] = $this->getPageName('institute', $deviceType);
                            }
                            else if($deviceType == 'mobile') 
                            {
                                $mobileData = array(
                                    'term' => array(
                                        'pageIdentifier' => $this->getPageName('institute', 'mobile')
                                    )
                                );

                                $mobile5Data = array(
                                    'term' => array(
                                        'pageIdentifier' => $this->getPageName('allCoursePage', 'mobile')
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
                                        'pageIdentifier' => $this->getPageName('institute', 'mobile')
                                    )
                                );

                                $desktopData = array(
                                    'term' => array(
                                        'pageIdentifier' => $this->getPageName('institute', 'desktop')
                                    )
                                );
                                $mobile5Data = array(
                                    'term' => array(
                                        'pageIdentifier' => $this->getPageName('allCoursePage', 'mobile')
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
                        $pageEntityId['term']['pageEntityId'] = intval($oneCourseId);
                        $pageIdentifier = '';

                        if ($isStudyAbroad == 'yes') {
                            $pageIdentifier['term']['pageIdentifier'] = $this->getAbroadPageName('courselisting');
                        } else {

                            if ($deviceType != '') {
                                $pageIdentifier['term']['pageIdentifier'] = $this->getPageName('courselisting', $deviceType);
                            } else {
                                $mobileData = array(
                                    'term' => array(
                                        'pageIdentifier' => $this->getPageName('courselisting', 'mobile')
                                    )
                                );

                                $desktopData = array(
                                    'term' => array(
                                        'pageIdentifier' => $this->getPageName('courselisting', 'desktop')
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
                    $flag = 1;

                    foreach ($extraData['CD']['articleId'] as $oneArticleId) {
                        $pageEntityId['term']['pageEntityId'] = intval($oneArticleId);
                        $pageIdentifier = '';

                        if ($isStudyAbroad == 'yes') {
                            $pageIdentifier['term']['pageIdentifier'] = $this->getAbroadPageName('article_detail');
                        } else {
                            if ($deviceType != '') {
                                $pageIdentifier['term']['pageIdentifier'] = $this->getPageName('article_detail', $deviceType);
                            } else {
                                $mobileData = array(
                                    'term' => array(
                                        'pageIdentifier' => $this->getPageName('article_detail', 'mobile')
                                    )
                                );

                                $desktopData = array(
                                    'term' => array(
                                        'pageIdentifier' => $this->getPageName('article_detail', 'desktop')
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
                     $flag = 1;
                    foreach ($extraData['CD']['discussionId'] as $oneDiscussionId) {
                        $pageEntityId['term']['pageEntityId'] = intval($oneDiscussionId);
                        $pageIdentifier = '';
                        if ($isStudyAbroad == 'yes') {
                            $pageIdentifier['term']['pageIdentifier'] = $this->getAbroadPageName('discussionDetail');
                        } else {
                            if ($deviceType != '') {
                                $pageIdentifier['term']['pageIdentifier'] = $this->getPageName('discussionDetail', $deviceType);
                            } else {
                                /*$mobileData = array(
                                    'term' => array(
                                        'pageIdentifier' => strtolower($this->getPageName('article_detail', 'mobile'))
                                    )
                                );*/

                                $desktopData = array(
                                    'term' => array(
                                        'pageIdentifier' => $this->getPageName('discussionDetail', 'desktop')
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
                }
                else if(count($extraData['CD']['subCategoryId']) > 0 || count($extraData['CD']['authorId']) >0)
                {
                    //
                    $flag = 1;
                        if($extraData['CD']['pageName'] == 'institute')
                        {
                            if ($isStudyAbroad == 'yes') {
                                    $instituteData = array(
                                        'terms' => array(
                                            'pageIdentifier' => array($this->getAbroadPageName('institute'),
                                                $this->getAbroadPageName('courselisting'))
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
                                                'pageIdentifier' => array($this->getPageName('institute','desktop'),
                                                $this->getPageName('courselisting','desktop'))
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
                                                'pageIdentifier' => array($this->getPageName('institute','mobile'),
                                                $this->getPageName('courselisting','mobile'),$this->getPageName('allCoursePage','mobile')) 
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
                                            'pageIdentifier' => array($this->getPageName('institute','desktop'),
                                                $this->getPageName('courselisting','desktop'),$this->getPageName('institute','mobile'),$this->getPageName('courselisting','mobile'),$this->getPageName('allCoursePage','mobile'))
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
                                            'pageIdentifier' => array($this->getAbroadPageName('article_detail'))
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
                                            'pageIdentifier' => array($this->getPageName('article_detail','desktop'))
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
                                            'pageIdentifier' => array($this->getPageName('article_detail','mobile')
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
                                            'pageIdentifier' => array($this->getPageName('article_detail','mobile'),$this->getPageName('article_detail','desktop')
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
                                            'pageIdentifier' => array($this->getPageName('discussionDetail','desktop'))
                                            )
                                        );
                                    $elasticQuery['body']['query']['filtered']['filter']['bool']['must'] = array(
                                                  $discussionData
                                            );
                        }
                    //
                }
                if($flag == 1)
                {
                $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['term']['isStudyAbroad'] = $isStudyAbroad;


                if ( !empty($extraData['CD']['deviceType'])) {
                    $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['term']['isMobile'] = $isMobile;
                }

                   if(isset($extraData['CD']['subCategoryId'])){
                $subCategoryId = $extraData['CD']['subCategoryId'];
                foreach ($subCategoryId as $key => $value) {
                    $subCategoryFilter['bool']['should'][]['term']['subCategoryId'] = $value;
                }
                $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = $subCategoryFilter;
            }
            if(isset($extraData['CD']['cityId']) || isset($extraData['CD']['stateId']))
            {
                $cityId =  $extraData['CD']['cityId'];
                foreach ($cityId as $key => $value) {
                    $cityFilter['bool']['should'][]['term']['cityId'] =  $value;
                }
                $stateId =  $extraData['CD']['stateId'];
                foreach ($stateId as $key => $value) {
                    $cityFilter['bool']['should'][]['term']['stateId'] =  $value;
                }
                $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = $cityFilter;
            }
            /*if(isset($extraData['CD']['stateId']))
            {
                $stateId =  $extraData['CD']['stateId'];
                foreach ($stateId as $key => $value) {
                    $stateFilter['bool']['should'][]['term']['stateId'] =  $value;
                }
                $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = $stateFilter;
            }*/
            if(isset($extraData['CD']['countryId']))
            {
                $countryId = $extraData['CD']['countryId'];
                foreach ($countryId as $key => $value) {
                    $countryFilter['bool']['should'][]['term']['countryId'] = $value;
                }
                $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = $countryFilter;
            }
            if(isset($extraData['CD']['authorId']))
            {
                $authorId = $extraData['CD']['authorId'];
                foreach ($authorId as $key => $value) {
                    $authorFilter['bool']['should'][]['term']['authorId'] = $value;
                }
                $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = $authorFilter;
            }

                $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['range']['visitTime']['lte'] = $endDate . 'T23:59:59';    

                $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['range']['visitTime']['gte'] = $startDate . 'T00:00:00';
                if( !isset($extraData['CD']['Overview']))
                {
                    $deviceWiseAggregation = array(
                        'deviceWise' => array(
                            'terms' => array('field' => 'isMobile'),
                        )
                    );
                        $dateWiseAggregation   = array(
                            'dateWise' => array(
                                'date_histogram' => array(
                                    'field'    => 'visitTime',
                                    'interval' => $extraData['CD']['viewWise']
                                ),
                                'aggs'           => $deviceWiseAggregation
                            )
                        );

                    $elasticQuery['body']['aggs'] = $dateWiseAggregation;
                }
                $result = $this->clientCon->search($elasticQuery);
                unset($elasticQuery);
                return $result;
            }
            else
            {
                $result = array();
                return $result;
            }
        
            }
        }
    }

    /**
     * Get the engagement data which was left over by the getPageviewData method. <br/>
     * This method works for both the CD and the SA codebases
     *
     * @param array  $dateRange The date range which comprises startdate and the enddate
     * @param string $pageName  The pagename corresponding to which the results are to be obtained. This is optional
     *                          for the CD codebase
     * @param array  $extraData The extradata which contains the filters for the CD and the SA codebase
     *
     * @return array
     */
    public function getPageMetricData($dateRange, $pageName='', $extraData=array())
    {
        if( count($extraData) > 0 ){
            if (array_key_exists('studyAbroad', $extraData)) {
                //_p($dateRange);_p($pageName);            _p($extraData);die;
                
                switch($extraData['studyAbroad']['view'])
                {
                    case 1: $extraData['studyAbroad']['view'] = 'day';
                            break;

                    case 2: $extraData['studyAbroad']['view'] = 'week';
                            break;

                    case 3: $extraData['studyAbroad']['view'] = 'month';
                            break;
                }
                if($extraData['studyAbroad']['engagementType'] == 'bounce')
                {
                    $elasticQuery = $this->getQuerySA($dateRange, $pageName, $extraData, 'landing');
                    //_p(json_encode($elasticQuery));die;
                    $totalSessions = array('result' => $this->clientCon->search($elasticQuery),
                                            'query' => $elasticQuery
                                            ); // Get total sessions Information for 
                   
                    $extraData['studyAbroad']['bounce'] = 'yes'; // bounce sessions

                    $elasticQuery = $this->getQuerySA($dateRange, $pageName, $extraData, 'landing');
                    //_p(json_encode($elasticQuery));die;
                    $bounceSessions = array('result' => $this->clientCon->search($elasticQuery),
                                            'query' => $elasticQuery
                                            );
                    //$bounceSessions = $this->clientCon->search($elasticQuery); // Get bounce sessions Information for
                    //_p($bounceSessions);die;
                }else if($extraData['studyAbroad']['engagementType'] == 'pgpersess')
                {
                    $elasticQuery = $this->getQuerySA($dateRange, $pageName, $extraData, 'landing');
                    //_p(json_encode($elasticQuery));die;
                    return array('result' => $this->clientCon->search($elasticQuery),
                                'query' => $elasticQuery
                                );
                }else if($extraData['studyAbroad']['engagementType'] == 'avgsessdur')
                {
                    $elasticQuery = $this->getQuerySA($dateRange, $pageName, $extraData, 'landing');
                    //_p(json_encode($elasticQuery));die;
                    //return $this->clientCon->search($elasticQuery);   
                    return array('result' => $this->clientCon->search($elasticQuery),
                                'query' => $elasticQuery
                                );
                }else if($extraData['studyAbroad']['engagementType'] == 'exit')
                {
                    $elasticQuery = $this->getQuerySAForExit($dateRange, $pageName, $extraData, 'exit'); // Get Exit Information
                    //_p(json_encode($elasticQuery));die;
                    $exitRateResult = array('result' => $this->clientCon->search($elasticQuery),
                                            'query' => $elasticQuery
                                            );
                    //$exitRateUserwise = $this->getQuerySAForUsers($dateRange, $pageName, $extraData, 'exit'); // Get Exit Information Userwise
                    $pageViewData = $this->getPageviewData($dateRange, $pageName, $extraData); // Get Pageview information
                }else if($extraData['studyAbroad']['engagementType'] == 'pageviews')
                {
                    $pageViewData = $this->getPageviewData($dateRange, $pageName, $extraData); // Get Pageview information
                }
                
                switch ($extraData['studyAbroad']['engagementType']) {
                    case 'exit':
                        
                        $SAData['exit'] = array(
                                'exitSession' => $exitRateResult,
                                'pageviews' => $pageViewData
                            );
                        return $SAData;
                        break;
                    
                    case 'pageviews':
                        $SAData['pageViews'] = $pageViewData;
                        break;

                    case 'bounce':
                        $SAData['bounce'] = array(
                                'totalSession' => $totalSessions,
                                'bounceSession' => $bounceSessions
                            );
                        break;

                    case 'pgpersess':
                        # code...
                        break;

                    case 'avgsessdur':
                        # code...
                        break;

                    default:
                        # code...
                        break;
                }
                //_p($SAData);die;
                return $SAData;

            } else if (array_key_exists('CD', $extraData)) {
                
            if(  empty($extraData['CD']['instituteId']) && empty($extraData['CD']['courseId']) && empty($extraData['CD']['articleId']) && empty($extraData['CD']['discussionId']) && empty($extraData['CD']['subCategoryId']) && empty($extraData['CD']['authorId']))
            {
                $bounceResult = array();
                $exitResult = array();
                $avgPagePerSession = array();
                $avgSessionDuration = array();
            }
            else
            {
                $elasticQuery = $this->getQueryCD($dateRange, $pageName, $extraData, 'landing','avgPagePerSession');
                $elasticQuery['body']['aggs']['totalDuration']['sum']['field'] = 'duration';
                $elasticQuery['body']['aggs']['totalPageViews']['sum']['field'] = 'pageviews';
                $elasticQuery['body']['sort']['startTime']['order'] = 'asc';
        
                $avgPagePerSession = $this->clientCon->search($elasticQuery);
                unset($elasticQuery);

                
                $elasticQuery = $this->getQueryCD($dateRange, $pageName, $extraData, 'landing','avgSessionDuration');


                $elasticQuery['body']['aggs']['totalDuration']['sum']['field'] = 'duration';
                $elasticQuery['body']['sort']['startTime']['order'] = 'asc';
                $avgSessionDuration = $this->clientCon->search($elasticQuery);
                //_p('avgSessionDuration'.json_encode($elasticQuery));
                unset($elasticQuery);
                $elasticQuery = $this->getQueryCD($dateRange, $pageName, $extraData, 'landing','bounceRate');
                $elasticQuery['body']['aggs']['totalDuration']['sum']['field'] = 'duration';

                $bounceFilter = array(
                    'term' => array(
                        'bounce' => 1
                    )
                );
                $elasticQuery['body']['aggs']['bounceSessions']['filter'] = $bounceFilter;
                $elasticQuery['body']['sort']['startTime']['order'] = 'asc';


                $bounceResult = $this->clientCon->search($elasticQuery); // Get Bounce Information
                

                unset($elasticQuery);
                $elasticQuery = $this->getQueryCD($dateRange, $pageName, $extraData, 'exit'); // Get Exit Information
                $elasticQuery['body']['sort']['startTime']['order'] = 'asc';

                $exitResult = $this->clientCon->search($elasticQuery);
                //_p('exit'.json_encode($elasticQuery));
                unset($elasticQuery);
            }
            $pageViewDataResult = $this->getPageviewData($dateRange, $pageName, $extraData); // Get Pageview information
                $engagementData = array();
                $engagementData['pageView'] = $pageViewDataResult;
                $engagementData['bounceRate'] = $bounceResult;
                $engagementData['exitRate'] = $exitResult;
                $engagementData['avgPagePerSession'] = $avgPagePerSession;
                $engagementData['avgSessionDuration'] = $avgSessionDuration;
                return $engagementData;
            }
        }
    }

    private function getQuerySAForExit($dateRange, $pageName, $extraData, $type='landing')
    {
        $elasticQuery['index'] = MISCommonLib::$TRAFFICDATA_SESSIONS;
        $elasticQuery['type']  = 'session';
        $elasticQuery['body']['size'] = 0;
        $prefix = '';
        switch($type){
            case 'landing':
                $prefix = 'landingPageDoc.';
                break;
            case 'exit':
                $prefix = 'exitPage.';
                break;
        }

        $isStudyAbroad = 'yes';
        $categoryId    = isset($extraData['studyAbroad']['categoryId']) ? $extraData['studyAbroad']['categoryId'] : 0;
        $courseLevel   = isset($extraData['studyAbroad']['courseLevel']) ? $extraData['studyAbroad']['courseLevel'] : 0;
        $countryId     = isset($extraData['studyAbroad']['country']) ? $extraData['studyAbroad']['country'] : 0;
        $valuesToMatch = array();
        $startDate = $dateRange['startDate'];
        $endDate = $dateRange['endDate'];
        
        $this->abroadCommonLib = $this->CI->load->library('listingPosting/AbroadCommonLib');
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
                    $elasticQuery['body']['query']['filtered']['filter']['bool']['should'][]['match'][$prefix.'pageIdentifier'] = 'courseRankingPage';
                    $elasticQuery['body']['query']['filtered']['filter']['bool']['should'][]['match'][$prefix.'pageIdentifier'] = 'universityRankingPage';
                    //$elasticQuery['body']['query']['filtered']['filter']['bool']['minimum_should_match'] = 1;
                    }
            } else {
                $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['term'][$prefix.'pageIdentifier'] = $pageName;
            }
        }
        
        $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['range']['startTime']['gte'] = $startDate . 'T00:00:00';
        $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['range']['startTime']['lt'] =  $endDate . 'T00:00:00';


        $deviceWiseAggregation   = array('siteSourse' => array('terms' => array('field' => $prefix.'isMobile',"size" => 0)));        
        return $elasticQuery;
    }

    private function getQuerySA($dateRange, $pageName, $extraData, $type='landing')
    {
        $elasticQuery['index'] = MISCommonLib::$TRAFFICDATA_SESSIONS;
        $elasticQuery['type']  = 'session';
        $elasticQuery['body']['size'] = 0;
        $prefix = '';
        switch($type){
            case 'landing':
                $prefix = 'landingPageDoc.';
                break;
            case 'exit':
                $prefix = 'exitPage.';
                break;
        }

        $isStudyAbroad = 'yes';
        $categoryId    = isset($extraData['studyAbroad']['categoryId']) ? $extraData['studyAbroad']['categoryId'] : 0;
        $courseLevel   = isset($extraData['studyAbroad']['courseLevel']) ? $extraData['studyAbroad']['courseLevel'] : 0;
        $countryId     = isset($extraData['studyAbroad']['country']) ? $extraData['studyAbroad']['country'] : 0;
        $valuesToMatch = array();
        $startDate = $dateRange['startDate'];
        $endDate = $dateRange['endDate'];
        
        $this->abroadCommonLib = $this->CI->load->library('listingPosting/AbroadCommonLib');
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
        $valuesToMatch[]['isStudyAbroad'] = $isStudyAbroad;
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
                    $elasticQuery['body']['query']['filtered']['filter']['bool']['should'][]['match'][$prefix.'pageIdentifier'] = 'courseRankingPage';
                    $elasticQuery['body']['query']['filtered']['filter']['bool']['should'][]['match'][$prefix.'pageIdentifier'] = 'universityRankingPage';
                    //$elasticQuery['body']['query']['filtered']['filter']['bool']['minimum_should_match'] = 1;
                    }
            } else {
                $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['term'][$prefix.'pageIdentifier'] = $pageName;
            }
        }
        
        $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['range']['startTime']['gte'] = $startDate . 'T00:00:00';
        $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['range']['startTime']['lt'] =  $endDate . 'T00:00:00';


        $deviceWiseAggregation   = array('siteSourse' => array('terms' => array('field' => $prefix.'isMobile',"size" => 0)));

        if($extraData['studyAbroad']['engagementType'] == 'pgpersess')
        {
            $pageviewsAggs   = array('pageViewsAggs' => array('sum' => array('field' => 'pageviews')));
            if($pageName == '')
            {
                $pageWiseAggregation = array('pageWise' => array('terms' => array('field' => 'landingPageDoc.pageIdentifier',"size" =>0)));
                $pageWiseAggregation['pageWise']['aggs'] = $pageviewsAggs;
                $deviceWiseAggregation['siteSourse']['aggs'] = $pageWiseAggregation;
            }else{
                $deviceWiseAggregation['siteSourse']['aggs'] = $pageviewsAggs;
            } 
        }else if($extraData['studyAbroad']['engagementType'] == 'avgsessdur')
        {
            $pageviewsAggs   = array('pageViewsAggs' => array('sum' => array('field' => 'duration')));
            if($pageName == '')
            {
                $pageWiseAggregation = array('pageWise' => array('terms' => array('field' => 'landingPageDoc.pageIdentifier',"size" =>0)));
                $pageWiseAggregation['pageWise']['aggs'] = $pageviewsAggs;
                $deviceWiseAggregation['siteSourse']['aggs'] = $pageWiseAggregation;
            }else{
                $deviceWiseAggregation['siteSourse']['aggs'] = $pageviewsAggs;
            } 
                
        }else if($extraData['studyAbroad']['engagementType'] == 'bounce' && $pageName == '')
        {
            $pagewiseAggregation = array('pageWise' => array('terms' => array('field' => 'landingPageDoc.pageIdentifier')));
            $deviceWiseAggregation['siteSourse']['aggs'] = $pagewiseAggregation;
        }else{
            $pagewiseAggregation = array('pageWise' => array('terms' => array('field' => 'landingPageDoc.pageIdentifier')));
            $deviceWiseAggregation['siteSourse']['aggs'] = $pagewiseAggregation;
        }

        $dateWiseAggregation   = array(
            'dateWise' => array(
                'date_histogram' => array(
                    'field'    => 'startTime',
                    'interval' => $extraData['studyAbroad']['view']
                ),
                'aggs'           => $deviceWiseAggregation
            ),
            "users" => array(
                        'filter' => array('term'=> array('userId' => 0))
                ),
        );

        if($extraData['studyAbroad']['engagementType'] == 'exit')
        {
            $dateWiseAggregation['users'] = array('filter' => array('term'=> array('exitPage.userId' => 0)));
        }else
        {
            $dateWiseAggregation['users'] = array('filter' => array('term'=> array('userId' => 0)));
        }


        if($extraData['studyAbroad']['engagementType'] == 'pgpersess')
        {
            $dateWiseAggregation['users']['aggs'] = array('userPageview' => array('sum'=> array('field'=> 'pageviews')));

        }else if($extraData['studyAbroad']['engagementType'] == 'avgsessdur')
        {
            $dateWiseAggregation['users']['aggs'] = array('userPageview' => array('sum'=> array('field'=> 'duration')));            
        }

        $elasticQuery['body']['aggs'] = $dateWiseAggregation;
        return $elasticQuery;
    }

	private function getQueryCD($dateRange, $pageName = '', $extraData, $type = 'landing',$flag='')
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
        $prefix = '';
        switch($type){
            case 'landing':
                $prefix = 'landingPageDoc.';
                break;
            case 'exit':
                $prefix = 'exitPage.';
                break;
        }

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
                    $pageIdentifier['term'][$prefix.'pageIdentifier'] = $this->getAbroadPageName('institute');
                } else {

                    if ($deviceType == 'desktop') {
                        $pageIdentifier['term'][$prefix.'pageIdentifier'] = $this->getPageName('institute', $deviceType);
                    }
                      else if($deviceType == 'mobile') 
                            {
                                $mobileData = array(
                                    'term' => array(
                                        $prefix.'pageIdentifier' => $this->getPageName('institute', 'mobile')
                                    )
                                );

                                $mobile5Data = array(
                                    'term' => array(
                                        $prefix.'pageIdentifier' => $this->getPageName('allCoursePage', 'mobile')
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
                                $prefix.'pageIdentifier' => $this->getPageName('institute', 'mobile')
                            )
                        );

                        $desktopData = array(
                            'term' => array(
                                $prefix.'pageIdentifier' => $this->getPageName('institute', 'desktop')
                            )
                        );
                        $mobile5Data = array(
                                    'term' => array(
                                        $prefix.'pageIdentifier' => $this->getPageName('allCoursePage', 'mobile')
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
                    $pageIdentifier['term'][$prefix.'pageIdentifier'] = $this->getAbroadPageName('courselisting');
                } else {

                    if ($deviceType != '') {
                        $pageIdentifier['term'][$prefix.'pageIdentifier'] = $this->getPageName('courselisting', $deviceType);
                    } else {
                        $mobileData = array(
                            'term' => array(
                                $prefix.'pageIdentifier' => $this->getPageName('courselisting', 'mobile')
                            )
                        );

                        $desktopData = array(
                            'term' => array(
                                $prefix.'pageIdentifier' => $this->getPageName('courselisting', 'desktop')
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
                    $pageIdentifier['term'][$prefix.'pageIdentifier'] = $this->getAbroadPageName('article_detail');
                } else {
                    if ($deviceType != '') {
                        $pageIdentifier['term'][$prefix.'pageIdentifier'] = $this->getPageName('article_detail', $deviceType);
                    } else {
                        $mobileData = array(
                            'term' => array(
                                $prefix.'pageIdentifier' => $this->getPageName('article_detail', 'mobile')
                            )
                        );

                        $desktopData = array(
                            'term' => array(
                                $prefix.'pageIdentifier' => $this->getPageName('article_detail', 'desktop')
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
        }else if(count($extraData['CD']['discussionId']) > 0)
        {
            foreach ($extraData['CD']['discussionId'] as $oneDiscussionId) {
                $pageEntityId['term'][$prefix.'pageEntityId'] = intval($oneDiscussionId);
                $pageIdentifier = '';

                if ($isStudyAbroad == 'yes') {
                    $pageIdentifier['term'][$prefix.'pageIdentifier'] = $this->getAbroadPageName('discussionDetail');
                } else {
                    if ($deviceType != '') {
                        $pageIdentifier['term'][$prefix.'pageIdentifier'] = $this->getPageName('discussionDetail', $deviceType);
                    } else {
                        /*$mobileData = array(
                            'term' => array(
                                $prefix.'pageIdentifier' => $this->getPageName('discussionDetail', 'mobile')
                            )
                        );*/

                        $desktopData = array(
                            'term' => array(
                                $prefix.'pageIdentifier' => $this->getPageName('discussionDetail', 'desktop')
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
        }
        else if(count($extraData['CD']['subCategoryId']) > 0 || count($extraData['CD']['authorId']) > 0)
        {
            if($extraData['CD']['pageName'] == 'institute')
            {
                if ($isStudyAbroad == 'yes') {
                        $instituteData = array(
                            'terms' => array(
                                $prefix.'pageIdentifier' => array($this->getAbroadPageName('institute'),
                                    $this->getAbroadPageName('courselisting'))
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
                                $prefix.'pageIdentifier' => array($this->getPageName('institute','desktop')),
                                    $this->getPageName('courselisting','desktop'))
                            );
                          $elasticQuery['body']['query']['filtered']['filter']['bool']['must'] = array(
                                      $instituteData
                                );
                        }
                        else if($deviceType == 'mobile')
                        {
                            $instituteData = array(
                                'terms' => array(
                                    $prefix.'pageIdentifier' => array($this->getPageName('institute','mobile'),
                                    $this->getPageName('courselisting','mobile'),$this->getPageName('allCoursePage','mobile'))
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
                                $prefix.'pageIdentifier' => array($this->getPageName('institute','desktop'),
                                    $this->getPageName('courselisting','desktop'),$this->getPageName('institute','mobile'),$this->getPageName('courselisting','mobile'),$this->getPageName('allCoursePage','mobile'))
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
                                $prefix.'pageIdentifier' => array($this->getAbroadPageName('article_detail'))
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
                                $prefix.'pageIdentifier' => array($this->getPageName('article_detail','desktop'))
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
                                $prefix.'pageIdentifier' => array($this->getPageName('article_detail','mobile')
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
                                $prefix.'pageIdentifier' => array($this->getPageName('article_detail','mobile'),$this->getPageName('article_detail','desktop')
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
                                $prefix.'pageIdentifier' => array($this->getPageName('discussionDetail','desktop'))
                                )
                            );
                        $elasticQuery['body']['query']['filtered']['filter']['bool']['must'] = array(
                                      $discussionData
                                );
            }
        }

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
        $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['range']['startTime']['lte'] = $endDate . 'T23:59:59';
        $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['range']['startTime']['gte'] = $startDate . 'T00:00:00';
        if( ! isset($extraData['CD']['Overview']))
        {
        if($flag == 'avgPagePerSession')
        {
            $pageViewWiseAggregation = array(
                'pageViewWise' => array(
                    'sum' => array('field' => "pageviews")
                    )
                );
               $deviceWiseAggregation = array(
                'deviceWise' => array(
                    'terms' => array('field' => $prefix.'isMobile'),
                    'aggs' => $pageViewWiseAggregation
                )

            );
        }
        else if($flag == 'avgSessionDuration')
        {
            $sessionDurationWise = array(
                'durationWise' => array(
                    'sum' => array('field' => "duration")
                    )
                );
             $deviceWiseAggregation = array(
                'deviceWise' => array(
                    'terms' => array('field' => $prefix.'isMobile'),
                    'aggs' => $sessionDurationWise
                )

            );
        }
        else if($flag == 'bounceRate')
        {
            $bounceWiseAggregation = array(
                'Bounces' => array(
                    'terms' => array('field' => 'bounce')
                    )
                );

            $deviceWiseAggregation = array(
                'deviceWise' => array(
                    'terms' => array('field' => $prefix.'isMobile'),
                    'aggs' => $bounceWiseAggregation
                )

            );
        }
        else
        {
         $deviceWiseAggregation = array(
                'deviceWise' => array(
                    'terms' => array('field' => $prefix.'isMobile'),
                )

            );   
        }
        $dateWiseAggregation   = array(
            'dateWise' => array(
                'date_histogram' => array(
                    'field'    => 'startTime',
                    'interval' => $extraData['CD']['viewWise']
                ),
                'aggs'           => $deviceWiseAggregation
            )
        );

        $elasticQuery['body']['aggs'] = $dateWiseAggregation;
        }

        return $elasticQuery;
    }
    function getGeoSplitData($instituteId= array(), $courseId = array(),$articleId = array(),$discussionId = array(),$subcatArray = array(),$source ='', $startDate='',$endDate='',$trafficSource='',$isStudyAbroad='')
    {
        $trafficSources = array(
            'paid' => array("paid"),
            'free' => array("mailer","seo","notsure","direct","social")
            );
        $flag = 0;
        $prefix = 'landingPageDoc.';

        $elasticQuery['index'] = MISCommonLib::$TRAFFICDATA_SESSIONS;
        $elasticQuery['type'] = 'session';
        $elasticQuery['body']['size'] = 0;

            if ( ! empty($source)) {
                $deviceType = $source;
                $isMobile   = ($deviceType == 'desktop') ? 'no' : 'yes';
            }

            if( ! empty($isStudyAbroad)) {
                $isStudyAbroad = $isStudyAbroad;
            } else {
                $isStudyAbroad = 'no';
            }

            if ( ! empty($courseId)  || ! empty($instituteId)) {
                $flag = 1;
            foreach ($instituteId as $instituteId) {
                $pageEntityId['term'][$prefix.'pageEntityId'] = intval($instituteId);
                $pageIdentifier = '';

                if ($isStudyAbroad == 'yes') {
                    $pageIdentifier['term']['landingPageDoc.pageIdentifier'] = $this->getAbroadPageName('institute');
                } else {

                    if ($source == 'desktop') {
                        $pageIdentifier['term']['landingPageDoc.pageIdentifier'] = $this->getPageName('institute', $source);
                    }
                     else if($source == 'mobile') 
                            {
                                $mobileData = array(
                                    'term' => array(
                                        $prefix.'pageIdentifier' => $this->getPageName('institute', 'mobile')
                                    )
                                );

                                $mobile5Data = array(
                                    'term' => array(
                                        $prefix.'pageIdentifier' => $this->getPageName('allCoursePage', 'mobile')
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
                                'landingPageDoc.pageIdentifier' => $this->getPageName('institute', 'mobile')
                            )
                        );

                        $desktopData = array(
                            'term' => array(
                                'landingPageDoc.pageIdentifier' => $this->getPageName('institute', 'desktop')
                            )
                        );
                         $mobile5Data = array(
                                    'term' => array(
                                        $prefix.'pageIdentifier' => $this->getPageName('allCoursePage', 'mobile')
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

            foreach ($courseId as $oneCourseId) {
                $pageEntityId['term']['landingPageDoc.pageEntityId'] = intval($oneCourseId);
                $pageIdentifier = '';

                if ($isStudyAbroad == 'yes') {
                    $pageIdentifier['term']['landingPageDoc.pageIdentifier'] = $this->getAbroadPageName('courselisting');
                } else {

                    if ($source != '') {
                        $pageIdentifier['term']['landingPageDoc.pageIdentifier'] = $this->getPageName('courselisting', $source);
                    } else {
                        $mobileData = array(
                            'term' => array(
                                'landingPageDoc.pageIdentifier' => $this->getPageName('courselisting', 'mobile')
                            )
                        );

                        $desktopData = array(
                            'term' => array(
                                'landingPageDoc.pageIdentifier' => $this->getPageName('courselisting', 'desktop')
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
        else if( ! empty($articleId)) {
            $flag = 1;
            foreach ($articleId as $oneArticleId) {
                $pageEntityId['term'][$prefix.'pageEntityId'] = intval($oneArticleId);
                $pageIdentifier = '';

                if ($isStudyAbroad == 'yes') {
                    $pageIdentifier['term'][$prefix.'pageIdentifier'] = $this->getAbroadPageName('article_detail');
                } else {
                    if ($source != '') {
                        $pageIdentifier['term'][$prefix.'pageIdentifier'] = $this->getPageName('article_detail', $source);
                    } else {
                        $mobileData = array(
                            'term' => array(
                                $prefix.'pageIdentifier' => $this->getPageName('article_detail', 'mobile')
                            )
                        );

                        $desktopData = array(
                            'term' => array(
                                $prefix.'pageIdentifier' => $this->getPageName('article_detail', 'desktop')
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
        else if( ! empty($discussionId))
        {
            $flag = 1;
            foreach ($discussionId as $oneDiscussionId) {
                $pageEntityId['term'][$prefix.'pageEntityId'] = intval($oneDiscussionId);
                $pageIdentifier = '';

                if ($isStudyAbroad == 'yes') {
                    $pageIdentifier['term'][$prefix.'pageIdentifier'] = $this->getAbroadPageName('discussionDetail');
                } else {
                    if ($deviceType != '') {
                        $pageIdentifier['term'][$prefix.'pageIdentifier'] = $this->getPageName('discussionDetail', $deviceType);
                    } else {
                        /*$mobileData = array(
                            'term' => array(
                                $prefix.'pageIdentifier' => $this->getPageName('discussionDetail', 'mobile')
                            )
                        );*/

                        $desktopData = array(
                            'term' => array(
                                $prefix.'pageIdentifier' => $this->getPageName('discussionDetail', 'desktop')
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
        }
        else if( ! empty($subcatArray))
        {
                //
            $flag = 1;
            if($subcatArray['pageName'] == 'institute')
            {
                if ($isStudyAbroad == 'yes') {
                        $instituteData = array(
                            'terms' => array(
                                $prefix.'pageIdentifier' => array( ($this->getAbroadPageName('institute')),
                                    $this->getAbroadPageName('courselisting'))
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
                                $prefix.'pageIdentifier' => array($this->getPageName('institute','desktop'),
                                    $this->getPageName('courselisting','desktop'))
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
                                    $prefix.'pageIdentifier' => array($this->getPageName('institute','mobile'),
                                    $this->getPageName('courselisting','mobile'),$this->getPageName('allCoursePage','mobile'))
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
                                    $prefix . 'pageIdentifier' => array(
                                        $this->getPageName('institute', 'desktop'),
                                        $this->getPageName('courselisting', 'desktop'),
                                        $this->getPageName('institute', 'mobile'),
                                        $this->getPageName('courselisting', 'mobile'),
                                        $this->getPageName('allCoursePage', 'mobile')
                                    )
                                )
                            );
                            $elasticQuery['body']['query']['filtered']['filter']['bool']['must'] = array(
                                            $instituteData
                                    );
                        }
                     }
                
            }
            else if($subcatArray['pageName'] == 'articlePage')
            {
                if ($isStudyAbroad == 'yes') {
                        $articleData = array(
                            'terms' => array(
                                $prefix.'pageIdentifier' => array($this->getAbroadPageName('article_detail'))
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
                                $prefix.'pageIdentifier' => array($this->getPageName('article_detail','desktop'))
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
                                $prefix.'pageIdentifier' => array($this->getPageName('article_detail','mobile')
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
                                $prefix.'pageIdentifier' => array($this->getPageName('article_detail','mobile'), $this->getPageName('article_detail','desktop'))
                                )
                            );
                        $elasticQuery['body']['query']['filtered']['filter']['bool']['must'] = array(
                                      $articleData
                                );

                    }

                }
            }
            else if($subcatArray['pageName'] == 'discussionPage')
            {
                $discussionData = array(
                            'terms' => array(
                                $prefix.'pageIdentifier' => array($this->getPageName('discussionDetail','desktop'))
                                )
                            );
                        $elasticQuery['body']['query']['filtered']['filter']['bool']['must'] = array(
                                      $discussionData
                                );
            }
                //           
        }
        if( $flag == 1)
        {

        $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['term'][$prefix.'isStudyAbroad'] = $isStudyAbroad;

        if ( ! empty($source)) {
            $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['term'][$prefix.'isMobile'] = $isMobile;
        }

        if(isset($subcatArray['subCategoryId'])){
            $subCategoryId = $subcatArray['subCategoryId'];
            foreach ($subCategoryId as $key => $value) {
                $subCategoryFilter['bool']['should'][]['term'][$prefix.'subCategoryId'] = $value;
            }
            $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = $subCategoryFilter;
        }
        if(isset($subcatArray['cityId']) || isset($subcatArray['stateId']))
        {
            $cityId =  $subcatArray['cityId'];
            foreach ($cityId as $key => $value) {
                $cityFilter['bool']['should'][]['term'][$prefix.'cityId'] =  $value;
            }
            $stateId =  $subcatArray['stateId'];
            foreach ($stateId as $key => $value) {
                $cityFilter['bool']['should'][]['term'][$prefix.'stateId'] =  $value;
            }
            $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = $cityFilter;
        }
        if(isset($subcatArray['countryId']))
        {
            $countryId = $subcatArray['countryId'];
            foreach ($countryId as $key => $value) {
                $countryFilter['bool']['should'][]['term'][$prefix.'countryId'] = $value;
            }
            $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = $countryFilter;
        }
        if(isset($subcatArray['authorId']))
        {
            $authorId = $subcatArray['authorId'];
            foreach ($authorId as $key => $value) {
                $authorFilter['bool']['should'][]['term'][$prefix.'authorId'] = $value;
            }
            $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = $authorFilter;
        }


           if( ! empty($trafficSource)){
            $trafficSourceType = $trafficSource;

            $trafficSourceWiseGrouping = array();
            foreach ($trafficSources[$trafficSourceType] as $oneTrafficSourceType => $oneTrafficSourceName) {
                $trafficSourceWiseGrouping['bool']['should'][]['term']['source'] = $oneTrafficSourceName;
            }

            $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = $trafficSourceWiseGrouping;

        }
        $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['range']['startTime']['lte'] = $endDate . 'T23:59:59';
        $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['range']['startTime']['gte'] = $startDate . 'T00:00:00';

        /*$deviceWiseAggregation = array(
            'deviceWise' => array(
                'terms' => array('field' => $prefix.'isMobile'),
            )
        );
        $dateWiseAggregation   = array(
            'dateWise' => array(
                'date_histogram' => array(
                    'field'    => 'startTime',
                    'interval' => $this->getPeriodForGrouping($dateRange)
                ),
                'aggs'           => $deviceWiseAggregation
            )
        );*/
        $userWiseAggregation = array(
            'userWise' => array(
                'cardinality' => array(
                    'field' => 'visitorId'
                    )
                )
            );

        /*$ipWiseAggregation = array(
            'ipWise' => array(
                'terms' => array('field' =>'clientIP',
                    'size' => '0'),
                "aggs" => $userWiseAggregation

                ));*/
        $cityWiseAggragation = array(
            'cityWise' => array(
                'terms'=> array(
                    'field' => 'geocity',
                    'size' => '0'
                    ),
                "aggs" => $userWiseAggregation
                )
            );

        $elasticQuery['body']['aggs'] = $cityWiseAggragation;
        $result = $this->clientCon->search($elasticQuery);
        unset($elasticQuery);
        return $result['aggregations']['cityWise']['buckets'];
        }
        else 
            return array();
    }
    function getBounceRateCD($dateRange, $extraData)
    {
        if(  empty($extraData['CD']['instituteId']) && empty($extraData['CD']['courseId']) && empty($extraData['CD']['articleId']) && empty($extraData['CD']['discussionId']))
            {
                return array();
            }
            else
            {
                $elasticQuery = $this->getQueryCD($dateRange,'',$extraData, 'landing','bounceRate');


                $elasticQuery['body']['aggs']['totalDuration']['sum']['field'] = 'duration';

                $bounceFilter = array(
                    'term' => array(
                        'bounce' => 1
                    )
                );
                $elasticQuery['body']['aggs']['bounceSessions']['filter'] = $bounceFilter;
                $elasticQuery['body']['sort']['startTime']['order'] = 'asc';


                $bounceResult = $this->clientCon->search($elasticQuery);
                return $bounceResult;
            }
    }
    function getExitRateCD($dateRange, $extraData)
    {
        if(  empty($extraData['CD']['instituteId']) && empty($extraData['CD']['courseId']) && empty($extraData['CD']['articleId']) && empty($extraData['CD']['discussionId']))
            {
                return array();
            }
            else
            {
                $elasticQuery = $this->getQueryCD($dateRange, '', $extraData, 'exit'); // Get Exit Information
                $elasticQuery['body']['sort']['startTime']['order'] = 'asc';

                $exitResult = $this->clientCon->search($elasticQuery);
                return $exitResult;   
            }
    }

    function getAvgPagePerSessionCD($dateRange,$extraData)
    {
        if(  empty($extraData['CD']['instituteId']) && empty($extraData['CD']['courseId']) && empty($extraData['CD']['articleId']) && empty($extraData['CD']['discussionId']))
            {
                return array();
            }
            else
            {
            $elasticQuery = $this->getQueryCD($dateRange, '', $extraData, 'landing','avgPagePerSession');

                $elasticQuery['body']['aggs']['totalDuration']['sum']['field'] = 'duration';
                $elasticQuery['body']['aggs']['totalPageViews']['sum']['field'] = 'pageviews';
                $elasticQuery['body']['sort']['startTime']['order'] = 'asc';
        
                $avgPagePerSession = $this->clientCon->search($elasticQuery);   
                return $avgPagePerSession;
            }
    }

    function getAvgSessionDurationCD($dateRange,$extraData)
    {
            if(  empty($extraData['CD']['instituteId']) && empty($extraData['CD']['courseId']) && empty($extraData['CD']['articleId']) && empty($extraData['CD']['discussionId']))
            {
                return array();
            }
            else
            {
                $elasticQuery = $this->getQueryCD($dateRange, '', $extraData, 'landing','avgSessionDuration');
                $elasticQuery['body']['aggs']['totalDuration']['sum']['field'] = 'duration';
                $elasticQuery['body']['sort']['startTime']['order'] = 'asc';
                $avgSessionDuration = $this->clientCon->search($elasticQuery);
                return $avgSessionDuration;
            }
    }

    function getPageviewDataCD($dateRange,$extraData)
    {
        $isStudyAbroad = isset($extraData['CD']['isStudyAbroad'])?$extraData['CD']['isStudyAbroad']:'no';
        //$deviceType    = empty($extradata['CD']['deviceType'])?'':$extradata['CD']['deviceType'];

        $elasticQuery['index'] = MISCommonLib::$TRAFFICDATA_PAGEVIEWS;
        $elasticQuery['type']  = 'pageview';
        $startDate             = $dateRange['startDate'];
        $endDate               = $dateRange['endDate'];

        $elasticQuery['body']['size'] = 0;
        $result = array();
        $flag=0;
        if(count($extraData['CD']['articleId']) > 0) {
                    $flag = 1;
                    foreach ($extraData['CD']['articleId'] as $oneArticleId) {
                        $pageEntityId['term']['pageEntityId'] = intval($oneArticleId);
                        $pageIdentifier = '';

                        if ($isStudyAbroad == 'yes') {
                            $pageIdentifier['term']['pageIdentifier'] = $this->getAbroadPageName('article_detail');
                        } else {
                            if ($deviceType != '') {
                                $pageIdentifier['term']['pageIdentifier'] = $this->getPageName('article_detail', $deviceType);
                            } else {
                                $mobileData = array(
                                    'term' => array(
                                        'pageIdentifier' => $this->getPageName('article_detail', 'mobile')
                                    )
                                );

                                $desktopData = array(
                                    'term' => array(
                                        'pageIdentifier' => $this->getPageName('article_detail', 'desktop')
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
                    $flag = 1;
                    foreach ($extraData['CD']['discussionId'] as $oneDiscussionId) {
                        $pageEntityId['term']['pageEntityId'] = intval($oneDiscussionId);
                        $pageIdentifier = '';
                        if ($isStudyAbroad == 'yes') {
                            $pageIdentifier['term']['pageIdentifier'] = $this->getAbroadPageName('discussionDetail');
                        } else {
                            if ($deviceType != '') {
                                $pageIdentifier['term']['pageIdentifier'] = $this->getPageName('discussionDetail', $deviceType);
                            } else {
                                /*$mobileData = array(
                                    'term' => array(
                                        'pageIdentifier' => $this->getPageName('article_detail', 'mobile')
                                    )
                                );*/

                                $desktopData = array(
                                    'term' => array(
                                        'pageIdentifier' => $this->getPageName('discussionDetail', 'desktop')
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
                }
                if($flag == 1)
                {
                    $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['term']['isStudyAbroad'] = $isStudyAbroad;

                    $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['range']['visitTime']['lte'] = $endDate . 'T23:59:59';
                    $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['range']['visitTime']['gte'] = $startDate . 'T00:00:00';
                    $pageEntityIdAggregation = array(
                     'pageIdWise' => array(
                                    'terms' => array('field' =>'pageEntityId',
                                    'size' => '0')
                                    ));
			        $elasticQuery['body']['aggs'] = $pageEntityIdAggregation;
                    $result = $this->clientCon->search($elasticQuery);
                }
                return $result;
    }

    function getAvgSessionDurationForCustomerDelivery($dateRange,$extraData)
    {   
                $pageName = '';
                $elasticQuery = $this->getQueryCD($dateRange, $pageName, $extraData, 'landing','avgSessionDuration');
                unset($elasticQuery['body']['aggs']);
                $sessionDurationWise = array(
                'durationWise' => array(
                    'sum' => array('field' => "duration")
                    )
                );
                $dateWiseAggregation   = array(
                    'dateWise' => array(
                        'date_histogram' => array(
                            'field'    => 'startTime',
                            'interval' => $extraData['CD']['viewWise']
                        ),
                        'aggs'           => $sessionDurationWise
                    )
                );
                $elasticQuery['body']['aggs'] = $dateWiseAggregation;
                $elasticQuery['body']['aggs']['totalDuration']['sum']['field'] = 'duration';
                $elasticQuery['body']['sort']['startTime']['order'] = 'asc';
                $avgSessionDuration = $this->clientCon->search($elasticQuery);
                $dateWise = $avgSessionDuration['aggregations']['dateWise']['buckets'];
                $result = array();
                $i = 0;
                foreach ($dateWise as $dateKey => $dateValue) {
                    $result[$i++] = array(
                                        'responseDate'=> date("Y-m-d", strtotime($dateValue['key_as_string'])),
                                        'responsescount' => number_format($dateValue['durationWise']['value']/$dateValue['doc_count'],2,'.','')
                                    );
                }
                //_p('avgSessionDuration'.json_encode($elasticQuery));
                unset($elasticQuery);
                unset($avgSessionDuration);
                return $result;           
    }

    function getBounceRateForCustomerDelivery($dateRange,$extraData)
    {
        $pageName = '';
        $elasticQuery = $this->getQueryCD($dateRange, $pageName, $extraData, 'landing','bounceRate');
        unset($elasticQuery['body']['aggs']);
        $bounceWiseAggregation = array(
                'Bounces' => array(
                    'terms' => array('field' => 'bounce')
                    )
                );
        $dateWiseAggregation   = array(
            'dateWise' => array(
                'date_histogram' => array(
                    'field'    => 'startTime',
                    'interval' => $extraData['CD']['viewWise']
                ),
                'aggs'           => $bounceWiseAggregation
            )
        );

        $bounceFilter = array(
            'term' => array(
                'bounce' => 1
            )
        );
        $elasticQuery['body']['aggs'] = $dateWiseAggregation;
        //$elasticQuery['body']['aggs']['bounceSessions']['filter'] = $bounceFilter;
        $elasticQuery['body']['sort']['startTime']['order'] = 'asc';
        $bounceResult = $this->clientCon->search($elasticQuery); // Get Bounce Information
        unset($elasticQuery);
        $dateWise = $bounceResult['aggregations']['dateWise']['buckets'];
        $result = array();
        $i = 0;
        foreach ($dateWise as $dateKey => $dateValue) {
            $bounceWise = $dateValue['Bounces']['buckets'];
            $no_of_bounces = 0;
            foreach ($bounceWise as $bounceKey => $bounceValue) {
                if($bounceValue['key'] == 1)
                {
                    $no_of_bounces += $bounceValue['doc_count'];
                }
            }
            $result[$i++] = array(
                                    'responseDate'=> date("Y-m-d", strtotime($dateValue['key_as_string'])),
                                    'responsescount' => number_format(($no_of_bounces/$dateValue['doc_count'])*100,2,'.','')
                                );
        }
        unset($bounceResult);
        return $result;
    }
    function getAvgPagePerSessionForCustomerDelivery($dateRange,$extraData)
    {
        $pageName = '';
        $elasticQuery = $this->getQueryCD($dateRange, $pageName, $extraData, 'landing','avgPagePerSession');
        unset($elasticQuery['body']['aggs']);
        $pageViewWiseAggregation = array(
                'pageViewWise' => array(
                    'sum' => array('field' => "pageviews")
                    )
                );
        $dateWiseAggregation   = array(
            'dateWise' => array(
                'date_histogram' => array(
                    'field'    => 'startTime',
                    'interval' => $extraData['CD']['viewWise']
                ),
                'aggs'           => $pageViewWiseAggregation
            )
        );
        $elasticQuery['body']['aggs'] = $dateWiseAggregation;
        $elasticQuery['body']['sort']['startTime']['order'] = 'asc';
        $avgPagePerSession = $this->clientCon->search($elasticQuery);
        $dateWise = $avgPagePerSession['aggregations']['dateWise']['buckets'];
        $result = array(); $i =0;
        foreach ($dateWise as $dateKey => $dateValue) {
            $result[$i++] = array(
                                    'responseDate'=> date("Y-m-d", strtotime($dateValue['key_as_string'])),
                                    'responsescount' => number_format($dateValue['pageViewWise']['value']/$dateValue['doc_count'],2,'.','')
                            );
        }
        
        return $result;
    }
    function getExitRateDataForCustomerDelivery($dateRange,$extraData)
    {
        $pageName ='';
                $elasticQuery = $this->getQueryCD($dateRange, $pageName, $extraData, 'exit'); // Get Exit Information
                $elasticQuery['body']['sort']['startTime']['order'] = 'asc';

                $exitResult = $this->clientCon->search($elasticQuery);
                $pageviewResult = $this->getPageviewData($dateRange,$pageName,$extraData);
                $result = $pageviewResult['aggregations']['dateWise']['buckets'];
                foreach ($result as $key => $value) {
                    $responseDate = date("Y-m-d", strtotime($value['key_as_string']));
                    $pageviewResultArray[$responseDate] =0;
                    $deviceWise = $value['deviceWise']['buckets'];
                    foreach ($deviceWise as $key => $deviceValue) {
                        $pageviewResultArray[$responseDate] += $deviceValue['doc_count'];
                        }
                    }
                    $result1 = $exitResult['aggregations']['dateWise']['buckets'];
                            foreach ($result1 as $key => $value) {
                                $responseDate = date("Y-m-d", strtotime($value['key_as_string']));
                                $exitResultArray[$responseDate] = 0;
                                $deviceWise = $value['deviceWise']['buckets'];
                                foreach ($deviceWise as $deviceKey => $deviceValue) {
                                    $exitResultArray[$responseDate] += $deviceValue['doc_count'];
                                }
                            }
                $i = 0;
                $arrayResult = array();
                foreach ($exitResultArray as $exitKey => $exitValue) {
                    $arrayResult[$i++] = array('responseDate' => $exitKey,
                                                'responsescount' => number_format(($exitValue/$pageviewResultArray[$exitKey])*100,2,'.','')
                                                );
                }
                return $arrayResult;
                unset($elasticQuery);
    }
    /*function getPageviewDataForCustomerDelivery($dateRange,$extraData)
    {
        $result = $this->getPageviewData($dateRange,'',$extraData);
        return $result;
    }*/
    function getUniqueUserCountForCustomerDelivery($dateRange,$extraData)
    {
        $pageName = '';
        $elasticQuery = $this->getQueryCD($dateRange, $pageName, $extraData, 'landing');
        unset($elasticQuery['body']['aggs']);
        $elasticQuery['body']['aggs']['users']['cardinality']['field'] = 'visitorId';
        $search = $this->clientCon->search($elasticQuery);
        return $search['aggregations']['users']['value'];

    }
    function getUniqueUsersDataForCustomerDelivery($dateRange,$extraData)
    {
        $pageName = '';
        $elasticQuery = $this->getQueryCD($dateRange, $pageName, $extraData, 'landing');
        unset($elasticQuery['body']['aggs']);
          $userWiseAggregation = array(
                'userWise' => array(
                    'cardinality' => array('field' => "visitorId")
                    )
                );
        $dateWiseAggregation   = array(
            'dateWise' => array(
                'date_histogram' => array(
                    'field'    => 'startTime',
                    'interval' => $extraData['CD']['viewWise']
                ),
                'aggs'           => $userWiseAggregation
            )
        );
        $elasticQuery['body']['aggs'] = $dateWiseAggregation;
        $elasticQuery['body']['sort']['startTime']['order'] = 'asc';
        $uniqueUserResult = $this->clientCon->search($elasticQuery);
        $dateWise = $uniqueUserResult['aggregations']['dateWise']['buckets'];
        $result = array();
        $i = 0;
        foreach ($dateWise as $dateKey => $dateValue) {
            $result[$i++] = array(
                                'responseDate'=> date("Y-m-d", strtotime($dateValue['key_as_string'])),
                                'responsescount' => $dateValue['userWise']['value']

                );
        }
        return $result;
    }
    function getPageviewDataForCustomerDelivery($dateRange,$extraData)
    {
                $pageviewResult = $this->getPageviewData($dateRange,$pageName,$extraData);
                $result = $pageviewResult['aggregations']['dateWise']['buckets'];
                $pageviewResultArray = array();
                $i = 0;
                foreach ($result as $key => $value) {
                    $pageviews =0;
                     $deviceWise = $value['deviceWise']['buckets'];
                    foreach ($deviceWise as $key => $deviceValue) {
                            $pageviews += $deviceValue['doc_count'];
                        }
                        $pageviewResultArray[$i++] = array(
                                                'responseDate' => date("Y-m-d", strtotime($value['key_as_string'])),
                                                'responsescount' => $pageviews
                            );
                }
                return $pageviewResultArray;
    }


    // Methods to obtain data for domestic MIS
    /**
     * Top tiles for engagement
     *
     * @param $dateRange
     * @param $pageName
     * @param $extraData
     *
     * @return array
     */
    public function getEngagementTiles($dateRange, $pageName, $extraData){

        $pageviewQuery = $this->MISCommonLib->preparePageviewQuery($dateRange, $pageName, $extraData);
        $pageviews = $this->clientCon->search($pageviewQuery);
        $pageviews = $pageviews['hits']['total'];

        $elasticQuery = $this->MISCommonLib->prepareTrafficQuery($dateRange, $pageName, $extraData);
        $elasticQuery['body']['aggs'] = array(
            'pageviewWise' => array(
                'sum' => array(
                    'field' => 'pageviews'
                )
            )
        );
        $pagesPerSessionData = $this->clientCon->search($elasticQuery);

        $elasticQuery = $this->MISCommonLib->prepareTrafficQuery($dateRange, $pageName, $extraData);
        $elasticQuery['body']['aggs'] = array(
            'totalDuration' => array(
                'sum' => array(
                    'field' => 'duration'
                )
            )
        );
        $averageSessionDurationData = $this->clientCon->search($elasticQuery);

        $bounce = 0;
        $elasticQuery['body']['aggs'] = $bounceAggregation = array(
            'bounceWise' => array(
                'terms' => array(
                    'field' => 'bounce'
                )
            )
        );
        $bounceInformation = $this->clientCon->search($elasticQuery);
        foreach($bounceInformation['aggregations']['bounceWise']['buckets'] as $bounceOrNot){
            if($bounceOrNot['key'] == 1){
                $bounce = number_format($bounceOrNot['doc_count'] / $bounceInformation['hits']['total'] * 100, 2, '.', '');
                break;
            }
        }

        $extraData['National']['pivot'] = 'exit';
        $elasticQuery = $this->MISCommonLib->prepareTrafficQuery($dateRange, $pageName, $extraData);
        $exitInformation = $this->clientCon->search($elasticQuery);

        $hourFormat = explode('.',number_format($averageSessionDurationData['aggregations']['totalDuration']['value'] / $averageSessionDurationData['hits']['total'], 2, '.', ''));

        return array(
            'pageview' => number_format($pageviews),
            'pgpersess' => number_format($pagesPerSessionData['aggregations']['pageviewWise']['value'] / $pagesPerSessionData['hits']['total'], 2, '.', ''),
            //'avgsessdur' => date('H:i:s', mktime(0, 0, $hourFormat[0])).'.'.$hourFormat[1],
			'avgsessdur' => date('i:s', mktime(0, 0, $hourFormat[0])),
            'bounce' => $bounce,
            'exit' => number_format($exitInformation['hits']['total'] / $pageviews * 100, 2, '.', '')
        );
    }


    /**
     * Get the data to be shown on a line chart. The date returned would be in the format:
     * [{Date, ScalarValue}, {Date2, ScalarValue2}....]
     *
     * @param array $dateRange The startDate and the endDate
     * @param array $extraData An array of additional data to be passed
     * @param string $pageName The name of the page for which the trend is to be identified
     *
     * @return array The array containing the trend information in the format mentioned
     */
    public function getEngagementTrends($dateRange, $extraData, $pageName)
    {
        $resultsForGraph = array();
        switch ($extraData["National"]['pivot']) {
            case 'pgpersess':

                $elasticQuery = $this->prepareTrafficQuery($dateRange, $pageName, $extraData);

                $pageviewsCountGrouping = array(
                    'pageViews' => array(
                        'sum' => array(
                            'field' => 'pageviews'
                        )
                    )
                );

                $elasticQuery['body']['aggs']['dateWise']['aggs'] = $pageviewsCountGrouping;

                $pagesPerSession = $this->clientCon->search($elasticQuery);

                $pagesPerSession = $pagesPerSession['aggregations']['dateWise']['buckets'];

                foreach ($pagesPerSession as $oneIndex => $oneSessionData) {
                    $dataToPush              = new stdClass();
                    $dataToPush->Date        = $this->MISCommonLib->extractDate($oneSessionData['key_as_string']);
                    $dataToPush->ScalarValue = number_format($oneSessionData['pageViews']['value'] / $oneSessionData['doc_count'], 2, ".", "");
                    $resultsForGraph[]       = $dataToPush;

                }
                break;

            case 'bounce':
                $elasticQuery = $this->prepareTrafficQuery($dateRange, $pageName, $extraData);

                $elasticQuery['body']['aggs']['dateWise']['aggs'] = array(
                    'bounceSessions' => array(
                        'terms' => array(
                            'field' => 'bounce',
                        )
                    )
                );

                $bounceValues = $this->clientCon->search($elasticQuery);

                $bounceValues = $bounceValues['aggregations']['dateWise']['buckets'];

                $resultsForGraph = array();

                foreach ($bounceValues as $oneIndex => $oneSessionData) {
                    $bounceInformation = $oneSessionData['bounceSessions']['buckets'];
                    if (count($bounceInformation) < 2)
                        continue;
                    foreach ($bounceInformation as $oneBounceData) {
                        if ($oneBounceData['key'] == 1) {
                            $dataToPush              = new stdClass();
                            $dataToPush->Date        = $this->MISCommonLib->extractDate($oneSessionData['key_as_string']);
                            $numberOfBounceSessions  = $oneBounceData['doc_count'];
                            $dataToPush->ScalarValue = number_format(($numberOfBounceSessions / $oneSessionData['doc_count']) * 100, 2, ".", "");
                            $resultsForGraph[]       = $dataToPush;
                        }
                    }
                }
                break;

            case 'pageview':
                $elasticQuery = $this->MISCommonLib->preparePageviewQuery($dateRange, $pageName, $extraData);

                $pageviews = $this->clientCon->search($elasticQuery);

                foreach ($pageviews['aggregations']['dateWise']['buckets'] as $onePageview) {
                    $date                    = $this->MISCommonLib->extractDate($onePageview['key_as_string']);
                    $dataToPush              = new stdClass();
                    $dataToPush->Date        = $date;
                    $dataToPush->ScalarValue = $onePageview['doc_count'];
                    $resultsForGraph[]       = $dataToPush;
                }

                break;

            case 'avgsessdur':
                $elasticQuery = $this->prepareTrafficQuery($dateRange, $pageName, $extraData);

                $elasticQuery['body']['aggs']['dateWise']['aggs'] = array(
                    'totalDuration' => array(
                        'sum' => array(
                            'field' => 'duration'
                        )
                    )
                );
                $sessionDurationInformation                       = $this->clientCon->search($elasticQuery);

                foreach ($sessionDurationInformation['aggregations']['dateWise']['buckets'] as $oneSessionDuration) {
                    $date                    = $this->MISCommonLib->extractDate($oneSessionDuration['key_as_string']);
                    $numberOfSessions        = $oneSessionDuration['doc_count'];
                    $sessionDurationThisDate = $oneSessionDuration['totalDuration']['value'];

                    $dataToPush              = new stdClass();
                    $dataToPush->Date        = $date;
                    $dataToPush->ScalarValue = number_format($sessionDurationThisDate / $numberOfSessions, 2, ".", "");
                    $resultsForGraph[]       = $dataToPush;
                }

                break;

            case 'exit':

                $exitQuery           = $this->prepareTrafficQuery($dateRange, $pageName, $extraData);
                $exitInformation     = $this->clientCon->search($exitQuery);
                $pageViewQuery = $this->MISCommonLib->preparePageviewQuery($dateRange, $pageName, $extraData);
                $pageviews = $this->clientCon->search($pageViewQuery);

                foreach ($pageviews['aggregations']['dateWise']['buckets'] as $onePageview) {
                    $date                    = $this->MISCommonLib->extractDate($onePageview['key_as_string']);
                    $dataToPush              = new stdClass();
                    $dataToPush->Date        = $date;
                    $dataToPush->ScalarValue = $onePageview['doc_count'];
                    $resultsForGraph[]       = $dataToPush;
                }

                $exitResultsForGraph = array();

                foreach ($exitInformation['aggregations']['dateWise']['buckets'] as $key => $oneDeviceExitData) {
                    $date                    = $this->MISCommonLib->extractDate($oneDeviceExitData['key_as_string']);
                    $dataToPush              = new stdClass();
                    $dataToPush->Date        = $date;
                    $dataToPush->ScalarValue = $onePageview['doc_count'];
                    $exitResultsForGraph[]   = $dataToPush;
                }

                foreach ($exitResultsForGraph as $key => $oneDeviceExitData) { // Process the exit information
                    foreach($resultsForGraph as $oneKey => $oneResult){
                        if($oneResult->Date == $oneDeviceExitData->Date){
                            $exitResultsForGraph[$key]->ScalarValue = number_format($oneDeviceExitData->ScalarValue *100 / $oneResult->ScalarValue, 2);
                            break;
                        }
                    }
                }

                $resultsForGraph = $exitResultsForGraph;
                unset($exitResultsForGraph);

                break;
        }

        if(count($resultsForGraph) > 0){
            $resultsForGraph = $this->MISCommonLib->insertZeroValues($resultsForGraph, $dateRange, $extraData['National']['view']);
        }

        return $resultsForGraph;
    }

    /**
     * Get the data to be shown on the donut and the bar chart
     *
     * @param $dateRange
     * @param $extraData
     * @param $pageName
     *
     * @return array
     */
    public function getEngagementSplit($dateRange, $extraData, $pageName)
    {
        $splitName = $extraData['National']['splitAspect'];

        switch ($extraData['National']['pivot']) {
            case 'pageview':
                $elasticQuery                 = $this->MISCommonLib->preparePageviewQuery($dateRange, $pageName, $extraData);
                $totalCount = 0; // Will contain the total value as obtained after grouping
                $splitAspectFieldNames = array(
                    'utmSource' => 'utm_source',
                    'utmMedium' => 'utm_medium',
                    'utmCampaign' => 'utm_campaign',
                    'session' => 'source',
                    'page' => 'pageIdentifier',
                    'device' => 'isMobile'
                );


                switch ($splitName) {
                    case 'page':
                    case 'device':
                    case 'utmCampaign':
                    case 'utmMedium':
                    case 'utmSource':
                    case 'session':
                        $elasticQuery['body']['aggs'] = array(
                            'splits' => array(
                                'terms' => array(
                                    'field' => $splitAspectFieldNames[$splitName],
                                    'size'  => 0,
                                    'order' => array(
                                        '_count' => 'desc'
                                    )
                                )
                            )
                        );
                        $result                       = $this->clientCon->search($elasticQuery);
                        $totalPageViewCount = $result['hits']['total'];

                        foreach ($result['aggregations']['splits']['buckets'] as $oneResult) {
                            $splitData              = new stdClass();
                            $splitData->ScalarValue = $oneResult['doc_count'];
                            if (htmlentities($oneResult['key']) == 'yes') {
                                $splitData->PivotName = 'Mobile';
                            } else if (htmlentities($oneResult['key']) == 'no') {
                                $splitData->PivotName = 'Desktop';
                            } else {
                                $splitData->PivotName = ucfirst(htmlentities($oneResult['key']));
                            }
                            $totalCount += (str_replace(",","", $splitData->ScalarValue));
                            $splits[]               = $splitData;
                        }

                        if($totalCount < $totalPageViewCount){ // Add 'Other' case
                            $splitData = new stdClass();
                            $splitData->ScalarValue = $totalPageViewCount - $totalCount;
                            $splitData->PivotName = 'Other';
                            $splits[] = $splitData;
                        }

                        arsort($splits);

                        $splitResults = array();
                        foreach ($splits as $currentArrayIndex => $oneSplit){
                            $splitData = new stdClass();
                            $rawValue = $oneSplit->ScalarValue;
                            $splitData->ScalarValue = number_format($oneSplit->ScalarValue);
                            $splitData->PivotName = $oneSplit->PivotName;
                            $splitData->Percentage = number_format($rawValue * 100 / $totalPageViewCount, 2);
                            $splitResults[] = $splitData;

                            unset($splits[$currentArrayIndex]);
                        }

                        return $splitResults;
                }

                break;

            case 'pgpersess':
                $elasticQuery = $this->prepareTrafficQuery($dateRange, $pageName, $extraData);

                $pageviewAggregation = array(
                    'pageviewWise' => array(
                        'sum' => array(
                            'field' => 'pageviews'
                        )
                    )
                );

                $splitAspectFieldNames = array(
                    'utmSource' => 'utm_source',
                    'utmMedium' => 'utm_medium',
                    'utmCampaign' => 'utm_campaign',
                    'session' => 'source',
                    'page' => 'landingPageDoc.pageIdentifier',
                    'device' => 'isMobile'
                );
                switch ($splitName) {
                    case 'page':
                    case 'device':
                    case 'utmCampaign':
                    case 'utmMedium':
                    case 'utmSource':
                    case 'session':
                        $pageAggregation = array(
                            'splits' => array(
                                'terms' => array(
                                    'field' => $splitAspectFieldNames[$splitName],
                                    'size' => 0
                                ),
                                'aggs' => $pageviewAggregation
                            )
                        );

                        $elasticQuery['body']['aggs'] = $pageAggregation;
                        $result                       = $this->clientCon->search($elasticQuery);

                        if(count($result['aggregations']['splits']['buckets']) == 0 ){
                            return array();
                        }
                        foreach ($result['aggregations']['splits']['buckets'] as $oneResult) {
                            $splitData              = new stdClass();
                            $splitData->ScalarValue = number_format($oneResult['pageviewWise']['value'] / $oneResult['doc_count'], 2, '.', '');
                            if (htmlentities($oneResult['key']) == 'yes') {
                                $splitData->PivotName = 'Mobile';
                            } else if (htmlentities($oneResult['key']) == 'no') {
                                $splitData->PivotName = 'Desktop';
                            } else {
                                $splitData->PivotName = ucfirst(htmlentities($oneResult['key']));
                            }
                            $splits[]               = $splitData;
                        }
                        arsort($splits);
                        return array_values($splits);
                }
                break;

            case 'bounce':

                $bounceAggregation = array(
                    'bounceWise' => array(
                        'terms' => array(
                            'field' => 'bounce',
                            'size' => 0
                        )
                    )
                );

                $elasticQuery = $this->prepareTrafficQuery($dateRange, $pageName, $extraData);
                $splitAspectFieldNames = array(
                    'utmSource' => 'utm_source',
                    'utmMedium' => 'utm_medium',
                    'utmCampaign' => 'utm_campaign',
                    'session' => 'source',
                    'page' => 'landingPageDoc.pageIdentifier',
                    'device' => 'isMobile'
                );

                switch ($splitName) {
                    case 'page':
                    case 'device':
                    case 'utmCampaign':
                    case 'utmMedium':
                    case 'utmSource':
                    case 'session':
                        $pageAggregation = array(
                            'splits' => array(
                                'terms' => array(
                                    'field' => $splitAspectFieldNames[ $splitName ],
                                    'size'  => 0
                                ),
                                'aggs'  => $bounceAggregation
                            )
                        );

                        $elasticQuery['body']['aggs'] = $pageAggregation;
                        $result                       = $this->clientCon->search($elasticQuery);

                        foreach ($result['aggregations']['splits']['buckets'] as $oneResult) {

                            if (count($oneResult['bounceWise']['buckets']) < 2)
                                continue;

                            foreach ($oneResult['bounceWise']['buckets'] as $bounceOrNot) {
                                if ($bounceOrNot['key'] == 1) {
                                    $splitData              = new stdClass();
                                    $splitData->ScalarValue = number_format($bounceOrNot['doc_count'] / $oneResult['doc_count'] * 100, 2, '.', '');
                                    if (htmlentities($oneResult['key']) == 'yes') {
                                        $splitData->PivotName = 'Mobile';
                                    } else if (htmlentities($oneResult['key']) == 'no') {
                                        $splitData->PivotName = 'Desktop';
                                    } else {
                                        $splitData->PivotName = ucfirst(htmlentities($oneResult['key']));
                                    }
                                    $splits[] = $splitData;
                                }
                            }
                        }
                        arsort($splits);
                        return array_values($splits);
                }

                break;

            case 'avgsessdur':
                $totalDuration = array(
                    'totalDuration' => array(
                        'sum' => array(
                            'field' => 'duration',
                        )
                    )
                );

                $elasticQuery = $this->prepareTrafficQuery($dateRange, $pageName, $extraData);
                $splitAspectFieldNames = array(
                    'utmSource' => 'utm_source',
                    'utmMedium' => 'utm_medium',
                    'utmCampaign' => 'utm_campaign',
                    'session' => 'source',
                    'page' => 'landingPageDoc.pageIdentifier',
                    'device' => 'isMobile'
                );

                switch ($splitName) {
                    case 'page':
                    case 'device':
                    case 'utmCampaign':
                    case 'utmSource':
                    case 'utmMedium':
                    case 'session':

                        $sessionDurationAggregation = array(
                            'splits' => array(
                                'terms' => array(
                                    'field' => $splitAspectFieldNames[$splitName],
                                    'size' => 0,
                                ),
                                'aggs' => $totalDuration
                            )
                        );

                        $elasticQuery['body']['aggs'] = $sessionDurationAggregation;
                        $result                       = $this->clientCon->search($elasticQuery);
                        foreach ($result['aggregations']['splits']['buckets'] as $oneResult) {
                            $chartOnOneDate               = new stdClass();
                            $averageSessionDurationInSeconds = number_format($oneResult['totalDuration']['value'] / $oneResult['doc_count'], 2, '.', '');
                            $chartOnOneDate->RawValue  = $averageSessionDurationInSeconds;
                            $hourFormat = explode('.',$averageSessionDurationInSeconds);
                            $chartOnOneDate->ScalarValue = date('i:s', mktime(0, 0, $hourFormat[0]));
                            if($oneResult['key'] == 'yes'){
                                $chartOnOneDate->PivotName = 'Mobile';
                            } else if($oneResult['key'] == 'no'){
                                $chartOnOneDate->PivotName = 'Desktop';
                            } else {
                                $chartOnOneDate->PivotName = ucfirst(htmlentities($oneResult['key']));
                            }
                            $splits[] = $chartOnOneDate;
                        }

                        arsort($splits);
                        return array_values($splits);
                }
                break;

            case 'exit':
                $exitQuery           = $this->prepareTrafficQuery($dateRange, $pageName, $extraData);
                unset($exitQuery['body']['aggs']);

                $pageViewQuery = $this->MISCommonLib->preparePageviewQuery($dateRange, $pageName, $extraData);
                unset($pageViewQuery['body']['aggs']);

                $splitAspectFieldNames = array(
                    'utmSource' => 'utm_source',
                    'utmMedium' => 'utm_medium',
                    'utmCampaign' => 'utm_campaign',
                    'session' => 'source',
                    'page' => 'landingPageDoc.pageIdentifier',
                    'device' => 'isMobile'
                );

                switch ($splitName) {

                    case 'utmSource':
                    case 'utmMedium':
                    case 'utmCampaign':
                    case 'session':
                    case 'page':
                    case 'device':
                    $trafficExitAggregation = array(
                            'splits' => array(
                                'terms' => array(
                                    'field' => $splitAspectFieldNames[$splitName],
                                    'size' => 0,
                                    'order' => array(
                                        '_count' => 'desc'
                                    )
                                ),
                            )
                        );


                        $exitQuery['body']['aggs'] = $trafficExitAggregation;
                        $exitPageResult = $this->clientCon->search($exitQuery);

                        $totalExits = $exitPageResult['hits']['total'];

                        foreach($exitPageResult['aggregations']['splits']['buckets'] as $oneExit){
                            $oneSplit = new stdClass();
                            if($oneExit['key'] == 'yes'){
                                $oneSplit->PivotName = 'Mobile';
                            } else if ($oneExit['key'] == 'no'){
                                $oneSplit->PivotName = 'Desktop';
                            } else {
                                $oneSplit->PivotName = ucfirst($oneExit['key']);
                            }
                            $oneSplit->ScalarValue = $oneExit['doc_count'];
                            $oneSplit->Percentage = $oneSplit->ScalarValue * 100 / $totalExits;
                            $splits[] = $oneSplit;
                        }

                        $trafficPageViewAggregation = array(
                            'splits' => array(
                                'terms' => array(
                                    'field' => $splitAspectFieldNames[$splitName],
                                    'size'  => 0,
                                    'order' => array(
                                        '_count' => 'desc'
                                    )
                                ),
                            )
                        );

                        $pageViewQuery['body']['aggs'] = $trafficPageViewAggregation;

                        $pageViewResult = $this->clientCon->search($pageViewQuery);
                        $totalPageViews = $pageViewResult['hits']['total'];

                        $pageViews = array(); // Hold the pageview data temporarily
                        foreach($pageViewResult['aggregations']['splits']['buckets'] as $onePageView){
                            $oneSplit = new stdClass();
                            if($onePageView['key'] == 'yes'){
                                $oneSplit->PivotName = 'Mobile';
                            } else if ($onePageView['key'] == 'no'){
                                $oneSplit->PivotName = 'Desktop';
                            } else {
                                $oneSplit->PivotName = ucfirst($onePageView['key']);
                            }
                            $oneSplit->ScalarValue = $onePageView['doc_count'];
                            $oneSplit->Percentage = $oneSplit->ScalarValue * 100 / $totalPageViews;
                            $pageViews[] = $oneSplit;
                        }

                        foreach($splits as $key => $oneSplit){
                            foreach($pageViews as $onePageView){
                                if(strtolower($oneSplit->PivotName, $onePageView->PivotName) == 0){
                                    $splits[$key]->ScalarValue = number_format($oneSplit->ScalarValue * 100 / $onePageView->ScalarValue, 2);
                                    $splits[$key]->Percentage = number_format($oneSplit->Percentage * 100/ $onePageView->Percentage, 2);
                                    break;
                                }
                            }
                        }

                        return array_values($splits);
                }
                break;

        }
    }

    /**
     * Get the data to be shown in the data grid (the data table).
     * The reason why the pageName variable has not been passed on with extraData is due to the fact that old code was written this way. Since, the time of writing of this method of this method coincides with the time when the domestic MISs were being fixed, this old style has been adopted here.
     *
     * @param array $dateRange The startDate and the endDate
     * @param array $extraData A list of extra data to be passed to obtain the table data
     * @param string $pageName The pagename.
     *
     * @return array
     */
    public function getEngagementTable($dateRange, $extraData, $pageName =''){
        switch ($extraData["National"]['pivot']) {
            case 'pgpersess':

                $elasticQuery = $this->prepareTrafficQuery($dateRange, $pageName, $extraData);

                $pageviewsCountGrouping = array(
                    'pageViews' => array(
                        'sum' => array(
                            'field' => 'pageviews'
                        )
                    )
                );

                $deviceWiseGrouping = array(
                    'deviceWise' => array(
                        'terms' => array(
                            'field' => 'isMobile',
                            'size' => 0,
                            'order' => array(
                                '_count' => 'desc'
                            )
                        ),
                        'aggs' => $pageviewsCountGrouping
                    )
                );

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

                $elasticQuery['body']['aggs'] = array(
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

                $pagesPerSession = $this->clientCon->search($elasticQuery);

                $pageWiseData = $pagesPerSession['aggregations']['pageWise']['buckets'];
                foreach($pageWiseData as $oneRowInResult){
                    $pageName = $this->MISCommonLib->convertToWords($oneRowInResult['key']);
                    foreach ($oneRowInResult['sourceWise']['buckets'] as $oneResult) {
                        $trafficSourceName = ucfirst($oneResult['key']);

                        $sourceApplicationWiseData = $oneResult['deviceWise']['buckets'];
                        foreach ($sourceApplicationWiseData as $oneDeviceData) {
                            $oneRowForTable                = new stdClass();
                            $oneRowForTable->ResponseCount = number_format($oneDeviceData['pageViews']['value'] / $oneResult['doc_count'], 2, ".", "");
                            $oneRowForTable->PageName      = $pageName;
                            $oneRowForTable->TrafficSource = $trafficSourceName;
                            $oneRowForTable->DeviceName    = $oneDeviceData['key'] == 'yes' ? 'Mobile' : 'Desktop';
                            $gridData[]                    = $oneRowForTable;
                        }
                    }
                }

                MISCommonLib::arrangeTableData($gridData, true);
                return array_values($gridData);

            case 'bounce':
                $elasticQuery = $this->prepareTrafficQuery($dateRange, $pageName, $extraData);

                $bounceAggregation = array(
                    'bounceSessions' => array(
                        'terms' => array(
                            'field' => 'bounce',
                        )
                    )
                );

                $deviceWiseGrouping = array(
                    'deviceWise' => array(
                        'terms' => array(
                            'field' => 'isMobile',
                            'size' => 0,
                            'order' => array(
                                '_count' => 'desc'
                            )
                        ),
                        'aggs' => $bounceAggregation
                    )
                );

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

                $elasticQuery['body']['aggs'] = array(
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

                $bounceValues = $this->clientCon->search($elasticQuery);

                $bounceValues = $bounceValues['aggregations']['pageWise']['buckets'];

                foreach($bounceValues as $oneRowInResult){

                    $pageName = $this->MISCommonLib->convertToWords($oneRowInResult['key']);
                    foreach ($oneRowInResult['sourceWise']['buckets'] as $oneResult) {
                        $trafficSourceName = ucfirst($oneResult['key']);

                        $sourceApplicationWiseData = $oneResult['deviceWise']['buckets'];
                        foreach ($sourceApplicationWiseData as $oneDeviceData) {
                            $bounceInformation = $oneDeviceData['bounceSessions']['buckets'];
                            $deviceName = $oneDeviceData['key'] == 'yes' ? 'Mobile' : 'Desktop';
                            if (count($bounceInformation) < 2)
                                continue;
                            foreach ($bounceInformation as $oneBounceData) {
                                if ($oneBounceData['key'] == 1) {
                                    $numberOfBounceSessions  = $oneBounceData['doc_count'];
                                    $oneRowForTable                = new stdClass();
                                    $oneRowForTable->ResponseCount = number_format(($numberOfBounceSessions / $oneDeviceData['doc_count']) * 100, 2, ".", "");
                                    $oneRowForTable->PageName      = $pageName;
                                    $oneRowForTable->TrafficSource = $trafficSourceName;
                                    $oneRowForTable->DeviceName    = $deviceName;
                                    $gridData[]                    = $oneRowForTable;
                                }
                            }
                        }
                    }
                }

                MISCommonLib::arrangeTableData($gridData, true);
                return array_values($gridData);

            case 'pageview':
                $elasticQuery = $this->MISCommonLib->preparePageviewQuery($dateRange, $pageName, $extraData);

                $deviceWiseGrouping = array(
                    'deviceWise' => array(
                        'terms' => array(
                            'field' => 'isMobile',
                            'size' => 0,
                            'order' => array(
                                '_count' => 'desc'
                            )
                        ),
                    )
                );

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

                $elasticQuery['body']['aggs'] = array(
                    'pageWise' => array(
                        'terms' => array(
                            'field' => 'pageIdentifier',
                            'size' => 0,
                            'order' => array(
                                '_count' => 'desc'
                            )
                        ),
                        'aggs' => $sourceWiseGrouping
                    )
                );

                $pageviews = $this->clientCon->search($elasticQuery);
                $totalPageviews = $pageviews['hits']['total'];

                $pageWiseData = $pageviews['aggregations']['pageWise']['buckets'];
                foreach($pageWiseData as $oneRowInResult){
                    $pageName = $this->MISCommonLib->convertToWords($oneRowInResult['key']);
                    foreach ($oneRowInResult['sourceWise']['buckets'] as $oneResult) {
                        $trafficSourceName = ucfirst($oneResult['key']);

                        $sourceApplicationWiseData = $oneResult['deviceWise']['buckets'];
                        foreach ($sourceApplicationWiseData as $oneDeviceData) {
                            $oneRowForTable                = new stdClass();
                            $oneRowForTable->ResponseCount = $oneDeviceData['doc_count'];
                            $oneRowForTable->PageName      = $pageName;
                            $oneRowForTable->TrafficSource = $trafficSourceName;
                            $oneRowForTable->Percentage = number_format($oneDeviceData['doc_count'] * 100 / $totalPageviews, 2);

                            $oneRowForTable->DeviceName    = $oneDeviceData['key'] == 'yes' ? 'Mobile' : 'Desktop';
                            $gridData[]                    = $oneRowForTable;
                        }
                    }
                }

                MISCommonLib::arrangeTableData($gridData, true);
                return array_values($gridData);

            case 'avgsessdur':
                $elasticQuery = $this->prepareTrafficQuery($dateRange, $pageName, $extraData);

                $sessionDurations = array(
                    'totalDuration' => array(
                        'sum' => array(
                            'field' => 'duration'
                        )
                    )
                );

                $deviceWiseGrouping = array(
                    'deviceWise' => array(
                        'terms' => array(
                            'field' => 'isMobile',
                            'size' => 0,
                            'order' => array(
                                '_count' => 'desc'
                            )
                        ),
                        'aggs' => $sessionDurations
                    )
                );

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

                $elasticQuery['body']['aggs'] = array(
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

                $sessionDurationInformation                       = $this->clientCon->search($elasticQuery);

                $pageWiseData = $sessionDurationInformation['aggregations']['pageWise']['buckets'];
                foreach($pageWiseData as $oneRowInResult){
                    $pageName = $this->MISCommonLib->convertToWords($oneRowInResult['key']);
                    foreach ($oneRowInResult['sourceWise']['buckets'] as $oneResult) {
                        $trafficSourceName = ucfirst($oneResult['key']);

                        $sourceApplicationWiseData = $oneResult['deviceWise']['buckets'];
                        foreach ($sourceApplicationWiseData as $oneDeviceData) {
                            $oneRowForTable                = new stdClass();
                            $averageSessionDurationInSeconds = number_format($oneDeviceData['totalDuration']['value'] / $oneDeviceData['doc_count'], 2);
                            $oneRowForTable->RawValue = $averageSessionDurationInSeconds;
                            $hourFormat = explode('.',$averageSessionDurationInSeconds);
                            $oneRowForTable->ResponseCount = date('i:s', mktime(0, 0, $hourFormat[0]));
                            $oneRowForTable->PageName      = $pageName;
                            $oneRowForTable->TrafficSource = $trafficSourceName;
                            $oneRowForTable->DeviceName    = $oneDeviceData['key'] == 'yes' ? 'Mobile' : 'Desktop';
                            $gridData[]                    = $oneRowForTable;
                        }
                    }
                }

                MISCommonLib::arrangeTableData($gridData, true);
                return array_values($gridData);

            case 'exit':

                $pageviewQuery = $this->MISCommonLib->preparePageviewQuery($dateRange, $pageName, $extraData);

                $deviceWiseGrouping = array(
                    'deviceWise' => array(
                        'terms' => array(
                            'field' => 'isMobile',
                            'size' => 0,
                            'order' => array(
                                '_count' => 'desc'
                            )
                        ),
                    )
                );

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

                $pageviewQuery['body']['aggs'] = array(
                    'pageWise' => array(
                        'terms' => array(
                            'field' => 'pageIdentifier',
                            'size' => 0,
                            'order' => array(
                                '_count' => 'desc'
                            )
                        ),
                        'aggs' => $sourceWiseGrouping
                    )
                );

                $pageviews = $this->clientCon->search($pageviewQuery);
                $totalPageviews = $pageviews['hits']['total'];

                $pageWiseData = $pageviews['aggregations']['pageWise']['buckets'];
                foreach($pageWiseData as $oneRowInResult){
                    $pageName = $this->MISCommonLib->convertToWords($oneRowInResult['key']);
                    foreach ($oneRowInResult['sourceWise']['buckets'] as $oneResult) {
                        $trafficSourceName = ucfirst($oneResult['key']);

                        $sourceApplicationWiseData = $oneResult['deviceWise']['buckets'];
                        foreach ($sourceApplicationWiseData as $oneDeviceData) {
                            $oneRowForTable                = new stdClass();
                            $oneRowForTable->ResponseCount = $oneDeviceData['doc_count'];
                            $oneRowForTable->PageName      = $pageName;
                            $oneRowForTable->TrafficSource = $trafficSourceName;
                            $oneRowForTable->Percentage = number_format($oneDeviceData['doc_count'] * 100 / $totalPageviews, 2);

                            $oneRowForTable->DeviceName    = $oneDeviceData['key'] == 'yes' ? 'Mobile' : 'Desktop';
                            $gridData[]                    = $oneRowForTable;
                        }
                    }
                }

                arsort($gridData); // contains the pageview information


                $exitData = array();
                $exitQuery           = $this->prepareTrafficQuery($dateRange, $pageName, $extraData);

                $exitQuery['body']['aggs'] = array(
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
                $exitInformation     = $this->clientCon->search($exitQuery);
                $totalExits = $exitInformation['hits']['total'];

                $pageWiseData = $exitInformation['aggregations']['pageWise']['buckets'];
                foreach($pageWiseData as $oneRowInResult){
                    $pageName = $this->MISCommonLib->convertToWords($oneRowInResult['key']);
                    foreach ($oneRowInResult['sourceWise']['buckets'] as $oneResult) {
                        $trafficSourceName = ucfirst($oneResult['key']);

                        $sourceApplicationWiseData = $oneResult['deviceWise']['buckets'];
                        foreach ($sourceApplicationWiseData as $oneDeviceData) {
                            $oneRowForTable                = new stdClass();
                            $oneRowForTable->ResponseCount = $oneDeviceData['doc_count'];
                            $oneRowForTable->PageName      = $pageName;
                            $oneRowForTable->TrafficSource = $trafficSourceName;

                            $oneRowForTable->DeviceName    = $oneDeviceData['key'] == 'yes' ? 'Mobile' : 'Desktop';
                            $exitData[]                    = $oneRowForTable;
                        }
                    }
                }

                foreach ($exitData as $oneKey => $oneExitData) { // Process the exit information

                    foreach($gridData as $onePageviewKey => $onePageview){

                        if(
                            $oneExitData->DeviceName == $onePageview->DeviceName &&
                            $oneExitData->PageName == $onePageview->PageName &&
                            $oneExitData->TrafficSource == $onePageview->TrafficSource
                        ){
                            $exitData[$oneKey]->ResponseCount = number_format($oneExitData->ResponseCount / $onePageview->ResponseCount, 2);
                            break;
                        }
                    }
                }

                MISCommonLib::arrangeTableData($exitData, true);
                return array_values($exitData);

        }
    }

    /**
     * This method will act as the replacement of the existing method getQuery
     *
     * Can we move this to MISCommonLib to replace the method trafficDataLib::_prepareNationalFilters?
     * @param        $dateRange
     * @param string $pageName
     * @param array  $extraData
     *
     * @return array
     */
    private function prepareTrafficQuery($dateRange, $pageName='', $extraData=array())
    {
        $elasticQuery = array(
            'index' => MISCommonLib::$TRAFFICDATA_SESSIONS,
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
        $viewType = $extraData['National']['viewType'];

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
            $pageNameValue = $this->MISCommonLib->getPageNameForDomestic($pageName);
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

        $view = $this->MISCommonLib->decideView($dateRange, $extraData['National']['view'], 'es');

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

}
