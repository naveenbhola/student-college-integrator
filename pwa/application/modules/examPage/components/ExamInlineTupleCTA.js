import React from 'react';
import './../assets/ExamInlineTupleCTA.css';
import ExamResponseDesktop from "./../../common/components/desktop/ResponseDesktop";
import {updateGuideTrackCookie, isGuideDownloaded} from "../utils/examPageHelper";
import PopupLayer from './../../common/components/popupLayer';
import ExamConfig from './../config/ExamConfig';
import PropTypes from 'prop-types';
import config from "../../../../config/config";

class ExamInlineTupleCTA extends React.Component{
	constructor(props) {
		super(props);
		this.state = {
			guideDownloadMsg : 'The exam guide has been sent to your email id. The email also includes important information such as Institutes Accepting this Exam, Related Articles, Questions & Answers, Similar Exams & more.'
		};
	}

	getQuestionPaperMidCTACallback = (response, data) => {
		window.location.href = data.redirectUrl;
	};
	getUpdatesMidCTACallback = (response, data) => {
		if(response['status'] === 'SUCCESS'){
			document.getElementsByClassName(data.elemClass)[0].classList.add('eaply-disabled');
			updateGuideTrackCookie(data.groupId, 'examGuide');
			isGuideDownloaded(data.groupId, 'examGuide');
			this.PopupLayer.open();
		}else{
			this.setState({guideDownloadMsg : 'Some error occurred. Please try after some time.'});
			this.PopupLayer.open();
		}
	};
	closeGetUpdatesCTASuccessPopup = () => {
		window.location.reload();
	};

	render() {
		let gaCategory = 'EXAM'+(this.props.activeSection === 'homepage' ? '' : ' '+this.props.originalSectionName.toUpperCase())+' PAGE';
		let formDataForDownloadSamplePapers = {
			trackingKeyId : ExamConfig[this.props.deviceType][this.props.sectionname]['trackingKeys']['inlineTupleGetQuestionPaper'],
			callbackFunction : 'redirectSamplePaperPage',  //callback from jQuery version in shiksha/public/js/registrationCallbacks.js
			callbackFunctionParams : {groupId : this.props.groupId, redirectUrl : this.props.samplePaperUrl, fromWhere : 'midInlineCTA', examName : this.props.examName, actionType : 'exam_download_sample_paper'},
			callbackObj : '',
			cta : 'exam_download_sample_paper'
		};
		let GetQuestionPaperCTAMid = null;
		if(this.props.isSamplePapersAvailable){
			GetQuestionPaperCTAMid = <ExamResponseDesktop gaCategory={gaCategory} gaWidget={'Inline_Widget'} listingId={this.props.groupId} listingType='exam' actionType={formDataForDownloadSamplePapers.cta} formData={formDataForDownloadSamplePapers} className="button button--secondary" ctaText="Get Question Papers" ctaId="getQuestionPaperMid" ref={() => window.getQuestionPaperMidCTACallback = this.getQuestionPaperMidCTACallback.bind(this)} />;
		}

		let formDataForDownloadGuide = {
			trackingKeyId : ExamConfig[this.props.deviceType][this.props.sectionname]['trackingKeys']['inlineTupleGetUpdates'],
			callbackFunction : 'callDownloadGuide', //callback from jQuery version in shiksha/public/js/registrationCallbacks.js
			callbackFunctionParams : {groupId : this.props.groupId, elemClass : 'mid-dwn-eguide', fromWhere : 'midInlineCTA' , examName : this.props.examName, actionType : 'exam_download_guide'},
			callbackObj : '',
			cta : 'exam_download_guide'
		};
		let GetUpdatesCTAMid = <ExamResponseDesktop gaCategory={gaCategory} gaWidget={'Inline_Widget'} ctaType={'guideDownload'} listingId={this.props.groupId} listingType='exam' actionType={formDataForDownloadGuide.cta} formData={formDataForDownloadGuide} className="button button--orange mid-dwn-eguide" ctaText="Download Guide" ctaId="getUpdatesMid" ref={() => window.getUpdatesMidCTACallback = this.getUpdatesMidCTACallback.bind(this)} />;

		if(this.props.newCTAConfig && this.props.newCTAConfig['ecpMiddleWidgetCtaInfo']){
			if(this.props.editorialPdfData && this.props.editorialPdfData[this.props.activeSection]){
				let formDataForMultipleCta = {
					trackingKeyId : this.props.newCTAConfig['ecpMiddleWidgetCtaInfo']['trackingKeyId'],
					callbackFunction : 'callDownloadGuide', //callback from jQuery version in shiksha/public/js/registrationCallbacks.js
					callbackFunctionParams : {groupId : this.props.groupId, fromWhere : 'multipleCTA', pdfUrl : config().IMAGES_SHIKSHA+this.props.editorialPdfData[this.props.activeSection], examName : this.props.examName , actionType : 'exam_download_guide'},
					callbackObj : '',
					cta : 'exam_download_guide'
				};
				GetQuestionPaperCTAMid = <ExamResponseDesktop gaCategory={gaCategory} gaWidget={'Inline_Widget'} ctaType={'examMultipleCta'} listingId={this.props.groupId} listingType='exam' actionType={formDataForMultipleCta.cta} formData={formDataForMultipleCta} className={'button '+this.props.newCTAConfig['ecpMiddleWidgetCtaInfo']['ctaColor']} ctaText={this.props.newCTAConfig['ecpMiddleWidgetCtaInfo']['ctaText']} ctaId="getMultipleCTAMid" />;
			}
		}

		let h2Heading = <h2 className={'preptips-h2'}>Get all exam details, important updates and more.</h2>;
		if(this.props.isSamplePapersAvailable){
			h2Heading = <h2 className={'preptips-h2'}>Get prep tips, practice papers, exam details and important updates</h2>;
		}
		return (
			<section className={'middleCTA'}>
				<div className="_container">
					<div className="_subcontainer">
						{h2Heading}
						<div className="preptips-flex">
							{GetQuestionPaperCTAMid}
							{GetUpdatesCTAMid}
						</div>
						{<PopupLayer closePopup={this.closeGetUpdatesCTASuccessPopup.bind(this)} onContentClickClose={false} heading={'Download Guide'} onRef={ref => (this.PopupLayer = ref)} data={this.state.guideDownloadMsg}/>}
					</div>
				</div>
			</section>
		);
	}
}
ExamInlineTupleCTA.propTypes = {
	deviceType : PropTypes.string,
	sectionname : PropTypes.string,
	samplePaperUrl : PropTypes.string.isRequired,
	groupId : PropTypes.number.isRequired,
	isSamplePapersAvailable : PropTypes.bool.isRequired
}
export default ExamInlineTupleCTA;
