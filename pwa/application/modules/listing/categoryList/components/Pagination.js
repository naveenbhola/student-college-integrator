import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import { Link, withRouter } from "react-router-dom";
import styles from '../assets/pagination.css';
import {getQueryVariable, parseQueryParams} from "../../../../utils/commonHelper";
import Analytics from './../../../reusable/utils/AnalyticsTracking';
import {getRankingPageParamStr} from './../../../ranking/utils/rankingUtil';


class Pagination extends Component{

  constructor(props){
    super(props);
  }

  empty (mixedVar) {
    //   example 1: empty(null)
    //   returns 1: true
    //   example 2: empty(undefined)
    //   returns 2: true
    //   example 3: empty([])
    //   returns 3: true
    //   example 4: empty({})
    //   returns 4: true
    //   example 5: empty({'aFunc' : function () { alert('humpty'); } })
    //   returns 5: false

    var undef
    var key
    var i
    var len
    var emptyValues = [undef, null, false, 0, '', '0']

    for (i = 0, len = emptyValues.length; i < len; i++) {
      if (mixedVar === emptyValues[i]) {
        return true
      }
    }

    if (typeof mixedVar === 'object') {
      for (key in mixedVar) {
        if (mixedVar.hasOwnProperty(key)) {
          return false
        }
      }
      return true
    }

    return false
  }

  createPrevUrlsHTML(prevUrl){
    let html = '';
    let queryString = '';
    if(typeof window != 'undefined' && window.location.search!='')
    {
      queryString = window.location.search;
    }
    var self = this;
    let rankQueryStr = '', urlParam;
    html = prevUrl.map(function(data, index){
      if(self.props.from == 'ranking'){
        urlParam = parseQueryParams(queryString);
        if(typeof urlParam.source != 'undefined' && urlParam.source > 0){
          rankQueryStr = '&source='+urlParam.source;
        }
        return(<li key={data.pageNumber}>
          {<Link to={data.url+rankQueryStr} onClick={self.trackEvent.bind(self, 'Pagination','click')} >{data.pageNumber}</Link>}
        </li>);
      }else{
        return(<li key={data.pageNumber}>
          {<Link to={data.url+queryString} onClick={self.trackEvent.bind(self, 'Pagination','click')} >{data.pageNumber}</Link>}
        </li>);
      }
    });
    return html;
  }


  trackEvent(action, label)
  {
    let category = 'CATEGORY_PAGE_MOBILE';
    if(typeof this.props.gaTrackingCategory !=='undefined'){
      category = this.props.gaTrackingCategory;
    }
    Analytics.event({category : category, action : action, label : label});
    window.filterCall=false;
  }

  createCurrentUrlsHTML(currentPageNumber, currentUrl){
    let html = '';
    let queryString = '';
    if(typeof window != 'undefined' && window.location.search!='')
    {
      queryString = window.location.search;
    }
    let rankQueryStr = '', urlParam;
    if(this.props.from == 'ranking'){
      urlParam = parseQueryParams(queryString);
      if(typeof urlParam.source != 'undefined' && urlParam.source > 0){
        rankQueryStr = '&source='+urlParam.source;
      }
      return(<li key={currentPageNumber}>
          {<a className="active" href="javascript:void(0);" onClick={this.trackEvent.bind(this,'Pagination','click')}>{currentPageNumber}</a>}
        </li>);
    }else{
      return(<li key={currentPageNumber}>
          {<Link className="active" to={currentUrl+queryString} onClick={this.trackEvent.bind(this,'Pagination','click')}>{currentPageNumber}</Link>}
        </li>);
    }
  }

  createNextUrlsHTML(nextUrl){
    let html = '';
    let queryString = '';
    if(typeof window != 'undefined'  && window.location.search!='')
    {
      queryString = window.location.search;
    }
    var self = this;
    let rankQueryStr = '', urlParam;
    html = nextUrl.map(function(data, index){
      if(self.props.from == 'ranking'){
        urlParam = parseQueryParams(queryString);
        if(typeof urlParam.source != 'undefined' && urlParam.source > 0){
          rankQueryStr = '&source='+urlParam.source;
        }
        return(<li key={data.pageNumber}>
          {<Link to={data.url+rankQueryStr} onClick={self.trackEvent.bind(self, 'Pagination','click')}>{data.pageNumber}</Link>}
        </li>);
      }else{
        return(<li key={data.pageNumber}>
          {<Link to={data.url+queryString} onClick={self.trackEvent.bind(self, 'Pagination','click')}>{data.pageNumber}</Link>}
        </li>);
      }
    });
    return html;
  }
  createPaginationUrlsForSRP(pageNumbers, queryParams){
      const pathName = this.props.location.pathname;
      const html = pageNumbers.map(function(data){
          return(<li key={data.pageNumber}>
              {<Link to={pathName + queryParams + '&pn='+data.pageNumber}>{data.pageNumber}</Link>}
          </li>);
      });
      return html;

  }
  handleRankingPageClick(url, obj){
    return;
    this.trackEvent('Pagination','click')
    let qryStr = url.split('?');
    let queryObj = parseQueryParams('?'+qryStr[1]);
    if(typeof window.selectedSource != 'undefined' && window.selectedSource != null && window.selectedSource > 0){
      queryObj.source = window.selectedSource;
      window.history.pushState({urlPath: url+'&source='+queryObj.source}, "", url+'&source='+queryObj.source);
      this.props.onRankingPageSelect(getRankingPageParamStr(url+'&source='+queryObj.source, queryObj));
    }else{
      window.history.pushState({urlPath: url}, "", url);
      this.props.onRankingPageSelect(getRankingPageParamStr(url, queryObj));
    }
  }
  render(){
      if(this.props.categoryData == null){
        return null;
      }
      let nextUrlsHTML=null, prevUrl, nextUrl, prevUrlsHTML =null, currentUrlsHTML, queryString = '';
      if(typeof window != 'undefined'  && window.location.search!='')
      {
          queryString = window.location.search;
      }
      if(this.props.isSrp){
          let queryParams = this.props.location.search;
          let pn =  getQueryVariable('pn', queryParams);
          queryParams = queryParams.replace('&pn='+pn,'');
          let prevUrls = this.props.categoryData.paginationData.prevUrls;
          let nextUrls = this.props.categoryData.paginationData.nextUrls;
          let currPageNumber = this.props.categoryData.paginationData.currentPageNUmber;
          prevUrlsHTML = this.createPaginationUrlsForSRP(prevUrls, queryParams);
          currentUrlsHTML = (
              <li key={currPageNumber}>
                  {<Link className="active" to={this.props.location.pathname+ queryParams + '&pn='+currPageNumber}>{currPageNumber}</Link>}
              </li>);
          nextUrlsHTML = this.createPaginationUrlsForSRP(nextUrls, queryParams);
          prevUrl = prevUrls && prevUrls.length > 0 ?
              (<Link className='leftarrow' to={this.props.location.pathname + queryParams + '&pn=' + (currPageNumber - 1)} onClick={this.trackEvent.bind(this, 'Prev Page','click')} >
                  <i className="Lft-arrw"></i></Link> ) :  (<a className="leftarrow disable-link"> <i className="LftDisbl-arrw"></i> </a>);

          nextUrl = nextUrls && nextUrls.length > 0  ?
              (<Link className='rightarrow' onClick={this.trackEvent.bind(this, 'Next Page','click')} to={this.props.location.pathname + queryParams + '&pn=' + nextUrls[0].pageNumber}>
                  <i className="Rgt-arrw"></i> </Link>) : (<a className="rightarrow disable-link"> <i className="RgtDisbl-arrw"></i> </a>);
      }
      else {
          if(typeof this.props.categoryData !='undefined' && typeof this.props.categoryData.paginationData !='undefined' && this.props.categoryData.paginationData && typeof this.props.categoryData.seoData !='undefined' && this.props.categoryData.seoData){
            prevUrlsHTML = this.createPrevUrlsHTML(this.props.categoryData.paginationData.prevUrls);
            currentUrlsHTML = this.createCurrentUrlsHTML(this.props.categoryData.paginationData.currentPageNUmber, this.props.categoryData.seoData.canonicalUrl);
            nextUrlsHTML = this.createNextUrlsHTML(this.props.categoryData.paginationData.nextUrls);
            if(this.props.from == 'ranking'){
              let finalPrevUrl = '';
              if(this.props.categoryData.seoData != null && this.props.categoryData.seoData.prevUrl != null && this.props.categoryData.seoData.prevUrl.indexOf("pageNo=") == -1){
          			finalPrevUrl += '?pageNo=1';
          		}else{
                finalPrevUrl = this.props.categoryData.seoData.prevUrl;
              }
              let urlParam = parseQueryParams(queryString);
              let rankQueryStr = '';
              if(typeof urlParam.source != 'undefined' && urlParam.source > 0){
                rankQueryStr = '&source='+urlParam.source;
              }
              prevUrl = !this.empty(this.props.categoryData.seoData.prevUrl) ?
                (<Link className='leftarrow' onClick={this.trackEvent.bind(this, 'Prev Page','click')} to={finalPrevUrl+rankQueryStr}> <i className="Lft-arrw"></i> </Link>) :
                (<a className="leftarrow disable-link"> <i className="LftDisbl-arrw"></i> </a>);
              nextUrl = !this.empty(this.props.categoryData.seoData.nextUrl)?
                (<Link className='rightarrow' onClick={this.trackEvent.bind(this, 'Next Page','click')} to={this.props.categoryData.seoData.nextUrl+rankQueryStr}> <i className="Rgt-arrw"></i> </Link> ):
                (<a className="rightarrow disable-link"> <i className="RgtDisbl-arrw"></i> </a>);
            }else{
            prevUrl = !this.empty(this.props.categoryData.seoData.prevUrl) ?
                (<Link className='leftarrow' to={this.props.categoryData.seoData.prevUrl+queryString} onClick={this.trackEvent.bind(this,'Prev Page','click')}> <i className="Lft-arrw"></i> </Link>) :
                (<a className="leftarrow disable-link"> <i className="LftDisbl-arrw"></i> </a>);
            nextUrl = !this.empty(this.props.categoryData.seoData.nextUrl)?
                (<Link className='rightarrow' to={this.props.categoryData.seoData.nextUrl+queryString} onClick={this.trackEvent.bind(this, 'Next Page','click')}> <i className="Rgt-arrw"></i> </Link> ):
                (<a className="rightarrow disable-link"> <i className="RgtDisbl-arrw"></i> </a>);
		}
          }
      }

    if(prevUrlsHTML == null || nextUrlsHTML ==null || (prevUrlsHTML.length==0 && nextUrlsHTML.length==0)){
      return '';
    }


    return(
      <div id="searchPagination" className="pagnation-col">
        <ul className="pagniatn-ul">
        <li>
            {prevUrl}
        </li>
          {prevUrlsHTML}
          {currentUrlsHTML}
          {nextUrlsHTML}
          <li>
              {nextUrl}
          </li>
        </ul>
      </div>
    );
  }
}

export default withRouter(Pagination);
