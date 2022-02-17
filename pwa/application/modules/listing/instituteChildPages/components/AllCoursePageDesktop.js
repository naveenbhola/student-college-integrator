import PropTypes from 'prop-types'
import React from 'react';
import TopWidget from './../../institute/components/TopWidgetCommon';
import TopWidgetSticky from './../../institute/components/TopWidgetSticky';
import CoursesOffered from './../../institute/components/CoursesOfferedComponent';
import  './../../course/assets/courseCommon.css';
import ReviewWidget from './../../course/components/ReviewWidget';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import {fetchAllCourseData,storeChildPageDataForPreFilled} from './../actions/AllChildPageAction';
import NotFound from './../../../common/components/NotFound';
import {getRequest} from './../../../../utils/ApiCalls';
import ClientSeo from './../../../common/components/ClientSeo';
import PreventScrolling from './../../../reusable/utils/PreventScrolling';
import TagManager  from './../../../reusable/utils/loadGTM';
import {getQueryVariable,getObjectSize,parseQueryString,isEmpty,addingDomainToUrl,resetGNB,getCookie,makeFiltersSticky,makeTopWidgetSticky} from './../../../../utils/commonHelper';
import {getCategoryGTMparams,getBeaconTrackData} from './../../categoryList/utils/categoryUtil';
import ChildPagesInterlinking from './ChildPagesInterlinking';
import CategoryTuple from './../../categoryList/components/CategoryTuple';
import {Link } from 'react-router-dom';
import './../assets/ACPDesktop.css';
import './../../institute/assets/css/colleges.css';
import './../assets/style.css';
import BreadCrumb from './../../../common/components/BreadCrumb';
import './../../institute/assets/css/style.css';
import APIConfig from './../../../../../config/apiConfig';
import {dfpBannerConfig,clearDfpBannerConfig} from './../../../reusable/actions/commonAction';
import {clearReduxData} from './../../../common/actions/commonAction';
import ContentLoaderACPDesktop from "./AllChildPageContentLoader";
import ElasticSearchTracking from './../../../reusable/utils/ElasticSearchTracking';
import DesktopFilters from "../../../search/components/DesktopFilters";
import SelectedFilters from "../../../search/components/SelectedFilter";
import {viewedResponse} from './../../../user/utils/Response';


class AllCoursePageDesktop extends React.Component
{
    constructor(props)
    {
        super(props);
        this.state = {
        filters : {},
        aliasMapping : {},
        defaultAppliedFilters : {},
        filterOrder : {},
        loadMoreCourses:[],
        nameMapping: {},
        selectedFiltersCount: 0,
        totalCourseCount : 0
      };
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
        this.filterCall = false;
        this.isDfpData = false;
    }

    componentDidMount(){
       window.isHeaderFixed = false;
       window.filterCall = true; 
        if(!this.isServerSideRenderedHTML())
        {
            this.initialFetchData(this.props.location,true);
            if(window && typeof window.scroll == 'function'){
              if(!getObjectSize(this.props.childPageData)){
                window.scroll(0,0);
              }
              else{
                if(document.querySelector('.filterColumn') != null && document.querySelector('.pwa_headerv1') != null){
                  window.scroll(0,document.querySelector('.filterColumn').clientHeight+document.querySelector('.pwa_headerv1').clientHeight);
                }
              }
            }
        }
        else{
            if(!this.isErrorPage()){
                this.trackBeacon();
                this.createViewedResponse();
            }
            this.getFiltersData();
            
        }
        if(typeof(document) !='undefined' && document.getElementsByClassName('tupleBrochureButton').length>0){
            let ebLen = document.getElementsByClassName('tupleBrochureButton');
            for(let i=0;i<ebLen.length;i++){
                let courseId = ebLen[i].getAttribute('courseid');
                let ebCookie = 'applied_'+courseId;
                if(getCookie(ebCookie)){
                    document.getElementById('ebTxt'+courseId).innerText = 'Brochure Mailed';
                    ebLen[i].classList.add('ebDisabled');
                }
            }
        }
        this.stickyBooleans = {bottomFixedFlag : false, topFixedFlag : false, footerSeenFlag : false, topIsReachedFlag : false, isTopSetToMax : false,
            isRelativeFlag : false};
        this.lastScrollTop = 0;
        resetGNB();
        this.gnbHeight = document.querySelector('.pwa-headerwrapper')? document.querySelector('.pwa-headerwrapper').clientHeight : 0;
        this.filterBottomFixPos = 0;
        window.addEventListener("scroll", this.onScroll);
        //this.clearReduxContentLoaderData();
        //window.addEventListener('scroll', this.makeFiltersSticky);

        
    }
   onScroll = () => {
      if(!this.isDfpData){
            this.isDfpData = true;
            this.getDFPData();
        }
      this.makeFiltersSticky();
      makeTopWidgetSticky();
    };

    componentWillReceiveProps(nextProps)
    {
        let nextHash = this.getBase64UrlParams(nextProps.location);
        let prevHash = this.getBase64UrlParams(this.props.location);

        if(prevHash != nextHash)
        {
            resetGNB();
            if(window && typeof window.scroll == 'function'){
              window.scroll(0,document.querySelector('.filterColumn').clientHeight+document.querySelector('.pwa_headerv1').clientHeight);
            }
            this.initialFetchData(nextProps.location,true);
            this.stickyBooleans = {bottomFixedFlag : false, topFixedFlag : false, footerSeenFlag : false, topIsReachedFlag : false, isTopSetToMax : false,
                isRelativeFlag : false};
            this.lastScrollTop = 0;
            this.filterBottomFixPos = 0;
        }
    }


    createViewedResponse() {
        let childPageData = this.props.childPageData;
        if(childPageData.flagshipCourseId){
        let flagshipCourseId = childPageData.flagshipCourseId;
            let viewedResponseData = {
                "listingId": flagshipCourseId,
                "trackingKeyId": 2053,
                "actionType": "Institute_Viewed",
                "listingType":"course"
                };
            viewedResponse(viewedResponseData);
        }

    }

    getDFPData(){
        var page='ACP';
        var dfpPostParams = 'parentPage=DFP_ALL_COURSES_PAGE';
        const {childPageData} = this.props;
        var dfpData = new Object();                  
        if(this.props.childPageData != null && typeof this.props.childPageData.requestData != 'undefined' && this.props.childPageData.requestData )
        {            
            const appliedFilters = typeof this.props.childPageData.requestData.appliedFilters != 'undefined' ? this.props.childPageData.requestData.appliedFilters: {};
            var dfpParams = '';
            if(!isEmpty(appliedFilters)){
                
                dfpData['pageType']        = 'homepage';
                dfpData['streams']         = appliedFilters.streams;
                dfpData['substreams']      = appliedFilters.substreams;
                dfpData['baseCourse']      = appliedFilters.baseCourse;
                dfpData['specializations'] = appliedFilters.specializations;
                dfpData['deliveryMethod']  = appliedFilters.deliveryMethod;
                dfpData['educationType']   = appliedFilters.educationType;
                dfpData['city']            = appliedFilters.city;
                dfpData['state']           = appliedFilters.state;
                dfpData['credential']      = appliedFilters.credential;
            }
            if(appliedFilters.streams){
                page='SIP';
                dfpPostParams = 'parentPage=DFP_SIP';
            }
            if(appliedFilters.baseCourse){
                page='BIP';
                dfpPostParams = 'parentPage=DFP_BIP';
            }
            if(page=='ACP' && getObjectSize(childPageData.streamObjects) && getObjectSize(childPageData.baseCourseObjects)){
              dfpData['streams']  ={};
              dfpData['baseCourse']  ={};
              for(let i=0;i<childPageData.streamObjects.length;i++){
                  dfpData['streams'][i] = childPageData.streamObjects[i]['streamId'];
              }
              for(let i=0;i<childPageData.baseCourseObjects.length;i++){
                  dfpData['baseCourse'][i] = childPageData.baseCourseObjects[i]['baseCourseId'];
              }
            }
                dfpParams = JSON.stringify(dfpData);

            dfpPostParams +='&entity_id='+this.props.childPageData.listingId+'&extraPrams='+dfpParams;
        }
        this.props.dfpBannerConfig(dfpPostParams);
    }
     getBase64UrlParams(locationParams)
    {
      if(!PreventScrolling.canUseDOM()){
          return "";
      }
      const paramsObj = this.getUrlParams(locationParams);
      let params = btoa(JSON.stringify(paramsObj));
      return params;
    }

  getUrlParams(locationParams){
      if(!PreventScrolling.canUseDOM()){
        return "";
      }
      let url = locationParams.pathname;
      
      let queryParams = {};//new URLSearchParams(locationParams.search);
          queryParams = parseQueryString(locationParams.search);
      let paramsObj = {};
      for(var key of Object.keys(queryParams))
      {
        let keyArr = key.split(/[[\]]{1,2}/);
        if(keyArr[0] == 'rf')
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

       paramsObj['instituteId'] = this.props.match.params.listingId;
      return paramsObj;
  }

    componentWillUnmount()
    {
        window.isHeaderFixed = true;
        PreventScrolling.enableScrolling(false,true);
        this.props.clearDfpBannerConfig();
        this.props.clearReduxData('allChildPageData');
        window.removeEventListener('scroll', this.onScroll);
    }

    initialFetchData(location , subsequentCall = false)
    {
        let instituteId = this.props.match.params.listingId;
        const paramsObj = this.getUrlParams(location);
        if(isNaN(instituteId))
            return;
       this.fetchAllCourseData(paramsObj,subsequentCall);
    }

     getFiltersData()
    {
      const {location} = this.props;
        this.filterCall = true;

         var params = this.getBase64UrlParams(location);
         const filterAPI = APIConfig.GET_ALL_COURSE_PAGEA_FILTER;
          getRequest(filterAPI+'?data='+params).then((response) => {
            if(response.data.data != null)
            {  

              
              var data = response.data.data;
    
              this.setState({aliasMapping : data.aliasMapping,defaultAppliedFilters : data.defaultAppliedFilters,filterOrder : data.filterOrder,filters: data.filters,nameMapping: data.nameMapping,selectedFiltersCount: data.selectedFiltersCount,totalCourseCount : data.totalCourseCount});
    
              this.trackGTM();
            }
        });
  }

     isServerSideRenderedHTML()
    {
        let htmlNode = document.getElementById('acp_pwa');
        return ((htmlNode && htmlNode.innerHTML) || this.isErrorPage());
    }

    isErrorPage()
    {
        let html404 = document.getElementById('notFound-Page');
        return (html404 && html404.innerHTML);
    }

  trackGTM()
  {
    const {childPageData} = this.props;
    var gtmParams;
    if(childPageData && typeof childPageData.requestData != 'undefined')
    {
        gtmParams = getCategoryGTMparams(childPageData,this.state.aliasMapping,'allCoursesPage');
        TagManager.dataLayer({dataLayer : gtmParams, dataLayerName : 'dataLayer'});
    }
  }
  trackBeacon()
  {
    const {childPageData,config} = this.props;
      var trackingParams;
      if(childPageData && typeof childPageData.requestData != 'undefined')
      {
            let cityId = 0, stateId = 0;
            if(childPageData.currentLocation && childPageData.currentLocation.city_id) {
                cityId = childPageData.currentLocation.city_id, stateId = childPageData.currentLocation.state_id;
            }
            childPageData.requestData['categoryData'] = {id: childPageData.listingId, 'cityId': cityId, 'stateId': stateId};
            trackingParams = {};
            // trackingParams['pageIdentifier'] = 'AllCoursePage_Desktop';
            trackingParams['pageEntityId'] = childPageData.listingId;
            trackingParams['countryId'] = 2;
            trackingParams['extraData'] = {} ;
            trackingParams['extraData']['url'] = addingDomainToUrl(this.props.location.pathname+this.props.location.search,config.SHIKSHA_HOME);
        trackingParams = getBeaconTrackData(childPageData, 'UILP', 'allCoursePage');
        ElasticSearchTracking(trackingParams,config.BEACON_TRACK_URL);
      }
  }


    fetchAllCourseData(paramsObj , subsequentCall = false)    // eslint-disable-line no-unused-vars
    {
        var url  = JSON.stringify(paramsObj);
        var data = Buffer.from(url).toString('base64');
        this.setState({isShowLoader : true});
        var fetchPromise = this.props.fetchAllCourseData(data,'',false);
        var self = this;
        fetchPromise.then(function() {
            self.trackBeacon();
            self.createViewedResponse();
            self.setState({isShowLoader : false});
            if(window.filterCall  || !getObjectSize(self.state.filters))
            {
              window.filterCall = true;
              self.setState({aliasMapping : {},defaultAppliedFilters : {},filterOrder : {},filters: {},loadMoreCourses:[],nameMapping: {},selectedFiltersCount: 0,totalCourseCount : 0});
              self.getFiltersData();
            }
            window.filterCall = true;
            self.getDFPData();
            self.isDfpData = true;
            self.props.storeChildPageDataForPreFilled();
        });
    }

    
    makeFiltersSticky = () => {
        let currScroll = document.documentElement.scrollTop;
        let footerSelector = document.getElementById('footer');
        if(document.querySelector('.aluminiReviewBnr')) {
            footerSelector = document.querySelector('.aluminiReviewBnr');
        }
        this.gnbHeight = (document.querySelector('.pwa-headerwrapper'))? document.querySelector('.pwa-headerwrapper').clientHeight : 0;
        const filterSidebarElement = document.querySelector('.ctp_sidebar');
        const resultsContainer = document.querySelector('.ctpSrp-contnr');
        const filterTopOffset = (filterSidebarElement)?filterSidebarElement.getBoundingClientRect().top + window.pageYOffset : window.pageYOffset ;
        this.filterBottomFixPos = (filterSidebarElement? filterSidebarElement.clientHeight : 0) - document.documentElement.clientHeight + filterTopOffset;
        this.stickyBooleans = makeFiltersSticky(currScroll,filterSidebarElement,resultsContainer,footerSelector,this.stickyBooleans,this.gnbHeight,this.lastScrollTop,this.filterBottomFixPos);
        this.lastScrollTop = currScroll ;
    };

  generateFilters(){


    const pageUrl = this.props.childPageData.allCoursePageUrl;
      return (
      <div>
        <SelectedFilters filtersData = {this.state.filters} shortName={this.state.aliasMapping} pageUrl={pageUrl}/>
        <div className="ctp_sidebar">
        <DesktopFilters gaTrackingCategory={'AllCoursePage_PWA_Desktop'} filters={this.state.filters}
                      displayName={this.state.nameMapping} shortName={this.state.aliasMapping}
                      defaultAppliedFilters={this.state.defaultAppliedFilters} filterOrder={this.state.filterOrder}
                      selectedFiltersCount={this.state.selectedFiltersCount} pageUrl={pageUrl} isAllCoursesPage={true}/>
        </div>
    </div>);
    
  }

  prepareBreadCrumbData(){
    if(this.props.childPageData.breadCrumb == null || this.props.childPageData.breadCrumb == ''){
      return
    }
    this.props.childPageData.breadCrumb.forEach((value)=>{
      value.isAbsoluteUrl = false
      if(value.name == 'Home' || (value.url && (value.url.indexOf('/university/') !=-1 || value.url.indexOf('/college/') !=-1 ))){
        value.isAbsoluteUrl = true;
      }
    });
  }

  showLoader(){
      const {contentLoaderData,childPageData,config} = this.props;
      let extraHeading = 'Courses, Fees 2019';  
      let bipSipId = '';
      if(contentLoaderData){
          if(contentLoaderData.PageHeading){
              extraHeading = contentLoaderData.PageHeading;
          }
          if(contentLoaderData.bipSipId){
            bipSipId = contentLoaderData.bipSipId;
          }

      }
      if(!getObjectSize(childPageData)){
        return (
              <div className="pwa_pagecontent">
                  <div className="pwa_container">
                    <section className="ctp_container">
                        <div className="acp_block">
                         {<ContentLoaderACPDesktop fullLoader={true} />}
                        </div>
                    </section>
                  </div>
              </div>          

          )
      }
      else if((typeof window != 'undefined' && window.filterCall)|| !getObjectSize(this.state.filters)){
          return(
                <div className="pwa_pagecontent">
                    <div className="pwa_container">
                          {childPageData.breadCrumb && <BreadCrumb breadCrumb={childPageData.breadCrumb} /> }
                          {childPageData && childPageData.instituteTopCardData && <TopWidget showChangeBranch={false} instituteId={childPageData.listingId} data={childPageData} config={config} location = {this.props.location} page ={this.props.childPageData.listingType} extraHeading ={extraHeading} allCoursePage={true} fromWhere= "allCoursePage" gaTrackingCategory={ 'AllCoursePage_PWA'} contentLoader={true}/>}
                            {(childPageData && bipSipId) ? this.generateBipSipHtml(bipSipId)
                              :<ContentLoaderACPDesktop onlyBipSip={true} />  
                            }
                            <ContentLoaderACPDesktop filter={true} tuple={true}  />
                    </div>
                </div>          
          );
      }else{
        return(
              <div className="pwa_pagecontent">
                  <div className="ctp_pwa">
                    <section className="ctp_container">
                        <div className="ctp_block">
                        {childPageData.breadCrumb && <BreadCrumb breadCrumb={childPageData.breadCrumb} /> }
                        {childPageData && childPageData.instituteTopCardData && <TopWidget showChangeBranch={false} instituteId={childPageData.listingId} data={childPageData} config={config} location = {this.props.location} page ={this.props.childPageData.listingType} extraHeading ={extraHeading} allCoursePage={true} fromWhere= "allCoursePage" gaTrackingCategory={ 'AllCoursePage_PWA'} contentLoader={true}/>}
                          {childPageData && this.generateBipSipHtml(bipSipId)}
                          <section className="pwa_container">
                              <div className="acp_block">
                                  {this.generateFilters()}
                                  <ContentLoaderACPDesktop filter={false} tuple={true}  />
                              </div>
                          </section>

                        </div>
                    </section>
                  </div>
              </div>          
        );
      }
  }
  generateBipSipHtml(bipSipId=null){
    var BipSipHtml =[];
    const {childPageData,config} = this.props;
    var scriptedText = (childPageData.seoData)?childPageData.seoData.scriptedText:'';
    var canonicalUrl = (childPageData.seoData)?childPageData.seoData.canonicalUrl:this.props.childPageData.seoUrl;
    var allCoursePageUrl = (childPageData.allCoursePageUrl)?childPageData.allCoursePageUrl:'';
    var BipSiptoActive = [];
    var flagForReset = false;
    BipSiptoActive["baseCourses"] = [];
    for(let i in childPageData.requestData.appliedFilters.baseCourse){
      BipSiptoActive["baseCourses"][i] = childPageData.requestData.appliedFilters.baseCourse[i];
      flagForReset = true;
    }
    BipSiptoActive["streams"] = [];
    for(let i in childPageData.requestData.appliedFilters.streams){
      BipSiptoActive["streams"][i] = childPageData.requestData.appliedFilters.streams[i];
      flagForReset = true;
    }
    if(typeof bipSipId != 'undefined' && bipSipId != null && bipSipId){
       bipSipId = bipSipId.split('_');
       if(bipSipId[0] == 'b'){
          BipSiptoActive["baseCourses"][0]=  parseInt(bipSipId[1]);
          BipSiptoActive["streams"] = [];

       }
       if(bipSipId[0] == 's'){
          BipSiptoActive["streams"][0]=  parseInt(bipSipId[1]);
          BipSiptoActive["baseCourses"]=  [];
       }
    }

    BipSipHtml.push( <div id="bip" key="bip_sip">
                            <div className="bip">
                              <section>
                                <div className="ctpn_container">
                                  <div className="ctpn-filter-head">
                                    <div className="ctpn-filter-sec">
                                      <div className="filterColumn">
                                        <h2 className="filter_rslt">Showing {childPageData.totalCourseCount} Courses</h2>
                                        <input type="checkbox" className='read-more-state hide' id={"readMr-evnt_scriptedText"}/>
                                        <h2 className='filteralt_txt read-more-wrap word-break'>{scriptedText}</h2>
                                        {flagForReset && <Link className='reset-link' to={childPageData.allCoursePageUrl} ><i className='reset-ico'></i> Reset </Link>}
                                      </div>
                                    {childPageData.totalCourseCount != null && <CoursesOffered childPageData = {childPageData} instituteData = {childPageData} page = {childPageData.listingType} config={config} location={childPageData.currentLocation} fromwhere= "allCoursePage" canonicalUrl = {canonicalUrl}  allCoursePageUrl = {allCoursePageUrl} paramsObj = {BipSiptoActive} flagForReset={flagForReset}/>}
                                    </div>
                                  </div>

                                </div>
                              </section>
                            </div>
                          </div> );
    return BipSipHtml;
  }

    render()
    {
        const {childPageData,config,location} = this.props;
        let nextHashValue = this.getBase64UrlParams(location);
        this.prepareBreadCrumbData();
        if(childPageData && typeof childPageData.statusCode != 'undefined' && (childPageData.statusCode == 400 || childPageData.statusCode == 500 || childPageData.statusCode == 404)) 
        {
            resetGNB();
            return <NotFound />;
        }
        if(this.state.isShowLoader)
        {
            return this.showLoader();
        }
        else if(childPageData == null || Object.keys(childPageData).length == 0)
        {
            return this.showLoader();
        }
        else if(typeof childPageData.hashUrl != 'undefined' && typeof nextHashValue != 'undefined' && nextHashValue != "" && childPageData.hashUrl != nextHashValue)
        {
            return this.showLoader();
        }
        else if(childPageData.pageType !='AllCoursePage'){
            return this.showLoader();

        }
        let seoData = (childPageData && childPageData.seoData) ? childPageData.seoData : '';
        return (
            <React.Fragment>
                {ClientSeo(seoData)}
                {childPageData.instituteTopCardData && <TopWidgetSticky showChangeBranch={false} instituteId={childPageData.listingId} data={childPageData} config={config} location = {this.props.location} page ={this.props.childPageData.listingType} extraHeading ='Courses, Fees Structure 2019' allCoursePage={true} fromWhere= "allCoursePage" gaTrackingCategory={ 'AllCoursePage_PWA_Desktop'} contentLoader={false} isDesktop={true} pageType={"AllCoursesPage"}/>}
                <div className="pwa_pagecontent">
                    <div className="pwa_container" id="acp_pwa">
                    {childPageData.breadCrumb && <BreadCrumb breadCrumb={childPageData.breadCrumb} /> }
                    
                  {childPageData.instituteTopCardData && <TopWidget showChangeBranch={false} instituteId={childPageData.listingId} data={childPageData} config={config} location = {this.props.location} page ={this.props.childPageData.listingType} extraHeading ='Courses, Fees Structure 2019' allCoursePage={true} fromWhere= "allCoursePage" gaTrackingCategory={ 'AllCoursePage_PWA_Desktop'} contentLoader={false} isDesktop={true} pageType={"AllCoursesPage"}/>}
                      {this.generateBipSipHtml()}
                        <div className="pwa_container">
                            <div className="acp_block">
                                {this.generateFilters()}
                                <div className="acp_rightsidebar">
                                    <CategoryTuple showOAF={true} isPdfCall={true} showInTable = {false} config={config} aggregateRatingConfig={this.props.childPageData.aggregateRatingConfig}  categoryData={this.props.childPageData} gaTrackingCategory={ 'AllCoursePage_Desktop'}  showPagination={true} onlycoursTuple={true} allCoursePage={true} fromwhere= "allCoursePage" srtTrackId={2453} ebTrackid={2451} pageNumber={this.props.childPageData.paginationData.currentPageNUmber} deviceType="desktop" pageType={"AllCoursesPage"} applyNowTrackId={2099}/>
                                </div>
                                {/*right side tuples ends*/}
                            <div className="college_reviews">
                                {childPageData.reviewWidget != null && childPageData.reviewWidget.reviewData != null && childPageData.reviewWidget.reviewData.reviewsData &&
                                    <ReviewWidget
                                    reviewWidgetData={childPageData.reviewWidget}
                                    config={config}  gaTrackingCategory={ 'AllCoursePage_Desktop'} deviceType='desktop' />}
                            </div>
                            </div>
                        </div>
                        {<ChildPagesInterlinking data={childPageData} gaCategory='AllCoursePage_Desktop' fromWhere= 'allCoursePage' deviceType="desktop" /> }  
                        {/*main page ends*/}
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
    return bindActionCreators({fetchAllCourseData,storeChildPageDataForPreFilled,dfpBannerConfig,clearDfpBannerConfig,clearReduxData}, dispatch);
}

export default connect(mapStateToProps,mapDispatchToProps)(AllCoursePageDesktop);

AllCoursePageDesktop.propTypes = {
  childPageData: PropTypes.any,
  clearDfpBannerConfig: PropTypes.any,
  clearReduxData: PropTypes.any,
  config: PropTypes.any,
  contentLoaderData: PropTypes.any,
  dfpBannerConfig: PropTypes.any,
  fetchAllCourseData: PropTypes.any,
  location: PropTypes.any,
  match: PropTypes.any,
  storeChildPageDataForPreFilled: PropTypes.any
}