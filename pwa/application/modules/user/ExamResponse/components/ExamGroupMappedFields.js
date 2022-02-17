import React, { Component } from 'react';
import CourseLevel from './CourseLevel';
import { filterBaseCoursesByLevel } from './../actions/ExamResponseFormAction';

class ExamGroupMappedFields extends Component {
	
	constructor(props) {
		
		super(props);
		this.state = {
			baseCourses: props.baseCourses,
			isPristine: true
		}
		this.getSubstreamSpecMapping = this.getSubstreamSpecMapping.bind(this);
		this.filterBaseCourses       = this.filterBaseCourses.bind(this);
		this.changeLocalityByLevel  = this.changeLocalityByLevel.bind(this);

	}

	componentWillReceiveProps(nextProps) {
		
		if(nextProps.pristine) {
			this.setState({isPristine: true});
		}
	}

	render() {
		if(!this.props.examGroupId || !this.props.examGroupData) {
			return null;
		}
		
		var examGroupData = this.props.examGroupData;
		var baseCourses   = this.state.isPristine ? this.props.baseCourses : this.state.baseCourses;
		
		return (
				<div id={"mappedFields_"+this.props.regFormId}>
					{
						(Object.keys(examGroupData.level).length > 1) &&
						<CourseLevel examGroupId={this.props.examGroupId} levelMapping={examGroupData.level} formValues={this.props.formValues} regFormId={this.props.regFormId} changeHandlerFunction={this.filterBaseCourses} />
					}						
					<div className="ih">
						<input type="hidden" value={examGroupData.mappedHierarchies.stream} name="stream" id={"stream_"+this.props.regFormId} />
						{
							(Object.keys(examGroupData.level).length == 1) &&
							<input type="hidden" value={Object.keys(examGroupData.level)[0]} name="level" id={"level_"+this.props.regFormId} />
						}
						{
							baseCourses.map((baseCourse, i) => <input type="hidden" value={baseCourse} name="baseCourses[]" key={i} id={"baseCourse_"+baseCourse} />)
						}
						{
							examGroupData.mode.map((mode, i) => <input type="hidden" value={mode} name="educationType[]" key={i} id={"mode_"+mode} />)
						}
						{ 
							Object.keys(examGroupData.mappedHierarchies.hierarchies).length > 0 && this.getSubstreamSpecMapping() 
						}
					</div>
				</div>
			);
		
	}

	getSubstreamSpecMapping() {

		var regFormId     = this.props.regFormId;
		var examGroupData = this.props.examGroupData;
		var unmappedSpecs = null;

		if(typeof(examGroupData.mappedHierarchies.hierarchies[0]) != 'undefined' || examGroupData.mappedHierarchies.hierarchies[0] != null) {
			unmappedSpecs = examGroupData.mappedHierarchies.hierarchies[0];
		}

		var hierarchies = examGroupData.mappedHierarchies.hierarchies;
		
		return (
				<React.Fragment>
				{
					Object.keys(hierarchies).map(function(index) {

						if(index != 0) {
							return(
								<div key={'a'+index}>
									<input key={'b'+index} type="hidden" value={index} name="subStream[]" id={"subStream_"+index+"_"+regFormId} />
									<div key={'c'+index}>
									{
										Object.keys(hierarchies[index]).map(function(specKey) {
											return(
												<input key={'d'+specKey} type="hidden" value={hierarchies[index][specKey]} id={"spec_"+hierarchies[index][specKey]+"_"+regFormId} name="specializations[]" parentid={"subStream_"+index+"_"+regFormId} />
											);
										})
									}
									</div>
								</div>
							);
						}
					})
				}
				{ 
					unmappedSpecs && Object.keys(unmappedSpecs).map(function(specKey) {
						return(
							<div key={'e'+specKey}>
								<input key={'f'+specKey} type="hidden" value={unmappedSpecs[specKey]} name="specializations[]" id={"spec_unmp_"+unmappedSpecs[specKey]+"_"+regFormId} />
							</div>
						);
					})
				}
				</React.Fragment>
			);
		
	}

	filterBaseCourses(examGroupId, levelId) {

		this.setState({...this.state, loader: true});
		var self         = this;
		var params       = 'examGroupId='+examGroupId+'&levelId='+levelId;
		var callResponse = Promise.resolve(filterBaseCoursesByLevel(params)).then((response) => {
			return response;
		});
		
		Promise.resolve(callResponse).then((resp) => {
			this.changeLocalityByLevel(resp.baseCourse);
			
			self.setState({
				...self.state,
				baseCourses: resp.baseCourse,
				isPristine: false,
				loader: false
			});
			
		});
	}

	changeLocalityByLevel(baseCourse)
	{
		this.props.changeLocalityByLevel(baseCourse);
	}

}

export default ExamGroupMappedFields;
