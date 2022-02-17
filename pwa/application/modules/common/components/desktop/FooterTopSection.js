import React, { Component } from 'react';

import AppInstallForm from './AppInstallForm';

class FooterTopSection extends Component {
  constructor(props){
    super(props);
    this.state = {
      isBannerEnabled : false
    };
  }
  whenAppInstallFormClosed(){
    this.setState({isBannerEnabled:false});
  }

  render(){
    let askBtnWidget = (
      <div className="n-fotHelplne">
        <p>Get our experts to answer your <br/>questions within<strong> 24 Hrs </strong></p>
        <a className="btn__prime" href={this.props.config.SHIKSHA_ASK_HOME}><button type="button" name="button" className="button button--orange">Ask Question</button></a>
      </div>
    );
    if(!this.props.isAskButton){
      askBtnWidget = (
        <div className="n-fotHelplne">
  				<p>Student Helpline Number :<b>011-40469621</b><br/>Timings : <span>9:30 AM - 6:30 PM, MON - FRI</span></p>
        </div>
      );
    }
    return (
      <div className="n-footer2">
        <div className="container">
          <div className="n-fotFolw">
            <a href="javascript:void(0);" onClick={this.getAppBanner.bind(this)} className="dwnld-app icons"></a>
          </div>
          {askBtnWidget}
        </div>
        <div className="n-fotFolw">
  				<ul>
  					<li><a track='FOOTER_FACEBOOK' href="https://www.facebook.com/shikshacafe" title="Join us on Facebook"><i className="icons ic_fb"></i></a></li>
  					<li><a track='FOOTER_TWITTER' href="https://twitter.com/shikshadotcom" title="Join us on Twitter"><i className="icons ic_tw"></i></a></li>
  					<li><a track='FOOTER_GOOGLE+' href="https://plus.google.com/+shiksha/posts" title="Join us on Google+"><i className="icons ic_gp"></i></a></li>
  				</ul>
  			</div>

        <div id="_appbanner">
          {(this.state.isBannerEnabled) ? <AppInstallForm whenAppInstallFormClosed={this.whenAppInstallFormClosed.bind(this)} /> : ''}
        </div>
        <p className="clr"></p>
      </div>
    );
  }
  getAppBanner(e){
    this.setState({isBannerEnabled:true});
  }
}

export default FooterTopSection;
