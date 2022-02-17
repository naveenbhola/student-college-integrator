<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['scaffolding_trigger'] = 'scaffolding';
|
| This route lets you set a "secret" word that will trigger the
| scaffolding feature for added security.
|
*/
//exam url at root
$domain   = (isset($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : @getenv('HTTP_HOST');
$isAbroad = stripos($domain,'studyabroad');

$route['default_controller'] = "shiksha";
$route['scaffolding_trigger'] = "";

/* url for applycontent pages is manually entered by cms user  */
$route['(.*)-applycontent(.*)'] = "applyContentPage/ApplyContentPage/applyContentPage/$2";
$route['nationalrecommendations(.*)'] = "recommendation/recommendation/processNationalMailer$1";
$route['recommendations(.*)'] = "recommendation/recommendation/processMailer$1";
$route['showRecommendations(.*)'] = "categoryList/CategoryList/recommendations$1";

//Details Page URL
/* Removing deprecated functionalities notification and scholarship
$route['getListingDetail(.*)/notification(.*)'] = "listing/Listing/getDetailsForListing$1/notification$2";
$route['getListingDetail(.*)/scholarship(.*)'] = "listing/Listing/getDetailsForListing$1/scholarship$2";
*/


$route['getListingDetail(.*)'] = "mobile_listing/Listing_mobile/listingDetailWap$1";

$route['college-admissions-online-mba-application-forms'] = "Online/OnlineForms/showOnlineFormsHomepage";
$route['indira-group-of-institutions.html'] = "instituteSite/indiraGroup";
$route['International-School-of-Business-and-Media.html'] = "instituteSite/isbm";
/*
| -------------------------------------------------------------------
| Beacon URL HACK START
| Bypass beacon image URL
| -------------------------------------------------------------------
|
*/
$path = (isset($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : @getenv('PATH_INFO');
$result = strpos($path,'beacon');
if (!$result) {
	$route['(.*)-listingoverviewtab-(.*)'] = "mobile_listing/Listing_mobile/listingDetailWap/$2/institute";
	$route['(.*)-listingcourse-(.*)'] = "mobile_listing/Listing_mobile/listingDetailWap/$2/course";
	$route['(.*)-listinganatab-(.*)'] = "listing/ListingPage/listingAnaTab/$2";
	$route['(.*)-campus-representatives-discussions-(.*)'] = "listing/ListingPage/listingCampusRepTab/$2";
	$route['(.*)-listingalumnitab-(.*)'] = "listing/ListingPage/listingAlumniTab/$2";
	$route['(.*)-listingmediatab-(.*)'] = "listing/ListingPage/listingMediaTab/$2";
	$route['(.*)-listingcoursetab-(.*)'] = "listing/Listing/getDetailsForListingNew/$2/institute/course";
}
/*
| -------------------------------------------------------------------
| Beacon URL HACK END
| Bypass beacon image URL
| -------------------------------------------------------------------
|
*/

/*Shiksha Apply Page - This must be kept ABOVE the courselisting identifier*/
$route['(.*)-rmc-(.*)'] = "rateMyChancePage/rateMyChancePage/rateMyChance/rateMyChancePage/$2";
$route['submission-success'] = "rateMyChancePage/rateMyChance/successPage";


/*
 * Study Abroad Listings Pages..
 */
$route['(.*)-courselisting-(.*)'] = "listing/abroadListings/courseListing/$2";
$route['(.*)-deptlisting-(.*)'] = "listing/abroadListings/departmentListing/$2";
$route['(.*)-univlisting-(.*)'] = "listing/abroadListings/universityListing/$2";


/* ANA */

/**
 *  mobile ANA urls
 */

/*
$route['messageBoard/MsgBoard/discussionHome/(.*)'] = "ANA/mobile_messageboard/render_messageboard_homepage/$1";
$route['messageBoard/MsgBoard/postQuestionFromCafeForm(.*)'] = "ANA/mobile_messageboard/render_ask_question_page$1";
$route['getTopicDetail(.*)'] = "ANA/mobile_messageboard/get_topic_detail$1";
$route['(.*)-qna-(.*)'] 	 = "ANA/mobile_messageboard/get_topic_detail/$2";
$route['(.*)-discussion-(.*)'] 	 = "ANA/mobile_messageboard/get_topic_detail/$2";
$route['(.*)-announcement-(.*)'] 	 = "ANA/mobile_messageboard/get_topic_detail/$2";
$route['(.*)-dscns-(.*)']   = "ANA/mobile_messageboard/get_topic_detail/$2";
$route['(.*)-ancmt-(.*)']         = "ANA/mobile_messageboard/get_topic_detail/$2";
*/
$route['getTopicDetail(.*)'] = "messageBoard/MsgBoard/topicDetails$1";
$route['(.*)-qna-(.*)'] = "messageBoard/MsgBoard/topicDetails/$2";
$route['(.*)-discussion-(.*)'] = "messageBoard/MsgBoard/topicDetails/$2";
$route['(.*)-announcement-(.*)'] = "messageBoard/MsgBoard/topicDetails/$2";
$route['(.*)-dscns-(.*)'] = "messageBoard/MsgBoard/topicDetails/$2";
$route['(.*)-ancmt-(.*)'] = "messageBoard/MsgBoard/topicDetails/$2";
$route['ANA/mobile_messageboard/render_ask_question_page(.*)'] = "messageBoard/MsgBoard/postQuestionFromCafeForm$1";
$route['ANA/mobile_messageboard/render_messageboard_homepage/(.*)'] = "messageBoard/MsgBoard/discussionHome/$1";


/* EVENT */
$route['getEventDetail(.*)'] = "events/Events/eventDetail$1";
$route['(.*)-details-update'] = "events/Events/eventDetail/$2";
$route['(.*)-details-update-(.*)'] = "events/Events/eventDetail/$2";
/* NETWORK */
/* Removing deprecated functionality
$route['getCollegeGroupDetail(.*)'] = "network/Network/collegeNetwork$1";
$route['getSchoolGroupDetail(.*)'] = "network/Network/schoolNetwork$1";
*/
/* ARTICLE */
$route['getArticleDetail(.*)'] = "blogs/shikshaBlog/blogDetails$1";
$route['(.*)-article-(.*)'] = "blogs/shikshaBlog/blogDetails/$2";

/* CATEGORY PAGES */
//$route['getCategoryPage/colleges(.*)'] = "shiksha/category$1";
$route['getCategoryPage/colleges(.*)'] = "mobile_category/CategoryMobile/categoryPage$1";
$route['(.*)-categorypage-(.*)'] = "mobile_category/CategoryMobile/categoryPage/$2";
$route['(.*)/CategoryList/categoryPage/(.*)'] = "mobile_category/CategoryMobile/categoryPage/$2";

$route['(.*)-fees-upto-(.*)-ctpg'] = "categoryList/CategoryList/categoryPage/$1-fees-upto-$2/RNRURL";
$route['(.*)-approved-(.*)-ctpg'] = "categoryList/CategoryList/categoryPage/$1-approved-$2/RNRURL";
$route['(.*)-(recognised|recognized)-(.*)-ctpg'] = "categoryList/CategoryList/categoryPage/$1-recognized-$3/RNRURL";
$route['(.*)-accepts-(.*)-ctpg'] = "categoryList/CategoryList/categoryPage/$1-accepts-$2/RNRURL";
$route['(.*)-ctpg'] = "mobile_category/CategoryMobile/categoryPage/$1/RNRURL";
$route['similar-institutes(.*)'] = "categoryList/CategoryList/similarInstitutes/$1";

/* TESTPREP */
//$route['testprep(.*)'] = "shiksha/testprep_category_page$1";
$route['testprep(.*)'] = "categoryList/CategoryList/oldTestPrepCategoryPage$1";

/* GET USER PROFILE */
$route['getUserProfile(.*)'] = "messageBoard/MsgBoard/userProfile$1";
$route['author(.*)'] = "blogs/shikshaBlog/getUserBlogs$1";
$route['inviteFriends/(.*)'] = "inviteFriends/InviteFriends/$1";

/* EXAM ARTICLE PAGE */
$route['education-test-preparation/(.*)'] = "shiksha/showExamArticlePage/$1";

/* Search */
$route['search/searchCI(.*)'] = "search/searchCI$1";
$route['shikshaSearchApi(.*)'] = "search/Search/getSearchResultApi$1";
$route['searchWidget/(.*)'] = "search/Search/searchWidget/$1";
$route['search/top-Education-Searches(.*)'] = "search/Search/topEducationSearches$1";
$route['search/AbroadSearch/(.*)'] = "search/AbroadSearch/$1";
$route['search-abroad(.*)'] = "search/AbroadSearch/index/$1";

/*New routes for Search*/
$route['search/AutoSuggestor/(.*)'] = "search/AutoSuggestor/$1";
$route['search/SearchEnterprise/(.*)'] = "search/SearchEnterprise/$1";
$route['search/Indexer/(.*)'] = "search/Indexer/$1";
$route['search/Test/(.*)'] = "search/Test/$1";
$route['search/Search/getInstituteSearchResults(.*)'] = "search/Search/getInstituteSearchResults/$1";
$route['search/Search/getContentSearchResults(.*)'] = "search/Search/getContentSearchResults/$1";
$route['search/Search/getCMSSearchResults(.*)'] = "search/Search/getCMSSearchResults/$1";
$route['searchmatrix/SearchMatrix/trackSearchQuery(.*)'] = "searchmatrix/SearchMatrix/trackSearchQuery$1";

$route['search/(.*)'] = "msearch/Msearch/$1";
$route['list-(.*)\.html'] = "msearch/Msearch/displaySearch/$1";

/*Ranking Pages*/
$route['(.*)-rankingpage-(.*)'] = "ranking/RankingMain/index/$2";
$route['(.*)/ranking/(.*)/(.*)'] = RANKING_PAGE_MODULE."/RankingMain/index/$3";

$route['marketing/Marketing/index/pageID/(.*)'] = "multipleMarketingPage/Marketing/index/pageID/$1";

$route['helpline(.*)'] = "messageBoard/MsgBoard/topicDetails/1761421";
// added for customized online form ---- sem purpose
$route['(.*)-online-application-form-mba-(.*)'] = "Online/OnlineForms/showOnlineForms/$2";
$route['(.*)-online-application-form-engineering-(.*)'] = "Online/OnlineForms/showOnlineForms/$2";
$route['(.*)-online-application-form-law-(.*)'] = "Online/OnlineForms/showOnlineForms/$2";
$route['(.*)-online-application-form-design-(.*)'] = "Online/OnlineForms/showOnlineForms/$2";

$route['cat-results(.*)'] = "blogs/shikshaBlog/openLink";

$route['snap-results(.*)'] = "blogs/shikshaBlog/SNAPResultsPage";
$route['ibsat-results(.*)'] = "blogs/shikshaBlog/IBSATResultsPage";
$route['xat-results(.*)'] = "blogs/shikshaBlog/XATResultsPage";
$route['nmat-results(.*)'] = "blogs/shikshaBlog/NMATResultsPage";
$route['mat-results(.*)'] = "blogs/shikshaBlog/MATResultsPage";
$route['micat-results(.*)'] = "blogs/shikshaBlog/MICATResultsPage";
$route['cmat-results(.*)'] = "blogs/shikshaBlog/CMATResultsPage";

/*Career Product*/
$route['(.*)-cc-(.*)'] = 'Careers/CareerController/getCareerDetailPage/$2';
$route['career-options-after-12th(.*)']="Careers/CareerController/getCareerHomepage";
$route['career-opportunities(.*)']="Careers/CareerController/getExpressInterestPage";
$route['career-counselling(.*)']="Careers/CareerController/recommendCareerOption";
$route['careers-after-12th-list(.*)'] = "Careers/CareerController/getCareersListForFooterOnHomePage$1";

$route['jgbscampaign(.*)'] = "shiksha/jgbscampaign";


//routes for cafe search                                             
  $route['(.*)search1(.*)'] = "messageBoard/MsgBoard/discussionHome";
  $route['(.*)search2(.*)'] = "messageBoard/MsgBoard/discussionHome"; 
  
/*
 * COURSE PAGES URLs..
 */
$route['(.*)-faq-coursepage/(.*)'] = "coursepages/CoursePage/permaLinkPage/$1/$2";
$route['(.*)-coursepage'] = "coursepages/CoursePage/coursePages/$1";
$route['(.*)/(.*)-home'] = "coursepages/CoursePage/coursePages/$2";
$route['(.*)-chp(.*)'] = "coursepages/CoursePage/courseHomePage/$2";
$route['(.*)/resources/(.*)-(questions/|questions)(.*)'] = "coursepages/CoursePage/coursePages/$2-questions/$4";
$route['(.*)/resources/(.*)-(faq/|faq)(.*)'] = "coursepages/CoursePage/coursePages/$2-faq/$4";
$route['(.*)/resources/(.*)-(discussions/|discussions)(.*)'] = "coursepages/CoursePage/coursePages/$2-discussions/$4";
$route['(.*)/resources/(.*)-(news-articles/|news-articles)(.*)'] = "coursepages/CoursePage/coursePages/$2-news-articles/$4";

$route['mba/resources/ask-current-mba-students'] = "mCampusAmbassador5/CCHomepageController/campusConnectHomepage/";
$route['campus-representatives-from-institutes'] = "CA/CampusAmbassador/getCADetailsForMarketingPage/";
$route['mba/resources/ask-current-mba-students/(.*)-ccpage-(:num)'] = "mCampusAmbassador5/CCHomepageController/campusConnectIntermediatePage/$1/$2";
$route['campus-representatives-from-institutes-(.*)'] = "CA/CampusAmbassador/getCADetailsForMarketingPage/$1";

$route['(.*)-exams-colleges-courses'] = "mCampusAmbassador5/MentorController/mentorHomepage/$1"; // for mentor
/*
 * ARTICLE PAGES
 */
$route['news/(.*)-article-(.*)'] = "blogs/shikshaBlog/blogDetails/$2";
$route['news-(.*)'] = "blogs/shikshaBlog/getNewsArticles/$1";
$route['news(.*)'] = "blogs/shikshaBlog/getNewsArticles$1";

/* Redirect from HTML5 to HTML4 if this is a featured phone */
$route['muser5/MobileUser/forgot_pass'] = "muser/MobileUser/forgot_pass";
$route['muser5/MobileUser/login'] = "muser/MobileUser/login";
$route['muser5/MobileUser/register'] = "muser/MobileUser/register";
$route['mcommon5/MobileSiteStatic/aboutus'] = "mcommon/MobileSiteStatic/aboutus";
$route['mcommon5/MobileSiteStatic/studentHelpLine'] = "mcommon/MobileSiteStatic/studentHelpLine";
$route['mcommon5/MobileSiteStatic/privacy'] = "mcommon/MobileSiteStatic/privacy";
$route['mcommon5/MobileSiteStatic/terms'] = "mcommon/MobileSiteStatic/terms";
$route['mcommon5/MobileSiteStatic/contactUs'] = "mcommon/MobileSiteStatic/contactUs";
/*terms and policies */
$route['privacy-policy.html'] = "mcommon/MobileSiteStatic/privacy";
$route['terms-conditions.html'] = "mcommon/MobileSiteStatic/terms";

/*
 * Study Abroad Category Pages..
 */
$route['(.*)-dc1(.*)'] = "categoryList/AbroadCategoryList/ldbCoursePage/$1/$2";
$route['(.*)-ds1(.*)'] = "categoryList/AbroadCategoryList/ldbCourseSubCategoryPage/$1/$2";
$route['(.*)-sl1(.*)'] = "categoryList/AbroadCategoryList/categorySubCategoryCourseLevelPage/$1/$2";
$route['(.*)-cl1(.*)'] = "categoryList/AbroadCategoryList/categoryCourseLevelPage/$1/$2";
$route['(.*)-countrypage-(.*)'] = "categoryList/AbroadCategoryList/countryPage/$1/$2";
//$route['(.*)-countrypage(.*)'] = "categoryList/AbroadCategoryList/countryPage/$1/$2";
$route['(.*)-countrypage(.*)'] = "categoryPage/CategoryPage/redirect301/$1/$2"; // SA-1745 - Redirect to the appropriate country page
// exam category page
$route['(.*)-colleges-accepting-(.*)-scores(.*)'] = "categoryList/AbroadCategoryList/examAcceptedCategoryPage/$1/$2/$3";
$route['(.*)-colleges-for-(.*)-score-(.*)'] = "categoryList/AbroadCategoryList/examAcceptedCategoryPage/$1/$2/$3";
/* 
 * SA Category page dir based urls
 */
$route['(.*)/(.*)-((dc|cl|ds|sl)(\-[0-9]+)?)']="categoryPage/CategoryPage/abroadCategoryPage/$1/$2/$3";

/*
 * Study Abroad Ranking Pages..
 */
$route['(.*)-abroadranking(.*)'] = "rankingPage/RankingPages/rankingPage/$2";
/*
 * Study Abroad Compare Pages..
 */
$route['comparepage-(.*)'] = "commonModule/CompareCourses/initComparePage/$1";
/*
 * Study Abroad Exam Pages..
 */
//$route['(.*)-abroadexam(.*)'] = "contentPage/contentPage/contentPage/$1";
$route['(.*)-abroadexam(.*)'] = "contentPage/contentExamPages/abroadExamPage/$1/$2";

/*
 * Study Abroad Content Org Pages..
 */
$route['(.*)-stagepage'] = "abroadContentOrg/AbroadContentOrgPages/abroadContentOrgPages/$1";


/* College Predictor */
$route['(.*)-college-predictor'] = "CP/CollegePredictorController/loadRankTab/$1";
$route['(.*)-cut-off-predictor'] = "CP/CollegePredictorController/loadCollegeTab/$1";
$route['(.*)-branch-predictor'] = "CP/CollegePredictorController/loadBranchTab/$1";
$route['(.*)-cut-off-(.*)'] = "CP/CollegePredictorController/loadInstitutePage/$1/$2";

/* Rank Predictor */
$route['(.*)-rank-predictor'] = "RP/RankPredictorController/loadRankPage/$1";
$route['engineering/exam/rank-colleges-predictors'] = "RP/RankPredictorController/commonPredictorPage";

/*
 * Study Abroad All Country Home Page
 */
$route['abroad-countries-countryhome'] = 'countryHome/CountryHome/allCountryHome';
// abroad sign up thank you page
$route['thank-you-for-downloading-(.*)'] = 'studyAbroadCommon/AbroadSignup/thankYouPage/$1';

/*
 * Study Abroad Country Home Pages
 */
//$route['(.*)-countryhome'] = 'countryHome/CountryHome/countryHome/$1';
$route['(.*)-countryhome'] = 'countryHomePage/CountryHomePage/combinedAbroadCountryHome/$1';

/* Exam Page */
$route['(.*)-exampage'] = "examPages/ExamPageMain/getExamPage/$1";
/* Exam page new URL */
$route['(.*)/exam/(.*)/(.*)'] = "mobile_examPages5/ExamPageMain/getExamPage/$2-$3";
$route['(.*)/exam/(.*)'] = "mobile_examPages5/ExamPageMain/getExamPage/$2";
/* LF-3031 */
$route['([^\/]+)/exam'] = "examPages/ExamPageMain/listExams/$1";


/* Entrance Exam pages */
$route['jee-main(.*)'] = "blogs/shikshaBlog/showEngineeringExams/jee-main/$1";
$route['jee-advanced(.*)'] = "blogs/shikshaBlog/showEngineeringExams/jee-advanced/$1";
$route['bitsat(.*)'] = "blogs/shikshaBlog/showEngineeringExams/bitsat/$1";
$route['karnataka-cet(.*)'] = "blogs/shikshaBlog/showEngineeringExams/karnataka-cet/$1";
$route['viteee(.*)'] = "blogs/shikshaBlog/showEngineeringExams/viteee/$1";
$route['srmeee(.*)'] = "blogs/shikshaBlog/showEngineeringExams/srmeee/$1";
$route['comedk(.*)'] = "blogs/shikshaBlog/showEngineeringExams/comedk/$1";
$route['keam(.*)'] = "blogs/shikshaBlog/showEngineeringExams/keam/$1";
$route['rpet(.*)'] = "blogs/shikshaBlog/showEngineeringExams/rpet/$1";
$route['wbjee(.*)'] = "blogs/shikshaBlog/showEngineeringExams/wbjee/$1";
$route['uptu-see(.*)'] = "blogs/shikshaBlog/showEngineeringExams/uptu-see/$1";
$route['upsee(.*)'] = "blogs/shikshaBlog/showEngineeringExams/upsee/$1";
$route['eamcet(.*)'] = "blogs/shikshaBlog/showEngineeringExams/eamcet/$1";
$route['tnea(.*)'] = "blogs/shikshaBlog/showEngineeringExams/tnea/$1";
$route['kiitee(.*)'] = "blogs/shikshaBlog/showEngineeringExams/kiitee/$1";

$route['(.*)-countrypage-(.*)'] = "categoryList/AbroadCategoryList/countryPage/$1/$2"; // Check purpose - desktop route in mobile routes?


$route['marketing/Marketing/tracker/pageID/(.*)'] = "multipleMarketingPage/Marketing/tracker/pageID/$1";
$route['marketing/Marketing/form/pageID/(.*)'] = "multipleMarketingPage/Marketing/form/pageID/$1";
$route['clientLandingPage-(.*)'] = "marketing/ClientLandingPage/index/$1";

/* Study abroad content */
$route['(.*)-articlepage-(.*)'] = "blogs/SAContent/getContentDetails/$2";
$route['(.*)-guidepage-(.*)'] = "blogs/SAContent/getContentDetails/$2";

/* consultant page */
$route['(.*)-overseas-education-consultant-(.*)'] = "consultantProfile/ConsultantPage/consultantPage/$2";

$route['shiksha-authors(.*)'] = "blogs/shikshaBlog/getAuthorProfilePage";

/*Study abroad shortlisted courses*/
//$route['shortlisted-courses-page'] = "categoryList/AbroadCategoryList/getShortlistPage/$2";
$route['my-saved-courses'] = "shortlistPage/ShortListPage/getShortlistedCourses";

/* Compare college tool */
$route['compare-colleges(.*)'] = "compareInstitute/compareInstitutes/compareInstitutesTool/$1";
$route['comparison-of(.*)'] = "compareInstitute/compareInstitutes/compareInstitutesTool/$1";

/* College Alumni review form */
$route['college-review-rating-form'] = "CollegeReviewForm/CollegeReviewForm/showReviewForm";
$route['college-review-rating-form/(.*)'] = "CollegeReviewForm/CollegeReviewForm/showReviewForm/$1";

/* Naukri Tool */
$route['best-colleges-for-jobs-based-on-mba(.*)'] = "NaukriTool/NaukriToolController/showNaukriTool";

/* Marketing Page */
$route['6steps-to-decide-your-mba-college'] = "shikshaHelp/ShikshaHelp/mba6StepsMarketingPage";

$route['ofconversion'] = "Online/OnlineFormConversionTracking/index";
$route['ofexception'] = "Online/OnlineFormConversionTracking/ofexception";


/* Myshortlist */
$route['my-shortlist-home'] = "mobile_myShortlist5/MyShortlistMobile/myShortlist";
$route['my-shortlist'] = "mShortlist5/ShortlistMobile/redirectToMBA";

/* College Reviews */
$route['colleges-reviews-cr'] = "CollegeReviewForm/CollegeReviewController/collegeReviewsHomepage";
$route['colleges-reviews-cr-(.*)'] = "CollegeReviewForm/CollegeReviewController/collegeReviewsHomepage/$1";
$route['(.*)-crpage'] = "CollegeReviewForm/CollegeReviewController/collegeIntermediatePage/$1";
$route['(.*)-crpage-(.*)'] = "CollegeReviewForm/CollegeReviewController/collegeIntermediatePage/$1/$2";

/*Event Calendar*/
$route['(.*)-exams-dates'] = "event/EventController/eventCalendar/$1";
$route['mba/resources/exam-calendar'] = "mEventCalendar5/EventCalendarController/loadEventCalendar/mba";

/*Mobile SMS Response*/
$route['SR-(.*)'] = "mailer/Mailer/GSR/$1";

$route['login'] = "commonModule/User/inLineLoginForm";

$route['checkAkamaiHeader'] = "shikshaHelp/ShikshaHelp/checkAkamaiHeaders";

$route['(.*)iim-predictor(.*)'] = "mIIMPredictor5/IIMPredictor/load/$1";
$route['mba/resources/iim-call-predictor-result'] = "mIIMPredictor5/IIMPredictor/loadDetailedResultPage";

/* for View All Tag Page*/
$route['tags'] = "messageBoard/TagDesktop";
$route['tags-(.*)'] = "messageBoard/TagDesktop";

/*Experts Panel Page*/
$route['experts'] =  "messageBoard/AnADesktop/getExpertsPanel";
$route['experts/(.*)'] =  "messageBoard/AnADesktop/getExpertsPanel/$1";

/* For Tag detail page */
$route['(.*)-tdp-(.*)'] = "messageBoard/TagDesktop";

if($isAbroad !== false){
	// Abroad Exam Content Pages
	$route['exams/(.*)'] = "contentPage/AbroadExamContentPage/index/$1";
}else{
	$route['exams/(.*)'] = "mobile_examPages5/ExamPageMain/getExamPage/$1/$2";
}

//$route['exams/(.*)/(.*)'] = "abroadExamContent/AbroadExamContentPage/index/$1/$2";
$route['sitemap'] = 'common/HTMLSitemap/sitemapHome';
$route['sitemap/browse-(.*)-colleges-by-location'] = 'common/HTMLSitemap/locationSitemap/$1';

$route['apply'] = 'applyHomePage/ApplyHomePage/applyHomePage';
$route['apply/counselors/(.*)-(.*)'] = 'applyHomePage/CounselorPage/counselorPage/$1/$2';
$route['([a-zA-Z\-\ ]+)/universities/([a-zA-Z0-9\-\ ]+)'] = "listingPage/ListingPage/abroadListingPage/";
$route['([a-zA-Z\-\ ]+)/universities/([a-zA-Z0-9\-\ ]+)/([a-zA-Z0-9\-\ ]+)'] = "listingPage/ListingPage/abroadListingPage/";


$route['([a-zA-Z\-\ ]+)/universities(-{0,1}[0-9]*)'] = "categoryPage/CategoryPage/countryPage/$1/$2"; // SA-1745
$route['([a-zA-Z\-\ ]+)'] = 'countryHome/CountryHome/combinedAbroadCountryHome/$1'; // SA-1736

/************national institute detail page********************/
$route['college/([a-zA-Z0-9\-\ ]*)-([0-9]+)/(articles|questions)(-(:num))?'] = "mobile_listing5/AllContentPageMobile/getAllContentPage/$2/$3/$5";
$route['university/([a-zA-Z0-9\-\ ]*)-([0-9]+)/(articles|questions)'] = "mobile_listing5/AllContentPageMobile/getAllContentPage/$2/$3";
$route['college/([a-zA-Z0-9\-\ ]*)-([0-9]+)'] = "mobile_listing5/InstituteMobile/getInstituteDetailPage/$2/institute";
$route['university/([a-zA-Z0-9\-\ ]*)-([0-9]+)'] = "mobile_listing5/InstituteMobile/getInstituteDetailPage/$2/university";

/* SHIPMENT  */
$route['apply/shipment'] = "shipmentModule/shipment/welcomePage";
$route['apply/shipment/shipping-information'] = "shipment/schedulePickupPage";
$route['apply/shipment/shipping-confirmation'] = "shipment/confirmationPage";

/* Abroad New Registration / Responses form*/
$route['signup'] = 'studyAbroadCommon/AbroadSignup/abroadSignupForm';

$route['securitycheck/(.*)'] = "SecurityCheck/$1";