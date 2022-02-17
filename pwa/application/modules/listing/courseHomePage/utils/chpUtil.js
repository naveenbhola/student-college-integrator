import React from 'react';
import SearchConfig from '../../../search/config/SearchConfig';
import PopularColleges from './../components/PopularColleges';
import Articles from '../../institute/components/ArticlesComponent';
import HomePage from './../components/HomePage';
import PopularCourses from './../components/PopularCourses';
import TopRatedCourse from './../components/TopRatedCourse';
import TopCollegesByLoc from './../components/TopCollegesByLoc';
import AnA from './../../course/components/AnAComponent';
import {DFPBannerTempalte} from './../../../reusable/components/DFPBannerAd';
import Feedback from "../../../common/components/feedback/Feedback";

export function createSections(config , courseHomePageData, isPdfCall, deviceType){
    let sectionData  = new Array();
    let count = 0;
    let resultSet = {};
    if(courseHomePageData.topArticles!=null){
      resultSet.articleData       = courseHomePageData.topArticles;
      resultSet.allArticlePageUrl = courseHomePageData.topArticles.allArticlePageUrl;
    }
    let html = (courseHomePageData.sectionOrder).map(function (key, index){
      switch (key) {
        case 'homePage':
          return (courseHomePageData[key]!=null && Array.isArray(courseHomePageData[key]) && courseHomePageData[key].length > 0 && <HomePage key={index} sectionname={key} displayName={courseHomePageData.displayName} sectiondata={courseHomePageData[key]} isPdfCall={isPdfCall} deviceType={deviceType}/> );
        case 'popularColleges':
          return ((courseHomePageData.popularColleges!=null && (courseHomePageData.popularColleges.wikiData!=null ||  courseHomePageData.popularColleges.tuple!=null)) && <PopularColleges key={index} sectionname={key} sectiondata={courseHomePageData.popularColleges} order={index} labelname={courseHomePageData.displayName} attributeMapping={courseHomePageData.attributeMapping} chpType={courseHomePageData.chpType} isPdfCall={isPdfCall} config={config} deviceType={deviceType}/>)
        case 'popularUGCourses':
          return ((courseHomePageData.popularUGCourses!=null && (courseHomePageData.popularUGCourses.wikiData!=null ||  courseHomePageData.popularUGCourses.tuple!=null)) && <PopularCourses key={index} order={index} courseData={courseHomePageData.popularUGCourses} labelname={courseHomePageData.displayName} sectionname={key} isPdfCall={isPdfCall} config={config} deviceType={deviceType}/>)
        case 'popularPGCourses':
          return ((courseHomePageData.popularPGCourses!=null && (courseHomePageData.popularPGCourses.wikiData!=null || courseHomePageData.popularPGCourses.tuple!=null)) && <PopularCourses key={index} order={index} courseData={courseHomePageData.popularPGCourses} labelname={courseHomePageData.displayName} sectionname={key} isPdfCall={isPdfCall} config={config} deviceType={deviceType}/>)
        case 'popularSpecialization':
          return ((courseHomePageData.popularSpecialization!=null && (courseHomePageData.popularSpecialization.wikiData!=null || courseHomePageData.popularSpecialization.tuple!=null)) && <PopularCourses key={index} courseData={courseHomePageData.popularSpecialization} order={index} labelname={courseHomePageData.displayName} sectionname={key} isPdfCall={isPdfCall} config={config} deviceType={deviceType}/>)
        case 'topRateCourses':
          return ((courseHomePageData.topRateCourses!=null && (courseHomePageData.topRateCourses.wikiData!=null || courseHomePageData.topRateCourses.tuple!=null)) && <TopRatedCourse key={index} sectiondata={courseHomePageData.topRateCourses} order={index} labelname={courseHomePageData.displayName} sectionname={key} isPdfCall={isPdfCall} config={config} deviceType={deviceType}/>);
        case 'topCollegesByLocation':
          return ((courseHomePageData.topCollegesByLocation!=null && (courseHomePageData.topCollegesByLocation.wikiData!=null || courseHomePageData.topCollegesByLocation.tuple!=null)) && <TopCollegesByLoc key={index} sectiondata={courseHomePageData.topCollegesByLocation} order={index} labelname={courseHomePageData.displayName} sectionname={key} isPdfCall={isPdfCall} deviceType={deviceType}/>);
        case 'popularExams':
          return ((courseHomePageData.popularExams!=null && (courseHomePageData.popularExams.wikiData!=null || courseHomePageData.popularExams.tuple!=null)) && <PopularExams key={index} sectiondata={courseHomePageData.popularExams} order={index} labelname={courseHomePageData.displayName} sectionname={key} deviceType={deviceType} />);
      }
      count = index;
    });
    //adding DFP Banner after first widget in CourseHomePage
    if(html.length > 0 && deviceType=='mobile')
    {
      if(html[0]== false || typeof html[0] == 'undefined'){
        html.splice(2, 0, <DFPBannerTempalte bannerPlace="content" key="dfp"/>);
      }
      else{
        html.splice(1, 0, <DFPBannerTempalte bannerPlace="content" key="dfp"/>);
      }
    }
    sectionData.push(html);
    sectionData.push(<Feedback key={'feedback_widget'} pageId={courseHomePageData.chpId} pageType={'CHP'} deviceType={deviceType} feedbackWidgetType={deviceType==='desktop'?'type2':'type1'} />);
    if(courseHomePageData.anaWidget!=null){
      let heading = 'Ask Queries on '+courseHomePageData.displayName;
      sectionData.push(<AnA anaWidget={courseHomePageData.anaWidget} config={config} page = "courseHomePage" key={count+5} heading={heading} deviceType={deviceType}/>)
    }
    if(Object.keys(resultSet).length!=0 ){
      sectionData.push(<Articles config={config} data = {resultSet} page = 'CourseHomePage' key={count} deviceType={deviceType}/>);
    }
    return sectionData;
  }

export function getChpTrackingParams(chpData) {
    var gtmParams = {};
    var data = new Object();
    var beaconTrackData = {};
    var attributeMapping = chpData.attributeMapping;
    if(attributeMapping){
      beaconTrackData['extraData'] = {};
      var beaconEntryHieraries = new Array();
      if(attributeMapping.streamId){
          gtmParams['stream'] = attributeMapping.streamId;
          data['stremId'] = attributeMapping.streamId;
      }
      if(attributeMapping.substreamId){
          gtmParams['substream'] = attributeMapping.substreamId;
          data['substreamId'] = attributeMapping.substreamId;
      }
      if(attributeMapping.specializationId){
          gtmParams['specialization'] = attributeMapping.specializationId;
          data['specializationId'] = attributeMapping.specializationId;
      }
      if(attributeMapping.basecourseId){
          gtmParams['baseCourseId'] = attributeMapping.basecourseId;
          beaconTrackData['extraData']['baseCourseId'] = attributeMapping.basecourseId;
      }
      if(attributeMapping.credentialId){
          gtmParams['credential'] = attributeMapping.credentialId;
          beaconTrackData['extraData']['credential'] = attributeMapping.credentialId;
      }
      if(attributeMapping.educationtypeId){
          gtmParams['educationType'] = attributeMapping.educationtypeId;
          beaconTrackData['extraData']['educationType'] = attributeMapping.educationtypeId;
      }
      if(attributeMapping.deliverymethodId){
          gtmParams['deliveryMethod'] = attributeMapping.deliverymethodId;
          beaconTrackData['extraData']['deliveryMethod'] = attributeMapping.deliverymethodId;
      }
      beaconTrackData['extraData']['childPageIdentifier'] = 'courseHomePage';
      beaconEntryHieraries.push(data);
      beaconTrackData['extraData']['hierarchy'] = beaconEntryHieraries;
      beaconTrackData['extraData']['chpType'] = chpData.chpType;
      beaconTrackData['extraData']['countryId'] = 2;
      gtmParams['pageType'] = 'CHP';
      beaconTrackData['pageIdentifier'] = "courseHomePage";
      beaconTrackData['pageEntityId'] = chpData.chpId ? chpData.chpId : 0;
      return {'gtmParams' : gtmParams, 'beaconTrackData' : beaconTrackData};
    }
}

export function prepareDFPdata(chpData){
  var dfpParams = '';
  var chpAttr = chpData.attributeMapping;
  if(chpAttr){
    var dfpData = new Object();
      dfpData['streams']         = chpAttr.streamId;
      dfpData['substreams']      = chpAttr.substreamId;
      dfpData['baseCourse']      = chpAttr.basecourseId;
      dfpData['specializations'] = chpAttr.specializationId;
      dfpData['deliveryMethod']  = chpAttr.deliverymethodId;
      dfpData['educationType']   = chpAttr.educationtypeId;
      dfpData['credential']      = chpAttr.credentialId;
      dfpParams                  = JSON.stringify(dfpData);
  }
    return dfpParams;
}

export function getEncodedUrlParams(url){
  let paramsObj = {};
  paramsObj['url'] = url;
  return Buffer.from(JSON.stringify(paramsObj)).toString('base64');
}