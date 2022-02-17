import React, { Component } from 'react';
import CustomFormLayer from './CustomFormLayer';
import Formsy from 'formsy-react';
import CustomInput from './CustomInput';

class ChangeMobileLayer extends Component {

	constructor(props) {
		super(props);
		this.state = {
			changeMobile : false
		}
		this.renderLayerHtml = this.renderLayerHtml.bind(this);
		this.updateNumber    = this.updateNumber.bind(this);
		this.notifyError     = this.notifyError.bind(this);
		this.maxLengthCheck  = this.maxLengthCheck.bind(this);
	}

	componentWillReceiveProps(nextProps) {
		
		if(nextProps.show == true) {
			this.setState({changeMobile: true});
		}
		if(nextProps.show == false) {
			this.setState({changeMobile: false});
		}

	}

	render() {

		if(!this.state.changeMobile) {
			return null;
		}
		
		const layerHTML    = this.renderLayerHtml();
		const layerHeading = 'Update Mobile Number';

		return (
			<CustomFormLayer data={layerHTML} heading={layerHeading} isOpen={this.state.changeMobile} onClose={this.closeLayer.bind(this)} regFormId={this.props.regFormId} />
			);

	}

	renderLayerHtml() {

		var countryHtml            = document.getElementById('countryInitials').innerHTML;
		var isdCodeHtml            = document.getElementById('countryCode').innerHTML;
		var minLength              = 10;
		var maxLength              = 10;
		var mobileValidations      = {validateMandatory: true, validateNationalMobile: true, validateMobileLength: 10};
		var mobileValidationErrors = {validateMandatory: 'Please enter your Mobile number', validateNationalMobile: 'The Mobile number can start with 9, 8, 7 or 6', validateMobileLength: 'The Mobile number must have 10 digits only'};

		if(isdCodeHtml != '+91') {

			minLength              = 6;
			maxLength              = 20;
			mobileValidations      = {validateMandatory: true, validateMinLength: 6, validateMaxLength: 20};
			mobileValidationErrors = {validateMandatory: 'Please enter your Mobile number', validateMinLength: 'The Mobile number must contain minimum 6 digits', validateMaxLength: 'The Mobile number can contain maximum 20 digits'};

		}

		return (
			<Formsy ref="form" onValidSubmit={this.updateNumber} onInvalidSubmit={this.notifyError}>
                <div>
					<p className="field-title">Enter your new Mobile Number:</p>
				</div>
				<div>
					<div className="signup-fld updt-isd">
						<div className="reg-form invalid isd-chng" id={"isdChangeMobile_"+this.props.regFormId}>
							<span className="moreLnk">{countryHtml}</span> <span className="text">{isdCodeHtml}</span>
						</div>
					</div>
					<CustomInput 
						name="newMobileNumber" 
						type="tel" 
						minLength={minLength} 
						maxLength={maxLength} 
						title="Mobile number" 
						validations={mobileValidations}
						validationErrors={mobileValidationErrors}
						baseId="newMobileNumber" 
						regFormId={this.props.regFormId} 
						onInput={this.maxLengthCheck.bind(this)} 
					/>
				</div>
				<div className="rst-lgn">
					<button type="submit" className="reg-btn" formNoValidate={true}>Update & send OTP</button>
				</div>
            </Formsy>
		);
	}

	updateNumber() {
		this.props.updateAndResendOTPCall(document.getElementById('newMobileNumber_'+this.props.regFormId).value);
	}

	closeLayer(layerClose) {
		
		if(layerClose) {
			this.props.trackEventHandler(this.props.trackingKeyId, 'Change_Mobile_Number_ClosePage_Link');
		}
		this.props.closeOTPLayer();
		this.props.closeResponseLayer();
		this.setState({changeMobile: false});
		
	}

	notifyError(data, resetForm, updateInputsWithError) {
	    
		let errs          = {};
		var currentInputs = this.refs.form.inputs;
		
		for(var i in currentInputs) {

			var errorDiv = document.getElementById(currentInputs[i].props.baseId+"_error_"+currentInputs[i].props.regFormId);
			
			if(currentInputs[i].showError() && Object.keys(errs).length == 0) {

	      		errs[currentInputs[i].props.name] = typeof currentInputs[i].getErrorMessage() === 'string' ? currentInputs[i].getErrorMessage() : '';
	      		errorDiv.innerHTML = errs[currentInputs[i].props.name];

	      	} else {
	      		
	      		errorDiv.innerHTML = '';
	      		
	      	}

        }
        
	}

	maxLengthCheck() {
		
		var input     = document.getElementById('newMobileNumber_'+this.props.regFormId);
		var nonNumReg = /[^0-9]/g;
		input.value   = input.value.replace(nonNumReg, '');
		if (input.value.length > input.maxLength) {
      		input.value = input.value.slice(0, input.maxLength);
    	}
    	
  	}

}

export default ChangeMobileLayer;