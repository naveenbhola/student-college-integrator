import React, { Component } from 'react';
import PropTypes from 'prop-types';
import BannerComponent from './BannerComponent';
import SearchComponent from './SearchComponent';
import TopFeaturedCollegeComponent from './TopFeaturedCollegeComponent';

class TopComponent extends Component {
	constructor(props)
  	{
  		super(props);
  	}
 	
  	render(){
  		return(
      	<div className="top-section-hp">
          <div className="relative banner searchComp">
            <BannerComponent bannerData={this.props.bannerData} imageDomain={this.props.imageDomain}/>
            <SearchComponent listingsCount={this.props.listingsCount}/>
          </div>
      		{this.props.collegeAds && <TopFeaturedCollegeComponent collegeAds={this.props.collegeAds}/>}
		</div>
      )
  	}

}
TopComponent.propTypes = {
  bannerData : PropTypes.array,
  imageDomain : PropTypes.string,
  listingsCount : PropTypes.object,
  collegeAds : PropTypes.object
}
export default TopComponent;