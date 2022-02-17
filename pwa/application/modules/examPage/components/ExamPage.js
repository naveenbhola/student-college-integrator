import React from 'react';
import './../assets/ExamPage.css';
import ExamTopCard from './ExamTopCard';
import TOCWidget from './../../common/components/TOCWidget';
import ExamMenu from './ExamMenu';
import ArticlesComponent from './../../listing/institute/components/ArticlesComponent';
import {connect} from 'react-redux';
import {bindActionCreators} from 'redux';
import {fetchExamPageData} from './../actions/ExamPageAction';
import {getPageComponents,getInstituteAcceptingViewAllUrl, prepareBreadCrumbData} from './../utils/examPageUtil';
import {dfpBannerConfig,clearDfpBannerConfig} from './../../reusable/actions/commonAction';
import PreventScrolling from './../../reusable/utils/PreventScrolling';
import NotFound from './../../common/components/NotFound';
import ErrorMsg from './../../common/components/ErrorMsg';
import {isServerSideRenderedHTML} from './../../reusable/utils/commonUtil';
import {viewedResponse} from './../../user/utils/Response.js';
import {createObserver, destroyObserver, loadResources} from './../../reusable/utils/wikiImgLazyLoadUtil';
import {parseQueryParams, isUserLoggedIn, getUrlParameter, goTo, Ucfirst} from '../../../utils/commonHelper';
import {getExamGroupIdForCTA, getExamPageDFPData, getExamPageTrackingData, disableGuideDownload, displayWikiDFP} from './../utils/examPageHelper';
import  { Redirect } from 'react-router-dom';
import ExamNotification from './ExamNotification';
import ExamUpcomingDates from './ExamUpcomingDates';
import ContentLoader from './../utils/contentLoader';
import config from './../../../../config/config';
import ExamConfig from './../config/ExamConfig';
import newCTAConfig from './../config/newCTAConfig';
import AMPExamConfig from './../config/AMPExamConfig';
import AnA from './../../listing/course/components/AnAComponent';
import ExamInstituteAccepting from './ExamInstituteAccepting';
import ElasticSearchTracking from './../../reusable/utils/ElasticSearchTracking';
import TagManager  from './../../reusable/utils/loadGTM';
import ExamCarousel from './../components/ExamCarousel';
import ExamSimilar from './../components/ExamSimilar';
import Loadable from 'react-loadable';
import ClientSeo from './../../common/components/ClientSeo';
import PropTypes from 'prop-types';
import Feedback from "../../common/components/feedback/Feedback";

import SocialSharingBand from './../../common/components/SocialSharingBand';

const ExamBottomSticky = Loadable({
  loader: () => import('./ExamBottomSticky'/* webpackChunkName: 'ExamBottomSticky' */),
  loading() {return null},
});

class ExamPage extends React.Component{
	constructor(props)
	{
		super(props);
		this.state = {
        	isShowLoader : false,
        	showBottomSticky: false
    	};
		this.makeDFPCall = true;
	}

	componentDidMount() {
		let serverCall = true;
		if (!isServerSideRenderedHTML('examPwa')) {
			serverCall = false;
			let nextHash = this.getHashByParams(this.props.location.pathname, this.props.location.search);
	        	this.initialFetchData(nextHash,this.props.location.pathname);
		}
		if (typeof window != 'undefined') {
			createObserver();
		}
		if (serverCall && this.props.examPageData != null && this.props.examPageData.examBasicInfo != null) {
			let examGroupId = getExamGroupIdForCTA(this.props.examPageData.examBasicInfo);
			viewedResponse({
				trackingKeyId: ExamConfig['mobile'][this.props.examPageData.activeSection]['trackingKeys']['viewedResponse'],
				listingId: examGroupId,
				listingType: 'exam',
				actionType: 'exam_viewed_response'
			});
		}
		if(serverCall){
			this.beaconTrackCall();
		}
		window.addEventListener('scroll', this.handleWindowScroll.bind(this));
		if (isUserLoggedIn()) {
			this.setState({
				showBottomSticky: true,
			})
		}

		let searchObj = parseQueryParams(this.props.location.search);
		if(typeof searchObj.scrollTo !== 'undefined' && searchObj.scrollTo.match(/[A-Za-z]+/) != null){
			goTo(searchObj.scrollTo,'mobile');
		}
	}

    componentWillUnmount(){
		window.removeEventListener('scroll', this.handleWindowScroll.bind(this));
		this.props.clearDfpBannerConfig();
		destroyObserver();
	}
	componentDidUpdate(){
		loadResources();
	}
	handleWindowScroll() {
		if (this.makeDFPCall) {
			this.makeDFPCall = false;
			this.dfpData();
			this.getWikiDfp();
		}
		let self = this;
		if(!this.state.showBottomSticky && !isUserLoggedIn()){
			ExamBottomSticky.preload().then(()=>{
					self.setState({showBottomSticky:true})
				}
			);
		}
	}
	getWikiDfp = () => {
		let self = this;
		if (this.props.examPageData && this.props.examPageData.contentInfo && typeof this.props.examPageData.contentInfo.dfpAdUnits != 'undefined' && this.props.examPageData.contentInfo.dfpAdUnits) {
			setTimeout(function () {
				let dfpSlot = self.props.examPageData.contentInfo.dfpAdUnits;
				displayWikiDFP(dfpSlot);
			}, 2000);
		}
	};
	dfpData(){
		this.props.dfpBannerConfig(getExamPageDFPData(this.props.examPageData, 'mobile'));
	}
	beaconTrackCall(){
		let trackParams = getExamPageTrackingData(this.props.examPageData);
		if(typeof trackParams != 'undefined' && trackParams){
			ElasticSearchTracking(trackParams.beaconTrackData, config().BEACON_TRACK_URL);
			TagManager.dataLayer({dataLayer:trackParams.gtmParams, dataLayerName:'dataLayer'});
		}
	}
	initialFetchData(params, url){
    	let that = this;
    	let examName = '';
    	if(url.indexOf('-exam')!=-1){
			let urlString   = url.split('-exam');
			let splitParams    = urlString[0].split('/');
			examName = splitParams[(splitParams.length-1)]
		}else{
			let splitParams   = url.split('/');
			examName      = splitParams[2];
		}
    	that.setState({isShowLoader : true});
    	let fetchPromise = this.props.fetchExamPageData(params,'',false,examName);
    	fetchPromise.then(function(res){
			if(that.props.examPageData != null && that.props.examPageData.examBasicInfo != null) {
				let examGroupId = getExamGroupIdForCTA(that.props.examPageData.examBasicInfo);
				viewedResponse({trackingKeyId: ExamConfig['mobile'][that.props.examPageData.activeSection]['trackingKeys']['viewedResponse'], listingId: examGroupId, listingType: 'exam', actionType: 'exam_viewed_response'});
			}
			that.dfpData();
			that.getWikiDfp();
			that.beaconTrackCall();
    		that.setState({isShowLoader : false});
    	});
    }

	renderLoader() {
    	PreventScrolling.enableScrolling(true);
    	return <ContentLoader/>;
  	}
  	getHashByParams = (url, queryStr = '') => {
		let params = {};
		if(queryStr !== ''){
			params = parseQueryParams(queryStr);
		}
		let paramsObj = {};
		paramsObj['url'] = url;
		if(typeof params.course != 'undefined'){
			paramsObj['groupId'] = params.course;
		}
		return Buffer.from(JSON.stringify(paramsObj)).toString('base64');
	};

  	componentWillReceiveProps(nextProps){
    	let nextPath = nextProps.location.pathname;
    	let prevPath = this.props.location.pathname;
    	if(nextPath!=prevPath || this.props.location.search != nextProps.location.search){
			this.initialFetchData(this.getHashByParams(nextPath, nextProps.location.search),nextPath);
		}		
  	}

	render()
	{
		const {examPageData} = this.props;
		let searchObj = parseQueryParams(this.props.location.search);
		if(this.state.isShowLoader){
      		return this.renderLoader();
    	}
		if(typeof examPageData == 'undefined' || examPageData == null) {
			return <ErrorMsg/>;
		}
		if(typeof searchObj.course !== 'undefined' && searchObj.course.match(/^-{0,1}\d+$/) == null){
			return <Redirect to={{ pathname: this.props.examPageData.url, state: { status: 301 } }}/>
		}
		let currHash = this.getHashByParams(this.props.location.pathname, this.props.location.search);
		if(examPageData.urlHash != currHash) {
			return this.renderLoader();
		}
		if(typeof examPageData == 'undefined' || examPageData == null) {
			return <ErrorMsg/>;
		}
		if((examPageData && typeof examPageData.statusCode != 'undefined' && examPageData.statusCode== 404)){
        	return <NotFound deviceType="mobile" />;
    	}else if((examPageData && typeof examPageData.statusCode != 'undefined' && examPageData.statusCode== 301)){
    		return <Redirect to={{ pathname: examPageData.url, state: { status: 301 } }}/>
    	}else if((examPageData && typeof examPageData.statusCode != 'undefined' && examPageData.statusCode== 302)){
			return <Redirect to={{ pathname: examPageData.url, state: { status: 302 } }}/>
		}
		
    	let pageComponents = '', sectionName = null, sectionUrls = null, sectionNameMapping = null, activeSection = 'homepage', tocData= '', groupId='', questionPaperUrl='', trackingKeyList = '', breadCrumbData='', seoData ='';
    	let ampCTATrackingKeys = new Object();

    	if(typeof examPageData.contentInfo != 'undefined' && examPageData.contentInfo){	

    		let examAMPCTAList = ['getUpdatesTop','getQuestionPaperTop','getUpdatesBottom','getQuestionPaperBottom','samplePaperData','guidePaperData'];
	      	let ampCTAId = getUrlParameter('clickId');
	      	let ampCTASectionName = getUrlParameter('sectionName');
	      	if(ampCTAId !='' && examAMPCTAList.indexOf(ampCTAId) != -1 && ampCTASectionName !=''){
	      		ampCTATrackingKeys = AMPExamConfig[ampCTASectionName]['trackingKeys'];
	      	}

			activeSection  = (examPageData.activeSection) ? examPageData.activeSection : 'homepage';
			trackingKeyList= ExamConfig['mobile'][activeSection]['trackingKeys'];
			pageComponents = getPageComponents({...this.props} ,'mobile', trackingKeyList, ampCTATrackingKeys, newCTAConfig);
			sectionName    = (typeof examPageData.contentInfo.sectionname !='undefined' && examPageData.contentInfo.sectionname) ? examPageData.contentInfo.sectionname : null;
			sectionUrls    = (typeof examPageData.contentInfo.sectionUrls !='undefined') ? examPageData.contentInfo.sectionUrls : null;
			sectionNameMapping = (typeof examPageData.contentInfo.sectionNameMapping !='undefined') ? examPageData.contentInfo.sectionNameMapping : null;
			sectionNameMapping['homepage'] = 'Overview';
			tocData        = (typeof examPageData.contentInfo[activeSection] !='undefined') ? examPageData.contentInfo[activeSection].wiki : null;
			groupId        = (examPageData.groupBasicInfo && examPageData.groupBasicInfo.groupId) ? examPageData.groupBasicInfo.groupId : 0;
			questionPaperUrl = (examPageData.contentInfo.sectionUrls && examPageData.contentInfo.sectionUrls.samplepapers) ? examPageData.contentInfo.sectionUrls.samplepapers : '';
			breadCrumbData = (typeof examPageData !='undefined' && examPageData.breadCrumb) ?  prepareBreadCrumbData(examPageData.breadCrumb, 'mobile') : '';
			seoData        = (examPageData && examPageData.seoData) ? examPageData.seoData : '';
    	}

		let finalTOCData = [];
		if(tocData){
			tocData.forEach((currentValue)=>{
				if(currentValue.toc_text){
					finalTOCData.push(currentValue.toc_text);
				}
			});
		}
		let resultSet = {};
		if(examPageData.articleData != null){
			if(examPageData.articleData.articleDetails != null && examPageData.articleData.articleDetails.length > 0){
				resultSet.articleData = examPageData.articleData;
			}
		}

		let instituteAcceptingCount = this.props.examPageData.ctpCountAndUrlResponse!=null && this.props.examPageData.ctpCountAndUrlResponse.collegeCount!=null?this.props.examPageData.ctpCountAndUrlResponse.collegeCount:0;
	        let instituteAcceptingViewAllUrl = this.props.examPageData.ctpCountAndUrlResponse!=null && this.props.examPageData.ctpCountAndUrlResponse.pageUrl!=null && this.props.examPageData.ctpCountAndUrlResponse.pageUrl.url!=null?this.props.examPageData.ctpCountAndUrlResponse.pageUrl.url:getInstituteAcceptingViewAllUrl(this.props.examPageData.examBasicInfo.id, this.props.examPageData.examBasicInfo.name,this.props.examPageData.groupBasicInfo.hierarchyData);
		return (
			<div className="examPwa" id="examPwa">
				{ClientSeo(seoData)}
				{<ExamTopCard {...this.props} originalSectionName={examPageData.contentInfo.sectionNameMapping[activeSection]} activeSection={activeSection} groupId={groupId} config={config()} trackingKeyList={trackingKeyList} ampCTATrackingKeys={ampCTATrackingKeys} deviceType="mobile" breadCrumbData={breadCrumbData} newCTAConfig={newCTAConfig['mobile']} editorialPdfData ={examPageData.contentInfo[activeSection].guideUrls} />}
				{sectionName && <ExamMenu activeSection={activeSection} sectionName={sectionName} sectionUrl= {sectionUrls} sectionNameMapping={sectionNameMapping}/>}
				<TOCWidget tocData={finalTOCData} gaCategory={'ExamPage'}/>
				
				<section className="shareWidget" key="sh201">
					<SocialSharingBand widgetPosition={"EP_"+Ucfirst(activeSection)+"_Top"}/>
				</section>
				
				{pageComponents}

				{activeSection!='homepage' && this.props.examPageData.epUpdates && this.props.examPageData.epUpdates!=null && <ExamNotification examName={this.props.examPageData.examBasicInfo.name} groupId={this.props.examPageData.groupBasicInfo.groupId} notifications={this.props.examPageData.epUpdates} originalSectionName={this.props.examPageData.contentInfo.sectionNameMapping[activeSection]} activeSection={activeSection} deviceType='mobile'/>}
				{examPageData.activeSection!='homepage' && examPageData.featuredColleges && <ExamCarousel heading="Featured Institute" data={examPageData.featuredColleges} category="ExamPage_Featured_Institute"/>}

				{examPageData.activeSection!='homepage' && <Feedback pageId={groupId} pageType={'ECP'} subPageType={activeSection} deviceType={'mobile'} feedbackWidgetType={'type1'} />}
				{examPageData.activeSection!='homepage' && this.props.examPageData.acceptingWidget!=null && this.props.examPageData.acceptingWidget.totalInstituteCount>0 && <ExamInstituteAccepting instituteAcceptingCount={instituteAcceptingCount} originalSectionName={examPageData.contentInfo.sectionNameMapping[activeSection]} activeSection={activeSection} examName={this.props.examPageData.examBasicInfo.name} viewAllUrl={instituteAcceptingViewAllUrl} deviceType='mobile' acceptingWidget={this.props.examPageData.acceptingWidget} />}

				{Object.keys(resultSet).length!==0 && <ArticlesComponent heading={`${this.props.examPageData.examBasicInfo.name} Updates & Articles`} showCount={false} config={config()} data={resultSet} page='ExamPage' deviceType='mobile' />}

				<AnA anaWidget={this.props.examPageData.anaWidget} activeSection={activeSection} config={config()} page = "examPage" heading={"Have any doubt related to "+ `${this.props.examPageData.examBasicInfo.name}` + "? Ask our experts"} examId={this.props.examPageData.examBasicInfo.id} groupId={examPageData.groupBasicInfo.groupId} deviceType="mobile"/>
				{examPageData.similarExams && <ExamSimilar examName={examPageData.examBasicInfo.name} data={examPageData.similarExams} deviceType='mobile' trackingKeyList={trackingKeyList} groupData={(examPageData.groupBasicInfo) ? examPageData.groupBasicInfo : null} examId={examPageData.examBasicInfo.id} originalSectionName={examPageData.contentInfo.sectionNameMapping[activeSection]} activeSection={activeSection} ampCTATrackingKeys={ampCTATrackingKeys}/>}
				{this.props.examPageData.upcomingEvents && this.props.examPageData.upcomingEvents!=null && this.props.examPageData.upcomingEvents.events.length>0 && <ExamUpcomingDates originalSectionName={examPageData.contentInfo.sectionNameMapping[activeSection]} activeSection={activeSection} upcomingEvents={this.props.examPageData.upcomingEvents}/> }
				{this.state.showBottomSticky && <ExamBottomSticky examName={examPageData.examBasicInfo.name} history={this.props.history} groupId={groupId} questionPaperUrl={questionPaperUrl} trackingKeyList={trackingKeyList} deviceType="mobile" originalSectionName={examPageData.contentInfo.sectionNameMapping[activeSection]} ampCTATrackingKeys={ampCTATrackingKeys} isFutureDateAvailable={examPageData.contentInfo.futureDateAvailable} activeSection={activeSection} newCTAConfig={newCTAConfig['mobile']} editorialPdfData ={examPageData.contentInfo[activeSection].guideUrls} />}
			</div>
		)
	}
}

function mapStateToProps(state){
	return{
		examPageData:state.examPageData
	}
}

function mapDispatchToProps(dispatch){
	return bindActionCreators({fetchExamPageData, dfpBannerConfig, clearDfpBannerConfig},dispatch);
}

ExamPage.propTypes = {
	location : PropTypes.object,
	examPageData : PropTypes.object, 
	clearDfpBannerConfig : PropTypes.func, 
	dfpBannerConfig : PropTypes.func,
	fetchExamPageData : PropTypes.func,
	history : PropTypes.object
}
export default connect(mapStateToProps,mapDispatchToProps)(ExamPage);
