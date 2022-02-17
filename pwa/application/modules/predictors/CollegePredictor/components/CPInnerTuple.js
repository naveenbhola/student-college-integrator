import PropTypes from 'prop-types'
import React from 'react';
import {getRupeesDisplayableAmount} from './../../../listing/course/utils/listingCommonUtil';
import Anchor from './../../../reusable/components/Anchor';
import ReviewRating from './../../../common/components/ReviewRating';
import Shortlist from "./../../../common/components/Shortlist";
import {addToShortlist} from "../utils/collegePredictorHelper";
import {event} from './../../../reusable/utils/AnalyticsTracking';
import {connect} from 'react-redux';
import {bindActionCreators} from 'redux';
import {storeCourseDataForPreFilled} from './../../../listing/course/actions/CourseDetailAction';

const CPInnerTuple = (props) => {
    
    const addToShortlistWrap = () => {
        event({category : props.gaTrackingCategory, action : 'Shortlist', label : 'click'});
        addToShortlist({courseId : props.tuples.id, srtTrackId : 3207, pageType : 'allCollegePredictorPage'}, {showRecommendations : 0})
    };

    const courseClick = () =>{
    	if(props.deviceType != 'desktop'){
    		var data = {};
	        data.instituteName = (props.collegeName) ? props.collegeName : props.tuples.name;
	        data.courseName    = (props.tuples.courseName) ? props.tuples.courseName : props.tuples.name;
	        data.courseId      = props.tuples.id;
	    	props.storeCourseDataForPreFilled(data);	
    	}
    	event({category : props.gaTrackingCategory, action : 'CLP_Name', label : 'click'})
    }

	return (
		<div className="courseCard" key={props.tuples.id+1}>
			{props.tuples.url && <p><Anchor className="fnt-w6" onClick={()=>{courseClick()}} to={props.tuples.url} link={props.link}>{props.tuples.name}</Anchor></p>}
			{props.tuples.location && <p className="instituteLocation">{props.tuples.location}</p>}
			{props.tuples.courseName && <span>{props.tuples.courseName}</span>}
			{(props.tuples.courseName && ((props.tuples.rating && props.tuples.rating.stars) || props.tuples.fees)) ? <span className="pipe">|</span> : null}
			{(props.tuples.rating && props.tuples.rating.stars) ? <div className="rating-widget"><ReviewRating gaTrackingCategory={props.gaTrackingCategory} stars={props.tuples.rating.stars} count={props.tuples.rating.count} url={props.tuples.rating.url}/></div> : null}
			{(props.tuples.rating && props.tuples.rating.stars && props.tuples.fees) ? <span className="pipe">|</span> : null}
			{(props.tuples.fees) ? <span className="inl-blk">&#8377; {getRupeesDisplayableAmount(props.tuples.fees)}</span> : null}
			{props.exams}
			{props.deviceType === 'desktop' ? <i className='course-shrtlst' id={'shrt_'+props.tuples.id} onClick={() => {addToShortlistWrap()}}> </i> : <Shortlist showRecoLayer={false} className="course-shrtlst" listingId={props.tuples.id} trackid={3213} actionType="CollegePredictor" pageType="mobileCollegePredictorPage" clickHandler={()=>{event({category : props.gaTrackingCategory, action : 'Shortlist', label : 'click'});}} />}
		</div>);
}

CPInnerTuple.propTypes = {
  exams: PropTypes.any,
  link: PropTypes.bool,
  tuples: PropTypes.object
}

function mapDispatchToProps(dispatch){
	return bindActionCreators({storeCourseDataForPreFilled},dispatch);
}
export default connect(null, mapDispatchToProps)(CPInnerTuple);