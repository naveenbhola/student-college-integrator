import React from 'react';
import './../assets/Filters.css'
import {getObjectSize, parseQueryParams, convertObjectIntoQueryString, resetGNB, getQueryVariable} from "../../../utils/commonHelper";
import {withRouter} from 'react-router-dom';
import ContentLoaderCTPDesktop from "./ContentLoaderCTPDesktop";
import Analytics from "../../reusable/utils/AnalyticsTracking";

class DesktopFilters extends React.Component {
    constructor(props){
        super(props);
        this.state = {
            filterChanged : false,
            showAllSelected : false,
            selectedFiltersCount : {},
            filtersWithSearch : {},
            showLoader : true,
            hideBucket : {}
        }
    }
    componentDidMount() {
        const {filters} = this.props;
        if(getObjectSize(filters) === 0)
            return;
        const selectedCount = this.getSelectedFiltersCount(filters);
        let filtersWithSearch = {};
        this.props.filtersWithSearch.map((filterType)=>{
            filtersWithSearch[filterType] = this.props.filters[filterType];
        });
        this.setState({selectedFiltersCount : selectedCount, filters : this.props.filters, showLoader: false, filtersWithSearch : filtersWithSearch});
        this.lastScrollTop = 0;
    }
    
    componentWillReceiveProps(nextProps) {
        const prevfilters = this.props.filters;
        const {filters} = nextProps;
        if(getObjectSize(prevfilters) === 0 && getObjectSize(filters) > 0){
            const selectedCount = this.getSelectedFiltersCount(filters);
            let filtersWithSearch = {};
            nextProps.filtersWithSearch.map((filterType)=>{
                filtersWithSearch[filterType] = nextProps.filters[filterType];
            });
            this.setState({selectedFiltersCount : selectedCount, filters : nextProps.filters, showLoader: false, filtersWithSearch : filtersWithSearch});
        }
    }
    trackEvent = (actionLabel, label) => {
        const categoryType = this.props.gaTrackingCategory;
        Analytics.event({category : categoryType, action : actionLabel, label : label});
    };

    getSelectedFiltersCount(filters) {
        let selectedCount = {};
        for(let key in filters) {
            let tempFilterType = (key === 'sub_spec') ? 'specialization' : key;
            selectedCount[tempFilterType] = this.getSelectedFiltersCountPerKeyWise(filters[key]);
        }
        return selectedCount;
    }
    getSelectedFiltersCountPerKeyWise(options) {
        if(!Array.isArray(options))
            return 0;
        let childSelectedOptions = 0;
        let selectedOptionsArr = options.filter(function(value){
            return value.checked == true;
        });
        for(let i in options) {
            if(options[i]['childFilters'] && options[i]['childFilters']['specialization'] &&
                Array.isArray(options[i]['childFilters']['specialization']) && options[i]['childFilters']['specialization'].length > 0) {
                childSelectedOptions += this.getSelectedFiltersCountPerKeyWise(options[i]['childFilters']['specialization']);
            }
        }
        return (selectedOptionsArr.length > 0 || childSelectedOptions > 0) ? parseInt(selectedOptionsArr.length)  +
            parseInt(childSelectedOptions) : 0;
    }
    generateFilterHtml(){
        const {filterOrder} = this.props;
        const {filters} = this.state;
        if(getObjectSize(filters) === 0 || getObjectSize(filterOrder) === 0 ) {
            this.setState({showLoader: true});
            return null;
        }
        let allFilterHtml = [];
        for(let filterType of filterOrder) {
            if(!filters[filterType])
                continue;
            let filterData = filters[filterType];
            if(this.props.filtersWithSearch.indexOf(filterType) !== -1){
                filterData = this.state.filtersWithSearch[filterType];
            }
            const filterHtml = this.createFilterList(filterData, filterType);
            if(filterHtml === false) {
                continue;
            }
            allFilterHtml.push(filterHtml);
        }
        return allFilterHtml;
    }
    
    onReset = () => {
        resetGNB();
        this.trackEvent('Clear All', 'Filters_click');
        const {defaultAppliedFilters, filters} = this.props;
        let uafParams = [];
        if(this.props.isSrp){
            uafParams = getQueryVariable('uaf', this.props.location.search);
            if(!uafParams || !Array.isArray(uafParams))
                uafParams = [];
            for(let key in filters){
                if(!filters.hasOwnProperty(key))
                    continue;
                const filterOptions = filters[key];
                for(let tuple of filterOptions) {
                    if(!tuple.enabled || !tuple.checked) {
                        continue;
                    }
                    if(uafParams.indexOf(key) === -1){
                        uafParams.push(key);
                    }
                }
            }
            let params = [];
            params['uaf'] = uafParams;
            params['rf'] = 'filters';
            const queryString = convertObjectIntoQueryString(params);
            let url = this.props.pageUrl;
            if(queryString) {
                url += '&' + queryString;
            }
            if(this.props.isSrp) {
                const queryParams = url.slice(url.indexOf('?'));
                this.props.history.push(url, {'tabsData' : {...this.props.tabsData,
                    'collegeSRPQuery' : queryParams}});
                return ;
            }
            this.props.history.push(url);

        }
        for (let filterType in defaultAppliedFilters) {
            switch (filterType) {
                case 'city':
                case 'state':
                    if(typeof filters['location'] !== 'undefined'){
                        !uafParams.includes('location') ? uafParams.push('location') : '';
                    }
                    break;
                case 'stream':
                    break;
                case 'substream':
                case 'specialization':
                    if(typeof filters['sub_spec'] != 'undefined'){
                        !uafParams.includes('sub_spec') ? uafParams.push('sub_spec') : '';
                    }
                    else if(typeof filters[filterType] != 'undefined'){
                        !uafParams.includes(filterType) ? uafParams.push(filterType) : '';
                    }
                    break;
                case 'education_type':
                case 'delivery_method':
                    if(typeof filters['et_dm'] != 'undefined'){
                        !uafParams.includes('et_dm') ? uafParams.push('et_dm') : '';
                    }
                    else if(typeof filters[filterType] != 'undefined'){
                        !uafParams.includes(filterType) ? uafParams.push(filterType) : '';
                    }
                    break;
                case 'course_level':
                case 'credential':
                    if(typeof filters['level_credential'] != 'undefined'){
                        !uafParams.includes('level_credential') ? uafParams.push('level_credential') : '';
                    }
                    else if(typeof filters[filterType] != 'undefined'){
                        !uafParams.includes(filterType) ? uafParams.push(filterType) : '';
                    }
                    break;
                default:
                    if(typeof filters[filterType] != 'undefined'){
                        !uafParams.includes(filterType) ? uafParams.push(filterType) : '';
                    }
                    break;
            }
        }
        let params = [];
        params['uaf'] = uafParams;
        params['rf'] = 'filters';
        const queryString = convertObjectIntoQueryString(params);
        let url = this.props.pageUrl;
        if(queryString && !this.props.urlHasParams){
            url += '?' + queryString;
        }
        this.props.history.push(url);
    };
   
    onChangeFilters = tuple => (event) => {
        resetGNB();
        const {displayName} = this.props;
        const filterType = event.target.attributes.filtertype.value;
        this.trackEvent(displayName[filterType],'Filters_click');
        const alias = this.props.shortName;
        const filterTupleType = tuple.filterType;
        const checkedFlag = event.target.checked;
        if(filterType === 'stream' && !this.props.isAllCoursesPage ){
            let searchParams = this.pruneUrlForFilters(this.props.location.search);
            searchParams['rf'] = 'filters';
            let queryString = convertObjectIntoQueryString(searchParams);
           if(this.props.isSrp){
                const searchedKeyword = getQueryVariable('q', this.props.location.search);
                searchParams = '?q=' + encodeURIComponent(searchedKeyword) + '&s[]=' + tuple.id + '&' + queryString;
               this.props.history.push('/search'  + searchParams, {'tabsData' : {...this.props.tabsData,
                       'collegeSRPQuery' : searchParams}});
               return;
            }
            if(typeof queryString != 'string' || queryString.length === 0) {
                queryString = 'rf=filters';
            }
            this.props.history.push(tuple.url + '?' + queryString);
            return;
        }
        let params;
        let shortName = alias[filterTupleType];
        if(filterType === 'sub_spec')
            shortName = alias[filterType];
        params = this.pruneUrlForFilters(this.props.location.search);
        if(checkedFlag) {
            if (!params[shortName]) {
                params[shortName] = [tuple.id];
            } else if (params[shortName].indexOf(tuple.id) === -1) {
                params[shortName].push(tuple.id);
            }
        } else{
            if(params[shortName] && params[shortName].indexOf(tuple.id) !== -1){
                delete params[shortName][params[shortName].indexOf(tuple.id)];
                if(params[shortName].length === 0){
                    delete params[params.indexOf(shortName)];
                }
            }
        }
        if(!params['uaf'] || !Array.isArray(params['uaf'])){
            params['uaf'] = [filterType];
        } else if(params['uaf'].indexOf(filterType) === -1){
            params['uaf'].push(filterType);
        }
        params['rf'] = 'filters';
        const queryString = convertObjectIntoQueryString(params);
        let url = this.props.pageUrl;
        if(queryString && this.props.urlHasParams) {
            url += '&' + queryString;
        } else if(queryString){
            url += '?' + queryString;
        }
        if(this.props.isSrp) {
            const queryParams = url.slice(url.indexOf('?'));
            this.props.history.push(url, {
                'tabsData': {
                    ...this.props.tabsData,
                    'collegeSRPQuery': queryParams
                }
            });
        }
        else
            this.props.history.push(url);
    };
    pruneUrlForFilters(queryParams){
        let paramsOld = {};
        if(queryParams)
            paramsOld = parseQueryParams(queryParams);
        let params = {};
        const alias = this.props.shortName;
        const {filters} = this.state;
        for(let key in filters){
            if(!filters.hasOwnProperty(key))
                continue;
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
        if(paramsOld && paramsOld['sby'])
            params['sby'] = paramsOld['sby'];
        params['rf'] = 'filters';
        return params;
    }

    bucketClick = filterType => () =>{
      let {hideBucket} = this.state;
      hideBucket[filterType] = !hideBucket[filterType];
      this.setState({hideBucket : hideBucket});
    };
    getInputValue = (filterType, event) => {
        const searchText = event.target.value.trim();
        let filtersWithSearch;
        if(searchText.length > 0) {
            let filteredData = this.state.filters[filterType].filter(function(n,i){
                return n.enabled && n.name.toLowerCase().indexOf(searchText.toLowerCase()) !== -1;
            });
            filtersWithSearch = {...this.state.filtersWithSearch};
            filtersWithSearch[filterType] = filteredData;
            this.setState({filtersWithSearch : filtersWithSearch});
            return;
        }
        filtersWithSearch = {...this.state.filtersWithSearch};
        filtersWithSearch[filterType] = this.state.filters[filterType];
        this.setState({filtersWithSearch : filtersWithSearch});

    };
    generateLocationSearch(filterType){
        return(
        <div className="siderbar-search">
            <input type="text" placeholder={"Search "+filterType} autoComplete={'off'} name="search" onChange={this.getInputValue.bind(this, filterType)} />
        </div>
        );
    }
    createFilterList(filterData, filterType){
        const {displayName} = this.props;
        let enabled = false;
        let checked = false;
        const filtersList = filterData.map((tuple, i) =>
            <li key={tuple.filterType + '_' + tuple.id} className={tuple['enabled'] ? 'enable' : 'disable'}>
                {enabled = (enabled || tuple['enabled'])}
                {checked = (checked || tuple.checked)}
                <input onChange = {this.onChangeFilters(tuple)} className="filter-chck" name="filters" type="checkbox"
                       filtertype = {filterType} id={"chck-" + tuple.filterType + "-" + tuple.id} defaultChecked= {tuple.checked} />
                <label  className="checkbox-label lead" htmlFor={"chck-" + tuple.filterType + "-" + tuple.id}>{tuple.name} {tuple.count > 0 ? '('+tuple.count+')' : ''}</label>
            </li>
        );
        if(!enabled && this.props.filtersWithSearch.indexOf(filterType) === -1)
            return false;
        if(checked){
            this.showClearAll = true;
        }
        return (
            <div key ={'filterList'+filterType} className="filter-block">
                <p className={"filterHead "+(this.state.hideBucket[filterType] === true ? " down-arrw":"")} onClick={this.bucketClick(filterType)}>
                    <strong>{displayName[filterType]}<i className="icons ic_down" /></strong></p>
                <div className={"filter-content" + (this.state.hideBucket[filterType] === true ? " disable":'')}>
                    {(this.props.filtersWithSearch.indexOf(filterType) !== -1) ? this.generateLocationSearch(filterType) : ''}
                    <div className="fix-scroll">
                        <ul className="sidebar-filter">
                            {filtersList}
                        </ul>
                    </div>
                </div>
            </div>);
    }
    render(){
        let renderHtml;
        this.showClearAll = false;
        if(this.state.showLoader) {
            return <ContentLoaderCTPDesktop filterLoader={true}/>;
        }
        else
            renderHtml = this.generateFilterHtml();
        return (
            <React.Fragment>
                <label className="nav_main_head">Filters {this.showClearAll ? <a className="clear-all-filter" onClick={this.onReset} href="javascript:void(0);">Clear All</a> : ""}</label>
                {renderHtml}
            </React.Fragment>);
    }
}
DesktopFilters.defaultProps = {
    urlHasParams : false,
    filtersWithSearch : ['location']
};
export default withRouter(DesktopFilters);
