import Loadable from 'react-loadable';
import ContentLoader from './../application/modules/listing/course/utils/contentLoader';
import InstituteLoader from './../application/modules/listing/institute/utils/contentLoader';
import CatPageContentLoader from './../application/modules/listing/categoryList/utils/contentLoader';
import HomeContentLoader from './../application/modules/homepage/utils/contentLoader';
import RankingContentLoader from './../application/modules/ranking/utils/rankingLoader';
import ExamSrpLoader from "../application/modules/search/components/Exam/ExamSrpLoader";

export const  HomePage = Loadable({
		loader: () => import('./../application/modules/homepage/components/HomePage'/* webpackChunkName: 'homepage' */),
		loading : HomeContentLoader
	});

export const CourseDetailPage = Loadable({
		loader: () => import('./../application/modules/listing/course/components/CourseDetailPage'/* webpackChunkName: 'coursedetail' */),
		loading : ContentLoader
	});

export const  InstituteDetailPage = Loadable({
		loader: () => import('./../application/modules/listing/institute/components/InstituteDetailPage'/* webpackChunkName: 'institutedetail' */),
		loading : InstituteLoader
	});

export const  AllChildPage = Loadable({
		loader: () => import('./../application/modules/listing/instituteChildPages/components/AllChildPageMain'/* webpackChunkName: 'AllChildPage' */),
		loading : InstituteLoader
	});

export const CategoryPage = Loadable({
		loader: () => import('./../application/modules/listing/categoryList/components/CategoryPage'/* webpackChunkName: 'categorypage' */),
		loading : CatPageContentLoader
	});

export const CourseDetailPageAmp = Loadable({
    loader: () => import('./../application/modules/listing/course/components/CourseDetailPageAmp'),
		loading : ContentLoader
});

export const SearchLayer = Loadable({
	loader : () => import("./../application/modules/search/components/SearchLayerWrap"/*webpackChunkName: 'searchLayer'*/),
	loading : ContentLoader
});
export const courseHomePage = Loadable({
	loader : () => import("./../application/modules/listing/courseHomePage/components/CourseHomePage"/*webpackChunkName: 'courseHomePage'*/),
	loading : ContentLoader
});
export const RankingPage = Loadable({
    loader : () => import("../application/modules/ranking/components/RankingPageMobile"/*webpackChunkName: 'rankingPageMobile'*/),
    loading : RankingContentLoader
});    
export const DummyLayer = Loadable({
	loader : () => import("./../application/modules/dummy/components/DummyLayer"/*webpackChunkName: 'dummyLayer'*/),
	loading : ContentLoader
});

export const examPage = Loadable({
	loader : () => import("./../application/modules/examPage/components/ExamPage"/*webpackChunkName: 'examPage'*/),
	loading : ContentLoader
});

export const ExamSRP = Loadable({
	loader : () => import("../application/modules/search/components/Exam/ExamSRP" /*webpackChunkName: 'ExamSrp'*/),
	loading : ExamSrpLoader
});

export const CollegePredictor = Loadable({
	loader : () => import("./../application/modules/predictors/CollegePredictor/components/CollegePredictorMobile"/*webpackChunkName: 'CollegePredictorMobile'*/),
	loading : ContentLoader
});

export const CollegePredictorResultsMobile = Loadable({
	loader : () => import("./../application/modules/predictors/CollegePredictor/components/CollegePredictorResultsMobile"/*webpackChunkName: 'CollegePredictorResultsMobile'*/),
	loading : ContentLoader
});

export const  ListingChildPages = Loadable({
		loader: () => import('./../application/modules/listing/pdfGenerator/ListingChildPages'/* webpackChunkName: 'ListingChildPages' */),
		loading : InstituteLoader
});

export const  RecommendationPage = Loadable({
	loader: () => import('./../application/modules/listing/recommendationPage/components/RecommendationPage'/* webpackChunkName: 'RecommendationPage' */),
	loading : ContentLoader
});