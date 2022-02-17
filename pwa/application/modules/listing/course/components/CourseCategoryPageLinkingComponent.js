import PropTypes from 'prop-types'
import React from 'react';
import './../assets/courseCategoryPageLink.css';
import './../assets/courseCommon.css';
import {Link} from 'react-router-dom';
import {splitPathQueryParamsFromUrl} from './../../../../utils/commonHelper';
import{removeDomainFromUrl} from "./../../../../utils/urlUtility";

function CourseCategoryPageLink(props)
{
	let rankingList = '';
	let categoryList = '';

	if(props.rankingInterlinking!=null){
		rankingList = props.rankingInterlinking.map(function(info, index){
			let anchorText = info.title;
			if(!info.title.startsWith("Top ")) {
				anchorText = "Top "+ info.title;
			}
			return (<li key={index}>
				<Link className="link" to={removeDomainFromUrl(info.url)}>{anchorText}</Link>
			</li>)
		});
	}

	if(props.categoryInterlinking!=null){
		categoryList = props.categoryInterlinking.map(function(info, index){
			var urlObject = splitPathQueryParamsFromUrl(info.url);
			var urlPathname = "";
			var searchParams = "";
			if(typeof urlObject == "object" && typeof urlObject.search != "undefined" && urlObject.search != "")
			{
				searchParams = urlObject.search;
			}
			if(typeof urlObject == "object" && typeof urlObject.pathname != "undefined" && urlObject.pathname != "")
			{
				urlPathname = removeDomainFromUrl(urlObject.pathname);
			}
			if(!urlPathname || urlPathname == "" || typeof urlPathname == 'undefined')
				return;
			return (<li key={index}>
				<Link to={{ pathname : urlPathname,search : searchParams}} className="link">{info.title}</Link>
			</li>)
		});
	}

	return(
		<section id="viewCollegeSec" className='viewCollegeSec'>
			<div className="_container">
				{props.rankingInterlinking!=null && Array.isArray(props.rankingInterlinking) && props.rankingInterlinking.length > 0?
					<React.Fragment><h2 className='headL2'>View colleges by ranking</h2>
						<div className= "_subcontainer">
							<ul className='nrml-lst'>
								{rankingList}
							</ul>
						</div></React.Fragment>:''}
				{props.categoryInterlinking!=null && Array.isArray(props.categoryInterlinking) && props.categoryInterlinking.length > 0 ?
					<React.Fragment><h2 className='headL2'>View colleges by location</h2>
						<div className= "_subcontainer">
							<ul className='nrml-lst'>
								{categoryList}
							</ul>
						</div></React.Fragment>:''}
			</div>
		</section>
	)
}

export default CourseCategoryPageLink;

CourseCategoryPageLink.propTypes = {
	categoryInterlinking: PropTypes.any,
	rankingInterlinking: PropTypes.any
}