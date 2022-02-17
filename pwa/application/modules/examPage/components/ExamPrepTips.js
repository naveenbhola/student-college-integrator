import React from 'react';
import PropTypes from 'prop-types';
import WikiLabel from '../../common/components/WikiLabel';
import WikiContent from '../../common/components/WikiContent';
import ExamChangeCourseLayer from './ExamChangeCourseLayer';
import ExamFiles from './ExamFiles';
import Anchor from "../../reusable/components/Anchor";
import {trackViewDetailEvent} from "../utils/examPageHelper";

class ExamPrepTips extends React.Component{
	trackViewDetailEventWrap = () => {
		trackViewDetailEvent(this.props.viewDetailGACategory, this.props.originalSectionName, this.props.deviceType);
	};

	getWikiDescription(){
      var self = this;
      var sectionData  = new Array();
      if(this.props.sectiondata == null || this.props.sectiondata.wiki == null){
      	return null;
	  }
      let gaCategory = 'EXAM'+(this.props.activeSection === 'homepage' ? '' : ' '+this.props.originalSectionName.toUpperCase())+' PAGE';
      let html = (this.props.sectiondata.wiki).map(function (data, index){
      		if(self.props.activeSection === 'homepage' && index > 0){
      			return null;
			}else if(self.props.activeSection !== 'homepage' && index === 0 && self.props.sectiondata.files != null && self.props.sectiondata.files.guidePaperData != null){
				return (<React.Fragment key={'key'+index}><WikiContent cutWikiContent={false} labelName='entity_type' labelValue='entity_value' key={index} sectiondata={data} order={index} sectionname={self.props.sectionname} deviceType={self.props.deviceType}/><ExamFiles gaCategory={gaCategory} deviceType={self.props.deviceType} trackingKeyList={self.props.trackingKeyList} files={self.props.sectiondata['files']} headingMapping={{'guidePaperData' : 'Preparation Tips'}} activeSection={self.props.activeSection} examName={self.props.examName} examGroupId={self.props.groupId} ampCTATrackingKeys={self.props.ampCTATrackingKeys}/></React.Fragment>);
			}
      		return (<WikiContent cutWikiContent={self.props.activeSection === 'homepage' && index === 0} labelName='entity_type' labelValue='entity_value' key={index} sectiondata={data} order={index} sectionname={self.props.sectionname} deviceType={self.props.deviceType}/>);
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
			        		{this.props.activeSection=="preptips" && this.props.groupMapping && this.props.groupMapping.length>1?<ExamChangeCourseLayer groupMapping={this.props.groupMapping} selectedGroupName={this.props.selectedGroupName} basePageUrl={this.props.basePageUrl} groupId={this.props.groupId}/>:''}
							{wikiDescription}
							{this.props.activeSection === 'homepage' && <div className="gradient-col"><Anchor onClick={this.trackViewDetailEventWrap.bind(this)} to={this.props.childPageUrl} className="gradVw-mr button button--secondary arrow">View Details</Anchor></div>}
						</div>
					</div>
			</section>
			</React.Fragment>
		)
	}
}
ExamPrepTips.propTypes = {
	activeSection: PropTypes.string,
	ampCTATrackingKeys: PropTypes.object,
	basePageUrl: PropTypes.string,
	childPageUrl: PropTypes.string,
	deviceType: PropTypes.string,
	examName: PropTypes.string,
	groupId: PropTypes.number,
	groupMapping: PropTypes.array,
	labelName: PropTypes.string,
	originalSectionName: PropTypes.string,
	sectiondata: PropTypes.object,
	sectionname: PropTypes.string,
	selectedGroupName: PropTypes.string,
	trackingKeyList: PropTypes.object,
	viewDetailGACategory: PropTypes.string
};
export default ExamPrepTips;
