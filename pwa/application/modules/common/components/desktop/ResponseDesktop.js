import React  from 'react';
import {showResponseFormWrapper} from '../../../../utils/regnHelper';
import Analytics from "../../../reusable/utils/AnalyticsTracking";
import config from './../../../../../config/config';

import {isUserLoggedIn} from './../../../../utils/commonHelper';
import {storeCTA, isCTAResponseMade} from "./../../../examPage/utils/examPageHelper";

class ResponseDesktop extends React.Component {
  constructor(props){
    super(props);
    this.classIdentifier = '';
    this.doRegistration = this.doRegistration.bind(this);
  }

  doRegistration = () => {
      this.trackEvent('Exam_'+this.props.gaWidget+'_'+this.props.actionType+'_Desktop', this.props.gaLabel);
      if(isUserLoggedIn() && isCTAResponseMade(this.props.listingId, this.props.actionType) && (typeof this.props.formData.callbackFunctionParams.redirectUrl != 'undefined' && this.props.formData.callbackFunctionParams.redirectUrl)){
          redirectSamplePaperPage({status : 'SUCCESS'}, this.props.formData.callbackFunctionParams);
      }else if(isUserLoggedIn() && isCTAResponseMade(this.props.listingId, this.props.actionType)){
          callDownloadGuide({status : 'SUCCESS'}, this.props.formData.callbackFunctionParams); 
      }else{
          showResponseFormWrapper(this.props.listingId, this.props.actionType, this.props.listingType, this.props.formData);
      }
  };

  trackEvent = (actionLabel, label)=>{
    if(!this.props.gaCategory)
      return;
    const categoryType = this.props.gaCategory;
    Analytics.event({category : categoryType, action : actionLabel, label : label});
  };

  render() {
    let ctaHtml = '';
    if(this.props.ctaType === 'guideDownload'){
      this.classIdentifier = 'eApplyNow'+this.props.listingId
    }
    switch(this.props.ctaTag){
      case 'button':
        ctaHtml = <button id={this.props.ctaId} className={this.props.className+' '+this.classIdentifier} onClick={this.doRegistration}>{this.props.ctaText}</button>;
        break;
      case 'image':
        ctaHtml = <img data-original={config().IMG_DOMAIN+'/pwa/public/images/desktop/inlineRegistration.jpg'} id={this.props.ctaId} className={this.classIdentifier +' lazy'} onClick={this.doRegistration}/>;
        break;
    }
    return (
        ctaHtml
    );
  }
}
ResponseDesktop.defaultProps = {
  ctaTag : 'button',
  className : 'btnYellow',
  ctaText : 'Get Updates',
  ctaType : 'other',
  gaLabel : 'click',
  gaWidget : 'CTA'
};
export default ResponseDesktop;
