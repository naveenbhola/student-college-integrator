<?php

$listings_not_included_in_recommendations = array(
    'INSTITUTES' => array(32470, 24852), // All live courses of these institutes will be taken care of already, no need to mention the courses below if institute is listed here.
    'COURSES' => array() // Mention only the courses whose institue is not mentioned above.
);

$parentToLDBCourseMapping = array(1 => 2, 35 => 52);

$highPriorityActions = array(
                                'Asked_Question_On_Listing',
                                'Asked_Question_On_Listing_MOB',
                                'Asked_Question_On_CCHome',
                                'Asked_Question_On_CCHome_MOB',
                                'D_MS_Ask',
                                'CP_Reco_ReqEbrochure',
                                'GetFreeAlert',
                                'LISTING_PAGE_EMAIL_SMS_CONTACT_DETAILS',
                                'LP_Reco_ ReqEbrochure',
                                'MOBILEHTML5',
		                'MOBILE5_CATEGORY_PAGE',
                		'MOBILE5_INSTITUTE_DETAIL_PAGE',
		                'MOBILE5_SIMILAR_INSTITUTE_DETAIL_PAGE',
                		'MOBILE5_SIMILAR_COURSE_DETAIL_PAGE',
		                'MOBILE5_COURSE_DETAIL_PAGE',
                		'MOBILE5_COURSE_DETAIL_PAGE_OTHER',
		                'MOBILE5_SEARCH_PAGE',
                		'MOBILE5_COLLEGE_PREDICTOR_PAGE',
		                'MOBILE5_RANK_PREDICTOR_PAGE',
                		'MOBILE5_SHORTLIST_PAGE',
		                'MOBILEHTML5_GETEB',
                		'RANKING_MOB_ReqEbrochure',
                                'mobilesite',
                                'mobilesitesearch',
                                'RANKING_PAGE_REQUEST_EBROCHURE',
                                'Request_E-Brochure',
                                'RESPONSE_MARKETING_PAGE',
                                'SEARCH_REQUEST_EBROCHURE',
                                'CoursePage_Reco',
                                'download_brochure_free_course'
                            );

$alsoViewedDecayFactor = 0.3;

$alsoViewedDecayInterval = 6;

$alsoViewedWeights = array('level_1' => 1.0, 'level_2' => 1.0, 'level_3' => 1.0);

$timePerSession = 1800;

$maximumViewsInSession = 100;

$internalIPs = array('115.249.243.194','115.254.79.170','121.243.22.130');

