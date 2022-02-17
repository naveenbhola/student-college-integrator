import React, { Component } from 'react';

class MappedEntities extends Component {

	constructor(props) {
		super(props);
		this.getSubstreamSpecMapping = this.getSubstreamSpecMapping.bind(this);
	}

	render() {
		
		var courseData = this.props.courseData;

		return (
				<div className="ih">
					<input type="hidden" value={courseData.mappedHierarchies.stream} name="stream" id={"stream_"+this.props.regFormId} />
					<input type="hidden" value={courseData.level} name="level" id={"level_"+this.props.regFormId} />
					<input type="hidden" value={courseData.credential} name="credential" id={"credential_"+this.props.regFormId} />
					<input type="hidden" value={courseData.baseCourse} name="baseCourses[]" id={"baseCourse_"+courseData.baseCourse} />
					<input type="hidden" value={courseData.mode} name="educationType[]" id={"mode_"+courseData.mode}  />
					{ Object.keys(courseData.mappedHierarchies.hierarchies).length > 0 && this.getSubstreamSpecMapping() }
				</div>
			);	

	}

	getSubstreamSpecMapping() {

		var regFormId     = this.props.regFormId;
		var courseData    = this.props.courseData;
		var unmappedSpecs = null;

		if(typeof(courseData.mappedHierarchies.hierarchies[0]) != 'undefined' || courseData.mappedHierarchies.hierarchies[0] != null) {
			unmappedSpecs = courseData.mappedHierarchies.hierarchies[0];
		}

		var hierarchies = courseData.mappedHierarchies.hierarchies;
		
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

}

export default MappedEntities;