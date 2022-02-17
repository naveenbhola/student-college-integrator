import PropTypes from 'prop-types'
import React  from 'react';
import AggregateReview from './../../course/components/AggregateReviewWidget';
import { Link } from "react-router-dom";
import {renderColumnStructure} from './../../course/utils/listingCommonUtil';
import PopupLayer from '../../../common/components/popupLayer';
import Analytics from './../../../reusable/utils/AnalyticsTracking';
import DownloadEBrochure from './../../../common/components/DownloadEBrochure';
import Shortlist from './../../../common/components/Shortlist';
import { removeDomainFromUrl} from './../../../../utils/urlUtility';
import {addingDomainToUrl} from './../../../../utils/commonHelper';
import './../../course/assets/topWidget.css';
import ChangeBranch from './../../course/components/ChangeBranchLinkComponent';
import {ILPConstants, ULPConstants,allCourseConstants,AdmissionPageConstants} from './../../categoryList/config/categoryConfig';
import {storeInstituteDataForPreFilled} from './../actions/InstituteDetailAction';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import ChangeBranchAmp from '../../../../../views/amp/pages/course/components/ChangeBranchAmp';
import AmpLightBox from './../../../common/components/AmpLightbox';


function bindingFunctions(functions)
{
    functions.forEach((f) => (this[f] = this[f].bind(this)));
}

class TopWidget extends React.Component{

    constructor(props) {
        super(props);
        bindingFunctions.call(this,[
            'openRecognitionLayer',
            'openAIUMemberLayer',
            'openCourseStatusLayer',
            'openUniversityTypeLayer',
            'openAffiliationLayer',
            'openRankingLayer',
            'openAccreditationLayer',
            'openNationalInstituteLayer'
        ]);

        this.locationLayerData = [];

        if(this.props.fromWhere && this.props.fromWhere == 'allCoursePage'){
            this.config = allCourseConstants();
        }
        else if(this.props.fromWhere && this.props.fromWhere == 'admissionPage'){
            this.config = AdmissionPageConstants();
        }
        else if(this.props.page == 'instiute'){
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
        else{
            headingHtml.push(<h1 className='inst-name' key="heading_h1"><Link to={linkingUrl}>{heading}</Link> {extraHeading}<span className='hid'> ,{this.instituteLocationName()}</span></h1>);
        }
        return headingHtml;
    }


    renderNameInfo()
    {
        const {config,data,isAmp} = this.props;
        var AllQuestionCount = (data.anaCountString == "0")?null:data.anaCountString ;
        var AllQuestionUrl = data.anaUrl;
        if(typeof AllQuestionUrl =='undefined' &&  data.anaWidget && data.anaWidget.allQuestionURL){
            AllQuestionUrl = data.anaWidget.allQuestionURL;
        }
        AllQuestionUrl = addingDomainToUrl(AllQuestionUrl,config.SHIKSHA_HOME);
        var heading =  data.instituteTopCardData.instituteName;
        var headingHtml = this.getHeadingHtml(heading,this.props.extraHeading,'');
        var ratingData = false;
        if(data.seoData && this.props.allCoursePage){
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
                {(!isAmp) ?
                    <React.Fragment>
                        <div className="ilp_head">
                            {headingHtml}
                            <i className="pwa-shrtlst-ico">
                                <Shortlist className="pwa-shrtlst-ico" actionType="NM_InstituteDetailPage" listingId={data.listingId} trackid={this.config.shortlistTrackingIdTopWidget}  recoEbTrackid={this.config.downloadBrochureTrackingIdRecoLayer} recoShrtTrackid={this.config.shortlistTrackingIdRecoLayer} recoPageType="EBrochure_RECO_Layer" recoActionType="EBrochure_RECO_Layer" pageType="NM_InstituteDetailPage" sessionId="" visitorSessionid="" isCallReco={this.props.isCallReco} clickHandler={this.trackEvent.bind(this,'Header','click_Shortlist')} isButton={false} page = {this.props.page}/>
                            </i>
                            { this.instituteLocationName()!='' ?
                                (<span className="ilp-loc"> <i className= 'clg_location_icon'></i><span>{this.instituteLocationName()}</span></span>):null}
                            {this.props.showChangeBranch && <ChangeBranch {...this.props}/>}
                        </div>
                        <ul className="aggregate_rating">
                            {ratingData &&
                            <li>
                                <AggregateReview isPaid ={false} config={config} uniqueKey= {'institute_'+data.listingId}   reviewType ={data.listingType} reviewsCount={data.reviewWidget.reviewData.totalReviews} reviewUrl = {data.reviewWidget.reviewData.allReviewUrl} aggregateReviewData = {data.aggregateReviewWidget} showAllreviewUrl = {true} gaTrackingCategory={this.props.gaTrackingCategory} showReviewBracket={true}/>
                            </li>
                            }
                            {(ratingData && AllQuestionCount !=null  )? <li><span className="pipe-sep"> | </span></li>:''}
                            {AllQuestionCount !=null &&
                            <li>
                                <a className="ans_qst qstn" onClick={this.trackEvent.bind(this,"Click","Header_AnsweredQuestions")} href={AllQuestionUrl}><i className="qstn_ico"></i>{AllQuestionCount}{(AllQuestionCount == "1")? " Answered Question":" Answered Questions"}</a>
                            </li>
                            }
                        </ul>
                    </React.Fragment>:
                    <React.Fragment>
                        <h1 className="color-3 f16 font-w6 pos-rl">{data.instituteTopCardData.instituteName}</h1>
                        <p className="loc-col f12 color-6 font-w4"><i className="loc-i"></i>{this.instituteLocationName()}</p>
                        <ChangeBranchAmp {...this.props}/>
                    </React.Fragment>
                }
            </React.Fragment>
        )
    }

    renderOwnershipInfo(){
        const {data, isAmp} = this.props;
        if(data.instituteTopCardData.inlineData && data.instituteTopCardData.inlineData.ownership){
            return(
                <React.Fragment>
                    {(!isAmp) ?
                        <React.Fragment>
                            <p><span className="clg-label-tip">Ownership</span></p>
                            <span className='clg-tip'>{data.instituteTopCardData.inlineData.ownership}</span>
                        </React.Fragment>:
                        <React.Fragment>
                            <span className="font-w6 f12 color-3">{data.instituteTopCardData.inlineData.ownership}</span>
                        </React.Fragment>
                    }
                </React.Fragment>
            )
        }
        return null;

    }
    renderEstablishedInfo(){
        const {data, isAmp} = this.props;
        if(data.instituteTopCardData.inlineData && data.instituteTopCardData.inlineData.estbYear){
            return (
                <React.Fragment>
                    {(!isAmp) ?
                        <React.Fragment>
                            <p><span className="clg-label-tip">Established</span></p>
                            <span className='clg-tip'>{data.instituteTopCardData.inlineData.estbYear}</span>
                        </React.Fragment>:
                        <React.Fragment>
                            <span className="font-w6 f12 color-3">Established {data.instituteTopCardData.inlineData.estbYear}</span>
                        </React.Fragment>
                    }
                </React.Fragment>
            )
        }
        return null;

    }
    renderCourseStatusInfo(){
        const {data, isAmp} = this.props;
        if(data.instituteTopCardData.instituteImportantData && data.instituteTopCardData.instituteImportantData.autonomous){
            return(
                <React.Fragment>
                    {(!isAmp) ?
                        <React.Fragment>
                            <span className='clg-tip'>Autonomous <i className="info-icon" onClick ={this.openCourseStatusLayer} ></i> </span>
                            {data.instituteToolTipData && typeof data.instituteToolTipData.autonomous != 'undefined' && <PopupLayer onRef={ref => (this.openCourseStatusLayer = ref)} data={this.generateInstituteToolTipData('autonomous')} heading="Autonomous"/>}
                        </React.Fragment>:
                        <React.Fragment>
                            <span className="font-w6 f12 color-3">Autonomous Institute</span>
                            <a className="pos-rl ga-analytic" on="tap:view-info-autonomous" role="button" tabIndex="0">
                                <i className="cmn-sprite clg-info i-block v-mdl"></i>
                            </a>
                            {data.instituteToolTipData && typeof data.instituteToolTipData.autonomous != 'undefined' && <AmpLightBox id="view-info-autonomous" onRef={ref => (this.openCourseStatusLayer = ref)} data={this.generateInstituteToolTipData('autonomous')} heading="Autonomous"/>}
                        </React.Fragment>
                    }
                </React.Fragment>
            )
        }
        return null;

    }
    renderImportanceInfo(){

        const {data, isAmp} = this.props;
        if(data.instituteTopCardData.instituteImportantData && data.instituteTopCardData.instituteImportantData.nationalImportance){
            return (
                <React.Fragment>
                    {(!isAmp) ?
                        <React.Fragment>
                            <span className='clg-tip'>Institute of National Importance <i className="info-icon" onClick ={this.openNationalInstituteLayer} ></i></span>
                            {data.instituteToolTipData && typeof data.instituteToolTipData.nationalImportance != 'undefined' && <PopupLayer onRef={ref => (this.openNationalInstituteLayerPopup = ref)} data={this.generateInstituteToolTipData('nationalImportance')} heading='National Importance'/>}
                        </React.Fragment> :
                        <React.Fragment>
                            <span className='font-w6 f12 color-3'>Institute of National Importance</span>
                            <a className="pos-rl ga-analytic" on="tap:view-info-imp" role="button" tabIndex="0">
                                <i className="cmn-sprite clg-info i-block v-mdl"></i>
                            </a>
                            {data.instituteToolTipData && typeof data.instituteToolTipData.nationalImportance != 'undefined' && <AmpLightBox id="view-info-imp" onRef={ref => (this.openNationalInstituteLayer = ref)} data={this.generateInstituteToolTipData('nationalImportance')} heading='National Importance'/>}
                        </React.Fragment>
                    }
                </React.Fragment>
            )
        }
        return null;
    }
    renderRecoginitionInfo(){
        const {data, isAmp} = this.props;
        if(data.instituteTopCardData.instituteImportantData && data.instituteTopCardData.instituteImportantData.ugcApproved){
            return(
                <React.Fragment>
                    {(!isAmp) ?
                        <React.Fragment>
                            <span className='clg-tip'>UGC Approved <i className="info-icon" onClick ={this.openRecognitionLayer} ></i></span>
                            {data.instituteToolTipData && typeof data.instituteToolTipData.ugc_approved != 'undefined' && <PopupLayer onRef={ref => (this.openRecognitionLayerPopup = ref)} data={this.generateInstituteToolTipData('ugc_approved')} heading='UGC Approved'/>}
                        </React.Fragment>:
                        <React.Fragment>
                            <span className="font-w6 f12 color-3">UGC Approved</span>
                            <a className="pos-rl ga-analytic" on="tap:view-info-ugc_approved" role="button" tabIndex="0">
                                <i className="cmn-sprite clg-info i-block v-mdl"></i>
                            </a>
                            {data.instituteToolTipData && typeof data.instituteToolTipData.ugc_approved != 'undefined' && <AmpLightBox id="view-info-ugc_approved" onRef={ref => (this.openRecognitionLayer = ref)} data={this.generateInstituteToolTipData('ugc_approved')} heading='UGC Approved'/>}
                        </React.Fragment>
                    }
                </React.Fragment>
            )
        }
        return null;
    }

    renderUniversityTypeInfo(){
        const {data, isAmp} = this.props;
        if(data.instituteTopCardData.instituteImportantData && data.instituteTopCardData.instituteImportantData.univeristyTypeWithSpecification){
            var universityType = data.instituteTopCardData.instituteImportantData.univeristyTypeWithSpecification.toLowerCase();
            return(
                <React.Fragment>
                    {(!isAmp) ?
                        <React.Fragment>
                            <p><span className="clg-label-tip">University Type <i className="info-icon" onClick ={this.openUniversityTypeLayer}></i></span></p>
                            <span className='clg-tip'>{data.instituteTopCardData.instituteImportantData.univeristyTypeWithSpecification}</span>
                            {data.instituteToolTipData && typeof data.instituteToolTipData['university_type_'+universityType] != 'undefined' && <PopupLayer onRef={ref => (this.openUniversityTypeLayerPopup = ref)} data={this.generateInstituteToolTipData('university_type_'+universityType)} heading={data.instituteTopCardData.instituteImportantData.univeristyTypeWithSpecification+' University'}/>}
                        </React.Fragment>:
                        <React.Fragment>
                            <span className="font-w6 f12 color-3">{data.instituteTopCardData.instituteImportantData.univeristyTypeWithSpecification} University</span>
                            <a className="pos-rl ga-analytic" on="tap:view-info-university-type" role="button" tabIndex="0">
                                <i className="cmn-sprite clg-info i-block v-mdl"></i>
                            </a>
                            {data.instituteToolTipData && typeof data.instituteToolTipData['university_type_'+universityType] != 'undefined' && <AmpLightBox id="view-info-university-type" onRef={ref => (this.openUniversityTypeLayer = ref)} data={this.generateInstituteToolTipData('university_type_'+universityType)} heading={data.instituteTopCardData.instituteImportantData.univeristyTypeWithSpecification+' University'} />}
                        </React.Fragment>
                    }
                </React.Fragment>
            )
        }
        return null;
    }

    renderAIUMemberInfo(){
        const {data, isAmp} = this.props;
        if(data.instituteTopCardData.instituteImportantData && data.instituteTopCardData.instituteImportantData.aiuMember){
            return(
                <React.Fragment>
                    {(!isAmp) ?
                        <React.Fragment>
                            <span className='clg-tip'>AIU Member<i className="info-icon" onClick ={this.openAIUMemberLayer}></i></span>
                            {data.instituteToolTipData && typeof data.instituteToolTipData.aiu_member != 'undefined' && <PopupLayer onRef={ref => (this.openAIUMemberLayerPopup = ref)} data={this.generateInstituteToolTipData('aiu_member')} heading="AIU Member"/>}
                        </React.Fragment>:
                        <React.Fragment>
                            <span className='font-w6 f12 color-3'>Member of AIU </span>
                            <a className="pos-rl ga-analytic" on="tap:view-info-aumember" role="button" tabIndex="0">
                                <i className="cmn-sprite clg-info i-block v-mdl"></i>
                            </a>
                            {data.instituteToolTipData && typeof data.instituteToolTipData.aiu_member != 'undefined' && <AmpLightBox id="view-info-aumember" onRef={ref => (this.openAIUMemberLayer = ref)} data={this.generateInstituteToolTipData('aiu_member')} heading="Member of AIU" />}
                        </React.Fragment>
                    }
                </React.Fragment>
            )
        }
        return null;
    }

    renderAccreditationInfo(){
        const {data, isAmp} = this.props;
        if(data.instituteTopCardData.instituteImportantData && data.instituteTopCardData.instituteImportantData.naacAccreditation){
            return(
                <React.Fragment>
                    {(!isAmp)?
                        <React.Fragment>
                            <p><span className="clg-label-tip">Accreditation<i className="info-icon" onClick ={this.openAccreditationLayer}></i></span></p>
                            <span className='clg-tip'>{data.instituteTopCardData.instituteImportantData.naacAccreditation}</span>
                            {data.instituteToolTipData && typeof data.instituteToolTipData.naac_accreditation != 'undefined' && <PopupLayer onRef={ref => (this.openAccreditationLayerPopup = ref)} data={this.generateInstituteToolTipData('naac_accreditation')} heading="NAAC Accreditation"/>}
                        </React.Fragment>:
                        <React.Fragment>
                            <span className='font-w6 f12 color-3'>Accreditation {data.instituteTopCardData.instituteImportantData.naacAccreditation}</span>
                            <a className="pos-rl ga-analytic" on="tap:view-info-univ-accredited" role="button" tabIndex="0">
                                <i className="cmn-sprite clg-info i-block v-mdl"></i>
                            </a>
                            {data.instituteToolTipData && typeof data.instituteToolTipData.naac_accreditation != 'undefined' && <AmpLightBox onRef={ref => (this.openAccreditationLayer = ref)} id="view-info-univ-accredited" data={this.generateInstituteToolTipData('naac_accreditation')} heading="NAAC Accreditation"/>}
                        </React.Fragment>
                    }
                </React.Fragment>
            )
        }
        return null;

    }

    openRecognitionLayer(){
        this.openRecognitionLayerPopup.open();
    }

    openAIUMemberLayer(){
        this.openAIUMemberLayerPopup.open();
    }
    openCourseStatusLayer(){
        this.openCourseStatusLayerPopup.open();
    }
    openUniversityTypeLayer(){
        this.openUniversityTypeLayerPopup.open();
    }
    openAffiliationLayer(){
        this.trackEvent('Header','click_moreAffiliation');
        this.openAffiliationLayerPopup.open();
    }
    openRankingLayer(){
        this.trackEvent('Header','click_moreRanks');
        this.openRankingLayerPopup.open();
    }

    openAccreditationLayer(){
        this.openAccreditationLayerPopup.open();
    }

    openNationalInstituteLayer(){
        this.openNationalInstituteLayerPopup.open();
    }

    generateInstituteToolTipData = id => {
        const {data , isAmp} = this.props;
        return (
            <React.Fragment>
                {(!isAmp) ?
                    <p>
                        {data.instituteToolTipData[id].helptext}
                    </p>:
                    <p className="pad10 color-3 l-18 f12">{data.instituteToolTipData[id].helptext}</p>
                }
            </React.Fragment>
        );
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
        Analytics.event({category : category, action : actionLabel, label : label});
    }



    renderRankingHtml(){

        const { data, isAmp} = this.props;
        var rankingData = typeof data.instituteTopCardData.rankingData != 'undefined' && Object.keys(data.instituteTopCardData.rankingData).length > 0 ? data.instituteTopCardData.rankingData : [];

        if(rankingData.length == 0)
            return;

        var rank =  rankingData[0];

        return (
            <React.Fragment>
                {(!isAmp)?
                    <li key="rank">
                        <p><strong>Ranked {rank.displayableRank}</strong> for <Link to={removeDomainFromUrl(rank.url)}>{rank.rankingPageText}</Link>  by {rank.publisherName} {rank.year} {rankingData.length > 1 && <a className='clp-mrLink more-btn' href="javascript:void(0)" onClick={this.openRankingLayer}> +{(parseInt(rankingData.length)-1)} more</a>
                        }</p>
                        {rankingData.length > 1 && <PopupLayer onRef={ref => (this.openRankingLayerPopup = ref)} data={this.generateRankingLayer()} heading="College Ranking"/>}
                    </li>:
                    <div key="amp_rank" className="f12 color-3 font-w6 m-5top">
                        <b>Ranked {rank.displayableRank}</b> for <a href={rank.url}>{rank.rankingPageText}</a> by {rank.publisherName} {rank.year} {rankingData.length > 1 && <a className='block color-b f12 font-w6' on="tap:amp-more-data"> +{(parseInt(rankingData.length)-1)} more</a>}
                        {rankingData.length > 1 && <AmpLightBox id="amp-more-data" onRef={ref => (this.openRankingLayer = ref)} data={this.generateRankingLayer()} heading="College Ranking"/>}
                    </div>
                }
            </React.Fragment>
        )

    }


    generateRankingLayer()
    {
        const {data, isAmp} = this.props;
        var rankingData = typeof data.instituteTopCardData.rankingData != 'undefined' && Object.keys(data.instituteTopCardData.rankingData).length > 0 ? data.instituteTopCardData.rankingData : [];
        return (
            <React.Fragment>
                {(!isAmp) ?
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
                    </div>:
                    <div className="pad10">
                        <ul>
                            {this.generateRankingLayerList(rankingData)}
                        </ul>
                    </div>
                }
            </React.Fragment>
        )
    }

    generateRankingLayerList(keyData){
        let isAmp = this.props.isAmp;
        var list = new Array();
        for(var i in keyData){
            if(i == 0 ) continue;
            var rank = keyData[i];
            if(keyData[i].publisherName){
                if(isAmp){
                    list.push(<li key={i}>Ranked {rank.displayableRank} for <Link className='lyr-link' to={removeDomainFromUrl(rank.url)} >{rank.rankingPageText}</Link>  by {rank.publisherName} {rank.year} </li>);
                } else{
                    list.push(<li key={i}>Ranked {rank.displayableRank} for <a className='lyr-link' href={rank.url}>{rank.rankingPageText}</a>  by {rank.publisherName} {rank.year} </li>);
                }
            }
        }
        return list;
    }

    renderAffiliatedHtml(){
        const {data} = this.props;
        let isAmp = this.props.isAmp;
        if(data.instituteTopCardData.affiliationData == null){
            if(data.instituteTopCardData.parentUniversityData == null){
                return;
            }
            else{
                if(data.instituteTopCardData.parentUniversityData.name){
                    return(
                        <React.Fragment>
                            { (!isAmp) ?
                                <li>
                                    <p>
                                        College of <Link to={data.instituteTopCardData.parentUniversityData.url} className="clp-mrLink" onClick={this.handleClickOnInstitute.bind(this,data.instituteTopCardData.parentUniversityData.name,data.instituteTopCardData.parentUniversityData.id)}>{data.instituteTopCardData.parentUniversityData.name}</Link></p>
                                </li>:
                                <div className="f12 color-3 font-w6 m-5top">
                                    <b>Affliated to</b> <a href={data.instituteTopCardData.parentUniversityData.url}>{data.instituteTopCardData.parentUniversityData.name}</a>
                                </div>
                            }
                        </React.Fragment>
                    )
                }
                else{
                    return null;
                }


            }
        }
        else{
            var affiliationData = data.instituteTopCardData.affiliationData[0];
            return (
                <React.Fragment>
                    {(!isAmp) ?
                        <li key='affiliation'>
                            <p> College of
                                { typeof affiliationData.type !='undefined' && affiliationData.type == 'abroad' ?
                                    (<a className = 'clp-mrLink mg-2' href ={affiliationData.url} > {affiliationData.name} </a>) :
                                    (<Link className="clp-mrLink mg-2" to={affiliationData.url}
                                           onClick={this.handleClickOnInstitute.bind(this,affiliationData.name,affiliationData.id)}>
                                        {' '+affiliationData.name} </Link>)
                                }
                                {data.instituteTopCardData.affiliationData.length > 1 && <a className='clp-mrLink more-btn' href="javascript:void(0)" onClick={this.openAffiliationLayer}>{' +'}{(parseInt(data.instituteTopCardData.affiliationData.length)-1)} more</a>}
                            </p>
                            {data.instituteTopCardData.affiliationData.length>1 && <PopupLayer onRef={ref => (this.openAffiliationLayerPopup = ref)} data={this.generateAffiliationLayer()} heading="Affliation College"/>}
                        </li>:
                        <div className="f12 color-3 font-w6 m-5top">
                            <b>Affliated to</b> <a href={affiliationData.url}>{affiliationData.name }</a>
                            {data.instituteTopCardData.affiliationData.length > 1 && <a className='block color-b f12 font-w6' on="tap:more-data-links" role="button" tabIndex="0">{' +'}{(parseInt(data.instituteTopCardData.affiliationData.length)-1)} more</a>}
                            {data.instituteTopCardData.affiliationData.length>1 && <AmpLightBox id="more-data-links" onRef={ref => (this.openAffiliationLayer = ref)} data={this.generateAffiliationLayer()} heading="Affliation College"/>}
                        </div>
                    }
                </React.Fragment>
            )
        }
    }

    generateAffiliationLayer()
    {
        const {data, isAmp} = this.props;
        var affiliationData = typeof data.instituteTopCardData.affiliationData != 'undefined' && Object.keys(data.instituteTopCardData.affiliationData).length > 0 ? data.instituteTopCardData.affiliationData : [];
        return (
            <React.Fragment>
                {(!isAmp) ?
                    <div className="glry-div">
                        <div className="hlp-info">
                            <div className="loc-list-col">
                                <div className='prm-lst'>
                                    <div className='amen-box'>
                                        <ul className='n-more-ul rnk-pb5'>
                                            {this.generateAffiliationLayerList(affiliationData)}
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>:
                    <React.Fragment>

                        <ul>
                            {this.generateAffiliationLayerList(affiliationData)}
                        </ul>

                    </React.Fragment>
                }
            </React.Fragment>
        )

    }

    generateAffiliationLayerList(keyData){
        let isAmp = this.props.isAmp;
        var list = new Array();
        var self = this;
        for(var i in keyData){
            if(i == 0 ) continue;
            if(isAmp){
                if(typeof keyData[i].type !='undefined' && keyData[i].type == 'abroad'){
                    list.push(<li key={'Affiliation_'+i}><p>
                        College of <a className="lyr-link clp-mrLink" href={keyData[i].url}>{keyData[i].name}  </a></p> </li>);
                }
                else{
                    list.push(<li key={'Affiliation_'+i}><p>
                        College of <Link className="lyr-link clp-mrLink"
                                         to={keyData[i].url}
                                         onClick={self.handleClickOnInstitute.bind(self,keyData[i].name,keyData[i].id)}
                    >{keyData[i].name}  </Link></p> </li>);
                }
            }else{
                list.push(<li key={'Affiliation_'+i}><p>
                    College of <Link className="lyr-link clp-mrLink" to={keyData[i].url}>{keyData[i].name}</Link></p> </li>);
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
        const {config,data,isAmp} = this.props;
        let SHIKSHA_HOME = config.SHIKSHA_HOME;
        var isPaid = true;
        if(typeof data.courseWidget !='undefined' && data.courseWidget && typeof data.courseWidget.instituteHasPaidCourse !='undefined'){
            isPaid = data.courseWidget.instituteHasPaidCourse;
        }

        return(
            <React.Fragment>
                {(!isAmp) ?
                    <section>

                        <div className="_container">
                            <div className="_subcontainer">
                                {this.renderNameInfo()}
                                <div className="clg-detail">
                                    <ul>
                                        {renderColumnStructure(this)}

                                        {this.props.page == 'institute' &&  data.instituteTopCardData.rankingData &&  this.renderRankingHtml()}
                                        {(data.instituteTopCardData.affiliationData || data.instituteTopCardData.parentUniversityData) && this.renderAffiliatedHtml()}
                                    </ul>
                                    <div className='dnld-btn'>
                                        <DownloadEBrochure className="req-padding rippleefect" buttonText="Request Brochure" listingId={data.listingId} listingName={data.instituteTopCardData.instituteName}trackid={this.config.downloadBrochureTrackingIdTopWidget} recoEbTrackid={this.config.downloadBrochureTrackingIdRecoLayer}  recoShrtTrackid={this.config.shortlistTrackingIdRecoLayer} isCallReco={this.props.isCallReco} clickHandler={this.trackEvent.bind(this,'Header','click_Request_Brochure')} page = {this.props.page}/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section> :
                    <div className="data-card m-5btm">
                        <div className="card-cmn color-w">
                            <div className="pos-rl">{this.renderNameInfo()}</div>
                            <div>
                                <ul className="m-5top cli-i">
                                    {renderColumnStructure(this, isAmp)}
                                </ul>
                                {this.props.page == 'institute' &&  data.instituteTopCardData.rankingData &&  this.renderRankingHtml()}
                                {(data.instituteTopCardData.affiliationData || data.instituteTopCardData.parentUniversityData) && this.renderAffiliatedHtml()}
                                {data.aggregateReviewWidget != null && data.reviewWidget != null && data.reviewWidget.reviewData != null && data.reviewWidget.reviewData.reviewsData  &&  data.aggregateReviewWidget.aggregateReviewData &&   <div className="f12 color-3 font-w6 m-5top">
                                    {data.aggregateReviewWidget != null && data.reviewWidget != null && data.reviewWidget.reviewData != null && data.reviewWidget.reviewData.reviewsData  &&  data.aggregateReviewWidget.aggregateReviewData && <AggregateReview isPaid ={isPaid} config={config} uniqueKey= {'institute_'+data.listingId}   reviewType ={data.listingType} reviewsCount={data.reviewWidget.reviewData.totalReviews} reviewUrl = {data.reviewWidget.reviewData.allReviewUrl} aggregateReviewData = {data.aggregateReviewWidget} showAllreviewUrl = {true} isAmp={true}/>}
                                </div> }
                                <a className="ga-analytic" href={SHIKSHA_HOME+"/muser5/UserActivityAMP/getResponseAmpPage?listingType="+this.props.page+"&listingId="+data.listingId+"&actionType=shortlist&fromwhere="+this.props.page} data-vars-event-name="<?=$GA_Tap_On_Shortlist;?>">
                                    <p className="btn btn-primary color-o color-f f14 font-w7 m-15top" tabIndex="0" role="button">Shortlist</p>
                                </a>
                            </div>
                        </div>
                    </div>
                }
            </React.Fragment>
        )
    }

}

function mapDispatchToProps(dispatch){
    return bindActionCreators({storeInstituteDataForPreFilled }, dispatch);
}

TopWidget.defaultProps = {
    isAmp: false
}
export default connect(null,mapDispatchToProps)(TopWidget);

TopWidget.propTypes = {
  allCoursePage: PropTypes.any,
  config: PropTypes.any,
  data: PropTypes.any,
  extraHeading: PropTypes.any,
  fromWhere: PropTypes.any,
  fromwhere: PropTypes.any,
  gaTrackingCategory: PropTypes.any,
  isAmp: PropTypes.bool,
  isCallReco: PropTypes.any,
  page: PropTypes.any,
  showChangeBranch: PropTypes.any,
  storeInstituteDataForPreFilled: PropTypes.any
}