import PropTypes from 'prop-types'
import React from 'react';
import { connect } from 'react-redux';
import ReviewWidget from './../../course/components/ReviewWidget';
import ChildPagesInterlinking from './ChildPagesInterlinking';
import TopWidget from './../../institute/components/TopWidgetCommon';
import TopWidgetSticky from './../../institute/components/TopWidgetSticky';
import AnA from './../../course/components/AnAComponent';
import NotFound from './../../../common/components/NotFound';
import  { Redirect } from 'react-router-dom';
import {addingDomainToUrl,getObjectSize,resetGNB,makeTopWidgetSticky,parseQueryString,getQueryVariable} from './../../../../utils/commonHelper';
import {contentLoaderHelper} from './../../../../utils/ContentLoaderHelper';
import {Helmet} from 'react-helmet';
import TagManager  from './../../../reusable/utils/loadGTM';
import Analytics from './../../../reusable/utils/AnalyticsTracking';
import {fetchCutOffPageData,storeChildPageDataForPreFilled} from './../actions/AllChildPageAction';
import PreventScrolling from './../../../reusable/utils/PreventScrolling';
import { bindActionCreators } from 'redux';
import BreadCrumb from './../../../common/components/BreadCrumb';
import {dfpBannerConfig,clearDfpBannerConfig} from './../../../reusable/actions/commonAction';
import ElasticSearchTracking from './../../../reusable/utils/ElasticSearchTracking';
import './../assets/style.css';
import {DFPBannerTempalte} from './../../../reusable/components/DFPBannerAd';
import './../assets/ADPDesktop.css';
import AdmissionPageDesktopLoader from "./AdmissionPageDesktopLoader";
import ContentLoader from './../../institute/utils/contentLoader';
import ContentLoaderMain from './../utils/ContentLoaderMain';
import {viewedResponse} from './../../../user/utils/Response';
import EditorialContentComponent from './EditorialContentComponent';
import CutoffWidgetWrapper from './CutoffWidgetWrapper';
import CutoffFilter from './DropDownFilters';
import APIConfig from './../../../../../config/apiConfig';
import {getRequest} from './../../../../utils/ApiCalls';
import LinkOCF from './LinkOCF';

              
class CutOffPageDesktop extends React.Component
{
  constructor(props)
    {
      super(props);
      this.state = {
          isShowLoader : false,
          filters : props.childPageData.filters,
          aliasMapping : props.childPageData.aliasMapping,
          defaultAppliedFilters : props.childPageData.defaultAppliedFilters,
          loadMoreCourses:[],
          filterOrder : props.childPageData.filterOrder,
          nameMapping: props.childPageData.nameMapping,
          selectedFiltersCount: 0,
          totalCourseCount : 0

      }
    this.isDfpData = false;
    this.courseSpecificData = false;
    this.filterCall = false;
    }

    componentDidMount(){
      window.isHeaderFixed = false;
      window.filterCall = true;
        if(!this.isServerSideRenderedHTML()){
            this.initialFetchData(this.props.location,this.props.match.params);
            if(window && typeof window.scroll == 'function'){
              if(!getObjectSize(this.props.childPageData) || this.props.childPageData.pageType !='placement'){
                window.scroll(0,0);
              }
              else if(document.getElementById('linkOCF') && document.querySelector('.pwa_headerv1') && document.getElementById('Overview') )
                window.scroll(0,document.getElementById('linkOCF').clientHeight+document.querySelector('.pwa_headerv1').clientHeight + document.getElementById('Overview')+clientHeight);
              
            }
      }
        else{
            if(!this.isErrorPage())
            {
                this.trackGTM();
                this.createViewedResponse();
            }
            //this.getFiltersData();
        }
        resetGNB();
        window.addEventListener("scroll", this.onScroll);
        this.props.clearDfpBannerConfig();  
        //this.props.storeChildPageDataForPreFilled();      


    } 

    onScroll = () => {

        if(!this.isDfpData){
            this.isDfpData = true;
            this.getDFPData();
        }
        makeTopWidgetSticky();
    };

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

    createViewedResponse() {
        let childPageData = this.props.childPageData;
        if(childPageData.flagshipCourseId){
        let flagshipCourseId = childPageData.flagshipCourseId;
            let viewedResponseData = {
                "listingId": flagshipCourseId,
                "trackingKeyId": 3687,
                "actionType": "Institute_Viewed",
                "listingType":"course"
                };
            viewedResponse(viewedResponseData);
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
          isShowLoader: showLoader
        }
        );
   } 
  
    componentWillUnmount()
    {
        window.isHeaderFixed = true;
        this.props.clearDfpBannerConfig();
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

    contentLoaderData = (position) => {
      const {childPageData} = this.props;
      let dataObj = [];
      dataObj.scrollPosition = position;
      dataObj.clickType = 'exam';
      let pageUrl = childPageData.listingUrl + '/cutoff';
      this.props.storeChildPageDataForPreFilled(contentLoaderHelper(childPageData,dataObj,"CutOff",pageUrl));
    };

    onResetButtonPress = () => {
      this.trackEvent('reset','click');
      this.contentLoaderData();
    };

    trackEvent(actionLabel,label){
        Analytics.event({category : 'Cutoff_Page_Desk', action : actionLabel, label : label});
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
            if(window && typeof window.scroll == 'function'){
              if(this.props.match.params.listingId != nextProps.match.params.listingId){
                window.scroll(0,0);
              }
              else if(document.querySelector('.pwa_headerv1') && document.getElementById('Overview')  ){
                let position = 0;
                position = document.querySelector('.pwa_headerv1').clientHeight + document.getElementById('Overview').clientHeight;
                if(document.getElementById('linkOCF')){
                  position += document.getElementById('linkOCF').clientHeight;
                }

                window.scroll(0,position);
              }
            }
            resetGNB();
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


    getFiltersData(){
      const {location} = this.props;
        this.filterCall = true;
         var params = this.getBase64UrlParams(location,this.props.match.params);
         const filterAPI = APIConfig.GET_CUTOFF_PAGE_DATA;
          getRequest(filterAPI+'?data='+params).then((response) => {
            if(response.data.data != null)
            {   
              var data = response.data.data;
              this.setState({aliasMapping : data.aliasMapping,defaultAppliedFilters : data.defaultAppliedFilters,filterOrder : data.filterOrder,filters: data.filters,nameMapping: data.nameMapping});
              this.trackGTM();
            }
        });
      }
    

    fetchCutOffData(paramsObj)
    {
        var reqString  = JSON.stringify(paramsObj);
        var queryParams = Buffer.from(reqString).toString('base64');
        this.setState({isShowLoader : true});
        var fetchPromise = this.props.fetchCutOffPageData(queryParams);
        var self = this;
        fetchPromise.then(function() {
          self.getDFPData();
          self.isDfpData = true;          
          self.setState({aliasMapping : self.props.childPageData.aliasMapping,defaultAppliedFilters : self.props.childPageData.defaultAppliedFilters,filterOrder : self.props.childPageData.filterOrder,filters: self.props.childPageData.filters,nameMapping: self.props.childPageData.nameMapping,isShowLoader:false});
          self.trackGTM();
          self.createViewedResponse();
          self.props.storeChildPageDataForPreFilled();      
        });
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
      const {contentLoaderData,config} = this.props;
      let position = 0;
      if(contentLoaderData && contentLoaderData.scrollPosition != -1){
          position = contentLoaderData.scrollPosition;
          PreventScrolling.enableScrolling(false,true,position);
      }
      return <ContentLoaderMain deviceType="desktop"/>;
      
      }

  render(){
      const {childPageData,config, location} = this.props;
      const pageUrl = childPageData.seoUrl;
      let showOCF = false;
      if(childPageData.filters && childPageData.filters.exam){
        showOCF = true;
      }
      let showReset = true;
      if(this.props.location.pathname === pageUrl && this.props.location.search == ''){
        showReset = false;
      }
      this.prepareBreadCrumbData();
      if(this.state.isShowLoader){
        return (
            <React.Fragment>
                {this.showLoader()}
            </React.Fragment>
            );
      }
      if(isNaN(this.props.match.params.listingId) || (childPageData && typeof childPageData.statusCode != 'undefined' && childPageData.statusCode == 404) || childPageData.listingId === 0 ){
        resetGNB();
        return <NotFound />;
      }
      else if(childPageData == null || Object.keys(childPageData).length == 0){
        return (
            <React.Fragment>
                {this.showLoader()}
            </React.Fragment>
            );
      }
      else if(childPageData.listingId && childPageData.listingId != this.props.match.params.listingId){
       return (
           <React.Fragment>
               {this.showLoader()}
           </React.Fragment>
       );
      }
      else if(childPageData.statusCode == 301 || childPageData.statusCode == 302){
        <Redirect to={childPageData.seoUrl}/>
      }

      else if(childPageData.pageType !='Cutoff'){
          return(
              <React.Fragment>
                  {this.showLoader()}
              </React.Fragment>
            );
      }
      let paramsObj = this.getBase64UrlParams(location,this.props.match.params);

      return(
            <React.Fragment>
              {(childPageData.seoData && childPageData.seoData.metaTitle) && 
                <Helmet>
                  <title> {childPageData.seoData.metaTitle} </title>
                </Helmet>
              }
              {childPageData.instituteTopCardData && 
                <TopWidgetSticky showChangeBranch={false} 
                instituteId={childPageData.listingId} 
                data={childPageData} 
                config={config} 
                location = {this.props.location} 
                page ={this.props.childPageData.listingType} 
                extraHeading ='- CutOff' 
                allCoursePage={true} 
                fromWhere= "cutOffPage" 
                gaTrackingCategory='Cutoff_Page_Desk' 
                contentLoader={false} 
                isDesktop={true}
                pageType = 'ND_AllContentPage_Placement' />}

              <div className="ilp courseChildPage pwa_admission" id="PP">
                  

                  <div className="pwa_pagecontent ap">
                      <div className="pwa_container">
                      {childPageData.breadCrumb && <BreadCrumb breadCrumb={childPageData.breadCrumb} /> }
                      
                        {childPageData.instituteTopCardData && 
                          <TopWidget showChangeBranch={false} 
                                     instituteId={childPageData.instituteId} 
                                     data={childPageData} 
                                     config={config} 
                                     location = {location} 
                                     page = {childPageData.listingType} 
                                     extraHeading ='- CutOff' 
                                     allCoursePage={true} 
                                     fromWhere= "cutOffPage" 
                                     gaTrackingCategory='Cutoff_Page_Desk'
                                     isDesktop={true}
                                     contentLoader={false}
                                     pageType = 'ND_AllContentPage_Placement'
                                     />}
                                         
                        <div className="pwa_leftCol">
                        {childPageData.aboutCollege && <EditorialContentComponent data={childPageData.aboutCollege} readMoreCount={750} gaCategory='Cutoff_Page_Desk' deviceType='desktop'/>}  
                        {(showOCF || showReset) && <div className="_padaround white--bg" id="linkOCF">      
                          <LinkOCF location={location} showReset={showReset} onClickReset={this.onResetButtonPress} collegeOCF={false} examOCF={true} pageUrl={pageUrl} childPageData={childPageData} gaCategory={'Cutoff_Page_Desk'} isDesktop={true}/>      
                        </div>}
                        {childPageData.filters &&  (childPageData.filters.exam || childPageData.filters.specialization || childPageData.filters.institute  || childPageData.filters.category)  
                          && 
                          <CutoffFilter childPageData={childPageData} gaTrackingCategory={"Cutoff_Page_Desk"} filters={this.state.filters}
                                        displayName={this.state.nameMapping} shortName={this.state.aliasMapping}
                                        defaultAppliedFilters={this.state.defaultAppliedFilters} filterOrder={this.state.filterOrder}
                                        hasFilterData={this.filterCall} onFilterButtonPress= {this.onScroll}
                                        filtersWithSearch={['exam','institute','specialization','category']}
                                        requestData={childPageData.requestData}
                                        instituteId={childPageData.listingId} isCutOffPage={true}
                                        pageUrl ={childPageData.seoUrl}
                                        appliedCategory = {childPageData.appliedCategory}      
                            />
                        }

                            
                            <CutoffWidgetWrapper 
                              data={childPageData.courseTuples}
                              listingId={childPageData.listingId}
                               listingName ={childPageData.listingName}
                               listingType = {childPageData.listingType}
                               device='desktop'
                               trackingKey = {3679}
                               pdfUrl ={childPageData.cutoffCtaPdfUrl}
                               shortlistTrackingKey = {3671}
                               ebTrackingKey = {3675}
                               gaTrackingCategory={"Cutoff_Page_Desk"}
                               ViewMoreCount = {6}
                               contentLoaderData={this.contentLoaderData}
                              paramsObj = {paramsObj}
                              courseTupleCount = {childPageData.courseTupleCount}
                              courseTupleLimit = {childPageData.courseTupleLimit}
                               pageUrl ={childPageData.seoUrl}
                               childPageData={childPageData}
                            />

                            {childPageData.reviewWidget != null &&
                              childPageData.reviewWidget.reviewData != null &&
                              childPageData.reviewWidget.reviewData.reviewsData &&
                            <ReviewWidget reviewWidgetData={childPageData.reviewWidget}
                            config={config} 
                            aggregateReviewWidgetData={childPageData.aggregateReviewWidget}  
                            gaTrackingCategory= 'Cutoff_Page_Desk'
                            deviceType='desktop' />}

                            <AnA anaWidget={childPageData.anaWidget} 
                            config={config} 
                            courseId={childPageData.flagshipCourseId} 
                            instituteId={childPageData.listingId} 
                            page = {childPageData.listingType} 
                            location = {childPageData.currentLocation} 
                            fromWhere = "placementPageDesktop" 
                            deviceType = 'desktop'
                            gaTrackingCategory='Cutoff_Page_Desk'
                            />

                        </div>

                        <div className="pwa_rightCol">
                          <DFPBannerTempalte key={"Dfpbanner3"} bannerPlace="C_RP" parentPage={"DFP_InstituteDetailPage"}/>
                          {<ChildPagesInterlinking data={childPageData} 
                            gaCategory='Cutoff_Page_Desk' 
                            fromWhere= "cutoffPage" 
                            similarPlacement={true}
                            /> } 
                          {<ChildPagesInterlinking data={childPageData} 
                            gaCategory='Cutoff_Page_Desk' 
                            fromWhere= "cutoffPage" /> }                           

                        </div>
                      </div>
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
    return bindActionCreators({ fetchCutOffPageData,storeChildPageDataForPreFilled,dfpBannerConfig,clearDfpBannerConfig }, dispatch);
}
export default connect(mapStateToProps,mapDispatchToProps)(CutOffPageDesktop);

CutOffPageDesktop.propTypes = {
  childPageData: PropTypes.any,
  clearDfpBannerConfig: PropTypes.any,
  config: PropTypes.any,
  contentLoaderData: PropTypes.any,
  dfpBannerConfig: PropTypes.any,
  fetchCutOffPageData: PropTypes.any,
  location: PropTypes.any,
  match: PropTypes.any,
  storeChildPageDataForPreFilled: PropTypes.any
}