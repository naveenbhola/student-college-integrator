import React from 'react';
import {Route, Switch} from 'react-router';
import {HomePageDesktop,DesktopCommonComponents, DesktopCTP, CourseHomePageDesktop, RankingPageDesktop, DesktopSearchLayerWrap, AllChildPageDesktop, InstituteDetailPageDesktop, ExamSRP,ExamPageDesktop, CollegePredictorDesktop, CollegePredictorResultsDesktop, ListingChildPages} from './loadableRoutesDesktop';
import gaTracker from './../application/client/gaTracker';
import NotFound from './../application/modules/common/components/NotFound';

export default function(pageReq=null,data = null) {
  return (
    <Switch>
	   <Route exact={true} path = "/" component={gaTracker(HomePageDesktop)}/>
  	    <Route path={"(.*)/ranking/(.*)/(.*)"}  component={gaTracker(RankingPageDesktop,pageReq)} />
        <Route path={"/desktop-common-components"} component={DesktopCommonComponents,pageReq} />
        <Route path={"(.*)-exam"}  component={gaTracker(ExamPageDesktop,pageReq)} />
        <Route path={"(.*)exam-(homepage|admit-card|answer-key|dates|application-form|counselling|cutoff|pattern|results|question-papers|slot-booking|syllabus|vacancies|call-letter|news|preptips)"}  component={gaTracker(ExamPageDesktop,pageReq)} />
        <Route path={"(.*)/exams/(.*)"}  component={gaTracker(ExamPageDesktop,pageReq)} />
        <Route path = {"/(college|university)/([a-zA-Z0-9\-\ ]*)-:listingId/(admission|courses|placement|cutoff)(.*)"} component={gaTracker(AllChildPageDesktop, pageReq, {} , "childPageDesktop")} />
        <Route path = {"/(college|university)/([a-zA-Z0-9\-\ ]*)-:listingId/(admission|courses|placement|cutoff)"} component={gaTracker(AllChildPageDesktop, pageReq, {} , "childPageDesktop")} />
        {        
        //<Route exact={true} path = {"/(college|university)/([a-zA-Z0-9\-\ ]*)-:listingId"} component={gaTracker(InstituteDetailPageDesktop, {},"institutePageDesktop")}/>
        }
        <Route path={"/(.*)/colleges/(.*)"}  component={gaTracker(DesktopCTP,pageReq, {}, "categoryPage")} />
        <Route path={"/colleges/(.*)"}  component={gaTracker(DesktopCTP,pageReq, {}, "categoryPage")} />
        <Route path={"/searchLayer"}  component={ gaTracker(DesktopSearchLayerWrap,pageReq,{}, data)} />
        <Route path={"/search/exam(.*)"}  component={ gaTracker(ExamSRP,pageReq, {}, "examSrpDesktop")} />
        <Route path={"/search(.*)"}  component={ gaTracker(DesktopCTP,pageReq, {}, "collegeSrp")} />
        <Route path={"/colleges(.*)"}  component={gaTracker(DesktopCTP,pageReq,{}, "categoryPage")} />
        <Route path={"(.*)-chp"}  component={gaTracker(CourseHomePageDesktop,pageReq)} />
        <Route path={"/college-predictor"} component={gaTracker(CollegePredictorDesktop,pageReq)} />
        <Route path={"/college-predictor-results"} component={gaTracker(CollegePredictorResultsDesktop,pageReq)} />
        <Route path={"/listing/pdfgenerator/(admission|courses|cutoff)/:id"} component={gaTracker(ListingChildPages)} />
        <Route path={"*"} component={NotFound} />
    </Switch>
  );
}
