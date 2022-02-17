import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import {formatHeadings,togglePageCSSForFullPageLayer} from './../../../utils/commonHelper';

import PropTypes from 'prop-types';
import PreventScrolling from './../../reusable/utils/PreventScrolling';
import './../assets/FullPageLayer.css';

class FullPagePopup extends Component {
  constructor(props){
    super(props);
    this.callScrollFunction = this.callScrollFunction.bind(this);
  }

  render() {
    if(typeof window === 'undefined'){
      return null;
    }
    else{
    return ReactDOM.createPortal(
      this.prepareHtml(),
      document.getElementById('fullLayer-container'),
    );
    }
  }

  componentDidMount(){
    this.ScrollElementtoView();
  }

  componentWillUnmount(){
    togglePageCSSForFullPageLayer('remove');
    if(typeof this.props.closeLayer != 'undefined' && this.props.closeLayer){
      this.props.closeLayer;
    }
    window.removeEventListener("scroll", this.callScrollFunction);
  }

  componentWillUpdate(){
    this.ScrollElementtoView();
  }

  componentWillReceiveProps(){
     this.ScrollElementtoView();
  }

  callScrollFunction(){
    if(typeof this.props.scrollFunction == 'function' && this.props.scrollFunction){
        this.props.scrollFunction();
    }
  }

  ScrollElementtoView()
  {
      if(this.props.ScrollToElementId) {
          const {ScrollToElementId} = this.props;
          var ele = document.getElementById(ScrollToElementId);
          ele.scrollIntoView({block: "start", inline: "nearest", behavior: 'smooth'});
      }
  }


  prepareHtml() {
            let  pwaLayerClass = this.props.isAnimation ? 'pwa-lyr pwa-anim-lyr bcglayer' : 'pwa-lyr bcglayer' ;
            pwaLayerClass = this.props.heading.length>36 ? pwaLayerClass + 'recoHdng' : pwaLayerClass;
            pwaLayerClass = this.props.desktopTableData ? pwaLayerClass + ' ext-layer' : pwaLayerClass;
           return(
                    <div className={pwaLayerClass}>
                        {this.props.isHeaderAllowed ?
                        (<div className='pwa-hd' id="pwa-hd">
                            <p className='flLt'>{this.props.heading}</p>
                            <a href="javascript:void(0);" className='lyr-crs' onClick={this.props.closeLayer}>&times;</a>
                            <p className='clr'></p>

                        </div>) : null}
                        {this.props.showMailerMessage && <div className="subheadingv1">
                             <div className="higlightedBox">
                                You will also receive an email from SRM to verify your email id to apply for SRMJEEE. Please check your inbox.
                             </div>
                          </div>}
                        {this.props.subHeading && this.props.subHeading == 'true' && <div className="subHeading"><p>You may also be interested in the following:</p></div>}
                        {this.props.isSearchExist && this.props.searchHtml}
                        <div id="fullLayer-inner-content" onScroll={this.callScrollFunction} className={"pwa-cont" + (this.props.isNopadding ? '' : ' pad') + (this.props.isHeaderAllowed ? '' : ' searchV1')+' '+this.props.additionalCSS}>
                              {!this.props.isShowLoader ? this.props.innerHtml : <div style={{textAlign: 'center', marginTop: '7px', marginBottom: '10px', display: 'block'}} id="loader-id">
                        <img border="0" alt="" id="loadingImage1" className="small-loader" style={{borderadius:'50%',height: '40px',width: '40px'}} src="//images.shiksha.ws/public/mobile5/images/ShikshaMobileLoader.gif"/>
                    </div>}
                        </div>
                </div>

            );
  }
}

FullPagePopup.defaultProps ={
  isNopadding : false,
  additionalCSS : ''
};

class FullPageLayer extends Component {
  constructor(props) {
    super(props);
    this.state = {
      isLayerOpen: false
    };
  }

  beforeOpen()
  {
    const {isOpen} = this.props;
    if(this.props.disableScroll === true){
      PreventScrolling.disableScrolling();
    }
    if(this.props.desktopTableData == true){
      togglePageCSSForFullPageLayer('add');
    }
  }
  closeLayer()
  {
    const {onClose} = this.props;
    PreventScrolling.enableScrolling(true);
    if(this.props.desktopTableData == true){
      togglePageCSSForFullPageLayer('remove');
    }
    onClose();
  }

  render() {
    const {onClose,isOpen} = this.props;
    if(isOpen)
    {
      this.beforeOpen();
    }
    if(isOpen){


        return (      <div> 
                          <FullPagePopup innerHtml={this.props.data} closeLayer={this.closeLayer.bind(this)} heading={this.props.heading} isShowLoader={this.props.isShowLoader} isHeaderAllowed={this.props.isHeaderAllowed} isAnimation={this.props.isAnimationRequired} additionalCSS={this.props.additionalCSS} isNopadding={this.props.isNopadding } ScrollToElementId ={this.props.ScrollToElementId} isSearchExist={this.props.isSearchExist} desktopTableData = {this.props.desktopTableData} searchHtml ={this.props.searchHtml} subHeading={this.props.subHeading} showMailerMessage={this.props.showMailerMessage} scrollFunction={this.props.scrollFunction}/>
                      </div>
        );
    }
    else{
      return null; 
    }
  }
}

FullPageLayer.defaultProps = {
  isShowLoader : false, isHeaderAllowed : true, isAnimationRequired: true, disableScroll: true, isSearchExist : false, desktopTableData : false,showMailerMessage: false, additionalCSS : ''
};

FullPageLayer.propTypes =
{
   onClose : PropTypes.func

}
export default FullPageLayer;
