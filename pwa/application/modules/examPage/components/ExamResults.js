import React from 'react';
import PropTypes from 'prop-types';
import WikiLabel from '../../common/components/WikiLabel';
import WikiContent from '../../common/components/WikiContent';
import ExamChangeCourseLayer from './ExamChangeCourseLayer';
import ExamEditorialCTA from './ExamEditorialCTA';
import Anchor from './../../reusable/components/Anchor';
import {formatDate} from '../../../utils/commonHelper';
import '../assets/Results.css';
import {trackViewDetailEvent} from "../utils/examPageHelper";

class ExamResults extends React.Component{
	getWikiDescription(){
      let self = this;
      let sectionData  = new Array();
      if(this.props.sectiondata == null || this.props.sectiondata.wiki == null){
      	return sectionData;
      }
      let html = (this.props.sectiondata.wiki).map(function (data, index){
               return (<WikiContent cutWikiContent={self.props.activeSection === 'homepage'} labelName='entity_type' labelValue='entity_value' key={index} sectiondata={data} order={index} sectionname={self.props.sectionname} deviceType={self.props.deviceType}/> );
      });
      sectionData.push(html);
      return sectionData;
    }
	trackViewDetailEventWrap = () => {
		trackViewDetailEvent(this.props.viewDetailGACategory, this.props.originalSectionName, this.props.deviceType);
	};

    getDateSection(){
    	let sectionData  = new Array();
    	let dateHeading = '';
    	if(this.props.sectiondata.date == null){
    		return dateHeading;
    	}
	    let html = (this.props.sectiondata.date).map(function (data){
	    	if(data.start_date && data.start_date!=null){
	    		dateHeading += (data.event_name) ? data.event_name+': ' : 'Result Declaration Date: ';
				dateHeading += formatDate(data.start_date,'d m y');
				if(data.end_date  && data.end_date!=null && data.start_date != data.end_date ) {
	    			dateHeading = dateHeading + ' - '+formatDate(data.end_date,'d m y');
            	}
	    	}	    	
           return dateHeading;
      	});

      	if(dateHeading==''){
      		return '';
      	}else{
      		sectionData.push(html);
      		return (<p className='result-date'>{sectionData}</p>);
      	}
    }

	render()
	{
		let wikiDescription = this.getWikiDescription();
		let dateSection = this.getDateSection();
		return (
			<React.Fragment>
			<section>
			        <div className="_container">
			        	<WikiLabel labelName={this.props.labelName} />
			        	<div className="_subcontainer">
			        		{this.props.activeSection=="results" && this.props.groupMapping && this.props.groupMapping.length>1?<ExamChangeCourseLayer groupMapping={this.props.groupMapping}  selectedGroupName={this.props.selectedGroupName} basePageUrl={this.props.basePageUrl} groupId={this.props.groupId}/>:''}
			        		{dateSection}
							{wikiDescription}
							{this.props.activeSection === 'homepage' && <div className="gradient-col">{this.props.newCTAConfig && <ExamEditorialCTA examName={this.props.examName} deviceType={this.props.deviceType} examGroupId={this.props.groupId} {...this.props.newCTAConfig} sectionName={this.props.sectionname} editorialPdfData={this.props.editorialPdfData} />}<Anchor onClick={this.trackViewDetailEventWrap.bind(this)} to={this.props.childPageUrl} className="gradVw-mr button button--secondary arrow">View Details</Anchor></div>}
						</div>
					</div>
			</section>
			</React.Fragment>
		)
	}
}
ExamResults.propTypes = {
	activeSection: PropTypes.string,
	basePageUrl: PropTypes.string,
	childPageUrl: PropTypes.string,
	deviceType: PropTypes.string,
	groupMapping: PropTypes.array,
	labelName: PropTypes.string,
	originalSectionName: PropTypes.string,
	sectiondata: PropTypes.object,
	sectionname: PropTypes.string,
	selectedGroupName: PropTypes.string,
	viewDetailGACategory: PropTypes.string
};
export default ExamResults;