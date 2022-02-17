import React from 'react';
import  './../assets/chphomePageCss.css';
import {withRouter, Redirect} from 'react-router-dom';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import {fetchCourseHomePageData} from './../actions/CourseHomePageAction';
import {getChpTrackingParams,createSections,prepareDFPdata,getEncodedUrlParams} from './../utils/chpUtil';
import Banner from './../components/Banner';
import Registration from './Registration';
import ErrorMsg from './../../../common/components/ErrorMsg';
import NotFound from './../../../common/components/NotFound';
import {DFPBannerTempalte} from './../../../reusable/components/DFPBannerAd';
import TagManager  from './../../../reusable/utils/loadGTM';
import ElasticSearchTracking from './../../../reusable/utils/ElasticSearchTracking';
import {dfpBannerConfig,clearDfpBannerConfig} from './../../../reusable/actions/commonAction';
import Menu from './Menu';
import OtherTopics from './../components/OtherTopics';
import {isErrorPage,isServerSideRenderedHTML} from './../../../reusable/utils/commonUtil';
import {getCookie,isUserLoggedIn} from './../../../../utils/commonHelper';
import ContentLoaderDesktop from './../utils/contentLoaderDesktop';
import PreventScrolling from './../../../reusable/utils/PreventScrolling';
import ClientSeo from './../../../common/components/ClientSeo';

class CourseHomePageDesktop extends React.Component
{
  constructor(props)
  {
    super(props);
    this.state = {
          isShowLoader : false,
          'showRegistration':false
      }
  }

  trackGTM()
    {
        const {config, courseHomePageData} = this.props;
        var trackingParams;
        var beaconData = {};
        if(typeof courseHomePageData != 'undefined' && courseHomePageData)
        {
            trackingParams = getChpTrackingParams(courseHomePageData);
            if(typeof trackingParams != 'undefined' && trackingParams){
                TagManager.dataLayer({dataLayer:trackingParams.gtmParams,dataLayerName:'dataLayer'});
                ElasticSearchTracking(trackingParams.beaconTrackData,config.BEACON_TRACK_URL);  
            }
        }
    }

  renderLoader() {
    PreventScrolling.enableScrolling(true);
/*    if(PreventScrolling.canUseDOM())
    {
        document.getElementById('page-header').style.display = "table";
        document.getElementById('page-header').style.position = "relative";
    }*/
    return <ContentLoaderDesktop/>;
  }

  componentDidMount(){
    window.isHeaderFixed = false;
    if(!isUserLoggedIn()){
        this.setState({'showRegistration':true});
    }
    if(!isServerSideRenderedHTML('ChpDesktop'))
    {
      const {location} = this.props;
      let url = location.pathname;
      let params = getEncodedUrlParams(url);
      this.initialFetchData(params);
    }else{
      if(!isErrorPage()){
        this.trackGTM();
        this.getDFPData();
      }
    }

    if(typeof(document) !='undefined' && document.getElementsByClassName('tupleBrochureButton').length>0){
      let ebLen = document.getElementsByClassName('tupleBrochureButton');
      for(var i=0;i<ebLen.length;i++){
        let courseId = ebLen[i].getAttribute('courseid');
        let ebCookie = 'applied_'+courseId;
        if(getCookie(ebCookie)){
          document.getElementById('ebTxt'+courseId).innerText = 'Brochure Mailed';
          ebLen[i].classList.add('ebDisabled');
        }
      }
    }

  }

   componentWillReceiveProps(nextProps)
    {
      let nextHash = nextProps.location.pathname;
      let prevHash = this.props.location.pathname;
      if(prevHash != nextHash || this.props.location.search != nextProps.location.search){
        let params = getEncodedUrlParams(nextHash);
        this.initialFetchData(params);
      }
    }

    initialFetchData(params)
    {
      var self = this;
      this.setState({isShowLoader : true});
      let fetchPromise = this.props.fetchCourseHomePageData(params);
      fetchPromise.then(function(res){
        self.setState({isShowLoader : false});
        self.trackGTM();
        self.getDFPData();
      });
    }

  getDFPData(){
    var dfpPostParams = 'parentPage=DFP_CourseHomePage';
    if(typeof this.props.courseHomePageData != 'undefined' && this.props.courseHomePageData.chpId){
      dfpPostParams +='&entity_id='+this.props.courseHomePageData.chpId+'&extraPrams='+prepareDFPdata(this.props.courseHomePageData);
    }
    this.props.dfpBannerConfig(dfpPostParams);
  }

  componentWillUnmount(){
    window.isHeaderFixed = true;
    this.props.clearDfpBannerConfig();
  }

   isErrorPage()
   {
    let html404 = document.getElementById('notFound-Page');
    return (html404 && html404.innerHTML);
  }

  render()
  {
    let prevHash = this.props.location.pathname;
    if(this.state.isShowLoader || (this.props.courseHomePageData.url!=null && (prevHash != this.props.courseHomePageData.url && this.props.courseHomePageData.statusCode == 200))){
      return (
          <React.Fragment>
              {this.renderLoader()}
          </React.Fragment>
          );
    }

    if((this.props.courseHomePageData && typeof this.props.courseHomePageData.statusCode != 'undefined' && this.props.courseHomePageData.statusCode== 404)){
        return <NotFound deviceType = 'desktop'/>;
    }else if((this.props.courseHomePageData && typeof this.props.courseHomePageData.statusCode != 'undefined' && this.props.courseHomePageData.statusCode== 301)){
        return <Redirect to={{ pathname: this.props.courseHomePageData.url, state: { status: 301 } }}/>;
    }

    const {config, courseHomePageData} = this.props;
    if(typeof courseHomePageData == 'undefined' || Object.keys(this.props.courseHomePageData).length==0) {
        return (
            <React.Fragment>
                {this.renderLoader()}
            </React.Fragment>
            );
    }
    else if(typeof courseHomePageData == 'undefined' || courseHomePageData == null) {
      return <ErrorMsg/>;
    }

    let currHash = getEncodedUrlParams(this.props.location.pathname);
    if(this.props.courseHomePageData.chpUrlHash != currHash) {
      return this.renderLoader();
    }

    let isPdfCall = true;
    let chpSections   = createSections(this.props.config, this.props.courseHomePageData ,isPdfCall, 'desktop');
    let regData = (typeof(this.props.courseHomePageData.hpParams) != 'undefined' && typeof(this.props.courseHomePageData.hpParams.national) != 'undefined' && this.props.courseHomePageData.hpParams.national) ? this.props.courseHomePageData.hpParams.national : null; 
    let seoData = (courseHomePageData && courseHomePageData.seoData) ? courseHomePageData.seoData : '';
    return (
      <div id="ChpDesktop">
      {ClientSeo(seoData)}
      <section className="pwa_banner">
        <Banner isPdfCall={false} count={this.props.courseHomePageData.counts} imageUrl={this.props.courseHomePageData.desktopImageUrl}  displayName={this.props.courseHomePageData.displayName} chpData={this.props.courseHomePageData} guideTrackingKey="1949" deviceType="desktop"/>     
      </section>

      <section className="pwa_l2menu">
        {<Menu isPdfCall={isPdfCall} config={this.props.config} sectionData={this.props.courseHomePageData} deviceType="desktop"/>}
      </section>

      <section className="pwa_pagecontent chp">
        <div className="pwa_container">
          <div className="pwa_leftCol">{chpSections}</div>
          <div className="pwa_rightCol">
          <DFPBannerTempalte bannerPlace="content" key="dfp"/>
          {(this.state.showRegistration) && <Registration hpParams={regData} tarckingKey="1965"/>}
          {(typeof this.props.courseHomePageData.relatedCHP != null && this.props.courseHomePageData.relatedCHP.length>0) && <OtherTopics relatedData={this.props.courseHomePageData.relatedCHP} key={19} deviceType="desktop" config={this.props.config} />}
          </div>
        </div>
      </section>
      </div>
      )
  }
}

function mapStateToProps(state)
{
  return {
      courseHomePageData : state.courseHomePageData,
      config : state.config
  }
}

function mapDispatchToProps(dispatch){
  return bindActionCreators({ fetchCourseHomePageData,  dfpBannerConfig,clearDfpBannerConfig}, dispatch);
}

export default withRouter(connect(mapStateToProps,mapDispatchToProps)(CourseHomePageDesktop));
