import PropTypes from 'prop-types'
import React from 'react';
import { connect } from 'react-redux';
import ReviewWidget from './../../course/components/ReviewWidget';
import ChildPagesInterlinking from './ChildPagesInterlinking';
import TopWidget from './../../institute/components/TopWidgetCommon';
import AnA from './../../course/components/AnAComponent';
import BottomSticky from './BottomSticky';
import NotFound from './../../../common/components/NotFound';
import  { Redirect } from 'react-router-dom';
import {addingDomainToUrl,getObjectSize,PageLoadToastMsg,showToastMsg,isUserLoggedIn,parseQueryString,getQueryVariable} from './../../../../utils/commonHelper';
import {Helmet} from 'react-helmet';
import TagManager  from './../../../reusable/utils/loadGTM';
import {fetchPlacementPageData,storeChildPageDataForPreFilled} from './../actions/AllChildPageAction';
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
import AlumniComponent from './NaukriAlumniComponent';
import EditorialContentComponent from './EditorialContentComponent';


const Placement = Loadable({
  loader: () => import('./../../course/components/PlacementComponent'/* webpackChunkName: 'PlacementComponent' */),
  loading() {return null},
});


class PlacementPage extends React.Component
{
  constructor(props)
    {
      super(props);
      this.state = {
          isShowLoader : false,
          isGraphShow : false 
      }
    this.isDfpData = false;
    this.courseSpecificData = false;
    this.showToastMsg = true;
    this._scrollCount = true;
    }

    onScroll = () => {

        if(!this.isDfpData){
            this.isDfpData = true;
            this.getDFPData();
        }
        if(this._scrollCount){
            this.setState({isGraphShow : true});
            this._scrollCount = false;
        }
        // if(!this.courseSpecificData && this.state.selectedStreamId){
        //   this.courseSpecificData = true;
        //   this.getCourseSpecificData();
        // }
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

   createViewedResponse() {
       let childPageData = this.props.childPageData;
       if(childPageData.flagshipCourseId){
       let flagshipCourseId = childPageData.flagshipCourseId;
           let viewedResponseData = {
               "listingId": flagshipCourseId,
               "trackingKeyId": 3233,
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
              if(!getObjectSize(this.props.childPageData) || this.props.childPageData.pageType !='placement'){
                window.scrollTo(0,0);
              }
              else{
                if(document.getElementById('alumni_heading') != null && document.querySelector('.pwa_headerv1') != null &&  document.getElementById('Overview') ) {
                  window.scrollTo(0,document.getElementById('alumni_heading').clientHeight+document.querySelector('.pwa_headerv1').clientHeight + document.getElementById('Overview').clientHeight);
                }
              }
            }
        }
        else{
            if(!this.isErrorPage())
            {
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
        if(typeof(isUserLoggedIn) != 'undefined' && isUserLoggedIn()){
            this._scrollCount = false;
            this.setState({isGraphShow : true});
        }
        window.addEventListener("scroll", this.onScroll);
        this.props.clearDfpBannerConfig();  
        //this.props.storeChildPageDataForPreFilled();      

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
              else if(document.getElementById('alumni_heading') && document.querySelector('.pwa_headerv1') && document.getElementById('Overview'))
                  window.scrollTo(0,document.getElementById('alumni_heading').clientHeight+document.querySelector('.pwa_headerv1').clientHeight + document.getElementById('Overview').clientHeight);
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
        this.showToastMsg = true;
        fetchPromise.then(function() {
          self.getDFPData();
          self.isDfpData = true;
          self.trackGTM();
          self.createViewedResponse();
          self.updateState(self.props.childPageData,false);
          self.props.storeChildPageDataForPreFilled();   
          self.props.storeInstituteDataForPreFilled();  


        });
    }


  renderLoader() {
      // PreventScrolling.enableScrolling(true);
      return <ContentLoaderMain deviceType="mobile"/>;
    }  

  render(){
      const {childPageData,config} = this.props;
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
      else if(this.props.match.url !== childPageData.seoUrl){
        return(
          <React.Fragment>
              {this.renderLoader()}
          </React.Fragment>
          )
      }

      else if(childPageData.pageType !='Placement'){
          return(
              <React.Fragment>
                  {this.renderLoader()}
              </React.Fragment>
            );
      }

      return(
            <React.Fragment>
            {(childPageData.seoData && childPageData.seoData.metaTitle) && <Helmet>
                            <title> {childPageData.seoData.metaTitle} </title>
                        </Helmet>}

            <div id="fixed-card" className="nav-tabs displayNone">
                <SectionalNav noNavBar={true} />
            </div>
              <div className="ilp courseChildPage pwa_admission" id="PP">
                  {childPageData.instituteTopCardData && <TopWidget showChangeBranch={false} instituteId={childPageData.instituteId} data={childPageData} config={config} location = {this.props.location} page = {childPageData.listingType} extraHeading ='- Placements' allCoursePage={true} fromWhere= "placementPage" gaTrackingCategory={'Placement_Page_Mobile'} />}
                  <div id="tab-section" className='nav-tabs hidden'>
                      <SectionalNav beforeStickyCTA = {true} noNavBar={true}/>
                  </div>
                 

                {childPageData.aboutCollege && <EditorialContentComponent data={childPageData.aboutCollege} readMoreCount={450} gaCategory='Placement_Page_Mobile' deviceType='mobile'/>}  
                
                {childPageData.naukriData && <AlumniComponent pageData = {childPageData} gaCategory='Placement_Page_Mobile' deviceType='mobile' />}                



                {this.state.isGraphShow && 
                 ((childPageData.placements!= null && childPageData.placements!='') || (childPageData.recruitmentCompanies!= null && childPageData.recruitmentCompanies.length > 0)) && 
                 <Placement instituteName={childPageData.instituteTopCardData.instituteName} 
                 clientCourseId={childPageData.flagshipCourseId} 
                 placementData={childPageData.placements} 
                 intershipData={childPageData.intership} 
                 placementCTATrackingKey={3229}
                 internshipCTATrackingKey={3277}
                 recruitmentCompanies={childPageData.recruitmentCompanies}
                 gaCategory='Placement_Page_Mobile'
                 flagshipCourseUrl = {childPageData.flagshipCourseUrl}
                 flagshipCourseName = {childPageData.flagshipCourseName}
                 />
               }

                  {childPageData.reviewWidget != null && childPageData.reviewWidget.reviewData != null && childPageData.reviewWidget.reviewData.reviewsData &&
                        <ReviewWidget
                        reviewWidgetData={childPageData.reviewWidget}
                        config={config} aggregateReviewWidgetData={childPageData.aggregateReviewWidget}  gaTrackingCategory={ 'Placement_Page_Mobile'} />}
                  <AnA anaWidget={childPageData.anaWidget} config={config} courseId={childPageData.flagshipCourseId} instituteId={childPageData.listingId} page = {childPageData.listingType} location = {childPageData.currentLocation} fromWhere = "placementPage"/>      


                  <DFPBannerTempalte bannerPlace="UILPX_LAA"/>
                  <DFPBannerTempalte bannerPlace="UILPX_LAA1"/>
                  {<ChildPagesInterlinking data={childPageData} gaCategory='Placement_Page_Mobile' fromWhere= "placementPage" similarPlacement={true}/> }      
                  {<ChildPagesInterlinking data={childPageData} gaCategory='Placement_Page_Mobile' fromWhere= "placementPage" /> }      
              </div> 
              {<BottomSticky listingId={childPageData.listingId} listingName={childPageData.displayName} page = {childPageData.listingType} fromWhere= "placementPage" config={config} gaTrackingCategory={ 'Placement_Page_Mobile'}/>}       
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
    return bindActionCreators({fetchPlacementPageData,storeChildPageDataForPreFilled,storeInstituteDataForPreFilled,dfpBannerConfig,clearDfpBannerConfig }, dispatch);
}
export default connect(mapStateToProps,mapDispatchToProps)(PlacementPage);

PlacementPage.propTypes = {
  childPageData: PropTypes.any,
  clearDfpBannerConfig: PropTypes.any,
  config: PropTypes.any,
  dfpBannerConfig: PropTypes.any,
  fetchPlacementPageData: PropTypes.any,
  location: PropTypes.any,
  match: PropTypes.any,
  storeChildPageDataForPreFilled: PropTypes.any,
  storeInstituteDataForPreFilled: PropTypes.any
}