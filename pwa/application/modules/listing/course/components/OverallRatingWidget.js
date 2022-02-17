import PropTypes from 'prop-types'
import React from 'react';
import './../assets/overallRatingWidget.css';
import './../assets/courseCommon.css';
import { add_query_params } from './../../../../utils/urlUtility';
import {addingDomainToUrl} from './../../../../utils/commonHelper';


class OverallRatingWidget extends React.Component{

	constructor(props)
	{
		super(props);
		this.state = {
			activeReview : '',
		}

		this.aggregateReviewWidgetData = this.props.aggregateReviewData.aggregateReviewData;
		this.aggregateRatingDisplayOrder = this.props.aggregateReviewData.aggregateRatingDisplayOrder;
		this.intervalsDisplayOrder = this.props.aggregateReviewData.intervalsDisplayOrder;

		this.reviewsCount = this.props.reviewsCount;
		this.anchorText = this.props.reviewsCount+' reviews';
		this.viewAllAnchorText = 'View all '+this.props.reviewsCount+' reviews';
		this.allReviewsUrl = addingDomainToUrl(this.props.reviewUrl,props.config.SHIKSHA_HOME);
	}

	intervalWidget(){
		var intervalsDisplayOrder = this.intervalsDisplayOrder;
		var returnElemet = [];
		if(this.props.reviewTypes =='placement' && !this.aggregateReviewWidgetData['intervalRatingCountForPlacement']){
			return null;
		}

		for(var key in intervalsDisplayOrder){
			let intervalReviewCount;
			if(this.props.reviewTypes!='placement'){
				intervalReviewCount = this.aggregateReviewWidgetData['intervalRatingCount'][key];
			}
			else{
				intervalReviewCount = this.aggregateReviewWidgetData['intervalRatingCountForPlacement'][key];	
			}
			var percentage = Math.round(intervalReviewCount/this.aggregateReviewWidgetData['totalCount']*100,0);
			var disableClass = '';
			if(percentage == 0){
				disableClass = 'disablefilter';
			}
			if(intervalReviewCount > 0) {
				var ratingFilter = key.slice(0,1);

				var url = add_query_params(this.allReviewsUrl,'rating='+ratingFilter);
			}
			if(this.props.reviewTypes =='placement'){
				url = 'javscript:void(0)';
			}
			var ele = (
				<div className={'starBar '+disableClass} key={"starbar_"+key}>
					<div className="starC" key={'starC_'+key}>
					{this.props.reviewTypes =='placement'?
					<a className='avoidLink' href='javascript:void(0)'>{intervalsDisplayOrder[key]} star </a>
					:<a href={url} key={'starlink_'+key}> {intervalsDisplayOrder[key]} star</a>
					}
					</div>
					<div className="loadbar" key={'starbar_'+key}>
						<div className="fillBar" style= {{width: percentage + '%'}} key={'fillBar_'+key} ></div>
					</div>
					<div className="starPrgrs" key={'starPrgrs_'+key}>{percentage}%</div>
				</div>
			);
			returnElemet.push(ele);
		}

		return(
			<div className="rvwProgress">
				<p className='heading'> Distribution of Rating </p>
				{returnElemet}
			</div>
		)

	}

	aggregateDifferentParamWidget(){
		var showAggreagtesByDifferentParams = false;
		var ratingparamList = [];
		for(var key in this.aggregateReviewWidgetData['aggregateRating']){
			if(parseInt(this.aggregateReviewWidgetData['aggregateRating'][key]['mean']) > 0){
				showAggreagtesByDifferentParams = true;
				break;
			}
		}
		if(showAggreagtesByDifferentParams){
			for(key in this.aggregateRatingDisplayOrder){
				if(parseInt(this.aggregateReviewWidgetData['aggregateRating'][key]['mean']) > 0){
					var ratingBar = this.aggregateReviewWidgetData['aggregateRating'][key]['mean'].toFixed(1)*20;
					var ele = (
						<div className="starBar" key = {'starBar_'+key}>
							<div className="componetText" key = {'componetText_'+key} > {this.aggregateRatingDisplayOrder[key]}</div>
							<div className='break_rating_star' key={'break_rating_star_'+key}>
								<div className="cRating" key = {'cRating_'+key}> {this.aggregateReviewWidgetData['aggregateRating'][key]['mean'].toFixed(1)}</div>
								<i className="empty_stars starBg " key={'empty_stars_'+key}>
									<i style={{width: ratingBar + '%'}} className="full_starts starBg" key={'full_starts'+key}></i>
								</i>
							</div>
						</div>


					);
					ratingparamList.push(ele);
				}
			}
			return(
				<div className="rvwRight">
					<div className="align-cntr">
						<h2 className="ratingAll rtng-cmpt">Breakup of Rating</h2>
						{ratingparamList}
					</div>
				</div>
			)
		}
		else{
			return null;
		}

	}

	render(){
		// var isPaid = parseInt(this.props.isPaid);
		let mainHeading = 'Overall Rating ';
		var aggregateRating = parseFloat(this.aggregateReviewWidgetData['aggregateRating']['averageRating']['mean']);
		if(this.props.reviewTypes =='placement'){
			mainHeading = 'Overall Placement Rating ';
			aggregateRating = parseFloat(this.aggregateReviewWidgetData['aggregateRating']['avgSalaryPlacementRating']['mean']);
		}	

		var aggregateRatingPercent = aggregateRating*20;
		// var showAggregateWidget = true;
		// if(typeof isPaid !='undefined' && isPaid && aggregateRating<3.5){
		// 	showAggregateWidget = false;
		// }
		if(this.reviewsCount == 1){
			this.anchorText = this.reviewsCount+' review';
		}
		let reviewText1 = this.props.reviewsCount>1?'reviews':'review';
		let reviewText2 = this.props.verifiedtotalReviews>1?'reviews are':'review is';
		return (
			<div className=''>
			{this.props.verifiedtotalReviews!=0?
				<div className="rvw-h">
					<div className="rvwBlock recomended-flex">
						<div className="rvwLeft">
							<h2 className="ratingAll"> {mainHeading} <span>(Out of 5)</span></h2>
							<div className="rvwScore">
								<h3>{aggregateRating.toFixed(1)}</h3>
								<div className="infrontRvws">
									<i className="empty_stars">
										<i 	style= {{width: aggregateRatingPercent + '%'}} className="full_starts"></i>
									</i>
									<div className="refrnceTxt">
				    	        	    	<span> Based on {this.props.verifiedtotalReviews} Verified Reviews</span>
									</div>
								</div>
							</div>
						</div>
						{this.intervalWidget()}
						{this.props.reviewTypes!='placement' && this.aggregateDifferentParamWidget()}
					</div>
				</div>:''}
				<div className="allRvws">
				    <div className="rvwImg"></div>
				    {(this.props.reviewsCount>this.props.verifiedtotalReviews && this.props.verifiedtotalReviews!=0)?
				    <div className="getAllrvws">Out of {this.props.reviewsCount} published {reviewText1}, <span className="new-title">{this.props.verifiedtotalReviews} {reviewText2} verified.</span> The <span className="verified-tag"><i className="icon-verified-tag"></i>Verified</span> <span className="new-title">badge</span> indicates that the reviewer's details have been verified by Shiksha, and reviewers are <span className="new-title">bona fide students</span> of this college. These reviews and ratings have been given by students. Shiksha does not endorsed the same</div>:''}
				    {(this.props.reviewsCount==this.props.verifiedtotalReviews && this.props.verifiedtotalReviews!=0)?
				    <div className="getAllrvws">All <span className="new-title">{this.props.verifiedtotalReviews} {reviewText2} verified.</span> The <span className="verified-tag"><i className="icon-verified-tag"></i>Verified</span> <span className="new-title">badge</span> indicates that the reviewer's details have been verified by Shiksha, and reviewers are <span className="new-title">bona fide students</span> of this college. These reviews and ratings have been given by students. Shiksha does not endorsed the same</div>:''}
				    {this.props.verifiedtotalReviews==0?
				    <div className="getAllrvws">The reviewer's details of the following {this.props.reviewsCount} reviews have not been verified yet. These reviews and ratings have been given by students. Shiksha does not endorsed the same</div>:''}
				</div>
			</div>
		)
	}
}


export default OverallRatingWidget;

OverallRatingWidget.propTypes = {
	aggregateReviewData: PropTypes.any,
	config: PropTypes.any,
	isPaid: PropTypes.any,
	reviewUrl: PropTypes.any,
	reviewsCount: PropTypes.any
}
