import React, { Component } from 'react';
import CustomSingleSelectLayer from './../../Common/components/CustomSingleSelectLayer';
import CustomCheckBox from './../../Common/components/CustomCheckBox';

class TermsConditions extends Component {

	constructor(props) {
		
		super(props);
		this.showHelpBox = this.showHelpBox.bind(this);

	}

	showHelpBox(id, type) {

		if(type == 'toggle') {
			if(document.getElementById(id+'_help_icon').classList.contains('clk')) {
				type = 'hide';
			} else {
				type = 'show';
			}
		}
		
		if(type == 'hide') {

			document.getElementById(id+'_help_icon').classList.remove('clk');
			document.getElementById(id+'_help').classList.add('ih');
			
			if(document.getElementById(id+'_block_'+this.props.regFormId).classList.contains('invalidFld')) {
				document.getElementById(id+'_error_'+this.props.regFormId).parentNode.style="display:block";
			}

		} else {
			
			document.getElementById(id+'_help_icon').classList.add('clk');
			document.getElementById(id+'_help').classList.remove('ih');

			if(document.getElementById(id+'_block_'+this.props.regFormId).classList.contains('invalidFld')) {
				document.getElementById(id+'_error_'+this.props.regFormId).parentNode.style="display:none";
			}

		}

	}

	render() {			
        
		var termsCheckBoxName   = 'agreeTerms';
		var serviceCheckBoxName = 'serviceTerms';
		var defaultChecked      = (this.props.countryName == 'INDIA') ? true: '';

		return (
				<React.Fragment>

					<div className="reg-form invalid agreepolicy" id={termsCheckBoxName+"_block_"+this.props.regFormId}>
						<CustomCheckBox 
							name={termsCheckBoxName}
							regFormId={this.props.regFormId}
							baseId={termsCheckBoxName}
							defaultChecked={defaultChecked}
							validations={{validateChecked: {'inputName':termsCheckBoxName,'regFormId':this.props.regFormId}}}
							validationErrors={{validateChecked: 'Please accept the Privacy Policy and T&C'}} 
						/>
						<label htmlFor={termsCheckBoxName+"_"+this.props.regFormId} className="agreeterms"></label>
						<div className="policyTxt"> Yes, I have read and provide my consent for my data to be processed for the purposes as mentioned in the <a href="https://www.shiksha.com/shikshaHelp/ShikshaHelp/privacyPolicy" target="_blank">Privacy Policy </a> and the
						      <a href="https://www.shiksha.com/shikshaHelp/ShikshaHelp/termCondition" target="_blank">Terms and Conditions.</a>
						</div>  
						<div className="input-helper errdisplay">
							<div className="up-arrow"></div>
							<div className="helper-text" id={termsCheckBoxName+"_error_"+this.props.regFormId}></div>
						</div>  
					</div>

					<div className="reg-form invalid agreepolicy" id={serviceCheckBoxName+"_block_"+this.props.regFormId}>
						<CustomCheckBox 
							name = {serviceCheckBoxName}
							regFormId = {this.props.regFormId}
							baseId = {serviceCheckBoxName}
							defaultChecked = {defaultChecked}
							validations = {{validateChecked: {'inputName':serviceCheckBoxName,'regFormId':this.props.regFormId}}}
							validationErrors = {{validateChecked: 'Please provide permission to be contacted'}} 
						/>
						<label htmlFor={serviceCheckBoxName+"_"+this.props.regFormId} className="agreeterms"></label>
						<div className="policyTxt">I agree to be contacted for service related information and promotional purposes.
                            <div className="reg-info-icn" tabIndex={0} onBlur={this.showHelpBox.bind(this,serviceCheckBoxName,'hide')}>
                               <i className="d-info" id={serviceCheckBoxName+"_help_icon"} onClick={this.showHelpBox.bind(this,serviceCheckBoxName,'toggle')}></i>
                               <div className="up-arrow"></div>
                            </div>
                            <div className="input-help tm ih" id={serviceCheckBoxName+"_help"}>
								
								<div className="help-text">I can edit my communication preferences at any time in “My Profile“ section and/or may withdraw and/or restrict my consent in full or in part.
								</div>
							</div>
							
						</div>
						<div className="input-helper errdisplay">
								<div className="up-arrow"></div>
								<div className="helper-text" id={serviceCheckBoxName+"_error_"+this.props.regFormId}></div>
						</div>					
					</div>

				</React.Fragment>
			);

	}


}

export default TermsConditions;
