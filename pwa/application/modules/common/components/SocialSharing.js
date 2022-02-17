/**
 * Device : mobile
 * Desc   : Social sharing layer from header
 * author : akhter
 **/
import React, { Component } from 'react';
import SocialSharingInner from './SocialSharingInner';
import FullPageLayer from './FullPageLayer';

const getPageHeading =()=>{
    let h1Text = '';
    let tagObj = document.getElementsByTagName('h1');
    if(tagObj && tagObj.length>0 && tagObj[0]){
        h1Text = tagObj[0].innerText;
    }            
    return (h1Text) ? <div className="socialHeading h1">{h1Text}</div> : null;
};

const SocialSharing =(props)=>{
    let pageHeading = getPageHeading();
    let shareHtml   = <SocialSharingInner shareHeading={pageHeading} widgetPosition='header' closeLayer={props.closeSocialLayer}/>;      
    return (
        <FullPageLayer desktopTableData={false} data={shareHtml} heading={props.layerHeading} onClose={props.closeSocialLayer} isOpen={props.openLayer} />
    ); 
};

SocialSharing.defaultProps= {
  openLayer : false,
  layerHeading : 'Share' 
}
export default SocialSharing;