/**
 * Device : Destop
 * Desc   : Display share icon on GNB only, When click on the Icon then load social share js,css and images
 * author : akhter
 **/
import React, { Component } from 'react';
import Loadable from 'react-loadable';
const SocialSharing = Loadable({
  loader: () => import('./SocialSharing'/* webpackChunkName: 'SocialSharing' */),
  loading() {return null},
});

class SocialSharingIcon extends Component{ 
  
  constructor(){
    super();
    this.state = {
      enableSocialLayer : false
    }
    this.openSocialLayer  = this.openSocialLayer.bind(this);
  }

  componentDidMount(){
    window.addEventListener("mouseover", this.triggeredAction); 
  }

  componentWillUnmount(){
    window.removeEventListener("mouseover", this.triggeredAction);
  }

  triggeredAction=(event)=> {
      var ignoreEle = document.getElementById('shareIcon');
      var target = event.target;
      if (target === ignoreEle || ignoreEle.contains(target)) {
          return;
      }
      this.closeSocialLayer();
  }

  closeSocialLayer=()=>{
    if(this.state.enableSocialLayer){
        this.setState({enableSocialLayer : false});  
    }
  }

  openSocialLayer=()=>{
    if(this.state.enableSocialLayer){
      this.setState({enableSocialLayer : false});
    }else{
      this.setState({enableSocialLayer : true});
    }
  }

  render(){
    return (
      <React.Fragment>
        <span onClick={this.openSocialLayer} id="shareIcon" className="share-shiksha">
          <a ga-page="gnb" ga-attr="GNB_OPEN_STATE" ga-optlabel="Share_GNB"><i className="icons ic_social-share"></i>&nbsp;</a>
          {this.state.enableSocialLayer && <SocialSharing />}      
        </span>
      </React.Fragment>
    );  
  }
  
};
export default SocialSharingIcon;