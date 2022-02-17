import React, { Component } from 'react';
import { connect } from 'react-redux';
import FooterLinks from './FooterLinks';
import BottomCompareWidget from './BottomCompareWidget';
import GDPRBanner from './GDPRBanner';
import ResetPasswordLayer from './ResetPasswordLayer';
import {DFPBannerTempalte} from './../../../reusable/components/DFPBannerAd';

class DesktopFooter extends Component {
  constructor(props){
    super(props);
  }

  render() {
    return (
      <React.Fragment>
          <div className = 'stickyBanner' id='stickyBanner'>    
              <div className="footer_dfp_flexi_add">
                <DFPBannerTempalte bannerPlace="sticky_banner_desktop"/>
              </div>
          </div>
        <p className="clr"></p>
        <FooterLinks config = {this.props.config} linksData={this.props.footerLinks} />
        <BottomCompareWidget compareTrackingId={1039} />
        <GDPRBanner />
        <div className="report-msg" id="report-msg"><p className="toastMsg" id="toastMsg"></p></div>
        <ResetPasswordLayer />
      </React.Fragment>
    	)
  }
}

function mapStateToProps(state)
{
  return {
      config : state.config,
      footerLinks : state.footerLinks
  }
}

export default connect(mapStateToProps)(DesktopFooter);
