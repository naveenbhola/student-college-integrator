import PropTypes from 'prop-types'
import React from 'react';
import { connect } from 'react-redux';
import APIConfig from './../../../../../config/apiConfig';
import Filters from '../../categoryList/components/FiltersComponent';
import ReviewWidget from './../../course/components/ReviewWidget';
import ChildPagesInterlinking from './ChildPagesInterlinking';
import TopWidget from './../../institute/components/TopWidgetCommon';
import AnA from './../../course/components/AnAComponent';
import NotFound from './../../../common/components/NotFound';
import  { Redirect } from 'react-router-dom';
import {addingDomainToUrl,getObjectSize,PageLoadToastMsg,showToastMsg,isUserLoggedIn,parseQueryString,getQueryVariable} from './../../../../utils/commonHelper';
import {contentLoaderHelper} from './../../../../utils/ContentLoaderHelper';
import {Helmet} from 'react-helmet';
import TagManager  from './../../../reusable/utils/loadGTM';
import {fetchCutOffPageData,storeChildPageDataForPreFilled} from './../actions/AllChildPageAction';
import {makeShikshaHeaderSticky,stickyNavTabAndCTA} from './../../../reusable/utils/commonUtil';
import {storeInstituteDataForPreFilled} from './../../institute/actions/InstituteDetailAction';
import ContentLoaderMain from './../utils/ContentLoaderMain';
import PreventScrolling from './../../../reusable/utils/PreventScrolling';
import { bindActionCreators } from 'redux';
import SectionalNav from './../../course/components/SectionalNavWidget';
import {dfpBannerConfig,clearDfpBannerConfig} from './../../../reusable/actions/commonAction';
import {DFPBannerTempalte} from './../../../reusable/components/DFPBannerAd';
import ElasticSearchTracking from './../../../reusable/utils/ElasticSearchTracking';
import './../assets/style.css';
import {viewedResponse} from './../../../user/utils/Response';
import Loadable from 'react-loadable';
import './../../institute/assets/css/style.css';
import CutoffWidgetWrapper from './CutoffWidgetWrapper';
import EditorialContentComponent from './EditorialContentComponent';
import ChildPagesInlineWidget from './ChildPagesInlineWidget';
import LinkOCF from './LinkOCF';

class CutOffPage extends React.Component
{
  constructor(props)
    {
      super(props);
      this.state = {
          filters: {},
          aliasMapping: {},
          defaultAppliedFilters: {},
          filterOrder: {},
          nameMapping: {},
          selectedFiltersCount: 0,
          requestData : {},
          OcfDownloaded:false,
          selectedTab:"exam"
      }
      this.params = {
                 listingAppBannerHeight : '0',
                 listingScrollflag : false,
                 listingRemovePaddingflag : false,
                 listingScrollUpDownflag : false,
                 st : 0,
                 shikshaBannerHeight : 0,
                 hashValue : null,
                 nextHashValue : null
               };
    this.isDfpData = false;
    this.courseSpecificData = false;
    this.showToastMsg = true;
    this._scrollCount = true;
    this.filterCall = false;
    this.filterTop = 0;
    this.footerPos = 0;

    }


    onScroll = () => {
        if (!this.state.filtersData && !this.filterCall) {
            this.getFiltersData();
        }

        if(!this.isDfpData){
            this.isDfpData = true;
            this.getDFPData();
        }
        if(this._scrollCount){
            this._scrollCount = false;
        }
    };

    scrollStop() {

      // Setup scrolling variable
      var isScrolling;
      var self = this;
      // Listen for scroll events
      window.scroll = null;
      window.onscroll = function () {
        stickyNavTabAndCTA();
        window.clearTimeout( isScrolling );
          self.scrollFinished();
      }
    }

    contentLoaderData = (position) => {
      let dataObj = [];
      dataObj.scrollPosition = position;
      const {childPageData} = this.props;
      let pageUrl = childPageData.listingUrl + '/cutoff';
      this.props.storeChildPageDataForPreFilled(contentLoaderHelper(childPageData,dataObj,"CutOff",pageUrl));
    };
    
    scrollFinished()
    {
        makeShikshaHeaderSticky(this.params);
    }

    trackGTM()
    {
        const {childPageData,config} = this.props;
        var trackingParams={};

        var beaconTrackData = {};
        if(typeof childPageData != 'undefined' && childPageData)
        {
              trackingParams.gtmParams={};
              trackingParams.gtmParams['pageType'] = 'cutoffPage';
              trackingParams.gtmParams['countryId'] = 2;
              trackingParams.gtmParams['listingType'] = childPageData.listingType;

            beaconTrackData['pageIdentifier'] ='cutoffPage';
            beaconTrackData['pageEntityId'] =childPageData.listingId;
            beaconTrackData['extraData']={};
            beaconTrackData['extraData']['listingType'] = childPageData.listingType;
            beaconTrackData['extraData']['countryId'] = 2;
            beaconTrackData['extraData']['url'] = addingDomainToUrl(childPageData.seoUrl,config.SHIKSHA_HOME);

            TagManager.dataLayer({dataLayer : trackingParams.gtmParams, dataLayerName : 'dataLayer'});
            ElasticSearchTracking(beaconTrackData,config.BEACON_TRACK_URL);
        }
    }


    getDFPData(){
        const {childPageData} = this.props;
        var dfpPostParams = 'parentPage=DFP_InstituteDetailPage';
         if(childPageData &&  childPageData.listingType){
          dfpPostParams += '&pageType='+childPageData.listingType+'_admission';
        }
        if(childPageData != null && typeof childPageData != 'undefined' && typeof childPageData.currentLocation != 'undefined' && childPageData.currentLocation)
        {
            dfpPostParams +='&city=' +childPageData.currentLocation.city_id+'&state=' +childPageData.currentLocation.state_id+'&entity_id='+this.props.match.params.listingId;
        }
        this.props.dfpBannerConfig(dfpPostParams);
    }


   
   updateState(data,showLoader=false){
      this.setState({
          isShowLoader: showLoader,
          aliasMapping: data.aliasMapping,
          defaultAppliedFilters: data.defaultAppliedFilters,
          filterOrder: data.filterOrder,
          filters: data.filters,
          nameMapping: data.nameMapping,
          selectedFiltersCount: data.selectedFiltersCount,
          requestData: data.requestData,
          totalCourseCount: data.totalCourseCount
        }
        );
   } 

   createViewedResponse() {
       let childPageData = this.props.childPageData;
       if(childPageData.flagshipCourseId){
       let flagshipCourseId = childPageData.flagshipCourseId;
           let viewedResponseData = {
               "listingId": flagshipCourseId,
               "trackingKeyId": 3685,
               "actionType": "MOB_Institute_Viewed",
               "listingType":"course"
               };
           viewedResponse(viewedResponseData);
       }

   }
  
  componentDidMount(){
        if(!this.isServerSideRenderedHTML()){
            this.initialFetchData(this.props.location,this.props.match.params);
            if(window && typeof window.scrollTo == 'function'){
              if(!getObjectSize(this.props.childPageData) || this.props.childPageData.pageType !='cutoff'){
                window.scrollTo(0,0);
              }
              else{
                if(document.getElementById('tab-section') && document.querySelector('.pwa_headerv1') && document.getElementById('Overview') ){
                  window.scrollTo(0,document.getElementById('tab-section').clientHeight+document.querySelector('.pwa_headerv1').clientHeight + document.getElementById('Overview').clientHeight);
                }
              }
            }
        }
        else{
            if(!this.isErrorPage())
            {
              this.lastScrollTop = window.scrollY;
              // stickyNavTabAndCTA();
              this.scrollStop();    
              this.trackGTM();
              this.createViewedResponse();
              if(typeof(isUserLoggedIn) != 'undefined' && isUserLoggedIn() && this.props.childPageData && this.props.childPageData.showToastMessage && this.showToastMsg){
                  this.showToastMsg = false;
                  setTimeout(function(){
                      showToastMsg(PageLoadToastMsg('SRM'),5000);
                  },3000);
              }
            }
        }
        this.filterCall = true;
        this.updateState(this.props.childPageData,false)
        if(typeof(isUserLoggedIn) != 'undefined' && isUserLoggedIn()){
            this._scrollCount = false;
        }
        window.addEventListener("scroll", this.onScroll);
        this.props.clearDfpBannerConfig();  
        //this.props.storeChildPageDataForPreFilled();      

    }

    onClickViewAll(){
      this.setState({selectedTab:'institute'},() =>{
        if(document && document.getElementById('filters_mobile')){
          document.getElementById('filters_mobile').click()
        }
      });
    };

    componentWillUnmount()
    {
        window.scroll = null;
        PreventScrolling.enableScrolling(false,true);
        if(PreventScrolling.canUseDOM())
        {
            document.getElementById('page-header').style.display = "table";
            document.getElementById('page-header').style.position = "relative";
        }
        this.props.clearDfpBannerConfig();
       // =this.props.storechildPageDataForPreFilled();
        window.removeEventListener('scroll', this.onScroll);
            
    }

    isErrorPage()
    {
        let html404 = document.getElementById('notFound-Page');
        return (html404 && html404.innerHTML);
    }

    isServerSideRenderedHTML()
    {
        let htmlNode = document.getElementById('PP');
        return ((htmlNode && htmlNode.innerHTML) || this.isErrorPage());
    }
    
    componentWillReceiveProps(nextProps)
    {
      let nextHash = this.getBase64UrlParams(nextProps.location,nextProps.match.params);
      let prevHash = this.getBase64UrlParams(this.props.location,this.props.match.params);
        if(PreventScrolling.canUseDOM()) {
             document.getElementById('page-header').style.display = "block";
             document.getElementById('page-header').style.position = "relative";
        }

        if(prevHash != nextHash)
        {
            if(window && typeof window.scrollTo == 'function'){
              if(this.props.match.params.listingId != nextProps.match.params.listingId){
                window.scrollTo(0,0);
              }
              else if(document.querySelector('.pwa_headerv1') && document.getElementById('Overview')  ){
                let position = 0;
                position = document.querySelector('.pwa_headerv1').clientHeight + document.getElementById('Overview').clientHeight;
                if(document.getElementById('tab-section')){
                  position += document.getElementById('tab-section').clientHeight;
                }

                window.scrollTo(0,position);
              }
            }
            this.initialFetchData(nextProps.location,nextProps.match.params);
        }  
    }

   getBase64UrlParams(locationParams,urlParams)
     {
       if(!PreventScrolling.canUseDOM()){
           return "";
       }
       const paramsObj = this.getUrlParams(locationParams,urlParams);
       let params = btoa(JSON.stringify(paramsObj));
       return params;
     }

   getUrlParams(locationParams,urlParams){
       if(!PreventScrolling.canUseDOM()){
         return "";
       }
       let url = locationParams.pathname;
       
       let queryParams = {};//new URLSearchParams(locationParams.search);
           queryParams = parseQueryString(locationParams.search);
       let paramsObj = {};
       if(urlParams && urlParams['3']!=''){
         paramsObj['examName'] = urlParams['3'].slice(1);
       }

       for(var key of Object.keys(queryParams))
       {
         let keyArr = key.split(/[[\]]{1,2}/);
         if(keyArr[0] == 'courseId'){
           paramsObj['boostCourseId'] = getQueryVariable(keyArr[0], locationParams.search);//queryParams.getAll(key)[0];
         }
         else if(keyArr[0] == 'rf')
         {
           paramsObj[keyArr[0]] = getQueryVariable(keyArr[0], locationParams.search);//queryParams.getAll(key)[0];
         }
         else if(keyArr[0] != '')
         {
           paramsObj[keyArr[0]] = getQueryVariable(keyArr[0], locationParams.search);//queryParams.getAll(key);  
         }

       }
       let length = url.length;
       let lastCharUrl = url[length-1];
       if(!isNaN(lastCharUrl)){
               var urlArray = url.split('-');
               paramsObj['pn'] = parseInt(urlArray[urlArray.length-1]); 
       }  
         paramsObj['url'] = url;
         paramsObj['instituteId'] = urlParams.listingId;
       return paramsObj;
   }

    initialFetchData(location,urlParams)
    {
       let instituteId = this.props.match.params.listingId;
       const paramsObj = this.getUrlParams(location,urlParams);
       if(isNaN(instituteId))
           return;
      this.fetchCutOffData(paramsObj);
    }

    fetchCutOffData(paramsObj)
    {
        var reqString  = JSON.stringify(paramsObj);
        var queryParams = Buffer.from(reqString).toString('base64');
        this.setState({isShowLoader : true});
        var fetchPromise = this.props.fetchCutOffPageData(queryParams);
        var self = this;
        this.showToastMsg = true;
        fetchPromise.then(function() {
          self.getDFPData();
          self.isDfpData = true;
          self.trackGTM();
          self.lastScrollTop = window.scrollY;
          stickyNavTabAndCTA();
          self.scrollStop();
          self.createViewedResponse();
          self.setState({
              isShowLoader: false,
              aliasMapping: self.props.childPageData.aliasMapping,
              defaultAppliedFilters: self.props.childPageData.defaultAppliedFilters,
              filterOrder: self.props.childPageData.filterOrder,
              filters: self.props.childPageData.filters,
              nameMapping: self.props.childPageData.nameMapping,
              selectedFiltersCount: self.props.childPageData.selectedFiltersCount,
              requestData: self.props.childPageData.requestData,
              totalCourseCount: self.props.childPageData.totalCourseCount
            }
            );
          self.props.storeChildPageDataForPreFilled();   
          self.props.storeInstituteDataForPreFilled(); 
        });
    }

    getSelectedTab(data){
      for (var i = 0; i < data.filterOrder.length; i++) {
        if(data.filters[data.filterOrder[i]]){
          this.setState({selectedTab:data.filterOrder[i]});
          return;
        }
      }
    }

    generateFilters() {
        const {childPageData} = this.props;
        const pageUrl = childPageData.seoUrl;
        let showReset = true;
        let showFilters = true;
        if(!childPageData.filters || (childPageData.filters && !childPageData.filters.exam && !childPageData.filters.institute && !childPageData.filters.specialization && !childPageData.filters.category)){
          showFilters = false;
        }
        if(this.props.location.pathname === pageUrl && this.props.location.search == ''){
          showReset = false;
        }
        if(!childPageData.filters[this.state.selectedTab]){
          this.getSelectedTab(childPageData);
        }
        return (showFilters  && <Filters showTCF = {false} showReset={showReset} filters={this.state.filters} displayName={this.state.nameMapping} shortName={this.state.aliasMapping} pageUrl={pageUrl} defaultAppliedFilters={this.state.defaultAppliedFilters} gaTrackingCategory={"Cutoff_Page_Mobile"} filterOrder={this.state.filterOrder} selectedFiltersCount={this.state.selectedFiltersCount} requestData={this.state.requestData} isSrp = {false} isCutOffPage={true} instituteId={this.props.match.params.listingId} hasFilterData={this.filterCall} onFilterButtonPress={this.onScroll} contentLoaderData={this.contentLoaderData} APIUrl={APIConfig.GET_CUTOFF_PAGE_DATA} withUAF={false} withInstituteId={true} filtersWithSearch={['exam','specialization','category','institute']}
          selectedTab={this.state.selectedTab}/>);
    }

    getFiltersData() {
        const {childPageData} = this.props;
        if (typeof childPageData !== 'undefined' && typeof childPageData.tupleData !== 'undefined' &&
            childPageData.tupleData.length > 0) {
            this.filterCall = true;
            const {location} = this.props;
            const params = this.getBase64UrlParams(location,this.props.match.params);
            this.fetchCutOffData(params);
        }
    }


  renderLoader() {

      const {contentLoaderData} = this.props;
      let position = 0;
      if(contentLoaderData && contentLoaderData.scrollPosition != -1){
          position = contentLoaderData.scrollPosition;
      }
      else if(document.getElementById('tab-section') && document.querySelector('.pwa_headerv1') && document.getElementById('Overview') ){
          position = document.getElementById('tab-section').clientHeight+document.querySelector('.pwa_headerv1').clientHeight + document.getElementById('Overview').clientHeight
      }   
      PreventScrolling.enableScrolling(false,true,position);
      return <ContentLoaderMain deviceType="mobile"/>;
    }  

  render(){
      const {childPageData,config,location} = this.props;
      let nextHashValue = this.getBase64UrlParams(location,this.props.match.params);
      let showOCF = false;
      if(childPageData.filters && (childPageData.filters.institute || childPageData.filters.exam) ){
        showOCF = true;
      }
      if(this.state.isShowLoader){
        return (
            <React.Fragment>
                {this.renderLoader()}
            </React.Fragment>
            );
      }
      if(isNaN(this.props.match.params.listingId) || (childPageData && typeof childPageData.statusCode != 'undefined' && childPageData.statusCode == 404) || childPageData.listingId === 0 ){
        return <NotFound />;
      }
      else if(childPageData == null || Object.keys(childPageData).length == 0){
        return (
            <React.Fragment>
                {this.renderLoader()}
            </React.Fragment>
            );
      }
       else if(childPageData.listingId && childPageData.listingId != this.props.match.params.listingId){
         return (
             <React.Fragment>
                 {this.renderLoader()}
             </React.Fragment>
         );
       }
      else if(childPageData.statusCode == 301 || childPageData.statusCode == 302){
        <Redirect to={childPageData.seoUrl}/>
      }
      else if(typeof childPageData.hashUrl != 'undefined' && typeof nextHashValue != 'undefined' && nextHashValue != "" && childPageData.hashUrl != nextHashValue)
      {
          return (
              <React.Fragment>
                  {this.renderLoader()}
              </React.Fragment>
              );
      }
      else if(childPageData.pageType !='Cutoff'){
          return(
              <React.Fragment>
                  {this.renderLoader()}
              </React.Fragment>
            );
      }

      let pageUrl = childPageData.listingUrl + '/cutoff';
      let paramsObj = this.getBase64UrlParams(location,this.props.match.params);
      return(
            <React.Fragment>
            {(childPageData.seoData && childPageData.seoData.metaTitle) && <Helmet>
                            <title> {childPageData.seoData.metaTitle} </title>
                        </Helmet>}
              <div className="ilp courseChildPage pwa_admission" id="PP">
                  <div id="fixed-card" className="nav-tabs display-none">
                    {this.generateFilters()}
                  </div>
                  {childPageData.instituteTopCardData && <TopWidget showChangeBranch={false} instituteId={childPageData.listingId} data={childPageData} config={config} location = {this.props.location} page = {childPageData.listingType} extraHeading ='- Placements' allCoursePage={true} fromWhere= "cutOffPage" gaTrackingCategory={'Cutoff_Page_Mobile'} />}
                  {childPageData.aboutCollege && <EditorialContentComponent data={childPageData.aboutCollege} readMoreCount={450} gaCategory='Cutoff_Page_mobile' deviceType='mobile'/>}  
                  <div id="tab-section" className="cutoff_filters_tab">
                    {this.generateFilters()}
                  </div>
                  {showOCF && <div className="_padaround white--bg">
                    <LinkOCF location={location} collegeOCF={true} examOCF={true} pageUrl={pageUrl} childPageData={childPageData} gaCategory={'Cutoff_Page_Mobile'} onClickViewAll={this.onClickViewAll.bind(this)}/>
                  </div>}
                  <CutoffWidgetWrapper 
                    data={childPageData.courseTuples} 
                    listingId={childPageData.listingId}
                     listingName ={childPageData.listingName}
                     listingType = {childPageData.listingType}
                     device='mobile'
                     trackingKey = {3677}
                     pdfUrl ={childPageData.cutoffCtaPdfUrl}
                     shortlistTrackingKey = {3669}
                     ebTrackingKey = {3673}
                     gaTrackingCategory={"Cutoff_Page_Mobile"}
                     ViewMoreCount = {6}
                     contentLoaderData={this.contentLoaderData}
                    paramsObj = {paramsObj}
                    courseTupleCount = {childPageData.courseTupleCount}
                    courseTupleLimit = {childPageData.courseTupleLimit}
                     pageUrl ={childPageData.seoUrl}
                    childPageData ={childPageData}
                  />
                  
                  {childPageData.reviewWidget != null && childPageData.reviewWidget.reviewData != null && childPageData.reviewWidget.reviewData.reviewsData &&
                        <ReviewWidget
                        reviewWidgetData={childPageData.reviewWidget}
                        config={config} aggregateReviewWidgetData={childPageData.aggregateReviewWidget}  gaTrackingCategory={ 'Cutoff_Page_Mobile'} />}
                  <AnA anaWidget={childPageData.anaWidget} config={config} courseId={childPageData.flagshipCourseId} instituteId={childPageData.listingId} page = {childPageData.listingType} location = {childPageData.currentLocation} fromWhere = "cutOffPage" gaTrackingCategory={'Cutoff_Page_Mobile'}/>      

                  <DFPBannerTempalte bannerPlace="UILPX_LAA"/>
                  <DFPBannerTempalte bannerPlace="UILPX_LAA1"/>
                  {<ChildPagesInterlinking data={childPageData} gaCategory='Cutoff_Page_Mobile' fromWhere= "cutoffPage" similarPlacement={true}/> }      
                  {<ChildPagesInterlinking data={childPageData} gaCategory='Cutoff_Page_Mobile' fromWhere= "cutoffPage" /> }      
                  
                  <div className = 'stickyBanner' id='stickyBanner'>    
                      <DFPBannerTempalte bannerPlace="sticky_banner" />
                  </div>
              </div>     
            </React.Fragment>
        )
  }

}
function mapStateToProps(state)
{
    return {
      childPageData : state.childPageData,
      config : state.config,
      contentLoaderData : state.contentLoaderData
    }
}
function mapDispatchToProps(dispatch){
    return bindActionCreators({fetchCutOffPageData,storeChildPageDataForPreFilled,storeInstituteDataForPreFilled,dfpBannerConfig,clearDfpBannerConfig }, dispatch);
}
export default connect(mapStateToProps,mapDispatchToProps)(CutOffPage);

CutOffPage.propTypes = {
  childPageData: PropTypes.any,
  clearDfpBannerConfig: PropTypes.any,
  config: PropTypes.any,
  dfpBannerConfig: PropTypes.any,
  fetchCutOffPageData: PropTypes.any,
  location: PropTypes.any,
  match: PropTypes.any,
  storeChildPageDataForPreFilled: PropTypes.any,
  storeInstituteDataForPreFilled: PropTypes.any
}