import React from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import {Link , withRouter} from "react-router-dom";

import config from './../../../../config/config';
import {addingDomainToUrl,Ucfirst} from './../../../utils/commonHelper';
import rankingConfig from './../config/rankingConfig';
import {getRupeesDisplayableAmount} from './../../listing/course/utils/listingCommonUtil';

import {storeCourseDataForPreFilled} from './../../listing/course/actions/CourseDetailAction';
import {storeInstituteDataForPreFilled} from './../../listing/institute/actions/InstituteDetailAction';

import Lazyload from './../../reusable/components/Lazyload';
import Shortlist from './../../common/components/Shortlist';
import DownloadEBrochure from './../../common/components/DownloadEBrochure';
import Analytics from './../../reusable/utils/AnalyticsTracking';
import RankingPageTupleReviewRating from './RankingPageTupleReviewRating';
// import ExamList from './ExamList';
import {DFPBannerTempalte} from './../../reusable/components/DFPBannerAd';
import Loadable from "react-loadable";
import OCF from './../../search/components/OCF' ;
import CollegeShortlistWidget from "../../search/components/CollegeShortlistWidget";
import Feedback from "../../common/components/feedback/Feedback";

// const OCF = Loadable({
//     loader: () => import('./../../search/components/OCF' webpackChunkName: 'OCF' ),
//     loading() {
//         return null
//     },
// });

class RankingPageTuple extends React.Component {
  constructor(props){
    super(props);
    this.dfp1Flag = false;
    this.dfp2Flag = false;
    this.instNameCharLimit = 65;
    this.courNameCharLimit = 30;
    if(this.props.deviceType == 'desktop'){
      this.instNameCharLimit = 45;
      this.courNameCharLimit = 60;
    }
  }

  isAllPublisherPage(selectedRankingSource = null) {
    return (selectedRankingSource == null || selectedRankingSource.toLowerCase().indexOf('all publisher') != -1);
  }

  render(){
    this.dfp1Flag = this.dfp2Flag = false;
    let ocfOrderIndex = 0;
    const ocfLength = this.props.showOCF && this.props.ocfOrder ? this.props.ocfOrder.length : 0;

    let tupleHtml = [], availableSources = [], courseName = null, instName = null, publisherArr = [], singleSourceCls = '', dbListingId = 0, dbPage = '', dbListingType = '', dbListingName = '',  feeStr = '', durationStr = '', tupleCountForDfp = 0,selectedRankingSource = this.props.selectedRankingSource;
    if(this.props.tupleData != undefined && this.props.tupleData.length > 0){
      if(this.isAllPublisherPage(selectedRankingSource)  && (this.props.baseCourse == 75 || this.props.baseCourse == 101)){ // mba
        publisherArr = rankingConfig.mbaRankingPublishersShowOrder;
      }else if(this.isAllPublisherPage(selectedRankingSource) && this.props.baseCourse == 10){ // engg
        publisherArr = rankingConfig.btechRankingPublishersShowOrder;
      }
      this.props.tupleData.forEach(
        (currObj, iter) => {
          tupleCountForDfp++;
          availableSources = [];
          if(this.isAllPublisherPage(selectedRankingSource) && this.props.isSearchTuple == false){ //default ranking source selected
            singleSourceCls = '';
            if(Object.keys(currObj.rankingData).length > 0){
              publisherArr.forEach(
                (publisherId, i) => {
                  if(currObj.rankingData != null && currObj.rankingData[publisherId] != undefined){
                    availableSources.push(
                      <div key={'instTupleSource_'+currObj.instituteId+'_'+publisherId} className='rank-hold'>
                      <div className="f11_normal clr_6 rc-top">{currObj.rankingData[publisherId].name != null ? currObj.rankingData[publisherId].name : null}</div>
                      <div className="f16_bold clr_0 rc-btm">{currObj.rankingData[publisherId].rank}</div>
                      </div>
                    );
                  }else{
                    availableSources.push(
                      <div key={'instTupleSource_'+currObj.instituteId+'_'+publisherId} className='rank-hold'>
                      <div className="f11_normal clr_6 rc-top">{rankingConfig.popularPublisherNames[publisherId]}</div>
                      <div className="clr_0 rc-btm"><div className={(this.props.deviceType == 'desktop' ? "txt-right" : "txt-middle") + " lead"}>Not Ranked</div></div>
                      </div>
                    );
                  }
                }
              );
            }else{
              availableSources.push(
                <div key={'instTupleSource_'+iter+'_'+currObj.instituteId}>
                <div className="clr_0 txt-center"><div className=" lead">Not Ranked</div></div>
                </div>
              );
            }
          }else{ //some ranking source selected
            singleSourceCls = 'source-selected';
            if(currObj.rankingData != null && Object.keys(currObj.rankingData).length > 0){
              Object.keys(currObj.rankingData).forEach(
                publisherId => {
                  availableSources.push(
                    <div key={'instTupleSource_'+currObj.instituteId+'_'+publisherId}>
                      <div className="f16_bold clr_0 txt-center"><span className="circleText">{currObj.rankingData[publisherId].rank}</span></div>
                    </div>
                  );
                }
              );
            }else{
              availableSources.push(
                <div key={'instTupleSource_'+currObj.instituteId}>
                  <div className="clr_0 txt-center"><div className="lead">Not Ranked</div></div>
                </div>
              );
            }
          }
          
            
          courseName = null;
          if(currObj.specializationData != null){
            courseName = currObj.specializationData.name
            if(courseName.length > this.courNameCharLimit){
              courseName = courseName.substr(0, this.courNameCharLimit);
              courseName += '...';
            }
            feeStr = durationStr = null;
            if(currObj.specializationData.fees != null){
              feeStr = <span className="fees">Fees <strong>&#8377; {getRupeesDisplayableAmount(currObj.specializationData.fees[0].fees_value, 2, true)}</strong></span>;
            }
            if(currObj.specializationData.durationValue != null && currObj.specializationData.durationUnit != null){
              durationStr = <span className="duration">Duration <strong>{currObj.specializationData.durationValue+' '+Ucfirst(currObj.specializationData.durationUnit)}</strong></span>;
            }
          }
          instName = null;
          if(currObj.name != null){
            instName = currObj.name;
            if(this.props.deviceType == 'mobile' && instName.length > this.instNameCharLimit){
              instName = instName.substr(0, this.instNameCharLimit);
              instName += '...';
            }
          }
          if(iter == 3 && this.props.isSearchTuple == false){
            this.dfp1Flag = true;
            tupleHtml.push(<DFPBannerTempalte key={iter} bannerPlace={this.props.deviceType == 'desktop' ? 'RP_Desktop1' : 'RP_Mobile1'}/>);
          }
          if(iter == 4 && this.props.deviceType == 'mobile' && this.props.showOCF && ocfLength > ocfOrderIndex){
                tupleHtml.push(<OCF key={'ocf'+ocfOrderIndex} filters={this.props.filters} displayName={this.props.displayName}
                               shortName={this.props.shortName} pageUrl={this.props.pageUrl} gaTrackingCategory={this.props.gaTrackingCategory}
                               defaultAppliedFilters={this.props.defaultAppliedFilters} filterName={this.props.ocfOrder[ocfOrderIndex++]} onClickHandler={this.rankingOCFClick.bind(this)}/>) 
          }    

            if(iter == 4 && this.props.isSearchTuple == false && this.showCollegeShortlistWidget()){
                tupleHtml.push(<CollegeShortlistWidget key="collegeShortlistWidget" deviceType={this.props.deviceType}
                                                       fullLengthWidget={true} gaTrackingCategory={this.props.gaTrackingCategory}/>)
            }
            if(iter == 7 && this.props.isSearchTuple == false){
            this.dfp2Flag = true;
            tupleHtml.push(<DFPBannerTempalte key={iter} bannerPlace={this.props.deviceType == 'desktop' ? 'RP_Desktop2' : 'RP_Mobile2'}/>);
          }

          if(iter == 8 && this.props.deviceType == 'mobile' && this.props.showOCF && ocfLength > ocfOrderIndex){
                tupleHtml.push(<OCF key={'ocf'+ocfOrderIndex} filters={this.props.filters} displayName={this.props.displayName}
                               shortName={this.props.shortName} pageUrl={this.props.pageUrl} gaTrackingCategory={this.props.gaTrackingCategory}
                               defaultAppliedFilters={this.props.defaultAppliedFilters} filterName={this.props.ocfOrder[ocfOrderIndex++]} onClickHandler={this.rankingOCFClick.bind(this)}/>) 
          }          

          if(iter == 10 && this.props.isSearchTuple == false){
            tupleHtml.push(<DFPBannerTempalte key={iter} bannerPlace={this.props.deviceType == 'desktop' ? 'RP_Desktop3' : 'RP_Mobile3'}/>);
          }
          if(iter == 12 && this.props.deviceType == 'mobile' && this.props.showOCF && ocfLength > ocfOrderIndex){
                tupleHtml.push(<OCF key={'ocf'+ocfOrderIndex} filters={this.props.filters} displayName={this.props.displayName}
                               shortName={this.props.shortName} pageUrl={this.props.pageUrl} gaTrackingCategory={this.props.gaTrackingCategory}
                               defaultAppliedFilters={this.props.defaultAppliedFilters} filterName={this.props.ocfOrder[ocfOrderIndex++]} onClickHandler={this.rankingOCFClick.bind(this)}/>) 
          }
          if(this.props.deviceType == 'mobile' && iter === 20){
              tupleHtml.push(<Feedback key={'feedback_'+iter} pageId={this.props.rankingPageId} pageType={'RP'} deviceType={'mobile'} url={this.props.location.pathname+this.props.location.search+this.props.location.hash} />);
          }
        if(currObj.specializationData != null){
            dbListingId = currObj.specializationData.courseId;
            dbListingName = currObj.specializationData.name;
            dbPage = 'rankingPage';
            dbListingType = 'course';
        }else{
            dbListingId = currObj.courseData.courseId;
            dbListingName = currObj.name;
            dbPage = 'course';
            dbListingType = 'course';
        }
          tupleHtml.push(
            <section key={'tupleInstId_'+currObj.instituteId} className="rank_tuplev1 bg-white clear_float ranking-flex">
              <div className={'clear_float desk-col '+singleSourceCls}>
               <div className="flt_left rank_section">
                {availableSources}
               </div>
               <div className="flt_right rank_dtl">
                 {this.props.deviceType == 'desktop' ? <div className="tuple-inst-img"><Lazyload offset={100} once><img width="100" alt={currObj.name} title={currObj.name} height="78" src={currObj.headerImage}></img></Lazyload></div> : null}
                 <div className="tuple-inst-info">
                 {this.props.deviceType == 'mobile' ? <Shortlist className="pwa_sprite star_slct" actionType="rankingMobile" listingId={currObj.courseData.courseId} trackid="344"  recoEbTrackid="1096" recoShrtTrackid="1097" recoPageType="EBrochure_RECO_Layer" recoActionType="EBrochure_RECO_Layer" pageType="NM_Ranking" sessionId="" visitorSessionid="" isCallReco={this.props.isCallReco} clickHandler={this.trackEvent.bind(this,'Shortlist-Star','tuple_click')} page={"rankingPage"}/> : null}

                 {this.props.deviceType == 'desktop' ? <a className="rank_clg" onClick={this.handleClickOnInstitute.bind(this)} href={addingDomainToUrl(currObj.url, config().SHIKSHA_HOME)} title={currObj.name}><h4 className="f14_bold link">{instName}</h4></a>:<Link className="rank_clg" onClick={this.handleClickOnInstitute.bind(this, currObj)} to={currObj.url} title={currObj.name}><h4 className="f14_bold link">{instName}</h4></Link>}

                 <span className="rank_loc f12_normal clr_6">
                    {currObj.location != null ? <span><i className="pwa_sprite clg-loc"></i>{currObj.location}</span> : null}
                  </span>

                  <div className="adminsn-singlecol">
                    <div className="flex_v">
                      <RankingPageTupleReviewRating deviceType={this.props.deviceType} gaTrackingCategory={this.props.gaTrackingCategory}  admissionUrl={currObj.admissionUrl} allCourseUrl={currObj.allCoursePageUrl} reviewData={currObj.reviewData} aggregateRatingConfig={this.props.aggregateRatingConfig} />
                    </div> 
                    {this.getFeesData(currObj)}
                  </div>

                  {courseName != null && this.props.deviceType == 'mobile' ? <span className="rank_loc f12_normal clr_6"><Link title={currObj.specializationData.name} to={currObj.specializationData.url} onClick={this.handleClickOnCourse.bind(this, currObj)} className='link-to-admsn'>{courseName}</Link></span> : null}

                  {courseName != null && this.props.deviceType == 'desktop' ? <span className="rank_loc f12_normal clr_6"><a title={currObj.specializationData.name} href={addingDomainToUrl(currObj.specializationData.url, config().SHIKSHA_HOME)} onClick={this.handleClickOnCourse.bind(this)} className='link-to-admsn'>{courseName}</a></span> : null}
                    { this.props.deviceType == 'desktop' ?
                      <div className="admsn_v1">
        
                          {this.getAdmissionLink(currObj)}
                      
                        {currObj.allCoursePageUrl !=null ? <div className="flex_r">
                           <Link to={currObj.allCoursePageUrl} onClick={this.handleClickOnInstitute.bind(this, currObj,'CourseLink','Courses', 'tuple_click')}>Courses & Fees</Link>
                        </div>: ''}
                        {currObj.placementPageUrl !=null ? <div className="flex_r">
                           <Link to={currObj.placementPageUrl} onClick={this.handleClickOnInstitute.bind(this, currObj,'PlacementLink','Placements', 'tuple_click')}>Placements</Link>
                        </div>: ''}
                      </div> : ''
                   }
                  </div>

               </div>
             </div>
               <div className="clear_float tuple_btns ctp-SrpBtnDiv">
               {this.props.deviceType == 'mobile' ?
                 <Shortlist className="pwa-shrtlst-ico" actionType="rankingMobile" listingId={currObj.courseData.courseId} trackid="344"  recoEbTrackid="1096" recoShrtTrackid="1097" recoPageType="EBrochure_RECO_Layer" recoActionType="EBrochure_RECO_Layer" pageType="NM_Ranking" sessionId="" visitorSessionid="" isCallReco={this.props.isCallReco} clickHandler={this.trackEvent.bind(this,'Shortlist','tuple_click')} isButton={true} page = {"rankingPage"}/> : <button type="button" name="button" onClick={this.downloadEBRequest.bind(this,{'listingId':currObj.courseData.courseId,'listingName':currObj.name, 'listingType':'course', 'ebTrackid' :235, 'pageType':'ND_Ranking'}, 'Shortlist')}  cta-type="shortlist" className={"ctp-btn ctpComp-btn button button--secondary rippleefect tupleShortlistButton shrt"+currObj.courseData.courseId} courseid={currObj.courseData.courseId} customcallback="listingShortlistCallback" customactiontype="ND_Ranking"><span id={'shrt'+currObj.courseData.courseId} instid={currObj.instituteId} product="Ranking" track="on" courseid={currObj.courseData.courseId}>Shortlist</span></button> }

                 {this.props.deviceType == 'mobile' ? <DownloadEBrochure buttonText="Apply Now" listingId={dbListingId} trackid="343" recoEbTrackid="1096" recoShrtTrackid="1097" isCallReco={this.props.isCallReco} clickHandler={this.trackEvent.bind(this,'Apply Now','tuple_click')} page ={dbPage}/> : <button type="button" name="button" onClick={this.downloadEBRequest.bind(this,{'listingId':dbListingId, 'listingName':dbListingName, 'listingType':dbListingType, 'ebTrackid' :234, 'pageType':'ND_Ranking',}, 'DownloadBrochure')}  cta-type="download_brochure" className={"ctp-btn ctpBro-btn button button--orange rippleefect tupleBrochureButton brchr_"+dbListingId} courseid={currObj.courseData.courseId} listingid={dbListingId} type={dbListingType}><span id={'ebTxt'+dbListingId} instid={currObj.instituteId} product="Ranking" track="on" courseid={dbListingId}>Apply Now</span></button>}
               </div>
               { this.props.deviceType == 'mobile' ?
                      <div className="admsn_v1">
                          {this.getAdmissionLink(currObj)}
                       {currObj.allCoursePageUrl !=null ? <div className="flex_r">
                           <Link to={currObj.allCoursePageUrl} onClick={this.handleClickOnInstitute.bind(this, currObj,'CourseLink','Courses', 'tuple_click')}>Courses & Fees</Link>
                        </div>: ''}
                        {currObj.placementPageUrl !=null ? <div className="flex_r">
                           <Link to={currObj.placementPageUrl} onClick={this.handleClickOnInstitute.bind(this, currObj,'PlacementLink','Placements', 'tuple_click')}>Placements</Link>
                        </div>: ''}
                      </div> : ''
                   }
            </section>
          );
        }
      );
      if(this.dfp1Flag == false && this.props.isSearchTuple == false){
        tupleHtml.push(<DFPBannerTempalte key="rp-dfp1" bannerPlace={this.props.deviceType == 'desktop' ? 'RP_Desktop1' : 'RP_Mobile1'}/>);
      }
      if(tupleCountForDfp > 3 && this.dfp2Flag == false && this.props.isSearchTuple == false){
        tupleHtml.push(<DFPBannerTempalte key="rp-dfp2" bannerPlace={this.props.deviceType == 'desktop' ? 'RP_Desktop2' : 'RP_Mobile2'}/>);
      }
    }
    return (
        <div id="rankingTupleWrapper" className="ranking_pwa_wrapper">
          {selectedRankingSource != null && this.props.isSearchTuple == false ? <div className="ranking_rslts">Showing <strong>{this.props.selectedRankingSource}</strong> All India Ranking</div> : null}
          {tupleHtml}
        </div>
    );
  }
  showCollegeShortlistWidget(){
      if(!this.props.appliedFiltersData)
          return false;
      const appliedFilters = this.props.appliedFiltersData;
      if(appliedFilters.baseCourse && Array.isArray(appliedFilters.baseCourse) && appliedFilters.baseCourse.indexOf(10) !== -1)
          return true;
      if(appliedFilters.streams && Array.isArray(appliedFilters.streams) && appliedFilters.streams.indexOf(2) !== -1)
          return true;
      return false;

  }
  rankingOCFClick(tuple){
    // console.log(tuple.url);
    // window.history.pushState({urlPath: tuple.url}, "", tuple.url);
    this.props.history.push(tuple.url);
  }

  trackEvent(actionLabel,label)
    {
      Analytics.event({category : this.props.gaTrackingCategory, action : actionLabel, label : label});
    }


  getAdmissionLink(currObj){
    if(!currObj.admissionUrl){
      return null;
    }
    else{
        return(
          <div className="first-spancol flex_r"><Link to={currObj.admissionUrl} className='link-to-admsn' onClick={this.handleClickOnInstitute.bind(this, currObj,'AdmissionLink','Admissions', 'tuple_click')} >Admissions 2019</Link></div>
        )
    }
  }

  getFeesData(currObj){
    if(!currObj.courseData.fees){
      return null
    }else{
      return(
       <div className="flex_v">Fees: {currObj.courseData.fees['feesUnitName'] ==='INR' ? '₹' : currObj.courseData.fees['feesUnitName']}  {getRupeesDisplayableAmount(currObj.courseData.fees['feesValue'], 2)}</div>
      )
    }
  }


  handleClickOnInstitute(instituteData,fromWhere='',trackCategory='',trackEvent='')
  {
      if(trackEvent!='' && trackCategory!=''){
        this.trackEvent(trackCategory ,trackEvent);
      }else{
        this.trackEvent('ILP','tuple_click');
      }
      if(typeof instituteData != 'undefined' && typeof instituteData == 'object') {
        var data =  [];
        data.instituteName = instituteData.name;
        data.location = instituteData.location;
        data.ownership = instituteData.instituteExtraData.ownership;
        data.autonomous = instituteData.instituteExtraData.autonomous;
        data.nationalImportance = instituteData.instituteExtraData.nationalImportance;
        data.univeristyTypeWithSpecification = instituteData.instituteExtraData.universityTypeWithSpecification;
        data.ugcApproved = instituteData.instituteExtraData.ugcApproved;
        data.naacAccreditation = instituteData.instituteExtraData.naacAccreditation;
        data.establishYear = instituteData.instituteExtraData.estbYear;
        data.aiuMember = instituteData.instituteExtraData.aiuMember;
        data.headerImage = instituteData.headerImage;
        if(fromWhere =='AdmissionLink'){
          data.extraHeading = 'Admission 2019 - Cutoffs, Eligibility & Dates';
          data.pageName = 'Admission';
          data.fromWhere = "admissionPage";
        }else if(fromWhere == 'CourseLink'){
          data.fromWhere = "coursePage";
          data.PageHeading = 'Courses, Fees 2019';
        }else if(fromWhere == 'PlacementLink'){
          data.pageName = 'Placement';
          data.fromWhere = 'placementPage';  
          data.showFullSectionLoader = true;
          data.extraHeading = 'Placement - Highest & Average Salary Package';
        }
        this.props.storeInstituteDataForPreFilled(data);
      }
  }
  handleClickOnCourse(instituteData)
  {
    this.trackEvent('CLP','tuple_click');
    if(typeof instituteData != 'undefined'  && typeof instituteData == 'object'){
      let courseData = instituteData.specializationData
      if(typeof courseData != 'undefined' && courseData != null && typeof courseData == 'object')
      {
        var data = {};
        data.recognition = courseData.recognition;
        data.courseId = courseData.courseId;
        data.name = courseData.name;

        data.instituteName =  courseData.primaryName;
        data.durationValue = courseData.durationValue;
        data.durationUnit =  courseData.durationUnit;

        if(courseData.fees != null){
          data.fees = courseData['fees'][0]['fees_value'];
          data.feesUnit = courseData['fees'][0]['fees_unit_name'];
        }
        if(courseData.courseTypeInformation && courseData.courseTypeInformation.entry_course && courseData.courseTypeInformation.entry_course.course_level && courseData.courseTypeInformation.entry_course.credential){
          data.courseExtraInfo = {};
          data.courseExtraInfo.courseCredential = courseData.courseTypeInformation.entry_course.credential.name;
          data.courseExtraInfo.courseLevel = courseData.courseTypeInformation.entry_course.course_level.name;
          if(courseData.education_type){
            data.courseExtraInfo.extraInfo ={};
            data.courseExtraInfo.extraInfo.educationType = courseData.education_type.name;
          }
        }
        this.props.storeCourseDataForPreFilled(data);
      }
    }
  }
  downloadEBRequest = (params, gaLabel, e) => {
    let thisObj = e.currentTarget;
    if(typeof(window) !='undefined' && typeof(ajaxDownloadEBrochure) !='undefined'){
      this.trackEvent(gaLabel, 'Response');
      window.ajaxDownloadEBrochure(thisObj, params.listingId, params.listingType, params.listingName, params.pageType, params.ebTrackid, 1104, 0, 0, 1105);
    }
  }
}

function mapDispatchToProps(dispatch){
  return bindActionCreators({ storeCourseDataForPreFilled,storeInstituteDataForPreFilled }, dispatch);
}

RankingPageTuple.defaultProps = {
  deviceType : 'mobile',
  gaTrackingCategory : 'RANKING_PAGE_MOBILE'
}

export default connect(null,mapDispatchToProps)(withRouter(RankingPageTuple));