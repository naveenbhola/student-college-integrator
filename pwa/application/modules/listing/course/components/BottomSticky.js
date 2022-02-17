import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import Shortlist from './../../../common/components/Shortlist';
import DownloadEBrochure from './../../../common/components/DownloadEBrochure';
import Analytics from './../../../reusable/utils/AnalyticsTracking';
class BottomSticky extends Component
{
	constructor(props)
	{
		super(props);
	}

	trackEvent(actionLabel,label)
	{
	  Analytics.event({category : 'CLP', action : actionLabel, label : label});
	}

	render(){
		return (
			<React.Fragment>
				<div className="ctp-SrpBtnDiv clp-BtmsSticky" id="clpBtmSticky">
				
				<Shortlist className="pwa-shrtlst-ico" actionType="NM_course_shortlist" courseId={this.props.courseId} trackid="954"  recoEbTrackid="1073" recoCMPTrackid="1074" recoShrtTrackid="1075" recoPageType="EBrochure_RECO_Layer" recoActionType="EBrochure_RECO_Layer" pageType="NM_CourseListing" sessionId="" visitorSessionid="" isCallReco={this.props.isCallReco} clickHandler={this.trackEvent.bind(this,'Shortlist','Response')} isButton={true}/>

				<DownloadEBrochure buttonText="Request Brochure" courseId={this.props.courseId} trackid="955" recoEbTrackid="1073" recoCMPTrackid="1074" recoShrtTrackid="1075" isCallReco={this.props.isCallReco} clickHandler={this.trackEvent.bind(this,'DownloadBrochure','Response')}/>
				</div>
			</React.Fragment>
		)
	}
}
export default BottomSticky;