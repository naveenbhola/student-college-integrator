import PropTypes from 'prop-types'
import React from 'react';
import { connect } from 'react-redux';
import ReviewWidget from './../../course/components/ReviewWidget';
import AdmissionProcess from './../../course/components/AdmissionProcess';
import Eligibility from './../../course/components/EligibilityComponent';
import ChildPagesInterlinking from './ChildPagesInterlinking';
import TopWidget from './../../institute/components/TopWidgetCommon';
import TopWidgetSticky from './../../institute/components/TopWidgetSticky';
import AnA from './../../course/components/AnAComponent';
import NotFound from './../../../common/components/NotFound';
import  { Redirect } from 'react-router-dom';
import {addingDomainToUrl,getObjectSize,resetGNB,makeTopWidgetSticky} from './../../../../utils/commonHelper';
import ClientSeo from './../../../common/components/ClientSeo';
import TagManager  from './../../../reusable/utils/loadGTM';
import {fetchAdmissionPageData,storeChildPageDataForPreFilled} from './../actions/AllChildPageAction';
import PreventScrolling from './../../../reusable/utils/PreventScrolling';
import AdmissionWidget from './../../institute/components/AdmissionComponent';
import { bindActionCreators } from 'redux';
import CourseEntryChances from './../../course/components/CourseEntryChancesWidget';
import CourseRecommendationInfo from './../../course/components/CourseRecommendationInfo'; 
import AdmissionMiddleSection from './AdmissonPageMiddleSection';
import BreadCrumb from './../../../common/components/BreadCrumb';
import AdmissionCourseSpecific from './AdmissionCourseSpecific';
import {dfpBannerConfig,clearDfpBannerConfig} from './../../../reusable/actions/commonAction';
import {DFPBannerTempalte} from './../../../reusable/components/DFPBannerAd';
import ElasticSearchTracking from './../../../reusable/utils/ElasticSearchTracking';
import './../assets/style.css';
import './../assets/ADPDesktop.css';
import APIConfig from './../../../../../config/apiConfig';
import {getRequest} from './../../../../utils/ApiCalls';
import AdmissionPageDesktopLoader from "./AdmissionPageDesktopLoader";
import ContentLoader from './../../institute/utils/contentLoader';
import {viewedResponse} from './../../../user/utils/Response';
import TOCWidget from './../../../common/components/TOCWidget';
import Feedback from "../../../common/components/feedback/Feedback";


class AdmissionPageDesktop extends React.Component
{
  constructor(props)
    {
      super(props);
      this.state = {
          isShowLoader : false,
          selectedStreamId:this.props.childPageData.selectedStreamId,
          selectedStreamName:this.props.childPageData.selectedStreamName,
          selectedCourseId:this.props.childPageData.selectedCourseId,
          selectedCourseName:this.props.childPageData.selectedCourseName,
          selectedCourseUrl:this.props.childPageData.selectedCourseUrl,
          streamObjs:this.props.childPageData.streamObjs,
          courseCollegeGrouping:this.props.childPageData.courseCollegeGrouping,
          collegeOrdering:this.props.childPageData.collegeOrdering,
          eligibility:this.props.childPageData.eligibility,
          admissionProcess:this.props.childPageData.admissionProcess,
          importantDates:this.props.childPageData.importantDates,
          predictorInfo:this.props.childPageData.predictorInfo
      }
    this.isDfpData = false;
    this.courseSpecificData = false;
    }

    componentDidMount(){
      window.isHeaderFixed = false;
        if(!this.isServerSideRenderedHTML()){
            this.initialFetchData();
            if(window && typeof window.scroll == 'function'){
              window.scroll(0,0);
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
        if(!this.courseSpecificData){
          this.courseSpecificData = true;
          this.getCourseSpecificData();
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

            beaconTrackData['pageIdentifier'] ='UILP';
            beaconTrackData['pageEntityId'] =childPageData.listingId;
            beaconTrackData['extraData']={};
            beaconTrackData['extraData']['listingType'] = childPageData.listingType;
            beaconTrackData['extraData']['countryId'] = 2;
            beaconTrackData['extraData']['childPageIdentifier'] = 'admissionPage';
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
                "trackingKeyId": 2069,
                "actionType": "Institute_Viewed",
                "listingType":"course"
                };
            viewedResponse(viewedResponseData);
        }

    }

    getCourseSpecificData(reqParams){
        const {selectedStreamId} = this.props.childPageData;
        this.courseSpecificData = true;
        if(selectedStreamId){
                let url = APIConfig.GET_ADMISSION_STREAM_COURSE_DATA;
                var param='';
                param ='?instituteId='+this.props.childPageData.listingId;
                if(reqParams){
                  if(reqParams['streamId']){
                    param +='&streamId='+reqParams['streamId'];    
                  }
                  if(reqParams['courseId']){
                    param +='&courseId='+reqParams['courseId'];    
                  }
                }else{
                  param +='&streamId='+selectedStreamId;
                }
                var self = this;
              getRequest(url+param).then((response) => {
                  if(response){
                    self.updateState(response.data.data);
                  }
              });
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
          selectedStreamId: data.selectedStreamId,
          selectedStreamName: data.selectedStreamName,
          selectedCourseId: data.selectedCourseId,
          selectedCourseName: data.selectedCourseName,
          selectedCourseUrl: data.selectedCourseUrl,
          streamObjs: data.streamObjs,
          courseCollegeGrouping: data.courseCollegeGrouping,
          collegeOrdering: data.collegeOrdering,
          eligibility: data.eligibility,
          admissionProcess: data.admissionProcess,
          importantDates: data.importantDates,
          predictorInfo: data.predictorInfo,
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
        let htmlNode = document.getElementById('AP');
        return ((htmlNode && htmlNode.innerHTML) || this.isErrorPage());
    }
    
    componentWillReceiveProps(nextProps)
    {
        window.scrollTo(0,0);
        resetGNB();
        let newinstituteId = nextProps.match.params.listingId;
        let previnstituteId = this.props.match.params.listingId;

        if(newinstituteId != previnstituteId){
          this.fetchAdmissionData(newinstituteId);
        }

        if(nextProps.location.search !=''){
            this.getDataForNewStreamOrCourse(nextProps.location.search);
        }
        if(nextProps.location.search ==''){
          if(this.props.childPageData.selectedStreamId !=this.state.selectedStreamId || this.props.childPageData.selectedCourseId !=this.state.selectedSCourseId){
            this.getCourseSpecificData();
          }
        }


        this.clearReduxContentLoaderData();
    }

    getDataForNewStreamOrCourse(searchString){
          var keyMapping = {'streamId':'streamId','courseId':"courseId"};
          searchString = searchString.substr(1,searchString.length);
          searchString = searchString.split('&');
          var keys = '';
          var reqParams =[];
          for(var i in searchString){
                keys = searchString[i].split('=');
              for(var j in keyMapping){
                  if(keyMapping[j] == keys[0]){
                    reqParams[keyMapping[j]] =  parseInt(keys[1]);
                  }
              }
          }
          if((reqParams['streamId'] && reqParams['streamId']!=this.state.selectedStreamId) ||(reqParams['courseId'] && reqParams['courseId']!=this.state.selectedCourseId)){
                this.getCourseSpecificData(reqParams);
          }

    }



    clearReduxContentLoaderData(){
    }




    initialFetchData()
    {
        let instituteId = this.props.match.params.listingId;
        if(isNaN(instituteId))
            return;
        this.fetchAdmissionData(instituteId);
    }

    fetchAdmissionData(instituteId)
    {
        var queryParams = 'instituteId='+instituteId;

        this.setState({isShowLoader : true});
        var fetchPromise = this.props.fetchAdmissionPageData(queryParams);
        var self = this;
        fetchPromise.then(function() {
          self.getDFPData();
          self.isDfpData = true;
          self.trackGTM();
          self.createViewedResponse();
          self.updateState(self.props.childPageData,false);
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
      
        if(getObjectSize(contentLoaderData) && contentLoaderData.showFullLoader){
          return(
                <div className="ilp courseChildPage pwa_admission">
                  <div className='pwa_pagecontent ap'>
                    <div className='pwa_container'>
                      {contentLoaderData.instituteTopCardData && <TopWidget showChangeBranch={false} instituteId={contentLoaderData.listingId} data={contentLoaderData} config={config} location = {contentLoaderData.currentLocation} page ='institute' extraHeading ={contentLoaderData.PageHeading} fromWhere= "allCoursePage" gaTrackingCategory={ 'AllCoursePage_PWA'} contentLoader={true}/>}
                      <AdmissionPageDesktopLoader leftLoader={true} rightLoader={true}/>
                    </div>
                  </div>
                </div>          
          );
        }
        else{
          return(
             <div className="ilp courseChildPage pwa_admission">
               <div className='pwa_pagecontent ap'>
                 <div className='pwa_container'>
                  <ContentLoader onlyTopCard={true} />
                  <AdmissionPageDesktopLoader leftLoader={true} rightLoader={true}/>
                  </div>
                </div>
            </div>         
          )
        }
      }

  render(){
      const {childPageData,config} = this.props;
      this.prepareBreadCrumbData();
      let finalTOCData = [];
      let tocData = childPageData.admissionTOC;
      if(tocData){
        finalTOCData.push(tocData);
      }

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

      else if(childPageData.pageType !='Admission'){
          return(
              <React.Fragment>
                  {this.showLoader()}
              </React.Fragment>
            );
      }
      let seoData = (childPageData && childPageData.seoData) ? childPageData.seoData : '';
      return(
            <React.Fragment>
              {ClientSeo(seoData)}
              {childPageData.instituteTopCardData && 
                <TopWidgetSticky showChangeBranch={false} 
                instituteId={childPageData.listingId} 
                data={childPageData} 
                config={config} 
                location = {this.props.location} 
                page ={this.props.childPageData.listingType} 
                extraHeading ='Admission' 
                allCoursePage={true} 
                fromWhere= "admissionPage" 
                gaTrackingCategory='AdmissionPage_PWA_Desk' 
                contentLoader={false} 
                isDesktop={true}
                pageType = 'ND_AllContentPage_Admission' />}

              <div className="ilp courseChildPage pwa_admission" id="AP">
                  

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
                                     extraHeading ='Admission' 
                                     allCoursePage={true} 
                                     fromWhere= "admissionPage" 
                                     gaTrackingCategory='AdmissionPage_PWA_Desk'
                                     isDesktop={true}
                                     contentLoader={false}
                                     pageType = 'ND_AllContentPage_Admission'
                                     />}
                                         
                        <div className="pwa_leftCol">
                            {childPageData.admissionTOC && <TOCWidget tocData={finalTOCData} gaCategory={'AdmissionPageMobile'}/>}
                            
                            {childPageData.adminssionData && childPageData.adminssionData.admissionDetails && 
                              <AdmissionMiddleSection data={childPageData.adminssionData} selectedStreamId={childPageData.selectedStreamId} />}
                            
                            <DFPBannerTempalte key={"Dfpbanner1"} bannerPlace="C_C2"/> 
                            <DFPBannerTempalte key={"Dfpbanner2"} bannerPlace="C_C2_2"/>        

                            {this.state.selectedStreamId && <AdmissionCourseSpecific placeHolderText='Search Course List' 
                            selectedStreamId={this.state.selectedStreamId} 
                            streamObjs={this.state.streamObjs} 
                            courseCollegeGroup={this.state.courseCollegeGrouping} 
                            otherCourses={this.state.otherCourses} 
                            collegeOrdering={this.state.collegeOrdering} 
                            data={childPageData} 
                            streamName ={this.state.selectedStreamName} 
                            courseName={this.state.selectedCourseName}  
                            clickHandler={this.updateState.bind(this)} 
                            courseSpecificData={this.courseSpecificData} 
                            clickHandlerForCourseData={this.getCourseSpecificData.bind(this)} 
                            fromWhere= "admissionPage" 
                            gaTrackingCategory='AdmissionPage_PWA_Desk'
                            isDesktop={true}
                            /> }
                            
                            <div id='courseSpecific'>
                              <div id='courseSpecificLoder' className='loaderAjax'>  </div>      
                            {this.state.eligibility != null && 
                              <Eligibility eligibility={this.state.eligibility} 
                                predictorInfo={null} 
                                deviceType ='desktop'
                                />}

                            {(this.state.admissionProcess != null || 
                             (this.state.importantDates && this.state.importantDates.importantDates != 'undefined' && this.state.importantDates.importantDates != null)) && 
                              <AdmissionProcess admissionProcess={this.state.admissionProcess} 
                              importantDates={this.state.importantDates} 
                              courseId={this.state.selectedCourseId} 
                              deviceType="desktop" />}  
                            
                            <CourseEntryChances predictorInfo={this.state.predictorInfo} 
                              fromWhere = "admissionPageDesktop" 
                              showCollegeOnly = {true} />

                            <CourseEntryChances predictorInfo={this.state.predictorInfo} 
                              showRankOnly = {true} 
                              fromWhere= "admissionPage" />
                    
                            
                            
                                   
                            {childPageData.selectedCourseUrl && 
                              <CourseRecommendationInfo listingId = {this.state.selectedCourseId}
                              instituteId={childPageData.instituteId} 
                              listingUrl={this.state.selectedCourseUrl} 
                              gaCategory='AdmissionPage_PWA_Desk' 
                              fromWhere = "admissionPage" 
                              isDesktop={true}
                              pageType = 'admissionPage'
                               />}

                           </div>
                            <Feedback pageId={childPageData.listingId} pageType={'AP'} deviceType={'desktop'} feedbackWidgetType={'type2'} />
                            {childPageData.reviewWidget != null && 
                              childPageData.reviewWidget.reviewData != null && 
                              childPageData.reviewWidget.reviewData.reviewsData &&
                            <ReviewWidget reviewWidgetData={childPageData.reviewWidget}
                            config={config} 
                            aggregateReviewWidgetData={childPageData.aggregateReviewWidget}  
                            gaTrackingCategory= 'AdmissionPage_PWA_Desk'
                            deviceType='desktop' />}

                            <AnA anaWidget={childPageData.anaWidget} 
                            config={config} 
                            courseId={this.state.selectedCourseId} 
                            instituteId={childPageData.listingId} 
                            page = {childPageData.listingType} 
                            location = {childPageData.currentLocation} 
                            fromWhere = "admissionPageDesktop" 
                            deviceType = 'desktop'
                            />

                        </div>

                        <div className="pwa_rightCol">
                          {<ChildPagesInterlinking data={childPageData} 
                            gaCategory='AdmissionPage_PWA_Desk' 
                            fromWhere= "admissionPage" /> } 

                          {childPageData.adminssionData !=null && 
                            (childPageData.tupleData) &&
                            <AdmissionWidget admissionData ={childPageData.adminssionData} 
                            config={config} 
                            page = {childPageData.listingType} 
                            gaCategory='AdmissionPage_PWA_Desk' 
                            showOnlyExam = {true} 
                            fromWhere = "admissionPage" 
                            deviceType = 'desktop'
                            tupleData={childPageData.tupleData}
                            />}  

                          <DFPBannerTempalte key={"Dfpbanner3"} bannerPlace="C_RP"/>  
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
    return bindActionCreators({ fetchAdmissionPageData,storeChildPageDataForPreFilled,dfpBannerConfig,clearDfpBannerConfig }, dispatch);
}
export default connect(mapStateToProps,mapDispatchToProps)(AdmissionPageDesktop);

AdmissionPageDesktop.propTypes = {
  childPageData: PropTypes.any,
  clearDfpBannerConfig: PropTypes.any,
  config: PropTypes.any,
  contentLoaderData: PropTypes.any,
  dfpBannerConfig: PropTypes.any,
  fetchAdmissionPageData: PropTypes.any,
  location: PropTypes.any,
  match: PropTypes.any,
  storeChildPageDataForPreFilled: PropTypes.any
}