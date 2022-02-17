<?php

$config['leftMenuArray'] = array(
    'Study Abroad Snapshot' => array(
        'className' => "fa-home",
        'children' => array(
            'Overview'	=> SHIKSHA_HOME."/trackingMIS/saMIS/metric/overview/abroad",
            'Traffic' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/traffic",
            'Engagement' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/engagement",
            'Registrations' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/registration",
            //'Leads' => SHIKSHA_HOME."/trackingMIS/saMIS/metric/leads",
            'Responses' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/response",
            'Consultant Enquiries' => SHIKSHA_HOME."/trackingMIS/saMIS/metric/cpenquiry",
            'RMC Responses' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/RMC",
            'Downloads' => SHIKSHA_HOME."/trackingMIS/saMIS/metric/downloads",
            'Comments - Replies' => SHIKSHA_HOME."/trackingMIS/saMIS/metric/commentReply",
            'Compare' => SHIKSHA_HOME."/trackingMIS/saMIS/metric/compare",
        )
    ),
    'Home Page' => array(
        'className'		=> "fa fa-bar-chart-o",
        'children'		=> array(
            'Traffic' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/traffic/homePage",
            'Engagement' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/engagement/homePage",
            'Registrations' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/registration/homePage",
            'Downloads' => SHIKSHA_HOME."/trackingMIS/saMIS/metric/downloads/homePage",
        )
    ),
    'Shiksha Apply Home' => array(
        'className'		=> "fa fa-bar-chart-o",
        'children'		=> array(
            'Traffic' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/traffic/applyHomePage",
            'Engagement' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/engagement/applyHomePage",
            'Registrations' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/registration/applyHomePage",
            'Exam Uploaded Docs' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/exam_upload/applyHomePage",
        )
    ),
    'Category Page' => array(
        'className'		=> "fa fa-bar-chart-o",
        'children'		=> array(
            'Traffic' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/traffic/categoryPage",
            'Engagement' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/engagement/categoryPage",
            'Registrations' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/registration/categoryPage",
            'Responses' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/response/categoryPage",
            'RMC Responses' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/RMC/categoryPage",
            'Downloads' => SHIKSHA_HOME."/trackingMIS/saMIS/metric/downloads/categoryPage",
            'Compare' => SHIKSHA_HOME."/trackingMIS/saMIS/metric/compare/categoryPage",
        )
    ),
    'Saved Course Page' => array(
        'className'		=> "fa fa-bar-chart-o",
        'children'		=> array(
            'Traffic' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/traffic/savedCoursesPage",
            'Engagement' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/engagement/savedCoursesPage",
            'Registrations' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/registration/savedCoursesPage",
            'Responses' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/response/savedCoursesPage",
            'RMC Responses' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/RMC/savedCoursesPage",
            'Compare' => SHIKSHA_HOME."/trackingMIS/saMIS/metric/compare/savedCoursesPage",
        )
    ),
    'University Listing' => array(
        'className'		=> "fa-table",
        'children'		=> array(
            'Traffic' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/traffic/universityPage",
            'Engagement' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/engagement/universityPage",
            'Registrations' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/registration/universityPage",
            'Responses' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/response/universityPage",
            'RMC Responses' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/RMC/universityPage",
            'Downloads' => SHIKSHA_HOME."/trackingMIS/saMIS/metric/downloads/universityPage",
        )
    ),
	'All Course Page' => array(
        'className'     => "fa fa-bar-chart-o",
        'children'      => array(
            'Traffic' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/traffic/allCoursePage",
            'Engagement' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/engagement/allCoursePage",
            'Registrations' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/registration/allCoursePage",
            'Responses' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/response/allCoursePage",
            'RMC Responses' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/RMC/allCoursePage",
        )
    ),
    'Department Listing' => array(
        'className'		=> "fa-home",
        'children'		=> array(
            'Traffic' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/traffic/departmentPage",
            'Engagement' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/engagement/departmentPage",
            'Registrations' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/registration/departmentPage",
            'Responses' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/response/departmentPage",
            'RMC Responses' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/RMC/departmentPage"
        )
    ),
    'Course Listing' => array(
        'className'		=> "fa-desktop",
        'children'		=> array(
            'Traffic' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/traffic/coursePage",
            'Engagement' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/engagement/coursePage",
            'Registrations' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/registration/coursePage",
            'Responses' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/response/coursePage",
            'RMC Responses' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/RMC/coursePage",
            'Downloads' => SHIKSHA_HOME."/trackingMIS/saMIS/metric/downloads/coursePage",
            'Compare' => SHIKSHA_HOME."/trackingMIS/saMIS/metric/compare/coursePage",
        )
    ),
    'User Dashboard Page' => array(
        'className'     => "fa-home",
        'children'      => array(
            'Traffic' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/traffic/userDashboard",
            'Engagement' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/engagement/userDashboard"
        )
    ),
    'Search Page' => array(
        'className'		=> "fa-home",
        'children'		=> array(
            'Home' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/home/searchPage",
            'Traffic' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/traffic/searchPage",
            'Engagement' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/engagement/searchPage",
            'Registrations' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/registration/searchPage",
            'Responses' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/response/searchPage",
            'RMC Responses' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/RMC/searchPage",
            'Compare' => SHIKSHA_HOME."/trackingMIS/saMIS/metric/compare/searchPage",
        )
    ),
    'Ranking Page' => array(
        'className'		=> "fa-home",
        'children'		=> array(
            'Traffic' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/traffic/rankingPage",
            'Engagement' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/engagement/rankingPage",
            'Registrations' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/registration/rankingPage",
            'Responses' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/response/rankingPage",
            'RMC Responses' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/RMC/rankingPage"
        )
    ),
    'Country Home Page' => array(
        'className'		=> "fa-table",
        'children'		=> array(
            'Traffic' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/traffic/countryHomePage",
            'Engagement' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/engagement/countryHomePage",
            'Registrations' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/registration/countryHomePage",
            'Downloads' => SHIKSHA_HOME."/trackingMIS/saMIS/metric/downloads/countryHomePage",
        )
    ),
    'Country Page' => array(
        'className'		=> "fa-home",
        'children'		=> array(
            'Traffic' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/traffic/countryPage",
            'Engagement' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/engagement/countryPage",
            'Registrations' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/registration/countryPage",
        )
    ),
    'Article Page' => array(
        'className'		=> "fa-edit",
        'children'		=> array(
            'Traffic' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/traffic/articlePage",
            'Engagement' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/engagement/articlePage",
            'Registrations' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/registration/articlePage",
            'Comments - Replies' => SHIKSHA_HOME."/trackingMIS/saMIS/metric/commentReply/articlePage",
        )
    ),
    'Guide Page' => array(
        'className'		=> "fa-desktop",
        'children'		=> array(
            'Traffic' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/traffic/guidePage",
            'Engagement' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/engagement/guidePage",
            'Registrations' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/registration/guidePage",
            'Downloads' => SHIKSHA_HOME."/trackingMIS/saMIS/metric/downloads/guidePage",
            'Comments - Replies' => SHIKSHA_HOME."/trackingMIS/saMIS/metric/commentReply/guidePage",
        )
    ),
    'Apply Content Page' => array(
        'className'		=> "fa-table",
        'children'		=> array(
            'Traffic' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/traffic/applyContentPage",
            'Engagement' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/engagement/applyContentPage",
            'Registrations' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/registration/applyContentPage",
            'Downloads' => SHIKSHA_HOME."/trackingMIS/saMIS/metric/downloads/applyContentPage",
            'Comments - Replies' => SHIKSHA_HOME."/trackingMIS/saMIS/metric/commentReply/applyContentPage",
        )
    ),
    'Exam Content Page' => array(
        'className'		=> "fa-edit",
        'children'		=> array(
            'Traffic' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/traffic/examContentPage",
            'Engagement' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/engagement/examContentPage",
            'Registrations' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/registration/examContentPage",
            'Downloads' => SHIKSHA_HOME."/trackingMIS/saMIS/metric/downloads/examContentPage",
            'Comments - Replies' => SHIKSHA_HOME."/trackingMIS/saMIS/metric/commentReply/examContentPage",
        )
    ),
    'Stage Page' => array(
        'className'		=> "fa-desktop",
        'children'		=> array(
            'Traffic' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/traffic/stagePage",
            'Engagement' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/engagement/searchPage",
            'Registrations' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/registration/stagePage",
        )
    ),
    'RMC Success Page' => array(
        'className'		=> "fa-home",
        'children'		=> array(
            //'Traffic' => SHIKSHA_HOME."/trackingMIS/saMIS/metric/traffic/rmcSuccessPage",
            //'Engagement' => SHIKSHA_HOME."/trackingMIS/saMIS/metric/engagements/rmcSuccessPage",
            'Responses' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/response/rmcSuccessPage",
            'RMC Responses' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/RMC/rmcSuccessPage",
            'Compare' => SHIKSHA_HOME."/trackingMIS/saMIS/metric/compare/rmcSuccessPage",
        )
    ),
    'Recommendation Page' => array(
        'className'		=> "fa-home",
        'children'		=> array(
            'Traffic' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/traffic/recommendationPage",
            'Engagement' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/engagement/recommendationPage",
            'Responses' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/response/recommendationPage",
            'RMC Responses' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/RMC/recommendationPage",
            'Compare' => SHIKSHA_HOME."/trackingMIS/saMIS/metric/compare/recommendationPage",
        )
    ),
    'Compare Page' => array(
        'className'		=> "fa-home",
        'children'		=> array(
            'Traffic' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/traffic/compareCoursesPage",
            'Engagement' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/engagement/compareCoursesPage",
            'Registrations' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/registration/compareCoursesPage",
            'Responses' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/response/compareCoursesPage",
            'RMC Responses' => SHIKSHA_HOME."/trackingMIS/Dashboard/metric/studyabroad/RMC/compareCoursesPage",
            'Compare' => SHIKSHA_HOME."/trackingMIS/saMIS/metric/compare/compareCoursesPage",
        )
    ),
);

$config['METRIC'] = array(
    'RESPONSE' => array(
        'PAGEWISE' => array(
            'SA_TOP_Tiles' => array('Total Responses','Responses/Respondent','Total Universities','Total Courses'),
            'LINE_CHART' =>array(
                'heading' => 'Responses'
            ),
        ),
        'OVERALL'=> array(
            'SA_TOP_Tiles' =>array('Total Responses','Responses/Respondent','Paid Responses','RMC Responses','Paid Courses'),
            'LINE_CHART' =>array(
                'heading' => 'Responses'
            ),
        )
    ),
    'LEADS' => array(
        'OVERALL'=> array(
            'SA_TOP_Tiles' =>array('Total Leads','MMP Leads', 'Response Leads','Signup Leads','Hamburger Leads'),
            'PIE_CHART' => array(
                'data' => array(
                    'SOURCE_APPLICATION' => array(
                        'heading' => 'Source Application',
                        'countHeading' => 'Total Leads',
                        'type' => 'Source Application',
                        'id' => 'sourceApplication'
                    ),
                    'TRAFFIC_SOURCE' => array(
                        'heading' => 'Sourcewise Usage',
                        'countHeading' => 'Total Leads',
                        'type' => 'Source',
                        'id' => 'leadsTrafficSource',
                    ),
                    'PAGE' => array(
                        'heading' => 'Pagewise Usage',
                        'countHeading' => 'Total Leads',
                        'type' => 'Page',
                        'id' => 'page',
                    ),
                    'LEADS_TYPE' => array(
                        'heading' => 'Leads Type',
                        'countHeading' => 'Total Leads',
                        'type' => 'Paid/Free',
                        'id' => 'leadType',
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
            'LINE_CHART' =>array(
                'heading' => 'Leads'
            ),
        )
    ),
    'RMC' => array(
        'PAGEWISE' => array(
            'SA_TOP_Tiles' => array('Total Responses','Responses/Respondent','Total Universities','Total Courses','Total Users'),
            'LINE_CHART' =>array(
                'heading' => 'RMC Responses'
            )
        ),

        'OVERALL'=> array(
            'SA_TOP_Tiles' =>array('Total Responses','Responses/Respondent','Total Universities','Total Courses','Total Users'),
            'LINE_CHART' =>array(
                'heading' => 'RMC Responses'
            )
        )
    ),

    'TRAFFIC' => array(
        'PAGEWISE' => array(
            'SA_TOP_Tiles' => array('Users','Sessions','Page Views','% New Sessions'),
            'PIE_CHART' => array(
                'data' => array(
                    'SOURCE_APPLICATION' => array(
                        'heading' => 'Source Application',
                        'countHeading' => 'Total Session',
                        'type' => 'Source Application',
                    ),
                    'TRAFFIC_SOURCE' => array(
                        'heading' => 'Sourcewise Usage',
                        'countHeading' => 'Total Session',
                        'type' => 'Source',
                    ),
                ),
            ),
            'BAR_GRAPH' => array(
                "UTM_SOURCE" => array(
                    "heading" => 'UTM Source',
                ),
                "UTM_MEDIUM" => array(
                    "heading" => 'UTM Medium',
                ),
                "UTM_CAMPAIGN" => array(
                    "heading" => 'UTM Campaign',
                ),
            ),
            'LINE_CHART' =>array(
                'heading' => 'Sessions'
            )
        ),

        'OVERALL'=> array(
            'SA_TOP_Tiles' =>array('Users','Sessions','Page Views','% New Sessions'),
            'PIE_CHART' => array(
                'data' => array(
                    'SOURCE_APPLICATION' => array(
                        'heading' => 'Source Application',
                        'countHeading' => 'Total Session',
                        'type' => 'Source Application',
                    ),
                    'TRAFFIC_SOURCE' => array(
                        'heading' => 'Sourcewise Usage',
                        'countHeading' => 'Total Session',
                        'type' => 'Source',
                    ),
                    'PAGE' => array(
                        'heading' => 'Pagewise Usage',
                        'countHeading' => 'Total Session',
                        'type' => 'Page',
                    )
                )
            ),
            'BAR_GRAPH' => array(
                "UTM_SOURCE" => array(
                    "heading" => 'UTM Source',
                ),
                "UTM_MEDIUM" => array(
                    "heading" => 'UTM Medium',
                ),
                "UTM_CAMPAIGN" => array(
                    "heading" => 'UTM Campaign',
                ),
            ),

            'LINE_CHART' =>array(
                'heading' => 'Sessions'
            )
        )
    ),

    'ENGAGEMENT' => array(
        'PAGEWISE' => array(
            'SA_TOP_Tiles' => array('Page Views','Pages/Session','Avg Session (mm:ss)','Exit Rate (%)','Bounce Rate (%)'),
            'PIE_CHART' => array(
                'data' => array(
                    'SOURCE_APPLICATION' => array(
                        'heading' => 'Source Application',
                        'countHeading' => 'Total Engagements',
                        'type' => 'Source Application',
                    ),
                    'TRAFFIC_SOURCE' => array(
                        'heading' => 'Sourcewise Usage',
                        'countHeading' => 'Total Session',
                        'type' => 'Source',
                    ),
                    'USER' => array(
                        'heading' => 'Userwise',
                        'countHeading' => 'Total Engagements',
                        'type' => 'Userwise',
                    ),
                ),
            ),
            'BAR_GRAPH' => array(
                "UTM_SOURCE" => array(
                    "heading" => 'UTM Source',
                ),
                "UTM_MEDIUM" => array(
                    "heading" => 'UTM Medium',
                ),
                "UTM_CAMPAIGN" => array(
                    "heading" => 'UTM Campaign',
                ),
            ),
            'LINE_CHART' =>array(
                'heading' => 'Engagements'
            ),
        ),

        'OVERALL'=> array(
            'SA_TOP_Tiles' => array('Page Views','Pages/Session','Avg Session (mm:ss)','Bounce Rate (%)'),
            'PIE_CHART' => array(
                'data' => array(
                    'SOURCE_APPLICATION' => array(
                        'heading' => 'Source Application',
                        'countHeading' => 'Total Engagements',
                        'type' => 'Source Application',
                    ),
                    'TRAFFIC_SOURCE' => array(
                        'heading' => 'Sourcewise Usage',
                        'countHeading' => 'Total Session',
                        'type' => 'Source',
                    ),
                    'USER' => array(
                        'heading' => 'Userwise',
                        'countHeading' => 'Total Engagements',
                        'type' => 'Userwise',
                    ),
                    'PAGE' => array(
                        'heading' => 'Pagewise Uses',
                        'countHeading' => 'Total Engagements',
                        'type' => 'Logged-in',
                    ),
                ),
            ),
            'BAR_GRAPH' => array(
                "UTM_SOURCE" => array(
                    "heading" => 'UTM Source',
                ),
                "UTM_MEDIUM" => array(
                    "heading" => 'UTM Medium',
                ),
                "UTM_CAMPAIGN" => array(
                    "heading" => 'UTM Campaign',
                ),
            ),
            'LINE_CHART' =>array(
                'heading' => 'Engagements'
            ),
        )
    ),

    'REGISTRATION' => array(
        'PAGEWISE' => array(
            'SA_TOP_Tiles' => array('Total Registrations','Signup Registrations','Hamburger Registrations','MMP Registrations'),
            'PIE_CHART' => array(
                'data' => array(
                    'SOURCE_APPLICATION' => array(
                        'heading' => 'Source Application',
                        'countHeading' => 'Total Registrations',
                        'type' => 'Source Application',
                    ),
                    'TRAFFIC_SOURCE' => array(
                        'heading' => 'Sourcewise Usage',
                        'countHeading' => 'Total Session',
                        'type' => 'Source',
                    ),
                    'ACTION' => array(
                        'heading' => 'Actionwise Usage',
                        'countHeading' => 'Total Registrations',
                        'type' => 'Source',
                    ),
                    'PAID-FREE' => array(
                        'heading' => 'Paid/Free',
                        'countHeading' => 'Total Registrations',
                        'type' => 'Paid/Free',
                    ),
                ),
            ),
            'LINE_CHART' => array(
                'heading' => 'Registrations'
            ),
        ),

        'OVERALL' => array(
            'SA_TOP_Tiles' =>array('Total Registrations','MMP Registrations', 'Response Registrations','Guide Registrations','Signup Registrations','Hamburger Registrations'),
            'PIE_CHART' => array(
                'data' => array(
                    'SOURCE_APPLICATION' => array(
                        'heading' => 'Source Application',
                        'countHeading' => 'Total Registrations',
                        'type' => 'Source Application',
                    ),
                    'TRAFFIC_SOURCE' => array(
                        'heading' => 'Sourcewise Usage',
                        'countHeading' => 'Total Session',
                        'type' => 'Source',
                    ),
                    'ACTION' => array(
                        'heading' => 'Actionwise Usage',
                        'countHeading' => 'Total Registrations',
                        'type' => 'Source',
                    ),
                    'PAID-FREE' => array(
                        'heading' => 'Paid/Free',
                        'countHeading' => 'Total Registrations',
                        'type' => 'Paid/Free',
                    ),
                    'PAGE' => array(
                        'heading' => 'Pagewise Usage',
                        'countHeading' => 'Total Registrations',
                        'type' => 'Page',
                    ),
                ),
            ),
            'BAR_GRAPH' => array(
                "UTM_SOURCE" => array(
                    "heading" => 'UTM Source',
                ),
                "UTM_MEDIUM" => array(
                    "heading" => 'UTM Medium',
                ),
                "UTM_CAMPAIGN" => array(
                    "heading" => 'UTM Campaign',
                ),
            ),
            'LINE_CHART' => array(
                'heading' => 'Registrations'
            ),
        )
    ),

    'DOWNLOAD' => array(
        'PAGEWISE' => array(
            'SA_TOP_Tiles' => array('Total Downloads','Registrations','Total Users','First Time Users'),
            'PIE_CHART' => array(
                'data' => array(
                    'SOURCE_APPLICATION' => array(
                        'heading' => 'Source Application',
                        'countHeading' => 'Total Downloads',
                        'type' => 'Source Application',
                    ),
                    'WIDGET' => array(
                        'heading' => 'Widgetwise Usage',
                        'countHeading' => 'Total Downloads',
                        'type' => 'Action',
                    ),
                ),
            ),
            'LINE_CHART' =>array(
                'heading' => 'Downloads'
            ),
        ),

        'OVERALL'=> array(
            'SA_TOP_Tiles' =>array('Total Downloads','Registrations','Total Users','First Time Users'),
            'PIE_CHART' => array(
                'data' => array(
                    'SOURCE_APPLICATION' => array(
                        'heading' => 'Source Application',
                        'countHeading' => 'Total Downloads',
                        'type' => 'Source Application',
                    ),
                    'PAGE' => array(
                        'heading' => 'Pagewise Usage',
                        'countHeading' => 'Total Downloads',
                        'type' => 'Page',
                    ),
                ),
            ),
            'LINE_CHART' =>array(
                'heading' => 'Downloads'
            ),
        )
    ),

    'CPENQUIRY' => array(
        'OVERALL'=> array(
            'SA_TOP_Tiles' =>array('Total Enquiries','Paid Consultants','Total Universites','Total Regions','Total Users','First Time Users'),
            'PIE_CHART' => array(
                'data' => array(
                    'SOURCE_APPLICATION' => array(
                        'heading' => 'Source Application',
                        'countHeading' => 'Total Consultant Enquiries',
                        'type' => 'Source Application',
                    ),
                    'ENQUIRY_TYPE' => array(
                        'heading' => 'Enquiry Sources',
                        'countHeading' => 'Total Consultant Enquiries',
                        'type' => 'Enquiry',
                    ),
                    'PAGE' => array(
                        'heading' => 'Pagewise Usage',
                        'countHeading' => 'Total Consultant Enquiries',
                        'type' => 'Page',
                    ),
                ),
            ),
            'LINE_CHART' =>array(
                'heading' => 'Consultant Enquiries'
            ),
        )
    ),

    'COMMENT_REPLY' => array(
        'PAGEWISE' => array(
            'SA_TOP_Tiles' => array('Total Comments','Total Replies','Avg Replies On Comments'),
            'PIE_CHART' => array(
                'data' => array(
                    'SOURCE_APPLICATION' => array(
                        'heading' => 'Source Application',
                        'countHeading' => 'Total Comments - Replies',
                        'type' => 'Source Application',
                    ),
                ),
            ),
            'LINE_CHART' =>array(
                'heading' => 'Comments - Replies'
            ),
        ),

        'OVERALL'=> array(
            'SA_TOP_Tiles' => array('Total Comments','Total Replies','Avg Replies On Comments'),
            'PIE_CHART' => array(
                'data' => array(
                    'SOURCE_APPLICATION' => array(
                        'heading' => 'Source Application',
                        'countHeading' => 'Total Comments - Replies',
                        'type' => 'Source Application',
                    ),
                    'PAGE' => array(
                        'heading' => 'Pagewise Usage',
                        'countHeading' => 'Total Comments - Replies',
                        'type' => 'Page',
                    ),
                ),
            ),
            'LINE_CHART' =>array(
                'heading' => 'Comments - Replies'
            ),
        )
    ),

    'COMPARE' => array(
        'PAGEWISE' => array(
            'SA_TOP_Tiles' => array('Courses Added','Unique Courses','Removed Courses','Total Users','First Time Users'),
            'PIE_CHART' => array(
                'data' => array(
                    'SOURCE_APPLICATION' => array(
                        'heading' => 'Source Application',
                        'countHeading' => 'Total added Courses',
                        'type' => 'Source Application',
                    ),
                    'USER' => array(
                        'heading' => 'Userwise',
                        'countHeading' => 'Total added Courses',
                        'type' => 'Userwise',
                    ),
                    'WIDGET' => array(
                        'heading' => 'Widgetwise',
                        'countHeading' => 'Total added Courses',
                        'type' => 'Widgetwise',
                    )

                ),
            ),
            'LINE_CHART' =>array(
                'heading' => 'Courses added To Compare List'
            ),
        ),

        'OVERALL'=> array(
            'SA_TOP_Tiles' => array('Courses Added','Unique Courses','Removed Courses','Total Responses','Total Users','First Time Users'),
            'PIE_CHART' => array(
                'data' => array(
                    'SOURCE_APPLICATION' => array(
                        'heading' => 'Source Application',
                        'countHeading' => 'Total added Courses',
                        'type' => 'Source Application',
                    ),
                    'USER' => array(
                        'heading' => 'Userwise',
                        'countHeading' => 'Total added Courses',
                        'type' => 'Userwise',
                    ),
                    'PAGE' => array(
                        'heading' => 'Pagewise Usage',
                        'countHeading' => 'Total added Courses',
                        'type' => 'Page',
                    )
                ),
            ),
            'LINE_CHART' =>array(
                'heading' => 'Courses added to Compare List'
            ),
        )
    ),

    'OVERVIEW' => array(
        'SA_TOP_Tiles' => array('Unique Users','Sessions','Page Views','Avg Session Duration','(Paid Responses)/(Paid Courses)','Total Registrations'),

        'BAR_GRAPH' => array(
            'TRAFFIC' => array(
                "TOP_PAGES" => array(
                    "heading" => 'Top Pages',
                ),
                "TOP_CATEGORY" => array(
                    "heading" => 'Top Categories',
                ),
                "TOP_SUBCATEGORY" => array(
                    "heading" => 'Top Sub-Categories',
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
                "TOP_CATEGORY" => array(
                    "heading" => 'Top Categories',
                ),
                "TOP_SUBCATEGORY" => array(
                    "heading" => 'Top Sub-Categories',
                ),
                "TOP_COUNTRY" => array(
                    "heading" => 'Top Countries',
                ),
                "TOP_CITIES" => array(
                    "heading" => 'Top Cities',
                ),
                "TOP_DESIRED_COUNTRY" => array(
                    "heading" => 'Top Desired Countries',
                ),
            ),

            'RESPONSES' => array(
                "TOP_PAGES" => array(
                    "heading" => 'Top Pages',
                ),
                "TOP_CATEGORY" => array(
                    "heading" => 'Top Categories',
                ),
                "TOP_SUBCATEGORY" => array(
                    "heading" => 'Top Sub-Categories',
                ),
                "TOP_LISTINGS" => array(
                    "heading" => 'Top Listings',
                ),
                "TOP_COUNTRY" => array(
                    "heading" => 'Top Countries',
                ),
                "TOP_CITIES" => array(
                    "heading" => 'Top Cities',
                ),
            )
        )
    ),

    'EXAM_UPLOAD' => array(
        'PAGEWISE' => array(
            'SA_TOP_Tiles' => array('Total Uploaded Docs','Total Users','First Time Users'),
            'LINE_CHART' =>array(
                'heading' => 'Total Uploaded Docs'
            ),
        ),
    ),
);

$config['FILTER'] = array(
    'TRAFFIC' => array('categoryPage' => array('category','country','courseLevel'),
        'coursePage'=> array('category','country','courseLevel'),
        'articlePage'=> array('category','country'),
        'guidePage' => array('category','country'),
        'applyContentPage'=>array('category','country'),
        'examContentPage' => array('category','country'),
        'rankingPage'=>array('category','country','rankingPageType'),
        'countryHomePage' => array('country'),
        'countryPage' =>array('country'),
        'universityPage' => array('country'),
        'departmentPage' =>array('country'),
        'homePage' => array(),
        'savedCoursesPage' =>array(),
        'searchPage' => array(),
        'stagePage' =>array(),
        'recommendationPage' =>array(),
        'compareCoursesPage' =>	array(),
        'Study Abroad' => array('category','country','courseLevel')
    ),
    'ENGAGEMENT' => array('categoryPage' => array('category','country','courseLevel'),
        'coursePage'=> array('category','country','courseLevel'),
        'articlePage'=> array('category','country'),
        'guidePage' => array('category','country'),
        'applyContentPage'=>array('category','country'),
        'examContentPage' => array('category','country'),
        'rankingPage'=>array('category','country','rankingPageType'),
        'countryHomePage' => array('country'),
        'countryPage' =>array('country'),
        'universityPage' => array('country'),
        'departmentPage' =>array('country'),
        'homePage' => array(),
        'savedCoursesPage' =>array(),
        'searchPage' => array(),
        'stagePage' =>array(),
        'recommendationPage' =>array(),
        'compareCoursesPage' =>	array(),
        'Study Abroad' => array('category','country','courseLevel')

    ),
    'RESPONSE'=>array(  'categoryPage' => array('category','country','courseLevel'),
        'coursePage'=> array('category','country','courseLevel'),
        'articlePage'=> array(),
        'guidePage' => array(),
        'applyContentPage'=> array(),
        'examContentPage' => array(),
        'rankingPage'=>array('category','country','courseLevel','rankingPageType'),
        'countryHomePage' => array(),
        'countryPage' => array(),
        'universityPage' => array('category','country','courseLevel'),
        'departmentPage' => array('category','country','courseLevel'),
        'homePage' => array(),
        'savedCoursesPage' => array('category','country','courseLevel'),
        'searchPage' => array('category','country','courseLevel'),
        'stagePage' =>array(),
        'recommendationPage' => array('category','country','courseLevel'),
        'compareCoursesPage' =>	array('category','country','courseLevel'),
        'rmcSuccessPage' => array('category','country','courseLevel'),
        'Study Abroad' => array('category','country','courseLevel')
    ),
    'RMC'=>array(  'categoryPage' => array('category','country','courseLevel'),
        'coursePage'=> array('category','country','courseLevel'),
        'articlePage'=> array(),
        'guidePage' => array(),
        'applyContentPage'=> array(),
        'examContentPage' => array(),
        'rankingPage'=>array('category','country','courseLevel','rankingPageType'),
        'countryHomePage' => array(),
        'countryPage' => array(),
        'universityPage' => array('category','country','courseLevel'),
        'departmentPage' => array('category','country','courseLevel'),
        'homePage' => array(),
        'savedCoursesPage' => array('category','country','courseLevel'),
        'searchPage' => array('category','country','courseLevel'),
        'stagePage' =>array(),
        'recommendationPage' => array('category','country','courseLevel'),
        'compareCoursesPage' =>	array('category','country','courseLevel'),
        'rmcSuccessPage' => array('category','country','courseLevel'),
        'Study Abroad' => array('category','country','courseLevel')
    ),
    'REGISTRATION'=>array(  'categoryPage' => array('category','country','courseLevel'),
        'coursePage'=> array('category','country','courseLevel'),
        'articlePage'=> array('category','country','courseLevel'),
        'guidePage' => array('category','country','courseLevel'),
        'applyContentPage'=> array('category','country','courseLevel'),
        'examContentPage' => array('category','country','courseLevel'),
        'rankingPage'=>array('category','country','courseLevel','rankingPageType'),
        'countryHomePage' => array('category','country','courseLevel'),
        'countryPage' => array('category','country','courseLevel'),
        'universityPage' => array('category','country','courseLevel'),
        'departmentPage' => array('category','country','courseLevel'),
        'homePage' => array('category','country','courseLevel'),
        'savedCoursesPage' => array('category','country','courseLevel'),
        'searchPage' => array('category','country','courseLevel'),
        'stagePage' =>array('category','country','courseLevel'),
        'recommendationPage' => array(),
        'compareCoursesPage' =>	array('category','country','courseLevel'),
        'rmcSuccessPage' => array(),
        'Study Abroad' => array('category','country','courseLevel')
    ),
    'LEADS'=>array(  'categoryPage' => array('category','country','courseLevel'),
        'coursePage'=> array('category','country','courseLevel'),
        'articlePage'=> array(),
        'guidePage' => array(),
        'applyContentPage'=> array(),
        'examContentPage' => array(),
        'rankingPage'=>array('category','country','courseLevel','rankingPageType'),
        'countryHomePage' => array(),
        'countryPage' => array(),
        'universityPage' => array('category','country','courseLevel'),
        'departmentPage' => array('category','country','courseLevel'),
        'homePage' => array(),
        'savedCoursesPage' => array('category','country','courseLevel'),
        'searchPage' => array('category','country','courseLevel'),
        'stagePage' =>array(),
        'recommendationPage' => array('category','country','courseLevel'),
        'compareCoursesPage' =>	array('category','country','courseLevel'),
        'rmcSuccessPage' => array('category','country','courseLevel'),
        'Study Abroad' => array('category','country','courseLevel')
    ),
    'DOWNLOAD' => array('categoryPage' => array('category','country','courseLevel'),
        'coursePage'=> array('category','country','courseLevel'),
        'articlePage'=> array(),
        'guidePage' => array('category','country','courseLevel'),
        'applyContentPage'=>array('category','country','courseLevel'),
        'examContentPage' => array('category','country','courseLevel'),
        'rankingPage'=>array('rankingPageType'),
        'countryHomePage' => array('category','country','courseLevel'),
        'countryPage' =>array(),
        'universityPage' => array('category','country','courseLevel'),
        'departmentPage' =>array(),
        'homePage' => array('category','country','courseLevel'),
        'savedCoursesPage' =>array(),
        'searchPage' => array(),
        'stagePage' => array(),
        'recommendationPage' => array(),
        'compareCoursesPage' =>	array(),
        'rmcSuccessPage' => array(),
        'Study Abroad' => array('category','country','courseLevel')
    ),
    'COMPARE' => array('categoryPage' => array('category','country','courseLevel','courseComparedFilter'),
        'coursePage'=> array('category','country','courseLevel','courseComparedFilter'),
        'articlePage'=> array(),
        'guidePage' => array(),
        'applyContentPage'=> array(),
        'examContentPage' => array(),
        'rankingPage'=> array('rankingPageType'),
        'countryHomePage' => array(),
        'countryPage' => array(),
        'universityPage' => array(),
        'departmentPage' => array(),
        'homePage' => array(),
        'savedCoursesPage' => array('category','country','courseLevel','courseComparedFilter'),
        'searchPage' => array('category','country','courseLevel','courseComparedFilter'),
        'stagePage' => array(),
        'recommendationPage' => array('category','country','courseLevel'),
        'compareCoursesPage' =>	array('category','country','courseLevel','courseComparedFilter'),
        'rmcSuccessPage' => array('category','country','courseLevel','courseComparedFilter'),
        'Study Abroad' => array('category','country','courseLevel','courseComparedFilter')
    ),
    'COMMENT_REPLY' => array('categoryPage' => array(),
        'coursePage'=> array(),
        'articlePage'=> array('category','country','courseLevel'),
        'guidePage' => array('category','country','courseLevel'),
        'applyContentPage'=>array('category','country','courseLevel'),
        'examContentPage' => array('category','country','courseLevel'),
        'rankingPage'=> array('rankingPageType'),
        'countryHomePage' => array(),
        'countryPage' => array(),
        'universityPage' => array(),
        'departmentPage' => array(),
        'homePage' => array(),
        'savedCoursesPage' => array(),
        'searchPage' => array(),
        'stagePage' => array(),
        'recommendationPage' => array(),
        'compareCoursesPage' =>	array(),
        'rmcSuccessPage' => array(),
        'Study Abroad' => array('category','country','courseLevel')
    ),
    'CPENQUIRY' => array('Study Abroad' => array('consultants','consultantLocationRegions')),
    'OVERVIEW' => array('device'),
    'EXAM_UPLOAD' => array(
        'applyHomePage' => array('abroadExamList','sourceApplication'),
    ),
);

/*$config['FILTER'] = array(
						'rankingPage' => array('category','country','courseLevel','pageType'=>array('uniRankingPage','courseRankingPage'));
						'categoryPage' => array('category','country','courseLevel');
						'countryPage' => array('category','country','courseLevel');
						'coursePage' => array('category','country','courseLevel');
						'universityPage' => array('category','country','courseLevel');
						'homePage' => array('category','country','courseLevel');
						'rankingPage' => array('category','country','courseLevel');
						'countryHomePage' => array('category','country','courseLevel');
						'shortlistPage' => array('category','country','courseLevel');
						'searchPage' => array('category','country','courseLevel');
						'articlePage' => array('category','country','courseLevel');
						'guidePage' => array('category','country','courseLevel');
						'applyContentPage' => array('category','country','courseLevel');
						'examContentPage' => array('category','country','courseLevel');
						'stagePage' => array('category','country','courseLevel');


						);*/
