let TEST_API_DOMAIN = 'testapi.shiksha.com';
let DEV_API_DOMAIN = 'http://172.16.3.107:9014';
import config from './config';


module.exports = {

	PROTOCOL : 'https://',
	API_DOMAIN : (process.env.NODE_ENV != 'production') ? TEST_API_DOMAIN : 'apis.shiksha.com',
	API_SERVER_DOMAIN : (process.env.NODE_ENV != 'production') ? 'https://'+TEST_API_DOMAIN : 'http://apis.shiksha.jsb9.net',
	TRACK_API_DOMAIN : (process.env.NODE_ENV != 'production') ? TEST_API_DOMAIN : 'track.shiksha.com',
	BOT_DETECTOR_IP : "10.10.16.72",
	BOT_DETECTOR_PORT : 8987,
	BOT_DETECTION_ENABLED : true,

	NEW_API_DOMAIN : (process.env.NODE_ENV == 'production') ? 'apis.shiksha.com' : ((process.env.NODE_ENV == 'development') ? DEV_API_DOMAIN : DEV_API_DOMAIN),
	NEW_API_SERVER_DOMAIN : (process.env.NODE_ENV == 'production') ? 'http://apis.shiksha.jsb9.net' : ((process.env.NODE_ENV == 'development') ? DEV_API_DOMAIN : DEV_API_DOMAIN),

	get GET_LATESTARTICLESANDCOUNTPARAMS() { return this.PROTOCOL+this.API_DOMAIN+'/apigateway/homepageapi/v1/info/getLatestArticlesAndCountParams'},
	get GET_EXAMYEAR() { return this.PROTOCOL+this.API_DOMAIN+'/apigateway/commonapi/v1/info/getExamYears'},
	get GET_HAMBURGER() { return this.PROTOCOL+this.API_DOMAIN+'/apigateway/commonapi/v1/info/getHamburgerData'},
	get GET_RHL() { return this.PROTOCOL+this.API_DOMAIN+'/apigateway/commonapi/v1/info/getRHLData'},
	get GET_COMPARE_COUNT() { return this.PROTOCOL+this.API_DOMAIN+'/apigateway/compareapi/v1/info/getCompareCountInfo'},
	get GET_HOMEPAGE_FEATURED_COLLEGE() { return this.PROTOCOL+this.API_DOMAIN+'/apigateway/homepageapi/v1/info/getHomePageFeaturedCollegeBanners'},
	get GET_LOCATIONBY_FILTER() { return this.PROTOCOL+this.API_DOMAIN+'/apigateway/commonapi/v1/info/getLocationsByFilters'},
	get GET_CHECKIFCATEGORYPAGE_EXISTS() { return this.PROTOCOL+this.API_DOMAIN+'/apigateway/commonapi/v1/info/checkIfCategoryPageExistsForFilters'},
	get GET_CATEGORYPAGE_TUPLE() { return this.PROTOCOL+this.API_DOMAIN+'/apigateway/categorypageapi/v1/info/getCategoryPageTuple'},
	get GET_CATEGORYPAGE_FILTERS() {return this.PROTOCOL+this.API_DOMAIN+'/apigateway/categorypageapi/v1/info/getCategoryPageFiltersAndLoadMoreCourses'},
	get GET_COURSE() { return this.PROTOCOL+this.API_DOMAIN+'/apigateway/courseapi/v1/info/getCourseData'},
	get GET_INSTITUTE() { return this.PROTOCOL+this.API_DOMAIN+'/apigateway/instituteapi/v2/info/getInstituteData'},
	get GET_CTP_LOADMORE() { return this.PROTOCOL+this.API_DOMAIN+'/apigateway/categorypageapi/v1/info/loadMoreCoursesWithAggregate'},
	get GET_COURSE_NAUKRIDATA() { return this.PROTOCOL+this.API_DOMAIN+'/apigateway/courseapi/v1/info/getCourseNaukriData'},
	get GET_CA_WIDGET() { return this.PROTOCOL+this.API_DOMAIN+'/apigateway/campusambassdorapi/v1/info/getCampusAmbassadorWidget'},
	get GET_CA_WIDGET_FOR_INSTITUTE() { return this.PROTOCOL+this.API_DOMAIN+'/apigateway/campusambassdorapi/v1/info/getCampusAmbassadorWidgetForInstitute'},
	get GET_IMPORTANT_DATES() { return this.PROTOCOL+this.API_DOMAIN+'/apigateway/courseapi/v1/info/getImportantDates'},
	get GET_SHORTLIST_COUNT() { return this.PROTOCOL+this.API_DOMAIN+'/apigateway/shortlistapi/v1/info/getUserShortlistedCoursesCount'},
	get GET_MULTIPLE_STREAMS(){ return this.PROTOCOL+this.API_DOMAIN+'/apigateway/commonapi/v1/info/getMultipleStreams'},
	get GET_MULTIPLE_SUBSTREAMS(){ return this.PROTOCOL+this.API_DOMAIN+'/apigateway/commonapi/v1/info/getMultipleSubstreams'},
	get GET_MULTIPLE_BASECOURSES(){ return this.PROTOCOL+this.API_DOMAIN+'/apigateway/commonapi/v1/info/getMultipleBaseCourses'},
	get GET_ALL_STREAMS(){ return this.PROTOCOL+this.API_DOMAIN+'/apigateway/commonapi/v1/info/getAllStreams'},
	get GET_HIERARCHYTREEHAVING_NONZEROLISTINGS(){ return this.PROTOCOL+this.API_DOMAIN+'/apigateway/commonapi/v1/info/getHierarchyTreeHavingNonZeroListings'},
	get GET_RANKPREDICTOR(){ return this.PROTOCOL+this.API_DOMAIN+'/apigateway/commonapi/v1/info/getRankPredictor'},
	get GET_COLLEGEPREDICTOR(){ return this.PROTOCOL+this.API_DOMAIN+'/apigateway/commonapi/v1/info/getCollegePredictor'},
	get GET_RECOMMENDATION(){ return this.PROTOCOL+this.API_DOMAIN+'/apigateway/commonapi/v1/info/getEBrochureDownloadRecommendationForAggregateRating'},
	get POST_CHECKSHORTLIST(){ return this.PROTOCOL+this.API_DOMAIN+'/apigateway/shortlistapi/v1/info/checkCourseForShortlist'},
	get GET_EXAMLIST(){ return this.PROTOCOL+this.API_DOMAIN+'/apigateway/commonapi/v1/info/getExamList' },
	get GET_BOT_DETECTOR_URL(){ return "http://"+this.BOT_DETECTOR_IP+":"+this.BOT_DETECTOR_PORT+"/cabis/sessiontracker"},
	get GET_EXAM_URL(){ return this.PROTOCOL+this.API_DOMAIN+"/apigateway/commonapi/v1/info/getExamUrlForHamburger"},
	get GET_COURSEHOMEPAGE() { return  this.PROTOCOL+this.API_DOMAIN+'/apigateway/coursehomepageapi/v1/info/getCourseHomePage'},
	get GET_RANKINGPAGE() { return  this.PROTOCOL+this.API_DOMAIN+'/searchfacade/v2/rp/getRankingTupleAndFilter'},
	get GET_RANKINGPAGE_SEARCH_RESULTS() { return  this.PROTOCOL+this.API_DOMAIN+'/searchfacade/v1/rp/getSearchTuple'},
	get GET_RANKINGPAGE_SEARCH_LAYER_RESULTS() { return  this.PROTOCOL+this.API_DOMAIN+'/searchfacade/v1/rp/getSearchResult'},

	get GET_COURSE_FROM_SERVER() { return this.API_SERVER_DOMAIN+'/apigateway/courseapi/v1/info/getCourseData'},
	get GET_INSTITUTE_FROM_SERVER() { return this.API_SERVER_DOMAIN+'/apigateway/instituteapi/v2/info/getInstituteData'},
	get GET_INSTITUTECOLLEGELIST() { return this.PROTOCOL+this.API_DOMAIN+'/apigateway/instituteapi/v2/info/getInstituteViewAllData'},
	get GET_HOME_LATESTARTICLE_SERVER() { return this.API_SERVER_DOMAIN+'/apigateway/homepageapi/v1/info/getLatestArticlesAndCountParams'},
	get GET_CATEGORYPAGE_TUPLE_SERVER() { return this.API_SERVER_DOMAIN+'/apigateway/categorypageapi/v1/info/getCategoryPageTuple'},
	get GET_COURSEHOMEPAGE_FROM_SERVER() { return  this.API_SERVER_DOMAIN+'/apigateway/coursehomepageapi/v1/info/getCourseHomePage'},
	get GET_RANKINGPAGE_FOR_SERVER() { return  this.API_SERVER_DOMAIN+'/searchfacade/v2/rp/getRankingTupleAndFilter'},
	get GET_EXAMPAGE_FROM_SERVER() { return this.API_SERVER_DOMAIN+'/apigateway/examapi/v1/info/getExamPage'},
	get GET_DFP_PARAMS(){ return config().SHIKSHA_HOME+ '/mcommon5/MobileSiteHome/getDFPData'},
	get GET_AUTOSUGGESTOR_TUPLES() { return this.PROTOCOL + this.API_DOMAIN + '/apigateway/autosuggestorApi/v1/info/getAutosuggestorResults'  },
    get GET_COLLEGE_SRP_TUPLE_SERVER() { return this.API_SERVER_DOMAIN+'/apigateway/srpApi/v1/info/getInsttSrpData'},
    get GET_COLLEGESRP_TUPLE() { return this.PROTOCOL+this.API_DOMAIN+'/apigateway/srpApi/v1/info/getInsttSrpData'},
    get GET_COLLEGE_SRP_FILTERS() {return this.PROTOCOL+this.API_DOMAIN+'/apigateway/srpApi/v1/info/getInsttSrpFiltersLoadMore'},
	get GET_SEARCH_ADVANCED_OPTIONS() {return this.PROTOCOL + this.API_DOMAIN + '/apigateway/autosuggestorApi/v1/info/getStreamsCoursesByInstitute'},
    get GET_SEARCH_TRACKING() {return  this.PROTOCOL + this.TRACK_API_DOMAIN + '/trackinggateway/trackingApi/v1/info/trackAutosuggestorSearch'},

    get GET_CHP_GUIDE_TRACKING() { return  this.PROTOCOL+this.TRACK_API_DOMAIN+'/trackinggateway/trackingApi/v1/common/info/downloadActionTracking'},
	get GET_PCW_DATA() { return this.PROTOCOL + this.API_DOMAIN + '/apigateway/categorypageapi/v1/info/getCategoryPagePopularColleges'},
	get GET_PCW_DATA_SERVER() {return this.API_SERVER_DOMAIN + '/apigateway/categorypageapi/v1/info/getCategoryPagePopularColleges'},
	get GET_GNB_HEADER_LINKS() { return this.NEW_API_SERVER_DOMAIN + '/apigateway/commonapi/v1/info/getDesktopGnbHeader'},
	get GET_FOOTER_LINKS() { return this.NEW_API_SERVER_DOMAIN + '/apigateway/commonapi/v1/info/getDesktopFooter'},

	get GET_AMP_IMPORTANT_DATES(){ return this.PROTOCOL+this.API_DOMAIN+'/apigateway/courseapi/v1/info/getAllImportantDates'},
	get GET_AMP_HAMBURGER(){ return config().SHIKSHA_HOME+ "/mcommon5/MobileSiteHamburgerV2/getAMPHamburger?fromwhere=pwa"},
	get GET_ALL_COURSE_PAGEA_DATA_SERVER() { return  this.API_SERVER_DOMAIN + '/apigateway/allcoursepageapi/v1/info/getAllCoursePageCourses' },
	get GET_ALL_COURSE_PAGEA_DATA() { return  this.PROTOCOL + this.API_DOMAIN + '/apigateway/allcoursepageapi/v1/info/getAllCoursePageCourses' },
	get GET_ALL_COURSE_PAGEA_FILTER() { return  this.PROTOCOL + this.API_DOMAIN + '/apigateway/allcoursepageapi/v1/info/getAllCoursePageFilters' },
	get GET_TRENDING_SEARCH_DATA_SERVER() { return  this.API_SERVER_DOMAIN + '/apigateway/autosuggestorApi/v1/info/getTrendingSearches' },
	get GET_TRENDING_SEARCH_DATA() { return  this.PROTOCOL + this.API_DOMAIN + '/apigateway/autosuggestorApi/v1/info/getTrendingSearches' },
	get GET_EXAMPAGE(){return this.PROTOCOL + this.API_DOMAIN + '/apigateway/examapi/v1/info/getExamPage'},
	get GET_EXAMPAGE_NOTIFICATIONS(){return this.PROTOCOL + this.API_DOMAIN+'/apigateway/examapi/v1/info/getUpdates'},
	get GET_ADMISSION_PAGE_DATA_SERVER(){ return this.API_SERVER_DOMAIN + '/apigateway/admissionpageapi/v1/info/getAdmissionPageData'},
	get GET_ADMISSION_PAGE_DATA(){ return this.PROTOCOL + this.API_DOMAIN + '/apigateway/admissionpageapi/v1/info/getAdmissionPageData'},	
	get GET_PLACEMENT_PAGE_DATA_SERVER(){ return this.API_SERVER_DOMAIN + '/apigateway/placementpage/v1/info/getPlacementPageData'},
	get GET_PLACEMENT_PAGE_DATA(){ return this.PROTOCOL + this.API_DOMAIN + '/apigateway/placementpage/v1/info/getPlacementPageData'},
	get GET_CUTOFF_PAGE_DATA_SERVER(){ return this.API_SERVER_DOMAIN + '/apigateway/cutoffpage/v1/info/getCutoffPageData'},
	get GET_CUTOFF_PAGE_DATA(){ return this.PROTOCOL + this.API_DOMAIN + '/apigateway/cutoffpage/v1/info/getCutoffPageData'},
	get GET_ADMISSION_STREAM_COURSE_DATA(){return this.PROTOCOL + this.API_DOMAIN +'/apigateway/admissionpageapi/v1/info/getSelectedStreamCourseData'},
	get GET_EXAM_SRP_TUPLE(){ return this.PROTOCOL + this.API_DOMAIN + '/apigateway/srpApi/v1/info/getExamSrpData'},
	get GET_EXAM_SRP_TUPLE_SERVER(){return this.API_SERVER_DOMAIN + '/apigateway/srpApi/v1/info/getExamSrpData'},
	get GET_INTEGRATED_SRP_DATA(){return this.PROTOCOL + this.API_DOMAIN + '/apigateway/srpApi/v1/info/getIntegratedSrpData'},
	get GET_INTEGRATED_SRP_DATA_SERVER(){return this.API_SERVER_DOMAIN + '/apigateway/srpApi/v1/info/getIntegratedSrpData'},
	get GET_SIMILAR_EXAM(){return this.PROTOCOL + this.API_DOMAIN + '/apigateway/examapi/v1/info/getSimilarExamsWidget'},
	get GET_COLLEGE_PREDICTOR_FROM_SERVER() { return this.API_SERVER_DOMAIN +'/apigateway/predictor/api/v1/info/shortlist/getExamsList'},
	get GET_COLLEGE_PREDICTOR(){return this.PROTOCOL + this.API_DOMAIN +'/apigateway/predictor/api/v1/info/shortlist/getExamsList'},
	get GET_COLLEGE_PREDICTOR_THIRD_FORM_DATA(){return this.PROTOCOL + this.API_DOMAIN +'/apigateway/predictor/api/v1/info/shortlist/getAllCategories'},
	get GET_COLLEGE_PREDICTOR_RESULTS_SERVER(){return this.API_SERVER_DOMAIN +'/apigateway/predictor/api/v1/info/shortlist/getResultPage'},
	get GET_COLLEGE_PREDICTOR_RESULTS(){return this.PROTOCOL + this.API_DOMAIN +'/apigateway/predictor/api/v1/info/shortlist/getResultPage'},
	get GET_COLLEGE_PREDICTOR_VIEW_MORE(){return this.PROTOCOL + this.API_DOMAIN +'/apigateway/predictor/api/v1/info/shortlist/getViewMoreData'},
	get GET_TRACK_COLLEGE_PREDICTOR_INPUTS(){return this.PROTOCOL + this.TRACK_API_DOMAIN +'/trackinggateway/trackingApi/v1/common/info/collegeShortlistTracking'},
	get GET_COLLEGE_PREDICTOR_SAVE_LIST(){return this.PROTOCOL + this.API_DOMAIN +'/apigateway/predictor/api/v1/info/shortlist/saveShortlistUrl'},
	get GET_RECOMMENDATION_PAGE_DATA_SERVER() { return this.API_SERVER_DOMAIN +'/apigateway/recoAPI/v1/info/getInstituteRecommendationPage'},
	get GET_RECOMMENDATION_PAGE_DATA(){return this.PROTOCOL + this.API_DOMAIN +'/apigateway/recoAPI/v1/info/getInstituteRecommendationPage'},
	get GET_SOCIAL_TRACKING_URL(){return this.PROTOCOL + this.TRACK_API_DOMAIN +'/trackinggateway/trackingApi/v1/common/info/socialShareTracking'},
	get GET_SOCIAL_COUNT(){return this.PROTOCOL + this.API_DOMAIN +'/apigateway/commonapi/v1/info/getSocialShareCount'},
	get GET_SAVE_FEEDBACK_URL(){return this.PROTOCOL + this.TRACK_API_DOMAIN  +'/trackinggateway/trackingApi/v1/common/info/feedbackTracking'},
        get GET_DESK_HOMEPAGE_SERVER_DATA(){return this.API_SERVER_DOMAIN +'/apigateway/homepageapi/v1/info/getHomepageData'},
        get GET_DESK_HOMEPAGE_CLIENT_DATA(){return this.PROTOCOL + this.API_DOMAIN +'/apigateway/homepageapi/v1/info/getHomepageData'}
}