import React from 'react';
import WikiLabel from '../../common/components/WikiLabel';
import WikiContent from '../../common/components/WikiContent';
import ExamChangeCourseLayer from './ExamChangeCourseLayer';
import Anchor from "../../reusable/components/Anchor";
import {trackViewDetailEvent} from "../utils/examPageHelper";
import {event} from "../../reusable/utils/AnalyticsTracking";
import {isUserLoggedIn} from "../../../utils/commonHelper";
import PropTypes from 'prop-types';
import ExamEditorialCTA from "./ExamEditorialCTA";

class ExamApplicationForm extends React.Component{
	constructor()
	{
		super();
	}

	getWikiDescription(){
      let self = this, formData = new Object(), sectionData  = new Array(), formUrl = '';
      if(this.props.sectiondata == null || this.props.sectiondata.wiki == null){
      	return formData;
	  }
      this.props.sectiondata.wiki.forEach((data,index)=>{
      	if(data && data['entity_type'] != 'applicationformurl'){
      			let html = (<WikiContent cutWikiContent={self.props.activeSection === 'homepage'} labelName='entity_type' labelValue='entity_value' key={index} sectiondata={data} order={index} sectionname={self.props.sectionname} deviceType={self.props.deviceType}/> );
      			sectionData.push(html);
      	}else{
      			formUrl = data['entity_value'];
      	}
      });
      formData.formUrl = formUrl;
      formData.wikiContent = sectionData;
      return formData;
    }
	trackViewDetailEventWrap = () => {
		trackViewDetailEvent(this.props.viewDetailGACategory, this.props.originalSectionName, this.props.deviceType);
	};
	trackFormURLEvent = () => {
		let gaCategory = 'EXAM'+(this.props.activeSection === 'homepage' ? '' : ' '+this.props.originalSectionName.toUpperCase())+' PAGE';
		let actionLabel = 'APPLICATION_FORM_URL_EXAM'+(this.props.activeSection === 'homepage' ? '' : '_'+this.props.originalSectionName.toUpperCase().replace(' ', '_'))+'_PAGE_'+(this.props.deviceType === 'mobile' ? 'MOB' : 'DESK');
		let label = isUserLoggedIn() ? 'Logged-In' : 'Non-Logged In';
		event({category : gaCategory, action : actionLabel, label : label});
	};

	render()
	{
		let wikiData = this.getWikiDescription();
		return (
			<React.Fragment>
			<section>
			        <div className="_container">
			        	<WikiLabel labelName={this.props.labelName} />
			        	<div className="_subcontainer">
			        		{this.props.activeSection=="applicationform" && this.props.groupMapping && this.props.groupMapping.length>1?<ExamChangeCourseLayer groupMapping={this.props.groupMapping} selectedGroupName={this.props.selectedGroupName} basePageUrl={this.props.basePageUrl} groupId={this.props.groupId}/>:''}
			        		{wikiData['formUrl'] && <p className='applicationTxt'>The application form is also available at this URL <Anchor onClick={this.trackFormURLEvent.bind(this)} to={wikiData['formUrl']} target="_blank">(click to visit)</Anchor></p>}
							{wikiData['wikiContent']}
							{this.props.activeSection === 'homepage' && <div className="gradient-col">{this.props.newCTAConfig && <ExamEditorialCTA examName={this.props.examName} deviceType={this.props.deviceType} examGroupId={this.props.groupId} {...this.props.newCTAConfig} sectionName={this.props.sectionname} editorialPdfData={this.props.editorialPdfData} />}<Anchor onClick={this.trackViewDetailEventWrap.bind(this)} to={this.props.childPageUrl} className="gradVw-mr button button--secondary arrow">View Details</Anchor></div>}
						</div>
					</div>
			</section>
			</React.Fragment>
		)
	}
}

ExamApplicationForm.propTypes = {
   sectiondata: PropTypes.object,
   labelName: PropTypes.string.isRequired,
   activeSection: PropTypes.string.isRequired,
   deviceType: PropTypes.string.isRequired,
   groupMapping: PropTypes.array,
   originalSectionName: PropTypes.string.isRequired,
   basePageUrl: PropTypes.string.isRequired,
   childPageUrl: PropTypes.string.isRequired,
   viewDetailGACategory: PropTypes.string,
   selectedGroupName: PropTypes.string.isRequired
}
export default ExamApplicationForm;
