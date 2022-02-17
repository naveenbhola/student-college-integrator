import PropTypes from 'prop-types'
import { Link } from "react-router-dom";
import React from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import {fetchAllCourseData,storeChildPageDataForPreFilled} from '../instituteChildPages/actions/AllChildPageAction';
import TopWidget from './../institute/components/TopWidgetCommon';
import  './../course/assets/courseCommon.css';
import CoursesOffered from './../institute/components/CoursesOfferedComponent';
import NotFound from './../../common/components/NotFound';
import ReviewWidget from './../course/components/ReviewWidget';
import {getRequest} from './../../../utils/ApiCalls';
import ClientSeo from './../../common/components/ClientSeo';
import PreventScrolling from './../../reusable/utils/PreventScrolling';
import TagManager  from './../../reusable/utils/loadGTM';
import {makeShikshaHeaderSticky,stickyNavTabAndCTA} from './../../reusable/utils/commonUtil';
import {getQueryVariable,getObjectSize,isUserLoggedIn,parseQueryString,isEmpty,addingDomainToUrl,PageLoadToastMsg,showToastMsg} from './../../../utils/commonHelper';
import {getCategoryGTMparams,getBeaconTrackData} from './../categoryList/utils/categoryUtil';
import CategoryTuple from './../categoryList/components/CategoryTuple';
import Filters from './../categoryList/components/FiltersComponent';
import './../categoryList/assets/categoryTuple.css';
import './../instituteChildPages/assets/style.css';
import './../institute/assets/css/colleges.css';
import './../institute/assets/css/style.css';
import APIConfig from './../../../../config/apiConfig';
import {dfpBannerConfig,clearDfpBannerConfig} from './../../reusable/actions/commonAction';
import ElasticSearchTracking from './../../reusable/utils/ElasticSearchTracking';
import {viewedResponse} from './../../user/utils/Response';

class AllCoursePdf extends React.Component
{
    constructor(props)
    {
        super(props);
        this.state = {
        isShowLoader : false,
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
        this.showToastMsg = true;
    }

    componentDidMount(){
        if(!this.isServerSideRenderedHTML())
        {
            this.initialFetchData(this.props.location,true);
        }
        else{
            if(!this.isErrorPage()){
                this.lastScrollTop = window.scrollY;
                stickyNavTabAndCTA();
                this.scrollStop();    
                this.trackBeacon();
                this.createViewedResponse();
                if(typeof(isUserLoggedIn) != 'undefined' && isUserLoggedIn() && this.props.childPageData && this.props.childPageData.showToastMessage && this.showToastMsg){
                    this.showToastMsg = false;
                    setTimeout(function(){
                        showToastMsg(PageLoadToastMsg('SRM'),5000);
                    },3000);
                }
            }
            window.addEventListener("scroll", this.onScroll);
        }

        
    }
     onScroll = () => {
        if(!this.state.filtersData && !this.filterCall) {
          this.getFiltersData();
        }
        if(!this.isDfpData){
            this.isDfpData = true;
            this.getDFPData();
        }
      };

    componentWillReceiveProps(nextProps)
    {
        let nextHash = this.getBase64UrlParams(nextProps.location);
        let prevHash = this.getBase64UrlParams(this.props.location);

        if(prevHash != nextHash)
        {
            this.initialFetchData(nextProps.location,true);
        }
    }

    createViewedResponse() {
        let childPageData = this.props.childPageData;
        if(childPageData.flagshipCourseId){
        let flagshipCourseId = childPageData.flagshipCourseId;
            let viewedResponseData = {
                "listingId": flagshipCourseId,
                "trackingKeyId": 2055,
                "actionType": "MOB_Institute_Viewed",
                "listingType":"course"
                };
            viewedResponse(viewedResponseData);
        }

    }

    getDFPData(){
            var page = 'ACP';
            var dfpPostParams = 'parentPage=DFP_ALL_COURSES_PAGE';
            var {childPageData} = this.props;
            if(this.props.childPageData != null && typeof this.props.childPageData.requestData != 'undefined')
            {

                const appliedFilters = typeof this.props.childPageData.requestData.appliedFilters != 'undefined' ? this.props.childPageData.requestData.appliedFilters: {};
                var dfpParams = '';
                if(!isEmpty(appliedFilters)){
                    var dfpData = new Object();
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
        window.scroll = null;
        PreventScrolling.enableScrolling(false,true);
        if(PreventScrolling.canUseDOM())
        {
            document.getElementById('page-header').style.display = "table";
            document.getElementById('page-header').style.position = "relative";
        }
        this.props.clearDfpBannerConfig();
        window.removeEventListener('scroll', this.onScroll);
    }

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
    
    scrollFinished()
    {
      makeShikshaHeaderSticky(this.params);
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
        let htmlNode = document.getElementById('ACP');
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
            trackingParams['pageEntityId'] = childPageData.listingId;
            trackingParams['countryId'] = 2;
            trackingParams['extraData'] = {} ;
            trackingParams['extraData']['url'] = addingDomainToUrl(this.props.location.pathname+this.props.location.search,config.SHIKSHA_HOME);
        trackingParams = getBeaconTrackData(childPageData,'UILP', 'allCoursePage');
        ElasticSearchTracking(trackingParams,config.BEACON_TRACK_URL);
      }
  }

    renderLoader() {
        PreventScrolling.enableScrolling(true);
        if(PreventScrolling.canUseDOM())
        {
            document.getElementById('page-header').style.display = "table";
            document.getElementById('page-header').style.position = "relative";
        }
    }

    fetchAllCourseData(paramsObj , subsequentCall = false)
    {
        var url  = JSON.stringify(paramsObj);
        var data = Buffer.from(url).toString('base64');
        this.setState({isShowLoader : true});
        var fetchPromise = this.props.fetchAllCourseData(data,'',false);
        var self = this;
        this.showToastMsg = true;
        fetchPromise.then(function() {
          if(self.props.childPageData != null && !self.isErrorPage()){
              if(typeof(isUserLoggedIn) != 'undefined' && isUserLoggedIn() && self.props.childPageData && self.props.childPageData.showToastMessage && self.showToastMsg){
                  self.showToastMsg = false;
                  setTimeout(function(){
                      showToastMsg(PageLoadToastMsg('SRM'),5000);
                  },3000);
              }
          }
          self.setState({aliasMapping : {},defaultAppliedFilters : {},filterOrder : {},filters: {},loadMoreCourses:[],nameMapping: {},selectedFiltersCount: 0,totalCourseCount : 0});
          self.trackBeacon();
          self.createViewedResponse();
          self.setState({isShowLoader : false});
          self.lastScrollTop = window.scrollY;
          stickyNavTabAndCTA();
          self.scrollStop();
          if(subsequentCall){
            self.getFiltersData();
          }
          self.getDFPData();
          self.isDfpData = true;
          self.props.storeChildPageDataForPreFilled();
        });
    }

  generateFilters(flagForReset){
     const pageUrl = this.props.childPageData.allCoursePageUrl;
     return (<Filters showTCF = {false} filters={this.state.filters} displayName={this.state.nameMapping} shortName={this.state.aliasMapping} pageUrl={pageUrl} defaultAppliedFilters={this.state.defaultAppliedFilters} gaTrackingCategory={'AllCoursePage_PWA'} filterOrder={this.state.filterOrder} selectedFiltersCount={this.state.selectedFiltersCount} isSrp = {false} isAllCoursesPage={true} instituteId={this.props.childPageData.listingId} flagForReset={flagForReset} hasFilterData={this.filterCall} onFilterButtonPress={this.onScroll}/>);
  }

  instituteLocationName(){
        const {childPageData,config} = this.props;
        let locationName = '';

        if(childPageData.currentLocation){
            if(childPageData.currentLocation.locality_name){
                locationName +=  childPageData.currentLocation.locality_name;
            }
            let showCityName = this.showCityName();
            if(childPageData.currentLocation.city_name && showCityName){
                if(locationName !=''){
                    locationName += ', ';
                }
                locationName += childPageData.currentLocation.city_name;
            }
        }
        return locationName;
    }

    showCityName(){
        const {childPageData} = this.props;
        let instituteName = '';
        let cityName = '';
        if(childPageData.instituteTopCardData && childPageData.instituteTopCardData.instituteName){
            instituteName = childPageData.instituteTopCardData.instituteName.toLowerCase();
        }
        if(childPageData.currentLocation && childPageData.currentLocation.city_name){
            cityName = childPageData.currentLocation.city_name.toLowerCase();
        }
        return (instituteName.indexOf(cityName) == -1)

    }

  getHeadingHtml(heading,extraHeading =null ,linkingUrl = ""){
      var headingHtml = [];
      if( this.props.fromwhere && this.props.fromwhere == "institutePage"){
          headingHtml.push(<h1 className='inst-name' key="heading_h1">{heading} <span className='hid'> ,{this.instituteLocationName()}</span></h1>);
      }
      else{
          headingHtml.push(<h1 className='inst-name' key="heading_h1">{heading} {extraHeading}<span className='hid'> ,{this.instituteLocationName()}</span></h1>);
      }
      return headingHtml;
  }
    render()
    {
        const {childPageData,config,location} = this.props;
        let seoData = (childPageData && childPageData.seoData) ? childPageData.seoData : '', 
        extraHeadingSuffix = (seoData.headingSuffix != null) ? seoData.headingSuffix: 'Admission';
        return (
            <React.Fragment>
                {ClientSeo(seoData)}
                <div className="ilp courseChildPage" id="ACP">
                    {this.getHeadingHtml(childPageData.instituteTopCardData.instituteName, extraHeadingSuffix)}
                    <CategoryTuple config={config} aggregateRatingConfig={this.props.childPageData.aggregateRatingConfig}  categoryData={this.props.childPageData} gaTrackingCategory={ 'AllCoursePage_PWA'}  onlycoursTuple={true} allCoursePage={true} fromwhere= "allCoursePage" recoShrtTrackid = {1142} recoEbTrackid ={1141} pageNumber={this.props.childPageData.paginationData.currentPageNUmber}  showOAF={true} applyNowTrackId={2101} deviceType='mobile' isPdfCall='true' hideCta={true} />
                </div>
            </React.Fragment>

            )
    }
}
function mapStateToProps(state)
{
    return {
        childPageData : state.childPageData,
        config : state.config
    }
}
function mapDispatchToProps(dispatch){
    return bindActionCreators({fetchAllCourseData,storeChildPageDataForPreFilled,dfpBannerConfig,clearDfpBannerConfig}, dispatch);
}

export default connect(mapStateToProps,mapDispatchToProps)(AllCoursePdf);

AllCoursePdf.propTypes = {
  childPageData: PropTypes.any,
  clearDfpBannerConfig: PropTypes.any,
  config: PropTypes.any,
  contentLoaderData: PropTypes.any,
  dfpBannerConfig: PropTypes.any,
  fetchAllCourseData: PropTypes.any,
  location: PropTypes.any,
  match: PropTypes.any,
  storeChildPageDataForPreFilled: PropTypes.any
}