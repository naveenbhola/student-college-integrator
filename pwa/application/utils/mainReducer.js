//import reducers from '../modules/homepage/reducers';
import config from './../../config/config.js';
import { homepageData } from './../modules/homepage/reducers/HomePageData';
import { courseDetailData, courseDataForPreFilledReducer,storeAggregateReviewReducer } from './../modules/listing/course/reducers/CourseDetailData';
import {instituteDetailData,instituteDataForPreFilledReducer} from './../modules/listing/institute/reducers/InstituteDetailData';
import {allChildPageData,childPageDataForPreFilledReducer} from './../modules/listing/instituteChildPages/reducers/AllChildPageReducer';
import { categoryPageData } from './../modules/listing/categoryList/reducers/CategoryPageData';
import { rankingPageData } from './../modules/ranking/reducers/RankingPageData';
import { userDetails } from './../modules/common/reducers/userDetails';
import { importantDatesAmp } from './../modules/common/reducers/importantDatesAmp';
import { alumniData } from './../modules/common/reducers/alumniData';
import { campusRepData } from './../modules/common/reducers/campusRepData';
import {dfpParams} from './../modules/reusable/reducers/commonReducer';
import { courseHomePageData, contentLoaderData } from './../modules/listing/courseHomePage/reducers/CourseHomePageData';
import { footerData } from './../modules/common/reducers/footerLinks';
import { gnbData } from './../modules/common/reducers/gnbLinks';
import {TrendingSearchData} from "../modules/search/reducers/TrendingSearchData";
import {examData} from "../modules/search/reducers/ExamData";
import {examPageData} from './../modules/examPage/reducers/ExamPageReducer';
import {collegePredictorData, collegePredictorResults, collegePredictorSaveList, collegePredictorFilterData} from './../modules/predictors/CollegePredictor/reducers/CollegePredictorReducer';
import {recommendationData} from "../modules/listing/recommendationPage/reducers/RecommendationData";

module.exports  = {
  "config" : config,
  "hpdata" : homepageData,
  "courseData" : courseDetailData,
  "instituteData" : instituteDetailData,
  "categoryData" : categoryPageData,
  "userDetails" : userDetails,
  "catpageCourse" : courseDataForPreFilledReducer,
  "catpageInstitute" : instituteDataForPreFilledReducer,
  "dfpParams" : dfpParams,
  "courseHomePageData": courseHomePageData,
  "courseHomePageLoaderData": contentLoaderData,
  "rankingPageData" : rankingPageData,
  "footerLinks" : footerData,
  "gnbLinks" : gnbData,
  "childPageData" : allChildPageData,
  "contentLoaderData": childPageDataForPreFilledReducer ,
  "trendingData" : TrendingSearchData,
  "examPageData":examPageData,
  "importantDatesAmp" : importantDatesAmp,
  "alumniData"  : alumniData,
  "campusRepData" : campusRepData,
  "examData" : examData,
  "collegePredictorData" : collegePredictorData,
  "collegePredictorResults" : collegePredictorResults,
  "saveList" : collegePredictorSaveList,
  "collegePredictorFilterData" : collegePredictorFilterData,
  "recommendationData" : recommendationData
}

