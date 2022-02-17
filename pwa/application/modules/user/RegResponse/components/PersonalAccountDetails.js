import React, { Component } from 'react';
import CustomSingleSelectLayer from './../../Common/components/CustomSingleSelectLayer';
import CustomInput from './../../Common/components/CustomInput';
import shikshaConfig from './../../../common/config/shikshaConfig';

class PersonalAccountDetails extends Component {

	constructor(props) {
		
		super(props);
		this.state = {
			isOpenSelectLayer: false,
			formValues: {}
		}
		this.layerData              = null;
		this.layerHeading           = null;
		this.fieldId                = null;
		this.renderPasswordField    = this.renderPasswordField.bind(this);
		this.renderIsdCodeHtml      = this.renderIsdCodeHtml.bind(this);
		this.togglePassword         = this.togglePassword.bind(this);
		this.handleKeyUp            = this.handleKeyUp.bind(this);
		this.showHelpBox            = this.showHelpBox.bind(this);
		this.maxLengthCheck         = this.maxLengthCheck.bind(this);
		this.mobileValidations      = {};
		this.mobileValidationErrors = {};
		this.isdClassName           = null;
		this.runAutoComplete        = this.runAutoComplete.bind(this);
		this.showSuggestedItem      = this.showSuggestedItem.bind(this);
		this.hideAutoSuggestor      = this.hideAutoSuggestor.bind(this);
		
	}

	render() {
		
		if(!this.state.formValues) {
			return null;
		}
		var formValues = this.state.formValues;
		
		if(formValues.isdCode == '91-2' || formValues.isdCode == null) {

			this.mobileValidations 	    = {validateMandatory: true, validateNationalMobile: true, validateMobileLength: 10};
			this.mobileValidationErrors = {validateMandatory: 'Please enter your Mobile number', validateNationalMobile: 'The Mobile number can start with 9, 8, 7 or 6', validateMobileLength: 'The Mobile number must have 10 digits only'};

		} else {

			this.mobileValidations 	    = {validateMandatory: true, validateMinLength: 6, validateMaxLength: 20};
			this.mobileValidationErrors = {validateMandatory: 'Please enter your Mobile number', validateMinLength: 'The Mobile number must contain minimum 6 digits', validateMaxLength: 'The Mobile number can contain maximum 20 digits'};

		}

		return (
			<React.Fragment>
				<CustomInput 
					name="email" 
					title="Email address" 
					value={formValues.email ? formValues.email : null} 
					maxLength="125" 
					validations={{validateMandatory: true, validateEmailAddress: true}} 
					validationErrors={{validateMandatory: 'Please enter your Email address', validateEmailAddress: 'The Email address specified is not correct'}} 
					baseId="email" 
					regFormId={this.props.regFormId} 
					showHelpBox={this.showHelpBox} 
					helpText="This will be your Shiksha user ID." 
					onKeyUp={this.runAutoComplete.bind(this)}
					onBlur={this.hideAutoSuggestor.bind(this)}
				/>
				{ this.renderPasswordField() }
				<CustomInput 
					name="firstName" 
					title="First name" 
					minLength="1" 
					maxLength="50" 
					value={formValues.firstName ? formValues.firstName : null} 
					validations={{validateMandatory: true, validateProfanity: true, validateBlacklist: true, validateSpecialChars: true}} 
					validationErrors={{validateProfanity: 'Please don\'t use objected words for the first name', validateMandatory: 'Please enter your First name', validateBlacklist: 'This username is not allowed', validateSpecialChars: 'The First Name can not contain special characters'}} 
					baseId="firstName" 
					regFormId={this.props.regFormId} 
				/>
				<CustomInput 
					name="lastName" 
					title="Last name" 
					minLength="1" 
					maxLength="50" 
					value={formValues.lastName ? formValues.lastName : null} 
					validations={{validateMandatory: true, validateProfanity: true, validateBlacklist: true, validateSpecialChars: true}} 
					validationErrors={{validateProfanity: 'Please don\'t use objected words for the last name', validateMandatory: 'Please enter your Last name', validateBlacklist: 'This username is not allowed', validateSpecialChars: 'The Last Name can not contain special characters'}} 
					baseId="lastName" 
					regFormId={this.props.regFormId} 
				/>
				<div className="pwa-reg">
					{this.renderIsdCodeHtml()}
					<CustomInput 
						name="mobile" 
						type="tel" 
						minLength="10" 
						maxLength="10" 
						value={formValues.mobile ? formValues.mobile : null} 
						title="Mobile number" 
						validations={this.mobileValidations}
						validationErrors={this.mobileValidationErrors}
						baseId="mobile" 
						regFormId={this.props.regFormId} 
						showHelpBox={this.showHelpBox} 
						helpText="Please enter correct number. One time password (OTP) will be sent for verification." 
						onInput={this.maxLengthCheck.bind(this,'mobile')} 
					/>
				</div>
				<CustomSingleSelectLayer show={this.state.isOpenSelectLayer} data={this.layerData} heading={this.layerHeading} search={true} fieldId={this.fieldId} showSubHeading={true} clickHandlerFunction={this.setFieldValue.bind(this)} onClose={this.closeSelectLayer.bind(this)} >
            	</CustomSingleSelectLayer>
			</React.Fragment>
			);

	}

	componentWillMount() {

		if(this.props.isLoggedInUser && this.props.formValues) {
			let formValues = Object.assign({},this.state.formValues,this.props.formValues);
			this.setState({
	        	...this.state,
	        	formValues: formValues
	        });
		}

	}

    renderPasswordField() {

    	if(this.props.isLoggedInUser) {

    		return null;

    	} else {
    		
    		return (
    			<CustomInput 
					name="password" 
					type="password" 
					title="Create a password" 
					minLength="6" 
					maxLength="25"
					validations={{validateMandatory: true, validateMinLength: 6}} 
					validationErrors={{validateMandatory: 'Please enter Password', validateMinLength: 'The Password should be atleast 6 characters'}} 
					baseId="password" 
					regFormId={this.props.regFormId} 
					onKeyUp={this.handleKeyUp} 
					togglePassword={this.togglePassword} 
					onInput={this.maxLengthCheck.bind(this,'password')}
				/>
    		);

    	}

    }

	renderIsdCodeHtml() {

		if(!this.props.isdCodeValues) {
			return null;
		}

		var isdCodeList    = this.props.isdCodeValues.isdCodeList;
		var isdCodeOptions = [];
		var className      = "reg-form signup-fld isdC";
        
        if(this.props.isLoggedInUser && this.state.formValues.isdCode != null) {
            className += " ih filled";
        }

        if(!this.isdClassName) {
        	this.isdClassName = className;
        }
		
		for(var index in isdCodeList) {
			isdCodeOptions.push(<option key={index} value={index}>{isdCodeList[index]}</option>);
		}

		return (
			<div className={this.isdClassName} id={"isdCode_block_"+this.props.regFormId} onClick={this.openSelectLayer.bind(this,'isdCode',isdCodeList,'Country')} >
				<div className="isdLayer invalid signup-fld">
					<div className="multiinput" id={"isdCode_input_"+this.props.regFormId}>
						<span id="countryInitials" className="moreLnk">IND</span> <span id="countryCode" className="text">+91</span>
					</div>
				</div>
				<div className="ih sValue">
            		<select name="isdCode" id={"isdCode_"+this.props.regFormId} value={this.state.formValues.isdCode ? this.state.formValues.isdCode : "91-2"} onChange={this.changeValue.bind(this)}>
	           			{isdCodeOptions}
		      		</select>
				</div>
			</div>
			);

	}

	openSelectLayer(fieldId, fieldValues, fieldLabelForSearch) {
		
		this.setState({
			...this.state, 
			isOpenSelectLayer: true,
		});
		this.layerData    = fieldValues;
		this.layerHeading = fieldLabelForSearch;
		this.fieldId      = fieldId;

	}

	closeSelectLayer() {
		this.setState({...this.state, isOpenSelectLayer: false});
	}

	setFieldValue(params) {
		
		var isdCodeMap     = this.props.isdCodeValues.isdCodeMap;
		var isdCode        = params.itemId.split('-');
		var isdDisplayHtml = '<span id="countryInitials" class="moreLnk">'+isdCodeMap[params.itemId]+'</span> <span id="countryCode" class="text">+'+isdCode[0]+'</span>';

		document.getElementById(params.fieldId+"_block_"+this.props.regFormId).classList.add("filled");
  		document.getElementById(params.fieldId+"_input_"+this.props.regFormId).innerHTML = isdDisplayHtml;
  		document.getElementById(params.fieldId+"_"+this.props.regFormId).value = params.itemId;

  		if(params.itemId != '91-2') {
  			document.getElementById('residenceCityLocality_block_'+this.props.regFormId).classList.add("ih");
  			document.getElementById('locality_block_'+this.props.regFormId).classList.add("ih");
  			document.getElementById('mobile_'+this.props.regFormId).minLength = 6;
  			document.getElementById('mobile_'+this.props.regFormId).maxLength = 20;
  		} else {
  			document.getElementById('residenceCityLocality_block_'+this.props.regFormId).classList.remove("ih");
  			document.getElementById('locality_block_'+this.props.regFormId).classList.remove("ih");
  			document.getElementById('mobile_'+this.props.regFormId).minLength = 10;
  			document.getElementById('mobile_'+this.props.regFormId).maxLength = 10;
  		}

  		if(params.itemId != '91-2') {

  			this.mobileValidations 	    = {validateMandatory: true, validateMinLength: 6, validateMaxLength: 20};
			this.mobileValidationErrors = {validateMandatory: 'Please enter your Mobile number', validateMinLength: 'The Mobile number must contain minimum 6 digits', validateMaxLength: 'The Mobile number can contain maximum 20 digits'};
			
		} else {

			this.mobileValidations 	    = {validateMandatory: true, validateNationalMobile: true, validateMobileLength: 10};
			this.mobileValidationErrors = {validateMandatory: 'Please enter your Mobile number', validateNationalMobile: 'The Mobile number can start with 9, 8, 7 or 6', validateMobileLength: 'The Mobile number must have 10 digits only'};
		}
		
		var event = new Event('change', { bubbles: true });
  		document.getElementById(params.fieldId+"_"+this.props.regFormId).dispatchEvent(event);
  		
	}

	changeValue() {
		
		let isdCodeValue = document.getElementById("isdCode_"+this.props.regFormId).value;
		let formValues   = Object.assign({},this.state.formValues,{isdCode: isdCodeValue });
		
		this.setState({
        	...this.state,
        	isOpenSelectLayer: false,
        	formValues: formValues
        });

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

			var event = new Event('blur', { bubbles: true });
  			document.getElementById(id+"_"+this.props.regFormId).dispatchEvent(event);
			
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

	togglePassword(type) {
		if(type == 'show') {
			document.getElementById('password_'+this.props.regFormId).type = 'text';
			document.getElementById('show_pswd').classList.add('ih');
			document.getElementById('hide_pswd').classList.remove('ih');
		} else {
			document.getElementById('password_'+this.props.regFormId).type = 'password';
			document.getElementById('hide_pswd').classList.add('ih');
			document.getElementById('show_pswd').classList.remove('ih');
		}
	}

	handleKeyUp() {
		var passwordValue = document.getElementById('password_'+this.props.regFormId).value;
		var passwordType  = document.getElementById('password_'+this.props.regFormId).type;
		if(passwordValue != '') {
			if(passwordType == 'text') {
				document.getElementById('hide_pswd').classList.remove('ih');
			} else {
				document.getElementById('show_pswd').classList.remove('ih');
			}
		} else {
			document.getElementById('show_pswd').classList.add('ih');
			document.getElementById('hide_pswd').classList.add('ih');
		}
	}

	maxLengthCheck(baseId) {
		
		var input = document.getElementById(baseId+'_'+this.props.regFormId);
		
		if(baseId == 'mobile') {
			var nonNumReg = /[^0-9]/g;
  			input.value = input.value.replace(nonNumReg, '');
		}

		if (input.value.length > input.maxLength) {
      		input.value = input.value.slice(0, input.maxLength);
    	}

  	}

  	runAutoComplete() {

		var searchString = document.getElementById('email_'+this.props.regFormId).value;
        if(searchString == '') { return false; }

		var searchChar              = '@';
		var suggestedItems          = shikshaConfig['validEmailDomains'];
		var autoCompleteContainer   = 'suggestor_'+this.props.regFormId;
		var autoCompleteContainerUL = 'suggestor_list_'+this.props.regFormId;

		document.getElementById(autoCompleteContainerUL).innerHTML = '';
		document.getElementById(autoCompleteContainer).style       = "display:none";
        
        var charPos = searchString.indexOf(searchChar);
        if(charPos != -1) {

			var suggestedItemClass = 'suggested-item';
			var searchStr          = searchString.substring(charPos, searchString.length);
			var suggestorItemsStr  = '';
			var suggestorItems     = [];
			var self               = this;

            for (var i=0; i<suggestedItems.length; i++) {

                if (suggestedItems[i].indexOf(searchStr) != -1) { 

					var suggestorItem = suggestedItems[i].substring(1, suggestedItems[i].length);
					var liNode        = document.createElement("LI");
					var textNode      = document.createTextNode(suggestorItem);
                    liNode.appendChild(textNode);
                    liNode.setAttribute("id", suggestorItem);
					liNode.setAttribute("class", suggestedItemClass);
					liNode.onclick = function() {
						self.showSuggestedItem(this);
					}
                    document.getElementById(autoCompleteContainerUL).appendChild(liNode);

                }

            }
			
			document.getElementById(autoCompleteContainer).style = "display:block";

        }

  	}

  	showSuggestedItem(obj) {

		var suggestedValue = obj.innerHTML;
		var emailValue     = document.getElementById('email_'+this.props.regFormId).value;
		var charPos        = emailValue.indexOf('@');
		var searchStr      = emailValue.substring(0, charPos+1);

        document.getElementById('email_'+this.props.regFormId).value     = searchStr+suggestedValue;
        document.getElementById('suggestor_'+this.props.regFormId).style = "display:none";
        document.getElementById('email_'+this.props.regFormId).focus();

    }

    hideAutoSuggestor(e) {

    	e.stopPropagation();
    	var container = document.getElementById('suggestor_'+this.props.regFormId);
	    //check if the clicked area is dropDown or not
	  	if(!e.target.classList.contains('emailFld-block')) {
	  		setTimeout(function() {
	        	container.style = "display:none";
	        }, 100);
	    }
	   	
    }

}

export default PersonalAccountDetails;