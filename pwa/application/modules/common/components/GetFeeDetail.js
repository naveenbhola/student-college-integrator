import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import { getRequest } from './../../../utils/ApiCalls';
import {getUrlParameter, showToastMsg,setEBCookie,getEBCookie, retainFeeDetailsCTAState, getQueryVariable, removeQueryString,retainRecoCTAState, isUserLoggedIn} from '../../../utils/commonHelper';
import APIConfig from './../../../../config/apiConfig';
import FullPageLayer from './FullPageLayer';
import Loadable from 'react-loadable';

const ResponseForm = Loadable({
  loader: () => import('./../../user/Response/components/ResponseForm'/* webpackChunkName: 'ResponseForm' */),
  loading() {return null},
});

class GetFeeDetail extends Component {

    constructor(props)
    {
        super(props);
        this.state = { openResponseForm: false, enableRegLayer: false};
        this.courseId = 0;
    }

    componentDidMount(){
        if(isUserLoggedIn()){
            this.setState({enableRegLayer: true});
        }
        retainFeeDetailsCTAState(this.props.listingId,this.props.responseCallBack);
        retainRecoCTAState(this.courseId);
        // if(getFDCookie(this.props.listingId)){
        //     this.props.responseCallBack();
        // }
    }

    render(){
        var listingType = "course";
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

        var buttonText = 'Get Fee Details';

      return(
        <React.Fragment>

            <a id={'getFeeDtl_'+this.props.listingId} href="javascript:void(0);" className={this.props.className+' btnYellow fee_lftbtn getFeeDtl_'+this.props.listingId} data-courseid={this.props.listingId} data-trackid={(typeof(this.props.isCallReco) == 'undefined') ? this.props.trackid : this.props.recoEbTrackid}  onClick={this.showResponseForm.bind(this)} title={this.props.title}>{buttonText}</a>
            
            {this.state.enableRegLayer && <ResponseForm openResponseForm={this.state.openResponseForm} clientCourseId={this.props.listingId} listingType={listingType} cta="downloadBrochure" actionType={"downloadBrochure"} trackingKeyId={trackingKey} callBackFunction={this.callBackFeeDetails.bind(this)} onClose={this.closeResponseForm.bind(this)} fromFeeDetails={true}/>}

        </React.Fragment>
      );
    }


    closeResponseForm() {
        removeQueryString();
        this.setState({openResponseForm: false});
    }

    callBackFeeDetails(response){
        if(!getEBCookie(response.listingId) && window.scrollY == 0){
            var ele = document.getElementById("fees");
            ele.scrollIntoView({block: "start", inline: "nearest", behavior: 'smooth'});
        }
        if(response.userId){
            //this.userId = response.userId;
            this.courseId = response.listingId;
            if(!getEBCookie(response.listingId)){
                setEBCookie(response.listingId);
                showToastMsg('Course Brochure Emailed Successfully');
            }
            retainFeeDetailsCTAState(this.courseId,this.props.responseCallBack);
            retainRecoCTAState(this.courseId);
            //this.props.responseCallBack();
            //document.getElementById('brchr_'+this.props.listingId).style.pointerEvents = "";
        }
    }

    showResponseForm() {

        var url = (typeof(window) != 'undefined') ? window.location.href : '';
        if(url.indexOf('fromwhere=MobileVerificationMailer') == '-1'){
            const {clickHandler} = this.props;
            if(typeof clickHandler == "function"){
                this.props.clickHandler();
            }
        }
        if(!getEBCookie(this.props.listingId)){
            ResponseForm.preload().then(() => {
                this.setState({enableRegLayer: true}, () => {this.setState({openResponseForm: true})});
            });
        }
        else{
            retainFeeDetailsCTAState(this.props.listingId,this.props.responseCallBack);
        }
        //document.getElementById('brchr_'+this.props.listingId).style.pointerEvents = "none";
    }

}

GetFeeDetail.defaultProps = {
  buttonText: 'Get Fee Details',
  className: 'rippleefect '
};

export default GetFeeDetail;
