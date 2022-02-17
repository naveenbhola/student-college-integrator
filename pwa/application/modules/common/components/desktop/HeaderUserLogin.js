import React, {Component} from 'react';
import { connect } from 'react-redux';
import { getRequest } from '../../../../utils/ApiCalls';
import APIConfig from './../../../../../config/apiConfig';
import {isEmpty, getImageUrlBySize, clearCTAInfo} from '../../../../utils/commonHelper';
import {showRegistrationFormWrapper, showLoginFormWrapper, signOutUserWrapper} from '../../../../utils/regnHelper';
import Anchor from './../../../reusable/components/Anchor';
import SocialSharingIcon from './SocialSharingIcon';
import HeaderSegments from "./HeaderSegments";

class HeaderUserLogin extends Component {
  constructor(props){
    super(props);
    this.state = {
        userData : {},
        shortlistedCourseCount : ''
    }
  }
  componentDidMount(){
    const axiosConfig = {
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        withCredentials: false
    };
    let nextState = {};
    //get logged in user data
    getRequest(this.props.config.SHIKSHA_HOME+'/mcommon5/MobileSiteHamburgerV2/getProfileData?from=pwa', axiosConfig).then((response) => {
        if(response.data && response.data.userStatus != 'false'){
          nextState.userData = response.data;
          nextState.profileImg = response.data.displayname[0].toUpperCase();

          window.userHasLoggedIn =  true;

          //get shortlist/compare count
          if(Object.keys(nextState).length > 0){
            this.setState(nextState);
          }

          if(typeof window != 'undefined'){
            window.isUserLoggedIn = true;
          }
      }else{
          clearCTAInfo();
      }
    })
    .catch((err)=> console.log('User::',err));
  }

  nonLoggedIdProfile(){
    return(
      <span className="n-loginSgnup">
        <i className="blank-pp-icon icons"> </i>
        <a href="javascript:void(0);" action="login" onClick={this.showLoginForm.bind(this)}>Login</a>
        <span className="registerPipe">|</span>
        <a href="javascript:void(0);" onClick={this.showRegistrationForm.bind(this)}>Sign Up</a>
      </span>
    )
  }
  loggedInProfile(){
    let avtarurl = (this.state.userData.avtarurl) ? this.state.userData.avtarurl : null;

    if(avtarurl != null && avtarurl !== ''){
      avtarurl = getImageUrlBySize(avtarurl, 'small');
    }

    let userInitial = (this.state.userData.displayname) ? this.state.userData.displayname[0].toUpperCase() : '';
    
    let displayname = (this.state.userData.displayname) ? this.state.userData.displayname : '';

    let profileUrl = this.state.userData.profileUrl ? this.state.userData.profileUrl : null;
    
    let saveListUrl = '';
    if(this.props.saveListUrl && this.props.saveListUrl.url){
        saveListUrl = this.props.saveListUrl.url;
    }
    if(!this.state.userData.profileUrl || this.state.userData.profileUrl === ''){
        return (
            <div className={"n-loginSgnup2"}> </div>
        );
    }
    let profileClass = 'n-profileBx innerp-log-box';
    if(this.props.isHomePage){
        profileClass = 'n-profileBx';
    }
    return(
      <div className={"n-loginSgnup2 "+(this.state.userData.profileUrl !== '' ? "n-profIco" : '')}>
          <span> {(avtarurl) ? <img className="small-pp-img" src={this.props.config.IMAGES_SHIKSHA+avtarurl}/> : <p className="user_initial">{userInitial}</p>} </span>
          <a className="n-username">{displayname}</a>
          <a className="n-username-icon">
              <i className="icons ic_profilepage"> </i>
          </a>
          <div className={profileClass}>
              <a href={profileUrl} tabIndex="1">Profile</a>
              {saveListUrl && <Anchor to={saveListUrl} >Predictor List</Anchor>}
              <a href="javascript:void(0);" tabIndex="2" action="logout" onClick={this.SignOutUser.bind(this)}>Sign Out</a>
          </div>
      </div>
    )
  }
  courseShortListStatus(){
      let style = {display:'none'};
      if(this.props.isUserLoggedIn === true || this.props.isUserLoggedIn === 'true'){
          style = {display:'block'};
      }
    return (
      <React.Fragment>
        <span className="n-headShortlst nShorlstd" style={style} id="_myShortlisted">
          <a href={this.props.config.SHIKSHA_HOME+"/resources/colleges-shortlisting#myshortlist"} title="Shortlisted colleges"><i className="icons ic_head_shorlist1x" id="selectedIcons"> </i>
            <p id="myShortlistCount">&nbsp;</p>Shortlist
          </a>
        </span>
      </React.Fragment>
    );
  }
  render(){
    return (
      <div className="n-lognSgnBx">
        <SocialSharingIcon />
        <span className="ask-shiksha">
          <a href={this.props.config.SHIKSHA_ASK_HOME} ga-page="gnb" ga-attr="GNB_OPEN_STATE" ga-optlabel="ASK_GNB" title="Get answers from current students, alumni &amp; our experts">
            <i className="icons ic_ask-shiksha"> </i>Ask
          </a>
        </span>
        {this.courseShortListStatus()}
          {(this.props.isUserLoggedIn === true || this.props.isUserLoggedIn === 'true') ? this.loggedInProfile() : this.nonLoggedIdProfile()}
      </div>
    );
  }
  showRegistrationForm(){
    let formData = {
      trackingKeyId : 249,
      callbackFunction : 'callBackAfterRegn',
      callbackFunctionParams : {}
    };
    showRegistrationFormWrapper(formData);
  }
  showLoginForm(){
    let formData = {};
    showLoginFormWrapper(formData);
  }
  SignOutUser(){
    signOutUserWrapper();
  }
}
HeaderUserLogin.defaultProps = {
    isUserLoggedIn : false,
    isHomePage : false
};
function mapStateToProps(state)
{
    return {
        config : state.config,
        userData : state.userDetails,
        saveListUrl : state.saveList
    }
}
export default connect(mapStateToProps)(HeaderUserLogin);
