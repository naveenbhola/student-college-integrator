import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import { getRequest } from '../../../utils/ApiCalls';
import {isUserLoggedIn} from './../../../utils/commonHelper';
import { connect } from 'react-redux';

class HamburgerUser extends Component {
  
  constructor(props){
    super(props);
    this.state = {
      userData : {}
    }
  }

  componentDidMount(){
    if(isUserLoggedIn()){
          const axiosConfig = {
              headers: {'Content-Type': 'application/x-www-form-urlencoded'},
              withCredentials: true
          };
          getRequest(this.props.config.SHIKSHA_HOME+'/mcommon5/MobileSiteHamburgerV2/getProfileData?from=pwa',axiosConfig)
          .then((response) =>{
            if(response.data && response.data.userStatus !='false'){
                this.setState({userData:response.data,profileImg:response.data.displayname[0].toUpperCase()});
                window.userHasLoggedIn = true;
            }
          })
          .catch((err)=> console.log('User::',err));
      }
  }

  isEmpty(obj) {
    for(var key in obj) {
        if(obj.hasOwnProperty(key))
            return false;
    }
    return true;
  }

  render() {
    return (
        <div>
          { ((typeof(this.state.userData.userStatus)!='undefined' && this.state.userData.userStatus !== 'false') || (!this.isEmpty(this.props.userData) && parseInt(this.props.userData.userStatus[0].userid)>0)) ? this.loggedInProfile() : this.nonLoggedIdProfile()}
        </div>
    	);
  }

  nonLoggedIdProfile(){
    return(
        <div className="r-user">
          <div className="user-block">
             <div className="u-div">
                <div className="user-img-ham"> <span>G</span> </div>
                <div className="ru-name">
                   <p className="c-name-login">Welcome Guest</p>
                   <p className="log-block">
                     <a className="log-in" href={this.props.config.SHIKSHA_HOME+'/muser5/UserActivityAMP/getLoginAmpPage?fromwhere=pwa'}>Login</a>
                     <a className="n-reg" href={this.props.config.SHIKSHA_HOME+'/muser5/UserActivityAMP/getRegistrationAmpPage?fromwhere=pwa'}>Register</a>
                   </p>
                 </div>
             </div>
          </div>
        </div>
    ) 
  }

  loggedInProfile(){

    const avtarurl = (this.state.userData.avtarurl) ? this.state.userData.avtarurl : ((Object.keys(this.props.userData).length>0 && this.props.userData.avtarurl) ? this.props.userData.avtarurl : null); 

    const profileImg = (Object.keys(this.props.userData).length>0 && this.props.userData.avtarurl == '') ? this.props.userData.displayname[0].toUpperCase() : ((this.state.userData.displayname) ? this.state.userData.displayname[0].toUpperCase() : 'G');

    const displayname = (Object.keys(this.props.userData).length>0 && this.props.userData.displayname) ? this.props.userData.displayname : ((this.state.userData.displayname) ? this.state.userData.displayname : 'Guest');

    const profileUrl = (this.state.userData.profileUrl) ? this.state.userData.profileUrl : ((Object.keys(this.props.userData).length>0 && this.props.userData.profileUrl) ? this.props.userData.profileUrl : null);

    return(
        <div className="r-user">
          <div className="user-block">
             <div className="u-div">
                <div className="user-img-ham"><span>
                {
                  (avtarurl) ? <img src={this.props.config.IMAGES_SHIKSHA+avtarurl}/> : profileImg
                }</span> </div>
                <div className="ru-name">
                   <p className="c-name-login makeBold">Welcome {displayname}</p>
                   <p className="log-block loged_in">
                     <a className="log-in" href={profileUrl}>View profile</a>
                     <a className="n-reg" href={this.props.config.SHIKSHA_HOME+'/muser5/MobileUser/logout'}>Logout</a>
                   </p>
                 </div>
             </div>
          </div>
        </div>
    ) 
  }
}

HamburgerUser.userData = {};

function mapStateToProps(state)
{
    return {
        config : state.config,
        userData : state.userDetails
    }
}
export default connect(mapStateToProps)(HamburgerUser);