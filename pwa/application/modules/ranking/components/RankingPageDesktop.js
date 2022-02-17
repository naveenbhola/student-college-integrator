import PropTypes from 'prop-types'
import React from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';

import {parseQueryParams,isEmpty,togglePageCSSForFullPageLayer} from '../../../utils/commonHelper';
import {dfpBannerConfig,clearDfpBannerConfig} from './../../reusable/actions/commonAction';
import {fetchRankingPageData} from './../actions/RankingPageAction';
import {getRankingPageParamStr} from './../utils/rankingUtil';
import {isServerSideRenderedHTML} from './../../reusable/utils/commonUtil';
import {getRequest} from './../../../utils/ApiCalls';
import {getCookie} from './../../../utils/commonHelper';

import '../assets/RankingFirstfold.css';
import '../assets/RankingStyle.css';
import '../assets/RankingDesktopFooterStyle.css';

import APIConfig from './../../../../config/apiConfig';
import ErrorMsg from './../../common/components/ErrorMsg';
import NotFound from './../../common/components/NotFound';
import Analytics from './../../reusable/utils/AnalyticsTracking';
import ElasticSearchTracking from './../../reusable/utils/ElasticSearchTracking';

import RankingPageInnerHeader from './RankingPageInnerHeader';
import RankingPageTuple from './RankingPageTuple';
import RankingPagination from './RankingPagination';
import RankingPageDataInTable from './RankingPageDataInTable';
import RankingLocationWidget from './RankingLocationWidget';
import RankingLoader from './../utils/rankingLoader';
import RankingFilter from './RankingFilterBar';
import RankingSearch from './RankingSearch';
import ClientSeo from './../../common/components/ClientSeo';
import {DFPBannerTempalte} from './../../reusable/components/DFPBannerAd';
import Feedback from "../../common/components/feedback/Feedback";

let tuplesPerPage = 30;
class RankingPageDesktop extends React.Component
{
  constructor(props){
    super(props);
    this.state = {
      showLoader : false,
      searchResultData : false,
      isSearchLayerOpen : false,
      searchLayerData : false,
      showSourceData: false,
      searchedValue : ''
    }
    this.showSearchResult = true;
    this.filterTopPos = 0;
    this.searchedString = '';
  }
  componentDidMount(){
    window.isHeaderFixed = false;
    window.scrollTo(0,0);
    let scrollBindFlag = true;
    if(!isServerSideRenderedHTML('rankingPageDesktop')){
      const {location} = this.props;
      let params = this.getEncodedUrlParams(location.pathname, location.search);
      let handlePromise = this.initialFetchData(params);
      handlePromise.then( () => {
        scrollBindFlag = false;
        this.bindLoadEvent();
      });
    }
    if(scrollBindFlag === true){
      this.bindLoadEvent();
    }
    this.showReadMoreBHST();
  }

  showReadMoreBHST(){
    if(document.getElementsByClassName('BHST_parent').length != 0 && document.getElementsByClassName('BHST_child').length != 0 && document.getElementsByClassName('BHST_parent')[0].clientHeight < document.getElementsByClassName('BHST_child')[0].clientHeight ){
      document.getElementsByClassName('BHST_read_more')[0].style.display = 'block';
    }
  }


  beaconTrackCall(){
    const {rankingPageData, config} = this.props;
    let beaconTrackData = {
      'pageIdentifier' : 'rankingPage',
      'pageEntityId' : rankingPageData.rankingPageId,
      'extraData' : {
        'pageType' : 'Ranking',
        'countryId' : 2,
        'hierarchy' : {}
      }
    };
    if(rankingPageData.seoData != null && typeof rankingPageData.seoData != 'undefined' && typeof rankingPageData.seoData.canonicalUrl != 'undefined'){
      beaconTrackData.extraData.url = config.SHIKSHA_HOME + rankingPageData.seoData.canonicalUrl;
    }
    if(rankingPageData.rankingRequestData != null && rankingPageData.rankingRequestData.appliedFilters != null){
      if(typeof rankingPageData.rankingRequestData.appliedFilters.state != 'undefined' && rankingPageData.rankingRequestData.appliedFilters.state[0] > 0){
        beaconTrackData.extraData.stateId = rankingPageData.rankingRequestData.appliedFilters.state[0];
      }
      if(typeof rankingPageData.rankingRequestData.appliedFilters.city != 'undefined' && rankingPageData.rankingRequestData.appliedFilters.city[0] > 0){
        beaconTrackData.extraData.cityId = rankingPageData.rankingRequestData.appliedFilters.city[0];
      }
      if(typeof rankingPageData.rankingRequestData.appliedFilters.educationType != 'undefined' && rankingPageData.rankingRequestData.appliedFilters.educationType[0] > 0){
        beaconTrackData.extraData.educationType = rankingPageData.rankingRequestData.appliedFilters.educationType[0];
      }
      if(typeof rankingPageData.rankingRequestData.appliedFilters.deliveryMethod != 'undefined' && rankingPageData.rankingRequestData.appliedFilters.deliveryMethod[0] > 0){
        beaconTrackData.extraData.deliveryMethod = rankingPageData.rankingRequestData.appliedFilters.deliveryMethod[0];
      }
      if(typeof rankingPageData.rankingRequestData.appliedFilters.credential != 'undefined' && rankingPageData.rankingRequestData.appliedFilters.credential[0] > 0){
        beaconTrackData.extraData.credential = rankingPageData.rankingRequestData.appliedFilters.credential[0];
      }
      /*if(typeof rankingPageData.rankingRequestData.appliedFilters.exam != 'undefined' && rankingPageData.rankingRequestData.appliedFilters.exam[0] > 0){
        beaconTrackData.extraData.examId = rankingPageData.rankingRequestData.appliedFilters.exam[0];
      }*/
      if(typeof rankingPageData.rankingRequestData.appliedFilters.streams != 'undefined' && rankingPageData.rankingRequestData.appliedFilters.streams[0] > 0){
        beaconTrackData.extraData.hierarchy.streamId = rankingPageData.rankingRequestData.appliedFilters.streams[0];
      }
      if(typeof rankingPageData.rankingRequestData.appliedFilters.substreams != 'undefined' && rankingPageData.rankingRequestData.appliedFilters.substreams[0] > 0){
        beaconTrackData.extraData.hierarchy.substreamId = rankingPageData.rankingRequestData.appliedFilters.substreams[0];
      }
      if(typeof rankingPageData.rankingRequestData.appliedFilters.specializations != 'undefined' && rankingPageData.rankingRequestData.appliedFilters.specializations[0] > 0){
        beaconTrackData.extraData.hierarchy.specializationId = rankingPageData.rankingRequestData.appliedFilters.specializations[0];
      }
      if(typeof rankingPageData.rankingRequestData.appliedFilters.baseCourse != 'undefined' && rankingPageData.rankingRequestData.appliedFilters.baseCourse[0] > 0){
        beaconTrackData.extraData.hierarchy.baseCourseId = rankingPageData.rankingRequestData.appliedFilters.baseCourse[0];
      }
    }
    ElasticSearchTracking(beaconTrackData, config.BEACON_TRACK_URL);
  }
  componentWillUnmount(){
    window.isHeaderFixed = true;
    this.props.clearDfpBannerConfig();
  }
  componentWillReceiveProps(nextProps){
    let self = this;
    window.scrollTo(0,0);
    let nextHash = nextProps.location.pathname;
    let prevHash = this.props.location.pathname;
    if(prevHash != nextHash || this.props.location.search != nextProps.location.search) {
      togglePageCSSForFullPageLayer('remove');
      self.searchedString = '';
      self.initialFetchData(this.getEncodedUrlParams(nextHash, nextProps.location.search)).then( () => {
        self.beaconTrackCall();
      });
    }
  }
  bindLoadEvent(){
    if(typeof(document) !='undefined' && document.getElementsByClassName('tupleBrochureButton').length>0){
      let ebLen = document.getElementsByClassName('tupleBrochureButton');
      for(let i=0;i<ebLen.length;i++){
        let type = ebLen[i].getAttribute('type');
        let courseId = ebLen[i].getAttribute('listingid');
        let ebCookie = 'applied_'+courseId;
        if(type == 'course' && getCookie(ebCookie)){
          document.getElementById('ebTxt'+courseId).innerText = 'Brochure Mailed';
          ebLen[i].classList.add('ebDisabled');
        }
      }
    }
    this.getSearchResults();
    this.dfpData();
    this.beaconTrackCall();
  }


  prepareBreadCrumbData(){
    if(this.props.rankingPageData.breadCrumb == null || this.props.rankingPageData.breadCrumb == ''){
      return
    }
    this.props.rankingPageData.breadCrumb.forEach((value)=>{
      value.isAbsoluteUrl = false
      if(value.name == 'Home'){
        value.isAbsoluteUrl = true;
      }
    });
  }

  getEncodedUrlParams(url, queryStr){
    return getRankingPageParamStr(url, parseQueryParams(queryStr));
  }
  resetAllFilter(){
  }
  getSearchResultHeading(pageHeading){
    return pageHeading.replace('Top', 'Popular');
  }
  dfpData(){
    let dfpPostParams = 'parentPage=DFP_RankingPage';
    if(this.props.rankingPageData != null && typeof this.props.rankingPageData.rankingRequestData != 'undefined' && this.props.rankingPageData.rankingRequestData != null && typeof this.props.rankingPageData.rankingPageId != 'undefined' && this.props.rankingPageData.rankingPageId != null)
    {
      const appliedFilters = typeof this.props.rankingPageData.rankingRequestData.appliedFilters != 'undefined' ? this.props.rankingPageData.rankingRequestData.appliedFilters: {};
      let dfpParams = '';
      if(!isEmpty(appliedFilters)){
        let dfpData = {};
        dfpData['streams']         = appliedFilters.streams;
        dfpData['substreams']      = appliedFilters.substreams;
        dfpData['baseCourse']      = appliedFilters.baseCourse;
        dfpData['specializations'] = appliedFilters.specializations;
        dfpData['city']            = appliedFilters.city;
        dfpData['state']           = appliedFilters.state;
        dfpData['educationType']   = appliedFilters.educationType;
        dfpData['deliveryMethod']  = appliedFilters.deliveryMethod;
        dfpParams                  = JSON.stringify(dfpData);
      }
      dfpPostParams +='&entity_id='+this.props.rankingPageData.rankingPageId+'&extraPrams='+dfpParams;
    }
    this.props.dfpBannerConfig(dfpPostParams);
  }
  resetSearch(){
    this.searchedString = '';
    const {location} = this.props;
    let params = this.getEncodedUrlParams(location.pathname, location.search);
    this.initialFetchData(params);
  }
  getSearchResults(){
    if(this.showSearchResult && this.props.rankingPageData.numberOfResultsFound < tuplesPerPage && this.props.rankingPageData.rankingInstituteTuple != null && this.props.rankingPageData.rankingInstituteTuple.length < tuplesPerPage){
      this.showSearchResult = false;
      if(this.props.rankingPageData.rankingRequestData == null){
        return;
      }
      let postData = {};
      postData['rpid'] = this.props.rankingPageData.rankingRequestData.appliedFilters.rankingPageIds[0];
      postData['pl'] = (tuplesPerPage - this.props.rankingPageData.rankingInstituteTuple.length);
      if(this.props.rankingPageData.rankingRequestData.appliedFilters.baseCourse[0] > 0){
        postData['bc'] = this.props.rankingPageData.rankingRequestData.appliedFilters.baseCourse[0];
      }
      if(this.props.rankingPageData.rankingRequestData.appliedFilters.streams[0] > 0){
        postData['st'] = this.props.rankingPageData.rankingRequestData.appliedFilters.streams[0];
      }
      if(this.props.rankingPageData.rankingRequestData.appliedFilters.substreams[0] > 0){
        postData['sb'] = this.props.rankingPageData.rankingRequestData.appliedFilters.substreams[0];
      }
      if(this.props.rankingPageData.rankingRequestData.appliedFilters.specializations[0] > 0){
        postData['sp'] = this.props.rankingPageData.rankingRequestData.appliedFilters.specializations[0];
      }
      if(this.props.rankingPageData.rankingRequestData.appliedFilters.city[0] > 0){
        postData['city'] = this.props.rankingPageData.rankingRequestData.appliedFilters.city[0];
      }
      if(this.props.rankingPageData.rankingRequestData.appliedFilters.state[0] > 0){
        postData['state'] = this.props.rankingPageData.rankingRequestData.appliedFilters.state[0];
      }
      if(this.props.rankingPageData.rankingRequestData.appliedFilters.exam[0] > 0){
        postData['exam'] = this.props.rankingPageData.rankingRequestData.appliedFilters.exam[0];
      }
      if(this.props.rankingPageData.rankingInstituteTuple.length > 0){
        postData['pid'] = [];
        this.props.rankingPageData.rankingInstituteTuple.forEach(tuple => {
          postData['pid'].push(tuple.instituteId);
        });
      }

      let finalPostData = Buffer.from(JSON.stringify(postData)).toString('base64');
      getRequest(APIConfig.GET_RANKINGPAGE_SEARCH_RESULTS+'?data='+finalPostData, {}).then( (response) => {
        this.setState({searchResultData : response.data.data});
      }).catch(function(){
      });
    }
  }
  openSearchLayer(){
    Analytics.event({category : this.props.gaTrackingCategory, action : 'Search', label : 'click'});
    //this.setState({isSearchLayerOpen : true});
  }
  fetchSearchData(institutes, searchedString){
    window.scrollTo(0, 0);
    let paramObj = {
      rpid : this.props.rankingPageData.rankingRequestData.appliedFilters.rankingPageIds[0],
      country : 2,
      state : this.props.rankingPageData.rankingRequestData.appliedFilters.state[0],
      city : this.props.rankingPageData.rankingRequestData.appliedFilters.city[0],
      exam : this.props.rankingPageData.rankingRequestData.appliedFilters.exam[0],
      pn : 1
    }
    if(typeof this.props.rankingPageData.rankingRequestData.appliedFilters.rankingSourceIds != 'undefined'){
      paramObj.source = this.props.rankingPageData.rankingRequestData.appliedFilters.rankingSourceIds[0];
    }
    this.searchedString = searchedString;
    if(institutes == null || !(institutes.length > 0)){
      this.setState({isSearchLayerOpen : false, searchLayerData : true});
      return;
    }
    let instStr = institutes.join(',')
    let queryString  = JSON.stringify(paramObj);
    let data = Buffer.from(queryString).toString('base64');
    let url = APIConfig.GET_RANKINGPAGE_SEARCH_LAYER_RESULTS + "?data=" + data + '&instituteId=' + instStr;
    this.setState({showLoader : true});
    return getRequest(url).then(response => {
      this.setState({showLoader : false, isSearchLayerOpen : false, searchLayerData : response.data.data});
    });
  }
  initialFetchData(params){
    let paramsJson = JSON.parse(atob(params));
    let self=this, showSourceData = false;
    if(paramsJson.source != "undefined") {
      showSourceData = true;
    }
    self.setState({showLoader : true, showSourceData: showSourceData});
    let fetchPromise = this.props.fetchRankingPageData(params);
    return fetchPromise.then(function(){
      self.showSearchResult = true;
      self.getSearchResults();
      self.dfpData();
      self.setState({showLoader : false, searchResultData : false, searchLayerData : false});
      self.showReadMoreBHST();
    }).catch(function(){});
  }
  renderLoader () {
    let loaderWithData = <section className="bg-white cntnt-laderbrder">
      <div className="loader-line shimmer wdt36"></div>
      <div className="loader-line shimmer"></div>
      <div className="loader-line shimmer"></div>
    </section>;
    if(typeof this.props.rankingPageData != 'undefined' && Object.keys(this.props.rankingPageData).length > 0){
      loaderWithData = <section className="bg-white cntnt-laderbrder">
        <RankingPageInnerHeader rankingPageName={this.props.rankingPageData.seoData.heading} disclaimer={this.props.rankingPageData.disclaimer} breadCrumb={this.props.rankingPageData.breadCrumb}/>
        <div className='filter_wrap'>
          <div className="ranking_filters">
            <RankingFilter rankingPageId={this.props.rankingPageData.rankingPageId} filters={this.props.rankingPageData.filters} filterOrder={this.props.rankingPageData.filterOrder} resetUrl={this.props.rankingPageData.resetUrl} gaTrackingCategory={this.props.gaTrackingCategory} />

            <div className="filter_space filter_pwasearch">
              <div className="search-container">
                <div className="search-box">
                  <div className="search icon"></div>
                  <input type="text" maxLength="100" placeholder="Search a college within this ranking" />
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>;
    }
    return (<div className='rankingPageDesktop'>
      <div className="dfp-loader">
        <div className='dfp-bg-rp'></div>
      </div>
      <section className="pwa_pagecontent">
      <div className="pwa_container">
        <div className='pwa_fullWidthCol'>
      {loaderWithData}
      <RankingLoader isMobile={false}/>
        </div></div></section>
    </div>);
  }
  render(){
    this.prepareBreadCrumbData();
    let currParams = this.getEncodedUrlParams(this.props.location.pathname, this.props.location.search);
    if(this.state.showLoader){
      return <React.Fragment>{this.renderLoader()}</React.Fragment>;
    }
    if(Object.keys(this.props.rankingPageData).length > 0 && typeof this.props.rankingPageData.show404 != 'undefined' && this.props.rankingPageData.show404 == true){
      return <NotFound />;
    }
    if(typeof this.props.rankingPageData == 'undefined' || Object.keys(this.props.rankingPageData).length==0) {
      return <React.Fragment>{this.renderLoader()}</React.Fragment>;
    }else if(typeof this.props.rankingPageData == 'undefined' || this.props.rankingPageData == null) {
      return <ErrorMsg/>;
    }
    else if(this.state.showSourceData == false && typeof this.props.rankingPageData.rankingUrlHash != 'undefined' && typeof currParams != 'undefined' && currParams != "" && this.props.rankingPageData.rankingUrlHash != currParams){
      return <React.Fragment>{this.renderLoader()}</React.Fragment>;
    }
    let resetUrl = this.props.rankingPageData.resetUrl;
    let seoData = (this.props.rankingPageData && this.props.rankingPageData.seoData) ? this.props.rankingPageData.seoData : '';
    return (
      <div id="rankingPageDesktop" className='rankingPageDesktop'>
      {ClientSeo(seoData)}
        <DFPBannerTempalte bannerPlace={'RP_DesktopLB_New'} parentPage={'DFP_RankingPage'}/>
        <section className="pwa_pagecontent">
          <div className="pwa_container">
            <div className="pwa_fullWidthCol">
              <RankingPageInnerHeader rankingPageName={this.props.rankingPageData.seoData.heading} disclaimer={this.props.rankingPageData.disclaimer}  breadCrumb={this.props.rankingPageData.breadCrumb} deviceType={'desktop'}/>

                <div className='filter_wrap' id="rankingFilter">
                  <div className="ranking_filters">
                    <RankingFilter rankingPageId={this.props.rankingPageData.rankingPageId} filters={this.props.rankingPageData.filters} filterOrder={this.props.rankingPageData.filterOrder} resetUrl={resetUrl} gaTrackingCategory={this.props.gaTrackingCategory} />

                    <RankingSearch rankingPageId={this.props.rankingPageData.rankingPageId} placeholder="Search a college within this ranking" textValue={this.searchedString} fetchSearchData={this.fetchSearchData.bind(this)} isSearchLayerOpen={true} onClick={this.openSearchLayer.bind(this)} onResetSearch={this.resetSearch.bind(this)} isSearchActive={this.state.searchLayerData} />
                  </div>
                </div>
              {this.state.searchLayerData === false ? <RankingPageTuple appliedFiltersData={this.props.rankingPageData &&
              this.props.rankingPageData.rankingRequestData && this.props.rankingPageData.rankingRequestData.appliedFilters ?
                  this.props.rankingPageData.rankingRequestData.appliedFilters : ''} deviceType={this.props.deviceType} rankingPageId={this.props.rankingPageData.rankingPageId}
              gaTrackingCategory={this.props.gaTrackingCategory} tupleData={this.props.rankingPageData.rankingInstituteTuple} baseCourse={this.props.rankingPageData.rankingRequestData.appliedFilters.baseCourse[0]} aggregateRatingConfig={this.props.rankingPageData.aggregateRatingConfig} selectedRankingSource={this.props.rankingPageData.selectedRankingSource} isSearchTuple={false} /> : null }

                {this.state.searchLayerData != false && this.state.searchLayerData.length > 0 ?
                    <React.Fragment>
                      <div className="showing-rslts">{"Showing results for "}<strong>{"\"" +this.searchedString +"\""}</strong></div>
                      <RankingPageTuple deviceType={this.props.deviceType} gaTrackingCategory={this.props.gaTrackingCategory} rankingPageId={this.props.rankingPageData.rankingPageId} tupleData={this.state.searchLayerData} baseCourse={this.props.rankingPageData.rankingRequestData.appliedFilters.baseCourse[0]} aggregateRatingConfig={this.props.rankingPageData.aggregateRatingConfig} selectedRankingSource={this.props.rankingPageData.selectedRankingSource} isSearchTuple={false} /></React.Fragment> : this.state.searchLayerData != false || this.state.searchLayerData.length == 0 ? <div id="rankingTupleWrapper"><div className="no_rslts">Sorry no colleges found for <strong>{"\"" +this.searchedString +"\""}</strong><div className="rp-zrp-back"><span>Here is what you can do:</span><ul><li>Check your Spelling</li><li>Try more general words Or <a href="javascript:void(0);" onClick={this.resetSearch.bind(this)}>Clear Search</a></li></ul></div></div></div> : null}

                {this.state.searchResultData != false && this.state.searchLayerData === false ?
                    <React.Fragment>
                      <div className="desk-search-head"><h4>{this.getSearchResultHeading(this.props.rankingPageData.seoData.heading)}</h4></div>
                      <RankingPageTuple deviceType={this.props.deviceType} rankingPageId={null} tupleData={this.state.searchResultData} gaTrackingCategory={this.props.gaTrackingCategory} baseCourse={this.props.rankingPageData.rankingRequestData.appliedFilters.baseCourse[0]} aggregateRatingConfig={this.props.rankingPageData.aggregateRatingConfig} selectedRankingSource={this.props.rankingPageData.selectedRankingSource} isSearchTuple={true} /></React.Fragment> : null}

                {this.state.searchLayerData === false ?
                    <div className="alter_touple">
                      <Feedback pageId={this.props.rankingPageData.rankingPageId} pageType={'RP'} deviceType={'desktop'} feedbackWidgetType={'type2'} />
                      <RankingPagination rankingPageData={this.props.rankingPageData} gaTrackingCategory={this.props.gaTrackingCategory} onRankingPageSelect={this.initialFetchData.bind(this)} />
                      <RankingPageDataInTable rankingPageName={this.props.rankingPageData.seoData.heading} rankingPageId={this.props.rankingPageData.rankingPageId} deviceType={this.props.deviceType} tableData={this.props.rankingPageData.rankingInstituteTuple} gaTrackingCategory={this.props.gaTrackingCategory} />
                    </div> : null }
              </div>
            </div>
          </section>
          <section className="ranking-footer-section" id="rankingFooterSection">
            <RankingLocationWidget heading={"Top Ranked "+this.props.rankingPageData.rankingPageName+" Colleges in"} layerHeading={"Top " + this.props.rankingPageData.rankingPageName + " Colleges in"} data={this.props.rankingPageData.filters.location} deviceType={this.props.deviceType} />
          </section>
        </div>
    );
  }
}

function mapDispatchToProps(dispatch){
  return bindActionCreators({ fetchRankingPageData, dfpBannerConfig, clearDfpBannerConfig }, dispatch);
}

function mapStateToProps(state){
  return {
    rankingPageData : state.rankingPageData,
    config : state.config
  }
}

RankingPageDesktop.defaultProps = {
  deviceType : 'desktop',
  gaTrackingCategory : 'RANKING_PAGE_DESKTOP'
}

export default connect(mapStateToProps, mapDispatchToProps)(RankingPageDesktop);

RankingPageDesktop.propTypes = {
  clearDfpBannerConfig: PropTypes.any,
  config: PropTypes.any,
  deviceType: PropTypes.string,
  dfpBannerConfig: PropTypes.any,
  fetchRankingPageData: PropTypes.any,
  gaTrackingCategory: PropTypes.string,
  location: PropTypes.any,
  rankingPageData: PropTypes.any
}
