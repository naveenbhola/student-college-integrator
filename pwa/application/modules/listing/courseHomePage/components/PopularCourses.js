import React from 'react';
import  './../assets/SpecializationBox.css';
import {Link} from 'react-router-dom';
import Analytics from '../../../../modules/reusable/utils/AnalyticsTracking';
import WikiContent from './../../../common/components/WikiContent';
import {addingDomainToUrl} from './../../../../utils/commonHelper';

class PopularCourses extends React.Component
{
   constructor(props)
   {
      super(props);
      this.state = {}
   }

   gaTrackEvent(params, e)
   {
      Analytics.event({category : params.category, action : params.category+'_'+params.action, label : params.category+'_'+params.label});
   }

   getWikiHtml(lableName){
      var self = this;
      var sectionData  = new Array();
      let html = (this.props.courseData.wikiData).map(function (data, index){
          return (<WikiContent key={data.sectionName} order={self.props.order} sectiondata={data} sectionname={data.sectionName}/>);
      });
      sectionData.push(html);
      return sectionData;
   }

   getCourseSectionHtml(lableName){
      var self = this;
      var sectionData  = new Array();
      let html = (this.props.courseData.tuple).map(function (data, index){
	 if(data.url!=null){
          return (<li key={index}>{(self.props.isPdfCall) ? <a href={addingDomainToUrl(data.url,self.props.config.SHIKSHA_HOME)} onClick={self.gaTrackEvent.bind(this,{'category':'CHP','action':lableName,'label':lableName})}>{data.name}</a> : <Link to={data.url} onClick={self.gaTrackEvent.bind(this,{'category':'CHP','action':lableName,'label':lableName})}>{data.name}</Link>}<p>{data.count} {data.count>1? " Colleges":" College"}</p></li>);
	}
      });
      sectionData.push(html);
      return sectionData;
   }

   render()
   {
      let chpWikiData    = '';
      let chpCourseList  = '';
      
      let h2subheading = "UG Courses";
      let heading3 = "UG Courses";
      let type = "UG";
      if(this.props.sectionname=='popularPGCourses'){
        h2subheading = 'PG Courses';
        heading3 = 'PG Courses';
        type = 'PG';
      }else if(this.props.sectionname=='popularSpecialization'){
        h2subheading = 'Specializations';
        heading3 = 'Popular Specializations';
        type = 'Specializations';
      }
      let heading2 = "Popular "+this.props.labelname+" "+h2subheading+" in India";
      let lableName = (this.props.deviceType == 'desktop') ? heading3.replace(' ','_Desktop_') : heading3.replace(' ','_');
      if(this.props.courseData.tuple!=null){
        chpCourseList = this.getCourseSectionHtml(lableName);
      }
      if(this.props.courseData.wikiData!=null){
        chpWikiData = this.getWikiHtml(lableName);
      }

      return (
            <React.Fragment>
		         <section id={type} >
		            <div className="_container">
		               <h2 className="tbSec2">{heading2}</h2>
                   <div className="_subcontainer">
                      {chpWikiData}
                      {chpCourseList!=''?
  		               <div className="specialization-box">
                        <div className="box-header">
                          <h3>{heading3}</h3>
                        </div>
                        <ul className="specialization-list">
                           {chpCourseList}
                           {this.props.courseData.allTupleUrl!=null?<li> {(this.props.isPdfCall) ? <a href={addingDomainToUrl(this.props.courseData.allTupleUrl,this.props.config.SHIKSHA_HOME)} onClick={this.gaTrackEvent.bind(this,{'category':'CHP','action':lableName,'label':lableName+'_ReadMore'})} className="align_center"><button type="button" name="button" className="button button--secondary arrow">View All</button></a> : <Link to={this.props.courseData.allTupleUrl} onClick={this.gaTrackEvent.bind(this,{'category':'CHP','action':lableName,'label':lableName+'_ReadMore'})} className="align_center"><button type="button" name="button" className="button button--secondary arrow">View All</button></Link>}</li>:''}
                        </ul>
                     </div>:''}
                    </div>
		            </div>
		         </section>
            </React.Fragment>
         )
   }
}

export default PopularCourses;
