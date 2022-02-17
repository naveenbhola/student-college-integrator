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
import {Helmet} from 'react-helmet';
import TagManager  from './../../../reusable/utils/loadGTM';
import {fetchPlacementPageData,storeChildPageDataForPreFilled} from './../actions/AllChildPageAction';
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
import Placement from './../../course/components/PlacementComponent';
import AlumniComponent from './NaukriAlumniComponent';
import EditorialContentComponent from './EditorialContentComponent';




class PlacementPageDesktop extends React.Component
{
  constructor(props)
    {
      super(props);
      this.state = {
          isShowLoader : false,
      }
    this.isDfpData = false;
    this.courseSpecificData = false;
    }

    componentDidMount(){
      window.isHeaderFixed = false;
        if(!this.isServerSideRenderedHTML()){
            this.initialFetchData(this.props.location,this.props.match.params);
            if(window && typeof window.scroll == 'function'){
              if(!getObjectSize(this.props.childPageData) || this.props.childPageData.pageType !='placement'){
                window.scroll(0,0);
              }
              else{
                if(document.getElementById('alumni_heading') != null && document.querySelector('.pwa_headerv1') != null && document.getElementById('Overview')){
                  window.scroll(0,document.getElementById('alumni_heading').clientHeight+document.querySelector('.pwa_headerv1').clientHeight + document.getElementById('Overview').clientHeight);
                }
              }
            }
        }
        else{
            if(!this.isErrorPage())
            {
                this.trackGTM();
                this.createViewedResponse();
            }
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
              trackingParams.gtmParams['pageType'] = 'admissionPage';
              trackingParams.gtmParams['countryId'] = 2;
              trackingParams.gtmParams['listingType'] = childPageData.listingType;
              trackingParams.gtmParams['stream'] = this.state.selectedStreamId;

            beaconTrackData['pageIdentifier'] ='admissionPage';
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
                "trackingKeyId": 3231,
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
        //PreventScrolling.enableScrolling(false,true);
        if(PreventScrolling.canUseDOM())
        {
            //document.getElementById('page-header').style.display = "table";
            //document.getElementById('page-header').style.position = "relative";
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
            if(window && typeof window.scroll == 'function'){
              if(this.props.match.params.listingId != nextProps.match.params.listingId){
                window.scroll(0,0);
              }
              else if(document.getElementById('alumni_heading') && document.querySelector('.pwa_headerv1') && document.getElementById('Overview'))
                  window.scroll(0,document.getElementById('alumni_heading').clientHeight+document.querySelector('.pwa_headerv1').clientHeight + document.getElementById('Overview').clientHeight);
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
      paramsObj['instituteId'] = urlParams.listingId;  
      if(urlParams['3']!=''){
        paramsObj['baseCourseNameFromUrl'] = urlParams['3'];
      }


      for(var key of Object.keys(queryParams))
      {
        let keyArr = key.split(/[[\]]{1,2}/);

        if(keyArr[0] == 'year')
        {
          paramsObj['courseCompletionYear'] = getQueryVariable(keyArr[0], locationParams.search);//queryParams.getAll(key)[0];
        }
      }
      return paramsObj;
  }

    initialFetchData(location,urlParams)
    {
       let instituteId = this.props.match.params.listingId;
       const paramsObj = this.getUrlParams(location,urlParams);
       if(isNaN(instituteId))
           return;
      this.fetchPlacementData(paramsObj);
    }
    

    fetchPlacementData(paramsObj)
    {
        var reqString  = JSON.stringify(paramsObj);
        var queryParams = Buffer.from(reqString).toString('base64');



        this.setState({isShowLoader : true});
        var fetchPromise = this.props.fetchPlacementPageData(queryParams);
        var self = this;
        fetchPromise.then(function() {
          self.getDFPData();
          self.isDfpData = true;
          self.trackGTM();
          self.createViewedResponse();
          self.setState({isShowLoader:false});
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

      return <ContentLoaderMain deviceType="desktop"/>;
      
      }

  render(){
      const {childPageData,config} = this.props;
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
     else if(this.props.match.url !== childPageData.seoUrl){
        return(
          <React.Fragment>
              {this.showLoader()}
          </React.Fragment>
          )
      }

      else if(childPageData.pageType !='Placement'){
          return(
              <React.Fragment>
                  {this.showLoader()}
              </React.Fragment>
            );
      }
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
                extraHeading ='- Placements' 
                allCoursePage={true} 
                fromWhere= "placementPage" 
                gaTrackingCategory='Placement_Page_Desk' 
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
                                     location = {this.props.location} 
                                     page = {childPageData.listingType} 
                                     extraHeading ='- Placements' 
                                     allCoursePage={true} 
                                     fromWhere= "placementPage" 
                                     gaTrackingCategory='Placement_Page_Desk'
                                     isDesktop={true}
                                     contentLoader={false}
                                     pageType = 'ND_AllContentPage_Placement'
                                     />}
                                         
                        <div className="pwa_leftCol">      

                            

                            {childPageData.aboutCollege && <EditorialContentComponent data={childPageData.aboutCollege} readMoreCount={750} gaCategory='Placement_Page_Desk' deviceType='desktop'/>}  
                
                            {childPageData.naukriData && <AlumniComponent pageData = {childPageData} gaCategory='Placement_Page_Desk' deviceType='desktop'/>}                
  


                            {((childPageData.placements!= null && childPageData.placements!='') || (childPageData.recruitmentCompanies!= null && childPageData.recruitmentCompanies.length > 0)) && 
                             <Placement 
                             instituteName={childPageData.instituteTopCardData.instituteName} 
                             clientCourseId={childPageData.flagshipCourseId} 
                             placementData={childPageData.placements} 
                             intershipData={childPageData.intership} 
                             recruitmentCompanies={childPageData.recruitmentCompanies}
                             placementCTATrackingKey={3227}
                             internshipCTATrackingKey={3267}
                             deviceType ='desktop'
                             gaCategory='Placement_Page_Desk'
                             flagshipCourseUrl = {childPageData.flagshipCourseUrl}
                             flagshipCourseName = {childPageData.flagshipCourseName}
                             />}

                            {childPageData.reviewWidget != null && 
                              childPageData.reviewWidget.reviewData != null && 
                              childPageData.reviewWidget.reviewData.reviewsData &&
                            <ReviewWidget reviewWidgetData={childPageData.reviewWidget}
                            config={config} 
                            aggregateReviewWidgetData={childPageData.aggregateReviewWidget}  
                            gaTrackingCategory= 'Placement_Page_Desk'
                            deviceType='desktop' />}

                            <AnA anaWidget={childPageData.anaWidget} 
                            config={config} 
                            courseId={childPageData.flagshipCourseId} 
                            instituteId={childPageData.listingId} 
                            page = {childPageData.listingType} 
                            location = {childPageData.currentLocation} 
                            fromWhere = "placementPageDesktop" 
                            deviceType = 'desktop'
                            />

                            <DFPBannerTempalte key={"Dfpbanner1"} bannerPlace="C_C2"/>
                            <DFPBannerTempalte key={"Dfpbanner2"} bannerPlace="C_C2_2"/>


                        </div>

                        <div className="pwa_rightCol">
                          <DFPBannerTempalte key={"Dfpbanner3"} bannerPlace="C_RP"/>
                          {<ChildPagesInterlinking data={childPageData} 
                            gaCategory='Placement_Page_Desk' 
                            fromWhere= "placementPage" 
                            similarPlacement={true}
                            /> } 
                          {<ChildPagesInterlinking data={childPageData} 
                            gaCategory='Placement_Page_Desk' 
                            fromWhere= "placementPage" /> }                           

                        </div>
                      </div>
                  </div>             
              </div> 
            </React.Fragment>
        )
  }

}

// PlacementPageDesktop.propTypes = {
//     childPageData: React.PropTypes.object,
//     contentLoaderData: React.PropTypes.object,
//     match: React.PropTypes.object,
//     config: React.PropTypes.object,
//     location: React.PropTypes.object,
// };

function mapStateToProps(state)
{
    return {
      childPageData : state.childPageData,
      config : state.config,
      contentLoaderData : state.contentLoaderData
    }
}
function mapDispatchToProps(dispatch){
    return bindActionCreators({ fetchPlacementPageData,storeChildPageDataForPreFilled,dfpBannerConfig,clearDfpBannerConfig }, dispatch);
}
export default connect(mapStateToProps,mapDispatchToProps)(PlacementPageDesktop);

PlacementPageDesktop.propTypes = {
  childPageData: PropTypes.any,
  clearDfpBannerConfig: PropTypes.any,
  config: PropTypes.any,
  contentLoaderData: PropTypes.any,
  dfpBannerConfig: PropTypes.any,
  fetchPlacementPageData: PropTypes.any,
  location: PropTypes.any,
  match: PropTypes.any,
  storeChildPageDataForPreFilled: PropTypes.any
}