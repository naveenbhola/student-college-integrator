import React, { Component } from 'react';
import CustomSingleSelectLayer from './../../Common/components/CustomSingleSelectLayer';
import CustomSelect from './../../Common/components/CustomSelect';
import { getFormByClientCourse } from './../actions/ResponseFormAction';
import Loader from './../../../reusable/components/Loader';
import PreventScrolling from './../../../reusable/utils/PreventScrolling';
import CourseMappedFields from './CourseMappedFields';

class ClientCourse extends Component {

	constructor(props) {
		
		super(props);
		this.state = {
			isOpenSelectLayer: false,
			showCourseMappedFields: false,
			courseData: null,
			loader: false
		}
		this.layerData                 = null;
		this.layerHeading              = null;
		this.fieldId                   = null;
		this.selectedCourse            = 0;
		this.renderCourseDropDown      = this.renderCourseDropDown.bind(this);
		this.getFormByClientCourseCall = this.getFormByClientCourseCall.bind(this);

	}

	render() {
		
		return (
				<div>
					{ this.renderCourseDropDown() }
            		{
            			this.state.showCourseMappedFields &&
            			<CourseMappedFields clientCourseId={this.selectedCourse} courseData={this.state.courseData} formValues={this.props.formValues} regFormId={this.props.regFormId} isLoggedInUser={this.props.isLoggedInUser} />
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

		var instituteCourses       = this.props.instituteCourses;
		var courseInstituteOptions = [];
		var coursesMap             = {};
		var optionsMap             = [];
		
		optionsMap.push(<option key={-1} value="">Course</option>); 

		courseInstituteOptions = Object.keys(instituteCourses).map(function(i) {
 						coursesMap[instituteCourses[i].id] = instituteCourses[i].name;
						return (
						<option key={i} value={instituteCourses[i].id}>{instituteCourses[i].name}</option>	
							);
					});
	
		optionsMap.push(courseInstituteOptions);

		return (
			<CustomSelect 
				name="clientCourse" 
				title={this.props.courseDropdownLabel} 
				value={null} 
				validations={{validateSelectMandatory: true}}
				validationErrors={{validateSelectMandatory: 'Please select Course'}} 
				baseId="clientCourse" 
				options={optionsMap} 
				regFormId={this.props.regFormId} 
				clickHandlerFunction={this.openSelectLayer.bind(this,'clientCourse',coursesMap,'Course')}
				changeHandlerFunction={this.getFormByClientCourseCall}
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

	getFormByClientCourseCall() {
		
		if(this.selectedCourse <= 0) {
			this.setState({...this.state, isOpenSelectLayer: false});
			return null;
		}
		this.setState({...this.state, loader: true});
		var self         = this;
		var params       = 'clientCourse='+this.selectedCourse;
		var callResponse = Promise.resolve(getFormByClientCourse(params)).then((response) => {
			return response;
		});
		
		Promise.resolve(callResponse).then((resp) => {
				
			self.setState({
				...self.state,
				showCourseMappedFields: true,
				courseData: resp,
				isOpenSelectLayer: false,
				loader: false
			});

			this.props.clientCourse(resp.clientCourse);
			
		});

	}
	
}

export default ClientCourse;
