import PropTypes from 'prop-types'
import React, {Component} from 'react';
import './../assets/StickySort.css';
import FullPageLayer from './../../common/components/FullPageLayer';
import {NavLink} from 'react-router-dom';
import {addQueryParams, removeParamFromUrl} from './../../../utils/commonHelper';
import Analytics from "../../reusable/utils/AnalyticsTracking";


class StickyFooterSort extends Component{
	constructor(props){
		super(props);
		this.state = {
			isOpen: false,
			layerHeading: 'Sort by',
            sortLabels:{
            	sponsered: 'Featured',
            	popularity:'Popularity', 
            	fees: 'Fees Low to High', 
            	feesDesc: 'Fees High to Low', 
            	rating:'Rating High to Low', 
            	ratingAsc:'Rating Low to High'
           }
		};
	}

	openSortLayer = () => {
	  this.setState({isOpen: true});
	};
 
    
    sortListData = () => {
    	const sortItems = this.state.sortLabels;
    	let sortType = this.props.sortType;
    	const {search} = this.props.location;
    	const{pathname} = this.props;
    	let location = pathname + search;
        const sortArray = [];
    	for(let labelName in sortItems){
    		if(!sortItems.hasOwnProperty(labelName))
    			continue;
    		sortArray.push(
              <li key={`sortlabel_${labelName}`} className='enable'>
                  <NavLink to={ labelName === 'sponsered' ? removeParamFromUrl('sby', location) :  addQueryParams('sby='+labelName, location)}
						   className={labelName === sortType ? 'checked': ''} onClick={this.trackEvent.bind(this, sortItems[labelName], 'click' )}>{sortItems[labelName]}</NavLink>
               </li>
    	   )
    	}

    	return sortArray;
    };


    renderSortHtmlData = () => {
       return (
            <div className='ctp-filters' id='ctp-filters-id'>
              <div className='ctp-filter-section custom-filter-section' id='ctp-filter-section'>
                 <div id='ctp-fltOptions'>
                    <div className='ctp-fltOptions active fullwidth'>
                      <ul className='ctpLocList'>
                         {this.sortListData()}
                      </ul>
                    </div>
                 </div>
              </div>
            </div>
       	)
    };

	closeSortlayer = () =>  {
		this.setState({isOpen: false})
	};

	render(){
		return(
	    <React.Fragment>		
			<div className="filter_labels" onClick={this.openSortLayer}>
			  <i className="sort_ico"/>
			   Sort
			</div>
			{this.state.isOpen && <FullPageLayer data={this.renderSortHtmlData()} heading={this.state.layerHeading} onClose={this.closeSortlayer} isOpen={this.state.isOpen} isNopadding={true} isShowLoader={false}/>}
	    </React.Fragment>		
	   )	
	}

	trackEvent(actionLabel, label) {
		Analytics.event({category: this.props.gaTrackingCategory, action: actionLabel, label: label});
	}
}


export default StickyFooterSort;

StickyFooterSort.propTypes = {
  location: PropTypes.any,
  pathname: PropTypes.string,
  sortType: PropTypes.string.isRequired
};
