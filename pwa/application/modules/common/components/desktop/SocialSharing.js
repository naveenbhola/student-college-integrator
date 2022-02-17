/**
 * Device : Desktop
 * Desc   : Social sharing layer from header
 * author : akhter
 **/
import React, { Component } from 'react';
import './../../assets/SocialSharingDesktop.css';
import SocialSharingInner from './../SocialSharingInner';

const SocialSharing = ()=>{ 
    return (
          <div id="socialSharingDesktop" className="social-sharing-popup">
            <div className="sharing-box-wrapper">
                <span className="box-pointer"></span>
                <SocialSharingInner deviceType="desktop" widgetPosition='header'/>
            </div>
          </div>      
  );  
};
export default SocialSharing;