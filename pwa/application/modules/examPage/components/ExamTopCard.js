import React from 'react';
import PropTypes from 'prop-types';
import BreadCrumb from './../../common/components/BreadCrumb';
import {formatDate, isUserLoggedIn} from './../../../utils/commonHelper';
import './../assets/TopCard.css';
import ExamApplyNow from './../../common/components/ExamApplyNow';
import {numberFormatter} from '../../../utils/MathUtils';
import Anchor from "../../reusable/components/Anchor";
import {event} from "../../reusable/utils/AnalyticsTracking";
import ExamEditorialCTA from "./ExamEditorialCTA";

class ExamTopCard extends React.Component{
	constructor(props)
	{
		super(props);
	}

	getUpComingDates(){
		if(this.props.examPageData.contentInfo == null || this.props.examPageData.contentInfo.comingDateInfo == null){
			return null;
		}
		let eventlist = this.props.examPageData.contentInfo.comingDateInfo;
		return(
			<div className="admit-sctn">
				<div className="">
				 <p>
					<i className="examicons admit-ico"></i> 
					<strong>{eventlist['categoryName']}:</strong> {(eventlist['startDate'] != eventlist['endDate']) ? formatDate(eventlist['startDate'], 'DD MM yy')+' - '+formatDate(eventlist['endDate'], 'DD MM yy') : formatDate(eventlist['startDate'], 'DD MM yy')}
				</p>	
				</div>
			</div>
		)
	}

	componentDidMount(){
		let self = this;
		if(document.getElementById('discuss-block')!=null){
			document.getElementById('discuss-block').addEventListener('click' , function(){
				self.goTo('ana');
			});
		}
	}

	goTo(targetEle){
		if(document.getElementById(targetEle)){
			let elePositon = document.getElementById(targetEle).getBoundingClientRect().top;
			elePositon = (window.scrollY>elePositon || window.scrollY<elePositon) ? (window.scrollY + elePositon) - 50 : elePositon;
			if(elePositon>0){
				window.scrollTo(0, elePositon);
			}
		}
	}

	getLastDateToApply = () => {
		let html = null;
		if(this.props.examPageData.applyOnline != null){
			if(this.props.examPageData.applyOnline.of_last_date != null){
				let lastDate = formatDate(this.props.examPageData.applyOnline.of_last_date, "d m'y");
				html = <div className="last-date">Last date to apply <strong>{lastDate}</strong></div>
				let applyUrl = this.props.examPageData.applyOnline.of_seo_url;
				if(this.props.examPageData.applyOnline.of_external_url != null && this.props.examPageData.applyOnline.of_external_url !== ''){
					applyUrl = this.props.examPageData.applyOnline.of_external_url;
					html = <div className="last-date">Last date to apply <strong>{lastDate}<Anchor to={applyUrl} link={false}>Apply Now</Anchor></strong></div>;
				}
			}
		}
		return html;
	};

	trackEvent = (linkClicked) => {
		let gaCategory = 'EXAM'+(this.props.activeSection === 'homepage' ? '' : ' '+this.props.originalSectionName.toUpperCase())+' PAGE';
		let actionLabel = linkClicked+'_'+(this.props.deviceType === 'mobile' ? 'MOB' : 'DESK');
		let label = isUserLoggedIn() ? 'Logged-In' : 'Non-Logged In';
		event({category : gaCategory, action : actionLabel, label : label});
	};

	render()
	{
		let updatedOn = (this.props.examPageData && this.props.examPageData.contentInfo && this.props.examPageData.contentInfo.updatedOn) ? this.props.examPageData.contentInfo.updatedOn : null;
		let examName  = (this.props.examPageData.examBasicInfo && this.props.examPageData.examBasicInfo.name) ? this.props.examPageData.examBasicInfo.name : '';
		let examfullName  = (this.props.examPageData.seoData && this.props.examPageData.seoData.h1Tags) ? this.props.examPageData.seoData.h1Tags : '';
		let groupYear = (this.props.examPageData.groupBasicInfo && this.props.examPageData.groupBasicInfo['entitiesMappedToGroup'] && this.props.examPageData.groupBasicInfo['entitiesMappedToGroup'].year && this.props.examPageData.groupBasicInfo['entitiesMappedToGroup'].year[0]) ? ' '+this.props.examPageData.groupBasicInfo['entitiesMappedToGroup'].year[0] : '';
		let totalQues = this.props.examPageData.anaWidget != null ? this.props.examPageData.anaWidget.totalNumber : 0;
		
		let stickyHeading = '';
		if(this.props.deviceType == 'desktop'){
			if(this.props.examPageData.examBasicInfo && this.props.examPageData.examBasicInfo.fullName){
				stickyHeading = this.props.examPageData.examBasicInfo.fullName+groupYear+' ( '+examName+' )';
			}else{
				stickyHeading = examName+groupYear;
			}
		}
		let gaCategory = 'EXAM'+(this.props.activeSection === 'homepage' ? '' : ' '+this.props.originalSectionName.toUpperCase())+' PAGE';
		let isMultiPleCtaShown = false;
		if(this.props.deviceType === 'mobile' && this.props.activeSection !== 'homepage' && this.props.editorialPdfData && Object.keys(this.props.editorialPdfData).length > 0){
			isMultiPleCtaShown = true;
		}
		return (
			<React.Fragment>
				<section>
		        	<div className="_conatiner">
			            <div className={"_subcontainer"}>
							{this.props.deviceType=='mobile' && this.props.breadCrumbData && <BreadCrumb breadCrumb={this.props.breadCrumbData} /> }
			              <div className="rwd-col">	
			              	<div className="exam_wrap">
								
								{this.props.examPageData.examBasicInfo && examfullName &&  <h1 id="pageTitle" className="event_name" data-originalheading={examfullName}>{examfullName}</h1>}
								{stickyHeading && <p id="stikcyTitle" className="hide">{stickyHeading}</p>}
								{updatedOn && <p className="event_status">Updated on <span>{formatDate(this.props.examPageData.contentInfo.updatedOn)}</span> {this.props.examPageData.userInfo != null ? <React.Fragment> by <a onClick={this.trackEvent.bind(this, 'TOP_CARD_AUTHOR_NAME')} href={`${this.props.config.SHIKSHA_HOME}`+"/author/"+`${this.props.examPageData.userInfo.username}`}>{this.props.examPageData.userInfo.firstName + " "+this.props.examPageData.userInfo.lastName}</a></React.Fragment> : null} </p>}
			               	</div>
			               	{totalQues>0?
			               	<div className="discuss-block">
								<a onClick={this.trackEvent.bind(this, 'TOP_CARD_ANSWERED_QUESTIONS')} id="discuss-block" className=""><i className="examicons examqstn-ico"></i>{totalQues>0?numberFormatter(totalQues) + " Answered Questions":''}</a>
			               	</div>:''}
			           	  </div>
			           	  <div className="rwd-right">	
			           		{this.getUpComingDates()}

			               	<div className="flex-mob">
								{this.props.deviceType === 'mobile' && this.props.newCTAConfig && this.props.newCTAConfig[this.props.activeSection] && this.props.newCTAConfig[this.props.activeSection]['ecpTopCardCtaInfo'] && <ExamEditorialCTA examName={examName} gaCategory={gaCategory} gaWidget={'Top_Card'} examGroupId={this.props.groupId} ctaInfo={this.props.newCTAConfig[this.props.activeSection]['ecpTopCardCtaInfo']} sectionName={this.props.activeSection} editorialPdfData={this.props.editorialPdfData} />}
								{this.props.deviceType === 'mobile' && !isMultiPleCtaShown && this.props.examPageData.contentInfo!=null && this.props.examPageData.contentInfo.sectionUrls != null && this.props.examPageData.contentInfo.sectionUrls.samplepapers && <ExamApplyNow examName={examName} gaCategory={gaCategory} gaWidget={'Top_Card'} className="trnBtn" ctaType="GetQuest" ctaText="Get Question Papers" examGroupId={this.props.groupId} cta="examDownloadSamplePaper" actionType="exam_download_sample_paper" trackingKeyId={this.props.trackingKeyList['getQuestionPaperTop']} callBackFunction={this.getQuestionPapersCallBackHandler.bind(this)} ctaId="getQuestionPaperTop" ampCTATrackingKeys={this.props.ampCTATrackingKeys}/>}
								{this.props.deviceType === 'mobile' && <ExamApplyNow examName={examName} ctaId="getUpdatesTop" ctaText="Download Guide" examGroupId={this.props.groupId} cta="examDownloadGuide" actionType="exam_download_guide" gaCategory={gaCategory} gaWidget={'Top_Card'} trackingKeyId={this.props.trackingKeyList['getUpdatesTop']} ampCTATrackingKeys={this.props.ampCTATrackingKeys}/>}
								{this.props.deviceType === 'desktop' && this.props.GetQuestionPaperCTA}
								{this.props.deviceType === 'desktop' && this.props.GetUpdatesCTA}
			               	</div>
							{this.getLastDateToApply()}
			               </div>	
			            </div>
		         	</div>
		      </section>
			</React.Fragment>
		)
	}

	getQuestionPapersCallBackHandler = () => {
		if(this.props.examPageData.contentInfo.sectionUrls != null && this.props.examPageData.contentInfo.sectionUrls.samplepapers != null){
			this.props.history.push(this.props.examPageData.contentInfo.sectionUrls.samplepapers);
		}
	}
}
ExamTopCard.propTypes = {
	examPageData : PropTypes.object,
	activeSection : PropTypes.string.isRequired,
	originalSectionName : PropTypes.string,
	deviceType : PropTypes.string,
	config : PropTypes.object,
	groupId : PropTypes.number,
	trackingKeyList : PropTypes.object,
	ampCTATrackingKeys : PropTypes.object,
	GetQuestionPaperCTA : PropTypes.element,
	GetUpdatesCTA : PropTypes.element,
	history : PropTypes.object
};
export default ExamTopCard;
