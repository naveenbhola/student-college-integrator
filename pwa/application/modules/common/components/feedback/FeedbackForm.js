import React from 'react';
import ReactForm from 'formsy-react';
import {withRouter} from 'react-router-dom';
import '../../assets/feedbackForm.css';
import feedbackConfig from "../../config/feedbackConfig";
import FeedbackStars from "./FeedbackStars";
import FeedbackTags from "./FeedbackTags";
import TextArea from "./formElements/TextArea";
import APIConfig from './../../../../../config/apiConfig';
import {postRequestAPIs} from "../../../../utils/ApiCalls";
import {getCookie, getPageCanonicalUrl, setCookie, setFeedbackWidgetFlagCookie} from "../../../../utils/commonHelper";
import config from "../../../../../config/config";
import {showRegistrationFormWrapper} from "../../../../utils/regnHelper";
import {event} from "../../../reusable/utils/AnalyticsTracking";

class FeedbackForm extends React.Component {
    constructor(props){
        super(props);
        this.state = {
            rating : this.props.rating,
            canSubmit : true,
            errorMsg : '',
            listBaseClass : ''
        };
    }
    setClassName = (starId) => {
      if(starId >= 4 ){
        this.setState({listBaseClass : 'form-container shortLength', rating : starId})
      }else{
        this.setState({listBaseClass : 'form-container fullLength' , rating : starId})
      }
    }
    componentDidMount() {
        this.setClassName(this.props.rating);
    }

   clickStar = (starId) => {
      //this.setState({rating : starId});
      this.setClassName(starId);
   };
   // validateForm = (formValues) => {
   //     return;
   //     let errMsg = '';
   //     if(!(formValues.rating > 0)){
   //         errMsg = 'Invalid Rating';
   //     }else if(formValues.ratingText !== '' && formValues.ratingText.length < 20){
   //         errMsg = 'Please write at least 20 characters.';
   //     }else if(formValues.ratingText.length > 200){
   //         errMsg = 'Max limit reached.';
   //     }
   //     if(errMsg !== ''){
   //         this.setState({errorMsg : errMsg, canSubmit : false});
   //     }else{
   //         this.setState({errorMsg : '', canSubmit : true});
   //     }
   // };
   submitFeedbackForm = (values) => {
       this.setState({canSubmit : false});
       let referrer = config().SHIKSHA_HOME+this.props.location.pathname+this.props.location.search;
       let feedbackData = {};
       feedbackData['pageId'] = this.props.pageId;
       feedbackData['pageType'] = this.props.pageType;
       feedbackData['stars'] = this.state.rating;
       //feedbackData['sessionId'] = getCookie('visitorSessionId');
       feedbackData['comment'] = values['ratingText'];
       feedbackData['tags'] = values['ratingTags'];
       feedbackData['url'] = getPageCanonicalUrl();
       feedbackData['deviceType'] = this.props.deviceType;
       event({category : this.props.pageType, action : 'Passive_widget_'+this.props.deviceType, label : this.state.rating+'_star_RatingNDetailsLayer'});
       if(values['ratingTags'].length > 0){
           event({category : this.props.pageType, action : 'Passive_widget_'+this.props.deviceType, label : values['ratingTags'].join('_')});
       }
       event({category : this.props.pageType, action : 'Passive_widget_'+this.props.deviceType, label : 'Submit_RatingNDetailsLayer'});
       postRequestAPIs(APIConfig.GET_SAVE_FEEDBACK_URL, feedbackData).then(res => {
           if(res.status === 200){
               event({category : this.props.pageType, action : 'Passive_widget_'+this.props.deviceType, label : 'SuccessfulSubmission'});
               setFeedbackWidgetFlagCookie(this.props.pageType, this.props.pageId, this.props.subPageType);
               if(getCookie('user') === ''){
                   setCookie('feedback', '1',1);
                   if(this.props.deviceType === 'desktop'){
                       window.reactCallBackSaveFeedback = this.saveFeedbackAfterRegn;
                       let regData = {'trackingKeyId': 3633, 'callbackFunction': 'callBackSaveFeedback', 'callbackFunctionParams':{'redirectUrl' : referrer, 'feedbackData' : feedbackData}};
                       showRegistrationFormWrapper(regData);
                   }else{
                       window.location.href = config().SHIKSHA_HOME + '/muser5/UserActivityAMP/getRegistrationAmpPage?fromwhere=feedback&referer=' + Buffer.from(referrer).toString('base64') + '&trackData=' + Buffer.from(JSON.stringify(feedbackData)).toString('base64');
                   }
                   this.props.closeFeedbackForm();
               }else{
                   this.props.closeFeedbackForm('success');
               }
               this.setWidgetVisibility();
           }
       }).catch((err)=> console.log('Error::', err));
   };
   setWidgetVisibility = () => {};
   saveFeedbackAfterRegn = (response, data) => {
       if(getCookie('feedback') === '1'){
           if(!(data && data.feedbackData)){
               data = {};
               data.feedbackData = {};
           }
           data.feedbackData.updateUserId = true;
           postRequestAPIs(APIConfig.GET_SAVE_FEEDBACK_URL, data.feedbackData).then(res => {
               if(res.status === 200){
                   setCookie('feedback', '', -1);
                   this.props.closeFeedbackForm('success');
               }
           }).catch((err)=> console.log('Error::', err));
       }
   };
   render(){
       return (
          <ReactForm onValidSubmit={this.submitFeedbackForm.bind(this)}>
          <div className={this.state.listBaseClass}>
             <div className="rating-container">
                <h2>Was this page helpful?</h2>
                 <FeedbackStars rating={this.state.rating} withForm={true} stars={feedbackConfig.ratingStars} clickStar={this.clickStar} />
             </div>
             <div className="information-section">
                <div className="feedback-text">{feedbackConfig.ratingStars[this.state.rating].label}</div>
                <FeedbackTags ratingStarTags={feedbackConfig.ratingStarTags[this.state.rating]} currentRating={this.state.rating} />
             </div>
             <div className="suggetion-section">
                <div className="suggetion-box">
                   <p className="field-head-text">Any suggestions or inputs for Shiksha.</p>
                   <TextArea className="inputbox" rows={5} cols={40} maxLength={200} name="ratingText" placeholder={'Write here (Optional)'} rating={this.state.rating}> </TextArea>
                </div>
                <div className="btn-container">
                    {this.state.errorMsg !== '' && <span className='err-msg'>{this.state.errorMsg}</span>}
                   <button type='submit' disabled={!this.state.canSubmit} className="button button--orange">Submit</button>
                </div>
             </div>
          </div>
          </ReactForm>
      );
   }
}
FeedbackForm.defaultProps = {
    rating : 0,
    gaTrackingCategory : 'FeedbackWidget',
    deviceType : 'mobile'
};
export default withRouter(FeedbackForm);