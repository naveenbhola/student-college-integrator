import React from 'react';
import '../../assets/feedbackTags.css';
import CheckBoxGroup from '../feedback/formElements/CheckBoxGroup';
const FeedbackTags = (props) => {
   return <div className="options-box">
      <p className="field-head-text">I found the page information:</p>
      <ul className="inline-list">
         <CheckBoxGroup name='ratingTags' tags={props.ratingStarTags} currentRating={props.currentRating} className={'capsule-selection'} />
      </ul>
   </div>;
};
export default FeedbackTags;