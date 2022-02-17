import React from 'react';
import '../assets/ChangeCourse.css'
import PropTypes from 'prop-types';
import TagWidget from './../../common/components/CommonTagWidget';

class ExamChangeCourseLayer extends React.Component{
	constructor(){
		super();
	}

	prepareData=()=>{
		let finalArr =[];
		let currentCourse = 0;
		this.props.groupMapping.forEach((item)=>{
			let tempObj  = {};
			let queryString = '';
			if(item.primaryGroup != 1){
				queryString = '?course='+item.id;
			}else if(item.primaryGroup == 1){
				currentCourse = item.id;	
			}
			tempObj.id = item.id;
			tempObj.name = item.name;
			tempObj.url  = this.props.basePageUrl+queryString;
			finalArr.push(tempObj);
		});
		return finalArr;
	}

	render()
	{
		return (
			<React.Fragment>
				<div className="exam_course">
					<TagWidget data={this.prepareData()} mainHeading="This exam is conducted for the multiple courses. Select a course of your interest:" selectedTag={this.props.groupId}/>
	         	</div>
	         	<p className="ccHeading">Showing details for <strong>{this.props.selectedGroupName}</strong></p>
         	</React.Fragment>
		)
	}
}

ExamChangeCourseLayer.propTypes = {
   groupMapping     : PropTypes.array,
   selectedGroupName: PropTypes.string.isRequired,
   basePageUrl      : PropTypes.string.isRequired
}
export default ExamChangeCourseLayer;
