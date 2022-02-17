import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import {setCookie, getCookie, updateNotification, isUserLoggedIn, getCompareCourse, removeCompareCourseFromRHL, showToastMsg} from '../../../utils/commonHelper';
import ResponseForm from './../../user/Response/components/ResponseForm';
import PopupLayer from './popupLayer';
import { getRequest } from '../../../utils/ApiCalls';
import APIConfig from './../../../../config/apiConfig';
import config from './../../../../config/config';
//import { connect } from 'react-redux';
//import { bindActionCreators } from 'redux';
//import {getCompareList} from './../actions/commonAction';

class Compare extends Component {
    constructor(props)
    {
        super(props);
        this.state = { openResponseForm: false };
        this.compareCookieName = "mob-compare-global-data";
        this.cmpCookieArr      = new Array();
        this.cmpCookieArrLen   = new Array();
        this.isCmpRemoved      = false;
        this.removeAllFunction = '';
        this.bindList          = {};
        this.bindList['click'] = ['.compare-btn','.cls-clg'];

    }

    componentDidMount(){
        this.bindCompareCTA();
    }

    addToCompare(compareParam, thisObj, callBackData) {
    
        thisObj.style.pointerEvents = "none"; // prevent from double clicks

        if(typeof(compareParam.instituteId) == 'undefined'){
            compareParam.instituteId = '';
        }

        var courseId    = compareParam.courseId;
        var instituteId = compareParam.instituteId;
        var comparePageKeyId = compareParam.tracking_keyid;

        this.cmpCookieArrLen = (getCookie(this.compareCookieName)) ? getCookie(this.compareCookieName).split("|") : [];
        this.cmpCookieArr    = this.cmpCookieArrLen;
        var totalCompChecks  = this.cmpCookieArr.length;
    
        this.removeCompare(courseId);

        if(compareParam.courseId>0){
                if(this.isCmpRemoved == false){

                  totalCompChecks = (this.cmpCookieArr.length !='' && this.cmpCookieArr.length !=-1) ? this.cmpCookieArr.length : totalCompChecks;

                    if(totalCompChecks >= 2){
                        thisObj.style.pointerEvents = "";
                        // step 1 - don't open layer, go to login page
                        var formText = {'heading':'You can compare only 2 colleges at a time.<br>Please register to view the comparison based on important parameters, such as:'};
                        this.userLayer(formText, comparePageKeyId, thisObj, compareParam, callBackData);
                        return true;
                    }
                    var instituteIdStr ='';
                    if(instituteId>0 && typeof(instituteId) !='undefined'){
                      instituteIdStr = '::'+instituteId;
                    }
                    var value = courseId+'::'+comparePageKeyId+instituteIdStr; // courseId::tracking_keyid::instituteId
                    this.cmpCookieArr.push(value);
              }
        }
        setCookie(this.compareCookieName,this.cmpCookieArr.join("|"),30);
        this.prepareCompare(compareParam, thisObj, callBackData);
    }

    prepareCompare(compareParam, thisObj, callBackData){
        //this.props.getCompareList(getCompareCourse());
        this.getCompareList(getCompareCourse(), compareParam, thisObj, callBackData);
        updateNotification();
    }

    getCompareList(courseIds, compareParam, thisObj, callBackData){
        
        var query = '';
        for(var i in courseIds){
            query += ((i>0) ? '&':'')+'compareCourseIds='+courseIds[i];
        }

        var actionType = (this.isCmpRemoved) ? 'removed' : 'added';

        if(query){
            getRequest(APIConfig.GET_COMPARE_COUNT+'?'+query).then((response) => {
                if(response.data.data){
                    var html = '';
                    var i = 0;
                    for(var courseId in response.data.data){
                        if(document.getElementById('l'+courseId)){
                            document.getElementById('l'+courseId).remove();
                        }
                        var newItem = document.createElement('li');
                           newItem.className = 'cmpList';
                           newItem.id = 'l'+courseId;
                           if(response.data.data[courseId]['localityName']){
                            var location = '<span class="locality"><i class="msprite compare-locality"></i>'+response.data.data[courseId]['localityName']+'</span>';
                           }
                           newItem.innerHTML ='<a href="javascript:void(0);"><i class="cls-clg" data-courseid="'+courseId+'" data-instituteid="'+response.data.data[courseId]['instituteId']+'">×</i>'+response.data.data[courseId]['instituteName']+location+'</a>';
                        var list = document.getElementById("_comparedList");
                        list.insertBefore(newItem, list.childNodes[i]);
                        i++;
                    }
                    this.bindRHLEvent();
                    var response = {'courseId':compareParam.courseId, 'instituteId':compareParam.instituteId, 'msg':'success','actionType': actionType};
                    this.callFun(response, compareParam, thisObj, callBackData);
                }
            }).catch(function(err){
                    var response = {'courseId':compareParam.courseId, 'instituteId':compareParam.instituteId, 'msg':'failed','actionType': actionType};
                    this.callFun(response, compareParam, thisObj, callBackData);
            });
        }else{
            this.bindRHLEvent();
            var response = {'courseId':compareParam.courseId, 'instituteId':compareParam.instituteId, 'msg':'success','actionType': actionType};
            this.callFun(response, compareParam, thisObj, callBackData);
        }
        
    }

    bindRHLEvent(){
        var classname = document.getElementsByClassName("cls-clg");
        for (var i = 0; i < classname.length; i++) {
            classname[i].addEventListener('click',function(){
                var ele = this;
                removeCompareCourseFromRHL(ele);
            });    
        }
    }

    userLayer(formText, comparePageKeyId, thisObj, compareParam, callBackData){
        this.handleExceedLimit(compareParam, thisObj, callBackData);
        if(!isUserLoggedIn())
        {   
            this.userLoginForCompare(formText, comparePageKeyId); 
        }else{
            this.alertMsg();
        }
    }

    userLoginForCompare(){
            var compareCookieArray = this.getCompareData()
            if(compareCookieArray.length > 0 && compareCookieArray !=''){
              this.showResponseForm();
            }else{
              window.location = config().SHIKSHA_HOME+'/resources/college-comparison';
            }
    }

    handleExceedLimit(compareParam, thisObj, callBackData){
        var response = {'courseId': compareParam.courseId, 'instituteId':compareParam.instituteId, 'msg':'failed','actionType':'exceedLimit'};
        this.callFun(response, compareParam, thisObj, callBackData);
    }

    callFun(response, compareParam, thisObj, callBackData){
        thisObj.style.pointerEvents = ""; // enable click
        if(typeof compareParam.customCallBack != 'undefined' && compareParam.customCallBack != ''){
            var callUserFunction = compareParam.customCallBack;
            // call callBack function with callBackData
            if(typeof callUserFunction == 'function') {
              callUserFunction(response, callBackData); 
            }else if(typeof eval(callUserFunction) == 'function') {
              eval(callUserFunction)(response, callBackData);
            }
        }  
    }

    setRemoveAllCallBack(functionName){
        if(typeof functionName != 'undefined'){
            this.removeAllFunction = functionName; 
        }
    }

    callRemoveAllCallBack(response){
          if(this.removeAllFunction !='' && typeof eval(this.removeAllFunction) == 'function'){
            eval(this.removeAllFunction)(response); // call function remove all from compare bottom sticky
          }
    }

    removeCompare(courseId,fromWhere){
        var array = new Array();
        fromWhere = (typeof(fromWhere) == 'undefined') ? '' : fromWhere;
        array = this.getCompareData();
        this.isCmpRemoved = false;
        for(var i in array){
        var cookieItemArray = array[i].split("::");
            if(cookieItemArray[0]==courseId){
                array.splice(i,1);
                this.cmpCookieArr = array;
                if(fromWhere === 'closeIcon'){
                  setCookie(compareCookieName,this.cmpCookieArr.join("|"),30);
                }
                this.isCmpRemoved = true;
                break;
            }
        }
    }
    
    getCompareData(){
      var cookiename = this.compareCookieName;
      if(getCookie(cookiename)){
          this.cmpCookieArr = getCookie(cookiename).split("|");
      } 
      return this.cmpCookieArr;
    }

    gotoComparePageWrapper(response){
        var self = this;
        if(typeof(response) !='undefined' && response.userId>0){
           self.getComparePageUrl();
        }
    }

    getComparePageUrl(){
        var cmpData = new Array();
        cmpData = this.getCompareData();
        var finalUrl ='';
        var urlStr   ='';
        for(var i = 0; i <  2; i++){
            if(cmpData[i] != undefined){ 
                var cmpStr    = cmpData[i].split("::");
                    finalUrl += cmpStr[0]+'-'; // courseId
            }
        }
        finalUrl = finalUrl.substring(0, (finalUrl.length)-1); 
        if(finalUrl){
          urlStr = '-'+finalUrl;
        }
        finalUrl = config().SHIKSHA_HOME+'/resources/college-comparison'+urlStr; 
        window.location = finalUrl; 
    }

    removeCompareFromRHL(thisObj){
        var listId      = thisObj.getAttribute("data-id");
        var courseId    = thisObj.getAttribute('data-courseId');
        var instituteId = thisObj.getAttribute('data-instituteId');
        this.removeCompare(courseId,'closeIcon');
        var response = {'msg':'success','actionType': 'removed', 'courseId':courseId, 'instituteId':instituteId}; 
        document.getElementById('l'+listId).remove();
        updateNotification();
        this.callRemoveAllCallBack(response);
    }

    changeCTAText(className, ctaText){
        var e = document.getElementsByClassName(className);  // Find the elements
        for(var i = 0; i < e.length; i++){
            e[i].innerText = ctaText;    // Change the content
        }
    }

    bindCompareCTA(){
        var self = this;
        document.querySelectorAll('.btnCmp'+this.props.courseId).forEach(function(ele) {
            ele.addEventListener('click' , function(){
                var ele = this;
                var compareParam = {'courseId':ele.getAttribute('data-courseid'), 'tracking_keyid':ele.getAttribute('data-trackid'), 'customCallBack': self.defaultCompareCallback,'callbackFunctionParams':{element:ele}};
                var callBackData = {element:ele};
                self.addToCompare(compareParam, ele, callBackData);
                //self.setRemoveAllCallBack(self.defaultCompareCallback); // not in use
            });
        });
        this.retainCompareCTA();
    }

    retainCompareCTA(){
        var cmpData = new Array();
        cmpData = this.getCompareData();
        for(var i in cmpData){
          var courseId = cmpData[i].split('::');
          var className =  "btnCmp"+courseId[0];
          if(className){
            this.changeCTAText(className, 'Added to Compare');
          }
        }
    }

    defaultCompareCallback(response, callbackParams){
        if(response.msg == "failed"){
            return;
        }else{
            var className = "btnCmp"+response.courseId;
            if(response.actionType == "added"){
                var ctaText = 'Added to Compare';
                showToastMsg('Course Added to Compare');
            }else{
                var ctaText = 'Compare';
            }
            var e = document.getElementsByClassName(className);  // Find the elements
            for(var i = 0; i < e.length; i++){
                e[i].innerText = ctaText;    // Change the content
            }
        }

        if(response.actionType == "removed" && document.getElementById('l'+response.courseId)){
            document.getElementById('l'+response.courseId).remove();
        }
    }

    alertMsg(){
        this.PopupLayer.open();
    }

    alertHtml(){
        return(
                <React.Fragment>
                    <p>You can compare up to only 2 colleges at a time. Would you like to compare the colleges you have selected ?</p>
                    <div className="compr-lyrBtn"><a className="ctp-btn ctpComp-btn" onClick={this.getComparePageUrl.bind(this)}>Compare</a></div>
                </React.Fragment>
            );
    }

    render(){
      return(
        <React.Fragment>
          
            <a className={'ctp-btn ctpComp-btn bindCmp btnCmp'+this.props.courseId} id={'compare'+this.props.courseId} data-courseid={this.props.courseId} data-trackid={(typeof(this.props.isCallReco) == 'undefined') ? this.props.trackid : this.props.recoTrackid} onClick={this.props.clickHandler}>Compare</a>

            <PopupLayer onRef={ref => (this.PopupLayer = ref)} data={this.alertHtml()} heading={'College Comparison'} onContentClickClose={false}/>

            <ResponseForm openResponseForm={this.state.openResponseForm} clientCourseId={this.props.courseId} listingType="course" cta="compare" actionType="MOB_COMPARE_VIEWED" trackingKeyId={(typeof(this.props.isCallReco) == 'undefined') ? this.props.trackid : this.props.recoTrackid} callBackFunction={this.gotoComparePageWrapper.bind(this)} onClose={this.closeResponseForm.bind(this)}/>
                        
        </React.Fragment>
      );
    }

    showResponseForm() {
        this.setState({...this.state, openResponseForm: true});
    }

    closeResponseForm() {
        this.setState({openResponseForm: false});
    }

}

/*function mapDispatchToProps(dispatch){
  return bindActionCreators({ getCompareList }, dispatch); 
}
export default connect(null,mapDispatchToProps)(Compare);*/

export default Compare;
