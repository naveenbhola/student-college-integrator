import React, {Component} from 'react';
import AggregateReview from '../../../../../application/modules/listing/course/components/AggregateReviewWidget';
import AmpLightBox  from '../../../../../application/modules/common/components/AmpLightbox';


class CollegeListAmp extends React.Component{
  constructor(props){
    super(props);
  }

  tooltipLayerData(){
    return(
      <div className="pad10">
         <p className="color-3 l-18 f12">   Colleges can be of 2 types – Constituent colleges and Affiliated colleges. Constituent colleges are colleges which are maintained by the university itself. Affiliated colleges are educational institutions that operate independently, but also have a formal collaborative agreement with a university for the purpose of awarding the degree with some level of control or influence over its academic policies, standards or programs.</p>
      </div>
    )
  }

  renderNameInfo(instituteData,instituteId)
  {
    var SHIKSHA_HOME = this.props.config.SHIKSHA_HOME;
    if(typeof instituteData == 'undefined' || !instituteData)
      return null;
      return (
          <p>
            <a href={SHIKSHA_HOME+instituteData.instituteUrl} className="f14 color-6 ga-analytic" data-vars-event-name="COLLEGE_ACAMEMIC">
                   {instituteData.shortName != '' &&  instituteData.shortName != null ? instituteData.shortName : instituteData.name}
            </a>
          </p>
      )
  }

  renderCollegeImage(collegeimageData, instituteData,instituteId)
  {
    if (instituteData.mediaUrl != null ) {
    	return(
				<div className="flexi_img">
									<img src={instituteData.mediaUrl} alt={instituteData.name}/>
        </div>
			)
    }
		return(
			<div className="flexi_img">
								<img src={collegeimageData.defaultImageUrl} alt ="default image"/>
			</div>
		)

  }

  renderReviewInfo(aggregateReviewData,instituteId,reviewUrl)
  {
		var SHIKSHA_HOME = this.props.config.SHIKSHA_HOME;
    if(typeof aggregateReviewData['aggregateReviewData'] != 'undefined' && typeof aggregateReviewData['aggregateReviewData'][instituteId] != 'undefined' && aggregateReviewData['aggregateReviewData'][instituteId])
    {

        return (<div className="ratingv1">
                  {<AggregateReview isPaid={true} showPopUpLayer = {false} uniqueKey= {'institute_'+instituteId} showAllreviewUrl={true} reviewsCount={aggregateReviewData['aggregateReviewData'][instituteId]['totalCount']}  aggregateReviewData = {{'aggregateReviewData' : aggregateReviewData.aggregateReviewData[instituteId],'aggregateRatingDisplayOrder' : aggregateReviewData.aggregateRatingDisplayOrder}} reviewUrl={reviewUrl} config={this.props.config}/>}
                </div>

              )
    }
    return null;
  }
  renderCollegeInfo(collegelistWidget)
  {
      var self = this;
      let maxdisplay = 3;
      let numberOflist = 0;
      if(typeof collegelistWidget['collegeWidgetData'] == 'undefined' || (typeof collegelistWidget['collegeWidgetData'] != 'undefined' && (collegelistWidget['collegeWidgetData'] == null || (collegelistWidget['collegeWidgetData']['topInstituteOrder'] && collegelistWidget['collegeWidgetData']['topInstituteOrder'].length == 0))))
      {
            return null;
      }
      let htmlData = [];
      for(var index in collegelistWidget['collegeWidgetData']['topInstituteOrder'])
      {

        let instituteId = collegelistWidget['collegeWidgetData']['topInstituteOrder'][index];

				//var SHIKSHA_HOME = this.props.config.SHIKSHA_HOME;
          if(typeof collegelistWidget['collegeWidgetData']['topInstituteData'][instituteId] != 'undefined')
          {
              htmlData.push(<li key={"collegelist_"+index}>
						  <React.Fragment>
                        {self.renderNameInfo(collegelistWidget['collegeWidgetData']['topInstituteData'][instituteId],instituteId)}
                          {typeof collegelistWidget['agrregateReviewsData'] != 'undefined'  && collegelistWidget['agrregateReviewsData'] && collegelistWidget['agrregateReviewsData']['aggregateReviewData'] && self.renderReviewInfo(collegelistWidget['agrregateReviewsData'],instituteId,collegelistWidget['collegeWidgetData']['topInstituteData'][instituteId]['reviewUrl'])}
                </React.Fragment>

            </li>);
          }

      }
      return htmlData;
  }

  renderCollegeTitle(){
    const {collegeName} = this.props;
     return (
       <span>{collegeName}</span>
     );
  }

  render(){
    const {collegelistWidget} = this.props;
    return(
    <React.Fragment>
     {(collegelistWidget !=null) ?
      <section>
         <div className="data-card m-btm">
           <h2 className="color-3 f16 heading-gap font-w6 inline"> Colleges/Departments </h2>
           <a on="tap:info-clg-departments" role="button" tabIndex="0" data-vars-event-name="'.$GA_TAP_ON_HELP_TEXT.'" className="pos-rl ga-analytic"><i className="cmn-sprite clg-info i-block v-mdl"></i></a>
           <AmpLightBox data={this.tooltipLayerData()} id="info-clg-departments"/>
           <div className="card-cmn color-w">
            <h3 className="color-3 f14 font-w6 sp-l">{this.props.collegelistWidget.collegeWidgetData.constituentCollegeText }  {this.renderCollegeTitle()}</h3>
            <ul className="cls-ul">
              {this.renderCollegeInfo(collegelistWidget)}
            </ul>
            <a className="btn btn-secondary color-w color-b f14 font-w6 m-15top ga-analytic" on="tap:mostViewedCollegeList" data-vars-event-name="VIEW_ALL_COLLEGE_ACADEMIC">View All</a>
          </div>
         </div>
      </section> : ''}
     </React.Fragment>
    )
  }
}


export default CollegeListAmp;
