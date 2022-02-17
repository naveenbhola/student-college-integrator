import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import { getRequest } from './../../../utils/ApiCalls';
import {getUrlParameter, showToastMsg, setEBCookie, retainRecoCTAState, getQueryVariable, removeQueryString, isUserLoggedIn,CTAResponseHTML} from '../../../utils/commonHelper';
import APIConfig from './../../../../config/apiConfig';
import FullPageLayer from './FullPageLayer';
import PopupLayer from './popupLayer';


import Loadable from 'react-loadable';
const CategoryTuple = Loadable({
  loader: () => import('./../../listing/categoryList/components/CategoryTuple'/* webpackChunkName: 'CategoryTuple' */),
  loading() {return null},
});
const ResponseForm = Loadable({
  loader: () => import('./../../user/Response/components/ResponseForm'/* webpackChunkName: 'ResponseForm' */),
  loading() {return null},
});

class DownloadEBrochure extends Component {

    constructor(props)
    {
        super(props);
        this.state = {
            enableRegLayer: false,
            openResponseForm: false,
            layerOpen :false,
            showMailerMessage:false,
            ResponseData:''
        };
        this.courseId = 0;
        this.isCoursePaid = false;
    }

    componentDidMount(){
        if(isUserLoggedIn()){
            this.setState({enableRegLayer: true});
        }

        if(this.props.page == "coursePage"){
            retainRecoCTAState(this.props.listingId);
        }else{
            let courseId = (this.courseId>0) ? this.courseId : this.props.listingId;
            retainRecoCTAState(courseId);
        }
    }

    render(){
        var listingType = "course";
        if(this.props.page === "institute"){
            listingType = "institute";
        }
        else if(this.props.page === "university"){
            listingType = "university";
        }
        var trackingKey = (typeof(this.props.isCallReco) == 'undefined') ? this.props.trackid : this.props.recoEbTrackid;
        var url         = (typeof(window) != 'undefined') ? window.location.href : '';

        if(url.indexOf('fromwhere=MobileVerificationMailer') != '-1'){
            var utmCampaign = getQueryVariable('utm_medium',url);
            if(utmCampaign == 'Email'){
                trackingKey = 1651;
            }else{
                trackingKey = 1653;
            }
        }

        //Mailer responses tracking keys only
        var actionType = getUrlParameter('action');
        var actionTypeList = ['db'];
        if(actionType !='' && actionTypeList.indexOf(actionType) != '-1'){
            var mailerType = getUrlParameter('mailer');
            switch (actionType) {
                case 'db':
                        switch (mailerType) {
                            case 'ViewedResponseMailer':
                                trackingKey = 1334;
                                break;
                            case 'DetailedRecommendationMailer':
                                trackingKey = 1330;
                                break;
                        }
                        break;
            }

        }
        var buttonText = 'Apply Now';
        if(this.props.buttonText !='Request Brochure'){
            buttonText = this.props.buttonText;
        }
        
        var actionType='';
        if(this.props.actionType){
            actionType = this.props.actionType;
        }else{
            actionType="downloadBrochure";
        }
        var ctaId = 'brchr_'+this.props.listingId;
        if(this.props.uniqueId){
            ctaId = this.props.uniqueId;
        }
        var ctaName = 'downloadBrochure';
        if(this.props.ctaName){
            ctaName = this.props.ctaName;
        }
      return(
        <React.Fragment>

            <button type="button" name="button" id={ctaId}  className={this.props.className+' button button--orange tupleBrochureButton brchr_'+this.props.listingId} data-courseid={this.props.listingId} data-trackid={(typeof(this.props.isCallReco) == 'undefined') ? this.props.trackid : this.props.recoEbTrackid}  onClick={this.showResponseForm.bind(this)}>{buttonText}</button>
            
            {this.state.enableRegLayer && <ResponseForm openResponseForm={this.state.openResponseForm} clientCourseId={this.props.listingId} listingType={listingType} cta={ctaName} actionType={actionType} trackingKeyId={trackingKey} callBackFunction={this.callBackDowloadBrochure.bind(this)} onClose={this.closeResponseForm.bind(this)} /> }

            {this.state.enableRegLayer && <FullPageLayer data={this.getTupleHTML()} heading={'Brochure Mailed to You'}  subHeading={'true'} onClose={this.closeRecoLayer.bind(this)} isOpen={this.state.layerOpen} showMailerMessage={this.state.showMailerMessage}/>}

            {<PopupLayer onRef={ref => (this.getLayer = ref)} data={this.state.ResponseData} heading={this.props.heading} onContentClickClose={false}/>}
        </React.Fragment>
      );
    }

    getCTAHtml(){
        let html = CTAResponseHTML(this.isCoursePaid,this.props.listingName,this.props.actionType,this.props.pdfUrl);
        this.setState({ResponseData: html});
    }

    closeRecoLayer(){
        this.setState({layerOpen : false});
    }

    closeResponseForm() {
        removeQueryString();
        this.setState({openResponseForm: false});
    }

    callBackDowloadBrochure(response){
        if(response.userId){
            this.courseId = response.listingId;
            this.isCoursePaid = response.isPaid;
            setEBCookie(response.listingId);
            retainRecoCTAState(this.courseId);
            if(document.getElementById('getFeeDtl_'+response.listingId)){
                document.getElementById('getFeeDtl_'+response.listingId).click();
            }
            if(this.props.actionType=='Request_Call_Back' || this.props.actionType=='Get_Free_Counselling' || this.props.actionType=='Get_Admission_Details' || this.props.actionType == 'Download_CourseList' || this.props.actionType == 'Get_Scholarship_Details' || this.props.actionType == 'Download_Top_Reviews' || this.props.actionType == 'Download_Top_Questions' || this.props.actionType =='Download_Cutoff_Details'){
                if(this.props.pdfUrl){
                    window.open(this.props.pdfUrl, "_blank");

                }
                this.getCTAHtml()
                this.getLayer.open();
            }else{
                showToastMsg('Course Brochure Emailed Successfully');    
            }

            if(this.props.showRecoLayer && typeof(this.props.isCallReco) == 'undefined'){
                this.getRecoData();
            }
        }
    }

    getRecoData(){
        getRequest(APIConfig.GET_RECOMMENDATION+'?courseId='+this.courseId).then((response) => {
            if(response.data.data && response.data.data.categoryInstituteTuple){
                this.setState({recoData:response.data.data,showMailerMessage:response.data.data.showMailerMessage, openResponseForm: false, layerOpen:true});
            }
        }).catch(function(err){});
    }

    showResponseForm(e) {
        let self = this;
        var url = (typeof(window) != 'undefined') ? window.location.href : '';
        if(url.indexOf('fromwhere=MobileVerificationMailer') == '-1'){
            const {clickHandler} = this.props;
            if(typeof clickHandler == "function"){
                this.props.clickHandler();
            }
        }

        this.showLoader();        
        ResponseForm.preload().then(function(){
            self.setState({...self.state, enableRegLayer: true});
            self.callRegLayer(self);
        });
    }

    callRegLayer(self){
        self.setState({...self.state, openResponseForm : true});
        self.hideLoader();
    }

    getTupleHTML(){
        if(!this.state.recoData || !this.state.recoData.categoryInstituteTuple || typeof this.state.recoData.categoryInstituteTuple == 'undefined'){ 
            return null; 
        }
        return(<CategoryTuple aggregateRatingConfig = {this.state.recoData.aggregateRatingConfig} isImageLazyLoad={false} recoData={this.state.recoData.categoryInstituteTuple} isCallReco={false} recoEbTrackid={(typeof(this.props.recoEbTrackid) !='undefined' && this.props.recoEbTrackid) ? this.props.recoEbTrackid : ''} recoCMPTrackid={(typeof(this.props.recoCMPTrackid) !='undefined' && this.props.recoCMPTrackid) ? this.props.recoCMPTrackid : ''} recoShrtTrackid={(typeof(this.props.recoShrtTrackid) !='undefined' && this.props.recoShrtTrackid) ? this.props.recoShrtTrackid : ''}/>);
    }

    showLoader(){
        if(document.getElementById('shiksha-loader')){
            document.getElementById('shiksha-loader').innerHTML = '<div id="common-loader" class="loader-col-msearch"><div class="three-quarters-loader-msearch">Loading…</div></div>';
        }
    }

    hideLoader(){
        if(document.getElementById('shiksha-loader')){
            document.getElementById('shiksha-loader').innerHTML = '';
        }
    }
}

DownloadEBrochure.defaultProps = {
  buttonText: 'Request Brochure',
  className: 'ctp-btn ctpBro-btn rippleefect',
  showRecoLayer : true
};

export default DownloadEBrochure;
