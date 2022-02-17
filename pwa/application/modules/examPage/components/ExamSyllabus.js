import React from 'react';
import PropTypes from 'prop-types';
import WikiLabel from '../../common/components/WikiLabel';
import WikiContent from '../../common/components/WikiContent';
import ExamChangeCourseLayer from './ExamChangeCourseLayer';
import Anchor from "../../reusable/components/Anchor";
import {trackViewDetailEvent} from "../utils/examPageHelper";
import ExamEditorialCTA from "./ExamEditorialCTA";

class ExamSyllabus extends React.Component{
	trackViewDetailEventWrap = () => {
		trackViewDetailEvent(this.props.viewDetailGACategory, this.props.originalSectionName, this.props.deviceType);
	};

	getWikiDescription(){
      let self = this;
      let sectionData  = [];
      let html = (this.props.sectiondata).map(function (data, index){
               return (<WikiContent cutWikiContent={self.props.activeSection === 'homepage'} labelName='entity_type' labelValue='entity_value' key={index} sectiondata={data} order={index} sectionname={self.props.sectionname} deviceType={self.props.deviceType}/> );
      });
      sectionData.push(html);
      return sectionData;
    }

	render()
	{
		let wikiDescription = this.getWikiDescription();
		return (
			<React.Fragment>
			<section>
			        <div className="_container">
			        	<WikiLabel labelName={this.props.labelName} />
			        	<div className="_subcontainer">
			        		{this.props.activeSection=="syllabus" && this.props.groupMapping && this.props.groupMapping.length>1?<ExamChangeCourseLayer groupMapping={this.props.groupMapping} selectedGroupName={this.props.selectedGroupName} basePageUrl={this.props.basePageUrl} groupId={this.props.groupId}/>:''}
							{wikiDescription}
							{this.props.activeSection === 'homepage' && <div className="gradient-col">{this.props.newCTAConfig && <ExamEditorialCTA examName={this.props.examName} deviceType={this.props.deviceType} examGroupId={this.props.groupId} {...this.props.newCTAConfig} sectionName={this.props.sectionname} editorialPdfData={this.props.editorialPdfData} />}<Anchor onClick={this.trackViewDetailEventWrap.bind(this)} to={this.props.childPageUrl} className="gradVw-mr button button--secondary arrow">View Details</Anchor></div>}
						</div>
					</div>
			</section>
			</React.Fragment>
		)
	}
}
ExamSyllabus.propTypes = {
	viewDetailGACategory : PropTypes.string,
	originalSectionName : PropTypes.string,
	labelName : PropTypes.string,
	deviceType : PropTypes.string,
	activeSection : PropTypes.string,
	selectedGroupName : PropTypes.string,
	basePageUrl : PropTypes.string,
	childPageUrl : PropTypes.string,
	sectiondata : PropTypes.array,
	groupMapping : PropTypes.array
};
export default ExamSyllabus;
