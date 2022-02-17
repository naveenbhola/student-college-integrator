import PropTypes from 'prop-types'
import React from 'react';
import './../assets/reviewWidget.css';
import './../assets/courseCommon.css';
import { makeURLAsHyperlink } from './../../../../utils/urlUtility';
import Analytics from './../../../reusable/utils/AnalyticsTracking';
import OverallRatingWidget from './OverallRatingWidget';
import {addingDomainToUrl} from './../../../../utils/commonHelper';
import DownloadEBrochure from './../../../common/components/DownloadEBrochure';
import {ILPConstants, ULPConstants} from './../../categoryList/config/categoryConfig';


class ReviewWidget extends React.Component
{
    constructor(props)
    {
        super(props);
        this.state = {
            activeReview : '',
        }
     if(this.props.page == 'institute'){
        this.config = ILPConstants();
        }else{
         this.config = ULPConstants();
      }
    }



    markSelected(activeReview)
    {
        this.trackEvent('Review_Section','Click_ review_drop_down');
        if(this.state.activeReview == activeReview){
            this.setState({'activeReview':''});
        }else{
            this.setState({'activeReview':activeReview});
        }

    }
    componentDidMount()
    {
        this.closeLayer();
    }

    closeLayer()
    {
        var self = this;
        document.addEventListener("click", function(e){
            if(self.state.activeReview){
                if (typeof e.target.getAttribute('class') == 'undefined' || !e.target.getAttribute('class') || (typeof e.target.getAttribute('class') != 'undefined' && e.target.getAttribute('class') && e.target.getAttribute('class').indexOf('rvw-lyr') < 0))
                {
                    self.setState({activeReview : ''});
                }   
            }
        });
    }

    isActive(id)
    {
        return (id === this.state.activeReview);
    }

    generateReviewWidgetHtml(){

        let verifiedReviewData = this.props.reviewWidgetData.reviewData.reviewsData;
	let unverifiedReviewData = (this.props.reviewWidgetData.reviewData.unverifiedReviewsData!=null && this.props.reviewWidgetData.reviewData.unverifiedReviewsData.length>0)?this.props.reviewWidgetData.reviewData.unverifiedReviewsData:[];
        let verifiedReviewsCount = this.props.reviewWidgetData.reviewData.totalReviews;
	let reviewData = '';
        if(unverifiedReviewData.length>0){
            reviewData = verifiedReviewData.concat(unverifiedReviewData);    
        }else{
            reviewData = verifiedReviewData;
        }
        var reviewUrl = addingDomainToUrl(this.props.reviewWidgetData.reviewData.allReviewUrl,this.props.config.SHIKSHA_HOME);
        var courseInfo = this.props.reviewWidgetData.courseInfo;
        var reviewRating =  this.props.reviewWidgetData.reviewData.reviewRating;
        var reviewHtml = [];
        let unverifiedReviewCounter = 0;
        for(var index in reviewData) {
            let verifiedReviewFlag = false;
            
            if(reviewData[index]['status']=='published'){
                verifiedReviewFlag = true;
            }else{
                unverifiedReviewCounter++;
            }
            let placementKey =this.props.reviewWidgetData.reviewData.reviewRating[reviewData[index]['reviewId']]['ratingsMap']; 
            var lastChild = '';
            if(index == (reviewData).length-1){
                lastChild = 'last-child';
            }

            var userDetails = [];
            userDetails['userName'] = (reviewData[index]['anonymousFlag']=='YES')?'Anonymous':reviewData[index]['reviewerDetails']['userName'];
            if(typeof courseInfo!= 'undefined' && reviewData[index]['yearOfGraduation'] && courseInfo[reviewData[index]['courseId']]['courseName']){
                userDetails['batchInfo'] = " - Batch of "+reviewData[index]['yearOfGraduation'];
            }
            var ratingBar = reviewData[index]['averageRating']*100/(Object.keys(reviewRating[reviewData[index]['reviewId']]['ratingsMap']).length);
            if(this.props.reviewWidgetData.pageType == 'placement'){
                ratingBar = placementKey['Placements']*20;
            }
            var  postedData = '';
            if(reviewData[index]['postedDate']){
                postedData = reviewData[index]['postedDate'].split(' ')[0];
            }
            var showDescLen = 450;
            var minCharPerSection = 40;
            var reviewSegments = [];
            var totalSegmentslen = 0;
            var elipses = [];
            var readMoreUrl = reviewUrl;
            if(this.props.appendCourseIdToUrl){
                readMoreUrl = reviewUrl+'?course='+reviewData[index]['courseId'];
            }

            if(typeof reviewData[index]['placementDescription'] != 'undefined' && reviewData[index]['placementDescription']!=''){
                reviewSegments['Placements'] = reviewData[index]['placementDescription'];
                totalSegmentslen = parseInt(totalSegmentslen) + parseInt(reviewSegments['Placements'].length);
            }
            if(reviewData[index]['infraDescription'] && reviewData[index]['infraDescription']!=''){
                reviewSegments['Infrastructure'] = reviewData[index]['infraDescription'];
                totalSegmentslen = parseInt(totalSegmentslen )+ parseInt(reviewSegments['Infrastructure'].length);
            }
            if(reviewData[index]['facultyDescription'] && reviewData[index]['facultyDescription']!=''){
                reviewSegments['faculty'] = reviewData[index]['facultyDescription'];
                totalSegmentslen = parseInt(totalSegmentslen) + parseInt(reviewSegments['faculty'].length);
            }
            if(reviewData[index]['reviewDescription'] && (typeof reviewSegments['Placements'] || reviewSegments['Infrastructure'] || reviewSegments['faculty'])){
                reviewSegments['Other'] = reviewData[index]['reviewDescription'];
                totalSegmentslen = parseInt(totalSegmentslen) + parseInt(reviewSegments['Other'].length);
            }else if(reviewData[index]['reviewDescription']){
                reviewSegments['Description'] = reviewData[index]['reviewDescription'];
                totalSegmentslen = parseInt(totalSegmentslen) + parseInt(reviewSegments['Description'].length);
            }
            var finalStr = [];
            var readmoredots = true;
            for(var key in reviewSegments){

                var str = [] ;
                if(parseInt(showDescLen)>0 ) {
                    if(key != 'Description'  && parseInt(reviewSegments[key].length)>0)
                    {
                        str.push(<strong key={"strng_"+key+index}>{key}: </strong>);
                    }
                    str.push(<span key={'str_'+key+index} dangerouslySetInnerHTML={{ __html : makeURLAsHyperlink(reviewSegments[key].substr(0,showDescLen))}}></span>);
                    var remainingLen =  parseInt(totalSegmentslen)-parseInt(showDescLen);
                    showDescLen = parseInt(showDescLen) - parseInt(reviewSegments[key].length);
                    if(showDescLen>0 && showDescLen<minCharPerSection && remainingLen>minCharPerSection){
                        showDescLen = minCharPerSection;
                    }
                }
                if(parseInt(showDescLen)<0 && readmoredots){
                    readmoredots = false;
                    finalStr.push(<p key={'finalString'+key+index}>{str}...</p>);
                }
                else{
                    finalStr.push(<p key={'finalString'+key+index}>{str}</p>);
                }
            }
            if(remainingLen>0){
                let currentReviewUrl =  readMoreUrl;
                if(this.props.deviceType =='desktop'){
                    currentReviewUrl = readMoreUrl+'#'+reviewData[index]['reviewId'];
                }else{
                    currentReviewUrl =  readMoreUrl+'#id='+reviewData[index]['reviewId']+'&seqId='+(parseInt(index)+1);
                }
                elipses.push(<a className="rdMr-link" href={currentReviewUrl} onClick={() => this.trackEvent('review_view_more')} key={"review_a"+index}>Read More</a>);
            }

            reviewHtml.push(
                <div className={"group-card gap pad-off "+lastChild} key={'review_'+index}>
                    {(unverifiedReviewCounter==1 && verifiedReviewsCount!=0)?
                        <strong>The reviewer's details of the following have not been verified yet.</strong>:''}
                    <div className="rvwv1Heading" key={'rvwv1Heading'+index}>
                        <div key={'rvw_'+index}>
                            <div className="new_rating" key={'new_rating_'+index}>
                            <span
                                className={this.isActive('rating-col_'+reviewData[index]['reviewId']) ? 'rvw-lyr rating-block revsarw' : 'rvw-lyr rating-block'}
                                onClick={this.markSelected.bind(this,'rating-col_'+reviewData[index]['reviewId'])} key={'rating-block_'+index} id={'rating-block_'+index}>

                              {this.props.reviewWidgetData.pageType == 'placement'?
                              placementKey['Placements'].toFixed(1):reviewData[index]['averageRating'].toFixed(1)}
                                <i className="empty_stars starBg rvw-lyr" key={'empty_stars_'+index}>
                                <i style={{width: ratingBar + '%'}} className="full_starts starBg rvw-lyr" key={'full_starts'+index}></i>
                              </i>
                              { this.props.reviewWidgetData.pageType != 'placement'?  <b className="icons bold_arw rvw-lyr" key={'rvw-lyr'+index}></b>:''}
                              { this.props.reviewWidgetData.pageType != 'placement'?  this.generateReviewRatingLayer1(reviewData[index]['reviewId']) :''}
                              

                            </span>
                                <span key={'reviewTitle'+index}>
                              {reviewData[index]['reviewTitle']}
                            </span>
                            </div>
                            <p  key={'byUser_'+index} className="byUser"> 
                            {verifiedReviewFlag?
                                <span className="verified-tag">
                                    <i className="icon-verified-tag"></i>Verified
                                </span>
                            :''}
                                <span>{userDetails['userName']}</span>, {postedData} |
                                {typeof courseInfo!='undefined'?
                                    ' '+courseInfo[reviewData[index]['courseId']]['courseName']+courseInfo[reviewData[index]['courseId']]['courseNameSuffix']+userDetails['batchInfo']
                                    : null
                                }
                            </p>
                        </div>
                    </div>
                    <div className="rvwv1-h" key={"review_p"+index}>
                        <div className="tabcontentv1" key={"tabcontentv1"+index}>
                            <div className="tabv_1" key={"tabv_1"+index}>
                                {finalStr}
                                {elipses}
                            </div>
                        </div>
                    </div>
                </div>
            );
        }

        return reviewHtml;

    }

    generateReviewRatingLayer1(reviewId){
        var ratingDisplayOrder = this.props.reviewWidgetData.ratingDisplayOrder;
        var reviewRatingData = this.props.reviewWidgetData.reviewData.reviewRating[reviewId]['ratingsMap'];
        var percentFactor = 100/(Object.keys(reviewRatingData).length);
        var ratingBlock = [];
        if(typeof ratingDisplayOrder =='undefined'){
            return (
                null
            );
        }
        for(var key in ratingDisplayOrder){
            var reviewhead = ratingDisplayOrder[key];
            var ratingLi = [];
            var rating = parseFloat(reviewRatingData[reviewhead]);
            var ratingBar = parseInt(rating*percentFactor);


            ratingLi.push(
                <span className="loadbar" key={"loadbar_"+key}>
                <span key={"ratingbar_"+key} style={{width: ratingBar + '%'}} className="fillBar"></span>
              </span>
            );

            ratingLi.push(<b className="bar_value" key={"bar_value_"+key}>{rating}</b>);
            var ele = (
                <div className="table_row" key={"table_row_"+key}>
                    <div className="table_cell" key={"table_cell_"+key}>
                        <p className="rating_label" key={"rating_label_"+key}>{reviewhead}</p>
                    </div>
                    <div className="table_cell" key={"table_"+key}>
                        {ratingLi}
                    </div>
                </div>
            );
            ratingBlock.push(ele);
        }
        return(
            <div className={this.isActive('rating-col_'+reviewId) ? 'rating_popup show' : 'rating_popup'} key = {'rating_popup'+reviewId}>
                <div className="inline-rating" key = {'inline-rating'+reviewId}>
                    {ratingBlock}
                </div>
            </div>
        )
    }


    trackEvent(eventAction,label='Click')
    {
        if(typeof this.props.gaTrackingCategory !='undefined' && this.props.gaTrackingCategory ){
            Analytics.event({category : this.props.gaTrackingCategory, action : eventAction, label : label});
        }
    }

    render(){
        var totalReviews = this.props.reviewWidgetData.reviewData.allReviewsCount;
        var verifiedtotalReviews = this.props.reviewWidgetData.reviewData.totalReviews;
        let reviewShown = '';
        if(this.props.reviewWidgetData.reviewData.unverifiedReviewsData!=null && this.props.reviewWidgetData.reviewData.unverifiedReviewsData.length>0){
            reviewShown = Object.keys(this.props.reviewWidgetData.reviewData.reviewsData).length + Object.keys(this.props.reviewWidgetData.reviewData.unverifiedReviewsData).length;
        }else{
            reviewShown = Object.keys(this.props.reviewWidgetData.reviewData.reviewsData).length;
        }	    
        var listingType = this.props.reviewWidgetData.reviewData.listingType;
        var showAggregateWidget = true;
        let reviewsPdfUrl = this.props.reviewWidgetData.pdfUrl;
        var reviewTypes = '';
        if(this.props.isPaid && typeof this.props.aggregateReviewWidgetData!='undefined' && this.props.aggregateReviewWidgetData && this.props.aggregateReviewWidgetData.aggregateReviewData && this.props.aggregateReviewWidgetData.aggregateReviewData.aggregateRating.averageRating.mean<3.5){
            showAggregateWidget = false;
        }
        var reviewHeading ='Course';
        if(listingType =='university' || listingType=='institute'){
            reviewHeading = 'College';
        }
        if(this.props.reviewWidgetData.pageType =='placement'){
            reviewHeading ='Placement';
            reviewTypes = 'placement';
        }
        var totalReviewHeading = totalReviews+' reviews';

        if(totalReviews == 1){
            totalReviewHeading = totalReviews+' review';
        }

        return(
            <section className='aluminiReviewBnr listingTuple' key="clp_review_widget" id="review">
                <div className='_container'>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            
                    <h2 className='tbSec2'>{reviewHeading+" Reviews "}
                    {this.props.reviewWidgetData.pageType !='placement' && <span className='head-L5 pad-left4'>(Showing {reviewShown} of {totalReviewHeading} )</span>}
                    </h2>
	{typeof this.props.aggregateReviewWidgetData!='undefined' && this.props.aggregateReviewWidgetData && this.props.aggregateReviewWidgetData.aggregateReviewData != null && showAggregateWidget  && <OverallRatingWidget config ={this.props.config} isPaid ={this.props.isPaid}
reviewsCount={totalReviews} verifiedtotalReviews={verifiedtotalReviews}  reviewUrl = {this.props.reviewWidgetData.reviewData.allReviewUrl} aggregateReviewData = {this.props.aggregateReviewWidgetData} reviewTypes={reviewTypes} />}
                    <div className='_subcontainer'>
                        {this.generateReviewWidgetHtml()}
                        {totalReviews>4 && <div className='button-container rvws--wrap'>
                           {reviewsPdfUrl && <DownloadEBrochure  actionType="Download_Top_Reviews" className="button--purple" buttonText="Download Top Reviews" ctaName='downloadTopReviews' heading='Downloading Top Reviews List.'  uniqueId={'downloadTopReviews_'+this.props.listingId} listingId={this.props.listingId} listingName={this.props.instituteName}  recoEbTrackid={this.config.DownloadTopReviewsCTA} clickHandler={this.trackEvent.bind(this,'NEWCTA','click_download_top_reviews_cta')} isCallReco={false} page={this.props.page} pdfUrl={reviewsPdfUrl} />}
                            <a  href={addingDomainToUrl(this.props.reviewWidgetData.reviewData.allReviewUrl,this.props.config.SHIKSHA_HOME)} onClick={() => this.trackEvent('AllReviews')} className="button button--secondary rippleefect arrow"> View All Reviews </a>
                        </div>
                        }	                </div>
                </div>
            </section>
        )


    }

}

export default ReviewWidget;

ReviewWidget.propTypes = {
  aggregateReviewWidgetData: PropTypes.any,
  appendCourseIdToUrl: PropTypes.any,
  config: PropTypes.any,
  deviceType: PropTypes.any,
  gaTrackingCategory: PropTypes.any,
  isPaid: PropTypes.any,
  reviewWidgetData: PropTypes.any
}
