import PropTypes from 'prop-types'
import React from 'react';
import {withRouter, Link} from 'react-router-dom';

import rankingConfig from './../config/rankingConfig';
import {stringTruncate} from './../../../utils/stringUtility';
import Analytics from './../../reusable/utils/AnalyticsTracking';

class FilterItems extends React.Component {
  constructor(props){
    super(props);
    this.state = {
      itemList : this.props.itemList,
      searchedText : ''
    }
    this.selectedItem = null;
  }
  componentDidMount(){
    let self = this;
    document.addEventListener("click", function(e){
      if (typeof e.target.getAttribute('class') == 'undefined' || !e.target.getAttribute('class') || (typeof e.target.getAttribute('class') != 'undefined' && e.target.getAttribute('class') && e.target.getAttribute('class').indexOf('click-cls') < 0)){
        self.props.hideFilter();
      }
    });
  }
  trackEvent(actionLabel, label){
    if(actionLabel == 'state' || actionLabel == 'city'){
      actionLabel = 'location';
    }
    Analytics.event({category : this.props.gaTrackingCategory, action : actionLabel, label : label});
  }
  filterListItems(txtBox){
    let filteredData = null, searchText = txtBox.target.value.trim();
    if(searchText.length > 0){
      filteredData = this.props.itemList.filter(function(n){
        return n.name.toLowerCase().indexOf(searchText.toLowerCase()) !== -1;
      });
      this.setState({itemList : filteredData, searchedText : searchText});
    }else{
      this.setState({itemList : this.props.itemList, searchedText : ''});
    }
  }
  render(){
    let filterValues = [], item = null;
    this.state.itemList.forEach(
        (currItem, i) => {
          if(this.props.itemName == 'ranking_source'){
            item = <Link onClick={this.trackEvent.bind(this, 'publisher', 'Filters_click')} to={this.props.location.pathname+"?source="+currItem.id}>{currItem.name} <span>({currItem.count})</span></Link>;
          }else if(this.props.itemName == 'location'){
            if(currItem.filterType == 'label'){
              if(this.state.searchedText == ''){
                item = <a className="loc-label click-cls" href="javascript:void(0);">{currItem.name}</a>
              }
            }else{
              item = <Link onClick={this.trackEvent.bind(this, currItem.filterType, 'Filters_click')} to={currItem.url}>{currItem.name}</Link>;
            }
          }else{
            item = <Link onClick={this.trackEvent.bind(this, currItem.filterType, 'Filters_click')} to={currItem.url}>{currItem.name}</Link>;
          }
          if(currItem.checked == true){
            if(this.props.itemName == 'ranking_source'){
              this.selectedItem = <div onClick={this.props.showFilter.bind(this)} className="filter_drpdwn click-cls">{stringTruncate(currItem.name.trim(), 20)} <span className="click-cls">({currItem.count})</span><i className="pwa-dropico click-cls"></i></div>
            }else{
              this.selectedItem = <div onClick={this.props.showFilter.bind(this)} className="filter_drpdwn click-cls">{stringTruncate(currItem.name.trim(), 20)}<i className="pwa-dropico click-cls"></i></div>
            }
            item = <strong>{item}</strong>
          }
          filterValues.push(<li key={i}>{item}</li>);
        }
    );
    let searchBoxHtml = '';
    if(this.props.showSearch){
      searchBoxHtml = <div className="filter-searchbox"><input className="click-cls rp-filter-search" type="text" value={this.state.searchedText} autoFocus={true} placeholder={rankingConfig.filterConfig[this.props.itemName].searchPlaceholder} onChange={this.filterListItems.bind(this)} /></div>
    }
    return (
        <React.Fragment>
          {this.selectedItem}
          {this.props.activeFilter == this.props.itemName ? <div className="pwafltr-layer">{searchBoxHtml}<div className="list-wrap"><ul>{filterValues}</ul></div></div> : null}
        </React.Fragment>
    );
  }
}

FilterItems.defaultProps = {
  gaTrackingCategory : 'RANKING_PAGE_DESKTOP'
};

export default withRouter(FilterItems);

FilterItems.propTypes = {
  activeFilter: PropTypes.any,
  gaTrackingCategory: PropTypes.string,
  hideFilter: PropTypes.any,
  itemList: PropTypes.any,
  itemName: PropTypes.any,
  location: PropTypes.any,
  showFilter: PropTypes.any,
  showSearch: PropTypes.any
}