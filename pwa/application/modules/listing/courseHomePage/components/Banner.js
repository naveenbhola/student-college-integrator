import React from 'react';
import './../assets/Banner.css';
import config from './../../../../../config/config';
import DownloadGuide from './DownloadGuide';
import Analytics from '../../../../modules/reusable/utils/AnalyticsTracking';

class Banner extends React.Component
{
   constructor(props)
   {
      super(props);
      this.state = {}
   }

   trackEvent(params, e)
   {
      Analytics.event({category : params.category, action : params.action, label : params.label});
   }

   render()
   {
      return (
            <React.Fragment>
                  <div id="chpBanner" className="chpBanner" style={{"backgroundImage" : 'url('+config().IMAGES_SHIKSHA+this.props.imageUrl+')'}} >
                     <div className="chpBanner_cell">
                     <div className="chp_h1">
                        <h1>{this.props.displayName}</h1>
                        {(this.props.count.colleges>0 || this.props.count.courses>0 || this.props.count.exams>0)?
                        <ul className="inlinedata-list">
                        {this.props.count.colleges>0?
                           <li>
                              <strong>{this.props.count.colleges} </strong>
                              {this.props.count.colleges>1?'Colleges':'College'}
                           </li>:''}
            {this.props.count.courses>0?
                           <li>
                              <strong>{this.props.count.courses} </strong>
                               {this.props.count.courses>1?'Courses':'Course'}
                           </li>:''}
			   {this.props.count.exams>0?
                           <li>
                              <strong>{this.props.count.exams} </strong>
                              {this.props.count.exams>1?'Exams':'Exam'}
                           </li>:''}

                        </ul>:''}
                     </div>
                     { !this.props.isPdfCall && typeof this.props.chpData != 'undefined' && this.props.chpData.guideUrl ? <DownloadGuide chpData={this.props.chpData} trackingKey={this.props.guideTrackingKey} deviceType={this.props.deviceType}/> : null}
                     </div>
                  </div>
            </React.Fragment>
         )
   }
}
export default Banner;
