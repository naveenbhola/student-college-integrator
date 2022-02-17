import React from 'react';
import ExamApplyNow from "../../common/components/ExamApplyNow";
import PopupLayer from "../../common/components/popupLayer";
import PreventScrolling from "../../reusable/utils/PreventScrolling";
import config from './../../../../config/config';
import ExamResponseDesktop from "../../common/components/desktop/ResponseDesktop";

class ExamEditorialCTA extends React.Component{
	constructor(props){
		super(props);
		this.state = {
			showPopup : false
		};
	}

	callBackFunction = () => {
		PreventScrolling.disableScrolling();
		this.setState({showPopup : true},() => {
			if(document.getElementById('filedown')){
				document.getElementById('filedown').click();
			}
		});
	};
	
	closePopup = () => {
      this.setState({showPopup : false});
   	};

	render = () => {
		let editorialPdfUrl = this.props.editorialPdfData && this.props.editorialPdfData[this.props.sectionName] ? config().IMAGES_SHIKSHA + this.props.editorialPdfData[this.props.sectionName] : null;
		if(!editorialPdfUrl){
			return null;
		}
		let msg = <p id="success-msg">Your download should start automatically.<br/>If it does not start automatically {editorialPdfUrl ? <a href={editorialPdfUrl} rel="noopener noreferrer" target="_blank" id="filedown">Click here</a> : 'Click here'} to manually download it.</p>;
		let formDataForDownloadGuide = {
			trackingKeyId : this.props.ctaInfo['trackingKeyId'],
			callbackFunction : 'callDownloadGuide', //callback from jQuery version in shiksha/public/js/registrationCallbacks.js
			callbackFunctionParams : {groupId : this.props.examGroupId, fromWhere : 'multipleCTA', pdfUrl : editorialPdfUrl, 'heading' : this.props.ctaInfo['ctaText'], examName : this.props.examName, actionType : 'exam_download_guide'},
			callbackObj : '',
			cta : 'exam_download_guide'
		};
		return (<React.Fragment>
			{this.props.deviceType === 'mobile' && <ExamApplyNow
				ctaId={this.props.sectionName+'_'+this.props.ctaInfo['trackingKeyId']}
				className={"button "+this.props.ctaInfo['ctaColor']}
				ctaType="GetQuest"
				ctaText={this.props.ctaInfo['ctaText']}
				examGroupId={this.props.examGroupId}
				cta={"getExamMultipleCta"}
				actionType="exam_download_guide"
				trackingKeyId={this.props.ctaInfo['trackingKeyId']}
				gaCategory={this.props.gaCategory}
				gaWidget={this.props.gaWidget+'_'+this.props.sectionName.replace(' ', '_')}
				callBackFunction={this.callBackFunction}
				examName = {this.props.examName}
			/>}
			{this.props.deviceType === 'desktop' && <ExamResponseDesktop
				gaCategory={this.props.gaCategory}
				gaWidget={this.props.gaWidget+'_'+this.props.sectionName.replace(' ', '_')}
				ctaType={'examMultipleCta'}
				listingId={this.props.examGroupId}
				listingType='exam'
				actionType={formDataForDownloadGuide.cta}
				formData={formDataForDownloadGuide}
				className={'button '+this.props.ctaInfo['ctaColor']}
				ctaText={this.props.ctaInfo['ctaText']}
				ctaId={this.props.sectionName+"_"+this.props.ctaInfo['trackingKeyId']}
				examName = {this.props.examName}
				/*ref={() => window.multipleCtaEcpDesktop = this.callBackFunction}*/
			/>}
			{this.state.showPopup && <PopupLayer isPopUpOn={this.state.showPopup} onContentClickClose={false} heading={this.props.ctaInfo['ctaText']} data={msg} onRef={ref => (this.PopupLayer = ref)} closePopup={this.closePopup}/>}
		</React.Fragment>);
	};
}
ExamEditorialCTA.defaultProps = {
	gaCategory : 'Exam Page',
	gaWidget : 'Multiple_CTA',
	deviceType : 'mobile'
};
export default ExamEditorialCTA;
