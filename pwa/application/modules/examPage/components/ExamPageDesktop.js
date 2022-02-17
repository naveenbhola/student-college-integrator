import React from 'react';
import PropTypes from 'prop-types';
import './../assets/ExamPage.css';
import './../assets/ExamPageDesktop.css';
import ExamTopCard from './ExamTopCard';
import TOCWidget from './../../common/components/TOCWidget';
import ExamMenu from './ExamMenu';
import {connect} from 'react-redux';
import {bindActionCreators} from 'redux';
import {fetchExamPageData} from './../actions/ExamPageAction';
import {getPageComponents, getInstituteAcceptingViewAllUrl, prepareBreadCrumbData} from './../utils/examPageUtil';
import {createObserver, destroyObserver, loadResources} from './../../reusable/utils/wikiImgLazyLoadUtil';
import PreventScrolling from './../../reusable/utils/PreventScrolling';
import NotFound from './../../common/components/NotFound';
import {isServerSideRenderedHTML} from './../../reusable/utils/commonUtil';
import {parseQueryParams, resetGNB, isUserLoggedIn, goTo, Ucfirst} from '../../../utils/commonHelper';
import  { Redirect } from 'react-router-dom';
import ExamNotification from './ExamNotification';
import ExamUpcomingDates from './ExamUpcomingDates';
import ContentLoaderDesktop from './../utils/contentLoaderDesktop';
import {DFPBannerTempalte} from './../../reusable/components/DFPBannerAd';
import ExamConfig from './../config/ExamConfig';
import newCTAConfig from './../config/newCTAConfig';
import BreadCrumb from './../../common/components/BreadCrumb';
import AnA from './../../listing/course/components/AnAComponent';
import config from './../../../../config/config';
import {getExamGroupIdForCTA, getExamPageDFPData, getExamPageTrackingData, updateGuideTrackCookie, isGuideDownloaded, disableGuideDownload, disableDownloadBrochure, displayWikiDFP} from "../utils/examPageHelper";
import {dfpBannerConfig,clearDfpBannerConfig} from './../../reusable/actions/commonAction';
import ExamInstituteAccepting from './ExamInstituteAccepting';
import ElasticSearchTracking from './../../reusable/utils/ElasticSearchTracking';
import TagManager  from './../../reusable/utils/loadGTM';
import ArticlesComponent from './../../listing/institute/components/ArticlesComponent';
import ExamCarousel from './../components/ExamCarousel';
import ErrorMsg from "./../../common/components/ErrorMsg";
import ExamSimilar from './ExamSimilar';
import ExamResponseDesktop from './../../common/components/desktop/ResponseDesktop';
import {viewedResponse} from './../../user/utils/Response.js';
import Loadable from "react-loadable";
import ClientSeo from './../../common/components/ClientSeo';
import {setVariablesForScrollableMMP} from './../../user/Response/utils/CommonMMP.js';
import SocialSharingBand from './../../common/components/SocialSharingBand';
import Feedback from "../../common/components/feedback/Feedback";

const PopupLayer = Loadable({
	loader: () => import('./../../common/components/popupLayer'/* webpackChunkName: 'PopupLayer' */),
	loading() {return null},
});

let currScroll = 0, scrollTop = 0, isDfpFixed = false, examRhsDfpTop = 0, footerOffsetTop = 0, footerSeen = false;
class ExamPage extends React.Component{
	constructor(props)
	{
		super(props);
		this.state = {
        	isShowLoader : false,
			showPopupFlag : false,
			guideDownloadMsg : 'The exam guide has been sent to your email id. The email also includes important information such as Institutes Accepting this Exam, Related Articles, Questions & Answers, Similar Exams & more.',
			topCtaIdLabel1 : 'getUpdatesTop',
			topCtaIdLabel2 : 'getQuestionPaperTop',
			showInlineRegistrationForm: false
    	};
	}

	componentDidMount(){
		let serverCall = true;
		window.isHeaderFixed = false;
		resetGNB();
		if(!isServerSideRenderedHTML('examPwaDesktop')){
			serverCall = false;
			let nextHash = this.getHashByParams(this.props.location.pathname, this.props.location.search);
        	this.initialFetchData(nextHash,this.props.location.pathname);
        }
		if(typeof window != 'undefined'){
			createObserver();
		}
		if(serverCall && this.props.examPageData != null && this.props.examPageData.examBasicInfo != null){
			let examGroupId = getExamGroupIdForCTA(this.props.examPageData.examBasicInfo);
			viewedResponse({trackingKeyId : ExamConfig['desktop'][this.props.examPageData.activeSection]['trackingKeys']['viewedResponse'], listingId : examGroupId, listingType : 'exam', actionType : 'exam_viewed_response'});
		}
		this.dfpData();
		if(serverCall){
			this.beaconTrackCall();
		}
		let searchObj = parseQueryParams(this.props.location.search);
		if(typeof searchObj.scrollTo !== 'undefined' && searchObj.scrollTo.match(/[A-Za-z]+/) != null){
			console.log(searchObj.scrollTo);
			goTo(searchObj.scrollTo,'desktop');
		}
		disableGuideDownload('examGuide');
		disableDownloadBrochure();
		window.addEventListener('scroll', this.handleWindowScroll.bind(this));
		window.scrollTo(0, window.scrollY-1);
		if(!isUserLoggedIn()){
			this.setState({
				showInlineRegistrationForm :true
			});
		}
		this.getWikiDfp();
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
	getHashByParams = (url, queryStr = '') => {
		let params = {};
		if(queryStr !== ''){
			params = parseQueryParams(queryStr);
		}
		let paramsObj = {};
		paramsObj['url'] = url;
		if(typeof params.course != 'undefined' && params.course.match(/^-{0,1}\d+$/) !== null){
			paramsObj['groupId'] = params.course;
		}
		return Buffer.from(JSON.stringify(paramsObj)).toString('base64');
	};
	handleWindowScroll() {
		this.handleRightSideDFPSticky();
	}
	handleRightSideDFPSticky = () => {
		let stickyHeight = 125;
		if(typeof document.getElementById('exam_rhs_dfp') === 'undefined' || document.getElementById('exam_rhs_dfp') == null){
			return;
		}
		if(document.getElementById('exam_rhs_dfp').offsetTop > examRhsDfpTop) {
			examRhsDfpTop = document.getElementById('exam_rhs_dfp').offsetTop;
			examRhsDfpTop -= stickyHeight;
			footerOffsetTop = document.getElementById('footer').offsetTop;
		}
		currScroll = window.scrollY;
		if(!isDfpFixed && !footerSeen && currScroll > scrollTop && currScroll >= examRhsDfpTop){ //scroll down
			isDfpFixed = true;
			document.getElementById('exam_rhs_dfp').classList.add('rhs-dfp-fix');
			document.querySelector('#exam_rhs_dfp').style.top = stickyHeight+'px';
		}else if(isDfpFixed && !footerSeen && currScroll < scrollTop && currScroll <= examRhsDfpTop){ //scroll up
			isDfpFixed = false;
			document.getElementById('exam_rhs_dfp').classList.remove('rhs-dfp-fix');
			document.querySelector('#exam_rhs_dfp').style.top = '';
		}else if(isDfpFixed && !footerSeen && currScroll > (footerOffsetTop - document.getElementById('exam_rhs_dfp').offsetHeight - stickyHeight)){
			isDfpFixed = false;
			footerSeen = true;
			document.getElementById('exam_rhs_dfp').classList.remove('rhs-dfp-fix');
			document.querySelector('#exam_rhs_dfp').style.top = '';
		}else if(!isDfpFixed && footerSeen && currScroll < (footerOffsetTop - document.getElementById('exam_rhs_dfp').offsetHeight - stickyHeight)){
			isDfpFixed = true;
			footerSeen = false;
			document.getElementById('exam_rhs_dfp').classList.add('rhs-dfp-fix');
			document.querySelector('#exam_rhs_dfp').style.top = stickyHeight+'px';
		}
		scrollTop = currScroll;
	};

    initialFetchData(params,url){
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
			examRhsDfpTop = 0;
			if(that.props.examPageData != null && that.props.examPageData.examBasicInfo != null) {
				let examGroupId = getExamGroupIdForCTA(that.props.examPageData.examBasicInfo);
				viewedResponse({trackingKeyId: ExamConfig['desktop'][that.props.examPageData.activeSection]['trackingKeys']['viewedResponse'], listingId: examGroupId, listingType: 'exam', actionType: 'exam_viewed_response'});
			}
			that.dfpData();
			that.beaconTrackCall();
			that.getWikiDfp();
			that.setState({isShowLoader : false});
		});
    }
	dfpData(){
		this.props.dfpBannerConfig(getExamPageDFPData(this.props.examPageData, 'desktop'));
	}
	beaconTrackCall(){
		let trackParams = getExamPageTrackingData(this.props.examPageData);
		ElasticSearchTracking(trackParams.beaconTrackData, config().BEACON_TRACK_URL);
		TagManager.dataLayer({dataLayer:trackParams.gtmParams, dataLayerName:'dataLayer'});
		setVariablesForScrollableMMP(trackParams.gtmParams);
	}

	renderLoader() {
    	PreventScrolling.enableScrolling(true);
    	return <ContentLoaderDesktop/>;
  	}

  	getNextHash(nextProps){
  		const {location} = nextProps;
		let url = location.pathname;
		let params = parseQueryParams(location.search);
		let paramsObj = {};
		paramsObj['url'] = url;
    	if(params!="undefined"){
    		paramsObj['groupId'] = params.course;
    	}
    	return Buffer.from(JSON.stringify(paramsObj)).toString('base64');
  	}

  	componentWillUnmount() {
		window.isHeaderFixed = true;
		this.props.clearDfpBannerConfig();
		window.removeEventListener('scroll', this.handleWindowScroll.bind(this));
		destroyObserver();
	}
	componentDidUpdate(){
		loadResources();
		disableGuideDownload('examGuide');
		disableDownloadBrochure();
	}

	componentWillReceiveProps(nextProps){
		let nextPath = nextProps.location.pathname;
		let prevPath = this.props.location.pathname;
		if(nextPath!=prevPath || this.props.location.search != nextProps.location.search){
			this.initialFetchData(this.getHashByParams(nextPath, nextProps.location.search),nextPath);
		}
  	}

	getQuestionPaperTopCTACallback = (response, data) => {
		window.location.href = data.redirectUrl;
	};
	
	getUpdatesTopCTACallback = (response, data) => {
		let self = this;
		PopupLayer.preload().then(()=>{
			self.setState({showPopupFlag:true});
			if(response['status'] === 'SUCCESS'){
				document.getElementsByClassName(data.elemClass)[0].classList.add('eaply-disabled');
				updateGuideTrackCookie(data.groupId, 'examGuide');
				isGuideDownloaded(data.groupId, 'examGuide');
				self.PopupLayer.open();
			}else{
				self.setState({guideDownloadMsg : 'Some error occurred. Please try after some time.'});
				self.PopupLayer.open();
			}
		});
	};

	closeGetUpdatesCTASuccessPopup = () => {
        if(typeof userHasLoggedIn == 'undefined'){
        	window.location.reload();
	    }else{
	     	this.setState({showPopupFlag:false});
	    }
	};

	changeCTATrackingKeyIdWhenSticky(){
		if(this.state.topCtaIdLabel1 != 'getUpdatesTopSticky' && this.state.topCtaIdLabel2 != 'getQuestionPaperTopSticky'){
			this.setState({topCtaIdLabel1 : 'getUpdatesTopSticky', topCtaIdLabel2 : 'getQuestionPaperTopSticky'});
		}
	}

	changeCTATrackingKeyIdWhenNonSticky(){
		if(this.state.topCtaIdLabel1 != 'getUpdatesTop' && this.state.topCtaIdLabel2 != 'getQuestionPaperTop'){
			this.setState({topCtaIdLabel1 : 'getUpdatesTop', topCtaIdLabel2 : 'getQuestionPaperTop'});
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
		if((this.props.examPageData && typeof this.props.examPageData.statusCode != 'undefined' && this.props.examPageData.statusCode== 404)){
        	return <NotFound deviceType="desktop" />;
    	}else if((this.props.examPageData && typeof this.props.examPageData.statusCode != 'undefined' && this.props.examPageData.statusCode== 301)){
    		return <Redirect to={{ pathname: this.props.examPageData.url, state: { status: 301 } }}/>
    	}else if((this.props.examPageData && typeof this.props.examPageData.statusCode != 'undefined' && this.props.examPageData.statusCode== 302)){
			return <Redirect to={{ pathname: this.props.examPageData.url, state: { status: 302 } }}/>
		}

    	let pageComponents = '', sectionName = null, sectionUrls = null, sectionNameMapping = null, activeSection = 'homepage', tocData= '', trackingKeyList = '', breadCrumbData='', seoData='';

    	if(typeof this.props.examPageData.contentInfo != 'undefined' && this.props.examPageData.contentInfo){
			activeSection  = (this.props.examPageData.activeSection) ? this.props.examPageData.activeSection : 'homepage';
			trackingKeyList= ExamConfig['desktop'][activeSection]['trackingKeys'];
			pageComponents = getPageComponents({...this.props} ,'desktop',trackingKeyList, undefined, newCTAConfig,{'showInlineRegistrationForm':this.state.showInlineRegistrationForm});
			sectionName    = (typeof this.props.examPageData.contentInfo.sectionname !='undefined' && this.props.examPageData.contentInfo.sectionname) ? this.props.examPageData.contentInfo.sectionname : null;
			sectionUrls    = (typeof this.props.examPageData.contentInfo.sectionUrls !='undefined') ? this.props.examPageData.contentInfo.sectionUrls : null;
			sectionNameMapping = (typeof this.props.examPageData.contentInfo.sectionNameMapping !='undefined') ? this.props.examPageData.contentInfo.sectionNameMapping : null;
			sectionNameMapping['homepage'] = 'Overview';
			tocData        = (typeof this.props.examPageData.contentInfo[activeSection] !='undefined') ? this.props.examPageData.contentInfo[activeSection].wiki : null;
			breadCrumbData = (typeof this.props.examPageData !='undefined' && this.props.examPageData.breadCrumb) ?  prepareBreadCrumbData(this.props.examPageData.breadCrumb, 'desktop') : '';
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
		let gaCategory = 'EXAM'+(activeSection === 'homepage' ? '' : ' '+examPageData.contentInfo.sectionNameMapping[activeSection].toUpperCase())+' PAGE';
	    let gaWidget = 'Top_Card';
	    let idLabel = 'ecpTopCardCtaInfo';
	    if(this.state.topCtaIdLabel1.indexOf('Sticky') >= 0){
			gaWidget = 'Top_Sticky_Card';
			idLabel = 'ecpTopStickyCtaInfo';
		}
	    let formDataForDownloadSamplePapers = {
			trackingKeyId : ExamConfig['desktop'][activeSection]['trackingKeys'][this.state.topCtaIdLabel2],
			callbackFunction : 'redirectSamplePaperPage',  //callback from jQuery version in shiksha/public/js/registrationCallbacks.js
			callbackFunctionParams : {groupId : this.props.examPageData.groupBasicInfo.groupId, redirectUrl : this.props.examPageData.contentInfo.sectionUrls.samplepapers, examName : this.props.examPageData.examBasicInfo.name, actionType : 'exam_download_sample_paper'},
			callbackObj : '',
			cta : 'exam_download_sample_paper'
		};
	    let GetQuestionPaperCTATop = null;
	    if(this.props.examPageData.contentInfo!=null && this.props.examPageData.contentInfo.sectionUrls != null && this.props.examPageData.contentInfo.sectionUrls.samplepapers){
			GetQuestionPaperCTATop = <ExamResponseDesktop gaCategory={gaCategory} gaWidget={gaWidget} listingId={this.props.examPageData.groupBasicInfo.groupId} listingType='exam' actionType={formDataForDownloadSamplePapers.cta} formData={formDataForDownloadSamplePapers} className="trnBtn" ctaText="Get Question Papers" ctaId="getQuestionPaperTop" ref={() => window.getQuestionPaperTopCTACallback = this.getQuestionPaperTopCTACallback.bind(this)} />;
		}

		let formDataForDownloadGuide = {
			trackingKeyId : ExamConfig['desktop'][activeSection]['trackingKeys'][this.state.topCtaIdLabel1],
			callbackFunction : 'callDownloadGuide', //callback from jQuery version in shiksha/public/js/registrationCallbacks.js
			callbackFunctionParams : {groupId : this.props.examPageData.groupBasicInfo.groupId, elemClass: 'dwn-eguide', examName : this.props.examPageData.examBasicInfo.name, actionType : 'exam_download_guide'},
			callbackObj : '',
			cta : 'exam_download_guide'
		};
	    let GetUpdatesCTATop = <ExamResponseDesktop gaCategory={gaCategory} gaWidget={gaWidget} ctaType={'guideDownload'} listingId={this.props.examPageData.groupBasicInfo.groupId} listingType='exam' actionType={formDataForDownloadGuide.cta} formData={formDataForDownloadGuide} className={"btnYellow dwn-eguide"} ctaText="Download Guide" ctaId="getUpdatesTop" ref={() => window.getUpdatesTopCTACallback = this.getUpdatesTopCTACallback.bind(this)} />;
	    let instituteAcceptingCount = this.props.examPageData.ctpCountAndUrlResponse!=null && this.props.examPageData.ctpCountAndUrlResponse.collegeCount!=null?this.props.examPageData.ctpCountAndUrlResponse.collegeCount:0;
	    let instituteAcceptingViewAllUrl = this.props.examPageData.ctpCountAndUrlResponse!=null && this.props.examPageData.ctpCountAndUrlResponse.pageUrl!=null && this.props.examPageData.ctpCountAndUrlResponse.pageUrl.url!=null?this.props.examPageData.ctpCountAndUrlResponse.pageUrl.url:getInstituteAcceptingViewAllUrl(this.props.examPageData.examBasicInfo.id, this.props.examPageData.examBasicInfo.name,this.props.examPageData.groupBasicInfo.hierarchyData);
		if(newCTAConfig['desktop'] && newCTAConfig['desktop'][activeSection] && newCTAConfig['desktop'][activeSection][idLabel]){
			if(this.props.examPageData.contentInfo[activeSection].guideUrls && this.props.examPageData.contentInfo[activeSection].guideUrls[activeSection]){
				let formDataForMultipleCta = {
					trackingKeyId : newCTAConfig['desktop'][activeSection][idLabel]['trackingKeyId'],
					callbackFunction : 'callDownloadGuide', //callback from jQuery version in shiksha/public/js/registrationCallbacks.js
					callbackFunctionParams : {groupId : this.props.examPageData.groupBasicInfo.groupId, fromWhere : 'multipleCTA', pdfUrl : config().IMAGES_SHIKSHA+this.props.examPageData.contentInfo[activeSection].guideUrls[activeSection], examName : this.props.examPageData.examBasicInfo.name, actionType : 'exam_download_guide'},
					callbackObj : '',
					cta : 'exam_download_guide'
				};
				GetQuestionPaperCTATop = <ExamResponseDesktop gaCategory={gaCategory} gaWidget={gaWidget} ctaType={'examMultipleCta'} listingId={this.props.examPageData.groupBasicInfo.groupId} listingType='exam' actionType={formDataForMultipleCta.cta} formData={formDataForMultipleCta} className={'button '+newCTAConfig['desktop'][activeSection][idLabel]['ctaColor']} ctaText={newCTAConfig['desktop'][activeSection][idLabel]['ctaText']} ctaId="getMultipleCTATop" />;
			}
		}
	
		return (
		<div id="examPwaDesktop">
		{ClientSeo(seoData)}
	      <section className="pwa_pagecontent">
			  <div className="inline-dfp-wrapper">
				  <div className="inline-dfp"><DFPBannerTempalte parentPage='DFP_ExamPage' bannerPlace="leaderboard_Desktop1"/></div>
				  <div className="inline-dfp"><DFPBannerTempalte parentPage='DFP_ExamPage' bannerPlace="leaderboard_Desktop2"/></div>
			  </div>
	        <div className="pwa_container">
	        	{breadCrumbData && <BreadCrumb breadCrumb={breadCrumbData} /> }
	        	<div className="cover" id="topCover">
	        	<div id="examPageHeader">
			        <section className="pwa_banner">
				        {<ExamTopCard {...this.props} GetQuestionPaperCTA={GetQuestionPaperCTATop} GetUpdatesCTA={GetUpdatesCTATop} originalSectionName={examPageData.contentInfo.sectionNameMapping[activeSection]} activeSection={activeSection} config={config()} trackingKeyList={trackingKeyList} deviceType="desktop"/>}
						{this.state.showPopupFlag && <PopupLayer closePopup={this.closeGetUpdatesCTASuccessPopup} onContentClickClose={false} heading={'Download Guide'} onRef={ref => (this.PopupLayer = ref)} data={this.state.guideDownloadMsg}/>}
			      	</section>
			      	<section className="pwa_l2menu">
				        {sectionName && <ExamMenu changeCTATrackingKeyIdWhenSticky={this.changeCTATrackingKeyIdWhenSticky.bind(this)} changeCTATrackingKeyIdWhenNonSticky={this.changeCTATrackingKeyIdWhenNonSticky.bind(this)} deviceType={'desktop'} activeSection={activeSection} sectionName={sectionName} sectionUrl= {sectionUrls} sectionNameMapping={sectionNameMapping}/>}
			      	</section>
			      	</div>
		      	</div>
            <div className="pwa_examwraper" id="pwa_examwraper">
	          <div className="pwa_leftCol" id="pwa_leftCol">
	          <TOCWidget tocData={finalTOCData} gaCategory={'ExamPage'} deviceType={'desktop'}/>
	          <div key="sh1999" className="shareWidgetDesk"><SocialSharingBand widgetPosition={"EP_"+Ucfirst(activeSection)+"_Top"} deviceType="desktop"/></div>
	          {pageComponents}
	          {activeSection !== 'homepage' && <Feedback pageId={this.props.examPageData.groupBasicInfo.groupId} pageType={'ECP'} subPageType={activeSection} deviceType={'desktop'} feedbackWidgetType={'type2'} />}
		  	{Object.keys(resultSet).length!==0 && <ArticlesComponent heading={`${this.props.examPageData.examBasicInfo.name} Updates & Articles`} showCount={false} config={config()} data={resultSet} page='ExamPage' deviceType='desktop' />}
	          <AnA anaWidget={this.props.examPageData.anaWidget} activeSection={activeSection} config={config()} page = "examPage" trackingKeyId={ExamConfig['desktop'][activeSection]['trackingKeys']['askNow']} heading={"Have any doubt related to "+ `${this.props.examPageData.examBasicInfo.name}` + "? Ask our experts"} examId={this.props.examPageData.examBasicInfo.id} groupId={this.props.examPageData.groupBasicInfo.groupId} deviceType="desktop"/>
	        {this.props.examPageData.similarExams && <ExamSimilar originalSectionName={this.props.examPageData.contentInfo.sectionNameMapping[activeSection]} activeSection={activeSection} examName={this.props.examPageData.examBasicInfo.name} data={this.props.examPageData.similarExams} groupData={(this.props.examPageData.groupBasicInfo) ? this.props.examPageData.groupBasicInfo : null} examId={this.props.examPageData.examBasicInfo.id} deviceType='desktop' trackingKeyList={trackingKeyList}/>}
	          </div>
	       <div className="pwa_rightCol" id="pwa_rightCol">
		   <div id="exam_rhs_content">
			{this.props.examPageData.featuredColleges && <ExamCarousel heading="Featured Institute" data={this.props.examPageData.featuredColleges} category="ExamPage_Featured_Institute" deviceType='desktop'/>}
	         	{this.props.examPageData.epUpdates && this.props.examPageData.epUpdates!=null && <ExamNotification examName={this.props.examPageData.examBasicInfo.name} groupId={this.props.examPageData.groupBasicInfo.groupId} notifications={this.props.examPageData.epUpdates} originalSectionName={this.props.examPageData.contentInfo.sectionNameMapping[activeSection]} activeSection={activeSection} deviceType='desktop'/>}
	         	{this.props.examPageData.acceptingWidget!=null && this.props.examPageData.acceptingWidget.totalInstituteCount>0 && <ExamInstituteAccepting deviceType='desktop' acceptingWidget={this.props.examPageData.acceptingWidget} originalSectionName={this.props.examPageData.contentInfo.sectionNameMapping[activeSection]} activeSection={activeSection} examName={this.props.examPageData.examBasicInfo.name} viewAllUrl={instituteAcceptingViewAllUrl} instituteAcceptingCount={instituteAcceptingCount}/>}
			{this.props.examPageData.upcomingEvents && this.props.examPageData.upcomingEvents!=null && this.props.examPageData.upcomingEvents.events.length>0 && <ExamUpcomingDates originalSectionName={this.props.examPageData.contentInfo.sectionNameMapping[activeSection]} activeSection={activeSection} upcomingEvents={this.props.examPageData.upcomingEvents} deviceType='desktop'/> }
				  </div>
				  <div id="exam_rhs_dfp"><DFPBannerTempalte bannerPlace="desktop_right_panel"/></div>
	          </div>
	         </div> 
	        </div>
	      </section>
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
	fetchExamPageData : PropTypes.func,
	dfpBannerConfig : PropTypes.func,
	clearDfpBannerConfig : PropTypes.func,
};
export default connect(mapStateToProps,mapDispatchToProps)(ExamPage);
