<?php

$trackingHome = SHIKSHA_HOME."/trackingMIS/Listings";

$config['leftMenuArray'] = array(
    'Domestic Snapshot'         => array(
        'className' => "fa-home",
        'children'  => array(
            'Overview'          => $trackingHome. "/dashboard",
            'Traffic'           => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/traffic",
            'Engagement'        => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/engagement",
            'Registration'      => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/registration",
            'Responses'         => SHIKSHA_HOME . "/trackingMIS/Dashboard/metric/domestic/response",
            'Shiksha Assistant' => SHIKSHA_HOME . "/trackingMIS/Dashboard/metric/domestic/sassistant",
        )
    ),
    "Exam Page Group" => array(
        'className' => "fa fa-bar-chart-o",
        'children'  => array(
            'Traffic'       => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/traffic/examPageMain",
            'Engagement'    => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/engagement/examPageMain",
            'Registration' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/registration/examPageMain",
            'Responses'         => SHIKSHA_HOME . "/trackingMIS/Dashboard/metric/domestic/response/examPageMain",
        )
    ),
/*    'Article Detail Metrics' => array(
        'className' => "fa fa-bar-chart-o",
        'children'  => array(
            'Traffic'       => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/traffic/allArticlePage",
            'Engagement'    => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/engagement/allArticlePage",
            'Registration' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/registration/allArticlePage",
            'Responses'         => SHIKSHA_HOME . "/trackingMIS/Dashboard/metric/domestic/response/allArticlePage",
        )
    ),
    'AnA Metrics' => array(
        'className' => "fa fa-bar-chart-o",
        'children'  => array(
            'Traffic'       => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/traffic/questionDetailPage",
            'Engagement'    => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/engagement/questionDetailPage",
            'Registration' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/registration/questionDetailPage",
            'Responses'         => SHIKSHA_HOME . "/trackingMIS/Dashboard/metric/domestic/response/questionDetailPage",
        )
    ),
    'Category Page Metrics' => array(
        'className' => "fa fa-bar-chart-o",
        'children'  => array(
            'Traffic'       => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/traffic/categoryPage",
            'Engagement'    => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/engagement/categoryPage",
            'Registration' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/registration/categoryPage",
            'Responses'         => SHIKSHA_HOME . "/trackingMIS/Dashboard/metric/domestic/response/categoryPage",
        )
    ),
    'Campus Connect - 1'    => array(
        'className' => "fa-bar-chart-o",
        'children'  => array(
            'Traffic'       => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/traffic/campusRepresentative",
            'Engagement'     => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/engagement/campusRepresentative",
            'Registration' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/registration/campusRepresentative",
            'Responses'         => SHIKSHA_HOME . "/trackingMIS/Dashboard/metric/domestic/response/campusRepresentative",
        )
    ),
    'Career Compass Metrics'    => array(
        'className' => "fa fa-bar-chart-o",
        'children'  => array(
            'Traffic'       => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/traffic/careerCompasPage",
            'Engagement'     => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/engagement/careerCompasPage",
            'Registration' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/registration/careerCompasPage",
            'Responses'         => SHIKSHA_HOME . "/trackingMIS/Dashboard/metric/domestic/response/careerCompasPage",
        )
    ),
    'College Predictor Metrics'    => array(
        'className' => "fa fa-bar-chart-o",
        'children'  => array(
            'Traffic'       => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/traffic/collegePredictor",
            'Engagement'     => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/engagement/collegePredictor",
            'Registration' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/registration/collegePredictor",
            'Responses'         => SHIKSHA_HOME . "/trackingMIS/Dashboard/metric/domestic/response/collegePredictor",
        )
    ),
    'College Review - 1 '    => array(
        'className' => "fa fa-bar-chart-o",
        'children'  => array(
            'Traffic'       => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/traffic/collegeReviewPage",
            'Engagement'     => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/engagement/collegeReviewPage",
            'Registration' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/registration/collegeReviewPage",
            'Responses'         => SHIKSHA_HOME . "/trackingMIS/Dashboard/metric/domestic/response/collegeReviewPage",
        )
    ),
    'Course Home Metrics'    => array(
        'className' => "fa fa-bar-chart-o",
        'children'  => array(
            'Traffic'       => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/traffic/courseHomePage",
            'Engagement'     => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/engagement/courseHomePage",
            'Registration' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/registration/courseHomePage",
            'Responses'         => SHIKSHA_HOME . "/trackingMIS/Dashboard/metric/domestic/response/courseHomePage",
        )
    ),
    'Course Listing Metrics'    => array(
        'className' => "fa fa-bar-chart-o",
        'children'  => array(
            'Traffic'       => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/traffic/courseDetailsPage",
            'Engagement'     => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/engagement/courseDetailsPage",
            'Registration' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/registration/courseDetailsPage",
            'Responses'         => SHIKSHA_HOME . "/trackingMIS/Dashboard/metric/domestic/response/courseDetailsPage",
        )
    ),
    'University Listing Metrics'    => array(
        'className' => "fa fa-bar-chart-o",
        'children'  => array(
            'Traffic'       => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/traffic/universityListingPage",
            'Engagement'     => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/engagement/universityListingPage",
            'Registration' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/registration/universityListingPage",
            'Responses'         => SHIKSHA_HOME . "/trackingMIS/Dashboard/metric/domestic/response/universityListingPage",
        )
    ),
    'Exam Page Metrics'         => array(
        'className' => "fa fa-bar-chart-o",
        'children'  => array(
            'Traffic'       => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/traffic/examPage",
            'Engagement'     => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/engagement/examPage",
            'Registration' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/registration/examPage",
            'Responses'         => SHIKSHA_HOME . "/trackingMIS/Dashboard/metric/domestic/response/examPage",
        )
    ),
    'All Exam Page Metrics'         => array(
        'className' => "fa fa-bar-chart-o",
        'children'  => array(
            'Traffic'       => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/traffic/allExamPage",
            'Engagement'     => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/engagement/allExamPage",
            'Registration' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/registration/allExamPage",
            'Responses'         => SHIKSHA_HOME . "/trackingMIS/Dashboard/metric/domestic/response/allExamPage",
        )
    ),
    'Exam Calendar Metrics'         => array(
        'className' => "fa fa-bar-chart-o",
        'children'  => array(
            'Traffic'       => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/traffic/eventCalendar",
            'Engagement'     => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/engagement/eventCalendar",
            'Registration' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/registration/eventCalendar",
            'Responses'         => SHIKSHA_HOME . "/trackingMIS/Dashboard/metric/domestic/response/eventCalendar",
        )
    ),
    'IIM Call Predictor'  => array(
        'className' => "fa fa-bar-chart-o",
        'children' => array(
            'Traffic'       => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/traffic/iimPredictorInput",
            'Engagement'     => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/engagement/iimPredictorInput",
            'Registration' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/registration/iimPredictorInput",
            'Responses'         => SHIKSHA_HOME . "/trackingMIS/Dashboard/metric/domestic/response/iimPredictorInput",
            )
        )
    ,
    'Institute Listing Metrics' => array(
        'className' => "fa fa-bar-chart-o",
        'children'  => array(
            'Traffic'       => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/traffic/instituteListingPage",
            'Engagement'     => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/engagement/instituteListingPage",
            'Registration' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/registration/instituteListingPage",
            'Responses'         => SHIKSHA_HOME . "/trackingMIS/Dashboard/metric/domestic/response/instituteListingPage",
        )
    ),
    'Home Page Metrics'         => array(
        'className' => "fa fa-bar-chart-o",
        'children'  => array(
            'Traffic'       => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/traffic/homePage",
            'Engagement'     => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/engagement/homePage",
            'Registration' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/registration/homePage"
        )
    ),
    'Ranking Page Metrics'      => array(
        'className' => "fa fa-bar-chart-o",
        'children'  => array(
            'Traffic'       => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/traffic/rankingPage",
            'Engagement'     => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/engagement/rankingPage",
            'Registration' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/registration/rankingPage",
            'Responses'         => SHIKSHA_HOME . "/trackingMIS/Dashboard/metric/domestic/response/rankingPage",
        )
    ),
    'Rank Predictor Metrics'         => array(
        'className' => "fa fa-bar-chart-o",
        'children'  => array(
            'Traffic'       => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/traffic/rankPredictor",
            'Engagement'     => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/engagement/rankPredictor",
            'Registration' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/registration/rankPredictor",
            'Responses'         => SHIKSHA_HOME . "/trackingMIS/Dashboard/metric/domestic/response/rankPredictor",
        )
    ),
    'Shortlist Page Metrics'    => array(
        'className' => "fa fa-bar-chart-o",
        'children'  => array(
            'Traffic'       => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/traffic/shortlistPage",
            'Engagement'     => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/engagement/shortlistPage",
            'Registration' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/domestic/registration/shortlistPage",
            'Responses'         => SHIKSHA_HOME . "/trackingMIS/Dashboard/metric/domestic/response/shortlistPage",
        )
    ),*/
);


$config['METRIC'] = array(
    'OVERVIEW' => array(
        'BAR_GRAPH' => array(
            'TRAFFIC' => array(
                "TOP_PAGES" => array(
                    "heading" => 'Top Pages',
                ),
                "TOP_STREAM" => array(
                        "heading" => 'Top Streams',
                ),
                "TOP_COUNTRY" => array(
                        "heading" => 'Top Countries',
                ),
                "TOP_CITIES" => array(
                        "heading" => 'Top Cities',
                )
            ),
            
            'REGISTRATION' => array(
                "TOP_PAGES" => array(
                    "heading" => 'Top Pages',
                ),
                "TOP_STREAM" => array(
                    "heading" => 'Top Streams',
                ),
                "TOP_COUNTRY" => array(
                    "heading" => 'Top Countries',
                ),
                "TOP_CITIES" => array(
                    "heading" => 'Top Cities',
                ),
            ),
            
            'RESPONSES' => array(
                "TOP_PAGES" => array(
                    "heading" => 'Top Pages',
                ),
                "TOP_STREAM" => array(
                    "heading" => 'Top Streams',
                ),            
                "TOP_LISTINGS" => array(
                    "heading" => 'Top Listings',
                ),
                "TOP_CITIES" => array(
                    "heading" => 'Top Cities',
                ),
            )
        )
    )
);

$config['responses'] = array(
    'tileNames'      => array(
        'totalResponseCount' => 'Total Responses',
        'respondentRatio'    => 'Responses/Respondent',
        'paidResponseCount'  => 'Paid Responses',
        'paidCoursesCount'   => 'Paid Courses',
        //'firstTimeUserCount' => 'First Time Users',
    ),
    'splits'    => array(
        array(
            'id'    => 'device',
            'title' => 'Responses - Source Application',
            'viewFileName'  => 'showSplit'
        ),
        array(
            'id'    => 'session',
            'title' => 'Responses - Traffic Source',
            'viewFileName'  => 'showSplit'
        ),
        array(
            'id'    => 'trafficSourceDrillDown',
            'title' => 'Responses - Traffic Source',
            'viewFileName'  => 'sourceBarGraph'
        ),
        /*array(
            'id'    => 'action',
            'title' => 'Registrations - Action',
        ),
        array(
            'id'    => 'registrationType',
            'title' => 'Registrations - Type',
        array(
            'id'    => 'page',
            'title' => 'Registrations - Page',
        ),
        ),*/
        array(
            'id'    => 'widget',
            'title' => 'Responses - Widget',
            'viewFileName'  => 'showSplit'
        ),
        array(
            'id'    => 'pivotType',
            'title' => 'Responses - Paid/Free',
            'viewFileName'  => 'showSplit'
        ),
        array(
            'id'    => 'page',
            'title' => 'Responses - Page',
            'viewFileName'  => 'showSplit'
        ),

    ),
);

$config['registration'] = array(
    'tileNames' => array(
        'totalCount'        => 'Total Registration',
        'mmpCount'          => 'MMP Registration',
        'responseRegCount'  => 'Response Registration',
        'signupRegCount'    => 'Signup Registration',
        'hamburgerRegCount' => 'Hamburger Registration',
    ),
    'splits'    => array(
        array(
            'id'    => 'device',
            'title' => 'Registrations - Source Application',
            'viewFileName'  => 'showSplit'
        ),
        array(
            'id'    => 'session',
            'title' => 'Registrations - Traffic Source',
            'viewFileName'  => 'showSplit'
        ),
        array(
            'id'    => 'trafficSourceDrillDown',
            'title' => 'Registrations - Traffic Source',
            'viewFileName'  => 'sourceBarGraph'
        ),
        array(
            'id'    => 'widget',
            'title' => 'Registrations - Widget',
            'viewFileName'  => 'showSplit'
        ),
        array(
            'id'    => 'pivotType',
            'title' => 'Registrations - Paid/Free',
            'viewFileName'  => 'showSplit'
        ),
        array(
            'id'    => 'page',
            'title' => 'Registrations - Page',
            'viewFileName'  => 'showSplit'
        ),
    ),
);

$config['traffic'] = array(
    'tileNames'      => array(
        'user' => 'Users',
        'session' => 'Unique Sessions',
        'pageview' => 'Page Views',
        'newsession' => '(%) New Sessions',
    ),
    'splits'    => array(
        array(
            'id'    => 'device',
            'title' => 'Traffic - Source Application',
            'viewFileName'  => 'showSplit'
        ),
        array(
            'id'    => 'session',
            'title' => 'Traffic - Traffic Source',
            'viewFileName'  => 'showSplit'
        ),
        array(
            'id'    => 'trafficSourceDrillDown',
            'title' => 'Traffic - Traffic Source',
            'viewFileName'  => 'sourceBarGraph'
        ),
        array(
            'id'    => 'page',
            'title' => 'Traffic - Page',
            'viewFileName'  => 'showSplit'
        ),
    )
);

$config['engagement'] = array(
    'tileNames'      => array(
        'pageview' => 'Page Views',
        'pgpersess' => 'Pages / Session',
        'bounce' => 'Bounce (%)',
        'exit' => 'Exit (%)',
        'avgsessdur' => 'Avg Session(mm:ss)',
    ),
    'splits'    => array(
        array(
            'id'    => 'device',
            'title' => 'Engagement - Source Application',
            'viewFileName'  => 'showSplit'
        ),
        array(
            'id'    => 'session',
            'title' => 'Engagement - Traffic Source',
            'viewFileName'  => 'showSplit'
        ),
        array(
            'id'    => 'trafficSourceDrillDown',
            'title' => 'Engagement - Traffic Source',
            'viewFileName'  => 'sourceBarGraph'
        ),
        array(
            'id'    => 'page',
            'title' => 'Enagagements - Page',
            'viewFileName'  => 'showSplit'
        ),
    )
);


