import React, {Component} from "react";
import {connect} from "react-redux";
import {bindActionCreators} from "redux";
import {fetchCategoryPageData} from "../actions/CategoryPageAction";
import {fetchCollegeSRPData, fetchIntegratedSRPData} from "../../../search/actions/SearchAction";
import "../assets/DesktopCategoryPage.css";
import {getCookie, getQueryVariable, isEmpty, parseQueryString, pruneSrpQueryParams, resetGNB, setCookie, makeFiltersSticky
} from "../../../../utils/commonHelper";
import PreventScrolling from "../../../reusable/utils/PreventScrolling";
import APIConfig from "../../../../../config/apiConfig";
import {getRequest} from "../../../../utils/ApiCalls";
import ErrorMsg from "../../../common/components/ErrorMsg";
import NotFound from "../../../common/components/NotFound";
import {Redirect} from "react-router-dom";
import ZeroResultPage from "./ZrpPage";
import CategoryTableComponent from "./CategoryTableComponent";
import BreadCrumbs from "../../../search/components/BreadCrumbs";
import DesktopFilters from "../../../search/components/DesktopFilters";
import SelectedFilters from "../../../search/components/SelectedFilter";
import ContentLoaderCTPDesktop from "../../../search/components/ContentLoaderCTPDesktop";
import {clearDfpBannerConfig, dfpBannerConfig} from "../../../reusable/actions/commonAction";
import {DFPBannerTempalte} from "../../../reusable/components/DFPBannerAd";
import RecoLinkWidget from "./RecoLinkWidget";
import {getBeaconTrackData} from "../utils/categoryUtil";
import ElasticSearchTracking from "../../../reusable/utils/ElasticSearchTracking";
import SRPNavbar from "../../../search/components/SRPNavbar";
import {ExamSRP} from "../../../../../routes/loadableRoutesDesktop";
import ClientSeo from "../../../common/components/ClientSeo";
import CategoryPageContent from "../../../search/components/CategoryPageContent";
import {reformatLoadMoreCourses} from "../../../search/utils/searchUtils";
import {Link} from "react-router-dom";
import SocialSharingBand from './../../../common/components/SocialSharingBand';
import {createHTMLObj} from './../../../search/utils/searchUtils'
import Analytics from "../../../reusable/utils/AnalyticsTracking";

class DesktopCTP extends Component {
    constructor(props){
        super(props);
        this.state = {
            isShowLoader : false, filters : {}, aliasMapping : {}, defaultAppliedFilters : {}, filterOrder : {}, loadMoreCourses:{},
            nameMapping: {}, selectedFiltersCount: 0, totalCourseCount : 0,shoshkeleStyleClass : 'shoshkele-open', tabsData : null
        };
        this.detectPage(this.props);
    }
    componentDidMount(){
        window.scrollTo(0, 0);
        if(!DesktopCTP.isServerSideRenderedHTML()) {
            const {location} = this.props;
            if(this.isSrp)
                this.initialFetchSRPData(location);
            else
                this.initialFetchData(location);
        }
        else if(!DesktopCTP.isErrorPage()) {
            this.trackBeacon();
            this.getDFPData();
            this.getFiltersData();
            if(this.props.location.state && this.props.location.state.tabsData && this.isSrp){
                this.setTabsData(this.props.location);
            } else if(this.isSrp) {
                this.getTabData(this.props.location);
            }
            this.showReadMoreBHST()
        }
        if(typeof(document) !='undefined' && document.getElementsByClassName('tupleBrochureButton').length>0){
            let ebLen = document.getElementsByClassName('tupleBrochureButton');
            for(let i=0;i<ebLen.length;i++){
                let courseId = ebLen[i].getAttribute('courseid');
                let ebCookie = 'applied_'+courseId;
                if(getCookie(ebCookie)){
                    document.getElementById('ebTxt'+courseId).innerText = 'Brochure Mailed';
                    ebLen[i].classList.add('ebDisabled');
                }
            }
        }
        this.stickyBooleans = {bottomFixedFlag : false, topFixedFlag : false, footerSeenFlag : false, topIsReachedFlag : false, isTopSetToMax : false,
            isRelativeFlag : false};
        this.lastScrollTop = 0;

        let gnbSelector = '#_globalNav';
        if(this.isSrp) {
            gnbSelector = '.fixed_tile';
            if(!document.querySelector(gnbSelector)){
                gnbSelector =  '#_globalNav';
            }
        }
        this.gnbHeight = document.querySelector(gnbSelector).clientHeight;
        this.filterBottomFixPos = 0;
        window.addEventListener('message', this.onMessage);
        window.addEventListener('scroll', this.makeFiltersSticky);
        if(this.isSrp){
            window.addEventListener('mousemove' , this.loadExamSRP);
        }
    }
    componentWillUnmount() {
        this.props.clearDfpBannerConfig();
        window.removeEventListener('message', this.onMessage);
        window.removeEventListener('scroll', this.makeFiltersSticky);
        if(this.isSrp){
            window.removeEventListener('mousemove' , this.loadExamSRP);
        }
    }
    componentWillReceiveProps(nextProps) {
        let nextHash = this.getBase64UrlParams(nextProps.location);
        let prevHash = this.getBase64UrlParams(this.props.location);
        if(prevHash !== nextHash) {
            resetGNB();
            this.detectPage(nextProps);
            window.scrollTo(0, 0);
            this.stickyBooleans = {bottomFixedFlag : false, topFixedFlag : false, footerSeenFlag : false, topIsReachedFlag : false, isTopSetToMax : false,
                isRelativeFlag : false};
            this.lastScrollTop = 0;
            this.filterBottomFixPos = 0;
            if(this.isSrp)
                this.initialFetchSRPData(nextProps.location, true);
            else
                this.initialFetchData(nextProps.location, true);
        }
    }
    trackBeacon() {
        const {categoryData,config} = this.props;
        if(categoryData && typeof categoryData.requestData !== 'undefined') {
            let trackingParams;
            if(this.isSrp) {
                trackingParams = {};
                trackingParams['pageIdentifier'] = 'search';
                trackingParams['pageEntityId'] = 0;
                trackingParams['extraData'] = {} ;
                trackingParams['extraData']['childPageIdentifier'] = 'collegeSRPPage';
            } else
                trackingParams = getBeaconTrackData(categoryData, 'search');
            ElasticSearchTracking(trackingParams,config.BEACON_TRACK_URL);
        }
    }
    onMessage = (event) =>{
        const data = event.data;
        if(data === "categoryPushDownBannerReload"){
            this.setState({shoshkeleStyleClass :  'shoshkele-open'});
        } else if(data === "categoryPushDownBannerClose"){
            this.setState({shoshkeleStyleClass : 'shoshkele-close'})

        }
    };

    makeFiltersSticky = () => {
        let currScroll = document.documentElement.scrollTop;
        let footerSelector = document.getElementById('footer');
        if(document.querySelector('.ctpRecoLinks')) {
            footerSelector = document.querySelector('.ctpRecoLinks');
        }
        const filterSidebarElement = document.querySelector('.ctp_sidebar');
        const resultsContainer = document.querySelector('.ctpSrp-contnr');
        if(!filterSidebarElement || !resultsContainer)
            return;
        const filterTopOffset = filterSidebarElement.getBoundingClientRect().top + window.pageYOffset;
        this.filterBottomFixPos = filterSidebarElement.clientHeight - document.documentElement.clientHeight + filterTopOffset;
        this.stickyBooleans = makeFiltersSticky(currScroll,filterSidebarElement,resultsContainer,footerSelector,this.stickyBooleans,this.gnbHeight,this.lastScrollTop,this.filterBottomFixPos);
        this.lastScrollTop = currScroll ;
    };

    detectPage(props){
        switch(props.hocData){
            case "categoryPage" :
                this.isSrp = false;
                this.gaTrackingCategory = "CATEGORY_PAGE_DESKTOP";
                this.showInTable = true;
                break;
            case "collegeSrp" :
                this.isSrp = true;
                this.gaTrackingCategory = "COLLEGE_SRP_DESKTOP";
                this.searchedKeyword = decodeURIComponent(parseQueryString(props.location.search)['q']);
                this.showInTable = false;
                break;
        }
    }
    loadExamSRP = () =>{
        ExamSRP.preload();
    };
    initialFetchData(location) {
        const self = this;
        this.setState({isShowLoader : true});
        const pathName = location ? location.pathname : this.props.location.pathname;
        const isFirstPage = DesktopCTP.detectIfFirstPage(pathName);
        const locationRegex = /^\/colleges/;
        const  locationPage = locationRegex.test(pathName);
        const showPCW = isFirstPage && !locationPage;
        const paramsObj = this.getUrlParams(location);
        if (paramsObj['rf'] !== 'filters')
            paramsObj['fr'] = "true";
        let ctpgRandom = getCookie('ctpgRandom');
        const randomNumber = Math.floor(1000 + Math.random() * 9000);
        if(typeof ctpgRandom === 'undefined' || ctpgRandom === ''){
            setCookie('ctpgRandom', randomNumber,1);
            ctpgRandom = randomNumber;
        }
        let isCaching = true;
        if(location.search && location.search !== ''){
            isCaching = false;
        }
        let fetchPromise = this.props.fetchCategoryPageData(paramsObj, '', false, showPCW, ctpgRandom, isCaching, isFirstPage);
        fetchPromise.then(function() {
            self.setState({isShowLoader : false, aliasMapping : {}, defaultAppliedFilters : {}, filterOrder : {}, filters : {}, loadMoreCourses : {}, nameMapping : {},
                selectedFiltersCount : 0, totalCourseCount : 0});
            self.trackBeacon();
            self.getDFPData();
            self.showReadMoreBHST();
            self.getFiltersData();
        });
    }
    setTabsData(location) {
        this.setState({tabsData: {...location.state.tabsData, 'collegeSRPQuery': location.search, 'collegeSRPCount' :
                    this.props.categoryData ? this.props.categoryData.totalInstituteCount : null}});
    }
    initialFetchSRPData(location) {
        const self = this;
        this.setState({isShowLoader : true});
        const paramsObj = this.getUrlParams(location);
        const params =  btoa(JSON.stringify(paramsObj));
        let fetchPromise = this.props.fetchCollegeSRPData(params);
        fetchPromise.then(function(){
            self.setState({isShowLoader : false, aliasMapping : {}, defaultAppliedFilters : {}, filterOrder : {}, filters : {}, loadMoreCourses : {},
                nameMapping : {}, selectedFiltersCount : 0, totalCourseCount : 0});
            if(location.state && location.state.tabsData){
                self.setTabsData(location);
            } else {
                self.getTabData(location);
            }
            self.trackBeacon();
            self.getDFPData();
            self.getFiltersData();
        });
    }
    getFiltersData() {
        const {categoryData} = this.props;
        if(typeof categoryData !== 'undefined' && typeof categoryData.categoryInstituteTuple !== 'undefined' &&
            categoryData.categoryInstituteTuple.length > 0) {
            const {location} = this.props;
            let loadMoreInstitutes = [];
            for(let i in categoryData.categoryInstituteTuple) {
                if(categoryData.categoryInstituteTuple[i].instituteId != 0)
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
            const params = this.getBase64UrlParams(location,loadMoreInstitutes);
            const filterAPI = this.isSrp ? APIConfig.GET_COLLEGE_SRP_FILTERS : APIConfig.GET_CATEGORYPAGE_FILTERS;
            getRequest(filterAPI+'?data='+params).then((response) => {
                if(response.data.data != null) {
                    const data = response.data.data;
                    const formattedLoadMoreCourses = reformatLoadMoreCourses(data.loadMoreCourses);
                    this.setState({aliasMapping : data.aliasMapping,defaultAppliedFilters : data.defaultAppliedFilters,
                        filterOrder : data.filterOrder, filters : data.filters, loadMoreCourses : formattedLoadMoreCourses,
                        nameMapping : data.nameMapping, selectedFiltersCount : data.selectedFiltersCount, totalCourseCount : data.totalCourseCount});
                    //this.trackGTM();
                    //this.formatForDefaultLocationLayer(data.filters);
                }
            });
        }
    }

    getDFPData(){
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
        if(this.props.categoryData && this.props.categoryData.requestData && this.props.categoryData.requestData.categoryPageId) {
            const appliedFilters = typeof this.props.categoryData.requestData.appliedFilters !== 'undefined' ? this.props.categoryData.requestData.appliedFilters: {};
            const seoData = typeof this.props.categoryData.requestData.categoryData != 'undefined' ? this.props.categoryData.requestData.categoryData : {};
            let dfpParams = '';
            if(!isEmpty(appliedFilters)){
                let dfpData = {};
                dfpData['streams']         = appliedFilters.streams;
                dfpData['substreams']      = appliedFilters.substreams;
                dfpData['baseCourse']      = appliedFilters.baseCourse;
                dfpData['specializations'] = appliedFilters.specializations;
                dfpData['deliveryMethod']  = seoData.deliveryMethod && seoData.deliveryMethod > 0 ? seoData.deliveryMethod : null;
                dfpData['educationType']   = seoData.educationType && seoData.educationType > 0 ? seoData.educationType : null;
                dfpData['city']            = appliedFilters.city ? appliedFilters.city : [0];
                dfpData['state']           = appliedFilters.state ? appliedFilters.state : [0];
                dfpData['credential']      = appliedFilters.credential;
                dfpParams                  = JSON.stringify(dfpData);
            }
            dfpPostParams +='&entity_id=' + this.props.categoryData.requestData.categoryPageId + '&extraPrams=' + dfpParams;
        }
        this.props.dfpBannerConfig(dfpPostParams);
    }

    getTabData(location){
        const searchedKeyword = decodeURIComponent(parseQueryString(location.search)['q']);
        if(this.state.tabsData && this.state.tabsData.searchedKeyword && this.state.tabsData.searchedKeyword === searchedKeyword){
            this.setState({tabsData : {...this.state.tabsData, 'collegeSRPQuery' : this.props.location.search,
                    'collegeSRPCount' : this.props.categoryData ? this.props.categoryData.totalInstituteCount : null}});
            return;
        }
        fetchIntegratedSRPData(searchedKeyword).then((response) => {
            if(!(response.status === 200 && response && response.data && response.data.data && response.data.data.tabs)){
                return;
            }
            this.setState({'tabsData' : {'searchedKeyword' : searchedKeyword , 'tabsData' : response.data.data.tabs,
                    'collegeSRPQuery' : this.props.location.search, 'collegeSRPCount' :
                        this.props.categoryData ? this.props.categoryData.totalInstituteCount : null}});
        }). catch(function(thrown) {
            //console.log(thrown);
        });
    }
    componentDidUpdate(prevProps, prevState) {
        if(!this.isSrp)
            return;
        if(document.querySelector('.fixed_tile'))
            this.gnbHeight = document.querySelector('.fixed_tile').clientHeight;
        else
            this.gnbHeight = document.querySelector(' #_globalNav').clientHeight;
    }

    showState(){
        return !(this.isSrp || this.props.categoryData.totalInstituteCount >= 10 || this.props.categoryData.fallbackResultCount <= 0);
    }
    getShoshkeleIfExists(){
        if(this.isSrp)
            return;
        if(!this.props.categoryData.shoshkeleUrl) {
            return (
                <React.Fragment>
                    <div id="RNR_pushdownbanner" className="clear top-ad-wrap" style={{display: 'none'}}></div>
                    <DFPBannerTempalte parentPage='DFP_CategoryPage' bannerPlace = "CTP_CS"/>
                </React.Fragment>
            );
        }
        const shoshkeleObject = this.props.categoryData.shoshkeleUrl;
        const firstInstitute = this.props.categoryData.categoryInstituteTuple[0].instituteId;
        let displayBannerUrl = shoshkeleObject.hasOwnProperty(firstInstitute) ? shoshkeleObject[firstInstitute] : null;
        if(displayBannerUrl && displayBannerUrl !== '' && displayBannerUrl.indexOf('.html') !== -1) {
            return(
                <div id="shoshkeleBannerDiv">
                <div id="RNR_pushdownbanner" className="clear top-ad-wrap" style = {{width:'943px', display:'inline-block'}}>
                    <iframe id="categoryPagePushDownBannerFrame" width="100%" height="160" scrolling="no"  frameBorder="0"
                            src={displayBannerUrl} bordercolor="#000000" vspace="0" hspace="0" marginHeight="0" marginWidth="0"
                            className={this.state.shoshkeleStyleClass}>
                    </iframe>
                </div>
                </div>
            )
        }
        else {
            return(
                <React.Fragment>
                    <div id="RNR_pushdownbanner" className="clear top-ad-wrap" style={{display: 'none'}}/>
                    <DFPBannerTempalte parentPage='DFP_CategoryPage' bannerPlace = "CTP_CS"/>
                </React.Fragment>
            )
        }

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

    generatePageHeading(enableSocialSharing = false){
        if(this.isSrp && this.searchedKeyword !== 'undefined'){
            const isRelaxed = this.props.categoryData.keywordRelaxed;
            const isSpellCheckDone = this.props.categoryData.spellcheckDone;
            this.searchedKeyword = decodeURIComponent(parseQueryString(this.props.location.search)['q']);
            let searchedTermData = this.props.categoryData.requestData.appliedFilters;
            if(searchedTermData.originalSearchKeyword === searchedTermData.searchKeyword || (!isSpellCheckDone && !isRelaxed))
                return null;
            else{
                return (<React.Fragment>
                    <section className="_subcontainer">
                        <h1 className="h1" >No Colleges found for '{this.searchedKeyword}'</h1>
                        <p className="resultTxt">Suggestion: Please enter only a college/course name or check spellings</p>
                        <p className="zrp-clgs">Showing results for '{searchedTermData.searchKeyword} {(!isSpellCheckDone && isRelaxed) ?
                            <span className="strikeThrough">{searchedTermData ? searchedTermData.qerKeyword:''}</span>:''}'</p>
                    </section>
                </React.Fragment>)
            }
        }
       /* else if(this.isSrp){
            return (<h1 className="search-heading">{this.props.categoryData.totalInstituteCount} Colleges</h1>);
        }*/
        if(!this.props.categoryData || !this.props.categoryData.requestData)
            return;
        const requestData = this.props.categoryData.requestData;
        const headingCheck = typeof requestData != 'undefined' &&
            typeof requestData.categoryData != 'undefined' && requestData.categoryData;
        return (<React.Fragment>
            <section className="_subcontainer position_rltv">
                <h1>{this.props.categoryData.totalInstituteCount} {headingCheck && requestData.categoryData.headingMobile}</h1>
                {this.props.categoryData.requestData.categoryData && this.props.categoryData.requestData.categoryData.bhstData
                    ? <React.Fragment>
                        <div className="BHST_parent">
                            <div className="BHST_child f12_normal clr_0"
                                 dangerouslySetInnerHTML={createHTMLObj(this.props.categoryData.requestData.categoryData.bhstData)}/>
                        </div>
                        <div className="BHST_read_more color-b" onClick={this.handleReadMoreClick}>Read More</div>
                    </React.Fragment> :
                <p>Find information related to Cutoffs, Placements, Courses, Fees, Admissions, Rankings, Eligibility and
                    Reviews for <span>{headingCheck && requestData.categoryData.headingMobile}</span></p>}
                {this.props.categoryData.rankingPageUrl &&  this.props.categoryData.rankingPageUrl !== "" &&
                <Link  to={this.props.categoryData.rankingPageUrl} onClick={this.trackEvent.bind(this, "RP_interlinking_Top", "click")} className="ranking_link">
                    <img src="https://images.shiksha.ws/pwa/public/images/Group.svg" alt="Ranking Page Link"/>Show Top Ranked Colleges
                </Link> }
                {enableSocialSharing && <div className="socialShareDeskCTPTop"><SocialSharingBand widgetPosition={"CTP_Top"} deviceType="desktop"/></div>}    
            </section>
        </React.Fragment>);
    }
    getBreadCrumbs(){
        if(typeof this.props.categoryData !== 'undefined' && this.props.categoryData != null &&
            typeof this.props.categoryData.requestData !== 'undefined' && this.props.categoryData.requestData != null &&
            this.props.categoryData.requestData.categoryData && this.props.categoryData.requestData.categoryData.breadcrumb){
            return <BreadCrumbs breadCrumbData = {this.props.categoryData.requestData.categoryData.breadcrumb} />
        }
        return null;
    }
    showLoader(fullPage = false){

        return(
            <div className="pwa_pagecontent">
                <div className="pwa_container">
                    {!this.isSrp && this.getBreadCrumbs()}
                    {this.isSrp &&
                        <SRPNavbar tabsData = {this.state.tabsData ? this.state.tabsData : {} } activeTab={'Colleges'} isMobile={false}
                                   keyword={decodeURIComponent(parseQueryString(this.props.location.search)['q'])} gaCategory = {'SEARCH_DESKTOP'}/>
                    }
                    {!this.isSrp && typeof this.props.categoryData !== 'undefined' && this.props.categoryData != null &&
                    typeof this.props.categoryData.requestData !== 'undefined' && this.props.categoryData.requestData != null &&
                    (this.props.categoryData.requestData.categoryData || (this.isSrp && this.props.categoryData.requestData.appliedFilters)) ?
                        this.generatePageHeading() : ''}
                    <div className="ctp_container">
                        <div className="ctp_block">
                            <div className="clearFix">
                            {fullPage === true ? <ContentLoaderCTPDesktop/> : <ContentLoaderCTPDesktop contentLoader = {true}/>}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
    render(){
        if(this.state.isShowLoader) {

            return this.showLoader(true);
        }
        const {location, categoryData} = this.props;

        let nextHashValue = this.getBase64UrlParams(location);
        if(typeof categoryData !== 'undefined' && categoryData == null) {
            resetGNB();
            return <ErrorMsg />;
        }
        if(!categoryData  || Object.keys(categoryData).length === 0){
            return this.showLoader(true);
        }
        if(categoryData.categoryUrlHash  && nextHashValue && categoryData.categoryUrlHash !== nextHashValue) {
            return this.showLoader(true);
        }
        if((categoryData &&  categoryData.requestData && ! categoryData.requestData.categoryData &&  categoryData.requestData.show404)) {
            resetGNB();
            return <NotFound/>;
        }
        if(categoryData.requestData && categoryData.requestData.categoryData){
            let totalCount = categoryData.totalInstituteCount;
            if(this.showState()){
                totalCount += categoryData.fallbackResultCount;
            }
            const maxPages = Math.ceil(totalCount/30);
            const currentPageNum = categoryData.requestData.pageNumber;
            let queryString = '';
            if(typeof window !== 'undefined' && window.location.search) {
                queryString = window.location.search;
            }
            const basePageUrl = categoryData.requestData.categoryData.url+queryString;
            if(currentPageNum > maxPages && maxPages != 0) { //safe check
                return <Redirect to={basePageUrl}  />
            }
            else if((categoryData && Array.isArray(categoryData.categoryInstituteTuple) && categoryData.categoryInstituteTuple.length == 0)) {
                resetGNB();
                return <ZeroResultPage isDesktop={true} isSrp = {this.isSrp} keyword = {this.searchedKeyword} config={this.props.config}/>
            }
        }
        if(this.isSrp && categoryData &&  categoryData.requestData && categoryData.requestData.redirectUrl){
            return <Redirect to={categoryData.requestData.redirectUrl}  />
        }
        if(categoryData && Array.isArray(categoryData.categoryInstituteTuple) && categoryData.categoryInstituteTuple.length == 0) {
            resetGNB();
            return <ZeroResultPage isDesktop={true} isSrp = {this.isSrp} keyword = {this.searchedKeyword}  config={this.props.config}/>
        }
        let queryP;
        if(this.isSrp)
            queryP =  pruneSrpQueryParams(this.props.location.search, ['q', 'tk','okw', 'autosuggestorPick','apk']);
        const pageUrl = this.isSrp ? this.props.location.pathname + queryP: this.props.categoryData.requestData.categoryData.url;
        let seoData = (categoryData && categoryData.seoData) ? categoryData.seoData : '';
        return (
            <React.Fragment>
                {ClientSeo(seoData)}
                <CategoryTableComponent show="false" categoryData={categoryData}/>
                <div className="pwa_pagecontent">
                    <div className="pwa_container" id="ctp_pwa">
                        {/*breadcrumbs start*/}
                        {!this.isSrp && <BreadCrumbs breadCrumbData = {categoryData.requestData.categoryData.breadcrumb} />}
                        {/*breadcrumbs ends*/}
                        {/*search nav bar starts*/}
                        {this.isSrp &&
                            <SRPNavbar tabsData = {this.state.tabsData ? this.state.tabsData : {}} activeTab={'Colleges'} isMobile={false}
                                       activeTabCount={this.props.categoryData.totalInstituteCount} gaCategory = {'SEARCH_DESKTOP'}
                                       keyword={decodeURIComponent(parseQueryString(this.props.location.search)['q'])} />
                        }
                        {/*search nav bar ends*/}
                        {/*heading start*/}
                        {this.getShoshkeleIfExists()}
                        {this.generatePageHeading(true)}
                        {/*heading ends*/}
                        {/*main page starts*/}
                        <div className="ctp_container">
                            <div className="ctp_block">
                                <SelectedFilters isSrp={this.isSrp} filtersData = {this.state.filters} shortName={this.state.aliasMapping} pageUrl={pageUrl}/>
                                <div className="clearFix">
                                    {/*left side filters starts*/}
                                    <div className="fltlft filter-area">
                                        <div className="ctp_sidebar">
                                            <DesktopFilters gaTrackingCategory={this.gaTrackingCategory} filters={this.state.filters}
                                                            displayName={this.state.nameMapping} shortName={this.state.aliasMapping}
                                                            defaultAppliedFilters={this.state.defaultAppliedFilters} filterOrder={this.state.filterOrder}
                                                            selectedFiltersCount={this.state.selectedFiltersCount} isSrp = {this.isSrp} pageUrl={pageUrl}
                                                            tabsData = {this.state.tabsData} urlHasParams={this.isSrp}/>
                                        </div>
                                    </div>
                                    {/*left side filters ends*/}
                                    <div className="fltryt card-area ctp_rightsidebar">
                                        <CategoryPageContent config={this.props.config} isSrp={this.isSrp}
                                                             categoryData={this.props.categoryData} gaTrackingCategory={this.gaTrackingCategory}
                                                             showStateData={this.showState()} loadMoreCourses={this.state.loadMoreCourses}
                                                             recoEbTrackid={this.isSrp ? 1044 : 214}
                                                             recoShrtTrackid = {this.isSrp ? 1047 : 216} deviceType="desktop"
                                                             srtTrackId={this.isSrp ? 1042 :215} ebTrackid={this.isSrp ? 1043 :213}
                                                             pageType = {this.isSrp ? "searchPage" : "categoryPage"} showUSPLda = {true}
                                                             showOAF={true} applyNowTrackId={this.isSrp ? 1040 : 1038} showSort={!this.isSrp}/>
                                    </div>
                                </div>

                                {/*right side tuples ends*/}
                            </div>
                            {!this.isSrp && <RecoLinkWidget recoData={this.props.categoryData.categoryPageLinks}/>}
                        </div>
                        {/*main page ends*/}
                    </div>
                </div>
            </React.Fragment>
        );
    }

    static isErrorPage() {
        let html404 = document.getElementById('notFound-Page');
        return (html404 && html404.innerHTML);
    }
    static isServerSideRenderedHTML() {
        let htmlNode = document.getElementById('ctp_pwa');
        return ((htmlNode && htmlNode.innerHTML) || DesktopCTP.isErrorPage());
    }
    static detectIfFirstPage (pathName){
        const lastCharUrl = pathName[pathName.length - 1];
        let firstPage = true;
        if(!isNaN(lastCharUrl) && parseInt(lastCharUrl) > 1){
            firstPage = false;
        }
        return firstPage;
    }
    getUrlParams(locationParams, loadMoreInstitutes){
        if(!PreventScrolling.canUseDOM()){
            return "";
        }
        let url = locationParams.pathname;
        let queryParams;//new URLSearchParams(locationParams.search);
        const locationSearch = locationParams.search;
        queryParams = parseQueryString(locationSearch);
        let paramsObj = {};
        for(let key of Object.keys(queryParams)) {
            let keyArr = key.split(/[[\]]{1,2}/);
            if(keyArr[0] === 'rf') {
                paramsObj[keyArr[0]] = getQueryVariable(keyArr[0], locationSearch);//queryParams.getAll(key)[0];
            }
            else if(keyArr[0] !== '') {
                paramsObj[keyArr[0]] = getQueryVariable(keyArr[0], locationSearch);//queryParams.getAll(key);
            }
        }
        if(!this.isSrp && paramsObj['uaf']){
            if(Array.isArray(paramsObj['uaf']) && paramsObj['uaf'].indexOf('undefined') !== -1) {
                delete paramsObj['uaf'][paramsObj['uaf'].indexOf('undefined')];
            } else if(!Array.isArray(paramsObj['uaf'])){
                delete paramsObj['uaf'];
            }
        }
        if(paramsObj['uaf'] === ''){
            delete paramsObj['uaf'];
        }
        if(!this.isSrp)
            paramsObj['url'] = url;
        if(this.isSrp && paramsObj['rf'] === 'searchwidget'){
            paramsObj['rf'] = 'searchWidget';
        }
        const loadMoreInsti = this.isSrp ? 'lmi' :  'loadMoreInstitutes';
        if(loadMoreInstitutes && Array.isArray(loadMoreInstitutes) && loadMoreInstitutes.length > 0) {
            paramsObj[loadMoreInsti] = loadMoreInstitutes;
        }
        return paramsObj;
    }
    getBase64UrlParams(locationParams, loadMoreInstitutes)
    {
        if(!PreventScrolling.canUseDOM()){
            return "";
        }
        const paramsObj = this.getUrlParams(locationParams, loadMoreInstitutes);
        if (!this.isSrp && paramsObj['rf'] !== 'filters')
            paramsObj['fr'] = "true";
        return btoa(JSON.stringify(paramsObj));
    }

    trackEvent(actionLabel, label){

        Analytics.event({category: this.gaTrackingCategory, action: actionLabel, label: label});
    }
}

function mapStateToProps(state)
{
    return {
        categoryData : state.categoryData,
        config : state.config
    }
}

function mapDispatchToProps(dispatch){
    return bindActionCreators({fetchCategoryPageData, dfpBannerConfig, clearDfpBannerConfig, fetchCollegeSRPData }, dispatch);
}
export default connect(mapStateToProps, mapDispatchToProps)(DesktopCTP);
