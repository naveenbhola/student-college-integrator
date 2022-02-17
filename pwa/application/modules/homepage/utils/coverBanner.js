import React from 'react';
export const rotateCoverBanner = (bannerData, imageDomain)=> {
    let ele    = document.getElementById('search-background-search-page');
    let gnbEle = document.getElementById('_globalNav');
    if(typeof bannerData == 'undefined' || gnbEle == null || ele == null) {
      return false;
    }
    
    ele.setAttribute('style', "background-image: url('"+imageDomain+bannerData['imageUrl']+"')");
    
    let gnbHeight = gnbEle.offsetHeight;
    if(gnbHeight<80){
        //ele.style.marginTop = '80px';
    }else{
        ele.style.marginTop = '';
    }

    // change text and url
    document.getElementById('bannerUrl').setAttribute('href', bannerData['url']);
    document.getElementById('bannerText').innerHTML = bannerData['title'];
}
