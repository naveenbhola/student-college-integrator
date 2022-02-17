import React, {Component} from 'react';
import Filters from './FiltersComponent';
import NotFound from './../../../common/components/NotFound';
import ZeroResultPage from './ZrpPage';
import {fetchCategoryPageData} from './../actions/CategoryPageAction';
import {connect} from 'react-redux';
import {bindActionCreators} from 'redux';
import {getRequest} from '../../../../utils/ApiCalls';
import RecoLinkWidget from './RecoLinkWidget';
import PreventScrolling from './../../../reusable/utils/PreventScrolling';
import ContentLoader from './../utils/contentLoader';
import {getCategoryGTMparams, generateCatPageUrl, getBeaconTrackData} from './../utils/categoryUtil';
import TagManager from './../../../reusable/utils/loadGTM';
import {defaultLocationLayerSteamIds} from './../config/categoryConfig';
import APIConfig from './../../../../../config/apiConfig';
import SingleSelectLayer from './../../../common/components/SingleSelectLayer';
import ElasticSearchTracking from './../../../reusable/utils/ElasticSearchTracking';
import {
    parseQueryString,
    getQueryVariable,
    getObjectSize,
    showToastMsg,
    isEmpty,
    pruneSrpQueryParams,
    getCookie,
    setCookie
} from '../../../../utils/commonHelper';
import {Redirect} from 'react-router-dom';
import './../assets/categoryPage.css';
import CategoryTableComponent from './CategoryTableComponent';
import ErrorMsg from './../../../common/components/ErrorMsg';
import {dfpBannerConfig, clearDfpBannerConfig} from './../../../reusable/actions/commonAction';
import {fetchCollegeSRPData, fetchIntegratedSRPData} from "../../../search/actions/SearchAction";
import {ExamSRP} from "../../../../../routes/loadableRoutes";
import Loadable from "react-loadable";
import BreadCrumbs from "../../../search/components/BreadCrumbs";
import ClientSeo from './../../../common/components/ClientSeo';
import CategoryPageContent from "../../../search/components/CategoryPageContent";
import {createHTMLObj, reformatLoadMoreCourses} from "../../../search/utils/searchUtils";
import {Link} from "react-router-dom";
import SocialSharingBand from './../../../common/components/SocialSharingBand';
import Analytics from "../../../reusable/utils/AnalyticsTracking";

const SRPNavbar = Loadable({
    loader: () => import('../../../search/components/SRPNavbar'/* webpackChunkName: 'SRPNavBar' */),
    loading() {return null}
});

class CategoryPage extends Component {
    constructor(props, context) {
        super(props, context);
        this.state = {
            isShowLoader: false,
            isOpen: false,
            customList: {},
            layerHeading: '',
            search: false,
            subHeading: false,
            placeHolderText: '',
            layerType: '',
            captionMsg: '',
            maxCount: 30,
            filters: {},
            aliasMapping: {},
            defaultAppliedFilters: {},
            filterOrder: {},
            loadMoreCourses: {},
            nameMapping: {},
            selectedFiltersCount: 0,
            totalCourseCount: 0,
            tabsData: '',
            enableSRPNavBar: false
        };
        this.detectPage();
        this.params = {
            listingAppBannerHeight: '0',
            listingScrollflag: false,
            listingRemovePaddingflag: false,
            listingScrollUpDownflag: false,
            st: 0,
            shikshaBannerHeight: 0,
            hashValue: null,
            nextHashValue: null
        };
        this.filterCall = false;
        this.isDfpData = false;

        this.filterTop = 0;
        this.footerPos = 0;
        this.makeSticky = this.makeSticky.bind(this); //bind function once
    }


    detectPage() {
        switch (this.props.hocData) {
            case "categoryPage" :
                this.isSrp = false;
                this.gaTrackingCategory = "CATEGORY_PAGE_MOBILE";
                break;
            case "collegeSrp" :
                this.isSrp = true;
                this.gaTrackingCategory = "COLLEGE_SRP_MOBILE";
                this.searchedKeyword = decodeURIComponent(parseQueryString(this.props.location.search)['q']);
                break;
        }
    }

    componentDidMount() {
        if (!this.isServerSideRenderedHTML()) {
            const {location} = this.props;
            if (this.isSrp)
                this.initialFetchSRPData(location);
            else
                this.initialFetchData(location);
        } else {
            if (!this.isErrorPage()) {
                this.lastScrollTop = window.scrollY;
                //stickyNavTabAndCTA();
                this.scrollStop();
                this.trackBeacon();
                this.openDefaultLocationLayer();
                if (this.props.location.state && this.props.location.state.tabsData && this.isSrp) {
                    SRPNavbar.preload().then(() => {
                        this.setState({enableSRPNavBar: true});
                        this.setTabsData(this.props.location);
                    });
                } else if (this.isSrp) {
                    this.getTabData();
                }
                this.showReadMoreBHST();
            }
            //this.preCacheFirstTuple();
        }
        window.addEventListener("scroll", this.onScroll);
        if (this.isSrp) {
            window.addEventListener('touchstart', this.loadExamSRP);
        }
        this.setPositionForSticky();
        window.addEventListener("scroll", this.makeSticky);
    }

    loadExamSRP = () => {
        ExamSRP.preload();
    };
    preloadNavBar = () => {
        SRPNavbar.preload().then(function () {
            this.setState({enableSRPNavBar: true});
        });
    };

    openDefaultLocationLayer() {
        const {categoryData} = this.props;
        const queryParams = this.props.location.search;
        if (this.isOpenLocationLayerOnLoad() && this.isAllIndiaRequest() && queryParams.indexOf("rf=filters") < 0) {
            const categoryInfoData = typeof categoryData.requestData !== 'undefined' && categoryData.requestData && typeof categoryData.requestData.categoryData !== 'undefined' ? categoryData.requestData.categoryData : {};
            this.getLocationLayer(categoryInfoData['streamId'], categoryInfoData['substreamId']);
        }
    }

    onScroll = () => {
        if (!this.state.filtersData && !this.filterCall) {
            this.getFiltersData();
        }
        if (!this.isDfpData) {
            this.isDfpData = true;
            this.dfpData();
        }
    };

    getLocationLayer(streamId, substreamId) {
        if (this.isErrorPage())
            return;
        let params = {};
        params['streamId'] = streamId;
        if (typeof substreamId !== 'undefined' && substreamId > 0)
            params['substreamId'] = parseInt(substreamId);
        this.setState({
            ...this.state,
            'customList': {},
            'isOpen': true,
            'layerHeading': 'Select your location',
            'search': true,
            'subHeading': false,
            'placeHolderText': 'Enter Location',
            captionMsg: 'To see relevant colleges, select preferred location(s):',
            layerType: 'location'
        });
        this.getFiltersData();
        PreventScrolling.disableScrolling();
    }

    formatForDefaultLocationLayer(filters) {
        var filtersData = [];
        if (typeof filters != 'undefined' && typeof filters['location'] != 'undefined' && Array.isArray(filters['location']) && filters['location'].length > 0) {
            filtersData = filters['location'].filter(function (value) {
                return value.enabled == true;
            })
        }
        if (Array.isArray(filtersData) && filtersData.length > 0) {
            this.displayList({'states': filtersData});
        } else {
            this.displayList(null);
        }
    }

    displayList(customData) {
        this.setState({'customList': customData});
    }

    trackGTM() {
        const {categoryData} = this.props;
        let gtmParams;
        if (categoryData && typeof categoryData.requestData != 'undefined') {
            gtmParams = getCategoryGTMparams(categoryData, this.state.aliasMapping);
            TagManager.dataLayer({dataLayer: gtmParams, dataLayerName: 'dataLayer'});
        }
    }

    trackBeacon() {
        const {categoryData, config} = this.props;
        if (categoryData && typeof categoryData.requestData !== 'undefined') {
            let trackingParams;
            if (this.isSrp) {
                trackingParams = {};
                trackingParams['pageIdentifier'] = 'search';
                trackingParams['pageEntityId'] = 0;
                trackingParams['extraData'] = {};
                trackingParams['extraData']['childPageIdentifier'] = 'collegeSRPPage';
            } else
                trackingParams = getBeaconTrackData(categoryData, 'search', 'categoryPage');
            ElasticSearchTracking(trackingParams, config.BEACON_TRACK_URL);
        }
    }

    componentWillReceiveProps(nextProps) {
        let nextHash = this.getBase64UrlParams(nextProps.location);
        let prevHash = this.getBase64UrlParams(this.props.location);
        if (prevHash !== nextHash) {
            if (this.isSrp)
                this.initialFetchSRPData(nextProps.location, true);
            else
                this.initialFetchData(nextProps.location, true);
        }
    }

    componentWillUnmount() {
        window.scroll = null;
        this.params.hashValue = null;
        this.params.nextHashValue = null;
        PreventScrolling.enableScrolling(false, true);
        if (PreventScrolling.canUseDOM()) {
            document.getElementById('page-header').style.display = "table";
            document.getElementById('page-header').style.position = "relative";
        }
        this.props.clearDfpBannerConfig();
        window.removeEventListener('scroll', this.onScroll);
        if (this.isSrp) {
            window.removeEventListener('touchstart', this.loadExamSRP);
        }
        window.removeEventListener("scroll", this.makeSticky);
    }

    setPositionForSticky=()=>{
        this.footerPos = (document.getElementById('page-footer')) ? document.getElementById('page-footer').offsetTop : 0;
        this.filterTop = (document.getElementById('fixed-card')) ? document.getElementById('fixed-card').offsetTop : 0;
    };

    // make filter sticky
    makeSticky = () =>{
        let wScroll = window.scrollY+1;
        let stickyEle = document.getElementById('fixed-card');
        if(stickyEle && wScroll > this.filterTop && wScroll<this.footerPos){
            stickyEle.classList.add('fix'); 
        }else if(stickyEle){
            stickyEle.classList.remove('fix');  
        }
    };


    isErrorPage() {
        let html404 = document.getElementById('notFound-Page');
        return (html404 && html404.innerHTML);
    }

    isServerSideRenderedHTML() {
        let htmlNode = document.getElementById('CTP');
        return ((htmlNode && htmlNode.innerHTML) || this.isErrorPage());
    }

    scrollStop() {

        // Setup scrolling variable
        var isScrolling;
        var self = this;

        // Listen for scroll events
        window.scroll = null;
        window.onscroll = function (event) {
            //stickyNavTabAndCTA();
            window.clearTimeout(isScrolling);
            //self.scrollFinished();
        }
    }

    scrollFinished() {
        //makeShikshaHeaderSticky(this.params);
    }

    getUrlParams(locationParams, loadMoreInstitutes) {
        if (!PreventScrolling.canUseDOM()) {
            return "";
        }
        let url = locationParams.pathname;
        let queryParams = parseQueryString(locationParams.search);
        let paramsObj = {};
        for (let key of Object.keys(queryParams)) {
            let keyArr = key.split(/[[\]]{1,2}/);
            if (keyArr[0] === 'rf') {
                paramsObj[keyArr[0]] = getQueryVariable(keyArr[0], locationParams.search);//queryParams.getAll(key)[0];
            } else if (keyArr[0] !== '') {
                paramsObj[keyArr[0]] = getQueryVariable(keyArr[0], locationParams.search);//queryParams.getAll(key);
            }
        }
        if (!this.isSrp && paramsObj['uaf']) {
            if (Array.isArray(paramsObj['uaf']) && paramsObj['uaf'].indexOf('undefined') !== -1) {
                delete paramsObj['uaf'][paramsObj['uaf'].indexOf('undefined')];
            } else if (!Array.isArray(paramsObj['uaf'])) {
                delete paramsObj['uaf'];
            }
        }
        if (paramsObj['uaf'] === '') {
            delete paramsObj['uaf'];
        }
        if (!this.isSrp)
            paramsObj['url'] = url;
        if (this.isSrp && paramsObj['rf'] === 'searchwidget') {
            paramsObj['rf'] = 'searchWidget';
        }
        const loadMoreInsti = this.isSrp ? 'lmi' : 'loadMoreInstitutes';
        if (typeof loadMoreInstitutes != "undefined" && Array.isArray(loadMoreInstitutes) && loadMoreInstitutes.length > 0) {
            paramsObj[loadMoreInsti] = loadMoreInstitutes;
        }
        return paramsObj;
    }

    getBase64UrlParams(locationParams, loadMoreInstitutes) {
        if (!PreventScrolling.canUseDOM()) {
            return "";
        }
        const paramsObj = this.getUrlParams(locationParams, loadMoreInstitutes);
        if (!this.isSrp && paramsObj['rf'] !== 'filters')
            paramsObj['fr'] = "true";
        let params = btoa(JSON.stringify(paramsObj));
        return params;
    }

    modalClose() {
        this.setState({...this.state, 'isOpen': false, 'customList': {}});
        PreventScrolling.enableScrolling();
    }

    dfpData() {
        if(this.isSrp){
            dfpPostParams = 'parentPage=DFP_SearchPage';
            if(this.props.categoryData && this.props.categoryData.requestData && this.props.categoryData.requestData.appliedFilters) {
                const appliedFilters = typeof this.props.categoryData.requestData.appliedFilters !== 'undefined' ? this.props.categoryData.requestData.appliedFilters: {};
                let dfpParams = '';
                if(!isEmpty(appliedFilters)){
                    let dfpData = {};
                    dfpData['streams']         = appliedFilters.streams;
                    dfpData['substreams']      = appliedFilters.substreams;
                    dfpData['baseCourse']      = appliedFilters.baseCourse;
                    dfpData['specializations'] = appliedFilters.specializations;
                    dfpData['deliveryMethod']  = appliedFilters.deliveryMethod && appliedFilters.deliveryMethod > 0 ? appliedFilters.deliveryMethod : null;
                    dfpData['educationType']   = appliedFilters.educationType && appliedFilters.educationType > 0 ? appliedFilters.educationType : null;
                    dfpData['city']            = appliedFilters.city ? appliedFilters.city : [0];
                    dfpData['state']           = appliedFilters.state ? appliedFilters.state : [0];
                    dfpData['credential']      = appliedFilters.credential;
                    dfpParams                  = JSON.stringify(dfpData);
                }
                dfpPostParams +='&extraPrams=' + dfpParams;
            }
            this.props.dfpBannerConfig(dfpPostParams);
            return;
        }
        let dfpPostParams = 'parentPage=DFP_CategoryPage';
        if (this.props.categoryData != null && typeof this.props.categoryData.requestData != 'undefined' && typeof this.props.categoryData.requestData.categoryPageId != 'undefined') {

            const appliedFilters = typeof this.props.categoryData.requestData.appliedFilters != 'undefined' ? this.props.categoryData.requestData.appliedFilters : {};
            let dfpParams = '';
            if (!isEmpty(appliedFilters)) {
                let dfpData = {};
                dfpData['streams'] = appliedFilters.streams;
                dfpData['substreams'] = appliedFilters.substreams;
                dfpData['baseCourse'] = appliedFilters.baseCourse;
                dfpData['specializations'] = appliedFilters.specializations;
                dfpData['deliveryMethod'] = appliedFilters.deliveryMethod;
                dfpData['educationType'] = appliedFilters.educationType;
                dfpData['city'] = appliedFilters.city ? appliedFilters.city : [0];
                dfpData['state'] = appliedFilters.state ? appliedFilters.state : [0];
                dfpData['credential'] = appliedFilters.credential;
                dfpParams = JSON.stringify(dfpData);
            }
            dfpPostParams += '&entity_id=' + this.props.categoryData.requestData.categoryPageId + '&extraPrams=' + dfpParams;
        }
        this.props.dfpBannerConfig(dfpPostParams);
    }

    initialFetchData(location, subsequentCall = false) {
        const self = this;
        this.setState({isShowLoader: true});
        const pathName = location ? location.pathname : this.props.location.pathname;
        const isFirstPage = this.detectIfFirstPage(pathName);
        const locationRegex = /^\/colleges/;
        const locationPage = locationRegex.test(pathName);
        const showPCW = isFirstPage && !locationPage;
        const paramsObj = this.getUrlParams(location);
        const randomNumber = Math.floor(1000 + Math.random() * 9000);
        let ctpgRandom = getCookie('ctpgRandom');
        if (typeof ctpgRandom === 'undefined' || ctpgRandom === '') {
            setCookie('ctpgRandom', randomNumber, 1);
            ctpgRandom = randomNumber;
        }
        if (paramsObj['rf'] !== 'filters')
            paramsObj['fr'] = "true";
        let isCaching = true;
        if (location.search && location.search !== '') {
            isCaching = false;
        }
        let fetchPromise = this.props.fetchCategoryPageData(paramsObj, '', false, showPCW, ctpgRandom, isCaching, isFirstPage);
        fetchPromise.then(function (res) {
            self.setState({isShowLoader: false});
            self.setState({
                aliasMapping: {},
                defaultAppliedFilters: {},
                filterOrder: {},
                filters: {},
                loadMoreCourses: {},
                nameMapping: {},
                selectedFiltersCount: 0,
                totalCourseCount: 0
            }, () =>{self.setPositionForSticky();});
            self.openDefaultLocationLayer();
            self.trackBeacon();
            //self.lastScrollTop = window.scrollY;
            //stickyNavTabAndCTA();
            //self.scrollStop();
            self.showReadMoreBHST();
            if (subsequentCall) {
                self.getFiltersData();
                self.dfpData();
            }
            //self.preCacheFirstTuple();
            self.setPositionForSticky();
        });
    }

    initialFetchSRPData(location, subsequentCall = false) {
        const self = this;
        this.setState({isShowLoader: true});
        const paramsObj = this.getUrlParams(location);
        const params = btoa(JSON.stringify(paramsObj));
        let fetchPromise = this.props.fetchCollegeSRPData(params);
        fetchPromise.then(function (res) {
            self.setState({isShowLoader: false});
            self.setState({
                aliasMapping: {},
                defaultAppliedFilters: {},
                filterOrder: {},
                filters: {},
                loadMoreCourses: {},
                nameMapping: {},
                selectedFiltersCount: 0,
                totalCourseCount: 0
            }, () =>{self.setPositionForSticky();});
            self.trackBeacon();
            //self.lastScrollTop = window.scrollY;
            //stickyNavTabAndCTA();
            //self.scrollStop();
            if (location.state && location.state.tabsData) {
                SRPNavbar.preload().then(function () {
                    self.setState({enableSRPNavBar: true});
                    self.setTabsData(location);
                });
            } else {
                self.getTabData(location);
            }
            if (subsequentCall) {
                self.getFiltersData();
                self.dfpData();
            }
            //self.preCacheFirstTuple();
            self.setPositionForSticky();
        });
    }
    setTabsData(location) {
        this.setState({tabsData: {...location.state.tabsData, 'collegeSRPQuery': location.search, 'collegeSRPCount' :
                    this.props.categoryData ? this.props.categoryData.totalInstituteCount : null}}, () =>{this.setPositionForSticky();});
    }
    getTabData(location = null) {
        const searchedKeyword = decodeURIComponent(parseQueryString(this.props.location.search)['q']);
        if (this.state.tabsData && this.state.tabsData.searchedKeyword && this.state.tabsData.searchedKeyword === searchedKeyword) {
            if(location){
                this.setState({tabsData : {...this.state.tabsData,
                        'collegeSRPQuery' : this.props.location.search, 'collegeSRPCount' :
                            this.props.categoryData ? this.props.categoryData.totalInstituteCount : null}},
                    () =>{this.setPositionForSticky();});
            }
            return;
        }
        fetchIntegratedSRPData(searchedKeyword).then((response) => {
            if (!(response.status === 200 && response && response.data && response.data.data && response.data.data.tabs)) {
                return;
            }
            SRPNavbar.preload().then(() => {
                this.setState({enableSRPNavBar: true});
                this.setState({'tabsData' : {'searchedKeyword' : searchedKeyword , 'tabsData' : response.data.data.tabs,
                        'collegeSRPQuery' : this.props.location.search, 'collegeSRPCount' :
                            this.props.categoryData ? this.props.categoryData.totalInstituteCount : null}},
                    () =>{this.setPositionForSticky();});
            });

        }).catch(function (thrown) {
            //console.log(thrown);
        });
    }

    getFiltersData() {
        const {categoryData} = this.props;
        if (typeof categoryData !== 'undefined' && typeof categoryData.categoryInstituteTuple !== 'undefined' &&
            categoryData.categoryInstituteTuple.length > 0) {
            this.filterCall = true;
            const {location} = this.props;
            let loadMoreInstitutes = [];
            for (let i in categoryData.categoryInstituteTuple) {
                if (categoryData.categoryInstituteTuple[i].instituteId != 0)
                    loadMoreInstitutes.push(categoryData.categoryInstituteTuple[i].instituteId);
            }
            if(!this.isSrp && categoryData.pcwData && categoryData.pcwData.popularInstituteTuple){
                for(let i in categoryData.pcwData.popularInstituteTuple) {
                    if(!categoryData.pcwData.popularInstituteTuple.hasOwnProperty(i))
                        continue;
                    if(categoryData.pcwData.popularInstituteTuple[i].instituteId != 0)
                        loadMoreInstitutes.push(categoryData.pcwData.popularInstituteTuple[i].instituteId);
                }
            }
            const params = this.getBase64UrlParams(location, loadMoreInstitutes);
            const filterAPI = this.isSrp ? APIConfig.GET_COLLEGE_SRP_FILTERS : APIConfig.GET_CATEGORYPAGE_FILTERS
            getRequest(filterAPI + '?data=' + params).then((response) => {
                if (response.data.data != null) {
                    const data = response.data.data;
                    const formattedLoadMoreCourses = reformatLoadMoreCourses(data.loadMoreCourses);
                    this.setState({
                        aliasMapping: data.aliasMapping,
                        defaultAppliedFilters: data.defaultAppliedFilters,
                        filterOrder: data.filterOrder,
                        filters: data.filters,
                        loadMoreCourses: formattedLoadMoreCourses,
                        nameMapping: data.nameMapping,
                        selectedFiltersCount: data.selectedFiltersCount,
                        totalCourseCount: data.totalCourseCount
                    });
                    this.trackGTM();
                    this.formatForDefaultLocationLayer(data.filters);
                    if(document.getElementById('fixed-card-wrapper')){
                        document.getElementById('fixed-card-wrapper').style.height='61px';
                    }
                }
            });
        }
    }


    isAllIndiaRequest() {
        const {categoryData} = this.props;
        if (typeof categoryData !== 'undefined' && !categoryData) {
            return;
        }

        const appliedFilters = typeof categoryData.requestData !== 'undefined' && categoryData.requestData && typeof categoryData.requestData.appliedFilters !== 'undefined' ? categoryData.requestData.appliedFilters : {};
        window.appliedFilters = appliedFilters;
        if ((typeof appliedFilters['city'] != 'undefined' && ((appliedFilters['city'].length == 0) || (appliedFilters['city'].length == 1 && appliedFilters['city'][0] == 1))) && (typeof appliedFilters['state'] != 'undefined' && ((appliedFilters['state'].length == 0) || (appliedFilters['state'].length == 1 && appliedFilters['state'][0] == 1)))) {
            return true;
        }
        return false;
    }

    isOpenLocationLayerOnLoad() {
        const {categoryData} = this.props;
        if (typeof categoryData !== 'undefined' && !categoryData) {
            return;
        }
        const categoryInfoData = typeof categoryData.requestData !== 'undefined' && categoryData.requestData && typeof categoryData.requestData.categoryData !== 'undefined' ? categoryData.requestData.categoryData : {};
        return !!(categoryInfoData && typeof categoryInfoData['streamId'] !== 'undefined' && defaultLocationLayerSteamIds.indexOf(categoryInfoData['streamId']) > -1);

    }

    renderLoader() {
        PreventScrolling.enableScrolling(true);
        if (PreventScrolling.canUseDOM()) {
            document.getElementById('page-header').style.display = "table";
            document.getElementById('page-header').style.position = "relative";
        }
        return <ContentLoader/>;
    }

    onLocationSubmit(selectedOptions) {
        let {history, location} = this.props;
        const {aliasMapping, defaultAppliedFilters, filters} = this.state;
        if (!Array.isArray(selectedOptions) && selectedOptions.length == 0) {
            return;
        }
        if (getObjectSize(filters) == 0) {
            showToastMsg('some problem occured. please try again');
            return;
        }
        selectedOptions.map(function (value) {
            var optionValArr = value.optionValue.split(/_(.+)?/),
                optionKey = optionValArr[0],
                optionValue = optionValArr[1];
            value.optionValue = aliasMapping[optionKey] + '_' + optionValue;
        });
        const queryParams = generateCatPageUrl(selectedOptions, aliasMapping, defaultAppliedFilters, filters);
        history.push(location.pathname + '?rf=filters&' + queryParams);
        this.setState({isOpen: false});
    }

    showReadMoreBHST(){
        if(this.isSrp)
            return;
        if(document.getElementsByClassName('BHST_parent').length != 0 &&
            document.getElementsByClassName('BHST_child').length != 0 &&
            document.getElementsByClassName('BHST_parent')[0].clientHeight <
            document.getElementsByClassName('BHST_child')[0].clientHeight ) {
            document.getElementsByClassName('BHST_read_more')[0].style.display = 'block';
        }
    }

    handleReadMoreClick = () => {
        document.getElementsByClassName('BHST_parent')[0].style.maxHeight = 'none';
        document.getElementsByClassName('BHST_read_more')[0].style.display = 'none';
    };
    generatePageHeadingHTML() {
        if (this.isSrp && this.searchedKeyword !== 'undefined') {
            const isRelaxed = this.props.categoryData.keywordRelaxed;
            const isSpellCheckDone = this.props.categoryData.spellcheckDone;

            let searchedTermData = this.props.categoryData.requestData.appliedFilters;
            if (searchedTermData.originalSearchKeyword === searchedTermData.searchKeyword || (!isSpellCheckDone && !isRelaxed))
                return (<h1>{this.props.categoryData.totalInstituteCount} Colleges for '{this.searchedKeyword}'</h1>);
            else {
                return (<React.Fragment>
                    <h1>No Colleges found for '{this.searchedKeyword}'</h1>
                    <p className="resultTxt">Suggestion: Please enter only a college/course name or check spellings</p>
                    <p className="zrp-clgs">Showing {this.props.categoryData.totalInstituteCount} results for
                        '{searchedTermData.searchKeyword} {(!isSpellCheckDone && isRelaxed) ?
                            <span
                                className="strikeThrough">{searchedTermData ? searchedTermData.qerKeyword : ''}</span> : ''}'</p>
                </React.Fragment>)
            }
        } else if (this.isSrp) {
            return (<h1>{this.props.categoryData.totalInstituteCount} Colleges</h1>);
        }
        const headingCheck = typeof this.props.categoryData.requestData != 'undefined' &&
            typeof this.props.categoryData.requestData.categoryData != 'undefined' && this.props.categoryData.requestData.categoryData;
        return (<React.Fragment>
            <h1>{this.props.categoryData.totalInstituteCount} {headingCheck && this.props.categoryData.requestData.categoryData.headingMobile}</h1>
            {this.props.categoryData.requestData.categoryData && this.props.categoryData.requestData.categoryData.bhstData
                ?<React.Fragment>
                    <div className="BHST_parent"> <div className="BHST_child f12_normal clr_0"
                           dangerouslySetInnerHTML={createHTMLObj(this.props.categoryData.requestData.categoryData.bhstData)}/>
                    </div>
                    <div className="BHST_read_more color-b" onClick={this.handleReadMoreClick}>Read More</div>
                </React.Fragment> :
                <p>Find information related to Cutoffs, Placements, Courses, Fees, Admissions, Rankings, Eligibility and
                Reviews for <span>{headingCheck && this.props.categoryData.requestData.categoryData.headingMobile}</span></p>}
            {this.props.categoryData.rankingPageUrl &&  this.props.categoryData.rankingPageUrl !== "" &&
            <Link  to={this.props.categoryData.rankingPageUrl} onClick={this.trackEvent.bind(this, "RP_interlinking_Top", "click")} className="ranking_link">
                <img src="https://images.shiksha.ws/pwa/public/images/Group.svg" alt="Ranking Page Link"/>Show Top Ranked Colleges
            </Link> }
        </React.Fragment>);
    }

    generateFilters() {
        let queryP = '', urlHasParams = false;
        let pathname = this.props.categoryData.requestData && this.props.categoryData.requestData.categoryData && this.props.categoryData.requestData.categoryData.url  ? this.props.categoryData.requestData.categoryData.url : '';
        if(this.isSrp) {
            queryP = pruneSrpQueryParams(this.props.location.search, ['q', 'tk', 'okw', 'autosuggestorPick', 'apk']);
        } else{
            const params = parseQueryString(this.props.location.search);
            if(params['sby']){
                urlHasParams = true;
                queryP = "?sby=" + params['sby'];
            }
        }
        const pageUrl = this.isSrp ? this.props.location.pathname + queryP : this.props.categoryData.requestData.categoryData.url + queryP;
        const sortType = this.props.categoryData.requestData && this.props.categoryData.requestData.sortType  ?
            this.props.categoryData.requestData.sortType : "";
        return (<Filters gaTrackingCategory={this.gaTrackingCategory} filters={this.state.filters}
                         displayName={this.state.nameMapping} shortName={this.state.aliasMapping} pageUrl={pageUrl}
                         defaultAppliedFilters={this.state.defaultAppliedFilters} filterOrder={this.state.filterOrder}
                         selectedFiltersCount={this.state.selectedFiltersCount} isSrp={this.isSrp}
                         hasFilterData={this.filterCall} onFilterButtonPress={this.onScroll} pageFooterFilter={!this.isSrp} sortType = {sortType} urlHasParams = {urlHasParams}
                         pathname = {pathname} showSort={!this.isSrp} />);
    }

    showState() {
        return !(this.isSrp || this.props.categoryData.totalInstituteCount >= 10 || this.props.categoryData.fallbackResultCount <= 0);


    }
    toShowOCFBasedOnCount(filtersArray){
        let count = 0;
        for(let filterData in filtersArray){
            if(!filtersArray.hasOwnProperty(filterData))
                continue;
            if(!filtersArray[filterData]['enabled'])
                continue;
            count++;
            if(count > 1)
                break;
        }
        return count > 1;

    }
    getBreadCrumbs(){
        if(typeof this.props.categoryData !== 'undefined' && this.props.categoryData != null &&
            typeof this.props.categoryData.requestData !== 'undefined' && this.props.categoryData.requestData != null &&
            this.props.categoryData.requestData.categoryData && this.props.categoryData.requestData.categoryData.breadcrumb){
            return <BreadCrumbs isMobile={true} breadCrumbData = {this.props.categoryData.requestData.categoryData.breadcrumb} />
        }
        return null;
    }
    getOCFOrder(){
        if(this.isSrp)
            return;
        let ocfOrder = [];
        if(this.props.location.search.indexOf('bc') === -1 && this.state.filters['base_course'] &&
            !this.state.defaultAppliedFilters.hasOwnProperty('base_course')){

            const showBc = this.toShowOCFBasedOnCount(this.state.filters['base_course']);
            if(showBc)
                ocfOrder.push('base_course');
        }
        if(this.props.location.search.indexOf('sp') === -1 && this.props.location.search.indexOf('ss') === -1 &&
            ((this.state.filters['specialization']  && !this.state.defaultAppliedFilters.hasOwnProperty('specialization')) ||
            (this.state.filters['sub_spec'] && !this.state.defaultAppliedFilters.hasOwnProperty('substream')))){

            let filterType;
            if(this.state.filters['specialization'])
                filterType = 'specialization';
            if(this.state.filters['sub_spec'])
                filterType = 'sub_spec';
            const showSP = this.toShowOCFBasedOnCount(this.state.filters[filterType]);
            if(showSP)
                ocfOrder.push(filterType);
        }
        if(this.props.location.search.indexOf('fe') === -1 && this.state.filters['fees'] &&
            !this.state.defaultAppliedFilters.hasOwnProperty('fees')){
            const showFe = this.toShowOCFBasedOnCount(this.state.filters['fees']);
            if(showFe)
                ocfOrder.push('fees')
        }
        return ocfOrder;
    }
    render() {
        if (this.state.isShowLoader) {
            return (
                <React.Fragment>
                    {this.renderLoader()}
                </React.Fragment>

            );
        }
        const {location, categoryData} = this.props;

        let nextHashValue = this.getBase64UrlParams(location);
        if (typeof categoryData == 'undefined' || (typeof categoryData != 'undefined' && categoryData && Object.keys(categoryData).length == 0) || (typeof categoryData != 'undefined' && this.props.categoryData && Object.keys(this.props.categoryData).length == 1 && Object.keys(this.props.categoryData)[0] == 'hashValue')) {
            return (
                <React.Fragment>
                    {this.renderLoader()}
                </React.Fragment>
            );
        } else if (typeof categoryData == 'undefined' || categoryData == null) {
            return <ErrorMsg/>;
        } else if (typeof categoryData.categoryUrlHash != 'undefined' && typeof nextHashValue != 'undefined' && nextHashValue != "" && categoryData.categoryUrlHash != nextHashValue) {
            return (
                <React.Fragment>
                    {this.renderLoader()}
                </React.Fragment>
            );
        } else if ((categoryData && categoryData.requestData && !categoryData.requestData.categoryData && categoryData.requestData.show404)) {
            return <NotFound/>;
        } else if (categoryData && categoryData.requestData && categoryData.requestData.categoryData) {
            let totalCount = this.props.categoryData.totalInstituteCount;
            if (this.showState()) {
                totalCount += this.props.categoryData.fallbackResultCount;
            }
            let maxPages = Math.ceil(totalCount / this.state.maxCount);
            let currentPageNum = this.props.categoryData.requestData.pageNumber;
            let queryString = '';
            if (typeof window != 'undefined' && window.location.search != '') {
                queryString = window.location.search;
            }
            let basePageUrl = this.props.categoryData.requestData.categoryData.url + queryString;
            if (currentPageNum > maxPages && maxPages != 0) { //safe check
                return <Redirect to={`${basePageUrl}`}/>
            } else if ((categoryData && Array.isArray(categoryData.categoryInstituteTuple) && categoryData.categoryInstituteTuple.length == 0)) {
                return <ZeroResultPage isSrp={this.isSrp} keyword={this.searchedKeyword} config={this.props.config}/>
            }
        } else if (this.isSrp && categoryData && categoryData.requestData && categoryData.requestData.redirectUrl) {
            return <Redirect to={categoryData.requestData.redirectUrl}/>
        } else if ((categoryData && Array.isArray(categoryData.categoryInstituteTuple) && categoryData.categoryInstituteTuple.length == 0)) {
            return <ZeroResultPage isSrp={this.isSrp} keyword={this.searchedKeyword} config={this.props.config}/>
        }
        if (PreventScrolling.canUseDOM() && document.getElementById('root').classList.contains('disable-scroll'))
            PreventScrolling.enableScrolling(true);
        let seoData = (categoryData && categoryData.seoData) ? categoryData.seoData : '';
        return (
            <React.Fragment>
                {ClientSeo(seoData)}
                <CategoryTableComponent show="false" categoryData={this.props.categoryData}/>
                
                <div id="CTP">
                    <div className="ctp">
                        <section>
                            <div className="ctp_container">
                                {!this.isSrp && <div className="breadcumb_col">{this.getBreadCrumbs()}</div>}
                                {this.isSrp && this.state.enableSRPNavBar &&
                                <SRPNavbar tabsData={this.state.tabsData ? this.state.tabsData : {} } activeTab={'Colleges'} showHeading={false}
                                           activeTabCount={this.props.categoryData.totalInstituteCount}
                                           keyword={this.searchedKeyword} isMobile={true} gaCategory = {'SEARCH_MOBILE'}/>
                                }
                                <div className={"ctp-filter-head" + (this.isSrp ? " srpNavBar" : "")}>
                                    <div className="fltr-contnt">
                                        {this.generatePageHeadingHTML()}
                                        {!this.isSrp && <div className="shareWidgetCTP"><SocialSharingBand widgetPosition={"CTP_Top"}/></div>}
                                    </div>
                                    <div id="tab-section">
                                        {this.generateFilters()}
                                    </div>
                                </div>
                                <CategoryPageContent config={this.props.config} isSrp={this.isSrp} categoryData={this.props.categoryData}
                                                     gaTrackingCategory={this.gaTrackingCategory} showStateData={this.showState()}
                                                     loadMoreCourses={this.state.loadMoreCourses} showUSPLda={true}
                                                     recoEbTrackid={this.isSrp ? 1138 : 273} recoShrtTrackid={this.isSrp ? 1139 : 1089}
                                                     showOAF={true} applyNowTrackId={this.isSrp ? 2051 : 2049} showOCF={!this.isSrp}
                                                     ocfOrder={this.getOCFOrder()}  filters={this.state.filters}
                                                     displayName={this.state.nameMapping} shortName={this.state.aliasMapping}
                                                     pageUrl={this.isSrp ? '' : this.props.categoryData.requestData.categoryData.url}
                                                     filterName={'base_course'} deviceType="mobile" ebTrackid={this.isSrp ? 1865 : 269}
                                                     srtTrackId={this.isSrp ? 1863 : 271}/>
                                
                                {!this.isSrp && <div className="shareWidgetCTPBtm"><SocialSharingBand widgetPosition={"CTP_Bottom"}/></div>}

                                <RecoLinkWidget recoData={this.props.categoryData.categoryPageLinks}/>
                                <SingleSelectLayer show={this.state.isOpen} onClose={this.modalClose.bind(this)}
                                                   data={this.state.customList} search={this.state.search}
                                                   heading={this.state.layerHeading}
                                                   showSubHeading={this.state.subHeading}
                                                   placeHolderText={this.state.placeHolderText}
                                                   layerType={this.state.layerType} captionMsg={this.state.captionMsg}
                                                   onSubmit={this.onLocationSubmit.bind(this)} multiSelect={true}>
                                </SingleSelectLayer>

                            </div>
                        </section>
                    </div>
                </div>
            </React.Fragment>
        )
    }

    detectIfFirstPage(pathName) {
        const lastCharUrl = pathName[pathName.length - 1];
        let firstPage = true;
        if (!isNaN(lastCharUrl) && parseInt(lastCharUrl) > 1) {
            firstPage = false;
        }
        return firstPage;
    }

    trackEvent(actionLabel, label){

        Analytics.event({category: this.gaTrackingCategory, action: actionLabel, label: label});
    }

}

function mapStateToProps(state) {
    return {
        categoryData: state.categoryData,
        config: state.config
    }
}

function mapDispatchToProps(dispatch) {
    return bindActionCreators({
        fetchCategoryPageData,
        dfpBannerConfig,
        clearDfpBannerConfig,
        fetchCollegeSRPData
    }, dispatch);
}

export default connect(mapStateToProps, mapDispatchToProps)(CategoryPage);
