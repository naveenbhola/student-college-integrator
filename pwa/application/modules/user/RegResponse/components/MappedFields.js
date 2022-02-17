import React, { Component } from 'react';
import PreferredLocation from './PreferredLocation';
import WorkExperience from './WorkExperience';
import ExamField from './ExamField';
import MappedEntities from './MappedEntities';

class CourseMappedFields extends Component {
	
	render() {

		if(!this.props.clientCourseId || !this.props.courseData) {
			return null;
		}
		
		return (
				<div id={"mappedFields_"+this.props.regFormId}>
					<MappedEntities courseData={this.props.courseData} regFormId={this.props.regFormId} />
					{this.props.courseData.locations && <PreferredLocation locations={this.props.courseData.locations} formValues={this.props.formValues} regFormId={this.props.regFormId} />}
					{this.props.courseData.workExList && <WorkExperience workExList={this.props.courseData.workExList} formValues={this.props.formValues} regFormId={this.props.regFormId} />}
					{this.props.courseData.examList && <ExamField examList={this.props.courseData.examList} formValues={this.props.formValues} regFormId={this.props.regFormId} />}
				</div>
			);
		
	}

}

export default CourseMappedFields;