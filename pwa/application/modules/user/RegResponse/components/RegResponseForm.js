import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import FormFieldsContent from './FormFieldsContent';
import respFormConfig from './../config/respFormConfig';
import CustomFormLayer from './../../Common/components/CustomFormLayer';
import { makeRandomString } from './../../../../utils/stringUtility';
// import { showResponseForm , getFormByClientCourse , isValidResponseUser , createResponse } from './../actions/ResponseFormAction';
import { isUserLoggedIn } from './../../../../utils/commonHelper';
import Loader from './../../../reusable/components/Loader';
import { getUserDetails } from './../../../common/actions/commonAction';
import Analytics from './../../../reusable/utils/AnalyticsTracking';

class RegResponseForm extends Component {

	constructor(props) {
		super(props);
		this.state = {
			openResponseForm: false,
			regFormId: null,
			formData: null,
			courseRelatedData: null,
			isLoggedInUser: false,
			loader: false,
			onlyOTP: false,
			isNewUser: false
		}
		this.responseFormCTAConfig      = respFormConfig['responseFormCTAConfig'];
		this.headingText      			= respFormConfig['headingText'];
		this.closeResponseLayer         = this.closeResponseLayer.bind(this);
		this.showResponseFormCall       = this.showResponseFormCall.bind(this);
		this.getFormByClientCourseCall  = this.getFormByClientCourseCall.bind(this);
		this.isValidCResponseUser       = this.isValidCResponseUser.bind(this);
		this.createResponseForValidUser = this.createResponseForValidUser.bind(this);
		this.trackResponseEvent         = this.trackResponseEvent.bind(this);
		this.updateNewUserFlag          = this.updateNewUserFlag.bind(this);
		
	}

	componentWillReceiveProps(nextProps) {
		
		if(nextProps.openResponseForm == true) {

			this.fetchFields(nextProps);

			// if(isUserLoggedIn() && nextProps.listingType == 'course') {
			// 	this.isValidCResponseUser(nextProps);
			// } else {
			// 	this.showResponseFormCall(nextProps);
			// }

		} else if(nextProps.openResponseForm == false) {

			if(this.state.openResponseForm){
				this.closeResponseLayer();
			}

		} 

	}

	fetchFields(newProps, isViewedCall) {

		if(typeof(newProps.clientCourseId) == 'undefined'){
            return false;
        }

        var listingType = 'course';
        if(typeof(newProps.listingType) != 'undefined'){
            listingType = newProps.listingType;
        }

        if(typeof(isViewedCall) == 'undefined'){
            isViewedCall = 'no';
        }

		var checkPrefFields = 'yes';
				
        if(isViewedCall == 'yes'){
        	checkPrefFields = 'no';
        } else {
			this.setState({...this.state, loader: true});
        }

		var params        = 'clientCourseId='+newProps.clientCourseId+'&checkPrefFields='+checkPrefFields+'&isViewedCall='+isViewedCall+'&isPWACall=true';
		var self          = this;
		var trackingKeyId = newProps.trackingKeyId;
		var callResp      = getFields(params).then((response) => {
	  		
						        self.setState({...self.state, loader: false});

							    if(typeof(response) !='undefined') {

							    	let onlyOTP = false;
							    	if(response.isValidUser == 'mobile_not_verified') {
										onlyOTP	=  true;
							    	}							 
									
									this.setState({...this.state, loader: true});
									var self     = this;
									var openForm = (typeof(newProps.openResponseForm) !='undefined') ? newProps.openResponseForm : this.props.openResponseForm;
									
							  			
									self.setState({
										openResponseForm: openForm,
										regFormId: makeRandomString(6),
										formData: response,
										isLoggedInUser: isUserLoggedIn(),
										loader: false,
										onlyOTP: onlyOTP
									});
									if(openForm) {
										self.trackResponseEvent(trackingKeyId, 'P1_Load_Successful');
									}									
								}
							});
			
		Promise.resolve(callResp).then((resp) => {
			self.setState({
				courseRelatedData: resp,
				loader: false
			});
		});

	}


	render() {
		if(this.state.loader) {
			return ReactDOM.createPortal(
	            <Loader show={this.state.loader} />,
	            document.getElementById('reg-full-layer'),
	        );
		}

		const formData          = this.state.formData;
		const courseRelatedData = this.state.courseRelatedData;
		const listingType       = this.state.listingType;

		if(!formData || (formData.clientCourseId && !courseRelatedData)) {
			return null;
		}
		
		if(this.props.cta == "" || !this.props.cta) {
			var cta = "default";
		} else {
			var cta = this.props.cta;
		}

		// if(this.state.onlyOTP) {
		// 	return (
		// 		<FormFieldsContent fromFeeDetails={(this.props.fromFeeDetails )? true:false} regFormId={this.state.regFormId} formData={formData} courseData={courseRelatedData} trackingKeyId={this.props.trackingKeyId} closeResponseLayer={this.closeResponseLayer} actionType={this.props.actionType} callBackFunction={this.props.callBackFunction} isLoggedInUser={this.state.isLoggedInUser} trackEventHandler={this.trackResponseEvent} formHeading={this.responseFormCTAConfig[cta]['formMainHeading']} onlyOTP={this.state.onlyOTP} cta={cta} />
		// 	);
		// }
		
		var submitButtonText    = (this.state.isLoggedInUser) ? this.responseFormCTAConfig[cta]['loggedInCTAText'] : this.responseFormCTAConfig[cta]['submitButtonText'];
		var responseFormHeading = 'To '+this.responseFormCTAConfig[cta]['formMainHeading'];
		responseFormHeading    += (this.state.isLoggedInUser) ? this.headingText['loggedIn'] : this.headingText['nonLoggedIn'];
		var courseDropdownLabel = 'Course '+this.responseFormCTAConfig[cta]['courseDDLabel'];

		const responseFormHTML  = <FormFieldsContent fromFeeDetails={(this.props.fromFeeDetails )? true:false} regFormId={this.state.regFormId} formData={formData} courseData={courseRelatedData} submitButtonText={submitButtonText} trackingKeyId={this.props.trackingKeyId} closeResponseLayer={this.closeResponseLayer} actionType={this.props.actionType} callBackFunction={this.props.callBackFunction} isLoggedInUser={this.state.isLoggedInUser} trackEventHandler={this.trackResponseEvent} formHeading={this.responseFormCTAConfig[cta]['formMainHeading']} onlyOTP={this.state.onlyOTP} updateNewUserFlag={this.updateNewUserFlag} courseDropdownLabel={courseDropdownLabel} cta={cta} />;
		
		return (
			<React.Fragment>
				<CustomFormLayer data={responseFormHTML} heading={responseFormHeading} isOpen={this.state.openResponseForm} onClose={this.closeResponseLayer} regFormId={this.state.regFormId} isResponseForm={true} />
			</React.Fragment>
		);
		
	}

	componentDidMount() {

		const props = this.props;
		if(props.viewedResponse == true && isUserLoggedIn()) {
			this.isValidCResponseUser(props, 'yes');
		}

	}

	closeResponseLayer(layerClose) {
		
		if(layerClose) {
			this.trackResponseEvent(this.props.trackingKeyId, 'P1_ClosePage_Link');
		}
		if(this.props.onClose != null && typeof this.props.onClose == 'function') {
            this.props.onClose();
        }
		this.setState({...this.state, openResponseForm: false, isNewUser: false});
		
	}

	showResponseFormCall(newProps, onlyOTP = false) {

		const clientCourseId = (typeof(this.props.clientCourseId) !='undefined') ? this.props.clientCourseId : newProps.clientCourseId;
		const listingType    = (typeof(this.props.listingType) !='undefined') ? this.props.listingType : (typeof(newProps.listingType) !='undefined' ? newProps.listingType : 'course');
		const trackingKeyId  = (typeof(this.props.trackingKeyId) !='undefined' && this.props.trackingKeyId) ? this.props.trackingKeyId : ((typeof(newProps.trackingKeyId) !='undefined' && newProps.trackingKeyId) ? newProps.trackingKeyId : 905);
		
		if(clientCourseId) {
	  		
			this.setState({...this.state, loader: true});
			var params   = 'clientCourseId='+clientCourseId+'&listingType='+listingType;
			var self     = this;
			var openForm = (typeof(newProps.openResponseForm) !='undefined') ? newProps.openResponseForm : this.props.openResponseForm;
			
			var callResp = showResponseForm(params).then((response) => {
	  			
  				self.setState({
					openResponseForm: openForm,
					regFormId: makeRandomString(6),
					formData: response,
					isLoggedInUser: isUserLoggedIn(),
					loader: false,
					onlyOTP: onlyOTP
				});
				if(openForm) {
					self.trackResponseEvent(trackingKeyId, 'P1_Load_Successful');
				}
				// if(listingType == 'course') {
				// 	var courseData = self.getFormByClientCourseCall();
				// 	return courseData;
				// }

			});
			Promise.resolve(callResp).then((resp) => {
				self.setState({
					courseRelatedData: resp,
					loader: false
				});
			});

		}	

	}

	// getFormByClientCourseCall() {

	// 	const formData = this.state.formData;

	// 	if(formData) {
			
	// 		if(!formData.instituteCourses && formData.clientCourseId > 0) {

	// 			this.setState({...this.state, loader: true});
	// 			var self         = this;
	// 			var params       = 'clientCourse='+formData.clientCourseId;
	// 			var callResponse = Promise.resolve(getFormByClientCourse(params)).then((response) => {
	// 				self.setState({...self.state, loader: false});
	// 				return response;
	// 			});
	// 			return callResponse;
				
	// 		} else {
				
	// 			return null;

	// 		}

	// 	}

	// }

	
	createResponseForValidUser(clientCourseId, actionType, trackingKeyId, listingType, isViewedResponse) {

		if(typeof(clientCourseId) == 'undefined' || clientCourseId == ''){
            return false;
        }
        if(!isViewedResponse) {
        	this.setState({...this.state, loader: true});
        }
        var self             = this;
		var responseCallBack = (this.props.callBackFunction) ? this.props.callBackFunction : null;
		var params           = 'listing_id='+clientCourseId+'&listing_type='+listingType+'&action_type='+actionType+'&tracking_keyid='+trackingKeyId+'&isViewedResponse='+isViewedResponse;
		var callResp         = createResponse(params).then((response) => {
			
			if(response.courseResponse.status == 'SUCCESS') {
			
				self.trackResponseEvent(trackingKeyId, 'Response_Creation_Successful');
				self.setState({...self.state, loader: false});
				if(typeof responseCallBack == 'function' && typeof responseCallBack != 'undefined' && responseCallBack != null) {
  					responseCallBack(response.courseResponse);
  					self.props.getUserDetails();
				}

			}

		});

	}

	trackResponseEvent(eventAction, eventLabel) {

		var eventCategory = 'Response National';
		if(isUserLoggedIn() && this.state.isNewUser == false){
            eventCategory = 'Mobile Verification'
        }
		var finalLabel    = 'PWA_';
		finalLabel += eventLabel;
		Analytics.event({category : eventCategory, action : eventAction, label : finalLabel});

	}

	updateNewUserFlag(isNewUser) {
		this.setState({...this.state, isNewUser: isNewUser});
	}

}

function mapStateToProps(state) {
	return {config : state.config}
}

function mapDispatchToProps(dispatch){
    return bindActionCreators({ getUserDetails }, dispatch); 
}

export default connect(mapStateToProps,mapDispatchToProps)(ResponseForm);