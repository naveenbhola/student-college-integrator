import PropTypes from 'prop-types'
import React from 'react';
import './../assets/contactWidget.css';
import './../assets/courseCommon.css';
import ChangeBranch from './ChangeBranchLinkComponent';
import {htmlEntities,nl2br}from '../../../../utils/stringUtility';
import Analytics from './../../../reusable/utils/AnalyticsTracking';
import {getUrlParameter, prep_url,getQueryVariable} from './../../../../utils/commonHelper';
import {isUserLoggedIn} from '../../../../utils/commonHelper';
import {setCookie, getCookie,showToastMsg, removeQueryString} from './../../../../utils/commonHelper';

import Loadable from 'react-loadable';
const ResponseForm = Loadable({
	loader: () => import('./../../../user/Response/components/ResponseForm'/* webpackChunkName: 'ResponseForm' */),
	loading() {return null},
});

class ContactDetails extends React.Component
{
	constructor(props)
	{
		super(props);
		this.state = { openResponseForm: false, enableRegLayer: false};
		this.responseFormLoaded = false;
		// this.loadResponseForm = this.loadResponseForm.bind(this);
	}

	componentDidMount(){
		this.retainContactDetails();
		if(isUserLoggedIn()){
			this.setState({enableRegLayer: true});
		}
		// window.addEventListener("scroll", this.loadResponseForm);
	}

	componentWillUnmount(){
		if(typeof window != 'undefined'){
			// window.removeEventListener('scroll', this.loadResponseForm);
		}
	}

	renderLocationHtml()
	{
		const {data} = this.props;
		let isMultilocation = Object.keys(data.locations).length > 1 ? true : false;
		if(!isMultilocation)
			return null;

		let locationName = data.currentLocation.city_name ;
		if(data.currentLocation.locality_name != null && data.currentLocation.locality_name != '')
			locationName = data.currentLocation.locality_name +", " + locationName;
		return(
			<div className="contact-det" key="location">
				<p className="contct-hdng">Location</p>
				<p><strong>{locationName}</strong></p>
				<ChangeBranch {...this.props}/>
			</div>
		)
	}

	renderAddressHtml()
	{

		const {data} = this.props;
		let address = data.currentLocation.contact_details.address!= null ? data.currentLocation.contact_details.address : null;

		if(!address)
			return;

		let locationName = data.currentLocation.city_name ;
		let cityName = data.currentLocation.city_name ;
		let stateName = data.currentLocation.state_name;
		let locality_name = '';
		let isEquals = (stateName)? stateName.localeCompare(cityName):0;
		let latitude = data.currentLocation.contact_details.latitude != "" && data.currentLocation.contact_details.latitude != null ? data.currentLocation.contact_details.latitude : '';
		let longitude = data.currentLocation.contact_details.longitude != "" && data.currentLocation.contact_details.longitude != null ? data.currentLocation.contact_details.longitude : '';
		if(data.currentLocation.locality_name != null && data.currentLocation.locality_name != '')
		{
			locationName = data.currentLocation.locality_name +", " + locationName;
			locality_name = data.currentLocation.locality_name;
		}
		if(address.indexOf(locality_name) > -1)
		{
			locationName = cityName;
			if(isEquals != 0)
			{
				locationName = locationName +' ( '+stateName+')';
			}
		}
		else if(stateName != '' && stateName != null && isEquals != 0)
		{
			locationName = locationName +' ( '+stateName+')';
		}
		return ( <div className="contact-det" key="address">
			<p className="contct-hdng">Address</p>
			<p><strong><span dangerouslySetInnerHTML={{ __html :nl2br(htmlEntities(address))}}/><br/>{locationName}</strong></p>
			{ longitude!= '' && latitude != '' && <a href={"http://maps.google.com/?q="+latitude+","+longitude} className="vw-mp"><i className="loc-ico"></i>View on Map </a>}
		</div>)

	}

	renderWebSiteURL()
	{
		const {data} = this.props;
		let websiteURL = data.currentLocation.contact_details.website_url != null && data.currentLocation.contact_details.website_url != '' ? prep_url(data.currentLocation.contact_details.website_url) : null;

		if(!websiteURL)
			return;
		return(
			<div className="contact-det" key="website">
				<p className="contct-hdng">Website</p>
				<a href={websiteURL} rel="nofollow">{websiteURL}</a>
			</div>
		)
	}

	renderPhoneEmailHtml()
	{
		const {data} = this.props;
		let contactDetails = data.currentLocation.contact_details;
		let isPhoneExist = false;
		let isEmailExist = false;

		if( (contactDetails['admission_contact_number'] != '' && contactDetails['admission_contact_number'] != null)  || (contactDetails['generic_contact_number'] != '' && contactDetails['generic_contact_number'] != null))
		{
			isPhoneExist = true;
		}
		if( (contactDetails['admission_email'] != '' && contactDetails['admission_email'] != null)  || (contactDetails['generic_email'] != '' && contactDetails['generic_email'] != null))
		{
			isEmailExist = true;
		}
		if(!isPhoneExist && !isEmailExist)
			return;
		return(
			<React.Fragment key="phone-email">
				{this.renderPhoneHtml()}
				{this.renderEmailHtml()}
			</React.Fragment>
		)
	}

	renderPhoneHtml()
	{
		const {data} = this.props;
		let contactDetails = data.currentLocation.contact_details;
		let distinctNumbers = false;
		if( (contactDetails['admission_contact_number'] != '' && contactDetails['admission_contact_number'] != null)  || (contactDetails['generic_contact_number'] != '' && contactDetails['generic_contact_number'] != null)) {
			if((contactDetails['admission_contact_number'] != '' && contactDetails['admission_contact_number'] != null)  && (contactDetails['generic_contact_number'] != '' && contactDetails['generic_contact_number'] != null) && contactDetails['admission_contact_number'].localeCompare(contactDetails['generic_contact_number']) !== 0)
			{
				distinctNumbers = true;
			}

			let phoneHtml  = [];
			let PhoneNumber = 0;
			if(contactDetails['generic_contact_number'] != '' && contactDetails['generic_contact_number'] != null) {
				PhoneNumber = contactDetails['generic_contact_number'];
			}
			if(contactDetails['admission_contact_number'] != '' && contactDetails['admission_contact_number'] != null && PhoneNumber == 0 )
			{
				PhoneNumber = contactDetails['admission_contact_number'];
			}

			phoneHtml.push(<a key="gnrl-phone-a" href={"tel:"+PhoneNumber}>{PhoneNumber}</a>);
			if(distinctNumbers){
				phoneHtml.push(<p key="gnrl-phone-p">(For general enquiry)</p>);
			}
			let htmlPhone = [];
			htmlPhone.push(<div key="gnrl-phone" className='cntct-gap'>{phoneHtml}</div>);
			phoneHtml = [];
			if(contactDetails['admission_contact_number'] != '' && contactDetails['admission_contact_number'] != null && distinctNumbers) {
				phoneHtml.push(<a key="admsn-phone-a" href={"tel:"+contactDetails['admission_contact_number']}>{contactDetails['admission_contact_number']}</a>);
				phoneHtml.push(<p key="admsn-phone-p">(For admission related enquiry)</p>);
				htmlPhone.push(<div key="admsn-phone" className='cntct-gap'>{phoneHtml}</div>);
			}

			return(
				<div className="contact-det hide" key="phone" id="cphone">
					<p className="contct-hdng">Phone</p>
					{htmlPhone}
				</div>
			)
		}
	}
	renderEmailHtml()
	{
		const {data} = this.props;
		let contactDetails = data.currentLocation.contact_details;
		let distinctEmails = false;
		if( (contactDetails['admission_email'] != '' && contactDetails['admission_email'] != null)  || (contactDetails['generic_email'] != '' && contactDetails['generic_email'] != null))
		{
			if((contactDetails['admission_email'] != '' && contactDetails['admission_email'] != null)  && (contactDetails['generic_email'] != '' && contactDetails['generic_email'] != null) &&contactDetails['admission_email'].localeCompare(contactDetails['generic_email']) !== 0)
			{
				distinctEmails = true;
			}
			let emailHtml  = [];
			let email = '';
			if(contactDetails['generic_email'] != '' && contactDetails['generic_email'] != null) {
				email = contactDetails['generic_email'];
			}
			if(contactDetails['admission_email'] != '' && contactDetails['admission_email'] != null && email == '' )
			{
				email = contactDetails['admission_email'];
			}

			emailHtml.push(<p key="grnl-mail-p">{email}</p>);
			if(distinctEmails){
				emailHtml.push(<p key="grnl-mail-p1">(For general enquiry)</p>);
			}
			let htmlEmail = [];
			htmlEmail.push(<div key="gnrl-mail" className='cntct-gap'>{emailHtml}</div>);
			emailHtml = [];
			if(contactDetails['admission_email'] != '' && contactDetails['admission_email'] != null && distinctEmails) {
				emailHtml.push(<p key="admsn-mail-p">{contactDetails['admission_email']}</p>);
				emailHtml.push(<p key="admsn-mail-p1">(For admission related enquiry)</p>);
				htmlEmail.push(<div key="admsn-mail" className='cntct-gap'>{emailHtml}</div>);
			}
			return(
				<div className="contact-det hide" key="email" id="cemail">
					<p className="contct-hdng">Email</p>
					{htmlEmail}
				</div>
			)

		}

	}

	trackEvent(actionLabel,label)
	{

		const {page} = this.props;
		if(page == "coursepage"){
			Analytics.event({category : 'CLP', action : actionLabel, label : label});
		}
		else if(page == "institute"){
			Analytics.event({category : 'ILP', action : actionLabel, label : label});
		}
		else{
			Analytics.event({category : 'ULP', action : actionLabel, label : label});
		}
	}

	callBackContact(response){
		if(response.userId){
			var listingId = '';
			if(this.props.page == "coursepage"){
				listingId = this.props.data.courseId;
			}
			else{
				listingId = this.props.data.listingId;
			}
			setCookie('courContResp',listingId);
			showToastMsg('Contact details have also been mailed to you.');
			this.retainContactDetails();
			if(typeof(window) !='undefined' && window.location.href.indexOf('actionType=contact') !='-1'){
				removeQueryString();
			}
		}
	}

	retainContactDetails(){
		if(getCookie('courContResp')){
			(document.getElementById('speml'))?document.getElementById('speml').classList.add('hide'):null;
			(document.getElementById('cphone'))?document.getElementById('cphone').classList.remove('hide'):null;
			(document.getElementById('cemail'))?document.getElementById('cemail').classList.remove('hide'):null;
		}
	}

	showResponseForm(){
		ResponseForm.preload().then(() => {
			this.setState({enableRegLayer: true}, () => {this.setState({openResponseForm: true})});
			var url = (typeof(window) != 'undefined') ? window.location.href : '';
			if(url.indexOf('fromwhere=MobileVerificationMailer') == '-1'){
				this.trackEvent('ViewContact','Response');
			}
		});
	}

	closeResponseForm() {
		removeQueryString();
		this.setState({openResponseForm: false});
	}

	render()
	{
		const {page,data} = this.props;
		var trackingKey = "";
		var listingId = "";
		var listingType = "";
		if(page == "institute"){
			trackingKey = 1116 ;
			listingId = data.listingId;
			listingType = "institute"
		}
		else if(page == "university"){
			trackingKey = 1118 ;
			listingId = data.listingId;
			listingType = "university";
		}
		else{
			trackingKey = 1114;
			listingId = data.courseId;
			listingType = "course";
		}
		var url         = (typeof(window) != 'undefined') ? window.location.href : '';

		if(url.indexOf('fromwhere=MobileVerificationMailer') != '-1'){
			var utmCampaign = getQueryVariable('utm_medium',url);
			if(utmCampaign == 'Email'){
				trackingKey = 1659;
			}else{
				trackingKey = 1661;
			}
		} else if(url.indexOf('actionType=contact') != '-1') {
			trackingKey = 1205;
		}

		//Mailer responses tracking keys only
		var actionType = getUrlParameter('action');
		var actionTypeList = ['showContact', 'cd'];
		if(actionType !='' && actionTypeList.indexOf(actionType) != '-1'){
			var mailerType = getUrlParameter('mailer');
			switch (actionType) {
				case 'showContact':
				case 'cd':
					switch (mailerType) {
						case 'ViewedResponseMailer':
							trackingKey = 1336;
							break;
						case 'DetailedRecommendationMailer':
							trackingKey = 1332;
							break;
					}
					break;
			}
		}

		var contactDetails = data.currentLocation.contact_details;
		if(contactDetails['address'] || contactDetails['admission_contact_number'] || contactDetails['website_url']  || contactDetails['generic_contact_number']) {
			return(
				<section className="contactBnr listingTuple" id="contact">
					<div className="_container">
						<h2 className="tbSec2">Contact Details</h2>
						<div className="_subcontainer">
							{this.renderLocationHtml()}
							{this.renderAddressHtml()}
							{this.renderWebSiteURL()}
							{this.renderPhoneEmailHtml()}


							<div className="button-container" id="speml">
								<button onClick={this.showResponseForm.bind(this)} id={'cntBtn'+listingId} className="button button--orange rippleefect dark">Show Phone & Email</button>
								{ this.state.enableRegLayer && <ResponseForm openResponseForm={this.state.openResponseForm} clientCourseId={listingId} listingType={listingType} cta="contactDetails" actionType="MOB_listingContactDetail" trackingKeyId={trackingKey} callBackFunction={this.callBackContact.bind(this)} onClose={this.closeResponseForm.bind(this)} /> }
							</div>
						</div>
					</div>
				</section>
			)
		}
		else
		{
			return null;
		}
	}
}
export default ContactDetails;

ContactDetails.propTypes = {
	config: PropTypes.any,
	data: PropTypes.any,
	page: PropTypes.any
}