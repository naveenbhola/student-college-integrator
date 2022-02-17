import React from 'react';
import ExamResponseDesktop from "./../../common/components/desktop/ResponseDesktop";
import ExamConfig from './../config/ExamConfig';
import PopupLayer from './../../common/components/popupLayer';
import {updateGuideTrackCookie, isGuideDownloaded} from "../utils/examPageHelper";

class ExamInlineRegistration extends React.Component{
	constructor(props) {
		super(props);
		this.state = {
			guideDownloadMsg : 'The exam guide has been sent to your email id. The email also includes important information such as Institutes Accepting this Exam, Related Articles, Questions & Answers, Similar Exams & more.'
		};
	}

	getInlineRegistationCallback = (response, data) => {
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
		let formDataForDownloadGuide = {
			trackingKeyId : ExamConfig[this.props.deviceType][this.props.sectionname]['trackingKeys']['inlineImageRegitrationForm'],
			callbackFunction : 'callDownloadGuide', //callback from jQuery version in shiksha/public/js/registrationCallbacks.js
			callbackFunctionParams : {groupId : this.props.groupId, elemClass : 'mid-dwn-eguide', fromWhere : 'midInlineRegistration', examName : this.props.examName, actionType : 'exam_download_guide'},
			callbackObj : '',
			cta : 'exam_download_guide'
		};
		let gaCategory = 'EXAM'+(this.props.activeSection === 'homepage' ? '' : ' '+this.props.originalSectionName.toUpperCase())+' PAGE';

		return (
			<section className={'middleCTA'}>
				<div className="_container">
					<div className="tbSec2">
						<h2>Download Exam Guide</h2>
						<p className="lead1 stats">We will also send you insights, recommendations and updates</p>
					</div>
					<div className="_subcontainer">
						<div className="preptips-flex">
							<ExamResponseDesktop ctaTag='image' ctaType={'guideDownload'} listingId={this.props.groupId} listingType='exam' actionType={formDataForDownloadGuide.cta} formData={formDataForDownloadGuide} ctaText="Get Updates" ctaId="getUpdatesMid" ref={myComponent => window.getInlineRegistationCallback = this.getInlineRegistationCallback.bind(this)} gaCategory={gaCategory} gaWidget='INLINE_REGISTRATION'/>
						</div>
						{<PopupLayer closePopup={this.closeGetUpdatesCTASuccessPopup.bind(this)} onContentClickClose={false} heading={'Download Guide'} onRef={ref => (this.PopupLayer = ref)} data={this.state.guideDownloadMsg}/>}
					</div>
				</div>
			</section>
		);
	}
}

export default ExamInlineRegistration;
