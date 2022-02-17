import React from 'react';
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
import {isUserLoggedIn} from "../../../../utils/commonHelper";
import {showRegistrationFormWrapper} from "../../../../utils/regnHelper";
import ContentLoader from './../utils/contentLoader';

class CollegePredictor extends React.Component{
	constructor(props)
	{
		super(props);
		this.state = {
			step:1,
			selectedExams : [],
			examScores : {},
			form3Data : {},
			isShowLoader : false
		};
		this.nextStep = this.nextStep.bind(this);
		this.previousStep = this.previousStep.bind(this);
		this.examDetails = {};
		for (let i in this.props.cpData){
			if(this.props.cpData.hasOwnProperty(i)){
				this.examDetails[this.props.cpData[i].id] = this.props.cpData[i];
			}
		}
	}

	componentDidMount(){
		if(!isServerSideRenderedHTML('collegeShortlist')){
        	this.initialFetchData();	
		}else if(!isErrorPage()){
            	this.trackGTM();
		}
    }

    initialFetchData()
  	{
      let self = this;
      this.setState({isShowLoader : true});
      let fetchPromise = this.props.fetchCollegePredictorData();
      fetchPromise.then(function(){
      		self.setState({isShowLoader : false});
      		self.trackGTM();
      });
  	}

  	trackGTM =()=>{
  		let trackParams = getTrackingParams();
		if(typeof trackParams != 'undefined' && trackParams){
			ElasticSearchTracking(trackParams.beaconTrackData, config().BEACON_TRACK_URL);
			TagManager.dataLayer({dataLayer:trackParams.gtmParams, dataLayerName:'dataLayer'});
		}
  	};

	nextStep(data){
		let nextStep = this.state.step+1;
		let nextState = {step:nextStep};
		if(this.state.step === 1){
			nextState.selectedExams = data;
			this.setState(nextState);
			if(this.props.deviceType === 'desktop'){
				this.props.manageActiveSteps(nextStep);
			}
		}else if(this.state.step === 2){
			getThirdFormData(this.state.selectedExams).then((res)=>{
				nextState.form3Data = res.data;
				nextState.examScores = data;
				this.setState(nextState);
				if(this.props.deviceType === 'desktop'){
					this.props.manageActiveSteps(nextStep);
				}
			});
		}else if(this.state.step === 3){
			nextState.userPersonalData = data;
			nextState.step = 3;
			this.setState(nextState);
			//this.loadResultPage();
		}
	}

	generateResultPageUrl=()=>{
		return '/college-predictor-results';
	};

	getRegistrationPrams=()=>{
		let customFields =  {  'stream':{
				'value':2,
				'hidden':1,
				'disabled':0
			},
			'baseCourses':{
				'value':10,
				'hidden':1,
				'disabled':0
			},
			'subStreamSpec':{
				'hidden':1
			},
			'educationType':{
				'value':20,
				'hidden':1,
				'disabled':0
			}
		};
		let formData = { 'trackingKeyId': 123, 'customFields': customFields, 'callbackFunction': 'callBackCollegePredictor', 'callbackFunctionParams':{ 'redirectUrl' : this.generateResultPageUrl()}};

		if(this.props.deviceType === 'desktop'){
			return formData;
		}else{
			return config().SHIKSHA_HOME + "/muser5/UserActivityAMP/getRegistrationAmpPage?actionType=finalStep&fromwhere=allCollegePredictor&referer="+Buffer.from(this.generateResultPageUrl()).toString('base64');
		}
	};

	loadResultPage = () => {
		if(!isUserLoggedIn()){
			this.doRegistration();
		}else{
			//send directly to result page
		}
	};

	doRegistration = () => {
		let regData = this.getRegistrationPrams();
		if(this.props.deviceType === 'desktop'){
			showRegistrationFormWrapper(regData);
		}else{
			window.location.href = regData; // AMP login and registration url
		}
	};

	previousStep(){
		let prevStep = this.state.step-1;
		let nextState = {step:prevStep};
		this.setState(nextState);
		if(this.props.deviceType === 'desktop'){
			this.props.manageActiveSteps(prevStep);
		}
	}

	getForm(){
		switch (this.state.step) {
			case 2:
				return <Form2 nextStep={this.nextStep} previousStep={this.previousStep} examScores={this.state.examScores} deviceType={this.props.deviceType} examDetails={this.examDetails} selectedExams={this.state.selectedExams} />;
			case 3:
				return <Form3 previousStep={this.previousStep} nextStep={this.nextStep} examScores={this.state.examScores} deviceType={this.props.deviceType} categoriesData={this.state.form3Data.categories} domicileStates={this.state.form3Data.states}/>;
			default:
				return <Form1 nextStep={this.nextStep} examList={this.examDetails} deviceType={this.props.deviceType} selectedExams={this.state.selectedExams} />;
		}
	}

	renderLoader() {
    //	PreventScrolling.enableScrolling(true);
    	return <ContentLoader/>;
  	}

	render()
	{
		const {cpData} = this.props;
		//console.log('this.props===',this.props.cpData);
		if(this.state.isShowLoader){
      		return this.renderLoader();
    	}

		if(typeof cpData == 'undefined' || cpData == null) {
			return <ErrorMsg/>;
		}
		let seoData = (cpData && cpData.seoData) ? cpData.seoData : '';
		if(this.state.userPersonalData){
			this.loadResultPage();
			//return this.renderLoader();
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
									   <p>Some dummy text</p>
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

CollegePredictor.defaultProps = {
	deviceType : 'mobile'
};

function mapStateToProps(state){
	return{
		cpData : state.collegePredictorData
	}
}
function mapDispatchToProps(dispatch){
	return bindActionCreators({fetchCollegePredictorData},dispatch);
}
export default connect(mapStateToProps,mapDispatchToProps)(CollegePredictor);
