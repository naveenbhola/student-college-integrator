import React from 'react';
import './../assets/HomePage.css';
import ExamChangeCourseLayer from './ExamChangeCourseLayer';
import WikiLabel from '../../common/components/WikiLabel';
import WikiContent from '../../common/components/WikiContent';
import ExamNotification from './ExamNotification';
import {DFPBannerTempalte} from './../../reusable/components/DFPBannerAd';
import ExamInlineTupleCTA from './ExamInlineTupleCTA';
import ExamInlineRegistration from './ExamInlineRegistration';
import {event} from "../../reusable/utils/AnalyticsTracking";
import PropTypes from 'prop-types';
import ExamEditorialCTA from "./ExamEditorialCTA";
import {isUserLoggedIn} from '../../../utils/commonHelper';
import Feedback from "../../common/components/feedback/Feedback";

class ExamHomePage extends React.Component{
	constructor(props)
	{
		super(props);
		this.homepageCtaSections = ['Summary', 'Eligibility', 'Exam Centers', 'Exam Analysis'],
		this.state = {
			showInlineRegistrationForm : false
    	};
	}

	componentDidMount(){
		if(!isUserLoggedIn()){
			this.setState({
				showInlineRegistrationForm :true
			})
		}
	}

	trackEvent = () => {
		let gaCategory = 'EXAM PAGE';
		let labelName = isUserLoggedIn() ? 'Logged-In' : 'Non-Logged In';
		let deviceLabel = this.props.deviceType === 'mobile' ? 'MOB' : 'DESK';
		let actionLabel = 'WEBSITE_LINK_CONTACT_EXAM_PAGE_'+deviceLabel;
		event({category : gaCategory, action : actionLabel, label : labelName});
	};

	createHTML(Obj){
		let self = this;
		let html = (Obj).map(function (data, index){
			let anchor = '';
				if(data.entity_type=='Official Website'){
					anchor = <a onClick={self.trackEvent.bind(this)} key={index} href={data.entity_value} target="_blank" rel="noopener noreferrer nofollow"> {data.entity_value}</a>;
				}
				if(data.entity_type=='Phone Number'){
					if(self.props.deviceType=='mobile'){
						anchor = <a key={index} href={"tel:"+`${data.entity_value}`}> {data.entity_value}</a>;
					}else{
						anchor = data.entity_value;
					}
				}
			return(<div className="contact-links" key={index+11}><strong>{data.entity_type}: </strong>{anchor}</div>)
		})
  		return html;
	}

	getWikiContent(){
		var self = this;
		var sectionData  = new Array();
		var contactInfo = new Array();
			
		let html = (this.props.sectiondata).map(function (data, index){
	  	let contactInfoSubSection = '';
  		if(data.entity_type=='Official Website' || data.entity_type=='Phone Number'){
  			contactInfo.push(data);
  			return '';
  		}
  		let headingText = data.entity_type;
    	if(data.entity_type == "Summary")
    	{
    		headingText = "Overview";
    	}
    	if(data.entity_type=='Contact Information' && typeof contactInfo !== 'undefined' && contactInfo.length > 0){
       		contactInfoSubSection = self.createHTML(contactInfo)
       	}
		let	className = (data.entity_type=='Contact Information')?'wrap-text':'';
        return (
        	<React.Fragment key={index}>
       		<section key={index}>
		    <div className="_container">
		    	{ headingText != 'Overview' && <WikiLabel labelName={self.props.labelPrefix + ' '+headingText}/>}
				<div className={"_subcontainer "+ className}>
					{index==0 && self.props.groupMapping && self.props.groupMapping.length>1?<ExamChangeCourseLayer groupMapping={self.props.groupMapping} selectedGroupName={self.props.selectedGroupName} basePageUrl={self.props.basePageUrl}  groupId={self.props.groupId}/>:''}
		       	<WikiContent key={index} labelValue={'entity_value'} sectiondata={data} sectionname={self.props.sectionname} deviceType={self.props.deviceType}/>
					{self.homepageCtaSections.indexOf(data.entity_type) > -1 && self.props.newCTAConfig && self.props.newCTAConfig[data.entity_type] && <div className="overview-editorial-cta"><ExamEditorialCTA examName={self.props.examName} deviceType={self.props.deviceType} examGroupId={self.props.groupId} {...self.props.newCTAConfig[data.entity_type]} sectionName={data.entity_type} editorialPdfData={self.props.editorialPdfData} /></div>}
		       		{contactInfoSubSection}
				</div>
			     	</div>
		    	</section>
				{index === 0 ? <React.Fragment><DFPBannerTempalte  key={'dfp1'} bannerPlace={self.props.deviceType+'_LAA'}/><DFPBannerTempalte key={'dfp2'} bannerPlace={self.props.deviceType+'_LAA1'}/></React.Fragment> : null}
				{index === 1 && self.props.deviceType==='desktop' ? <ExamInlineTupleCTA {...self.props} examName={self.props.examName}/> : null}
				{self.props.deviceType==='desktop' && typeof self.props.extraObject.showInlineRegistrationForm !== 'undefined' && self.props.extraObject.showInlineRegistrationForm && index === 1 ? <ExamInlineRegistration {...self.props} examName={self.props.examName} /> : null}
				{index === 3 ? <React.Fragment><DFPBannerTempalte key={'dfp3'} bannerPlace={self.props.deviceType+'_AON'}/><DFPBannerTempalte key={'dfp4'} bannerPlace={self.props.deviceType+'_AON1'}/></React.Fragment> : null}
		    	{self.props.deviceType=='mobile' && index==0 && self.props.epUpdates != null && <ExamNotification {...self.props} examName={self.props.examName} groupId={self.props.groupId} notifications={self.props.epUpdates}/>}
				{index === 0 && <Feedback pageId={self.props.groupId} pageType={self.props.activeSection === 'homepage' ? 'EP':'ECP'} subPageType={self.props.activeSection} deviceType={self.props.deviceType} feedbackWidgetType={self.props.deviceType === 'desktop' ? 'type2' : 'type1'} />}
		    	</React.Fragment>
		       	);
	  });
	  sectionData.push(html);
	  return sectionData;
    }

	render()
	{
		let wikiContent   = this.getWikiContent();
		return (
			<React.Fragment>
				{wikiContent}
			</React.Fragment>
		)
	}
}

ExamHomePage.propTypes = {
	deviceType : PropTypes.string,
	sectiondata : PropTypes.array
}
export default ExamHomePage;
