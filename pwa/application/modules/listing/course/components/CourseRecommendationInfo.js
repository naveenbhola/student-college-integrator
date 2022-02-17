import PropTypes from 'prop-types'
import React, { Component } from 'react';
import Shortlist from './../../../common/components/Shortlist';
import Analytics from './../../../reusable/utils/AnalyticsTracking';
import {Link} from 'react-router-dom';
import {AdmissionPageConstants,AdmissionPageDesktopConstants} from './../../categoryList/config/categoryConfig';

class CourseRecommendationInfo extends Component
{
	constructor(props)
	{
		super(props);
		if(this.props.fromWhere && this.props.fromWhere == 'admissionPage'){
			this.config = this.props.isDesktop ?AdmissionPageDesktopConstants(): AdmissionPageConstants();
		}
	}

	trackEvent(actionLabel,label='click')
	{
		Analytics.event({category : this.props.gaCategory, action : actionLabel, label : label});
	}

	render(){
		return (
			<React.Fragment>
				<section >
					<div className="_container">
						<div className= "_subcontainer text-center">
							<div className="recomended-flex">
								<p className="basic_font">Interested in this Course ?</p>

								<div className="widget-flex">
									<div className="flex-btnwraper">
										{this.props.isDesktop?

											<a href="javascript:void(0);" className={'ctp-btn ctpComp-btn rippleefect tupleShortlistButton shrt'+this.props.listingId} onClick={this.addToShortlist.bind(this,{'courseId':this.props.listingId})} id={'shrt'+this.props.listingId} instid={this.props.instituteId} product="Category" track="on" courseid={this.props.listingId}>
												<span className="tup-view-details">Shortlist</span></a>

											:<Shortlist className="pwa-shrtlst-ico button button--secondary rippleefect"
														actionType="NM_course_shortlist"
														listingId={this.props.listingId}
														trackid={this.config.intrestingCourseTrackingId}
														recoEbTrackid={this.config.downloadBrochureTrackingIdRecoLayer}
														recoShrtTrackid={this.config.shortlistTrackingIdRecoLayer}
														recoPageType="EBrochure_RECO_Layer"
														recoActionType="EBrochure_RECO_Layer"
														pageType="NM_CourseListing"
														sessionId=""
														visitorSessionid=""
														isCallReco={this.props.isCallReco}
														clickHandler={this.trackEvent.bind(this,'Course_Specific_Shortlist_Course')}
														isButton={true}
														page = 'coursePage'/>

										}
									</div>
									<div className="flex-btnwraper">
										{this.props.listingUrl && this.props.isDesktop ?
											<a href={this.props.listingUrl} target='_blank' rel="noopener noreferrer" onClick={this.trackEvent.bind(this,'Course_Specific_View_Details')} className='button button--orange ripple dark' >View Details</a>
											:<Link to={this.props.listingUrl} onClick={this.trackEvent.bind(this,'Course_Specific_View_Details')}  className='button button--orange ripple dark'>View Details</Link>}

									</div>
								</div>
							</div>
						</div>
					</div>
				</section>

			</React.Fragment>
		)
	}

	addToShortlist =(params)=>{
		if(typeof(window) !='undefined' && typeof(addShortlistFromSearch) !='undefined'){
			this.trackEvent('Shortlist','Response');
			let pageType = this.props.pageType;
			window.addShortlistFromSearch(params.courseId, this.config.intrestingCourseTrackingId, pageType);
		}
	}
}
export default CourseRecommendationInfo;

CourseRecommendationInfo.propTypes = {
	fromWhere: PropTypes.any,
	gaCategory: PropTypes.any,
	instituteId: PropTypes.any,
	isCallReco: PropTypes.any,
	isDesktop: PropTypes.any,
	listingId: PropTypes.any,
	listingUrl: PropTypes.any,
	pageType: PropTypes.any
}