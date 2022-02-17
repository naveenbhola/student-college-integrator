import React, { Component } from 'react';
import { connect } from 'react-redux';

import HeaderSegments from './HeaderSegments';
import {resetGNB} from "../../../../utils/commonHelper";
import {DFPBannerLeaderBoard} from './../../../reusable/components/DFPBannerAd';
import {withRouter} from 'react-router-dom';

let currScroll = 0, scrollTop = 0, isFixed = false, isHidden = false, dfp_above_header = 0, headerFixedAt = 0;

class DesktopHeader extends Component {
  constructor(props){
    super(props);
    this.isHomePage = this.props.location.pathname === '/';
    this.leaderBoardDfp = {
      'DFP_CategoryPage' : ['/(.*)/colleges/(.*)', '/colleges/(.*)', '/colleges(.*)']
    };
    let parentPage = '';
    for(let page in this.leaderBoardDfp){
      if(this.leaderBoardDfp.hasOwnProperty(page)){
        this.leaderBoardDfp[page].map(regx => {
          let pattern = new RegExp(regx);
          let res = pattern.test(this.props.location.pathname);
          if(res){
            parentPage = page;
            return;
          }
        });
        if(parentPage !== ''){
          break;
        }
      }
    }
    this.state = {
      parentPage : parentPage
    };
  }
  componentDidMount(){
    currScroll = window.scrollY;
    window.addEventListener('scroll', this.handleGNBSlide.bind(this));
    this.unlisten = this.props.history.listen(this.updateParentPageName);
  }
  componentWillUnmount() {
    window.removeEventListener('scroll', this.handleGNBSlide.bind(this));
    this.unlisten();
  }
  updateParentPageName = (location) => {
    this.isHomePage = location.pathname === '/';
    resetGNB();
    if (location.pathname !== this.state.pathname) {
      let parentPage = '';
      for(let page in this.leaderBoardDfp){
        if(this.leaderBoardDfp.hasOwnProperty(page)){
          this.leaderBoardDfp[page].map(regx => {
            let pattern = new RegExp(regx);
            let res = pattern.test(location.pathname);
            if(res){
              parentPage = page;
              return;
            }
          });
          if(parentPage !== ''){
            break;
          }
        }
      }
      this.setState({ parentPage : parentPage });
    }
  };

  handleGNBSlide(event){
    if(window.isHeaderFixed === true){
      currScroll = window.scrollY;
      if(dfp_above_header === 0){
        dfp_above_header = document.getElementById('dfp_above_header').clientHeight;
        headerFixedAt = 80;
      }else{
        headerFixedAt = 0;
      }
      if(document.querySelector('#_globalNav').classList.contains('_gnb-toggle-anim') && currScroll + headerFixedAt < document.getElementById('page-header').offsetTop -1){
        resetGNB();
      }else if(currScroll < document.getElementById('page-header').offsetTop){
        return;
      }

      if(isFixed==false && currScroll > scrollTop && window.getComputedStyle(document.querySelector('#_globalNav')).display != 'none'){
        isFixed = true;
        if(window.getComputedStyle(document.querySelector('.menu-overlay')).display == 'none'){
          document.querySelector('#_globalNav').classList.add('_gnb-sticky');
          document.querySelector('#_globalNav').classList.remove('_gnb-toggle-anim');
          document.querySelector('#main-wrapper').style.marginTop = '56px';
         //(document.getElementById('search-background-search-page')) ? document.getElementById('search-background-search-page').style.marginTop = '80px' : null;
        }
      }else if(isFixed==true && currScroll < scrollTop && window.getComputedStyle(document.querySelector('#_globalNav')).display != 'none'){
        isFixed = false;
        document.querySelector('#_globalNav').classList.add('_gnb-toggle-anim');
        document.querySelector('#main-wrapper').style.marginTop = '80px';
       // (document.getElementById('search-background-search-page')) ? document.getElementById('search-background-search-page').style.marginTop = '' : null;
      }
      if(!isHidden && document.getElementById('footer').offsetTop - currScroll < 80){
        document.querySelector('#_globalNav').style.display = 'none';
        isHidden = true;
      }else if(isHidden && document.getElementById('footer').offsetTop - currScroll > 80){
        document.querySelector('#_globalNav').style.display = 'block';
        isHidden = false;
      }
      scrollTop = currScroll;
    }else{
      document.querySelector('#_globalNav').style.display = 'block';
    }
  }
  render() {
    return (
      <React.Fragment>
        <div className="dfp_flexi_add" id='dfp_above_header'>
          <DFPBannerLeaderBoard isMobile={false} bannerPlace="leaderboard_Desktop" parentPage={this.state.parentPage} />
        </div>
        <HeaderSegments isHomePage={this.isHomePage} config={this.props.config} isUserLoggedIn={this.props.isUserLoggedIn} />
        <div className="menu-overlay"></div>
      </React.Fragment>
    );
  }
}
DesktopHeader.defaultProps = {
  isUserLoggedIn : false
};

function mapStateToProps(state)
{
  return {
      config : state.config
  }
}
export default connect(mapStateToProps)(withRouter(DesktopHeader));
