import PropTypes from 'prop-types'
import React from 'react';
import { connect } from 'react-redux';
import TopWidget from './TopWidgetComponent';
import Highlights from './HighlightsComponent';
import Eligibility from './EligibilityComponent';
import PartnerColleges from './PartnerCollegesComponent';
import PlacementAmp from '../../../../../views/amp/pages/course/components/PlacementAmp';
import AdmissionProcessAmp  from '../../../../../views/amp/pages/course/components/AdmissionProcessAmp';
import GalleryAmp  from '../../../../../views/amp/pages/course/components/galleryamp';
import Alumni from '../../../../../views/amp/pages/course/components/AluminiComponent';
import CourseCategoryPageLinkAmp  from '../../../../../views/amp/pages/course/components/CourseCategoryPageLinkAmp';
import CourseStructureAmp from '../../../../../views/amp/pages/course/components/CourseStructureAmp';
import AnaWidgetAmp from '../../../../../views/amp/pages/course/components/AnaWidgetAmp';
import SeatsBreakupAmp from '../../../../../views/amp/pages/course/components/SeatsBreakupAmp';
import AmpCarousel from '../../../common/components/AmpCarousel';
import FeesAmp from '../../../../../views/amp/pages/course/components/FeesAmp';
import ReviewWidgetAmp from '../../../../../views/amp/pages/course/components/ReviewWidgetAmp'
import ContactDetailsAmp from '../../../../../views/amp/pages/course/components/ContactDetailsAmp';
import CourseStickyWidgetAMP from '../../../../../views/amp/pages/course/components/CourseStickyWidget';
import {DFPBannerTempalte} from './../../../reusable/components/DFPBannerAd';


class CourseDetailPageAmp extends React.Component
{
    constructor(props)
    {
        super(props);
        this.state = {
            //isShowLoader : false
        }
    }

    render()
    {

        const {courseData,importantDatesAmp} = this.props;

        return (
            <React.Fragment>
                <section className="s-container">
                    <TopWidget data={courseData} config={this.props.config} location={this.props.location} isAmp={true} />
                    { courseData.highlights != null && courseData.highlights.length > 0 && <Highlights data={courseData.highlights} isAmp={true}/>}
                    <DFPBannerTempalte isAmp ={true} bannerPlace="Client"/>
                    {courseData.reviewWidget != null && courseData.reviewWidget.reviewData != null && courseData.reviewWidget.reviewData.reviewsData &&
                    <ReviewWidgetAmp
                        reviewWidgetData={courseData.reviewWidget}
                        config={this.props.config} isPaid={courseData.coursePaid}
                        aggregateReviewWidgetData = {courseData.aggregateReviewWidget} />}
                    {courseData.eligibility != null && <Eligibility eligibility={courseData.eligibility} predictorInfo={courseData.predictorInfo} isAmp = {true}/> }
                    <DFPBannerTempalte isAmp ={true} bannerPlace="LAA"/>
                    <DFPBannerTempalte isAmp ={true} bannerPlace="LAA1"/>
                    {(courseData.admissionProcess != null || (courseData.importantDates && courseData.importantDates.importantDates != 'undefined' && courseData.importantDates.importantDates != null)) && <AdmissionProcessAmp admissionProcess={courseData.admissionProcess} importantDates={courseData.importantDates} courseId={courseData.courseId} importantDatesAmp={importantDatesAmp}/>}
                    { courseData.courseFees != null && <FeesAmp fees={courseData.courseFees} currentLocation={courseData.currentLocation} instituteUrl={courseData.instituteUrl} instituteName={courseData.instituteName} courseId={courseData.courseId}/>}
                    <DFPBannerTempalte isAmp ={true} bannerPlace="AON"/>
                    <DFPBannerTempalte isAmp ={true} bannerPlace="AON"/>
                    {courseData.courseStructure != null && <CourseStructureAmp courseStructure={courseData.courseStructure}/>}
                    {courseData.seatsData != null && <SeatsBreakupAmp seatsData={courseData.seatsData}/>}
                    {((courseData.placements!= null && courseData.placements!='') || (courseData.recruitmentCompanies!= null && courseData.recruitmentCompanies.length > 0)) && <PlacementAmp instituteName={courseData.instituteName} clientCourseId={courseData.courseId} placementData={courseData.placements} intershipData={courseData.intership} recruitmentCompanies={courseData.recruitmentCompanies} courseUrl={courseData.courseUrl}/>}
                    {this.props.alumniData != null && <Alumni alumniData={this.props.alumniData} courseData={courseData}  />}
                    <AnaWidgetAmp anaWidget={courseData.anaWidget} config={this.props.config} courseId={courseData.courseId} instituteId={courseData.instituteId} campusRepData = {this.props.campusRepData}/>
                    {courseData.alsoViewedCourses.length>0 && <AmpCarousel heading="Students who viewed this course also viewed" data={courseData.alsoViewedCourses}  courseId = {courseData.courseId} page ="coursepage" section="alsoviewed"/>}
                    { courseData.coursePartners != null && courseData.coursePartners.length > 0 && <PartnerColleges data={courseData.coursePartners} config={this.props.config} isAmp ={true}/>}
                    {(courseData.media!= null) && <GalleryAmp data={courseData.media}/>}
                    { courseData.currentLocation != 'undefined' && courseData.currentLocation.contact_details != null && <ContactDetailsAmp data={courseData} config={this.props.config} location={this.props.location} courseId = {courseData.courseId} courseUrl = {courseData.courseUrl}/>}
                    {courseData.similarCourses.length>0 && <AmpCarousel heading="Other Similar Courses" courseId = {courseData.courseId} data={courseData.similarCourses} page ="coursepage" section="similar"/>}
                    {((courseData.rankingInterlinking!=null && courseData.rankingInterlinking.length>0)|| (courseData.categoryPageLinks!=null && courseData.categoryPageLinks.length>0)) && <CourseCategoryPageLinkAmp rankingInterlinking={courseData.rankingInterlinking} categoryInterlinking={courseData.categoryPageLinks} config={this.props.config}/>}
                    <CourseStickyWidgetAMP courseId = {courseData.courseId} courseUrl = {courseData.courseUrl} />
                </section>
            </React.Fragment>

        );
    }
}

function mapStateToProps(state)
{
    return {
        ampHamburger : state.ampHamburger ,
        importantDatesAmp : state.importantDatesAmp ,
        alumniData : state.alumniData,
        campusRepData : state.campusRepData,
        courseData : state.courseData,
        config : state.config,
        catpageCourse : state.catpageCourse
    }
}

export default connect(mapStateToProps)(CourseDetailPageAmp);

CourseDetailPageAmp.propTypes = {
    alumniData: PropTypes.any,
    campusRepData: PropTypes.any,
    config: PropTypes.any,
    courseData: PropTypes.any,
    importantDatesAmp: PropTypes.any,
    location: PropTypes.any
}