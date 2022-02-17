import React, { Component } from 'react';
import CustomSingleSelectLayer from './../../Common/components/CustomSingleSelectLayer';
import CustomSelect from './../../Common/components/CustomSelect';

class PrefYear extends Component {

	constructor(props) {
		
		super(props);
		this.state = {
			isOpenSelectLayer: false
		}
		this.layerData          = null;
		this.layerHeading       = null;
		this.fieldId            = null;
		this.renderPrefYearHtml = this.renderPrefYearHtml.bind(this);
		
	}

	render() {
	
		return (
				<div>
					{this.renderPrefYearHtml()}
					<CustomSingleSelectLayer show={this.state.isOpenSelectLayer} data={this.layerData} heading={this.layerHeading} search={false} fieldId={this.fieldId} showSubHeading={true} clickHandlerFunction={this.setFieldValue.bind(this)} onClose={this.closeSelectLayer.bind(this)} >
            		</CustomSingleSelectLayer>
				</div>
			);

	}

	renderPrefYearHtml() {

		var prefYearValues       = this.props.prefYearValues;
		var prefYearOptions      = [];
		var prefYearMandatory    = this.props.prefYearMandatory;
		var isValidationRequired = '';
		var optional             = true;
		var validations          = {};
		var validationErrors     = {};

		if(prefYearMandatory == 1) {
			optional = false;
			validations = {validateSelectMandatory: true};
			validationErrors = {validateSelectMandatory: 'Please select Preferred Year of Admission'}
		}
		
		prefYearOptions.push(<option key={-1} value="">Select</option>);
		
		for(var index in prefYearValues) {
			prefYearOptions.push(<option key={index} value={index}>{prefYearValues[index]}</option>);
		}
		
		return (
			<CustomSelect 
				name="prefYear" 
				title="Preferred Year of Admission"
				optional={optional}
				validations={validations}
				validationErrors={validationErrors} 
				baseId="prefYear" 
				options={prefYearOptions} 
				regFormId={this.props.regFormId} 
				clickHandlerFunction={this.openSelectLayer.bind(this,'prefYear',prefYearValues,'Preferred Year of Admission')} 
			/>
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
		
		document.getElementById(params.fieldId+"_block_"+this.props.regFormId).classList.add("filled");
		document.getElementById(params.fieldId+"_input_"+this.props.regFormId).innerHTML = params.itemName;
		document.getElementById(params.fieldId+"_"+this.props.regFormId).value           = params.itemId;
  		this.setState({
        	...this.state,
        	isOpenSelectLayer: false
        });

        var event = new Event('change', { bubbles: true });
  		document.getElementById(params.fieldId+"_"+this.props.regFormId).dispatchEvent(event);

	}

}

export default PrefYear;