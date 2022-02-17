import React, {Component} from 'react';
import {connect} from 'react-redux';
import './../../assets/anaLayer.css';

import {initANALayer, bindElementsAndInitialize, openQuestionPostingLayer, askCourseSelection, showAskCoursesDropdown} from '../../../../utils/anaHelper';

class QNALayer extends Component {
  constructor(props){
    super(props);
    this.state = {
      isLayerOpen : false
    }
    this.closeLayer = this.closeLayer.bind(this);
  }
  closeLayer(){
    this.setState({isLayerOpen : false});
    this.props.closeModal();
  }
  componentDidMount(){
    let initConfig = {postingForPage:this.props.postingForPage , postingType : this.props.postingType};
    initANALayer(initConfig);
    if(this.props.isLayerOpen){
      this.setState({isLayerOpen : true});
    }
    window.removedTagsFromAddMoreLayer = [];
  }
  componentDidUpdate(){
    if(this.state.isLayerOpen){
      bindElementsAndInitialize(this.props.postingForType);
      openQuestionPostingLayer();
    }
  }

  askCourseSelectionWrap(cId, cName, obj){
    askCourseSelection(cId, cName);
    showAskCoursesDropdown();
  }

  render(){
    let layer = null, courseDropDownHtml = null;
    if(this.props.courseViewFlag && this.props.instituteCourses.length > 0){
      let courseList = [];
      for (var i = 0; i < this.props.instituteCourses.length; i++) {
        if(this.props.instituteCourses[i]['course_id'] > 0){
          courseList.push(
            <li key={'crs'+i} className="course-li">
              <a onClick={this.askCourseSelectionWrap.bind(this, this.props.instituteCourses[i]['course_id'], this.props.instituteCourses[i]['course_name'])} data-id= {this.props.instituteCourses[i]['course_id']}>{this.props.instituteCourses[i]['course_name']}</a>
            </li>
          );
        }
      }
      courseDropDownHtml = (
        <React.Fragment>
          <div className="slct-box" id="courseSelectionTab">
            <input type="text" id="askCourseSelected" placeholder="Select a course on which you want to ask this question" autoComplete="off" />
            <div className="box-dwn" id="ask_courses">
              <ul className='course-ul' id="cLst">
                <div id="courseSelectionTiny">
                  <div className="scrollbar">
                    <div className="track">
                      <div className="thumb">
                        <div className="end">
                        </div>
                      </div>
                    </div>
                  </div>
                  <div className="viewport">
                    <div id="mainOvLst" className="overview list">
                      {courseList}
                    </div>
                  </div>
                </div>
              </ul>
            </div>
          </div>
          <div style={{display:"none"}} id="cLst_error"><p className="err0r-msg">Please select a course from drop down list.</p></div>
        </React.Fragment>
      );
    }
    if(this.state.isLayerOpen == true){
      layer = (
        <React.Fragment>
        <div className="an-layer an-layer-inner" id="an-layer">
          <form id="anaPostFormQues" action=""  acceptCharset="utf-8" method="post" noValidate="novalidate" name="anaPostFormQues">
            <div id="qsn-post" className="post-col">
              <div className="opacticy-col">
                <img border="0" src="https://images.shiksha.ws/pwa/public/images/desktop/ShikshaMobileLoader.gif" id="loadingImageNew" alt="" className="small-loader" />
              </div>
              <div className="post-qstn">
                <h3 className="post-h2">{this.props.qPostingTitle}</h3>
                <p className="txt-count" id="qstn-input_counter_outer">Characters <span id="qstn-input_counter"> 0</span>/140</p>
                <textarea type="text" minLength="20" maxLength="140" name="question" placeholder={"Type Your "+this.props.postingForType} caption="Question" validate="validateStr" required={true} className="qstn-input" id='qstn-input'></textarea>
                <div style={{display:'none'}}>
                  <p className="err0r-msg" id='qstn-input_error'>The Answer must contain atleast 20 characters.</p>
                </div>
                {courseDropDownHtml}
                <input type="hidden" id="instituteCoursesQP" name="instituteCoursesQP" value={this.props.courseIdQP}/>
                <input type="hidden" id="instituteIdQP" name="instituteIdQP" value={this.props.instituteId}/>
                <input type="hidden" id="responseActionTypeQP" name="responseActionTypeQP" value={this.props.responseAction}/>
              </div>
              <div id="more-qstns-posting" className="more-qstns" >
                <a id="lnk-add-more" className="h2-blue " href="javascript:void(0);"><i className="before" id="plus-minus-icon" nextclass='after'></i>Add more details</a>
                <div id="more-ques-posting">
                  <p className="txt-count" id="more-input-posting_counter_outer">Characters <span className="" id="more-input-posting_counter"> 0</span>/300</p>
                  <textarea className="more-input" id="more-input-posting" minLength="20" maxLength="300" required={this.props.postingForType=="discussion" ? true : false} caption="Description" validate="validateStr" placeholder="Give Information like score, education background etc."></textarea>
                  <div style={{display:'none'}}>
                    <p className="err0r-msg" id="more-input-posting_error">The Answer must contain atleast 20 characters.</p>
                  </div>
                </div>
                <div className="btns-col">
                  <span className="tag-note">Keep it short &amp; simple. Type complete word. Avoid abusive language.</span>
                  <span className="right-box">
                    <a className="exit-btn" href="javascript:void(0);" onClick={this.closeLayer} id="cancelButtonPosting">Cancel</a>
                    <a className="prime-btn" href="javascript:void(0);" id="nextButtonPosting">Next</a>
                  </span>
                  <p className="clr"></p>
                </div>
              </div>
            </div>
          </form>
          <div id="qsn-prvw" className="post-col">
            <div className="opacticy-col"><img border="0" src="https://images.shiksha.ws/pwa/public/images/desktop/ShikshaMobileLoader.gif" id="loadingImageNew" className="small-loader"/></div>
              <div className="post-qstn">
                <h3 className="post-h2 qdTitle-l2">Your  Question</h3>
                <p className="qstn-title" id="qstn-title-posting"><span></span><a href="javascript:void(0);" className="edit-qstn" id="edit-qstn">Edit</a></p>
              </div>
              <div className="more-qstns">
                <div id="slctd-tags" className="tags-slctd"></div>
                  <div className="btns-col">
                    <span className="tag-note">Add relevant tags to get quick responses.</span>
                    <span className="right-box">
                      <a className="exit-btn" href="javascript:void(0);" onClick={this.closeLayer} id="cancelButtonSecondLayer">Cancel</a>
                      <a className="prime-btn" href="javascript:void(0);" id="finalButtonPosting">Post</a>
                    </span>
                    <p className="clr"></p>
                  </div>
                  <div id="similar_ques_outer" className="similar_ques_outer"><br /><br /><br />
                    <img border="0" src="https://images.shiksha.ws/pwa/public/images/desktop/ShikshaMobileLoader.gif" id="loadingImageNew" className="small-loader"/>
                  </div>
                </div>
                <input type="hidden" id="tracking_keyid_ques" value={this.props.qtrackingPageKeyId}/>
                <input type="hidden" id="tracking_keyid_disc" value={this.props.dtrackingPageKeyId}/>
                <input type="hidden" id="entityId" value={this.props.entityId}/>
                <input type="hidden" id="examResponseId" value={this.props.examResponseId}/>
                <input type="hidden" id="tagEntityType" value={this.props.tagEntityType}/>
                <input type="hidden" id="quesDiscKeyId" value={this.props.quesDiscKeyId}/>
              </div>
              <div id="addMoreTagsLayer"></div>
            </div>
        </React.Fragment>
      );
    }
    return layer;
  }
}

function mapStateToProps(store){
  return {
      config : store.config
  };
}
export default connect(mapStateToProps)(QNALayer);
