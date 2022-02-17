import React from 'react';
import { makeURLAsHyperlink } from '../../../../../application/utils/urlUtility';
import OverallRatingWidgetAmp from './OverallRatingWidgetAmp';



class ReviewWidgetAmp extends React.Component{
  constructor(props){
    super(props);
    this.state = {
			activeReview : '',
	    }
  }
  generateReviewWidgetHtml(){

      var reviewData = this.props.reviewWidgetData.reviewData.reviewsData;
      var reviewUrl = this.props.reviewWidgetData.reviewData.allReviewUrl;
      var courseInfo = this.props.reviewWidgetData.courseInfo;
      var reviewRating =  this.props.reviewWidgetData.reviewData.reviewRating;
      var reviewHtml = [];


      for(var index in reviewData) {

        var lastChild = '';
        if(index == (reviewData).length-1){
            lastChild = 'last-child';
        }

        var userDetails = [];
        userDetails['userName'] = (reviewData[index]['anonymousFlag']=='YES')?'Anonymous':reviewData[index]['reviewerDetails']['userName'];
        if(reviewData[index]['yearOfGraduation'] && courseInfo[reviewData[index]['courseId']]['courseName']){
          userDetails['batchInfo'] = " - Batch of "+reviewData[index]['yearOfGraduation'];
        }
        var ratingBar = reviewData[index]['averageRating']*100/(Object.keys(reviewRating[reviewData[index]['reviewId']]['ratingsMap']).length);
        var  postedData = '';
        if(reviewData[index]['postedDate']){
          postedData = reviewData[index]['postedDate'].split(' ')[0];
        }
        var showDescLen = 450;
        var minCharPerSection = 40;
        var userName = (reviewData[index]['anonymousFlag']=='YES')?'Anonymous':reviewData[index]['reviewerDetails']['userName'];
        var reviewSegments = [];
        var totalSegmentslen = 0;
        var elipses = [];
        if(typeof reviewData[index]['placementDescription'] != 'undefined' && reviewData[index]['placementDescription']!=''){
            reviewSegments['Placements'] = reviewData[index]['placementDescription'];
            totalSegmentslen = parseInt(totalSegmentslen) + parseInt(reviewSegments['Placements'].length);
        }
        if(typeof reviewData[index]['infraDescription'] != 'undefined' && reviewData[index]['infraDescription']!=''){
           reviewSegments['Infrastructure'] = reviewData[index]['infraDescription'];
           totalSegmentslen = parseInt(totalSegmentslen )+ parseInt(reviewSegments['Infrastructure'].length);
        }
        if(typeof reviewData[index]['facultyDescription'] != 'undefined' && reviewData[index]['facultyDescription']!=''){
           reviewSegments['faculty'] = reviewData[index]['facultyDescription'];
           totalSegmentslen = parseInt(totalSegmentslen) + parseInt(reviewSegments['faculty'].length);
        }
        if(typeof reviewData[index]['reviewDescription'] != 'undefined' && (typeof reviewSegments['Placements'] != 'undefined' || typeof reviewSegments['Infrastructure'] != 'undefined' || typeof reviewSegments['faculty'] != 'undefined')){
            reviewSegments['Other'] = reviewData[index]['reviewDescription'];
           totalSegmentslen = parseInt(totalSegmentslen) + parseInt(reviewSegments['Other'].length);
        }else{
            reviewSegments['Description'] = reviewData[index]['reviewDescription'];
           totalSegmentslen = parseInt(totalSegmentslen) + parseInt(reviewSegments['Description'].length);
        }
        var finalStr = [];
        for(var key in reviewSegments){
        var str = [] ;
        if(parseInt(showDescLen)>0 ) {
          if(key != 'Description'  && parseInt(reviewSegments[key].length)>0)
          {
            str.push(<strong key="description">{key}: </strong>);
          }
            str.push(<span key="descrptn" dangerouslySetInnerHTML={{ __html : makeURLAsHyperlink(reviewSegments[key].substr(0,showDescLen))}}></span>);
          var remainingLen =  parseInt(totalSegmentslen)-parseInt(showDescLen);
              showDescLen = parseInt(showDescLen) - parseInt(reviewSegments[key].length);
              if(showDescLen>0 && showDescLen<minCharPerSection && remainingLen>minCharPerSection){
                 showDescLen = minCharPerSection;
              }
          }

            finalStr.push(<p key="final_str_key">{str}</p>);
      }
       if(remainingLen>0){
            elipses.push('...');
            elipses.push(<a key="elipses" className="rdMr-link" href={reviewUrl+'#id='+reviewData[index]['reviewId']+'&seqId='+(parseInt(index)+1)} key={"review_a"+index}>more</a>);
        }

        reviewHtml.push(
                    <div key="reviewHtml" className={"group-card gap pad-off "+lastChild} key={'review_'+index}>
                      <div className="rvwv1Heading" key={'rvwv1Heading'+index}>
                        <div key={'rvw_'+index}>
                          <div className="new_rating" key={'new_rating_'+index}>
                            <span
                              className={this.isActive('rating-col_'+reviewData[index]['reviewId']) ? 'rvw-lyr rating-block revsarw' : 'rvw-lyr rating-block'}
                                on={`tap:view-review_${reviewData[index]['reviewId']}`} role="button" tabIndex="0">

                              {reviewData[index]['averageRating'].toFixed(1)}
                              <i className="empty_stars starBg rvw-lyr">
                                <i style={{width: ratingBar + '%'}} className="full_starts starBg rvw-lyr"></i>
                              </i>
                              <b className="icons bold_arw rvw-lyr"></b>
                              {this.generateReviewRatingLayer1(reviewData[index]['reviewId'])}
                            </span>
                            <span>
                              {reviewData[index]['reviewTitle']}
                            </span>
                          </div>

                          <p className="byUser">by <span>{userDetails['userName']}</span>, {postedData} |
                          {courseInfo[reviewData[index]['courseId']]['courseName']+' '+courseInfo[reviewData[index]['courseId']]['courseNameSuffix']+userDetails['batchInfo']}
                          </p>
                        </div>
                      </div>
                      <div className="rvwv1-h" key={"review_p"+index}>
                        <div className="tabcontentv1">
                          <div className="tabv_1">
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
  isActive(id)
	{
	      return (id === this.state.activeReview);
	}

  generateReviewRatingLayer1(reviewId){
     var ratingDisplayOrder = this.props.reviewWidgetData.ratingDisplayOrder;
     var reviewRatingData = this.props.reviewWidgetData.reviewData.reviewRating[reviewId]['ratingsMap'];
     var percentFactor = 100/(Object.keys(reviewRatingData).length);
     var ratingBlock = [];
     for(var key in ratingDisplayOrder){
       var reviewhead = ratingDisplayOrder[key];
       var ratingLi = [];
         var rating = parseFloat(reviewRatingData[reviewhead]);
         var ratingBar = parseInt(rating*percentFactor);

            ratingLi.push(<span key="ratingSpan" className="loadbar"><span style={{width: ratingBar + '%'}} className="fillBar"></span></span>);
            ratingLi.push(<b key="ratingSpan" className="bar_value" key={"bar_value_"+key}>{rating}</b>);
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
       <amp-lightbox  id={`view-review_${reviewId}`} layout="nodisplay">
         <div className="lightbox">
             <a className="cls-lightbox f25 color-f font-w6" on={`tap:view-review_${reviewId}.close`} role="button" tabIndex="0">&times;</a>
            <div className="m-layer">
              <div className="min-div colo-w">
                <div className="pad10  rvw-fix color-w">{ratingBlock}</div>
              </div>
            </div>
         </div>
        </amp-lightbox>
     )
   }
  render(){
    var reviewShown = Object.keys(this.props.reviewWidgetData.reviewData.reviewsData).length;
    var totalReviews = this.props.reviewWidgetData.reviewData.totalReviews;
    var showAggregateWidget = true;
    if(this.props.isPaid && this.props.aggregateReviewWidgetData && this.props.aggregateReviewWidgetData.aggregateReviewData && this.props.aggregateReviewWidgetData.aggregateReviewData.aggregateRating.averageRating.mean<3.5){
       showAggregateWidget = false;
    }
    var listing_type = 'Course';
    var reviewHeading ='Course';
    if(listing_type =='university' || listing_type=='institute'){
      reviewHeading = 'College';
    }
    var totalReviewHeading = totalReviews+' reviews';
    if(totalReviews == 1){
      totalReviewHeading = totalReviews+' review';
    }
    return(
        <section>
          <div className="data-card m-5btm">
            <h2 className='color-3 f16 heading-gap font-w6'>{reviewHeading+" Reviews"}<span className='f12 font-w4 color-3'>  (Showing {reviewShown} of {totalReviewHeading} )</span></h2>
            {this.props.aggregateReviewWidgetData && this.props.aggregateReviewWidgetData.aggregateReviewData != null && showAggregateWidget  && <OverallRatingWidgetAmp isPaid ={this.props.isPaid} reviewsCount={this.props.reviewWidgetData.reviewData.totalReviews} reviewUrl = {this.props.reviewWidgetData.reviewData.allReviewUrl} aggregateReviewData = {this.props.aggregateReviewWidgetData} />}
          </div>
          <div className="card-cmn color-w">
           {this.generateReviewWidgetHtml()}
           {totalReviews>4 &&
               <a className='btn-mob-ter' href={this.props.reviewWidgetData.reviewData.allReviewUrl} >View All {totalReviews} Reviews</a>
           }
          </div>
       </section>
    )
  }
}

export default ReviewWidgetAmp;
