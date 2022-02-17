import React, { Component } from 'react';
import seoConfig from '../config/defaultSeoConfig';
import {addingDomainToUrl} from './../../../utils/commonHelper';
//import EventSchema from './EventSchema';

export default function SeoDetails(state, fromWhere){
        var seoData = {};
        var SHIKSHA_HOME = state.config.SHIKSHA_HOME;
        let shikshaId = "";
        let thumbnailUrl = seoConfig['default'].thumbnailUrl;
        if(fromWhere == 'coursePage' && Object.keys(state.courseData).length){
            seoData = state.courseData.seoData;
            seoData.ampUrl = '';
        }else if(fromWhere == 'categoryPage' && Object.keys(state.categoryData).length){
            seoData = state.categoryData.seoData;
        }else if(fromWhere == 'institutePage' && Object.keys(state.instituteData).length){
            seoData.ampUrl = '';
            seoData = state.instituteData.seoData;
            shikshaId = state.instituteData.listingId;
            thumbnailUrl = (state.instituteData.instituteTopCardData && state.instituteData.instituteTopCardData.logoImageUrl) ? state.instituteData.instituteTopCardData.logoImageUrl : '';
        }else if(fromWhere == 'courseHomePage' && Object.keys(state.courseHomePageData).length){
            seoData = state.courseHomePageData.seoData;
        }else if(fromWhere == 'allChildPage' && Object.keys(state.childPageData).length){
            seoData = state.childPageData.seoData;
            thumbnailUrl = (state.childPageData.instituteTopCardData && state.childPageData.instituteTopCardData.logoImageUrl) ? state.childPageData.instituteTopCardData.logoImageUrl : '';
        }else if(fromWhere == 'rankingPage' && Object.keys(state.rankingPageData).length){
          seoData = state.rankingPageData.seoData;
        }else if(fromWhere == 'examPage' && Object.keys(state.examPageData).length){
            seoData = state.examPageData.seoData;
            shikshaId = state.examPageData.examBasicInfo.id;
        }else if(fromWhere == 'collegePredictor' && Object.keys(state.collegePredictorData).length){
            seoData = state.collegePredictorData.seoData;
        }else if(fromWhere == 'collegePredictorResults' && Object.keys(state.collegePredictorResults).length){
            seoData = state.collegePredictorResults.seoData;
        } else if(fromWhere === 'recommendation'){
            seoData.metaTitle = "Institute Recommendation Page";
        }
        
	return (
              <React.Fragment>
                <title id="seoTitle">{ (seoData && seoData.metaTitle) ? seoData.metaTitle : (fromWhere == '404Page' ? '404 Page' : seoConfig['default'].metaTitle) }</title>
                <meta name="keywords" content={(seoData && seoData.metaKeywords) ? seoData.metaKeywords : seoConfig['default'].metaKeywords }/>
                <meta name="description" content={(seoData && seoData.metaDescription) ? seoData.metaDescription : seoConfig['default'].metaDescription}/>
                <link id="canonicalUrl" rel="canonical" href={(seoData && seoData.canonicalUrl) ? addingDomainToUrl(seoData.canonicalUrl,SHIKSHA_HOME) : SHIKSHA_HOME }/>
                {(seoData && seoData.prevUrl) ? <link rel="prev" href={SHIKSHA_HOME+seoData.prevUrl}/> : null}
                {(seoData && seoData.nextUrl) ? <link rel="next" href={SHIKSHA_HOME+seoData.nextUrl}/> : null}
                {/*((shikshaId == "" || fromWhere != 'examPage') && seoData && typeof seoData.ampUrl != 'undefined' && seoData.ampUrl )  ? <link rel="amphtml" href={SHIKSHA_HOME+seoData.ampUrl}/> : null */}
                {/*(fromWhere == 'examPage' && state.examPageData.contentInfo.importantdates!=null) && <EventSchema data={state.examPageData.contentInfo.importantdates}/>*/}
                <meta property="og:title" content={ (seoData && seoData.metaTitle) ? seoData.metaTitle : (fromWhere == '404Page' ? '404 Page' : seoConfig['default'].metaTitle) }/>
                <meta property="og:url"   content={(seoData && seoData.canonicalUrl) ? addingDomainToUrl(seoData.canonicalUrl,SHIKSHA_HOME) : SHIKSHA_HOME } />
                <meta property="og:image" content={thumbnailUrl} />
                <meta property="fb:app_id" content={seoConfig['default'].fbAPPId}/>
              </React.Fragment>
        )
}
