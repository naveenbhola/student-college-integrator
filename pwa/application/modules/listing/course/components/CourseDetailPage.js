import PropTypes from 'prop-types'
import React from 'react';
import TopWidget from './TopWidgetComponent';
import Highlights from './HighlightsComponent';
import Eligibility from './EligibilityComponent';
import AdmissionProcess from './AdmissionProcess';
import Fees from './FeesComponent';
import CourseStructure from './CourseStructure';
import SeatsBreakup from './SeatsComponent';
import PartnerColleges from './PartnerCollegesComponent';
import ReviewWidget from './ReviewWidget'
import OtherTopics from './../../courseHomePage/components/OtherTopics';
import AnA from './AnAComponent';
import BottomCourseSticky from './BottomCourseSticky';
import './../assets/courseCommon.css';
import {MPTMailerTrakingMapping} from './../config/courseConfig.js';
import { connect } from 'react-redux';
import Gallery from './GalleryComponent';
import CourseCategoryPageLink from './CourseCategoryPageLinkingComponent';
import Carousel from '../../../common/components/Carousel';
import ContactDetails from './ContactDetailsComponent';
import SectionalNav from './SectionalNavWidget';
import { bindActionCreators } from 'redux';
import {fetchCourseDetailPageData,storeCourseDataForPreFilled} from './../actions/CourseDetailAction';
import PreventScrolling from './../../../reusable/utils/PreventScrolling';
import ContentLoader from './../utils/contentLoader';
import Scholarship from './ScholarshipComponent';
import {getCourseTrackingParams} from './../utils/listingCommonUtil';
import TagManager  from './../../../reusable/utils/loadGTM';
import {getQueryVariable, getObjectSize, isUserLoggedIn,showToastMsg,PageLoadToastMsg,getEBCookie} from './../../../../utils/commonHelper';
import ElasticSearchTracking from './../../../reusable/utils/ElasticSearchTracking';
import BeaconIndexTracking from './../../../reusable/utils/BeaconTracking';
import NotFound from './../../../common/components/NotFound';
import  { Redirect } from 'react-router-dom';
import {dfpBannerConfig,clearDfpBannerConfig} from './../../../reusable/actions/commonAction';
import {DFPBannerTempalte} from './../../../reusable/components/DFPBannerAd';
import {viewedResponse} from './../../../user/utils/Response';
import Loadable from 'react-loadable';
import Loading from './../../../reusable/utils/Loader';
import ClientSeo from './../../../common/components/ClientSeo';
import CoursesOffered from './../../institute/components/CoursesOfferedComponent';
import {courseWidget} from './mockData2';
import Feedback from "../../../common/components/feedback/Feedback";

const Placement = Loadable({
    loader: () => import('./PlacementComponent'/* webpackChunkName: 'PlacementComponent' */),
    loading() {return null},
});

// const Alumni = Loadable({
//     loader: () => import('./AlumniComponent'/* webpackChunkName: 'Alumni' */),
//     loading() {return null},
// });

class CourseDetailPage extends React.Component
{
    constructor(props)
    {
        super(props);
        this.state = {
            alumniData: '',
            isShowLoader : false,
            isGraphShow : false
        }
        this.isDfpData = false;
        this._scrollCount = true;
        this.showToastMsg = true;
        this.sectionalNavData = {};
    }

    componentDidMount(){
        // var currentCourseId = null;
        if(!this.isServerSideRenderedHTML())
        {
            this.initialFetchData();
        }
        else
        {
            if(!this.isErrorPage()){
                // currentCourseId = this.props.match.params.listingId;
                this.trackGTM();
                // this.fetchResponseFormData(currentCourseId);
                if(typeof(isUserLoggedIn) != 'undefined' && isUserLoggedIn() && this.props.courseData && this.props.courseData.showToastMessage && this.showToastMsg){
                    this.showToastMsg = false;
                    setTimeout(function(){
                        showToastMsg(PageLoadToastMsg('SRM'),5000);
                    },3000);
                }
            }
            this.createViewedResponse();
        }
        const courseId = this.props.match.params.listingId;
        const {catpageCourse} = this.props;
        this.clearReduxCategoryCourseData(courseId,catpageCourse);
        window.addEventListener("scroll", this.onScroll);
        if(typeof(isUserLoggedIn) != 'undefined' && isUserLoggedIn()){
            this.setState({isGraphShow : true});
        }else{
            window.addEventListener("scroll", this.enableGraph);
        }

    }

    enableGraph = () =>{
        if(this._scrollCount){
            this.setState({isGraphShow : true});
            this._scrollCount = false;
        }
    };

    onScroll = () => {
        if(!this.isDfpData){
            this.isDfpData = true;
            this.getDFPData();
        }
    };

    getDFPData(){
        var dfpPostParams = 'parentPage=DFP_CourseDetailPage';
        var {courseData}  = this.props;
        if(courseData != null && typeof courseData != 'undefined' && typeof courseData.currentLocation != 'undefined' && courseData.currentLocation){
            dfpPostParams +='&city='+courseData.currentLocation.city_id+'&state='+courseData.currentLocation.state_id+'&entity_id='+courseData.courseId;
        }
        this.props.dfpBannerConfig(dfpPostParams);
    }

    componentWillUnmount()
    {
        if(PreventScrolling.canUseDOM())
        {
            document.getElementById('page-header').style.display = "table";
            document.getElementById('page-header').style.position = "relative";
        }
        PreventScrolling.enableScrolling(false,true);
        this.props.clearDfpBannerConfig();
        window.removeEventListener('scroll', this.onScroll);
        if(typeof(isUserLoggedIn) != 'undefined' && !isUserLoggedIn()){
            window.removeEventListener("scroll", this.enableGraph);
        }
    }

    isErrorPage()
    {
        let html404 = document.getElementById('notFound-Page');
        return (html404 && html404.innerHTML);
    }

    isServerSideRenderedHTML()
    {
        let htmlNode = document.getElementById('clp');
        return ((htmlNode && htmlNode.innerHTML) || this.isErrorPage());
    }

    componentWillReceiveProps(nextProps)
    {
        let newCourseId = nextProps.match.params.listingId;
        let prevCourseId = this.props.match.params.listingId;
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
        if(newCourseId != prevCourseId || nextLocalityId != prevLocalotyId || nextCityId != prevCityId)
        {
            this.fetchCourseData(newCourseId,nextCityId,nextLocalityId);
            // this.fetchResponseFormData(newCourseId);

        }
        const {catpageCourse} = nextProps;
        this.clearReduxCategoryCourseData(newCourseId,catpageCourse);
        window.addEventListener("scroll", this.enableGraph);
    }

    clearReduxCategoryCourseData(courseId,catpageCourse)
    {
        if(typeof catpageCourse != 'undefined' && getObjectSize(catpageCourse) > 0 && courseId != catpageCourse.courseId)
        {
            this.props.storeCourseDataForPreFilled();
        }
    }

    createViewedResponse() {

        let queryParams = this.props.location.search, mptTuple = false;
        let mailerId = 0;
        if(queryParams != '') {
            let queryParamsArr = queryParams.split('?')[1].split('&');
            queryParamsArr.map(function(value, index) {
                if(value.includes("fromwhere")){
                    mailerId = value.split("=")[1]
                }
            })
        }
        let actionType = "mobile_viewedListing";
        let trackingKeyId = 1103;
        if(mailerId != 0 && MPTMailerTrakingMapping[mailerId]){
            actionType = "Mailer_Promotion_Tuple";
            trackingKeyId = MPTMailerTrakingMapping[mailerId];
        }
        let courseData = this.props.courseData;
        if(courseData.courseId){
            let viewedResponseData = {
                "listingId": courseData.courseId,
                "trackingKeyId": trackingKeyId,
                "actionType": actionType,
                "listingType":"course"
            };
            viewedResponse(viewedResponseData);
        }

    }

    initialFetchData()
    {
        let courseId = this.props.match.params.listingId;
        if(isNaN(courseId))
            return;
        let cityId = 0;
        let localityId = 0;
        //let queryParams = new URLSearchParams(this.props.location.search);
        cityId = getQueryVariable('city', this.props.location.search);//queryParams.get('city');
        localityId = getQueryVariable('locality', this.props.location.search);//queryParams.get('locality');
        this.fetchCourseData(courseId,cityId,localityId);
        // this.fetchResponseFormData(courseId);
    }


    fetchCourseData(courseId,cityId,localityId)
    {
        var queryParams = 'courseId='+courseId;
        if(cityId != 0 && cityId != '' && cityId != null)
        {
            queryParams += '&cityId='+cityId;
        }
        if(localityId != 0 && localityId != '' && localityId != null)
        {
            queryParams += '&localityId='+localityId;
        }
        this.setState({isShowLoader : true});
        var fetchPromise = this.props.fetchCourseDetailPageData(queryParams);
        var self = this;
        this.showToastMsg = true;
        fetchPromise.then(function() {
            self.setState({isShowLoader : false});
            self.trackGTM();
            self.createViewedResponse();
            if(self.props.courseData != null && !self.isErrorPage()){
                if(typeof(isUserLoggedIn) != 'undefined' && isUserLoggedIn() && self.props.courseData && self.props.courseData.showToastMessage && self.showToastMsg){
                    self.showToastMsg = false;
                    setTimeout(function(){
                        showToastMsg(PageLoadToastMsg('SRM'),5000);
                    },3000);
                }
            }

            self.getDFPData();
            self.isDfpData = true;
        });
    }


    // fetchResponseFormData(courseId)
    // {
    //     return;
    //     // var params1              = 'clientCourseId='+courseId+'&listingType=course';
    //     // // showResponseForm(params1);
    //     // var params2              = 'clientCourse='+courseId;
    //     // getFormByClientCourse(params2);
    // }

    renderLoader() {
        PreventScrolling.enableScrolling(true);
        if(PreventScrolling.canUseDOM())
        {
            document.getElementById('page-header').style.display = "table";
            document.getElementById('page-header').style.position = "relative";
        }
        return <ContentLoader courseId={this.props.match.params.listingId}/>;
    }

    createSectionalNavData(){
        var sectionalNavObj = {};
        var sectionalNavCount = {};
        var sectionalGaObj = {};
        const {courseData} = this.props;
        if(this.props.courseData.highlights != null && this.props.courseData.highlights.length > 0){
            sectionalNavObj['highlights'] = 'Highlights';
            sectionalGaObj['highlights'] = {category : 'CLP',action : 'NavHighlights',label : 'NavBar'};
        }
        if(this.props.courseData.reviewWidget != null && this.props.courseData.reviewWidget.reviewData != null && this.props.courseData.reviewWidget.reviewData.reviewsData != null){
            sectionalNavCount['review'] = this.props.courseData.reviewWidget.reviewData.allReviewsCount;
            sectionalNavObj['review'] = 'Reviews'
            sectionalGaObj['review'] = {category : 'CLP',action : 'NavReviews',label : 'NavBar'};
        }
        if(this.props.courseData.courseFees != null){
            sectionalNavObj['fees'] = 'Fees'
            sectionalGaObj['fees'] = {category : 'CLP',action : 'NavFees',label : 'NavBar'};
        }
        if(this.props.courseData.eligibility != null){
            sectionalNavObj['eligibility'] = 'Eligibility';
            sectionalGaObj['eligibility'] = {category : 'CLP',action : 'NavEligibility',label : 'NavBar'};
        }
        if(this.props.courseData.admissionProcess != null || (this.props.courseData.importantDates && this.props.courseData.importantDates.importantDates != null)){
            if(this.props.courseData.admissionProcess == null)
            {
                sectionalNavObj['admissions'] = 'Dates';
            }
            else
            {
                sectionalNavObj['admissions'] = 'Admissions';
            }
            sectionalGaObj['admissions'] = {category : 'CLP',action : 'NavAdmissions',label : 'NavBar'};
        }
        if(this.props.courseData.courseStructure != null){
            sectionalNavObj['structure'] = 'Structure'
            sectionalGaObj['structure'] = {category : 'CLP',action : 'NavCourseStructure',label : 'NavBar'};
        }
        if(courseData.seatsData != null && ((courseData.seatsData.totalSeats > 0) || (courseData.seatsData.categoryWiseSeats != null && Array.isArray(courseData.seatsData.categoryWiseSeats) && courseData.seatsData.categoryWiseSeats.length > 0) || (courseData.seatsData.examWiseSeats != null && Array.isArray(courseData.seatsData.examWiseSeats) && courseData.seatsData.examWiseSeats.length > 0) || (courseData.seatsData.domicileWiseSeats != null && Array.isArray(courseData.seatsData.domicileWiseSeats) && courseData.seatsData.domicileWiseSeats.length > 0) || (courseData.seatsData.relatedStates != null && Array.isArray(courseData.seatsData.relatedStates) && courseData.seatsData.relatedStates.length > 0))) {
            sectionalNavObj['seats'] = 'Seats Info';
            sectionalGaObj['seats'] = {category : 'CLP',action : 'NavSeats',label : 'NavBar'};

        }
        if(this.state.isGraphShow && (this.props.courseData.placements!= null || (this.props.courseData.recruitmentCompanies!= null && this.props.courseData.recruitmentCompanies.length > 0))){
            sectionalNavObj['placements'] = 'Placements';
            sectionalGaObj['placements'] = {category : 'CLP',action : 'NavPlacement',label : 'NavBar'};
        }
        if(this.props.courseData.anaWidget != null && typeof this.props.courseData.anaWidget.questionsDetail != 'undefined' && this.props.courseData.anaWidget.questionsDetail!= null ) {
            sectionalNavCount['ana'] = this.props.courseData.anaWidget != null ? this.props.courseData.anaWidget.totalNumber : 0;
            sectionalNavObj['ana'] = 'Q&A';
            sectionalGaObj['ana'] = {category : 'CLP',action : 'NavQandA',label : 'NavBar'};
        }
        if(this.props.courseData.coursePartners != null && this.props.courseData.coursePartners.length > 0){
            sectionalNavObj['partner'] = 'Partner';
            sectionalGaObj['partner'] = {category : 'CLP',action : 'NavPartner',label : 'NavBar'};
        }
        if(this.props.courseData.media!= null){
            sectionalNavObj['gallery'] = 'Gallery';
            let totalImages = 0;
            let totalVideos = 0;
            let photos = typeof this.props.courseData.media['photos'] != 'undefined' ? this.props.courseData.media['photos'] : {};
            let photoSections = typeof this.props.courseData.media['photoSections'] != 'undefined' ? this.props.courseData.media['photoSections'] : [];
            for(let i in photoSections)
            {
                totalImages += photos[photoSections[i]].length;
            }
            let videos = typeof this.props.courseData.media['videos'] != 'undefined' && this.props.courseData.media['videos'].length > 0 ? this.props.courseData.media['videos'] : [];

            if(typeof videos != 'undefined' && Array.isArray(videos) && videos.length > 0)
            {
                totalVideos = videos.length;
            }
            sectionalNavCount['gallery'] = totalImages + totalVideos;
            sectionalGaObj['gallery'] = {category : 'CLP',action : 'NavGallery',label : 'NavBar'};
        }
        if(this.props.courseData.currentLocation != 'undefined' && this.props.courseData.currentLocation.contact_details != null) {
            let contactDetails = this.props.courseData.currentLocation.contact_details;
            if(contactDetails['address'] != null || contactDetails['admission_contact_number'] != null || contactDetails['website_url'] != null  || contactDetails['generic_contact_number'] != null)
            {
                sectionalNavObj['contact'] = 'Contact';
                sectionalGaObj['contact'] = {category : 'CLP',action : 'NavContact',label : 'NavBar'};
            }
        }

        return  {sectionalNavObj : sectionalNavObj,sectionalNavCount : sectionalNavCount,sectionalGaObj : sectionalGaObj};
    }
    trackGTM()
    {
        const {courseData,config} = this.props;
        var trackingParams;
        var beaconData = {};
        if(typeof courseData != 'undefined' && courseData)
        {
            trackingParams = getCourseTrackingParams(courseData);
            TagManager.dataLayer({dataLayer : trackingParams.gtmParams, dataLayerName : 'dataLayer'});
            ElasticSearchTracking(trackingParams.beaconTrackData,config.BEACON_TRACK_URL);
            beaconData['listing_type'] = 'course';
            beaconData['listing_id'] = courseData.courseId;
            beaconData['product_Id'] = config.CLP_PRODUCT_ID;
            BeaconIndexTracking(beaconData, config.BEACON_INDEX_TRACK_URL);
        }
    }
    render()
    {
        if(this.state.isShowLoader)
        {
            return (
                <React.Fragment>
                    {this.renderLoader()}
                </React.Fragment>

            );
        }
        if(isNaN(this.props.match.params.listingId) || (this.props.courseData && typeof this.props.courseData.statusCode != 'undefined' && this.props.courseData.statusCode== 404))
        {
            return <NotFound />;
        }
        else if(this.props.courseData == null || Object.keys(this.props.courseData).length == 0)
        {
            return (
                <React.Fragment>
                    {this.renderLoader()}
                </React.Fragment>

            );
        }
        else if(this.props.courseData.courseId && this.props.courseData.courseId != this.props.match.params.listingId)
        {
            return (
                <React.Fragment>
                    {this.renderLoader()}
                </React.Fragment>

            );
        }
        else if(this.props.courseData.statusCode == 301 || this.props.courseData.statusCode == 302)
        {
            <Redirect to={this.props.courseData.redirectUrl}/>
        }
        this.sectionalNavData = this.createSectionalNavData();
        PreventScrolling.enableScrolling(true);
        let seoData = (this.props.courseData && this.props.courseData.seoData) ? this.props.courseData.seoData : '';
        let x={};
        x.courseWidget= courseWidget;
        return (
            <React.Fragment>
                {ClientSeo(seoData)}
                <div id="fixed-card" className="nav-tabs display-none">
                    <SectionalNav sectionalNavObj={this.sectionalNavData['sectionalNavObj']} sectionalNavCount={this.sectionalNavData['sectionalNavCount']} sectionalGaObj={this.sectionalNavData['sectionalGaObj']}/>
                </div>
                <div className="clp" id="clp">
                    <TopWidget data={this.props.courseData} config={this.props.config} location={this.props.location}/>
                    <div id="tab-section" className='nav-tabs'>
                        <SectionalNav sectionalNavObj={this.sectionalNavData['sectionalNavObj']} sectionalNavCount={this.sectionalNavData['sectionalNavCount']} sectionalGaObj={this.sectionalNavData['sectionalGaObj']}/>
                    </div>


                    { this.props.courseData.highlights != null && this.props.courseData.highlights.length > 0 && <Highlights data={this.props.courseData.highlights} page = "coursePage" />}

                    <DFPBannerTempalte bannerPlace="Client"/>

                    {this.props.courseData.reviewWidget != null && this.props.courseData.reviewWidget.reviewData != null && this.props.courseData.reviewWidget.reviewData.reviewsData &&
                    <ReviewWidget
                        reviewWidgetData={this.props.courseData.reviewWidget}
                        config={this.props.config} isPaid={this.props.courseData.coursePaid}

                        aggregateReviewWidgetData = {this.props.courseData.aggregateReviewWidget} gaTrackingCategory={'CLP'} />}

                    { this.props.courseData.courseFees != null && <Fees courseId={this.props.courseData.courseId} listingName={this.props.courseData.instituteName} courseName={this.props.courseData.courseName} fees={this.props.courseData.courseFees} currentLocation={this.props.courseData.currentLocation} scholarshipData={this.props.courseData.scholarshipData}/>}
                    {(this.props.courseData.scholarshipData!='' && this.props.courseData.scholarshipData!=null) && <Scholarship instituteName={this.props.courseData.instituteName} scholarship={this.props.courseData.scholarshipData}/>}
                    {this.props.courseData.eligibility != null && <Eligibility eligibility={this.props.courseData.eligibility} examTuples={this.props.courseData.tupleData} acceptingExamMapping={null} predictorInfo={this.props.courseData.predictorInfo}/>}

                    <DFPBannerTempalte bannerPlace="LAA"/>
                    <DFPBannerTempalte bannerPlace="LAA1"/>

                    {(this.props.courseData.admissionProcess != null || (this.props.courseData.importantDates && this.props.courseData.importantDates.importantDates != 'undefined' && this.props.courseData.importantDates.importantDates != null)) && <AdmissionProcess admissionProcess={this.props.courseData.admissionProcess} importantDates={this.props.courseData.importantDates} courseId={this.props.courseData.courseId}/>}



                    <DFPBannerTempalte bannerPlace="AON"/>
                    <DFPBannerTempalte bannerPlace="AON1"/>

                    {this.props.courseData.courseStructure != null && <CourseStructure courseStructure={this.props.courseData.courseStructure}/>}

                    {this.props.courseData.seatsData != null && <SeatsBreakup seatsData={this.props.courseData.seatsData}/>}

                    { this.state.isGraphShow && ((this.props.courseData.placements!= null && this.props.courseData.placements!='') || (this.props.courseData.recruitmentCompanies!= null && this.props.courseData.recruitmentCompanies.length > 0)) && <Placement instituteName={this.props.courseData.instituteName} clientCourseId={this.props.courseData.courseId} placementData={this.props.courseData.placements} intershipData={this.props.courseData.intership} recruitmentCompanies={this.props.courseData.recruitmentCompanies} placementCTATrackingKey={958} internshipCTATrackingKey={959}/>}

                    {/* this.state.isGraphShow && <Alumni alumniData={this.state.alumniData} courseData={this.props.courseData} /> */}

                    {this.props.courseData.courseWidget && <CoursesOffered instituteData={{}} courseWidget={this.props.courseData.courseWidget} page = {'course'} instituteType={this.props.courseData.instituteType} config={this.props.config} location={this.props.courseData.currentLocation} isMultiLocation={false} fromwhere= "coursePage" instituteUrl={this.props.courseData.instituteUrl} instituteName={this.props.courseData.instituteName}/>}
   
                    <Feedback pageId={this.props.courseData.courseId} pageType={'CLP'} deviceType={'mobile'} />
                    {(typeof this.props.courseData != 'undefined' && typeof this.props.courseData.relatedCHP != 'undefined' && this.props.courseData.relatedCHP.length>0)?
                        <OtherTopics relatedData={this.props.courseData.relatedCHP} key={19} config={this.props.config} isPdfCall={false}/>:''
                    }

                    <AnA anaWidget={this.props.courseData.anaWidget} config={this.props.config} courseId={this.props.courseData.courseId} instituteId={this.props.courseData.instituteId} page = "coursePage" location = {this.props.courseData.currentLocation}/>

                    {this.props.courseData.alsoViewedCourses.length>0 && <Carousel heading="Students who viewed this course also viewed the following colleges" data={this.props.courseData.alsoViewedCourses} page ="coursepage" section="alsoviewed"/>}

                    { this.props.courseData.coursePartners != null && this.props.courseData.coursePartners.length > 0 && <PartnerColleges data={this.props.courseData.coursePartners} config={this.props.config}/>}

                    {(this.props.courseData.media!= null) && <Gallery data={this.props.courseData.media} name = {this.props.courseData.instituteName}/>}

                    { this.props.courseData.currentLocation != 'undefined' && this.props.courseData.currentLocation.contact_details != null && <ContactDetails data={this.props.courseData} config={this.props.config} location={this.props.location} page = "coursepage"/>}

                    {this.props.courseData.similarCourses.length>0 && <Carousel heading="Other Similar Courses" data={this.props.courseData.similarCourses} page ="coursepage" section="similar"/>}

                    {((this.props.courseData.rankingInterlinking!=null && this.props.courseData.rankingInterlinking.length>0)|| (this.props.courseData.categoryPageLinks!=null && this.props.courseData.categoryPageLinks.length>0)) && <CourseCategoryPageLink rankingInterlinking={this.props.courseData.rankingInterlinking} categoryInterlinking={this.props.courseData.categoryPageLinks} config={this.props.config} page ="coursepage"/>}
                </div>
                { <BottomCourseSticky 
                    listingId={this.props.courseData.courseId} 
                    listingName={this.props.courseData.instituteName} 
                    page = "coursePage"  
                    config={this.props.config}
                    />}                
            </React.Fragment>

        )
    }
}
function mapStateToProps(state)
{
    return {
        courseData : state.courseData,
        config : state.config,
        catpageCourse : state.catpageCourse
    }
}
function mapDispatchToProps(dispatch){
    return bindActionCreators({ fetchCourseDetailPageData,storeCourseDataForPreFilled ,dfpBannerConfig,clearDfpBannerConfig}, dispatch);
}

export default connect(mapStateToProps,mapDispatchToProps)(CourseDetailPage);

CourseDetailPage.propTypes = {
  catpageCourse: PropTypes.any,
  clearDfpBannerConfig: PropTypes.any,
  config: PropTypes.any,
  courseData: PropTypes.any,
  dfpBannerConfig: PropTypes.any,
  fetchCourseDetailPageData: PropTypes.any,
  location: PropTypes.any,
  match: PropTypes.any,
  storeCourseDataForPreFilled: PropTypes.any
}
