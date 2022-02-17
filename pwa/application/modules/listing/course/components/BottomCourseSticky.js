import PropTypes from 'prop-types'
import React, { Component } from 'react';
import Shortlist from './../../../common/components/Shortlist';
import DownloadEBrochure from './../../../common/components/DownloadEBrochure';
import Analytics from './../../../reusable/utils/AnalyticsTracking';
import {DFPBannerTempalte} from './../../../reusable/components/DFPBannerAd';
import './../assets/bottomSticky.css';

class BottomCourseSticky extends Component
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
					<div className = 'stickyBanner' id='stickyBanner'>
						<DFPBannerTempalte bannerPlace="sticky_banner"/>
					</div>

					<Shortlist className="pwa-shrtlst-ico" actionType="NM_course_shortlist" listingId={this.props.listingId} trackid="954"  recoEbTrackid="1073" recoCMPTrackid="1074" recoShrtTrackid="1075" recoPageType="EBrochure_RECO_Layer" recoActionType="EBrochure_RECO_Layer" pageType="NM_CourseListing" sessionId="" visitorSessionid="" isCallReco={this.props.isCallReco} clickHandler={this.trackEvent.bind(this,'Shortlist','Response')} isButton={true} page = {"coursePage"}/>

					<DownloadEBrochure buttonText="Request Brochure" listingId={this.props.listingId} listingName={this.props.listingName} trackid="955" recoEbTrackid="1073" recoCMPTrackid="1074" recoShrtTrackid="1075" isCallReco={this.props.isCallReco} clickHandler={this.trackEvent.bind(this,'DownloadBrochure','Response')} page = {"coursePage"}/>
				</div>
			</React.Fragment>
		)
	}
}
export default BottomCourseSticky;

BottomCourseSticky.propTypes = {
	isCallReco: PropTypes.any,
	listingId: PropTypes.any,
	listingName: PropTypes.any
}