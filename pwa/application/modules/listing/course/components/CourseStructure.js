import PropTypes from 'prop-types'
import React from 'react';
import './../assets/courseStructure.css';
import './../assets/courseCommon.css';
import FullPageLayer from './../../../common/components/FullPageLayer';
import { makeURLAsHyperlink } from './../../../../utils/urlUtility';

class CourseStructure extends React.Component
{
	constructor(props)
	{
		super(props);
		this.state = {
			courseStructure: {},
			layerHeading:'Course Structure',
			layerOpen : false
		}
		this.closeLayer = this.closeLayer.bind(this);

	}

	formatStructureData(viewType='page'){
		var structureData = this.props.courseStructure.periodWiseCourses;
		var structureGroup = this.props.courseStructure.period;
		var html = [];
		for(let index in structureData)
		{
			if(structureGroup != 'program')
			{
				html.push(<h3 key={'h_'+index}>{structureGroup+' ' + (parseInt(index)) +' Courses'}</h3>);
			}
			var html1 = structureData[index].map(function (course, key) {
				if(structureGroup == 'program' && key > 6 && viewType == 'page')
					return;
				return <li key={'course_'+key+index} dangerouslySetInnerHTML={{ __html : makeURLAsHyperlink(course)}}></li>
			})
			html.push(<ul key={'ul_'+index} className="nrml-list">{html1}</ul>);
			if(viewType == 'page' && index == 1)
				break;
		}
		return html;
	}

	openCourseStructureLayer() {
		this.setState({layerOpen : true});
	}
	closeLayer()
	{
		this.setState({layerOpen : false});
	}

	render()
	{
		var structureData = this.props.courseStructure.periodWiseCourses;
		var structureHtml = this.formatStructureData('page');
		var layerHtml = this.formatStructureData('layer');
		var structureCount = Object.keys(this.props.courseStructure.periodWiseCourses).length;
		var ViewAllInFirstCourseStructure = false;
		for(let index in structureData)
		{	var courseCountInStructure = 0;
			structureData[index].map(function () {
				courseCountInStructure++;
				if(courseCountInStructure >= 8){
					ViewAllInFirstCourseStructure = true;
				}

			});
		}
		return (
			<section className='courseBnr listingTuple' id="structure">
				<div className='_container'>
					<h2 className='tbSec2'>Course Structure</h2>
					<div className='_subcontainer'>
						<div>{structureHtml}
						</div>
						<FullPageLayer data={layerHtml} heading={this.state.layerHeading} onClose={this.closeLayer} isOpen={this.state.layerOpen}/>
						{(structureCount > 1 || ViewAllInFirstCourseStructure) && <div className='button-container'>
							<button type="button" className='button button--secondary arrow' onClick={this.openCourseStructureLayer.bind(this)}>View Complete Course Structure </button>
						</div>}
					</div>
				</div>
			</section>
		)
	}
}

export default CourseStructure;

CourseStructure.propTypes = {
  courseStructure: PropTypes.any
}