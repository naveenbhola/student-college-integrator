import PropTypes from 'prop-types'
import React from 'react';
import AggregateReview from './../../course/components/AggregateReviewWidget';
import './../../course/assets/sticky.css';
import Analytics from './../../../reusable/utils/AnalyticsTracking';
import {addingDomainToUrl} from './../../../../utils/commonHelper';
import ChangeBranch from './../../course/components/ChangeBranchLinkComponent';
import {ILPConstants, ULPConstants,allCoursePageDesktopConstants,AdmissionPageDesktopConstants,PlacmentPageDesktopConstants,CutoffPageDesktopConstants} from './../../categoryList/config/categoryConfig';

class TopWidgetSticky extends React.Component{

    constructor(props) {
        super(props);

        this.locationLayerData = [];

        if(this.props.fromWhere && this.props.fromWhere == 'allCoursePage'){
            this.config = allCoursePageDesktopConstants();
        }
        else if(this.props.fromWhere && this.props.fromWhere == 'admissionPage'){
            this.config = AdmissionPageDesktopConstants();
        }
        else if(this.props.fromWhere && this.props.fromWhere == 'placementPage'){
            this.config = PlacmentPageDesktopConstants();
        }
        else if(this.props.fromWhere && this.props.fromWhere == 'cutOffPage'){
            this.config = CutoffPageDesktopConstants();
        }
        else if(this.props.page == 'institute'){
            this.config = ILPConstants();
        }else{
            this.config = ULPConstants();
        }
    }

    instituteLocationName(){
        const {data} = this.props;
        let locationName = '';

        if(data.currentLocation){
            if(data.currentLocation.locality_name){
                locationName +=  data.currentLocation.locality_name;
            }
            let showCityName = this.showCityName();
            if(data.currentLocation.city_name && showCityName){
                if(locationName !=''){
                    locationName += ', ';
                }
                locationName += data.currentLocation.city_name;
            }
        }
        return locationName;
    }

    showCityName(){
        const {data} = this.props;
        let instituteName = '';
        let cityName = '';
        if(data.instituteTopCardData && data.instituteTopCardData.instituteName){
            instituteName = data.instituteTopCardData.instituteName.toLowerCase();
        }
        if(data.currentLocation && data.currentLocation.city_name){
            cityName = data.currentLocation.city_name.toLowerCase();
        }
        return (instituteName.indexOf(cityName) == -1)

    }

    handleClickOnInstitute(instituteName,instituteId)
    {
        this.trackEvent('affiliationLink','click');
        if(instituteName && instituteId) {
            var data = {};
            data.instituteName = instituteName;
            data.instituteId = instituteId;
            this.props.storeInstituteDataForPreFilled(data);
        }
    }

    getHeadingHtml(heading,extraHeading =null ,linkingUrl = ""){
        var headingHtml = [];
        if( this.props.fromwhere && this.props.fromwhere == "institutePage"){
            headingHtml.push(<h2 className='inst-name' key="heading_h1">{heading} <span className='hid'> ,{this.instituteLocationName()}</span></h2>);
        }
        else{
            headingHtml.push(<h2 className='inst-name' key="heading_h1"><a href={ addingDomainToUrl(linkingUrl, this.props.config.SHIKSHA_HOME)}>{heading}</a> {extraHeading}<span className='hid'> ,{this.instituteLocationName()}</span></h2>);
        }
        return headingHtml;
    }


    renderNameInfo()
    {
        const {config,data} = this.props;
        var AllQuestionCount = (data.anaCountString == "0")?null:data.anaCountString ;
        var AllQuestionUrl = data.anaUrl;
        if(typeof AllQuestionUrl =='undefined' &&  data.anaWidget && data.anaWidget.allQuestionURL){
            AllQuestionUrl = data.anaWidget.allQuestionURL;
        }
        AllQuestionUrl = addingDomainToUrl(AllQuestionUrl,config.SHIKSHA_HOME);
        var heading =  data.instituteTopCardData.instituteName;
        var headingHtml = this.getHeadingHtml(heading,this.props.extraHeading,'');
        var ratingData = false;
        if(data.seoData && this.props.allCoursePage && !this.props.contentLoader){
            headingHtml = this.getHeadingHtml(data.seoData.headingName,data.seoData.headingSuffix,data.listingUrl);
        }
        if(data.aggregateReviewWidget != null && data.reviewWidget != null && data.reviewWidget.reviewData != null && data.reviewWidget.reviewData.reviewsData  &&  data.aggregateReviewWidget.aggregateReviewData){
            ratingData = true;
        }

        // var isPaid = true;
        // if(typeof data.courseWidget !='undefined' && data.courseWidget && typeof data.courseWidget.instituteHasPaidCourse !='undefined'){
        //     isPaid = data.courseWidget.instituteHasPaidCourse;
        // }
        return (
            <React.Fragment>

                <div className="text-cntr clg_dtlswidget">
                    {headingHtml}
                    <div className="rank-widget">
                        <div className="clg-col single-col">
                            <ul className="aggregate_rating">
                                <li>
                                    { this.instituteLocationName()!='' ?
                                        (<span className="ilp-loc"> <i className= 'clg_location_icon'></i><span>{this.instituteLocationName()}</span></span>):null}
                                    { (this.instituteLocationName()!='')? <span className="pipe-sep"> | </span>:''}
                                    {this.props.showChangeBranch && <ChangeBranch {...this.props}/>}
                                    {( this.props.showChangeBranch && Object.keys(data.locations).length > 1 && (ratingData || AllQuestionCount != null))? <span className="pipe-sep"> | </span>:''}
                                    {ratingData && <AggregateReview isPaid ={false} config={config} uniqueKey= {'institute_'+data.listingId}   reviewType ={data.listingType} reviewsCount={data.reviewWidget.reviewData.allReviewsCount} reviewUrl = {data.reviewWidget.reviewData.allReviewUrl} aggregateReviewData = {data.aggregateReviewWidget} showAllreviewUrl = {true} gaTrackingCategory={this.props.gaTrackingCategory} showReviewBracket={true} showPopUpLayer={true}/>}
                                    {
                                        (ratingData && AllQuestionCount !=null  )?<span className="pipe-sep"> | </span>:''}
                                    {AllQuestionCount !=null &&
                                    <a className="ans_qst qstn" onClick={this.trackEvent.bind(this,"Click","Header_AnsweredQuestions")} href={AllQuestionUrl}><i className="qstn_ico"></i>{AllQuestionCount}{(AllQuestionCount == "1")? " Answered Question":" Answered Questions"}</a>
                                    }
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </React.Fragment>
        )
    }



    trackEvent(actionLabel,label)
    {
        var category = 'ILP';
        if(this.props.page == "university"){
            category = 'ULP';
        }

        if(this.props.gaTrackingCategory){
            category = this.props.gaTrackingCategory;
        }

        Analytics.event({category : category, action : actionLabel, label : label});
    }


    render(){
        const {data} = this.props;
        return(
            <div className="pwa-headerwrapper hid" id="sticky_header">
                <div className="pwa_topwidget">
                    {this.renderNameInfo()}
                    <div className="flex flex-column">
                        <div className="topcard_btns">
                            <a onClick={this.downloadEBRequest.bind(this,{'listingId':data.listingId,'listingName':data.instituteTopCardData.instituteName, 'listingType':data.listingType,'ebTrackid' :this.config.shortlistTrackingIdSticky, 'pageType':this.props.pageType}, 'Shortlist')} href="javascript:void(0);" cta-type="shortlist" className={"ctp-btn ctpComp-btn rippleefect tupleShortlistButton shrt"+data.listingId} customcallback="listingShortlistCallback" customactiontype='shortlist' ><span instid={data.listingId} product="NM_ACPDesktop" >Shortlist</span></a>
                            <a onClick={this.downloadEBRequest.bind(this,{'listingId':data.listingId,'listingName':data.instituteTopCardData.instituteName, 'listingType':data.listingType,'ebTrackid' :this.config.downloadBrochureTrackingIdSticky, 'pageType':this.props.pageType}, 'DownloadBrochure')}  href="javascript:void(0);" cta-type="download_brochure" className={"ctp-btn ctpBro-btn rippleefect tupleBrochureButton brchr_"+data.listingId} customactiontype='DownloadBrochure'> <span instid={data.listingId} product="NM_ACPDesktop" >Apply Now</span></a>
                        </div>
                    </div>
                </div>
            </div>
        )
    }

    downloadEBRequest = (params, gaLabel, e) => {
        let thisObj = e.currentTarget;
        if(typeof(window) !='undefined' && typeof(ajaxDownloadEBrochure) !='undefined'){
            this.trackEvent(gaLabel, 'Response');
            window.ajaxDownloadEBrochure(thisObj, params.listingId, params.listingType, params.listingName, params.pageType, params.ebTrackid,this.config.downloadBrochureTrackingIdRecoLayer, this.config.compareTrackingIdRecoLayer, 0, this.config.shortlistTrackingIdRecoLayer);
        }
    }

}

export default (TopWidgetSticky);

TopWidgetSticky.propTypes = {
  allCoursePage: PropTypes.any,
  config: PropTypes.any,
  contentLoader: PropTypes.any,
  data: PropTypes.any,
  extraHeading: PropTypes.any,
  fromWhere: PropTypes.any,
  fromwhere: PropTypes.any,
  gaTrackingCategory: PropTypes.any,
  page: PropTypes.any,
  pageType: PropTypes.any,
  showChangeBranch: PropTypes.any,
  storeInstituteDataForPreFilled: PropTypes.any
}
