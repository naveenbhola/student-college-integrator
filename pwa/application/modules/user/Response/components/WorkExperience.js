import React, { Component } from 'react';
import CustomSingleSelectLayer from './../../Common/components/CustomSingleSelectLayer';
import CustomSelect from './../../Common/components/CustomSelect';

class WorkExperience extends Component {

	constructor(props) {
		
		super(props);
		this.state = {
			isOpenSelectLayer: false
		}
		this.layerData         = null;
		this.layerHeading      = null;
		this.fieldId           = null;
		this.workExperience    = (props.formValues.experience) ? (props.formValues.experience) : "";
		this.renderWorkExpHtml = this.renderWorkExpHtml.bind(this);
		
	}

	render() {
			
		return (
				<div>
					{this.renderWorkExpHtml()}
					<CustomSingleSelectLayer show={this.state.isOpenSelectLayer} data={this.layerData} heading={this.layerHeading} search={false} fieldId={this.fieldId} showSubHeading={true} clickHandlerFunction={this.setFieldValue.bind(this)} onClose={this.closeSelectLayer.bind(this)} >
            		</CustomSingleSelectLayer>
				</div>
			);

	}

	renderWorkExpHtml() {

		var workExList    = this.props.workExList;
		var workExOptions = [];
		
		workExOptions.push(<option key={-2} value="">Select work experience</option>);
		workExOptions.push(<option key={-1} value={-1}>{workExList[-1]}</option>);  //to get the correct sequence to be displayed
		
		for(var index in workExList) {
			if(index != -1){
				workExOptions.push(<option key={index} value={index}>{workExList[index]}</option>);
			}
		}

		return (
			<CustomSelect 
				name="workExperience" 
				title="Work experience" 
				defaultValue={this.workExperience} 
				value={this.workExperience} 
				isSelected={this.workExperience >= -1 && this.workExperience != "" ? true : false} 
				validations={{validateWorkEx: true}}
				validationErrors={{validateWorkEx: 'Please select Work experience'}} 
				baseId="workExp" 
				options={workExOptions} 
				regFormId={this.props.regFormId} 
				clickHandlerFunction={this.openSelectLayer.bind(this,'workExp',workExList,'Work Experience')} 
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
  		document.getElementById(params.fieldId+"_"+this.props.regFormId).value = params.itemId;
  		this.setState({
        	...this.state,
        	isOpenSelectLayer: false
        });

        var event = new Event('change', { bubbles: true });
  		document.getElementById(params.fieldId+"_"+this.props.regFormId).dispatchEvent(event);

	}

}

export default WorkExperience;