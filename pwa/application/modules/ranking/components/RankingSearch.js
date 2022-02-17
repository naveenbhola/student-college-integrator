import PropTypes from 'prop-types'
import React from 'react';
import axios from 'axios';

import {fetchTupleData} from './../../search/actions/SearchAction';

import './../assets/RankingSearch.css'

class RankingSearch extends React.Component {
  constructor(props){
    super(props);
    this.state = {
      typedKeyword : this.props.textValue,
      suggestionList : [],
      activeSuggestion : -1
    };
    this.extraSearchParams = {
      searchType: 'rankings',
      rankingSearchParams : {
        suggestionCount : 30,
        suggestionType : 'institute',
        sby : 'popularity',
        appliedFilters : {
          rankingPageIds : [this.props.rankingPageId]
        }
      }
    }
  }
  onChangeSearchString = (event) => {
    this.setState({typedKeyword : event.target.value});
    let searchedString = event.target.value.trim();
    if(!searchedString){
      if(typeof this._source != 'undefined'){
        this._source.cancel('Operation Cancelled');
      }
      this.setState({activeSuggestion : -1, suggestionList : []});
      return;
    }
    if(typeof this._source != 'undefined'){
      this._source.cancel('Operation Cancelled');
    }
    let autoSuggestorReults = [];
    this._source = axios.CancelToken.source();
    let config = {cancelToken: this._source.token};

    let extraParams = null;
    if(this.extraSearchParams != null && this.extraSearchParams.searchType == 'rankings'){
      extraParams = this.extraSearchParams.rankingSearchParams;
    }
    fetchTupleData(searchedString, config, extraParams).then((response) => {
      if(response.status === 200 && response.data.data){
        let bucket = response.data.data.bucketName;
        const searchedKeyword = response.data.data.searchKeyword;
        response.data.data.solrResults.forEach((solrResult) => {
          let tupleData = {'data': solrResult, 'bucket': bucket[solrResult.type], 'keyword':searchedKeyword };
          autoSuggestorReults.push(tupleData);
        });
        this.setState({suggestionList : autoSuggestorReults});
      }
    }).
    catch(function() {
      // if (axios.isCancel(thrown)) {
      // }
    });
  }
  onSearchKeyPress = (event) => {
    if (event.keyCode !== 13){
      return;
    }
    if(this.state.suggestionList.length == 0 || this.state.activeSuggestion == -1){
      this.performSearch(event.target.value.trim());
    }else if(this.state.suggestionList.length > 0 && this.state.activeSuggestion >= 0 && this.state.activeSuggestion < this.state.suggestionList.length){
      let tuple = this.state.suggestionList[this.state.activeSuggestion];
      this.onClickSuggestion(tuple.data, tuple.bucket, 'close', tuple.keyword);
    }
  }
  performSearch = (searchedString) => {
    if(!searchedString){
      return;
    }
    // if(false){
    //   //exact match case, handle later
    // }

      if(this.extraSearchParams != null && this.extraSearchParams.searchType == 'rankings'){
        this.handleRankingSearchClick(null, searchedString);
        return;
      }
      //extend later if required

  }
  onClickSuggestion = (tupleData, bucket, searchType, searchedKeyword = null) => {
    if(this.extraSearchParams != null && this.extraSearchParams.searchType == 'rankings'){
      this.handleRankingSearchClick(tupleData, searchedKeyword);
      return;
    }
    //extend later if required
  }
  handleRankingSearchClick = (tupleData, searchedString) => {
    let institutes = [];
    let finalSearchedString = searchedString;
    if(tupleData != null){
      institutes.push(tupleData.id);
      finalSearchedString = tupleData.name;
    }else if(this.state.suggestionList.length > 0){
      this.state.suggestionList.forEach(
          currTuple => {
            institutes.push(currTuple.data.id);
          }
      );
    }else{
      institutes = null;
    }
    this.props.fetchSearchData(institutes, finalSearchedString);
  }
  componentDidMount = () => {
    window.addEventListener('keydown', this.onKeyDown.bind(this));
  }
  onKeyDown = (event) => {
    if(!(this.state.suggestionList.length > 0)){
      return;
    }
    switch (event.keyCode) {
      case 40: //key down                    
      case 38: {//key up                       
        let indexAdder = event.keyCode - 39;
        if(this.state.suggestionList.length > 0 && this.state.activeSuggestion + indexAdder >= -1 && this.state.activeSuggestion + indexAdder < this.state.suggestionList.length){
          event.preventDefault();
          this.setState({activeSuggestion : this.state.activeSuggestion + indexAdder});
          let suggestorBoxDiv = document.querySelector('#suggestionList');
          this.scrollOnKeyMovements(suggestorBoxDiv, event.keyCode, this.state.activeSuggestion);
        }
        break;
      }
    }
  }
  scrollOnKeyMovements = (suggestorBoxDiv, keyCode, activeIndex) => {
    let tupleHeight = suggestorBoxDiv.children[0].clientHeight;
    if(keyCode === 40 && suggestorBoxDiv.scrollTop + suggestorBoxDiv.clientHeight <= tupleHeight * (activeIndex + 1)){
      suggestorBoxDiv.scrollTop = suggestorBoxDiv.scrollTop + tupleHeight;
    }
    if(keyCode === 38 && suggestorBoxDiv.scrollTop > 0){
      suggestorBoxDiv.scrollTop = suggestorBoxDiv.scrollTop - tupleHeight > 0 ? suggestorBoxDiv.scrollTop - tupleHeight : 0;
    }
  }
  render(){
    let tupleList = [], bgClass = '';
    this.state.suggestionList.forEach(
        (tuple, iter) => {
          bgClass = '';
          if(this.state.activeSuggestion == iter){
            bgClass = 'tupleBg';
          }
          tupleList.push(<li className={bgClass} key={'key_'+tuple.data.id} onClick={this.onClickSuggestion.bind(this, tuple.data, tuple.bucket, 'close', tuple.keyword)}>{tuple.data.name}</li>);
        }
    );
    return (
        <div className="filter_space filter_pwasearch">
          <div className="search-container">
            <div className="search-box">
              <div className="search icon"></div>
              <input type="text" maxLength="100" value={this.state.typedKeyword} placeholder={this.props.placeholder} onClick={this.props.onClick} onFocus={this.onChangeSearchString.bind(this)} onChange={this.onChangeSearchString.bind(this)} onKeyUp={this.onSearchKeyPress.bind(this)} />
              {this.props.isSearchActive ? <div className="reset-srch" onClick={this.props.onResetSearch}></div> : null}
            </div>
            {this.props.isSearchLayerOpen && this.state.suggestionList.length > 0 ? <div className="search-suggestion-container"><ul id="suggestionList">{tupleList}</ul></div> : null}
          </div>
        </div>
    );
  }
}

RankingSearch.defaultProps ={
  placeholder : 'Search Colleges',
  value : '',
  isSearchLayerOpen : false
};

export default RankingSearch;

RankingSearch.propTypes = {
  fetchSearchData: PropTypes.any,
  isSearchActive: PropTypes.any,
  isSearchLayerOpen: PropTypes.bool,
  onClick: PropTypes.any,
  onResetSearch: PropTypes.any,
  placeholder: PropTypes.string,
  rankingPageId: PropTypes.any,
  textValue: PropTypes.any,
  value: PropTypes.string
}