import React from 'react';
import './../assets/ReviewRating.css';
import {event} from "../../reusable/utils/AnalyticsTracking";

const ReviewRating = (props) => {
	if(props.stars == ''){
        return null;
    }
    let percentage = props.stars*20;
    return(
        <div className="clg-col single-col">
            <span className="rating-block rvw-lyr">{props.stars+' '}
                <i className="empty_stars starBg rvw-lyr">
                    <i style={{width: percentage + '%'}} className="full_starts starBg rvw-lyr"></i>
                </i>
            </span>
            {(props.count && props.url) ? <a onClick={()=>{event({category : props.gaTrackingCategory, action : 'Reviews', label : 'click'})}} className="view_rvws" href={props.url}> {' ('+props.count+')'}</a> : null}
        </div>
    );
}
export default ReviewRating;