import PropTypes from 'prop-types'
import React from 'react';
import TopWidget from './TopWidgetCommon';
import TopWidgetSticky from './../../institute/components/TopWidgetSticky';
import KnowledgeBox from './KnowledgeBoxComponent';
import CutOffWidget from './CutOffWidgetComponent';
import CoursesOffered from './CoursesOfferedComponent';
import CollegeListComponent from './CollegeListComponent';
import Events from './EventsComponent';
import Articles from './ArticlesComponent';
import Facilities from './FacilitiesComponent';
import './../assets/css/style.css';
import Highlights from './../../course/components/HighlightsComponent';
import ReviewWidget from './../../course/components/ReviewWidget';
import OtherTopics from './../../courseHomePage/components/OtherTopics';
import AnA from './../../course/components/AnAComponent';
import BottomInstituteSticky from './BottomInstituteSticky';
import './../../course/assets/courseCommon.css';
import './../../categoryList/assets/categoryTuple.css';
import { connect } from 'react-redux';
import Gallery from './../../course/components/GalleryComponent';
import CourseCategoryPageLink from './../../course/components/CourseCategoryPageLinkingComponent';
import Carousel from '../../../common/components/Carousel';
import ContactDetails from './../../course/components/ContactDetailsComponent';
import SectionalNav from './../../course/components/SectionalNavWidget';
import { bindActionCreators } from 'redux';
import {fetchInstituteDetailPageData,storeInstituteDataForPreFilled} from './../actions/InstituteDetailAction';
import PreventScrolling from './../../../reusable/utils/PreventScrolling';
import ContentLoader from './../../instituteChildPages/utils/contentLoader';
import Scholarship from './ScholarshipComponent';
import {getInstituteTrackingParams} from './../../course/utils/listingCommonUtil';
import TagManager  from './../../../reusable/utils/loadGTM';
import ResponseForm from './../../../user/Response/components/ResponseForm';
import { showResponseForm } from './../../../user/Response/actions/ResponseFormAction';
import {getQueryVariable,getObjectSize,isUserLoggedIn,makeTopWidgetSticky} from './../../../../utils/commonHelper';
import ElasticSearchTracking from './../../../reusable/utils/ElasticSearchTracking';
import BeaconIndexTracking from './../../../reusable/utils/BeaconTracking';
import NotFound from './../../../common/components/NotFound';
import  { Redirect } from 'react-router-dom';
import AdmissionWidget from './AdmissionComponent';
import {dfpBannerConfig,clearDfpBannerConfig} from './../../../reusable/actions/commonAction';
import {getRequest} from './../../../../utils/ApiCalls';
import {DFPBannerTempalte} from './../../../reusable/components/DFPBannerAd';
import ClientSeo from './../../../common/components/ClientSeo';

class InstituteDetailPageDesktop extends React.Component
{
    constructor(props)
    {
        super(props);
        this.state = {
            isShowLoader : false
        }
        this.isDfpData = false;
    }
    componentDidMount(){
        const {instituteData,match} = this.props;



        if(!this.isServerSideRenderedHTML())
        {
            this.initialFetchData();
        }
        else
        {
            if(!this.isErrorPage())
            {
                this.trackGTM();
                //this.fetchResponseFormData(currentinstituteId);
            }

            if(instituteData != null && !this.isErrorPage()){
                //getAlsoViewedAndSimilarInstitutes(instituteData.alsoViewedInstitutes, instituteData.similarInstitutes, currentinstituteId);
                if(instituteData.courseWidget && instituteData.courseWidget.allCourses){
                    var courseIds = [];
                    if(instituteData.courseWidget.popularCourseList){
                        for(let i in instituteData.courseWidget.popularCourseList){
                            courseIds.push(instituteData.courseWidget.popularCourseList[i]);       
                        }
                    }
                    if(instituteData.courseWidget.featuredCourseList){
                        for(let i in instituteData.courseWidget.featuredCourseList){
                            courseIds.push(instituteData.courseWidget.featuredCourseList[i]);       
                        }
                    }
                    if(courseIds.length>0){
                        //preCacheCourseData(courseIds);
                    }
                }
                if(instituteData.collegeWidget && instituteData.collegeWidget.collegeWidgetData && instituteData.collegeWidget.collegeWidgetData.topInstituteOrder){
                    //preCacheFirstCollegeList(instituteData.collegeWidget.collegeWidgetData.topInstituteOrder[0]);
                }                 

            }
            
        }
        const instituteId = match.params.listingId;
        this.instituteDigestCall(instituteId);
        window.addEventListener("scroll", this.onScroll);
    }


    instituteDigestCall(instituteId){
        if(isUserLoggedIn()){
            var url = this.props.config.SHIKSHA_HOME+"/nationalInstitute/InstituteDetailPage/sendInstituteDigest/";
            if(!isNaN(instituteId)){
                url += instituteId;
                getRequest(url);
            }
        }

    }

    componentWillUnmount()
    {
        window.scroll = null;
        PreventScrolling.enableScrolling(false,true);
        if(PreventScrolling.canUseDOM())
        {
            document.getElementById('page-header').style.display = "table";
            document.getElementById('page-header').style.position = "relative";
        }
        this.props.clearDfpBannerConfig();
    window.removeEventListener('scroll', this.onScroll);
    }

    isErrorPage()
    {
        let html404 = document.getElementById('notFound-Page');
        return (html404 && html404.innerHTML);
    }

    isServerSideRenderedHTML()
    {
        let htmlNode = document.getElementById('ILP');
        return ((htmlNode && htmlNode.innerHTML) || this.isErrorPage());
    }
    onScroll = () => {
        if(!this.isDfpData){
            this.isDfpData = true;
            this.getDFPData();
        }
        makeTopWidgetSticky();
    };
    componentWillReceiveProps(nextProps)
    {
        if(PreventScrolling.canUseDOM()) {
             document.getElementById('page-header').style.display = "block";
             document.getElementById('page-header').style.position = "relative";
         }
        let newinstituteId = nextProps.match.params.listingId;
        let previnstituteId = this.props.match.params.listingId;

        let nextCityId = 0;
        let nextLocalityId = 0;
        let prevCityId = 0;
        let prevLocalotyId = 0;
        if(typeof nextProps.location.search != 'undefined' && nextProps.location.search != '')
        {
            //let queryParams =  new URLSearchParams(nextProps.location.search);
            nextCityId = getQueryVariable('city', nextProps.location.search);//queryParams.get('city');
            nextLocalityId = getQueryVariable('locality', nextProps.location.search);//queryParams.get('locality');
        }
        if(typeof this.props.location.search != 'undefined' && this.props.location.search != '')
        {
            //let queryParams =  new URLSearchParams(this.props.location.search);
            prevCityId = getQueryVariable('city', this.props.location.search);//queryParams.get('city');
            prevLocalotyId = getQueryVariable('locality', this.props.location.search);//queryParams.get('locality');
        }
        if(newinstituteId != previnstituteId || nextLocalityId != prevLocalotyId || nextCityId != prevCityId)
        {
            this.fetchInstituteData(newinstituteId,nextCityId,nextLocalityId);
            this.instituteDigestCall(newinstituteId);
            //this.fetchResponseFormData(newinstituteId);
        }
        const {catpageInstitute} = nextProps;
        this.clearReduxCategoryinstituteData(newinstituteId,catpageInstitute);

    }

    clearReduxCategoryinstituteData(instituteId,catpageInstitute)
    {
        if(typeof catpageInstitute != 'undefined' && getObjectSize(catpageInstitute) > 0 && instituteId != catpageInstitute.instituteId)
        {
            this.props.storeInstituteDataForPreFilled();
        }
    }


    initialFetchData()
    {
        let instituteId = this.props.match.params.listingId;
        if(isNaN(instituteId))
            return;
        let cityId = 0;
        let localityId = 0;
        //let queryParams = new URLSearchParams(this.props.location.search);
        cityId = getQueryVariable('city', this.props.location.search);//queryParams.get('city');
        localityId = getQueryVariable('locality', this.props.location.search);//queryParams.get('locality');
        this.fetchInstituteData(instituteId,cityId,localityId);
        //this.fetchResponseFormData(instituteId);
    }

    fetchInstituteData(instituteId,cityId,localityId)
    {

        var queryParams = 'instituteId='+instituteId;
        if(cityId != 0 && cityId != '' && cityId != null)
        {
            queryParams += '&cityId='+cityId;
        }
        if(localityId != 0 && localityId != '' && localityId != null)
        {
            queryParams += '&localityId='+localityId;
        }

        this.setState({isShowLoader : true});
        var fetchPromise = this.props.fetchInstituteDetailPageData(queryParams);
        var self = this;
        fetchPromise.then(function() {
        self.setState({isShowLoader : false});
        self.trackGTM();
        if(self.props.instituteData != null && !self.isErrorPage()){
                //getAlsoViewedAndSimilarInstitutes(self.props.instituteData.alsoViewedInstitutes, self.props.instituteData.similarInstitutes);
                if(self.props.instituteData.courseWidget && self.props.instituteData.courseWidget.allCourses){
                    var courseIds = [];
                    if(self.props.instituteData.courseWidget.popularCourseList){
                        for(let i in self.props.instituteData.courseWidget.popularCourseList){
                            courseIds.push(self.props.instituteData.courseWidget.popularCourseList[i]);       
                        }
                    }
                    if(self.props.instituteData.courseWidget.featuredCourseList){
                        for(let i in self.props.instituteData.courseWidget.featuredCourseList){
                            courseIds.push(self.props.instituteData.courseWidget.featuredCourseList[i]);       
                        }
                    }
                    if(courseIds.length>0){
                        //preCacheCourseData(courseIds);
                    }
                }
                if(self.props.instituteData.collegeWidget && self.props.instituteData.collegeWidget.collegeWidgetData && self.props.instituteData.collegeWidget.collegeWidgetData.topInstituteOrder){
                    //preCacheFirstCollegeList(self.props.instituteData.collegeWidget.collegeWidgetData.topInstituteOrder[0]);
                }


        }
           self.getDFPData();
            self.isDfpData = true;
        });
    }
    getDFPData(){
        const {instituteData} = this.props;
        var dfpPostParams = 'parentPage=DFP_InstituteDetailPage';
        if(typeof instituteData !='undefined' &&  instituteData.listingType && instituteData.listingType =='university'){
            dfpPostParams = 'parentPage=DFP_UniversityListingPage';
        }
        if(instituteData != null && typeof instituteData != 'undefined' && typeof instituteData.currentLocation != 'undefined' && instituteData.currentLocation)
        {
            dfpPostParams +='&city=' +instituteData.currentLocation.city_id+'&state=' +instituteData.currentLocation.state_id+'&entity_id='+this.props.match.params.listingId;
        }
        this.props.dfpBannerConfig(dfpPostParams);
    }
    fetchResponseFormData(instituteId)
    {
        var params    = 'clientCourseId='+instituteId+'&listingType=institute';
        showResponseForm(params);
    }



    renderLoader() {
        PreventScrolling.enableScrolling(true);
        // if(PreventScrolling.canUseDOM())
        // {
        //     document.getElementById('page-header').style.display = "block";
        //     document.getElementById('page-header').style.position = "relative";
        // }

    return <ContentLoader instituteId={this.props.match.params.listingId} showFullLoader = {0} />;
    }

    createSectionalNavData(){
        const {instituteData} = this.props;
        var category = 'ILP';
        if(instituteData.listingType == "university"){
            category = 'ULP';
        }
        var sectionalNavObj = {};
        var sectionalNavCount = {};
        var sectionalGaObj = {};
                    if(this.props.instituteData.collegeWidget != null && this.props.instituteData.collegeWidget.collegeWidgetData != null && this.props.instituteData.collegeWidget.collegeWidgetData.instituteCount != null && this.props.instituteData.collegeWidget.collegeWidgetData.instituteCount > 0){
                        sectionalNavCount['Colleges'] = this.props.instituteData.collegeWidget.collegeWidgetData.instituteCount;
                        sectionalNavObj['Colleges'] = 'Colleges';
                        sectionalGaObj['Colleges'] = {category : category,action : 'NavColleges',label : 'NavBar'};
                    }
                    if(this.props.instituteData.courseWidget != null && this.props.instituteData.courseWidget.totalCourseCount != null && this.props.instituteData.courseWidget.totalCourseCount > 0 ){
                      sectionalNavCount['Courses'] = this.props.instituteData.courseWidget.totalCourseCount;
                      sectionalNavObj['Courses'] = 'Courses & Fees';
                      sectionalGaObj['Courses'] = {category : category,action : 'NavCourses',label : 'NavBar'};
                    }

                    if(instituteData.reviewWidget != null && instituteData.reviewWidget.reviewData != null && instituteData.reviewWidget.reviewData.reviewsData != null){
                        sectionalNavCount['review'] = instituteData.reviewWidget.reviewData.totalReviews;
                        sectionalNavObj['review'] = 'Reviews'
                        sectionalGaObj['review'] = {category : category,action : 'NavReviews',label : 'NavBar'};
                    }

                    if(instituteData.adminssionData !=null && (instituteData.adminssionData.admissionDetails || (instituteData.adminssionData.examList && instituteData.adminssionData.examList.length>0)  || instituteData.adminssionData.showAdmissionFlag ==true )){
                        sectionalNavObj['admission'] = 'Admissions & Cut-Offs';
                        sectionalGaObj['admission'] = {category : category,action : 'NavAdmissions',label : 'NavBar'};
                    }

                    if(instituteData.highlightsInfo && instituteData.highlightsInfo.highlights && instituteData.highlightsInfo.highlights.length > 0){
                        sectionalNavObj['highlights'] = 'Highlights';
                        sectionalGaObj['highlights'] = {category : category,action : 'NavHigh  lights',label : 'NavBar'};

                    }

                    if(instituteData.facilityInfo  && instituteData.facilityInfo.length > 0){
                        sectionalNavObj['Facilities'] = 'Facilities';
                        sectionalGaObj['Facilities'] = {category : category,action : 'NavFacilities',label : 'NavBar'};
                    }

                    if(instituteData.anaWidget != null && typeof instituteData.anaWidget.questionsDetail != 'undefined' && instituteData.anaWidget.questionsDetail!= null) {
                        sectionalNavCount['ana'] = instituteData.anaWidget != null ? instituteData.anaWidget.totalNumber : 0;
                        sectionalNavObj['ana'] = 'Q&A';
                        sectionalGaObj['ana'] = {category : category,action : 'NavQandA',label : 'NavBar'};
                    }
                    if(instituteData.media!= null){
                        sectionalNavObj['gallery'] = 'Gallery';
                        let totalImages = 0;
                        let totalVideos = 0;
                        let photos = typeof instituteData.media['photos'] != 'undefined' ?instituteData.media['photos'] : {};
                        let photoSections = typeof instituteData.media['photoSections'] != 'undefined' ? instituteData.media['photoSections'] : [];
                        for(let i in photoSections)
                        {
                            totalImages += photos[photoSections[i]].length;
                        }
                        let videos = typeof instituteData.media['videos'] != 'undefined' && instituteData.media['videos'].length > 0 ? instituteData.media['videos'] : [];

                        if(typeof videos != 'undefined' && Array.isArray(videos) && videos.length > 0)
                        {
                            totalVideos = videos.length;
                        }
                        sectionalNavCount['gallery'] = totalImages + totalVideos;
                        sectionalGaObj['gallery'] = {category : category,action : 'NavGallery',label : 'NavBar'};
                    }
                    if(instituteData.scholarshipInfo && (instituteData.scholarshipInfo.scholarShipDetails!='' && instituteData.scholarshipInfo.scholarShipDetails!=null)){
                        sectionalNavObj['scholarships'] = 'Scholarships';
                        sectionalGaObj['scholarships'] = {category : category,action : 'NavPartner',label : 'NavBar'};
                    }

                    if(instituteData.collegeCutOffWidget && instituteData.collegeCutOffWidget.previewText && instituteData.collegeCutOffWidget.cutOffUrl){
                        sectionalNavObj['Cut-Offs'] = 'Cut-Offs';
                        sectionalGaObj['Cut-Offs'] = {category : category,action : 'NavCut-Offs',label : 'NavBar'};
                    }
                    if((instituteData.eventInfo!=null && instituteData.eventInfo.eventsDetails && instituteData.eventInfo.eventsDetails.length>0 )){
                        sectionalNavObj['Events'] = 'Events'
                        sectionalGaObj['Events'] = {category : category,action : 'NavEvents',label : 'NavBar'};
                    }
                    if((instituteData.articleRecommendations && instituteData.articleRecommendations.articleData.articleDetails.length>0 )){
                        sectionalNavObj['Articles'] = 'Articles'
                        sectionalGaObj['Articles'] = {category : category,action : 'NavArticles',label : 'NavBar'};
                    }

                    if(instituteData.currentLocation != 'undefined' && instituteData.currentLocation.contact_details != null) {
                        let contactDetails = instituteData.currentLocation.contact_details;
                        if( contactDetails['address'] != null || contactDetails['admission_contact_number'] != null || contactDetails['website_url'] != null  || contactDetails['generic_contact_number'] != null)
                        {
                            sectionalNavObj['contact'] = 'Contact';
                            sectionalGaObj['contact'] = {category : category,action : 'NavContact',label : 'NavBar'};
                        }
                    }

        return  {sectionalNavObj : sectionalNavObj,sectionalNavCount : sectionalNavCount,sectionalGaObj : sectionalGaObj};
    }
    trackGTM()
    {
        const {instituteData,config} = this.props;
        var trackingParams;

        var beaconData = {};
        if(typeof instituteData != 'undefined' && instituteData)
        {
            trackingParams = getInstituteTrackingParams(instituteData);
            TagManager.dataLayer({dataLayer : trackingParams.gtmParams, dataLayerName : 'dataLayer'});
            ElasticSearchTracking(trackingParams.beaconTrackData,config.BEACON_TRACK_URL);
            beaconData['listing_type'] = 'institute';
            beaconData['listing_id'] = instituteData.listingId;
            beaconData['product_Id'] = config.CLP_PRODUCT_ID;
            BeaconIndexTracking(beaconData, config.BEACON_INDEX_TRACK_URL);
        }
    }
    render()
    {
        const {instituteData,match,config} = this.props;
        const listingType = instituteData.listingType;

        if(this.state.isShowLoader)
        {
            return (
                <React.Fragment>
                    {this.renderLoader()}
                </React.Fragment>

                );
        }
        if(isNaN(match.params.listingId) || (instituteData && typeof instituteData.statusCode != 'undefined' && instituteData.statusCode == 404) || instituteData.listingId === 0 )
        {
            return <NotFound />;
        }
        else if(instituteData == null || Object.keys(instituteData).length == 0)
        {

            return (
                <React.Fragment>
                    {this.renderLoader()}
                </React.Fragment>

                );
        }
        else if(instituteData.listingId && instituteData.listingId != match.params.listingId)
        {
            return (
                <React.Fragment>

                    {this.renderLoader()}
                </React.Fragment>

                );
        }
        else if(instituteData.statusCode == 301 || instituteData.statusCode == 302)
        {
            <Redirect to={instituteData.redirectUrl}/>
        }
        let sectionalNavData = this.createSectionalNavData();
        PreventScrolling.enableScrolling(true);
        var instituteName = (instituteData.listingName)?((instituteData.listingName).split(','))[0]:null;
         var category = 'ILP';
        if(instituteData.listingType == "university"){
            category = 'ULP';
        }
        var isDesktop = false;
        if(this.props.hocData == 'institutePageDesktop'){
            isDesktop = true;
        }
        let seoData = (instituteData && instituteData.seoData) ? instituteData.seoData : '';
        return (
            <React.Fragment>
            {ClientSeo(seoData)}
               {instituteData.instituteTopCardData && <TopWidgetSticky showChangeBranch={true} instituteId={instituteData.listingId} data={instituteData} config={config} location = {this.props.location} page = {listingType} fromwhere= "institutePage" gaTrackingCategory={category} isDesktop={true} />}
                <div className="ilup courseChildPage pwa_admission" id="iulp">
                    <div className="pwa_pagecontent" id="ctp_pwa">
                        <div id="fixed-card" className="nav-tabs display-none">
                            <SectionalNav sectionalNavObj={sectionalNavData['sectionalNavObj']} sectionalNavCount={sectionalNavData['sectionalNavCount']} sectionalGaObj={sectionalNavData['sectionalGaObj']}  />
                        </div>
                        <section className="pwa_container">
                            <div className="ctp_block">
                               <div className="ilp" id="ILP">
                                   {instituteData.instituteTopCardData && <TopWidget showChangeBranch={true} instituteId={instituteData.listingId} data={instituteData} config={config} location = {this.props.location} page = {listingType} fromwhere= "institutePage" gaTrackingCategory={category} isDesktop={true} />}
                                   <div id="tab-section" className='nav-tabs'>
                                       <SectionalNav sectionalNavObj={sectionalNavData['sectionalNavObj']} sectionalNavCount={sectionalNavData['sectionalNavCount']} sectionalGaObj={sectionalNavData['sectionalGaObj']} beforeStickyCTA = {true} />
                                   </div>
                                  <div className="pwa_leftCol"> 
                                   {instituteData.instituteTopCardData && <KnowledgeBox showChangeBranch={true} instituteId={instituteData.listingId} data={instituteData} config={config} location = {this.props.location} page = {listingType} fromwhere= "institutePage" gaTrackingCategory={category} isDesktop={true}/>}
                                   {instituteData.collegeWidget != null && instituteData.collegeWidget.collegeWidgetData !=null && instituteData.collegeWidget.collegeWidgetData.topInstituteData !=null && <CollegeListComponent collegelistWidget={instituteData.collegeWidget} collegeName={instituteData.listingName} listingId={instituteData.listingId} config={config} page = {listingType}/>}
                                   {instituteData.courseWidget != null && <CoursesOffered instituteData={instituteData} courseWidget={instituteData.courseWidget} page = {listingType} config={config} location={instituteData.currentLocation} fromwhere= "institutePage"/>}

                                   <DFPBannerTempalte bannerPlace="Client"/>
                                   <DFPBannerTempalte bannerPlace="LAA"/>
                                   <DFPBannerTempalte bannerPlace="LAA1"/>

                                   {instituteData.reviewWidget != null && instituteData.reviewWidget.reviewData != null && instituteData.reviewWidget.reviewData.reviewsData &&
                                       <ReviewWidget
                                       reviewWidgetData={instituteData.reviewWidget}
                                       config={config}
                                        aggregateReviewWidgetData = {instituteData.aggregateReviewWidget} gaTrackingCategory={category} />}

                                   {instituteData.adminssionData !=null && (instituteData.adminssionData.admissionDetails || (instituteData.adminssionData.examList && instituteData.adminssionData.examList.length>0)  || instituteData.adminssionData.showAdmissionFlag ==true ) &&

                                   <AdmissionWidget instituteData={instituteData} admissionData ={instituteData.adminssionData} instituteName = {instituteName}config={config} page = {listingType}  fromWhere='instiutePage' />}


                                   <DFPBannerTempalte bannerPlace="AON"/>
                                   <DFPBannerTempalte bannerPlace="AON1"/>

                                   { instituteData.highlightsInfo && instituteData.highlightsInfo.highlights && instituteData.highlightsInfo.highlights.length > 0 && <Highlights data={instituteData.highlightsInfo.highlights} allHighlightsPageUrl = {instituteData.highlightsInfo.allHighlightsPageUrl} page = {listingType} />}

                                   { instituteData.facilityInfo  && instituteData.facilityInfo.length > 0 && <Facilities data={instituteData.facilityInfo} page = {listingType} isDesktop={isDesktop} />}

                                 

                                   <AnA anaWidget={instituteData.anaWidget} config={config} courseId={instituteData.courseId} instituteId={instituteData.listingId} page = {listingType} location = {instituteData.currentLocation}/>

                                   

                                   {instituteData.alsoViewedInstitutes && instituteData.alsoViewedInstitutes.length>0 && <Carousel heading="Students who viewed this institutes also Viewed the following" data={instituteData.alsoViewedInstitutes} page ={listingType} section="alsoviewed"/>}

                                   { instituteData.scholarshipInfo && (instituteData.scholarshipInfo.scholarShipDetails!='' && instituteData.scholarshipInfo.scholarShipDetails!=null) && <Scholarship instituteName={instituteData.listingName} scholarship={instituteData.scholarshipInfo} config={config} page = {listingType}/>}

                                   {(instituteData.collegeCutOffWidget) && <CutOffWidget instituteName={instituteData.listingName} data={instituteData.collegeCutOffWidget} config={config} page = {listingType}/>}

                                   {(instituteData.eventInfo!=null && instituteData.eventInfo.eventsDetails && instituteData.eventInfo.eventsDetails.length>0 ) && <Events data={instituteData.eventInfo} page = {listingType}/>}

                                   {(instituteData.articleRecommendations && instituteData.articleRecommendations.articleData.articleDetails.length>0 ) && <Articles config={config} instituteName={instituteData.listingName} data ={instituteData.articleRecommendations} page = {listingType} />}

                                   {(instituteData.similarInstitutes  && instituteData.similarInstitutes.length>0 )&& <Carousel heading={"Other "+instituteName+" Institutes"} data={instituteData.similarInstitutes} page ={listingType} section="similar"/>}
                                   { instituteData.currentLocation && instituteData.currentLocation != 'undefined' && instituteData.currentLocation.contact_details != null && <ContactDetails data={instituteData} config={config} location={this.props.location} page = {listingType}/>}
                                    
                                </div>
                                <div className="pwa_rightCol">
                                  {(instituteData.media!= null) && <Gallery data={instituteData.media} name = {instituteData.listingName}/>}  
                                     {(typeof instituteData != 'undefined' && typeof instituteData.relatedCHP != 'undefined' && instituteData.relatedCHP.length>0)?
                                       <OtherTopics relatedData={instituteData.relatedCHP} key={19} config={config} isPdfCall={false}/>:''
                                   }
                                </div>
                                <div className="pwacolumn">
                                   {((instituteData.rankingInterlinking!=null && instituteData.rankingInterlinking.length>0)|| (instituteData.categoryPageLinks!=null && instituteData.categoryPageLinks.length>0)) && <CourseCategoryPageLink rankingInterlinking={instituteData.rankingInterlinking} categoryInterlinking={instituteData.categoryPageLinks} config={config} page = {listingType}/>}
                     
                                </div>
                               </div>
                                {/*right side tuples ends*/}
                            </div>
                           { <BottomInstituteSticky listingId={instituteData.listingId} listingName={instituteName} page = {listingType} config={config}/>}
                           {(instituteData.courseWidget) && <ResponseForm viewedResponse={true} clientCourseId={instituteData.courseWidget.flagshipCourseId} listingType={"course"} trackingKeyId={1102} actionType={"MOB_Institute_Viewed"} />}

                        </section>
                        {/*main page ends*/}
                    </div>
                </div>
            </React.Fragment>

            )
    }
}
function mapStateToProps(state)
{
    return {
        instituteData : state.instituteData,
        config : state.config,
        catpageInstitute : state.catpageInstitute,
        userDetails : state.userDetails
    }
}
function mapDispatchToProps(dispatch){
    return bindActionCreators({ fetchInstituteDetailPageData,storeInstituteDataForPreFilled,dfpBannerConfig,clearDfpBannerConfig }, dispatch);
}

export default connect(mapStateToProps,mapDispatchToProps)(InstituteDetailPageDesktop);

InstituteDetailPageDesktop.propTypes = {
  catpageInstitute: PropTypes.any,
  clearDfpBannerConfig: PropTypes.any,
  config: PropTypes.any,
  dfpBannerConfig: PropTypes.any,
  fetchInstituteDetailPageData: PropTypes.any,
  hocData: PropTypes.any,
  instituteData: PropTypes.any,
  location: PropTypes.any,
  match: PropTypes.any,
  storeInstituteDataForPreFilled: PropTypes.any,
  userDetails: PropTypes.any
}