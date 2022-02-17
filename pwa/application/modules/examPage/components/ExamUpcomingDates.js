import React,{PureComponent} from 'react';
import PropTypes from 'prop-types';
import './../assets/UpcomingDates.css';
import Anchor from './../../reusable/components/Anchor';
import {formatDate, addingDomainToUrl, isUserLoggedIn} from '../../../utils/commonHelper';
import config from './../../../../config/config';
import {event} from './../../reusable/utils/AnalyticsTracking';

class ExamUpcomingDates extends PureComponent{
    trackEvent = (pageClicked) => {
        let deviceLabel = this.props.deviceType === 'mobile' ? 'MOB' : 'DESK';
        let gaCategory = 'EXAM'+(this.props.activeSection === 'homepage' ? '' : ' '+this.props.originalSectionName.toUpperCase())+' PAGE';
        let actionLabelPostfix = this.props.activeSection === 'homepage' ? 'EXAM_PAGE' : this.props.originalSectionName.toUpperCase().replace(' ', '_')+'_PAGE';
        let actionLabelPrefix = pageClicked.toUpperCase().replace(' ', '_');
        let actionLabel = 'UPCOMING_EXAM_ALERT_'+actionLabelPrefix+'_'+actionLabelPostfix+'_'+deviceLabel;
        let label = isUserLoggedIn() ? 'Logged-In' : 'Non-Logged In';
        event({category : gaCategory, action : actionLabel, label : label});
    };
    trackViewAllEvent = () => {
        let deviceLabel = this.props.deviceType === 'mobile' ? 'MOB' : 'DESK';
        let gaCategory = 'EXAM'+(this.props.activeSection === 'homepage' ? '' : ' '+this.props.originalSectionName.toUpperCase())+' PAGE';
        let label = isUserLoggedIn() ? 'Logged-In' : 'Non-Logged In';
        let actionLabel = 'VIEW_ALL_UPCOMING_EXAM_DATES_'+gaCategory.toUpperCase().split(' ').join('_')+'_'+deviceLabel;
        event({category : gaCategory, action : actionLabel, label : label});
    };

  createTupleChildPageHtml(inputData){
    let sectionData  = new Array();
    let html = inputData.map(
      (data, index) => 
      {
        return(
          <React.Fragment key={'link'+index}>
          <Anchor onClick={this.trackEvent.bind(this, data.name)} to={data.url} className="quick-links">{data.name}</Anchor>
          {inputData.length - index >1? <b>|</b>:''}
          </React.Fragment>
        )
      }
    )

    sectionData.push(<div className="collections_a"  key={0}>{html}</div>);
    return sectionData;
  }

 createTuple(){
    let self = this;
    let sectionData  = new Array();
    let html = (this.props.upcomingEvents.events).map(
      (data , index)=>{
        return(
          <div className="examslist" key={index}>
                   <div className="anchor_holder"><Anchor onClick={this.trackEvent.bind(this, 'EXAM_PAGE')} to={data.examUrl}>{data.groupYear>0?data.examName + " " + data.groupYear:data.examName }</Anchor></div>

              {data.eventName !='' ? <div className="exams-infodata"><strong>{data.eventName!='' && data.startDate!=data.endDate?formatDate(data.startDate,'d m\'y')+" - ":''} {data.eventName!=''?formatDate(data.endDate,'d m\'y'):''}:  </strong>{data.eventName}</div> : null}
                  {data.childPages && self.createTupleChildPageHtml(data.childPages)}
                </div>
        )
      }
    )
    sectionData.push(html);
    return sectionData;
  }
	
	render()
	{
    let tuples = this.createTuple();
		return (
			<React.Fragment>
				<section>
           <div className="upcoming-states">
             <div className="_container">
               <h2 className="tbSec2">{this.props.upcomingEvents.heading}</h2>
             </div>
             <div className="_subcontainer _noPad">
               <div className="upcmng-examlist">
                 {tuples}
               </div>
               <div className="viewSectn">
                  {this.props.upcomingEvents.allExamsUrl!=null && <Anchor link={false} to={addingDomainToUrl(this.props.upcomingEvents.allExamsUrl,config().SHIKSHA_HOME)} className="button button--secondary rippleefect dark" onClick={this.trackViewAllEvent.bind(this)}>View All Exams <i className="blu-arrw"></i></Anchor>}
              </div>
             </div>
           </div>
        </section>
			</React.Fragment>
		)
	}
}
ExamUpcomingDates.defaultProps = {
    deviceType : 'mobile'
};
ExamUpcomingDates.propTypes = {
    deviceType : PropTypes.string,
    activeSection : PropTypes.string,
    originalSectionName : PropTypes.string,
    upcomingEvents : PropTypes.object
};
export default ExamUpcomingDates;
