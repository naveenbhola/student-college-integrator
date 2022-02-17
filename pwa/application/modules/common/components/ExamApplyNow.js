import React, { Component } from 'react';
import './../assets/ExamApplyNow.css';
import Loadable from 'react-loadable';
import {isUserLoggedIn, getCookie, setCookie, getUrlParameter} from '../../../utils/commonHelper';
import PopupLayer from './popupLayer';
import {updateGuideTrackCookie, isGuideDownloaded, storeCTA, isCTAResponseMade} from "../../examPage/utils/examPageHelper";
import Analytics from "../../reusable/utils/AnalyticsTracking";

const ExamResponseForm = Loadable({
  loader: () => import('./../../user/ExamResponse/components/ExamResponseForm'/* webpackChunkName: 'ExamResponseForm' */),
  loading() {return null},
});

class ExamApplyNow extends Component {

    constructor(props){
        super(props);
        this.state = {enableRegLayer: false, openResponseForm: false, trackingKeyId : 0};
        this.cookieName  = 'examGuide';
        this.identifireClass = (this.props.ctaType == 'ApplyNow') ? ' eaply eApplyNow'+this.props.examGroupId : '';
    }

    componentDidMount(){
        if(isUserLoggedIn()){
            this.setState({enableRegLayer: true});
        }else{
            setCookie(this.cookieName, '');
        }
        isGuideDownloaded(this.props.examGroupId, this.cookieName);
    }

    render(){
        let ctaHtml = '';
        switch(this.props.ctaTag){
            case 'button': ctaHtml = <button id={this.props.ctaId} className={this.props.className+this.identifireClass} onClick={this.doRegistration.bind(this)}>{this.props.ctaText}</button>;
            break;
        }
        let popupMsg = <p className='applicationGuide'>The exam guide has been sent to your email id. The email also includes important information such as Institutes Accepting this Exam, Related Articles, Questions & Answers, Similar Exams & more.</p>;
        return(
            <React.Fragment>
                {ctaHtml}   
                {this.state.enableRegLayer && <ExamResponseForm examName={this.props.examName} callBackFunction={this.responseCallBack.bind(this)} openResponseForm={this.state.openResponseForm} examGroupId={this.props.examGroupId} cta={this.props.cta} actionType={this.props.actionType} trackingKeyId={this.state.trackingKeyId}  onClose={this.closeRegistration.bind(this)} clickId={this.props.ctaId} /> }
                {<PopupLayer onContentClickClose={false} heading={this.props.popupHeading} onRef={ref => (this.PopupLayer = ref)} data={popupMsg}/>}
            </React.Fragment>
      );
    }

    trackEvent = (actionLabel, label)=>{
        if(!this.props.gaCategory)
            return;
        const categoryType = this.props.gaCategory;
        Analytics.event({category : categoryType, action : actionLabel, label : label});
    };

    doRegistration(){
        this.trackEvent('Exam_'+this.props.gaWidget+'_'+this.props.actionType+'_Mobile', this.props.gaLabel);
        if(isUserLoggedIn() && isCTAResponseMade(this.props.examGroupId, this.props.actionType)){
            this.callBackAction();            
        }else{
            ExamResponseForm.preload().then(()=>{
                this.setState({enableRegLayer: true});
                this.callRegLayer();
            });
        }
    }

    manageTrackingKey=()=>{
        let sectionName = getUrlParameter('sectionName');
        let fromwhere   = getUrlParameter('fromwhere');
        let clickId     = getUrlParameter('clickId');
        let trackingKeyId = 0;
        if(clickId && (sectionName !='' || fromwhere == 'response') && typeof this.props.ampCTATrackingKeys != 'undefined' && this.props.ampCTATrackingKeys){
            trackingKeyId = this.props.ampCTATrackingKeys[clickId];
        }
        return (trackingKeyId>0) ? trackingKeyId : this.props.trackingKeyId;
    }

    successMsg(){
        this.PopupLayer.open();
    }

    callRegLayer(){
        this.setState({...this.state, openResponseForm : true, trackingKeyId: this.manageTrackingKey()});
    }

    closeRegistration(){
        this.setState({openResponseForm : false, trackingKeyId : 0});
    }

    responseCallBack(response){
        if(response.userId && response.status == 'SUCCESS'){
            this.closeRegistration();
            this.callBackAction();            
            storeCTA(this.props.examGroupId, this.props.actionType);
        }
    }

    callBackAction=()=>{
        if(typeof this.props.callBackFunction == 'function' && this.props.callBackFunction){
            this.props.callBackFunction();
        }else{
            this.successMsg();
            updateGuideTrackCookie(this.props.examGroupId, this.cookieName);
            isGuideDownloaded(this.props.examGroupId, this.cookieName);
        }
    }
}

ExamApplyNow.defaultProps = {
    ctaType : 'ApplyNow',
    gaLabel : 'click',
    gaWidget : 'CTA',
    cta : 'examDownloadGuide',
    actionType : 'downloadGuide',
    ctaTag : 'button',
    className : 'button button--orange rippleefect',
    ctaText : 'Apply Now',
    popupHeading : 'Application Guide' // show on success layer
}
export default ExamApplyNow;
