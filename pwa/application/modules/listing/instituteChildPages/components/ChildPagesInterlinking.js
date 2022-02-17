import PropTypes from 'prop-types'
import React from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import './../assets/childInterlinking.css';
import {addingDomainToUrl} from './../../../../utils/commonHelper';
import config from './../../../../../config/config';
import Analytics from './../../../reusable/utils/AnalyticsTracking';
import { Link } from "react-router-dom";
import {storeChildPageDataForPreFilled} from './../../instituteChildPages/actions/AllChildPageAction';

class ChildPagesInterlinking extends React.Component{

    constructor(props){
        super(props);
    }

    handleClickallCourses(clickType = '',actionLabel){
        this.trackEvent(actionLabel);
        var data = {};
        if(this.props.data.listingId){
            data.listingId = this.props.data.listingId;
        }        
        if(this.props.data.listingId){
            data.listingType = this.props.data.listingType;
        }
        if(this.props.data.instituteTopCardData){
            data.instituteTopCardData = this.props.data.instituteTopCardData;
        }
        if(this.props.data.reviewWidget){
            data.reviewWidget = this.props.data.reviewWidget;
        }
        if(this.props.data.currentLocation){
            data.currentLocation = this.props.data.currentLocation;
        }
        if(this.props.data.aggregateReviewWidget !=='undefined'){
            data.aggregateReviewWidget = this.props.data.aggregateReviewWidget;
        }
        if(this.props.data.anaCountString !=='undefined'){
            data.anaCountString = this.props.data.anaCountString;
        }
        data.anaWidget = {};
        data.allQuestionURL = '';
        data.showFullLoader = false;
        if(clickType == 'admissionLink'){
          data.PageHeading = 'Admission 2019 - Cutoffs, Eligibility & Dates';
        }else if(clickType == 'courseLink'){
          data.PageHeading = 'Courses, Fees 2019';
        }else if(clickType == 'placementLink'){
          data.fromWhere = 'placementPage';  
          data.showFullSectionLoader = true;
          data.PageHeading = 'Placement - Highest & Average Salary Package';
        }else{
            data.fromWhere = 'cutoffPage';  
            data.showFullSectionLoader = true;
            data.PageHeading = 'Cut off & Merit List 2019';
        }
        this.props.storeChildPageDataForPreFilled(data);
    }

    handleClickOnColleges(linkHeading,clickType = '',page){
        this.trackEvent(clickType);
        var data = {};
        if(this.props.data.listingId){
            data.listingId = this.props.data.listingId;
        }        
        if(this.props.data.listingId){
            data.listingType = this.props.data.listingType;
        }
        if(this.props.data.instituteTopCardData){
            data.instituteTopCardData = this.props.data.instituteTopCardData;
            data.instituteTopCardData.instituteName = linkHeading;
        }
        if(this.props.data.reviewWidget){
            data.reviewWidget = this.props.data.reviewWidget;
        }
        if(this.props.data.currentLocation){
            data.currentLocation = this.props.data.currentLocation;
        }
        if(this.props.data.aggregateReviewWidget !=='undefined'){
            data.aggregateReviewWidget = this.props.data.aggregateReviewWidget;
        }
        if(this.props.data.anaCountString !=='undefined'){
            data.anaCountString = this.props.data.anaCountString;
        }
        data.anaWidget = {};
        data.allQuestionURL = '';
        data.showFullLoader = false;
        data.fromWhere = 'placementPage';  
        data.showFullSectionLoader = true;
        if(page == 'placementPage'){
            data.PageHeading = 'Placement - Highest & Average Salary Package';
        }else if(page == 'cutoffPage'){
            data.PageHeading = 'Cut off & Merit List 2019';
        }
        this.props.storeChildPageDataForPreFilled(data);
    }

    interlinkingHtml(){
        const {data} = this.props;
        let heading = '',linkList = [] , linkHeading,url,linkSuffix="";
        if(data.listingType == 'institute'){
            heading = 'Other college information';
        }
        else{
            heading = 'Other university information';
        }

        if(this.props.fromWhere !='allCoursePage' && data.courseCount && data.courseCount>0 && data.allCourseUrl){
            linkHeading = data.courseCount == 1? '1 Course Offered':data.courseCount+' Courses Offered';
            linkList.push(<li key='intr-course'><Link onClick={this.handleClickallCourses.bind(this,'courseLink','Courses_Other_College_Info')} to ={data.allCourseUrl}> {linkHeading} </Link></li>);
        }

        if(this.props.fromWhere !='admissionPage' && data.admissionData !=null && data.admissionData.url){
            linkList.push(<li key='intr-admission'><Link onClick={this.handleClickallCourses.bind(this,'admissionLink','Admission_Other_College_Info')} to={data.admissionData.url}> Admission Process </Link></li>);

        }
        if(this.props.fromWhere !='placementPage' && data.placementPageUrl){
          linkList.push(<li key='intr-placement'><Link onClick={this.handleClickallCourses.bind(this,'placementLink','Placements_College_Info')} to={data.placementPageUrl}> Placements </Link></li>);
          
        }
        if(this.props.fromWhere != 'cutoffPage' && data.cutoffPageUrl !=null ){
                  if(data.courseCount){
                    let linkSuffixPrefix = ' for ';
                    linkSuffix = (data.courseCount == 1)? '1 course':data.courseCount+' courses';
                    linkSuffix = linkSuffixPrefix + linkSuffix;
                  }
                  linkHeading = data.instituteTopCardData.instituteName + " Cut-offs"+linkSuffix;
                  linkList.push(<li key='intr-cutOff'><Link onClick={this.handleClickallCourses.bind(this,'cutoffLink','CutOff_Other_College_Info')} to ={data.cutoffPageUrl}>{linkHeading}</Link></li>);
        }
        if(data.reviewWidget !=null && data.reviewWidget.reviewData != null && data.reviewWidget.reviewData.totalReviews>0 && data.reviewWidget.reviewData.allReviewUrl){
            linkHeading = data.reviewWidget.reviewData.totalReviews == 1? '1 Student Review':data.reviewWidget.reviewData.totalReviews+' Student Reviews';
            linkList.push(<li key='intr-review'><a onClick={this.trackEvent.bind(this,'Reviews_Other_College_Info')} href ={addingDomainToUrl(data.reviewWidget.reviewData.allReviewUrl,config().SHIKSHA_HOME)}> {linkHeading} </a></li>);
        }
        if(data.anaCount && data.anaCount>0 && data.listingUrl){
            url = data.listingUrl+'/questions';
            linkHeading = data.anaCount == 1? '1 Answered Question':data.anaCount+' Answered Questions';
            linkList.push(<li key='intr-ana'><a onClick={this.trackEvent.bind(this,'Answered_Questions_Other_College_Info')} href ={addingDomainToUrl(url,config().SHIKSHA_HOME)}> {linkHeading} </a></li>);
        }
        if(data.articleCount && data.articleCount>0 && data.listingUrl){
            url  = data.listingUrl+'/articles';
            linkHeading = data.articleCount == 1? '1 News and Article':data.articleCount+' News and Articles';
            linkList.push(<li key='intr-article'><a onClick={this.trackEvent.bind(this,'Articles_Other_College_Info')} href ={addingDomainToUrl(url,config().SHIKSHA_HOME)}> {linkHeading} </a></li>);
        }
        if(data.scholarshipInfo !=null && data.scholarshipInfo.scholarShipDetails != null && data.scholarshipInfo.allScholarshipPageUrl){
            linkList.push(<li key='intr-scholarship'><a onClick={this.trackEvent.bind(this,'Scholarships_Other_College_Info')} href ={addingDomainToUrl(data.scholarshipInfo.allScholarshipPageUrl,config().SHIKSHA_HOME)}> Scholarships </a></li>);
        }

        if(linkList.length>0){
            return(

                <section id="chd-intlk" className="viewCollegeSec interlinkSec">
                    <div className="viewCllgSec _container">
                        <h2 className='tbSec2'>{heading}</h2>
                        <div className= "viewCllgSec _subcontainer">
                            <ul className="dot_ul">
                                {linkList}
                            </ul>
                        </div>
                    </div>
                </section>
            )
        }
        else{
            return null;
        }
    }

    similarPlacementColleges(){
        const {data} = this.props;
        let heading = '',page = '',actionLabel='';
        if(this.props.fromWhere ==  'cutoffPage'){
            page = 'cut-offs' ;
            actionLabel = 'Cutoff_Similar_Colleges';
        }else if(this.props.fromWhere ==  'placementPage'){
            page = 'placement';
            actionLabel = 'Placement_Similar_Colleges';
        }
        if(data.listingType == 'institute'){
            heading = 'View '+page+' of similar colleges';
        }
        else{
            heading = 'View '+page+' of similar universities';
        }

        var linkList = [];
        var linkHeading;
        if(data.alsoViewedInstitutes && data.alsoViewedInstitutes.length >0 ){
            var counter = 0;
            data.alsoViewedInstitutes.map((item) => {
                linkHeading = item.instituteName;
                counter++;
                if(counter < 6){
                    if(this.props.deviceType == 'desktop'){
                        linkList.push(<li key={counter+'intr-similar-placement'}><a onClick={this.handleClickOnColleges.bind(this,actionLabel)} href ={ addingDomainToUrl(item.instituteUrl,config().SHIKSHA_HOME)}> {linkHeading} </a></li>);
                    }
                    else{
                        linkList.push(<li key={counter+'intr-similar-placement'}><Link onClick={this.handleClickOnColleges.bind(this,linkHeading,actionLabel,this.props.fromWhere)} to ={item.instituteUrl}> {linkHeading} </Link></li>);
                    }
                }
            })
        }

        if(linkList.length>0){
            return(

                <section id="chd-intlk" className="viewCollegeSec interlinkSec">
                    <div className="viewCllgSec _container">
                        <h2 className='tbSec2'>{heading}</h2>
                        <div className= "viewCllgSec _subcontainer">
                            <ul className="dot_ul">
                                {linkList}
                            </ul>
                        </div>
                    </div>
                </section>
            )
        }
        else{
            return null;
        }
    }

    trackEvent(action=null)
    {
        Analytics.event({category : this.props.gaCategory, action : action ? action : 'interlinking', label : 'click'});
    }

    render(){
        return (
            <React.Fragment>
                {this.props.similarPlacement? this.similarPlacementColleges() : this.interlinkingHtml()}
            </React.Fragment>
        );
    }

}

function mapDispatchToProps(dispatch){
    return bindActionCreators({ storeChildPageDataForPreFilled}, dispatch);
}

export default connect(null,mapDispatchToProps)(ChildPagesInterlinking);

ChildPagesInterlinking.propTypes = {
  data: PropTypes.any,
  deviceType: PropTypes.any,
  fromWhere: PropTypes.any,
  gaCategory: PropTypes.any,
  similarPlacement: PropTypes.any,
  storeChildPageDataForPreFilled: PropTypes.any
}