import React,{PureComponent} from 'react';
import CategoryTuple from './../../listing/categoryList/components/CategoryTuple';
import config from './../../../../config/config';
import Anchor from './../../reusable/components/Anchor';
import {isUserLoggedIn} from "../../../utils/commonHelper";
import {event} from './../../reusable/utils/AnalyticsTracking';
import ExamConfig from './../config/ExamConfig';
import PropTypes from 'prop-types';

class ExamInstituteAccepting extends PureComponent{
	constructor()
	{
		super();
	}
	trackEvent = (actionLabel, label) => {
		event({category : 'EXAM'+(this.props.activeSection === 'homepage' ? '' : ' '+this.props.originalSectionName.toUpperCase())+' PAGE', action : actionLabel, label : label});
	};
	trackViewAllEvent = () => {
		let deviceLabel = this.props.deviceType === 'mobile' ? 'MOB' : 'DESK';
		let actionLabel = 'INSTITUTE_ACCEPT_EXAM_VIEWALL_'+(this.props.activeSection === 'homepage' ? 'EXAM' : this.props.originalSectionName.toUpperCase().replace(' ','_'))+'_PAGE_'+deviceLabel;
		let label = isUserLoggedIn() ? 'Logged-In' : 'Non-Logged In';
		this.trackEvent(actionLabel, label);
	};
	render()
	{
   		let isPdfCall     = this.props.deviceType =='mobile'? false: true;
		return (
			<React.Fragment>
				<section>
                <div className="_container">
                  <h2 className="tbSec2">{this.props.instituteAcceptingCount>0?this.props.instituteAcceptingCount:''} Institutes accepting {this.props.examName}</h2>
                  <div className="_subcontainer">
                    {this.props.acceptingWidget!=null && <CategoryTuple srtTrackId={ExamConfig[this.props.deviceType][this.props.activeSection]['trackingKeys']['instAcceptingExamShortlist']} ebTrackid={ExamConfig[this.props.deviceType][this.props.activeSection]['trackingKeys']['instAcceptingExamApplyNow']} recoEbTrackid={ExamConfig[this.props.deviceType][this.props.activeSection]['trackingKeys']['instAcceptingExamRecoApplyNow']} recoShrtTrackid={ExamConfig[this.props.deviceType][this.props.activeSection]['trackingKeys']['instAcceptingExamRecoShortlist']} config={config()} categoryData={this.props.acceptingWidget} aggregateRatingConfig={this.props.acceptingWidget.aggregateRatingConfig} gaTrackingCategory={'EXAM '+this.props.originalSectionName.toUpperCase()+' PAGE'} showInTable={false} pageType = {"ExamPage"} deviceType={this.props.deviceType} isPdfCall={isPdfCall}/>}
                    {(this.props.viewAllUrl!='') ? <div className="button-container"><Anchor onClick={this.trackViewAllEvent.bind(this)} to={this.props.viewAllUrl} className="button button--secondary arrow">View All</Anchor></div>:''}
                  </div>
                </div>
        </section>
			</React.Fragment>
		)
	}
}
ExamInstituteAccepting.defaultProps = {
	deviceType : 'mobile'
};

ExamInstituteAccepting.propTypes = {
	activeSection : PropTypes.string,
	originalSectionName : PropTypes.string,
	deviceType : PropTypes.string,
	examName :PropTypes.string,
	viewAllUrl:PropTypes.string,
	acceptingWidget : PropTypes.object
}

export default ExamInstituteAccepting;
