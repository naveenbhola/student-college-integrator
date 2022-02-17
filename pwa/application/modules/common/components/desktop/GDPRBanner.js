import React, { Component } from 'react';
import {connect} from 'react-redux';

import { setCookie, isUserLoggedIn ,getCookie} from '../../../../utils/commonHelper';
import { getRequest } from '../../../../utils/ApiCalls';

class GDPRBanner extends Component {
  componentDidMount(){
    if(window.hideGdpr) {
        this.handleGDPRClick(0);
    }
    var gdprCookieExist = getCookie('gdpr');
    if(gdprCookieExist.length <= 0){
  		document.querySelector('.cokkie-lyr').style.display = 'block';
      document.getElementById('stickyBanner').style.bottom = '50px';
      document.querySelector('.cookAgr-btn').addEventListener('click', this.handleGDPRClick.bind(this));
  	}
  }
  handleGDPRClick(userClicked = 1){
    document.querySelector('.cokkie-lyr').style.display = 'none';
    document.querySelector('.stickyBanner').style.bottom = '0px';
    var curDateObj = new Date();
    var timeStamp = Math.floor(curDateObj.getTime() / 1000);
    setCookie('gdpr', timeStamp, 90, 'days');
    if(userClicked && isUserLoggedIn()){
      getRequest(this.props.config.SHIKSHA_HOME+'/common/CookieBannerTracking/saveOldUserCookieData?from=pwa');
    }
  }
  render() {
    return (
      <React.Fragment>
      <div className="cokkie-lyr">
        <div className="cokkie-box">
          <p>We use cookies to improve your experience. By continuing to browse the site, you agree to our <a href="/shikshaHelp/ShikshaHelp/privacyPolicy" target="_blank">Privacy Policy</a> and <a href="/shikshaHelp/ShikshaHelp/cookiePolicy" target="_blank">Cookie Policy</a>.</p>
          <div className="tar"><button className="cookAgr-btn">OK</button></div>
        </div>
      </div>
      </React.Fragment>
    );
  }
}
function mapStateToProps(store) {
  return (
    {
      config : store.config
    }
  );
}
export default connect(mapStateToProps)(GDPRBanner);
