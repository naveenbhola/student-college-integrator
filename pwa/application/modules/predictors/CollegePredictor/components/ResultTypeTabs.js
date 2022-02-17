import PropTypes from 'prop-types'
import React from 'react';
import Anchor from './../../../reusable/components/Anchor';
import {event} from './../../../reusable/utils/AnalyticsTracking';

const ResultTypeTabs = (props) => {
	return (
		<div className={"fltryt"+(props.deviceType === 'desktop' ? ' desktopOnly' : '')}>
			<div className="table specialization-tab">
				<span className="table-cell">View By: <Anchor onClick={()=>{event({category : props.gaTrackingCategory, action : 'Colleges_Tab', label : 'click'})}} className={(props.activeTab == 'branch') ? 'blackLink' : ''} to={props.currentPageUrl+'&tab=college'}><span id="tabCollege" className={(props.activeTab == 'college') ? "tab active" : "tab" }><i className="college-icon"></i>Colleges</span></Anchor></span>
				<span className="table-cell"><Anchor onClick={()=>{event({category : props.gaTrackingCategory, action : 'Specialization_Tab', label : 'click'})}} className={(props.activeTab == 'college') ? 'blackLink' : ''} to={props.currentPageUrl+'&tab=branch'}><span id="tabSpecialization" className={(props.activeTab == 'branch') ? "tab active" : "tab" }><i className="specialization-icon"></i>Specialization</span></Anchor></span>
			</div>
		</div>
	);
};
export default ResultTypeTabs;

ResultTypeTabs.propTypes = {
  activeTab: PropTypes.string,
  currentPageUrl: PropTypes.string,
  deviceType: PropTypes.string
}