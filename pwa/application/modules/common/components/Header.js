import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import HeaderMetaTags from './HeaderMetaTags';
import PreventScrolling  from './../../reusable/utils/PreventScrolling';
import Analytics from './../../reusable/utils/AnalyticsTracking';
import {withRouter, Link} from 'react-router-dom';
import Loadable from 'react-loadable';
import Loading from './../../reusable/utils/Loader';
import { connect } from 'react-redux';
import {DFPBannerLeaderBoard} from './../../reusable/components/DFPBannerAd';
import { getCookie, setCookie, isUserLoggedIn, getQueryVariable, clearCTAInfo} from './../../../utils/commonHelper';
import { getRequest } from './../../../utils/ApiCalls';
import {SearchLayer} from "../../../../routes/loadableRoutes";
import APIConfig from './../../../../config/apiConfig';

const SocialSharing = Loadable({
  loader: () => import('./SocialSharing'/* webpackChunkName: 'SocialSharing' */),
  loading() {return null},
});

const Hamburger = Loadable({
  loader: () => import('./Hamburger'/* webpackChunkName: 'hamburger' */),
  loading() {return null},
});

const RightPanel = Loadable({
  loader: () => import('./RightPanel'/* webpackChunkName: 'rightpanel' */),
  loading() {return null},
  render(loaded, props) {
    let Component = loaded.default;
    return <Component {...props}/>;
  }
});

class Header extends Component {
  constructor(props)
  {
    super(props);
    this.trackHeaderClick    = this.trackHeaderClick.bind(this);
    this.state = {
      pathName: null,
      tabsContentByCategory : {'data':''},
      selectedStreamId      : 0,
      selectedStreamName    : '',
      nonZeroData : {},
      rendered :false,
      showHamburgerPanel: false,
      showRightPanel: false,
      showHamburgerLoader:false,
      showRightPanelLoader:false,
      enableSocialLayer : false,
      openLayer : false
    }
    this.clickScrollFlag = false
    this.mobileApp  =  this.props.mobileApp;
    this.openSocialLayer  = this.openSocialLayer.bind(this);
    this.closeSocialLayer = this.closeSocialLayer.bind(this);
  }

  openSocialLayer=()=>{
    this.setState({enableSocialLayer : true, openLayer : true});
  }

  closeSocialLayer=()=>{
    this.setState({openLayer : false});
    PreventScrolling.enableScrolling(true);
  }

  hamburger(e){
    var self = this;
    self.clickScrollFlag = true;
    Analytics.event({category : 'Head', action : 'HburgOpen', label : 'NavBar', nonInteraction : true});
    (document.getElementById('hbug') && typeof(document.getElementById('hbug')) != 'undefined' && document.getElementById('hbug').classList.contains('bcglayer')) ? document.getElementById('hbug').classList.add('bcglayer') : '';
    
    var menuBox = document.querySelector('#menu-container');
    if(menuBox)
    {
      menuBox.classList.add("show");
      document.getElementById('hbug').classList.add("bcglayer");
      setTimeout(function(){menuBox.classList.add("transZero")},0);
      PreventScrolling.disableScrolling();
      self.getHamburgerData();
    }
  }

  hamburgerOpen(e){
    var self = this;
    if(isUserLoggedIn()){
      self.setState({
          showHamburgerLoader:false,
          showHamburgerPanel:true,
      })
      self.hamburger(e)
    }else{
        self.setState({
          showHamburgerLoader:true
        })
        Hamburger.preload().then(function(){
        self.setState({
          showHamburgerPanel:true,
          showHamburgerLoader:false
        })
        self.hamburger(e)
      })
    }
  }

  /*get heirarchy data for all stream, substream and specialization which having >0 listings, using for hamburger*/
  getHamburgerData(){
      if(document.getElementById('hmldr') == null){ // No hamburger api call if data is already available.
        return;
      }
      getRequest(APIConfig.GET_HAMBURGER).then((response) =>{
        if(response.data !=null){
            this.retainUserState(response.data.data);
        }
      })
      .catch((err)=> console.log('Hamburger :: ',err))
  }

  /*this is use to personalize the hamburger data based on user selected stream*/
  retainUserState(apiResponse){
    var streamid = (getCookie('userStreamId')) ? getCookie('userStreamId') : '';
    var streamName = (getCookie('userStreamName')) ? getCookie('userStreamName') : '';
    if(parseInt(streamid) && streamid !='' && streamName !=''){
      this.setState({'selectedStreamId':getCookie('userStreamId'),'selectedStreamName' : streamName, tabsContentByCategory:apiResponse, nonZeroData:apiResponse['hierarchiesHavingNonZeroListings'], rendered : true});
    }else{
      this.setState({tabsContentByCategory:apiResponse, nonZeroData:apiResponse['hierarchiesHavingNonZeroListings'], rendered : true});
    }
  }

  updateHamburgerByUserStream(s){
    this.setState({'selectedStreamId':s.id,'selectedStreamName' : s.name });
  }

  preloadRightPanel(e){
    var self = this;
    self.setState({
      showRightPanelLoader:true
    })
    RightPanel.preload().then(function(){
    Analytics.event({category : 'Head', action : 'RburgOpen', label : 'NavBar', nonInteraction : true});
    PreventScrolling.disableScrolling();
    }).then(function(){      
      self.setState({
        showRightPanel:true,
        showRightPanelLoader:false
      })
      self.clickScrollFlag = true;
      let menuBox = document.querySelector('#menu-right');
      if(menuBox)
      {
        setTimeout(function(){menuBox.classList.add("transZero")},0);
        PreventScrolling.disableScrolling();
      }
    })
  }

  rightPanelOpen(e)
  {
    var self = this;
    if(!isUserLoggedIn()){
      self.preloadRightPanel();
    }else{
      Analytics.event({category : 'Head', action : 'RburgOpen', label : 'NavBar', nonInteraction : true});
      PreventScrolling.disableScrolling();
      self.setState({
        showRightPanel:true,
        showRightPanelLoader:false
      })
      self.clickScrollFlag = true;
      let menuBox = document.querySelector('#menu-right');
      if(menuBox)
      {
        setTimeout(function(){menuBox.classList.add("transZero")},0);
        PreventScrolling.disableScrolling();
      }
    }
    
  }

  trackHeaderClick(actionLabel)
  {
      Analytics.event({category : 'Head', action : actionLabel, label : 'NavBar'});
  }

  createGDPRCookie(userClicked = 1) {
    document.getElementById('mobAPPBanner').style.display = "none";
    var date      = new Date();
    var timestamp = Math.floor(date.getTime() / 1000);
    setCookie("gdpr", timestamp, 90, 'days');
    
    if(userClicked && isUserLoggedIn()) {
      getRequest(this.props.config.SHIKSHA_HOME+'/common/CookieBannerTracking/saveOldUserCookieData?from=pwa');
    }
  }

  onScroll(){
    if(!this.clickScrollFlag && !isUserLoggedIn()){
      if(!this.mobileApp){
        Hamburger.preload();
        RightPanel.preload();
      }
        SearchLayer.preload();
    }
    return;
  }

  componentDidMount() 
  {
    if(window.hideGdpr) {
        this.createGDPRCookie(0);
    }
	  if(getCookie('gdpr')) {
      document.getElementById('mobAPPBanner').style.display = "none";
    } else {
      document.getElementById('mobAPPBanner').style.display = "block";
    }
    var hashvalue = '' ;
    if(typeof window !='undefined'){
      hashvalue = window.location.hash;
      if( typeof hashvalue !='undefined' && hashvalue == '#banner=1' ){
        document.getElementById('mobAPPBanner').style.display = "none"; 
        return;
      }
    }
    if(isUserLoggedIn()){
      this.setState({
          showHamburgerPanel:true,
          showRightPanel:true
      })
      SearchLayer.preload();
    }else{
      window.addEventListener("scroll", this.onScroll);
      window.addEventListener("touchstart", this.onScroll);
      clearCTAInfo();
    }
  }
  


  setReferrer(){
    window.referrer = window.location.href;
  }

  render() {
    const {config} = this.props;
    
    let isPdfCall = false;
    let urlParams = getQueryVariable('isPdfCall', this.props.location.search);
    if(urlParams!=null && urlParams!='undefined' && urlParams){
      isPdfCall = true;
    }
    var hidClass ='';
    if(this.mobileApp){
      hidClass = 'hid';
    }
    
    return (
      <React.Fragment>
        <header id="page-header" className={'header ui-header-fixed '+hidClass} key='shiksha_header'>
        <input type="checkbox" id="offcanvas-menu" className="toggle" />
        
        {this.state.showHamburgerPanel && <Hamburger selectedStreamId = {this.state.selectedStreamId} selectedStreamName={this.state.selectedStreamName} tabsContentByCategory={this.state.tabsContentByCategory} nonZeroData={this.state.nonZeroData} rendered={this.state.rendered} updateHamburgerByUserStream={this.updateHamburgerByUserStream.bind(this)}/> }
         <input type="checkbox" id="offcanvas-right" className="toggle-right" />
        {this.state.showRightPanel && <RightPanel/>}

        {!isPdfCall ? <div className="cokkie-lyr display-none"  id="mobAPPBanner">

        <div className="cokkie-box">
          <p>We use cookies to improve your experience. By continuing to browse the site, you agree to our <a href={config.SHIKSHA_HOME+"/mcommon5/MobileSiteStatic/privacy"}>Privacy Policy</a> and <a href={config.SHIKSHA_HOME+"/mcommon5/MobileSiteStatic/cookie"}>Cookie Policy</a>.</p>
          <div className="tar"><a onClick={this.createGDPRCookie.bind(this)} className="cookAgr-btn">OK</a></div>
        </div>
        </div>:<div id="mobAPPBanner"></div>} 
          
        <div className="mys-notifyOvrly"></div>
        <div className="header slideShow"><label htmlFor="offcanvas-menu" className="hem-menu _hmPanel_" onClick={this.hamburgerOpen.bind(this)}>
            
            {this.state.showHamburgerLoader?<span className="hamb-loader"><img src="https://images.shiksha.ws/pwa/public/images/hamburger.gif" /></span>:''}
              <span className="icon-bar rippleefect"></span>
            </label> 
            
            { isPdfCall ? <a className="logo" href={config.SHIKSHA_HOME}><span className="msprite" >Shiksha.com</span></a> : <Link className="logo" to={{ pathname : '/'}} ><span className="msprite" >Shiksha.com</span></Link>}

            {!isPdfCall && <a onClick={this.openSocialLayer} id="_shareIcon" className="shareIcon"><span>&nbsp;</span></a>}

						{ isPdfCall ? <a href={config.SHIKSHA_HOME+'/searchLayer'} className="short-lst q-srch" id="_hsrchLayer"><span className="search rippleefect">&nbsp;</span></a> : <Link to={{ pathname : '/searchLayer'}}  onClick={this.setReferrer.bind(this)} className="short-lst q-srch" id="_hsrchLayer">
							<span className="search rippleefect">&nbsp;</span>
						</Link>}

            <a className="q-ask" id="_q-ask" href={config.SHIKSHA_HOME+"/mAnA5/AnAMobile/getQuestionPostingAmpPage"} onClick={ () => this.trackHeaderClick('Ask')}>
            <span className ="rippleefect"></span>
          </a>
              <label htmlFor="offcanvas-right" className="right-menu" onClick={this.rightPanelOpen.bind(this)}><span id="bellIcon" className="msprite rippleefect"></span><b className="notification" id="notification">0</b>
                    {this.state.showRightPanelLoader?<span className="bell-loader"><img src="https://images.shiksha.ws/pwa/public/images/notification.gif" /></span>:''}
              </label>
          <p></p>
        </div>
      </header>
        <DFPBannerLeaderBoard isMobile={true} bannerPlace="leaderboard"/>
        {this.state.enableSocialLayer && <SocialSharing openLayer={this.state.openLayer} closeSocialLayer={this.closeSocialLayer}/>}
    </React.Fragment>
    	)
  }
}

function mapStateToProps(state)
{
  return {
      config : state.config
  }
}

//export default Header;
export default connect(mapStateToProps)(withRouter(Header));

