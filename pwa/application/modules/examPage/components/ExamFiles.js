import React from 'react';
import './../assets/ExamFiles.css';
import Loadable from 'react-loadable';
import PopupLayer from './../../common/components/popupLayer';
import {isUserLoggedIn, addingDomainToUrl, getUrlParameter, Ucfirst} from '../../../utils/commonHelper';
import config from './../../../../config/config';
import {showResponseFormWrapper} from "../../../utils/regnHelper";
import PropTypes from 'prop-types';
import Analytics from "../../reusable/utils/AnalyticsTracking";
import {storeCTA, isCTAResponseMade} from "../../examPage/utils/examPageHelper";

const ExamResponseForm = Loadable({
  loader: () => import('./../../user/ExamResponse/components/ExamResponseForm'/* webpackChunkName: 'ExamResponseForm' */),
  loading() {return null},
});

class ExamFiles extends React.Component{
	
	constructor(props){
		super(props);
		this.cutFilesContent = true;
		this.state = {enableRegLayer: false, openResponseForm: false, successHeading : '', cta:'', actionType : '', trackingKeyId : 0, clickId : ''};
		this.fileType = '';
		this.fileUrl = '';
		this.headingKey = '';
		this.fileId = '';
	}

	componentDidMount(){
        if(isUserLoggedIn()){
            this.setState({enableRegLayer: true});
        }
    }

	createFileSection(){
		let fileData = this.props.files;
		let filelist = [];
		this.cutFilesContent = true;
		for(let index in fileData) {
			if(fileData[index] == '' || fileData[index].length<=0){
				continue;
			}
			let item = <div key={'fs'+index} className="download_samples">
						<h3>Download {this.props.examName} {this.props.headingMapping[index]} </h3>
						<div className="samples_tiles flex">
								{this.createFiles(fileData[index], index)}	
						</div>
					</div>;
			filelist.push(item);
		}
		return filelist;
	}

    createFiles(filesInfo, downloadType){
    	let files = [], index;
		for(index in filesInfo) {
			if(this.props.cutContent === true && this.cutFilesContent === false){
				break;
			}
			if(filesInfo[index] && filesInfo[index]['fileName']){
				let truncateFileName = (filesInfo[index]['fileName'].length>154) ? filesInfo[index]['fileName'].substring(0,150)+'...' : filesInfo[index]['fileName'].substring(0, (filesInfo[index]['fileName'].length -4));
				let item = <div key={'f'+index} className="sample_tuple"  id={downloadType+'_'+(parseInt(index)+1)}>
					<div className="sample_paperimg">
						<img data-original={addingDomainToUrl(filesInfo[index]['imageUrl'], config().IMAGES_SHIKSHA)} alt={filesInfo[index]['fileName']} className="lazy"/>
	
					</div>
					<div className="sample_dscrptn">{truncateFileName} <button type="button" className="button button--secondary" onClick={this.doRegistration.bind(this, downloadType, filesInfo[index]['url'], downloadType+'_'+(parseInt(index)+1))} >Download</button> </div>
				</div>;
				files.push(item);
				if(this.props.cutContent === true && this.cutFilesContent === true && index > 0){
					this.cutFilesContent = false;
					break;
				}
			}
		}
		if(this.props.cutContent === true && index == 0){
			this.cutFilesContent = false;
		}
		return files;
    }

	render()
	{
		if(this.props.files == null || this.props.files == '' || (this.props.files && this.props.files.length<=0)){
			return null;
		}
		let msg = <p id="success-msg">Your download should start automatically.<br/>If it does not start automatically <a href={this.fileUrl} rel="noopener noreferrer" target="_blank" id="filedown">Click here</a> to manually download it.</p>
		return (
			<React.Fragment>
				{this.props.defaultText && this.props.defaultText !== '' && <p>{this.props.defaultText}</p>}
					{this.createFileSection()}
					{this.props.deviceType === 'mobile' && this.state.enableRegLayer && <ExamResponseForm examName={this.props.examName} callBackFunction={this.responseCallBack.bind(this)} openResponseForm={this.state.openResponseForm} examGroupId={this.props.examGroupId} cta={this.state.cta} actionType={this.state.actionType} trackingKeyId={this.state.trackingKeyId}  onClose={this.closeRegistration.bind(this)} clickId={this.state.clickId} /> }
                	{<PopupLayer onContentClickClose={false} heading={this.state.successHeading} onRef={ref => (this.PopupLayer = ref)} data={msg}/>}
			</React.Fragment>
		)
	}

	trackEvent = (actionLabel, label)=>{
		if(!this.props.gaCategory)
			return;
		const categoryType = this.props.gaCategory;
		Analytics.event({category : categoryType, action : actionLabel, label : label});
	};

	doRegistration(downloadType,fileUrl, fileId){
		this.trackEvent(this.props.gaAction[downloadType]+'_'+Ucfirst(this.props.deviceType), 'click');
		this.fileType = (this.props.headingMapping[downloadType] == 'Preparation Tips') ? 'guidePrepTip' : downloadType;
		this.fileUrl  = addingDomainToUrl(fileUrl, config().IMAGES_SHIKSHA);
		this.headingKey = downloadType;
		this.fileId = fileId;
		if(this.props.deviceType === 'desktop'){
			this.setState({...this.state, cta : this.props.ctaMappings[this.fileType].cta, actionType : this.props.ctaMappings[this.fileType].actionType, trackingKeyId: this.manageTrackingKey(), clickId : this.fileId});
			let formData = {
				trackingKeyId : this.manageTrackingKey(),
				callbackFunction : 'startDownloading', //callback from jQuery version in shiksha/public/js/registrationCallbacks.js
				callbackFunctionParams : {groupId : this.props.examGroupId, fileUrl : this.fileUrl},
				callbackObj : '',
				cta : this.props.ctaMappings[this.fileType].actionType
			};

			if(isUserLoggedIn() && isCTAResponseMade(this.props.examGroupId, this.props.ctaMappings[this.fileType].actionType)){
            	this.startDownloadingCallback();            
        	}else{
        		showResponseFormWrapper(this.props.examGroupId, this.props.ctaMappings[this.fileType].actionType, 'exam', formData);
				window.startDownloadingCallback = this.startDownloadingCallback.bind(this);	
        	}
			return;
		}
        
        if(isUserLoggedIn() && isCTAResponseMade(this.props.examGroupId, this.props.ctaMappings[this.fileType].actionType)){
            this.startDownloadingCallback();            
        }else{
	        ExamResponseForm.preload().then(()=>{
	            this.setState({enableRegLayer: true});
	            this.callRegLayer();
	        });
    	}
    }

	startDownloadingCallback(){
		this.successMsg();
	}

    successMsg(){
		this.setState({successHeading : 'Downloading '+this.props.headingMapping[this.headingKey]});
		this.PopupLayer.open();
		setTimeout(function(){if(document.getElementById('filedown')){ document.getElementById('filedown').click();}},300);
		storeCTA(this.props.examGroupId, this.props.ctaMappings[this.fileType].actionType);
    }

     manageTrackingKey(){
        let clickId     = getUrlParameter('clickId');
        let trackingKeyId = 0;
        if(clickId && typeof this.props.ampCTATrackingKeys != 'undefined' && this.props.ampCTATrackingKeys){
        	trackingKeyId = this.props.ampCTATrackingKeys[clickId];
        }
        return (trackingKeyId>0) ? trackingKeyId : this.props.trackingKeyList[this.fileType];
    }

    callRegLayer(){
        this.setState({...this.state, openResponseForm : true, cta : this.props.ctaMappings[this.fileType].cta, actionType : this.props.ctaMappings[this.fileType].actionType, trackingKeyId: this.manageTrackingKey(), clickId : this.fileId});
    }

    closeRegistration(){
        this.setState({openResponseForm:false, cta:'', actionType:'', trackingKeyId:0, clickId:''});
    }

    responseCallBack(response){
    	this.closeRegistration();
        if(response.userId && response.status == 'SUCCESS'){    
            this.successMsg();
        }
    }
}
ExamFiles.defaultProps={
	deviceType : 'mobile',
	cutContent : false,
	headingMapping : {	'samplePaperData' : 'Question Papers','guidePaperData' : 'Prep Guides' ,'guidePrepTip' : 'Preparation Tips'},
	gaAction : {'samplePaperData' : 'QuestionPaperDownload','guidePaperData' : 'PrepGuideDownload' ,'guidePrepTip' : 'PreparationTipsDownload'},
	ctaMappings    : {	'samplePaperData' : {'cta':'examDownloadSamplePaper',
											 'actionType':'exam_download_sample_paper'},
					  	'guidePaperData'  : {'cta':'examDownloadPrepGuide',
											 'actionType':'exam_download_prep_guide'},
						'guidePrepTip'    : {'cta':'examDownloadPrepTip',
											 'actionType':'exam_download_prep_tip'}}
};

ExamFiles.propTypes = {
	deviceType : PropTypes.string,
	cutContent : PropTypes.bool,
	examName : PropTypes.string,
	headingMapping : PropTypes.object,
	ctaMappings : PropTypes.object,
	examGroupId : PropTypes.number,
	ampCTATrackingKeys : PropTypes.object,
	trackingKeyList : PropTypes.object,
	files : PropTypes.object
}
export default ExamFiles;
