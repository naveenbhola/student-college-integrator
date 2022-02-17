import React, { Component } from 'react';
import PropTypes from 'prop-types';
import '../../assets/desktop/Search.css';
import Anchor from './../../../reusable/components/Anchor';
import DesktopSearchLayer from '../../../search/components/DesktopSearchLayer';

class SearchComponent extends Component {
	constructor(props)
  	{
  		super(props);
        this.state = {
            isSearchLayerOpen : false
        }
  	}
    openSearchLayer = () => {
        window.referrer = window.location.href;
        this.setState({isSearchLayerOpen : true});
    };
    closeSearchLayer(){
        this.setState({isSearchLayerOpen : false});
    }

  	render(){
      if(this.props.listingsCount == null){
        return null;
      }
  		return(
      <div className="pwadesktop-search">
        <div className="placeon-center">
            <div className="search-mainBox">
                <div className="search-heading"><h1 className="pageHeading">Find Colleges, Courses &amp; Exams that are Best for You</h1></div>
                <div className="search-items">
                    <ul className="search-itemsList">
                        <li> <strong>{this.props.listingsCount.instCount}+</strong> <span>Colleges</span> </li>
                        <li> <strong>{this.props.listingsCount.shikshaCourses}+</strong> <span>Courses</span> </li>
                        <li> <strong>{this.props.listingsCount.reviewsCount}+</strong> <span>Reviews</span> </li>
                        <li> <strong>{this.props.listingsCount.examCount}+</strong> <span>Exams</span> </li>
                    </ul>
                </div>
                <div onClick={this.openSearchLayer.bind(this)} className="pwadesktop-srchbox"> Search Colleges, Courses, Exams, Questions and Articles
                    <button type="button" name="button" className="srchBtnv1">Search</button>
                </div>
                <DesktopSearchLayer isSearchLayerOpen={this.state.isSearchLayerOpen} onClose={this.closeSearchLayer.bind(this)} searchPage = {false}/>
            </div>
        </div>
      </div>
      )
  	}

}
SearchComponent.propTypes = {
  listingsCount : PropTypes.object
}
export default SearchComponent;