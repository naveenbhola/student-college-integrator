import React from 'react';
import ExamHomePage from './../components/ExamHomePage';
import ExamSamplePapers from './../components/ExamSamplePapers';
import ExamResults from './../components/ExamResults';
import ExamAnswerKey from './../components/ExamAnswerKey';
import ExamCutOff from './../components/ExamCutOff';
import ExamCounselling from './../components/ExamCounselling';
import ExamPattern from './../components/ExamPattern';
import ExamAdmitCard from './../components/ExamAdmitCard';
import ExamApplicationForm from './../components/ExamApplicationForm';
import ExamCallLetter from './../components/ExamCallLetter';
import ExamImportantDates from './../components/ExamImportantDates';
import ExamPrepTips from './../components/ExamPrepTips';
import ExamSyllabus from './../components/ExamSyllabus';
import ExamNews from './../components/ExamNews';
import ExamSlotBooking from './../components/ExamSlotBooking';
import ExamVacancies from './../components/ExamVacancies';
import SearchConfig from '../../search/config/SearchConfig';
import {DFPBannerTempalte} from './../../reusable/components/DFPBannerAd';
import ExamCarousel from "../components/ExamCarousel";
import ExamInstituteAccepting from "../components/ExamInstituteAccepting";
import ExamInlineTupleCTA from "../components/ExamInlineTupleCTA";
import ExamInlineRegistration from '../components/ExamInlineRegistration';
import SocialSharingBand from './../../common/components/SocialSharingBand';

export function getPageComponents(props, deviceType, trackingKeyList, ampCTATrackingKeys, newCTAConfig, extraObject){
    let {examPageData} = props;
    let pageComponents = new Array();
    let counterKey = 0;
    let groupYear = (examPageData.groupBasicInfo && examPageData.groupBasicInfo['entitiesMappedToGroup'] && examPageData.groupBasicInfo['entitiesMappedToGroup'].year && examPageData.groupBasicInfo['entitiesMappedToGroup'].year[0]) ? ' '+examPageData.groupBasicInfo['entitiesMappedToGroup'].year[0] : '';
    let examName  = examPageData.examBasicInfo.name;
    let labelPrefix = examName+groupYear;
    let isHomePage = false;
    let examId  = examPageData.examBasicInfo.id
    let groupId = examPageData.groupBasicInfo.groupId;

    let sectionList = (examPageData.activeSection != 'homepage') ? [examPageData.activeSection] : examPageData.contentInfo.sectionname;
    let shareWidget = (deviceType === 'mobile') ? <section className="shareWidget" key="sh91"><SocialSharingBand widgetPosition={"EP_"+examPageData.activeSection+"_Bottom"} deviceType={deviceType}/></section> : <div key="sh1999" className="shareWidgetDesk"><SocialSharingBand key={'sh199'} widgetPosition={"EP_"+examPageData.activeSection+"_Bottom"} deviceType={deviceType}/></div>;          
    for(let sectionName of sectionList){
      if(examPageData.contentInfo[sectionName] == null){
        continue;
      }

      let originalSectionName = examPageData.contentInfo.sectionNameMapping[sectionName];
      let labelName = (examPageData.activeSection == 'homepage') ? labelPrefix+' '+originalSectionName : '';

      let childPageProps = {
          newCTAConfig : newCTAConfig[deviceType][sectionName],
          activeSection : examPageData.activeSection,
          groupMapping : examPageData.examBasicInfo.groupMapping,
          selectedGroupName: examPageData.groupBasicInfo.groupName,
          groupId: examPageData.groupBasicInfo.groupId,
          key : counterKey,
          sectionname : sectionName,
          labelName : labelName,
          deviceType : deviceType,
          childPageUrl : examPageData.contentInfo.sectionUrls[sectionName],
          editorialPdfData : examPageData.contentInfo[sectionName].guideUrls,
          originalSectionName : originalSectionName,
          labelPrefix: labelPrefix,
          basePageUrl: examPageData.url,
          viewDetailGACategory : 'EXAM PAGE',
          examName : examName
      };

       switch (sectionName) {
            case 'homepage':
              isHomePage = true;
              {examPageData.contentInfo && examPageData.contentInfo.homepage && examPageData.contentInfo.homepage.wiki && pageComponents.push(<ExamHomePage {...childPageProps} sectiondata={examPageData.contentInfo.homepage.wiki} examName={examName} epUpdates={examPageData.epUpdates} isSamplePapersAvailable={examPageData.contentInfo.sectionUrls.samplepapers!=null} samplePaperUrl={examPageData.contentInfo.sectionUrls['samplepapers']} extraObject={extraObject} location={props.location} />)}
              break;
            case 'samplepapers':
              pageComponents.push(<ExamSamplePapers {...childPageProps} sectiondata={examPageData.contentInfo.samplepapers} defaultText={(examPageData.contentInfo.defaultText && examPageData.contentInfo.defaultText.samplepapers) ? examPageData.contentInfo.defaultText.samplepapers : null} examName={examName} examId={examId} groupId={groupId} trackingKeyList={trackingKeyList} ampCTATrackingKeys={ampCTATrackingKeys}/>);
              break;
            case 'results':
              {examPageData.contentInfo.results && pageComponents.push(<ExamResults {...childPageProps} sectiondata={examPageData.contentInfo.results} />)}
              break;
            case 'answerkey':
              {examPageData.contentInfo && examPageData.contentInfo.answerkey && examPageData.contentInfo.answerkey.wiki && pageComponents.push(<ExamAnswerKey {...childPageProps} sectiondata={examPageData.contentInfo.answerkey.wiki} />)}
              break;
            case 'cutoff':
              {examPageData.contentInfo && examPageData.contentInfo.cutoff && examPageData.contentInfo.cutoff.wiki && pageComponents.push(<ExamCutOff {...childPageProps} sectiondata={examPageData.contentInfo.cutoff.wiki} />)}
              break;
            case 'counselling':
              {examPageData.contentInfo && examPageData.contentInfo.counselling && examPageData.contentInfo.counselling.wiki && pageComponents.push(<ExamCounselling {...childPageProps} sectiondata={examPageData.contentInfo.counselling.wiki} />)}
              break;
            case 'pattern':
              {examPageData.contentInfo && examPageData.contentInfo.pattern && examPageData.contentInfo.pattern.wiki && pageComponents.push(<ExamPattern {...childPageProps} sectiondata={examPageData.contentInfo.pattern.wiki} />)}
              break;
            case 'admitcard':
              {examPageData.contentInfo && examPageData.contentInfo.admitcard && examPageData.contentInfo.admitcard.wiki && pageComponents.push(<ExamAdmitCard {...childPageProps} sectiondata={examPageData.contentInfo.admitcard.wiki} />)}
              break;
            case 'applicationform':
              {examPageData.contentInfo && examPageData.contentInfo.applicationform && pageComponents.push(<ExamApplicationForm {...childPageProps} sectiondata={examPageData.contentInfo.applicationform} />)}
              break;
            case 'callletter':
              {examPageData.contentInfo && examPageData.contentInfo.callletter && examPageData.contentInfo.callletter.wiki && pageComponents.push(<ExamCallLetter {...childPageProps} sectiondata={examPageData.contentInfo.callletter.wiki} />)}
              break;
            case 'importantdates':
              {examPageData.contentInfo && examPageData.contentInfo.importantdates && pageComponents.push(<ExamImportantDates {...childPageProps} sectiondata={examPageData.contentInfo.importantdates} defaultText={examPageData.contentInfo.defaultText.importantdates} />)}
               break;
            case 'preptips':
              {examPageData.contentInfo && examPageData.contentInfo.preptips && pageComponents.push(<ExamPrepTips {...childPageProps} sectiondata={examPageData.contentInfo.preptips} examName={examName} groupId={groupId} trackingKeyList={trackingKeyList} ampCTATrackingKeys={ampCTATrackingKeys}/>)}
              break;
            case 'syllabus':
              {examPageData.contentInfo && examPageData.contentInfo.syllabus && examPageData.contentInfo.syllabus.wiki && pageComponents.push(<ExamSyllabus {...childPageProps} sectiondata={examPageData.contentInfo.syllabus.wiki} />)}
              break;
            case 'slotbooking':
              {examPageData.contentInfo && examPageData.contentInfo.slotbooking && examPageData.contentInfo.slotbooking.wiki && pageComponents.push(<ExamSlotBooking {...childPageProps} sectiondata={examPageData.contentInfo.slotbooking.wiki} />)}
              break;
            case 'news':
              {examPageData.contentInfo && examPageData.contentInfo.news && examPageData.contentInfo.news.wiki && pageComponents.push(<ExamNews {...childPageProps} sectiondata={examPageData.contentInfo.news.wiki} />)}
              break;
            case 'vacancies':
              {examPageData.contentInfo && examPageData.contentInfo.vacancies && examPageData.contentInfo.vacancies.wiki && pageComponents.push(<ExamVacancies {...childPageProps} sectiondata={examPageData.contentInfo.vacancies.wiki} />)}
              break;
          }
          
          if(isHomePage && (counterKey+1) == sectionList.length){
              pageComponents.push(shareWidget);
          } 
      
          if(isHomePage && counterKey === 1 && deviceType === 'mobile'){
	      let instituteAcceptingCount = examPageData.ctpCountAndUrlResponse!=null && examPageData.ctpCountAndUrlResponse.collegeCount!=null?examPageData.ctpCountAndUrlResponse.collegeCount:0;
	      let instituteAcceptingViewAllUrl = examPageData.ctpCountAndUrlResponse!=null && examPageData.ctpCountAndUrlResponse.pageUrl!=null && examPageData.ctpCountAndUrlResponse.pageUrl.url!=null?examPageData.ctpCountAndUrlResponse.pageUrl.url:getInstituteAcceptingViewAllUrl(examId ,examPageData.examBasicInfo.name,examPageData.groupBasicInfo.hierarchyData);
              {examPageData.featuredColleges && pageComponents.push(<ExamCarousel key={'fc'+counterKey} heading="Featured Institute" data={examPageData.featuredColleges} category="ExamPage_Featured_Institute"/>)}
              {examPageData.acceptingWidget!=null && examPageData.acceptingWidget.totalInstituteCount>0 && pageComponents.push(<ExamInstituteAccepting originalSectionName={examPageData.contentInfo.sectionNameMapping[examPageData.activeSection]} activeSection={examPageData.activeSection} key={'eia'+counterKey} acceptingWidget={examPageData.acceptingWidget} examName={examPageData.examBasicInfo.name} viewAllUrl={instituteAcceptingViewAllUrl} instituteAcceptingCount={instituteAcceptingCount}/>)}
          }
          if(!isHomePage){
              pageComponents.push(shareWidget);
              pageComponents.push(<DFPBannerTempalte key={'dfp5'} bannerPlace={deviceType+'_LAA'}/>);
              pageComponents.push(<DFPBannerTempalte key={'dfp6'} bannerPlace={deviceType+'_LAA1'}/>);
              if(deviceType === 'desktop' && examPageData.contentInfo && examPageData.contentInfo.sectionUrls){
                  pageComponents.push(<ExamInlineTupleCTA {...childPageProps} key={'inlineWidget'} isSamplePapersAvailable={examPageData.contentInfo.sectionUrls.samplepapers!=null} samplePaperUrl={examPageData.contentInfo.sectionUrls['samplepapers']} />);
                  {extraObject.showInlineRegistrationForm && pageComponents.push(<ExamInlineRegistration {...childPageProps} key={'examInlineRegistration'} />)}
              }
          }
          
          counterKey++;
    }
    return pageComponents
}

export function getInstituteAcceptingViewAllUrl(examId, labelname, attributeMapping){
      let url = '';
      let stream = '';
      let substream ='';
      let specialization ='';
      for (let key in attributeMapping) {
        if(attributeMapping[key]['primary_hierarchy']==1){
          stream = attributeMapping[key]['stream'];
          substream = attributeMapping[key]['substream'];
          specialization = attributeMapping[key]['specialization'];
        }
      }    
      let queryString = 'q='+encodeURIComponent(labelname);
      if(stream!=null){
         queryString += '&'+SearchConfig.filterCHPMapping['stream']+ '=' + stream;
      }
      if(substream!=null){
         queryString += '&'+SearchConfig.filterCHPMapping['substream']+ '=' + substream;
      }
      if(specialization!=null){
         queryString += '&'+SearchConfig.filterCHPMapping['specialization']+ '=' + specialization;
      }
      url = '/search?'+queryString+'&apk=true' + '&rf=examPage&ex[]='+examId;
      return url;
}

export function prepareBreadCrumbData(breadCrumbData, deviceType){
    if(breadCrumbData == null || breadCrumbData == ''){
      return
    }
    let temData = [];
    breadCrumbData.forEach((value)=>{
        value.isAbsoluteUrl = false;
        if(value.name == 'Home'){
          value.isAbsoluteUrl = (deviceType && deviceType == 'desktop') ? true : false;
        }
        if(value.name != 'Home' && value.url && (value.url.indexOf('-chp') !=-1)){
          value.isAbsoluteUrl = false;
        }else if(value.name != 'Home' && value.url && (value.url.indexOf('-st-') !=-1 || value.url.indexOf('-sb-') !=-1 || value.url.indexOf('-pc-') !=-1)){
          value.isAbsoluteUrl = true;
        }else if(value.name != 'Home' && value.url && (value.url.indexOf('-exam') ==-1 && value.url.indexOf('-exam-') ==-1 && value.url.indexOf('/exams/') == -1)){
          value.isAbsoluteUrl = true;
        }
        temData.push(value);
    });
    return temData;
}
