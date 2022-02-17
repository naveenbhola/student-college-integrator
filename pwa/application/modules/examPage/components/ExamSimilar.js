import React from 'react';
import PropTypes from 'prop-types';
import './../assets/ExamSimilar.css';
import Anchor from './../../reusable/components/Anchor';
import {formatDate, isUserLoggedIn} from './../../../utils/commonHelper';
import FullPageLayer from './../../common/components/FullPageLayer';
import { getRequest } from './../../../utils/ApiCalls';
import APIConfig from './../../../../config/apiConfig';
import ExamApplyNow from './../../common/components/ExamApplyNow';
import {event} from "../../reusable/utils/AnalyticsTracking";
import ExamResponseDesktop from "../../common/components/desktop/ResponseDesktop";
import {isGuideDownloaded, updateGuideTrackCookie} from "../utils/examPageHelper";
import PopupLayer from './../../common/components/popupLayer';

class ExamSimilar extends React.Component{
	constructor(props){
		super(props);
		this.state = {
			layerOpen : false,
			layerHtml : '',
			guideDownloadMsg : 'The exam guide has been sent to your email id. The email also includes important information such as Institutes Accepting this Exam, Related Articles, Questions & Answers, Similar Exams & more.'
		};
		this.pageNum    = 2;
		this.totalPages = this.props.data.totalPages;
		this.callApi    = true;
		this.uniqueKey  = 1;
		this.pageLimit      = (this.props.deviceType === 'mobile') ? 5 : 10; // page limit of similar exams
		this.gaCategory = 'EXAM'+(this.props.activeSection === 'homepage' ? '' : ' '+this.props.originalSectionName.toUpperCase())+' PAGE';
	}
	trackSEEvent = (pageClicked) => {
		let deviceLabel = this.props.deviceType === 'mobile' ? 'MOB' : 'DESK';
		let gaCategory = 'EXAM'+(this.props.activeSection === 'homepage' ? '' : ' '+this.props.originalSectionName.toUpperCase())+' PAGE';
		let actionLabelPostfix = this.props.activeSection === 'homepage' ? 'EXAM_PAGE' : this.props.originalSectionName.toUpperCase().replace(' ', '_')+'_PAGE';
		let actionLabelPrefix = pageClicked.toUpperCase().replace(' ', '_');
		let actionLabel = 'SIMILAR_EXAM_'+actionLabelPrefix+'_'+actionLabelPostfix+'_'+deviceLabel;
		let label = isUserLoggedIn() ? 'Logged-In' : 'Non-Logged In';
		event({category : gaCategory, action : actionLabel, label : label});
	};

	examChildLinks(childData){
		if(childData == '' || (childData && childData.length<=0)){
			return null;
		}
		let childLink = [];
		childData.forEach((value,index)=>{
			let item = value['url'] && <Anchor key={'ec'+index} onClick={this.trackSEEvent.bind(this, value['name'])} className="quick-links" to={value['url']} >{value['name']}</Anchor>;
			let seprator =  <b key={'sp'+index}>|</b>;
			childLink.push(item);
			childLink.push(seprator);
		});
		childLink.pop();
		return <div className="exams_a">{childLink}</div>;
	}

	examDates(dates){
		if(dates == '' || dates == null){
			return;
		}
		let textLength = 30;
		let dateList = [];
		dates.forEach((value,index)=>{
			let startDate = new Date(value['start_date'].replace(/-/g, '/')).getTime();
			let endDate   = new Date(value['end_date'].replace(/-/g, '/')).getTime();
			let item = <tr key={index}>
			        <td className="fix-tdwidth">
			         <p>{formatDate(value['start_date'], 'd m\'y')}{startDate !== endDate && ' - '}</p>
			                {startDate !== endDate && <p>{formatDate(value['end_date'], 'd m\'y')}</p>}
			        </td>
		            <td>
		                <p>{(value['event_name'].length>textLength) ? value['event_name'].substring(0,textLength)+'...' : value['event_name']}</p>
		            </td>
			        </tr>;
			    dateList.push(item);
		});			    
		return(
			<div className="exam_impdates">
                <table>
                    <tbody>
						{dateList}					
                    </tbody>
                </table>
            </div>
			);                                
	}
	getUpdatesSimilarExamCTACallback = (response, data) => {
		if(response['status'] === 'SUCCESS'){
			if(typeof document.getElementsByClassName(data.elemClass+response['listingId']) != 'undefined' && document.getElementsByClassName(data.elemClass+response['listingId']) !== null && document.getElementsByClassName(data.elemClass+response['listingId']).length > 0){
				document.getElementsByClassName(data.elemClass+response['listingId'])[0].classList.add('eaply-disabled');
			}
			updateGuideTrackCookie(response['listingId'], 'examGuide');
			isGuideDownloaded(response['listingId'], 'examGuide');
			this.PopupLayer.open();
		}else{
			this.setState({guideDownloadMsg : 'Some error occurred. Please try after some time.'});
			this.PopupLayer.open();
		}
	};

	closeGetUpdatesCTASuccessPopup = () => {
		if(typeof userHasLoggedIn == 'undefined'){
            window.location.reload();
        }else{
            this.PopupLayer.close();
        }
	};

	createSimilarExams(examdata){
		let itemList = [];
		let formDataForDownloadGuide = {
			trackingKeyId : this.props.trackingKeyList['applyNowSimilar'],
			callbackFunction : 'callDownloadGuide', //callback from jQuery version in shiksha/public/js/registrationCallbacks.js
			callbackFunctionParams : {},
			callbackObj : '',
			cta : 'exam_download_guide'
		};
		examdata.forEach((value,index)=>{
			if(value['name']){
				formDataForDownloadGuide.callbackFunctionParams = {groupId : value['groupId'], elemClass : 'similar-apply', fromWhere : 'similarExamApply', examName : value['name'], actionType : 'exam_download_guide'};
				let groupYear = (value['year']) ? ' '+value['year'] : '';
				let item = <section key={this.uniqueKey++} className="uilp_exam_card">
						                    <div className="exam_topcol">
						                        <div className="exam_date auto_clear">
						                            <div className="exam_name_width">
						                                <p>{value['url'] && <Anchor onClick={this.trackSEEvent.bind(this, 'EXAM_PAGE')} key={'ec'+index} className="exam_title" to={value['url']} title={value['name']+groupYear}>{value['name']+groupYear}</Anchor>}</p>
						                            </div>
						                            {this.props.deviceType === 'mobile' && <ExamApplyNow examName={value['name']} ctaId={'smlrApplyNow'+value['groupId']} examGroupId={value['groupId']} trackingKeyId={this.props.trackingKeyList['applyNowSimilar']} gaCategory={this.gaCategory} gaWidget={'Similar_Exam'} ampCTATrackingKeys={this.props.ampCTATrackingKeys}/>}
													{this.props.deviceType === 'desktop' && <ExamResponseDesktop examName={value['name']} gaCategory={this.gaCategory} gaWidget={'Similar_Exam'} ctaType={'guideDownload'} className={'button button--orange similar-apply'+value['groupId']} ctaText={'Apply Now'} listingId={value['groupId']} listingType={'exam'} actionType={'exam_download_guide'} formData={formDataForDownloadGuide} ref={() => window.getUpdatesSimilarExamCTACallback = this.getUpdatesSimilarExamCTACallback.bind(this)} />}
						                        </div>
						                        {this.examDates(value['dates'])}
						                        {this.examChildLinks(value['childPageLinks'])}
						                    </div>
						                </section>
				itemList.push(item);
			}	
		});
		return itemList;
	}

	getSimilarExamData(){
		let params = new Object();
		params['examId']  = this.props.examId;
		params['groupId'] = (this.props.groupData && this.props.groupData.groupId) ? this.props.groupData.groupId : 0;
		params['pageNum'] = this.pageNum;
		params['basecourseIds'] = (this.props.groupData && this.props.groupData.entitiesMappedToGroup.course) ? this.props.groupData.entitiesMappedToGroup.course : 0;
		let primaryHierarchy    = (this.props.groupData && this.props.groupData.entitiesMappedToGroup.primaryHierarchy) ? this.props.groupData.entitiesMappedToGroup.primaryHierarchy : [];
		let hierarchy           = (this.props.groupData && this.props.groupData.entitiesMappedToGroup.hierarchy) ? this.props.groupData.entitiesMappedToGroup.hierarchy : [];
		let finalHierarchy      = primaryHierarchy.concat(hierarchy);
		params['hierarchyIds']  = [... new Set(finalHierarchy)];
        getRequest(APIConfig.GET_SIMILAR_EXAM+'?data='+Buffer.from(JSON.stringify(params)).toString('base64')).then((response) => {
            if(response.data.data && typeof response.data.data['examTuple'] != 'undefined'){
                if(this.totalPages >= params['pageNum']){
           		     this.pageNum = this.pageNum+1;
           		     let layerHtml = this.createSimilarExams(response.data.data['examTuple']);
           		     const finalList = [...this.state.layerHtml, ...layerHtml];
           		     this.setState({layerHtml : finalList});
           		     this.callApi = true;
           		     this.hideLoader();
                }
            }
        }).catch(function(){});
    }

	getMoreSimilarExams(){
		let ele = document.querySelector('div#fullLayer-inner-content');
		let lastEleTop = (ele.lastChild.offsetTop - ele.lastChild.offsetHeight) - 500;
		if(ele.scrollTop > lastEleTop && this.totalPages >= this.pageNum && this.callApi){
			this.showLoader()
			this.callApi = false;
			this.getSimilarExamData();
		}
	}

	hideLoader(){
		document.getElementById("similar-loader").remove();  
	}

	showLoader(){
		var node = document.createElement("section");  
			node.setAttribute('id','similar-loader');
			node.setAttribute('class','similar-loader');
		var textnode = document.createTextNode("Loading..."); 
			node.appendChild(textnode);
			document.getElementById("fullLayer-inner-content").appendChild(node);  
	}

	viewAllExams(){
		let deviceLabel = this.props.deviceType === 'mobile' ? 'MOB' : 'DESK';
		let gaCategory = 'EXAM'+(this.props.activeSection === 'homepage' ? '' : ' '+this.props.originalSectionName.toUpperCase())+' PAGE';
		let actionLabelPostfix = this.props.activeSection === 'homepage' ? 'EXAM_PAGE' : this.props.originalSectionName.toUpperCase().replace(' ', '_')+'_PAGE';
		let actionLabel = 'SIMILAR_EXAM_VIEW_ALL'+'_'+actionLabelPostfix+'_'+deviceLabel;
		let label = isUserLoggedIn() ? 'Logged-In' : 'Non-Logged In';
		event({category : gaCategory, action : actionLabel, label : label});

		let layerHtml = this.createSimilarExams(this.props.data['examTuple']);
		this.setState({layerOpen : true, layerHtml : layerHtml});
		document.getElementById('fullLayer-container').classList.add('epSimilar');
	}

	closeLayer(){
		this.uniqueKey = 1;
		this.pageNum   = 2;
        this.setState({layerOpen : false, layerHtml : ''});
        document.getElementById('fullLayer-container').classList.remove('epSimilar');
    }

	render()
	{  
		if(typeof this.props.data['examTuple'] == 'undefined' || this.props.data['examTuple'] == '' || this.props.data['examTuple'] == null){
			return null;
		}
		let heading = 'Exams Similar to '+this.props.examName;
		return (
			<React.Fragment>
					<section id="similarExams">
					    <div className="_container">
					        <h2 className="tbSec2">{heading}</h2>
					        <div className="_subcontainer exam_pwa">
					            <div id="similar-exam-inner" className="listof_exams auto_clear">
					    			{this.createSimilarExams(this.props.data['examTuple'].slice(0,this.pageLimit))}            
					            </div>
					            {this.props.data['examTuple'].length > 5 && <div className="viewSectn"><a className="button button--secondary dark arrow" onClick={this.viewAllExams.bind(this)}>View All</a></div>}
					        </div>
					    </div>
					</section>
					<FullPageLayer desktopTableData={this.props.deviceType == 'desktop' ? true : false} scrollFunction={this.getMoreSimilarExams.bind(this)} data={<div className="listof_exams auto_clear">{this.state.layerHtml}</div>} heading={heading} onClose={this.closeLayer.bind(this)} isOpen={this.state.layerOpen} />
					{<PopupLayer closePopup={this.closeGetUpdatesCTASuccessPopup} onContentClickClose={false} heading={'Download Guide'} onRef={ref => (this.PopupLayer = ref)} data={this.state.guideDownloadMsg}/>}
			</React.Fragment>
		)
	}
}
ExamSimilar.propTypes = {
	activeSection: PropTypes.string,
	ampCTATrackingKeys: PropTypes.object,
	data: PropTypes.object,
	deviceType: PropTypes.string,
	examId: PropTypes.number,
	examName: PropTypes.string,
	groupData: PropTypes.object,
	originalSectionName: PropTypes.string,
	trackingKeyList: PropTypes.object
};
export default ExamSimilar;
