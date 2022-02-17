import React from 'react';
import  './../assets/SpecializationBox.css';
import {Link} from 'react-router-dom';
import WikiContent from './../../../common/components/WikiContent';
import AggregateReview from './../../course/components/AggregateReviewWidget';
import {isEmpty, addingDomainToUrl} from './../../../../utils/commonHelper';
import config from './../../../../../config/config';
import Analytics from '../../../../modules/reusable/utils/AnalyticsTracking';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import {storeCourseDataForPreFilled} from './../../course/actions/CourseDetailAction';
import {storeInstituteDataForPreFilled} from './../../institute/actions/InstituteDetailAction';

class TopRatedCourse extends React.Component
{
   constructor(props)
   {
      super(props);
      this.state = {}
   }

    getWikiHtml(){
      var self = this;
      var sectionData  = new Array();
      let html = (this.props.sectiondata.wikiData).map(function (data, index){
              return (<WikiContent key={index} order={self.props.order} sectiondata={data} sectionname={self.props.sectionname}/>);
      });
      sectionData.push(html);
      return sectionData;
   }

  trackEvent = (linkText) =>{
    let actionLable = (this.props.deviceType == 'desktop') ? 'CHP_Desktop_TopRatedCourse' : 'CHP_TopRatedCourse';
    Analytics.event({category : 'CHP', action : actionLable, label : 'CHP_'+linkText});
  }

  storeCourseData(item,linkText){
    if(typeof item != 'undefined' && typeof item == 'object') {
      this.props.storeCourseDataForPreFilled(item);
    }
    this.trackEvent(linkText);
  }

  storeInstituteData(item,linkText){
    if(typeof item != 'undefined' && typeof item == 'object') {
      this.props.storeInstituteDataForPreFilled(item);
    }
    this.trackEvent(linkText); 
  }

   getSectionHtml(){
      var self = this;
      var courseItem = '';
      var instItem = '';
      var sectionData  = new Array();
      var aggregateReviewDataArr  = {};
      let gaCategoryLabel = (self.props.deviceType == 'desktop') ? 'CHP_Desktop' : 'CHP';
      let html = (this.props.sectiondata.tuple).map(function (data, index){
              aggregateReviewDataArr  = {};
              if(data.aggregateReviewData != null){
                aggregateReviewDataArr.aggregateReviewData = data.aggregateReviewData;
              }
              if(self.props.isPdfCall){
                 courseItem = <a href={addingDomainToUrl(data.courseUrl, self.props.config.SHIKSHA_HOME)}  onClick={self.trackEvent.bind(self,'Course_Click')}><strong>{data.courseName}</strong></a>;
                 instItem = <a onClick={self.trackEvent.bind(self,'Institute_Click')} href={addingDomainToUrl(data.instituteUrl,self.props.config.SHIKSHA_HOME)}><label className="grayLabel">Offered By</label>{data.instituteName}</a>;
              }else{
                 courseItem = data.courseUrl && <Link onClick={self.storeCourseData.bind(self,data,'Course_Click')} to={data.courseUrl}><strong>{data.courseName}</strong></Link>;
                 instItem = data.instituteUrl && <Link onClick={self.storeInstituteData.bind(self,data,'Institute_Click')} to={data.instituteUrl}><label className="grayLabel">Offered By</label>{data.instituteName}</Link>;
              }
              return (<li key={index}>
                            {courseItem}
                            {instItem}
                            { !isEmpty(aggregateReviewDataArr) && typeof aggregateReviewDataArr.aggregateReviewData !='undefined' && aggregateReviewDataArr.aggregateReviewData !== null && <AggregateReview gaCategory={gaCategoryLabel} aggregateReviewData = {aggregateReviewDataArr} reviewsCount={aggregateReviewDataArr.aggregateReviewData.totalCount} showPopUpLayer = {false} reviewUrl = {data.allReviewsUrl} showAllreviewUrl = {false} config={config()} isPdfCall={self.props.isPdfCall}/>}
                      </li>);
      });
      sectionData.push(html);
      return sectionData;
   }

   render()
   {

      let chpWikiData    = '';
      let sectionData    = '';
      if(this.props.sectiondata.wikiData!=null){
        chpWikiData   = this.getWikiHtml();
      }

      if(this.props.sectiondata.tuple!=null){
        sectionData = this.getSectionHtml();
      }

      return (
            <React.Fragment>
             <section id="TopRatedCourse">
                <div className="_container">
                  <h2 className="tbSec2">Top Rated Courses</h2>
                  <div className="_subcontainer">
                    {chpWikiData}
                    {sectionData!=''?
                    <div className="specialization-box">
                      <div className="box-header">
                        <h3>Courses</h3>
                      </div>
                      <ul className="specialization-list">
                        {sectionData}
                      </ul>
                  </div>:''}
                  {this.props.sectiondata.allTupleUrl!=null?<div className="button-container">{(this.props.isPdfCall && this.props.deviceType != 'desktop') ? <a onClick={this.trackEvent.bind('this','ViewAllCourse')} href={addingDomainToUrl(this.props.sectiondata.allTupleUrl,this.props.config.SHIKSHA_HOME)} ><button type="button" name="button" className="button button--secondary arrow">View All Courses</button></a> : <Link onClick={this.trackEvent.bind('this','ViewAllCourse')} to={this.props.sectiondata.allTupleUrl} ><button type="button" name="button" className="button button--secondary arrow">View All Courses</button></Link>}</div>:''}
                  </div>
                </div>
             </section>
            </React.Fragment>
         )
   }
}

function mapDispatchToProps(dispatch){
  return bindActionCreators({ storeCourseDataForPreFilled ,storeInstituteDataForPreFilled}, dispatch); 
}
export default connect(null,mapDispatchToProps)(TopRatedCourse);
