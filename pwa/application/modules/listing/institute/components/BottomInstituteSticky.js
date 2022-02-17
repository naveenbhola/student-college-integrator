import React, { Component }  from 'react';
import PropTypes from 'prop-types';
import Shortlist from './../../../common/components/Shortlist';
import DownloadEBrochure from './../../../common/components/DownloadEBrochure';
import Analytics from './../../../reusable/utils/AnalyticsTracking';
import './../../course/assets/bottomSticky.css';
import {DFPBannerTempalte} from './../../../reusable/components/DFPBannerAd';

class BottomInstituteSticky extends Component
{
	constructor(props)
	{
		super(props);
	}


	trackEvent(actionLabel,label)
	{
		if(this.props.page === "institute"){
			Analytics.event({category : 'ILP', action : actionLabel, label : label});
		}
		else{
			Analytics.event({category : 'ULP', action : actionLabel, label : label});
		}

	}



	render(){
		let isAmp = this.props.isAmp;
		let SHIKSHA_HOME = this.props.config.SHIKSHA_HOME;
		let recoEbTrackid =1077;
		let recoCMPTrackid = 1074;
		let recoShrtTrackid = 1070;
		let EbTrackid = 1765;
		let shrtTrackid = 1767;
		if(this.props.page == "institute"){
			recoEbTrackid = 1063;
			recoCMPTrackid = 1074;
			recoShrtTrackid = 1208;
			EbTrackid = 1769;
			shrtTrackid =1771;
		}
		else if(this.props.page == "university") {
			EbTrackid = 1081;
		}
		return (
			<React.Fragment>
				{(!isAmp) ?
					<React.Fragment>
						<div className="ctp-SrpBtnDiv clp-BtmsSticky" id="clpBtmSticky">
							<div className = 'stickyBanner' id='stickyBanner'>
								<DFPBannerTempalte bannerPlace="sticky_banner"/>
							</div>
							<Shortlist className="pwa-shrtlst-ico" actionType="NM_InstituteDetailPage" listingId={this.props.listingId} trackid={shrtTrackid}  recoEbTrackid={recoEbTrackid} recoCMPTrackid={recoCMPTrackid} recoShrtTrackid={recoShrtTrackid} recoPageType="EBrochure_RECO_Layer" recoActionType="EBrochure_RECO_Layer" pageType="NM_InstituteDetailPage" sessionId="" visitorSessionid="" isCallReco={this.props.isCallReco} clickHandler={this.trackEvent.bind(this,'Shortlist','Response')} isButton={true} page = {this.props.page}/>

							<DownloadEBrochure buttonText="Request Brochure" listingId={this.props.listingId} trackid={EbTrackid} recoEbTrackid={recoEbTrackid} recoCMPTrackid={recoCMPTrackid} recoShrtTrackid={recoShrtTrackid} isCallReco={this.props.isCallReco} clickHandler={this.trackEvent.bind(this,'DownloadBrochure','Response')} page = {this.props.page}/>
						</div>
					</React.Fragment> :

					<div className="sticky-dv">
						<div className="table max-table">
							<section className="wd50 i-block m-lt">
								<a href={SHIKSHA_HOME+"/muser5/UserActivityAMP/getResponseAmpPage?listingType="+this.props.page+"&listingId="+this.props.listingId+"&actionType=shortlist&fromwhere="+this.props.page+"&pos=sticky"} className="btn btn-secondary color-w color-b f14 font-w7 wd50 tab-cell ga-analytic" data-vars-event-name="SHORTLIST_STICKY">Shortlist</a>
							</section>
							<section className="wd50 i-block">
								<a href={SHIKSHA_HOME+"/muser5/UserActivityAMP/getResponseAmpPage?listingType="+this.props.page+"&listingId="+this.props.listingId+"&actionType=brochure&fromwhere="+this.props.page+"&pos=sticky"} className="btn btn-primary color-o color-f f14 font-w7 wd50 tab-cell ga-analytic" data-vars-event-name="DBROCHURE_STICKY">Request Brochure</a>
							</section>
							<p className="clr"></p>
						</div>
					</div>
				}
			</React.Fragment>
		)
	}
}

BottomInstituteSticky.defaultProps= {
	isAmp: false
}
export default BottomInstituteSticky;

BottomInstituteSticky.propTypes = {
  config: PropTypes.any,
  isAmp: PropTypes.bool,
  isCallReco: PropTypes.any,
  listingId: PropTypes.any,
  page: PropTypes.any
}