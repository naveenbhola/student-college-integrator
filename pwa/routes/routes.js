import React from 'react';
import {Route, Switch} from 'react-router';
import {
	HomePage,
	CourseDetailPage,
	CategoryPage,
	SearchLayer,
	InstituteDetailPage,
	courseHomePage,
	AllChildPage,
	RankingPage,
	examPage,
	DummyLayer,
	CourseDetailPageAmp,
	ExamSRP,
	CollegePredictor,
	CollegePredictorResultsMobile,
	ListingChildPages,
	RecommendationPage
} from './loadableRoutes';
import gaTracker from './../application/client/gaTracker';
import NotFound from './../application/modules/common/components/NotFound';

export default function(pageReq=null,data = null) {
		return (
				  	<Switch>
						<Route exact={true} path = "/" component={gaTracker(HomePage,pageReq)}/>
						<Route path = {"/([a-zA-Z0-9\-\ ]*)/([a-zA-Z0-9\-\ ]*)/course/amp/([a-zA-Z0-9\-\ ]*)-:listingId"} component={gaTracker(CourseDetailPageAmp,pageReq)}/>	
						<Route path = {"/([a-zA-Z0-9\-\ ]*)/course/amp/([a-zA-Z0-9\-\ ]*)-:listingId"} component={gaTracker(CourseDetailPageAmp,pageReq)}/>
						<Route path={"(.*)/ranking/(.*)/(.*)"}  component={gaTracker(RankingPage,pageReq)} />
						<Route path={"(.*)-exam"}  component={gaTracker(examPage,pageReq)} />
						<Route path={"(.*)exam-(homepage|admit-card|answer-key|dates|application-form|counselling|cutoff|pattern|results|question-papers|slot-booking|syllabus|vacancies|call-letter|news|preptips)"}  component={gaTracker(examPage,pageReq)} />
						<Route path={"(.*)/exams/(.*)"}  component={gaTracker(examPage,pageReq)} />
						<Route path = {"/(college|university)/([a-zA-Z0-9\-\ ]*)-:listingId/(admission|courses|placement|cutoff)(.*)"} component={gaTracker(AllChildPage, pageReq)} />
						<Route path = {"/(college|university)/([a-zA-Z0-9\-\ ]*)-:listingId/(admission|courses|placement|cutoff)"} component={gaTracker(AllChildPage, pageReq)} />
						<Route path = {"/([a-zA-Z0-9\-\ ]*)/course/([a-zA-Z0-9\-\ ]*)-:listingId"} component={gaTracker(CourseDetailPage,pageReq)}/>
						<Route exact={true} path = {"/(college|university)/([a-zA-Z0-9\-\ ]*)-:listingId"} component={gaTracker(InstituteDetailPage,pageReq)}/>
						<Route path = {"/([a-zA-Z0-9\-\ ]*)/([a-zA-Z0-9\-\ ]*)/course/([a-zA-Z0-9\-\ ]*)-:listingId"} component={gaTracker(CourseDetailPage,pageReq)}/>
						<Route path={"getListingDetail(.*)/course(.*)-:listingId"} component={gaTracker(CourseDetailPage,pageReq)} />
						<Route path={"/(.*)/colleges/(.*)"}  component={gaTracker(CategoryPage,pageReq, {}, "categoryPage")} />
						<Route path={"/colleges/(.*)"}  component={gaTracker(CategoryPage,pageReq, {}, "categoryPage")} />
						<Route path={"/searchLayer"}  component={ gaTracker(SearchLayer,pageReq,{}, data)} />
						<Route path={"/search/exam(.*)"}  component={ gaTracker(ExamSRP,pageReq, {}, "examSrpMobile")} />
                        <Route path={"/search(.*)"}  component={ gaTracker(CategoryPage,pageReq, {}, "collegeSrp")} />
						<Route path={"/recommendation(.*)"}  component={ gaTracker(RecommendationPage, pageReq, {}, "recommendationMobile")} />
						<Route path={"/colleges(.*)"}  component={gaTracker(CategoryPage,pageReq,{}, "categoryPage")} />
						<Route path={"(.*)-chp"}  component={gaTracker(courseHomePage,pageReq)} />
						<Route path={"/dummyLayer"}  component={ gaTracker(DummyLayer,pageReq,{}, data)} />
						<Route path={"/college-predictor"} component={gaTracker(CollegePredictor)} />
						<Route path={"/college-predictor-results"} component={gaTracker(CollegePredictorResultsMobile)} />
						<Route exact={true} path={"/listing/pdfgenerator/(admission|cutoff)/:id"} component={gaTracker(ListingChildPages)} />
						<Route path={"*"}  component={NotFound} />
					</Switch>
			)

}
