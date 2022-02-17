import React from 'react';
import { add_query_params } from '../../../../../application/utils/urlUtility';

class OverallRatingWidgetAmp extends React.Component{
  constructor(props){
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
      this.allReviewsUrl = this.props.reviewUrl;
  }
  intervalWidget(){
    var intervalsDisplayOrder = this.intervalsDisplayOrder;
    var ratingBlock = [];
    var ratingFilter = '';
    var returnElemet = [];
      for(var key in intervalsDisplayOrder){
        var percentage = Math.round(this.aggregateReviewWidgetData['intervalRatingCount'][key]/this.aggregateReviewWidgetData['totalCount']*100,0);
        var disableClass = '';
        if(percentage == 0){
          disableClass = 'disablefilter';
        }
        if(this.aggregateReviewWidgetData['intervalRatingCount'][key] > 0) {
          var ratingFilter = key.slice(0,1);
          var url = add_query_params(this.props.reviewUrl,'rating='+ratingFilter);
        }
        var ele = (
          <div className={'starBar '+disableClass} key={"starbar_"+key}>
                    <div className="starC" key={'starC_'+key}><a href={url} key={'starlink_'+key}> {intervalsDisplayOrder[key]} star</a></div>
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
						var ele = (
	        	               <div className="starBar" key = {'starBar_'+key}>
	        	               	<div className="cRating" key = {'cRating_'+key}> {this.aggregateReviewWidgetData['aggregateRating'][key]['mean'].toFixed(1)}</div>
	        	               	<div className="componetText" key = {'componetText_'+key} > {this.aggregateRatingDisplayOrder[key]}</div>
	        	               </div>
						);
						ratingparamList.push(ele);
					}
				}
				return(
						<div className="rvwRight">
				    		<div className="align-cntr">
				        		<h2 className="ratingAll rtng-cmpt">Component Ratings <span>(Out of 5)</span></h2>
				        		{ratingparamList};
				    		</div>
						</div>
				)
			}
			else{
				return null;
			}

	}

  render(){
    var isPaid = parseInt(this.props.isPaid);
		var aggregateRating = parseFloat(this.aggregateReviewWidgetData['aggregateRating']['averageRating']['mean']);
		var aggregateRatingPercent = aggregateRating*20;
		var showAggregateWidget = true;
		if(typeof isPaid !='undefined' && isPaid && aggregateRating<3.5){
   			showAggregateWidget = false;
		}
		if(this.reviewsCount == 1){
			this.anchorText = this.reviewsCount+' review';
		}
    return(

        <div className="card-cmn color-w m-15btm">
        <div className="rvw-h">
          <div className="rvwBlock">
              <div className="rvwLeft">
                <h2 className="ratingAll">Overall Rating <span>(Out of 5)</span></h2>
                <div className="rvwScore">
                    <h3>{aggregateRating.toFixed(1)}</h3>
                    <div className="infrontRvws">
                        <i className="empty_stars">
                        <i 	style= {{width: aggregateRatingPercent + '%'}} className="full_starts"></i>
                        </i>
                        <div className="refrnceTxt">
                            <span> Based on {this.anchorText}</span>
                        </div>
                    </div>
                </div>
                {this.intervalWidget()}
              </div>
                  {this.aggregateDifferentParamWidget()}

          </div>
          <div className="allRvws">
              <div className="rvwImg"></div>
              <div className="getAllrvws"> All <span className="new-title">{this.anchorText}</span> have been published only after ensuring that the reviewers are <span className="new-title">bona fide students </span> of this college.</div>
          </div>
        </div>
        </div>

    )
  }
}

export default OverallRatingWidgetAmp;
