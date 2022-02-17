import PropTypes from 'prop-types'
import React  from 'react';
import AggregateReview from './../../course/components/AggregateReviewWidget';
import { Link } from "react-router-dom";
import {renderColumnStructureCommon} from './../../course/utils/listingCommonUtil';
import PopupLayer from '../../../common/components/popupLayer';
import Analytics from './../../../reusable/utils/AnalyticsTracking';
import DownloadEBrochure from './../../../common/components/DownloadEBrochure';
import Shortlist from './../../../common/components/Shortlist';
import {removeDomainFromUrl} from './../../../../utils/urlUtility';
import {addingDomainToUrl} from './../../../../utils/commonHelper';
import './../../course/assets/topWidgetCommon.css';
import ChangeBranch from './../../course/components/ChangeBranchLinkComponent';
import {ILPConstants, ULPConstants,allCourseConstants,AdmissionPageConstants,allCoursePageDesktopConstants,AdmissionPageDesktopConstants,PlacmentPageDesktopConstants,PlacementPageConstants,CutoffPageDesktopConstants,CutoffPageConstants} from './../../categoryList/config/categoryConfig';
import {storeInstituteDataForPreFilled} from './../actions/InstituteDetailAction';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';

class TopWidget extends React.Component{

    constructor(props) {
        super(props);

        this.locationLayerData = [];
        if(this.props.fromWhere && this.props.fromWhere == 'allCoursePage'){
            this.config = this.props.isDesktop ? allCoursePageDesktopConstants():allCourseConstants();
        }
        else if(this.props.fromWhere && this.props.fromWhere == 'admissionPage'){
            this.config = this.props.isDesktop ?AdmissionPageDesktopConstants(): AdmissionPageConstants();
        }
        else if(this.props.fromWhere && this.props.fromWhere == 'placementPage'){
            this.config = this.props.isDesktop ?PlacmentPageDesktopConstants(): PlacementPageConstants();
        }
        else if(this.props.fromWhere && this.props.fromWhere == 'cutOffPage'){
            this.config = this.props.isDesktop ?CutoffPageDesktopConstants(): CutoffPageConstants();
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
            headingHtml.push(<h1 className='inst-name' key="heading_h1">{heading} <span className='hid'> ,{this.instituteLocationName()}</span></h1>);
        }
        else if(this.props.isDesktop == true){
            headingHtml.push(<h1 className='inst-name' key="heading_h1"><a onClick={this.trackEvent.bind(this,"Click","IULP_Top_Card")} href={ addingDomainToUrl(linkingUrl, this.props.config.SHIKSHA_HOME)}>{heading}</a> {extraHeading}<span className='hid'> ,{this.instituteLocationName()}</span></h1>);
        }else{
            headingHtml.push(<h1 className='inst-name' key="heading_h1"><Link onClick={this.trackEvent.bind(this,"Click","IULP_Top_Card")} to={linkingUrl}>{heading}</Link> {extraHeading}<span className='hid'> ,{this.instituteLocationName()}</span></h1>);
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

        // var ireviewWidgetsPaid = true;
        // if(typeof data.courseWidget !='undefined' && data.courseWidget && typeof data.courseWidget.instituteHasPaidCourse !='undefined'){
        //     isPaid = data.courseWidget.instituteHasPaidCourse;
        // }
        return (
            <React.Fragment>

                <div className="text-cntr clg_dtlswidget">
                    {headingHtml}
                    <div className="rank-widget">
                        <ul className="aggregate_rating">
                            <li>
                                { this.instituteLocationName()!='' ?
                                    (<span className="ilp-loc"> <i className= 'clg_location_icon'></i><span> {this.instituteLocationName()}</span></span>):null}
                                {(this.instituteLocationName()!='')? <span className="pipe-sep"> | </span>:''}
                                {this.props.showChangeBranch && <ChangeBranch {...this.props}/>}
                                {( this.props.showChangeBranch && Object.keys(data.locations).length > 1 && (ratingData || AllQuestionCount != null))? <span className="pipe-sep"> | </span>:''}
                                {ratingData &&<AggregateReview isPaid ={false} config={config} uniqueKey= {'institute_'+data.listingId}   reviewType ={data.listingType} reviewsCount={data.reviewWidget.reviewData.allReviewsCount} reviewUrl = {data.reviewWidget.reviewData.allReviewUrl} aggregateReviewData = {data.aggregateReviewWidget} showAllreviewUrl = {true} gaTrackingCategory={this.props.gaTrackingCategory} showReviewBracket={true} showPopUpLayer={(this.props.isDesktop)?true:false}/> }
                                {(ratingData && AllQuestionCount !=null  )? <span className="pipe-sep"> | </span>:''}
                                {AllQuestionCount !=null &&
                                <a className="ans_qst qstn" onClick={this.trackEvent.bind(this,"Click","Header_AnsweredQuestions")} href={AllQuestionUrl}><i className="qstn_ico"></i>{AllQuestionCount}{(AllQuestionCount == "1")? " Answered Question":" Answered Questions"}</a>
                                }
                            </li>
                        </ul>
                    </div>


                </div>
            </React.Fragment>
        )
    }

    renderOwnershipInfo(){
        const {data} = this.props;
        if(data.instituteTopCardData.inlineData && data.instituteTopCardData.inlineData.ownership){
            return(
                <React.Fragment>
                    <span key="ownership" className='clg-tip'>{data.instituteTopCardData.inlineData.ownership}</span>
                </React.Fragment>
            )
        }
        return null;

    }

    renderRecoginitionInfo(){
        const {data} = this.props;
        if(data.instituteTopCardData.instituteImportantData && data.instituteTopCardData.instituteImportantData.ugcApproved){
            return(
                <React.Fragment>
                    <span className='clg-tip'>UGC Approved</span>
                </React.Fragment>
            )
        }
        return null;
    }

    renderCourseStatusInfo(){
        const {data} = this.props;
        if(data.instituteTopCardData.instituteImportantData && data.instituteTopCardData.instituteImportantData.autonomous){
            return(
                <React.Fragment>
                    <span className='clg-tip'>Autonomous</span>
                </React.Fragment>
            )
        }
        return null;

    }
    renderImportanceInfo(){

        const {data} = this.props;
        if(data.instituteTopCardData.instituteImportantData && data.instituteTopCardData.instituteImportantData.nationalImportance){
            return (
                <React.Fragment>
                    <span key="Importance" className='clg-tip'>Institute of National Importance</span>
                </React.Fragment>
            )
        }
        return null;
    }

    renderUniversityTypeInfo(){
        const {data} = this.props;
        if(data.instituteTopCardData.instituteImportantData && data.instituteTopCardData.instituteImportantData.univeristyTypeWithSpecification){
            return(
                <React.Fragment>
                    <span key="university" className='clg-tip'>{data.instituteTopCardData.instituteImportantData.univeristyTypeWithSpecification}</span>
                </React.Fragment>
            )
        }
        return null;
    }

    renderAIUMemberInfo(){
        const {data} = this.props;
        if(data.instituteTopCardData.instituteImportantData && data.instituteTopCardData.instituteImportantData.aiuMember){
            return(
                <React.Fragment>
                    <span className='clg-tip'>AIU Member</span>
                </React.Fragment>
            )
        }
        return null;
    }

    renderAccreditationInfo(){
        const {data} = this.props;
        if(data.instituteTopCardData.instituteImportantData && data.instituteTopCardData.instituteImportantData.naacAccreditation){
            return(
                <React.Fragment>
                    <span key="Accreditation" className='clg-tip'>{"NAAC Accreditation ( " + data.instituteTopCardData.instituteImportantData.naacAccreditation + ")"}</span>
                </React.Fragment>
            )
        }
        return null;

    }

    openRankingLayer(){
        this.trackEvent('Header','click_moreRanks');
        this.openRankingLayerPopup.open();
    }



    trackEvent(actionLabel,label)
    {
        var category = 'ILP';
        if(this.props.page == "university"){
            category = 'ULP';
        }
        if(this.props.fromWhere && this.props.fromWhere == 'allCoursePage'){
            category = 'AllCoursePage_PWA';
        }
        else if(this.props.fromWhere && this.props.fromWhere == 'admissionPage'){
            category = 'AdmissionPage_PWA';
        }

        if(this.props.gaTrackingCategory){
            category = this.props.gaTrackingCategory;
        }

        Analytics.event({category : category, action : actionLabel, label : label});
    }


    renderRankingHtml(){
        const { data} = this.props;
        var rankingData = this.props.page == 'institute' &&  data.instituteTopCardData.rankingData && typeof data.instituteTopCardData.rankingData != 'undefined' && Object.keys(data.instituteTopCardData.rankingData).length > 0 ? data.instituteTopCardData.rankingData : [];

        if(rankingData.length == 0)
            return null;

        var rank =  rankingData[0];

        return (<React.Fragment>
                <p key="rank"><strong>Ranked {rank.displayableRank}</strong> for <Link to={removeDomainFromUrl(rank.url)}>{rank.rankingPageText}</Link>  by {rank.publisherName} {rank.year} {rankingData.length > 1 && <a className='clp-mrLink more-btn' href="javascript:void(0)" onClick={this.openRankingLayer.bind(this)}> +{(parseInt(rankingData.length)-1)} more</a>
                }</p>
                {rankingData.length > 1 && <PopupLayer onRef={ref => (this.openRankingLayerPopup = ref)} data={this.generateRankingLayer()} heading="College Ranking"/>}
            </React.Fragment>
        )

    }


    generateRankingLayer()
    {
        const {data} = this.props;
        var rankingData = typeof data.instituteTopCardData.rankingData != 'undefined' && Object.keys(data.instituteTopCardData.rankingData).length > 0 ? data.instituteTopCardData.rankingData : [];
        return (
            <div className="glry-div">
                <div className="hlp-info">
                    <div className="loc-list-col">
                        <div className='prm-lst'>
                            <div className='amen-box'>
                                <ul className='n-more-ul rnk-pb5'>
                                    {this.generateRankingLayerList(rankingData)}
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        )
    }

    generateRankingLayerList(keyData){
        var list = new Array();
        for(var i in keyData){
            if(i == 0 ) continue;
            var rank = keyData[i];
            if(keyData[i].publisherName){
                list.push(<li key={i}>Ranked {rank.displayableRank} for <Link className='lyr-link' to={removeDomainFromUrl(rank.url)} >{rank.rankingPageText}</Link>  by {rank.publisherName} {rank.year} </li>);
            }
        }
        return list;
    }


    generateBHSTHtml(){
        var bhst = [];
        if(this.props.fromWhere=='admissionPage'){
            bhst.push(<div className="addtnl-col" key="bhst">
                <p className="txt-later">  Find details of the Admission Process - Eligibility, Dates and Cut Offs </p>
            </div>)
        }
        return bhst;
    }


    render(){
        const {data} = this.props;
        var headerImage = null;
        if(this.props.data.instituteTopCardData && this.props.isDesktop && this.props.data.instituteTopCardData.headerImageDesktop  ){
            headerImage = this.props.data.instituteTopCardData.headerImageDesktop ;
        }
        else if(this.props.data.instituteTopCardData && this.props.data.instituteTopCardData.headerImageMobile){
            headerImage = this.props.data.instituteTopCardData.headerImageMobile ;
        }
        // headerImage = null;

        var logo = (this.props.data.instituteTopCardData && this.props.data.instituteTopCardData.logoImageUrl)? this.props.data.instituteTopCardData.logoImageUrl : null;

        return(

            <div className="pwa_headerv1">
                {<div className={"header-bgcol "+(this.props.isDesktop?"desktop":"mobile")} style={(headerImage)?{"backgroundImage" : "url('"+headerImage+"')"}:null} >
                </div>}
                <div className="pwa-headerwrapper">
                    <div className="pwa_topwidget">
                        {logo && <div className="header_img">
                            <img src={logo}/>
                        </div>}
                        {this.renderNameInfo()}
                        <div className="flex flex-column">
                            {renderColumnStructureCommon(this, this.props.page)}
                            <div className="topcard_btns" id='CTASection'>
                                {this.props.isDesktop ?
                                    <a onClick={this.downloadEBRequest.bind(this,{'listingId':data.listingId,'listingName':data.instituteTopCardData.instituteName, 'listingType':data.listingType,'ebTrackid' :this.config.shortlistTrackingIdTopWidget, 'pageType':this.props.pageType}, 'Shortlist')} href="javascript:void(0);" cta-type="shortlist" className={"ctp-btn ctpComp-btn rippleefect tupleShortlistButton shrt"+data.listingId} customcallback="listingShortlistCallback" customactiontype="NM_ACPDesktop"><span instid={data.listingId} product="NM_ACPDesktop" >Shortlist</span></a>

                                    : <Shortlist  actionType="NM_InstituteDetailPage" listingId={data.listingId} trackid={this.config.shortlistTrackingIdTopWidget}  recoEbTrackid={this.config.downloadBrochureTrackingIdRecoLayer} recoShrtTrackid={this.config.shortlistTrackingIdRecoLayer} recoPageType="EBrochure_RECO_Layer" recoActionType="EBrochure_RECO_Layer" pageType="NM_InstituteDetailPage" sessionId="" visitorSessionid="" isCallReco={this.props.isCallReco} clickHandler={this.trackEvent.bind(this,'Header','click_Shortlist')} isButton={true} page = {this.props.page}/> }

                                {this.props.isDesktop ?

                                    <a onClick={this.downloadEBRequest.bind(this,{'listingId':data.listingId,'listingName':data.instituteTopCardData.instituteName, 'listingType':data.listingType,'ebTrackid' :this.config.downloadBrochureTrackingIdTopWidget, 'pageType':this.props.pageType}, 'DownloadBrochure')}  href="javascript:void(0);" cta-type="download_brochure" className={"ctp-btn ctpBro-btn rippleefect tupleBrochureButton brchr_"+data.listingId} customactiontype="NM_ACPDesktop"> <span instid={data.listingId} product="NM_ACPDesktop" >Apply Now</span></a>

                                    : <DownloadEBrochure  buttonText="Request Brochure" listingId={data.listingId} listingName={data.instituteTopCardData.instituteName} trackid={this.config.downloadBrochureTrackingIdTopWidget} recoEbTrackid={this.config.downloadBrochureTrackingIdRecoLayer}  recoShrtTrackid={this.config.shortlistTrackingIdRecoLayer} isCallReco={this.props.isCallReco} clickHandler={this.trackEvent.bind(this,'Header','click_Request_Brochure')} page = {this.props.page}/> }
                            </div>
                            {this.generateBHSTHtml()}
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

function mapDispatchToProps(dispatch){
    return bindActionCreators({storeInstituteDataForPreFilled }, dispatch);
}

export default connect(null,mapDispatchToProps)(TopWidget);

TopWidget.propTypes = {
  allCoursePage: PropTypes.any,
  config: PropTypes.any,
  contentLoader: PropTypes.any,
  data: PropTypes.any,
  extraHeading: PropTypes.any,
  fromWhere: PropTypes.any,
  fromwhere: PropTypes.any,
  gaTrackingCategory: PropTypes.any,
  isCallReco: PropTypes.any,
  isDesktop: PropTypes.any,
  page: PropTypes.any,
  pageType: PropTypes.any,
  showChangeBranch: PropTypes.any,
  storeInstituteDataForPreFilled: PropTypes.any
}
