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

/*register service worker*/
$route['loadsw'] = 'ServiceWorker/loadView';

/* url for applycontent pages is manually entered by cms user  */
$route['(.*)-applycontent(.*)'] = "contentPage/ApplyContentPage/applyContentPage/$2";
$route['nationalrecommendations(.*)'] = "recommendation/recommendation/processNationalMailer$1";
$route['recommendations(.*)'] = "recommendation/recommendation/processMailer$1";
$route['showRecommendations(.*)'] = "categoryList/CategoryList/recommendations$1";

$route['writeforus'] = "WriteForUsMobile/home";

//Details Page URL
/* Removing deprecated functionalities notification and scholarship
$route['getListingDetail(.*)/notification(.*)'] = "listing/Listing/getDetailsForListing$1/notification$2";
$route['getListingDetail(.*)/scholarship(.*)'] = "listing/Listing/getDetailsForListing$1/scholarship$2";
*/

$route['college-admissions-online-application-forms'] = "mOnlineForms5/OnlineFormsMobile/showOnlineFormsHomepage/Management";
$route['college-admissions-online-mba-application-forms'] = "mOnlineForms5/OnlineFormsMobile/showOnlineFormsHomepage/Management";
$route['college-admissions-engineering-online-application-forms'] = "mOnlineForms5/OnlineFormsMobile/redirect301/engineering";

$route['mba/resources/application-forms'] = "mOnlineForms5/OnlineFormsMobile/showOnlineFormsHomepage/Management";
$route['engineering/resources/application-forms'] = "mOnlineForms5/OnlineFormsMobile/showOnlineFormsHomepage/Engineering";

$route['indira-group-of-institutions.html'] = "mobile_listing5/InstituteMobile/getInstituteDetailPage/22276/institute";
$route['International-School-of-Business-and-Media.html'] = "instituteSite/isbm";
/*
| -------------------------------------------------------------------
| Beacon URL HACK START
| Bypass beacon image URL
| -------------------------------------------------------------------
|
*/

//Course Detail Page AMP URL
$route['([a-zA-Z0-9\-\ ]*)/course/amp/([a-zA-Z0-9\-\ ]*)-([0-9]+)'] = "mobile_listing5/CourseMobile/ampCourseDetailPage/$3";
$route['([a-zA-Z0-9\-\ ]*)/([a-zA-Z0-9\-\ ]*)/course/amp/([a-zA-Z0-9\-\ ]*)-([0-9]+)'] = "mobile_listing5/CourseMobile/ampCourseDetailPage/$4";


$path = (isset($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : @getenv('PATH_INFO');
$result = strpos($path,'beacon');
if (!$result) {
	$route['(.*)-listingoverviewtab-(.*)'] = "mobile_listing5/Listing_mobile/listingDetailWap/$2/institute";
	$route['(.*)-listingcourse-(.*)'] = "mobile_listing5/Listing_mobile/listingDetailWap/$2/course";
	$route['(.*)-listinganatab-(.*)'] = "listing/ListingPage/listingAnaTab/$2";
	$route['(.*)-campus-representatives-discussions-(.*)'] = "listing/ListingPage/listingCampusRepTab/$2";
	$route['(.*)-listingalumnitab-(.*)'] = "listing/ListingPage/listingAlumniTab/$2";
	$route['(.*)-listingmediatab-(.*)'] = "listing/ListingPage/listingMediaTab/$2";
	$route['(.*)-listingcoursetab-(.*)'] = "mobile_listing5/Listing_mobile/listingCoursesTab/$2";
	$route['([^\/]+)/course/(.*)/(.*)'] = "mobile_listing5/Listing_mobile/listingDetailWap/$3/course";
}

//College Detail Page AMP URL
$route['college/amp/([a-zA-Z0-9\-\ ]*)-([0-9]+)'] = "mobile_listing5/InstituteMobile/ampInstituteDetailPage/$2/institute";
$route['university/amp/([a-zA-Z0-9\-\ ]*)-([0-9]+)'] = "mobile_listing5/InstituteMobile/ampInstituteDetailPage/$2/university";

/*
| -------------------------------------------------------------------
| Beacon URL HACK END
| Bypass beacon image URL
| -------------------------------------------------------------------
|
*/

/*Shiksha Apply Page - This must be kept ABOVE the courselisting identifier*/
$route['(.*)-rmc-(.*)'] = "rateMyChancePage/rateMyChance/rateMyChancePage/$2";
$route['submission-success'] = "rateMyChancePage/rateMyChance/successPage";

/*
 * Study Abroad Listings Pages..
 */
//$route['(.*)-courselisting-(.*)'] = "listing/abroadListings/courseListing/$2";
$route['(.*)-courselisting-(.*)'] = "listingPage/ListingPage/courseListing/$2";
$route['(.*)-deptlisting-(.*)'] = "listing/abroadListings/departmentListing/$2";
//$route['(.*)-univlisting-(.*)'] = "listing/abroadListings/universityListing/$2";
$route['(.*)-univlisting-(.*)'] = "listingPage/ListingPage/universityListing/$2";


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
$route['(.*)-dscns-(.*)']   = "ANA/mobile_messageboard/get_topic_detail/$2";^M
$route['(.*)-ancmt-(.*)']         = "ANA/mobile_messageboard/get_topic_detail/$2";^M
*/
$route['getTopicDetail/(.*)/(.*)'] = "mAnA5/AnAMobile/getQuestionDiscussionDetailPage/$1";
$route['getTopicDetail/(.*)'] = "mAnA5/AnAMobile/getQuestionDiscussionDetailPage/$1";
	//$route['(.*)-qna-(.*)'] = "messageBoard/MsgBoard/topicDetails/$2";
$route['(.*)-qna-(.*)'] = "mAnA5/AnAMobile/getQuestionDiscussionDetailPage/$2/question";
$route['(.*)-dscns-(.*)'] = "mAnA5/AnAMobile/getQuestionDiscussionDetailPage/$2/discussion";
$route['(.*)-ancmt-(.*)'] = "mAnA5/AnAMobile/getQuestionDiscussionDetailPage/$2/announcement";
$route['ANA/mobile_messageboard/render_ask_question_page(.*)'] = "messageBoard/MsgBoard/postQuestionFromCafeForm$1";
$route['ANA/mobile_messageboard/render_messageboard_homepage/(.*)'] = "messageBoard/MsgBoard/discussionHome/$1";

$route['messageBoard/MsgBoard/discussionHome/(.*)/0/(.*)'] = "mAnA5/AnAMobile/getHomepage";
$route['messageBoard/MsgBoard/discussionHome'] = "mAnA5/AnAMobile/getHomepage";
$route['messageBoard/MsgBoard/discussionHome/(.*)/6/(.*)'] = "mAnA5/AnAMobile/getHomepage/discussion";
$route['messageBoard/MsgBoard/discussionHome/(.*)/3/(.*)'] = "mAnA5/AnAMobile/getHomepage/unanswered";
$route['mobileQnAHomePageAjax'] = "mAnA5/AnAMobile/getHomepage/home";
$route['discussions'] = "mAnA5/AnAMobile/getHomepage/discussion";
$route['unanswers'] = "mAnA5/AnAMobile/getHomepage/unanswered";
/* $route['questions'] = "mAnA5/AnAMobile/getHomepage";
$route['questions/(.*)'] = "mAnA5/AnAMobile/getHomepage"; */
$route['questions'] = "messageBoard/AnADesktop/getAllQuestionDiscussionPage/question/";
$route['questions/(.*)'] = "messageBoard/AnADesktop/getAllQuestionDiscussionPage/question/$1";
$route['questions/(.*)/(.*)'] = "messageBoard/AnADesktop/getAllQuestionDiscussionPage/question/$1/$2";
$route['all-discussions'] = "messageBoard/AnADesktop/getAllQuestionDiscussionPage/discussion/";
$route['all-discussions/(.*)'] = "messageBoard/AnADesktop/getAllQuestionDiscussionPage/discussion/$1";
$route['all-discussions/(.*)/(.*)'] = "messageBoard/AnADesktop/getAllQuestionDiscussionPage/discussion/$1/$2";
/* For Tag detail page */
$route['(.*)-tdp-(.*)'] = "mTag5/TagMobile/getTagDetailPage/$2";


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
//$route['getArticleDetail(.*)'] = "blogs/shikshaBlog/blogDetails$1";
$route['news-articles'] = "blogs/shikshaBlog/redirect301/";
$route['news-articles/(:num)'] = "blogs/shikshaBlog/redirect301/$1";

$route['(.*)/articles-st-(.*)'] = 'marticle5/ArticleMobileController/getArticles/stream/$1/$2';
$route['(.*)/articles-pc-(.*)'] = 'marticle5/ArticleMobileController/getArticles/popularCourse/$1/$2';
$route['(.*)/articles-sb-(.*)'] = 'marticle5/ArticleMobileController/getArticles/substream/$1/$2/$3';
$route['articles-all(.*)'] = 'marticle5/ArticleMobileController/getArticles/all/$1';

$route['(.*)-article-(.*)'] = "marticle5/ArticleMobileController/getArticleDetailPage/$2";
$route['getArticleDetail(.*)'] = "marticle5/ArticleMobileController/getArticleDetailPage$1";

$route['(.*)/articles/amp/(.*)-blogId-(.*)'] = "marticle5/ArticleMobileController/getAmpArticleDetailPage/$3";
$route['articles/amp/(.*)-blogId-(.*)'] = "marticle5/ArticleMobileController/getAmpArticleDetailPage/$2";
$route['(.*)/articles/(.*)-blogId-(.*)'] = "marticle5/ArticleMobileController/getArticleDetailPage/$3";
$route['(.*)-blogId-(.*)'] = "marticle5/ArticleMobileController/getArticleDetailPage/$2";

$route['boards/amp(.*)'] = "marticle5/ArticleMobileController/getAmpArticleDetailPage/$0";
$route['courses-after-12th/amp(.*)'] = "marticle5/ArticleMobileController/getAmpArticleDetailPage/$0";
$route['boards(.*)'] = "marticle5/ArticleMobileController/getArticleDetailPage/$0";
$route['courses-after-12th(.*)'] = "marticle5/ArticleMobileController/getArticleDetailPage/$0";


$route['news-(.*)'] = "marticle5/ArticleMobile/getNewsArticles/$1"; //redirect 301
$route['news(.*)'] = "marticle5/ArticleMobile/getNewsArticles$1"; //redirect 301

$route['blogs/shikshaBlog/showArticlesList']="marticle5/ArticleMobile/showArticlesList";
$route['blogs/shikshaBlog/showArticlesList/(.*)/(.*)']="marticle5/ArticleMobile/showArticlesList/$1/$2";

/* CATEGORY PAGES */
//$route['getCategoryPage/colleges(.*)'] = "shiksha/category$1";
//$route['getCategoryPage/colleges(.*)'] = "mobile_category5/CategoryMobile/categoryPage$1";
//$route['([^\/]+)/colleges/(.*)/(.*)']           = "mobile_category5/CategoryMobile/categoryPage/$3";
//$route['([^\/]+)/colleges/(.*)']           = "mobile_category5/CategoryMobile/categoryPage/$2/RNRURL";
$route['(.*)/colleges/(.*)']       = "mobile_nationalCategory5/McategoryList";
$route['(.*)-categorypage-(.*)'] = "categoryList/CategoryList/categoryPage/$2";
$route['(.*)/CategoryList/categoryPage/(.*)'] = "categoryList/CategoryList/categoryPage/$2";


/*$route['(.*)-fees-upto-(.*)-ctpg'] = "categoryList/CategoryList/categoryPage/$1-fees-upto-$2/RNRURL";
$route['(.*)-approved-(.*)-ctpg'] = "categoryList/CategoryList/categoryPage/$1-approved-$2/RNRURL";
$route['(.*)-(recognised|recognized)-(.*)-ctpg'] = "categoryList/CategoryList/categoryPage/$1-recognized-$3/RNRURL";
$route['(.*)-accepts-(.*)-ctpg'] = "categoryList/CategoryList/categoryPage/$1-accepts-$2/RNRURL";
$route['(.*)-ctpg'] = "mobile_category5/CategoryMobile/categoryPage/$1/RNRURL";
*/

// $route['([^\/]+)-fees-upto-([^\/]+)-ctpg'] = "mobile_category5/CategoryMobile/renderCategoryPage/$1-fees-upto-$2/RNRURL";
// $route['([^\/]+)-approved-([^\/]+)-ctpg'] = "mobile_category5/CategoryMobile/renderCategoryPage/$1-approved-$2/RNRURL";
// $route['([^\/]+)-(recognised|recognized)-([^\/]+)-ctpg'] = "mobile_category5/CategoryMobile/renderCategoryPage/$1-recognized-$3/RNRURL";
// $route['([^\/]+)-accepts-([^\/]+)-ctpg'] = "mobile_category5/CategoryMobile/renderCategoryPage/$1-accepts-$2/RNRURL";
// $route['^(([^\/]+)-ctpg)'] = "mobile_category5/CategoryMobile/categoryPage/$1/RNRURL";

$route['([^\/]+)-fees-upto-([^\/]+)-ctpg'] = "categoryList/CategoryList/categoryPage/$1-fees-upto-$2/RNRURL";
$route['([^\/]+)-approved-([^\/]+)-ctpg'] = "categoryList/CategoryList/categoryPage/$1-approved-$2/RNRURL";
$route['([^\/]+)-(recognised|recognized)-([^\/]+)-ctpg'] = "categoryList/CategoryList/categoryPage/$1-recognized-$3/RNRURL";
$route['([^\/]+)-accepts-([^\/]+)-ctpg'] = "categoryList/CategoryList/categoryPage/$1-accepts-$2/RNRURL";
$route['^(([^\/]+)-ctpg)'] = "categoryList/CategoryList/categoryPage/$1/RNRURL";

$route['similar-institutes(.*)'] = "categoryList/CategoryList/similarInstitutes/$1";

/* TESTPREP */
//$route['testprep(.*)'] = "shiksha/testprep_category_page$1";
$route['testprep(.*)'] = "categoryList/CategoryList/oldTestPrepCategoryPage$1";

/* GET USER PROFILE */
$route['getUserProfile(.*)'] = "messageBoard/MsgBoard/userProfile$1";
$route['author(.*)'] = "blogs/shikshaBlog/getUserBlogs$1";
$route['inviteFriends/(.*)'] = "inviteFriends/InviteFriends/$1";

$route['profileSetting'] = "user/MyShiksha/accountSettingAbroad";

/* EXAM ARTICLE PAGE */
$route['education-test-preparation/(.*)'] = "shiksha/showExamArticlePage/$1";

/* Search */
$route['search/searchCI(.*)'] = "search/searchCI$1";
$route['shikshaSearchApi(.*)'] = "search/Search/getSearchResultApi$1";
$route['searchWidget/(.*)'] = "search/Search/searchWidget/$1";
$route['search/top-Education-Searches(.*)'] = "search/Search/topEducationSearches$1";

/*New routes for Search*/
$route['search/AutoSuggestor/(.*)'] = "search/AutoSuggestor/$1";
$route['search/AutoSuggestorV2/(.*)'] = "search/AutoSuggestorV2/$1";
$route['search/SearchEnterprise/(.*)'] = "search/SearchEnterprise/$1";
$route['search/Indexer/(.*)'] = "search/Indexer/$1";
$route['search/Test/(.*)'] = "search/Test/$1";
//$route['search/AbroadSearch/(.*)'] = "search/AbroadSearch/$1";
//$route['search-abroad(.*)'] = "search/AbroadSearch/index/$1";
$route['search/AbroadSearch/(.*)'] = "searchPage/searchPage/$1";
$route['search-abroad(.*)'] = "searchPage/searchPageV2/index/$1";
$route['([a-zA-Z\-\ ]+)/universities-in-([a-zA-Z0-9\-\ ]+)'] = "searchPage/searchPageV2/index/$1";


$route['search/Search/getInstituteSearchResults(.*)'] = "search/Search/getInstituteSearchResults/$1";
$route['search/Search/getContentSearchResults(.*)'] = "search/Search/getContentSearchResults/$1";
$route['search/Search/getCMSSearchResults(.*)'] = "search/Search/getCMSSearchResults/$1";
$route['searchmatrix/SearchMatrix/trackSearchQuery(.*)'] = "searchmatrix/SearchMatrix/trackSearchQuery$1";

if(MOBILE_SEARCH_V2_INTEGRATION_FLAG == 1){
	$route['search/(.*)'] = "msearch5/MsearchV3/$1";
	$route['search/question'] = "msearch5/MsearchV3/showQuestionSRP";
	$route['search/question/answered'] = "msearch5/MsearchV3/showQuestionSRP/answered";
	$route['search/question/unanswered'] = "msearch5/MsearchV3/showQuestionSRP/unanswered";
	$route['search/question/topics'] = "msearch5/MsearchV3/showQuestionSRP/topics";
	$route['search'] = "msearch5/MsearchV3/index/$1";
}else{
	$route['search/(.*)'] = "msearch5/Msearch/$1";
	$route['search'] = "msearch5/Msearch/index/$1";
}


//$route['list-(.*)\.html'] = "msearch5/Msearch/displaySearch/$1";
$route['msearch/Msearch/showTopSearches'] = "msearch5/Msearch/showTopSearches";
$route['user/Userregistration/Forgotpassword/(.*)'] = "muser5/MobileUser/Forgotpassword/$1";

/* Get EB */
$route['getEB/(.*)'] = "mgetEB/Msearch/$1";
//$route['list-(.*)\.html'] = "mgetEB/Msearch/displaySearch/$1";

/*Ranking Pages*/
$route['(.*)-rankingpage-(.*)'] = "mranking5/RankingMain/showRankingPage/$2";

/*Ranking Page New URL*/
$route['(.*)/ranking/(.*)/(.*)'] = "mranking5/RankingMain/showRankingPage/$3";

$route['marketing/Marketing/index/pageID/(.*)'] = "multipleMarketingPage/Marketing/index/pageID/$1";

$route['helpline(.*)'] = "messageBoard/MsgBoard/topicDetails/1761421";
// added for customized online form ---- sem purpose
$route['(.*)-online-application-form-mba-(.*)'] = "Online/OnlineForms/redirectApplicationForm/$2/mba";
$route['(.*)-online-application-form-engineering-(.*)'] = "Online/OnlineForms/redirectApplicationForm/$2/engineering";
$route['(.*)-online-application-form-law-(.*)'] = "Online/OnlineForms/redirectApplicationForm/$2/law";
$route['(.*)-online-application-form-design-(.*)'] = "Online/OnlineForms/redirectApplicationForm/$2/design";


$route['(.*)/mba-application-form-(.*)'] = "Online/OnlineForms/showOnlineForms/$2";
$route['(.*)/engineering-application-form-(.*)'] = "Online/OnlineForms/showOnlineForms/$2";
$route['(.*)/law-application-form-(.*)'] = "Online/OnlineForms/showOnlineForms/$2";
$route['(.*)/design-application-form-(.*)'] = "Online/OnlineForms/showOnlineForms/$2";


/*Exam page discussion */
$route['(.*)-discussions-exampage'] = "examPages/ExamPageMain/getExamPage/$1"."-discussions";
$route['([^\/]+)/exam/(.*)/discussions'] = "examPages/ExamPageMain/getExamPage/$2"."-discussions";

/* Exam Page */
$route['(.*)/exams-pc-(:num)'] = "mobile_examPages5/AllExamPageMobile/getAllExamList/$2";
$route['(.*)/exams-st-(:num)'] = "mobile_examPages5/AllExamPageMobile/getAllExamList/$2";
$route['(.*)/exams-sb-(:num)-(:num)'] = "mobile_examPages5/AllExamPageMobile/getAllExamList/$2/$3";
$route['(.*)/exams/amp/(.*)'] = "mobile_examPages5/ExamPageMain/getAmpExamPage/$2/$3";
$route['(.*)/exams/(.*)'] = "mobile_examPages5/ExamPageMain/getExamPage/$2/$3";
$route['(.*)/amp/(.*)'] = "mobile_examPages5/ExamPageMain/getExamPageByNewURl/amp"; 
if($isAbroad===false){
	$route['(.*)-exam'] = "mobile_examPages5/ExamPageMain/getExamPageByNewURl"; //latest exam url
	$route['(.*)-exam-(homepage|admit-card|answer-key|dates|application-form|counselling|cutoff|pattern|results|question-papers|slot-booking|syllabus|vacancies|call-letter|news|preptips)'] = "mobile_examPages5/ExamPageMain/getExamPageByNewURl";
}

/*old exam url*/
$route['engineering/exam/rank-colleges-predictors'] = "RP/RankPredictorController/commonPredictorPage301";
$route['(.*)-exampage'] = "mobile_examPages5/ExamPageMain/redirectOther301/$1";
$route['([^\/]+)/exam/(.*)/(.*)'] = "mobile_examPages5/ExamPageMain/redirectMBA301/$2/$1/$3";
$route['([^\/]+)/exam/(.*)'] = "mobile_examPages5/ExamPageMain/redirectMBA301/$2/$1";
/* LF-3031 */
$route['([^\/]+)/exam'] = "examPages/ExamPageMain/redirectExamList/$1";


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

$route['careers']="Careers/CareerController/getCareerHomepage";
$route['careers/opportunities']="Careers/CareerController/getExpressInterestPage";
$route['careers/counselling']="Careers/CareerController/recommendCareerOption";
$route['careers/([a-zA-Z0-9\-\ ]*)-([0-9]+)']="Careers/CareerController/getCareerDetailPage/$2";

$route['jgbscampaign(.*)'] = "shiksha/jgbscampaign";

//routes for cafe search
  $route['(.*)search1(.*)'] = "messageBoard/MsgBoard/discussionHome";
  $route['(.*)search2(.*)'] = "messageBoard/MsgBoard/discussionHome";

/* Redirect Old mobile site to New one */
$route['muser/MobileUser/register'] = "muser5/MobileUser/register";
$route['muser/MobileUser/login'] = "muser5/MobileUser/login";
$route['mcommon/MobileSiteStatic/studentHelpLine'] = "mcommon5/MobileSiteStatic/studentHelpLine";
$route['mcommon/MobileSiteStatic/aboutus'] = "mcommon5/MobileSiteStatic/aboutus";
$route['mcommon/MobileSiteStatic/privacy'] = "mcommon5/MobileSiteStatic/privacy";
$route['mcommon/MobileSiteStatic/terms'] = "mcommon5/MobileSiteStatic/terms";
$route['mcommon/MobileSiteStatic/contactUs'] = "mcommon5/MobileSiteStatic/contactUs";
  
/*
 * COURSE PAGES URLs..
 */
$route['(.*)-faq-coursepage/(.*)'] = "coursepages/CoursePage/permaLinkPage/$1/$2";
$route['(.*)-coursepage'] = "coursepages/CoursePage/coursePages/$1";
$route['([^\/]+)/([^\/]+)-home'] = "coursepages/CoursePage/coursePages/$2";
$route['(.*)-home'] = "coursepages/CoursePage/coursePages/$1";
$route['(.*)-chp(.*)'] = "coursepages/CoursePage/courseHomePage/$2";
$route['([^\/]+)/resources/([^\/]+)-(questions/|questions)(.*)'] = "coursepages/CoursePage/coursePages/$2-questions/$4";
$route['([^\/]+)/resources/([^\/]+)-(faq/|faq)(.*)'] = "coursepages/CoursePage/coursePages/$2-faq/$4";
$route['([^\/]+)/resources/([^\/]+)-(discussions/|discussions)(.*)'] = "coursepages/CoursePage/coursePages/$2-discussions/$4";
$route['([^\/]+)/resources/([^\/]+)-(news-articles/|news-articles)(.*)'] = "coursepages/CoursePage/coursePages/$2-news-articles/$4";
$route['(.*)resources/faq-(.*)'] = "coursepages/CoursePage/coursePagesFaq/$2";

$route['(.*)-(news-articles|news-articles-(:num))'] = "blogs/shikshaBlog/newsAndArticlePages/$1/$3";

//campus connect new url
$route['([^\/]+)/resources/campus-connect-program-(:num)'] = "mCampusAmbassador5/CCHomepageController/campusConnectHomepage/$2";
$route['([^\/]+)/([^\/]+)/resources/campus-connect-program-(:num)'] = "mCampusAmbassador5/CCHomepageController/campusConnectHomepage/$3";
//campus connect old url
$route['mba/resources/ask-current-mba-students'] = "CA/CampusConnectController/redirect301";
$route['campus-representatives-from-institutes'] = "CA/CampusConnectController/redirect301";
$route['mba/resources/ask-current-mba-students/(.*)-ccpage-(:num)'] = "mCampusAmbassador5/CCHomepageController/campusConnectIntermediatePage/$1/$2";
$route['(.*)-ccpage-(:num)'] = "mCampusAmbassador5/CCHomepageController/campusConnectIntermediatePage/$1/$2";



$route['muser/MobileUser/forgot_pass'] = "muser5/MobileUser/forgot_pass";
$route['mcommon/MobileSiteHome/renderHomePage/abroad'] = "mcommon5/MobileSiteHome/renderHomePage";
$route['mcommon/MobileSiteHome/showSubCategoriesHome(.*)'] = "mcommon5/MobileSiteHome/renderHomePage";
$route['shikshaHelp/ShikshaHelp/termCondition'] = "mcommon5/MobileSiteStatic/terms";

//$route['mba/resources/mahcet-college-predictor'] = "mcollegepredictor5/CollegePredictorController/loadCollegePredictor/mahcet/mba";
$route['(.*)/resources/(.*)-college-predictor'] = "mcollegepredictor5/CollegePredictorController/loadCollegePredictor/$2/$1/1";
$route['(.*)/resources/(.*)-cut-off-predictor'] = "mcollegepredictor5/CollegePredictorController/loadCollegePredictor/$2/$1/2";
$route['(.*)/resources/(.*)-branch-predictor'] = "mcollegepredictor5/CollegePredictorController/loadBranchTab/$2/$1";
$route['(.*)/resources/(.*)-cut-off-(.*)'] = "mcollegepredictor5/CollegePredictorController/loadInstitutePage/$2/$3/$1";

$route['(.*)-college-predictor'] = "mcollegepredictor5/CollegePredictorController/loadCollegePredictor/$1/'b-tech'/1";
$route['(.*)iimpredictor-ouput(.*)'] = "mIIMPredictor5/MBAPredictor/loadDetailedResultPage/$1";
$route['(.*)iim-predictor(.*)'] = "mIIMPredictor5/MBAPredictor/load/$1";

$route['(.*)-cut-off-predictor'] = "mcollegepredictor5/CollegePredictorController/loadCollegePredictor/$1/'b-tech'/2";
$route['(.*)-branch-predictor'] = "mcollegepredictor5/CollegePredictorController/loadBranchTab/$1";
$route['(.*)-cut-off-(.*)'] = "mcollegepredictor5/CollegePredictorController/loadInstitutePage/$1/$2";

/* Rank Predictor */
$route['b-tech/resources/(.*)-rank-predictor'] = "mRankPredictor/RankPredictorMController/loadRankPage/$1";
$route['(.*)-rank-predictor'] = "mRankPredictor/RankPredictorMController/loadRankPage/$1";
$route['b-tech/resources/rank-colleges-predictors'] = "RP/RankPredictorController/commonPredictorPage";
/*
 * Study Abroad Category Pages..
 */

$route['(.*)-dc1(.*)'] = "categoryPage/CategoryPage/ldbCoursePage/$1/$2";
$route['(.*)-ds1(.*)'] = "categoryPage/CategoryPage/ldbCourseSubCategoryPage/$1/$2";
$route['(.*)-sl1(.*)'] = "categoryPage/CategoryPage/categorySubCategoryCourseLevelPage/$1/$2";
$route['(.*)-cl1(.*)'] = "categoryPage/CategoryPage/categoryCourseLevelPage/$1/$2";
//$route['(.*)-countrypage(.*)'] = "categoryPage/CategoryPage/countryPage/$1/$2";
$route['(.*)-countrypage(.*)'] = "categoryPage/CategoryPage/redirect301/$1/$2"; // SA-1745 - Redirect to the appropriate country page
// exam category page
$route['(.*)-colleges-accepting-(.*)-scores(.*)'] = "categoryPage/CategoryPage/examAcceptedCategoryPage/$1/$2/$3";
$route['(.*)-colleges-for-(.*)-score-(.*)'] = "categoryPage/CategoryPage/examAcceptedCategoryPage/$1/$2/$3";
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
$route['(.*)-abroadexam(.*)'] = "contentPage/contentExamPages/abroadExamPage/$1/$2";
/*
 * Study Abroad Content Org Pages..
 */
$route['(.*)-stagepage'] = "abroadContentOrg/AbroadContentOrgPages/abroadContentOrgPages/$1";

/*
 * Study Abroad All Country Home Page
 */
$route['abroad-countries-countryhome'] = 'countryHome/CountryHome/allCountryHome';

/*
 * Study Abroad Country Home Pages
 */
//$route['(.*)-countryhome'] = 'countryHome/CountryHome/countryHome/$1';
$route['(.*)-countryhome'] = 'countryHomePage/CountryHomePage/combinedAbroadCountryHome/$1';

/* Entrance Exam pages */
$route['jee-main(.*)'] = "marticle5/ArticleMobile/showEngineeringExams/jee-main/$1";
$route['jee-advanced(.*)'] = "marticle5/ArticleMobile/showEngineeringExams/jee-advanced/$1";
$route['bitsat(.*)'] = "marticle5/ArticleMobile/showEngineeringExams/bitsat/$1";
$route['karnataka-cet(.*)'] = "marticle5/ArticleMobile/showEngineeringExams/karnataka-cet/$1";
$route['viteee(.*)'] = "marticle5/ArticleMobile/showEngineeringExams/viteee/$1";
$route['srmeee(.*)'] = "marticle5/ArticleMobile/showEngineeringExams/srmeee/$1";
$route['comedk(.*)'] = "marticle5/ArticleMobile/showEngineeringExams/comedk/$1";
$route['keam(.*)'] = "marticle5/ArticleMobile/showEngineeringExams/keam/$1";
$route['rpet(.*)'] = "marticle5/ArticleMobile/showEngineeringExams/rpet/$1";
$route['wbjee(.*)'] = "marticle5/ArticleMobile/showEngineeringExams/wbjee/$1";
$route['uptu-see(.*)'] = "marticle5/ArticleMobile/showEngineeringExams/uptu-see/$1";
$route['upsee(.*)'] = "marticle5/ArticleMobile/showEngineeringExams/upsee/$1";
$route['eamcet(.*)'] = "marticle5/ArticleMobile/showEngineeringExams/eamcet/$1";
$route['tnea(.*)'] = "marticle5/ArticleMobile/showEngineeringExams/tnea/$1";
$route['kiitee(.*)'] = "marticle5/ArticleMobile/showEngineeringExams/kiitee/$1";

$route['(.*)-countrypage-(.*)'] = "categoryList/AbroadCategoryList/countryPage/$1/$2"; // Check purpose - desktop route in mobile routes?

$route['marketing/Marketing/tracker/pageID/(.*)'] = "multipleMarketingPage/Marketing/tracker/pageID/$1";
$route['marketing/Marketing/form/pageID/(.*)'] = "multipleMarketingPage/Marketing/form/pageID/$1";
$route['clientLandingPage-(.*)'] = "marketing/ClientLandingPage/index/$1";

/* Study abroad content */
//$route['(.*)-articlepage-(.*)'] = "blogs/SAContent/getContentDetails/$2";
//$route['(.*)-guidepage-(.*)'] = "blogs/SAContent/getContentDetails/$2";
$route['(.*)-articlepage-(.*)'] = "contentPage/contentPage/getArticleDetails/$2";
$route['(.*)-guidepage-(.*)'] = "contentPage/contentPage/getGuideDetails/$2";

/* consultant page */
$route['(.*)-overseas-education-consultant-(.*)'] = "consultantProfile/ConsultantPage/consultantPage/$2";

$route['shiksha-authors(.*)'] = "blogs/shikshaBlog/getAuthorProfilePage";

$route['(.*)-exams-colleges-courses'] = "mCampusAmbassador5/MentorController/mentorHomepage/$1"; // for mentor

/*Study abroad shortlisted courses*/
//$route['shortlisted-courses-page'] = "shortlistPage/ShortListPage/getShortlistedCourses";
$route['my-saved-courses'] = "shortlistPage/ShortListPage/getShortlistedCourses";

/* Compare college tool */
$route['compare-colleges(.*)'] = "mCompareInstitute5/compareInstitutes/redirectToNewCompareUrl/$1";
$route['comparison-of(.*)'] = "mCompareInstitute5/compareInstitutes/redirectToNewCompareUrl/$1";
$route['resources/college-comparison(.*)'] = "mCompareInstitute5/compareInstitutes/mainComparePage/$1";

/* SHortlisted colleges page */
$route['shortlisted-colleges'] = "mShortlist5/ShortlistMobile/redirect301";

/* College Alumni review form */
$route['college-review-rating-form'] = "mCollegeReviewForm5/CollegeReviewForm/showReviewForm";
$route['college-review-rating-form/(.*)'] = "mCollegeReviewForm5/CollegeReviewForm/showReviewForm/$1";
$route['shiksha-letsintern-college-review-form'] = "mCollegeReviewForm5/CollegeReviewForm/showReviewForm//letsIntern";
$route['shiksha-letsintern-college-review-form/(.*)'] = "mCollegeReviewForm5/CollegeReviewForm/showReviewForm/$1/letsIntern";
$route['college-review-form'] = "mCollegeReviewForm5/CollegeReviewForm/showReviewForm//campusRep";
$route['college-review-form/(.*)'] = "mCollegeReviewForm5/CollegeReviewForm/showReviewForm/$1/campusRep";

/* Naukri Tool */
$route['mba/resources/mba-alumni-data'] = "mNaukriTool5/NaukriToolController/showNaukriTool";
$route['mba/resources/best-mba-(.*)-colleges-based-on-mba-alumni-data'] = "mNaukriTool5/NaukriToolController/showNaukriTool/$1";
$route['best-colleges-for-jobs-based-on-mba(.*)'] = "mNaukriTool5/NaukriToolController/showNaukriTool";
$route['best-colleges-for-(.*)-jobs-based-on-mba-alumni-data'] = "mNaukriTool5/NaukriToolController/showNaukriTool/$1";

/*terms and policies */
$route['privacy-policy.html'] = "commonModule/User/privacyPolicy";
$route['terms-conditions.html'] = "commonModule/User/termsandCondition";

/* Marketing Page */
$route['6steps-to-decide-your-mba-college'] = "shikshaHelp/ShikshaHelp/mba6StepsMarketingPage";

$route['ofconversion'] = "Online/OnlineFormConversionTracking/index";
$route['ofexception'] = "Online/OnlineFormConversionTracking/ofexception";

/* Myshortlist */
$route['my-shortlist-home'] = "mobile_myShortlist5/MyShortlistMobile/redirect301";
$route['my-shortlist'] = "mobile_myShortlist5/MyShortlistMobile/redirect301";
$route['my-shortlist-nav-(.*)'] = "mobile_myShortlist5/MyShortlistMobile/redirect301";
$route['resources/colleges-shortlisting'] = "mobile_myShortlist5/MyShortlistMobile/myShortlist";

/* College Reviews */
$route['mba-colleges-reviews-cr'] = "mCollegeReviews5/CollegeReviewsController/collegeReviewsHomepage/1/101/20/0";
$route['mba-colleges-reviews-cr-(.*)'] = "mCollegeReviews5/CollegeReviewsController/collegeReviewsHomepage/1/101/20/0";
$route['mba/resources/college-reviews'] = "mCollegeReviews5/CollegeReviewsController/collegeReviewsHomepage/1/101/20/0";
$route['mba/resources/college-reviews/(.*)'] = "mCollegeReviews5/CollegeReviewsController/collegeReviewsHomepage/1/101/20/0";

$route['colleges-reviews-cr'] = "CollegeReviewForm/CollegeReviewController/redirectToNewURL";
$route['colleges-reviews-cr-(.*)'] = "CollegeReviewForm/CollegeReviewController/redirectToNewURL";

/*$route['engineering-colleges-reviews-cr'] = "mCollegeReviews5/CollegeReviewsController/collegeReviewsHomepage/2/10/20/0";
$route['engineering-colleges-reviews-cr-(.*)'] = "mCollegeReviews5/CollegeReviewsController/collegeReviewsHomepage/2/10/20/0";
*/
$route['engineering-colleges-reviews-cr'] = "CollegeReviewForm/CollegeReviewController/redirectToNewBtechURL";
$route['engineering-colleges-reviews-cr-(.*)'] = "CollegeReviewForm/CollegeReviewController/redirectToNewBtechURL";

$route['btech/resources/college-reviews'] = "mCollegeReviews5/CollegeReviewsController/collegeReviewsHomepage/2/10/20/0";
$route['btech/resources/college-reviews/(.*)'] = "mCollegeReviews5/CollegeReviewsController/collegeReviewsHomepage/2/10/20/0";

$route['mba/resources/reviews/(.*)'] = "mCollegeReviews5/CollegeReviewsController/collegeIntermediatePage/$1/1";
$route['mba/resources/reviews/(.*)/(.*)'] = "mCollegeReviews5/CollegeReviewsController/collegeIntermediatePage/$1/$2";

$route['btech/resources/reviews/(.*)'] = "mCollegeReviews5/CollegeReviewsController/collegeIntermediatePage/$1";
$route['btech/resources/reviews/(.*)/(.*)'] = "mCollegeReviews5/CollegeReviewsController/collegeIntermediatePage/$1/$2";

$route['(.*)-crpage'] = "mCollegeReviews5/CollegeReviewsController/collegeIntermediatePage/$1";
$route['(.*)-crpage-(.*)'] = "mCollegeReviews5/CollegeReviewsController/collegeIntermediatePage/$1";


/*Event Calendar*/
$route['(.*)-exams-dates'] = "mEventCalendar5/EventCalendarController/loadEventCalendar/$1";
$route['(.*)/resources/exam-calendar'] = "mEventCalendar5/EventCalendarController/loadEventCalendar/$1";

/*Mobile SMS Response*/
$route['SR-(.*)'] = "mailer/Mailer/GSR/$1";

/*Particular Review Page*/
$route['(.*)/resources/reviews/(.*)-review/(.*)'] = "CollegeReviewForm/CollegeReviewController/showReviewPage/$3";

/*MIS related Entries*/
$route['trackinglogin.html'] = "trackingMIS/Dashboard/login";

$route['trackCtr/(.*)'] = "shiksha/trackBannerCtrHomepage/$1/$2";

$route['login'] = "commonModule/User/inLineLoginForm";

$route['checkAkamaiHeader'] = "shikshaHelp/ShikshaHelp/checkAkamaiHeaders";

$route['mba/cat-exam-percentile-predictor'] = "mIIMPredictor5/IIMPredictor/iimPercentilePredictor";
$route['mba/cat-exam-predicted-percentile-cut-off'] = "mIIMPredictor5/IIMPredictor/iimPercentileResultPage";


$route['mba/resources/iim-call-predictor'] = "mIIMPredictor5/MBAPredictor/load";
$route['mba/resources/iim-call-predictor-result'] = "mIIMPredictor5/MBAPredictor/loadDetailedResultPage";

// for management page
$route['team'] = "shiksha/team";

$route['userprofile/edit'] = "muserProfile5/UserProfile/displayUserProfile";
$route['userprofile/(.*)'] = "muserProfile5/UserProfile/displayUserPublicProfile/$1";
$route['kiPoints'] = "trackingMIS/KiPoints/dashboard";

/* For View All Tags*/
$route['tags'] = "mTag5/TagMobile/getTags";
$route['tags-(.*)'] = "mTag5/TagMobile/getTags/$1";

/*Experts Panel Page*/
$route['experts'] =  "messageBoard/AnADesktop/getExpertsPanel";
$route['experts/(.*)'] =  "messageBoard/AnADesktop/getExpertsPanel/$1";

$route['sitemap'] = 'common/HTMLSitemap/sitemapHome';
$route['sitemap/browse-(.*)-colleges-by-location-(.*)-(.*)'] = 'common/HTMLSitemap/locationSitemap/$1/$2/$3';
$route['sitemap/browse-colleges-by-location'] = 'common/HTMLSitemap/locationSitemap';

if($isAbroad !== false){
	// Abroad Exam Content Pages
	$route['exams/(.*)'] = "contentPage/AbroadExamContentPage/index/$1";
}else{
	$route['exams/amp/(.*)'] = "mobile_examPages5/ExamPageMain/getAmpExamPage/$1/$2";
	$route['exams/(.*)'] = "mobile_examPages5/ExamPageMain/getExamPage/$1/$2";
}
// abroad sign up thank you page
$route['thank-you-for-downloading-(.*)'] = 'studyAbroadCommon/AbroadSignup/thankYouPage/$1';

//$route['exams/(.*)/(.*)'] = "abroadExamContent/AbroadExamContentPage/index/$1/$2";
$route['apply'] = 'applyHomePage/ApplyHomePage/applyHomePage';
$route['apply/counselors/(.*)-(.*)'] = 'applyHomePage/CounselorPage/counselorPage/$1/$2';
$route['apply/counselors/feedback-form'] = 'applyHome/CounselorHomePage/counselorReviewPostingPage';


$route['([a-zA-Z\-\ ]+)/universities/([a-zA-Z0-9\-\ ]+)'] = "listingPage/ListingPage/abroadListingPage/";
$route['([a-zA-Z\-\ ]+)/universities/([a-zA-Z0-9\-\ ]+)/([a-zA-Z0-9\-\ ]+)'] = "listingPage/ListingPage/abroadListingPage/";


$route['([a-zA-Z\-\ ]+)/universities(-{0,1}[0-9]*)'] = "categoryPage/CategoryPage/countryPage/$1/$2"; // SA-1745

$route['([a-zA-Z\-\ ]+)'] = 'countryHomePage/CountryHomePage/combinedAbroadCountryHome/$1'; // SA-1736



/************national institute detail page********************/

$route['university/([a-zA-Z0-9\-\ ]*)-([0-9]+)/cutoff/([a-zA-Z]*)'] = "mobile_listing5/CollegeCutoffControllerMobile/getCutOffDetailPage/$2/$3";
$route['college/([a-zA-Z0-9\-\ ]*)-([0-9]+)/cutoff/([a-zA-Z]*)'] = "mobile_listing5/CollegeCutoffControllerMobile/getCutOffDetailPage/$2/$3";
$route['university/([a-zA-Z0-9\-\ ]*)-([0-9]+)/cutoff'] = "mobile_listing5/CollegeCutoffControllerMobile/getCutOffDetailPage/$2";
$route['college/([a-zA-Z0-9\-\ ]*)-([0-9]+)/cutoff'] = "mobile_listing5/CollegeCutoffControllerMobile/getCutOffDetailPage/$2";

$route['college/([a-zA-Z0-9\-\ ]*)-([0-9]+)/(articles|questions|reviews|admission|scholarships)(-(:num))?'] = "mobile_listing5/AllContentPageMobile/getAllContentPage/$2/$3/$5";
$route['university/([a-zA-Z0-9\-\ ]*)-([0-9]+)/(articles|questions|reviews|admission|scholarships)(-(:num))?'] = "mobile_listing5/AllContentPageMobile/getAllContentPage/$2/$3/$5";
$route['college/([a-zA-Z0-9\-\ ]*)-([0-9]+)'] = "mobile_listing5/InstituteMobile/getInstituteDetailPage/$2/institute";
$route['university/([a-zA-Z0-9\-\ ]*)-([0-9]+)'] = "mobile_listing5/InstituteMobile/getInstituteDetailPage/$2/university";
/*
$route['university/([a-zA-Z0-9\-\ ]*)-([0-9]+)/cutoff/([a-zA-Z]*)'] = "nationalInstitute/CollegeCutoffController/getCutOffDetailPage/$2/$3";
$route['college/([a-zA-Z0-9\-\ ]*)-([0-9]+)/cutoff/([a-zA-Z]*)'] = "nationalInstitute/CollegeCutoffController/getCutOffDetailPage/$2/$3";
$route['university/([a-zA-Z0-9\-\ ]*)-([0-9]+)/cutoff'] = "nationalInstitute/CollegeCutoffController/getCutOffDetailPage/$2";
$route['college/([a-zA-Z0-9\-\ ]*)-([0-9]+)/cutoff'] = "nationalInstitute/CollegeCutoffController/getCutOffDetailPage/$2";*/

$route['([a-zA-Z0-9\-\ ]*)/course/([a-zA-Z0-9\-\ ]*)-([0-9]+)'] = "mobile_listing5/CourseMobile/getCourseDetailPage/$3";
$route['([a-zA-Z0-9\-\ ]*)/([a-zA-Z0-9\-\ ]*)/course/([a-zA-Z0-9\-\ ]*)-([0-9]+)'] = "mobile_listing5/CourseMobile/getCourseDetailPage/$4";
$route['getListingDetail(.*)/course(.*)'] = "mobile_listing5/CourseMobile/getCourseDetailPage$1";
$route['getListingDetail(.*)/institute/(.*)'] = "mobile_listing5/InstituteMobile/getInstituteDetailPage$1/institute";


$route['(college|university)/([a-zA-Z0-9\-\ ]*)-([0-9]+)/(courses)'] = "mobile_nationalCategory5/AllCoursesPageMobile/index/$3";
$route['(college|university)/([a-zA-Z0-9\-\ ]*)-([0-9]+)/(courses)/(.*)'] = "mobile_nationalCategory5/AllCoursesPageMobile/index/$3/$5";
$route['(college|university)/([a-zA-Z0-9\-\ ]*)-([0-9]+)/(courses-(.*))'] = "mobile_nationalCategory5/AllCoursesPageMobile/index/$3";

/*$route['college/([a-zA-Z0-9\-\ ]*)-([0-9]+)/(courses)'] = "mobile_nationalCategory5/AllCoursesPageMobile/index/$2";
$route['university/([a-zA-Z0-9\-\ ]*)-([0-9]+)/(courses)'] = "mobile_nationalCategory5/AllCoursesPageMobile/index/$2";
$route['college/([a-zA-Z0-9\-\ ]*)-([0-9]+)/(courses-(.*))'] = "mobile_nationalCategory5/AllCoursesPageMobile/index/$2";
$route['university/([a-zA-Z0-9\-\ ]*)-([0-9]+)/(courses-(.*))'] = "mobile_nationalCategory5/AllCoursesPageMobile/index/$2";*/

/* SHIPMENT  */
$route['apply/shipment'] = "shipmentModule/shipment/welcomePage";
$route['apply/shipment/shipping-information'] = "shipment/schedulePickupPage";
$route['apply/shipment/shipping-confirmation'] = "shipment/confirmationPage";

/*VITEEE Result Page*/
$route['vitResult'] = "marticle5/ArticleMobileController/thirdPartyResultPage";
/*SRMJEEE Result Page*/
$route['srmResult'] = "marticle5/ArticleMobileController/thirdPartyResultPage";

/*JEE Result Page*/
$route['jeeResult'] = "mcommon5/ResultPage/jeeResultPage";

/* Abroad New Registration / Responses form*/
$route['signup'] = 'studyAbroadCommon/AbroadSignup/abroadSignupForm';
/*AnA*/
$route['(.*)-announcement-(.*)'] = "mAnA5/AnAMobile/getQuestionDiscussionDetailPage/$2/announcement";

$route['scholarships/([a-zA-Z\-]+)-(cp(\-[0-9]+)?)'] = "scholarshipPage/scholarshipCategoryPage/index/$1/$2";
$route['scholarships/([a-zA-Z]+)-((courses)(\-[0-9]+)?)'] = "scholarshipPage/scholarshipCategoryPage/index/$1/$2";

$route['scholarships/(.*)'] = "scholarshipPage/scholarshipDetailPage/index";
$route['scholarships'] = "scholarshipPage/scholarshipHomePage/index";
// $route['scholarships'] = "scholarshipHomepage/scholarshipHomepage/index";
$route['securitycheck/(.*)'] = "SecurityCheck/$1";

$route['users/([a-zA-Z0-9]+)'] = "userProfilePage/userProfilePage/viewUserProfile/$1";
$route['users/([a-zA-Z0-9]+)/edit'] = "userProfilePage/userProfilePage/editUserProfile/$1";
$route['a/(.*)'] = "registration/Registration/redirectReminderUser/$1";
$route['rm/(.*)'] = "registration/Registration/redirectMVReminderUser/$1";// please add new route entry before this always at the end
$route['apply-education-loan'] = "Loan/EducationLoanPage/index";
$route['search-layer'] = 'SASearch/AbroadSearchStarter/index';
$route['shiksha-assistant'] = "common/ChatPlugin/chatPlugin";
