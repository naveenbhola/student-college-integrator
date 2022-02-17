import React from 'react';
import APIConfig from './../../../../config/apiConfig';
import {getRequest, postRequest } from './../../../utils/ApiCalls';
import {removeDomainFromUrlV2} from './../../../utils/urlUtility';

export function getShareUrl(){
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

export function getShareCount(){
    let pageUrl = removeDomainFromUrlV2(getShareUrl());
    return getRequest(APIConfig.GET_SOCIAL_COUNT+'?url='+btoa(pageUrl)).catch(function(err){});
}

export function trackSocialShare(deviceType, widgetPosition, sourceType, shareUrl){
    let data = {};
    data['device']      = deviceType;
    data['position']    = widgetPosition;
    data['shareSource'] = sourceType;
    data['url']         = removeDomainFromUrlV2(shareUrl);
    const axiosConfig   = { headers: {'Content-Type': 'application/json; charset=utf-8'}, withCredentials: true};
    postRequest(APIConfig.GET_SOCIAL_TRACKING_URL, data, '', axiosConfig).then((response) => {}).catch(function(err){});
}

export function isInt(n){
    return Number(n) === n && n % 1 === 0;
}

export function calculateShareCount(totalCount){
    let count      = parseInt(totalCount);
    let finalCount = 0;
    let thresold   = 1000;
    if(count>=thresold){
      finalCount = count/thresold;
      finalCount = finalCount.toFixed(1);
      finalCount = parseFloat(finalCount);
    }
    if(finalCount && isInt(finalCount)){ // return if number like 2.0 then 2
      finalCount = finalCount.toFixed(0);
      finalCount = parseFloat(finalCount);
    }
    return (finalCount) ? finalCount+'k' : count;
}