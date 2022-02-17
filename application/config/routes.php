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
$route['(.*)-applycontent(.*)'] = "applyContent/applyContentPage/$2";
$route['nationalrecommendations(.*)'] = "recommendation/recommendation/processNationalMailer$1";
$route['recommendations(.*)'] = "recommendation/recommendation/processMailer$1";
$route['showRecommendations(.*)'] = "categoryList/CategoryList/recommendations$1";

$route['writeforus'] = "WriteForUs/home";
$route['testresponse(.*)'] = "TestResp$1";
//Details Page URL
/* Removing deprecated functionalities notification and scholarship
$route['getListingDetail(.*)/notification(.*)'] = "listing/Listing/getDetailsForListing$1/notification$2";
$route['getListingDetail(.*)/scholarship(.*)'] = "listing/Listing/getDetailsForListing$1/scholarship$2";
*/

$route['college-admissions-online-application-forms'] = "Online/OnlineForms/redirect301/mba";
$route['college-admissions-online-mba-application-forms'] = "Online/OnlineForms/showOnlineFormsHomepage/Management";
$route['mba/resources/application-forms'] = "Online/OnlineForms/showOnlineFormsHomepage/Management";
$route['college-admissions-engineering-online-application-forms'] = "Online/OnlineForms/redirect301/engineering";
$route['engineering/resources/application-forms'] = "Online/OnlineForms/showOnlineFormsHomepage/Engineering";

$route['indira-group-of-institutions.html'] = "nationalInstitute/InstituteDetailPage/getInstituteDetailPage/22276/institute";
$route['International-School-of-Business-and-Media.html'] = "instituteSite/isbm";
$route['(.*)-listingcourse-161047(.*)'] = "instituteSite/aakashITutor";
$route['(.*)-listingcourse-161048(.*)'] = "instituteSite/aakashITutor";
$route['(.*)-listingcourse-161049(.*)'] = "instituteSite/aakashITutor";
$route['(.*)-listingoverviewtab-35760(.*)'] = "instituteSite/aakashITutor";
$route['(.*)-listinganatab-35760(.*)'] = "instituteSite/aakashITutor";
/*
| -------------------------------------------------------------------
| Beacon URL HACK START
| Bypass beacon image URL
| -------------------------------------------------------------------
|
*/

//Course Detail Page AMP URL

$route['([a-zA-Z0-9\-\ ]*)/course/amp/([a-zA-Z0-9\-\ ]*)-([0-9]+)'] = "nationalCourse/CourseDetailPage/getCourseDetailPage/$3";
$route['([a-zA-Z0-9\-\ ]*)/([a-zA-Z0-9\-\ ]*)/course/amp/([a-zA-Z0-9\-\ ]*)-([0-9]+)'] = "nationalCourse/CourseDetailPage/getCourseDetailPage/$4";

$path = (isset($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : @getenv('PATH_INFO');
$result = strpos($path,'beacon');
if (!$result) {
	$route['(.*)-listingoverviewtab-(.*)'] = "listing/ListingPage/listingOverviewTab/$2/institute";
	$route['(.*)-listingcourse-(.*)'] = "listing/ListingPage/listingOverviewTab/$2/course";
	$route['(.*)-listinganatab-(.*)'] = "listing/ListingPage/listingAnaTab/$2";
	$route['(.*)-campus-representatives-discussions-(.*)'] = "listing/ListingPage/listingCampusRepTab/$2";
	$route['(.*)-listingalumnitab-(.*)'] = "listing/ListingPage/listingAlumniTab/$2";
	$route['(.*)-listingmediatab-(.*)'] = "listing/ListingPage/listingMediaTab/$2";
	$route['(.*)-listingcoursetab-(.*)'] = "listing/ListingPage/listingCoursesTab/$2";
	$route['([^\/]+)/course/(.*)/(.*)'] = "listing/ListingPage/listingOverviewTab/$3/course";
}
/*
| -------------------------------------------------------------------
| Beacon URL HACK END
| Bypass beacon image URL
| -------------------------------------------------------------------
|
*/


/*Shiksha Apply Page - This must be kept ABOVE the courselisting identifier*/
$route['(.*)-rmc-(.*)'] = "rateMyChances/rateMyChancesHomepage/$2";
$route['submission-success'] = "rateMyChances/successPage";

/*
 * Study Abroad Listings Pages..
 */
$route['(.*)-courselisting-(.*)'] = "listing/abroadListings/courseListing/$2";
$route['(.*)-deptlisting-(.*)'] = "listing/abroadListings/departmentListing/$2";
$route['(.*)-univlisting-(.*)'] = "listing/abroadListings/universityListing/$2";

/* ANA */
/*$route['getTopicDetail(.*)'] = "messageBoard/MsgBoard/topicDetails$1";
$route['(.*)-qna-(.*)'] = "messageBoard/MsgBoard/topicDetails/$2";
$route['(.*)-discussion-(.*)'] = "messageBoard/MsgBoard/topicDetails/$2";
$route['(.*)-announcement-(.*)'] = "messageBoard/MsgBoard/topicDetails/$2";
$route['(.*)-dscns-(.*)'] = "messageBoard/MsgBoard/topicDetails/$2";
$route['(.*)-ancmt-(.*)'] = "messageBoard/MsgBoard/topicDetails/$2";*/

$route['getTopicDetail/(.*)/(.*)/(.*)'] = "messageBoard/AnADesktop/getQuestionDiscussionDetailPage/$1/$2/$3";
$route['getTopicDetail/(.*)'] = "messageBoard/AnADesktop/getQuestionDiscussionDetailPage/$1";
$route['(.*)-qna-(.*)/(.*)'] = "messageBoard/AnADesktop/getQuestionDiscussionDetailPage/$2/question/$3";
$route['(.*)-qna-(.*)'] = "messageBoard/AnADesktop/getQuestionDiscussionDetailPage/$2/question";
$route['(.*)-dscns-(.*)/(.*)'] = "messageBoard/AnADesktop/getQuestionDiscussionDetailPage/$2/discussion/$3";
$route['(.*)-dscns-(.*)'] = "messageBoard/AnADesktop/getQuestionDiscussionDetailPage/$2/discussion";
$route['(.*)-ancmt-(.*)'] = "messageBoard/AnADesktop/getQuestionDiscussionDetailPage/$2/announcement";

//$route['discussions'] = "messageBoard/MsgBoard/discussionHome/1/6/1/answer/";
//$route['unanswers'] = "messageBoard/MsgBoard/discussionHome/1/3/1/answer/";
$route['discussions'] = "messageBoard/AnADesktop/getHomePage/discussion";
$route['unanswers'] = "messageBoard/AnADesktop/getHomePage/unanswered";

/* $route['questions'] = "messageBoard/MsgBoard/getQuestionListingPage";
$route['questions/(.*)'] = "messageBoard/MsgBoard/getQuestionListingPage/$1"; */
$route['questions'] = "messageBoard/AnADesktop/getAllQuestionDiscussionPage/question/";
$route['questions/(.*)'] = "messageBoard/AnADesktop/getAllQuestionDiscussionPage/question/$1";
$route['questions/(.*)/(.*)'] = "messageBoard/AnADesktop/getAllQuestionDiscussionPage/question/$1/$2";
$route['all-discussions'] = "messageBoard/AnADesktop/getAllQuestionDiscussionPage/discussion/";
$route['all-discussions/(.*)'] = "messageBoard/AnADesktop/getAllQuestionDiscussionPage/discussion/$1";
$route['all-discussions/(.*)/(.*)'] = "messageBoard/AnADesktop/getAllQuestionDiscussionPage/discussion/$1/$2";

/* For Tag detail page */
$route['(.*)-tdp-(.*)'] = "Tagging/TaggingDesktop/getTagDetailPage/$2";

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

$route['news-articles'] = "blogs/shikshaBlog/redirect301/"; // redirect 301
$route['news-articles/(:num)'] = "blogs/shikshaBlog/redirect301/$1"; // redirect 301
$route['(.*)-article-(.*)'] = "article/ArticleController/getArticleDetailPage/$2";
$route['news/(.*)-article-(.*)'] = "article/ArticleController/getArticleDetailPage/$2";
$route['news-(:num)'] = "blogs/shikshaBlog/redirect301/$1"; // redirect 301
$route['news(.*)'] = "blogs/shikshaBlog/redirect301$1";// redirect 301

$route['(.*)/articles-st-(.*)'] = 'article/ArticleController/getArticles/stream/$1/$2';
$route['(.*)/articles-pc-(.*)'] = 'article/ArticleController/getArticles/popularCourse/$1/$2';
$route['(.*)/articles-sb-(.*)'] = 'article/ArticleController/getArticles/substream/$1/$2/$3';
$route['articles-all'] = 'article/ArticleController/getArticles/all';
$route['articles-all-(:num)'] = 'article/ArticleController/getArticles/all/$1';

$route['getArticleDetail(.*)'] = "article/ArticleController/getArticleDetailPage$1";
$route['(.*)-blogId-(.*)'] = "article/ArticleController/getArticleDetailPage/$2";

$route['boards(.*)'] = "article/ArticleController/getArticleDetailPage/$0";
$route['courses-after-12th(.*)'] = "article/ArticleController/getArticleDetailPage/$0";

$route['news/(.*)-blogId-(.*)'] = "article/ArticleController/getArticleDetailPage/$2";

/* CATEGORY PAGES */
//$route['getCategoryPage/colleges(.*)'] = "shiksha/category$1";
//$route['getCategoryPage/colleges(.*)'] = "categoryList/CategoryList/oldCategoryPage$1";
//$route['([^\/]+)/colleges/(.*)/(.*)']  = "categoryList/CategoryList/categoryPage/$3";
//$route['([^\/]+)/colleges/(.*)']       = "categoryList/CategoryList/categoryPage/$2/RNRURL";
$route['(.*)/colleges/(.*)']       = "nationalCategoryList/NationalCategoryList";
$route['colleges']       = "nationalCategoryList/NationalCategoryList";
$route['colleges/(.*)']       = "nationalCategoryList/NationalCategoryList";
$route['(.*)-categorypage-(.*)'] = "categoryList/CategoryList/categoryPage/$2";

// $route['(.*)-fees-upto-(.*)-ctpg'] = "categoryList/CategoryList/categoryPage/$1-fees-upto-$2/RNRURL";
$route['([^\/]+)-fees-upto-([^\/]+)-ctpg'] = "categoryList/CategoryList/categoryPage/$1-fees-upto-$2/RNRURL";
// $route['(.*)-approved-(.*)-ctpg'] = "categoryList/CategoryList/categoryPage/$1-approved-$2/RNRURL";
$route['([^\/]+)-approved-([^\/]+)-ctpg'] = "categoryList/CategoryList/categoryPage/$1-approved-$2/RNRURL";
// $route['(.*)-(recognised|recognized)-(.*)-ctpg'] = "categoryList/CategoryList/categoryPage/$1-recognized-$3/RNRURL";
$route['([^\/]+)-(recognised|recognized)-([^\/]+)-ctpg'] = "categoryList/CategoryList/categoryPage/$1-recognized-$3/RNRURL";
// $route['(.*)-accepts-(.*)-ctpg'] = "categoryList/CategoryList/categoryPage/$1-accepts-$2/RNRURL";
$route['([^\/]+)-accepts-([^\/]+)-ctpg'] = "categoryList/CategoryList/categoryPage/$1-accepts-$2/RNRURL";
// $route['(.*)-ctpg'] = "categoryList/CategoryList/categoryPage/$1/RNRURL";
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
$route['search/SearchQER/(.*)'] = "search/SearchQER/$1";
$route['search/AbroadSearch/(.*)'] = "search/AbroadSearch/$1";
//$route['search-abroad(.*)'] = "search/AbroadSearch/index/$1";
$route['search-abroad(.*)'] = "SASearch/abroadSearchV2/index/$1";
$route['([a-zA-Z\-\ ]+)/universities-in-([a-zA-Z0-9\-\ ]+)'] = "SASearch/abroadSearchV2/index/$1";
$route['search/AbroadSearchQER/(.*)'] = "search/AbroadSearchQER/$1";

$route['search/Search/getInstituteSearchResults(.*)'] = "search/Search/getInstituteSearchResults/$1";
$route['search/Search/getContentSearchResults(.*)'] = "search/Search/getContentSearchResults/$1";
$route['search/Search/getCMSSearchResults(.*)'] = "search/Search/getCMSSearchResults/$1";

$route['search/SearchV3/(.*)'] = "search/SearchV3/$1";
$route['search/(.*)'] = "search/SearchV3/$1";

$route['search/question'] = "search/SearchV3/showQuestionSRP";
$route['search/question/answered'] = "search/SearchV3/showQuestionSRP/answered";
$route['search/question/unanswered'] = "search/SearchV3/showQuestionSRP/unanswered";
$route['search/question/topics'] = "search/SearchV3/showQuestionSRP/topics";
/*
$route['search/question'] = "msearch5/MsearchV3/showQuestionSRP";
$route['search/question/answered'] = "msearch5/MsearchV3/showQuestionSRP/answered";
$route['search/question/unanswered'] = "msearch5/MsearchV3/showQuestionSRP/unanswered";
$route['search/question/topics'] = "msearch5/MsearchV3/showQuestionSRP/topics";
*/
$route['search'] = "search/SearchV3/index/$1";

//$route['list-(.*)\.html'] = "search/Search/displaySearch/$1";
// $route['list-(.*)\.html'] = "search/SearchV2/handleOldListURL/list-$1.html";


/*Ranking Pages*/
$route['(.*)-rankingpage-(.*)'] = RANKING_PAGE_MODULE."/RankingMain/showRankingPage/$2";

/* Ranking Page for new urls */

$route['([^\/]+)/ranking'] = RANKING_PAGE_MODULE."/RankingMain/rankingsHome";
$route['(.*)/ranking/(.*)/(.*)'] = RANKING_PAGE_MODULE."/RankingMain/showRankingPage/$3";

$route['marketing/Marketing/index/pageID/(.*)'] = "multipleMarketingPage/Marketing/index/pageID/$1";
$route['marketing/Marketing/tracker/pageID/(.*)'] = "multipleMarketingPage/Marketing/tracker/pageID/$1";
$route['marketing/Marketing/form/pageID/(.*)'] = "multipleMarketingPage/Marketing/form/pageID/$1";
$route['clientLandingPage-(.*)'] = "marketing/ClientLandingPage/index/$1";


$route['helpline(.*)'] = "messageBoard/MsgBoard/topicDetails/1761421";
// added for customized online form ---- sem purpose
$route['(.*)-online-application-form-mba-(.*)'] = "Online/OnlineForms/redirectApplicationForm/$2/mba";
$route['(.*)-online-application-form-engineering-(.*)'] = "Online/OnlineForms/redirectApplicationForm/$2/engineering";
$route['(.*)-online-application-form-law-(.*)'] = "Online/OnlineForms/redirectApplicationForm/$2/law";
$route['(.*)-online-application-form-design-(.*)'] = "Online/OnlineForms/redirectApplicationForm/$2/design";
$route['(.*)-online-application-form-me-(.*)'] = "Online/OnlineForms/redirectApplicationForm/$2/engineering";
$route['(.*)-online-application-form-mca-(.*)'] = "Online/OnlineForms/redirectApplicationForm/$2/it";
$route['(.*)-online-application-form-masscomm-(.*)'] = "Online/OnlineForms/redirectApplicationForm/$2/mass-communication";
$route['(.*)-online-application-form-arts-(.*)'] = "Online/OnlineForms/redirectApplicationForm/$2/arts";
$route['(.*)-online-application-form-fashion-(.*)'] = "Online/OnlineForms/redirectApplicationForm/$2/design";
$route['(.*)-online-application-form-aviation-(.*)'] = "Online/OnlineForms/redirectApplicationForm/$2/aviation";

$route['(.*)/mba-application-form-(.*)'] = "Online/OnlineForms/showOnlineForms/$2";
$route['(.*)/engineering-application-form-(.*)'] = "Online/OnlineForms/showOnlineForms/$2";
$route['(.*)/design-application-form-(.*)'] = "Online/OnlineForms/showOnlineForms/$2";
$route['(.*)/hospitality-application-form-(.*)'] = "Online/OnlineForms/showOnlineForms/$2";
$route['(.*)/law-application-form-(.*)'] = "Online/OnlineForms/showOnlineForms/$2";
$route['(.*)/animation-application-form-(.*)'] = "Online/OnlineForms/showOnlineForms/$2";
$route['(.*)/mass-communication-application-form-(.*)'] = "Online/OnlineForms/showOnlineForms/$2";
$route['(.*)/it-application-form-(.*)'] = "Online/OnlineForms/showOnlineForms/$2";
$route['(.*)/humanities-application-form-(.*)'] = "Online/OnlineForms/showOnlineForms/$2";
$route['(.*)/arts-application-form-(.*)'] = "Online/OnlineForms/showOnlineForms/$2";
$route['(.*)/science-application-form-(.*)'] = "Online/OnlineForms/showOnlineForms/$2";
$route['(.*)/architecture-application-form-(.*)'] = "Online/OnlineForms/showOnlineForms/$2";
$route['(.*)/accounting-application-form-(.*)'] = "Online/OnlineForms/showOnlineForms/$2";
$route['(.*)/bfsi-application-form-(.*)'] = "Online/OnlineForms/showOnlineForms/$2";
$route['(.*)/aviation-application-form-(.*)'] = "Online/OnlineForms/showOnlineForms/$2";
$route['(.*)/teaching-application-form-(.*)'] = "Online/OnlineForms/showOnlineForms/$2";
$route['(.*)/nursing-application-form-(.*)'] = "Online/OnlineForms/showOnlineForms/$2";
$route['(.*)/medicine-application-form-(.*)'] = "Online/OnlineForms/showOnlineForms/$2";
$route['(.*)/beautyfitness-application-form-(.*)'] = "Online/OnlineForms/showOnlineForms/$2";

/* Exam Page */

/* new exampage recat rule UGC-4313*/
$route['(.*)/exams-pc-(:num)'] = "examPages/ExamPageMain/getAllExamList/$2";
$route['(.*)/exams-st-(:num)'] = "examPages/ExamPageMain/getAllExamList/$2";
$route['(.*)/exams-sb-(:num)-(:num)'] = "examPages/ExamPageMain/getAllExamList/$2/$3";
$route['(.*)/exams/amp/(.*)'] = "examPages/ExamPageMain/getExamPage/$2/$3";
$route['(.*)/exams/(.*)'] = "examPages/ExamPageMain/getExamPage/$2/$3";
if($isAbroad === false){
	$route['(.*)-exam'] = "examPages/ExamPageMain/getExamPageByNewURl"; //latest exam url
	$route['(.*)-exam-(homepage|admit-card|answer-key|dates|application-form|counselling|cutoff|pattern|results|question-papers|slot-booking|syllabus|vacancies|call-letter|news|preptips)'] =  "examPages/ExamPageMain/getExamPageByNewURl";
}

/*old exam url*/
$route['engineering/exam/rank-colleges-predictors'] = "RP/RankPredictorController/commonPredictorPage301";
$route['(.*)-exampage'] = "examPages/ExamPageMain/redirectOther301/$1";
$route['([^\/]+)/exam/(.*)/(.*)'] = "examPages/ExamPageMain/redirectMBA301/$2/$1/$3";
$route['([^\/]+)/exam/(.*)'] = "examPages/ExamPageMain/redirectMBA301/$2/$1";
/* LF-3031 */
$route['([^\/]+)/exam'] = "examPages/ExamPageMain/redirectExamList/$1";


$route['cat-results(.*)'] = "blogs/shikshaBlog/openLink";
$route['snap-results(.*)'] = "blogs/shikshaBlog/SNAPResultsPage";
$route['ibsat-results(.*)'] = "blogs/shikshaBlog/IBSATResultsPage";
$route['xat-results(.*)'] = "blogs/shikshaBlog/XATResultsPage";
$route['nmat-results(.*)'] = "blogs/shikshaBlog/NMATResultsPage";
$route['mat-results(.*)'] = "blogs/shikshaBlog/MATResultsPage";
$route['cmat-results(.*)'] = "blogs/shikshaBlog/CMATResultsPage";
$route['micat-results(.*)'] = "blogs/shikshaBlog/MICATResultsPage";
$route['cbse-results(.*)'] = "blogs/shikshaBlog/CBSEResultsPage";

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
$route['(.*)resources/faq-(.*)'] = "coursepages/CoursePage/coursePagesFaq/$2";
$route['([^\/]+)/resources/([^\/]+)-(discussions/|discussions)(.*)'] = "coursepages/CoursePage/coursePages/$2-discussions/$4";
$route['([^\/]+)/resources/([^\/]+)-(news-articles/|news-articles)(.*)'] = "coursepages/CoursePage/coursePages/$2-news-articles/$4"; // 301

$route['(.*)-(news-articles|news-articles-(:num))'] = "blogs/shikshaBlog/newsAndArticlePages/$1/$3"; // 301

//$route['campus-representatives-from-institutes'] = "CA/CampusAmbassador/getCADetailsForMarketingPage/";
//$route['campus-representatives-from-institutes-(.*)'] = "CA/CampusAmbassador/getCADetailsForMarketingPage/$1";

//campus connect new url
$route['([^\/]+)/resources/campus-connect-program-(:num)'] = "CA/CampusConnectController/campusConnectHomepage/$2";
$route['([^\/]+)/([^\/]+)/resources/campus-connect-program-(:num)'] = "CA/CampusConnectController/campusConnectHomepage/$3";
//campus connect old url
$route['mba/resources/ask-current-mba-students'] = "CA/CampusConnectController/redirect301";
$route['campus-representatives-from-institutes'] = "CA/CampusConnectController/redirect301";
$route['mba/resources/ask-current-mba-students/(.*)-ccpage-(:num)'] = "CA/CampusConnectController/campusConnectIntermediatepage/$1/$2";
$route['(.*)-ccpage-(:num)'] = "CA/CampusConnectController/campusConnectIntermediatepage/$1/$2";
$route['(.*)-exams-colleges-courses'] = "CA/MentorController/mentorHomepage/$1"; // for mentor

/*
 * Study Abroad Category Pages..
 */
$route['(.*)-dc1(.*)'] = "categoryList/AbroadCategoryList/ldbCoursePage/$1/$2";
$route['(.*)-ds1(.*)'] = "categoryList/AbroadCategoryList/ldbCourseSubCategoryPage/$1/$2";
$route['(.*)-sl1(.*)'] = "categoryList/AbroadCategoryList/categorySubCategoryCourseLevelPage/$1/$2";
$route['(.*)-cl1(.*)'] = "categoryList/AbroadCategoryList/categoryCourseLevelPage/$1/$2";
/* 
 * SA Category page dir based urls
 */
$route['(.*)/(.*)-((dc|cl|ds|sl)(\-[0-9]+)?)']="categoryList/AbroadCategoryList/abroadCategoryPage/$1/$2/$3";

//$route['(.*)-countrypage(.*)'] = "categoryList/AbroadCategoryList/countryPage/$1/$2"; // SA-1745 - Old routes backup
$route['(.*)-countrypage(.*)'] = "categoryList/AbroadCategoryList/redirect301/$1/$2"; // SA-1745 - Redirect to the appropriate country page
// exam category page
$route['(.*)-colleges-accepting-(.*)-scores(.*)'] = "categoryList/AbroadCategoryList/examAcceptedCategoryPage/$1/$2/$3";
$route['(.*)-colleges-for-(.*)-score-(.*)'] = "categoryList/AbroadCategoryList/examAcceptedCategoryPage/$1/$2/$3";

/*
 * Study Abroad Ranking Pages..
 */
$route['(.*)-abroadranking(.*)'] = "abroadRanking/AbroadRanking/rankingPage/$2";
/*
 * Study Abroad Compare Pages..
 */

$route['comparepage-(.*)'] = "studyAbroadCommon/compareCourses/initComparePage/$1";

/*
 * Study Abroad Exam Pages..
 */
$route['(.*)-abroadexam(.*)'] = "abroadExamPages/AbroadExamPages/abroadExamPage/$1/$2";

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
$route['(.*)-countryhome'] = 'countryHome/CountryHome/combinedAbroadCountryHome/$1';

/* College Predictor */
//$route['(.*)/resources/mhcet-college-predictor'] = "CP/CollegePredictorController/loadRankTab/mhcet/$1";
//$route['(.*)/resources/(.*)-college-predictor'] = "CP/CollegePredictorController/loadRankTab/$2/$1";
$route['(.*)/resources/(.*)-college-predictor'] = "CP/CollegePredictorController/loadCollegePredictor/$2/$1/1";
$route['(.*)/resources/(.*)-cut-off-predictor'] = "CP/CollegePredictorController/loadCollegePredictor/$2/$1/2";
$route['(.*)/resources/(.*)-branch-predictor'] = "CP/CollegePredictorController/loadBranchTab/$2/$1";
$route['(.*)/resources/(.*)-cut-off-(.*)'] = "CP/CollegePredictorController/loadInstitutePage/$2/$3/$1";
/*old urls catered for redirection*/
$route['(.*)-college-predictor'] = "CP/CollegePredictorController/loadCollegePredictor/$1/'b-tech'/1";
$route['(.*)-cut-off-predictor'] = "CP/CollegePredictorController/loadCollegePredictor/$2/'b-tech'/2";
$route['(.*)-branch-predictor'] = "CP/CollegePredictorController/loadBranchTab/$1";

/* Rank Predictor */
$route['b-tech/resources/(.*)-rank-predictor'] = "RP/RankPredictorController/loadRankPage/$1";
$route['(.*)-rank-predictor'] = "RP/RankPredictorController/loadRankPage/$1";
$route['b-tech/resources/rank-colleges-predictors'] = "RP/RankPredictorController/commonPredictorPage";

/* Exam Pages */
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

/* Study abroad content */
$route['(.*)-articlepage-(.*)'] = "blogs/SAContent/getContentDetails/$2";
$route['(.*)-articletabloid-(.*)'] = "blogs/SAContent/launchTabloid/$2";
$route['(.*)-guidepage-(.*)'] = "blogs/SAContent/getContentDetails/$2";
//$route['(.*)-guidepage-(.*)'] = "blogs/SAContent/launchTabloid/$2";
/* consultant page */
$route['(.*)-overseas-education-consultant-(.*)'] = "consultantProfile/ConsultantPage/consultantPage/$2";

/* Study abroad content */
$route['shiksha-authors'] = "blogs/shikshaBlog/getAuthorProfilePage";

/*Study abroad shortlisted courses*/
//$route['shortlisted-courses-page'] = "categoryList/AbroadCategoryList/getShortlistPage/$2";
$route['my-saved-courses'] = "categoryList/AbroadCategoryList/getShortlistPage/$2";

/* Compare college tool */
$route['compare-colleges(.*)'] = "comparePage/comparePage/redirectToNewCompareUrl/$1";
$route['comparison-of(.*)'] = "comparePage/comparePage/redirectToNewCompareUrl/$1";

$route['resources/college-comparison(.*)'] = "comparePage/comparePage/mainComparePage/$1";

$route['college-review-rating-form'] = "CollegeReviewForm/CollegeReviewForm/showReviewForm";
$route['college-review-rating-form/(.*)'] = "CollegeReviewForm/CollegeReviewForm/showReviewForm/$1";
$route['shiksha-letsintern-college-review-form'] = "CollegeReviewForm/CollegeReviewForm/showReviewForm//letsIntern";
$route['shiksha-letsintern-college-review-form/(.*)'] = "CollegeReviewForm/CollegeReviewForm/showReviewForm/$1/letsIntern";
$route['college-review-form'] = "CollegeReviewForm/CollegeReviewForm/showReviewForm//campusRep";
$route['college-review-form/(.*)'] = "CollegeReviewForm/CollegeReviewForm/showReviewForm/$1/campusRep";

/* Myshortlist */
$route['my-shortlist-home'] = "myShortlist/MyShortlist/redirect301";
$route['my-shortlist'] = "myShortlist/MyShortlist/redirect301";
$route['my-shortlist-nav-(.*)'] = "myShortlist/MyShortlist/redirect301";
$route['resources/colleges-shortlisting'] = "myShortlist/MyShortlist";

/* Naukri Tool (Career Compass) */
$route['mba/resources/mba-alumni-data'] = "NaukriTool/NaukriToolController/showNaukriTool";
$route['mba/resources/best-mba-(.*)-colleges-based-on-mba-alumni-data'] = "NaukriTool/NaukriToolController/showNaukriTool/$1";
$route['best-colleges-for-jobs-based-on-mba(.*)'] = "NaukriTool/NaukriToolController/showNaukriTool";
$route['best-colleges-for-(.*)-jobs-based-on-mba-alumni-data'] = "NaukriTool/NaukriToolController/showNaukriTool/$1";
$route['naukri-data'] = "listing/Naukri_Data_Integration_Controller/upadateNaukriData/";
/* Marketing Page */
$route['6steps-to-decide-your-mba-college'] = "shikshaHelp/ShikshaHelp/mba6StepsMarketingPage";

$route['ofconversion'] = "Online/OnlineFormConversionTracking/index";
$route['ofexception'] = "Online/OnlineFormConversionTracking/ofexception";

/* College Reviews */
/* For MBA */
$route['mba-colleges-reviews-cr'] = "CollegeReviewForm/CollegeReviewController/collegeReviewsHomepage/1/1/101/20/0";
$route['mba-colleges-reviews-cr-(.*)'] = "CollegeReviewForm/CollegeReviewController/collegeReviewsHomepage/$1/1/101/20/0";
$route['mba/resources/college-reviews'] = "CollegeReviewForm/CollegeReviewController/collegeReviewsHomepage/1/1/101/20/0";
$route['mba/resources/college-reviews/(.*)'] = "CollegeReviewForm/CollegeReviewController/collegeReviewsHomepage/$1/1/101/20/0";

/* For Redirection Old Url to MBA New URL */
$route['colleges-reviews-cr'] = "CollegeReviewForm/CollegeReviewController/redirectToNewURL";
$route['colleges-reviews-cr-(.*)'] = "CollegeReviewForm/CollegeReviewController/redirectToNewURL/$1";

/* For B.tech */
$route['engineering-colleges-reviews-cr'] = "CollegeReviewForm/CollegeReviewController/redirectToNewBtechURL";
$route['engineering-colleges-reviews-cr-(.*)'] = "CollegeReviewForm/CollegeReviewController/redirectToNewBtechURL/$1";

$route['btech/resources/college-reviews'] = "CollegeReviewForm/CollegeReviewController/collegeReviewsHomepage/1/2/10/20/0";
$route['btech/resources/college-reviews/(.*)'] = "CollegeReviewForm/CollegeReviewController/collegeReviewsHomepage/$1/2/10/20/0";


$route['btech/resources/reviews/(.*)'] = "CollegeReviewForm/CollegeReviewController/collegeIntermediatePage/$1";
$route['btech/resources/reviews/(.*)/(.*)'] = "CollegeReviewForm/CollegeReviewController/collegeIntermediatePage/$1/$2";

$route['mba/resources/reviews/(.*)'] = "CollegeReviewForm/CollegeReviewController/collegeIntermediatePage/$1/1";
$route['mba/resources/reviews/(.*)/(.*)'] = "CollegeReviewForm/CollegeReviewController/collegeIntermediatePage/$1/$2";

$route['(.*)-crpage'] = "CollegeReviewForm/CollegeReviewController/collegeIntermediatePage/$1/1";
$route['(.*)-crpage-(.*)'] = "CollegeReviewForm/CollegeReviewController/collegeIntermediatePage/$1/$2";

/*terms and policies */
$route['privacy-policy.html'] = "shikshaHelp/ShikshaHelp/privacyPolicy";
$route['terms-conditions.html'] = "shikshaHelp/ShikshaHelp/termCondition";


/*Event Calendar*/
$route['(.*)-exams-dates'] = "event/EventController/eventCalendar/$1";
$route['(.*)/resources/exam-calendar'] = "event/EventController/eventCalendar/$1";


/*Mobile SMS Response*/
$route['SR-(.*)'] = "mailer/Mailer/GSR/$1";

/*Particular Review Page*/
$route['(.*)/resources/reviews/(.*)-review/(.*)'] = "CollegeReviewForm/CollegeReviewController/showReviewPage/$3";


$route['login'] = "user/MyShiksha/studyAbroadInLineLogin";

$route['([^\/]+)/resources'] = "common/ResourcesHomeController";

/*MIS related Entries*/
$route['trackinglogin.html'] = "trackingMIS/Dashboard/login";


$route['trackCtr/(.*)'] = "shiksha/trackBannerCtrHomepage/$1/$2";


$route['mba/cat-exam-percentile-predictor'] = "IIMPredictor/IIMPredictor/iimPercentilePredictor";
$route['mba/cat-exam-predicted-percentile-cut-off'] = "IIMPredictor/IIMPredictor/iimPercentileResultPage";

$route['checkAkamaiHeader'] = "shikshaHelp/ShikshaHelp/checkAkamaiHeaders";
$route['mba/resources/iim-call-predictor'] = "IIMPredictor/MBAPredictor/load";
$route['mba/resources/iim-call-predictor-result'] = "IIMPredictor/MBAPredictor/loadDetailedResultPage";

//for management static page
$route['team'] = 'shiksha/team';

$route['userprofile/edit'] = "userProfile/UserProfileController/showUserProfile";
$route['userprofile/(.*)'] = "userProfile/UserProfileController/showUserPublicProfile/$1";

if($isAbroad !== false){
	// Abroad Exam Content Pages
	$route['exams/(.*)'] = "abroadExamContent/AbroadExamContent/index/$1";
}else{
	$route['exams/amp/(.*)'] = "examPages/ExamPageMain/getExamPage/$1/$2";
	$route['exams/(.*)'] = "examPages/ExamPageMain/getExamPage/$1/$2";
}

$route['kiPoints'] = 'trackingMIS/KiPoints/dashboard';

/*View All Tags*/

$route['tags'] = "Tagging/TaggingDesktop/getTags";
$route['tags-(.*)'] = "Tagging/TaggingDesktop/getTags/$1";
$route['contributorTags'] = "Tagging/TaggingDesktop/getContributorTags";
$route['contributorTags-(.*)'] = "Tagging/TaggingDesktop/getContributorTags/$1";
/*Experts Panel Page*/
$route['experts'] =  "messageBoard/AnADesktop/getExpertsPanel";
$route['experts/(.*)'] =  "messageBoard/AnADesktop/getExpertsPanel/$1";

$route['LDBLeadTracking/login'] = 'LDB/LDBLeadTracking/showLogin';
$route['LDBLeadTracking/home'] = 'LDB/LDBLeadTracking/displayLeadTrackingHomePage';
$route['sitemap'] = 'common/HTMLSitemap/sitemapHome';
$route['sitemap/browse-(.*)-colleges-by-location-(.*)-(.*)'] = 'common/HTMLSitemap/locationSitemap/$1/$2/$3';
$route['sitemap/browse-colleges-by-location'] = 'common/HTMLSitemap/locationSitemap';
$route['apply'] = 'applyHome/ApplyHome/ApplyHomePage';
$route['apply/counselors/(.*)-(.*)'] = 'applyHome/CounselorHomePage/counselorHomePage/$1/$2';
$route['apply/counselors/feedback-form'] = 'applyHome/CounselorHomePage/counselorReviewPostingPage';

$route['([a-zA-Z\-\ ]+)/universities/([a-zA-Z0-9\-\ ]+)'] = "listing/abroadListings/abroadListingPage/";
$route['([a-zA-Z\-\ ]+)/universities/([a-zA-Z0-9\-\ ]+)/([a-zA-Z0-9\-\ ]+)'] = "listing/abroadListings/abroadListingPage/";

$route['([a-zA-Z\-\ ]+)/universities(-{0,1}[0-9]*)'] = "categoryList/AbroadCategoryList/countryPage/$1/$2"; // SA-1745
$route['cmsPosting/substreamPosting.*'] = 'listingBase/SubstreamAdmin/index';
$route['cmsPosting/specializationPosting.*'] = 'listingBase/SpecializationAdmin/index';
$route['cmsPosting/hierarchyPosting.*'] = 'listingBase/HierarchyAdmin/index';
$route['cmsPosting/basecoursePosting.*'] = 'listingBase/BaseCourseAdmin/index';
$route['cmsPosting/populargroupPosting.*'] = 'listingBase/PopulargroupAdmin/index';
$route['cmsPosting/certificationProviderPosting.*'] = 'listingBase/CertificationProviderAdmin/index';
$route['nationalCourse/CoursePosting/(viewList|create|edit).*'] = 'nationalCourse/CoursePosting/index';
$route['nationalInstitute/InstitutePosting/(viewList|create|cmsPopularCourses|cmsFeaturedCourses|cmsFeaturedArticle).*'] = 'nationalInstitute/InstitutePosting/index';

// abroad sign up thank you page
$route['thank-you-for-downloading-(.*)'] = 'studyAbroadCommon/AbroadSignup/thankYouPage/$1';

$route['([a-zA-Z\-\ ]+)'] = 'countryHome/CountryHome/combinedAbroadCountryHome/$1'; // SA-1736

/************institute detail page********************/
$route['college/amp/([a-zA-Z0-9\-\ ]*)-([0-9]+)'] = "nationalInstitute/InstituteDetailPage/getInstituteDetailPage/$2/institute";
$route['university/amp/([a-zA-Z0-9\-\ ]*)-([0-9]+)'] = "nationalInstitute/InstituteDetailPage/getInstituteDetailPage/$2/university";

$route['college/([a-zA-Z0-9\-\ ]*)-([0-9]+)/(articles|questions|reviews|admission|scholarships)(-(:num))?'] = "nationalInstitute/AllContentPage/getAllContentPage/$2/$3/$5";
$route['university/([a-zA-Z0-9\-\ ]*)-([0-9]+)/(articles|questions|reviews|admission|scholarships)(-(:num))?'] = "nationalInstitute/AllContentPage/getAllContentPage/$2/$3/$5";
$route['college/([a-zA-Z0-9\-\ ]*)-([0-9]+)/(articles|questions|reviews|admission|scholarships)(/pdf)(-(:num))?'] = "mobile_listing5/AllContentPageMobile/getAllContentPage/$2/$3/$5";
$route['university/([a-zA-Z0-9\-\ ]*)-([0-9]+)/(articles|questions|reviews|admission|scholarships)(/pdf)(-(:num))?'] = "mobile_listing5/AllContentPageMobile/getAllContentPage/$2/$3/$5";
$route['university/([a-zA-Z0-9\-\ ]*)-([0-9]+)/cutoff/([a-zA-Z]*)'] = "nationalInstitute/CollegeCutoffController/getCutOffDetailPage/$2/$3";
$route['college/([a-zA-Z0-9\-\ ]*)-([0-9]+)/cutoff/([a-zA-Z]*)'] = "nationalInstitute/CollegeCutoffController/getCutOffDetailPage/$2/$3";
$route['university/([a-zA-Z0-9\-\ ]*)-([0-9]+)/cutoff'] = "nationalInstitute/CollegeCutoffController/getCutOffDetailPage/$2";
$route['college/([a-zA-Z0-9\-\ ]*)-([0-9]+)/cutoff'] = "nationalInstitute/CollegeCutoffController/getCutOffDetailPage/$2";
$route['college/([a-zA-Z0-9\-\ ]*)-([0-9]+)'] = "nationalInstitute/InstituteDetailPage/getInstituteDetailPage/$2/institute";
$route['university/([a-zA-Z0-9\-\ ]*)-([0-9]+)'] = "nationalInstitute/InstituteDetailPage/getInstituteDetailPage/$2/university";

/************course detail page********************/
$route['([a-zA-Z0-9\-\ ]*)/course/([a-zA-Z0-9\-\ ]*)-([0-9]+)'] = "nationalCourse/CourseDetailPage/getCourseDetailPage/$3";
$route['([a-zA-Z0-9\-\ ]*)/([a-zA-Z0-9\-\ ]*)/course/([a-zA-Z0-9\-\ ]*)-([0-9]+)'] = "nationalCourse/CourseDetailPage/getCourseDetailPage/$4";
$route['getListingDetail(.*)/course(.*)'] = "nationalCourse/CourseDetailPage/getCourseDetailPage$1";
$route['getListingDetail(.*)/institute/(.*)'] = "nationalInstitute/InstituteDetailPage/getInstituteDetailPage$1/institute";


/* ALL COURSES PAGE */
$route['(college|university)/([a-zA-Z0-9\-\ ]*)-([0-9]+)/(courses)'] = "nationalCategoryList/AllCoursesPage/index/$3";
$route['(college|university)/([a-zA-Z0-9\-\ ]*)-([0-9]+)/(courses)/(.*)'] = "nationalCategoryList/AllCoursesPage/index/$3/$5";
$route['(college|university)/([a-zA-Z0-9\-\ ]*)-([0-9]+)/(courses-(.*))'] = "nationalCategoryList/AllCoursesPage/index/$3";
// $route['university/([a-zA-Z0-9\-\ ]*)-([0-9]+)/(courses)'] = "nationalCategoryList/AllCoursesPage/index/$2";
// $route['university/([a-zA-Z0-9\-\ ]*)-([0-9]+)/(courses-(.*))'] = "nationalCategoryList/AllCoursesPage/index/$2";

/* SHIPMENT  */
$route['apply/shipment'] = "shipment/welcomePage";
$route['apply/shipment/shipping-information'] = "shipment/schedulePickupPage";
$route['apply/shipment/shipping-confirmation'] = "shipment/confirmationPage";

/*VITEEE Result Page*/
$route['vitResult'] = "article/ArticleController/thirdPartyResultPage";
/*SRMJEEE Result Page*/
$route['srmResult'] = "article/ArticleController/thirdPartyResultPage";

/*JEE Result Page*/
$route['jeeResult'] = "common/ResultPage/jeeResultPage";
$route['signup'] = 'studyAbroadCommon/AbroadSignup/abroadSignupForm';

/*AnA*/
$route['(.*)-announcement-(.*)'] = "messageBoard/AnADesktop/getQuestionDiscussionDetailPage/$2/announcement";
$route['scholarships/([a-zA-Z\-]+)-(cp(\-[0-9]+)?)'] = "scholarshipCategoryPage/scholarshipCategoryPage/index/$1/$2";
$route['scholarships/([a-zA-Z]+)-((courses)(\-[0-9]+)?)'] = "scholarshipCategoryPage/scholarshipCategoryPage/index/$1/$2";
$route['scholarships/(.*)'] = "scholarshipsDetailPage/scholarshipsDetailPage/index";
$route['scholarships'] = "scholarshipHomepage/scholarshipHomepage/index";
$route['securitycheck/(.*)'] = "SecurityCheck/$1";

$route['users/([a-zA-Z0-9]+)'] = "userProfileSA/userProfilePage/viewUserProfile/$1";
$route['users/([a-zA-Z0-9]+)/edit'] = "userProfileSA/userProfilePage/editUserProfile/$1";
$route['a/(.*)'] = "registration/Registration/redirectReminderUser/$1";
$route['rm/(.*)'] = "registration/Registration/redirectMVReminderUser/$1"; // please add new route entry before this always at the end
$route['apply-education-loan'] = "Loan/EducationLoanPage/index";
$route['search-layer'] = 'SASearch/AbroadSearchStarter/index';
$route['shiksha-assistant1'] = "common/ChatPlugin/chatPlugin";
