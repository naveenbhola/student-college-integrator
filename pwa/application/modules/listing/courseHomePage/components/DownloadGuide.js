import React from 'react';
import Analytics from '../../../../modules/reusable/utils/AnalyticsTracking';
import config from './../../../../../config/config';
import PopupLayer from './../../../common/components/popupLayer';
import {getUrlParameter, removeQueryString, isUserLoggedIn, setCookie, getCookie} from './../../../../utils/commonHelper';
import APIConfig from './../../../../../config/apiConfig';
import { postRequest } from './../../../../utils/ApiCalls';

class DownloadGuide extends React.Component
{
   constructor(props)
   {
      super(props);
      this.state = {
         'isShikshaUser' : false
      }
   }

   componentDidMount(){
      var actionType = getUrlParameter('actionType');
      var actionTypeList = ['downloadGuide'];
      if(actionType !='' && actionTypeList.indexOf(actionType) != '-1' && this.props.category == 'CHP' && isUserLoggedIn()){
         setCookie('isChpNewUser',1,30);
         this.getLayer.open();
         this.startDownload();
         removeQueryString();
      }

      if(!isUserLoggedIn()){
         setCookie('isChpNewUser',0,30);
         setCookie('_chpCTA',0,30); // for click on shortlist/eb reload page
      }else{
         this.setState({'isShikshaUser':true});
         setCookie('_chpCTA',1,30);
      }
   }

   doTracking(){
      var data = new Object();
      data['id']        = this.props.chpData.chpId;
      data['trackingKey']  = 1895;
      data['isNewUser']    = getCookie('isChpNewUser');
      data['pageType']     = 'chp';
      data['action']     = 'download';
      const axiosConfig = { headers: {'Content-Type': 'application/json; charset=utf-8'}, withCredentials: true};
      postRequest(APIConfig.GET_CHP_GUIDE_TRACKING, data, '', axiosConfig).then((response) => {}).catch(function(err){});
   }

   gaTrackEvent(){
      let actionLabel = (this.props.deviceType == 'desktop') ? this.props.category+'_Desktop_'+this.props.action : this.props.category+'_'+this.props.action;
      Analytics.event({category : this.props.category, action : actionLabel, label : this.props.category+'_'+this.props.label});
   }

   openLayer(e){
      this.getLayer.open();
      this.doTracking();
      this.gaTrackEvent();
   }

   startDownload(){
      if(typeof document != 'undefined'){
         this.doTracking();
         let hrefUrl = (this.props.chpData.guideUrl) ? config().IMAGES_SHIKSHA+this.props.chpData.guideUrl : 'javascript:void();';
         document.getElementById('clickHere').setAttribute('href',hrefUrl);
         document.getElementById('clickHere').click();
      }
   }

   getHtml(){
      return(
            <div>Your download should start automatically. If it does not start automatically <a className="link" href={(this.props.chpData.guideUrl) ? config().IMAGES_SHIKSHA+this.props.chpData.guideUrl : 'javascript:void();'} target="_blank">Click Here</a> to manually download it.</div>
      );
   }

   doRegistration(regData){
      if(typeof(window) !='undefined' && typeof(downloadGuideCHP) !='undefined'){
         let referral = config().SHIKSHA_HOME+this.props.chpData.url+'?actionType=downloadGuide&fromwhere=CHP';
         let trackingKey = 1949;
         window.downloadGuideCHP(referral, trackingKey, regData);
         this.gaTrackEvent();
      } 
   }

   render(){
      var data = new Object();
      if(typeof this.props.chpData != 'undefined' && this.props.chpData != null && this.props.chpData.attributeMapping != null && this.props.chpData.attributeMapping.streamId>0){
         data['streamId']    = this.props.chpData.attributeMapping.streamId;
      }
      if(typeof this.props.chpData != 'undefined' && this.props.chpData != null && this.props.chpData.attributeMapping != null && this.props.chpData.attributeMapping.substreamId>0){
         data['substreamId']    = this.props.chpData.attributeMapping.substreamId;
      }
      if(typeof this.props.chpData != 'undefined' && this.props.chpData != null && this.props.chpData.attributeMapping != null && this.props.chpData.attributeMapping.specializationId>0){
         data['specializationId']    = this.props.chpData.attributeMapping.specializationId;
      }
      if(typeof this.props.chpData != 'undefined' && this.props.chpData != null && this.props.chpData.attributeMapping != null && this.props.chpData.attributeMapping.basecourseId>0){
         data['baseCourse']    = this.props.chpData.attributeMapping.basecourseId;
      }
      
      let registrationUrl = config().SHIKSHA_HOME+'/muser5/UserActivityAMP/getRegistrationAmpPage?actionType=downloadGuide&fromwhere='+this.props.fromwhere+'&referer='+Buffer.from(this.props.chpData.url).toString('base64')+'&attribute='+Buffer.from(JSON.stringify(data)).toString('base64')+'&widgetType='+this.props.widget;

      registrationUrl = (typeof(this.props.deviceType) != 'undefined' && this.props.deviceType == 'desktop') ? <a className={this.props.ctaClass} onClick={this.doRegistration.bind(this, data)} href="javascript:void(0);">{this.props.ctaText}</a> : <a className={this.props.ctaClass} onClick={this.gaTrackEvent.bind(this)} href={registrationUrl}>{this.props.ctaText}</a>

      let guidCTA = (this.state.isShikshaUser) ? <a className={this.props.ctaClass + ' button button--orange'} onClick={this.openLayer.bind(this)} href={(this.props.chpData.guideUrl) ? config().IMAGES_SHIKSHA+this.props.chpData.guideUrl : 'javascript:void();'} target="_blank">{this.props.ctaText} </a> : registrationUrl;
      return (
            <React.Fragment>  
               <PopupLayer onRef={ref => (this.getLayer = ref)} data={this.getHtml()} heading={"Downloading "+this.props.chpData.displayName+" Guide"} onContentClickClose={false}/>                   
               <div className="dnld-btn" id="_dg_">
                  {guidCTA}
               </div>
               <a key="1001" className="hide" target="_blank" id="clickHere">clickHere</a>
            </React.Fragment>
         )
   }
}
DownloadGuide.defaultProps = {
   'ctaText'  : 'Download Guide',
   'ctaClass' : 'button button--orange',
   'category' : 'CHP',
   'action' : 'Header',
   'label' : 'Download_Guide',
   'name'  :'<title>',
   'fromwhere' : 'CHP',
   'deviceType' : '',
   'widget':''
};
export default DownloadGuide;