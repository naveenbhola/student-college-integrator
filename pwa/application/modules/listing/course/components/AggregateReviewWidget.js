import PropTypes from 'prop-types'
import React from 'react';
import './../assets/aggregateReviewWidget.css';
import './../assets/courseCommon.css';
import Analytics from './../../../reusable/utils/AnalyticsTracking';
import {addingDomainToUrl} from './../../../../utils/commonHelper';

class AggregateReviewWidget extends React.Component
{
	constructor(props)
	{
		super(props);

		this.aggregateReviewWidgetData = this.props.aggregateReviewData.aggregateReviewData;
		this.aggregateRatingDisplayOrder = this.props.aggregateReviewData.aggregateRatingDisplayOrder;
		this.reviewsCount = this.props.reviewsCount;
		this.anchorText = this.props.reviewsCount+' reviews';
		this.viewAllAnchorText = 'View all '+this.props.reviewsCount+' reviews';
		this.allReviewsUrl = addingDomainToUrl(this.props.reviewUrl,this.props.config.SHIKSHA_HOME);
		this.state = {
			activeReview: ''
		};

	}

	markSelected(reviewUniqueKey)
	{
		this.trackEvent('drop_down',"click_ review_drop_down");
		let item = {};
		if(this.state.activeReview === reviewUniqueKey){
			item = {'activeReview':''};
		}else{
			item = {'activeReview':reviewUniqueKey};
		}
		this.setState(item);
	}

	componentWillMount(){
		if(this.reviewsCount === 1) {
			this.anchorText = this.reviewsCount+' review';
			this.viewAllAnchorText = 'View '+this.reviewsCount+ ' review';
		}
	}

	closeLayer()
	{
		var self = this;
		document.addEventListener("click", function(e){
			if (typeof e.target.getAttribute('class') == 'undefined' || !e.target.getAttribute('class') || (typeof e.target.getAttribute('class') != 'undefined' && e.target.getAttribute('class') && e.target.getAttribute('class').indexOf('rvw-lyr') < 0))
			{
				if(self.state.activeReview == self.props.uniqueKey) {
					let item= {'activeReview':''};
					self.setState(item);
				}
			}
		});
	}

	hideLayer() {
		if(this.state.activeReview == this.props.uniqueKey) {
			let item= {'activeReview':''};
			setTimeout(() => {this.setState(item);});
			// self.props.storeAggregateReview(item);
		}
	}

	trackEvent(action, label)
	{
		var category = 'ILP';
		if(typeof this.props.gaTrackingCategory !='undefined'){
			category = this.props.gaTrackingCategory;
		}
		Analytics.event({category : category, action : action, label : label});
	}

	generateReviewRatingLayer(){
		var aggregateRatingDisplayOrder = this.aggregateRatingDisplayOrder;
		var reviewRatingData = this.aggregateReviewWidgetData.aggregateRating;
		var isAmp = this.props.isAmp;

		var percentFactor = 100/(Object.keys(reviewRatingData).length - 1);
		var ratingBlock = [];
		for(var key in aggregateRatingDisplayOrder){
			var ratingLi = [];
			var rating = parseFloat(reviewRatingData[key]['mean']);
			var ratingBar = parseInt(rating*percentFactor);

			ratingLi.push(<span className="loadbar" key={"loadbar_"+key}><span key={"ratingbar_"+key} style={{width: ratingBar + '%'}} className="fillBar"></span></span>);
			ratingLi.push(<b className="bar_value" key={"bar_value_"+key}>{rating.toFixed(1)}</b>);
			var ele = (
				<div className="table_row" key={"table_row_"+key}>
					<div className="table_cell" key={"table_cell_"+key}>
						<p className="rating_label" key={"rating_label_"+key}>{aggregateRatingDisplayOrder[key]}</p>
					</div>
					<div className="table_cell" key={"table_"+key}>
						{ratingLi}
					</div>
				</div>
			);
			ratingBlock.push(ele);
		}
		return(
			<React.Fragment>
				{(!isAmp) ? <div className={this.state.activeReview === this.props.uniqueKey ? 'rat_col rating_popup show' : 'rat_col rating_popup'}>
						<div className="inline-rating">
							{ratingBlock}
							{
								(typeof this.reviewsCount != 'undefined' && this.reviewsCount )?
									(
										<div className="table_row">
											<div className="fill_cell">
												<a className="view_rvws" href={this.allReviewsUrl} onClick={this.trackEvent.bind(this, 'viewAllUrl' , 'click')}>{this.viewAllAnchorText}<i className="sprite-cmn arw_l"></i></a>
											</div>
										</div>
									) : null
							}
						</div>
					</div> :
					<React.Fragment>
						<amp-lightbox id="rating-toplightboxcourse" layout="nodisplay">
							<div className='lightbox'>
								<a className='cls-lightbox f25 color-f font-w6' role='button' tabIndex='0' on='tap:rating-toplightboxcourse.close'>&times;</a>
								<div className="m-layer">
									<div className='min-div colo-w'>
										<div className='pad10  rvw-fix color-w'>
											{ratingBlock}
											{
												(typeof this.reviewsCount != 'undefined' && this.reviewsCount )? (
													<div className="table_row">
														<div className="fill_cell">
															<a className="view_rvws" href={this.allReviewsUrl} >{this.viewAllAnchorText}<i className="sprite-cmn arw_l"></i></a>
														</div>
													</div>
												): null
											}

										</div>
									</div>
								</div>
							</div>
						</amp-lightbox>

					</React.Fragment>
				}
			</React.Fragment>


		)
	}


	render(){
		var isPaid = false;
		if(this.props.isPaid){
			isPaid = this.props.isPaid;
		}
		var aggregateRating = parseFloat(this.aggregateReviewWidgetData.aggregateRating.averageRating.mean);
		var aggregateRatingPercent = parseInt(aggregateRating*20);
		var showAggregateWidget = true;
		var isAmp = this.props.isAmp;
		if(isPaid && aggregateRating<3.5){
			showAggregateWidget = false;
		}

		if(typeof this.props.showPopUpLayer !='undefined' && !this.props.showPopUpLayer){
			if(!showAggregateWidget){
				return null;
			}
			return(
				<div className='clg-col single-col'>
						<span className='rating-block'>{aggregateRating.toFixed(1)}
							<i className="empty_stars starBg rvw-lyr">
								<i style= {{width: aggregateRatingPercent + '%'}} className="full_starts starBg rvw-lyr"></i>
							</i>
						</span>
					{
						((typeof this.props.reviewsCount != 'undefined' && this.props.reviewsCount>0) ) ? (
							(typeof this.props.reviewUrl !='undefined' && this.props.reviewUrl) ?
								(typeof this.props.showReviewBracket !='undefined' && this.props.showReviewBracket)?<a className="view_rvws" href={this.allReviewsUrl} onClick={this.trackEvent.bind(this,'reviewCount','click')} >({this.props.reviewsCount})</a>:
									(<a onClick={this.trackEvent.bind(this,'reviewCount','click')} className="view_rvws" href={this.props.reviewUrl}>{this.anchorText}</a>):
								<span className="view_rvws">{this.anchorText}</span>
						) : null
					}
				</div>
			)

		}


		return(
			<div className='clg-col single-col'>
				{ showAggregateWidget ?
					(
						<span className={this.state.activeReview === this.props.uniqueKey ? 'rating-block rvw-lyr revsarw' : 'rating-block rvw-lyr'} onClick={(!isAmp) ? this.markSelected.bind(this,this.props.uniqueKey): ''} onBlur={(!isAmp) ? this.hideLayer.bind(this): ''} on={(isAmp)? 'tap:rating-toplightboxcourse' : '' } role='button' tabIndex='0'>

					{aggregateRating.toFixed(1)}
							<i className="empty_stars starBg rvw-lyr">
						<i style= {{width: aggregateRatingPercent + '%'}} className="full_starts starBg rvw-lyr"></i>
					</i>
					<b className="icons bold_arw rvw-lyr"></b>
							{this.generateReviewRatingLayer()}
				</span>

					) : null

				}
				{

					((typeof this.props.showAllreviewUrl != 'undefined' && this.props.showAllreviewUrl && typeof this.props.reviewsCount != 'undefined' && this.props.reviewsCount>0) ) ?

						(typeof this.props.showReviewBracket !='undefined' && this.props.showReviewBracket) ?
							<a className="view_rvws" href={this.allReviewsUrl} onClick={this.trackEvent.bind(this,'reviewCount','click')} >({this.props.reviewsCount})</a>
							:(
								<a className="view_rvws" onClick={this.trackEvent.bind(this,'reviewCount','click')} href={this.allReviewsUrl} >{this.anchorText}<i className="sprite-cmn arw_l"></i></a>

							) : null

				}
			</div>
		);
	}
}


AggregateReviewWidget.defaultProps = {
	gaCategory: 'CLP',
	gaAction  : 'AllReviews',
	isAmp: false
};

export default AggregateReviewWidget;

AggregateReviewWidget.propTypes = {
  aggregateReviewData: PropTypes.any,
  config: PropTypes.any,
  gaAction: PropTypes.string,
  gaCategory: PropTypes.string,
  gaTrackingCategory: PropTypes.any,
  isAmp: PropTypes.bool,
  isPaid: PropTypes.any,
  reviewUrl: PropTypes.any,
  reviewsCount: PropTypes.any,
  showAllreviewUrl: PropTypes.any,
  showPopUpLayer: PropTypes.any,
  showReviewBracket: PropTypes.any,
  uniqueKey: PropTypes.any
}