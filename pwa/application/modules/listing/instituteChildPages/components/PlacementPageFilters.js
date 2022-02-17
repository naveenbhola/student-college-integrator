import PropTypes from 'prop-types'
import React, {Component} from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
//import './../assets/placementPageFilter.css';
import {Link} from 'react-router-dom';
import Analytics from './../../../reusable/utils/AnalyticsTracking';
import {addQueryParams} from './../../../../utils/commonHelper';
import {storeChildPageDataForPreFilled} from './../actions/AllChildPageAction';
import CommonTagWidget from './../../../common/components/CommonTagWidget';


class PlacementPageFilters extends Component {
    constructor(props) {
        super(props);
    }


    trackEvent(actionLabel,label='click')
    {
      var category = "Placement_Page";
      if(this.props.gaTrackingCategory){
        category = this.props.gaTrackingCategory; 
      }
      Analytics.event({category : category, action : actionLabel, label : label});
    }

    handlePlacementFilterClick(dataObj, eventAction){
        this.trackEvent(eventAction);
        var data = {};
        let PageHeading=dataObj.pageHeading;
        let clickType=dataObj.clickType;
        let selectedId=dataObj.id;
        

        if(this.props.pageData.listingId){
            data.listingId = this.props.pageData.listingId;
        }        
        if(this.props.pageData.listingType){
            data.listingType = this.props.pageData.listingType;
        }
        if(this.props.pageData.instituteTopCardData){
            data.instituteTopCardData = this.props.pageData.instituteTopCardData;
        }
        if(this.props.pageData.breadCrumb){
            data.breadCrumb = this.props.pageData.breadCrumb;
        }
        if(this.props.pageData.reviewWidget){
            data.reviewWidget = this.props.pageData.reviewWidget;
        }
        if(this.props.pageData.currentLocation){
            data.currentLocation = this.props.pageData.currentLocation;
        }
        if(this.props.pageData.aggregateReviewWidget !='undefined'){
            data.aggregateReviewWidget = this.props.pageData.aggregateReviewWidget;
        }
        if(this.props.pageData.anaCountString !='undefined'){
            data.anaCountString = this.props.pageData.anaCountString;
        }
        data.placementFiltersData = this.props;     
        data.aboutCollege = this.props.pageData.aboutCollege;     
        data.fromWhere = 'placementPage';  
        data.anaWidget = {};
        data.allQuestionURL = '';
        data.showFullLoader = false;
        data.showFullSectionLoader = false;
        data.PageHeading = 'Placement - Highest & Average Salary Package';
        if(PageHeading != 'ALL' && this.props.pageData.seoData.headingSuffix){
            data.PageHeading = this.props.pageData.seoData.headingSuffix;
        }
        if(PageHeading && PageHeading !='' && PageHeading != 'ALL'){
            data.PageHeading = PageHeading + ' Placement - Highest & Average Salary Package';
        }

        if(clickType == 'year'){
            data.yearClick=true;
            data.selectedYear = selectedId;
        }else{
            data.courseClick= true;
            data.selectedBaseCourseId = selectedId;
        }
        this.props.storeChildPageDataForPreFilled(data);
    }


    yearList(){
        const {seoUrl} = this.props;
        var yearList = [];
        for(let i  in this.props.data.completionYear){
            let yearObj = {};
            let name = this.props.data.completionYear[i];
            let selectedClass = '';
            if(this.props.data.completionYear[i] === -1){
                name = 'ALL';
            }
            if(this.props.data.completionYear[i] === this.props.selectedYear){
                selectedClass = 'active';
            }
            let pageUrl = addQueryParams('year='+this.props.data.completionYear[i],seoUrl);
            yearObj.id = this.props.data.completionYear[i];
            yearObj.name = name;
            yearObj.url = pageUrl;
            yearObj.clickType = 'year';
            yearObj.pageHeading = null;
            yearList.push(yearObj);
        }
        return yearList;

    }

    baseCourseList(){
        var baseCourseList = [];
        const {placementPageUrl} = this.props;
        for(let i in this.props.data.baseCourseIds){
            let baseCourseObj = {};
            let pageId = this.props.data.baseCourseIds[i];
            if(pageId === 0){
                continue;
            }
            let url ='';
            let name = '';
            let selectedClass = '';

            if(this.props.data.baseCourseIds[i] == -1){
                name ='ALL';
                url = placementPageUrl; 

            }else{
                name = this.props.data.baseCourseObjects[pageId].name;
                url  = placementPageUrl +'-'+this.props.data.baseCourseObjects[pageId].url;
            }
            if(this.props.selectedYear !=-1){
                url +='?year='+this.props.selectedYear;
            }

            baseCourseObj.name = name;
            baseCourseObj.url = url;
            baseCourseObj.id = this.props.data.baseCourseIds[i];
            baseCourseObj.clickType = 'course';
            baseCourseObj.pageHeading = name;

            baseCourseList.push(baseCourseObj);
        }
        return baseCourseList;
    }


    render(){
        return(
            <React.Fragment>
                {this.props.yearClick && this.props.data.baseCourseIds && <CommonTagWidget mainHeading='Select a passout batch' callFunction={this.handlePlacementFilterClick.bind(this)} selectedTag={this.props.selectedYear}  data={this.yearList()} gaTrackingCategory="Pass_out_batch_Naukri_placement" />}
                {this.props.courseClick && this.props.data.completionYear && <CommonTagWidget mainHeading='Select a course to view Salary details:' callFunction={this.handlePlacementFilterClick.bind(this)} selectedTag={this.props.selectedBaseCourseId}  data={this.baseCourseList()} gaTrackingCategory="Base_Course_Naukri_placement" />}
            </React.Fragment>

        )


    }

}

function mapDispatchToProps(dispatch){
    return bindActionCreators({ storeChildPageDataForPreFilled}, dispatch);
}

PlacementPageFilters.defaultProps = {
    yearClick: true,
    courseClick: true
};

export default connect(null,mapDispatchToProps)(PlacementPageFilters);

PlacementPageFilters.propTypes = {
  data: PropTypes.any,
  selectedBaseCourseId: PropTypes.any,
  selectedYear: PropTypes.any,
  seoUrl: PropTypes.any
}