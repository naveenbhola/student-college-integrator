import React from 'react';
import PropTypes from 'prop-types';
import {Link} from 'react-router-dom';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import './../../course/assets/courseCommon.css';
import './../../../common/assets/linkOCF.css';
import {contentLoaderHelper} from './../../../../utils/ContentLoaderHelper';
import { add_query_params } from './../../../../utils/urlUtility';
import Analytics from './../../../reusable/utils/AnalyticsTracking';
import {storeChildPageDataForPreFilled} from './../../instituteChildPages/actions/AllChildPageAction';
import CommonTagWidget from './../../../common/components/CommonTagWidget';


class LinkOCF extends React.Component {
  constructor(props) {
    super(props);
  }

  trackEvent(eventAction,label='click')
  {
    Analytics.event({category : this.props.gaCategory, action : eventAction, label : label});
  }

  handleClickOnOCF(dataObj, eventAction){
    this.trackEvent(eventAction);
    this.props.storeChildPageDataForPreFilled(contentLoaderHelper(this.props.childPageData,dataObj,"CutOff",this.props.pageUrl));
  }

  examList(){
      const {childPageData,pageUrl} = this.props;
      var examList = [];
      if(childPageData.filters != null){
        let exams = childPageData.filters.exam;
      
        for(let i  in exams){
          let queryParams = '';
          if(childPageData.cutOffPageType == 'icop'){
              queryParams = (this.props.location)?this.props.location.search:"";
          }
          let examObj = {};
          let name = exams[i].name;
          let selectedClass = '';
          if(exams[i] === -1){
              name = 'ALL';
          }
          if(exams[i].checked){
              selectedClass = 'active';
          }
          let redirectUrl = pageUrl+'-'+exams[i].url + queryParams;
          examObj.selectedClass = selectedClass;
          examObj.id = exams[i].id;
          examObj.name = name;
          examObj.url = redirectUrl;
          examObj.clickType = exams[i].filterType;
          examObj.pageHeading = null;
          examList.push(examObj);
        }
      }
      return examList;

  }

  instituteList(){
     const {childPageData,pageUrl} = this.props;
     var instituteList = [];
     if(childPageData.filters != null){
       let institutes = childPageData.filters.institute;
       for(let i  in institutes){
           let examParams = '';
           if(childPageData.cutOffPageType == 'icox'){
              if(childPageData.requestData.appliedFilters && childPageData.requestData.appliedFilters.examIds.length != 0){
                  examParams ='?'+ childPageData.aliasMapping.exam +'[]='+childPageData.requestData.appliedFilters.examIds[0];
              }
           }
           let instituteObj = {};
           let name = institutes[i].name;
           let selectedClass = '';
           if(institutes[i] === -1){
               name = 'ALL';
           }
           if(institutes[i].checked){
               selectedClass = 'active';
           }
           let redirectUrl = institutes[i].url + examParams;
           instituteObj.selectedClass = selectedClass;
           instituteObj.id = institutes[i].id;
           instituteObj.name = name;
           instituteObj.url = redirectUrl;
           instituteObj.clickType = institutes[i].filterType;
           instituteObj.pageHeading = null;
           instituteList.push(instituteObj);
       }
    }
     return instituteList;
  }

  render() {
    const {childPageData,collegeOCF,examOCF} = this.props;
    let selectedTag = null,showExams=false;
    if(this.props.selectedTag){
      selectedTag = this.props.selectedTag;
    }
    if((examOCF && childPageData.filters && childPageData.filters.exam) || this.props.showReset){
      showExams = true;
    }
    if(childPageData.filters){
        return (
          <React.Fragment>
            {collegeOCF  && childPageData.filters && childPageData.filters.institute && <CommonTagWidget selectedTag={selectedTag} view_all={true} onClickViewAll={this.props.onClickViewAll} sectionClass="textOverflow" mainHeading='Select a College' callFunction={this.handleClickOnOCF.bind(this)} data={this.instituteList()} gaCategory="OCF_College" />}
            {showExams && <CommonTagWidget pageUrl={this.props.pageUrl} showReset={this.props.showReset} onClickReset={this.props.onClickReset} selectedTag={selectedTag} mainHeading={examOCF && childPageData.filters && childPageData.filters.exam ? 'Select an Exam' : null} callFunction={this.handleClickOnOCF.bind(this)} data={this.examList()} gaCategory="OCF_Exams" />}
          </React.Fragment>
        ) 
    }
    else return null;
  }
}
function mapDispatchToProps(dispatch){
  return bindActionCreators({ storeChildPageDataForPreFilled}, dispatch);
}

LinkOCF.defaultProps ={
  examOCF : false,
  collegeOCF : false,
  isDesktop : false,
  selectedTag : null,
  showReset: false
};

export default connect(null,mapDispatchToProps)(LinkOCF);


