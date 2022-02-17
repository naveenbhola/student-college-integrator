import React, { Component } from 'react';

import FooterTopSection from './FooterTopSection';
import FooterMiddleSection from './FooterMiddleSection';
import FooterBottomSection from './FooterBottomSection';

class FooterLinks extends Component {
  render() {
    return (
      <footer id="footer">
        <FooterTopSection config={this.props.config} isAskButton={true} />
        <FooterMiddleSection order={this.props.linksData.footer1_Order} data={this.props.linksData.footer1} />
        <FooterBottomSection order={this.props.linksData.footer2_Order} data={this.props.linksData.footer2} />
      </footer>
    	)
  }
}

export default FooterLinks;
