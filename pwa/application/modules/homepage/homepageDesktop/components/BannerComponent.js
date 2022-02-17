import React, { Component } from 'react';
import PropTypes from 'prop-types';
import '../assets/Banner.css';

class BannerComponent extends Component {
	constructor(props)
  	{
  		super(props);
  	}
 	
  	render(){
  		return(
      	<div id="search-background-search-page" className="search-background-search-page-transition" style={{backgroundImage: "url('https://images.shiksha.com/mediadata/images/1459318264phpOjE4ZG.jpeg')"}} data-type="default">  
          <a href="https://www.shiksha.com/trackCtr/16?url=https%3A%2F%2Fwww.shiksha.com%2Fmba%2Fresources%2Fask-current-mba-students" rel="nofollow" target="_blank" className="cover-banner-link"><span className="banner-link-text">Want to ask questions to current MBA Students?&nbsp;</span><span className="cover-banner-text-span">(view details)</span></a>
          <div className="homePageBannerOvrly"></div>
        </div>
      )
  	}

}
export default BannerComponent;