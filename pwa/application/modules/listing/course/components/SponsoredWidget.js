import PropTypes from 'prop-types'
import React from 'react';
import {sponsoredWidgetFreeCourses,sponsoredWidgetPaidData} from './../config/courseConfig';
import './../assets/sponsoredwidget.css';

function SponsoredWidget(props)
{
	const {courseId} = props;
	if(!(typeof sponsoredWidgetFreeCourses != 'undefined' && typeof sponsoredWidgetFreeCourses[courseId] != 'undefined'))
	{
		return null;
	}
	var cdHtml = [];
	if(typeof sponsoredWidgetPaidData!= 'undefined' && typeof sponsoredWidgetPaidData[sponsoredWidgetFreeCourses[courseId]] != 'undefined')
	{
		var data = sponsoredWidgetPaidData[sponsoredWidgetFreeCourses[courseId]]
		var index = 0;
		for(let i in data)
		{
			var temp = [];
			temp.push(<strong key={index++}>{data[i].name}</strong>);
			temp.push(<p key={index++}>{data[i].description}</p>);
			temp.push(<a key={index++} rel="nofollow" href={data[i].url}>{data[i].ctaName}</a>);
			cdHtml.push(<li key={index++} >{temp}</li>);
		}
	}
	return (
		<section>
			<h2 className="tbSec2">Latest Admission Alert <span className="sponsered-tag">Sponsored</span></h2>
			<div className="_subcontainer adms-alrtList">
				<ul>
					{cdHtml}
				</ul>
			</div>
		</section>
	)
}

export default SponsoredWidget;

SponsoredWidget.propTypes = {
	courseId: PropTypes.any
}