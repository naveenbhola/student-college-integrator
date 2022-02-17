<?php
$resourceDirectoryName = 'resources';
global $COURSE_PAGES_SUB_CAT_ARRAY;
$COURSE_PAGES_SUB_CAT_ARRAY =
        array(
            23  => array("Name" => 'MBA', 'UrlKey' => 'mba', 'tagId' => 422),
            24  => array("Name" => 'Distance MBA', 'UrlKey' => 'distance-mba', 'tagId' => 638),
            25  => array("Name" => 'Executive MBA', 'UrlKey' => 'executive-mba', 'tagId' => 640),
            26  => array("Name" => 'Part-time MBA', 'UrlKey' => 'part-time-mba', 'tagId' => 637),
            27  => array("Name" => 'Online MBA', 'UrlKey' => 'online-mba', 'tagId' => 419505),
            56  => array("Name" => 'Engineering', 'UrlKey' => 'engineering', 'tagId' => 20),
            69  => array("Name" => 'Fashion & Textile Design', 'UrlKey' => 'fashion-textile-design', 'tagId' => 65),
            98  => array("Name" => 'MCA', 'UrlKey' => 'mca','newUrl'=> 1, 'tagId' => 430),
            28  => array("Name" => 'BBA', 'UrlKey' => 'bba','newUrl'=> 1, 'tagId' => 421),
            64  => array("Name" => 'BSC', 'UrlKey' => 'bsc','newUrl'=> 1, 'tagId' => 407),
            100 => array("Name" => 'BCA', 'UrlKey' => 'bca','newUrl'=> 1, 'tagId' => 429),
            84  => array("Name" => 'Hotel Management', 'UrlKey' => 'hotel-management','newUrl'=> 1, 'tagId' => 22),
            139 => array("Name" => 'Pharmacy', 'UrlKey' => 'pharmacy','newUrl'=> 1, 'tagId' => 416687),
            65  => array("Name" => 'MSC', 'UrlKey' => 'msc','newUrl'=> 1, 'tagId' => 408),
            18  => array("Name" => 'Mass Communication', 'UrlKey' => 'mass-communication','newUrl'=> 1, 'tagId' => -1),
            133 => array("Name" => 'MBBS', 'UrlKey' => 'mbbs','newUrl'=> 1, 'tagId' => 401),
        );

global $Question_DISCUSSION_COUNT_ARRAY;
$Question_DISCUSSION_COUNT_ARRAY    =   array   (   'countOfQuestionsToShow'            =>  5,
                                                    'countOfQuestionsToStoreInCache'    =>  10,
                                                    'countOfDiscussionsToShow'          =>  5,
                                                    'countOfDiscussionsToStoreInCache'  => 10
                                                );

global $ARTICLES_CATEGORY_NAME_ARRAY;
$ARTICLES_CATEGORY_NAME_ARRAY =
        array(
            7 => array("Name" => 'Media, Films and Mass Communication','ShortName'=>'Media & Mass Communication', 'UrlKey' => 'media-mass-comm'),
            6 => array("Name" => 'Hotel Management, Tourism and Aviation','ShortName'=>'hospitality & Aviation', 'UrlKey' => 'hospitality'),
            13 => array("Name" => 'Design', 'ShortName'=>'Design','UrlKey' => 'design'),
            12 => array("Name" => 'Animation, VFX, Gaming and Comics', 'ShortName'=>'Animation','UrlKey' => 'animation-vfx-gaming-comics'),
            9 => array("Name" => 'Law', 'ShortName'=>'Arts, Law & Teaching','UrlKey' => 'law'),
            3 => array("Name" => 'BBA and Management Certifications', 'ShortName'=>'Management','UrlKey' => 'bba-certifications'),
            2 => array("Name" => 'Architecture and PhD.', 'ShortName'=>'Engineering','UrlKey' => 'science-engineering')
        );
        
global $ARTICLES_CAT_SUBCAT_NAME_ARRAY;
$ARTICLES_CAT_SUBCAT_NAME_ARRAY =
        array(
            2 => array('Architecture'=>60,'Diploma'=>55, 'M.E./M.Tech'=>59 ,'Phd'=>68),
            3 => array('Certifications'=>31,'BBA/BBM/BBS'=>28)
        );

        
global $COURSE_PAGES_EXCLUSION_IDS;        
$COURSE_PAGES_EXCLUSION_IDS  = 
		array(
			'QNA'=>array(2821564),
		        'DISCUSSIONS'=>array(2761808, 2741445, 2757135, 2690800, 2509595, 2766010, 2312868, 2498918, 2774001, 2761134, 2715807, 2638153, 2694419, 2652392,2781625,2433703,2499984,2590727,2535867,2769164, 2654371,2633625, 2455551, 2318019,2802901, 2809712, 2798998,2810563, 2644836,2818467,2812874, 2819089, 2813857,2796193,2819387,2821125,2845559,2864530,2848827,2861869,2894095,2879959,2909188,2901275,2909594,2896095,2875485,2837331,2866650),
		         'ARTICLES'=>array(7068, 7070, 7053, 7084, 7090, 7079,7337,2875485,2837331,2866650,7773)
		);    

global $coursePagesSEODataIndia;
$coursePagesSEODataIndia = array(
    'Home' => array(
                'url'       => '{subcategory}-coursepage',
                'newSeoUrl' => '{subcategory}-home',
                1 => array( 
                'title' => '{alias} in India, see {alias} colleges and courses',
                'description' => '{alias} in India. View details of {alias} colleges, courses, fees, admissions, exams and much more only on Shiksha.com',
                'keywords' => 'MBA, MBA in India, MBA Courses, MBA Colleges in india, MBA Colleges, MBA Admission, MBA Career, MBA Discussion, MBA Important Dates, MBA Education'
                ),
                2 => array( 
                'title' => '{alias} in India, see {alias} colleges and courses',
                'description' => '{alias} in India. View details of {alias} colleges, courses, fees, admissions, exams and much more only on Shiksha.com',
                'keywords' => 'Online MBA, Online MBA in India, Online MBA Courses, Online MBA Colleges, Online MBA Admission, Online MBA Career, Online MBA Discussion, Online MBA Important Dates, Online MBA Education'
                ),
                3 => array( 
                'title' => '{alias} in India, see {alias} colleges and courses',
                'description' => '{alias} in India. View details of {alias} colleges, courses, fees, admissions, exams and much more only on Shiksha.com',
                'keywords' => 'Distance MBA, Distance MBA in India, Distance MBA Courses, Distance MBA Colleges, Distance MBA Admission, Distance MBA Career, Distance MBA Discussion, Distance MBA Important Dates, Distance MBA Education'
                ),
                4 => array( 
                'title' => '{alias} in India, see {alias} colleges and courses',
                'description' => '{alias} in India. View details of {alias} colleges, courses, fees, admissions, exams and much more only on Shiksha.com',
                'keywords' => 'Part Time MBA, Part Time MBA in India, Part Time MBA Courses, Part Time MBA Colleges, Part Time MBA Admission, Part Time MBA Career, Part Time MBA Discussion, Part Time MBA Important Dates, Part Time MBA Education'
                ),
                5 => array( 
                'title' => '{alias} in India, see {alias} colleges and courses',
                'description' => '{alias} in India. View details of {alias} colleges, courses, fees, admissions, exams and much more only on Shiksha.com',
                'keywords' => 'Executive MBA, Executive MBA in India, Executive MBA Courses, Executive MBA Colleges, Executive MBA Admission, Executive MBA Career, Executive MBA Discussion, Executive MBA Important Dates, Executive MBA Education'
                ),
                6 => array( 
                'title' => '{alias} in India, see {alias} colleges and courses',
                'description' => '{alias} in India. View details of {alias} colleges, courses, fees, admissions, exams and much more only on Shiksha.com',
                'keywords' => 'Engineering, Engineering in India, Engineering Courses, Engineering Colleges, Engineering Admission, Engineering Career, Engineering Discussion, Engineering Important Dates, b tech courses, m tech courses'
                ),
                7 => array( 
                'title' => '{alias} in India, see {alias} colleges and courses',
                'description' => '{alias} in India. View details of {alias} colleges, courses, fees, admissions, exams and much more only on Shiksha.com',
                'keywords' => 'Fashion Design Courses, Fashion Design Colleges,  List of Fashion and Textile Design Colleges, Fashion and Textile Design Career, Fashion and Textile Design Important Dates, Fashion and Textile Design Education, Fashion and Textile Design, Fashion and Textile Design in India'
                ),
                8 => array( 
                'title' => '{alias} in India, see {alias} colleges and courses',
                'description' => '{alias} in India. View details of {alias} colleges, courses, fees, admissions, exams and much more only on Shiksha.com',
                ),
                9 => array( 
                'title' => '{alias} in India, see {alias} colleges and courses',
                'description' => '{alias} in India. View details of {alias} colleges, courses, fees, admissions, exams and much more only on Shiksha.com',
                ),
                10 => array( 
                'title' => '{alias} in India, see {alias} colleges and courses',
                'description' => '{alias} in India. View details of {alias} colleges, courses, fees, admissions, exams and much more only on Shiksha.com',
                'keywords' => 'hotel management, hotel management in india, hotel management courses, hotel management colleges in India, hotel management colleges, hotel management admission, hotel management career, hotel management discussion, hotel management important dates, hotel management education'
                ),
                11 => array( 
                'title' => '{alias} in India, see {alias} colleges and courses',
                'description' => '{alias} in India. View details of {alias} colleges, courses, fees, admissions, exams and much more only on Shiksha.com',
                ),
                12 => array( 
                'title' => '{alias} in India, see {alias} colleges and courses',
                'description' => '{alias} in India. View details of {alias} colleges, courses, fees, admissions, exams and much more only on Shiksha.com',
                ),
                13 => array( 
                'title' => '{alias} in India, see {alias} colleges and courses',
                'description' => '{alias} in India. View details of {alias} colleges, courses, fees, admissions, exams and much more only on Shiksha.com',
                ),
                14 => array( 
                'title' => '{alias} in India, see {alias} colleges and courses',
                'description' => '{alias} in India. View details of {alias} colleges, courses, fees, admissions, exams and much more only on Shiksha.com',
                ),
                15 => array( 
                'title' => '{alias} in India, see {alias} colleges and courses',
                'description' => '{alias} in India. View details of {alias} colleges, courses, fees, admissions, exams and much more only on Shiksha.com',
                ),
                16 => array( 
                'title' => '{alias} in India, see {alias} colleges and courses',
                'description' => '{alias} in India. View details of {alias} colleges, courses, fees, admissions, exams and much more only on Shiksha.com',
                )

    ),
    'AskExperts' => array(
        'url'       => '{subcategory}-questions-PAGE-coursepage',
		'newSeoUrl' => $resourceDirectoryName."/{subcategory}-questions/PAGE",
		23 => array( 
                'title' => 'Ask MBA Related Questions to our MBA Career Experts - Shiksha.com',
                'description' => 'Ask your questions on MBA education, courses, institutes, admission, entrance exams, colleges, and other career related doubts in India or abroad',
                'keywords' => 'MBA Career Experts, MBA, MBA in India, MBA Courses, MBA Colleges, MBA Admission, MBA Career, Ask the Career Experts'
                ),
                25 => array( 
                'title' => 'Ask Executive MBA Related Questions to our Executive MBA Career Experts - Shiksha.com',
                'description' => 'Ask your questions on Executive MBA education, courses, institutes, admission, entrance exams, colleges, and other Executive MBA career related doubts in India or abroad',
                'keywords' => 'Executive MBA Career Experts, Executive MBA, Executive MBA in India, Executive MBA Courses, Executive MBA Colleges, Executive MBA Admission, Executive MBA Career, Ask the Career Experts'
                ),
                27 => array( 
                'title' => 'Ask Online MBA Related Questions to our Online MBA Career Experts - Shiksha.com',
                'description' => 'Ask your questions on Online MBA education, courses, institutes, admission, entrance exams, colleges, and other Online MBA career related doubts in India or abroad',
                'keywords' => 'Online MBA Career Experts, Online MBA, Online MBA in India, Online MBA Courses, Online MBA Colleges, Online MBA Admission, Online MBA Career, Ask the Career Experts'
                ),
                26 => array( 
                'title' => 'Ask Part Time MBA Related Questions to our Part Time MBA Career Experts - Shiksha.com',
                'description' => 'Ask your questions on Part Time MBA education, courses, institutes, admission, entrance exams, colleges, and other Part Time MBA career related doubts in India or abroad',
                'keywords' => 'Part Time MBA Career Experts, Part Time MBA, Part Time MBA in India, Part Time MBA Courses, Part Time MBA Colleges, Part Time MBA Admission, Part Time MBA Career, Ask the Career Experts'
                ),
                24 => array( 
                'title' => 'Ask Distance MBA Related Questions to our Distance MBA Career Experts - Shiksha.com',
                'description' => 'Ask your questions about Distance MBA education, courses, institutes, admission, entrance exams, colleges, and other Distance MBA career related doubts in India or abroad',
                'keywords' => 'Distance MBA Career Experts, Distance MBA, Distance MBA in India, Distance MBA Courses, Distance MBA Colleges, Distance MBA Admission, Distance MBA Career, Ask the Career Experts'
                ),
                56 => array( 
                'title' => 'Ask B.Tech / BE Courses Related Questions to our Engineering Career Experts - Shiksha.com',
                'description' => 'Ask your questions on Engineering education, B.Tech / BE courses, institutes, admission, engineering entrance exams, colleges, and  career related doubts in India or abroad',
                'keywords' => 'engineering career experts, engineering, engineering in india, engineering courses, ask about engineering colleges, engineering admission, engineering career, ask the career experts'
                ),
                84 => array( 
                'title' => 'Ask Hotel Management Related Questions to our Hotel Management Career Experts - Shiksha.com',
                'description' => 'Ask your questions on hotel management courses, institutes, admission, entrance exams, colleges, and other hotel management career related doubts in India or abroad',
                'keywords' => 'Hotel Management Career Experts, ask about career in Hotel Management, Hotel Management in India, Hotel Management Courses, questions on Hotel Management Colleges, Hotel Management Admission, Hotel Management Career, Ask the Career Experts'
                ),
                69 => array( 
                'title' => 'Ask Fashion and Textile Designing Related Questions to our Fashion and Textile Designing Career Experts - Shiksha.com',
                'description' => 'Ask your questions about Fashion and Textile Designing courses, institutes, admission, entrance exams, colleges, and other designing career related doubts in India or abroad',
                'keywords' => 'Fashion and Textile Design Career Experts, ask about Fashion and Textile Design, career in Fashion and Textile Design in India, Fashion and Textile Design Courses, Fashion and Textile Design Career, Ask the Career Experts fashion designing, fashion designing courses'
                )        
    ),
    'Discussions' => array(
                'url'       => '{subcategory}-discussions-PAGE-coursepage',       
                'newSeoUrl' => $resourceDirectoryName.'/{subcategory}-discussions/PAGE',		
                23 => array( 
                'title' => 'MBA Discussion Forum - Ask the Career Experts - Shiksha.com',
                'description' => 'Discuss your doubts and ideas about MBA education with our career experts at Shiksha.com for a successful career in MBA',
                'keywords' => 'MBA Education and Career Discussion Forum, MBA Discussion Forum, MBA Career Discussion Forum, Ask the Career Experts, MBA, MBA in India, MBA Courses, MBA Colleges, MBA Admission, MBA Career, MBA Discussion'
                ),
                25 => array( 
                'title' => 'Executive MBA Discussion Forum - Ask the Career Experts at Shiksha.com',
                'description' => 'Discuss your doubts and ideas about Executive MBA education with our career experts for a successful career in Executive MBA',
                'keywords' => 'Executive MBA Education and Career Discussion Forum, Executive MBA Discussion Forum, Executive MBA Career Discussion Forum, Ask the Career Experts, Executive MBA, Executive MBA in India, ask Executive MBA Admission, Executive MBA Career, Executive MBA Discussion'
                ),
                27 => array( 
                'title' => 'Online MBA Discussion Forum - Ask the Career Experts at Shiksha.com',
                'description' => 'Discuss your doubts and ideas about Online MBA education with our career experts for a successful career in Online MBA',
                'keywords' => 'Online MBA Education and Career Discussion Forum, Online MBA Discussion Forum, Online MBA Career Discussion Forum, Ask the Career Experts, Online MBA, Online MBA in India, Online MBA Courses, Online MBA Colleges, Online MBA Admission, Online MBA Career, Online MBA Discussion'
                ),
                26 => array( 
                'title' => 'Part Time MBA Discussion Forum - Ask the Career Experts at Shiksha.com',
                'description' => 'Discuss your doubts and ideas about Part Time MBA education with our career experts for a successful career in Part Time MBA',
                'keywords' => 'Part Time MBA Education and Career Discussion Forum, Part Time MBA Discussion Forum, Part Time MBA Career Discussion Forum, Part Time MBA, Part Time MBA in India, Part Time MBA Discussion'
                ),
                24 => array( 
                'title' => 'Distance MBA Discussion Forum - Ask the Career Experts at Shiksha.com',
                'description' => 'Discuss your doubts and ideas about Distance MBA education with our career experts for a successful career in Distance Learning MBA',
                'keywords' => 'Distance MBA Education and Career Discussion Forum, Distance MBA Discussion Forum, Distance MBA Career Discussion Forum, Ask the Career Experts, Distance MBA, Distance MBA in India, Distance MBA Courses, Distance MBA Colleges, Distance MBA Admission, Distance MBA Career, Distance MBA Discussion'
                ),
                56 => array( 
                'title' => 'Engineering - B.Tech - BE Discussion Forum - Ask the Career Experts - Shiksha.com',
                'description' => 'Discuss your doubts and ideas about Engineering education with our career experts at Shiksha.com for a successful career in Engineering',
                'keywords' => 'engineering education and career discussion forum, engineering discussion forum, engineering career discussion forum, ask the career experts, engineering, engineering in india, engineering courses, engineering admission, engineering career, engineering discussion'
                ),
                84 => array( 
                'title' => 'Hotel Management Discussion Forum - Ask the Career Experts - Shiksha.com',
                'description' => 'Discuss your doubts and ideas about hotel management education with our career experts at Shiksha.com for a successful career in hotel management',
                'keywords' => 'Hotel Management Education and Career Discussion Forum, Hotel Management Discussion Forum, Hotel Management Career Discussion Forum, Ask the Career Experts, Hotel Management,'
                ),
                69 => array( 
                'title' => 'Fashion and Textile Designing Discussion Forum - Ask the Career Experts - Shiksha.com',
                'description' => 'Discuss your doubts and ideas about Fashion and Textile Designing education with our career experts at Shiksha.com for a successful career in designing',
                'keywords' => 'Fashion and Textile Design Education and Career Discussion Forum, Fashion and Textile Design Discussion Forum, Fashion and Textile Design Career Discussion Forum'
                )        
    ),
    'News' => array(
        'url'       => '{subcategory}-news-articles-PAGE-coursepage',
		'newSeoUrl' => $resourceDirectoryName.'/{subcategory}-news-articles/PAGE',
                23 => array( 
                'title' => 'Latest MBA News on MBA Exams, Results, Notification, Important Dates, Admission, Courses, and Colleges - Shiksha.com',
                'description' => 'Read daily and latest MBA education news articles about MBA Entrance Exams, Results, Notification, Admission, Important Dates, Courses, and Colleges at Shiksha.com',
                'keywords' => 'Latest MBA Education News on MBA Exams, MBA Education News, News on MBA Exams, MBA notification, MBA important dates'
                ),
                25 => array( 
                'title' => 'Executive MBA Education News on Exams, Results, Admission, Notification, and Important Dates - Shiksha.com',
                'description' => 'Read daily and latest Executive MBA education news articles on Executive MBA Entrance Exams, Results, Admission Notification, Important Dates, Courses, and Colleges at Shiksha.com',
                'keywords' => 'Latest Executive MBA Education News, Executive MBA Exams, Executive MBA Education News, News on Executive MBA, admission notification for executive MBA'
                ),
                27 => array( 
                'title' => 'Online MBA Education News on Online MBA Exams, Notification, Admission, Results, Admission, Courses, and Colleges - Shiksha.com',
                'description' => 'Read daily and latest Online MBA news articles on Entrance Exams, Important Dates, Admission Notification, Results, Courses, and Colleges at Shiksha.com',
                'keywords' => 'Latest Online MBA Education News on Online MBA Exams, Online MBA Education News, News on Online MBA, Daily Online Education Exam News'
                ),
                26 => array( 
                'title' => 'Part Time MBA Education News on Part Time MBA Exams, Results, Important Dates, Notification, Admission, Courses, and Colleges - Shiksha.com',
                'description' => 'Read daily and latest Part Time MBA education news articles about Part Time MBA Entrance Exams, Results, Important Dates, Admission Notification, Courses, and Colleges at Shiksha.com',
                'keywords' => 'Latest Education News on Part Time MBA Exams, Part Time MBA Education News, News on Part Time MBA Exams, Part time MBA news, Part time MBA admission notification'
                ),
                24 => array( 
                'title' => 'Latest Distance MBA Education News on Distance MBA Exams, Results, Admission, Courses, and Colleges - Shiksha.com',
                'description' => 'Read daily and latest Distance MBA education news articles about Distance MBA Entrance Exams, Results, Admission, Courses, and Colleges at Shiksha.com',
                'keywords' => 'Latest Distance MBA Education News on Distance MBA Exams, Distance MBA Education News, News on Distance MBA Exams, latest distance learning MBA'
                ),
                56 => array( 
                'title' => 'Latest Engineering Education News on Engineering Exams, Results, Important Dates, Admission Notification, Courses, and Colleges - Shiksha.com',
                'description' => 'Read daily and latest news articles on Engineering Entrance Exams, Results, Admission Notification, Important Dates, Courses, and Colleges at Shiksha.com',
                'keywords' => 'latest engineering education news on engineering exams, engineering education news, news on engineering exams, B.Tech  course news, lates news on B.Tech education'
                ),
                84 => array( 
                'title' => 'Latest Hotel Management Education News on Exams, Results, Admission Notification, Important Dates, Courses, and Colleges - Shiksha.com',
                'description' => 'Read daily and latest hotel management education news articles on Entrance Exams, Results, Admission Notification, Courses, Important Dates, and Colleges, at Shiksha.com',
                'keywords' => 'Latest Hotel Management Education News on Hotel Management Exams, Hotel Management Education News, News on Hotel Management Exams'
                ),
                69 => array( 
                'title' => 'Latest Fashion and Textile Designing Education News on Exams, Results, Admission Notification, Important Dates, Courses, and Colleges - Shiksha.com',
                'description' => 'Read daily and latest Fashion and Textile Designing education news articles about Entrance Exams, Results, Important Dates, Admission Notification, Courses, and Colleges at Shiksha.com',
                'keywords' => 'Latest Fashion and Textile Design Education News,  fashion designing Exams, Fashion designing admissions, notification fashion designing admission, News on Fashion and Textile Design Exams, Fashion & Textile Design career news'
                )        
    ),
    
    'Faq' => array(
               'pattern'=>array(
                'title' => '{alias} - Frequently Asked Questions | Shiksha.com',
                'description' => 'Get a list of frequently asked questions related to {alias} admissions, courses, colleges and entrance exams in India',
                'keywords' => '{alias} FAQ, {alias} Frequently Asked Questions, Frequently Asked Questions about {alias}, ask questions, {alias} questions and answers'
                ),
		1 => array(
                'title' => '{alias} - Frequently Asked Questions | Shiksha.com',
                'description' => 'Get a list of frequently asked questions related to {alias} admissions, courses, colleges and entrance exams in India',
                'keywords' => 'MBA FAQ, MBA Frequently Asked Questions, Frequently Asked Questions about MBA, ask questions, MBA questions and answers'
                ),
                5 => array(
                'title' => '{alias} - Frequently Asked Questions | Shiksha.com',
                'description' => 'Get a list of frequently asked questions related to {alias} admissions, courses, colleges and entrance exams in India',
                'keywords' => 'Executive MBA FAQ, Executive MBA Frequently Asked Questions, Frequently Asked Questions about Executive MBA, ask questions, Executive MBA questions and answers'
                ),
                2 => array(
                'title' => '{alias} - Frequently Asked Questions | Shiksha.com',
                'description' => 'Get a list of frequently asked questions related to {alias} admissions, courses, colleges and entrance exams in India',
                'keywords' => 'Online MBA FAQ, Online MBA Frequently Asked Questions, Frequently Asked Questions about Online MBA, ask questions, Online MBA questions and answers'
                ),
                4 => array(
                'title' => '{alias} - Frequently Asked Questions | Shiksha.com',
                'description' => 'Get a list of frequently asked questions related to {alias} admissions, courses, colleges and entrance exams in India',
                'keywords' => 'Part Time MBA FAQ, Part Time MBA Frequently Asked Questions, Frequently Asked Questions about Part Time MBA, ask questions, Part Time MBA questions and answers'
                ),
                3 => array(
                'title' => '{alias} - Frequently Asked Questions | Shiksha.com',
                'description' => 'Get a list of frequently asked questions related to {alias} admissions, courses, colleges and entrance exams in India',
                'keywords' => 'Distance MBA FAQ, Distance MBA Frequently Asked Questions, Frequently Asked Questions about Distance MBA, ask questions, Distance MBA questions and answers'
                ),
                6 => array(
                'title' => '{alias} - Frequently Asked Questions | Shiksha.com',
                'description' => 'Get a list of frequently asked questions related to {alias} admissions, courses, colleges and entrance exams in India',
                'keywords' => 'Engineering FAQ, Engineering Frequently Asked Questions, Frequently Asked Questions about Engineering, ask questions about b tech mtech courses, Engineering questions and answers'
                ),
                84 => array(
                'title' => '{alias} - Frequently Asked Questions | Shiksha.com',
                'description' => 'Get a list of frequently asked questions related to {alias} admissions, courses, colleges and entrance exams in India',
                'keywords' => 'Hotel Management FAQ, Hotel Management Frequently Asked Questions, Frequently Asked Questions about Hotel Management, ask questions, Hotel Management questions and answers'
                ),
                7 => array( 
                'title' => '{alias} - Frequently Asked Questions | Shiksha.com',
                'description' => 'Get a list of frequently asked questions related to {alias} admissions, courses, colleges and entrance exams in India',
                'keywords' => 'Fashion and Textile Design FAQ, Fashion and Textile Design Frequently Asked Questions, Frequently Asked Questions about Fashion and Textile Design, ask questions, Fashion and Textile Design questions and answers'
                )
    ),
    
    'questionDetail' => array(
                'url' => '{subcategory}-faq-coursepage/{question_title}',
                'title' => '{question_title}',
                'description' => '{question_description}',
                'keywords' => ''
                )
);
global $courseHomeResources;
$courseHomeResources=array(
    '1'=>array(
        'url'=>'/mba/resources'
    )
);
