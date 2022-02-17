import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import APIConfig from './../../../../config/apiConfig';
import { getRequest,postRequest } from './../../../utils/ApiCalls';
import {setCookie, getCookie,showToastMsg,updateNotification,getQueryVariable,removeQueryString, isUserLoggedIn} from '../../../utils/commonHelper';
import FullPageLayer from './FullPageLayer';

import Loadable from 'react-loadable';
const CategoryTuple = Loadable({
  loader: () => import('./../../listing/categoryList/components/CategoryTuple'/* webpackChunkName: 'CategoryTuple' */),
  loading() {return null},
});
const ResponseForm = Loadable({
  loader: () => import('./../../user/Response/components/ResponseForm'/* webpackChunkName: 'ResponseForm' */),
  loading() {return null},
});

class Shortlist extends Component {

    constructor(props)
    {
        super(props);
        this.state = {enableRegLayer: false, openResponseForm: false, layerOpen :false,showMailerMessage:false};
        this.action = this.action.bind(this);
        this.userId = 0;
        this.courseId = 0;
    }

    componentDidMount(){
        if(isUserLoggedIn()){
            this.setState({enableRegLayer: true});
        }

        if(this.props.page == "coursePage"){
            this.retainShortlistCTA(this.props.listingId);    
        }
        else this.retainShortlistCTA(this.courseId);    
    }

    render(){
        var listingType = "course"; 
        if(this.props.page == "institute"){
            listingType = "institute";
        }
        else if(this.props.page == "university"){
            listingType = "university";
        }
        else{
            this.courseId = this.props.listingId;
        }

        var trackingKey = (typeof(this.props.isCallReco) == 'undefined') ? this.props.trackid : this.props.recoShrtTrackid;
        var url         = (typeof(window) != 'undefined') ? window.location.href : '';

        if(url.indexOf('fromwhere=MobileVerificationMailer') != '-1'){
            var utmCampaign = getQueryVariable('utm_medium',url);
            if(utmCampaign == 'Email'){
                trackingKey = 1643;
            }else{
                trackingKey = 1645;
            }
        }
       
      return(
        <React.Fragment>
            
            {(this.props.isButton) ? <button type="button" name="button" data-type="button" id={'shrtBtn_'+this.props.listingId} className={this.props.className+' ctp-btn ctpComp-btn button button--secondary rippleefect tupleShortlistButton shrtBtn_'+this.props.listingId} data-courseid={this.props.listingId} data-trackid={(typeof(this.props.isCallReco) == 'undefined') ? this.props.trackid : this.props.recoShrtTrackid} onClick={this.action}>Shortlist</button> : <i id={'shrt_'+this.props.listingId} className={this.props.className+' tupleShortlistButton shrt_'+this.props.listingId} data-courseid={this.props.listingId} data-trackid={(typeof(this.props.isCallReco) == 'undefined') ? this.props.trackid : this.props.recoShrtTrackid} onClick={this.action}></i> }

            {this.state.enableRegLayer && <ResponseForm openResponseForm={this.state.openResponseForm} clientCourseId={this.props.listingId} listingType={listingType} cta="shortlist" actionType={(typeof(this.props.isCallReco) == 'undefined') ? this.props.actionType : this.props.recoActionType} trackingKeyId={trackingKey} callBackFunction={this.callBackShortlist.bind(this)} onClose={this.closeResponseForm.bind(this)} />}

            {this.state.enableRegLayer && <FullPageLayer data={this.getTupleHTML()} heading={'Course Shortlisted Successfully'} subHeading={'true'} onClose={this.closeRecoLayer.bind(this)} isOpen={this.state.layerOpen} showMailerMessage={this.state.showMailerMessage} />}

        </React.Fragment>
      );
    }

    action(e) {
        var url = (typeof(window) != 'undefined') ? window.location.href : '';
        if(url.indexOf('fromwhere=MobileVerificationMailer') != '-1'){
            this.showResponseForm();
        } else {
            var ele = e.currentTarget;
            var ampCTA = (getCookie('fromAMPCTA')) ? atob(getCookie('fromAMPCTA')).split('::') : [];
            if((typeof(ele) !='undefined' && ele.classList.contains('active')) || (ele.text == 'Shortlisted') || (ampCTA.length>0 && ampCTA[0] == 'shortlist' && ampCTA[1])){
                    this.callShortlistAPI(this.userId,this.courseId);
            }else{
                this.showResponseForm();
            }
        }
    }

    showResponseForm() {
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

    closeResponseForm() {
        removeQueryString();
        this.setState({openResponseForm: false});
    }

    closeRecoLayer(){
        this.setState({layerOpen : false});
    }

    callBackShortlist(response){
        if(response.userId){
            if(this.props.page == "coursePage"){
                this.courseId = this.props.listingId;
            }
            else {
                this.courseId = response.listingId;
            }
            this.callShortlistAPI(response.userId,this.courseId);
            this.userId = response.userId;

        }
    }

    callShortlistAPI(userId,courseId){
        var trackingKeyId = (typeof(this.props.isCallReco) == 'undefined' && this.props.trackid) ? this.props.trackid : this.props.recoShrtTrackid;

        var ampCTA = (getCookie('fromAMPCTA')) ? atob(getCookie('fromAMPCTA')).split('::') : [];
        if(ampCTA.length>0 && ampCTA[0] == 'shortlist' && ampCTA[1]){
            trackingKeyId = ampCTA[2];
        }

        const pageType = (typeof(this.props.isCallReco) == 'undefined' && this.props.pageType) ? this.props.pageType : this.props.recoPageType;
        
        const sessionId = (this.props.sessionId) ? this.props.sessionId : 0;
        const visitorSessionid = (this.props.visitorSessionid) ? this.props.visitorSessionid : getCookie('visitorSessionId');

        var postData = 'courseId='+courseId+'&userId=0&trackingKeyId='+trackingKeyId+'&pageType='+pageType+'&sessionId='+sessionId+'&visitorSessionid='+visitorSessionid;

        const axiosConfig = {
              headers: {'Content-Type': 'application/x-www-form-urlencoded'},
              withCredentials: true
          };
        postRequest(APIConfig.POST_CHECKSHORTLIST,postData,'',axiosConfig).then((response) => {
            if(response.data.data){
                var res = response.data.data;
                var courseArr = (res.courses.length) ? res.courses : [];
                setCookie('mob_shortlist_Count',courseArr.length,0,'/');
                this.shortlistMsg(res.status);
                this.shortlistCourse(res.status,courseId);
                if(typeof (WebView) !='undefined'){
                    let shortlistCount = courseArr.length;
                  WebView.getShortListCount(shortlistCount.toString());
                }

                this.setShortlistedCookie(courseArr);
                if(this.props.showRecoLayer && typeof(this.props.isCallReco) == 'undefined' && res.status == 'shortlisted'){
                    this.getRecoData();
                }
                updateNotification();
                setCookie('fromAMPCTA', '', 0, 'seconds');
            }
        }).catch(function(err){});
    }

    shortlistCourse(actionType, courseId){
        var e = document.getElementsByClassName('shrt_'+courseId);  // Find the elements
        if(actionType == 'shortlisted') {
            for(var i = 0; i < e.length; i++){
                e[i].classList.add('active');
            }    
        }else{
            for(var i = 0; i < e.length; i++){
                e[i].classList.remove('active');
            }
        }

        var e = document.getElementsByClassName('shrtBtn_'+courseId);  // Find the elements
        if(e.length){
            if(actionType == 'shortlisted') {
                for(var i = 0; i < e.length; i++){
                    e[i].text = 'Shortlisted';
                }    
            }else{
                for(var i = 0; i < e.length; i++){
                    e[i].text = 'Shortlist';
                }
            }
        }
    }

    shortlistMsg(actionType){
        var msg = (actionType == 'shortlisted') ? 'Course shortlisted successfully' : 'Course removed from shortlist' ;
        showToastMsg(msg);
    }

    getRecoData(){
        getRequest(APIConfig.GET_RECOMMENDATION+'?courseId='+this.courseId).then((response) => {
            if(response.data.data && response.data.data.categoryInstituteTuple){
                this.setState({recoData:response.data.data,showMailerMessage:response.data.data.showMailerMessage, openResponseForm: false, layerOpen:true});
            }
        }).catch(function(err){});
    }

    getTupleHTML(){
        if(!this.state.recoData || !this.state.recoData.categoryInstituteTuple || typeof this.state.recoData.categoryInstituteTuple == 'undefined'){ 
            return null; 
        }
        return(<CategoryTuple aggregateRatingConfig={this.state.recoData.aggregateRatingConfig} isImageLazyLoad={false} recoData={this.state.recoData.categoryInstituteTuple} isCallReco={false} recoEbTrackid={(typeof(this.props.recoEbTrackid) !='undefined' && this.props.recoEbTrackid) ? this.props.recoEbTrackid : ''} recoCMPTrackid={(typeof(this.props.recoCMPTrackid) !='undefined' && this.props.recoCMPTrackid) ? this.props.recoCMPTrackid : ''} recoShrtTrackid={(typeof(this.props.recoShrtTrackid) !='undefined' && this.props.recoShrtTrackid) ? this.props.recoShrtTrackid : ''}/>);
    }

    setShortlistedCookie(courseArr){

        setCookie('short_courses',btoa(JSON.stringify(courseArr)),0,'/');
    }

    retainShortlistCTA(courseId){
        var courseArr = this.getShortlistedCookie();
        if(courseArr.length>0 && courseArr.indexOf(courseId) !='-1'){
            var e = document.getElementsByClassName('shrt_'+courseId);
            for(var i = 0; i < e.length; i++){
                e[i].classList.add('active');
            }

            var e = document.getElementsByClassName('shrtBtn_'+courseId);
            if(e.length){
                for(var i = 0; i < e.length; i++){
                    e[i].text = 'Shortlisted';
                }
            }    
        }
    }

    getShortlistedCookie(){
        var courseArr = new Array();
            courseArr = (getCookie('short_courses')) ? JSON.parse(atob(getCookie('short_courses'))) : [];
        if(courseArr.length>0){
            return courseArr;
        }
        return courseArr;
    }
}
Shortlist.defaultProps = {
  className: 'ctp-shrtlst rippleefect',
  isButton : false,
  showRecoLayer : true
};
export default Shortlist;