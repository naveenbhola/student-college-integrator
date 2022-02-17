/* eslint no-unused-vars: 0 */ 
/* eslint react/prop-types: 0 */ 


import React from 'react';
import style from './../assets/css/style.css';
import specificFile from './../assets/css/admission.css';
import commonCss from './../../course/assets/courseCommon.css';
import {getTextFromHtml} from './../../../../utils/stringUtility';
import {makeURLAsHyperlink} from './../../../../utils/urlUtility';
import ExamTuple from "./../../../search/components/Exam/ExamTuple";
import Analytics from './../../../reusable/utils/AnalyticsTracking';
import {addingDomainToUrl} from './../../../../utils/commonHelper';
import {storeChildPageDataForPreFilled} from './../../instituteChildPages/actions/AllChildPageAction';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import {Link} from 'react-router-dom';
import DownloadEBrochure from './../../../common/components/DownloadEBrochure';
import {ILPConstants, ULPConstants} from './../../categoryList/config/categoryConfig';

class Admission extends React.Component {
  constructor(props) {
    super(props);
    if(this.props.page == 'institute'){
        this.config = ILPConstants();
    }else{
        this.config = ULPConstants();
    }
  }

  trackEvent(action='')
  {

    var category = 'ILP';
    this.config = ILPConstants();
    if(this.props.page == "university"){
          category = 'ULP';
    //this.config = ULPConstants();
    }
    if(this.props.fromWhere=='admissionPage'){
      category = this.props.gaCategory;
    } 
    if(action ==''){
      action ='AdmissionWidget';
    }
    Analytics.event({category : category, action : action, label : 'Click'});
  }

  handleClickonAddmission(eventAction, PageHeading = ''){
    this.trackEvent();
    var data = {};
    if(this.props.instituteData.listingId){
      data.listingId = this.props.instituteData.listingId;
    }
    if(this.props.instituteData.instituteTopCardData){
      data.instituteTopCardData = this.props.instituteData.instituteTopCardData;
    }
    if(this.props.instituteData.reviewWidget){
      data.reviewWidget = this.props.instituteData.reviewWidget;
    }
    if(this.props.instituteData.currentLocation){
      data.currentLocation = this.props.instituteData.currentLocation;
    }
    if(this.props.instituteData.aggregateReviewWidget !='undefined'){
      data.aggregateReviewWidget = this.props.instituteData.aggregateReviewWidget; 
    }
    if(this.props.instituteData.anaCountString !='undefined'){
      data.anaCountString = this.props.instituteData.anaCountString; 
    }
    data.anaWidget = {};
    data.allQuestionURL = ''; 
    data.showFullLoader = false;
    data.PageHeading = 'Admission 2019 - Cutoffs, Eligibility & Dates';
    if(PageHeading !=''){
     data.PageHeading = PageHeading+data.PageHeading; 
    }
    data.fromWhere = "admissionPage";
    this.props.storeChildPageDataForPreFilled(data);
  }


  ExamWidget(examTuples) {

    const {config, isAmp} = this.props;
    var ExamData = [];
    var examData = [];
    if(isAmp){
      examData = this.props.admissionData.examList;
    }
    let gaCategory = this.props.page == 'university'? 'ULP' :'ILP';
    let trackingKeyId = this.props.page == 'university'? 3175 :3177;
    
    if(this.props.fromWhere == 'admissionPage'){
        gaCategory = this.props.gaCategory;
        if(this.props.deviceType == 'mobile'){
          trackingKeyId = this.props.page == 'university'? 3179 :3183;
        }else{
          trackingKeyId = this.props.page == 'university'? 3181 :3185;
        }
    }
    let examList = null;
    if(examTuples){
      examList = examTuples.map((tuple, i) =>
          <ExamTuple key={"examTuple" + i} acceptingExam={null} tupleData = {tuple} isMobile={this.props.deviceType == "mobile"?true:false}  gaCategory ={ gaCategory} trackingKeyId={trackingKeyId}/>
      );
    }

    for(var i in examData){
      if(isAmp){
        var ele  = (
          <li  key={'examData'+i} className='ga-analytic' data-vars-event-name="VIEW_EXAM">
             <a className="rippleefect" href={addingDomainToUrl(examData[i].url,config.SHIKSHA_HOME)} onClick = {this.trackEvent.bind(this)} >
               <p className='f14 color-6'>
                 <strong className='block m-3btm'>
                   <span >{examData[i].name+' '+examData[i].year} </span>
                 </strong>
               </p>
               <i className="lft-frwd"></i>
             </a>
           </li>
           )
      }
        ExamData.push(ele);

    }

    return (
      <React.Fragment>
        {(!isAmp) ?
          <div className="_subcontainer _noPad">
                    {examList &&  examList.length != 0 && ( this.props.fromWhere == 'instiutePage' ? (<h3 className="offeredH3">Exams Offered by {this.props.page == 'university'? "University":"Institute"}</h3>):null) }
                <div>
                {examList && examList.length != 0 &&  <div className='pwaexams_container'>
                  <div className='listof_exams auto_clear'>{examList}</div>
                </div>}
                </div>
              </div>:
          <section>
           <div className='data-card m-5btm'>
             <div className='card-cmn color-w'>
               {this.props.fromWhere == 'instiutePage' ? (<h3 className="offeredH3">Exams Offered by University</h3>):null }
               <ul className='cls-ul'>
                {ExamData}
               </ul>
             </div>
           </div>
          </section>
        }
      </React.Fragment>
     )

  }


  AdmissionstaticWidget = ( ) => {
    const {config,admissionData, isAmp} = this.props;
    const admmsionUrl  =   addingDomainToUrl(admissionData.url,config.SHIKSHA_HOME);
    return(
        <React.Fragment>
        {(admissionData.showAdmissionFlag === true) ? (
          <React.Fragment>
            { (!isAmp) ?
              (  <div className="find-schlrSec h0">
                  <div className="find-schlrSec-inr h0">
                  <p>{"Want to know the eligibility, admission process and important dates of all "+this.props.instituteName+" courses?"}</p>
                    <a href={admmsionUrl} className="button button--secondary rippleefect" onClick = {this.trackEvent.bind(this)}>View Admissions Info</a>
                  </div>
                </div>) :
               (<section>
                  <div className='data-card m-5btm'>
                     <div className='card-cmn color-w'>
                       <h2 className='f14 color-3 font-w6 m-btm'>{"Want to know the eligibility, admission process and important dates of all "+this.props.instituteName+" courses?"}</h2>
                       <a href={admmsionUrl} className="btn btn-secondary color-w color-b f14 font-w6 ga-analytic" on={'tap:',this.trackEvent.bind(this)} data-vars-event-name="VIEW_ADMISSION_INFO">View Admissions Info</a>
                     </div>
                  </div>
               </section>)
            }
          </React.Fragment>  
        ) : null
      }
  </React.Fragment>
    )

}

  render() {

    const {config,admissionData, isAmp,tupleData} = this.props;
    const admmsionUrl  =   admissionData.url;
    if(this.props.showOnlyExam){
      var pageHeading = this.props.page +' Exams';
      return(
            <section className='listingTuple clearFix' id="admission">
                <div className="_container">
                  {tupleData && tupleData.length != 0 && <h2 className="tbSec2 uperCase"> {pageHeading}</h2>}
                  {tupleData && tupleData.length != 0  && this.ExamWidget(tupleData) }
                </div>
            </section>        
        )
    }   
    return (
    <React.Fragment>
      { (!isAmp) ?
        <section className='listingTuple' id="admission">
              <div className="_container">
                <h2 className="tbSec2">Admissions & Cut-Offs</h2>
                { admissionData.admissionDetails !=null ?
                  (<div className="_subcontainer psrltv">
                           <div className="adm-DivSec">
                             <div dangerouslySetInnerHTML={{
                                 __html: getTextFromHtml(admissionData.admissionDetails, 500)
                               }}></div>
                             <div className="gradient-col gardient-flex" id="viewMoreLink">
                               {admissionData.pdfUrl && <DownloadEBrochure actionType='Get_Admission_Details' ctaName='getAdmissionDetails' heading='Downloading Admission Details' className='button--yellow'  buttonText="Get Admission Details" uniqueId={'getAdmissionDetails_'+this.props.listingId} listingId={this.props.listingId} listingName={this.props.instituteName} recoEbTrackid={this.config.GetAdmissionDetailCTA}  isCallReco={false} clickHandler={this.trackEvent.bind(this,'NEWCTA','click_get_admission_cta')} page = {this.props.page} pdfUrl={admissionData.pdfUrl}/>}                    
                               <Link to={admmsionUrl} className="gradVw-mr button button--secondary rippleefect" onClick = {this.handleClickonAddmission.bind(this)} >View More
                                 <i className="blu-arrw"></i>
                               </Link>
                             </div>
                           </div>
                         </div>) : null
                }
                {tupleData ? this.ExamWidget(tupleData): null}
              </div>
              {   
                (admissionData.showAdmissionFlag === true) ? (
                  <div className="find-schlrSec h0">
                    <div className="find-schlrSec-inr h0">
                    <p>{"Want to know the eligibility, admission process and important dates of all "+this.props.instituteName+" courses?"}</p>
                      <Link to={admmsionUrl} className="trnBtn rippleefect" onClick = {this.handleClickonAddmission.bind(this)}>View Admissions & Cut-Offs Info</Link>
                    </div>
                  </div>
                ) : null
              }
            </section>:
        <React.Fragment>
        { admissionData.admissionDetails !=null ?
          <section>
            <div className='data-card m-5btm pos-rl'>
               <h2 className='color-3 f16 heading-gap font-w6'>Admissions</h2>
               <div className='card-cmn color-w'>
                 <div className='rich-txt-container admission-div'>
                   <div dangerouslySetInnerHTML={{
                       __html: getTextFromHtml(admissionData.admissionDetails, 500)
                     }}></div>
                 </div>
                 <div className="gradient-col hide-class">
                   <a href={admmsionUrl+'#admission'} className="color-b btn-tertiary f14 ga-analytic" data-vars-event-name="VIEW_MORE_ADMISSION" on = {'tap:',this.trackEvent.bind(this)} >View More</a>
                 </div>
               </div>
            </div>
          </section>: null}
          {admissionData.examList && admissionData.examList.length>0 ? this.ExamWidget(admissionData.examList): null}
          {this.AdmissionstaticWidget()}
        </React.Fragment>
      }
    </React.Fragment>
  )

  }
}


Admission.defaultProps={
  isAmp: false,
  deviceType: 'mobile'
}

function mapDispatchToProps(dispatch){
  return bindActionCreators({ storeChildPageDataForPreFilled}, dispatch);
}

export default connect(null,mapDispatchToProps)(Admission);
