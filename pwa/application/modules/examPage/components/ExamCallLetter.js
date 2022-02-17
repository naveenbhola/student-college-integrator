import React from 'react';
import WikiLabel from '../../common/components/WikiLabel';
import WikiContent from '../../common/components/WikiContent';
import ExamChangeCourseLayer from './ExamChangeCourseLayer';
import Anchor from "../../reusable/components/Anchor";
import {trackViewDetailEvent} from "../utils/examPageHelper";
import PropTypes from 'prop-types';

class ExamCallLetter extends React.Component{
	constructor()
	{
		super();
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
			        		{this.props.activeSection=="callletter" && this.props.groupMapping && this.props.groupMapping.length>1?<ExamChangeCourseLayer groupMapping={this.props.groupMapping}  selectedGroupName={this.props.selectedGroupName} basePageUrl={this.props.basePageUrl} groupId={this.props.groupId}/>:''}
							{wikiDescription}
							{this.props.activeSection === 'homepage' && <div className="gradient-col"><Anchor onClick={this.trackViewDetailEventWrap.bind(this)} to={this.props.childPageUrl} className="gradVw-mr button button--secondary arrow">View Details</Anchor></div>}
						</div>
					</div>
			</section>
			</React.Fragment>
		)
	}
}

ExamCallLetter.propTypes = {
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
export default ExamCallLetter;
