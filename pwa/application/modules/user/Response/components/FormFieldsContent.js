import React, { Component } from 'react';
import CourseMappedFields from './CourseMappedFields';
import PrefYear from './PrefYear';
import ResidenceCityLocality from './ResidenceCityLocality';
import ClientCourse from './ClientCourse';
import PersonalAccountDetails from './PersonalAccountDetails';
import TermsConditions from './TermsConditions';
import OTPLayer from './../../Common/components/OTPLayer';
import ChangeMobileLayer from './../../Common/components/ChangeMobileLayer';
import { checkForExistingUser , verifyUserForOTP , verifyOTPCall , registerNewUser , updateExistingUser , trackFieldData } from './../../Common/actions/FormActions';
import { createResponse , storeResponseData } from './../actions/ResponseFormAction';
import { setCookie } from './../../../../utils/commonHelper';
import Formsy from 'formsy-react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { getUserDetails } from './../../../common/actions/commonAction';
import PreventScrolling from './../../../reusable/utils/PreventScrolling';
import TagManager from './../../../reusable/utils/loadGTM';
import Analytics from './../../../reusable/utils/AnalyticsTracking';
import shikshaConfig from './../../../common/config/shikshaConfig';

class FormFieldsContent extends Component {

	constructor(props) {
		super(props);
		this.state = {
			showOTPLayer : false,
			changeMobile : false,
			clientCourseId : null,
			allFormInputs: [
				'clientCourse',
				'prefCity',
				'prefLocality',
				'workExperience',
				'prefYear',
				'email',
				'password',
				'firstName',
				'lastName',
				'isdCode',
				'mobile',
				'residenceCityLocality',
				'residenceLocality',
				'agreeTerms',
				'serviceTerms'
			],
			formValues: {
				clientCourse : null,
				prefCity : null,
				prefLocality : null,
				experience : null,
				email : null,
				password : null,
				firstName : null,
				lastName : null,
				isdCode : null,
				mobile : null,
				residenceCityLocality : null,
				residenceLocality : null,
				prefYear : null
			}
		}
		this.form                     = null;
		this.email                    = null;
		this.mobile                   = null;
		this.isdCode                  = '91-2';
		this.serializedFormData       = null;
		this.callBackParams           = {};
		this.clickSubmitHandler       = this.clickSubmitHandler.bind(this);
		this.sendOTPLayerCall         = this.sendOTPLayerCall.bind(this);
		this.submitForm               = this.submitForm.bind(this);
		this.getDataForRegistration   = this.getDataForRegistration.bind(this);
		this.serializeForm            = this.serializeForm.bind(this);
		this.createResponseCall       = this.createResponseCall.bind(this);
		this.showChangeMobileLayer    = this.showChangeMobileLayer.bind(this);
		this.closeOTPLayer            = this.closeOTPLayer.bind(this);
		this.resendOTP                = this.resendOTP.bind(this);
		this.updateAndResendOTP       = this.updateAndResendOTP.bind(this);
		this.authenticateOTP          = this.authenticateOTP.bind(this);
		this.showExistingEmailMessage = this.showExistingEmailMessage.bind(this);
		this.notifyFirstError		  = this.notifyFirstError.bind(this);
		this.showLoader               = this.showLoader.bind(this);
		this.hideLoader               = this.hideLoader.bind(this);
		this.loginClick               = this.loginClick.bind(this);
		this.checkForExistingUserCall = this.checkForExistingUserCall.bind(this);
		this.storeTempResponseData    = this.storeTempResponseData.bind(this);

	}

	render() {

		const { onlyOTP , formData , courseData } = this.props;
		if(this.props.isLoggedInUser) {
			var userEmail   = formData.userDetails.email ? formData.userDetails.email : this.email;
			var userMobile  = this.mobile ? this.mobile : formData.userDetails.mobile; // because it can be changed
			var userIsdCode = formData.userDetails.isdCode ? formData.userDetails.isdCode : this.isdCode;
			if(onlyOTP) {
				var isdCodeMap = formData.isdCodeValues.isdCodeMap;
				var isdCode = userIsdCode.split('-');
			}
		}
		const showPrefYearField = !formData.prefYearHidden && !this.state.formValues.prefYear;

		// to handle cta action after login
		var action = this.props.cta;
		var fromFeeDetails = (this.props.fromFeeDetails)? 'true':null;
		if(this.props.actionType == 'compare') { action = "compareRightPanel"; }
		if(this.props.actionType == 'NM_courseDownloadPlacement') { action = "placement"; }
		if(this.props.actionType == 'NM_courseDownloadInternship') { action = "intern"; }
		console.log(action);
		return (
			<React.Fragment>
			{ 
				!onlyOTP &&
				<Formsy 
					ref="form"
					onValidSubmit={this.clickSubmitHandler} 
					onInvalidSubmit={this.notifyFirstError} 
					id={"responseForm_"+this.props.regFormId} 
					className="screenHolder" 
				>
					<div className="scrolable-vport form-scroll" id={"sndscreenSroll_"+this.props.regFormId}>
						<p className="field-title">We will also send you insights, recommendations and updates.</p>
						{
							formData.instituteCourses &&
							<ClientCourse instituteCourses={formData.instituteCourses} courseDropdownLabel={this.props.courseDropdownLabel} regFormId={this.props.regFormId} formValues={this.state.formValues} isLoggedInUser={this.props.isLoggedInUser} clientCourse={this.getClientCourse.bind(this)} />
						}
				        {
				        	courseData && 
				        	<React.Fragment>
					        	<input type="hidden" name="clientCourse" value={formData.clientCourseId} id={"clientCourse_"+this.props.regFormId} />
					        	<CourseMappedFields clientCourseId={formData.clientCourseId} courseData={courseData} formValues={this.state.formValues} regFormId={this.props.regFormId} isLoggedInUser={this.props.isLoggedInUser} />
					        </React.Fragment>
				        }
						{
			        		showPrefYearField &&
			        		<PrefYear prefYearValues={formData.prefYearValues} formValues={this.state.formValues} prefYearMandatory={formData.prefYearMandatory} regFormId={this.props.regFormId} />
			        	}
						<PersonalAccountDetails isdCodeValues={formData.isdCodeValues} formValues={this.state.formValues} regFormId={this.props.regFormId} isLoggedInUser={this.props.isLoggedInUser} />
						<ResidenceCityLocality cityList={formData.cityList} baseCourse={courseData ? courseData.baseCourse : null} formValues={this.state.formValues} regFormId={this.props.regFormId} isLoggedInUser={this.props.isLoggedInUser} />
						<input type="hidden" id="regFormId" name="regFormId" value={this.props.regFormId} />
						<input type="hidden" name="listing_type" value="course" id={"listing_type_"+this.props.regFormId} />
						<input type="hidden" id="isResponseForm" name="isResponseForm" value="yes" />
						<input type="hidden" id={"context_"+this.props.regFormId} name="context" value="nationalResponseMobile" />
						<input type="hidden" id="registrationSource" name="registrationSource" value="PWA_COURE_RESPONSE" />
						<input type="hidden" id={"tracking_keyid_"+this.props.regFormId} name="tracking_keyid" value={this.props.trackingKeyId} />
						<input type="hidden" name="action_type" value={this.props.actionType} id={"response_action_"+this.props.regFormId} />
						<input type='hidden' id='isMR' name='isMR' value='YES' />
					{
						!this.props.isLoggedInUser &&
						<TermsConditions regFormId={this.props.regFormId} countryName={formData.countryName}/>
					}
					</div>
					<div className="shadow-vport" id={"bottomDiv_"+this.props.regFormId}>
						<div className="shadow-lyr"></div>
						<button className="reg-btn stp2" type="submit" formNoValidate={true}>
							{this.props.submitButtonText}
				        </button>
				    { 
						!this.props.isLoggedInUser && 
						<div className="btn-rltv clear">
				            <div className="Sgup-box">
				                <a className="newSks lgn2 loginScreen" onClick={this.loginClick} href={this.props.config.SHIKSHA_HOME+'/muser5/UserActivityAMP/getLoginAmpPage?fromwhere=pwaResponseLogin&listingType='+formData.listingType+'&listingId='+formData.clientCourseId+'&action='+action+'&fromFeeDetails='+fromFeeDetails}>Already have an account? Login here</a>
				            </div>
				        </div>
				    }
				    </div>
				</Formsy>
			}
			{
				onlyOTP &&
				<div>
					<input type="hidden" name="clientCourse" value={formData.clientCourseId} id={"clientCourse_"+this.props.regFormId} />
					<input type="hidden" id={"email_"+this.props.regFormId} name="email" value={userEmail} />
					<input type="hidden" id={"mobile_"+this.props.regFormId} name="mobile" value={userMobile} />
					<input type="hidden" id={"isdCode_"+this.props.regFormId} name="isdCode" value={userIsdCode} />
					<input type="hidden" id="regFormId" name="regFormId" value={this.props.regFormId} />
					<input type="hidden" name="listing_type" value="course" id={"listing_type_"+this.props.regFormId} />
					<input type="hidden" id="isResponseForm" name="isResponseForm" value="yes" />
					<input type="hidden" id={"context_"+this.props.regFormId} name="context" value="nationalResponseMobile" />
					<input type="hidden" id="registrationSource" name="registrationSource" value="PWA_COURE_RESPONSE" />
					<input type="hidden" id={"tracking_keyid_"+this.props.regFormId} name="tracking_keyid" value={this.props.trackingKeyId} />
					<input type="hidden" name="action_type" value={this.props.actionType} id={"response_action_"+this.props.regFormId} />
					<input type='hidden' id='isMR' name='isMR' value='YES' />
					<div className="multiinput ih" id={"isdCode_input_"+this.props.regFormId}>
						<span id="countryInitials" className="moreLnk">{isdCodeMap[this.isdCode]}</span> <span id="countryCode" className="text">{"+"+isdCode[0]}</span>
					</div>
				</div>
			}
				<OTPLayer show={this.state.showOTPLayer} regFormId={this.props.regFormId} closeResponseLayer={this.props.closeResponseLayer} mobile={this.mobile} isdCode={this.isdCode} resendOTPCall={this.resendOTP} changeMobileCall={this.showChangeMobileLayer} authenticateOTP={this.authenticateOTP} trackEventHandler={this.props.trackEventHandler} trackingKeyId={this.props.trackingKeyId} isLoggedInUser={this.props.isLoggedInUser} formHeading={this.props.formHeading} closeOTPLayer={this.closeOTPLayer} />
				<ChangeMobileLayer show={this.state.changeMobile} regFormId={this.props.regFormId} closeResponseLayer={this.props.closeResponseLayer} closeOTPLayer={this.closeOTPLayer} updateAndResendOTPCall={this.updateAndResendOTP} trackEventHandler={this.props.trackEventHandler} trackingKeyId={this.props.trackingKeyId} isLoggedInUser={this.props.isLoggedInUser} />
			</React.Fragment>
			);

	}

	componentWillMount() {

		if(this.props.isLoggedInUser && this.props.formData.userDetails) {
			let formValues = Object.assign({},this.state.formValues,this.props.formData.userDetails);
	  		this.setState({
	        	...this.state,
	        	formValues: formValues
	        });
		}

	}

	componentDidMount() {
		if(this.props.onlyOTP) {
			this.storeTempResponseData();
			this.sendOTPLayerCall();
		}
	}

	showLoader() {

		var loaders = document.getElementsByClassName('loader-col-msearch');
		Array.from(loaders).forEach(loader => {
			if(loader.classList.contains('ih')) {
				loader.classList.remove('ih');
			}
		});

	}

	hideLoader() {

		var loaders = document.getElementsByClassName('loader-col-msearch');
		Array.from(loaders).forEach(loader => {
			if(!loader.classList.contains('ih')) {
				loader.classList.add('ih');
			}
		});

	}

	openNewWindow(link) {
		
		var url = "";
		switch(link) {
			case "tnc":
				url = "https://www.shiksha.com/shikshaHelp/ShikshaHelp/termCondition";
				break;

			case "privacyP":
				url = "https://www.shiksha.com/shikshaHelp/ShikshaHelp/privacyPolicy";
				break;
		}
		if(url != '') {
			var newwindow = window.open(url);
        	if (window.focus) {
	        	newwindow.focus();
        	}
        }
        return false;

	}

	clickSubmitHandler(model, resetForm, invalidateForm) {
		
		this.props.trackEventHandler(this.props.trackingKeyId, 'P1_SignUp_Button_Click_Successful');
		if(!this.props.isLoggedInUser) {
			this.checkForExistingUserCall();
		} else {
			this.submitForm();
		}

	}

	notifyFirstError(data, resetForm, updateInputsWithError) {
	    
	    let errs          = {};
		var allFormInputs = this.state.allFormInputs;
		var currentInputs = this.refs.form.inputs;
	    
	    for (var index in allFormInputs){

	    	for(var i in currentInputs) {

				var errorDiv = document.getElementById(currentInputs[i].props.baseId+"_error_"+currentInputs[i].props.regFormId);
				
				if(currentInputs[i].props.name == allFormInputs[index]) {

					if(currentInputs[i].showError() && Object.keys(errs).length == 0) {

						// code to track invalid email domain data
						if(currentInputs[i].props.name == 'email') {

							var emailValue  = document.getElementById("email_"+currentInputs[i].props.regFormId).value;
							var domainIndex = emailValue.indexOf('@')+1;
					        var domain      = emailValue.substring(domainIndex, emailValue.length);
					        if(shikshaConfig['invalidEmailDomains'].indexOf(domain.toLowerCase()) != -1) {
					            
								var regFormId = currentInputs[i].props.regFormId;
								var params    = 'fieldName=email&fieldValue='+emailValue+'&regFormId='+regFormId;
								trackFieldData(params);
					            
					        }

						}
						errs[currentInputs[i].props.name] = typeof currentInputs[i].getErrorMessage() === 'string' ? currentInputs[i].getErrorMessage() : '';
						errorDiv.innerHTML                = errs[currentInputs[i].props.name];
						var input                         = document.getElementById(currentInputs[i].props.baseId+'_'+this.props.regFormId);
						var inputBlock                    = document.getElementById(currentInputs[i].props.baseId+'_block_'+this.props.regFormId);
			      		inputBlock.scrollIntoView({ behavior: 'smooth' });
			      		// input.focus(); // commenting so that keyboard doesn't open itself

			      	} else {

			      		errorDiv.innerHTML = '';

			      	}

	            }

	        }

		}

	}

	checkForExistingUserCall() {

		var regFormId     = this.props.regFormId;
		this.email        = document.getElementById('email_'+regFormId).value;
		var params        = 'email='+this.email;
		var self          = this;
		var callResp = checkForExistingUser(params).then((response) => {

			var isExistingUser = response.isExistingUser;
			if(isExistingUser == 'true') {
				self.showExistingEmailMessage();
				return;
			} else {
				self.submitForm();
				return;
			}

		});

	}

	sendOTPLayerCall(isResend, isChangeNumber) {

		this.showLoader();
		isResend          = typeof(isResend) !== 'undefined' ? isResend : 0;
		isChangeNumber    = typeof(isChangeNumber) !== 'undefined' ? isChangeNumber : 0;
		var regFormId     = this.props.regFormId;
		var trackingKeyId = this.props.trackingKeyId;
		this.email        = document.getElementById('email_'+regFormId).value;
		this.mobile       = this.mobile ? this.mobile : document.getElementById('mobile_'+regFormId).value;
		this.isdCode      = document.getElementById('isdCode_'+regFormId).value;
		var verification  = 'OTP';

		var isResendForOTPTracking = isResend;
        if(isChangeNumber == 1){
            isResendForOTPTracking = 0;
        }

		var params   = 'regFormId='+regFormId+'&trackingKeyId='+trackingKeyId+'&email='+this.email+'&mobile='+this.mobile+'&isdCode='+this.isdCode+'&isResend='+isResendForOTPTracking+'&isChangeNumber='+isChangeNumber+'&verification='+verification;
		var self     = this;
		
		var callResp = verifyUserForOTP(params).then((response) => {

			var otpResponse = response.otpResponse;
			self.hideLoader();

			switch(otpResponse) {

				case 'skip' :
					self.createResponseCall();
					return;
					break;

				case 'failed' :
					self.props.trackEventHandler(self.props.trackingKeyId, 'P2_Verify_OTP_Button_Click_Max5Attempt_Exceeded');
					alert("You've exceeded maximum 5 attempts for using OTP sent on SMS to verify your mobile. Please try again after 1 hour.");
					window.location.reload();
					return;
					break;

				case 'yes' :
				case 'no' :
					self.props.trackEventHandler(self.props.trackingKeyId, 'After_P1_OTP_Call_Successful');
					self.setState({showOTPLayer: true}, function(){
						self.props.trackEventHandler(self.props.trackingKeyId, 'P2_OTP_PageLoad_Successful');
					});
					if (otpResponse == 'no' && !isResend) {
		                document.getElementById('otp_error_'+self.props.regFormId).innerHTML = "PIN could not be sent";
		            }
		            if (isResend) {
		                var OTPErrorMsg = 'OTP Sent';
		                if (otpResponse == 'no') {
		                    OTPErrorMsg = 'OTP could not be sent';
		                    setTimeout(function() {
			                    document.getElementById('otp_error_'+self.props.regFormId).innerHTML = OTPErrorMsg;
								document.getElementById('otp_error_'+self.props.regFormId).focus();
								var otpBlock = document.getElementById('otp_block_'+self.props.regFormId);
								otpBlock.classList.add('invalidFld','focused');
							}, 500);
		                } else {
		                	document.getElementById('otp_block_'+self.props.regFormId).classList.remove('invalidFld','focused');
		                	setTimeout(function() {
		                    	document.getElementById('otp-msg').innerHTML = OTPErrorMsg;
		                    	document.getElementById('sendOTP_'+self.props.regFormId).classList.add('invalidFld','focused');
                    		}, 500);
                    		setTimeout(function() {
		                        document.getElementById('sendOTP_'+self.props.regFormId).classList.remove('invalidFld','focused');
		                    }, 2000);
		                	document.getElementById('otp-msg').parentNode.classList.add('otp-resp-yes');
		                }
		            }
					break;

			}

		});

	}

	submitForm() {

		var data                            = this.getDataForRegistration();
		var self                            = this;
		var params                          = {'isRegistrationCall':'yes'};
		var gtmParams                       = {};
		gtmParams['registration-datalayer'] = 'registration-success';
		gtmParams['event']                  = 'reg-btn';
		this.showLoader();

		if(!this.props.isLoggedInUser) {

			var updateNewUserFlag = this.props.updateNewUserFlag;
			if(typeof updateNewUserFlag == 'function' && typeof updateNewUserFlag != 'undefined' && updateNewUserFlag != null) {
				updateNewUserFlag(true);
			}
			var callResp = registerNewUser(data).then((response) => {
				
				self.hideLoader();
				if (response.registerResponse.status == 'USER_ALREADY_EXISTS') {
		            self.showExistingEmailMessage();
					return;
		        }

		        if (response.registerResponse.status == 'FAIL') {
		        	self.props.trackEventHandler(self.props.trackingKeyId, 'Registration_Fail');
		            alert('Something went wrong...'); 
		            window.location.reload();
		            return;
		        }

		        self.props.trackEventHandler(self.props.trackingKeyId, 'UserRegistration_Successful');
		        TagManager.dataLayer({dataLayer : gtmParams, dataLayerName : 'dataLayer'});
		        self.callBackParams = response.registerResponse;
		        self.sendOTPLayerCall();
				
			});

		} else {

			var callResp = updateExistingUser(data).then((response) => {
				
				self.hideLoader();
				if (response.updateUserResponse.status == 'USER_ALREADY_EXISTS') {
		            self.showExistingEmailMessage();
					return;
		        }

		        if (response.updateUserResponse.status == 'FAIL') {
		        	self.props.trackEventHandler(self.props.trackingKeyId, 'Registration_Fail');
		            alert('Something went wrong...'); 
		            window.location.reload();
		            return;
		        }

		        TagManager.dataLayer({dataLayer : gtmParams, dataLayerName : 'dataLayer'});
				self.callBackParams = response.updateUserResponse;
				self.sendOTPLayerCall();
				
			});

		}
			
	}

    createResponseCall() {
		
		this.showLoader();
		var self          = this;
		var listingId     = document.getElementById('clientCourse_'+this.props.regFormId).value;
		var listingType   = document.getElementById('listing_type_'+this.props.regFormId).value;
		var actionType    = document.getElementById('response_action_'+this.props.regFormId).value;
		var trackingKeyId = document.getElementById('tracking_keyid_'+this.props.regFormId).value;
		var data          = this.serializedFormData ? this.serializedFormData : 'listing_type='+listingType+'&action_type='+actionType+'&tracking_keyid='+trackingKeyId+'&isViewedResponse=no';
		
		var baseCourseArray = listingId.split("_");
		if(baseCourseArray[0]=='bc' || baseCourseArray[0]=='cc'){
			listingId = this.state.clientCourseId;
		}

		data              +='&listing_id='+listingId;
		var callResp      = createResponse(data).then((response) => {
			
			if(response.courseResponse.status == 'SUCCESS') {

				self.props.trackEventHandler(self.props.trackingKeyId, 'Response_Creation_Successful');
				self.hideLoader();
				self.closeOTPLayer(false);
				self.props.closeResponseLayer();
				var responseCallBack = self.props.callBackFunction;
				if(typeof responseCallBack == 'function' && typeof responseCallBack != 'undefined' && responseCallBack != null) {
	  				responseCallBack(response.courseResponse);
	  				self.props.getUserDetails();
				} else {
					window.location.reload();
				}
				setCookie('hpTab', '', -1 ,'seconds');
				
			}
			PreventScrolling.enableScrolling(true);
			
		});

    }

    closeOTPLayer(reload = true) {
		
    	this.setState({showOTPLayer : false});
    	if(reload) {
			window.location.reload();
    	}
		
	}

	resendOTP() {
		this.sendOTPLayerCall(1, 0);
	}

    showChangeMobileLayer() {

    	this.showLoader();
    	var self = this;
    	this.setState({changeMobile: true}, function() {
    		var isdValue = document.getElementById('isdCode_'+self.props.regFormId).value;
	        if (isdValue != '91-2') {
	        	document.getElementById('newMobileNumber_'+self.props.regFormId).minLength = 6;
  				document.getElementById('newMobileNumber_'+self.props.regFormId).maxLength = 20;
	        }
	        self.hideLoader();
    	});

    }

    updateAndResendOTP(newMobileNumber) {

    	this.props.trackEventHandler(this.props.trackingKeyId, 'UpdateMobileNumber_Button_Click_Successful');
    	
    	document.getElementById('mobile_'+this.props.regFormId).value = newMobileNumber;
        this.mobile  = newMobileNumber;
        var isdValue = document.getElementById('isdCode_'+this.props.regFormId).value;
		isdValue     = isdValue.split("-");
		isdValue     = isdValue[0];

        document.getElementById('otp-mbl').innerHTML = "+"+isdValue+"-"+newMobileNumber;
        document.getElementById('one-time-pass_'+this.props.regFormId).parentNode.classList.remove('invalidFld','focused');
        document.getElementById('one-time-pass_'+this.props.regFormId).value = '';
        this.sendOTPLayerCall(1,1);
        this.setState({changeMobile: false});

    }

    authenticateOTP() {

		this.email   = document.getElementById('email_'+this.props.regFormId).value;
		this.mobile  = this.mobile ? this.mobile : document.getElementById('mobile_'+this.props.regFormId).value;
		this.isdCode = document.getElementById('isdCode_'+this.props.regFormId).value;
		var OTP 	 = document.getElementById('one-time-pass_'+this.props.regFormId).value;

		if (isNaN(OTP) || OTP < 1000 || OTP > 9999) {
            
            document.getElementById('otp_error_'+this.props.regFormId).innerHTML = 'Enter a vaild OTP';
            document.getElementById('otp_error_'+this.props.regFormId).focus();
            var otpBlock = document.getElementById('otp_block_'+this.props.regFormId);
			otpBlock.classList.add('invalidFld','focused');
            
        } else {

			var params = 'email='+this.email+'&mobile='+this.mobile+'&isdCode='+this.isdCode+'&OTP='+OTP+'&isStudyAbroad='+0;
			var self   = this;
			this.showLoader(); 

			var callResp = verifyOTPCall(params).then((response) => {

				var isVerified = response.isVerified;
				self.hideLoader();

				switch(isVerified) {

					case 'yes' :
						self.props.trackEventHandler(self.props.trackingKeyId, 'P2_OTP_Call_Response_Positive');
						self.createResponseCall();
						return;
						break;

					case 'failed' :
						self.props.trackEventHandler(self.props.trackingKeyId, 'P2_Verify_OTP_Button_Click_Max5Attempt_Exceeded');
						alert("You've exceeded maximum 5 attempts for using OTP sent on SMS to verify your mobile. Please try again after 1 hour.");
						window.location.reload();
						return;
						break;

					case 'no' :
						self.props.trackEventHandler(self.props.trackingKeyId, 'P2_OTP_Call_Response_Negative');
						document.getElementById('otp_error_'+self.props.regFormId).innerHTML = 'The OTP is incorrect';
			            document.getElementById('otp_error_'+self.props.regFormId).focus();
			            var otpBlock = document.getElementById('otp_block_'+self.props.regFormId);
						otpBlock.classList.add('invalidFld','focused');
						return;
						break;

				}

			});

        }

    }

    showExistingEmailMessage() {

    	document.getElementById('email_error_'+this.props.regFormId).innerHTML = 'This Email ID is already registered. Please click login from the bottom to continue';
        document.getElementById('email_'+this.props.regFormId).focus();
        document.getElementById('email_block_'+this.props.regFormId).classList.add('invalidFld','focused');

    }

    getDataForRegistration() {

		this.form = document.getElementById("responseForm_"+this.props.regFormId);
		
		if(this.form != undefined) {
        	var data = this.serializeForm(this.form);
        }
        
        var parentSubstreamId    = '';
        var parentSubStreamValue = '';
        var subStream            = [];
        var subStreamSpecMap     = {};
        var specElements		 = document.getElementsByName('specializations[]');
        var subStreamElements	 = document.getElementsByName('subStream[]');

        for (var i = 0; i < specElements.length; i++) {
            
			parentSubstreamId    = specElements[i].getAttribute('parentid');
			
            if (typeof(parentSubstreamId) == 'undefined' || parentSubstreamId == null) {
                parentSubStreamValue = 'ungrouped';
            } else {
                parentSubStreamValue = document.getElementById(parentSubstreamId).value;
            }

            if (typeof(subStreamSpecMap[parentSubStreamValue]) == 'undefined') {
                subStreamSpecMap[parentSubStreamValue] = [];
            }

            subStreamSpecMap[parentSubStreamValue].push(specElements[i].value);

            if (!(subStream.indexOf(parentSubStreamValue) > -1)) {
                subStream.push(parentSubStreamValue);
            }

        }

        for (var j = 0; j < subStreamElements.length; j++) {
            
            if(typeof(subStreamSpecMap[subStreamElements[j].value]) == 'undefined') {
                subStreamSpecMap[subStreamElements[j].value] = [];
            }

            // to avoid repeated values of substream in post data
            if(subStream.indexOf(subStreamElements[j].value) > -1) {
            	delete subStream[subStream.indexOf(subStreamElements[j].value)];
            }

        }

        var extraParams = '';
        for (var index in subStream) {
            if (subStream[index] != 'undefined' && typeof(subStream[index]) != 'undefined') {
                extraParams += '&subStream[]=' + subStream[index];
            }
        }
        data += extraParams + '&subStreamSpecMapping=' + JSON.stringify(subStreamSpecMap);
        this.serializedFormData = data;
        return data;

	}

	serializeForm(form) {

        if (!form || form.nodeName !== "FORM") {
            return;
        }
        var i, j, q = [];

        for (i = 0; i < form.elements.length; i++) {
            if (form.elements[i].name == "") {
                continue;
            }

            switch (form.elements[i].nodeName) {
                case 'INPUT':
                    switch (form.elements[i].type) {
                        case 'text':
                        case 'hidden':
                        case 'password':
                        case 'button':
                        case 'submit':
                        case 'number':
                        case 'tel':
                            q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].value));
                            break;
                        case 'checkbox':
                            if (form.elements[i].checked) {
                                q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].value));
                            }
                            break;
                    }
                    break;
                case 'SELECT':
                    switch (form.elements[i].type) {
                        case 'select-one':
                        	if(form.elements[i].name=='clientCourse' || form.elements[i].name=='listing_id'){
                        		var baseCourseArray = form.elements[i].value.split("_");
                        		if(baseCourseArray[0]=='bc' || baseCourseArray[0]=='cc'){
                        			q.push(form.elements[i].name + "=" + encodeURIComponent(this.state.clientCourseId));
                        		}
                        		else{
                        			q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].value));
                        		}
                        	}
                        	else{
                            	q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].value));
                        	}
                            break;
                        case 'select-multiple':
                            for (j = form.elements[i].options.length - 1; j >= 0; j = j - 1) {
                                if (form.elements[i].options[j].selected) {
                                    q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].options[j].value));
                                }
                            }
                            break;
                    }
                    break;
                case 'BUTTON':
                    switch (form.elements[i].type) {
                        case 'reset':
                        case 'submit':
                        case 'button':
                            q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].value));
                            break;
                    }
                    break;
            }
        }
        return q.join("&");
    }

    loginClick() {
    	this.props.trackEventHandler(this.props.trackingKeyId, 'P1_Login_Link_Click');
    }

    storeTempResponseData() {

		var self          = this;
		var regFormId     = this.props.regFormId;
		var clientCourse  = document.getElementById('clientCourse_'+regFormId).value;;
		var listingType   = document.getElementById('listing_type_'+regFormId).value;
		var actionType    = document.getElementById('response_action_'+regFormId).value;
		var trackingKeyId = document.getElementById('tracking_keyid_'+regFormId).value;
		var params        = 'clientCourse='+clientCourse+'&listing_type='+listingType+'&action_type='+actionType+'&tracking_keyid='+trackingKeyId+'&regFormId='+regFormId;
		storeResponseData(params);

    }

    getClientCourse(clientCourse){
    	this.setState({clientCourseId : clientCourse});
    }

}

function mapStateToProps(state) {
    return {
        config : state.config
    }
}

function mapDispatchToProps(dispatch){
    return bindActionCreators({ getUserDetails }, dispatch); 
}

export default connect(mapStateToProps,mapDispatchToProps)(FormFieldsContent);
