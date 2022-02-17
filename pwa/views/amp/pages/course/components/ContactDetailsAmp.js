import React from 'react';
import {htmlEntities,nl2br} from '../../../../../application/utils/stringUtility';
import {prep_url,getQueryVariable} from '../../../../../application/utils/commonHelper';
import AmpLightBox from '../../../../../application/modules/common/components/AmpLightbox';
import ChangeBranchAmp from './ChangeBranchAmp';
import config from './../../../../../config/config';


class ContactDetailsAmp extends React.Component{
  constructor(props){
    super(props);
    this.state = { openResponseForm: false};
  }
  renderLocationHtml()
	{
		const {data,config} = this.props;
		let isMultilocation = Object.keys(data.locations).length > 1 ? true : false;
		if(!isMultilocation)
			return null;

		let locationName = data.currentLocation.city_name ;
		if(data.currentLocation.locality_name != null && data.currentLocation.locality_name != '')
			locationName = data.currentLocation.locality_name +", " + locationName;
		return(
				<li key="location">
                        <label className="color-6 f-lt i-block l-18 f14">Location</label>
                        <span className="block color-3 f14">{locationName}</span>
                        <ChangeBranchAmp id="contact-details" {...this.props}/>

        		</li>
			)
	}
  renderAddressHtml()
  {

    const {data,config} = this.props;
    let address = data.currentLocation.contact_details.address!= null ? data.currentLocation.contact_details.address : null;

    if(!address)
      return;

    let locationName = data.currentLocation.city_name ;
    let cityName = data.currentLocation.city_name ;
    let stateName = data.currentLocation.state_name;
    let locality_name = '';
    let isEquals =  stateName.localeCompare(cityName);
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
    return ( <li key="address">
                        <label className="color-6 f-lt i-block l-18 f14">Address</label>
                        <span className="block color-3 f14"><span dangerouslySetInnerHTML={{ __html :nl2br(htmlEntities(address))}}/><br/>{locationName}</span>
                        { longitude!= '' && latitude != '' && <a href={"http://maps.google.com/?q="+latitude+","+longitude} className="vw-mp ga-analytic" data-vars-event-name="CONTACT_VIEWONMAP" target='_blank'><i className="loc-ico"></i>View on Map </a>}
                    </li>)

  }
  renderWebSiteURL()
	{
		const {data} = this.props;
		let websiteURL = data.currentLocation.contact_details.website_url != null && data.currentLocation.contact_details.website_url != '' ? prep_url(data.currentLocation.contact_details.website_url) : null;

		if(!websiteURL)
			return;
		return(
				<li key="website">
		            <label className="color-6 f-lt i-block l-18 f14">Website</label>
		            <a href={websiteURL} rel="nofollow" className="block color-b f14" target='_blank'>{websiteURL}</a>
		        </li>
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
                phoneHtml.push(<span key="gnrl-phone-p" className="block color-3 f14">(For general enquiry)</span>);
      }
      let htmlPhone = [];
      htmlPhone.push(<div key="gnrl-phone" className='cntct-gap'>{phoneHtml}</div>);
      phoneHtml = [];
      if(contactDetails['admission_contact_number'] != '' && contactDetails['admission_contact_number'] != null && distinctNumbers) {
        phoneHtml.push(<a key="admsn-phone-a" href={"tel:"+contactDetails['admission_contact_number']}>{contactDetails['admission_contact_number']}</a>);
        phoneHtml.push(<span key="admsn-phone-p" className="block color-3 f14">(For admission related enquiry)</span>);
        htmlPhone.push(<div key="admsn-phone" className='cntct-gap'>{phoneHtml}</div>);
            }

            return(
                <section className="contact-det hide" key="phone" id="cphone" amp-access-hide="1">
                  <li className="color-6 f-lt i-block l-18 f14">Phone</li>
                  {htmlPhone}
              </section>
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
        emailHtml.push(<span key="admsn-mail-p1" className='block color-3 f14'>(For admission related enquiry)</span>);
        htmlEmail.push(<span key="admsn-mail" className='block color-3 f14'>{emailHtml}</span>);
            }
            return(
                <section className="contact-det hide" key="email" id="cemail" amp-access-hide="1">
                  <label className="color-6 f-lt i-block l-18 f14">Email</label>
                  {htmlEmail}
              </section>
              )

        }

  }

  render(){
    const {data,courseId,courseUrl} = this.props;
    const domainName = config().SHIKSHA_HOME;
    var contactDetails = data.currentLocation.contact_details;
    if(contactDetails['address'] || contactDetails['admission_contact_number'] || contactDetails['website_url'] || contactDetails['generic_contact_number']) {
      return(
        <section>
         <div className="data-card m-5btm">
            <h2 className="color-3 f16 heading-gap font-w6">Contact Details</h2>
              <div className="card-cmn color-w">
                <ul className="cntct-list">
                  {this.renderLocationHtml()}
                	{this.renderAddressHtml()}
                	{this.renderWebSiteURL()}
                	{this.renderPhoneEmailHtml()}
                  { contactDetails['admission_contact_number'] !=null || contactDetails['generic_contact_number'] || contactDetails['generic_email'] ||  contactDetails['admission_email'] ? <li>
                    <section className="" amp-access="NOT validuser AND NOT contact" amp-access-hide="1">
                        <li id="brochureClick-li">
                          <a class="btn btn-primary color-o color-f f14 font-w7 m-15top ga-analytic" data-vars-event-name="CONTACT_SHOW_PHONEEMAIL" href={domainName+"/muser5/UserActivityAMP/getResponseAmpPage?listingId="+courseId+"&listingType=course&actionType=contact&fromwhere=coursepage"}>Show Phone & Email</a>
                          </li>
                    </section>
                    <section className="" amp-access="validuser AND NOT contact" amp-access-hide="1" tabIndex="0">
                        <li id="brochureClick-li">
                        <a class="btn btn-primary color-o color-f f14 font-w7 m-15top ga-analytic" data-vars-event-name="CONTACT_SHOW_PHONEEMAIL" href={courseUrl+"?actionType=contact&course="+courseId}>Show Phone & Email</a>
                      </li>
                      </section>
                    </li> : ''}
                  </ul>
                </div>
           </div>
         </section>

        )
    }else {
      return(
        null
      )
    }
  }
}

export default ContactDetailsAmp;
