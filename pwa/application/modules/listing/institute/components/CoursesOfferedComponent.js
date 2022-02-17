import React from 'react';
import PropTypes from 'prop-types';
import {Link} from 'react-router-dom';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import './../../course/assets/courseCommon.css';
import { add_query_params } from './../../../../utils/urlUtility';
import './../assets/css/colleges.css';
import AggregateReview from '../../../listing/course/components/AggregateReviewWidget';
import Analytics from './../../../reusable/utils/AnalyticsTracking';
import {storeChildPageDataForPreFilled} from './../../instituteChildPages/actions/AllChildPageAction';
import {storeCourseDataForPreFilled} from './../../course/actions/CourseDetailAction';
import DownloadEBrochure from './../../../common/components/DownloadEBrochure';
import {ILPConstants, ULPConstants} from './../../categoryList/config/categoryConfig';

class CoursesOffered extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
        paramsObj : {}
      }
    if(this.props.page == 'institute'){
            this.config = ILPConstants();
      }else{
            this.config = ULPConstants();
      }
  }

  trackEvent(eventAction,label='click')
  {
    let category = 'ILP';
    if(this.props.page == "university"){
          category = 'ULP';
    }
    if(this.props.fromwhere == 'allCoursePage'){
          category = 'AllCoursePage_PWA';
    }
    if(this.props.fromwhere == 'coursePage'){
          category = 'CoursePage_PWA';
    }
    Analytics.event({category : category, action : eventAction, label : label});
  }

  handleClickallCourses(eventAction, PageHeading = '',bipSipId){
    this.trackEvent(eventAction);
    var data = {};
    if(this.props.instituteData.listingId){
      data.listingId = this.props.instituteData.listingId;
    }
    if(this.props.instituteData.instituteTopCardData){
      data.instituteTopCardData = this.props.instituteData.instituteTopCardData;
    }    
    if(this.props.instituteData.breadCrumb){
      data.breadCrumb = this.props.instituteData.breadCrumb;
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
    data.PageHeading = 'Courses & Fees 2019';
    if(PageHeading !=''){
     data.PageHeading = PageHeading+' '+data.PageHeading; 
    }
    if(bipSipId){
      data.bipSipId = bipSipId;
    }

    this.props.storeChildPageDataForPreFilled(data);
  }

  renderBrowsebyCourse = (browsebycourse,heading,from) => {
     let isAmp = this.props.isAmp;
      if(  browsebycourse == null || (Array.isArray(browsebycourse) && browsebycourse.length == 0)) {
      return null;

    }
    var SHIKSHA_HOME = this.props.config.SHIKSHA_HOME;
    let tagsUrl = (this.props.fromwhere == "institutePage" || this.props.fromwhere == "coursePage") ? this.props.courseWidget.allCoursesUrl : this.props.allCoursePageUrl;
    var browsebucket= [];

    for(let i in browsebycourse){
     var url = tagsUrl +'/'+browsebycourse[i]['url'];
     var locationurl = this.props.location;
     var id = "";
     var gaTrack = '';
     if(from == "baseCourse"){
        id = "b_"+ browsebycourse[i]['baseCourseId'];
        gaTrack = 'BIP_TAG';
     }else{
        id = "s_"+ browsebycourse[i]['streamId'];
        gaTrack = 'SIP_TAG';
     }
     if(isAmp){
       browsebucket.push(
         <a href={url} className="a-link" key={"browse_" + i} target="_blank"  on={'tap:',this.trackEvent.bind(this)} >{browsebycourse[i].name}</a>
       );
     }else{
        browsebucket.push(
          <Link to={url} className="_clist rippleefect" id ={"bip_sip_"+id}key={"browse_" + i}  onClick={this.handleClickallCourses.bind(this,gaTrack,browsebycourse[i].name,id)} >{browsebycourse[i].name}</Link>
        );
      }
    }

      return(
      <React.Fragment>
        {(!isAmp) ?
            <div className="_browsesection">
              <div className="_sctntitle">{heading}</div>
              <div className="_browseBy">
                <div className="_browseRow">
                  {browsebucket}
                  <a href="javascript:void(0);" id="viewAllTags" className="_clink rippleefect">View More</a>
                </div>
              </div>
            </div>:
            <div className='brws-div'>
                <strong>{heading}</strong>
                <div>
                   <div className='browse-row'>
                     {browsebucket}
                   </div>
                </div>
            </div>
        }
      </React.Fragment>

    )
  }

  handleClickOnCourse(courseData, gaTrack = 'click')
  {
    this.trackEvent(gaTrack);
    if(typeof courseData != 'undefined' && typeof courseData == 'object')
      {
        var data = {};
        data.recognition = courseData.recognition;
        data.courseId = courseData.course_id;
        data.name = courseData.name;

        data.instituteName = '';
        data.instituteName =  courseData.primary_name;   
        data.durationValue = courseData.duration_value;
        data.durationUnit =  courseData.duration_unit;      

        if(courseData.fees !=null){
          data.fees = courseData['fees'][0]['fees_value'];
          data.feesUnit = courseData['fees'][0]['fees_unit_name'];
        }
        if(courseData.course_type_information && courseData.course_type_information.entry_course && courseData.course_type_information.entry_course.course_level && courseData.course_type_information.entry_course.credential){
          data.courseExtraInfo = {};
          data.courseExtraInfo.courseCredential = courseData.course_type_information.entry_course.credential.name;
          data.courseExtraInfo.courseLevel = courseData.course_type_information.entry_course.course_level.name;
          if(courseData.education_type){
            data.courseExtraInfo.extraInfo ={};
            data.courseExtraInfo.extraInfo.educationType = courseData.education_type.name;
          }
        }

        this.props.storeCourseDataForPreFilled(data);
      }
  }



renderCourseList(courselist, coursereviewdata){
 if( courselist == null || (Array.isArray(courselist) && courselist.length == 0)){return null;}
 let isAmp = this.props.isAmp;
 const allCourse = this.props.courseWidget.allCourses;
 var self = this;
  return courselist.map((item)=>{
    return(
    <React.Fragment key={"course_fragment_"+item}>
      {(!isAmp) ?
        <li key={"course_"+item} onClick={self.handleClickOnCourse.bind(self,allCourse[item],'courseWidget')} className="rippleefect">

        <Link to={ allCourse[item].listing_seo_url}  className="_padaround">
          <div className="cors-sec">
            <span>
              {allCourse[item].name.length > 90 ? allCourse[item].name.substr(0, 90).trim() + '...' : allCourse[item].name.trim()}
              <span className="fullColumn">
                { allCourse[item].fees != null ? (<strong>Fees - {allCourse[item].fees[0]['fees_unit_name']} {this.numDifferentiation(allCourse[item].fees[0]['fees_value'])} </strong>) : ''}
               
                {allCourse[item].fees != null && allCourse[item].fees != 'undefined' ? (<b>|</b>) : '' }
                <strong>{allCourse[item].education_type.name != 'undefined' && allCourse[item].education_type.name != null ? (allCourse[item].education_type.name): ''}</strong>

                {allCourse[item].duration_value != null || allCourse[item].duration_value != 'undefined' ? (<b>|</b>) : ''}
                <strong>{allCourse[item].duration_value != null ? (allCourse[item].duration_value) : ''} {allCourse[item].duration_unit !=null  ? allCourse[item].duration_unit  : '' }</strong>

                </span>
                {this.renderAggregateData(item)}
                {this.renderCourseOffered(allCourse[item])}
              </span>
              <span className="cors-fee">
                <i className="blu-arrw"></i>
              </span>
            </div>
          </Link>
        </li> :
         <li className='ga-analytic' data-vars-event-name="POPULAR_COURSE" key={"course_"+item} on={'tap:',this.trackEvent.bind(this)}>
             <a href={ allCourse[item].listing_seo_url} className='block'>
                <div className='table'>
                 <span title={allCourse[item].name} className='f14 color-3 tab-cell v-mdl l-20 font-w6 crs-titl'>
                   {allCourse[item].name.length > 90 ? allCourse[item].name.substr(0, 90).trim() + '...' : allCourse[item].name.trim()}
                   {this.renderAggregateData(item)}
                 </span>
                   { allCourse[item].fees != null ? (<span className='tab-cell f13 color-3 crs-inf font-w6 t-right v-mdl'>{allCourse[item].fees[0]['fees_unit_name']} {this.numDifferentiation(allCourse[item].fees[0]['fees_value'])} <i className='rupee-icn'></i></span>) : ''}
                </div>
             </a>
         </li>
      }
    </React.Fragment>
    )
  })


}

setThousandUnit(val){
  if(val < 99999){ return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); }
}
numDifferentiation(val, decimalPlace = 2) {
    if(val >= 10000000) val = (val/10000000).toFixed(decimalPlace) + ' Cr';
    else if(val >= 100000) val = (val/100000).toFixed(decimalPlace) + ' L';
    else if(val >= 1000) val = this.setThousandUnit(val) + '';
    return val;
}

renderAggregateData(aggredataDataId){
  const aggregateData = this.props.courseWidget.agrregateReviewsData;
  let isAmp = this.props.isAmp;
  const isPaid =  this.props.courseWidget.isPaidCourse;
   if((typeof aggregateData == 'undefined' || aggregateData == null) ||
   (typeof aggregateData.aggregateReviewData == 'undefined' || aggregateData.aggregateReviewData == null  )
    || (typeof aggregateData.aggregateReviewData[aggredataDataId] == 'undefined'
      || aggregateData.aggregateReviewData[aggredataDataId] == null))
  return null;

  return(
  <React.Fragment>
    { (!isAmp) ?
      <div className="ratingv1">
        {<AggregateReview isPaid={isPaid[aggredataDataId]} showPopUpLayer = {false} uniqueKey= {'institute_'+aggregateData.aggregateReviewData[aggredataDataId]} showAllreviewUrl={true} reviewsCount={aggregateData.aggregateReviewData[aggredataDataId]['totalCount']}  aggregateReviewData = {{'aggregateReviewData' : aggregateData.aggregateReviewData[aggredataDataId],'aggregateRatingDisplayOrder' : aggregateData.aggregateRatingDisplayOrder}}  config={this.props.config}/>}
      </div>
      :
      <React.Fragment>
        {<AggregateReview isPaid={isPaid[aggredataDataId]} showPopUpLayer = {false} uniqueKey= {'institute_'+aggregateData.aggregateReviewData[aggredataDataId]} showAllreviewUrl={true} reviewsCount={aggregateData.aggregateReviewData[aggredataDataId]['totalCount']}  aggregateReviewData = {{'aggregateReviewData' : aggregateData.aggregateReviewData[aggredataDataId],'aggregateRatingDisplayOrder' : aggregateData.aggregateRatingDisplayOrder}}  config={this.props.config} isAmp={true} />}
      </React.Fragment>
     }
  </React.Fragment>
  )

}




  populatePopularCoursesViewMore = () => {
    if(!document.querySelector('._browsesection'))
     return;
    const setwindowlength = document.querySelector('._browsesection').clientWidth;
    document.querySelectorAll('._browsesection ._browseRow').forEach((item, index) => {
      var anchorsLength = 0;
      var anchors = item.querySelectorAll('._clist');
      var numberOfAnchors = anchors.length;
      var i = 0;
      var tempLength = 0;
      while (i < numberOfAnchors && tempLength <= setwindowlength) {
        tempLength += anchors[i].clientWidth;
        if (tempLength > setwindowlength) {
          i--;
          anchorsLength += setwindowlength;
          tempLength = 0;
        }
        i++;
      }
      if (anchorsLength >= 3 * setwindowlength) {
        // $(this).addClass('cllpse');
        item.classList.add('cllpse');
        var sd = item.querySelectorAll('._clink');
        item.querySelectorAll('._clink')[0].style.display = 'inline-block';
      } else {
        item.querySelectorAll('._clink')[0].style.display = 'none';
      }
      item.querySelectorAll('._clink')[0].addEventListener('click', () => {
        item.classList.remove('cllpse');
        item.querySelectorAll('._clink')[0].style.display = 'none';
      });
    })

  }


  renderviewallCourse(viewcount){
    let isAmp = this.props.isAmp;
    if(typeof viewcount == 'undefined' ||  viewcount == null )
    return null;
    let tagsUrl = this.props.courseWidget.allCoursesUrl;
    var SHIKSHA_HOME = this.props.config.SHIKSHA_HOME;
    if(typeof tagsUrl == 'undefined' || tagsUrl == null )
    return null;
    var url = tagsUrl;
    var locationurl = this.props.location;
    if(this.props.fromwhere =='institutePage' && this.props.isMultiLocation){
      if(locationurl && locationurl.city_id){
       url = add_query_params(url, 'ct[]='+locationurl.city_id);
      }
      if(locationurl && locationurl.locality_id){
       url = add_query_params(url, 'lo[]='+locationurl.locality_id);
     }
   }
   return(
     <React.Fragment>
       {(!isAmp) ?
        <div className="btn-v1-col _borderTop">
          {this.props.courseWidget.pdfUrl && <DownloadEBrochure actionType='Download_CourseList'  ctaName='downloadCourseList' uniqueId={'downloadCourseList_'+this.props.instituteData.listingId} pdfUrl={this.props.courseWidget.pdfUrl}  heading='Downloading Course List'  className='button button--blue'  buttonText="Download Course List" listingId={this.props.instituteData.listingId} listingName={this.props.instituteData.listingName} recoEbTrackid={this.config.DownloadCourseListCTA} isCallReco={false} clickHandler={this.trackEvent.bind(this,'NEWCTA','click_Download_course_list')} page = {this.props.page} />}
          <Link to={url} className="button button--secondary arrow" onClick={this.handleClickallCourses.bind(this,'All_course_link','')} > View All
          </Link>
        </div>:
        <div className='data-card m-btm'>
          <a href={url} className="btn btn-secondary color-w color-b f14 font-w6 pos-rl top-minus ga-analytic" data-vars-event-name="VIEW_ALL_COURSES">View All {viewcount > 1 ? (viewcount + ' Courses') : (viewcount + ' Course')}</a>
        </div>
       }
     </React.Fragment>
    )

  }

  showcourseCount = (coursecount) => {
    let isAmp = this.props.isAmp;
   if(coursecount == null || typeof coursecount == 'undefined' || coursecount < 0)
    return null;
    return(
      <strong className={(!isAmp) ? '' : 'font-w6'}>{ coursecount > 1 ? (coursecount + ' courses') : (coursecount + ' course') }</strong>
    )

  }

  browseAndSpecalizationlength(){
    var specalizations = null;
    if(this.props.courseWidget.specializationIds){
      specalizations = this.props.courseWidget.specializationIds.length;
    }
    var streams = null;
    if(this.props.courseWidget.streamObjects){
      var streams = this.props.courseWidget.streamObjects.length;
    }
    if((specalizations == null || specalizations == 0) || (streams == null || streams == 0))
    return null;
    return(
      <React.Fragment>
      <strong> {streams > 1 ? (streams + ' streams') : (streams + ' stream')}</strong> and <strong> {specalizations > 1 ? (specalizations + ' specializations') : (specalizations + ' specialization')} </strong>
      </React.Fragment>
    )
  }

  renderBipSipActive(){
    var paramsObj = this.props.paramsObj;
    for(var i in paramsObj.baseCourses){
          var classNametoActive = document.getElementById("bip_sip_b_"+paramsObj.baseCourses[i])  ;
          if(classNametoActive != null){
            classNametoActive.classList.add('active');
            
          }
        }
    for(var i in paramsObj.streams){
          var classNametoActive = document.getElementById("bip_sip_s_"+paramsObj.streams[i])  ;
          if(classNametoActive != null){
            classNametoActive.classList.add('active');
            
          }
        } 
  }

  componentDidMount() {
   if(this.props.fromwhere == "institutePage" || this.props.fromwhere == "coursePage"){
      this.populatePopularCoursesViewMore();
    }
    else if(this.props.fromwhere == "allCoursePage" && typeof document != "undefined"){
          this.renderBipSipActive();
      }
  }

  renderCourseOffered(data){
    var offeredtype = data;
    let listtype = this.props.page;
    if(this.props.fromwhere == "coursePage"){
      listtype = this.props.instituteType;
    }
    if(offeredtype && offeredtype['offered_by_short_name'] && offeredtype['offered_by_short_name'].length != 0 && listtype=='university'){
    return(
      <p className="offrTxt"> {offeredtype['offered_by_short_name'].length > 80 ? ('Offered by'+ ' '+ offeredtype['offered_by_short_name'].substr(0, 80).trim()) + '...' :( 'Offered by'+ ' '+offeredtype['offered_by_name'].trim())}</p>
     )
    }
  }


  render() {
    let isAmp = this.props.isAmp;
    return (
      <React.Fragment>
        { (!isAmp) ?(this.props.fromwhere == "institutePage" || this.props.fromwhere == "coursePage")?<section className='listingTuple' id='Courses'>
               <div className="collegedpts listingTuple">
                 <div className="_container">
                  {this.props.fromwhere =='coursePage'?
                  (<h2 className="tbSec2">Other Courses offered by 
                      <Link to={this.props.instituteUrl}> {this.props.instituteName}</Link>
                    </h2>
                  )                 
                  :<h2 className="tbSec2">Courses & Fees Offered</h2>
                  }
                   <div className="_subcontainer _noPad">
                     <div className="offeringall">This college offers {this.showcourseCount(this.props.courseWidget.totalCourseCount)} in {this.browseAndSpecalizationlength()}</div>
                      <div className="_padaround">
                     {(this.props.courseWidget.baseCourseObjects != null) && this.renderBrowsebyCourse(this.props.courseWidget.baseCourseObjects,"Browse by Courses","baseCourse")}
                     {(this.props.courseWidget.streamObjects != null) && this.renderBrowsebyCourse(this.props.courseWidget.streamObjects,"Browse by Streams","stream")}
                   </div>

                     <div className="_popularClist">
                       <div className="_listSection">
                         <div className="_repeatList">
                           <ul className="cors-offLst">
                           {this.renderCourseList(this.props.courseWidget.popularCourseList, this.props.courseWidget.agrregateReviewsData)}
                           {this.renderCourseList(this.props.courseWidget.featuredCourseList, this.props.courseWidget.agrregateReviewsData )}

                           </ul>
                         </div>
                          {this.renderviewallCourse(this.props.courseWidget.totalCourseCount)}
                       </div>
                     </div>

                   </div>
                 </div>
               </div>
             </section>:
             <div className="_padaround">
               {(this.props.childPageData.baseCourseObjects != null)&&this.renderBrowsebyCourse(this.props.childPageData.baseCourseObjects,"Browse by Courses","baseCourse")}
               {(this.props.childPageData.streamObjects != null)&&this.renderBrowsebyCourse(this.props.childPageData.streamObjects,"Browse by Streams","stream")}
             </div> :
        <section>
         <div className='data-card m-btm'>
           <h2 className='color-3 f16 heading-gap font-w6'>Courses Offered</h2>
           <p className='color-3 f11 m-5btm p-l11'>This college offers  {this.showcourseCount(this.props.courseWidget.totalCourseCount)} in {this.browseAndSpecalizationlength()}</p>
           <div className='card-cmn color-w'>
             <div>
               {this.renderBrowsebyCourse(this.props.courseWidget.baseCourseObjects,"Browse by Courses")}
               {this.renderBrowsebyCourse(this.props.courseWidget.streamObjects,"Browse by Streams")}
             </div>
             <h3 className='color-6 font-w6 f14 padb clg-pl-subHead  m-btm'>Popular Courses</h3>
             <ul className='course-li'>
               {this.renderCourseList(this.props.courseWidget.popularCourseList, this.props.courseWidget.agrregateReviewsData)}
               {this.renderCourseList(this.props.courseWidget.featuredCourseList, this.props.courseWidget.agrregateReviewsData )}
             </ul>
           </div>
         </div>
         {this.renderviewallCourse(this.props.courseWidget.totalCourseCount)}
        </section>
      }
      </React.Fragment>
    )
  }
}
function mapDispatchToProps(dispatch){
  return bindActionCreators({ storeChildPageDataForPreFilled,storeCourseDataForPreFilled}, dispatch);
}

CoursesOffered.defaultprops={
  isAmp: false
}

export default connect(null,mapDispatchToProps)(CoursesOffered);


