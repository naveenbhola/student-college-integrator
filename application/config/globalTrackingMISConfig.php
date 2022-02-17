<?php

$config['leftMenuArray'] = array(
    'Shiksha' => array(
        'className' => "fa-home",
        'children' => array(
            'Overview' => SHIKSHA_HOME."/trackingMIS/Dashboard/overview",
            'Traffic' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/shiksha/traffic",
            'Engagement' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/shiksha/engagement",
            'Registrations' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/shiksha/registration",
            //'Leads' => SHIKSHA_HOME."/trackingMIS/Dashboard/leads",
            'Responses' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/shiksha/response",

        )
    )
);

$config['PAGE_METRIC_MAPPING'] = array(
    "DOMESTIC" => array(
        "Domestic Snapshot"            => array("pageIdentifier"=>"", "metric" => array("Overview","Traffic", "Engagement", "Registration", "Response","Shiksha Assistant")),
        "Exam Page Group"                    => array("pageIdentifier"=>"examPageMain", "metric" => array("Traffic", "Engagement", "Registration", "Response"))/*,
        "Home Page"                    => array("pageIdentifier"=>"homePage", "metric" => array("Traffic", "Engagement", "Registration", "Response")),
        "Category Page"                => array("pageIdentifier"=>"categoryPage", "metric" => array("Traffic", "Engagement", "Registration", "Response")),
        "Search Result Page"           => array("pageIdentifier"=>"SRP", "metric" => array("Traffic", "Engagement", "Registration", "Response")),
        "University Listing Page"      => array("pageIdentifier"=>"universityListingPage", "metric" => array("Traffic", "Engagement", "Registration", "Response")),
        "Course Details Page"          => array("pageIdentifier"=>"courseDetailsPage", "metric" => array("Traffic", "Engagement", "Registration", "Response")),
        "Ranking Page"                 => array("pageIdentifier"=>"rankingPage", "metric" => array("Traffic", "Engagement", "Registration", "Response")),
        "Question Detail Page"         => array("pageIdentifier"=>"questionDetailPage", "metric" => array("Traffic", "Engagement", "Registration", "Response")),
        "Article Detail Page"          => array("pageIdentifier"=>"articleDetailPage", "metric" => array("Traffic", "Engagement", "Registration", "Response")),
        "College Predictor"            => array("pageIdentifier"=>"collegePredictor", "metric" => array("Traffic", "Engagement", "Registration", "Response")),
        "Rank Predictor"               => array("pageIdentifier"=>"rankPredictor", "metric" => array("Traffic", "Engagement", "Registration", "Response")),
        "Tag Detail Page"              => array("pageIdentifier"=>"tagDetailPage", "metric" => array("Traffic", "Engagement", "Registration", "Response")),
        "IIM - Non IIM Call Predictor" => array("pageIdentifier"=>"iimPredictorInput", "metric" => array("Traffic", "Engagement", "Registration", "Response")),
        "Course Home Page"             => array("pageIdentifier"=>"CHP", "metric" => array("Traffic", "Engagement", "Registration", "Response")),*/
    /*
        "Domestic Snapshot"         => array("pageIdentifier"=>"", "metric" => array("Overview","Traffic", "Engagement", "Registration", "Response")),
        "Admission Page"            => array("pageIdentifier"=>"admissionPage", "metric" => array("Traffic", "Engagement", "Registration", "Response")),
        "Advisory Board Page"       => array("pageIdentifier"=>"advisoryBoardPage", "metric" => array("Traffic", "Engagement", "Registration", "Response")),
        "All Article Page"            => array("pageIdentifier"=>"allArticlePage", "metric" => array("Traffic", "Engagement", "Registration", "Response")),
        "All Courses Page"            => array("pageIdentifier"=>"allCoursesPage", "metric" => array("Traffic", "Engagement", "Registration", "Response")),
        "All Discussions Page"        => array("pageIdentifier"=>"allDiscussionsPage", "metric" => array("Traffic", "Engagement", "Registration", "Response")),
        "All Exam Page"               => array("pageIdentifier"=>"allExamPage", "metric" => array("Traffic", "Engagement", "Registration", "Response")),
        "All Questions Page"          => array("pageIdentifier"=>"allQuestionsPage", "metric" => array("Traffic", "Engagement", "Registration", "Response")),
        "All Reviews Page"            => array("pageIdentifier"=>"allReviewsPage", "metric" => array("Traffic", "Engagement", "Registration", "Response")),
        "Article Author Profile Page"  => array("pageIdentifier"=>"articleAuthorProfilePage", "metric" => array("Traffic", "Engagement", "Registration", "Response")),
        "Article Detail Page"         => array("pageIdentifier"=>"articleDetailPage", "metric" => array("Traffic", "Engagement", "Registration", "Response")),
        "Campus Representative"      => array("pageIdentifier"=>"campusRepresentative", "metric" => array("Traffic", "Engagement", "Registration", "Response")),
        "Career Compas Page"          => array("pageIdentifier"=>"careerCompasPage", "metric" => array("Traffic", "Engagement", "Registration", "Response")),
        "Career Counselling"         => array("pageIdentifier"=>"careerCounselling", "metric" => array("Traffic", "Engagement", "Registration", "Response")),
        "Career Detail Page"          => array("pageIdentifier"=>"careerDetailPage", "metric" => array("Traffic", "Engagement", "Registration", "Response")),
        "Career Home Page"            => array("pageIdentifier"=>"careerHomePage", "metric" => array("Traffic", "Engagement", "Registration", "Response")),
        "Career Opportunities"       => array("pageIdentifier"=>"careerOpportunities", "metric" => array("Traffic", "Engagement", "Registration", "Response")),
        "Category Page"              => array("pageIdentifier"=>"categoryPage", "metric" => array("Traffic", "Engagement", "Registration", "Response")),
        "CHP"                       => array("pageIdentifier"=>"CHP", "metric" => array("Traffic", "Engagement", "Registration", "Response")),
        "College Predictor"          => array("pageIdentifier"=>"collegePredictor", "metric" => array("Traffic", "Engagement", "Registration", "Response")),
        "College Review Page"         => array("pageIdentifier"=>"collegeReviewPage", "metric" => array("Traffic", "Engagement", "Registration", "Response")),
        "College Review Rating Form"   => array("pageIdentifier"=>"collegeReviewRatingForm", "metric" => array("Traffic", "Engagement", "Registration", "Response")),
        "Compare Page"               => array("pageIdentifier"=>"comparePage", "metric" => array("Traffic", "Engagement", "Registration", "Response")),
        "Course Details Page"         => array("pageIdentifier"=>"courseDetailsPage", "metric" => array("Traffic", "Engagement", "Registration", "Response")),
        "Discussion Detail Page"      => array("pageIdentifier"=>"discussionDetailPage", "metric" => array("Traffic", "Engagement", "Registration", "Response")),
        "Event Calendar"             => array("pageIdentifier"=>"eventCalendar", "metric" => array("Traffic", "Engagement", "Registration", "Response")),
        "Exam Page"                  => array("pageIdentifier"=>"examPage", "metric" => array("Traffic", "Engagement", "Registration", "Response")),
        "Home Page"                  => array("pageIdentifier"=>"homePage", "metric" => array("Traffic", "Engagement", "Registration", "Response")),
        "Online Application Form"     => array("pageIdentifier"=>"onlineApplicationForm", "metric" => array("Traffic", "Engagement", "Registration", "Response")),
        "Qna Page"                   => array("pageIdentifier"=>"qnaPage", "metric" => array("Traffic", "Engagement", "Registration", "Response")),
        "Question Detail Page"        => array("pageIdentifier"=>"questionDetailPage", "metric" => array("Traffic", "Engagement", "Registration", "Response")),
        "Ranking Page"               => array("pageIdentifier"=>"rankingPage", "metric" => array("Traffic", "Engagement", "Registration", "Response")),
        "Rank Predictor"             => array("pageIdentifier"=>"rankPredictor", "metric" => array("Traffic", "Engagement", "Registration", "Response")),
        "Scholarship Page"           => array("pageIdentifier"=>"scholarshipPage", "metric" => array("Traffic", "Engagement", "Registration", "Response")),
        "Search Page"                => array("pageIdentifier"=>"searchPage", "metric" => array("Traffic", "Engagement", "Registration", "Response")),
        "Shiksha Authors Profile Page" => array("pageIdentifier"=>"shikshaAuthorsProfilePage", "metric" => array("Traffic", "Engagement", "Registration", "Response")),
        "Shortlist Page"             => array("pageIdentifier"=>"shortlistPage", "metric" => array("Traffic", "Engagement", "Registration", "Response")),
        "Student Forms DashBoard Page" => array("pageIdentifier"=>"studentFormsDashBoardPage", "metric" => array("Traffic", "Engagement", "Registration", "Response")),
        "Tag Detail Page"             => array("pageIdentifier"=>"tagDetailPage", "metric" => array("Traffic", "Engagement", "Registration", "Response")),
        "University Listing Page"     => array("pageIdentifier"=>"universityListingPage", "metric" => array("Traffic", "Engagement", "Registration", "Response")),
        "UserPointSystemInfo Page"   => array("pageIdentifier"=>"userPointSystemInfoPage", "metric" => array("Traffic", "Engagement", "Registration", "Response")),
        "View All Tags Page"           => array("pageIdentifier"=>"viewAllTagsPage", "metric" => array("Traffic", "Engagement", "Registration", "Response"))
    */
    )    
);

$config['METRIC'] = array(
    'OVERVIEW' => array(
        'SA_TOP_Tiles' => array('Unique Users','Sessions','Page Views','Avg Session Duration','(Paid Responses)/(Paid Courses)','Total Registrations'),

        'BAR_GRAPH' => array(
            'TRAFFIC' => array(
                "TOP_PAGES" => array(
                    "heading" => 'Top Pages',
                ),
                "TOP_COUNTRY" => array(
                    "heading" => 'Top Countries',
                ),
                "TOP_CITIES" => array(
                    "heading" => 'Top Cities',
                ),
            ),

            'REGISTRATION' => array(
                "TOP_PAGES" => array(
                    "heading" => 'Top Pages',
                ),
                "TOP_COUNTRY" => array(
                    "heading" => 'Top Countries',
                ),
                "TOP_CITIES" => array(
                    "heading" => 'Top Cities',
                ),
            ),

            'RESPONSES' => array(
                "TOP_PAGES"       => array(
                    "heading" => 'Top Pages',
                ),
                "TOP_CITIES"      => array(
                    "heading" => 'Top Cities',
                ),
            )
        )
    ),
    'TRAFFIC' => array(
        'OVERALL' => array(
            'TOP_TILES' => array(
                'COMMON' => array(
                    'Users'                 => array('title' => 'Users', 'id' => 'visitorId'),
                    'Sessions'            => array('title' => 'Sessions', 'id' => 'sessions'),
                    'pageViews'          => array('title' => 'Page Views', 'id' => 'pageviews'),
                    'perNewSessions'         => array('title' => '% New Sessions', 'id' => 'sessionNumber'),
                ),
                'SHIKSHA' => array(),
                'DOMESTIC' => array(),
                'STUDY ABROAD' => array(),
            ),
            'LINE_CHART' => array(
                'heading'         => 'Users',
                'field'        => 'noField',
                'id'		=> 'lineChartDiv'
            ),
            'PIE_CHART' => array(
                'COMMON' => array(
                    'Source Application' => array(
                        'id' => 'device',
                        'fieldName' => 'isMobile',
                        'title' => 'Source Application'
                    ),
                    'Traffic Source' => array(
                        'id' => 'trafficSource',
                        'fieldName' => 'source',
                        'title' => 'Traffic Source'
                    )
                ),
                'SHIKSHA' => array(
                    'Page Wise' => array(
                        'id' => 'pageIdentifier',
                        'fieldName' => 'landingPageDoc.pageIdentifier',
                        'title' => 'Page'
                    ),
                ),

                'DOMESTIC' => array(),
                'STUDY ABROAD' => array(),
            ),
            'BAR_GRAPH' => array(
                'COMMON' => array(
                    "UTM Source" => array(
                        "id" => 'UTM_Source',
                        'fieldName' => 'utm_source',
                        'title' => 'UTM Source'
                    ),
                    "UTM Medium" => array(
                        "id" => 'UTM_Medium',
                        'fieldName' => 'utm_medium',
                        'title' => 'UTM Medium'
                    ),
                    "UTM Campaign" => array(
                        "id" => 'UTM_Campaign',
                        'fieldName' => 'utm_campaign',
                        'title' => 'UTM Campaign'
                    ),
                ),
                'SHIKSHA' => array(),
                'DOMESTIC' => array(),
                'STUDY ABROAD' => array(),
            ),
            'DATA_TABLE' => array(
                'COMMON' => array(),
                'SHIKSHA' => array(),
                'DOMESTIC' => array(
                    'id' => 'dataTable',
                    'fields' => array(
                        'page' => array(
                            'field' => 'landingPageDoc.pageIdentifier',
                            'title' => 'Page'
                        ),
                        'trafficSource' => array(
                            'field' => 'source',
                            'title' => 'Traffic Source'
                        ),
                        'sourceApplication' => array(
                            'field' => 'isMobile',
                            'title' => 'Source Application'
                        ),
                    )
                ),
                'STUDY ABROAD' => array(
                    'id' => 'dataTable',
                    'fields' => array(
                        'page' => array(
                            'field' => 'landingPageDoc.pageIdentifier',
                            'title' => 'Page'
                        ),
                        'trafficSource' => array(
                            'field' => 'source',
                            'title' => 'Traffic Source'
                        ),
                        'sourceApplication' => array(
                            'field' => 'isMobile',
                            'title' => 'Source Application'
                        ),
                    ),
                ),
            ),
        ),
        'PAGEWISE' => array(
            'TOP_TILES' => array(
                'COMMON' => array(
                    'Users'                 => array('title' => 'Users', 'id' => 'visitorId'),
                    'Sessions'            => array('title' => 'Sessions', 'id' => 'sessions'),
                    'pageViews'          => array('title' => 'Page Views', 'id' => 'pageviews'),
                    'perNewSessions'         => array('title' => '% New Sessions', 'id' => 'sessionNumber'),
                ),
                'SHIKSHA' => array(),
                'DOMESTIC' => array(),
                'STUDY ABROAD' => array(),
            ),
            'LINE_CHART' => array(
                'heading'         => 'Users',
                'field'        => 'noField',
                'id'		=> 'lineChartDiv'
            ),
            'PIE_CHART' => array(
                'COMMON' => array(
                    'Source Application' => array(
                        'id' => 'device',
                        'fieldName' => 'isMobile',
                        'title' => 'Source Application'
                    ),
                    'Traffic Source' => array(
                        'id' => 'trafficSource',
                        'fieldName' => 'source',
                        'title' => 'Traffic Source'
                    ),
                ),
                'SHIKSHA' => array(),
                'DOMESTIC' => array(),
                'STUDY ABROAD' => array(),
            ),
            'BAR_GRAPH' => array(
                'COMMON' => array(
                    "UTM Source" => array(
                        "id" => 'UTM_Source',
                        'fieldName' => 'utm_source',
                        'title' => 'UTM Source'
                    ),
                    "UTM Medium" => array(
                        "id" => 'UTM_Medium',
                        'fieldName' => 'utm_medium',
                        'title' => 'UTM Medium'
                    ),
                    "UTM Campaign" => array(
                        "id" => 'UTM_Campaign',
                        'fieldName' => 'utm_campaign',
                        'title' => 'UTM Campaign'
                    ),
                ),
                'SHIKSHA' => array(),
                'DOMESTIC' => array(),
                'STUDY ABROAD' => array(
                ),
            ),
            'DATA_TABLE' => array(
                'COMMON' => array(),
                'SHIKSHA' => array(),
                'DOMESTIC' => array(
                    'id' => 'dataTable',
                    'fields' => array(
                        'page' => array(
                            'field' => 'landingPageDoc.pageIdentifier',
                            'title' => 'Page'
                        ),
                        'trafficSource' => array(
                            'field' => 'source',
                            'title' => 'Traffic Source'
                        ),
                        'sourceApplication' => array(
                            'field' => 'isMobile',
                            'title' => 'Source Application'
                        ),
                    )
                ),
                'STUDY ABROAD' => array(
                    'id' => 'dataTable',
                    'fields' => array(
                        'keyName' => array(
                            'field' => 'landingPageDoc.pageEntityId',
                            'title' => 'Action'
                        ),
                        'trafficSource' => array(
                            'field' => 'source',
                            'title' => 'Traffic Source'
                        ),
                        'sourceApplication' => array(
                            'field' => 'isMobile',
                            'title' => 'Source Application'
                        ),
                    )
                ),
            ),
        ),
        'ajaxDestinationURL' => "/trackingMIS/Dashboard/diffChartDataForMIS/",
    ),

    'REGISTRATION' => array(
        'OVERALL' => array(
            'TOP_TILES' => array(
                'COMMON' => array(
                    'totalCount'                 => array('title' => 'Total Registration', 'id' => 'totalRegistration'),
                    'mmpCount'            => array('title' => 'MMP Registrations', 'id' => 'mmpRegistration'),
                    'responseRegCount'          => array('title' => 'Responses Registration', 'id' => 'responseRegistration'),
                    'signupRegCount'           => array('title' => 'Signup Registration', 'id' => 'signUpRegistration'),
                    'hamburgerRegCount'         => array('title' => 'Hamburger Registrations', 'id' => 'hamburgerRegistration'),
                ),
                'SHIKSHA' => array(),
                'DOMESTIC' => array(),
                'STUDY ABROAD' => array(
                    'guideRegCount'         => array('title' => 'Guide Registrations', 'id' => 'guideRegistration'),
                ),
            ),
            'LINE_CHART' => array(
                'heading'         => 'Registrations',
                'field'        => 'registrationDate',
                'id'		=> 'lineChartDiv'
            ),
            'PIE_CHART' => array(
                'COMMON' => array(
                    'Source Application' => array(
                        'id' => 'sourceApplication',
                        'fieldName' => 'sourceApplication',
                        'title' => 'Source Application'
                    ),
                    'Traffic Source' => array(
                        'id' => 'trafficSource',
                        'fieldName' => 'trafficSource',
                        'title' => 'Traffic Source'
                    ),
                    'Action' => array(
                        'id' => 'keyName',
                        'fieldName' => 'keyName',
                        'title' => 'Action'
                    ),
                    'Type' => array(
                        'id' => 'type',
                        'fieldName' => 'source',
                        'title' => 'Paid / Free'
                    ),
                    'Page Wise' => array(
                        'id' => 'pageIdentifier',
                        'fieldName' => 'pageIdentifier',
                        'title' => 'Page'
                    ),
                ),
                'SHIKSHA' => array(),
                'DOMESTIC' => array(),
                'STUDY ABROAD' => array(),
            ),
            'BAR_GRAPH' => array(
                'COMMON' => array(
                    "UTM Source" => array(
                        "id" => 'UTM_Source',
                        'fieldName' => 'utmSource',
                        'title' => 'UTM Source'
                    ),
                    "UTM Medium" => array(
                        "id" => 'UTM_Medium',
                        'fieldName' => 'utmMedium',
                        'title' => 'UTM Medium'
                    ),
                    "UTM Campaign" => array(
                        "id" => 'UTM_Campaign',
                        'fieldName' => 'utmCampaign',
                        'title' => 'UTM Campaign'
                    ),

                ),
                'SHIKSHA' => array(),
                'DOMESTIC' => array(),
                'STUDY ABROAD' => array(
                ),
            ),
            'DATA_TABLE' => array(
                'COMMON' => array(),
                'SHIKSHA' => array(),
                'DOMESTIC' => array(
                    'id' => 'dataTable',
                    'fields' => array(
                        'page' => array('field' => 'pageIdentifier',
                            'title' => 'Page'),
                        'trafficSource' => array('field' => 'trafficSource',
                            'title' => 'Traffic Source'),
                        'sourceApplication' => array('field' => 'sourceApplication',
                            'title' => 'Source Application'),
                    )
                ),
                'STUDY ABROAD' => array(
                    'id' => 'dataTable',
                    'fields' => array(
                        'page' => array('field' => 'pageIdentifier',
                            'title' => 'Page'),
                        'trafficSource' => array('field' => 'trafficSource',
                            'title' => 'Traffic Source'),
                        'sourceApplication' => array('field' => 'sourceApplication',
                            'title' => 'Traffic Source'),

                    ),
                ),
            ),
        ),
        'PAGEWISE' => array(
            'TOP_TILES' => array(
                'COMMON' => array(
                    'totalCount'                 => array('title' => 'Total Registration', 'id' => 'totalRegistration'),
                    'mmpCount'            => array('title' => 'MMP Registrations', 'id' => 'mmpRegistration'),
                    'responseRegCount'          => array('title' => 'Responses Registration', 'id' => 'responseRegistration'),
                    'signupRegCount'           => array('title' => 'Signup Registration', 'id' => 'signUpRegistration'),
                    'hamburgerRegCount'         => array('title' => 'Hamburger Registrations', 'id' => 'hamburgerRegistration'),
                ),
                'SHIKSHA' => array(),
                'DOMESTIC' => array(),
                'STUDY ABROAD' => array(
                ),
            ),
            'LINE_CHART' => array(
                'heading'         => 'Registrations',
                'field'        => 'registrationDate',
                'id'		=> 'lineChartDiv'
            ),
            'PIE_CHART' => array(
                'COMMON' => array(
                    'Source Application' => array(
                        'id' => 'device',
                        'fieldName' => 'sourceApplication',
                        'title' => 'Source Application'
                    ),
                    'Traffic Source' => array(
                        'id' => 'trafficSource',
                        'fieldName' => 'trafficSource',
                        'title' => 'Traffic Source'
                    ),
                    'Action' => array(
                        'id' => 'keyName',
                        'fieldName' => 'keyName',
                        'title' => 'Action'
                    ),
                    'Type' => array(
                        'id' => 'type',
                        'fieldName' => 'source',
                        'title' => 'Paid / Free'
                    ),
                ),
                'SHIKSHA' => array(),
                'DOMESTIC' => array(),
                'STUDY ABROAD' => array(),
            ),
            'BAR_GRAPH' => array(
                'COMMON' => array(
                    "UTM Source" => array(
                        "id" => 'UTM_Source',
                        'fieldName' => 'utmSource',
                        'title' => 'UTM Source'
                    ),
                    "UTM Medium" => array(
                        "id" => 'UTM_Medium',
                        'fieldName' => 'utmMedium',
                        'title' => 'UTM Medium'
                    ),
                    "UTM Campaign" => array(
                        "id" => 'UTM_Campaign',
                        'fieldName' => 'utmCampaign',
                        'title' => 'UTM Campaign'
                    ),

                ),
                'SHIKSHA' => array(),
                'DOMESTIC' => array(),
                'STUDY ABROAD' => array(
                ),
            ),
            'DATA_TABLE' => array(
                'COMMON' => array(),
                'SHIKSHA' => array(),
                'DOMESTIC' => array(
                    'id' => 'dataTable',
                    'fields' => array(
                        'keyName' => array('field' => 'keyName',
                            'title' => 'Action'),

                        'widget' => array('field' => 'widget',
                            'title' => 'Widget'),
                        'sourceApplication' => array('field' => 'sourceApplication',
                            'title' => 'Source Application'),
                    )
                ),
                'STUDY ABROAD' => array(
                    'id' => 'dataTable',
                    'fields' => array(
                        'keyName' => array('field' => 'keyName',
                            'title' => 'Action'),
                        'widget' => array('field' => 'widget',
                            'title' => 'Widget'),
                        'sourceApplication' => array('field' => 'sourceApplication',
                            'title' => 'Source Application'),
                    )
                ),
            ),
        ),
        'ajaxDestinationURL' => "/trackingMIS/Dashboard/diffChartDataForMIS/",
    ),
    'SEARCH_HOME' => array(
        'TOP_TILES' => array(
            'COMMON' => array(
            ),
            'SHIKSHA' => array(),
            'DOMESTIC' => array(),
            'STUDY ABROAD' => array(
                'totalSearch'               => array('title' => 'Total Searches', 'id' => 'totalSearch'),
                'searchPerRespondent'       => array('title' => 'Searches/Respondent', 'id' => 'searchPerRespondent'),
                'totalFilterApplied'        => array('title' => 'Total Filter Applied', 'id' => 'totalFilterApplied'),
                'totalSortingApplied'     	=> array('title' => 'Total Sorting Applied', 'id' => 'totalSortingApplied'),
                'totalInteraction'         	=> array('title' => 'Total Interactions', 'id' => 'totalInteraction'),
                'historySearchPercentage'	=> array('title' => 'History Searches %', 'id' => 'historySearchPercentage')
            ),
        ),
        'LINE_CHART' => array(
            'heading'         => 'Total Searches',
            'field'        => 'searchTime',
            'id'		=> 'lineChartDiv'
        ),
        'PIE_CHART' => array(
            'COMMON' => array(
            ),
            'SHIKSHA' => array(),
            'DOMESTIC' => array(),
            'STUDY ABROAD' => array(
                'Source Application' => array(  //direct
                    'id' => 'device',
                    'fieldName' => 'sourceApplication',
                    'title' => 'Source Application'
                ),
                /*'Traffic Source' => array(
                    'id' => 'trafficSource',
                    'fieldName' => 'visitorSessionId',
                    'title' => 'Traffic Source'   // //direct     require high memory and check for elastic search performance
              ),*/
                'Page Wise' => array(
                    'id' => 'pageIdentifier',
                    'fieldName' => 'trackingKeyId',
                    'title' => 'Source Page'      // direct group on tracink pagekey
                ),
                'Searches Type' => array(
                    'id' => 'searchType',
                    'fieldName' => 'searchType',
                    'title' => 'Searches Type'  //direct
                ),
                'SRT Type' => array(
                    'id' => 'SRPType',
                    'fieldName' => 'searchResultType',
                    'title' => 'SRP Type'  //direct
                ),
                'Searches Entity' => array(
                    'id' => 'searchEntity',
                    'fieldName' => 'searchEntity',
                    'title' => 'Searches Entity'
                ),
                'Users Interaction' => array(
                    'id' => 'usersInteraction',
                    'fieldName' => 'clickSource',
                    'title' => "Users' Interactions",
                    'addExtraDataToTitle' => false
                ),
            ),
        ),
        'BAR_GRAPH' => array(
            'COMMON' => array(
            ),
            'SHIKSHA' => array(),
            'DOMESTIC' => array(),
            'STUDY ABROAD' => array(
            ),
        ),
        'DATA_TABLE' => array(
            'COMMON' => array(),
            'SHIKSHA' => array(),
            'DOMESTIC' => array(
            ),
            'STUDY ABROAD' => array(
            ),
        ),
        'ajaxDestinationURL' => "/trackingMIS/Dashboard/diffChartDataForMIS/",
    ),
    'EXAM_UPLOAD' => array(
        //'PAGEWISE' => array(
            //'SA_TOP_Tiles' => array('Total Uploaded Docs','Total Users','First Time Users'),
            //'LINE_CHART' =>array(
                //'heading' => 'Total Uploaded Docs'
            //),
        //),
        'TOP_TILES' => array(
            'COMMON' => array(
            ),
            'SHIKSHA' => array(),
            'DOMESTIC' => array(),
            'STUDY ABROAD' => array(
                'totalUpload'               => array('title' => 'Total Uploaded Docs', 'id' => 'totalUpload'),
                'totalUser'       => array('title' => 'Total Users', 'id' => 'totalUser'),
                'firstTimeUser'        => array('title' => 'First Time Users', 'id' => 'firstTimeUser'),
            ),
        ),
        'LINE_CHART' => array(
            'heading'         => 'Exam Uploaded Docs',
            'field'        => 'addedOn',
            'id'		=> 'lineChartDiv'
        ),
        'PIE_CHART' => array(
            'COMMON' => array(
            ),
            'SHIKSHA' => array(),
            'DOMESTIC' => array(),
            'STUDY ABROAD' => array(
                'Source Application' => array(  //direct
                    'id' => 'device',
                    'fieldName' => 'sourceApplication',
                    'title' => 'Source Application'
                ),
                'Widget' => array(  //direct
                    'id' => 'widget',
                    'fieldName' => 'widget',
                    'title' => 'Widget'
                ),
            ),
        ),
        'BAR_GRAPH' => array(
            'COMMON' => array(
            ),
            'SHIKSHA' => array(),
            'DOMESTIC' => array(),
            'STUDY ABROAD' => array(
            ),
        ),
        'DATA_TABLE' => array(
            'COMMON' => array(),
            'SHIKSHA' => array(),
            'DOMESTIC' => array(
            ),
            'STUDY ABROAD' => array(
                'id' => 'dataTable',
                'fields' => array(
                    'widget' => array(
                        'field' => 'widget',
                        'title' => 'Page'
                    ),
                    'sourceApplication' => array(
                        'field' => 'sourceApplication',
                        'title' => 'Source Application'
                    ),
                ),
            ),
        ),
        'ajaxDestinationURL' => "/trackingMIS/Dashboard/diffChartDataForMIS/",
    ),

    /*'LEADS' => array(
        'SA_TOP_Tiles' =>array('Total Leads','MMP Leads', 'Response Leads','Signup Leads','Hamburger Leads'),
        'PIE_CHART' => array(
            'data' => array(
                'SOURCE_APPLICATION' => array(
                    'title' => 'Leads - Source Application',
                ),
                'TRAFFIC_SOURCE' => array(
                    'title' => 'Leads - Traffic Source',
                ),
                'PAGE' => array(
                    'title' => 'Leads - Page',
                ),
                'LEAD_TYPE' => array(
                    'title' => 'Leads - Paid / Free',
                ),
            ),
        ),
        'BAR_GRAPH' => array(
            "UTM_SOURCE" => array(
                "heading" => 'UTM Source',
            ),
            "UTM_CAMPAIGN" => array(
                "heading" => 'UTM Campaign',
            ),
            "UTM_MEDIUM" => array(
                "heading" => 'UTM Medium',
            ),
        ),
        'LINE_CHART' => array(
            'heading' => 'Leads'
        ),
    ),*/
    'RMC' => array(
        'OVERALL' => array(
            'TOP_TILES' => array(
                'COMMON' => array(),
                'SHIKSHA' => array(),
                'DOMESTIC' => array(),
                'STUDY ABROAD' => array(
                    'totalCount'            => array('title' => 'Total Response', 'id' => 'totalResponse'),
                    'responsePerRespondent' => array('title' => 'Responses / Respondent', 'id' => 'responsePerRespondent'),
                    'totalUniv'          => array('title' => 'Total Universities', 'id' => 'totalUniv'),
                    'totalCourse'        => array('title' => 'Total Courses', 'id' => 'totalCourse'),
                    'totalUsers'        => array('title' => 'Total Users', 'id' => 'totalUsers')
                )
            ),
            'LINE_CHART' => array(
                'heading'         => 'Responses',
                'field'        => 'response_time',
                'id'        => 'lineChartDiv'
            ),
            'PIE_CHART' => array(
                'COMMON' => array(),
                'SHIKSHA' => array(),
                'DOMESTIC' => array(),
                'STUDY ABROAD' => array(
                    'Source Application' => array(
                        'id' => 'device',
                        'fieldName' => 'device',
                        'title' => 'Source Application'
                    ),
                    'Traffic Source' => array(
                        'id' => 'trafficSource',
                        'fieldName' => 'response_source',
                        'title' => 'Traffic Source'
                    ),
                    'Widget' => array(
                        'id' => 'widget',
                        'fieldName' => 'source',
                        'title' => 'Widget'
                    ),
                    'Type' => array(
                        'id' => 'type',
                        'fieldName' => 'is_response_paid',
                        'title' => 'Paid / Free'
                    )
                ),
            ),
            'BAR_GRAPH' => array(
                'COMMON' => array(),
                'SHIKSHA' => array(),
                'DOMESTIC' => array(),
                'STUDY ABROAD' => array(
                    "UTM Source" => array(
                        "id" => 'UTM_Source',
                        'fieldName' => 'utm_source',
                        'title' => 'UTM Source'
                    ),
                    "UTM Medium" => array(
                        "id" => 'UTM_Medium',
                        'fieldName' => 'utm_medium',
                        'title' => 'UTM Medium'
                    ),
                    "UTM Campaign" => array(
                        "id" => 'UTM_Campaign',
                        'fieldName' => 'utm_campaign',
                        'title' => 'UTM Campaign'
                    )
                )
            ),
            'DATA_TABLE' => array(
                'COMMON' => array(),
                'SHIKSHA' => array(),
                'DOMESTIC' => array(),
                'STUDY ABROAD' => array(
                    'id' => 'dataTable',
                    'fields' => array(
                        'page' => array('field' => 'page','title' => 'Page Name'),
                        'sourceApplication' => array('field' => 'device','title' => 'Source Application'),
                    ),
                ),
            ),
        ),
        'PAGEWISE' => array(
            'TOP_TILES' => array(
                'COMMON' => array(),
                'SHIKSHA' => array(),
                'DOMESTIC' => array(),
                'STUDY ABROAD' => array(
                    'totalCount'            => array('title' => 'Total Response', 'id' => 'totalResponse'),
                    'responsePerRespondent' => array('title' => 'Responses / Respondent', 'id' => 'responsePerRespondent'),
                    'totalUniv'          => array('title' => 'Total Universities', 'id' => 'totalUniv'),
                    'totalCourse'        => array('title' => 'Total Courses', 'id' => 'totalCourse'),
                    'totalUsers'        => array('title' => 'Total Users', 'id' => 'totalUsers')
                ),
            ),
            'LINE_CHART' => array(
                'heading'         => 'Responses',
                'field'        => 'response_time',
                'id'        => 'lineChartDiv'
            ),
            'PIE_CHART' => array(
                'COMMON' => array(),
                'SHIKSHA' => array(),
                'DOMESTIC' => array(),
                'STUDY ABROAD' => array(
                    'Source Application' => array(
                        'id' => 'device',
                        'fieldName' => 'device',
                        'title' => 'Source Application'
                    ),
                    'Traffic Source' => array(
                        'id' => 'trafficSource',
                        'fieldName' => 'response_source',
                        'title' => 'Traffic Source'
                    ),
                    'Type' => array(
                        'id' => 'type',
                        'fieldName' => 'is_response_paid',
                        'title' => 'Paid / Free'
                    )
                )
            ),
            'BAR_GRAPH' => array(
                'COMMON' => array(),
                'SHIKSHA' => array(),
                'DOMESTIC' => array(),
                'STUDY ABROAD' => array(
                    "UTM Source" => array(
                        "id" => 'UTM_Source',
                        'fieldName' => 'utm_source',
                        'title' => 'UTM Source'
                    ),
                    "UTM Medium" => array(
                        "id" => 'UTM_Medium',
                        'fieldName' => 'utm_medium',
                        'title' => 'UTM Medium'
                    ),
                    "UTM Campaign" => array(
                        "id" => 'UTM_Campaign',
                        'fieldName' => 'utm_campaign',
                        'title' => 'UTM Campaign'
                    )
                )
            ),
            'DATA_TABLE' => array(
                'COMMON' => array(),
                'SHIKSHA' => array(),
                'DOMESTIC' => array(),
                'STUDY ABROAD' => array(
                    'id' => 'dataTable',
                    'fields' => array(
                        'keyName' => array('field' => 'source','title' => 'Key Name'),
                        'widget' => array('field' => 'widget','title' => 'Widget'),
                        'sourceApplication' => array('field' => 'device','title' => 'Source Application')
                    )
                )
            ),
        ),
        'ajaxDestinationURL' => "/trackingMIS/Dashboard/diffChartDataForMIS/",
    ),
    'RESPONSE' => array(
        'OVERALL' => array(
            'TOP_TILES' => array(
                'COMMON' => array(
                    'totalCount'            => array('title' => 'Total Response', 'id' => 'totalResponse'),
                    'responsePerRespondent' => array('title' => 'Responses / Respondent', 'id' => 'responsePerRespondent'),
                    'paidResponse'          => array('title' => 'Paid Response', 'id' => 'paidResponse')
                ),
                'SHIKSHA' => array(
                    'rmcResponse'        => array('title' => 'RMC Responses', 'id' => 'rmcResponse')
                    ),
                'DOMESTIC' => array(
                    'responsePerSessions' => array('title' => 'Responses / Sessions', 'id' => 'responsePerSessions')
                    ),
                'STUDY ABROAD' => array(
                    'rmcResponse'        => array('title' => 'RMC Responses', 'id' => 'rmcResponse'),
                    'paidCourse'        => array('title' => 'Paid Courses', 'id' => 'paidCourse')
                ),
            ),
            'LINE_CHART' => array(
                'heading'         => 'Responses',
                'field'        => 'response_time',
                'id'        => 'lineChartDiv'
            ),
            'PIE_CHART' => array(
                'COMMON' => array(
                    'Source Application' => array(
                        'id' => 'device',
                        'fieldName' => 'device',
                        'title' => 'Source Application'
                    ),
                    'Traffic Source' => array(
                        'id' => 'trafficSource',
                        'fieldName' => 'response_source',
                        'title' => 'Traffic Source'
                    ),
                    'Widget' => array(
                        'id' => 'widget',
                        'fieldName' => 'source',
                        'title' => 'Widget'
                    ),
                    'Type' => array(
                        'id' => 'type',
                        'fieldName' => 'is_response_paid',
                        'title' => 'Paid / Free'
                    )
                ),
                'SHIKSHA' => array(
                    'Page Wise' => array(
                        'id' => 'page',
                        'fieldName' => 'page',
                        'title' => 'Page'
                    )
                ),
                'DOMESTIC' => array(),
                'STUDY ABROAD' => array(),
            ),
            'BAR_GRAPH' => array(
                'COMMON' => array(
                    "UTM Source" => array(
                        "id" => 'UTM_Source',
                        'fieldName' => 'utm_source',
                        'title' => 'UTM Source'
                    ),
                    "UTM Medium" => array(
                        "id" => 'UTM_Medium',
                        'fieldName' => 'utm_medium',
                        'title' => 'UTM Medium'
                    ),
                    "UTM Campaign" => array(
                        "id" => 'UTM_Campaign',
                        'fieldName' => 'utm_campaign',
                        'title' => 'UTM Campaign'
                    ),

                ),
                'SHIKSHA' => array(),
                'DOMESTIC' => array(),
                'STUDY ABROAD' => array(
                ),
            ),
            'DATA_TABLE' => array(
                'COMMON' => array(),
                'SHIKSHA' => array(),
                'DOMESTIC' => array(
                    'id' => 'dataTable',
                    'fields' => array(
                        'page' => array('field' => 'page',
                            'title' => 'Page'),
                        'sourceApplication' => array('field' => 'device',
                            'title' => 'Source Application'),
                    )
                ),
                'STUDY ABROAD' => array(
                    'id' => 'dataTable',
                    'fields' => array(
                        'page' => array('field' => 'page',
                            'title' => 'Page'),
                        'sourceApplication' => array('field' => 'device',
                            'title' => 'Traffic Source'),

                    ),
                ),
            ),
        ),
        'PAGEWISE' => array(
            'TOP_TILES' => array(
                'COMMON' => array(
                    'totalCount'            => array('title' => 'Total Response', 'id' => 'totalResponse'),
                    'responsePerRespondent' => array('title' => 'Responses / Respondent', 'id' => 'responsePerRespondent')                    
                ),
                'SHIKSHA' => array(
                    'paidResponse'          => array('title' => 'Paid Response', 'id' => 'paidResponse'),
                    'rmcResponse'        => array('title' => 'RMC Responses', 'id' => 'rmcResponse'),
                    'paidCourse'        => array('title' => 'Paid Courses', 'id' => 'paidCourse')
                    ),
                'DOMESTIC' => array(
                    'paidResponse'          => array('title' => 'Paid Response', 'id' => 'paidResponse'),
                    ),
                'STUDY ABROAD' => array(
                    'totalUniv'        => array('title' => 'Total Universities', 'id' => 'totalUniv'),
                    'totalCourse'        => array('title' => 'Total Courses', 'id' => 'totalCourse')
                ),
            ),
            'LINE_CHART' => array(
                'heading'         => 'Responses',
                'field'        => 'response_time',
                'id'        => 'lineChartDiv'
            ),
            'PIE_CHART' => array(
                'COMMON' => array(
                    'Source Application' => array(
                        'id' => 'device',
                        'fieldName' => 'device',
                        'title' => 'Source Application'
                    ),
                    'Traffic Source' => array(
                        'id' => 'trafficSource',
                        'fieldName' => 'response_source',
                        'title' => 'Traffic Source'
                    ),
                    'Widget' => array(
                        'id' => 'widget',
                        'fieldName' => 'widget',
                        'title' => 'Widget'
                    ),
                    'Type' => array(
                        'id' => 'type',
                        'fieldName' => 'is_response_paid',
                        'title' => 'Paid / Free'
                    )
                ),
                'SHIKSHA' => array(
                    'Page Wise' => array(
                        'id' => 'page',
                        'fieldName' => 'page',
                        'title' => 'Page'
                    )
                ),
                'DOMESTIC' => array(),
                'STUDY ABROAD' => array(),
            ),
            'BAR_GRAPH' => array(
                'COMMON' => array(
                    "UTM Source" => array(
                        "id" => 'UTM_Source',
                        'fieldName' => 'utm_source',
                        'title' => 'UTM Source'
                    ),
                    "UTM Medium" => array(
                        "id" => 'UTM_Medium',
                        'fieldName' => 'utm_medium',
                        'title' => 'UTM Medium'
                    ),
                    "UTM Campaign" => array(
                        "id" => 'UTM_Campaign',
                        'fieldName' => 'utm_campaign',
                        'title' => 'UTM Campaign'
                    ),

                ),
                'SHIKSHA' => array(),
                'DOMESTIC' => array(),
                'STUDY ABROAD' => array(
                ),
            ),
            'DATA_TABLE' => array(
                'COMMON' => array(),
                'SHIKSHA' => array(),
                'DOMESTIC' => array(
                    'id' => 'dataTable',
                    'fields' => array(
                        'keyName' => array('field' => 'source','title' => 'Key Name'),
                        'widget' => array('field' => 'widget','title' => 'Widget'),
                        'sourceApplication' => array('field' => 'device','title' => 'Source Application')
                    )
                ),
                'STUDY ABROAD' => array(
                    'id' => 'dataTable',
                    'fields' => array(
                        'keyName' => array('field' => 'source','title' => 'Key Name'),
                        'widget' => array('field' => 'widget','title' => 'Widget'),
                        'sourceApplication' => array('field' => 'device','title' => 'Source Application')
                    ),
                ),
            ),
        ),
        'ajaxDestinationURL' => "/trackingMIS/Dashboard/diffChartDataForMIS/",
    ), 

    'ENGAGEMENT' => array(
        'OVERALL' => array(
            'TOP_TILES' => array(
                'COMMON' => array(
                    'Page Views'                 => array('title' => 'Page Views', 'id' => 'pageViews'),
                    'Pages/Session'            => array('title' => 'Pages/Session', 'id' => 'pgPerSess'),
                    'Avg Session (mm:ss)'          => array('title' => 'Avg Session (mm:ss)', 'id' => 'avgSessDuration'),
                    'Bounce Rate (%)'         => array('title' => 'Bounce Rate (%)', 'id' => 'bounceRate'),
                ),
                'SHIKSHA' => array(),
                'DOMESTIC' => array('totalSessions'         => array('title' => 'Total Sessions', 'id' => 'totalSessions')),
                'STUDY ABROAD' => array(),
            ),
            'LINE_CHART' => array(
                'heading'         => 'Page Views',
                'field'        => 'visitTime',
                'id'		=> 'lineChartDiv'
            ),
            'PIE_CHART' => array(
                'COMMON' => array(
                    'Source Application' => array(
                        'id' => 'device',
                        'fieldName' => 'isMobile',
                        'title' => 'Source Application'
                    ),
                    'Traffic Source' => array(
                        'id' => 'trafficSource',
                        'fieldName' => 'source',
                        'title' => 'Traffic Source'
                    ),
                    'USER' => array(
                        'id' => 'userId',
                        'fieldName' => 'userId',
                        'title' => 'User',
                    ),
                    'Page Wise' => array(
                        'id' => 'pageIdentifier',
                        'fieldName' => 'landingPageDoc.pageIdentifier',
                        'title' => 'Page'
                    )
                ),
                'SHIKSHA' => array(),
                'DOMESTIC' => array(),
                'STUDY ABROAD' => array(),
            ),
            'BAR_GRAPH' => array(
                'COMMON' => array(
                    "UTM Source" => array(
                        "id" => 'UTM_Source',
                        'fieldName' => 'utm_source',
                        'title' => 'UTM Source'
                    ),
                    "UTM Medium" => array(
                        "id" => 'UTM_Medium',
                        'fieldName' => 'utm_medium',
                        'title' => 'UTM Medium'
                    ),
                    "UTM Campaign" => array(
                        "id" => 'UTM_Campaign',
                        'fieldName' => 'utm_campaign',
                        'title' => 'UTM Campaign'
                    ),
                ),
                'SHIKSHA' => array(),
                'DOMESTIC' => array(),
                'STUDY ABROAD' => array(),
            ),
            'DATA_TABLE' => array(
                'COMMON' => array(),
                'SHIKSHA' => array(),
                'DOMESTIC' => array(
                    'id' => 'dataTable',
                    'fields' => array(
                        'page' => array(
                            'field' => 'pageIdentifier',
                            'title' => 'Page'
                        ),
                        'trafficSource' => array(
                            'field' => 'source',
                            'title' => 'Traffic Source'
                        ),
                        'sourceApplication' => array(
                            'field' => 'isMobile',
                            'title' => 'Source Application'
                        ),
                    )
                ),
                'STUDY ABROAD' => array(
                    'id' => 'dataTable',
                    'fields' => array(
                        'page' => array(
                            'field' => 'pageIdentifier',
                            'title' => 'Page'
                        ),
                        'trafficSource' => array(
                            'field' => 'source',
                            'title' => 'Traffic Source'
                        ),
                        'sourceApplication' => array(
                            'field' => 'isMobile',
                            'title' => 'Source Application'
                        ),
                    ),
                ),
            ),
        ),
        'PAGEWISE' => array(
            'TOP_TILES' => array(
                'COMMON' => array(
                    'Page Views'                 => array('title' => 'Page Views', 'id' => 'pageViews'),
                    'Pages/Session'            => array('title' => 'Pages/Session', 'id' => 'pgPerSess'),
                    'Avg Session (mm:ss)'          => array('title' => 'Avg Session (mm:ss)', 'id' => 'avgSessDuration'),
                    'Bounce Rate (%)'         => array('title' => 'Bounce Rate (%)', 'id' => 'bounceRate'),
                    'Exit Rate (%)'	=> array('title' => 'Exit Rate (%)', 'id' => 'exitRate'),
                ),
                'SHIKSHA' => array(),
                'DOMESTIC' => array('Sessions'         => array('title' => 'Total Sessions', 'id' => 'totalSessions')),
                'STUDY ABROAD' => array(),
            ),
            'LINE_CHART' => array(
                'heading'         => 'Page Views',
                'field'        => 'visitTime',
                'id'		=> 'lineChartDiv'
            ),
            'PIE_CHART' => array(
                'COMMON' => array(
                    'Source Application' => array(
                        'id' => 'device',
                        'fieldName' => 'isMobile',
                        'title' => 'Source Application'
                    ),
                    'Traffic Source' => array(
                        'id' => 'trafficSource',
                        'fieldName' => 'source',
                        'title' => 'Traffic Source'
                    ),
                    'USER' => array(
                        'id' => 'userId',
                        'fieldName' => 'userId',
                        'title' => 'User',
                    ),
                ),
                'SHIKSHA' => array(),
                'DOMESTIC' => array(),
                'STUDY ABROAD' => array(),
            ),
            'BAR_GRAPH' => array(
                'COMMON' => array(
                    "UTM Source" => array(
                        "id" => 'UTM_Source',
                        'fieldName' => 'utm_source',
                        'title' => 'UTM Source'
                    ),
                    "UTM Medium" => array(
                        "id" => 'UTM_Medium',
                        'fieldName' => 'utm_medium',
                        'title' => 'UTM Medium'
                    ),
                    "UTM Campaign" => array(
                        "id" => 'UTM_Campaign',
                        'fieldName' => 'utm_campaign',
                        'title' => 'UTM Campaign'
                    ),
                ),
                'SHIKSHA' => array(),
                'DOMESTIC' => array(),
                'STUDY ABROAD' => array(),
            ),
            'DATA_TABLE' => array(
                'COMMON' => array(),
                'SHIKSHA' => array(),
                'DOMESTIC' => array(
                    'id' => 'dataTable',
                    'fields' => array(
                        'page' => array(
                            'field' => 'pageIdentifier',
                            'title' => 'Page'
                        ),
                        'trafficSource' => array(
                            'field' => 'source',
                            'title' => 'Traffic Source'
                        ),
                        'sourceApplication' => array(
                            'field' => 'isMobile',
                            'title' => 'Source Application'
                        ),
                    )
                ),
                'STUDY ABROAD' => array(),
            ),
        ),
        'ajaxDestinationURL' => "/trackingMIS/Dashboard/diffChartDataForMIS/",
    ),
);

$config['VIEWED_ACTION_LIST'] = array(
    'DOMESTIC' => array("COMPARE_VIEWED","MOB_COMPARE_VIEWED","MOB_Viewed","Mob_Viewed_Listing_Pre_Reg","mobile_viewedListing","Viewed_Listing","Viewed_Listing_Pre_Reg","Viewed_Listing_Pre_Reg_sa_mobile","Viewed_Listing_sa_mobile","Institute_Viewed","MOB_Institute_Viewed","exam_viewed_response"),
    'STUDY ABROAD' => array()
    ); // REPLACE THIS CONFIG THIS /var/www/html/shiksha/application/modules/User/response/config/responseConfig.php

$config['TOP_CLIENT'] = array(
    7840431663241,271920,1782807,4908178,1056732,5517064,11275449,4131293,5244618,6303835,6638118,5196998,4197277,8007177,1639624,5242140,326097,6523499,2782594,231574,895892,10935995,5230840,5672881,421502,3282442,5045895,1721261,7439481,2022040,5195321,2322698,7740833,4125690,339953,7723318,11570061,3432402,937303,5287624,7855299,4721506,4495011,639041,3674726,369652,4733769,7510486,6152108,7560913,4342422,1978300,7701774,5558169,3127837,7530656,10836137,1463240,6173495,59000,6458454,6846206,67889,7560982,5278180,1085066,7518262,2672164,1837903,2122730,2578660,4495009,8346089,8394805,10993045,10598127,8008169,4999195,5204834,5277669,8319049,5887374,1773605,6643427,6592830,2258227,2260024,6437494,5309738,7755983,7098974,1334606,1771292,3127846,1034302,2757033,5629680,2424079,3667618

    /*231574   => array( "email" => "manish@isbr.in", "displayName" => "ISBR" ),
    271920   => array( "email" => "info@lpu.in", "displayName" => "LPUJALANDHAR" ),
    326097   => array( "email" => "data@jaro.in", "displayName" => "JAROINSTITUTE" ),
    339953   => array( "email" => "gangagroupdelhi@gmail.com", "displayName" => "GANGAINSTITUTE" ),
    369652   => array( "email" => "ibmrc@vsnl.in", "displayName" => "ASM" ),
    421502   => array( "email" => "Admissions@niituniversity.in", "displayName" => "NIITUNIVERSITY50022" ),
    639041   => array( "email" => "info@hkbkeducation.org", "displayName" => "HKBK" ),
    663241   => array( "email" => "enquiry@alliance.edu.in", "displayName" => "ALLIANCEUNIVERSITY" ),
    895892   => array( "email" => "m.vadavat@latrobe.edu.au", "displayName" => "LaTrobeUniversity" ),
    937303   => array( "email" => "avinashdash@jaipuria.ac.in", "displayName" => "JAIPURIAINSTITUTEOFMAN" ),
    1056732  => array( "email" => "admissions.india@srmuniv.ac.in", "displayName" => "SRMUNIVERSITY" ),
    1639624  => array( "email" => "admissions@cumail.in", "displayName" => "ChdUniv" ),
    1721261  => array( "email" => "info@taxila.in", "displayName" => "TAXILACOLLEGE" ),
    1782807  => array( "email" => "Anuj.sharma@pearlacademy.com", "displayName" => "PEARLACADEMYOFFASHION" ),
    2022040  => array( "email" => "nimish.saxena@srmu.ac.in", "displayName" => "SRMULUCKNOW" ),
    2322698  => array( "email" => "bangalore@fateheducation.com", "displayName" => "FED" ),
    2782594  => array( "email" => "amiya.kumar@timesgroup.com", "displayName" => "TIMESPRO79356" ),
    3282442  => array( "email" => "admission@ismrpune.edu.in", "displayName" => "ISMRPUNE" ),
    3432402  => array( "email" => "dean@revainstitution.org", "displayName" => "REVA63327" ),
    3674726  => array( "email" => "trityaeducation@gmail.com", "displayName" => "INFOTRITYACOM" ),
    4125690  => array( "email" => "vikram@srvmedia.com", "displayName" => "SRVMEDIA" ),
    4131293  => array( "email" => "Itm.211@itm.edu", "displayName" => "ITMPGDM" ),
    4197277  => array( "email" => "admission.cgc@gmail.com", "displayName" => "CGCLANDRAN" ),
    4342422  => array( "email" => "directorbfit@gmail.com", "displayName" => "BFITBHUPINDER" ),
    4495011  => array( "email" => "rawasthi@amity.edu", "displayName" => "AMITYMUMBAI" ),
    4721506  => array( "email" => "yp@educatusexpo.com", "displayName" => "EDUCATUSEXPO" ),
    4733769  => array( "email" => "sbarsaiyan@amity.edu", "displayName" => "AMITYGURGAON" ),
    4908178  => array( "email" => "obaid.khan@talentedge.in", "displayName" => "VENKATESHWARA45927" ),
    5045895  => array( "email" => "osd@mmumullana.org", "displayName" => "MMUAMBALA" ),
    5195321  => array( "email" => "Infoctr@pace.edu", "displayName" => "ACORDONPACEEDU" ),
    5196998  => array( "email" => "kanishkdugal@icriindia.com", "displayName" => "ICRIINDIA" ),
    5230840  => array( "email" => "enquiry@dypdc.com", "displayName" => "ADYPU" ),
    5242140  => array( "email" => "saisailaja@ipeindia.org", "displayName" => "ARAKESH33171" ),
    5244618  => array( "email" => "admissions@manipal.edu", "displayName" => "MANIPALUNI" ),
    5287624  => array( "email" => "osd@cgc.ac.in", "displayName" => "CGCJHANJERI" ),
    5517064  => array( "email" => "alkendra.singh@bennett.edu.in", "displayName" => "BENNETT76447" ),
    5672881  => array( "email" => "manisha@dviodigital.com", "displayName" => "MANISHAXEBECEMEDIACOM" ),
    6152108  => array( "email" => "subhalaxmik@itm.edu", "displayName" => "ITMEXECUTIVE" ),
    6303835  => array( "email" => "chinkymttl@gmail.com", "displayName" => "VSTOMAR" ),
    6523499  => array( "email" => "khushbu.cc@mriu.edu.in", "displayName" => "MRVPL" ),
    6638118  => array( "email" => "website@mituniversity.edu.in", "displayName" => "MITADT" ),
    7439481  => array( "email" => "ibsat@ibsindia.org", "displayName" => "IBSMBAPGPM" ),
    7510486  => array( "email" => "support@iimtgroup.edu.in", "displayName" => "BURLYINSTITUTE" ),
    7560913  => array( "email" => "kanishkdugalmumbai@gmail.com", "displayName" => "ICRIMUMBAI" ),
    7840431  => array( "email" => "nandkumar.dhake@mitpune.edu.in", "displayName" => "MITWPU" ),
    7855299  => array( "email" => "newcastle.univ1@gmail.com", "displayName" => "UNIVOFNEWCASTLE" ),
    8007177  => array( "email" => "info@cmr.edu.in", "displayName" => "CMRUNI" ),
    10935995 => array( "email" => "admission@theaims.ac.in", "displayName" => "AIMSBANGALORE18601" ),
    11275449 => array( "email" => "achatterjee@upes.ac.in", "displayName" => "ADITYACHATERJEE31396" ),
    11570061 => array( "email" => "registrar@reva.edu.in", "displayName" => "REVAUNIVERSITY95531" )*/
);

$config['FILTER'] = array(
    'SHIKSHA' => array(
        'REGISTRATION'=>array(
            'Overall' 					=> array('sourceApplication')
        ),
        'RESPONSE'=>array(
            'Overall'                   => array('sourceApplication')
        ),
        'TRAFFIC'=>array(
            'Overall' 					=> array('sourceApplication')
        ),
        'ENGAGEMENT'=>array(
            'Overall' 					=> array('sourceApplication')
        ),
    ),
    'DOMESTIC' => array(
        'REGISTRATION'=>array(
            "articleDetailPage"     => array('stream','substream','specialization','baseCourse','mode','sourceApplication'),
            "allArticlePage"        => array('stream','substream','specialization','baseCourse','mode','sourceApplication'),
            "questionDetailPage"    => array('stream','substream','specialization','baseCourse','mode','sourceApplication'),
            "categoryPage"          => array('stream','substream','specialization','baseCourse','mode','sourceApplication'),
            "campusRepresentative"  => array('stream','substream','specialization','baseCourse','mode','sourceApplication'),
            "careerCompasPage"      => array('stream','substream','specialization','baseCourse','mode','sourceApplication'),
            "collegePredictor"      => array('stream','substream','specialization','baseCourse','mode','sourceApplication'),
            "collegeReviewPage"     => array('stream','substream','specialization','baseCourse','mode','sourceApplication'),
            "courseHomePage"        => array('stream','substream','specialization','baseCourse','mode','sourceApplication'),
            "courseDetailsPage"     => array('stream','substream','specialization','baseCourse','mode','sourceApplication'),
            "universityListingPage" => array('stream','substream','specialization','baseCourse','mode','sourceApplication'),
            "examPageMain"              => array('stream','substream','specialization','baseCourse','mode','sourceApplicationType','groupPageList','trafficSourceType'),
            "eventCalendar"         => array('stream','substream','specialization','baseCourse','mode','sourceApplication'),
            "iimPredictorInput"     => array('stream','substream','specialization','baseCourse','mode','sourceApplication'),
            "instituteListingPage"  => array('stream','substream','specialization','baseCourse','mode','sourceApplication'),
            "homePage"              => array('stream','substream','specialization','baseCourse','mode','sourceApplication'),
            "rankingPage"           => array('stream','substream','specialization','baseCourse','mode','sourceApplication'),
            "rankPredictor"         => array('stream','substream','specialization','baseCourse','mode','sourceApplication'),
            "shortlistPage"         => array('stream','substream','specialization','baseCourse','mode','sourceApplication'),
            "Overall"               => array('stream','substream','specialization','baseCourse','mode','shikshaPageGroups','shikshaPages','sourceApplicationType','trafficSourceType')
        ),
        'TRAFFIC'=>array(
            "articleDetailPage"     => array('stream','substream','specialization','baseCourse','credential','baseCourseLevel','educationType','deliveryMethod','sourceApplication'),
            "allArticlePage"        => array('stream','substream','specialization','baseCourse','credential','baseCourseLevel','educationType','deliveryMethod','sourceApplication'),
            "questionDetailPage"    => array('stream','substream','specialization','baseCourse','credential','baseCourseLevel','educationType','deliveryMethod','sourceApplication'),
            "categoryPage"          => array('stream','substream','specialization','baseCourse','credential','baseCourseLevel','educationType','deliveryMethod','sourceApplication'),
            "campusRepresentative"  => array('stream','substream','specialization','baseCourse','credential','baseCourseLevel','educationType','deliveryMethod','sourceApplication'),
            "careerCompasPage"      => array('stream','substream','specialization','baseCourse','credential','baseCourseLevel','educationType','deliveryMethod','sourceApplication'),
            "collegePredictor"      => array('stream','substream','specialization','baseCourse','credential','baseCourseLevel','educationType','deliveryMethod','sourceApplication'),
            "collegeReviewPage"     => array('stream','substream','specialization','baseCourse','credential','baseCourseLevel','educationType','deliveryMethod','sourceApplication'),
            "courseHomePage"        => array('stream','substream','specialization','baseCourse','credential','baseCourseLevel','educationType','deliveryMethod','sourceApplication'),
            "courseDetailsPage"     => array('stream','substream','specialization','baseCourse','credential','baseCourseLevel','educationType','deliveryMethod','sourceApplication'),
            "universityListingPage" => array('stream','substream','specialization','baseCourse','credential','baseCourseLevel','educationType','deliveryMethod','sourceApplication'),
            "examPageMain"              => array('stream','substream','specialization','baseCourse','credential','baseCourseLevel','educationType','deliveryMethod','groupPageList','sourceApplication'),
            "eventCalendar"         => array('stream','substream','specialization','baseCourse','credential','baseCourseLevel','educationType','deliveryMethod','sourceApplication'),
            "iimPredictorInput"     => array('stream','substream','specialization','baseCourse','credential','baseCourseLevel','educationType','deliveryMethod','sourceApplication'),
            "instituteListingPage"  => array('stream','substream','specialization','baseCourse','credential','baseCourseLevel','educationType','deliveryMethod','sourceApplication'),
            "homePage"              => array('stream','substream','specialization','baseCourse','credential','baseCourseLevel','educationType','deliveryMethod','sourceApplication'),
            "rankingPage"           => array('stream','substream','specialization','baseCourse','credential','baseCourseLevel','educationType','deliveryMethod','sourceApplication'),
            "rankPredictor"         => array('stream','substream','specialization','baseCourse','credential','baseCourseLevel','educationType','deliveryMethod','sourceApplication'),
            "shortlistPage"         => array('stream','substream','specialization','baseCourse','credential','baseCourseLevel','educationType','deliveryMethod','sourceApplication'),
            "Overall"               => array('stream','substream','specialization','baseCourse','credential','baseCourseLevel','educationType','deliveryMethod','sourceApplication','trafficSourceType','userUsedSassistant','shikshaPageGroups','shikshaPages')
        ),
        'ENGAGEMENT'=>array(
            "articleDetailPage"     => array('stream','substream','specialization','baseCourse','credential','baseCourseLevel','educationType','deliveryMethod','sourceApplication'),
            "allArticlePage"        => array('stream','substream','specialization','baseCourse','credential','baseCourseLevel','educationType','deliveryMethod','sourceApplication'),
            "questionDetailPage"    => array('stream','substream','specialization','baseCourse','credential','baseCourseLevel','educationType','deliveryMethod','sourceApplication'),
            "categoryPage"          => array('stream','substream','specialization','baseCourse','credential','baseCourseLevel','educationType','deliveryMethod','sourceApplication'),
            "campusRepresentative"  => array('stream','substream','specialization','baseCourse','credential','baseCourseLevel','educationType','deliveryMethod','sourceApplication'),
            "careerCompasPage"      => array('stream','substream','specialization','baseCourse','credential','baseCourseLevel','educationType','deliveryMethod','sourceApplication'),
            "collegePredictor"      => array('stream','substream','specialization','baseCourse','credential','baseCourseLevel','educationType','deliveryMethod','sourceApplication'),
            "collegeReviewPage"     => array('stream','substream','specialization','baseCourse','credential','baseCourseLevel','educationType','deliveryMethod','sourceApplication'),
            "courseHomePage"        => array('stream','substream','specialization','baseCourse','credential','baseCourseLevel','educationType','deliveryMethod','sourceApplication'),
            "courseDetailsPage"     => array('stream','substream','specialization','baseCourse','credential','baseCourseLevel','educationType','deliveryMethod','sourceApplication'),
            "universityListingPage" => array('stream','substream','specialization','baseCourse','credential','baseCourseLevel','educationType','deliveryMethod','sourceApplication'),
            "examPageMain"              => array('stream','substream','specialization','baseCourse','credential','baseCourseLevel','educationType','deliveryMethod','groupPageList','sourceApplication'),
            "eventCalendar"         => array('stream','substream','specialization','baseCourse','credential','baseCourseLevel','educationType','deliveryMethod','sourceApplication'),
            "iimPredictorInput"     => array('stream','substream','specialization','baseCourse','credential','baseCourseLevel','educationType','deliveryMethod','sourceApplication'),
            "instituteListingPage"  => array('stream','substream','specialization','baseCourse','credential','baseCourseLevel','educationType','deliveryMethod','sourceApplication'),
            "homePage"              => array('stream','substream','specialization','baseCourse','credential','baseCourseLevel','educationType','deliveryMethod','sourceApplication'),
            "rankingPage"           => array('stream','substream','specialization','baseCourse','credential','baseCourseLevel','educationType','deliveryMethod','sourceApplication'),
            "rankPredictor"         => array('stream','substream','specialization','baseCourse','credential','baseCourseLevel','educationType','deliveryMethod','sourceApplication'),
            "shortlistPage"         => array('stream','substream','specialization','baseCourse','credential','baseCourseLevel','educationType','deliveryMethod','sourceApplication'),
            "Overall"               => array('stream','substream','specialization','baseCourse','credential','baseCourseLevel','educationType','deliveryMethod', 'sourceApplication', 'trafficSourceType','userUsedSassistant','shikshaPageGroups','shikshaPages')
        ),
        'RESPONSE' => array(
            "articleDetailPage"     => array('stream','substream','specialization','baseCourse','mode','sourceApplication','responseType'),
            "allArticlePage"        => array('stream','substream','specialization','baseCourse','mode','sourceApplication','responseType'),
            "questionDetailPage"    => array('stream','substream','specialization','baseCourse','mode','sourceApplication','responseType'),
            "categoryPage"          => array('stream','substream','specialization','baseCourse','mode','sourceApplication','responseType'),
            "campusRepresentative"  => array('stream','substream','specialization','baseCourse','mode','sourceApplication','responseType'),
            "careerCompasPage"      => array('stream','substream','specialization','baseCourse','mode','sourceApplication','responseType'),
            "collegePredictor"      => array('stream','substream','specialization','baseCourse','mode','sourceApplication','responseType'),
            "collegeReviewPage"     => array('stream','substream','specialization','baseCourse','mode','sourceApplication','responseType'),
            "courseHomePage"        => array('stream','substream','specialization','baseCourse','mode','sourceApplication','responseType'),
            "courseDetailsPage"     => array('stream','substream','specialization','baseCourse','mode','sourceApplication','responseType'),
            "universityListingPage" => array('stream','substream','specialization','baseCourse','mode','sourceApplication','responseType'),
            "examPageMain"              => array('stream','substream','specialization','baseCourse','mode','sourceApplicationType','groupPageList','trafficSourceType','responseType'),
            "eventCalendar"         => array('stream','substream','specialization','baseCourse','mode','sourceApplication','responseType'),
            "iimPredictorInput"     => array('stream','substream','specialization','baseCourse','mode','sourceApplication','responseType'),
            "instituteListingPage"  => array('stream','substream','specialization','baseCourse','mode','sourceApplication','responseType'),
            "homePage"              => array('stream','substream','specialization','baseCourse','mode','sourceApplication','responseType'),
            "rankingPage"           => array('stream','substream','specialization','baseCourse','mode','sourceApplication','responseType'),
            "rankPredictor"         => array('stream','substream','specialization','baseCourse','mode','sourceApplication','responseType'),
            "shortlistPage"         => array('stream','substream','specialization','baseCourse','mode','sourceApplication','responseType'),
            "Overall"               => array('stream','substream','specialization','baseCourse','mode','shikshaPageGroups','shikshaPages','sourceApplicationType','responseListingType','responseType','trafficSourceType',"clientList", "courseListings","responseWarmth","isourceFilter")
        ),
        'Shiksha Assistant' => array()
    ),
    'STUDY ABROAD' => array(
        'REGISTRATION'=>array(
            'categoryPage'         			=> array('category','country','courseLevel','abroadExam','sourceApplication'),
            'coursePage'                	=> array('category','country','courseLevel','abroadExam','sourceApplication'),
            'articlePage'                	=> array('category','country','courseLevel','abroadExam','sourceApplication'),
            'guidePage'                 	=> array('category','country','courseLevel','abroadExam','sourceApplication'),
            'applyContentPage'        		=> array('category','country','courseLevel','abroadExam','sourceApplication'),
            'examContentPage'               => array('category','country','courseLevel','abroadExam','sourceApplication'),
            'countryHomePage'         		=> array('category','country','courseLevel','abroadExam','sourceApplication'),
            'countryPage'         			=> array('category','country','courseLevel','abroadExam','sourceApplication'),
            'universityPage'         		=> array('category','country','courseLevel','abroadExam','sourceApplication'),
            'departmentPage'         		=> array('category','country','courseLevel','abroadExam','sourceApplication'),
            'homePage'                 		=> array('category','country','courseLevel','abroadExam','sourceApplication'),
            'applyHomePage'                 => array('category','country','courseLevel','abroadExam','sourceApplication'),
            'savedCoursesPage'         		=> array('category','country','courseLevel','abroadExam','sourceApplication'),
            'searchPage'                 	=> array('category','country','courseLevel','abroadExam','sourceApplication'),
            'stagePage'                 	=> array('category','country','courseLevel','abroadExam','sourceApplication'),
            'compareCoursesPage' 			=> array('category','country','courseLevel','abroadExam','sourceApplication'),
            'Overall'                 		=> array('category','country','courseLevel','abroadExam','sourceApplication'),
            'rmcSuccessPage'         		=> array(),
            'recommendationPage' 			=> array(),
            'rankingPage'                	=> array('category','country','courseLevel','rankingPageType','abroadExam','sourceApplication'),
            'allCoursePage'                 => array('category','country','courseLevel','abroadExam','sourceApplication'),
            'userDashboard'                 => array('category','country','courseLevel','abroadExam','sourceApplication'),
        ),
        'TRAFFIC'=>array(
            'categoryPage'         			=> array('category','country','courseLevel','user','sourceApplication'),
            'coursePage'                	=> array('category','country','courseLevel','user','sourceApplication'),
            'articlePage'                	=> array('category','country','courseLevel','user','sourceApplication'),
            'guidePage'                 	=> array('category','country','courseLevel','user','sourceApplication'),
            'applyContentPage'        		=> array('category','country','courseLevel','user','sourceApplication'),
            'examContentPage'               => array('category','country','courseLevel','user','sourceApplication'),
            'countryHomePage'         		=> array('category','country','courseLevel','user','sourceApplication'),
            'countryPage'         			=> array('category','country','courseLevel','user','sourceApplication'),
            'universityPage'         		=> array('category','country','courseLevel','user','sourceApplication'),
            'departmentPage'         		=> array('category','country','courseLevel','user','sourceApplication'),
            'homePage'                 		=> array('category','country','courseLevel','user','sourceApplication'),
            'applyHomePage'                 => array('user','sourceApplication'),
            'savedCoursesPage'         		=> array('category','country','courseLevel','user','sourceApplication'),
            'searchPage'                 	=> array('category','country','courseLevel','user','sourceApplication'),
            'stagePage'                 	=> array('category','country','courseLevel','user','sourceApplication'),
            'compareCoursesPage' 			=> array('category','country','courseLevel','user','sourceApplication'),
            'Overall'                 		=> array('category','country','courseLevel','user','sourceApplication'),
            'rmcSuccessPage'         		=> array(),
            'recommendationPage' 			=> array(),
            'rankingPage'                	=> array('category','country','courseLevel','rankingPageType','user','sourceApplication'),
            'allCoursePage'                 => array('category','country','courseLevel','user','sourceApplication'),
            'userDashboard'                 => array('category','country','courseLevel','user','sourceApplication'),
        ),
        'ENGAGEMENT'=>array(
            'categoryPage'         			=> array('category','country','courseLevel','user','sourceApplication'),
            'coursePage'                	=> array('category','country','courseLevel','user','sourceApplication'),
            'articlePage'                	=> array('category','country','courseLevel','user','sourceApplication'),
            'guidePage'                 	=> array('category','country','courseLevel','user','sourceApplication'),
            'applyContentPage'        		=> array('category','country','courseLevel','user','sourceApplication'),
            'examContentPage'               => array('category','country','courseLevel','user','sourceApplication'),
            'countryHomePage'         		=> array('category','country','courseLevel','user','sourceApplication'),
            'countryPage'         			=> array('category','country','courseLevel','user','sourceApplication'),
            'universityPage'         		=> array('category','country','courseLevel','user','sourceApplication'),
            'departmentPage'         		=> array('category','country','courseLevel','user','sourceApplication'),
            'homePage'                 		=> array('category','country','courseLevel','user'),
            'applyHomePage'                 => array('user','sourceApplication'),
            'savedCoursesPage'         		=> array('category','country','courseLevel','user','sourceApplication'),
            'searchPage'                 	=> array('category','country','courseLevel','user','sourceApplication'),
            'stagePage'                 	=> array('category','country','courseLevel','user','sourceApplication'),
            'compareCoursesPage' 			=> array('category','country','courseLevel'),
            'Overall'                 		=> array('category','country','courseLevel','user','sourceApplication'),
            'rmcSuccessPage'         		=> array(),
            'recommendationPage' 			=> array(),
            'rankingPage'           		=> array('category','country','courseLevel','rankingPageType','user','sourceApplication'),
            'allCoursePage'                 => array('category','country','courseLevel','user','sourceApplication'),
            'userDashboard'                 => array('category','country','courseLevel','user','sourceApplication'),
        ),
        'HOME'=>array(
            'searchPage' 					=> array('sourceApplication'),
        ),
        'EXAM_UPLOAD' => array(
            'applyHomePage' => array('abroadExamList','sourceApplication'),
        ),
        'RESPONSE' => array(
            'rankingPage'        => array('category','country','courseLevel','sourceApplication','responseType','rankingPageType'),
            'categoryPage'       => array('category','country','courseLevel','sourceApplication','responseType'),
            'coursePage'         => array('category','country','courseLevel','sourceApplication','responseType'),
            'universityPage'     => array('category','country','courseLevel','sourceApplication','responseType'),
            'departmentPage'     => array('category','country','courseLevel','sourceApplication','responseType'),
            'savedCoursesPage'   => array('category','country','courseLevel','sourceApplication','responseType'),
            'searchPage'         => array('category','country','courseLevel','sourceApplication','responseType'),
            'recommendationPage' => array('category','country','courseLevel','responseType'),
            'compareCoursesPage' => array('category','country','courseLevel','sourceApplication','responseType'),
            'rmcSuccessPage'     => array('category','country','courseLevel','sourceApplication','responseType'),
            'Overall'            => array('category','country','courseLevel','sourceApplication'),
            'articlePage'        => array(),
            'guidePage'          => array(),
            'applyContentPage'   => array(),
            'examContentPage'    => array(),
            'countryHomePage'    => array(),
            'countryPage'        => array(),
            'homePage'           => array(),
            'stagePage'          => array(),
            'allCoursePage'      => array('category','country','courseLevel','sourceApplication'),
            'userDashboard'      => array('category','country','courseLevel','sourceApplication'),
        ),
        'RMC' => array(
            'rankingPage'        => array('category','country','courseLevel','sourceApplication','rankingPageType'),
            'categoryPage'       => array('category','country','courseLevel','sourceApplication'),
            'coursePage'         => array('category','country','courseLevel','sourceApplication'),
            'universityPage'     => array('category','country','courseLevel','sourceApplication'),
            'departmentPage'     => array('category','country','courseLevel','sourceApplication'),
            'savedCoursesPage'   => array('category','country','courseLevel','sourceApplication'),
            'searchPage'         => array('category','country','courseLevel','sourceApplication'),
            'compareCoursesPage' => array('category','country','courseLevel','sourceApplication'),
            'rmcSuccessPage'     => array('category','country','courseLevel','sourceApplication'),
            'Overall'            => array('category','country','courseLevel','sourceApplication'),
            'recommendationPage' => array('category','country','courseLevel'),
            'stagePage'          => array(),
            'articlePage'        => array(),
            'guidePage'          => array(),
            'applyContentPage'   => array(),
            'examContentPage'    => array(),
            'countryHomePage'    => array(),
            'countryPage'        => array(),
            'homePage'           => array(),
            'allCoursePage'      => array('category','country','courseLevel','sourceApplication'),
            'userDashboard'      => array('category','country','courseLevel','sourceApplication'),
        )
    )
);
