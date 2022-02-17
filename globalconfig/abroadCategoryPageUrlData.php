<?php
$courseLevelAndMainCategoryPages =
array(
		'GENERAL' => array(
				'url' => '{level}-of-{category}-in-{location}-{pageIdentifier}',
				'countryCatPageUrl' => '{location}/{level}-of-{category}-colleges-{pageIdentifier}',
				'title' => '{level} of {category} in {location} - Universities, Colleges, Fees & Eligibility',
				 
				'description' => 'Compare {universityCount} {level} of {category} Universities & Colleges in {location}. Check fees, eligibility, scholarships and accommodation details to study {level} of {category} in {location} at Shiksha.com.',
				'keywords' => ''
		),
		'COURSE_LEVEL' => array(
				'phd' => array(
						'url' => '{level}-in-{category}-in-{location}-{pageIdentifier}',
						'countryCatPageUrl' => '{location}/{level}-in-{category}-colleges-{pageIdentifier}',
						'title' => '{level} in {category} in {location} - Universities, Colleges, Fees & Eligibility',
						
						'description' => 'Compare {universityCount} {level} of {category}  Universities & Colleges in {location}. Check fees, eligibility, scholarships and accommodation details to study {level} of {category} in {location} at Shiksha.com.',
						'keywords' => ''
					),
                'Certificate - Diploma' => array(
						'url' => '{level}-in-{category}-in-{location}-{pageIdentifier}',
						'countryCatPageUrl' => '{location}/{level}-in-{category}-colleges-{pageIdentifier}',
						'title' => 'Diploma of {category} in {location} - Universities, Colleges, Fees & Eligibility',
						'description' => 'Compare {universityCount} Diploma of {category} Universities & Colleges in {location}. Check fees, eligibility, scholarships and accommodation details to study Diploma of {category} in {location} at Shiksha.com.',                        
						'keywords' => ''
					)
				)
);

$ldbCourseCategoryPages =
array(
		'GENERAL' => array(
				'url' => '{ldbCourse}-in-{location}-{pageIdentifier}',
				'countryCatPageUrl' => '{location}/{ldbCourse}-colleges-{pageIdentifier}',
				'title' => '{ldbCourse} in {location} - Universities, Colleges, Fees & Eligibility',
				'description' => 'Compare  {universityCount} {ldbCourse} Universities & Colleges in {location}. Check fees, eligibility, scholarships and accommodation details to study {ldbCourse} in {location}  at Shiksha.com.',
				'keywords' => ''
		),
                 
                'LDBCOURSE' =>array(
                        /* For Bachelor of Engineering pages redirected to BE-BTECH with LDB courseID 1510 */
                        'countryPage_1510' => array(
                                        'url' => '{ldbCourse}-in-{location}-{pageIdentifier}',
                                        'countryCatPageUrl' => '{location}/{ldbCourse}-in-{location}-{pageIdentifier}',
                                        'title' => 'Engineering in {location} - Universities, Colleges, Fees & Eligibility',
                                        'description' => 'Compare {universityCount} engineering Universities & Colleges in {location}. Check fees, eligibility, scholarships and accommodation details to study engineering in {location}  at Shiksha.com.',
                                        'keywords' => ''
                                      ),
                        'countryPage_1508' => array(
                                        'url' => '{ldbCourse}-in-{location}-{pageIdentifier}',
                                        'countryCatPageUrl' => '{location}/{ldbCourse}-colleges-{pageIdentifier}',
                                        'title' => 'MBA in {location} - Universities, Colleges, Fees & Eligibility',
                                        'description' => 'Compare {universityCount} MBA Universities & Colleges in {location}. Check fees, eligibility, scholarships and accommodation details to study MBA in {location}  at Shiksha.com.',
                                        'keywords' => ''
                                      ),
                        'abroadPage_1510' => array(
                                        'url' => '{ldbCourse}-in-{location}-{pageIdentifier}',
                                        'countryCatPageUrl' => '{location}/{ldbCourse}-colleges-{pageIdentifier}',
                                        'title' => 'Engineering in {location} (bachelors) - Universities, Colleges, Fees & Eligibility',
                                        'description' => 'Compare {universityCount} bachelor of engineering Universities & Colleges in {location}. Check fees, eligibility, scholarships and accommodation details to study bachelor of engineering in {location}  at Shiksha.com.', 
                                        'keywords' => ''
                                      ),
                        'abroadPage_1508' => array(
                                        'url' => '{ldbCourse}-in-{location}-{pageIdentifier}',
                                        'countryCatPageUrl' => '{location}/{ldbCourse}-colleges-{pageIdentifier}',
                                        'title' => 'MBA in {location} - Universities, Colleges, Fees & Eligibility',
                                        'description' => 'Compare {universityCount} MBA Universities & Colleges in {location}. Check fees, eligibility, scholarships and accommodation details to study MBA in {location} at Shiksha.com.', 
                                        'keywords' => ''
                                      )
                )
);

$ldbCourseAndSubCategoryPages =
array(
		'GENERAL' => array(
				'url' => '{ldbCourse}-in-{subcategory}-from-{location}-{pageIdentifier}',
				'countryCatPageUrl' => '{location}/{ldbCourse}-in-{subcategory}-colleges-{pageIdentifier}',
				'title' => '{ldbCourse} in {subcategory} in {location} - Universities, Colleges, Fees & Eligibility',
				'description' => 'Compare {universityCount} {ldbCourse} in {subcategory} Universities & Colleges in {location}. Check fees, eligibility, scholarships and accommodation details to study {ldbCourse} in {subcategory} in {location} at Shiksha.com.',
				'keywords' => ''
		),
		'CATEGORY' => array(
				'240' => array(
						'url' => '{ldbCourse}-in-{subcategory}-engineering-from-{location}-{pageIdentifier}',
						'countryCatPageUrl' => '{location}/{ldbCourse}-in-{subcategory}-colleges-{pageIdentifier}',
						'title' => '{ldbCourse} in {subcategory} Engineering in {location} - Universities, Colleges, Fees & Eligibility',
						'description' => 'Compare {universityCount} {ldbCourse} in {subcategory} Engineering Universities & Colleges in {location}. Check fees, eligibility, scholarships and accommodation details to study {ldbCourse} in {subcategory} in {location} at Shiksha.com.',
						'keywords' => ''
					)
				),
		'SUBCATEGORY' => array(
				'274' => array(
						'url' => '{ldbCourse}-in-{subcategory}-from-{location}-{pageIdentifier}',
						'countryCatPageUrl' => '{location}/{ldbCourse}-in-{subcategory}-colleges-{pageIdentifier}',
						'title' => '{ldbCourse} in {subcategory} in {location} - Universities, Colleges, Fees & Eligibility',
						'description' => 'Compare {universityCount} {ldbCourse} in {subcategory} Universities & Colleges in {location}. Check fees, eligibility, scholarships and accommodation details to study {ldbCourse} in {subcategory} in {location} at Shiksha.com.',
						'keywords' => ''
					)
				),
                 'LDBCOURSE' => array(
                                     /*MS Subcategories for Masters in Science Engineering and Computers*/
                                    DESIRED_COURSE_MS => array(
                                                    'url' => '{ldbCourse}-in-{subcategory}-from-{location}-{pageIdentifier}',
                                                    'countryCatPageUrl' => '{location}/{ldbCourse}-in-{subcategory}-colleges-{pageIdentifier}',
                                                    'title' => '{ldbCourse} in {subcategory}  in {location} - Universities, Colleges, Fees & Eligibility',
                                                    'description' => 'Compare {universityCount} {ldbCourse} in {subcategory} Universities & Colleges in {location}. Check fees, eligibility, scholarships and accommodation details to study {ldbCourse} in {subcategory} in {location} at Shiksha.com.',
                                                    'keywords' => ''
                                                        ),
                                     /*BE BTECH Subcategories for Bachelor of Engineering and Computers*/
                                    DESIRED_COURSE_BTECH => array(
                                                    'url' => '{ldbCourse}-in-{subcategory}-from-{location}-{pageIdentifier}',
                                                    'countryCatPageUrl' => '{location}/{ldbCourse}-in-{subcategory}-colleges-{pageIdentifier}',
                                                    'title' => '{subcategory} in {location} -  Universities, Colleges, Fees & Eligibility',
                                                    'description' => 'Compare {universityCount} {subcategory}  Universities & Colleges in {location}. Check fees, eligibility, scholarships and accommodation details to study {subcategory} in {location} at Shiksha.com.',
                                                    'keywords' => ''
                                                    )                                    
                                    )
);


$courseLevelSubCategoryAndMainCategoryPages =
array(
		'GENERAL' => array(
				'url' => '{level}-in-{subcategory}-courses-in-{location}-{pageIdentifier}',
				'countryCatPageUrl' => '{location}/{level}-in-{subcategory}-courses-{pageIdentifier}',
				'title' => '{level} in {subcategory} in {location} - Universities, Colleges, Fees & Eligibility',
				'description' => 'Compare {universityCount} {level} in {subcategory}  Universities & Colleges in {location}. Check fees, eligibility, scholarships and accommodation details to study {level} in {subcategory} in {location} at Shiksha.com.',
				'keywords' => ''
		),
		'CATEGORY' => array(
				'240' => array(
						'url' => '{level}-in-{subcategory}-engineering-courses-in-{location}-{pageIdentifier}',
						'countryCatPageUrl' => '{location}/{level}-in-{subcategory}-courses-{pageIdentifier}',
						'title' => '{level} in {subcategory} Engineering in {location} - Universities, Colleges, Fees & Eligibility',
						'description' => 'Compare {universityCount} {level} in {subcategory} Engineering Universities & Colleges in {location}. Check fees, eligibility, scholarships and accommodation details to study {level} in {subcategory} Engineering in {location} at Shiksha.com.',
						'keywords' => ''
					)
				),
		'SUBCATEGORY' => array(
				'274' => array(
						'url' => '{level}-in-{subcategory}-courses-in-{location}-{pageIdentifier}',
						'countryCatPageUrl' => '{location}/{level}-in-{subcategory}-courses-{pageIdentifier}',
						'title' => '{level} in {subcategory} in {location} - Universities, Colleges, Fees & Eligibility',
						'description' => 'Compare {universityCount} {level} in {subcategory}  Universities & Colleges in {location}. Check fees, eligibility, scholarships and accommodation details to study {level} in {subcategory} in {location} at Shiksha.com.',
						'keywords' => ''
					)
				),
                'COURSE_LEVEL' => array(
                                    /*If its Computers category with boardID 241 of Bachelors*/
				'BACHELORS' => array(
                                                     /*BoardId of Computers Category is 241*/
                                                     '241' => array(
                                                    
                                                                    'url' => '{level}-in-{subcategory}-courses-in-{location}-{pageIdentifier}',
                                                                    'countryCatPageUrl' => '{location}/{level}-in-{subcategory}-courses-{pageIdentifier}',
                                                                    'title' => 'Bachelors of Computers in {subcategory} in {location} - Universities, Colleges, Fees & Eligibility',
                                                                    'description' => 'Compare {universityCount} {subcategory} Universities & Colleges in {location}. Check fees, eligibility, scholarships and accommodation details to study {subcategory} in {location} at Shiksha.com.',
                                                                    'keywords' => ''
                                                            )
                                                    )
                                        )
);



$countryPageUrlInfo =
array(
		'GENERAL' => array(
				'url' => 'universities-in-{location}-{pageIdentifier}',
				'countryPageUrl' => '{location}/universities',
				'title'=>'Top Universities in {location} - Fees, Ranking & Eligibility',
				'description' => ' top & best universities in {location} with their ranking, fees, scholarships, living cost, cutoffs, eligibility, exams, admission procedure and accommodation details.',				
				'keywords' => ''
		)
);

$categoryPageAcceptingExam =
    array(  'LDB_COURSE' =>
                            array(  'url' => '{ldbCourse}-in-{location}-accepting-{exam}-{pageIdentifier}',
                                    'title' => '{ldbCourse} Colleges in {location} Accepting {exam} Score - Study Abroad',
                                    'description' => 'Search for {ldbCourse} colleges in {location} accepting {exam} score. Get a list of colleges, institutes and universities in {location} that accept {exam} score for admission into {ldbCourse} program',
                                    'keywords' => ''
                                 ),
            'LDB_COURSE_SUBCAT' =>
                            array(  'url' => '{ldbCourse}-in-{subcategory}-from-{location}-accepting-{exam}-{pageIdentifier}',
                                    'title' => '',
                                    'description' => '',
                                    'keywords' => ''
                                 ),
            'CAT_SUBCAT_COURSELEVEL' =>
                            array(  'url' => '{level}-in-{subcategory}-courses-in-{location}-accepting-{exam}-{pageIdentifier}',
                                    'title' => '',
                                    'description' => '',
                                    'keywords' => ''
                                 ),
            'CAT_COURSELEVEL' =>
                            array(  'url' => '{level}-of-{category}-in-{location}-accepting-{exam}-{pageIdentifier}',
                                    'title' => '',
                                    'description' => '',
                                    'keywords' => ''
                                 ),
    );

$examAcceptedPagePattern = array(
					'withoutScore' =>"/{course}-colleges-accepting-{exam}-scores",
					'withScore' =>"/{course}-colleges-for-{exam}-score-{lower-limit}-to-{upper-limit}",
				);


/* Config Related Exams where
Field 'coursesApplicable'   :it define an array with index as course name and value as boolean for which
        Exam accepting Category Page(EACP) will be formed,
        where bool represent EACP with score range will be formed or not
Field 'ldbNameIdMap'        :it is used by College accepting widget on Exam Pages

Field 'courseMapForArticle' : it is used by Related Articles right widget on exam page
Filed 'countryMapForArticle' : it is used by Related Articles right widget on exam page

Either courseMapForArticle or countryMapForArticle will be used for a exam. if both defined then
courseMapForArticle will be used
*/
$listOfValidExamAcceptedCPCombinations = array(
    'GRE'	=>array(
        'coursesApplicable'=> array(
            'MS'=>true,'MBA'=>false,'MEM'=>true,
            'MPHARM'=>true,'MFIN'=>true,'MENG'=>true,
            'MIS'=>true,'MIM'=>true,'MARCH'=>true,
            'MASC'=>true, 'MA'=>true
        ),
        'ldbNameIdMap' =>array(
            DESIRED_COURSE_MBA=>STUDY_ABROAD_POPULAR_MBA,
            DESIRED_COURSE_MS=>STUDY_ABROAD_POPULAR_MS,
//            DESIRED_COURSE_MEM => STUDY_ABROAD_POPULAR_MEM,
//            DESIRED_COURSE_MPHARM => STUDY_ABROAD_POPULAR_MPHARM,
//            DESIRED_COURSE_MFIN => STUDY_ABROAD_POPULAR_MFIN,
//            DESIRED_COURSE_MENG => STUDY_ABROAD_POPULAR_MENG,
//            DESIRED_COURSE_MIS => STUDY_ABROAD_POPULAR_MIS,
//            DESIRED_COURSE_MIM => STUDY_ABROAD_POPULAR_MIM,
//            DESIRED_COURSE_MARCH => STUDY_ABROAD_POPULAR_MARCH,
//            DESIRED_COURSE_MASC => STUDY_ABROAD_POPULAR_MASC,
//            DESIRED_COURSE_MA => STUDY_ABROAD_POPULAR_MA,
        ),
        'courseMapForArticle' => array(
            DESIRED_COURSE_MS,
            DESIRED_COURSE_MEM,
            DESIRED_COURSE_MPHARM,
            DESIRED_COURSE_MFIN,
            DESIRED_COURSE_MENG,
            DESIRED_COURSE_MIS,
            DESIRED_COURSE_MIM,
            DESIRED_COURSE_MARCH,
            DESIRED_COURSE_MASC,
            DESIRED_COURSE_MA
        )
    ),
    'GMAT'	=>array(
        'coursesApplicable'=> array(
            'MBA'=>true,'MS'=>false
        ),
        'ldbNameIdMap' =>array(
            DESIRED_COURSE_MBA=>STUDY_ABROAD_POPULAR_MBA,
            DESIRED_COURSE_MS=>STUDY_ABROAD_POPULAR_MS
        ),
        'courseMapForArticle' => array(
            DESIRED_COURSE_MBA,
        )
    ),
    'SAT'	=>array(
        'coursesApplicable'=>array(
            'BE-BTECH'=>false,'BACHELORS-OF-BUSINESS'=>false,
            'BBA'=>true,'BHM'=>true,'BSC'=>true
        ),
        'ldbNameIdMap' =>array(
            DESIRED_COURSE_BTECH=>STUDY_ABROAD_POPULAR_BEBTECH
        ),
        'courseMapForArticle' => array(
            DESIRED_COURSE_BBA,
            DESIRED_COURSE_BHM,
            DESIRED_COURSE_BSC,
            DESIRED_COURSE_BTECH
        )

    ),
    'IELTS'	=>array(
        'coursesApplicable'=>array(
            'BE-BTECH'=>false,'MBA'=>false,'MS'=>false
        ),
        'ldbNameIdMap' =>array(
            DESIRED_COURSE_MBA=>STUDY_ABROAD_POPULAR_MBA,
            DESIRED_COURSE_MS=>STUDY_ABROAD_POPULAR_MS,
            DESIRED_COURSE_BTECH=>STUDY_ABROAD_POPULAR_BEBTECH
        ),
        'countryMapForArticle' => array(4,5,7,8,22,9,32,42,12,40,14,46,21,53,43,27,25,26,17,48,16,11,13,44,54,56,58,50,15)
    ),
    'TOEFL'	=>array(
        'coursesApplicable'=>array(
            'BE-BTECH'=>false,'MBA'=>false,'MS'=>false
        ),
        'ldbNameIdMap' =>array(
            DESIRED_COURSE_MBA=>STUDY_ABROAD_POPULAR_MBA,
            DESIRED_COURSE_MS=>STUDY_ABROAD_POPULAR_MS,
            DESIRED_COURSE_BTECH=>STUDY_ABROAD_POPULAR_BEBTECH
        ),
        'countryMapForArticle' => array(3,8)
    ),
    'PTE'	=>array(
        'coursesApplicable'=>array(
            'BE-BTECH'=>true,'MBA'=>true,'MS'=>true
        ),
        'ldbNameIdMap' =>array(
            DESIRED_COURSE_MBA=>STUDY_ABROAD_POPULAR_MBA,
            DESIRED_COURSE_MS=>STUDY_ABROAD_POPULAR_MS,
            DESIRED_COURSE_BTECH=>STUDY_ABROAD_POPULAR_BEBTECH
        ),
        'countryMapForArticle' => array(5,7)
    )
);
$listOfValidScoresForExamAcceptedCPCombinations = array(
    'GRE' => array(261=>270,271=>280,281=>290,291=>300,301=>310,311=>320,321=>330,331=>340),
    'GMAT'=> array(310=>350,360=>400,410=>450,460=>500,510=>550,560=>600,610=>650,660=>700),
    'SAT' => array(400=>500,500=>600,600=>700, 700=>800, 800=>900, 900=>1000, 1000=>1100, 1100=>1200, 1200=>1300, 1300=>1400, 1400=>1500, 1500=>1600)
);