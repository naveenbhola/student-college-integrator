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
            <BannerComponent bannerData={this.props.bannerData}/>
            <SearchComponent listingsCount={this.props.listingsCount}/>
          </div>
      		<TopFeaturedCollegeComponent/>
		</div>
      )
  	}

}
export default TopComponent;