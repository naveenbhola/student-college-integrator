import React, { Component } from 'react';
import Formsy, { addValidationRule, propTypes, withFormsy } from 'formsy-react';
import { isUserLoggedIn } from './../../../../utils/commonHelper';

addValidationRule('validateChecked', function(values, value, params) {

    var inputObj = document.getElementById(params.inputName+'_'+params.regFormId);
    if(typeof inputObj != 'undefined' && inputObj != null){
        var chkd = inputObj.checked;
        if(chkd) {
            return true;
        }
    }
    
    return false;

});

class CustomCheckBox extends Component {

    constructor(props) {
        super(props);
        this.changeValue = this.changeValue.bind(this);
        this.state = {
             value: (this.props.defaultChecked == true) ? true : ''
        };
    }

    changeValue(event) {

        var errorDiv = document.getElementById(this.props.baseId+"_error_"+this.props.regFormId);
        if(event.target.checked == false && errorDiv.innerHTML != '') {            
            errorDiv.innerHTML = '';
        }
        this.props.setValue(event.target.checked);
        this.setState({value : event.target.checked});

    }

    render() {

        var value = this.state.value;
        return (
            <React.Fragment>                
                <input type="checkbox" onChange={this.changeValue} checked={value} className="ngInput chk-bx" id={this.props.baseId+"_"+this.props.regFormId} name={this.props.name} regfieldid={this.props.regFormId} />
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
            inputDiv.classList.add('invalidFld');
        } else if(inputDiv.classList.contains('invalidFld')) {
            inputDiv.classList.remove('invalidFld');
        }

    }

}

CustomCheckBox.propTypes = {
    ...propTypes,
};

export default withFormsy(CustomCheckBox);
