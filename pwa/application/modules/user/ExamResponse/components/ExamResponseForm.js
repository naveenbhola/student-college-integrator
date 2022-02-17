import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import styles from './../../../common/assets/registration-response.css';
import CustomFormLayer from './../../Common/components/CustomFormLayer';
import { makeRandomString } from './../../../../utils/stringUtility';
import ExamFormFieldsContent from './ExamFormFieldsContent';
import respFormConfig from './../../Response/config/respFormConfig';
import { showExamResponseForm, isValidExamResponse } from './../actions/ExamResponseFormAction';
import { isValidResponseUser , createResponse } from './../../Response/actions/ResponseFormAction';
import { isUserLoggedIn, getCookie } from './../../../../utils/commonHelper';
import Loader from './../../../reusable/components/Loader';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { getUserDetails } from './../../../common/actions/commonAction';
import Analytics from './../../../reusable/utils/AnalyticsTracking';

class ExamResponseForm extends Component {

	constructor(props) {
		super(props);
		this.state = {
			openResponseForm: false,
			regFormId: null,
			formData: null,
			groupRelatedData: null,
			isLoggedInUser: false,
			loader: false,
			onlyOTP: false,
			isNewUser: false
		}
		this.responseFormCTAConfig      = respFormConfig['responseFormCTAConfig'];
		this.headingText      			= respFormConfig['headingText'];
		this.closeResponseLayer         = this.closeResponseLayer.bind(this);
		this.showExamResponseFormCall   = this.showExamResponseFormCall.bind(this);
		this.isValidCResponseUser       = this.isValidCResponseUser.bind(this);
		this.createResponseForValidUser = this.createResponseForValidUser.bind(this);
		this.trackResponseEvent         = this.trackResponseEvent.bind(this);
		this.updateNewUserFlag          = this.updateNewUserFlag.bind(this);
		
	}

	componentWillReceiveProps(nextProps) {
		
		if(nextProps.openResponseForm == true) {

			if(isUserLoggedIn()) {
				this.isValidCResponseUser(nextProps);
			} else {
				this.showExamResponseFormCall(nextProps);
			}

		} else if(nextProps.viewedResponse == true) {

			if(isUserLoggedIn()) {
				this.isValidCResponseUser(nextProps, 'yes');
			}

		} else if(nextProps.openResponseForm == false) {

			if(this.state.openResponseForm){
				this.closeResponseLayer();
			}

		}

	}

	render() {
		
		if(this.state.loader) {
			return ReactDOM.createPortal(
	            <Loader show={this.state.loader} />,
	            document.getElementById('reg-full-layer'),
	        );
		}

		const formData         = this.state.formData;
		const groupRelatedData = this.state.groupRelatedData;

		if(this.props.viewedResponse == true) {
			return null;
		}

		if(!formData || (formData.examGroupId && !groupRelatedData)) {
			return null;
		}
		
		if(this.props.cta == "" || !this.props.cta) {
			var cta = "default";
		} else {
			var cta = this.props.cta;
		}

		if(this.state.onlyOTP) {
			/*console.log(this.state.onlyOTP);
			return (
				<ExamFormFieldsContent regFormId={this.state.regFormId} formData={formData} courseData={groupRelatedData} trackingKeyId={this.props.trackingKeyId} closeResponseLayer={this.closeResponseLayer} actionType={this.props.actionType} callBackFunction={this.props.callBackFunction} isLoggedInUser={this.state.isLoggedInUser} trackEventHandler={this.trackResponseEvent} formHeading={this.responseFormCTAConfig[cta]['formMainHeading']} onlyOTP={this.state.onlyOTP} />
			);*/
		}
		
		var submitButtonText    = (this.state.isLoggedInUser) ? this.responseFormCTAConfig[cta]['loggedInCTAText'] : this.responseFormCTAConfig[cta]['submitButtonText'];
		var responseFormHeading = 'To '+this.responseFormCTAConfig[cta]['formMainHeading'];
		responseFormHeading    += (this.state.isLoggedInUser) ? this.headingText['loggedIn'] : this.headingText['nonLoggedIn'];
		const responseFormHTML  = <ExamFormFieldsContent clickId={this.props.clickId} regFormId={this.state.regFormId} formData={formData} examGroupData={groupRelatedData} submitButtonText={submitButtonText} trackingKeyId={this.props.trackingKeyId} closeResponseLayer={this.closeResponseLayer} actionType={this.props.actionType} callBackFunction={this.props.callBackFunction} isLoggedInUser={this.state.isLoggedInUser} trackEventHandler={this.trackResponseEvent} formHeading={this.responseFormCTAConfig[cta]['formMainHeading']} onlyOTP={this.state.onlyOTP} updateNewUserFlag={this.updateNewUserFlag} examName={this.props.examName} />;

		/*console.log(responseFormHTML);*/
		
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

	showExamResponseFormCall(newProps, onlyOTP = false) {
		// onlyOTP = false;

		const examGroupId   = (typeof(this.props.examGroupId) !='undefined') ? this.props.examGroupId : newProps.examGroupId;
		const trackingKeyId = (typeof(this.props.trackingKeyId) !='undefined' && this.props.trackingKeyId) ? this.props.trackingKeyId : ((typeof(newProps.trackingKeyId) !='undefined' && newProps.trackingKeyId) ? newProps.trackingKeyId : 1287);
		
	  	if(examGroupId) {

			this.setState({...this.state, loader: true});
			var params   = 'examGroupId='+examGroupId;
			var self     = this;
			var openForm = (typeof(newProps.openResponseForm) !='undefined') ? newProps.openResponseForm : this.props.openResponseForm;
			
			showExamResponseForm(params).then((response) => {
	  			if(openForm) {
					self.trackResponseEvent(trackingKeyId, 'P1_Load_Successful');
				}
  				self.setState({
					openResponseForm: openForm,
					regFormId: makeRandomString(6),
					formData: response,
					groupRelatedData: response.examGroupData,
					isLoggedInUser: isUserLoggedIn(),
					loader: false,
					onlyOTP: onlyOTP
				});
				
			});
			
		}	

	}

	isValidCResponseUser(newProps, isViewedCall) {

		if(typeof(newProps.examGroupId) == 'undefined'){
            return false;
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

		var params        = 'examGroupId='+newProps.examGroupId+'&isViewedCall='+isViewedCall+'&isPWACall=true';
		var self          = this;
		var trackingKeyId = newProps.trackingKeyId;
		var callResp      = isValidExamResponse(params).then((response) => {
	  		
            self.setState({...self.state, loader: false});

            if(typeof(response) !='undefined' && response == true) {
            	self.createResponseForValidUser(newProps.examGroupId, newProps.actionType, trackingKeyId, isViewedCall, newProps.examName);

            } else if(isViewedCall == 'no' && typeof(response) !='undefined') {

            	if(response == 'mobile_not_verified') {
            		self.showExamResponseFormCall(newProps, true);
            	} else {
            		self.showExamResponseFormCall(newProps);
            	}

            }

		});

	}
 
	createResponseForValidUser(examGroupId, actionType, trackingKeyId, isViewedResponse, examName) {

		if(typeof(examGroupId) == 'undefined' || examGroupId == ''){
            return false;
        }
        if(!isViewedResponse) {
        	this.setState({...this.state, loader: true});
        }

        var eName = '';
        if(typeof examName != 'undefined' && examName){
        	eName = examName;
        }

        var self             = this;
		var responseCallBack = (this.props.callBackFunction) ? this.props.callBackFunction : null;
		var params           = 'listing_id='+examGroupId+'&listing_type=exam'+'&action_type='+actionType+'&tracking_keyid='+trackingKeyId+'&isViewedResponse='+isViewedResponse+'&examName='+eName;
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

export default connect(mapStateToProps,mapDispatchToProps)(ExamResponseForm);
