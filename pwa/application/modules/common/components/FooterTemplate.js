import React, { Component } from 'react';
import URLMapping from '../config/ShikshaURLMapping';
import { connect } from 'react-redux';
import {getUrlParameter, setCookie, getCookie, showToastMsg, removeQueryString, getEBCookie, setEBCookie, retainRecoCTAState,retainFeeDetailsCTAState, isUserLoggedIn, retainQueryVariable} from './../../../utils/commonHelper';
import {DFPBannerTempalte} from './../../reusable/components/DFPBannerAd';
import Anchor from './../../reusable/components/Anchor';
import Loadable from 'react-loadable';
import {ShowLoader} from './../../reusable/components/Loader';

const BackTop = Loadable({
  loader: () => import('./../../common/components/BackTop'/* webpackChunkName: 'BackTop' */),
  loading() {return null},
});

const RecoLayer = Loadable({
  loader: () => import('./RecoLayer' /*webpackChunkName: 'recolayer' */),
  loading : ShowLoader,
});

class Footer extends Component {
  constructor(props)
  {
        super(props);
        this.state = {
            activeTabId : '',
            recoCourseId:0,
            enableBackToTop : false
        }
        this.updateOnlineStatus = this.updateOnlineStatus.bind(this);
  }

  componentDidMount(){
    if(isUserLoggedIn()){
        setTimeout(()=>{this.triggerCTAAction()},1000);
    }
    window.addEventListener("scroll", this.enableScrolling);
    window.addEventListener('online',  this.updateOnlineStatus);
    window.addEventListener('offline', this.updateOnlineStatus);
  }

  enableScrolling=()=>{
    let wScroll = window.scrollY;
    if(wScroll>100 && !this.state.enableBackToTop){
        this.setState({enableBackToTop : true});
    }
    this.checkStickybannerCrossButton();	  
  }

  updateOnlineStatus=()=> {
    if(typeof navigator !='undefined' && !navigator.onLine){
      showToastMsg('You are currently offline', '', true);
    }else{
      showToastMsg('You are now online');
    }
  }

  checkStickybannerCrossButton = () => {
      if(document.getElementById('stickyBanner') && document.getElementById('crossStickyBanner') && document.getElementById('stickyBanner').offsetHeight>0){
        document.getElementById('crossStickyBanner').style.display = "block";
      }
      if(document.getElementById('stickyBanner') && document.getElementById('crossStickyBanner') && document.getElementById('stickyBanner').offsetHeight == 0){
        document.getElementById('crossStickyBanner').style.display = "none";
      }
  }

  componentWillUnmount(){
      window.removeEventListener("scroll", this.checkStickybannerCrossButton);
      window.removeEventListener('online',  this.updateOnlineStatus);
      window.removeEventListener('offline', this.updateOnlineStatus);
  }

  loadShikshaCommonCss(){
    if (typeof commonCss == 'string' && commonCss!= '' && document.getElementById('shkCmn') && document.getElementById('shkCmn').href == '') {
        document.getElementById('shkCmn').href = commonCss;
    }
  }

  triggerCTAAction(){
    // these are the CTA action, will be triggered when user come from AMP
    var ampCTA = (getCookie('fromAMPCTA')) ? atob(getCookie('fromAMPCTA')).split('::') : [];
    var url = window.location.href;

    var actionType = getUrlParameter('action');
    var fromFeeDetails = getUrlParameter('fromFeeDetails');
    var actionTypeList = ['db','showContact', 'cd', 'sc'];

    if(url.indexOf('fromwhere=pwaResponseLogin') != '-1') {
      var listingType = getUrlParameter('listingType');
      var listingId   = getUrlParameter('listingId');
	  switch(actionType) {
        case 'downloadBrochure':
          if(fromFeeDetails=='true'){
            if(document.getElementById('getFeeDtl_'+listingId)){
              document.getElementById('getFeeDtl_'+listingId).click();
              var ele = document.getElementById("fees");
              ele.scrollIntoView({block: "start", inline: "nearest", behavior: 'smooth'});
            }
          }
          else{
            if(document.getElementById('brchr_'+listingId)){
                document.getElementById('brchr_'+listingId).click();
            }
          }
          removeQueryString();
          break;
        case 'shortlist':
          if(document.getElementById('shrt_'+listingId)){
            document.getElementById('shrt_'+listingId).click();
          }else if(document.getElementById('shrtBtn_'+listingId)){
            document.getElementById('shrtBtn_'+listingId).click();
          }
          removeQueryString();
          break;
        case 'contactDetails':
          if(document.getElementById('cntBtn'+listingId)){
            document.getElementById('cntBtn'+listingId).click();
          }
          removeQueryString();
          break;
        case 'applyOnline':
          if(document.getElementById('applynow')){
            document.getElementById('applynow').click();
          }
          removeQueryString();
          break;
        case 'compare':
          if(document.getElementById('compare'+listingId)){
            document.getElementById('compare'+listingId).click();
          }
          removeQueryString();
          break;
        case 'compareRightPanel':
          if(document.getElementById('compareRightPanel')){
            document.getElementById('compareRightPanel').click();
          }
          removeQueryString();
          break;
        case 'placement':
        case 'intern':
          if(document.getElementById(actionType)){
            document.getElementById(actionType).click();
          }
            removeQueryString();
          window.scrollTo(0,100);
          break;
        case 'exam_download_sample_paper': // retain CTA response for already have an account user only
        case 'exam_download_guide':
        case 'exam_download_prep_guide':
        case 'exam_download_prep_tip': 
        case 'downloadGuide':
          let clickId = getUrlParameter('clickId');
          this.clickCTA(clickId);
          break;
        case 'getFreeCounselling':
        case 'requestCallBack':
        case 'getAdmissionDetails':
        case 'getScholarshipDetails':
        case 'downloadTopQuestions':
        case 'downloadTopReviews':
        case 'downloadCourseList':
        case 'downloadCutoffDetails':
        if(document.getElementById(actionType+'_'+listingId)){
          document.getElementById(actionType+'_'+listingId).click();
        }
        removeQueryString();
        break;  
      }
      this.loadShikshaCommonCss();
    } else if(url.indexOf('fromwhere=MobileVerificationMailer') != '-1' || (actionType !='' && actionTypeList.indexOf(actionType) != '-1')) {
      
      var courseId = url.split('?');
      courseId     = (courseId.length) ? courseId[0].split('-') : [];
      courseId     = courseId[courseId.length-1];
      courseId     = (Number.isInteger(parseInt(courseId)))  ? parseInt(courseId) : 0;
      
      switch(actionType) {
        case 'db':
          if(document.getElementById('brchr_'+courseId)){
            document.getElementById('brchr_'+courseId).click();
          }
          break;
        case 'sc':
          if(document.getElementById('shrt_'+courseId)){
            document.getElementById('shrt_'+courseId).click();
          }else if(document.getElementById('shrtBtn_'+courseId)){
            document.getElementById('shrtBtn_'+courseId).click();
          }
          break;
        case 'cd':
        case 'showContact':
          if(document.getElementById('cntBtn'+courseId)){
            document.getElementById('cntBtn'+courseId).click();
          }
          break;
      }
      removeQueryString();
      this.loadShikshaCommonCss();
    } else {
      let clickId = getUrlParameter('clickId');
      let examCTAList = ['getUpdatesTop','getQuestionPaperTop','getUpdatesBottom','getQuestionPaperBottom'];
      let examFileList= ['samplePaperData','guidePaperData'];

      if(url.indexOf('=compare') !='-1' || url.indexOf('=shortlist') !='-1' || url.indexOf('=contact') !='-1' || url.indexOf('=placement') !='-1' || url.indexOf('=intern') !='-1' || url.indexOf('=applynow') !='-1' || url.indexOf('=brochure') !='-1' || url.indexOf('=checked_fee_details') !='-1'){
          var action = window.location.href.split('actionType=');
              action = (action.length) ? action[1].split('&') : [];
              action = (action.length) ? action[0] : '';
          var c = window.location.href.split('course=');
          c = (c.length && typeof(c[1]) !='undefined') ? c[1] : 0;
          var courseId = (c) ? c :0 ;
      }

      if(ampCTA.length>0 && (ampCTA[0] == 'brochure' || ampCTA[0] == 'checked_fee_details')){
        var fromFeeDetails = getUrlParameter('fromFeeDetails');
        if(fromFeeDetails == 'true'){
         // document.getElementById('getFeeDtl_'+listingId).click();
          var ele = document.getElementById("fees");
          ele.scrollIntoView({block: "start", inline: "nearest", behavior: 'smooth'});
          showToastMsg('Course Brochure Emailed Successfully');
        }
        else{
          if(document.getElementById('brchr_'+listingId)){
            document.getElementById('brchr_'+listingId).click();
            //showToastMsg('Course Brochure Emailed Successfully');
          }
        }
        removeQueryString();
        this.loadShikshaCommonCss();  
      }else if(ampCTA.length>0 && (ampCTA[0] == 'brochure' || ampCTA[0] == 'checked_fee_details') || ((action == 'brochure' || action == 'checked_fee_details') && courseId)){ // For loggedIn user from AMP to CLP
        if(getEBCookie(courseId)){
          showToastMsg('Course Brochure Emailed Successfully');
          if(document.getElementById('brchr_'+listingId)){
              document.getElementById('brchr_'+listingId).click();
          }
          removeQueryString();
        }else{
          showToastMsg('Course Brochure Emailed Successfully');
          setEBCookie(courseId);
          if(document.getElementById('brchr_'+listingId)){
              document.getElementById('brchr_'+listingId).click();
          }
          retainRecoCTAState(courseId);
          retainFeeDetailsCTAState(courseId);
        }
        this.loadShikshaCommonCss();  
      }else if(ampCTA.length>0 && ampCTA[0] == 'shortlist' || (action == 'shortlist' && courseId)){
        var c = (courseId) ? courseId : ampCTA[1];
        if(url.indexOf('fromCoursePage') !='-1'){
          var couseId = c.split('&');
          if(document.getElementById('shrt_'+couseId[0])){
            document.getElementById('shrt_'+couseId[0]).click();
          }else if(document.getElementById('shrtBtn_'+couseId[0])){
            document.getElementById('shrtBtn_'+couseId[0]).click();
          }
          removeQueryString();
        }
        else if(url.indexOf('fromCoursePage') =='-1' && ampCTA.length>0 && ampCTA[0] == 'shortlist'){
          this.setState({'recoCourseId':c});
        }
        this.loadShikshaCommonCss();  
      }else if(ampCTA.length>0 && ampCTA[0] == 'compare' || (action == 'compare' && courseId)){
        var c = (courseId) ? courseId : ampCTA[1];
        if(document.getElementById('compare'+c)){
          document.getElementById('compare'+c).click();
          setCookie('fromAMPCTA', '', 0, 'seconds');
          removeQueryString();
          this.loadShikshaCommonCss();  
        }
      }else if(getCookie('courContResp') == '' && action == 'contact' && courseId){
        var c = (courseId) ? courseId : ampCTA[1];
        if(document.getElementById('cntBtn'+c)){
          document.getElementById('cntBtn'+c).click();
          this.loadShikshaCommonCss();  
        }
      }else if(ampCTA.length>0 && ampCTA[0] == 'contact'){
        showToastMsg('Contact details have also been mailed to you.');
        setCookie('fromAMPCTA', '', 0, 'seconds');
        removeQueryString();
        this.loadShikshaCommonCss();  
      }else if(action == 'placement' || action == 'intern'){
        if(document.getElementById(action)){
          document.getElementById(action).click();
          removeQueryString();
          this.loadShikshaCommonCss();  
        }
      }else if(action == 'applynow'){
        if(document.getElementById(action)){
          document.getElementById(action).click();
          removeQueryString();
          this.loadShikshaCommonCss();  
        }
      }else if(examCTAList.indexOf(clickId) != -1){
          this.clickCTA(clickId);
      }else if(examFileList.indexOf(clickId) != -1){
          let files = clickId+'_'+getUrlParameter('fileNo');
          this.clickCTA(files);
      }
    }
  }

  clickCTA(clickId){
     setTimeout(()=>{
        if(document.getElementById(clickId)){
          document.getElementById(clickId).click();  
        }
      },100); 
     setTimeout(()=>{retainQueryVariable('course')},1500); 
  }

  attachClickEventListenerOnAccrdn()
  {
      var acrdnList = document.getElementsByClassName(
        "ftr-click"
      );
      var accordianList = ['stream-links','exam-links','university-links','resources-links','abroad-links','help-links'];
      var self = this;
      for (var i=0; i < acrdnList.length; i++) {
        var id = accordianList[i];
          acrdnList[i].onclick = function(id){
              if(self.state.activeTabId == id){
                self.setState({'activeTabId':''});
              }else{
                self.setState({'activeTabId':id});
              }           
          }
      };
  }
   
  markSelected(activeTabId)
  {
      if(this.state.activeTabId == activeTabId){
        this.setState({'activeTabId':''});
      } else {
        this.setState({'activeTabId':activeTabId});
      }
      
  }

  isActive(id)
  {
      return (id === this.state.activeTabId);
  }

  render() {
    return (
      <React.Fragment>


        {this.state.recoCourseId > 0 && <RecoLayer courseId={this.state.recoCourseId}/>}
      	<div className="main-footer" id="page-footer" data-enhance="false">
          <DFPBannerTempalte bannerPlace="footer"/>
          <ul className="clearfix" id="accordian-list">
            <li>
              <a href="javascript:void(0);" onClick={this.markSelected.bind(this,'stream-links')} className="ftr-click anchor-link">Streams <i className={this.isActive('stream-links') ? 'msprite footer-arr-up' : 'msprite footer-arr-dwn'}></i></a>

              <div className={this.isActive('stream-links') ? 'active link-box' : 'link-box'}>
                <a href={"/business-management-studies/colleges/colleges-india"}>BUSINESS & MANAGEMENT STUDIES</a><span className="link-sep"> | </span>
                <a href={"/engineering/colleges/colleges-india"}>ENGINEERING</a><span className="link-sep"> | </span>
                <a href={"/design/colleges/colleges-india"}>DESIGN</a><span className="link-sep"> | </span>
                <a href={"/hospitality-travel/colleges/colleges-india"}>HOSPITALITY & TRAVEL</a><span className="link-sep"> | </span>
                <a href={"/law/colleges/colleges-india"}>LAW</a><span className="link-sep"> | </span>
                <a href={"/animation/colleges/colleges-india"}>ANIMATION</a><span className="link-sep"> | </span>
                <a href={"/mass-communication-media/colleges/colleges-india"}>MASS COMMUNICATION & MEDIA</a><span className="link-sep"> | </span>
                <a href={"/it-software/colleges/colleges-india"}>IT & SOFTWARE</a><span className="link-sep"> | </span>
                <a href={"/humanities-social-sciences/colleges/colleges-india"}>HUMANITIES & SOCIAL SCIENCES</a><span className="link-sep"> | </span>
                <a href={"/arts-fine-visual-performing/colleges/colleges-india"}>ARTS ( FINE / VISUAL / PERFORMING )</a><span className="link-sep"> | </span>
                <a href={"/science/colleges/colleges-india"}>SCIENCE</a><span className="link-sep"> | </span>
                <a href={"/architecture-planning/colleges/colleges-india"}>ARCHITECTURE & PLANNING</a><span className="link-sep"> | </span>
                <a href={"/accounting-commerce/colleges/colleges-india"}>ACCOUNTING & COMMERCE</a><span className="link-sep"> | </span>
                <a href={"/banking-finance-insurance/colleges/colleges-india"}>BANKING, FINANCE & INSURANCE</a><span className="link-sep"> | </span>
                <a href={"/aviation/colleges/colleges-india"}>AVIATION</a><span className="link-sep"> | </span>
                <a href={"/teaching-education/colleges/colleges-india"}>TEACHING & EDUCATION</a><span className="link-sep"> | </span>
                <a href={"/nursing/colleges/colleges-india"}>NURSING</a><span className="link-sep"> | </span>
                <a href={"/medicine-health-sciences/colleges/colleges-india"}>MEDICINE & HEALTH SCIENCES</a><span className="link-sep"> | </span>
                <a href={"/beauty-fitness/colleges/colleges-india"}>BEAUTY & FITNESS</a><span className="link-sep"> | </span>
                <a href={"/sarkari-exams/exams-st-21"}>SARKARI EXAMS</a>
              </div>
            
            </li>
            <li>
              <a id="flE" href="javascript:void(0);" onClick={this.markSelected.bind(this,'exam-links')} className="ftr-click anchor-link">Top Exams <i className={this.isActive('exam-links') ? 'msprite footer-arr-up' : 'msprite footer-arr-dwn'}></i></a>

              <div className={this.isActive('exam-links') ? 'active link-box' : 'link-box'}>
                <p className="quick-link-head mrgnTop">MBA</p>
                <div>
                  <Anchor to={'/mba/cat-exam'}>CAT</Anchor><span className="link-sep"> | </span>
                  <Anchor to={'/mba/cmat-exam'}>CMAT</Anchor><span className="link-sep"> | </span>
                  <Anchor to={'/mba/snap-exam'}>SNAP</Anchor><span className="link-sep"> | </span>
                  <Anchor to={'/mba/xat-exam'}>XAT</Anchor><span className="link-sep"> | </span>
                  <Anchor to={'/mba/mat-exam'}>MAT</Anchor><span className="link-sep"> | </span>
                  <Anchor to={'/mba/nmat-by-gmac-exam'}>NMAT by GMAC</Anchor>
                </div>
                <p className="quick-link-head mrgnTop">Engineering</p>
                <div>
                  <Anchor to={'/b-tech/jee-mains-exam'}>JEE Main</Anchor><span className="link-sep"> | </span>
                  <Anchor to={'/b-tech/comedk-uget-exam'}>COMEDK</Anchor><span className="link-sep"> | </span>
                  <Anchor to={'/b-tech/gate-exam'}>GATE</Anchor><span className="link-sep"> | </span>
                  <Anchor to={'/b-tech/wbjee-exam'}>WBJEE</Anchor><span className="link-sep"> | </span>
                  <Anchor to={'/b-tech/upsee-exam'}>UPSEE</Anchor><span className="link-sep"> | </span>
                  <Anchor to={'/b-tech/jee-advanced-exam'}>JEE ADVANCED</Anchor>
                </div>

              </div>
             
            </li>
            <li>
              <a id="flSA" href="javascript:void(0);" onClick={this.markSelected.bind(this,'abroad-links')} className="anchor-link" >Study Abroad <i className={this.isActive('abroad-links') ? 'msprite footer-arr-up' : 'msprite footer-arr-dwn'}></i> </a>
              <div className={this.isActive('abroad-links') ? 'active link-box' : 'link-box'}>
                <p className="quick-link-head mrgnTop">Courses</p>
                <div>
                  <a title="BE/BTech abroad" href= {this.props.config.SHIKSHA_STUDYABROAD_HOME + "/be-btech-in-abroad-dc11510"}>BE/Btech</a><span className="link-sep"> | </span>
                  <a title="MS" href= {this.props.config.SHIKSHA_STUDYABROAD_HOME +"/ms-in-abroad-dc11509"}>MS</a><span className="link-sep"> | </span>
                  <a title="MBA" href= {this.props.config.SHIKSHA_STUDYABROAD_HOME + "/mba-in-abroad-dc11508"}>MBA</a><span className="link-sep"> | </span>
                  <a title="Law" href= {this.props.config.SHIKSHA_STUDYABROAD_HOME + "/bachelors-of-law-in-abroad-cl1245"}>Law</a><span className="link-sep"> | </span>
                  <a title="All Courses" href= {this.props.config.SHIKSHA_STUDYABROAD_HOME }>All Courses</a>
                </div>

                <p className="quick-link-head mrgnTop" >Countries</p>
                <div>
                  <a title="USA" href= {this.props.config.SHIKSHA_STUDYABROAD_HOME + "/usa"}>USA</a><span className="link-sep"> | </span>
                  <a title="UK" href= {this.props.config.SHIKSHA_STUDYABROAD_HOME + "/uk"}>UK</a><span className="link-sep"> | </span>
                  <a title="Canada" href= {this.props.config.SHIKSHA_STUDYABROAD_HOME + "/canada"}>Canada</a><span className="link-sep"> | </span>
                  <a title="Australia" href= {this.props.config.SHIKSHA_STUDYABROAD_HOME + "/australia"}>Australia</a><span className="link-sep"> | </span>
                  <a title="All Countries" href= {this.props.config.SHIKSHA_STUDYABROAD_HOME + "/abroad-countries-countryhome"}>All Countries</a>
                </div>

                <p className="quick-link-head mrgnTop">Exams</p>
                <div>
                  <a title="GRE" href= {this.props.config.SHIKSHA_STUDYABROAD_HOME + "/exams/gre"}>GRE</a><span className="link-sep"> | </span>
                  <a title="GMAT" href= {this.props.config.SHIKSHA_STUDYABROAD_HOME + "/exams/gmat"}>GMAT</a><span className="link-sep"> | </span>
                  <a title="SAT" href= {this.props.config.SHIKSHA_STUDYABROAD_HOME + "/exams/sat"}>SAT</a><span className="link-sep"> | </span>
                  <a title="IELTS" href= {this.props.config.SHIKSHA_STUDYABROAD_HOME + "/exams/ielts"}>IELTS</a><span className="link-sep"> | </span>
                  <a title="TOEFL" href= {this.props.config.SHIKSHA_STUDYABROAD_HOME + "/exams/toefl"}>TOEFL</a>
                </div>
              </div>
             
            </li>
            <li>
              <a id="flH" href="javascript:void(0);" onClick={this.markSelected.bind(this,'help-links')} className="anchor-link">Help <i className={this.isActive('help-links') ? 'msprite footer-arr-up' : 'msprite footer-arr-dwn'}></i></a>
              <div className={this.isActive('help-links') ? 'active link-box' : 'link-box'}>

                <a title="About Us" href={this.props.config.SHIKSHA_HOME + URLMapping.aboutusUrl}>About us</a><span className="link-sep"> | </span>
                <a title="Careers With Us" href="https://careers.shiksha.com/">Careers With Us</a><span className="link-sep"> | </span>
                <a id="flSHB" title="Student HelpLine" href={this.props.config.SHIKSHA_HOME + URLMapping.helplineUrl}>Student helpline</a><span className="link-sep"> | </span>
                <a title="Privacy" href={this.props.config.SHIKSHA_HOME + URLMapping.policyUrl}>Privacy policy</a><span className="link-sep"> | </span>
                <a title="Contact Us" href={this.props.config.SHIKSHA_HOME + URLMapping.contactusUrl}>Contact Us</a><span className="link-sep"> | </span>
                <a title="Terms of use" href={this.props.config.SHIKSHA_HOME + URLMapping.termUrl}>Terms of Use</a><span className="link-sep"> | </span>
                <a title="Shiksha Sitemap" href={this.props.config.SHIKSHA_HOME + "/sitemap"}>Sitemap</a>
              </div>
           
            </li>
          </ul>
          <section className="tac footer-child">
            <nav className="partner-nav">
              <strong>Partner Sites</strong>
              <p><a href="http://www.naukri.com" target="_blank">Jobs</a> | <a href="http://www.firstnaukri.com" target="_blank">Jobs for freshers</a> | <a href="http://www.naukrigulf.com" target="_blank">Jobs in MIddle East</a> | <a href="http://www.99acres.com" target="_blank"> Real Estate</a> | <a href="http://www.allcheckdeals.com" target="_blank" rel="nofollow">Real Estate Agents</a> |  <a href="http://www.jeevansathi.com" target="_blank">Matrimonial</a> | <a href="http://www.policybazaar.com" target="_blank" rel="nofollow"> Insurance Comparsion</a> |  <a href="http://www.meritnation.com" target="_blank" rel="nofollow">School Online</a> | <a href="http://www.brijj.com" target="_blank" rel="nofollow">Brijj </a> | <a href="http://www.zomato.com" target="_blank" rel="nofollow"> Zomato </a> | <a href="http://www.mydala.com/" target="_blank" rel="nofollow">mydala - Best deals in India</a> | <a href="http://www.ambitionbox.com/" target="_blank">Ambition Box</a></p>
            </nav>
            <aside>
              Trade Marks belong to the respective owners.Copyright © 2018 Infoedge India Ltd. All rights reserved
            </aside>
          </section>
          <div className="report-msg" id="report-msg"><p className="toastMsg" id="toastMsg"></p></div>
          {this.state.enableBackToTop && <BackTop/>}
        </div>
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


export default connect(mapStateToProps)(Footer);
