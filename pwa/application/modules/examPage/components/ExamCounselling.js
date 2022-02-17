import React from 'react';
import WikiLabel from '../../common/components/WikiLabel';
import WikiContent from '../../common/components/WikiContent';
import ExamChangeCourseLayer from './ExamChangeCourseLayer';
import Anchor from "../../reusable/components/Anchor";
import {trackViewDetailEvent} from "../utils/examPageHelper";
import PropTypes from 'prop-types';
import ExamEditorialCTA from "./ExamEditorialCTA";

class ExamCounselling extends React.Component{
	constructor(props)
	{
		super(props);
	}

	getWikiDescription(){
      var self = this;
      var sectionData  = new Array();
      let html = (this.props.sectiondata).map(function (data, index){
               return (<WikiContent cutWikiContent={self.props.activeSection === 'homepage'} labelName='entity_type' labelValue='entity_value' key={index} sectiondata={data} order={index} sectionname={self.props.sectionname} deviceType={self.props.deviceType}/> );
      });
      sectionData.push(html);
      return sectionData;
    }
	trackViewDetailEventWrap = () => {
		trackViewDetailEvent(this.props.viewDetailGACategory, this.props.originalSectionName, this.props.deviceType);
	};

	render()
	{
		let wikiDescription = this.getWikiDescription();
		return (
			<React.Fragment>
			<section>
			        <div className="_container">
			        	<WikiLabel labelName={this.props.labelName} />
			        	<div className="_subcontainer">
			        		{this.props.activeSection=="counselling" && this.props.groupMapping && this.props.groupMapping.length>1?<ExamChangeCourseLayer groupMapping={this.props.groupMapping}  selectedGroupName={this.props.selectedGroupName} basePageUrl={this.props.basePageUrl} groupId={this.props.groupId}/>:''}
							{wikiDescription}
							{this.props.activeSection === 'homepage' && <div className="gradient-col">{this.props.newCTAConfig && <ExamEditorialCTA examName={this.props.examName} deviceType={this.props.deviceType} examGroupId={this.props.groupId} {...this.props.newCTAConfig} sectionName={this.props.sectionname} editorialPdfData={this.props.editorialPdfData} />}<Anchor onClick={this.trackViewDetailEventWrap.bind(this)} to={this.props.childPageUrl} className="gradVw-mr button button--secondary arrow">View Details</Anchor></div>}
						</div>
					</div>
			</section>
			</React.Fragment>
		)
	}
}

ExamCounselling.propTypes = {
   sectiondata: PropTypes.array,
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
export default ExamCounselling;
