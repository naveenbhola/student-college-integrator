<?php
    define('ALUMNI_SECTION_FLAG', true);
    define('MAILER_NUM_QUESTIONS',5);
    define('MAILER_MIN_QUESTIONS',4);
    define('REGISTRATION_QUESTION_POOL_DURATION',3);
    define('BEST_ANSWER_MAIL_COUNT',5);
    define('WEEKLY_MAILER_QUESTION_POOL_DURATION',20);
    define('DAILY_MAILER_QUESTION_POOL_DURATION',3);
    define('WEEKLY_MAILER_USER_IGNORE_DURATION',7);
    define('WEEKLY_MAILER_POPULAR_QUESTION_COUNT',3);
    define('MAILER_USER_CACHE',10000);
    define('MAILER_CATEGORY_CACHE',3);
    define('PER_CATEGORY_QUESTION_CACHE',1000);
    define('DAILY_MAILER_USER_SET_START_TIME',1);
    define('DAILY_MAILER_USER_SET_END_TIME',0);
    define('COMMENT_ALERT_QUOTA',10000);
    define('SHIKSHA_ADMIN_MAIL','noreply@shiksha.com');
    define("TESTPREP_LISTINGS_PER_PAGE", 30);

    /*
     * Recommendation constants
     */
    
    define('RECO_USER_BATCH_SIZE',200);
    
    define('RECO_NUM_RESULTS',5);
    define('RECO_MIN_REQUIRED_RESULTS',1);
    define('RECO_MAX_CATEGORIES',2);
    
    define('RECO_NUM_DAYS',90);
    define('RECO_TIME_WINDOW',30);
    
    define('RECO_CRON_ON','On');
    define('RECO_CRON_OFF','Off');
    define('RECO_CRON_FAIL','Failed');
    define('RECO_CRON_TERMINATE','Terminated');
    
    define('RECO_MAX_CRON_FAIL_COUNT',2);
    
    define('RECO_LOG_FILE','/tmp/recommendation.log');
    
    define('FILTER_PREFERRED_OR_RESIDENCE_CITIES',1);
	define('FILTER_PREFERRED_CITIES',2);
	define('FILTER_MODE_OF_LEARNING',3);
	define('FILTER_EXAMS',4);
	define('FILTER_PREFERRED_COUNTRIES',5);
	define('FILTER_COURSE_LEVEL',6);
	
	define('ORDER_AIMA_RATING',1);
	define('ORDER_AICTE_RECOGNITION',2);
	define('ORDER_UGC_RECOGNITION',3);
	define('ORDER_INTERNATIONAL_AFFILIATION',4);
	define('ORDER_AVERAGE_SALARY',5);
	define('ORDER_PLACEMENT_ASSISTENCE',6);
	define('ORDER_ALUMNI_RATING',7);
	define('ORDER_PHOTOS_AVAILABLE',8);
	define('ORDER_QUESTIONS_ASKED',9);
	define('ORDER_RESPONSES_GENERATED',10);
	define('ORDER_DEC_RECOGNITION',11);
	define('ORDER_INCAMPUS_HOSTEL',12);
	define('ORDER_EXAM_CAT',13);
	define('ORDER_EXAM_MAT',14);
	define('ORDER_COURSE_DURATION',15);
	define('ORDER_LOCALITY',16);
	define('ORDER_AFFILIATION_TO_ANY_UNIVERSITY',17);
	
	define('EXCLUSION_SOURCE_RECOMMENDATIONS',1);
	
	define('ALGO_ALSO_VIEWED',1);
	define('ALGO_SIMILAR_INSTITUTES',2);
	define('ALGO_PROFILE_BASED',3);
	
	define('USE_PROFILE_BASED_COLLABORATIVE_FILTERING', false);
	define('MAX_USER_SET_SIZE', 500000);
	define('USERS_CONSIDERED_FOR_PAST_MONTHS', 12);
	
	global $validResponseTypesForRecommendations;
	$validResponseTypesForRecommendations = array(	'Compare_Email',
													'GetFreeAlert',
													'Listing-Photos',
													'RANKING_PAGE_REQUEST_EBROCHURE',
													'reco_after_category',
													'mobilesite',
													'LISTING_PAGE_RIGHT_RECOMMENDATION',
													'LISTING_PAGE_BOTTOM_RECOMMENDATION',
													'Request_E-Brochure',
													'RESPONSE_MARKETING_PAGE',
													'SEARCH_REQUEST_EBROCHURE'
												);
	/*
	 * Category Page Filters
	 */ 
	define('CP_FILTER_MODE',1);
	define('CP_FILTER_COURSELEVEL',2);
	define('CP_FILTER_DURATION',3);
	define('CP_FILTER_CLASSTIMINGS',4);
	define('CP_FILTER_LOCALITY',5);
	define('CP_FILTER_CITY',6);
	define('CP_FILTER_DEGREEPREF',7);
	define('CP_FILTER_EXAMS',8);
	define('CP_FILTER_AIMARATING',9);
	define('CP_FILTER_ZONE',10);
	define('CP_FILTER_COUNTRY',11);
	define('CP_FILTER_COURSELEVEL1',12);
	define('CP_FILTER_COURSELEVEL2',13);
	define('CP_FILTER_STATE',14);
	define('CP_FILTER_FEES',15);
	define('CP_FILTER_COURSEEXAMS',16);
	define('CP_FILTER_LASTMODIFIEDDATE',17);
	define('CP_FILTER_MOREOPTIONS',18);
	define('CP_FILTER_COURSECATEGORY',19);
	define('CP_FILTER_EXAMSSCORE',20);
	
	define('ABROAD_STUDENT_HELPLINE', '011-4046-9621');

	/*
	 * Category Page Sorters
	 */
	define('CP_SORTER_FEES',1);
	define('CP_SORTER_DURATION',2);
	define('CP_SORTER_VIEWCOUNT',3);
	define('CP_SORTER_TOPINSTITUTES',4);
	define('CP_SORTER_DATEOFCOMMENCEMENT',5);
	define('CP_SORTER_EXAMSCORE',6);
	
	/*
	 * Abroad Category Page Sorters
	 */
	define('ABROAD_CP_SORTER_FEES',1);
	define('ABROAD_CP_SORTER_VIEWCOUNT',2);
	define('ABROAD_CP_SORTER_EXAM',3);
	
	/*
	 * Abroad Ranking Page Sorters
	 */
	define('ABROAD_CP_SORTER_RANK',4);

	/*
	 * Category Page Cron
	 */
	define('CP_CRON_ON','On');
    define('CP_CRON_OFF','Off');
    define('CP_CRON_FAIL','Failed');
    define('CP_CRON_TERMINATE','Terminated');
    define('CP_MAX_CRON_FAIL_COUNT',3);
    
    /***
     *  Category Page Solr Switch.
     */
    
    define('CP_SOLR_FLAG',true);
    global $CP_SOLR_FL_LIST;
	$CP_SOLR_FL_LIST = array(
								'course_id',
								'course_pack_type'
							);

	global $CP_SOLR_FL_LIST_SORT;
    $CP_SOLR_FL_LIST_SORT = array(
												'duration' 	=> 'course_duration_in_hours',
												'fees' 		=> 'course_normalised_fees',
                                                                                                'date_form_submission' 		=> 'date_form_submission',
												'examscore' => 'course_exam_*'
											);
    global $CP_SOLR_FL_LIST_FOR_MULTILOCATION;
    $CP_SOLR_FL_LIST_FOR_MULTILOCATION = array(
		'city' => 'course_city_id',
		'state' => 'course_state_id'			
	);
    global $SOLR_DATA_FIELDS_ALIAS;
    $SOLR_DATA_FIELDS_ALIAS = array(
		"course_id" 			=> "a",
		"institute_id" 			=> "b",
		"course_pack_type" 		=> "c",
		"course_city_id" 		=> "d",
		"course_state_id"		=> "e",
		"course_duration_in_hours" 	=> "f",
		"course_normalised_fees" 	=> "g",
		"date_form_submission"	=> "h",
		"institute_title_facet"	=> "i",
		"institute_view_count"	=> "j",
		"course_view_count"		=> "k",
		"course_locality_id"	=> "l",
		"institute_synonyms"	=> "m",
		"institute_accronyms"	=> "n",
		"asv2_subcat_name_id_map" => 'o',
		"asv2_spec_ldb_custom_name_id_map" => 'p');
    
    define('SEARCH_PAGE_LIMIT', 15);
    define('SEARCH_PAGE_LIMIT_MOBILE', 5);
	
	//define('SA_SEARCH_PAGE_LIMIT', 10);
    //define('SA_SEARCH_PAGE_LIMIT_MOBILE', 5);
    
	/*
	 * NAV INtegration Cron
	 */
	define('NAV_CRON_ON','On');
    define('NAV_CRON_OFF','Off');
    define('NAV_CRON_FAIL','Failed');
    define('NAV_CRON_TERMINATE','Terminated');
    define('NAV_MAX_CRON_FAIL_COUNT',2);

	
    define("LDB_SERVER_URL", "http://".$ip."/LDB/LDB_Server");
    define("LDB_SERVER_PORT", $serverPort);
    global $CP_FEES_RANGE;
	$CP_FEES_RANGE = array(
								'RS_RANGE_IN_LACS' => array(
												100000  => "Maximum 1 Lakh",
												200000  => "Maximum 2 Lakhs",
												500000  => "Maximum 5 Lakhs",
												700000  => "Maximum 7 Lakhs",
												1000000  => "Maximum 10 Lakhs",
											)
							);
	global $MBA_SCORE_RANGE;
	$MBA_SCORE_RANGE = array(
									'Less than 55' 	=> 54,
									'Around 60' 	=> 60,
									'Around 70' 	=> 70,
									'Around 80' 	=> 80,
									'Around 90' 	=> 90,
									'Around 95' 	=> 95
								);
	global $MBA_PERCENTILE_RANGE_MAT;
	$MBA_PERCENTILE_RANGE_MAT = array (
									'Less than 55' 	=> 54,
									'Around 60' 	=> 60,
									'Around 70' 	=> 70,
									'More than 75' 	=> 75,
								);
	global $MBA_PERCENTILE_RANGE_XAT;
	$MBA_PERCENTILE_RANGE_XAT = array (
									'Less than 55' 	=> 54,
									'Around 60' 	=> 60,
									'Around 70' 	=> 70,
									'Around 80'		=> 80,
									'More than 85' 	=> 85,
								);
 	global $MBA_PERCENTILE_RANGE_NMAT;
	$MBA_PERCENTILE_RANGE_NMAT = array (
									'Less than 55' 	=> 54,
									'Around 60' 	=> 60,
									'Around 70' 	=> 70,
									'Around 80'		=> 80,
									'More than 85' 	=> 85,
								);
	
	global $MBA_SCORE_RANGE_CMAT;
	$MBA_SCORE_RANGE_CMAT = array(
									'Less than 80' 	 => "0-79",
									'From 80-150' 	 => "80-150",
									'From 150-220' 	 => "150-220",
									'From 220-400' 	 => "220-400",
									//'More than 400 ' => "401"
								);
	global $MBA_SCORE_RANGE_GMAT;
	$MBA_SCORE_RANGE_GMAT = array(
									'Less than 400' 	 => "0-399",
									'From 400-499' 	 	 => "400-499",
									'From 500-599' 	 	 => "500-599",
									'From 600-650' 	 	 => "600-650",
									'More than 650' 	 => "651"
								);
	
	global $MBA_EXAM_CUTOFF_LIMITS;
    $MBA_EXAM_CUTOFF_LIMITS = array(
    	'defaultExam' => array (
    	 'lower' => 54,
    	 'upper' => 95,
    	 'diff'  => 5,
    	 'type'  => 'around'						 
    ),
      	'MAT' => array (
    	 'lower' => 54,
    	 'upper' => 75,
    	 'diff'  => 5,
      	 'type'  => 'around',
    ),
        'XAT' => array (
    	 'lower' => 54,
    	 'upper' => 85,
    	 'diff'  => 5,
      	 'type'  => 'around',
    ),
   		'NMAT' => array (
   		'lower' => 54,
   		'upper' => 85,
   		'diff'  => 5,
   		'type'  => 'around',
   ),
   		'CMAT' => array (
   		'type'  => 'range',
    ),
    	'GMAT' => array (
       	'type'  => 'range',
    )
   );
	
	global $MBA_EXAMS_SPECIAL_SCORES;
	$MBA_EXAMS_SPECIAL_SCORES = array("MAT", "XAT", "NMAT", "CMAT", "GMAT");
	global $ENGINEERING_EXAMS_REQUIRED_SCORES;
	$ENGINEERING_EXAMS_REQUIRED_SCORES = array("BITSAT", "TNEA");
	global $MBA_EXAMS_REQUIRED_SCORES;
	$MBA_EXAMS_REQUIRED_SCORES = array("CMAT", "GMAT");
	global $MBA_NO_OPTION_EXAMS;
	$MBA_NO_OPTION_EXAMS = array("No Exam Required", "IIFT", "SNAP", "IBSAT", "IRMA");
	
	
    global $exam_weightage_array;
    $exam_weightage_array = array(
    	'CAT'=>6,
        'MAT'=>5,
		'CMAT'=>4,
        'XAT'=>3,
		'ATMA'=>2,
		'NMAT'=>1,
		'SET'=>1,
		'SNAP'=>1,
		'FMS'=>1,
		'IIFT'=>1,
        'IRMA'=>1,
		'JMET'=>1,
        'IBSAT'=>1,
        'CET'=>1,
        'JEE Mains'=>4,
        'JEE Advanced'=>3,
        'BITSAT'=>2,
        'GATE'=>1,
		'CET'=>1,
		'AIEEE'=>1,
		'IIT'=>1,
		'EAMCET'=>1,
		'COMEDK'=>1,
		'VITEEE'=>1,
		'WBJEE' => 1,
		"TNEA" => 1,
		"KCET" 	=> 1,
		"UPSEE" => 1,
		"MT-CET" => 1,
		"CGPET" => 1,
		"SRMJEEE" => 1,
		"KEAM" 	=> 1,
		"MPPEPT" => 1,
		"MANIPAL - ENAT" => 1,
		"No Exam Required" => 0
    );
    global $ug_course_array;
    $ug_course_array = array( 'B.A.',
                                    'B.A.(Hons)',
                                    'B.Sc',
                                    'B.Sc(Gen)',
                                    'B.Sc(Hons)',
                                    'B.E./B.Tech',
                                    'B.Des',
                                    'B.Com',
                                    'BBA/BBM/BBS',
                                    'B.Ed',
                                    'BCA/BCM',
                                    'BVSc',
                                    'BHM',
                                    'BJMC',
                                    'BDS',
                                    'B.Pharma',
                                    'B.Arch',
                                    'MBBS',
                                    'LLB',
                                    'Diploma');
$ug_course_mapping_array = array( '2' => 'B.A.' , '2' => 'B.A.(Hons)',
		'11' => 'B.Sc', '11' => 'B.Sc(Gen)','11' => 'B.Sc(Hons)',
		'12' => 'B.E./B.Tech',
		'6' =>    'B.Com',
		'5' =>    'BBA/BBM/BBS',
		'7' =>     'B.Ed',
		'4' =>     'BCA/BCM',
		'16' =>      'BVSc',
		'9' =>      'BHM',
		'8' =>      'BDS',
		'10' =>      'B.Pharma',
		'3' => 'B.Arch',
		'14' =>      'MBBS',
		'13' =>      'LLB',
		'15' =>      'Diploma',
		'17' => 'CA',
		'18' => 'CS',
		'19' => 'ICWA');

$pg_course_mapping_array = array('20' => 'Integrated PG',
		'21' => 'LLM',
		'22' => 'M.A',
		'23' => 'M.Arch',
		'24' => 'M.Com',
		'25' => 'M.Ed',
		'26' => 'M.Pharma',
		'27' => 'M.Sc',
		'28' => 'M.Tech',
		'29' => 'M.B.A/PGDM',
		'30' => 'M.C.A',
		'31' => 'PG Diploma',
		'32' => 'MVSC',
		'33' => 'MCM',
		'34' => 'Ph.D/Doctorate',
		'35' => 'M.Phil');

global $countryArrayForMigration;
$countryArrayForMigration = array ( 'Canada' => '8',
        'UK' => '4',
        'USA' => '3',
        'Australia' =>'5',
        'Germany' =>'9',
        'Singapore' =>'6',
        'New Zealand' => '7' );
        
    /*
      Vikas K, July 13 2011 | Ticket #412
      Add institute ID here if you don't want to display its city in the following places:
      1. "Also on Shiksha" on listing details page
      2. "Institutes & Universities in ..." on study abroad page
    */

    global $hideCityForInstitutes;
    $hideCityForInstitutes = array(
      33211,
      32469,
      32383,
      32645
    );    
    
	/*
	 * PLEASE MAKE SURE THE PERMISSIONS OF SVN_PATH is 777
	 */ 
	define('SVN_PATH','/home/vikas/SVNCheckouts/HOTFIX_HMVC_DEV_MAINLINE_RECAT_04032012');    
   
    define("JS_PATH", $_SERVER['DOCUMENT_ROOT'].'/public/js/' );
    define("REPOS_SIZE", 200);
    define("SERVE_SIZE", 500);
    
    define("MISC_SERVER_URL", "http://".$ip."/misc/miscServer");
    define("SA_SERVER_URL", "http://".$ip."/blogs/sa_server");
    define("MISC_SERVER_PORT", $serverPort);
	global $aimaRatings; 
	$aimaRatings = array("SL","A+","A1","A2","A3","A4","B1","B2","B3","B4");
	
	global $sourceList; 
	$sourceList = array("Website", "Sales person", "Client", "Others");
		
	
	if(isset($shiksha_folder)) {
		include_once $shiksha_folder.'/globalconfig/marketingPageFieldConstants.php';
	}
	else {
		include_once('globalconfig/marketingPageFieldConstants.php');
	}

	global $testPrepCourses_requires_12th_marks;
	
	$testPrepCourses_requires_12th_marks  =  array (3280,330,3299,302,303,3300,418,410);
    define("ANSWER_CHARACTER_LENGTH", 2500);
    define("ANSWER_MIN_CHARACTER_LENGTH", 15);

	$shikshaCacheProfile = array();

    define("BRONZE_LISTINGS_BASE_PRODUCT_ID", 7);
    define("GOLD_SL_LISTINGS_BASE_PRODUCT_ID", 1);
    define("GOLD_ML_LISTINGS_BASE_PRODUCT_ID", 375);
    
    define("CONSULTANT_BASE_PRODUCT_ID", 630);
    define("CONSULTANT_CLIENT_MIN_RESPONSE_PRICE", 300);
    define("CONSULTANT_CLIENT_APP_ID", 1);
    
    // For exisiting legacy lisitngs (Silver product, no longer in existence)
    define("SILVER_LISTINGS_BASE_PRODUCT_ID", 2);
//Career Product Constants

define("CAREER_HOME_PAGE",SHIKSHA_HOME.'/careers');
define("CAREER_EXPRESSINTEREST_PAGE",SHIKSHA_HOME.'/careers/opportunities'); 
define("CAREER_SUGGESTION_PAGE",SHIKSHA_HOME.'/careers/counselling');
define("CAREER_DETAIL_PAGE_FLASH_BANNER",SHIKSHA_HOME.'/public/images/careers/1358942378phpr9VPUm.swf');

    global $currencyAttributesArray;
    $currencyAttributesArray = array('0' => array('currencyType' => 'INR', 'currencySymbol' => 'INR'),
                                 '1' => array('currencyType' => 'USD', 'currencySymbol' => 'USD'),
                                 '2' => array('currencyType' => 'AUD', 'currencySymbol' => 'AUD'),
                                 '3' => array('currencyType' => 'CAD', 'currencySymbol' => 'CAD'),
                                 '4' => array('currencyType' => 'SGD', 'currencySymbol' => 'SGD'),
                                 '5' => array('currencyType' => 'GBP', 'currencySymbol' => 'GBP'),
                                 '6' => array('currencyType' => 'NZD', 'currencySymbol' => 'NZD'),
                                 '7' => array('currencyType' => 'EUR', 'currencySymbol' => 'EUR')
    );

	$MMPTrackedPages = array(
		53
	);	
    global $institutesWithoutUnified;
    $institutesWithoutUnified = array(27907,36439);
	global $callMeWidgetInstitutes;

	$callMeWidgetInstitutes = array(
								 '27542' => array('numbers' => '09015345149','mintime'=> '09:00','maxtime'=>'18:00'),
								 '168' => array('numbers' => '04222656422,09943117777','mintime'=> '09:00','maxtime'=>'18:00'),
								 '26287' => array('numbers' => '9448474282','mintime'=> '09:00','maxtime'=>'18:00'),
								 '30613' => array('numbers' => '9560155557,9313377036,9311163400','mintime'=> '09:00','maxtime'=>'18:00')
								 );
    define('CAREER_PRODUCT_FLAG', true);
	
	$responseActionViewMapping = array(
		'Asked_Question_On_Listing'             => 'Asked_Question_On_Listing',
		'Asked_Question_On_Listing_MOB'         => 'Asked_Question_On_Listing',
		'Asked_Question_On_CCHome'              => 'Asked_Question_On_CCHome',
		'Asked_Question_On_CCHome_MOB'          => 'Asked_Question_On_CCHome_MOB',
		'D_MS_Ask'                              => 'Asked_Question_On_Listing',
		'GetFreeAlert'                          => 'Get Free Alert',
		'Mailer_Alert'                          => 'Mailer Alert',
		'reco_widget_mailer'                    => 'Mailer Alert',
		'reco_widget_mailer_national'           => 'Mailer Alert',
		'reco_widget_mailer_national_mobile'    => 'Mailer Alert',
		'Client Mailer'                         => 'Mailer Alert',
		'Compare_Email'                         => 'Request E-Brochure',
		'LISTING_PAGE_BOTTOM_RECOMMENDATION'    => 'Request E-Brochure',
		'LISTING_PAGE_RIGHT_RECOMMENDATION'     => 'Request E-Brochure',
		'Listing-Photos'                        => 'Request E-Brochure',
		'mobilesite'                            => 'Request E-Brochure',
		'RANKING_PAGE_REQUEST_EBROCHURE'        => 'Request E-Brochure',
		'reco_after_category'                   => 'Request E-Brochure',
		'Request_E-Brochure'                    => 'Request E-Brochure',
		'RankPredictor'                         => 'Request E-Brochure',
		'RESPONSE_MARKETING_PAGE'               => 'Request_E-Brochure',
		'MOBILEHTML5'                           => 'Request_E-Brochure',
		'MOBILEHTML5_GETEB'                     => 'Request_E-Brochure',
		'MOBILE5_CATEGORY_PAGE'                 => 'Request_E-Brochure',
		'MOBILE5_INSTITUTE_DETAIL_PAGE'         => 'Request_E-Brochure',
		'MOBILE5_SIMILAR_INSTITUTE_DETAIL_PAGE' => 'Request_E-Brochure',
		'MOBILE5_SIMILAR_COURSE_DETAIL_PAGE'    => 'Request_E-Brochure',
		'MOBILE5_COURSE_DETAIL_PAGE'            => 'Request_E-Brochure',
		'MOBILE5_COURSE_DETAIL_PAGE_OTHER'      => 'Request_E-Brochure',
		'MOBILE5_SEARCH_PAGE'                   => 'Search Request_E-Brochure',
		'MOBILE5_COLLEGE_PREDICTOR_PAGE'        => 'Request_E-Brochure',
		'MOBILE5_RANK_PREDICTOR_PAGE'           => 'Request_E-Brochure',
		'MOBILE5_SHORTLIST_PAGE'                => 'Request_E-Brochure',
		'RANKING_MOB_ReqEbrochure'              => 'Request_E-Brochure',
		'CP_MOB_Reco_ReqEbrochure'              => 'Request_E-Brochure',
		'SEARCH_MOB_Reco_ReqEbrochure'          => 'Request_E-Brochure',
		'RANKING_MOB_Reco_ReqEbrochure'         => 'Request_E-Brochure',
		'LP_MOB_Reco_ReqEbrochure'              => 'Request_E-Brochure',
		'MOB_CareerCompass_Ebrochure'           => 'Request_E-Brochure',
		'MOB_COMPARE_EBrochure'                 => 'Request_E-Brochure',
		'SEARCH_REQUEST_EBROCHURE'              => 'Search Request E-Brochure',
		'Viewed_Listing'                        => 'Viewed_Listing',
		'mobile_viewedListing'                  => 'Viewed_Listing',
		'MOB_COMPARE_VIEWED'                    => 'Viewed_Listing',
		'Viewed_Listing_Pre_Reg'                => 'Pre-Registration View',
		'User_ShortListed_Course'               => 'Shortlisted by User',
		'ND_myshortlist_shortlist'              => 'Shortlisted by User',
		'ND_category_shortlist'                 => 'Shortlisted by User',
		'ND_ranking_shortlist'                  => 'Shortlisted by User',
		'ND_course_shortlist'                   => 'Shortlisted by User',
		'MOB_Course_Shortlist'                  => 'Shortlisted by User'
	);
	
	$blogIdToNewSubCategoryMapping = array(
			298 => 48,
			300 => 51,
			464 => 54,
			299 => 47,
			297 => 49
		);
	
	$GLOBALS['SHIKSHA_ONLINE_FORMS_HOME'] = SHIKSHA_HOME . '/mba/resources/application-forms';
	$onlineFormsDepartments = array(
		'Management' => array(
							  'level' => 'PG',
							  'shortName' => 'MBA',
							  'url' => 'mba/resources/application-forms',
							  'gdPiName' => 'GD/PI',
							  'id' => 1,
							  'exams' => 'CAT, MAT, GMAT, XAT etc.'
							  ),
		'Engineering' => array(
							  'level' => 'UG',
							  'shortName' => 'Engineering',
							  'url' => 'college-admissions-engineering-online-application-forms',
							  'gdPiName' => 'Interview / Counselling',
							  'id' => 2,
							  'exams' => 'JEE Mains/Paper 1'
							  )
	);

	define("RESPONSE_PORTING_DURATION_BASED_BASE_PRODUCT_ID", 496);
	define("LEAD_PORTING_DURATION_BASED_BASE_PRODUCT_ID", 127);			//constant moved to LDB Lead Subscription Id now
	define("LEAD_PORTING_QUANTITY_BASED_BASE_PRODUCT_ID", 127);			//constant moved to LDB Lead Subscription Id now
	define("LMS_PORTING_USER_ID", 2796439);
	
	define("MAIL_SALES",'sales@shiksha.com');
	$GLOBALS['MAIL_SALES_CC']=array('ambrish@shiksha.com');
	define("MAIL_LISTING",'tarun.agarwal@shiksha.com');
	$GLOBALS['MAIL_LISTING_CC'] = array('parminder.kaur@shiksha.com', 'kamaljeet.singh@shiksha.com');
	define("LISTING_QUESTIONS_PER_PAGE",30);

	//list of acceptible fees types
	$fees_types_array = array("Tuition", "Admission", "Hostel", "Others");

	define("SHIKSHA_DATA_LAST_UPDATED","2008-10-01 00:00:00");
	
	define("SHIKSHA_COURSE_QUESTIONS_MIGRATION_DATE","2014-02-13");
	
	$excludeViewLimitForClients = array(1029, 874001);

	define("SHIKSHA_ARTICLE_POPULAR_MAIN_MULTIPLIER",'500');
	define("SHIKSHA_ARTICLE_VIEW_COUNT_WEIGHT","3");
	define("SHIKSHA_ARTICLE_COMMENT_COUNT_WEIGHT","7");
	define("SHIKSHA_ARTICLE_DATE_ADD_CONSTANT","2");
	define("SHIKSHA_ARTICLE_EXPONENTIAL","4");
	define("SHIKSHA_ARTICLE_ADDITION_CONSTANT","2");
	
	// START : Section to define all the constants that need to be modified later while going live
	define("ENT_SA_PRE_LIVE_STATUS", "live");
    define("ENT_SA_DELETED_STATUS", "deleted");
	define("ENT_SA_COUNTRY_CITY_TABLE_NAME" , "countryCityTable");

	define("ENT_SA_HISTORY_STATUS", "history");
	define("ENT_SA_COUNTRY_TABLE_NAME" , "countryTable");
	define("ENT_SA_STATE_TABLE_NAME" , "stateTable");
	define("ENT_SA_REGION_TABLE_NAME" , "tregion");
	define("ENT_SA_REGION_MAPPING_TABLE_NAME" , "tregionmapping");	
	
	
	$responseActionsByPage = array(
		'category' => array(
			'ND_category_shortlist',
			'Request_E-Brochure',
			'Compare_Email',
			'Listing-Photos',
			'reco_after_category',
			'CP_Reco_ReqEbrochure',
			'CP_Reco_popupLayer',
			'CP_Reco_divLayer',
			'CoursePage_Reco',
			'download_brochure_free_course',
			'User_ShortListed_Course',
			'MOB_Course_Shortlist',
			'MOBILE5_CATEGORY_PAGE',
			'CP_MOB_Reco_ReqEbrochure'),
		'listing'  => array(
			'ND_course_shortlist',
			'Viewed_Listing',
			'Viewed_Listing_Pre_Reg',
			'mobile_viewedListing',
			'GetFreeAlert',
			'contactinfo',
			'requestinfo',
			'savedlisting',
			'sentmail',
			'sentsms',
			'Asked_Question_On_Listing',
			'Asked_Question_On_Listing_MOB',
			'Asked_Question_On_CCHome',
			'Asked_Question_On_CCHome_MOB',
			'D_MS_Ask',
			'LISTING_PAGE_RIGHT_RECOMMENDATION',
			'LISTING_PAGE_BOTTOM_RECOMMENDATION',
			'LP_Reco_ ReqEbrochure', 'Institute_Viewed',
			'LISTING_PAGE_EMAIL_SMS_CONTACT_DETAILS',
			'LP_Reco_SimilarInstiLayer',
			'LP_Reco_AlsoviewLayer',
			'LP_Reco_ShowRecoLayer',
			'Listing-Photos',
			'RESPONSE_MARKETING_PAGE',
			'RANKING_PAGE_REQUEST_EBROCHURE',
			'marketingPage',
			'mailerAlert',
			'Mailer_Alert',
			'reco_widget_mailer',
			'reco_widget_mailer_national',
			'reco_widget_mailer_national_mobile',
			'download_brochure_free_course',
			'User_ShortListed_Course',
			'MOB_Course_Shortlist',
			'MOBILE5_INSTITUTE_DETAIL_PAGE',
			'MOBILE5_SIMILAR_INSTITUTE_DETAIL_PAGE',
			'MOBILE5_SIMILAR_COURSE_DETAIL_PAGE',
			'MOBILE5_COURSE_DETAIL_PAGE',
			'MOBILE5_COURSE_DETAIL_PAGE_OTHER',
			'RANKING_MOB_ReqEbrochure',
			'RANKING_MOB_Reco_ReqEbrochure',
			'LP_MOB_Reco_ReqEbrochure'),
		'search'   => array(
			'SEARCH_REQUEST_EBROCHURE',
			'CP_Reco_popupLayer',
			'CP_Reco_ReqEbrochure',
			'download_brochure_free_course',
			'MOBILE5_SEARCH_PAGE',
			'SEARCH_MOB_Reco_ReqEbrochure'),
		'mobile'   => array(
			'mobilesite',
			'mobilesitesearch',
			'MOBILEHTML5',
			'MOBILEHTML5_GETEB',
			'MOB_CareerCompass_Ebrochure',
			'MOBILE5_RANK_PREDICTOR_PAGE',
			'MOBILE5_COLLEGE_PREDICTOR_PAGE',
			'MOB_COMPARE_EBrochure',
			'MOB_COMPARE_VIEWED',
			'MOB_COMPARE_EMAIL')
	);

    /**Blocked IP for Abroad Listing View Count Tracking**/
	
	$blockedIPsForAbroadListingViewCountTracking = array('121.243.22.130','115.254.79.170','115.249.243.194');
	
    /* List of subcategories of courses for which list of courses dropdown need not to be shown on national course pages */
    global $nationalCoursesSubcatsWithoutBrochureList;
    $nationalCoursesSubcatsWithoutBrochureList = array(23, 27, 26, 25, 24, 28, 56, 100, 84);

	
/***Fee Range For Abroad Filters***/
	$GLOBALS['CP_ABROAD_FEES_RANGE'] = array(
			'ABROAD_RS_RANGE_IN_LACS' => array(
					'0-1000000' => "Max 10 Lacs",
					'0-2000000'  => "Max 20 Lacs",
					'0-3000000'  => "Max 30 Lacs",
					'0-4000000'  => "Max 40 Lacs",
					'0-90000000000'  => "Any Fees",
					
			)
	);
	
/**** More Option Filter value on Abroad Category Pages**/
		
	$GLOBALS['MORE_OPTIONS'] = array(
			
			'OFR_SCHLSHP'  	=> "Offering Scholarship",
			'WTH_ACMDTN'   	=> "With Accommodation",
			'PUB_FUND'   	=> "Publicly Funded",
			'DEGREE_COURSE' => "Exclude Certificate / Diploma Courses"
	);
	
/**** mappings for URL parameter of url parameters ****/
	$GLOBALS['ABROAD_CP_URL_PARAMS_TO_FILTER_MAPPINGS'] =  array(
	    'FEE_URL_PARAM_TO_RANGE_KEY_MAPPING' => array(
			'0to10l'    	=> '0-1000000',
			'0to20l'  	=> '0-2000000',
			'0to30l'  	=> '0-3000000',
			'0to40l'  	=> '0-4000000',
			'any'   	=> '0-10000000000'
			),
	    'MORE_OPTIONS_URL_PARAM_TO_KEY_MAPPING' => array(
			'scholarship'    => 'OFR_SCHLSHP',
			'accomodation'   => 'WTH_ACMDTN' ,
			'publiclyfunded' => 'PUB_FUND'   
			),
	    'FILTER_NAME_TO_URL_PARAM_NAME_MAPPING' => array(
			'fee'	 => '1stYearFees'   ,
			'course' => 'specialization'   ,
			'moreopt'=> 'moreoptions'   )
	);
	
global $naukri_functional_name_mapping;
    $naukri_functional_name_mapping =   array(
       		"Sales / BD"=>"Sales/ Business Development",
                "Marketing / Advertising / MR / PR"=>"Marketing/Advertising",
                "HR / Administration / IR"=>"Human Resources",
                "Accounts / Finance / Tax / CS / Audit"=>"Accounts/Finance",
                "Banking / Insurance"=>"Banking/ Insurance",
                "IT Software"=>"IT Software",
                "ITES / BPO / KPO / Customer Service / Operations"=>"ITES/ BPO",
                "Purchase / Logistics / Supply Chain"=>"Logistics / Supply Chain",
                "Corporate Planning / Consulting"=>"Corporate Planning / Consulting",
		"Production / Maintenance / Quality"=>"Production / Maintenance",
		"Analytics & Business Intelligence"=>"Analytics & Business Intelligence",
		"Pharma / Biotech / Healthcare / Medical / R&D"=>"Pharma / Biotech",
		"Top Management"=>"Top Management",
		"Site Engineering / Project Management"=>"Project Management",
		"Export / Import / Merchandising"=>"Merchandising",
		"IT- Hardware / Telecom / Technical Staff / Support"=>"IT- Hardware / Telecom",
		"Teaching / Education"=>"Teaching / Education",
		"Fashion / Garments / Merchandising"=>"Fashion / Garments", 
		"Ticketing / Travel / Airlines"=>"Travel / Airlines",
		"Engineering Design / R&D"=>"Engineering Design / R&D",
		"Hotels / Restaurants"=>"Hotels / Restaurants",
		"Secretary / Front Office / Data Entry"	=>"Secretary / Front Office",
		"Legal"=>"Legal",
		"Content / Journalism"=>"Content / Journalism",
		"Self Employed / Consultants"=>"Entreprenuership",
		"Agent"=>"Others",
		"Guards / Security Services"=>"Others",
		"Web / Graphic Design / Visualiser"=>"Others",
		"Packaging"=>"Others",
		"TV / Films / Production"=>"Films / Production",
		"Architecture / Interior Design"=>"Others",
		"Shipping"=>"Others"  	
        );
  
      global $naukri_specialization_mapping;  
        $naukri_specialization_mapping = array(
      		"385"   =>array( "shikshaspl"=>"Operations",                     "naukrispl"=>"Operations"              ),
		"451"   =>array( "shikshaspl"=>"Sales & Marketing",              "naukrispl"=>"Marketing"               ),
                "188"   =>array( "shikshaspl"=>"Finance",                        "naukrispl"=>"Finance"                 ),
		"251"   =>array( "shikshaspl"=>"Human Resources",                "naukrispl"=>"HR/Industrial Relations" ),
		"283"   =>array( "shikshaspl"=>"IT & Systems",                   "naukrispl"=>"Information Technology"  ),
		"274"   =>array( "shikshaspl"=>"International Business",         "naukrispl"=>"International Business"  ),	 
  		"257"   =>array( "shikshaspl"=>"Import & Export",                "naukrispl"=>"Other Management"        ),
		"440"   =>array( "shikshaspl"=>"Retail",                         "naukrispl"=>"Other Management"        ),
		"236"   =>array( "shikshaspl"=>"HealthCare & Hospital",          "naukrispl"=>"Other Management"        ),
		"496"   =>array( "shikshaspl"=>"Telecom",                        "naukrispl"=>"Other Management"        ),
		"266"   =>array( "shikshaspl"=>"Infrastructure",                 "naukrispl"=>"Other Management"        ),
		"511"   =>array( "shikshaspl"=>"Transport & Logistics",          "naukrispl"=>"Other Management"        ),
		"17"    =>array( "shikshaspl"=>"Agriculture & Food Business",    "naukrispl"=>"Other Management"        ),
		"170"   =>array( "shikshaspl"=>"Entrepreneurship",               "naukrispl"=>"Other Management"        ),
		"399"   =>array( "shikshaspl"=>"Pharma",                         "naukrispl"=>"Other Management"        )	              			
      );
	define("COLLEGE_PREDICTOR_COUNT_OFFSET","15");
	define("STUDY_ABROAD_NEW_REGISTRATION", true);

      global $engineeringExams; 
      $engineeringExams = array(
                "jee-main"=>array("url"=>SHIKSHA_SCIENCE_HOME."/jee-main","name"=>"JEE Main"),
                "jee-advanced"=>array("url"=>SHIKSHA_SCIENCE_HOME."/jee-advanced","name"=>"JEE Advanced"),
                "bitsat"=>array("url"=>SHIKSHA_SCIENCE_HOME."/bitsat","name"=>"BITSAT"),
		"kiitee"=>array("url"=>SHIKSHA_SCIENCE_HOME."/kiitee","name"=>"KIITEE"),
                "eamcet"=>array("url"=>SHIKSHA_SCIENCE_HOME."/eamcet","name"=>"EAMCET"),
		"upsee"=>array("url"=>SHIKSHA_SCIENCE_HOME."/upsee","name"=>"UPSEE"),
		"comedk"=>array("url"=>SHIKSHA_SCIENCE_HOME."/comedk","name"=>"COMEDK"),
		"wbjee"=>array("url"=>SHIKSHA_SCIENCE_HOME."/wbjee","name"=>"WBJEE"),
		"keam"=>array("url"=>SHIKSHA_SCIENCE_HOME."/keam","name"=>"KEAM"),
		"rpet"=>array("url"=>SHIKSHA_SCIENCE_HOME."/rpet","name"=>"RPET"),
		"tnea"=>array("url"=>SHIKSHA_SCIENCE_HOME."/tnea","name"=>"TNEA"),
		"viteee"=>array("url"=>SHIKSHA_SCIENCE_HOME."/viteee","name"=>"VITEEE"),
		"karnataka-cet"=>array("url"=>SHIKSHA_SCIENCE_HOME."/karnataka-cet","name"=>"Karnataka CET"),
                "srmjeee"=>array("url"=>SHIKSHA_SCIENCE_HOME."/srmjeee","name"=>"SRMJEEE")
      );
      
      
      global $engineeringExamPageLinks; 
	  $engineeringExamPageLinks = array (
		1 => array (
				"jee-main" => array (
						"url" => SHIKSHA_HOME . "/b-tech/exams/jee-mains",
						"name" => "JEE Mains",
						"param"=> 'jee-mains'
				),
				"jee-advanced" => array (
						"url" => SHIKSHA_HOME . "/b-tech/exams/jee-advanced",
						"name" => "JEE Advanced",
						"param"=> 'jee-advanced'
				),
				"bitsat" => array (
						"url" => SHIKSHA_HOME . "/b-tech/exams/bitsat",
						"name" => "BITSAT",
						"param"=> 'bitsat'
				),
				"kiitee" => array (
						"url" => SHIKSHA_HOME . "/mba/exams/kiitee-management",
						"name" => "KIITEE",
						"param"=> 'kiitee'
				) 
		),
		2 => array (
				"ts-eamcet" => array (
						"url" => SHIKSHA_HOME . "/b-tech/exams/ts-eamcet",
						"name" => "TS EAMCET",
						"param"=> 'ts-eamcet'
				),
				"ap-eamcet" => array (
						"url" => SHIKSHA_HOME . "/b-tech/exams/ap-eamcet",
						"name" => "AP EAMCET",
						"param"=> 'ap-eamcet'
				),
				"upsee" => array (
						"url" => SHIKSHA_HOME . "/b-tech/exams/upsee",
						"name" => "UPSEE",
						"param"=> 'upsee'
				),
				"comedk" => array (
					"url" => SHIKSHA_HOME . "/b-tech/exams/comedk-uget ",
						"name" => "COMEDK",
						"param"=> 'comedk'
				),
				"wbjee" => array (
						"url" => SHIKSHA_HOME . "/b-tech/exams/wbjee",
						"name" => "WBJEE",
						"param"=> 'wbjee'
				),
				"keam" => array (
						"url" => SHIKSHA_HOME . "/b-tech/exams/keam",
						"name" => "KEAM",
						"param"=> 'keam'
				),
				"rpet" => array (
						"url" => SHIKSHA_HOME . "/b-tech/exams/rpet",
						"name" => "RPET",
						"param"=> 'rpet'
				),
				"tnea" => array (
						"url" => SHIKSHA_HOME . "/b-tech/exams/tnea",
						"name" => "TNEA",
						"param"=> 'tnea'
				),
				"viteee" => array (
						"url" => SHIKSHA_HOME . "/engineering/exams/vitmee",
						"name" => "VITEEE",
						"param"=> 'viteee'
				),
				"kcet" => array (
						"url" => SHIKSHA_HOME . "/b-tech/exams/kcet",
						"name" => "KCET",
						"param"=> 'kcet'
				),
				"srmjeee" => array (
						"url" => SHIKSHA_HOME . "/b-tech/exams/srmjeee-ug",
						"name" => "SRMJEEE",
						"param"=> 'srmjeee'
				),
				"aeee" => array (
						"url" => SHIKSHA_HOME . "/b-tech/exams/aeee",
						"name" => "AEEE",
						"param"=> 'aeee'
				),
				"upes-eat" => array (
						"url" => SHIKSHA_HOME . "/b-tech/exams/upes-eat",
						"name" => "UPES-EAT",
						"param"=> 'upes-eat'
				),
				"pessat" => array (
						"url" => SHIKSHA_HOME . "/b-tech/exams/pessat",
						"name" => "PESSAT",
						"param"=> 'pessat'
				),
				"vitmee" => array (
						"url" => SHIKSHA_HOME . "/engineering/exams/vitmee",
						"name" => "VITMEE",
						"param"=> 'vitmee'
				),
				"jcece" => array (
						"url" => SHIKSHA_HOME . "/b-tech/exams/jcece",
						"name" => "JCECE",
						"param"=> 'jcece'
				),
				"lpu-nest" => array (
						"url" => SHIKSHA_HOME . "/b-tech/exams/lpu-nest",
						"name" => "LPU NEST",
						"param"=> 'lpu-nest'
				),
				"gate" => array (
						"url" => SHIKSHA_HOME . "/b-tech/exams/gate",
						"name" => "GATE",
						"param"=> 'gate'
				),
				"mu-oet" => array (
						"url" => SHIKSHA_HOME . "/b-tech/exams/mu-oet",
						"name" => "MU-OET",
						"param"=> 'mu-oet'
				),
				"aueet" => array (
						"url" => SHIKSHA_HOME . "/b-tech/exams/alliance-aueet",
						"name" => "AUEET",
						"param"=> 'aueet'
				),
				"nata" => array (
						"url" => SHIKSHA_HOME . "/b-tech/exams/nata",
						"name" => "NATA",
						"param"=> 'nata'
				),
				"ojee" => array (
						"url" => SHIKSHA_HOME . "/b-tech/exams/ojee",
						"name" => "OJEE",
						"param"=> 'ojee'
				)
				
			) 
		);
    global $mbaExamPageLinks;
    $mbaExamPageLinks = array (
    		1 => array (
    				"cat" => array (
    						"url" => SHIKSHA_HOME . "/mba/exams/cat",
    						"name" => "CAT",
						"param"=> 'cat'
    				),
    				"mat" => array (
    						"url" => SHIKSHA_HOME . "/mba/exams/mat",
    						"name" => "MAT",
						"param"=> 'mat'
    				),
    				"cmat" => array (
    						"url" => SHIKSHA_HOME . "/mba/exams/cmat",
    						"name" => "CMAT",
						"param"=> 'cmat'
    				),
    				"xat" => array (
    						"url" => SHIKSHA_HOME . "/mba/exams/xat",
    						"name" => "XAT",
						"param"=> 'xat'
    				)
    		),
    		2 => array (
    				"snap" => array (
    						"url" => SHIKSHA_HOME . "/mba/exams/snap",
    						"name" => "SNAP",
						"param"=> 'snap'
    				),
    				"nmat" => array (
    						//"url" => SHIKSHA_HOME . "/mba/exams/nmat",
    						"name" => "NMAT",
						"param"=> 'nmat'
    				),
    				"iift" => array (
    						"url" => SHIKSHA_HOME . "/mba/exams/iift",
    						"name" => "IIFT",
						"param"=> 'iift'
    				),
    				"ibsat" => array (
    						//"url" => SHIKSHA_HOME . "/mba/exams/ibsat",
    						"name" => "IBSAT",
						"param"=> 'ibsat'
    				),
					"pessat-mba" => array (
						"url" => SHIKSHA_HOME . "/mba/exams/pessat-mba",
						"name" => "PESSAT MBA",
						"param"=> 'pessat-mba'
					),
					"atma" => array (
						"url" => SHIKSHA_HOME . "/mba/exams/atma",
						"name" => "ATMA",
						"param"=> 'atma'
					),
					"kiitee-management" => array (
						"url" => SHIKSHA_HOME . "/mba/exams/kiitee-management",
						"name" => "KIITEE MANAGEMENT",
						"param"=> 'kiitee-management'
					),
					"micat" => array (
						"url" => SHIKSHA_HOME . "/mba/exams/micat",
						"name" => "MICAT",
						"param"=> 'micat'
					),
    				"ojee-mba" => array (
    						"url" => SHIKSHA_HOME . "/mba/exams/ojee-mba",
    						"name" => "OJEE MBA",
    						"param"=> 'ojee-mba'
    				)
    		)
    );

    define('COLLEGE_PREDICTOR_BASE_URL',SHIKSHA_HOME.'/b-tech/resources');
	define('COLLEGE_PREDICTOR_BASE_URL_',SHIKSHA_HOME.'/b-tech/resources');
	define('COLLEGE_PREDICTOR_BASE_URL',SHIKSHA_HOME.'/b-tech/resources');
	define('RANK_PREDICTOR_BASE_URL',SHIKSHA_HOME.'/b-tech/resources');


    global $collegePredictorExams;
    $collegePredictorExams = array(
                "jee-mains"=>array("url"=>COLLEGE_PREDICTOR_BASE_URL."/jee-mains-college-predictor","name"=>"JEE MAIN ".date('Y')." College predictor"),
                "kcet"=>array("url"=>COLLEGE_PREDICTOR_BASE_URL."/kcet-college-predictor","name"=>"KCET ".date('Y')." College predictor"),
                "comedk"=>array("url"=>COLLEGE_PREDICTOR_BASE_URL."/comedk-college-predictor","name"=>"COMEDK ".date('Y')." College predictor"),
                                "keam"=>array("url"=>COLLEGE_PREDICTOR_BASE_URL."/keam-college-predictor","name"=>"KEAM ".date('Y')." College predictor"),
                                "wbjee"=>array("url"=>COLLEGE_PREDICTOR_BASE_URL."/wbjee-college-predictor","name"=>"WBJEE ".date('Y')." College predictor"),
                                "mppet"=>array("url"=>COLLEGE_PREDICTOR_BASE_URL."/mppet-college-predictor","name"=>"MPPET ".date('Y')." College predictor"),
                                "cgpet"=>array("url"=>COLLEGE_PREDICTOR_BASE_URL."/cgpet-college-predictor","name"=>"CGPET ".date('Y')." College predictor"),
                                "tnea"=>array("url"=>COLLEGE_PREDICTOR_BASE_URL."/tnea-college-predictor","name"=>"TNEA ".date('Y')." College predictor"),
                                "ptu"=>array("url"=>COLLEGE_PREDICTOR_BASE_URL."/ptu-college-predictor","name"=>"PTU ".date('Y')." College predictor"),
                                "upsee"=>array("url"=>COLLEGE_PREDICTOR_BASE_URL."/upsee-college-predictor","name"=>"UPSEE ".date('Y')." College predictor"),
                                "mhcet"=>array("url"=>COLLEGE_PREDICTOR_BASE_URL."/mhcet-college-predictor","name"=>"MHCET ".date('Y')." College predictor"),
                                "hstes"=>array("url"=>COLLEGE_PREDICTOR_BASE_URL."/hstes-college-predictor","name"=>"HSTES ".date('Y')." College predictor"),
                                "ap-eamcet"=>array("url"=>COLLEGE_PREDICTOR_BASE_URL."/ap-eamcet-college-predictor","name"=>"AP-EAMCET ".date('Y')." College predictor"),
                                "ts-eamcet"=>array("url"=>COLLEGE_PREDICTOR_BASE_URL."/ts-eamcet-college-predictor","name"=>"TS-EAMCET ".date('Y')." College predictor"),
                                "ojee"=>array("url"=>COLLEGE_PREDICTOR_BASE_URL."/ojee-college-predictor","name"=>"OJEE ".date('Y')." College predictor"),                                
                                "bitsat"=>array("url"=>COLLEGE_PREDICTOR_BASE_URL."/bitsat-college-predictor","name"=>"BITSAT ".date('Y')." College predictor"),
                                "ggsipu"=>array("url"=>COLLEGE_PREDICTOR_BASE_URL."/ggsipu-college-predictor","name"=>"GGSIPU ".date('Y')." College predictor"),
                                "gujcet"=>array("url"=>COLLEGE_PREDICTOR_BASE_URL."/gujcet-college-predictor","name"=>"GUJCET ".date('Y')." College predictor"),
                                "nift"=>array("url"=>"/design/resources/nift-college-predictor","name"=>"NIFT ".date('Y')." College predictor"),
                                "nchmct"=>array("url"=>"/hospitality-travel/resources/nchmct-college-predictor","name"=>"NCHMCT ".date('Y')." College predictor"),
                                "mahcet"=>array("url"=>"/mba/resources/mahcet-college-predictor","name"=>"MAHCET ".date('Y')." College predictor"),
                                "clat"=>array("url"=>"/law/resources/clat-college-predictor","name"=>"CLAT ".date('Y')." College predictor")



      );

    global $rankPredictorExams;
    $rankPredictorExams = array(
                "jee-mains"=>array("url"=>RANK_PREDICTOR_BASE_URL."/jee-main-rank-predictor","name"=>"JEE MAIN ".date('Y')." Rank predictor"),
				"comedk"=>array("url"=>RANK_PREDICTOR_BASE_URL."/comedk-rank-predictor","name"=>"COMEDK ".date('Y')." Rank predictor"),
				"jee-advanced"=>array("url"=>RANK_PREDICTOR_BASE_URL."/jee-advanced-rank-predictor","name"=>"JEE Advanced ".date('Y')." Rank predictor")

      );

      /* this array has been added to accomodate exam scores(that are in form of grades) in tUserEducation.
       * with this array grades can be mapped to their respective numerical value which can be then saved in tUserEducation
       */
      global $examGrades;
      $examGrades = array(
                "CAE"=>array("A"=>1,"B"=>2,"C"=>3,1=>"A",2=>"B",3=>"C")
      );
      
      /*this array has been added to accomodate exam scores(that are in form of float) in tUserEducation.*/
      global $examFloat;
      $examFloat = array('IELTS' => TRUE);
      
      global $studyAbroadPopularCourses;
      $studyAbroadPopularCourses = array(
			"1508"  => "Master of Business Administration",
			"1509"  => "Master of Science",
			"1510"  => "Bachelor of Engineering",
			"11837" => "Masters in Engineering Management",
			"11838" => "Masters in Pharmacy",
			"11839" => "Master in Finance",
			"11840" => "Masters of Design",
			"11841" => "Master of Fine Arts",
			"11842" => "Masters in Engineering",
			"11843" => "Bachelor of Science",
			"11844" => "Bachelor of Business Administration",
			"11845" => "Bachelor of Medicine",
			"11846" => "Bachelor of Hotel Management",
			"11847" => "Master of Architecture",
			"11848" => "Master in Management",
			"11849" => "Master of International Management",
			"11850" => "Master of Applied Science",
			"11851" => "Master of Arts"
      );
      
      global $studyAbroadPopularCountries;
      $studyAbroadPopularCountries = array(
	    "3" => "USA",
	    "8" => "Canada",
	    "5" => "Australia",
	    "4" => "UK",
	    "6" => "Singapore",
	    "7" => "New Zealand"
      );
      
const CATEGORY_ENGINEERING = 240;
const CATEGORY_SCIENCE     = 242;
const CATEGORY_BUSINESS    = 239;
const CATEGORY_HUMANITIES  = 244;
const CATEGORY_MEDICINE    = 243;
const CATEGORY_COMPUTERS   = 241;

//subcategory constants
const SUB_CATEGORY_ANIMATION_AND_DESIGN = 276;

//Desired Courses
const DESIRED_COURSE_MBA    = 1508;
const DESIRED_COURSE_MS     = 1509;
const DESIRED_COURSE_BTECH  = 1510 ;
const DESIRED_COURSE_MEM    = 11837;
const DESIRED_COURSE_MPHARM = 11838;
const DESIRED_COURSE_MFIN   = 11839;
const DESIRED_COURSE_MDES   = 11840;
const DESIRED_COURSE_MFA    = 11841;
const DESIRED_COURSE_MENG   = 11842;
const DESIRED_COURSE_BSC    = 11843;
const DESIRED_COURSE_BBA    = 11844;
const DESIRED_COURSE_MBBS   = 11845;
const DESIRED_COURSE_BHM    = 11846;
const DESIRED_COURSE_MARCH  = 11847;
const DESIRED_COURSE_MIS    = 11848;
const DESIRED_COURSE_MIM    = 11849;
const DESIRED_COURSE_MASC   = 11850;
const DESIRED_COURSE_MA     = 11851;

   /**** STUDY ABROAD ** popolar course to category mapping ****/
   	global $studyAbroadPopularCourseToCategoryMapping;
    $studyAbroadPopularCourseToCategoryMapping = array(
					DESIRED_COURSE_MBA    => array(CATEGORY_BUSINESS),
					DESIRED_COURSE_MS     => array(
												CATEGORY_ENGINEERING,
												CATEGORY_SCIENCE,
												CATEGORY_COMPUTERS
											),
					DESIRED_COURSE_BTECH  => array(
												CATEGORY_ENGINEERING,
												CATEGORY_SCIENCE,
												CATEGORY_COMPUTERS
											),
					DESIRED_COURSE_MEM    => array(CATEGORY_ENGINEERING),
					DESIRED_COURSE_MPHARM => array(CATEGORY_SCIENCE),
					DESIRED_COURSE_MFIN   => array(CATEGORY_BUSINESS),
					DESIRED_COURSE_MDES   => array(
												CATEGORY_ENGINEERING,
												CATEGORY_HUMANITIES
											),
					DESIRED_COURSE_MFA    => array(CATEGORY_HUMANITIES),
					DESIRED_COURSE_MENG   => array(CATEGORY_ENGINEERING),
					DESIRED_COURSE_BSC    => array(CATEGORY_SCIENCE),
					DESIRED_COURSE_BBA    => array(CATEGORY_BUSINESS),
					DESIRED_COURSE_MBBS   => array(CATEGORY_MEDICINE),
					DESIRED_COURSE_BHM    => array(CATEGORY_MEDICINE),
					DESIRED_COURSE_MARCH  => array(CATEGORY_ENGINEERING),
					DESIRED_COURSE_MIS    => array(CATEGORY_COMPUTERS),
					DESIRED_COURSE_MIM    => array(CATEGORY_BUSINESS),
					DESIRED_COURSE_MASC   => array(CATEGORY_SCIENCE,CATEGORY_ENGINEERING),
					DESIRED_COURSE_MA     => array(CATEGORY_HUMANITIES,CATEGORY_BUSINESS)
	);
	global $studyAbroadPopularCourseToLevelMapping;
	$studyAbroadPopularCourseToLevelMapping = array(
		DESIRED_COURSE_MBA    => "Masters",
		DESIRED_COURSE_MS     => "Masters",
		DESIRED_COURSE_BTECH  => "Bachelors",
		DESIRED_COURSE_MEM    => "Masters",
		DESIRED_COURSE_MPHARM => "Bachelors",
		DESIRED_COURSE_MFIN   => "Masters",
		DESIRED_COURSE_MDES   => "Masters",
		DESIRED_COURSE_MFA    => "Masters",
		DESIRED_COURSE_MENG   => "Masters",
		DESIRED_COURSE_BSC    => "Bachelors",
		DESIRED_COURSE_BBA    => "Bachelors",
		DESIRED_COURSE_MBBS   => "Bachelors",
		DESIRED_COURSE_BHM    => "Bachelors",
		DESIRED_COURSE_MARCH  => "Masters",
		DESIRED_COURSE_MIS    => "Masters",//?????array(CATEGORY_COMPUTERS),
		DESIRED_COURSE_MIM    => "Masters",
		DESIRED_COURSE_MASC   => "Masters",
		DESIRED_COURSE_MA     => "Masters"
	 );
    /**
     * STUDY ABROAD BUDGET MAPPING TO REPRESENTABLE TEXT
     */
    $budgetToTextArray = array(
        0   => '0-20 Lakh',
        20  => '20-40 Lakh',
        40  => '40-60 Lakh',
        60  => '60-80 Lakh',
        80  => '80L-1 Crore',
        100 => 'More than 1 Crore'
    );
      

    $excludedClientsForResponseLead = array(
		1463240, 965633
	);

    /*
     * feedback button flag/data
     */
    global $feedbackArray;
    $feedbackArray = array(
	'showFeedback' => true);

define("EN_LOG_FLAG", 0);
define("EN_CP_LOG_FILENAME", "/tmp/categoryPagePerformace_4Sept.log");

        /****
	 *  campus rep incentives for mba
         *  h = hours
         *  d = day
         *  ex: 2h = 2 hours, 1d = 1 day
         *  day*hr*min*sec = (1*24*60*60)
         ***/
       
    global $mywalletAttributesArray;
	    $mywalletAttributesArray = array('0' => array('timeType' => '2h', 'time' => (2*60*60),    'money'=>75,'featured'=>75),
                                          '1' => array('timeType' => '1d', 'time' => (1*24*60*60), 'money'=>50,'featured'=>50),
                                          '2' => array('timeType' => '3d', 'time' => (3*24*60*60), 'money'=>25,'featured'=>25),
                                          '3' => array('timeType' => '5d', 'time' => (5*24*60*60), 'money'=>10,'featured'=>0),
                                          '4' => array('timeType' => '7d', 'time' => (7*24*60*60), 'money'=>5, 'featured'=>0),
                                          '5' => array('timeType' => '10d','time' => (10*24*60*60),'money'=>3, 'featured'=>0),
                                          '6' => array('timeType' => '30d','time' => (30*24*60*60),'money'=>0, 'featured'=>0)
                                         );


/**
 * blogParser date
 */
define("BLOG_PARSER_DATE", "2014-01-01 00:00:00");

        /****
	 *  campus rep incentives for engineering
         *  h = hours
         *  d = day
         *  ex: 2h = 2 hours, 1d = 1 day
         *  day*hr*min*sec = (1*24*60*60)
         ***/
       
    global $mywalletEnginerringIncentive;
	    $mywalletEnginerringIncentive = array('0' => array('timeType' => '6h', 'time' => (6*60*60),    'money'=>50,'featured'=>50),
                                          '1' => array('timeType' => '1d', 'time' => (1*24*60*60), 'money'=>25,'featured'=>25),
                                          '2' => array('timeType' => '3d', 'time' => (3*24*60*60), 'money'=>10,'featured'=>10),
                                          '3' => array('timeType' => '5d', 'time' => (5*24*60*60), 'money'=>5,'featured'=>5),
                                          '4' => array('timeType' => '7d', 'time' => (7*24*60*60), 'money'=>0, 'featured'=>0),
                                          '5' => array('timeType' => '10d','time' => (10*24*60*60),'money'=>0, 'featured'=>0),
                                          '6' => array('timeType' => '30d','time' => (30*24*60*60),'money'=>0, 'featured'=>0)
                                         );


/**********  Section : National CMS Tab IDs ***********/
define("RESPONSE_VIEWER_TAB_ID"         , 39);
define("EXAM_RESPONSE_VIEWER_TAB_ID"         , 1041);
define("EXAM_PAGES_TAB_ID"                    , 204);
define("CHP_PAGES_TAB_ID"                    , 1045);
define("LMS_PORTING_TAB_ID"                 , 778);
define("LMS_PORTING_CUSTOM_LOCATION_ID"     , 900);
define("ADMIN_REPORT_TAB_ID",		 1012);
define("PORTING_MIS_TAB_ID"                 , 779);
define("SMART_DASHBOARD_TAB_ID"     , 781);
define("SMART_REPORT_TAB_ID"              , 782);
define("SMART_CLIENT_EXPECTATION_TAB_ID"    , 783);
define("SMART_CLIENT_LOGIN_TAB_ID"    , 787);
define("CR_MODERATOR_TAB_ID" ,802);
define("CR_MODERATOR_USER_GROUP" ,'CRModerator');
define("SMART_SODETAILS_TAB_ID", 1046);
define("OAF_PORTING_TAB_ID", 1048);
define("OAF_MIS_PORTING_TAB_ID", 1049);
define("ACCESS_CLIENT_LISTING_DETAILS_TAB_ID", 1051);
define('PORTING_UPLOAD_CSV_TAB_ID',1052);

/*
 * RMS counsellor SMS limit per user , i.e. restrict from sending more than 25 sms per day for each user's request callback
 */
define("COUNSELLOR_SMS_LIMIT_PER_USER", 25);

define('RANKING_PAGE_MODULE','rankingV2');
define('NEW_RANKING_PAGE', true);
define('OVERALL_PARAM','Overall Rank');
define('RANKING_SHIKSHA_DEFAULT_PUBLISHER', 1);

/*
 * Institue Phone number for call back
 * mapping of phone numbers with institute id
 */

global $institutePhoneNumber;
$institutePhoneNumber[3698] = array('08028390434'); //institute number
$institutePhoneNumber[35619] = array('18002099727');
$institutePhoneNumber[36115] = array('04427197573');
$institutePhoneNumber[31938] = array('08030938000','08030938001','08030938002','08030938003');
$institutePhoneNumber[38043] = array('02226440096','02226440057');
$institutePhoneNumber[45420] = array('02261439207');
$institutePhoneNumber[36115] = array('08939814624','08939814625','08939814407','08939814408','08939814409');
$institutePhoneNumber[34457] = array('09358189714');
$institutePhoneNumber[34723] = array('08396907284');
$institutePhoneNumber[24356] = array('07895090992');
$institutePhoneNumber[37288] = array('09561453777', '09503999259', '08975752904');
$institutePhoneNumber[30613] = array('08130991446');
$institutePhoneNumber[38195] = array('08800392713', '08800392711');
$institutePhoneNumber[36071] = array('09929013219');
$institutePhoneNumber[37663] = array('07032806788', '09959968904', '09177605337');
$institutePhoneNumber[47641] = array('09881128773', '02025431972', '02025441524');
$institutePhoneNumber[33940] = array('9884052079');
$institutePhoneNumber[48006] = array('9866770279', '9700500087');
$institutePhoneNumber[24289] = array('09717059132', '09873251723', '09999774237');
$institutePhoneNumber[25218] = array('8447744044', '8447744077');
$institutePhoneNumber[26485] = array('9997511626');
$institutePhoneNumber[29963] = array('9212335443');
$institutePhoneNumber[30488] = array('9999210334', '9999210335');
$institutePhoneNumber[26485] = array('9910058209');
$institutePhoneNumber[32277] = array('9949507969', '9391932129');




global $newsletterTemplateIdsArray;
$newsletterTemplateIdsArray = array('3148','4725','4756','4757','6111','6112','6113','6114','6115','6116','6117','6118','6119','6121','6122','6123','6751','8438','9432','9433','9436','9437','9438','9439','9802','9803','11432','11433','11434','11835','11836','11837','12688','23859','23858','23857','34454','34455','34456');

global $mailerIdArrayForMEA;
$mailerIdArrayForMEA = array(57013,57014,57015,57016,57017,57018,57019,57020,57021,57022,57023,57024,57025,57026,57027,57028,57029,57030,57031,57032,57033,57034,57035,57036,57037,57038,57039,57040,57041,57042,57043,57044,57045,57046,57047,57048,57049,57050,57051,57052);

/*
 * Constants for shiksha's Coupon System
 */
define('COUPON_CODE_PREFIX','RC');
define('DEFAULT_COUPON_CODE','SHK101');
define('SHOW_CAPTCHA_MOBILE_SA',FALSE);

/*
 * Set this flag to 1 in case paytm functionality is to be enabled on online form flow
 */
define("OF_PAYTM_INTEGRATION_FLAG" , 0);
global $usersForPaytmTesting;
$usersForPaytmTesting = array(11);
/*
Constant for MBA subcategory Id
 */
define("MBA_SUBCAT_ID" , 23);

/*
Constant for Engineering subcategory Id
 */
define("ENGINEERING_SUBCAT_ID",56);

define("MOB_HAMBURGER_CUSTOMIZE",true);

/** Log Performance Data */
define("LOG_PERFORMANCE_DATA",0);
define("LOG_PERFORMANCE_DATA_FILE_NAME",'/tmp/logCMSPerformanceData.log');

if($_REQUEST['debugger'] == 1) {
	define("LOG_SEARCH_PERFORMANCE_DATA", 0);
	define("DEBUGGER", 1);
} else {
	define("LOG_SEARCH_PERFORMANCE_DATA", 0);
	define("DEBUGGER", 0);
}

if($_REQUEST['debugger'] == 1) {
	define("LOG_CL_PERFORMANCE_DATA", 0);
	define("DEBUGGER", 1);
} else {
    define("LOG_CL_PERFORMANCE_DATA", 0);
    define("DEBUGGER", 0);
}

define("SHOW_QUESTION_SEARCH", true);

if($_COOKIE['ci_mobile'] == 'mobile') {
	//define("LOG_CL_PERFORMANCE_DATA", 0);
    define("LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME",'/tmp/log_search_page_mobile.log');
	define("LOG_CL_PERFORMANCE_DATA_FILE_NAME",'/tmp/log_cl_page_mobile.log');
} else {
    //define("LOG_CL_PERFORMANCE_DATA", 0);
	define("LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME",'/tmp/log_search_page.log');
	define("LOG_CL_PERFORMANCE_DATA_FILE_NAME",'/tmp/log_cl_page.log');
}

define("LOG_POPULARITY_PERFORMANCE_DATA_FILE_NAME",'/tmp/log_popularity.log');

define("GET_SEARCH_RESULT_COURSES_AS_FACET", 1);

define("LOG_RANKING_PERFORMANCE_DATA", 0);
define("LOG_RANKING_PERFORMANCE_DATA_FILE_NAME",'/tmp/log_ranking_page.log');

define("LOG_HOMEPAGE_PERFORMANCE_DATA", 0);
define("LOG_HOMEPAGE_PERFORMANCE_DATA_FILE_NAME",'/tmp/log_home_page.log');
define("LOG_CR_FORM_FILE_NAME",'/tmp/log_CR_form.log');
define("LOG_User_Point_Deducation",'/tmp/log_user_pints_ded.log');
define("LOG_User_Level_Updation",'/tmp/log_user_points_updation.log');

global $pageTypeResponseActionMapping;
$pageTypeResponseActionMapping = array(
			'ND_MyShortlist'        => 'ND_myshortlist_shortlist',
			'ND_Category'           => 'ND_category_shortlist',
			'ND_Ranking'            => 'ND_ranking_shortlist',
			'ND_CourseListing'      => 'ND_course_shortlist',
			'ND_CategoryReco'       => 'ND_CategoryReco_shortlist',
			'ND_NaukriTool'         => 'ND_CareerCompass_Shortlist',
			'ND_SERP'               => 'ND_SERP_shortlist',
			'ND_Compare'            => 'ND_Compare_shortlist',
			'ND_MyShortlist_Search' => 'ND_myshortlist_shortlist',
			
			'NM_Category'           => 'NM_category_shortlist',
			'NM_SERP'               => 'NM_SERP_shortlist',
			'NM_ALL_COURSES'        => 'NM_ALL_COURSES_shortlist',
			'NM_CourseListing'      => 'NM_course_shortlist',
			'NM_MyShortlist'        => 'NM_myshortlist_shortlist',
			'NM_Ranking'            => 'NM_ranking_shortlist',
			'NM_Compare'            => 'NM_Compare_shortlist',
			'NM_CareerCompass'      => 'NM_CareerCompass_shortlist',
			'NM_AlsoViewed'         => 'NM_AlsoViewed_shortlist',
			'NM_MyShortlist_Search' => 'NM_myshortlist_shortlist',
			'mobileCategoryPage'    => 'MOB_Category_Shortlist'
		);

define("SHIKSHA_MBA_CALENDAR",SHIKSHA_HOME.'/mba/resources/exam-calendar');
define("SHIKSHA_ENGINEERING_CALENDAR",SHIKSHA_HOME.'/engineering/resources/exam-calendar');

global $myShortlistStaticExamList;
$myShortlistStaticExamList = array("CAT","MAT","XAT","CMAT","GMAT","SNAP","NMAT","IBSAT","IIFT","KMAT","IRMA");

global $popularCitiesCategoryPage; //list of popular cities sorted on name
$popularCitiesCategoryPage = array(
					'23' => array(
						'55' => 'Bhopal',
						'912' => 'Bhubaneswar',
						'63' => 'Chandigarh',
						'67' => 'Coimbatore',
						'73' => 'Dehradun',
						'106' => 'Indore',
						'109' => 'Jaipur',
						'138' => 'Lucknow',
						'156' => 'Nagpur',
						'12140' => 'Trivandrum'
					),
					'56' => array(
						'109' => 'Jaipur',
						'55' => 'Bhopal',
						'912' => 'Bhubaneswar',
						'63' => 'Chandigarh',
						'67' => 'Coimbatore',
						'73' => 'Dehradun',
						'84' => 'Faridabad',
						'30' => 'Ahmedabad',
						'122' => 'Kanpur',
						'138' => 'Lucknow',
						'146' => 'Meerut',
						'156' => 'Nagpur',
						'2631' => 'Sonepat'
					)
				);
global $categoryForCollegeInstituteChange;
$categoryForCollegeInstituteChange = array("2","3");
/* Consultant Enquiry Interval (number of days) in which new enquiry cant be generated by same user on same consultant */
define('CONSULTANT_ENQUIRY_INTERVAL_IN_DAYS',1);
define("LDB_COURSE_MAPPING_LIMIT", 20);
define('ABROAD_USER_TRACKING',1);
global $subCategoriesEntranceExamGNB;
$subCategoriesEntranceExamGNB = array('MBA' => array('value' => 'MBA Entrance Exams','id' => 'mbaEntranceExamMenu'),
									  'Engineering' => array('value' => 'Engineering Entrance<br/> Exams' ,'id' => 'engEntranceExamMenu','style'=>'height:43px;'),
									  'Design' => array('value' => 'Design Entrance Exams','id' => 'designEntranceExamMenu'),
									  'Hospitality' => array('value' => 'Hotel Management<br/> Entrance Exams','id' => 'hospitalityEntranceExamMenu','style'=>'height:43px;'),
									  'Law' => array('value' => 'Law Entrance Exams','id' => 'lawEntranceExamMenu'),
									  'Mass Communication' => array('value' => 'Mass Communication Exams','id' => 'massCommunicationExamMenu')
									);
global $studyAbroadStudentGuideCountrySpecific;
$studyAbroadStudentGuideCountrySpecific = array(5 => 13,
                                                8 => 14,
                                                3 => 15,
                                                4 => 16,
                                                7 => 245,
                                                6 => 266,
                                                12 => 269,
                                                9 => 273);
global $paymentPaymentExclusionCourses;
$paymentPaymentExclusionCourses = array(2446,110897,176266,178023,178034,187858,231579);

global $studyAbroadStaticUniversities;
$studyAbroadStaticUniversities = array(
										array('title'=>'Universities in USA',
											  'url'  =>'usa/universities'),
										array('title'=>'Universities in Canada',
											  'url'  =>'canada/universities'),
										array('title'=>'Universities in Australia',
											  'url'  =>'australia/universities'),
										array('title'=>'Universities in UK',
											  'url'  =>'uk/universities'),
										array('title'=>'Universities in Germany',
											  'url'  =>'germany/universities'),
										array('title'=>'Universities in Singapore',
											  'url'  =>'singapore/universities'),
										array('title'=>'Universities in New Zealand',
											  'url'  =>'new-zealand/universities'),
									  );

global $studyAbroadStaticExams;
$studyAbroadStaticExams = array(
										array('title'=>'All about IELTS',
											  'url'  =>'ielts-abroadexam323'),
										array('title'=>'All about TOEFL',
											  'url'  =>'toefl-abroadexam320'),
										array('title'=>'All about GRE',
											  'url'  =>'gre-abroadexam322'),
										array('title'=>'All about GMAT',
											  'url'  =>'gmat-abroadexam321'),
										array('title'=>'All about SAT',
											  'url'  =>'sat-abroadexam324')
									  );

global $studyAbroadStaticStudentGuides;
$studyAbroadStaticStudentGuides = array(
										array('title'=>'Guide for USA',
											  'url'  =>'student-guide-to-the-united-states-of-america-usa-guidepage-15'),
										array('title'=>'Guide for Canada',
											  'url'  =>'student-guide-to-canada-guidepage-14'),
										array('title'=>'Guide for UK',
											  'url'  =>'student-guide-to-the-united-kingdom-uk-guidepage-16'),
										array('title'=>'Guide for Australia',
											  'url'  =>'student-guide-to-australia-guidepage-13'),
										array('title'=>'Guide for New Zealand',
											  'url'  =>'student-guide-to-new-zealand-nz-guidepage-245'),
										array('title'=>'Guide for Singapore',
											  'url'  =>'student-guide-to-singapore-guidepage-266'),
										array('title'=>'Guide for Germany',
											  'url'  =>'student-guide-to-germany-guidepage-273'),
										array('title'=>'Guide for France',
											  'url'  =>'student-guide-to-france-guidepage-269'),
										array('title'=>'Canada vs Australia',
											  'url'  =>'country-comparison-canada-vs-australia-guidepage-314'),
										array('title'=>'USA vs UK',
											  'url'  =>'country-comparison-usa-vs-uk-guidepage-303'),
									  );

define('MENTORSHIP_PROGRAM_FLAG',false);
define('IIM_CALL_INTERLINKING_FLAG',true);
define('MBA_COLLEGE_REVIEW', 'mba/resources/college-reviews');
define('ENGINEERING_COLLEGE_REVIEW', 'btech/resources/college-reviews');
define('MENTEE_MOBILE_DASHBOARD_URL',SHIKSHA_HOME.'/mCampusAmbassador5/MenteeChatDashboard/menteeDashboard');
define('passwordSalt','8249ba6be2ee399bd9e0a0bb307beea2');

global $subcatToAdvancedFiltersMapping;
$subcatToAdvancedFiltersMapping = array(
										'23' => array(	
														'exams',
														'fees',
														'specialization'
													),
										'56' => array(
														'exams',
														'fees',
														'specialization'
													),
										'59' => array(
														'mode',
														'specialization',
														'exams'
													),
										'31' => array(
														'specialization',
														'courseLevel'
													),
										'55' => array(
														'courseLevel',
														'mode',
														'specialization'
													),
										'25' => array(
														'mode',
														'specialization',
														'exams'
													),
										'68' => array(
														'mode',
														'specialization'
													),
										'26' => array(
														'exams',
														'specialization'
													),
										'238' => array(
														'specialization',
														'mode'
													),
										'32' => array(
														'mode',
														'courseLevel'
													),
										'65' => array(
														'mode',
														'specialization'
													),
										'100' => array(
														'courseLevel',
														'mode'
													),
										'64' => array(
														'mode',
														'specialization'
													)
									);

global $subcatWithMultiSelectSpec;
$subcatWithMultiSelectSpec = array('23', '56');

/* Email ids for super user */
global $superUserEmail;
$superUserEmail = array('teamldb@shiksha.com','mohd.alimkhan@shiksha.com','neha.maurya@shiksha.com','aneeketbarua.shiksha@gmail.com', 'prabhat.sachan@shiksha.com', 'test1qa@gmail.com','jakhodia.nikita@Shiksha.com','ruchika.rathee@shiksha.com','dhruv.chaudhary@shiksha.com'); 

/* config variable to denote whether exam & fee filter on abroad home page are fetched from db or from cache */
define('USE_CACHE_FOR_SA_HOME_MORE_OPTIONS' , TRUE);


global $responseActionTypeMappingMobile;
$responseActionTypeMappingMobile = array(

						'mUser_ShortListed_Course_sa_mobile','MOB_Course_Shortlist','MOB_COMPARE_EMAIL','Request_Callback_sa_mobile',
						'CP_Request_Callback_sa_mobile','Shortlist_Request_Callback_sa_mobile','Asked_Question_On_Listing_MOB',
						'Asked_Question_On_CCHome_MOB','MOB_COMPARE_EBrochure','GetFreeAlert_sa_mobile','MOB_CareerCompass_Ebrochure',
						'download_brochure_free_course_sa_mobile','MOBILEHTML5','MOBILE5_CATEGORY_PAGE','MOBILE5_INSTITUTE_DETAIL_PAGE'.
						'MOBILE5_SIMILAR_INSTITUTE_DETAIL_PAGE','MOBILE5_SIMILAR_COURSE_DETAIL_PAGE','MOBILE5_COURSE_DETAIL_PAGE',
						'MOBILE5_COURSE_DETAIL_PAGE_OTHER','MOBILE5_SEARCH_PAGE','MOBILE5_COLLEGE_PREDICTOR_PAGE','MOBILE5_RANK_PREDICTOR_PAGE',
						'MOBILE5_SHORTLIST_PAGE','MOBILEHTML5_GETEB','RANKING_MOB_ReqEbrochure','mobilesite','mobilesitesearch',
						'Request_E-Brochure_sa_mobile','brochureUnivSARanking_sa_mobile','brochureCourseSARanking_sa_mobile',
						'CP_MOB_Reco_ReqEbrochure','CP_MOB_Reco_ReqEbrochure','CP_Reco_ReqEbrochure_sa_mobile','MOB_COMPARE_VIEWED',
						'LP_MOB_Reco_ReqEbrochure','LP_Reco_AlsoviewLayer_sa_mobile','CP_MOB_Reco_ReqEbrochure','Shortlist_Page_Reco_ReqEbrochure_sa_mobile',
						'CP_Reco_ReqEbrochure_sa_mobile','LP_MOB_Reco_ReqEbrochure','LP_Reco_AlsoviewLayer_sa_mobile',
						'LP_Reco_ReqEbrochure_sa_mobile','LP_Reco_SimilarInstiLayer_sa_mobile','reco_also_view_layer_sa_mobile',
						'RP_Reco_AlsoviewLayer_sa_mobile','RANKING_MOB_Reco_ReqEbrochure','reco_widget_mailer_sa_mobile','SEARCH_MOB_Reco_ReqEbrochure',
						'LP_AdmissionGuide_sa_mobile','Viewed_Listing_sa_mobile','Viewed_Listing_Pre_Reg_sa_mobile','reco_widget_mailer_national_mobile'
						);
define('DB_TRACK_FOR_GA_PARAM_VERIFICATION',false);

define('SOLR_SEARCH_SORT_BY_VIEW_COUNT',false);
define('GET_COURSES_SORTED_ON_NUMBER_OF_INSTITUTES',true);

// Config variable to hold domain names which receive mails from amazon mail service
global $domainsUsingAmazonMailService;
$domainsUsingAmazonMailService = array('apeejay.edu', 'morenaservices.com');

// Config variable to hold email ids of clients who will receive mails from amazon mail service
global $emailidsUsingAmazonMailService;
$emailidsUsingAmazonMailService = array('reformationiasacademy@gmail.com','molly.west@millsaps.edu', 'Info@adtds.com', 'advisor@adtds.com', 'shiksha@kccitm.edu.in', 'kccadmissions@kccitm.edu.in', 'rtrikha@amity.edu');

//College Review Referral Link
define('COLLEGE_REVIEW_REFERRAL_URL',SHIKSHA_HOME.'/college-review-form');
global $workExperience;
$workExperience = array(
"-1"  => "No work experience",
            "0"  => "0 - 1 year",
            "1"  => "1 - 2 years",
            "2"  => "2 - 3 years",
            "3"  => "3 - 4 years",
            "4"  => "4 - 5 years",
            "5"  => "5 - 6 years",
            "6"  => "6 - 7 years",
            "7"  => "7 - 8 years",
            "8"  => "8 - 9 years",
            "9"  => "9 - 10 years",
            "10"  => "> 10 years"
	);

/*Mapping for grade and boad for CBSE (SAapply form) */
global $CBSE_Grade_Mapping;
$CBSE_Grade_Mapping = array('4 - 4.9'=>4, '5 - 5.9'=>5, '6 - 6.9'=>6, '7 - 7.9'=>7, '8 - 8.9'=>8, '9 - 10.0'=>9);

/*Mapping for grade and boad for IGCSE (SAapply form) */
global $IGCSE_Grade_Mapping;
$IGCSE_Grade_Mapping = array('A*'=>1,'A'=>2,'B'=>3, 'C'=>4, 'D'=>5, 'E'=>6,'F'=>7, 'G'=>8);


/*Reverse Mapping for grade and boad for CBSE  */
global $Reverse_CBSE_Grade_Mapping;
$Reverse_CBSE_Grade_Mapping = array('4'=>'4 - 4.9', '5'=>'5 - 5.9', '6'=>'6 - 6.9', '7'=>'7 - 7.9', '8'=>'8 - 8.9', '9'=>'9 - 10.0');

/*Reverse Mapping for grade and boad for IGCSE  */
global $Reverse_IGCSE_Grade_Mapping;
$Reverse_IGCSE_Grade_Mapping = array('1'=>'A*','2'=>'A','3'=>'B', '4'=>'C', '5'=>'D', '6'=>'E','7'=>'F', '8'=>'G');

/*Mapping for grade and boad for ICSE (SAapply form) */
global $ICSE_Grade_Mapping;
$ICSE_Grade_Mapping =array('< than 50%'=>'50','50% to 60%'=>'60','60% to 70%'=> '70','70% to 80%'=>'80','80% to 90%'=>'90','90% or above'=>'100');

global $IBMYP_Grade_Mapping;
$IBMYP_Grade_Mapping = array('28'=>'28','29'=>'29','30'=>'30','31'=>'31','32'=>'32','33'=>'33','34'=>'34','35'=>'35','36'=>'36','37'=>'37','38'=>'38','39'=>'39','40'=>'40','41'=>'41','42'=>'42','43'=>'43','44'=>'44','45'=>'45','46'=>'46','47'=>'47','48'=>'48','49'=>'49','50'=>'50','51'=>'51','52'=>'52','53'=>'53','54'=>'54','55'=>'55','56'=>'56');

global $NIOS_Grade_Mapping;
$NIOS_Grade_Mapping =array('< than 50%'=>'50','50% to 60%'=>'60','60% to 70%'=> '70','70% to 80%'=>'80','80% to 90%'=>'90','90% or above'=>'100');

global $ABROAD_Exam_Grade_Mapping;
$ABROAD_Exam_Grade_Mapping = array(
		'CBSE' => array(
				'4' => '37',
				'5' => '45',
				'6' => '55',
				'7' => '65',
				'8' => '75',
				'9' => '85'
			),
		'IGCSE' => array(
				'1' => '95',
				'2' => '84',
				'3' => '74',
				'4' => '64',
				'5' => '54',
				'6' => '44',
				'7' => '34',
				'8' => '24'
			),
		'IBMYP' => array(
			'28' => '52',
			'29' => '52',
			'30' => '52',
			'31' => '52',
			'32' => '57',
			'33' => '57',
			'34' => '57',
			'35' => '65',
			'36' => '65',
			'37' => '65',
			'38' => '72',
			'39' => '72',
			'40' => '72',
			'41' => '72',
			'42' => '77',
			'43' => '77',
			'44' => '77',
			'45' => '82',
			'46' => '82',
			'47' => '82',
			'48' => '82',
			'49' => '87',
			'50' => '87',
			'51' => '87',		
			'52' => '95',
			'53' => '95',
			'54' => '95',
			'55' => '95',
			'56' => '100'
			)
	);

global $budgetOfStudies;
$budgetOfStudies = array(1=>'< than 10 lakh annually',
								2=>'10 - 20 lakh annually',
								3=>'20 - 30 lakh annually',
								4=>'> than 30 lakh annually');
global $sourceOfFunding;
$sourceOfFunding = array('own'=>'Self-funded','bank'=>'Education Loan');

global $twelfthBoardList;
$twelfthBoardList = array('CBSE'=>"CBSE/ISC/State Boards",'CIE'=>"Cambridge International Examination (CIE)",'IB'=>"International Baccalaureate (IB)");

global $twelfthMarksList;
$twelfthMarksList = array(
	'CBSE'=>array(1=>'< than 50%',2=>'50% to 60%',3=>'60% to 70%',4=>'70% to 80%',5=>'80% to 90%',6=>'90% or above'),
	'CIE'=>array(1=>'A*',2=>"A",3=>"B",4=>"C",5=>"D",6=>"E"),
	'IB'=>array(1=>"< than 24",2=>"24 to 28",3=>"28 to 32",4=>"32 to 35",5=>"36 to 38",6=>"38 or above")
	);
define("RMC_TAB_TUPLE_COUNT",20);
define("RANKING_PAGE_TUPLE_COUNT",10);
define("RANKING_PAGE_FIRSTPAGE_COUNT",25);
define("ABROADRMCLIMIT",7);

global $FACILITY_ID_CSS_ICON_NAME_MAPPING;
/*$FACILITY_ID_CSS_ICON_NAME_MAPPING = array(
		1 => 'library',
		2 => 'wifi',
		3 => 'room',
		4 => 'food',
		5 => 'transport',
		6 => '',
		7 => 'medical',
		8 => 'play',
		9 => 'gym',
		10 => '',
		11 => 'lab',
		12 => '',
	);*/
$FACILITY_ID_CSS_ICON_NAME_MAPPING = array(
		1 => 'library',
		2 => 'wifi',
		3 => 'room',
		9 => 'food',
		10 => 'transport',
		12 => 'medical',
		13 => 'play',
		14 => 'gym',
		15 => 'ac_room',
		16 => 'labs',
		20 => 'design_studio',
		21 => 'moot_court',
		8 => 'convenience',
		19 => 'dance_room',
		18 => 'music_room',
		11 => 'auditorium'
	);

global $Degreee_Pref_Mapping;
$Degreee_Pref_Mapping = array(
							'aicte' => 'AICTE',
							'ugc'   => 'UGC',
							'dec'   => 'DEC'
							);

define('SEARCH_PAGE_URL_PREFIX','/search');
define('SEARCH_PAGE_QUESTION_URL_PREFIX','/search/question');
define('SASEARCH_PAGE_URL_PREFIX','/search-abroad');

global $botsAvoidedForSearch;
$botsAvoidedForSearch = array('pingdom','googlebot','bingbot','ysearch/slurp','trendictionbot','applebot','ltx71','msnbot','ia_archiver','spider','facebookexternalhit','crawler');
$botFlag = 0;

foreach($botsAvoidedForSearch as $bot){
	if(empty($_SERVER['HTTP_USER_AGENT']) || strpos(strtolower($_SERVER['HTTP_USER_AGENT']), $bot) !== false){
		$botFlag = 1;
		break;
	}
}
if($botFlag){
	define('DO_SEARCHPAGE_TRACKING',false);
}
else{
	if($_SERVER['HTTP_X_MOZ'] == 'prefetch' || $_SERVER['HTTP_X_Purpose'] == 'prefetch' || $_SERVER['HTTP_Purpose'] == 'prefetch'){
		define('DO_SEARCHPAGE_TRACKING',false);
	}
	else{
		define('DO_SEARCHPAGE_TRACKING',true);
	}
}

define('DO_TUPLE_TRACKING', true);

global $TupleClickParams;
$TupleClickParams = array(
							'from' => 'fro',
							'trackingSearchId' => 'ts',
							'pagenum' => 'pn',
							'tuplenum' => 'tn',
							'clicktype' => 'ct',
							'listingtypeid' => 'lti',
							'trackingFilterId' => 'tf');

global $searchReductionCriteria;
$searchReductionCriteria = array('1' => 'relax', '2' => 'spellcheck', '3' => 'relaxandspellcheck');

/* Constants to change ISD code in testing environment*/
define('INDIA_ISD_CODE','91-2'); //ISD-countryId
define('TESTING_ISD_CODE',false);
define('TESTING_ISD_CODE_COUNTRY', 'India');

define('SHIKSHA_APPLY_REPORT_START_DATE' , '2016-09-01');

$MMP_Tracking_keyId = array(
							'desktop' => array('indianpage' => '350' , 'abroadpage'=> '352','testpreppage'=> '354'),
							'mobile' => array('indianpage' => '351', 'abroadpage'=> '353','testpreppage'=> '355')
							);
$mobileMMPFormIds = array();

// National Listings Response/ Registration tracking key ids

//Institute Listing Page 
define('DESKTOP_NL_LP_INST_RIGHT_DEB','229');
define('DESKTOP_NL_LP_INST_RIGHT_DEB_RECO','232'); 
define('DESKTOP_NL_LP_INST_MIDDLE_DEB','230');
define('DESKTOP_NL_LP_INST_MIDDLE_DEB_RECO','515');
define('DESKTOP_NL_LP_INST_BELLY_DEB','233');
define('DESKTOP_NL_LP_INST_BELLY_DEB_RECO','516');
define('DESKTOP_NL_LP_INST_LEFT_ALSO_VIEWED_RECO_DEB','462');
define('DESKTOP_NL_LP_INST_LEFT_SIMILAR_RECO_DEB','457');
 // Send Contact Details
define('DESKTOP_NL_LP_INST_TOP_SEND_CONTCT_DTLS','228'); 
define('DESKTOP_NL_LP_INST_BOTTOM_SEND_CONTCT_DTLS','231');

//Course Listing Page //223
 //DEB
define('DESKTOP_NL_LP_COURSE_RIGHT_DEB','219');
define('DESKTOP_NL_LP_COURSE_RIGHT_DEB_RECO','517');
define('DESKTOP_NL_LP_COURSE_MIDDLE_DEB','220');
define('DESKTOP_NL_LP_COURSE_BELLY_DEB','224');
define('DESKTOP_NL_LP_COURSE_BELLY_DEB_RECO','518');
define('DESKTOP_NL_LP_COURSE_LEFT_ALSO_VIEWED_RECO_DEB','226');
define('DESKTOP_NL_LP_COURSE_LEFT_SIMILAR_RECO_DEB','227'); 
define('DESKTOP_NL_LP_COURSE_BELLY_APPLYONLINE_RECO_DEB','225');
 //Shortlist
define('DESKTOP_NL_LP_COURSE_TOP_SHORTLIST','218');
define('DESKTOP_NL_LP_COURSE_MID_SHORTLIST','221');
 // Send Contact Details
define('DESKTOP_NL_LP_COURSE_TOP_SEND_CONTCT_DTLS','217');
define('DESKTOP_NL_LP_COURSE_BOTTOM_SEND_CONTCT_DTLS','222');
define('DESKTOP_NL_COURSE_HOME_PAGE_RIGHT_DEB','250');
//Online Forms
define('DESKTOP_NL_COURSE_PAGE_TOP_APPLY_OF','977');
define('DESKTOP_NL_COURSE_PAGE_ADMISSION_APPLY_OF','978');
define('MOBILE_NL_COURSE_PAGE_TOP_APPLY_OF','986');
define('MOBILE_NL_COURSE_PAGE_ADMISSION_APPLY_OF','987');


//Category Page
	//DEB


//Ranking Page //344 346 347
	//DEB
define('DESKTOP_NL_RNKINGPGE_TUPLE_DEB','234');
define('DESKTOP_NL_RNKINGPGE_TUPLE_DEB_RECO','236');

define('MOBILE_NL_RNKINGPGE_TUPLE_DEB','343');
define('MOBILE_NL_RNKINGPGE_TUPLE_DEB_RECO','345');
	//Shortlist
define('DESKTOP_NL_RNKINGPGE_TUPLE_SHORTLIST','235');
define('MOBILE_NL_RNKINGPGE_TUPLE_COURSESHORTLIST', '344');

//MyShortlist Page
	//DEB
define('DESKTOP_NL_SHORTLIST_HOME_TUPLE_DEB','458');
define('MOBILE_NL_SHORTLIST_HOME_TUPLE_SETLAYER_DEB','254');
define('MOBILE_NL_SHORTLIST_COURSE_DETAIL_PAGE_TUPLE_SETLAYER_DEB','255');

	//Shortlist
define('DESKTOP_NL_SHORTLIST_HOME_SEARCH_SHORTLIST','245');
define('DESKTOP_NL_SHORTLIST_HOME_FINDBYEXAM_SHORTLIST','246');
define('DESKTOP_NL_SHORTLIST_HOME_SIMILAR_SHORTLIST','247');
define('DESKTOP_NL_SHORTLIST_HOME_RECO_BOTTOM_SHORTLIST','247');
define('MOBILE_NL_SHORTLISTPAGE_SHORTLISTRECOCOURSEDETAIL_COURSESHORTLIST', '256');
define('MOBILE_NL_SHORTLISTPAGE_FINDCOLLEGEBYEXAMADDMORE_COURSESHORTLIST', '252');
define('MOBILE_NL_SHORTLISTPAGE_SEARCHCOLLEGEADDMORE_COURSESHORTLIST', '251');
//NAUKRI TOOL /*LEFT*/
	//shortlist
define('DESKTOP_NL_NAUKRITOOL_SHORTLIST','203');
define('DESKTOP_NL_NAUKRITOOL_BOTTOM_SHORTLIST','204');

//DESKTOP EXAM PAGES
define('DESKTOP_NL_EXAM_PAGE_HOME_TOP_REG', '237');
define('DESKTOP_NL_EXAM_PAGE_HOME_BELLY_REG', '238');
define('DESKTOP_NL_EXAM_PAGE_SYLLABUS_BELLY_REG', '239');
define('DESKTOP_NL_EXAM_PAGE_IMP_DATES_TOP_REG', '240');
define('DESKTOP_NL_EXAM_PAGE_RESULTS_REG', '241');
define('DESKTOP_NL_EXAM_PAGE_DISCUSSION_REG', '242');
define('DESKTOP_NL_EXAM_PAGE_PREPTIP_REG', '243');
define('DESKTOP_NL_EXAM_PAGE_NEWS_N_ARTICLE_BELLY_REG', '244');

//DESKTOP SEARCH
define('DESKTOP_NL_SEARCHV2_TUPLE_SHORTLIST', '513');
define('DESKTOP_NL_SEARCHV2_TUPLE_DEB', '512');
define('DESKTOP_NL_SEARCHV2_TUPLE_DEB_RECO', '514');

define('DESKTOP_NL_QUES_SEARCH_UNQUES_FOLLOW','1535');
define('DESKTOP_NL_QUES_SEARCH_AQUES_FOLLOW','1541');
define('DESKTOP_NL_QUES_SEARCH_TAG_FOLLOW','1539');
define('DESKTOP_NL_QUES_SEARCH_UNQUES_WRITE_ANS','1537');
define('DESKTOP_NL_QUES_SEARCH_AQUES_WRITE_ANS','1543');

//DESKTOP CTPG
define('DESKTOP_NL_CTPG_TUPLE_SHORTLIST','215');
define('DESKTOP_NL_CTPG_TUPLE_DEB','213');
define('DESKTOP_NL_CTPG_TUPLE_DEB_RECO','214');
define('DESKTOP_NL_CTPG_TUPLE_SHORTLIST_RECO','216');

//MOBILE SEARCH
define('MOBILE_NL_SEARCHV2_TUPLE_COMPARE','666');
define('MOBILE_NL_SEARCHV2_TUPLE_SHORTLIST','1137');
define('MOBILE_NL_SEARCHV2_TUPLE_DEB','1136');
define('MOBILE_NL_SEARCHV2_TUPLE_COMPARE_RECO','1140');
define('MOBILE_NL_SEARCHV2_TUPLE_SHORTLIST_RECO','1139');
define('MOBILE_NL_SEARCHV2_TUPLE_DEB_RECO','1138');

define('MOBILE_NL_QUES_SEARCH_QUES_FOLLOW','1423');
define('MOBILE_NL_QUES_SEARCH_TAG_FOLLOW','1425');
define('MOBILE_NL_QUES_SEARCH_WRITE_ANS','1427');

//MOBILE CTPG
define('MOBILE_NL_CTPG_TUPLE_COMPARE','272');
define('MOBILE_NL_CATEGORY_TUPLE_COURSESHORTLIST','271');
define('MOBILE_NL_CTPG_TUPLE_DEB','269');
define('MOBILE_NL_CTPG_TUPLE_COMPARE_RECO','1088');
define('MOBILE_NL_CTPG_SHORTLIST_RECO','1089');
define('MOBILE_NL_CTPG_TUPLE_DEB_RECO','273');

//MOBILE ALL COURSES
define('MOBILE_NL_ALL_COURSES_TUPLE_COMPARE','1094');
define('MOBILE_NL_ALL_COURSES_TUPLE_SHORTLIST','1135');
define('MOBILE_NL_ALL_COURSES_TUPLE_DEB','1134');
define('MOBILE_NL_ALL_COURSES_TUPLE_COMPARE_RECO','1143');
define('MOBILE_NL_ALL_COURSES_SHORTLIST_RECO','1142');
define('MOBILE_NL_ALL_COURSES_TUPLE_DEB_RECO','1141');

// COURSE HOME PAGE
//REG
define('DESKTOP_NL_COURSE_HOME_PAGE_RIGHT_REG','249');
//DEB

define('MOBILE_NL_EXAM_PAGE_HOME_TOP_REG','257');
define('MOBILE_NL_EXAM_PAGE_HOME_BELLY_REG','258');

define('MOBILE_NL_EXAM_PAGE_IMP_DATES_TOP_REG','261');
define('MOBILE_NL_EXAM_PAGE_IMP_DATES_BELLY_REG','262');

define('MOBILE_NL_EXAM_PAGE_SYLLABUS_TOP_REG','259');
define('MOBILE_NL_EXAM_PAGE_SYLLABUS_BELLY_REG','260');

define('MOBILE_NL_EXAM_PAGE_NEWS_N_ARTICLE_TOP_REG','283');
define('MOBILE_NL_EXAM_PAGE_NEWS_N_ARTICLE_BELLY_REG','267');

define('MOBILE_NL_EXAM_PAGE_PREPTIP_TOP_REG','284');
define('MOBILE_NL_EXAM_PAGE_PREPTIP_BELLY_REG','266');

define('MOBILE_NL_EXAM_PAGE_RESULTS_TOP_REG','263');
define('MOBILE_NL_EXAM_PAGE_RESULTS_BELLY_REG','264');

define('MOBILE_NL_EXAM_PAGE_DISCUSSION_TOP_REG','282');
define('MOBILE_NL_EXAM_PAGE_DISCUSSION_BELLY_REG','265');

define('DESKTOP_NL_VIEWED_LISTING', '712');
define('DESKTOP_NL_INSTITUTE_VIEWED', '713');
define('DESKTOP_NL_UNIVERSITY_VIEWED', '3219');

define('MAILER_RECO', '778');

define('MOBILE_HAMBURGER_REGISTRATION', '797');

define('RECO_WIDGET_MAILER_NATIONAL', '918');
define('RECO_WIDGET_MAILER_NATIONAL_MOBILE', '919');

define('COMPARE_DESKTOP_CTA', '935');
define('COMPARE_DESKTOP_CTA_STICKY', '1010');

define('DEB_DESKTOP_CTA', '934');
define('DEB_DESKTOP_CTA_STICKY', '937');

define('FILTER_OLD_QUESTION_DATE', '2013-01-01T00:00:00Z');

// mobile app banner on the top of mobilesite
//define("MOB_APPMARKETING_MOBILEHEADER",true);

//show iim banner on mobile site insted of mobile app banner
define("MOB_ICPMARKETING_MOBILEHEADER",true);

//App Banner for Dekstop ANA Pages
define("DESKTOP_APPMARKETING_BANNER",false);
define("SHOW_FEE_DISC_CMPR",false);


global $certificateDiplomaLevels ;
$certificateDiplomaLevels = array('Bachelors Diploma','Bachelors Certificate','Masters Diploma','Masters Certificate');
global $certificateDiplomaCountries; // always show certificate diploma on these country's cat pages
$certificateDiplomaCountries = array(7,8); // country ids

define('RANKING_PAGE_TABLE','ranking_pages');
define('RANKING_PAGE_DATA_TABLE','ranking_page_data');

global $courseExpandedLevels;
$courseExpandedLevels = array(
	'certificate - diploma' => array('Bachelors Diploma','Bachelors Certificate','Masters Diploma','Masters Certificate'),
	'bachelors' => array('bachelors','Bachelors Diploma','Bachelors Certificate'),
	'masters' => array('masters','Masters Diploma','Masters Certificate')
);

define("MOBILE_SEARCH_V2_INTEGRATION_FLAG" , 1);


 global $SEARCH_FEES_RANGE;
 $SEARCH_FEES_RANGE = array(
							23 => array(
										'f1'  => array('min'=>'1', 'max'=>'99999','placeholder'=> ' < 1 Lakh'),
										'f2'  => array('min'=>'100000', 'max'=>'199999','placeholder'=> '1 - 2 Lakh'),
										'f3'  => array('min'=>'200000', 'max'=>'299999','placeholder'=> '2 - 3 Lakh'),
										'f4'  => array('min'=>'300000', 'max'=>'499999','placeholder'=> '3 - 5 Lakh'),
										'f5'  => array('min'=>'500000', 'max'=> '*','placeholder'=> ' > 5 Lakh'),
									),
							56 => array(
										'f1'  => array('min'=>'1', 'max'=>'99999','placeholder'=> ' < 1 Lakh'),
										'f2'  => array('min'=>'100000', 'max'=>'199999','placeholder'=> '1 - 2 Lakh'),
										'f3'  => array('min'=>'200000', 'max'=>'299999','placeholder'=> '2 - 3 Lakh'),
										'f4'  => array('min'=>'300000', 'max'=>'499999','placeholder'=> '3 - 5 Lakh'),
										'f5'  => array('min'=>'500000', 'max'=> '*','placeholder'=> ' > 5 Lakh'),
									)
							);

//At the time of heavy load on server, object of courses(eg. of Edukart) will not be loaded
define("DISABLE_RESTRICTED_LISTING", 0);
global $restrictedCourseIds;
$restrictedCourseIds = array(230874,230745,213405,230916,230921,231519,231524,230855,230926,230909,230923,230919,230882,230870,230863,230878,230879,230885,230887,230928,230867,230861,230772,230865,230780,230829,230749,230767,230903,230913,198720,230753,234871,213402,234877,230900,230832,213397,213400,230906);
global $restrictedInstituteIds;
$restrictedInstituteIds = array(35861);

global $subCatsForCollegeReviews;		//Subcategories for College Reviews
$subCatsForCollegeReviews = array(  '55' => '1',
                                    '56' => '1',
                                    '57' => '1',
                                    '58' => '1',
                                    '59' => '1',
                                    '60' => '1',
                                    '61' => '1',
                                    '62' => '1',
                                    '63' => '1',
                                    '64' => '1',
                                    '65' => '1',
                                    '66' => '1',
                                    '67' => '1',
                                    '68' => '1',
                                    '238' => '1',
                                    '23' => '1',
                                    '24' => '1',
                                    '25' => '1',
                                    '26' => '1',
                                    '27' => '1',
                                    '28' => '1',
                                    '29' => '1',
                                    '30' => '1',
                                    '31' => '1',
                                    '74' => '1',
                                    '75' => '1', 
                                    '76' => '1',
                                    '77' => '1',
                                    '78' => '1',
                                    '79' => '1',
                                    '80' => '1',
                                    '81' => '1',
                                    '82' => '1',
                                    '83' => '1',
                                    '233' => '1',
                                    '234' => '1',
                                    '97' => '1',
                                    '98' => '1',
                                    '99' => '1',
                                    '100' => '1',
                                    '101' => '1',
                                    '102' => '1',
                                    '103' => '1',
                                    '104' => '1',
                                    '105' => '1',
                                    '106' => '1',
                                    '107' => '1',
                                    '108' => '1',
                                    '109' => '1',
                                    '110' => '1',
                                    '111' => '1',
                                    '112' => '1',
                                    '113' => '1',
                                    '114' => '1',
                                    '115' => '1',
                                    '116' => '1',
                                    '117' => '1',
                                    '118' => '1',
                                    '119' => '1',
                                    '120' => '1',
                                    '121' => '1',
                                    '122' => '1',
                                    '123' => '1',
                                    '124' => '1',
                                    '125' => '1',
                                    '126' => '1',
                                    '127' => '1'
                                );

define('FEES_DISCLAIMER_TEXT', '(Estimated based on first year fees)');
Global $streamWiseFlowSplit;
$streamWiseFlowSplit = array(
		'specialization' => array('subStreamSpec', 'baseCourse', 'educationType', 'flowValue'),
		'course' => array( 'baseCourse', 'subStreamSpec', 'educationType', 'flowValue'),
		'both' => array('flowChoice')
	);

define("UNGROUPED_SPECIALIZATIONS_NAME", "Ungrouped Specializations");
Global $coursePriorities;
$coursePriorities = array('UG', 'PG', 'Advanced Masters', 'Doctorate', 'Post Doctorate', 'Certificates', 'After 10th');

Global $educationTypePriorities;
$educationTypePriorities = array(
								'20'=>'Full Time – Classroom',
								'33'=>'Part Time – Classroom',
								'34'=>'Distance / Correspondence',
								'39'=>'Online',
								'36'=>'Virtual Classroom',
								'35'=>'On The Job (Apprenticeship)'
								);

Global $modeAttributes;
$modeAttributes = array(5,7,8);

Global $examLevelPriorities;
$examLevelPriorities = array(
								'14'=>'UG',
								'15'=>'PG',
								'19'=>'None',
								'16'=>'Advanced Masters',
								'17'=>'Doctorate',
								'18'=>'Post Doctorate',
								'13'=>'After 10th'
								);

define('MANAGEMENT_STREAM', 1);
define('MANAGEMENT_COURSE', 101);
define('EDUCATION_TYPE', 20);
define('ENGINEERING_STREAM', 2);
define('ENGINEERING_COURSE', 10);

define('FULL_TIME_MODE', 20);
define('PART_TIME_MODE', 21);
define('CLASSROOM_MODE', 33);
define('DELIVERY_METHOD_ATTRIBUTE_ID', 7);

define('OTHERS_FACILITY_ID', 17);
define('HOSTEL_FACILITY_ID', 3);

define('DESIGN_STREAM',3);
define('IT_SOFTWARE_STREAM',6);
define('ANIMATION_STREAM',8);
define('LAW_STREAM',5);

define('MASS_COMMUNICATION_MEDIA',7);
define('HUMANITIES_SOCIAL_SCIENCES',9);
define('SCIENCE',11);
define('ACCOUNTING_AND_COMMERCE',13);
define('GOVERNMENT_EXAMS_STREAM',21);
define('PREF_YEAR_MANDATORY',0);
define('PREF_YEAR_HIDDEN',0);                     //If set this to 1 then set PREF_YEAR_MANDATORY to 0 only. added by mansi

Global $registrationFormSubHeading;
$registrationFormSubHeading = array(
									array(
											"heading"=>"Find Detailed Information on:",
											"body"=>array(
												"Top Colleges &amp; Universities",
												"Popular Courses",
												"Exams Preparation", 
												"Admissions &amp; Eligibility",
												"College Rankings"
											)
										),
									array(
											"heading"=>"Get Unique Insights from:",
											"body"=>array(
												"Compare Colleges Tool",
												"Student &amp; Alumni Reviews",
												"Ask &amp; Answer Community", 
												"Rank &amp; College Predictors",
												"News &amp; Recommendations"
											)
										)
									);
									
/**
 * RESPONSE_CREATION_METHOD constant has three values.
 	1.	cron
 	2.	direct
 	3.	queue
 */
define('RESPONSE_CREATION_METHOD','cron');

/**
 * Which also viewed algo is used
 * COLLABORATIVE_FILTERING : For collaborative filtering
 * SHIKSHA_ALSO_VIEWED : Shiksha's also viewed
 */
define('ALSO_VIEWED_ALGO', 'COLLABORATIVE_FILTERING');
//define('ALSO_VIEWED_ALGO', 'SHIKSHA_ALSO_VIEWED');

/**
 * Tier-Affinity map defines minimum required affinity for a tier
 * e.g. for tier 1 cities, minimum affinity required is 3
 * If user has affinity less than this, then s/he will not be eligible
 * for preferred MR location match
 */
$requiredAffinityForCityTier = array(
        1 => 3,
        2 => 2,
        3 => 1
);
global $nursingStream;
$nursingStream =17;

global $medicineStream;
$medicineStream =18;

global $beautyStream;
$beautyStream =19;

global $managementStreamMR;
$managementStreamMR =1;

global $mbaBaseCourse;
$mbaBaseCourse = 101;

global $engineeringtStreamMR;
$engineeringtStreamMR = 2;

global $btechBaseCourse;
$btechBaseCourse = 10;

global $fullTimeEdType;
$fullTimeEdType = 20;

global $defaultSubstream;
$defaultSubstream = 0;

global $userGlobalViewLimit;
$userGlobalViewLimit = 12;

global $totalEducationMode;
$totalEducationMode = 8;

global $postGrad;
$postGrad = 15;

global $certificateCredential;
$certificateCredential = 11;

global $MRPricingArray;
$MRPricingArray = array(
	$managementStreamMR => array('view'=>125,'SMS'=>5,'email'=>3),
	$engineeringtStreamMR => array('view'=>125,'SMS'=>5,'email'=>3)
	);

define('DEFAULT_TRACKING_KEY_DESKTOP', 904);
define('DEFAULT_TRACKING_KEY_MOBILE', 905);
define('DEFAULT_TRACKING_KEY_EXAM_DESKTOP', 1286);
define('DEFAULT_TRACKING_KEY_EXAM_MOBILE', 1287);


define("NONE_COURSE_LEVEL",19);
define("NONE_CREDENTIAL",12);
define("CERTIFICATE_CREDENTIAL",11);
define("NBA_APPROVAL",55);
define("MOB_HAMBURGER_UPDATE_CONTEXT", 15390610107562); // add in the formate of Unix timestamp, ex: 1482312337
define("NATIONAL_COUNTRY_NAME",'India');
define("NATIONAL_COUNTRY_ID",'2');

global 	$highProfileResponseConfig;
$highProfileResponseConfig =array('Asked_Question_On_CCHome','Asked_Question_On_CCHome_MOB','Asked_Question_On_Listing','Asked_Question_On_Listing_MOB','brochureCourseSARanking','brochureUnivSARanking','CollegePredictor','COMPARE_AskQuestion','COMPARE_EBrochure','Compare_Email','COMPARE_VI, 22','contactinfo','CoursePage_Reco','CP_MOB_Reco_ReqEbrochure','CP_Reco_divLayer','CP_Reco_popupLayer','CP_Reco_ReqEbrochure','CP_Request_Callback','CP_Request_Callback_sa_mobile','download_brochure_free_course','download_brochure_free_course_sa_mobile','D_MS_Ask','D_MS_Request_e_Brochure','GetFreeAlert','GetFreeAlert_sa_mobile','Listing-Photos','listingPageBellyNational','listingPageBellyNationalForInstitute','LISTING_PAGE_BOTTOM_RECOMMENDATION','LISTING_PAGE_EMAIL_SMS_CONTACT_DETAILS','LISTING_PAGE_RIGHT_RECOMMENDATION','LP_AdmissionGuide','LP_EligibilityExam','LP_MOB_Reco_ReqEbrochure','LP_Reco_AlsoviewLayer','LP_Reco_ReqEbrochure','LP_Reco_ShowRecoLayer','LP_Reco_SimilarInstiLayer','Mobile5_Asked_Questions_On_Comparepage','MOBILE5_CATEGORY_PAGE','MOBILE5_COLLEGE_PREDICTOR_PAGE','MOBILE5_COURSE_DETAIL_PAGE','MOBILE5_COURSE_DETAIL_PAGE_OTHER','MOBILE5_INSTITUTE_DETAIL_PAGE','MOBILE5_RANK_PREDICTOR_PAGE','MOBILE5_SEARCH_PAGE','MOBILE5_SHORTLIST_PAGE','MOBILE5_SIMILAR_COURSE_DETAIL_PAGE','MOBILEHTML5','MOBILEHTML5_GETEB','mobilesite','mobilesitesearch','MOB_CareerCompass_Ebrochure','MOB_Category_Shortlist','MOB_COMPARE_EBrochure','MOB_COMPARE_EMAIL','MOB_Course_Shortlist','ND_CareerCompass_Ebrochure','ND_CareerCompass_Shortlist','ND_CategoryReco_shortlist','ND_category_shortlist','ND_Compare_shortlist','ND_course_shortlist','ND_myshortlist_shortlist','ND_ranking_shortlist','ND_SERP_shortlist','ND_SRP_Reco_popupLayer','ND_SRP_Request_E_Brochure','NM_AlsoViewed_shortlist','NM_CareerCompass_shortlist','NM_category_shortlist','NM_Compare_shortlist','NM_course_shortlist','NM_myshortlist_shortlist','NM_ranking_shortlist','NM_shortlist_REB','OF_Request_E-Brochure','Online_Application_Started','RANKING_MOB_Reco_ReqEbrochure','RANKING_MOB_ReqEbrochure','RANKING_PAGE_REQUEST_EBROCHURE','RankPredictor','rate_my_chances','rate_my_chances_sa_mobile','reco_after_category','reco_also_view_layer_sa_mobile','reco_widget_mailer','requestinfo','Request_Callback','Request_Callback_sa_mobile','Request_E-Brochure','Request_E-Brochure_sa_mobile','request_salaryData','response_abroad_belly_link','response_abroad_category_page','response_abroad_category_page_shortList_tab','response_abroad_download_form_bottom','response_abroad_email_popout','RP_Reco_AlsoviewLayer','savedlisting','SEARCH_MOB_Reco_ReqEbrochure','SEARCH_REQUEST_EBROCHURE','sentmail','sentsms','Shortlist_Page_Reco_ReqEbrochure','Shortlist_Request_Callback
','Shortlist_Request_Callback_sa_mobile','similar_institute_deb','User_ShortListed_Course','User_ShortListed_Course_sa_mobile','downloadBrochure','courseDownloadBrochure','ND_courseDownloadInternship','ND_courseDownloadPlacement','NM_courseDownloadPlacement','onlineFormApply','reco_widget_mailer_national');

global $placementBrochureTrackingKeys;
$placementBrochureTrackingKeys = array(939,958);

global $internshipBrochureTrackingKeys;
$internshipBrochureTrackingKeys = array(940,959);

define('BROCHURE_SUCCESS_MSG_TXT','Brochure successfully mailed');

if(ENVIRONMENT == 'development')
	define("DEBUG_ON",true);
else
	define("DEBUG_ON",false);


global $JS_VERSION_MAPPINGS;
$JSVERSIONMAPPINGS = array(
		'shikshaDesktop'         => 'public/js',
		'nationalMIS'             => 'public/js/trackingMIS',
		'nationalMobile'          => 'public/mobile5/js',
		'nationalMobileVendor'    => 'public/mobile5/js/vendor',
		'nationalMobileBoomerang' => 'public/mobile5/js/vendor/boomerang',
		'abroadMobile'            => 'public/mobileSA/js',
		'abroadMobileVendor'      => 'public/mobileSA/js/vendor',
	);

/**
 * Recaptcha keys localshiksha.com
 */ 
//define("RECAPTCHA_SITE_KEY", "6LcJExEUAAAAAAuLdUEqp0Yva_YaEzqc0G4EOhFy");
//define("RECAPTCHA_SECRET_KEY", "6LcJExEUAAAAAILWxk2jAC1uKUBm4kXzohd0u2Ec");

/**
 * Recaptcha keys production
 */ 
define("RECAPTCHA_SITE_KEY", "6LctNhYUAAAAAB87Zq8O7HouLTzhOkP2slgIiGKX");
define("RECAPTCHA_SECRET_KEY", "6LctNhYUAAAAAN3ANnTQUu7JRb0Y8nLbN9Az0u45");

/*define("SESSION_INDEX_NAME", "shiksha_trafficdata_sessions");
define("SESSION_INDEX_NAME_REALTIME", "shiksha_trafficdata_sessions_realtime_".date('Y.m.d', strtotime('-'.(date('w')+1).' days'))); // also need to change in trafficIndexer.php file
define("SESSION_INDEX_NAME_REALTIME_SEARCH", "shiksha_trafficdata_sessions_realtime*");*/

define("SESSION_INDEX_NAME", "shiksha_sessions_m*");
define("SESSION_INDEX_NAME_PREFIX", "shiksha_sessions_m"); // new monthly index prefix and also used for snapshot purpose
define("SESSION_INDEX_NAME_CURRENT", "shiksha_sessions_m".date("Y").date("m"));
define("SESSION_INDEX_NAME_PREVIOUS_MONTH", "shiksha_sessions_m".date('Ym',strtotime('-1 month'.date("Ym"))));
define("SESSION_INDEX_NAME_REALTIME", "shiksha_trafficdata_sessions_realtime_".date('Y.m.d', strtotime('-'.(date('w')+1).' days'))); // also need to change in trafficIndexer.php file
define("SESSION_INDEX_NAME_REALTIME_SEARCH", "shiksha_trafficdata_sessions_realtime*");


//define("PAGEVIEW_INDEX_NAME", "shiksha_trafficdata_pageviews");
define("PAGEVIEW_INDEX_NAME", "shiksha_pageviews_m*"); // for search query
define("PAGEVIEW_INDEX_NAME_PREFIX", "shiksha_pageviews_m"); // new monthly index prefix and also used for snapshot purpose
define("PAGEVIEW_INDEX_NAME_CURRENT", "shiksha_pageviews_m".date("Y").date("m"));
define("PAGEVIEW_INDEX_NAME_PREVIOUS_MONTH", "shiksha_pageviews_m".date('Ym',strtotime('-1 month'.date("Ym"))));

define("PAGEVIEW_INDEX_NAME_REALTIME", "shiksha_trafficdata_pageviews_realtime_".date('Y.m.d', strtotime('-'.(date('w')+1).' days')));// also need to change in trafficIndexer.php file
define("PAGEVIEW_INDEX_NAME_REALTIME_SEARCH", "shiksha_trafficdata_pageviews_realtime*");

define("RESPONSE_INDEX_NAME", "mis_responses");
define("REGISTRATION_INDEX_NAME", "mis_registrations");
define("LDB_RESPONSE_INDEX_NAME", "ldb_response");
define("SHIKSHA_RESPONSE_INDEX_NAME", "shiksha_response");
define("MMM_INDEX_NAME", "mmm_mail_index");
define("USE_ELASTIC_SEARCH", true);

global $contactDetailsResponseTrackingKeys;
$contactDetailsResponseTrackingKeys = array(1113, 1114, 1115, 1116, 1117, 1118, 1165, 1168, 1173, 1175, 1181, 1184, 1189, 1191, 1205, 1233, 1238);

global $examViewedResponseTrackingKeys;
$examViewedResponseTrackingKeys = array(1311,1310,1309);

// define("REQUIRE_CSRF_PROTECTION", FALSE);
define("CTA_TYPE_EBROCHURE", "download_brochure");
define("CTA_TYPE_CONTACT_DETAILS", "contact_details");

global $statesToIgnore;
$statesToIgnore = array('128', '129', '130', '131', '134', '135', '345');

global $noSpecId;
$noSpecId = 99999;
global $noSpecName;
$noSpecName = array('noSpecMapping' => 'Specialization Not Expressed', 'noSpecMapping_data' => 'Specialization Not Expressed');


define('CR_MOD_SOLR_FLAG',true);

if(ENVIRONMENT == 'production')
	define("RIGHT_CLICK_DISABLED_ON_REVIEW_PAGE", true);
else
	define("RIGHT_CLICK_DISABLED_ON_REVIEW_PAGE", false);

define('LISTINGS_LOGGING', 1);
define('COURSE_DELETION_LOG_FILE', '/tmp/courseDeletionFlow.log');

global $pagesToShowBtmRegLyr;
$pagesToShowBtmRegLyr = array("articleDetailPage", "tagDetailPage", "questionDetailPage");

global $NM_SERVER_IPS;
$NM_SERVER_IPS = array("180.179.180.224");
define('HOMEPAGE_GA_TRACKING', 0);

define("SHOW_RANKING_WEBSITE_TOUR", true);

define('ICP_BANNER_COURSE',101);
define('CP_BANNER_COURSE_MBA',101);
define('CP_BANNER_COURSE_ENGG',10);


define("FACEBOOK_ACCESS_TOKEN", "EAABfA7BEImEBADToDtGjMUNNom1FbkRgNr05KIM5ugYi5bAJpybpqn58bY6ZCO6XG2exM0ZAhYK4ffZCdLXPfZC2hMvUohMoQKpITaGrY6ZB6zsOmtQ5TZCL8gc0QKgvH6juBZBaWDnWx3kbWBihz65fQVci7ZCyWxsl90f7a05xGQZDZD");

define('COURSE_VIEWER_ELASTIC_FLAG',true);

define('DURATION_TOOLTIP','Duration may change depending upon course modules and course timing slots.');

global $COURSE_MESSAGE_KEY_MAPPING;
$COURSE_MESSAGE_KEY_MAPPING = array();
$COURSE_MESSAGE_KEY_MAPPING = array(
				'242615' => 'SRM',
				'242609' => 'SRM',
				'242612' => 'SRM',
				'242620' => 'SRM',
				'242626' => 'SRM',
				'242610' => 'SRM',
				'242627' => 'SRM',
				'242628' => 'SRM',
				'242613' => 'SRM',
				'242616' => 'SRM',
				'242614' => 'SRM',
				'242622' => 'SRM',
				'242617' => 'SRM',
				'242629' => 'SRM',
				'330527' => 'SRM',
				'242618' => 'SRM',
				'330565' => 'SRM',
				'330641' => 'SRM',
				'330545' => 'SRM',
				'330631' => 'SRM',
				'330541' => 'SRM',
				'330627' => 'SRM',
				'330629' => 'SRM',
				'330569' => 'SRM',
				'330635' => 'SRM',
				'330637' => 'SRM',
				'278317' => 'SRM',
				'278319' => 'SRM',
				'278318' => 'SRM',
				'278287' => 'SRM',
				'278293' => 'SRM',
				'278288' => 'SRM',
				'303265' => 'SRM',
				'278292' => 'SRM',
				'238561' => 'SRM',
				'238568' => 'SRM',
				'238565' => 'SRM',
				'238566' => 'SRM',
				'238562' => 'SRM',
				'238564' => 'SRM',
				'238563' => 'SRM',
				'233896' => 'SRM',
				'276276' => 'SRM',
				'276277' => 'SRM',
				'266808' => 'SRM',
				'233899' => 'SRM',
				'294744' => 'SRM',
				'233899' => 'SRM',
				'294744' => 'SRM',
				'294743' => 'SRM',
				'233892' => 'SRM',
				'294740' => 'SRM',
				'233901' => 'SRM',
				'233891' => 'SRM',
				'233898' => 'SRM',
				'294748' => 'SRM',
				'294746' => 'SRM',
				'294741' => 'SRM',
				'294745' => 'SRM',
				'330413' => 'SRM',
				'294749' => 'SRM',
				'294742' => 'SRM',
				'330429' => 'SRM',
				'330425' => 'SRM'
    ); 

global $INSTITUTE_MESSAGE_KEY_MAPPING;
$INSTITUTE_MESSAGE_KEY_MAPPING = array();
$INSTITUTE_MESSAGE_KEY_MAPPING = array(
				'24749' => 'SRM',
				'51999' => 'SRM',
				'51992' => 'SRM',
				'47486' => 'SRM',
				'42930' => 'SRM'
			); 



global $MESSAGE_MAPPING;
$MESSAGE_MAPPING = array(
        'SRM' => array(
                    'email'   => "You will also receive an email from SRM to verify your email Id to apply for SRMJEEE.",
                    'SMS'     => "You have shown interest in SRM on Shiksha. You will shortly receive an email from SRM to verify your email to apply for SRMJEEE.",
                    'onSiteDesktop' => "You will also receive an email from SRM to verify your email Id to apply for SRMJEEE. Please check your inbox.",
                    'onSiteMobile' => "You will also receive an email from SRM to verify your email Id to apply for SRMJEEE. Please check your inbox.",
                    'ToastMsg' => "Thank you for your interest in SRM. You may receive an email from SRM to verify your email ID to apply for SRMJEEE."
                )
    );

global $TABLES_WITH_CI_MAPPING;
$TABLES_WITH_CI_MAPPING = array(
        array(
            'table' => 'ranking_page_data',
            'primaryIdColumn' => 'id',
            'instituteIdColumn' => 'institute_id',
            'courseIdColumn' => 'course_id',
            'instituteRelation' => 'any_parent',
            'statusColumn' => ''
        ),
        array(
            'table' => 'popular_institutes',
            'instituteIdColumn' => 'institute_id',
            'courseIdColumn' => 'courseId',
            'instituteRelation' => 'primary',
            'statusColumn' => 'status'
        ),
        array(
            'table' => 'latestUserResponseData',
            'instituteIdColumn' => 'instituteId',
            'courseIdColumn' => 'courseId',
            'instituteRelation' => 'primary',
            'statusColumn' => ''
        ),
        array(
            'table' => 'CollegeReview_MappingToShikshaInstitute',
            'instituteIdColumn' => 'instituteId',
            'courseIdColumn' => 'courseId',
            'instituteRelation' => 'primary',
            'statusColumn' => ''
        ),
        array(
            'table' => 'CA_MainCourseMappingTable',
            'primaryIdColumn' => 'id',
            'instituteIdColumn' => 'instituteId',
            'courseIdColumn' => 'courseId',
            'instituteRelation' => 'any_parent',
            'statusColumn' => 'status'
        ),
        array(
            'table' => 'OF_InstituteDetails',
            'primaryIdColumn' => 'id',
            'instituteIdColumn' => 'instituteId',
            'courseIdColumn' => 'courseId',
            'instituteRelation' => 'any_parent',
            'statusColumn' => 'status'
        ),
        array(
            'table' => 'questions_listing_response',
            'primaryIdColumn' => 'id',
            'instituteIdColumn' => 'instituteId',
            'courseIdColumn' => 'courseId',
            'instituteRelation' => 'any_parent',
            'statusColumn' => 'status'
        ),
        array(
            'table' => 'OF_PBTSeoDetails',
            'primaryIdColumn' => 'instructionId',
            'instituteIdColumn' => 'instituteId',
            'courseIdColumn' => 'courseId',
            'instituteRelation' => 'any_parent',
            'statusColumn' => 'status'
        )
    );

global $TABLES_WITH_CL_MAPPING;
$TABLES_WITH_CL_MAPPING = array(
        array(
            'table' => '',
            'uniqueId' => 'id',
            'locationIdColumn' => '',
            'stateIdColumn' => '',
            'cityIdColumn' => '',
            'localityIdColumn' => '',
            'courseIdColumn' => ''
        ),
    );



global $TABLES_WITH_C_MAPPING;

$TABLES_WITH_C_MAPPING =array(
	array(
            'table' => 'alsoViewedFilteredCourses',
            'uniqueId' => 'id',
            'courseIdColumn' => 'course_id',
            'status' =>'status',
        ),
	);




global $TABLES_WITH_I_MAPPING;

$TABLES_WITH_I_MAPPING =array(
	array(
            'table' => 'alsoViewedFilteredCourses',
            'uniqueId' => 'id',
            'instituteIdColumn' => 'recommended_institute_id',
            'status' => 'status'
        ),
	);

global $IVR_Action_Types;
$IVR_Action_Types = array('Institute_Viewed', 'MOB_Institute_Viewed');
define("Inst_Viewed_Action_Course","Course specific interest not expressed yet");

global $mmpFormsForCaching;
$mmpFormsForCaching = array(2833,2835,2836,2837,3051);

define("BASE_ENTITIES_CACHE",true);
define("ANALYTICS_ELASTIC_INDEX", "shiksha_analytics_jun");


//Private-Key for OpenSSL Cerification for Google CDN Cache Update Request

define("AMP_PRIVATE_KEY","MIIEowIBAAKCAQEAwgxmq+iJmBszsDg6dZeIuidiTlZF1yN8Jz40K0RCbxnyf8is
IwbV8dfXjDOxXrkdgvnm+e9YaM772fki3XHmH84AXiLnChlWLOi/38LZbI6jLRgE
/kEr/VsoFFkYeU354buahkP6lEAH9D5lS1TeZ6RcSdgg/ueggwjITxZWPcojRM/m
r0S+/CnXRyKczvzlS9oSmyOJFY+Jh5g8syxlE1KTyzu26tB8tdvxx7zV/lkR579E
M8s7iIoix4tYZu6uIL2RDlEx/7JvI1jbYYXPCPyVrU1njvab8DJZCTpyFXXxsm4D
GTekmQPLXjIMdZOi4IWf519xqjA2LJbXFpLp7wIDAQABAoIBAAS3enNpRpbs6le5
NiqPkWI8/NW8oRv2n9jwWSJIGXlL3yod/ZkoXGDxhyrAQupzg3Ugj+25VKPPjC2j
MabCUv6o6jdfj9AeB4s16RwOR2ytFuhMsipf+SrCYXoFJGQmchF7luj2lAuwdsEI
Fzw6huFsDFY7K6omhS9KMlxBxbzgtxj52325QVopA2wYlyrBRITdzfDm4cWTioBS
/OtXx0rgj82C6PtF91RW6N5QT0NLmRM0aJV1jAV90bwvHru5e/beMwGsZGYf2qA4
kse7Iggm/ukfxcLiZML2k5r8NzDEA7Sefvoct3paJtoH9DIbrGYolHD4P+ZDweO7
xN+3pgkCgYEA7fNSsfp4vjqDukMILqRweVyNxQ4DpBbwtndKt+N8pLUv9bOV0Fo1
1wulFSJVDDiuK0Py9SzityQt2gxSOYREs0dEhZLBjoxTAPYQGTRLzfkXEEZkiiBi
Jf4G1BJ+F5ypfC/Vye/ZvIqIAd4Dbpxyvo/qhxTQJE1n0WUCCWk3P4MCgYEA0MSP
Sk7Ki2BdYceEIXSQEuI6qBMEycMcKQ+9+MklcdbYvt0ywg5IRTGmCPbacACrAIt0
RwNaDpcrEpLo8W5w0dBJVhHBH5dUYcpreF1zku9E9/bKMGjuMKAoNsWW4aBMdrGl
8q0IKCqBYQOrIAH31MDw0OZgPLo01+zCL051lCUCgYEA04s3QbOEFNrAsZ9WbuES
fVKjV0UWR5N9fTqg2ssLzQKoGLAHyKvqobxgj2FuaucZMK3AGehFxrwLZ4b8stW8
ngYGDRpjqe9m+7vGpCGyIvQIZRev2nzfxRcJyxCFuUg2BYohbt4lnVEriT1vn67G
9FPOFTmTsjJ+0dIS9Xrs+zECgYACCelcUGip88b1rX0c1oaRqKPqAEWLstwTipPQ
WiaDdhWnx4E3Y+xQwKteawq3DUqeNr5r8xxuCAvjooujz/BKHD6bGJFKPbAVRGTI
SCFYzf1eboqK7ntk/itmXYebrHUSs6lrNUVfHwskZ2TEa0CAU2IGqDlIXoklkqpS
y/FaAQKBgH4uXKUC0FhLiERLWajcqGgPrvHp7+GyYgMWgKVAG7OEokrHYeqhlV4l
CJRUEpwGTIQZMIV4q21vaUYb5YOA7Da4jLMqz+q7D0xgUzskPR3wkmkHv7Xvh7i4
fY1ga8a7mHbVdr+J4UL1IFlNFYWvYlZcL//lc7ymfpCGoadhNS1Y"
);

global $invalidEmailDomains;
$invalidEmailDomains = array('gamil.com', 'gmail.con', 'gmai.com', 'gmil.com', 'gmail.cim', 'gimal.co', 'gmail.co', 'gimal.com', 'gmal.com', 'gemill.com', 'gmqil.com', 'gemail.com', 'gmial.com', 'gmali.com', 'gnail.com', 'email.com');

global $validDomains;
$validDomains = array('@gmail.com','@yahoo.com','@yahoo.co.in','@rediffmail.com','@hotmail.com');

define("ELASTIC_TIMEZONE", "Asia/Calcutta");
define("ELASTIC_AGGS_SIZE", "100000");
define("SHIKSHA_ASSISTANT_INDEX_NAME", "shiksha_assistant_conversations");
define("SHIKSHA_ASSISTANT_INDEX_NAME_REALTIME", "shiksha_assistant_conversations".date('Ym'));
define("SHIKSHA_ASSISTANT_INDEX_NAME_REALTIME_SEARCH", "shiksha_assistant_conversations*");

define("SHIKSHA_ASSISTANT_FLAG", 1);
define("SHIKSHA_ABTEST_INDEX_NAME", "shiksha_abtest");

global $studyAbroadAllDesiredCourses;
$studyAbroadAllDesiredCourses = array('1508','1509','1510','11837','11838','11839','11840','11841','11842','11843','11844','11845','11846','11847','11848','11849','11850','11851');

global $studyAbroadLevelWiseDesiredCourses;
$studyAbroadLevelWiseDesiredCourses = array('UG'=>array('1510','11843','11844','11846'),'PG'=>array('1508','1509','11837','11838','11839','11840','11841','11842','11845','11847','11848','11849','11850','11851'));

/*Study Abroad LDB Course and Category Config*/
define('STUDY_ABROAD_POPULAR_MBA','MBA');
define('STUDY_ABROAD_POPULAR_MS','MS');
define('STUDY_ABROAD_POPULAR_BEBTECH','BE/Btech');
define('STUDY_ABROAD_POPULAR_MEM','MEM');
define('STUDY_ABROAD_POPULAR_MPHARM','MPharm');
define('STUDY_ABROAD_POPULAR_MFIN','MFin');
define('STUDY_ABROAD_POPULAR_MDES','MDes');
define('STUDY_ABROAD_POPULAR_MFA','MFA');
define('STUDY_ABROAD_POPULAR_MENG','MEng');
define('STUDY_ABROAD_POPULAR_BSC','BSc');
define('STUDY_ABROAD_POPULAR_BBA','BBA');
define('STUDY_ABROAD_POPULAR_MBBS','MBBS');
define('STUDY_ABROAD_POPULAR_BHM','BHM');
define('STUDY_ABROAD_POPULAR_MARCH','MArch');
define('STUDY_ABROAD_POPULAR_MIS','MIS');
define('STUDY_ABROAD_POPULAR_MIM','MIM');
define('STUDY_ABROAD_POPULAR_MASC','MASc');
define('STUDY_ABROAD_POPULAR_MA','MA');
define('STUDY_ABROAD_MASTERS','MASTERS');
define('STUDY_ABROAD_BACHELORS','BACHELORS');
define('STUDY_ABROAD_CATEGORY_BUSINESS','Business');
define('STUDY_ABROAD_CATEGORY_ENGINEERING','Engineering');
define('STUDY_ABROAD_CATEGORY_COMPUTERS','Computers');
define('STUDY_ABROAD_CATEGORY_SCIENCE','Science');
define('STUDY_ABROAD_CATEGORY_MEDICINE','Medicine');
define('STUDY_ABROAD_CATEGORY_HUMANITIES','Humanities');
define('STUDY_ABROAD_CATEGORY_LAW','Law');
define('ES_ACTIVE_USER_INDEX', 'active_users');
/*study abroad admin user Id constants*/
define('STUDY_ABROAD_ADMIN_USER_ID',3284455);

define('ES_ACTIVE_USER_INDEX', 'active_users');
define('MOBILE_APP_NEW', false);
