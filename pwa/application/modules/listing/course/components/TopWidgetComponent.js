import PropTypes from 'prop-types'
import React from 'react';
import { Link } from "react-router-dom";
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import './../assets/courseCommon.css';
import './../assets/topWidget.css';
import PopupLayer from './../../../common/components/popupLayer';
import AmpLightBox from './../../../common/components/AmpLightbox';
import {scrollToElementId,addingDomainToUrl} from './../../../../utils/commonHelper';
import {removeDomainFromUrl} from './../../../../utils/urlUtility';
import {getRupeesDisplayableAmount,renderColumnStructure} from './../utils/listingCommonUtil';
import ChangeBranch from './ChangeBranchLinkComponent';
import ChangeBranchAmp from '../../../../../views/amp/pages/course/components/ChangeBranchAmp';
import Analytics from './../../../reusable/utils/AnalyticsTracking';
import DownloadEBrochure from './../../../common/components/DownloadEBrochure';
import Shortlist from './../../../common/components/Shortlist';
import ApplyNow from './../../../common/components/ApplyNow';
import AggregateReview from './AggregateReviewWidget';
import {storeInstituteDataForPreFilled} from './../../institute/actions/InstituteDetailAction';

function bindingFunctions(functions)
{
	functions.forEach((f) => (this[f] = this[f].bind(this)));
}
class TopWidget extends React.Component
{
	constructor(props)
	{
		super(props);
		this.locationLayerData = [];
		bindingFunctions.call(this,[
			'openApprovalsLayer',
			'openRecognitionLayer',
			'openFeesLayer',
			'openMediumLayer',
			'openRankingLayer',
			'openDurationLayer',
			'scrollToDiv'
		]);
	}

	getInstituteNameWithCityLocality(instituteName,cityName,localityName)
	{
		let lowerCityName = (cityName)?cityName.toLowerCase():"";
		if(instituteName.toLowerCase().indexOf(lowerCityName) == -1){
			cityName = ', '+ cityName;
		}
		else
		{
			cityName = '';
		}
		if(localityName != ''  && localityName != null){
			localityName = ', '+localityName;
		}
		if(localityName == '' || localityName == null)
			localityName = '';
		return instituteName+localityName+cityName;
	}


	handleClickOnInstitute(info)
	{
		this.trackEvent('ChooseILP','click')
		if(typeof info != 'undefined' && typeof info == 'object') {
			var instituteData = {"instituteName": info.instituteName};
			this.props.storeInstituteDataForPreFilled(instituteData);
		}
	}
	durationlayerdata(){
		const {data} = this.props;
		return(
			<div className="pad10">
				{data.tooltipData.durationTooltip}
			</div>
		)
	}
	renderCourseInfo()
	{
		const {data, isAmp} = this.props;
		var courseInfo = data.entryCourseTypeInformation;
		var courseInfoHtml = [];
		var pushPipe = false;
		if(courseInfo != null && typeof courseInfo.course_level != 'undefined' && courseInfo.course_level != null && courseInfo.course_level.name != 'None')
		{
			if(typeof courseInfo.credential != 'undefined' && courseInfo.credential != null && courseInfo.credential.name != 'None')
			{
				if(isAmp){
					courseInfoHtml.push(<li key="credential" className="i-block color-6 f12 font-w6">{courseInfo.course_level.name} {courseInfo.credential.name}</li>);
				}else{
					courseInfoHtml.push(<li key="credential">{courseInfo.course_level.name} {courseInfo.credential.name}</li>);
				}

			}
			else
			{
				isAmp ? courseInfoHtml.push(<li key="credential" className="i-block color-6 f12 font-w6">{courseInfo.course_level.name}</li>): courseInfoHtml.push(<li key="credential">{courseInfo.course_level.name}</li>);
				//courseInfoHtml.push(<li key="credential">{courseInfo.course_level.name}</li>);
			}
			pushPipe = true;
		}
		else if(courseInfo == null && typeof courseInfo.credential != 'undefined' && courseInfo.credential != null && courseInfo.credential.name != 'None')
		{
			isAmp ? courseInfoHtml.push(<li key="credential" className="i-block color-6 f12 font-w6">{courseInfo.credential.name}</li>) : courseInfoHtml.push(<li key="credential">{courseInfo.credential.name}</li>);
			pushPipe = true;
		}
		if(pushPipe)
		{
			isAmp ? courseInfoHtml.push(<li key="sep1">|</li>) : courseInfoHtml.push(<li key="sep1" className="sep-pipe">|</li>);
			pushPipe = false;
		}
		if(data.educationTypeString != null)
		{
			isAmp ? courseInfoHtml.push(<li key="education" className="i-block color-6 f12 font-w6">{data.educationTypeString}</li>) : courseInfoHtml.push(<li key="education">{data.educationTypeString}</li>);
			pushPipe = true;
		}
		if(pushPipe)
		{
			isAmp ? courseInfoHtml.push(<li key="sep2">|</li>) : courseInfoHtml.push(<li key="sep2" className="sep-pipe">|</li>);
			pushPipe = false;
		}
		if(data.durationValue != null && data.durationUnit != null)
		{
			(isAmp) ? courseInfoHtml.push(<li key="duration" className="i-block color-6 f12 font-w6">Duration - {data.durationValue} {data.durationValue > 1 ? data.durationUnit : data.durationUnit.replace(/s$/, '')} {data.showDurationDisclaimer ? <React.Fragment><a className='pos-rl' on='tap:duration-more-data1' tabIndex='0' role='button'><i className="cmn-sprite clg-info i-block v-mdl"></i></a> 	<AmpLightBox onRef={ref => (this.durationLayer = ref)} data={this.durationlayerdata()} heading="Duration" id="duration-more-data1"/></React.Fragment>  : ''}</li>) : courseInfoHtml.push(<li key="duration">{data.durationValue} {data.durationValue > 1 ? data.durationUnit : data.durationUnit.replace(/s$/, '')} {data.showDurationDisclaimer ? <React.Fragment><i className="info-icon" onClick={this.openDurationLayer}></i><PopupLayer onRef={ref => (this.durationLayer = ref)} data={data.tooltipData.durationTooltip} heading="Duration"/></React.Fragment> : ''}</li>);
			pushPipe = true;
		}
		if(courseInfoHtml.length > 0)
			return courseInfoHtml;
	}
	renderNameInfo()
	{

		const {config,data, isAmp} = this.props;
		var AllQuestionCount = (data.anaCountString == "0")?null:data.anaCountString ;
		var AllQuestionUrl = (data.anaWidget)? data.anaWidget.allQuestionURL : data.courseUrl+'/questions';
		AllQuestionUrl = addingDomainToUrl(AllQuestionUrl,config.SHIKSHA_HOME);
		let instituteUrl = data.instituteUrl;
		var ratingData = false;
		if(typeof data.aggregateReviewWidget !='undefined' && data.aggregateReviewWidget != null && data.reviewWidget != null && data.reviewWidget.reviewData != null && data.reviewWidget.reviewData.reviewsData  &&  data.aggregateReviewWidget.aggregateReviewData){
			ratingData = true;
		}
		return (
			<React.Fragment>
				{ (!isAmp) ?
					<div className="clp-hdr">
						<div>
							<span>
								<Link className="link" to={removeDomainFromUrl(instituteUrl)} onClick={()=> {this.trackEvent('ChooseILP','click')}}>{this.getInstituteNameWithCityLocality(data.instituteName,data.currentLocation.city_name,data.currentLocation.locality_name)}</Link>
								<Shortlist className="pwa-shrtlst-ico" actionType="NM_course_shortlist" listingId={data.courseId} trackid="954"  recoEbTrackid="1073" recoCMPTrackid="1074" recoShrtTrackid="1075" recoPageType="EBrochure_RECO_Layer" recoActionType="EBrochure_RECO_Layer" pageType="NM_CourseListing" sessionId="" visitorSessionid="" isCallReco={this.props.isCallReco} clickHandler={this.trackEvent.bind(this,'Shortlist','Response')} page ="coursePage"/>
								<ChangeBranch {...this.props}/>
							</span>
							<h1>{data.courseName}</h1>
							<ul className="caption-list">
								{this.renderCourseInfo()}
							</ul>
							<ul className="aggregate_rating">
								{ratingData &&
								<li>
									<AggregateReview config={config}  uniqueKey= {'Course_'+data.courseId} isPaid ={false} reviewsCount={data.reviewWidget.reviewData.allReviewsCount} reviewUrl = {data.reviewWidget.reviewData.allReviewUrl} aggregateReviewData = {data.aggregateReviewWidget}  gaTrackingCategory="CLP" showAllreviewUrl = {true} showReviewbracket = {true} showReviewBracket={true}/>
								</li>
								}
								{(ratingData && AllQuestionCount !=null  )? <li><span> | </span></li>:''}
								{AllQuestionCount !=null &&
								<li>
									<a className="ans_qst qstn" onClick={this.trackEvent.bind(this,"Click","Header_AnsweredQuestions")} href={AllQuestionUrl}><i className="qstn_ico"></i>{AllQuestionCount}{(AllQuestionCount == "1")? " Answered Question":" Answered Questions"}</a>
								</li>
								}
							</ul>
						</div>
					</div>:  <React.Fragment>
						<div>
							<a href={instituteUrl} className="block l-16 f12 color-b m-5btm font-w4">{this.getInstituteNameWithCityLocality(data.instituteName,data.currentLocation.city_name,data.currentLocation.locality_name)}</a>
							<ChangeBranchAmp id="top-section-contact" {...this.props}/>
						</div>
						<h1 className="color-3 f16 font-w6 word-break">{data.courseName}</h1>
						<ul className="cl-ul">
							{this.renderCourseInfo()}
						</ul>
						<ul className="aggregate_rating">
							{ratingData &&
							<li>
								<div className="new-rating">
									<AggregateReview config={config}isPaid ={data.coursePaid} reviewsCount={data.reviewWidget.reviewData.totalReviews} reviewUrl = {data.reviewWidget.reviewData.allReviewUrl} aggregateReviewData = {data.aggregateReviewWidget} isAmp={true}  showAllreviewUrl={true} showReviewBracket={true} />
								</div>
							</li>
							}
							{(ratingData && AllQuestionCount !=null  )? <li><span> | </span></li>:''}
							{AllQuestionCount !=null &&
							<li>
								<a className="ans_qst qstn ga-analytic" data-vars-event-name="Header_AnsweredQuestions" href={AllQuestionUrl}><i className="qstn_ico"></i>{AllQuestionCount}{(AllQuestionCount == "1")? " Answered Question":" Answered Questions"}</a>
							</li>
							}
						</ul>

					</React.Fragment>
				}

			</React.Fragment>
		);

	}
	renderApprovalsInfo()
	{
		const {data, isAmp} = this.props;
		if(data.recognitions == null || data.recognitions.length == 0)
			return;
		var recogHtml = [];
		if(data.recognitions.length > 1)
		{
			if(isAmp){
				recogHtml.push(
					<React.Fragment key="recognition_1">
						<span key="recognition" className='font-w6 f12 color-3'>{data.recognitions[0].name} Approved</span><a className='block color-b f12 font-w6 ga-analytic' data-vars-event-name='APPROVED_MORE' on='tap:approval-more-data' role='button' tabIndex="0"> +{data.recognitions.length-1} more</a>
						<AmpLightBox onRef={ref => (this.approvalLayer = ref)} data={this.generateApprovalsLayer()} heading="Recognition" id="approval-more-data"/>
					</React.Fragment>
				);
			}else {
				recogHtml.push(<p key="rec-hdng"><span className='clg-label-tip'>Recognition</span></p>);
				recogHtml.push(<span key="recognition">{data.recognitions[0].name} Approved<a className='clp-mrLink' href='javascript:void(0);' onClick={this.openApprovalsLayer}> +{data.recognitions.length-1} more</a></span>);
			}
		}

		else
		{
			if (isAmp) {
				recogHtml.push(<React.Fragment key="recognition">
					<span key="recognition" className='font-w6 f12 color-3'>{data.recognitions[0].name} Approved</span><a className='color-b f12 font-w6' role='button' tabIndex='0' on='tap:approval-more-data'><i className="cmn-sprite clg-info i-block v-mdl"></i></a>
					<AmpLightBox onRef={ref => (this.approvalLayer = ref)} data={this.generateApprovalsLayer()} heading="Recognition" id="approval-more-data"/>
				</React.Fragment>);
			}else {
				recogHtml.push(<p key="rec-hdng"><span className='clg-label-tip'>Recognition<i className='info-icon' onClick={this.openApprovalsLayer}></i></span></p>);
				recogHtml.push(<span key="recognition">{data.recognitions[0].name} Approved</span>);
			}
		}

		return (<React.Fragment key="approvals-main">
				{recogHtml}
				<PopupLayer onRef={ref => (this.approvalLayer = ref)} data={this.generateApprovalsLayer()} heading="Recognition"/>
			</React.Fragment>
		);
	}
	renderRecoginitionInfo()
	{
		const {data, isAmp} = this.props;
		var tempHtml = [];
		if(data.courseAccreditation == null && data.instituteAccrediation == null)
			return;
		if(data.courseAccreditation != null)
		{
			if(isAmp){
				//tempHtml.push(<p key="rec-hdng"><span className="clg-label-tip">Accreditation {data.instituteAccrediation == null && <i className='info-icon' onClick={this.openRecognitionLayer}></i>}</span></p>);
				tempHtml.push(
					<React.Fragment>
						<span key="accredited" className='font-w6 f12 color-3'>{data.courseAccreditation.name} Accredited </span>
						{ data.instituteAccrediation != null && <a className='block color-b f12 font-w6 ga-analytic' data-vars-event-name='ACCREDITED_MORE' on='tap:institute-more-data' role="button" tabIndex="0">+1 more</a> }
						<AmpLightBox onRef={ref => (this.recognitionLayer = ref)} data={this.generateRecognitionLayer()} heading="Accreditation" id="institute-more-data"/>

					</React.Fragment>)
			} else{
				tempHtml.push(<p key="rec-hdng"><span className="clg-label-tip">Accreditation {data.instituteAccrediation == null && <i className='info-icon' onClick={this.openRecognitionLayer}></i>}</span></p>);
				tempHtml.push(<span key="accredited">{data.courseAccreditation.name} Accredited { data.instituteAccrediation != null && <a className='clp-mrLink' href='javascript:void(0);' onClick={this.openRecognitionLayer}> +1 more</a> }</span>)
			}

		}
		else if(data.instituteAccrediation != null)
		{
			if(isAmp){
				//tempHtml.push(<p key="rec-hdng"><span className="clg-label-tip">Accreditation <i className='info-icon' onClick={this.openRecognitionLayer}></i></span></p>);
				tempHtml.push( <React.Fragment>
						<span key="accredited" className='font-w6 f12 color-3'>NAAC {`${data.instituteAccrediation}`} Grade Accredited</span>
						<a className='color-b f12 font-w6' on='tap:institute-more-data' role="button" tabIndex="0"><i className='cmn-sprite clg-info i-block v-mdl'></i></a>
						<AmpLightBox onRef={ref => (this.recognitionLayer = ref)} data={this.generateRecognitionLayer()} heading="Accreditation" id="institute-more-data"/>
					</React.Fragment>
				)
			}else {
				tempHtml.push(<p key="rec-hdng"><span className="clg-label-tip">Accreditation <i className='info-icon' onClick={this.openRecognitionLayer}></i></span></p>);
				tempHtml.push(<span key="accredited">NAAC {data.instituteAccrediation} Accredited</span>)
			}

		}
		return(
			<React.Fragment key="recog-main">
				{tempHtml}
				<PopupLayer onRef={ref => (this.recognitionLayer = ref)} data={this.generateRecognitionLayer()} heading="Accreditation"/>

			</React.Fragment>
		)
	}
	openApprovalsLayer()
	{
		this.approvalLayer.open();
	}
	openRecognitionLayer()
	{
		this.recognitionLayer.open();
	}
	openFeesLayer()
	{
		this.feeLayer.open();
	}
	openMediumLayer()
	{
		this.mediumLayer.open();
	}
	openRankingLayer()
	{
		this.rankingLayer.open()
	}
	openDurationLayer()
	{
		this.durationLayer.open();
	}
	renderRankingHtml()
	{
		const { data, isAmp} = this.props;
		var courseRanking = typeof data.rankingsBySource != 'undefined' && Object.keys(data.rankingsBySource).length > 0 ? data.rankingsBySource : [];

		if(courseRanking.length == 0)
			return;

		var rank =  courseRanking[0];
		if(rank)
		{
			return (
				<React.Fragment>
					{(isAmp) ? <div className="div-border">
						<div className="f12 color-3 font-w6 m-5top">
							Ranked {rank.displayableRank} by <a href={rank.url}> {rank.publisherName} {rank.year}</a>
							{courseRanking.length > 1 && <a className='block color-b f12 font-w6 ga-analytic' data-vars-event-name="RANKED_MORE"  on="tap:rank-more-data" role="button" tabIndex="0">+{(parseInt(courseRanking.length)-1)} more</a>}
							{courseRanking.length > 1 && <AmpLightBox onRef={ref => (this.rankingLayer = ref)} data={this.generateRankingLayer()} heading="Course Ranking" id="rank-more-data"/>}
						</div>
					</div> : <li key="rank">
						<p><strong>Ranked {rank.displayableRank}</strong> by <a href={rank.url}> {rank.publisherName} {rank.year}</a>{courseRanking.length > 1 && <a className='clp-mrLink' href="javascript:void(0)" onClick={this.openRankingLayer}>+{(parseInt(courseRanking.length)-1)} more</a>
						}</p>
						{courseRanking.length > 1 && <PopupLayer onRef={ref => (this.rankingLayer = ref)} data={this.generateRankingLayer()} heading="Course Ranking"/>}
					</li>}
				</React.Fragment>);
		}


	}
	generateRankingLayer()
	{
		const {data, isAmp} = this.props;
		var courseRanking = Object.keys(data.rankingsBySource).length > 0 ? data.rankingsBySource : [];
		var LocationRanking = Object.keys(data.rankingsByLocation).length > 0 ? data.rankingsByLocation : [];
		return (
			<React.Fragment>
				{(isAmp) ? <React.Fragment>
						<ul className="pad10 pt0 rnk-br ">
							{this.generateRankingLayerList(courseRanking)}
						</ul>
						{ LocationRanking.length > 0 && <ul className='pad10 pt0 hide-padding '>
							{this.generateRankingLayerList(LocationRanking)}
						</ul>}
					</React.Fragment>
					: <div className="glry-div">
						<div className="hlp-info">
							<div className="loc-list-col">
								<div className='prm-lst'>
									<div className='amen-box'>
										<ul className='n-more-ul rnk-pb5'>
											{this.generateRankingLayerList(courseRanking)}
										</ul>
										{ LocationRanking.length > 0 && <ul className='n-more-ul rnk-pb5'>
											{this.generateRankingLayerList(LocationRanking)}
										</ul>}
									</div>
								</div>
							</div>
						</div>
					</div>
				}

			</React.Fragment>

		)

	}

	generateRankingLayerList(keyData){
		var list = new Array();
		const isAmp = this.props;
		for(var i in keyData){
			var rurl = keyData[i].url;
			var rank = keyData[i];
			if(keyData[i].publisherName){
				isAmp ? list.push(<li key={i} className="f12 color-6 m-5btm">Ranked {keyData[i].rank} by <a href={rurl}> {keyData[i].publisherName} {keyData[i].year}</a></li>) : list.push(<li key={i}>Ranked {rank.displayableRank} by <a href={rurl}> {keyData[i].publisherName} {keyData[i].year}</a></li>);

			}
			if(keyData[i].locationName){
				isAmp ? list.push(<li key={i} className="f12 color-6 m-5btm">Top Ranked colleges in   <a href={rurl}> {keyData[i].locationName}</a></li>) : list.push(<li key={i}>Top Ranked colleges in   <a href={rurl}> {keyData[i].locationName}</a></li>);
			}
		}
		return list;
	}

	renderAffiliatedHtml()
	{
		const {data, isAmp} = this.props;
		var affiliationData = data.affiliationData != null ? data.affiliationData : null;
		if(affiliationData == null)
			return;
		let options = [];
		if(affiliationData.scope != 'domestic'){
			options['target'] = '_blank';
		}
		return (
			<React.Fragment>
				{(isAmp) ?
					<div className='div-border' key="affiliated">
						<div className='f12 color-3 font-w6 m-5top'>
							{ (affiliationData.url !=null) ? 'Affiliated To': '' }	<a href={affiliationData.url} {...options}>{affiliationData.name}</a>
						</div>
					</div> : 	<li key="affiliated">
						<p>
							Affiliated To <Link to={affiliationData.url} {...options}>{affiliationData.name}</Link>
						</p>
					</li>
				}
			</React.Fragment>
		)
	}
	renderDifficultyHtml()
	{
		const {data, isAmp} = this.props;
		if(data.difficultyLevel == null)
			return;
		return(

			<React.Fragment>
				{	(isAmp) ? <React.Fragment key="dif-main">
					<p key="difficulty" className='color-9 pos-rl f12'>Difficulty Level</p>
					<span className='block color-3 f12 font-w6'>{data.difficultyLevel.name}</span>
				</React.Fragment> : <React.Fragment key="dif-main">
					<p key="difficulty" >Difficulty Level</p>
					<span>{data.difficultyLevel.name}</span>
				</React.Fragment>}
			</React.Fragment>


		)
	}

	renderDates()
	{
		const {data, isAmp} = this.props;
		var displayDate = '';
		var ctaName = '';
		var dateText = '';
		var h ;
		if(data.onlineFormData != null)
		{
			if(data.onlineFormData['of_external_url'] != null & data.onlineFormData['of_external_url'] != '')
			{
				displayDate = data.onlineFormData['of_last_date'];
				ctaName = 'Start Application';
				dateText = 'Last Date to Apply';
				h = <ApplyNow courseId={data.courseId} instituteName={data.instituteName} isInternal={data.onlineFormData['isExternal']} dateText={dateText} displayDate={displayDate} ctaName={ctaName}/>
			}

		}else{
			var impDates = data.importantDates != null && typeof data.importantDates.importantDates != 'undefined' ? data.importantDates.importantDates : [];
			for(var key in impDates)
			{
				if(impDates[key].showUpcoming == true)
				{
					ctaName = 'View all dates';
					displayDate = impDates[key].displayString;
					dateText += (dateText != '') ? (' - '+ impDates[key].eventName) : impDates[key].eventName;
					break;
				}
			}
			if(isAmp){
				h = (dateText) ? <div key="dates" className='dot-div m-top'> <h2 className='f13 color-6 font-w6 pad8 word-break'>{dateText}<span className='f14 color-3 font-w6 pad3'>{displayDate}</span></h2></div> : null;
			}else{
				h = (dateText) ? <div key="dates" className='dot-div'> <h2>{dateText}</h2><p className='apply-t sdf' onClick={this.scrollToDiv}>{displayDate}<a className='link  ga-analytic' data-vars-event-name="VIEWALLDATES">{ctaName}</a></p></div> : null;
			}

		}
		return (h) ? h : null;
	}

	scrollToDiv()
	{
		this.trackEvent('DatesExpand','Subwidget');
		scrollToElementId('admissions');
	}

	generateRecognitionLayer()
	{
		const {data, isAmp} = this.props;
		return (
			<React.Fragment>
				{  (isAmp) ? <ul>
						{ data.courseAccreditation != null && typeof data.tooltipData[data.courseAccreditation.name] != 'undefined' &&
						(<li className='f12 color-6 m-5btm'>
							<strong>{data.courseAccreditation.name}</strong> :
							{data.tooltipData[data.courseAccreditation.name]}
						</li>)
						}
						{ data.instituteAccrediation != null && typeof data.tooltipData[data.instituteAccrediation] != 'undefined' &&
						(<li className='f12 color-6 m-5btm'>
							<strong>NAAC {data.instituteAccrediation} Grade</strong> :

							{data.tooltipData[data.instituteAccrediation]}
						</li>)
						}
					</ul> :

					<div className='glry-div'>
						<div className='hlp-info'>
							<div className='loc-list-col'>
								<div className='prm-lst'>
									{ data.courseAccreditation != null && typeof data.tooltipData[data.courseAccreditation.name] != 'undefined' &&
									(<div className='amen-box'>
										<strong>{data.courseAccreditation.name}</strong>
										<p className='para-L3'>
											{data.tooltipData[data.courseAccreditation.name]}
										</p>
									</div>)
									}
									{ data.instituteAccrediation != null && typeof data.tooltipData[data.instituteAccrediation] != 'undefined' &&
									(<div className='amen-box'>
										<strong>NAAC {data.instituteAccrediation} Grade</strong>
										<p className='para-L3'>
											{data.tooltipData[data.instituteAccrediation]}
										</p>
									</div>)
									}
								</div>
							</div>
						</div>
					</div> }
			</React.Fragment>
		);
	}
	generateMediumLayer()
	{
		const {data, isAmp} = this.props;
		var mediumOfInstruction = data.mediumOfInstruction != null ? data.mediumOfInstruction : [];
		if(mediumOfInstruction.length > 0)
		{
			return (
				<React.Fragment>
					{(isAmp) ? <ul className='color-6'>
						{mediumOfInstruction.map(function(keyData,index){
							return (
								<li key={index} className='f12 color-6 m-5btm'>
									{keyData.name}
								</li>
							)
						})}
					</ul> :<div className='glry-div'>
						<div className='hlp-info'>
							<div className='loc-list-col'>
								<div className='prm-lst'>
									{mediumOfInstruction.map(function(keyData,index){
										return (
											<div key={index} className='amen-box'>
												<strong className='nrml-strng'>{keyData.name}</strong>
											</div>
										)
									})}
								</div>
							</div>
						</div>
					</div>}
				</React.Fragment>
			)
		}
		return
	}
	generateApprovalsLayer()
	{
		const {data, isAmp} = this.props;
		return (
			(isAmp) ? <React.Fragment>
					<ul>
						{ data.recognitions.map(function(keyData,index){
							return (<li className='f12 color-6 m-5btm' key={index}>
								<strong>{keyData.name} : </strong>
								{typeof data.tooltipData[keyData.name] != 'undefined' && data.tooltipData[keyData.name]}
							</li>)
						})}
					</ul>
				</React.Fragment>
				: <div className='glry-div'>
					<div className='hlp-info'>
						<div className='loc-list-col'>
							<div className='prm-lst'>
								{ data.recognitions.map(function(keyData,index){
									return (<div className='amen-box' key={index}>
										<strong>{keyData.name}</strong>
										<p className='para-L3'>
											{typeof data.tooltipData[keyData.name] != 'undefined' && data.tooltipData[keyData.name]}
										</p>
									</div>)
								})}
							</div>
						</div>
					</div>
				</div>
		);
	}
	renderFeeValue()
	{
		const {data, isAmp} = this.props;
		let feeValue = data.currentLocation.fees != null ? data.currentLocation.fees : null;
		let tooltip = false;
		let showDisclaimer = false;
		if(feeValue == null)
		{
			feeValue = data.courseFees!= null && data.courseFees.feesUnitName != null && typeof data.courseFees.feesUnitName != 'undefined' ? data.courseFees.feesUnitName+' ' : '';
			feeValue = data.courseFees != null && data.courseFees.fees != null &&  typeof data.courseFees.fees.totalFees.general != 'undefined' ? feeValue +''+getRupeesDisplayableAmount(data.courseFees.fees.totalFees.general.value) : '';
			if(data.courseFees != null && data.courseFees.fees != null &&  typeof data.courseFees.fees.totalFees != 'undefined' && Object.keys(data.courseFees.fees.totalFees).length > 1)
				tooltip = true;
			if( data.courseFees != null && typeof data.courseFees.fees != 'undefined' && data.courseFees.fees != null && data.courseFees != null && typeof data.courseFees.fees != 'undefined' && data.courseFees.fees != null && typeof data.courseFees.fees.showDisclaimer != 'undefined' && data.courseFees.fees.showDisclaimer)
			{
				showDisclaimer = true;
			}
		}
		else
		{
			var clFees = (typeof data.currentLocation.fees != 'undefined') ? data.currentLocation.fees : {};
			for(var clfKey in clFees)
			{
				if(clFees[clfKey]['category'] == 'general')
				{
					feeValue = clFees[clfKey]['fees_unit_name']+ " "+getRupeesDisplayableAmount(clFees[clfKey]['fees_value']);
				}
			}
			if(Object.keys(clFees).length > 1)
			{
				tooltip = true;
			}
			var listing_location_id = typeof data.currentLocation['listing_location_id'] != 'undefined' ?  data.currentLocation['listing_location_id']  : '';

			if(listing_location_id != '')
			{
				showDisclaimer = data.courseFees != null && data.courseFees.locationWiseFees != null &&  typeof data.courseFees.locationWiseFees[listing_location_id] != 'undefined' ? data.courseFees.locationWiseFees[listing_location_id].showDisclaimer : false;
			}
		}
		let feesTooltipHtml = [];
		if(tooltip)
		{
			if(isAmp){
				feesTooltipHtml.push(<li key="1" className='f12 color-6 m-5btm'>{data.tooltipData.feesTooltip}</li>);
			}else{
				feesTooltipHtml.push(<p key="2">{data.tooltipData.feesTooltip}</p>);
			}
		}
		if(showDisclaimer)
		{

			if(isAmp){
				feesTooltipHtml.push(<li key="5" className='f12 color-6 m-5btm'>{data.tooltipData.feesDisclaimer}</li>);
			}else {
				feesTooltipHtml.push(<br key="3"/>);
				feesTooltipHtml.push(<br key="4"/>);
				feesTooltipHtml.push(<p key="6">{data.tooltipData.feesDisclaimer}</p>);
			}
		}

		if(feeValue != null && feeValue != '')
		{
			return (
				<React.Fragment key="fee-main">
					{(isAmp) ? <React.Fragment>
						<p className="pos-rl color-9 f12 "> Total Fees {typeof data.tooltipData.feesTooltip != 'undefined' && tooltip && <a className='pos-rl' on='tap:fee-more-data'><i className='cmn-sprite clg-info i-block v-mdl'></i></a>}</p>
						<span className='block color-3 f13 font-w6'>{feeValue}</span>
						{typeof data.tooltipData.feesTooltip != 'undefined' && tooltip && <AmpLightBox onRef={ref => (this.feeLayer = ref)} data={feesTooltipHtml} heading="Total Fees" id="fee-more-data" datatype={"addul"}/>}
					</React.Fragment> : <React.Fragment>
						<p><span className='clg-label-tip'>Total Fees {typeof data.tooltipData.feesTooltip != 'undefined' && tooltip && <i className='info-icon' onClick={this.openFeesLayer}></i>}</span></p>
						<span>{feeValue}</span>
						{typeof data.tooltipData.feesTooltip != 'undefined' && tooltip && <PopupLayer onRef={ref => (this.feeLayer = ref)} data={feesTooltipHtml} heading="Total Fees"/>}
					</React.Fragment>
					}

				</React.Fragment>
			);
		}
	}
	renderMediumHtml()
	{
		const {data, isAmp} = this.props;
		let showCount ;
		let mediumInstruction = typeof data.mediumOfInstruction != 'undefined' && data.mediumOfInstruction != null ? data.mediumOfInstruction : [];
		if(mediumInstruction.length == 0)
			return;
		showCount = mediumInstruction.length > 1 ? true : false;
		var mediumName = mediumInstruction[0].name;
		var mediumHtml = [];
		if(mediumName && mediumName != 'English' || showCount)
		{
			if(isAmp){
				mediumHtml.push(<p key='medium' className='color-9 pos-rl f12'>Medium<span className='block color-3 f12 font-w6'>{mediumName} </span></p>);
				mediumHtml.push(<span key='medium-name'>{showCount && <React.Fragment key="frg-medium"><a className='block color-b f12 font-w6 ga-analytic' on='tap:medium-more-data' data-vars-event-name="MEDIUM_MORE"> +{mediumInstruction.length-1} more</a>
					<AmpLightBox onRef={ref => (this.mediumLayer = ref)} data={this.generateMediumLayer()} heading="Mediums of Education" id="medium-more-data"/></React.Fragment>}</span>);
			}else{
				mediumHtml.push(<p key='medium'><span className='clg-label-tip'>Medium</span></p>);
				mediumHtml.push(<span key='medium-name'>{mediumName} {showCount && <React.Fragment key="frg-medium"><a className='clp-mrLink' href='javascript:void(0);' onClick={this.openMediumLayer}> +{mediumInstruction.length-1} more</a>
					<PopupLayer onRef={ref => (this.mediumLayer = ref)} data={this.generateMediumLayer()} heading="Mediums of Education"/></React.Fragment>}</span>);
			}
		}
		return mediumHtml;
	}

	trackEvent(actionLabel,label)
	{
		Analytics.event({category : 'CLP', action : actionLabel, label : label});
	}

	render()
	{
		const {data, isAmp, config} = this.props;
		const courseId = data.courseId;
		const domainName = config.SHIKSHA_HOME;
		return (

			<section>

				{
					(!isAmp) ?  <div className='_container'>
						<div className='_subcontainer'>
							{this.renderNameInfo()}
							<div className='clg-detail'>
								<ul>
									{renderColumnStructure(this, isAmp)}
									{this.renderRankingHtml()}
									{this.renderAffiliatedHtml()}
								</ul>
								{this.renderDates()}
								<div className='dnld-btn'>


									<DownloadEBrochure className="btnYellow req-padding" buttonText="Request Brochure" listingId={data.courseId} trackid="955" recoEbTrackid="1073" recoCMPTrackid="1074" recoShrtTrackid="1075" isCallReco={this.props.isCallReco} clickHandler={this.trackEvent.bind(this,'DownloadBrochure','Response')} page = "coursePage"/>
								</div>
							</div>
						</div>
					</div> :  <div className="card-cmn color-w">
						<div className="data-card m-5btm">
							{this.renderNameInfo()}

							<div>
								<ul className="m-15top cli-i">
									{renderColumnStructure(this, isAmp)}
								</ul>
								{this.renderRankingHtml()}
								{this.renderAffiliatedHtml()}
								{this.renderDates()}
							</div>
							<section className="p1" amp-access="shortlisted" amp-access-hide="1">
								<a className="ga-analytic" href={domainName+data.courseUrl+"?actionType=shortlist&course="+courseId }  data-vars-event-name="SHORTLIST">
									<p className="btn btn-primary color-o color-f f14 font-w7 m-15top" tabIndex="0" role="button"><i className="shortl-list active"></i>Shortlisted</p>
								</a>
							</section>

							<section className="p1" amp-access="NOT validuser AND NOT shortlisted" amp-access-hide="1">
								<a className="ga-analytic" href={domainName+"/muser5/UserActivityAMP/getResponseAmpPage?listingId="+courseId+"&actionType=shortlist&fromwhere=coursepage"} data-vars-event-name="SHORTLIST">
									<p className="btn btn-primary color-o color-f f14 font-w7 m-15top" tabIndex="0" role="button"><i className="shrt-list"></i>Shortlist</p>
								</a>
							</section>
							<section className="p1" amp-access="validuser AND NOT shortlisted" amp-access-hide="1" tabIndex="0">
								<a className="ga-analytic" href={domainName+data.courseUrl+"?actionType=shortlist"} data-vars-event-name="SHORTLIST">
									<p className="btn btn-primary color-o color-f f14 font-w7 m-15top" tabIndex="0" role="button"><i className="shrt-list"></i>Shortlist</p>
								</a>
							</section>
						</div>
					</div>
				}

			</section>
		)
	}

}

TopWidget.defaultProps={
	isAmp : false
}

function mapDispatchToProps(dispatch){
	return bindActionCreators({ storeInstituteDataForPreFilled }, dispatch);
}

export default connect(null,mapDispatchToProps)(TopWidget);

TopWidget.propTypes = {
	config: PropTypes.any,
	data: PropTypes.any,
	isAmp: PropTypes.bool,
	isCallReco: PropTypes.any,
	storeInstituteDataForPreFilled: PropTypes.any
}
