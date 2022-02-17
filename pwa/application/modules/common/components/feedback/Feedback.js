import React from 'react';
import Loadable from 'react-loadable';

import '../../assets/feedback.css';
import feedbackConfig from '../../config/feedbackConfig';
import FeedbackStars from "./FeedbackStars";
import PopupLayer from './../popupLayer';
import UserActionMsgBox from "../UserActionMsgBox";
import {event} from "../../../reusable/utils/AnalyticsTracking";
import {checkIfFeedbackAlreadySubmitted} from "../../../../utils/commonHelper";

const FeedbackForm = Loadable({
   loader: () => import('./FeedbackForm'/* webpackChunkName: 'FeedbackForm' */),
   loading() {return null},
});

class Feedback extends React.Component {
   constructor(props){
      super(props);
      this.state = {
         showFeedbackWidget : true,
         showFeedbackLayer : false,
         rating : 0,
         feedbackSaved : false
      };
   }
   componentDidMount() {
      if(checkIfFeedbackAlreadySubmitted(this.props.pageType, this.props.pageId, this.props.subPageType)){
         this.setState({showFeedbackWidget : false});
      }
   }
   clickStar = (starId, withForm = false) => {
      let self = this;
      if(starId > 0) {
         if(!withForm){
            event({category : this.props.pageType, action : 'Passive_widget_'+this.props.deviceType, label : 'ClickStarRating_'+starId});
         }
         FeedbackForm.preload().then(function(){
            self.setState({showFeedbackLayer : true, rating : starId});
         });
      }
   };
   closeFeedbackForm = (status) => {
      let newState = {showFeedbackLayer : false, rating : 0, feedbackSaved : false, showFeedbackWidget : true};
      if(typeof status != 'undefined' && status === 'success'){
         newState.feedbackSaved = true;
         newState.showFeedbackWidget = false;
      }else{
         event({category : this.props.pageType, action : 'Passive_widget_'+this.props.deviceType, label : 'Close_RatingNDetailsLayer'});
      }
      this.setState(newState);
   };
   closeSuccessPopup = () => {
      this.setState({feedbackSaved : false, showFeedbackLayer : false, rating : 0});
   };

   getWidgetType = () => {
      let feedbackWidgetType = '';
      switch (this.props.feedbackWidgetType) {
         case 'type1':
            feedbackWidgetType = <section className="feedbackType1"><div className="rating-container">
               <h2>Was this page helpful?</h2>
               <FeedbackStars stars={feedbackConfig.ratingStars} clickStar={this.clickStar} />
            </div></section>;
            break;
         case 'type2':
            feedbackWidgetType = <section className="feedbackType2"><div className="rating-container aligned">
               <h2>Was this page helpful?</h2>
               <FeedbackStars stars={feedbackConfig.ratingStars} clickStar={this.clickStar} />
            </div></section>;
            break;
         default:
            feedbackWidgetType = null;
      }
      return feedbackWidgetType;
   };
   render(){
      return (<React.Fragment>
         {this.state.showFeedbackWidget && this.getWidgetType()}
         {<PopupLayer isPopUpOn={this.state.showFeedbackLayer} data={<FeedbackForm rating={this.state.rating} {...this.props} closeFeedbackForm={this.closeFeedbackForm} gaTrackingCategory={this.props.gaTrackingCategory} />} onContentClickClose={false} closePopup={this.closeFeedbackForm} onRef={ref => {this.layer = ref}} heading={'Feedback'} layerCssClass={'large-popup'} />}
         {this.state.feedbackSaved && <PopupLayer isPopUpOn={this.state.feedbackSaved} data={<UserActionMsgBox msgString={'Thank you for your feedback.'} iconFlag={'success'} />} onContentClickClose={false} heading={'Feedback Received'} closePopup={this.closeSuccessPopup} onRef={ref => {this.layer = ref}} />}
      </React.Fragment>);
   }
}
Feedback.defaultProps = {
   deviceType : 'mobile',
   gaTrackingCategory : 'FeedbackWidget',
   feedbackWidgetType : 'type1',
   subPageType : ''
};
export default Feedback;