import React, { Component } from 'react';
import '../../assets/desktop/Banner.css';
import PropTypes from 'prop-types';
import {rotateCoverBanner} from './../../utils/coverBanner';

class BannerComponent extends Component {
	  constructor(props){
  		super(props);
      this.timer     = null;
      this.bannerData= null;
      this.minLength = 0;
      this.maxLength = 0;
      this.refreshHomepageItems = this.refreshHomepageItems.bind(this);
  	}
 	  
    componentDidMount(){
      this.bannerData = this.props.bannerData;
      this.maxLength  = this.bannerData.length - 1;
      if(this.timer == null && this.bannerData != null){
        this.timer = setInterval(()=>{this.refreshHomepageItems()}, 10000);
      }
    }

    componentWillUnmount(){
      if(this.timer){
        clearTimeout(this.timer);
      }
    }

    refreshHomepageItems(){
      let index = this.getRandomIndex();
      rotateCoverBanner(this.bannerData[index], this.props.imageDomain); //to change homepage cover banners
    }

    getRandomIndex(){
      return Math.floor(Math.random() * (this.maxLength - this.minLength + 1)) + this.minLength;
    }

  	render(){
      if(this.props.bannerData == null){
        return null;
      }
      let defaultImage = (this.props.bannerData && typeof this.props.bannerData[0] != 'undefined') ? this.props.imageDomain+this.props.bannerData[0]['imageUrl'] : this.props.imageDomain+'/mediadata/images/1552472350phpCQtAo7.jpeg';
  		return(
        	<div id="search-background-search-page" className="search-background-search-page-transition" style={{ background: `url('https://images.shiksha.com/mediadata/images/1563446256phpJMXYYs.jpeg')` }}>  
            <a id="bannerUrl" href={(this.props.bannerData && typeof this.props.bannerData[0] != 'undefined') ?this.props.bannerData[0]['url']:null} rel="nofollow" target="_blank" className="cover-banner-link">
              <span className="banner-link-text" id="bannerText">{(this.props.bannerData && typeof this.props.bannerData[0] != 'undefined') ?this.props.bannerData[0]['title'] : null}</span>
              <span className="cover-banner-text-span">(view details)</span>
            </a>
            <div className="homePageBannerOvrly"></div>
          </div>
      )
  	}

}
BannerComponent.propTypes = {
  bannerData : PropTypes.array,
  imageDomain : PropTypes.string
}
export default BannerComponent;
