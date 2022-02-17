import PropTypes from 'prop-types'
import React from 'react';
import config from './../../../../config/config';
import AggregateReview from './../../listing/course/components/AggregateReviewWidget';
import Analytics from './../../reusable/utils/AnalyticsTracking';
import { Link } from "react-router-dom";

class RankingPageTupleReviewRating extends React.Component {
  render(){
    let reviewWidget = null;
    if(this.props.reviewData.aggregateReviewData != null){
      reviewWidget = <AggregateReview gaTrackingCategory={this.props.gaTrackingCategory} uniqueKey={'Course_'} isPaid = {false} reviewsCount={this.props.reviewData.aggregateReviewData.totalCount} reviewUrl = {this.props.reviewData.url} aggregateReviewData = {{'aggregateReviewData' : this.props.reviewData.aggregateReviewData,
        'aggregateRatingDisplayOrder' : this.props.aggregateRatingConfig.aggregateRatingDisplayOrder}} showReviewBracket = {true} showAllreviewUrl = {true} config={config()}/>;
    }
    let courseCountLabel = ' Courses';

    return (
        <React.Fragment>
          <div className="clear_float rank_bar">
            {reviewWidget}
          </div>
        </React.Fragment>
    );
  }
  trackEvent (action, label){
    Analytics.event({category : this.props.gaTrackingCategory, action : action, label : label});
  }
}

RankingPageTupleReviewRating.defaultProps = {
  deviceType : 'mobile',
  gaTrackingCategory : 'RANKING_PAGE_MOBILE'
}

export default RankingPageTupleReviewRating;

RankingPageTupleReviewRating.propTypes = {
  admissionUrl: PropTypes.any,
  aggregateRatingConfig: PropTypes.any,
  allCourseUrl: PropTypes.any,
  deviceType: PropTypes.string,
  gaTrackingCategory: PropTypes.string,
  reviewData: PropTypes.any
}