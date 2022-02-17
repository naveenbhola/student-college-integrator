import React,{PureComponent} from 'react';
import PropTypes from 'prop-types';
import './../assets/Notification.css';
import APIConfig from './../../../../config/apiConfig';
import {getRequest} from './../../../utils/ApiCalls';
import {makeURLAsHyperlink} from "../../../utils/urlUtility";
import {event} from "../../reusable/utils/AnalyticsTracking";
import {isUserLoggedIn} from "../../../utils/commonHelper";
import Loadable from 'react-loadable';

const FullPageLayer = Loadable({
  loader: () => import('./../../common/components/FullPageLayer'/* webpackChunkName: 'FullPageLayer' */),
  loading() {return null},
});

class ExamNotification extends PureComponent{
	constructor(props)
	{
		super(props);
         this.state = {
          showFullLayer: false,
          layerHTML:''
        }
	}
    trackViewAllEvent = () => {
        let gaCategory = 'EXAM'+(this.props.activeSection === 'homepage' ? '' : ' '+this.props.originalSectionName.toUpperCase())+' PAGE';
        let actionLabel = 'VIEW_ALL_NOTIFICATIONS_'+(this.props.activeSection === 'homepage' ? 'EXAM' : this.props.originalSectionName.toUpperCase().replace(' ', '_'))+'_PAGE_'+(this.props.deviceType === 'mobile' ? 'MOB' : 'DESK');
        let label = isUserLoggedIn() ? 'Logged-In' : 'Non-Logged In';
        event({category : gaCategory, action : actionLabel, label : label});
    };

  openFullPageLayer(){
	let self = this;
	self.trackViewAllEvent();
	FullPageLayer.preload().then(function(){
        self.getLayerHTML();
        document.getElementById('fullLayer-container').classList.add('epSimilar');
    }) 	  
  }

  closeFullPageLayer(){
    this.setState({
        showFullLayer: false
    });
    document.getElementById('fullLayer-container').classList.remove('epSimilar');
  }

  createLayerHTML(inputData){
    let sectionData  = [];
    let html = (inputData).map((data,index)=>{
            return(<div className="notification_list" key={'input'+index}>
              {data.url!=''?<a href={data.url}>{data.text}</a>:<span dangerouslySetInnerHTML={{'__html':makeURLAsHyperlink(data.text)}}></span>}
            </div>)
            }
          );
    sectionData.push(html);
    return sectionData;
  }

  getLayerHTML(){
    let url = APIConfig.GET_EXAMPAGE_NOTIFICATIONS;
    getRequest(url+'?groupId='+this.props.groupId)
      .then((response) =>{           
        return this.createLayerHTML(response.data.data.updates)
      })
      .then((res)=>{
        if(res!=='' && res!=null){
          this.setState({
          showFullLayer:true,
          layerHTML:res
          })
        }
      })
      .catch(()=> {});
  }

  getNotifications(){
    let sectionData  = new Array();
    let html = (this.props.notifications.updates).map((data,index)=>{
            return(<div className="notification_list" key={'list'+index}>
              {data.url != null && data.url!=='' ? <a href={data.url}>{data.text}</a> : <span dangerouslySetInnerHTML={{'__html':makeURLAsHyperlink(data.text)}}></span>}
            </div>)
            }
          );
    sectionData.push(html);
    return sectionData;
  }

	render()
	{
    let notifications = this.getNotifications();
		return (
			<React.Fragment>
				<section>
        <div className="_container">
          <h2 className="tbSec2 highlight_color">{this.props.examName} Notifications</h2>
        <div className="_subcontainer _noPad">
         <div className="notification_wrapper">
         {notifications}
        </div>
        {this.props.notifications.showViewAll?
          <div className="btn_wrapper">
             <button type="button" name="button" onClick={this.openFullPageLayer.bind(this)} className="button button--secondary arrow">View All Updates</button>
             {this.state.showFullLayer && <FullPageLayer desktopTableData={this.props.deviceType == 'desktop' ? true : false} data={this.state.layerHTML}
              heading={"Latest Updates about "+`${this.props.examName}`}
              isOpen={this.state.showFullLayer}
              onClose={this.closeFullPageLayer.bind(this)}/>}
           </div> :''}
        </div>
        </div>
			      </section>
			</React.Fragment>
		)
	}
}
ExamNotification.propTypes = {
    activeSection: PropTypes.string,
    deviceType: PropTypes.string,
    examName: PropTypes.string,
    groupId: PropTypes.number,
    notifications: PropTypes.object,
    originalSectionName: PropTypes.string
};
export default ExamNotification;
