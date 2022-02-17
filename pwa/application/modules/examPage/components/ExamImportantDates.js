import React from 'react';
import WikiLabel from '../../common/components/WikiLabel';
import WikiContent from '../../common/components/WikiContent';
import ExamDateWidget from './../components/ExamDateWidget';
import ExamChangeCourseLayer from './ExamChangeCourseLayer';
import Anchor from "../../reusable/components/Anchor";
import {trackViewDetailEvent} from "../utils/examPageHelper";
import PropTypes from 'prop-types';
import ExamEditorialCTA from "./ExamEditorialCTA";

class ExamImportantDates extends React.Component{
	constructor(props)
	{
		super(props);
	}

	checkIfDateExist(){
		if(this.props.sectiondata.dates == null){
			return false;
		}else if((this.props.sectiondata.dates.pastDates != null && this.props.sectiondata.dates.pastDates.length > 0) || (this.props.sectiondata.dates.futureDates != null && this.props.sectiondata.dates.futureDates.length > 0)){
			return true;
		}
		return false;
	}

	trackViewDetailEventWrap = () => {
		trackViewDetailEvent(this.props.viewDetailGACategory, this.props.originalSectionName, this.props.deviceType);
	};

	getWikiDescription = () => {
		var self = this;
		var sectionData = new Array();
		if (this.props.sectiondata == null || this.props.sectiondata.wiki == null) {
			if (this.checkIfDateExist()) {
				sectionData.push(<ExamDateWidget cutContent={self.props.activeSection === 'homepage'}
												 defaultText={self.props.defaultText}
												 dates={self.props.sectiondata.dates}
												 sectionname={self.props.sectionname}
												 deviceType={self.props.deviceType} key={'key'}/>);
			} else {
				return null;
			}
		}
		if(this.props.sectiondata && this.props.sectiondata.wiki){
			let html = (this.props.sectiondata.wiki).map(function (data, index) {
				if (self.props.activeSection === 'homepage' && index > 0) {
					return null;
				} else if (self.props.activeSection === 'homepage' && data.entity_type === 'upperWiki') {
					return (<WikiContent cutWikiContent={self.props.activeSection === 'homepage' && index === 0}
										 labelName='entity_type' labelValue='entity_value' key={index} sectiondata={data}
										 order={index} sectionname={self.props.sectionname}
										 deviceType={self.props.deviceType} key={index}/>);
				} else if (self.props.activeSection === 'homepage' && self.props.sectiondata.wiki.length === 1 && self.checkIfDateExist() && data.entity_type === 'upperWiki') {
					return <ExamDateWidget cutContent={self.props.activeSection === 'homepage'}
										   dates={self.props.sectiondata.dates} sectionname={self.props.sectionname}
										   deviceType={self.props.deviceType} key={index}/>
				} else if (self.props.activeSection === 'homepage' && self.props.sectiondata.wiki.length === 1 && self.checkIfDateExist() && data.entity_type !== 'upperWiki') {
					return <ExamDateWidget cutContent={self.props.activeSection === 'homepage'}
										   dates={self.props.sectiondata.dates} sectionname={self.props.sectionname}
										   defaultText={self.props.defaultText} deviceType={self.props.deviceType} key={index}/>
				} else if (self.props.activeSection === 'homepage' && !self.checkIfDateExist()) {
					return (<WikiContent cutWikiContent={self.props.activeSection === 'homepage'} labelName='entity_type'
										 labelValue='entity_value' key={index} sectiondata={data} order={index}
										 sectionname={self.props.sectionname} deviceType={self.props.deviceType} key={index}/>);
				} else if (self.props.activeSection !== 'homepage' && data.entity_type === 'upperWiki') {
					return (
						<React.Fragment key={index}><WikiContent cutWikiContent={self.props.activeSection === 'homepage' && index === 0}
													 labelName='entity_type' labelValue='entity_value' key={index}
													 sectiondata={data} order={index} sectionname={self.props.sectionname}
													 deviceType={self.props.deviceType}/><ExamDateWidget
							cutContent={self.props.activeSection === 'homepage'} dates={self.props.sectiondata.dates}
							sectionname={self.props.sectionname} deviceType={self.props.deviceType}/></React.Fragment>);
				} else if (self.props.activeSection !== 'homepage' && data.entity_type !== 'upperWiki') {
					return (<React.Fragment key={index}>{self.props.sectiondata.wiki.length === 1 &&
					<WikiContent labelName='entity_type' labelValue='labelValue'
								 sectiondata={{labelValue: self.props.defaultText}} order={999}
								 sectionname={self.props.sectionname} deviceType={self.props.deviceType}/>}{index === 0 &&
					<ExamDateWidget cutContent={self.props.activeSection === 'homepage'}
									dates={self.props.sectiondata.dates} sectionname={self.props.sectionname}
									deviceType={self.props.deviceType}/>}<WikiContent
						cutWikiContent={self.props.activeSection === 'homepage' && index === 0} labelName='entity_type'
						labelValue='entity_value' key={index} sectiondata={data} order={index}
						sectionname={self.props.sectionname} deviceType={self.props.deviceType}/></React.Fragment>);
				}
			});
			sectionData.push(html);
		}
		return sectionData;
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
							{this.props.activeSection=="importantdates" && this.props.groupMapping && this.props.groupMapping.length>1?<ExamChangeCourseLayer groupMapping={this.props.groupMapping} selectedGroupName={this.props.selectedGroupName} basePageUrl={this.props.basePageUrl} groupId={this.props.groupId}/>:''}
							{wikiDescription}
							{this.props.activeSection === 'homepage' && <div className="gradient-col">{this.props.newCTAConfig && <ExamEditorialCTA examName={this.props.examName} deviceType={this.props.deviceType} examGroupId={this.props.groupId} {...this.props.newCTAConfig} sectionName={this.props.sectionname} editorialPdfData={this.props.editorialPdfData} />}<Anchor onClick={this.trackViewDetailEventWrap.bind(this)} to={this.props.childPageUrl} className="gradVw-mr button button--secondary arrow">View Details</Anchor></div>}
						</div>
					</div>
				</section>
			</React.Fragment>
		);
	}
}
ExamImportantDates.propTypes = {
	deviceType : PropTypes.string,
	sectiondata : PropTypes.object,
	viewDetailGACategory : PropTypes.string,
	originalSectionName : PropTypes.string,
	labelName : PropTypes.string,
	defaultText : PropTypes.string,
	activeSection : PropTypes.string,
	childPageUrl : PropTypes.string,
	selectedGroupName : PropTypes.string,
	basePageUrl : PropTypes.string,
	groupMapping : PropTypes.array
}
export default ExamImportantDates;
