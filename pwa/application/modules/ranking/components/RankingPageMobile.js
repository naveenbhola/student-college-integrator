import PropTypes from 'prop-types'
import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import {parseQueryParams,isEmpty} from '../../../utils/commonHelper';
import {dfpBannerConfig,clearDfpBannerConfig} from './../../reusable/actions/commonAction';
import {getRequest} from './../../../utils/ApiCalls';
import {fetchRankingPageData} from './../actions/RankingPageAction';
import {getRankingPageParamStr} from './../utils/rankingUtil';
import APIConfig from './../../../../config/apiConfig';
import '../assets/RankingFirstfold.css';
import '../assets/RankingStyle.css';
import PreventScrolling from './../../reusable/utils/PreventScrolling';
import RankingLoader from './../utils/rankingLoader';
import ErrorMsg from './../../common/components/ErrorMsg';
import NotFound from './../../common/components/NotFound';
import Analytics from './../../reusable/utils/AnalyticsTracking';
import ElasticSearchTracking from './../../reusable/utils/ElasticSearchTracking';

import RankingPageInnerHeader from './RankingPageInnerHeader';
import RankingPageTuple from './RankingPageTuple';
import RankingPageDataInTable from './RankingPageDataInTable';
import RankingPagination from './RankingPagination';
import RankingLocationWidget from './RankingLocationWidget';
import Filters from './../../listing/categoryList/components/FiltersComponent';
import SearchLayer from './../../search/components/SearchLayer';
import {DFPBannerTempalte} from './../../reusable/components/DFPBannerAd';
import ClientSeo from './../../common/components/ClientSeo';
import SocialSharingBand from './../../common/components/SocialSharingBand';

let tuplesPerPage = 30;
let currScroll = 0, scrollTop = 0, isFilterFixed = false;
class RankingPageMobile extends Component {
  constructor(props){
    super(props);
    this.state = {
      showLoader : false,
      searchResultData : false,
      isSearchLayerOpen : false,
      searchLayerData : false,
      showSourceData: false
    }
    this.showSearchResult = true;
    this.filterTopPos = 0;
    this.searchedString = null;
    this.makeDFPCall = true;
  }
  componentDidMount(){
    let scrollBindFlag = true;
    if(!this.isServerSideRenderedHTML())
    {
      const {location} = this.props;
      let params = this.getEncodedUrlParams(location.pathname, location.search);
      let handlePromise = this.initialFetchData(params);
      handlePromise.then(() => {
        scrollBindFlag = false;
        this.bindScrollForFilterSticky();
      });
    }
    if(scrollBindFlag === true){
      this.bindScrollForFilterSticky();
    }
    this.beaconTrackCall();
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
        'hierarchy' : {},
        'childPageIdentifier' : 'rankingPage'
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
  bindScrollForFilterSticky(){
    this.filterTopPos = 0;
    if(typeof document.getElementById('rankingFilter') != 'undefined' && document.getElementById('rankingFilter') != null){
      this.filterTopPos = document.getElementById('rankingFilter').offsetTop;
    }
    window.addEventListener('scroll', this.handleWindowScroll.bind(this));
    window.scrollTo(0, window.scrollY-1);
  }
  componentWillUnmount(){
    this.props.clearDfpBannerConfig();
    window.removeEventListener('scroll', this.handleWindowScroll.bind(this));
  }
  handleZrpClick(){
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

  handleWindowScroll(){
    if(this.makeDFPCall){
      this.makeDFPCall = false;
      this.dfpData();
    }
    this.getSearchResults();
    this.handleFilterSticky();
  }
  handleFilterSticky(){
    if(typeof document.getElementById('rankingTupleWrapper') == 'undefined' || document.getElementById('rankingTupleWrapper') == null){
      return;
    }
    currScroll = window.scrollY;
    if(isFilterFixed == false && currScroll > scrollTop){ //scroll down
      if(currScroll > this.filterTopPos){
        isFilterFixed = true;
        document.getElementById('rankingFilter').classList.add('filter-sticky');
        document.getElementById('rankingTupleWrapper').style.marginTop = document.getElementById('rankingFilter').offsetHeight + 'px';
      }
    }else if(isFilterFixed == true && currScroll < scrollTop){ //scroll up
      if(currScroll <= this.filterTopPos){
        isFilterFixed = false;
        document.getElementById('rankingFilter').classList.remove('filter-sticky');
        document.getElementById('rankingTupleWrapper').style.marginTop = '';
      }
    }
    scrollTop = currScroll;
  }
  fetchSearchData(paramObj, institutes, searchedString){
    window.scrollTo(0, 0);
    document.getElementById('rankingFilter').classList.remove('filter-sticky');

    this.searchedString = searchedString;
    if(institutes == null || !(institutes.length > 0)){
      this.setState({isSearchLayerOpen : false, searchLayerData : true});
      return;
    }
    let instStr = institutes.join(',')
    let queryString  = JSON.stringify(paramObj);
    let data = Buffer.from(queryString).toString('base64');
    let url = APIConfig.GET_RANKINGPAGE_SEARCH_LAYER_RESULTS + "?data=" + data + '&instituteId=' + instStr;
    return getRequest(url).then(response => {
      this.setState({isSearchLayerOpen : false, searchLayerData : response.data.data});
    });
  }
  getEncodedUrlParams(url, queryStr){
    return getRankingPageParamStr(url, parseQueryParams(queryStr));
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
      self.dfpData();
      self.beaconTrackCall();
      self.setState({showLoader : false, searchResultData : false, searchLayerData : false});
      self.showReadMoreBHST();
    }).catch(function(){});
  }
  componentWillReceiveProps(nextProps){
    let nextHash = nextProps.location.pathname;
    let prevHash = this.props.location.pathname;
    if(prevHash != nextHash || this.props.location.search != nextProps.location.search) {
      this.initialFetchData(this.getEncodedUrlParams(nextHash, nextProps.location.search));
    }
  }
  isServerSideRenderedHTML()
  {
    let htmlNode = document.getElementById('rankingPage');
    return ((htmlNode && htmlNode.innerHTML) || this.isErrorPage());
  }
  isErrorPage()
  {
    let html404 = document.getElementById('notFound-Page');
    return (html404 && html404.innerHTML);
  }
  renderLoader () {
    PreventScrolling.enableScrolling(true);
    if(PreventScrolling.canUseDOM())
    {
      document.getElementById('page-header').style.display = "table";
      document.getElementById('page-header').style.position = "relative";
    }
    return <RankingLoader/>;
  }
  onFilterButtonPress(){
    //dummy for filter
  }
  resetAllFilter(){
    const {location} = this.props;
    let curr_url = location.pathname;
    let url_parts = curr_url.split('/');
    let url_params = url_parts[url_parts.length-1].split('-');
    let new_url_params = url_params[0]+'-'+url_params[1]+'-0-0-0';
    url_parts[url_parts.length-1] = new_url_params;
    let final_url = url_parts.join('/');

    let params = this.getEncodedUrlParams(final_url, '');
    this.initialFetchData(params);
    window.history.pushState({urlPath: this.props.rankingPageData.resetUrl}, "", this.props.rankingPageData.resetUrl);
  }
  getSearchResultHeading(pageHeading){
    return pageHeading.replace('Top', 'Popular');
  }
  openSearchLayer(){
    Analytics.event({category : this.props.gaTrackingCategory, action : 'Search', label : 'click'});
    this.setState({isSearchLayerOpen : true});
  }
  closeSearchLayer(){
    this.setState({isSearchLayerOpen : false});
  }

  toShowOCFBasedOnCount(filtersArray){
    let count = 0;
    for(let filterData in filtersArray){
      if(!filtersArray.hasOwnProperty(filterData))
        continue;
      if(!filtersArray[filterData]['enabled'])
        continue;
      count++;
      if(count > 1)
        break;
    }
    return count > 1;

  }
  getOCFOrder(){
      let ocfOrder = [];

      if(this.props.rankingPageData.filters['location'] ){
          const showBc = this.toShowOCFBasedOnCount(this.props.rankingPageData.filters['location'] );
          if(showBc)
              ocfOrder.push('location')
      }      
      if(this.props.rankingPageData.rankingRequestData.appliedFilters.exam[0] == 0 && this.props.rankingPageData.filters['exam'] ){
          const showBc = this.toShowOCFBasedOnCount(this.props.rankingPageData.filters['exam'] );
          if(showBc)
              ocfOrder.push('exam')
      }
      if(this.props.rankingPageData.rankingRequestData.appliedFilters.specializations[0] == 0 && this.props.rankingPageData.filters['specialization'] ){
      const showBc = this.toShowOCFBasedOnCount(this.props.rankingPageData.filters['specialization'] );
      if(showBc)
        ocfOrder.push('specialization')
    }
    return ocfOrder;
  }

  render(){
    let currParams = this.getEncodedUrlParams(this.props.location.pathname, this.props.location.search);
    if(this.state.showLoader){
      return (
          <React.Fragment>{this.renderLoader()}</React.Fragment>
      );
    }
    if(Object.keys(this.props.rankingPageData).length > 0 && typeof this.props.rankingPageData.show404 != 'undefined' && this.props.rankingPageData.show404 == true)
    {
      return <NotFound />;
    }
    if(typeof this.props.rankingPageData == 'undefined' || Object.keys(this.props.rankingPageData).length==0) {
      return (
          <React.Fragment>{this.renderLoader()}</React.Fragment>
      );
    }else if(typeof this.props.rankingPageData == 'undefined' || this.props.rankingPageData == null) {
      return <ErrorMsg/>;
    }
    else if(this.state.showSourceData == false && typeof this.props.rankingPageData.rankingUrlHash != 'undefined' && typeof currParams != 'undefined' && currParams != "" && this.props.rankingPageData.rankingUrlHash != currParams){
      return (<React.Fragment>{this.renderLoader()}</React.Fragment>);
    }
    let resetUrl = this.props.rankingPageData.resetUrl;
    let displayName = {
      'ranking_source' : 'Ranked By',
      'location' : 'Locations',
      'exam' : 'Exams',
      'specialization' : 'Specializations'
    };
    let shortName = {
      'ranking_source' : 'rs',
      'state' : 'st',
      'city' : 'ct',
      'exam' : 'ex',
      'specialization' : 'sp'
    }
    let extraSearchParams = {
      searchType: 'rankings',
      rankingSearchParams : {
        suggestionCount : 30,
        suggestionType : 'institute',
        sby : 'popularity',
        appliedFilters : {
          rankingPageIds : [this.props.rankingPageData.rankingPageId]
        }
      },
      rankingPageParams : {
        rpid : this.props.rankingPageData.rankingRequestData.appliedFilters.rankingPageIds[0],
        country : 2,
        state : this.props.rankingPageData.rankingRequestData.appliedFilters.state[0],
        city : this.props.rankingPageData.rankingRequestData.appliedFilters.city[0],
        exam : this.props.rankingPageData.rankingRequestData.appliedFilters.exam[0],
        pn : 1
      }
    }
    if(typeof this.props.rankingPageData.rankingRequestData.appliedFilters.rankingSourceIds != 'undefined'){
      extraSearchParams.rankingPageParams.source = this.props.rankingPageData.rankingRequestData.appliedFilters.rankingSourceIds[0];
    }
    let seoData = (this.props.rankingPageData && this.props.rankingPageData.seoData) ? this.props.rankingPageData.seoData : '';
    return (
      <React.Fragment>
      {ClientSeo(seoData)}
        <div className="ranking_pwa" id="rankingPage">
          <RankingPageInnerHeader
            rankingPageName={this.props.rankingPageData.seoData.heading}
            disclaimer={this.props.rankingPageData.disclaimer}
            breadCrumb={this.props.rankingPageData.breadCrumb}
            deviceType={'mobile'}
            filterStickyFunction = {this.bindScrollForFilterSticky.bind(this)}
          />

          <div id="rankingFilter" className="ranking_filter bg-white">
           <div className="filterv1 rank_cell">
           <Filters
              filterLayerType="all_links"
              gaTrackingCategory={this.props.gaTrackingCategory}
              selectedTab="ranking_source"
              filters={this.props.rankingPageData.filters}
              displayName={displayName}
              shortName={shortName}
              filterOrder={this.props.rankingPageData.filterOrder}
              isSrp={false}
              hasFilterData={true}
              onFilterButtonPress={this.onFilterButtonPress}
              onSourceSelect={this.initialFetchData.bind(this)}
              onResetFilter={this.resetAllFilter.bind(this)}
              resetUrl={resetUrl} showTCF={false}
           />
           </div>
           <div className="rank_srchv1 rank_cell">
             <input type="text" name="" placeholder="Search Colleges" readOnly onClick={this.openSearchLayer.bind(this)} />
             <span className="srch_holder" onClick={this.openSearchLayer.bind(this)}><i className="pwa_sprite srch_i"></i></span>
             <SearchLayer
                extraSearchParams={extraSearchParams}
                isSearchLayerOpen={this.state.isSearchLayerOpen}
                onClose={this.closeSearchLayer.bind(this)}
                fetchSearchData={this.fetchSearchData.bind(this)}
                showRecentSearch={false} showUserSearches={false} />
           </div>
           </div>

           {this.state.searchLayerData === false ? <RankingPageTuple appliedFiltersData={this.props.rankingPageData &&
           this.props.rankingPageData.rankingRequestData && this.props.rankingPageData.rankingRequestData.appliedFilters ?
               this.props.rankingPageData.rankingRequestData.appliedFilters : ''} ocfOrder={this.getOCFOrder()} showOCF={true} rankingPageId={this.props.rankingPageData.rankingPageId} tupleData={this.props.rankingPageData.rankingInstituteTuple} baseCourse={this.props.rankingPageData.rankingRequestData.appliedFilters.baseCourse[0]} aggregateRatingConfig={this.props.rankingPageData.aggregateRatingConfig} selectedRankingSource={this.props.rankingPageData.selectedRankingSource} isSearchTuple={false} filters={this.props.rankingPageData.filters}
              displayName={displayName}
              shortName={shortName}
              filterOrder={this.props.rankingPageData.filterOrder} deviceType={this.props.deviceType}/> : null }
            {this.state.searchLayerData != false && this.state.searchLayerData.length > 0 ?
                <React.Fragment>
                  <div className="ranking_rslts">{"Showing results for "}<strong>{"\"" +this.searchedString +"\""}</strong></div>
                  <div className="rp-zrp-back"><a href="javascript:void(0);" onClick={this.resetAllFilter.bind(this)}>Clear Search</a></div>
                  <RankingPageTuple rankingPageId={this.props.rankingPageData.rankingPageId} tupleData={this.state.searchLayerData} baseCourse={this.props.rankingPageData.rankingRequestData.appliedFilters.baseCourse[0]} aggregateRatingConfig={this.props.rankingPageData.aggregateRatingConfig} selectedRankingSource={this.props.rankingPageData.selectedRankingSource} isSearchTuple={false} /></React.Fragment> : this.state.searchLayerData != false || this.state.searchLayerData.length == 0 ? <div className="ranking_rslts">{"No result found for "}<strong>{"\"" +this.searchedString +"\""}</strong><div className="rp-zrp-back"><a href="javascript:void(0);" onClick={this.handleZrpClick.bind(this)}><i className="pwa_sprite rp-back-icn"></i>Back to Previous Page</a></div></div> : null}

            {this.state.searchResultData != false && this.state.searchLayerData === false ?
                <React.Fragment>
                  <div className="ranking_rslts"><h4>{this.getSearchResultHeading(this.props.rankingPageData.seoData.heading)}</h4></div>
                  <RankingPageTuple rankingPageId={null} tupleData={this.state.searchResultData} baseCourse={this.props.rankingPageData.rankingRequestData.appliedFilters.baseCourse[0]} aggregateRatingConfig={this.props.rankingPageData.aggregateRatingConfig} selectedRankingSource={this.props.rankingPageData.selectedRankingSource} isSearchTuple={true} /></React.Fragment> : null}

            {this.state.searchLayerData === false ?
                <div className="alter_touple">
                  <RankingPageDataInTable gaTrackingCategory={this.props.gaTrackingCategory}
                                          rankingPageName={this.props.rankingPageData.seoData.heading}
                                          rankingPageId={this.props.rankingPageData.rankingPageId}
                                          tableData={this.props.rankingPageData.rankingInstituteTuple}
                  />

                  <RankingPagination
                      rankingPageData={this.props.rankingPageData}
                      gaTrackingCategory={this.props.gaTrackingCategory}
                      onRankingPageSelect={this.initialFetchData.bind(this)}
                  />
                </div> : null }
            <div className="socialShareRPBtm"><SocialSharingBand widgetPosition={"RP_Bottom"}/></div>
            <RankingLocationWidget
                heading={"Top Ranked "+this.props.rankingPageData.rankingPageName+" Colleges in"}
                layerHeading={"Top " + this.props.rankingPageData.rankingPageName + " Colleges in"}
                data={this.props.rankingPageData.filters.location}
            />
            <div className = 'stickyBanner' id='stickyBanner'>
              <DFPBannerTempalte bannerPlace="sticky_banner" />
            </div>
          </div>
        </React.Fragment>
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

RankingPageMobile.defaultProps = {
  deviceType : 'mobile',
  gaTrackingCategory : 'RANKING_PAGE_MOBILE'
}

export default connect(mapStateToProps, mapDispatchToProps)(RankingPageMobile);

RankingPageMobile.propTypes = {
  clearDfpBannerConfig: PropTypes.any,
  config: PropTypes.any,
  deviceType: PropTypes.string,
  dfpBannerConfig: PropTypes.any,
  fetchRankingPageData: PropTypes.any,
  gaTrackingCategory: PropTypes.string,
  location: PropTypes.any,
  rankingPageData: PropTypes.any
}