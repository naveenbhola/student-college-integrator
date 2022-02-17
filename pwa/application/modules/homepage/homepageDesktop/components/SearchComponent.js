import React, { Component } from 'react';
import PropTypes from 'prop-types';
import '../assets/Search.css';
import Anchor from './../../../reusable/components/Anchor';

class SearchComponent extends Component {
	constructor(props)
  	{
  		super(props);
  	}

  	render(){
  		return(
      <div className="pwadesktop-search">
        <div className="placeon-center">
            <div className="search-mainBox">
                <div className="search-heading"> <strong>Discover.</strong> <strong>Research.</strong> <strong>Decide.</strong> </div>
                <div className="search-items">
                    <ul className="search-itemsList">
                        <li> <strong>{this.props.listingsCount.colleges}+</strong> <span>Colleges</span> </li>
                        <li> <strong>{this.props.listingsCount.courses}+</strong> <span>Courses</span> </li>
                        <li> <strong>{this.props.listingsCount.reviews}+</strong> <span>Reviews</span> </li>
                        <li> <strong>{this.props.listingsCount.exams}+</strong> <span>Exams</span> </li>
                    </ul>
                </div>
                <Anchor to="/searchLayer">
                    <div className="pwadesktop-srchbox"> Search Colleges, Courses, Exams, Questions and Articles
                        <button type="button" name="button" className="srchBtnv1">Search</button>
                    </div>
                </Anchor>
            </div>
        </div>
      </div>
      )
  	}

}
export default SearchComponent;