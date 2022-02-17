import React from 'react';
import './../../listing/categoryList/assets/pagination.css';
import Anchor from './../../reusable/components/Anchor';
import {removeParamFromUrl} from "./../../../utils/commonHelper";

export function pagination(totalItems, currentPage, pageSize=10, maxPages=5) {
    // calculate total pages
    let totalPages = Math.ceil(totalItems / pageSize);

    // ensure current page isn't out of range
    if (currentPage < 1) {
        currentPage = 1;
    } else if (currentPage > totalPages) {
        currentPage = totalPages;
    }

    let startPage=0, endPage =0;
    if (totalPages <= maxPages) {
        // total pages less than max so show all pages
        startPage = 1;
        endPage = totalPages;
    } else {
        // total pages more than max so calculate start and end pages
        let maxPagesBeforeCurrentPage = Math.floor(maxPages / 2);
        let maxPagesAfterCurrentPage = Math.ceil(maxPages / 2) - 1;
        if (currentPage <= maxPagesBeforeCurrentPage) {
            // current page near the start
            startPage = 1;
            endPage = maxPages;
        } else if (currentPage + maxPagesAfterCurrentPage >= totalPages) {
            // current page near the end
            startPage = totalPages - maxPages + 1;
            endPage = totalPages;
        } else {
            // current page somewhere in the middle
            startPage = currentPage - maxPagesBeforeCurrentPage;
            endPage = currentPage + maxPagesAfterCurrentPage;
        }
    }

    // calculate start and end item indexes
    let startIndex = (currentPage - 1) * pageSize;
    let endIndex = Math.min(startIndex + pageSize - 1, totalItems - 1);

    // create an array of pages to ng-repeat in the pager control
    let pages = Array.from(Array((endPage + 1) - startPage).keys()).map(i => startPage + i);

    // return object with all pager properties required by the view
    return {
        totalItems: totalItems,
        currentPage: parseInt(currentPage),
        pageSize: pageSize,
        totalPages: totalPages,
        startPage: startPage,
        endPage: endPage,
        startIndex: startIndex,
        endIndex: endIndex,
        pages: pages
    };
}

function createPaginationList(params, url){
	let items = [];
	let prevUrl   = (params.currentPage>=2) ? url+(params.currentPage-1) : '', prevlist = '';
	if(prevUrl){
		prevlist = <li key="prev"><Anchor className="leftarrow" to={prevUrl}> <i className="Lft-arrw"></i></Anchor></li>
	}else{
		prevlist = <li key="prev"><Anchor className="leftarrow disable-link" link={false} to="javascript:void(0);"> <i className="LftDisbl-arrw"></i> </Anchor></li>		
	}
	
	items.push(prevlist);
	params.pages.forEach((value)=>{
		let list = <li key={value}><Anchor className={(params.currentPage == value) ? 'active' : ''} to={url+value}>{value}</Anchor></li>
		items.push(list);
	});

	let nextUrl = (params.currentPage<params.totalPages) ? url+(params.currentPage+1) : '', nextlist = '';
	if(nextUrl){
		nextlist = <li key="next"><Anchor className="rightarrow" to={nextUrl}><i className="Rgt-arrw"></i> </Anchor></li>;
	}else{
		nextlist = <li key="next"><Anchor className="rightarrow disable-link" link={false} to="javascript:void(0);"><i className="RgtDisbl-arrw"></i> </Anchor></li>;
	}
	items.push(nextlist);
	return items;
}

function getQueryOperator(url,queryParam){
	let operator = '?';
	if(url.indexOf('?'+queryParam) !=-1){
		operator = '?';
	}else if(url.indexOf('&'+queryParam) !=-1){
		operator = '&';
	}else if(url.indexOf('?') !=-1){
		operator = '&';
	}
	return operator;
}	

const Pagination=(props)=>{
	if(props.totalItems <= props.pageSize || (props.pageSize<=0 || props.pageSize=='') || props.totalItems<=0 || !Number.isInteger(props.totalItems) || !Number.isInteger(props.currentPage) || props.currentPageUrl == '' || props.queryParam == '' || (props.pageSize && !Number.isInteger(props.pageSize)) || (props.maxPages && !Number.isInteger(props.maxPages))){
		return null;
	}
	let result        = pagination(props.totalItems, props.currentPage, props.pageSize, props.maxPages);
	let paginationUrl = removeParamFromUrl(props.queryParam, props.currentPageUrl);
    	paginationUrl = paginationUrl+getQueryOperator(props.currentPageUrl, props.queryParam)+props.queryParam+'=';
	return (
		<div className="pagnation-col">
		    <ul className="pagniatn-ul">
				{createPaginationList(result, paginationUrl)}	        
		    </ul>
		</div>
	);
}
export default Pagination;