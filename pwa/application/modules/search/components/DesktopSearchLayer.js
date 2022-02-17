import React, {Component} from 'react';
import {connect} from "react-redux";
import {bindActionCreators} from "redux";
import axios from "axios";
import {fetchCollegeData, fetchTrendingSearchesData, fetchTupleData, trackSearch, fetchIntegratedSRPData} from "../actions/SearchAction";
import SearchConfig from "../config/SearchConfig";
import {numberFormatter} from "../../../utils/MathUtils";
import {withRouter} from 'react-router-dom';
import Loader from "../../reusable/components/Loader";
import ReactDOM from "react-dom";
import {DesktopCTP} from '../../../../routes/loadableRoutesDesktop';
import {clearDfpBannerConfig, dfpBannerConfig} from "../../reusable/actions/commonAction";
import {DFPBannerTempalte} from "../../reusable/components/DFPBannerAd";
import {getCookie, setCookie} from "../../../utils/commonHelper";
import Analytics from "../../reusable/utils/AnalyticsTracking";
import {highlightTypedKeyword} from './../utils/searchUtils'

class DesktopSearchLayer extends Component {

    constructor(props) {
        super(props);
        this.state = {
            autosuggestorTuples: [],
            showAdvancedSearchLayer: false,
            openCourseDropdown: false, // new
            openStreamDropdown: false, //new
            showLoader: false,
            showAdvancedLoader : false, //new,
            disableInput : false, //new
            blurSuggestor : false,
            streamValue : null,
            courseValue : null,
            activeIndex : -1, //new
            activeIndexStream : -1, //new
            activeIndexCourse : -1, //new
            showRecentSearches : !(this.props && this.props.location && this.props.location.keyword && this.props.location.keyword.length > 0),
            resetActive: !!(this.props && this.props.location && this.props.location.keyword && this.props.location.keyword.length > 0),
            typedKeyword : this.props && this.props.location && this.props.location.keyword ? this.props.location.keyword : '',
            showUserSearches : true,
            showErrorMessage : false,
            searchPopupAnimClass : 'hide',
            visibleDots : '',
            searchButtonText : 'Search'

        };
        this.filtersData = {};
        this.prevSearchedKeyword = '';
        this.exactMatch = null;
        this.url = null;
        this.searchType = null;
        this.courseValue = null;
        this.shiftDown = false;
    }
    componentDidMount() {
        if(this.props.isSearchLayerOpen) {
            document.getElementById('wrapperMainForCompleteShiksha').classList.add("noscroll");
            if(!this.props.searchPage) {
                this.setState({searchPopupAnimClass : 'active'});
            }
            else{
                document.getElementById('searchInput').focus();
                this.getDFPData()
            }
        }
        if(!this.props.trendingData || !this.props.trendingData.solrResults || !this.props.trendingData.bucketName) {
            this.props.fetchTrendingSearchesData(false);
        }
        window.addEventListener('keyup', this.onKeyUp);
        window.addEventListener('keydown', this.onKeyDown);
        document.getElementById('searchLayer').addEventListener("click", this.onLayerClick);

    }
      componentWillReceiveProps(nextProps) {
            if(!this.props.isSearchLayerOpen && nextProps.isSearchLayerOpen && !this.props.searchPage) {
                if(googletag && googletag.pubads) {
                    googletag.pubads().refresh();
                }
            }
        }

    onLayerClick = (event) => {
        if(this.state.autosuggestorTuples.length > 0 && !this.state.disableInput){
            if((typeof event.target.getAttribute('class') == 'undefined' || !event.target.getAttribute('class')) && !this.state.blurSuggestor){
                this.setState({blurSuggestor: true});
                return;
            }
            if (typeof event.target.getAttribute('class') != 'undefined' && event.target.getAttribute('class')) {
                if (event.target.getAttribute('class').indexOf('mainTuple') < 0  && !this.state.blurSuggestor) {
                    this.setState({blurSuggestor: true});
                    return;
                }
                if (event.target.getAttribute('class').indexOf('mainTuple') > 0  && this.state.blurSuggestor) {
                    this.setState({blurSuggestor: false});
                    return;
                }
            }
        }
        if(this.state.showAdvancedSearchLayer && this.state.openStreamDropdown && this.filtersData.length > 0){
            if ((typeof event.target.getAttribute('class') == 'undefined' || !event.target.getAttribute('class')) && this.state.openStreamDropdown){
                this.setState({openStreamDropdown: false});
                return;
            }
            if(typeof event.target.getAttribute('class') != 'undefined' && event.target.getAttribute('class')){
                if (event.target.getAttribute('class').indexOf('streamTuple') < 0  && this.state.openStreamDropdown) {
                    this.setState({openStreamDropdown: false});
                    return;
                }
                if (event.target.getAttribute('class').indexOf('streamTuple') > 0  && !this.state.openStreamDropdown) {
                    this.setState({openStreamDropdown: true});
                    return;
                }
            }
        }
        if(this.state.showAdvancedSearchLayer && this.state.openCourseDropdown && this.state.streamValue.subSuggestions.length > 0){
            if ((typeof event.target.getAttribute('class') == 'undefined' || !event.target.getAttribute('class')) && this.state.openCourseDropdown){
                this.setState({openCourseDropdown: false});
                return;
            }
            if(typeof event.target.getAttribute('class') != 'undefined' && event.target.getAttribute('class')) {
                if (event.target.getAttribute('class').indexOf('courseTuple') < 0  && this.state.openCourseDropdown) {
                    this.setState({openCourseDropdown: false});
                    return;
                }
                if (event.target.getAttribute('class').indexOf('courseTuple') > 0  && !this.state.openCourseDropdown) {
                    this.setState({openCourseDropdown: true});
                }
            }
        }
    };
    onKeyDown = (event) => {
        if(!this.props.isSearchLayerOpen){
            return;
        }
        const {keyCode} = event;
        switch (keyCode) {
            case 16:                           //shift
                this.shiftDown = true;
                break;
            case 9:                            //tab
                event.preventDefault();
                const adder = this.shiftDown ? -1 :1;
                this.controlTabBehaviour(adder);
                break;
            case 40: // ArrowDown
            case 38: // ArrowUp
                event.preventDefault();
                const indexAdder = keyCode - 39;
                if (this.state.autosuggestorTuples.length > 0 && !this.state.blurSuggestor && !this.state.disableInput &&
                    this.state.activeIndex + indexAdder >= -1 && this.state.activeIndex + indexAdder < this.state.autosuggestorTuples.length) {
                    const activeIndexTuple = this.state.activeIndex + indexAdder;
                    this.setState({activeIndex: activeIndexTuple});
                    if(activeIndexTuple === -1)
                        return;
                    const suggestorBoxDiv = document.querySelector('.showsuggestorBox');
                    const tupleHeight = document.querySelector('.active-tuple').clientHeight;
                    if (suggestorBoxDiv.clientHeight < 300)
                        break;
                    this.scrollOnKeyMovements(suggestorBoxDiv, keyCode, tupleHeight, this.state.activeIndex, 300);
                } else if (this.state.showAdvancedSearchLayer && this.state.openStreamDropdown && this.filtersData.length > 0 &&
                    this.state.activeIndexStream + indexAdder >= -1 && this.state.activeIndexStream + indexAdder < this.filtersData.length) {

                    const streamActiveIndex = this.state.activeIndexStream + indexAdder;
                    this.setState({activeIndexStream: streamActiveIndex});
                    if(streamActiveIndex === -1)
                        return;
                    const suggestorBoxDiv = document.querySelector('.showOptns.show-optns-block');
                    const tupleHeight = document.querySelector('.showOptns.show-optns-block ul li.active-advanced').clientHeight;
                    if (suggestorBoxDiv.clientHeight < 200)
                        break;
                    if (keyCode === 40 && streamActiveIndex > 1) {
                        suggestorBoxDiv.scrollTop = suggestorBoxDiv.scrollTop + tupleHeight;
                    }
                    if (keyCode === 38 && suggestorBoxDiv.scrollTop > 0) {
                        suggestorBoxDiv.scrollTop = suggestorBoxDiv.scrollTop - tupleHeight > 0 ? suggestorBoxDiv.scrollTop - tupleHeight : 0;
                    }
                } else if (this.state.showAdvancedSearchLayer && this.state.openCourseDropdown && this.state.streamValue.subSuggestions.length > 0 &&
                    this.state.activeIndexCourse + indexAdder >= -1 &&
                    this.state.activeIndexCourse + indexAdder < this.state.streamValue.subSuggestions.length) {

                    const courseActiveIndex = this.state.activeIndexCourse + indexAdder;
                    this.setState({activeIndexCourse: courseActiveIndex});
                    if(courseActiveIndex === -1)
                        return;
                    const suggestorBoxDiv = document.querySelector('.showOptns.show-optns-block');
                    const tupleHeight = document.querySelector('.showOptns.show-optns-block ul li.active-advanced').clientHeight;
                    if (suggestorBoxDiv.clientHeight < 200)
                        break;
                    if (keyCode === 40 && courseActiveIndex > 1) {
                        suggestorBoxDiv.scrollTop = suggestorBoxDiv.scrollTop + tupleHeight;
                    }
                    if (keyCode === 38 && suggestorBoxDiv.scrollTop > 0) {
                        suggestorBoxDiv.scrollTop = suggestorBoxDiv.scrollTop - tupleHeight > 0 ? suggestorBoxDiv.scrollTop - tupleHeight : 0;
                    }
                    /*console.log(tupleHeight, '  ',suggestorBoxDiv.clientHeight);
                    this.scrollOnKeyMovements(suggestorBoxDiv, keyCode, tupleHeight, this.state.activeIndexCourse + 1);*/
                }
                break;
        }
    };
    controlTabBehaviour(tabAdder) {
        let activeFocus = document.activeElement.tabIndex;
        if(activeFocus <= 0){
            if(this.state.showAdvancedSearchLayer) {
                document.getElementById('tabIndex2').focus(); //Stream list
            }
            else
                document.getElementById('searchInput').focus();
            return;
        }
        activeFocus += tabAdder;
        if(activeFocus === 1 ){
            if(this.state.showAdvancedSearchLayer) {
                if(this.state.streamValue)
                    document.getElementById('tabIndex4').focus(); //course dropdown
                else
                    document.getElementById('tabIndex3').focus();  //stream dropdown
            }
            else
                document.getElementById('searchInput').focus(); //stream button
            return;
        }
        if(activeFocus > 4 && this.state.showAdvancedSearchLayer && this.state.streamValue){
            document.getElementById('tabIndex2').focus(); //search button
            return;
        }
        if(activeFocus > 3 && this.state.showAdvancedSearchLayer && !this.state.streamValue){
            document.getElementById('tabIndex2').focus(); //search button
            return;
        }

        if(activeFocus > 2 && !this.state.showAdvancedSearchLayer){
            document.getElementById('searchInput').focus();
            return;
        }

        if(activeFocus <= 0){
            if(this.state.showAdvancedSearchLayer) {
                if (this.state.streamValue)
                    document.getElementById('tabIndex4').focus(); //course dropdown
                else
                    document.getElementById('tabIndex3').focus(); //stream dropdown
            } else
                document.getElementById('tabIndex2').focus();
            return;
        }
        document.getElementById('tabIndex' + activeFocus).focus();
    }
    onKeyUp = (event) => {
        if(!this.props.isSearchLayerOpen){
            return;
        }
        const {keyCode} = event;
        switch (keyCode) {
            case 16:                          //shift
                this.shiftDown = false;
                break;
            case 27:                          //escape
                this.closeLayerPopup();
                break;
            case 13:                          //enter
                //for main autosuggestor
                if(!this.state.blurSuggestor && !this.state.disableInput && !this.state.showAdvancedSearchLayer){
                    if(this.state.activeIndex === -1) {
                        const searchedString = this.state.typedKeyword;
                        this.performSearch(searchedString);
                    }
                    else if(this.state.activeIndex >=0 && this.state.activeIndex < this.state.autosuggestorTuples.length){
                        const tuple = this.state.autosuggestorTuples[this.state.activeIndex];
                        this.onClickSuggestion(tuple.data, tuple.bucket, 'close', tuple.keyword);
                    }
                } else if(this.state.showAdvancedSearchLayer && this.state.openStreamDropdown && this.filtersData.length > 0 &&
                    this.state.activeIndexStream >= 0 && this.state.activeIndexStream < this.filtersData.length){
                    this.selectStream(this.filtersData[this.state.activeIndexStream]);
                } else if(this.state.showAdvancedSearchLayer && this.state.openCourseDropdown && this.state.streamValue.subSuggestions.length > 0 &&
                    this.state.activeIndexCourse >= 0 && this.state.activeIndexCourse < this.state.streamValue.subSuggestions.length){
                    this.selectCourse(this.state.streamValue.subSuggestions[this.state.activeIndexCourse]);
                } else if(((this.state.showAdvancedSearchLayer && !this.state.openStreamDropdown && !this.state.openCourseDropdown) ||
                    this.state.showAdvancedLoader) && this.url) {
                    this.prepareAdvancedTrackingData();
                    window.location.href = this.url;
                    if(this.state.showAdvancedLoader) {
                        this.setState({
                            showAdvancedSearchLayer: false, streamValue: null, courseValue: null,
                            openStreamDropdown: false, openCourseDropdown: false, showAdvancedLoader: false});}
                }
                break;
        }
    };
    scrollOnKeyMovements(suggestorBoxDiv, keyCode, tupleHeight, activeIndex){
        if(keyCode === 40 && suggestorBoxDiv.scrollTop + suggestorBoxDiv.clientHeight <= tupleHeight * (activeIndex + 4)){
            suggestorBoxDiv.scrollTop = suggestorBoxDiv.scrollTop + tupleHeight;
        }
        if(keyCode === 38 && suggestorBoxDiv.scrollTop > 0){
            suggestorBoxDiv.scrollTop = suggestorBoxDiv.scrollTop - tupleHeight > 0 ? suggestorBoxDiv.scrollTop - tupleHeight : 0;
        }
    }
    componentWillUnmount() {
        this.props.clearDfpBannerConfig();
        document.getElementById('wrapperMainForCompleteShiksha').classList.remove("noscroll");
    }
    componentDidUpdate(prevProps, prevState, nextState) {
        if(this.props.isSearchLayerOpen && !prevProps.isSearchLayerOpen) {
            document.getElementById('wrapperMainForCompleteShiksha').classList.add("noscroll");
            if(!this.props.searchPage) {
                this.setState({searchPopupAnimClass : 'active'}, () => {document.getElementById('searchInput').focus();});
            }
        } else if(!this.props.isSearchLayerOpen && prevProps.isSearchLayerOpen){
            document.getElementById('wrapperMainForCompleteShiksha').classList.remove("noscroll");
        }

    }

    trackEvent(actionLabel,label) {
        Analytics.event({category : 'SEARCH_DESKTOP', action : actionLabel, label : label});
    }

    getDFPData(){
        let dfpPostParams = 'parentPage=DFP_SearchLayer';
        this.props.dfpBannerConfig(dfpPostParams);
    }
    closeLayerPopup(redirectToPwa = false){
        if(this.props.searchPage && !redirectToPwa){
            window.history.back();
            return;
        }
        if(typeof this._source !== typeof undefined){
            this._source.cancel('Operation Cancelled');
        }
        this.prevSearchedKeyword = '';
        clearInterval(this.myInter);
        this.setState({autosuggestorTuples : [], showRecentSearches: true, resetActive: false, streamValue : null, courseValue : null
            ,disableInput: false, typedKeyword : '', showAdvancedLoader : false, showLoader : false, showAdvancedSearchLayer: false, activeIndex: -1,
            openStreamDropdown : false, openCourseDropdown : false, showUserSearches : true, showErrorMessage : false, activeIndexCourse : -1,
            activeIndexStream : -1, searchPopupAnimClass : 'hide', visibleDots : '', searchButtonText : 'Search'} );
        this.url = null;
        this.courseValue = null;
        this.advancedSearhTypedKeyword = null;
        this.numTuples = null;
        if(redirectToPwa && this.props.searchPage)
            return;
        if(!this.props.searchPage) {
            this.props.onClose();
            return;
        }
    };

    openDropdownCourse = () =>{
        const dropDownState = !this.state.openCourseDropdown;
        this.setState({openCourseDropdown: dropDownState, openStreamDropdown : false});
    };
    openDropdownCourseBlur = () =>{
        this.setState({openCourseDropdown: false});
    };
    openDropdownCourseFocus = () =>{
        this.setState({openCourseDropdown: true, openStreamDropdown : false});
    };
    openDropdownStream = () => {
        const dropDownState = !this.state.openStreamDropdown;
        this.setState({openStreamDropdown: dropDownState, openCourseDropdown : false});
    };
    openDropdownStreamFocus = () => {
        this.setState({openStreamDropdown: true, openCourseDropdown : false});
    };
    openDropdownStreamBlur = () => {
        this.setState({openStreamDropdown: false});
    };
    /*
     * no match found before this (as we are taking priority into account), autosuggestor result and typed keyword are same ,
     * the result is not part of {$SearchConfig.BUCKET_IGN_LIST} bucket. Story-> https://infoedge.atlassian.net/browse/SS-350
     */
    matchesKeyword(tupleData){
        return !this.exactMatch && tupleData.data.name.toLowerCase() === tupleData.keyword.toLowerCase()
            && SearchConfig.BUCKET_IGN_LIST.indexOf(tupleData.bucket) === -1;

    }
    clearRecentSearchCookie = () => {
        setCookie('recentSearches','', -1);
        this.trackEvent('recent_search', 'clear_recent_search');
        this.setState({showRecentSearches: false});
    };
    generateRecentSearches(){
        if(!this.canUseDOM() && !this.props.hocData)
            return null;
        let recentSearchesData;
        //if the page is being served through server then props.hocData will be set
        try {
            if (this.props.hocData)
                recentSearchesData = JSON.parse(new Buffer(this.props.hocData, 'base64'));
            else
                recentSearchesData = getCookie('recentSearches') ? JSON.parse(atob(getCookie('recentSearches'))) : null;
        } catch(err) {
            setCookie('recentSearches','', -1);
        }
        if(!recentSearchesData)
            return;
            const recentList = recentSearchesData.map((tuple, i) => {
                return  (tuple.bucket || (typeof tuple.tupleData == 'object' && tuple.tupleData.type)) ? //Closed Search
                        (<li key={'recentList' + i} className={'recentTuple'}
                            onClick={this.onClickSuggestion.bind(this, tuple.tupleData, tuple.bucket, 'recentSearch', '')}>
                            <p className="rcnt-srchtitl recentTuple">{tuple.tupleData.name}</p>
                            <span className="srchType recentTuple"> {tuple.bucket} </span>
                        </li>) : (typeof  tuple.tupleData == 'object' ? //Open Search
                        (<li key={'recentList' + i} className={'recentTuple'}
                             onClick={this.onOpenRecentSearch.bind(this, tuple.tupleData)}>
                                <p className="rcnt-srchtitl recentTuple">{tuple.tupleData.name}</p>
                        </li>) : '');
                }
            );
        if(!recentList || recentList.length <= 0)
            return;
        return (<div className="trendscol">
            <div className="dvMsgHead rcnt">Recent Searches <span className="clr-r-srch" onClick={this.clearRecentSearchCookie}>Clear All</span></div>
            <div className="spaceUp">
                <ul className="lstRecentSrc">
                    {recentList}
                </ul>
            </div>
        </div> );
    }
    generateUserSearches(){
        const trendingSearchData = this.props.trendingData;
        let trendingList = [];
        if(trendingSearchData && trendingSearchData.solrResults) {
            trendingList = trendingSearchData.solrResults.map((tuple, i) =>
                <li key={'trendingList' + i} className={'trendingTuple'}
                    onClick={this.onClickSuggestion.bind(this, tuple, trendingSearchData.bucketName[tuple.type], 'trendingSearch', '')}>
                    <p className="rcnt-srchtitl trendingTuple">{tuple.name}</p>
                    <span className="srchType trendingTuple"> {trendingSearchData.bucketName[tuple.type]} </span>
                </li>
            );
        }
        if(!trendingList || trendingList.length <= 0)
            return;
        return (<div className="trendscol">
                <div className="dvMsgHead">Trending Searches</div>
                <div className="spaceUp">
                    <ul className="lstRecentSrc">
                        {trendingList}
                    </ul>
                </div>
            </div> );
    }
    setStreamOver = (index) =>  () => {
        this.setState({activeIndexStream : index});
    };
    setCourseOver = (index) => () => {
        this.setState({activeIndexCourse : index});
    };
    selectStream(tupleData){
        this.trackEvent('Advance_options_stream', 'click');
        this.setState({streamValue: tupleData, courseValue: null, openStreamDropdown : false, openCourseDropdown: true, activeIndexCourse : -1});
        document.getElementById('tabIndex4').focus();
        if(document.querySelector('.showOptns.courseTuple')){
            document.querySelector('.showOptns.courseTuple').scrollTop = 0;
        }
        this.courseValue = null;
        this.url = tupleData.url;
    }
    prepareStreamData(){
        const streamTuples = this.filtersData;
        const streamList = streamTuples.map((tuple, i) =>
            <li key={'streamTuple_'+i} className = {this.state.activeIndexStream === i ? 'active-advanced streamTuple' : 'streamTuple'}
                onMouseOver = {this.setStreamOver(i)}
                onClick={this.selectStream.bind(this, tuple)}><span className='streamTuple'>{tuple.name}</span></li>
        );
        return(
            <ul key="streamList streamTuple">
                {streamList}
            </ul>
        );
    }
    selectCourse(tupleData){
        this.trackEvent('Advance_options_course', 'click');
        this.url = tupleData.url;
        this.courseValue = tupleData;
        this.prepareAdvancedTrackingData();
        window.location.href = this.url;
        //this.setState({courseValue: tupleData, openCourseDropdown : false});
    }
    prepareCourseData(){
        if(!this.state.streamValue || !this.state.streamValue.subSuggestions)
            return;
        const courseTuples = this.state.streamValue.subSuggestions;
        const courseList = courseTuples.map((tuple, i) =>
            <li key={'courseTuple_'+i}  className = {this.state.activeIndexCourse === i ? 'active-advanced courseTuple' : ''}
                onMouseOver = {this.setCourseOver(i)}
                onClick={this.selectCourse.bind(this, tuple)}><span className='courseTuple'>{tuple.name}{tuple.subName?',':''} {tuple.subName}</span></li>
        );
        return(
            <ul className="ul-list" key="courseList">
                {courseList}
            </ul>
        );
    }
    generateAdvancedSearch(){
        return (<div className="advance-col">
            <fieldset className="subSection">
                <legend className="advanced-title">Advanced Search <span> (Optional) </span></legend>
                <div className="refernce-txt">
                    This college offers multiple courses and multiple streams. You may select stream/course from below:
                </div>
                <div className="dropdownflex flex-row space-btwn">
                    <div className={this.state.openStreamDropdown === true ? "flexdropdwnbox active streamTuple": "flexdropdwnbox streamTuple"}>
                        <a id="tabIndex3" tabIndex="3"  onFocus={this.openDropdownStreamFocus} onBlur={this.openDropdownStreamBlur} />
                        <div className="streamTuple" onClick={this.openDropdownStream}>
                            <p className={this.state.streamValue ? "changeTxt advanced-bold streamTuple" : "changeTxt streamTuple"} >
                                {this.state.streamValue ? this.state.streamValue.name :'Choose Stream'}</p>
                            <i className="custm-arwico streamTuple"/>
                        </div>
                        <div className={"showOptns streamTuple " + (this.state.openStreamDropdown ? 'show-optns-block' : 'hide-optns' )}>
                            {this.prepareStreamData()}
                        </div>
                    </div>
                    <div className={this.state.openCourseDropdown === true && this.state.streamValue ? "flexdropdwnbox active courseTuple":
                        (this.state.streamValue ? "flexdropdwnbox courseTuple" : "flexdropdwnbox notallowed")}>
                        <a id="tabIndex4" tabIndex="4"  onFocus={this.openDropdownCourseFocus} onBlur={this.openDropdownCourseBlur} />
                        <div className="courseTuple" onClick={this.openDropdownCourse}>
                            <p className={this.state.courseValue ? "changeTxt advanced-bold courseTuple" : "changeTxt courseTuple"}>{this.state.courseValue ? (this.state.courseValue.name + (this.state.courseValue.subName ? (', ' +this.state.courseValue.subName) : '')) :'Choose Course '}</p>
                            <i className="custm-arwico courseTuple"/>
                        </div>
                        <div className={"showOptns courseTuple " + (this.state.openCourseDropdown && this.state.streamValue ? 'show-optns-block' : 'hide-optns' )}>
                            {this.prepareCourseData()}
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>);
    }
    OnChangeSearchString = (event) => {
        this.setState({typedKeyword : event.target.value});
        let searchedString = event.target.value.trim();
        if(!searchedString){
            if(typeof this._source !== typeof undefined){
                this._source.cancel('Operation Cancelled');
            }
            this.prevSearchedKeyword = '';
            this.setState({autosuggestorTuples : [], showRecentSearches: true, resetActive: false, activeIndex: -1, blurSuggestor : false,
                showUserSearches : true} );
            return;
        }
        DesktopCTP.preload();
        if(this.prevSearchedKeyword === searchedString)
            return;
        if(typeof this._source !== typeof undefined){
            this._source.cancel('Operation Cancelled');
        }
        this.prevSearchedKeyword = searchedString;
        this.exactMatch = null;
        let autoSuggestorReults = [];

        this.setState({showUserSearches: false, resetActive: true, showErrorMessage : false});
        this._source = axios.CancelToken.source();
        let config = {cancelToken: this._source.token};

        fetchTupleData(searchedString, config).
        then((response) => {
            if(response.status === 200 && response.data.data){
                let bucket = response.data.data.bucketName;
                const searchedKeyword = response.data.data.searchKeyword;
                response.data.data.solrResults.forEach((solrResult) => {
                    let tupleData = {'data': solrResult, 'bucket': bucket[solrResult.type],
                        'keyword':searchedKeyword };
                    autoSuggestorReults.push(tupleData);
                    if(this.matchesKeyword(tupleData)){
                        this.exactMatch = tupleData;
                    }
                });
                this.setState({autosuggestorTuples : autoSuggestorReults, activeIndex : -1, blurSuggestor : false});
                document.querySelector('.showsuggestorBox').scrollTop = 0;
            }
        }).
        catch(function(thrown) {
            if (axios.isCancel(thrown)) {
            } else {
                //console.log(thrown);
            }
        });
    };
    getStreamCourseData(data, searchType) {
        this.advancedSearhTypedKeyword = this.state.typedKeyword;
        this.numTuples = this.state.autosuggestorTuples.length;
        this.setState({showAdvancedLoader: true, disableInput : true, typedKeyword : data.name, autosuggestorTuples : [], activeIndex : -1,
            showUserSearches : false, resetActive : true});
        document.getElementById('searchInput').blur();
        document.getElementById('tabIndex2').focus();
        fetchCollegeData(data).then((response) => {
            if (response.status !== 200) {
                return;
            }
            this.filtersData =  response.data.data.advancedOptions;
            this.searchType = searchType;
            if(!this.state.showAdvancedLoader){
                return;
            }
            this.setState({showAdvancedLoader: false, showAdvancedSearchLayer: true});

        }).
        catch((err) => {});
    }
    redirectToPWAPage(url, state = null){
        this.closeLayerPopup(true);
        if(state) {
            this.props.history.push(url, state);
        }
        else
            this.props.history.push(url);
    }
    onClickSuggestion = (tupleData, bucket, searchType, searchedKeyword = null) => {
        this.updateRecentSearchesCookie(tupleData, bucket);
        const isRecent = searchType === 'recentSearch';
        if(isRecent){
            this.trackEvent('Recent Search', 'click');
        }
        const isTrending = searchType === 'trendingSearch';
        if(isTrending){
            this.trackEvent('Trending Search', 'click');
        }
        if((bucket === SearchConfig.COLLEGE_BUCKET || bucket === SearchConfig.UNIV_BUCKET) && tupleData.type !== "ilpPage"){
            this.collegeData = tupleData;
            this.url = tupleData.url;
            this.getStreamCourseData(tupleData, searchType);
            return;
        }
        searchType = searchType === 'recentSearch' ? 'close' : searchType;
        searchType = searchType === 'trendingSearch' ? 'close' : searchType;
        this.prepareTrackingData({'typedKeyword' : searchedKeyword, 'searchType': searchType, 'isRecent':isRecent, 'isTrending' : isTrending}, tupleData);
        if(tupleData.url != null){
            if(bucket === SearchConfig.COURSE_BUCKET) {

                this.redirectToPWAPage(tupleData.url);
            }
            else
                window.location.href = tupleData.url;
        }
        if(tupleData.url)
            return;

        /*if(!searchedKeyword){
            searchedKeyword = tupleData.name;
        }*/
        this.redirectToPWAPage('/search?q=' + encodeURIComponent(tupleData.name) +
            '&' + SearchConfig.typeUrlMapping[tupleData.type] + '='+tupleData.id + '&apk=true' + '&rf=searchWidget') ;
    };
    setActiveIndex(activeIndex){
        this.setState({activeIndex : activeIndex});
    }

    generateHtml()
    {
        const suggestorTuples = this.state.autosuggestorTuples;
        const suggestorList = suggestorTuples.map((tuple, i) =>
            <li key={'suggestorTuple_'+i} onMouseOver={this.setActiveIndex.bind(this, i)}
                className = {this.state.activeIndex === i ? 'active-tuple mainTuple' :'mainTuple'}
                onClick={this.onClickSuggestion.bind(this, tuple.data, tuple.bucket, 'close', tuple.keyword)}>
              <span className="getName mainTuple">
                  <span className="getsearchname mainTuple" dangerouslySetInnerHTML={highlightTypedKeyword(tuple.data.name, tuple.keyword)}/>
                  {(tuple.bucket === SearchConfig.TOPIC_BUCKET && tuple.data.questionCount && tuple.data.questionCount > 0) ?
                      (<span className="stats mainTuple">
                       <span className="statsCount mainTuple">{numberFormatter(tuple.data.questionCount)}{tuple.data.questionCount > 1 ? ' Questions': ' Question'}</span>
                          {tuple.data.answerCount && tuple.data.answerCount > 0 ?
                              (<span className="statsCount mainTuple">, {numberFormatter(tuple.data.answerCount)} {tuple.data.answerCount > 1 ? ' Answers' : ' Answer'}
                            </span>): null}
                        </span>):null
                  }</span>
                <span className="srchType mainTuple"> {tuple.bucket} </span>
            </li>
        );
        return(<ul className="sugstrUl">
                {suggestorList}
            </ul>
        );
    }
    displayOpenSRP(searchedString){
        if(searchedString.length === 0)
            return;
        const queryParams ='?q=' + encodeURIComponent(searchedString) +
            '&rf=' + encodeURIComponent('searchWidget');
        this.textLoader();
        const self = this;
        fetchIntegratedSRPData(searchedString).then((response) => {
            if(!(response.status === 200 && response && response.data && response.data.data && response.data.data.tabs)){
                return;
            }
            const tabsData = response.data.data.tabs;
            let landingPathname;
            if(!Array.isArray(tabsData) || tabsData.length === 0){
                landingPathname = "/search";
            }else{
                landingPathname = tabsData[0].url;
            }
            self.prepareTrackingData({'typedKeyword' : searchedString, 'searchedKeyword' : searchedString, 'searchType': 'open',
                'landingUrl' : (landingPathname+queryParams)});
            const tupleData = {"url" : landingPathname + queryParams, "name" : searchedString};
            self.updateRecentSearchesCookie(tupleData);
            self.textLoader(true);
            self.redirectToPWAPage(landingPathname + queryParams, {'tabsData' : {'searchedKeyword' : searchedString , 'tabsData' : tabsData}});
        }). catch(function(thrown) {
            //console.log(thrown);
        });
    }

    onReset = () => {
        if(typeof this._source !== typeof undefined){
            this._source.cancel('Operation Cancelled');
        }
        this.prevSearchedKeyword = '';
        //this.textLoader(true);
        clearInterval(this.myInter);
        this.setState({autosuggestorTuples : [], showUserSearches: true, resetActive: false, typedKeyword : '',
            showAdvancedSearchLayer : false, disableInput : false, streamValue : null, courseValue : null, activeIndex : -1,
            openStreamDropdown : false, openCourseDropdown : false, showAdvancedLoader : false, showErrorMessage : false, activeIndexCourse : -1,
            activeIndexStream : -1, visibleDots : '', searchButtonText : 'Search'} );
        this.url = null;
        this.courseValue = null;
        this.advancedSearhTypedKeyword = null;
        this.numTuples = null;
        document.getElementById('searchInput').value ='';
    };
    onSearchPress = () =>{
        this.trackEvent('Search CTA', 'click');
        clearInterval(this.myInter);
        const searchedString = this.state.typedKeyword;
        if(this.url) {        //advanced Search case
            this.prepareAdvancedTrackingData();
            window.location.href = this.url;
            return;
        }
        this.performSearch(searchedString);
    };

    performSearch(searchedString){
        if(!searchedString){
            this.setState({showErrorMessage : true});
            return;
        }
        if(this.exactMatch){
            let tuple = this.exactMatch;
            this.onClickSuggestion(tuple.data, tuple.bucket, 'autopick',  tuple.keyword);
        }
        else {
            searchedString = searchedString.trim();
            this.displayOpenSRP(searchedString);
        }
    }
    render(){
        if(typeof window === 'undefined'){
            return null;
        }
       if(this.props.isSearchLayerOpen === false){
            return null;
        }
        if(this.state.showLoader)
            return ReactDOM.createPortal(<Loader show={this.state.showLoader}/>, document.getElementById('searchLayer'));
        return ReactDOM.createPortal(
            <div id='searchPopup' className={this.props.searchPage ? 'search-popup active' : 'search-popup ' + this.state.searchPopupAnimClass}>
                <a className="close-layer" onClick={this.closeLayerPopup.bind(this, false)}/>
                <div className="searchContent">
                    <div className="searchcolumn">
                        <div className="sugstrbox">
                            <div className="inputDiv">
                                {this.state.resetActive ? <div className="input-cls" onClick={this.onReset}/> : ''}
                                <input type="text" value = {this.state.typedKeyword} id='searchInput'
                                       onChange={this.OnChangeSearchString} autoComplete="off"
                                       placeholder="Search Colleges, Courses, Exams, QnA, & Articles" autoFocus='autofocus'
                                       className={"desktop-input mainTuple" + (this.state.disableInput ? ' disable-input ' : '')} tabIndex="1"/>
                                {this.state.showErrorMessage ? <span className="srch-err-msg">Please enter search term</span> : ''}
                                {!this.state.disableInput && !this.state.blurSuggestor ? <div className="showsuggestorBox">
                                    {this.generateHtml()}
                                </div>: ''}
                            </div>
                            <button type="submit" tabIndex="2" onClick={this.onSearchPress} className="pwabtn pwaprime-btn"
                                    id="tabIndex2">{this.state.searchButtonText}<span>{this.state.visibleDots}</span></button>
                        </div>
                        {/* Recent Search section starts */}
                        <div className={"recentBlock flex-row space-btwn" + (this.state.showUserSearches ? "" : " hide")}>
                            <div>
                                {this.state.showUserSearches && this.generateRecentSearches()}
                                {this.state.showUserSearches && this.generateUserSearches()}
                            </div>
                            <div className="sponsor-adcol"><DFPBannerTempalte bannerPlace="searchLayer"/></div>
                        </div>
                        {/* Advance options block start */}
                        {this.state.showAdvancedLoader && DesktopSearchLayer.generateAdvancedLoader()}
                        {this.state.showAdvancedSearchLayer && this.generateAdvancedSearch()}
                    </div>
                </div>
            </div>
            , document.getElementById('searchLayer'));
    }
    updateRecentSearchesCookie(tupleData, bucket = null){
        let cookieObject;
        cookieObject = {'tupleData':tupleData, 'bucket': bucket};
        let recentSearchesData = getCookie('recentSearches') ? JSON.parse(atob(getCookie('recentSearches'))) : null;
        if(!recentSearchesData || recentSearchesData.length <= 0) {
            recentSearchesData = [];
            recentSearchesData[0] = cookieObject;
        } else {
            let index = -1;
            for (let curr = 0; curr < recentSearchesData.length; curr++) {
                if (!this.differentSearches(recentSearchesData[curr], cookieObject)) {
                    index = curr;
                    break;
                }
            }
            if (index === 0) {//do nothing
            } else if (index === -1 || index === recentSearchesData.length - 1) {
                recentSearchesData.unshift(cookieObject);
                if((index === -1 && recentSearchesData.length > 5) || index > -1)
                    recentSearchesData.pop();
            } else {
                recentSearchesData.splice(index, 1);
                recentSearchesData.unshift(cookieObject);
            }
        }
        let string = btoa(JSON.stringify(recentSearchesData));
        setCookie('recentSearches',string , 30);
    }

    differentSearches(searchA, searchB){
        if(searchA.bucket !== searchB.bucket) {
            return true;
        }
        if(searchA.bucket && searchA.tupleData.name === searchB.tupleData.name && searchA.tupleData.id === searchB.tupleData.id && searchA.tupleData.type === searchB.tupleData.type){
            return false;
        }
        return !(!searchA.bucket && searchA.tupleData.url === searchB.tupleData.url);

    }

    onOpenRecentSearch(tupleData){
        this.trackEvent('Recent Search', 'click');
        this.updateRecentSearchesCookie(tupleData);
        this.prepareTrackingData({'typedKeyword' : tupleData.name, 'searchedKeyword' : tupleData.name, 'searchType': 'open',
            'isRecent' : true, 'landingUrl' : tupleData.url});
        this.redirectToPWAPage(tupleData.url);
    }

    prepareTrackingData(dataObj, data = null){
        if(window.referrer)
            dataObj.referrerUrl = window.referrer;
        else if (document.referrer){
            dataObj.referrerUrl = document.referrer;
        }
        if(data){
            dataObj.searchedKeyword = data.name;
            dataObj.selectedKeywordId = data.id;
            dataObj.landingUrl = data.url;
            dataObj.selectedKeywordType = data.type + (data.subType ? '-' + data.subType:'');

        }
        if(this.state.autosuggestorTuples) {
            dataObj.numOfSuggestionsShown = this.state.autosuggestorTuples.length;
        }
        dataObj.device = 'desktop';
        dataObj.isRecent = false;
        trackSearch(dataObj)
    }

    prepareAdvancedTrackingData(){
        let dataObj = {};
        let data = this.collegeData;
        if(!data)
            return;
        if(window.referrer) {
            dataObj.referrerUrl = window.referrer;
        } else if (document.referrer){
            dataObj.referrerUrl = document.referrer;
        }
        dataObj.searchedKeyword = data.name;
        dataObj.selectedKeywordId = data.id;
        dataObj.selectedKeywordType = data.type;
        if(this.state.streamValue){
            dataObj.selectedKeywordType += '-' + 'stream';
            if(this.courseValue){
                dataObj.selectedKeywordType += '-' + 'course';
            }
        }
        dataObj.landingUrl = this.url;
        dataObj.typedKeyword = this.advancedSearhTypedKeyword;
        dataObj.isRecent = this.searchType === 'recentSearch';
        dataObj.isTrending = this.searchType === 'trendingSearch';
        dataObj.searchType = (this.searchType === 'recentSearch' ? 'close' : this.searchType);
        dataObj.searchType = (this.searchType === 'trendingSearch' ? 'close' : this.searchType);
        dataObj.numOfSuggestionsShown = this.numTuples;
        dataObj.device = 'desktop';
        trackSearch(dataObj)
    }

    canUseDOM() {
        return !!(typeof window !== 'undefined' && window.document && window.document.createElement);
    }

    static generateAdvancedLoader(){
        return(<div className="advance-col content-loader">
            <fieldset className="subSection">
                <legend className="advanced-title">
         <span className="loader-line shimmer" style={{'top':'3px'}}>
            Advanced Search (Optional)
         </span>
                </legend>
                <div className="refernce-txt">
                    <span className="loader-line shimmer"/>
                    <span className="loader-line shimmer wdt85"/>
                </div>
                <div className="dropdownflex flex-row space-btwn">
                    <a href = "javascript:void(0);" >
                        <div  className="flexdropdwnbox streamTuple" style={{'border':'none'}}>
                            <span className="loader-line shimmer wdt85"/>
                        </div>
                    </a>
                    <a href = "javascript:void(0);">
                    <div className="flexdropdwnbox notallowed" style={{'border':'none'}}>
                        <span className="loader-line shimmer wdt85"/>
                    </div></a>
                </div>
            </fieldset>
        </div>);
    }

    textLoader(stopFlag = false, dots = 3, interval = 800){
        if(stopFlag){
            clearInterval(this.myInter);
            this.setState({visibleDots : '', searchButtonText : 'Search'});
            return;
        }
        const self = this;
        this.myInter = window.setInterval(function(){
            let visibleDots = self.state.visibleDots;
            if(visibleDots.length >= dots)
                visibleDots = '';
            else
                visibleDots += '.';
            self.setState({visibleDots : visibleDots, searchButtonText : 'Searching'});
        }, interval);
    }

}

DesktopSearchLayer.defaultProps = {
    isSearchLayerOpen : true
};

function mapStateToProps(state) {
    return {
        trendingData : state.trendingData,
        config : state.config
    }
}

function mapDispatchToProps(dispatch){
    return bindActionCreators({fetchTrendingSearchesData, dfpBannerConfig, clearDfpBannerConfig}, dispatch);
}

export default connect(mapStateToProps, mapDispatchToProps)(withRouter(DesktopSearchLayer));
