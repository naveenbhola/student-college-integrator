import PropTypes from 'prop-types'
import React, {Component} from "react";
import "../assets/OCF.css";
import {convertObjectIntoQueryString, parseQueryParams} from "../../../utils/commonHelper";
import {withRouter} from "react-router-dom";
import Analytics from "../../reusable/utils/AnalyticsTracking";

class OCF extends Component {
    constructor(props) {
        super(props);

    }
    trackEvent = (actionLabel, label) => {
        const categoryType = this.props.gaTrackingCategory;
        Analytics.event({category : categoryType, action : actionLabel, label : label});
    };
    onFilterClick = tuple => (event) => {
        this.trackEvent('OCF_'+ this.props.displayName[this.props.filterName], 'click');
        if(this.props.onClickHandler){
            this.props.onClickHandler(tuple);
        }else{
            const filterType = event.target.attributes.filtertype.value;
            const alias = this.props.shortName;
            const filterTupleType = tuple.filterType;
            let params;
            let shortName = alias[filterTupleType];
            if(filterType === 'sub_spec')
                shortName = alias[filterType];
            params = this.pruneUrlForFilters(this.props.location.search);
            if (!params[shortName]) {
                params[shortName] = [tuple.id];
            } else if (params[shortName].indexOf(tuple.id) === -1) {
                params[shortName].push(tuple.id);
            }
            if(!params['uaf'] || !Array.isArray(params['uaf'])){
                params['uaf'] = [filterType];
            } else if(params['uaf'].indexOf(filterType) === -1){
                params['uaf'].push(filterType);
            }
            params['rf'] = 'filters';
            const queryString = convertObjectIntoQueryString(params);
            let url = this.props.pageUrl;
            if(queryString){
                url += '?' + queryString;
            }
            this.props.history.push(url);
        }
    };
    allIndiaFilterClick = () => {
        this.trackEvent('OCF_'+ "Location", 'click');
        let params = this.pruneUrlForFilters(this.props.location.search, true);
        if(!params['uaf'] || !Array.isArray(params['uaf'])){
            params['uaf'] = ["location"];
        } else if(params['uaf'].indexOf("location") === -1){
            params['uaf'].push("location");
        }
        params['rf'] = 'filters';
        const queryString = convertObjectIntoQueryString(params);
        let url = this.props.pageUrl;
        if(queryString){
            url += '?' + queryString;
        }
        this.props.history.push(url);
    };
    pruneUrlForFilters(queryParams, allIndiaLocation = false){
        let paramsOld = {};
        if(queryParams)
            paramsOld = parseQueryParams(queryParams);
        let params = {};
        const alias = this.props.shortName;
        const {filters} = this.props;
        for(let key in filters){
            if(!filters.hasOwnProperty(key))
                continue;
            if(allIndiaLocation && key === "location"){
                continue;
            }
            const filterOptions = filters[key];
            for(let tuple of filterOptions) {
                if(!tuple.enabled || !tuple.checked) {
                    continue;
                }
                let shortName = alias[tuple.filterType];
                if(key === 'sub_spec'){
                    shortName = alias['sub_spec'];
                }
                if (!params[shortName]) {
                    params[shortName] = [tuple.id];
                } else if (params[shortName].indexOf(tuple.id) === -1) {
                    params[shortName].push(tuple.id);
                }
            }
        }
        if(paramsOld && paramsOld['uaf'] && Array.isArray(paramsOld['uaf']))
            params['uaf'] = paramsOld['uaf'];
        params['rf'] = 'filters';
        return params;
    }
    generateOCF(){
        const {filterName} = this.props;
        const filterType = filterName;
        if(!this.props.filters[filterName])
            return null;
        const filtersArray = this.props.filters[filterName];
        let ocfList = [], count = 0;
        if(this.props.showAllIndiaLocation && this.props.filterName === "location"){
            ocfList.push(<li key={"all_india_ocf"} filtertype = {filterType} onClick={this.allIndiaFilterClick}
            >All India</li>)
        }
        for(let filterData in filtersArray){
            if(!filtersArray.hasOwnProperty(filterData))
                continue;
            if(!filtersArray[filterData]['enabled'] || filtersArray[filterData]['checked'])
                continue;
            count++;
            if(count > 10)
                break;
            ocfList.push(<li key={this.props.filterName + filterData} filtertype = {filterType} onClick={this.onFilterClick(filtersArray[filterData])}
            >{filtersArray[filterData].name}</li>)
        }
        return (
            <div id="inline-filter" className="ocfTuple">
                <div className="ocf-filter-sec ctp-SrpLst" id="fixed-card">
                    <strong className="filter_name">Filter Colleges by {this.props.displayName[this.props.filterName]}</strong>
                    <div className="scroll_div">
                        <ul className="filterUl">
                            {ocfList}
                        </ul>
                    </div>
                </div>
            </div>
        );
    }
    render(){
        return this.generateOCF();
    }
} export default withRouter(OCF);

OCF.propTypes = {
  displayName: PropTypes.any,
  filterName: PropTypes.any,
  filters: PropTypes.any,
  gaTrackingCategory: PropTypes.string,
  showAllIndiaLocation: PropTypes.bool,
  location: PropTypes.any,
  onClickHandler: PropTypes.func,
  pageUrl: PropTypes.string,
  shortName: PropTypes.any
};

OCF.defaultProps = {
    showAllIndiaLocation: false
};