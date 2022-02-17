<?php
$config['country_page_widget_placement'] = array(
	'countryHomePopularUniversity'   => array(
                                        'order'     => 1 ,
                                        'excludeInCountry'   => array(),  // prevent widget from appearing on a certain country (if ever required)
                                        'title' => 'Popular universities in {country}', // replace {country} by  country name
                                        'status'=> TRUE, // true\false : on\off
                                        'occupyMultipleColumns' => FALSE
                                    ),
	'countryHomePopularCourses'        => array(
                                        'order'     => 2 ,
                                        'excludeInCountry'   => array(),  // prevent widget from appearing on a certain country (if ever required)
                                        'title' => 'Popular courses in {country}', // replace {country} by  country name
                                        'status'=> TRUE, // true\false : on\off
                                        'occupyMultipleColumns' => FALSE
                                    ),
	'countryHomeFeaturedColleges'      => array(
                                        'order'     => 3 ,
                                        'excludeInCountry'   => array(),  // prevent widget from appearing on a certain country (if ever required)
                                        'title' => 'Featured universities in {country}', // replace {country} by  country name
                                        'status'=> TRUE, // true\false : on\off
                                        'occupyMultipleColumns' => FALSE
                                    ),
	'countryHomeGuideDownloads'         => array(
                                        'order'     => 4 ,
                                        'excludeInCountry'   => array(),  // prevent widget from appearing on a certain country (if ever required)
                                        'title' => 'Free downloads for {country}', // replace {country} by  country name
                                        'status'=> TRUE, // true\false : on\off
                                        'occupyMultipleColumns' => FALSE
                                    ),
	'countryHomeFilteredColleges'        => array(
                                        'order'     => 5 ,
                                        'excludeInCountry'   => array(),  // prevent widget from appearing on a certain country (if ever required)
                                        'title' => 'Browse {selectCourse} colleges based on Fees & Exams', // replace {selectCourse} by  HTML for dropdown of desired courses : MBA/MS/BE-BTech
                                        'status'=> TRUE, // true\false : on\off
                                        'occupyMultipleColumns' => TRUE
                                    ),
	'countryHomePopularArticles'       => array(
                                        'order'     => 6 ,
                                        'excludeInCountry'   => array(),  // prevent widget from appearing on a certain country (if ever required)
                                        'title' => 'Popular articles for {country}', // replace {country} by  country name
                                        'status'=> TRUE, // true\false : on\off
                                        'occupyMultipleColumns' => TRUE
                                    )
);
$config['country_home_widget_list'] = array(
                                                                'countryHomePopularUniversities'=> true,
                                                                'countryHomeFeeAffordability'=> true,
                                                                'countryHomeEligibilityExamScore'=> true
                                                            );
