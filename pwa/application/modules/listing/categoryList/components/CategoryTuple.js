import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import PropTypes from 'prop-types';
import { Link } from "react-router-dom";
import { getRequest } from '../../../../utils/ApiCalls';
import {getRupeesDisplayableAmount} from './../../course/utils/listingCommonUtil';
import Pagination from './Pagination';
import APIConfig from './../../../../../config/apiConfig';
import DownloadEBrochure from './../../../common/components/DownloadEBrochure';
import Shortlist from './../../../common/components/Shortlist';
import Analytics from './../../../reusable/utils/AnalyticsTracking';
import Lazyload from './../../../reusable/components/Lazyload';
import {storeCourseDataForPreFilled} from './../../course/actions/CourseDetailAction';
import {storeInstituteDataForPreFilled} from './../../institute/actions/InstituteDetailAction';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import FullPageLayer from './../../../common/components/FullPageLayer';
import {DFPBannerTempalte} from './../../../reusable/components/DFPBannerAd';
import tupleCss from './../assets/categoryTuple.css';
import CategoryTableComponent from './CategoryTableComponent';
import {addingDomainToUrl} from '../../../../utils/commonHelper';
import {removeDomainFromUrl} from '../../../../utils/urlUtility';
import config from './../../../../../config/config';
import AggregateReview from './../../course/components/AggregateReviewWidget';
import {srpConstants,ctpConstants,allCourseConstants} from './../config/categoryConfig';
import PCW from './../../../search/components/PCW'
import ApplyNow from './../../../common/components/ApplyNow';
import Loadable from "react-loadable";

const OCF = Loadable({
    loader: () => import('../../../search/components/OCF'/* webpackChunkName: 'OCF' */),
    loading() {
        return null
    },
});
class CategoryTuple extends Component {
  constructor(props, context)
  {
    super(props, context);
    this.state = {
      listings:[],
      remainingCourseIds: [],
      fullLayer: false
    }
    this.trackingKeyId = 272;
    if(this.props.isSrp){
        this.config = srpConstants(this.props.isPdfCall);
    } 
    else if(this.props.allCoursePage){
      this.config = allCourseConstants();
    }else{
        this.config = ctpConstants(this.props.isPdfCall);
    }
    
    this.aggregateRatingConfig = this.props.aggregateRatingConfig;
    this.OcfDownloaded = false;
  }
  componentDidMount() {
      window.addEventListener("scroll", this.onScroll);
  }
  componentWillUnmount() {
      window.removeEventListener('scroll', this.onScroll);
  }

    onScroll = () => {
      if(this.props.showOCF && !this.OcfDownloaded){
          OCF.preload();
          this.OcfDownloaded = true;
      }
    };
    openTableComponentLayer() {
      this.trackEvent('Data in table', 'click');
    this.setState({
        fullLayer: true
    });
      if(document.getElementById('ctp_pwa')==null){
          return;
      }
      document.getElementById('fullLayer-container').classList.add("show");
      document.getElementById('wrapperMainForCompleteShiksha').classList.add("noscroll");
  }

  closeTableComponentLayer(){
    this.setState({
        fullLayer: false
    });
      if(document.getElementById('ctp_pwa')==null){
          return;
      }
      document.getElementById('fullLayer-container').classList.remove("show");
      document.getElementById('wrapperMainForCompleteShiksha').classList.remove("noscroll");
  }

  getTableComponent(){
    return <CategoryTableComponent show="true" categoryData={this.props.categoryData}/>;
  }

  showExamMore(courseId){
    document.getElementById('openEPMore'+courseId).classList.add("hide");
    document.getElementById('epList'+courseId).classList.remove("hide");
  }

  prepareExamList(examList, examUrls, courseId, thisObj){
    var elist = new Array();
    let examSet = new Set();
    for(var i in examList){
        let e = examList[i];
        if(examSet.has(e.exam_name) || e.exam_name==null)
            continue;
        examSet.add(e.exam_name);
        if(typeof(examUrls[e.exam_id]) != 'undefined'){
          if(this.props.isPdfCall) {            
            elist.push(<a key={'e'+i} onClick={thisObj.trackEvent.bind(thisObj,'Popular_Colleges','Exam_click')} href={addingDomainToUrl(examUrls[e.exam_id], this.props.config.SHIKSHA_HOME)}>{' '+e.exam_name}</a>);
          }
          else {
            elist.push(<Link key={'e'+i} onClick={thisObj.trackEvent.bind(thisObj,'Popular_Colleges','Exam_click')} to={examUrls[e.exam_id]}>{' '+e.exam_name}</Link>);
          }
          elist.push(',');
        }else{
          elist.push(<span className='exm-name' key={'e'+i}>{' '+e.exam_name}</span>);
          elist.push(',');
        }
    }
    elist.pop();
    if(examSet.size ==0 ){
      return null;
    }
    return (
        <span className='avail_exams allexam'>
          {elist}
        </span>
      )
  }
  
  handleClickOnCourse(instituteData,courseData)
  {
      this.trackEvent('ChooseCLP','Click');
    if(typeof courseData != 'undefined' && typeof courseData == 'object' && typeof instituteData != 'undefined' && typeof instituteData == 'object')
      {
        courseData.instituteName =  instituteData.name;
        courseData.instituteUrl  = instituteData.url;
        this.props.storeCourseDataForPreFilled(courseData);
      }
  }

  downloadEBRequest=(params,e)=>{
    let thisObj = e.currentTarget;
    if(typeof(window) !='undefined' && typeof(ajaxDownloadEBrochure) !='undefined'){
        this.trackEvent('DownloadBrochure','Response');
        window.ajaxDownloadEBrochure(thisObj,params.courseId,"course",params.courseName,this.props.pageType, this.props.ebTrackid, this.props.recoEbTrackid, 0, 0, this.props.recoShrtTrackid);
    }
  }

  addToShortlist =(params,e)=>{
    if(typeof(window) !='undefined' && typeof(addShortlistFromSearch) !='undefined'){
        this.trackEvent('Shortlist','Response');
        let pageType = this.props.pageType;
        if(this.props.pageType === 'searchPage'){
            pageType = 'SearchV2';
        }
        let otherParams = {recoEbTrackid : this.props.recoEbTrackid, recoShrtTrackid : this.props.recoShrtTrackid};
        window.addShortlistFromSearch(params.courseId, this.props.srtTrackId, pageType, otherParams);
    } 
  }

  handleClickOnInstitute(instituteData, index = null,fromWhere='',trackCategory='',trackEvent='')
  {
      if(trackEvent!='' && trackCategory!=''){
        this.trackEvent(trackCategory ,trackEvent);
      }else{
          if(index)
            this.trackEvent('ILP_' + (parseInt(index) + 1) ,'tuple_click');
          else
            this.trackEvent('ILP','tuple_click');
      }

      if(typeof instituteData != 'undefined' && typeof instituteData == 'object') {
        var data =  [];
        data.instituteName = instituteData.name;
        data.ownership = instituteData.ownership;
        data.autonomous = instituteData.autonomous;
        data.nationalImportance = instituteData.nationalImportance;
        data.univeristyTypeWithSpecification = instituteData.universityTypeWithSpecification;
        data.ugcApproved = instituteData.ugcApproved;
        data.naacAccreditation = instituteData.naacAccreditation;
        data.establishYear = instituteData.estbYear;
        data.aiuMember = instituteData.aiuMember;
        data.headerImage = instituteData.instituteThumbUrl;
        if(typeof instituteData != 'undefined' && instituteData != null && instituteData.courseTupleData && instituteData.courseTupleData.displayLocationString){
            data.location = instituteData.courseTupleData.displayLocationString;
        }
        if(fromWhere =='AdmissionLink'){
          data.extraHeading = 'Admission 2019 - Cutoffs, Eligibility & Dates';
          data.pageName = 'Admission';
          data.fromWhere = "admissionPage";
        }

        this.props.storeInstituteDataForPreFilled(data);
      }
  }

  courseStatusData(courseData){
    var courseName = courseData.courseStatusData.split(' by ');
    let ele = '';
    if(this.props.deviceType=='mobile'){
     ele = <Link to={courseData.courseStatusUrl} onClick={this.trackEvent.bind(this,'Offered_By','click')} > {courseName[1]} </Link>
    }
    else{
     ele = <a href={courseData.courseStatusUrl} onClick={this.trackEvent.bind(this,'Offered_By','click')} > {courseName[1]} </a> 
    }
    return (
        <ul>
          <li className="offered-by">{courseName[0]} by {ele}</li>
        </ul>
      )    
  }
    

  getCourseTuple(info, tupleType, instituteUrl,instituteData){
    var ratingData = null;
    if(typeof info.aggregateReviewData!='undefined' && info.aggregateReviewData && typeof this.aggregateRatingConfig!= 'undefined' && this.aggregateRatingConfig){
      ratingData =  {...this.aggregateRatingConfig, 'aggregateReviewData': info.aggregateReviewData };
    }
    var self = this;
    var moreClass = 'none';
    if(typeof tupleType != 'undefined' && tupleType=='more'){
      moreClass = "brdr-Top";
    }
    if(!info.courseUrl)
      return;
    let examsCount = 0;
    if(info.showExams && info.eligibility.length>0 && typeof(self.props.categoryData) != 'undefined' && self.props.categoryData.examsUrl){
        examsCount = this.getExamsUniqueCount(info.eligibility);
    }

    return(
      <div id={info.courseId} key={info.courseId} className={moreClass}>
        <div className='flexi-div'>
          <div className="ctp-SrpDiv">
          {(self.props.isPdfCall) ? <a href={addingDomainToUrl(info.courseUrl, self.props.config.SHIKSHA_HOME)} className="fnt-w6" onClick={self.handleClickOnCourse.bind(self,instituteData,info)}>{info.name.length>55?info.name.substring(0,52)+'...': info.name}</a> : <Link to={info.courseUrl} className="fnt-w6" onClick={self.handleClickOnCourse.bind(self,instituteData,info)}>{info.name.length>55?info.name.substring(0,52)+'...': info.name}</Link>}
              <div className="ctp-detail">
                  <ul>
                  {ratingData &&
                      <li>
                        <AggregateReview uniqueKey = {'Course_'+info.courseId} isPaid = {false} reviewsCount={info.reviewCount} reviewUrl = {info.allReviewsUrl} aggregateReviewData = {ratingData} showAllreviewUrl = {true} config={config()} showReviewBracket = {true} gaTrackingCategory = {this.props.gaTrackingCategory}/>
                      </li>
                  } 
                      {(ratingData!=null && info.fees!=null)? <li><span> | </span></li>:''} 
                      {info.fees!=null && (!info.feesUnit || info.feesUnit=='INR')?<li className="feesUnit">Total Fees: &#8377; {getRupeesDisplayableAmount(info.fees)}</li>:''}
                      {(info.fees!=null && info.feesUnit && info.feesUnit!='INR')?<li className="feesUnit">Total Fees: {info.feesUnit +' '+getRupeesDisplayableAmount(info.fees)}</li>:''}
                      {((ratingData!=null || info.fees!=null) && info.durationValue>0)?<li><span> | </span></li>:''}
                      {info.durationValue>0?<li>{info.durationValue} {info.durationUnit}</li>:''}
                      {((ratingData!=null || info.fees!=null || info.durationValue>0) && info.courseExtraInfo.extraInfo.educationType!=null)?<li><span> | </span></li>:''}
                      {info.courseExtraInfo.extraInfo.educationType!=null?<li>{info.courseExtraInfo.extraInfo.educationType}</li>:''}
                  </ul>
              </div>
              
              {examsCount > 0 && self.prepareExamList(info.eligibility, self.props.categoryData.examsUrl, info.courseId, self)!=null ?
              <div className="ctp-detail">
                  <label>Exams: 
                  {self.prepareExamList(info.eligibility, self.props.categoryData.examsUrl, info.courseId, self)}
                  </label>
              </div> :''}
              {  (info.courseStatusData && typeof info.courseStatusUrl !='undefined' && info.courseStatusUrl) ?
                (
                  <div className="ctp-detail QRP-list">
                        {this.courseStatusData(info)}
                  </div>
                  ): null  
              }
          </div>
    </div>
      {this.props.hideCta ? '' : 
          <div className="ctp-SrpBtnDiv">
              {info.lda && this.props.showUSPLda ? <div><span className="application-msg">{info.lda}</span></div> : ''}
  {(typeof(this.props.deviceType) == 'undefined' || this.props.deviceType == 'mobile') ?
      <Shortlist listingId={info.courseId} trackid={(typeof(this.props.srtTrackId) != 'undefined' && this.props.srtTrackId) ?
          this.props.srtTrackId : this.config.shortlistTrackingId}
                 recoShrtTrackid={(typeof(this.props.recoShrtTrackid) !='undefined' && this.props.recoShrtTrackid) ? this.props.recoShrtTrackid : 1089}
                 recoEbTrackid={(typeof(this.props.recoEbTrackid) !='undefined' && this.props.recoEbTrackid) ? this.props.recoEbTrackid : 273}
                 recoPageType="EBrochure_RECO_Layer" recoActionType="EBrochure_RECO_Layer" actionType="NM_category_shortlist" pageType="NM_Category"
                 sessionId="" visitorSessionid="" isCallReco={this.props.isCallReco} clickHandler={this.trackEvent.bind(this,'Shortlist','Response')}
                 isButton={true}/> :
      <button type="button" name="button" className={'ctp-btn ctpComp-btn button button--secondary rippleefect tupleShortlistButton shrt'+info.courseId}
         onClick={this.addToShortlist.bind(this,{'courseId':info.courseId})} id={'shrt'+info.courseId} instid={instituteData.instituteId}
         product="Category" track="on" courseid={info.courseId}><span className="tup-view-details">Shortlist</span></button>}
    { this.props.showOAF && info.onlineFormInfo && (typeof  info.onlineFormInfo != "undefined" ) ? this.showOnlineApply(info, instituteData) :
    (typeof(this.props.deviceType) == 'undefined' || this.props.deviceType == 'mobile') ?
      <DownloadEBrochure listingId={info.courseId} listingName={instituteData.name} trackid={(typeof(this.props.ebTrackid) !='undefined' &&
          this.props.ebTrackid) ? this.props.ebTrackid : this.config.downloadBrochureTrackingId}
                         recoEbTrackid={(typeof(this.props.recoEbTrackid) !='undefined' && this.props.recoEbTrackid) ? this.props.recoEbTrackid : 273}
                         recoShrtTrackid={(typeof(this.props.recoShrtTrackid) !='undefined' && this.props.recoShrtTrackid) ?
                             this.props.recoShrtTrackid : 1089} isCallReco={this.props.isCallReco}
                         clickHandler={this.trackEvent.bind(this,'DownloadBrochure','Response')}/> :
      <button onClick={this.downloadEBRequest.bind(this,{'courseId':info.courseId,'courseName':info.name} ) } 
         cta-type="download_brochure" className={"ctp-btn ctpBro-btn button button--orange rippleefect tupleBrochureButton brchr_"+info.courseId}
         courseid={info.courseId}><span id={'ebTxt'+info.courseId} instid={instituteData.instituteId} product="Category" track="on"
                                        courseid={info.courseId}>Apply Now</span></button> }
      </div>
    }
  </div>

  );
  }
  applyOnline = redirectUrl  => () => {
      redirectUrl += "?tracking_keyid=" + this.props.applyNowTrackId;
      window.location.href = redirectUrl;
  };

  showOnlineApply(info, instituteData){
      //displayDate = data.onlineFormData['of_last_date'];
      if(this.props.deviceType === "desktop"){
          return(
          <button type='button' id="applynow" className='oafcta-btn' onClick={this.applyOnline(info.onlineFormInfo.of_external_url)}>
                  <i className="d-arrow"></i>Start Application</button>
          );
      }
      return(<ApplyNow courseId={info.courseId} instituteName={instituteData.name} isInternal={info.onlineFormInfo.external}
                        showLastDate={false} ctaName='Start Application' trackid = {this.props.applyNowTrackId}/>);
  }

  getExamsUniqueCount(examList) {
      let examSet = new Set();
      for (var i in examList) {
          examSet.add(examList[i].exam_name);
      }
      return examSet.size;
  }
  getCourseData(info,loadeMoreCoursesData){
    this.trackEvent('more_courses_link', 'click');
    let self = this;
    let instituteId = info.instituteId;
    let numberOfCoursesToLoad = 5;

    if(typeof self.state.remainingCourseIds[instituteId]!='undefined'){
          var courseIdArr = self.state.remainingCourseIds[instituteId]
    }else{
          var courseIdArr = loadeMoreCoursesData[instituteId];
    }

    let cloneCourseIdArr = courseIdArr.slice();
    let courseIdsToLoadArr = cloneCourseIdArr.splice(0, numberOfCoursesToLoad);
    let courseIdsToLoadStr = courseIdsToLoadArr.join();

    let numOfRemainingCourses = cloneCourseIdArr.length;
    let remainingCourseIdsStr = cloneCourseIdArr.join();

    let showLoadMore = true;
    let courseIdStr = '';
    for(var i in courseIdsToLoadArr){
      courseIdStr += '&courseIds='+courseIdsToLoadArr[i];
    }

    var url = APIConfig.GET_CTP_LOADMORE;
    var params = 'instituteId='+info.instituteId+courseIdStr;

    getRequest(url+'?'+params).then((response) => {
      let ResponseData   = response.data.data;
      let courseData   = response.data.data.courseTupleList;

      let courseTuples = courseData.map(
                            function(courseInfo, index){
                              let courseId = courseInfo.courseId;
                              let listingCloneObject = Object.assign({}, self.state.listings);
                              let courseObj = listingCloneObject[info.instituteId];
                              if(typeof courseObj=='undefined'){
                                courseObj = [];
                              }

                              self.setState({
                                listings: {
                                  ...self.state.listings,
                                  [info.instituteId]:courseObj.concat(self.getCourseTuple(courseInfo, 'more', info.url,info))
                                },
                                remainingCourseIds: {
                                    ...self.state.remainingCourseIds,
                                    [instituteId]:cloneCourseIdArr,
                                  }
                              });
                            }
                          );
    })
  }
  /* widget pos in 0-index based */
  getPCWArray(dataArray, widgetPos, widgetSize){
      if(!dataArray) {
          return null;
      }
      const widgetStartIndex = widgetPos*widgetSize;
      if(widgetStartIndex > dataArray.length)
          return false;
      const widgetEndingIndex = (widgetStartIndex + widgetSize) < dataArray.length ? (widgetStartIndex + widgetSize) : dataArray.length;
      return dataArray.slice(widgetStartIndex, widgetEndingIndex);
  }
  getTupleHTML(){
    const self = this;
    let tupleData = '';
    let listData  = new Array();
    let ocfOrderIndex = 0;
    const {loadMoreCourses} = this.props;
    let loadeMoreCoursesData  = {};
    const ocfLength = this.props.showOCF && this.props.ocfOrder ? this.props.ocfOrder.length : 0;
    for(let i in loadMoreCourses) {
      loadeMoreCoursesData[loadMoreCourses[i].instituteId] = loadMoreCourses[i].courseIds.slice(1);
    }

    tupleData = (typeof(this.props.recoData) != 'undefined' && this.props.recoData) ? this.props.recoData : self.props.categoryData.categoryInstituteTuple;
    let showPCW = false;
    let pcwCount = 0;
    if(typeof this.props.recoData === 'undefined'  && this.props.categoryData.categoryInstituteTuple &&
        this.props.categoryData.categoryInstituteTuple.length > 0 && this.props.categoryData.categoryInstituteTuple[0].courseTupleData.paid &&
        this.props.categoryData.totalInstituteCount > 10) {
        showPCW = this.props.categoryData.pcwData ? true : false;
    }
    for(let index in tupleData){
        let info = tupleData[index];
        if(showPCW && index % this.config.TUPLES_BEFORE_PCW === 0){
            let pcwData = this.getPCWArray(this.props.categoryData.pcwData.popularInstituteTuple, pcwCount, this.config.PCW_COUNT);
            if(pcwData) {
                listData.push(<PCW key={'PCW_' + pcwCount} nonPWALinks = {this.props.isPdfCall}
                                   aggregateReviewConfig={this.props.categoryData.pcwData.aggregateRatingConfig}
                                   showPCW={showPCW} tupleData={pcwData} gaTrackingCategory={this.props.gaTrackingCategory}/>);
                pcwCount++;
            }
        }
        if(!info.courseTupleData.courseUrl)
            continue;
        let moreCourseLength = '';
        moreCourseLength = (typeof self.state.remainingCourseIds!='undefined' && info.instituteId in self.state.remainingCourseIds) ?self.state.remainingCourseIds[info.instituteId].length: (typeof loadeMoreCoursesData[info.instituteId] != 'undefined' ? loadeMoreCoursesData[info.instituteId].length : 0);

        if(this.props.showStateData && this.props.categoryData.requestData.pageNumber === 1 && index == this.props.categoryData.totalInstituteCount ){
            listData.push(<h2 className="fallback-heading" key="headingState">{this.props.categoryData.fallbackResultCount} {typeof this.props.categoryData.requestData != 'undefined' && typeof this.props.categoryData.requestData.categoryData != 'undefined' && this.props.categoryData.requestData.fallbackHeading}</h2>);
        }

    listData.push(
    <div className="ctpSrp-tuple" key={index}>
       <div className="ctp-SrpLst">

                 <div className="ctp-SrpDiv">
                      <div className="ctpSrp-Lft">
                          {(self.props.isPdfCall) ? <a href={addingDomainToUrl(info.url, self.props.config.SHIKSHA_HOME)}
                                                       onClick={self.trackEvent.bind(self,'ChooseILP','click')}>
                              {(this.props.isImageLazyLoad) ? <Lazyload offset={100} once> <img alt={info.name} src={info.instituteThumbUrl}/></Lazyload> : <img alt={info.name} src={info.instituteThumbUrl}/> }
                          </a> :<Link to={removeDomainFromUrl(info.url)} onClick={self.handleClickOnInstitute.bind(self,info, index)}>
                          {(this.props.isImageLazyLoad) ? <Lazyload offset={100} once> <img alt={info.name} src={info.instituteThumbUrl}/></Lazyload> : <img alt={info.name} src={info.instituteThumbUrl}/> }                            
                        </Link>}
                      </div>
                      <div className="ctpSrp-Rgt">
                        <p className="ctpIns-tl">
                        {(self.props.isPdfCall) ?
                            <a href={addingDomainToUrl(info.url, self.props.config.SHIKSHA_HOME)}
                               onClick={self.handleClickOnInstitute.bind(self, info, index)}>{info.name.length>55?info.name.substring(0,52)+'...': info.name}</a>
                            : <Link to={removeDomainFromUrl(info.url)} onClick={self.handleClickOnInstitute.bind(self,info, index)}>{info.name.length>55?info.name.substring(0,52)+'...': info.name}</Link>}
                        </p>
                          <p className="ctp-cty">{info.courseTupleData.displayLocationString}</p>
                          {info.multiLocationInstituteUrl!=null?<a href={info.multiLocationInstituteUrl}>+more branches</a>:''}
                          {self.getAdmissionLink(info.admissionUrl,self.props.isPdfCall,info,index)}
                        {(typeof(self.props.deviceType) == 'undefined' || self.props.deviceType == 'mobile') ? <Shortlist listingId={info.courseTupleData.courseId} trackid={(typeof(this.props.srtTrackId) != 'undefined' && this.props.srtTrackId) ? this.props.srtTrackId : this.config.shortlistTrackingId} recoShrtTrackid={(typeof(self.props.recoShrtTrackid) !='undefined' && self.props.recoShrtTrackid) ? self.props.recoShrtTrackid : 1089} recoPageType="EBrochure_RECO_Layer" recoActionType="EBrochure_RECO_Layer" actionType="NM_category_shortlist" pageType="NM_Category" sessionId="" visitorSessionid="" isCallReco={self.props.isCallReco} clickHandler={self.trackEvent.bind(self,'Shortlist','Response')}/> : <i id={'shrt_'+info.courseTupleData.courseId} onClick={self.addToShortlist.bind(self,{'courseId':info.courseTupleData.courseId})} className={"ctp-shrtlst rippleefect tupleShortlistButton shrt_"+info.courseTupleData.courseId} data-type="shortlistStar"></i> }
                      </div>
                     {info.courseTupleData.usp && this.props.showUSPLda ?
                         <div className="highligt-msg"><i className="pwa-header-icon bulb"></i>
                         <span className="highlight-msg-txt">{info.courseTupleData.usp}</span>
                     </div> : ''}
                </div>
                <div id="courseTuple">
                {self.getCourseTuple(info.courseTupleData,'', info.url,info)}
                {self.state.listings[info.instituteId]}
                </div>
        </div>
        {moreCourseLength>0?
        <div className="ctp-shwMr">
                  {moreCourseLength>1?
            <a onClick={self.getCourseData.bind(self, info,loadeMoreCoursesData )}>+{moreCourseLength} more courses</a>:<a onClick={self.getCourseData.bind(self, info,loadeMoreCoursesData )}>+{moreCourseLength} more course</a>}
        </div>:''}
      </div>)
        if((typeof(this.props.deviceType) == 'undefined' || this.props.deviceType == 'mobile') && typeof this.props.recoData == "undefined") {
            if (index == 4) {
                listData.push(
                    <DFPBannerTempalte key={"Dfpbanner+" + index} bannerPlace="CTP_Mobile1"/>
                )
            }
            if (index == 9) {
                listData.push(
                    <DFPBannerTempalte key={"Dfpbanner+" + index} bannerPlace="CTP_Mobile2"/>
                )
            }
            if(index == 5){
                {this.props.showOCF && ocfLength > ocfOrderIndex ?
                    listData.push(<OCF key={'ocf'+index} filters={self.props.filters} displayName={self.props.displayName}
                                   shortName={self.props.shortName} pageUrl={self.props.pageUrl} gaTrackingCategory={this.props.gaTrackingCategory}
                                   defaultAppliedFilters={self.props.defaultAppliedFilters} filterName={self.props.ocfOrder[ocfOrderIndex++]}/>)
                    : '' }
            }
            if(index == 9){
                {this.props.showOCF && ocfLength > ocfOrderIndex ?
                    listData.push(<OCF key={'ocf'+index} filters={this.props.filters} displayName={this.props.displayName}
                                   shortName={this.props.shortName} pageUrl={this.props.pageUrl} gaTrackingCategory={this.props.gaTrackingCategory}
                                   defaultAppliedFilters={this.props.defaultAppliedFilters} filterName={this.props.ocfOrder[ocfOrderIndex++]}/>)
                    : '' }
            }
            if(index == 14){
                {this.props.showOCF && ocfLength > ocfOrderIndex ?
                    listData.push(<OCF key={'ocf'+index} filters={this.props.filters} displayName={this.props.displayName}
                                   shortName={this.props.shortName} pageUrl={this.props.pageUrl} gaTrackingCategory={this.props.gaTrackingCategory}
                                   defaultAppliedFilters={this.props.defaultAppliedFilters} filterName={this.props.ocfOrder[ocfOrderIndex++]}/>)
                    : '' }
            }
        } else if(this.props.deviceType === 'desktop' && this.props.pageType === "categoryPage" && typeof this.props.recoData == "undefined"){
            if (index == 3) {
                listData.push(
                    <div key={"DFPdiv"+index} className="ad-slotDiv">
                    <DFPBannerTempalte key={"Dfpbannerleft" + index} bannerPlace="CTP_Banner1"/>
                    <DFPBannerTempalte key={"Dfpbannermiddle" + index} bannerPlace="CTP_Banner2"/>
                    <DFPBannerTempalte key={"Dfpbannerright" + index} bannerPlace="CTP_Banner3"/>
                    </div>
                );
            }
            if (index == 16) {
                listData.push(
                    <div key={"DFPdiv"+index} className="ad-slotDiv">
                    <DFPBannerTempalte key={"Dfpbanner+left+" + index} bannerPlace="CTP_Banner4"/>
                    <DFPBannerTempalte key={"Dfpbanner+middle+" + index} bannerPlace="CTP_Banner5"/>
                    <DFPBannerTempalte key={"Dfpbanner+right+" + index} bannerPlace="CTP_Banner6"/>
                    </div>);
            }
        }
    }
    return listData;
  }

  getAdmissionLink(admissionLink, pdfCall,instituteData,index){
    if(admissionLink == null){
      return null;
    }
    else{
      if(pdfCall && this.props.deviceType == 'mobile'){
        return (
          <p><a onClick={this.trackEvent.bind(this,'Popular_Colleges','Admission_details_Click')} href={ addingDomainToUrl(admissionLink, this.props.config.SHIKSHA_HOME)} className="vw-adLnk">Admissions 2019</a></p>
        )
      }
      else{
        return (
        <p><Link onClick={this.handleClickOnInstitute.bind(this, instituteData,index,'AdmissionLink','Popular_Colleges','Admission_details_Click')} to={admissionLink} className="vw-adLnk">Admissions 2019</Link></p>
        )
      }
    }
  }


  getTupleHTMLNew(){
     var self = this;
     var listData  = new Array();

     var tupleData = this.props.categoryData.coursesTuple;
     var instituteData = {};
     var $tupleIndex = 0;
     var pageNum = typeof this.props.pageNumber !='undefined' ? this.props.pageNumber : 1;
     for(var index in tupleData){
        $tupleIndex++;
        var info = tupleData[index];
        instituteData = {};
        if(typeof info.primaryParentName !='undefined' && info.primaryParentName){
          instituteData.name =info.primaryParentName;
          instituteData.url = self.props.categoryData.listingUrl;
        }
        listData.push(
            <div className="ctpSrp-tuple" key={index}>
                <div className="ctp-SrpLst">
                    <div id="courseTuple">
                      {self.getCourseTuple(tupleData[index],'', instituteData.url,instituteData)}
                    </div>
                </div>
            </div>)
        if ($tupleIndex == 3){
            listData.push(
                <DFPBannerTempalte key={"Dfpbanner+"+index} bannerPlace={'C1_'+this.props.deviceType}/>
            )
        }
        if ($tupleIndex == 7){
            listData.push(
                <DFPBannerTempalte key={"Dfpbanner+"+index} bannerPlace={'C2_'+this.props.deviceType}/>
            )
        }
        if ($tupleIndex == 10){
            listData.push(
                <DFPBannerTempalte key={"Dfpbanner+"+index} bannerPlace={'C3_'+this.props.deviceType}/>
            )
        }
        if ($tupleIndex == 14){
            listData.push(
                <DFPBannerTempalte key={"Dfpbanner+"+index} bannerPlace={'C4_'+this.props.deviceType}/>
            )
        }
     }
     return listData;
  }

  trackEvent = (actionLabel,label)=>{
      if(typeof this.props.gaTrackingCategory === 'undefined' || !this.props.gaTrackingCategory)
         return;
      const categoryType = this.props.gaTrackingCategory;
      Analytics.event({category : categoryType, action : actionLabel, label : label});
  }

  render(){
    if(typeof this.props.onlycoursTuple !='undefined' && this.props.onlycoursTuple){
      let listData            = this.getTupleHTMLNew();      
      return( <div className="ctpSrp-contnr">
                       {listData}                        
                            {(this.props.showPagination!='undefined' && this.props.showPagination) && <Pagination categoryData={this.props.categoryData} isSrp = {this.props.isSrp} gaTrackingCategory = {this.props.gaTrackingCategory} />}
                    </div>)
    
    }


    let listData            = this.getTupleHTML();
    let totalInstituteCount = (typeof this.props.recoData == 'undefined' && this.props.categoryData.totalInstituteCount!='undefined') ? this.props.categoryData.totalInstituteCount : 0;
    let headingMobile       = (typeof this.props.recoData == 'undefined' && this.props.categoryData.requestData ) ? ((this.props.categoryData.requestData.categoryData!=undefined) ?this.props.categoryData.requestData.categoryData.headingMobile : null):null;
    return( <div className="ctpSrp-contnr">
                     {listData}
                     { (typeof this.props.recoData == 'undefined') ?
                         <React.Fragment>
                             {this.props.showInTable != 'undefined' && this.props.showInTable ?
                                 (<React.Fragment>
                                     <FullPageLayer data={this.getTableComponent()}
                                                heading={`${totalInstituteCount} ${headingMobile}`}
                                                isOpen={this.state.fullLayer}
                                                onClose={this.closeTableComponentLayer.bind(this)} desktopTableData = {this.props.isPdfCall}/>
                                 <a onClick = {this.openTableComponentLayer.bind(this)} className="shwDat-link" id="rnkTbl-btn">Show data in table</a>
                                 </React.Fragment>) : null}
                             {(this.props.showPagination!='undefined' && this.props.showPagination) && <Pagination categoryData={this.props.categoryData} gaTrackingCategory={this.props.gaTrackingCategory} isSrp = {this.props.isSrp} />}
                         </React.Fragment>: null
                     }
                  </div>)
    }
}

CategoryTuple.defaultProps = {
  isImageLazyLoad : true, showInTable : true, isSrp: false, showOAF : false, showUSPLda : false, recoShrtTrackid : 0, recoEbTrackid : 0, showOCF : false, deviceType: "mobile"
};

function mapDispatchToProps(dispatch){
  return bindActionCreators({ storeCourseDataForPreFilled,storeInstituteDataForPreFilled }, dispatch); 
}

export default connect(null,mapDispatchToProps)(CategoryTuple);
