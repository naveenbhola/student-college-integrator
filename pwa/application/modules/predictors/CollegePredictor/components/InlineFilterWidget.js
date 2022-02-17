import React from 'react';
import './../assets/InlineFilterWidget.css';
import Anchor from "../../../reusable/components/Anchor";
import {event} from "../../../reusable/utils/AnalyticsTracking";

const InlineFilterWidget = (props) => {
	let itemList = [], link = '/';
	const getShortName = (filterType) => {
		return props.aliasMapping[filterType];
	};
	if(props.data && props.data.length > 0) {
		for (let index = 0; index < props.data.length; index++){
			if(index === 10) {
				break;
			}
			link = props.currentPageUrl + '&' + getShortName(props.data[index]['filterType']) + '[]=' + props.data[index]['id'];
			itemList.push(<li key={props.heading+'-ocf-'+props.data[index]['id']}><span className="filter-capsule"><Anchor onClick={()=>{event({category : props.gaTrackingCategory, action : 'OCF_'+props.heading, label : 'click'})}} className='blackLink' to={link}>{props.data[index]['name']}</Anchor></span></li>);
		}
	}
	return (
		<div className="filter-widget">
		   <p><strong>Filter by {props.heading}</strong></p>
		   <div className="filter-items">
			  <span className="gredient-corner left"> </span>
			  <span className="gredient-corner right"> </span>
			  <ul className="filter-item-list">{itemList}</ul>
		   </div>
		</div>
	);

};
InlineFilterWidget.defaultProps = {
	data : [],
	heading : ''
};
export default InlineFilterWidget;
