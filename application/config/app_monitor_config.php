<?php

$config[] 	  	= array();

// config
define("EN_PERF_LOG_URLS_LIMIT" , 100);
global $mainmodules;

$mainmodules 		= array(array('shiksha', 'index'),
                            array('CategoryList','categoryPage'),
							array('Search','index'),
							array('CoursePage', 'coursePages'),
                            array('MsgBoard', 'discussionHome'),
                            array('MsgBoard', 'topicDetails'),

                            array('AbroadCategoryList', 'categoryPage'),
                            array('studyAbroadHome', 'homepage'),
                            array('SAContent', 'getContentDetails'),
                            array('abroadListings', 'courseListing'),
                            array('abroadListings', 'departmentListing'),
                            array('abroadListings', 'universityListing'),
                            array('AbroadCategoryList', 'countryPage'),
                            array('CountryHome', 'countryHome'),
                            array('AbroadRanking', 'rankingPage'),
                            array('AbroadCategoryList', 'getShortlistPage'),
                            array('AbroadSearch', 'index'),
                            array('CategoryPage', 'categoryPageMobile'),
                            array('ListingPage', 'courseListing'),
                            array('HomePage', 'renderHome'),
                            array('searchPage', 'index'),
                            array('contentPage', 'getArticleDetails'),
                            array('InstituteDetailPage', 'getInstituteDetailPage'),
                            array('AllContentPage', 'getAllContentPage'),
                            array('InstituteMobile', 'getInstituteDetailPage'),
                            array('AllContentPageMobile', 'getAllContentPage'),
                            array('AnADesktop', 'getHomePage'),
                            array('AnAMobile', 'getHomepage'),
                            array('AnADesktop', 'getQuestionDiscussionDetailPage'),
                            array('AnAMobile', 'getQuestionDiscussionDetailPage')
						);

global $timeBuckets;
$timeBuckets = array(100,300,600,1000,2000,5000,10000);

global $timeBucketsDefine;
$timeBucketsDefine = array(100 => "0-100",
					 300 => "100-300",
					 600 => "300-600",
					 1000 => "600-1000",
					 2000 => "1000-2000",
					 5000 => "2000-5000",
					 10000 => "5000-10000",
					 50000 => "10000+");

global $timeBucketValConfidenceLevels;
$timeBucketValConfidenceLevels = array(100 => 1,
									   300 => 2,
									   600 => 3,
									   1000 => 4,
									   2000 => 5,
									   5000 => 6,
									   10000 => 7,
									   50000 => 8);
define("TIME_LAST_BUCKET", 50000);

global $memoryBuckets;
$memoryBuckets = array(20971520, 52428800, 83886080, 104857600, 136314880, 209715200, 419430400);

global $cacheBuckets;
$cacheBuckets = array(1048576, 2097152, 5242880, 10485760, 20971520, 52428800, 104857600, 209715200, 314572800, 419430400, 524288000);

global $memoryBucketsDefine;
$memoryBucketsDefine = array(20971520 => "20 MB",
					 52428800 => "50 MB",
					 83886080 => "80 MB",
					 104857600 => "100 MB",
					 136314880 => "130 MB",
					 209715200 => "200 MB",
					 419430400 => "400 MB",
					 "lastBucket" => "400+ MB");

global $cacheBucketsDefine;
$cacheBucketsDefine = array(1048576 => "1 Mb",
					 2097152 => "2 Mb",
					 5242880 => "5 Mb",
					 10485760 => "10 Mb",
					 20971520 => "20 Mb",
					 52428800 => "50 Mb",
					 104857600 => "100 Mb",
                     209715200 => "200 Mb",
                     314572800 => "300 Mb",
                     419430400 => "400 Mb",
                     524288000 => "500 Mb",
					 "lastBucket" => "500+ Mb");                    

global $ENT_EE_TIMETHRESHOLD;
$ENT_EE_TIMETHRESHOLD = 500; //-- milliseconds

global $ENT_EE_MEMORYTHRESHOLD;
$ENT_EE_MEMORYTHRESHOLD = 30 * 1024 * 1024;

global $ENT_EE_CACHETHRESHOLD;
$ENT_EE_CACHETHRESHOLD = 2 * 8 * 1024 * 1024;

global $ENT_EE_SQLQUERY_COUNT_THRESHOLD;
$ENT_EE_SQLQUERY_COUNT_THRESHOLD = 20;

global $ENT_EE_VERYSLOW_SQL;
$ENT_EE_VERYSLOW_SQL = 10; // very slow sql in seconds
                     
global $ENT_EE_MODULES;
$ENT_EE_MODULES = array(		"mobile_app" => "UGC",
								"listings" => "Listings",  
								//"ugc" => "UGC", 
								"studyabroad" => "StudyAbroad",
								"ldb" => "LDB",
								"common" => "Common",
								"Service" => "Services",
								"search" => "Search",
								"others" => "Others");

global $ENT_EE_MODULES_COLORS;
$ENT_EE_MODULES_COLORS = array(	"mobile_app"    => "#8B0707",
								"listings" 		=> "#3366cc",  
								// "ugc"           => "#dc3912", 
								"studyabroad"   => "#ff9900",
								"ldb"           => "#109618",
								"common"        => "#990099",
								"service"      => "#b6731a",
								"search"		=> "#db4a76",
								"others" 		=> "#0099c6",
                                "all" 		=> "#000000");


global $ENT_EE_MODULES_CONTROLLER_MAP;
$ENT_EE_MODULES_CONTROLLER_MAP = array("common" => array( 		"" => array("tracking","FileReplaceScript","common","CaptchaControl","Redir","welcome"),
													 "support" => array("SupportServer", "Support"),
													 "monitor" => array("CronMonitor"),
													 "shikshaSchema" => array("ShikshaSchema"),
													 "tracker" => array("TrackerM", "Tracker", "a"),
													 "common" => array("ErrorExceptionLogger"),
													 "shikshaHelp" => array("ShikshaHelp"),
													 "network" => array("Network", "Network_server"),
													 "enterprise" => array("EnterpriseServer" , "TestEnterprise", "Enterprise"),
													 "search"     => array("AbroadSearch"),
													 "beacon" => array("Beacon_server","Beacon"),
													 "trackingMIS" => array("Dashboard", "saSalesDashboard", "MISCrons"),
													 "ContentRecommendation" => array("AnArecommend"),
													 "alerts" => array("alert_feed_client")
						 					),
						"ldb" => array( "" => array("LeadExport", "LDBLeadMigrationScript","ProductDeliveryScripts","ResponseLocationFixer","UserFlowRedirection","RespondentReport"),
										"shikshaStats" => array("Deferral_Server","shikshaStatsServer2","ShikshaStats3","ShikshaStats","shikshaStatsServer3","shikshaStatsServer","deferral","deferral1","ShikshaStats2","ShikshaStats1","shikshaStatsServer1"),
										"FloatingRegistration" => array("FloatingRegistration"),
										"MultipleApply" => array("MultipleApply"),										
										"common" => array("HardBounceTracking"),
										"userVerification" => array("userVerification"),
										"systemMailer" => array("SystemMailer"),
										"mail" => array("Mail", "Mail_server"),
										"recommendation" => array("recommendation_POC", "recommendation_similar_institute", "recommendation_server","recommendation"),
										"SMS" => array("SMS", "sms_server"),
										"mailer" => array("MailerServer","MailerProcessor","Mailer","MarketingFormProcessor","MarketingFormMailer"),
										"inviteFriends" => array("InviteFriends"),
										"payment" => array("paymentFirst","paymentGatewayServer","firstPageLogin","paymentFree","paymentServer","newPage","newHeader","payment","addListing"),
										"sums" => array("MIS","Quotation_Server","Subscription_Server","Nav_Sums_Integration_Server","targetInput","Quotation","mis_server","Subscription","Sums_Common","Manage_Server","targetInputServer","Product","SumsMailerServer","testtarget","SumsMailer","Manage","Sums_Mail_Events","Nav_Integreation","Product_Server"),
										"customizedmmp" => array("mmp", "mmp_server"),
										"lms" => array("ResponseMigration","lms","Porting","lmsServer"),
										"offlineOps" => array("index"),
										"searchAgents" => array("searchAgents","sawatchdog","LeadConsumptionReport","MatchedResponseAgent","ndnc","searchAgents_Server","searchAgents_Server_bkp"),
										"LDB" => array("LDB_Server","LDBCourseServer","LeadDashboard"),
										"misc" => array("miscServer", "Misc"),
										"registration" => array("ParkLeads","CustomRegistrationCommunication","Registration","Forms","WelcomeMailerAttachment","PiRegistration","Tracker","RegistrationForms","RegistrationAPIs"),
										"marketing" => array("ResponseMarketingPage","ClientLandingPage","MarketingServer","Marketing"),
										"user" => array("CityListCleanUpMain","Login_server","Register_server","CityListDbAndFileOperations","MyShiksha","CityListException","PowerUser","UnifiedRegistration","PowerUser_server","CityListCleanUp","MyShiksha_Server","UserIndexer","Login","Userregistration","Register_StudyAbroad"),
										"MIS" => array("Ldbmis","SADownloadleads","LdbReport","Ldbmis_server"),
										"consultants" => array("consultant_server","shikshaConsultants"),
										"multipleMarketingPage" => array("Marketing"),
										"smart" => array("addSmartUser","SmartMis"),
										"crm" => array("CRM_Server","CRMFeedback_Server","CRM"),
										"enterprise" => array("LDBSearchTabs","shikshaDB","PRILine","shikshaDB","MultipleMarketingPage","offlineResponse","MultipleMarketingPageServer","ReviewHomepageTiles", "enterpriseSearch"),
										"CollegeReviewForm" => array("CollegeReviewForm","CollegeReviewInstituteReplyController","CollegeReviewController", "CollegeReviewCrons"),
										"mCollegeReviewForm5" => array("CollegeReviewForm"),
										"CAEnterprise" => array("CampusAmbassadorEnterprise"),
										"alerts" => array("Alerts","alert_server"),
						"userProfile"=> array("UserProfileController"),
										"muser5" => array("userVerification","MobileUser","ODBVerification"),
										"userProfile"=> array("UserProfileController"),
										"leadTracking" => array("LDBLeadTracking"),
										"muserProfile5" => array("UserProfile"),
										"userSearchCriteria" => array("searchCriteria"),
										"response" => array("Response"),
	                                                                        "mcommon" => array("MobileSiteHome"),
										"splice" => array("dashboard")
								),
						"listings" => array("" => array("shiksha","instituteSite","ListingScripts"),
											"coursepages" => array("CoursePage","CoursePageCron","CoursePageCms"),
											"ranking" => array("RankingMain","RankingBanner","RankingEnterprise"),
											"ranking_new" => array("RankingMain","RankingBanner","RankingEnterprise"),
											"location" => array("Location","LocationServer"),
											"common" => array("IDGenerator","autoSuggestorSearchTracking"),
											"categoryList" => array("TestListing","NewCategoryPageURLCtrl","CacheAnalyzer","CategoryList","CategoryServer","CategoryPageServer","Browse","Category_list_server","CourseDataInconsistencyFixer","CacheUtility"),
											"alumni" => array("alumniSpeak","AlumniSpeakFeedBack"),
											"listing" => array("ListingPage","ListingPage","Microsite","ListingReports","BannerFinderServer","CourseServer","InstituteServer","InstituteFinderServer","BannerServer","CourseFinderServer","ListingDebugger","listing_server","NationalUpgradeCourses","ListingPageWidgets","listing_media_server","Listing","ListingsCrons","MediaPost","CoursePost","ListingDelete","ListingUpdate","AbstractListingPost","ListingPublisher","InstitutePost","addlisting","Naukri_Data_Integration_Controller"),
											
											"naukrishiksha" => array("naukrishiksha"),
											"listingfeed" => array("listingfeed_server", "listingfeed"),
											"saveProduct" => array("SaveProduct_server", "SaveProduct"),
											
											"enterprise" => array("FilterExam", "ShowForms"),
											"searchmatrix" => array("SearchMatrix", "SearchMatrix_Server"),
		                                    "mobile_category5" => array("CategoryMobile"),
		                                    "mobile_listing5" => array("CourseMobile", "InstituteMobile","AllContentPageMobile"),
		                                    "mobile_nationalCategory5" => array("AllCoursesPageMobile","McategoryList"),
											"mranking5" => array("RankingMain","RankingBanner","RankingEnterprise"),
											"NaukriTool" => array("NaukriToolController"),
										        "mgetEB" => array("Msearch"),	
										     "nationalInstitute" => array("InstituteDetailPage", "AllContentPage", "InstitutePosting"),
										     "nationalCourse" => array("CoursePosting", "CourseDetailPage"),
										     "nationalCategoryList" => array("CategoryProductEnterprise","CategoryPageSeoEnterprise","CategoryPageSeoCron","AllCoursesPage"),
										     "rankingV2" => array("RankingMain"),
									         "blogs" => array("shikshaBlog","blogParser","sa_server","blog_server"),
									        "mCompareInstitute5" => array("compareInstitutes"),
									        "marticle5" => array("ArticleMobile", "ArticleMobileController"),
									        "article" => array("ArticleController"),
									        "comparePage" => array("comparePage"),
										     "listingCommon" => array("ListingCrons", "ListingCommonScripts")
											
											),
						"studyabroad" =>  array("" => array("StudyAbroadListingMigrationScripts"),
												"applyHome" => array("ApplyHome"),
										  		"listingPage" => array("ListingPage"),
										  		"categoryPage" => array("CategoryPage"),
										  		"contentPage" => array("contentPage"),
										  		"searchPage" => array("searchPage", "searchPageV2"),
										  		"homePage" => array("HomePage"),
										  		"commonModule" => array("User"),
										  		"shortListPage" => array("ShortListPage"),
										  		"studyAbroadHome" => array("studyAbroadHome"),
										  		"common" => array("studyAbroadUserTracking"),
										  		"listingPosting" => array("AbroadListingPosting","AbroadTestController"),
										  		"responseAbroad" => array("ResponseAbroad"),
										  		"enterprise" => array("StudyAbroadPageWidget"),
										  		"abroadRanking" => array("AbroadRanking"),
										  		"categoryList" => array("AbroadCategoryList"),
												"rateMyChancePage" => array("rateMyChance"),
												"rateMyChances" =>array("rateMyChances"),
												"shikshaApplyCRM" =>array("rmcPosting", "shikshaApplyCrons","counsellorAllocation","shikshaApplyLeadsAndResponses"),
												"listing" => array("abroadListings", "AbroadListingCrons"),
												"blogs" => array("SAContent"),
												"consultantEnquiry" => array("ConsultantEnquiry","ConsultantCrons"),
											    "consultantPosting" => array("ConsultantPosting"),
											    "consultantProfile" => array("ConsultantPage"),
											    "abroadExamPages" => array("AbroadExamPages"),
		                                                                            "abroadContentOrg" => array("AbroadContentOrgPages"),
											    "studyAbroadArticleWidget" => array("articleAbroadWidgets"),
											    "search" => array("Indexer", "AbroadSearchQER"),
											    "countryHomePage" => array("CountryHomePage"),
											    "countryHome" => array("CountryHome", "CountryHomeCrons"),
											    "SASearch" => array("AbroadIndexer", "abroadSearchV2"),
											    "shipment" => array("shipment", "shipmentCrons"),
											    "salesDashboard" => array("SalesDashboard"),
											    "abroadExamContent" => array("AbroadExamContent"),
											    "scholarshipPage" => array("scholarshipCategoryPage", "scholarshipHomePage"),
											    "scholarshipsDetailPage" => array("scholarshipsDetailPage"),
											    "applyContent" => array("applyContent")

										  ),
						"mobile_app" => array("" => array("SiteMap","HomePageServer","NavigationWidgets","r","crawler","Seo","Seo_server","ServiceWorker"),
									   "CA" => array("CampusAmbassador","MentorController","CACrons","CRDashboard","CampusConnectController","CADiscussions"),
									   "RP" => array("RankPredictorController"),
									   "RPExcelUploader" => array("RPExcelUploader"),
									   "articleWidgets" => array("articleWidgets"),
									   "facebook" => array("Facebook_server","FacebookF"),
									   "monitor" => array("MobileActivityReport"),
									   "compareInstitute" => array("compareInstitutes","collegecomparetool"),
									   "common" => array("CommonReviewWidget"),
									   "Acl" => array("Acl_server"),
									   "upcomingEvents" => array("upcomingEvents"),
									   "event" => array("EventController"),
									   "ExamPageDiscussion" => array("ExamPageDiscussion"),
									   "mcommon5" => array("MobileSiteBottomNavBar","MobileSiteStatic","parse_apache_log","MobileSiteHome","MobileSiteHamburger","MobileBeacon", "MobileSiteHamburgerV2"),
									   "mCampusAmbassador5" => array("MentorController","CCHomepageController","MenteeChatDashboard"),
									   "mOnlineForms5" => array("OnlineFormsMobile"),
									   "mcollegepredictor5" => array("CollegePredictorController"),
									   "mEventCalendar5" => array("EventCalendarController"),
									   "mRankPredictor" => array("RankPredictorMController"),
									   "mShortlist5" => array("ShortlistMobile"),
									   "homepage" => array("HomepageSlideshow_server","HomepageSlideshowWidget"),
									   "enterprise" => array("MobileHomepageConfig"),
									   "Careers" => array("CareerControllerImageupload","CareerController"),
									   "CareerProductEnterprise" => array("CareerEnterprise"),
									   "Online" => array("PaymentController","OnlineFormsCreator","OnlineForms","onlineForms_server","OnlineFormConversionTracking","onlineTests_server","OnlineTests"),
									   "studentFormsDashBoard" => array("StudentDashBoard","MyForms","MyDocuments","FindInstitute","ManageBreadCrumb","StudentDashboardServer"),
									   "onlineFormEnterprise" => array("EnterpriseDataGrid","OnlineMailer","OnlineFormEnterprise_server","OnlineFormEnterprise",""),
									   "CP" => array("CollegePredictorController"),
									   "ExcelUploader" => array("ExcelUploader"),
									   "muser" => array("MobileUser"),
									   "examPages" => array("ExamPageMain","ExamPagesCMS"),
										"myShortlist" => array("MyShortlist", "MyShortlistCMS"),
										"mobile_examPages5" => array("ExamPageMain"),
										"mobile_myShortlist5" => array("MyShortlistMobile"),	
									   "mcommon" => array("MobileSiteStatic","parse_apache_log","ShkshaSMSMarketing","MobileBeacon"),
									   "v1" 		=> array("AnA", "AnAPost","APICrons","APIParent","CommonAPI", "Search", "Tags", "NotificationInfo", "Universal", "User", "UserProfileBuilder", "UserProfile"),
										  "v2" 		=> array("AnA"),
										 "messageBoard" => array("message_board_server","MsgBoard","MessageBoardInternal","AnACrons","AnADesktop", "AnAPostDesktop"),
										 "UserLeaderBoard" => array("UserLeaderBoardWidget"),
                                                                                 "RelatedDiscussion" => array("RelatedDiscussion"),
                                                                                 "CafeBuzz" => array("ListingPageAnA","CafeBuzz"),
                                                                                 "anaMisReport" => array("AnaReport"),
										 "questionClosure" => array("questionClosure"),
                                                                                 "AskAQuestion" => array("AskAQuestion"),
										 "ANA" => array("mobile_messageboard_post_forms","mobile_messageboard"),
										 "relatedData" => array("relatedData","relatedData_server"),
										  "Tagging" => array("TaggingCMS", "TaggingController", "TaggingDesktop"),
										  "mAnA5" => array("AnAPostMobile","AnAMobile","AnAController"),
										  "mTag5" => array("TagMobile"),
										  "common_api" => array("APICrons"),
										  "mCollegeReviews5" => array("CollegeReviewsController"),
										  "CollegeReviewForm" => array("CollegeReviewForm", "CollegeReviewController", "CollegeReviewCrons", "CollegeReviewInstituteReplyController", "SolrIndexing")
								),
					"search" => array("nationalCategoryList" => array("NationalCategoryList"),
									  "search" => array("Search","SearchEnterprise","AutoSuggestor","SearchQER","Test","searchCI", "SearchV2", "AutoSuggestorV2","SearchV3"),
									  	"indexer" => array("NationalIndexer"),
										"msearch" => array("Msearch"),
										"msearch5" => array("MsearchV3"))
);


define("ENT_EE_REPORT_DAY_SPAN", 170);
define("ENT_DASHBOARD_TYPE_SLOWPAGES", "slowpages");
define("ENT_DASHBOARD_TYPE_MEMORY", "memory");
define("ENT_DASHBOARD_TYPE_EXCEPTION", "exception");
define("ENT_DASHBOARD_TYPE_SEARCH_TRACKING",'searchtracking');
define("ENT_DASHBOARD_TYPE_DB_ERROR", "dberror");
define("ENT_DASHBOARD_TYPE_CRON_LAG", "cronlag");
define("ENT_DASHBOARD_TYPE_CRON_ERROR", "cronerror");
define("ENT_DASHBOARD_TYPE_SLOWQUERY", "slowquery");
define("ENT_DASHBOARD_TYPE_CLIENT_SIDE", "clientside");
define("ENT_DASHBOARD_TYPE_JS_ERROR","jserror");
define("ENT_DASHBOARD_TYPE_SQLI","sqli");
define("ENT_DASHBOARD_TYPE_GOOGLEWEBLIGHT", "googleweblight");
define("ENT_DASHBOARD_TYPE_API_REPORT", "apireport");
define("ENT_DASHBOARD_MAIN", "dashboard");
define("ENT_DASHBOARD_CICD", "cicd");
define("ENT_DASHBOARD_TYPE_CACHE", "heavycache");
define("ENT_DASHBOARD_TYPE_SPEARALERTS", "spearalerts");
define("ENT_DASHBOARD_TYPE_BOTREPORT", "botreport");
define("ENT_DASHBOARD_TYPE_HTTPSTATUSCODES", "httpstatuscodes");
define("ENT_DASHBOARD_TYPE_TRAFFICREPORT", "trafficreport");
define("ENT_DASHBOARD_TYPE_JSB9_REPORT", "jsb9report");
define("ENT_DASHBOARD_TYPE_HIGH_SQL_QUERIES", "highsqlqueries");
define("ENT_DASHBOARD_TYPE_SOLR_ERRORS", "solrerrors");

global $DASHBOARD_TYPE_NAME;
$DASHBOARD_TYPE_NAME = array(
						ENT_DASHBOARD_TYPE_SLOWPAGES => "Slow Pages",
						ENT_DASHBOARD_TYPE_MEMORY => "High-Memory Pages",
						ENT_DASHBOARD_TYPE_EXCEPTION => "Exceptions",
						ENT_DASHBOARD_TYPE_DB_ERROR => "DB-Errors",
						ENT_DASHBOARD_TYPE_CRON_LAG => "Cron Lag",
						ENT_DASHBOARD_TYPE_CRON_ERROR => "Cron Error",
						ENT_DASHBOARD_TYPE_SLOWQUERY => "Slow Query",
						ENT_DASHBOARD_TYPE_JS_ERROR => "JS Error",
						ENT_DASHBOARD_MAIN => "Dashboard",
                        ENT_DASHBOARD_TYPE_HTTPSTATUSCODES => "HTTP Status Codes",
                        ENT_DASHBOARD_TYPE_TRAFFICREPORT => "Traffic Report",
                        ENT_DASHBOARD_TYPE_BOTREPORT => "Bot Report",
                        ENT_DASHBOARD_TYPE_SEARCH_TRACKING => 'Search Trends',
                        ENT_DASHBOARD_TYPE_SOLR_ERRORS => 'Solr Errors',
                        ENT_DASHBOARD_TYPE_CI_CD => 'CI / CD',
				);
global $CRON_MAPPING;
$CRON_MAPPING = array(
			"/mailer/MailerProcessor/processMailers" => "ugc",
			"/searchAgents/searchAgents_Server/oldLeadsMatching" => 'studyabroad',	
			"/listingPosting/AbroadListingPosting/getNotFoundExternalURL" => 'studyabroad',
			"/MultipleApply/MultipleApply/createAutoResponses" => 'ldb',
			"/searchAgents/searchAgents_Server/matchingLeads" => 'ldb',
			"/lms/Porting/startPorting" => 'ldb',
			"/MultipleApply/MultipleApply/createAutoResponsesMobileAndDesktop" => 'ldb',
			"/searchAgents/MatchedResponseAgent/run" => 'ldb',
			"/search/Indexer/processIndexLog/" => 'studyabroad',
			"/beacon/Beacon_server/storePreComputedAlsoViewedCourses/" => 'ldb',
			"/mailer/MailerProcessor/processProductMailers" => 'ldb',
			"/LeadExport/mailMatchResponses" => 'ldb',
			"/lms/Porting/startEmailPorting" => 'ldb',
			"/mailer/MailerProcessor/processOtherProductMailers" => 'ldb'
);

global $ENT_EE_REPORT_TABS;
$ENT_EE_REPORT_TABS = array("trends" => "Trends",
					"pagetrends" => "Page-wise Trends",
                    "realtime" => "Real Time",
                    "yesterday" => "Yesterday",
					"detailedreport" => "Detailed Report",
					"diffReport" => "Diff Report",
					);

global $ENT_EE_SERVERS;
$ENT_EE_SERVERS = array(	"Master:81" => "Master:81",  
											"Slave01:71" => 'Slave01:71',
											"Slave02:72" => 'Slave02:72',
											);

global $SHIKSHA_PROD_SERVERS;
$SHIKSHA_PROD_SERVERS = array(	"10.10.16.91" => "10.10.16.91",
                                "10.10.16.92" => "10.10.16.92",
                                "10.10.16.93" => "10.10.16.93",
                                "10.10.16.81" => "10.10.16.81",
                                "10.10.16.71" => "10.10.16.71",
                                "10.10.16.72" => "10.10.16.72",
								"10.10.16.82" => "10.10.16.82"			
											);                                            
                                                                          
global $BOT_STATUSES;
$BOT_STATUSES = array(	        "-1" => "Bad Bot",
                                "-2" => "IP Blocked",
                                "-3" => "User Agent Blocked"
                ); 
                   
global $CAPTCHA_STATUSES;
$CAPTCHA_STATUSES = array(	    "show_captcha" => "Captcha Shown",
                                "verify_captcha" => "Captcha Verified"
                );  


global $HTTPSTATUSCODES;
$HTTPSTATUSCODES = array(
			"500" => "500",
			"501" => "501",
			"502" => "502",
			"503" => "503",
			"504" => "504",
			"505" => "505",
			"506" => "506",
			"507" => "507",
			"508" => "508",
			"510" => "510",
			"511" => "511",
			"599" => "599"
        );

global $HTTPSTATUSCODES_COLORS;
$HTTPSTATUSCODES_COLORS = array(
	       	"500" => "#006600",
	        "501" => "#3A80E0",
	        "502" => "#ff9900",
	        "503" => "#d60e30",
	        "504" => "#9c27b0",
	        "505" => "#009688",
	        "506" => "#cddc39",
	        "507" => "#000000",
	        "508" => "#80DAEB",
	        "510" => "#FF8243",
	        "511" => "#BAB86C",
	        "599" => "#17806D"
        );
                                                                          
global $ENT_EE_SERVERS_COLORS;
$ENT_EE_SERVERS_COLORS = array(	"Master:81" 		=> "#006600",  
								"Slave01:71"           => "#3A80E0", 
								"Slave02:72"   => "#ff9900",
                                "All"   => "#000000"
                                );

global $SHIKSHA_PROD_SERVERS_COLORS;
$SHIKSHA_PROD_SERVERS_COLORS = array(
                                    "10.10.16.91" => "#006600",  
                                    "10.10.16.92" => "#3A80E0", 
                                    "10.10.16.93" => "#ff9900",
                                    "10.10.16.81" => "#d60e30",  
                                    "10.10.16.71" => "#9c27b0", 
                                    "10.10.16.72" => "#009688",
                                    "10.10.16.82" => "#cddc39",
                                    "All"   => "#000000"
                                );

global $BOT_STATUS_COLORS;
$BOT_STATUS_COLORS = array(
                                    "-1" => "#006600",  
                                    "-2" => "#3A80E0", 
                                    "-3" => "#ff9900",
                                    "All"   => "#000000"
                                );

global $CAPTCHA_STATUS_COLORS;
$CAPTCHA_STATUS_COLORS = array(
                                    "show_captcha" => "#006600",  
                                    "verify_captcha" => "#3A80E0", 
                                    "All"   => "#000000"
                                );

global $ENT_CLIENTSIDE_PERF_METRICS;
$ENT_CLIENTSIDE_PERF_METRICS = array(
									"homepage" => array("http://www.shiksha.com/"),
									"categorypage" => array("http://www.shiksha.com/mba/colleges/mba-colleges-in-delhi-ncr",
															 "http://www.shiksha.com/mba/colleges/mba-colleges-in-mumbai-all",
															 "http://www.shiksha.com/mba/colleges/mba-colleges-in-bangalore",
															 "http://www.shiksha.com/mba/colleges/mba-colleges-in-pune",
															 "http://www.shiksha.com/mba/colleges/mba-in-finance-colleges-in-india",
															 "http://engineering.shiksha.com/be-btech-courses-in-delhi-ncr-ctpg",
															 "http://engineering.shiksha.com/be-btech-courses-in-india-accepts-jee-mains-score-ctpg",
															 "http://design.shiksha.com/fashion-textile-designing-courses-in-mumbai-all-categorypage-13-69-1-0-0-10224-1-2-0-none-1-0",
															 "http://animation.shiksha.com/animation-courses-in-bangalore-categorypage-12-89-1-0-0-278-106-2-0-none-1-0",
															 "http://testprep.shiksha.com/engineering-entrance-exams-delhi-ncr-categorypage-14-48-1-0-0-10223-1-2-0-none-1-0"),
									"rankingpage" => array( "http://www.shiksha.com/mba/ranking/top-mba-colleges-delhi-ncr/2-0-0-10223-0",
															"http://www.shiksha.com/mba/ranking/top-mba-colleges-mumbai-all/2-0-0-10224-0",
															"http://www.shiksha.com/mba/ranking/top-mba-colleges-bangalore/2-0-0-278-0",
															"http://www.shiksha.com/mba/ranking/top-mba-colleges-pune/2-0-0-174-0",
															"http://www.shiksha.com/mba/ranking/top-executive-mba-colleges-india/18-2-0-0-0",
															"http://www.shiksha.com/top-engineering-colleges-in-india-rankingpage-44-2-0-0-0",
															"http://www.shiksha.com/top-fashion-design-colleges-in-india-rankingpage-94-2-0-0-0",
															"http://www.shiksha.com/top-commerce-colleges-in-india-rankingpage-97-2-0-0-0",
															"http://www.shiksha.com/top-law-colleges-in-india-rankingpage-56-2-0-0-0",
															"http://www.shiksha.com/top-engineering-colleges-in-india-accepting-bitsat-score-rankingpage-44-2-0-0-6218"),
									"exampage" => array("http://www.shiksha.com/mba/exam/cat",
														"http://www.shiksha.com/mba/exam/mat",
														"http://www.shiksha.com/mba/exam/xat",
														"http://www.shiksha.com/nid-entrance-exam-exampage",
														"http://www.shiksha.com/jee-mains-exampage",
														"http://www.shiksha.com/bitsat-exampage",
														"http://www.shiksha.com/jee-advanced-exampage",
														"http://www.shiksha.com/mba/exam/iift",
														"http://www.shiksha.com/mba/exam/micat",
														"http://www.shiksha.com/mba/exam/cmat"),
									"institutepage" => array("http://www.shiksha.com/getListingDetail/307/institute/college-Indian-Institute-Of-Management-Ahmedabad-Iim-A-Ahmedabad-India",
															 "http://www.shiksha.com/getListingDetail/20190/institute/college-Indian-Institute-Of-Management-Calcutta-Iim-C-Kolkata-India",
															 "http://www.shiksha.com/getListingDetail/4223/institute/college-Management-Development-Institute-Gurgaon-Mdi-Gurgaon-India",
															 "http://www.shiksha.com/getListingDetail/2999/institute/college-Indian-Institute-Of-Technology-Iit-Kharagpur-Kharagpur-India",
															 "http://www.shiksha.com/getListingDetail/3031/institute/college-Indian-Institute-Of-Technology-Iit-Madras-Chennai-India",
															 "http://www.shiksha.com/getListingDetail/23920/institute/college-Delhi-Technological-University-Delhi-India",
															 "http://www.shiksha.com/getListingDetail/27356/institute/college-National-Institute-Of-Fashion-Technology-Nift-Delhi-Delhi-India",
															 "http://www.shiksha.com/getListingDetail/27682/institute/college-Pearl-Academy-Delhi-Ncr-West-Campus-Delhi-India",
															 "http://www.shiksha.com/getListingDetail/21891/institute/college-Symbiosis-Institute-Of-Design-Sid-Pune-India",
															 "http://www.shiksha.com/getListingDetail/27355/institute/college-National-Institute-Of-Fashion-Technology-Patna-India"),
									"coursepage"   => array("http://www.shiksha.com/b-tech-in-computer-science-engineering-iitk-indian-institute-of-technology-kanpur-kanpur-listingcourse-109925",
															"http://www.shiksha.com/b-e-in-mechanical-engineering-bits-pilani-birla-institute-of-technology-and-science-bits-pilani-pilani-listingcourse-119871",
															"http://www.shiksha.com/b-tech-in-computer-science-engineering-vit-vit-university-vellore-vellore-listingcourse-119914",
															"http://www.shiksha.com/mba/course/master-business-administration-nmims-university-school-business-management/228082",
															"http://www.shiksha.com/mba/course/master-business-administration-symbiosis-institute-business-management-pune/13280",
															"http://www.shiksha.com/mba/course/post-graduate-diploma-management-prin-l-n-welingkar-institute-management-development-research-weschool/113283",
															"http://www.shiksha.com/4-Yr-Ba-Honors-Programmes-In-Communication-Design-course-in-Delhi-Pearl-Academy-Of-Fashion-Paf-course-information-listingcourse-153399",
															"http://www.shiksha.com/getListingDetail/29475/course/course-B-Des-In-Fashion-Design-Pune-India",
															"http://www.shiksha.com/getListingDetail/115389/course/course-B-A-Hons-In-Fashion-Design-Jaipur-India",
															"http://www.shiksha.com/mba/course/post-graduate-diploma-management-k-j-somaiya-institute-management-studies-research-simsr/11644"));

global $clientSideModuleNames;
$clientSideModuleNames = array("homepage" => "Home Page",
							   "categorypage" => "Category Page",
							   "rankingpage" => "Ranking Page",
							   "exampage" => "Exam Page",
							   "institutepage" => "Institute Page",
							   "coursepage" => "Course Page");

global $DESKTOP_PERFORMANCE_PARAMS;
$DESKTOP_PERFORMANCE_PARAMS = array( "speedScore" => "Speed Score",
									 "numberResources" => "Number of Resources",
									 "numberHosts" => "Number of Hosts",
									 "totalRequestBytes" => "Total Requestes Bytes",
									 "numberStaticResources" => "Number of Static Resources",
									 "htmlResponseBytes" => "Html Response Bytes",
									 "cssResponseBytes" => "CSS Response Bytes",
									 "imageResponseBytes" => "Images Response Bytes",
									 "javascriptResponseBytes" => "JS Response Bytes",
									 "otherResponseBytes" => "Other Response Bytes",
									 "numberJsResources" => "Number of JS Resources",
									 "numberCssResources" => "Number of CSS Resources");

global $MOBILE_PERFORMANCE_PARAMS;
$MOBILE_PERFORMANCE_PARAMS = array(  "speedScore" => "Speed Score",
									 "usabilityScore" => "Usability Score",
									 "numberResources" => "Number of Resources",
									 "numberHosts" => "Number of Hosts",
									 "totalRequestBytes" => "Total Requestes Bytes",
									 "numberStaticResources" => "Number of Static Resources",
									 "htmlResponseBytes" => "Html Response Bytes",
									 "cssResponseBytes" => "CSS Response Bytes",
									 "imageResponseBytes" => "Images Response Bytes",
									 "javascriptResponseBytes" => "JS Response Bytes",
									 "otherResponseBytes" => "Other Response Bytes",
									 "numberJsResources" => "Number of JS Resources",
									 "numberCssResources" => "Number of CSS Resources");


global $DATABASE_DNS_NAME_MAPPING;
$DATABASE_DNS_NAME_MAPPING = array(  "masterdb.shiksha.jsb9.net" => "Master DB",
									 "slave01.shiksha.jsb9.net" => "Slave1 DB",
									 "slave02.shiksha.jsb9.net" => "Slave2 DB",
									 "slave03.shiksha.jsb9.net" => "MIS DB",
									 "mailermaster.shiksha.jsb9.net" => "MMM DB"
									 );
