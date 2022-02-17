import React from 'react';
import '../../assets/feedbackStars.css';
import TextBox from './formElements/TextBox';
const FeedbackStars = (props) => {
   let stars = [];
   let starClass = 'blank';
   let filledStars = 0;
   if(props.rating > 0){
      filledStars = props.rating;
   }
   let index = 0;
   for (let i in props.stars){
      if(props.stars.hasOwnProperty(i)){
         if(index < filledStars){
            starClass = 'fill';
         }else{
            starClass = 'blank';
         }
         stars.push(<span key={'star-'+i} onClick={()=>{props.clickStar(i, props.withForm)}}><i className={"rating-icon "+starClass}> </i></span>);
         index++;
      }

   }
   return <span className="rating-icon-container">
      {stars}
      {props.withForm && <TextBox name='rating' value={props.rating} className='hide'/>}
   </span>;
};
FeedbackStars.defaultProps = {
   stars : [],
   rating : 0,
   withForm : false
};
export default FeedbackStars;