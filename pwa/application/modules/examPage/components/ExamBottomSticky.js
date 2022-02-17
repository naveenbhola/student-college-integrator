import React from 'react';
import './../assets/BottomSticky.css';
import ExamApplyNow from './../../common/components/ExamApplyNow';
import {DFPBannerTempalte} from "../../reusable/components/DFPBannerAd";
import PropTypes from 'prop-types';
import ExamEditorialCTA from "./ExamEditorialCTA";

class ExamBottomSticky extends React.Component{
	render(){
		let ctaInfo = {
			'label' : 'Download Guide',
			'trackingKeyId' : this.props.trackingKeyList['getUpdatesBottom']
		};
		if(this.props.isFutureDateAvailable){
			ctaInfo.label = 'Set Exam Alert';
			ctaInfo.trackingKeyId = this.props.trackingKeyList['setExamAlert'];
		}
		let gaCategory = 'EXAM'+(this.props.activeSection === 'homepage' ? '' : ' '+this.props.originalSectionName.toUpperCase())+' PAGE';
		let isMultiPleCtaShown = false;
		if(this.props.editorialPdfData && this.props.activeSection !== 'homepage' && Object.keys(this.props.editorialPdfData).length > 0){
			isMultiPleCtaShown = true;
		}
		return (
			<div className={'ctp-SrpBtnDiv1 hide'} id="examBtmCTA">
				<div className='stickyBanner' id='stickyBanner'><DFPBannerTempalte bannerPlace="sticky_banner"/></div>
				<div className="btn-table">
					{this.props.newCTAConfig && this.props.newCTAConfig[this.props.activeSection] && this.props.newCTAConfig[this.props.activeSection]['ecpBottomStickyCtaInfo'] && <ExamEditorialCTA examName={this.props.examName} gaCategory={gaCategory} gaWidget={'Bottom_Sticky'} examGroupId={this.props.groupId} ctaInfo={this.props.newCTAConfig[this.props.activeSection]['ecpBottomStickyCtaInfo']} sectionName={this.props.activeSection} editorialPdfData={this.props.editorialPdfData} />}
					{this.props.questionPaperUrl && !isMultiPleCtaShown && <ExamApplyNow examName={this.props.examName} ctaId="getQuestionPaperBottom" gaCategory={gaCategory} gaWidget={'Bottom_Sticky'} className="button button--secondary" ctaType="GetQuest" ctaText="Get Question Papers" examGroupId={this.props.groupId} cta="examDownloadSamplePaper" actionType="exam_download_sample_paper" trackingKeyId={this.props.trackingKeyList['getQuestionPaperBottom']} callBackFunction={this.getQuestionPapersCallBackHandler.bind(this)} ampCTATrackingKeys={this.props.ampCTATrackingKeys}/>}
					{<ExamApplyNow examName={this.props.examName} ctaId="getUpdatesBottom" gaCategory={gaCategory} gaWidget={'Bottom_Sticky'} ctaText={ctaInfo.label} examGroupId={this.props.groupId} cta="examDownloadGuide" actionType="exam_download_guide" trackingKeyId={ctaInfo.trackingKeyId} ampCTATrackingKeys={this.props.ampCTATrackingKeys}/>}
				</div>
			</div>
		)
	}

	getQuestionPapersCallBackHandler = () => {
		if(this.props.questionPaperUrl){
			this.props.history.push(this.props.questionPaperUrl);
		}
	}
}
ExamBottomSticky.defaultProps = {
	isFutureDateAvailable : false
};
ExamBottomSticky.propTypes = {
   questionPaperUrl: PropTypes.string,
   groupId : PropTypes.number,
   trackingKeyList : PropTypes.object,
   history : PropTypes.object,
   ampCTATrackingKeys : PropTypes.object
};
export default ExamBottomSticky;
