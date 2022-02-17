<?php
// in this config, key is courseHomePageId of the respective course home page ,if enabled and data present , widget comes on the page
$config['rankPredictorWidgetInfo'] = array(
    '6' =>array(
		"examsForRankPrediction"=>array(
            "jee main"=>array(
                "url"       => SHIKSHA_HOME . "/jee-main-rank-predictor",
                "heading"   => "Want to predict your JEE MAIN ".date('Y')." rank ?",
                "urlCaption"=> "Enter your score and predict your rank"
            )
        )
    ),
);

$config['rankingWidgetInfo'] = array(
    '6' => array(
        'collegePredictorExams' => array(
            "jee-mains" => array(
                "url" => COLLEGE_PREDICTOR_BASE_URL . "/jee-mains-college-predictor", 
                "name" => "JEE MAIN " . date('Y') . " College predictor"
            ),
            "kcet" => array(
                "url" => COLLEGE_PREDICTOR_BASE_URL . "/kcet-college-predictor", 
                "name" => "KCET " . date('Y') . " College predictor"
            ),
            "comedk" => array(
                "url" => COLLEGE_PREDICTOR_BASE_URL . "/comedk-college-predictor", 
                "name" => "COMEDK " . date('Y') . " College predictor"
            ),
            "keam" => array(
                "url" => COLLEGE_PREDICTOR_BASE_URL . "/keam-college-predictor", 
                "name" => "KEAM " . date('Y') . " College predictor"
            ),
            "wbjee" => array(
                "url" => COLLEGE_PREDICTOR_BASE_URL . "/wbjee-college-predictor", 
                "name" => "WBJEE " . date('Y') . " College predictor"
            ),
            "mppet" => array(
                "url" => COLLEGE_PREDICTOR_BASE_URL . "/mppet-college-predictor", 
                "name" => "MPPET " . date('Y') . " College predictor"
            ),
            "cgpet" => array(
                "url" => COLLEGE_PREDICTOR_BASE_URL . "/cgpet-college-predictor", 
                "name" => "CGPET " . date('Y') . " College predictor"
            ),
            "tnea" => array(
                "url" => COLLEGE_PREDICTOR_BASE_URL . "/tnea-college-predictor", 
                "name" => "TNEA " . date('Y') . " College predictor"
            ),
            "ptu" => array(
                "url" => COLLEGE_PREDICTOR_BASE_URL . "/ptu-college-predictor", 
                "name" => "PTU " . date('Y') . " College predictor"
            ),
            "upsee" => array(
                "url" => COLLEGE_PREDICTOR_BASE_URL . "/upsee-college-predictor", 
                "name" => "UPSEE " . date('Y') . " College predictor"
            ),
            "mhcet" => array(
                "url" => COLLEGE_PREDICTOR_BASE_URL . "/mhcet-college-predictor", 
                "name" => "MHCET " . date('Y') . " College predictor"
            ),
            "hstes" => array(
                "url" => COLLEGE_PREDICTOR_BASE_URL . "/hstes-college-predictor", 
                "name" => "HSTES " . date('Y') . " College predictor"
            ),
            "ap-eamcet" => array(
                "url" => COLLEGE_PREDICTOR_BASE_URL . "/ap-eamcet-college-predictor", 
                "name" => "AP-EAMCET " . date('Y') . " College predictor"
            ),
            "ts-eamcet" => array(
                "url" => COLLEGE_PREDICTOR_BASE_URL . "/ts-eamcet-college-predictor", 
                "name" => "TS-EAMCET " . date('Y') . " College predictor"
            ),
            "ojee" => array(
                "url" => COLLEGE_PREDICTOR_BASE_URL . "/ojee-college-predictor", 
                "name" => "OJEE " . date('Y') . " College predictor"
            ),
           "bitsat" => array(
                "url" => COLLEGE_PREDICTOR_BASE_URL . "/bitsat-college-predictor", 
                "name" => "BITSAT " . date('Y') . " College predictor"
            ),
            "ggsipu" => array(
                "url" => COLLEGE_PREDICTOR_BASE_URL . "/ggsipu-college-predictor", 
                "name" => "GGSIPU " . date('Y') . " College predictor"
            ),
            "gujcet" => array(
                "url" => COLLEGE_PREDICTOR_BASE_URL . "/gujcet-college-predictor", 
                "name" => "GUJCET " . date('Y') . " College predictor"
            )
            
        ),
        'rankPredictorExams' => array(
            "jee-mains" => array(
                "url" => RANK_PREDICTOR_BASE_URL . "/jee-main-rank-predictor", 
                "name" => "JEE MAIN " . date('Y') . " Rank predictor"
            ),
            "comedk" => array(
                "url" => RANK_PREDICTOR_BASE_URL . "/comedk-rank-predictor", 
                "name" => "COMEDK " . date('Y') . " Rank predictor"
            ),
            "jee-advanced" => array(
                "url" => RANK_PREDICTOR_BASE_URL . "/jee-advanced-rank-predictor", 
                "name" => "JEE Advanced " . date('Y') . " Rank predictor"
            )
        ))
);

$config['jobsDataWidget']=array(
    '1'=>array( 
        'heading' => "Have a dream Job?",
        'subHeading'=>"Find MBA colleges to help you get there!",
        'column1' => array(
        array('name'=>'Sales','url'=>SHIKSHA_HOME.'/mba/resources/best-mba-sales-colleges-based-on-mba-alumni-data'),
        array('name'=>'Finance','url'=>SHIKSHA_HOME.'/mba/resources/best-mba-finance-colleges-based-on-mba-alumni-data'),
        array('name'=>'Marketing','url'=>SHIKSHA_HOME.'/mba/resources/best-mba-marketing-colleges-based-on-mba-alumni-data'),
        array('name'=>'More &raquo','url'=>SHIKSHA_HOME.'/mba/resources/mba-alumni-data')
        
    ),
    'column2'=>array(
        array('name'=>'HDFC Bank','url'=>SHIKSHA_HOME.'/mba/resources/mba-alumni-data','cookieDetails'=>array('name'=>'seo_data','value'=>'Companies_HDFC Bank_0')),
        array('name'=>"Infosys Technology's",'url'=>SHIKSHA_HOME.'/mba/resources/mba-alumni-data','cookieDetails'=>array('name'=>'seo_data','value'=>'Companies_HDFC Bank_0')),
        array('name'=>'Axis Bank','url'=>SHIKSHA_HOME.'/mba/resources/mba-alumni-data','cookieDetails'=>array('name'=>'seo_data','value'=>'Companies_HDFC Bank_0')),
        array('name'=>'More &raquo','url'=>SHIKSHA_HOME.'/mba/resources/mba-alumni-data')
    )
        ),
    '6'=>array( 
        'heading' => "Have a dream Job?",
        'subHeading'=>"Find Engineering colleges to help you get there!",
        'column1' => array(
        array('name'=>'EESales','url'=>SHIKSHA_HOME.'/mba/resources/best-mba-sales-colleges-based-on-mba-alumni-data'),
        array('name'=>'EEFinance','url'=>SHIKSHA_HOME.'/mba/resources/best-mba-finance-colleges-based-on-mba-alumni-data'),
        array('name'=>'EEMarketing','url'=>SHIKSHA_HOME.'/mba/resources/best-mba-marketing-colleges-based-on-mba-alumni-data'),
        array('name'=>'EEMore &raquo','url'=>SHIKSHA_HOME.'/mba/resources/mba-alumni-data')
        
    ),
    'column2'=>array(
        array('name'=>'EEHDFC Bank','url'=>SHIKSHA_HOME.'/mba/resources/mba-alumni-data','cookieDetails'=>array('name'=>'seo_data','value'=>'Companies_HDFC Bank_0')),
        array('name'=>"EEInfosys Technology's",'url'=>SHIKSHA_HOME.'/mba/resources/mba-alumni-data','cookieDetails'=>array('name'=>'seo_data','value'=>'Companies_HDFC Bank_0')),
        array('name'=>'EEAxis Bank','url'=>SHIKSHA_HOME.'/mba/resources/mba-alumni-data','cookieDetails'=>array('name'=>'seo_data','value'=>'Companies_HDFC Bank_0')),
        array('name'=>'EEMore &raquo','url'=>SHIKSHA_HOME.'/mba/resources/mba-alumni-data')
    )
        )
);
$config['reviewRatingWidget']=array(
    '1'=>array(
        'heading' => "MBA College Reviews and Ratings",
        'subHeading'=>"By Alumni and Current Students",  
        'viewAllUrl'=>"mba/resources/college-reviews",
    ),
    '6'=>array(
        'heading' => "Engineering College Reviews and Ratings",
        'subHeading'=>"By Alumni and Current Students",  
        'viewAllUrl'=>"engineering-colleges-reviews-cr",
    )
);
$config['campusConnectWidget'] = array(
    '1'=>array(
        'heading'=>"Connect with Current MBA Students",
        'subHeading'=>"6,000+ questions answered by 400+ students",
        'explore'=>array(
            '1'=>array(
                'caption'=>"Questions for Top Ranking MBA Colleges",
                'url' => "mba/resources/ask-current-mba-students#topQuestionContainer",
            ),
            '2'=>array(
                'caption'=>"Most Viewed Questions",
                'url' => "mba/resources/ask-current-mba-students#mostViewedContainer",
            ),
            'viewAllUrl'=>"mba/resources/ask-current-mba-students"
        )
    ),
    '6'=>array(
        'heading'=>"Connect with Current Engineering Students",
        'subHeading'=>"6,000+ questions answered by 400+ students",
        'explore'=>array(
            '1'=>array(
                'caption'=>"Questions for Top Ranking Engineering Colleges",
                'url' => "engineering/resources/ask-current-engineering-students#topQuestionContainer",
            ),
            '2'=>array(
                'caption'=>"Most Viewed Questions",
                'url' => "engineering/resources/ask-current-engineering-students#mostViewedContainer",
            ),
            'viewAllUrl'=>"mba/resources/ask-current-engineering-students"
        )
    )
);
$config['registrationBaseCourseRemapping'] = array(
        '130' => array(
                        'cr' => 10,
                        'cl' => 13
                    ),
        '131' => array(
                        'cr' => 9,
                        'cl' => 13
                    ),
        '132' => array(
                        'cr' => 10,
                        'cl' => 14
                    ),
        '133' => array(
                        'cr' => 9,
                        'cl' => 14
                    ),
        '134' => array(
                        'cr' => 10,
                        'cl' => 15
                    ),
        '135' => array(
                        'cr' => 9,
                        'cl' => 15
                    ),
        '136' => array(
                        'cr' => 10,
                        'cl' => 16
                    ),
        '137' => array(
                        'cr' => 9,
                        'cl' => 16
                    ),
        '138' => array(
                        'cr' => 10,
                        'cl' => 17
                    ),
        '139' => array(
                        'cr' => 9,
                        'cl' => 17
                    ),
        '140' => array(
                        'cr' => 10,
                        'cl' => 18
                    ),
        '141' => array(
                        'cr' => 19,
                        'cl' => 18
                    ),
        '142' => array(
                        'cr' => 11,
                    ),
    );
$config['301ChpMappings'] = array('1'=>'/mba-pgdm-chp',
                          '2'=>'/online-mba-pgdm-chp',
                          '3'=>'/distance-mba-pgdm-chp',
                          '4'=>'/part-time-mba-pgdm-chp',
                          '5'=>'/executive-mba-pgdm-chp',
                          '6'=>'/engineering-chp',
                          '7'=>'/design/fashion-design-chp',
                          '8'=>'/b-sc-chp',
                          '9'=>'/bba-chp',
                          '10'=>'/hospitality-travel/hotel-hospitality-management-chp',
                          '11'=>'/mass-communication-media-chp',
                          '12'=>'/mbbs-chp',
                          '13'=>'/mca-chp',
                          '14'=>'/bca-chp',
                          '15'=>'/medicine-health-sciences/pharmacy-chp',
                          '16'=>'/m-sc-chp'
                         );
