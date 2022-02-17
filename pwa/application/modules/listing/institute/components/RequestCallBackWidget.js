import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import DownloadEBrochure from './../../../common/components/DownloadEBrochure';
import {ILPConstants, ULPConstants} from './../../categoryList/config/categoryConfig';
import Analytics from './../../../reusable/utils/AnalyticsTracking';


class RequestCallBackWidget extends Component
{
	constructor(props)
	{
		super(props);
		if(this.props.page == 'institute'){
            this.config = ILPConstants();
        }else{
            this.config = ULPConstants();
        }
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
		return (
			<React.Fragment>
				<section>
				   <div className="_container">
				       <div className="_subcontainer text-center remove_brdr">
				         Are you interested in this College? Help is just a call away.
				         <div className="rvws_btnv1 btn-v1-col _borderTop">
				          <DownloadEBrochure actionType='Request_Call_Back' heading='Thank you for your Interest.' className='button--purple' ctaName='requestCallBack'  buttonText="Request Call Back" listingId={this.props.listingId} uniqueId={'requestCallBack_'+this.props.listingId} listingName={this.props.listingName} recoEbTrackid={this.config.RequestCallBackCTA}  isCallReco={false} clickHandler={this.trackEvent.bind(this,'NEWCTA','click_Request_Call_Back')} page = {this.props.page}/> 
				          <DownloadEBrochure actionType='Get_Free_Counselling' heading='Thank you for your Interest.'  className='button--blue' ctaName='getFreeCounselling' buttonText="Get Free Counselling" listingId={this.props.listingId} uniqueId={'getFreeCounselling_'+this.props.listingId} listingName={this.props.listingName} recoEbTrackid={this.config.GetFreeCounsellingCTA} isCallReco={false} clickHandler={this.trackEvent.bind(this,'NEWCTA','click_Get_Free_Counselling')} page = {this.props.page}/>
 						 </div>
				      </div>
				 </div>
				</section>
			</React.Fragment>
		)
	}
}

export default RequestCallBackWidget;
