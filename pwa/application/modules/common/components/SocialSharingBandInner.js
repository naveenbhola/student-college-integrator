/**
 * Desc   : Social sharing Band Inner list
 * author : akhter
 **/
import React, { Component } from 'react';
import SocialShareConfig from './../config/SocialShareConfig';
import {trackSocialShare} from './../utils/socialShareHelper';

const SocialSharingBandInner=(props)=>{
    let itemList = Array();
    let shareUrl = (props.shareUrl) ? props.shareUrl : '';
    let socialBucket = SocialShareConfig.socialBucket;
    let ignoreBucket = SocialShareConfig.ignoreBucket;
    let shareText = (props.shareCount) ? <li key="12"><div className="rytPipe"><strong className="block">{props.shareCount}</strong><span className="block">SHARES</span></div></li> : <li key="12"><span className="sharethis">Share this : </span></li>;
    itemList.push(shareText);
    Object.keys(socialBucket).forEach((index)=>{
        let ele = '';
        if(props.deviceType == 'desktop' && ignoreBucket.indexOf(index) != -1){
          return;
        }
        if(index == 'email'){
          ele = <a href={"mailto:?subject=I found an interesting link on Shiksha.com&body=Check out this link "+shareUrl+". For more details visit www.shiksha.com"} target="_blank"><i className={"social-icons "+index}></i></a>;
        }else if(index == 'whatsapp'){
          ele = <a href={socialBucket[index]+"Check out this link "+shareUrl+". For more details visit www.shiksha.com"} target="_blank"><i className={"social-icons "+index}></i></a>;
        }else{
          ele = <a href={socialBucket[index]+shareUrl} target="_blank"><i className={"social-icons "+index}></i></a>;
        }
        itemList.push(<li key={index} onClick={()=>{trackSocialShare(props.deviceType, props.widgetPosition, index, shareUrl)}}>{ele}</li>);
    });
    return itemList;
}
SocialSharingBandInner.defaultProps= {
  widgetPosition : 'Inline'
}
export default SocialSharingBandInner;