import PropTypes from 'prop-types'
import React from 'react';
import {withRouter, Link} from 'react-router-dom';

import './../assets/RankingFilter.css';
import rankingConfig from './../config/rankingConfig';
import Analytics from './../../reusable/utils/AnalyticsTracking';

import FilterItems from './FilterItems';

let currScroll = 0, scrollTop = 0, isFilterFixed = false, filterHide = false;
class RankingFilter extends React.Component {
  constructor(props){
    super(props);
    this.state = {
      activeFilter : null
    };
    this.filterTopPos = 0;
    this.footerTopPos = 0;
  }
  componentDidMount(){
    this.bindScrollEvents();
  }
  componentWillUnmount(){
    this.unbindScrollEvents();
  }
  bindScrollEvents(){
    if(typeof window == 'undefined'){
      return;
    }
    this.filterTopPos = this.footerTopPos = 0;
    window.addEventListener('scroll', this.handleWindowScroll.bind(this));
  }
  unbindScrollEvents(){
    if(typeof window == 'undefined'){
      return;
    }
    window.removeEventListener('scroll', this.handleWindowScroll.bind(this));
  }
  handleWindowScroll(){
    this.handleFilterSticky();
  }
  handleFilterSticky(){
    currScroll = window.scrollY;
    if(typeof document.getElementById('rankingFilter') == 'undefined' || document.getElementById('rankingFilter') == null){
      return;
    }
    if(typeof document.getElementById('rankingFooterSection') != 'undefined' && document.getElementById('rankingFooterSection') != null && document.getElementById('rankingFooterSection').offsetTop > this.footerTopPos){
      this.footerTopPos = document.getElementById('rankingFooterSection').offsetTop;
    }
    if(typeof document.getElementById('rankingFilter') != 'undefined' && document.getElementById('rankingFilter') != null && document.getElementById('rankingFilter').offsetTop > this.filterTopPos){
      this.filterTopPos = document.getElementById('rankingFilter').offsetTop;
    }
    if(isFilterFixed == false && currScroll > scrollTop){ //scroll down
      if(currScroll > this.filterTopPos){
        isFilterFixed = true;
        document.getElementById('rankingFilter').classList.add('fixed-filters');
        document.getElementById('rankingTupleWrapper').style.marginTop = (document.getElementById('rankingFilter').offsetHeight + 37) + 'px';
      }
    }else if(isFilterFixed == true && currScroll < scrollTop){ //scroll up
      if(currScroll <= this.filterTopPos){
        isFilterFixed = false;
        document.getElementById('rankingFilter').classList.remove('fixed-filters');
        document.getElementById('rankingTupleWrapper').style.marginTop = '';
      }
    }
    if(this.footerTopPos-currScroll < 0 && filterHide == false){
      filterHide = true;
      document.getElementById('rankingFilter').style.display = 'none';
    }else if(this.footerTopPos-currScroll > 0 && filterHide == true){
      filterHide = false;
      document.getElementById('rankingFilter').style.display = 'block';
    }
    scrollTop = currScroll;
  }
  showFilter(filter){
    if(this.state.activeFilter == filter){
      this.setState({activeFilter : null});
    }else{
      if(filter != null){
        this.trackEvent(rankingConfig.filterConfig[filter].gaLabel, 'click');
      }
      this.setState({activeFilter : filter});
    }
  }
  hideFilter(){
    this.setState({activeFilter : null});
  }
  render(){
    let filterList = [], showSearch = true, finalList = [];
    let locationPopularCities = [], locationState = [], locationCity = [];
    this.props.filterOrder.forEach(
        (currFilter, i) => {
          if(currFilter != 'ranking_source' && typeof this.props.filters[currFilter] != 'undefined' && this.props.filters[currFilter].length > 8){
            showSearch = true;
          }else{
            showSearch = false;
          }
          finalList = this.props.filters[currFilter];
          if(currFilter == 'location'){
            this.props.filters[currFilter].forEach(
                location => {
                  if(rankingConfig.popularCities.indexOf(location.id) >= 0){
                    locationPopularCities.push(location);
                  }else if(location.filterType == 'state'){
                    locationState.push(location);
                  }else{
                    locationCity.push(location);
                  }
                }
            );
            finalList = [];
            if(locationPopularCities.length > 0){
              finalList.push({filterType : 'label', name : 'Popular Cities'});
              locationPopularCities.forEach(
                  loc => {
                    finalList.push(loc);
                  }
              );
            }
            if(locationState.length > 0){
              finalList.push({filterType : 'label', name : 'States'});
              locationState.forEach(
                  loc => {
                    finalList.push(loc);
                  }
              );
            }
            if(locationCity.length > 0){
              finalList.push({filterType : 'label', name : 'Cities'});
              locationCity.forEach(
                  loc => {
                    finalList.push(loc);
                  }
              );
            }
          }
          filterList.push(
              <div key={i} className={'filter_space pwa_dropbox'}>
                <p className="filter_title">{typeof rankingConfig.filterConfig[currFilter] != 'undefined' ? rankingConfig.filterConfig[currFilter].filterDisplayName : currFilter}</p>
                <FilterItems hideFilter={this.hideFilter.bind(this)} showFilter={this.showFilter.bind(this, currFilter)} activeFilter={this.state.activeFilter} showSearch={showSearch} gaTrackingCategory={this.props.gaTrackingCategory} itemName={currFilter} itemList={finalList} />
              </div>
          );
        }
    );
    return (
        <div className="inlineFlex">
          <div className="filter_space filter_heading">Filters :</div>
          {filterList}
          {this.props.resetUrl != this.props.location.pathname+this.props.location.search ? <div className="filter_space filter_reset"><Link onClick={this.trackEvent.bind(this, 'Reset Filters', 'Filters_click')} to={this.props.resetUrl} className="reset-filter">Reset All</Link></div> : null}
        </div>
    );
  }
  trackEvent(actionLabel,label){
    Analytics.event({category : this.props.gaTrackingCategory, action : actionLabel, label : label});
  }
}

RankingFilter.defaultProps = {
  gaTrackingCategory : 'RANKING_PAGE_DESKTOP'
};

export default withRouter(RankingFilter);

RankingFilter.propTypes = {
  filterOrder: PropTypes.any,
  filters: PropTypes.any,
  gaTrackingCategory: PropTypes.string,
  location: PropTypes.any,
  resetUrl: PropTypes.any
}