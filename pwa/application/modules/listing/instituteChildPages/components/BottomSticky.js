import PropTypes from 'prop-types'
import React, { Component } from 'react';
import Shortlist from './../../../common/components/Shortlist';
import DownloadEBrochure from './../../../common/components/DownloadEBrochure';
import Analytics from './../../../reusable/utils/AnalyticsTracking';
import {AdmissionPageConstants,PlacementPageConstants,CutoffPageConstants} from './../../categoryList/config/categoryConfig';
import {DFPBannerTempalte} from './../../../reusable/components/DFPBannerAd';

class BottomSticky extends Component
{
	constructor(props)
	{
		super(props);
		if(this.props.fromWhere && this.props.fromWhere === 'admissionPage'){
			this.config = AdmissionPageConstants();
		}
		else if(this.props.fromWhere && this.props.fromWhere === 'placementPage'){
			this.config = PlacementPageConstants();
		}
		else if(this.props.fromWhere && this.props.fromWhere === 'cutOffPage'){
			this.config = CutoffPageConstants();
		}
	}


	trackEvent(actionLabel,label)
	{
		let category = '';
		if(this.props.fromWhere && this.props.fromWhere === 'admissionPage'){
			category = 'AdmissionPage_PWA';
		}
		else if(this.props.fromWhere && this.props.fromWhere === 'placementPage'){
			category = 'PlacementPage_PWA'
		}
		if(this.props.gaTrackingCategory){
			category = this.props.gaTrackingCategory;
		}
		Analytics.event({category : category, action : actionLabel, label : label});

	}



	render(){
		return (
			<React.Fragment>
				<div className="ctp-SrpBtnDiv clp-BtmsSticky" id="clpBtmSticky">
					<div className = 'stickyBanner' id='stickyBanner'>
						<DFPBannerTempalte bannerPlace="sticky_banner"/>
					</div>


					<Shortlist className="pwa-shrtlst-ico" actionType="NM_InstituteDetailPage" listingId={this.props.listingId} trackid={this.config.shortlistTrackingIdBottomSticky}  recoEbTrackid={this.config.downloadBrochureTrackingIdRecoLayer}  recoShrtTrackid={this.config.shortlistTrackingIdRecoLayer} recoPageType="EBrochure_RECO_Layer" recoActionType="EBrochure_RECO_Layer" pageType="NM_InstituteDetailPage" sessionId="" visitorSessionid="" isCallReco={this.props.isCallReco} clickHandler={this.trackEvent.bind(this,'Shortlist','Response')} isButton={true} page = {this.props.page}/>

					<DownloadEBrochure buttonText="Request Brochure" listingId={this.props.listingId} listingName={this.props.listingName} trackid={this.config.downloadBrochureTrackingIdBottomSticky} recoEbTrackid={this.config.downloadBrochureTrackingIdRecoLayer}  recoShrtTrackid={this.config.shortlistTrackingIdRecoLayer} isCallReco={this.props.isCallReco} clickHandler={this.trackEvent.bind(this,'DownloadBrochure','Response')} page = {this.props.page}/>
				</div>
			</React.Fragment>
		)
	}
}
export default BottomSticky;

BottomSticky.propTypes = {
	fromWhere: PropTypes.any,
	isCallReco: PropTypes.any,
	listingId: PropTypes.any,
	listingName: PropTypes.any,
	page: PropTypes.any
}