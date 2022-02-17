import React from 'react';
import './../assets/SocialSharingInner.css';
import APIConfig from './../../../../config/apiConfig';
import { postRequest } from './../../../utils/ApiCalls';
import {removeDomainFromUrlV2} from './../../../utils/urlUtility';

const trackSocialShare=(deviceType, widgetPosition, sourceType, shareUrl)=>{
    let data = new Object();
    data['device']      = deviceType;
    data['position']    = widgetPosition;
    data['shareSource'] = sourceType;
    data['url']         = removeDomainFromUrlV2(shareUrl);
    const axiosConfig   = { headers: {'Content-Type': 'application/json; charset=utf-8'}, withCredentials: true};
    postRequest(APIConfig.GET_SOCIAL_TRACKING_URL, data, '', axiosConfig).then((response) => {}).catch(function(err){});
}

const getShareUrl=()=>{
    if(typeof window != 'undefined' && window){
      let canonical = "";
      let links = document.getElementsByTagName("link");
      for (let i = 0; i < links.length; i ++) {
          if (links[i].getAttribute("rel") === "canonical") {
              canonical = links[i].getAttribute("href")
          }
      }
      return (canonical) ? canonical : window.location.href;
    }
}

const createSocialLink = (socialBucket, ignoreBucket, deviceType, widgetPosition, closeLayer)=>{
    let itemList = Array();
    let shareUrl = getShareUrl();
    Object.keys(socialBucket).forEach((index)=>{
        let ele = '';
        if(deviceType == 'desktop' && ignoreBucket.indexOf(index) != -1){
          return;
        }
        if(index == 'email'){
          ele = <a href={"mailto:?subject=I found an interesting link on Shiksha.com&body=Check out this link "+shareUrl+". For more details visit www.shiksha.com"} target="_blank"><i className={"social-icons "+index}></i><span className="social-name">{index}</span></a>;
        }else if(index == 'whatsapp'){
          ele = <a href={socialBucket[index]+"Check out this link "+shareUrl+". For more details visit www.shiksha.com"} target="_blank"><i className={"social-icons "+index}></i><span className="social-name">{index}</span></a>;
        }else{
          ele = <a href={socialBucket[index]+shareUrl} target="_blank"><i className={"social-icons "+index}></i><span className="social-name">{index}</span></a>;
        }
        if(typeof closeLayer == 'function' && closeLayer){
          itemList.push(<li key={index} onClick={()=>{trackSocialShare(deviceType, widgetPosition, index, shareUrl), closeLayer()}}>{ele}</li>);
        }else{
          itemList.push(<li key={index} onClick={()=>{trackSocialShare(deviceType, widgetPosition, index, shareUrl)}}>{ele}</li>);
        }
    });
    return itemList;
}

const SocialSharingInner = (props) => {
    let icons = createSocialLink(props.socialBucket, props.ignoreBucket, props.deviceType, props.widgetPosition , props.closeLayer);
    return (
        <React.Fragment>
          {props.shareHeading && props.shareHeading}
          <div className="sharing-box">
              <ul className="sharing-list flex-list">
                  {icons}
              </ul>
          </div>
        </React.Fragment>
    );
};

SocialSharingInner.defaultProps = {
    ignoreBucket : ['whatsapp'],
    socialBucket : {'facebook':'https://www.facebook.com/sharer/sharer.php?u=', 'whatsapp':'https://api.whatsapp.com/send?text=', 'twitter':'http://twitter.com/intent/tweet?url=', 'linkedin':'https://www.linkedin.com/shareArticle?mini=true&url=', 'email' : ''},
    deviceType : 'mobile'
};

export default SocialSharingInner;