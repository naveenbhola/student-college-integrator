import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import { connect } from 'react-redux';
import {bindActionCreators} from 'redux';
import PreventScrolling from './../../reusable/utils/PreventScrolling';
import Analytics from './../../reusable/utils/AnalyticsTracking';
import {isUserLoggedIn, updateNotification, removeCourseFromCompare, getComparePageUrl, getCompareData, getCompareCourse, setCookie} from './../../../utils/commonHelper';
import ResponseForm from './../../user/Response/components/ResponseForm';
import APIConfig from './../../../../config/apiConfig';
import {getRequest } from '../../../utils/ApiCalls';
import Anchor from './../../reusable/components/Anchor';
import {storeSaveList} from './../../predictors/CollegePredictor/actions/CollegePredictorAction';

function bindFunctions(functions)
{
  functions.forEach( f => (this[f] = this[f].bind(this)));
}

class RightPanel extends Component {
  
  constructor(props,context)
  {
    super(props);
    this.state = { openResponseForm: false };
    bindFunctions.call(this,[
      'trackEvent'
      ]);

  }

  componentDidMount(){
    let storeData = {};
    var courseIds = getCompareCourse();
    var query = '';
    for(var i in courseIds){
        query += '&compareCourseIds='+courseIds[i];
    }
    
    if(courseIds.length>0 || isUserLoggedIn()){
      window.addEventListener('load', getRequest(APIConfig.GET_RHL+'?userId=0'+query)
      .then((response) => { 
          this.displayBellCount(response.data.data);

          let saveListUrl = (response.data.data && response.data.data.shortlistUrl) ? response.data.data.shortlistUrl : '';
          if(saveListUrl){
              storeData.url = saveListUrl;
              this.props.storeSaveList(storeData);
          }
        })
        .catch((err)=> console.log(err)));
    }
  }

  componentDidUpdate(){
      updateNotification();
  }

  displayBellCount(data)
  {
      var shrtCount = (data.shortlistedCourse.length) ? data.shortlistedCourse.length : 0;
      var bellIconCount = parseInt(shrtCount);
      setCookie('mob_shortlist_Count',shrtCount);
      this.setState({bellIconCount : bellIconCount, compareList : data.compareCourseMap});
      updateNotification();
  }

  prepareCompareData(){
      var html = new Array();
      var cmp = (typeof(this.props.compareData) !='undefined' && Object.keys(this.props.compareData).length) ? this.props.compareData : (typeof(this.state.compareList) !='undefined' && Object.keys(this.state.compareList).length) ? this.state.compareList : {};
      if(Object.keys(cmp).length>0){
        for(var c in cmp){
          html.push(<li id={'l'+c} key={'l'+c}><a href="javascript:void(0);"><i onClick={this.removeCompareCourse.bind(null,{'courseId':c,'instituteId':cmp[c]['instituteId']})} className="cls-clg" data-courseid={c} data-instituteid={cmp[c]['instituteId']}>×</i>{cmp[c]['instituteName']}{(cmp[c]['localityName']) ? <span className="locality"><i className="msprite compare-locality"></i>{cmp[c]['localityName']}</span>: null}</a></li>);
        }
      }
      return html;
  }

  removeCompareCourse(data){
      document.getElementById('l'+data.courseId).remove();
      removeCourseFromCompare(data.courseId);
  }

  rightPanelClose()
  {
    let menuBox = document.querySelector('#menu-right');
    if(menuBox)
    {
      menuBox.classList.remove("transZero");
    }
    PreventScrolling.enableScrolling();
  }

  gotoComparePageWrapper(response){
    if(typeof(response) !='undefined' && response.userId>0){
        getComparePageUrl();
    }
  }

  validateCompareUser(){
      var course = getCompareData();
      course = (course.length) ? course[0].split("::") : [];
    if(!isUserLoggedIn() && course.length>0){
      this.setState({openResponseForm: true, courseId : course[0], trackingKeyId : course[1]});
    }else{
      getComparePageUrl();
    }
  }

  trackEvent=(action)=>{
    let gaAction = (action) ? action : 'RightPanelClick';  //RburgClick
    Analytics.event({category : 'Head', action : gaAction, label : 'Click'});
    if(gaAction == 'saveList'){
        document.getElementById("off-right").click();
    }
  }

  render() {
    var compareCourseList = this.prepareCompareData();
    let saveListUrl = '';
    if(this.props.saveListUrl && this.props.saveListUrl.url){
        saveListUrl = this.props.saveListUrl.url;
    }
    return( 
      <React.Fragment>
      
        <aside className="menu-right" id="menu-right">
            <section className="layer-shade clearfix">
                <div className="clearfix">
                    <ul className="list-items added-items">
                          <li><a href={this.props.config.SHIKSHA_HOME + "/userprofile/edit"} onClick={this.trackEvent}>My Profile</a></li>
                          <li><a href={ this.props.config.SHIKSHA_HOME +  "/resources/colleges-shortlisting"} onClick={this.trackEvent} id="total-shortlisted-colleges">Shortlist (0)</a></li>
                          {saveListUrl && <li><Anchor onClick={()=>{this.trackEvent('saveList')}} to={saveListUrl} >Predictor List</Anchor></li>}
                          <li><a href="javascript:void(0);" onClick={this.trackEvent} id="total-college-compare">Compare Colleges (0)</a></li>
                    </ul>
                </div>
            </section>
            <div id="_createCmp" className="compare-colg-slist">
                <ul id="_comparedList">
                  {compareCourseList}
                  <li>
                      <a href="javascript:void(0);" id="compareRightPanel" onClick={this.validateCompareUser.bind(this)}>
                        <button type="button" name="button" className="button button--teal">Compare</button>
                      </a>
                      
                  </li>
                </ul>
            </div>
            <ResponseForm openResponseForm={this.state.openResponseForm} clientCourseId={this.state.courseId} listingType={"course"} actionType={"compare"} trackingKeyId={this.state.trackingKeyId} callBackFunction={this.gotoComparePageWrapper} onClose={this.closeResponseForm.bind(this)} />
      </aside>
      <label htmlFor="offcanvas-right" id="off-right" className="full-screen-close" onClick={this.rightPanelClose.bind(this)}></label>
      </React.Fragment>
      )
  }

  closeResponseForm() {
    this.setState({openResponseForm: false});
  }

}

function mapStateToProps(state)
{ 
    return {
        config : state.config,
        compareData : state.compareCourseList,
        saveListUrl : state.saveList
    }
}
function mapDispatchToProps(dispatch){
  return bindActionCreators({storeSaveList},dispatch);
}
export default connect(mapStateToProps, mapDispatchToProps)(RightPanel);
