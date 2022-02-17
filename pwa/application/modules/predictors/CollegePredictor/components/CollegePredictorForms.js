import React from 'react';
import PropTypes from 'prop-types';
import {connect} from 'react-redux';
import {bindActionCreators} from 'redux';
import {fetchCollegePredictorData} from './../actions/CollegePredictorAction';
import ErrorMsg from './../../../common/components/ErrorMsg';
import Form1 from './Step1';
import Form2 from './Step2';
import Form3 from './Step3';
import './../assets/CollegePredictor.css';
import {isErrorPage, isServerSideRenderedHTML} from './../../../reusable/utils/commonUtil';
import ElasticSearchTracking from './../../../reusable/utils/ElasticSearchTracking';
import TagManager  from './../../../reusable/utils/loadGTM';
import {getThirdFormData, getTrackingParams} from './../utils/collegePredictorHelper';
import config from './../../../../../config/config';
import ClientSeo from './../../../common/components/ClientSeo';
import {resetGNB, isUserLoggedIn} from "../../../../utils/commonHelper";
import FormContentLoader from '../utils/FormContentLoader';
import predictorConfig from './../config/predictorsConfig';
import {getUserFormDataObjectFromUrl, getRegistrationPrams, getResultType} from "../utils/collegePredictorHelper";
import {postRequestAPIs} from "../../../../utils/ApiCalls";
import APIConfig from "../../../../../config/apiConfig";
import {event} from './../../../reusable/utils/AnalyticsTracking';
import PreventScrolling from "../../../reusable/utils/PreventScrolling";

class CollegePredictorForms extends React.Component{
	constructor(props)
	{
		super(props);
		let queryParamState = this.initiateStateIfQueryParamsAvailable();
		this.state = {
			step:1,
			selectedExams : queryParamState['selectedExams'],
			examScores : queryParamState['examScores'],
			userPersonalData : queryParamState['userPersonalData'],
			form3Data : {},
			showResultPage : false,
			isShowLoader : false
		};
		this.nextStep = this.nextStep.bind(this);
		this.previousStep = this.previousStep.bind(this);
		this.reFormatData();
	}
	reFormatData = () => {
		this.domicileStateMap = {};
		this.categoryMap = {};
		this.examDetails = {};
		this.orderedExams = [];
		let {exams} = this.props.cpData;
		if(exams){
			for (let i in exams){
				if(exams.hasOwnProperty(i)){
					this.examDetails[exams[i].id] = exams[i];
					this.orderedExams.push(exams[i].id);
				}
			}
		}
	};

	componentDidMount(){
		window.scrollTo(0, 0);
		if(this.props.deviceType === 'desktop'){
			//resetGNB();
		}
		if(!isServerSideRenderedHTML('collegeShortlist')){
        	this.initialFetchData();	
		}else if(!isErrorPage()){
            	this.trackGTM();
		}
    }
    componentWillUnmount = () => {
		//resetGNB();
	};

	initialFetchData()
  	{
      let self = this;
      this.setState({isShowLoader : true});
      let fetchPromise = this.props.fetchCollegePredictorData();
	  window.scrollTo(0, 0);
      fetchPromise.then(function(){
		  self.reFormatData();
		  let newState = self.initiateStateIfQueryParamsAvailable();
		  newState.isShowLoader = false;
		  self.setState(newState);
		  self.trackGTM();
      });
  	}

  	initiateStateIfQueryParamsAvailable = () => {
		let urlParams = getUserFormDataObjectFromUrl(this.props.location.search);
		let initialState = {};
		initialState.selectedExams = [];
		if(typeof urlParams['se'] != 'undefined' && Array.isArray(urlParams['se'])){
			initialState.selectedExams = urlParams['se'];
		}
		initialState.examScores = {};
		if(typeof urlParams['sc'] != 'undefined' && Array.isArray(urlParams['sc'])){
			initialState.examScores = urlParams['sc'];
		}
		initialState.userPersonalData = {};
		if(typeof urlParams['gender'] != 'undefined' && urlParams['gender'] > 0){
			initialState.userPersonalData.gender = urlParams['gender'];
		}
		if(typeof urlParams['category'] != 'undefined' && urlParams['category'] > 0){
			initialState.userPersonalData.userCategory = urlParams['category'];
		}
		return initialState;
	};

  	trackGTM =(examId='', isBeaconCall = true)=>{
  		let trackParams = getTrackingParams('', examId);
		if(typeof trackParams != 'undefined' && trackParams){
			if(isBeaconCall){
				ElasticSearchTracking(trackParams.beaconTrackData, config().BEACON_TRACK_URL);
			}
			TagManager.dataLayer({dataLayer:trackParams.gtmParams, dataLayerName:'dataLayer'});
		}
  	};

	nextStep(data){
		window.scrollTo(0, 1);
		let nextStep = this.state.step+1;
		let nextState = {step:nextStep};
		if(this.state.step === 1){
			event({category : this.props.gaTrackingCategory, action : 'Form_1_Next', label : 'click'});
			nextState.selectedExams = data;
			nextState.showResultPage = false;
			this.setState(nextState);
			if(this.props.deviceType === 'desktop'){
				this.props.manageActiveSteps(nextStep);
			}
			if(data && data.length){
				this.trackGTM(data, false);
			}
		}else if(this.state.step === 2){
			event({category : this.props.gaTrackingCategory, action : 'Form_2_Next', label : 'click'});
			getThirdFormData(this.state.selectedExams).then((res)=>{
				nextState.form3Data = res.data;
				this.formatDataForTracking(res.data);
				nextState.examScores = data;
				nextState.showResultPage = false;
				this.setState(nextState);
				if(this.props.deviceType === 'desktop'){
					this.props.manageActiveSteps(nextStep);
				}
			});
		}else if(this.state.step === 3){
			event({category : this.props.gaTrackingCategory, action : 'Form_3_Submit', label : 'click'});
			nextState.userPersonalData = data;
			nextState.step = 3;
			nextState.showResultPage = true;
			this.setState(nextState);
		}
	}

	generateResultPageUrl=()=>{
		let userData = [], examId;
		let examArr = [], examScore = [];
		for (let i in this.state.selectedExams){
			if(this.state.selectedExams.hasOwnProperty(i)){
				examId = this.state.selectedExams[i];
				if(this.state.examScores[examId] !== ''){
					examArr.push('se[]='+examId);
					examScore.push('sc['+examId+']='+this.state.examScores[examId]);
				}
			}
		}
		userData.push(examArr.join('&'));
		userData.push(examScore.join('&'));
		userData.push('gender='+this.state.userPersonalData.gender);
		userData.push('category='+this.state.userPersonalData.userCategory);
		if(typeof this.state.userPersonalData.userDomState !== 'undefined' && this.state.userPersonalData.userDomState){
			userData.push('ds='+this.state.userPersonalData.userDomState);
		}
		for(let examId in this.state.userPersonalData.userExamSpcCategory){
			let userExamSpcCategory = this.state.userPersonalData.userExamSpcCategory[examId];
			if(typeof userExamSpcCategory !== 'undefined'){
				userData.push('esc['+examId+']='+userExamSpcCategory);
			}
		}
		return this.props.resultPageUrl+'?'+userData.join('&');
	};

	loadResultPage = () => {
		this.doRegistration();
	};

	doRegistration = () => {
		let url     = this.generateResultPageUrl();
		let otherData = {
			trackUserInputData : this.trackUserInputData.bind(this, url),
			userInputTrackingDataStr : Buffer.from(JSON.stringify(this.getUserInputDataForTracking())).toString('base64')
		};
		let regData = getRegistrationPrams(config().SHIKSHA_HOME, predictorConfig.registrationCustomFields, url, this.props.deviceType, otherData);
		if(this.props.deviceType === 'desktop'){
			this.props.showRegistrationFormWrapper(regData);
		}else if(isUserLoggedIn() && url){
			this.trackUserInputData(url);
			//this.props.history.push(url);
		}else{
			window.location.href = regData; // AMP login and registration url
		}
	};
	formatDataForTracking = (form3data) => {
		let categoryMap = {}, domicileStateMap = {};
		if(form3data['categories']['mainCategory']){
			form3data['categories']['mainCategory'].map((cat)=>{
				categoryMap[cat.id] = cat['fullName'];
			});
		}
		if(form3data['categories']['examSpecificCategories']){
			for(let examId in form3data['categories']['examSpecificCategories']){
				if(form3data['categories']['examSpecificCategories'].hasOwnProperty(examId) && form3data['categories']['examSpecificCategories'][examId]['categories']){
					form3data['categories']['examSpecificCategories'][examId]['categories'].map((cat)=>{
						categoryMap[cat.id] = cat['fullName'];
					});
				}
			}
		}
		if(form3data['states']){
			form3data['states'].map((state)=>{
				domicileStateMap[state['state_id']] = state['state_name'];
			});
		}
		this.categoryMap = categoryMap;
		this.domicileStateMap = domicileStateMap;
	};
	trackUserInputData = (url) => {
		let trackingData = this.getUserInputDataForTracking();
		postRequestAPIs(APIConfig.GET_TRACK_COLLEGE_PREDICTOR_INPUTS, trackingData).then().catch((err)=> console.log('Track Inputs Error::', err));
		this.props.history.push(url);
	};
	getUserInputDataForTracking = () => {
		let trackingData = [], rawObject = {};
		for (let examId in this.state.examScores){
			if(this.state.examScores.hasOwnProperty(examId)){
				rawObject = {};
				rawObject.examName = this.examDetails[examId]['name'];
				rawObject.result = this.state.examScores[examId];
				rawObject.resultType = getResultType(examId);
				rawObject.categoryName = this.categoryMap[this.state.userPersonalData.userCategory];
				if(this.state.userPersonalData && typeof this.state.userPersonalData.userExamSpcCategory[examId] !== 'undefined' && this.state.userPersonalData.userExamSpcCategory[examId] !== ''){
					rawObject.categoryName = this.categoryMap[this.state.userPersonalData.userExamSpcCategory[examId]];
				}
				rawObject.homestate = 'All India';
				if(this.state.userPersonalData.userDomState !== undefined && this.state.userPersonalData.userDomState !== ''){
					rawObject.homestate = this.domicileStateMap[this.state.userPersonalData.userDomState];
				}
				trackingData.push(rawObject);
			}
		}
		return {"inputs":trackingData};
	};

	previousStep(){
		let prevStep = this.state.step-1;
		event({category : this.props.gaTrackingCategory, action : 'Form_'+this.state.step+'_Back', label : 'click'});
		let nextState = {step : prevStep, showResultPage : false};
		this.setState(nextState);
		if(this.props.deviceType === 'desktop'){
			this.props.manageActiveSteps(prevStep);
		}
	}

	getForm(){
		switch (this.state.step) {
			case 2:
				return <Form2 nextStep={this.nextStep} previousStep={this.previousStep} examScores={this.state.examScores} deviceType={this.props.deviceType} examDetails={this.examDetails} orderedExams={this.orderedExams} selectedExams={this.state.selectedExams} gaTrackingCategory={this.props.gaTrackingCategory} />;
			case 3:
				return <Form3 previousStep={this.previousStep} nextStep={this.nextStep} examScores={this.state.examScores} deviceType={this.props.deviceType} categoriesData={this.state.form3Data.categories} domicileStates={this.state.form3Data.states} userPersonalData={this.state.userPersonalData} gaTrackingCategory={this.props.gaTrackingCategory} />;
			default:
				return <Form1 nextStep={this.nextStep} examList={this.examDetails} orderedExams={this.orderedExams} deviceType={this.props.deviceType} selectedExams={this.state.selectedExams} gaTrackingCategory={this.props.gaTrackingCategory} />;
		}
	}

	renderLoader() {
		PreventScrolling.enableScrolling(true);
    	return <FormContentLoader deviceType={this.props.deviceType} />;
  	}

	render()
	{
		const {cpData} = this.props;
		if(this.state.isShowLoader || (typeof cpData.exams != 'undefined' && Object.keys(cpData.exams).length === 0)){
      		return this.renderLoader();
    	}

		if(typeof cpData.exams == 'undefined' || cpData.exams == null) {
			return <ErrorMsg/>;
		}
		
		let seoData = (cpData && cpData.seoData) ? cpData.seoData : '';
		if(this.state.showResultPage){
			this.loadResultPage();
		}

		return(
			<div id="collegeShortlist" className={'collegeShortlist'+(this.props.deviceType === 'desktop' ? ' cpDesktop' : ' cpMobile')}>
			{ClientSeo(seoData)}
				<div className="pwa_banner">
					<section className="head-strip">
						<div id="cp-banner">
							<div className="clg-bg">
								<div className="clg-txBx">
									   <h1>College Predictor</h1>
									   <p>Predict Colleges based on engineering exams you have taken.</p>
								</div>
							</div>
						</div>
					</section>
				</div>
				<div className="pwa_pagecontent">
					<div className="pwa_container">
						<div className="form-area shadow-box clearFix">
							{this.props.deviceType === 'desktop' && this.props.getUserSteps}
							<div className="col-forms fltryt">
								{this.getForm()}
							</div>
						</div>
					</div>
				</div>
			</div>
		);
		
	}
}

CollegePredictorForms.defaultProps = {
	deviceType: 'mobile',
	resultPageUrl: '/college-predictor-results',
	gaTrackingCategory : 'College_Predictor_Mobile'

};

function mapStateToProps(state){
	return{
		cpData : state.collegePredictorData
	}
}
function mapDispatchToProps(dispatch){
	return bindActionCreators({fetchCollegePredictorData},dispatch);
}
export default connect(mapStateToProps,mapDispatchToProps)(CollegePredictorForms);

CollegePredictorForms.propTypes = {
  cpData: PropTypes.object.isRequired,
  deviceType: PropTypes.string.isRequired,
  fetchCollegePredictorData: PropTypes.func,
  getUserSteps: PropTypes.element,
  history: PropTypes.object,
  location: PropTypes.any,
  manageActiveSteps: PropTypes.func,
  resultPageUrl: PropTypes.string,
  showRegistrationFormWrapper: PropTypes.any
}