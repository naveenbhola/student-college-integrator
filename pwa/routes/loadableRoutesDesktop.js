import Loadable from 'react-loadable';
import ContentLoader from './../application/modules/listing/course/utils/contentLoader';
import CommonContentLoader from './../application/modules/common/components/CommonContentLoader';
import InstituteLoader from './../application/modules/listing/instituteChildPages/components/AllChildPageContentLoader';

export const HomePageDesktop = Loadable({
	loader : () => import("./../application/modules/homepage/components/desktop/HomePageDesktop"/*webpackChunkName: 'HomePageDesktop'*/),
	loading : CommonContentLoader
});

export const CourseHomePageDesktop = Loadable({
	loader : () => import("./../application/modules/listing/courseHomePage/components/CourseHomePageDesktop"/*webpackChunkName: 'CourseHomePageDesktop'*/),
	loading : CommonContentLoader
});

export const DesktopCTP = Loadable({
    loader : () => import("./../application/modules/listing/categoryList/components/DesktopCTP"/*webpackChunkName: 'DesktopCTP'*/),
    loading : CommonContentLoader
});

export const RankingPageDesktop = Loadable({
    loader : () => import("./../application/modules/ranking/components/RankingPageDesktop"/*webpackChunkName: 'RankingPageDesktop'*/),
    loading : CommonContentLoader
});

export const DesktopSearchLayerWrap = Loadable({
	loader : () => import("./../application/modules/search/components/DesktopSearchWrapper" /*webpackChunkName: 'desktopSearchLayer'*/),
	loading : () => null
});

export const  AllChildPageDesktop = Loadable({
		loader: () => import('./../application/modules/listing/instituteChildPages/components/AllChildPageMain'/* webpackChunkName: 'AllChildPageDesktop' */),
		loading : InstituteLoader
	});

export const  InstituteDetailPageDesktop = Loadable({
		loader: () => import('./../application/modules/listing/institute/components/InstituteDetailPageDesktop'/* webpackChunkName: 'institutedetaildesktop' */),
		loading : CommonContentLoader
	});

export const ExamSRP = Loadable({
	loader : () => import("../application/modules/search/components/Exam/ExamSRP" /*webpackChunkName: 'ExamSrp'*/),
	loading : CommonContentLoader
});

export const ExamPageDesktop = Loadable({
	loader : () => import("./../application/modules/examPage/components/ExamPageDesktop"/*webpackChunkName: 'ExamPageDesktop'*/),
    loading : CommonContentLoader
});
export const CollegePredictorDesktop = Loadable({
	loader : () => import("./../application/modules/predictors/CollegePredictor/components/CollegePredictorDesktop"/*webpackChunkName: 'CollegePredictorDesktop'*/),
	loading : CommonContentLoader
});
export const CollegePredictorResultsDesktop = Loadable({
	loader : () => import("./../application/modules/predictors/CollegePredictor/components/CollegePredictorResultsDesktop"/*webpackChunkName: 'CollegePredictorResultsDesktop'*/),
	loading : CommonContentLoader
});

export const  ListingChildPages = Loadable({
		loader: () => import('./../application/modules/listing/pdfGenerator/ListingChildPages'/* webpackChunkName: 'ListingChildPages' */),
		loading : InstituteLoader
});