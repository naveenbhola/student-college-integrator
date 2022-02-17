import React from 'react';
import FullPageLayer from './../../../common/components/FullPageLayer';
import './../assets/filter.css';
import {getRequest} from './../../../../utils/ApiCalls';
import {Link, withRouter} from 'react-router-dom';
import Loader from './../../../reusable/components/Loader';
import APIConfig from './../../../../../config/apiConfig';
import Analytics from './../../../reusable/utils/AnalyticsTracking';
import StickyFooterSort from '../../../search/components/StickyFooterSort';
import {
	getObjectSize,
	getQueryVariable,
	parseQueryParams,
	splitPathQueryParamsFromUrl,
	convertObjectIntoQueryString
} from './../../../../utils/commonHelper';

function bindFunctions(functions)
{
	functions.forEach( f => (this[f] = this[f].bind(this)));
}


class Filters extends React.Component{
	constructor(props)
	{
		super(props);
		bindFunctions.call(this, [
			'openFilterLayer',
			'closeFilterLayer',
			'removeAllSeclectedFilters',
			'submitApplyFilters'

		]);
		this.state = {
			isOpen : false,
			layerHeading : 'Filter your search',
			selectedTab : 'location',
			filterChanged : false,
			showAllSelected : false,
			selectedFiltersCount : {},
			requestData : {},
			filtersWithSearch : {},
			locationFilter : [],
			loader : false,
			isShowLoader : true
		};
		this.selectedFilters = [];
		this.selectedOptions = [];
		this.specSelectedOptions = [];
		this.initailRequest = true;
		this.subStreamSpecMapping = {};
        this.removedUAF = [];
        this.removedFilterForSubmit = [];
	}
	componentDidMount()
	{
		const {filters} = this.props;
		if(getObjectSize(filters) === 0)
			return;
		let selectedCount = this.getSelectedFiltersCount(filters);
		this.initailRequest = true;
		let filtersWithSearch = {};
		this.props.filtersWithSearch.map((filterType)=>{
			filtersWithSearch[filterType] = this.props.filters[filterType];
		});
		this.setState({selectedFiltersCount : selectedCount,filters : this.props.filters, requestData : this.props.requestData, locationFilter : this.props.filters['location'], filtersWithSearch : filtersWithSearch});
		if(this.state.isOpen)
		{
			this.setFilterContainerHeight();
		}
		const self = this;
		window.addEventListener('resize',function(){
			if(self.state.isOpen)
				self.setFilterContainerHeight()
		});
	}
	componentDidUpdate()
	{
		if(this.state.isOpen)
		{
			this.setFilterContainerHeight();
		}
	}
	componentWillReceiveProps(nextProps)
	{
		const {prevfilters} = this.props;
		const {filters} = nextProps;
		if( getObjectSize(prevfilters) === 0 && getObjectSize(filters) > 0 && this.state.isOpen){
			this.openFilterLayer(nextProps);
		}
	}

	onFilterButtonPress = () => {
        this.trackEvent('Filter_Button','click');
        if(this.props.contentLoaderData){
        	this.props.contentLoaderData();
        }
        if(this.props.hasFilterData){
        	this.openFilterLayer(this.props);
		}else{
            this.openFilterLayer();
            this.props.onFilterButtonPress();
		}
	};

	onResetButtonPress = () => {
		this.trackEvent('reset','click');
		if(this.props.contentLoaderData){
			this.props.contentLoaderData();
		}
	}

	getSelectedFiltersCount(filters)
	{
		let selectedCount = {};
		for(let key in filters)
		{
			let tempFilterType = (key === 'sub_spec') ? 'specialization' : key;
			selectedCount[tempFilterType] = this.getSelectedFiltersCountPerKeyWise(filters[key]);
		}
		return selectedCount;
	}
	openFilterLayer(data = [], selectedTab = null)
	{
		this.selectedFilters = [];
		this.selectedOptions = [];
		this.specSelectedOptions = [];
		this.initailRequest = true;
		this.subStreamSpecMapping = {};
		let filters = data.filters;
		let selectedCount = this.getSelectedFiltersCount(filters);
		let isShowLoader = false;
		if(getObjectSize(filters) === 0)
		{
			isShowLoader = true;
		}
		let filtersWithSearch = {};
		if(getObjectSize(data) !=0){
			data.filtersWithSearch.map((filterType)=>{
				filtersWithSearch[filterType] = data.filters[filterType];
			});
		}
		if(getObjectSize(data) !== 0 && !selectedTab){
			this.setState({isOpen : true, filters : data.filters, requestData : data.requestData, locationFilter : data.filters['location'],
				selectedFiltersCount : selectedCount, showAllSelected : false, filterChanged : false, selectedTab : this.props.selectedTab,
				isShowLoader : isShowLoader,filtersWithSearch : filtersWithSearch});
		} else if(getObjectSize(data) !== 0 && selectedTab){
			this.setState({isOpen : true, filters : data.filters, requestData : data.requestData, locationFilter : data.filters['location'],
				selectedFiltersCount : selectedCount, showAllSelected : false, filterChanged : false, selectedTab : selectedTab,
				isShowLoader : isShowLoader,filtersWithSearch : filtersWithSearch},() => {
				setTimeout( () => {
					const sel = document.getElementById(selectedTab+'_li');
					sel.scrollIntoView();
				}, 200);

			});

		}
		else{
			this.setState({isOpen : true, isShowLoader : isShowLoader});
		}
	}
	closeFilterLayer()
	{
		this.backToResults();
	}
	backToResults()
	{
		this.setState({isOpen : false});
	}
	isActive(filterName)
	{
		return filterName === this.state.selectedTab;
	}
	changeDisplayFilter(filterName)
	{
		if(filterName === 'ranking_source'){
			this.trackEvent('publisher','click');
		}else{
			this.trackEvent(filterName,'click');
		}
		if(this.state.filterChanged && this.state.selectedTab !== filterName)
		{
			this.getFiltersData(this.removedUAF);
			for(let uafRemovedIndex in this.removedUAF) {
                if (this.removedFilterForSubmit.indexOf(this.removedUAF[uafRemovedIndex]) === -1) {
                    this.removedFilterForSubmit.push(this.removedUAF[uafRemovedIndex]);
                }
            }
           this.setState({loader : true});
		}
		this.setState({selectedTab : filterName});
	}
	generateCatPageUrl()
	{
		let selectedFilters = this.selectedFilters;
		const {shortName} = this.props;
		let appliedFilters = {};
        let uaFilters = {};
		selectedFilters.map(function(value)
		{
			uaFilters[value.optionType] = true;
			if(value.optionType === "sub_spec" || value.optionType === 'et_dm'){
				optionKey = shortName[value.optionType],
				optionValue = value.optionValue;
			}
			else{
				var optionValArr  = value.optionValue.split(/_(.+)?/),
				optionKey = optionValArr[0],
				optionValue = optionValArr[1];
			}
			if(optionKey !== '' && typeof optionKey != 'undefined')
			{
				if(typeof appliedFilters[optionKey] == 'undefined')
						appliedFilters[optionKey] = [];
				appliedFilters[optionKey].push(optionValue);
			}
		});
		if(this.props.withUAF){
			appliedFilters['uaf'] = this.getDefaultFilters();
		}
		if(this.props.isSrp){
			appliedFilters['uaf'] = this.getDefaultFilters(uaFilters);
			for(let uafType in this.removedFilterForSubmit){
				if(appliedFilters['uaf'].indexOf(this.removedFilterForSubmit[uafType]) === -1){
					appliedFilters['uaf'].push(this.removedFilterForSubmit[uafType]);
				}
			}
		}
		if(this.props.withInstituteId){
			appliedFilters["instituteId"] = [];
			appliedFilters["instituteId"].push(this.props.instituteId);
		}
		return this.convertObjectIntoQueryParams(appliedFilters);
	}
	convertObjectIntoQueryParams(data)
	{
		var str = "";
		for (var key in data) {
			for(var lkey in data[key])
			{
				if (str != "") {
		        str += "&";
			    }
			    if(this.props.isAllCoursesPage && key == "instituteId"){
			    	str += key +"=" + encodeURIComponent(data[key][lkey]);
			    }else{
			    	str += key+'[]' + "=" + encodeURIComponent(data[key][lkey]);
				}
			}
		}
		return str;
	}
	getDefaultFilters(appliedFilters = null)
	{
		const {defaultAppliedFilters,filters} = this.props;
		let params = [];
        let userAppliedFilters = defaultAppliedFilters;
		if(appliedFilters){
			userAppliedFilters = appliedFilters;
		}
		for(var filterType in userAppliedFilters)
		{
				switch(filterType){
					case 'city':
					case 'state':
						if(typeof filters['location'] != 'undefined'){
							!params.includes('location') ? params.push('location') : '';
						}
						break;
					case 'stream':
						break;
					case 'substream':
					case 'specialization':
						if(typeof filters['sub_spec'] != 'undefined'){
							params.push('sub_spec');
						}
						else if(typeof filters[filterType] != 'undefined'){
							params.push(filterType);
						}
						break;
					case 'education_type':
					case 'delivery_method':
						if(typeof filters['et_dm'] != 'undefined'){
							params.push('et_dm');
						}
						else if(typeof filters[filterType] != 'undefined'){
							params.push(filterType);
						}
						break;
					case 'course_level':
					case 'credential':
						if(typeof filters['level_credential'] != 'undefined'){
							params.push('level_credential');
						}
						else if(typeof filters[filterType] != 'undefined'){
							params.push(filterType);
						}
						break;
					default:
						if(typeof filters[filterType] != 'undefined'){
							params.push(filterType);
						}
						break;
				}
			}
		return params;
	}
	getFiltersData(uafRemoved = null)
	{
		if(this.props.isAllPredictorPage){
			this.setState({loader : false});
			return;
		}
		const selectedFilters = this.selectedFilters;
		window.selectedFilters = this.selectedFilters;
		window.selectedOptionsArr = this.selectedOptionsArr;
		window.selectedOptions = this.selectedOptions;
		let appliedFilters = {}, optionKey, optionValue;
		const {shortName} = this.props;
		selectedFilters.map(function(value)
		{
			if(value.optionType === "sub_spec" || value.optionType === 'et_dm'){
				optionKey = shortName[value.optionType];
				optionValue = value.optionValue;
			}
			else{
				let optionValArr  = value.optionValue.split(/_(.+)?/);
				optionKey = optionValArr[0];
				optionValue = optionValArr[1];
			}
			if(optionKey !== '' && typeof optionKey != 'undefined')
			{
				if(typeof appliedFilters[optionKey] == 'undefined')
						appliedFilters[optionKey] = [];
				appliedFilters[optionKey].push(optionValue);
			}
		});
        if(!this.props.isSrp && !this.props.isCutOffPage) {
        	if(this.props.urlHasParams && this.props.pathname)
            	appliedFilters['url'] = this.props.pathname;
        	else
				appliedFilters['url'] = this.props.pageUrl;
            appliedFilters['deviceType'] = 'mobile';
            appliedFilters['uaf'] = this.getDefaultFilters();
        }
		else if(this.props.withUAF){
			let uafParams = getQueryVariable('uaf', this.props.location.search);
			if(uafParams)
				appliedFilters['uaf'] = uafParams;
			if(uafRemoved && uafRemoved.length > 0) {
				if(!appliedFilters['uaf'])
                    appliedFilters['uaf'] = [];
				for(let uafRemovedIndex in uafRemoved) {
					if(appliedFilters['uaf'].indexOf(uafRemoved[uafRemovedIndex])  === -1)
                    appliedFilters['uaf'].push(uafRemoved[uafRemovedIndex]);
                }
            }
            appliedFilters = {...appliedFilters, ...parseQueryParams(splitPathQueryParamsFromUrl(this.props.pageUrl).search)};
		}else if(this.props.isCutOffPage){
			appliedFilters['url'] = this.props.pageUrl;
		}
        appliedFilters['rf'] = 'filterBucket';
		window.appliedFilters = appliedFilters;
		if(this.props.withInstituteId){
			appliedFilters["instituteId"] = this.props.instituteId;
		}
        if(!this.props.isSrp && appliedFilters['uaf'] && Array.isArray(appliedFilters['uaf'])  && appliedFilters['uaf'].indexOf('undefined') !== -1){
            delete appliedFilters['uaf'][appliedFilters['uaf'].indexOf('undefined')];
        }
		let params = btoa(JSON.stringify(appliedFilters));
		this.makeAsyncCall(params);
	}

	makeAsyncCall(params){
        let url = APIConfig.GET_CATEGORYPAGE_FILTERS;
		if(this.props.isSrp)
			url = APIConfig.GET_COLLEGE_SRP_FILTERS;
		if(this.props.isAllCoursesPage)
			url = APIConfig.GET_ALL_COURSE_PAGEA_FILTER;
		if(this.props.APIUrl)
			url = this.props.APIUrl;
		getRequest(url+'?data='+params).then((response) => {
			window.filterData = response.data.data;

				//check
				this.selectedFilters = [];
				this.selectedOptions = [];
				this.specSelectedOptions = [];
				this.initailRequest = true;
				this.subStreamSpecMapping = {};

				let selectedCount = this.getSelectedFiltersCount(response.data.data.filters);
				//this.setState({});
				////check end ,locationFilter : locationFilterData

				let filtersWithSearch = {};
				this.props.filtersWithSearch.map((filterType)=>{
					filtersWithSearch[filterType] = response.data.data.filters[filterType];
				});

				let locationFilterData = typeof response.data.data.filters.location != 'undefined' ? response.data.data.filters.location : [];
				window.locationFilterData = locationFilterData;
				this.setState({filters : response.data.data.filters, requestData : response.data.data.requestData, loader : false,filterChanged : false,isOpen : true, locationFilter:locationFilterData, selectedFiltersCount : selectedCount, filtersWithSearch: filtersWithSearch});
			});
	}
	renderFiltersLayOut()
	{
		let html = this.renderFiltersContainerHTML();
		return (
				<React.Fragment>
					{this.renderSelectedFiltersHTML()}
					{html}
					{this.renderApplyFilterButton()}
				</React.Fragment>
		)
	}
	getInputValue(filterType, event)
	{
		let searchText = event.target.value.trim();
		let filtersWithSearch;
		if(searchText.length > 0)
		{
			let filteredData = this.state.filters[filterType].filter(function(n,i){
	        return n.enabled && n.name.toLowerCase().indexOf(searchText.toLowerCase()) !== -1;
		    });
			filtersWithSearch = {...this.state.filtersWithSearch};
			filtersWithSearch[filterType] = filteredData;
			if(filterType === 'location'){
				this.setState({filtersWithSearch : filtersWithSearch, locationFilter : filteredData});
			}else{
				this.setState({filtersWithSearch : filtersWithSearch});
			}
		}
		else
		{
			filtersWithSearch = {...this.state.filtersWithSearch};
			filtersWithSearch[filterType] = this.state.filters[filterType];
			this.setState({filtersWithSearch : filtersWithSearch, locationFilter : this.state.filters['location']});
		}
	}
	rankingPageFilterHTML(filters, filterOrder){
		let filtersHtml = [];
		let leftBucketHtml = [];
		let rightBucketHtml = [];
		let fHtml = '';
		for(let ftype of filterOrder){
			if(!filters[ftype]){
				continue;
			}
			leftBucketHtml.push(<li key={ftype+"_li"} onClick={this.changeDisplayFilter.bind(this,ftype)} className={this.isActive(ftype) ? 'active' : ''}><a className={"ctp-filter-col"}>{this.props.displayName[ftype]}</a></li>);

			/*
			if(ftype === 'location'){
				fHtml = this.createLocationFilter(filters[ftype], ftype);
				rightBucketHtml.push(<div key={ftype+"_div"} className={"ctp-fltOptions " +(this.isActive(ftype) ? 'active' : 'hide')}><div className="ctp-srcFld"><i className="ctp-srcIcn"></i><input type="text" className="ctp-locFld" placeholder="Enter Location" ref={(input) => this.inputText = input} onChange={this.getInputValue.bind(this, ftype)}/></div>{fHtml}</div>);
			}else{
				fHtml = this.createStreamFilter(filters[ftype], ftype);
				rightBucketHtml.push(<div key={ftype+"_div"} className={"ctp-fltOptions " +(this.isActive(ftype) ? 'active' : 'hide')}>{fHtml}</div>);
			}
			*/
			fHtml = this.createList(filters[ftype], ftype);
			rightBucketHtml.push(<div key={ftype+"_div"} className={"ctp-fltOptions " +(this.isActive(ftype) ? 'active' : 'hide')}>{fHtml}</div>);

		}
		filtersHtml.push(<div className="ctp-filters" id="ctp-filters-id" key={"main_div"}><div id="ctp-filter-section" className="ctp-filter-section custom-filter-section"><ul id="ctp-filterList" className="ctp-filterList">{leftBucketHtml}</ul><div id="ctp-fltOptions">{rightBucketHtml}</div></div></div>);

		return filtersHtml;
	}
	renderFiltersContainerHTML()
	{
		const {displayName,filterOrder} = this.props;
		const {filters} = this.state;

		if(getObjectSize(filters) === 0)
			return null;



		let filtersHtml = [];
		let leftBucketHtml = [];
		let rightBucketHtml = [];
		if(this.props.filterLayerType === 'all_links'){
			return this.rankingPageFilterHTML(filters, filterOrder);
		}
		for(let ftype of filterOrder)
		{
            if(!filters[ftype]){
                continue;
            }
			let tempFilterType = (ftype === 'sub_spec') ? 'specialization' : ftype;
			let tempDisplayName = displayName[ftype];
			if(ftype === 'exam')
			{
				let tempArr = tempDisplayName.split(" ");
				tempDisplayName = [];
				tempDisplayName.push(<span key="fl">{tempArr[0]}</span>);
				let temp = tempArr[1];
				tempDisplayName.push(<span key="ml"><br/></span>);
				tempDisplayName.push(<span key="rl">{temp}</span>);
			}
			leftBucketHtml.push(<li key={ftype+"_li"} id={ftype+"_li"} className={this.isActive(ftype) ? 'active' : ''} onClick={this.changeDisplayFilter.bind(this,ftype)} data-var={ftype}><a className={"ctp-filter-col" + (ftype == 'exam' ? ' exmsaccpt' : '')}>{tempDisplayName}</a><span className="fltr-count" ref={ftype+'_count'}>{ this.state.selectedFiltersCount[tempFilterType] != 0 ? this.state.selectedFiltersCount[tempFilterType] : ''}</span></li>);

			let fHtml = this.createList(filters[ftype],ftype);
			rightBucketHtml.push(<div key={ftype+"_div"} className={"ctp-fltOptions " +(this.isActive(ftype) ? 'active' : 'hide')}>{fHtml}</div>);
			/*switch(ftype)
			{
				case 'location1':
						fHtml = this.createLocationFilter(filters[ftype],ftype);
						rightBucketHtml.push(<div key={ftype+"_div"} className={"ctp-fltOptions " +(this.isActive(ftype) ? 'active' : 'hide')}>{fHtml}</div>);
							break;
				case 'stream1':
						fHtml = this.createStreamFilter(filters[ftype],ftype);
						rightBucketHtml.push(<div key={ftype+"_div"} className={"ctp-fltOptions " +(this.isActive(ftype) ? 'active' : 'hide')}>{fHtml}</div>);
							break;
				default :
						fHtml = this.createList(filters[ftype],ftype);
						rightBucketHtml.push(<div key={ftype+"_div"} className={"ctp-fltOptions " +(this.isActive(ftype) ? 'active' : 'hide')}>{fHtml}</div>);
						break;

			}*/
		}
        filtersHtml.push(<div className="ctp-filters" id="ctp-filters-id" key={"main_div"}><div id="ctp-filter-section" className="ctp-filter-section custom-filter-section"><ul id="ctp-filterList" className="ctp-filterList">{leftBucketHtml}</ul><div id="ctp-fltOptions">{rightBucketHtml}</div></div></div>);
        this.initailRequest = false;
		return filtersHtml;
	}
	renderApplyFilterButton()
	{
		let hideClass = '';
		if(this.props.filterLayerType === 'all_links'){
			hideClass = 'hide';
		}
		return (<div className={"ctp-applySec "+hideClass} id="ctp-btn"><button className="button button--orange" type="button" onClick={this.submitApplyFilters}>Apply Filters</button></div>)
	}

    /*	
    createStreamFilter(streamData,ftype)
	{
		if(streamData.length > 0)
		{
			return this.createList(streamData,ftype);
		}
		return null;
	}

	createLocationFilter(locationData,ftype)
	{
		const {locationFilter} = this.state;
		if(locationFilter.length > 0)
		{
			return this.createList(locationFilter,ftype);
		}
		return null;
	}
	*/

	createList(filterData, filterType, isChildHtml = false, parentData)
	{
		if(this.props.filtersWithSearch.indexOf(filterType) !== -1){
			if(filterType === 'location'){
				filterData = this.state.locationFilter;
			}else{
				filterData = this.state.filtersWithSearch[filterType];
			}
		}
		if(filterData.length === 0 && this.props.filtersWithSearch.indexOf(filterType) === -1)
		{
			return null;
		}

		let filterHtml = [];
		const {shortName} = this.props;
		const {displayName} = this.props;
		let firstRequest = true;
		if(typeof window != 'undefined' && filterType === 'et_dm')
		{
			window.filterHtml = filterData;
		}
		for(let ldata in filterData)
		{
			var optionValue = shortName[filterData[ldata]['filterType']]+'_'+filterData[ldata]['id'];
			if(filterType === 'sub_spec' || filterType === 'et_dm' || (filterType === 'specialization' && isChildHtml))
			{
				optionValue = filterData[ldata]['id'];
			}
			var tempFilterType = (typeof parentData != 'undefined' && parentData[0].optionType === 'sub_spec') && isChildHtml ? 'sub_spec' : filterType;
			filterData[ldata]['checked'] ? this.initailRequest && this.addToSelectedFilters(filterData[ldata]['name'],tempFilterType,optionValue) : '';
			let countText = (filterData[ldata]['count'] && filterData[ldata]['count'] > 0) ? ' ('+filterData[ldata]['count']+')' : '';
			if(isChildHtml)
			{
				for(let pkey in parentData)
				{
					if(firstRequest)
						this.subStreamSpecMapping[parentData[pkey].optionValue] = [];

					this.subStreamSpecMapping[parentData[pkey].optionValue].push({optionName : filterData[ldata]['name'],optionType : tempFilterType,optionValue : optionValue,enabled : filterData[ldata]['enabled']});
					firstRequest = false;
				}
				if(typeof window != 'undefined')
					window.subStreamSpecMapping = this.subStreamSpecMapping;

			}
			let subHtml = [];
			if(typeof filterData[ldata]['childFilters'] != 'undefined')
			{
				for(let child in filterData[ldata]['childFilters'])
				{

					let parentData = [{optionName : filterData[ldata]['name'],optionType : tempFilterType,optionValue : optionValue}];
					subHtml.push(<span key={"child_"+child}>{this.createList(filterData[ldata]['childFilters'][child],child,true,parentData)}</span>);
				}
			}
			if(((filterType === 'category' || filterType === 'institute') && this.props.isCutOffPage) ||  (filterType === 'stream' && !this.props.isAllCoursesPage) || this.props.filterLayerType === 'all_links')
			{
				let searchParams = this.props.location;
				let streamUrl = filterData[ldata]['url'];
				if(filterType === 'category' && this.props.isCutOffPage){
					let params = parseQueryParams('&'+this.generateCatPageUrl());
					params['cat'] = [];
					params['cat'].push(filterData[ldata]['id']);
					let url = convertObjectIntoQueryString(params)
					searchParams = '?'+ url+ '&rf=filters';
					streamUrl = "";
				}else if(filterType === 'institute' && this.props.isCutOffPage){
					let examParams = "";
					if(this.state.requestData.appliedFilters.pageType == 'icox'){
						if(this.state.requestData.appliedFilters && this.state.requestData.appliedFilters.examIds.length != 0){
						    examParams ='?'+ this.props.shortName.exam +'[]='+this.state.requestData.appliedFilters.examIds[0];
						}
					}
					searchParams = '?'+ examParams+ '&rf=filters';
					streamUrl = filterData[ldata]['url'];
				}
				if(typeof searchParams != 'string' || searchParams.length == 0)
				{
					searchParams = "";
				}
				if(this.props.isSrp){
                    const searchedKeyword = getQueryVariable('q', this.props.location.search);
                    searchParams = '?q=' + encodeURIComponent(searchedKeyword) + '&s[]=' + filterData[ldata]['id'] +  '&rf=filters';
                    streamUrl = '/search'
				}
				if(this.props.filterLayerType === 'all_links'){
					if(filterType === 'ranking_source'){
						filterHtml.push(<li key={"list_"+ldata} className={filterData[ldata]['enabled'] ? 'enable' : 'disable'}><Link className={this.isOptionSelected(optionValue) ? 'checked' : ''} to={this.props.location.pathname+"?source="+filterData[ldata]['id']} onClick={this.handleRankingFilterClick.bind(this, 'publisher')}>{filterData[ldata]['name']+ ' '+countText}</Link>{subHtml}</li>);
					}else{
						if(this.isOptionSelected(optionValue)){
							filterHtml.push(<li key={"list_"+ldata} className={filterData[ldata]['enabled'] ? 'enable' : 'disable'}><a className={this.isOptionSelected(optionValue) ? 'checked' : ''} href="javascript:void(0);">{filterData[ldata]['name']}</a>{subHtml}</li>);
						}else{
							filterHtml.push(<li key={"list_"+ldata} className={filterData[ldata]['enabled'] ? 'enable' : 'disable'}><Link onClick={this.handleRankingFilterClick.bind(this, filterData[ldata]['filterType'])} className={this.isOptionSelected(optionValue) ? 'checked' : ''} to={{pathname : streamUrl, search : searchParams}}>{filterData[ldata]['name']}</Link>{subHtml}</li>);
						}
					}
				}else{
					filterHtml.push(<li key={"list_"+ldata} className={filterData[ldata]['enabled'] ? 'enable' : 'disable'}>
                                        <Link className={this.isOptionSelected(optionValue) ? 'checked' : ''} onClick={this.trackEvent.bind(this, 'Stream', 'Filters_click')}
                                                  to={{pathname : streamUrl, search : searchParams}}>{filterData[ldata]['name'] +' '+ countText}</Link>{subHtml}</li>);
				}
			}
			else
			{
				filterHtml.push(<li key={"list_"+ldata} className={filterData[ldata]['enabled'] ? 'enable' : 'disable'}>
					<a className={this.isOptionSelected(optionValue) ? 'checked' : ''} href="javascript:void(0)"
					   onClick={this.filterClick(filterData[ldata]['name'], tempFilterType, optionValue,false, parentData,false, displayName[filterType])}>{filterData[ldata]['name'] +' '+ countText}</a>{subHtml}</li>);
			}
		}
		let searchHtml;
		if(this.props.filtersWithSearch.indexOf(filterType) !== -1){
			searchHtml = <div className="ctp-srcFld"><i className="ctp-srcIcn"></i><input type="text" className="ctp-locFld" placeholder={"Search "+filterType} ref={(input) => this.inputText = input} onChange={this.getInputValue.bind(this, filterType)}/></div>;
		}
		return(
			<React.Fragment>
				{searchHtml}
				<ul className={"ctpLocList" +  (isChildHtml ? " ctp-SbList" : "")} ref={filterType+"_opts"}>
					{filterHtml}
				</ul>
			</React.Fragment>
			)
	}
	filterClick = (optionName, optionType, optionValue, isOnlyRemove, otherData, isOnlyAdd, filterLabel) =>  () => {
		this.trackEvent(filterLabel,'Filters_click');
		this.handleFilterOptionClick(optionName, optionType, optionValue, isOnlyRemove, otherData, isOnlyAdd);
	};
	handleRankingFilterClick = (filterLabel) => {
		if(filterLabel === 'state' || filterLabel === 'city'){
			filterLabel = 'location';
		}
		this.trackEvent(filterLabel, 'Filters_click');
	};

	handleFilterOptionClick(optionName,optionType,optionValue,isOnlyRemove = false,otherData,isOnlyAdd = false)
	{
		if(optionName.length > 38)
		{
			optionName = optionName.substr(0,37)+'...';
		}
		if(this.isOptionSelected(optionValue) && !isOnlyAdd)
		{
			this.removeFromSelectedFilters(optionType,optionValue,true);
			if(typeof otherData != 'undefined' && otherData !== '')
			{
				for(let key in otherData)
				{
					this.handleFilterOptionClick(otherData[key].optionName,otherData[key].optionType,otherData[key].optionValue,true);
				}
			}
			if(typeof this.subStreamSpecMapping[optionValue] != 'undefined' && !isOnlyRemove)
			{
				for(let key in this.subStreamSpecMapping[optionValue])
				{
					let keyData = this.subStreamSpecMapping[optionValue][key];
					if(typeof keyData.enabled != 'undefined' && keyData.enabled)
						this.handleFilterOptionClick(keyData.optionName,keyData.optionType,keyData.optionValue);
				}
			}
		}
		else if(!isOnlyRemove || isOnlyAdd)
		{
			this.addToSelectedFilters(optionName,optionType,optionValue,true);
            if(this.removedUAF.indexOf(optionType) === -1 && optionType && typeof optionType != 'undefined')
                this.removedUAF.push(optionType);
			if(typeof this.subStreamSpecMapping[optionValue] != 'undefined' && !isOnlyRemove)
			{
				for(let key in this.subStreamSpecMapping[optionValue])
				{
					let keyData = this.subStreamSpecMapping[optionValue][key];
					if(typeof keyData.enabled != 'undefined' && keyData.enabled)
						this.handleFilterOptionClick(keyData.optionName,keyData.optionType,keyData.optionValue,false,'',true);
				}
			}
		}
	}
	getSelectedFiltersCountPerKeyWise(options)
	{
		if(!Array.isArray(options))
			return 0;
		let childSelectedOptionsArr = 0;
		const self = this;
		let selectedOptionsArr = options.filter(function(value){
			return value.checked === true;
		});
		for(let i in options)
		{
			if(typeof options[i]['childFilters'] != 'undefined' && typeof options[i]['childFilters']['specialization'] != 'undefined' && Array.isArray(options[i]['childFilters']['specialization']) && options[i]['childFilters']['specialization'].length > 0)
			{
				childSelectedOptionsArr += self.getSelectedFiltersCountPerKeyWise(options[i]['childFilters']['specialization']);
			}
		}
		return (selectedOptionsArr.length > 0 || childSelectedOptionsArr > 0) ? parseInt(selectedOptionsArr.length)  + parseInt(childSelectedOptionsArr) : 0;
	}
	isOptionSelected(option)
	{
		return this.selectedOptions.includes(option);
	}
	addToSelectedFilters(optionName,optionType,optionValue,stateChangeCall = false)
	{
		if(this.isOptionSelected(optionValue))
		{
			return;
		}

		this.selectedFilters.push({optionName : optionName,optionValue : optionValue , optionType : optionType});
		this.selectedOptions.push(optionValue);
		if((optionType === 'sub_spec' || optionType === 'specialization' ) && !this.specSelectedOptions.includes(optionValue))
		{
			this.specSelectedOptions.push(optionValue);
		}
		if(stateChangeCall)
		{
			let updatedCount = 0;
			if(optionType === 'specialization' || optionType === 'sub_spec')
			{
				updatedCount = this.specSelectedOptions.length;
				optionType = 'specialization';
			}
			else
			{
				updatedCount = typeof this.state.selectedFiltersCount[optionType] != 'undefined' ? parseInt(this.state.selectedFiltersCount[optionType]) + 1 : 1 ;
			}
			let selectedFiltersCount = Object.assign({},this.state.selectedFiltersCount,{[optionType] : updatedCount });
			this.setState({filterChanged : true,selectedFiltersCount : selectedFiltersCount});
		}
	}
	removeFromSelectedFilters(optionType,optionValue,stateChangeCall = false)
	{
        if(this.removedUAF.indexOf(optionType) === -1 && optionType && typeof optionType != 'undefined') {
            this.removedUAF.push(optionType);
            if(this.removedFilterForSubmit.indexOf(optionType) === -1) {
                this.removedFilterForSubmit.push(optionType);
            }
        }
		this.selectedFilters = this.selectedFilters.filter(function (value) {
			return !(value['optionValue'] == optionValue)
		});
		let postion = this.selectedOptions.indexOf(optionValue);
		this.selectedOptions.splice(postion, 1 );

		if((optionType === 'specialization' || optionType === 'sub_spec') && this.specSelectedOptions.includes(optionValue))
		{
			let postion = this.specSelectedOptions.indexOf(optionValue);
			this.specSelectedOptions.splice(postion, 1 );
			optionType = 'specialization';
		}

		if(stateChangeCall)
		{
			let updatedCount = 0;
			if(optionType === 'specialization' || optionType === 'sub_spec')
			{
				updatedCount = this.specSelectedOptions.length;
				optionType = 'specialization';
			}
			else
			{
				updatedCount = parseInt(this.state.selectedFiltersCount[optionType]) - 1;
			}

			let selectedFiltersCount = Object.assign({},this.state.selectedFiltersCount,{[optionType] : updatedCount });
			this.setState({filterChanged : true,selectedFiltersCount : selectedFiltersCount});
			const subNamesArray = ['specialization','sub_spec'];
			if((this.state.selectedTab !== optionType && this.state.selectedTab !== 'sub_spec') || (this.state.selectedTab === 'sub_spec' &&
				subNamesArray.indexOf(optionType) == -1))
			{
				this.getFiltersData(this.removedUAF);
				this.setState({loader : true});
			}
		}
	}
	showAllSelectedFilters(className)
	{
		if(this.showAll.classList.contains(className)){
			this.showAll.classList.remove(className);
			this.showAll.classList.add('d-i');
		}
		if(!this.showMore.classList.contains(className))
		{
			this.showMore.classList.add(className);
		}
	}
	removeAllSeclectedFiltersForRanking(){
		if(this.props.filterLayerType === 'all_links'){
			Analytics.event({category : 'RANKING_PAGE_MOBILE', action : 'Reset Filters', label : 'Filters_click'});
			this.props.onResetFilter();
		}
	}
	removeAllSeclectedFilters()
	{
		this.trackEvent('Clear All', 'Filters_click');
        for(let sel in this.selectedFilters) {
			let optionType = this.selectedFilters[sel]['optionType'];
        	if(this.removedUAF.indexOf(optionType)  === -1 && optionType && typeof optionType != 'undefined'){
        		this.removedUAF.push(optionType);
			}
            if (this.removedFilterForSubmit.indexOf(optionType) === -1 && optionType && typeof optionType != 'undefined') {
                this.removedFilterForSubmit.push(optionType);
            }
		}
		this.selectedFilters = [];
		this.selectedOptions = [];
		this.specSelectedOptions = [];
		this.setState({selectedFiltersCount : {}, loader : true});
		this.getFiltersData(this.removedUAF);
	}
	renderSelectedFiltersHTML()
	{
		let selectedFilters = [];
		let hideSelectedFilters = [];
		for(let sel in this.selectedFilters)
		{
			if(this.state.showAllSelected || sel == 0)
			{
				selectedFilters.push(<div key={"selected_"+sel} className="select-box" key={this.selectedFilters[sel].optionValue} onClick={this.removeFromSelectedFilters.bind(this,this.selectedFilters[sel]['optionType'],this.selectedFilters[sel].optionValue,true)}>{this.selectedFilters[sel].optionName} <a href="javascript:void(0)" className="remove-city">&times;</a></div>);
			}else if(!this.state.showAllSelected)
			{
				hideSelectedFilters.push(<div key={"selected_"+sel} className="select-box" key={this.selectedFilters[sel].optionValue} onClick={this.removeFromSelectedFilters.bind(this,this.selectedFilters[sel]['optionType'],this.selectedFilters[sel].optionValue,true)}>{this.selectedFilters[sel].optionName} <a className="remove-city" href="javascript:void(0)">&times;</a></div>);
			}
		}
		if(hideSelectedFilters.length > 0)
		{
			selectedFilters.push(<div key="span" ref={ (showAll) => this.showAll = showAll} className="hide sltd-fltOpt">{hideSelectedFilters}</div>);
		}

		if(!this.state.showAllSelected && this.selectedFilters.length > 1)
		{
			selectedFilters.push(<a key="view-all" href="javascript:void(0);" data-var="1" className="cnt-morLnk" ref={ (showMore) => this.showMore = showMore} onClick={this.showAllSelectedFilters.bind(this,'hide')}>+{this.selectedFilters.length-1} more</a>);
		}


		if(this.selectedFilters.length == 0)
		{
			return null;
		}
		if(this.props.filterLayerType === 'all_links'){
			return (
				<div className="ctp-filtr-selectn">
					<a className="clear-all-rank" onClick={this.removeAllSeclectedFiltersForRanking.bind(this)} href="javascript:void(0);">RESET FILTERS</a>
				</div>
			);
		}
		return (
				<div className="ctp-filtr-selectn" id="ctp-filtr-selectn">
                    <a className="clear-all" onClick={this.removeAllSeclectedFilters}>Clear All</a>
                    <div className="fltr-ovrflw">
                        <div className="selectd-fltr sltd-fltOpt">
                           	{selectedFilters}
                        </div>
                    </div>
                </div>
			)
	}
    trackEvent(actionLabel,label)
    {
        if(typeof this.props.gaTrackingCategory === 'undefined' || !this.props.gaTrackingCategory)
            return;
        const categoryType = this.props.gaTrackingCategory;
        Analytics.event({category : categoryType, action : actionLabel, label : label});
    }
	submitApplyFilters()
	{
		let {history} = this.props;
		let url = this.generateCatPageUrl();
		let filterUrlParams = this.props.pageUrl+'?rf=filters&'+url;
		if(this.props.isSrp){
            filterUrlParams = this.props.pageUrl + '&' + url + '&rf=filters';
			this.removedUAF = [];
		}
		if(this.props.urlHasParams){
			filterUrlParams = this.props.pageUrl + '&' + url + '&rf=filters';
		}
		history.push(filterUrlParams);
		let categoryType = 'CATEGORY_PAGE_MOBILE';
		if(this.props.isSrp)
		    categoryType = 'Integrated_Search';
		else if(this.props.isAllCoursesPage){
		    categoryType = 'allCoursePage';
		}else if(this.props.isAllPredictorPage){
			categoryType = 'College_Predictor_Mobile';
		}else if(this.props.gaTrackingCategory){
			categoryType = this.props.gaTrackingCategory;
		}
		Analytics.event({category : categoryType, action : 'Apply Filters', label : 'Filters_click'});
		this.backToResults();
		
	}

	setFilterContainerHeight(){

		if(!document.getElementById('ctp-filters-id'))
			return null;
		const selFilHieght = document.getElementById('ctp-filtr-selectn') && typeof document.getElementById('ctp-filtr-selectn') != 'undefined' ? document.getElementById('ctp-filtr-selectn').offsetHeight : 0;
		const headingHeight = document.getElementById('pwa-hd').offsetHeight;
		const buttonHeight = document.getElementById('ctp-btn').offsetHeight;
        setTimeout(function () {
            const filterContHeight = parseInt(document.documentElement.clientHeight - (headingHeight + selFilHieght + buttonHeight));
            document.getElementById('ctp-filterList').style.height = "100%";
            document.getElementById('ctp-fltOptions').style.height = "100%";
            const lengthElements = document.getElementsByClassName('custom-filter-section').length;

            for(let i = 0 ;i < lengthElements;i++) {
            	document.getElementsByClassName('custom-filter-section')[i].style.minHeight = filterContHeight + 'px';
            	document.getElementsByClassName('custom-filter-section')[i].style.height = filterContHeight + 'px';
            }
        }, 200);
	}
	onFilterClick = filterType => () => {
		this.trackEvent('TCF_'+ filterType, 'click');
		this.openFilterLayer(this.props, filterType);
	};
	createTCF(){
		const {filterOrder, filters, displayName, selectedFiltersCount} = this.props;
		if(getObjectSize(filters) === 0 || getObjectSize(filterOrder) === 0 ) {
			return (<div onClick={this.onFilterButtonPress} className="flex filter-area non-tcf-filter">
				<div className="filterBlock">
				<i className="filter-icon"/>Filters <span className="filterCount">
					{selectedFiltersCount && selectedFiltersCount > 0 ? '(' + selectedFiltersCount + ')':''}</span>
			</div>
				</div>);
		}
		let tcfList = [];
		for(let filterType of filterOrder) {
			if(!filters[filterType])
				continue;
			tcfList.push(<li key={'TCF_'+filterType} onClick={this.onFilterClick(filterType)}
			><span className="filter-capsule">{displayName[filterType]}</span></li>)
		}
		return(
			<div className="flex filter-area">
				<div onClick={this.onFilterButtonPress} className="filterBlock">
					<i className="filter-icon"/>Filters <span className="filterCount">
					{selectedFiltersCount && selectedFiltersCount > 0 ? '(' + selectedFiltersCount + ')':''}</span>
				</div>
				<div className="optionBlock">
					<div className="sliderWrapper">
						<div className="filter-items"><span className="gredient-corner left"/>
							<span className="gredient-corner right"/>
						<ul className="filter-item-list">
							{tcfList}
						</ul></div>
					</div>
				</div>
			</div>
		);
	}

	render()
	{

		const {selectedFiltersCount,pageUrl} = this.props;
		return (
		<React.Fragment>
				<div className="fixed-card-wrapper" id="fixed-card-wrapper">
					<div className={this.props.showTCF ? "ctp-filter-sec" : "ctp-filter-sec dspTbl"  } id="fixed-card">
						{this.props.showTCF ? this.createTCF() :
							<a href="javascript:void(0)" id="filters_mobile" onClick={this.onFilterButtonPress}>Filters <span>
							{selectedFiltersCount && selectedFiltersCount > 0 ? '(' + selectedFiltersCount + ')':''}</span></a>}
							{this.props.showReset && (this.props.flagForReset || (selectedFiltersCount && selectedFiltersCount > 0)) ? <Link className='reset-link' to={pageUrl} onClick={this.onResetButtonPress}><i className='reset-ico'></i> Reset </Link>:''}
                        {this.state.isOpen && <FullPageLayer data={this.renderFiltersLayOut()} heading={this.state.layerHeading} onClose={this.closeFilterLayer} isOpen={this.state.isOpen} isNopadding={true} isShowLoader={this.state.isShowLoader}/>}
                        <Loader show={this.state.loader}/>
                    </div>
                </div>
                {this.props.pageFooterFilter ?  
                	<div className="ctp--sticky_filters">
				        <div className="filter_labels" onClick={this.onFilterButtonPress}>
				           <i className="filter-icon"/>
				            Filters <span>
							{selectedFiltersCount && selectedFiltersCount > 0 ? '(' + selectedFiltersCount + ')':''}</span>
							{this.props.showReset && (this.props.flagForReset || (selectedFiltersCount && selectedFiltersCount > 0)) ? <Link className='reset-link' to={pageUrl} ><i className='reset-ico'/> Reset </Link>:''}
				        </div>
						{this.props.showSort && <StickyFooterSort location={this.props.location} sortType={this.props.sortType}  pathname = {this.props.pathname} gaTrackingCategory={this.props.gaTrackingCategory} /> }
				    </div> : ''}
          </React.Fragment>      
                )
	}
}

Filters.defaultProps ={
    isSrp : false,
    isAllCoursesPage : false,
    isCutOffPage : false,
    selectedTab : 'location',
	urlHasParams : false,
	isAllPredictorPage : false,
	filtersWithSearch : ['location'],
	showTCF : true,
	showReset : false,
	withInstituteId : false,
	withUAF:true,
	pageFooterFilter: false,
	APIUrl: null,
	showSort : false
};

export default withRouter(Filters);
