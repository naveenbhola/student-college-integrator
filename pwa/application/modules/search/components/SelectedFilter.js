import React,{Component} from 'react';
import './../assets/SelectedFilter.css';
import {convertObjectIntoQueryString, getObjectSize, parseQueryParams} from "../../../utils/commonHelper";
import {withRouter} from "react-router-dom";

class SelectedFilters extends Component {
    constructor(props){
        super(props);
        this.state = {
            filters : [],
            showLoader : true,
            showViewMore : false
        }
    }
    componentDidMount() {
        const {filtersData} = this.props;
        if(getObjectSize(filtersData) === 0)
            return;
        if(document.getElementById('heightCheck') && document.getElementById('heightCheck').offsetHeight> 42){
            this.setState({filters : this.props.filtersData, showLoader: false, showViewMore : true});
        }
        else
            this.setState({filters : this.props.filtersData, showLoader: false});
    }
    componentWillReceiveProps(nextProps) {
        const prevfilters = this.props.filtersData;
        const {filtersData} = nextProps;
        if(getObjectSize(prevfilters) === 0 && getObjectSize(filtersData) > 0){
            this.setState({filters : nextProps.filtersData, showLoader : false});
        }
    }
    componentDidUpdate(prevProps, prevState, snapshot) {
        if(prevState.showViewMore === false && document && document.getElementById('heightCheck') &&
            document.getElementById('heightCheck').offsetHeight> 42){
            this.setState({showViewMore : true});
        }
    }

    generateSelectedFilterHTML(){
        const {filtersData} = this.props;
        let selectedFiltersHtml = [];
        for(let key in filtersData){
            if(!filtersData.hasOwnProperty(key))
                continue;
            const filterOptions = filtersData[key];
            for(let tuple of filterOptions) {
                if(!tuple.enabled || !tuple.checked)
                    continue;
                tuple.filterBucket = key;
                selectedFiltersHtml.push(<a key={'selectedF_'+tuple.name} onClick = {this.onRemove(tuple)} className="searchTags">{tuple.name}<span className="closeTag"><i
                    className="srpSprite ics">&times;</i></span> </a>);
            }
        }
        return selectedFiltersHtml;
    }
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
        if(paramsOld && paramsOld['uaf'])
            params['uaf'] = paramsOld['uaf'];
        return params;
    }
    onRemove = tuple => () => {
        const alias = this.props.shortName;
        const filterType = tuple.filterBucket;
        const filterTupleType = tuple.filterType;
        let params; let shortName = alias[filterTupleType];
        if(filterType === 'sub_spec')
            shortName = alias[filterType];
        params = this.pruneUrlForFilters(this.props.location.search);
        if(params[shortName] && params[shortName].indexOf(tuple.id) !== -1){
            delete params[shortName][params[shortName].indexOf(tuple.id)];
            if(params[shortName].length === 0){
                delete params[params.indexOf(shortName)];
            }
        }
        if(!params['uaf']){
            params['uaf'] = [filterType];
        } else if(params['uaf'].indexOf(filterType) === -1){
            params['uaf'].push(filterType);
        }
        params['rf'] = 'filters';
        const queryString = convertObjectIntoQueryString(params);
        let url = this.props.pageUrl;
        if(queryString && (this.props.isSrp || this.props.urlHasParams)) {
            url += '&' + queryString;
        } else if(queryString){
            url += '?' + queryString;
        }
        this.props.history.push(url);
    };
    viewMoreClick = () => {
        this.setState({showViewMore : false});
    };
    render(){
        if(this.state.showLoader)
            return '';
        const selectedFilters = this.generateSelectedFilterHTML();
        const showSelectedFiltersHeading = !!(selectedFilters && Array.isArray(selectedFilters) && selectedFilters.length > 0);
        return (
            <React.Fragment>
                <div className="selectedFilterBlock">
                    <div className={"multiTags" + (this.state.showViewMore ?  " filterTopMaxHeight" :"")} id="topFilters">
                        <div id="heightCheck">
                            {this.state.showViewMore ? <span className="viewMoreBtn" onClick={this.viewMoreClick}>View More</span> : ''}
                            {showSelectedFiltersHeading ? <strong>Selected Filters </strong> : ''}
                            {selectedFilters}
                        </div>
                    </div>
                </div>

            </React.Fragment>
        );
    }
}
SelectedFilters.defaultProps = {
    urlHasParams : false
};
export default withRouter(SelectedFilters);
