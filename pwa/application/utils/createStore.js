import {createStore, applyMiddleware} from 'redux';
import thunk from 'redux-thunk';
//import reducers from '../modules/homepage/reducers';
import config from './../../config/config.js';

import { combineReducers } from 'redux';
import { homepageData } from './../modules/homepage/reducers/HomePageData';
import {courseDetailData} from './../modules/listing/course/reducers/CourseDetailData';
import {instituteDetailData} from './../modules/listing/institute/reducers/InstituteDetailData';
import { categoryPageData } from './../modules/listing/categoryList/reducers/CategoryPageData';
import { importantDatesAmp } from './../modules/common/reducers/importantDatesAmp';
import { alumniData } from './../modules/common/reducers/alumniData';
import { campusRepData } from './../modules/common/reducers/campusRepData';
import {dfpParams} from './../modules/reusable/reducers/commonReducer';
import { rankingPageData } from './../modules/ranking/reducers/RankingPageData';
import { courseHomePageData, contentLoaderData } from './../modules/listing/courseHomePage/reducers/CourseHomePageData';
import { footerData } from './../modules/common/reducers/footerLinks';
import { gnbData } from './../modules/common/reducers/gnbLinks';
import {allChildPageData} from './../modules/listing/instituteChildPages/reducers/AllChildPageReducer';
import {TrendingSearchData} from "../modules/search/reducers/TrendingSearchData";
import {examData} from "../modules/search/reducers/ExamData";
import {examPageData} from './../modules/examPage/reducers/ExamPageReducer';
import {collegePredictorData, collegePredictorResults} from './../modules/predictors/CollegePredictor/reducers/CollegePredictorReducer';
import {recommendationData} from "./../modules/listing/recommendationPage/reducers/RecommendationData"

const rootReducer = combineReducers({
        config : config,
        hpdata : homepageData,
        courseData : courseDetailData,
        instituteData : instituteDetailData,
        rankingPageData : rankingPageData,
        categoryData: categoryPageData,
        importantDatesAmp : importantDatesAmp,
        alumniData : alumniData,
        campusRepData :campusRepData,
        dfpParams: dfpParams,
        trendingData : TrendingSearchData,
        courseHomePageData: courseHomePageData,
        courseHomePageLoaderData: contentLoaderData,
        footerLinks: footerData,
        gnbLinks: gnbData,
        childPageData: allChildPageData,
        examData: examData,
	    examPageData: examPageData,
        collegePredictorData : collegePredictorData,
        collegePredictorResults : collegePredictorResults,
        recommendationData : recommendationData
});


export default () => {
	const store = createStore(rootReducer, {} , applyMiddleware(thunk));

	return store;
}
