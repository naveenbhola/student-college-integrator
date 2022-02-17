import React from 'react';
import PropTypes from 'prop-types';
import Anchor from './../../reusable/components/Anchor';
import './../assets/SamplePapers.css';
import WikiLabel from '../../common/components/WikiLabel';
import WikiContent from '../../common/components/WikiContent';
import ExamChangeCourseLayer from './ExamChangeCourseLayer';
import ExamFiles from './ExamFiles';
import{trackViewDetailEvent} from './../utils/examPageHelper';

class ExamSamplePapers extends React.Component{
	getWikiDescription = () => {
		let gaCategory = 'EXAM' + (this.props.activeSection === 'homepage' ? '' : ' ' + this.props.originalSectionName.toUpperCase()) + ' PAGE';
		var self = this;
		var sectionData = new Array();
		if (this.props.sectiondata == null || this.props.sectiondata.wiki == null) {
			if (self.props.sectiondata && self.props.sectiondata['files']) {
				sectionData.push(<ExamFiles gaCategory={gaCategory} deviceType={self.props.deviceType}
											cutContent={self.props.activeSection==='homepage'} files={self.props.sectiondata['files']}
											defaultText={self.props.defaultText}
											examName={self.props.examName} activeSection={self.props.activeSection}
											examGroupId={self.props.groupId}
											trackingKeyList={self.props.trackingKeyList}
											ampCTATrackingKeys={self.props.ampCTATrackingKeys} key={'key'}/>);
			} else {
				return null;
			}
		}
		if(this.props.sectiondata && this.props.sectiondata.wiki){
			let html = (this.props.sectiondata.wiki).map(function (data, index) {
				if (self.props.activeSection === 'homepage' && index > 0) {
					return null;
				} else if (self.props.activeSection === 'homepage' && data.entity_type === 'upperWiki') {
					return (<WikiContent cutWikiContent={true} labelName='entity_type' labelValue='entity_value' key={index}
										 sectiondata={data} order={index} sectionname={self.props.sectionname}
										 deviceType={self.props.deviceType} key={index}/>);
				} else if (self.props.activeSection === 'homepage' && self.props.sectiondata.wiki.length === 1 && self.props.sectiondata['files'] && data.entity_type !== 'upperWiki') {
					return <ExamFiles gaCategory={gaCategory} deviceType={self.props.deviceType} cutContent={true}
									  files={self.props.sectiondata['files']} examName={self.props.examName}
									  activeSection={self.props.activeSection} examGroupId={self.props.groupId}
									  defaultText={self.props.defaultText}
									  trackingKeyList={self.props.trackingKeyList}
									  ampCTATrackingKeys={self.props.ampCTATrackingKeys} key={index}/>
				} else if (self.props.activeSection === 'homepage' && !self.props.sectiondata['files']) {
					return (<WikiContent cutWikiContent={true} labelName='entity_type' labelValue='entity_value' key={index}
										 sectiondata={data} order={index} sectionname={self.props.sectionname}
										 deviceType={self.props.deviceType} key={index}/>);
				} else if (self.props.activeSection !== 'homepage' && data.entity_type === 'upperWiki') {
					return (<React.Fragment key={index}><WikiContent cutWikiContent={self.props.activeSection === 'homepage'}
														 labelName='entity_type' labelValue='entity_value' key={index}
														 sectiondata={data} order={index}
														 sectionname={self.props.sectionname}
														 deviceType={self.props.deviceType}/><ExamFiles
						gaCategory={gaCategory} deviceType={self.props.deviceType} cutContent={false}
						files={self.props.sectiondata['files']} examName={self.props.examName}
						activeSection={self.props.activeSection} examGroupId={self.props.groupId}
						trackingKeyList={self.props.trackingKeyList}
						ampCTATrackingKeys={self.props.ampCTATrackingKeys}/></React.Fragment>);
				} else if (self.props.activeSection !== 'homepage' && data.entity_type !== 'upperWiki') {
					return (<React.Fragment key={index}>{index === 0 &&
					<ExamFiles gaCategory={gaCategory} deviceType={self.props.deviceType} cutContent={false}
							   files={self.props.sectiondata['files']} examName={self.props.examName}
							   defaultText={self.props.defaultText}
							   activeSection={self.props.activeSection} examGroupId={self.props.groupId}
							   trackingKeyList={self.props.trackingKeyList}
							   ampCTATrackingKeys={self.props.ampCTATrackingKeys}/>}<WikiContent
						cutWikiContent={self.props.activeSection === 'homepage'} labelName='entity_type'
						labelValue='entity_value' key={index} sectiondata={data} order={index}
						sectionname={self.props.sectionname} deviceType={self.props.deviceType}/></React.Fragment>);
				}
			});
			sectionData.push(html);
		}
		return sectionData;
	};
        jeeMainsText(){
    	let date = new Date();
    	return(
    			<table className="predictor-table">
				   <tbody>
				      <tr>
				         <td>
				            <h3><strong><a href="https://www.shiksha.com/b-tech/resources/jee-main-rank-predictor" rel="noopener noreferrer">Predict your JEE Main {date.getFullYear()} ranks with Shiksha's newly launched JEE Main rank predictor tool</a></strong>
				            </h3>
				         </td>
				      </tr>
				   </tbody>
				</table>)
    }
	trackViewDetailEventWrap = () => {
        trackViewDetailEvent(this.props.viewDetailGACategory, this.props.originalSectionName, this.props.deviceType);
	};
	render()
	{
		let wikiDescription = this.getWikiDescription();
		let filterData = '';
		let defaultText = this.props.defaultText;
		if(this.props.sectiondata != null && this.props.sectiondata.wiki != null){
			filterData = this.props.sectiondata.wiki.filter(info => info.entity_type=='upperWiki').map(function (data, index){
		  		return(data);
		  	});
	  		defaultText = (typeof filterData[0]!="undefined" && filterData[0].entity_type=='upperWiki' && filterData[0].entity_value!='')?filterData[0].entity_value:this.props.defaultText;
		}
		
		let jeeMainsText = '';
		if(this.props.examId=='6244'){
			jeeMainsText = this.jeeMainsText();
		}
		let gaCategory = 'EXAM'+(this.props.activeSection === 'homepage' ? '' : ' '+this.props.originalSectionName.toUpperCase())+' PAGE';
		let sectiondata = {}
		sectiondata.labelValue = defaultText;
		return (
			<React.Fragment>
				<section>
			        <div className="_container">
			          <WikiLabel labelName={this.props.labelName} />
			          <div className="_subcontainer">
			          {this.props.activeSection=="samplepapers" && this.props.groupMapping && this.props.groupMapping.length>1?<ExamChangeCourseLayer groupMapping={this.props.groupMapping} selectedGroupName={this.props.selectedGroupName} basePageUrl={this.props.basePageUrl} groupId={this.props.groupId}/>:''}
						  {jeeMainsText}
			            {wikiDescription}
						  {this.props.activeSection === 'homepage' && <div className="gradient-col"><Anchor to={this.props.childPageUrl} onClick={this.trackViewDetailEventWrap.bind(this)} className="gradVw-mr button button--secondary arrow">View Details</Anchor></div>}

			          </div>
			        </div>
			     </section>
			</React.Fragment>
		)
	}
}
ExamSamplePapers.propTypes = {
	activeSection: PropTypes.string,
	ampCTATrackingKeys: PropTypes.object,
	basePageUrl: PropTypes.string,
	childPageUrl: PropTypes.string,
	defaultText: PropTypes.string,
	deviceType: PropTypes.string,
	examId: PropTypes.number,
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
export default ExamSamplePapers;
