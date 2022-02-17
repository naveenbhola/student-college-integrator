import React, { Component } from 'react';
import Formsy, { addValidationRule, propTypes, withFormsy } from 'formsy-react';
import { isUserLoggedIn } from './../../../../../utils/commonHelper';
import { isEmptyStr, isProfaneReg, isBlackListedReg, validateSpecialChars, validateMinLength, validateMaxLength, validateMobileNumber, validateMobileLength, validateEmailAddress } from './../../../../../utils/commonValidations';

addValidationRule('validateProfanity', function(values, value) {
    
    if (typeof value != 'undefined' && value != null && value.length > 0) {
        
        value = value.replace(/[(\n)\r\t\"\']/g, ' ');
        value = value.replace(/[^\x20-\x7E]/g, '');
        var profaneResponse = isProfaneReg(value);
        if (profaneResponse !== false) {
            return false;
        }
        return true;

    }

});

addValidationRule('validateMandatory', function(values, value) {
    
    if(typeof value != 'undefined' && value != null) {
        if (value.length == 0) {
            return false;
        }
        return true;
    }

});

addValidationRule('validateBlacklist', function(values, value) {

    if (typeof value != 'undefined' && value != null && value.length > 0) {

        var blackListResponse = isBlackListedReg(value);
        if (blackListResponse == true) {
            return false;
        }
        return true;

    }

});

addValidationRule('validateMinLength', function(values, value, minLength) {

    if (typeof value != 'undefined' && value != null && value.length > 0) {
        return validateMinLength(value, minLength);
    }

});

addValidationRule('validateMaxLength', function(values, value, maxLength) {

    if (typeof value != 'undefined' && value != null && value.length > 0) {
        return validateMaxLength(value, maxLength);
    }

});

addValidationRule('validateSpecialChars', function(values, value) {

    if (typeof value != 'undefined' && value != null && value.length > 0) {
        return validateSpecialChars(value);
    }

});

addValidationRule('validateEmailAddress', function(values, value) {

    if (typeof value != 'undefined' && value != null && value.length > 0) {
        return validateEmailAddress(value);
    }

});

addValidationRule('validateNationalMobile', function(values, value) {

    if (typeof value != 'undefined' && value != null && value.length > 0) {
        return validateMobileNumber(value);
    }

});

addValidationRule('validateMobileLength', function(values, value, length) {

    if (typeof value != 'undefined' && value != null && value.length > 0) {
        return validateMobileLength(value, length);
    }

});

class CustomInput extends Component {

    constructor(props) {
        super(props);
        this.changeValue  = this.changeValue.bind(this);
        this.focusElement = this.focusElement.bind(this);
        this.blurElement  = this.blurElement.bind(this);
        this.className    = null;
    }
    
    render() {

        const { getValue } = this.props;
        var className      = "reg-form invalid";
        if(this.props.name == 'mobile') {
            className += " mblFld";
        }
        if(this.props.name == 'email') {
            className += " emailFld-block";
        }
        if(this.props.name == 'newMobileNumber') {
            className += " updt-mblFld";
        } else if(isUserLoggedIn() && (getValue() || this.props.type == 'password')) {
            className += " ih filled";
        }
        if(!this.className) {
            this.className = className;
        }
        
		return (

            <div className={this.className} id={this.props.baseId+"_block_"+this.props.regFormId} tabIndex={0} onBlur={this.props.onBlur}>
                <div className="ngPlaceholder">{this.props.title}</div>
                <input 
                    className="ngInput"
                    type={this.props.type || 'text'}
                    onChange={this.changeValue}
                    name={this.props.name}
                    id={this.props.baseId+"_"+this.props.regFormId}
                    value={getValue() || ''}
                    onFocus={this.focusElement}
                    onBlur={this.blurElement}
                    onKeyUp={this.props.onKeyUp}
                    onInput={this.props.onInput}
                    minLength={this.props.minLength}
                    maxLength={this.props.maxLength}
                />
                {
                    (this.props.name == 'email' || this.props.name == 'mobile') &&
                    <div tabIndex={0} onBlur={this.props.showHelpBox.bind(this,this.props.name,'hide')}>
                        <i id={this.props.name+"_help_icon"} className="d-info" onClick={this.props.showHelpBox.bind(this,this.props.name,'toggle')}></i>
                        <div id={this.props.name+"_help"} className="input-help ih">
                            <div className="up-arrow"></div>
                            <div className="help-text">{this.props.helpText}</div>
                        </div>
                    </div>
                }
                {
                    this.props.name == 'password' &&
                    <React.Fragment>
                        <span id="show_pswd" className="hideblk ih" onClick={this.props.togglePassword.bind(this,'show')}>show</span>
                        <span id="hide_pswd" className="hideblk ih" onClick={this.props.togglePassword.bind(this,'hide')}>hide</span>
                    </React.Fragment>
                }
                <div className="input-helper">
                    <div className="up-arrow"></div>
                    <div id={this.props.baseId+"_error_"+this.props.regFormId} className="helper-text"></div>
                </div>
                {
                    this.props.name == 'email' &&
                    <div className="regform-email-suggestor" id={"suggestor_"+this.props.regFormId}>
                        <ul className="regform-email-suggestor-list" id={"suggestor_list_"+this.props.regFormId}></ul>
                    </div>
                }
            </div>

        );
        
    }

    componentDidUpdate() {
        
        var inputDiv = document.getElementById(this.props.baseId+"_block_"+this.props.regFormId);
        var errorDiv = document.getElementById(this.props.baseId+"_error_"+this.props.regFormId);

        const { showError, isPristine, hasValue, isFormSubmitted, getErrorMessage } = this.props;
        const hasError     = (!isPristine() && isFormSubmitted() && (showError() || !hasValue()));
        const errorMessage = hasError ? getErrorMessage() : null;
        
		if(errorMessage && errorDiv.innerHTML != '') {
            inputDiv.classList.add('invalidFld','deError','focused');
        } else if(inputDiv.classList.contains('invalidFld')) {
            inputDiv.classList.remove('invalidFld','deError','focused');
        }

    }

    changeValue(event) {
        this.props.setValue(event.currentTarget.value, false);
    }

    focusElement(event) {
        var inputElement = document.getElementById(this.props.baseId+"_"+this.props.regFormId);
        inputElement.parentNode.classList.add('focused');
        (inputElement.value != '')?(inputElement.parentNode.classList.add('filled')):(inputElement.parentNode.classList.remove('filled'));
    }

    blurElement(event) {

        this.props.setValue(event.currentTarget.value);

        var inputElement = document.getElementById(this.props.baseId+"_"+this.props.regFormId);
        var errorDiv     = document.getElementById(this.props.baseId+"_error_"+this.props.regFormId);

        if(inputElement.parentNode.classList.contains('invalidFld') && errorDiv.innerHTML != '') {
            errorDiv.innerHTML = this.props.getErrorMessage();
        }

        inputElement.parentNode.classList.remove('focused');
        (inputElement.value != '')?(inputElement.parentNode.classList.add('filled')):(inputElement.parentNode.classList.remove('filled'));

    }

}

CustomInput.propTypes = {
    ...propTypes,
};

export default withFormsy(CustomInput);
