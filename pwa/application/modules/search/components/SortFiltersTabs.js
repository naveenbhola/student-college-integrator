import PropTypes from 'prop-types'
import React, {Component} from 'react';
import './../assets/SortFilterTabs.css';
import {withRouter, NavLink} from 'react-router-dom';
import {addQueryParams, removeParamFromUrl} from './../../../utils/commonHelper';
import Analytics from "../../reusable/utils/AnalyticsTracking";

class SortFiltersTabs extends Component{
	constructor(props){
		super(props);
		this.sortParams = ['sponsered', 'popularity', 'fees', 'rating'];
		this.sortLabels = {'sponsered' : 'Featured', 'popularity' : 'Popularity', 'fees' :'Fees',   'rating' : 'Rating', 'ratingAsc' : 'Rating', 'feesDesc' : 'Fees'};
	}

	renderSortLabelsHtmlData = () =>{
		const location = this.props.pathname + this.props.location.search;
		const sortType = this.props.sortType;
		const sortarray = [];
		let active = sortType;

       for(let index in this.sortParams){
       		let sortParam = this.sortParams[index];
	       	if(sortType === 'fees' && sortParam === 'fees'){
	  			active = 'feesDesc';
	       		sortParam = 'feesDesc';
	       	}else if(sortType === 'feesDesc' && sortParam === 'fees'){
	       		active = 'fees';
	       	}else if(sortType === 'rating' && sortParam === 'rating'){
                active = 'ratingAsc'
                sortParam = 'ratingAsc';
            } else if(sortType === 'ratingAsc' && sortParam === 'rating'){
                active = 'rating';
            }

           sortarray.push(
		   <NavLink
			   className={active === sortParam ? 'sort--label active' : 'sort--label'}
			   key={`sortlable_${sortParam}`} onClick={this.trackEvent.bind(this, this.sortLabels[sortParam], 'click' )}
			   to={ sortParam === 'sponsered' ? removeParamFromUrl('sby', location) : addQueryParams('sby='+sortParam, location)} >
				 {this.sortLabels[sortParam]}
		   </NavLink>
		 );
       }
       return sortarray;
	};
  
	render() {
		
		return(
             <div className="ctpSrp-contnr">
               <div className="ctp--top_filters">
                <div className="sortby--options">
                   <b>Sort by:</b>
                   <span className="sort-left">
                     {this.renderSortLabelsHtmlData()}
                    </span>
                </div>
              </div>
            </div>
		)
	}

	trackEvent(actionLabel, label) {
		Analytics.event({category: this.props.gaTrackingCategory, action: actionLabel, label: label});
	}
}

export default withRouter(SortFiltersTabs);

SortFiltersTabs.propTypes = {
  location: PropTypes.any,
  pathname: PropTypes.string,
  sortType: PropTypes.string.isRequired
};
