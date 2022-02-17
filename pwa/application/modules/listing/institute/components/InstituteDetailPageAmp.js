import React from 'react';
import ReactDOM from 'react-dom';
import { connect } from 'react-redux';
import TopWidget from './TopWidgetComponent';
import Highlights from '../../course/components/HighlightsComponent';
import Facilities from './FacilitiesComponent';
import Scholarship from './ScholarshipComponent';
import CutOffWidget from './CutOffWidgetComponent';
import CollegeListAmp from '../../../../../views/amp/pages/institute/components/CollegeListAmp';
import AdmissionWidget from './AdmissionComponent';
import ReviewWidgetAmp from '../../../../../views/amp/pages/course/components/ReviewWidgetAmp';
import CoursesOffered from './CoursesOfferedComponent';
import AnaWidgetAmp from '../../../../../views/amp/pages/course/components/AnaWidgetAmp';
import GalleryAmp  from '../../../../../views/amp/pages/course/components/galleryamp';
import Events from './EventsComponent';
import Articles from './ArticlesComponent';
import  { Redirect } from 'react-router-dom';
import AmpCarousel from '../../../common/components/AmpCarousel';
import CourseCategoryPageLinkAmp  from '../../../../../views/amp/pages/course/components/CourseCategoryPageLinkAmp';
import ContactDetailsAmp from '../../../../../views/amp/pages/course/components/ContactDetailsAmp';
import BottomInstituteSticky from './BottomInstituteSticky';


class InstituteDetailPageAmp extends React.Component{
  constructor(props)
  {
      super(props);
      this.state = {
          isShowLoader : false
      }

  }
  render(){
    const {instituteData,config} = this.props;
    console.log("Inside InstituteDetailPageAmp");
    var instituteName = (instituteData.listingName)?((instituteData.listingName).split(','))[0]:null;
    return(
      <React.Fragment>
        <section className="s-container">
          {instituteData.instituteTopCardData && <TopWidget instituteId={instituteData.listingId} data={instituteData} config={config} location = {this.props.location} page = {instituteData.listingType} isAmp={true}/>}
            {instituteData.collegeWidget != null && instituteData.collegeWidget.collegeWidgetData !=null  && <CollegeListAmp collegelistWidget={instituteData.collegeWidget} collegeName={instituteData.listingName} listingId={instituteData.listingId} config={config} page = {instituteData.listingType} />}
            {instituteData.courseWidget != null && <CoursesOffered courseWidget={instituteData.courseWidget} page = {instituteData.listingType} config={config} location={instituteData.currentLocation} isAmp={true}/>}
          {/*instituteData.adminssionData !=null && (instituteData.adminssionData.admissionDetails || (instituteData.adminssionData.examList && instituteData.adminssionData.examList.length>0)  || instituteData.adminssionData.showAdmissionFlag ==true ) &&
            <AdmissionWidget admissionData ={instituteData.adminssionData} instituteName = {instituteName}config={config} page = {instituteData.listingType} isAmp={true}/>*/}
          { instituteData.highlightsInfo && instituteData.highlightsInfo.highlights && instituteData.highlightsInfo.highlights.length > 0 && <Highlights data={instituteData.highlightsInfo.highlights} allHighlightsPageUrl = {instituteData.highlightsInfo.allHighlightsPageUrl} page = {instituteData.listingType} isAmp={true}/>}
          { instituteData.facilityInfo  && instituteData.facilityInfo.length > 0 && <Facilities data={instituteData.facilityInfo} page = {instituteData.listingType} isAmp={true}/>}
          {instituteData.reviewWidget != null && instituteData.reviewWidget.reviewData != null && instituteData.reviewWidget.reviewData.reviewsData &&
          <ReviewWidgetAmp reviewWidgetData={instituteData.reviewWidget} config={this.props.config} isPaid={instituteData.coursePaid} aggregateReviewWidgetData = {instituteData.aggregateReviewWidget} />}
          {(instituteData.media!= null) && <GalleryAmp data={instituteData.media} name = {instituteData.listingName}/>}
          {instituteData.alsoViewedInstitutes && instituteData.alsoViewedInstitutes.length>0 && <AmpCarousel heading="Students who viewed this institutes also Viewed the following" data={instituteData.alsoViewedInstitutes} page ="institutepage" section="alsoviewed"/>}
          { instituteData.scholarshipInfo && (instituteData.scholarshipInfo.scholarShipDetails!='' && instituteData.scholarshipInfo.scholarShipDetails!=null) && <Scholarship instituteName={instituteData.listingName} scholarship={instituteData.scholarshipInfo} config={config} page = {instituteData.listingType} isAmp={true}/>}
          {(instituteData.collegeCutOffWidget) && <CutOffWidget instituteName={instituteData.listingName} data={instituteData.collegeCutOffWidget} config={config} page = {instituteData.listingType} isAmp={true}/>}
          {(instituteData.eventInfo!=null && instituteData.eventInfo.eventsDetails && instituteData.eventInfo.eventsDetails.length>0 ) && <Events data={instituteData.eventInfo} page = {instituteData.listingType} isAmp={true}/>}
          {(instituteData.articleRecommendations && instituteData.articleRecommendations.articleData.articleDetails.length>0 ) && <Articles config={config} instituteName={instituteData.listingName} data ={instituteData.articleRecommendations} page = {instituteData.listingType} isAmp={true}/>}
          {(instituteData.similarInstitutes  && instituteData.similarInstitutes.length>0 )&& <AmpCarousel heading={"Other "+instituteName+" Institutes"} data={instituteData.similarInstitutes} page ="institutepage" section="similar" page = "institutepage"/>}
          { instituteData.currentLocation && instituteData.currentLocation != 'undefined' && instituteData.currentLocation.contact_details != null && <ContactDetailsAmp data={instituteData} config={config} location={this.props.location} />}
          {((instituteData.rankingInterlinking!=null && instituteData.rankingInterlinking.length>0)|| (instituteData.categoryPageLinks!=null && instituteData.categoryPageLinks.length>0)) && <CourseCategoryPageLinkAmp rankingInterlinking={instituteData.rankingInterlinking} categoryInterlinking={instituteData.categoryPageLinks} config={config}/>}
          { <BottomInstituteSticky listingId={instituteData.listingId} page = {instituteData.listingType} config={config} isAmp={true}/>}
        </section>
      </React.Fragment>
    )
  }
}


function mapStateToProps(state)
{
    return {
        ampHamburger : state.ampHamburger ,
        instituteData : state.instituteData,
        catpageInstitute : state.catpageInstitute,
        config : state.config
    }
}

function mapDispatchToProps(dispatch){
    //return bindActionCreators({ fetchCourseDetailPageData,storeCourseDataForPreFilled ,dfpBannerConfig,clearDfpBannerConfig}, dispatch);
}

export default connect(mapStateToProps)(InstituteDetailPageAmp);
