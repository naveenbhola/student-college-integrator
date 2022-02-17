import React, { Component } from 'react';
import CustomSingleSelectLayer from './../../Common/components/CustomSingleSelectLayer';
import CustomSelect from './../../Common/components/CustomSelect';

class CourseLevel extends Component {

	constructor(props) {
		
		super(props);
		this.state = {
			isOpenSelectLayer: false
		}
		this.layerData             = null;
		this.layerHeading          = null;
		this.fieldId               = null;
		this.courseLevel           = (props.formValues.level) ? (props.formValues.level) : "";
		this.renderCourseLevelHtml = this.renderCourseLevelHtml.bind(this);
		this.onChangeFunction      = this.onChangeFunction.bind(this);
		
	}

	render() {
			
		return (
				<div>
					{this.renderCourseLevelHtml()}
					<CustomSingleSelectLayer show={this.state.isOpenSelectLayer} data={this.layerData} heading={this.layerHeading} search={false} fieldId={this.fieldId} showSubHeading={true} clickHandlerFunction={this.setFieldValue.bind(this)} onClose={this.closeSelectLayer.bind(this)} >
            		</CustomSingleSelectLayer>
				</div>
			);

	}

	renderCourseLevelHtml() {

		var levelMapping = this.props.levelMapping;
		var levelOptions = [];
		
		levelOptions.push(<option key={-1} value="">Select Level</option>);
		
		for(var index in levelMapping) {
			if(levelMapping[index]=="None")
			{
				levelMapping[index] = "Certificate";
			}
			levelOptions.push(<option key={index} value={index}>{levelMapping[index]}</option>);
		}

		return (
			<CustomSelect 
				name="level" 
				title="Level of study you're interested in" 
				defaultValue={this.courseLevel} 
				value={this.courseLevel} 
				isSelected={this.courseLevel != "" ? true : false} 
				validations={{validateSelectMandatory: true}}
				validationErrors={{validateSelectMandatory: 'Please select Level'}} 
				baseId="level" 
				options={levelOptions} 
				regFormId={this.props.regFormId} 
				clickHandlerFunction={this.openSelectLayer.bind(this,'level',levelMapping,'Level')} 
				changeHandlerFunction={this.onChangeFunction}
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
  		this.courseLevel = params.itemId;
  		this.setState({
        	...this.state,
        	isOpenSelectLayer: false
        });

        var event = new Event('change', { bubbles: true });
  		document.getElementById(params.fieldId+"_"+this.props.regFormId).dispatchEvent(event);

	}

	onChangeFunction() {
		var examGroupId = this.props.examGroupId;
		this.props.changeHandlerFunction(examGroupId, this.courseLevel);
	}

}

export default CourseLevel;
