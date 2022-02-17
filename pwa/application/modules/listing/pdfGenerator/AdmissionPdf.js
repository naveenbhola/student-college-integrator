import PropTypes from 'prop-types'
import React from 'react';
import { Link } from "react-router-dom";
import { connect } from 'react-redux';
import  { Redirect } from 'react-router-dom';
import { bindActionCreators } from 'redux';
import ReviewWidget from './../course/components/ReviewWidget';
import AdmissionProcess from './../course/components/AdmissionProcess';
import Eligibility from './../course/components/EligibilityComponent';
import NotFound from './../../common/components/NotFound';
import {addingDomainToUrl,PageLoadToastMsg,showToastMsg,isUserLoggedIn} from './../../../utils/commonHelper';
import ClientSeo from './../../common/components/ClientSeo';
import TagManager  from './../../reusable/utils/loadGTM';
import {fetchAdmissionPageData,storeChildPageDataForPreFilled} from '../instituteChildPages/actions/AllChildPageAction';
import {storeInstituteDataForPreFilled} from './../institute/actions/InstituteDetailAction';
import ContentLoader from '../instituteChildPages/utils/contentLoader';
import PreventScrolling from './../../reusable/utils/PreventScrolling';
import AdmissionWidget from '../institute/components/AdmissionComponent';
import AdmissionMiddleSection from '../instituteChildPages/components/AdmissonPageMiddleSection';
import {dfpBannerConfig,clearDfpBannerConfig} from './../../reusable/actions/commonAction';
import '../instituteChildPages/assets/style.css';
import APIConfig from './../../../../config/apiConfig';
import {getRequest} from './../../../utils/ApiCalls';
import {viewedResponse} from './../../user/utils/Response';

class AdmissionPage extends React.Component
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
      };
    this.isDfpData = false;
    this.courseSpecificData = false;
    this.showToastMsg = true;
    }

    onScroll = () => {

        if(!this.isDfpData){
            this.isDfpData = true;
            this.getDFPData();
        }
        if(!this.courseSpecificData && this.state.selectedStreamId){
          this.courseSpecificData = true;
          this.getCourseSpecificData();
        }
    };

    trackGTM()
    {
        const {childPageData,config} = this.props;
        let trackingParams={};

        let beaconTrackData = {};
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
        }
    }

    getCourseSpecificData(reqParams){
        const {selectedStreamId,selectedCourseId} = this.props.childPageData;
        this.courseSpecificData = true;
        if(selectedStreamId && selectedCourseId){
                let url = APIConfig.GET_ADMISSION_STREAM_COURSE_DATA;
                let param='';
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
                  param +='&courseId='+selectedCourseId;

                }
                let self = this;
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

   createViewedResponse() {
       let childPageData = this.props.childPageData;
       if(childPageData.flagshipCourseId){
       let flagshipCourseId = childPageData.flagshipCourseId;
           let viewedResponseData = {
               "listingId": flagshipCourseId,
               "trackingKeyId": 2071,
               "actionType": "MOB_Institute_Viewed",
               "listingType":"course"
               };
           viewedResponse(viewedResponseData);
       }

   }

  
  componentDidMount(){
        if(!this.isServerSideRenderedHTML()){
            this.initialFetchData();
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
        let htmlNode = document.getElementById('AP');
        return ((htmlNode && htmlNode.innerHTML) || this.isErrorPage());
    }
    
    componentWillReceiveProps(nextProps)
    {
        if(PreventScrolling.canUseDOM()) {
             document.getElementById('page-header').style.display = "block";
             document.getElementById('page-header').style.position = "relative";
         }
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
      PreventScrolling.enableScrolling(true);
      return <ContentLoader />;
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

  render(){
      const {childPageData,config} = this.props;
      if(this.state.isShowLoader){
        return (
            <React.Fragment>
                {this.renderLoader()}
            </React.Fragment>
            );
      }
      
      let seoData = (childPageData && childPageData.seoData) ? childPageData.seoData : '', 
      extraHeadingSuffix = (seoData.headingSuffix != null) ? seoData.headingSuffix: 'Admission';
      return(
            <React.Fragment>
              {ClientSeo(seoData)}
              <div className="ilp courseChildPage pwa_admission" id="AP">
                  {this.getHeadingHtml(childPageData.instituteTopCardData.instituteName, extraHeadingSuffix)}
                  {childPageData.adminssionData && childPageData.adminssionData.admissionDetails && <AdmissionMiddleSection data={childPageData.adminssionData} selectedStreamId={childPageData.selectedStreamId} isPdfGenerator={this.props.isPdfGenerator} />}

                  {this.state.eligibility != null && <Eligibility eligibility={this.state.eligibility} predictorInfo={null} isPdfGenerator={this.props.isPdfGenerator} />}

                  {(this.state.admissionProcess != null || (this.state.importantDates && this.state.importantDates.importantDates != 'undefined' && this.state.importantDates.importantDates != null)) && <AdmissionProcess admissionProcess={this.state.admissionProcess} importantDates={this.state.importantDates} courseId={this.state.selectedCourseId} isPdfGenerator={this.props.isPdfGenerator} />}  
                  
                  {childPageData.adminssionData !=null && (childPageData.tupleData) &&
                    <AdmissionWidget admissionData ={childPageData.adminssionData} config={config} tupleData={childPageData.tupleData} page = {childPageData.listingType} gaCategory='AdmissionPage_PWA' showOnlyExam = {true} fromWhere = "admissionPage" />}
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
    return bindActionCreators({fetchAdmissionPageData,storeChildPageDataForPreFilled,storeInstituteDataForPreFilled,dfpBannerConfig,clearDfpBannerConfig }, dispatch);
}
export default connect(mapStateToProps,mapDispatchToProps)(AdmissionPage);

AdmissionPage.propTypes = {
  childPageData: PropTypes.any,
  clearDfpBannerConfig: PropTypes.any,
  config: PropTypes.any,
  dfpBannerConfig: PropTypes.any,
  fetchAdmissionPageData: PropTypes.any,
  location: PropTypes.any,
  match: PropTypes.any,
  storeChildPageDataForPreFilled: PropTypes.any,
  storeInstituteDataForPreFilled: PropTypes.any,
  userDetails: PropTypes.any
}