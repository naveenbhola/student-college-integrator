import React, { Component } from 'react';
import CustomMultiSelectLayer from './../../Common/components/CustomMultiSelectLayer';

class ExamField extends Component {

	constructor(props) {
		
		super(props);
		this.state = {
			isOpenSelectLayer: false
		}
		this.layerData       = null;
		this.layerHeading    = null;
		this.searchHeading   = null;
		this.fieldId         = null;
		this.selectedExams	 = (props.formValues.exams) ? props.formValues.exams : null;
		this.renderExamsHtml = this.renderExamsHtml.bind(this);

	}

	render() {
			
		return (
				<div>
					{this.renderExamsHtml()}
					<CustomMultiSelectLayer show={this.state.isOpenSelectLayer} data={this.layerData} heading={this.layerHeading} searchHeading={this.searchHeading} search={true} fieldId={this.fieldId} showSubHeading={true} clickHandlerFunction={this.setFieldValue.bind(this)} onClose={this.closeSelectLayer.bind(this)} >
            		</CustomMultiSelectLayer>
				</div>
			);

	}

	componentDidMount() {

		if(this.selectedExams) {
			
			var container         = document.querySelector('#examField_'+this.props.regFormId);
			var checkedInputs     = container.querySelectorAll('input[type="checkbox"]');
			var firstCheckedInput = null;
			var count             = 0;

			Array.from(checkedInputs).forEach(input => {
			    if(input.checked){
					if(!firstCheckedInput) {
						firstCheckedInput = input.value;
					}
					count++;
				}
			});

			if (firstCheckedInput) {

	            var tempHTML = '<span class="text">' + firstCheckedInput + '</span>';
	            if (count > 1) {
	                tempHTML += ' <span class="moreLnk">&nbsp;+' + (count - 1) + ' more</span>';
	            }
	            document.getElementById("exams_input_"+this.props.regFormId).innerHTML = tempHTML;
				document.getElementById('exams_block_'+this.props.regFormId).classList.add('filled');

	        } else {

	        	document.getElementById("exams_input_"+this.props.regFormId).innerHTML = '';
				document.getElementById('exams_block_'+this.props.regFormId).classList.remove('filled');

	        }

	    }

	}

	renderExamsHtml() {

		var examList    = this.props.examList;
		var examOptions = [];
		
		for(var index in examList) {

			if(this.selectedExams && this.selectedExams.indexOf(examList[index]) > -1) {
				examOptions.push(<input key={index} type="checkbox" id={"exams_"+index} value={examList[index]} name="exams[]" label={examList[index]} checked={true} />);
			} else {
				examOptions.push(<input key={index} type="checkbox" id={"exams_"+index} value={examList[index]} name="exams[]" label={examList[index]} checked={false} />);
			}

		}

		return (
			<React.Fragment>
				<div className="reg-form signup-fld invalid" id={"exams_block_"+this.props.regFormId} onClick={this.openSelectLayer.bind(this,'exams',examList,'Exams Taken','Exams')}>
					<div className="ngPlaceholder">Exams taken <span className="optl">(Optional)</span></div>
					<div className="multiinput" id={"exams_input_"+this.props.regFormId}></div>
				</div>
				<div className="ih" id={"examField_"+this.props.regFormId}>
					{examOptions}
				</div>
			</React.Fragment>
			);

	}

	openSelectLayer(fieldId, fieldValues, fieldLabelForLayer, fieldLabelForSearch) {
		
		this.setState({
			...this.state, 
			isOpenSelectLayer: true,
		});
		this.layerData     = fieldValues;
		this.layerHeading  = fieldLabelForLayer;
		this.searchHeading = fieldLabelForSearch
		this.fieldId       = fieldId;

	}

	closeSelectLayer() {
		this.setState({...this.state, isOpenSelectLayer: false});
	}

	setFieldValue() {

		var container         = document.querySelector('#mainDiv-checkboxes');
		var checkedInputs     = container.querySelectorAll('input[type="checkbox"]');
		var originalId        = null;
		var firstCheckedInput = null;
		var count             = 0;
		this.selectedExams    = [];
		
		Array.from(checkedInputs).forEach(input => {
			var originalId   = input.id.split('_input');
			var inputElement = document.getElementById(originalId[0]);
		    if(input.checked){
				inputElement.checked = true;
				if(!firstCheckedInput) {
					firstCheckedInput = inputElement.value;
				}
				this.selectedExams[count] = inputElement.value;
				count++;
			} else {
				inputElement.checked = false;
			}
		});

		if (firstCheckedInput) {

            var tempHTML = '<span class="text">' + firstCheckedInput + '</span>';
            if (count > 1) {
                tempHTML += ' <span class="moreLnk">&nbsp;+' + (count - 1) + ' more</span>';
            }
            
            document.getElementById("exams_input_"+this.props.regFormId).innerHTML = tempHTML;
			document.getElementById('exams_block_'+this.props.regFormId).classList.add('filled');

        } else {

        	document.getElementById("exams_input_"+this.props.regFormId).innerHTML = '';
			document.getElementById('exams_block_'+this.props.regFormId).classList.remove('filled');

        }

		this.closeSelectLayer();
	}

}

export default ExamField;