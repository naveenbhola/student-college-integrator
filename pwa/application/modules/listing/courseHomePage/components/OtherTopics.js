import React from 'react';
import  './../assets/OtherTopics.css';
import Analytics from '../../../../modules/reusable/utils/AnalyticsTracking';
import {Link} from 'react-router-dom';

import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { preFilledDataForLoader } from './../actions/CourseHomePageAction';

class OtherTopics extends React.Component
{
   constructor(props)
   {
      super(props);
      this.state = {}
   }

   componentDidMount(){
      if(document.getElementById('OtherTopic-list') && document.getElementById('OtherTopic-list').clientHeight>72 && document.getElementById('more')){
          document.getElementById('more').style.display = 'block';
      }
   }

   viewMore(ele){
      let e  = ele.currentTarget;
      e.style.display = 'none';
      var els = document.querySelectorAll('.hideitems');
      for (var i = 0; i < els.length; i++) {
        els[i].classList.remove('hideitems');
      }
   }

   gaTrackEvent(params)
   {
      let actionLabel = (this.props.deviceType == 'desktop') ? params.category+'_Desktop_'+params.action : params.category+'_'+params.action;
      Analytics.event({category : params.category, action : actionLabel, label : params.category+'_'+params.label});
   }

   createElement(){
    let ele = new Array();
    let list = this.props.relatedData;
    for(var i in list){
      let imgUrl = (this.props.deviceType == 'desktop') ? this.props.config.IMG_DOMAIN+list[i].desktopImageUrl : this.props.config.IMG_DOMAIN+list[i].imageUrl;
      let item = (this.props.isPdfCall) ? <a onClick={this.gaTrackEvent.bind(this,{'category':'CHP','action':'Other_topics','label':'related_chps'})} href={this.props.config.SHIKSHA_HOME+list[i].url} key={list[i].chpId}>{list[i].displayName}</a> : <Link onClick={this.prepareLoaderData.bind(this,{'category':'CHP','action':'Other_topics','label':'related_chps','chpName':list[i].displayName,'imgUrl':imgUrl})} to={list[i].url} key={list[i].chpId}>{list[i].displayName}</Link>;
      ele.push(<li key={i} className={(i>9) ? 'hideitems' : ''}>{item}</li>);
    }
    return ele;
   }

   prepareLoaderData =(params, e)=>{
      let loaderData = {'title': params.chpName, 'imgUrl' : params.imgUrl};
      if(typeof loaderData != 'undefined' && typeof loaderData == 'object') {
        this.props.preFilledDataForLoader(loaderData);
      }
      this.gaTrackEvent(params);
   }

   render()
   {
      return (
            <React.Fragment>
               <section id="OtherTopics">
		            <div className="_container">
		               <h2 className="tbSec2">Courses you may be interested in</h2>
		               <div className="_subcontainer">
                      <div className="moreitems" id="moreitems">
                      <ul id="OtherTopic-list" className="OtherTopic-list read-more-wrap" key={100}>
                            {this.createElement()}
		                  </ul>
                      </div>
                      { this.createElement().length>10 &&
                        <div className="btnwraper rippleefect" id="more">
                        <a className="more arrow" onClick={this.viewMore.bind(this)}>View more</a>
                        </div>  
                      }
                      
		               </div>
		            </div>
		         </section>
            </React.Fragment>
         )
   }
}

function mapDispatchToProps(dispatch){
  return bindActionCreators({preFilledDataForLoader}, dispatch);
}

export default connect(null, mapDispatchToProps)(OtherTopics);
