import React, { Component } from 'react';
import CustomSingleSelectLayer from './../../Common/components/CustomSingleSelectLayer';
import CustomSelect from './../../Common/components/CustomSelect';
import { getFormByExamGroup } from './../actions/ExamResponseFormAction';
import Loader from './../../../reusable/components/Loader';
import PreventScrolling from './../../../reusable/utils/PreventScrolling';
import ExamGroupMappedFields from './ExamGroupMappedFields';

class ExamCourseGroup extends Component {

	constructor(props) {
		
		super(props);
		this.state = {
			isOpenSelectLayer: false,
			examGroupData: props.examGroupData,
			pristine: null,
			loader: false
		}
		this.layerData              = null;
		this.layerHeading           = null;
		this.fieldId                = null;
		this.selectedCourse         = 0;
		this.selectedCourseName     = null;
		this.renderCourseDropDown   = this.renderCourseDropDown.bind(this);
		this.getFormByExamGroupCall = this.getFormByExamGroupCall.bind(this);

	}

	render() {
		
		return (
				<div>
					{ this.renderCourseDropDown() }
            		{
            			this.state.examGroupData &&
            			<ExamGroupMappedFields examGroupId={this.selectedCourse} examGroupData={this.state.examGroupData} baseCourses={this.state.examGroupData.baseCourse} pristine={this.state.pristine} formValues={this.props.formValues} regFormId={this.props.regFormId} isLoggedInUser={this.props.isLoggedInUser} />
            		}
					<CustomSingleSelectLayer show={this.state.isOpenSelectLayer} data={this.layerData} sortOptions={'none'} heading={this.layerHeading} search={true} fieldId={this.fieldId} showSubHeading={true} clickHandlerFunction={this.setFieldValue.bind(this)} onClose={this.closeSelectLayer.bind(this)} >
            		</CustomSingleSelectLayer>
            		<Loader show={this.state.loader}/>
            	</div>
			);

	}

	componentDidMount() {

  		if(this.state.loader || this.state.isOpenSelectLayer) {
  			PreventScrolling.disableFormScrolling();
  		} else {
  			PreventScrolling.enableFormScrolling();
  		}

	}

	componentDidUpdate() {
				
		if(this.state.loader || this.state.isOpenSelectLayer) {
  			PreventScrolling.disableFormScrolling();
  		} else {
  			PreventScrolling.enableFormScrolling();
  		}
				
	}

	renderCourseDropDown() {

		var groupMappedExams = this.props.groupMappedExams;
		var coursesOptions   = [];
		var coursesMap       = [];
		var optionsMap       = [];
		var self             = this;
		
		optionsMap.push(<option key={-1} value="">Select Course</option>); 

		coursesOptions = Object.keys(groupMappedExams).map(function(i) {
					coursesMap[groupMappedExams[i].id] = groupMappedExams[i].name;
					if(groupMappedExams[i].id == self.props.examGroupId && !self.selectedCourse) {
						self.selectedCourse     = groupMappedExams[i].id;
						self.selectedCourseName = groupMappedExams[i].name;
					}
					return (
						<option key={i} value={groupMappedExams[i].id}>{groupMappedExams[i].name}</option>	
						);
				});
		
		optionsMap.push(coursesOptions);

		return (
			<CustomSelect 
				name="clientCourse" 
				title="Course you're interested in" 
				defaultValue={this.selectedCourse} 
				value={this.selectedCourse > 0 ? this.selectedCourse : null} 
				selectedInput={this.selectedCourseName} 
				validations={{validateSelectMandatory: true}}
				validationErrors={{validateSelectMandatory: 'Please select Course'}} 
				baseId="clientCourse" 
				options={optionsMap} 
				regFormId={this.props.regFormId} 
				clickHandlerFunction={this.openSelectLayer.bind(this,'clientCourse',coursesMap,'Course')}
				changeHandlerFunction={this.getFormByExamGroupCall}
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
		
		this.selectedCourse = params.itemId;
		document.getElementById(params.fieldId+"_block_"+this.props.regFormId).classList.add("filled");
  		document.getElementById(params.fieldId+"_input_"+this.props.regFormId).innerHTML = params.itemName;
  		document.getElementById(params.fieldId+"_"+this.props.regFormId).value = params.itemId;
  		
  		var event = new Event('change', { bubbles: true });
  		document.getElementById(params.fieldId+"_"+this.props.regFormId).dispatchEvent(event);
  		
	}

	getFormByExamGroupCall() {

		if(this.selectedCourse <= 0) {
			this.setState({...this.state, isOpenSelectLayer: false});
			return null;
		}

		this.setState({...this.state, loader: true});
		var self         = this;
		var params       = 'examGroupId='+this.selectedCourse;
		var callResponse = Promise.resolve(getFormByExamGroup(params)).then((response) => {
			return response;
		});
		
		Promise.resolve(callResponse).then((resp) => {
				
			self.setState({
				...self.state,
				examGroupData: resp,
				isOpenSelectLayer: false,
				pristine: true,
				loader: false
			});
			
		});

	}
	
}

export default ExamCourseGroup;
