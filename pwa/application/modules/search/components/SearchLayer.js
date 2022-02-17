import React from 'react';
import FullPageLayer from './../../common/components/FullPageLayer';
import {
    fetchTupleData,
    fetchCollegeData,
    trackSearch,
    fetchTrendingSearchesData, fetchIntegratedSRPData
} from './../actions/SearchAction';
import config from './../../../../config/config';
import axios from 'axios';
import PreventScrolling from "../../reusable/utils/PreventScrolling";
import {numberFormatter} from './../../../utils/MathUtils'
import AdvancedSearchLayer from "./AdvancedSearchLayer";
import SearchConfig from '../config/SearchConfig';
import Loader from "../../reusable/components/Loader";
import {getCookie, setCookie} from '../../../utils/commonHelper';
import {Link, withRouter} from 'react-router-dom';
import {trackEvent, highlightTypedKeyword} from "../utils/searchUtils";
import {bindActionCreators} from "redux";
import {connect} from "react-redux";
import './../assets/SearchLayer.css'

const domainName                = config().SHIKSHA_HOME;
class SearchLayer extends React.Component
{
    constructor(props) {
        super(props);
        this.state = {
            autosuggestorTuples: [],
            showAdvancedSearchLayer: false,
            showLoader: false,
            showRecentSearches : this.props && this.props.location && this.props.location.keyword && this.props.location.keyword.length > 0 ? false  : true,
            resetActive: this.props && this.props.location && this.props.location.keyword && this.props.location.keyword.length > 0 ? true  : false,
            typedKeyword : this.props && this.props.location && this.props.location.keyword ? this.props.location.keyword : '',
            showUserSearches : true, showLineLoader : false
        };
        this.OnChangeSearchString    = this.OnChangeSearchString.bind(this);
        this.filtersData = {};
        this.prevSearchedKeyword = '';
        this.exactMatch = null;
    }
    componentDidMount(){
      if(this.props.showRecentSearch === false){
        this.setState({showRecentSearches : false});
      }
      if(!this.props.trendingData || !this.props.trendingData.solrResults || !this.props.trendingData.bucketName) {
            this.props.fetchTrendingSearchesData(false);
      }
    }

    canUseDOM() {
        return !!(typeof window !== 'undefined' && window.document && window.document.createElement);
    }
    differentSearches(searchA, searchB){
        if(searchA.bucket !== searchB.bucket) {
            return true;
        }
        if(searchA.bucket && searchA.tupleData.name === searchB.tupleData.name && searchA.tupleData.id === searchB.tupleData.id && searchA.tupleData.type === searchB.tupleData.type){
            return false;
        }
        if(!searchA.bucket && searchA.tupleData.url === searchB.tupleData.url){
            return false;
        }
        return true;
    }
    clearRecentSearchCookie(){
        setCookie('recentSearches','', -1);
        trackEvent('recent_search', 'clear_recent_search');
        this.setState({showRecentSearches: false});
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
    getStreamCourseData(data, searchType) {
        this.setState({showLoader: true});
         fetchCollegeData(data).then((response) => {
            if (response.status !== 200) {
                return;
            }
             this.filtersData =  response.data.data.advancedOptions;
            this.searchType = searchType;
             this.setState({showLoader: false,showAdvancedSearchLayer: true});

        }).
        catch((err) => {});
    }
    closeAdvancedLayer(){
        this.setState({showAdvancedSearchLayer: false});
    }
    onBack(){
      if(this.props.extraSearchParams != null && this.props.extraSearchParams.searchType == 'rankings'){
        this.props.onClose();
        return;
      }
      this.setState({autosuggestorTuples : [], showRecentSearches: true, resetActive: false, showUserSearches : true, showLineLoader : false} );
      PreventScrolling.enableScrolling(true);
      window.history.back();
    }
    onReset(event){
        if(typeof this._source != typeof undefined){
            this._source.cancel('Operation Cancelled');
        }
        this.setState({autosuggestorTuples : [], showRecentSearches: true, resetActive: false, typedKeyword : '', showUserSearches : true,
            showLineLoader : false} );
       document.getElementById('searchInput').value ='';
    }
    displayOpenSRP(searchedString){
        if(searchedString.length === 0)
            return;
        const queryParams ='?q=' + encodeURIComponent(searchedString) +
            '&rf=' + encodeURIComponent('searchWidget');
        this.setState({showLineLoader : true});
        const self  = this;
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
                'landingUrl' : (landingPathname + queryParams)});
            self.setState({showLineLoader : false});
            let tupleData = {"url" : landingPathname + queryParams, "name" : searchedString};
            self.updateRecentSearchesCookie(tupleData, null);
            self.props.history.push(landingPathname + queryParams, {'tabsData' : {'searchedKeyword' : searchedString , 'tabsData' : tabsData}});
        }). catch(function(thrown) {
            console.log('error  ',thrown);
            //console.log(thrown);
        });
    }
    performSearch(searchedString){
        if(!searchedString)
            return;
        if(this.exactMatch){
            let tuple = this.exactMatch;
            this.onClickSuggestion(tuple.data, tuple.bucket, 'autopick',  tuple.keyword);
        }
        else {
          if(this.props.extraSearchParams != null && this.props.extraSearchParams.searchType == 'rankings'){
            this.handleRankingSearchClick(null, searchedString);
            return;
          }
          this.displayOpenSRP(searchedString);
        }
    }
    onSearchKeyPress(event) {
        if (event.keyCode !== 13)
            return;
        const searchedString = event.target.value.trim();
        this.performSearch(searchedString);

    }
    handleRankingSearchClick(tupleData, searchedString){
      let institutes = [];
      let finalSearchedString = searchedString;
      if(tupleData != null){
        institutes.push(tupleData.id);
        finalSearchedString = tupleData.name;
      }else if(this.state.autosuggestorTuples.length > 0){
        this.state.autosuggestorTuples.forEach(
          currTuple => {
            institutes.push(currTuple.data.id);
          }
        );
      }else{
        institutes = null;
      }
      this.props.fetchSearchData(this.props.extraSearchParams.rankingPageParams, institutes, finalSearchedString);
    }
    onClickSuggestion(tupleData, bucket, searchType, searchedKeyword = null){
        if(this.props.extraSearchParams != null && this.props.extraSearchParams.searchType == 'rankings'){
          this.handleRankingSearchClick(tupleData, searchedKeyword);
          return;
        }
        this.updateRecentSearchesCookie(tupleData, bucket);
        const isRecent = searchType === 'recentSearch';
        const isTrending = searchType === 'trendingSearch';
        if(isRecent){
            trackEvent('Recent Search', 'click');
        }
        if(isTrending){
            trackEvent('Trending Search', 'click');
        }
        if((bucket === SearchConfig.COLLEGE_BUCKET || bucket === SearchConfig.UNIV_BUCKET) && tupleData.type !== "ilpPage"){
            this.collegeData = tupleData;
            this.getStreamCourseData(tupleData, searchType);
            return;
        }
        searchType = searchType === 'recentSearch' ? 'close' : searchType;
        searchType = searchType === 'trendingSearch' ? 'close' : searchType;
        this.prepareTrackingData({'typedKeyword' : searchedKeyword, 'searchType': searchType, 'isRecent':isRecent, 'isTrending' : isTrending},
            tupleData);
        if(tupleData.url != null){
            if(bucket === SearchConfig.COURSE_BUCKET) {
                this.props.history.push(tupleData.url);
            } else
            window.location.href = tupleData.url;
        }
        if(tupleData.url)
            return;
        this.setState({showLoader: true});
        this.props.history.push('/search?q=' + encodeURIComponent(tupleData.name) +
            '&' + SearchConfig.typeUrlMapping[tupleData.type] + '='+tupleData.id + '&apk=true' + '&rf=searchWidget') ;
    }
    OnChangeSearchString(event) {
        this.setState({typedKeyword : event.target.value});
        let searchedString = event.target.value.trim();
        if(!searchedString){
            if(typeof this._source != typeof undefined){
                this._source.cancel('Operation Cancelled');
            }
            this.prevSearchedKeyword = '';
            this.setState({autosuggestorTuples : [], showRecentSearches: true, resetActive: false, showUserSearches : true} );
            return;
        }
        if(this.prevSearchedKeyword === searchedString)
            return;
        if(typeof this._source != typeof undefined){
            this._source.cancel('Operation Cancelled');
        }
        this.prevSearchedKeyword = searchedString;
        this.exactMatch = null;
        let autoSuggestorReults = [];

        this.setState({showRecentSearches: false, resetActive: true, showUserSearches : false});
        this._source = axios.CancelToken.source();
        let config = {cancelToken: this._source.token};

        let extraParams = null;
        if(this.props.extraSearchParams != null && this.props.extraSearchParams.searchType == 'rankings'){
          extraParams = this.props.extraSearchParams.rankingSearchParams;
        }

        fetchTupleData(searchedString, config, extraParams).
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
               this.setState({autosuggestorTuples : autoSuggestorReults});
            }
        }).
        catch(function(thrown) {
            if (axios.isCancel(thrown)) {
            } else {
                //console.log(thrown);
            }
        });
    }

    anaClick(){
        trackEvent('Auto_suggestor', 'Auto_suggestor_Ask_New_Question');
        const searchedString = document.getElementById('searchInput').value.trim();
        if(searchedString) {
            setCookie('asked_q', searchedString,1);
        }
        window.location.href = domainName + '/mAnA5/AnAMobile/getQuestionPostingAmpPage';
    }
    /*
     * no match found before this (as we are taking priority into account), autosuggestor result and typed keyword are same ,
     * the result is not part of {$SearchConfig.BUCKET_IGN_LIST} bucket. Story-> https://infoedge.atlassian.net/browse/SS-350
     */
    matchesKeyword(tupleData){
        if(!this.exactMatch && tupleData.data.name.toLowerCase() === tupleData.keyword.toLowerCase()
            && SearchConfig.BUCKET_IGN_LIST.indexOf(tupleData.bucket) === -1) {
            return true;
        }
        return false;
    }

    generateRecentSearchHtml(){

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
        const recentSearchesList = recentSearchesData.map((tuple, i) =>
            {return (tuple.bucket || (typeof tuple.tupleData == 'object' && tuple.tupleData.type)) ? //Closed Search
                    (<li className="clearBoth" key={'recentTuple_'+i}>
                    <a href = 'javascript:void(0);' onClick={this.onClickSuggestion.bind(this, tuple.tupleData, tuple.bucket, 'recentSearch', '')}>
                        <span className="getName lead">{tuple.tupleData.name}</span>
                        <span className="srchType">{tuple.bucket}</span></a>
                </li>) : (typeof  tuple.tupleData == 'object' ? <li className="clearBoth" key={'recentTuple_'+i}>
                        <Link to={tuple.tupleData.url} onClick={this.trackDataRecentSearches.bind(this, tuple.tupleData)}><span className="getName lead bind-text">{tuple.tupleData.name}</span></Link>
                    </li> : '') ;
            }
        );
        return(
            <div className="recentKnocks">
                <div className="srch_banner"><i className="srchspritev1  recent_srch"></i> Recent Searches <a href='javascript:void(0);' onClick={this.clearRecentSearchCookie.bind(this)} className="clearMe">Clear All</a> </div>
                <div>
                    <ul className="srchFilterUl">
                        {recentSearchesList}
                    </ul>
                </div>
            </div>
        );
    }
    generateUserSearches(){
        const trendingSearchData = this.props.trendingData;
        if(!trendingSearchData || !trendingSearchData.solrResults || !trendingSearchData.bucketName)
            return null;
        const trendingSearchesList = trendingSearchData.solrResults.map((tuple, i) =>
                <li className="clearBoth" key={'recentTuple_'+i}
                    onClick={this.onClickSuggestion.bind(this, tuple, trendingSearchData.bucketName[tuple.type], 'trendingSearch', '')}>
                    <a href = 'javascript:void(0);'>  <span className="getName lead">{tuple.name}
                    </span>
                        <span className="srchType">{trendingSearchData.bucketName[tuple.type]}</span></a>
                </li>
        );
        if(!trendingSearchesList || !trendingSearchesList.length > 0)
            return;
        return(
            <div className="recentKnocks">
                <div className="srch_banner"><i className="srchspritev1  trending_srch"></i> Trending Searches</div>
                <div>
                    <ul className="srchFilterUl">
                        {trendingSearchesList}
                    </ul>
                </div>
            </div>
        );
    }
    generateHtml()
    {
            let html ;
            const suggestorTuples = this.state.autosuggestorTuples;
            const suggestorList = suggestorTuples.map((tuple, i) =>
                <li className="clearBoth" key={'suggestorTuple_'+i}>
                    <a href = 'javascript:void(0);' onClick={this.onClickSuggestion.bind(this, tuple.data, tuple.bucket, 'close', tuple.keyword)}><span className="getName lead"> <span dangerouslySetInnerHTML={highlightTypedKeyword(tuple.data.name, tuple.keyword)}></span>
                    {(tuple.bucket === SearchConfig.TOPIC_BUCKET && tuple.data.questionCount && tuple.data.questionCount > 0) ?
                        (<span className="stats">
                       <span className="statsCount">{numberFormatter(tuple.data.questionCount)}{tuple.data.questionCount > 1 ? ' Questions': ' Question'}</span>
                        {tuple.data.answerCount && tuple.data.answerCount > 0 ?
                            (<span className="statsCount">, {numberFormatter(tuple.data.answerCount)} {tuple.data.answerCount > 1 ? ' Answers' : ' Answer'}
                            </span>): null}
                        </span>):null
                    }</span>
                    <span className="srchType"> {tuple.bucket} </span></a>
                </li>
            );
            /*let searchInput = (<input type="text" autofocus={true} id='searchInput'
                                      placeholder="Search Colleges, Exams, QnA & Articles"
                                      onChange={this.OnChangeSearchString} onKeyUp={this.onSearchKeyPress.bind(this)}/>);
            if(this.props && this.props.location && this.props.location.keyword){
                searchInput =  (<input type="text"  value = {this.props.location.keyword} autofocus={true} id='searchInput'
                                       placeholder="Search Colleges, Exams, QnA & Articles"
                                       onChange={this.OnChangeSearchString} onKeyUp={this.onSearchKeyPress.bind(this)}/>);
            }*/
            let placeholder = 'Search Colleges, Exams, QnA & Articles';
            if(this.props.extraSearchParams != null && this.props.extraSearchParams.searchType == 'rankings'){
              placeholder = 'Search Colleges';
            }
            html = (
                <section className="newSearch">
                    <div className="articleSearch">
                        <div className="searchHold">
                            {(!this.props.pageReq || !this.props.pageReq.mobileApp) &&  <span onClick={this.onBack.bind(this)} className="getmebck"><i className="srchspritev1 bckIco"></i></span> }
                            <input type="text"  value = {this.state.typedKeyword} autoFocus='autofocus' id='searchInput'
                                   placeholder={placeholder}
                                   onChange={this.OnChangeSearchString} onKeyUp={this.onSearchKeyPress.bind(this)}/>
                            {this.state.resetActive ? <label htmlFor="srchIco"><a href="javascript:void(0);" className="lyr-crs" onClick={this.onReset.bind(this)}>&times;</a></label> : ''}
                        </div>
                        {this.state.showLineLoader && <div className="fixed-loader-strip">
                            <div className="loader-line shimmer"></div>
                        </div>}
                        <div className="srchRslts">
                            <div className="resultSet">
                                {this.props.showRecentSearch && this.state.showRecentSearches ? this.generateRecentSearchHtml():''}
                                {this.props.showUserSearches && this.state.showUserSearches ? this.generateUserSearches():''}
                                <div className="autoSuggestorResults">
                                    <div>
                                        <ul className="srchFilterUl">
                                            {suggestorList}
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div className="fixedsrchbtm">
                            <div className="ask_new">
                                <a onClick = {this.anaClick.bind(this)}><i className="srchspritev1 ask_q"></i>
                                    Ask New Question</a>
                            </div>
                        </div>
                    </div>
                </section>
            );
        return html;
    }
    render()
    {
      if(this.props.isSearchLayerOpen === false){
        return null;
      }
        let html = [];
        html = this.generateHtml();

        return (
            <React.Fragment>
                <AdvancedSearchLayer  onRef={ref => (this.advSearchLayerRef = ref)} filtersData = {this.filtersData} collegeData = {this.collegeData} openLayer = {this.state.showAdvancedSearchLayer} onBack = {this.onBack.bind(this)} onClose = {this.closeAdvancedLayer.bind(this)} historyData = {this.props.location} typedKeyword={this.prevSearchedKeyword} numOfResults = {this.state.autosuggestorTuples.length} searchType={this.searchType} onReset = {this.onReset.bind(this)} />
                <Loader show={this.state.showLoader}/>
                <FullPageLayer onRef={ref => (this.searchLayerRef = ref)} data={html} isHeaderAllowed={false} heading="Search" isOpen={true} isNopadding={true} disableScroll = {false} isAnimationRequired = {false}/>
            </React.Fragment>
        );
    }

    prepareTrackingData(dataObj, data = null){
        if(window.referrer)
            dataObj.referrerUrl = window.referrer;
        else if (this.canUseDOM() && document.referrer){
            dataObj.referrerUrl = document.referrer;
        }
        if(data){
            dataObj.searchedKeyword = data.name;
            dataObj.selectedKeywordId = data.id
            dataObj.landingUrl = data.url;
            dataObj.selectedKeywordType = data.type + (data.subType ? '-' + data.subType:'');

        }
        if(this.state.autosuggestorTuples) {
            dataObj.numOfSuggestionsShown = this.state.autosuggestorTuples.length;
        }
        dataObj.device = 'mobile';
        trackSearch(dataObj)
    }
    trackDataRecentSearches(tupleData){
        trackEvent('Recent Search', 'click');
        this.updateRecentSearchesCookie(tupleData);
        this.prepareTrackingData({'typedKeyword' : tupleData.name, 'searchedKeyword' : tupleData.name, 'searchType': 'open',
            'isRecent' : true, 'landingUrl' : tupleData.url});
    }
}
SearchLayer.defaultProps = {
  showRecentSearch : true,
  isSearchLayerOpen : true, showUserSearches : true
}
function mapStateToProps(state) {
    return {
        trendingData : state.trendingData,
        config : state.config
    }
}

function mapDispatchToProps(dispatch){
    return bindActionCreators({fetchTrendingSearchesData}, dispatch);
}

export default connect(mapStateToProps, mapDispatchToProps)(withRouter(SearchLayer));
