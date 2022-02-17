import React, { Component } from 'react';
import Formsy, { addValidationRule, propTypes, withFormsy } from 'formsy-react';
import { isUserLoggedIn } from './../../../../utils/commonHelper';

addValidationRule('validateSelectMandatory', function(values, value, params) {
    
    if(['residenceCityLocality', 'residenceLocality'].indexOf(params.inputName) >= 0) {
        if(typeof document.getElementById('isdCode_'+params.regFormId) != 'undefined' && document.getElementById('isdCode_'+params.regFormId) != null) {
            var isdCodeVal = document.getElementById('isdCode_'+params.regFormId).value;
            if(isdCodeVal != '91-2') {
                return true;
            }
        }
    }

    if(typeof value != 'undefined' && value != null) {
        if (value == '' || value == -1) {
            return false;
        }
        return true;
    }

});

addValidationRule('validateWorkEx', function(values, value) {
    
    if(typeof value != 'undefined') { 
        if (value == '') {
            return false;
        }
        return true;
    }

});

class CustomSelect extends Component {

    constructor(props) {
        super(props);
        this.changeValue = this.changeValue.bind(this);
        this.className   = null;
    }

    render() {

        const { getValue } = this.props;
        var className      = "reg-form signup-fld invalid";
        if(isUserLoggedIn() && getValue() && this.props.isSelected) {
            className += " ih filled";
        }
        if(this.props.selectedInput) {
            className += " filled";
        }
        if(!this.className) {
            this.className = className;
        }
        
        return (
            <React.Fragment>
                <div className={this.className} onClick={this.props.clickHandlerFunction} id={this.props.baseId+"_block_"+this.props.regFormId}>
                    <div className="ngPlaceholder">{this.props.title}
                        {
                            this.props.optional && 
                            <span className="optl"> (Optional)</span>
                        }
                    </div>
                    <div className="multiinput" id={this.props.baseId+"_input_"+this.props.regFormId}>{this.props.selectedInput}</div>
                    <div className="input-helper">
                        <div className="up-arrow"></div>
                        <div id={this.props.baseId+"_error_"+this.props.regFormId} className="helper-text"></div>
                    </div>
                </div>
                <div className="ih sValue">
                    <select 
                        name={this.props.name} 
                        id={this.props.baseId+"_"+this.props.regFormId} 
                        onChange={this.changeValue} 
                        value={getValue() ? getValue() : this.props.defaultValue}
                    >
                        {this.props.options}
                    </select>
                </div>
            </React.Fragment>
            );

    }

    componentDidUpdate() {
        
        var inputDiv = document.getElementById(this.props.baseId+"_block_"+this.props.regFormId);
        var errorDiv = document.getElementById(this.props.baseId+"_error_"+this.props.regFormId);

        const { showError, isPristine, hasValue, isFormSubmitted, getErrorMessage } = this.props;
        const hasError     = (!isPristine() && isFormSubmitted() && (showError() || !hasValue()));
        const errorMessage = hasError ? getErrorMessage() : null;
        
		if(errorMessage && errorDiv.innerHTML != '') {
            inputDiv.classList.add('invalidFld','deError');
        } else {
            inputDiv.classList.remove('invalidFld','deError');
        }

    }

    changeValue(event) {

        this.props.setValue(event.currentTarget.value);

        if(typeof this.props.changeHandlerFunction == 'function' && typeof this.props.changeHandlerFunction != 'undefined') {
            this.props.changeHandlerFunction();
        }

    }

}

CustomSelect.propTypes = {
    ...propTypes,
};

export default withFormsy(CustomSelect);
